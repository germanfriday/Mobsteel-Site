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

Name: CWError.php

Description: Main error handler for Cartweaver site

================================================================

*/





function initErrors() {

	$cwerrors = array();

	/* Set the following line to a default error page*/

	$cwerrors["errorpage"] = "error.php";

	/* Set the following line to a default location in your server

	to log all errors or leave blank for no logging */

	$cwerrors["errorlog"] = "";

	/* Set the following line to an email address for the 

	errors to be sent, or leave blank for no notification*/

	$cwerrors["erroremail"] = "chris@thegermanfriday.com";

	/* Set the following variable to true to pass error message in query 

	string to error page. SET TO false FOR LIVE SITE*/

	$cwerrors["errorshowerrors"] = false;

	return $cwerrors;

}



// Handles fatal errors. Does not work in newer versions of PHP

function fatal_error_handler($buffer) {

	if (ereg("(error<\/b>:)(.+)(<br)", $buffer, $regs) ) {

		$err = preg_replace("/<.*?>/","",$regs[2]);

		$err = "$err\n";

		error_log($err,3,"c:\cwerrors.txt");

		header("Location: error.php?error=$err");

		exit(0);

	}

	return $buffer;

}



function handle_error ($errno, $errstr, $errfile, $errline, $vars){

	// save to the error log

	$cwerrors = initErrors();

	$cwErrorTime = date("Y-m-d H:i:s");

	$cwErrorPage = $cwerrors["errorpage"];

	$cwErrorQuerystring = ($cwerrors["errorshowerrors"]) ? "?error=" . urlencode($errstr) : "";

	$cwErrorMessage = "$errstr in $errfile on line $errline at $cwErrorTime\n";

	if($cwerrors["errorlog"] != "") 

		error_log($cwErrorMessage,3,$cwerrors["errorlog"]);

	if($cwerrors["erroremail"] != "") {

		$cwErrorHeader = "From: " . $cwerrors["erroremail"];

		mail($cwerrors["erroremail"], "Error at $errfile", $cwErrorMessage, $cwErrorHeader);

	}

	$errstr = urlencode($errstr);

	header("Location: $cwErrorPage$cwErrorQuerystring");

	exit(0);

}



ob_start("fatal_error_handler");

set_error_handler("handle_error");

?>

