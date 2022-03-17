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
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password'])< 4 || empty($_POST['phone']) || empty($_POST['address']) ) {
      if (empty($_POST['name'])) {
        $nameError = "Name field cannot be null.";
      }
      if (empty($_POST['email'])) {
        $emailError = "Email field cannot be null.";
      }
      if (empty($_POST['password'])) {
        $passwordError = "Password field cannot be null.";
      }
      else if (strlen($_POST['password']) < 4) {
        $passwordError = "Password must be more than 4 characters.";
      }
      if (empty($_POST['phone'])) {
        $phoneError = "Phone field cannot be null.";
      }
      if (empty($_POST['address'])) {
        $addressError = "Address field cannot be null.";
      }
    }
    else{
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $phone = $_POST['phone'];
      $address = $_POST['address'];

      if (empty($_POST['role'])) {
        $role = 0;
      }else{
        $role = 1;
      }
      $sql = "SELECT * FROM users WHERE email=:email AND id!=:id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([':email'=>$email,':id'=>$id]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($user) {
        echo "<script>alert('Email Duplicated.');</script>";
      }
      else{
        if (empty($_POST['password'])) {
          $pdoStatement = $pdo->prepare("UPDATE users SET name=:name, email=:email,phone=:phone ,address=:address, role=:role WHERE id=".$_GET['id']);
        }else{
          $pdoStatement = $pdo->prepare("UPDATE users SET name=:name, email=:email,phone=:phone,password=:password ,address=:address, role=:role WHERE id=".$_GET['id']);
        }
        $result = $pdoStatement->execute(array(':name'=>$name,':email'=>$email,':phone'=>$phone,':password'=>$password,':address'=>$address,':role'=>$role));
        if ($result) {
        echo "<script>alert('Post Updated Success');window.location.href='userIndex.php';</script>";
        }
      }
    }
    }
  $stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
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
                <h1 class="card-title">Edit User</h1>
                <div class="float-right">
                  <a href="userIndex.php" type="button" class="btn btn-warning">Back</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="" method="post">
                  <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                  <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                  <div class="form-group">
                    <label for="">Name</label>
                    <p style="color:red"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                    <input value="<?php echo $user['name']; ?>" type="text" name="name" class="form-control" placeholder="Enter Name">
                  </div>
                  <div class="form-group">
                    <label for="">E-mail</label>
                    <p style="color:red"><?php echo empty($emailError) ? '' : '*'.$emailError; ?></p>
                    <input value="<?php echo $user['email']; ?>" type="email" name="email" class="form-control" placeholder="Enter E-mail" >
                  </div>
                  <div class="form-group">
                    <label for="">Phone</label>
                    <p style="color:red"><?php echo empty($passwordError) ? '' : '*'.$passwordError; ?></p>
                    <input value="<?php echo $user['phone']; ?>" type="number" name="phone" class="form-control" placeholder="Enter Phone Number" >
                  </div>
                  <div class="form-group">
                    <label for="">Password</label>
                    <p style="color:red"><?php echo empty($passwordError) ? '' : '*'.$passwordError; ?></p>
                    <input value="<?php echo $user['password']; ?>" type="password" name="password" class="form-control" placeholder="Enter Password" >
                  </div>
                  <div class="form-group">
                    <label for="">Address</label>
                    <p style="color:red"><?php echo empty($passwordError) ? '' : '*'.$passwordError; ?></p>
                    <input value="<?php echo $user['address']; ?>" type="text" name="address" class="form-control" placeholder="Enter Address" >
                  </div>
                  <div class="form-group">
                    <label for="">Admin</label><br>
                    <input type="checkbox" name="role">
                  </div>
                  <input type="submit" name="button" class="btn btn-success" value="Add User">
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