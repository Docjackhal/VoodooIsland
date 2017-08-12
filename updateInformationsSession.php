<?php
function updateInformationsSession()
{
	global $mysql_ip, $mysql_user, $mysql_password, $base, $PT;

	// Connexion a la base
	$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
	mysqli_set_charset($mysqli, "ANSI");

	$IDPartie = $_SESSION['IDPartieEnCours'];

	// Caractéristiques joueurs
	$requete = "SELECT IDHeros, FaimActuel, SoifActuel, FatigueActuel, PvActuel, PaActuel, PmActuel, RegionActuelle, DateArriveeLieu FROM ".$PT."personnages WHERE Joueur = '".$_SESSION['ID']."' AND IDPartie = '".$IDPartie."' LIMIT 1";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('RequÃªte invalide : ' . mysqli_error($mysqli));
	$personnage = mysqli_fetch_assoc($retour);

	$_SESSION['FaimActuel'] = $personnage['FaimActuel'];
	$_SESSION['SoifActuel'] = $personnage['SoifActuel'];
	$_SESSION['FatigueActuel'] = $personnage['FatigueActuel'];
	$_SESSION['PvActuel'] = $personnage['PvActuel'];
	$_SESSION['PaActuel'] = $personnage['PaActuel'];
	$_SESSION['PmActuel'] = $personnage['PmActuel'];
	$_SESSION['RegionActuelle'] = $personnage['RegionActuelle'];
	$_SESSION['DateArriveeLieu'] = $personnage['DateArriveeLieu'];
	$_SESSION['IDPersonnage'] = $personnage['IDHeros'];

	$IDRegion = $_SESSION['RegionActuelle'];

	// Cycle et jours
	$requete = "SELECT * FROM ".$PT."parties WHERE ID = '".$IDPartie."' LIMIT 1";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requéte invalide : ' . mysqli_error($mysqli));
	$partie = mysqli_fetch_assoc($retour);

	$_SESSION['PartieEnCours']['Cycle'] = $partie['Cycle'];
	$_SESSION['PartieEnCours']['Jour'] = $partie['Jour'];

	// Données Tchat
	$requete = "SELECT Auteur, Message, Canal, DateEnvoie FROM ".$PT."tchats WHERE IDPartie = '".$IDPartie."' AND (Canal = '".$_SESSION['RegionActuelle']."' OR Canal = 0) AND DateEnvoie >= '".$_SESSION['DateArriveeLieu']."' ORDER BY DateEnvoie DESC";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requête invalide : ' . mysql_error($mysqli));
	
	$_SESSION['Tchats'] = array();

	if(mysqli_num_rows($retour))
	{
		while($message = mysqli_fetch_assoc($retour))
		{
			if(!isset($_SESSION['Tchats'][$message['Canal']]))
				$_SESSION['Tchats'][$message['Canal']] = array();

			$_SESSION['Tchats'][$message['Canal']][] = $message;
		}
	}
	
	// Récupération Lieux Région
	$_SESSION["LieuxDansRegion"] = array();
	$requete = "SELECT * FROM ".$PT."lieux WHERE IDPartie = ".$IDPartie." AND IDRegion = ".$IDRegion;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requête invalide : '.$requete . mysql_error($mysqli));

	while($lieu = mysqli_fetch_assoc($retour))
	{
		$IDLieu = $lieu["ID"];
		$_SESSION["LieuxDansRegion"][$IDLieu] = $lieu;

		// Récupération des infos propres aux types de lieu
		switch($lieu["IDTypeLieu"])
		{
			case 1: // Banc de poisson
			{	
				$requete2 = "SELECT * FROM ".$PT."parametresBancsPoissons WHERE IDLieu = ".$IDLieu;
				$retour2 = mysqli_query($mysqli,$requete2);
				if (!$retour2) 
					die('Requête invalide : '.$requete2 . mysql_error($mysqli));
				$_SESSION["LieuxDansRegion"][$IDLieu]["Parametres"] = mysqli_fetch_assoc($retour2);
			}
			break;
			case 5: // Sources d'eau
			{	
				$requete2 = "SELECT * FROM ".$PT."parametresSourcesEau WHERE IDLieu = ".$IDLieu;
				$retour2 = mysqli_query($mysqli,$requete2);
				if (!$retour2) 
					die('Requête invalide : '.$requete2 . mysql_error($mysqli));
				$_SESSION["LieuxDansRegion"][$IDLieu]["Parametres"] = mysqli_fetch_assoc($retour2);
			}
			break;
		}
	}

	// Récupération des personnages dans la région
	$_SESSION["PersonnagesDansRegion"] = array();
	$requete = "SELECT IDHeros FROM ".$PT."personnages WHERE IDPartie = ".$IDPartie." AND RegionActuelle = ".$IDRegion;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requête invalide : '.$requete . mysql_error($mysqli));
	while($personnage = mysqli_fetch_assoc($retour))
	{
		$IDHeros = $personnage["IDHeros"];
		$_SESSION["PersonnagesDansRegion"][$IDHeros] = $personnage;
	}
}
?>