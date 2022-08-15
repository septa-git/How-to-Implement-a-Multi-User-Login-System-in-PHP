<?php
  session_start();
  if(!isset($_SESSION["admin"])){
    header("Location: /admins/login/login.php");
    exit();
  }
  require_once("../../inc/inc_connect.php");
  require_once("../../inc/inc_functions.php");
  
  $success="";
  $error="";
  $searchData=(isset($_POST["searchData"]))?$_POST["searchData"]:"";
  
  if(isset($_GET["delete"])=="delete"){
    $id=$_GET["id"];
    $sqliDelete="delete from contacts where id='$id'";
    $sqliQuery=mysqli_query($connection,$sqliDelete);
    if($sqliQuery){
      $success="Successfully deleted to data";
    }
  }
  
  $numberPages=2;
  $amountData=count(query("SELECT * FROM contacts"));
  $numberPage=ceil($amountData / $numberPages);
  $activePage=(isset($_GET["page"])) ? $_GET["page"] : 1;
  $initialData=($numberPages * $activePage) - $numberPages;
    //Ambil data dari table mahasiswa / query data mahasiswa.
  $contacts=query("SELECT * FROM contacts ORDER BY id DESC LIMIT $initialData, $numberPages");
    //Tombol pencarian ditekan.
  if(isset($_POST["searchButton"])){
    $contacts=contactSearch($_POST["searchData"]);
  }
?>
<head>
  <title>CONTACT DATA</title>
</head>
<?php
  include_once("../../views/header.php");
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
  <?php } else if($error){ ?>
      <div class="alert alert-danger" role="alert">
          <?= $error ?>
      </div>
  <?php } ?>
  
  <h1 class="mt-5 mb-3">Contact Data</h1>
    <?php
      $sqliOrderContacts="select * from contacts order by id desc";
      $sqlContactsQuery=mysqli_query($connection,$sqliOrderContacts);
      $sqlArrContact=mysqli_fetch_array($sqlContactsQuery);
      if($sqlArrContact==""){
    ?>
        <div class="alert alert-danger" role="alert">
          No messages her yet
        </div>
    <?php
      }
    ?>
    
  <?php $number=1; foreach ($contacts as $dataI): ?>
    <div class="card mb-4" style="width: 18rem;">
    <div class="card-header">
      <h5>Name: <?= $dataI["name"]; ?></h5>
    </div>
    <div class="card-body">
      <p class="card-text"><?= $dataI["content"]; ?></p>
    </div>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><a href="#"><?= $dataI["email"]; ?></a></li>
    </ul>
    <div class="card-body">
      <label class="float-left"><?= $number; ?></label>
      <a class="card-link float-right" href="contact.php?delete&id=<?php echo $dataI["id"]; ?>">Delete</a>
      <div class="clear"></div>
    </div>
  </div>
  <?php $number++; endforeach; ?>
</div>
<?php
  include_once("../../views/footer.php");
?>