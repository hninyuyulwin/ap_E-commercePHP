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
                <h1 class="card-title">Order Details</h1>
                <div class="float-right">
                  <a href="order_list.php" type="button" class="btn btn-default">Back</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">id</th>
                      <th>Product</th>
                      <th>Quantity</th>
                      <th>Order Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php  
                        if (isset($_GET['pageno'])) {
                          $pageno = $_GET['pageno'];    
                        }else{
                          $pageno = 1;
                        }
                        $numofrecs = 1;
                        $offset = ($pageno - 1)*$numofrecs;

                        $sql = "SELECT * FROM sale_orders_details where sale_orders_id=".$_GET['id'];
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $rawResult = $stmt->fetchAll();  

                        $total_pages = ceil(count($rawResult)/$numofrecs);  

                        $sql = "SELECT * FROM sale_orders_details where sale_orders_id=".$_GET['id']." LIMIT $offset,$numofrecs";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll();   

                      if ($result) {
                      foreach ($result as $value) {

                        $pid = $value['product_id'];
                        $pstmt = $pdo->prepare("SELECT * FROM products where id=$pid");
                        $pstmt->execute();
                        $pResult = $pstmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <tr>
                      <td><?php echo escape($value['id']); ?></td>
                      <td><?php echo escape($pResult['name']); ?></td>
                      <td>
                        <?php echo escape($value['quantity']); ?>
                      <td><?php echo escape(date('d-m-Y',strtotime($value['order_date']))); ?></td>
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
                      <a class="page-link" href="?id=<?php echo $_GET['id'];?>&pageno=1">First</a>
                    </li>
                    <li class="page-item <?php if($pageno <=1 ){echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno <= 1){echo '#';}else{echo "?id=".$_GET['id']."&pageno=".($pageno-1);} ?>">Previous</a>
                    </li>
                    <li class="page-item active">
                      <a class="page-link " href="#"><?php echo $pageno; ?></a>
                    </li>
                    <li class="page-item <?php if($pageno >= $total_pages){echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno >= $total_pages){echo '#';}else{echo "?id=".$_GET['id']."&pageno=".($pageno+1);} ?>">Next</a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="?id=<?php echo $_GET['id'];?>&pageno=<?php echo $total_pages; ?>">Last</a>
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
