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
Name: Orders.php
Description: Dispalys a list of orders filtered by the selected "Order Status"
================================================================
*/

/* Set location for highlighting in Nav Menu */
$strSelectNav = "Orders";

/* Set default values for order status and search dates */
if(!isset($_GET["searchBy"])) {$_GET["searchBy"] = 0;}
if(!isset($_POST["StartDate"])) {
	$_POST["StartDate"] = cwDateFormat(date("Y/m/d",mktime(0,0,0,date("m"),date("d") - 7, date("y"))),true);
}else{
	$_POST["StartDate"] = cwDateFormat($_POST["StartDate"], true);
}

//default is last week
if(!isset($_POST["EndDate"])) {
	$_POST["EndDate"] = cwDateFormat(date('Y/m/d'),true);
} else{
	$_POST["EndDate"] = cwDateFormat($_POST["EndDate"], true);
}

if(!isset($_POST["Status"])) {$_POST["Status"] = '0';}

if($_GET["searchBy"] != 0) {
	$_POST["Status"] = $_GET["searchBy"];
}

/* Get a list of ship types */
$query_rsCWShipStatusTypes = "SELECT shipstatus_id, shipstatus_Name, shipstatus_Sort
FROM tbl_list_shipstatus
ORDER BY shipstatus_Sort ASC";
$rsCWShipStatusTypes = $cartweaver->db->executeQuery($query_rsCWShipStatusTypes);
$rsCWShipStatusTypes_recordCount = $cartweaver->db->recordCount;
$row_rsCWShipStatusTypes = $cartweaver->db->db_fetch_assoc($rsCWShipStatusTypes);

/* Get Ship status list */

$query_rsCWCurrentShipStatusType = sprintf("SELECT shipstatus_id, shipstatus_Name, shipstatus_Sort 
FROM tbl_list_shipstatus %s
ORDER BY shipstatus_Sort ASC",($_POST["Status"] != 0) ? " WHERE shipstatus_ID = " .$_POST["Status"] : '');
$rsCWCurrentShipStatusType = $cartweaver->db->executeQuery($query_rsCWCurrentShipStatusType);
$rsCWCurrentShipStatusType_recordCount = $cartweaver->db->recordCount;
$row_rsCWCurrentShipStatusType = $cartweaver->db->db_fetch_assoc($rsCWCurrentShipStatusType);

/* retrieve orders by date */
$query_rsCWByDate = sprintf("SELECT order_ID, order_TransactionID, order_Date, 
order_Status, order_CustomerID, order_Tax, order_Shipping, order_Total, 
order_ShipMeth_ID, order_ShipDate, order_ShipTrackingID, order_Address1, 
order_Address2, order_City, order_State, order_Zip, order_Country, 
order_Notes, order_ActualShipCharge, 
order_ShipName FROM tbl_orders
WHERE order_Date >= '%s'
AND order_Date <= '%s'
%s
ORDER BY order_Date DESC"
,mySQLDate($_POST["StartDate"])
,mySQLDate($_POST["EndDate"] . " 23:59:59")
,($_POST["Status"] != 0) ? " AND order_Status = " .$_POST["Status"] : '');
$rsCWByDate = $cartweaver->db->executeQuery($query_rsCWByDate);
$rsCWByDate_recordCount = $cartweaver->db->recordCount;
$row_rsCWByDate = $cartweaver->db->db_fetch_assoc($rsCWByDate);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CW Admin: Orders</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="assets/admin.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
/* Date Pop Up */
// function to load the calendar window.
function ShowCalendar(FormName, FieldName) {
	var curValue = eval("document."+FormName+"."+FieldName+".value");
	window.open("DatePopup.php?getDate="+ curValue + "&FormName=" + FormName + "&FieldName=" + FieldName, "CalendarWindow", "width=250,height=200");
}
//Function to clear default text. Function name must stay all lowercase!
function cw_cleardefault(theField){
 if(theField.value == theField.defaultValue){theField.value = '';}
}
//-->
</script>
</head>
<body>

<?php include("CWIncNav.php");?>
<div id="divMainContent">
  
<form name="DateForm" method="post" action="<?php echo($cartweaver->thisPage);?>">
      From
        <input name="StartDate" type="text" required="yes" message="Must be a date - mm/dd/yyyy format" validate="date" value="<?php echo($_POST["StartDate"]);?>" size="10" passthrough="onFocus=""cw_cleardefault(this);""">
      <a href="javascript:ShowCalendar('DateForm', 'StartDate')"><img src="assets/images/calendar.gif" alt="Click to Select Date" width="16" height="16"></a>&nbsp;To
        <input  name="EndDate" type="text" required="yes" validate="date" message="Must be a date - mm/dd/yyyy format" value="<?php echo($_POST["EndDate"]);?>" size="10" passthrough="onFocus=""cw_cleardefault(this);""">
      <a href="javascript:ShowCalendar('DateForm', 'EndDate')"><img src="assets/images/calendar.gif" width="16" height="16" style="margin-bottom:0px;" alt="Click to Select Date"></a>
      
      <select name="Status">
        <option value="0" <?php if($_GET["searchBy"] == "Any"){echo("selected");}?>>Any</option>
	   <?php do { // CW Repeat region
	   ?>
          <option value="<?php echo($row_rsCWShipStatusTypes["shipstatus_id"]);?>" <?php if($row_rsCWShipStatusTypes["shipstatus_id"] == $_POST["Status"]) {echo("selected");}?>><?php echo($row_rsCWShipStatusTypes["shipstatus_Name"]);?></option>
        <?php } while ($row_rsCWShipStatusTypes = $cartweaver->db->db_fetch_assoc($rsCWShipStatusTypes)); ?>
      </select>
      <input name="Submit" type="submit" class="formButton" value="Get Orders">
</form>
  <br>
  
  <h1>Orders By Status:
    <?php /* If there is a valid status filter */
	if($rsCWCurrentShipStatusType_recordCount != 0) {
      echo($cartweaver->db->valueList($rsCWCurrentShipStatusType, "shipstatus_Name"));
     }else{
      echo("Any");
    }?>
  </h1>
  <?php /* Results Output table */
  if($rsCWByDate_recordCount !=0) { ?>
    <table>
      <tr>
        <th>Date</th>
        <th>Order ID</th>
        <th>Customer</th>
        <th>Zip</th>
        <?php /* Show only if we don't know the status type */
        if($rsCWCurrentShipStatusType_recordCount == 0) {
          echo("<th>Status</th>");
		}?>
        <th>View</th>
      </tr>
      <?php /* loop through to show all records found */
      if($rsCWByDate_recordCount == 0) {
        echo("<p>No results found.</p>");
      }
    $recCounter = 0;
	do {
		$query_getCustomer = sprintf("SELECT cst_FirstName,cst_LastName,cst_Zip 
		FROM tbl_customers 
		WHERE cst_ID = '%s'",$row_rsCWByDate["order_CustomerID"]);
		$getCustomer = $cartweaver->db->executeQuery($query_getCustomer);
		$getCustomer_recordCount = $cartweaver->db->recordCount;
		$row_getCustomer = $cartweaver->db->db_fetch_assoc($getCustomer);

		$query_getStatusName = "SELECT shipstatus_Name 
		FROM tbl_list_shipstatus 
		WHERE shipstatus_id = " . $row_rsCWByDate["order_Status"];
		$getStatusName = $cartweaver->db->executeQuery($query_getStatusName);
		$getStatusName_recordCount = $cartweaver->db->recordCount;
		$row_getStatusName = $cartweaver->db->db_fetch_assoc($getStatusName);
		?>
        <tr class="<?php cwAltRow($recCounter++);?>">
          <td style="white-space: nowrap; text-align: right;"><?php echo(cwDateFormat($row_rsCWByDate["order_Date"],false));?></td>
          <td><?php echo($row_rsCWByDate["order_ID"]);?></td>
          <td><?php echo($row_getCustomer["cst_FirstName"] . " " . $row_getCustomer["cst_LastName"]);?></td>
          <td><?php echo($row_getCustomer["cst_Zip"]);?></td>
          <?php /* Show only if we don't know the status type */
        if($rsCWCurrentShipStatusType_recordCount == 0) {
          echo("<td>" . $row_getStatusName["shipstatus_Name"] . "</td>");
		}?>
          <td align="center"><a href="OrderDetails.php?order_ID=<?php echo($row_rsCWByDate["order_ID"]);?>"><img src="assets/images/viewdetails.gif" width="15" height="15" alt="View Order Details"></a> 
        </tr>
      <?php } while ($row_rsCWByDate = $cartweaver->db->db_fetch_assoc($rsCWByDate)); ?>
    </table>
    <?php 
} else { 
	echo("<p><strong>Sorry, no matching records found.");
	/* If the user has tried a form search, and there are no results, prompt them to choose different dates */
	if (isset ($_POST["StartDate"])) {
		echo("Try different dates.");
	}
	echo("</strong></p>");
}?>
</div>
</body>
</html>
<?php
cwDebugger($cartweaver);
?>