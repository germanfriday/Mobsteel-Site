<?php
require_once("application.php");
if(isset($_GET["status"])) {
	$archived = $_GET["status"] == "1";
}else{
	$archived = false;
}
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
Name: ProductActive.php
Description: Display a list of Active Products
================================================================
*/

/* Set location for highlighting in Nav Menu */
$strSelectNav = "Products";

/* Reactivate Product */
if(isset($_GET["ReactivateProduct_ID"])){
	$query_rsCWArchiveProduct = "UPDATE tbl_products
	  SET product_Archive = '0'
	  WHERE product_ID = " . $_GET["ReactivateProduct_ID"];
	$rsCWArchiveProduct = $cartweaver->db->executeQuery($query_rsCWArchiveProduct);
	header("Location: ProductForm.php?product_ID=" . $_GET["ReactivateProduct_ID"]);
	exit();
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CW Admin: Product List</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="assets/admin.css" rel="stylesheet" type="text/css">
</head>
<body>

<?php include("CWIncNav.php");?>

<div id="divMainContent">
 <?php 
require("CWProductSearch.php");

if($rsCWProductsSearch_recordCount != 0){ 
	echo($pagingLinks);?>
    <h1>Product List <?php if($archived) {echo("* Archived products *");}?></h1>
    <table>
      <tr>
        <th>Product ID</th>
        <th>Name</th>
        <th>Photo</th>
        <th>On Web</th>
        <th>Edit</th>
      </tr>
<?php
    $imageRoot = "../../$imageThumbFolder";			
	$fullRoot = $imageThumbFolder;
	$recCounter = 0;
	do {		
    	/* Get a product thumbnail if it exists */
		$query_rsCWThumbnail = "SELECT i.prdctImage_FileName
				FROM tbl_prdtimages i
				WHERE 
					i.prdctImage_ProductID = " . $row_rsCWProductsSearch["product_ID"] . " AND
					i.prdctImage_ImgTypeID = 1";
		$rsCWThumbnail = $cartweaver->db->executeQuery($query_rsCWThumbnail);
		$rsCWThumbnail_recordCount = $cartweaver->db->recordCount;
		$row_rsCWThumbnail = $cartweaver->db->db_fetch_assoc($rsCWThumbnail);
		$imageSRC = $imageRoot . $row_rsCWThumbnail["prdctImage_FileName"]; 
		$imagePath = $siteRoot . $fullRoot . $row_rsCWThumbnail["prdctImage_FileName"];
		?>
        <tr class="<?php cwAltRow($recCounter++);?>">
          <td align="center"><?php echo($row_rsCWProductsSearch["product_MerchantProductID"]);?></td>
          <td><a href="ProductForm.php?product_ID=<?php echo($row_rsCWProductsSearch["product_ID"]);?>" title="Edit <?php echo($row_rsCWProductsSearch["product_Name"]);?>"><?php echo($row_rsCWProductsSearch["product_Name"]);?></a></td>
          <td align="center"><?php if(file_exists($imagePath)) {echo("<img src=\"$imageSRC\">");}?></td>
          <td align="center"><input name="checkbox" type="checkbox" class="formCheckbox" value="checkbox" disabled<?php if($row_rsCWProductsSearch["product_OnWeb"] == 1){echo(" checked");}?>></td>
          <td align="center"><?php if($archived) {
		  echo('[<a href="' . $cartweaver->thisPage . "?ReactivateProduct_ID=" . $row_rsCWProductsSearch["product_ID"] . '">Reactivate</a>]');
		  }else{
		  ?><a href="ProductForm.php?product_ID=<?php echo($row_rsCWProductsSearch["product_ID"]);?>"><img src="assets/images/edit.gif" alt="Edit <?php echo($row_rsCWProductsSearch["product_Name"]);?>" width="15" height="15" border="0"></a>
		  <?php } // END if($archived) ?></td>
        </tr>
      <?php } while ($row_rsCWProductsSearch = $cartweaver->db->db_fetch_assoc($rsCWProductsSearch)); ?>
    </table>
<?php echo($pagingLinks);
  }else{
 	echo("<p><strong>No Matches Found</strong></p>");
 }?>
</div>
</body>
</html>
<?php
cwDebugger($cartweaver);
?>