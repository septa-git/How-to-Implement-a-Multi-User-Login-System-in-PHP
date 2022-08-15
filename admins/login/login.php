<?php
  session_start();
  if(isset($_SESSION["admin"])){
    header("Location: admin-dashboard.php");
    exit;
  }
  require_once("../../inc/inc_connect.php");
  
  $admin="";
  $error="";
  
    //cek cookie.
  if(isset($_COOKIE["id"])&&isset($_COOKIE["key"])){
    $id=$_COOKIE["id"];
    $key=$_COOKIE["key"];
      //ambil username berdasarkan id.
    $result=mysqli_query($connection,"SELECT admin FROM admins WHERE id=$id");
    $row=mysqli_fetch_assoc($result);
      //cek cookie dan username;
    if($key===md5($row["admin"])){
      $_SESSION["admin"]=true;
    }
  }
  
  if(isset($_POST["submit"])){
    $admin=$_POST["admin"];
    $password=$_POST["password"];
    
    if($admin=="" or $password==""){
      $error="Please enter all data";
    }else{
      $sqliWhereAdmin="select * from admins where admin='$admin'";
      $sqlQuery=mysqli_query($connection,$sqliWhereAdmin);
      $sqlArr=mysqli_fetch_array($sqlQuery);
      $sqlNumRows=mysqli_num_rows($sqlQuery);
      
      if($sqlNumRows<1){
        $error="User not found";
      }else if($sqlArr["password"] != md5($password)){
        $error="Wrong password";
      }else{
        $result=mysqli_query($connection,"SELECT * FROM admins WHERE admin = '$admin'");
          //Cek username.
        if(mysqli_num_rows($result) === 1){
            //Cek password.
          $row=mysqli_fetch_assoc($result);
              //Set session.
            $_SESSION["admin"]=true;
            if(isset($_POST["remember"])){
                //buat cookie.
              setcookie("id",$row["id"],time()+60);
              setcookie("key",md5($row["admin"]), time()+60);
            }
            $_SESSION["adminName"]=$admin;
            header("Location: admin-dashboard.php");
            exit;
        }
      }
    }
  }
?>
<head>
  <title>LOGIN ADMIN</title>
</head>
<?php
  include_once("../../views/header.php");
?>
<div class="wrapper">
  <?php if($error){ ?>
      <div class="alert alert-danger" role="alert">
          <?= $error ?>
      </div>
  <?php } ?>
  
  
  <h1 class="mb-3">Welcome to login admin</h1>
  <form action="" method="post">
    <div class="mb-3">
      <label for="admin" class="form-label">Admin</label>
      <input type="text" name="admin" class="form-control" id="admin" value="<?php echo $admin; ?>">
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Your Password</label>
      <input type="password" name="password" class="form-control" id="password">
    </div>
    <input type="checkbox" name="remember" id="remember"> Remember Me
    <button type="submit" class="btn btn-primary btn-lg" name="submit" style="display:block;">SEND</button>
  </form>
</div>
<?php
  include_once("../../views/footer.php");
?>