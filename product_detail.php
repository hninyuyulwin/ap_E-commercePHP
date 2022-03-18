<?php 
  include('header.php');

   if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
        header('location:login.php');
    }
?>
<!--================Single Product Area =================-->
<div class="product_image_area">
  <div class="container">
    <a href="index.php" class="btn btn-warning my-3 float-right text-white">Back</a>
    <div class="row s_product_inner">
      <div class="col-lg-6">
        <?php 
          $prod = $pdo->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
          $prod->execute();
          $res = $prod->fetch(PDO::FETCH_ASSOC);

          $catid = $res['category_id'];
          $stmt = $pdo->prepare("SELECT * FROM categories WHERE id=$catid");
          $stmt->execute();
          $catRes = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="s_Product_carousel">
          <div class="single-prd-item">
            <img class="img-fluid" src="images/<?php echo escape($res['image']); ?>" alt="">
          </div>
          <div class="single-prd-item">
            <img class="img-fluid" src="images/<?php echo escape($res['image']); ?>" alt="">
          </div>
          <div class="single-prd-item">
            <img class="img-fluid" src="images/<?php echo escape($res['image']); ?>" alt="">
          </div>
        </div>
      </div>
      <div class="col-lg-5 offset-lg-1">
        <div class="s_product_text">
          <h3><?php echo $res['name']; ?></h3>
          <h2><?php echo $res['price']." MMK"; ?></h2>
          <ul class="list">
            <li><a class="active" href="#"><span>Category</span> : <?php echo $catRes['name']; ?></a></li>
            <li><a href="#"><span>Availibility</span> : <?php echo $res['quantity']; ?></a></li>
          </ul>
          <p><?php echo $res['description']; ?></p>
          <form action="addtocart.php" method="post">
            <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
            <input type="hidden" name="id" value="<?php echo $res['id']; ?>">
            <div class="product_count">
              <label for="qty">Quantity:</label>
              <input type="text" name="qty" id="sst" maxlength="12" value="1" title="Quantity:" class="input-text qty">
              <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
               class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
              <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
               class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
            </div>
            <div class="card_area d-flex align-items-center">
              <button type="submit" class="primary-btn" style="border: none;">Add to Cart</button>
            </div>
          </form>
            
        </div>
      </div>
    </div>
  </div>
</div><br>
<!--================End Single Product Area =================-->

<!--================End Product Description Area =================-->
<?php include('footer.php');?>
