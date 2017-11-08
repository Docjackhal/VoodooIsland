<?php
session_start();
if(empty($_SESSION))
	header('Location: accueil.php');
else
{
	include_once("fonctionsLangue.php");
	include_once("prive/config.php");
	include_once("updateInformationsSession.php");
	include_once("fonctionsItems.php");
	include_once("fonctionsVariables.php");
	include_once("fonctionsEvents.php");
	include_once("fonctionsLieux.php");
	include_once("fonctionsGlobales.php");

	$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
	mysqli_set_charset($mysqli, "utf8");

	updateInformationsSession();
	updateDateAndTime();

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
	// Action 3: Explorer la région
	// Action 4: Répondre a un evenement complexe
}
?>