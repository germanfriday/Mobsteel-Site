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

Cartweaver Version: 2.2  -  Date: 09/07/2005
================================================================
Name: ShipStateProv.php
Description: Here we set the states and provinces as well as 
set a "Shipping extension" for each. A shipping extension is a 
multiplier by which the shipping cost is multiplied. The 
resulting amount is then add onto the shipping cost. This “loads” 
or "weights" the state with an additional cost thus allowing us to 
set shipping "zones" based on destination.
================================================================
*/

/* Set location for highlighting in Nav Menu */
$strSelectNav = "ShippingTax";

/* Initialize variables */
$stateView = "0";
$endLoop = false;

/* Update Record */
if(isset($_POST["UpdateTaxes"])){
	for($i = 0; $i<count($_POST["stprv_ID"]); $i++) {
		$query_rsCW = sprintf("UPDATE tbl_stateprov 
		SET stprv_Ship_Ext = %s, 
		stprv_Tax = %s
		WHERE stprv_ID = %s",
		$_POST["stprv_Ship_Ext"][$i],
		$_POST["stprv_Tax"][$i],
		$_POST["stprv_ID"][$i]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	}
	header("Location: " . $cartweaver->thisLocation);
	exit();
}

/* Get Records  */
$query_rsCWGetStProvs = "SELECT s.stprv_ID, 
s.stprv_Code, 
s.stprv_Name, 
s.stprv_Country_ID, 
s.stprv_Tax, 
s.stprv_Ship_Ext, 
s.stprv_Archive, 
c.country_Name
FROM tbl_list_countries c
INNER JOIN tbl_stateprov s
ON c.country_ID = s.stprv_Country_ID
WHERE stprv_Archive = $stateView
ORDER BY c.country_Sort, c.country_Name, s.stprv_Name";
$rsCWGetStProvs = $cartweaver->db->executeQuery($query_rsCWGetStProvs);
$rsCWGetStProvs_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetStProvs = $cartweaver->db->db_fetch_assoc($rsCWGetStProvs);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CW Admin: State/Province Tax &amp; Shipping</title>
<link href="assets/admin.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php include("CWIncNav.php");?>
<div id="divMainContent">
  <h1>Tax &amp; Shipping Extension </h1>
<form action="<?php echo($cartweaver->thisPage);?>" method="POST" name="Update"> 
  <?php  
/* Only show table if we have records */
if($rsCWGetStProvs_recordCount != 0){
	$lastTFM_nest = "";
	$recCounter = 0;
	do {
		$tfm_nest = $row_rsCWGetStProvs['country_Name'];
		if ($lastTFM_nest != $tfm_nest) {
			$lastTFM_nest = $tfm_nest; ?>
<h2><?php echo($row_rsCWGetStProvs["country_Name"]); ?></h2>
<table>    
    <tr>
	<?php if($row_rsCWGetStProvs["stprv_Code"] != "None") {?>
      <th align="center">Code</th>
      <th align="center">Name</th><?php } /* END  if($row_rsCWGetStProvs["stprv_Code"] != "None") */?>
      <th align="center">Tax%</th>
      <th align="center">Ship/Ext%</th>
    </tr>
	<?php } ?>
        <tr class="<?php cwAltRow($recCounter++);?>">
		<?php if($row_rsCWGetStProvs["stprv_Code"] != "None") {?>
          <td><?php echo($row_rsCWGetStProvs["stprv_Code"]);?></td>
          <td><?php echo($row_rsCWGetStProvs["stprv_Name"]);?></td><?php 		 
		  } /* END  if($row_rsCWGetStProvs["stprv_Code"] != "None") */?>
          <td align="center"><input name="stprv_Tax[]" type="text"  value="<?php echo($row_rsCWGetStProvs["stprv_Tax"])?>" size="6">
          </td>
          <td align="center">
		  <input name="stprv_ID[]" type="hidden" value="<?php echo($row_rsCWGetStProvs["stprv_ID"]);?>">
		  <input name="stprv_Ship_Ext[]" type="text" value="<?php echo($row_rsCWGetStProvs["stprv_Ship_Ext"]);?>" size="6">
          </td>
        </tr>
      <?php 
	$row_rsCWGetStProvs = $cartweaver->db->db_fetch_assoc($rsCWGetStProvs);
	if($row_rsCWGetStProvs) {
		if($lastTFM_nest != $row_rsCWGetStProvs["country_Name"]) {
?>
		</table>		
		<input type="submit" class="formButton" value="Update Tax & Shipping">
	<?php
		}
	}else{
		// Ending the repeat region
		$endLoop = true;
		echo("</table>");
		echo('<input type="submit" class="formButton" value="Update Tax & Shipping">');
	} 
} while (!$endLoop); ?>
<input type="hidden" name="UpdateTaxes" value="true">
</form>
<?php 
}else{
	echo("There are no shipping locations");
}?>
 
</div>
</body>
</html>
<?php
cwDebugger($cartweaver);
?>
