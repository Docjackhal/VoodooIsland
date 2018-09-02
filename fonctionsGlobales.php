<?php
function getCoutDeplacement()
{
	return COUT_DEPLACEMENT;
}

function getCoutExploration()
{
	return COUT_EXPLORATION;
}

//Renvoi le cout en AP actuel de la peche. Peut varier selon la météo ou les objets/compétences d'un personnage.
function getCoutPeche()
{
	return COUT_PECHE;
}


function updateDateAndTime()
{
	$currentTimestamp = time("Y-m-d H:i:s");
	$dateDebutPartie = $_SESSION['PartieEnCours']['DateDemarrage'];
	$timestampDebutPartie = strtotime($dateDebutPartie);
	$diffTimestamp = $currentTimestamp - $timestampDebutPartie;

	// Conversion en jour
	$jour = ceil($diffTimestamp/(60*60*24));
	
	// Conversion en cycle	
	$heures = floor($diffTimestamp/(60*60));
	$cycle = floor($heures/3);

	$_SESSION['Jour'] = $jour;
	$_SESSION['Heures'] = $heures;
	$_SESSION['Cycle'] = $cycle;
}

// Renvoi le nombre de voodoo dans la partie
function getNombreVoodoos($mysqli)
{
	global $PT;
	$requete = "SELECT count(*) as NB FROM ".$PT."personnages WHERE EstVoodoo = 'o' AND IDPartie = ".$_SESSION["IDPartie"];
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

	$infosParties = mysqli_fetch_assoc($retour);
	return $infosParties["NB"];
}



?>