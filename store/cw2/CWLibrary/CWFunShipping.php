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

Cartweaver Version: 2.3  -  Date: 09/18/2005
================================================================
Name:  CWFunShipping.php
Description: Calculate shipping - 
There are three possible variables used for calculating shipping; 
a base shipping fee, shipping by weight range, and a shipping 
extension by location.  Shipping charges can be a result of any 
one of there or a combination. This criteria is set in the admin 
section on the Company Information Page. 
================================================================
............................................................................
Attributes: 
    shiptype = "localcalc"  -  ( Default )
		localcalc = Calculate shipping localy using shipping data in database
		This is the only option available at this time.
		
		mode = "ShipList" - (Default)
			ShipList - Return a query (rsGetShipMethods) with a list of valid shipping
				methods. Return $_SESSION["shipPref"] with the chose valid shipping
				method
			Calculation - Perform the shipping calculation and set $_SESSION["shipTotal"]
				to the final shipping total for the customer.
............................................................................
*/

function cwShipping($shipPref, $cartID, $cartWeightTotal, $shipBase, $shipExtension, $shipByWeight) {
	global $cartweaver;	
	/* Perform local shipping calculation */
	if ($shipPref == 0) {
		/* Customer has not made a valid shipping selection, so don't calculate totals */
		$shipTotal = 0;
	}else{
		/* Get all customer's products that charge shipping. */
		$query_rsCWCheckShipCharge = "SELECT P.product_shipchrg 
		FROM tbl_cart C
		INNER JOIN tbl_skus S 
		ON C.cart_sku_ID = S.SKU_ID
		INNER JOIN tbl_products P
		ON P.product_ID = S.SKU_ProductID
		WHERE C.cart_custcart_ID = '" . $cartID ."' 
		AND P.product_shipchrg = 1";
		$rsCWCheckShipCharge = $cartweaver->db->executeQuery($query_rsCWCheckShipCharge);
		$rsCWCheckShipCharge_recordCount = $cartweaver->db->recordCount;
		$row_rsCWCheckShipCharge = $cartweaver->db->db_fetch_assoc($rsCWCheckShipCharge);
		
		/* Set default values for base and weight charges */
		$baseShipRate = "0";
		$weightRangeRate = "0";

		/* If we have products that require shipping */
		if($rsCWCheckShipCharge_recordCount != 0){
			/* Get the base shipping rate for the users shipping preference */
			if ($shipBase == 1){
				$query_rsCWGetShipRate = "SELECT shipmeth_Rate
				, shipmeth_ID
				, shipmeth_Name
				FROM tbl_shipmethod
				WHERE shipmeth_ID = $shipPref 
				AND shipmeth_archive = 0";
				$rsCWGetShipRate = $cartweaver->db->executeQuery($query_rsCWGetShipRate);
				$rsCWGetShipRate_recordCount = $cartweaver->db->recordCount;
				$row_rsCWGetShipRate = $cartweaver->db->db_fetch_assoc($rsCWGetShipRate);
				if ($rsCWGetShipRate_recordCount != 0){
					$baseShipRate = $row_rsCWGetShipRate["shipmeth_Rate"];
				}
			}/* if $shipBase == 1 */

			/* If Weight Range Rate rate is being charged */
			if($shipByWeight == 1){
				$query_rsCWGetWeightRate = "SELECT ship_weightrange_Amount
				FROM tbl_shipweights
				WHERE ship_weightrange_Method_ID = " . $shipPref . "
				AND ship_weightrange_From <= " . $cartWeightTotal . "
				AND ship_weightrange_To >= " . $cartWeightTotal;
				$rsCWGetWeightRate = $cartweaver->db->executeQuery($query_rsCWGetWeightRate);
				$rsCWGetWeightRate_recordCount = $cartweaver->db->recordCount;
				$row_rsCWGetWeightRate = $cartweaver->db->db_fetch_assoc($rsCWGetWeightRate);

				/* If the selected shipping rate doesn't have the correct weight range, choose the first that does */
				if ($rsCWGetWeightRate_recordCount != 0){
					$weightRangeRate = $row_rsCWGetWeightRate["ship_weightrange_Amount"];
				}
			}/* shipByWeight == 1 */

			/* Set Shipping sub total prior to Extension calculation */
			$shipSubTotal = 0;
			$shipSubTotal =  $shipSubTotal + ($baseShipRate + $weightRangeRate);
			/* Calculate Shipping Total */
			/* If the shipping extension is to be factored in do so, otherwise Tally shipping without it. */
			if ($shipExtension == 1){
				$shipSubTotal = $shipSubTotal + ($shipSubTotal * $shipExtension);
			}
			$shipTotal = $shipSubTotal;
		}else{ /* rsCWCheckShipCharge.recordcount EQ 0 */
			$shipTotal = 0;
		} /* rsCWCheckShipCharge.recordcount NEQ 0 */
	}/* $_SESSION["shipPref"] == 0 */
	return $shipTotal;
}

/* If shipping is enabled */
if($_SESSION["enableShipping"] == "1"){
	if($_SESSION["chargeShipByWeight"] == 0) {
		$query_rsCWShippingMethods = "SELECT s.shipmeth_ID, 
		s.shipmeth_Name, 
		s.shipmeth_Rate, 
		s.shipmeth_Sort, 
		s.shipmeth_Archive, 
		r.shpmet_cntry_ID, 
		r.shpmet_cntry_Meth_ID, 
		r.shpmet_cntry_Country_ID
		FROM tbl_shipmethod s, 
		tbl_shipmethcntry_rel r
		WHERE shpmet_cntry_Country_ID = " . $_SESSION["shipToCountryID"] . "
		AND shpmet_cntry_Meth_ID = shipmeth_ID 
		AND shipmeth_archive = 0 
		ORDER BY shipmeth_Sort ASC";
		$rsCWShippingMethods = $cartweaver->db->executeQuery($query_rsCWShippingMethods);
		$rsCWShippingMethods_recordCount = $cartweaver->db->recordCount;
		$row_rsCWShippingMethods = $cartweaver->db->db_fetch_assoc($rsCWShippingMethods);				
	}else{/* if($shipByWeight == 0) */
		/* If you're charging by weight, then only show those shipping methods that are available
		for the weight in the cart */
		$query_rsCWShippingMethods = "SELECT Min(W.ship_weightrange_From) AS MinOfship_weightrange_From
		, Max(W.ship_weightrange_To) AS MaxOfship_weightrange_To
		, M.shipmeth_ID
		, M.shipmeth_Name
		, C.shpmet_cntry_Country_ID
		, M.shipmeth_Sort
		FROM (tbl_shipmethcntry_rel C
		LEFT JOIN tbl_shipmethod M ON C.shpmet_cntry_Meth_ID = M.shipmeth_ID) 
		LEFT JOIN tbl_shipweights W ON M.shipmeth_ID = W.ship_weightrange_Method_ID 
		WHERE (M.shipmeth_Archive = 0)
		AND (C.shpmet_cntry_Country_ID = " . $_SESSION["shipToCountryID"] . ")
		GROUP BY M.shipmeth_ID
		, M.shipmeth_Name
		, C.shpmet_cntry_Country_ID
		, M.shipmeth_Sort
		, M.shipmeth_Archive
		HAVING (Min(W.ship_weightrange_From) <= " . $_SESSION["cartWeightTotal"] . ")
		AND (Max(W.ship_weightrange_To) >= " . $_SESSION["cartWeightTotal"] . ")
		OR MAX(W.ship_weightrange_To) IS NULL
		ORDER BY M.shipmeth_Sort";
		$rsCWShippingMethods = $cartweaver->db->executeQuery($query_rsCWShippingMethods);
		$rsCWShippingMethods_recordCount = $cartweaver->db->recordCount;
		$row_rsCWShippingMethods = $cartweaver->db->db_fetch_assoc($rsCWShippingMethods);
	} /* if($shipByWeight == 0) */
	if($rsCWShippingMethods_recordCount == 0) {
		/* There are no shipping methods for the user's locale */
		/* Set the shipping preference to 0 for use on CWIncShowCart.php */
		$_SESSION["shipPref"] = 0;
	}else{
		/* 
	 rsCWShippingMethods_recordCount != 0
		We have at least one shipping method	*/
		
		/* Since we've already got our shipping list, go ahead and set the user's current selection. */
		/* If the currently selected ship preference is no longer valid, set the default. */
		$shipList =  $cartweaver->db->queryOfQuery($rsCWShippingMethods, array("shipmeth_ID"));
		if($_SESSION["shipPref"] == 0 && count($shipList > 0)){
			/* Set default for the current ship-to country */
			$_SESSION["shipPref"] = $shipList[0]["shipmeth_ID"];
		}
		$_SESSION["shipTotal"] = cwShipping($_SESSION["shipPref"]
			,$_SESSION["CartId"]
			,$_SESSION["cartWeightTotal"]
			,$_SESSION["chargeShipBase"]
			,$_SESSION["shipExtension"]
			,$_SESSION["chargeShipByWeight"]);
	} /*      if($rsCWShippingMethods_recordCount == 0) {*/

}else{
	/* shipEnabled = 0 */
	$_SESSION["shipPref"] = 0;
	$_SESSION["shipTotal"] = 0;
}
?>