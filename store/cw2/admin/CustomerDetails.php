<?php require_once("application.php");
/* 
================================================================
Application Info: 
Cartweaver© 2002 - 2005, All Rights Reserved.
Developer Info: 
	Application Dynamics Inc.
	1560 NE 10th
	East Wenatchee, WA 98802
	Support Info: http://www.cartweaver.com/go/phphelp

Cartweaver Version: 2.5  -  Date: 04/25/2006
================================================================
Name: CustomerDetails.php
Description: Administers individual customer data
================================================================
*/
/* Set location for highlighting in Nav Menu */
$strSelectNav = "Customers";
if(!isset($_GET["cst_ID"])) {
	$_GET["cst_ID"] = 0;
}

/* If form has been submitted, Update or Delete Customer */
if(isset($_POST["Update"])){
	$query_rsCW = sprintf("UPDATE tbl_customers SET 
	cst_FirstName='%s'
	, cst_LastName='%s'
	, cst_Address1='%s'
	, cst_Address2='%s'
	, cst_City='%s'
	, cst_Zip='%s'
	, cst_ShpName='%s'
	, cst_ShpAddress1='%s'
	, cst_ShpAddress2='%s'
	, cst_ShpCity='%s'
	, cst_ShpZip='%s'
	, cst_Phone='%s'
	, cst_Email='%s'
	, cst_Username='%s'
	, cst_Password='%s' 
	WHERE cst_ID='%s'",
	   $_POST['cst_FirstName'],
	   $_POST['cst_LastName'],
	   $_POST['cst_Address1'],
	   $_POST['cst_Address2'],
	   $_POST['cst_City'],
	   $_POST['cst_Zip'],
	   $_POST['cst_ShpName'],
	   $_POST['cst_ShpAddress1'],
	   $_POST['cst_ShpAddress2'],
	   $_POST['cst_ShpCity'],
	   $_POST['cst_ShpZip'],
	   $_POST['cst_Phone'],
	   $_POST['cst_Email'],
	   $_POST['cst_Username'],
	   $_POST['cst_Password'],
	   $_GET['cst_ID']);
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	
		/* Update Billing state */
	$query_rsCW = sprintf("UPDATE tbl_custstate SET
		CustSt_StPrv_ID = %d
		WHERE CustSt_Cust_ID = '%s' AND CustSt_Destination = 'BillTo'",  
			$_POST["cst_BillState"],
			$_GET["cst_ID"]);
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	
		/* Update Shipping State */
	
	$query_rsCW = sprintf("UPDATE tbl_custstate SET
		CustSt_StPrv_ID = %d
		WHERE CustSt_Cust_ID = '%s'
		AND CustSt_Destination = 'ShipTo'
		",$_POST["cst_ShipState"],
		$_GET["cst_ID"]);
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	
}else if(isset($_POST["Delete"])) {

	$query_rsCW = sprintf("DELETE FROM tbl_custstate 
	WHERE CustSt_Cust_ID = '%s'",$_GET["cst_ID"]);
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	
	$query_rsCW = sprintf("DELETE FROM tbl_customers WHERE cst_ID = '%s'",$_GET["cst_ID"]);
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);

	header("Location: Customers.php"); 
}

$query_rsCWGetCustDetails = sprintf("SELECT cst_ID, cst_Type_ID, cst_FirstName, cst_LastName, 
cst_Address1, cst_Address2, cst_City, cst_Zip, 
cst_ShpName, cst_ShpAddress1, cst_ShpAddress2, 
cst_ShpCity, cst_ShpZip, cst_Phone, cst_Email, 
cst_Username, cst_Password
FROM tbl_customers
WHERE cst_ID = '%s'",$_GET["cst_ID"]);
$rsCWGetCustDetails = $cartweaver->db->executeQuery($query_rsCWGetCustDetails);
$rsCWGetCustDetails_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetCustDetails = $cartweaver->db->db_fetch_assoc($rsCWGetCustDetails);

$query_rsCWGetCustOrders = sprintf("SELECT 
	o.order_ID, 
	o.order_Date, 
	o.order_Total, 
	os.orderSKU_SKU, 
	s.SKU_MerchSKUID, 
	p.product_Name
FROM 
	tbl_products p
	INNER JOIN tbl_skus s
		ON p.product_ID = s.SKU_ProductID
	INNER JOIN tbl_orderskus os			
		ON s.SKU_ID = os.orderSKU_SKU
	INNER JOIN tbl_orders o
		ON o.order_ID = os.orderSKU_OrderID	
WHERE 
	o.order_CustomerID = '%s'
ORDER BY 
	o.order_Date DESC",$_GET["cst_ID"]);
$rsCWGetCustOrders = $cartweaver->db->executeQuery($query_rsCWGetCustOrders);
$rsCWGetCustOrders_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetCustOrders = $cartweaver->db->db_fetch_assoc($rsCWGetCustOrders);

$query_rsCWGetBillTo = sprintf("SELECT c.country_Name, 
	s.stprv_Name, 
	c.country_ID, 
	s.stprv_ID
FROM 
	tbl_list_countries c
	INNER JOIN tbl_stateprov s 
		ON c.country_ID = s.stprv_Country_ID
	INNER JOIN tbl_custstate cs 
		ON s.stprv_ID = cs.CustSt_StPrv_ID
WHERE 
	cs.CustSt_Cust_ID = '%s' 
	AND cs.CustSt_Destination = 'BillTo'",$_GET["cst_ID"]);
$rsCWGetBillTo = $cartweaver->db->executeQuery($query_rsCWGetBillTo);
$rsCWGetBillTo_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetBillTo = $cartweaver->db->db_fetch_assoc($rsCWGetBillTo);

$query_rsCWGetShipTo = sprintf("SELECT c.country_Name, 
	s.stprv_Name, 
	c.country_ID, 
	s.stprv_ID, 
	cs.CustSt_Destination
FROM 
	tbl_list_countries c
	INNER JOIN tbl_stateprov s 
		ON c.country_ID = s.stprv_Country_ID 
	INNER JOIN tbl_custstate cs 
		ON s.stprv_ID = cs.CustSt_StPrv_ID
WHERE 
	cs.CustSt_Cust_ID = '%s' 
	AND cs.CustSt_Destination = 'ShipTo'",$_GET["cst_ID"]);
$rsCWGetShipTo = $cartweaver->db->executeQuery($query_rsCWGetShipTo);
$rsCWGetShipTo_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetShipTo = $cartweaver->db->db_fetch_assoc($rsCWGetShipTo);

$query_rsCWGetStateList = "SELECT 
	c.country_Name, 
	s.stprv_ID, 
	s.stprv_Name
FROM tbl_list_countries c 
INNER JOIN tbl_stateprov s 
ON c.country_ID = s.stprv_Country_ID
WHERE 
	s.stprv_Archive = 0 
	AND c.country_Archive = 0
ORDER BY 
	c.country_Sort, 
	s.stprv_Name";
$rsCWGetStateList = $cartweaver->db->executeQuery($query_rsCWGetStateList);
$rsCWGetStateList_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetStateList = $cartweaver->db->db_fetch_assoc($rsCWGetStateList);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CW Admin: Customer Details</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="assets/admin.css" rel="stylesheet" type="text/css">
<style>
table.custdetails{width:450px;}
.custdetails input {width:250px;}
</style>
</head>
<body>
<?php include("CWIncNav.php");?>
<div id="divMainContent">
  <h1>Customer Details</h1>
  <form action="<?php echo($cartweaver->thisPageQS);?>" method="POST" name="editCustomer">
    <table class="custdetails">
      <tr>
        <th align="right">Customer ID:</th>
        <td><?php echo($row_rsCWGetCustDetails["cst_ID"]);?></td>
      </tr>
      <tr>
        <th align="right">First Name: </th>
        <td><input name="cst_FirstName" type="text" id="cst_FirstName" value="<?php echo($row_rsCWGetCustDetails["cst_FirstName"]);?>"></td>
      </tr>
      <tr>
        <th align="right">Last Name: </th>
        <td><input name="cst_LastName" type="text" id="cst_LastName" value="<?php echo($row_rsCWGetCustDetails["cst_LastName"]);?>"></td>
      </tr>
      <tr>
        <th align="right">Email:</th>
        <td><input type="text" name="cst_Email" value="<?php echo($row_rsCWGetCustDetails["cst_Email"]);?>">
        </td>
      </tr>
      <tr>
        <th align="right">Phone:</th>
        <td><input type="text" name="cst_Phone" value="<?php echo($row_rsCWGetCustDetails["cst_Phone"]);?>">
        </td>
      </tr>
      <tr>
        <th align="right">Username:</th>
        <td><input name="cst_Username" type="text" id="cst_Username" value="<?php echo($row_rsCWGetCustDetails["cst_Username"]);?>">
        </td>
      </tr>
      <tr>
        <th align="right">Password: </th>
        <td><input type="text" name="cst_Password" value="<?php echo($row_rsCWGetCustDetails["cst_Password"]);?>">
        </td>
      </tr>
    </table>
    <table class="custdetails">
      <caption>
      Billing Information
      </caption>
      <tr>
        <th align="right">Address:</th>
        <td valign="top"><input type="text" name="cst_Address1" value="<?php echo($row_rsCWGetCustDetails["cst_Address1"]);?>">
          <br>
          <input type="text" name="cst_Address2" value="<?php echo($row_rsCWGetCustDetails["cst_Address2"]);?>">
        </td>
      </tr>
      <tr>
        <th align="right">City:</th>
        <td valign="top"><input type="text" name="cst_City" value="<?php echo($row_rsCWGetCustDetails["cst_City"]);?>">
        </td>
      </tr>
      <tr>
        <th align="right">State/Prov:</th>
        <td valign="top"><select name="cst_BillState">
            <option value="0">State</option>
<?php $lastTFM_nest = "";
do {  
	$tfm_nest = $row_rsCWGetStateList['country_Name'];
	if ($lastTFM_nest != $tfm_nest) {
		$lastTFM_nest = $tfm_nest;
		echo("<option value=\"\">----$tfm_nest----</option>");
	} ?>
            <option value="<?php echo $row_rsCWGetStateList['stprv_ID']?>" <?php if ($row_rsCWGetStateList["stprv_ID"] == $row_rsCWGetBillTo["stprv_ID"]) {echo(' selected="selected"');}?>><?php echo $row_rsCWGetStateList['stprv_Name']?></option>
<?php } while ($row_rsCWGetStateList = $cartweaver->db->db_fetch_assoc($rsCWGetStateList));
$rows = $cartweaver->db->db_num_rows($rsCWGetStateList);
if($rows > 0) {
	$cartweaver->db->db_data_seek($rsCWGetStateList, 0);
	$row_rsCWGetStateList = $cartweaver->db->db_fetch_assoc($rsCWGetStateList);
}
?>
          </select>
        </td>
      </tr>
      <tr>
        <th align="right">Zip:</th>
        <td valign="top"><input type="text" name="cst_Zip" value="<?php echo($row_rsCWGetCustDetails["cst_Zip"]);?>" size="8">
        </td>
      </tr>
      <tr>
        <th align="right">Country:</th>
        <td valign="top"><?php echo($row_rsCWGetBillTo["country_Name"]);?> </td>
      </tr>
    </table>
    <table class="custdetails">
      <caption>
      Shipping Information
      </caption>
      <tr>
        <th align="right" scope="row">Name: </th>
        <td><input name="cst_ShpName" type="text" id="cst_ShpName" value="<?php echo($row_rsCWGetCustDetails["cst_ShpName"]);?>"></td>
      </tr>
      <tr>
        <th align="right" scope="row">Address: </th>
        <td><input type="text" name="cst_ShpAddress1" value="<?php echo($row_rsCWGetCustDetails["cst_ShpAddress1"]);?>">
          <br>
          <input type="text" name="cst_ShpAddress2" value="<?php echo($row_rsCWGetCustDetails["cst_ShpAddress2"]);?>"></td>
      </tr>
      <tr>
        <th align="right" scope="row">City: </th>
        <td><input type="text" name="cst_ShpCity" value="<?php echo($row_rsCWGetCustDetails["cst_ShpCity"]);?>"></td>
      </tr>
      <tr>
        <th align="right" scope="row">State/Prov: </th>
        <td><select name="cst_ShipState">
            <option value="0">State</option>
<?php $cartweaver->db->db_data_seek($rsCWGetStateList, 0);
$lastTFM_nest = "";
do {  
	$tfm_nest = $row_rsCWGetStateList['country_Name'];
	if ($lastTFM_nest != $tfm_nest) {
		$lastTFM_nest = $tfm_nest;
		echo("<option value=\"\">----$tfm_nest----</option>");
	} ?><option value="<?php echo $row_rsCWGetStateList['stprv_ID']?>" <?php if ($row_rsCWGetStateList["stprv_ID"] == $row_rsCWGetShipTo["stprv_ID"]) {echo(' selected="selected"');}?>><?php echo $row_rsCWGetStateList['stprv_Name']?></option>
<?php
} while ($row_rsCWGetStateList = $cartweaver->db->db_fetch_assoc($rsCWGetStateList));
$rows = $cartweaver->db->db_num_rows($rsCWGetStateList);
if($rows > 0) {
	$cartweaver->db->db_data_seek($rsCWGetStateList, 0);
	$row_rsCWGetStateList = $cartweaver->db->db_fetch_assoc($rsCWGetStateList);
}
?></select></td>
      </tr>
      <tr>
        <th align="right" scope="row">Zip: </th>
        <td><input type="text" name="cst_ShpZip" value="<?php echo($row_rsCWGetCustDetails["cst_ShpZip"]);?>" size="8"></td>
      </tr>
      <tr>
        <th align="right" scope="row">Country: </th>
        <td><?php echo($row_rsCWGetShipTo["country_Name"]);?> </td>
      </tr>
    </table>
    <p>
      <input name="Update" type="submit" class="formButton" id="Update3" value="Update">
<?php 
if($rsCWGetCustOrders_recordCount == 0) { /* If there are no orders hide this section */
	echo('<input name="Delete" type="submit" class="formButton" id="Delete" value="No Orders - Delete">');
} ?>
    </p>
  </form>
  <?php if($rsCWGetCustOrders_recordCount != 0) { /* If there are no orders hide this section */ ?>
  <h1>Order History</h1>
  <table class="custdetails">
    <tr>
      <th>Order ID</th>
      <th>Order Date</th>
      <th>Products</th>
      <th>Order Total</th>
      <th>View</th>
    </tr>
<?php 
/* Set variables for control cell row colors */
$rowCounter = 0;
$rsCWGetDistinctCustOrders = $cartweaver->db->queryOfQuery($rsCWGetCustOrders, explode(",","order_ID,order_Total,SKU_MerchSKUID,order_Date,product_Name"), true, null, null);
foreach ($rsCWGetDistinctCustOrders as $key => $row_rsCWGetDistinctCustOrders) {  
?>
    <tr class="<?php cwAltRow($rowCounter++);?>">
      <td><?php echo($row_rsCWGetDistinctCustOrders["order_ID"]);?></td>
      <td align="right"><?php echo(cwDateFormat($row_rsCWGetDistinctCustOrders["order_Date"]));?></td>
      <td><?php echo($row_rsCWGetDistinctCustOrders["product_Name"]);?> <span class="smallprint">(<?php echo($row_rsCWGetDistinctCustOrders["SKU_MerchSKUID"]);?>)</span><br></td>
      <td align="right"><?php echo(cartweaverMoney($row_rsCWGetDistinctCustOrders["order_Total"]));?></td>
      <td align="center"><a href="OrderDetails.php?order_ID=<?php echo($row_rsCWGetDistinctCustOrders["order_ID"]);?>"> <img src="assets/images/viewdetails.gif" alt="View Order Details" width="15" height="15"></a></td>
    </tr>
    <?php } // End rsCWGetDistinctCustOrders loop ?>
  </table>
  <?php } /* END if($rsCWGetCustOrders_recordCount != 0)*/ ?>
</div>
</body>
</html>
<?php
cwDebugger($cartweaver);
?>
