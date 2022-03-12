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
      if (strlen($_POST['password']) < 4) {
        $passwordError = "Password must be more than 4 characters.";
      }
    }
    else{
      $id = $_POST['id'];
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT) ;

      if (empty($_POST['role'])) {
        $role = 0;
      }else{
        $role = 1;
      }

      // for Email
      $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
      $stmt->execute(array(':email'=>$email,':id'=>$id));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($user) {
        echo "<script>alert('Email Duplicated.');</script>";
      }
      else{
        if ($password != null) {
          $pdostmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',password='$password',role=$role WHERE id=$id");
        }
        else{
          $pdostmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',role=$role WHERE id=$id");
        }
        $result = $pdostmt->execute();
        if ($result) {
          echo "<script>alert('Post Updated Success');window.location.href='userIndex.php';</script>";
        }       
      }
    }
  }

  $pdoStatement = $pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
  $pdoStatement->execute();
  $select = $pdoStatement->fetch(PDO::FETCH_ASSOC);
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
                  <input type="hidden" name="id" value="<?php echo escape($select['id']); ?>">
                  <div class="form-group">
                    <label for="">Name</label>
                    <p style="color:red"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                    <input value="<?php echo escape($select['name']); ?>" type="text" name="name" class="form-control" placeholder="Enter Name">
                  </div>
                  <div class="form-group">
                    <label for="">E-mail</label>
                    <p style="color:red"><?php echo empty($emailError) ? '' : '*'.$emailError; ?></p>
                    <input value="<?php echo escape($select['email']); ?>" type="email" name="email" class="form-control" placeholder="Enter E-mail" >
                  </div>
                  <div class="form-group">
                    <label for="">Password</label>
                    <p style="color:red"><?php echo empty($passwordError) ? '' : '*'.$passwordError; ?></p>
                    <input value="<?php echo $select['password']; ?>" type="password" name="password" class="form-control" placeholder="Enter Password" >
                  </div>
                  <div class="form-group">
                    <label for="">Admin</label><br>
                    <input type="checkbox" name="role" value="1" <?php echo $select['role']==1 ? 'checked' : ''; ?>>
                  </div>
                  <input type="submit" name="button" class="btn btn-warning" value="Update User">
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