<?php
include_once("fonctionsLangue.php");
include_once("fonctionsGlobales.php");
include_once("fonctionsTchats.php");
include_once("fonctionsItems.php");
include_once("prive/config.php");

function updateInformationsSession()
{
	global $mysql_ip, $mysql_user, $mysql_password, $base, $PT;

	// Connexion a la base
	$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
	mysqli_set_charset($mysqli, "utf8");

	$IDPartie = $_SESSION['IDPartieEnCours'];

	if($IDPartie == null)
		header("Location:deconnexion.php");

	// Caractéristiques joueurs
	$requete = "SELECT * FROM ".$PT."personnages WHERE Joueur = '".$_SESSION['ID']."' AND IDPartie = '".$IDPartie."' LIMIT 1";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('RequÃªte invalide : ' . mysqli_error($mysqli));
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
	$_SESSION["DateDerniereUpdateTchat"] = $personnage["DateDerniereUpdateTchat"];

	$IDRegion = $_SESSION['RegionActuelle'];

	// Cycle et jours
	$requete = "SELECT * FROM ".$PT."parties WHERE ID = '".$IDPartie."' LIMIT 1";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requéte invalide : ' . mysqli_error($mysqli));
	$partie = mysqli_fetch_assoc($retour);

	$_SESSION['PartieEnCours']['Cycle'] = $partie['Cycle'];
	$_SESSION['PartieEnCours']['Jour'] = $partie['Jour'];

	// MAJ des variables de la partie
	$_SESSION["Variables"] = array();
	$requete = "SELECT IDVariable,Valeur FROM ".$PT."variables WHERE IDPartie = ".$IDPartie;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requête invalide : '.$requete . mysqli_error($mysqli));
	while($variable = mysqli_fetch_assoc($retour))
		$_SESSION["Variables"][$variable["IDVariable"]] = $variable["Valeur"];


	// Récupération de la liste des lieux découverts par le joueur
	$_SESSION["LieuxDecouverts"] = array();
	$requete = "SELECT IDLieu FROM ".$PT."lieuxDecouverts WHERE IDPersonnage = ".$_SESSION['IDPersonnage'];
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requête invalide : '.$requete . mysqli_error($mysqli));
	while($lieuDecouvert = mysqli_fetch_assoc($retour))
		$_SESSION["LieuxDecouverts"][$lieuDecouvert["IDLieu"]] = true;
	
	// Récupération Lieux Région
	$_SESSION["LieuxDansRegion"] = array();
	$requete = "SELECT * FROM ".$PT."lieux WHERE IDPartie = ".$IDPartie." AND IDRegion = ".$IDRegion;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requête invalide : '.$requete . mysqli_error($mysqli));

	while($lieu = mysqli_fetch_assoc($retour))
	{
		$IDLieu = $lieu["ID"];
		$lieu["Parametres"] = array();
		$_SESSION["LieuxDansRegion"][$IDLieu] = $lieu;

		if($lieu["EtatDecouverte"] == "ADecouvrir"  && empty($_SESSION["LieuxDecouverts"][$IDLieu]))
			continue;

		// Récupération des infos propres aux types de lieu (si découvert)
		switch($lieu["IDTypeLieu"])
		{
			case 1: // Banc de poisson
			{	
				$requete2 = "SELECT * FROM ".$PT."parametresBancsPoissons WHERE IDLieu = ".$IDLieu;
				$retour2 = mysqli_query($mysqli,$requete2);
				if (!$retour2) 
					die('Requête invalide : '.$requete2 . mysqli_error($mysqli));
				$_SESSION["LieuxDansRegion"][$IDLieu]["Parametres"] = mysqli_fetch_assoc($retour2);
				$_SESSION["LieuxDansRegion"][$IDLieu]["Parametres"]["CoutPeche"] = getCoutPeche();
			}
			break;	
			case 2: // Emplacement de campement
				$_SESSION["LieuxDansRegion"][$IDLieu]["Parametres"]["CoutInstallation"] = COUT_INSTALLATION_CAMPEMENT;
			break;	
			case 3: // Campement
				$requete2 = "SELECT * FROM ".$PT."parametresCampement WHERE IDCampement = ".$IDLieu;
				$retour2 = mysqli_query($mysqli,$requete2);
				if (!$retour2) 
					die('Requête invalide : '.$requete2 . mysqli_error($mysqli));
				$_SESSION["LieuxDansRegion"][$IDLieu]["Parametres"] = mysqli_fetch_assoc($retour2);
				$_SESSION["LieuxDansRegion"][$IDLieu]["Parametres"]["CoutAllumageFeu"] = COUT_ALLUMER_FEU;
				$_SESSION["LieuxDansRegion"][$IDLieu]["Parametres"]["ChanceAllumerFeuSilex"] = CHANCE_ALLUMER_FEU_SILEX;
				$_SESSION["LieuxDansRegion"][$IDLieu]["Parametres"]["ChanceAllumerFeuBois"] = CHANCE_ALLUMER_FEU_BOIS;
				$_SESSION["LieuxDansRegion"][$IDLieu]["Parametres"]["StockBucheMax"] = STOCK_BUCHE_MAX_FEU;

				//Récupération de l'inventaire du campement
				$_SESSION["InventaireCampement"] = array();
				$requete = "SELECT * FROM ".$PT."items WHERE IDPartie = ".$IDPartie." AND TypeInventaire = 'campement' AND IDProprietaire = ".$IDLieu;
				$retour = mysqli_query($mysqli,$requete);
				if (!$retour) die('Requête invalide : '.$requete . mysql_error($mysqli));
				while($item = mysqli_fetch_assoc($retour))
				{
					$IDItem = $item["ID"];
					$IDTypeItem = $item["IDTypeItem"];

					if(empty($_SESSION["InventaireCampement"][$IDTypeItem]))
						$_SESSION["InventaireCampement"][$IDTypeItem] = array();

					$_SESSION["InventaireCampement"][$IDTypeItem][$IDItem] = $item;
				}

			break;	
			case 5: // Sources d'eau
			{	
				$requete2 = "SELECT * FROM ".$PT."parametresSourcesEau WHERE IDLieu = ".$IDLieu;
				$retour2 = mysqli_query($mysqli,$requete2);
				if (!$retour2) 
					die('Requête invalide : '.$requete2 . mysqli_error($mysqli));
				$_SESSION["LieuxDansRegion"][$IDLieu]["Parametres"] = mysqli_fetch_assoc($retour2);
			}
			break;
		}

		$_SESSION["LieuxDansRegion"][$IDLieu]["Nom"] = lang("Lieu_".$lieu["IDTypeLieu"]."_Nom");
		$_SESSION["LieuxDansRegion"][$IDLieu]["Description"] = lang("Lieu_".$lieu["IDTypeLieu"]."_Description");
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

	// Récupération des objets dans l'inventaire du joueur, triés par IDTypeItem
	$_SESSION["Inventaire"] = array();
	$requete = "SELECT * FROM ".$PT."items WHERE IDPartie = ".$IDPartie." AND TypeInventaire = 'personnage' AND IDProprietaire = ".$_SESSION['IDPersonnage'];
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requête invalide : '.$requete . mysql_error($mysqli));
	while($item = mysqli_fetch_assoc($retour))
	{
		$IDItem = $item["ID"];
		$IDTypeItem = $item["IDTypeItem"];

		if(empty($_SESSION["Inventaire"][$IDTypeItem]))
			$_SESSION["Inventaire"][$IDTypeItem] = array();

		$_SESSION["Inventaire"][$IDTypeItem][$IDItem] = $item;
	}

	// Récupération des conditions
	$_SESSION["Conditions"] = array();
	$requete = "SELECT * FROM ".$PT."conditions WHERE IDPartie = ".$IDPartie." AND IDPersonnage = ".$_SESSION['IDPersonnage'];
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requête invalide : '.$requete . mysql_error($mysqli));
	while($condition = mysqli_fetch_assoc($retour))
		$_SESSION["Conditions"][$condition["IDCondition"]] = $condition;

	//Historique des tchats
	$_SESSION["AccesTchatRadio"] = (getItem(30,"personnage") != null);
	$_SESSION["Tchats"] = getHistoriquesTchats($mysqli,$_SESSION['IDPersonnage'],$IDRegion,$_SESSION['DateArriveeLieu'],$_SESSION["AccesTchatRadio"],$IDPartie);
}
?>