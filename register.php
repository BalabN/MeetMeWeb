<?php 
session_start(); 

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	require_once 'include/DB_Connect.php';
		$db1 = new DB_Connect();
		$con=$db1->connect();
		
	$_SESSION['UserName']=mysqli_real_escape_string($con,$_POST['user']);
	$_SESSION['FirstName']=mysqli_real_escape_string($con,$_POST['fname']);  
	$_SESSION['LastName']=mysqli_real_escape_string($con,$_POST['lname']); 
	$_SESSION['Email']=mysqli_real_escape_string($con,$_POST['email']); 

	require_once 'include/DB_Functions.php';
	$db = new DB_Functions();

	$password = mysqli_real_escape_string($con,$_POST['pass']);

			
	$signup=$db->SignUp($password);

	if($_SESSION['sucess']=="Your registration is completed!")
	{
		$_SESSION['FirstName']="";  
		$_SESSION['LastName']=""; 
		$_SESSION['Email']=""; 
		// preusmeritev na login!
		while (ob_get_status()) 
		{
   			ob_end_clean();
		}
		header( "Location: login.php" );
		}
	}
else
{
	$_SESSION['UserName']='';  
	$_SESSION['FirstName']=''; 
	$_SESSION['LastName']='';  
	$_SESSION['Email']='';  

	$_SESSION['Email-error']='';
	$_SESSION['Username-error']='';
	$_SESSION['password-error']='';
	$_SESSION['FirstName-error']='';
	$_SESSION['LastName-error']='';
	$_SESSION['sucess']='';
}

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
    <form  method="POST" action="register.php">
      <div class="grid_12">
        <div class="grid_3 omega">&nbsp;</div>
        <div class="grid_3 input_text">first name:</div>
        <input class="grid_3" type="text" name="fname" value="<?= $_SESSION['FirstName']; ?>">
        </input>
      </div>
      <div class="grid_12">
        <div class="grid_3 omega">&nbsp;</div>
        <div class="grid_3 input_text">last name:</div>
        <input class="grid_3" type="text" name="lname" value="<?= $_SESSION['LastName']; ?>">
        </input>
      </div>
      <div class="grid_12">
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
        <div class="grid_3 omega">&nbsp;</div>
        <div class="grid_3 input_text">e-mail:</div>
        <input class="grid_3" type="text" name="email" value="<?= $_SESSION['Email']; ?>">
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
      <button type="submit" value="login" name="submit" class="grid_4" id="btn_singup"></button>
    </form>
  </div>
</div>
</div>
</body>
</html>