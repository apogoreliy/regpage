<?php
include_once "ajax.php";
$memberId = db_getMemberIdBySessionId (session_id());
$adminCountries = db_getAdminCountryOnly($memberId);
$adminRegionsAjax = db_getAdminRegionOnly($memberId);
$adminLocations = db_getAdminLocalitiesOnly($memberId);
$admisList = db_getAdminsByLRC($memberId);

if(isset($_GET["get_activity"])){
    echo json_encode(["members" => db_getActivityList($_GET['start'], $_GET['stop'], $_GET['locality'], $_GET['page'], $_GET['admins'], $admisList)]);
    exit();
}
if(isset($_GET["get_admins_name"])){
    echo json_encode(array("members" => db_getAdminsNameByMembersKeys($admisList)));
    exit();
}
else if(isset($_GET["get_admins_by_country"])){
    echo json_encode(array("members" => db_getAdminsByCountry($adminCountries)));
    exit();
}
else if(isset($_GET["get_admins_by_region"])){
    echo json_encode(array("members" => db_getAdminsByRegion($adminRegionsAjax)));
    exit();
}
else if(isset($_GET["get_admins_by_locality"])){
    echo json_encode(array("members" => db_getAdminsByLocalitiesNew($adminLocations)));
    exit();
}
else if(isset($_GET["get_regions_by_admin"])){
    echo json_encode(array("regions" => db_getAdminRegionOnly($memberId)));
    exit();
}
else if(isset($_GET["get_country_by_admin"])){
    echo json_encode(array("countries" => db_getAdminCountryOnly($memberId)));
    exit();
}
else if(isset($_GET["get_locality_by_admin"])){
    echo json_encode(array("localities" => db_getAdminLocalitiesOnly($memberId)));
    exit();
}
else if(isset($_GET["get_all_my_admin"])){
    echo json_encode(array("localities" => db_getAdminsByLRC($memberId)));
    exit();
}

/*
else if(isset($_GET['get_admins_by_country'])){
    db_getAdminsBycountry($_POST['countryId']);
    exit();
}

else if(isset($_GET['add_activity'])){
    db_activityLogInsert($_POST['adminId'], $_POST['page']);

    exit();
}*/
?>
