<?php

//Modifie une carac pour le personnage en cours
function updateMyCarac($mysqli,$carac,$modificateur)
{
	updateCarac($mysqli,$_SESSION["IDPersonnage"],$carac,$modificateur);
}

function updateCarac($mysqli,$IDPersonnage,$carac,$modificateur)
{
	global $PT;

	//die("carac ".$carac." modif ".$modificateur);

	$heros = $_SESSION["Heros"][$IDPersonnage];
	$fatigue = $_SESSION['FatigueActuel'];
	$faim = $_SESSION['FaimActuel'];
	$soif = $_SESSION['SoifActuel'];
	$pa = $_SESSION['PaActuel'];
	$pm = $_SESSION['PmActuel'];
	$pv = $_SESSION['PvActuel'];
	$mp = $_SESSION['MPActuel'];

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
	$requete2 = "UPDATE  ".$PT."personnages SET FatigueActuel = '".$fatigue."', FaimActuel = '".$faim."', SoifActuel = '".$soif."', PaActuel = '".$pa."', PmActuel = '".$pm."', PvActuel = '".$pv."',MPActuel = '".$mp."' WHERE IDHeros= '".$IDPersonnage."' AND IDPartie = ".$_SESSION["IDPartieEnCours"];
	$retour2 = mysqli_query($mysqli,$requete2);
	if (!$retour2) die('Requête invalide : ' . mysqli_error($mysqli));
}

?>