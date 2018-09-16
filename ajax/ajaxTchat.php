<?php
session_start();
header('Content-type: text/json');
include_once("../prive/config.php");
include_once("../fonctionsGlobales.php");
include_once("../fonctionsTchats.php");

$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
mysqli_set_charset($mysqli, "utf8");

$result = array();
$IDPersonnage = $_SESSION["IDPersonnage"];
$IDRegion = $_SESSION["RegionActuelle"];
$IDPartie = $_SESSION["IDPartieEnCours"];
$IDCycle = getIDCycleActuel();

switch($_GET['action'])
{
	case "envoyerMessage": // Heros actif envoie message
	{
		$message = $_POST["message"];
		$canal = $_POST["idCanal"];
		$auteur = "Heros_".$IDPersonnage;
		$destinataires = "Tous";

		//Mise en forme du canal Region
		if($canal == "Region")
			$canal = "Region_".$IDRegion;

		//Verification des droits d'accès aux cannaux
		if(($canal == "Radio" && !$_SESSION["AccesTchatRadio"]) || $canal == "Partie")
		{
			$result = "Erreur: Ecriture dans le canal ".$canal." impossible";
			break;
		}

		$messageEnvoye = envoyerMessage($mysqli,$canal,$auteur,$message,$IDPartie,$IDCycle,$destinataires);

		if($messageEnvoye != null)
			$result["DivNouveauMessage"] = genererBlocMessage($messageEnvoye);
		else
			$result = "Erreur d'envoi du message";

	}
	break;
}

echo json_encode($result);
?>