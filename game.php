<?php
session_start();
if(empty($_SESSION))
	header('Location: accueil.php');
else
{
	include_once("fonctionsLangue.php");
	include_once("prive/config.php");
	include_once("fonctionsLieux.php");
	include_once("fonctionsPersonnages.php");
	include_once("fonctionsGlobales.php");
	include_once("fonctionsTchats.php");
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
		$estReady = $_SESSION["PretCycleSuivant"];	

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

		$title = lang("Tooltip_BarresStats_".$nomStat);
		if($nomStat == "Pa")
			$title = str_replace("%Number%", GAIN_PA_CYCLE, $title);
		if($nomStat == "Pm")
			$title = str_replace("%Number%", GAIN_PM_CYCLE, $title);

		$div .= "<div title=\"".$title."\" class='stat_barre' id='stat_barre_".$nomStat."'>";
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

function genererHTMLInventaireJoueur()
{
	$html = "";
	/*for($i=0;$i<70;$i++)*/ // Test
	foreach($_SESSION["Inventaire"] as $IDTypeItem => $listeItemsDeType)
	{
		foreach($listeItemsDeType as $IDItem => $item)
		{
			$html .= "<div class='bloc_inventaire' id='bloc_inventaire_".$IDItem."' IDTypeItem='".$IDTypeItem."' IDItem='".$IDItem."'>";
				$html .= "<img title='".ucfirst(lang("Item_".$IDTypeItem."_Nom"))."' src='images/items/item_".$IDTypeItem.".png' width='65px' height='55px' align='middle'/>";
			$html .= "</div>";
		}
	}
	return $html;
}

function genererHTMLInventaireCampement()
{
	$html = "";
	if(isset($_SESSION["InventaireCampement"]))
	{
		foreach($_SESSION["InventaireCampement"] as $IDTypeItem => $listeItemsDeType)
		{
			foreach($listeItemsDeType as $IDItem => $item)
			{
				$html .= "<div class='bloc_inventaire' id='bloc_inventaire_".$IDItem."' IDTypeItem='".$IDTypeItem."' IDItem='".$IDItem."'>";
					$html .= "<img title='".ucfirst(lang("Item_".$IDTypeItem."_Nom"))."' src='images/items/item_".$IDTypeItem.".png' width='65px' height='55px' align='middle'/>";
				$html .= "</div>";
			}
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
<link href="style/tchat.css" rel="stylesheet" type="text/css"/>
<link rel="icon" type="image/png" href="images/fav.png" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/lang.js"></script>
<script type="text/javascript" src="js/lieux.js"></script>
<script type="text/javascript" src="js/tchat.js"></script>
<script type="text/javascript" src="js/items.js"></script>
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
			<div id='btn_rdy_y' title="Vous êtes prêts à passer au prochain cycle." style='display:<?php echo(($estReady)?"block":"none");?>'></div>
			<div id='btn_rdy_n' title="Cliquez ici pour indiquer au MJ que vous êtes prêt à passer au prochain cycle." style='display:<?php echo(($estReady)?"none":"block");?>'></div>
		</div>

		<!-- Bloc personnage Gauche -->
		<div id="bloc_personnage">
			<div id='nom_personnage'><?php echo $_SESSION['Heros'][$IDPersoActuel]['Prenom']." ".$_SESSION['Heros'][$IDPersoActuel]['Nom']?></div>
			<div id='image_personnage' style='background-image:url(images/Personnage_portrait/Personnage_Portrait_<?php echo $IDPersoActuel; ?>.png);'></div>
			<div id='metier_personnage'><?php echo $_SESSION['Heros'][$IDPersoActuel]['Metier']?></div>

			<div id='bloc_etats'>
				<?php
					if(!empty($_SESSION["Conditions"]))
					{
					foreach($_SESSION["Conditions"] as $IDCondition=>$condition)
						{
							$parametresCondition = $_SESSION["ParametresConditions"][$IDCondition];
							echo "<div class='bloc_condition bloc_condition_".$parametresCondition["Type"]."' IDCondition='".$IDCondition."' id='bloc_condition_".$IDCondition."' style='background-image:url(images/Conditions/condition_".$IDCondition.".png);'></div>";
						}
					}
					else
						echo "Vous êtes en bonne santé.";
				?>		
			</div>

			<div id='bloc_stat'>
				<div class='stat_personnage' id='stat_pa'><?php echo genererBarreStatistique("Pa"); ?></div>
				<div class='stat_personnage' id='stat_pm'><?php echo genererBarreStatistique("Pm"); ?></div>
				<div class='stat_personnage' id='stat_sante'><?php echo genererBarreStatistique("Pv"); ?></div>
				<div class='stat_personnage' id='stat_mp'><?php echo genererBarreStatistique("MP"); ?></div>
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
					<img title="Votre inventaire" src="images/UI/icone_inventaire.png" width="150" height="150" align="middle"/>
				</div>
				<div class='bloc_menu_bas' id="bloc_menu_bas_1"></div>
				<div class='bloc_menu_bas' id="bloc_menu_bas_2"></div>
				<div class='bloc_menu_bas' id="bloc_menu_bas_3"></div>
				<div class='bloc_menu_bas' id="bloc_menu_bas_4"></div>
			</div>
			<div id='popup_validation_voyage' class="popup">
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
			<div id='popup_interdiction_voyage' class='popup'>
				<div class='popup_content'>
					Cette région n'est pas accessible depuis l'endroit où vous vous trouvez.
					<div class='content_button'>
						<button class='submit submit_popup' onclick='SwitchPopupInterdictionVoyage();'>Fermer</button>
					</div>
				</div>
			</div>
			<div id='popup_validation_exploration' class='popup'>
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
			<div id='popup_lieu' class='popup'>
				<div class='popup_content'>	
					<div id="popup_lieu_close" class="popup_close">X</div>		
					<div id='popup_lieu_titre'></div>
					<div id='popup_lieu_illustration'></div>
					<div id='popup_lieu_description'></div> 					
					<div id='popup_lieu_infos'></div>
					<div id='popup_lieu_actions'> </div>				
				</div>
			</div>
			<?php
			// Popup Message Simple
				if(!empty($_SESSION["Message"]))
				{
					?>
					<div id='popup_message' class='popup'>
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

			//Popup Evenement simple
				if(!empty($_SESSION["PopupEvenement"]))
				{
					$titre = $_SESSION["PopupEvenement"]["Titre"];
					$message = $_SESSION["PopupEvenement"]["Message"];
					$texteBouton = (isset($_SESSION["PopupEvenement"]["TexteReponse"])) ? $_SESSION["PopupEvenement"]["TexteReponse"] : "Daccord";
					?>
					<div id='popup_evenement' class='popup'>
						<div class='popup_content'>
							<div class="titrePopupEvenement">! Événement !</div>
							<div class="titreEvenement"><?php echo $titre;?></div>
							<div class="messageEvenement"><?php echo $message;?></div>

							<?php

							//Liste des items perdus
								if(!empty($_SESSION["PopupEvenement"]["PertesItems"]))
								{
									echo "<div class='popupEvenement_listeGainsItems'>";
										echo "<div class='popupEvenement_listeGainsItems_titre red'>".lang("Pertes:")."</div>";
										foreach($_SESSION["PopupEvenement"]["PertesItems"] as $IDItem => $IDTypeItem)
										{
											$blocItemPerdu = "<div class='bloc_inventaire' id='bloc_inventaire_".$IDTypeItem."' IDTypeItem='".$IDTypeItem."' IDItem='".$IDItem."'>";
												$blocItemPerdu .= "<img title='".ucfirst(lang("Item_".$IDTypeItem."_Nom"))."' src='images/items/item_".$IDTypeItem.".png' width='65px' height='55px' align='middle'/>";
											$blocItemPerdu .= "</div>";
											echo $blocItemPerdu;
										}
									echo "</div>";
								}

							// Liste des items gagnés
								if(!empty($_SESSION["PopupEvenement"]["GainsItems"]))
								{
									echo "<div class='popupEvenement_listeGainsItems'>";
										echo "<div class='popupEvenement_listeGainsItems_titre green'>".lang("Gains:")."</div>";
										foreach($_SESSION["PopupEvenement"]["GainsItems"] as $IDItem => $IDTypeItem)
										{
											$blocItemGagne = "<div class='bloc_inventaire' id='bloc_inventaire_".$IDTypeItem."' IDTypeItem='".$IDTypeItem."' IDItem='".$IDItem."'>";
												$blocItemGagne .= "<img title='".ucfirst(lang("Item_".$IDTypeItem."_Nom"))."' src='images/items/item_".$IDTypeItem.".png' width='65px' height='55px' align='middle'/>";
											$blocItemGagne .= "</div>";
											echo $blocItemGagne;
										}
									echo "</div>";
								}

							//Lieux découverts
								if(!empty($_SESSION["PopupEvenement"]["LieuDecouvert"]) && $_SESSION["PopupEvenement"]["LieuDecouvert"] > 0)
								{
									$IDLieu = $_SESSION["PopupEvenement"]["LieuDecouvert"];
									$IDTypeLieu = $_SESSION["LieuxDansRegion"][$IDLieu]["IDTypeLieu"];
									echo "<div style='margin-top:10px;'>".str_replace("%NomLieu%","<span class='blue'>".lang("Lieu_".$IDTypeLieu."_Nom")."</span>",lang("EvenementLieuDecouvert"))."</div>";
									echo "<div class='blocImageLieu'>";
										echo "<img src='images/lieux/Visuels/visuels_".$IDTypeLieu.".png' width='400px' height='150px' align='middle'/>";
									echo "</div>";				
								}
							?>


							<div class='content_button'>
								<button class='submit submit_popup' onclick='fermerPopupEvenement()'><?php echo $texteBouton;?></button>
							</div>
						</div>
					</div>	
					<?php
					$_SESSION["PopupEvenement"] = array();
				}	

				//Popup Evenement Complexe
				if(!empty($_SESSION["PopupEvenementComplexe"]))
				{
					$titre = $_SESSION["PopupEvenementComplexe"]["Titre"];
					$message = $_SESSION["PopupEvenementComplexe"]["Message"];
					?>
					<div id='popup_evenement_complexe' class='popup'>
						<div class='popup_content'>				
							<div class="titrePopupEvenement">! Événement !</div>
							<div class="titreEvenement"><?php echo $titre;?></div>
							<div class="messageEvenement"><?php echo $message;?></div>

							<?php
								foreach($_SESSION["PopupEvenementComplexe"]["Choix"] as $nbChoix => $texteChoix)
								{
									?>
									<div class='content_button'>
										<form METHOD=post ACTION='action.php?action=4'>
											<input type="hidden" name="IDEvent" value="<?php echo $_SESSION["PopupEvenementComplexe"]["IDEvent"];?>"/>
											<input type="hidden" name="IDReponse" value="<?php echo  $nbChoix;?>"/>
											<input type="submit" class='submit submit_popup submit_popup_eventComplexe' value="<?php echo $texteChoix;?>"/>
										</form>
									</div>
									<?php
								}					
							?>
						</div>
					</div>	
					<?php
				}	
			?>	
		</div>

		<!-- Bloc Tchat droit -->
		<div id="bloc_tchat">
			<div id='bloc_selection_channels'>
				<div class='selection_channel selected' id='selection_channel_Region'  onclick='switchTchat("Region")'><?php echo lang("Tchat_Region");?></div>
				<div class='selection_channel' id='selection_channel_Partie'  onclick='switchTchat("Partie")'><?php echo lang("Tchat_Partie");?></div>		
				<div class='selection_channel' id='selection_channel_Radio' onclick='switchTchat("Radio")'><?php echo lang("Tchat_Radio");?></div>
			</div>

			<div id='contener_tchat'>
				<div class='tchat' id='tchat_Region'>
					<?php echo genererTchat("Region"); ?>
				</div>
				<div class='tchat' id='tchat_Partie' style='display:none'>
					<?php echo genererTchat("Partie"); ?>
				</div>
				<div class='tchat' id='tchat_Radio' style='display:none'>
					<?php echo genererTchat("Radio"); ?>
				</div>

			</div>
		</div>

		<!-- Popup Inventaire -->
		<div id="popup_inventaire" class='popup'>	
			<div id="popup_inventaire_titre"><?php echo lang("Inventaire");?></div>
			<div id="popup_inventaire_close" class="popup_close">X</div>
			<div id="popup_inventaire_contenu"><?php echo genererHTMLInventaireJoueur();?></div>
		</div>

		<!-- Popup Inventaire Campement -->
		<div id="popup_inventaireCampement" class='popup'>	
			<div id="popup_inventaireCampement_titre"><?php echo lang("InventaireCampement");?></div>
			<div id="popup_inventaireCampement_close" class="popup_close">X</div>
			<div id="popup_inventaireCampement_contenu"><?php echo genererHTMLInventaireCampement();?></div>
		</div>

		<div id="popup_zoomItem" class='popup'>
			<div id="popup_zoomItem_titre">Titre</div>
			<div id="popup_zoomItem_close" class="popup_close">X</div>
			<div id="popup_zoomItem_contenu">
				<div id="popup_zoomItem_ZoneImage">
					<img id="popup_zoomItem_image" src='images/items/item_1.png' width='130px' height='110px' align='middle'/>
				</div>
			</div>
			<div id="popup_zoomItem_description"></div>
			<div id="popup_zoomItem_actionsEtInfos"></div>
		</div>

		<!-- Popup Condition/Etats -->
		<div id="popup_condition" class='popup'>
			<div class='condition_icone'></div>
			<div class='condition_titre'>Titre</div>
			<div class='condition_description'>Description</div>
			<div id="popup_condition_close" class="popup_close">X</div>
		</div>

		<!-- Popup Confirmation Cycle Suivant -->
		<div id="popup_confirmationCycle" class='popup'>
			<div class="confirmationCycle_description"><?php echo $lang["DescriptionConfirmationChangementCycle"];?></div>
			<div class="ico_btn_ready"></div>
			<div class="confirmationCycle_blocChoix">
				<form METHOD=post ACTION='action.php?action=5'>
					<input class="btnConfirmerCycle btnConfirmerCycleY" type="submit" value="<?php echo $lang["Oui"];?>"/>
				</form>
				<div class="btnConfirmerCycle"><?php echo $lang["Non"];?></div>
			</div>
		</div>

	</div>

	<div id='footer'>
		<a href='deconnexion.php'><div class='btn' id='deconnexion_button'>Deconnexion</div></a>
	</div>
</body>

<script type="text/javascript" src="js/run.js"></script>
<script type="text/javascript" src="js/configZindexRegions.js"></script>

<!--Fragments langues JS-->
<script>
	window.lang = {};
	<?php
		foreach($lang as $libele=>$content)
			echo "lang['".$libele."'] = \"".$content."\";";
	?>
</script>

</html>

<?php
}
?>