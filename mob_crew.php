<?php $section  = "about";?>
<?php $subsection  = "crew";?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Mobsteel Rides - To - Die - For</title>
<link href="includes/MobSteelStyles.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript" src="includes/pngfix.js"></script>
<script language="JavaScript" type="text/javascript" src="includes/common.js"></script>
</head>
<body>
<div align="center">
  <table width="800" border="0" cellpadding="0" cellspacing="0" class="CityBG">
    <tr>
      <td colspan="2"><?php include('includes/header.php'); ?></td>
    </tr>
    <tr>
      <td width="555" rowspan="2" align="right" valign="top"><table width="535" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><img src="images/mob_crew_header.png" alt="Long Live Detroit" width="535" height="64" /></td>
          </tr>
          <tr>
            <td valign="top" background="images/content_spacer.png"><table width="535" border="0" cellpadding="8" cellspacing="0" class="ContentBG">
                <tr>
                  <td class="PageText">&nbsp;</td>
                  <td align="center" class="PageText"><script type="text/javascript" src="includes/flashobject.js"></script>
                    <div id="mobcrew" style="width: 480px; height: 375px"></div>
                    <script type="text/javascript">
var fo = new FlashObject("flash/mobcrew.swf", "animationName", "480", "375", "8", "#000000");
fo.addParam("allowScriptAccess", "sameDomain");
fo.addParam("quality", "high");
fo.addParam("scale", "noscale");
fo.addParam("loop", "false");
fo.write("mobcrew");
</script></td>
                  <td class="PageText">&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td valign="top"><img src="images/content_bottom.png" width="535" height="17" /></td>
          </tr>
        </table></td>
      <td width="245" align="center" valign="top"><?php include('includes/about_subnav.php'); ?></td>
    </tr>
    <tr>
      <td align="left" valign="top"><?php include('includes/sidebar.php'); ?></td>
    </tr>
    <tr>
      <td colspan="2"><?php include('includes/footer.php'); ?></td>
    </tr>
  </table>
</div>
</body>
</html>
