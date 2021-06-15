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

Cartweaver Version: 2.4  -  Date: 11/27/2005
================================================================
Name:  CWIncFunctions.php
Description:  This file contains commonly used functions within
the Cartweaver store. The file is included in the application.php
file. Functions contained within are documented in the Cartweaver 
documentation and can be used anywhere in the site.
================================================================

*/
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
	if($dateString && $dateString != "") {
		$format = "%c";
		if($short) {$format = "%x";}
		$time = strtotime($dateString);
		return strftime($format,$time);
	}
	return "";
}

//Format date for insert to mysql
function mySQLDate($dateString, $short=false) {
	if($short) {
		return date('Y/m/d', strtotime($dateString));
	}else{
		return date('Y/m/d H:i:s', strtotime($dateString));
	}
}

// Send an email using the CWMail class
function sendEmail($to,$from,$subject,$text,$html=null) {
	$email = new CWMail();
	$email->setTo($to);
	$email->setFrom($from);
	$email->setSubject($subject);
	$email->setText($text);
	if($html) $email->setHtml($html);
	$email->send();	
}

function getPageName($path) {
	$tempArray = explode("/",$path);
	return array_pop($tempArray);
}

/* Call like this: 
	class="<?php cwAltRow($recCounter++);?>"  */
function cwAltRow($recordNumber=0) {
	$recordNumber = intval($recordNumber);
	$class = ($recordNumber % 2 == 0) ? 'altRowEven' : 'altRowOdd';
	echo($class);
}

// Return file path in Windows or Unix
function localPath() {
    if(isset($_SERVER['DOCUMENT_ROOT'])) {
    	$siteRoot = $_SERVER["DOCUMENT_ROOT"] .  dirname($_SERVER["PHP_SELF"]);
    }else{
    	$siteRoot = dirname($_SERVER["PATH_TRANSLATED"]);
    }
    $siteRoot = str_replace("\\\\","/",$siteRoot);
	$siteRoot = str_replace("\\","/", $siteRoot);
    return $siteRoot;
}

// Get file path (for Linux and Windows)
function expandPath($file) {
	return localPath() . "/" . $file;
}

/*Return a list of values from a multi-dimensional array
given an array and the key (field) */
function arrayValueList($array, $field) {
	$theList = "";
	if(is_array($array))
		foreach($array as $key => $value) {
			if($theList != "") $theList .= ",";
			$theList .= $array[$key][$field];
		};
	return $theList;
}

/* Return an array of values from a multi-dimensional array
given an array and the key (field) */
function arrayKeyToArray($array, $field) {
	$newArray = array();
	if(is_array($array))
		foreach($array as $key => $value) {
			array_push($newArray, $array[$key][$field]);
		};
	return $newArray;
}

/* find an item in an array 
// Usage:
//  if(arrayFind($theArray, $theItem) != -1) { // do something }
*/
function arrayFind($array, $item) {
	if(is_array($array)) 
		foreach($array as $key => $value) {
			if($item == $value) {
			   return $key;
			}
		}
	return -1;
}

/*Remove an item from an array */
function arrayRemove($array, $field, $item) {
	if(is_array($array))
		for($i=count($array)-1; $i>=0; $i--){
			if($array[$i][$field]==$item) unset($array[$i]);
		}
	return $array;
}
?>
