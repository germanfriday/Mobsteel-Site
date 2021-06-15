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

Cartweaver Version: 2.0  -  Date: 07/22/2005
================================================================
Name: CWIncDetailsSetup.php
Description: 
	This page sets up CWIncDetails.php, which shows individual products 
	along with all of the product's associated SKUs. If a product has only 
	1 or 2 Option Groups, then select menus are shown for the customer to
	choose the desired SKUs. If more than 2 Option Groups are available
	for one product, then a crosstab table is displayed with all
	of the SKUs available. Options are handled through the
	CWFunProductOptions include file.
	
	This page creates the recordsets and performs the redirects, if 
	necessary, before any HTML is sent to the browser, and is included 
	via the application.php file.
==============================================================================
*/ 
/* Set Local Product_ID variable based on value passed */
if (isset($_POST["prodId"])){
	$productId = $_POST["prodId"];
}elseif (isset($_GET["prodId"])) {
	$productId = $_GET["prodId"];
}elseif (isset($_GET["SKU_ProductID"])) {
	$productId = $_GET["SKU_ProductID"];
}else {
	$$productId = 0;
}

if(isset($_GET["stockAlert"]) && $_GET["stockAlert"] != "") {
   $cartweaver->setCWError("stockAlert","You have selected more quantity than is currently available.");
}

$urlResult = (isset($_GET["result"])) ? $_GET["result"] : "-1";

$displayUpsell = $_SESSION["showupsell"];

/* ======= ///  [ START ADD TO CART] /// ========================================================== */
/*  IF "FORM ADD TO CART" DEFINED, Add item to the cart. */
if(isset($_POST["submit"])) {
	$cartweaver->setProductId($productId);
	/* If we have specific skus, great, otherwise go through individual options */
	if(isset($_POST["skuid"])) {
		/* Add multiple skus to the cart */
		$cartweaver->add($_POST["skuid"], $_POST["qty"]);
	}else{
		/* Else we don't have SKUs */
		/* Loop through the form collection and grab all the options */
		$strOptionsArr = Array();
		$numOptions = 0; 

		/* If the form field is one of our select menus and they've chosen something besides
			the first option, then add the option to the list and increase the count */
		while (list($key,$val) = each($_POST)){
            if(substr($key,0,3) == "sel"){
    			array_push($strOptionsArr, $val);
    			$numOptions++;  
            }
		}
		$strOptions = implode(",", $strOptionsArr);
		
		/* Add to cart using only selected options. Pass option as a list, number of options as number and FORM.qty */
		$cartweaver->setOptionList($strOptions);
		$cartweaver->setSkuQty($_POST["qty"]);
		$cartweaver->multiOption();
	}
	/* End isset($_POST["skuid"]) */
	if (!$cartweaver->getCWError()) {
		$redirect =  $cartweaver->settings->targetDetails . "?prodId=" . $productId . "&result=" . $cartweaver->getQtyAdded() . "&stockAlert=" .$cartweaver->getCWError("stockAlert");
		header("Location: $redirect");
		exit();
	}else{
		$urlResult = $cartweaver->getQtyAdded();
	}
}

/* After adding an item to cart, "GoTo" Target page or "Comfirm". This is a Cartweaver setting in CWGlobalSettings.php page. */
if($cartweaver->settings->onSubmitAction == "GoTo" && !$cartweaver->getCWError() && isset($_GET["result"])) {
	$stockAlert = isset($_GET["stockAlert"]) ? $_GET["stockAlert"] : "";
	header("Location: " . $cartweaver->settings->targetGoToCart . "?result=" . $cartweaver->getQtyAdded() . "&stockAlert=" . $stockAlert . "&returnurl=" . urlencode($cartweaver->thisPage . "?prodId=" . $productId ));
	exit();
}
/* ============= ///  [ END ADD TO CART ] /// =================== */


/* ==============///  [ DISPLAY ITEM ] ///=================== */
/* Get Product Data */
$query_rsCWGetProduct = sprintf("SELECT product_ID,
product_Name,
product_Description
FROM
tbl_products
WHERE product_ID = %s 
AND product_Archive = 0
AND product_OnWeb = 1", $productId);
$rsCWGetProduct = $cartweaver->db->executeQuery($query_rsCWGetProduct);
$rsCWGetProduct_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetProduct = $cartweaver->db->db_fetch_assoc($rsCWGetProduct);

$query_rsCWGetProductImage = "SELECT prdctImage_FileName
FROM tbl_prdtimages
WHERE prdctImage_ProductID = " . $productId  .
" AND prdctImage_ImgTypeID = 2";
$rsCWGetProductImage = $cartweaver->db->executeQuery($query_rsCWGetProductImage);
$rsCWGetProductImage_recordCount = $cartweaver->db->recordCount;
$row_rsCWGetProductImage = $cartweaver->db->db_fetch_assoc($rsCWGetProductImage);

if ($_SESSION["showupsell"] == 1){
	/* Get a list of Up Sell products */
	$query_rsCWGetupsell = "SELECT product_MerchantProductID, product_Name, upsell_id, upsell_ProdId, upsell_relProdId
	FROM tbl_products, tbl_prdtupsell
	WHERE upsell_relProdId = product_ID
	AND upsell_ProdId = " . $productId .
	" AND product_Archive = 0
	AND product_OnWeb = 1";
	$rsCWGetupsell = $cartweaver->db->executeQuery($query_rsCWGetupsell);
	$rsCWGetupsell_recordCount = $cartweaver->db->recordCount;
	$row_rsCWGetupsell = $cartweaver->db->db_fetch_assoc($rsCWGetupsell);
}

/* Variables for manipulating product images */
$imageRoot = $cartweaver->settings->imageLargeFolder;
$imagePath = "";
$imageSRC = "";

/* ======================================================= */ 
?>
