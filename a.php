<?php 
require_once('conf.inc.php');

$db_handle = mysql_connect($CFG->server, $CFG->user_name, $CFG->password);
$db_found = mysql_select_db($CFG->database, $db_handle);
	$r=array();
	$result = mysql_query("SELECT * FROM robo");
	while($row = mysql_fetch_assoc($result)) {
		$r[]=$row['name'];
		$r[]=$row['quantity'];
		$r[]=$row['url'];
		$r[]=$row['type'];
		$r[]=$row['owners'];
	}
	echo count($r);
	for($i=0;$i<count($r);$i++)
		mysql_query("UPDATE robo SET name='".base64_decode($r[$i*5])."',quantity='".intval($r[$i*5+1])."',url='".base64_decode($r[$i*5+2])."',type='".base64_decode($r[$i*5+3])."',owners='".base64_decode($r[$i*5+4])."' WHERE name='".$r[$i*5]."'");
?>
