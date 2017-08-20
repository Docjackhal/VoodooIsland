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
		if($cumul < $poid)
			return $event;
		else
			$cumul += $poid;
	}
}

function gainItem($mysqli,$IDTypeItem,$parametre1)
{
	ajouterItem($mysqli,$_SESSION["IDPartieEnCours"],3,$_SESSION["IDPersonnage"],"personnage",$parametre1);
}

// Effectue le resultat d'un evenement simple (sans choix multiple)
function effectueResultatEvenementSimple($mysqli,$event)
{
	$IDEvent = $event["ID"];
	$IDPartie = $_SESSION["IDPartieEnCours"];

	$_SESSION["PopupEvenement"] = array();
	switch($IDEvent)
	{
		case 1:// Un truc qui brille
		{
			gainItem($mysqli,3,0); // Pelle
			$i = getVariable(1);
			setVariable($mysqli,$IDPartie,1,($i>=0)?$i++:1);
			break;
		}
		case 2:// La vieille marmitte
		{
			gainItem($mysqli,32,0); // Marmitte
			setVariable($mysqli,$IDPartie,2,1);
			break;
		}
	}

	// Popup Event
	$_SESSION["PopupEvenement"]["Titre"] = $event["TitreFR"];
	$_SESSION["PopupEvenement"]["Message"] = $event["DescriptionFR"];
}
	
?>