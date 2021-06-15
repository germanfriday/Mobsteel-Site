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
Name: ListShipStatus.php
Description: allow for setting the order in which shipping staus records appier.
================================================================
*/

/* Set location for highlighting in Nav Menu */
$strSelectNav = "Settings";

/* Use parameter to check and see if nav should be updated */
$updateStatus = "0";

/* Update Record */
if(isset($_POST["UpdateRecords"])){
	for($i = 0; $i<count($_POST["shipstatus_ID"]); $i++) {
		$query_rsCW = sprintf("UPDATE tbl_list_shipstatus 
			SET shipstatus_Sort = %s,
			shipstatus_Name = '%s' 
			WHERE shipstatus_ID = %d",
			$_POST["shipstatus_Sort"][$i],
			$_POST["shipstatus_Name"][$i],
			$_POST["shipstatus_ID"][$i]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
		$updateStatus = "1";
	}
}

/* Get Record */
$query_rsCWShipStatus = "SELECT shipstatus_ID, shipstatus_Name, shipstatus_Sort
FROM tbl_list_shipstatus 
ORDER BY shipstatus_Sort ASC";
$rsCWShipStatus = $cartweaver->db->executeQuery($query_rsCWShipStatus);
$rsCWShipStatus_recordCount = $cartweaver->db->recordCount;
$row_rsCWShipStatus = $cartweaver->db->db_fetch_assoc($rsCWShipStatus);

if($updateStatus == 1){
	$_SESSION["ShipStatusMenu"] = "";
	do{
		$_SESSION["ShipStatusMenu"] .= '<a href="Orders.php?searchBy=' . $row_rsCWShipStatus["shipstatus_ID"] . '">&#8211;' . $row_rsCWShipStatus["shipstatus_Name"] . "</a>";
	} while ($row_rsCWShipStatus = $cartweaver->db->db_fetch_assoc($rsCWShipStatus));
	header("Location: " . $cartweaver->thisLocation);
	exit();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Order Status Codes Administration</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="assets/admin.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php include("CWIncNav.php");?>

<div id="divMainContent">
  <h1> Order Status Codes</h1>
 <?php 
/* Only show table if we have records */
if($rsCWShipStatus_recordCount != 0){ ?>
<form action="<?php echo($cartweaver->thisPage);?>" method="POST" name="Update">
  <table>
    <caption>
    Order Status Codes
    </caption>
    <tr>
      <th align="center">Status Name</th>
      <th align="center">Sort</th>
    </tr>
<?php $recCounter = 0;
    $currentRow = 0;
	do {
?>  
	<tr class="<?php cwAltRow($recCounter++);?>">
	  <td>
	  <input name="shipstatus_ID[]" type="hidden"  value="<?php echo($row_rsCWShipStatus["shipstatus_ID"]);?>">
	  <input name="shipstatus_Name[]" type="hidden"  value="<?php echo($row_rsCWShipStatus["shipstatus_Name"]);?>">
	  <?php echo($row_rsCWShipStatus["shipstatus_Name"]);?></td>
	  <td align="center">
	  <input name="shipstatus_Sort[]" type="text" value="<?php echo($row_rsCWShipStatus["shipstatus_Sort"]);?>" size="3">
	  </td>
	</tr>      
    <?php } while ($row_rsCWShipStatus = $cartweaver->db->db_fetch_assoc($rsCWShipStatus)); ?>
  </table>
  <input name="UpdateRecords" type="submit" class="formButton" id="UpdateRecords" value="Update Ship Status"> 
  </form>
<?php }else{
  echo("<p>There are no  Order Status Codes.</p>");
}?>
</div>
</body>
</html>
<?php
cwDebugger($cartweaver);
?>