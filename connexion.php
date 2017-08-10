<?php
session_start();
if((!empty($_POST['login']) && !empty($_POST['mdp'])) || (isset($_GET['loginRegister']) && isset($_GET['mdpRegister'])))
{
	include_once("prive/config.php");
	// Connexion a la base
	$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
	mysqli_set_charset($mysqli, "utf8");

	$login = isset($_POST['login']) ? $_POST['login'] : $_GET['loginRegister'];
	$mdp = isset($_POST['mdp']) ? $_POST['mdp'] : $_GET['mdpRegister'] ;
	setcookie("ConnexionVoodooIsland", $login);

	$requete = "SELECT * FROM accounts WHERE Login = '".$login."' AND Password = '".$mdp."' LIMIT 1";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

	if(mysqli_num_rows($retour))
	{
			$account = mysqli_fetch_assoc($retour);
			$_SESSION = $account;

			// Récupération des informations sur les personnages
			$_SESSION['Heros'] = array();
			$requete = "SELECT * FROM heros";
			$retour = mysqli_query($mysqli ,$requete);
			if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

			while($personnage = mysqli_fetch_assoc($retour))
				$_SESSION['Heros'][$personnage['ID']] = $personnage;

			if($_SESSION['IDPartieEnCours'] != -1)
			{
				$requete = "SELECT * FROM parties WHERE ID = '".$_SESSION['IDPartieEnCours']."' ";
				$retour = mysqli_query($mysqli,$requete);
				if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));
				$partie = mysqli_fetch_assoc($retour);
				$_SESSION['PartieEnCours'] = $partie;

				for($i = 1; $i < count($_SESSION['Heros']); $i++)
				{
					if($_SESSION['PartieEnCours']['Joueur'.$i] == $_SESSION['Login'])
						$_SESSION['IDPersoActuel'] = $i;
				}
			}

			// Récuperation des regions
			$requete = "SELECT * FROM regions";
			$retour = mysqli_query($mysqli,$requete);
			if (!$retour) die('Requête invalide : ' . mysqli_error($mysqli));

			while($region = mysqli_fetch_assoc($retour))
				$_SESSION['Regions'][$region['ID']] = $region;


			// Connexion terminée, redirection
			header('Location: index.php');
	}
	else
		header('Location: accueil.php?c=1');
}
else
	header('Location: accueil.php?c=1');

?>
