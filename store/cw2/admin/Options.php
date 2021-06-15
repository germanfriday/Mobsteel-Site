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

Cartweaver Version: 2.2  -  Date: 09/07/2005
================================================================
Name: Options.php
Description: Control the options available for product SKUs
================================================================
*/


/* Delete this entire option group*/
if(isset($_GET["DeleteOption"])){
	$query_rsCW = "DELETE FROM tbl_list_optiontypes 
	  WHERE optiontype_ID = " . $_GET["DeleteOption"];
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	session_unregister('OptionsMenu'); 
	header("Location: Options.php");
	exit();
}


/* Set Local Variable for currently selected option */
$thisOption = "0";
/* Set local variables for doing option nav update and redirection */
$updateOptions = "0";
$redirectURL = "";

/* Set location for highlighting in Nav Menu */
$strSelectNav = "Options";

/* Set default Action value */
if(!isset($_POST["action"])) {$_POST["action"] = "";} 

/* Insert a new option type */
if($_POST["action"] == "AddOption"){
	$query_rsCW = sprintf("INSERT INTO tbl_list_optiontypes (optiontype_Name) 
	VALUES ('%s')",$_POST["option_name"]);
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	session_unregister('OptionsMenu'); 
	header("Location: Options.php");
	exit();
}


if(isset($_GET["optionID"])){
	$thisOption = $_GET["optionID"];
}elseif(isset($_POST["option_Type_ID"])){
	$thisOption = $_POST["option_Type_ID"];
}

/* Set Page Archive Status */
if(!isset($optionView)) { $optionView = "0";}
if(isset($_GET["OptionView"])){
	$optionView = $_GET["OptionView"];
}




/* ADD Record */
if($_POST["action"] == "AddRecord"){
	$_POST["option_Sort"] = intval($_POST["option_Sort"]);
	$query_rsCW = sprintf("INSERT INTO tbl_skuoptions (option_Type_ID, option_Name, option_Sort) 
	VALUES('%s','%s',%s)",$_POST["option_Type_ID"],$_POST["option_Name"],$_POST["option_Sort"]);
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	session_unregister('OptionsMenu'); 
	header("Location: " . $cartweaver->thisLocation . "?optionID=" . $thisOption);
	exit();
}


/* Update Records */
if(isset($_POST["UpdateOptions"])){
	// If any delete checkboxes are checked, delete options
	if(isset($_POST["deleteOption"])) {
		$deleteOptions = join($_POST["deleteOption"],",");
		$query_Delete = "DELETE FROM tbl_skuoptions 
		WHERE option_ID IN ($deleteOptions)";
		$rsCW = $cartweaver->db->executeQuery($query_Delete);
	}
	// If any archive checkboxes are checked, archive options
	if(isset($_POST["archiveOption"])) {
		$archiveOptions = join($_POST["archiveOption"],",");
		$query_Archive = "UPDATE tbl_skuoptions 
		SET option_Archive = " . $_POST["option_View"] . "
		WHERE option_ID IN ($archiveOptions)";
		$rsCW = $cartweaver->db->executeQuery($query_Archive);
	}
	
	// Loop through all options to update
	for($i = 0; $i<count($_POST["option_ID"]); $i++) {
		if(!isset($deleteOptions) || arrayFind($_POST["deleteOption"], $_POST["option_ID"][$i]) != -1) {
			$_POST["option_Sort"][$i] = intval($_POST["option_Sort"][$i]);
			$query_Update = sprintf("UPDATE tbl_skuoptions 
			SET option_Sort = %d, 
			option_Name = '%s'
			WHERE option_ID = %d",
			$_POST["option_Sort"][$i],
			$_POST["option_Name"][$i],		
			$_POST["option_ID"][$i]);
			$rsCW = $cartweaver->db->executeQuery($query_Update);
		}
	}	
	
	session_unregister('OptionsMenu'); 
	header("Location: " . $cartweaver->thisLocation . "?optionID=" . $thisOption);
	exit();
}

/* Get Record */
if ($thisOption != 0){
	$query_rsCWGetOptions = "SELECT s.option_Name, ot.optiontype_Name, option_ID, s.option_Sort
	  FROM tbl_list_optiontypes ot
	  INNER JOIN tbl_skuoptions s
	  ON ot.optiontype_ID = s.option_Type_ID 
	  WHERE ot.optiontype_ID = $thisOption 
	  AND s.option_Archive = $optionView 
	  ORDER BY s.option_Sort";
	$rsCWGetOptions = $cartweaver->db->executeQuery($query_rsCWGetOptions);
	$rsCWGetOptions_recordCount = $cartweaver->db->recordCount;
	$row_rsCWGetOptions = $cartweaver->db->db_fetch_assoc($rsCWGetOptions);
	if($rsCWGetOptions_recordCount == 0) {
    /* Get Option Name if there are no records from rsCWGetOptions */
		$query_rsCWOptionName = "SELECT optiontype_Name 
		FROM tbl_list_optiontypes 
		WHERE optiontype_ID = $thisOption";
		$rsCWOptionName = $cartweaver->db->executeQuery($query_rsCWOptionName);
		$rsCWOptionName_recordCount = $cartweaver->db->recordCount;
		$row_rsCWOptionName = $cartweaver->db->db_fetch_assoc($rsCWOptionName);
		$thisOptionName = $row_rsCWOptionName["optiontype_Name"];
		/* Check to see if there are archived options to hide Delete Option if necessary */
		$query_rsCWArchivedOptions = "SELECT option_ID 
		FROM tbl_skuoptions 
		WHERE option_type_ID = $thisOption 
		AND option_Archive = 1";
		$rsCWArchivedOptions = $cartweaver->db->executeQuery($query_rsCWArchivedOptions);
		$rsCWArchivedOptions_recordCount = $cartweaver->db->recordCount;
		$row_rsCWArchivedOptions = $cartweaver->db->db_fetch_assoc($rsCWArchivedOptions);		
    }else{
    	$thisOptionName = $row_rsCWGetOptions["optiontype_Name"];
  	}
}else{
  $thisOptionName = "Add New Option Group";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CW Admin: <?php echo($thisOptionName);?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="assets/admin.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php include("CWIncNav.php");?>

<div id="divMainContent">
  <?php 
if($thisOption != 0) { ?>
    <h1>Option Group: <?php echo($thisOptionName);?></h1>
    <p><?php 
	if($optionView == "0") {
		echo('<a href="' . $cartweaver->thisPage . "?OptionView=1&optionID=$thisOption\">View Archived</a>");
	}else{
		echo('<a href="' . $cartweaver->thisPage . "?OptionView=0&optionID=$thisOption\">View Active</a>");
	}?>
    </p>
	<?php if ($optionView == 0) { ?>
    <table>
      <caption>
      Add <?php echo($thisOptionName);?>
      </caption>
      <form name="Add" method="POST" action="<?php echo($cartweaver->thisPage . "?optionID=" . $thisOption);?>">
        <tr align="center">
          <th><?php echo($thisOptionName)?></th>
          <th>Sort</th>
          <th>Add</th>
        </tr>
        <tr align="center">
          <td><input name="option_Name" type="text">
          </td>
          <td><input name="option_Sort" type="text" size="5">
            <input name="option_Type_ID" type="hidden" id="option_Type_ID" value="<?php echo($thisOption);?>">
          </td>
          <td><input name="AddRecord" type="submit" class="formButton" value="Add">
          </td>
        </tr>
		<input name="action" type="hidden" value="AddRecord">
      </form>
    </table>
   <?php } /* END if ($optionView == 0)*/ ?>
   <?php /* Show table only if we have options */
	if ($rsCWGetOptions_recordCount != 0) { ?>
      <form action="<?php echo($cartweaver->thisPage);?>" method="POST" name="Update">
	  <table>
        <caption>
        Current Option Values
        </caption>
        <tr align="center">
          <th><?php echo($thisOptionName);?> </th>
          <th>Sort</th>
          <th>Delete</th>
          <th>
            <?php echo ($optionView == "0") ? "Archive" : "Activate"; ?>
          </th>
        </tr>
        <?php 
	$recCounter = 0;
	do {
		/* Check to see if this option is associated with a SKU. */
		$query_rsCWCheckSKUOptions = "SELECT Count(optn_rel_sku_id) as AreThereSkus 
		FROM tbl_skuoption_rel 
		WHERE optn_rel_Option_ID = " . $row_rsCWGetOptions["option_ID"];
		$rsCWCheckSKUOptions = $cartweaver->db->executeQuery($query_rsCWCheckSKUOptions);
		$rsCWCheckSKUOptions_recordCount = $cartweaver->db->recordCount;
		$row_rsCWCheckSKUOptions = $cartweaver->db->db_fetch_assoc($rsCWCheckSKUOptions);
		
		$query_rsCWCheckProductOptions = sprintf("SELECT Count(optn_rel_sku_id) AS AreThereOptions 
		FROM tbl_products p
		INNER JOIN tbl_skus s
		ON p.product_ID = s.SKU_ProductID 
		INNER JOIN tbl_skuoption_rel r
		ON s.SKU_ID = r.optn_rel_SKU_ID
		WHERE r.optn_rel_Option_ID = %s 
		AND p.product_Archive = 0",$row_rsCWGetOptions["option_ID"]);
		$rsCWCheckProductOptions = $cartweaver->db->executeQuery($query_rsCWCheckProductOptions);
		$rsCWCheckProductOptions_recordCount = $cartweaver->db->recordCount;
		$row_rsCWCheckProductOptions = $cartweaver->db->db_fetch_assoc($rsCWCheckProductOptions);	

?>					
          <tr class="<?php cwAltRow($recCounter++);?>">
            
              <td><?php echo($recCounter);?>. <input name="option_Name[]" required="yes" message="Option Name Required - Please insert a value" type="text" value="<?php echo($row_rsCWGetOptions["option_Name"]);?>" size="25">
                <input name="option_ID[]" type="hidden" id="option_ID" value="<?php echo($row_rsCWGetOptions["option_ID"]);?>">
              </td>
              <td><input name="option_Sort[]" required="yes" validate="integer" message="Sort Required - Must be Numeric Value" type="text" id="option_Sort" value="<?php echo($row_rsCWGetOptions["option_Sort"]);?>" size="3">
              </td>             
              <td align="center">
			  <input type="checkbox" name="deleteOption[]" class="formCheckbox" value="<?php echo($row_rsCWGetOptions["option_ID"]);?>" <?php if ($row_rsCWCheckSKUOptions["AreThereSkus"] != 0) {echo(' disabled="disabled"');}?> /></td>
              <td align="center">
			  <input type="checkbox" name="archiveOption[]" class="formCheckbox" value="<?php echo($row_rsCWGetOptions["option_ID"]);?>" <?php if ($row_rsCWCheckProductOptions["AreThereOptions"] != 0) {echo(' disabled="disabled"');}?> />
			  </td>            
          </tr>
         <?php } while ($row_rsCWGetOptions = $cartweaver->db->db_fetch_assoc($rsCWGetOptions)); ?>
      </table>
	  	<input name="option_Type_ID" type="hidden" id="option_Type_ID" value="<?php echo($thisOption);?>" />
		<input type="submit" name="UpdateOptions" value="Update Options" class="formButton" /> 
		<input type="hidden" name="action" value="update options" />
		<input type="hidden" name="option_View" value="<?php echo(($optionView == 0) ? "1" : "0");?>"/>
	  </form>
      <?php 
	} else {/* ELSE if ($rsCWGetOptions_recordCount != 0) */
		if($optionView == 0) {
			if ($rsCWArchivedOptions_recordCount == 0) {
				echo('<p>There are currently no options available.<br>
				Would you like to [<a href="' . $cartweaver->thisPage . '?DeleteOption=' . $thisOption . '">DELETE OPTION</a>]</p>');
			}else{
				echo("<p>Delete all active and archived options to delete this option group.</p>");
			}
		}else {
			echo("<p>There are currently no archived options.</p>");
		} /* end if($optionView == 0 )*/
	} /* end if ($rsCWGetOptions_recordCount != 0) */
}else{ ?>
    <h1>Add New Option Group</h1>
    <form action="Options.php" method="post" name="frmAddOptions" id="frmAddOptions">
      <p>
        <label>Option Name:
        <input name="option_name" type="text" id="option_name">
        <input name="AddOption" type="submit" class="formButton" id="subAddOption" value="Add Option">
		<input type="hidden" name="action" value="AddOption">
        </label>
      </p>
    </form>
  <?php 
} /* end if($thisOption != 0) */
?>
</div>
</body>
</html>
<?php
cwDebugger($cartweaver);
?>