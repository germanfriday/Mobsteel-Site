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
Name: ShipMethods.php
Description: Here we set available shipping methods, the country 
they are associated with and a base charge, if any, for each. 
Base charge is elective, if no base charge is desired the vale 
should be 0. Bas charge can be used as a flat rate or as a base 
on which to add by weight shipping and shipping extensions.
================================================================
*/

/* Set location for highlighting in Nav Menu */
$strSelectNav = "ShippingTax";
$endLoop = false;
/* Set Page Archive Status */
$methodView = (isset($_GET["MethodView"])) ? $_GET["MethodView"] : 0;

/* ARCHIVE Record */
if(isset($_GET["SetTo"])){
	$query_rsCW = sprintf("UPDATE tbl_shipmethod 
	  SET shipmeth_Archive = %s 
	  WHERE shipmeth_ID = %s",$_GET["SetTo"],$_GET["ID"]);
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);

	header("Location: " . $cartweaver->thisLocation);
	exit();
}

/* ADD Record */
if(isset($_POST["AddRecord"])){
	$query_rsCW = sprintf("INSERT INTO tbl_shipmethod 
	(shipmeth_Sort, shipmeth_Name, shipmeth_Rate,shipmeth_Archive) 
	VALUES (%s, '%s', %s, 0)",
	$_POST["shipmeth_Sort"],
	$_POST["shipmeth_Name"],
	$_POST["shipmeth_Rate"]);
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	  
	/* get the new method id we just added */
	$query_getNewMethID = "SELECT shipmeth_ID AS newID 
	  FROM tbl_shipmethod
	  ORDER BY shipmeth_ID DESC";
	$getNewMethID = $cartweaver->db->executeQuery($query_getNewMethID);
	$getNewMethID_recordCount = $cartweaver->db->recordCount;
	$row_getNewMethID = $cartweaver->db->db_fetch_assoc($getNewMethID);

	/* now add country / method relationship */
	$query_rsCW = sprintf("INSERT INTO tbl_shipmethcntry_rel 
	(shpmet_cntry_Meth_ID,shpmet_cntry_Country_ID)
	VALUES (%s, %s)",$row_getNewMethID["newID"],$_POST["country_ID"]);
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);

	header("Location: " . $cartweaver->thisLocation);
	exit();
}
/* DELETE Record */
if(isset($_GET["DeleteRecord"])) {
	/* DELETE Country Relationship */
	$query_rsCW = sprintf("DELETE FROM tbl_shipmethcntry_rel 
	WHERE shpmet_cntry_Meth_ID = %s",$_GET["DeleteRecord"]);
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	/* DELETE Method */
	$query_rsCW = "DELETE FROM tbl_shipmethod WHERE shipmeth_ID = " . $_GET["DeleteRecord"];
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);

	header("Location: " . $cartweaver->thisLocation);
	exit();
}



/* Update Records */
if(isset($_POST["UpdateMethods"])){
	// If any delete checkboxes are checked, delete methods
	if(isset($_POST["deleteMethod"])) {
		$deleteMethods = join($_POST["deleteMethod"],",");
		/* DELETE Country Relationship */
		$query_rsCW = "DELETE FROM tbl_shipmethcntry_rel 
		WHERE shpmet_cntry_Meth_ID 
		IN ($deleteMethods)";
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
		/* DELETE Method */
		$query_rsCW = "DELETE FROM tbl_shipmethod WHERE shipmeth_ID IN ($deleteMethods) ";
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	}
	// If any archive checkboxes are checked, archive options
	if(isset($_POST["archiveMethod"])) {
		$archiveMethods = join($_POST["archiveMethod"],",");
		$query_Archive = sprintf("UPDATE tbl_shipmethod 
		SET shipmeth_Archive = %s WHERE shipmeth_ID IN ($archiveMethods)",
		$_POST["shipmeth_View"]);
		$rsCW = $cartweaver->db->executeQuery($query_Archive);
	}
	for($i = 0; $i<count($_POST["shipmeth_ID"]); $i++) {		
		/* EDIT Method */
		$query_rsCW = sprintf("UPDATE tbl_shipmethod 
		SET shipmeth_Rate = %d, 
		shipmeth_Sort = '%s', 
		shipmeth_Name = '%s' 
		WHERE shipmeth_ID = %d",
		$_POST["shipmeth_Rate"][$i],
		$_POST["shipmeth_Sort"][$i],
		$_POST["shipmeth_Name"][$i],
		$_POST["shipmeth_ID"][$i]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	}

	header("Location: " . $cartweaver->thisLocation);
	exit();
}

/* GET Record */
$query_rsCWGetShipping = "SELECT s.shipmeth_ID, s.shipmeth_Name, s.shipmeth_Rate, s.shipmeth_Sort,
c.country_Name, c.country_ID 
FROM (tbl_shipmethod s 
INNER JOIN tbl_shipmethcntry_rel r
ON s.shipmeth_ID = r.shpmet_cntry_Meth_ID) 
INNER JOIN tbl_list_countries c
ON r.shpmet_cntry_Country_ID = c.country_ID 
WHERE s.shipmeth_archive = $methodView
ORDER BY c.country_Sort, c.country_Name, 
s.shipmeth_Sort, s.shipmeth_Name";
$rsCWGetShipping = $cartweaver->db->executeQuery($query_rsCWGetShipping);
$rsCWGetShipping_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetShipping = $cartweaver->db->db_fetch_assoc($rsCWGetShipping);

/* Get Countries to populate Select Fields */
$query_rsCWGetCountry = "SELECT country_ID,country_Name
FROM tbl_list_countries";
$rsCWGetCountry = $cartweaver->db->executeQuery($query_rsCWGetCountry);
$rsCWGetCountry_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetCountry = $cartweaver->db->db_fetch_assoc($rsCWGetCountry);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CW Admin: Shipping Methods</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="assets/admin.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php include("CWIncNav.php");?>
<div id="divMainContent">
  <h1><?php echo(($methodView == "0") ? "Active" : "Archived");?> Shipping Methods View </h1>
  <p><?php 
	if($methodView == "0"){
		echo('<a href="' . $cartweaver->thisPage . '?MethodView=1">View Archived</a>');
	}else{
		echo('<a href="' . $cartweaver->thisPage . '?MethodView=0">View Active</a>');
	}?></p>
 
<?php if($methodView == "0") { ?>
  <form action="<?php echo($cartweaver->thisPage);?>" method="POST" name="AddRecord">
    <table>
      <caption>
      Add Ship Method
      </caption>
      <tr>
        <th>Sort</th>
        <th>Method</th>
        <th>Country</th>
        <th>Rate</th>
        <th>Add</th>
      </tr>
      <tr class="altRowEven">
        <td align="center">
          <input name="shipmeth_Sort" type="text" id="ship_sort" size="2" required="yes" validate="integer" message="Sort must be Numeric Only - no $ or ,">
        </td>
        <td align="center">
          <input name="shipmeth_Name" type="text" id="ship_method" size="15" required="yes" message="Ship Method Required">
        </td>
        <td align="center">
          <select name="country_ID">
			<?php do { // Cartweaver repeat region
?>
			<option value="<?php echo($row_rsCWGetCountry["country_ID"]);?>"><?php echo($row_rsCWGetCountry["country_Name"]);?></option>
			<?php } while ($row_rsCWGetCountry = $cartweaver->db->db_fetch_assoc($rsCWGetCountry)); ?>
          </select>
        </td>
        <td align="center">
          <input name="shipmeth_Rate" type="text" id="ship_rate" size="15" required="yes" validate="float" message="Rate must be Numeric Only - no $ or ,">
        </td>
        <td align="center">
          <input name="AddRecord" type="submit" class="formButton" id="AddRecord" value="Add">
        </td>
      </tr>
    </table>
  </form>
<?php } /* END if($methodView == "0") */ ?>
  
<?php /* Only show table if we have records */
if($rsCWGetShipping_recordCount != 0) { ?>
<form name="EditRecord" method="POST" action="<?php echo($cartweaver->thisPage);?>">
<?php
$lastTFM_nest = "";
$recCounter = 0;
do { // Special repeat region to include nesting of country names
	$tfm_nest = $row_rsCWGetShipping["country_Name"];
	if ($lastTFM_nest != $tfm_nest) {
		$lastTFM_nest = $tfm_nest;
?>
<h2><?php echo($row_rsCWGetShipping["country_Name"]);?></h2>
<table>
<tr>
	<th>Method</th>
	<th>Rate</th>
	<th>Sort</th>
	<th>Delete</th>
	<th><?php echo(($methodView != "0") ? "Activate" : "Archive");?></th>
</tr>
<?php } // End nested region, continue loop
	$query_CheckOrder = "SELECT Count(order_ID) AS AreThereOrders
	FROM tbl_orders 
	WHERE order_ShipMeth_ID = " . $row_rsCWGetShipping["shipmeth_ID"];
	$checkOrder = $cartweaver->db->executeQuery($query_CheckOrder);
	$checkOrder_recordCount = $cartweaver->db->recordCount;
	$row_CheckOrder = $cartweaver->db->db_fetch_assoc($checkOrder);
	
	$query_CheckRange = "SELECT Count(ship_weightrange_ID) AS AreThereRanges
	FROM tbl_shipweights 
	WHERE ship_weightrange_Method_ID = " . $row_rsCWGetShipping["shipmeth_ID"];
	$checkRange = $cartweaver->db->executeQuery($query_CheckRange);
	$checkRange_recordCount = $cartweaver->db->recordCount;
	$row_CheckRange = $cartweaver->db->db_fetch_assoc($checkRange);
	?>
	<tr class="<?php cwAltRow($recCounter++);?>">
		<td align="right"><?php echo($recCounter);?>
		<input name="country_ID[]" type="hidden" value="<?php echo($row_rsCWGetShipping["country_ID"]);?>"/>
		<input name="shipmeth_ID[]" type="hidden" id="shipmeth_ID" value="<?php echo($row_rsCWGetShipping["shipmeth_ID"]);?>">
		<input name="shipmeth_Name[]" size="25" value="<?php echo($row_rsCWGetShipping["shipmeth_Name"]);?>" /> 
		</td>
		<td align="center">
		<input name="shipmeth_Rate[]" type="text" value="<?php echo($row_rsCWGetShipping["shipmeth_Rate"]);?>" size="6" required="yes" message="Rate must be Numeric Only - no $ or ," validate="float" id="ship_rate">
		</td>
		<td><input name="shipmeth_Sort[]" type="text" value="<?php echo($row_rsCWGetShipping["shipmeth_Sort"]);?>" size="2" /></td>
		<td align="center"><input type="checkbox" value="<?php echo($row_rsCWGetShipping["shipmeth_ID"]);?>" class="formCheckbox" name="deleteMethod[]" <?php if ($row_CheckOrder["AreThereOrders"] != 0 || $row_CheckRange["AreThereRanges"] != 0) {echo(' disabled="disabled"');}?> /> </td> 
		<td align="center"><input type="checkbox" value="<?php echo($row_rsCWGetShipping["shipmeth_ID"]);?>" class="formCheckbox" name="archiveMethod[]" /> </td> 
	</tr>
	<?php $row_rsCWGetShipping = $cartweaver->db->db_fetch_assoc($rsCWGetShipping);
	if($row_rsCWGetShipping) {
		if($lastTFM_nest != $row_rsCWGetShipping["country_Name"]) {?>
	</table>
	<?php
		}
	}else{
		// Ending the repeat region
		$endLoop = true;
		echo("</table>");
	} 
} while (!$endLoop); ?>
	<input type="submit" value="Update Methods" name="UpdateMethods" class="formButton" /> 
	<input type="hidden" name="shipmeth_View" value="<?php echo(($methodView == 0) ? "1" : "0");?>"/>
</form>
 <?php }else{
 	echo("<p>There are no ");
	echo($methodView == "0" ? "active" : "archived" . " ship methods.</p>");
}?>
</div>
</body>
</html>
<?php
cwDebugger($cartweaver);
?>
