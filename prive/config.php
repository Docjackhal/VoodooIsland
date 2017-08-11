<?php

if(file_exists("isProd.iv")) // Ce fichier (vide) doit etre mis à la racine de config en prod
	include_once("config_sql_prod.php");
else
	include_once("config_sql.php");


date_default_timezone_set("Europe/Paris"); 
?>