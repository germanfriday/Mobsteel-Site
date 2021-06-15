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

Cartweaver Version: 2.3  -  Date: 09/18/2005
================================================================
Name: Product$_POST["php"]
Description: Displays details and administers SKUs for the selected product.
================================================================
*/
/* Set location for highlighting in Nav Menu */
$strSelectNav = "Products";
$displayUpSell = $_SESSION["showupsell"];

/* ===[ START ]====  Product Action  ========================= */
/*  
  Include contains all the action queries for Adding, Updating, 
  and Deleteing Product and SKU records
*/
if(!isset($addProductError)) {$addProductError = array();}
if(!isset($addSKUError)) {$addSKUError = array();}

require_once("CWValidateProduct.php");
if(count($addProductError) == 0 && count($addSKUError) == 0) {
	require("CWProductAction.php");
}
/* ===[ END ]=====  Product Action  ========================== */
/* Set Product_ID paramiters */
if(!isset($_GET["product_ID"])) {$_GET["product_ID"] = 0;}

/* Set default params for the entire page */
$skuList = 0;
$hasOrders = 0;
$disabledText = "";
$formFocus = "'productform','product_Name'";

if($_GET["product_ID"] == 0) {
	$formFocus = "'productform','product_MerchantProductID'";
}elseif(isset($_GET["addsku"])) {
	$formFocus = "'addSKU','SKU_MerchSKUID'";
}
/* Reactivate Product */
if(isset($_POST["ReactivateProduct"]) && isset($_GET["product_ID"])){
	$query_rsCWArchiveProduct = sprintf("UPDATE tbl_products
	  SET product_Archive = '0'
	  WHERE product_ID = %d",$_GET["product_ID"]);
	$rsCWArchiveProduct = $cartweaver->db->executeQuery($query_rsCWArchiveProduct);
	header("Location: ProductForm.php?product_ID=" . $_GET["product_ID"]);
	exit();
}

/* Get Product Data */
$query_rsCWGetProduct = sprintf("SELECT product_ID, product_MerchantProductID, 
product_Name, product_Description, 
product_ShortDescription, product_Sort, 
product_OnWeb, product_Archive, product_shipchrg
FROM tbl_products WHERE product_ID = %d",$_GET["product_ID"]);
$rsCWGetProduct = $cartweaver->db->executeQuery($query_rsCWGetProduct);
$rsCWGetProduct_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetProduct = $cartweaver->db->db_fetch_assoc($rsCWGetProduct);

/* Is this an archived product? */
$archived = $row_rsCWGetProduct["product_Archive"] == 1;

/* All Available Options */
$query_rsCWAllAvailOptions = "SELECT o.optiontype_ID, o.optiontype_Name, s.option_Name, s.option_Sort, s.option_ID 
FROM tbl_list_optiontypes o
INNER JOIN tbl_skuoptions s
ON o.optiontype_ID = s.option_Type_ID 
WHERE o.optiontype_Archive = 0 
AND s.option_Archive = 0 
ORDER BY o.optiontype_Name, s.option_ID";
$rsCWAllAvailOptions = $cartweaver->db->executeQuery($query_rsCWAllAvailOptions);
$rsCWAllAvailOptions_recordCount = $cartweaver->db->recordCount;
$row_rsCWAllAvailOptions = $cartweaver->db->db_fetch_assoc($rsCWAllAvailOptions);

/* Get Distinct Product Options */
$query_rsCWProductOptions = "SELECT DISTINCT optiontype_ID, optiontype_Name 
FROM tbl_list_optiontypes 
WHERE optiontype_Archive <> 1 
ORDER BY optiontype_Name";
$rsCWProductOptions = $cartweaver->db->executeQuery($query_rsCWProductOptions);
$rsCWProductOptions_recordCount = $cartweaver->db->recordCount;
$row_rsCWProductOptions = $cartweaver->db->db_fetch_assoc($rsCWProductOptions);

/* Get Selected Product Options */
$query_rsCWRelProductOptions = sprintf("SELECT DISTINCT 
	o.optiontype_ID, 
	o.optiontype_Name, 
	s.option_ID, 
	s.option_Name, 
	s.option_Sort
FROM tbl_products p
	INNER JOIN tbl_prdtoption_rel r 
		ON p.product_ID = r.optn_rel_Prod_ID 
	INNER JOIN tbl_list_optiontypes o
		ON o.optiontype_ID = r.optn_rel_OptionType_ID
	INNER JOIN tbl_skuoptions s 	
		ON o.optiontype_ID = s.option_Type_ID	
WHERE p.product_ID= %s
ORDER BY 
	o.optiontype_Name, 
	s.option_Sort",$_GET["product_ID"]);
$rsCWRelProductOptions = $cartweaver->db->executeQuery($query_rsCWRelProductOptions);
$rsCWRelProductOptions_recordCount = $cartweaver->db->recordCount;
$row_rsCWRelProductOptions = $cartweaver->db->db_fetch_assoc($rsCWRelProductOptions);

/* Get SKU Data */
$query_rsCWGetSKUs = sprintf("SELECT SKU_ID, SKU_MerchSKUID, 
SKU_ProductID, SKU_Price, SKU_Weight, 
SKU_Stock, SKU_ShowWeb, SKU_Sort
FROM tbl_skus 
WHERE SKU_ProductID = %s 
ORDER BY SKU_Sort",$_GET["product_ID"]);
$rsCWGetSKUs = $cartweaver->db->executeQuery($query_rsCWGetSKUs);
$rsCWGetSKUs_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetSKUs = $cartweaver->db->db_fetch_assoc($rsCWGetSKUs);

/* Get Categories */
$query_rsCWCategories = "SELECT category_ID, 
category_Name, 
category_sortorder, 
category_archive 
FROM tbl_prdtcategories
ORDER BY category_sortorder";
$rsCWCategories = $cartweaver->db->executeQuery($query_rsCWCategories);
$rsCWCategories_recordCount = $cartweaver->db->recordCount;
$row_rsCWCategories = $cartweaver->db->db_fetch_assoc($rsCWCategories);

/* Get Secondary Categories */
$query_rsCWScndCategories = "SELECT scndctgry_ID, 
scndctgry_Name, 
scndctgry_Sort, 
scndctgry_Archive
FROM tbl_prdtscndcats";
$rsCWScndCategories = $cartweaver->db->executeQuery($query_rsCWScndCategories);
$rsCWScndCategories_recordCount = $cartweaver->db->recordCount;
$row_rsCWScndCategories = $cartweaver->db->db_fetch_assoc($rsCWScndCategories);

/* Get Related Categories */
$query_rsCWRelCategories = sprintf("SELECT prdt_cat_rel_Cat_ID 
FROM tbl_prdtcat_rel 
WHERE prdt_cat_rel_Product_ID = %d",$_GET["product_ID"]);
$rsCWRelCategories = $cartweaver->db->executeQuery($query_rsCWRelCategories);
$rsCWRelCategories_recordCount = $cartweaver->db->recordCount;
$row_rsCWRelCategories = $cartweaver->db->db_fetch_assoc($rsCWRelCategories);

/* Get Related Secondary Categories */
$query_rsCWRelScndCategories = sprintf("SELECT prdt_scnd_rel_ScndCat_ID
FROM tbl_prdtscndcat_rel
WHERE prdt_scnd_rel_Product_ID = %d",$_GET["product_ID"]);
$rsCWRelScndCategories = $cartweaver->db->executeQuery($query_rsCWRelScndCategories);
$rsCWRelScndCategories_recordCount = $cartweaver->db->recordCount;
$row_rsCWRelScndCategories = $cartweaver->db->db_fetch_assoc($rsCWRelScndCategories);


/* Variable for checking if a sku or product has previous orders */
if($rsCWGetSKUs_recordCount != 0) {
	$skuList = $cartweaver->db->valueList($rsCWGetSKUs, "SKU_ID");
}

$query_checkForOrders = "SELECT Count(ordersku_id) as AreThereSkus 
FROM tbl_orderskus WHERE orderSKU_SKU IN($skuList)";
$checkForOrders = $cartweaver->db->executeQuery($query_checkForOrders);
$checkForOrders_recordCount = $cartweaver->db->recordCount;
$row_checkForOrders = $cartweaver->db->db_fetch_assoc($checkForOrders);


if($row_checkForOrders["AreThereSkus"] != 0) {
	$hasOrders = 1;
	$disabledText = ' disabled="disabled"';
}

/* Get Cross Sell List */
$query_rsCWGetUpsell = sprintf("SELECT product_MerchantProductID, product_Name, upsell_id, upsell_ProdId
FROM tbl_products, tbl_prdtupsell
WHERE upsell_relProdId = product_ID
AND upsell_ProdId = %d",$_GET["product_ID"]);
$rsCWGetUpsell = $cartweaver->db->executeQuery($query_rsCWGetUpsell);
$rsCWGetUpsell_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetUpsell = $cartweaver->db->db_fetch_assoc($rsCWGetUpsell);


/* Set default form values */
if(!isset($_POST["product_MerchantProductID"])) {$_POST["product_MerchantProductID"] = '';}
if(!isset($_POST["product_Name"])) {$_POST["product_Name"] = $row_rsCWGetProduct["product_Name"];}
if(!isset($_POST["product_Sort"])) {$_POST["product_Sort"] = $row_rsCWGetProduct["product_Sort"];}
if(!isset($_POST["product_OnWeb"])) {$_POST["product_OnWeb"] = $row_rsCWGetProduct["product_OnWeb"];}
if(!isset($_POST["product_shipchrg"])) {$_POST["product_shipchrg"] = $row_rsCWGetProduct["product_shipchrg"];}

//if(!isset($_POST["product_Category_ID"])) {$_POST["product_Category_ID"] = 0;}

$lstProductOptions = array(); 
if(isset($_POST["product_options"])){
	$lstProductOptions = $_POST["product_options"]; 
}else{
	if($rsCWRelProductOptions_recordCount > 0) {
		$lstTemp = $cartweaver->db->valueList($rsCWRelProductOptions, "optiontype_ID");
		$lstProductOptions = explode(",",$lstTemp);
	}
}

/* Create a list of assigned categories for the select menus */
$lstRelCats = "";
if(isset($_POST["product_Category_ID"])){
	$lstRelCats = $_POST["product_Category_ID"];
}else{
	$lstTemp = $cartweaver->db->valueList($rsCWRelCategories, "prdt_cat_rel_Cat_ID");
	$lstRelCats = explode(",",$lstTemp);
}

/* Create a list of assigned secondary categories for the select menus */
$lstRelScndCats = "";
if(isset($_POST["scndctgry_ID"])){
	$lstRelScndCats = $_POST["scndctgry_ID"];
}else{
	$lstTemp = $cartweaver->db->valueList($rsCWRelScndCategories, "prdt_scnd_rel_ScndCat_ID");
	$lstRelScndCats = explode(",",$lstTemp);
}
/*
if(count($lstRelScndCats) == 0) {
	$lstRelScndCats = array("1");
}
*/
if(!isset($_POST["product_ShortDescription"])) {$_POST["product_ShortDescription"] = $row_rsCWGetProduct["product_ShortDescription"];}
if(!isset($_POST["product_Description"])) {$_POST["product_Description"] = $row_rsCWGetProduct["product_Description"];}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CW Admin: Product Data</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="assets/admin.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
function setfocus(myForm,myField)
{
	eval("document."+myForm+"."+myField+".focus()");
}

function showUpload(obj) {
	window.open(obj.href, "imageUploadWindow", "width=700,height=600,scrollbars=yes,resizable=yes");
}

function updateImagePreview(imgType, imageSRC) {
	var obj = eval("document.productform.image"+imgType);
	obj.src = imageSRC;	
	if(imageSRC.lastIndexOf("/")+1 != imageSRC.length) {
		obj.alt = 'Image path: '+imageSRC;
		obj.style.display = 'inline';
	}
}

</script>
</head>
<body onLoad="setfocus(<?php echo($formFocus);?>)"> 
<?php include("CWIncNav.php");?> 
<div id="divMainContent"> 
<?php /* Place our product search form with this include file */
require("CWProductSearch.php");?> 
	<h1>Product Data <?php if($archived) {echo(" *Archived product* ");}?></h1> 	
	<form method="post" name="productform" action="<?php echo($cartweaver->thisPageQS);?>"> 
		<?php if(count($addProductError) != 0) {
			echo("<p><strong>There were errors adding/updating this product.</strong></p>
			<ul>");

			foreach($_POST as $key=>$value)
				$_POST[$key] = stripslashes($value);

			while (list($key, $error) = each($addProductError)) {
    			echo("<li>$error</li>\n");
			}
			echo("</ul>");
		}?>
		<table> 
	<tr> 
		<th align="right">ID:</th> 
		<td><?php if($rsCWGetProduct_recordCount == 0){
					echo('<input name="product_MerchantProductID" type="text" id="product_MerchantProductID" tabindex="1" value="' . $_POST["product_MerchantProductID"] . '" size="25">');
				}else{
					echo($row_rsCWGetProduct["product_MerchantProductID"]);
					echo('<input name="product_ID" type="hidden" value="' . $row_rsCWGetProduct["product_ID"] . '">');
				}?> </td> 		
	</tr> 
	<tr> 
		<th align="right">Name:</th> 
		<td>
		<input name="product_Name" type="text" value="<?php echo(htmlentities($_POST["product_Name"]));?>" size="30" tabindex="2"> </td> 
	</tr> 
	<tr> 
		<th align="right">Sort:</th> 
		<td>
		<input name="product_Sort" type="text" value="<?php echo($_POST["product_Sort"]);?>" size="5" tabindex="3"></td> 
	</tr> 
	<tr> 
		<th align="right">OnWeb:</th> 
		<td>
		<select name="product_OnWeb" tabindex="4"> 
		<?php if($_POST["product_OnWeb"] == "1" || $_POST["product_OnWeb"] == "") { 
			echo('<option value="1" selected>Yes</option>');
			echo('<option value="0">No</option>');
		}else{
			echo('<option value="1" >Yes</option>');
			echo('<option value="0" selected>No</option>');
		}?>
		  </select> </td> 
	</tr> 
	<tr> 
		<th align="right">Charge Shipping: </th> 
		<td><select name="product_shipchrg" tabindex="5">
		<?php if($_POST["product_shipchrg"] == "1") { 
			echo('<option value="1" selected>Yes</option>');
			echo('<option value="0">No</option>');
		}else{
			echo('<option value="1" >Yes</option>');
			echo('<option value="0" selected>No</option>');
		}?> 
			</select> </td> 
	</tr> 
	<tr>
	<th align="right">Category:</th> 
		<td>
		<select name="product_Category_ID[]" size="7" multiple="multiple" tabindex="6"> 
				<?php do { // Cartweaver repeat region
?>
					<option <?php if($row_rsCWCategories["category_archive"] == 1) {echo(' style="color: #999999;"');}?> value="<?php echo($row_rsCWCategories["category_ID"]);?>"<?php if(arrayFind($lstRelCats,$row_rsCWCategories["category_ID"])!= -1){ echo(' selected="selected"');}?>><?php echo($row_rsCWCategories["category_Name"]);?></option> 
				<?php } while ($row_rsCWCategories = $cartweaver->db->db_fetch_assoc($rsCWCategories)); ?>
		  </select> 
			<input name="HasOrders" type="hidden" id="HasOrders" value="<?php echo($hasOrders);?>"> 
			<br />
				<span class="smallprint">Archived categories are displayed in <span style="color: #999999;">gray</span>.</span></td> 
	</tr>

	<tr>
	<th align="right">Secondary<br> 
			Categories: </th> 
		<td><select name="scndctgry_ID[]" size="7" multiple="multiple" tabindex="8"> 
				<?php do { // Cartweaver repeat region
?>
					<option <?php if($row_rsCWScndCategories["scndctgry_Archive"] == 1) {echo(' style="color: #999999;"');}?> value="<?php echo($row_rsCWScndCategories["scndctgry_ID"]);?>"<?php if(arrayFind($lstRelScndCats,$row_rsCWScndCategories["scndctgry_ID"])!= -1){ echo(' selected="selected"');}?>><?php echo($row_rsCWScndCategories["scndctgry_Name"]);?></option>
				<?php } while ($row_rsCWScndCategories = $cartweaver->db->db_fetch_assoc($rsCWScndCategories)); ?>
			</select> <br />
					<span class="smallprint">Archived secondary categories are displayed in <span style="color: #999999;">gray</span>.</span></td> 
	</tr>
	<tr> 
		<th align="right">Product Options: </th> 
		<td>
			<select name="product_options[]" size="5" tabindex="8" multiple="multiple" <?php echo($disabledText);?>> 
				<?php do { // Cartweaver repeat region
?>
					<option value="<?php echo($row_rsCWProductOptions["optiontype_ID"]);?>"<?php if(arrayFind($lstProductOptions,$row_rsCWProductOptions["optiontype_ID"])!= -1){ echo(' selected="selected"');}?>><?php echo($row_rsCWProductOptions["optiontype_Name"]);?></option> 
				<?php } while ($row_rsCWProductOptions = $cartweaver->db->db_fetch_assoc($rsCWProductOptions)); ?>
			</select>
			<?php if($hasOrders == 1){
				echo('<span class="smallprint"><br> 
				Orders placed, no changes allowed </span>'); 
			}?>
		</td> 
		</tr> 
	</table> 
	<table> 
		<caption>
		Descriptions
		</caption> 
		<tr> 
			<th align="right">Short:</th> 
			<td> <textarea name="product_ShortDescription" cols="60" rows="5" tabindex="9"><?php echo($_POST["product_ShortDescription"]);?></textarea> </td> 
		</tr> 
		<tr> 
			<th align="right">Long: </th> 
			<td> <textarea name="product_Description" cols="60" rows="10" tabindex="10"><?php echo($_POST["product_Description"]);?></textarea> </td> 
		</tr> 
	</table> 
<?php	
/* Get Thumbnail Image */
$query_rsCWGetThumbnail = sprintf("SELECT prdctImage_FileName,prdctImage_ID 
FROM tbl_prdtimages 
WHERE prdctImage_ProductID = %s AND prdctImage_ImgTypeID = 1",$_GET["product_ID"]);
$rsCWGetThumbnail = $cartweaver->db->executeQuery($query_rsCWGetThumbnail);
$rsCWGetThumbnail_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetThumbnail = $cartweaver->db->db_fetch_assoc($rsCWGetThumbnail);

/* Get Large Image */ 
$query_rsCWGetLargeImage = sprintf("SELECT prdctImage_FileName,prdctImage_ID 
FROM tbl_prdtimages 
WHERE prdctImage_ProductID = %s AND prdctImage_ImgTypeID = 2",$_GET["product_ID"]);
$rsCWGetLargeImage = $cartweaver->db->executeQuery($query_rsCWGetLargeImage);
$rsCWGetLargeImage_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetLargeImage = $cartweaver->db->db_fetch_assoc($rsCWGetLargeImage);
?> 
	<table> 
		<caption>
		Photo Management
		</caption> 
		<tr> 
		<?php 
		$thumbRoot = $imageThumbFolder;
		// Image stuff for thumbnails
		$imageRoot = "../../$imageThumbFolder";			
		$imageFile = (isset($_POST["ImageFileName_T"])) ? $_POST["ImageFileName_T"] : $row_rsCWGetThumbnail["prdctImage_FileName"];
		$imageSRC = $imageRoot . $imageFile; 
		$imagePath = $siteRoot . $thumbRoot . $imageFile;
		?>
			<th align="right">Photo Thumb:</th>
			<td><input name="ImageFileName_T" type="text" tabindex="11" value="<?php echo($imageFile);?>" size="25"  onblur="<?php echo("updateImagePreview('1','$imageThumbFolder'+this.value);");?>"> 
				<input name="ImageID_T" type="hidden" value="<?php echo($row_rsCWGetThumbnail["prdctImage_ID"]);?>">
				<?php echo('<a href="ProductImageUpload.php?type=1&file=' . $imageFile . '" title="Upload/Manage Images" onclick="showUpload(this); return false;"><img src="assets/images/folder.gif" width="16" height="16" border="0" />');?> 
				<br /> 
					<?php 
				if(file_exists($imagePath) && is_file($imagePath)){
					echo("<img src=\"$imageSRC\" id=\"image1\" alt=\"Product Thumbnail Image path: $imageSRC\">");
				}else{ 
					echo('<img id="image1" src="" style="display: none;">');
				}?></td> 
		</tr>
		<tr>
			<th align="right">Photo Large:</th> 
			<td><?php 
			$imageFile = (isset($_POST["ImageFileName_L"])) ? $_POST["ImageFileName_L"] : $row_rsCWGetLargeImage["prdctImage_FileName"];
			$imageRoot = "../../$imageLargeFolder";			
			$fullRoot = $imageLargeFolder;
			$imageSRC = $imageRoot . $imageFile; 
			$imagePath = $siteRoot . $fullRoot . $imageFile;
			?><input name="ImageFileName_L" type="text" tabindex="12" value="<?php echo($imageFile);?>" size="25" onBlur="<?php echo("updateImagePreview('2','$imageLargeFolder'+this.value);");?>"> 
				<input name="ImageID_L" type="hidden" value="<?php echo($row_rsCWGetLargeImage["prdctImage_ID"]);?>">
				<?php echo('<a href="ProductImageUpload.php?type=2&file=' . $imageFile . '" title="Upload/Manage Images" onclick="showUpload(this); return false;"><img src="assets/images/folder.gif" alt="Upload Image"width="16" height="16" border="0" /></a>');?>
				<br /> 
		<?php 
				if(file_exists($imagePath) && is_file($imagePath)){
					echo("<img src=\"$imageSRC\" id=\"image2\" alt=\"Product Large Image path: $imageSRC\">");
				}else{ 
					echo('<img id="image2" src="" style="display: none;">');
				}?></td> 
	    </tr> 
	</table> 
		<?php 
		if($_GET["product_ID"] != '0') {
			echo('<input name="UpdateProduct" type="submit" class="formButton" id="UpdateProduct" tabindex="13" value="Update Product"> ');
			if(!$archived) {
				if($hasOrders != 0) {
					echo('<input name="ArchiveProduct" type="submit" class="formButton" tabindex="13" onClick="return confirm(\'Are you SURE you want to ARCHIVE this Product and all associated SKUs AND REMOVE IT FROM THE WEB.?\')" value="Archive Product">'); 
					echo('<p> *To delete Product, delete all SKUs first.* </p>'); 
				}else{
					echo('<input name="DeleteProduct" type="submit" class="formButton" id="DeleteProduct" tabindex="13" onClick="return confirm(\'Are you SURE you want to DELETE this Product? All related SKUs will also be deleted. This action cannot be undone.\')" value="Delete Product">');
				}
			}else{
				echo('<input name="ReactivateProduct" type="submit" class="formButton" tabindex="13" onClick="return confirm(\'Are you SURE you want to REACTIVATE this Product and all associated SKUs? It will show up on the web.\')" value="Reactivate Product">'); 
			}
		}else{ 
			echo('<input name="AddProduct" type="submit" class="formButton" id="AddProduct" tabindex="13" value="Add Product">');
		} ?>
		<input name="HasOrders" type="hidden" id="HasOrders" value="<?php echo($hasOrders);?>">
	</form>


<?php if($_GET["product_ID"] != "0") {
/* Up SELL */
	if($displayUpSell == 1) {
/* Add Up Sell Form */ 
$query_rsCWUpsellProducts = "SELECT product_ID, product_Name
FROM tbl_products
ORDER BY product_Name";
$rsCWUpsellProducts = $cartweaver->db->executeQuery($query_rsCWUpsellProducts);
$rsCWUpsellProducts_recordCount = $cartweaver->db->recordCount;
$row_rsCWUpsellProducts = $cartweaver->db->db_fetch_assoc($rsCWUpsellProducts);
?>
	<form name="frmAddUpSell" method="POST" action="<?php echo($cartweaver->thisPage);?>"> 
		<fieldset> 
		<legend>Up Sell Admin</legend> 
		<table class="noBorders"> 
			<tr> 
				<td><select name="UpSellProduct_ID">
				<?php do { // Cartweaver repeat region
				?>
					<option value="<?php echo($row_rsCWUpsellProducts["product_ID"]);?>"><?php echo($row_rsCWUpsellProducts["product_Name"]);?></option>
				<?php } while ($row_rsCWUpsellProducts = $cartweaver->db->db_fetch_assoc($rsCWUpsellProducts)); ?>
				</select></td> 
				<td><input name="ADDUpsell" type="submit" class="formButton" id="ADDUpsell" value="Add Up Sell Product"></td> 
			</tr> 
		</table> 
		<input name="product_ID" type="hidden" value="<?php echo($_GET["product_ID"]);?>"> 
		<?php
		/* Display a Up Sell error if one exist */ 
		if(isset ($upSellProductIDError)){
			echo('<div class="smallprint">\n');
			echo("<p><strong>**$upSellProductIDError</strong></p>
			</div> "); 
		}
		/* Display up sell items if there are any */ 
		if($rsCWGetUpsell_recordCount != 0) { ?>
			<table class="tabularData"> 
				<tr>
					<th>Related Product </th>
					<th>Delete</th>
				</tr>
				<?php do {	?>	
					<tr> 
						<td><?php echo($row_rsCWGetUpsell["product_Name"]);?> (<?php echo($row_rsCWGetUpsell["product_MerchantProductID"]);?>)</td> 
						<td style="text-align: center;"><a href="<?php echo($cartweaver->thisPage . '?delupsell_id=' . $row_rsCWGetUpsell["upsell_id"] . '&product_ID=' . $_GET["product_ID"]);?>"><img src="assets/images/delete.gif" width="14" height="17" border="0"></a></td> 
					</tr> 
				<?php } while ($row_rsCWGetUpsell = $cartweaver->db->db_fetch_assoc($rsCWGetUpsell)); ?>
			</table> 
		<?php } ?>
		</fieldset> 
	</form>
	<?php } /* END IF - var.DisplayUpSell EQ 1 */
/* END Up SELL */
}/* END IF - $_GET["product_ID"] NEQ "0" */


	if(isset($_GET["addsku"])) {
		if(!isset($_POST["SKU_MerchSKUID"])) {$_POST["SKU_MerchSKUID"] = "";}
		if(!isset($_POST["SKU_Price"])) {$_POST["SKU_Price"] = "";}
		if(!isset($_POST["SKU_Sort"])) {$_POST["SKU_Sort"] = 0;}
		if(!isset($_POST["SKU_Weight"])) {$_POST["SKU_Weight"] = 0;}
		if(!isset($_POST["SKU_Stock"])) {$_POST["SKU_Stock"] = 0;}
		?>
		<p>[ <a href="<?php echo($cartweaver->thisPage . '?product_ID=' . $row_rsCWGetProduct["product_ID"]);?>">Hide Add SKU Form </a>]</p> 
		<hr> 
		<form name="addSKU" method="post" action="<?php echo($cartweaver->thisPageQS . "#addsku");?>">
			<fieldset> 
			<legend><a name="addsku">Add New SKU</a></legend> 
			<?php if(count($addSKUError) != 0) {
				echo("<p><strong>There was an error adding the new sku</strong></p> 
				<ul>");
				while (list($key, $error) = each($addSKUError)) {
    				echo("<li>$error</li>\n");
				}
				echo("</ul>");
			}?>
			<table> 
				<tr> 
					<th>SKU</th> 
					<th>On Web </th> 
					<th valign="top">Price</th> 
					<th valign="top">Sort</th> 
					<th valign="top">Weight</th> 
					<th valign="top">Stock</th> 
				</tr> 
				<tr> 
					<td><input name="SKU_MerchSKUID" type="text" value="<?php echo($_POST["SKU_MerchSKUID"]);?>" tabindex="14"></td> 
					<td>
						<select name="SKU_ShowWeb" id="SKU_ShowWeb" tabindex="14"> 
							<option value="1" <?php echo((isset($_POST["SKU_ShowWeb"]) && $_POST["SKU_ShowWeb"] == 1) ? ' selected="selected"' : '');?>>Yes</option> 
							<option value="0" <?php echo((isset($_POST["SKU_ShowWeb"]) && $_POST["SKU_ShowWeb"] == 0) ? ' selected="selected"' : '');?>>No</option> 
						</select> </td> 
					<td valign="top"><input name="SKU_Price" type="text" value="<?php echo($_POST["SKU_Price"]);?>" size="10" tabindex="14"></td> 
					<td valign="top"><input name="SKU_Sort" type="text" value="<?php echo($_POST["SKU_Sort"]);?>" size="5" tabindex="14"></td> 
					<td valign="top"><input name="SKU_Weight" type="text" value="<?php echo($_POST["SKU_Weight"]);?>" size="5" tabindex="14"></td> 
					<td valign="top"><input name="SKU_Stock" type="text" value="<?php echo($_POST["SKU_Stock"]);?>" size="5" tabindex="14"></td> 
				</tr> 
			</table> 
			<?php if($rsCWRelProductOptions_recordCount > 0) { 
			$cartweaver->db->db_data_seek($rsCWRelProductOptions,0);
			$row_rsCWRelProductOptions = $cartweaver->db->db_fetch_assoc($rsCWRelProductOptions);
			echo('<table class="tabularData"> 
				<caption>
				SKU Options
				</caption> 
				');
				$currentRow = 1;
				$lastTFM_nest = "";
				do {  
					$tfm_nest = $row_rsCWRelProductOptions['optiontype_Name'];
					if ($lastTFM_nest != $tfm_nest) {
						$lastTFM_nest = $tfm_nest;
						echo('<tr> 
						<th align="right">' . $row_rsCWRelProductOptions["optiontype_Name"] . ': </th> 
						<td> <select name="selOption' . $currentRow++ . '" tabindex="14">');
					}
					echo('<option value="' . $row_rsCWRelProductOptions["option_ID"] . '">' . $row_rsCWRelProductOptions["option_Name"] . '</option>');
					if ($lastTFM_nest != $tfm_nest) {
						
						echo('</select> </td> 
					</tr>');
					}
				} while ($row_rsCWRelProductOptions = $cartweaver->db->db_fetch_assoc($rsCWRelProductOptions));	?>
			</table> 
			<?php }else{
			echo('<div class="smallprint">No SKU Options available for this product.</div>'); 
		}?>
			<input name="product_ID" type="hidden" id="product_ID" value="<?php echo($_GET["product_ID"]);?>"> 
			<input name="addsku" type="submit" class="formButton" tabindex="14" value="Add New SKU"> 
			</fieldset> 
		</form> 
		<hr> 
	<?php } /* end if($rsCWRelProductOptions_recordCount > 0) */
	if($_GET["product_ID"] != "0") {
		if(!isset($_GET["addsku"])) { 
			echo('<p>SKU Data:[ <a href="ProductForm.php?product_ID=' . $row_rsCWGetProduct["product_ID"] . '&addsku=ADD#addsku">Add SKU</a> ]&nbsp;&nbsp;</p> ');
		}?>		
		<?php if($rsCWGetSKUs_recordCount > 0) {?>
		<table class="noBorders"> 
			<?php $cartweaver->db->db_data_seek($rsCWGetSKUs,0);
			$row_rsCWGetSKUs = $cartweaver->db->db_fetch_assoc($rsCWGetSKUs);
			$recCounter = 0;
			do{
				/* Check for orders containing this SKU */ 
				$query_checkForOrders = "SELECT Count(ordersku_id) as AreThereSkus 
				FROM tbl_orderskus 
				WHERE orderSKU_SKU = " . $row_rsCWGetSKUs["SKU_ID"];
				$checkForOrders = $cartweaver->db->executeQuery($query_checkForOrders);
				$checkForOrders_recordCount = $cartweaver->db->recordCount;
				$row_checkForOrders = $cartweaver->db->db_fetch_assoc($checkForOrders);

				if($row_checkForOrders["AreThereSkus"] != 0) {
					$hasOrders = 1; 
					$disabledText = ' disabled="disabled"'; 
				}else{
					$hasOrders = 0; 
					$disabledText = ""; 
				}
				/* Get SKU Option */ 
				$query_rsCWSkuOptions = "SELECT DISTINCT r.optn_rel_ID, o.option_Type_ID, r.optn_rel_Option_ID 
				FROM tbl_skuoptions o 
				INNER JOIN tbl_skuoption_rel r 
				ON o.option_ID = r.optn_rel_Option_ID 
				WHERE r.optn_rel_SKU_ID = " . $row_rsCWGetSKUs["SKU_ID"];
				$rsCWSkuOptions = $cartweaver->db->executeQuery($query_rsCWSkuOptions);
				$rsCWSkuOptions_recordCount = $cartweaver->db->recordCount;
				$row_rsCWSkuOptions = $cartweaver->db->db_fetch_assoc($rsCWSkuOptions);
				
				$lstSKUOptions = array();
				if($rsCWSkuOptions_recordCount > 0) {
					$lstSKUOptions = $cartweaver->db->fieldToArray($rsCWSkuOptions, "optn_rel_Option_ID");
				}
				?>
				<tr class="<?php cwAltRow($recCounter++);?>"> 
					<td><form method="post" name="updateSKU" action="<?php echo($cartweaver->thisPage . '?product_ID=' . $_GET["product_ID"]);?>"> 
						<fieldset>
							<legend>
								<?php if($hasOrders == 0) { 
									echo('<a href="' . $cartweaver->thisPage . '?delete_sku_id=' . $row_rsCWGetSKUs["SKU_ID"] . '&product_ID=' . $row_rsCWGetProduct["product_ID"] . '" onClick="return confirm(\'Are you SURE you want to DELETE this SKU?\')"><img src="assets/images/delete.gif" alt="Delete SKU" name="delete" width="14" height="17" align="middle"></a>'); 
								}else{
									echo('<a href="javascript:alert(\'Cannot Delete This SKU - It has associated orders\');"><img src="assets/images/delete-fade.gif" alt="You cannot delete this SKU" name="delete" width="14" height="17" align="middle"></a>');
								} ?>
								<a name="<?php echo($row_rsCWGetSKUs["SKU_ID"]);?>"><?php echo($row_rsCWGetSKUs["SKU_MerchSKUID"]);?></a></legend> 
							<?php if(isset ($cantDeleteSKU)) { 
								if($_GET["delete_sku_id"] == $row_rsCWGetSKUs["SKU_ID"]) {
									echo("<p><strong>$cantDeleteSKU</strong></p>");
								}
							}?>
							<table class="tabularData"> 
								<tr> 
									<th>On Web </th> 
									<th>Price </th> 
									<th>Sort</th> 
									<th>Weight</th> 
									<th>Stock</th> 
								</tr> 
								<tr> 
									<td><select name="SKU_ShowWeb" id="SKU_ShowWeb" tabindex="14"> 
											<?php if($row_rsCWGetSKUs["SKU_ShowWeb"] == 1) {
												echo('<option value="1" selected="selected">Yes</option>
												<option value="0">No</option>');
											}else{
												echo('<option value="1">Yes</option>
												<option value="0" selected="selected">No</option>');
											}?>
										</select></td> 
									<td><input name="SKU_Price" type="text" value="<?php echo($row_rsCWGetSKUs["SKU_Price"]);?>" size="10" tabindex="14"> </td> 
									<td><input name="SKU_Sort" type="text" value="<?php echo($row_rsCWGetSKUs["SKU_Sort"]);?>" size="5" tabindex="14"></td> 
									<td><input name="SKU_Weight" type="text" value="<?php echo($row_rsCWGetSKUs["SKU_Weight"]);?>" size="5" tabindex="14"></td> 
									<td><input name="SKU_Stock" type="text" value="<?php echo($row_rsCWGetSKUs["SKU_Stock"]);?>" size="5" tabindex="14"></td> 
								</tr> 
							</table> 
							<?php if($rsCWRelProductOptions_recordCount > 0) { 
								$cartweaver->db->db_data_seek($rsCWRelProductOptions,0);
								$row_rsCWRelProductOptions = $cartweaver->db->db_fetch_assoc($rsCWRelProductOptions);
								echo('<table class="tabularData"> 
									<caption>
 									SKU Options
									</caption> 
									');
									if($hasOrders == 0) {
										$currentRow = 1;
										$lastTFM_nest = "";
										do {  
											$tfm_nest = $row_rsCWRelProductOptions['optiontype_Name'];
											if ($lastTFM_nest != $tfm_nest) {
												$lastTFM_nest = $tfm_nest;
												echo('<tr> 
												<th align="right">' . $row_rsCWRelProductOptions["optiontype_Name"] . ': </th> 
												<td> <select name="selOption' . $currentRow++ . '" tabindex="14"' . $disabledText . '>');
											}
											if(arrayFind($lstSKUOptions,$row_rsCWRelProductOptions["option_ID"])!=-1) { 
												echo('<option value="' . $row_rsCWRelProductOptions["option_ID"] . '" selected="selected">' . $row_rsCWRelProductOptions["option_Name"] . '</option>');
											}else{
												echo('<option value="' . $row_rsCWRelProductOptions["option_ID"] . '">' . $row_rsCWRelProductOptions["option_Name"] . '</option>');
											}
											if ($lastTFM_nest != $tfm_nest) {
												
												echo('</select> </td> 
											</tr>');
											}
										} while ($row_rsCWRelProductOptions = $cartweaver->db->db_fetch_assoc($rsCWRelProductOptions));
									}else{
										$currentRow = 1;
										$lastTFM_nest = "";
										do {  
											$tfm_nest = $row_rsCWRelProductOptions['optiontype_Name'];
											if ($lastTFM_nest != $tfm_nest) {
												$lastTFM_nest = $tfm_nest;
												echo('<tr> 
												<th align="right">' . $row_rsCWRelProductOptions["optiontype_Name"] . ': </th> 
												<td>');
											}
											
											if(arrayFind($lstSKUOptions,$row_rsCWRelProductOptions["option_ID"])!= -1) { 
												echo($row_rsCWRelProductOptions["option_Name"]);
												echo('<input type="hidden" name="selOption' . $currentRow++ . '" id="selOption' . $currentRow++ . '" value="' . $row_rsCWRelProductOptions["option_ID"] . '">');
											}
											if ($lastTFM_nest != $tfm_nest) {
												
												echo('</td> 
											</tr>');
											}
										} while ($row_rsCWRelProductOptions = $cartweaver->db->db_fetch_assoc($rsCWRelProductOptions));
									} 
								echo('</table>');
								}else{
								echo('<div class="smallprint">No SKU Options available for this product.</div>'); 
							}
							if($hasOrders == 1) {
								echo('<div class="smallprint">Orders placed, no Option changes allowed</div>'); 
							}
							?>
							<input name="UpdateSKU" type="submit" class="formButton" id="UpdateSKU" tabindex="15" value="Update This SKU"> 
							<input name="SKU_ProductID" type="hidden" id="SKU_ProductID" value="<?php echo($row_rsCWGetSKUs["SKU_ProductID"]);?>"> 
							<input name="sku_id" type="hidden" id="sku_id" value="<?php echo($row_rsCWGetSKUs["SKU_ID"]);?>"> 
						 </fieldset>
					</form>  </td> 
				</tr> 
			<?php } while ($row_rsCWGetSKUs = $cartweaver->db->db_fetch_assoc($rsCWGetSKUs)); ?>
		</table>
	<?php }/*END if($rsCWGetSKUs_recordCount > 0) */
	} /* END if($_GET["product_ID"] != "0") */?>
</div> 
</body>
</html>
<?php
cwDebugger($cartweaver);
?>