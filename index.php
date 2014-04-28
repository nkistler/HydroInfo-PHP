<?php
/*
Default non-logged in page.
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
require_once("models/header.php");

echo "
<body>
<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
<h1>HydroInfo</h1>
<h2>Web application to track moisture levels in farm fields</h2>
<div id='left-nav'>";
include("left-nav.php");

echo "
</div>
<div id='main'>
<p>HydroInfo is a site dedicated to providing it's users secure cloud access to data concerning the moisture in their farm fields. This data is provided in near real time and can be accessed on mobile devices through our Android application. This is a student project built by Ricardo Castaneda, Nathan Kistler and Casandra Martin who are CSUMB students from the Computer Science and Information Technology program.</p>
<p>We plan to partner with regional water districts in order for them to view the raw data, which is of benefit to them because they can track patterns over time. This allows them to better plan for the future, particularly when it comes to infrastructure.</p>
<p>Copyright (c) 2013-".date("Y");

echo "
</p>
</div>
<div id='bottom'></div>
</div>
</body>
</html>";

?>
