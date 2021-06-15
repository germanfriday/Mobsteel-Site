<?php

require_once("application.php");



if(isset($_SESSION["LoggedIn"]) && $_SESSION["LoggedIn"] == 1) {

	if($cartweaver->thisPageName != "index.php") {

		header("Location: " . $cartweaver->thisLocationQS);

		exit();

	}else{

		header("Location: AdminHome.php");

		exit();

	}

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

Name: index.php -

Description: Admin log on page

================================================================

*/



if(isset($_POST["username"])) {

  /* Query the database to see if the user is registered */

	$query_getLogOn = "SELECT admin_UserID, admin_User, admin_UserName, admin_Password 

	,admin_LoginDate, admin_LastLogin

	FROM tbl_adminusers 

	WHERE admin_UserName = '" . $_POST["username"] . "'

	AND admin_Password ='" . $_POST["password"] . "'";

	$getLogOn = $cartweaver->db->executeQuery($query_getLogOn);

	$getLogOn_recordCount = $cartweaver->db->recordCount;

	$row_getLogOn = $cartweaver->db->db_fetch_assoc($getLogOn);



	$lastLogin = $row_getLogOn["admin_LoginDate"];	

	if($lastLogin == ""){

		$lastLogin = strtotime("2002-01-01 01:00:00");

	}



  /* Record found, login  */

	if($getLogOn_recordCount != 0){

		/* Set the session vars */

		$_SESSION["LoggedIn"] = 1;

		/* This session store the username */

		$_SESSION["LoggedUser"] =  $getLogOn["admin_UserName"];

		$_SESSION["LastLogin"] = $lastLogin;

		/* Store username inside a cookie if required */

		if(isset($_POST["remember_me"])){

			setcookie("CWAdminUsername", $_POST["username"], mktime(12,0,0,1, 1, 2020));

		/* Else, clean any existing cookie */

		}else{

			setcookie("CWAdminUsername", "", mktime(12,0,0,1, 1, 1990));

		}

		/* Update user logon date */

		$query_rsCW = "UPDATE tbl_adminusers 

		SET admin_LastLogin = '$lastLogin', 

		admin_LoginDate = now()";

		$rsCW = $cartweaver->db->executeQuery($query_rsCW);



		/* If the user requested a specific page, redirect there */

		if(isset($_POST["redirect_to"])){

			// use Refresh so cookies will be set

			header("Refresh: 0; URL=" . $_POST["redirect_to"]);

			exit();

		}else{

			header("Refresh: 0; URL=AdminHome.php");

			exit();

		}

	/* Login failed */

	}else{ // if($getLogOn_RecordCount != 0){

	/* Display an error message */

		$logOnError = "Log on unsuccessful. No match was found. Please try again or contact administrator.";

	} // end if($getLogOn_RecordCount != 0){

} // end if isset $_POST["username"]

if(!isset($_COOKIE["CWAdminUsername"])){

	setcookie("CWAdminUsername", "", mktime(12,0,0,1, 1, 2004));

}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

<title>CW Admin: Log In</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="assets/admin.css" rel="stylesheet" type="text/css">

</head>

<body onLoad="document.login.<?php echo(isset($_COOKIE["CWAdminUsername"]) ? "password" : "username");?>.focus();">

<div id="divMainContent" style="margin:20px;">

  <?php if(isset($logOnError)){ 

	echo("<p><strong>$logOnError</strong></p>");

}?>

  <img src="assets/images/logo.gif" width="168" height="87">

  <form action="<?php echo($cartweaver->thisPageName);?>" method="post" name="login" id="login">

    <h1>Mobsteel Administration Log In</h1>

    <table>

      <tr>

        <th align="right">Username:</th>

        <td><input name="username" type="text" id="username" value="<?php echo(isset($_COOKIE["CWAdminUsername"]) ? $_COOKIE["CWAdminUsername"] : "");?>">

        </td>

      </tr>

      <tr>

        <th align="right">Password:</th>

        <td><input name="password" type="password" id="password">

          <input name="remember_me" type="checkbox" class="formCheckbox" value="1"<?php echo(isset($_COOKIE["CWAdminUsername"]) && $_COOKIE["CWAdminUsername"] != "") ? " CHECKED" : "";?>>

          Remember me </td>

      </tr>

    </table>

    <input name="Submit" type="submit" class="formButton" value="Log In">

    <?php 

    /* Store the path to the requested page inside an hidden field */ 

	if(isset($_GET["accessdenied"])){

		echo('<input type="hidden" name="redirect_to" value="' . $_GET["accessdenied"] . '">');

	}

	?>
  </form>

  </div>

</body>

</html>

<?php

cwDebugger($cartweaver);

?>