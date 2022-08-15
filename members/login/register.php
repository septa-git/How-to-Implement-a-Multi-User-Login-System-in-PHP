<?php
  session_start();
  if(isset($_SESSION["member"])){
    header("Location: /members/login/member-dashboard.php");
    exit();
  }
  require_once("../../inc/inc_connect.php");
  require_once("../../inc/inc_functions.php");
  
  
  $member="";
  $email="";
  $success="";
  $error="";
  
  
  if(isset($_POST["submit"])){
    $member=$_POST["member"];
    $email=$_POST["email"];
    $password=mysqli_real_escape_string($connection, $_POST["password"]);
    $confirmPassword=mysqli_real_escape_string($connection, $_POST["confirmPassword"]);
    
    if($member=="" or $email=="" or $password=="" or $confirmPassword==""){
      $error="Please enter all data";
    }
      //Cek email apakah email yang dimasukan sudah ada di dalam database atau belum.
    if($email!=""){
      $sqliWhereEmail="select email from members where email='$email'";
      $sqlQuery=mysqli_query($connection,$sqliWhereEmail);
      $sqlNumRows=mysqli_num_rows($sqlQuery);
      
      if($sqlNumRows>0){
        $error.="<li>The email you entered has been registered</li>";
      }
        //Validasi email.
      if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $error.="<li>Your email is invalid</li>";
      }
    }
      //Cek konfirmasi password.
    if($password!=$confirmPassword){
      $error.="<li>Unauthorized password confirmation</li>";
    }
    if(strlen($password)<3){
      $error.="<li>The allowed password character length is 3</li>";
    }
    if(empty($error)){
      /*$status=md5(rand(0,1000));
      $password=md5($password);*/
         //Enkripsi password.
      $status=md5(rand(0,1000));
      $password=password_hash($password,PASSWORD_DEFAULT);
      $emailTitle="Halaman Konfirmasi Pendaftaran";
      $emailContent="Akun yang anda miliki dengan email <b>$email</b> telah siap digunakan.<br/>";
      $emailContent.="Sebelumnya silakan melakukan aktifasi email di link di bawah ini:<br/>";
      $emailContent.=baseUrl()."/verification.php?email=$email&id=$status";
      sendEmail($email,$member,$emailTitle,$emailContent);
      $sqliInsert="insert into members(member,email,password,status) values('$member','$email','$password','$status')";
      $sqlQuery=mysqli_query($connection,$sqliInsert);
      if($sqlQuery){
        $_SESSION["statusUniqid"]=$status;
        $success="The process is successful, please check your email for verfication";
      }
    }
  }
?>


<head>
  <title>REGISTER MEMBERS</title>
</head>
<?php
  include_once("../../views/header.php");
?>
<div class="wrapper">
    <?php if($success){ ?>
      <div class="alert alert-success" role="alert">
        <?= $success ?>
      </div>
  <?php } else if($error){ ?>
      <div class="alert alert-danger" role="alert">
          <ul><?= $error ?></ul>
      </div>
  <?php } ?>
  
  
  <h1 class="mb-1">Welcome to register members</h1>
  <p class="mb-3">
    <a href="/members/login/login.php">Enter our account</a>
  </p>
  <form action="" method="post">
    <div class="mb-3">
      <label for="member" class="form-label">Full Name</label>
      <input type="text" name="member" class="form-control" id="member" value="<?php echo $member; ?>">
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email Address</label>
      <input type="email" name="email" class="form-control" id="email" value="<?php echo $email; ?>">
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Your Password</label>
      <input type="password" name="password" class="form-control" id="password">
    </div>
    <div class="mb-3">
      <label for="confirmPassword" class="form-label">Confirm Password</label>
      <input type="password" name="confirmPassword" class="form-control" id="confirmPassword">
    </div>
    <button type="submit" class="btn btn-primary btn-lg" name="submit" style="display:block;">SEND</button>
  </form>
</div>
<?php
  include_once("../../views/footer.php");
?>