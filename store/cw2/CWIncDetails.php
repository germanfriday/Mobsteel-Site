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
Name: CWIncDetails.php
Description: 
	This page displays individual products along with all
	of the product's associated SKUs. If a product has only 1 or
	2 Option Groups, then select menus are shown for the customer to
	choose the desired SKUs. If more than 2 Option Groups are available
	for one product, then a crosstab table is displayed with all
	of the SKUs available. Options are handled through the
	CWFunProductOptions include file.
================================================================
*/ 
include("CWLibrary/CWFunProductOptions.php");
/* [ START ] DISPLAY PRODUCT */ 
if($rsCWGetProduct_recordCount != 0) {
	$imageSRC = $imageRoot . $row_rsCWGetProductImage["prdctImage_FileName"];
	$imagePath = expandPath($imageSRC);
?>
<!-- Display Common Product data -->
<link href="../includes/MobStoreStyles.css" rel="stylesheet" type="text/css" />

 <table cellpadding="5" id="tableProductDetails"> 
	<tr>
	<td><?php if (file_exists($imagePath) && is_file($imagePath)){ ?>
			<img src="<?php echo($imageSRC);?>" alt="<?php echo($row_rsCWGetProduct["product_Name"]);?>"> 
		<?php }?></td> 
	<td align="left" valign="top"><!-- Anchor point for when form is submitted -->
	<a name="skus"></a> 
	<span class="BlackTextBold"><?php echo($row_rsCWGetProduct["product_Name"]);?></span>
	<p class="BlackText"><?php echo($row_rsCWGetProduct["product_Description"]);?></p>
	<!-- [ START ] SKUs DATA TABLE --> 
	<?php 
		if (strtolower($cartweaver->settings->detailsDisplay) != "tables") { 
			include("CWLibrary/CWFunPriceList.php");
			echo(getPriceList($row_rsCWGetProduct["product_ID"], $cartweaver->settings->allowBackOrders, $cartweaver->db));
		} 
	/*  [ START ] ==	ERROR ALERTS and CONFIRMATION NOTICE =========================  */
	if ($cartweaver->getCWError()){
	/* If fields were left blank or in correct data entered, show "Field Alert" */
?>
	<p><span class="errorMessage"><?php foreach($cartweaver->cwError as $key => $value) echo($value);?></span></p> 
	<?php 
	}
	if ($urlResult >= 0) {
	echo("<p><strong>* $urlResult");
		if ($urlResult > 1 or $urlResult == 0) {
			echo(" items ");
		}else{
			echo(" item ");
		}
		echo("added to your cart! * <a href=\"" . $cartweaver->settings->targetGoToCart . "?cartid=" . $_SESSION["CartId"] . "&returnurl=" . $cartweaver->thisPageQS . "\">[Go To Cart]</a></strong></p> ");
	} 

/*  [ END] ==  ERROR ALERTS and CONFIRMATION NOTICE =========================  */
/* ===================== [ BEGIN MULTI SKU DISPLAY ]=========================================== */ 
?>
<form action="<?php echo($cartweaver->thisPage . "?cartid=" . $_SESSION["CartId"]);?>" method="post" name="addToCart">
<?php cwProductOptions($productId);?>
	<input name="prodId" type="hidden" value="<?php echo($productId);?>">
	<input name="submit" type="submit" class="formButton" value="Add to Cart"> 
</form>
	</td> 
	</tr>
	<?php
if ($displayUpsell == 1){
 /* If there are upsell products associsted with this Product, display them. */
  if ($rsCWGetupsell_recordCount != 0) { echo("<tr>
	  <td>&nbsp;</td>
	  <td>
	  <br>");
		if($rsCWGetupsell_recordCount > 1){
			echo("You may also be interested in these products:");	   
		}else{
			echo("You may also be interested in this product:");
		}
		   echo("<br>");
		$rsCWGetupsell_currentRow = 0;
		do {
		$rsCWGetupsell_currentRow++;
		  echo("<a href=\"" . $cartweaver->thisPage . "?prodId=" . $row_rsCWGetupsell["upsell_relProdId"] . "\">" . $row_rsCWGetupsell["product_Name"] . "</a>");
		  if (($rsCWGetupsell_recordCount > 1) && ($rsCWGetupsell_recordCount != $rsCWGetupsell_currentRow)){ echo(",&nbsp");}
	   } while ($row_rsCWGetupsell = $cartweaver->db->db_fetch_assoc($rsCWGetupsell)); 
	  echo("</td>
    </tr>");
 }/* END IF - rsCWGetupsell.RecordCount NEQ 0 */	
}/* END if ($displayUpsell == 1) */
?>
</table>
<?php 
/* [ END ] DISPLAY PRODUCT */ 
}else{ 
	echo("<p>No product selected.</p> ");
}
/* End Check for Product ID */ 
?>