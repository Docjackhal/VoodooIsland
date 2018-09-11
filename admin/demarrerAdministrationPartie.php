<?php
	session_start();
	$IDPartie = $_POST["IDPartie"];

	if($IDPartie == null)
		die("ID partie Inconnu");

	$_SESSION["Admin"] = array();
	$_SESSION["Admin"]["IDPartieEnCours"] = $IDPartie;

	include("../prive/config.php");

	// Connexion a la base
	$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
	mysqli_set_charset($mysqli, "utf8");

	$requete = "SELECT * FROM ".$PT."parties WHERE ID != ".$IDPartie;
	$retour = mysqli_query($mysqli,$requete);
		if (!$retour) trigger_error('Impossible de selectionner les parties : ' . mysqli_error($mysqli));
	$parties = array();
	$partie = mysqli_fetch_assoc($retour);
		$_SESSION["Admin"]["PartieEnCours"] = $partie;

	$requete = "SELECT * FROM ".$PT."heros";
	$retour = mysqli_query($mysqli,$requete);
		if (!$retour) trigger_error('Impossible de selectionner les héros : ' . mysqli_error($mysqli));
	$listeHeros = array();
	while($heros = mysqli_fetch_assoc($retour))
		$listeHeros[$heros["ID"]] = $heros;
	$_SESSION["Admin"]["Heros"] = $listeHeros;

	$requete = "SELECT * FROM ".$PT."typeItems";
	$retour = mysqli_query($mysqli,$requete);
		if (!$retour) trigger_error('Impossible de selectionner les items : ' . mysqli_error($mysqli));
	$listeItems = array();
	while($item = mysqli_fetch_assoc($retour))
		$listeItems[$item["ID"]] = $item;
	$_SESSION["Admin"]["TypeItems"] = $listeItems;

	$requete = "SELECT * FROM ".$PT."accounts WHERE IDPartieEnCours = ".$IDPartie;
	$retour = mysqli_query($mysqli,$requete);
		if (!$retour) trigger_error('Impossible de selectionner les comptes joueurs : ' . mysqli_error($mysqli));
	$listeJoueurs = array();
	while($joueur = mysqli_fetch_assoc($retour))
		$listeJoueurs[$joueur["ID"]] = $joueur;

	$_SESSION["Admin"]["Accounts"] = $listeJoueurs;

	$requete = "SELECT * FROM ".$PT."regions";
	$retour = mysqli_query($mysqli,$requete);
		if (!$retour) trigger_error('Impossible de selectionner les régions : ' . mysqli_error($mysqli));
	$listeRegions = array();
	while($region = mysqli_fetch_assoc($retour))
		$listeRegions[$region["ID"]] = $region;

	$_SESSION["Admin"]["Regions"] = $listeRegions;

	header("Location: index.php");

?>