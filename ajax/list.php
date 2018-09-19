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
    $_SESSION['sort_field']=$_GET ['sort_field'];
    $sort_field = $_GET ['sort_field'];
}
else
    $sort_field = 'name';

if (isset ($_GET['sort_type']))
{
    $_SESSION['sort_type']=$_GET ['sort_type'];
    $sort_type = $_GET ['sort_type'];
}
else{
    $sort_type = 'asc';
}

echo json_encode(array (
    /*
    "regions"=>db_getRegionsList (isset ($_GET ['selCountry']) ? $_GET ['selCountry'] : null),
    "localities"=>db_getLocalListByRegion (isset ($_GET ['selRegion']) ? $_GET ['selRegion'] : null,
            isset ($_GET ['selCountry']) ? $_GET ['selCountry'] : null)
     */
    
    "regions"=>db_getRegionsList (),
    "localities"=>db_getLocalListByRegion (),
    "admins"=>db_getMemberListAdmins ($sort_field, $sort_type)
        
    /*

    "members"=>db_getMemberListAdmins ($sort_field, $sort_type,
        isset ($_GET ['selCountry']) ? $_GET ['selCountry'] : null,
        isset ($_GET ['selRegion']) ? $_GET ['selRegion'] : null,
        isset ($_GET ['selLocality']) ? $_GET ['selLocality'] : null,
        isset ($_GET ['searchText']) ? $_GET ['searchText'] : null)
     */
    )


);