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
Name: CWIncValidateOrderForm.php
Description: Here we validate the data submitted in the order form. 
If the data fits the required parameters we send continue on with 
the transaction. If not we halt the transaction and pass back error 
messages to be displayed by the calling template.
================================================================
*/

function checkBlank($theString) {
	return ($theString == "");
}

function checkEmail($theString) {
	return(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$", $theString));
}

if(isset($_POST["shipSame"])){
	$cartweaver->shipAddress = "SAME";
	/* Set shipping form fields to billing form fields */
	$cstShpName = $_POST["cstFirstName"] . " " . $_POST["cstLastName"];
	$cstShpAddress1 = $_POST["cstAddress1"];
	$cstShpAddress2 = $_POST["cstAddress2"];
	$cstShpCity = $_POST["cstCity"];
	$cstShpStateProv = $_POST["cstStateProv"];
	$cstShpZip = $_POST["cstZip"];
}else{
	$cartweaver->shipAddress = "NotSAME";
}

// Check First Name
if(checkBlank($_POST["cstFirstName"])) {$cartweaver->setCWError("CST_FIRSTNAME_ERROR","First Name");}
// Check Last Name
if(checkBlank($_POST["cstLastName"])) {$cartweaver->setCWError("CST_LASTNAME_ERROR","Last Name");}
// Check Address1
if(checkBlank($_POST["cstAddress1"])) {$cartweaver->setCWError("CST_ADDRESS1_ERROR","Address 1");}
// Check City
if(checkBlank($_POST["cstCity"])) {$cartweaver->setCWError("CST_CITY_ERROR","City");}
// Check State
if(checkBlank($_POST["cstStateProv"])) {$cartweaver->setCWError("CST_STATEPROV_ERROR","State or Province");}
// Check Zip
if(checkBlank($_POST["cstZip"])) {$cartweaver->setCWError("CST_ZIP_ERROR","Zip or Postal Code");}
// Check Phone
if(checkBlank($_POST["cstPhone"])) {$cartweaver->setCWError("CST_PHONE_ERROR","Phone");}
// Check Password
if(checkBlank($_POST["cstPassword"])) {$cartweaver->setCWError("CST_PASSWORD_ERROR","Password");}
// Check password confirm
if($_POST["cstPassword"] != $_POST["cstPasswordConfirm"]) {$cartweaver->setCWError("CST_PASSWORDCONFIRM_ERROR","Password Confirm");}

// If Ship Same is not selected, validate Shipping Address data
if ($cartweaver->shipAddress == "NotSAME"){
	// Check Name
	if(checkBlank($_POST["cstShpName"])) {$cartweaver->setCWError("CST_SHPNAME_ERROR","Shipping Name");}
	// Check Address1
	if(checkBlank($_POST["cstShpAddress1"])) {$cartweaver->setCWError("CST_SHPADDRESS1_ERROR","Address 1");}
	// Check City
	if(checkBlank($_POST["cstShpCity"])) {$cartweaver->setCWError("CST_SHPCITY_ERROR","City");}
	// Check State
	if(checkBlank($_POST["cstShpStateProv"])) {$cartweaver->setCWError("CST_SHPSTATEPROV_ERROR","State or province");}
	// Check Zip
	if(checkBlank($_POST["cstShpZip"])) {$cartweaver->setCWError("CST_SHPZIP_ERROR","Zip or Postal Code");}
}// END SHIP SAME

// Check Email
if(checkEmail($_POST["cstEmail"])) {
	$cartweaver->setCWError("CST_EMAIL_ERROR","Email Address");
}else{
	/* Check for duplicate email addresses */
	$query_rsCWCheckForDupEmail = sprintf("SELECT Count(cst_Email) as EmailCount
	FROM tbl_customers 
	WHERE cst_Username <> '%s'
	AND cst_Email = '%s'",$_POST["cstUsername"],$_POST["cstEmail"]);
	$rsCWCheckForDupEmail = $cartweaver->db->executeQuery($query_rsCWCheckForDupEmail);
	$rsCWCheckForDupEmail_recordCount = $cartweaver->db->recordCount;
	$row_rsCWCheckForDupEmail = $cartweaver->db->db_fetch_assoc($rsCWCheckForDupEmail);
	
	/* If there is a duplicate email address and it's not for the current username, throw an error */
	if($row_rsCWCheckForDupEmail["EmailCount"] > 0) {
		$cartweaver->setCWError("CST_DUPEMAIL_ERROR","The requested email address is already in use for another account. Please enter a new email address, or complete the Forgotten Password form.");
	}
}
/* Check for duplicate usernames */
/* Select records with matching usernames */
// Check Username
if(checkBlank($_POST["cstUsername"])) {
	$cartweaver->setCWError("CST_USERNAME_ERROR","Username");
}else{
	$custId = (isset($_SESSION["customerID"]) && $_SESSION["customerID"] != "0") ? " AND cst_ID <> '" . $_SESSION["customerID"] . "'" : "";
	$query_rsCWCheckForDup = sprintf("SELECT Count(cst_Username) as UsernameCount 
	FROM tbl_customers 
	WHERE cst_Username = '%s' %s",$_POST["cstUsername"],$custId);
	$rsCWCheckForDup = $cartweaver->db->executeQuery($query_rsCWCheckForDup);
	$rsCWCheckForDup_recordCount = $cartweaver->db->recordCount;
	$row_rsCWCheckForDup = $cartweaver->db->db_fetch_assoc($rsCWCheckForDup);
	
	if ($row_rsCWCheckForDup["UsernameCount"] > 0) {
		$cartweaver->setCWError("CST_DUPUSERNAME_ERROR", "The requested username is already taken. Please choose a new username.");
	}
}
?>