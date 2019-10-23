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
	if (!$dbconnecterror) {
		try {
			$sql = "SELECT * FROM doList";
			$stmt = $dbh->prepare($sql);
			$response = $stmt->execute();
			http_response_code(200);
			echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC))
			exit();

		} catch (PDOException $e) {
			//This means we had some database issues
			http_response_code(504);
			echo 'could not get tasks';
			exit();
		}
	}
}
