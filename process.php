<script type = "text/javascript">
	function hi(evt, int) {
		var charCode = (evt.which) ? evt.which : event.keyCode;
		if(charCode == 13) {
			alert("ENTER");
			return false;
		}
		return true;
		<?php ?>
	}
</script>

<?php
require_once("conf.inc.php");

	function sortByName($left, $right) {
		$left = $left[0];
	$right = $right[0];
	  while((strlen($left) > 0) && (strlen($right) > 0)) {
		if(preg_match('/^([^0-9]*)([0-9].*)$/Us', $left, $lMatch)) {
		  $lTest = $lMatch[1];
		  $left = $lMatch[2];
		} else {
		  $lTest = $left;
		  $left = '';
		}
		if(preg_match('/^([^0-9]*)([0-9].*)$/Us', $right, $rMatch)) {
		  $rTest = $rMatch[1];
		  $right = $rMatch[2];
		} else {
		  $rTest = $right;
		  $right = '';
		}
		$test = strcmp($lTest, $rTest);
		if($test != 0) {
		  return $test;
		}
		if(preg_match('/^([0-9]+)([^0-9].*)?$/Us', $left, $lMatch)) {
		  $lTest = intval($lMatch[1]);
		  $left = 1;
		} else {
		  $lTest = 0;
		}
		if(preg_match('/^([0-9]+)([^0-9].*)?$/Us', $right, $rMatch)) {
		  $rTest = intval($rMatch[1]);
		  $right = 0;
		} else {
		  $rTest = 0;
		}
		$test = $lTest - $rTest;
		if($test != 0) {
		  return $test;
		}
	  }
	  return strcmp($left, $right);}
	$db_handle = mysql_connect($CFG->server, $CFG->user_name, $CFG->password);
	$db_found = mysql_select_db($CFG->database, $db_handle);
	if ($db_found) {
		if(isset($_POST['viewteam'])) {
			$result = mysql_query("SELECT * FROM roboaccounts WHERE name='".encode($_POST['viewteam'])."';");
			$num_rows = mysql_num_rows($result);
			if ($num_rows)  {
				$teamNum=-1;
				while($row = mysql_fetch_assoc($result)) {
					$teamNum = intval($row['num']);
				}
				$a = array();
				$result = mysql_query("SELECT * FROM parts");
				while($row = mysql_fetch_assoc($result)) {
					$c=split(";",decode($row['owners']));
					if(intval($c[$teamNum])>0) {
						$b = array(decode($row['name']),$row['quantity'],decode($row['url']),decode(trim($row['type'])),$c[$teamNum]);
						array_push($a,$b);
					}
				}
				usort($a,'sortByName');
				echo '<div class="itemtop"></div>';
				$i = 0;
				foreach($a as $f) {
					if($f[2]=="") $f[2]="empty.png";
					echo '<div class="item"><img class="image" src="'.$f[2].'" alt="" /><div class="listname"><span id="listname'.$i.'">'.$f[0].'</span><br/><div class="listdetails">Your team has checked out '.$f[4].'.</div></div></div>';
				}
				echo '<div class="itembottom"></div>';
			}
			else {
				echo "invalid team name";	
			}
		}
		else if(isset($_POST['partlist'])) {
			$valid = false;
			if(isset($_POST['final'])) {
				$result = mysql_query("SELECT * FROM roboaccounts WHERE name='".encode($_POST['adminname'])."' AND pass='".($_POST['adminpass'])."' AND num=-1;");
				$num_rows = mysql_num_rows($result);
				if (true)  {
					$result = mysql_query("SELECT * FROM roboaccounts WHERE name='".encode($_POST['teamname'])."';");
					$num_rows = mysql_num_rows($result);
					if ($num_rows)  {
						$valid=true;
						while($row = mysql_fetch_assoc($result)) {
							$teamNum = intval($row['num']);
							$a=split(",",$_POST['partlist']);
							for($i=0;$i<count($a);$i+=2) {
								$result2 = mysql_query("SELECT * FROM parts WHERE name LIKE '%".encode(str_replace("~",",",$a[$i]))."%';");
								while($row2 = mysql_fetch_assoc($result2)) {
									$b=split(";",decode($row2['owners']));
									$c="";
									$d=0;
									foreach($b as $e)
										$d+=intval($e);
									// NEW STUFF
									$studId="'100000'";
									$teamName = "'" . $_POST['teamname'] . "'";
									$partName = $a[$i];
									$qty = $a[$i+1];
									$id = mysql_query("SELECT id FROM parts WHERE name LIKE '%".encode(str_replace("~",",",$a[$i]))."%';");
									// Gets the part ID
									// Added by Richard and Jonathan
									$partID = 0;
									while($row3 = mysql_fetch_assoc($id))
									{
										$partID = $row3['id'];
									}
									// Loop over each of the teams in the "owners" string to copy or update the checkout table
									// Previous error: part name $a[$i] was passed in
									// Fixed by Richard and Jonathan by adding the above ID fetcher
									$query = "INSERT INTO `checkout` ( `userId`, `team`, `partId`, `qtyOut`, `dateOut`, `dateUpdate`) VALUES ( $studId, $teamName, $partID, $qty, NOW(), NOW());";
									$result = mysql_query($query);
									if( !$result )
									{
										echo("Error: ".mysql_error());
										echo('<br />'.$query);
										die("Error");
									}
									// END NEW STUFF
									for($j=0;$j<count($b);$j++) {
										if($j>0) $c.=";";
										if($j==$teamNum) {
											$c.=max(0,min(intval($row2['quantity'])-$d,min(intval($row2['quantity']),intval($b[$j])+intval($a[$i+1]))));
										}
										else $c.=$b[$j];
									}
									/// Update query to update checked out quantities in the owner (aka borrower) field...
									mysql_query("UPDATE parts SET owners='".encode($c)."' WHERE name LIKE '%".encode(str_replace("~",",",$a[$i]))."%';");
								}
							}
						}
						echo '<script type="text/javascript">window.location.reload();</script>';
					}
					else echo '<div class="error">Team does not exist!</div><br/><br/><script type="text/javascript">document.getElementById("checkoutButton").onclick=function() {checkoutSubmit2(checkoutArray.join());}</script>';
				}
				else {
					echo 'Wrong admin credentials!<br/><script type="text/javascript">document.getElementById("checkoutButton").onclick=function() {checkoutSubmit2(checkoutArray.join());}</script>';
				}
				/*echo '<script type="text/javascript">alert("hi")</script>';*/
			}
			if(!$valid) {
				$a = array();
				$c = explode(",",$_POST['partlist']);
				echo '<div class="found">Your Cart:</div><div class="itemtop"></div>';
				$result = mysql_query("SELECT * FROM parts");
				while($row = mysql_fetch_assoc($result)) {
					for($i=0;$i<count($c);$i+=2) {
						if(trim(decode($row['name']))==str_replace("~",",",$c[$i])) {
							$b = array(trim(decode($row['name'])),$c[$i+1],decode($row['url']),decode(trim($row['type'])));
							array_push($a,$b);
							break;
						}
					}
				}
				usort($a,'sortByName');
				$i = 0;
				foreach($a as $key => $f) {
					if($f[2]=="") $f[2]="empty.png";
					$g = ' <span class="checkoutlink" onclick="checkout('.++$i.')" id="checkout'.$i.'">Checkout_?</span>';
					echo '<div class="item"><img class="image" src="'.$f[2].'" alt="" /><div class="listname"><span id="listname">'.$f[0].'</span><br/><div class="listdetails">You ordered '.'<input id="checkoutbox' . $key . '" class="checkout" type="text" value="' . $f[1] . '" onpaste="return false" onkeypress="return hi(event,' . $key . ')"></input>'.'.</div></div></div>';
					//return isNum(event,' . $key . ')
				}
			}
		}
		else if(isset($_POST['browse'])) {
			$a = array();
			$result = mysql_query("SELECT * FROM parts");
			while($row = mysql_fetch_assoc($result)) {
				if($_POST['browse']=="browse") {
					$e = true;
					for($i=0;$i<count($a);$i++) {
						$n = trim(decode($row['type']));
						if($a[$i][0]==$n) {
							$e = false;
							if($a[$i][1]=="")
								$a[$i][1]=decode($row['url']);
							$a[$i][2]++;
						}
					}
					if($e) {
						$b = array(trim(decode($row['type'])),decode($row['url']),1);
						array_push($a,$b);
					}
				}
				else {
					if(encode(trim(decode($row['type'])))==$_POST['browse']) {
						$b = array(decode($row['name']),$row['quantity'],decode($row['url']),decode(trim($row['type'])),decode(trim($row['owners'])));
						array_push($a,$b);
					}
				}
			}
			usort($a,'sortByName');
			if($_POST['browse']=="browse") {
				$i=0;
				echo '<form name="browseForm2" id="browseForm2" class="browseform2=" action="" method="POST"><input name="browse" type="hidden" value=""></input><div class="itemtop"></div>';
				foreach($a as $c) {
					$d = " items";
					if($c[2]==1) $d=" item";
					echo '<div class="category" onclick="browseIt(\''.encode($c[0]).'\');"><img class="image" src="'.$c[1].'" alt="" /><div class="listname">'.$c[0].'<br/><div class="listdetails">Contains '.$c[2]. $d . '.</div></div></div>';
				}
				echo '<div class="itembottom"></div></form>';
			}
			else {
				echo '<div class="itemtop"></div>';
				$i = 0;
				echo '<div class="category" onclick="$j(\'#browseForm\').submit();"><img class="image" src="http://www.veryicon.com/icon/png/System/Longhorn%20R2/Back%20Button.png" alt="" /><div class="listname">Back<br/><div class="listdetails">Brings you back to the Browse page.</div></div></div>';
				foreach($a as $f) {
					$j=0;
					$h=split(";",$f[4]);
					foreach($h as $k)
						$j+=intval($k);
					if($f[2]=="") $f[2]="empty.png";
					$g = ' <span class="checkoutlink" onclick="checkout('.++$i.')" id="checkout'.$i.'">Checkout?</span>';
					if($f[1]==0) $g = "";	
					echo '<div class="item"><img class="image" src="'.$f[2].'" alt="" /><div class="listname"><span id="listname'.$i.'">'.$f[0].'</span><br/><div class="listdetails">There are <span id="remaining'.$i.'">'.(intval($f[1])-$j).'</span>/'.$f[1].' remaining.'.$g.'</u></div></div></div>';
				}
				echo '<div class="itembottom"></div>';
			}
		}
		else if(strlen(trim($_POST['part']))<1) {
			echo 'Invalid search terms!';
		}
		else {
		$result = mysql_query("SELECT * FROM parts");
		$a = explode(" ",strtolower(trim($_POST['part'])));
		$d = array();
		while($row = mysql_fetch_assoc($result)) {
			$b = true;
			foreach($a as $c) {
				if(strpos(strtolower(decode($row['name'])),$c)===false) {
					$h = true;
					if(strlen($c) > 3)
					{
						if(substr($c, -1)=='s'&&strpos(strtolower(decode($row['name'])),substr($c, 0, -1))!==false)
							$h = false;
						if($h&&substr($c, -2)=='es'&&strpos(strtolower(decode($row['name'])),substr($c, 0, -2))!==false)
							$h = false;
						if($h&&substr($c, -3)=='ies'&&strpos(strtolower(decode($row['name'])),substr($c, 0, -3)."y")!==false)
							$h = false;
						if($h&&(strpos(strtolower(decode($row['name'])),$c."s")!==false||strpos(" ".strtolower(decode($row['name'])),$c."es")!==false))
							$h = false;
						if($h&&substr($c, -1)=='y'&&strpos(strtolower(decode($row['name'])),substr($c, 0, -1)."ies")!==false)
							$h = false;
					}
					if($h) {
						$b = false;
						break;
					}
				}
			}
			if($b||$_POST['part']=="*") {
				$e = array(decode($row['name']),$row['quantity'],decode($row['url']),trim(decode($row['type'])),trim(decode($row['owners'])));
				array_push($d,$e);
			}
		}
		usort($d,'sortByName');
		if(count($d)==0)
			$g = "There were no results found.";
		else
			if(count($d)==1)
				$g = "There was 1 result found:";
			else $g = "There were ".count($d)." results found:";
		echo '<div class="found">'.$g.'</div><div class="itemtop"></div>';
		$i=0;
		foreach($d as $f) {
			$j=0;
			$h=split(";",$f[4]);
			foreach($h as $k)
				$j+=intval($k);
			if($f[2]=="") $f[2]="empty.png";
			$g = ' <span class="checkoutlink" onclick="checkout('.++$i.')" id="checkout'.$i.'">Checkout?</span>';
			if($f[1]==0) $g = "";	
			echo '<div class="item"><img class="image" src="'.$f[2].'" alt="" /><div class="listname"><span id="listname'.$i.'">'.$f[0].'</span><br/><div class="listdetails">There are <span id="remaining'.$i.'">'.(intval($f[1])-$j).'</span>/'.$f[1].' remaining.'.$g.'</u></div></div></div>';
		}
		echo '<div class="itembottom"></div>';
	}
	}
	else {
		echo 'Database Error!';
	}
?>
