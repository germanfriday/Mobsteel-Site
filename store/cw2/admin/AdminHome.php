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

Cartweaver Version: 2.0  -  Date: 07/22/2005
================================================================
Name: AdminHome.php
Description: Admin home page and dispalys basics sales reports.
================================================================
*/

$query_rsCWNewOrders = "SELECT o.order_Date
, o.order_ID
, o.order_Total 
FROM tbl_orders o
WHERE o.order_Date
BETWEEN '" . $_SESSION["LastLogin"] . "' AND '" . date("Y-m-d H:i:s") . "'
ORDER BY o.order_Date";
$rsCWNewOrders = $cartweaver->db->executeQuery($query_rsCWNewOrders);
$rsCWNewOrders_recordCount = $cartweaver->db->recordCount;
$row_rsCWNewOrders = $cartweaver->db->db_fetch_assoc($rsCWNewOrders);

$query_rsCWToVerify = "SELECT o.order_Date, o.order_ID, o.order_Total 
FROM tbl_orders o
WHERE o.order_Status = 1
ORDER BY o.order_Date";
$rsCWToVerify = $cartweaver->db->executeQuery($query_rsCWToVerify);
$rsCWToVerify_recordCount = $cartweaver->db->recordCount;
$row_rsCWToVerify = $cartweaver->db->db_fetch_assoc($rsCWToVerify);

$query_rsCWToShip = "SELECT o.order_ID, o.order_Date, o.order_Total, s.shipmeth_Name
FROM tbl_shipmethod s INNER JOIN tbl_orders o ON s.shipmeth_ID = o.order_ShipMeth_ID
WHERE o.order_Status = 2
ORDER BY o.order_Date";
$rsCWToShip = $cartweaver->db->executeQuery($query_rsCWToShip);
$rsCWToShip_recordCount = $cartweaver->db->recordCount;
$row_rsCWToShip = $cartweaver->db->db_fetch_assoc($rsCWToShip);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CW Admin: Home</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="assets/admin.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php include("CWIncNav.php");?>
<div id="divMainContent">
  <h1>Mobsteel Administraion Section </h1>
  <h2>Order Status</h2>
  <h3>New Orders</h3>
<?php 
if($rsCWNewOrders_recordCount == 0) {
	echo("<p>No new orders");
}else{
	echo($rsCWNewOrders_recordCount . " new " . ($rsCWNewOrders_recordCount < 1) ? "orders" : "order");
}
echo(" since " . cwDateFormat($_SESSION["LastLogin"]) . "</p>");		
if($rsCWNewOrders_recordCount > 0){ ?>
  <table>
    <col />
    <col style="text-align: right;" />
    <col style="text-align: center;" />
    <tr>
      <th>Order Date</th>
      <th>Total</th>
      <th>View</th>
    </tr>
<?php 
	$recCounter = 0;
	do {
		?>
    <tr class="<?php cwAltRow($recCounter++);?>">
      <td><?php echo(date("Y-m-d H:i:s",strtotime($row_rsCWNewOrders["order_Date"])));?></td>
      <td><?php echo(cartweaverMoney($row_rsCWNewOrders["order_Total"]));?></td>
      <td><a href="OrderDetails.php?order_ID=<?php echo($row_rsCWNewOrders["order_ID"]);?>"><img src="assets/images/viewdetails.gif" alt="View Order" width="15" height="15" border="0"></a></td>
    </tr>
<?php  	
	}  while ($row_rsCWNewOrders = $cartweaver->db->db_fetch_assoc($rsCWNewOrders)); ?>
  </table>
<?php 
} ?>
  <h3>Unverified Orders</h3>
  <p><?php echo($rsCWToVerify_recordCount);
	echo ($rsCWToVerify_recordCount != 1) ? " orders" : " order";?></p>
<?php
if($rsCWToVerify_recordCount > 0) { ?>
  <table>
    <col />
    <col style="text-align: right;" />
    <col style="text-align: center;" />
    <tr>
      <th>Order Date</th>
      <th>Total</th>
      <th>View</th>
    </tr>
<?php 
	$recCounter = 0;
	do {
		?>
    <tr class="<?php cwAltRow($recCounter++);?>">
      <td><?php echo(cwDateFormat($row_rsCWToVerify["order_Date"]));?></td>
      <td><?php echo(cartweaverMoney($row_rsCWToVerify["order_Total"]));?></td>
      <td><a href="OrderDetails.php?order_ID=<?php echo($row_rsCWToVerify["order_ID"]);?>"><img src="assets/images/viewdetails.gif" alt="View Order" width="15" height="15" border="0"></a></td>
    </tr>
<?php 
	}  while ($row_rsCWToVerify = $cartweaver->db->db_fetch_assoc($rsCWToVerify)); ?>
  </table>
<?php 
} ?>
  <h3>Orders to ship</h3>
  <p><?php echo($rsCWToShip_recordCount . (($rsCWToShip_recordCount != 1) ? " orders" : " order"));?></p>
<?php
if($rsCWToShip_recordCount > 0) { ?>
  <table>
    <col />
    <col style="text-align: right;" />
    <col />
    <col style="text-align: center;" />
    <tr>
      <th>Order Date</th>
      <th>Total</th>
      <th>Ship Method</th>
      <th>View</th>
    </tr>
<?php 
	$recCounter = 0;
	do {
	?>
    <tr class="<?php cwAltRow($recCounter++);?>">
      <td><?php echo(cwDateFormat($row_rsCWToShip["order_Date"]));?></td>
      <td><?php echo(cartweaverMoney($row_rsCWToShip["order_Total"]));?></td>
      <td><?php echo($row_rsCWToShip["shipmeth_Name"]);?></td>
      <td><a href="OrderDetails.php?order_ID=<?php echo($row_rsCWToShip["order_ID"]);?>"><img src="assets/images/viewdetails.gif" alt="View Order" width="15" height="15" border="0"></a></td>
    </tr>
<?php  	
	}  while ($row_rsCWToShip = $cartweaver->db->db_fetch_assoc($rsCWToShip)); ?>
  </table>
<?php 
} ?>
</div>
</body>
</html>
<?php
cwDebugger($cartweaver);
?>