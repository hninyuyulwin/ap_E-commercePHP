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
                  <a href="product_add.php" type="button" class="btn btn-success">Add New Product</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
               <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Description</th>
                      <th>Price</th>
                      <th>Qty</th>
                      <th>Category_id</th>
                      <th>Image</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    
                      if (isset($_GET['pageno'])){
                        $pageno = $_GET['pageno'];
                      }else{
                        $pageno = 1;
                      }
                      $numofrecs = 3;
                      $offset = ($pageno - 1) * $numofrecs;

                      if (empty($_POST['search']) && empty($_COOKIE['search'])) {
                        $sql = "SELECT * FROM products ORDER BY id DESC";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $rawResult = $stmt->fetchAll();

                        $total_pages = ceil(count($rawResult) / $numofrecs);

                        $sql = "SELECT * FROM products ORDER BY id DESC LIMIT $offset,$numofrecs";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                      }else{
                        $searchKey = $_COOKIE['search'] ? $_COOKIE['search'] : $_POST['search'];

                        $sql = "SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $rawResult = $stmt->fetchAll();

                        $total_pages = ceil(count($rawResult) / $numofrecs);

                        $sql = "SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numofrecs";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                      }                     

                      if ($result) {
                      foreach ($result as $value) {
                    ?>
                    <?php 
                      $cat = $value['category_id'];
                      $stmt = $pdo->prepare("SELECT * FROM categories WHERE id=$cat ORDER BY id DESC");
                      $stmt->execute();
                      $category = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <tr>
                      <td><?php echo escape($value['id']); ?></td>
                      <td><?php echo escape($value['name']); ?></td>
                      <td><?php echo escape(substr($value['description'],0,100)."..."); ?></td>
                      <td><?php echo escape($value['price']); ?></td>
                      <td><?php echo escape($value['quantity']); ?></td>
                      <td><?php echo escape($category['name']); ?></td>
                      <td><img src="../images/<?php echo escape($value['image']); ?>" width="100" height="100"></td>
                      <td>
                        <div class="btn btn-group">
                          <div class="container">
                            <a href="product_edit.php?id=<?php echo $value['id'];?>" type="button" class="btn btn-warning">Edit</a>
                          </div>
                          <div class="container">
                            <a onclick="return confirm('Are you sure want to delete?');" href="product_delete.php?id=<?php echo $value['id'];?>" type="button" class="btn btn-danger">Delete</a>
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
                    <li class="page-item active">
                      <a class="page-link " href="#"><?php echo $pageno; ?></a>
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
