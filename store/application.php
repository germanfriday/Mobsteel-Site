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

Name: application.php

Description: 

	The application.php sets all of the global variables used 

	throughout the Cartweaver application.

================================================================



Include this file at the top of any Cartweaver page. 

Supplies all global variables, database wrappers, and other functionality

*/



// Load settings file with defaults

require_once("cw2/CWLibrary/CWIncGlobalSettings.php"); 



//=== Start Cartweaver Variables ===

$cwGlobalSettings->hostname = "mysql127.secureserver.net";

$cwGlobalSettings->database = "newmobsteel";

$cwGlobalSettings->databaseUsername = "newmobsteel";

$cwGlobalSettings->databasePassword = "Mobsteel411";

$cwGlobalSettings->websiteURL = "http://www.mobsteel.com/store/";

$cwGlobalSettings->websiteSSLURL = "https://www.mobsteel.com/store/";

$cwGlobalSettings->onSubmitAction = "Confirm";

$cwGlobalSettings->targetResults = "results.php";

$cwGlobalSettings->recordsAtATime = 100;

$cwGlobalSettings->targetDetails = "details.php";

$cwGlobalSettings->detailsDisplay = "Simple";

$cwGlobalSettings->targetGoToCart = "showcart.php";

$cwGlobalSettings->targetCheckout = "orderform.php";

$cwGlobalSettings->targetConfirmOrder = "confirmation.php";

$cwGlobalSettings->imageThumbFolder = "cw2/assets/product_thumb/"; 

$cwGlobalSettings->imageLargeFolder = "cw2/assets/product_full/";

$cwGlobalSettings->cwLocale = "en_US.iso885915";

$cwGlobalSettings->paymentAuthType = "gateway"; 

$cwGlobalSettings->paymentAuthName = "CWIncAuthorizeNet.php"; 

$cwGlobalSettings->enableErrorHandling = true; //false for testing, true for live sites

$cwGlobalSettings->cwDebug = false; //true for testing, false for live sites

$cwGlobalSettings->debugPassword = "pissed";

//=== End Cartweaver Variables ===



/* Include the CWCartweaver class, which supplies all cart functionality */

require_once("cw2/CWLibrary/CWCart.php");

/* Include the CWMail class, which simplifies email functionality */

require_once("cw2/CWLibrary/CWMail.php");

/* Do application stuff to set up Cartweaver, include functions and files, and set session variables */

require_once("cw2/CWLibrary/CWIncSetup.php");

/* Include the search class */

require_once("cw2/CWLibrary/CWSearch.php");

// Include the relevant setup files

if(strtolower($cartweaver->thisPageName) == strtolower(getPageName($cartweaver->settings->targetGoToCart))){

	include("cw2/CWIncShowCartSetup.php");

}

if(strtolower($cartweaver->thisPageName) == strtolower(getPageName($cartweaver->settings->targetCheckout))){

	include("cw2/CWIncOrderFormSetup.php");

}

if(strtolower($cartweaver->thisPageName) == strtolower(getPageName($cartweaver->settings->targetDetails))){

	include("cw2/CWIncDetailsSetup.php");

}

?>