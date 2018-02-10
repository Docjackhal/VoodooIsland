<?php
function updateInformationsSession()
{
	global $mysql_ip, $mysql_user, $mysql_password, $base, $PT;

	// Connexion a la base
	$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
	mysqli_set_charset($mysqli, "utf8");

	$IDPartie = $_SESSION['IDPartieEnCours'];

	// Caract�ristiques joueurs
	$requete = "SELECT * FROM ".$PT."personnages WHERE Joueur = '".$_SESSION['ID']."' AND IDPartie = '".$IDPartie."' LIMIT 1";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));
	$personnage = mysqli_fetch_assoc($retour);

	$_SESSION['FaimActuel'] = $personnage['FaimActuel'];
	$_SESSION['SoifActuel'] = $personnage['SoifActuel'];
	$_SESSION['FatigueActuel'] = $personnage['FatigueActuel'];
	$_SESSION['PvActuel'] = $personnage['PvActuel'];
	$_SESSION['PaActuel'] = $personnage['PaActuel'];
	$_SESSION['PmActuel'] = $personnage['PmActuel'];
	$_SESSION['MPActuel'] = $personnage['MPActuel'];

	$_SESSION['FaimMax'] = $personnage['FaimMax'];
	$_SESSION['SoifMax'] = $personnage['SoifMax'];
	$_SESSION['FatigueMax'] = $personnage['FatigueMax'];
	$_SESSION['PvMax'] = $personnage['PvMax'];
	$_SESSION['PaMax'] = $personnage['PaMax'];
	$_SESSION['PmMax'] = $personnage['PmMax'];
	$_SESSION['MPMax'] = $personnage['MPMax'];

	$_SESSION['RegionActuelle'] = $personnage['RegionActuelle'];
	$_SESSION['DateArriveeLieu'] = $personnage['DateArriveeLieu'];
	$_SESSION['IDPersonnage'] = $personnage['IDHeros'];
	$_SESSION['EstVoodoo'] = ($personnage['EstVoodoo'] == 'o');
	$_SESSION['EstCapture'] = ($personnage['EstCapture'] == 'o');
	$_SESSION['DateCapture'] = $personnage['DateCapture'];
	$_SESSION['RiteEnCours'] = ($personnage['RiteEnCours'] == 'o');
	$_SESSION['DateDebutRite'] = $personnage['DateDebutRite'];
	$_SESSION['PretCycleSuivant'] = ($personnage['PretCycleSuivant'] == 'o');

	$IDRegion = $_SESSION['RegionActuelle'];

	// Cycle et jours
	$requete = "SELECT * FROM ".$PT."parties WHERE ID = '".$IDPartie."' LIMIT 1";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requ�te invalide : ' . mysqli_error($mysqli));
	$partie = mysqli_fetch_assoc($retour);

	$_SESSION['PartieEnCours']['Cycle'] = $partie['Cycle'];
	$_SESSION['PartieEnCours']['Jour'] = $partie['Jour'];

	// Donn�es Tchat
	$requete = "SELECT Auteur, Message, Canal, DateEnvoie FROM ".$PT."tchats WHERE IDPartie = '".$IDPartie."' AND (Canal = '".$_SESSION['RegionActuelle']."' OR Canal = 0) AND DateEnvoie >= '".$_SESSION['DateArriveeLieu']."' ORDER BY DateEnvoie DESC";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requ�te invalide : ' . mysql_error($mysqli));
	
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

	// MAJ des variables de la partie
	$_SESSION["Variables"] = array();
	$requete = "SELECT IDVariable,Valeur FROM ".$PT."variables WHERE IDPartie = ".$IDPartie;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requ�te invalide : '.$requete . mysqli_error($mysqli));
	while($variable = mysqli_fetch_assoc($retour))
		$_SESSION["Variables"][$variable["IDVariable"]] = $variable["Valeur"];


	// R�cup�ration de la liste des lieux d�couverts par le joueur
	$_SESSION["LieuxDecouverts"] = array();
	$requete = "SELECT IDLieu FROM ".$PT."lieuxDecouverts WHERE IDPersonnage = ".$_SESSION['IDPersonnage'];
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requ�te invalide : '.$requete . mysql_error($mysqli));
	while($lieuDecouvert = mysqli_fetch_assoc($retour))
		$_SESSION["LieuxDecouverts"][$lieuDecouvert["IDLieu"]] = true;
	
	// R�cup�ration Lieux R�gion
	$_SESSION["LieuxDansRegion"] = array();
	$requete = "SELECT * FROM ".$PT."lieux WHERE IDPartie = ".$IDPartie." AND IDRegion = ".$IDRegion;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requ�te invalide : '.$requete . mysqli_error($mysqli));

	while($lieu = mysqli_fetch_assoc($retour))
	{
		$IDLieu = $lieu["ID"];
		$_SESSION["LieuxDansRegion"][$IDLieu] = $lieu;

		if($lieu["EtatDecouverte"] == "ADecouvrir"  && empty($_SESSION["LieuxDecouverts"][$IDLieu]))
			continue;

		// R�cup�ration des infos propres aux types de lieu (si d�couvert)
		switch($lieu["IDTypeLieu"])
		{
			case 1: // Banc de poisson
			{	
				$requete2 = "SELECT * FROM ".$PT."parametresBancsPoissons WHERE IDLieu = ".$IDLieu;
				$retour2 = mysqli_query($mysqli,$requete2);
				if (!$retour2) 
					die('Requ�te invalide : '.$requete2 . mysqli_error($mysqli));
				$_SESSION["LieuxDansRegion"][$IDLieu]["Parametres"] = mysqli_fetch_assoc($retour2);
			}
			break;
			case 5: // Sources d'eau
			{	
				$requete2 = "SELECT * FROM ".$PT."parametresSourcesEau WHERE IDLieu = ".$IDLieu;
				$retour2 = mysqli_query($mysqli,$requete2);
				if (!$retour2) 
					die('Requ�te invalide : '.$requete2 . mysqli_error($mysqli));
				$_SESSION["LieuxDansRegion"][$IDLieu]["Parametres"] = mysqli_fetch_assoc($retour2);
			}
			break;
		}

		$_SESSION["LieuxDansRegion"][$IDLieu]["NomFR"] = $_SESSION["TypeLieux"][$lieu["IDTypeLieu"]]["NomFR"];
	}

	// R�cup�ration des personnages dans la r�gion
	$_SESSION["PersonnagesDansRegion"] = array();
	$requete = "SELECT IDHeros FROM ".$PT."personnages WHERE IDPartie = ".$IDPartie." AND RegionActuelle = ".$IDRegion;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requ�te invalide : '.$requete . mysql_error($mysqli));
	while($personnage = mysqli_fetch_assoc($retour))
	{
		$IDHeros = $personnage["IDHeros"];
		$_SESSION["PersonnagesDansRegion"][$IDHeros] = $personnage;
	}

	// R�cup�ration des objets dans l'inventaire du joueur, tri�s par IDTypeItem
	$_SESSION["Inventaire"] = array();
	$requete = "SELECT * FROM ".$PT."items WHERE IDPartie = ".$IDPartie." AND TypeInventaire = 'personnage' AND IDProprietaire = ".$_SESSION['IDPersonnage'];
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requ�te invalide : '.$requete . mysql_error($mysqli));
	while($item = mysqli_fetch_assoc($retour))
	{
		$IDItem = $item["ID"];
		$IDTypeItem = $item["IDTypeItem"];

		if(empty($_SESSION["Inventaire"][$IDTypeItem]))
			$_SESSION["Inventaire"][$IDTypeItem] = array();

		$_SESSION["Inventaire"][$IDTypeItem][$IDItem] = $item;
	}

	// R�cup�ration des conditions
	$_SESSION["Conditions"] = array();
	$requete = "SELECT * FROM ".$PT."conditions WHERE IDPartie = ".$IDPartie." AND IDPersonnage = ".$_SESSION['IDPersonnage'];
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requ�te invalide : '.$requete . mysql_error($mysqli));
	while($condition = mysqli_fetch_assoc($retour))
		$_SESSION["Conditions"][$condition["IDCondition"]] = $condition;
}
?>