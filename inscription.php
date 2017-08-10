<?php
if(!empty($_POST['login']) && !empty($_POST['mdp'])  && !empty($_POST['mdpv'])  && !empty($_POST['email']))
{
	include_once("prive/config.php");

	$login = $_POST['login'];
	$mdp = $_POST['mdp'];
	$mdpv = $_POST['mdpv'];
	$email = $_POST['email'];
	
	// Connexion a la base
	$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
	mysqli_set_charset($mysqli, "utf8");
	
	// Verification d'un login similaire existant
	$requete = "SELECT 1 FROM accounts WHERE Login = '".$login."' LIMIT 1";
	$retour = mysqli_query($mysqli,$requete);
	if (!$retour) die('Requ�te invalide : ' . mysqli_error());
		
	if(!mysqli_num_rows($retour))
	{
		// Verification de la concordance des mdp
		if($mdp == $mdpv)
		{
			// Création du compte
			$requete = "INSERT INTO accounts (Login,Password,Email,DateInscription) VALUES ('".$login."','".$mdp."','".$email."', NOW())";
			$retour = mysqli_query($mysqli,$requete);
			if (!$retour) die('Requête invalide : ' . mysqli_error());

			// Inscription terminée !
			header('Location: connexion.php?loginRegister='.$login."&mdpRegister=".$mdp); // Redirection
		}
		else
			header('Location: accueil.php?e=3&mdp='.$mdp."&email=".$email&"login=".$login); // Mot de passe non concordants
	}
	else
		header('Location: accueil.php?e=2&mdp='.$mdp."&email=".$email); // Login deja existant
} 
else
	header('Location: accueil.php?e=1');

?>