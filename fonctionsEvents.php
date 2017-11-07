<?php

// Cette fonction renvoi la liste des evenements possibles selon la région actuelle et l'état de la partie
function getEvenementsPossibles($mysqli)
{
	$eventsPossibles = array();
	$variables = $_SESSION["Variables"]; // Variables actuelles de la partie.
	$regionActuelle = $_SESSION["Regions"][$_SESSION["RegionActuelle"]];

	foreach($_SESSION["Evenements"] as $IDEvent => $event)
	{
		// Test du type de région
		if($event["TypeRegion"] != "toutes" && $event["TypeRegion"] != $regionActuelle["Type"])
			continue;

		// Test des conditions
		$conditionsValidees = true;
		for($i = 1; $i <= 2; $i++)
		{
			$type = $event["Condition".$i."_Type"];
			$operateur = $event["Condition".$i."_Operateur"];
			$valeurA = $event["Condition".$i."_ValeurA"];
			$valeurB = $event["Condition".$i."_ValeurB"];

			if($type == null || empty($type))
				continue;

			$a = $valeurA;
			$b = $valeurB;

			if($type == "variable")
				$a = getVariable($valeurA);
		
			$conditionValide = true;
			switch($operateur)
			{
				case ">":
					$conditionValide = ($a > $b);
					break;
				case ">=":
					$conditionValide = ($a >= $b);
					break;
				case "<":
					$conditionValide = ($a < $b);
					break;
				case "<=":
					$conditionValide = ($a <= $b);
					break;
				case "==":
					$conditionValide = ($a == $b);
					break;
				case "!=":
					$conditionValide = ($a != $b);
					break;
			}

			if(!$conditionValide)
			{
				$conditionsValidees = false;
				break;
			}	
		}

		$conditionScriptee = getConditionsScripteesEvenement($IDEvent);

		if($conditionsValidees && $conditionScriptee)
			$eventsPossibles[$IDEvent] = $event;
	}

	return $eventsPossibles;
}

// Cette méthode permet de scripter une condition un peu trop complexe pour etre configurée en bdd
function getConditionsScripteesEvenement($IDEvent)
{
	$conditionScriptee = true;
	switch($IDEvent)
	{
		default:
			break;
	}
	return $conditionScriptee;
}

// Retourne un event choisi aléatoirement parmi une liste, en prenant en compte leur poids
function choisiEvenementDansListe($listeEvents)
{
	$poidTotal = 0;
	foreach($listeEvents as $IDEvent=>$event)
		$poidTotal += $event["Poids"];

	$rand = mt_rand(1,$poidTotal);
	$cumul = 0;

	foreach($listeEvents as $IDEvent=>$event)
	{
		$poid = $event["Poids"];
		if($rand <= $poid+$cumul)
			return $event;
		else
			$cumul += $poid;
	}
}

function gainItem($mysqli,$IDTypeItem,$parametre1)
{
	return ajouterItem($mysqli,$_SESSION["IDPartieEnCours"],$IDTypeItem,$_SESSION["IDPersonnage"],"personnage",$parametre1);
}

// Effectue le resultat d'un evenement simple (sans choix multiple)
function effectueResultatEvenementSimple($mysqli,$event)
{
	$IDEvent = $event["ID"];
	$IDPartie = $_SESSION["IDPartieEnCours"];

	$_SESSION["PopupEvenement"] = array();
	$_SESSION["PopupEvenement"]["GainsItems"] = array();


	switch($IDEvent)
	{
		case 1:// Un truc qui brille
		{
			$idItem = gainItem($mysqli,3,0); // Pelle
			$i = getVariable(1);
			setVariable($mysqli,$IDPartie,1,($i>=0)?$i+1:1);
			$_SESSION["PopupEvenement"]["GainsItems"][$idItem] = 3;
			break;
		}
		case 2:// La vieille marmitte
		{
			$idItem = gainItem($mysqli,32,0); // Marmitte
			setVariable($mysqli,$IDPartie,2,1);
			$_SESSION["PopupEvenement"]["GainsItems"][$idItem] = 32;
			break;
		}
		case 3:// Une toile rudimentaire
		{
			$idItem = gainItem($mysqli,31,0); // Toile déchirée
			setVariable($mysqli,$IDPartie,3,1);
			$_SESSION["PopupEvenement"]["GainsItems"][$idItem] = 31;
			break;
		}
		case 4:// Ho la belle coco
		{
			$idItem = gainItem($mysqli,21,0); // Noix de coco
			$_SESSION["PopupEvenement"]["GainsItems"][$idItem] = 21;
			break;
		}
	}

	// Popup Event
	$_SESSION["PopupEvenement"]["Titre"] = $event["TitreFR"];
	$_SESSION["PopupEvenement"]["Message"] = $event["DescriptionFR"];
	$_SESSION["PopupEvenement"]["TexteReponse"] = $event["TexteChoix1"];
}

// Effectue le resultat d'un evenement complexe (à choix multiple)
function effectueResultatEvenementComplexe($mysqli,$event)
{
	$IDEvent = $event["ID"];
	$IDPartie = $_SESSION["IDPartieEnCours"];

	$_SESSION["PopupEvenementComplexe"] = array();

	$_SESSION["PopupEvenementComplexe"]["Titre"] = $event["TitreFR"];
	$_SESSION["PopupEvenementComplexe"]["Message"] = $event["DescriptionFR"];
	$_SESSION["PopupEvenementComplexe"]["IDEvent"] = $IDEvent;
	$_SESSION["PopupEvenementComplexe"]["Choix"] = array();
	$_SESSION["PopupEvenementComplexe"]["Choix"][1] = $event["TexteChoix1"];
	$_SESSION["PopupEvenementComplexe"]["Choix"][2] = $event["TexteChoix2"];
	$_SESSION["PopupEvenementComplexe"]["Choix"][3] = $event["TexteChoix3"];

	// Ici, on pourra filtrer les events et les réponses selon d'autre elements, par exemple la présence d'objet en inventaire ou le PJ joué, et ainsi ajouter/retirer des réponses possibles.
	switch($IDEvent)
	{
	}
}

function effectueResultatChoixEvenementComplexe($mysqli,$IDEvent,$IDReponse)
{
	$event = $_SESSION["Evenements"][$IDEvent];

	$_SESSION["PopupEvenement"] = array();
	$_SESSION["PopupEvenement"]["GainsItems"] = array();
	$_SESSION["PopupEvenement"]["Titre"] = $event["TitreFR"];
	$message = lang("Evenement_".$IDEvent."_Message_Choix_".$IDReponse); // Par défaut pour les choix à conséquences uniques.
	$texteReponse = lang("Daccord"); // Par défaut
	$_SESSION["PopupEvenement"]["TexteReponse"] = "D'accord";

	$de100 = mt_rand(1,100);

	switch($IDEvent)
	{
		case 5: // Sous les cocotiers
		{
			switch($IDReponse)
			{
				case 1: // Grimper en haut
				{
					if($de100 <= 30) // Gains 1 a 3 noix
					{
						$de3 = mt_rand(1,3);
						for($i = 0; $i < $de3; $i++)
							$_SESSION["PopupEvenement"]["GainsItems"][gainItem($mysqli,21,0)] = 21; // Noix de coco

						$message = ($de3 == 1) ? lang("Evenement_".$IDEvent."_Message_Choix_".$IDReponse."_A1") : str_replace("%Number%", $i, lang("Evenement_".$IDEvent."_Message_Choix_".$IDReponse."_AX"));	
						$texteReponse = lang("Miam!");		
					}
					else
					{
						//TODO: PERTE 1PV
						$message = lang("Evenement_".$IDEvent."_Message_Choix_".$IDReponse."_B");
						$texteReponse = lang("Aie!");
					}
					break;
				}
				case 2: // Donner un coup
					if($de100 <= 50) // Gains 1  noix
					{
						$_SESSION["PopupEvenement"]["GainsItems"][gainItem($mysqli,21,0)] = 21; // Noix de coco	
						$message = lang("Evenement_".$IDEvent."_Message_Choix_".$IDReponse."_A");
						$texteReponse = lang("Miam!");
					}
					else // Perte 3pv et assomé
					{
						//TODO: PERTE 13PV
						//TODO: STUN
						$message = lang("Evenement_".$IDEvent."_Message_Choix_".$IDReponse."_B");
						$texteReponse = lang("Aie!");
					}
					break;
				case 3:// Partir
					break;
			}
			break;
		}
	}

	
	$_SESSION["PopupEvenement"]["Message"] = $message;
	$_SESSION["PopupEvenement"]["TexteReponse"] = $texteReponse;
	
}
	
?>