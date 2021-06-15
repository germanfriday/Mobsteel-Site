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
Name: CWIncConfirmation.php
Description: 
	This page is displayed to the user once they've completed their
	order. It sends the confirmation email to the customer as well
	as displaying all of their order details in a table suitable
	for printing.
================================================================
*/

/* If the Payment Processor is posting to the page, then process the payment */
if(strtolower($cartweaver->settings->paymentAuthType) == "processor") {
	$processorStatus = "NoForm";
	require("CWLibrary/ProcessPayment/" . $cartweaver->settings->paymentAuthName);
}
/* End Payment Processing check */

$_SESSION["completeOrderID"] = (isset($_SESSION["completeOrderID"])) ? $_SESSION["completeOrderID"] : 0;
$emailContents = "";

/* If we have a valid order id, output the order */
if($_SESSION["completeOrderID"] != "") {
	$thisOrderID = $_SESSION["completeOrderID"];
	/* Output order details for the user to print out. */
	/* Get Order */

	$query_rsCWOrder = "SELECT O.order_ID, 
		O.order_TransactionID, 
		O.order_Date, 
		O.order_Status, 
		O.order_CustomerID, 
		O.order_Tax, 
		O.order_Shipping, 
		O.order_Total, 
		O.order_ShipMeth_ID, 
		O.order_ShipDate, 
		O.order_ShipTrackingID, 
		O.order_Address1, 
		O.order_Address2, 
		O.order_City, 
		O.order_State, 
		O.order_Zip, 
		O.order_Country, 
		O.order_Notes, 
		O.order_ActualShipCharge, 
		O.order_ShipName, 
		C.cst_FirstName, 
		C.cst_LastName, 
		C.cst_Email, 
		OS.orderSKU_SKU, 
		P.product_Name, 
		OS.orderSKU_Quantity, 
		OS.orderSKU_UnitPrice, 
		OS.orderSKU_SKUTotal, 
		SM.shipmeth_Name, 
		S.SKU_MerchSKUID
	FROM tbl_orders O
	INNER JOIN tbl_customers C
	ON C.cst_ID = O.order_CustomerID
	LEFT JOIN tbl_shipmethod SM
	ON SM.shipmeth_ID = O.order_ShipMeth_ID
	INNER JOIN tbl_orderskus OS
	ON O.order_ID = OS.orderSKU_OrderID
	INNER JOIN tbl_skus S
	ON S.SKU_ID = OS.orderSKU_SKU
	INNER JOIN tbl_products P
	ON P.product_ID = S.SKU_ProductID
	WHERE O.order_ID ='$thisOrderID'
	ORDER BY P.product_Sort, 
	P.product_Name, 
	S.SKU_Sort, 
	S.SKU_ID;";
	$rsCWOrder = $cartweaver->db->executeQuery($query_rsCWOrder);
	$rsCWOrder_recordCount = $cartweaver->db->recordCount;
	$row_rsCWOrder = $cartweaver->db->db_fetch_assoc($rsCWOrder);

	/* If there are valid order records */
	if($rsCWOrder_recordCount != 0) {
	
	/* Shipping Details */?>
	<h2>Ship To</h2>
	<p><strong><?php echo($row_rsCWOrder["order_ShipName"]);?></strong><br>
	 <?php echo($row_rsCWOrder["order_Address1"]);?>
	 <?php if ($row_rsCWOrder["order_Address2"] != "") {
	  echo("<br>");
	  echo($row_rsCWOrder["order_Address2"]);
	 }?>
	 <br>
	 <?php echo($row_rsCWOrder["order_City"]); ?>, <?php echo($row_rsCWOrder["order_State"]); ?> <?php echo($row_rsCWOrder["order_Zip"]); ?><br>
	 <?php echo($row_rsCWOrder["order_Country"]);?></p>
	 <?php /* Create Ship To information for confirmation email */ 
	$emailContents .= "\r\nOrder ID: " . $row_rsCWOrder["order_ID"];	
	$emailContents .= "\r\nShip To";
	$emailContents .= "\r\n====================";
	$emailContents .= "\r\n" . $row_rsCWOrder["order_ShipName"];
	$emailContents .= "\r\n" . $row_rsCWOrder["order_Address1"];
	if ($row_rsCWOrder["order_Address2"] != ""){
		$emailContents .= "\r\n" . $row_rsCWOrder["order_Address2"];
	}
	$emailContents .= "\r\n" . $row_rsCWOrder["order_City"] . ", " . $row_rsCWOrder["order_State"] . " " . $row_rsCWOrder["order_Zip"];
	$emailContents .= "\r\n" . $row_rsCWOrder["order_Country"];
	
	$emailContents .= "\r\n" . "Order Details";
	$emailContents .= "\r\n" . "====================";
	
	/* Output Order Table */ ?>
	<h2>Order Details</h2>
	<p>Order ID: <?php echo($row_rsCWOrder["order_ID"]);?></p>
	<table class="tabularData" id="tblOrderDetails">
		<tr>
			<th>Product</th>
			<th>Qty.</th>
			<th>Price</th>
			<th>Total</th>
		</tr>
	 <?php 
	 /* Set a variable for alternating the table rows. */
	 $recCounter = 0;
	 
	do { /* CW Repeat rsCWOrder */ 
		  $query_rsCWGetOptions = sprintf("SELECT OT.optiontype_Name, 
		  	SO.option_Name
			FROM tbl_list_optiontypes OT
				INNER JOIN tbl_skuoptions SO
				ON OT.optiontype_ID = SO.option_Type_ID 
				INNER JOIN tbl_skuoption_rel SR
				ON SO.option_ID = SR.optn_rel_Option_ID 
			WHERE SR.optn_rel_SKU_ID = %s
			ORDER BY OT.optiontype_Name, 
			SO.option_Sort",$row_rsCWOrder["orderSKU_SKU"]);
			$rsCWGetOptions = $cartweaver->db->executeQuery($query_rsCWGetOptions);
			$rsCWGetOptions_recordCount = $cartweaver->db->recordCount;
			?>
		<tr valign="top" class="<?php cwAltRow($recCounter++);?>">
			<td><?php echo($row_rsCWOrder["product_Name"]); ?>  (<?php echo($row_rsCWOrder["SKU_MerchSKUID"]) ?>)
			<?php
			$emailContents .= "\r\n" . $row_rsCWOrder["product_Name"] . "(" . $row_rsCWOrder["SKU_MerchSKUID"] . ")";
			  /* Output the individual sku options */
			  while ($row_rsCWGetOptions = $cartweaver->db->db_fetch_assoc($rsCWGetOptions)) { /* CW Repeat rsCWGetOptions */ ?>
				<br>
				<strong style="margin-left: 10px;"><?php echo($row_rsCWGetOptions["optiontype_Name"]);?></strong>: <?php echo($row_rsCWGetOptions["option_Name"]);?>
			<?php $emailContents .= "\r\n   " . $row_rsCWGetOptions["optiontype_Name"] . ": " . $row_rsCWGetOptions["option_Name"];
			 } ; 
/* End CW Repeat rsCWGetOptions */ ?>
			</td>
			<td align="center"><?php echo($row_rsCWOrder["orderSKU_Quantity"]);?></td>
			<td align="right"><?php echo(cartweaverMoney($row_rsCWOrder["orderSKU_UnitPrice"]));?></td>
			<td align="right"><?php echo(cartweaverMoney($row_rsCWOrder["orderSKU_SKUTotal"]));?></td>
		</tr>
	<?php $emailContents .= "\r\nQuantity: " . $row_rsCWOrder["orderSKU_Quantity"];
		$emailContents .= "\r\nPrice: " . cartweaverMoney($row_rsCWOrder["orderSKU_UnitPrice"]);
		$emailContents .= "\r\nTotal: " . cartweaverMoney($row_rsCWOrder["orderSKU_SKUTotal"]) . "\r\n";
		}  while ($row_rsCWOrder = $cartweaver->db->db_fetch_assoc($rsCWOrder)); 
/* End CW Repeat rsCWOrder */ 
$cartweaver->db->db_data_seek($rsCWOrder,0);
$row_rsCWOrder = $cartweaver->db->db_fetch_assoc($rsCWOrder);?>
		<tr>
			<th colspan="3" align="right">Subtotal: </th>
			<td align="right"><?php echo(cartweaverMoney($row_rsCWOrder["order_Total"] - ($row_rsCWOrder["order_Shipping"] + $row_rsCWOrder["order_Tax"])));?></td>
		</tr>
		<?php if ($row_rsCWOrder["order_ShipMeth_ID"] != 0) { ?>
		<tr>
			<th colspan="3" align="right">Shipping <?php echo($row_rsCWOrder["shipmeth_Name"]);?>): </th>
			<td align="right"><?php echo(cartweaverMoney($row_rsCWOrder["order_Shipping"]));?></td>
		</tr>
		<?php } ?>
		<tr>
			<th colspan="3" align="right">Tax: </th>
			<td align="right"><?php echo(cartweaverMoney($row_rsCWOrder["order_Tax"]));?></td>
		</tr>
		<tr>
			<th colspan="3" align="right">Order Total: </th>
			<td align="right"><strong><?php echo(cartweaverMoney($row_rsCWOrder["order_Total"]));?></strong></td>
		</tr>
		</table> 
		<?php
		$emailContents .= "\r\n====================";
		$emailContents .= "\r\nSubtotal: " . cartweaverMoney($row_rsCWOrder["order_Total"]-($row_rsCWOrder["order_Shipping"]+$row_rsCWOrder["order_Tax"]));
		$emailContents .= "\r\nShipping (" . $row_rsCWOrder["shipmeth_Name"] . "): " . cartweaverMoney($row_rsCWOrder["order_Shipping"]);
		$emailContents .= "\r\nTax: " . cartweaverMoney($row_rsCWOrder["order_Tax"]);
		$emailContents .= "\r\nOrder Total: " . cartweaverMoney($row_rsCWOrder["order_Total"]);  ?>
	 <?php
		/* 
		Send Order confirmation Email to Customer and Order Notice Email to Merchant.
		Do this before anything else just in case there are errors with the display portion. 
		*/
		require("CWLibrary/CWFunOrderConfirmEmails.php");
		confirmEmails($row_rsCWOrder["cst_Email"], $emailContents, $cartweaver->settings->paymentAuthType,null);
		
		/* // All Done Now ... Need to Kill Sessions and delete Client Order ID // */
		session_unregister('completeOrderID'); 
		session_unregister('checkingOut'); 
	
		if(strtolower($cartweaver->settings->paymentAuthType) == "processor") {
			$processorStatus = "Form";
			require("CWLibrary/ProcessPayment/" . $cartweaver->settings->paymentAuthName);
		}
	}else{
		echo("Invalid order number.");
	} /* END if($rsCWOrder_recordCount != 0) */
}elseif (isset($_GET["mode"])) {
	switch($_GET["mode"]) {
		case "return":
			echo("<p>Thank you for your payment. Your order will be processed shortly.</p>");
			break;
		case "cancel":
			echo("<p>You have chosen to cancel your payment. Your order will not be processed.</p>");
			break;
		default:
			echo("<p>Invalid mode submission.</p>");
			break;
	}
}else{
	/* no valid cart, redirect the user. */
	if(isset($_GET["mode"])) {
		if($_GET["mode"] == "return"){
			echo("<p>Thank you for your payment.</p>");
		}else{
			echo("<p>Your payment has been canceled.</p>");
		}
	//}else{		
		//header("Location: " . $cartweaver->settings->targetGoToCart);
		//exit(); 
	}
} /* end if($_SESSION["completeOrderID"] != 0) */

?>