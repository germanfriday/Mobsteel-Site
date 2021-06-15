<?php $section  = "about";?>
<?php $subsection  = "about_sub";?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Mobsteel Rides - To - Die - For</title>
<link href="includes/MobSteelStyles.css" rel="stylesheet" type="text/css" />
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
            <td><img src="images/about_header.png" alt="Long Live Detroit" width="535" height="64" /></td>
          </tr>
          <tr>
            <td valign="top" background="images/content_spacer.png"><table width="535" border="0" cellpadding="8" cellspacing="0" class="ContentBG">
                <tr>
                  <td class="PageText">&nbsp;</td>
                  <td align="center" valign="middle" class="Video_BG"><script type="text/javascript" src="includes/flashobject.js"></script>
                    <div id="flashvideo" style="width: 400px; height: 320px"></div>
                    <script type="text/javascript">
var fo = new FlashObject("flash/about_video.swf", "animationName", "400", "320", "8", "#FFFFFF");
fo.addParam("allowScriptAccess", "sameDomain");
fo.addParam("quality", "high");
fo.addParam("wmode", "transparent");
fo.addParam("scale", "noscale");
fo.addParam("loop", "false");
fo.write("flashvideo");
</script>
                  </td>
                  <td class="PageText">&nbsp;</td>
                </tr>
                <tr>
                  <td class="PageText">&nbsp;</td>
                  <td align="left" valign="middle" class="PageText">Since Henry Ford set up shop here, Detroit&rsquo;s had a rich history of building great cars. At one time Detroit was the heart beat of this whole country.  With innovative spirit, great design, and tough work ethic the past generations have laid a permanent template of how the industry operates today. We also don't kid ourselves, Detroit is now known for its money, power, murder, crime and corruption. We embody these two legacies in the cars we build. These luxury vehicles once driven by powerful influencers of the past are brought back to life as modern day, &ldquo;true gangster rides&rdquo;.<br />
                    <br />
                    When you roll up in a Mobsteel edition ride heads turn and people are not sure if you are going to step out in a suit or with a sawed off shot gun.  Let them think what they want, but know this, they will be thinking&hellip;<br />
                    <br />
                    We start with Detroit's finest vintage luxury cars and turn them into modern day menacing street machines with the technology and features only available today. The cars we build are a part of American history; Vintage Detroit steel, not some fiberglass repro. Steel with soul and a story. The blood, sweat and tears of our family and friends are in these motors, frames and bodies. At Mobsteel we take it to the next level.</td>
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
