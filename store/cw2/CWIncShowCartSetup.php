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
Name: CWIncShowCartSetup.php
Description:
	This page sets up the CWIncShowCart.php page, which shows the 
	user their shopping cart contents. If the user is checking  
	out, it also collects their credit card	information and 
	submits the data to your payment gateway or	payment processor. 
	If the order is processed successfully the customer is sent to 
	the confirmation page.
	
	This page creates the recordsets and performs the redirects, if 
	necessary, before any HTML is sent to the browser, and is included 
	via the application.php file.
================================================================
*/

/* START [ SET PARAMITERS ] ================================================== */
/* Set default Low Stock parameter */
if(isset($_GET["stockAlert"]) && $_GET["stockAlert"] != "") {
   $cartweaver->setCWError("stockAlert","You have selected more quantity than is currently available.");
}else{
	$_GET["stockAlert"] = "";
}

$_SESSION["shipToCountryID"] = (isset($_SESSION["shipToCountryID"])) ? $_SESSION["shipToCountryID"] : "1";
$_SESSION["checkingOut"] = (isset($_SESSION["checkingOut"])) ? $_SESSION["checkingOut"] : "NO";
$_SESSION["shipTotal"] = (isset($_SESSION["shipTotal"])) ? $_SESSION["shipTotal"] : 0;

/* If ship pref form has been submitted, set shipping Preference  */
$_SESSION["shipPref"] = (isset($_POST["pickShipPref"])) ? $_POST["pickShipPref"] : 0;
/* Set defaults for Credit Card processing fields */
$_POST["cstCCardHolderName"] = (isset($_POST["cstCCardHolderName"])) ? $_POST["cstCCardHolderName"] : "";
$_POST["cstCCardType"] = (isset($_POST["cstCCardType"])) ? $_POST["cstCCardType"] : "";
$_POST["cstCCNumber"] = (isset($_POST["cstCCNumber"])) ? $_POST["cstCCNumber"] : "";
$_POST["cstCCV"] = (isset($_POST["cstCCV"])) ? $_POST["cstCCV"] : "";
$_POST["cstExprMonth"] = (isset($_POST["cstExprMonth"])) ? $_POST["cstExprMonth"] : "";
$_POST["cstExprYr"] = (isset($_POST["cstExprYr"])) ? $_POST["cstExprYr"] : "";

/* Set default error checking variables */

/* END [ SET PARAMITERS ] ===================================================== */
/* If the "PLACE ORDER" button has been clicked... */
/* START [ PROCESS ORDER ] =================================================== */
if(isset($_POST["action"]) && $_POST["action"] == "placeorder"){
	/* Process order */
	include("CWLibrary/CWIncProcessOrder.php");
}
/* END [ PROCESS ORDER ] =============================++====================== */
/* START [ CART ACTIONS ] ===================================================== */
/* DELETE Items checked "remove" from cart */
if (isset($_POST["remove"])) {
    for($i=0; $i<count($_POST["remove"]); $i++) {
		$cartweaver->delete($_POST["remove"][$i]);
	}
	header("Location: " . $cartweaver->thisPageQS);
	exit();
}
/* UPDATE ITEM ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
if ((isset($_POST["action"]) && $_POST["action"] == "update") || isset($_POST["update"])) {
	$cartweaver->setSkuId($_POST["skuid"]);
	$cartweaver->setSkuQty($_POST["qty"]);
	$cartweaver->update();
}
/* END [ CART ACTIONS ] ===================================================== */
/* Perform all shipping calculations */
/* First, get order */
if(isset($_SESSION["CartId"])){
	$hasCart = true;
	/* Reset Subtotal and Weight for recalculation */
	$_SESSION["cartSubtotal"] = 0;
	$_SESSION["cartWeightTotal"] = 0;
	/* Get Cart items associated with the current CartId */
	$query_rsCWGetCart = "SELECT P.product_Sort
	, P.product_Name
	, P.product_shipchrg
	, C.cart_custcart_ID
	, S.SKU_MerchSKUID
	, S.SKU_ID
	, C.cart_sku_qty
	, S.SKU_Price
	, S.SKU_Weight
	, S.SKU_Price * C.cart_sku_qty AS lineTotal
	FROM tbl_products P
		INNER JOIN tbl_skus S
			ON P.product_ID = S.SKU_ProductID
		INNER JOIN tbl_cart C
			ON C.cart_sku_ID = S.SKU_ID
	WHERE C.cart_custcart_ID='" . $_SESSION["CartId"] . "'
		AND	P.product_Archive = 0
		AND S.SKU_ShowWeb = 1
		AND P.product_OnWeb = 1
	ORDER BY P.product_Sort
	, P.product_Name
	, S.SKU_Sort
	, S.SKU_ID";
	$rsCWGetCart = $cartweaver->db->executeQuery($query_rsCWGetCart);
	$rsCWGetCart_recordCount = $cartweaver->db->recordCount;
	$row_rsCWGetCart = $cartweaver->db->db_fetch_assoc($rsCWGetCart);

	if($rsCWGetCart_recordCount == 0){
		$hasCart = false;
	}else{
		/* Calculate CartSubTotal and CartWeightTotal */
		$_SESSION["cartSubtotal"] = 0;
		do{
			/* Calculate cart subtotal and cart weight total */
			$_SESSION["cartSubtotal"] += $row_rsCWGetCart["SKU_Price"] * $row_rsCWGetCart["cart_sku_qty"];
			/* Calculate weight by adding item weight while ommiting the weight of "free shipping" items */
			if($row_rsCWGetCart["product_shipchrg"] == 1){
				$_SESSION["cartWeightTotal"] += $row_rsCWGetCart["SKU_Weight"] * $row_rsCWGetCart["cart_sku_qty"];
			}
		}  while ($row_rsCWGetCart = $cartweaver->db->db_fetch_assoc($rsCWGetCart));
        $cartweaver->db->db_data_seek($rsCWGetCart, 0);
        $row_rsCWGetCart = $cartweaver->db->db_fetch_assoc($rsCWGetCart);
		if($_SESSION["checkingOut"] == "YES") {
			/* Get customer's address Information. */
			$query_rsCWGetCustData = sprintf("SELECT cst_ID
			,cst_Type_ID
			,cst_FirstName
			,cst_LastName
			,cst_Address1
			,cst_Address2
			,cst_City
			,cst_Zip
			,cst_ShpName
			,cst_ShpAddress1
			,cst_ShpAddress2
			,cst_ShpCity
			,cst_ShpZip
			,cst_Phone
			,cst_Email
			,cst_Username
			,cst_Password
			FROM tbl_customers WHERE cst_ID = '%s'",$_SESSION["customerID"]);
			$rsCWGetCustData = $cartweaver->db->executeQuery($query_rsCWGetCustData);
			$rsCWGetCustData_recordCount = $cartweaver->db->recordCount;
			$row_rsCWGetCustData = $cartweaver->db->db_fetch_assoc($rsCWGetCustData);

			/* Get customer's bill to Information. */
			$query_rsCWGetBillTo = sprintf("SELECT CS.CustSt_StPrv_ID
			, S.stprv_Name
			, C.country_Name
			, S.stprv_Tax
			, S.stprv_Ship_Ext 
			FROM tbl_custstate	CS
			INNER JOIN tbl_stateprov S
			ON CS.CustSt_StPrv_ID = S.stprv_ID
			INNER JOIN tbl_list_countries C
			ON S.stprv_Country_ID = C.country_ID
			WHERE CS.CustSt_Cust_ID='%s' 
			AND CS.CustSt_Destination='BillTo'",$_SESSION["customerID"]);
			$rsCWGetBillTo = $cartweaver->db->executeQuery($query_rsCWGetBillTo);
			$rsCWGetBillTo_recordCount = $cartweaver->db->recordCount;
			$row_rsCWGetBillTo = $cartweaver->db->db_fetch_assoc($rsCWGetBillTo);
			
			/* Get customer ship to information */
			$query_rsCWGetShipTo = sprintf("SELECT CS.CustSt_StPrv_ID
			, S.stprv_Name
			, C.country_Name
			, S.stprv_Tax
			, S.stprv_Ship_Ext 
			FROM tbl_custstate CS
			INNER JOIN tbl_stateprov S
			ON CS.CustSt_StPrv_ID = S.stprv_ID
			INNER JOIN tbl_list_countries C
			ON S.stprv_Country_ID = C.country_ID
			WHERE CS.CustSt_Cust_ID='%s' 
			AND CS.CustSt_Destination='ShipTo'",$_SESSION["customerID"]);
			$rsCWGetShipTo = $cartweaver->db->executeQuery($query_rsCWGetShipTo);
			$rsCWGetShipTo_recordCount = $cartweaver->db->recordCount;
			$row_rsCWGetShipTo = $cartweaver->db->db_fetch_assoc($rsCWGetShipTo);
			
			/* Calculate Tax */
			$tallyTaxAmt = 0;
			$tallyTaxAmt = $tallyTaxAmt + ($_SESSION["cartSubtotal"] * $row_rsCWGetBillTo["stprv_Tax"]);
			$_SESSION["taxAmt"] = $tallyTaxAmt;
			/* For Shipping, set ship to StProv Shipping extension */
			$_SESSION["shipExtension"] = $row_rsCWGetShipTo["stprv_Ship_Ext"];
			
			/* ============================================================= */
			/* Calculate SHIPPING  [ START ]================================ */
			/* ============================================================= */
			/* [ Call Shipping include file ] ..................................................
			All shipping calculations are handled by the CWFunShipping.php include file */
			$shipType = $_SESSION["shipCalcType"];
			include("CWLibrary/CWFunShipping.php"); 
			/* ============================================================= */
			/* Calculate ORDER TOTAL  [ START ]============================= */
			/* ============================================================= */
			$tallyOrder = 0;
			$tallyOrder += ( $_SESSION["cartSubtotal"] + $_SESSION["taxAmt"] ) + $_SESSION["shipTotal"];
			$_SESSION["orderTotal"] = $tallyOrder;
			/* Calculate ORDER TOTAL  [ END ]=============================== */
		}/* END if($_SESSION["checkingOut"] == "YES") */
	}/* END if($rsCWGetCart_recordCount == 0) */
}else{
	$hasCart = false;
}/* END if(isset($_SESSION["CartId"])) */

/* After adding an item to cart, "GoTo" Target page or "Comfirm". This variable is set on the CWGlobalSettings.php page. */
if($cartweaver->settings->onSubmitAction == "GoTo" && !$cartweaver->getCWError() && 
	($cartweaver->thisPageName !=  getPageName($cartweaver->settings->targetGoToCart))) {
	header("Location: " . $cartweaver->settings->targetGoToCart . "?result=" . $cartweaver->getQtyAdded() . "&stockalert=" . $_GET["stockAlert"] . "&returnurl=" . urlencode($cartweaver->thisLocationQS));
	exit();
}
?>