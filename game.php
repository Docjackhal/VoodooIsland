<?php
session_start();
if(empty($_SESSION))
	header('Location: accueil.php');
else
{
	include_once("prive/config.php");

	if($_SESSION['IDPartieEnCours'] == -1 || $_SESSION['PartieEnCours']['Etat'] == 'en_creation')
		header('Location: lobby.php');
	else
	{
		include_once("updateInformationsSession.php");
		updateInformationsSession();
		$IDPersoActuel = $_SESSION['IDPersonnage'];

		// Heures et cycles
		// Determination du jour et cycle actif
		$currentTimestamp = time("Y-m-d H:i:s");
		$dateDebutPartie = $_SESSION['PartieEnCours']['DateDemarrage'];
		$timestampDebutPartie = strtotime($dateDebutPartie);
		$diffTimestamp = $currentTimestamp - $timestampDebutPartie;

		// Conversion en jour
		//$jour = ceil($diffTimestamp/(60*60*24));

		$heures = floor($diffTimestamp/(60*60));
		$_SESSION['Heures'] = $heures;
		
		// Conversion en cycle	A ENLEVER QUAND CRON ACTIF
		$cycle = (floor($heures/3))%8;
		$_SESSION['PartieEnCours']['Cycle'] = $cycle;

		//$cycle = $_SESSION['PartieEnCours']['Cycle'];
		$jour = $_SESSION['PartieEnCours']['Jour'];
		

		// Nuit / Jour
		if($cycle <= 2 || $cycle >= 7)
		{
			$colorDay = "black";
			$_SESSION['Nuit'] = true;
		}
		else
		{
			$colorDay = "blue";
			$_SESSION['Nuit'] = false;
		}

		// Temps restant
		$H = date('H');
		$M = date('i');
		$S = date('s');
		$currentSec = ($H*60*60) + ($M*60) + ($S);
		$secToNextCycle = (($cycle+1)*3*60*60);
		$secAvantNextCycle = $secToNextCycle-$currentSec;
		$h = floor($secAvantNextCycle/3600);
		$m = floor(($secAvantNextCycle/60)-($h*60));
		$s =  $secAvantNextCycle - ($h*60*60) - ($m*60);

		if($m < 10)
			$m = '0'.$m;
		if($s< 10)
			$s = '0'.$s;

		$time = $h.'h'.$m.'m'.$s.'s';

		$region = $_SESSION['Regions'][$_SESSION['RegionActuelle']];		
	}

function genererBarreStatistique($nomStat)
{
	$personnage = $_SESSION['Heros'][$_SESSION['IDPersonnage']];
	$currentValue = $_SESSION[$nomStat.'Actuel'];
	$maxValue = $personnage[$nomStat.'Max'];
	$pourcentage = ($currentValue/$maxValue)*100;
	$bottom = -(105*(1-($pourcentage/100)));

	$div = "<div class='stat_bloc' id='stat_".$nomStat."'>";

		$div .= "<div class='stat_barre' id='stat_barre_".$nomStat."'>";
		$div .= "<div class='stat_barre_filter' id='stat_barre_".$nomStat."_filter' style='height:".$pourcentage."%;bottom:".$bottom."px;'></div>";
		$div .= "</div>";

		$div .= "<div class='text_stat'>";
			$div .= $nomStat.":</br>";
			$div .= "<span class='stat_value_actuel'>".$currentValue."</span>";
			$div .=  "/";
			$div .= "<span class='stat_value_max'>".$maxValue."</span>";
		$div.= "</div>";
	$div.= "</div>";

	return $div;	
}

function genererInventaire()
{
	$div = "";
	$tailleInventaire = 5;
	for($i = 0; $i < $tailleInventaire; $i++)
	{
		$div .= "<div class='slot_inventaire' id='slot_inventaire_".$i."'>".$i;
		$div .= "</div>";
	}

	return $div;
}

function genererTchat($idCanal)
{
	$content = "";
	if(!empty($_SESSION['Tchats'][$idCanal]))
	{
		$content .= "<div class='enssemble_message' id='enssemble_message_".$idCanal."'>";
		foreach($_SESSION['Tchats'][$idCanal] as $message)
		{
			$content .= "<div class='entree_tchat'>";
				$content .= "<span class='tchat_date'>".substr($message['DateEnvoie'],11).": </span>";
				$content .= "<span class='tchat_auteur'>".$message['Auteur'].": </span>";
				$content .= "<span class='tchat_message'>".$message['Message']."</span>";
			$content .= "</div>";
		}
		$content .= "</div>";

		// Bloc poster message
		$content .= "<div class='bloc_envoi_message' id='bloc_envoi_message_".$idCanal."'>";
				$content .= "<input type='hidden' name='idCanal' value='".$idCanal."'></input>";
				$content .= "<textarea type='text' name='message' maxlength='255' id='zone_envoi_message_".$idCanal."'></textarea>";
				$content .= "<div class='submit' onclick='envoyerMessage(".$idCanal.")'>Envoyer</div>";
		$content .= "</div>";

	}
	else
				$content .= "<div class='enssemble_message' id='enssemble_message_".$idCanal."'><div class='entree_tchat'>Aucun message</div></div>";
	return $content;
}


?>

<html>
<head>
<title>Voodoo Island - Game</title>
<link href="style/game.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/game.js"></script>
</head>

<body>
	<div id='game'>
		<!-- Topbar -->
		<div id='topBar'>
			<div id='horloge'>
				<div class='time'><b>Jour:</b> <?php echo $jour; ?></div>
				<div class='time'><b>Cycle:</b> <?php echo $cycle; ?></div>
				<div class='time'><b>Prochain cycle dans: </b><span id='horloge_js'><?php echo $time; ?></span></div>
				<div id='symbole_soleil' style='background-color:<?php echo $colorDay; ?>'><image src='images/soleils/soleil_<?php echo $cycle; ?>.png'/></div>
			</div>
		</div>

		<!-- Bloc personnage Gauche -->
		<div id="bloc_personnage">
			<div id='nom_personnage'><?php echo $_SESSION['Heros'][$IDPersoActuel]['Nom']?></div>
			<div id='image_personnage' style='background-image:url(images/Personnage_portrait/Personnage_Portrait_<?php echo $IDPersoActuel; ?>.png);'></div>
			<div id='metier_personnage'><?php echo $_SESSION['Heros'][$IDPersoActuel]['Metier']?></div>

			<div id='bloc_stat'>
				<div class='stat_personnage' id='stat_sante'><?php echo genererBarreStatistique("Pv"); ?></div>
				<div class='stat_personnage' id='stat_fatigue'><?php echo genererBarreStatistique("Fatigue"); ?></div>
				<div class='stat_personnage' id='stat_faim'><?php echo genererBarreStatistique("Faim"); ?></div>
				<div class='stat_personnage' id='stat_soif'><?php echo genererBarreStatistique("Soif"); ?></div>
			</div>

			<div id='bloc_main_points'>
				<div class='stat_personnage' id='stat_pa'><?php echo genererBarreStatistique("Pa"); ?></div>
				<div class='stat_personnage' id='stat_pm'><?php echo genererBarreStatistique("Pm"); ?></div>
			</div>

		</div>

		<!-- Bloc Personnage central -->
		<div id="bloc_game">
			<div id='bloc_canvas'>
				<canvas id='canvas'></canvas>
				<div id='zone_lieu'><span><?php echo $_SESSION['Regions'][$_SESSION['RegionActuelle']]['Nom']; ?></span></div>
				<div id='btn_voyager' onclick='switchMode();'>Voyager</div>
			</div>
			<div id='bloc_inventaire'>
				<?php echo genererInventaire(); ?>
			</div>
			<div id='popup_validation_voyage'>
				<div class='popup_voyage_content'>
					<?php
						if($_SESSION['PmActuel'] > 0)
						{
							echo "Voulez-vous voyager jusqu'à </br><span id='name_region'>".$region['Nom']."</span> ?" ;	
							echo "<div class='content_button'>";
							echo "<form METHOD=post ACTION='action.php?action=2'>";
							echo "<input type='hidden' name='idRegion' id='inputIdRegion'></input>";
							echo "<input type='submit' class='submit submit_popup' onclick='SwitchPopupVoyage()' value='Voyager'></input>";
							echo "</form>";
							echo "<button class='submit submit_popup' onclick='SwitchPopupVoyage()'>Fermer</button>";
							
							echo "</div>";
						}
						else
						{
							echo "Vous n'avez plus de points de mouvement!</br> Attendez le prochain cycle pour vous déplacer." ;	
							echo "<div class='content_button'>";
							echo "<button class='submit submit_popup' onclick='SwitchPopupVoyage()'>Fermer</button>";
							echo "</div>";
						}
											
					?>
				</div>
			</div>	
			<div id='popup_interdiction_voyage'>
				<div class='popup_voyage_content'>
					Cette région n'est pas accessible depuis l'endroit où vous vous trouvez.
					<div class='content_button'>
						<button class='submit submit_popup' onclick='SwitchPopupInterdictionVoyage()'>Fermer</button>
					</div>";
				</div>
			</div>			
		</div>

		<!-- Bloc Tchat droit -->
		<div id="bloc_tchat">
			<div id='bloc_selection_channels'>
				<div class='selection_channel selected' id='selection_channel_<?php echo $_SESSION['ID'] ?>'  onclick='switchTchat("<?php echo $_SESSION['ID'] ?>")'>Personnel</div>
				<div class='selection_channel' id='selection_channel_<?php echo $_SESSION['RegionActuelle'] ?>'  onclick='switchTchat("<?php echo $_SESSION['RegionActuelle'] ?>")'>Local</div>
				<div class='selection_channel' id='selection_channel_0' onclick='switchTchat(0)'>Général</div>
			</div>

			<div id='contener_tchat'>
				<div class='tchat' id='tchat_<?php echo $_SESSION['ID'] ?>'>
					<?php echo genererTchat($_SESSION['ID']); ?>
				</div>
				<div class='tchat' id='tchat_<?php echo $_SESSION['RegionActuelle'] ?>' style='display:none'>
					<?php echo genererTchat($_SESSION['RegionActuelle']); ?>
				</div>
				<div class='tchat' id='tchat_0' style='display:none'>
					<?php echo genererTchat(0); ?>
				</div>

			</div>
		</div>


	</div>

	<div id='footer'>
		<a href='deconnexion.php'><div class='btn' id='deconnexion_button'>Deconnexion</div></a>
	</div>
</body>


<script type="text/javascript" src="js/run.js"></script>
</html>

<?php
}
?>