<?php
// Ajax
include_once "ajax.php";
include_once "../db/practicesdb.php";
$adminId = db_getMemberIdBySessionId (session_id());

if (!$adminId)
{
    header("HTTP/1.0 401 Unauthorized");
    exit;
}

if(isset($_GET['new_practices'])){
    echo json_encode(db_newDayPractices($adminId));
    exit();
}

if(isset($_GET['update_practices_today'])){
    echo json_encode(db_updateTodayPractices($adminId, $_GET['user_data']));
    exit();
}

if(isset($_GET['update_practices_edit'])){
    echo json_encode(db_updatePracticesByAdmin($_GET['id'], $_GET['user_data']));
    exit();
}

if(isset($_GET['get_practices'])){
    echo json_encode(["practices"=>db_getPractices($adminId)]);
    exit();
}
// for development
if(isset($_GET['get_practices_all'])){
    echo json_encode(["practices"=>db_getPracticesAll()]);
    exit();
}

if(isset($_GET['get_practices_for_admin'])){
    echo json_encode(["practices"=>db_getPracticesForAdmin($_GET['data'])]);
    exit();
}

if(isset($_GET['get_practices_today'])){
    echo json_encode(["practices"=>db_getPracticesToday($adminId)]);
    exit();
}

?>
