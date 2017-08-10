<?php
	session_start();
	$_SESSION["Admin"] = array();
	header("Location:index.php");
?>