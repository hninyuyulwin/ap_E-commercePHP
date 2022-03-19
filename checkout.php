<?php 
    session_start();

    require_once "config/config.php";
    require_once "config/common.php";

    if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
        header('location:login.php');
    }
?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/fav.png">
    <!-- Author Meta -->
    <meta name="author" content="CodePixar">
    <!-- Meta Description -->
    <meta name="description" content="">
    <!-- Meta Keyword -->
    <meta name="keywords" content="">
    <!-- meta character set -->
    <meta charset="UTF-8">
    <!-- Site Title -->
    <title>AP Shop</title>

    <!--
            CSS
            ============================================= -->
    <link rel="stylesheet" href="css/linearicons.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/nouislider.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>

    <!-- Start Header Area -->
	<header class="header_area sticky-header">
		<div class="main_menu">
			<nav class="navbar navbar-expand-lg navbar-light main_box">
                <div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<a class="navbar-brand logo_h" href="index.html"><h4>AP Shopping<h4></a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
					 aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse offset" id="navbarSupportedContent">
						<ul class="nav navbar-nav navbar-right">
							<li class="nav-item"><a href="#" class="cart"><span class="ti-bag"></span></a></li>
							<li class="nav-item">
								<button class="search"><span class="lnr lnr-magnifier" id="search"></span></button>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</div>
		<div class="search_input" id="search_input_box">
			<div class="container">
				<form class="d-flex justify-content-between">
					<input type="text" class="form-control" id="search_input" placeholder="Search Here">
					<button type="submit" class="btn"></button>
					<span class="lnr lnr-cross" id="close_search" title="Close Search"></span>
				</form>
			</div>
		</div>
	</header>
	<!-- End Header Area -->

    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Checkout</h1>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================Checkout Area =================-->
    <section class="checkout_area section_gap">
        <div class="container">
            <?php if (isset($_SESSION['cart'])): ?>
                
            <div class="billing_details">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="order_box">
                            <h2>Your Order</h2>
                            <ul class="list">
                                <li>
                                    <a href="#">Product <span>Total</span></a>
                                </li>                            
                            <?php 
                                $total = 0;
                                foreach ($_SESSION['cart'] as $key => $qty) {
                                    $id = str_replace('id','',$key);

                                    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$id);
                                    $stmt->execute();
                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $total += $result['price'] * $qty;
                            ?>
                                <li>
                                    <a href="#"><?php echo $result['name']; ?>
                                        <span class="middle">x <?php echo $qty; ?></span>
                                        <span class="last"><?php echo $result['price'] * $qty ." MMK"; ?></span>
                                    </a>
                                </li>
                            <?php
                                }
                            ?>
                            </ul>
                            <ul class="list list_2">
                                <li><a href="#">Total <span><?php echo $total." MMK"; ?></span></a></li>
                            </ul>
                            <div class="row col-12">
                                <div class="col-6">
                                    <a class="primary-btn" href="sale_order.php">Proceed</a>
                                </div>
                                <div class="col-6">
                                    <a class="primary-btn" href="cart.php">Back</a>
                                </div>
                            </div>                    
                        </div>
                    </div>
                </div>
            </div>
            <?php endif ?>
        </div>
    </section>
    <!--================End Checkout Area =================-->

    <!-- start footer Area -->
<?php require_once "footer.php"; ?>
