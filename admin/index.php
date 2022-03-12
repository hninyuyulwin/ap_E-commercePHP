<?php 
  session_start();
  require_once "../config/config.php";
  require_once "../config/common.php";

  if (empty($_SESSION['user_id'] && $_SESSION['logged_in'])) {
    header('location:login.php');
  }  
  if($_SESSION['role'] != 1) {
    header('location:login.php');
  }
  if (isset($_POST['search'])) {
    setcookie('search', $_POST['search'], time() + (86400 * 30), "/"); // 86400 = 1 day
  }else{
    if (empty($_GET['pageno'])) {
      unset($_COOKIE['search']); 
      setcookie('search', null, -1, '/'); 
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
                <h1 class="card-title">Product Listing</h1>
                <div class="float-right">
                  <a href="add.php" type="button" class="btn btn-success">New Blog Post</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
               
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
