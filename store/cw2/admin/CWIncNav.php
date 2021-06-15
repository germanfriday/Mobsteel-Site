<script language="JavaScript" type="text/javascript" src="assets/global.js"></script>
<div id="divHelp" style="float: right; margin-right: 10px;">
	<a href="helpfiles/AdminHelp.php?helpFileName=<?php echo($cartweaver->thisPageName);?>" target="_blank"> <img src="assets/images/cwContextHelp.gif" alt="Get Help" width="16" height="16" align="absmiddle"></a>
</div>
<div id="leftNav">
<div id="divLogo"><a href="AdminHome.php"><img src="assets/images/logo.gif" alt="Cartweaver Logo" width="168" height="87" border="0" id="imgLogo" /></a></div>
	<a href="javascript:;" id="lnProducts" onClick="dwfaq_ToggleOMaticClass(this,'lnProducts','leftNavOpen');dwfaq_ToggleOMaticDisplay(this,'lnSubProducts');return document.MM_returnValue" class="leftNav">Products</a>
	<div id="lnSubProducts" class="lnSubMenu" style="display: none;">
		<a href="ProductForm.php">&#8211;Add New</a>
		<a href="ProductActive.php?status=0">&#8211;Active Products</a>
		<a href="ProductActive.php?status=1">&#8211;Archived Products</a>
	</div>
	
	<a href="javascript:;" class="leftNav" id="lnOrders" onClick="dwfaq_ToggleOMaticClass(this,'lnOrders','leftNavOpen');dwfaq_ToggleOMaticDisplay(this,'lnSubOrders');return document.MM_returnValue">Orders</a>
	<div id="lnSubOrders" class="lnSubMenu" style="display: none;">
		<a href="Orders.php">&#8211;Search By Date</a>
		<?php echo($_SESSION["ShipStatusMenu"]);?>
	</div>
	
	<a href="javascript:;" class="leftNav" id="lnCustomers" onClick="dwfaq_ToggleOMaticClass(this,'lnCustomers','leftNavOpen');dwfaq_ToggleOMaticDisplay(this,'lnSubCustomers');return document.MM_returnValue">Customers</a>
 	<div id="lnSubCustomers" class="lnSubMenu" style="display: none;">
		<a href="Customers.php">&#8211;Customer Search</a>
	</div>
	
		<a href="javascript:;" class="leftNav" id="lnCategories" onClick="dwfaq_ToggleOMaticClass(this,'lnCategories','leftNavOpen');dwfaq_ToggleOMaticDisplay(this,'lnSubCategories');return document.MM_returnValue">Categories</a>
 	<div id="lnSubCategories" class="lnSubMenu" style="display: none;">
		<a href="ListCategories.php">&#8211;Main</a>
		<a href="ListScndCategories.php">&#8211;Secondary</a>
	</div>
	
	<a href="javascript:;" class="leftNav" id="lnOptions" onClick="dwfaq_ToggleOMaticClass(this,'lnOptions','leftNavOpen');dwfaq_ToggleOMaticDisplay(this,'lnSubOptions');return document.MM_returnValue">Options</a>
	<div id="lnSubOptions" class="lnSubMenu" style="display: none;">
		<a href="Options.php">&#8211;Add New</a>
		<?php echo($_SESSION["OptionsMenu"]);?>
	</div>

	<a href="javascript:;" class="leftNav" id="lnShippingTax" onClick="dwfaq_ToggleOMaticClass(this,'lnShippingTax','leftNavOpen');dwfaq_ToggleOMaticDisplay(this,'lnSubShippingTax');return document.MM_returnValue">Shipping/Tax</a>
	<div id="lnSubShippingTax" class="lnSubMenu" style="display: none;">
		<a href="ShipSettings.php">&#8211;Settings</a>
		<a href="ShipMethods.php">&#8211;Methods</a>
		<a href="ShipWeightRange.php">&#8211;Weight Range</a>
		<a href="ShipStateProv.php">&#8211;Tax/Extension</a>
	</div>
	
	<a href="javascript:;" class="leftNav" id="lnSettings" onClick="dwfaq_ToggleOMaticClass(this,'lnSettings','leftNavOpen');dwfaq_ToggleOMaticDisplay(this,'lnSubSettings');return document.MM_returnValue">Store Settings</a>
	<div id="lnSubSettings" class="lnSubMenu" style="display: none;">
		<a href="ListAdminUsers.php">&#8211;Admin Users</a>
		<a href="CompanyInfo.php">&#8211;Company Info.</a>
		<a href="ListCountry.php">&#8211;Countries</a>
		<a href="ListCreditCard.php">&#8211;Credit Cards</a>
		<a href="ListShipStatus.php">&#8211;Ship/Order Status</a>
		<a href="Settings.php">&#8211;Other Settings</a>
	</div>

	<a href="javascript:;" class="leftNav" id="lnHelp" onClick="dwfaq_ToggleOMaticClass(this,'lnHelp','leftNavOpen');dwfaq_ToggleOMaticDisplay(this,'lnSubHelp');return document.MM_returnValue">Help</a>
	<div id="lnSubHelp" class="lnSubMenu">
		<a href="helpfiles/AdminHelp.php?helpFileName=<?php echo($cartweaver->thisPageName);?>" target="_blank">&#8211;Get Help</a>
	</div>
	
	<?php if($_SESSION["companyemail"] == "support@cartweaver.com") {?>
	<p class="smallprint"><strong>Please change your <a href="CompanyInfo.php">company email</a>. It is currently set to the Cartweaver default value.</strong></p>
	<?php } /* END if($_SESSION["companyemail"] == "support@cartweaver.com") */ ?>

<div id="logOut">
<form name="theFrom" method="post" action="<?php echo($cartweaver->thisLocation . "?logout=true");?>">
  <input type="submit" name="Submit" value="Log Out" class="formButton">
</form>
</div>
</div>
<?php if(isset($strSelectNav)) { 
	echo("<script type=\"text/javascript\">\n");
	echo("<!--\n");
	echo("dwfaq_ToggleOMaticClass(this,'ln$strSelectNav','leftNavOpen');\n");
	echo("dwfaq_ToggleOMaticDisplay(this,'lnSub$strSelectNav');\n");
	echo("-->\n");
	echo("</script>\n");
}
?>
