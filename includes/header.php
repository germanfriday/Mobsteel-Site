<?php

switch ($section)

{

	case "about";

		$about = "about";

		break;

		

	case "rides";

		$rides = "rides";

		break;

		

	case "news";

		$news = "news";

		break;



	case "free";

		$free = "free";

		break;

	

	case "contact";

		$contact = "contact";

		break;

		

}

?>
<script type="text/javascript" src="includes/flashobject.js"></script>

<div id="flashcontent" style="width: 800px; height: 225px"></div>

<script type="text/javascript">
var fo = new FlashObject("flash/<? echo $section ?>_container.swf", "animationName", "800", "225", "8", "#FFFFFF");
fo.addParam("allowScriptAccess", "sameDomain");
fo.addParam("quality", "high");
fo.addParam("wmode", "transparent");
fo.addParam("scale", "noscale");
fo.addParam("loop", "false");
fo.write("flashcontent");
</script>




