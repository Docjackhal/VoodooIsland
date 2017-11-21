<?php

// Renvoi l'id du lieu de l'idType indiqué si existant dans la région (découvert ou non)
function getIDLieuDeTypeDansRegion($IDTypeLieu)
{
	foreach($_SESSION["LieuxDansRegion"] as $IDLieu => $lieu)
	{
		if($lieu["IDTypeLieu"] == $IDTypeLieu)
			return $IDLieu;
	}
	return -1;
}

// Découvre un lien en bdd si pas déja découvert
function decouvrirLieu($mysqli, $IDLieu)
{
	global $PT;

	$requete = "INSERT IGNORE INTO ".$PT."lieuxDecouverts (IDLieu,IDPersonnage,DateDecouverte) VALUES (".$IDLieu.",".$_SESSION["IDPersonnage"].",NOW())";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide : '.$requete . mysqli_error($mysqli));
}

// Renvoi le nombre de personnages actuellement capturés dans le village caché voodoo.
function getNombrePersonnagesCapturesDansVillage($mysqli)
{
	global $PT;
	$requete = "SELECT count(*) as NB FROM ".$PT."personnages WHERE EstCapture = 'o' AND IDPartie = ".$_SESSION["IDPartie"];
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

	$infosParties = mysqli_fetch_assoc($retour);
	return $infosParties["NB"];
}

// Capturer un joueur dans le village voodoo
function capturerJoueurDansVillage($mysqli,$IDHeros)
{
	global $PT;

	$requete = "UPDATE ".$PT."personnages SET EstCapture = 'o', NombreCyclesDepuisCapture = 0 WHERE IDHeros = ".$IDHeros." AND IDPartie = ".$_SESSION["IDPartieEnCours"];
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide : '.$requete . mysqli_error($mysqli));
}

function commencerRiteSurPersonnage($mysqli,$IDHeros)
{
	global $PT;

	$IDPersoRiteEnCours = getIDPersonnageRiteEnCours($mysqli);
	if($IDPersoRiteEnCours != -1)
		trigger_error("ERREUR RITE PERSONNAGE: RITE DEJA EN COURS");


	$requete = "UPDATE ".$PT."personnages SET EstCapture = 'n', RiteEnCours = 'o', NombreCyclesDepuisRite = 0 WHERE IDHeros = ".$IDHeros." AND IDPartie = ".$_SESSION["IDPartieEnCours"];
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide : '.$requete . mysqli_error($mysqli));
}

// renvoi l'id du personnage subissant un rite de transformation, ou -1 si il n'y a pas de rite en cours.
function getIDPersonnageRiteEnCours($mysqli)
{
	global $PT;

	$requete = "SELECT IDHeros FROM ".$PT."personnages WHERE RiteEnCours = 'o' AND IDPartie = ".$_SESSION["IDPartie"]." AND NombreCyclesDepuisRite < ".DUREE_CYCLE_RITE;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

	if(mysqli_num_rows($retour) > 0)
	{
		$infosPerso = mysqli_fetch_assoc($retour);
		return $infosPerso["IDHeros"];
	}
	else
		return -1;
}

?>