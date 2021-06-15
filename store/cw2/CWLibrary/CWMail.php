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
Name: CWMail.php
Description:
	Cartweaver class to send emails. 
	To use, include the file, create a new CWMail object, 
	set a few params, and execute the send() method. Minimum requirements:
	
	<?php 
	require_once("cw2/CWLibrary/CWMail.php");
	$email = new CWMail();
	$email->setTo("someone@somewhere.com");
	$email->setFrom("someone@somewhere.com");
	$email->setSubject("Testing - Inside Paypal Processing");
	$email->setText("Inside Paypal Processing.");
	$email->send();
	?>
	
	Optional params:
	
	setHtml(message) -- optional html message (for multipart messages)
	setToName(name) -- optional friendly name for TO field
	setFromName(name) -- optional friendly name for FROM field
	setReplyTo(email) -- optional reply field, if different than FROM
	setCC(email) -- optional cc field
	setBCC(email) -- optional bcc field
================================================================
*/

class CWMail {
	var  $fromAddress, 
	$toAddress, 
	$to,
	$from,
	$subject, 
	$textMessage, 
	$htmlMessage, 
	$message, 
	$header, 
	$fromName, 
	$toName,
	$cc,
	$bcc,
	$replyTo;
	
	function CWMail() {
	}
	
	function send() {
		if($this->setUpMessage())
			mail($this->to, $this->subject, $this->message, $this->header); // send the e-mail
	}
	
	function setUpMessage() {
		if($this->toAddress == "" || $this->toAddress == null) 
			return false;
		if($this->fromAddress == "" || $this->fromAddress == null) 
			return false;
		if($this->fromName == "" || $this->fromName == null) 
			$this->fromName = $this->fromAddress;
		if($this->toName == "" ||$this->toName == null) 
			$this->toName = $this->toAddress;
		$this->to = $this->toAddress;
		$this->message = "";
		$sendFrom = "From: " . $this->fromName . " <" . $this->fromAddress . ">";
		// On Windows servers it may be necessary to use the following line instead
		//$sendFrom = "From: " .  $this->fromAddress ;
		if($this->replyTo == null || $this->replyTo == "") 
			$this->replyTo = $this->fromAddress;
		$replyto = "reply-to: " . $this->replyTo;
		$this->header = $sendFrom; // set the from field in the header
		$this->header .= "\r\n"; // add a line feed
		// Add cc and bcc if available
		if($this->cc) $this->header .= "CC: " . $this->cc . "\r\n";
		if($this->bcc) $this->header .= "BCC: " . $this->bcc . "\r\n";
		
		$this->header .= $replyto; // add the reply-to header to the header
		$this->header .= "\r\n"; // add a line feed
		$this->header .= "MIME-version: 1.0\r\n"; //add the mime-version header
		
		if($this->htmlMessage) {
			$randChars = rand(1,1000000);
			$boundary = "------------wwwDOTcartweaverDOTcom_$randChars";
			$textheader = "Content-Type: text/plain; charset=us-ascii\r\n";
			$textheader .= "Content-Transfer-Encoding: 7bit";
			$multipartheader = "Content-type: multipart/alternative; ";
			$multipartheader .= "boundary=\"".$boundary."\"";
			$htmlheader = "Content-Type: text/html; charset=us-ascii\r\n";
			$htmlheader .= "Content-Transfer-Encoding: 7bit";
			$endmessage = "--\r\n\r\n-- End --";
			$this->header .= $multipartheader;
			$this->header .= "\r\n";
			$this->message .= $boundary."\r\n".$textheader."\r\n\r\n";
		}
		
		$this->message .= $this->textMessage."\r\n\r\n";
		
		if($this->htmlMessage) {
			$this->message .= "--".$boundary."\r\n".$htmlheader."\r\n\r\n";
			$this->message .= $this->htmlMessage."\r\n\r\n";
			$this->message .= "--".$boundary.$endmessage;
		}
		$this->subject = ($this->subject == "" || $this->subject == null) ? "No subject" : $this->subject;
		return true;
	}
	
	function setHtml($html) {
		$this->htmlMessage = $html;
	}
	function setText($text) {
		$this->textMessage = $text;
	}
	function setSubject($subject) {
		$this->subject = $subject;
	}
	function setTo($to) {
		$this->toAddress = $to;
	}
	function setFrom($from) {
		$this->fromAddress = $from;
	}
	function setToName($toName) {
		$this->toName = $toName;
	}
	function setFromName($fromName) {
		$this->fromName = $fromName;
	}
	function setReplyTo($reply) {
		$this->replyTo = $reply;
	}
	function setCC($cc) {
		$this->cc = $cc;
	}
	function setBCC($bcc) {
		$this->bcc = $bcc;
	}
}

?>