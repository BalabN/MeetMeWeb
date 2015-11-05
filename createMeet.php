<?php
session_start();

#If user is loged in
if(empty($_SESSION['LogedIn'])){
	$_SESSION['LogedIn']=false;
} 

   if((!$_SESSION['LogedIn'])) {
	   header("Location: login.php");
   };
   
   	require_once 'include/DB_Functions.php';
		$fn = new DB_Functions();
	
	require_once 'include/DB_Connect.php';
		$db1 = new DB_Connect();
		$con=$db1->connect();

	# TODO funkcija za dobit prijatelje ovo
	$showFriend = "";
	$username = $_SESSION['UserName'];
	$id = $fn->getIdByUsername($username);
	$resFriends = mysqli_query($con, "SELECT f.id_user2, u.username FROM users u LEFT JOIN  friendship f ON u.unique_id = f.id_user1 WHERE f.id_user1 = '$id' AND f.status = '1'");
	$numRows = mysqli_num_rows($resFriends);
	$i = 0;
	if ($numRows>0) {
		while ($checkForReqRes = mysqli_fetch_array($resFriends)){
			$friendId[] = $checkForReqRes['id_user2'];
			$friendUsername[] = $fn->getUsernameById($checkForReqRes['id_user2']);
			$showFriend .= '<p><input type="checkbox" name="friend[]" id="friend" value="'.$friendId[$i].'">'. $friendUsername[$i].'</input></p>';
			$i = $i + 1 ;
			}
		} else {
			$showFriend = ("You have no friends. Add them <a href='addFriends.php'>here</a>!");	
		}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
			
			if (empty($_POST["eventPlace"])){
				$eventPlace = "";
			} else {
				$eventPlace = $_POST["eventPlace"];
			}
			
			if (empty($_POST["eventDate"])){
				$eventDate = "";
			} else {
				$eventDate = $_POST["eventDate"];
			}
			
			if (empty($_POST["eventTime"])){
				$eventTime = "";
			} else {
				$eventTime = $_POST["eventTime"];
			}
			
			
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
<script src="js/autocomplete.js"></script>
</head>

<body>
<div id="mainBody">
<div class="container_16">
  <header class="indexHeader">
    <div class="grid_4 alpha headerLogo"><a href="index.php" name="MeetMe"><img src="assets/indexLogo.png" width="230" height="73" alt=""/></a></div>
    <div class="grid_4 searchBar">
      <input type="text" value="" placeholder="Search" id="keyword">
    </div>
    <nav class="grid_8 omega">
      <ul>
        <li><a href="myMeets.php">my meets</a></li>
        <li><a href="addFriends.php"><img src="assets/addFriendIcon.png" width="25" height="25" alt=""/></a></li>
        <li><a href="logout.php"><img src="assets/iconSettings.png" width="22" height="22" alt=""/></a></li>
      </ul>
    </nav>
  </header>
  <div id="results"> </div>
  <div class="grid_6 img_container"><!-- Put picture from place choosen --></div>
  <div class="grid_10 gMaps" id="map-canvas"></div>
  <div class="grid_6 overview">
<!-- get input from dashboard -->
	<p><?php echo($eventPlace); ?></p>
    <p><?php echo($eventDate); ?></p>
    <p><?php echo($eventTime); ?></p>
  </div>
  <div class="grid_10 addFriendsEvent omega">
<!-- show all friends and add them -->
	<form method="POST" name="eventForm" action="eventHandler.php" onsubmit="return validateForm()">
    	<input type="hidden" name="placeName" value="<?php echo ($eventPlace); ?>" >
        <input type="hidden" name="placeDate" value="<?php echo ($eventDate); ?>" >
        <input type="hidden" name="placeTime" value="<?php echo ($eventTime); ?>" >
	<?php echo $showFriend; ?>
    <button type="submit" onClick="checkForm();">Add Friends</button>
    </form>
  </div>
</div>
<footer>Nikola Balaban 2015</footer>
</div>
<script src="js/starRating.js"></script>
<script>
function validateForm() {
		var place = document.forms["eventForm"]["placeName"].value;
		var date = document.forms["eventForm"]["placeDate"].value;
		var time = document.forms["eventForm"]["placeTime"].value;
		var friend = document.forms["eventForm"]["friend"].value;
		
		/*if(place == null || place == "" || date == null || date == "" || time == null || time == "" || friend == null || friend == ""){
			alert("You must fill all the information");
			return false;
			}
		}*/
</script>
</body>
</html>