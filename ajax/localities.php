<?php
include_once "ajax.php";

if (isset ($_GET ['query']))
{
    echo json_encode(array ("suggestions"=>db_findLocality ($_GET ['query'])));
}

?>
