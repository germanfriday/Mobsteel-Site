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
Name: Authorize.net include file
Description: Authorize.net payment processing.

NOTE: Setting up accounts and integrating with third party gateways is not 
a supported feature of Cartweaver. For information and support concerning 
payment gateways contact the appropriate gateway tech support web site or 
personnel. Cartweaver includes this integration code as a courtesy with no 
guarantee or warranty expressed or implied. Gateway providers may make changes 
to their protocols or practices that may affect the code provided here. 
If so, updates and modifications are the sole responsibility of the user.
================================================================
*/
/* 
	Set these two values to your Authorize.net Username and password (or
	Trasaction Key, depending on how you've defined your options in the Auth.net
	admin).
*/
$accountUsername = "6pE7Zt4H39c";
$accountPassword = "63NZAH776cbrh93d";
$debugEmails = false;
$testMode = "False";

/* Email confirmation notice to company representative */
$companyEmail = $_SESSION["companyemail"];
$company = $_SESSION["companyname"];

$authorizeNet["x_Login"] = $accountUsername;
$authorizeNet["x_Password"] = $accountPassword;
$authorizeNet["x_version"] = "True";
$authorizeNet["x_type"] = "AUTH_CAPTURE";	

$authorizeNet["x_Amount"] = $_SESSION["orderTotal"];
$authorizeNet["x_Card_Num"] = $ccNumber;
$authorizeNet["x_Exp_Date"] = $ccExprDate;
$authorizeNet["x_Card_Code"] = $ccV;

$authorizeNet["x_Last_Name"] = $row_rsCWGetCustBilling["cst_LastName"];
$authorizeNet["x_First_Name"] = $row_rsCWGetCustBilling["cst_FirstName"];
$authorizeNet["x_company"] = "NA";
$authorizeNet["x_Address"] = $row_rsCWGetCustBilling["cst_Address1"];
$authorizeNet["x_City"] = $row_rsCWGetCustBilling["cst_City"];
$authorizeNet["x_State"] = $row_rsCWGetCustBilling["stprv_Name"];
$authorizeNet["x_Zip"] = $row_rsCWGetCustBilling["cst_Zip"];
$authorizeNet["x_Country"] = $row_rsCWGetCustBilling["country_Code"];
$authorizeNet["x_Phone"] = $row_rsCWGetCustBilling["cst_Phone"];
$authorizeNet["x_email"] = $row_rsCWGetCustBilling["cst_Email"];
$authorizeNet["x_customer_ip"] = $_SERVER['REMOTE_ADDR'];

$authorizeNet["x_Method"] = "CC";
$authorizeNet["x_ADC_Delim_Character"] = ",";
$authorizeNet["x_ADC_Delim_Data"] = "TRUE";
$authorizeNet["x_ADC_Encapsulate_Character"] = "";
$authorizeNet["x_ADC_URL"] = "FALSE";
$authorizeNet["x_Test_Request"] = $testMode;

/* Create a variable with a pretty list of name/value pairs from Authorize.net for store records */
$formValues = "";
$formValues .= "Form Variables\r\n";

$url = "https://secure.authorize.net";
$path = "/gateway/transact.dll";

$str = "";
foreach ($authorizeNet as $key => $value) {
	$value = urlencode(stripslashes($value));
	if($str != "") $str .= "&";
	$str .= "$key=$value";
	$formValues .= $key . ": " . urlencode($value) . "\r\n"; // string for emails
}

if($debugEmails) {
	sendEmail($companyEmail,$companyEmail,"Got values","$formValues");
}

// post back to Authorize.net system 
$ch = curl_init();
curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
curl_setopt ($ch, 'CURLOPT_PROXYTYPE', 'CURLPROXY_HTTP'); 
curl_setopt ($ch, CURLOPT_PROXY,'http://64.202.165.130:3128');
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_URL,$url . $path);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
$authNetResult = curl_exec($ch);
curl_close ($ch);

if (!$authNetResult) {
	// HTTP ERROR
	sendEmail($companyEmail,$companyEmail,"Bad connection to Authorize.net",'Bad connection to Authorize.net. Nothing was processed.');
} else {	
	if($debugEmails) {
		sendEmail($companyEmail,$companyEmail,"Made it to authorize.net","$authNetResult \r\n\r\n\r\n$formValues");
	}
}

if($debugEmails) {
	sendEmail($companyEmail,$companyEmail,"Made it to successful transaction","$authNetResult \r\n\r\n\r\n$formValues");
}



$authNetCodes = explode(",",$authNetResult);
if($authNetCodes[0] == "1") {
	$transactionResult = "Approved";
	$orderStatusID = 2;	
}else{
	$transactionResult = "Failed";
}
$transactionID = (isset($authNetCodes[4])) ? $authNetCodes[4] : 0;
$transactionMessage = (isset($authNetCodes[3])) ? $authNetCodes[3] : "";

/* 1=Pending, 2=Verified, 3=Shipped */
if($transactionResult == 1) {
	$transactionResult = "Approved";
	$orderStatusID = 2;
}

if($debugEmails) {
	$response = "";
	foreach ($authNetCodes as $key => $value) {
		$value = urlencode(stripslashes($value));
		$response .= $key . ": " . urlencode($value) . "\r\n"; // string for emails
	}
	sendEmail($companyEmail,$companyEmail,"Transaction results","$authNetResult \r\n\r\n\r\n$response");
}
?>
