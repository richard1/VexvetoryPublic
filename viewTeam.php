<?php 
require_once("conf.inc.php");
/*
 * Based on team parts access code created by Lucas Tindall.
 * Modified by Jonathan Chang
 */
$db_handle = mysql_connect($CFG->server, $CFG->user_name, $CFG->password);
$db_found = mysql_select_db($CFG->database, $db_handle);
if($db_found)
{

}
$team = $_GET['team'];

$part= mysql_query("select * from parts");
$parts = mysql_fetch_assoc($part);
$count = mysql_query("SELECT COUNT(owners) FROM parts");
$counter= mysql_result($count,0);

if ($team==="a")
	$num=0;
else if($team==="b")
	$num=1;
else if($team==="c")
	$num=2;
else if($team==="d")
	$num=3;
else if($team==="e")
	$num=4;
else if($team==="f")
	$num=5;
else if($team==="g")
	$num=6;
else if($team==="bad")
	$num=7;
else
	header('Location: index.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>View Team</title>
	<link rel="stylesheet" type="text/css" href="team254.css" />
</head>
<body>
	<div class="main">
		<a href="index.php"><div class="headerimage"></div></a>
		<div class="skylineimage">
			<div class="title">Team 254 Inventory System</div>
		</div>
		<div class="content">
<?php
echo "<table border=10px cellpadding=5px><tr><th>Part Name</th><th>Team ".strtoupper($team)."</th></tr>";
while ($parts = mysql_fetch_assoc($part))
{
	$owners = explode(";",$parts['owners']);
	if(!($owners[$num] === "0"))
	{
		echo "<tr><td>".$parts['name']."</td>";
		echo "<td>".$owners[$num]."</td>";
		echo "</tr>";
	}
}
echo "</table>";
?>
		</div>
		
	</div>
<div class="footer" align="center">			
	<form action="index.php" method="get">
				<input type="submit" name="" value="Back" />
				</form>

		</div>
</body>
</html>