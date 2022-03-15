<?php 
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	require_once 'config/config.php';
	require_once 'config/common.php';

	if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])) {
		header('location:login.php');
	}

	if (isset($_GET['pageno'])) {
		$pageno = $_GET['pageno'];
	}else{
		$pageno = 1;
	}
	$numOfrecs = 6;
	$offset = ($pageno -1)*$numOfrecs;

	if (empty($_POST['search']) && empty($_COOKIE['search'])) {
		$sql = "SELECT * FROM products ORDER BY id DESC";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rawResult = $stmt->fetchAll();

		$total_pages = ceil(count($rawResult)/$numOfrecs);	

		$sql = "SELECT * FROM products ORDER BY id DESC limit $offset,$numOfrecs";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
	}else{
		$searchKey = $_POST['search'] ? $_POST['search'] : $_COOKIE['search'] ;
		$sql = "SELECT * FROM products where name like '%$searchKey%' ORDER BY id DESC";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rawResult = $stmt->fetchAll();

		$total_pages = ceil(count($rawResult)/$numOfrecs);	

		$sql = "SELECT * FROM products where name like '%$searchKey%' ORDER BY id DESC limit $offset,$numOfrecs";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
	}
?>
<?php include('header.php') ?>
<div class="container">
	<div class="row">
		<div class="col-xl-3 col-lg-4 col-md-5">
			<div class="sidebar-categories">
				<div class="head">Browse Categories</div>
				<ul class="main-categories">
					<li class="main-nav-list">
						<?php 
							$stmt = $pdo->prepare("SELECT * FROM categories");
							$stmt->execute();
							$cat = $stmt->fetchAll();
							if ($cat) {
								foreach ($cat as $value) {
						?>
						<a href="" aria-expanded="false">
							<span class="lnr lnr-arrow-right"></span>
							<?php echo $value['name']; ?>
						</a>
						<?php
								}
							}
						?>
					</li>
				</ul>
			</div>
		</div>
	<div class="col-xl-9 col-lg-8 col-md-7">
	<!-- Start Filter Bar -->
	<div class="filter-bar d-flex flex-wrap align-items-center">
		<div class="pagination">
			<a href="?pageno=1" class="">First</a>
			<a <?php if($pageno <= 1){echo 'disabled';}?> href="<?php if($pageno <= 1){echo '#';}else{ echo '?pageno='.($pageno-1);}?>" >
				<i class="fa fa-long-arrow-left" aria-hidden="true"></i>
			</a>
			<a href="<?php echo $pageno; ?>" class="active"><?php echo $pageno; ?></a>
			<a <?php if($pageno >= $total_pages){echo 'disabled';} ?> href="<?php if($pageno >= $total_pages){echo '#';}else{echo '?pageno='.($pageno+1);} ?>" >
				<i class="fa fa-long-arrow-right" aria-hidden="true"></i>
			</a>
			<a href="?pageno=<?php echo $total_pages; ?>" class="">Last</a>
		</div>
	</div>
	<!-- End Filter Bar -->
	<!-- Start Best Seller -->
	<section class="lattest-product-area pb-40 category-list">
		<div class="row">
			<!-- single product -->
			<?php 
				if ($result) {
					foreach ($result as $value) {
			?>
			<div class="col-lg-4 col-md-6">
				<div class="single-product">
					<img class="" src="images/<?php echo escape($value['image']); ?>" height="200">
					<div class="product-details">
						<h6><?php echo escape($value['name']); ?></h6>
						<div class="price">
							<h6><?php echo escape($value['price'])." MMK"; ?></h6>
						</div>
						<div class="prd-bottom">

							<a href="" class="social-info">
								<span class="ti-bag"></span>
								<p class="hover-text">add to bag</p>
							</a>
							<a href="" class="social-info">
								<span class="lnr lnr-move"></span>
								<p class="hover-text">view more</p>
							</a>
						</div>
					</div>
				</div>		
			</div>
			<?php
					}
				}
			?>		
			<!-- single product -->
		</div>
	</section>
	<!-- End Best Seller -->
<?php include('footer.php');?>
