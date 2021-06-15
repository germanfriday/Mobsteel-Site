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
Name: ListCreditCard.php
Description: list available Credit Card choices
================================================================
*/

/* Set location for highlighting in Nav Menu */
$strSelectNav = "Settings";

/* ADD Record */
if(isset($_POST["AddRecord"])){
	$query_rsCWCCardList = sprintf("SELECT ccard_ID, ccard_Name, ccard_Code, ccard_Archive
	FROM tbl_list_ccards
	WHERE ccard_Code ='%s'",$_POST["ccard_Code"]);
	$rsCWCCardList = $cartweaver->db->executeQuery($query_rsCWCCardList);
	$rsCWCCardList_recordCount = $cartweaver->db->recordCount;
	$row_rsCWCCardList = $cartweaver->db->db_fetch_assoc($rsCWCCardList);
	
	if($rsCWCCardList_recordCount == 0) {
		$query_rsCW = sprintf("INSERT INTO tbl_list_ccards (ccard_Name, ccard_Code) 
			VALUES ('%s', '%s')",$_POST["ccard_Name"],$_POST["ccard_Code"]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
		header("Location: " . $cartweaver->thisLocation);
		exit();
	}else{
		$adderror="Card Code *" . $_POST["ccard_Code"] . "* already exists in the database.";  
	} 
	 
}/* END if(isset($_POST["AddRecord"])){ */


/* Update Record */
if(isset($_POST["UpdateCards"])){
	/* DELETE Records */
	if(isset($_POST["deleteCard"])) {
		$deleteCards = join($_POST["deleteCard"],",");
		$query_rsCW = "DELETE FROM tbl_list_ccards 
		WHERE ccard_ID IN ($deleteCards)";
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	}
	for($i = 0; $i<count($_POST["ccard_ID"]); $i++) {
		$query_rsCW = sprintf("UPDATE tbl_list_ccards 
		SET ccard_Code = '%s', 
		ccard_Name = '%s' 
		WHERE ccard_ID = %s",
		$_POST["ccard_Code"][$i],
		$_POST["ccard_Name"][$i],
		$_POST["ccard_ID"][$i]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
	}
	header("Location: " . $cartweaver->thisLocation);
	exit();
}/* END if(isset($_POST["UpdateCards"])){ */

/* Get Record */
$query_rsCWCCardList = "SELECT ccard_ID, ccard_Name, ccard_Code, ccard_Archive
FROM tbl_list_ccards
ORDER BY ccard_Name";
$rsCWCCardList = $cartweaver->db->executeQuery($query_rsCWCCardList);
$rsCWCCardList_recordCount = $cartweaver->db->recordCount;
$row_rsCWCCardList = $cartweaver->db->db_fetch_assoc($rsCWCCardList);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CW Admin: Credit Cards</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="assets/admin.css" rel="stylesheet" type="text/css">
</head>
<body>

<?php include("CWIncNav.php");?>

<div id="divMainContent">
  <h1>Credit Cards</h1>
  <form name="Add" method="POST" action="<?php echo($cartweaver->thisPage);?>">
  <?php if(isset($adderror)){
	echo($adderror);
}?>
    <table>
   <caption>Add Credit Card</caption>
     <tr align="center">
        <th>Card Name</th>
        <th>Card Code</th>
        <th>Add</th>
      </tr>
      <tr align="center" class="altRowEven">
        <td><input name="ccard_Name" type="text" size="15">
        </td>
        <td><input name="ccard_Code" type="text" size="15">
        </td>
        <td>
          <input name="AddRecord" type="submit" class="formButton" id="AddRecord" value="Add">
        </td>
      </tr>
    </table>
  </form>
  <form action="<?php echo($cartweaver->thisPageName);?>" method="POST" name="Update">
  <table>
    <caption>Currently Accepted Credit Cards</caption><tr>
      <th align="center">Card Name</th>
      <th align="center">Card Code</th>
      <th align="center">Delete</th>
    </tr>
<?php 
	$recCounter = 0;
	do {
?>
      <tr class="<?php cwAltRow($recCounter++);?>">        
          <td><?php echo($recCounter);?>: <input name="ccard_Name[]" type="text" value="<?php echo($row_rsCWCCardList["ccard_Name"]);?>">
		  <input name="ccard_ID[]" type="hidden" value="<?php echo($row_rsCWCCardList["ccard_ID"]);?>"></td>
          <td><input name="ccard_Code[]" type="text" value="<?php echo($row_rsCWCCardList["ccard_Code"]);?>"></td>
          <td align="center"><input type="checkbox" class="formCheckbox" name="deleteCard[]" value="<?php echo($row_rsCWCCardList["ccard_ID"]);?>"></td>
      </tr>
    <?php } while ($row_rsCWCCardList = $cartweaver->db->db_fetch_assoc($rsCWCCardList)); ?>
  </table>
  <input name="UpdateCards" type="submit" class="formButton" value="Update Cards">
  </form>
</div>
</body>
</html>
<?php
cwDebugger($cartweaver);
?>