<?php
include_once "../ajax/ajax.php";
include_once "panelDB.php";
include_once '../logWriter.php';

$adminId = db_getMemberIdBySessionId (session_id());
if (!$adminId)
{
    header("HTTP/1.0 401 Unauthorized");
    exit;
}
/*
if(isset($_GET['copy_sessions'])){
    echo json_encode(["result" =>db_copySessions($_POST['member'], $_POST['session'])]);
    exit();
}
else*/ if(isset($_GET['get_sessions'])){
    echo json_encode(["sessions"=>db_getSessionsAdmins()]);
    exit();
}
else if(isset($_GET['delete_old_sessions'])){
    echo json_encode(["sessions"=>db_delete_old_sessions()]);
    exit();
}
else if(isset($_GET['set_practices_pvom'])){
    echo json_encode(["result"=>db_setPracticesForStudentsPVOM()]);
    exit();
} else if(isset($_GET['get_statistics_status'])){
    echo json_encode(["result"=>db_statusStatisticsContacts()]);
    exit();
}

?>
