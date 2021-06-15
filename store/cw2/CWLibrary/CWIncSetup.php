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

Cartweaver Version: 2.3  -  Date: 09/18/2005
================================================================
Name: CWIncSetup.php
Description:
	This file is included in the application.php page, which is
	included at the top of every cartweaver page. 
	Creates session, supplies global variables (including the main 
	$cartweaver object and database wrapper), sets up debugger (if active),
	cleans $_GET and $_POST variables, and handles logins.
================================================================
*/

/* Make sure sessions are started */
session_start();

/* This line creates the Cartweaver object that drives all functionality. */
if(!isset($_COOKIE["CartId"]) && !isset($_SESSION["CartId"])){
	$cartid = (isset($_GET["cartid"])) ? $_GET["cartid"] : null;
	$cartweaver = new CWCart("Cartweaver", $cartid, $cwGlobalSettings);
    $_SESSION["CartId"] = $cartweaver->getCartId();
}else{
	if(isset($_COOKIE["CartId"])) $_SESSION["CartId"]  = (isset($_GET["cartid"])) ? $_GET["cartid"] : $_COOKIE["CartId"];
    $cartweaver = new CWCart("Cartweaver",$_SESSION["CartId"] , $cwGlobalSettings);
}

// Set up debugging for the session
if(!isset($_SESSION["debug"])) $_SESSION["debug"] = false;
if($cartweaver->settings->cwDebug) {	
	$cartweaver->debugger = "ON"; // Variable for links
	if(isset($_GET['debug']) && $_GET["debug"] == $cartweaver->settings->debugPassword) {
		$_SESSION['debug'] = !($_SESSION['debug']);
	}
	if($_SESSION["debug"] == true) {
		$cartweaver->db->debug = $_SESSION["debug"];
		if($_SESSION["debug"] == true) {
			$cartweaver->debugger = "OFF"; // Variable for links
		}
		include("CWDebugger.php");
	}else{
		// dummy function, no debugging
		function cwDebugger($null) {return;};
	}
}else{
	// dummy function, no debugging
	function cwDebugger($null) {return;};
}
// Set the locale as specified in the global settings
setlocale(LC_ALL, $cartweaver->settings->cwLocale);

/*  Make all post and get variables safe for database insertion */
if(isset($_POST))
	foreach($_POST as $key=>$value)
		$_POST[$key] = $cartweaver->db->sqlSafe($value);
		
if(isset($_GET))
	foreach($_GET as $key=>$value)
		$_GET[$key] = $cartweaver->db->sqlSafe($value);

/* Set Store/Company information in Application Variables */
/* If the companyname application variable isn't set or the user has
  requested to reset the application variables */
if (!isset($_SESSION["companyname"]) || 
   ((isset($_GET["resetApplication"]) && ($_GET["resetApplication"] == $cartweaver->settings->debugPassword)))){
	/* Set the shipping calculation type preferance. Default is "localcalc"	*/
	$_SESSION["shipCalcType"] = "localcalc";
	/* Get the data from the Database  */
	$query = "SELECT comp_ID, comp_Name, comp_Address1, comp_Address2, comp_City, 
	comp_State, comp_Zip, comp_Country, comp_Phone, 
	comp_Fax, comp_Email, comp_ChargeBase, 
	comp_ChargeWeight, comp_ChargeExtension, comp_enableshipping, 
	comp_ShowUpSell, comp_AllowBackOrders 
	FROM tbl_companyinfo ";
	$rsCWGetCompInfo = $cartweaver->db->executeQuery($query);
	$row_rsCWGetCompInfo = $cartweaver->db->db_fetch_assoc($rsCWGetCompInfo);
	
	/* Set Company information into the Application Scope  */
	$_SESSION["companyname"] = $row_rsCWGetCompInfo['comp_Name'];
	$_SESSION["companyaddress1"] = $row_rsCWGetCompInfo['comp_Address1'];
	if ($row_rsCWGetCompInfo['comp_Address2'] != ""){
		$_SESSION["companyaddress2"] = $row_rsCWGetCompInfo['comp_Address2'];
	}
	$_SESSION["companycity"] = $row_rsCWGetCompInfo['comp_City'];
	$_SESSION["companystate"] = $row_rsCWGetCompInfo['comp_State'];
	$_SESSION["companyzip"] = $row_rsCWGetCompInfo['comp_Zip'];
	/* Set Company Contact Information */
	$_SESSION["companyphone"] = $row_rsCWGetCompInfo['comp_Phone'];
	$_SESSION["companyfax"] = $row_rsCWGetCompInfo['comp_Fax'];
	$_SESSION["companyemail"] = $row_rsCWGetCompInfo['comp_Email'];
	/* Set whether or nor to display Cross Sell links on Detals page */
	$_SESSION["showupsell"] = $row_rsCWGetCompInfo['comp_ShowUpSell'];
	/* Set whether or not to allow back orders */
	$_SESSION["AllowBackOrders"] = $row_rsCWGetCompInfo['comp_AllowBackOrders'];	
	/* Set Shipping Criteria 
	These variables determine how shipping will be calculated. 
	By Base rate, weight range or location extension or a combination 
	of these. These variables are set in the company Information 
	form in the Admin section.
	*/		
	$_SESSION["chargeShipBase"] = $row_rsCWGetCompInfo['comp_ChargeBase'];
	$_SESSION["chargeShipByWeight"] = $row_rsCWGetCompInfo['comp_ChargeWeight'];
	$_SESSION["chargeShipExtension"] = $row_rsCWGetCompInfo['comp_ChargeExtension'];
	$_SESSION["enableShipping"] = $row_rsCWGetCompInfo['comp_enableshipping'];
}
$cartweaver->settings->allowBackOrders = $_SESSION["AllowBackOrders"];

/* Add the relevant SSL information to the target URLs */
if($cartweaver->settings->websiteSSLURL != ""){
	// Make sure last character is /
	if(substr($cartweaver->settings->websiteSSLURL, strlen($cartweaver->settings->websiteSSLURL)-1, 1) != "/") {
		$cartweaver->settings->websiteSSLURL = $cartweaver->settings->websiteSSLURL . "/";
	}
	if(!preg_match("/http[s]?\:\/\//", $cartweaver->settings->targetCheckout)) {
		$cartweaver->settings->targetCheckout = $cartweaver->settings->websiteSSLURL . $cartweaver->settings->targetCheckout; 
	}
	if(!preg_match("/http[s]?\:\/\//", $cartweaver->settings->targetGoToCart)) {
		$cartweaver->settings->targetGoToCart = $cartweaver->settings->websiteSSLURL . $cartweaver->settings->targetGoToCart; 
	}
	if(!preg_match("/http[s]?\:\/\//", $cartweaver->settings->targetConfirmOrder)) {
		$cartweaver->settings->targetConfirmOrder = $cartweaver->settings->websiteSSLURL . $cartweaver->settings->targetConfirmOrder; 
	}
}
if($cartweaver->settings->websiteURL != ""){
	// Make sure last character is /
	if(substr($cartweaver->settings->websiteURL, strlen($cartweaver->settings->websiteURL)-1, 1) != "/") {
		$cartweaver->settings->websiteURL = $cartweaver->settings->websiteURL . "/";
	}
	
	if(!preg_match("/http[s]?\:\/\//", $cartweaver->settings->targetGoToCart)) {
		$cartweaver->settings->targetGoToCart = $cartweaver->settings->websiteURL . $cartweaver->settings->targetGoToCart; 
	}
	if(!preg_match("/http[s]?\:\/\//", $cartweaver->settings->targetResults)) {
		$cartweaver->settings->targetResults = $cartweaver->settings->websiteURL . $cartweaver->settings->targetResults; 
	}
	if(!preg_match("/http[s]?\:\/\//", $cartweaver->settings->targetDetails)) {
		$cartweaver->settings->targetDetails = $cartweaver->settings->websiteURL . $cartweaver->settings->targetDetails; 
	}
}
// Include all required Cartweaver functions
require_once("CWIncFunctions.php");

// Set up error handling for the session
/* IF Custom error pages to be shown  */
if(!isset($_SESSION["enableErrorHandling"])) {
	$_SESSION["enableErrorHandling"] = $cartweaver->settings->enableErrorHandling;
}

if($_SESSION["enableErrorHandling"] == true) {
	// Set Custom error page to be shown
	error_reporting(E_ALL);
	include("CWError.php");
}else{
	//turn on all error messages
	ini_set("display_errors", "on");
	error_reporting(E_ALL);
}
//ini_set("display_errors", "on");
/* thisPage is used to create a link or form action that links back to the current file. */
$cartweaver->thisPage = $_SERVER["PHP_SELF"];
/* Add the query string */
$queryString = isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : "";
$cartweaver->thisPageQS = $cartweaver->thisPage . $queryString;
$cartweaver->thisPageName = getPageName($cartweaver->thisPage);
$http = (isset($_ENV["HTTPS"]) && $_ENV["HTTPS"] == "on") ? "https://" : "http://";
$cartweaver->thisLocation = $http . $_SERVER['HTTP_HOST'] . $cartweaver->thisPage; 
$cartweaver->thisLocationQS = $http . $_SERVER['HTTP_HOST'] . $cartweaver->thisPage . $queryString; 

/* If the customer log in form has been submitted, try to find a match */ 
$logged = false;
if(isset($_POST["retcustomer"])){
	include("CWFunCustomerAction.php");
	$loginError = login($_POST["username"],$_POST["password"]);
	$logged = ($loginError == '');
	if($logged) {
		header("Refresh: 0; URL=" . $cartweaver->thisPageName);
		exit();
	}
}

/* If the user has chosen to logout, clear the CartId
if logout contains "savecart", save contents of cart 
and kill session ("I am not Tom -- SIGN IN AGAIN") */
if(isset($_GET["logout"])) {
	if($_GET["logout"] == "savecart") {
   		$temp = (isset($_SESSION["CartId"])) ? $_SESSION["CartId"] : 0;
		session_destroy();
		session_start();
		$_SESSION["CartId"] = $temp;
		$_SESSION["debug"] = true;
	}else{
		/* Delete All Items From "Cart" table */
		$cartweaver->clearCart();
		/* Now delete all Session Variables for this browser. */
		session_destroy();
	}
	header("Location: " . $cartweaver->thisPage); 
	exit();
}


if(isset($_GET["clear"])) {
	$cartweaver->clearCart();
}
?>