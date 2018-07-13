<?php
if (!isset($_SESSION)) session_start();
	session_destroy();
	$_SESSION['name'] ="";
	$_SESSION['username'] ="";
	header('Location:index.php');	
?>
