<?php 
	require_once "../config/config.php";

	$stmt = $pdo->prepare("DELETE FROM products WHERE id=".$_GET['id']);
	$result = $stmt->execute();
	header('location:index.php');
?>