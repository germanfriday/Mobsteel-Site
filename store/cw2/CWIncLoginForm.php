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

Cartweaver Version: 2.3  -  Date: 09/18/2005
================================================================
Name: CWIncLoginForm.php
Description: This page displays a login form, which allows a user
to log into the site. This file can be called from anywhere within the 
Cartweaver store.
================================================================
*/

/* If the Forgot Password form has been submitted, call the "find password" include file. */ 
if(isset($_POST["forgotemailaddress"])){
	include("CWLibrary/CWFunFindPassword.php");
	$pwFound = cwFindPassword($_POST["forgotemailaddress"]);
}


// If no session is not set or customerID is 0 or a login error occurred, show login form
if (!isset($_SESSION["customerID"]) || (isset($_SESSION["customerID"]) && $_SESSION["customerID"] == "0") 
	|| isset($loginError)) {
		if(isset($loginError)) {
			$cartweaver->setCWError("LoginError", $loginError);
			$cartweaver->displayError("","LoginError","p"); // Output a login error if found
			$cartweaver->setCWError();
		}
	if(!$logged) { ?>
	<p>If you are a returning customer, please log in.</p>
<form name="login" method="post" action="<?php echo($cartweaver->thisPage);?>">
  <input name="retcustomer" type="hidden" value="yes">
  <table cellpadding="3" cellspacing="0" class="tabularData">
    <tr>
      <th align="right">Username:</th>
      <td class="altRowOdd"><input name="username" type="text">
      </td>
    </tr>
    <tr>
      <th align="right">Password:</th>
      <td class="altRowEven"><input name="password" type="password">
      </td>
    </tr>
  </table>
  <input name="Submit" type="submit" class="formButton" value="Log In">
</form>
<p>Did you forget your password?</p>
<?php
	/* Forgotten Username and password form */ 
	/* If the find password form has been submited and a match was found, display the "PWFound" message */ 
	if(isset($pwFound) && $pwFound == true){ ?>
		<p><strong>Your username and password have been sent to <?php echo($_POST["forgotemailaddress"]); ?><br>
		If this email address is no longer accessible you will need to contact customer service.</strong></p>
<?php
	}
	/* Display the forgotten password form. */ 
	if(isset($pwFound)){
		if(!$pwFound){
			/* If the find password form has been submited and a match was NOT found, 
			      display the "PWNotFound" message */ 
			echo("<p class=\"errorMessage\">Sorry, no matching record was found. Please try again.</p>");
		}else{
			echo("<p>Did you forget your password?</p>");
		}
		/* Forgotten password form */
	}?>
<form action="<?php echo($cartweaver->thisPage);?>" method="post" name="getForgotPW">
  <table border="0" cellpadding="3" cellspacing="0" class="tabularData">
    <tr>
      <th align="right">Email Address:</th>
      <td class="altRowOdd"><input name="forgotemailaddress" type="text" id="emailaddress">
      </td>
    </tr>
  </table>
  <input name="forgot" type="submit" class="formButton" id="forgot" value="Send Password">
</form>
<?php } /* END if(!$logged) */
}/* END if (!isset($_SESSION["customerID"]) || (isset($_SESSION["customerID"]) && $_SESSION["customerID"] == "0") || isset($_GET["error"]))  */ 
?>