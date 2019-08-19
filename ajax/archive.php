<?php
include_once "ajax.php";

$adminId = db_getMemberIdBySessionId (session_id());
if (!$adminId)
{
    header("HTTP/1.0 401 Unauthorized");
    exit;
}

if (isset ($_GET['sort_field']))
{
    $_SESSION['sort_field-archive']=$_GET['sort_field'];
    $sort_field = $_GET ['sort_field'];
}
else
    $sort_field = 'start_date';

if (isset ($_GET['sort_type']))
{
    $_SESSION['sort_type-archive']=$_GET['sort_type'];
    $sort_type = $_GET['sort_type'];
}
else{
    $sort_type = 'DESC';
}

if(isset($_GET['get_events'])){
    echo json_encode(["events"=>db_getArchiveEvents($sort_type, $sort_field, $_GET['startDate'], $_GET['endDate'])]);
    exit();
}
else if(isset($_GET['get_members_list'])){
    echo json_encode(["list"=>db_getArchiveEventList($adminId, $_POST['event_id']),
        "services" => db_getServices()]);
    exit();
}
else if(isset ($_GET['get_members_statistic'])){
    echo json_encode(["list"=>getEventArchiveMembersStatistic($adminId, $_GET['event_type'], $_GET['startDate'], $_GET['endDate'], $_GET['text'])]);
    exit();
}
else if(isset ($_GET['get_general_archive'])){
    echo json_encode(["list"=>db_getArchiveGeneralEvents($adminId, 'desc', 'start_date', $_GET['locality'], $_GET['event'], $_GET['startDate'], $_GET['endDate'])]);
    exit();
}
else if(isset ($_GET['add_event'])){
    db_addEventArchive($adminId, $_POST);
    echo json_encode(["events"=>db_getArchiveEvents($sort_type, $sort_field, $_POST['startDate'], $_POST['endDate'])]);
    exit();
}
