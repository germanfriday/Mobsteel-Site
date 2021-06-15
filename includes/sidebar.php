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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "Hit_List_Sign_Up"))
		  {
  $insertSQL = sprintf("INSERT INTO tbl_hitlist (FirstName, LastName, Email) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['LastName'], "text"),
                       GetSQLValueString($_POST['Email'], "text"));

  mysql_select_db($database_NewMobsteel, $NewMobsteel);
  $Result1 = mysql_query($insertSQL, $NewMobsteel) or die(mysql_error());
}
?>
<table width="223" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center"><a href="news.php"><img src="images/over_call.png" alt="AutoRama" border="0" /></a></td>
  </tr>
  
  
  <tr>
    <td><img src="images/hit_list_header.png" alt="Join The Hit List" width="223" height="103" /></td>
  </tr>
  <tr>
    <td height="278" align="center" valign="top" background="images/hit_list_bg.png"><form id="Hit_List_Sign_Up" name="Hit_List_Sign_Up" method="post" action="<?php echo $editFormAction; ?>">
        <table width="223" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td align="center" class="HitListText"><?php if (isset($_POST['MM_insert']))
		  { echo 'We\'ll tell you all you need to know';}
		  else
		  { echo 'Get our Newsletter';} ?>            </td>
          </tr>
          <tr>
            <td align="center"><input name="FirstName" type="text" class="FormElements" id="FirstName" value="First Name" size="12" />
              <input name="LastName" type="text" class="FormElements" id="LastName" value="Last Name" size="12" />            </td>
          </tr>
          <tr>
            <td align="center"><input name="Email" type="text" class="FormElements" id="Email" value="Email" /></td>
          </tr>
          <tr>
            <td align="center"><input name="image" type="image" src="images/keep_me_informed.png" /></td>
          </tr>
          <tr>
            <td align="center" valign="middle">
			<!--<script type="text/javascript" src="includes/flashobject.js"></script>

<div id="flashsidebar" style="width: 196px; height: 144px"></div>

<script type="text/javascript">
var fo = new FlashObject("flash/free_shipping.swf", "animationName", "196", "144", "8", "#FFFFFF");
fo.addParam("allowScriptAccess", "sameDomain");
fo.addParam("quality", "high");
fo.addParam("wmode", "transparent");
fo.addParam("scale", "noscale");
fo.addParam("loop", "false");
fo.write("flashsidebar");
</script>--></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="Hit_List_Sign_Up" />
      </form></td>
  </tr>
</table>
