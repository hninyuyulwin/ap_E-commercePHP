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
    if (empty($_POST['name']) || empty($_POST['description'])) {
      if (empty($_POST['name'])) {
        $nameError = "Name Field Required!";
      }
      if (empty($_POST['description'])) {
        $descriptionError = "Description Field Required!";
      }
    }
    else{
      $name = $_POST['name'];
      $description = $_POST['description'];

      $sql = "INSERT INTO categories(name, description) VALUES (:name,:description)";
      $pdoStatement = $pdo->prepare($sql);
      $result = $pdoStatement->execute(array(':name'=>$name,':description'=>$description));
      if ($result) {
        echo "<script>alert('Post Created Success');window.location.href='category.php';</script>";
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
                  <a href="category.php" type="button" class="btn btn-warning">Back</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="cat_add.php" method="post">
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
