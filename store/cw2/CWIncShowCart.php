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

Cartweaver Version: 2.4  -  Date: 11/27/2005
================================================================
Name: CWIncShowCart.php
Description:
	This page shows the user their shopping cart contents. If the
	user is checking out, it also collects their credit card
	information and submits the data to your payment gateway or
	payment processor. If the order is processed successfully the
	customer is sent to the confirmation page.
================================================================
*/
?>
<script type="text/javascript">
var allOn = true;
function selectAllPhp(myForm,myBox){
		var countBoxes = eval("document."+myForm+"['"+myBox+"[]'].length");
	if(!countBoxes){
		eval("document."+myForm+"['"+myBox+"[]'].checked =  allOn");
	}
	else{
		for (var i=0; i<countBoxes ;i++){
			eval("document."+myForm+"['"+myBox+"[]'][" + i + "].checked =  allOn");
		}
	}
	allOn = !allOn;
}

function cleanField(obj){
	obj.value = obj.value.replace(/[^\d]/g,"");
}
</script>
<?php
/*  [ START ] ==	ERROR ALERTS and CONFIRMATION NOTICE =========================  */
if($cartweaver->getCWError()) {
	/* If fields were left blank or incorrect data entered, show error messages */
	echo("<p class=\"errorMessage\">");
	foreach($cartweaver->cwError as $error) {
		printf("%s<br>",$error);
	}
	echo("</p>");
} else {
	if($cartweaver->getQtyAdded() != 0) {/* If something was added, show success message */
		echo("<p><strong>Your shopping cart has been successfully updated.</strong></p> ");
	}
}

if($hasCart != true){ ?>
	<p>There is nothing in your Cart at this time.</p>
<?php
}else{
	if($_SESSION["checkingOut"] == "YES"){
	/* Error Outputting */ 

	if(isset($_SESSION["transactionMessage"]) && $_SESSION["transactionMessage"] != "" && $_SESSION["transactionMessage"] != "Approved"){
		echo("<p class=\"errorMessage\">Your credit card transaction has failed.<br>");
		echo("Gateway Message: <span class=\"errorMessage\">" . $_SESSION["transactionMessage"] . "</span></p>");
	}
	
	printf("<p class=\"smallprint\"> [<a href=\"%s?logout=savecart\"> Your name is not %s %s? Click Here. </a>] </p>",
		$cartweaver->settings->targetCheckout,
		$row_rsCWGetCustData["cst_FirstName"],
		$row_rsCWGetCustData["cst_LastName"]);?>

<table class="tabularData">
  <tr>
    <th align="right">&nbsp;</th>
    <th>Billing</th>
    <th>Shipping</th>
  </tr>
  <tr>
    <th align="right">Name</th>
    <td><?php echo($row_rsCWGetCustData["cst_FirstName"]); ?> <?php echo($row_rsCWGetCustData["cst_LastName"]); ?></td>
    <td valign="top"><?php echo($row_rsCWGetCustData["cst_ShpName"]);?></td>
  </tr>
  <tr valign="top">
    <th align="right">Address:</th>
    <td><?php echo($row_rsCWGetCustData["cst_Address1"]); ?><br>
		<?php if($row_rsCWGetCustData["cst_Address2"] != "") { 
			echo($row_rsCWGetCustData["cst_Address2"]);
		} ?>
		<?php echo($row_rsCWGetCustData["cst_City"]); ?> 
		<?php if(strtolower($row_rsCWGetBillTo["stprv_Name"]) != "none") { ?>
			, <?php echo($row_rsCWGetBillTo["stprv_Name"]); ?>
		<?php } ?> <?php echo($row_rsCWGetCustData["cst_Zip"]); ?><br>
		<?php echo($row_rsCWGetBillTo["country_Name"]); ?></td>
    <td><?php echo($row_rsCWGetCustData["cst_ShpAddress1"]); ?><br>
		<?php 
		if($row_rsCWGetCustData["cst_ShpAddress2"] != "") {
			echo($row_rsCWGetCustData["cst_ShpAddress2"] . "<br>");
		} ?>
		<?php echo($row_rsCWGetCustData["cst_ShpCity"]); ?>
		<?php 
		if(strtolower($row_rsCWGetShipTo["stprv_Name"]) != "none") { ?>
			, <?php echo($row_rsCWGetShipTo["stprv_Name"]); ?>
		<?php } ?> <?php echo($row_rsCWGetCustData["cst_ShpZip"]); ?><br>
		<?php echo($row_rsCWGetShipTo["country_Name"]); ?></td>
  </tr>
  <tr>
    <th align="right">Phone</th>
    <td><?php echo($row_rsCWGetCustData["cst_Phone"]);?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th align="right">Email:</th>
    <td><?php echo($row_rsCWGetCustData["cst_Email"]);?></td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>If address is incorrect - <a href="<?php echo($cartweaver->settings->targetCheckout);?>"><strong>Return to Order Form</strong></a></p>
<?php } /* End if($_SESSION["checkingOut"] == "YES"){ */ ?>
<form name="updatecart" action="<?php echo($cartweaver->thisPageQS);?>" method="post">
  <table class="tabularData">
    <tr>
      <th>Name</th>
      <th align="center">Price</th>
      <th align="center">Qty.</th>
      <th align="center">Total</th>
      <th align="center">Remove<br>
        <a href="javascript:selectAllPhp('updatecart','remove');">Select All</a></th>
    </tr>
    <?php
    /* Set a counter for the row colors. Can't use CurrentRow since we're grouping */
    $recCounter = 0;
   do {
	/* Grab the appropriate product options */
	$rsCWGetOptions_query = "SELECT ot.optiontype_Name, 
	s.option_Name
    FROM tbl_list_optiontypes ot
	INNER JOIN tbl_skuoptions s
	ON ot.optiontype_ID = s.option_Type_ID
	INNER JOIN tbl_skuoption_rel r
	ON s.option_ID = r.optn_rel_Option_ID
	WHERE r.optn_rel_SKU_ID= " . $row_rsCWGetCart["SKU_ID"] . "
	AND ot.optiontype_ID = s.option_Type_ID
	ORDER BY ot.optiontype_Name, s.option_Sort";
	$rsCWGetOptions = $cartweaver->db->executeQuery($rsCWGetOptions_query);
	$row_rsCWGetOptions = $cartweaver->db->db_fetch_assoc($rsCWGetOptions);
	$rsCWGetOptions_recordCount = $cartweaver->db->recordCount;
	?>
    <tr valign="top" class="<?php cwAltRow($recCounter++);?>">
      <td><input name="skuid[]" type="hidden" value="<?php echo($row_rsCWGetCart["SKU_ID"]);?>">
		<?php echo($row_rsCWGetCart["product_Name"]); ?> (<?php echo($row_rsCWGetCart["SKU_MerchSKUID"]); ?>)
		<?php
		/* Output sku options */
		if($rsCWGetOptions_recordCount > 0) {
			do { /* Cartweaver repeat region */
			?> <br>
        <strong style="margin-left: 10px;"><?php echo($row_rsCWGetOptions["optiontype_Name"]);?>:</strong> <?php echo($row_rsCWGetOptions["option_Name"]);?>
        <?php 
			} while ($row_rsCWGetOptions = $cartweaver->db->db_fetch_assoc($rsCWGetOptions)); 
		}?>
      </td>
      <td align="center"><?php echo(cartweaverMoney($row_rsCWGetCart["SKU_Price"]));?></td>
      <td align="center"><input name="qty[]" type="text" value="<?php echo($row_rsCWGetCart["cart_sku_qty"]);?>" size="3" onBlur="cleanField(this)">
        <input name="qty_now[]" type="hidden" value="<?php echo($row_rsCWGetCart["cart_sku_qty"]);?>">
      </td>
      <td align="right"><?php echo(cartweaverMoney($row_rsCWGetCart["lineTotal"]));?></td>
      <td align="center"><input name="remove[]" type="checkbox" class="formCheckbox" value="<?php echo($row_rsCWGetCart["SKU_ID"]);?>">
      </td>
    </tr>
    <?php }  while ($row_rsCWGetCart = $cartweaver->db->db_fetch_assoc($rsCWGetCart)); ?>
    <tr>
      <td colspan="4" align="center" >&nbsp;</td>
      <td align="center"><input name="update" type="submit" class="formButton" id="update" value="Update" />
        <input name="action" type="hidden" id="action" value="update">
      </td>
    </tr>
    <tr>
      <th colspan="3" align="right">Subtotal:&nbsp;</th>
      <td align="right"><?php echo(cartweaverMoney($_SESSION["cartSubtotal"]));?></td>
      <td>&nbsp;</td>
    </tr>
    <?php
	/* If Checking out show Tax, Shipping and Total */
	if ($_SESSION["checkingOut"] == "YES") {
	 /* Display Tax ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */?>
    <tr>
      <th colspan="3" align="right">Tax:&nbsp;</th>
      <td align="right"><?php echo(cartweaverMoney($_SESSION["taxAmt"]));?></td>
      <td>&nbsp;</td>
    </tr>
	<?php if($_SESSION["enableShipping"] == "1") { 
	 /* Display Shipping ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */?>    
    <tr>
      <th colspan="3" align="right" valign="top"> Ship By:
        <?php
		/* If more than one shipping option is available, allow user to select a method */
		if($rsCWShippingMethods_recordCount > 1) { ?>
        <select name="pickShipPref">
          <option value="0" selected>Shipping Method</option>
          <?php $cartweaver->db->db_data_seek($rsCWShippingMethods,0);
		  $row_rsCWShippingMethods = $cartweaver->db->db_fetch_assoc($rsCWShippingMethods);
		  do { /* CW Repeat rsCWGetShipMethods */ ?>
          <option value="<?php echo($row_rsCWShippingMethods["shipmeth_ID"]);?>"<?php if($_SESSION["shipPref"] == $row_rsCWShippingMethods["shipmeth_ID"]) {echo('selected="selected"');}?>><?php echo($row_rsCWShippingMethods["shipmeth_Name"]);?></option>
          <?php }  while ($row_rsCWShippingMethods = $cartweaver->db->db_fetch_assoc($rsCWShippingMethods)); 
			/* End CW Repeat rsCWGetShipMethods */ ?>
        </select>
        <br>
        <input name="Submit" type="submit" value="Select" style="margin-top: 3px;">
        <?php }else{
			echo($row_rsCWShippingMethods["shipmeth_Name"]);		
		} /* End if $rsCWGetShipMethods_recordCount > 1*/ ?></th>
      <td align="right" valign="top"><?php echo(cartweaverMoney($_SESSION["shipTotal"]));?></td>
      <td valign="top">&nbsp;</td>
    </tr>
	<?php } /* END if($_SESSION["enableShipping"] == "1") */?>
    <!-- Display ORDER TOTAL ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
    <tr>
      <th colspan="3" align="right">Order Total:&nbsp;</th>
      <td align="right"><?php echo(cartweaverMoney($_SESSION["orderTotal"]));?></td>
      <td align="center">&nbsp;</td>
    </tr>
    <?php }/* END if($_SESSION["checkingOut"] == "YES"){ */ ?>
  </table>
</form>
<?php
/* End of presentation table */

if ($_SESSION["checkingOut"] != "YES") { ?>
<form action="<?php echo($cartweaver->settings->targetCheckout);?>" method="post">
  <input name="checkout" type="submit" id="checkout" value="Checkout" class="formButton">
</form>
<?php 
	if (isset($_GET["returnurl"])) { // If returnurl is set ?>
		<p><a href="<?php echo($_GET["returnurl"]); ?>">Continue Shopping</a></p>
<?php
	} 
}else{  /* If Checking out, show credit card input form or Processor information*/?>
<form action="<?php echo($cartweaver->thisPage);?>" method="post" name="PlaceOrder">
  <?php 
    if($_SESSION["shipPref"] == 0 && $_SESSION["enableShipping"] == "1") { ?>
		<p>You must choose a shipping method to complete your order</p>
<?php
	}else{ 
		if(strtolower($cartweaver->settings->paymentAuthType) == "gateway") {
			/* get Credit Cards for form field */
			$query_rsCWGetCCards = "SELECT ccard_Name, ccard_Code 
			FROM tbl_list_ccards 
			WHERE ccard_Archive = 0 
			ORDER BY ccard_Name";
			$rsCWGetCCards = $cartweaver->db->executeQuery($query_rsCWGetCCards);
			$rsCWGetCCards_recordCount = $cartweaver->db->recordCount;
			$row_rsCWGetCCards = $cartweaver->db->db_fetch_assoc($rsCWGetCCards);
			?>
   <p>Enter your credit card details to complete your order.</p>
  <table class="tabularData">
    <tr>
      <th colspan="2">Credit Card Data</th>
    </tr>
    <tr class="altRowOdd" >
      <td align="right"><?php
		if($cartweaver->getCWError("cwErrorCHN")) {
			echo("<span class=\"errorMessage\">Card Holder Name</span>");
		}else{
			echo("Card Holder Name");
		}
		?></td>
      <td><input name="cstCCardHolderName" type="text" value="<?php echo($_POST["cstCCardHolderName"]);?>">
        * </td>
    </tr>
    <tr class="altRowEven" >
      <td align="right"><?php
		if($cartweaver->getCWError("cwErrorCT")) {
			echo("<span class=\"errorMessage\">Card Type</span>");
		}else{
			echo("Card Type");
		}
		?></td>
      <td><select name="cstCCardType">
          <option value="forgot" selected>Choose Credit Card</option>
          <?php do { /* CW Repeat rsCWGetCCCards */ ?>
          <option value="<?php echo($row_rsCWGetCCards["ccard_Code"]);?>"<?php if($_POST["cstCCardType"] == $row_rsCWGetCCards["ccard_Code"]) {echo("SELECTED");}?>><?php echo($row_rsCWGetCCards["ccard_Name"]);?></option>
          <?php }  while ($row_rsCWGetCCards = $cartweaver->db->db_fetch_assoc($rsCWGetCCards)); /* End CW Repeat rsCWGetCCCards */ ?>
        </select>
        * </td>
    </tr>
    <tr class="altRowOdd" >
      <td align="right"><?php
		if($cartweaver->getCWError("cwErrorCN")) {
			echo("<span class=\"errorMessage\">Card Number</span>");
		}else{
			echo("Card Number");
		}
		?>
        <br></td>
      <td><input type="text" name="cstCCNumber" value="<?php echo($_POST["cstCCNumber"]);?>">
        *</td>
    </tr>
    <tr class="altRowEven">
      <td align="right"><?php
		if($cartweaver->getCWError("cwErrorCY") || $cartweaver->getCWError("cwErrorCM")) {
			echo("<span class=\"errorMessage\">Expiration Date</span>");
		}else{
			echo("Expiration Date");
		}
		?></td>
      <td><select name="cstExprMonth" id="cst_ExprMonth">
          <option value="forgot">--</option>
          <?php for($monthValue = 1; $monthValue <= 12; $monthValue++) { ?>
          <option<?php if(isset($_POST["cstExprMonth"]) &&  $_POST["cstExprMonth"]== $monthValue) {?> selected<?php }?>><?php echo(($monthValue < 10) ? "0" . $monthValue : $monthValue); ?></option>
          <?php } ?>
        </select>
        /
        <select name="cstExprYr" id="cst_ExprYr">
          <option value="0">--</option>
          <?php for($yearValue = date("Y"); $yearValue <= date("Y") + 7; $yearValue++) { ?>
          <option<?php if(isset($_POST["cstExprYr"]) &&  $_POST["cstExprYr"]== $yearValue) {?> selected<?php }?> value="<?php echo($yearValue);?>"><?php echo($yearValue);?></option>
          <?php }?>
        </select>
        * </td>
    </tr>
    <tr class="altRowEven" >
      <td align="right" valign="top"><?php
		if($cartweaver->getCWError("cwErrorCCV")) {
			echo("<span class=\"errorMessage\">CCV Code</span>");
		}else{
			echo("CCV Code");
		}
		?></td>
      <td><p>
          <input name="cstCCV" type="text" value="<?php echo($_POST["cstCCV"]);?>" size="4" maxlength="4">
          <br>
          <span class="smallprint">This is the 3 digit number<br>
          that appears on the reverse side of your <br>
          credit card (where your signature appears).<br>
          Amex cards only - the 4 digit number on <br>
          the front of your card.</span><br>
          <br>
          <img src="cw2/assets/cards/ccv.gif" width="135" height="86"></p></td>
    </tr>
  </table>
  <?php } elseif(strtolower($cartweaver->settings->paymentAuthType) == "processor") { ?>
  <p>Once you click Place Order below you will receive an order number. On the next page you will need to process your payment through our third party payment processor before your order will be shipped. </p>
  <?php } /* end if(strtolower($cartweaver->settings-paymentAuthType) == "gateway")*/ ?>
  <input name="placeorder" type="submit" class="formButton" value="Place Order">
  <input name="action" type="hidden" value="placeorder">
  <input type="hidden" name="pickShipPref" value="<?php echo($_SESSION["shipPref"]);?>">  
	<?php
	} /* END  if($_SESSION["shipPref"] ==Q 0 && $_SESSION["enableShipping"] == "1") {*/?>
</form>
<?php } /* end if($_SESSION["checkingOut"] != "YES"){ */
}/* END if($hasCart != true) */
?>
