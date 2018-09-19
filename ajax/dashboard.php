<?php
include_once "ajax.php";

$adminId = db_getMemberIdBySessionId (session_id());
if (!$adminId)
{
    header("HTTP/1.0 401 Unauthorized");
    exit;
}

if (!isset ($_GET ['event']))
{
    header("HTTP/1.0 400 Bad Request");
    exit;
}

if (isset ($_GET['sort_field']))
{
    $_SESSION['sort_field_'.$_GET ['event']]=$_GET ['sort_field'];
    $sort_field = $_GET ['sort_field'];
}
else if (isset ($_SESSION['sort_field_'.$_GET ['event']]))
    $sort_field = $_SESSION['sort_field_'.$_GET ['event']];
else
    $sort_field = 'name';

if (isset ($_GET['sort_type']))
{
    $_SESSION['sort_type_'.$_GET ['event']]=$_GET ['sort_type'];
    $sort_type = $_GET ['sort_type'];
}
else if (isset ($_SESSION['sort_type_'.$_GET ['event']]))
    $sort_type = $_SESSION['sort_type_'.$_GET ['event']];
else
    $sort_type = 'asc';

if(db_isAdminRespForReg($adminId, $_GET ['event'])){  
    echo json_encode(["members"=> db_getDashboardMembersService (
        $_GET ['event'], 
        isset ($_GET['attended']) ? $_GET['attended'] : NULL,
        isset ($_GET['regstate']) ? $_GET['regstate'] : NULL,
        $sort_field, $sort_type, 
        isset ($_GET['searchText']) ? $_GET['searchText'] : NULL,
        isset ($_GET['coord']) ? $_GET['coord'] : NULL,
        isset ($_GET['service']) ? $_GET['service'] : NULL,
        isset ($_GET['localityFilter']) ? $_GET['localityFilter'] : NULL),
        "localities"=>db_getEventLocalities ($_GET['event'])]);
}
else{
    echo json_encode(array ("members"=>db_getDashboardMembers ($adminId, $_GET ['event'], $sort_field, $sort_type, isset ($_GET['searchText'])? $_GET['searchText'] : NULL,
            isset ($_GET['regstate']) ? $_GET['regstate'] : NULL, isset ($_GET['localityFilter']) ? $_GET['localityFilter'] : NULL),
        "localities"=>db_getAdminEventLocalities ($_GET['event'], $adminId)));
}

?>