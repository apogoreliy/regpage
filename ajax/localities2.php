<?php
include_once "ajax.php";
$memberId = db_getMemberIdBySessionId (session_id());
if (isset ($_GET ['query']))
{
    echo json_encode(array ("suggestions"=>db_getAdminLocalitiesAdmin ($_GET ['query'], $memberId)));
}
?>
