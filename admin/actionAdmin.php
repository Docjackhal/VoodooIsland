<?php
session_start();
if(empty($_SESSION))
	header('Location: accueil.php');
else
{
	include_once("../fonctionsLangue.php");
	include_once("../prive/config.php");
	include_once("../updateInformationsSession.php");
	include_once("../fonctionsItems.php");
	include_once("../fonctionsVariables.php");
	include_once("../fonctionsEvents.php");
	include_once("../fonctionsLieux.php");
	include_once("../fonctionsConditions.php");
	include_once("../fonctionsPersonnages.php");
	include_once("../fonctionsGlobales.php");

	$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
	mysqli_set_charset($mysqli, "utf8");

	//updateInformationsSession();
	updateDateAndTime();

	//Start transaction, ne pas oublier de commit a la fin de chaque action
	mysqli_begin_transaction($mysqli);

	if(!isset($_GET['action']))
		die("IDAction not defined");

	$idAction = $_GET['action'];
	$ref = "";

	//Informations globales
	$IDPartie = $_SESSION["Admin"]["IDPartieEnCours"];

	// ------------------- Liste des actions -------------------
	// Action 0: Full AP un personnage
	// Action 1: Donner items à un personnage
	// Action 2: Modifier une variable de partie
	
	switch($idAction)
	{
		case 0:// Full AP/PM un personnage
		{
			$IDHeros = $_POST["IDHeros"];
			$personnage = getPersonnage($mysqli,$IDHeros,$IDPartie);
			updateCarac($mysqli,$personnage,"Pa",$_SESSION["Admin"]["Heros"][$IDHeros]["PaMax"]);
			$personnage = getPersonnage($mysqli,$IDHeros,$IDPartie);
			updateCarac($mysqli,$personnage,"Pm",$_SESSION["Admin"]["Heros"][$IDHeros]["PmMax"]);
			mysqli_commit($mysqli);
			break;
		}
		case 1:// Donner items à un personnage
		{
			$IDHeros = $_POST["IDHeros"];
			$IDItem = $_POST["IDItem"];
			$parametre = $_POST["parametre"];
			$quantite = intval($_POST["quantite"]);

			for($i=0;$i<$quantite;$i++)
				ajouterItem($mysqli,$IDPartie,$IDItem,$IDHeros,"personnage",$parametre);	

			mysqli_commit($mysqli);
			break;
		}
		case 2:// Modifier une variable
		{
			$IDVariable = $_POST["IDVariable"];
			$value = $_POST["value"];
			setVariable($mysqli,$IDPartie,$IDVariable,$value);
			$ref = "index.php?o=v";
			mysqli_commit($mysqli);
			break;
		}
	}

	if($ref == "")
			header('Location: index.php');
		else
			header('Location:'.$ref);
}
?>