<?php require("application.php");

/*
============================================================================
This is the presentation file for the Results Page.
============================================================================
*/
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Mobsteel Store</title>
<!-- InstanceEndEditable -->
<link href="includes/MobStoreStyles.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript" src="includes/common.js"></script>
<!-- InstanceBeginEditable name="head" -->


<link href="cw2/assets/css/cartweaver.css" rel="stylesheet" type="text/css" />
<link href="includes/MobStoreStyles.css" rel="stylesheet" type="text/css" /><!-- InstanceEndEditable -->
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
          <!-- InstanceEndEditable --></td>
          <td width="1" background="images/vert_line.jpg"><img src="images/vert_line.jpg" alt="Mobsteel" width="1" height="35" /></td>
          <td height="100%" align="left" valign="top"><!-- InstanceBeginEditable name="content" -->
            <table width="575" border="0" cellspacing="0" cellpadding="10">
              <tr>
                <td><span class="BlackTextBold">Shopping &amp; Order Information</span><br />
                  <span class="PageTextBold"><br />
                  How do I order online?</span><br />
                  You can order online by visiting the Mobsteel store at <a href="http://www.mobsteel.com/store">www.mobsteel.com/store</a>. Browse our catalog and place items in your shopping cart. When you are ready to check-out simply choose check out. <br />
                  <br />
                  <span class="PageTextBold">Does Mobsteel.com&reg; ship internationally? </span><br />
                  No. Mobsteel.com&reg; only delivers orders within the continental United States, Hawaii, Alaska, Puerto Rico, and other US Territories, APO and FPO addresses.<br />
                  <br />
                  <span class="PageTextBold">Which credit cards do you accept?</span><br />
                  You may use one of the following methods of payment: VISA or MasterCard<br />
                  <br />
                  <span class="PageTextBold">Can I pay for my online order by check?</span><br />
                  Sorry, at this time we are unable to accept payment by check. <br />
                  <br />
                  <span class="PageTextBold">Does Mobsteel&reg; have a mail-order catalog?</span><br />
                  At this time we do not have a catalog for our store fashion merchandise. To receive product mailers and promotions from Mobsteel&reg;, please  click <a href="http://www.mobsteel.com/contact.php" target="_blank">here</a> to be added to our e-mail list or, click on &quot;Join the hit list&quot; on our home page. <br />
                  <br />
                  <span class="PageTextBold">Can I track my order online?</span><br />
                  Yes. Your order's tracking number is located on the shipping confirmation email. To track your order on the US Postal Service's Web site, please copy and paste your tracking number at:   <a href="http://www.usps.com/shipping/trackandconfirm.htm" target="_blank">http://www.usps.com/shipping/trackandconfirm.htm</a>. Please allow up to 48 hours for your shipping information to be available. For further assistance, you may contact us via email at   <a href="mailto:sales@mobsteel.com">sales@mobsteel.com</a>  or 1.810.333.6100, M-F, 9am - 5pm EST.<br />
                  <br />
                  <span class="PageTextBold">I recently placed an order with Mobsteel.com&reg;, has my order been processed yet?</span> <br />
                  To inquire about a recent order placed from our Web site, you may call our customer service department at 1.810.333.6100, M-F, 9am - 5pm EST or contact us via email at  HYPERLINK &quot;mailto:sales@Mobsteel.com&quot; sales@Mobsteel.com. If you are sending an email, please be sure to include the complete billing name and address for your credit card and your order confirmation number.<br />
                  <span class="PageTextBold"><br />
                  I recently placed an order and have decided to cancel the order. How do I go about doing this?</span> <br />
                  To see if your order can be canceled, please contact our customer service department at 1.810.333.6100, M-F, 9am - 5pm EST. If your order has already been processed and shipped to you, we unfortunately cannot cancel the order. However, if you still do not wish to receive your order, you may do one of two things 1) Follow normal return procedures, or 2) Refuse delivery of the package, and when it is returned to us, a merchandise credit will be issued to your credit card, less delivery charges. <br />
                  <br />
                  <span class="PageTextBold">I've just placed an order on your web site, and now realize that I have made a mistake. Can my order be corrected?</span> <br />
                  To see if your order can be changed, please contact our customer service department 1.810.333.6100, M-F, 9am - 5pm EST. If your order has already been processed and shipped to you, we unfortunately cannot change the order. Upon receipt of your order, please follow the normal return/exchange procedures and we will be happy to exchange the item (assuming it is in stock). <br />
                  <br />
                  <span class="PageTextBold">An item I ordered is on backorder. When will I receive it?</span> <br />
                  Backordered styles are usually shipped 2-4 weeks after you place your order. To inquire about your backordered item you may call our customer service department at 1.810.333.6100, M-F, 9am - 5pm EST or contact us by email at  HYPERLINK &quot;mailto:sales@Mobsteel.com&quot; sales@Mobsteel.com. Please be sure to include your complete order information: name and address, order confirmation number and the style number(s) of the backordered items, so that we can research your order.<br />
                  <br />
                  <span class="BlackTextBold">Product Information</span><br />
                  <span class="PageTextBold"><br />
                  What sizes does Mobsteel&reg; offer?</span><br />
                  Mobsteel&reg; sizes range from S to 4XL dependant upon the item.<br />
                  <br />
                  <span class="PageTextBold">How will I know whether or not you have inventory of a particular item that I'm interested in?</span><br />
                  If a size is not available, it will not display in the drop down menu for selection. <br />
                  <br />
                  <span class="PageTextBold">Does Mobsteel&reg; design its own products?</span><br />
                  Yes. All Mobsteel&reg; apparel is designed in house. <br />
                  <br />
                  <span class="PageTextBold">I have a question about one of your products. Who should I call for assistance?</span><br />
                  E-mail your detailed product question to  HYPERLINK  <a href="mailto:sales@Mobsteel.com">sales@Mobsteel.com</a> or call our customer service number at 1.810.333.6100, M-F, 9am - 5pm EST <br />
                  <br />
                  <span class="PageTextBold">How often do you add new products to your Web site?</span> <br />
                  We add new products on a weekly basis to the Mobsteel Store. <br />
                  <span class="BlackTextBold"><br />
                  Shipping, Handling and Tax information</span><br />
                  <br />
                  <span class="PageTextBold">How much do you charge for shipping?</span><br />
                  See our rates below.<br />
                  <br />
                  <table width="565" border="0" cellpadding="3" cellspacing="3" class="Stroke">
                    <tr>
                      <td colspan="4" bgcolor="#CCCCCC" class="BlackTextBold">Shipping Charges</td>
                    </tr>
                    <tr>
                      <td class="PageTextBold">Order Value</td>
                      <td class="PageTextBold">Standard S&amp;H</td>
                      <td class="PageTextBold">Express S&amp;H <br />
2-5 Business Days</td>
                      <td class="PageTextBold">Premium S&amp;H <br />
1-3 Business Days</td>
                    </tr>
                    <tr>
                      <td bgcolor="#CCCCCC">$0 - $25</td>
                      <td bgcolor="#CCCCCC">$4.95</td>
                      <td bgcolor="#CCCCCC">$12.95</td>
                      <td bgcolor="#CCCCCC">$19.95</td>
                    </tr>
                    <tr>
                      <td>$25.01 - $35</td>
                      <td>$7.95</td>
                      <td>$15.95</td>
                      <td>$22.95</td>
                    </tr>
                    <tr>
                      <td bgcolor="#CCCCCC">$35.01 - $50</td>
                      <td bgcolor="#CCCCCC">$8.95</td>
                      <td bgcolor="#CCCCCC">$16.95 </td>
                      <td bgcolor="#CCCCCC">$23.95</td>
                    </tr>
                    <tr>
                      <td>$50.01 - $75</td>
                      <td>$9.95</td>
                      <td>$17.95</td>
                      <td>$24.95</td>
                    </tr>
                    <tr>
                      <td bgcolor="#CCCCCC">$75.01 - $100</td>
                      <td bgcolor="#CCCCCC">$11.95</td>
                      <td bgcolor="#CCCCCC">$19.95</td>
                      <td bgcolor="#CCCCCC">$26.95 </td>
                    </tr>
                    <tr>
                      <td>$100.01 - $125</td>
                      <td>$13.95</td>
                      <td>$21.95</td>
                      <td>$28.95</td>
                    </tr>
                    <tr>
                      <td bgcolor="#CCCCCC">$125.01 - $150</td>
                      <td bgcolor="#CCCCCC">$14.95</td>
                      <td bgcolor="#CCCCCC">$22.95</td>
                      <td bgcolor="#CCCCCC">$29.95</td>
                    </tr>
                    <tr>
                      <td>$150.01 - $200</td>
                      <td>$15.95</td>
                      <td>$23.95</td>
                      <td>$30.95</td>
                    </tr>
                    <tr>
                      <td bgcolor="#CCCCCC">$200.01 - or more</td>
                      <td bgcolor="#CCCCCC">$16.95</td>
                      <td bgcolor="#CCCCCC">$24.95</td>
                      <td bgcolor="#CCCCCC">$31.95</td>
                    </tr>
                  </table>
                  <br />
                  <span class="PageTextBold">What are my shipping options?</span> <br />
                  Standard Shipping 4-8 Business Days<br />
                  Following credit card approval and merchandise availability, an order shipped via USPS ground mail will be shipped the following business day, and delivered in four to eight business days. The USPS does not deliver ground mail on Sunday. <br />
                  *Please Note: We recommend all orders being shipped to Alaska and Hawaii use Express or Premium shipping. If choosing Standard Shipping please allow 3 to 6 weeks for delivery. For APO and FPO orders, Mobsteel.com&reg; can only control delivery time to initial base, not final destination.
<p>Express Shipping 2-5 Business Days <br />
                    Following credit card approval and merchandise availability, an order shipped via USPS Priority Mail or USPS First-Class Mail will be shipped the same business day if the order is received by 11:00 A.M. EST. If the order is received after 11:00 A.M EST, it will be shipped the following business day. The USPS does not deliver Priority Mail or First-Class Mail on Sunday. <br />
                    *Please Note: We recommend all orders being shipped to Alaska and Hawaii use Express or Premium shipping. If choosing Standard Shipping please allow 3 to 6 weeks for delivery. For APO and FPO orders, Mobsteel.com&reg; can only control delivery time to initial base, not final destination. </p>
                  <p>Premium Shipping 1-3 Business Days<br />
                    Following credit card approval and merchandise availability, an order shipped via USPS Express Mail will be shipped the same business day if the order is received by 11:00 A.M. EST. If the order is received after 11:00 A.M. EST, it will be shipped the following business day. Express Mail delivery offers delivery to most locations 365 days a year including Sundays and holidays.<br />
                    *Please Note: We recommend all orders being shipped to Alaska and Hawaii use Express or Premium shipping. If choosing Standard Shipping please allow 3 to 6 weeks for delivery. For APO and FPO orders, Mobsteel.com&reg; can only control delivery time to initial base, not final destination. <br />
                    <br />
                    <span class="PageTextBold">Does Mobsteel.com&reg; ship outside the United States?</span> <br />
                    We currently offer shipping to any address in the United States, including Alaska and Hawaii. </p>
                  <p class="PageText"><span class="BlackTextBold">Returns &amp; Exchanges</span><br />
                    <br />
                    <span class="PageTextBold">How do I return an item?</span><br />
                    Enclose your packing slip and the merchandise in a package then ship to the address below. We cannot refund or exchange merchandise that is not received, so we recommend that you insure your package (postage and insurance fees are non-refundable).<br />
                    <br />
                    <span class="PageTextBold">Mobsteel&reg;<br />
                    Returns Department<br />
                    702 Advance Street<br />
                    Brighton, MI 48116</span><br />
                    <br />
                    If you need further assistance prior to returning your item(s), please call us at 1.810.333.6100, M-F, 9am - 5pm EST<br />
                    <br />
                    <span class="PageTextBold">I lost my packing slip and would like to return an item(s). What type of information do you need?</span> <br />
                    To receive proper credit, please provide the name and address used on the order. If available, please include the order number, and the item number(s) of the merchandise you are returning. For further assistance, please call us at 1.810.333.6100, M-F, 9am - 5pm EST or email us at  <a href="mailto:sales@Mobsteel.com">sales@Mobsteel.com</a>. <br />
                    <br />
                    <span class="PageTextBold">What do I do if I was shipped the wrong item(s) or my order was damaged in transit?</span> <br />
                    Please email us at  <a href="mailto:sales@Mobsteel.com">sales@Mobsteel.com</a> with your order number, name, address and details on the item you ordered vs. the item you received, or call us at 1.810.333.6100, M-F, 9am - 5pm EST. If the item you requested is in stock, we will ship it to you immediately. We will assist you in returning the original shipment for a full refund, including shipping charges. <br />
                    <br />
                    <span class="PageTextBold">Will I get a full refund for my return? </span><br />
                    Assuming your return is in the same condition it was when we sent it to you and the proof of purchase is included with the package, you will receive a full refund for the cost of the product. We unfortunately cannot refund you for original and/or return postage and handling. <br />
                    <br />
                    <span class="PageTextBold">How long does it take to get my refund?</span> <br />
                    We process all returns immediately upon receipt. Depending on how you shipped the return, it can take up to 7-10 days for us to receive your package. Your credit card will be credited the full amount of the merchandise and posted to your account once the return is processed. <br />
                    <br />
                    <span class="PageTextBold">Who do I contact to check the status of my return?</span> <br />
                    To check on the status of your return, please email us at   <a href="mailto:sales@Mobsteel.com">sales@Mobsteel.com</a> or call us 1.810.333.6100, M-F, 9am - 5pm EST <br />
                    <br />
                    <span class="BlackTextBold">Mail and Email Communication </span><br />
                    <br />
                    <span class="PageTextBold">How can I sign up to receive e-mail news from Mobsteel&reg;?</span><br />
                    You can sign up to receive emails by joining the Mobsteel Hit List link located on every page or  click <a href="http://www.mobsteel.com/contact.php">here</a>.<br />
                    <span class="PageTextBold"><br />
                    How can I unsubscribe from your e-mail list?</span><br />
                    To unsubscribe from our e-mail list, simply send an e-mail to Mobsteel at  <a href="mailto:sales@Mobsteel.com">sales@mobsteel.com</a> using &quot;Unsubscribe&quot; in the subject line. Removal from our mailing list could take up to 3-5 days. <br />
                    <span class="PageTextBold"><br />
                    Does Mobsteel&reg; share my personal information with third parties?</span><br />
                    No. The names, e-mail and postal addresses and product preferences you provide are strictly private and will not be given or sold to any third party. Please  click <a href="#" onclick="MM_openBrWindow('privacy.php','','scrollbars=yes,resizable=yes,width=470,height=350')">here</a> see our privacy policy for a full explanation.<br />
                    <br />
                    <span class="BlackTextBold">Contact Us</span><br />
                    <br />
                    <span class="PageTextBold">Who do I contact if I have a question about my order?</span><br />
                    If you have any questions about your online order you may contact us by phone at 1.810.333.6100, M-F, 9am - 5pm EST, via email at  <a href="mailto:sales@Mobsteel.com">sales@Mobsteel.com</a>.<br />
                    <br />
                    <span class="BlackTextBold">Privacy and Security</span><br />
                    <br />
                    <span class="PageTextBold">Does Mobsteel&reg; share my personal information with third parties?</span><br />
                    No. The names, e-mail and postal addresses and product preferences you provide are strictly private and will not be given or sold to any third party. Please click <a href="#" onclick="MM_openBrWindow('privacy.php','','width=470,height=350')">here</a> see our privacy policy for a full explanation.<br />
                    <br />
                    <span class="PageTextBold">What is Mobsteel&reg;'s Privacy policy?</span><br />
                Please  click <a href="#" onclick="MM_openBrWindow('privacy.php','','scrollbars=yes,resizable=yes,width=470,height=350')">here</a> see our privacy policy.</p></td>
              </tr>
            </table>
            <p>&nbsp;</p>
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