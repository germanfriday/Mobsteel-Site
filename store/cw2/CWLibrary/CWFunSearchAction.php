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

Cartweaver Version: 2.2  -  Date: 09/07/2005
================================================================
Name: CWFunSearchAction.php
Description: Provides search queries based on type of search.

function syntax:
===========================================================
*/

function cwSearchAction(	$category //= $urlcategory
							, $secondary //= $urlsecondary
							, $keywords //= $urlkeywords
							, $allowBackOrders) {
	/* ------------------   END   Set Default Attributes  -----------------------------   */
	/* ================================================================================== */
	
	if($keywords == "Enter Keywords"){
		$keywords = "";
	}
	
	
	$rsCWGetResults_query = "
	SELECT DISTINCT	P.product_ID
	FROM tbl_products P
	INNER JOIN tbl_skus S
	ON P.product_ID = S.SKU_ProductID ";

	if($secondary != 0) {
		$rsCWGetResults_query .= "		
			LEFT JOIN tbl_prdtscndcat_rel C
			ON P.product_ID = C.prdt_scnd_rel_Product_ID 
			";
	}
	if($category != 0) {
		$rsCWGetResults_query .= "		
			LEFT JOIN tbl_prdtcat_rel PC
			ON P.product_ID = PC.prdt_cat_rel_Product_ID 
			";
	}
	
	$rsCWGetResults_query .= " WHERE 
		P.product_OnWeb = 1
		AND P.product_Archive = 0";
		
	if($allowBackOrders == 0) {
		$rsCWGetResults_query .= " AND S.SKU_Stock > 0";
	}
		
	if($keywords != "") {
		$rsCWGetResults_query .= " AND (1=0 ";
		$keywordsArray = split(",",$keywords);
			foreach($keywordsArray AS $i) {
				$rsCWGetResults_query .= " OR P.product_Name LIKE '%$i%'";
			}
			foreach($keywordsArray AS $i) {
				$rsCWGetResults_query .= " OR P.product_ShortDescription LIKE '%$i%'";
			}
			foreach($keywordsArray AS $i) {
				$rsCWGetResults_query .= " OR P.product_Description LIKE '%$i%'";
			}
		$rsCWGetResults_query .= ")";
	}	

	if($category != 0) {
		$rsCWGetResults_query .= " AND PC.prdt_cat_rel_Cat_ID = " . $category;
	}
	if($secondary != 0) {
		$rsCWGetResults_query .= " AND C.prdt_scnd_rel_ScndCat_ID = " . $secondary;
	}
	/* return query results to calling page */
	return $rsCWGetResults_query;
}
?>