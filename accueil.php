

<?php

// Erreur inscription
if(!empty($_GET['e']))
	$e = $_GET['e'];
else
	$e = 0;

// Erreur connexion
if(!empty($_GET['c']))
	$c = $_GET['c'];
else
	$c = 0;

// Recuperation des informations précédents les erreurs
if(!empty($_GET['login']))
	$login = $_GET['login'];
else
	$login = "";

if(!empty($_GET['mdp']))
	$mdp = $_GET['mdp'];
else
	$mdp = "";

if(!empty($_GET['email']))
	$email = $_GET['email'];
else
	$email = "";

$loginConnexion = "";
if(isset($_COOKIE["ConnexionVoodooIsland"]))
	$loginConnexion = $_COOKIE["ConnexionVoodooIsland"];
?>

<html>
<head>
<title>Voodoo Island - Accueil</title>
<link href='http://fonts.googleapis.com/css?family=Chelsea+Market' rel='stylesheet' type='text/css'>
<link href="style/accueil.css" rel="stylesheet" type="text/css"/>
<link rel="icon" type="image/png" href="images/fav.png" />

<script type="text/javascript" src="js/accueil.js"></script>
</head>

<body>
	<div id='logo'>Voodoo Island</div>
	
	<div id='bloc'>
		<?php
			if($c == 1)
					echo "<div class='message_erreur'>Erreur lors de la connection: Login ou mot de passe innexistants</div></br>";
			else if($e == 2)
		?>
		
		<!-- Formulaire de connexion -->
		<div id='topBar'>
				<div id='formulaire_inscription'>
					<form METHOD=post ACTION="connexion.php">
						Login: <input type='text' name='login' value='<?php echo $loginConnexion; ?>'></input>
						Mot de passe: <input type='password' name='mdp'></input>
						<input id='submit_connection' class='submit' type='submit' value='Connection'/>
					</form>
				</div>
		</div>

		<div id='bloc_inscription'>
					Pas encore inscrit?
					<button class='submit' onclick='switchPopupInscription()'>Inscription</button>
				</div>

		<div id='popup_inscription'>
				<h2>Inscription</h2>
				
				<?php
				if($e == 1)
					echo "<div class='message_erreur'>Erreur lors de l'inscription: un des formulaires est vide</div></br>";
				else if($e == 2)
					echo "<div class='message_erreur'>Erreur lors de l'inscription: Login deja existant, désolé !</div></br>";
				else if($e == 3)
					echo "<div class='message_erreur'>Erreur lors de l'inscription: Les deux mots de passe ne correspondent pas.</div></br>";
				else if($e == 4)
					echo "<div class='message_validation'>Erreur lors de l'inscription: Les deux mots de passe ne correspondent pas.</div></br>";

				if($e != 4)
				{
				?>
					<form METHOD=post ACTION="inscription.php">
						<span>Login</br></span><input type='text' name='login' value='<?php echo $login ?>'></input></br>
						<span>Mot de passe</br></span><input type='password' name='mdp' value='<?php echo $mdp ?>'></input></br>
						<span>Confirmer le mot de passe</br></span><input type='password' name='mdpv'></input></br>
						<span>Email</br></span><input type='text' name='email'value='<?php echo $email ?>'></input></br>
						<input class='submit' type='submit' value='Inscription'/>
					</form>
				<?php
				}
				?>
		</div>
		
		
	</div>
	
	

	
	
	
</body>
</html>

<?php
if($e != 0)
{
	?><script>switchPopupInscription();</script><?php
}
?>



