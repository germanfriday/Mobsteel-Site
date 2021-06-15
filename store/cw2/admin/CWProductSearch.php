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
Name: CWIncProductSearch.php
Description: Product Search form used on the product list and product details pages.
================================================================
*/
if(!isset($_GET["searchBy"])) {
	$_GET["searchBy"] = "prodID";
}
if(!isset($_GET["matchType"])) {
	$_GET["matchType"] = "anyMatch";
}
if(!isset($_GET["find"])) {
	$_GET["find"] = "";
}
if(!isset($_GET["find"])) {
	$_GET["find"] = "";
}
if(!isset($_GET["status"])) {
	$_GET["status"] = "0";
}

$queryFind = "%";
if ($_GET["find"] != "") {
	$queryFind = $_GET["find"];
}
/* Set Variables for recordset Paging  */
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsCWProductsSearch = 10;
$cwPageNum_rsCWProductsSearch = 0;
if (isset($_GET['pageNum_rsCWProductsSearch'])) {
  $cwPageNum_rsCWProductsSearch = $_GET['pageNum_rsCWProductsSearch'];
}
$cwStartRow_rsCWProductsSearch = $cwPageNum_rsCWProductsSearch * $maxRows_rsCWProductsSearch;

$query_rsCWProductsSearch = "SELECT 
	p.product_ID, 
	p.product_Name, 
	p.product_OnWeb, 
	p.product_MerchantProductID
FROM tbl_products p
WHERE 
	p.product_Archive = " . $_GET["status"];
if($_GET["searchBy"] == "prodID") {
    $query_rsCWProductsSearch .= " AND p.product_MerchantProductID";
}else{
    $query_rsCWProductsSearch .= " AND p.product_Name";
}
if($_GET["matchType"] == "exactMatch") {
    $query_rsCWProductsSearch .= " = '$queryFind'";
}else{
    $query_rsCWProductsSearch .= " LIKE '%$queryFind%'";
}

$query_rsCWProductsSearch .= " ORDER BY p.product_Name, p.product_MerchantProductID";
$query_limit_rsCWProductsSearch = sprintf("%s LIMIT %d, %d", $query_rsCWProductsSearch, $cwStartRow_rsCWProductsSearch, $maxRows_rsCWProductsSearch);

$rsCWProductsSearch = $cartweaver->db->executeQuery($query_limit_rsCWProductsSearch);
$rsCWProductsSearch_recordCount = $cartweaver->db->recordCount;
$row_rsCWProductsSearch = $cartweaver->db->db_fetch_assoc($rsCWProductsSearch);
?>

<form name="theForm" method="get" action="ProductActive.php">
  <p>
    <label for="Find">Find</label>
    <input name="find" type="text" id="find" value="<?php echo($_GET["find"]);?>">
    <label for="searchBy">by</label>
    <select name="searchBy" id="searchBy">
      <option value="prodID" <?php if($_GET["searchBy"] == "prodID") {echo("selected");}?>>ID</option>
      <option value="prodName" <?php if($_GET["searchBy"] == "prodName") {echo("selected");}?>>Name</option>
    </select>
    <label for="matchType">match</label>
    <select name="matchType" id="matchType">
      <option value="anyMatch" <?php if($_GET["matchType"] == "anyMatch") {echo("selected");}?>>Any</option>
      <option value="exactMatch" <?php if($_GET["matchType"] == "exactMatch") {echo("selected");}?>>Exact</option>
    </select>
	<input type="hidden" name="status" value="<?php echo($_GET["status"]);?>"/>
    <input name="Search" type="submit" class="formButton" id="Search" value="Search" style="margin-bottom: 2px;">
  </p>
</form>




<?php
if (isset($_GET['totalRows_rsCWProductsSearch'])) {
  $cwTotalRows_rsCWProductsSearch = $_GET['totalRows_rsCWProductsSearch'];
} else {
  $all_rsCWProductsSearch = $cartweaver->db->executeQuery($query_rsCWProductsSearch);
  $cwTotalRows_rsCWProductsSearch = $cartweaver->db->recordCount;
}

$totalPages_rsCWProductsSearch = ceil($cwTotalRows_rsCWProductsSearch/$maxRows_rsCWProductsSearch)-1;
$queryString_rsCWProductsSearch = "";
if (!empty($_SERVER['QUERY_STRING'])) {
	$params = explode("&", $_SERVER['QUERY_STRING']);
	$newParams = array();
	foreach ($params as $param) {
		if (stristr($param, "pageNum_rsCWProductsSearch") == false && 
			stristr($param, "totalRows_rsCWProductsSearch") == false) {
			array_push($newParams, $param);
		}
	}
	if (count($newParams) != 0) {
		$queryString_rsCWProductsSearch = "&" . htmlentities(implode("&", $newParams));
	}
}
$queryString_rsCWProductsSearch = sprintf("&totalRows_rsCWProductsSearch=%d%s", $cwTotalRows_rsCWProductsSearch, $queryString_rsCWProductsSearch);

$pagingURL = "&searchBy=" . $_GET["searchBy"] . "&matchtype=" . $_GET["matchType"] . "&find=" . $_GET["find"];

$pagingLinks = '<p class="pagingLinks">Page ' . ($cwPageNum_rsCWProductsSearch + 1) . " of " . ($totalPages_rsCWProductsSearch + 1) . "<br />";
if ($cwPageNum_rsCWProductsSearch > 0) { // Show if not first page 
	$pagingLinks .= '<a href="' . sprintf("%s?pageNum_rsCWProductsSearch=%d%s", $currentPage, 0, $queryString_rsCWProductsSearch) . '">First</a> | ';
	$pagingLinks .= '<a href="' . sprintf("%s?pageNum_rsCWProductsSearch=%d%s", $currentPage, max(0, $cwPageNum_rsCWProductsSearch - 1), $queryString_rsCWProductsSearch) . '">Previous</a> | ';
} else {
	$pagingLinks .= "First | Previous | ";
}// Show if not first page 

if ($cwPageNum_rsCWProductsSearch < $totalPages_rsCWProductsSearch) { // Show if not last page 
	$pagingLinks .= '<a href="' . sprintf("%s?pageNum_rsCWProductsSearch=%d%s", $currentPage, min($totalPages_rsCWProductsSearch, $cwPageNum_rsCWProductsSearch + 1), $queryString_rsCWProductsSearch). '">Next</a> | ';
	$pagingLinks .= '<a href="' . sprintf("%s?pageNum_rsCWProductsSearch=%d%s", $currentPage, $totalPages_rsCWProductsSearch, $queryString_rsCWProductsSearch). '">Last</a>';
} else {
	$pagingLinks .= "Next | Last";
} // Show if not last page   
$pagingLinks .= "</p>"; 
?>
