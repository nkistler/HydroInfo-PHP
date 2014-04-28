<?php
/*
Page for user to view data log from particular node
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
$nodeId = $_GET['id'];
$validNode = false;
$user_id = $loggedInUser->user_id;
$user_permissions = fetchUserPermissions($user_id);
$sensors = $loggedInUser->getUserSensors();
$measurements = NULL;

//check to see if user has permissions to access any node
foreach ($user_permissions as $val)
{
	if ($val['permission_id'] == 2 || $val['permission_id'] == 3)
	{
		$validNode = true;
	}
}

//check to see if sensor data being queried does actually belong to the user
if ($validNode == false)
{
	if ($sensors)
	{
		foreach ($sensors as $val)
		{
			if($val['id'] == $nodeId)
			{
				$validNode = true;
			}
		}
	}
}

//display the page only if user owns the node or the user is an administrator
if ($validNode == true)
{
	$measurements = fetchMeasurementInfo($nodeId);
	if ($measurements)
	{
		//google charts api needs to be loaded in page header, so default header can't be used for this page
		echo "
		<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
		<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
		<title>".$websiteName."</title>
		<link href='".$template."' rel='stylesheet' type='text/css' />
		<script type='text/javascript' src='https://www.google.com/jsapi'></script>
		<script src='models/funcs.js' type='text/javascript'>
		</script>
		<script type='text/javascript'>
			google.load('visualization', '1', {packages:['corechart']});
			google.setOnLoadCallback(drawChart);
			function drawChart()
			{
				var data = google.visualization.arrayToDataTable([
					['Time', 'Measurement 1', 'Measurement 2', 'Measurement 3'],";
		$i = 0;
		foreach ($measurements as $measurement)
		{
			echo "['".$measurement['time']."', ".$measurement['measurement_1'].", ".$measurement['measurement_2'].", ".$measurement['measurement_3']."]";
			$i++;
			if ($i != count($measurements))
			{
				echo ", ";
			}

		}
		echo "
			]);

				var options = {
					title: 'Soil Moisture Data'
				};

				var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
				chart.draw(data, options);
			}
		</script>
		</head>";
	}
	else
	{
		require_once("models/header.php");
	}
	echo "
	<body>
	<div id='wrapper'>
	<div id='top'><div id='logo'></div></div>
	<div id='content'>
	<h1>HydroInfo</h1>
	<h2>Node Log</h2>
	<div id='left-nav'>";
	include("left-nav.php");
	
	echo "
	</div>
	<div id='main'>";
	if ($measurements)
	{
		$column_1[] = NULL;
		$column_2[] = NULL;
		$column_3[] = NULL;
		echo "
		<table class='admin' cellpadding='5'><tr><th>Time</th><th>Measurement 1</th><th>Measurement 2</th><th>Measurement 3</th><th>Temperature</th><th>Weather Condition</th><th>Wind Speed</th><th>Wind Direction</th><th>Humidity</th><th>Precipitation</th></tr>";

		foreach ($measurements as $val)
		{
			$column_1[] = $val['measurement_1'];
			$column_2[] = $val['measurement_2'];
			$column_3[] = $val['measurement_3'];
			echo "<tr><td>".$val['time']."</td><td>".$val['measurement_1']."</td><td>".$val['measurement_2']."</td><td>".$val['measurement_3']."</td><td>".$val['temperature']."</td><td>".$val['weather']."</td><td>".$val['wind_speed']."</td><td>".$val['wind_condition']."</td><td>".$val['humidity']."</td><td>".$val['precipitation']."</td></tr>";
		}
		echo "
		</table>";
		echo "<table cellpadding='10'>";
		echo "<tr><td>Measurement 1</td><td>Average Value: ".mean($column_1)."</td><td>Standard Deviation: ".standardDeviation($column_1)."</td></tr>";
		echo "<tr><td>Measurement 2</td><td>Average Value: ".mean($column_2)."</td><td>Standard Deviation: ".standardDeviation($column_2)."</td></tr>";
		echo "<tr><td>Measurement 3</td><td>Average Value: ".mean($column_3)."</td><td>Standard Deviation: ".standardDeviation($column_3)."</td></tr>";
		echo "</table>";
	}
	else
	{
		echo "<p>No records returned for this node.</p>";
	}
	echo "
	<div id='chart_div'></div>
	</div>
	<div id='bottom'></div>
	</div>
	</body>
	</html>";
}
else
{
	header("Location: account.php"); die();
}
?>
