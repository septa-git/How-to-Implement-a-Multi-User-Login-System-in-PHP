<?php
  session_start();
  require_once("../inc/inc_connect.php");
  require_once("../inc/inc_functions.php");
  
  $success="";
  $error="";
  $searchData=(isset($_POST["searchData"]))?$_POST["searchData"]:"";
  
  if(isset($_GET["delete"])=="delete"){
    if(isset($_SESSION["admin"])){
      $id=$_GET["id"];
      $sqliDelete="delete from partners where id='$id'";
      $sqliQuery=mysqli_query($connection,$sqliDelete);
      if($sqliQuery){
        $success="Successfully deleted to data";
      }
    }
    $error="Sorry you have no access here";
  }
    //Aktif dan Pasif partner
  if(isset($_GET["active"])=="active"){
    if(isset($_SESSION["admin"])){
      $id=$_GET["id"];
      $statusPartner="active";
      $sqlSetStatusPartner="update partners set status='$statusPartner' where id='$id'";
      $sqliQuery=mysqli_query($connection,$sqlSetStatusPartner);
      if($sqliQuery){
        $success="Successfully Active to data";
      }
    }
    $error="Sorry you have no access here";
  }
  if(isset($_GET["passive"])=="passive"){
    if(isset($_SESSION["admin"])){
      $id=$_GET["id"];
      $statusPartner="passive";
      $sqlSetStatusPartner="update partners set status='$statusPartner' where id='$id'";
      $sqliQuery=mysqli_query($connection,$sqlSetStatusPartner);
      if($sqliQuery){
        $success="Successfully Passive to data";
      }
    }
    $error="Sorry you have no access here";
  }
  
  
  $numberPages=2;
  $amountData=count(query("SELECT * FROM partners"));
  $numberPage=ceil($amountData / $numberPages);
  $activePage=(isset($_GET["page"])) ? $_GET["page"] : 1;
  $initialData=($numberPages * $activePage) - $numberPages;
    //Ambil data dari table mahasiswa / query data mahasiswa.
  $partners=query("SELECT * FROM partners ORDER BY id DESC LIMIT $initialData, $numberPages");
    //Tombol pencarian ditekan.
  if(isset($_POST["searchButton"])){
    $partners=partnerSearch($_POST["searchData"]);
  }
?>
<head>
  <title>PARTNERS TABLE</title>
</head>
<?php
  include_once("../views/header.php");
?>

<div class="wrapper">
  <form action="" method="post">
    <div class="input-group mb-3 w-50">
      <input type="text" name="searchData" class="form-control" placeholder="Search for message users" aria-label="Username" value="<?= $searchData ?>" autocomplete="off">
      <span class="input-group-text">@</span>
      <button name="searchButton" type="type" class="btn btn-primary">Primary</button>
    </div>
  </form>
  
  
  <nav aria-label="...">
    <ul class="pagination">
      <?php if($activePage > 1): ?>
        <li class="page-item">
          <a class="page-link" href="?page=<?=$activePage - 1;?>">Previous</a>
        </li>
      <?php endif; ?>
      <?php for($i=1;$i<=$numberPage;$i++): ?>
        <?php if($i==$activePage): ?>
          <li class="page-item active" aria-current="page">
            <a class="page-link" href="?page=<?=$i;?>"><?=$i;?></a>
          </li>
        <?php else : ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?=$i;?>"><?=$i;?></a>
          </li>
        <?php endif; ?>
      <?php endfor; ?>
      <?php if($activePage < $numberPage): ?>
        <li class="page-item">
          <a class="page-link" href="?page=<?=$activePage + 1;?>">Next</a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
  
  <?php if($success){ ?>
    <div class="alert alert-success" role="alert">
      <?= $success ?>
    </div>
  <?php }else if($error){ ?>
    <div class="alert alert-danger" role="alert">
      <?= $error ?>
    </div>
  <?php } ?>
  
  <h1 class="mt-5 mb-1">Partners Table</h1>
  <p>
    <a class="mb-3" href="/partners/signup-partner.php">Register to join our partner</a>
      <br>
    <a class="mb-3" href="/partners/our-partners.php">Check our partners here</a>
  </p>
  <?php
    $sqliOrderPartners="select * from partners order by id desc";
    $sqlPartnersQuery=mysqli_query($connection,$sqliOrderPartners);
    $sqlArrContact=mysqli_fetch_array($sqlPartnersQuery);
    if($sqlArrContact==""){
  ?>
      <div class="alert alert-danger" role="alert">
        There is no partner her yet
      </div>
  <?php
    }
  ?>
  
  
  <table class="table">
    <thead>
      <tr>
        <th scope="col">NO</th>
        <th scope="col">Partner</th>
        <th scope="col">Email</th>
        <th scope="col">Status</th>
        <?php if(isset($_SESSION["admin"])): ?>
          <th scope="col">Edit</th>
          <th scope="col">Delete</th>
          <th scope="col">Action</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody class="table-group-divider">
      <?php $number=1; foreach ($partners as $dataI): ?>
      <tr>
        <th scope="row"><?= $number; ?></th>
        <td><?= $dataI["partner"]; ?></td>
        <td><?= $dataI["email"]; ?></td>
        <?php
          $statusPartner=$dataI["status"];
          if($dataI["status"]=="passive"){
            $statusPartner="passive";
            $styleStatus="color:red;";
            $styleButton="btn btn-primary";
          }else{
            $statusPartner="active";
            $styleStatus="color:green;";
            $styleButton="btn btn-danger";
          }
        ?>
        <td style="<?= $styleStatus ?>"><?= $statusPartner ?></td>
        <?php if(isset($_SESSION["admin"])): ?>
          <td><a class="card-link" href="signup-partner.php?id=<?php echo $dataI["id"]; ?>">Edit</a></td>
          <td><a class="card-link" href="partner-tables.php?delete&id=<?php echo $dataI["id"]; ?>">Delete</a></td>
          <?php
            if($dataI["status"]=="passive"){
              $statusButton="active";
              $styleButton="btn btn-primary";
            }else{
              $statusButton="passive";
              $styleButton="btn btn-danger";
            }
          ?>
          <td><a class="<?= $styleButton ?>" type="submit" href="partner-tables.php?<?php echo $statusButton ?>&id=<?php echo $dataI["id"]; ?>"><?= $statusButton ?></a></td>
        <?php endif; ?>
      </tr>
      <?php $number++; endforeach; ?>
    </tbody>
  </table>
</div>
<?php
  include_once("../views/footer.php");
?>