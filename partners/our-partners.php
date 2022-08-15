<?php
  session_start();
  require_once("../inc/inc_connect.php");
  require_once("../inc/inc_functions.php");
?>
<head>
  <title>OUR PARTNERS</title>
</head>
<?php
  include_once("../views/header.php");
  
  $sqliOrder="select * from partners order by id desc";
  $sqlQuery=mysqli_query($connection,$sqliOrder);
  
  
?>
<div class="wrapper">
  <h1>Our Partners</h1>
  <a href="/partners/partner-tables.php">Check out some of our partner tables here</a>
  <h2 class="mb-3"><?php echo baseUrl().'/image/'; ?></h2>
  
  
  <?php
    while($sqlArr=mysqli_fetch_array($sqlQuery)){
      if($sqlArr["status"]=="active"){
        
  ?>
    <a href="<?php echo partnerLink($sqlArr["id"]); ?>"><div class="card mb-3 inline" style="width: 18rem;">
      <img src="<?php echo partnerImage($sqlArr["id"]); ?>" class="card-img-top" alt="...">
      <div class="card-body">
        <h5 class="card-title">Mitra: <?= $sqlArr["partner"]; ?></h5>
      </div>
      <div class="card-body list-group-flush" style="margin-top:-1rem;">
        <a href="#" class="card-link"><?= $sqlArr["email"]; ?></a>
      </div>
  <?php } } ?>
</div>
<?php
  include_once("../views/footer.php");
?>