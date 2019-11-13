<?php
$url = 'http://3.208.74.116/api/tasks.php';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="doIT">
		<meta name="author" content="Russell Thackston">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>doIT</title>

		<link rel="stylesheet" href="style/style.css">

		<link href="https://fonts.googleapis.com/css?family=Chilanka%7COrbitron&display=swap" rel="stylesheet">

	</head>


	<body>
		<a href="index.php"><h1 id="siteName">doIT</h1></a>
		<hr>

			<?php if ($http_status_code == 200) { ?>
				<?php foreach(json_decode($response, true) as $item){ ?>
					<div class="list">
						<form method="POST" action="edit.php" style="display: inline-block">
							<input type="hidden" 	name="listID" value="<?php echo $item["listID"];?>" >
							<input type="checkbox"	name="fin" <?php if($item["completed"]){echo "checked='checked'";} ?> >
							<input type="text" 	name="listItem" size="50" value="<?php echo $item["taskName"];?>" maxlength="100" >
							<span>by:</span>
							<input type="date" 	name="finBy" value="<?php if($item['taskDate']=='0000-00-00'){echo '';} else {echo $item['taskDate'];} ?>" >
							<input type="submit" 	name="submitEdit" value="&check;" >
						</form>
						<form method="POST" action="delete.php" style="display: inline-block">
							<input type="hidden" name="listID" value="<?php echo $item["listID"];?>" >
							<input type="submit" name="submitDelete" value="&times;" >
						</form>
					</div>
					<?php } ?>
			<?php } ?>

			<div class="list">
				<form  method="POST" action="add.php">
					<input type="checkbox" name="fin" value="done">
					<input type="text" name="listItem" size="50">
					<span>by:</span>
					<input type="date" id="finDate" name="finBy">
					<input type="submit" value="&#43;">
				</form>
			</div>

			<?php if ($http_status_code != 200) { ?>
			<div class="error">
				Uh oh! There was an error reading the to do list.
			</div>
			<?php } ?>
			<?php if (array_key_exists('error', $_GET)) { ?>
				<?php if ($_GET['error'] == 'add') { ?>
				<div class="error">
					Uh oh! There was an error adding your to do item. Please try again later.
				</div>
				<?php } ?>
				<?php if ($_GET['error'] == 'delete') { ?>
				<div class="error">
					Uh oh! There was an error deleting your to do item. Please try again later.
				</div>
				<?php } ?>
				<?php if ($_GET['error'] == 'edit') { ?>
				<div class="error">
					Uh oh! There was an error updating your to do item. Please try again later.
				</div>
				<?php } ?>
			<?php } ?>
			
			
			<footer>
				<div>
					<span id="greeting" class="hide">Welcome!</span> You visited this site from this computer on <span id="time"></span>
					<button type="button">Do Not Track Me</button>
				</div>
			</footer>
			
		<script>
			//variables
			var footer = document.querySelector("footer");
			var greeting = document.querySelector("footer #greeting");
			var time_visited = document.querySelector("footer #time");
			var no_track = document.querySelector("footer button");
			
			//local storage key names (holds the content in the button for tarcking and the time span for visited
			var STORAGE_KEY_TIME = "time-visited";
		
			var STORAGE_KEY_NO_TRACK = "no-track";
			
			//check to see if the user wants to be tracked; functions as a get item check
			if (!localStorage.getItem(STORAGE_KEY_NO_TRACK)) {
				
				//Visitor has not visted the site yet; must get the date
				if (!localStorage.getItem(STORAGE_KEY_TIME)){
					//creates new date
					var currentDate = new Date();
					//creates the date and timestamp
					var dateString = currentDate.toDateString() + " " + currentDate.toLocaleTimeString("en-us");
					
					//sets the date into local storage
					localStorage.setItem(STORAGE_KEY_TIME, dateString);
					greeting.classList.remove("hide");
				}
					//displays the time visited 
					var storedDate = localStorage.getItem(STORAGE_KEY_TIME);
					
					time_visited.innerHTML = storedDate;
			} else {
				
				footer.classList.add("hide")
			}
			
			//Check to see if the user chose NOT to be tracked
			no_track.addEventListener("click", function () {
				//removes the time storage from storage
				localStorage.removeItem(STORAGE_KEY_TIME);
				//sets flag to not track user again
				localStorage.setItem(STORAGE_KEY_NO_TRACK, "TRUE");
			});
		</script>
	</body>
</html>

