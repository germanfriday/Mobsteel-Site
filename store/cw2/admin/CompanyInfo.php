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
if(isset($_POST["Update"])) {
	$query_rsCW = sprintf("UPDATE tbl_companyinfo 
		SET comp_Name='%s', 
		  comp_Address1='%s', 
			comp_Address2='%s', 
			comp_City='%s', 
			comp_State='%s', 
			comp_Zip='%s', 
			comp_Country='%s', 
			comp_Phone='%s', 
			comp_Fax='%s', 
			comp_Email='%s'
	  WHERE comp_ID=%s"
	  ,$_POST["comp_Name"]
	  ,$_POST["comp_Address1"]
	  ,$_POST["comp_Address2"]
	  ,$_POST["comp_City"]
	  ,$_POST["comp_State"]
	  ,$_POST["comp_Zip"]
	  ,$_POST["comp_Country"]
	  ,$_POST["comp_Phone"]
	  ,$_POST["comp_Fax"]
	  ,$_POST["comp_Email"]
	  ,$_POST["comp_ID"]
	  );
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);

	header("Location: " . $cartweaver->thisLocation . "?resetApplication=" . $cartweaver->settings->debugPassword);
	exit();
}

/* Get Company Information */
$query_rsCWGetCompData = "SELECT comp_ID, comp_Name, comp_Address1, 
comp_Address2, comp_City, comp_State, comp_Zip, comp_Country, comp_Phone, 
comp_Fax, comp_Email, comp_ChargeBase, comp_ChargeWeight, comp_ChargeExtension, 
comp_enableshipping, comp_ShowUpSell, comp_AllowBackOrders
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
  <form name="compinfo" action="<?php echo($cartweaver->thisPage);?>" method="POST">
    <h1>Company Information</h1>
    <table cellpadding="3" cellspacing="0">
      <tr>
        <th align="right"><label for="comp_Name">Company Name: </label></th>
        <td><input name="comp_Name" type="text" value="<?php echo($row_rsCWGetCompData["comp_Name"]);?>">
          <input name="comp_ID" type="hidden" value="<?php echo($row_rsCWGetCompData["comp_ID"]);?>">
        </td>
      </tr>
      <tr>
        <th align="right"><label for="comp_Address1">Address 1: </label></th>
        <td><input name="comp_Address1" type="text" value="<?php echo($row_rsCWGetCompData["comp_Address1"]);?>"></td>
      </tr>
      <tr>
        <th align="right"><label for="comp_Address2">Address 2: </label></th>
        <td><input name="comp_Address2" type="text" value="<?php echo($row_rsCWGetCompData["comp_Address2"]);?>"></td>
      </tr>
      <tr>
        <th align="right"><label for="comp_City">City: </label></th>
        <td><input name="comp_City" type="text" value="<?php echo($row_rsCWGetCompData["comp_City"]);?>"></td>
      </tr>
      <tr>
        <th align="right"><label for="comp_State">State / Prov: </label></th>
        <td><input name="comp_State" type="text" value="<?php echo($row_rsCWGetCompData["comp_State"]);?>"></td>
      </tr>
      <tr>
        <th align="right"><label for="comp_Zip">Postal Code: </label></th>
        <td><input name="comp_Zip" type="text" value="<?php echo($row_rsCWGetCompData["comp_Zip"]);?>"></td>
      </tr>
      <tr>
        <th align="right"><label for="comp_Country">Country: </label></th>
        <td><input name="comp_Country" type="text" value="<?php echo($row_rsCWGetCompData["comp_Country"]);?>"></td>
      </tr>
      <tr>
        <th align="right"><label for="comp_Phone">Phone: </label></th>
        <td><input name="comp_Phone" type="text" value="<?php echo($row_rsCWGetCompData["comp_Phone"]);?>"></td>
      </tr>
      <tr>
        <th align="right"><label for="comp_Fax">Fax: </label></th>
        <td><input name="comp_Fax" type="text" value="<?php echo($row_rsCWGetCompData["comp_Fax"]);?>"></td>
      </tr>
      <tr>
        <th align="right"><label for="comp_Email">Email: </label></th>
        <td><input name="comp_Email" type="text" value="<?php echo($row_rsCWGetCompData["comp_Email"]);?>"></td>
      </tr>
    </table>
    <input name="Update" type="submit" class="formButton" id="Update" value="Update">
  </form>
</div>
</body>
</html>
<?php
cwDebugger($cartweaver);
?>
