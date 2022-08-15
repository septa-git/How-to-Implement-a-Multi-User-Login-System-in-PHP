<?php 
  session_start();
  if(isset($_SESSION['member'])){
      header("location: /members/login/member-dashboard.php");
      exit();
  }
  
  require_once("../../inc/inc_connect.php");
  require_once("../../inc/inc_functions.php");
  
  $email=$_GET["email"];
  $token=$_GET["token"];
  $success="";
  $error="";
  
  if($email=="" or $token==""){
    $error.="Invalid link, email and token not available";
  }else{
    $sqliWhereEmail="select * from members where email='$email' and token='$token'";
    $sqlQuery=mysqli_query($connection,$sqliWhereEmail);
    $sqlNumRows=mysqli_num_rows($sqlQuery);
    
    if($sqlNumRows<1){
      $error.="Invalid link, invalid email and token";
    }
  }
  
  
  if(isset($_POST['submit'])){
    $password=$_POST['password'];
    $confirmPassword=$_POST['confirmPassword'];
    
    if($password=="" or $confirmPassword==""){
      $error.="Enter the password and conformating password";
    }elseif($password != $confirmPassword){
      $error.="Confirm password is not correct";
    }elseif(strlen($password)<3){
      $error.="Amount of password 3 characters";
    }
    
    if(empty($error)){
      $password=password_hash($password,PASSWORD_DEFAULT);
      $sqlSetPassword="update members set token='',password='$password' where email='$email'";
      mysqli_query($connection,$sqlSetPassword);
      $success="Password has been changed successfully, <a href='".baseUrl()."/login.php'>please login</a>.";
    }
  }
?>


<head>
  <title>CHANGED PASSWORD</title>
</head>
<?php
  include_once("../../views/header.php");
?>
<div class="wrapper">
  <?php if($success){ ?>
    <div class="alert alert-success" role="alert">
      <?= $success ?>
    </div>
  <?php }else if($error){ ?>
    <div class="alert alert-danger" role="alert">
      <?= $error ?>
    </div>
  <?php } ?>
  
  
  <h1 class="mb-3">Welcome to change password</h1>
  <form action="" method="post">
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" name="password" class="form-control" id="password">
    </div>
    <div class="mb-3">
      <label for="confirmPassword" class="form-label">Confirm Password</label>
      <input type="password" name="confirmPassword" class="form-control" id="confirmPassword">
    </div>
    <button type="submit" class="btn btn-primary btn-lg" name="submit" style="display:block;">SEND</button>
  </form>
</div>
<?php
  include_once("../../views/footer.php");
?>