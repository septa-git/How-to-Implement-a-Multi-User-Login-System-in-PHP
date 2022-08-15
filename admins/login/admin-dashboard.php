<?php
  session_start();
  if(!isset($_SESSION["admin"])){
    header("Location: login.php");
    exit;
  }
  require_once("../../inc/inc_connect.php");
  require_once("../../inc/inc_functions.php");
?>
<head>
  <title>ADMIN DASHBOARD</title>
</head>
<?php
  include_once("../../views/header.php");
  
  if(!isset($_SESSION["adminName"])){
    $message="Welcome to dashboard page.";
  }else{
    $message="Welcome {$_SESSION['adminName']} to admin dashboard.";
  }
?>
  <h1><?= $message ?></h1>
  <p>
    <a href="<?php echo baseUrl()."/login/logout.php" ?>">Logout</a>
      <br>
    <a href="http://localhost:8000/admins/login/logout.php">Alternative Logout</a>
      <br>
    <a href="/partners/partner-tables.php">Check the partner the tables</a>
  </p>
<?php
  include_once("../../views/footer.php");
?>