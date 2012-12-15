$j(document).ready(function(){
				setForm();
			});
			var delay=0;
			var curCheckout = 1;
			var checkoutArray = [];
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
				$j.post('process.php', "final=true&teamname="+document.getElementById("teamname").value+"&adminname="+document.getElementById("adminname").value+"&adminpass="+document.getElementById("adminpass").value+"&partlist="+partlist, function(data) {
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
