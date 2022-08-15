<?php 
  session_start();
  if(isset($_SESSION["member"])){
      header("location: /members/login/member-dashboard.php");
      exit();
  }
  
  require_once("../../inc/inc_connect.php");
  require_once("../../inc/inc_functions.php");
  
  $email="";
  $success="";
  $error="";
  
  if(isset($_POST["submit"])){
    $email=$_POST["email"];
    
    if($email==""){
      $error="Enter your email";
    }else{
      $sqliWhereEmail="select * from members where email='$email'";
      $sqlQuery=mysqli_query($connection,$sqliWhereEmail);
      $sqlNumRows=mysqli_num_rows($sqlQuery);
      
      if($sqlNumRows<1){
        $error="Email <b>".$email."</b> was not found";
      }
        //Validasi email.
      if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $error="Your email is invalid";
      }
    }
    
    if(empty($error)){
      $token=md5(rand(0,1000));
      $emailTitle="Ubah Password";
      $emailContent="Seseorang meminta untuk melakukan perubahan password. Silakan klik link di bawah ini:<br/>";
      $emailContent.=baseUrl()."/change-password.php?email=$email&token=$token";
      sendEmail($email,$email,$emailTitle,$emailContent);
      $sqliSetPassword="update members set token='$token' where email='$email'";
      mysqli_query($connection,$sqliSetPassword);
      $success="The change password link has been sent to your account email";
    }
  }
?>


<head>
  <title>FORGOT PASSWORD</title>
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
  
  
  <h1 class="mb-3">Welcome to forgot password</h1>
  <p class="mb-3">
    <a href="/members/login/login.php">Back to login page</a>
  </p>
  <form action="" method="post">
    <div class="mb-3">
      <label for="email" class="form-label">Your Email Account</label>
      <input type="email" name="email" class="form-control" id="email" value="<?php echo $email; ?>">
    </div>
    <button type="submit" class="btn btn-primary btn-lg" name="submit" style="display:block;">SEND</button>
  </form>
</div>
<?php
  include_once("../../views/footer.php");
?>