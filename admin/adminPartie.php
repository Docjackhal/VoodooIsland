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

<div id="blocAdminPartie">
	<div id="title">Partie N° <?php echo $IDPartie;?></div>
	<div id="jourEtCycle">Jour n° <?php echo $partie["Jour"];?> - Cycle n° <?php echo $partie["Cycle"];?></div>

	<div id="menuTopBar">
		<form action="deconnexionAdminPartie.php">
			<input id="deconnexion" class="btnTopBar" type="submit" value="Retour"/>
		</form>
		<form onsubmit="return confirm('Etes-vous sûrs de vouloir passer au cycle suivant?');" action="cycleSuivant.php">
			<input class="btnTopBar" type="submit" value="Passer Cycle"/>
		</form>
	</div>

	<div id="blocPersonnages">
		<?php
			foreach($personnages as $personnage)
			{
				$hero = $heros[$personnage["IDHeros"]];
				$joueur = $joueurs[$personnage["Joueur"]];
				?>
					<div class="blocPersonnage" id="blocPersonnage_<?php echo $personnage["IDHeros"];?>">
						<div class="zoneNom"><?php echo "<b>".$hero["Prenom"]."</b> (".$joueur["Login"].")";?> <img class="iconeRdy" width=15px height=15px src='../images/button_Nready.png'/></div>

						<div class="bloc_1">
							<div class='image_personnage' style='background-image:url(../images/Personnage_portrait/Personnage_Portrait_<?php echo $hero["ID"]; ?>.png);'></div>
							<div class="bloc_carac">
								<div class="carac_perso"><span style="color:green;">PA</span>:<span class="PAActuel">?</span>/<span class="PAMax">?</span></div>
								<div class="carac_perso"><span style="color:cyan;">PM</span>:<span class="PMActuel">?</span>/<span class="PMMax">?</span></div>
								<div class="carac_perso"><span style="color:red;">PV</span>:<span class="PVActuel">?</span>/<span class="PVMax">?</span></div>
								<div class="carac_perso"><span style="color:purple;">MP</span>:<span class="MPActuel">?</span>/<span class="MPMax">?</span></div>

								<div class="region_perso">Lieu:<span class="nom_region_perso">???</span></div>
							</div>
						</div>
						<div class="bloc_2">
						</div>
					</div>
				<?php
			}

		?>
	</div>
</div>

<script type="text/javascript" src="admin.js"></script>