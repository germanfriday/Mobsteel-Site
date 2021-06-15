<?php
require_once("application.php");

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
Name: OrderDetails.php
Description: Displays and administers status of a selected order.
================================================================
*/

/* Set location for highlighting in Nav Menu */
$strSelectNav = "Orders";
if(!isset($_GET["order_ID"])) {$_GET["order_ID"] = '';}

/* If DELETE was submitted, delete this order. */
if (isset($_POST["Delete"])){
/* First we delete the Order Skus */
	$query_rsCW = sprintf("DELETE FROM tbl_orderskus 
	WHERE orderSKU_OrderID = '%s'",$_POST["orderID"]);
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	
	/* Now we delete the order record itself */
	$query_rsCW = sprintf("DELETE FROM tbl_orders 
	WHERE order_ID = '%s'",$_POST["orderID"]);
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	header("Location: Orders.php");
	exit();
}

/* set default FormError variable */
$formError = "NONE";

/* If Update Query has been submited, update the record. */
if(isset($_POST["Update"])){
	if($_POST["order_Status"] == 3 && $_POST["order_ShipDate"] == "") {
	  $formError = "A shipped order must have a Ship Date";
	}
  
	if($formError == "NONE"){
		$_POST["order_ActualShipCharge"] = ($_POST["order_ActualShipCharge"] != "") ? floatval($_POST["order_ActualShipCharge"]) : "NULL";
		$_POST["order_ShipDate"] = ($_POST["order_ShipDate"] != "") ? $_POST["order_ShipDate"] : "NULL";
		$query_rsCW = sprintf("UPDATE tbl_orders 
				SET order_Status='%s'
					, order_ActualShipCharge = %s
					, order_ShipDate='%s'
					, order_ShipTrackingID='%s', 
					order_Notes = '%s'
			WHERE order_ID='%s'"
			,$_POST["order_Status"]
			,$_POST["order_ActualShipCharge"]
			,mySQLDate($_POST["order_ShipDate"],true)
			,$_POST["order_ShipTrackingID"]
			,$_POST["order_Notes"]
			,$_POST["orderID"]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);

		header("Location: Orders.php");
		exit();
	}/* END IF - $formError == "NONE" */  
}/* END IF - isset ('$_POST["Update"]') */

$query_rsCWOrder = sprintf("SELECT 
	ss.shipstatus_Name, 
	o.order_ID, 
	o.order_TransactionID, 
	o.order_Date, 
	o.order_Status, 
	o.order_CustomerID, 
	o.order_Tax, 
	o.order_Shipping, 
	o.order_Total, 
	o.order_ShipMeth_ID, 
	o.order_ShipDate, 
	o.order_ShipTrackingID, 
	o.order_Address1, 
	o.order_Address2, 
	o.order_City, 
	o.order_State, 
	o.order_Zip, 
	o.order_Country, 
	o.order_Notes, 
	o.order_ActualShipCharge, 
	o.order_ShipName, 
	c.cst_FirstName, 
	c.cst_LastName, 
	os.orderSKU_SKU, 
	p.product_Name, 
	os.orderSKU_Quantity, 
	os.orderSKU_UnitPrice, 
	os.orderSKU_SKUTotal, 
	sm.shipmeth_Name, 
	s.SKU_ID,
	s.SKU_MerchSKUID
FROM
	tbl_products p
	INNER JOIN tbl_skus s 
		ON p.product_ID = s.SKU_ProductID 
	INNER JOIN tbl_orderskus os 
		ON s.SKU_ID = os.orderSKU_SKU 
	INNER JOIN tbl_orders o 
		ON o.order_ID = os.orderSKU_OrderID 
	LEFT JOIN tbl_shipmethod sm 
		ON sm.shipmeth_ID = o.order_ShipMeth_ID 
	INNER JOIN tbl_list_shipstatus ss 
		ON ss.shipstatus_id = o.order_Status 
	INNER JOIN tbl_customers c 
		ON c.cst_ID = o.order_CustomerID
WHERE 
	o.order_ID='%s'
ORDER BY 
	p.product_Name, 
	s.SKU_Sort",$_GET["order_ID"]);
$rsCWOrder = $cartweaver->db->executeQuery($query_rsCWOrder);
$rsCWOrder_recordCount = $cartweaver->db->recordCount;
$row_rsCWOrder = $cartweaver->db->db_fetch_assoc($rsCWOrder);

$query_rsCWShipStatusList = "SELECT shipstatus_id, shipstatus_Name, shipstatus_Sort 
FROM tbl_list_shipstatus
ORDER BY shipstatus_Sort";
$rsCWShipStatusList = $cartweaver->db->executeQuery($query_rsCWShipStatusList);
$rsCWShipStatusList_recordCount = $cartweaver->db->recordCount;
$row_rsCWShipStatusList = $cartweaver->db->db_fetch_assoc($rsCWShipStatusList);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CW Admin: Transaction Details</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
<!--
/* Date Pop Up */
// function to load the calendar window.
function ShowCalendar(FormName, FieldName) {
	var curValue = eval("document."+FormName+"."+FieldName+".value");
	window.open("DatePopup.php?getDate="+ curValue + "&FormName=" + FormName + "&FieldName=" + FieldName, "CalendarWindow", "width=250,height=200");
}

//-->
</script>
<link href="assets/admin.css" rel="stylesheet" type="text/css">
<link href="assets/orderprint.css" rel="stylesheet" type="text/css" media="print">
</head>
<body>
<?php include("CWIncNav.php");?>
<div id="divMainContent">
  <h1>Transaction Details</h1>
  <?php if($rsCWOrder_recordCount != 0) { ?>
  <table>
    <tr>
      <th>Order ID</th>
      <th>Date</th>
      <th>Customer ID</th>
    </tr>
    <tr align="center">
      <td><?php echo($row_rsCWOrder["order_ID"]);?></td>
      <td><?php echo(cwDateFormat($row_rsCWOrder["order_Date"]));?></td>
      <td><a href="CustomerDetails.php?cst_ID=<?php echo($row_rsCWOrder["order_CustomerID"]);?>"><?php echo($row_rsCWOrder["order_CustomerID"]);?></a></td>
    </tr>
  </table>
  <h2> Ship To </h2>
  <p><strong><?php echo($row_rsCWOrder["order_ShipName"]);?></strong><br>
    <?php echo($row_rsCWOrder["order_Address1"]);?>
    <?php if($row_rsCWOrder["order_Address2"] != "") {
      echo("<br>" . $row_rsCWOrder["order_Address2"]);
     } ?>
    <br>
    <?php echo($row_rsCWOrder["order_City"]);?>, <?php echo($row_rsCWOrder["order_State"]);?> <?php echo($row_rsCWOrder["order_Zip"]);?><br>
    <?php echo($row_rsCWOrder["order_Country"]);?></p>
	<?php /* Show Transaction Id captured from the payment gateway. */
		if(strtolower($cartweaver->settings->paymentAuthType) != "none"){
		echo("<p>Gateway Transaction ID: " . $row_rsCWOrder["order_TransactionID"] . "</p>");
		}?>
  <div id="divOrderShippingInfo">
    <?php if($formError != "NONE") {
	echo("<br><span class=\"txt-Error\">$formError</span><br>");
}?>
    <?php 	/* Set default form values for the form */
	if(!isset($_POST["order_Status"])) {$_POST["order_Status"] = $row_rsCWOrder["order_Status"];}
	if(!isset($_POST["order_ShipDate"])) {$_POST["order_ShipDate"] = $row_rsCWOrder["order_ShipDate"];}
	if(!isset($_POST["order_ShipTrackingID"])) {$_POST["order_ShipTrackingID"] = $row_rsCWOrder["order_ShipTrackingID"];}
	if(!isset($_POST["order_Notes"])) {$_POST["order_Notes"] = $row_rsCWOrder["order_Notes"];}
?>
    <form name="OrderStatus" method="POST" action="<?php echo($cartweaver->thisPageQS);?>">
      <table>
        <caption>
        Order Status
        </caption>
        <tr>
          <th align="right"> Order Status:
            <input name="orderID" type="hidden" value="<?php echo($row_rsCWOrder["order_ID"]);?>">
          </th>
          <td><?php if ($row_rsCWOrder["order_Status"] != 4 && $row_rsCWOrder["order_Status"] != 5) {
			   /* If order status is NOT Canceled */
			   ?>
            <select name="order_Status" id="order_Status">
              <?php if($row_rsCWOrder["order_Status"] != 3) {
					  /* If order status is NOT Shipped */
					  do {
						if($_POST["order_Status"] == $row_rsCWShipStatusList["shipstatus_id"]) { 
						  echo('<option value="' . $row_rsCWShipStatusList["shipstatus_id"] . '" selected>' . $row_rsCWShipStatusList["shipstatus_Name"] . "</option>");
						 }else{
						   echo('<option value="' . $row_rsCWShipStatusList["shipstatus_id"] . '">' . $row_rsCWShipStatusList["shipstatus_Name"] . "</option>");
						}
					   } while ($row_rsCWShipStatusList = $cartweaver->db->db_fetch_assoc($rsCWShipStatusList)); 
				}else{
				  /* If order status IS Shipped */ ?>
              <option value="3" selected>Shipped</option>
              <option value="5">Returned</option>
              <?php } ?>
            </select>
            <?php
			 
			} /* END if ($row_rsCWOrder["order_Status"] != 4 && $row_rsCWOrder["order_Status"] != 5) */
			if($row_rsCWOrder["order_Status"] == 4) {
			   echo("Order Canceled");
			 }elseif($row_rsCWOrder["order_Status"] == 5) {
			   echo("Order Returned");
			 } ?>
          </td>
        </tr>
        <tr>
          <th align="right">Shipping Method: </th>
          <td><?php echo($row_rsCWOrder["shipmeth_Name"]);?></td>
        </tr>
        <tr>
          <th align="right"> <?php echo($formError == "NONE") ? "Ship Date:" : '<span class="txt-formerror">Ship Date Required!</span>'; ?> </th>
          <td><input name="order_ShipDate" type="text" size="12" value="<?php echo(cwDateFormat($_POST["order_ShipDate"],true));?>">
            <a href="javascript:ShowCalendar('OrderStatus', 'order_ShipDate')"><img src="assets/images/calendar.gif" alt="Click to Select Date" width="16" height="16"></a> </td>
        </tr>
        <tr>
          <th align="right">Tracking ID: </th>
          <td><?php if($row_rsCWOrder["order_Status"] != 5) {
            echo('<input name="order_ShipTrackingID" type="text" id="order_ShipTrackingID" size="25" value="' . $row_rsCWOrder["order_ShipTrackingID"] . '">');
		  }else{
		    echo($row_rsCWOrder["order_ShipTrackingID"]);
		  } ?>
          </td>
        </tr>
        <tr>
          <th align="right">Actual Shipping Cost </th>
          <td><input name="order_ActualShipCharge" type="text" value="<?php echo($row_rsCWOrder["order_ActualShipCharge"]);?>"></td>
        </tr>
        <tr>
          <th align="right">Notes: </th>
          <td><textarea name="order_Notes" cols="50" rows="15" id="order_Notes"><?php echo($_POST["order_Notes"]);?></textarea></td>
        </tr>
      </table>
      <?php if($row_rsCWOrder["order_Status"] != 5 && $row_rsCWOrder["order_Status"] != 4) {
      echo('<input name="Update" type="submit" class="formButton" id="Update5" value="Update Order Status" style="margin-bottom:20px;">');
      }
	  /* Show Delete button is the order is not "Shipped" */
      if($row_rsCWOrder["order_Status"] != 3 && $row_rsCWOrder["order_Status"] != 5) {
	  echo('<br>
	  <input name="Delete" type="submit" class="formButton" id="Delete" onClick="return confirm(\'Are you SURE you want to DELETE this Order? This action cannot be undone.\')" value="Delete This Order">');
	 } ?>
    </form>
  </div>
  <h2>Order Details</h2>
  <table id="tblOrderDetails">
    <tr>
      <th>Product</th>
      <th>Qty.</th>
      <th>Price</th>
      <th> Total</th>
    </tr>
    <?php $recCounter = 0;
	do {
		$query_rsCWGetOptions = "SELECT 
					o.optiontype_Name, 
					s.option_Name
				FROM tbl_list_optiontypes o
					INNER JOIN tbl_skuoptions s
						ON o.optiontype_ID = s.option_Type_ID
					INNER JOIN tbl_skuoption_rel r 
						ON s.option_ID = r.optn_rel_Option_ID
				WHERE 
					r.optn_rel_SKU_ID = " . $row_rsCWOrder["orderSKU_SKU"] . "
				ORDER BY s.option_Sort";
$rsCWGetOptions = $cartweaver->db->executeQuery($query_rsCWGetOptions);
$rsCWGetOptions_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetOptions = $cartweaver->db->db_fetch_assoc($rsCWGetOptions);
?>
    <?php 
$theTotal = $row_rsCWOrder["order_Total"];
$theShipping = $row_rsCWOrder["order_Shipping"];
$theShippingType = $row_rsCWOrder["shipmeth_Name"];
$theTax = $row_rsCWOrder["order_Tax"];
		?>
    <tr class="<?php cwAltRow($recCounter++);?>">
      <td><?php echo($row_rsCWOrder["product_Name"]);?> (<?php echo($row_rsCWOrder["SKU_MerchSKUID"]);?>)
        <?php do { // Cartweaver repeat region
		 ?>
        <br>
        <strong style="margin-left: 10px;"><?php echo($row_rsCWGetOptions["optiontype_Name"]);?></strong>: <?php echo($row_rsCWGetOptions["option_Name"]);?>
        <?php } while ($row_rsCWGetOptions = $cartweaver->db->db_fetch_assoc($rsCWGetOptions)); ?>
      </td>
      <td align="center"><?php echo($row_rsCWOrder["orderSKU_Quantity"]);?></td>
      <td align="right"><?php echo(cartweaverMoney($row_rsCWOrder["orderSKU_UnitPrice"]));?></td>
      <td align="right"><?php echo(cartweaverMoney($row_rsCWOrder["orderSKU_SKUTotal"]));?></td>
    </tr>
    <?php } while ($row_rsCWOrder = $cartweaver->db->db_fetch_assoc($rsCWOrder)); 
	   ?>
    <tr>
      <th colspan="3" align="right">Subtotal: </th>
      <td align="right"><?php echo(cartweaverMoney($theTotal-($theShipping + $theTax)));?></td>
    </tr>
	<?php if($row_rsCWOrder["order_ShipMeth_ID"] != 0) { ?>
    <tr>
      <th colspan="3" align="right">Shipping (<?php echo($theShippingType);?>): </th>
      <td align="right"><?php echo(cartweaverMoney($theShipping));?></td>
    </tr>
	<?php } /* END if($row_rsCWOrder["order_ShipMeth_ID"] != 0) */?>
    <tr>
      <th colspan="3" align="right">Tax: </th>
      <td align="right"><?php echo(cartweaverMoney($theTax));?></td>
    </tr>
    <tr>
      <th colspan="3" align="right">Order Total: </th>
      <td align="right"><strong><?php echo(cartweaverMoney($theTotal));?></strong></td>
    </tr>
  </table>
  <?php }else{ 
	echo("<p>Invalid order number</p>");
	} ?>
</div>
</body>
</html>
<?php
cwDebugger($cartweaver);
?>
