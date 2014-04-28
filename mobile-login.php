<?php
/*
	Page to validate mobile login application. This page shouldn't normally be accessible by the links on the website, but needs to be publically open, so it can be accessed by hard link. This also needs to be a self-contained script in order to work properly.
*/


function sanitize($str)
{
	return strtolower(strip_tags(trim(($str))));
}

function generateHash($plainText, $salt)
{
	$salt = substr($salt, 0, 25);
	return $salt . sha1($salt . $plainText);
}

require_once("models/db-settings.php");
$username = $_POST["username"];
$password = $_POST["password"];
$result = mysqli_query($mysqli,"SELECT * FROM users where user_name='$username'");
$row = mysqli_fetch_array($result);
if($row)
{
	$hashed_pass =  $row[3];
	$entered_pass = generateHash($password, $hashed_pass);
	if ($hashed_pass == $entered_pass)
		foreach ($row as $val)
		{
			echo $val.",";
		}
	else
		echo "Error...";
}
else
{
	echo "Error";
}
mysqli_close($mysqli);
?>

