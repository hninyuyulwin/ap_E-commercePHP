<?php 
	session_start();
	require_once "config/config.php";
	if ($_POST) {
		$id = $_POST['id'];
		$qty = $_POST['qty'];

		$stmt = $pdo->prepare("SELECT * from products WHERE id=".$id);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($qty > $res['quantity']) {
			echo "<script>alert('No Stock Available.');window.location.href='product_detail.php?id=$id';</script>";
		}else{
			if (isset($_SESSION['cart']['id'.$id])) {
				$_SESSION['cart']['id'.$id] += $qty;
			}else{
				$_SESSION['cart']['id'.$id] = $qty;
			}
			header('location:cart.php');
		}			
	}
?>