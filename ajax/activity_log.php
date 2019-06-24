<?php
include_once "ajax.php";

header('Content-type: text/plain');

if(isset($_GET["get_activity"])){
    echo json_encode(["members" => db_getActivityList($_GET['start'], $_GET['stop'], $_GET['locality'], $_GET['page'], $_GET['admins'])]);
    exit();
}
/*
else if(isset($_GET['add_activity'])){
    db_activityLogInsert($_POST['adminId'], $_POST['page']);

    exit();
}*/
?>
