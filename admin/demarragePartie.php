<?php
session_start();
include_once("../prive/config.php");

// Connection base
$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
	mysqli_set_charset($mysqli, "utf8");


$requete = "START TRANSACTION";
$retour = mysqli_query($mysqli,$requete);

$idPartie = $_POST["IDPartie"];
$requete = "SELECT * FROM parties WHERE ID = '".$idPartie."' AND Etat = 'en_creation' LIMIT 1";
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
		$requete = "UPDATE parties SET DateDemarrage = CURDATE(), Etat = 'en_cours', Jour=1 WHERE ID = '".$idPartie."'";
		$retour = mysqli_query($mysqli,$requete);
		if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

		$requete = "SELECT * FROM parties WHERE ID = '".$idPartie."' AND Etat = 'en_cours' LIMIT 1";
		$retour = mysqli_query($mysqli,$requete);
		if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));
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

		$requete = "UPDATE parties SET Cycle = '".$cycle."' WHERE ID = '".$idPartie."'";
		$retour = mysqli_query($mysqli,$requete);
		if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

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

			$requete = "INSERT INTO personnages (Joueur, IDHeros, IDPartie, PvActuel, PvMax, FaimActuel, FaimMax, SoifActuel, SoifMax, FatigueActuel, FatigueMax, PaActuel, PaMax, PmActuel, PmMax) VALUES ('".$partie['Joueur'.$i]."', '".$i."','".$idPartie."' ,'".$pv."', '".$pv."', '".$faim."', '".$faim."', '".$soif."', '".$soif."', '".$fatigue."','".$fatigue."','".$pa."','".$pa."','".$pm."','".$pm."')";
			$retour = mysqli_query($mysqli,$requete);
			if (!$retour) die('Requête création perso invalide : ' . mysqli_error($mysqli));

			if($i == $_SESSION['IDPersoActuel'])
			{
				$idInsert = mysqli_insert_id($mysqli);
				$requete = "SELECT * FROM personnages WHERE Joueur = '".$_SESSION['ID']."' AND IDPartie = ".$idPartie." LIMIT 1";
				$retour = mysqli_query($mysqli,$requete);
				if (!$retour) trigger_error('Requête invalide : ' . mysqli_error($mysqli));

				$_SESSION['Personnage'] = mysqli_fetch_assoc($retour);
			}
		}

		// Repartition des personnages dans les differents lieux
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


		// Repartition aleatoire dans les lieux
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

			$requete = "UPDATE personnages SET RegionActuelle = '".$idLieu."', DateArriveeLieu = NOW() WHERE IDHeros = '".$idJoueur."' AND idPartie = '".$idPartie."'";
			$retour = mysqli_query($mysqli,$requete);
			if (!$retour) trigger_error('Requête invalide : ' . mysqli_error($mysqli));
		}

		$requete = "COMMIT";
		$retour = mysqli_query($mysqli,$requete);

		//header('Location: game.php');
		header('Location: index.php');
	}
	else
		trigger_error("Erreur creation partie not ready");
}
else
	trigger_error("Erreur creation partie not found");

$requete = "ROLLBACK";
$retour = mysqli_query($mysqli,$requete);

?>