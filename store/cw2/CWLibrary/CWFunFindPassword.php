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
Name: CWFunFindPassword.php
Description: 
	This include file finds a customer's password based on their
	email address. The results are sent to
	their email address on file.

Attributes:
	emailaddress: The email address to find the username and password. 
		The found username and password will be emailed to the same address.

Returns
	true or false: If the email address is  found in the database

================================================================
*/
function cwFindPassword($emailAddress) {
	global $cartweaver;
	global $_SESSION;
	$query_rsCWGetPw = "SELECT cst_Email,cst_FirstName,cst_LastName,cst_Username,cst_Password
	FROM tbl_customers
	WHERE cst_Email = '" . $emailAddress . "'";
	$rsCWGetPw = $cartweaver->db->executeQuery($query_rsCWGetPw);
	$rsCWGetPw_recordCount = $cartweaver->db->recordCount;
	$row_rsCWGetPw = $cartweaver->db->db_fetch_assoc($rsCWGetPw);
	
	/* If there is no matching record display an error */
	if($rsCWGetPw_recordCount == 0) {
		return false;
		//$cartweaver->settings->PWNotFound = "Sorry, no matching record was found. Please try again.";
	}else{
		$subject = "Your " . $_SESSION["companyname"] . " login information.";
		//$to = "'" . $row_rsCWGetPw['cst_FirstName'] . " " . $row_rsCWGetPw['cst_LastName'] . "' <" . $emailAddress . ">";
		$to = $emailAddress;
		$from =  $_SESSION["companyname"] . " <" . $_SESSION["companyemail"] . ">";
		$message = "Hello " . $row_rsCWGetPw['cst_FirstName'] . " " . $row_rsCWGetPw['cst_LastName'] .",

Here is your username and password...

Username: " . $row_rsCWGetPw['cst_Username'] . "
Password: " . $row_rsCWGetPw['cst_Password'] . "

Thank you!
		
Customer Support. " .
$_SESSION["companyname"] . "
" . $_SESSION["companyaddress1"] . "
" . $_SESSION["companycity"] . ", " . $_SESSION["companystate"] . " " . $_SESSION["companyzip"] . "
---
" . $_SESSION["companyphone"];
		sendEmail($to, $from, $subject, $message);
		$cartweaver->settings->PWFound = "Your username and password have been sent to ". $row_rsCWGetPw["cst_Email"] .".<br>If this email address is no longer accessible you will need to contact customer service.";
	}
	return true;
}

?>