<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Declare the credentials to the database
$dbconnecterror = FALSE;
$dbh = NULL;
require_once 'credentials.php';
	
try{
	
	$conn_string = "mysql:host=".$dbserver.";dbname=".$db;
	
	$dbh= new PDO($conn_string, $dbusername, $dbpassword);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
}catch(Exception $e){
	//$dbconnecterror = TRUE;
	
	//This means we had some database issues
	http_response_code(504);
	exit();
}


//GET Task
if ($_SERVER['REQUEST_METHOD'] == "GET") {  
    if (array_key_exists('listID', $_GET)){
		$listID = $_GET['listID']; 
	}else {
		//Could not be found
		http_response_code(404);
		echo "id error";
		exit();
	}
	
	//decoding the json body from the request
    $task = json_decode(file_get_contents('php://input'), true);
	
    if (array_key_exists('completed', $task)) {
		$complete = $task["completed"];
	} else {
		//Could not be found
		http_response_code(404);
		echo "complete error";
		exit();
	}

	// add the other two fields here
	if (array_key_exists('taskName', $task)) {
		$taskName = $task["taskName"];
	} else {
		//Could not be found
		http_response_code(404);
		echo "taskName error";
		exit();
	}
		
	
	if (array_key_exists('taskDate', $task)) {
		$taskDate = $task["taskDate"];
	} else {
		//Could not be found
		http_response_code(404);
		echo "taskDate error";
		exit();
	}
	
if (!$dbconnecterror) {
		try {
			$sql = "SELECT doList WHERE listID=:listID";
			$stmt = $dbh->prepare($sql);			
			$stmt->bindParam(":listID", $listID);
			$response = $stmt->execute();
			http_response_code(204);
			exit();
			
		} catch (PDOException $e) {
		//This means we had some database issues
		http_response_code(504);
		exit();
		
		}	
	} else {
		//This means we had a bad gateway
		http_response_code(502);
		echo "database error";
		exit();
	}

