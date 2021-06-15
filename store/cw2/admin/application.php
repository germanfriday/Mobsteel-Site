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
Name: application.php
Description: This flie pulls the general settings from the main 
site Application file, then checks to see if the user is logged 
in corectly and ifso it sets the Admin spacific settings and variables.
================================================================
*/

/* Get general Application settings from site application.php file */
require_once("../../application.php");

/* Verify the user is logged in. */
if ($cartweaver->thisPageName != "index.php" && !isset($_SESSION["LoggedIn"])){
	$strURL = $_SERVER["SCRIPT_NAME"] ;
	$tempQuerystring = isset($_SERVER["QUERY_STRING"]) ? str_replace("logout=true","",$_SERVER["QUERY_STRING"]) : "";
	$strURL = ($tempQuerystring != "") ? $strURL . "?" . $tempQuerystring : $strURL;
	header("Location: index.php?accessdenied=$strURL"); 
	exit();
}

/* Now Set Admin Specific Settings */

/* 
Queries used in Navigation menu 
Since these queries are on all pages we can reuse them elsewhere in the admin 
by keeping them in the application.php. Load the navs if the application variables
are not defined, or if updates are made to the options menus or ship statuses.
*/
/* Options Menu */
if (!isset($_SESSION['OptionsMenu'])){
	$query_rsCWOptionsNav = "SELECT optiontype_ID, optiontype_Name
		FROM tbl_list_optiontypes
		ORDER BY optiontype_Name";
	$rsCWOptionsNav = $cartweaver->db->executeQuery($query_rsCWOptionsNav);
	$rsCWOptionsNav_recordCount = $cartweaver->db->recordCount;
	$row_rsCWOptionsNav = $cartweaver->db->db_fetch_assoc($rsCWOptionsNav);
	
	$_SESSION['OptionsMenu'] = "";
	
	do {
		$_SESSION['OptionsMenu'] .= '<a href="Options.php?optionID=' . $row_rsCWOptionsNav['optiontype_ID'] . '">&#8211;' . $row_rsCWOptionsNav['optiontype_Name'] . '</a>';
	}  while ($row_rsCWOptionsNav = $cartweaver->db->db_fetch_assoc($rsCWOptionsNav)); 
}

if (!isset($_SESSION['ShipStatusMenu'])){
	$query_rsCWShipStatusTypes = "SELECT shipstatus_id, shipstatus_Name, shipstatus_Sort
	FROM tbl_list_shipstatus
	ORDER BY shipstatus_Sort ASC";
	$rsCWShipStatusTypes = $cartweaver->db->executeQuery($query_rsCWShipStatusTypes);
	$rsCWShipStatusTypes_recordCount = $cartweaver->db->recordCount;
	$row_rsCWShipStatusTypes = $cartweaver->db->db_fetch_assoc($rsCWShipStatusTypes);
	$_SESSION['ShipStatusMenu'] = "";
	do {
		$_SESSION['ShipStatusMenu'] .= '<a href="Orders.php?searchBy=' . $row_rsCWShipStatusTypes['shipstatus_id'] . '">&#8211;' . $row_rsCWShipStatusTypes['shipstatus_Name'] . '</a>';
	}  while ($row_rsCWShipStatusTypes = $cartweaver->db->db_fetch_assoc($rsCWShipStatusTypes)); 
}

$imageThumbFolder = $cartweaver->settings->imageThumbFolder; 
$imageLargeFolder = $cartweaver->settings->imageLargeFolder;
if(isset($_SERVER['DOCUMENT_ROOT'])) {
	$siteRoot = $_SERVER["DOCUMENT_ROOT"] .  dirname($_SERVER["PHP_SELF"]);
}else{
	$siteRoot = dirname($_SERVER["PATH_TRANSLATED"]);
	$siteRoot = str_replace("\\\\","\\",$siteRoot);
}
$siteRoot = str_replace("cw2\\Admin","",$siteRoot);
$siteRoot = str_replace("cw2\\admin","",$siteRoot);
$siteRoot = str_replace("cw2/Admin","",$siteRoot);
$siteRoot = str_replace("cw2/admin","",$siteRoot);
?>