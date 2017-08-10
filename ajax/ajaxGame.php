<?php
session_start();
header('Content-type: text/json');

$result = array();

$result['Region'] = $_SESSION['Regions'][$_SESSION['RegionActuelle']];
$result['Regions'] = $_SESSION['Regions'];
$result['IDPersoActuel'] = $_SESSION['IDPersonnage'];
echo json_encode($result);
?>