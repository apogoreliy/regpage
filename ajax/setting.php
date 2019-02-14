<?php
include_once "ajax.php";

$adminId = db_getMemberIdBySessionId (session_id());
if (!$adminId)
{
    header("HTTP/1.0 401 Unauthorized");
    exit;
}

if(isset($_GET['get_settings'])){
    echo json_encode(["user_settings" => db_getUserSettings($adminId), 
					  "settings"=>db_getSettings(),
					  "access_area"=>db_getAdminAccessAreas ($adminId),
					  "user_access_area_settings"=>db_getUserAccessAreaSettings($adminId)]);
    exit();
}
else if(isset($_GET['change_user_setting'])){
	db_updateUserSetting($adminId, $_GET['setting_key'], $_GET['is_checked']);
    echo json_encode(["res" => "ok"]);

    exit();
}
else if(isset($_GET['change_access_area'])){
	db_updateUserAccessAreaSetting($adminId, $_GET['setting_key'], $_GET['is_checked']);
    echo json_encode(["res" => "ok"]);

    exit();
}