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
Name: CWFunProductOptions.php
Description:
	Use the CWProductOptions include file to add cross tab tables
	and list menus for individual product options. If a product
	has more than 2 options, a crosstab table is displayed with
	every possible combination of options, making it easy for a
	customer to find what they're looking for. If 2 options are
	available, and you've set the Advanced Display option, then
	the two list menus will be dependent on each other, making
	sure the user can't select an invalid combination. If you are
	not using Advanced Display, then the list menus will not be
	dependent on each other, and all checking will be handled once
	the page is submitted. If there	is only one option, then just
	the prices are displayed.
	
	The function parameter is $productId: The product ID to display.
	If not passed to the function, the current product id will be 
	displayed.

================================================================
*/
function cwProductOptions($productId) {
	global $cartweaver;
	$numOptions = 0; // set the default option number
	$lastTFM_nest = ""; // for nested regions
	$intQuantity = 0; // initialize the quantity
	
	/* Retrieve Associated SKU data */ 
	$sku_stock = ($_SESSION["AllowBackOrders"] == 0) ? " AND S.SKU_Stock > 0 " : "";
	$query_rsCWGetSKUs = "SELECT S.SKU_ID
	, S.SKU_MerchSKUID
	, S.SKU_Price
	, S.SKU_ProductID 
	FROM tbl_skus S
	WHERE S.SKU_ProductID = " . $productId  .
	" AND S.SKU_ShowWeb = 1 $sku_stock
	ORDER BY S.SKU_Sort";
	$rsCWGetSKUs = $cartweaver->db->executeQuery($query_rsCWGetSKUs);
	$rsCWGetSKUs_recordCount = $cartweaver->db->recordCount;
	$row_rsCWGetSKUs = $cartweaver->db->db_fetch_assoc($rsCWGetSKUs);
	$skuid = $row_rsCWGetSKUs["SKU_ID"];
	/* Get number of option types */
	$query_rsCWGetProductOptions = "SELECT DISTINCT O.optn_rel_OptionType_ID 
	FROM tbl_products P
	INNER JOIN tbl_prdtoption_rel O 
	ON P.product_ID = O.optn_rel_Prod_ID 
	WHERE O.optn_rel_Prod_ID = " . $productId .
	"  AND P.product_Archive = 0";
	$rsCWGetProductOptions = $cartweaver->db->executeQuery($query_rsCWGetProductOptions);
	$rsCWGetProductOptions_recordCount = $cartweaver->db->recordCount;
	//$row_rsCWGetProductOptions = $cartweaver->db->db_fetch_assoc($rsCWGetProductOptions);
	
	if ((strtolower($cartweaver->settings->detailsDisplay) == "tables") || ($rsCWGetProductOptions_recordCount > 2)) {
		$displayType = "Tables";
	}else if($rsCWGetProductOptions_recordCount > 1 && strtolower($cartweaver->settings->detailsDisplay) == "advanced") {
		$displayType = "Advanced";
	}else{
		$displayType = "Simple";
	}
	
	/* Get all of the product information for building our options lists. */
	$query_rsCWProductTable = "SELECT OT.optiontype_Name, 
	S.SKU_ID, 
	S.SKU_MerchSKUID, 
	S.SKU_Price, 
	O.option_Name, 
	O.option_Sort, 
	S.SKU_Stock, 
	O.option_ID
	FROM tbl_skus S
		INNER JOIN tbl_skuoption_rel SO
			ON S.SKU_ID = SO.optn_rel_SKU_ID
		INNER JOIN tbl_skuoptions O
			ON O.option_ID = SO.optn_rel_Option_ID
		INNER JOIN tbl_list_optiontypes OT
			ON OT.optiontype_ID = O.option_Type_ID
	WHERE S.SKU_ProductID = " . $productId .
	" AND S.SKU_ShowWeb = 1 ";
	if($cartweaver->settings->allowBackOrders == 0) {
		$query_rsCWProductTable .=  " AND S.SKU_Stock <> 0 ";
	}
	$query_rsCWProductTable .=  " ORDER BY ";
	$query_rsCWProductTable .= ($displayType == "Tables") ? "S.SKU_Sort, S.SKU_MerchSKUID, O.option_Sort" : "OT.optiontype_Name, O.option_Sort";
	$rsCWProductTable = $cartweaver->db->executeQuery($query_rsCWProductTable);
	$rsCWProductTable_recordCount = $cartweaver->db->recordCount;
	
	// Get all of the distinct options for the product /
	if($displayType == "Tables") {
		$distinctFields = array('optiontype_Name');
	}else{
		$distinctFields = array('optiontype_Name', 'option_ID', 'option_Name');
	}
	$rsCWAllOptions = $cartweaver->db->queryOfQuery($rsCWProductTable, $distinctFields, true, false, false);
	cwDebugger($rsCWAllOptions);	
	
	/* If we have more than 1 option, get our option information set for display */
	if($displayType == "Tables") {
		$optionNames = arrayKeyToArray($rsCWAllOptions, "optiontype_Name");
		$numOptions = count($optionNames);
		$optionArray = array();
		cwDebugger($optionNames);
	}

	if(count($rsCWAllOptions)!= 0) {
	
	if($displayType == "Tables"){
		$row_rsCWProductTable = $cartweaver->db->db_fetch_assoc($rsCWProductTable);
		/* If there are more than 3 options, display a crosstab table */ 
	echo(' <table class="tabularData"> 
	  <caption>
		Product Prices
	   </caption> 
	  <tr> 
		 <th>SKU</th>');
		/* Output the headers for each column */
		foreach($optionNames as $key => $value) { 
			echo("<th>$value</th>");
		} 
		echo("<th>Price</th><th>Qty.</th></tr>\r\n");
		$lastTFM_nest = "";
		$currentRow = 1;
		$price=0;
		$recCounter = 0;
		do{ /* Output all of the SKUs   */
			echo('<tr class="' . cwAltRow($recCounter++) . '"><th>');
			$tfm_nest = $row_rsCWProductTable['SKU_MerchSKUID'];
			if ($lastTFM_nest != $tfm_nest) {
				$lastTFM_nest = $tfm_nest;
				$price = $row_rsCWProductTable["SKU_Price"];
				$skuid = $row_rsCWProductTable["SKU_ID"];
				for($i=0; $i < $numOptions; $i++) $optionArray[$i] = "None";
				echo ($row_rsCWProductTable['SKU_MerchSKUID'] . "</th>");
			} //End of  Simulated Nested Repeat
			
			/* Set all options to "None" */
			do { /* Find the current option in the optionNames list */
				$i = arrayFind($optionNames, $row_rsCWProductTable["optiontype_Name"]);
				/* Set the array to the option name */
				if($i != -1) {
					$optionArray[$i] = $row_rsCWProductTable["option_Name"];
					$row_rsCWProductTable = $cartweaver->db->db_fetch_assoc($rsCWProductTable);
				}
			} while($currentRow++ % $numOptions != 0);
			
			/* Output each option for this sku */
			foreach($optionArray as $key => $value) {
				echo("<td>$value</td>");
			} 
		 	echo("<td>" . cartweaverMoney($price) . "</td>");
		 	echo('<td align="center"><input name="qty[]" type="text" value="0" size="2">
		  <input name="row[]" type="hidden" value="' . $currentRow . '"> 
		  <input name="skuid[]" type="hidden" value="' . $skuid . '"></td></tr>');
		} while ($currentRow < $rsCWProductTable_recordCount); // End while  
		echo("</table>\r\n");
	  /* If we have one or two options, display select menus */
	}else{ // end if displayType = tables 
		echo("<table>\r\n");
		/* If we're using advanced display, use fancy dropdowns. Iffy Netscape suppot */
		if ($displayType == "Advanced"){
			$query_rsCWParentQuery = "SELECT S.SKU_ID, 
			O.option_ID, 
			O.option_Name, 
			O.option_Sort
			FROM tbl_skuoptions O
			INNER JOIN tbl_skuoption_rel SO
			ON O.option_ID = SO.optn_rel_Option_ID
			INNER JOIN tbl_skus S
			ON S.SKU_ID = SO.optn_rel_SKU_ID
			WHERE S.SKU_ShowWeb = 1
			AND S.SKU_ProductID = ". $productId;
			if($cartweaver->settings->allowBackOrders == 0) {
				$query_rsCWParentQuery .= " AND S.SKU_Stock <> 0 ";
			}
			$query_rsCWParentQuery .= " ORDER BY O.option_Sort";
			
			$rsCWParentQuery = $cartweaver->db->executeQuery($query_rsCWParentQuery);
			$rsCWParentQuery_recordCount = $cartweaver->db->recordCount;
			//$row_rsCWParentQuery = $cartweaver->db->db_fetch_assoc($rsCWParentQuery);
		 echo("<script language=\"JavaScript\" src=\"cw2/assets/scripts/dropdowns.js\"></script>
		  <script language=\"JavaScript\">
			var arrDynaList = new Array();
			var arrDL1 = new Array();\r\n");
			$currentRow = 0;
			$lastTFM_nest = "";
			$intFieldCounter=1;
			foreach ($rsCWAllOptions as $key => $row_rsCWAllOptions){
				$currentRow++;
				$tfm_nest = $row_rsCWAllOptions['optiontype_Name'];
				if ($lastTFM_nest != $tfm_nest) {
					$lastTFM_nest = $tfm_nest;
					echo("arrDL1[" . $intFieldCounter++ . "] = \"sel" . $currentRow . "\";\r\n");
					echo("arrDL1[" . $intFieldCounter++ . "] = \"addToCart\";\r\n");
				}//End of  Simulated Nested Repeat
			}
			echo("arrDL1[" . $intFieldCounter . "] = arrDynaList;\r\n");
			//Explanation:
			//Element 1: Parent relationship
			//Element 2: Child Label
			//Element 3: Child Value
			$intListCounter = 0;
			$lastTFM_nest = "";
			foreach($rsCWAllOptions as $row_rsCWAllOptions){
				$tfm_nest = $row_rsCWAllOptions['optiontype_Name'];
				if (($intListCounter == 0) || ($lastTFM_nest == $tfm_nest)) {
					$lastTFM_nest = $tfm_nest;
					/* Get all SKUs with the current option */
					$rsCWOptionOne = $cartweaver->db->queryOfQuery($rsCWParentQuery, "*", false, "option_ID", $row_rsCWAllOptions["option_ID"]);
					/* Get a list of skus for filtering options */
					$cascadeSkuIDs = arrayValueList($rsCWOptionOne,"SKU_ID");
					/* Get all child options for the parent options */
					$rsCWTempOptions = $cartweaver->db->queryOfQuery($rsCWParentQuery, "*", false, "SKU_ID", $cascadeSkuIDs);
					$rsCWChildOptions = arrayRemove($rsCWTempOptions, "option_ID", $row_rsCWAllOptions["option_ID"]);
					/* Loop through the lists for the current parent option and set all available child options */
					foreach($rsCWChildOptions as $option => $value) {
						echo("arrDynaList[" . $intListCounter++ . "] = \"" . $rsCWOptionOne[0]["option_ID"] . "\";\r\n");
						echo("arrDynaList[" . $intListCounter++ . "] = \"" . $rsCWChildOptions[$option]["option_Name"] . "\";\r\n");
						echo("arrDynaList[" . $intListCounter++ . "] = \"" . $rsCWChildOptions[$option]["option_ID"] . "\";\r\n");
					}
					/* Reset the SKU list for the next loop */
					$cascadeSkuIDs = "";
				}
			}
			echo("</script>\r\n");
		} // end if ($displayType == "Advanced")
		 /* Output the select menu */
		$intSelectCounter = 0;
		if ($rsCWGetProductOptions_recordCount != 1) {
			$lastTFM_nest = "";
	
			foreach($rsCWAllOptions as $row_rsCWAllOptions) {
				$intSelectCounter++;
				$tfm_nest = $row_rsCWAllOptions['optiontype_Name'];
				if ($lastTFM_nest != $tfm_nest) {
					$lastTFM_nest = $tfm_nest; 
			echo('<tr><td align="right">');
			echo($row_rsCWAllOptions["optiontype_Name"]);
			echo(" :</td>\r\n");
			echo('<td><select name="sel' . $intSelectCounter . '"');
			/* If we're using advanced meus, set the onChange event to make it work. */ 
			if (($intSelectCounter == 1) && $displayType == "Advanced") {
				echo (' onChange="setDynaList(arrDL1)"');
			}
			echo('>'); 
		} //End of  Simulated Nested Repeat 
		echo('<option value="' . $row_rsCWAllOptions["option_ID"] . '">' . $row_rsCWAllOptions["option_Name"] . "</option>\r\n");
		$tfm_nest = $row_rsCWAllOptions['optiontype_Name'];
		if ($lastTFM_nest != $tfm_nest) {
			$lastTFM_nest = $tfm_nest; 
			echo("</select></td></tr>\r\n");	   
		} //End of  Simulated Nested Repeat
	   }
	}else{
		while ($row_rsCWProductTable = $cartweaver->db->db_fetch_assoc($rsCWProductTable)) {
			$intSelectCounter++; 
			$tfm_nest = $row_rsCWProductTable['optiontype_Name'];
			if ($lastTFM_nest != $tfm_nest) {
				$lastTFM_nest = $tfm_nest;
				echo('<tr><td align="right">' . $row_rsCWProductTable["optiontype_Name"] . ": </td>\r\n");
				echo('<td><select name="skuid[]">');
			} //End of  Simulated Nested Repeat
			echo('<option value="' . $row_rsCWProductTable["SKU_ID"] . '">' . $row_rsCWProductTable["option_Name"] . "</option>\r\n");
			$tfm_nest = $row_rsCWProductTable['optiontype_Name'];
			if ($lastTFM_nest != $tfm_nest) {
				$lastTFM_nest = $tfm_nest; 
				echo("</select></td>\r\n</tr>\r\n");	   
			} //End of  Simulated Nested Repeat 
		} // end if
	}
	echo('	<tr>
		<td align="right">Qty:</td> 
		<td>');
	/* If the form has been submitted, and there were multiple submissions */ 
	$intQuantity = 1;
	if (isset ($_POST["addToCart"]) && $_POST["addToCart"] == 'multi') {
		if ($cartweaver->getCWError()) { /* If there is a CWError */ 
			$intQuantity = $_POST["qty"][0];			
		}			
	}
	echo('<input name="qty[]" type="text" value="' . $intQuantity . '" size="2"> </td>');
	echo("\r\n");
	echo("</tr> 
	</table>\r\n");
	}
	}else{
		if(isset($skuid) && $skuid != ""){ 
			echo('Qty:
					<input name="qty" type="text" value="1" size="2"> 
					<input name="skuid" type="hidden" value="' . $skuid . '"> 
					<br>');
	}else{ 
		echo('<p>This product is currently out of stock.</p>');
		}
	}
	/* Start JavaScript if we're using Advanced display */
	if ($displayType == "Advanced"){
		echo("<script language=\"JavaScript\">setDynaList(arrDL1);</script>\r\n");
	} 
}
?> 