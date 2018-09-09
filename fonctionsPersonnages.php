<?php

//Modifie une carac pour le personnage en cours
function updateMyCarac($mysqli,$carac,$modificateur)
{
	$personnage = array();
	$personnage["ID"] = $_SESSION['IDPersonnage'];
	$personnage["IDPartie"] = $_SESSION['IDPartieEnCours'];
	$personnage["FatigueActuel"] = $_SESSION['FatigueActuel'];
	$personnage["FaimActuel"] = $_SESSION['FaimActuel'];
	$personnage["SoifActuel"] = $_SESSION['SoifActuel'];
	$personnage["PaActuel"] = $_SESSION['PaActuel'];
	$personnage["PmActuel"] = $_SESSION['PmActuel'];
	$personnage["PvActuel"] = $_SESSION['PvActuel'];
	$personnage["MPActuel"] = $_SESSION['MPActuel'];
	updateCarac($mysqli,$personnage,$carac,$modificateur);
}

function updateCarac($mysqli,$personnage,$carac,$modificateur)
{
	global $PT;

	//die(print_r($personnage));
	//die("carac ".$carac." modif ".$modificateur);
	$IDPersonnage = $personnage["IDHeros"];
	$heros = isset($_SESSION["Admin"]["Heros"][$IDPersonnage]) ? $_SESSION["Admin"]["Heros"][$IDPersonnage] : $_SESSION["Heros"][$IDPersonnage];
	$fatigue = $personnage['FatigueActuel'];
	$faim = $personnage['FaimActuel'];
	$soif = $personnage['SoifActuel'];
	$pa = $personnage['PaActuel'];
	$pm = $personnage['PmActuel'];
	$pv = $personnage['PvActuel'];
	$mp = $personnage['MPActuel'];

	switch($carac)
	{
		case "Pa":
			$pa += $modificateur;
			break;
		case "Pm":
			$pm += $modificateur;
			break;
		case "Soif":
			$soif += $modificateur;
			break;
		case "Pv":
			$pv += $modificateur;
			break;
		case "Faim":
			$faim += $modificateur;
			break;
		case "Fatigue":
			$fatigue += $modificateur;
			break;
		case "MP":
			$mp += $modificateur;
			break;
	}

	// Lissage des caractéristiques
	if($pa > $heros["PaMax"])
		$pa = $heros["PaMax"];
	else if($pa < 0)
		$pa = 0;
	if($pm > $heros["PmMax"])
		$pm = $heros["PmMax"];
	else if($pm < 0)
		$pm = 0;
	if($soif > $heros["SoifMax"])
		$soif = $heros["SoifMax"];
	else if($soif < 0)
		$soif = 0;
	if($pv > $heros["PvMax"])
		$pv = $heros["PvMax"];
	else if($pv < 0)
		$pv = 0;
	if($mp > $heros["MPMax"])
		$mp = $heros["MPMax"];
	else if($mp < 0)
		$mp = 0;
	if($faim > $heros["FaimMax"])
		$faim = $heros["FaimMax"];
	else if($faim < 0)
		$faim = 0;
	if($fatigue > $heros["FatigueMax"])
		$fatigue = $heros["FatigueMax"];
	else if($fatigue < 0)
		$fatigue = 0;


	// Update final
	$requete2 = "UPDATE  ".$PT."personnages SET FatigueActuel = '".$fatigue."', FaimActuel = '".$faim."', SoifActuel = '".$soif."', PaActuel = '".$pa."', PmActuel = '".$pm."', PvActuel = '".$pv."',MPActuel = '".$mp."' WHERE IDHeros = '".$IDPersonnage."' AND IDPartie = ".$personnage["IDPartie"];
	$retour2 = mysqli_query($mysqli,$requete2);
	if (!$retour2) die('Requête invalide : ' . mysqli_error($mysqli));

	//die($requete2);
}

//Renvoi toutes les infos d'un personnage depuis la base, ou null si introuvable
function getPersonnage($mysqli,$IDHeros,$IDPartie)
{
	global $PT;

	$requete = "SELECT * FROM ".$PT."personnages WHERE IDHeros= '".$IDHeros."' AND IDPartie = ".$IDPartie;
	$retour = mysqli_query($mysqli,$requete);
		if (!$retour) trigger_error('Impossible de selectionner le personnage : ('.$requete.') ' . mysqli_error($mysqli));

	if(mysqli_num_rows($retour) == 0)
		return null;
	else
		return mysqli_fetch_assoc($retour);
}

?>