<?php 
session_start();
if(!isset($_SESSION['authenticated']))
{
	$_SESSION['denied'] = 1;
	header('Location: index.php');
}
else
{
	if(isset($_SESSION['denied']))
		unset($_SESSION['denied']);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Team 254</title>
		<link rel="stylesheet" type="text/css" href="team254.css" />
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.min.js"></script>
		<script type="text/javascript">
			var $j = jQuery.noConflict();
		</script>
		<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.min.js"></script>
		<script type="text/javascript">
			$j(document).ready(function(){
				setForm();
			});
			var delay=0;
			var curCheckout = 1;
			var checkoutArray = [];
			function disableEnterKey(e)
			{
     				var key;
     				if(window.event)
          				key = window.event.keyCode;     //IE
     				else
          				key = e.which;     //firefox
     				if(key == 13)
          				return false;
     				else
          				return true;
			}
			function setForm()
			{
				$j("#searchForm").validate({
					submitHandler: function(form) {
						$j.post('process.php', $j("#searchForm").serialize(), function(data) {
							document.getElementById("checkoutButton").onclick=function() {checkoutSubmit(checkoutArray.join());}
							$j("#list").animate({
								opacity:0
							},delay, function(){
								delay=500;
								$j('#list2').html(data);
								fixHTML();
								$j("#list").animate({opacity:1,height:$j("#list2").height()},500);
							});
						});
					}
				});
				$j("#browseForm").validate({
					submitHandler: function(form) {
						$j.post('process.php', $j("#browseForm").serialize(), function(data) {
							document.getElementById("checkoutButton").onclick=function() {checkoutSubmit(checkoutArray.join());}
							$j("#list").animate({
								opacity:0
							},delay, function(){
								delay=500;
								$j('#list2').html(data);
								$j("#list").animate({opacity:1,height:$j("#list2").height()},500);
							});
						});
					}
				});
			}
			function browseIt(section) {
				$j.post('process.php', "browse="+section, function(data) {
							document.getElementById("checkoutButton").onclick=function() {checkoutSubmit(checkoutArray.join());}
							$j("#list").animate({
								opacity:0
							},delay, function(){
								delay=500;
								$j('#list2').html(data);
								fixHTML();
								$j("#list").animate({opacity:1,height:$j("#list2").height()},500);
							});
						});
			}
			function checkoutSubmit(partlist) {
				document.getElementById("checkoutdetails").style.display = "block";
				$j.post('process.php', "partlist="+partlist, function(data) {
							document.getElementById("checkoutButton").onclick=function() {checkoutSubmit2(checkoutArray.join());}
							$j("#list").animate({
								opacity:0
							},delay, function(){
								delay=500;
								$j('#list2').html(data);
								fixHTML();
								$j("#list").animate({opacity:1,height:$j("#list2").height()},500);
							});
						});
			}
			function checkoutSubmit2(partlist) {
				document.getElementById("checkoutdetails").style.display = "block";
				$j.post('process.php', "final=true&teamname="+document.getElementById("teamname").value+"&adminname=ADMIN"+"&adminpass=PASS"+"&partlist="+partlist, function(data) {
							document.getElementById("checkoutButton").onclick=function() {checkoutSubmit(checkoutArray.join());}
							$j("#list").animate({
								opacity:0
							},delay, function(){
								delay=500;
								$j('#list2').html(data);
								fixHTML();
								$j("#list").animate({opacity:1,height:$j("#list2").height()},500);
							});
						});
			}
			function fixHTML() {
				var i=1;
				while(document.getElementById("listname"+i)!=null) {
					for(var j=0;j<checkoutArray.length;j+=2) {
						if(document.getElementById("listname"+i).innerHTML==checkoutArray[j]) {
							var newVal = parseInt(document.getElementById("remaining"+i).innerHTML)-checkoutArray[j+1];
							document.getElementById("remaining"+i).innerHTML = newVal;
							if(newVal<1)
								document.getElementById("checkout"+i).innerHTML = '';
							break;
						}
					}
					i++;
				}
			}
			function getInputSelection(el) {
			    var start = 0, end = 0, normalizedValue, range,
			        textInputRange, len, endRange;
			    if (typeof el.selectionStart == "number" && typeof el.selectionEnd == "number") {
			        start = el.selectionStart;
			        end = el.selectionEnd;
			    } else {
			        range = document.selection.createRange();
			        if (range && range.parentElement() == el) {
			            len = el.value.length;
			            normalizedValue = el.value.replace(/\r\n/g, "\n");
						textInputRange = el.createTextRange();
			            textInputRange.moveToBookmark(range.getBookmark());
						endRange = el.createTextRange();
			            endRange.collapse(false);
						if (textInputRange.compareEndPoints("StartToEnd", endRange) > -1) {
			                start = end = len;
			            } else {
			                start = -textInputRange.moveStart("character", -len);
			                start += normalizedValue.slice(0, start).split("\n").length - 1;
							if (textInputRange.compareEndPoints("EndToEnd", endRange) > -1) {
			                    end = len;
			                } else {
			                    end = -textInputRange.moveEnd("character", -len);
			                    end += normalizedValue.slice(0, end).split("\n").length - 1;
			                }
			            }
			        }
			    }
				return {
 			       start: start,
 			       end: end
 			   };
			}
			function isNum(evt,int)
      		{
				var selection = getInputSelection(document.getElementById("checkoutbox"+int));
    			var charCode = (evt.which) ? evt.which : event.keyCode
				if(charCode == 13) {
					// theVal is the quantity the customer wants to check out
					// newVal is the new remaining balance
					var theVal = parseInt(document.getElementById("checkoutbox"+int).value);
					var newVal = parseInt(document.getElementById("remaining"+int).innerHTML)-theVal;
					if(newVal>0) {
						document.getElementById("checkout"+int).innerHTML = 'Checkout?';
						var oldCur = int;
						document.getElementById("checkout"+int).onclick = function() {checkout(oldCur);};
						$j('#checkout'+int).addClass('checkoutlink');
					}
					else {
						document.getElementById("checkout"+int).innerHTML = '';
					}
					i = true;
					var name = document.getElementById("listname"+int).innerHTML;
					for(var j=0;j<checkoutArray.length;j+=2) {
						if(name==checkoutArray[j]) {
							i = false;
							checkoutArray[j+1]+=theVal;
						}
					}
					if(i)
						checkoutArray.push(name.replace(",","~"),theVal);
					document.getElementById("remaining"+int).innerHTML = newVal;
					document.getElementById("checkoutdiv").style.display = "block";
					var s1 = " items";
					var s2 = "are ";
					if(checkoutArray.length==2) {
						s1 = " item";
						s2 = "is ";	
					}
					document.getElementById("cartitems").innerHTML = s2+(checkoutArray.length/2)+s1;
					return true;
				}
				else if (charCode > 31 && charCode!=45 && (charCode < 48 || charCode > 57))
					return false;
				else if(charCode==45||(charCode > 47 && charCode < 58))
					if(charCode!=45) {
						if(selection.start==selection.end) {
							if(parseInt(document.getElementById("checkoutbox"+int).value+(charCode-48))>parseInt(document.getElementById("remaining"+int).innerHTML))
								return false;
						}
						else {
							var text = document.getElementById("checkoutbox"+int).value;
							text = text.substring(0,selection.start)+(charCode-48)+text.substring(selection.end);
							if(parseInt(text)>parseInt(document.getElementById("remaining"+int).innerHTML))
								return false;
						}
					}
					
				return true;
			}
			function checkout(int) {
				if(document.getElementById("checkout"+curCheckout)==null) curCheckout = 1;
				document.getElementById("checkout"+curCheckout).innerHTML = 'Checkout?';
				var oldCur = curCheckout;
				document.getElementById("checkout"+curCheckout).onclick = function() {checkout(oldCur);};
				$j('#checkout'+curCheckout).addClass('checkoutlink');
				curCheckout = int;
				document.getElementById("checkout"+int).innerHTML = 'How Many? <input id="checkoutbox'+int+'" class="checkout" type="text" onpaste="return false" onkeypress="return isNum(event,'+int+')"></input>';
				document.getElementById("checkout"+int).onclick = function() {};
				$j('#checkout'+int).removeClass('checkoutlink');
			}
			function viewParts(team) {
				$j.post('process.php', "viewteam="+team, function(data) {
							document.getElementById("checkoutButton").onclick=function() {checkoutSubmit2(checkoutArray.join());}
							$j("#list").animate({
								opacity:0
							},delay, function(){
								delay=500;
								$j('#list2').html(data);
								fixHTML();
								$j("#list").animate({opacity:1,height:$j("#list2").height()},500);
							});
						});
			}
		</script>
	</head>
	<body>
		<div class="main">
			<a href="main.php"><div class="headerimage"></div></a>
			<div class="skylineimage">
				<div class="title">Team 254 Inventory System</div>
			</div>
			<div class="content"><br/>
				<div class="instruct">Search for Parts:</div>
				<form name="searchForm" id="searchForm" action="" method="POST">
					<input class="field" type="text" name="part"/>
				</form><div class="browseinstruct"><form name="browseForm" id="browseForm" class="browseform" action="" method="POST"><input name="browse" type="hidden" value="browse"></input>(Or <a href="#" onclick="$j('#browseForm').submit();">browse</a> parts)</form></div>
				<div class="list" id="list">
					<div class="list2" id="list2">
					
					</div>
				</div><br/>
				<div class="checkout" id="checkoutdiv" style="display:none">
					<div style="margin:0px auto;width:625px;height:0px;border-top:2px solid #CECECE;border-bottom:1px #666;padding:0px 25px 0px 0px"></div>
					<br/>
					<form name="checkoutForm" id="checkoutForm" class="browseform" action="" method="POST"><div class="checkoutdetails" id="checkoutdetails" style="margin:0px auto;width:625px;font-family:"Futura LT";font-size:20pt;display:none;">
						<!-- Pass field was changed into password type by Richard -->
						<font face=FuturaLT>Team Name:&nbsp;&nbsp;&nbsp;&nbsp;<input class="field2" type="text" id="teamname" onKeyPress="return disableEnterKey(event);"/></font><br/><div style="height:10px;"></div>
					</div>
					<div class="item" id="checkoutButton" onclick="checkoutSubmit(checkoutArray.join())"><img class="image" src="checkout.jpg" alt="" /><div class="listname">Checkout<br/><div class="listdetails">There <span id="cartitems">are 0 items</span> in your cart.</div></div></div></form>
				</div>
			</div>
                
		</div>
		<div class="footer">
			<div style="text-decoration:underline;font-size:24px;font-family:FuturaLT;color:#DDD">View my Team's Checked Out Parts</div>
                <form action="viewTeam.php" method="get">
				<select name="team" onChange="this.form.submit()">
					<option value="none">Pick a Team...</option>
					<option value="a">Team A</option>
					<option value="b">Team B</option>
					<option value="c">Team C</option>
					<option value="d">Team D</option>
					<option value="e">Team E</option>
					<option value="f">Team F</option>
					<option value="g">Team G</option>
					<option value="bad">Bad</option>
				</select>
				</form>
				<br />
				
			<div align="center">
			<form action="logout.php" method="post">
				<input type="submit" name="logout" value="Logout" />
				</form>
			</div>
		</div>
	</body>
</html>
