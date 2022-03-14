<?php 
  session_start();
  require_once "../config/config.php";
  require_once "../config/common.php";

  if (empty($_SESSION['user_id'] && $_SESSION['logged_in'])) {
    header('location:login.php');
  } 
  if($_SESSION['role'] != 1) {
    header('location:../login.php');
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
                <h1 class="card-title">User Listing</h1>
                <div class="float-right">
                  <a href="userAdd.php" type="button" class="btn btn-success">Add New User</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th>Id</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Password</th>
                      <th>Address</th>
                      <th>Role</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php  
                      if(isset($_GET['pageno'])){
                        $pageno = $_GET['pageno'];
                      }else{
                        $pageno = 1;
                      }
                      $numOfrecs = 5;
                      $offset = ($pageno - 1)*$numOfrecs; 

                      if (empty($_POST['search']) && empty($_COOKIE['search'])) {
                        $sql = "SELECT * FROM users ORDER BY id DESC";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $rawResult = $stmt->fetchAll();

                        $total_pages = ceil(count($rawResult)/$numOfrecs);
                        
                        $sql = "SELECT * FROM users LIMIT $offset,$numOfrecs ";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                      }
                      else{
                        $searchKey = $_POST['search'] ? $_POST['search'] : $_COOKIE['search'];

                        $sql = "SELECT * FROM users where name like '%$searchKey%' ORDER BY id DESC";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $rawResult = $stmt->fetchAll();

                        $total_pages = ceil(count($rawResult)/$numOfrecs);
                        
                        $sql = "SELECT * FROM users where name like '%$searchKey%' LIMIT $offset,$numOfrecs ";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                      }
                      if ($result) {
                      foreach ($result as $value) {
                    ?>
                    <tr>
                      <td><?php echo escape($value['id']); ?></td>
                      <td><?php echo escape($value['name']); ?></td>
                      <td><?php echo escape($value['email']);?> </td>
                      <td><?php echo escape($value['phone']);?> </td>
                      <td><?php echo escape(substr($value['password'],0,10));?> </td>
                      <td><?php echo escape($value['address']);?> </td>
                      <td><?php echo $value['role'] == 1 ? 'admin' : 'user';?> </td>
                      <td>
                        <div class="btn btn-group">
                          <div class="container">
                            <a href="userEdit.php?id=<?php echo $value['id'];?>" type="button" class="btn btn-warning">Edit</a>
                          </div>
                          <div class="container">
                            <a onclick="return confirm('Are you sure want to delete?');" href="userDelete.php?id=<?php echo $value['id'];?>" type="button" class="btn btn-danger">Delete</a>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <?php
                        }
                      }
                    ?>
                  </tbody>
                </table><br>
                <!-- Pagination Start -->
                <nav aria-label="Page navigation example" class="float-right">
                  <ul class="pagination">
                    <li class="page-item">
                      <a class="page-link" href="?pageno=1">First</a>
                    </li>
                    <li class="page-item <?php if($pageno <=1 ){echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno <= 1){echo '#';}else{echo "?pageno=".($pageno-1);} ?>">Previous</a>
                    </li>
                    <li class="page-item <?php if($pageno == $pageno){echo 'active';}?>">
                      <a class="page-link " href="<?php echo '?pageno='.($pageno); ?>"><?php echo $pageno; ?></a>
                    </li>
                    <li class="page-item <?php if($pageno == $total_pages){echo 'disabled';} ?>">
                      <a class="page-link " href="<?php if($pageno == $total_pages){echo 'disabled';}else{ echo '?pageno='.($pageno+1);} ?>"><?php echo $pageno+1; ?></a>
                    </li>
                    <li class="page-item <?php if($pageno >= $total_pages){echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno >= $total_pages){echo '#';}else{echo "?pageno=".($pageno+1);} ?>">Next</a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a>
                    </li>
                  </ul>
                </nav>
                <!-- Pagination End -->
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
