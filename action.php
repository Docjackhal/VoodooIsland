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
	include_once("fonctionsConditions.php");
	include_once("fonctionsTchats.php");
	include_once("fonctionsPersonnages.php");
	include_once("fonctionsGlobales.php");

	$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
	mysqli_set_charset($mysqli, "utf8");

	updateInformationsSession();
	updateDateAndTime();

	//Start transaction, ne pas oublier de commit a la fin de chaque action
	mysqli_begin_transaction($mysqli);
	mysqli_autocommit($mysqli,false);

	if(!isset($_GET['action']))
		die("IDAction not defined");
	else
	{
		$idAction = $_GET['action'];
		$ref = "";
		$idPageAction = floor(($idAction/10));

		//Informations globales
		$IDPartie = $_SESSION['IDPartieEnCours'];
		$IDRegion = $_SESSION['RegionActuelle'];
		$IDPersonnage = $_SESSION["IDPersonnage"];

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
	// Action 5: Etre prêt a passer au cycle suivant
	// Action 6: Pecher dans un banc de poisson
	// Action 7: Creuser un emplacement de campement
	// Action 8: Installer une toile dans un emplacement de campement
	// Action 9: Installer la vieille marmitte dans un emplacement de campement
	// Action 10: Allumer le feu d'un campement (silex ou bois)
}
?>