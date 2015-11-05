<?php

session_start();
if($_POST['out_data']){
    $data = json_decode($_POST['out_data'], true);
	$rating = new ratings($data['widget_id']);
	if ($data['fetch'] == 1){
	$rating->get_ratings($data);
	} else $rating->vote($data);
}

class ratings {

    private $widget_id;
    private $data = array();
	
function __construct($wid) {
    
    $this->widget_id = $wid;
    }

public function get_ratings($data) {
	require_once 'include/DB_connect.php';
	$db = new DB_connect();
   	$con = $db->connect();
	
	$pid = $data['place_id'];
	
	$resPlaceGrade = mysqli_query($con, "SELECT `grade` FROM `ratings` WHERE `places_uid` = '$pid'");
	$no_of_rows = mysqli_num_rows($resPlaceGrade);
	if($no_of_rows>0) {
		$placeGradeAvg = 0;
		while($checkPlaceGrade = mysqli_fetch_array($resPlaceGrade)){
			$placeGrade[] = $checkPlaceGrade['grade'];
			}
		for($i = 0; $i<count($placeGrade); $i++){
			$placeGradeAvg += $placeGrade[$i];
			}
		$placeGradeAvg = $placeGradeAvg/count($placeGrade);
		echo (int)$placeGradeAvg;
		} else echo 2;
}
public function vote($data) {
    require_once 'include/DB_connect.php';
	require_once 'include/DB_Functions.php';
	$fn = new DB_Functions();
	$db = new DB_connect();
   	$con = $db->connect();
	
    # Get the value of the vote
    preg_match('/star_([1-5]{1})/', $_POST['out_data'], $match);
    $vote = $match[1];
	$pid = $data['place_id'];
	$username = $_SESSION['UserName'];
	$idEventOwner = $fn->getIdByUsername($username);
	
	$sqlDidVote = "SELECT gid FROM `ratings` WHERE `uid` = '$idEventOwner' AND `places_uid` = '$pid'";
	$resDidVote = mysqli_query($con,$sqlDidVote);
	$no_of_rows = mysqli_num_rows($resDidVote);
	if($no_of_rows == 0){
	$sqlInsertVote = "INSERT INTO `ratings`(`places_uid`, `uid`, `grade`) VALUES ($pid, $idEventOwner, $vote)";
	$insertVote = mysqli_query($con, $sqlInsertVote) or die(mysqli_error($con));
	}
    $this->get_ratings($data);
}

}

?>