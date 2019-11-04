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


//GET Task
if ($_SERVER['REQUEST_METHOD'] == "GET") {
	if (!$dbconnecterror) {
		try {
			$sql = "SELECT * FROM doList";
			$stmt = $dbh->prepare($sql);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			// listID => listID
			// listName -> taskName
			// finishDate -> taskDate
			// complete -> completed
			
			$new_data = [];
			foreach($data as $task) {
				$task['taskName'] = $task['listName'];
				$task['taskDate'] = $task['finishDate'];
				$task['completed'] = $task['complete'] ? true : false;
				$new_data[] = $task;
			}
			
			http_response_code(200);
			echo json_encode($new_data);
			exit();

		} catch (PDOException $e) {
			//This means we had some database issues
			http_response_code(504);
			echo 'could not get tasks';
			exit();
		}
	}
}
