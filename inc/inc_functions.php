<?php
  require_once("inc_connect.php");
  
    //Fungsi mengambil data.
  function query($query){
    global $connection;
    $result=mysqli_query($connection,$query);
    $rows=[];
    while($row=mysqli_fetch_assoc($result)){
      $rows[]=$row;
    }
    return $rows;
  }
        //Fungsi mencari data.
  function contactSearch($keyword){
    $query="SELECT * FROM contacts WHERE
      name LIKE '%$keyword%' OR
      email LIKE '%$keyword%' OR
      content LIKE '%$keyword%'
    ";
    return query($query);
  }
  function partnerSearch($keyword){
    $query="SELECT * FROM partners WHERE
      partner LIKE '%$keyword%' OR
      email LIKE '%$keyword%'
    ";
    return query($query);
  }
    function memberSearch($keyword){
    $query="SELECT * FROM members WHERE
      member LIKE '%$keyword%' OR
      email LIKE '%$keyword%'
    ";
    return query($query);
  }
  
  
  function baseUrl(){
    /*$baseUrl="http://".$_SERVER["SERVER_NAME"].dirname($_SERVER["SCRIPT_NAME"]);
    return $baseUrl;*/
    $url_dasar  = "http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']);
    return $url_dasar;
  }
  function getId(){
    $id="";
    if(isset($_SERVER["PATH_INFO"])){
      $id=dirname($_SERVER["PATH_INFO"]);
      $id=preg_replace("/[^0-9]/","",$id);
    }
    return $id;
  }
  function setContent($content){
    $content=str_replace("image/",baseUrl()."/image/",$content);
    return $content;
  }
  function clearName($title){
    $newTitle=strtolower($title);
    $newTitle=preg_replace("/[^a-zA-Z0-9\s]/","",$newTitle);
    $newTitle=str_replace(" ","-",$newTitle);
    return $newTitle;
  }
  
  
  function partnerImage($id){
    global $connection;
    $sqliWhere="select * from partners where id='$id'";
    $sqlQuery=mysqli_query($connection,$sqliWhere);
    $sqlArr=mysqli_fetch_array($sqlQuery);
    $image=$sqlArr["image"];
    
    if($image){
      return "image/".$image;
    }else{
      return "image/default-image/partner.png";
    }
  }
  function partnerLink($id){
    global $connection;
    $sqliWherePartner="select * from partners where id='$id'";
    $sqlQuery=mysqli_query($connection,$sqliWherePartner);
    $sqlArr=mysqli_fetch_array($sqlQuery);
    $partner=clearName($sqlArr["partner"]);
    return "home-partner.php/$id/$partner";
    
    echo($id == $id);
  }
  
  
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;
  function sendEmail($email,$member,$emailTitle,$emailContent){
    $emailMember="masucupkicup@gmail.com";
    $memberSend="noreply";
      //Load Composer's autoloader
    //require getcwd().'/vendor/autoload.php';
    require "../../vendor/autoload.php";
      //Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);
    try {
            //Server settings
        $mail->SMTPDebug = 0;//Enable verbose debug output
        $mail->isSMTP();//Send using SMTP
        $mail->Host       = 'smtp.gmail.com';//Set the SMTP server to send through
        $mail->SMTPAuth   = true;//Enable SMTP authentication
        $mail->Username   = $emailMember;//SMTP username
        $mail->Password   = 'zwqhvpwdshspkzwk';//SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;//Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;//TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
          //Recipients
        $mail->setFrom($emailMember,$memberSend);
        $mail->addAddress($email,$member);//Add a recipient
          //Content
        $mail->isHTML(true);//Set email format to HTML
        $mail->Subject = $emailTitle;
        $mail->Body    = $emailContent;
        $mail->send();
        return "Success";
    } catch (Exception $e) {
        return "Error: {$mail->ErrorInfo}";
    }
}
?>