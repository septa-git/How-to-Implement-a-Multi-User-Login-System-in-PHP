<?php
  include_once("../inc/inc_connect.php");
  
  if(isset($_GET["id"])){
    $id=$_GET["id"];
  }else{
    $id="";
  }
  
  $name="";
  $content="";
  $email="";
  $success="";
  $error="";
  
  if($id!=""){
    $sqliWhereContacts="select * from contacts where id='$id'";
    $sqlQuery=mysqli_query($connection,$sqliWhereContacts);
    $sqlArr=mysqli_fetch_array($sqlQuery);
    $name=$sqlArr["name"];
    $content=$sqlArr["content"];
    $email=$sqlArr["email"];
  }
  
    //Validasi jika input kosong
  if(isset($_POST["submit"])){
    $name=$_POST["name"];
    $content=$_POST["content"];
    $email=$_POST["email"];
    if($name=="" or $content=="" or $email==""){
      $error="Incomplete message data";
    }
      //Validasi email.
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $error.="<li>Your email is invalid</li>";
    }
    if(empty($error)){
      if($id!=""){
        $sqli="update contacts set name='$name',content='$content',email='$email',date=now() where id='$id'";
      }else{
        $sqli="insert into contacts(name,content,email) values('$name','$content','$email')";
      }
      $sqlQuery=mysqli_query($connection,$sqli);
      if($sqlQuery){
        $success="Successfully entered data";
      }else{
        $error="Failed enter to data";
      }
    }
  }
?>
<?php
  include_once("../views/header.php");
?>
<div class="wrapper">
  <?php if($success){ ?>
      <div class="alert alert-success" role="alert">
        <?= $success ?>
      </div>
  <?php } else if($error){ ?>
      <div class="alert alert-danger" role="alert">
          <?= $error ?>
      </div>
  <?php } ?>
  
  
  <h1 class="mb-3">Contact US</h1>
  <form action="" method="post">
    <div class="mb-3">
      <label for="name" class="form-label">Full Name</label>
      <input type="text" name="name" class="form-control" id="name" placeholder="Jhon Ecample" value="<?php echo $name; ?>">
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email address</label>
      <input type="email" name="email" class="form-control" id="email" placeholder="example@test.com" value="<?php echo $email; ?>">
    </div>
    <div class="mb-3">
      <label for="textarea" class="form-label">Your Message</label>
      <textarea class="form-control" id="textarea" rows="3" name="content" placeholder="Your Message"></textarea>
    </div>
    <button type="submit" class="btn btn-primary btn-lg" name="submit">SEND</button>
  </form>
</div>
<?php
  include_once("../views/footer.php");
?>