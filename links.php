<?php
include_once 'header.php';
include_once 'nav.php';

$specPage = 'ul';
if ($specPage && trim($specPage)!='') {
	echo '<div class="container"><div style="background-color: white; padding: 20px;">';
    echo db_getCustomPage($specPage);
    echo'</div></div>';
}

include_once 'footer.php';
?>
