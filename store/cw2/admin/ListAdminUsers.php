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

Cartweaver Version: 2.1  -  Date: 08/07/2005
================================================================
Name: ListAdminUsers.php
Description: Administer users with access ti admin section
================================================================
*/

/* Set location for highlighting in Nav Menu */
$strSelectNav = "Settings";

/* ADD Record */
if(isset($_POST["AddRecord"]) && $_POST["admin_User"] != "") {
 	/* Check to see if User is already in use */
	$query_checkPassword = sprintf("SELECT admin_UserID
	FROM tbl_adminusers 
	WHERE admin_UserName = '%s'", $_POST["admin_UserName"]);
	$checkPassword = $cartweaver->db->executeQuery($query_checkPassword);
	$checkPassword_recordCount = $cartweaver->db->recordCount;

  /*  If not, enter record, if it is, generate error */
	if($checkPassword_recordCount == 0) {
		$query_rsCW = sprintf("INSERT INTO tbl_adminusers (admin_User, admin_UserName, admin_Password) VALUES
			('%s', '%s', '%s')",$_POST["admin_User"],$_POST["admin_UserName"],$_POST["admin_Password"]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);
		header("Location: " . $cartweaver->thisLocation);
		exit();
  }else{
   	$userDuplicate = "User already exists, please choose another User Identification";
 	}
}
if(isset($_POST["updateUsers"])){
	for($i = 0; $i<count($_POST["admin_UserID"]); $i++) {
		/* Update Records */
		$query_rsCW = sprintf("UPDATE tbl_adminusers 
		SET admin_Password = '%s'
		WHERE admin_UserID = %d",
		$_POST["admin_Password"][$i],
		$_POST["admin_UserID"][$i]);
		$rsCW = $cartweaver->db->executeQuery($query_rsCW);			
	}
	header("Location: " . $cartweaver->thisLocation);
	exit();
}
/* DELETE Record */
if(isset($_GET["DeleteRecord"])){
	$query_rsCW = "DELETE FROM tbl_adminusers 
	WHERE admin_UserID = " . $_GET["DeleteRecord"];
	$rsCW = $cartweaver->db->executeQuery($query_rsCW);

	header("Location: " . $cartweaver->thisLocation);
	exit();
}

/* Get Admin Records */

$query_getAdminUsers = "SELECT admin_UserID, admin_User, admin_UserName, admin_Password
FROM tbl_adminusers";
$getAdminUsers = $cartweaver->db->executeQuery($query_getAdminUsers);
$getAdminUsers_recordCount = $cartweaver->db->recordCount;
$row_getAdminUsers = $cartweaver->db->db_fetch_assoc($getAdminUsers);

/* Set default value for form fields, and fill it with previous data if form has posted */
$frmUserName = isset($userDuplicate) ? $_POST["admin_UserName"] : "";
$frmPassword = isset($userDuplicate) ? $_POST["admin_Password"] : "";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CW Admin: Administrators</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="assets/admin.css" rel="stylesheet" type="text/css">
</head>
<body>


<?php include("CWIncNav.php");?>

<div id="divMainContent">
  <h1>Administrators</h1>
  <?php
  /*If the username already exists, display an error */
  if(isset($userDuplicate)) {
    echo("<p><strong>$userDuplicate</strong></p>");
  }
  ?>
  <table>
    <caption>
    Add User
    </caption>
    <form name="Add" method="POST" action="<?php echo($cartweaver->thisPage);?>">
      <tr>
        <th align="center">User</th>
        <th align="center">Username</th>
        <th align="center">Password</th>
        <th align="center">Add</th>
      </tr>
      <tr class="altRowEven">
        <td>
          <input type="text" name="admin_User" >
        </td>
        <td>
          <input name="admin_UserName" type="text" value="<?php echo($frmUserName);?>" size="10">
        </td>
        <td>
          <input name="admin_Password" type="text" value="<?php echo($frmPassword);?>" size="10">
        </td>
        <td align="center"><input name="AddRecord" type="submit" class="formButton" id="AddRecord" value="Add">
        </td>
      </tr>
    </form>
  </table>
<form action="<?php echo($cartweaver->thisPageName);?>" method="POST" name="Update"> 
  <table>
    <caption>
    Current Users
    </caption>
    <tr>
      <th align="center">User</th>
      <th align="center">Username</th>
      <th align="center">Password</th>
      <th align="center">Delete</th>
    </tr>
    <?php $recCounter = 0;
		do {
		?>
	<tr class="<?php cwAltRow($recCounter++);?>"> 
        <td><?php echo($row_getAdminUsers["admin_User"]);?>
		<input type="hidden" name="admin_UserID[]" value="<?php echo($row_getAdminUsers["admin_UserID"]);?>" /></td>
        <td><?php echo($row_getAdminUsers["admin_UserName"]);?></td>
        <td><input name="admin_Password[]" type="text" size="15" value="<?php echo($row_getAdminUsers["admin_Password"]);?>"/></td>
        <td align="center"><?php 
if($getAdminUsers_recordCount > 1) {
	echo('<a href="' . $cartweaver->thisPage . "?DeleteRecord=" . $row_getAdminUsers["admin_UserID"] . '" onclick="return confirm(\'Are you SURE you want to DELETE this record?\')"><img src="assets/images/delete.gif" alt="Delete this record" width="14" height="17"></a>');
}else{
	echo('<a href="javascript:;" onclick="return alert(\'Cannot DELETE Last Admin User record!\')"><img src="assets/images/delete-fade.gif" alt="Cannot delete last admin user record" width="14" height="17"></a>');
} ?></td>
      </tr>
    <?php } while ($row_getAdminUsers = $cartweaver->db->db_fetch_assoc($getAdminUsers)); ?>
  </table>
  <input type="submit" name="updateUsers" value="Update Users" class="formButton" />
</form>
</div>
</body>
</html>
<?php
cwDebugger($cartweaver);
?>