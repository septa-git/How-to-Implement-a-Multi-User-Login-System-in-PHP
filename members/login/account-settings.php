<?php
  session_start();
  if(!isset($_SESSION["member"])){
    header("Location: /members/login/login.php");
    exit();
  }
  require_once("../../inc/inc_connect.php");
  
  $member="";
  $success="";
  $error="";
  
  if(isset($_POST["submit"])){
    $member=$_POST["member"];
    $oldPassword=$_POST["oldPassword"];
    $newPassword=$_POST["newPassword"];
    $confirmPassword=$_POST["confirmPassword"];
    
    
    $sqliWhereEmail="select * from members where email='".$_SESSION["memberEmail"]."'";
    $sqlQuery=mysqli_query($connection,$sqliWhereEmail);
    $sqlArr=mysqli_fetch_array($sqlQuery);
    $sqlNumRows=mysqli_num_rows($sqlQuery);
    
    if($member==""){
      $error.="<li>Your account name cannot be empty</li>";
    }
    if($oldPassword=="" or $newPassword=="" or $confirmPassword==""){
      $error.="<li>Enter all password data</li>";
    }
    if($newPassword!=$confirmPassword){
      $error.="<li>Unauthorized password confirmation</li>";
    }
    if(strlen($newPassword)<3){
      $error.="<li>The allowed password character length is 3</li>";
    }
    if($sqlArr["password"] != $oldPassword){
      $error.="<li>Old Password is not correct</li>";
    }
    if(password_verify($oldPassword, $sqlArr["password"])){
      $sqlSetMembers="update members set member='".$member."' where email='".$_SESSION['memberEmail']."'";
      mysqli_query($connection,$sqlSetMembers);
      $_SESSION['memberName']=$member;
      
      if($newPassword){
        $newPassword=password_hash($newPassword,PASSWORD_DEFAULT);
        $sqlSetPasswords="update members set password='".$newPassword."' where email='".$_SESSION['memberEmail']."'";
        mysqli_query($connection,$sqlSetPasswords);
      }
      header("Location: /members/login/member-dashboard.php");
      exit();
    }
  }
?>
<head>
  <title>ACCOUNT SETTINGS</title>
</head>
<?php
  include_once("../../views/header.php");
?>
<div class="wrapper">
  <?php if($success){ ?>
      <div class="alert alert-success" role="alert">
        <?= $success ?>
      </div>
  <?php } else if($error){ ?>
      <div class="alert alert-danger" role="alert">
          <ul><?= $error ?></ul>
      </div>
  <?php } ?>
  
  
  <h1>Welcome to my account settings</h1>
  <p class="mb-3">
    <a href="/members/login/member-dashboard.php">Your Dashboard</a>
  </p>
  <form action="" method="post">
    <div class="mb-3">
      <label for="member" class="form-label">User</label>
      <input type="member" name="member" class="form-control" id="member" value="<?php echo $member; ?>">
    </div>
    <div class="mb-3">
      <label for="oldPassword" class="form-label">Old Password</label>
      <input type="password" name="oldPassword" class="form-control" id="oldPassword">
    </div>
    <div class="mb-3">
      <label for="newPassword" class="form-label">New Password</label>
      <input type="password" name="newPassword" class="form-control" id="newPassword">
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