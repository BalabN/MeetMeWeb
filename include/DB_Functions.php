<?php

class DB_Functions 
{
    private $db;
    //put your code here
    // constructor
    function __construct() 
	{
        require_once 'DB_Connect.php';
        // connecting to database
        $this->db = new DB_Connect();
        $this->db->connect();
    }
	
    // destructor
    function __destruct() 
	{
		
    }
	
    /**
     * Random string which is sent by mail to reset password
     */
	public function random_string()
	{
		$character_set_array = array();
		$character_set_array[] = array('count' => 7, 'characters' => 'abcdefghijklmnopqrstuvwxyz');
		$character_set_array[] = array('count' => 1, 'characters' => '0123456789');
		$temp_array = array();
		
		foreach ($character_set_array as $character_set) 
		{
			for ($i = 0; $i < $character_set['count']; $i++) 
			{
				$temp_array[] = $character_set['characters'][rand(0, strlen($character_set['characters']) - 1)];
			}
		}
		shuffle($temp_array);
		return implode('', $temp_array);
	}
	
	# Dobi ID iz prijatelja
	public function getUsernameById($id) {
		require_once 'include/DB_Connect.php';
			$db2 = new DB_Connect();
			$con=$db2->connect();
		$resUserUsername = mysqli_query($con, "SELECT `username` FROM `users` WHERE `unique_id` = '$id'");
		$no_of_rows = mysqli_num_rows($resUserUsername);
		if($no_of_rows > 0){
			$resUserUsername = mysqli_fetch_array($resUserUsername);
			$userUsername = $resUserUsername['username'];
			#echo("User Username is " . $userUsername);
			return $userUsername;
			}else{
				echo("Cannot find user!");
				}
		}
	
	# Dobi prijatelja iz ID
	public function getIdByUsername($username) {
		require_once 'include/DB_Connect.php';
			$db2 = new DB_Connect();
			$con=$db2->connect();
		$resUserID = mysqli_query($con, "SELECT `unique_id` FROM `users` WHERE `username` = '$username'");
		$no_of_rows = mysqli_num_rows($resUserID);
		if($no_of_rows > 0){
			$resUserID = mysqli_fetch_array($resUserID);
			$userID = $resUserID['unique_id'];
			#echo("User ID is " . $userID);
			return $userID;
			}else{
				echo("Cannot find user!");
				}
		}
		
	#Dobi ime mjesta od eventId
	public function getPlaceByEventId($eventId) {
		require_once 'include/DB_Connect.php';
			$db2 = new DB_Connect();
			$con=$db2->connect();
		$resEventPlace = mysqli_query($con, "SELECT `places`.`name` FROM `places` LEFT JOIN `meetme`.`events` ON `places`.`uid` = `events`.`places_uid` WHERE (`events`.`id_events` = '$eventId')");
		$no_of_rows = mysqli_num_rows($resEventPlace);
		if($no_of_rows > 0){
			$resEventPlace = mysqli_fetch_array($resEventPlace);
			$eventPlace = $resEventPlace['name'];
			#echo("User ID is " . $userID);
			return $eventPlace;
			}else{
				echo("Cannot find event!");
				}
		}
		
			#Dobi datum od eventId
	public function getDateByEventId($eventId) {
		require_once 'include/DB_Connect.php';
			$db2 = new DB_Connect();
			$con=$db2->connect();
		$resEventPlace = mysqli_query($con, "SELECT `date` FROM `events` WHERE (`events`.`id_events` = '$eventId')");
		$no_of_rows = mysqli_num_rows($resEventPlace);
		if($no_of_rows > 0){
			$resEventPlace = mysqli_fetch_array($resEventPlace);
			$eventPlace = $resEventPlace['date'];
			#echo("User ID is " . $userID);
			return $eventPlace;
			}else{
				echo("Cannot find event!");
				}
		}
		
		#Dobi čas od eventId
	public function getTimeByEventId($eventId) {
		require_once 'include/DB_Connect.php';
			$db2 = new DB_Connect();
			$con=$db2->connect();
		$resEventPlace = mysqli_query($con, "SELECT `time` FROM `events` WHERE (`events`.`id_events` = '$eventId')");
		$no_of_rows = mysqli_num_rows($resEventPlace);
		if($no_of_rows > 0){
			$resEventPlace = mysqli_fetch_array($resEventPlace);
			$eventPlace = $resEventPlace['time'];
			#echo("User ID is " . $userID);
			return $eventPlace;
			}else{
				echo("Cannot find event!");
				}
		}
		
		# Funkcija za dobit vse svoje evente
#--------------------------------------------------------------------------------------------------------------------------------------------	
function getEvents($userId){
	require_once 'include/DB_Connect.php';
	$db = new DB_connect();
 	$con = $db->connect();
	# Handle Arrays
	$sqlGetUserEvents = "SELECT `events_id_events` FROM `users_has_events` WHERE `users_unique_id` =  '".$userId."'";
	$resUserEvents = mysqli_query($con, $sqlGetUserEvents) or die(mysqli_error($con));
	$num_rows = mysqli_num_rows($resUserEvents);
	$j = 0;
	if($num_rows > 0){
		while ($checkUserEvents = mysqli_fetch_array($resUserEvents)) {
			$eventId[] = $checkUserEvents['events_id_events'];
			$sqlGetFriendsAttending = "SELECT `users`.`unique_id` FROM `users` LEFT JOIN `meetme`.`users_has_events` ON `users`.`unique_id` = `users_has_events`.`users_unique_id` WHERE (`users_has_events`.`events_id_events` = '".$eventId[$j]."' AND (`status` = '0' OR `status` = '1'))";
			$resFriendsAttending = mysqli_query($con, $sqlGetFriendsAttending);
			$num_rows = mysqli_num_rows($resFriendsAttending);
			if($num_rows > 0){
				while ($checkFriendsAttending = mysqli_fetch_array($resFriendsAttending)) {
					$events[$eventId[$j]][] = $checkFriendsAttending['unique_id'];
					}
				}
			$j++;
			}
		} else {
			echo "No events found!";
			$events = "";
			}
			return $events;
	}
		
		# Prikazi evente
function drawEvents($events, $userId){
	require_once 'include/DB_Connect.php';
	$db = new DB_connect();
 	$con = $db->connect();
	# Handle Arrays
	$sqlGetUserEvents = "SELECT `events_id_events` FROM `users_has_events` WHERE `users_unique_id` =  '".$userId."'";
	$resUserEvents = mysqli_query($con, $sqlGetUserEvents) or die(mysqli_error($con));
	$num_rows = mysqli_num_rows($resUserEvents);
	if($num_rows > 0){
		while ($checkUserEvents = mysqli_fetch_array($resUserEvents)) {
			$eventId[] = $checkUserEvents['events_id_events'];
		}
		$i = 0;
		foreach($events as $e){
			$eventPlace[$i] = $this->getPlaceByEventId($eventId[$i]);
			$eventDate[$i] = $this->getDateByEventId($eventId[$i]);
			$eventTime[$i] = $this->getTimeByEventId($eventId[$i]);
			echo "<div class='grid_10' id='shownEventWrapper'>$eventPlace[$i] $eventDate[$i] at $eventTime[$i]";
			foreach($e as $f){
				if($f != $userId){
					echo "<li>".$this->getUsernameById($f)."</li>";
					}
				}
			echo "</div>";
			$i++;
			}
		}
	}

	
	/**
     * login function
     */
	public function Login($password)
	{ 
		$_SESSION['sucess']='';
		
		if(!empty($_SESSION['UserName']) and !empty($password))
		{
			$_SESSION['password-error']="";
			
			$login = $this->getUserByUsernameAndPassword($_SESSION['UserName'],$password);
			
			if($login)
			{ 
				$_SESSION['sucess']="login succesful!";
				$_SESSION['LogedIn']=true;
				return true;					
			} 
			else
			{
			$_SESSION['sucess']="Wrong username or password!";
	        return false;
			}
		}
		else
		{
			$_SESSION['LogedIn']=false;
			$_SESSION['sucess']="Enter username/password";
		}
	}
	
	/**
     * Verifies if loged in 
     */
	public function IsLogedIn()
	{
		if ($_SESSION['LogedIn']==false) 
		{
		header("Location: login.php");
		return false;
		}
		else
		{
		return true;
		}
	}
	
	/**
     * logout
     */
	public function Logout()
	{
		if ($_SESSION['LogedIn']==true) 
		{
		$_SESSION['LogedIn']==false;
		session_destroy();
		header("Location: login.php");
		return true;

		}
	}
	
	/**
     * SignUp
     */
	public function SignUp($password) 
	{ 
		$_SESSION['sucess']='';
		
		if(!empty($_SESSION['UserName']) and !empty($_SESSION['Email']) and !empty($_SESSION['FirstName']) and !empty($_SESSION['LastName']) and !empty($password))
		{
		$_SESSION['FirstName-error']='';
		$_SESSION['LastName-error']='';
		
		$check=$this->CheckForm($password);
		
		
		if($check==0) // save user!
			{
			$newuser=$this->newuser($password);
			}
		}

	
		else
		{
			
				$_SESSION['sucess']="You must fill out the form!";
				return false;
		}
	} 
	 /**
     * NewUser
     */
	 public function newuser($password)
	{ 
		$user =$this->storeUser($_SESSION['FirstName'], $_SESSION['LastName'], $_SESSION['Email'], $_SESSION['UserName'], $password);

		if($user)
			{ 
			$_SESSION['sucess']="Your registration is completed!";
			} 
		else
			{
			$_SESSION['sucess']="Fail!";
			}
		
		
	}
	
	/**
     * Adding new user to mysql database
     * returns user details
     */
    public function storeUser($fname, $lname, $email, $uname, $password) {
        require_once 'include/DB_Connect.php';
			$db2 = new DB_Connect();
			$con=$db2->connect();
			
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
        $result = mysqli_query($con,"INSERT INTO users(firstname, lastname, email, username, encrypted_password, salt) VALUES('$fname', '$lname', '$email', '$uname', '$encrypted_password', '$salt')");
        // check for successful store
        if ($result) {
            return true;
        } else {
            return false;
        }
		mysqli_close($con);
    }
	
    /**
     * Verifies user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {
		require_once 'include/DB_Connect.php';
			$db2 = new DB_Connect();
			$con=$db2->connect();
			
        $result = mysqli_query($con,"SELECT * FROM users WHERE email = '$email'") or die(mysql_error());/////////////////////////////////?
        // check for result
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            $salt = $result['salt'];
            $encrypted_password = $result['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $result;
            }
        } else {
            // user not found
            return false;
        }
		mysqli_close($con);
    }
	 /**
     * Verifies user by username and password
     */
    public function getUserByUsernameAndPassword($username, $password) 
	{
		require_once 'include/DB_Connect.php';
			$db2 = new DB_Connect();
			$con=$db2->connect();
		
        $result = mysqli_query($con,"SELECT * FROM users WHERE username = '$username'") or die(mysql_error()); // mysqli_error ??
        // check for result
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) 
		{
            $result = mysqli_fetch_array($result);
            $salt = $result['salt'];
            $encrypted_password = $result['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) 
			{
                // user authentication details are correct
                $_SESSION['sucess']="login sucess";
				return true;
            }
			else
			{	
			$_SESSION['sucess']="username or password incorrect";
			return false;
			}
        } 
		else 
		{
            // user not found
			$_SESSION['sucess']="username or password incorrect";
            return false;
        }
		//close connection to sql
		mysqli_close($con);
    }
	
	/**
     * Checks whether the email is valid or fake
     */
	public function validEmail($email)
	{
	   $isValid = true;
	   $atIndex = strrpos($email, "@");
	   if (is_bool($atIndex) && !$atIndex)
	   {
		  $isValid = false;
	   }
	   else
	   {
		  $domain = substr($email, $atIndex+1);
		  $local = substr($email, 0, $atIndex);
		  $localLen = strlen($local);
		  $domainLen = strlen($domain);
		  if ($localLen < 1 || $localLen > 64)
		  {
			 // local part length exceeded
			 $isValid = false;
		  }
		  else if ($domainLen < 1 || $domainLen > 255)
		  {
			 // domain part length exceeded
			 $isValid = false;
		  }
		  else if ($local[0] == '.' || $local[$localLen-1] == '.')
		  {
			 // local part starts or ends with '.'
			 $isValid = false;
		  }
		  else if (preg_match('/\.\./', $local))
		  {
			 // local part has two consecutive dots
			 $isValid = false;
		  }
		  else if (!preg_match('/^[A-Za-z0-9\-\.]+$/', $domain))
		  {
			 // character not valid in domain part
			 $isValid = false;
		  }
		  else if (preg_match('/\.\./', $domain))
		  {
			 // domain part has two consecutive dots
			 $isValid = false;
		  }
		  else if
	(!preg_match('/^(\\.|[A-Za-z0-9!#%&`_=\/$*+?^{}|~.-])+$/',
					 str_replace("\\","",$local)))
		  {
			 // character not valid in local part unless
			 // local part is quoted
			 if (!preg_match('/^"(\\"|[^"])+"$/',
				 str_replace("\\","",$local)))
			 {
				$isValid = false;
			 }
		  }
		  if ($isValid && !(checkdnsrr($domain,"MX") ||
	 ↪checkdnsrr($domain,"A")))
		  {
			 // domain not found in DNS
			 $isValid = false;
		  }
	   }
	   return $isValid;
	}
	
	/**
     * Check user-email is existed or not
     */
    public function isUserExisted($email) 
		{
		require_once 'include/DB_Connect.php';
			$db1 = new DB_Connect();
			$con=$db1->connect();
	
        $result = mysqli_query($con,"SELECT email from users WHERE email = '$email'");
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) 
			{
            // user existed
            return true;
			} 
		else 
			{
			// user not existed
			return false;
			}
		mysqli_close($con);
        }
    
	/**
     * Check username is existed or not
     */
	public function isUsernameTaken($username) 
		{
		require_once 'include/DB_Connect.php';
			$db1 = new DB_Connect();
			$con=$db1->connect();
			
        $result = mysqli_query($con,"SELECT email from users WHERE username = '$username'");
		$no_of_rows = mysqli_num_rows($result);
		if ($no_of_rows > 0)
			{
			// user existed
			return true;
			}
		else
			{
			// user not existed
			return false;
			}
		mysqli_close($con);	
        }
    /**
     * Check what is missing in the form(not working yet!)
     */
    public function Unfilled($password) 
		{
		
        if(empty($_SESSION['UserName']))
				{
				$_SESSION['Username-error']="<font color='red'>X</font> (missing)";
				}
			else
				{
				$_SESSION['Username-error']="";
				}
				
			if(empty($_SESSION['Email']))
				{
				$_SESSION['Email-error']="<font color='red'>X</font> (missing)";
				}
			else
				{
				$_SESSION['Email-error']="";
				}
			
			if(empty($_SESSION['FirstName']))
				{
				$_SESSION['FirstName-error']="<font color='red'>X</font> (missing)";
				}
			else
				{
				$_SESSION['FirstName-error']="";
				}
			
			if(empty($_SESSION['LastName']))
				{
				$_SESSION['LastName-error']="<font color='red'>X</font> (missing)";
				}
			else
				{
				$_SESSION['LastName-error']="";
				}
				
			if(empty($password))
				{
				$_SESSION['password-error']="<font color='red'>X</font> (missing)";
				}
			else if (strlen ($password)<6)//long enough password?
				{
				$_SESSION['password-error']="<font color='red'>X</font> (minimal length is 6)";
				}
			else
				{
				$_SESSION['password-error']="";
				}
			
			if(empty($_SESSION['UserName']) and empty($_SESSION['Email']) and empty($_SESSION['FirstName']) and empty($_SESSION['LastName']))
				{
				return true;
				}		
        }
		
	/**
    * Checking
    * the Form
    */

	public function CheckForm($password) 
		{
		require_once 'include/DB_Functions.php';
		$db = new DB_Functions();
		
		$error=0;
		
		if ( trim($_SESSION['UserName']) == "" )//username not blank?
			{
			$_SESSION['UserName']='';
			$_SESSION['sucess']="Please fill in your username";
			$error=$error+1;//štetje errorjev
			}
		else if($db->isUsernameTaken($_SESSION['UserName'])) //username already taken ?
			{
			$_SESSION['UserName']='';
			$_SESSION['sucess']="Username taken";
			$error=$error+1;//štetje errorjev
			}	
		else	
			{
			$_SESSION['sucess']="";
			}
		
		if ( trim($_SESSION['FirstName']) == "" )//FirstName not blank?
			{
			$_SESSION['FirstName']='';
			$_SESSION['sucess']="Please fill in your first name";
			$error=$error+1;//štetje errorjev
			}	
		else
			{
			$_SESSION['sucess']='';
			}
		
		if ( trim($_SESSION['LastName']) == "" )//LastName not blank?
			{
			$_SESSION['LastName']='';
			$_SESSION['sucess']="Please fill in your last name";
			$error=$error+1;//štetje errorjev
			}	
		else
			{
			$_SESSION['LastName-error']='';
			}

		if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$",$_SESSION['Email']))// correct form of email?
			{
			$_SESSION['Email']='';
			$_SESSION['sucess']="Please enter valid email";
			$error=$error+1;//štetje errorjev
			}
		else if($db->isUserExisted($_SESSION['Email'])) //email already in use ?
			{
			$_SESSION['Email']='';
			$_SESSION['sucess']="Email already exists";
			$error=$error+1;//štetje errorjev
			}
		else
			{
			$_SESSION['Email-error']="";
			}	

		if (strlen ($password)<6)//long enough password?
			{
			$_SESSION['sucess']="Password must be at least 6 characters long";
			$error=$error+1;//štetje errorjev
			}
		return $error;	
		}	
    /**
     * Encrypting password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
    /**
     * Decrypting password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;
    }
}
?>