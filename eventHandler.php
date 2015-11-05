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
 
 $username = $_SESSION['UserName'];
 $idEventOwner = $fn->getIdByUsername($username);


if($_SERVER['REQUEST_METHOD'] == 'POST'){
			
			if (empty($_POST["placeName"])){
				$eventPlaceErr = "No place input";
				echo $eventPlaceErr;
			} else {
				$eventPlace = $_POST["placeName"];
				echo $eventPlace."<br />";
			}
			
			if (empty($_POST["placeDate"])){
				$eventDateErr = "No date input";
				echo $eventDateErr;
			} else {
				$eventDate = $_POST["placeDate"];
				echo $eventDate."<br />";
			}
			
			if (empty($_POST["placeTime"])){
				$eventTimeErr = "No time input";
				echo $eventTimeErr;
			} else {
				$eventTime = $_POST["placeTime"];
				echo $eventTime."<br />";
			}
			
			if (!empty($_POST["friend"])){
				$name = $_POST["friend"];
				$n = 0;
				foreach ($name as $color){
				$friendId[$n] = $color;
				$n++;
				}
			} else {
				$eventFriendsErr = "Add to invite friends";
				echo $eventFriendsErr;
			}
	
	# TODO Zapakirat u funkciju createPlace
	$resPlaces = mysqli_query($con, "SELECT `uid` FROM `places` WHERE `name` = '" . $eventPlace."'");
	$numRows = mysqli_num_rows($resPlaces);
	if($numRows > 0){
		echo "Place already in db!";
		} else {
			$insertNewPlace = mysqli_query($con, "INSERT INTO `places`(`name`, `gmaps`, `rating`, `img`) VALUES ('".$eventPlace."', '1','1','')");
			if ($insertNewPlace) {
					echo("Place saved!");
					}else{
						echo("Error inserting in table friendship");
						}
			}
			
			
	# TODO Zapakirat u funkciju createEvent
	$resPlaceId = mysqli_query($con, "SELECT `uid` FROM `places` WHERE `name` = '".$eventPlace."'");
	$numRows = mysqli_num_rows($resPlaceId);
	$i = 0;
	if ($numRows > 0) {
		while($checkForRequestsResult = mysqli_fetch_array($resPlaceId)){
		$idPlace[$i] = $checkForRequestsResult['uid'];
		# Dodaj event	
		$insertNewEvent = mysqli_query($con, "INSERT INTO `events`(`places_uid`, `date`, `time`) VALUES ('".$idPlace[$i]."','".$eventDate."','".$eventTime."')");
		if($insertNewEvent) {
			echo "Event Created!";
			$lastEventId = mysqli_insert_id($con);
			echo $lastEventId;
			
			# userEvent dodaj
			$insertUserEvent = mysqli_query($con, "INSERT INTO `users_has_events`( `users_unique_id`, `events_id_events`, `events_places_uid`, `status`) VALUES ('".$idEventOwner."','".$lastEventId."','".$idPlace[$i]."', '1')") or die($con);
			if ($insertUserEvent) {		
				# Dodaj prijatelje u event sa statusom 0
				foreach($friendId as $friend){
					$sqlInsertFrend = "INSERT INTO `users_has_events`( `users_unique_id`, `events_id_events`, `events_places_uid`, `status`) VALUES ('".$friend."','".$lastEventId."','".$idPlace[$i]."', '0')";
					if (mysqli_query($con, $sqlInsertFrend)){
						echo $friend . " Added!</br>";
						} else {
							echo "Friends not added!";
							}
					}
				echo "Event fully compleated!";
				header("Location: myMeets.php");
				}else {
					echo "UserEvent creation failed!";
					}
		
			} else {
				echo "Event creation error!";
				}
		$i++;
		}
	}
	
}


?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>