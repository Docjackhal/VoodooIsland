<?php
ob_start();
session_start();

include_once("fonctionsLangue.php");

if(empty($_SESSION))
	 header('Location: accueil.php');
else
{
	if($_SESSION['IDPartieEnCours'] != -1 && $_SESSION["PartieEnCours"]["Etat"] == "en_cours")
		header('Location: game.php');
	else
		header('Location: lobby.php');
}

?>