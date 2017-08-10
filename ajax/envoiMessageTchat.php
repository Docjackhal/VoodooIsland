<?php
session_start();
header('Content-type: text/json');
$result = array();

$message = mysql_real_escape_string($_GET['message']);
$idCanal = mysql_real_escape_string($_GET['idCanal']);

include_once("../prive/config.php");
$link = mysql_connect($mysql_ip, $mysql_user,$mysql_password);
if (!$link)
	die('Connexion impossible : ' . mysql_error());
else
	mysql_select_db($base);

$requete = "INSERT INTO tchats (IDPartie,Canal,Auteur,Message,DateEnvoie) VALUES ('".$_SESSION['IDPartieEnCours']."','".$idCanal."','".$_SESSION['Login']."','".$message."', NOW())";
$retour = mysql_query($requete);
if (!$retour) die('Requête invalide : ' . mysql_error());

$idMessage = mysql_insert_id();

$requete = "SELECT DateEnvoie FROM tchats WHERE ID = '".$idMessage."' LIMIT 1";
$retour = mysql_query($requete);
if (!$retour) die('Requête invalide : ' . mysql_error());

$message = mysql_fetch_assoc($retour);

$result['date'] = substr($message['DateEnvoie'],11);
$result['auteur'] = $_SESSION['Login'];

echo json_encode($result);
?>