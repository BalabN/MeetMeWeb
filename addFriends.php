<?php
session_start();

$friendRequest = 0;

if(empty($_SESSION['LogedIn'])){
	$_SESSION['LogedIn']=false;
} 

   if((!$_SESSION['LogedIn'])) {
	   header("Location: login.php");
   };
   
 require_once 'include/DB_connect.php';
 $db = new DB_connect();
 $con = $db->connect();
 
 # Get userID (Change this with SESSION variable)
		$username = $_SESSION['UserName'];
		$resUserID = mysqli_query($con, "SELECT `unique_id` FROM `users` WHERE `username` = '$username'");
		$no_of_rows = mysqli_num_rows($resUserID);
		if($no_of_rows > 0){
			$resUserID = mysqli_fetch_array($resUserID);
			$userID = $resUserID['unique_id'];
			}else{
				echo("Cannot find user");
				}

 # Check for pending requests and get username if any
 $checkForRequestsQuery = mysqli_query($con, "SELECT `id_user2` FROM `friendship` WHERE (`id_user1` = '$userID') AND (`status` = '0' AND `isSender` = '0')");
 $no_of_rows = mysqli_num_rows($checkForRequestsQuery);
		if($no_of_rows > 0){
			$requestList = "";
			$loopInt = 0;
			while($checkForRequestsResult = mysqli_fetch_array($checkForRequestsQuery)){
				$loopInt = $loopInt + 1;
				$senderID = $checkForRequestsResult['id_user2'];
				$resSenderID = mysqli_query($con, "SELECT `username` FROM `users` WHERE `unique_id` = '$senderID'");
				$no_of_rows = mysqli_num_rows($resSenderID);
				if($no_of_rows > 0){
					$resSenderID = mysqli_fetch_array($resSenderID);
					$senderName = $resSenderID['username'];
					$requestList .= '<div class="grid_10"><div id="usernameRequest' . $loopInt .'" class="grid_4 alpha">' . $senderName . '</div><button type="submit" onClick="responseRequest(this.id)" id="acceptFriendRequest' . $loopInt .'" class="grid_2">Accept</button><button type="submit" onClick="responseRequest(this.id)" id="rejectFriendRequest' . $loopInt .'" class="grid_2">Reject</button><div class="grid_1 omega">&nbsp;</div></div>';
					}else{
						echo("Cannot find sender");
						}
				}
			}else {
				$requestList = "<div class='grid_10'>No Friend Requests</div>";
				}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>MeetMe - Home</title>
<link href="styles/style.css" rel="stylesheet" type="text/css" media="screen">
<script src="https://maps.googleapis.com/maps/api/js"></script>
<script src="js/gMapsScript.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="js/friendRequestsHandler.js"></script>
<script src="js/autocomplete.js"></script>
</head>

<body>
<div id="mainBody">
<div class="container_16">
  <header class="indexHeader">
    <div class="grid_4 alpha headerLogo"><a href="index.php" name="MeetMe"><img src="assets/indexLogo.png" width="230" height="73" alt=""/></a></div>
    <div class="grid_4 searchBar"> &nbsp; </div>
    <nav class="grid_8 omega">
      <ul>
		<li><a href="myMeets.php">my meets</a></li>
        <li><a href="addFriends.php"><img src="assets/addFriendIcon.png" width="25" height="25" alt=""/></a></li>
        <li><a href="logout.php"><img src="assets/iconSettings.png" width="22" height="22" alt=""/></a></li>
      </ul>
    </nav>
  </header>
  <div id="results"> </div>
  <div class="grid_6 img_container"><img src="assets/imgSaxPub.png" width="340" height="210" alt=""/></div>
  <div class="grid_10 gMaps" id="map-canvas"></div>
  <div class="grid_6 dashboard">
    <h1 class="grid_4 alpha">new meet:</h1>
    <div class="grid_2 omega">&nbsp;</div>
    <div class="grid_6 alpha">
    <form method="POST" name="eventForm" action="createMeet.php" onsubmit="return validateForm()">
      <input type="text" name="eventPlace">
      </input>
      </div>
      <div class="grid_2 alpha">
        <p>place</p>
      </div>
      <div class="grid_4 omega">&nbsp;</div>
      <div class="grid_6 alpha">
        <input type="date" name="eventDate">
        </input>
      </div>
      <div class="grid_2 alpha">
        <p>date</p>
      </div>
      <div class="grid_4 omega">&nbsp;</div>
      <div class="grid_6 alpha">
        <input type="time" name="eventTime">
        </input>
      </div>
      <div class="grid_2 alpha">
        <p>time</p>
      </div>
      <div class="grid_4 omega">&nbsp;</div>
      <div class="grid_6">
        <button type="submit" value="createEvent" name="submit"></button>
      </div>
    </form>
  </div>
  <div class="grid_10 eventShowing omega">
  <h1 class="grid_10">Add your best friends: </h1>
    <div id="addFrResults"></div>
    <div class="grid_4 searchBar">
      <input type="text" value="" name="username" placeholder="Search" id="keyinput" autocomplete="off">
    </div>
    <button type="submit" onClick="" name="sendRequest" id="sendReq" class="grid_2">Add friend</button>
    <div class="grid_4 omega">&nbsp;</div>
    <div class="grid_10" id="requestStatus"></div>
    <?php echo($requestList); ?>
  </div>
</div>
<footer>Nikola Balaban 2015</footer>
</div>
<script>
function validateForm() {
		var place = document.forms["eventForm"]["eventPlace"].value;
		var date = document.forms["eventForm"]["eventDate"].value;
		var time = document.forms["eventForm"]["eventTime"].value;
		
		if(place == null || place == "" || date == null || date == "" || time == null || time == ""){
			alert("You must fill all the information");
			return false;
			}
		}
</script>
</body>
</html>
