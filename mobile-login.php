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
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	if($row)
	{
		$user_id = $row['id'];
		$display_name = $row['display_name'];
		$hashed_pass =  $row['password'];
		$is_active = $row['active'];
		$title = $row['title'];
		$entered_pass = generateHash($password, $hashed_pass);
		if ($hashed_pass == $entered_pass and $is_active == 1)
		{
			#Echo out user data to be held by application. Done with comma separated values for ease of processing.
			echo "Success,".$user_id.",".$username.",".$display_name.",".$hashed_pass.",".$title;
			#Retrieve data on user accessible nodes.
			$result2 = null;
			if ($title == "Administrator" or $title == "Premium Member")
			{
				$result2 = mysqli_query($mysqli,"SELECT id, coordinates FROM sensors");
			}
			else
			{
				$result2 = mysqli_query($mysqli,"SELECT id, coordinates FROM sensors where user_id='$user_id'");
			}
			if ($result2)
			{
				$sensorIds = array();
				$temp = "";
				$numOfSensors = 0;
				$temp2 = "";
				$numOfMeasurements = 0;
				#Get number of nodes and related node data
				while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC))
				{ 
					$sensorIds[] = $row2['id'];
		   			$temp .= ",".$row2['id'].",".$row2['coordinates'];
					$numOfSensors += 1;
				}
				echo ",".$numOfSensors.$temp;
				#Query measurement data for each sensor
				foreach ($sensorIds as $sensorId)
				{
					$result3 = mysqli_query($mysqli,"SELECT id, file_timestamp, sensor_1, sensor_2, sensor_3, temperature, weather_condition, wind_speed, wind_direction, humidity, precipitation FROM measurements where sensor_id='$sensorId'");
					if ($result3)
					{
						#Get number of measurements and related measurement data
						while ($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC))
						{
							$temp2 .= ",".$sensorId.",".$row3['id'].",".$row3['file_timestamp'].",".$row3['sensor_1'].",".$row3['sensor_2'].",".$row3['sensor_3'].",".$row3['temperature'].",".$row3['weather_condition'].",".$row3['wind_speed'].",".$row3['wind_direction'].",".$row3['humidity'].",".$row3['precipitation'];
							$numOfMeasurements += 1;
						}
					}
				}
				echo ",".$numOfMeasurements.$temp2;
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

