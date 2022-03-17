<?php 
	session_start();
	require_once "config/config.php";
	session_destroy();
	header('location:login.php');
?>