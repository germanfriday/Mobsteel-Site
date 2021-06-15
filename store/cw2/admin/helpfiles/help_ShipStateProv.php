<h2>State/Provinces Admin Help</h2>
<h3>Maintain States / Provinces and Their Sales Tax Setting</h3>
<p> This page allows you to add, update, and delete States/Provinces and relate them to the appropriate country. You can also set a Sales tax and Shipping Extension for each state listing. </p>
<h3>The values for each State are; Code, Name, Country, TaX%, and Extension. </h3>
<ul>
  <li><strong>Code:</strong><br>
    When adding a State you will assign it a Code, this will be the official postal code for the state as in California = CA. </li>
  <li><strong>Name:</strong><br>
    The State name is what will appear to the users on the web. </li>
  <li><strong>Tax %</strong><br>
    Tax % will be the State sales tax you will require a customer FROM THIS STATE to pay on an order. If you do not require a person from outside your home state to pay sales tax enter 0 in this field. </li>
  <li><strong>Shipping Extension % </strong><br>
    The shipping Extension is an additional percentage that will be charged to orders being shipped to this State. If it costs you 20% more to ship to Maine than it does to your home state you would enter .25 as Maine's Shipping Extension. Whether or not you wish to factor the Shipping Extension into your shipping charges is set on the <a href="<?php echo($cartweaver->thisPage);?>?helpFileName=ShipSettings.php">Shipping Settings</a> page. </li>
</ul>
<h3>Updating and Deleting</h3>
<p>You can update the Tax and Shipping Extension values for a State at any time. You can delete a state that does not have any orders associated with it. If there are orders associated with a State then you may Archive it if you wish the State to no longer be available. <br>
</p>
