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

<div>
	<form action="deconnexionAdminPartie.php">
		<input id="deconnexion" type="submit" value="retourner à la selection des parties"/>
	</form>
</div>

<div id="blocAdminPartie">
	<div id="title">Partie N° <?php echo $IDPartie;?></div>
	<div id="jourEtCycle">Jour n° <?php echo $partie["Jour"];?> - Cycle n° <?php echo $partie["Cycle"];?></div>

	<div id="blocPersonnages">
		<?php
			foreach($personnages as $personnage)
			{
				$hero = $heros[$personnage["IDHeros"]];
				$joueur = $joueurs[$personnage["Joueur"]];
				?>
					<div class="blocPersonnage" id="blocPersonnage_<?php echo $personnage["IDHeros"];?>">
						<div class="zoneNom"><?php echo "<b>".$hero["Prenom"]."</b> (".$joueur["Login"].")";?> <img class="iconeRdy" width=15px height=15px src='../images/button_Nready.png'/></div>
						<div class='image_personnage' style='background-image:url(../images/Personnage_portrait/Personnage_Portrait_<?php echo $hero["ID"]; ?>.png);'></div>
					</div>
				<?php
			}

		?>
	</div>
</div>

<script type="text/javascript" src="admin.js"></script>