<?php 
  session_start();
  require_once "../config/config.php";
  require_once "../config/common.php";

  if (empty($_SESSION['user_id'] && $_SESSION['logged_in']) && $_SESSION['role'] != 1) {
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
                <h1 class="card-title">Best Seller Items</h1>
                <div class="float-right">
                  <a href="index.php" type="button" class="btn btn-primary">Back</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
               <table class="table table-bordered" id="dTable">
                  <thead>                  
                    <tr>
                      <th>ID</th>
                      <th>Product Name</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
                      $sql = "SELECT * FROM sale_orders_details GROUP BY product_id HAVING SUM(quantity)>2";
                      $stmt = $pdo->prepare($sql);
                      $stmt->execute();
                      $result = $stmt->fetchAll();

                      if ($result) {
                      $i = 1;
                      foreach ($result as $value) {
                      $pname = $value['product_id'];
                      $stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$pname);
                      $stmt->execute();
                      $pResult = $stmt->fetch(PDO::FETCH_ASSOC);
                  ?>
                    <tr>
                      <td><?php echo escape($i); ?></td>
                      <td><?php echo escape($pResult['name']); ?></td>
                    </tr>
                    <?php
                      $i++;
                        }
                      }
                    ?>
                  </tbody>
                </table><br>
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
  include "footer.php";
?>
<script>
  $(document).ready(function() {
    $('#dTable').DataTable();
  });
</script>
