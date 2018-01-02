<?php
session_start();
include_once("../prive/config.php");
header('Content-type: text/json');

$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
mysqli_set_charset($mysqli, "utf8");

$result = array();
$IDPartie = $_SESSION["Admin"]["IDPartieEnCours"];

switch($_GET['action'])
{
	case "Update":
	{
		$requete = "SELECT * FROM ".$PT."personnages WHERE IDPartie = ".$IDPartie;
		$retour = mysqli_query($mysqli,$requete);
			if (!$retour) trigger_error('Impossible de selectionner les personnages : ' . mysqli_error($mysqli));
		$personnages = array();
		while($personnage = mysqli_fetch_assoc($retour))
			$personnages[$personnage["IDHeros"]] = $personnage;

		$result["Personnages"] = $personnages;
	}
	break;
}


echo json_encode($result);
?>