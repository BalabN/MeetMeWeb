<?php
class DB_Connect {
    // constructor
    function __construct() {
    }
    // destructor
    function __destruct() {
        // $this->close();
    }
    // Connecting to database
    public function connect() {
        require_once 'include/config.php';
        // connecting to mysql
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_DATABASE);
        // return database handler
		return $con; 
    }
    // Closing database connection
    public function close() {
        mysqli_close();
    }
}

// We will use PDO to execute database stuff. 
// This will return the connection to the database and set the parameter
// to tell PDO to raise errors when something bad happens
function getDbConnection() {
  require_once 'config.php';
  $db = new PDO(DB_DRIVER . ":dbname=" . DB_DATABASE . ";host=" . DB_HOST, DB_USER, DB_PASSWORD);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
  return $db;
}

// This is the 'search' function that will return all possible rows starting with the keyword sent by the user
function searchForUsers($keyword) {
    $db = getDbConnection();
    $stmt = $db->prepare("SELECT username FROM `users` WHERE username LIKE ? UNION SELECT username FROM `users` WHERE firstname LIKE ? UNION SELECT username FROM `users` WHERE lastname LIKE ?");

    $keyword = $keyword . '%';
    $stmt->bindParam(1, $keyword, PDO::PARAM_STR, 100);
	$stmt->bindParam(2, $keyword, PDO::PARAM_STR, 100);
	$stmt->bindParam(3, $keyword, PDO::PARAM_STR, 100);
	
    $isQueryOk = $stmt->execute();
  
    $results = array();
    
    if ($isQueryOk) {
      $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } else {
      trigger_error('Error executing statement.', E_USER_ERROR);
    }

    $db = null; 

    return $results;
}

?>