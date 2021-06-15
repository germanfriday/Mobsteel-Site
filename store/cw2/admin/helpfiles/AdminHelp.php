<?php require_once("../../../application.php");

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
Name: AdminHelp.php
================================================================

*/

/* Verify the user is logged in. */
if ($cartweaver->thisPageName != "AdminHelp.php" && !isset($_SESSION["LoggedIn"])){
	$strURL = $_SERVER["SCRIPT_NAME"] ;
	$tempQuerystring = isset($_SERVER["QUERY_STRING"]) ? str_replace("logout=true","",$_SERVER["QUERY_STRING"]) : "";
	$strURL = ($tempQuerystring != "") ? $strURL . "?" . $tempQuerystring : $strURL;
	header("Location: ../index.php?accessdenied=$strURL"); 
	exit();
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cartweaver Admin Help</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../assets/help.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php include("inc_HelpHeader.php");/* Header Include */?>
<?php include("inc_HelpNav.php");/* Help Navigation */?>
<div id="content">
<?php /* Help File Body Include, populated by helpFileName variable */
$helpFileName = isset($_GET["helpFileName"]) ? $_GET["helpFileName"] : "AdminHome.php";
include("help_" . $helpFileName);?>
</div>

<?php include("inc_HelpFooter.php");/* Footer Include */?>
</body>
</html>
<?php
if($_SESSION["debug"] == true) {
    cwDebugger($cartweaver);
}
?>
