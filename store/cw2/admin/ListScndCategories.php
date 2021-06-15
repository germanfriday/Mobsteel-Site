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
Name: ListScndCategories.php
Description: List secondary categories
================================================================
*/
/* Set location for highlighting in Nav Menu */
$strSelectNav = "Categories";

/* Set Page Archive Status */
if(!isset($_SESSION["ScndCategoryView"])) { $_SESSION["ScndCategoryView"] = "1";}
if(isset($_GET["ScndCategoryView"])){
  $_SESSION["ScndCategoryView"] = $_GET["ScndCategoryView"];
}

/* Set local variable for currently viewed status to limit hits to Client scope */
if($_SESSION["ScndCategoryView"] == "1"){
  $currentStatus = "Active"; 
}else{
  $currentStatus = "Archived";
}

/* ARCHIVE Record */
if(isset($_POST["UpdateCategories"])){
	/* First, DELETE Records with checkboxes */
	if(isset($_POST["deleteCategory"])){
		$deletedCategories = implode(",",$_POST["deleteCategory"]);
		$query_rsCW = "DELETE FROM tbl_prdtscndcats 
		WHERE scndctgry_ID 
		IN (" . $deletedCategories . ")";
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	}

	if(isset($_POST["scndctgry_Archive"])) {
		$archivedCategories = implode(",",$_POST["scndctgry_Archive"]);
		$query_rsCW = sprintf("UPDATE tbl_prdtscndcats 
		SET	scndctgry_Archive = %s
		WHERE scndctgry_ID IN (%s)",
		$_SESSION["ScndCategoryView"], $archivedCategories);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	}
	
	for($i=0; $i < $_POST["categoryCounter"]; $i++) {
		if($_POST["scndctgry_Sort"][$i] == ""){$_POST["scndctgry_Sort"][$i] = 0;}
		$query_rsCW = sprintf("UPDATE tbl_prdtscndcats 
		SET	scndctgry_Name = '%s',
			scndctgry_Sort = %s
			WHERE scndctgry_ID = %s",
			$_POST["scndctgry_Name"][$i],
			$_POST["scndctgry_Sort"][$i],
			$_POST["scndctgry_ID"][$i]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);	
	}
	header("Location: " . $cartweaver->thisLocation);
	exit();
}

/* ADD Record */
if(isset($_POST["AddRecord"])){
	$query_rsCWCheckScndCategory = sprintf("SELECT scndctgry_ID, scndctgry_Name, scndctgry_Sort, scndctgry_Archive
	FROM tbl_prdtscndcats
	WHERE scndctgry_Name ='%s'",$_POST["ScndCategory_Name"]);
	$rsCWCheckScndCategory = $cartweaver->db->executeQuery($query_rsCWCheckScndCategory);
	$rsCWCheckScndCategory_recordCount = $cartweaver->db->recordCount;
	$row_rsCWCheckScndCategory = $cartweaver->db->db_fetch_assoc($rsCWCheckScndCategory);
	if($rsCWCheckScndCategory_recordCount == 0){
		if($_POST["scndctgry_Sort"] == ""){$_POST["scndctgry_Sort"] = 0;}
		$query_rsCW = sprintf("INSERT INTO tbl_prdtscndcats (scndctgry_Name,scndctgry_Sort) 
			VALUES ('%s','%s')",$_POST["ScndCategory_Name"],$_POST["scndctgry_Sort"]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
		header("Location: " . $cartweaver->thisLocation);
		exit();
	}else{
		$adderror="Secondary Category *" . stripslashes($_POST["ScndCategory_Name"]) . "* already exists in the database.";
	}
}

/* Get Record */
$query_rsCWScndCategoryList = sprintf("SELECT scndctgry_ID, scndctgry_Name, scndctgry_Sort, scndctgry_Archive
FROM tbl_prdtscndcats
WHERE scndctgry_Archive <> %s
ORDER BY scndctgry_Sort",$_SESSION["ScndCategoryView"]);
$rsCWScndCategoryList = $cartweaver->db->executeQuery($query_rsCWScndCategoryList);
$rsCWScndCategoryList_recordCount = $cartweaver->db->recordCount;
$row_rsCWScndCategoryList = $cartweaver->db->db_fetch_assoc($rsCWScndCategoryList);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CW Admin: Secondary Categories</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="assets/admin.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
function confirmDelete(obj,str,numberProducts){
	if(obj.checked){
		clickConfirm = confirm("There are products associated with this category, are you sure you want to delete this category?\nThere are "+numberProducts+" affected products:\n"+str);
		if(!clickConfirm){obj.checked = false;}
	}
}
</script>

</head>
<body>
<?php include("CWIncNav.php");?>
<div id="divMainContent">
  <h1>Secondary Categories</h1>
  <p><?php 
if($currentStatus == "Active"){
	echo('<a href="' . $cartweaver->thisPage . '?ScndCategoryView=0">View Archived</a>');
}else{
	echo(' <a href="' . $cartweaver->thisPage . '?ScndCategoryView=1">View Active</a>');
}?></p>
<?php if($currentStatus == "Active"){ ?>
  <form name="Add" method="post" action="<?php echo($cartweaver->thisPage);?>">
<?php 
if(isset($adderror)) { 
	echo($adderror);
}?>
    <table>
      <caption>
      Add Secondary Category
      </caption>
      <tr align="center">
        <th>Secondary Category</th>
        <th>Sort</th>
        <th>Add</th>
      </tr>
      <tr align="center" class="altRowEven">
        <td><input name="ScndCategory_Name" type="text" size="15">
        </td>
        <td><input name="scndctgry_Sort" type="text" size="3" value="0"></td>
		<td><input name="AddRecord" type="submit" class="formButton" id="AddRecord" value="Add">
        </td>
      </tr>
    </table>
  </form>
<?php } /* END IF - CurrentStatus EQ "Active" */ 

if($rsCWScndCategoryList_recordCount != 0) { ?>
<form action="<?php echo($cartweaver->thisPage);?>" method="post" name="frmSecondary">
  <table>
    <caption>
    Current Secondary Categories
    </caption>
    <tr>
      <th align="center">Secondary Category</th>
      <th align="center">Sort</th>
      <th align="center">Delete</th>
      <th align="center"><?php echo($currentStatus == "Active") ? "Archive" : "Activate";?></th>
    </tr>
    <?php 
	$recCounter = 0;
    $currentRow = 0;
	do {
	  	/* Check to see if the Category is associated with any Products. */
		$query_rsCWProductScndCategories = "SELECT DISTINCT s.scndctgry_ID
		FROM tbl_products p 
		INNER JOIN tbl_prdtscndcat_rel r
			ON p.product_ID = r.prdt_scnd_rel_Product_ID
		INNER JOIN tbl_prdtscndcats s
			ON s.scndctgry_ID = r.prdt_scnd_rel_ScndCat_ID 
		WHERE s.scndctgry_ID = " . $row_rsCWScndCategoryList["scndctgry_ID"];
		$rsCWProductScndCategories = $cartweaver->db->executeQuery($query_rsCWProductScndCategories);
		$rsCWProductScndCategories_recordCount = $cartweaver->db->recordCount;
		$row_rsCWProductScndCategories = $cartweaver->db->db_fetch_assoc($rsCWProductScndCategories);
		?>
    <tr class="<?php cwAltRow($recCounter++);?>">
      <td><?php echo($recCounter);?><input type="hidden" name="scndctgry_ID[]" value="<?php echo($row_rsCWScndCategoryList["scndctgry_ID"]);?>">
	  <input name="scndctgry_Name[]" type="text" size="25" value="<?php echo($row_rsCWScndCategoryList["scndctgry_Name"]);?>"> </td>
      <td><input name="scndctgry_Sort[]" type="text" size="3"  value="<?php echo($row_rsCWScndCategoryList["scndctgry_Sort"]);?>"></td>
      <td align="center"><input type="checkbox" class="formCheckbox" name="deleteCategory[]" value="<?php echo($row_rsCWScndCategoryList["scndctgry_ID"]);?>"<?php if($rsCWProductScndCategories_recordCount != 0) {echo(" disabled");}?>></td>
	  <td align="center"><input type="checkbox" class="formCheckbox" name="scndctgry_Archive[]" value="<?php echo($row_rsCWScndCategoryList["scndctgry_ID"]);?>"></td>
    </tr>
    <?php } while ($row_rsCWScndCategoryList = $cartweaver->db->db_fetch_assoc($rsCWScndCategoryList)); ?>
  </table>
  <input type="hidden" name="categoryCounter" value="<?php echo($rsCWScndCategoryList_recordCount);?>">
  <input type="submit" name="UpdateCategories" value="Update Categories" class="formButton">
</form>
  <?php 
}else{
	echo("There are no records to display");
}/* END if($rsCWScndCategoryList_recordCount != 0) */
?>
</div>
</body>
</html>
<?php
cwDebugger($cartweaver);
?>
