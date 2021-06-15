<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- TemplateBeginEditable name="doctitle" -->
<title>Mobsteel Store</title>
<!-- TemplateEndEditable -->
<link href="../application/includes/MobStoreStyles.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript" src="../application/includes/common.js"></script>
<!-- TemplateBeginEditable name="head" --><!-- TemplateEndEditable -->
<style type="text/css">
<!--
body {
	background-color: #676767;
}
-->
</style>
<script type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>
<body onload="MM_preloadImages('../application/images/faq_f2.jpg','../application/images/apparel_f2.jpg','../application/images/parts_f2.jpg','../application/images/contact_f2.jpg')">
<table width="880" height="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="40" rowspan="3" background="../application/images/left_bg.jpg">&nbsp;</td>
    <td height="110"><table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4"><a href="../application/index.php"><img src="../application/images/mob_store_header.jpg" alt="Mobsteel Store" width="800" height="110" border="0" id="Mobsteel_Store" /></a></td>
        </tr>
        <tr>
          <td width="163" background="../application/images/nav_spacer.jpg"><a href="http://www.mobsteel.biz/store/"><img src="../application/images/apparel.jpg" alt="Mobsteel Store - Apparel" width="163" height="44" border="0" id="MobsteelStroeApparel" onmouseover="MM_swapImage('MobsteelStroeApparel','','../application/images/apparel_f2.jpg',1)" onmouseout="MM_swapImgRestore()" /></a></td>
          <td width="107" background="../application/images/nav_spacer.jpg"><a href="../application/faq.php"><img src="../application/images/faq.jpg" alt="Mobsteel Store - FAQ" width="107" height="44" border="0" id="MobsteelStroeFAQ" onmouseover="MM_swapImage('MobsteelStroeFAQ','','../application/images/faq_f2.jpg',1)" onmouseout="MM_swapImgRestore()" /></a></td>
          <td width="224" background="../application/images/nav_spacer.jpg"><a href="../application/contact.php"><img src="../application/images/contact.jpg" alt="Mobsteel Store - Contact" width="145" height="44" border="0" id="MobsteelStroeContact" onmouseover="MM_swapImage('MobsteelStroeContact','','../application/images/contact_f2.jpg',1)" onmouseout="MM_swapImgRestore()" /></a></td>
          <td width="306" height="35" valign="top" background="../application/images/nav_spacer.jpg"><table width="294" height="32" border="0" cellpadding="5" cellspacing="0">
              <tr>
                <td align="right" valign="top" class="BlackText"><!-- [ START ] Place Include that will display product "Details" --><!-- [ END ] Place Include that will display product "Details" -->
                  <?php 
$cwSearchObj = new CWSearch($cartweaver);
$cwSearchObj->setSearchType("Form");
$cwSearchObj->setFormid("frmKeywords");
$cwSearchObj->setButtonLabel("Search");
$cwSearchObj->setKeywords("Yes");
$cwSearchObj->setKeywordsLabel("");
$cwSearchObj->display();
?>                </td>
              </tr>
          </table>          </td>
        </tr>
      </table></td>
    <td width="40" rowspan="3" background="../application/images/rt_bg.jpg">&nbsp;</td>
  </tr>
  <tr>
    <td height="100%" align="left" valign="top"><table width="800" height="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="149" align="left" valign="top"><!-- TemplateBeginEditable name="navigation" -->
            <table width="149" border="0" cellspacing="0" cellpadding="20">
              <tr>
                <td align="left" valign="top" class="BlackCategoryText"><a href="#">Mens</a><br />
                    <br />
                    <a href="#">Womens</a><br />
                    <br />
                    <a href="#">Accessories</a><br />
                    <br />
                    <a href="#">Hats</a><br />
                    <br />
                  <a href="#">Stickers</a></td>
              </tr>
            </table>
          <!-- TemplateEndEditable --></td>
          <td width="1" background="../application/images/vert_line.jpg"><img src="../application/images/vert_line.jpg" alt="Mobsteel" width="1" height="35" /></td>
          <td height="100%" align="left" valign="top"><!-- TemplateBeginEditable name="content" -->
            <table width="585" border="0" cellspacing="0" cellpadding="6">
              <tr>
                <td width="285" rowspan="8"><img src="../application/images/hats_flexfit_frnt.jpg" alt="Mobsteel Store - Product Image" width="285" height="200" /><br />
                    <img src="../application/images/hats_flexfit_bck.jpg" alt="Mobsteel Store - Product Image" width="285" height="200" /></td>
                <td width="300" align="left" valign="top" class="BlackText"><span class="BlackCategoryText">Gangsta' Lid</span><br />
                  Flexfit Cap </td>
              </tr>
              <tr>
                <td align="left" valign="top"><span class="BlackText"><span class="BlackTextBold">Price:</span> US $16.00 </span></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="BlackTextBold">Color:
                  <label>
                    <select name="select" class="BlackText" id="color">
                      <option value="Black">Black</option>
                      <option value="Red">Red</option>
                    </select>
                  </label></td>
              </tr>
              <tr>
                <td align="left" valign="top"><p class="BlackText">Description of product goes here. Get as detailed in description as needed. Maybe say somthing about lifestyle and how this product fits into it.</p></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="BlackText"><span class="BlackTextBold">Size:</span>
                    <select name="size" class="BlackText" id="size">
                      <option value="sm-m">SM-M</option>
                      <option value="l-xl">L-XL</option>
                  </select></td>
              </tr>
              <tr>
                <td align="left" valign="top" class="BlackTextBold">Quantity:
                  <label>
                    <input name="quantity" type="text" id="quantity" value="1" size="3" />
                  </label></td>
              </tr>
              <tr>
                <td align="left" valign="top"><label>
                  <input name="submit" type="submit" class="BlackText" id="submit" value="Purchase This Item" />
                </label></td>
              </tr>
              <tr>
                <td align="right" valign="top"><img src="../application/images/ssl.jpg" alt="Mobsteel Store - SSL" width="60" height="86" /></td>
              </tr>
              <tr>
                <td colspan="2" align="center"><img src="../application/images/credit_cards.jpg" alt="Visa Mastercard AMerican Express Accepted" width="120" height="26" /></td>
              </tr>
            </table>
          <!-- TemplateEndEditable --></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="44"><table width="800" height="44" border="0" cellpadding="10" cellspacing="0">
      
      <tr>
        <td width="149" align="center" valign="bottom" class="BlackText">&nbsp;</td>
        <td width="347" align="right" valign="bottom" class="BlackText"><img src="../application/images/imgEcheck.jpg" alt="Credit Cards" width="167" height="27" /></td>
        <td width="244" align="center" valign="bottom" class="BlackText"><a href="#" class="PageText" onclick="MM_openBrWindow('../application/privacy.php','','scrollbars=yes,resizable=yes,width=470,height=350')">Privacy Policy </a></td>
      </tr>
      <tr>
        <td colspan="3" align="right" valign="bottom" background="../application/images/footer_graphic.jpg" class="BlackText"> <span class="BlackTextBold">Mobsteel, LLC </span>702 Advance Street Brighton, MI 48116<br />
          <span class="BlackTextBold"><span class="RedTextBold">Customer Service / Order by Phone:</span> 810.333.6100 </span></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
