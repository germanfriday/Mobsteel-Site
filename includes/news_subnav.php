<?php

switch ($subsection)

{

	case "news_sub";

		$news_sub = "_f2";

		break;
		
	case "press";

		$press = "_f2";

		break;
		
	case "calendar";

		$calendar = "_f2";

		break;
		
	case "old";

		$old = "_f2";

		break;

		

}

?>


<table width="175" border="0" cellspacing="0" cellpadding="2">

  <tr>

    <td>&nbsp;</td>
  </tr>
  
  <tr>
<td><a href="news.php"><img src="images/news_butt<? echo $news_sub ?>.jpg" alt="News & Events" width="175" height="39" border="0" id="news_nav_1" onMouseOver="MM_swapImage('news_nav_1','','images/news_butt_f2.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></td>
  </tr>
  
    <tr>
<td><a href="press.php"><img src="images/press_butt<? echo $press ?>.jpg" alt="News & Events" width="175" height="39" border="0" id="news_nav_2" onMouseOver="MM_swapImage('news_nav_2','','images/press_butt_f2.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></td>
  </tr>

  <!--<tr>
    <td><a href="calendar.php"><img src="images/calendar_butt<? echo $calendar ?>.jpg" alt="Calendar" width="175" height="39" border="0" id="news_nav_3" onMouseOver="MM_swapImage('news_nav_3','','images/calendar_butt_f2.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></td>
  </tr>-->
  
    <tr>
<td><a href="old_shit.php"><img src="images/old_butt<? echo $old ?>.jpg" alt="Old Shit" width="175" height="39" border="0" id="news_nav_4" onMouseOver="MM_swapImage('news_nav_4','','images/old_butt_f2.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></td>
  </tr>
</table>

