<?php
  $host="localhost";
  $user="root";
  $pass="asa1234";
  $db="web";
  
  $connection=mysqli_connect($host,$user,$pass,$db)  or die(mysqli_connect_error());
  if(!$connection){
    die("Failed to connect to database");
  }
?>