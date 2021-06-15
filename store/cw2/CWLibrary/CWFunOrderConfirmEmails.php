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

Cartweaver Version: 2.4  -  Date: 11/27/2005
================================================================
Name: CWFunOrderConfirmEmails.php
Description: This page sends both confirmation emails to the 
customer and the store merchant.
================================================================
*/

function confirmEmails($customerEmail,$emailContents,$paymentAuth,$htmlMessage=null) {
	global $_SESSION;

	/* Email confirmation notice to customer */
	//$mailServer = $_SESSION["mailserver"];
	$subject = "Your Order From " . $_SESSION["companyname"];
	$companyEmail = $_SESSION["companyemail"];
	$company = $_SESSION["companyname"];	$htmlMessage = nl2br($emailContents);
	/* If you're using a payment gateway */
	if (strtolower($paymentAuth) == "gateway") {
		$textMessage = "Your order has been received and will be shipped to you shortly! 
		Your details are as follows.\r\n" . $emailContents . "
		
		Thank you!";		
	}else{/* If you're using anything other than a payment gateway */
		$textMessage = "Your order has been received.
		
		As soon as your payment is verified you will recive a confirmation notice and 
		your order will be shipped! Your order details are as follows: " . $emailContents . "
		Thank you!";
	} /* END if (strtolower($paymentAuth) == "gateway") */
	if(!isset($htmlMessage)) {
			$htmlMessage = nl2br($textMessage);
	}
	sendEmail($customerEmail,$companyEmail,$subject,$textMessage,$htmlMessage);
	/* "You have an order" Notification sent to Merchant */
	$subject="Merchant Order Notification";
	$emailContents = "You have just received an order. The order details are as follows:" . $emailContents;
	sendEmail($companyEmail,$companyEmail,$subject,$emailContents,$htmlMessage);
}
?>