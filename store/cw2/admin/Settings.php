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
Name: Company Information Admin
Description: This page allows you to set the default company 
Information to be used throughout the site.
================================================================
*/
/* Set location for highlighting in Nav Menu */
$strSelectNav = "Settings";

/* Update Company information if form has been submitted */
if(isset($_POST["Update"])){
	$query_updateCompData = sprintf("UPDATE tbl_companyinfo 
	SET comp_ShowUpSell = '%s', 
		comp_AllowBackOrders = '%s' 
	WHERE comp_ID = %s",$_POST["comp_ShowUpSell"],$_POST["comp_AllowBackOrders"],$_POST["comp_ID"]);
	$updateCompData = $cartweaver->db->executeQuery($query_updateCompData);

	header("Location: " . $cartweaver->thisPageName . "?resetApplication=" . $cartweaver->settings->debugPassword);
	exit();
}

/* Get Company Information */
$query_rsCWGetCompData = "SELECT comp_ID, comp_ShowUpSell, comp_AllowBackOrders 
FROM tbl_companyinfo";
$rsCWGetCompData = $cartweaver->db->executeQuery($query_rsCWGetCompData);
$rsCWGetCompData_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetCompData = $cartweaver->db->db_fetch_assoc($rsCWGetCompData);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CW Admin: Company Information</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="assets/admin.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php include("CWIncNav.php");?>
<div id="divMainContent">
  <form name="compinfo" action="<?php echo($cartweaver->thisPageName);?>" method="POST">
    <h1>Settings</h1>
    <table cellpadding="3" cellspacing="0">
      <tr>
        <th align="right">Allow Backorders: </th>
        <td><select name="comp_AllowBackOrders">
		    <option value="1" <?php if($row_rsCWGetCompData["comp_AllowBackOrders"] == 1) {echo("selected");}?>>Yes</option>
            <option value="0" <?php if($row_rsCWGetCompData["comp_AllowBackOrders"] == 0) {echo("selected");}?>>No</option>
          </select></td>
      </tr>
      <tr>
        <th align="right">Show Up Sell</th>
        <td><select name="comp_ShowUpSell">
            <option value="1" <?php if($row_rsCWGetCompData["comp_ShowUpSell"] == 1) {echo("selected");}?>>Yes</option>
            <option value="0" <?php if($row_rsCWGetCompData["comp_ShowUpSell"] == 0) {echo("selected");}?>>No</option>
          </select>
        </td>
      </tr>
    </table>
    <input name="comp_ID" type="hidden" value="<?php echo($row_rsCWGetCompData["comp_ID"]);?>">
    <input name="Update" type="submit" class="formButton" id="Update" value="Update" />
  </form>
</div>
</body>
</html>
<?php
cwDebugger($cartweaver);
?>