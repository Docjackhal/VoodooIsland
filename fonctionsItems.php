<?php

// Ajoute un objets a un proprietaire. Retourne l'ID de l'objet ajouté
function ajouterItem($mysqli,$IDPartie,$IDTypeItem,$IDProprietaire,$typeInventaire,$parametre1 = -1)
{
	global $PT;

	$requete = "INSERT INTO ".$PT."items (IDTypeItem,Parametre1,IDPartie,IDProprietaire,TypeInventaire) VALUES (".$IDTypeItem.",".$parametre1.",".$IDPartie.",".$IDProprietaire.",'".$typeInventaire."')";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide : '.$requete . mysqli_error($mysqli));

	return mysqli_insert_id($mysqli);
}

// Supprime un objet a partir de son ID.
function supprimerItem($mysqli,$IDItem)
{
	global $PT;

	$requete = "DELETE FROM ".$PT."items WHERE ID = ".$IDItem;
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) trigger_error('Requête invalide : '.$requete . mysqli_error($mysqli));
}

//Renvoi le nombre d'items dans l'inventaire du type donné
function nbItemsDansInventaire($typeItem)
{
	if(isset($_SESSION["Inventaire"][$typeItem]))
	{
		$nb = 0;
		foreach($_SESSION["Inventaire"][$typeItem] as $item)
			$nb++;
		return $nb;
	}
	else
		return 0;
}

//Renvoi tous les objets d'un type donné dans l'inventaire donné
function getItems($typeItem,$typeInventaire)
{
	if(!empty($_SESSION[$typeInventaire][$typeItem]))
		return $_SESSION[$typeInventaire][$typeItem];
	else
		return array();
}

//Renvoi le premier objet d'un type donné dans un inventaire donné, ou null
/*
TypeInventaire valides:
Inventaire => Inventaire du personnage actif
InventaireCampement => Inventaire du campement de la région active
*/
function getItem($typeItem,$typeInventaire)
{
	$items = getItems($typeItem,$typeInventaire);
	if(empty($items))
		return null;
	else
	{	
		foreach($items as $IDItem=>$item)
			return $item;
	}
}
?>