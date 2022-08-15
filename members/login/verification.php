<?php
  require_once("../../inc/inc_connect.php");
  
  $success="";
  $error="";
  
  if(!isset($_GET["email"]) or !isset($_GET["id"])){
    $error="Data needed for verfication is not availabe";
  }else{
    $email=$_GET["email"];
    $id=$_GET["id"];
    $sqliWhere="select * from members where email='$email'";
    $sqlQuery=mysqli_query($connection,$sqliWhere);
    $sqlArr=mysqli_fetch_array($sqlQuery);
    
    if($sqlArr["status"]==$id){
      $sqlUpdateCode="update members set status='1' where email='$email'";
      mysqli_query($connection,$sqlUpdateCode);
      $success="Active account, please login";
    }else{
      $error="Failed to login, Please check your account confirmation email";
    }
  }
?>
<?php
  require_once("../../inc/inc_connect.php");
?>
<head>
  <title>VERIFICATION</title>
</head>
<?php
  include_once("../../views/header.php");
?>
<div class="wrapper">
  <h1>Welcome to email verfication</h1>
  
  <?php if($success){ ?>
    <div class="alert alert-success" role="alert">
      <?= $success ?>
    </div>
  <?php } else if($error){ ?>
    <div class="alert alert-danger" role="alert">
        <?= $error ?>
    </div>
  <?php } ?>
</div>
<?php
  include_once("../../views/footer.php");
?>