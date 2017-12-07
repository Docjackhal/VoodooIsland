<?php
session_start();
include_once("../prive/config.php");
include_once("../fonctionsItems.php");
$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
mysqli_set_charset($mysqli, "utf8");

$requete = "START TRANSACTION";
$retour = mysqli_query($mysqli,$requete);
$necessiteRollback = true;

$IDPartie = $_POST["IDPartie"];
$requete = "SELECT * FROM ".$PT."parties WHERE ID = '".$IDPartie."' AND Etat = 'en_creation' LIMIT 1";
$retour = mysqli_query($mysqli,$requete);
if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));


if(mysqli_num_rows($retour))
{
	$partie = mysqli_fetch_assoc($retour);
	$pret = true;
	for($i = 1; $i <= count($_SESSION['Heros']); $i++)
	{
		if($partie['Joueur'.$i] < 0)
		{
			$pret = false;
			break;
		}
	}

	if($pret)
	{
		// Autorisation Démarrage de la partie	
		$requete = "UPDATE ".$PT."parties SET DateDemarrage = CURDATE(), Etat = 'en_cours', Jour=1 WHERE ID = '".$IDPartie."'";
		$retour = mysqli_query($mysqli,$requete);
		if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

		$requete = "SELECT * FROM ".$PT."parties WHERE ID = '".$IDPartie."' AND Etat = 'en_cours' LIMIT 1";
		$retour = mysqli_query($mysqli,$requete);
		if (!$retour) die('Requête invalide : ' . mysql_error($mysqli));
		$partie = mysqli_fetch_assoc($retour);

		// Determination du cycle en cours
		$currentTimestamp = time("Y-m-d H:i:s");
		$dateDebutPartie = $_SESSION['PartieEnCours']['DateDemarrage'];
		$timestampDebutPartie = strtotime($dateDebutPartie);
		$diffTimestamp = $currentTimestamp - $timestampDebutPartie;
		$heures = floor($diffTimestamp/(60*60));
		$cycle = (floor($heures/3))%8;

		//Manuel:
		$cycle = 0;

		$requete = "UPDATE ".$PT."parties SET Cycle = '".$cycle."' WHERE ID = '".$IDPartie."'";
		$retour = mysqli_query($mysqli,$requete);
		if (!$retour) die('Requête invalide : ' . mysqli_error());

		$partie['Cycle'] = $cycle;
		$_SESSION['PartieEnCours'] = $partie;

		// Creation des personnages
		for($i = 1; $i <= count($_SESSION['Heros']); $i++)
		{
			$heros = $_SESSION['Heros'][$i];
			$faim = $heros['FaimMax'];
			$soif = $heros['SoifMax'];
			$fatigue = $heros['FatigueMax'];
			$pv = $heros['PvMax'];
			$pa = $heros['PaMax'];
			$pm = $heros['PmMax'];
			$mp = $heros['MPMax'];

			$requete = "INSERT INTO ".$PT."personnages (Joueur, IDHeros, IDPartie, PvActuel, PvMax, MPActuel, MPMax, FaimActuel, FaimMax, SoifActuel, SoifMax, FatigueActuel, FatigueMax, PaActuel, PaMax, PmActuel, PmMax) VALUES ('".$partie['Joueur'.$i]."', '".$i."','".$IDPartie."' ,'".$pv."', '".$pv."','".$mp."', '".$mp."', '".$faim."', '".$faim."', '".$soif."', '".$soif."', '".$fatigue."','".$fatigue."','".$pa."','".$pa."','".$pm."','".$pm."')";
			$retour = mysqli_query($mysqli,$requete);
			if (!$retour) die('Requête création perso invalide : ' . mysqli_error($mysqli));		

			//Génération des objets de depart
			switch($i)
			{
				case 1:	// Kurt Williams
					ajouterItem($mysqli,$IDPartie,1,$i,"personnage",-1); // Couteau
					break;
				case 2:	// Hanna Vilhelm
					ajouterItem($mysqli,$IDPartie,12,$i,"personnage",-1); // Trousse de secours
					ajouterItem($mysqli,$IDPartie,12,$i,"personnage",-1); // Trousse de secours
					ajouterItem($mysqli,$IDPartie,25,$i,"personnage",-1); // Kit d'analyse d'eau
					break;
				case 3:	// John Fisherman
					ajouterItem($mysqli,$IDPartie,13,$i,"personnage",-1); // Bouteille d'eau
					break;
				case 4:	// Sergeï Moskovski
					ajouterItem($mysqli,$IDPartie,1,$i,"personnage",-1); // Couteau
					ajouterItem($mysqli,$IDPartie,16,$i,"personnage",-1); // Vodka
					ajouterItem($mysqli,$IDPartie,17,$i,"personnage",-1); // Conserve
					ajouterItem($mysqli,$IDPartie,17,$i,"personnage",-1); // Conserve
					ajouterItem($mysqli,$IDPartie,17,$i,"personnage",-1); // Conserve
					ajouterItem($mysqli,$IDPartie,17,$i,"personnage",-1); // Conserve
					break;
				case 5:	// Enzo Lombardi
					ajouterItem($mysqli,$IDPartie,13,$i,"personnage",-1); // Bouteille d'eau
					break;
				case 6:	// Abby Lopez
					ajouterItem($mysqli,$IDPartie,33,$i,"personnage",-1); // Clé à molete
					ajouterItem($mysqli,$IDPartie,30,$i,"personnage",-1); // Radio
					ajouterItem($mysqli,$IDPartie,30,$i,"personnage",-1); // Radio
					break;
				case 7:	// Kenny
					ajouterItem($mysqli,$IDPartie,1,$i,"personnage",-1); // Couteau
					break;
				case 8:	//Yuri Lin
					ajouterItem($mysqli,$IDPartie,13,$i,"personnage",-1); // Bouteille d'eau
					break;
			}
		}

		// Repartition des personnages dans les differents régions
		// un groupe de 3, un groupe de 2 et trois groupes de 1
		$groupe3 = array();
		$groupe2 = array();

		for($i = 0; $i < 3; $i++)
		{
			$idJoueur = rand(1,8);
			if(!in_array($idJoueur,$groupe3))
				$groupe3[] = $idJoueur;
			else
				$i--;
		}

		for($i = 0; $i < 2; $i++)
		{
			$idJoueur = rand(1,8);
			if(!in_array($idJoueur,$groupe3) && !in_array($idJoueur,$groupe2))
				$groupe2[] = $idJoueur;
			else
				$i--;
		}

		$lieu3 = rand(1,8);
		$lieu2 = $lieu3;
		while($lieu2 == $lieu3)
			$lieu2 = rand(1,8);


		// Repartition aleatoire dans les régions (montagne et volcan exclues)
		$lieuxSeul = array();
		for($idJoueur = 1; $idJoueur <= 8; $idJoueur++)
		{
			$idLieu = -1;
			if(in_array($idJoueur,$groupe3))
				$idLieu = $lieu3;
			else if(in_array($idJoueur,$groupe2))
				$idLieu = $lieu2;
			else
			{
				$idLieu = $lieu3;
				while($idLieu == $lieu3 || $idLieu == $lieu2 || in_array($idLieu,$lieuxSeul))
				{
					$idLieu = rand(1,8);
				}

				$lieuxSeul[] = $idLieu;
			}

			$requete = "UPDATE ".$PT."personnages SET RegionActuelle = '".$idLieu."', DateArriveeLieu = NOW() WHERE IDHeros = '".$idJoueur."' AND idPartie = '".$IDPartie."'";
			$retour = mysqli_query($mysqli,$requete);
			if (!$retour) trigger_error('Requête invalide : ' . mysqli_error($mysqli));
		}

		// Génération des lieux

		// Emplacements de campement
		genererLieu($mysqli,2,$IDPartie,1,true);
		genererLieu($mysqli,2,$IDPartie,2,true);
		genererLieu($mysqli,2,$IDPartie,3,true);

		// Création des bancs de poisson
		$regionsPoisson = array(1,2,3);
		$IDBancsPoissonsDansRegion = array();
		for($i = 0; $i < count($regionsPoisson);$i++)
			$IDBancsPoissonsDansRegion[$i] = genererLieu($mysqli,1,$IDPartie,$regionsPoisson[$i],false);
	
		// Initialisation des bancs de poisson
		$listeNbPoissons = array(rand(5,9),rand(12,18),rand(21,23));
		for($i = 0; $i < count($regionsPoisson);$i++)
		{
			$r = rand(0,count($listeNbPoissons)-1);
			$nbPoissons = $listeNbPoissons[$r];
			array_splice($listeNbPoissons, $r,true);
			initialiserBancPoisson($mysqli,$IDBancsPoissonsDansRegion[$i],$IDPartie,$nbPoissons);
		}

		// Sources d'eau
		$listeRegionsSource = array(4,5,6,7,8);
		for($i=0;$i<3;$i++)
		{
			$r = rand(0,count($listeRegionsSource)-1);
			$IDRegion = $listeRegionsSource[$r];
			array_splice($listeRegionsSource, $r,true);
			$IDLieu = genererLieu($mysqli,5,$IDPartie,$IDRegion,false);
			initialiserSourceEau($mysqli,$IDLieu,$IDPartie,($i > 0));
		}

		// Village Voodoo
		genererLieu($mysqli,6,$IDPartie,rand(4,8),false);
		// Idole Voodoo
		genererLieu($mysqli,7,$IDPartie,rand(4,8),false);
		// Antre de la bête
		genererLieu($mysqli,8,$IDPartie,rand(4,8),false);
		// Epave d'avion
		genererLieu($mysqli,9,$IDPartie,rand(4,8),false);
		// Epave Santa Marina
		genererLieu($mysqli,4,$IDPartie,rand(1,3),false);
		// Antenne Radio
		genererLieu($mysqli,10,$IDPartie,9,false);
		// Autel Voodoo
		genererLieu($mysqli,11,$IDPartie,10,true);
		// Coulée de lave
		genererLieu($mysqli,12,$IDPartie,10,true);

		$requete = "COMMIT";
		$retour = mysqli_query($mysqli,$requete);

		$necessiteRollback = false;

		header('Location: index.php');
	}
	else
		trigger_error("Erreur creation partie not ready");
}
else
	trigger_error("Erreur creation partie not found");

if($necessiteRollback)
{
	$requete = "ROLLBACK";
	$retour = mysqli_query($mysqli,$requete);
	echo "<h1>ROLLBACK</h1>";
}

// Genere un lieu et retourne l'id du lieu dans la table Lieu
function genererLieu($mysqli,$IDTypeLieu,$IDPartie,$IDRegion,$estDecouvert)
{
	global $PT;

	echo "</br>Création lieu ".$IDTypeLieu." dans la région ".$IDRegion;

	$etatDecouverte = $estDecouvert ? "Visible" : "ADecouvrir" ;
	$requete = "INSERT INTO ".$PT."lieux (IDTypeLieu,IDPartie,IDRegion,EtatDecouverte) VALUES (".$IDTypeLieu.",".$IDPartie.",".$IDRegion.",'".$etatDecouverte."')";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide : '.$requete . mysqli_error($mysqli));
	return mysqli_insert_id($mysqli);
}

function initialiserBancPoisson($mysqli,$IDLieu,$IDPartie,$nbPoissons)
{
	global $PT;

	echo "</br>Initialisation banc poisson ID lieu ".$IDLieu." avec ".$nbPoissons." poisson";

	$requete = "INSERT INTO ".$PT."parametresBancsPoissons (IDPartie,IDLieu,NbPoissons) VALUES (".$IDPartie.",".$IDLieu.",".$nbPoissons.")";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide : '.$requete . mysqli_error($mysqli));

	$IDBancPoisson = mysqli_insert_id($mysqli);

	$requete = "UPDATE ".$PT."lieux SET IDParametrageLieu = ".$IDBancPoisson." WHERE ID = ".$IDLieu;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide : ' . mysqli_error($mysqli));
}

function initialiserSourceEau($mysqli,$IDLieu,$IDPartie,$estPotable)
{
	global $PT;

	echo "</br>Initialisation Source eau IDlieu ".$IDLieu."";

	$requete = "INSERT INTO ".$PT."parametresSourcesEau (IDPartie,IDLieu,EstPotable) VALUES (".$IDPartie.",".$IDLieu.",'".($estPotable ? 'o' : 'n')."')";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide : ' . mysqli_error($mysqli));

	$IDSource = mysqli_insert_id($mysqli);

	$requete = "UPDATE ".$PT."lieux SET IDParametrageLieu = ".$IDSource." WHERE ID = ".$IDLieu;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide : ' . mysqli_error($mysqli));
}

?>