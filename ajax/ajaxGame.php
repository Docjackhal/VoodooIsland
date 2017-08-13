<?php
session_start();
header('Content-type: text/json');

$result = array();

switch($_GET['action'])
{
	case "Chargement":
	{
		$result['Region'] = $_SESSION['Regions'][$_SESSION['RegionActuelle']];
		$result['Regions'] = $_SESSION['Regions'];
		$result['IDPersoActuel'] = $_SESSION['IDPersonnage'];
		$result['PersonnagesDansRegion'] =  $_SESSION["PersonnagesDansRegion"];
		$result['Inventaire'] =  $_SESSION["Inventaire"];
		$result['TypesItems'] =  $_SESSION["TypesItems"];

		// Lieux découverts
		$lieuxDecouverts = array();
		foreach($_SESSION['LieuxDansRegion'] as $IDLieu => $lieu)
		{
			if($lieu["EtatDecouverte"] == "Visible" ||true)
				$lieuxDecouverts[$IDLieu] = $lieu;
		}
		$result['LieuxDecouverts'] = $lieuxDecouverts;
	}
	break;
}


echo json_encode($result);
?>