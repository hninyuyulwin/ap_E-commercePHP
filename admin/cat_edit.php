<?php 
  session_start();
  require_once "../config/config.php";
  require_once "../config/common.php";

  if (empty($_SESSION['user_id'] && $_SESSION['logged_in'])) {
    header('location:login.php');
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
      $id = $_POST['id'];
      $name = $_POST['name'];
      $description = $_POST['description'];

      $sql = "UPDATE categories SET name=:name,description=:description WHERE id=:id";
      $pdoStatement = $pdo->prepare($sql);
      $result = $pdoStatement->execute(array(':name'=>$name,':description'=>$description,':id'=>$id));
      if ($result) {
        echo "<script>alert('Post Updated Success');window.location.href='category.php';</script>";
      }
    }
  }
  $stmt = $pdo->prepare("SELECT * FROM categories WHERE id=".$_GET['id']);
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
                  <a href="category.php" type="button" class="btn btn-warning">Back</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="" method="post">
                  <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                  <input type="hidden" name="id" value="<?php echo escape($user['id']); ?>">
                  <div class="form-group">
                    <label for="">Name</label>
                    <p class="text-danger"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                    <input value="<?php echo escape($user['name']); ?>" type="text" name="name" class="form-control" placeholder="Enter Name">
                  </div>
                  <div class="form-group">
                    <label for="">Description</label>
                    <p class="text-danger"><?php echo empty($descriptionError) ? '' : '*'.$descriptionError; ?></p>
                    <textarea class="form-control" name="description" rows="6" cols="40">
                      <?php echo escape($user['description']); ?>
                    </textarea>
                  </div>
                  <input type="submit" name="button" class="btn btn-success" value="Edit">
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
