<?php 
  session_start();
  require_once "../config/config.php";
  require_once "../config/common.php";

  if (empty($_SESSION['user_id'] && $_SESSION['logged_in'])) {
    header('location:login.php');
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
                <h1 class="card-title">Order Listing</h1>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">id</th>
                      <th>User</th>
                      <th>Total Price</th>
                      <th>Order Date</th>
                      <th>Actions</th>
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

                      $sql = "SELECT * FROM sale_orders";
                      $stmt = $pdo->prepare($sql);
                      $stmt->execute();
                      $rawResult = $stmt->fetchAll();

                      $total_pages = ceil(count($rawResult) / $numofrecs);

                      $sql = "SELECT * FROM sale_orders LIMIT $offset,$numofrecs  ";
                      $stmt = $pdo->prepare($sql);
                      $stmt->execute();
                      $result = $stmt->fetchAll();           

                      if ($result) {
                      foreach ($result as $value) {
                        $uid = $value['user_id'];
                        $ustmt = $pdo->prepare("SELECT * FROM users where id=$uid");
                        $ustmt->execute();
                        $uResult = $ustmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <tr>
                      <td><?php echo escape($value['id']); ?></td>
                      <td><?php echo escape($uResult['name']); ?></td>
                      <td>
                        <?php echo escape($value['total_price']); ?>
                      <td><?php echo escape(date('d-m-Y',strtotime($value['order_date']))); ?></td>
                      </td>
                      <td>
                        <a href="order_detail.php?id=<?php echo $value['id'];?>" type="button" class="btn btn-warning">View</a>
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
