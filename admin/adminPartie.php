<?php

	//echo phpinfo();

	$IDPartie = $_SESSION["Admin"]["IDPartieEnCours"];

	$IDOnglet = (isset($_GET['o'])) ? $_GET['o'] : 'p';

	// Chargement de la partie
	$requete = "SELECT * FROM ".$PT."parties WHERE ID = ".$IDPartie;
	$retour = mysqli_query($mysqli,$requete);
		if (!$retour) trigger_error('Impossible de selectionner les parties : ' . mysqli_error($mysqli));
	$partie = mysqli_fetch_assoc($retour);

	// Chargement des personnages
	$requete = "SELECT * FROM ".$PT."personnages WHERE IDPartie = ".$IDPartie;
	$retour = mysqli_query($mysqli,$requete);
		if (!$retour) trigger_error('Impossible de selectionner les personnages : ' . mysqli_error($mysqli));
	$personnages = array();
	while($personnage = mysqli_fetch_assoc($retour))
		$personnages[$personnage["IDHeros"]] = $personnage;
	

	// CHargement des comptes joueurs
	$requete = "SELECT ID,Login FROM ".$PT."accounts WHERE IDPartieEnCours = ".$IDPartie;
	$retour = mysqli_query($mysqli,$requete);
		if (!$retour) trigger_error('Impossible de selectionner les comptes joueurs : ' . mysqli_error($mysqli));
	$joueurs = array();
	while($joueur = mysqli_fetch_assoc($retour))
		$joueurs[$joueur["ID"]] = $joueur;

	// CHargement des comptes joueurs
	$requete = "SELECT * FROM ".$PT."heros";
	$retour = mysqli_query($mysqli,$requete);
		if (!$retour) trigger_error('Impossible de selectionner les profils heros : ' . mysqli_error($mysqli));
	$heros = array();
	while($hero = mysqli_fetch_assoc($retour))
		$heros[$hero["ID"]] = $hero;
	
?>

<!-- Popup Don  Objet -->
<div class="popup" id='popupDonObjet'>
	<h2>Donner des objets à <span class="labelNomHeros">???</span></h2>
	<form action="actionAdmin.php?action=1" method="post">
		<input class="inputIDHeros" name="IDHeros" value="" type="hidden"/>
		<div>
			Objet: <select name="IDItem">
			<?php
				foreach($_SESSION["Admin"]["TypeItems"] as $item)
					echo "<option value='".$item["ID"]."'>(".$item["ID"].") ".$item["NomFR"]."</option>";
			?>
			</select>
		</div>
		<div>
			Parametre: <input name="parametre" value="-1" type="number"/>		
		</div>
		<div>
			Quantité: <input name="quantite" value="1" type="number"/>	
		</div>	
		<div>
			<input class="submit" type="submit" value="Donner"/>
		</div>	
	</form>
	<div>
		<input class="submit" type="submit" onclick="fermerPopupDonItem();" value="Fermer"/>
	</div>
</div>

<div id="blocAdminPartie">
	<div id="title">Partie N° <?php echo $IDPartie;?></div>
	<div id="jourEtCycle">Jour n° <?php echo $partie["Jour"];?> - Cycle n° <?php echo $partie["Cycle"];?></div>

	<div id="menuTopBar">
		<form action="deconnexionAdminPartie.php">
			<input id="deconnexion" class="btnTopBar" type="submit" value="Retour"/>
		</form>
		<form onsubmit="return confirm('Etes-vous sûrs de vouloir passer au cycle suivant?');" action="cycleSuivant.php">
			<input class="btnTopBar" id="btnPasserCycle" type="submit" value="Passer Cycle"/>
		</form>
		<form action="#"><input id="btnTopBar_perso" class="btnTopBar <?php echo ($IDOnglet=='p') ? 'btnTopBarLocked' : '';?>" value="Personnages" onclick="switchOngletGestionPartie('perso');"/></form>
		<form action="#"><input id="btnTopBar_variables" class="btnTopBar <?php echo ($IDOnglet=='v') ? 'btnTopBarLocked' : '';?>" value="Variables" onclick="switchOngletGestionPartie('variables');"/></form>
		<form action="#"><input id="btnTopBar_carte" class="btnTopBar <?php echo ($IDOnglet=='c') ? 'btnTopBarLocked' : '';?>" value="Carte" onclick="switchOngletGestionPartie('carte');"/></form>
		<form action="#"><input id="btnTopBar_tchats" class="btnTopBar <?php echo ($IDOnglet=='t') ? 'btnTopBarLocked' : '';?>" value="Tchats" onclick="switchOngletGestionPartie('tchats');"/></form>
	</div>

	<div class="blocOnglet" id="blocOnglet_perso" style="display:<?php echo ($IDOnglet=='p')?'block':'none';?>">
		<div id="blocPersonnages">
			<?php
				foreach($personnages as $personnage)
				{
					//die(print_r($personnage));
					$hero = $heros[$personnage["IDHeros"]];
					$joueur = $joueurs[$personnage["Joueur"]];
					?>
						<div class="blocPersonnage" id="blocPersonnage_<?php echo $personnage["IDHeros"];?>">
							<div class="zoneNom"><?php echo "<b>".$hero["Prenom"]."</b> (".$joueur["Login"].")";?> <img class="iconeRdy" width=15px height=15px src='../images/button_Nready.png'/></div>

							<div class="bloc_1">
								<div class='image_personnage' style='background-image:url(../images/Personnage_portrait/Personnage_Portrait_<?php echo $hero["ID"]; ?>.png);'></div>
								<div class="bloc_carac">
									<div class="carac_perso"><span style="color:purple;">PA</span>:<span class="PAActuel">?</span>/<span class="PAMax">?</span></div>
									<div class="carac_perso"><span style="color:cyan;">PM</span>:<span class="PMActuel">?</span>/<span class="PMMax">?</span></div>
									<div class="carac_perso"><span style="color:red;">PV</span>:<span class="PVActuel">?</span>/<span class="PVMax">?</span></div>
									<div class="carac_perso"><span style="color:green;">MP</span>:<span class="MPActuel">?</span>/<span class="MPMax">?</span></div>

									<div class="region_perso">Lieu:<span class="nom_region_perso">???</span></div>
								</div>
							</div>
							<div class="bloc_2">
								<div>
									<form action="actionAdmin.php?action=0" method="post">
										<input name="IDHeros" type="hidden" value="<?php echo $hero["ID"]; ?>"/>
										<input class="boutonActionPerso" type="submit" value="Full AP/PM"/>
									</form>
								</div>
								<div>
									<input class="boutonActionPerso" type="button" value="Donner objet" onclick="afficherPopupDonObjet(<?php echo $hero["ID"]; ?>);"/>
								</div>
							</div>
						</div>
					<?php
				}

			?>
		</div>
	</div>
	<div class="blocOnglet" id="blocOnglet_variables" style="display:<?php echo ($IDOnglet=='v')?'block':'none';?>">
		<h1>Variables</h1>
		<div id='listeVariablesPartie'>
			<table id="tableVariables">
				<tr class='titres'>
					<td>ID Variable</td>
					<td>Description</td>
					<td class='etatVariable'>Etat</td>
					<td>Modifier</td>
				</tr>
				<?php
					$listeVariables = array();
					$listeVariables[1] = "Nombre pelles trouvées, X = Nombre pelles, 3 max";
					$listeVariables[2] = "Vieille marmitte trouvé. 1 si trouvée";
					$listeVariables[3] = "Toile rudimentaire trouvée. 1 si trouvée";
					$listeVariables[4] = "Toile posée sur le camp. 1 si toile posée";
					$listeVariables[5] = "Marmitte posée sur le camp. 1: Camp monté";

					foreach($listeVariables as $IDVariable=>$description)
					{
						echo "<tr id='rowVariable_".$IDVariable."'>
							<form action='actionAdmin.php?action=2' method='post'>
								<input type='hidden' name='IDVariable' value='".$IDVariable."'/>
								<td>".$IDVariable."</td>
								<td class='descriptionVariable red'>".$description."</td>
								<td class='etatVariable grey'><input type='number' name='value' value='-1'/></td>
								<td><input class='submit' type='submit' value='modifier'/></td>
							</form>
						</tr>";
					}		
				?>
			</table>
		</div>
	</div>
	<div class="blocOnglet" id="blocOnglet_carte" style="display:<?php echo ($IDOnglet=='c')?'block':'none';?>">
		<h1>Carte de l'île</h1>
		<div id="carteIle">
			<?php
				foreach($_SESSION["Admin"]["Regions"] as $IDRegion=>$region)
					echo '<div class="zoneRegion" id="region_'.$IDRegion.'" title="'.$region["Nom"].'"></div>';
			?>
		</div>
	</div>
	<div class="blocOnglet" id="blocOnglet_tchats" style="display:<?php echo ($IDOnglet=='t')?'block':'none';?>">
		<h1>Tchats</h1>
		<div id="bloc_tchats">
			<div class="bloc_tchat" id='canal_Partie'><h2>Partie</h2>
				<div class='tchat_connectes'>
					<div class='tchat_connectes_title'>---</div>
					<div class="tchat_connectes_content"></div>
				</div>
				<div class="bloc_tchat_content"></div>
				<div class="bloc_tchat_envoieMessage">
					<div class='bloc_envoi_message' id='bloc_envoi_message_Partie'>
						<input type='hidden' name='idCanal' value='Partie'></input>
						<textarea placeholder='Ecrire un message Admin' type='text' name='message' maxlength='255' id='zone_envoi_message_Partie'></textarea>
						<div class='submit' onclick='envoyerMessageAdmin("Partie");'>Envoyer</div>
					</div>
				</div>
			</div>
			<div class="bloc_tchat" id='canal_Radio'><h2>Radio</h2>
				<div class='tchat_connectes'>
					<div class='tchat_connectes_title'>Joueurs présents:</div>
					<div class="tchat_connectes_content">Loading</div>
				</div>
				<div class="bloc_tchat_content"></div>
				<div class="bloc_tchat_envoieMessage">
					<div class='bloc_envoi_message' id='bloc_envoi_message_Radio'>
						<input type='hidden' name='idCanal' value='Radio'></input>
						<textarea placeholder='Ecrire un message Admin' type='text' name='message' maxlength='255' id='zone_envoi_message_Radio'></textarea>
						<div class='submit' onclick='envoyerMessageAdmin("Radio");'>Envoyer</div>
					</div>
				</div>
			</div>
			<?php
				for($i=1;$i<=10;$i++)
				{
					?>
						<div class="bloc_tchat" id='canal_Region_<?php echo $i;?>'><h2>Region <?php echo $i;?></h2>
							<div class='tchat_connectes'>
								<div class='tchat_connectes_title'>Joueurs présents:</div>
								<div class="tchat_connectes_content">Loading</div>
							</div>
							<div class="bloc_tchat_content"></div>
							<div class="bloc_tchat_envoieMessage">
								<div class='bloc_envoi_message' id='bloc_envoi_message_Region_<?php echo $i;?>'>
									<input type='hidden' name='idCanal' value='Region_<?php echo $i;?>'></input>
									<textarea placeholder='Ecrire un message Admin' type='text' name='message' maxlength='255' id='zone_envoi_message_Region_<?php echo $i;?>'></textarea>
									<div class='submit' onclick='envoyerMessageAdmin("Region_<?php echo $i;?>");'>Envoyer</div>
								</div>
							</div>
						</div>
					<?
				}
			?>	
		</div>
	</div>
</div>

<script>
	var ongletActif = "<?php
		switch($IDOnglet)
		{
			case "p":
			default:
				echo "perso";	
				break;
			case "v":
				echo "variables";
				break;
			case "c":
				echo "carte";	
				break;
			case "t":
				echo "tchats";	
				break;
		}?>";
</script>
<script type="text/javascript" src="admin.js"></script>
