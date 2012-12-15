<?php

	function decode($arg)
	{
		//	return base64_decode($arg);
		return $arg;
	}
	
	function encode($arg)
	{
		//	return base64_encode($arg);
		return $arg;
	}
	
	unset($CFG);


$CFG = new stdClass();
$CFG->user_name = "CHANGE_ME";
$CFG->password = "CHANGE_ME";
$CFG->database = "CHANGE_ME";
$CFG->server = "CHANGE_ME";

?>
