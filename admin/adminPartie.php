<?php
	$IDPartie = $_SESSION["Admin"]["IDPartieEnCours"];

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
	<div></div>
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
		<form action="#"><input id="btnTopBar_perso" class="btnTopBar btnTopBarLocked" value="Personnages" onclick="switchOngletGestionPartie('perso');"/></form>
		<form action="#"><input id="btnTopBar_variables" class="btnTopBar" value="Variables" onclick="switchOngletGestionPartie('variables');"/></form>
	</div>

	<div class="blocOnglet" id="blocOnglet_perso">
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
										<input class="boutonActionPerso" type="submit" value="Full AP"/>
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
	<div class="blocOnglet" id="blocOnglet_variables" style="display:none;">
		<h1>Variables</h1>
		<div id='listeVariablesPartie'>
			<table id="tableVariables">
				<tr class='titres'>
					<td>ID Variable</td>
					<td>Description</td>
					<td class='etatVariable'>Etat</td>
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
							<td>".$IDVariable."</td>
							<td class='descriptionVariable red'>".$description."</td>
							<td class='etatVariable grey'>-1</td>
						</tr>";
					}		
				?>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript" src="admin.js"></script>