<?php
/* Ce script réinitialise une partie.

La partie retourne en mode création. Les joueurs gardent leurs choix de personnages. Les personnages sont supprimés, les objets et les lieux égalements.

La partie est alors prête à etre relancée  par un administrateur.*/

session_start();
include_once("../prive/config.php");
include_once("../fonctionsItems.php");
$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
mysqli_set_charset($mysqli, "utf8");

$requete = "START TRANSACTION";
$retour = mysqli_query($mysqli,$requete);
$necessiteRollback = true;

if(!empty($_POST["IDPartie"]))
{
	$IDPartie = $_POST["IDPartie"];

	// Suppression des personnages
	$requete = "DELETE FROM ".$PT."personnages WHERE IDPartie = ".$IDPartie;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide : ' . mysqli_error($mysqli));

	// Supressions des objets
	$requete = "DELETE FROM ".$PT."items WHERE IDPartie = ".$IDPartie;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide : ' . mysqli_error($mysqli));

	// Supression des bancs de poissons
	$requete = "SELECT IDParametrageLieu FROM ".$PT."lieux WHERE IDPartie = ".$IDPartie." AND IDTypeLieu = 1";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide : ' . mysqli_error($mysqli));
	$IDParametresLieu = array();
	while($lieu = mysqli_fetch_assoc($retour))
		$IDParametresLieu[] = $lieu["IDParametrageLieu"];
	if(count($IDParametrageLieu) > 0)
	{
		$requete = "DELETE FROM ".$PT."parametragesBancsPoissons WHERE ID IN (".implode(',',$IDParametresLieu).")";
		$retour = mysqli_query($mysqli,$requete);
		if (!$retour) trigger_error('Requête invalide : ' . mysqli_error($mysqli));
	}


	// Suppression des sources d'eau
	$requete = "SELECT IDParametrageLieu FROM ".$PT."lieux WHERE IDPartie = ".$IDPartie." AND IDTypeLieu = 5";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide : ' . mysqli_error($mysqli));
	$IDParametresLieu = array();
	while($lieu = mysqli_fetch_assoc($retour))
		$IDParametresLieu[] = $lieu["IDParametrageLieu"];
	if(count($IDParametrageLieu) > 0)
	{
		$requete = "DELETE FROM ".$PT."parametragesSourcesEau WHERE ID IN (".implode(',',$IDParametresLieu).")";
		$retour = mysqli_query($mysqli,$requete);
		if (!$retour) trigger_error('Requête invalide : ' . mysqli_error($mysqli));
	}

	// Suppressions des lieux
	$requete = "DELETE FROM ".$PT."lieux WHERE IDPartie = ".$IDPartie;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide : ' . mysqli_error($mysqli));

	// Suppression du tchat
	$requete = "DELETE FROM ".$PT."tchats WHERE IDPartie = ".$IDPartie;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide : ' . mysqli_error($mysqli));

	// Remise en attente de démarrage de la partie
	$requete = "UPDATE ".$PT."parties SET Etat = 'en_creation' WHERE ID = ".$IDPartie;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide : ' . mysqli_error($mysqli));

	$requete = "COMMIT";
	$retour = mysqli_query($mysqli,$requete);

	$necessiteRollback = false;
}
else
{
	echo "<h1>Pas de partie</h1>";
}

if($necessiteRollback)
{
	$requete = "ROLLBACK";
	$retour = mysqli_query($mysqli,$requete);
	echo "<h1>ROLLBACK</h1>";
}
else
{
	echo "<h1>PARTIE ".$IDPartie." REINITIALISEE</h1>";
}

?>