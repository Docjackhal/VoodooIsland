<?php

// Cette fonction renvoi la liste des evenements possibles selon la région actuelle et l'état de la partie
function getEvenementsPossibles()
{
	$eventsPossibles = array();
	$variables = $_SESSION["Variables"]; // Variables actuelles de la partie.
	$regionActuelle = $_SESSION["Regions"][$_SESSION["RegionActuelle"]];

	foreach($_SESSION["Evenements"] as $IDEvent => $event)
	{
		// Test du type de région
		if($event["TypeRegion"] != "toutes" && $event["TypeRegion"] == $regionActuelle["Type"])
			continue;

		// Test des conditions
		$conditionsValidees = true;
		for($i = 1; $i <= 2; $i++)
		{
			$type = $event["Condition".$i."_Type"];
			$operateur = $event["Condition".$i."_Operateur"];
			$valeurA = $event["Condition".$i."_ValeurA"];
			$valeurB = $event["Condition".$i."_ValeurB"];

			if($type == null)
				continue;

			$a = $valeurA;
			$b = $valeurB;

			if($type == "variable")
				$a = getVariable($valeurA);
		
			$conditionValide = true;
			switch($operateur)
			{
				case ">":
					$conditionValide = ($a > $b);
					break;
				case ">=":
					$conditionValide = ($a >= $b);
					break;
				case "<":
					$conditionValide = ($a < $b);
					break;
				case "<=":
					$conditionValide = ($a <= $b);
					break;
				case "==":
					$conditionValide = ($a == $b);
					break;
				case "!=":
					$conditionValide = ($a != $b);
					break;
			}

			if(!$conditionValide)
			{
				$conditionsValidees = false;
				break;
			}	
		}

		$conditionScriptee = getConditionsScripteesEvenement($IDEvent);

		if($conditionsValidees && $conditionScriptee)
			$eventsPossibles[$IDEvent] = $event;
	}

	return $eventsPossibles;
}

// Cette méthode permet de scripter une condition un peu trop complexe pour etre configurée en bdd
function getConditionsScripteesEvenement($IDEvent)
{
	$conditionScriptee = true;
	switch($IDEvent)
	{
		default:
			break;
	}
	return $conditionScriptee;
}
	
?>