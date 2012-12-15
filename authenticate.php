<?php
require_once "conf.inc.php";
session_start();
$login = false;

$db_handle = mysql_connect($CFG->server, $CFG->user_name, $CFG->password);
$db_found = mysql_select_db($CFG->database, $db_handle);
if(!$db_found)
	exit(1);

if(!isset($_POST['username']) || !isset($_POST['password']))
	$login = false;
else 
{
	$result = mysql_query("SELECT * FROM roboaccounts WHERE name='".encode($_POST['username'])."' AND pass='".($_POST['password'])."' AND num=-1;");
	$num_rows = mysql_num_rows($result);
	if($num_rows)
		$login = true;
}
if($login)
{
	$_SESSION['authenticated'] = 1;
	if(isset($_SESSION['failure']))
		unset($_SESSION['failure']);
	header('Location: main.php');
}
else 
{
	$_SESSION['failure'] = 1;
	header('Location: index.php');
}
?>