<?php
/*
This will be the page that displays the list of all node for viewing by admin users.
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
require_once("models/header.php");

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
	echo "<table><tr><th>Node ID number</th><th>Node Location</th></tr>";
	foreach ($nodeList as $val)
	{
		echo "<tr><td><a href='table.php?id=".$val['id']."'>".$val['id']."</a></td><td>".$val['coordinates']."</td></tr>";
	}
	echo "
	</table>";
}
echo "
</div>
<div id='bottom'></div>
</div>
</body>
</html>";

?>
