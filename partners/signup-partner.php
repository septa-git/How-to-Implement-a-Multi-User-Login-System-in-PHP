<?php
  session_start();
  require_once("../inc/inc_connect.php");
  require_once("../inc/inc_functions.php");
  
  $partner="";
  $email="";
  $image="";
  $imageName="";
  $status="passive";
  $error="";
  
  if(isset($_GET["id"])){
    $id=$_GET["id"];
  }else{
    $id="";
  }
  
  if($id!=""){
    $sqliWhere="select * from partners where id='$id'";
    $sqliQuery=mysqli_query($connection,$sqliWhere);
    $sqlArr=mysqli_fetch_array($sqliQuery);
    $partner=$sqlArr["partner"];
    $email=$sqlArr["email"];
    $content=$sqlArr["content"];
    $image=$sqlArr["image"];
  }
  
  if(isset($_POST["submit"])){
    $partner=$_POST["partner"];
    $email=$_POST["email"];
    $content=$_POST["content"];
    if($partner=="" or $content=="" or $email==""){
      $error="Incomplete message data";
    }
      //Validasi email.
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
      $error.="<li>Your email is invalid</li>";
    }
      //Array ( [image] => Array ( [name] => vidma_recorder_08082022_134136.jpg [type] => image/jpeg [tmp_name] => /tmp/phpJ9Ae7b [error] => 0 [size] => 135973 ) )
      //print_r($_FILES);
    if($_FILES["image"]["name"]){
      $imageName=$_FILES["image"]["name"];
      $fileSize=$_FILES["image"]["size"];
      $fileName=$_FILES["image"]["tmp_name"];
      $fileDetail=pathinfo($imageName);
      $imageExtension=$fileDetail["extension"];
      
        //Cek apakah ukuran file mendukung untuk di upload.
      if($fileSize>1000000){
        $error="The image file size is too big";
      }
        //Array ( [dirname] => . [basename] => vidma_recorder_08082022_134136.jpg [extension] => jpg [filename] => vidma_recorder_08082022_134136 )
        //print_r($fileDetail);
      $trueExtension=array("jpg","jpeg","png","gif");
      if(!in_array($imageExtension,$trueExtension)){
        $error="What you input is not an image extension";
      }
    }
    if(empty($error)){
      if($imageName){
        date_default_timezone_set("Asia/Jakarta");
        $imageDirectory="image";
        @unlink($imageDirectory."/$image");
        $imageName="partner_".date("d-m-Y H-i-s")."_".$imageName;
        move_uploaded_file($fileName,$imageDirectory."/".$imageName);
        $image=$imageName;
      }else{
        $imageName=$image;
      }
      if($id!=""){
        if(isset($_SESSION["login"])){
          $sql="update partners set partner='$partner',email='$email',content='$content',image='$imageName',status='$status',date=now() where id='$id'";
        }else{
          $sql=false;
        }
      }else{
        $sql="insert into partners(partner,email,content,image,status) values('$partner','$email','$content','$imageName','$status')";
      }
      $sqliQuery=mysqli_query($connection,$sql);
      if($sqliQuery){
        $success="Successfully entered data";
      }else if($sql==false){
        $error="Sorry you have no access here";
      }else{
        $error="Failed enter to data";
      }
    }
  }
?>
<head>
  <title>SIGN UP PARTNER</title>
</head>
<?php
  include_once("../views/header.php");
?>
<div class="wrapper">
  <?php if($success){ ?>
      <div class="alert alert-success" role="alert">
        <?= $success ?>
        <p><a href="/partners/partner-tables.php">Check Partners Table Here</a></p>
      </div>
  <?php } else if($error){ ?>
      <div class="alert alert-danger" role="alert">
          <?= $error ?>
      </div>
  <?php } ?>
  
  
  <h1 class="mb-2">Please Register to be our partner</h1>
  <p class="mb-3"><a href="/partners/partner-tables.php">Check your pertner status here</a></p>
  <form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="partner" class="form-label">Partner Name</label>
      <input type="text" name="partner" class="form-control" id="partner" placeholder="Partner Name" value="<?php echo $partner; ?>">
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email address</label>
      <input type="email" name="email" class="form-control" id="email" placeholder="example@test.com" value="<?php echo(strtolower($email)); ?>">
    </div>
    <div class="mb-3">
      <label for="textarea" class="form-label">Your Message</label>
      <textarea class="form-control" id="textarea" rows="3" name="content" placeholder="Your Message"><?php echo $content; ?></textarea>
    </div>
    <?php
      if($image==""){
        echo "<img src='image/default-image/partner.png' style='max-height:100px;max-width:100px;'>";
      }else{
          echo "<img src='image/$image' style='max-height:100px;max-width:100px;'>";
      }
    ?>
    <input type="file" name="image">
    <button type="submit" class="btn btn-primary btn-lg mt-4 d-block" name="submit">SEND</button>
  </form>
</div>
<?php
  include_once("../views/footer.php");
?>