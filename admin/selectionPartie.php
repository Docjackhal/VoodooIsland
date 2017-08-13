<?php
	$requete = "SELECT * FROM ".$PT."parties WHERE Etat != 'terminee'";
	$retour = mysqli_query($mysqli,$requete);
		if (!$retour) trigger_error('Impossible de selectionner les parties : ' . mysqli_error($mysqli));
	$parties = array();
	while($partie = mysqli_fetch_assoc($retour))
		$parties[] = $partie;

	$requete = "SELECT * FROM ".$PT."heros";
	$retour = mysqli_query($mysqli,$requete);
		if (!$retour) trigger_error('Impossible de selectionner les héros : ' . mysqli_error($mysqli));
	$listeHeros = array();
	while($heros = mysqli_fetch_assoc($retour))
		$listeHeros[$heros["ID"]] = $heros;

	$_SESSION["Heros"] = $listeHeros;

	$requete = "SELECT * FROM ".$PT."accounts";
	$retour = mysqli_query($mysqli,$requete);
		if (!$retour) trigger_error('Impossible de selectionner les comptes joueurs : ' . mysqli_error($mysqli));
	$listeJoueurs = array();
	while($joueur = mysqli_fetch_assoc($retour))
		$listeJoueurs[$joueur["ID"]] = $joueur;
?>

<div id="blocSelectionPartie">
	<div id="title">Selection de la partie</div>

	<?php
		foreach($parties as $partie)
		{
			$etatPartie = ($partie["Etat"] == "en_creation") ? "En création" : "En cours";
			$classEtatPartie = ($partie["Etat"] == "en_creation") ? "etatPartieEnCreation" : "etatPartieEnCours";

			$nbJoueursDansPartie = 0;
			foreach($listeHeros as $IDHeros => $heros)
			{
				$IDJoueurSurHeros = $partie["Joueur".$IDHeros];
				if($IDJoueurSurHeros >= 0)
					$nbJoueursDansPartie++;
			}


			?>
			<div class='partie'>
				<div class="nomPartie"/>Partie N° <?php echo $partie["ID"];?></div>
				<div class="etatPartie <?php echo $classEtatPartie;?>"/>Etat: <?php echo $etatPartie;?></div>
				<div class="dateDemarragePartie"/>Démarrage: <?php echo $partie["DateDemarrage"];?></div>
				<div class="jourEtCyclePartie"/>Jour: <?php echo $partie["Jour"];?> - Cycle: <?php echo $partie["Cycle"];?></div>
				<div class="titreJoueur"/>Joueurs: </div>

				<?php
					foreach($listeHeros as $IDHeros=>$heros)
					{
							$IDJoueur = $partie["Joueur".$IDHeros];
							if($IDJoueur > 0)
								$login = "<b style='color:green;'>".$listeJoueurs[$IDJoueur]["Login"]."</b>";
							else
								$login = "<b style='color:red;'>Vide</b>";

						?>
						<div class="slotHeros">
							<span class="nomHeros">- <?php echo $heros["Prenom"]." ".$heros["Nom"];?></span>
							<span class="jouePart"> incarné par <?php echo $login;?></span>
						</div>
						<?php
					}


				if($partie["Etat"] == "en_cours")
				{
					?>
					<form method="POST" action="demarrerAdministrationPartie.php">
						<input type="hidden" name="IDPartie" value="<?php echo $partie["ID"];?>"/>
						<input class="boutonAdministrer" type="submit" value="Administrer"></input>
					</form>

					<form method="POST" action="reinitialiserPartie.php">
						<input type="hidden" name="IDPartie" value="<?php echo $partie["ID"];?>"/>
						<input class="boutonAdministrer" type="submit" value="Reinitialiser"></input>
					</form>
					<?php
				}
				else if($nbJoueursDansPartie == count($listeHeros))
				{
					?>
					<form method="POST" action="demarragePartie.php">
						<input type="hidden" name="IDPartie" value="<?php echo $partie["ID"];?>"/>
						<input class="boutonAdministrer" type="submit" value="Démarrer"></input>
					</form>
					<?php
				}
				else
				{
					?>
						<div class="partieNonPrete">La partie n'est pas prête</div>
					<?php
				}

			?>
			</div>
			<?php
		}
	?>

</div>