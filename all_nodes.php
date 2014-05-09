<?php
/*
This will be the page that displays the list of all node for viewing by admin users.
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
require_once("models/map_header.php");

echo "
<body>
<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
<h1>List of All Nodes</h1>
<h2>Nodes listed by date added.</h2>
<div id='left-nav'>";

include("left-nav.php");

echo "
</div>
<div id='main'>";
$nodeList = fetchNodes();
if ($nodeList)
{
	getLocationMap($nodeList);
}
echo "
<input type='submit' name='Submit' value='Submit' />
</form>
</div>
<div id='bottom'></div>
</div>
</body>
</html>";

?>
