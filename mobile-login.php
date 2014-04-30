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
	$result = mysqli_query($mysqli,"SELECT id, password FROM users where user_name='$username'");
	$row = mysqli_fetch_array($result);
	if($row)
	{
		$user_id = $row[0];
		$hashed_pass =  $row[1];
		$entered_pass = generateHash($password, $hashed_pass);
		if ($hashed_pass == $entered_pass)
		{
			$nodes = mysqli_query($mysqli,"SELECT id, latitude, longitude FROM sensors where user_id='$user_id'");
			$records = mysqli_fetch_array($nodes);
			echo "Success";
			if ($records)
			{
				echo ",";
				foreach ($records as $val)
				{
					echo $records[0].",".$records[1].",".$records[2].",";
				}
			}
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

