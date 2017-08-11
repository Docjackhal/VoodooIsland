<?php

// Cron de nouveau cycle

include_once("../prive/config.php");
$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
mysqli_set_charset($mysqli, "utf8");


// Préparation
$test = false;

echo "TEST :".$test."</br>";

// Recuperation des heros
$listHeros = array();
$requete = "SELECT * FROM  ".$PT."heros";
$retour = mysqli_query($mysqli,$requete);
if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));
while($heros = mysql_fetch_assoc($retour))
	$listHeros[$heros['ID']] = $heros;


$requete = "SELECT * FROM  ".$PT."parties WHERE ETAT= 'en_cours'";
$retour = mysqli_query($mysqli,$requete);
if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

if(mysqli_num_rows($retour))
{
	while($partie = mysqli_fetch_assoc($retour))
	{
		$oldCycle = $partie['Cycle'];
		$jour = $partie['Jour'];
		$idPartie = $partie['ID'];

		echo "---------------------------------- Partie numero ".$idPartie." ----------------------------------</br></br>";

		// Incrémentation du cycle
		$newCycle = $oldCycle+1;
		if($newCycle > 8)
		{
			$newCycle = 1;
			$jour++;
		}
			

		echo "Passage du cycle ".$oldCycle." au cycle ".$newCycle."</br>";

		// Update final de la partie
		if(!$test)
		{
			$requete2 = "UPDATE  ".$PT."parties SET Cycle='".$newCycle."', Jour='".$jour."' WHERE ID= '".$idPartie."'";
			$retour2 = mysql_queryi($mysqli,$requete2);
			if (!$retour2) die('Requête invalide : ' . mysqli_error($mysqli));
		}

		// Gestion des joueurs de la partie
		$requete = "SELECT * FROM  ".$PT."personnages WHERE idPartie= '".$idPartie."' AND Actif='o'";
		$retour = mysqli_query($mysqli,$requete);
		if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

		if(mysqli_num_rows($retour))
		{
			while($personnage = mysqli_fetch_assoc($retour))
			{
				$login = $personnage['Joueur'];
				$idHeros = $personnage['IDHeros'];
				$heros = $listHeros[$idHeros];

				// Mise a jour des caractéristiques
				echo "</br><b>Mise à jour de ".$heros['Nom'].", joué par ".$login."</b></br></br>";
	
				$idPersonnage = $personnage['ID'];
				$fatigue = $personnage['FatigueActuel'];
				$faim = $personnage['FaimActuel'];
				$soif = $personnage['SoifActuel'];
				$pa = $personnage['PaActuel'];
				$pm = $personnage['PmActuel'];
				$pv = $personnage['PvActuel'];

				// Augmentation des PA de 2
				$pa += 2;
				if($pa > $heros['PaMax'])
					$pa = $heros['PaMax'];
				echo "PA: ".$personnage['PaActuel']." => ".$pa."</br>";

				// Recuperation des PM MAX
				$pm = $heros['PmMax'];
				if($pm > $heros['PmMax'])
					$pm = $heros['PmMax'];
				echo "PM: ".$personnage['PmActuel']." => ".$pm."</br>";

				//Perte de faim et de soif
				if($soif > 0)
				{
					$soif--;
					echo "Soif: ".$personnage['SoifActuel']." => ".$soif."</br>";
				}
				else
				{
					$pv -= 2;
					$soif = 0;
					echo "Soif: Zero, perte de pv : ".$personnage['PvActuel']." => ".$pv."</br>";
				}

				if($faim > 0)
				{
					$faim--;
					echo "Faim: ".$personnage['FaimActuel']." => ".$faim."</br>";
				}
				else
				{
					$pv -= 2;
					$faim = 0;
					echo "Faim: Zero, perte de pv : ".$personnage['PvActuel']." => ".$pv."</br>";
				}

				// Regain de fatigue
				$fatigue += 1;
				if($fatigue > $heros['FatigueMax'])
					$fatigue = $heros['FatigueMax'];
				echo "FatigueMax: ".$personnage['FatigueActuel']." => ".$fatigue."</br>";

				if($pv <= 0)
				{
					$pv = 0;
					// Personnage mort
					TuerPersonnage($personnage, $heros);
				}
					
				// Update final
				if(!$test)
				{
					$requete2 = "UPDATE  ".$PT."personnages SET FatigueActuel = '".$fatigue."', FaimActuel = '".$faim."', SoifActuel = '".$soif."', PaActuel = '".$pa."', PmActuel = '".$pm."', PvActuel = '".$pv."' WHERE ID= '".$idPersonnage."'";
					$retour2 = mysqli_query($mysqli,$requete2);
					if (!$retour2) die('Requête invalide : ' . mysqli_error($mysqli));
				}			
			}
		}
		else
		{
			// FIN DE LA PARTIE
			echo "Plus aucun personnage de vivant dans la partie, fin de la partie.";
		}

	}
}
		
function TuerPersonnage($personnage,$heros)
{
	global $test, $PT, $mysqli;

	echo "</br><span style='color:red;'><b>Le Personnage ".$heros['Nom']." joué par ".$personnage['Joueur']." est MORT </b></span></br></br>";

	if(!$test)
	{
			// Mise hors activité du personnage
		$requete = "UPDATE  ".$PT."personnages SET Actif = 'n' WHERE ID= '".$personnage['ID']."'";
		$retour = mysqli_query($mysqli,$requete);
				if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

		// Nouvelle partie pour le joueur
		$requete = "UPDATE  ".$PT."accounts SET IDPartieEnCours = '-1' WHERE Login= '".$personnage['Joueur']."'";
		$retour = mysqli_query($mysqli,$requete);
			if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));
	}
}	

?>