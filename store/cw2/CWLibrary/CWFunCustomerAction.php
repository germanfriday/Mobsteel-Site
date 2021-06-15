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

Cartweaver Version: 2.2  -  Date: 09/07/2005
================================================================
Name: CWFunCustomerAction.php 
Description: 
	This set of functions handle logging in customers, adding new 
	customers and updating existing customers.

Functions:

		*	login: Login a user. If you use this action you must also 
			pass the Username, Password and Redirect attributes.

		*	newCustomer: Create a new user. This action adds a new user based 
			on the form fields submitted from CWIncOrderForm.php. No 
			other attributes are required, but your form must have 
			all the same fields as CWIncOrderForm.php.

		*	updateCustomer: Update an existing customer. This action updates 
			an existing customer based on the form fields submitted 
			from CWIncOrderForm.php. No other attributes are required, 
			but your form must have all the same fields as CWIncOrderForm.php.

		*	getUniqueCustomerId: Creates a unique customer id and checks it in the database

	Redirect: The page a successfully logged in user should be redirected 
		to. The default is $cartweaver->settings->targetCheckOut, which is defined in the 
		application.php file.
================================================================
*/

/* getUniqueCustomerId: create a unique customer id and check it in the database */
function getUniqueCustomerId($size=20, $formatString = '', $prefix="cw") {
	// $size defaults to 20 characters, but can be any number
	// $formatString is optional, but allows id to be formatted using 'n' to substitute 
		// for numbers
	// GUID uses format string nnnnnnnn-nnnn-nnnn-nnnn-nnnnnnnnnnnn 

	global $cartweaver;
	$custid = md5(uniqid("CW"));
	$custid = $prefix . $custid;
	$custid = substr($custid, 0, $size);
	if($formatString != '') {
		while(strlen($custid) < strlen($formatString)) {
			$custid .= md5(uniqid("CW"));
		}
		$newCustId = "";
		$custidArray = str_split($custid);
		$formatStringArray = str_split($formatString);
		for($i=0; $i<count($formatStringArray); $i++) {
			if($formatStringArray[$i] == "n") {
				$newCustId .= $custidArray[$i];
			}else{
				$newCustId .= $formatStringArray[$i];
			}
		}
		$custid = $newCustId;
	}
	$query_rsCWGetId = "SELECT cst_ID FROM tbl_customers WHERE cst_ID = '$custid'";
	$rsCWGetId = $cartweaver->db->executeQuery($query_rsCWGetId);
	$rsCWGetId_recordCount = $cartweaver->db->recordCount;
	if($rsCWGetId_recordCount > 0) {
		return getUniqueCustomerId($size);
	}else{
		return $custid;
	}
}
/* login: Process a user login. The arguments are:
	$username: The username that you wish to log in to the site. 
		This will normally be passed from a form field.

	$password: The password for the username you want to log in to the 
		site, as specified in the $username attribute. This will normally 
		be passed from a form field.

*/
function login($username, $password) {
    global $cartweaver;
	$error = "";
	/* Query the database for the username and password */
	$query_rsCWGetCustomer = "SELECT cst_ID, cst_ShpCity 
	FROM tbl_customers
	WHERE cst_Username = '$username'
	AND	cst_Password = '$password'";
	$rsCWGetCustomer = $cartweaver->db->executeQuery($query_rsCWGetCustomer);
	$rsCWGetCustomer_recordCount = $cartweaver->db->recordCount;
	$row_rsCWGetCustomer = $cartweaver->db->db_fetch_assoc($rsCWGetCustomer);

	/* If a match was found Set the Customer ID Session variable */ 
	if($rsCWGetCustomer_recordCount == 1) {
		$_SESSION["customerID"] = $row_rsCWGetCustomer["cst_ID"];
		$query_rsCWGetCountryID = "SELECT s.stprv_Country_ID 
		FROM tbl_stateprov s
		INNER JOIN tbl_custstate cs
		ON s.stprv_ID = cs.CustSt_StPrv_ID 		
		INNER JOIN tbl_customers c
		ON c.cst_ID = cs.CustSt_Cust_ID
		WHERE cs.CustSt_Destination	= 'ShipTo' 
		AND c.cst_ID = '" . $_SESSION["customerID"] . "'";
		$rsCWGetCountryID = $cartweaver->db->executeQuery($query_rsCWGetCountryID);
		$rsCWGetCountryID_recordCount = $cartweaver->db->recordCount;
		$row_rsCWGetCountryID = $cartweaver->db->db_fetch_assoc($rsCWGetCountryID);
		/*  Set session ShipToCountryID based on query results*/ 
		$_SESSION["shipToCountryID"] = $row_rsCWGetCountryID["stprv_Country_ID"];
		$_SESSION["checkingOut"] = "YES";
	}else{/* The user's login failed. Set an error */
		$error = "We're sorry, but your username and password are incorrect.";
	}
	return $error;
}
/* End login */	

/* Add a new customer */
function newCustomer($firstName
				,$lastName
				,$address1
				,$address2
				,$city
				,$state
				,$zip
				,$phone
				,$email
				,$username
				,$password
				,$shipAddress1 = null
				,$shipAddress2 = null
				,$shipCity = null
				,$shipState = null
				,$shipZip = null
				,$shipSame = null) {
    global $cartweaver;
	$shipName = "$firstName $lastName";
	$shipAddress1 = ($shipSame=='Same') ? $address1 : $shipAddress1;
	$shipAddress2 = ($shipSame=='Same') ? $address2 : $shipAddress2;
	$shipCity = ($shipSame=='Same') ? $city : $shipCity;
	$shipZip = ($shipSame=='Same') ? $zip : $shipZip;
	/* Create a new Unique Customer ID */
	$_SESSION["customerID"] = getUniqueCustomerId(11);
	/* INSERT NEW CUSTOMER DATA */
	$query_rsCWInsertCustomer = sprintf("INSERT INTO tbl_customers
	(cst_ID,
	cst_FirstName,
	cst_LastName,
	cst_Address1,
	cst_Address2,
	cst_City,
	cst_Zip,
	cst_Phone,
	cst_Email,
	cst_Username,
	cst_Password,
	cst_ShpName,
	cst_ShpAddress1,
	cst_ShpAddress2,
	cst_ShpCity,
	cst_ShpZip,
	cst_Type_ID
    )
	VALUES	('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',%s)",
	$_SESSION["customerID"],
	$firstName,
	$lastName,
	$address1,
	$address2,
	$city,
	$zip,
	$phone,
	$email,
	$username,
	$password,
	$shipName,
	$shipAddress1,
	$shipAddress2,
	$shipCity,
	$shipZip,
    1
	);
	$rsCWInsertCustomer = $cartweaver->db->executeQuery($query_rsCWInsertCustomer);
	
	/*  Insert new State and Country "BillTo" references */
	$query_rsCWInsertBillTo = sprintf("INSERT INTO tbl_custstate (
	CustSt_Cust_ID
	, CustSt_StPrv_ID
	, CustSt_Destination)
	VALUES ('%s','%s','%s')",$_SESSION["customerID"],
	$state,
	"BillTo");
	$rsCWInsertBillTo = $cartweaver->db->executeQuery($query_rsCWInsertBillTo);
	
	/* set State ID based on appropriate FORM Field */
	$stID = ($shipSame=='Same') ? $state : $shipState;
	
	/*  Insert new State and Country "ShipTo" references */
	$query_rsCWInsertShipTo = sprintf("INSERT INTO tbl_custstate (
	CustSt_Cust_ID
	, CustSt_StPrv_ID
	, CustSt_Destination)
	VALUES ('%s','%s','%s')",$_SESSION["customerID"],
	isset($shipSame) ? $state : $shipState,
	"ShipTo");
	$rsCWInsertShipTo = $cartweaver->db->executeQuery($query_rsCWInsertShipTo);
	
	/* Get Country ID */
	$query_rsCWGetCountryID = "SELECT stprv_Country_ID FROM tbl_stateprov WHERE stprv_ID = $stID";
	$rsCWGetCountryID = $cartweaver->db->executeQuery($query_rsCWGetCountryID);
	$row_rsCWGetCountryID = $cartweaver->db->db_fetch_assoc($rsCWGetCountryID);

	/* Set Shipping Client Variables for shipping selections available during order confirmation */
	$_SESSION["shipToCountryID"] = $row_rsCWGetCountryID["stprv_Country_ID"];
}/* End Add new customer */
	
/* Update a returning customer */
function updateCustomer($firstName
				,$lastName
				,$address1
				,$address2
				,$city
				,$state
				,$zip
				,$phone
				,$email
				,$username
				,$password
				,$typeID
				,$shipAddress1 = null
				,$shipAddress2 = null
				,$shipCity = null
				,$shipState = null
				,$shipZip = null
				, $shipSame = null) {
    global $cartweaver;
	$shipName = "$firstName $lastName";
	$shipAddress1 = ($shipSame=='Same') ? $address1 : $shipAddress1;
	$shipAddress2 = ($shipSame=='Same') ? $address2 : $shipAddress2;
	$shipCity = ($shipSame=='Same') ? $city : $shipCity;
	$shipZip = ($shipSame=='Same') ? $zip : $shipZip;
				
	/* Update Customer data */
	$query_rsCWUpdateCustomer = sprintf("UPDATE tbl_customers
	SET cst_Firstname='%s', 
	cst_Lastname='%s',
	cst_Address1='%s', 
	cst_Address2='%s', 
	cst_City='%s',
	cst_Zip='%s', 
	cst_Phone='%s', 
	cst_Email='%s', 
	cst_Username='%s',
	cst_Password='%s', 
	cst_Shpname='%s', 
	cst_ShpAddress1='%s',
	cst_ShpAddress2='%s', 
	cst_ShpCity='%s', 
	cst_ShpZip='%s'
	WHERE cst_ID='%s'",
	$firstName,
	$lastName,
	$address1,
	$address2,
	$city,
	$zip,
	$phone,
	$email,
	$username,
	$password,
	$shipName,
	$shipAddress1,
	$shipAddress2,
	$shipCity,
	$shipZip,
	$_SESSION["customerID"]);
	$rsCWUpdateCustomer = $cartweaver->db->executeQuery($query_rsCWUpdateCustomer);
	
	// First, check to make sure customer has entry in state field
	$query_rsCWGetState = sprintf("SELECT CustSt_StPrv_ID FROM tbl_custstate 
	WHERE CustSt_Cust_ID = '%s' AND CustSt_Destination = 'BillTo'",$_SESSION["customerID"]);
	$rsCWGetState = $cartweaver->db->executeQuery($query_rsCWGetState);
	$rsCWGetState_recordCount = $cartweaver->db->recordCount;

	if($rsCWGetState_recordCount == 0) {
		/*  Insert new State and Country "BillTo" references */
		$query_rsCWInsertBillTo = sprintf("INSERT INTO tbl_custstate (
		CustSt_Cust_ID
		, CustSt_StPrv_ID
		, CustSt_Destination)
		VALUES ('%s','%s','%s')",$_SESSION["customerID"],
		$state,
		"BillTo");
		$rsCWInsertBillTo = $cartweaver->db->executeQuery($query_rsCWInsertBillTo);
	}else{	
		/*  Update State "BillTo" references */
		$query_rsCWUpdateBillTo = sprintf("UPDATE tbl_custstate 
		SET CustSt_StPrv_ID = '%s' 
		WHERE CustSt_Cust_ID = '%s'
		AND CustSt_Destination = 'BillTo'"
		,$state
		,$_SESSION["customerID"]);
		$rsCWUpdateBillTo = $cartweaver->db->executeQuery($query_rsCWUpdateBillTo);
	}
	
	/* set State ID based on appropriate FORM Field */
	$stID = ($shipSame=='Same') ? $state : $shipState;
	
	// First, check to make sure customer has entry in state field
	$query_rsCWGetState = sprintf("SELECT CustSt_StPrv_ID FROM tbl_custstate 
	WHERE CustSt_Cust_ID = '%s' AND CustSt_Destination = 'ShipTo'",$_SESSION["customerID"]);
	$rsCWGetState = $cartweaver->db->executeQuery($query_rsCWGetState);
	$rsCWGetState_recordCount = $cartweaver->db->recordCount;

	if($rsCWGetState_recordCount == 0) {
		/*  Insert new State and Country "ShipTo" references */
		$query_rsCWInsertShipTo = sprintf("INSERT INTO tbl_custstate (
		CustSt_Cust_ID
		, CustSt_StPrv_ID
		, CustSt_Destination)
		VALUES ('%s','%s','%s')",$_SESSION["customerID"],
		isset($shipSame) ? $state : $shipState,
		"ShipTo");
		$rsCWInsertShipTo = $cartweaver->db->executeQuery($query_rsCWInsertShipTo);
	}else{
		/*  Update State "ShipTo" references */
		$query_rsCWUpdateShipTo = sprintf("UPDATE tbl_custstate
		SET CustSt_StPrv_ID = %s
		WHERE CustSt_Cust_ID = '%s'
		AND CustSt_Destination ='ShipTo'"
		,$stID
		,$_SESSION["customerID"]);
		$rsCWUpdateShipTo = $cartweaver->db->executeQuery($query_rsCWUpdateShipTo);
	}
	/* Get Country ID */
	$query_rsCWGetCountryID = "SELECT stprv_Country_ID 
	FROM tbl_stateprov 
	WHERE stprv_ID = $stID";
    $rsCWGetCountryID = $cartweaver->db->executeQuery($query_rsCWGetCountryID);
    $row_rsCWGetCountryID = $cartweaver->db->db_fetch_assoc($rsCWGetCountryID);

	/* Set Shipping Client Variables for shipping selections available during order confirmation */
	$_SESSION["shipToCountryID"] = $row_rsCWGetCountryID["stprv_Country_ID"];
	
}/* End Update returning customer */
?>