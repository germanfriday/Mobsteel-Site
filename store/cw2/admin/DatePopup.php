<?php
include("application.php");

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
Name: DatePopup.php
Description: This is the calander used by the Order Search By Date $_POST[""]
================================================================
*/
/* Check to see user is logged in */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Select Date</title>
<link href="assets/admin.css" rel="stylesheet" type="text/css" />
<style type="text/css">
/*Calendar styles*/
table {
	width: 200px;
}
#calHead, #calHead td {
	border: none;
	padding: 4px;
}
#calDetails td {
	text-align: center;
}
.caltitle {
	text-align: center;
	font-weight: bold;
}
.caltitler {
	text-decoration: none;
	text-align: right;
}
.caltitlel {
	text-decoration: none;
	text-align: left;
}
#divMainContent {
	margin: 0px;
}
td.calForeignMonth {
	color: #CCCCCC;
}
body {
	text-align: center;
}
</style>
</head>
<body>
<?php

/* Set the month and year parameters to equal the current values 
  if they do not exist.	*/
function isDate($theField) {
	/*$dateArray = explode("/",$theField);
	return checkdate(intval($dateArray[1]),intval($dateArray[2]),intval($dateArray[0]));
	*/
	$date = strtotime($theField);
	if($date < 959832000) return false; // before 1970
	return checkdate(date("m",$date),date("d",$date),date("Y",$date));
}

$currentdate = getdate();
/* If the user had a date selected, move to the selected month */
if(isset($_GET["getDate"]) && $_GET["getDate"] != "" && isDate($_GET["getDate"])){
	/*$dateArray = explode("/",$_GET["getDate"]);
	$month = $dateArray[0];
	$year = $dateArray[2];*/
	$theDate = strtotime($_GET["getDate"]);
	$month = strftime("%m",$theDate);
	$year = strftime("%Y",$theDate);
}else{
	$month = isset($_GET["month"]) ? $_GET["month"] : $currentdate["mon"];
	$year = isset($_GET["year"]) ? $_GET["year"] : $currentdate["year"];
}
$month = (intval($month) > 0) ? intval($month) : $currentdate["mon"];
$year = (intval($year) > 1970) ? intval($year) : $currentdate["year"];

//Get locale-specific strings. Use 8/1/2004 because sunday falls on the 1st
$daysOfWeek = array();
for($i=1; $i<=7; $i++) {
	$tempdate = strtotime("8/$i/2004");
	array_push($daysOfWeek, strftime("%a",$tempdate));
}
$months = array();
for($i=1; $i<=12; $i++) {
	$tempdate = strtotime("$i/1/2004");
	array_push($months, strftime("%B",$tempdate));
}
/* //or just hard code the arrays
$daysOfWeek = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
*/
/* Set the requested (or current) month/year date and determine the 
  number of days in the month. */
$thisMonth = mktime (0,0,0, $month, 1, $year);  
$days = date('t', $thisMonth);

/* Set the values for the previous and next months for the back/next links. */
/* Set the values for the previous and next months for the back/next links. */
$lastMonth = mktime(0,0,0,$month - 1, 1, $year);
$lastMonthName = date("m", $lastMonth);
$lastMonthYear = date("Y", $lastMonth);

$nextMonth =  mktime(0,0,0,$month + 1, 1, $year);
$nextMonthName = date("m", $nextMonth);
$nextMonthYear = date("Y", $nextMonth);

$lastYear = $year - 1;
$nextYear = $year + 1;

$formName = $_GET["FormName"];
$fieldName = $_GET["FieldName"];
echo("
<script language=\"JavaScript\">
<!--

// function to populate the date on the form and to close this window. */
function cwShowDate(DateToShow) {
    var FormName=\"$formName\";
    var FieldName=\"$fieldName\";
    eval(\"self.opener.document.\" + FormName + \".\" + FieldName + \".value=DateToShow\");
    window.close();
}

//-->
</script>");

echo("<div id=\"divCalendar\">
<table id=\"calHead\" class=\"calHead\">        
	<tr class=\"heading\">");
/* Display the current month/year as well as the back/next links. */
echo("<td class=\"caltitle1\"><a href=\"DatePopup.php?month=$month&year=$lastYear&FormName=" . urlencode($formName) . "&FieldName=" . urlencode($fieldName) . "\">&laquo;</a> ");
echo("<a href=\"DatePopup.php?month=$lastMonthName&year=$lastMonthYear&FormName=" . urlencode($formName) . "&FieldName=" . urlencode($fieldName) . "\">&lt;</a></td>");
echo("<td  class=\"caltitle\">" . $months[$month-1] . " $year</td>");
echo("<td class=\"caltitler\"><a href=\"DatePopup.php?month=$nextMonthName&year=$nextMonthYear&FormName=" . urlencode($formName) . "&FieldName=" . urlencode($fieldName) . "\">&gt;</a> ");
echo("<a href=\"DatePopup.php?month=$month&year=$nextYear&FormName=" . urlencode($formName) . "&FieldName=" . urlencode($fieldName) . "\">&raquo;</a></td>");
echo("</tr></table>");
echo("<table id=\"calDetails\">\n\r  <tr>");
/* Display the day of week headers.  I've truncated the values to display only 
the first three letters of each day of the week.  */
for($i=0; $i<7; $i++){
	echo("<th>" . $daysOfWeek[$i] . "</th>");
}
echo("</tr>");
/* Set the ThisDay variable to 0.  This value will remain 0 until the day 
of the week on which the first day of the month falls on is reached. */
$thisDay = 0;
/* Loop through until the number of days in the month is reached.  */
while($thisDay <= $days) {
	echo("<tr>");
	/* Loop through each day of the week. */
	for($loopDay = 0; $loopDay <7; $loopDay++) {
	/* If ThisDay is still 0, check to see if the current day of the week 
	in the loop matches the day of the week for the first day of the 
	month. */
	/* If the values match, set ThisDay to 1. */
	/* Otherwise, the value will remain 0 until the correct day of 
	the week is found. */
		if($thisDay == 0) {
		//$temp = mktime (0,0,0, $month, $day, $year);  
			if(date("w",$thisMonth) == $loopDay) {
				$thisDay=1;
			}
		} /* end if($thisDay == 0)  */
		/* If the ThisDay value is still 0, or it is greater than the number 
		of days in the month, display nothing in the column. */
		/* Otherwise, display the day of the month and increment the value. */
		if(($thisDay != 0) && ($thisDay <= $days)){
			$theCurrentDate = cwDateFormat($month . "/" . $thisDay . "/" . $year,true);
			echo("<td class=\"calDay\"> <a href=\"javascript:cwShowDate('$theCurrentDate')\">$thisDay</a> </td>");
			$thisDay++;
		}else{
			echo("<td  class=\"calForeignMonth\">&nbsp;</td>");
		} /* end if(($thisDay != 0) && ($thisDay <= $days)) */
	} /* end for($loopDay = 0; $loopDay <7; $loopDay++)  */
	echo("</tr>");
} /* end while($thisDay <= $days)  */
echo("</table>");
echo("</div>");?>
</body>
</html>