<?php
session_start();
if(empty($_SESSION))
	header('Location: accueil.php');
else
{
	include_once("prive/config.php");
	include_once("updateInformationsSession.php");
	$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base);

	updateInformationsSessionLobby($mysqli);

	// Verification de la disponibilité du joueur
	if($_SESSION['IDPartieEnCours'] != -1 && $_SESSION['PartieEnCours']['Etat'] == "en_cours")
		header('Location: game.php');

	//Récupération des parties
	$requete = "SELECT * FROM ".$PT."parties WHERE Etat = 'en_creation' LIMIT 1";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

	if(mysqli_num_rows($retour))
	{
		$partie = mysqli_fetch_assoc($retour);
		$slotsLibres = array();
		$nbSlotsLibres = 0;
		$IDPartie = $partie["ID"];

		for($i = 1;$i <= count($_SESSION['Heros']);$i++)
		{
			$slotsLibres[] = $i;
			if($partie["Joueur".$i] < 0)
				$nbSlotsLibres++;
		}
	}
	else
		echo "Erreur: Pas de partie en cours";

	?>

<html>
<head>
<title>Voodoo Island - Lobby</title>
<link href="style/lobby.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="js/lobby.js"></script>
</head>

<body>

	<h1>Partie N° <?php echo $IDPartie;?></h1>
	<a href="deconnexion.php"/>Deconnexion</a>

	<?php

		if($_SESSION['IDPartieEnCours'] == -1)
			echo "<h1>Choisissez votre personnage</h1>";
		else if($nbSlotsLibres > 0)
			echo "<h1>En attente des autres joueurs</h1>";
		else
			echo "<h1>En attente du démarrage de la partie par l'administrateur</h1>";

		if(isset($_GET['e']))
		{
			switch($_GET['e'])
			{
				case 0: // Personnage non disponible
					echo "<div class='message_erreur'>Ce personnage n'est plus disponible.</div>";
					break;
			}
		}

		$i = 0;
		foreach($slotsLibres as $num => $idPerso)
		{
			$personnage = $_SESSION['Heros'][$idPerso];
			$class = '';
			$joueurSurPerso = $partie['Joueur'.$idPerso];

			if($_SESSION['IDPartieEnCours'] == -1)
				$enabled = true;
			else
				$enabled = false;

			if($joueurSurPerso >= 0)
			{
				if($joueurSurPerso == $_SESSION['ID'])
					$class = 'bloc_personnage_self';
				else
					$class = 'bloc_personnage_disabled';

				$enabled = false;
			}

			$div = "<div class='bloc_personnage ".$class."' id='bloc_personnage_".$num."' onclick='afficherBioPersonnage(".$idPerso.")'>";
				$div .= "<div class='image_personnage' id='image_personnage_".$idPerso."'></div>";
				$div .= "<div class='nom_personnage'>".$personnage['Prenom']." ".$personnage['Nom']."</div>";
				$div .= "<div class='metier_personnage'>".$personnage['Profession']."</div>";
			$div .= "</div>";

			// popup biographie
			$div .= "<div class='bloc_infos_personnage' id='bloc_infos_personnage_".$idPerso."'>";
				$div .= "<div class='nom_personnage'>".$personnage['Titre']." ".$personnage['Prenom']." ".$personnage['Nom']."</div>";
				$div .= "<div class='image_personnage' id='image_personnage_".$idPerso."'></div>";
				$div .= "<div class='infos_personnage' id='infos_personnage".$idPerso."'>";
					$div .= "<b>Profession:</b> ".$personnage['Profession']."</br>";
					$div .= "<b>Age:</b> ".$personnage['Age']."ans</br>";
					$div .= "<b>Origine:</b> ".$personnage['Origine']."</br>";
				$div .= "</div>";
				$div .= "<div class='biographie_personnage'>";
					$div .= "<div class='biographie_titre'>Biographie:</div>";
					$div .= "<div class='biographie'>".str_replace("%","</br>",$personnage['Biographie'])."</div>";
				$div .= "</div>";

				if($enabled)
				{
					$div .= "<form METHOD=post ACTION='action.php?action=0'><input type='hidden' name='idPerso' value='".$idPerso."'/><input type='hidden' name='idPartie' value='".$partie['ID']."'/></form>";
					$div .= "<div class='form_selection_personnage' onclick='submitForm(".$i.")'><div>Commencer</div></div>";
					$i++;
				}
				else if ($joueurSurPerso == $_SESSION['ID']) 
				{
					$div .= "<form METHOD=post ACTION='action.php?action=1'></form>";
					$div .= "<div class='form_selection_personnage' onclick='submitForm(".$i.")'><div>Annuler</div></div>";
					$i++;
				}
				else if($joueurSurPerso > 0)
					$div .= "<div class='form_selection_personnage form_selection_personnage_disabled'><div>Deja pris</div></div>";
				else
					$div .= "<div class='form_selection_personnage form_selection_personnage_disabled'><div>Commencer</div></div>";

				$div .= "<div class='fermer_biographie' onclick='closeAllBio()'><div>Fermer</div></div>";
			$div .= "</div>";
			echo $div;
			
		}
	?>
</body>
</html>

<?php
}
?>