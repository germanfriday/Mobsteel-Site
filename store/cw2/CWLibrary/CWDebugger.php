<?php
/*
================================================================
Application Info: 
Cartweaver© 2002 - 2005, All Rights Reserved.
Developer Info: 
	Application Dynamics Inc.
	1560 NE 10th
	East Wenatchee, WA 98802
	Support Info: http://www.cartweaver.com/go/phphelp

Cartweaver Version: 2.1  -  Date: 08/07/2005
================================================================
PHP debugger written by Tom Muck, works with DB classes and other variables.
the $queries global variable allows the  DB object to store 
queries and results to be displayed by this function

Debugger is turned on/off by setting a session variable:

// Set up debugging for the session
if(!isset($_SESSION["debug"])) $_SESSION["debug"] = false;
if($cartweaver->settings->cwDebug == true) {
	$debugger = "ON"; // Variable for links
	if(isset($_GET['debug']) && $_GET["debug"] == $cartweaver->settings->debugPassword) {
		$_SESSION['debug'] = !($_SESSION['debug']);
	}
	if($_SESSION["debug"] == true) {
		$cartweaver->db->debug = $_SESSION["debug"];
		if($_SESSION["debug"] == true) {
			$debugger = "OFF"; // Variable for links
		}
		require_once("debug.php");
	}
}

For Cartweaver, the debugger is set up for use in the application.php file
Set up the debugger password in the cwGlobalSettings.php file (debugPassword)

Turn on debugger with a URL variable appended to any page in the site:

http://www.mycartweaversite.com/index.php?debug=yourpassword

A subsequent call to any page in the site using the password will turn
debugger off

The debugger is called like this:

cwDebugger($objectname);


Additionally, you can send messages to the debugger like this:

<?php
if($_SESSION["debug"] == true) {
    cwDebugger("Just executed foo");
}
?>

or

<?php
cwDebugger("Just executed foo");
?>

The message will print with the line number:

LINE 137:
  Just executed foo
  
If debugging is not enabled, calls to cwDebugger will be ignored
*/
function expand($blah,$type) {
	echo("<h2>$type</h2>\n");
	echo("<table>\n");
	echo("<tr><th>Var</th><th>Value</th></tr>\n");		
	while (list($key,$val) = each($blah)){
		if(is_object($val)) {
			echo("<tr><td>&nbsp;</td><td>");
			expand($val, $type);
			echo("</td></tr>");
		}
		else if(is_array($val)) {
			echo ("<tr><td>$key</td><td>$val</td></tr>\n");
			echo("<tr><td>&nbsp;</td><td><table>");
			echo("<tr><th>Var</th><th>Value</th></tr>\n");
			if(count($val) > 0) {	
				while(list($keyArray, $valArray) = each($val)) {
					//if(is_array($valArray)) expand($valArray, "$valArray");
					echo ("<tr><td>$key" . "[$keyArray]</td><td>$valArray</td></tr>\n");
				}
			}else{
				echo("<tr><td>(empty)</td><td>&nbsp;</td></tr>");
			}
			echo("</table></td></tr>");
		}else{
			if($val == "") $val = "(empty)";
			echo ("<tr><td>$key</td><td>$val</td></tr>\n");
		}
	}
	echo("</table><br>\n");
	echo("<hr>");
}
$cwDebuggerStrings = array();
function cwDebugger($object = array()) {
	global $queries;
	global $cwDebuggerStrings;
	// If a string is passed in, add to array and quit
	if(is_string($object)) {
		$temp = debug_backtrace();
		array_push($cwDebuggerStrings,"LINE: " . $temp[0]["line"] . "  " . $temp[0]["file"]);
		array_push($cwDebuggerStrings,"  " . $object);
		return;
	}
	if(is_array($object)) {
		$temp = debug_backtrace();
		array_push($cwDebuggerStrings,"LINE: " . $temp[0]["line"] . "  " . $temp[0]["file"]);
		array_push($cwDebuggerStrings,$object);
		return;
	}
	
	// First, set up some CSS for the debugger
	echo("<style>
	#divDebugger {font-face:Verdana, Arial, sans-serif; font-size:.85em;display:none;position:absolute; top:100px;}
	#divDebugger table {border:1px solid black;border-collapse:collapse;}
	#divDebugger th {background-color:#ccc;padding:2px; border-left:1px solid black;color:black}
	#divDebugger td {padding:2px; border-left:1px solid black}
	</style>");
	echo("<div id=\"divDebugger\" onDblClick=\"repositionDebugger()\">");
	echo("<h1>Cartweaver Debugger</h1>");
	echo("<hr/>");
	if(isset($cwDebuggerStrings) && count($cwDebuggerStrings) > 0){
		echo("<h2>Messages</h2>\n");
		echo("<p>");
		while (list($key,$val) = each($cwDebuggerStrings)) {
			if(is_array($val)) {
				expand($val,"Array");
			}else{
				echo($val);
			}
			echo("<br>");
		}
		echo("</p>");
	}
	
	if(isset($queries)){
		echo("<h2>Queries</h2>\n");
		while (list($key,$val) = each($queries)) {
			echo ("<p style=\"width:500px;\">" . $key . ": " . nl2br($val[0]) . "</p>\n");
			if(stristr($val[0], 'select ')) {
				if($object->db->db_num_rows($val[1]) > 0) {
					$object->db->db_data_seek($val[1],0);
					echo("<table>");
					$temp = $object->db->db_fetch_assoc($val[1]);
					echo("<tr>");
					while (list($row,$column) = each($temp)) {
							echo("<th>" . $row . "</th>");
					}
					reset($temp);
					do {
						echo("<tr>");
						while (list($row,$column) = each($temp)) {
							echo("<td>" . $column . "</td>");
						}
						echo("</tr>\n");
					} while ($temp = $object->db->db_fetch_assoc($val[1])); 
					echo("</table><br>\n");
				}
			}			
		}		
		echo("<hr>");
	}
	
	$temp = get_defined_vars();
	if($temp) {
		expand($temp,"Page Variables");
	}
	
	if(isset($_GET)){
		expand($_GET, "URL Variables");
	}
	if(isset($_POST)) {
		expand($_POST, "Form Variables");
	}
	if(isset($_SESSION)) {
		expand($_SESSION, "Session Variables");
	}
	if(isset($_ENV)) {
		expand($_ENV, "Environment Variables");
	}
	if(isset($_COOKIE)) {
		expand($_COOKIE, "Cookie Variables");
	}
	if(isset($_FILES)) {
		expand($_FILES, "File Variables");
	}
	
	expand($object, "Object");
	$methods = get_class_methods($object);
	echo("<h2>Class Methods</h2>\n");
	echo("<table>\n");
	echo("<tr><th>Var</th><th>Value</th></tr>\n");		
	while (list($key, $val) = each($methods)) {
		if($val == "") $val = "(empty)";
		echo ("<tr><td>$key</td><td>$val</td></tr>\n");
	}
	echo("</table>\n");
	
	$props = get_object_vars($object);
	echo("<h2>Class Variables</h2>\n");
	echo("<table>\n");
	echo("<tr><th>Var</th><th>Value</th></tr>\n");		
	while (list($key, $val) = each($props)) {
		if($val == "") $val = "(empty)";
		echo ("<tr><td>$key</td><td>$val</td></tr>\n");
	}	
	echo("</table>\n");
	
	/* The following JavaScript will move the debugger div to the bottom 
		of the page after all other divs by calculating the height of all 
		existing divs and moving the div below the others */
	echo "</div>\n";
		echo("<script>
	function repositionDebugger(){
		var allDivs = document.getElementsByTagName(\"div\");
		var maxBottom = 0;
		var divBottom = 0;
		for(var i=0; i<allDivs.length; i++) {
			if(allDivs[i].id != \"divDebugger\"){
				divBottom = allDivs[i].offsetTop + allDivs[i].offsetHeight;
				if (divBottom > maxBottom){
					maxBottom = divBottom;
				}
			}
		}
	
		if(maxBottom < document.body.offsetHeight) {
			maxBottom = document.body.offsetHeight;
		}
		maxBottom = maxBottom + 100;
		var debugDiv = document.getElementById(\"divDebugger\");
		debugDiv.style.top = maxBottom + \"px\";	
		debugDiv.style.display = \"block\";
	}
	repositionDebugger();
	</script>

	");

}// End cwDebugger()

?>