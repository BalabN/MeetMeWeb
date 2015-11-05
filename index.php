<?php
session_start();

if(empty($_SESSION['LogedIn'])){
	$_SESSION['LogedIn']=false;
} 

   if((!$_SESSION['LogedIn'])) {
	   header("Location: login.php");
   };
   
 require_once 'include/DB_connect.php';
 require_once 'include/DB_Functions.php';
 $fn = new DB_Functions();
 $db = new DB_connect();
 $con = $db->connect();
 
 # Random place contetnt
 $resPlaceQuery = mysqli_query($con, "SELECT uid, img, name FROM places");
 $no_of_rows = mysqli_num_rows($resPlaceQuery);
		if($no_of_rows > 0){
			while($checkForRequestsResult = mysqli_fetch_array($resPlaceQuery)){
			$idPlace[] = $checkForRequestsResult['uid'];
			$imgPlace[] = $checkForRequestsResult['img'];
			$namePlace[] = $checkForRequestsResult['name'];
			}
		$randImgIndex = array_rand($imgPlace);
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
      <input type="text" value="" placeholder="Search" id="keyword" autocomplete="off">
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
  <div class="grid_6 img_container"><img src="<?php echo($imgPlace[$randImgIndex]); ?>" width="340" height="210" alt=""/></div>
  <div class="grid_10 gMaps" id="map-canvas"></div>
  <div class="grid_6 dashboard">
    <h1 class="grid_4 alpha">new meet:</h1>
    <div class="grid_2 omega">&nbsp;</div>
    <div class="grid_6 alpha">
    <form method="POST" action="createMeet.php" name="eventForm" onsubmit="return validateForm()">
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
    <h1 class="grid_10"><?php echo($namePlace[$randImgIndex]); ?></h1>
    <div id="r1" class="rate_widget grid_5">
      <div class="star_1 ratings_stars grid_1 alpha"></div>
      <div class="star_2 ratings_stars grid_1"></div>
      <div class="star_3 ratings_stars grid_1"></div>
      <div class="star_4 ratings_stars grid_1"></div>
      <div class="star_5 ratings_stars grid_1 omega"></div>
    </div>
    <div class="grid_5 omega">&nbsp;</div>
    <div class="grid_10">
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam felis libero, eleifend nec semper consequat, varius nec nisi. Maecenas non turpis justo. Sed erat lacus, feugiat et quam in, pellentesque imperdiet elit. Donec id velit at tortor pharetra lacinia. Fusce sem dolor, cursus a condimentum nčćžđšon, cursus scelerisque lorem. Fusce at mi id turpis mattis cursus in sed lorem. Nunc lobortis nibh sit amet metus tincidunt commodo. Sed blandit mauris ut ante condimentum, quis auctor nulla porta. Aliquam non turpis efficitur, volutpat ex vitae, pulvinar arcu.</p>
    </div>
  </div>
</div>
</div>
<footer>Nikola Balaban 2015</footer>
<script>var placeID = <?php echo json_encode($idPlace[$randImgIndex]); ?>;
	
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
<script src="js/starRating.js"></script>
</body>
</html>