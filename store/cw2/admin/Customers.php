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
Name: Customers.php
Description: Searches for and lists customer records
================================================================
*/
/* Set location for highlighting in Nav Menu */
$strSelectNav = "Customers";
if(!isset($_GET["Alpha"])) {$_GET["Alpha"] = "xxx";}
if(!isset($_GET["Order"])) {$_GET["Order"] = "";}
if(!isset($_GET["Zip"])) {$_GET["Zip"] = "";}
if(!isset($_GET["Email"])) {$_GET["Email"] = "";}

/* If the Search button was clicked, the form has been submitted */
$query_rsCWGetCustomer = "SELECT c.cst_ID, c.cst_FirstName, c.cst_LastName, 
c.cst_City, c.cst_Zip, c.cst_Phone, 
c.cst_Email, s.stprv_Name, cs.CustSt_Destination
	FROM tbl_stateprov s
		INNER JOIN tbl_custstate cs
			ON s.stprv_ID = cs.CustSt_StPrv_ID
		INNER JOIN tbl_customers c				
			ON c.cst_ID = cs.CustSt_Cust_ID
	WHERE cs.CustSt_Destination = 'BillTo'
	AND cst_LastName LIKE '". $_GET["Alpha"] . "%'";
if($_GET["Zip"] != "") {
	$query_rsCWGetCustomer .=  " AND cst_Zip LIKE '" . $_GET["Zip"] . "%'";
}
/* Add to the WHERE clasue if we have an Order number */
if($_GET["Order"] != "" && is_int($_GET["Order"])) {
	$query_rsCWGetCustomer .=  " AND cst_ID IN (SELECT order_CustomerID FROM tbl_orders WHERE order_ID = " . $_GET["Order"];
}
if($_GET["Email"] != "") {
	$query_rsCWGetCustomer .= " AND cst_Email LIKE '%" . $_GET["Email"] . "%'";
}
$query_rsCWGetCustomer .=  " ORDER BY c.cst_LastName, c.cst_FirstName";
$rsCWGetCustomer = $cartweaver->db->executeQuery($query_rsCWGetCustomer);
$rsCWGetCustomer_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetCustomer = $cartweaver->db->db_fetch_assoc($rsCWGetCustomer);
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
<h1>Customer Search</h1>
  <form name="CustSearch" action="<?php echo($cartweaver->thisPage);?>" method="get">
    <table class="noBorders">
	<tr>
	<td><label for="Alpha">Last Name:</label></td>
	<td><?php
      /* Create the alphabet array so that we can show the item selected after the form is submitted. */
      $theAlphabet="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	  ?>
	  <select name="Alpha" id="Alpha">
        <option value="%"<?php if($_GET["Alpha"] == "%") { echo(' selected="selected"');}?>>ALL</option>
        <?php for($i=0; $i < strlen($theAlphabet); $i++) {
			echo('<option value="' . $theAlphabet[$i] . '"');
			if($_GET["Alpha"] == $theAlphabet[$i]) { 
				echo(' selected="selected"');
			}
			echo(">" . $theAlphabet[$i] . "</option>\n");
        }?>
      </select></td>
	  <td><label for="Zip">Zip Code:</label></td>
	  <td><input name="Zip" type="text" id="Zip" size="12" value="<?php echo($_GET["Zip"]);?>"></td>
	  <td><label for="Order">Order#:</label></td>
	  <td><input name="Order" type="text" id="Order" size="12"  value="<?php echo($_GET["Order"]);?>"><br/></td>
	</tr>
	<tr>
	<td> <label for="Email">Email Address:</label></td>
	<td colspan="4"><input type="text" name="Email" id="Email" size="30" value="<?php echo($_GET["Email"]);?>"></td>
	<td><input name="theSearch" type="submit" class="formButton" value="Search"></td>
	</tr>
	</table>
  </form>
  <h1>Customer List</h1>
<?php if($rsCWGetCustomer_recordCount != 0) { ?>
  <table>
    <tr>
      <th>Name</th>
      <th>Address</th>
      <th>E-mail</th>
      <th>Phone</th>
    </tr>
    <?php $recCounter = 0;
		do {
		?>
    <tr class="<?php cwAltRow($recCounter++);?>">
      <td><a href="CustomerDetails.php?cst_ID=<?php echo($row_rsCWGetCustomer["cst_ID"]);?>"><?php echo($row_rsCWGetCustomer["cst_LastName"]);?>, <?php echo($row_rsCWGetCustomer["cst_FirstName"]);?></a></td>
      <td><?php echo($row_rsCWGetCustomer["cst_City"]);?>,<br>
        <?php echo($row_rsCWGetCustomer["stprv_Name"]);?> <?php echo($row_rsCWGetCustomer["cst_Zip"]);?></td>
      <td><a href="Mailto:<?php echo($row_rsCWGetCustomer["cst_Email"]);?>"><?php echo($row_rsCWGetCustomer["cst_Email"]);?></a></td>
      <td nowrap><?php echo($row_rsCWGetCustomer["cst_Phone"]);?></td>
    </tr>
    <?php } while ($row_rsCWGetCustomer = $cartweaver->db->db_fetch_assoc($rsCWGetCustomer)); ?>
  </table>
<?php 
} else { 
  echo("<p><strong>No Matching Records Found.</strong></p>");
} // end if($rsCWGetCustomer_recordCount != 0) ?>
</div>
</body>
</html>
<?php
cwDebugger($cartweaver);
?>
