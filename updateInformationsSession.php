<?php
function updateInformationsSession()
{
	global $mysql_ip, $mysql_user, $mysql_password, $base;

	// Connexion a la base
	$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
	mysqli_set_charset($mysqli, "ANSI");

	// Caractéristiques joueurs
	$requete = "SELECT IDHeros, FaimActuel, SoifActuel, FatigueActuel, PvActuel, PaActuel, PmActuel, RegionActuelle, DateArriveeLieu FROM personnages WHERE Joueur = '".$_SESSION['ID']."' AND IDPartie = '".$_SESSION['IDPartieEnCours']."' LIMIT 1";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('RequÃªte invalide : ' . mysqli_error($mysqli));
	$personnage = mysqli_fetch_assoc($retour);

	$_SESSION['FaimActuel'] = $personnage['FaimActuel'];
	$_SESSION['SoifActuel'] = $personnage['SoifActuel'];
	$_SESSION['FatigueActuel'] = $personnage['FatigueActuel'];
	$_SESSION['PvActuel'] = $personnage['PvActuel'];
	$_SESSION['PaActuel'] = $personnage['PaActuel'];
	$_SESSION['PmActuel'] = $personnage['PmActuel'];
	$_SESSION['RegionActuelle'] = $personnage['RegionActuelle'];
	$_SESSION['DateArriveeLieu'] = $personnage['DateArriveeLieu'];
	$_SESSION['IDPersonnage'] = $personnage['IDHeros'];

	// Cycle et jours
	$requete = "SELECT * FROM parties WHERE ID = '".$_SESSION['IDPartieEnCours']."' LIMIT 1";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requéte invalide : ' . mysqli_error($mysqli));
	$partie = mysqli_fetch_assoc($retour);

	$_SESSION['PartieEnCours']['Cycle'] = $partie['Cycle'];
	$_SESSION['PartieEnCours']['Jour'] = $partie['Jour'];

	// Données Tchat
	$requete = "SELECT Auteur, Message, Canal, DateEnvoie FROM tchats WHERE IDPartie = '".$_SESSION['IDPartieEnCours']."' AND (Canal = '".$_SESSION['RegionActuelle']."' OR Canal = 0) AND DateEnvoie >= '".$_SESSION['DateArriveeLieu']."' ORDER BY DateEnvoie DESC";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requète invalide : ' . mysql_error($mysqli));
	
	$_SESSION['Tchats'] = array();

	if(mysqli_num_rows($retour))
	{
		while($message = mysqli_fetch_assoc($retour))
		{
			if(!isset($_SESSION['Tchats'][$message['Canal']]))
				$_SESSION['Tchats'][$message['Canal']] = array();

			$_SESSION['Tchats'][$message['Canal']][] = $message;
		}
	}
	

}
?>