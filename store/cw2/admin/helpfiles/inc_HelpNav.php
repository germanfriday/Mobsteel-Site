<div id="helpNav"> 
    <h3>Help by Page Name</h3> 
<?php $callingPage = $cartweaver->thisPage;
$_GET["helpFileName"] = (isset($_GET["helpFileName"])) ? $_GET["helpFileName"] : "AdminHome.php";
/* Declare each page, text link on a line by line basis for easier reading */
$helpNavLinkString ="AdminHome.php,Admin Home,
ListAdminUsers.php,Admin Users,
ListCategories.php,Categories,
CompanyInfo.php,Company Information,
ListCountry.php,Countries,
ListCreditCard.php,Credit Cards,
CustomerDetails.php,Customer Details,
Customers.php,Customers,
Options.php,Options,
Orders.php,Orders,
OrderDetails.php,Orders Details,
ProductActive.php,Products,
ProductArchive.php,Archived Products,
ProductForm.php,Product Details,
ProductImageUpload.php,Product Image Upload,
ListShipStatus.php,Ship/Order Status,
ShipMethods.php,Shipping Methods,
ShipSettings.php,Shipping Settings,
ShipWeightRange.php,Shipping Weight Ranges,
Settings.php,Settings,
ShipStateProv.php,Tax/Extension";
/* Strip out line breaks and/or carriage returns 
to form a continuous string for the help navigation list. */
$helpNavLinkString = str_replace("\n","",$helpNavLinkString); 
$helpNavLinkString = str_replace("\r","",$helpNavLinkString);

$helpNavLinks = explode(",",$helpNavLinkString);

for($i=0; $i < count($helpNavLinks); $i = $i + 2) {
	$currentLink = $_GET["helpFileName"] == $helpNavLinks[$i] ? ' class="current" ' : "";
	echo("<a" . $currentLink . ' href="' . $callingPage . "?helpFileName=" . $helpNavLinks[$i] . '">' . 
		$helpNavLinks[$i + 1] . "</a>\n");
}
?>
</div> 