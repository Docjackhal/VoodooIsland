<?php

/*Liste des variables
1	Nombre pelles trouvees	X: Nombre pelles, 3 max
2	Vieille marmitte trouvée	1 si trouvée
3	Toile rudimentaire trouvée	1 si trouvée
4	Toile posée sur le camp	1: toile posée
5	Marmitte posée sur le camp = camp monté	1: Camp monté
*/


// Récupere la valeur d'une variable de la partie, ou -1 si n'est pas définie
function getVariable($IDVariable)
{
	return (!empty($_SESSION["Variables"][$IDVariable])) ? $_SESSION["Variables"][$IDVariable] : -1;
}

function getVariablesDePartie($mysqli,$IDPartie)
{
	global $PT;

	$variables = array();
	$requete = "SELECT IDVariable,Valeur FROM ".$PT."variables WHERE IDPartie = ".$IDPartie;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requête invalide (GetVariableDePartie): ' . mysqli_error($mysqli));

	while($variable = mysqli_fetch_assoc($retour))
		$variables[$variable["IDVariable"]] = $variable["Valeur"];

	return $variables;
}

//Set une variable dans la partie en cours
function setVariablePartie($mysqli,$IDVariable,$value)
{
	setVariable($mysqli,$_SESSION["IDPartieEnCours"],$IDVariable,$value);
}

//Set une variable
function setVariable($mysqli,$IDPartie,$IDVariable,$value)
{
	global $PT;
	$requete = "INSERT INTO ".$PT."variables (IDPartie,IDVariable,Valeur) VALUES (".$IDPartie.",".$IDVariable.",".$value.") ON DUPLICATE KEY UPDATE Valeur = ".$value;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requête invalide  (setVariable): ' . mysqli_error($mysqli));
}

?>