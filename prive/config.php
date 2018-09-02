<?php

include_once("config_sql.php");

date_default_timezone_set("Europe/Paris"); 

// Variables jeu
const COUT_DEPLACEMENT = 0; // 1
const COUT_EXPLORATION = 0; // 2

//Peche
const COUT_PECHE = 1; // 1
const CHANCE_BASE_PECHE = 50;
const BOOST_HARPON_PECHE = 30;
const CHANCE_PECHE_TORTUE = 10;

//Capture et rites voodoo
const DUREE_CYCLE_CAPTURE = 6;
const DUREE_CYCLE_RITE = 6;

?>