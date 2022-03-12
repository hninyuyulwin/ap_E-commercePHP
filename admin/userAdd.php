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
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password'])< 4) {
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
    }
    else{
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

      if (empty($_POST['role'])) {
        $role = 0;
      }else{
        $role = 1;
      }
      $sql = "SELECT * FROM users WHERE email=:email";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':email',$email);
      $stmt->execute();
      $user = $stmt->fetchAll();

      if ($user) {
        echo "<script>alert('Email Duplicated.');</script>";
      }
      else{
        $sql = "INSERT INTO users(name, email, password, role) VALUES (:name,:email,:password,:role)";
        $pdoStatement = $pdo->prepare($sql);
        $result = $pdoStatement->execute(array(':name'=>$name,':email'=>$email,':password'=>$password,':role'=>$role));
        if ($result) {
        echo "<script>alert('Post Created Success');window.location.href='userIndex.php';</script>";
        }
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
                <h1 class="card-title">Create User</h1>
                <div class="float-right">
                  <a href="userIndex.php" type="button" class="btn btn-warning">Back</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="userAdd.php" method="post">
                  <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                  <div class="form-group">
                    <label for="">Name</label>
                    <p style="color:red"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                    <input type="text" name="name" class="form-control" placeholder="Enter Name">
                  </div>
                  <div class="form-group">
                    <label for="">E-mail</label>
                    <p style="color:red"><?php echo empty($emailError) ? '' : '*'.$emailError; ?></p>
                    <input type="email" name="email" class="form-control" placeholder="Enter E-mail" >
                  </div>
                  <div class="form-group">
                    <label for="">Password</label>
                    <p style="color:red"><?php echo empty($passwordError) ? '' : '*'.$passwordError; ?></p>
                    <input type="password" name="password" class="form-control" placeholder="Enter Password" >
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