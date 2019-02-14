<?php
include_once "ajax.php";

$adminId = db_getMemberIdBySessionId (session_id());
if (!$adminId)
{
    header("HTTP/1.0 401 Unauthorized");
    exit;
}

if(isset($_GET['downloadExcelData'])){
    $res = db_downloadExcelData($_POST['members']);

    echo json_encode(["result" =>$res]);
    exit();
}

if (isset ($_GET['sortedFields']['sort_field']))
{
    $_SESSION['sort_field-members']=$_GET ['sortedFields']['sort_field'];
    $sort_field = $_GET ['sortedFields']['sort_field'];
}
else if (isset ($_POST['sortedFields']['sort_field']))
{
    $_SESSION['sort_field-members']=$_POST ['sortedFields']['sort_field'];
    $sort_field = $_POST ['sortedFields']['sort_field'];
}
else
    $sort_field = 'name';

if (isset ($_GET['sortedFields']['sort_type']))
{
    $_SESSION['sort_type-members']=$_GET ['sortedFields']['sort_type'];
    $sort_type = $_GET ['sortedFields']['sort_type'];
}
else if (isset ($_POST['sortedFields']['sort_type']))
{
    $_SESSION['sort_type-members']=$_POST ['sortedFields']['sort_type'];
    $sort_type = $_POST ['sortedFields']['sort_type'];
}
else{
    $sort_type = 'asc';
}

if (isset ($_GET ['member']) && isset ($_GET ['active'])){
    db_setMemberActive ($adminId, $_GET ['member'], $_GET ['active'], isset ($_GET ['reason']) ? $_GET ['reason'] : null);
}

if(isset($_GET['setCollege'])){
    db_setCollege (isset($_POST['collegeId']) ? $_POST['collegeId'] : null, $_POST['name'], $_POST['shortName'], $_POST['locality'], $adminId);
    echo json_encode(["colleges" => db_getColleges($adminId)]);
    exit();
}

if(isset($_GET['getColleges'])){
    echo json_encode(["colleges" => db_getColleges($adminId), 
        "localities" => db_getCollegesLocality()]);
    exit();
}

if(isset($_GET['deleteCollege'])){
    db_deleteCollege($_POST['collegeId']);
    echo json_encode(["colleges" => db_getColleges($adminId)]);
    exit();
}

if(isset($_GET['is_member_in_reg'])){
    echo json_encode(["res" => db_isMemberRegistrated($_POST['memberId'])]);
    exit();
}

if(isset($_GET['set_attend_meeting'])){
    echo json_encode(["result" => db_setAttendMeeting($_POST['value'], $_POST['memberId'])]);
    exit();
}

if(isset($_GET['add_filter'])){
    db_addFilter($_GET['filter_name'], $adminId);
    
    echo json_encode(["filters" => db_getAdminFilters($adminId)]);
    exit();
}

if(isset($_GET['get_filters'])){
    echo json_encode(["filters" => db_getAdminFilters($adminId)]);
    exit();
}

if(isset($_GET['show_filter'])){
    echo json_encode(["filters" => db_getAdminFilters($adminId)]);
    exit();
}

if(isset($_GET['save_filter_localities'])){
    db_saveFilterLocalities($_GET['filter_id'], $_GET['filter_localities']);

    echo json_encode(["filters" => db_getAdminFilters($adminId)]);
    exit();
}

if(isset($_GET['save_filter'])){
    db_saveFilter($_GET['filter_id'], $_GET['filter_name']);

    echo json_encode(["filters" => db_getAdminFilters($adminId)]);
    exit();
}

if(isset($_GET['remove_filter'])){
    db_removeFilter($_GET['filter_id']);

    echo json_encode(["filters" => db_getAdminFilters($adminId)]);
    exit();
}

if(isset($_GET['get_localities'])){

    echo json_encode(["localities" => db_getAdminLocalitiesWithFilters($adminId)]);
    exit();
}


if(isset($_GET['remove'])){
    db_removeMember($_POST['memberId']);
}

if (isset ($_GET ['update_member'])){
    db_setEventMember ($adminId, $_GET, $_POST);
}

echo json_encode(array("members" => db_getMemberListCopy($adminId, $sort_field, $sort_type)));
