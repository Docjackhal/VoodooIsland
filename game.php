<?php
session_start();
if(empty($_SESSION))
	header('Location: accueil.php');
else
{
	include_once("prive/config.php");
	include_once("fonctionsGlobales.php");
	include_once("updateInformationsSession.php");

	if($_SESSION['IDPartieEnCours'] == -1 || $_SESSION['PartieEnCours']['Etat'] == 'en_creation')
		header('Location: lobby.php');
	else
	{
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

// ----------------------- Fonctions Graphiques ---------------------------
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

function genererHTMLInventaireJoueur()
{
	$html = "";
	/*for($i=0;$i<70;$i++)*/ // Test
	foreach($_SESSION["Inventaire"] as $IDTypeItem => $listeItemsDeType)
	{
		foreach($listeItemsDeType as $IDItem => $item)
		{
			$html .= "<div class='bloc_inventaire' id='bloc_inventaire_".$IDItem."' IDTypeItem='".$IDTypeItem."' IDItem='".$IDItem."'>";
				$html .= "<img src='images/items/item_".$IDTypeItem.".png' width='65px' height='55px' align='middle'/>";
			$html .= "</div>";
		}
	}
	return $html;
}

function AP($cout)
{
	echo "<div class='iconeCoutAP";
	if($cout > $_SESSION["PaActuel"])
		echo " iconeCoutInsufisant";
	echo "'>";
	echo $cout;
	echo "</div>";
}

function PM($cout)
{
	echo "<div class='iconeCoutPM";
	if($cout > $_SESSION["PmActuel"])
		echo " iconeCoutInsufisant";
	echo "'>";
	echo $cout;
	echo "</div>";
}

// ----------------------- Fin Fonctions Graphiques ---------------------------
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
				<div id='btn_voyager' onclick='switchMode();'>Voyager <?php PM(getCoutDeplacement());?></div>
				<div id='btn_explorer' onclick='SwitchPopupValidationExploration();'>Explorer <?php AP(getCoutExploration());?></div>
			</div>
			<div id='menu_bas'>
				<div class='bloc_menu_bas' id="bloc_menu_bas_0">
					<img src="images/UI/icone_inventaire.png" width="150" height="150" align="middle"/>
				</div>
				<div class='bloc_menu_bas' id="bloc_menu_bas_1"></div>
				<div class='bloc_menu_bas' id="bloc_menu_bas_2"></div>
				<div class='bloc_menu_bas' id="bloc_menu_bas_3"></div>
				<div class='bloc_menu_bas' id="bloc_menu_bas_4"></div>
			</div>
			<div id='popup_validation_voyage'>
				<div class='popup_content'>
					<?php
						if($_SESSION['PmActuel'] >= getCoutDeplacement())
						{
							echo "Voulez-vous voyager jusqu'à </br><span id='name_region'>".$region['Nom']."</span> ?" ;	
							echo "<div class='content_button'>";
							echo "<form METHOD=post ACTION='action.php?action=2'>";
							echo "<input type='hidden' name='idRegion' id='inputIdRegion'></input>";
							echo "<input type='submit' class='submit submit_popup' onclick='SwitchPopupVoyage()' value='Voyager (".getCoutDeplacement()."Pm)'></input>";
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
				<div class='popup_content'>
					Cette région n'est pas accessible depuis l'endroit où vous vous trouvez.
					<div class='content_button'>
						<button class='submit submit_popup' onclick='SwitchPopupInterdictionVoyage()'>Fermer</button>
					</div>";
				</div>
			</div>
			<div id='popup_validation_exploration'>
				<div class='popup_content'>
					Voulez-vous explorer cette zone?
					<div class='content_button'>
						<form METHOD=post ACTION='action.php?action=3'>
							<input type='submit' class='submit submit_popup' value='Explorer (<?php echo getCoutExploration();?>AP)'></input>
						</form>
						<button class='submit submit_popup' onclick='SwitchPopupValidationExploration()'>Annuler</button>
					</div>
				</div>
			</div>		

			<?php
			// Popup Message Simple
				if(!empty($_SESSION["Message"]))
				{
					?>
					<div id='popup_message'>
						<div class='popup_content'>
							<?php echo $_SESSION["Message"];?>
							<div class='content_button'>
								<button class='submit submit_popup' onclick='fermerPopupMessage()'>Daccord</button>
							</div>
						</div>
					</div>	
					<?php
					$_SESSION["Message"] = "";
				}

			//Popup Evenement
				if(!empty($_SESSION["PopupEvenement"]))
				{
					$titre = $_SESSION["PopupEvenement"]["Titre"];
					$message = $_SESSION["PopupEvenement"]["Message"];
					$texteBouton = (isset($_SESSION["PopupEvenement"]["TexteBouton"])) ? $_SESSION["PopupEvenement"]["TexteBouton"] : "Daccord";
					?>
					<div id='popup_evenement'>
						<div class='popup_content'>
							<div class="titreEvenement"><?php echo $titre;?></div>
							<div class="messageEvenement"><?php echo $message;?></div>
							<div class='content_button'>
								<button class='submit submit_popup' onclick='fermerPopupEvenement()'><?php echo $texteBouton;?></button>
							</div>
						</div>
					</div>	
					<?php
					//$_SESSION["PopupEvenement"] = array();
				}	
			?>	
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

		<!-- Popup Inventaire -->
		<div id="popup_inventaire">	
			<div id="popup_inventaire_titre">Inventaire</div>
			<div id="popup_inventaire_close" class="popup_close">X</div>
			<div id="popup_inventaire_contenu"><?php echo genererHTMLInventaireJoueur();?></div>
		</div>

		<div id="popup_zoomItem">
			<div id="popup_zoomItem_titre">Titre</div>
			<div id="popup_zoomItem_close" class="popup_close">X</div>
			<div id="popup_zoomItem_contenu">
				<div id="popup_zoomItem_ZoneImage">
					<img id="popup_zoomItem_image" src='images/items/item_1.png' width='130px' height='110px' align='middle'/>
				</div>
			</div>
			<div id="popup_zoomItem_description">blablabla</div>
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