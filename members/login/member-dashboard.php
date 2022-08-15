<?php
  session_start();
  if(!isset($_SESSION["member"])){
    header("Location: /members/login/login.php");
    exit;
  }
  require_once("../../inc/inc_connect.php");
  require_once("../../inc/inc_functions.php");
?>
<head>
  <title>MEMBER DASHBOARD</title>
</head>
<?php
  include_once("../../views/header.php");
  
  if(!isset($_SESSION["member"])){
    $message="Welcome to dashboard page.";
  }else{
    $message="Welcome {$_SESSION['memberName']} to member dashboard.";
  }
?>
  <h1><?= $message ?></h1>
  <p>
    <a href="<?php echo baseUrl()."/login/logout.php" ?>">Logout</a>
      <br>
    <a href="http://localhost:8000/members/login/logout.php">Alternative Logout</a>
      <br>
    <a href="/members/member-tables.php">Check the member data table</a>
  </p>
<?php
  include_once("../../views/footer.php");
?>