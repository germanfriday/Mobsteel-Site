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
Name: Cartweaver Custom Class
Description: This is the heart of the Cartweaver functionality. 
This class creates the cart, adds to, updates and deletes
items from the cart.
================================================================

Methods:
		*	add: Add SKUs to the cart as defined by the 
			sku_id and sku_qty attributes. The sku_id and sku_qty are 
			required for the multi-sku action. The sku_id and sku_qty must 
			be passed in comma delimited lists corresponding to the SKU ids 
			and quantities to be added. The CWIncDetails.php include uses 
			this method when adding products from a crosstab table of SKUs, 
			where multiple SKUs can be added at one time.

		*	update: Update a SKU to a specific quantity in the cart as
			defined by the sku_id and sku_qty attributes. The sku_id is 
			required for the update action. Setting the quantity to 0 will 
			delete an item from the cart.

		*	delete: Delete a SKU from the cart as defined by the sku_id 
			attribute. The sku_id is required for the delete action.
	
		*	multiOption: Add a SKU based on the SKU options as defined in 
			the optionlist attribute. The optionlist attribute is required 
			for the multi-option action. The appropriate SKU is found for 
			you based on the options based to the include file. The CWIncDetails.php 
			include uses this method when adding products with two options.
			
		*	multiAdd: private method used by multiOption and multiSku
		
		*	clearCart: Clears the cart and removes it from the database
		
		*	validate: this method displays an error message to the user if 
			there are any errors contained in the cart
			
		SETTER METHODS:
		
		*	setSkuQty 
		*	setSkuId 
		*	setCWError 
		*	setOptionList 
		*	setProductId 
		*	setCartId 
		
		GETTER METHODS:
		
		*	getSkuQty 
		*	getSkuId 
		*	getCWError 
		*	getOptionList 
		*	getProductId 
		*	getCartId 
		*	getQtyAdded 		

Properties:  (use getter methods)

	sku_id (optional): The sku_id attribute passes either a specific SKU ID 
		(for the add, update and delete cartactions) or a comma delimited 
		list of SKU IDs for the multi-sku cart action. The SKU ID passed is 
		the SKU_ID field from tbl_SKUs. The default is 0 if the attribute is not provided.
	
	sku_qty (optional): The sku_qty attribute passes either a single quantity 
		(for the add and update cartactions) or a comma delimited list of 
		quantities for the multi-option cart action. The default is 1 if the 
		attribute is not provided. If 0 is passed, the SKU is deleted from the 
		cart, regardless of the cartaction.
		
	optionlist (optional): The optionlist attribute passes a comma separated 
		list of option IDs for use with the multi-option cartaction. 
		This attribute is not used with any other cartaction.


	cwError: Any errors that occur during cart actions are placed in the cwError array.
		-cwError["stockAlert"]: Returns “Yes” if backorders are not allowed and there 
		is not enough quantity to fill a particular quantity request.
	
	qtyAdded: The number of items successfully added to the cart. Use getter method.


*/


// $db  contains database functionality, $settings contains Cartweaver prefs
class CWCart {
	var $name, $db, $settings, $debugger, $sku_qty, $sku_id, $qtyAdded, $cwError, $optionList, $productId, $CartId;
	function CWCart($name, $cartId, $settings){
		// Set the properties
		$this->name = $name;
		$this->qtyAdded = 0;
		// CWGlobalSettings includes all global settings for app
		//   place into settings property
		$this->settings = $settings;
		require_once($this->settings->db);
		
		$this->debugger = "OFF";
		$this->cwError = array();
		$this->setCartId($cartId);
		$this->db = new DB($this->settings->hostname,
											$this->settings->database, 
											$this->settings->databaseUsername,
											$this->settings->databasePassword);
		$this->getSkuQty();
	}

	// SETTER FUNCTIONS
	function setSkuQty($sku_qty) {
		/* VALIDATIONS ==============================
		Validate Quantity to be sure that the quantity is not 0,
		and does not contain a fraction or negative number.
		*/
        if(is_array($sku_qty)) {
           $this->sku_qty = $sku_qty;
           return;
        }
		if(!isset($sku_qty)) $sku_qty = 0;
		if(!is_numeric($sku_qty)) {
			$this->setCWError("badValue","Please enter a valid quantity.");
			$this->sku_qty = 0;
			return;
		}
		$sku_qty = intval($sku_qty);
		$this->sku_qty = $sku_qty;
	}
	
	// pass in qty, sku, and optional true/false if adding items to cart
	function checkStockCount($sku_qty, $sku_id, $add = false) {
		/* if back orders are not allowed verify quantity against SKU_Stock  */
		if($sku_qty > 0 && $this->settings->allowBackOrders == 0) {
			$query_rsCWCheckStockCount = "SELECT SKU_Stock 
			FROM tbl_skus
			WHERE SKU_ID = " . $sku_id;
			$rsCWCheckStockCount = $this->db->executeQuery($query_rsCWCheckStockCount);
			$row_rsCWCheckStockCount = $this->db->db_fetch_assoc($rsCWCheckStockCount);
			/*
			If the new quantity exceeds the stock quantity
			If Backorders are not allowed Check total quantity in the cart to be sure
			that the updated amount will not exceed the stock count.
			If it does, adjust quantity to Stock Count.
			*/
			// if none left, return 0
			if($row_rsCWCheckStockCount["SKU_Stock"] <= 0) return 0;
			$currentQty = 0;
			// if adding an item to the cart, check existing cart quantity
			if($add) $currentQty = $this->getCartItemQty($sku_id);
			if ($sku_qty + $currentQty > $row_rsCWCheckStockCount["SKU_Stock"]) {
				/* Set the stockAlert so we can display an error on the add to cart page. */
				$this->setCWError("stockAlert","You have selected more quantity than is currently available.");
				/* Set the qty added to match current quantity on hand minus what was already in cart */
				if($currentQty >= $row_rsCWCheckStockCount["SKU_Stock"]) {
					if($currentQty > $row_rsCWCheckStockCount["SKU_Stock"]){
						$this->updateItem($sku_qty, $row_rsCWCheckStockCount["SKU_Stock"]);
					}
					// More items in cart than exist in stock. Can't add any.
					return 0;
				}
				return $row_rsCWCheckStockCount["SKU_Stock"] - $currentQty;
			}
		}
		// Stock count is fine
		return $sku_qty;
	}

	function setSkuId($sku_id) {
		if(isset($sku_id)) $this->sku_id = $sku_id;
	}

	function setCWError($key=null, $value=null) {
		if(isset($key)) {
			$this->cwError[$key] = $value;
		}else{
			$this->cwError = array();
		}
	}
	
	function setOptionList($optionList) {
		if(isset($optionList)) $this->optionList = $optionList;
	}
	
	function setProductId($productId) {
		if(isset($productId)) $this->productId = $productId;
	}

	function setCartId($cartId) {
		/* Check to see if a CartId exists, if it dosen't create one */
		if(!isset($cartId)) {
			$cartId = rand(1000000,5000000);
		}
		$this->CartId = $cartId;
		setcookie("CartId", $cartId, mktime(12,0,0,1, 1, 2020));
	}
	
	// GETTER FUNCTIONS
	function getSkuQty() {
		if(!isset($this->sku_qty)) {
			$this->sku_qty = 0;
			return $this->sku_qty;
		}else{
			return $this->sku_qty;
		}
	}
	
	function getCartQty() {
		$query_rsCWGetCartCount = "SELECT SUM(cart_sku_qty) AS CartCount
		, cart_custcart_ID 
		FROM tbl_cart 
		GROUP BY cart_custcart_ID 
		HAVING cart_custcart_ID = " . $this->CartId;	
		$rsCWGetCartCount = $this->db->executeQuery($query_rsCWGetCartCount);
		$row_rsCWGetCartCount = $this->db->db_fetch_assoc($rsCWGetCartCount);
		$cartQuantity = intval($row_rsCWGetCartCount["CartCount"]);
		return $cartQuantity;
	}

	function getCartItemQty($sku_id) {
		$query_rsCWGetCartItemCount = sprintf("SELECT SUM(cart_sku_qty) AS ItemCount,
		cart_custcart_ID
		FROM tbl_cart 
		WHERE cart_sku_ID = '%s'
		GROUP BY cart_custcart_ID 
		HAVING cart_custcart_ID = '%s'",$sku_id, $this->CartId);
		$rsCWGetCartItemCount = $this->db->executeQuery($query_rsCWGetCartItemCount);
		$row_rsCWGetCartItemCount = $this->db->db_fetch_assoc($rsCWGetCartItemCount);
		$cartItemQuantity = intval($row_rsCWGetCartItemCount["ItemCount"]);
		return $cartItemQuantity;
	}

	function getSkuId() {
		return (isset($this->sku_id)) ? $this->sku_id : 0;
	}

	function getQtyAdded() {
		return (isset($this->qtyAdded)) ? $this->qtyAdded : null;
	}
	
	// gets a specific error, or returns entire array if no key is given
	function getCWError($key = null) {
		if (isset($key)) {
		  return (isset($this->cwError[$key])) ? $this->cwError[$key] : null;
		}else{
			$temp =count($this->cwError);
			if($temp == 0) {
				return null;
			}else{
				$errorStr = "";
				foreach($this->cwError as $key=>$value) {
					$errorStr .=  $value . "<br>";
				}
				return $errorStr;
			}
		}
	}
	
	function getOptionList() {
		return $this->optionList;
	}

	function getProductId() {
        if(isset($this->productId)){
           return $this->productId;
        }else{
          return null;
        }
	}
	
	function getCartId() {
		return $this->CartId;
	}
	
	function displayError($text,$error,$tag="span") {
		$errorMessage = $this->getCWError($error);
		if($errorMessage && $errorMessage != "") {
			$errorMessage = str_replace("\\","",urldecode($errorMessage));
			echo("<$tag class=\"errorMessage\">$errorMessage</$tag>");
			$this->setCWError($error,$text);
		}else{
			echo($text);
		}		
	}
	

	/* ///////////////////////////////////////////////////////////////////// */
	/* [ UPDATE ITEMS IN CART ] === START ============ */
	function update() {
		/* If we have multiple skus */
		/* Put the skus and qtys in parallel arrays */
		if(is_array($this->sku_id) || is_array($this->sku_qty)) {
           $arSkus = $this->sku_id;
           $arQty = $this->sku_qty;
        }else{
           $arSkus = split(",",$this->sku_id);
           $arQty = split(",",$this->sku_qty);
        }
		/* Loop through our array of SKUs and get SKU data */
		for($i = 0; $i < count($arSkus); $i++) {
			// Check each product sku to make sure they are available
			$this->setSkuQty($arQty[$i]);
			$arQty[$i] = $this->getSkuQty();
			$arQty[$i] = $this->checkStockCount($arQty[$i], $arSkus[$i]);
			/* update sku that already exists in the cart */
			$query_rsCWUpdate = sprintf("UPDATE tbl_cart 
			SET cart_sku_qty = %d 
			, cart_dateadded = now()
			WHERE cart_sku_ID = %d
			AND cart_custcart_ID = '%s'",$arQty[$i],$arSkus[$i],$this->CartId);
			$rsCWUpdate = $this->db->executeQuery($query_rsCWUpdate);
			$this->qtyAdded = $this->qtyAdded + $arQty[$i];
		}
	}
	
	/* [ UPDATE ITEM IN CART ] === END =========================== */
	function updateItem($sku, $qty) {
		if($qty == 0) {
			$this->delete($sku);
		}else{
			/* update sku that already exists in the cart */
			$query_rsCWUpdate = sprintf("UPDATE tbl_cart 
			SET cart_sku_qty = %d + cart_sku_qty
			, cart_dateadded = now()
			WHERE cart_sku_ID = %d
			AND cart_custcart_ID = '%s'",$qty,$sku,$this->CartId);
			$rsCWUpdate = $this->db->executeQuery($query_rsCWUpdate);
		}
	}
	/* [ END UPDATE ITEM IN CART ] === END =========================== */
	
	/* ///////////////////////////////////////////////////////////////////// */
	/* [ DELETE ITEM IN CART ] === START ============ */
	function delete($sku_id = null) {
		if(isset($sku_id)) {
			$this->setSkuId($sku_id);
		}
		if(is_array($this->sku_id)) {
			$deletelist = implode(",",$this->getSkuId());
		}else{
			$deletelist = $this->getSkuId();
		}
		/* delete sku from cart */
		$query_rsCWDelete = sprintf("DELETE FROM tbl_cart 
		WHERE cart_sku_ID IN ($deletelist)
		AND cart_custcart_ID ='%s'",$this->CartId);
		$rsCWDelete = $this->db->executeQuery($query_rsCWDelete);
	}
	/* [ DELETE ITEM IN CART ] === END ============================ */
  	
	/* If we have multiple options, but no definite sku */
	function multiOption() {
		/* create two arrays with just one element */
		$arSKUs = array();
		$arQty = array();
		$numOptions = count(split(",", $this->optionList));

		/* Get any found skus. Use optionlist variable to filter the recordset. */
		$query_rsCWoptn_rel_SKU_ID = "SELECT O.optn_rel_SKU_ID 
		FROM tbl_skuoption_rel O
		GROUP BY O.optn_rel_SKU_ID 
		HAVING Count(O.optn_rel_SKU_ID) = $numOptions";
		$rsCWOptn_rel_SKU_ID = $this->db->executeQuery($query_rsCWoptn_rel_SKU_ID);
		$optn_rel_SKU_ID = $this->db->valueList($rsCWOptn_rel_SKU_ID,"optn_rel_SKU_ID");
		
		$query_rsCWoptn_rel_SKU_ID2 = sprintf("SELECT O.optn_rel_SKU_ID
		FROM tbl_skuoption_rel O
		WHERE O.optn_rel_Option_ID IN (%s)
		AND O.optn_rel_SKU_ID IN ($optn_rel_SKU_ID)
		GROUP BY O.optn_rel_SKU_ID
		HAVING Count(O.optn_rel_SKU_ID) = $numOptions", $this->optionList);
		$rsCWOptn_rel_SKU_ID2 = $this->db->executeQuery($query_rsCWoptn_rel_SKU_ID2);
		$optn_rel_SKU_ID2 = $this->db->valueList($rsCWOptn_rel_SKU_ID2,"optn_rel_SKU_ID");		

		$query_rsCWFindSKU = "SELECT DISTINCT (SKU_ID) AS ThisSKU, SKU_Stock
		FROM tbl_skuoption_rel, tbl_skus
		WHERE SKU_ID IN ($optn_rel_SKU_ID2)
		AND SKU_ProductID	= " . $this->productId;
		$rsCWFindSKU = $this->db->executeQuery($query_rsCWFindSKU);
		/* If we didn't find a sku */ 
		if ($this->db->recordCount == 0) {
			/* Set an error to display on the page */ 
			$this->setCWError("noSku","No Matching SKU found for your selected options.");
		}else{
			/* Add the sku to be added to our cart to the end of our array */
			$row_rsCWFindSKU = $this->db->db_fetch_assoc($rsCWFindSKU);
			array_push($arSKUs, $row_rsCWFindSKU['ThisSKU']);
			/* Clean up quantity and place in array */
			if(!is_numeric($this->sku_qty[0]) || $this->sku_qty[0] < 0) {
				$this->setCWError("invalidQuantity","Please enter a valid quantity.");
				return;
			}else{
				array_push($arQty, $this->sku_qty[0]);
			}
		}
		$this->add($arSKUs, $arQty);
	} /* End Multi add section */

	function add($sku_id = null, $sku_qty = null) {
		/* if user passes sku_id and sku_qty, set them first */
		if(isset($sku_id) && isset($sku_qty)) {
			$this->setSkuId($sku_id);
			$this->setSkuQty($sku_qty);
		}		
		/* If we have multiple skus */
		/* Put the skus and qtys in parallel arrays */
		if(is_array($this->sku_id) || is_array($this->sku_qty)) {
           $arSkus = $this->sku_id;
           $arQty = $this->sku_qty;
        }else{
           $arSkus = split(",",$this->sku_id);
           $arQty = split(",",$this->sku_qty);
        }
		$this->qtyAdded = 0;
		/* If we have valid products to add to the cart */
		if (isset($arSkus) && count($arSkus) != 0){
			/* Loop through our array of SKUs and get SKU data */
			for($i = 0; $i < count($arSkus); $i++) {
				$this->setSkuId($arSkus[$i]);
				$arQty[$i] = $this->checkStockCount($arQty[$i], $this->getSkuId(), true);				
				$this->setSkuQty($arQty[$i]);
				$this->addItem();
				$this->qtyAdded = $this->qtyAdded + $arQty[$i];
			}
		}
		return;
	}
	
	/* [ Private method -- ADD NEW ITEM TO CART ] === START ============== */
	function addItem() {
		if($this->getSkuQty() > 0) {
			// Check for item first:
			$query_rsCWSkuExists = sprintf("SELECT cart_Line_ID, cart_sku_qty 
			FROM tbl_cart
			WHERE cart_custcart_ID = '%s' 
			AND	cart_sku_ID = %d",$this->getCartId(),$this->sku_id);
			$rsCWSkuExists = $this->db->executeQuery($query_rsCWSkuExists);
			// Item already exists in cart, update it
			if($this->db->recordCount > 0) {
				$this->updateItem($this->getSkuId(), $this->getSkuQty());
			}else{
				/* Insert sku into cart */
				$query_rsCWAdd = sprintf("INSERT INTO tbl_cart 
				(cart_custcart_ID, cart_sku_ID, cart_sku_qty, cart_dateadded)
				VALUES ('%s', %d, %d, now())",$this->CartId,$this->sku_id,$this->getSkuQty());
				$rsCWAdd = $this->db->executeQuery($query_rsCWAdd);
			}
		}
		return;
	}

	/* [ Public method -- CLEAR THE CART ] === START ============== */
	function clearCart() {
		$query_rsCWKillCart = "DELETE FROM tbl_cart
		WHERE cart_custcart_ID='". $this->getCartId() . "'";
		$rsCWKillCart = $this->db->executeQuery($query_rsCWKillCart);
		$this->setCWError();
		setcookie("CartId", "", mktime(12,0,0,1, 1, 1980));
		return;
	}
	
	/* [ Public method -- Cart links for display ] === START ============== */
	function cartLinks($returnURL = null) {
		if(!isset($returnURL)) {
			$returnURL = urlencode($this->settings->targetResults);
		}
		// Set the divider between the cart links
		$divider = " | ";
		$cartlinks = array();
		$cartQuantity = $this->getCartQty();
		$items = ($cartQuantity == 0 || $cartQuantity > 1) ? "items" : "item";
		$cartlinks_display = "You have <strong>$cartQuantity</strong> $items in your cart. ";
		$cartlinks["gotocart"] = "<a href=\"" . $this->settings->targetGoToCart . "?cartid=" . $this->getCartId() . "&returnurl=$returnURL\">View Cart</a>";
		$cartlinks["checkout"] = "<a href=\"" . $this->settings->targetCheckout . "?cartid=" .  $this->getCartId() . "\">Go to Checkout</a>";
		
		if($this->settings->cwDebug) {
			// The following is for debugging
			$cartlinks["debugger"] = "<a href=\"" . $this->thisPage;
			$debuggerQS = "";
			foreach($_GET as $key=>$val) {
				if($key != "debug") {
					if($debuggerQS != "") $debuggerQS .= "&";
					$debuggerQS .= $key . "=" . $val;
				}
			}
			$cartlinks["debugger"] .= ($debuggerQS == "") ? "?" : "?$debuggerQS&";
			$cartlinks["debugger"] .= "debug=" . $this->settings->debugPassword;
			$cartlinks["debugger"] .= "\">Debugger " . $this->debugger  . "</a>";
		}
		
		$tempdivider = "";
		foreach ($cartlinks as $key => $value) {
			$cartlinks_display .= "$tempdivider$value";
			if($tempdivider == "") $tempdivider = $divider;
		}
		$cartlinks_display .= "";
		return $cartlinks_display;
	}	
} /* End Class definition */
?>