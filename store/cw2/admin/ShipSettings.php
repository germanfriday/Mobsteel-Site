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
Name: ShipSettings.php
Description: Select the settings by which Shipping wil be calculated.
================================================================
*/
/* Set location for highlighting in Nav Menu */
$strSelectNav = "ShippingTax";

/* Update Company information if form has been submitted */
if (isset($_POST["update"])){
	$query_rsCW = sprintf("UPDATE tbl_companyinfo 
	SET comp_ChargeBase = %s
	, comp_ChargeWeight = %s
	, comp_ChargeExtension = %s 
	, comp_enableshipping = %s
	WHERE comp_ID = %s"
	,$_POST["comp_ChargeBase"]
	,$_POST["comp_ChargeWeight"]
	,$_POST["comp_ChargeExtension"]
	,$_POST["comp_EnableShipping"]
	,$_POST["comp_ID"]);
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	/* Reset the application variables */
	$_SESSION["ChargeShipBase"] = $_POST["comp_ChargeBase"];
	$_SESSION["ChargeShipByWeight"] = $_POST["comp_ChargeWeight"];
	$_SESSION["ChargeShipExtension"] = $_POST["comp_ChargeExtension"];
	$_SESSION["EnableShipping"] = $_POST["comp_EnableShipping"];

	header("Location: " . $cartweaver->thisLocation);
	exit();
}
/*Get Company Information*/
$query_rsCWGetCompData = "SELECT comp_ID, 
comp_ChargeWeight, 
comp_ChargeExtension, 
comp_enableshipping, 
comp_ChargeBase
FROM tbl_companyinfo";
$rsCWGetCompData = $cartweaver->db->executeQuery($query_rsCWGetCompData);
$rsCWGetCompData_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetCompData = $cartweaver->db->db_fetch_assoc($rsCWGetCompData);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CW Admin: Customer List</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="assets/admin.css" rel="stylesheet" type="text/css">
</head>
<body> 
<?php include("CWIncNav.php");?>
<div id="divMainContent"> 
  <form name="compinfo" action="<?php echo($cartweaver->thisPage);?>" method="POST"> 
    <h1>Shipping Calculation settings</h1> 
    <br> 
	<table> 
	<caption>
	Shipping Settings 
	</caption> 
		<tr> 
			<th align="right"> <label for="comp_EnableShipping">Enable Shipping: </label> </th> 
			<td>
			<select name="comp_EnableShipping">
				<?php if($row_rsCWGetCompData["comp_enableshipping"] == 1) { ?>
				<option value="1" selected>Yes</option> 
				<option value="0">No</option> 
				<?php }else{
				echo('<option value="1">Yes</option>'); 
				echo('<option value="0" selected>No</option>');
				}?>
			</select>
			</td> 
		</tr>	
		<tr> 
			<th align="right"> <label for="comp_ChargeBase">Charge Base: </label> </th> 
			<td>
			<select name="comp_ChargeBase"> 
				<?php if($row_rsCWGetCompData["comp_ChargeBase"] == 1) { ?>
				<option value="1" selected>Yes</option> 
				<option value="0">No</option> 
				<?php }else{
				echo('<option value="1">Yes</option>'); 
				echo('<option value="0" selected>No</option>');
				}?>
			</select>
			</td> 
		</tr> 
		<tr> 
			<th align="right"> <label for="comp_ChargeWeight">Charge Weight Range: </label> </th> 
			<td> 
			<select name="comp_ChargeWeight"> 
				<?php 
				if($row_rsCWGetCompData["comp_ChargeWeight"] == 1) { ?>
				<option value="1" selected>Yes</option> 
				<option value="0">No</option> 
				<?php 
				}else{
				echo('<option value="1">Yes</option>'); 
				echo('<option value="0" selected>No</option>');
				}?>
			</select>
			</td> 
		</tr> 
		<tr> 
			<th align="right"> <label for="comp_ChargeExtension">Charge Location Extension: </label> </th> 
			<td>
			<select name="comp_ChargeExtension"> 
				<?php 
				if($row_rsCWGetCompData["comp_ChargeExtension"] == 1) { ?>
				<option value="1" selected>Yes</option> 
				<option value="0">No</option> 
				<?php 
				}else{
				echo('<option value="1">Yes</option>'); 
				echo('<option value="0" selected>No</option>');
				}?>
			</select>
			</td> 
		</tr> 
    </table> 
    <input name="comp_ID" type="hidden" value="<?php echo($row_rsCWGetCompData["comp_ID"]);?>"> 
    <input name="update" type="submit" class="formButton" id="Update2" value="Update"> 
  </form> 
</div> 
</body>
</html>
<?php
cwDebugger($cartweaver);
?>
