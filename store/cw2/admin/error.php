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

Name: Custom Error Page.

Description: Display an error on this page 

Design this to look like the rest of your site.



==========================================================

*/?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title>Cartweaver Error Notice</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="../assets/css/cartweaver.css" rel="stylesheet" type="text/css">

</head>

<body>

<div>

<h1>Mobsteel.com - ERROR NOTICE! </h1>

<p><strong>An Error has occurred!</strong></p>

<p>If you continue to receive this error, restart your browser and try again. </p>

<hr>

<p><?php echo isset($_GET["error"]) ? $_GET["error"] : "There was an error on your page";?></p>

<p><a href="index.php">HOME</a></p>

</div>

</body>

</html>

