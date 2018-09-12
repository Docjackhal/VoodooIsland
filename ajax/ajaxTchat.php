<?php
session_start();
header('Content-type: text/json');
include_once("../prive/config.php");


$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
mysqli_set_charset($mysqli, "utf8");

$result = array();

switch($_GET['action'])
{
	case "Chargement":
	{
		
	}
	break;
}

echo json_encode($result);
?>