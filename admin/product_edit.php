<?php 
  session_start();
  require_once "../config/config.php";
  require_once "../config/common.php";

  if (empty($_SESSION['user_id'] && $_SESSION['logged_in'])) {
    header('location:login.php');
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
      else if(is_int($_POST['price']) != 1){
        $qtyError = "Price value must be number";
      }
      if (empty($_POST['quantity'])) {
        $quantityError = "Quantity Field Required!";
      }
      else if(is_int($_POST['quantity']) != 1){
        $qtyError = "Quantity value must be number";
      }
      if (empty($_POST['category'])) {
        $categoryError = "Category Field Required!";
      }
      if (empty($_FILES['image'])) {
        $imageError = "Image Field Required!";
      }
    }else{
      if ($_FILES['image']['name'] != null) {
        $img = $_FILES['image']['name'];
        $file = "../images/".$img;
        $fileName = $_FILES['image']['tmp_name'];
        $imgType = pathinfo($file,PATHINFO_EXTENSION);
        
        if ($imgType != 'jpg' && $imgType != 'png' && $imgType != 'jpeg') {
          echo "<script>alert('Image type must be jpg or png or jpeg.');</script>";
        }else{
          $id = $_POST['id'];
          $name = $_POST['name'];
          $description = $_POST['description'];
          $price = $_POST['price'];
          $quantity = $_POST['quantity'];
          $category_id = $_POST['category'];
          $image = $img;
          move_uploaded_file($fileName, $file);

          $sql = "UPDATE products SET name=:name,description=:description,price=:price,quantity=:quantity,category_id=:category_id,image=:image WHERE id=$id";
          $stmt = $pdo->prepare($sql);
          $result = $stmt->execute(array(':name'=>$name,':description'=>$description,':price'=>$price,':quantity'=>$quantity,':category_id'=>$category_id,':image'=>$image));
          if ($result) {
            echo "<script>alert('Post Updated Success!');window.location.href='index.php';</script>";
          }
        }
      }else{
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $category_id = $_POST['category'];
        
        $sql = "UPDATE products SET name=:name,description=:description,price=:price,quantity=:quantity,category_id=:category_id WHERE id=$id";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute(array(':name'=>$name,':description'=>$description,':price'=>$price,':quantity'=>$quantity,':category_id'=>$category_id));
        if ($result) {
          echo "<script>alert('Post Updated Success!');window.location.href='index.php';</script>";
        }
      }
    }
  }
  $stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
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
                <form action="" method="post" enctype="multipart/form-data">
                  <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                  <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                  <div class="form-group">
                    <label for="">Name</label>
                    <p class="text-danger"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                    <input value="<?php echo $user['name']; ?>" type="text" name="name" class="form-control" placeholder="Enter Name">
                  </div>
                  <div class="form-group">
                    <label for="">Description</label>
                    <p class="text-danger"><?php echo empty($descriptionError) ? '' : '*'.$descriptionError; ?></p>
                    <textarea class="form-control" name="description" rows="6" cols="40">
                      <?php echo $user['description']; ?>
                    </textarea>
                  </div>
                  <div class="form-group">
                    <label for="">Price</label>
                    <p class="text-danger"><?php echo empty($priceError) ? '' : '*'.$priceError; ?></p>
                    <input  value="<?php echo $user['price']; ?>" type="number" name="price" class="form-control" placeholder="Enter Price">
                  </div>
                  <div class="form-group">
                    <label for="">Quantity</label>
                    <p class="text-danger"><?php echo empty($quantityError) ? '' : '*'.$quantityError; ?></p>
                    <input value="<?php echo $user['quantity']; ?>" type="number" name="quantity" class="form-control" placeholder="Enter Qty">
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
                      <?php if($value['id'] == $user['category_id']) : ?>
                        <option value="<?php echo $value['id']; ?>" selected><?php echo $value['name']; ?></option>
                      <?php else : ?>
                        <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                      <?php endif; ?>
                      <?php
                          }
                        }
                      ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="">Image</label>
                    <p class="text-danger"><?php echo empty($imageError) ? '' : '*'.$imageError; ?></p>
                    <img src="../images/<?php echo $user['image']; ?>" width="100" height="100" class="mb-2"><br>
                    <input type="file" name="image" class="">
                  </div>
                  <input type="submit" name="button" class="btn btn-primary" value="Update">
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
