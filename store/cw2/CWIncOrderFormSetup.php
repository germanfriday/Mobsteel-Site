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
Name: CWIncOrderFormSetup.php
Description: This page allows the user to register for the site
	and enter shipping and billing information. This is the first
	step before credit card details are entered.
================================================================
*/ 
/* If the order form has been submitted process the customer data and move
      on to the final "Show Invoice" page. */ 
if(isset($_POST["orderFormNext"])){
	/* Vailidate form field entries */ 
	include("CWLibrary/CWIncValidateOrderForm.php");	
	/* If there are no errors */
	if(!$cartweaver->getCWError()) {
		$_SESSION["checkingOut"] = "YES";
        include("CWLibrary/CWFunCustomerAction.php");
		/* If this is a new customer */
        if(!isset($_SESSION["customerID"]) || $_SESSION["customerID"] == "0") {
			/* Add a new user */
			newCustomer($_POST["cstFirstName"]
				,$_POST["cstLastName"]
				,$_POST["cstAddress1"]
				,$_POST["cstAddress2"]
				,$_POST["cstCity"]
				,$_POST["cstStateProv"]
				,$_POST["cstZip"]
				,$_POST["cstPhone"]
				,$_POST["cstEmail"]
				,$_POST["cstUsername"]
				,$_POST["cstPassword"]
				,isset($_POST["cstShpAddress1"]) ? $_POST["cstShpAddress1"] : ""
				,isset($_POST["cstShpAddress2"]) ? $_POST["cstShpAddress2"] : ""
				,isset($_POST["cstShpCity"]) ? $_POST["cstShpCity"] : ""
				,isset($_POST["cstShpStateProv"]) ? $_POST["cstShpStateProv"] : ""
				,isset($_POST["cstShpZip"]) ? $_POST["cstShpZip"] : ""
				,isset($_POST["shipSame"]) ? $_POST["shipSame"] : 'notsame');
				header("Location: " . $cartweaver->settings->targetGoToCart);
				exit();
		}else{
			/* Update a returning customer */
			updateCustomer($_POST["cstFirstName"]
				,$_POST["cstLastName"]
				,$_POST["cstAddress1"]
				,$_POST["cstAddress2"]
				,$_POST["cstCity"]
				,$_POST["cstStateProv"]
				,$_POST["cstZip"]
				,$_POST["cstPhone"]
				,$_POST["cstEmail"]
				,$_POST["cstUsername"]
				,$_POST["cstPassword"]
				,1
				,isset($_POST["cstShpAddress1"]) ? $_POST["cstShpAddress1"] : ""
				,isset($_POST["cstShpAddress2"]) ? $_POST["cstShpAddress2"] : ""
				,isset($_POST["cstShpCity"]) ? $_POST["cstShpCity"] : ""
				,isset($_POST["cstShpStateProv"]) ? $_POST["cstShpStateProv"] : ""
				,isset($_POST["cstShpZip"]) ? $_POST["cstShpZip"] : ""
				,isset($_POST["shipSame"]) ? $_POST["shipSame"] : 'notsame');
				header("Location: " . $cartweaver->settings->targetGoToCart);
				exit();

		}
	}else{
		/* We don't have a user, don't allow the user to checkout. Delete the checkingout session variable */
        $_SESSION["checkingOut"] = "NO";
	}/* END if(!$cartweaver->getCWError())  */
}

/* Get states for select menus */
$query_rsCWGetStates = "SELECT C.country_ID
, S.stprv_ID
, C.country_Name
, S.stprv_Name
, C.country_DefaultCountry
FROM tbl_list_countries C
INNER JOIN tbl_stateprov S
ON C.country_ID = S.stprv_Country_ID
WHERE C.country_Archive = 0 
AND S.stprv_Archive = 0
ORDER BY C.country_Sort
, C.country_Name
, S.stprv_Name";
$rsCWGetStates = $cartweaver->db->executeQuery($query_rsCWGetStates);
$rsCWGetStates_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetStates = $cartweaver->db->db_fetch_assoc($rsCWGetStates);

if(isset($_SESSION["customerID"])) {
	/* ///////////// Get Customer Data //////////////////  */ 
	/* get customer information */ 
	$query_rsCWGetCustomerData = "SELECT cst_ID, cst_Type_ID, 
	cst_FirstName, cst_LastName, cst_Address1, cst_Address2, 
	cst_City, cst_Zip, cst_ShpName, cst_ShpAddress1, 
	cst_ShpAddress2, cst_ShpCity, cst_ShpZip, 
	cst_Phone, cst_Email, cst_Username, cst_Password
	FROM tbl_customers
	WHERE cst_ID = '" . $_SESSION["customerID"] . "'";
	$rsCWGetCustomerData = $cartweaver->db->executeQuery($query_rsCWGetCustomerData);
	$rsCWGetCustomerData_recordCount = $cartweaver->db->recordCount;
	$row_rsCWGetCustomerData = $cartweaver->db->db_fetch_assoc($rsCWGetCustomerData);
	
	if ($rsCWGetCustomerData_recordCount != 0) {
		/* get customer Bill To state ID */ 
		
		$query_rsCWBillStateProv = "SELECT CS.CustSt_StPrv_ID
		, C.country_ID
		FROM tbl_list_countries C
		INNER JOIN tbl_stateprov S ON C.country_ID = S.stprv_Country_ID 
		INNER JOIN tbl_custstate CS ON S.stprv_ID = CS.CustSt_StPrv_ID
		WHERE CS.CustSt_Cust_ID = '" . $_SESSION["customerID"] . "' 
		AND CS.CustSt_Destination = 'BillTo'";
		$rsCWBillStateProv = $cartweaver->db->executeQuery($query_rsCWBillStateProv);
		$rsCWBillStateProv_recordCount = $cartweaver->db->recordCount;
		$row_rsCWBillStateProv = $cartweaver->db->db_fetch_assoc($rsCWBillStateProv);
		
		$thisBillStateID = $row_rsCWBillStateProv["CustSt_StPrv_ID"];
		$thisBillCountryID = $row_rsCWBillStateProv["country_ID"];
		/* get customer Ship To state ID */ 	
		
		$query_rsCWShipStateProv = "SELECT CS.CustSt_StPrv_ID, C.country_ID
		FROM tbl_list_countries C 
		INNER JOIN tbl_stateprov S 
		ON C.country_ID = S.stprv_Country_ID
		INNER JOIN tbl_custstate CS
		ON S.stprv_ID = CS.CustSt_StPrv_ID
		WHERE CS.CustSt_Cust_ID = '" . $_SESSION["customerID"] . "' 
		AND CS.CustSt_Destination = 'ShipTo'";
		$rsCWShipStateProv = $cartweaver->db->executeQuery($query_rsCWShipStateProv);
		$rsCWShipStateProv_recordCount = $cartweaver->db->recordCount;
		$row_rsCWShipStateProv = $cartweaver->db->db_fetch_assoc($rsCWShipStateProv);
	
		$thisShipStateID = $row_rsCWShipStateProv["CustSt_StPrv_ID"];
		$thisShipCountryID = $row_rsCWShipStateProv["country_ID"];		
	}
}

?>