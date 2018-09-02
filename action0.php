<?php
switch($idAction)
{
	case 0: // Rejoindre une partie
		if(isset($_POST['idPerso']) && isset($_POST['idPartie']) && $_SESSION['IDPartieEnCours'] == -1)
		{
			$idPerso = $_POST['idPerso'];
			$idPartie = $_POST['idPartie'];

			// Verification de la disponibilité du personnage
			$requete = "SELECT * FROM ".$PT."parties WHERE ID = '".$idPartie."' AND Etat = 'en_creation' LIMIT 1";
			$retour = mysqli_query($mysqli,$requete);
			if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

			if(mysqli_num_rows($retour))
			{
				$partie = mysqli_fetch_assoc($retour);
				if($partie['Joueur'.$idPerso] < 0)
				{
					// Partie et joueur disponible
					$requete = "UPDATE ".$PT."parties SET Joueur".$idPerso." = '".$_SESSION['ID']."' WHERE ID = '".$idPartie."'";
					$retour = mysqli_query($mysqli,$requete);
					if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

					$requete = "UPDATE ".$PT."accounts SET IDPartieEnCours = '".$idPartie."', NombrePartiesJouees = NombrePartiesJouees+1 WHERE ID = '".$_SESSION['ID']."'";
					$retour = mysqli_query($mysqli,$requete);
					if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

					// Update Session
					$_SESSION['IDPartieEnCours'] = $idPartie;
					$requete = "SELECT * FROM parties WHERE ID = '".$idPartie."' LIMIT 1";
					$retour = mysqli_query($mysqli,$requete);
					if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));
					$partie = mysqli_fetch_assoc($retour);
					$_SESSION['PartieEnCours'] = $partie;
					$_SESSION['IDPersoActuel'] = $idPerso;

					// Verification des conditions de démarrage de la partie
					$pret = true;
					for($i = 1; $i <= count($_SESSION['Heros']); $i++)
					{
						if($partie['Joueur'.$i] <= 0)
						{
							$pret = false;
							break;
						}
					}

					if($pret && false) // Demarrage partie manuel
					{
						// Démarrage de la partie
						$ref = "demarragePartie.php";
					}
					else
						$ref = "lobby.php?";
				}
				else
					$ref = "lobby.php?e=0&t=0";
			}
			else
				$ref = "lobby.php?e=0&t=1";
		}
		else
			trigger_error("Error Tricherie");
		break;
	case 1: // Annuler selection personnage
	{
		$ref = "lobby.php";
		// Verification de la disponibilité du personnage
		$requete = "SELECT * FROM ".$PT."parties WHERE ID = '".$_SESSION['IDPartieEnCours']."' AND Etat = 'en_creation' LIMIT 1";
		$retour = mysqli_query($requete);
		if (!$retour) die('Requête invalide : ' . mysqli_error());

		if(mysqli_num_rows($retour))
		{
			$requete = "UPDATE ".$PT."parties SET Joueur".$_SESSION['IDPersoActuel']." = '-1' WHERE ID = '".$_SESSION['IDPartieEnCours']."'";
			$retour = mysqli_query($requete);
			if (!$retour) die('Requête invalide : ' . mysqli_error());

			$requete = "UPDATE ".$PT."accounts SET IDPartieEnCours = '-1', NombrePartiesJouees = NombrePartiesJouees-1 WHERE ID = '".$_SESSION['ID']."'";
			$retour = mysqli_query($requete);
			if (!$retour) die('Requête invalide : ' . mysqli_error());

			// Update Session
			$_SESSION['IDPartieEnCours'] = -1;
			$_SESSION['PartieEnCours'] = array();
			$_SESSION['IDPersoActuel'] = -1;
		}
	}
		break;
	case 2: // Voyager
	{
		if(isset($_POST['idRegion']) && isset($_SESSION['Regions'][$_POST['idRegion']]))
		{
			$idRegionCible = $_POST['idRegion'];

			// Verification des PM
			if($_SESSION['PmActuel'] >= getCoutDeplacement())
			{
				// Verification de la liaison entre la zone
				$regionCible = $_SESSION['Regions'][$_POST['idRegion']];
				$idRegionActuelle = $_SESSION['RegionActuelle'];
				if($regionCible['Lien1'] == $idRegionActuelle || $regionCible['Lien2'] == $idRegionActuelle || $regionCible['Lien3'] == $idRegionActuelle || $regionCible['Lien4'] == $idRegionActuelle || $regionCible['Lien5'] == $idRegionActuelle)
				{
					// Voyage accepté
					$requete = "UPDATE ".$PT."personnages SET RegionActuelle = '".$idRegionCible."' WHERE IDHeros = '".$_SESSION['IDPersonnage']."' AND IDPartie = ".$_SESSION["IDPartieEnCours"];
					$retour = mysqli_query($mysqli,$requete);
					if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

					$_SESSION['RegionActuelle'] = $idRegionCible;
					
					updateCarac($mysqli,$_SESSION["IDPersonnage"],"Pm",-getCoutDeplacement());
				}
			}
			else
				trigger_error("Tentative de voyage: PM insuffisants");
		}
		else
			trigger_error("Tentative de voyage: Region innexistante");
	}
	break;
	case 3: // Explorer la région
	{
		if($_SESSION["PaActuel"] >= getCoutExploration())
		{
			$eventsPossibles = getEvenementsPossibles($mysqli);
			if(!empty($eventsPossibles))
			{
				$eventChoisi = choisiEvenementDansListe($eventsPossibles);
				if($eventChoisi["EstSimple"])
					effectueResultatEvenementSimple($mysqli,$eventChoisi);
				else
					effectueResultatEvenementComplexe($mysqli,$eventChoisi);
			}
			else
				$_SESSION["Message"] = "Malheuresement, vous n'avez rien trouvé.";

			updateCarac($mysqli,$_SESSION["IDPersonnage"],"Pa",-getCoutExploration());
		}
		else
			$_SESSION["Message"] = "Vous n'avez plus assez d'actions pour faire ça !";
	}
	break;
	case 4: // Répondre a un evenement complexe
	{
		$IDEvent = $_POST["IDEvent"];
		$IDReponse = $_POST["IDReponse"];

		$_SESSION["PopupEvenementComplexe"] = array(); // Destruction de la popup de choix complexe
		effectueResultatChoixEvenementComplexe($mysqli,$IDEvent,$IDReponse);
	}
	break;
	case 5: //Etre prêt a passer au cycle suivant
	{
		$requete = "UPDATE ".$PT."personnages SET PretCycleSuivant='o' WHERE IDHeros = '".$_SESSION['IDPersonnage']."' AND IDPartie = ".$_SESSION["IDPartieEnCours"];
		$retour = mysqli_query($mysqli,$requete);
		if (!$retour) die('Requête invalide : ' . mysqli_error());
	}
	break;
	case 6://Pecher dans un banc de poisson
	{
		$IDBanc = getIDLieuDeTypeDansRegion(1);
		if($IDBanc != -1)
		{
			$banc = $_SESSION["LieuxDansRegion"][$IDBanc];
			//Verification de la decouverte
			if(isset($_SESSION["LieuxDecouverts"][$IDBanc]))
			{
				//Verification des PA
				$coutPeche = getCoutPeche();
				if($_SESSION["PaActuel"] >= getCoutPeche())
				{
					//Decrementation des PA
					updateMyCarac($mysqli,"Pa",-$coutPeche);

					//Tout est ok: Verification du nombre de poissons dans le banc
					$_SESSION["PopupEvenement"] = array();
					$nbPoissons = $banc["Parametres"]["NbPoissons"];
					$poissonPeche = 0;
					if($nbPoissons > 0)
					{
						//Test de pêche
						$rand = mt_rand(1,100);
						$chancesSucces = CHANCE_BASE_PECHE;

						if(nbItemsDansInventaire(2) > 0)//Harpon?
							$chancesSucces += BOOST_HARPON_PECHE;

						if($rand <= $chancesSucces)
							$poissonPeche = 1;

						//Test tortue
						if($poissonPeche > 0)
						{
							$randTortue = mt_rand(1,100);
							if($randTortue <= CHANCE_PECHE_TORTUE)
								$poissonPeche = 2;
						}
					}
					
					//Resultat
					if($poissonPeche > 0)
					{
						if($poissonPeche == 1) // Poisson cru
						{
							//Ajout inventaire
							$IDItemGain = ajouterItem($mysqli,$IDPartie,4,$_SESSION["IDPersonnage"],"personnage");
							$_SESSION["PopupEvenement"]["Titre"] = lang("Action_6_PechePoissonCru_Titre");
							$_SESSION["PopupEvenement"]["Message"] = lang("Action_6_PechePoissonCru_Description");
							$_SESSION["PopupEvenement"]["GainsItems"][$IDItemGain] = 4;

						}
						else if($poissonPeche == 2) // Tortue
						{
							//Ajout inventaire
							$IDItemGain = ajouterItem($mysqli,$IDPartie,9,$_SESSION["IDPersonnage"],"personnage");
							$_SESSION["PopupEvenement"]["Titre"] = lang("Action_6_PecheTortue_Titre");
							$_SESSION["PopupEvenement"]["Message"] = lang("Action_6_PecheTortue_Description");
							$_SESSION["PopupEvenement"]["GainsItems"][$IDItemGain] = 9;
						}

						//Modification du banc
						$requete = "UPDATE ".$PT."parametresBancsPoissons SET NbPoissons = NbPoissons-1 WHERE IDLieu = ".$banc["ID"];
						$retour = mysqli_query($mysqli,$requete);
						if (!$retour) die('Requête invalide : ' . mysqli_error());
					}
					else
					{
						//Peche infructeuse
						$_SESSION["PopupEvenement"]["Titre"] = lang("Action_6_PecheInfructueuse_Titre");
						$_SESSION["PopupEvenement"]["Message"] = lang("Action_6_PecheInfructueuse_Description");
					}
				}
				else
					$_SESSION["Message"] = lang("ErreurAPPourAction");

			}
			else
				$_SESSION["Message"] = "Erreur Pêche: Banc de poisson non découvert.";
		}
		else
			$_SESSION["Message"] = "Erreur Pêche: Banc de poisson introuvable.";
	}
	break;
}
?>