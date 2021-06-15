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
Name:  CWFunPriceList.php
Description: This file gets and displays product pricing based 
on the number of SKU and Product options related to the product. 
It is called by the CWIncDetails.php file 

The function takes 3 arguments.
$productID: The product ID to display.
$allowBackOrders: preference setting for whether to allow back orders
$db: the cartweaver database connection
================================================================
*/
function getPriceList($productID, $allowBackOrders, $db) {
	$displayString = "";
	// Check to see how many options this product has 
	
	$query_rsCWOptionCount = "SELECT Count(optn_rel_ID) AS OptionCount 
	FROM tbl_prdtoption_rel
	WHERE optn_rel_Prod_ID = $productID";
	$rsCWOptionCount = $db->executeQuery($query_rsCWOptionCount);
	$rsCWOptionCount_recordCount = $db->recordCount;
	$row_rsCWOptionCount = $db->db_fetch_assoc($rsCWOptionCount);

	
	$query_rsCWPriceCount = "SELECT DISTINCT SKU_Price 
	FROM tbl_skus
	WHERE SKU_ProductID = $productID AND SKU_ShowWeb = 1";
	$rsCWPriceCount = $db->executeQuery($query_rsCWPriceCount);
	$rsCWPriceCount_recordCount = $db->recordCount;
	$row_rsCWPriceCount = $db->db_fetch_assoc($rsCWPriceCount);
	
	$numOptions = $row_rsCWOptionCount["OptionCount"];
	if($numOptions != 0 && $rsCWPriceCount_recordCount > 1){
		if($numOptions < 3) {
			$sku_stock = ($allowBackOrders == 0) ? " AND s.SKU_Stock > 0 " : "";
			/*   Get price list   */ 
			$query_rsCWGetPricing = "SELECT s.sku_id, 
			s.SKU_Price, 
			so.option_Name, 
			ot.optiontype_Name
			FROM tbl_products p 
			INNER JOIN tbl_skus s
			ON p.product_ID = s.SKU_ProductID
			INNER JOIN tbl_skuoption_rel sr
			ON s.SKU_ID = sr.optn_rel_SKU_ID
			INNER JOIN tbl_skuoptions so
			ON so.option_ID = sr.optn_rel_Option_ID
			INNER JOIN tbl_list_optiontypes ot 
			ON ot.optiontype_ID = so.option_Type_ID
			AND ot.optiontype_ID = so.option_Type_ID
			WHERE p.product_ID = $productID
			AND s.SKU_ShowWeb = 1
			$sku_stock
			ORDER BY s.SKU_Price, 
			s.SKU_Sort, 
			s.SKU_ID, 
			so.option_Sort,
			so.option_Name";
			$rsCWGetPricing = $db->executeQuery($query_rsCWGetPricing);
			$rsCWGetPricing_recordCount = $db->recordCount;
			$row_rsCWGetPricing = $db->db_fetch_assoc($rsCWGetPricing);
			$displayString = "<strong>Price List: </strong>";
			
			
			$lastTFM_nest = "";
			$currentRow = 1;
			$priceList = "";
			do {  
				$tfm_nest = $row_rsCWGetPricing["SKU_Price"];
				if ($lastTFM_nest != $tfm_nest) {
					$lastTFM_nest = $tfm_nest;
					if($currentRow != 1) {
						$displayString .= $priceList; 
					}
					$displayString .= "<br />";
					$displayString .= cartweaverMoney($row_rsCWGetPricing["SKU_Price"]);
					$priceList = "";
				} 
				if($currentRow++ % $numOptions != 0) {
					$priceList .= ", ";
				}else{
					$priceList .= "/";
				}
				$priceList .= $row_rsCWGetPricing["option_Name"];
				
			} while ($row_rsCWGetPricing = $db->db_fetch_assoc($rsCWGetPricing));
			$displayString .= $priceList;
		}/* END if numOptions < 3 */
	}else{
		$query_rsCWGetPricing = "SELECT SKU_Price, SKU_ProductID 
		FROM tbl_skus 
		WHERE SKU_ProductID = $productID AND SKU_ShowWeb = 1";
		$rsCWGetPricing = $db->executeQuery($query_rsCWGetPricing);
		$rsCWGetPricing_recordCount = $db->recordCount;
		$row_rsCWGetPricing = $db->db_fetch_assoc($rsCWGetPricing);
		$displayString = "<strong>Price: </strong>" . cartweaverMoney($row_rsCWGetPricing["SKU_Price"]);		
	} /* END numOptions != 0 */
	return $displayString;
}
?>