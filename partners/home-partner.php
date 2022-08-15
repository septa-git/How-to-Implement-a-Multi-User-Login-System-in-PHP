<?php
  require_once("../inc/inc_connect.php");
  require_once("../inc/inc_functions.php");
  
  $error="";
  
  $id=getId();
  $sqliWherePartners="select * from partners where id='$id'";
  $sqlQuery=mysqli_query($connection,$sqliWherePartners);
  $sqliNumRows=mysqli_num_rows($sqlQuery);
  $sqlArr=mysqli_fetch_array($sqlQuery);
  $partner=$sqlArr["partner"];
  
  if($partner==""){
    $error="404 NOT FOUND!";
  }
?>
<head>
  <title>HOME PARTNER</title>
</head>
<?php
  include_once("../views/header.php");
?>
<div class="wrapper">
  <?php if($error){ ?>
    <div class="alert alert-danger" role="alert">
      <?= $error ?>
    </div>
  <?php }else{ ?>
    <h1>Lecturer Profile</h1>
    <p class="mb-5"><a href="/partners/our-partners.php">Check out our partners her</a></p>
    <div>
      <h1>Mitra: <?php echo $sqlArr['partner']; ?></h1>
      <img src="<?php echo "/partners/".partnerImage($sqlArr['id']); ?>" style="max-width:100px;max-height:100px;" />
      <h3 class="mt-3">Tentang Kami</h3>
      <p><?php echo setContent($sqlArr['content']); ?></p>
    </div>
  <?php } ?>
</div>
<?php
  include_once("../views/footer.php");
?>