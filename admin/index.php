<?php
	session_start();

	include("../prive/config.php");

	// Connexion a la base
	$mysqli = mysqli_connect($mysql_ip, $mysql_user,$mysql_password,$base); 
	mysqli_set_charset($mysqli, "utf8");
?>

<html>
<head>
<title>Voodoo Island - Admin</title>
<link href='http://fonts.googleapis.com/css?family=Chelsea+Market' rel='stylesheet' type='text/css'>
<link href="../style/accueil.css" rel="stylesheet" type="text/css"/>
<link href="../style/admin.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../js/jquery.js"></script>
</head>

<body>
	<div id='logo'>Voodoo Admin</div>

	<?php
		if(empty($_SESSION["Admin"]))
			include("selectionPartie.php");
		else
			include("adminPartie.php");
	?>
	
</body>
</html>
