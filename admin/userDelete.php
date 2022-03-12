<?php 
	require_once '../config/config.php';

	$stmt = $pdo->prepare("DELETE FROM users WHERE id=".$_GET['id']);
	$result = $stmt->execute();
	if ($result) {
		header('location:userIndex.php');
	}
?>