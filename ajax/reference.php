<?php
include_once "ajax.php";

$adminId = db_getMemberIdBySessionId (session_id());
if (!$adminId)
{
    header("HTTP/1.0 401 Unauthorized");
    exit;
}


$sort_field = isset ($_COOKIE['sort_field_reference']) ? $_COOKIE ['sort_field_reference'] : 'name';
$sort_type = isset ($_COOKIE['sort_type_reference']) ? $_COOKIE ['sort_type_reference'] : 'asc';

if(isset($_GET['add'])){
    db_addReference($_POST);
}
else if(isset($_GET['set'])){
    db_setReference($_POST);
}
else if(isset($_GET['delete'])){
    db_deleteReference($_POST['id']);
}
else if(isset($_GET['set_field'])){
    db_setReferenceFieldValue($_POST['field'], $_POST['value'], $_POST['id']);
    
    echo json_encode(["res"=>"ok"]);
    exit();
}

echo json_encode(["references"=>db_getReferences($sort_field, $sort_type)]);