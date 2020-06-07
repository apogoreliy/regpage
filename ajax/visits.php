<?php
include_once "ajax.php";
include_once "../db/visitsdb.php";

$adminId = db_getMemberIdBySessionId (session_id());

if (!$adminId)
{
    header("HTTP/1.0 401 Unauthorized");
    exit;
}


if (isset ($_GET['sort_field']))
{
    $_SESSION['sort_field-meetings']=$_GET['sort_field'];
    $sort_field = $_GET ['sort_field'];
}
else
    $sort_field = 'date_visit';

if (isset ($_GET['sort_type']))
{
    $_SESSION['sort_type-meetings']=$_GET['sort_type'];
    $sort_type = $_GET['sort_type'];
}
else{
    $sort_type = 'DESC';
}

if(isset($_GET['get_visits'])){
    echo json_encode(["meetings"=>db_getVisits($adminId, $sort_type, $sort_field, $_GET['localityFilter'], $_GET['meetingFilter'], $_GET['startDate'],$_GET['endDate'])]);
    exit();
}
else if(isset($_GET['set_date_visit'])){
    echo json_encode(["result" => db_setDateVisit($_POST['value'], $_POST['visitId'])]);
    exit();
}
else if(isset($_GET['set_status_visit'])){
    echo json_encode(["result" => db_setPerformedVisit($_POST['value'], $_POST['visitId'])]);
    exit();
}
else if(isset ($_GET['remove'])){
    db_removeVisit($_POST['meeting_id']);
    echo json_encode(["meetings"=>db_getVisits($adminId, $sort_type, $sort_field, $_GET['localityFilter'], $_GET['meetingFilter'], $_GET['startDate'],$_GET['endDate'])]);
    exit();
}
else if(isset($_GET['set_visit'])){
    $isDoubleMeeting = db_setVisit($_POST);

    echo json_encode(["meetings"=>db_getVisits($adminId, $sort_type, $sort_field, $_GET['localityFilter'], $_GET['meetingFilter'], $_GET['startDate'],$_GET['endDate']),
                      "isDoubleMeeting"=>$isDoubleMeeting]);
    exit();
}
