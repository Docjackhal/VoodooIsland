<?php

	// Renvoi une condition affectuant le joueur, ou null si non affecté
	function getCondition($IDCondition)
	{
		if(!empty($_SESSION['Conditions'][$IDCondition]))
			return $_SESSION['Conditions'][$IDCondition];
		else
			return null;
	}

	// Ajoute une condition au joueur si il n'est pas deja atteint par cette condition.
	// Return true si ajouté ou false si deja possédée
	function ajouteCondition($IDCondition,$IDPersonnage)
	{
		global $PT;
		if(getCondition($IDCondition) == null)
		{
			$requete = "INSERT INTO ".$PT."conditions (IDPartie,IDPersonnage,IDCondition) VALUES (".$_SESSION["IDPartie"].",".$IDPersonnage.",".$IDCondition.")";
			$retour = mysqli_query($mysqli,$requete);
			if (!$retour) trigger_error('Requête invalide : '.$requete . mysqli_error($mysqli));
		}
		else 
			return false;
	}

	// Supprime la condition IDCondition du personnage IDPersonnage de la partie en cours.
	function supprimeCondition($IDCondition,$IDPersonnage)
	{
		global $PT;
		$requete = "DELETE FROM ".$PT."conditions WHERE IDPartie=".$_SESSION["IDPartie"].",IDPersonnage=".$IDPersonnage.",IDCondition=".$IDCondition;
		$retour = mysqli_query($mysqli,$requete);
			if (!$retour) trigger_error('Requête invalide : '.$requete . mysqli_error($mysqli));
	}

	// Augmente de 1 la durée des conditions actives de la partie en cours
	function incrementeConditionsPartie()
	{
		global $PT;
		$requete = "UPDATE ".$PT."conditions SET DureeCondition = DureeCondition+1 WHERE IDPartie=".$_SESSION["IDPartie"];
		$retour = mysqli_query($mysqli,$requete);
			if (!$retour) trigger_error('Requête invalide : '.$requete . mysqli_error($mysqli));
	}
?>