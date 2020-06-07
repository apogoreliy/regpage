<?php
include_once "ajax.php";
include_once "../db/statisticdb.php";

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

if (isset($_GET['get_statistic'])){
    echo json_encode(["statistic"=>db_getStatisticStrings ($adminId, $_GET['localities'])]);
    exit();
}
elseif (isset($_GET['set_statistic'])){
  if (!getCheckDublicateStatistic($_GET['period_id'], $_GET['locality'])) {
    echo json_encode(["statistic"=>db_setNewStatistic($adminId, $_GET['locality'], $_GET['locality_status'], $_GET['period_id'], $_GET['bptz17'], $_GET['bptzAll'], $_GET['attended17'], $_GET['attended17_25'], $_GET['attended25'], $_GET['attended60'], $_GET['attendedAll'], $_GET['lt_MeetingAverage'], $_GET['archive'], $_GET['comment'], $_GET['statisticCompleteChkbox'])]);
    exit();
  } else {
    echo json_encode('error_001');
    exit();
  }
    //$_GET['period_date'],
}
elseif (isset($_GET['update_statistic'])){
    echo json_encode(["statistic"=>db_updateStatistic($adminId, $_GET['locality'], $_GET['locality_status'], $_GET['period_id'], $_GET['bptz17'], $_GET['bptzAll'], $_GET['attended17'], $_GET['attended17_25'], $_GET['attended25'], $_GET['attended60'], $_GET['attendedAll'], $_GET['lt_MeetingAverage'], $_GET['archive'], $_GET['comment'], $_GET['statisticCompleteChkbox'], $_GET['id_statistic'])]);
    exit();
    //$_GET['period_date'],
}
elseif (isset($_GET['get_member_name'])){
  echo json_encode (["statistic"=>db_getMemberNameEmail($_GET['memberId'])]);
  exit();
}
elseif (isset($_GET['get_members_statistic'])){
  echo json_encode (["statistic"=>getMembersStatistic($_GET['locality_key'])]);
  exit();
}
elseif (isset($_GET['delete_members_statistic'])){
  echo json_encode (db_deleteMembersStatistic($_GET['id_statistic']));
  exit();
}
