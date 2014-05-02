<?php
/*
	Page to validate mobile login application. This page shouldn't normally be accessible by the links on the website, but needs to be publically open, so it can be accessed by hard link. This also needs to be a self-contained script in order to work properly.
*/
require_once("models/db-settings.php");
require_once("models/funcs.php");
#Check if script is being sent post variables. This check should let us know if page is being accessed through mobile app or through web browser.
if(!empty($_POST))
{
	#Match mySQL entry with entered username and password
	$username = sanitize($_POST["username"]);
	$password = trim($_POST["password"]);
	$result = mysqli_query($mysqli,"SELECT id, display_name, password, active, title FROM users where user_name='$username'");
	$row = mysqli_fetch_array($result);
	if($row)
	{
		$user_id = $row[0];
		$display_name = $row[1];
		$hashed_pass =  $row[2];
		$is_active = $row[3];
		$title = $row[4];
		$entered_pass = generateHash($password, $hashed_pass);
		if ($hashed_pass == $entered_pass & $is_active == 1)
		{
			#Echo out user data to be held by application. Done with comma separated values for ease of processing.
			echo "Success,".$user_id.",".$username.",".$display_name.",".$hashed_pass.",".$title.",";
			#Retrieve data on user accessible nodes.
			$result2 = mysqli_query($mysqli,"SELECT id, coordinates FROM sensors where user_id='$user_id'");
			$row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC); 
   			echo $row2['id'].",".$row2['coordinates'];
	
		}
		else #if password doesn't match...
		{
			echo "Error...";
		}
	}
	else #if user doesn't exist in database...
	{
		echo "Error...";
	}
	mysqli_close($mysqli);
}
else
{
	header("Location: account.php");
}
?>

