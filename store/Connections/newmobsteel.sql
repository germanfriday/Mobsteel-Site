-- phpMyAdmin SQL Dump-- version 2.6.0-pl3-- http://www.phpmyadmin.net-- -- Host: localhost-- Generation Time: Apr 21, 2006 at 09:24 PM-- Server version: 5.0.15-- PHP Version: 5.0.4-- -- Database: `newmobsteel`-- -- ---------------------------------------------------------- -- Table structure for table `tbl_adminusers`-- CREATE TABLE `tbl_adminusers` (
  `admin_UserID` int(11) NOT NULL auto_increment,
  `admin_User` varchar(75) default NULL,
  `admin_UserName` varchar(75) default NULL,
  `admin_Password` varchar(75) default NULL,
  `admin_LoginDate` datetime default NULL,
  `admin_LastLogin` datetime default NULL,
  PRIMARY KEY  (`admin_UserID`),
  KEY `UsrID` (`admin_UserID`)
) -- -- Dumping data for table `tbl_adminusers`-- INSERT INTO `tbl_adminusers` VALUES (1, 'General Admin', 'admin', 'admin', '2004-10-30 08:36:00', '2004-10-29 20:35:00');-- ---------------------------------------------------------- -- Table structure for table `tbl_cart`-- CREATE TABLE `tbl_cart` (
  `cart_Line_ID` int(11) NOT NULL auto_increment,
  `cart_custcart_ID` varchar(50) default NULL,
  `cart_sku_ID` int(11) default NULL,
  `cart_sku_qty` int(11) default NULL,
  `cart_dateadded` datetime default NULL,
  PRIMARY KEY  (`cart_Line_ID`),
  KEY `cart_custcart_ID` (`cart_custcart_ID`),
  KEY `cart_Line_ID` (`cart_Line_ID`),
  KEY `sku_ID` (`cart_sku_ID`)
) -- -- Dumping data for table `tbl_cart`-- -- ---------------------------------------------------------- -- Table structure for table `tbl_companyinfo`-- CREATE TABLE `tbl_companyinfo` (
  `comp_ID` int(6) NOT NULL default '0',
  `comp_Name` varchar(50) default NULL,
  `comp_Address1` varchar(255) default NULL,
  `comp_Address2` varchar(50) default NULL,
  `comp_City` varchar(50) default NULL,
  `comp_State` varchar(20) default NULL,
  `comp_Zip` varchar(20) default NULL,
  `comp_Country` varchar(50) default NULL,
  `comp_Phone` varchar(30) default NULL,
  `comp_Fax` varchar(30) default NULL,
  `comp_Email` varchar(50) default NULL,
  `comp_ChargeBase` tinyint(1) default NULL,
  `comp_ChargeWeight` tinyint(1) default NULL,
  `comp_ChargeExtension` tinyint(1) default NULL,
  `comp_enableshipping` tinyint(1) default '0',
  `comp_ShowUpSell` tinyint(1) default NULL,
  `comp_AllowBackOrders` tinyint(11) default NULL,
  PRIMARY KEY  (`comp_ID`),
  KEY `CompanyID` (`comp_ID`)
) -- -- Dumping data for table `tbl_companyinfo`-- INSERT INTO `tbl_companyinfo` VALUES (1, 'Cartweaver Demo Store', '123 St.', '', 'SomeTown', 'WA', '98765', 'USA', '555-555-1236', '', 'support@cartweaver.com', 1, 1, 1, 1, 1, 0);-- ---------------------------------------------------------- -- Table structure for table `tbl_customers`-- CREATE TABLE `tbl_customers` (
  `cst_ID` varchar(50) NOT NULL default '',
  `cst_Type_ID` int(11) default NULL,
  `cst_FirstName` varchar(50) default NULL,
  `cst_LastName` varchar(50) default NULL,
  `cst_Address1` varchar(150) default NULL,
  `cst_Address2` varchar(150) default NULL,
  `cst_City` varchar(50) default NULL,
  `cst_Zip` varchar(20) default NULL,
  `cst_ShpName` varchar(50) default NULL,
  `cst_ShpAddress1` varchar(150) default NULL,
  `cst_ShpAddress2` varchar(100) default NULL,
  `cst_ShpCity` varchar(50) default NULL,
  `cst_ShpZip` varchar(50) default NULL,
  `cst_Phone` varchar(30) default NULL,
  `cst_Email` varchar(50) default NULL,
  `cst_Username` varchar(20) default NULL,
  `cst_Password` varchar(20) default NULL,
  PRIMARY KEY  (`cst_ID`),
  UNIQUE KEY `cst_Email` (`cst_Email`),
  UNIQUE KEY `cst_Username` (`cst_Username`),
  KEY `CustomersCustomerID` (`cst_ID`),
  KEY `tbl_Cust_Typetbl_Customers` (`cst_Type_ID`)
) -- -- Dumping data for table `tbl_customers`-- INSERT INTO `tbl_customers` VALUES ('1', 1, 'Bob', 'Buyer', '1234 St.', '', 'Sometown', '98801', 'Bob Buyer', '1234 st.', '', 'Sometown', '98801', '123.456.7890', 'bob@buyer.com', 'test', 'test');-- ---------------------------------------------------------- -- Table structure for table `tbl_custstate`-- CREATE TABLE `tbl_custstate` (
  `CustSt_ID` int(11) NOT NULL auto_increment,
  `CustSt_Cust_ID` varchar(50) NOT NULL default '',
  `CustSt_StPrv_ID` int(11) default NULL,
  `CustSt_Destination` varchar(50) default NULL,
  PRIMARY KEY  (`CustSt_ID`),
  KEY `CustStCntry_Cust_ID` (`CustSt_Cust_ID`),
  KEY `CustStCntry_ID` (`CustSt_ID`),
  KEY `tbl_Customerstbl_Cust_State` (`CustSt_Cust_ID`),
  KEY `tbl_StateProvtbl_Cust_StProvCntry_relation` (`CustSt_StPrv_ID`)
) -- -- Dumping data for table `tbl_custstate`-- INSERT INTO `tbl_custstate` VALUES (117, '1', 5, 'BillTo');INSERT INTO `tbl_custstate` VALUES (118, '1', 5, 'ShipTo');-- ---------------------------------------------------------- -- Table structure for table `tbl_custtype`-- CREATE TABLE `tbl_custtype` (
  `custtype_ID` int(11) NOT NULL auto_increment,
  `custtype_Name` varchar(50) default NULL,
  PRIMARY KEY  (`custtype_ID`),
  KEY `custtyp_ID` (`custtype_ID`)
) -- -- Dumping data for table `tbl_custtype`-- INSERT INTO `tbl_custtype` VALUES (1, 'Retail');INSERT INTO `tbl_custtype` VALUES (2, 'Wholesale');-- ---------------------------------------------------------- -- Table structure for table `tbl_list_ccards`-- CREATE TABLE `tbl_list_ccards` (
  `ccard_ID` int(11) NOT NULL auto_increment,
  `ccard_Name` varchar(50) default NULL,
  `ccard_Code` varchar(50) default NULL,
  `ccard_Archive` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`ccard_ID`),
  KEY `ccard_code` (`ccard_Code`),
  KEY `ccard_ID` (`ccard_ID`)
) -- -- Dumping data for table `tbl_list_ccards`-- INSERT INTO `tbl_list_ccards` VALUES (2, 'Master Card', 'master', 0);INSERT INTO `tbl_list_ccards` VALUES (3, 'American Express', 'amex', 0);INSERT INTO `tbl_list_ccards` VALUES (4, 'Discover', 'discover', 0);INSERT INTO `tbl_list_ccards` VALUES (18, 'Visa', 'visa', 0);-- ---------------------------------------------------------- -- Table structure for table `tbl_list_countries`-- CREATE TABLE `tbl_list_countries` (
  `country_ID` int(1) NOT NULL auto_increment,
  `country_Name` varchar(50) default NULL,
  `country_Code` varchar(50) default NULL,
  `country_Sort` int(11) default NULL,
  `country_Archive` tinyint(4) NOT NULL default '0',
  `country_DefaultCountry` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`country_ID`),
  KEY `region_ID` (`country_ID`),
  KEY `region_code` (`country_Code`)
) -- -- Dumping data for table `tbl_list_countries`-- INSERT INTO `tbl_list_countries` VALUES (1, 'United States', 'USA', 1, 0, 1);INSERT INTO `tbl_list_countries` VALUES (2, 'US Territories', 'USA_Terr', 2, 0, 0);INSERT INTO `tbl_list_countries` VALUES (3, 'Canada', 'CA', 3, 0, 0);-- ---------------------------------------------------------- -- Table structure for table `tbl_list_imagetypes`-- CREATE TABLE `tbl_list_imagetypes` (
  `imgType_ID` int(11) NOT NULL auto_increment,
  `imgType_Name` varchar(100) default NULL,
  `imgType_SortOrder` int(11) default NULL,
  PRIMARY KEY  (`imgType_ID`),
  KEY `imgType_ID` (`imgType_ID`)
) -- -- Dumping data for table `tbl_list_imagetypes`-- INSERT INTO `tbl_list_imagetypes` VALUES (1, 'Thumb', 1);INSERT INTO `tbl_list_imagetypes` VALUES (2, 'Large', 2);-- ---------------------------------------------------------- -- Table structure for table `tbl_list_optiontypes`-- CREATE TABLE `tbl_list_optiontypes` (
  `optiontype_ID` int(11) NOT NULL auto_increment,
  `optiontype_Required` tinyint(4) NOT NULL default '0',
  `optiontype_Name` varchar(75) default NULL,
  `optiontype_Archive` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`optiontype_ID`),
  KEY `optiontype_ID` (`optiontype_ID`)
) -- -- Dumping data for table `tbl_list_optiontypes`-- INSERT INTO `tbl_list_optiontypes` VALUES (1, 1, 'Size', 0);INSERT INTO `tbl_list_optiontypes` VALUES (2, 1, 'Color', 0);INSERT INTO `tbl_list_optiontypes` VALUES (3, 1, 'Cut', 0);-- ---------------------------------------------------------- -- Table structure for table `tbl_list_shipstatus`-- CREATE TABLE `tbl_list_shipstatus` (
  `shipstatus_id` int(11) NOT NULL auto_increment,
  `shipstatus_Name` varchar(70) default NULL,
  `shipstatus_Sort` int(11) default NULL,
  PRIMARY KEY  (`shipstatus_id`),
  KEY `shipstatus_id` (`shipstatus_id`)
) -- -- Dumping data for table `tbl_list_shipstatus`-- INSERT INTO `tbl_list_shipstatus` VALUES (1, 'Pending', 1);INSERT INTO `tbl_list_shipstatus` VALUES (2, 'Verified', 2);INSERT INTO `tbl_list_shipstatus` VALUES (3, 'Shipped', 3);INSERT INTO `tbl_list_shipstatus` VALUES (4, 'Canceled', 4);INSERT INTO `tbl_list_shipstatus` VALUES (5, 'Returned', 5);-- ---------------------------------------------------------- -- Table structure for table `tbl_orders`-- CREATE TABLE `tbl_orders` (
  `order_ID` varchar(75) NOT NULL default '',
  `order_TransactionID` varchar(50) default NULL,
  `order_Date` datetime default NULL,
  `order_Status` int(11) default NULL,
  `order_CustomerID` varchar(50) NOT NULL default '',
  `order_Tax` double default NULL,
  `order_Shipping` double default NULL,
  `order_Total` double default NULL,
  `order_ShipMeth_ID` int(11) default NULL,
  `order_ShipDate` datetime default NULL,
  `order_ShipTrackingID` varchar(100) default NULL,
  `order_Address1` varchar(125) default NULL,
  `order_Address2` varchar(50) default NULL,
  `order_City` varchar(100) default NULL,
  `order_State` varchar(50) default NULL,
  `order_Zip` varchar(50) default NULL,
  `order_Country` varchar(75) default NULL,
  `order_Notes` mediumtext,
  `order_ActualShipCharge` double default NULL,
  `order_ShipName` varchar(75) default NULL,
  PRIMARY KEY  (`order_ID`),
  KEY `order_ShipTrackingID` (`order_ShipTrackingID`),
  KEY `OrdersCustomerID` (`order_CustomerID`),
  KEY `OrdersOrderID` (`order_ID`),
  KEY `OrdersShippingMethodID` (`order_ShipMeth_ID`),
  KEY `tbl_Customerstbl_Orders` (`order_CustomerID`),
  KEY `tbl_ShipMethodtbl_Orders` (`order_ShipMeth_ID`)
) -- -- Dumping data for table `tbl_orders`-- -- ---------------------------------------------------------- -- Table structure for table `tbl_orderskus`-- CREATE TABLE `tbl_orderskus` (
  `orderSKU_ID` int(11) NOT NULL auto_increment,
  `orderSKU_OrderID` varchar(50) NOT NULL default '',
  `orderSKU_SKU` int(11) NOT NULL default '0',
  `orderSKU_Quantity` int(11) default NULL,
  `orderSKU_UnitPrice` double default NULL,
  `orderSKU_SKUTotal` double default NULL,
  `orderSKU_Picked` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`orderSKU_ID`),
  KEY `LineID` (`orderSKU_ID`),
  KEY `OrderDetailsOrderID` (`orderSKU_OrderID`),
  KEY `OrderSKUsSKU` (`orderSKU_SKU`),
  KEY `tbl_Orderstbl_OrderSKUs` (`orderSKU_OrderID`),
  KEY `tbl_SKUstbl_OrderSKUs` (`orderSKU_SKU`)
) -- -- Dumping data for table `tbl_orderskus`-- -- ---------------------------------------------------------- -- Table structure for table `tbl_prdtcat_rel`-- CREATE TABLE `tbl_prdtcat_rel` (
  `prdt_cat_rel_ID` int(11) NOT NULL auto_increment,
  `prdt_cat_rel_Product_ID` int(11) default NULL,
  `prdt_cat_rel_Cat_ID` int(11) default NULL,
  PRIMARY KEY  (`prdt_cat_rel_ID`)
) -- -- Dumping data for table `tbl_prdtcat_rel`-- INSERT INTO `tbl_prdtcat_rel` VALUES (15, 22, 2);INSERT INTO `tbl_prdtcat_rel` VALUES (19, 21, 2);INSERT INTO `tbl_prdtcat_rel` VALUES (20, 23, 4);INSERT INTO `tbl_prdtcat_rel` VALUES (21, 24, 2);INSERT INTO `tbl_prdtcat_rel` VALUES (22, 24, 4);-- ---------------------------------------------------------- -- Table structure for table `tbl_prdtcategories`-- CREATE TABLE `tbl_prdtcategories` (
  `category_ID` int(11) NOT NULL auto_increment,
  `category_Name` varchar(75) default NULL,
  `category_sortorder` int(11) default '0',
  `category_archive` tinyint(4) default '0',
  PRIMARY KEY  (`category_ID`),
  KEY `category_id` (`category_ID`)
) -- -- Dumping data for table `tbl_prdtcategories`-- INSERT INTO `tbl_prdtcategories` VALUES (2, 'Clothing', 0, 0);INSERT INTO `tbl_prdtcategories` VALUES (4, 'Housewares', 0, 0);-- ---------------------------------------------------------- -- Table structure for table `tbl_prdtimages`-- CREATE TABLE `tbl_prdtimages` (
  `prdctImage_ID` int(11) NOT NULL auto_increment,
  `prdctImage_ProductID` int(11) default NULL,
  `prdctImage_ImgTypeID` int(11) default NULL,
  `prdctImage_FileName` varchar(50) default NULL,
  `prdctImage_SortOrder` int(11) default NULL,
  PRIMARY KEY  (`prdctImage_ID`),
  KEY `prdctImage_ID` (`prdctImage_ID`),
  KEY `prdctImage_ImgTypeID` (`prdctImage_ImgTypeID`),
  KEY `prdctImage_ProductID` (`prdctImage_ProductID`),
  KEY `tbl_list_ImageTypestbl_PrdtImages` (`prdctImage_ImgTypeID`),
  KEY `tbl_Productstbl_PrdtImages` (`prdctImage_ProductID`)
) -- -- Dumping data for table `tbl_prdtimages`-- INSERT INTO `tbl_prdtimages` VALUES (12, 21, 2, 'PlaceHolder_Lrg.gif', 1);INSERT INTO `tbl_prdtimages` VALUES (13, 21, 1, 'PlaceHolder_Sml.gif', 1);INSERT INTO `tbl_prdtimages` VALUES (14, 22, 2, 'PlaceHolder_Lrg.gif', 1);INSERT INTO `tbl_prdtimages` VALUES (15, 22, 1, 'PlaceHolder_Sml.gif', 1);INSERT INTO `tbl_prdtimages` VALUES (16, 23, 2, 'PlaceHolder_Lrg.gif', 1);INSERT INTO `tbl_prdtimages` VALUES (17, 23, 1, 'PlaceHolder_Sml.gif', 1);INSERT INTO `tbl_prdtimages` VALUES (18, 24, 2, 'PlaceHolder_Lrg.gif', 1);INSERT INTO `tbl_prdtimages` VALUES (19, 24, 1, 'PlaceHolder_Sml.gif', 1);-- ---------------------------------------------------------- -- Table structure for table `tbl_prdtoption_rel`-- CREATE TABLE `tbl_prdtoption_rel` (
  `optn_rel_ID` int(11) NOT NULL auto_increment,
  `optn_rel_Prod_ID` int(11) NOT NULL default '0',
  `optn_rel_OptionType_ID` int(11) NOT NULL default '0',
  PRIMARY KEY  (`optn_rel_ID`),
  KEY `option_rel_optionID` (`optn_rel_OptionType_ID`),
  KEY `SKU_option_ID` (`optn_rel_ID`),
  KEY `tbl_list_OptionTypestbl_ProductOption_rel` (`optn_rel_OptionType_ID`),
  KEY `tbl_Productstbl_ProductOption_rel` (`optn_rel_Prod_ID`)
) -- -- Dumping data for table `tbl_prdtoption_rel`-- INSERT INTO `tbl_prdtoption_rel` VALUES (107, 22, 1);INSERT INTO `tbl_prdtoption_rel` VALUES (113, 21, 2);INSERT INTO `tbl_prdtoption_rel` VALUES (114, 23, 2);INSERT INTO `tbl_prdtoption_rel` VALUES (115, 23, 1);INSERT INTO `tbl_prdtoption_rel` VALUES (116, 24, 2);INSERT INTO `tbl_prdtoption_rel` VALUES (117, 24, 3);INSERT INTO `tbl_prdtoption_rel` VALUES (118, 24, 1);-- ---------------------------------------------------------- -- Table structure for table `tbl_prdtscndcat_rel`-- CREATE TABLE `tbl_prdtscndcat_rel` (
  `prdt_scnd_rel_ID` int(11) NOT NULL auto_increment,
  `prdt_scnd_rel_Product_ID` int(11) default NULL,
  `prdt_scnd_rel_ScndCat_ID` int(11) default NULL,
  PRIMARY KEY  (`prdt_scnd_rel_ID`),
  KEY `prdt_scnd_re_ScndID` (`prdt_scnd_rel_ScndCat_ID`),
  KEY `prdt_scnd_rel_id` (`prdt_scnd_rel_ID`),
  KEY `prdt_scnd_rel_prdctID` (`prdt_scnd_rel_Product_ID`),
  KEY `tbl_PrdtScndCategoriestbl_PrdtScndCtgry_rel` (`prdt_scnd_rel_ScndCat_ID`),
  KEY `tbl_Productstbl_PrdtScndCtgry_rel` (`prdt_scnd_rel_Product_ID`)
) -- -- Dumping data for table `tbl_prdtscndcat_rel`-- INSERT INTO `tbl_prdtscndcat_rel` VALUES (163, 22, 2);INSERT INTO `tbl_prdtscndcat_rel` VALUES (164, 22, 5);INSERT INTO `tbl_prdtscndcat_rel` VALUES (169, 21, 2);INSERT INTO `tbl_prdtscndcat_rel` VALUES (170, 21, 3);INSERT INTO `tbl_prdtscndcat_rel` VALUES (171, 23, 7);INSERT INTO `tbl_prdtscndcat_rel` VALUES (172, 24, 2);INSERT INTO `tbl_prdtscndcat_rel` VALUES (173, 24, 3);INSERT INTO `tbl_prdtscndcat_rel` VALUES (174, 24, 4);-- ---------------------------------------------------------- -- Table structure for table `tbl_prdtscndcats`-- CREATE TABLE `tbl_prdtscndcats` (
  `scndctgry_ID` int(11) NOT NULL auto_increment,
  `scndctgry_Name` varchar(100) default NULL,
  `scndctgry_Sort` int(11) default '0',
  `scndctgry_Archive` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`scndctgry_ID`),
  KEY `scndctgry_id` (`scndctgry_ID`)
) -- -- Dumping data for table `tbl_prdtscndcats`-- INSERT INTO `tbl_prdtscndcats` VALUES (2, 'Men''s', 0, 0);INSERT INTO `tbl_prdtscndcats` VALUES (3, 'Women''s', 0, 0);INSERT INTO `tbl_prdtscndcats` VALUES (4, 'Children''s', 0, 0);INSERT INTO `tbl_prdtscndcats` VALUES (5, 'Shirts', 0, 0);INSERT INTO `tbl_prdtscndcats` VALUES (6, 'Pants', 0, 0);INSERT INTO `tbl_prdtscndcats` VALUES (7, 'Training', 0, 0);-- ---------------------------------------------------------- -- Table structure for table `tbl_prdtupsell`-- CREATE TABLE `tbl_prdtupsell` (
  `upsell_id` int(11) NOT NULL auto_increment,
  `upsell_ProdID` int(11) NOT NULL default '0',
  `upsell_relProdID` int(11) NOT NULL default '0',
  PRIMARY KEY  (`upsell_id`)
) -- -- Dumping data for table `tbl_prdtupsell`-- -- ---------------------------------------------------------- -- Table structure for table `tbl_products`-- CREATE TABLE `tbl_products` (
  `product_ID` int(11) NOT NULL auto_increment,
  `product_MerchantProductID` varchar(50) NOT NULL default '',
  `product_Name` varchar(125) default NULL,
  `product_Description` longtext,
  `product_ShortDescription` longtext,
  `product_Sort` int(11) default NULL,
  `product_OnWeb` tinyint(4) NOT NULL default '0',
  `product_Archive` tinyint(4) NOT NULL default '0',
  `product_shipchrg` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`product_ID`),
  UNIQUE KEY `ItemNumber` (`product_MerchantProductID`),
  KEY `intProdcut_ID` (`product_ID`)
) -- -- Dumping data for table `tbl_products`-- INSERT INTO `tbl_products` VALUES (21, '1option', 'One Option Product', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vestibulum vel augue quis velit vulputate commodo. Sed leo magna, adipiscing ut, nonummy ac, pulvinar at, libero. Sed vulputate. Etiam sed purus. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec porta. Pellentesque vel mauris ac dolor vulputate tincidunt. Aliquam justo eros, vehicula et, aliquet nec, molestie vitae, tellus. Ut mollis imperdiet mauris. Nam aliquam varius tellus. Praesent venenatis tellus vel ligula. Etiam mattis nisl et urna. Donec euismod egestas ipsum. Nulla facilisi. Praesent ac nulla. Sed nec turpis ut neque nonummy euismod. Vivamus sed augue vel magna interdum faucibus. In felis urna, laoreet convallis, facilisis eu, convallis sit amet, ligula. Maecenas consequat ultrices velit.\r\n\r\nDonec facilisis aliquam eros. Morbi lobortis dolor a sapien. Quisque nibh est, tempus nonummy, interdum vel, auctor in, urna. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam rhoncus. Phasellus iaculis, ipsum ac mollis ultricies, nulla massa rutrum pede, et pellentesque massa massa ut massa. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae, In hac habitasse platea dictumst. Curabitur pretium nunc eget odio. Proin orci justo, auctor vel, consectetuer sed, hendrerit eu, tortor. Aliquam pede. Cras turpis purus, iaculis non, tincidunt molestie, hendrerit nec, lacus. Duis bibendum tempor velit.\r\n\r\nNam aliquet purus porttitor mi. Vivamus sed dolor. In hendrerit hendrerit nulla. Donec dapibus elit quis nisl. In eros ante, commodo a, vulputate et, auctor ut, turpis. Vivamus porttitor justo vitae sem. Integer suscipit ullamcorper velit. Morbi sit amet erat. Vestibulum ipsum augue, nonummy non, aliquam in, malesuada in, magna. Suspendisse iaculis.', 'orem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam enim. Proin condimentum iaculis nisl. Fusce euismod, felis non auctor tincidunt, pede purus tempor felis, eu accumsan lacus orci quis velit. Integer at sapien. Suspendisse molestie.', 2, 1, 0, 1);INSERT INTO `tbl_products` VALUES (22, '1sku', 'Single SKU Product', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vestibulum vel augue quis velit vulputate commodo. Sed leo magna, adipiscing ut, nonummy ac, pulvinar at, libero. Sed vulputate. Etiam sed purus. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec porta. Pellentesque vel mauris ac dolor vulputate tincidunt. Aliquam justo eros, vehicula et, aliquet nec, molestie vitae, tellus. Ut mollis imperdiet mauris. Nam aliquam varius tellus. Praesent venenatis tellus vel ligula. Etiam mattis nisl et urna. Donec euismod egestas ipsum. Nulla facilisi. Praesent ac nulla. Sed nec turpis ut neque nonummy euismod. Vivamus sed augue vel magna interdum faucibus. In felis urna, laoreet convallis, facilisis eu, convallis sit amet, ligula. Maecenas consequat ultrices velit.\r\n\r\nDonec facilisis aliquam eros. Morbi lobortis dolor a sapien. Quisque nibh est, tempus nonummy, interdum vel, auctor in, urna. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam rhoncus. Phasellus iaculis, ipsum ac mollis ultricies, nulla massa rutrum pede, et pellentesque massa massa ut massa. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae, In hac habitasse platea dictumst. Curabitur pretium nunc eget odio. Proin orci justo, auctor vel, consectetuer sed, hendrerit eu, tortor. Aliquam pede. Cras turpis purus, iaculis non, tincidunt molestie, hendrerit nec, lacus. Duis bibendum tempor velit.\r\n\r\nNam aliquet purus porttitor mi. Vivamus sed dolor. In hendrerit hendrerit nulla. Donec dapibus elit quis nisl. In eros ante, commodo a, vulputate et, auctor ut, turpis. Vivamus porttitor justo vitae sem. Integer suscipit ullamcorper velit. Morbi sit amet erat. Vestibulum ipsum augue, nonummy non, aliquam in, malesuada in, magna. Suspendisse iaculis.', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam felis wisi, rhoncus id, bibendum eu, tristique at, magna. Aliquam a nisl. Quisque accumsan molestie enim. Sed sit amet nisl. Nunc sit amet massa porta massa.', 1, 1, 0, 1);INSERT INTO `tbl_products` VALUES (23, '2option', 'Two Option Product', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nunc lobortis, odio viverra vestibulum lacinia, ipsum nisl interdum dui, varius posuere velit lacus a ligula. Integer lacus. Suspendisse porttitor. Aenean commodo vehicula tellus. Cras pede lacus, tincidunt in, viverra quis, viverra hendrerit, lorem. Donec nibh neque, suscipit non, posuere nec, tristique ut, sem. Pellentesque quis lectus eget risus hendrerit viverra. Donec dui mi, iaculis vitae, pharetra ac, egestas in, lectus. Aenean non arcu. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Integer hendrerit nunc nec dui. Vestibulum id nibh. Etiam elit. Nulla et erat ut leo scelerisque nonummy. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Etiam interdum purus non enim ultricies vulputate. Proin lacinia velit imperdiet libero.\r\n\r\nPellentesque at augue ac ante pulvinar blandit. Curabitur id tortor vitae elit pulvinar sagittis. Sed a augue. Proin pede velit, ornare vel, fermentum at, dignissim vitae, nulla. Donec feugiat ligula at risus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae, Morbi elit risus, pellentesque rhoncus, vestibulum eget, euismod et, nibh. Ut metus enim, accumsan eu, gravida eget, dignissim eget, lectus. Nulla facilisi. Quisque tristique quam ut sem. Ut urna urna, dignissim at, dignissim quis, convallis elementum, turpis. Pellentesque quis purus. Duis eget enim. Nunc dictum, est sed vulputate dictum, diam nibh fermentum metus, eu accumsan massa enim nec enim. Donec ultrices, libero sed porttitor nonummy, libero ante ullamcorper tellus, tempor gravida mauris metus sed odio. Integer viverra felis posuere massa. Maecenas euismod rutrum nulla. Pellentesque gravida, neque ac malesuada consectetuer, sem massa varius mauris, id pulvinar diam pede in urna. ', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam felis wisi, rhoncus id, bibendum eu, tristique at, magna. Aliquam a nisl. Quisque accumsan molestie enim. Sed sit amet nisl. Nunc sit amet massa porta massa.', 3, 1, 0, 1);INSERT INTO `tbl_products` VALUES (24, '3option', 'Three Option Product', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nunc lobortis, odio viverra vestibulum lacinia, ipsum nisl interdum dui, varius posuere velit lacus a ligula. Integer lacus. Suspendisse porttitor. Aenean commodo vehicula tellus. Cras pede lacus, tincidunt in, viverra quis, viverra hendrerit, lorem. Donec nibh neque, suscipit non, posuere nec, tristique ut, sem. Pellentesque quis lectus eget risus hendrerit viverra. Donec dui mi, iaculis vitae, pharetra ac, egestas in, lectus. Aenean non arcu. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Integer hendrerit nunc nec dui. Vestibulum id nibh. Etiam elit. Nulla et erat ut leo scelerisque nonummy. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Etiam interdum purus non enim ultricies vulputate. Proin lacinia velit imperdiet libero.\r\n\r\nPellentesque at augue ac ante pulvinar blandit. Curabitur id tortor vitae elit pulvinar sagittis. Sed a augue. Proin pede velit, ornare vel, fermentum at, dignissim vitae, nulla. Donec feugiat ligula at risus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae, Morbi elit risus, pellentesque rhoncus, vestibulum eget, euismod et, nibh. Ut metus enim, accumsan eu, gravida eget, dignissim eget, lectus. Nulla facilisi. Quisque tristique quam ut sem. Ut urna urna, dignissim at, dignissim quis, convallis elementum, turpis. Pellentesque quis purus. Duis eget enim. Nunc dictum, est sed vulputate dictum, diam nibh fermentum metus, eu accumsan massa enim nec enim. Donec ultrices, libero sed porttitor nonummy, libero ante ullamcorper tellus, tempor gravida mauris metus sed odio. Integer viverra felis posuere massa. Maecenas euismod rutrum nulla. Pellentesque gravida, neque ac malesuada consectetuer, sem massa varius mauris, id pulvinar diam pede in urna. ', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nunc lobortis, odio viverra vestibulum lacinia, ipsum nisl interdum dui, varius posuere velit lacus a ligula. Integer lacus.', 4, 1, 0, 1);-- ---------------------------------------------------------- -- Table structure for table `tbl_shipmethcntry_rel`-- CREATE TABLE `tbl_shipmethcntry_rel` (
  `shpmet_cntry_ID` int(11) NOT NULL auto_increment,
  `shpmet_cntry_Meth_ID` int(11) default NULL,
  `shpmet_cntry_Country_ID` int(11) default NULL,
  PRIMARY KEY  (`shpmet_cntry_ID`),
  KEY `shpmet_cntry_country_id` (`shpmet_cntry_Country_ID`),
  KEY `shpmet_cntry_ID` (`shpmet_cntry_ID`),
  KEY `shpmet_cntry_meth_id` (`shpmet_cntry_Meth_ID`)
) -- -- Dumping data for table `tbl_shipmethcntry_rel`-- INSERT INTO `tbl_shipmethcntry_rel` VALUES (1, 35, 1);INSERT INTO `tbl_shipmethcntry_rel` VALUES (2, 36, 1);INSERT INTO `tbl_shipmethcntry_rel` VALUES (3, 60, 1);INSERT INTO `tbl_shipmethcntry_rel` VALUES (4, 74, 2);INSERT INTO `tbl_shipmethcntry_rel` VALUES (7, 65, 3);-- ---------------------------------------------------------- -- Table structure for table `tbl_shipmethod`-- CREATE TABLE `tbl_shipmethod` (
  `shipmeth_ID` int(11) NOT NULL auto_increment,
  `shipmeth_Name` varchar(100) NOT NULL default '',
  `shipmeth_Rate` double default NULL,
  `shipmeth_Sort` int(11) NOT NULL default '0',
  `shipmeth_Archive` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`shipmeth_ID`),
  KEY `RowID` (`shipmeth_ID`)
) -- -- Dumping data for table `tbl_shipmethod`-- INSERT INTO `tbl_shipmethod` VALUES (35, 'USA UPS Ground', 4, 1, 0);INSERT INTO `tbl_shipmethod` VALUES (36, 'USA 2 Day Air', 7, 2, 0);INSERT INTO `tbl_shipmethod` VALUES (60, 'USA Overnight Air', 8, 3, 0);INSERT INTO `tbl_shipmethod` VALUES (65, 'Canadian UPS', 15, 4, 0);INSERT INTO `tbl_shipmethod` VALUES (66, 'International UPS', 28, 5, 0);INSERT INTO `tbl_shipmethod` VALUES (73, 'USPS', 4, 6, 0);INSERT INTO `tbl_shipmethod` VALUES (74, 'US Terriotories UPS', 4, 1, 0);-- ---------------------------------------------------------- -- Table structure for table `tbl_shipweights`-- CREATE TABLE `tbl_shipweights` (
  `ship_weightrange_ID` int(11) NOT NULL auto_increment,
  `ship_weightrange_Method_ID` int(11) default NULL,
  `ship_weightrange_From` double default NULL,
  `ship_weightrange_To` double default NULL,
  `ship_weightrange_Amount` double default NULL,
  PRIMARY KEY  (`ship_weightrange_ID`),
  KEY `shp_weightrange_ID` (`ship_weightrange_ID`),
  KEY `shp_weightrange_method_ID` (`ship_weightrange_Method_ID`),
  KEY `tbl_ShipMethodtbl_ShipWeightRange` (`ship_weightrange_Method_ID`)
) -- -- Dumping data for table `tbl_shipweights`-- INSERT INTO `tbl_shipweights` VALUES (1, 35, 0, 5, 11.55);INSERT INTO `tbl_shipweights` VALUES (2, 35, 5.01, 10, 20.5);INSERT INTO `tbl_shipweights` VALUES (3, 35, 10.01, 20, 30);INSERT INTO `tbl_shipweights` VALUES (4, 35, 20.01, 10000000, 40);INSERT INTO `tbl_shipweights` VALUES (5, 36, 0, 5, 15);INSERT INTO `tbl_shipweights` VALUES (6, 36, 5.01, 10, 25);INSERT INTO `tbl_shipweights` VALUES (7, 36, 10.01, 20, 35);INSERT INTO `tbl_shipweights` VALUES (8, 36, 20.01, 10000000, 45);INSERT INTO `tbl_shipweights` VALUES (9, 60, 1, 5, 16);INSERT INTO `tbl_shipweights` VALUES (10, 60, 5.01, 10, 26);INSERT INTO `tbl_shipweights` VALUES (11, 60, 10.01, 20, 36);INSERT INTO `tbl_shipweights` VALUES (12, 60, 20.01, 10000000, 46);INSERT INTO `tbl_shipweights` VALUES (13, 65, 0, 5, 19);INSERT INTO `tbl_shipweights` VALUES (14, 65, 5.01, 10, 29);INSERT INTO `tbl_shipweights` VALUES (15, 65, 10.01, 20, 39);INSERT INTO `tbl_shipweights` VALUES (16, 65, 20.01, 10000000, 49);INSERT INTO `tbl_shipweights` VALUES (17, 66, 0, 5, 21);INSERT INTO `tbl_shipweights` VALUES (18, 66, 5.01, 10, 31);INSERT INTO `tbl_shipweights` VALUES (19, 66, 10.01, 20, 41);INSERT INTO `tbl_shipweights` VALUES (20, 66, 20.01, 10000000, 51);-- ---------------------------------------------------------- -- Table structure for table `tbl_skuoption_rel`-- CREATE TABLE `tbl_skuoption_rel` (
  `optn_rel_ID` int(11) NOT NULL auto_increment,
  `optn_rel_SKU_ID` int(11) NOT NULL default '0',
  `optn_rel_Option_ID` int(11) NOT NULL default '0',
  PRIMARY KEY  (`optn_rel_ID`),
  KEY `option_rel_optionID` (`optn_rel_Option_ID`),
  KEY `SKU_option_ID` (`optn_rel_ID`),
  KEY `tbl_SKU_optionstbl_SKU_Option_relation` (`optn_rel_Option_ID`),
  KEY `tbl_SKUstbl_SKUOption_rel` (`optn_rel_SKU_ID`)
) -- -- Dumping data for table `tbl_skuoption_rel`-- INSERT INTO `tbl_skuoption_rel` VALUES (462, 28, 6);INSERT INTO `tbl_skuoption_rel` VALUES (463, 29, 7);INSERT INTO `tbl_skuoption_rel` VALUES (464, 30, 4);INSERT INTO `tbl_skuoption_rel` VALUES (465, 31, 1);INSERT INTO `tbl_skuoption_rel` VALUES (466, 31, 6);INSERT INTO `tbl_skuoption_rel` VALUES (467, 32, 6);INSERT INTO `tbl_skuoption_rel` VALUES (468, 32, 2);INSERT INTO `tbl_skuoption_rel` VALUES (469, 33, 3);INSERT INTO `tbl_skuoption_rel` VALUES (470, 33, 6);INSERT INTO `tbl_skuoption_rel` VALUES (471, 34, 6);INSERT INTO `tbl_skuoption_rel` VALUES (472, 34, 4);INSERT INTO `tbl_skuoption_rel` VALUES (473, 35, 1);INSERT INTO `tbl_skuoption_rel` VALUES (474, 35, 7);INSERT INTO `tbl_skuoption_rel` VALUES (475, 36, 7);INSERT INTO `tbl_skuoption_rel` VALUES (476, 36, 3);INSERT INTO `tbl_skuoption_rel` VALUES (477, 37, 1);INSERT INTO `tbl_skuoption_rel` VALUES (478, 37, 25);INSERT INTO `tbl_skuoption_rel` VALUES (479, 38, 25);INSERT INTO `tbl_skuoption_rel` VALUES (480, 38, 2);INSERT INTO `tbl_skuoption_rel` VALUES (481, 39, 1);-- ---------------------------------------------------------- -- Table structure for table `tbl_skuoptions`-- CREATE TABLE `tbl_skuoptions` (
  `option_ID` int(11) NOT NULL auto_increment,
  `option_Type_ID` int(11) default NULL,
  `option_Name` varchar(50) default NULL,
  `option_Sort` int(11) default NULL,
  `option_Archive` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`option_ID`),
  KEY `option_ID` (`option_ID`),
  KEY `tbl_list_OptionTypestbl_SKUOptions` (`option_Type_ID`),
  KEY `tbl_list_OptionTypestbl_SKUOptions1` (`option_Type_ID`)
) -- -- Dumping data for table `tbl_skuoptions`-- INSERT INTO `tbl_skuoptions` VALUES (1, 1, 'Small', 1, 0);INSERT INTO `tbl_skuoptions` VALUES (2, 1, 'Medium', 2, 0);INSERT INTO `tbl_skuoptions` VALUES (3, 1, 'Large', 3, 0);INSERT INTO `tbl_skuoptions` VALUES (4, 1, 'X-Large', 4, 0);INSERT INTO `tbl_skuoptions` VALUES (5, 1, 'XX-Large', 5, 0);INSERT INTO `tbl_skuoptions` VALUES (6, 2, 'Black', 1, 0);INSERT INTO `tbl_skuoptions` VALUES (7, 2, 'Blue', 2, 0);INSERT INTO `tbl_skuoptions` VALUES (9, 1, 'None', 9, 0);INSERT INTO `tbl_skuoptions` VALUES (20, 2, 'None', 9, 0);INSERT INTO `tbl_skuoptions` VALUES (21, 3, 'Fat', 1, 1);INSERT INTO `tbl_skuoptions` VALUES (22, 3, 'Slim', 2, 0);INSERT INTO `tbl_skuoptions` VALUES (24, 3, 'Form Fitting', 3, 0);INSERT INTO `tbl_skuoptions` VALUES (25, 2, 'Green', 3, 0);INSERT INTO `tbl_skuoptions` VALUES (26, 2, 'Red', 4, 0);INSERT INTO `tbl_skuoptions` VALUES (27, 2, 'Mauve', 6, 0);INSERT INTO `tbl_skuoptions` VALUES (28, 1, 'Boys Medium', 6, 0);-- ---------------------------------------------------------- -- Table structure for table `tbl_skus`-- CREATE TABLE `tbl_skus` (
  `SKU_ID` int(11) NOT NULL auto_increment,
  `SKU_MerchSKUID` varchar(50) default NULL,
  `SKU_ProductID` int(11) NOT NULL default '0',
  `SKU_Price` double default NULL,
  `SKU_Weight` double default NULL,
  `SKU_Stock` int(11) default NULL,
  `SKU_ShowWeb` tinyint(4) NOT NULL default '0',
  `SKU_Sort` int(11) default NULL,
  PRIMARY KEY  (`SKU_ID`),
  UNIQUE KEY `SKU_ID` (`SKU_MerchSKUID`),
  KEY `intSKU_ID` (`SKU_ID`),
  KEY `ProductsProductID` (`SKU_ProductID`),
  KEY `tbl_Productstbl_SKUs` (`SKU_ProductID`)
) -- -- Dumping data for table `tbl_skus`-- INSERT INTO `tbl_skus` VALUES (28, '1option', 21, 15, 12.3, 10, 1, 1);INSERT INTO `tbl_skus` VALUES (29, '1option2', 21, 15, 12.3, 10, 1, 1);INSERT INTO `tbl_skus` VALUES (30, '1sku', 22, 13.95, 10, 10, 1, 1);INSERT INTO `tbl_skus` VALUES (31, '2option1', 23, 14.99, 3, 10, 1, 0);INSERT INTO `tbl_skus` VALUES (32, '2option2', 23, 14.99, 3, 10, 1, 0);INSERT INTO `tbl_skus` VALUES (33, '2option3', 23, 14.99, 3, 10, 1, 0);INSERT INTO `tbl_skus` VALUES (34, '2option4', 23, 15.99, 0, 10, 1, 0);INSERT INTO `tbl_skus` VALUES (35, '2option5', 23, 14.99, 3, 10, 1, 0);INSERT INTO `tbl_skus` VALUES (36, '2option6', 23, 14.99, 3, 10, 1, 0);INSERT INTO `tbl_skus` VALUES (37, '2option7', 23, 14.99, 3, 10, 1, 0);INSERT INTO `tbl_skus` VALUES (38, '2option8', 23, 14.99, 3, 10, 1, 0);INSERT INTO `tbl_skus` VALUES (39, '3option1', 24, 13.99, 10, 10, 1, 0);INSERT INTO `tbl_skus` VALUES (40, '3option2', 24, 13.99, 10, 10, 1, 0);INSERT INTO `tbl_skus` VALUES (41, '3option3', 24, 13.99, 10, 10, 1, 0);INSERT INTO `tbl_skus` VALUES (42, '3option4', 24, 14.99, 10, 10, 1, 0);-- ---------------------------------------------------------- -- Table structure for table `tbl_stateprov`-- CREATE TABLE `tbl_stateprov` (
  `stprv_ID` int(11) NOT NULL auto_increment,
  `stprv_Code` varchar(50) default NULL,
  `stprv_Name` varchar(255) default NULL,
  `stprv_Country_ID` int(11) default NULL,
  `stprv_Tax` double default NULL,
  `stprv_Ship_Ext` double default NULL,
  `stprv_Archive` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`stprv_ID`),
  KEY `st_code` (`stprv_Code`),
  KEY `tbl_list_Countriestbl_StateProv` (`stprv_Country_ID`)
) -- -- Dumping data for table `tbl_stateprov`-- INSERT INTO `tbl_stateprov` VALUES (1, 'AL', 'Alabama', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (2, 'AK', 'Alaska', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (3, 'AZ', 'Arizona', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (4, 'AR', 'Arkansas', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (5, 'CA', 'California', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (6, 'CO', 'Colorado', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (7, 'CT', 'Connecticut', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (8, 'DE', 'Delaware', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (9, 'FL', 'Florida', 1, 0, 0.5, 0);INSERT INTO `tbl_stateprov` VALUES (10, 'GA', 'Georgia', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (11, 'HI', 'Hawaii', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (12, 'ID', 'Idaho', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (13, 'IL', 'Illinois', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (14, 'IN', 'Indiana', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (15, 'IA', 'Iowa', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (16, 'KS', 'Kansas', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (17, 'KY', 'Kentucky', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (18, 'LA', 'Louisiana', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (19, 'ME', 'Maine', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (20, 'MD', 'Maryland', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (21, 'MA', 'Massachusett', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (22, 'MI', 'Michigan', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (23, 'MN', 'Minnesota', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (24, 'MS', 'Mississippi', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (25, 'MO', 'Missouri', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (26, 'MT', 'Montana', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (27, 'NE', 'Nebraska', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (28, 'NV', 'Nevada', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (29, 'NH', 'New Hampshire', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (30, 'NJ', 'New Jersey', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (31, 'NM', 'New Mexico', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (32, 'NY', 'New York', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (33, 'NC', 'North Carolina', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (34, 'ND', 'North Dakota', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (35, 'OH', 'Ohio', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (36, 'OK', 'Oklahoma', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (37, 'OR', 'Oregon', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (38, 'PA', 'Pennsylvania', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (39, 'RI', 'Rhode Island', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (40, 'SC', 'South Carolina', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (41, 'SD', 'South Dakota', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (42, 'TN', 'Tennessee', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (43, 'TX', 'Texas', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (44, 'UT', 'Utah', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (45, 'VT', 'Vermont', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (46, 'VA', 'Virginia', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (47, 'WA', 'Washington', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (48, 'WV', 'West Virginia', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (49, 'WI', 'Wisconsin', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (50, 'WY', 'Wyoming', 1, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (51, 'BC', 'British Columbia', 3, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (52, 'MB', 'Manitoba', 3, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (53, 'NF', 'Newfoundland', 3, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (54, 'NB', 'New Brunswick', 3, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (55, 'NT', 'Northwest Territories', 3, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (56, 'NS', 'Nova Scotia', 3, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (57, 'ON', 'Ontario', 3, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (58, 'PE', 'Prince Edward Island', 3, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (59, 'QC', 'Quebec', 3, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (60, 'SK', 'Saskatchewan', 3, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (61, 'YT', 'Yukon', 3, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (62, 'AS', 'American Samoa', 2, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (63, 'FM', 'Fed. Micronesia', 2, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (64, 'G', 'Guam', 2, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (65, 'MH', 'Marshall Island', 2, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (66, 'MP', 'N. Mariana Is.', 2, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (67, 'PW', 'Palau Island', 2, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (68, 'PR', 'Puerto Rico', 2, 0, 0, 0);INSERT INTO `tbl_stateprov` VALUES (69, 'VI', 'Virgin Islands', 2, 0, 0, 0);