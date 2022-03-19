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
                <h1 class="card-title">Monthly Reports</h1>
                <div class="float-right">
                  <a href="index.php" type="button" class="btn btn-warning">Back</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
               <table class="table table-bordered" id="dTable">
                  <thead>                  
                    <tr>
                      <th>ID</th>
                      <th>User</th>
                      <th>Total Amount</th>
                      <th>Order Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                        $current_date = date('Y-m-d');

                        $fromDate = date('Y-m-d',strtotime($current_date. ' +1 day'));
                        $toDate = date('Y-m-d',strtotime($current_date. ' -1 month'));

                        $sql = "SELECT * FROM sale_orders WHERE order_date < :from_date AND  order_date >=:to_date ORDER BY id DESC";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([':from_date'=>$fromDate,':to_date'=>$toDate]);
                        $result = $stmt->fetchAll();  

                        if ($result) {
                        $i = 1;
                        foreach ($result as $value) {

                        $user = $value['user_id'];
                        $stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$user);
                        $stmt->execute();
                        $userName = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <tr>
                      <td><?php echo escape($i); ?></td>
                      <td><?php echo escape($userName['name']); ?></td>
                      <td><?php echo escape($value['total_price']); ?></td>
                      <td><?php echo escape(date('d-m-Y',strtotime($value['order_date']))); ?></td>
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
