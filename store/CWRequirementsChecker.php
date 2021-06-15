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

Cartweaver Version: 2.0  -  Date: 07/22/2005
================================================================
Name: CWRequirementsChecker.php
Description: This file checks your database and PHP versions to 
	make sure you have the required versions to run Cartweaver.
================================================================
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Check Versions for Cartweaver</title>
<style type="text/css">
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/* Cartweaver 2 CSS HTML Styles */
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
body, td, th, p {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
}

a:link {
	color: #345F81;
}
a:visited {
	color: #425929;
}
a:hover {
	color: #8CA26B;
	text-decoration:none;
}
a:active {
	color: #76685D;
	text-decoration:none;
}

hr{
	color: #76685D;
	height: 1px;
	width: 90%;
}

h1 {
	color: #425929;
	font-size: 14px;
	margin: 0px;
	border-bottom:3px double #8CA26B;
}

h2 {
	color: #76685D;
	font-size: 18px;
}

/*Form Elements*/
form {
	margin: 0px;
}

input, select, textarea {
	font-family: Verdana, Arial, Helvetica, sans-serif;
 	font-size: 11px;
	color: #425929;
	border: 1px inset #A1978F;
}

/*~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/* Cartweaver 2 CSS Classes */
/*~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/*
NOTE: All styles below are specific to Cartweaver 2.
You may use the CSS above, or remove it and attach
your own CSS file in addition to cartweaver.css
*/

/*Form Styles*/
input.formCheckbox {
	border: none;
	background: transparent;
}

input.formButton {
	background: #425929;
	border-bottom: 1px solid #688C40;
	border-left: 1px solid #688C40;
	border-right: 1px solid #141C0D;
	border-top: 1px solid #141C0D;
	color: #FFFFFF;
	font-weight: bold;
	margin: 3px 3px 0px 3px;
}

/* Tables used to display tabular Data. */
/* Apply to <table> tags only. 
Example: <table class="tabulardata">*/
table.tabularData, table.tabularData td, table.tabularData th {
	border: 1px solid #76685D;
	border-collapse: collapse;
}
table.tabularData td, table.tabularData th{
	padding: 3px;
}
table.tabularData th, table.tabularData th a:link, table.tabularData th a:visited, table.tabularData th a:hover, table.tabularData th a:active  {
	background: #A1978F;
	color: #FFFFFF;
}

/* altRowEven & altRowOdd are used to style alternating table rows. */
.altRowEven {
	background-color: #E7E4E2;
}
.altRowOdd{
	background-color: #DFDBD9;
}

.smallprint {
	font: 10px;
}

.pagingLinks {
	text-align: center;
}

.errorMessage {
	color: #993333;
	font-weight: bold;
}
table {
	width: 50%;
}
caption {
	margin-top: 1em;
	margin-bottom: .25em;
	text-align: left;
	font-weight: bold;
}
</style>
</head>
<body>
<h1>Cartweaver Requirements Checker</h1>
<?php 
function cwAltRow($recordNumber=0) {
	$recordNumber = intval($recordNumber);
	$class = ($recordNumber % 2 == 0) ? 'altRowEven' : 'altRowOdd';
	echo($class);
}

if(isset($_POST["username"])) { 
?>
<p>The following are the test results for PHP and MySQL versions, and other requirements for Cartweaver to function correctly. </p>
<?php 
	error_reporting(0);
	$success = true;
	$connect = mysql_connect($_POST["host"], $_POST["username"], $_POST["password"]);
	if(!$connect) echo("<p class='errorMessage'>Connection error: " . mysql_error() . "</p>");
	if(mysql_error() == "Client does not support authentication protocol requested by server; consider upgrading MySQL client") {
		echo("<p class='errorMessage'>You have a version of PHP that may not be compatible with your version of MySQL. Please visit the following sites for more information:
<br>
<a href='http://us3.php.net/mysql'>PHP/MySQL setup page</a><br>
(Note that in PHP 5 there is no MySQL support by default).<br><br>
<a href='http://dev.mysql.com/doc/mysql/en/old-client.html'>MySQL Technote</a><br><br>
<a href='http://www.macromedia.com/cfusion/knowledgebase/index.cfm?id=c45f8a29'>Macromedia Technote</a><br>
 </p>");
	}
	$requirements = array();
	$requirements["phpversion"] = array(phpversion(), false, "PHP Version", "4.3.11");
	$requirements["mysqlclientversion"] = array(mysql_get_client_info(), false, "MySQL Client Version", "3.23");
	$requirements["mysqlserverversion"] = array(mysql_get_server_info(), false, "MySQL Server Version", "4.0");
	//$requirements["mailserver"] = array(mail('','',''),false, "Mail Server working","n/a");
	$requirements["fileupload"] = array(ini_get("file_uploads"), false, "File Upload Enabled", "1");
	//$requirements["globals"] = array(ini_get("register_globals"), false, "Globals off","n/a");
	//$requirements["uploadmax"] = array(ini_get("upload_max_filesize"),true, "Max file upload","Information only");
	
	$connect = mysql_connect('localhost', 'mysql_user', 'mysql_password');
	if($requirements["phpversion"][0] > "4.3.10") {
		$requirements["phpversion"][1] = true;
	}else{
		$success = false;
	}
	if($requirements["mysqlclientversion"][0] > "3.23") {
		$requirements["mysqlclientversion"][1] = true;
	}else{
		$success = false;
	}
	if($requirements["mysqlserverversion"][0] > "4.0.0") {
		$requirements["mysqlserverversion"][1] = true;
	}else{
		$success = false;
	}
	if($requirements["fileupload"][0] == "1") {
		$requirements["fileupload"][1] = true;
	}else{
		$success = false;
	}
	
	$recCounter = 0;
if($success == false) { ?>
<h2>We're sorry</h2>
<p>We're sorry, but your server does not currently have all of the necessary components to run Cartweaver 2 PHP. Please consult the version and requirement list below, and contact your host to see if they are able to support the requirements.</p>
<?php 
	}else{ ?>
<h2>Congratulations!</h2>
<p>Your server meets all of the requirements to run Cartweaver 2 PHP. If you have not yet purchased Cartweaver, please visit <a href="http://www.cartweaver.com/">Cartweaver.com</a> to buy today.</p>
<?php 
	} 
?>
<table class="tabularData">
  <tr>
    <th>Description</th>
    <th>Requirement</th>
    <th>Value</th>
    <th>Pass/Fail</th>
  </tr>
  <?php 
foreach($requirements as $requirement=>$value) { ?>
  <tr class="<?php cwAltRow($recCounter++);?>">
    <td><?php echo($value[2]);?></td>
    <td><?php echo($value[3]);?></td>
    <td><?php echo($value[0]);?></td>
    <td><?php echo(($value[1] == true) ? "Pass" : "<span class='errorMessage'>Fail</span>");?></td>
  </tr>
  <?php } ?>
</table>
<?php 
}else{
	?>
<p>Complete the following form using your MySQL database information to test your server for compatibility with Cartweaver 2 PHP.</p>
<form name="form1" method="post" action="">
  <label>Host
  <br>
  <input type="text" name="host">
  <br></label>
  <label>Username
  <br>
  <input type="text" name="username">
  <br></label>
  <label>Password
  <br>
  <input type="password" name="password"></label>
  <input name="Submit" type="submit" class="formButton" value="Submit">
</form>
<?php
}?>
<p>The following is a list of requirements for Cartweaver to function correctly. The server must meet all requirements for all features of the program to work.*</p>
<ul>
  <li>PHP Version: 4.3.11</li>
  <li>MySQL Version: 4.0</li>
  <li>File uploading enabled</li>
</ul>
<p class="smallprint">* All requirements must be met in order to receive support. Use Cartweaver at your own risk if your server does not meet all requirements. 
<p>Cartweaver by Application Dynamics Inc. &copy; 2002-<?php echo(date("Y"));?> All rights reserved. </p>
</body>
</html>
