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
Name: Validate Product Form
Description: Here we validate the data submitted in the product form
If the data fits the required parameters we send continue on with 
the transaction. If not we halt the transaction and pass back error 
messages to be displayed by the calling template.
================================================================
*/

/* We either updated or added a new product */
$addProductError = array(); // new arrays to hold errors
$addSKUError = array();

if( isset($_POST["AddProduct"]) || isset($_POST["UpdateProduct"])){
	if( isset($_POST["AddProduct"]) && $_POST["product_MerchantProductID"] == ""){
		array_push($addProductError,"Product ID is required.");
	}
	if( $_POST["product_Name"] == ""){
		array_push($addProductError,"Product Name is required.");
	}
	if( $_POST["product_Sort"] == "" && is_numeric($_POST["product_Sort"])){
		$_POST["product_Sort"] = 0;
	}
	if( ! isset($_POST["scndctgry_ID"]) || $_POST["scndctgry_ID"] == ""){
		$_POST["scndctgry_ID"] = 1;
	}
	if( $_POST["product_ShortDescription"] == ""){
		array_push($addProductError,"A Short Description is required.");
	}
	if( $_POST["product_Description"] == ""){
		array_push($addProductError,"A Long Description is required..");
	}
}

/* If we're adding a new sku */
if( isset($_POST["addsku"])){
	if( $_POST["SKU_MerchSKUID"] == ""){
		array_push($addSKUError,"A SKU is required.");
	} 
	if( !is_numeric($_POST["SKU_Price"])){
		array_push($addSKUError,"A valid price is required.");
	} 
	if( !is_numeric($_POST["SKU_Sort"])){
		$_POST["SKU_Sort"] = 0;
	}
	if( !is_numeric($_POST["SKU_Weight"])){
		$_POST["SKU_Weight"] = 0;
	}
	if( !is_numeric($_POST["SKU_Stock"])){
		$_POST["SKU_Stock"] = 0;
	}
}
?>