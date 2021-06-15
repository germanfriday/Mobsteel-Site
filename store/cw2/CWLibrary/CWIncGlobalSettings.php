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
Name: CWIncGlobalSettings.php
Description:
	Sets all of the global variables used throughout the
	cartweaver application. This array is called from the
	application.php file.
================================================================
*/


global $cwGlobalSettings;
/* ..................................................................... */    
/*Set the database connector file*/
$cwGlobalSettings->db = "CWDBMySQL.php";

/* Set Default attributes */ 
/* When someone clicks a "ViewCart" link they will be taken to this page. 
	  Default file name is showcart.php */ 
$cwGlobalSettings->targetGoToCart = "showcart.php";

/* When someone clicks "CheckOut" on the Show Cart page they will be taken to this page.
	  Default file name is orderform.php */
$cwGlobalSettings->targetCheckout = "orderform.php";

/* After an order is placed/submited they will be taken to this Confirmation page. 
	  Default file name is confirmation.php */
$cwGlobalSettings->targetConfirmOrder = "confirmation.php";

/* When someone Searches for a product they will be taken to this page. 
	  Default file name is results.php */ 
$cwGlobalSettings->targetResults = "results.php";

/* When someone links from Search Results to Display Product Details they will be taken to this page.
	  Default file name is details.php */ 
$cwGlobalSettings->targetDetails = "details.php";

/* Set the number of records we want to show at a time on the Search Results page*/ 
$cwGlobalSettings->recordsAtATime = 10;

/* [ OnSubmitttAction ] 
  Set what to do after adding the item to the cart. 
  The Choices are "GoTo" and "Confirm" ...
  "GoTo" will take us to the "$cartweaver->settings->targetGoToCart" 
  "Confirm" will display a confirmation - This is the Default.
 */
$cwGlobalSettings->onSubmitAction = "GoTo";

/* // Set "Location" for currency and date formatting  //  */
/* Windows choices located at 
http://msdn.microsoft.com/library/default.asp?url=/library/en-us/vclib/html/_crt_language_strings.asp  
Linux choices at:
http://www.w3.org/WAI/ER/IG/ert/iso639.htm

For more information on locales, check the PHP documentation

*/
$cwGlobalSettings->cwLocale = "english-us"; // "en_US.iso88591" on Linux

/* Now we set out Database info for the application.  */
$cwGlobalSettings->db = "CWDBMySQL.php";

$cwGlobalSettings->hostname = "";
$cwGlobalSettings->database = "";
$cwGlobalSettings->databaseUsername = "";
$cwGlobalSettings->databasePassword = "";

/* This is the mail server your application will use to send emails. */
$cwGlobalSettings->mailServer = "localhost";

/* This determines how SKUs and SKU options will be displayed on the "Details" page. 
	  The two choices are "Simple" and "Advanced" - "Advanced" is the default */
$cwGlobalSettings->detailsDisplay = "Advanced";

/* These two parameters determine the type of payment processing to handle, and
	  the filename for the include file that will handle the processing. Set both to
			none for testing purposes. 
options for paymentAuthType are processor, gateway, or none
options for paymentAuthName are 
CWIncPayPal.php -- processor
CWIncAuthorizeNet.php -- gateway
CWIncPayFlowPro.php -- gateway
CWIncWorldPay.php -- processor
*/
$cwGlobalSettings->paymentAuthType = "none"; 
$cwGlobalSettings->paymentAuthName = "none"; 
/* Set URLs for the store pages. The websiteSSLURL variable determines where
				secure pages should be loaded. These should be absolute paths, including the
				trailing slash. */
$cwGlobalSettings->websiteURL = "";
$cwGlobalSettings->websiteSSLURL = $cwGlobalSettings->websiteURL;

/* Declare variables for thumbnail and fullsize image folders */

$cwGlobalSettings->imageThumbFolder = "/cw2/assets/product_thumb/"; 
$cwGlobalSettings->imageLargeFolder = "/cw2/assets/product_full/";

/* Turn debugging on or off ("Yes" or "No"). Off by default, but if "Yes" set up a password */
$cwGlobalSettings->cwDebug = false;
/* set up an application password for debugging and resetting application */
$cwGlobalSettings->debugPassword = "sWLus2O3";
/* CUSTOM ERROR SETTING - Set variable. 
	Custom Error to show custom error pages or not. 
      "Yes" to show custom error pages, "No" to show default errors */
$cwGlobalSettings->enableErrorHandling = true;
?>
