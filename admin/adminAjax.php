<?php
session_start();
include_once("../prive/config.php");
include_once("../fonctionsVariables.php");
include_once("../fonctionsLieux.php");
include_once("../fonctionsTchats.php");
include_once("../fonctionsLangue.php");
include_once("../fonctionsGlobales.php");
header('Content-type: text/json');

$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
mysqli_set_charset($mysqli, "utf8");

$result = array();
$IDPartie = $_SESSION["Admin"]["IDPartieEnCours"];

if($IDPartie == null)
	die("Ajax: Deconnection");

switch($_GET['action'])
{
	case "UpdateGlobalDatas":
	{
		// Récuperation des regions
		$result['Regions'] = array();
		$requete = "SELECT * FROM ".$PT."regions";
		$retour = mysqli_query($mysqli,$requete);
		if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

		while($region = mysqli_fetch_assoc($retour))
			$result['Regions'][$region['ID']] = $region;


		// Récupération de la liste des TypesItems
		$result["TypesItems"] = array();
		$requete = "SELECT * FROM ".$PT."typeItems";
		$retour = mysqli_query($mysqli,$requete);
		if (!$retour) die('Requête invalide : '.$requete . mysqli_error($mysqli));
		while($typeItem = mysqli_fetch_assoc($retour))
			$result["TypesItems"][$typeItem["ID"]] = $typeItem;

		// Récupération des types de lieu dans la liste
		$result["TypeLieux"] = array();
		$requete = "SELECT * FROM ".$PT."typeLieux";
		$retour = mysqli_query($mysqli,$requete);
		if (!$retour) die('Requête invalide : '.$requete . mysqli_error($mysqli));
		while($typeLieu = mysqli_fetch_assoc($retour))
			$result["TypeLieux"][$typeLieu["ID"]] = $typeLieu;

		// Récupération de la table des evenements
		$result['Evenements'] = array();
		$requete = "SELECT * FROM ".$PT."parametresEvenements";
		$retour = mysqli_query($mysqli,$requete);
		if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

		while($event = mysqli_fetch_assoc($retour))
		{
			$event["EstSimple"] = $event["EstSimple"] == 'o';
			$result['Evenements'][$event['ID']] = $event;	
		}

		// Récupération de la liste des TypesItems
		$result["ParametresConditions"] = array();
		$requete = "SELECT * FROM ".$PT."parametresConditions";
		$retour = mysqli_query($mysqli,$requete);
		if (!$retour) die('Requête invalide : '.$requete . mysqli_error($mysqli));
		while($typeCondition = mysqli_fetch_assoc($retour))
			$result["ParametresConditions"][$typeCondition["ID"]] = $typeCondition;
	}
	break;
	case "Update":
	{
		//Personnages
		$requete = "SELECT * FROM ".$PT."personnages WHERE IDPartie = ".$IDPartie;
		$retour = mysqli_query($mysqli,$requete);
			if (!$retour) trigger_error('Impossible de selectionner les personnages : ' . mysqli_error($mysqli));
		$personnages = array();
		while($personnage = mysqli_fetch_assoc($retour))
			$personnages[$personnage["IDHeros"]] = $personnage;

		$result["Personnages"] = $personnages;

		$result["Heros"] = $_SESSION["Admin"]["Heros"];

		//Variables
		$result["Variables"] = getVariablesDePartie($mysqli,$IDPartie);

		//Lieux
		$result["Lieux"] = getLieuxDansPartie($mysqli,$IDPartie);
	}
	break;
	case "UpdateTchat":
	{
		$historique = adminGetDerniersMessagesTchat($mysqli,$IDPartie,30);
		$result["Historique"] = $historique;
	}
	break;
	case "envoyerMessageAdmin":
	{
		$message = $_POST["message"];
		$canal = $_POST["idCanal"];
		$auteur = "Admin";
		$destinataires = "Tous";

		$messageEnvoye = envoyerMessage($mysqli,$canal,$auteur,$message,$IDPartie,getIDCycleActuel(),$destinataires);

		if($messageEnvoye != null)
			$result["DivNouveauMessage"] = genererBlocMessage($messageEnvoye);
		else
			$result = "Erreur d'envoi du message";

	}
	break;
}


echo json_encode($result);
?>