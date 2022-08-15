<?php
  session_start();
  if(isset($_SESSION["member"])){
    header("Location: /members/login/member-dashboard.php");
    exit();
  }
  require_once("../../inc/inc_connect.php");
  
  $email="";
  $success="";
  $error="";
  
        //cek cookie.
  if(isset($_COOKIE["id"])&&isset($_COOKIE["key"])){
    $id=$_COOKIE["id"];
    $key=$_COOKIE["key"];
      //ambil username berdasarkan id.
    $result=mysqli_query($connection,"SELECT member FROM members WHERE id=$id");
    $row=mysqli_fetch_assoc($result);
      //cek cookie dan username;
    if($key===hash("sha256",$row["member"])){
      $_SESSION["member"]=true;
    }
  }
  if(isset($_POST["submit"])){
    $email=$_POST["email"];
    $password=$_POST["password"];
    
    if($email=="" or $password==""){
      $error.="<li>Please enter all data</li>";
    }else {
      $sqliWhereEmail="select * from members where email='$email'";
      $sqlQuery=mysqli_query($connection,$sqliWhereEmail);
      $sqlArr=mysqli_fetch_array($sqlQuery);
      $sqlNumRows=mysqli_num_rows($sqlQuery);
      
      
      if($sqlArr["status"]!="1" && $sqlNumRows>0){
        $error.="<li>The account you have is not yet active</li>";
      }
      /*if($sqlArr["password"]!=md5($password) && $sqlNumRows>0 && $sqlArr["status"]=="1"){
        $error.="<li>Check your account and password</li>";
      }*/
      if($sqlNumRows<1 or $sqlArr["password"] != $password){
        $error.="<li>Check your account and password</li>";
      }
      
      
      if(password_verify($password, $sqlArr["password"])){
          //Set session.
        $_SESSION["member"]=true;
        $_SESSION["memberName"]=$sqlArr["member"];
        $_SESSION["memberEmail"]=$sqlArr["email"];
        if(isset($_POST["remember"])){
            //buat cookie.
          setcookie("id",$sqlArr["id"],time()+60);
          setcookie("key",hash("sha256",$sqlArr["member"]), time()+60);
        }
        header("Location: /members/login/member-dashboard.php");
        exit();
      }
    }
  }
?>

<head>
  <title>LOGIN MEMBERS</title>
</head>
<?php
  include_once("../../views/header.php");
?>
<div class="wrapper">
  <?php if($error){ ?>
      <div class="alert alert-danger" role="alert">
          <ul><?= $error ?></ul>
      </div>
  <?php } ?>
  
  
  <h1 class="mb-3">Welcome to login member</h1>
  <p class="mb-3">
    <a href="/members/login/register.php">Login to register</a>
      <br>
    <a href="/members/login/forgot-password.php">Forgot password</a>
  </p>
  <form action="" method="post">
    <div class="mb-3">
      <label for="email" class="form-label">Your Email Account</label>
      <input type="email" name="email" class="form-control" id="email" value="<?php echo $email; ?>">
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