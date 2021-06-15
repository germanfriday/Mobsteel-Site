<?php require_once("application.php");?>
<?php require_once("cw2/CWLibrary/CWSearch.php");
/*
============================================================================
This is the presentation file for the Home Page. We have placed
a few example searches to demonstrate how they will look on the page.
============================================================================
*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Mobsteel Store</title>
<!-- InstanceEndEditable -->
<link href="includes/MobStoreStyles.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript" src="includes/common.js"></script>
<!-- InstanceBeginEditable name="head" -->

<meta HTTP-EQUIV="REFRESH" content="0; url=http://www.mobsteel.com/store/results.php?category=4">
<link href="cw2/assets/css/cartweaver.css" rel="stylesheet" type="text/css" />
<link href="includes/MobStoreStyles.css" rel="stylesheet" type="text/css" />
<!-- InstanceEndEditable -->
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
<body onload="MM_preloadImages('images/faq_f2.jpg','images/apparel_f2.jpg','images/parts_f2.jpg','images/contact_f2.jpg')">
<table width="880" height="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="40" rowspan="3" background="images/left_bg.jpg">&nbsp;</td>
    <td height="110"><table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4"><a href="index.php"><img src="images/mob_store_header.jpg" alt="Mobsteel Store" width="800" height="110" border="0" id="Mobsteel_Store" /></a></td>
        </tr>
        <tr>
          <td width="163" background="images/nav_spacer.jpg"><a href="http://www.mobsteel.com/store/"><img src="images/apparel.jpg" alt="Mobsteel Store - Apparel" width="163" height="44" border="0" id="MobsteelStroeApparel" onmouseover="MM_swapImage('MobsteelStroeApparel','','images/apparel_f2.jpg',1)" onmouseout="MM_swapImgRestore()" /></a></td>
          <td width="107" background="images/nav_spacer.jpg"><a href="faq.php"><img src="images/faq.jpg" alt="Mobsteel Store - FAQ" width="107" height="44" border="0" id="MobsteelStroeFAQ" onmouseover="MM_swapImage('MobsteelStroeFAQ','','images/faq_f2.jpg',1)" onmouseout="MM_swapImgRestore()" /></a></td>
          <td width="224" background="images/nav_spacer.jpg"><a href="contact.php"><img src="images/contact.jpg" alt="Mobsteel Store - Contact" width="145" height="44" border="0" id="MobsteelStroeContact" onmouseover="MM_swapImage('MobsteelStroeContact','','images/contact_f2.jpg',1)" onmouseout="MM_swapImgRestore()" /></a></td>
          <td width="306" height="35" valign="top" background="images/nav_spacer.jpg"><table width="294" height="32" border="0" cellpadding="5" cellspacing="0">
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
    <td width="40" rowspan="3" background="images/rt_bg.jpg">&nbsp;</td>
  </tr>
  <tr>
    <td height="100%" align="left" valign="top"><table width="800" height="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="149" align="left" valign="top"><!-- InstanceBeginEditable name="navigation" -->
            <table width="149" border="0" cellspacing="0" cellpadding="20">
              <tr>
                <td align="left" valign="top" class="BlackCategoryText"><?php 
$cwSearchObj = new CWSearch($cartweaver);
$cwSearchObj->setSearchType("Links");
$cwSearchObj->setAllCategoriesLabel("");
$cwSearchObj->setSeparator("<br /><br />");
$cwSearchObj->setSelectedStart("<span style=\"font-weight: bold\">");
$cwSearchObj->setSelectedEnd("</span>");
$cwSearchObj->display();
?></td>
              </tr>
            </table>
            <div align="center"><?php echo($cartweaver->cartLinks());?> </div>
          <!-- InstanceEndEditable --></td>
          <td width="1" background="images/vert_line.jpg"><img src="images/vert_line.jpg" alt="Mobsteel" width="1" height="35" /></td>
          <td height="100%" align="left" valign="top"><!-- InstanceBeginEditable name="content" -->
            <table width="625" border="0" align="center" cellpadding="3" cellspacing="0">
              
              <tr>
                <td><table width="602" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td><img src="images/featured_items.jpg" alt="Featured Items" width="222" height="29" /></td>
                  </tr>
                  <tr>
                    <td><!--<table width="602" border="0" cellpadding="3" cellspacing="0" class="HomeFeaturedBG">
                      <tr>
                        <td align="center" valign="top"><a href="details.php?prodId=56&category=4"><img src="images/thumb%20promo.JPG" alt="Featured Item 1" width="160" height="151" border="0" /></a></td>
                        <td align="center" valign="top"><a href="details.php?prodId=32&amp;category=4"><img src="images/wise_ws_thumb.jpg" alt="Featured Item 1" width="142" height="150" border="0" /></a></td>
                        <td align="center" valign="top"><a href="details.php?prodId=55&category=6"><img src="images/KNUCKLES3X2.jpg" alt="Featured Item 1" width="216" height="162" border="0" /></a></td>
                      </tr>
                      <tr>
                        <td align="center" class="BlackText"><a href="details.php?prodId=56&category=4">Mobsteel &quot;Logo&quot; Tee</a><br />
                            $10.00</td>
                        <td align="center"><span class="BlackText"><a href="details.php?prodId=32&amp;category=4">Mobsteel &quot;Wiseguy&quot; Work Shirt</a><br />
$35.00</span></td>
                        <td align="center"><span class="BlackText"><a href="details.php?prodId=55&category=6">Mobsteel Knuckles </a><br />
$15.00</span></td>
                      </tr>
                    </table>--></td>
                  </tr>
                </table></td>
              </tr>
            </table>
          <!-- InstanceEndEditable --></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="44"><table width="800" height="44" border="0" cellpadding="10" cellspacing="0">
      
      <tr>
        <td width="149" align="center" valign="bottom" class="BlackText">&nbsp;</td>
        <td width="347" align="center" valign="bottom" class="BlackText"> <a href="http://www.authorize.net" target="_blank"> <script language="Javascript" src="https://seal.starfieldtech.com/getSeal?sealID=440212740cff24e412710b91d2c8b817bcd08376833256786854827"></script> <img src="images/authorize_icon.jpg" alt="" width="81" height="18" border="0" /></a><a href="http://www.novainfo.com/" target="_blank"><img src="images/nova_icon.jpg" alt="" width="50" height="18" border="0" /></a> <img src="images/credit_cards.jpg" alt="Credit Cards" width="84" height="27" /></td>
        <td width="244" align="center" valign="bottom" class="BlackText"><a href="#" class="PageText" onclick="MM_openBrWindow('privacy.php','','scrollbars=yes,resizable=yes,width=470,height=350')">Privacy Policy </a></td>
      </tr>
      <tr>
        <td colspan="3" align="right" valign="bottom" background="images/footer_graphic.jpg" class="BlackText"> <span class="BlackTextBold">Mobsteel, LLC </span>702 Advance Street Brighton, MI 48116<br />
          <span class="BlackTextBold"><span class="RedTextBold">Customer Service / Order by Phone:</span> 810.333.6100 </span></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
<!-- InstanceEnd --></html>
<?php
cwDebugger($cartweaver);
?>
