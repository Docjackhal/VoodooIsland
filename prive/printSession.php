<?php
    session_start();
   	include("config.php");
   	$loginsAutorises = array("docjackhal");

	//phpinfo();

	if(in_array($_SESSION['Login'], $loginsAutorises) ||true)
	{
		echo "<pre>";
		print_r($_SESSION);
		echo "</pre>";
	}
?>