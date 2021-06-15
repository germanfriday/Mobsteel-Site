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
Name: CWGetLocales.php
Description: 
	This file shows all of the supported locales for the server
	processing the script. Use this file on a Unix or Linux server
	to determine the appropriate locale for your site.
================================================================
*/

ob_start();
system('locale -a'); // for all locales
$locales = ob_get_contents();
ob_end_clean();
$localeArray = explode("\n", $locales);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php if(isset($_GET["utf"])) { ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php }else{ ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php }?>
<title>List Locales</title>
<link rel="stylesheet" type="text/css" href="../../../cw2/assets/css/cartweaver.css">
<style>
table{border-collapse:collapse;}
td{padding:3px;}
</style>
</head>
<?php
// Format for the money
function cartweaverMoney($theNum) {
	$cwLocaleInfo = localeconv();
	return $cwLocaleInfo["currency_symbol"] . 
		number_format($theNum, 
			2, 
			$cwLocaleInfo["mon_decimal_point"],
			$cwLocaleInfo["mon_thousands_sep"]);
}

// Format the date for displays
function cwDateFormat($dateString, $short=false) {
	$format = "%c";
	if($short) {$format = "%x";}
	$time = strtotime($dateString);
	return strftime($format,$time);
}

function cwAltRow($recordNumber=0) {
	$recordNumber = intval($recordNumber);
	$class = ($recordNumber % 2 == 0) ? 'altRowEven' : 'altRowOdd';
	echo($class);
}

$somedate = date('Y/m/d');
$recCounter=0;
?>
<body>
<h1>Locales</h1>
<p>Supported locales on this server. 
<?php if(isset($_GET["utf"]))  echo("<strong>");?>
<a href="CWGetLocales.php?utf=1">utf-8</a>
<?php if(isset($_GET["utf"]))  echo("</strong>");?>
 / 
<?php if(!isset($_GET["utf"]))  echo("<strong>");?>
 <a href="CWGetLocales.php">iso-8859-1</a></p>
 <?php if(!isset($_GET["utf"]))  echo("</strong>");?>
<table>
	<tr><th>Locale Code</th><th>Money</th><th>Date</th><th>Date/Time</th></tr>
	<?php
	foreach($localeArray as $i) {
	if((stristr($i, "utf") && isset($_GET["utf"])) || (!stristr($i, "utf") && !isset($_GET["utf"]))){
	?><tr class="<?php cwAltRow($recCounter++);?>">
		<td><?php echo($i);?></td><?php setlocale(LC_ALL, $i);?>
		<td><?php echo(cartweaverMoney(1023.72));?></td>
		<td><?php echo(cwDateFormat($somedate,true));?></td>
		<td><?php echo(cwDateFormat($somedate,false));?></td>
	</tr>
<?php }
} ?>
</table>
</body>
</html>
  