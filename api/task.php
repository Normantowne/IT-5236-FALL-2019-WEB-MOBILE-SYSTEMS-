<?php
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



//Update Task
if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    if (array_key_exists('listID', $_GET)){
		$listID = $_GET['listID'];
	}else {
		//Could not be found
		http_response_code(404);
		echo "ID error";
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
			$sql = "UPDATE doList SET complete=:complete, listItem=:listItem, finishDate=:finishDate WHERE listID=:listID";
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(":complete", $complete);
			$stmt->bindParam(":listItem", $taskName);
			$stmt->bindParam(":finishDate", $taskDate);
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
//CREATE
} else if ($_SERVER['REQUEST_METHOD'] == "POST"){
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
			$sql = "INSERT INTO doList (listItem, finishDate, complete) VALUES (:listItem, :finishDate, :complete)";
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(":complete", $complete);
			$stmt->bindParam(":listItem", $taskName);
			$stmt->bindParam(":finishDate", $taskDate);
			$stmt->execute();
			$taskID = $dbh->lastInsertId();
			http_response_code(201);
			echo json_encode(["ID" => taskID]);
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

//DELETE
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE"){
	//find the ID of the task you want to DELETE
	if (array_key_exists('listID', $_GET)){
		$listID = $_GET['listID'];
	}else {
		//Could not be found
		http_response_code(404);
		echo "id error";
		exit();

	}

	if (!$dbconnecterror) {
		try {
			//deletes all from the table where the list ID is the given list ID
			$sql = "DELETE FROM doList WHERE listID=:listID";
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(":listID", $listID);
			$response = $stmt->execute();
			http_response_code(204);
			exit();

		} catch (PDOException $e) {
		//This means we had some database issues
		http_response_code(504);
		echo "check your internet connection";
		exit();

		}
	} else {
		//This means we had a bad gateway
		http_response_code(502);
		echo "database error";
		exit();

	}
} else {
		//METHOD NOT ALLOWED
		http_response_code(405);
		echo "DELETE method required";
		exit();
}
