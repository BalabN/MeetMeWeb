<?php
session_start();

if(empty($_SESSION['LogedIn'])){
	$_SESSION['LogedIn']=false;
} 

if($_SERVER['REQUEST_METHOD'] == 'POST')
	{	
		require_once 'include/DB_Connect.php';
			$db1 = new DB_Connect();
			$con=$db1->connect();
			
		
		$_SESSION['UserName']=mysqli_real_escape_string($con,$_POST['user']);
		$password = mysqli_real_escape_string($con,$_POST['pass']);
		
		require_once 'include/DB_Functions.php';
		$db = new DB_Functions();
		
		$login=$db->Login($password);
		
		if($login)
			{ 
				header("Location: index.php");
				$con->close();	
				
			} 
	}
else
	{
		$_SESSION['UserName']='';
		$_SESSION['Username-error']='';
		$_SESSION['password-error']='';
		$_SESSION['sucess']='';
		$_SESSION['LogedIn']=false;
	}
	$password="";
 ?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>MeetMe</title>
<link href="styles/style.css" rel="stylesheet" type="text/css" media="screen">
</head>

<body>
<div class="container_16">
  <div class="grid_2">&nbsp;</div>
  <div class="grid_12 bg_orange">
    <header class="grid_12"> <img src="assets/LoginLogo.png" width="680" height="325" alt="Meet Me"/> </header>
    <form  method="POST" action="login.php">
      <div class="grid_12" id="loginFormMargin">
        <div class="grid_3 omega">&nbsp;</div>
        <div class="grid_3 input_text">username:</div>
        <input class="grid_3" type="text" name="user" value="<?= $_SESSION['UserName']; ?>">
        </input>
      </div>
      <div class="grid_12">
        <div class="grid_3 omega">&nbsp;</div>
        <div class="grid_3 input_text">password:</div>
        <input class="grid_3" type="password" name="pass">
        </input>
      </div>
      <div class="grid_12">
        <div class="grid_4 omega">&nbsp;</div>
        <div class="grid_5 singup_text">
          <?= $_SESSION['sucess']; ?>
        </div>
      </div>
      <div class="grid_12">
      <div class="grid_4 omega">&nbsp;</div>
      <button type="submit" value="login" name="submit" class="grid_4" id="btn_login"></button>
    </form>
  </div>
  <div class="grid_12">
    <div class="grid_5 omega">&nbsp;</div>
    <div class="grid_2 singup_text"><a href="register.php">sign up now</a></div>
  </div>
  <div class="grid_12 alpha">
      <div class="grid_2">&nbsp;</div>
      <iframe  class="grid_8 video" width="580" height="270" src="https://www.youtube.com/embed/rV9zZafzlqc" frameborder="0" allowfullscreen> </iframe>
    </div>
</div>
</div>
</body>
</html>
