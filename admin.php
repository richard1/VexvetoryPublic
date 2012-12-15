<?php 
	require_once("conf.inc.php");

$db_handle = mysql_connect($CFG->server, $CFG->user_name, $CFG->password);
$db_found = mysql_select_db($CFG->database, $db_handle);
function _strnatcmp($left, $right) {while((strlen($left) > 0) && (strlen($right) > 0)) {if(preg_match('/^([^0-9]*)([0-9].*)$/Us', $left, $lMatch)) {$lTest = $lMatch[1];$left = $lMatch[2];} else {$lTest = $left;$left = '';}if(preg_match('/^([^0-9]*)([0-9].*)$/Us', $right, $rMatch)) {$rTest = $rMatch[1];$right = $rMatch[2];} else {$rTest = $right;$right = '';}$test = strcmp($lTest, $rTest);if($test != 0) {return $test;}if(preg_match('/^([0-9]+)([^0-9].*)?$/Us', $left, $lMatch)) {$lTest = intval($lMatch[1]);$left = $lMatch[2];} else {$lTest = 0;}if(preg_match('/^([0-9]+)([^0-9].*)?$/Us', $right, $rMatch)) {$rTest = intval($rMatch[1]);$right = $rMatch[2];} else {$rTest = 0;}$test = $lTest - $rTest;if($test != 0) {return $test;}}return strcmp($left, $right);}?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Team 254</title>
		<style>
			@font-face {
				font-family: CartoGothicStdBold;
				src: url('CartoGothicStd-Bold.otf');
			}
			html {
				background: -moz-linear-gradient(top, #003375 0px, #3C679D 500px);
				background: -webkit-gradient(linear, left top, left bottom, color-stop(1%,#003375), color-stop(300px,#3C679D));
				background: -webkit-linear-gradient(top, #003375 0px,#3C679D 300px);
				background: -o-linear-gradient(top, #003375 0px,#3C679D 300px);
				background: -ms-linear-gradient(top, #003375 0px,#3C679D 300px);
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#003375', endColorstr='#3C679D',GradientType=0 );background: linear-gradient(top, #003375 0px,#3C679D 300px);
				background-repeat: repeat-x;
				background-color:#3C679D;
			}
			div.main {
				margin:0px auto;
				width:960px;
			}
			div.headerimage {
				margin:0px auto;
				width:450px;
				height:124px;
				background-image:url('header.png');
			}
			div.skylineimage {
				margin:0px auto;
				width:960px;
				height:110px;
				background-image:url('skyline.png');
			}
			div.title {
				margin:0px auto;
				position:relative;
				top:55px;
				font-family:CartoGothicStdBold, 'century gothic', 'Trebuchet MS', Arial, sans-serif;
				font-size:16px;
				line-height:55px;
				color:#003375;
				text-align:center;
			}
			div.content {
				padding:10px;
				background-color:white;
				width:940px;
			}
			div.instruct {
				text-align:right;
				font-family:CartoGothicStdBold, 'century gothic', 'Trebuchet MS', Arial, sans-serif;
				font-size:19pt;
				position:relative;
				width:350px;
			}
			div.browseinstruct {
				text-align:left;
				font-family:CartoGothicStdBold, 'century gothic', 'Trebuchet MS', Arial, sans-serif;
				font-size:19pt;
				position:relative;
				top:-66px;
				left:600px;
				width:250px;
			}
			input.field {
				width:225px;
				height:30px;
				font-size:19pt;
				position:relative;
				top:-33px;
				left:356px;
				font-family:CartoGothicStdBold, 'century gothic', 'Trebuchet MS', Arial, sans-serif;
			}
			input.field2 {
				width:225px;
				height:30px;
				font-size:19pt;
				font-family:CartoGothicStdBold, 'century gothic', 'Trebuchet MS', Arial, sans-serif;
			}
			input.field:focus {
				outline:none;
			}
			div.list {
				margin:0px auto;
				overflow:hidden;
				height:0px;
				width:800px;
				border:1px black;
			}
			div.item {
				margin:0px auto;
				background-image:url('item.png');
				width:654px;
				height:100px;
			}
			div.category {
				margin:0px auto;
				background-image:url('item.png');
				width:654px;
				height:100px;
				cursor: pointer;
			}
			div.itemtop {
				margin:0px auto;
				background-image:url('itemtop.png');
				width:654px;
				height:9px;
			}
			div.itembottom {
				margin:0px auto;
				background-image:url('itembottom.png');
				width:654px;
				height:9px;
			}
			img.image {
				width:80px;
				height:80px;
				padding:10px 19px;
				float:left;
			}
			div.listname {
				padding:20px 20px 19px 0px;
				float:left;
				font-family:CartoGothicStdBold, 'century gothic', 'Trebuchet MS', Arial, sans-serif;
				font-size:20pt;
			}
			div.found {
				margin:0px auto;
				width:636px;
				font-family:CartoGothicStdBold, 'century gothic', 'Trebuchet MS', Arial, sans-serif;
				font-size:18pt;
			}
			div.listdetails {
				font-size:14pt;
				position:relative;
				top:8px;
			}
			a:link,a:visited,a:hover,a:active {
				color:black;
				text-decoration:none;
			}
			form.browseform {
				margin:0px;
			}
			span.checkoutlink {
				cursor: pointer;
			}
			input.checkout {
				width:40px;
				height:20px;
				font-size:14pt;
				font-family:CartoGothicStdBold, 'century gothic', 'Trebuchet MS', Arial, sans-serif;
			}
			input.checkout:focus {
				outline:none;
			}
			div.checkout {
				margin:0px auto;
				overflow:hidden;
				width:800px;
				border:1px black;
				cursor: pointer;
			}
		</style>
        <script type="text/javascript">
			function createAccount() {
				
			}
		</script>
	</head>
	<body>
		<div class="main">
			<div class="headerimage"></div>
			<div class="skylineimage">
				<div class="title">TEAM 254 INVENTORY SYSTEM</div>
			</div>
			<div class="content"><br/><?php
					$e = array();
					$l=true;
					if(isset($_POST['adminname'])&&isset($_POST['adminpass'])) {
						if($_POST['adminname']!=""&&$_POST['adminpass']!="") {
							$result = mysql_query("SELECT * FROM roboaccounts WHERE name='".base64_encode($_POST['adminname'])."' AND pass='".md5($_POST['adminpass'])."' AND num=-1;");
							$num_rows = mysql_num_rows($result);
							if ($num_rows) $l=false;
							else echo "Wrong admin credentials!<br/>";
						}
						else echo "You must enter the admin credentials!<br/>";
					}
					if($l) {}
            		else if(isset($_POST['newaccount'])) {
						if(!isset($_POST['name'])||$_POST['name']=="")
							$e[]="You must enter a username!";
						else if(strlen($_POST['name'])>29)
							$e[]="The username must be shorter!";
						if(!isset($_POST['pass1'])||$_POST['pass1']=="")
							$e[]="You must enter a password!";
						else if(strlen($_POST['pass1'])>29)
							$e[]="The password must be shorter!";
						else if(!isset($_POST['pass2'])||$_POST['pass2']=="")
							$e[]="You must confirm the password!";
						else if($_POST['pass1']!=$_POST['pass2'])
							$e[]="The passwords do not match!";
						if(count($e)==0) {
							if ($db_found) {
								$result = mysql_query("SELECT * FROM roboaccounts");
								$c=array();
								while($row = mysql_fetch_assoc($result)) {
									if(base64_encode($_POST['name'])==$row['name']) {
										$e[]="That name has already been taken!";
										break;
									}
									if(intval($row['num'])>-1) {
										$c[]=intval($row['num']);
									}
								}
								if(count($e)==0) {
									if(count($c)>9) $e[]="There are too many accounts registred!";
									else {
										$d=0;
										for($i=0;$i<10;$i++)
											if(!in_array($i,$c)) {
												$d=$i;
												break;	
											}	
										$sql="INSERT INTO roboaccounts (name, pass, num) VALUES ('".base64_encode($_POST['name'])."','".md5($_POST['pass1'])."',".(isset($_POST['admin'])&&$_POST['admin']=="admin"?-1:$d).");";
										if (!mysql_query($sql,$db_handle))
											die('Error: ' . mysql_error());
										else {
											echo "Success!<br/>";
										}	
									}
								}
							}
							else echo "DB ERROR";
						}
					}
					else if(isset($_POST['newpart'])) {
						if(!isset($_POST['partname'])||$_POST['partname']=="")
							$e[]="You must enter a part name!";
						else if(strlen($_POST['name'])>63)
							$e[]="The part name must be shorter!";
						if(!isset($_POST['type'])||$_POST['type']=="")
							$e[]="You must enter a type!";
						else if(strlen($_POST['type'])>29)
							$e[]="The password must be shorter!";
						if(strlen($_POST['url'])>63)
							$e[]="The url must be shorter!";
						if(!isset($_POST['num'])||$_POST['num']==""||!is_numeric($_POST['num']))
							$e[]="You must enter a part count!";
						if(count($e)==0) {
							if ($db_found) {
								$result = mysql_query("SELECT * FROM robo");
								while($row = mysql_fetch_assoc($result)) {
									if(base64_encode($_POST['partname'])==$row['name']) {
										$e[]="That name has already been taken!";
										break;
									}
								}
								if(count($e)==0) {
									$sql="INSERT INTO robo (name, quantity, url, type, owners) VALUES ('".base64_encode($_POST['partname'])."',".intval($_POST['num']).",'".base64_encode($_POST['url'])."','".base64_encode($_POST['type'])."','MDswOzA7MDswOzA7MDswOzA7MA==');";
									if (!mysql_query($sql,$db_handle))
										die('Error: ' . mysql_error());
									else {
										echo "Success!<br/>";
									}	
								}
							}
							else echo "DB ERROR";
						}
					}
					else if(isset($_POST['deletepart'])) {
						mysql_query("DELETE FROM robo WHERE name='".base64_encode($_POST['selectpart'])."';");
						echo "Success!<br/>";
					}
					if(count($e)>0) {
						foreach($e as $f)
							echo $f."<br/>";
						echo "<br/>";
					}
				?>
				<form method="post" action="../robo/admin.php">Administrative Page<br/><br/>
                Enter the admin username or password before doing anything else: <input name="adminname" type="text" value="<?php if(isset($_POST['adminname'])) echo $_POST['adminname'];  ?>"></input> <input name="adminpass" type="password" value="<?php if(isset($_POST['adminpass'])) echo $_POST['adminpass'];  ?>"></input><br/><br/><hr/><br/>
                Choose a part to edit: <select name="selectpart"><?php
              	if ($db_found) {
					$result = mysql_query("SELECT * FROM robo");
					$r=array();
					while($row = mysql_fetch_assoc($result))
						$r[]=base64_decode($row['name']);
					usort($r, '_strnatcmp');
					foreach($r as $s)
						echo "<option>".$s."</option>";
				}
				else echo "DB ERROR";
				?></select><br/>Enter new part information:<br/>Part name: <input name="partname" type="text"/><br/>Type: <input name="type" type="text"/><br/>URL: <input name="url" type="text"/><br/>Count: <input type="text" name="num" /><br/><input name="editpart" type="submit" value="Submit"></input> or <input name="deletepart" type="submit" value="Delete Part"></input><br/><br/><hr/><br/>Create a new part:<br/>Part name: <input name="partname" type="text"/><br/>Type: <input name="type" type="text"/><br/>URL: <input name="url" type="text"/><br/>Count: <input type="text" name="num" /><br/><input name="newpart" type="submit" value="Submit"></input>
				<br/><br/><hr/><br/>
                Choose an account to edit: <select name="selectaccount"><?php
              	if ($db_found) {
					$result = mysql_query("SELECT * FROM roboaccounts");
					while($row = mysql_fetch_assoc($result)) {
						echo "<option>".base64_decode($row['name'])."</option>";
					}
				}
				else echo "DB ERROR";
				?></select><br/>(will be able to delete accounts, change names and passwords here, later)<br/><br/><hr/><br/>Create a new account:<br/>Username: <input name="name" type="text"/><br/>Password: <input name="pass1" type="password"/><br/>Confirm Password: <input name="pass2" type="password"/><br/><input type="checkbox" name="admin" value="admin" /> Admin Account<br/><input name="newaccount" type="submit" value="Submit"></input>
			<form></div>
		</div>
	</body>
</html>
