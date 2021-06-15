<?php
/* 
================================================================
Application Info: 
Cartweaver© 2002 - 2005, All Rights Reserved.
Developer Info: 
	Application Dynamics Inc.
	1560 NE 10th
	East Wenatchee, WA 98802
	Support Info: http://www.cartweaver.com/go/phphelp

Cartweaver Version: 2.1  -  Date: 08/07/2005
================================================================
Name: CWIncOrderForm.php
Description: This page allows the user to register for the site
	and enter shipping and billing information. This is the first
	step before credit card details are entered.
================================================================
*/
?>
<script language="JavaScript" src="cw2/assets/scripts/dropdowns.js"></script>
<script language="JavaScript">
	var arrDynaList = new Array();
	var arrDL1 = new Array();
	var arrDL2 = new Array();

	arrDL1[1] = "cstCountry";
	arrDL1[2] = "PlaceOrder";
	arrDL1[3] = "cstStateProv";
	arrDL1[4] = "PlaceOrder";
	arrDL1[5] = arrDynaList;
	arrDL2[1] = "cstShpCountry";
	arrDL2[2] = "PlaceOrder";
	arrDL2[3] = "cstShpStateProv";
	arrDL2[4] = "PlaceOrder";
	arrDL2[5] = arrDynaList;
	//Explanation:
	//Element 1: Parent relationship
	//Element 2: Child Label
	//Element 3: Child Value
	
	
<?php 
	$intListCounter = 0;
	do {
		echo("arrDynaList[" . $intListCounter++ ."] = \"" . $row_rsCWGetStates["country_ID"] . "\";\r\n");
		echo("arrDynaList[" . $intListCounter++ ."] = \"" . $row_rsCWGetStates["stprv_Name"] . "\";\r\n");
		echo("arrDynaList[" . $intListCounter++ ."] = \"" . $row_rsCWGetStates["stprv_ID"] . "\";\r\n");
	} while ($row_rsCWGetStates = $cartweaver->db->db_fetch_assoc($rsCWGetStates)); 
     $cartweaver->db->db_data_seek($rsCWGetStates, 0);
?>
function doShipFields() {
 	var shippingFields = new Array("cstShpName","cstShpAddress1","cstShpAddress2","cstShpCity","cstShpStateProv","cstShpZip");
 	for(var i=0; i < shippingFields.length; i++) {
		document.getElementById(shippingFields[i]).disabled = document.PlaceOrder.shipSame.checked == true; 
	}
 }
</script>
<?php include("CWIncLoginForm.php");?>
<?php if (isset($_SESSION["customerID"]) && $_SESSION["customerID"] != "0") { ?>
	<h1>Welcome back <?php echo($row_rsCWGetCustomerData["cst_FirstName"]); ?> <?php echo($row_rsCWGetCustomerData["cst_LastName"]); ?>! 
	<span class="smallprint">[<a href="<?php echo($cartweaver->thisPage); ?>?logout=savecart">Not <?php echo($row_rsCWGetCustomerData["cst_FirstName"]); ?>?</a>]</span></h1>
<?php }
if($cartweaver->getCWError()){ 
    foreach($_POST as $key=>$value)
        $_POST[$key] = stripslashes($value);?>
	<p><strong>* Please be sure to fill in all Required fields.</strong></p>
<?php
} 

//Set all of the values for the order form based off of the recordset or the latest form post.
$cstFirstName = (isset($_POST["cstFirstName"])) ? $_POST["cstFirstName"] : ((isset($row_rsCWGetCustomerData["cst_FirstName"])) ? $row_rsCWGetCustomerData["cst_FirstName"] : "");
$cstLastName = (isset($_POST["cstLastName"])) ? $_POST["cstLastName"] : ((isset($row_rsCWGetCustomerData["cst_LastName"])) ? $row_rsCWGetCustomerData["cst_LastName"] : "");
$cstAddress1 = (isset($_POST["cstAddress1"])) ? $_POST["cstAddress1"] : ((isset($row_rsCWGetCustomerData["cst_Address1"])) ? $row_rsCWGetCustomerData["cst_Address1"] : "");
$cstAddress2 = (isset($_POST["cstAddress2"])) ? $_POST["cstAddress2"] : ((isset($row_rsCWGetCustomerData["cst_Address2"])) ? $row_rsCWGetCustomerData["cst_Address2"] : "");
$cstCity = (isset($_POST["cstCity"])) ? $_POST["cstCity"] : ((isset($row_rsCWGetCustomerData["cst_City"])) ? $row_rsCWGetCustomerData["cst_City"] : "");
$cstCountry = (isset($_POST["cstCountry"])) ? $_POST["cstCountry"] : ((isset($thisBillCountryID)) ? $thisBillCountryID : "");
$cstStateProv = (isset($_POST["cstStateProv"])) ? $_POST["cstStateProv"] : ((isset($thisBillStateID)) ? $thisBillStateID : "");
$cstZip = (isset($_POST["cstZip"])) ? $_POST["cstZip"] : ((isset($row_rsCWGetCustomerData["cst_Zip"])) ? $row_rsCWGetCustomerData["cst_Zip"] : "");
$cstPhone = (isset($_POST["cstPhone"])) ? $_POST["cstPhone"] : ((isset($row_rsCWGetCustomerData["cst_Phone"])) ? $row_rsCWGetCustomerData["cst_Phone"] : "");
$cstEmail = (isset($_POST["cstEmail"])) ? $_POST["cstEmail"] : ((isset($row_rsCWGetCustomerData["cst_Email"])) ? $row_rsCWGetCustomerData["cst_Email"] : "");
$cstUsername = (isset($_POST["cstUsername"])) ? $_POST["cstUsername"] : ((isset($row_rsCWGetCustomerData["cst_Username"])) ? $row_rsCWGetCustomerData["cst_Username"] : "");
$cstPassword = (isset($_POST["cstPassword"])) ? $_POST["cstPassword"] : ((isset($row_rsCWGetCustomerData["cst_Password"])) ? $row_rsCWGetCustomerData["cst_Password"] : "");
$cstPasswordConfirm = $cstPassword;
$cstShpName = (isset($_POST["cstShpName"])) ? $_POST["cstShpName"] : ((isset($row_rsCWGetCustomerData["cst_ShpName"])) ? $row_rsCWGetCustomerData["cst_ShpName"] : "");
$cstShpAddress1 = (isset($_POST["cstShpAddress1"])) ? $_POST["cstShpAddress1"] : ((isset($row_rsCWGetCustomerData["cst_ShpAddress1"])) ? $row_rsCWGetCustomerData["cst_ShpAddress1"] : "");
$cstShpAddress2 = (isset($_POST["cstShpAddress2"])) ? $_POST["cstShpAddress2"] : ((isset($row_rsCWGetCustomerData["cst_ShpAddress2"])) ? $row_rsCWGetCustomerData["cst_ShpAddress2"] : "");
$cstShpCity = (isset($_POST["cstShpCity"])) ? $_POST["cstShpCity"] : ((isset($row_rsCWGetCustomerData["cst_ShpCity"])) ? $row_rsCWGetCustomerData["cst_ShpCity"] : "");
$cstShpCountry = (isset($_POST["cstShpCountry"])) ? $_POST["cstShpCountry"] : ((isset($thisShipCountryID)) ? $thisShipCountryID : "");
$cstShpStateProv = (isset($_POST["cstShpStateProv"])) ? $_POST["cstShpStateProv"] : ((isset($thisShipStateID)) ? $thisShipStateID : "");
$cstShpZip = (isset($_POST["cstShpZip"])) ? $_POST["cstShpZip"] : ((isset($row_rsCWGetCustomerData["cst_ShpZip"])) ? $row_rsCWGetCustomerData["cst_ShpZip"] : "");

?>
<form name="PlaceOrder" method="post" action="<?php echo($cartweaver->thisPage);?>">
  <p> * Required fields</p>
<?php 
$cartweaver->displayError("","CST_DUPUSERNAME_ERROR","p");
$cartweaver->displayError("","CST_DUPEMAIL_ERROR","p");  
?>
  <table cellpadding="3" cellspacing="0" class="tabularData">
    <tr>
      <th colspan="2">Customer Information</th>
    </tr>
    <tr class="altRowEven" >
      <td align="right"><?php $cartweaver->displayError("Country","CST_COUNTRY_ERROR");?></td>
      <td><select name="cstCountry" onChange="setDynaList(arrDL1)">
			<option value="0">Choose Country</option>
          <?php	$rsCWGetCountries = $cartweaver->db->queryOfQuery($rsCWGetStates, explode(",","country_Name,country_ID,country_DefaultCountry"), true, null, null);
	foreach ($rsCWGetCountries as $key => $row_rsCWGetCountries) {  ?>         
			<option value="<?php echo $row_rsCWGetCountries['country_ID']?>" <?php echo(($cstCountry == $row_rsCWGetCountries["country_ID"] || ($cstCountry == '' && $row_rsCWGetCountries["country_DefaultCountry"] == 1)) ? "SELECTED" : "");?>><?php echo $row_rsCWGetCountries['country_Name']?></option>
          <?php
	} 
$cartweaver->db->db_data_seek($rsCWGetStates, 0);
?>
        </select>
      *</td>
    </tr>
    <tr class="altRowOdd" >
      <td align="right"><?php $cartweaver->displayError("First Name","CST_FIRSTNAME_ERROR");?></td>
      <td><input name="cstFirstName" type="text" value="<?php echo($cstFirstName);?>" >
        * </td>
    </tr>
    <tr class="altRowEven" >
      <td align="right"><?php $cartweaver->displayError("Last Name","CST_LASTNAME_ERROR");?></td>
      <td><input name="cstLastName" type="text" value="<?php echo($cstLastName);?>">
        * </td>
    </tr>
    <tr class="altRowOdd" >
      <td align="right"><?php $cartweaver->displayError("Address 1","CST_ADDRESS1_ERROR");?></td>
      <td><input name="cstAddress1" type="text" value="<?php echo($cstAddress1);?>">
        * </td>
    </tr>
    <tr class="altRowEven" >
      <td align="right"><?php $cartweaver->displayError("Address 2","CST_ADDRESS2_ERROR");?></td>
      <td><input name="cstAddress2" type="text" value="<?php echo($cstAddress2);?>">
      </td>
    </tr>
    <tr class="altRowOdd" >
      <td align="right"><?php $cartweaver->displayError("City","CST_CITY_ERROR");?></td>
      <td><input name="cstCity" type="text" value="<?php echo($cstCity);?>">
        * </td>
    </tr>
    <tr class="altRowEven" >
      <td align="right"><?php $cartweaver->displayError("State or Province","CST_STATEPROV_ERROR");?></td>
      <td><select name="cstStateProv">
			<option value="">Choose a State or Province</option>
<?php 
$lastTFM_nest = "";
$row_rsCWGetStates = $cartweaver->db->db_fetch_assoc($rsCWGetStates);
do {  
	$tfm_nest = $row_rsCWGetStates['country_Name'];
	if ($lastTFM_nest != $tfm_nest) {
		$lastTFM_nest = $tfm_nest;
		echo("<option value=\"\">----$tfm_nest----</option>");
	} ?>			<option value="<?php echo $row_rsCWGetStates['stprv_ID']?>" <?php echo(($cstStateProv == $row_rsCWGetStates["stprv_ID"]) ? "SELECTED" : "");?>><?php echo $row_rsCWGetStates['stprv_Name']?></option>
<?php
} while ($row_rsCWGetStates = $cartweaver->db->db_fetch_assoc($rsCWGetStates));
$cartweaver->db->db_data_seek($rsCWGetStates, 0);
?>
        </select>
      * </td>
    </tr>
    <tr class="altRowOdd" >
      <td align="right"><?php $cartweaver->displayError("Zip or Postal Code","CST_ZIP_ERROR");?></td>
      <td><input name="cstZip" type="text" value="<?php echo($cstZip);?>">
        * </td>
    </tr>
    <tr class="altRowEven" >
      <td align="right"><?php $cartweaver->displayError("Phone","CST_PHONE_ERROR");?></td>
      <td><input name="cstPhone" type="text" value="<?php echo($cstPhone);?>">
        * </td>
    </tr>
    <tr class="altRowOdd" >
      <td align="right"><?php $cartweaver->displayError("Email Address","CST_EMAIL_ERROR");?></td>
      <td><input name="cstEmail" type="text" value="<?php echo($cstEmail);?>">
        * </td>
    </tr>
    <tr class="altRowEven" >
      <td align="right"><?php $cartweaver->displayError("Username","CST_USERNAME_ERROR");?></td>
      <td><input name="cstUsername" type="text" value="<?php echo($cstUsername);?>">
        * </td>
    </tr>
    <tr class="altRowOdd" >
      <td align="right"><?php $cartweaver->displayError("Password","CST_PASSWORD_ERROR");?></td>
      <td><input name="cstPassword" type="password" value="<?php echo($cstPassword);?>">
        * </td>
    </tr>
    <tr class="altRowEven" >
      <td align="right"><?php $cartweaver->displayError("Confirm Password","CST_PASSWORDCONFIRM_ERROR");?></td>
      <td><input name="cstPasswordConfirm" type="password" value="<?php echo($cstPassword);?>">
        * </td>
    </tr>
    <tr >
      <th colspan="2"><strong>Shipping Address</strong></th>
    </tr>
    <tr class="altRowOdd" >
      <td colspan="2" align="center"><input name="shipSame" type="checkbox" class="formCheckbox" value="Same"<?php echo(isset($_POST["shipSame"])) ? "CHECKED" : ((isset($row_rsCWGetCustomerData["shipSame"])) ? "CHECKED" : "");?> onclick="doShipFields()">
        Same <strong> **required if shipping is different</strong></td>
    </tr>
    <tr class="altRowEven" >
      <td align="right"><?php $cartweaver->displayError("Country","CST_SHPCOUNTRY_ERROR");?></td>
      <td><select name="cstShpCountry" onChange="setDynaList(arrDL2)">
          <option value="0">Choose Country</option>
<?php	
$rsCWGetCountries = $cartweaver->db->queryOfQuery($rsCWGetStates, explode(",","country_Name,country_ID,country_DefaultCountry"), true, null, null);
foreach ($rsCWGetCountries as $key => $row_rsCWGetCountries) {  
?>
          <option value="<?php echo $row_rsCWGetCountries['country_ID']?>" <?php echo(($cstCountry == $row_rsCWGetCountries["country_ID"] || ($cstShpCountry == '' && $row_rsCWGetCountries["country_DefaultCountry"] == 1)) ? "SELECTED" : "");?>><?php echo $row_rsCWGetCountries['country_Name']?></option>
<?php
}
$cartweaver->db->db_data_seek($rsCWGetStates, 0);
?>
        </select>
        ** </td>
    </tr>
    <tr class="altRowOdd" >
      <td align="right"><?php $cartweaver->displayError("Shipping Name","CST_SHPNAME_ERROR");?></td>
      <td><input name="cstShpName" enabled="false" type="text" value="<?php echo($cstShpName);?>">
        ** </td>
    </tr>
    <tr class="altRowEven" >
      <td align="right"><?php $cartweaver->displayError("Address 1","CST_SHPADDRESS1_ERROR");?></td>
      <td><input name="cstShpAddress1" type="text" value="<?php echo($cstShpAddress1);?>">
        ** </td>
    </tr>
    <tr class="altRowOdd" >
      <td align="right"><?php $cartweaver->displayError("Address 2","CST_SHPADDRESS2_ERROR");?></td>
      <td><input name="cstShpAddress2" type="text" value="<?php echo($cstShpAddress2);?>">
      </td>
    </tr>
    <tr class="altRowEven" >
      <td align="right"><?php $cartweaver->displayError("City","CST_SHPCITY_ERROR");?></td>
      <td><input name="cstShpCity" type="text" value="<?php echo($cstShpCity);?>">
        ** </td>
    </tr>
    <tr class="altRowOdd" >
      <td align="right"><?php $cartweaver->displayError("State or Province","CST_SHPSTATEPROV_ERROR");?></td>
      <td><select name="cstShpStateProv">
          <option value="">Choose a State or Province</option>
<?php 
$lastTFM_nest = "";
$row_rsCWGetStates = $cartweaver->db->db_fetch_assoc($rsCWGetStates);
do {  
	$tfm_nest = $row_rsCWGetStates['country_Name'];
	if ($lastTFM_nest != $tfm_nest) {
		$lastTFM_nest = $tfm_nest;
		echo("<option value=\"\">----$tfm_nest----</option>");
	} ?>
          <option value="<?php echo $row_rsCWGetStates['stprv_ID']?>" <?php echo(($cstShpStateProv == $row_rsCWGetStates["stprv_ID"]) ? "SELECTED" : "");?>><?php echo $row_rsCWGetStates['stprv_Name']?></option>
          <?php
} while ($row_rsCWGetStates = $cartweaver->db->db_fetch_assoc($rsCWGetStates));
?>
        </select>
        ** </td>
    </tr>
    <tr class="altRowEven">
      <td align="right"><?php $cartweaver->displayError("Zip or Postal Code","CST_SHPZIP_ERROR");?></td>
      <td><input name="cstShpZip" type="text" value="<?php echo($cstShpZip);?>">
        ** </td>
    </tr>
  </table>
  <input name="orderFormNext" type="submit" class="formButton" value="NEXT &raquo;">
<?php if(isset($_POST["cstShpStateProv"]) && $_POST["cstShpStateProv"] == "0") {
	echo('<script language="JavaScript">setDynaList(arrDL1);setDynaList(arrDL2);</script>');
}?>
</form>
