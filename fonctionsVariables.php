<?php

// Récupere la valeur d'une variable de la partie, ou -1 si n'est pas définie
function getVariable($IDVariable)
{
	return (!empty($_SESSION["Variables"][$IDVariable])) ? $_SESSION["Variables"][$IDVariable] : -1;
}

function setVariable($mysqli,$IDPartie,$IDVariable,$value)
{
	global $PT;
	$requete = "INSERT INTO ".$PT."variables (IDPartie,IDVariable,Valeur) VALUES (".$IDPartie.",".$IDVariable.",".$value.") ON DUPLICATE KEY UPDATE Valeur = ".$value;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));
}

?>