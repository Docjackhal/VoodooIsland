<?php
session_start();
if(empty($_SESSION))
	header('Location: accueil.php');
else
{
	include_once("prive/config.php");

	$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
	mysqli_set_charset($mysqli, "utf8");

	if(!isset($_GET['action']))
		die();
	else
	{
		$idAction = $_GET['action'];
		$ref = "";
		$idPageAction = floor(($idAction/10));
		include("action".$idPageAction.".php");

		if($ref == "")
			header('Location: game.php');
		else
			header('Location:'.$ref);
	}

	// ------------------- Liste des actions -------------------
	// Action 0: Rejoindre une partie
	// Action 1: Annuler selection personnage
	// Action 2: Voyager
}

function UpdateDateAndTime()
{
	$currentTimestamp = time("Y-m-d H:i:s");
	$dateDebutPartie = $_SESSION['PartieEnCours']['DateDemarrage'];
	$timestampDebutPartie = strtotime($dateDebutPartie);
	$diffTimestamp = $currentTimestamp - $timestampDebutPartie;

	// Conversion en jour
	$jour = ceil($diffTimestamp/(60*60*24));
	
	// Conversion en cycle	
	$heures = floor($diffTimestamp/(60*60));
	$cycle = floor($heures/3);

	$_SESSION['Jour'] = $jour;
	$_SESSION['Heures'] = $heures;
	$_SESSION['Cycle'] = $cycle;
}
?>