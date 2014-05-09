<?php
/*
This will be the page that displays the list of sensors the regular member owns. Will display our prototype sensor id for our administrative users. Also allows user to manage their set of nodes.
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}

//Forms posted
if(!empty($_POST))
{
	//Delete nodes
	if(!empty($_POST['delete']))
	{
		$deletions = $_POST['delete'];
		if ($deletion_count = deleteNodes($deletions))
		{
			$successes[] = lang("NODE_DELETIONS_SUCCESSFUL", array($deletion_count));
		}
	}
	
	//Create new node location
	if(!empty($_POST['location']))
	{
		$location = trim($_POST['location']);
		$location_arr = getLatitudeLongitude($location);
		
		//Validate request
		if (!$location_arr)
		{
			$errors[] = lang("INVALID_LOCATION_FORMAT", array($location));
		}		
		elseif (locationInUse($location))
		{
			$errors[] = lang("LOCATION_IN_USE", array($location));
		}
		elseif (minMaxRange(1, 150, $location))
		{
			$errors[] = lang("LOCATION_CHAR_LIMIT", array(1, 150));	
		}
		else
		{
			if ($loggedInUser->addSensor($location_arr['latitude'], $location_arr['longitude']))
			{
				$successes[] = lang("NODE_LOCATION_CREATION_SUCCESSFUL", array($location));
			}
			else
			{
				$errors[] = lang("SQL_ERROR");
			}
		}
	}
}

//Need header here so we can add our map script
require_once("models/map_header.php");

echo "
<body>
<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
<h1>List of Nodes Owned by $loggedInUser->displayname.</h1>
<h2>Nodes listed by date added.</h2>
<div id='left-nav'>";

include("left-nav.php");

echo "
</div>
<div id='main'>";

echo resultBlock($errors,$successes);

$sensors = $loggedInUser->getUserSensors();
if ($sensors)
{
	getLocationMap($sensors);
}
else
{
	echo "<p>There are currently no existing nodes belonging to $loggedInUser->displayname.</p>";
	echo "<form name='updateNodes' action='".$_SERVER['PHP_SELF']."' method='post'>";
}
echo "
<p></p>
<h3>Add New Node</h3>
<p>
<label>Node Location:</label>
<input type='text' name='location' />
</p>                                
<input type='submit' name='Submit' value='Submit' />
</form>
</div>
<div id='bottom'></div>
</div>
</body>
</html>";

?>
