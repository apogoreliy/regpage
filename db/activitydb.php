<?php
// DATA BASE QUERY

// ACTIVITY

function db_getActivityList ($start, $stop, $locality, $page, $admins, $availableAdmins)
{
    global $db;
    $availableAdmins = implode( "','", $availableAdmins);
    $locality = $db->real_escape_string($locality);
    $page = $db->real_escape_string($page);
    $admins = $db->real_escape_string($admins);
    $requestLocality = $locality=="_all_" ? "" : " AND lo.key='$locality' ";
    $requestPage = $page == "_all_" ? "" : " AND act.page='$page' ";
    $requestAdmins = $admins == "_all_" ? " AND ad.member_key IN ('".$availableAdmins."') " : " AND ad.member_key='$admins' ";
    $res=db_query ("SELECT act.id as id_string, act.admin_key as id, act.page as page, act.time_create as time, m.name as name, lo.name as locality_name, lo.key as locality_key
    FROM activity_log as act
    INNER JOIN admin ad ON ad.member_key=act.admin_key
    INNER JOIN member m ON act.admin_key = m.key
    INNER JOIN locality lo ON lo.key=m.locality_key
    WHERE act.time_create > '$start' AND act.time_create < '$stop' $requestLocality $requestPage $requestAdmins ORDER BY name");

    $members = array ();
    while ($row = $res->fetch_object()) $members[]=$row;
    return $members;
}
function db_getActivityListLog ($start, $stop, $locality, $page, $admins)
{
    global $db;
    $locality = $db->real_escape_string($locality);
    $page = $db->real_escape_string($page);
    $admins = $db->real_escape_string($admins);
    $requestLocality = $locality=="_all_" ? "" : " AND lo.key='$locality' ";
    $requestPage = $page == "_all_" ? "" : " AND act.page='$page' ";
    $requestAdmins = $admins == "_all_" ? "" : " AND ad.member_key='$admins' ";
    $res=db_query ("SELECT act.id as id_string, act.admin_key as id, act.page as page, act.time_create as time, m.name as name, lo.name as locality_name, lo.key as locality_key
    FROM activity_log as act
    INNER JOIN admin ad ON ad.member_key=act.admin_key
    INNER JOIN member m ON act.admin_key = m.key
    INNER JOIN locality lo ON lo.key=m.locality_key
    WHERE act.time_create > '$start' AND act.time_create < '$stop' $requestLocality $requestPage $requestAdmins ORDER BY name");

    $members = array ();
    while ($row = $res->fetch_object()) $members[]=$row;
    return $members;
}

?>
