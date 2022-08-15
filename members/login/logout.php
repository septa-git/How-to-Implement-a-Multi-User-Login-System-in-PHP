<?php
  session_start();
  $_SESSION=[];
  unset($_SESSION["member"]);
  unset($_SESSION["memberName"]);
  unset($_SESSION["memberEmail"]);
  session_unset();
  session_destroy();
  
  setcookie("id","",time()-3600);
  setcookie("key","",time()-3600);
  
  header("Location: login.php");
  exit;
?>