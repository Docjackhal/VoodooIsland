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
			if($_SESSION['PmActuel'] > 0)
			{
				// Verification de la liaison entre la zone
				$regionCible = $_SESSION['Regions'][$_POST['idRegion']];
				$idRegionActuelle = $_SESSION['RegionActuelle'];
				if($regionCible['Lien1'] == $idRegionActuelle || $regionCible['Lien2'] == $idRegionActuelle || $regionCible['Lien3'] == $idRegionActuelle || $regionCible['Lien4'] == $idRegionActuelle || $regionCible['Lien5'] == $idRegionActuelle)
				{
					// Voyage accepté
					$requete = "UPDATE ".$PT."personnages SET PmActuel = PmActuel -1, RegionActuelle = '".$idRegionCible."' WHERE IDHeros = '".$_SESSION['IDPersonnage']."' AND IDPartie = ".$_SESSION["IDPartieEnCours"];
					$retour = mysqli_query($mysqli,$requete);
					if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

					$_SESSION['RegionActuelle'] = $idRegionCible;
					$_SESSION['PmActuel']--;
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
		$_SESSION["Message"] = "test message";
	}
	break;
}
?>