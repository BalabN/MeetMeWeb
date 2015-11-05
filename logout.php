<?php
if(empty($_SESSION['LogedIn'])){
	$_SESSION['LogedIn']=true;
} 

   require_once 'include/DB_Functions.php';
   $db = new DB_Functions();
   $db->Logout();
?>