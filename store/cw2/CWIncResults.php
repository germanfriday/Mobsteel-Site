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
Name: CWIncResults.php
Description: Displays all results from a search in a repeating
	table.
================================================================
*/


$urlkeywords = (isset($_GET["keywords"])) ? $_GET["keywords"] : "";
$urlcategory = (isset($_GET["category"])) ? $_GET["category"] : "";
$urlsecondary = (isset($_GET["secondary"])) ? $_GET["secondary"] : "";

/* ////////// [ START - Cartweaver Search Results include file Call ] ////////// */
/* All the search queriess are contained in the CWFunSearchAction.php file */
require_once("CWLibrary/CWFunSearchAction.php");
/* ////////// [ END ] Cartweaver Search Results include file Call ] ////////// */

$maxRows_rsCWResults = $cartweaver->settings->recordsAtATime;
$cwPageNum_rsCWResults = 0;
if (isset($_GET['pageNum_rsCWResults'])) {
  $cwPageNum_rsCWResults = $_GET['pageNum_rsCWResults'];
}
$cwStartRow_rsCWResults = $cwPageNum_rsCWResults * $maxRows_rsCWResults;

$query_rsCWResults = cwSearchAction($urlcategory , $urlsecondary, $urlkeywords, $cartweaver->settings->allowBackOrders);
$rsCWResults = $cartweaver->db->executeQuery($query_rsCWResults);
if($cartweaver->db->recordCount > 0) {
	$idList = $cartweaver->db->valueList($rsCWResults, "product_ID");
	$query_rsCWResults = "SELECT p.product_ID, 
	p.product_Name, 
	p.product_ShortDescription
	FROM tbl_products p
	WHERE p.product_ID IN ($idList)
	ORDER BY p.product_Sort, 
	p.product_Name";
	$query_limit_rsCWResults = sprintf("%s LIMIT %d, %d", $query_rsCWResults, $cwStartRow_rsCWResults, $maxRows_rsCWResults);	
	$rsCWResults = $cartweaver->db->executeQuery($query_limit_rsCWResults);
}
$rsCWResults_recordCount = $cartweaver->db->recordCount;

/* Set Variables for recordset Paging  */
if (isset($_GET['totalRows_rsCWResults'])) {
  $cwTotalRows_rsCWTotals = $_GET['totalRows_rsCWResults'];
} else {
	$rsCWTotals = $cartweaver->db->executeQuery($query_rsCWResults);
	$cwTotalRows_rsCWResults = $cartweaver->db->recordCount;
}
$totalPages_rsCWResults = ceil($cwTotalRows_rsCWResults/$maxRows_rsCWResults);

/* Variables for manipulating product images */
$imageRoot = $cartweaver->settings->imageThumbFolder;
$imagePath = "";
$imageSRC = "";

/* START -  Display Results */
//  Display number of matching records  // 
?>
<p><strong>Total Search Results: </strong>[ <?php echo($cwTotalRows_rsCWResults);?> ]</p> 
<?php
//  Display the following based on search results  //
if ($rsCWResults_recordCount == 0) { ?>
	<p align="center"> Sorry, no records that match your search criteria were found.<br> 
		Please refine or change your search and try again.</p> 
	<p align="center"><strong>Thank You! </strong></p>
<?php
} else {
// If there ARE records, display a table with the results // 
	?>
<table class="tabularData" id="tableSearchResults"> 
	<tr> 
		<th height="5">&nbsp;</th> 
		<th>&nbsp;</th> 
	</tr>
	<?php
$row_rsCWResults = $cartweaver->db->db_fetch_assoc($rsCWResults);
$recCounter = 0;
do {
	//Get product thumbnail //
	$tempProdId = $row_rsCWResults["product_ID"];
	$rsCWThumbnail_query = "SELECT prdctImage_FileName
	FROM tbl_prdtimages 
	WHERE prdctImage_ProductID = $tempProdId 
	AND	prdctImage_ImgTypeID = 1";
	$rsCWThumbnail = $cartweaver->db->executeQuery($rsCWThumbnail_query);
	$row_rsCWThumbnail = $cartweaver->db->db_fetch_assoc($rsCWThumbnail);
	$imageSRC = $imageRoot . $row_rsCWThumbnail["prdctImage_FileName"];
	$imagePath = expandPath($imageSRC);
	?>
	<tr class="<?php cwAltRow($recCounter++);?>" style="vertical-align: top">
		<td><p><strong><?php echo($row_rsCWResults["product_Name"]);?></strong><br>
			<?php echo($row_rsCWResults["product_ShortDescription"]);?></p></td>
		<td style="text-align: center;"><?php 
				if (file_exists($imagePath) && is_file($imagePath)) { ?>
					<a href="<?php echo($cartweaver->settings->targetDetails . "?prodId=" . $row_rsCWResults["product_ID"] . "&category=" . $urlcategory) ?>"><img src="<?php echo($imageSRC) ?>" alt="<?php echo($row_rsCWResults["product_Name"]) ?>" border="0"></a>
				<?php }else{ ?>No Image Available
				<?php } ?>
				<p><a href="<?php echo($cartweaver->settings->targetDetails . "?prodId=" . $row_rsCWResults["product_ID"] . "&category=" . $urlcategory) ?>">Details</a></p>
		</td>
  </tr>
	<?php 
	}  while ($row_rsCWResults = $cartweaver->db->db_fetch_assoc($rsCWResults)); ?>
</table><?php
} // End if ($rsCWResults_recordCount == 0)
// END -  Display Results //

// RecordSet Paging //
if ($rsCWResults_recordCount != 0){
// Set defaults for paging //
	$pagingCategory = "";
	$pagingSecondary = "";
	$pagingKeywords = "";
	if ($urlcategory != ""){
		$pagingCategory = "&category=" . $urlcategory;
	}
	if ($urlsecondary != ""){
		$pagingSecondary = "&secondary=" . $urlsecondary;
	}
	if ($urlkeywords != ""){
		$pagingKeywords = "&keywords=" . $urlkeywords;
	}
	$pagingURL = $pagingCategory . $pagingSecondary . $pagingKeywords;
	?>
	<p class="pagingLinks"><?php
		if ($cwPageNum_rsCWResults > 0){ ?>
			<a href="<?php echo($cartweaver->thisPage ."?pageNum_rsCWResults=0" . $pagingURL) ?>">First</a> <a href="<?php echo($cartweaver->thisPage . "?pageNum_rsCWResults=" .  max(0, $cwPageNum_rsCWResults - 1) . $pagingURL) ?>">Previous</a>
		<?php 
		}
		if ($cwPageNum_rsCWResults < $totalPages_rsCWResults - 1) { ?>
			<a href="<?php echo($cartweaver->thisPage ."?pageNum_rsCWResults=" . (min($totalPages_rsCWResults, $cwPageNum_rsCWResults + 1)) . $pagingURL) ?>">Next</a> <a href="<?php echo($cartweaver->thisPage . "?pageNum_rsCWResults=" . ($totalPages_rsCWResults - 1) . $pagingURL) ?>">Last</a>
		<?php
		} ?></p> 
<?php } ?>