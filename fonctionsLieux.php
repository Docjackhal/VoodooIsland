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

?>