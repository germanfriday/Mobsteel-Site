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
Name: ShipWeightRange.php
Description: Here we set the From - To weight ranges and shipping 
cost for each range. This data is used in calculating the shipping 
cost.
================================================================
*/
/* Set location for highlighting in Nav Menu */
$strSelectNav = "ShippingTax";
$endLoop = false;

/* ADD Record */
if(isset($_POST["AddRecord"])) {
	$_POST["ship_weightrange_Method_ID"] = floatval($_POST["ship_weightrange_Method_ID"]);
	$_POST["ship_weightrange_From"] = floatval($_POST["ship_weightrange_From"]);
	$_POST["ship_weightrange_To"] = floatval($_POST["ship_weightrange_To"]);
	$_POST["ship_weightrange_Amount"] = floatval($_POST["ship_weightrange_Amount"]);
	$query_rsCW = sprintf("INSERT INTO tbl_shipweights 
	(ship_weightrange_Method_ID, ship_weightrange_From,
	  ship_weightrange_To, ship_weightrange_Amount) VALUES 
	  ( %s, %s, %s, %s)"
	  ,$_POST["ship_weightrange_Method_ID"]
	  ,$_POST["ship_weightrange_From"]
	  ,$_POST["ship_weightrange_To"]
	  ,$_POST["ship_weightrange_Amount"]);
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);
}



/* Update Record */
if(isset($_POST["UpdateRanges"])){
	/* DELETE Record */
	if (isset($_POST["deleteRange"])){
		$deleteRanges = join($_POST["deleteRange"],",");
		/* DELETE weight range */
		$query_rsCW = "DELETE FROM tbl_shipweights 
		WHERE ship_weightrange_ID  IN ($deleteRanges) ";
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
		header("Location: " . $cartweaver->thisLocation);
		exit();
	}
	for($i = 0; $i<count($_POST["weightrange_ID"]); $i++) {
		$query_rsCW = "UPDATE tbl_shipweights 
		SET ship_weightrange_From = ";
		$query_rsCW .= (isset($_POST["ship_weightrange_From"][$i]) && $_POST["ship_weightrange_From"][$i] != "") ? $_POST["ship_weightrange_From"][$i] : "NULL";
		$query_rsCW .= " , ship_weightrange_To = ";
		$query_rsCW .= (isset($_POST["ship_weightrange_To"][$i]) && $_POST["ship_weightrange_To"][$i] != "") ? $_POST["ship_weightrange_To"][$i] : "NULL";
		$query_rsCW .=  " , ship_weightrange_Amount = ";
		$query_rsCW .=  (isset($_POST["ship_weightrange_Amount"][$i]) && $_POST["ship_weightrange_Amount"][$i] != "") ? $_POST["ship_weightrange_Amount"][$i] : "NULL";
		$query_rsCW .=   " WHERE ship_weightrange_ID = " .$_POST["weightrange_ID"][$i];
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	}
}

/* Get Record */
$query_rsCWGetWtRange = "SELECT w.ship_weightrange_ID, 
w.ship_weightrange_Method_ID, 
w.ship_weightrange_From, 
w.ship_weightrange_To,
w.ship_weightrange_Amount,
m.shipmeth_Name, 
c.country_Name
FROM tbl_shipweights w
INNER JOIN tbl_shipmethod m
ON w.ship_weightrange_Method_ID = m.shipmeth_ID
INNER JOIN tbl_shipmethcntry_rel r
ON r.shpmet_cntry_Meth_ID = m.shipmeth_ID
INNER JOIN tbl_list_countries c
ON r.shpmet_cntry_Country_ID = c.country_ID
ORDER BY c.country_Sort, 
c.country_Name, 
m.shipmeth_Sort, 
m.shipmeth_Name, 
w.ship_weightrange_From, 
w.ship_weightrange_To";
$rsCWGetWtRange = $cartweaver->db->executeQuery($query_rsCWGetWtRange);
$rsCWGetWtRange_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetWtRange = $cartweaver->db->db_fetch_assoc($rsCWGetWtRange);

/* Get Shipping Method List */
$query_rsCWGetMethod = "SELECT m.shipmeth_name, 
m.shipmeth_id, 
c.country_Name, 
c.country_ID
FROM (tbl_shipmethod m
INNER JOIN tbl_shipmethcntry_rel r
ON m.shipmeth_ID = r.shpmet_cntry_Meth_ID) 
INNER JOIN tbl_list_countries c
ON r.shpmet_cntry_Country_ID = c.country_ID
WHERE shipmeth_archive = 0 
ORDER BY c.country_Sort,
c.country_Name, 
m.shipmeth_Sort, 
m.shipmeth_Name";
$rsCWGetMethod = $cartweaver->db->executeQuery($query_rsCWGetMethod);
$rsCWGetMethod_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetMethod = $cartweaver->db->db_fetch_assoc($rsCWGetMethod);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CW Admin: Shipping Weight, Ranges &amp; Rates</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="assets/admin.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php include("CWIncNav.php");?>
<div id="divMainContent">
  <h1>Shipping Weight, Ranges &amp; Rates</h1>
  <h2>* Note: the first weight range of any method must start at 0 with a Rate of 0.00 </h2>
  <form name="Add" method="POST" action="<?php echo($cartweaver->thisPageName);?>">
    <table>
      <caption>
      Add Range
      </caption>
      <tr align="center">
        <th>Method</th>
        <th>From</th>
        <th>To</th>
        <th>Rate</th>
        <th>&nbsp;</th>
      </tr>
      <tr align="center" class="altRowEven">
        <td>		
			<select name="ship_weightrange_Method_ID">
			<?php 
			$lastTFM_nest = "";
			do { // Cartweaver repeat region
			$tfm_nest = $row_rsCWGetMethod["country_Name"];
			if ($lastTFM_nest != $tfm_nest) {
			if($lastTFM_nest != "") echo("</optgroup>");
			$lastTFM_nest = $tfm_nest;
			echo("<optgroup label=\"$tfm_nest\">");
			}?>
			<option value="<?php echo($row_rsCWGetMethod["shipmeth_id"]);?>"><?php echo($row_rsCWGetMethod["shipmeth_name"]);?></option>
			<?php } while ($row_rsCWGetMethod = $cartweaver->db->db_fetch_assoc($rsCWGetMethod)); 
			echo("</optgroup>")?>
				
			</select>
        </td>
        <td><input name="ship_weightrange_From" required="yes" validate="float" message="From Weight Required - Must be Numeric Value" type="text" size="10"/>
        </td>
        <td><input name="ship_weightrange_To" required="yes" validate="float" message="To Weight Required - Must be Numeric Value" type="text" size="10"/>
        </td>
        <td><input name="ship_weightrange_Amount" required="yes" validate="float" message="Rate Required - Must be Numeric Value" type="text" size="10"/>
        </td>
        <td><input name="AddRecord" type="hidden" value="True" />
		<input type="submit" class="formButton" id="AddRecord" value="Add"/>
        </td>
      </tr>
    </table>
  </form>
  
<form action="<?php echo($cartweaver->thisPageName);?>" method="POST" name="Update"> 
<?php
$lastTFM_nest = "";
$recCounter = 0;
if($rsCWGetWtRange_recordCount > 0) {
do { // Special repeat region to include nesting of country names
	$tfm_nest = $row_rsCWGetWtRange["country_Name"];
	if ($lastTFM_nest != $tfm_nest) {
		$lastTFM_nest = $tfm_nest;
?>
	<h2><?php echo($row_rsCWGetWtRange["country_Name"]);?></h2>  
	<table>
		<caption>
		Current Ranges
		</caption>
		<tr>
			<th>Method</th>
			<th>From</th>
			<th>To</th>
			<th>Rate</th>
			<th>Delete</th>
		</tr>
	<?php } // End nested region, continue loop ?>
      <tr class="<?php cwAltRow($recCounter++);?>">
          <td><?php echo($recCounter);?>: <input name="weightrange_ID[]" type="hidden" value="<?php echo($row_rsCWGetWtRange["ship_weightrange_ID"]);?>">
            <?php echo($row_rsCWGetWtRange["shipmeth_Name"]);?>
            <input name="ship_weightrange_Method_ID[]" type="hidden" id="ship_weightrange_Method_ID" value="<?php echo($row_rsCWGetWtRange["ship_weightrange_Method_ID"]);?>">
          </td>
          <td align="center"><input name="ship_weightrange_From[]" type="text" required="yes" validate="float" message="From Range Required - Must be Numeric Value" value="<?php echo($row_rsCWGetWtRange["ship_weightrange_From"]);?>" size="10">
          </td>
          <td align="center"><input name="ship_weightrange_To[]" type="text" required="yes" validate="float" message="To Range Required - Must be Numeric Value" value="<?php echo($row_rsCWGetWtRange["ship_weightrange_To"]);?>" size="10">
          </td>
          <td align="center"><input name="ship_weightrange_Amount[]" type="text" required="yes" validate="float" message="Rate Required - Must be Numeric Value" value="<?php echo($row_rsCWGetWtRange["ship_weightrange_Amount"]);?>" size="10">
          </td>
          <td align="center"><input type="checkbox" class="formCheckbox" name="deleteRange[]" value="<?php echo($row_rsCWGetWtRange["ship_weightrange_ID"]);?>"></td>
      </tr>
<?php 
	$row_rsCWGetWtRange = $cartweaver->db->db_fetch_assoc($rsCWGetWtRange);
	if($row_rsCWGetWtRange) {
		if($lastTFM_nest != $row_rsCWGetWtRange["country_Name"]) {
?>
		</table>		
		<input type="submit" value="Update Ranges" class="formButton"> 
	<?php
		}
	}else{
		// Ending the repeat region
		$endLoop = true;
		echo("</table>");
		echo('<input type="submit" value="Update Ranges" class="formButton"> ');
	} 
  } while (!$endLoop); 
}/* END if($rsCWGetWtRange_recordCount > 0) */?>
<input type="hidden" name="UpdateRanges" value="true">
</form>
</div>
</body>
</html>
<?php
cwDebugger($cartweaver);
?>