<?php 
	require_once "../config/config.php";

	$sql = "DELETE FROM categories WHERE id=".$_GET['id'];
	$pdoStatement = $pdo->prepare($sql);
	$result = $pdoStatement->execute();
	if ($result) {
		echo "<script>alert('Post Deleted Success');window.location.href='category.php';</script>";
	}
?>