<?php
	include("lang/FR.php"); // Par défaut

	function lang($libeleFragment)
	{
		global $lang;
		return (!empty($lang[$libeleFragment])) ? $lang[$libeleFragment] : "STR_NOT_FOUNT: ".$libeleFragment;
	}
?>