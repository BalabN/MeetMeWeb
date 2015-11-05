<?php
session_start();

	require_once 'include/DB_connect.php';
	$db = new DB_connect();
   	$con = $db->connect();

	# Accept/reject friend request
	if(isset($_POST['friendUsername']) === true && empty($_POST['friendUsername']) === false && isset($_POST['status']) === true && empty($_POST['status']) === false){
		$senderUsername = $_POST['friendUsername'];
		$status = $_POST['status'];
		# Get senderID 
		$username = $_SESSION['UserName'];
		$resSenderID = mysqli_query($con, "SELECT `unique_id` FROM `users` WHERE `username` = '$senderUsername'");
		$no_of_rows = mysqli_num_rows($resSenderID);
		if($no_of_rows > 0){
			$resSenderID = mysqli_fetch_array($resSenderID);
			$senderID = $resSenderID['unique_id'];
			
			# Get reciver ID
			$resUsernameID = mysqli_query($con, "SELECT `unique_id` FROM `users` WHERE `username` = '$username'");
			$no_of_rows = mysqli_num_rows($resUsernameID);
			if($no_of_rows > 0) {
				$resUsernameID = mysqli_fetch_array($resUsernameID);
				$usernameID = $resUsernameID['unique_id'];
				
				# Dodaj v tabelo accept
				$responseQuery = mysqli_query($con, "UPDATE `friendship` SET `status` = '". $status ."' WHERE (`id_user1` = '$senderID' AND `id_user2` = '$usernameID')");
				$responseTwoQuery = mysqli_query($con, "UPDATE `friendship` SET `status` = '". $status ."' WHERE (`id_user1` = '$usernameID' AND `id_user2` = '$senderID')");
				if ($responseQuery && $responseTwoQuery) {
					if($status == 1){
						echo("You and ". $senderUsername ." are now friends!");
						}else {
							echo("Friend request from " . $senderUsername . " rejected!");
							}
					}else{
						echo("Error inserting in table");
						}
				}else {
					echo("Cannot get user!");
					}
			}else{
				echo("Cannot find sender!");
				}
		}
	
	# Send request	
	if(isset($_POST['friendName']) === true && empty($_POST['friendName']) === false){
		
		$recUsername = $_POST['friendName'];
		
		# Get senderID (TODO zamjeni sa getIdByUsername)
		$senderUsername = $_SESSION['UserName'];
		$resSenderID = mysqli_query($con, "SELECT `unique_id` FROM `users` WHERE `username` = '$senderUsername'");
		$no_of_rows = mysqli_num_rows($resSenderID);
		if($no_of_rows > 0){
			$resSenderID = mysqli_fetch_array($resSenderID);
			$senderID = $resSenderID['unique_id'];
			
			# Get reciver ID
			$resRecID = mysqli_query($con, "SELECT `unique_id` FROM `users` WHERE `username` = '$recUsername'");
			$no_of_rows = mysqli_num_rows($resRecID);
			if($no_of_rows > 0) {
				$resRecID = mysqli_fetch_array($resRecID);
				$recID = $resRecID['unique_id'];
				if($recID == $senderID){
					echo("To si ti");
					} else {
						$resFriends = mysqli_query($con, "SELECT `status` FROM `friendship` WHERE ((`id_user1` = '$senderID' AND `id_user2` = '$recID') AND (`status` = '0' OR `status` = '1' OR `status` = '4'))");
						$no_of_rows = mysqli_num_rows($resFriends);
						$resFriends = mysqli_fetch_array($resFriends);
						echo($resFriends['status']);
						if ($no_of_rows > 0){
							if ($resFriends['status'] == "1"){
								echo("You are already friend to: " . $recUsername);
								}else if ($resFriends['status'] == "4"){
									$friendReqQuery = mysqli_query($con, "UPDATE `friendship` SET `status` = '0' WHERE (`id_user1` = '$senderID' AND `id_user2` = '$recID')");
									$friendTwoReqQuery = mysqli_query($con, "UPDATE `friendship` SET `status` = '0' WHERE (`id_user1` = '$recID' AND `id_user2` = '$senderID')");
									if ($friendReqQuery && $friendTwoReqQuery) {
										echo("Request send!");
										}else{
											echo("Error inserting in table friendship");
											}
									}else {
										echo("Pending friend request");
										}
						}else {
							$friendReqQuery = mysqli_query($con, "INSERT INTO `friendship` (`id_user1`,`id_user2`,`status`,`isSender`) VALUES ('$senderID', '$recID', '0', '1')");
							$friendTwoReqQuery = mysqli_query($con, "INSERT INTO `friendship` (`id_user1`,`id_user2`,`status`, `isSender`) VALUES ('$recID', '$senderID', '0', '0')");
							if ($friendReqQuery && $friendTwoReqQuery) {
								echo("Request send!");
								}else{
									echo("Error inserting in table friendship");
									}
							}
						}
				}else {
					echo("Friend does not exist. Please invite your friend");
					}
			}else{
				echo("Error getting senderID");
				}
		mysqli_close($con);
		}
?>