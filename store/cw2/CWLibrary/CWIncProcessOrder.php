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
Name: CWIncProcessOrder.php
Description: 
	The Process Order include file adds the user's order to the
	database. All data is validated before processing the order.
	This page calls the CW payment processor include file in order
	to pass data to the payment processor.
================================================================
*/
// Initialize transaction variables
$transactionResult = "";
$transactionID = "";
$transactionMessage = "";

if(isset($_SESSION["CartId"])){
	/* Validate Credit Card Input */
	if(strtolower($cartweaver->settings->paymentAuthType) == "gateway") {
		$cwErrorText = "Please correct the following problems:";
		// Check Card holder name
		if($_POST["cstCCardHolderName"] == "") {
			$cartweaver->setCWError("cwErrorCHN", "Card Holder Name cannot be blank.");
		}
		
		// Check C Card Type
		if ($_POST["cstCCardType"] == "forgot"){ 
			$cartweaver->setCWError("cwErrorCT","Please choose your credit card type.");
		}
		
		// Check C Card Number
		$ccLength = 16;
		$ccvLength = 3;
		if($_POST["cstCCardType"] == "amex"){
				$ccLength = 15;
				$ccvLength = 4;
		}
		if (!is_numeric($_POST["cstCCNumber"]) || strlen($_POST["cstCCNumber"]) < $ccLength){
			$cartweaver->setCWError("cwErrorCN","You did not enter a valid credit card number.");
		}
		
		if (!is_numeric($_POST["cstCCV"]) || strlen($_POST["cstCCV"]) < $ccvLength){
			$cartweaver->setCWError("cwErrorCCV","You did not enter a CCV code.");
		}
		
		// Check C Card Expr Month
		if ($_POST["cstExprMonth"] == "forgot"){
			$cartweaver->setCWError("cwErrorCM","Please choose the month your card expires.");
		}
		
		// Check C Card Expr Year
		if ($_POST["cstExprYr"] == "forgot"){
			$cartweaver->setCWError("cwErrorCY","Please choose the year your card expires.");
		}
	}
	/* Check to be sure there are no credit card errors */
	if(!$cartweaver->getCWError()) {
    /* If a payment processor or no payment verification is being used, 
		      then approve the transactions */
		if(strtolower($cartweaver->settings->paymentAuthType) == "processor" 
			|| strtolower($cartweaver->settings->paymentAuthType) == "none"){
			$orderStatusID = 1;
			$transactionID = $_SESSION["CartId"];
			$_SESSION["transactionMessage"] = "Approved";
			$transactionResult = "Approved";
		}

		/* [START Payment Gateway include file Call] */
		if(strtolower($cartweaver->settings->paymentAuthType) == "gateway"){
			/* Set default values for use in the Gateway */
			$ccardHolderName = $_POST["cstCCardHolderName"];
			$ccardType = $_POST["cstCCardType"];
			$ccNumber = $_POST["cstCCNumber"];
			$exprMonth = $_POST["cstExprMonth"];
			$exprYr = $_POST["cstExprYr"];
			$ccV = $_POST["cstCCV"];
			$ccExprDate = $exprMonth . $exprYr;
			/* Get billing information for Credit Card validation */
			$query_rsCWGetCustBilling = "SELECT c.cst_FirstName, 
			c.cst_LastName, 
			s.stprv_Code, 
			s.stprv_Name, 
			co.country_Code, 
			c.cst_Email, 
			c.cst_Phone, 
			c.cst_Address1, 
			c.cst_Address2, 
			c.cst_City, 
			c.cst_Zip
			FROM tbl_list_countries co
			INNER JOIN tbl_stateprov s 
			ON co.country_ID = s.stprv_Country_ID 
			INNER JOIN tbl_custstate cs 
			ON c.cst_ID = cs.CustSt_Cust_ID
			INNER JOIN tbl_customers c
			ON s.stprv_ID = cs.CustSt_StPrv_ID
			WHERE c.cst_ID = '" . $_SESSION["customerID"] . "' 
			AND cs.CustSt_Destination = 'BillTo' ";
			$rsCWGetCustBilling = $cartweaver->db->executeQuery($query_rsCWGetCustBilling);
			$rsCWGetCustBilling_recordCount = $cartweaver->db->recordCount;
			$row_rsCWGetCustBilling = $cartweaver->db->db_fetch_assoc($rsCWGetCustBilling);
	
			/* Process the payment and return a result */
			require("ProcessPayment/" . $cartweaver->settings->paymentAuthName);
		}/* [END Payment Gateway include file Call] */
	} /* End check for valid credit card data */
	/* Transaction APPROVED enter data in database. */
	if($transactionResult == "Approved") {
		$query_rsCWGetCustShipping = "SELECT cst_ShpName,
		cst_ShpAddress1, 
		cst_ShpAddress2, 
		cst_ShpCity, 
		stprv_Code, 
		cst_ShpZip, 
		country_Code, 
		cst_Email 
		FROM tbl_customers, 
		tbl_custstate, 
		tbl_stateprov, 
		tbl_list_countries 
		WHERE cst_ID ='" . $_SESSION["customerID"] ."'
		AND stprv_ID = CustSt_StPrv_ID 
		AND CustSt_Cust_ID = cst_ID 
		AND CustSt_Destination ='ShipTo'
		AND country_ID = stprv_Country_ID";
		$rsCWGetCustShipping = $cartweaver->db->executeQuery($query_rsCWGetCustShipping);
		$rsCWGetCustShipping_recordCount = $cartweaver->db->recordCount;
		$row_rsCWGetCustShipping = $cartweaver->db->db_fetch_assoc($rsCWGetCustShipping);
		
		/* Create a New Order ID */
		$thisOrderID = uniqid("cw");
		/* Insert the order into the database */
		$query_rsCWAddOrder = sprintf("INSERT INTO tbl_orders 
		(order_ID, order_CustomerID, 
		order_Tax, order_Shipping, 
		order_Total, order_Status,
		order_ShipMeth_ID, order_Address1, 
		order_Address2, order_City,
		order_Zip, order_Country, 
		order_State, order_TransactionID, 
		order_Date, order_ShipName) 
		VALUES ('%s','%s',%s,%s,%s,'%s',%s,'%s','%s','%s','%s','%s','%s','%s',now(),'%s')",
		$thisOrderID
		, $_SESSION["customerID"]
		, $_SESSION["taxAmt"]
		, $_SESSION["shipTotal"]
		, $_SESSION["orderTotal"]
		, $orderStatusID
		, $_SESSION["shipPref"]
		, $row_rsCWGetCustShipping["cst_ShpAddress1"]
		, $row_rsCWGetCustShipping["cst_ShpAddress2"]
		, $row_rsCWGetCustShipping["cst_ShpCity"]
		, $row_rsCWGetCustShipping["cst_ShpZip"]
		, $row_rsCWGetCustShipping["country_Code"]
		, $row_rsCWGetCustShipping["stprv_Code"]
		, $transactionID 
		, $row_rsCWGetCustShipping["cst_ShpName"]);
		$rsCWAddOrder = $cartweaver->db->executeQuery($query_rsCWAddOrder);
		
		/* // === Now Add SKUs ordered to "OrderItems" Table === // */
		/* Get the items in the cart */
		$query_rsCWGetCartItems = "SELECT S.SKU_ID
		, C.cart_sku_qty
		, S.SKU_Price
		, S.SKU_Stock
		FROM tbl_skus S
		LEFT JOIN tbl_cart C
		ON S.SKU_ID = C.cart_sku_ID
		WHERE C.cart_custcart_ID='" . $_SESSION["CartId"] ."'
		AND S.SKU_ShowWeb=1
		ORDER BY S.SKU_Sort";
		$rsCWGetCartItems = $cartweaver->db->executeQuery($query_rsCWGetCartItems);
		$rsCWGetCartItems_recordCount = $cartweaver->db->recordCount;
		$row_rsCWGetCartItems = $cartweaver->db->db_fetch_assoc($rsCWGetCartItems);

		/* Loop through the results and record them */
		do { /* CW Repeat rsCWGetCartItems */ 
		$skuTotal = $row_rsCWGetCartItems["SKU_Price"] * $row_rsCWGetCartItems["cart_sku_qty"];
		$query_rsCWAddSKUs = sprintf("INSERT INTO tbl_orderskus 
		( orderSKU_OrderID
		, orderSKU_SKU
		, orderSKU_Quantity
		, orderSKU_UnitPrice
		, orderSKU_SKUTotal
		, orderSKU_Picked) 
		VALUES 
		('%s',%s,%s,%s,%s,%s)
		", $thisOrderID
		, $row_rsCWGetCartItems["SKU_ID"]
		, $row_rsCWGetCartItems["cart_sku_qty"]
		, $row_rsCWGetCartItems["SKU_Price"]
		, $skuTotal
		, 0);			
		$rsCWAddSKUs = $cartweaver->db->executeQuery($query_rsCWAddSKUs);
		
		/* Debit purchased quantity from stock on hand. */
		$newStockCount = $row_rsCWGetCartItems["SKU_Stock"] - $row_rsCWGetCartItems["cart_sku_qty"];
		$query_rsCWUpdateSKUs = sprintf("UPDATE tbl_skus 
		SET SKU_Stock = %s 
		WHERE SKU_ID = %s", 
		$newStockCount,
		$row_rsCWGetCartItems["SKU_ID"]);
		$rsCWUpdateSKUs = $cartweaver->db->executeQuery($query_rsCWUpdateSKUs);
		}  while ($row_rsCWGetCartItems = $cartweaver->db->db_fetch_assoc($rsCWGetCartItems)); 
		/* End CW Repeat rsCWGetCartItems */ 

		/* Delete All Items From "Cart" table */
		$cartweaver->clearCart();
		$_SESSION["completeOrderID"] = $thisOrderID;
		/* Redirect to Confirmation page */
		header("Location: " . $cartweaver->settings->targetConfirmOrder); 
		exit();
	}else{
		$cartweaver->setCWError("TransactionError",$transactionMessage);
	} /* END if($transactionResult == "Approved") */
}else{
  /* no valid cart, redirect the user to the cart page */
	header("Location: " . $cartweaver->settings->targetGoToCart);
	exit();
}
?>