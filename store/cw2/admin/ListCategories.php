<?php
require_once("application.php");
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
Name: ListCategories.php
Description: List and administer Product Categories
================================================================
*/
/* Set location for highlighting in Nav Menu */
$strSelectNav = "Categories";

/* Set Page Archive Status */
if(!isset($_SESSION["CategoryView"])) { $_SESSION["CategoryView"] = "1";}
if(isset($_GET["CategoryView"])){
	$_SESSION["CategoryView"] = $_GET["CategoryView"];
}

/* Set local variable for currently viewed status to limit hits to Client scope */
if($_SESSION["CategoryView"] == "1"){
	$currentStatus = "Active";
}else{
	$currentStatus = "Archived";
}

/* ARCHIVE Record */
if(isset($_POST["UpdateCategories"])){
	/* DELETE Record */
	if(isset($_POST["deleteCategory"])){
		$deletedCategories = implode(",",$_POST["deleteCategory"]);
		$query_rsCW = "DELETE FROM tbl_prdtcategories 
		WHERE category_ID 
		IN (" . $deletedCategories . ")";
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	}
	
	if(isset($_POST["category_Archive"])) {
		$archivedCategories = implode(",",$_POST["category_Archive"]);
		$query_rsCW = sprintf("UPDATE tbl_prdtcategories 
		SET	category_archive = %s
		WHERE category_ID IN (%s)",
		$_SESSION["CategoryView"], $archivedCategories);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	}
	
	for($i=0; $i < $_POST["categoryCounter"]; $i++) {
		if($_POST["category_sortorder"][$i] == ""){$_POST["category_sortorder"][$i] = 0;}
		$query_rsCW = sprintf("UPDATE tbl_prdtcategories 
		SET	category_Name = '%s',
		category_sortorder = %s
		WHERE category_ID = %s",
		$_POST["category_Name"][$i],
		$_POST["category_sortorder"][$i],
		$_POST["category_ID"][$i]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);	
	}
	header("Location: " . $cartweaver->thisLocation);
	exit();
}

/* ADD Record */
if(isset($_POST["AddRecord"])){
	$query_rsCWCheckCategory = sprintf("SELECT category_ID, 
	category_Name, 
	category_sortorder,
	category_archive 
	FROM tbl_prdtcategories
	WHERE category_Name ='%s'", $_POST["category_Name"]);
	$rsCWCheckCategory = $cartweaver->db->executeQuery($query_rsCWCheckCategory);
	$rsCWCheckCategory_recordCount = $cartweaver->db->recordCount;
	$row_rsCWCheckCategory = $cartweaver->db->db_fetch_assoc($rsCWCheckCategory);
	
	if($rsCWCheckCategory_recordCount == 0) {
		if($_POST["category_sortorder"] == ""){$_POST["category_sortorder"] = 0;}
		$query_rsCW = sprintf("INSERT INTO tbl_prdtcategories (
		category_Name, 
		category_sortorder, 
		category_archive) 
		VALUES ('%s','%s',0)",$_POST["category_Name"], $_POST["category_sortorder"]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);	
		header("Location: " . $cartweaver->thisLocation);
		exit();
	}else{
		$adderror="Category *" . stripslashes($_POST["category_Name"]) . "* already exists in the database.";
	}
}

/* Get Record */
$query_rsCWCategoryList = sprintf("SELECT category_ID, category_Name, category_sortorder, category_archive
FROM tbl_prdtcategories
WHERE category_archive <> %s
ORDER BY category_sortorder",$_SESSION["CategoryView"]);
$rsCWCategoryList = $cartweaver->db->executeQuery($query_rsCWCategoryList);
$rsCWCategoryList_recordCount = $cartweaver->db->recordCount;
$row_rsCWCategoryList = $cartweaver->db->db_fetch_assoc($rsCWCategoryList);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CW Admin: Categories</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="assets/admin.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php include("CWIncNav.php");?>
<div id="divMainContent">
  <h1>Categories</h1>
  <p>
    <?php if($currentStatus == "Active") {
     	echo('<a href="' . $cartweaver->thisPage . '?CategoryView=0">View Archived</a>');
      }else{
      	echo('<a href="' . $cartweaver->thisPage . '?CategoryView=1">View Active</a>');
    } ?>
  </p>
  <?php
/* If we are viewing ACTIVE records show the ADD NEW form */
if($currentStatus == "Active") {?>
  <form name="Add" method="POST" action="<?php echo($cartweaver->thisPage);?>">
    <?php 
  	if(isset($adderror)) {
    	echo($adderror);
	}?>
    <table>
      <caption>
      Add Category
      </caption>
      <tr align="center">
        <th>Category</th>
        <th>Sort</th>
        <th>Add</th>
      </tr>
      <tr align="center" class="altRowEven">
        <td><input name="category_Name" type="text" size="15" ></td>
		<td><input name="category_sortorder" type="text" size="3" value="0"></td>
        <td><input name="AddRecord" type="submit" class="formButton" id="AddRecord" value="Add">
        </td>
      </tr>
    </table>
  </form>
  <?php }?>
  <?php
if($rsCWCategoryList_recordCount != 0) { ?>
<form action="<?php echo($cartweaver->thisPage);?>" method="post" name="frmCategory">
  <table>
    <caption>
    Current Categories
    </caption>
    <tr>
      <th align="center">Category</th>
      <th align="center">Sort</th>
      <th align="center">Delete</th>
      <th align="center"><?php if($currentStatus == "Active"){
          echo("Archive");
          }else{
          echo("Activate");
        }?></th>
    </tr>
    <?php $recCounter = 0;
	do {
	  /* Check to see if the Category is associated with any Products. */
		$query_rsCWProductCategories = sprintf("SELECT product_Name 
		FROM tbl_prdtcat_rel r
		INNER JOIN tbl_products p
		ON r.prdt_cat_rel_Product_ID = p.product_ID 
		WHERE prdt_cat_rel_Cat_ID = %s 
		ORDER BY product_Name",$row_rsCWCategoryList["category_ID"]);
		$rsCWProductCategories = $cartweaver->db->executeQuery($query_rsCWProductCategories);
		$rsCWProductCategories_recordCount = $cartweaver->db->recordCount;
		$row_rsCWProductCategories = $cartweaver->db->db_fetch_assoc($rsCWProductCategories);
?>
    <tr class="<?php cwAltRow($recCounter++);?>">
      <td><?php echo($recCounter);?><input type="hidden" name="category_ID[]" value="<?php echo($row_rsCWCategoryList["category_ID"]);?>">
	  <input name="category_Name[]" type="text" size="25" value="<?php echo($row_rsCWCategoryList["category_Name"]);?>"> </td>
      <td><input name="category_sortorder[]" type="text" size="3"  value="<?php echo($row_rsCWCategoryList["category_sortorder"]);?>"></td>
      <td align="center"><input type="checkbox" class="formCheckbox" name="deleteCategory[]" value="<?php echo($row_rsCWCategoryList["category_ID"]);?>"<?php if($rsCWProductCategories_recordCount != 0) {echo(" disabled");}?>></td>
	  <td align="center"><input type="checkbox" class="formCheckbox" name="category_Archive[]" value="<?php echo($row_rsCWCategoryList["category_ID"]);?>"></td>
    </tr>
    <?php } while ($row_rsCWCategoryList = $cartweaver->db->db_fetch_assoc($rsCWCategoryList)); ?>
  </table>
  <input type="hidden" name="categoryCounter" value="<?php echo($rsCWCategoryList_recordCount);?>">
  <input type="submit" name="UpdateCategories" value="Update Categories" class="formButton">
</form>
  <?php 
}else{
	echo("There are no records to be displayed.");
}/* END IF - rsCWCategoryList.RecordCount NEQ 0 */
  ?>
</div>
</body>
</html>
<?php
cwDebugger($cartweaver);
?>
