<?php
	$page = explode('.', substr($_SERVER['PHP_SELF'], 1))[0];
	$s = $_SERVER['QUERY_STRING'];

	$textBlock = db_getTextBlock($page);     
    if ($textBlock && trim($textBlock)!='' && (!strlen($s) || strlen($s) > 2) ) echo "<div class='textblock container'><div class='alert'>". $textBlock ."</div></div>";
?>