<?php

require_once('DB_connect.php');

if(!isset($_GET['keyword'])){
	die();
} else{
$keyword = $_GET['keyword'];
$data = searchForUsers($keyword);
echo json_encode($data);
}

if(!isset($_Get['keyinput'])) {
	die();
} else {
	$keyword = $_GET['keyinput'];
	$data = searchForUsers($keyword);
	echo json_encode($data);
	}
	
	if(!isset($_Get['keyinput'])) {
	die();
} else {
	$keyword = $_GET['keyinput'];
	$data = searchForUsers($keyword);
	echo json_encode($data);
	}
?>