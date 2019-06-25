<?php


// get my admin my regions
function db_getAdminByLocalityCombobox($locality_key){
    global $db;
    $locality = $db->real_escape_string($locality_key);

    $res = db_query ("
        SELECT DISTINCT m.key as id, m.name as name, a.login as email FROM member as m
        LEFT JOIN admin a ON a.member_key = m.key
        LEFT JOIN access ac ON ac.member_key = m.key
        LEFT JOIN country c ON c.key = ac.country_key
        LEFT JOIN region r ON r.country_key = c.key
        LEFT JOIN locality l ON l.region_key = r.key
        WHERE l.key='$locality' AND a.role>1
        UNION
        SELECT DISTINCT m.key as id, m.name as name, a.login as email FROM member as m
        LEFT JOIN admin a ON a.member_key = m.key
        LEFT JOIN access ac ON ac.member_key = m.key
        LEFT JOIN region r ON r.key = ac.region_key
        LEFT JOIN locality l ON l.region_key = r.key
        WHERE l.key='$locality' AND a.role>1
        UNION
        SELECT DISTINCT m.key as id, m.name as name, a.login as email FROM member as m
        LEFT JOIN admin a ON a.member_key = m.key
        LEFT JOIN access ac ON ac.member_key = m.key
        LEFT JOIN locality l ON ac.locality_key = l.key
        WHERE l.key='$locality' AND a.role>1 ");

    $admins = [];
    while ($row = $res->fetch_assoc()) $admins[]=$row['name'];
    //return $admins;
    /*while ($row = $res->fetch_assoc()){
        $admins [] = $row;
    }*/
    return ($admins) ? $admins : null;
}
/*
function db_getAdminsRegion($adminId){
    global $db;
    $adminId = $db->real_escape_string($adminId);

    $res=db_query ("SELECT c.key as country
                    FROM member m
                    INNER JOIN locality l ON l.key = m.locality_key
                    INNER JOIN region r ON r.key = l.region_key
                    INNER JOIN country c ON c.key = r.country_key
                    WHERE m.key='$adminId'");

    while ($row = $res->fetch_assoc()) $country=$row['country'];
    return $country;
}
function db_getLocalListByRegionNew()
{
    $res = db_query("SELECT CONCAT_WS (':', c.key, r.key, l.key) as locality_key, l.name FROM region r INNER JOIN locality l ON r.key=l.region_key INNER JOIN country c ON c.key=r.country_key");

    $localities = array ();
    while ($row = $res->fetch_object()) $localities[]=$row;
    return $localities;
}
function db_getAdminsMyRegionNew($adminId, $locality){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $locality = $locality === '_all_' ? '' : " AND l.key='".$db->real_escape_string($locality)."'";

    $res=db_query ("SELECT a.member_key
        FROM access a
        LEFT JOIN country c ON c.key = a.country_key
        LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
        INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
        LEFT JOIN district d ON d.locality_key=l.key
        INNER JOIN member m ON m.locality_key = l.key OR m.locality_key=d.district
        INNER JOIN admin ad ON a.member_key = a.key
        WHERE a.member_key='$adminId' $locality ");

    while($row = $res->fetch_assoc()){

    }
    return $count;
}

function db_getAdminsMyRegionCombobox ($adminId)
{
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $res=db_query ("SELECT a.member_key as id, a.locality_key, a.country_key, a.region_key
    FROM access as a
    WHERE member_key = '$adminId' ORDER BY id");

    $members = array ();
    while ($row = $res->fetch_assoc()) $members[$row['id']];
    return $members;
}

function db_getAdminMyRegion ($adminId)
{
    global $db;
    $adminId = $db->real_escape_string($adminId);

    $res=db_query ("SELECT DISTINCT * FROM (
                        SELECT m.key as id, m.name as name, l.name as locality, l.key as locId, m.category_key as catId
                        FROM access a
                        LEFT JOIN country c ON c.key = a.country_key
                        LEFT JOIN region r ON r.key = a.region_key or c.key=r.country_key
                        INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
                        INNER JOIN member m ON m.locality_key = l.key
                        WHERE a.member_key='$adminId'
                        UNION
                        SELECT m.key as id, m.name as name, COALESCE(l.name, m.new_locality) as locality,
                        l.key as locId, m.category_key as catId
                        FROM member m
                        LEFT JOIN locality l ON l.key=m.locality_key
                        WHERE m.admin_key='$adminId'
                        UNION
                        SELECT m.key as id, m.name as name, COALESCE(l.name, m.new_locality) as locality, l.key as locId,
                        m.category_key as catId
                        FROM reg
                        INNER JOIN member m ON m.key=reg.member_key
                        LEFT JOIN locality l ON l.key=m.locality_key
                        WHERE reg.admin_key='$adminId'
                        ) q ORDER BY q.name");

    $members = array ();
    while ($row = $res->fetch_assoc())
        $members[$row['id']]=array (
            "name" => $row['name'],
            "locality" => $row['locality'],
            "localityId" => $row['locId'],
            "categoryId" => $row['catId']
        );
    return $members;
}


function db_loginAdmin ($sessionId, $login, $password)
{
    global $db;
    $sessionId = $db->real_escape_string($sessionId);
    $login = $db->real_escape_string($login);
    $password = $db->real_escape_string($password);

    $res=db_query ("SELECT member_key FROM admin a inner join member m ON m.key=a.member_key WHERE a.login='$login' and a.password='$password' and m.active=1");
    if ($row = $res->fetch_assoc())
    {
        $adminId = $row['member_key'];

        db_query ("UPDATE admin SET session='$sessionId' WHERE member_key='$adminId'");
        return $adminId;
    }
    return NULL;
}

function db_logoutAdmin ($adminId)
{
    global $db;
    $adminId = $db->real_escape_string($adminId);
    db_query ("UPDATE admin SET session=NULL WHERE member_key='$adminId'");
}
*/
?>
