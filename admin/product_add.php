<?php 
  session_start();
  require_once "../config/config.php";
  require_once "../config/common.php";

  if (empty($_SESSION['user_id'] && $_SESSION['logged_in'])) {
    header('location:login.php');
  }
  if ($_SESSION['role'] != 1) {
    header('location:../login.php');
  }
  
  if ($_POST) {
    if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['price']) || empty($_POST['quantity']) || empty($_POST['category']) || empty($_FILES['image'])) {
      if (empty($_POST['name'])) {
        $nameError = "Name Field Required!";
      }
      if (empty($_POST['description'])) {
        $descriptionError = "Description Field Required!";
      }
      if (empty($_POST['price'])) {
        $priceError = "Price Field Required!";
      }
      else if(is_numeric($_POST['price']) != 1){
        $priceError = "Price value must be number";
      }
      if (empty($_POST['quantity'])) {
        $quantityError = "Quantity Field Required!";
      }
      else if(is_numeric($_POST['quantity']) != 1){
        $quantityError = "Quantity value must be number";
      }
      if (empty($_POST['category'])) {
        $categoryError = "Category Field Required!";
      }
      if (empty($_FILES['image'])) {
        $imageError = "Image Field Required!";
      }
    }else{
      if (is_numeric($_POST['price']) != 1) {
        $priceError = "Price value must be number!";
      }
      if (is_numeric($_POST['quantity']) != 1) {
        $quantityError = "Quantity value must be number!";
      }
        if (empty($quantityError) && empty($priceError)) {
          $img = $_FILES['image']['name'];
          $file = "../images/".$img;
          $fileName = $_FILES['image']['tmp_name'];
          $imgType = pathinfo($file,PATHINFO_EXTENSION);
          if ($imgType != 'jpg' && $imgType != 'png' && $imgType != 'jpeg') {
            echo "<script>alert('Image type must be jpg or png or jpeg.');</script>";
          }else{
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $category_id = $_POST['category'];
            $image = $img;
            move_uploaded_file($fileName, $file);

            $sql = "INSERT INTO products(name,description,price,quantity,category_id, image) VALUES (:name,:description,:price,:quantity,:category_id,:image)";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(array(':name'=>$name,':description'=>$description,':price'=>$price,':quantity'=>$quantity,':category_id'=>$category_id,':image'=>$image));
            if ($result) {
              echo "<script>alert('Post Creatded Success!');window.location.href='index.php';</script>";
            }
          }
        }
    }
  }
?>
<?php 
  include_once "header.php";
?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h1 class="card-title">Create Category Listing</h1>
                <div class="float-right">
                  <a href="index.php" type="button" class="btn btn-warning">Back</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="product_add.php" method="post" enctype="multipart/form-data">
                  <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                  <div class="form-group">
                    <label for="">Name</label>
                    <p class="text-danger"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                    <input type="text" name="name" class="form-control" placeholder="Enter Name">
                  </div>
                  <div class="form-group">
                    <label for="">Description</label>
                    <p class="text-danger"><?php echo empty($descriptionError) ? '' : '*'.$descriptionError; ?></p>
                    <textarea class="form-control" name="description" rows="6" cols="40"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="">Price</label>
                    <p class="text-danger"><?php echo empty($priceError) ? '' : '*'.$priceError; ?></p>
                    <input type="number" name="price" class="form-control" placeholder="Enter Price">
                  </div>
                  <div class="form-group">
                    <label for="">Quantity</label>
                    <p class="text-danger"><?php echo empty($quantityError) ? '' : '*'.$quantityError; ?></p>
                    <input type="number" name="quantity" class="form-control" placeholder="Enter Qty">
                  </div>
                  <div class="form-group">
                    <label for="">Category</label>
                    <p class="text-danger"><?php echo empty($categoryError) ? '' : '*'.$categoryError; ?></p>
                    <select class="form-control" name="category">
                      <option value="">Select Category</option>
                      <?php 
                        $stmt = $pdo->prepare("SELECT * FROM categories");
                        $stmt->execute();
                        $cat = $stmt->fetchAll();
                        if ($cat) {
                          foreach ($cat as $value) {
                      ?>
                        <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                      <?php
                          }
                        }
                      ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="">Image</label>
                    <p class="text-danger"><?php echo empty($imageError) ? '' : '*'.$imageError; ?></p>
                    <input type="file" name="image" class="">
                  </div>
                  <input type="submit" name="button" class="btn btn-success" value="Create">
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
<?php 
  include_once "footer.php";
?>
