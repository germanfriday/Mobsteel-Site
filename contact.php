<?php require_once('Connections/NewMobsteel.php'); ?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MobsteelContactForm")) {
  $insertSQL = sprintf("INSERT INTO tbl_hitlist (Newsletter, FirstName, LastName, Email, Phone, Address, City, `State`, Zip, QuestionsComments) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString(isset($_POST['Newsletter']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['LastName'], "text"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['Phone'], "text"),
                       GetSQLValueString($_POST['Address'], "text"),
                       GetSQLValueString($_POST['City'], "text"),
                       GetSQLValueString($_POST['State'], "text"),
                       GetSQLValueString($_POST['Zip'], "text"),
                       GetSQLValueString($_POST['QuestionsComments'], "text"));

  mysql_select_db($database_NewMobsteel, $NewMobsteel);
  $Result1 = mysql_query($insertSQL, $NewMobsteel) or die(mysql_error());
  
$FirstName = $_POST['FirstName'];
$LastName = $_POST['LastName'];
$Email = $_POST['Email'];
$Phone = $_POST['Phone'];
$Address = $_POST['Address'];
$City = $_POST['City'];
$State = $_POST['State'];
$Zip = $_POST['Zip'];
$QuestionsComments = $_POST['QuestionsComments'];
$Newsletter = $_POST['Newsletter'];
$email_message = ("First Name: $FirstName Last Name: $LastName Email: $Email Phone: $Phone Address: $Address City: $City State: $State Zip: $Zip Questions or Comments: $QuestionsComments Newsletter: $Newsletter");

mail(/*'chris@thegermanfriday.com',*/'adam@mobsteel.com','Mobsteel Contact Form Submission', $email_message);
  $insertGoTo = "thank_you.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
 
$section  = "contact";?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Mobsteel Rides - To - Die - For</title>
<link href="includes/MobSteelStyles.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript" src="includes/pngfix.js"></script>
<script language="JavaScript" type="text/javascript" src="includes/common.js"></script>
<script type="text/JavaScript">
<!--
function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>
</head>
<body> 
<div align="center"> 
  <table width="800" border="0" cellpadding="0" cellspacing="0" class="CityBG"> 
    <tr> 
      <td colspan="2"><?php include('includes/header.php'); ?></td> 
    </tr> 
    <tr> 
      <td width="555" align="right" valign="top"><table width="535" border="0" cellspacing="0" cellpadding="0"> 
          <tr> 
            <td><img src="images/contact_header.png" alt="Long Live Detroit" width="535" height="64" /></td> 
          </tr> 
          <tr> 
            <td valign="top" background="images/content_spacer.png"><table width="535" border="0" cellpadding="0" cellspacing="0" class="ContentBG"> 
                <tr> 
                  <td width="10" class="PageText">&nbsp;</td> 
                  <td align="center" valign="top" class="PageText"><form id="MobsteelContactForm" name="MobsteelContactForm" method="POST" action="<?php echo $editFormAction; ?>"> 
                      <table width="450" border="0" cellspacing="0" cellpadding="5"> 
                        <tr> 
                          <td colspan="4" align="left"><span class="PageTextBold">Mobsteel</span><br /> 
                            702 Advance Street<br /> 
                            Brighton, MI<br /> 
                            48116<br /> 
                            <span class="FormElements">Phone:</span> (810) 333-6100<br /> 
                            <a href="mailto:sales@mobsteel.com">sales@mobsteel.com</a></td> 
                        </tr> 
                        <tr> 
                          <td colspan="4" align="left">&nbsp;</td> 
                        </tr> 
                        <tr> 
                          <td width="81" align="left">First Name <span class="Required">*</span> </td> 
                          <td width="132" align="left"><input name="FirstName" type="text" class="FormElements" id="FirstName" /></td> 
                          <td width="74" align="left">Last Name <span class="Required">*</span> </td> 
                          <td width="123" align="left"><input name="LastName" type="text" class="FormElements" id="LastName" /></td> 
                        </tr> 
                        <tr> 
                          <td align="left">Email <span class="Required">*</span> </td> 
                          <td align="left"><input name="Email" type="text" class="FormElements" id="Email" /></td> 
                          <td align="left">Phone <span class="Required">*</span> </td> 
                          <td align="left"><input name="Phone" type="text" class="FormElements" id="Phone" /> 
                            <br /> 
                            <span class="FormElements">(ie xxx-xxx-xxxx)</span></td> 
                        </tr> 
                        <tr> 
                          <td align="left">Address</td> 
                          <td align="left"><input name="Address" type="text" class="FormElements" id="Address" /></td> 
                          <td align="left">City</td> 
                          <td align="left"><input name="City" type="text" class="FormElements" id="City" /></td> 
                        </tr> 
                        <tr> 
                          <td align="left">State</td> 
                          <td align="left"><select name="State" id="State" class="FormElements"> 
                              <option>Choose</option> 
                              <option value="Alabama">Alabama</option> 
                              <option value="Alaska">Alaska</option> 
                              <option value="Arizona">Arizona</option> 
                              <option value="Arkansas">Arkansas</option> 
                              <option value="California">California</option> 
                              <option value="Colorado">Colorado</option> 
                              <option value="Connecticut">Connecticut</option> 
                              <option value="Delaware">Delaware</option> 
                              <option value="DC">DC</option> 
                              <option value="Florida">Florida</option> 
                              <option value="Georgia">Georgia</option> 
                              <option value="Hawaii">Hawaii</option> 
                              <option value="Idaho">Idaho</option> 
                              <option value="Illinois">Illinois</option> 
                              <option value="Indiana">Indiana</option> 
                              <option value="Iowa">Iowa</option> 
                              <option value="Kansas">Kansas</option> 
                              <option value="Kentucky">Kentucky</option> 
                              <option value="Louisiana">Louisiana</option> 
                              <option value="Maine">Maine</option> 
                              <option value="Maryland">Maryland</option> 
                              <option value="Massachusetts">Massachusetts</option> 
                              <option value="Michigan">Michigan</option> 
                              <option value="Minnesota">Minnesota</option> 
                              <option value="Mississippi">Mississippi</option> 
                              <option value="Missouri">Missouri</option> 
                              <option value="Montana">Montana</option> 
                              <option value="Nebraska">Nebraska</option> 
                              <option value="Nevada">Nevada</option> 
                              <option value="New Hampshire">New Hampshire</option> 
                              <option value="New Jersey">New Jersey</option> 
                              <option value="New Mexico">New Mexico</option> 
                              <option value="New York">New York</option> 
                              <option value="North Carolina">North Carolina</option> 
                              <option value="North Dakota">North Dakota</option> 
                              <option value="Ohio">Ohio</option> 
                              <option value="Oklahoma">Oklahoma</option> 
                              <option value="Oregon">Oregon</option> 
                              <option value="Pennsylvania">Pennsylvania</option> 
                              <option value="Rhode Island">Rhode Island</option> 
                              <option value="South Carolina">South Carolina</option> 
                              <option value="South Dakota">South Dakota</option> 
                              <option value="Tennessee">Tennessee</option> 
                              <option value="Texas">Texas</option> 
                              <option value="Utah">Utah</option> 
                              <option value="Vermont">Vermont</option> 
                              <option value="Virginia">Virginia</option> 
                              <option value="Washington">Washington</option> 
                              <option value="West Virginia">West Virginia</option> 
                              <option value="Wisconsin">Wisconsin</option> 
                              <option value="Wyoming">Wyoming</option> 
                            </select></td> 
                          <td align="left">Zip</td> 
                          <td align="left"><input name="Zip" type="text" class="FormElements" id="Zip" size="10" /></td> 
                        </tr> 
                        <tr> 
                          <td align="left"><p>Questions and Comments</p> 
                            <p>&nbsp;</p></td> 
                          <td colspan="3" align="left" valign="top"><textarea name="QuestionsComments" cols="60" rows="6" class="FormElements" id="QuestionsComments"></textarea></td> 
                        </tr> 
                        <tr> 
                          <td colspan="4" align="left"><input name="Newsletter" type="checkbox" class="FormElements" id="Newsletter" value="Yes" checked="checked" /> 
                            Put me on 'The Hit List' to receive exclusive Mobsteel information. </td> 
                        </tr> 
                        <tr> 
                          <td align="left">&nbsp;</td> 
                          <td align="left">&nbsp;</td> 
                          <td align="left">&nbsp;</td> 
                          <td align="left"><input name="image" type="image" onclick="MM_validateForm('FirstName','','R','LastName','','R','Email','','RisEmail','Phone','','R');return document.MM_returnValue" src="images/submit.png" /></td> 
                        </tr> 
                        <tr> 
                          <td colspan="4" align="left" class="FormElements"><span class="Required">*</span> indicates required field </td> 
                        </tr> 
                      </table> 
                      <input type="hidden" name="MM_insert" value="MobsteelContactForm"> 
                    </form></td> 
                  <td width="10" class="PageText">&nbsp;</td> 
                </tr> 
              </table></td> 
          </tr> 
          <tr> 
            <td valign="top"><img src="images/content_bottom.png" width="535" height="17" /></td> 
          </tr> 
        </table></td> 
      <td width="245" align="left" valign="top"><?php include('includes/video_sidebar.php'); ?></td> 
    </tr> 
    <tr> 
      <td colspan="2"><?php include('includes/footer.php'); ?></td> 
    </tr> 
  </table> 
</div> 
</body>
</html>
