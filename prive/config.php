<?php

include_once("config_sql.php");

date_default_timezone_set("Europe/Paris"); 

// Variables jeu
const COUT_DEPLACEMENT = 2; // 2
const COUT_EXPLORATION = 1; // 1
const GAIN_PA_CYCLE = 2;
const GAIN_PM_CYCLE = 1;

//Tchat
const HISTORIQUE_TCHAT = 10; // Nombre de messages récupérés

//Peche
const COUT_PECHE = 1; // 1
const CHANCE_BASE_PECHE = 50;
const BOOST_HARPON_PECHE = 30;
const CHANCE_PECHE_TORTUE = 10;

//Emplacement campement
const COUT_INSTALLATION_CAMPEMENT = 1;

//Campement
const COUT_ALLUMER_FEU = 1;
const CHANCE_ALLUMER_FEU_SILEX = 90;
const CHANCE_ALLUMER_FEU_BOIS = 20;
const STOCK_BUCHE_MAX_FEU = 5;

//Capture et rites voodoo
const DUREE_CYCLE_CAPTURE = 6;
const DUREE_CYCLE_RITE = 6;

?>