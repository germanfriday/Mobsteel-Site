<h2>Product Admin Help</h2>
<h3>Adding and Maintaining Products </h3>
<p> This page handles all of your product maintenance chores. Adding, updating, deleting or archiving products as well as adding, maintaining and deleting SKUs. Here we also can designate &quot;Up Sell&quot; products. </p>
<h3>A word about Products and SKUs.</h3>
<p>First it's imperative that you understand the Product / SKU relationship. A product is a particular Item within a brand, for example Levis 501 Blues is a product. The SKUs for this product would be the variations of 501 Blues, the sizes, which will be entered as SKUs (Stock Keeping Units). In the database it would applier like this.</p>
<table>
  <caption>
  Products
  </caption>
  <tr align="center">
    <th>ID</th>
    <th align="center">Name</th>
  </tr>
  <tr align="center">
    <td>Levis-501</td>
    <td align="center">Levis 501 Blues </td>
  </tr>
</table>
<blockquote>
  <table>
    <caption>
    Product SKUs
    </caption>
    <tr align="center">
      <th align="center">ID</th>
      <th align="center">Related Prod ID</th>
      <th><p>Description</p></th>
    </tr>
    <tr>
      <td align="center">Levis-501-3230</td>
      <td align="center">Levis-501</td>
      <td>32X30</td>
    </tr>
    <tr>
      <td align="center">Levis-501-3430</td>
      <td align="center">Levis-501</td>
      <td>34X30</td>
    </tr>
    <tr>
      <td align="center">Levis-501-3630</td>
      <td align="center">Levis-501</td>
      <td>36X30</td>
    </tr>
  </table>
</blockquote>
<p>This is a rather simple example, but it illustrates the relationship between a product and it's SKUs quite well. A product must have at least one SKU, for example a list for a unique item such as an original painting, or it can have as many SKUs as you need. </p>
<p>If you end up with a store with only three products with fifty SKUs each, it may be a good idea to reconsider how you are defining your Products. Going back to the previous example &quot;Blue Jeans&quot; would be a category or a secondary category, not a product. A particular line item from a particular brand or vendor, such as Levis 501 Blues, would be the correct choice as a product.</p>
<h3>Adding a Product</h3>
<p>When you click the Add Product link in the admin menu you are taken to a blank product admin page. Complete all of the fields for the Product Data section of the page. If you want to add a product but are not ready for it to appear on the web, set the &quot;ON Web&quot; setting to No. This will hide the product and all it's SKUs from the web until you are ready for it to go on-line. After you complete the Product Data and click Add product, the initial product data will be added to the database and the Product Admin page will reload with the newly added product. Now you will be able to <a href="<?php echo($cartweaver->thisPage);?>?helpFileName=ProductImageUpload.php">upload image</a> files and add SKUs. </p>
<h3>Adding SKUs</h3>
<p>Once the product has been created, the Add SKUs form will be visible. You can add as many SKUs as you would like. Once you are done adding SKUs you can hide the Add SKU form by clicking the &quot;Hide add SKU Form&quot; link. If you wish to add more SKUs simply click the &quot;Add SKU&quot; link to show the Add SKU form again. </p>
<p>When adding SKUs be sure to fill in all fields. If weight is unimportant for your product line just enter 0; all fields must be complete. </p>
<h3>Deleting Products and SKUs</h3>
<p><strong>Deleting a Product:</strong><br>
  You can only delete a product that has no SKUs related to it. In order to delete a product you will need to delete all the associated SKUs first. If you can not delete all the SKUs because of related orders but still want to eliminate the product, you can choose to Archive the product. This will remove it from the web and remove it from the Active products list. </p>
<p><strong>Deleting SKUs:</strong><br>
  You delete SKUs by clicking the trash can next to the SKU's ID. You may not delete SKUs that have orders associated with them. If you can not delete the SKU and wish to make it unavailable for sale you can set the &quot;On Web&quot; setting to No.</p>
<h3>Up Sell Products</h3>
<p>Up Sell products are products that the buyer of the current product may find of interest. If &quot;Show Cross Sell&quot; is set to Yes on the <a href="<?php echo($cartweaver->thisPage);?>?helpFileName=CompanyInfo.php">Company Info</a> page, links to the Up Sell products will appear on the Details Page in your Cart. You can add as many of these as you wish, though it is usually better to limit the number to a few to afford a better &quot;impact&quot;. </p>
