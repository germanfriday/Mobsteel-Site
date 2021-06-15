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
Name: CWProductAction.php
Description: All actions for the Product Form page are handled here.
================================================================
*/

/* =====  ADD PRODUCT and / or SKU  ===== */
/* [ START ] Add new Product */
if(isset($_POST["AddProduct"])) {
	$addProductError = array();

	$query_rsCWCheckProductID = "SELECT product_MerchantProductID 
	FROM tbl_products 
	WHERE product_MerchantProductID = '" . $_POST["product_MerchantProductID"] . "'";
	$rsCWCheckProductID = $cartweaver->db->executeQuery($query_rsCWCheckProductID);
	$rsCWCheckProductID_recordCount = $cartweaver->db->recordCount;
	$row_rsCWCheckProductID = $cartweaver->db->db_fetch_assoc($rsCWCheckProductID);
	if($rsCWCheckProductID_recordCount != 0) {
		array_push($addProductError,"Product already exists, please enter a new Product ID");
	}else{
		/* Set Default Values for form fields for insert */
		$_POST["product_MerchantProductID"] = isset($_POST["product_MerchantProductID"]) ? $_POST["product_MerchantProductID"] : "NULL";
		$_POST["product_Name"] = isset($_POST["product_Name"]) ? $_POST["product_Name"] : "NULL";
		$_POST["product_Description"] = isset($_POST["product_Description"]) ? $_POST["product_Description"] : "NULL";
		$_POST["product_ShortDescription"] = isset($_POST["product_ShortDescription"]) ? $_POST["product_ShortDescription"] : "NULL";
		$_POST["product_Sort"] = isset($_POST["product_Sort"]) ? $_POST["product_Sort"] : "0";
		$_POST["product_OnWeb"] = isset($_POST["product_OnWeb"]) ? $_POST["product_OnWeb"] : "NULL";
		$_POST["product_Archive"] = isset($_POST["product_Archive"]) ? $_POST["product_Archive"] : "0";
		$_POST["product_shipchrg"] = isset($_POST["product_shipchrg"]) ? $_POST["product_shipchrg"] : "1";
		$_POST["product_Category_ID"] = isset($_POST["product_Category_ID"]) ? $_POST["product_Category_ID"] : "1";

		$query_rsCW = sprintf("INSERT INTO tbl_products 
				(product_MerchantProductID, 
				product_Name, 
				product_Description, 
				product_ShortDescription,
				product_Sort, 
				product_OnWeb, 
				product_Archive, 
				product_shipchrg)
		VALUES ('%s','%s','%s','%s',%d,%d,%d,'%s'
			)",$_POST["product_MerchantProductID"]
			,$_POST["product_Name"]
			,$_POST["product_Description"]
			,$_POST["product_ShortDescription"]
			,$_POST["product_Sort"]
			,$_POST["product_OnWeb"]
			,$_POST["product_Archive"]
			,$_POST["product_shipchrg"]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
		
		/* Get our new autonumber ID for further inserts */
		$query_rsCWGetNewProdId = "SELECT product_ID 
		FROM tbl_products 
		WHERE product_MerchantProductID = '" . $_POST["product_MerchantProductID"] . "'";
		$rsCWGetNewProdId = $cartweaver->db->executeQuery($query_rsCWGetNewProdId);
		$rsCWGetNewProdId_recordCount = $cartweaver->db->recordCount;
		$row_rsCWGetNewProdId = $cartweaver->db->db_fetch_assoc($rsCWGetNewProdId);
		
		/* Add Category */
		if(isset($_POST["product_Category_ID"])){		
			for($i=0; $i < count($_POST["product_Category_ID"]); $i++) {
				$query_updtCats = sprintf("INSERT INTO tbl_prdtcat_rel 
				(prdt_cat_rel_Product_ID, prdt_cat_rel_Cat_ID)
				VALUES (%d, %d)",$row_rsCWGetNewProdId["product_ID"],$_POST["product_Category_ID"][$i]);
				$updtCats = $cartweaver->db->executeQuery($query_updtCats);
			}
		}
	
			/* Add Secondary Category */
		if(isset($_POST["scndctgry_ID"])){		
			for($i=0; $i < count($_POST["scndctgry_ID"]); $i++) {
				$query_updtScndCats = sprintf("INSERT INTO tbl_prdtscndcat_rel
						(prdt_scnd_rel_Product_ID, prdt_scnd_rel_ScndCat_ID )
						VALUES (%d, %d)",$row_rsCWGetNewProdId["product_ID"],$_POST["scndctgry_ID"][$i]);
				$updtScndCats = $cartweaver->db->executeQuery($query_updtScndCats);
			}
		}

			/* Add Selected Product Options */
		if(isset($_POST["product_options"])){		
			for($i=0; $i < count($_POST["product_options"]); $i++) {
				$query_updtProductOptions = sprintf("INSERT INTO tbl_prdtoption_rel
					(optn_rel_Prod_ID, optn_rel_OptionType_ID)
					VALUES (%d, %d)",$row_rsCWGetNewProdId["product_ID"],$_POST["product_options"][$i]);
				$updtProductOptions = $cartweaver->db->executeQuery($query_updtProductOptions);
			}
		}

			/* Add Image URLs */
		if($_POST["ImageFileName_L"] != "") {
			$query_rsCW = sprintf("INSERT INTO tbl_prdtimages 
							(prdctImage_ProductID,
								prdctImage_ImgTypeID,
								prdctImage_FileName,
								prdctImage_SortOrder)
					VALUES (%d,2,'%s',1)",$row_rsCWGetNewProdId["product_ID"],$_POST["ImageFileName_L"]);
			$rsCW = $cartweaver->db->executeQuery($query_rsCW);
		}

		if($_POST["ImageFileName_T"] != "") {				
			$query_rsCW = sprintf("INSERT INTO tbl_prdtimages 
							(prdctImage_ProductID,
								prdctImage_ImgTypeID,
								prdctImage_FileName,
								prdctImage_SortOrder)
					VALUES (%d,1,'%s',1)",$row_rsCWGetNewProdId["product_ID"],$_POST["ImageFileName_T"]);
			$rsCW = $cartweaver->db->executeQuery($query_rsCW);
		}
		
		header("Location: " . $cartweaver->thisLocation . "?product_ID=" . $row_rsCWGetNewProdId["product_ID"] . "&addsku=ADD#addsku"); 
		exit();
 	}/* END if(rsCheckProductID_recordCount != 0) { */
}/* END if(isset($_POST["AddProduct"])) { */
 
/* [ START ] Add new SKU  */
if (isset($_POST["addsku"])){
	/* Check to make sure the SKU_ID is unique */

	$query_rsCWCheckUniqueSKU = "SELECT SKU_MerchSKUID 
	FROM tbl_skus 
	WHERE SKU_MerchSKUID = '" . $_POST["SKU_MerchSKUID"] . "'";
	$rsCWCheckUniqueSKU = $cartweaver->db->executeQuery($query_rsCWCheckUniqueSKU);
	$rsCWCheckUniqueSKU_recordCount = $cartweaver->db->recordCount;
	$row_rsCWCheckUniqueSKU = $cartweaver->db->db_fetch_assoc($rsCWCheckUniqueSKU);

	if ($rsCWCheckUniqueSKU_recordCount != 0) {
		array_push($addSKUError,"SKU ID already exists. Please choose a different SKU ID.");
	}else{
		/* Set Default Form Values */
		$_POST["SKU_MerchSKUID"] = isset($_POST["SKU_MerchSKUID"]) ? $_POST["SKU_MerchSKUID"] : "NULL";
		$_POST["product_ID"] = isset($_POST["product_ID"]) ? $_POST["product_ID"] : "NULL";
		$_POST["SKU_Price"] = isset($_POST["SKU_Price"]) ? $_POST["SKU_Price"] : "NULL";
		$_POST["SKU_Weight"] = isset($_POST["SKU_Weight"]) ? $_POST["SKU_Weight"] : "0";
		$_POST["SKU_Stock"] = isset($_POST["SKU_Stock"]) ? $_POST["SKU_Stock"] : "0";
		$_POST["SKU_ShowWeb"] = isset($_POST["SKU_ShowWeb"]) ? $_POST["SKU_ShowWeb"] : "NULL";
		$_POST["SKU_Sort"] = isset($_POST["SKU_Sort"]) ? $_POST["SKU_Sort"] : "0";

		$query_rsCW = sprintf("INSERT INTO tbl_skus (SKU_MerchSKUID, SKU_ProductID, SKU_Price, SKU_Weight, SKU_Stock,
			SKU_ShowWeb, SKU_Sort) VALUES (
				'%s','%s','%s','%s','%s',%d,%d)",$_POST["SKU_MerchSKUID"],
				$_POST["product_ID"],
				$_POST["SKU_Price"],
				$_POST["SKU_Weight"],
				$_POST["SKU_Stock"],
				$_POST["SKU_ShowWeb"],
				$_POST["SKU_Sort"]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
		
		/* Get the autonumber to use for all further inserts */

		$query_rsCWNewSKUID = "SELECT SKU_ID 
		FROM tbl_skus 
		WHERE SKU_MerchSKUID = '" . $_POST["SKU_MerchSKUID"] . "'";
		$rsCWNewSKUID = $cartweaver->db->executeQuery($query_rsCWNewSKUID);
		$rsCWNewSKUID_recordCount = $cartweaver->db->recordCount;
		$row_rsCWNewSKUID = $cartweaver->db->db_fetch_assoc($rsCWNewSKUID);
	
		$newSKUID = $row_rsCWNewSKUID["SKU_ID"];
		/* Add SKU Options */
		/* Loop through the form collection and grab all of the chosen options */
		while (list($key,$val) = each($_POST)){
			if (substr($key, 0, 9) == "selOption" && $val != "choose"){
				$query_updtProductOptions = "INSERT INTO tbl_skuoption_rel
				(optn_rel_sku_id, optn_rel_Option_ID)
				VALUES ($newSKUID, $val)";
				$updtProductOptions = $cartweaver->db->executeQuery($query_updtProductOptions);
			}
		}
		header("Location: " . $cartweaver->thisLocation . "?product_ID=" . $_GET["product_ID"] . "&addsku=ADD#addsku"); 
		exit();
	}/* END if ($rsCWCheckUniqueSKU_recordCount != 0) */
}/* END if (isset($_POST["addsku"])){ */
/* [ END ] Add new SKU  */

/* =====  DELETE PRODUCT and associated SKUs ===== */
 
/* [ START ] Delete PRODUCT */

if (isset($_POST["DeleteProduct"])){	
	/* Get any product SKUs */	
	$query_getSKUs = "SELECT SKU_ID
		FROM tbl_skus
		WHERE SKU_ProductID = " . $_POST["product_ID"];
	$getSKUs = $cartweaver->db->executeQuery($query_getSKUs);
	$getSKUs_recordCount = $cartweaver->db->recordCount;
	$row_getSKUs = $cartweaver->db->db_fetch_assoc($getSKUs);

	/* If we have skus, delete them */
	if($getSKUs_recordCount != 0) {
		$skuList = $cartweaver->db->valueList($getSKUs, "SKU_ID");
		/* Delete all related product skus */
		/* Delete options */
		$query_rsCW = "DELETE FROM tbl_skuoption_rel 
					WHERE optn_rel_sku_id IN ($skuList)";
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);

		/* Delete SKU */
		$query_rsCW = "DELETE FROM tbl_skus 
					WHERE SKU_ID IN ($skuList)";
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	} /* END if($getSKUs_recordCount != 0) { */
	
	/* Delete Product Option Information */
	$query_rsCW = "DELETE FROM tbl_prdtoption_rel
		WHERE optn_rel_Prod_ID = " . $_POST["product_ID"];
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	
	/* Delete current category Information */
	$query_rsCW = "DELETE FROM tbl_prdtcat_rel 
			WHERE prdt_cat_rel_Product_ID = " . $_POST["product_ID"];
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	
	/* Delete Product Secondary Category Information */	
	$query_rsCW = "DELETE FROM tbl_prdtscndcat_rel
		WHERE prdt_scnd_rel_Product_ID = " . $_POST["product_ID"];
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);

	/* Delete Product Image Information */
	$query_rsCW = "DELETE FROM tbl_prdtimages
		WHERE prdctImage_ProductID = " . $_POST["product_ID"];
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	
	/* Delete Product Up Sell Records*/
	$query_rsCW = "DELETE FROM  tbl_prdtupsell
		WHERE upsell_ProdId = " . $_POST["product_ID"];
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);

	/* Delete Product */
	$query_rsCW = "DELETE FROM tbl_products 
		WHERE product_ID = " . $_POST["product_ID"];
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);

	/* Go to Product List */
	header("Location: ProductActive.php"); 
	exit();
}/* [ END ] if (isset($_POST["DeleteProduct"])){	Delete PRODUCT */


/* =====  DELETE SKU  ===== */
 
 /* [ START ] Delete SKU  */
if(isset($_GET["delete_sku_id"])){
	/* First, see if it is in use */
	$query_checkSKUuse = "SELECT orderSKU_SKU
			FROM tbl_orderskus
			WHERE orderSKU_SKU = " . $_GET["delete_sku_id"];
	$checkSKUuse = $cartweaver->db->executeQuery($query_checkSKUuse);
	$checkSKUuse_recordCount = $cartweaver->db->recordCount;
	$row_checkSKUuse = $cartweaver->db->db_fetch_assoc($checkSKUuse);

	/* If NOT - Delete it, If it IS - Set Error Message */
	if($checkSKUuse_recordCount == "0"){
		/* Delete Size and color */
		$query_rsCW = "DELETE FROM tbl_skuoption_rel 
					WHERE optn_rel_sku_id = " . $_GET["delete_sku_id"];
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
		
		/* Delete SKU */		
		$query_rsCW = "DELETE FROM tbl_skus 
					WHERE SKU_ID = " . $_GET["delete_sku_id"];
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
		header("Location: ProductForm.php?product_ID=" . $_GET["product_ID"]); 
		exit();
	}else{
		$cantDeleteSKU = "Cannot delete this SKU. It is included in order records. You may set it to NOT appear on the web if you wish.";
	}
}
/* [ END ] Delete SKU  */



/* ===== UPDATE PRODUCT ===== */
/* [ START ] Update Existing Product */
if(isset($_POST["UpdateProduct"])){
	/* Set Default FORM values */
	$_POST["product_Description"] = isset($_POST["product_Description"]) ? $_POST["product_Description"] : "NULL";
	$_POST["product_ShortDescription"] = isset($_POST["product_ShortDescription"]) ? $_POST["product_ShortDescription"] : "NULL";
	$_POST["product_Sort"] = isset($_POST["product_Sort"]) ? $_POST["product_Sort"] : "0";
	$_POST["product_OnWeb"] = isset($_POST["product_OnWeb"]) ? $_POST["product_OnWeb"] : "NULL";
	$_POST["product_Archive"] = isset($_POST["product_Archive"]) ? $_POST["product_Archive"] : "0";
	$_POST["product_shipchrg"] = isset($_POST["product_shipchrg"]) ? $_POST["product_shipchrg"] : "1";
	$_POST["product_Category_ID"] = isset($_POST["product_Category_ID"]) ? $_POST["product_Category_ID"] : "1";
		
	$query_rsCW = sprintf("UPDATE tbl_products 
		SET 
			product_Name='%s', 
			product_Description = '%s',
			product_ShortDescription = '%s', 
			product_Sort = %s, 
			product_OnWeb = %s, 
			product_Archive = '%s', 
			product_shipchrg = '%s'
		WHERE product_ID=%s"
		,$_POST["product_Name"]
		,$_POST["product_Description"]
		,$_POST["product_ShortDescription"]
		,$_POST["product_Sort"]
		,$_POST["product_OnWeb"]
		,$_POST["product_Archive"]
		,$_POST["product_shipchrg"]
		,$_POST["product_ID"]);
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	/* Update Thumbnail Images */
	if(isset($_POST["ImageFileName_T"]) && $_POST["ImageFileName_T"] == "" && $_POST["ImageID_T"] != ""){
		$query_rsCW = "DELETE FROM tbl_prdtimages 
		WHERE prdctImage_ID = " . $_POST["ImageID_T"];
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);

	}elseif(isset($_POST["ImageFileName_T"]) && $_POST["ImageFileName_T"] != "" && $_POST["ImageID_T"] != "") {
		$query_rsCW = sprintf("UPDATE tbl_prdtimages
		SET prdctImage_FileName = '%s' 
		WHERE prdctImage_ID = %s", $_POST["ImageFileName_T"], $_POST["ImageID_T"]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);

	}elseif(isset($_POST["ImageFileName_T"]) && $_POST["ImageFileName_T"] != "" && $_POST["ImageID_T"] == "") {
		$query_rsCW = sprintf("INSERT INTO tbl_prdtimages 
			(prdctImage_ProductID,
				prdctImage_ImgTypeID,
				prdctImage_FileName,
				prdctImage_SortOrder)
			VALUES (%s,	1,'%s',	1)",$_POST["product_ID"],$_POST["ImageFileName_T"]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);	
	}
	
	/* Update Fullsize Images */
	if(isset($_POST["ImageFileName_L"]) && $_POST["ImageFileName_L"] == "" && $_POST["ImageID_L"] != "") {
		$query_rsCW = "DELETE FROM tbl_prdtimages 
		WHERE prdctImage_ID = " . $_POST["ImageID_L"];
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);

	}elseif(isset($_POST["ImageFileName_L"]) && $_POST["ImageFileName_L"] != "" && $_POST["ImageID_L"] != "") {
		$query_rsCW = sprintf("UPDATE tbl_prdtimages
					SET prdctImage_FileName = '%s' 
					WHERE prdctImage_ID = %s", $_POST["ImageFileName_L"],$_POST["ImageID_L"]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);

	}elseif(isset($_POST["ImageFileName_L"]) && $_POST["ImageFileName_L"] != "" && $_POST["ImageID_L"] == "") {
		$query_rsCW = sprintf("INSERT INTO tbl_prdtimages 
				(prdctImage_ProductID,
					prdctImage_ImgTypeID,
					prdctImage_FileName,
					prdctImage_SortOrder)
			VALUES (%s,2,'%s',	1)",$_POST["product_ID"],$_POST["ImageFileName_L"]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	}

	/* Add Category/s */  
	/* Delete current category Relationships */

	$query_rsCW = "DELETE FROM tbl_prdtcat_rel 
			WHERE prdt_cat_rel_Product_ID = " . $_POST["product_ID"];
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);

	/*  INSERT the new ones  */
	if(!isset($_POST["product_Category_ID"])){
		$_POST["product_Category_ID"] = array('1');
	}

	for($i=0; $i < count($_POST["product_Category_ID"]); $i++) {
		$query_updtCats = sprintf("INSERT INTO tbl_prdtcat_rel 
		(prdt_cat_rel_Product_ID, prdt_cat_rel_Cat_ID)
		VALUES (%d, %d)",$_POST["product_ID"],$_POST["product_Category_ID"][$i]);
		$updtCats = $cartweaver->db->executeQuery($query_updtCats);
	}
	
	/* Add Secondary Category/s */  
	/* Delete current Subcategory Relationships */

	$query_rsCW = "DELETE FROM tbl_prdtscndcat_rel 
			WHERE prdt_scnd_rel_Product_ID = " . $_POST["product_ID"];
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);

	/*  INSERT the new ones  */
	if(!isset($_POST["scndctgry_ID"])){
		$_POST["scndctgry_ID"] = array('1');
	}

	for($i=0; $i < count($_POST["scndctgry_ID"]); $i++) {
		$query_updtScndCats = sprintf("INSERT INTO tbl_prdtscndcat_rel
				(prdt_scnd_rel_Product_ID, prdt_scnd_rel_ScndCat_ID )
				VALUES (%s, %s)",$_POST["product_ID"],$_POST["scndctgry_ID"][$i]);
		$updtScndCats = $cartweaver->db->executeQuery($query_updtScndCats);
	}

	
 	if( $_POST["HasOrders"] != 1){
		if(isset($_POST["product_options"])){
			$productOptions = implode(",",$_POST["product_options"]);
			// Get options
			$query_rsCW = sprintf("SELECT r.optn_rel_ID
					FROM tbl_skuoptions o
					INNER JOIN tbl_skuoption_rel r
					ON o.option_ID = r.optn_rel_Option_ID
					INNER JOIN tbl_skus s						
					ON s.SKU_ID = r.optn_rel_SKU_ID
					WHERE s.SKU_ProductID = %s
					AND o.option_Type_ID IN (%s)",$_POST["product_ID"] ,$productOptions);
			$rsCW = $cartweaver->db->executeQuery($query_rsCW);
			$skuoptions = $cartweaver->db->valueList($rsCW, "optn_rel_ID");
			// Get sku_id
			$query_rsCW = sprintf("SELECT SKU_ID 
			FROM tbl_skus 
			WHERE SKU_ProductID = %s",$_POST["product_ID"]);
			$rsCW = $cartweaver->db->executeQuery($query_rsCW);
			$skuids = $cartweaver->db->valueList($rsCW, "SKU_ID");
			
			
		/* If we've removed a product option, remove the related sku options */
			$query_rsCW = sprintf("DELETE FROM tbl_skuoption_rel 
				WHERE optn_rel_ID NOT IN(%s)
				AND optn_rel_SKU_ID IN (%s)",$skuoptions, $skuids);
			$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	
			/* Delete Product Option Information */
	
			$query_rsCW = "DELETE FROM tbl_prdtoption_rel
			WHERE optn_rel_Prod_ID = " . $_POST["product_ID"];
			$rsCW = $cartweaver->db->executeQuery($query_rsCW);
		
			/* Add Selected Product Options */
			for($i=0; $i < count($_POST["product_options"]); $i++) {
				$query_updtProductOptions = sprintf("INSERT INTO tbl_prdtoption_rel
					(optn_rel_Prod_ID, optn_rel_OptionType_ID)
					VALUES (%s, %s)",$_POST["product_ID"],$_POST["product_options"][$i]);
				$updtProductOptions = $cartweaver->db->executeQuery($query_updtProductOptions);
			}

		}
	
	} /* END IF - $_POST["HasOrders"] NEQ 1 */	
	header("Location: " . $cartweaver->thisLocation . "?product_ID=" . $_GET["product_ID"]); 
	exit();
}
/* [ END ] Update Existing Product */



/* =====  UPDATE SKU ===== */
/* [ START ] Update SKU */
if(isset($_POST["UpdateSKU"])){
	/* Set Default FORM values */
	$_POST["SKU_Stock"] = isset($_POST["SKU_Stock"]) ? $_POST["SKU_Stock"] : "0";
	$_POST["SKU_ShowWeb"] = isset($_POST["SKU_ShowWeb"]) ? $_POST["SKU_ShowWeb"] : "NULL";
	$_POST["SKU_Sort"] = isset($_POST["SKU_Sort"]) ? $_POST["SKU_Sort"] : "0";
	
	$query_rsCW = sprintf("UPDATE tbl_skus 
		SET 
			SKU_ProductID='%s', 
			SKU_Price=%s, 
			SKU_Weight=%s, 
			SKU_Stock= %s, 
			SKU_ShowWeb= %s, 
			SKU_Sort= %s
		WHERE SKU_ID=%s"
		,$_POST["SKU_ProductID"]
		,$_POST["SKU_Price"]
		,$_POST["SKU_Weight"]
		,$_POST["SKU_Stock"]
		,$_POST["SKU_ShowWeb"]
		,$_POST["SKU_Sort"]
		,$_POST["sku_id"]);
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);

	/* DELETE current options */ 

	$query_rsCW = "DELETE FROM tbl_skuoption_rel 
		WHERE optn_rel_sku_id = " . $_POST["sku_id"];
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);

	/*  INSERT the new relationships  */
	/* Loop throught the form collection and grab all of the chosen options */
	$strOptions = "";
	while (list($key,$val) = each($_POST)){
		if (substr($key, 0, 9) == "selOption" && $val != "choose"){
			$query_updtSKUOptions = sprintf("INSERT INTO tbl_skuoption_rel
			(optn_rel_sku_id, optn_rel_Option_ID)
			VALUES (%s, %s)",$_POST["sku_id"],$val);
			$updtSKUOptions = $cartweaver->db->executeQuery($query_updtSKUOptions);
		}
	}
	
	header("Location: " . $cartweaver->thisLocation . "?product_ID=" . $_GET["product_ID"]); 
	exit();
}
/* [ END ] Update SKU */

/* ===== ARCHIVE PRODUCT ===== */
/* [ START ] Archive Product */

if(isset($_POST["ArchiveProduct"])){
	$query_archiveProduct = "UPDATE tbl_products
			SET product_Archive = '1'
			WHERE product_ID=" . $_POST["product_ID"];
	$archiveProduct = $cartweaver->db->executeQuery($query_archiveProduct);
	header("Location: ProductActive.php?status=1"); 
	exit();
}



/* Up SELL - Queries and actions for Cross Selling Produts */
/* ADD Up Sell Action */
if(isset($_POST["ADDUpsell"])){
	/* Check for duplicate records */
	$query_checkfordupes = sprintf("SELECT upsell_id
			FROM tbl_prdtupsell
			WHERE upsell_ProdId = %s
			AND upsell_relProdId = '%s'", $_POST["product_ID"],$_POST["UpSellProduct_ID"] );
	$checkfordupes = $cartweaver->db->executeQuery($query_checkfordupes);
	$checkfordupes_recordCount = $cartweaver->db->recordCount;
	$row_checkfordupes = $cartweaver->db->db_fetch_assoc($checkfordupes);

	/* If no duplicates, proceed */
	if($checkfordupes_recordCount == 0){
		$query_rsCW = sprintf("INSERT INTO tbl_prdtupsell (upsell_ProdId, upsell_relProdId) 
					VALUES (%s,'%s')",$_POST["product_ID"],$_POST["UpSellProduct_ID"]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	}else{
		/* If a duplicate throw an error */
		$query_checkfordupes = "SELECT product_Name
		FROM tbl_products
		WHERE product_ID = '" . $_POST["UpSellProduct_ID"] . "'";
		$checkfordupes = $cartweaver->db->executeQuery($query_checkfordupes);
		$checkfordupes_recordCount = $cartweaver->db->recordCount;
		$row_checkfordupes = $cartweaver->db->db_fetch_assoc($checkfordupes);			
		$upsellProductIDError = " <strong>- " . $row_checkfordupes["product_Name"] . " -</strong> is allready associated <br>with this Product. Record not added.";
	}  
}

/* DELETE Cross Sell Record */
if (isset($_GET["delupsell_id"])) {
	$query_rsCW = "DELETE FROM tbl_prdtupsell WHERE upsell_id = " . $_GET["delupsell_id"];
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);
}

if (isset($_POST["product_ID"])) {
	$_GET["product_ID"] = $_POST["product_ID"];
}

/* ===== END ===== */
?>