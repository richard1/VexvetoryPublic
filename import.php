<?php
	$user_name = "CHANGE_ME";
	$password = "CHANGE_ME";
	$database = "CHANGE_ME";
	$server = "CHANGE_ME";
	$db_handle = mysql_connect($server, $user_name, $password);
	$db_found = mysql_select_db($database, $db_handle);
	if ($db_found) {
		$s = 'Sensor,Ultrasonic Range Finder,4,http://www.vexrobotics.com/media/catalog/product/cache/11/image/296x/5e06319eda06f020e43594a9c230972d/u/l/ultrasonic-range-finder-a_1.jpg,
		$a = explode(",",$s);
		$i=0;
		for($i=0;$i<count($a);$i+=4) {
			echo(intval($a[$i+2])>0);
			mysql_query("INSERT INTO robo (name, quantity, url, type) VALUES ('".$a[$i+1]."', ".$a[$i+2].",'".$a[$i+3]."','".$a[$i]."')");
		}
		mysql_close($db_handle);
						
	}
?>