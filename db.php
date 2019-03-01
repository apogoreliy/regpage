<?php

define ('DONT_CHANGE',"_dont_change_");
define ('EVENT_TYPE',"event");
define ('USER_TYPE',"user");
define ('COLLEGE_TYPE',"college");

date_default_timezone_set ("Europe/Moscow");
require_once 'FirePHP.class.php';
require_once 'config.php';
include_once 'utils.php';

/*
class ValidationException extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
*/

// establish mySQLi connection

$db->set_charset('utf8');
if ($db->connect_errno) die('Could not connect: '.$mysqli->connect_error);

// make a query to the database
function db_query ($query)
{
  global $db;
  $res=$db->query ($query);
  if (!$res) throw new Exception ($db->error);
  return $res;
}

function db_multiQuery ($query)
{
    global $db;
    $res=$db->multi_query ($query);
    if (!$res) throw new Exception ($db->error);
    return $res;
}

function db_checkSync ()
{
    $res=db_query ("SELECT value FROM param WHERE name='sync_started'");
    if ($row = $res->fetch_assoc())
    {
        if ($row['value'])
        {
            $diff = time()-strtotime($row['value']);
            if ($diff>1800)
                db_query ("UPDATE param SET value=NULL WHERE name='sync_started'");
            else
                throw new Exception("В данный момент база данных находится в процессе синхронизации. Повторите попытку позже.");
        }
    }
}

function db_getSelfRegPwd ()
{
    $res=db_query ("SELECT value FROM param WHERE name='self_reg_pwd'");
    if ($row = $res->fetch_assoc()) return $row['value'];
    return null;
}

function db_getMemberIdBySessionId ($sessionId)
{
    global $db;
    $sessionId = $db->real_escape_string($sessionId);

    $res=db_query ("SELECT member_key from admin where session='$sessionId'");
    if ($row = $res->fetch_assoc()) return $row['member_key'];
    return NULL;
}

function db_getCategories ()
{
    $res=db_query ("SELECT `key` as id, name FROM category");
    $array = array ();
    while ($row = $res->fetch_assoc()) $array[$row['id']]=$row['name'];
    return $array;
}

function db_getSpecPages ()
{
    $res=db_query ("SELECT name FROM custom_page WHERE name LIKE '__'");
    $array = array ();
    while ($row = $res->fetch_assoc()) $array[]=$row['name'];
    return $array;
}

function db_getEventInfo ($eventId)
{
    global $db;
    $eventId = $db->real_escape_string($eventId);
    $res=db_query ("SELECT info FROM event WHERE `key`='$eventId'");
    $row = $res->fetch_assoc();
    return $row ? $row['info'] : "";
}

function db_getEventInfoForAdmins ($eventId)
{
    global $db;
    $eventId = $db->real_escape_string($eventId);
    $res=db_query ("SELECT name FROM event WHERE `key`='$eventId'");
    $row = $res->fetch_assoc();
    return $row ? $row['name'] : "";
}

function db_getEventInvitation ($eventId)
{
    global $db;
    $eventId = $db->real_escape_string($eventId);
    $res=db_query ("SELECT invitation FROM event WHERE `key`='$eventId'");
    $row = $res->fetch_assoc();
    return $row ? $row['invitation'] : "";
}
/*
function db_getService($memberId){
    global $db;

    $memberId = $db->real_escape_string($memberId);
    $res = db_query("SELECT `key` FROM event_access WHERE `member_key` = '$memberId' ORDER BY `key`");
    $events = array();
    while($row = $res->fetch_object()) $events[]=$row;
    return $events;
}
*/

function db_getEv ($eventId){
    global $db;

    $eventId = $db->real_escape_string($eventId);
    $res = db_query("SELECT `key` as id, name, start_date, end_date, regend_date, info, need_passport, need_transport, need_prepayment, private FROM event WHERE `key` = '$eventId' ORDER BY start_date");
    $events = array();
    while ($row = $res->fetch_object())$events[]=$row;
    return $events;
}

function db_getEventMessages ($eventId)
{
    global $db;
    $eventId = $db->real_escape_string($eventId);
    $res=db_query ("SELECT reg_message, save_message FROM event WHERE `key`='$eventId'");
    return $res->fetch_assoc();
}

function db_getMemberNameEmail ($memberId)
{
    global $db;
    $memberId = $db->real_escape_string($memberId);
    $res=db_query ("SELECT name, email FROM member WHERE `key`='$memberId'");
    $row = $res->fetch_assoc();
    return $row ? array ($row['name'], $row['email']) : array ('','');
}

function db_getMemberNameEmailShort ($memberId)
{
    global $db;
    $memberId = $db->real_escape_string($memberId);
    $res=db_query ("SELECT name, email FROM member WHERE `key`='$memberId'");
    $row = $res->fetch_assoc();
    $pieces = explode(" ", $row['name']);
    $firstname = $pieces[1];
    $secondname = $pieces[2];

    $res = $pieces[0].$firstname{0}.$secondname{0};
    return $row ? array ($res, $row['email']) : array ('','');
}

function db_getMemberLocality ($memberId)
{
    global $db;
    $memberId = $db->real_escape_string($memberId);
    $res=db_query ("SELECT l.name FROM member m INNER JOIN locality l ON l.key=m.locality_key WHERE m.key='$memberId'");
    $row = $res->fetch_assoc();
    return $row ? $row['name'] : '';
}

function db_getPermalink ($memberId, $eventId)
{
    global $db;
    $memberId = $db->real_escape_string($memberId);
    $eventId = $db->real_escape_string($eventId);
    $res=db_query ("SELECT permalink FROM reg WHERE member_key='$memberId' and event_key='$eventId'");
    $row = $res->fetch_assoc();
    return $row ? $row['permalink'] : '';
}

function db_getTeamEmail ($eventId)
{
    global $db;
    $eventId = $db->real_escape_string($eventId);
    $res=db_query ("SELECT t.email FROM team t INNER JOIN event e ON e.team_key=t.key WHERE e.key='$eventId'");
    $row = $res->fetch_assoc();
    return $row ? $row['email'] : null;
}

function db_getDocuments ()
{
    global $db;

    $res=db_query ("SELECT `key` as id, name FROM document ORDER BY name");
    $array = array ();
    while ($row = $res->fetch_assoc()) $array[$row['id']]=$row['name'];
    return $array;
}

function db_getStatuses ()
{
    $res=db_query ("SELECT `key` as id, name FROM status ORDER BY name");
    $array = array ();
    while ($row = $res->fetch_assoc()) $array[$row['id']]=$row['name'];
    return $array;
}

function db_getServices ()
{
    $res=db_query ("SELECT `key` as id, name FROM service ORDER BY name");
    $array = array ();
    while ($row = $res->fetch_assoc()) $array[$row['id']]=$row['name'];
    return $array;
}

function db_getProfile ($memberId)
{
    global $db;
    $memberId = $db->real_escape_string($memberId);
    $res=db_query ("SELECT m.key as member_key, m.name,
                    CASE WHEN m.male=1 THEN 'male' WHEN m.male=0 THEN 'female' ELSE '' END as gender, m.male,
                    m.birth_date, m.locality_key, m.address, m.home_phone, m.cell_phone, a.login as email,
                    m.category_key, m.document_key, m.document_num, m.document_date, m.document_auth,
                    m.new_locality, m.citizenship_key, m.admin_key as mem_admin, m.baptized,
                    IF (rg.name='--',l.name,CONCAT (l.name,', ',rg.name)) as locality_name,
                    1 as need_passport, m.comment as admin_comment, m.active, m.school_comment,
                    m.english, m.tp_num, m.tp_date, m.tp_auth, m.tp_name, 1 as need_tp, m.russian_lg,
                    m.school_start, m.school_end, m.college_start, m.college_end, m.college_key, m.college_comment,
                    DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365 as age, c.locality_key as college_city
                    FROM member as m
                    INNER JOIN admin a ON a.member_key = m.key
                    LEFT JOIN locality l ON m.locality_key=l.key
                    LEFT JOIN region rg ON l.region_key=rg.key
                    LEFT JOIN college c ON m.college_key=c.key
                    WHERE m.key='$memberId'");
    if ($row = $res->fetch_assoc()) return $row;
    return NULL;
}

function db_getMember ($memberId)
{
    global $db;
    $memberId = $db->real_escape_string($memberId);
    $res=db_query ("SELECT m.key as member_key, m.name,
                    CASE WHEN m.male=1 THEN 'male' WHEN m.male=0 THEN 'female' ELSE '' END as gender, m.male,
                    m.birth_date, m.locality_key, m.address, m.home_phone, m.cell_phone, m.email,
                    m.category_key, m.document_key, m.document_num, m.document_date, m.document_auth,
                    m.new_locality, m.citizenship_key, m.admin_key as mem_admin, m.baptized, m.attend_meeting,
                    IF (rg.name='--',l.name,CONCAT (l.name,', ',rg.name)) as locality_name,
                    1 as need_passport, m.comment as admin_comment, m.active, m.school_comment,
                    m.english, m.tp_num, m.tp_date, m.tp_auth, m.tp_name, 1 as need_tp, m.russian_lg,
                    m.school_start, m.school_end, m.college_start, m.college_end, m.college_key, m.college_comment,
                    DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365 as age, c.locality_key as college_city,
                    c.name as college_name, c.short_name as college_short_name
                    FROM member as m
                    LEFT JOIN locality l ON m.locality_key=l.key
                    LEFT JOIN region rg ON l.region_key=rg.key
                    LEFT JOIN college c ON m.college_key=c.key
                    WHERE m.key='$memberId'");
    if ($row = $res->fetch_assoc()) return $row;
    return NULL;
}

function db_getAdminAsMember ($memberId)
{
    global $db;
    $memberId = $db->real_escape_string($memberId);
    $res=db_query ("SELECT a.notice_info, a.notice_reg FROM admin as a WHERE a.member_key='$memberId'");
    if ($row = $res->fetch_assoc()) return $row;
    return NULL;
}

$selectEventMember = "SELECT m.key as member_key, m.name, CASE WHEN m.male=1 THEN 'male' WHEN m.male=0 THEN 'female' ELSE '' END as gender, m.male,
                      m.birth_date, m.locality_key, m.address, m.home_phone, m.cell_phone, m.email,
                      r.arr_date, r.arr_time, r.dep_date, r.dep_time, r.accom, r.coord, r.temp_phone, r.transport,
                      r.mate_key, r.comment, r.admin_comment, r.status_key, m.category_key, m.document_key,
                      m.document_num, m.document_date, m.document_auth, m.new_locality, r.regstate_key, m.citizenship_key,
                      m.admin_key as mem_admin, r.admin_key as reg_admin, r.parking, e.name as event_name,
                      e.key as event_key, e.need_passport, e.need_transport, e.need_prepayment, e.start_date, e.end_date,
                      IF (rg.name='--',l.name,CONCAT (l.name,', ',rg.name)) as locality_name, r.permalink, r.attended,
                      r.place, r.prepaid, e.organizer, e.need_parking, e.need_service, e.need_accom,
                      CASE WHEN r.currency IS NULL THEN e.currency ELSE r.currency END as currency,
                      CASE WHEN r.contrib=0 THEN e.contrib ELSE r.contrib END as contrib, r.avtomobile, r.avtomobile_number,
                      r.service_key, m.english, e.need_tp, e.need_flight, m.tp_num, m.school_comment,
                      m.tp_date, m.tp_auth, m.tp_name, r.flight_num_arr, e.need_address, e.list_name, e.list,
                      r.list_name as reg_list_name,
                      r.flight_num_dep, r.note, m.russian_lg, r.visa, m.baptized, m.attend_meeting,
                      r.aid_paid, r.aid, r.fellowship, r.contr_amount, r.trans_amount, c.key as country_key,
                      m.school_start, m.school_end, m.college_start, m.college_end, m.college_key, m.college_comment,
                      DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365 as age, cl.locality_key as college_city
                      FROM member as m
                      INNER JOIN reg r ON r.member_key=m.key
                      INNER JOIN event e ON r.event_key=e.key
                      LEFT JOIN locality l ON m.locality_key=l.key
                      LEFT JOIN region rg ON l.region_key=rg.key
                      LEFT JOIN country c ON c.key=rg.country_key
                      LEFT JOIN college cl ON cl.key=m.college_key";

function db_getEventMember ($memberId, $eventId)
{
    global $db, $selectEventMember;
    $memberId = $db->real_escape_string($memberId);
    $eventId = $db->real_escape_string($eventId);

    $res=db_query ("$selectEventMember WHERE m.key='$memberId' AND r.event_key='$eventId' ");

    if ($row = $res->fetch_assoc()) return $row;
    return NULL;
}

function db_getEventMemberByLink ($link)
{
    global $db, $selectEventMember;
    $link = $db->real_escape_string($link);
    $res=db_query ("$selectEventMember WHERE r.permalink='$link'");
    if ($row = $res->fetch_assoc()) return $row;
    return NULL;
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

function db_getAdminCountry($adminId){
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

function db_getAdminLocalitiesWithFilters($adminId){
    global $db;
    $adminId = $db->real_escape_string($adminId);

    $res=db_query ("SELECT DISTINCT * FROM (
                    SELECT l.key as id, l.name as name
                    FROM access a
                    LEFT JOIN country c ON c.key = a.country_key
                    LEFT JOIN region r ON r.key = a.region_key or c.key=r.country_key
                    INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
                    LEFT JOIN member m ON m.locality_key = l.key
                    WHERE a.member_key='$adminId' and l.key is not null
                    UNION
                    SELECT f.value as id, f.name as name
                    FROM filter f
                    WHERE f.admin_key='$adminId' and f.value is not null
                    ) q ORDER BY q.name");

    $localities = array ();
    while ($row = $res->fetch_assoc()) $localities[]=$row;
    return $localities;

}

function db_getAdminLocalities ($adminId)
{
    global $db;
    $adminId = $db->real_escape_string($adminId);

    $res=db_query ("SELECT DISTINCT * FROM (
                    SELECT l.key as id, l.name as name
                    FROM access a
                    LEFT JOIN country c ON c.key = a.country_key
                    LEFT JOIN region r ON r.key = a.region_key or c.key=r.country_key
                    INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
                    LEFT JOIN member m ON m.locality_key = l.key
                    WHERE a.member_key='$adminId'
                    UNION
                    SELECT l.key as id, l.name as name
                    FROM member m
                    LEFT JOIN locality l ON l.key=m.locality_key
                    WHERE m.admin_key='$adminId'
                    UNION
                    SELECT l.key as id, l.name as name
                    FROM reg
                    INNER JOIN member m ON m.key=reg.member_key
                    LEFT JOIN locality l ON l.key=m.locality_key
                    WHERE reg.admin_key='$adminId'
                    ) q ORDER BY q.name");

    $localities = array ();
    while ($row = $res->fetch_assoc()) $localities[$row['id']]=$row['name'];
    return $localities;
}

function db_getAdminLocalitiesNotRegTbl ($adminId)
{
    global $db;
    $adminId = $db->real_escape_string($adminId);

    $res=db_query ("SELECT DISTINCT * FROM (
                    SELECT l.key as id, l.name as name
                    FROM access a
                    LEFT JOIN country c ON c.key = a.country_key
                    LEFT JOIN region r ON r.key = a.region_key or c.key=r.country_key
                    INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
                    LEFT JOIN member m ON m.locality_key = l.key
                    WHERE a.member_key='$adminId'                    
                    ) q ORDER BY q.name");

    $localities = array ();
    while ($row = $res->fetch_assoc()) $localities[$row['id']]=$row['name'];
    return $localities;
}

function db_getCountries ($sorted_ones)
{
    $res=db_query ($sorted_ones ? "SELECT `key` as id, name FROM country WHERE COALESCE(`order`,0)>0 ORDER BY `order`" :
                   "SELECT `key` as id, name FROM country WHERE COALESCE(`order`,0)=0 ORDER BY name");

    $countries = array ();
    while ($row = $res->fetch_assoc()) $countries[$row['id']]=$row['name'];
    return $countries;
}

function db_getTextBlock ($name)
{
    global $db;
    $name = $db->real_escape_string($name);

    $res=db_query ("SELECT value FROM textblock WHERE name='$name'");

    if ($row = $res->fetch_assoc())
        return $row['value'];
    else
        return '';
}

function db_getCustomPage ($name)
{
    global $db;
    $name = $db->real_escape_string($name);

    $res=db_query ("SELECT value FROM custom_page WHERE name='$name'");

    if ($row = $res->fetch_assoc())
        return $row['value'];
    else
        return '';
}

function db_isSingleCityAdmin ($adminId)
{
    global $db;
    $adminId = $db->real_escape_string($adminId);

    $res=db_query ("SELECT IF ((SELECT COUNT(DISTINCT c.key) FROM access as a LEFT JOIN country c ON c.key = a.country_key WHERE a.member_key='$adminId')=0
                           AND (SELECT COUNT(DISTINCT r.key) FROM access as a LEFT JOIN region r ON r.key = a.region_key WHERE a.member_key='$adminId')=0
                           AND (SELECT COUNT(DISTINCT l.key) FROM access as a LEFT JOIN locality l ON l.key = a.locality_key WHERE a.member_key='$adminId')=1,1,0) as single");

    if ($row = $res->fetch_assoc())
        return $row['single']=='1';
    else
        return false;
}

function db_getAdminAccess ($adminId)
{
    global $db;
    $adminId = $db->real_escape_string($adminId);

    $res=db_query ("SELECT DISTINCT c.name as name FROM access as a LEFT JOIN country c ON c.key = a.country_key WHERE a.member_key='$adminId' AND c.name IS NOT NULL
                    UNION SELECT DISTINCT r.name as name FROM access as a LEFT JOIN region r ON r.key = a.region_key WHERE a.member_key='$adminId' AND r.name IS NOT NULL
                    UNION SELECT DISTINCT l.name as name FROM access as a LEFT JOIN locality l ON l.key = a.locality_key WHERE a.member_key='$adminId' AND l.name IS NOT NULL");

    $access = array ();
    while ($row = $res->fetch_assoc()) $access[]=$row['name'];
    return $access;
}

function db_getAdminAccessAreas ($adminId)
{
    global $db;
    $adminId = $db->real_escape_string($adminId);

    $res=db_query ("SELECT DISTINCT c.key as access_key, c.name as name FROM access as a LEFT JOIN country c ON c.key = a.country_key
                        WHERE a.member_key='$adminId' AND c.name IS NOT NULL
                    UNION SELECT DISTINCT r.key as access_key, r.name as name FROM access as a LEFT JOIN region r ON r.key = a.region_key
                        WHERE a.member_key='$adminId' AND r.name IS NOT NULL
                    UNION SELECT DISTINCT l.key as access_key, l.name as name FROM access as a LEFT JOIN locality l ON l.key = a.locality_key
                        WHERE a.member_key='$adminId' AND l.name IS NOT NULL");

    $access = array ();
    while ($row = $res->fetch_assoc()) $access[$row['access_key']]=$row['name'];
    return $access;
}

function db_getAdminMembers ($adminId)
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

function db_getAdminActiveMembers ($adminId, $locId, $catId, $text, $eventId){
    global $db;
    $adminId = $adminId ? $db->real_escape_string($adminId) : null;
    $locId = $locId ? " AND m.locality_key='".$db->real_escape_string($locId)."' " : '';
    $catId = $catId ? " AND m.category_key='".$db->real_escape_string($catId)."' " : '';
    $text = $db->real_escape_string($text);
    $_text = $text ? " AND ( m.name LIKE '%".$text."%' OR l.name LIKE '%".$text."%' OR m.new_locality LIKE '%$text%')" : '';
    $eventId = $db->real_escape_string($eventId);
    $res = null;

    if($adminId){
        $res=db_query ("SELECT DISTINCT * FROM (
                        SELECT m.key as id, m.name as name, l.name as locality, l.key as locId, m.category_key as catId
                        FROM access a
                        LEFT JOIN country c ON c.key = a.country_key
                        LEFT JOIN region r ON r.key = a.region_key or c.key=r.country_key
                        INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
                        INNER JOIN member m ON m.locality_key = l.key
                        WHERE a.member_key='$adminId' AND m.active>0 $locId $catId $_text
                        UNION
                        SELECT m.key as id, m.name as name, COALESCE(l.name, m.new_locality) as locality,
                        l.key as locId, m.category_key as catId
                        FROM member m
                        LEFT JOIN locality l ON l.key=m.locality_key
                        WHERE  m.admin_key='$adminId' AND m.active>0 $locId $catId $_text
                        UNION
                        SELECT m.key as id, m.name as name, COALESCE(l.name, m.new_locality) as locality, l.key as locId,
                        m.category_key as catId
                        FROM reg
                        INNER JOIN member m ON m.key=reg.member_key
                        LEFT JOIN locality l ON l.key=m.locality_key
                        WHERE reg.admin_key='$adminId' AND m.active>0 $locId $catId $_text
                        ) q ORDER BY q.name");
    }
    elseif($locId || $catId || strlen ($text)>=3) {
        $res = db_query("SELECT DISTINCT m.key as id, m.name, m.key, l.name as locality, l.key as locId, m.category_key as catId
            FROM member m
            INNER JOIN locality l ON l.key=m.locality_key
            WHERE 1 $_text $locId $catId AND m.active>0 ORDER BY m.name ASC");
    }

    if($res && $res->num_rows > 0){
        $members = array ();
        while ($row = $res->fetch_assoc()){
            $members[$row['id']]=array (
            "name" => $row['name'],
            "locality" => $row['locality'],
            "localityId" => $row['locId'],
            "categoryId" => $row['catId']
            );
        }
        return $members;
    }
    return null;
}

/*
function db_unregisterMembers ($adminId, $eventId, $memberIds)
{
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $eventId = $db->real_escape_string($eventId);

    db_checkSync ();

    $ids='';
    foreach ($memberIds as $memberId) $ids.="'".$db->real_escape_string($memberId)."',";
    $ids = rtrim($ids,',');

    db_query ("DELETE FROM member WHERE `key` IN ({$ids}) AND `key` LIKE '99%' AND (SELECT COUNT(*) FROM reg WHERE member_key=member.key)<2");

    db_query ("DELETE FROM reg
               WHERE (regstate_key IS NULL OR regstate_key='' OR regstate_key='01' OR regstate_key='05')
               AND member_key IN ({$ids})
               AND event_key='$eventId'");

    db_query ("UPDATE reg SET regstate_key='03', admin_key='$adminId'
               WHERE (regstate_key='02' OR regstate_key='03' OR regstate_key='04')
               AND member_key IN ({$ids})
               AND event_key='$eventId'");
}
*/

function db_unregisterMembers ($adminId, $eventId, $memberIds)
{
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $eventId = $db->real_escape_string($eventId);
    //$_reason = $reason ? ", admin_comment='".$db->real_escape_string($reason)."' " : '';

    db_checkSync ();

    $ids='';
    foreach ($memberIds as $memberId) $ids.="'".$db->real_escape_string($memberId)."',";
    $ids = rtrim($ids,',');

    db_query ("DELETE message FROM message INNER JOIN reg ON reg.event_key=message.event_key AND reg.member_key=message.receiver
              WHERE (reg.regstate_key IS NULL OR reg.regstate_key='' OR reg.regstate_key='01' OR reg.regstate_key='05') AND reg.event_key='$eventId' AND reg.member_key IN ({$ids}) ");

    db_query ("DELETE FROM member WHERE `key` IN ({$ids}) AND `key` LIKE '99%' AND (SELECT COUNT(*) FROM reg WHERE member_key=member.key)<2");

    db_query ("DELETE FROM reg
               WHERE (regstate_key IS NULL OR regstate_key='' OR regstate_key='01' OR regstate_key='05')
               AND member_key IN ({$ids})
               AND event_key='$eventId'");

    /*
    db_query ("DELETE FROM reg
               WHERE member_key IN ({$ids})
               AND event_key='$eventId' AND member_key LIKE '99%' AND (SELECT COUNT(*) FROM member WHERE `key`=reg.member_key)<2");
    */

     db_query ("UPDATE reg SET regstate_key='03', admin_key='$adminId'
               WHERE (regstate_key='02' OR regstate_key='03' OR regstate_key='04')
               AND member_key IN ({$ids})
               AND event_key='$eventId'");

    /*
    db_query ("UPDATE reg SET regstate_key='03', admin_key='$adminId' $_reason
               WHERE member_key IN ({$ids}) AND event_key='$eventId'");
    */
}

function db_registerMembers ($adminId, $eventId, $memberIds)
{
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $eventId = $db->real_escape_string($eventId);

    $hasAccessToAllLocalities = db_isAdminRespForReg($adminId, $eventId);

    db_checkSync ();
    $invalid = array ();

    foreach ($memberIds as $memberId){
        $memberId = $db->real_escape_string($memberId);
        $res=db_query ("SELECT regstate_key FROM reg WHERE member_key='$memberId' AND event_key='$eventId'");
        $row=$res->fetch_array();

        if (!$row['regstate_key'] || $row['regstate_key'] =='05' || $row['regstate_key'] =='03'){
            $rs=db_query ("SELECT m.name FROM reg r
                       INNER JOIN member m ON r.member_key=m.key
                       INNER JOIN event e ON r.event_key=e.key
                       WHERE r.member_key='$memberId' AND r.event_key='$eventId'
                       AND (

                       m.name=''
                       OR NULLIF (m.citizenship_key,'') IS NULL
                       OR (NULLIF (m.locality_key,'') IS NULL AND NULLIF (m.new_locality,'') IS NULL)
                       OR NULLIF (m.category_key,'') IS NULL

                       OR ( e.need_parking > 0 AND (r.parking IS NULL))

                       OR (e.need_passport>0

                            AND (NULLIF (m.document_key,'') IS NULL
                                OR NULLIF (m.document_num,'') IS NULL
                                OR m.document_date IS NULL OR YEAR(m.document_date)<1900
                                OR NULLIF (m.document_auth,'') IS NULL )
                       )
                       OR (r.arr_date IS NULL OR r.dep_date IS NULL)
                       OR (e.need_transport>0 AND (r.transport IS NULL OR r.transport = 0))
                       OR (e.need_tp>0  AND (NULLIF (m.tp_num,'') IS NULL OR NULLIF (m.tp_auth,'') IS NULL
                            OR m.tp_date IS NULL OR YEAR(m.tp_date)<1900 OR NULLIF (m.tp_name,'') IS NULL)
                            )

                       OR (e.need_flight>0 AND (NULLIF (r.visa,'') IS NULL OR NULLIF (m.english,'') IS NULL))

                       )
                       ");

            if ($rs->num_rows) {
                $rc = $rs->fetch_array();
                $invalid[]=$rc[0];
            }
            else {
                db_query ("UPDATE reg SET regstate_key='".($row[0]=='03' ? "02" : "01")."', admin_key='$adminId' WHERE member_key='$memberId' AND event_key='$eventId'");
            }
        }
    }
    return $invalid;
}

function db_registerNewMembers ($adminId, $eventId, $memberIds)
{
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $eventId = $db->real_escape_string($eventId);

    db_checkSync ();

    foreach ($memberIds as $memberId)
    {
        $memberId = $db->real_escape_string($memberId);
        $res=db_query ("SELECT m.name as member_name, e.name as event_name FROM reg r INNER JOIN member m ON m.key =r.member_key INNER JOIN event e ON e.key=r.event_key WHERE r.member_key='$memberId' AND r.event_key='$eventId'");
        $row = $res->fetch_assoc();

        if ($res->num_rows == 0){
            $res=db_query ("SELECT * FROM event WHERE `key`='$eventId'");
            $event=$res->fetch_assoc();
            $currency = $event['currency'];
            $contrib = $event['contrib'];
            $web = $event['web'];
            db_query ("INSERT INTO reg (member_key, event_key, admin_key, permalink, currency, contrib, created, web) VALUES ('$memberId', '$eventId', '$adminId', UUID(), '$currency', '$contrib', NOW(), $web)");
        }
        else
            //throw new Exception ("Участник ".$row['member_name']." уже добавлен в список".$row['event_name']."");
            throw new Exception ("Участник ".$row['member_name']." уже добавлен в список");
    }
}

function db_getTeamAdmins(){
    $res=db_query ("SELECT value FROM param WHERE name='support_email' ");
    $row = $res->fetch_assoc();
    return $row ? $row['value'] : null;
}


function db_enqueueLetter ($memberId, $eventId, $subject, $body, $to, $from_email, $from_name)
{
    global $db;
    $memberId=$db->real_escape_string($memberId);
    $eventId=$db->real_escape_string($eventId);
    $_from_email = $from_email;
    $_from_name = $from_name;
    $_to = $to;
    $_subject=$subject;
    $_body=$body;

    $stmt = $db->prepare ("INSERT INTO send_queue (member_key, event_key, subject, body, to_email, from_name, from_email) VALUES ('$memberId','$eventId',?,?,?,?,?)");
    if (!$stmt) throw new Exception ($db->error);

    $stmt->bind_param ("sssss", $_subject, $_body, $_to, $_from_name, $_from_email);
    if (!$stmt->execute ()) throw new Exception ($db->error);

    $stmt->close ();
    db_query("UPDATE reg SET send_result='queue' WHERE member_key='$memberId' AND event_key='$eventId'");
}
/*
function db_enqueueLetter ($memberId, $eventId, $subject, $body, $headers, $from)
{
    global $db;
    $memberId=$db->real_escape_string($memberId);
    $eventId=$db->real_escape_string($eventId);
    $_from = $from;
    $_subject=$subject;
    $_body=$body;
    $_headers=$headers;

    $stmt = $db->prepare ("INSERT INTO send_queue (member_key, event_key, subject, body, headers, from_email) VALUES ('$memberId','$eventId',?,?,?,?)");
    if (!$stmt) throw new Exception ($db->error);

    $stmt->bind_param ("ssss", $_subject, $_body, $_headers, $_from);
    if (!$stmt->execute ()) throw new Exception ($db->error);

    $stmt->close ();
    db_query("UPDATE reg SET send_result='queue' WHERE member_key='$memberId' AND event_key='$eventId'");
}
*/

function db_pickLetters ()
{
    $res=db_query ("SELECT s.id, m.email, s.subject, s.body, s.to_email, s.from_email, s.from_name, s.event_key
                    FROM send_queue s
                    INNER JOIN member m ON m.key=s.member_key");

    $letters = array();
    while($row = $res->fetch_object()){
        $letters[] = $row;
    }

    if(count($letters) > 0){
        return $letters;
    }
    return null;
}

function db_pickLetter ()
{
    $res=db_query ("SELECT s.id, m.email, s.subject, s.body, s.headers, s.from_email, s.event_key
                    FROM send_queue s
                    INNER JOIN member m ON m.key=s.member_key
                    LIMIT 0,1");

    if ($row = $res->fetch_object())
        return $row;
    else
        return null;
}

function db_dequeueLetter ($id, $result)
{
    global $db;
    $id = (int)$id;
    $result = $db->real_escape_string($result);

    db_multiQuery ("UPDATE reg SET send_result='$result'
                    WHERE member_key=(SELECT member_key FROM send_queue WHERE id=$id)
                    AND event_key=(SELECT event_key FROM send_queue WHERE id=$id);
                    DELETE FROM send_queue WHERE id = $id");
}

/*
function db_dequeueLetter ($id, $result)
{
    global $db;
    $id = (int)$id;
    $result = $db->real_escape_string($result);

    db_multiQuery ("UPDATE reg SET send_result='$result'
                    WHERE member_key=(SELECT member_key FROM send_queue WHERE id=$id)
                    AND event_key=(SELECT event_key FROM send_queue WHERE id=$id);
                    DELETE FROM send_queue WHERE id = $id");
}
*/

function db_getDashboardMembers ($adminId, $eventId, $sortField='name', $sortType='asc', $searchText, $regstate, $locality)
{
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $searchText = $db->real_escape_string($searchText);
    $sortField = str_replace(' ', '', $sortField);
    $sortType = str_replace(' ', '', $sortType);
    $sortAdd = $sortField!='name' ? ', name' : '';
    $searchText = !$searchText ? '' : ' AND (m.name LIKE "%'.$searchText.'%" OR  l.name LIKE "%'.$searchText.'%")';

    $regstate = $db->real_escape_string($regstate);
    $regstateArr = [];

    if($regstate){
        switch($regstate){
            case '01':
                $regstateArr [] = null;
                break;
            case '02':
                $regstateArr = ['01', '02'];
                break;
            default :
                $regstateArr [] = $regstate;
                break;
        }
    }
    $_regstate = $regstate && $regstate != '_all_' ? $regstate=='01' ? " AND ( reg.regstate_key IS NULL ) " : " AND ( reg.regstate_key IN (".implode(',', $regstateArr).") ) " : "";
    $_locality = $locality ? $locality == 'without' ? " AND (m.locality_key IS NULL OR m.locality_key ='') " : " AND m.locality_key ='".$db->real_escape_string($locality)."' " : '';

    $res=db_query ("SELECT DISTINCT * FROM
  (
    SELECT m.key as id, m.name as name, IF (COALESCE(l.name,'')='', m.new_locality, l.name) as locality, m.email as email,
        m.cell_phone as cell_phone, reg.arr_date, reg.arr_time, reg.dep_date, reg.dep_time, reg.regstate_key as regstate,
        (reg.changed>0 or m.changed>0) as changed, m.admin_key as mem_admin_key, e.web,
        reg.admin_key as reg_admin_key, (SELECT name FROM member m2 WHERE m2.key=m.admin_key) as mem_admin_name, m.male,
        (SELECT name FROM member m3 WHERE m3.key=reg.admin_key) as reg_admin_name, reg.send_result, stts.name as status, srv.name as service,
        IF (reg.attended, IFNULL(reg.place,''), null) as place, reg.accom, reg.transport, reg.parking, reg.mate_key,
        e.need_passport, e.need_tp, reg.admin_comment, reg.comment, e.list_name, reg.list_name as reg_list_name,
        IF((SELECT COUNT(*) FROM reg rg WHERE rg.event_key=e.key AND (rg.regstate_key = '01' OR rg.regstate_key = '02' OR rg.regstate_key = '04' OR rg.regstate_key is NULL )) >= e.participants_count AND e.participants_count > 0, 1, 0) as stop_registration,
        e.close_registration,
        reg.prepaid, reg.attended, reg.aid_paid, reg.paid, reg.service_key, reg.status_key, reg.coord, reg.contr_amount, reg.currency,
        reg.visa, reg.note, reg.flight_num_arr, reg.flight_num_dep, m.english, m.birth_date,
        m.tp_name, m.tp_num, m.tp_auth, m.tp_date, m.address, reg.avtomobile, reg.avtomobile_number,
        (SELECT rg.name FROM region rg WHERE rg.key=l.region_key) as region,
        (SELECT co.name FROM country co INNER JOIN region re ON co.key=re.country_key WHERE l.region_key=re.key) as country,
        d.name as document_name, m.document_num, m.document_auth as document_auth, m.document_date as document_date,
        ca.name as category_name
    FROM access as a
    LEFT JOIN country c ON c.key = a.country_key
    LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
    INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
    INNER JOIN member m ON m.locality_key = l.key
    INNER JOIN reg ON reg.member_key = m.key
    LEFT JOIN service srv ON srv.key = reg.service_key
    LEFT JOIN status stts ON stts.key = reg.status_key
    INNER JOIN event e ON e.key = reg.event_key
    LEFT JOIN document d ON d.key = m.document_key
    LEFT JOIN category ca ON m.category_key = ca.key
    WHERE a.member_key='$adminId' AND reg.event_key='$eventId' $searchText $_regstate $_locality
    UNION
    SELECT m.key as id, m.name as name, IF (COALESCE(l.name,'')='', m.new_locality, l.name) as locality, m.email as email,
        m.cell_phone as cell_phone, reg.arr_date, reg.arr_time, reg.dep_date, reg.dep_time, reg.regstate_key as regstate,
        (reg.changed>0 or m.changed>0) as changed, m.admin_key as mem_admin, e.web,
        reg.admin_key as reg_admin, (SELECT name FROM member m2 WHERE m2.key=m.admin_key) as mem_admin_name, m.male,
        (SELECT name FROM member m3 WHERE m3.key=reg.admin_key) as reg_admin_name, reg.send_result, stts.name as status, srv.name as service,
        IF (reg.attended, IFNULL(reg.place,''), null) as place, reg.accom, reg.transport, reg.parking, reg.mate_key,
        e.need_passport, e.need_tp, reg.admin_comment, reg.comment, e.list_name, reg.list_name as reg_list_name,
        IF((SELECT COUNT(*) FROM reg rg WHERE rg.event_key=e.key AND (rg.regstate_key = '01' OR rg.regstate_key = '02' OR rg.regstate_key = '04' OR rg.regstate_key is NULL )) >= e.participants_count AND e.participants_count > 0, 1, 0) as stop_registration,
        e.close_registration,
        reg.prepaid, reg.attended, reg.aid_paid, reg.paid, reg.service_key, reg.status_key, reg.coord, reg.contr_amount, reg.currency,
        reg.visa, reg.note, reg.flight_num_arr, reg.flight_num_dep, m.english, m.birth_date,
        m.tp_name, m.tp_num, m.tp_auth, m.tp_date, m.address, reg.avtomobile, reg.avtomobile_number,
        (SELECT rg.name FROM region rg WHERE rg.key=l.region_key) as region,
        (SELECT co.name FROM country co INNER JOIN region re ON co.key=re.country_key WHERE l.region_key=re.key) as country,
        d.name as document_name, m.document_num, m.document_auth as document_auth, m.document_date as document_date,
        ca.name as category_name
    FROM reg
    INNER JOIN member m ON m.key = reg.member_key
    LEFT JOIN locality l ON l.key = m.locality_key
    LEFT JOIN service srv ON srv.key = reg.service_key
    LEFT JOIN status stts ON stts.key = reg.status_key
    INNER JOIN event e ON e.key = reg.event_key
    LEFT JOIN document d ON d.key = m.document_key
    LEFT JOIN category ca ON m.category_key = ca.key
    WHERE (reg.admin_key = '$adminId' OR m.admin_key='$adminId') AND reg.event_key='$eventId' $searchText $_regstate $_locality
    ) q ORDER BY q."."{$sortField} {$sortType} {$sortAdd}");

    $members = array ();
    while ($row = $res->fetch_object()) $members[]=$row;
    return $members;
}


// check using to remove
function db_getDashboardMembersService ($eventId, $attended, $regstate, $sortField='name', $sortType='asc', $searchText, $coord, $service, $locality){
    global $db;
    $eventId = (int)$eventId;
    $searchText = $db->real_escape_string($searchText);
    $sortField = str_replace(' ', '', $sortField);
    $sortType = str_replace(' ', '', $sortType);
    $sortAdd = $sortField!='name' ? ', name' : '';
    $searchText = !$searchText ? '' : ' AND (m.name LIKE "%'.$searchText.'%")';
    $regstate = $db->real_escape_string($regstate);
    $regstateArr = [];

    if($regstate){
        switch($regstate){
            case '01':
                $regstateArr [] = null;
                break;
            case '02':
                $regstateArr = ['01', '02'];
                break;
            default :
                $regstateArr [] = $regstate;
                break;
        }
    }

    $_attended = $attended ? " AND reg.attended".($db->real_escape_string($attended) == 1 ? "=1 " : "<>1 ")."" : "";
    $_regstate = $regstate && $regstate != '_all_' ? $regstate=='01' ? " AND ( reg.regstate_key IS NULL ) " : " AND ( reg.regstate_key IN (".implode(',', $regstateArr).") ) " : "";

    $_coord = $coord ? " AND reg.coord='1' " : '';
    $_service = $service ? " AND reg.service_key IS NOT NULL AND reg.service_key <> '' " : '';
    $_locality = $locality ? $locality == 'without' ? " AND (m.locality_key IS NULL OR m.locality_key ='') " : " AND m.locality_key ='".$db->real_escape_string($locality)."' " : '';

    $res=db_query ("SELECT DISTINCT * FROM (
        SELECT m.key as id, m.name as name, IF (COALESCE(l.name,'')='', m.new_locality, l.name) as locality,
        m.email as email, m.cell_phone as cell_phone, m.birth_date,
        (SELECT name FROM member m2 WHERE m2.key=m.admin_key) as mem_admin_name,
        m.admin_key as mem_admin_key, m.male, m.document_num as document_num,
        m.document_auth as document_auth, m.document_date as document_date, stts.name as status,
        s.name as service, reg.attended, reg.prepaid, reg.aid_paid, reg.paid,
        (SELECT name FROM member m3 WHERE m3.key=reg.admin_key) as reg_admin_name,
        IF (reg.attended, IFNULL(reg.place,''), null) as place, reg.accom,
        reg.transport, reg.parking, reg.service_key, reg.status_key, reg.admin_key, reg.arr_date,
        reg.arr_time, reg.dep_date, reg.dep_time, reg.regstate_key as regstate,
        (reg.changed>0 or m.changed>0) as changed, reg.contr_amount, reg.currency, e.list_name, reg.list_name as reg_list_name,
        reg.coord, reg.send_result, reg.admin_key as reg_admin_key, reg.admin_comment, reg.comment,
        d.name as document_name, e.web, r.name as region, c.name as country,
        reg.visa, reg.note, reg.flight_num_arr, reg.flight_num_dep, m.english,
        IF((SELECT COUNT(*) FROM reg rg WHERE rg.event_key=e.key AND (rg.regstate_key = '01' OR rg.regstate_key = '02' OR rg.regstate_key = '04' OR rg.regstate_key is NULL )) >= e.participants_count AND e.participants_count > 0, 1, 0) as stop_registration,
        e.close_registration,
        m.tp_name, m.tp_num, m.tp_auth, m.tp_date, reg.mate_key, m.address, reg.avtomobile, reg.avtomobile_number,
        ca.name as category_name
        FROM member as m
        INNER JOIN locality l ON m.locality_key = l.key OR m.new_locality =l.name
        INNER JOIN region r ON r.key = l.region_key
        INNER JOIN country c ON c.key=r.country_key
        INNER JOIN reg ON reg.member_key = m.key
        INNER JOIN event e ON e.key=reg.event_key
        LEFT JOIN service s ON s.key = reg.service_key
        LEFT JOIN status stts ON stts.key = reg.status_key
        LEFT JOIN document d ON d.key = m.document_key
        LEFT JOIN category ca ON m.category_key = ca.key
        WHERE reg.event_key=$eventId $searchText $_attended $_regstate $_service $_coord $_locality
        UNION
        SELECT m.key as id, m.name as name, '' as locality,
        m.email as email, m.cell_phone as cell_phone, m.birth_date,
        (SELECT name FROM member m2 WHERE m2.key=m.admin_key) as mem_admin_name,
        m.admin_key as mem_admin_key, m.male, m.document_num as document_num,
        m.document_auth as document_auth, m.document_date as document_date, stts.name as status,
        s.name as service, reg.attended, reg.prepaid, reg.aid_paid, reg.paid,
        (SELECT name FROM member m3 WHERE m3.key=reg.admin_key) as reg_admin_name,
        IF (reg.attended, IFNULL(reg.place,''), null) as place, reg.accom,
        reg.transport, reg.parking, reg.service_key, reg.status_key, reg.admin_key, reg.arr_date,
        reg.arr_time, reg.dep_date, reg.dep_time, reg.regstate_key as regstate,
        (reg.changed>0 or m.changed>0) as changed, reg.contr_amount, reg.currency, e.list_name, reg.list_name as reg_list_name,
        reg.coord, reg.send_result, reg.admin_key as reg_admin_key, reg.admin_comment, reg.comment,
        d.name as document_name, e.web, '' as region, '' as country,
        reg.visa, reg.note, reg.flight_num_arr, reg.flight_num_dep, m.english,
        IF((SELECT COUNT(*) FROM reg rg WHERE rg.event_key=e.key AND (rg.regstate_key = '01' OR rg.regstate_key = '02' OR rg.regstate_key = '04' OR rg.regstate_key is NULL )) >= e.participants_count AND e.participants_count > 0, 1, 0) as stop_registration,
        e.close_registration,
        m.tp_name, m.tp_num, m.tp_auth, m.tp_date, reg.mate_key, m.address, reg.avtomobile, reg.avtomobile_number,
        ca.name as category_name
        FROM member as m
        INNER JOIN reg ON reg.member_key = m.key
        INNER JOIN event e ON e.key=reg.event_key
        LEFT JOIN service s ON s.key = reg.service_key
        LEFT JOIN status stts ON stts.key = reg.status_key
        LEFT JOIN document d ON d.key = m.document_key
        LEFT JOIN category ca ON m.category_key = ca.key
        WHERE ( m.locality_key IS NULL OR m.locality_key ='' ) AND reg.event_key=$eventId $searchText $_attended $_regstate $_service $_coord $_locality)
        q ORDER BY q."."{$sortField} {$sortType} {$sortAdd}");

    $members = array ();
    while ($row = $res->fetch_object()) $members[]=$row;

    return $members;
}

function db_getMemberListCopy ($adminId, $sortField, $sortType)
{
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $sortField = str_replace(' ', '', $sortField);
    $sortType = str_replace(' ', '', $sortType);
    $sortAdd = $sortField!=' name ' ? ' , name' : ' ';
    $active = 'active DESC, ';

    $res=db_query ("SELECT DISTINCT * FROM (SELECT m.key as id, m.name as name, IF (COALESCE(l.name,'')='', m.new_locality, l.name) as locality, m.male,
                    m.email as email, m.cell_phone as cell_phone, m.changed>0 as changed, m.admin_key as admin_key,
                    (SELECT name FROM member m2 WHERE m2.key=m.admin_key) as admin_name, m.active, m.locality_key,
                    DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365 as age, m.birth_date,
                    m.school_comment, m.college_comment, m.college_start, m.college_end, m.school_start, m.school_end,
                    m.comment, co.name as college_name, m.category_key, m.attend_meeting,
                    CASE WHEN m.category_key='SC' OR m.category_key='PS' THEN 1 ELSE 0 END as school,
                    CASE WHEN m.school_start>0 THEN YEAR(NOW()) - m.school_start + 1 ELSE 0 END as school_level,
                    CASE WHEN m.college_start>0 THEN YEAR(NOW()) - m.college_start + 1 ELSE 0 END as college_level,
                    ca.name as category_name,
                    (SELECT rg.name FROM region rg WHERE rg.key=l.region_key) as region,
                    (SELECT co.name FROM country co INNER JOIN region re ON co.key=re.country_key WHERE l.region_key=re.key) as country
                    FROM access as a
                    LEFT JOIN country c ON c.key = a.country_key
                    LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
                    INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
                    INNER JOIN member m ON m.locality_key = l.key
                    LEFT JOIN college co ON co.key = m.college_key
                    LEFT JOIN category ca ON ca.key = m.category_key
                    WHERE a.member_key='$adminId'
                    UNION
                    SELECT m.key as id, m.name as name, IF (COALESCE(m.locality_key,'')='', m.new_locality, m.name) as locality,
                    m.male, m.email as email, m.cell_phone as cell_phone, m.changed>0 as changed, m.admin_key as admin_key,
                    (SELECT name FROM member m2 WHERE m2.key=m.admin_key) as admin_name, m.active, m.locality_key,
                    DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365 as age, m.birth_date,
                    m.school_comment, m.college_comment, m.college_start, m.college_end, m.school_start, m.school_end,
                    m.comment, co.name as college_name, m.category_key, m.attend_meeting,
                    CASE WHEN m.category_key='SC' OR m.category_key='PS' THEN 1 ELSE 0 END as school,
                    CASE WHEN m.school_start>0 THEN YEAR(NOW()) - m.school_start + 1 ELSE 0 END as school_level,
                    CASE WHEN m.college_start>0 THEN YEAR(NOW()) - m.college_start + 1 ELSE 0 END as college_level,
                    ca.name as category_name,
                    '' as region,
                    '' as country
                    FROM member m
                    LEFT JOIN college co ON co.key = m.college_key
                    LEFT JOIN category ca ON ca.key = m.category_key
                    WHERE m.admin_key='$adminId' and m.locality_key is NULL
                    ) q ORDER BY $active $sortField $sortType $sortAdd ");

    $members = array ();
    while ($row = $res->fetch_object()) $members[]=$row;
    return $members;
}

function db_getEvents()
{
    $res=db_query ("SELECT `key` as id, name, start_date, end_date, regend_date, info, need_passport, need_transport, web,
                    need_prepayment, private FROM event ORDER BY start_date");
    $events = array ();
    while ($row = $res->fetch_object()) $events[]=$row;
    return $events;
}

function db_getEventsByAdmin($adminId){
    global $db;
    $admin = $db->real_escape_string($adminId);
    $isMemberAdmin = db_isAdmin($adminId);
    $events = array ();

    if($isMemberAdmin){
        $request= $adminId == "" ? "  AND e.is_active=1 " : "";

        $res=db_query ("SELECT DISTINCT * FROM(
        SELECT e.key as id, e.name, e.start_date, e.end_date, e.regend_date,
        e.info, e.need_passport, e.event_type, e.web, e.need_flight, e.list_name,
        IF((SELECT COUNT(*) FROM reg rg WHERE rg.event_key=e.key AND (rg.regstate_key = '01' OR rg.regstate_key = '02' OR rg.regstate_key = '04' OR rg.regstate_key is NULL )) >= e.participants_count AND e.participants_count > 0, 1, 0) as stop_registration,
        e.close_registration, e.need_transport, e.need_prepayment, e.private, e.need_tp,
        (SELECT ea.member_key FROM event_access ea WHERE ea.member_key='$admin' AND ea.key=e.key) as admin_access
        FROM event e
        LEFT JOIN event_zones z ON z.event_key=e.key
        LEFT JOIN country c ON c.key = z.country_key
        LEFT JOIN region r ON r.key = z.region_key or c.key=r.country_key
        INNER JOIN locality lo ON lo.key = z.locality_key or lo.region_key = r.key
        INNER JOIN access a ON a.country_key=c.key or a.region_key=r.key or a.locality_key = lo.key
        WHERE a.member_key='$adminId' AND e.is_active=1 $request
        UNION
        SELECT e.key as id, e.name, e.start_date, e.end_date, e.regend_date,
        e.info, e.need_passport, e.event_type, e.web, e.need_flight, e.list_name,
        IF((SELECT COUNT(*) FROM reg rg WHERE rg.event_key=e.key AND (rg.regstate_key = '01' OR rg.regstate_key = '02' OR rg.regstate_key = '04' OR rg.regstate_key is NULL )) >= e.participants_count AND e.participants_count > 0, 1, 0) as stop_registration,
        e.close_registration,
        e.need_transport, e.need_prepayment, e.private, e.need_tp,
        (SELECT ea.member_key FROM event_access ea WHERE ea.member_key='$admin' AND ea.key=e.key) as admin_access
        FROM event e
        INNER JOIN access a ON a.member_key='$adminId'
        INNER JOIN country c ON c.key = a.country_key
        INNER JOIN region r ON r.key = a.region_key or c.key=r.country_key
        INNER JOIN locality lo ON lo.key = a.locality_key or lo.region_key = r.key
        INNER JOIN event_zones z ON z.event_key=e.key AND (z.country_key=c.key or z.region_key=r.key or z.locality_key=lo.key)
        WHERE e.is_active=1 $request
        UNION
        SELECT e.key as id, e.name, e.start_date, e.end_date, e.regend_date,
        e.info, e.need_passport, e.event_type, e.web, e.need_flight, e.list_name,
        IF((SELECT COUNT(*) FROM reg rg WHERE rg.event_key=e.key AND (rg.regstate_key = '01' OR rg.regstate_key = '02' OR rg.regstate_key = '04' OR rg.regstate_key is NULL )) >= e.participants_count AND e.participants_count > 0, 1, 0) as stop_registration,
        e.close_registration,
        e.need_transport, e.need_prepayment, e.private, e.need_tp,
        (SELECT ea.member_key FROM event_access ea WHERE ea.member_key='$admin' AND ea.key=e.key) as admin_access
        FROM event e
        WHERE ((SELECT COUNT(*) FROM event_zones ez WHERE ez.event_key=e.key) = 0 OR e.author='$adminId') AND e.is_active=1 $request
        ) q ORDER BY start_date ");

        while ($row = $res->fetch_object()) $events[]=$row;
    }
    else{
        $res = db_query("SELECT e.key as id, e.name, e.start_date, e.end_date, e.regend_date,
        e.info, e.need_passport, e.event_type, e.web, e.need_flight, e.list_name,
        e.need_transport, e.need_prepayment, e.private, e.need_tp,
        IF((SELECT COUNT(*) FROM reg rg WHERE rg.event_key=e.key AND (rg.regstate_key = '01' OR rg.regstate_key = '02' OR rg.regstate_key = '04' OR rg.regstate_key is NULL )) >= e.participants_count AND e.participants_count > 0, 1, 0) as stop_registration,
        e.close_registration,
        $admin as admin_access
        FROM event e
        INNER JOIN event_access ea ON e.key = ea.key WHERE ea.member_key = '$admin' ");

        while ($row = $res->fetch_object()) $events[]=$row;
    }
    return $events;
}

function db_getEventsAvailable($memberId)
{
    $res=db_query ("SELECT `key` as id FROM event_access WHERE member_key='$memberId'");
    $events = array ();
    while ($row = $res->fetch_object()) $events[]=$row;
    return $events;
}

function db_getNewMemberKey ()
{
    $res=db_query ("SELECT `key` as id FROM member WHERE `key` LIKE '99%' ORDER BY `key` DESC LIMIT 1");
    $row = $res->fetch_object();
    $key = "990000000";
    if ($row && strlen($row->id)==9) $key = (string)($row->id + 1);
    return $key;
}

function db_getNeedPassport ($eventId)
{
    global $db;
    $eventId = $db->real_escape_string($eventId);
    $res=db_query ("SELECT need_passport FROM event WHERE `key`= '$eventId'");
    $row = $res->fetch_object();
    return $row && $row->need_passport;
}

function db_getNeedPassportTp ($eventId)
{
    global $db;
    $eventId = $db->real_escape_string($eventId);
    $res=db_query ("SELECT need_tp FROM event WHERE `key`= '$eventId'");
    $row = $res->fetch_object();
    return $row && $row->need_tp;
}

function db_getNeedFlight ($eventId)
{
    global $db;
    $eventId = $db->real_escape_string($eventId);
    $res=db_query ("SELECT need_flight FROM event WHERE `key`= '$eventId'");
    $row = $res->fetch_object();
    return $row && $row->need_flight;
}

function db_getNeedParking($eventId){
    global $db;
    $eventId = $db->real_escape_string($eventId);
    $res=db_query ("SELECT need_parking FROM event WHERE `key`= '$eventId'");
    $row = $res->fetch_object();
    return $row && $row->need_parking > 0;
}

function db_getNeedTransport($eventId){
    global $db;
    $eventId = $db->real_escape_string($eventId);
    $res=db_query ("SELECT need_transport FROM event WHERE `key`= '$eventId'");
    $row = $res->fetch_object();
    return $row && $row->need_transport;
}

function db_setEventMember ($adminId, $get, $post){
    global $db;
    $_page = $db->real_escape_string($post['page']);

    $isUserAuth = $db->real_escape_string($adminId) !== '';
    $_adminId = $_page != '/index' ? $db->real_escape_string($adminId) : (isset($post['member']) && strlen($post ['member']) ? $db->real_escape_string($post['member']) : null);
    $_memberId = $_page == '/index' || $_page == '/main' ? (isset($post['member']) && strlen($post ['member']) ? $db->real_escape_string($post['member']) : null) : ($_page == '/members' ? isset($get ['create']) ? "dont_register" : $db->real_escape_string($get ['update_member'] ) : $db->real_escape_string($get['member']));
    $_eventId = $_page == '/index' || $_page == '/main' ? $db->real_escape_string($post['event']) : ($_page == '/members' ? null : $db->real_escape_string($get['event']));
    $doRegister = $_page == '/index' || $_page == '/main' ? true : ( $_page == '/reg' || $_page == '/admin' ? isset ($get ['register']) : false );
    $_name = preg_replace("/#/", " ", $db->real_escape_string($post['name']));
    $_address = isset($post['address']) && strlen($post ['address']) ? $db->real_escape_string($post['address']) : '';
    $_arr_date = $_page == '/members' ? (DONT_CHANGE) : (isset($post['arr_date']) ? $db->real_escape_string($post['arr_date']) : null);
    $_arr_time = $_page == '/members' ? (DONT_CHANGE) : (isset($post['arr_time']) ? $db->real_escape_string($post['arr_time']) : null);
    $_birth_date = isset($post['birth_date']) ? $db->real_escape_string($post['birth_date']) : null;
    $_cell_phone = $db->real_escape_string($post['cell_phone']);
    $_comment = ($_page != '/reg' || $_page != '/admin') && isset($post['comment']) ? ($db->real_escape_string($post['comment']) ): ($post['comment'] ? $db->real_escape_string($post['comment']) : "");
    $_dep_date = $_page == '/members' ? (DONT_CHANGE) : (isset($post['dep_date']) ? $db->real_escape_string($post['dep_date']) : null);
    $_dep_time = $_page == '/members' ? (DONT_CHANGE) : (isset($post['dep_time']) ? $db->real_escape_string($post['dep_time']) : null);
    $_email = $db->real_escape_string($post['email']);
    $_locality_key = isset($post['locality_key']) && strlen($post ['locality_key']) ? $db->real_escape_string($post['locality_key']) : null;
    $_new_locality = isset($post['new_locality']) && strlen($post ['new_locality']) ? $db->real_escape_string($post['new_locality']) : null;
    $_document_num = isset($post['document_num']) && strlen($post ['document_num']) ? $db->real_escape_string($post['document_num']) : null;
    $_document_date = isset($post['document_date']) && strlen($post ['document_date']) ? $db->real_escape_string($post['document_date']) : null;
    $_document_auth = isset($post['document_auth']) && strlen($post ['document_auth']) ? $db->real_escape_string($post['document_auth']) : null;
    $_category_key = $_page =='/index' || $_page == '/main' ? (DONT_CHANGE) : (isset($post['category_key']) && strlen($post ['category_key']) ? $db->real_escape_string($post['category_key']) : null);
    $_document_key = isset($post['document_key']) && strlen($post ['document_key']) ? $db->real_escape_string($post['document_key']) : null;
    $_tp_num = isset($post['tp_num']) && strlen($post ['tp_num']) ? $db->real_escape_string($post['tp_num']) : null;
    $_tp_date = isset($post['tp_date']) && strlen($post ['tp_date']) ? $db->real_escape_string($post['tp_date']) : null;
    $_tp_auth = isset($post['tp_auth']) && strlen($post ['tp_auth']) ? $db->real_escape_string($post['tp_auth']) : null;
    $_tp_name = isset($post['tp_name']) && strlen($post ['tp_name']) ? $db->real_escape_string($post['tp_name']) : null;
    $_english_level = $_page =='/members' ? (DONT_CHANGE) : isset($post['english_level']) ? $db->real_escape_string($post['english_level']) : null;
    $_flight_num_arr = $_page =='/members' ? (DONT_CHANGE) : (isset($post['flight_num_arr']) ? $db->real_escape_string($post['flight_num_arr']) : null);
    $_flight_num_dep = $_page =='/members' ? (DONT_CHANGE) : (isset($post['flight_num_dep']) ? $db->real_escape_string($post['flight_num_dep']) : null);
    $_note = $_page =='/members' ? (DONT_CHANGE) : (isset($post['note']) ? $db->real_escape_string($post['note']) : null);
    $_status_key = $_page == '/members' || $_page == '/index' ? (DONT_CHANGE) : (isset($post['status_key']) ? $db->real_escape_string($post['status_key']) : null);
    $_male = $post["gender"]=="male" ? 1 : ($post["gender"]=="female" ? 0 : null);
    $_mate_key = $_page == '/members' || $_page == '/index' ? (DONT_CHANGE) : (isset($post['mate_key']) ? $db->real_escape_string($post['mate_key']) : '');
    $_accom = $_page =='/members' ? (DONT_CHANGE) : (isset($post['accom']) && $post['accom']!='' ? $db->real_escape_string($post['accom']) : null);
    $_coord = $_page == '/members' || $_page == '/index' ? (DONT_CHANGE) : $db->real_escape_string($post['coord']);
    $_temp_phone = DONT_CHANGE;
    $_transport = $_page =='/members' ? (DONT_CHANGE) : (isset($post['transport']) && $post['transport']!='' ? $db->real_escape_string($post['transport']) : null);
    $_citizenship_key = $db->real_escape_string($post['citizenship_key']);
    $_parking = $_page =='/members' ? (DONT_CHANGE) : (isset($post['parking']) && $post['parking']!='' ? $db->real_escape_string($post['parking']) : null);
    $_avtomobile = $_page =='/members' ? (DONT_CHANGE) : (isset($post['avtomobile']) && $post['avtomobile']!='' ? $db->real_escape_string($post['avtomobile']) : '');
    $_avtomobile_number = $_page =='/members' ? (DONT_CHANGE) : (isset($post['avtomobile_number']) && $post['avtomobile_number']!='' ? $db->real_escape_string($post['avtomobile_number']) : '');
    $_prepaid = $_page == '/members' || $_page == '/index' ? (DONT_CHANGE) : (int)$post['prepaid'];
    $_currency = $_page == '/members' || $_page == '/index' ? (DONT_CHANGE) : (isset($post['currency']) ? $db->real_escape_string($post['currency']) : null);
    $_service_key = $_page == '/members' || $_page == '/index' ? (DONT_CHANGE) : (isset($post['service_key']) ? $db->real_escape_string($post['service_key']) : null);
    $is_guest = $_page == '/index';
    $_aid = $_page == '/members' || $_page == '/index' ? (DONT_CHANGE) : (isset($post['aid']) ? (int)$post['aid'] : 0);
    $_contr_amount = $_page == '/members' || $_page == '/index' ? (DONT_CHANGE) : (isset($post['contr_amount']) ? (int)$post['contr_amount'] : 0);
    $_trans_amount = $_page == '/members' || $_page == '/index' ? (DONT_CHANGE) : (isset($post['trans_amount']) ? (int)$post['trans_amount'] : 0);
    $_fellowship = $_page == '/members' || $_page == '/index' ? (DONT_CHANGE) : (isset($post['fellowship']) ? (int)$post['fellowship'] : 0);
    $_schoolStart = $_page !== '/members' ? (DONT_CHANGE) : (isset($post['schoolStart']) ? (int)$post['schoolStart'] : 0);
    $_schoolEnd = $_page != '/members' ? (DONT_CHANGE) : (isset($post['schoolEnd']) ? (int)$post['schoolEnd'] : 0);
    $_collegeStart = $_page != '/members' ? (DONT_CHANGE) : (isset($post['collegeStart']) ? (int)$post['collegeStart'] : 0);
    $_collegeEnd = $_page !== '/members' ? (DONT_CHANGE) : (isset($post['collegeEnd']) ? (int)$post['collegeEnd'] : 0);
    $_college = $_page != '/members' ? (DONT_CHANGE) : $db->real_escape_string($post['college']);
    $_collegeComment = $_page != '/members' ? (DONT_CHANGE) : $db->real_escape_string($post['collegeComment']);
    $_russian_lg = $db->real_escape_string($post['russian_lg']);
    $_schoolComment = $_page !='/members' ? (DONT_CHANGE) : (isset($post['school_comment']) ? $db->real_escape_string($post['school_comment']) : '');
    $_visa = $_page =='/members' ? (DONT_CHANGE ): $db->real_escape_string($post['visa']);
    $_baptized = $_page !='/members' ? (DONT_CHANGE) : (isset($post['baptized']) ? $post['baptized'] : null);
    $_termsUse = $_page === '/index' ? $post['termsUse'] : DONT_CHANGE;
    $isInvitation = isset($post['isInvitation']) && $post['isInvitation'] == true ? !!$post['isInvitation'] : false;
    $regListName = $_page == '/members' ? (DONT_CHANGE) : (isset($post['regListName']) ? $db->real_escape_string($post['regListName']) : null);

    db_checkSync ();

    if ($doRegister){
        $exc = new Exception ('Все поля должны быть заполнены');
        if (!$_birth_date || !$_name || !$_citizenship_key || (!$_locality_key && !$_new_locality)) throw $exc;
        if ($_memberId && !$_category_key)  throw $exc;
        if (!$_adminId && !$_email)  throw $exc;
        if (!$_memberId && (!$_dep_date || !$_arr_date))  throw $exc;
        if ($_eventId && db_getEventNeedAddress($_eventId) && !$_address)  throw $exc;
        if (db_getNeedPassport ($_eventId) && (!$_document_key || !$_document_num || !$_document_date || !$_document_auth))  throw $exc;
        if (db_getNeedPassportTp ($_eventId) && (!$_tp_num || !$_tp_date || !$_tp_auth || !$_tp_name))  throw $exc;
        if (db_getNeedFlight ($_eventId) && $_visa == '0')  throw $exc;
        if (db_getNeedParking ($_eventId) && (int)$_parking == 1 && ($_avtomobile_number == '' || $_avtomobile == ''))  throw $exc;
        $exec = new Exception('Укажите сумму необходимую для оказания финансовой помощи');
        if($_aid > 0 && $_contr_amount == 0 && $_trans_amount == 0) throw $exec;
    }
    if(!$_termsUse){
        throw new Exception('Для пользования сайтом, необходимо дать согласие на обработку персональных данных');
    }

    $regChanged = true;
    $memChanged = true;
    $regCommentField = $is_guest ? 'comment' : 'admin_comment';

    if (!$_memberId || $_memberId == "dont_register"){
        $newMemberId = db_getNewMemberKey();
        if (!$_adminId) $_adminId=$newMemberId;

        $stmt = $db->prepare ("INSERT INTO member (`key`, `name`, male, birth_date, locality_key, category_key, address,
                                cell_phone, email, document_key, document_num, document_date, document_auth,
                                tp_num, tp_date, tp_auth, tp_name, english, school_start, school_end, college_start,
                                college_end, college_key, college_comment, school_comment, russian_lg, baptized, changed,
                                new_locality, citizenship_key, admin_key ".(!$_eventId ? ', comment' : '').")
                                VALUES ('$newMemberId',?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,? ".(!$_eventId ? ',?' : '').")");

        if ($_locality_key===DONT_CHANGE) $_locality_key=null;
        if ($_category_key===DONT_CHANGE) $_category_key='BL';
        if ($_eventId){
            if ($_status_key===DONT_CHANGE) $_status_key='01';
            if ($_prepaid===DONT_CHANGE) $_prepaid='0';
            if ($_currency===DONT_CHANGE) $_currency=null;
            if ($_service_key===DONT_CHANGE) $_service_key=null;
            if ($_mate_key===DONT_CHANGE) $_mate_key=null;

            $_college='';
            $_collegeComment='';
            $_schoolComment='';
        }
    }
    else{
        $isUserAddedToreg = db_checkIfUserAddedToReg($_eventId, $_memberId);

        $m = $_eventId && $isUserAddedToreg ? db_getEventMember($_memberId, $_eventId) : db_getMember($_memberId);
        if (!$m) throw new Exception ("Участник не существует или не зарегистрирован на это мероприятие");

        if ($_locality_key===DONT_CHANGE) $_locality_key=$m["locality_key"];
        if ($_category_key===DONT_CHANGE) $_category_key=$m["category_key"] ? $m["category_key"] : 'BL';

        if ($_eventId){
            if ($_status_key===DONT_CHANGE) $_status_key = isset($m["status_key"]) ? $m["status_key"] : '01';
            if ($_prepaid===DONT_CHANGE) $_prepaid = isset($m["prepaid"]) ? $m["prepaid"] : 0;
            if ($_currency===DONT_CHANGE) $_currency = isset($m["currency"]) ? $m["currency"] : null;
            if ($_service_key===DONT_CHANGE) $_service_key = isset($m["service_key"]) ? $m["service_key"] : null;

            if ($_college ===DONT_CHANGE) $_college= $m["college_key"] ? $m["college_key"] : '';
            if ($_collegeComment===DONT_CHANGE) $_collegeComment= $m["college_comment"] ? $m["college_comment"] : '';
            if ($_schoolComment===DONT_CHANGE) $_schoolComment= $m["school_comment"] ? $m["school_comment"] : '';

            if ($_baptized===DONT_CHANGE) $_baptized= $m["baptized"] ? $m["baptized"] : null;
            if ($_mate_key===DONT_CHANGE) $_mate_key= isset($m["mate_key"]) ? $m["mate_key"] : null;
        }
        else{
            if ($_english_level===DONT_CHANGE) $_english_level = $m["english"];
        }

        $regChanged = $_eventId && $isUserAddedToreg
            && ($doRegister || $_arr_date != $m["arr_date"] || $_arr_time != $m["arr_time"]
            || $_comment != $m[$regCommentField]
            || $_dep_date != $m["dep_date"] || $_dep_time != $m["dep_time"]
            || $_status_key != $m["status_key"]
            || $_mate_key != $m["mate_key"] || $_accom != $m["accom"] || $_parking != $m["parking"]
            || $_transport != $m["transport"]
            // || $_temp_phone != $m["temp_phone"]
            || $_prepaid != $m["prepaid"] || $_currency != $m["currency"]
            || $_service_key != $m["service_key"] || $_coord != $m["coord"]
            || $_flight_num_arr != $m["flight_num_arr"] || $_flight_num_dep != $m["flight_num_dep"]
            || $_note != $m["note"] || $_aid != $m['aid'] || $_contr_amount != $m['contr_amount']
            || $_trans_amount != $m['trans_amount'] || $_fellowship != $m['fellowship'] || $_visa != $m['visa']
            || ($_eventId && $_avtomobile != $m['avtomobile'])
            || ($_eventId && $_avtomobile_number != $m['avtomobile_number'])
            || ($_eventId && $regListName != $m['reg_list_name']))
            || ($_eventId && isset($get['create']));

        $memChanged =
                ($_name !== DONT_CHANGE && $_name != $m["name"]) ||
                ($_address !== DONT_CHANGE && $_address != $m["address"]) ||
                ($_birth_date !== DONT_CHANGE && $_birth_date != $m["birth_date"]) ||
                ($_cell_phone !== DONT_CHANGE && $_cell_phone != $m["cell_phone"]) ||
                ($_email !== DONT_CHANGE && $_email != $m["email"]) ||
                ($_locality_key !== DONT_CHANGE && $_locality_key != $m["locality_key"]) ||
                ($_new_locality !== DONT_CHANGE && $_new_locality != $m["new_locality"]) ||
                ($_document_num !== DONT_CHANGE && $_document_num != $m["document_num"]) ||
                ($_document_date !== DONT_CHANGE && $_document_date != $m["document_date"]) ||
                ($_document_auth !== DONT_CHANGE && $_document_auth != $m["document_auth"]) ||
                ($_category_key !== DONT_CHANGE && $_category_key != $m["category_key"]) ||
                ($_document_key !== DONT_CHANGE && $_document_key != $m["document_key"]) ||
                ($_male !== DONT_CHANGE && $_male != $m["male"]) ||
                ($_citizenship_key !== DONT_CHANGE && $_citizenship_key != $m["citizenship_key"]) ||
                (!$_eventId && $_name !== DONT_CHANGE && $_comment != $m['admin_comment']) ||
                ($_tp_num !== DONT_CHANGE && $_tp_num != $m["tp_num"])   ||
                ($_tp_date !== DONT_CHANGE && $_tp_date != $m["tp_date"]) ||
                ($_tp_auth !== DONT_CHANGE && $_tp_auth != $m["tp_auth"]) ||
                ($_tp_name !== DONT_CHANGE && $_tp_name != $m["tp_name"]) ||
                ($_english_level !== DONT_CHANGE && $_english_level != $m["english"]) ||
                ($_schoolStart !== DONT_CHANGE && $_schoolStart != $m["school_start"]) ||
                ($_schoolEnd !== DONT_CHANGE && $_schoolEnd != $m["school_end"]) ||
                ($_collegeStart !== DONT_CHANGE && $_collegeStart != $m["college_start"]) ||
                ($_collegeEnd !== DONT_CHANGE && $_collegeEnd != $m["college_end"]) ||
                ($_college !== DONT_CHANGE && $_college != $m["college_key"] )||
                ($_collegeComment !== DONT_CHANGE && $_collegeComment != $m['college_comment']) ||
                ($_schoolComment !== DONT_CHANGE && $_schoolComment != $m['school_comment']) ||
                ($_russian_lg !== DONT_CHANGE && $_russian_lg != $m['russian_lg']) ||
                ($_baptized !== DONT_CHANGE && $_baptized != $m['baptized']);

        if ($_eventId && $isUserAddedToreg){
            //$reg = db_getEventMember ($_memberId, $_eventId);
            $reg = $m;
            $regstate = $reg["regstate_key"];
        }

        $stmt = $db->prepare ("UPDATE member SET `name` = ?, male = ?, birth_date = ?, locality_key = ?,
                            category_key = ?, address = ?, cell_phone = ?, email = ?, document_key = ?, document_num = ?,
                            document_date = ?, document_auth = ?, tp_num = ?, tp_date = ?, tp_auth = ?, tp_name = ?,
                            english = ?, school_start=?, school_end=?, college_start=?,
                            college_end=?, college_key=?, college_comment=?, school_comment=?, russian_lg=?,
                            baptized=?, changed = ?, new_locality = ?, citizenship_key = ?,
                            admin_key = ? ".(!$_eventId ? ', comment=?' : '')." WHERE `key`='$_memberId'");
    }

    if ($memChanged){
        if (!$stmt) throw new Exception ($db->error);

        $isMemberChanged = 1;
        if ($_eventId)
            $stmt->bind_param ("ssssssssssssssssssssssssssssss", $_name, $_male, $_birth_date, $_locality_key, $_category_key,
                $_address, $_cell_phone, $_email, $_document_key, $_document_num, $_document_date,
                $_document_auth, $_tp_num, $_tp_date, $_tp_auth, $_tp_name, $_english_level,
                $_schoolStart, $_schoolEnd, $_collegeStart, $_collegeEnd, $_college, $_collegeComment, $_schoolComment,
                $_russian_lg, $_baptized, $isMemberChanged, $_new_locality, $_citizenship_key, $_adminId);
        else
            $stmt->bind_param ("sssssssssssssssssssssssssssssss", $_name, $_male, $_birth_date, $_locality_key, $_category_key,
                $_address, $_cell_phone, $_email, $_document_key, $_document_num, $_document_date,
                $_document_auth, $_tp_num, $_tp_date, $_tp_auth, $_tp_name, $_english_level,
                $_schoolStart, $_schoolEnd, $_collegeStart, $_collegeEnd, $_college, $_collegeComment, $_schoolComment,
                $_russian_lg, $_baptized, $isMemberChanged, $_new_locality, $_citizenship_key, $_adminId, $_comment);

        if (!$stmt->execute ()) throw new Exception ($db->error);
        $stmt->close ();
    }

    if (($regChanged && $_memberId != "dont_register") || $isInvitation || ($doRegister && !$isUserAddedToreg)){
        if(!$_memberId || !$isUserAddedToreg){
            $res=db_query ("SELECT * FROM event WHERE `key`='$_eventId'");
            $event=$res->fetch_assoc();
            $_currency = $event['currency'];
            $_web = $event['web'];
            $_contrib = $event['contrib'];
        }

        $stmt =  !$_memberId || !$isUserAddedToreg ? $db->prepare ("INSERT INTO reg (arr_date, arr_time, dep_date, dep_time, accom,
                                              transport, mate_key, admin_key, status_key, $regCommentField, parking,
                                              prepaid, currency, service_key, coord,
                                              flight_num_arr, flight_num_dep, note,
                                              aid, contr_amount, trans_amount, list_name, fellowship, visa, avtomobile, avtomobile_number,
                                              changed, regstate_key, member_key, event_key, permalink, created, web, contrib)
                                              VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,0,"
                                                . ($doRegister ? "'01'," : "NULL,")
                                                . ( !$_memberId ? "'$newMemberId'" : "'$_memberId'") .", '$_eventId', UUID(), NOW(), $_web, $_contrib)")
                               : $db->prepare ("UPDATE reg SET arr_date=?, arr_time=?, dep_date=?, dep_time=?, accom=?,
                                              transport=?, mate_key=?, admin_key=?, status_key=?,
                                              $regCommentField=?, parking=?, prepaid=?, currency=?, service_key=?, coord=?,
                                              flight_num_arr = ?, flight_num_dep = ?, note = ?,
                                              aid = ?, contr_amount = ?, trans_amount = ?, list_name=?, fellowship=?, visa=?, avtomobile =?, avtomobile_number=? "
                                              .( $_aid < 1 ? ", aid_paid=NULL " : "" )
                                              . ($regstate=='01' || $regstate=='02' || $regstate=='04' ? ", changed=1" : "")
                                              . ((!$regstate || $regstate=='05') && $doRegister ? ", regstate_key='01'" : "")
                                              . " where member_key='$_memberId' and event_key='$_eventId'");

        if (!$stmt) throw new Exception ($db->error);
        $stmt->bind_param ("ssssssssssssssssssssssssss", $_arr_date, $_arr_time, $_dep_date, $_dep_time, $_accom,
            $_transport, $_mate_key, $_adminId, $_status_key, $_comment, $_parking,  $_prepaid, $_currency,
            $_service_key, $_coord, $_flight_num_arr, $_flight_num_dep,
            $_note, $_aid, $_contr_amount, $_trans_amount, $regListName, $_fellowship, $_visa, $_avtomobile, $_avtomobile_number);

        if (!$stmt->execute ()) throw new Exception ($db->error);
        $stmt->close ();
    }

    if($_page == '/index'){
        db_sendMessagesToMembersAdmins($_eventId, $_name, $_locality_key);
    }

    return isset ($newMemberId) ? $newMemberId : $_memberId;
}

function db_setEventMembers ($adminId, $eventId, $memberIds, $arr_date, $arr_time, $dep_date, $dep_time,
                             $accom, $transport, $status, $coord, $service, $mate)
{
    global $db;
    db_checkSync ();

    $ids='';
    foreach ($memberIds as $memberId) $ids.="'".$db->real_escape_string($memberId)."',";
    $ids = rtrim($ids,',');

    $eventId = $db->real_escape_string($eventId);
    $adminId = $db->real_escape_string($adminId);
    $ending = "', changed=1, admin_key='$adminId' WHERE event_key='$eventId' AND member_key IN ($ids)";
    if ($arr_date) db_query ("UPDATE reg SET arr_date='".$db->real_escape_string($arr_date).$ending);
    if ($arr_time) db_query ("UPDATE reg SET arr_time='".$db->real_escape_string($arr_time).$ending);
    if ($dep_date) db_query ("UPDATE reg SET dep_date='".$db->real_escape_string($dep_date).$ending);
    if ($dep_time) db_query ("UPDATE reg SET dep_time='".$db->real_escape_string($dep_time).$ending);
    if (is_numeric($accom)) db_query ("UPDATE reg SET accom='".$db->real_escape_string($accom).$ending);
    if (is_numeric($transport)) db_query ("UPDATE reg SET transport='".$db->real_escape_string($transport).$ending);
    if (is_numeric($status)) db_query ("UPDATE reg SET status_key='".$db->real_escape_string($status).$ending);
    if (is_numeric($coord)) db_query ("UPDATE reg SET coord='".$db->real_escape_string($coord).$ending);
    if (is_numeric($service)) db_query ("UPDATE reg SET service_key='".$db->real_escape_string($service).$ending);
    if (is_numeric($mate)) db_query ("UPDATE reg SET mate_key='".$db->real_escape_string($mate).$ending);
}

function db_setEventMembersService ($adminId, $eventId, $memberIds, $paid, $place, $attended)
{
    global $db;
    db_checkSync ();

    $ids='';
    foreach ($memberIds as $memberId) $ids.="'".$db->real_escape_string($memberId)."',";
    $ids = rtrim($ids,',');

    $eventId = $db->real_escape_string($eventId);
    $paid = $db->real_escape_string($paid);
    $place = $db->real_escape_string($place);
    $attended = $attended ? $db->real_escape_string($attended) : null;

    $ending = ", changed=1 WHERE event_key='$eventId' AND member_key IN ($ids)";
    db_query ("UPDATE reg SET paid='$paid', place='$place', attended='$attended' $ending");
}

function db_setMemberActive ($adminId, $memberId, $active, $reason)
{
    global $db;
    $active = (int)$active;
    $memberId = $db->real_escape_string($memberId);
    $adminId = $db->real_escape_string($adminId);
    $reason = $reason ? $db->real_escape_string($reason) : '';
    db_checkSync ();
    $res=db_query ("SELECT active, comment FROM member WHERE `key`='$memberId'");
    if ($row = $res->fetch_object())
        if ($row->active!=$active)
            db_query ("UPDATE member SET active=$active, changed=1, admin_key='$adminId', comment='".$row->comment.' '.$reason."' WHERE `key`='$memberId'");
}

function db_findLocality ($query)
{
    global $db;
    $query = $db->real_escape_string($query);

    $res=db_query ("SELECT l.key as data, IF (r.name='--',l.name,CONCAT (l.name,', ',r.name)) as value
                    FROM locality as l
                    INNER JOIN region as r ON l.region_key=r.key
                    WHERE IF (r.name='--',l.name,CONCAT (l.name,', ',r.name)) LIKE '$query%'
                    ORDER BY l.name");

    $localities = array ();
    while ($row = $res->fetch_object()) $localities[]=$row;
    return $localities;
}


function db_getMemberList ($adminId, $sortField, $sortType, $selCountry, $selRegion, $selCity)
{
    global $db;
    $adminId = $db->real_escape_string($adminId);

    $sortField = str_replace(' ', '', $sortField);
    $sortType = str_replace(' ', '', $sortType);
    $sortAdd = $sortField!='name' ? ', name' : '';

    $res=db_query ("SELECT * FROM (
        SELECT m.key as id, m.name as name, IF (COALESCE(l.name,'')='', m.new_locality, l.name) as locality, m.email as email, m.cell_phone as cell_phone,
               m.changed>0 as changed, m.admin_key as admin_key, (SELECT name FROM member m2 WHERE m2.key=m.admin_key) as admin_name, m.active
        FROM access as a
        LEFT JOIN country c ON c.key = a.country_key
        LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
        INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
        INNER JOIN member m ON m.locality_key = l.key
        WHERE a.member_key='$adminId'".
        ($selCountry ? "AND l.key='".$db->real_escape_string($selCountry)."'" : "").
        ($selRegion ? "AND m.category_key='".$db->real_escape_string($selRegion)."'" : "").
        ($selCity ? "AND m.category_key='".$db->real_escape_string($selCity)."'" : "").
        ") q ORDER BY q."."{$sortField} {$sortType} {$sortAdd}");

    $members = array ();
    while ($row = $res->fetch_object()) $members[]=$row;
    return $members;
}

function db_getCountriesList ()
{
    $res=db_query ("SELECT `key` as id, name FROM country ORDER BY name");
    $countries = array ();
    while ($row = $res->fetch_assoc()) $countries[$row['id']]=$row['name'];
    return $countries;
}

function db_getRegionsList()
{
    $res=db_query ("SELECT `key`, `name` FROM region WHERE COALESCE(`name`,0)!='--'");
    $regions = array ();
    while ($row = $res->fetch_object()) $regions[]=$row;
    return $regions;
}

function db_getLocalListByRegion()
{
    $res = db_query("SELECT CONCAT_WS (':', c.key, r.key, l.key) as locality_key, l.name FROM region r INNER JOIN locality l ON r.key=l.region_key INNER JOIN country c ON c.key=r.country_key");

    $localities = array ();
    while ($row = $res->fetch_object()) $localities[]=$row;
    return $localities;
}

function db_getMemberListAdmins ($sortField, $sortType)
{
    global $db;
    $sortField = str_replace(' ', '', $sortField);
    $sortType = str_replace(' ', '', $sortType);

    $res=db_query ("SELECT m.key as id, m.name as name, m.email as email, m.cell_phone as cell_phone,
        lo.name as locality_name, ad.comment as note, GROUP_CONCAT(DISTINCT c.key) as countries, GROUP_CONCAT(DISTINCT r.key) as regions, GROUP_CONCAT(DISTINCT l.key) as localities
        FROM access as a
        INNER JOIN admin ad ON ad.member_key=a.member_key
        INNER JOIN member m ON a.member_key = m.key
        INNER JOIN locality lo ON lo.key=m.locality_key

        LEFT JOIN country c ON c.key=a.country_key
        LEFT JOIN region r ON r.country_key=c.key OR r.key=a.region_key
        LEFT JOIN locality l ON l.region_key=r.key OR l.key=a.locality_key

        GROUP BY m.key ORDER BY $sortField $sortType");

    $members = array ();
    while ($row = $res->fetch_object()) $members[]=$row;
    return $members;
}

function db_getAdminsList ()
{
    $res=db_query ("SELECT DISTINCT m.key as id, m.name as name, m.email as email, m.cell_phone as cell_phone, lo.name as locality_name, ad.comment as note
    FROM access as a
    INNER JOIN admin ad ON ad.member_key=a.member_key
    INNER JOIN member m ON a.member_key = m.key
    INNER JOIN locality lo ON lo.key=m.locality_key ORDER BY name");

    $members = array ();
    while ($row = $res->fetch_object()) $members[]=$row;
    return $members;
}

function db_getAdminRole ($adminId){
    global $db;
    $adminId = $db->real_escape_string($adminId);

    $res = db_query("SELECT role FROM admin WHERE member_key='$adminId' ");
    if ($row = $res->fetch_assoc()) return (int)$row['role'];
    return NULL;
}

function db_isEventPrivate($event){
    global $db;
    $event = $db->real_escape_string($event);
    $res = db_query("SELECT private FROM event WHERE `key`=$event ");

    if($res->num_rows > 0){
        $row = $res->fetch_object();
        return (int)$row->private == 1 ? "private" : "unprivate";
    }
    return null;
}

function db_getLocalities(){
    $res = db_query("SELECT `key`, `name` FROM locality ORDER BY name ASC");

    $localities = array ();
    while ($row = $res->fetch_assoc()) $localities[$row['key']]=$row['name'];
    return $localities;

}

function db_checkRegState($memberId, $event){
    $memberId = (int)$memberId;
    $event = (int)$event;

    $res = db_query("SELECT regstate_key FROM reg WHERE member_key=$memberId AND event_key=$event ");
    if($res->num_rows>0){
        $rowKey = $res->fetch_object();
        $regKey = $rowKey->regstate_key;
        return $regKey;
    }
    else{
        return null;
    }
}

function db_checkName($name){
    global $db;
    $name = $db->real_escape_string($name);

    $res=db_query ("SELECT m.key as member_key, m.name, m.birth_date, l.name as locality_name
                      FROM member as m
                      LEFT JOIN locality l ON m.locality_key=l.key
                      WHERE m.name LIKE '$name%'");

    if($res->num_rows>0){
        $members = array();
        while ($row = $res->fetch_object()) $members[]=$row;
        return $members;
    }
    else{
        return null;
    }
}

function db_getEventMemberCheck ($memberId, $event)
{
    global $db;
    $memberId = $db->real_escape_string($memberId);
    $event = $db->real_escape_string($event);

    $res=db_query ("SELECT m.key as member_key, m.name,
                    CASE WHEN m.male=1 THEN 'male' WHEN m.male=0 THEN 'female' ELSE '' END as gender,
                    m.male, m.birth_date, m.locality_key, m.address, m.cell_phone,
                    m.email, event.need_transport as need_transport, event.need_address,
                    m.category_key, m.document_key, m.document_num, m.document_date,
                    m.document_auth, m.new_locality,
                    m.citizenship_key, m.admin_key as mem_admin, l.name as locality_name,
                    event.need_passport as need_passport,
                    m.english, event.need_tp, event.contrib, event.currency,
                    event.need_flight, m.tp_num, m.tp_date, m.tp_auth, m.tp_name, c.key as country_key,
                    event.need_accom, event.need_service, event.need_parking
                    FROM event, member as m
                    INNER JOIN locality l ON m.locality_key=l.key
                    LEFT JOIN region r ON l.region_key=r.key
                    LEFT JOIN country c ON c.key=r.country_key
                    WHERE m.key=$memberId AND event.key='$event'");

    if ($row = $res->fetch_assoc()) return $row;
    return NULL;
}

function db_getAidStatistic ($eventId, $scope, $adminId){
    global $db;
    $eventId = $db->real_escape_string($eventId);
    $adminId = $db->real_escape_string($adminId);
    $scope = $scope ? $db->real_escape_string($scope) : NULL;

    switch($scope){
        case 'rejected':
            $filter = ' AND aid_paid=-1 ';
            break;
        case 'handled':
            $filter = ' AND aid_paid > 0 ';
            break;
        case 'unhandled':
            $filter = ' AND ( aid_paid IS NULL OR aid_paid=0) ';
            break;
        default:
            $filter = '';
    }

    $res=db_query ("SELECT DISTINCT * FROM (
    SELECT m.key as id, m.name as name, IF (COALESCE(l.name,'')='', m.new_locality, l.name) as locality,
    reg.fellowship, reg.contr_amount, reg.trans_amount, COALESCE(reg.aid_paid,0) as aid_paid
    FROM access as a
    LEFT JOIN country c ON c.key = a.country_key
    LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
    INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
    INNER JOIN member m ON m.locality_key = l.key
    INNER JOIN reg ON reg.member_key = m.key
    INNER JOIN event e ON e.key = reg.event_key
    WHERE a.member_key='$adminId' AND reg.event_key='$eventId' AND reg.aid > 0 $filter
    UNION
    SELECT m.key as id, m.name as name, IF (COALESCE(l.name,'')='', m.new_locality, l.name) as locality,
    reg.fellowship, reg.contr_amount, reg.trans_amount, COALESCE(reg.aid_paid, 0) as aid_paid
    FROM reg
    INNER JOIN member m ON m.key = reg.member_key
    LEFT JOIN locality l ON l.key = m.locality_key
    INNER JOIN event e ON e.key = reg.event_key
    WHERE (reg.admin_key = '$adminId' OR m.admin_key='$adminId') AND reg.event_key='$eventId' AND reg.aid > 0 $filter ) q ORDER BY q.name");

    $members = array ();
    while ($row = $res->fetch_object()) $members[]=$row;
    return $members;
}

function db_setUserAid($member_id, $amount, $eventId){
    global $db;
    $member_id = $db->real_escape_string($member_id);
    $amount = $db->real_escape_string($amount);
    $eventId = $db->real_escape_string($eventId);

    db_query ("UPDATE reg SET aid_paid='$amount' WHERE member_key='$member_id' AND event_key='$eventId'");
}

function db_getCollegesNames(){
    $res = db_query("SELECT `key` as id, short_name, name FROM college");

    $colleges = array ();
    while ($row = $res->fetch_assoc()) $colleges[$row['id']]=$row['short_name']." (".$row['name']." )";
    return $colleges;
}

function db_getColleges($member_id){
    global $db;
    $member_id = $db->real_escape_string($member_id);

    //$search = $text ? " AND (c.name LIKE '%$text%' OR c.short_name LIKE '%$text%' OR l.name LIKE '%$text%') " : "";

    $res = db_query("SELECT c.key as id, c.short_name, c.name, l.name as locality, l.key as locality_key,
                CASE WHEN c.author='$member_id' THEN 1 ELSE 0 END as author
                FROM college c INNER JOIN locality l ON c.locality_key=l.key ORDER BY c.short_name ASC ");

    $colleges = array ();
    while ($row = $res->fetch_assoc()) $colleges[]=$row;
    return $colleges;
}

function db_getCollegesLocality(){
    $res = db_query("SELECT DISTINCT l.name as locality, l.key as locality_key
                FROM college c INNER JOIN locality l ON c.locality_key=l.key ORDER BY l.name ASC");

    $localities = array ();
    while ($row = $res->fetch_assoc()) $localities[]=$row;
    return $localities;
}

function db_setCollege($collegeId, $name, $shortName, $locality, $adminId){
    global $db;
    $collegeId = $db->real_escape_string($collegeId);
    $name = $db->real_escape_string($name);
    $shortName = $db->real_escape_string($shortName);
    $locality = $db->real_escape_string($locality);
    $adminId = $db->real_escape_string($adminId);

    if($collegeId){
        db_query("UPDATE college SET locality_key='$locality', name='$name', short_name='$shortName', changed=1 WHERE `key`='$collegeId'");
    }
    else {
        $newCollegeId = db_getNewCollegeKey();
        db_query("INSERT INTO college (`key`, locality_key, name, short_name, author, changed) VALUE ('$newCollegeId', '$locality', '$name', '$shortName', '$adminId', 1)");

        $adminName = db_getAdminNameById($adminId);

        db_sendMsgToRespOneSync(COLLEGE_TYPE, [ 'name' => $name, 'short_name' => $shortName, 'author' => $adminName]);
    }
}

function db_getNewCollegeKey (){
    $res=db_query ("SELECT `key` as id FROM college WHERE `key` LIKE '9%' ORDER BY `key` DESC LIMIT 1");
    $row = $res->fetch_object();
    $key = "90000";
    if ($row && strlen($row->id) == 5) $key = (string)($row->id + 1);
    return $key;
}

function db_deleteCollege($collegeId){
    global $db;
    $collegeId = $db->real_escape_string($collegeId);
    db_query ("DELETE FROM college WHERE `key`='$collegeId'");
}


function db_isMemberRegistrated($memberId){
    global $db;
    $memberId = $db->real_escape_string($memberId);

    $res = db_query("SELECT * FROM reg WHERE member_key='$memberId'");

    if($res->num_rows>0){
        return true;
    }

    return false;
}

function db_removeMember($memberId){
    global $db;
    $memberId = $db->real_escape_string($memberId);

    db_query("DELETE FROM member WHERE `key`='$memberId'");
    db_query("DELETE FROM admin WHERE member_key='$memberId'");
}

function db_removeAccount($memberId, $reason){
    global $db;
    $memberId = $db->real_escape_string($memberId);
    $reason = $db->real_escape_string($reason);

    db_query("UPDATE member SET active=0, comment='$reason', changed=1 WHERE `key`='$memberId' ");
    db_logoutAdmin($memberId);
}

function db_getCurrencies(){
    $res = db_query("SELECT `key`, name FROM currency");
    $currencies = [];
    while ($row = $res->fetch_assoc()) $currencies[$row['key']]=$row['name'];
    return $currencies;
}

function db_isAdminRespForReg($adminId, $eventId){
    global $db;

    $adminId = $db->real_escape_string($adminId);
    $eventId = $db->real_escape_string($eventId);
    $res = db_query("SELECT * FROM event_access WHERE member_key='$adminId' AND `key`='$eventId'");

    return $res->num_rows > 0;
}

function db_getAdminEventsRespForReg($adminId){
    global $db;

    $adminId = $db->real_escape_string($adminId);
    $res = db_query("SELECT `key` as event_id FROM event_access WHERE member_key='$adminId'");

    $events = [];
    while ($row = $res->fetch_assoc()) $events [] = $row['event_id'];
    return $events;
}

function db_hasAdminFullAccess($adminId){
    global $db;

    $adminId = $db->real_escape_string($adminId);
    $res = db_query("SELECT * FROM event_access WHERE member_key='$adminId'");

    return $res->num_rows > 0;
}

function db_searchMembersToAdd($text, $eventId){
    global $db;
    $text = $db->real_escape_string($text);
    $eventId = $db->real_escape_string($eventId);

    $res = db_query("SELECT DISTINCT m.name, m.key, l.name as locality_name
            FROM member m
            INNER JOIN locality l ON l.key=m.locality_key
            INNER JOIN reg r ON r.member_key<>m.key
            INNER JOIN event e ON e.key=r.event_key
            WHERE (m.name LIKE '%$text%' OR l.name LIKE '%$text%' OR m.new_locality LIKE '%$text%') AND e.key='$eventId' ORDER BY m.name ASC LIMIT 0,5 ");

    if($res->num_rows>0){
        $members = [];
        while($row = $res->fetch_object()) $members[]=$row;
        return $members;
    }
    return null;
}

function db_unregisterMembersService($adminId, $eventId, $memberIds, $reason){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $eventId = $db->real_escape_string($eventId);
    $_reason = $reason ? ", admin_comment='".$db->real_escape_string($reason)."' " : '';

    db_checkSync ();

    $ids='';
    foreach ($memberIds as $memberId) $ids.="'".$db->real_escape_string($memberId)."',";
    $ids = rtrim($ids,',');

    db_query ("DELETE FROM member WHERE `key` IN ({$ids}) AND `key` LIKE '99%' AND (SELECT COUNT(*) FROM reg WHERE member_key=member.key)<2");

    db_query ("DELETE FROM reg WHERE member_key IN ({$ids})
               AND event_key='$eventId' AND member_key LIKE '99%' AND (SELECT COUNT(*) FROM member WHERE `key`=reg.member_key)<2");

    db_query ("UPDATE reg SET regstate_key='05', admin_key='$adminId' $_reason
               WHERE member_key IN ({$ids}) AND event_key='$eventId'");
}

function db_getEventLocalities($eventId){
    global $db;
    $eventId = $db->real_escape_string($eventId);

    $res = db_query("SELECT DISTINCT l.key as `key`, l.name as name FROM locality l
            INNER JOIN member m ON m.locality_key = l.key
            INNER JOIN reg r ON r.member_key = m.key
            WHERE r.event_key='$eventId' ORDER BY l.name ASC");

    $localities = array ();
    while ($row = $res->fetch_assoc()) $localities[$row['key']]=$row['name'];
    return $localities;
}

function db_getAdminEventLocalities ($eventId, $adminId)
{
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $eventId = $db->real_escape_string($eventId);

    $res=db_query ("SELECT DISTINCT * FROM
  (
    SELECT IF (COALESCE(l.name,'')='', m.new_locality, l.name) as locality, m.locality_key as locality_key
    FROM access as a
    LEFT JOIN country c ON c.key = a.country_key
    LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
    INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
    INNER JOIN member m ON m.locality_key = l.key
    INNER JOIN reg ON reg.member_key = m.key
    LEFT JOIN service srv ON srv.key = reg.service_key
    INNER JOIN event e ON e.key = reg.event_key
    LEFT JOIN document d ON d.key = m.document_key
    WHERE a.member_key='$adminId' AND reg.event_key='$eventId'
    UNION
    SELECT IF (COALESCE(l.name,'')='', m.new_locality, l.name) as locality, m.locality_key as locality_key
    FROM reg
    INNER JOIN member m ON m.key = reg.member_key
    LEFT JOIN locality l ON l.key = m.locality_key
    LEFT JOIN service srv ON srv.key = reg.service_key
    INNER JOIN event e ON e.key = reg.event_key
    LEFT JOIN document d ON d.key = m.document_key
    WHERE (reg.admin_key = '$adminId' OR m.admin_key='$adminId') AND reg.event_key='$eventId'
    ) q");

    $localities = array ();
    while ($row = $res->fetch_assoc()) $localities[$row['locality_key']]=$row['locality'];
    return $localities;
}

function db_getAdminLocality($adminId){
    global $db;
    $adminId = $db->real_escape_string($adminId);

    $res = db_query("SELECT locality_key FROM member WHERE `key`='$adminId'");

    if($res->num_rows>0){
        return $res->fetch_assoc()['locality_key'];
    }
    return '';
}

function db_setAttendedDismissMembersEvent($adminId, $event, $setAttendedMembers, $dismissAttendedMembers){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $event = $db->real_escape_string($event);
    $setAttendedMembers = $db->real_escape_string($setAttendedMembers);
    $dismissAttendedMembers = $db->real_escape_string($dismissAttendedMembers);

    if($dismissAttendedMembers){
        db_query("UPDATE reg SET attended=0, admin_key='$adminId' WHERE event_key='$event' AND member_key IN ($dismissAttendedMembers)");
    }

    if($setAttendedMembers){
        db_query("UPDATE reg SET attended=1, admin_key='$adminId' WHERE event_key='$event' AND member_key IN ($setAttendedMembers)");
    }
}

function db_setMembersRegstateEvent($adminId, $event, $memberId, $state){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $event = $db->real_escape_string($event);
    $memberId = $db->real_escape_string($memberId);
    $state = $db->real_escape_string($state);

    if($state == '03'){
        db_query ("DELETE FROM member WHERE `key`='$memberId' AND `key` LIKE '99%' AND (SELECT COUNT(*) FROM reg WHERE member_key=member.key)<2");

        db_query ("DELETE FROM reg
                   WHERE (regstate_key IS NULL OR regstate_key='' OR regstate_key='01' OR regstate_key='05')
                   AND member_key ='$memberId' AND event_key='$event'");

        db_query ("UPDATE reg SET regstate_key='03', admin_key='$adminId'
                   WHERE (regstate_key='02' OR regstate_key='04')
                   AND member_key='$memberId' AND event_key='$event'");

    }
    else if($state == '06' || $state == '07'){
        db_query("UPDATE reg SET attended=".($state == '06' ? 1 : 0)." WHERE event_key='$event' AND member_key = '$memberId' ");
    }
    else if($state == '04'){
        if (checkMemberEventREadyToRegistrate($memberId, $event)) {
            db_query("UPDATE reg SET regstate_key='04' WHERE event_key='$event' AND member_key = '$memberId' ");
        }
        else{
            throw new Exception('Необходимо заполнить все обязательные поля выделенные розовым цветом!');
        }
    }
    else{
        db_query("UPDATE reg SET regstate_key='$state', admin_key='$adminId' WHERE event_key='$event' AND member_key = '$memberId' ");
    }
}

function checkMemberEventReadyToRegistrate($memberId, $event){
    $member = db_getEventMember($memberId, $event);

    $isReady = true;
    if (!$member['name'] || !$member['birth_date'] || !$member['gender'] ||
        !$member['citizenship_key'] || (!$member['locality_key'] && !$member['new_locality']) ||
        !$member['address'] || !$member['category_key'] ||
        (db_getNeedPassport ($event) && (!$member['document_key'] || !$member['document_num'] || !$member['document_date'] || !$member['document_auth'])) ||

        !$member['dep_date'] || !$member['arr_date'] || !$member['accom'] ||
        (db_getNeedPassportTp ($event) && (!$member['tp_num'] || !$member['tp_date'] || !$member['tp_auth'] || !$member['tp_name'] )) ||
        (db_getNeedFlight ($event) && ($member['visa'] == '0' || $member['english'] == null)) ||
        (db_getNeedTransport($event) && !$member['transport']) ||
        ($member['aid'] > 0 && $member['contr_amount'] === 0 && $member['trans_amount'] === 0)) {
        $isReady = false;
    }

    return $isReady;
}

function db_setMembersStateEvent($adminId, $event, $memberId, $state){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $event = $db->real_escape_string($event);
     $memberId = $db->real_escape_string($memberId);
     $state = $db->real_escape_string($state);

     if($state == '03'){
         db_query ("DELETE FROM member WHERE `key`='$memberId' AND `key` LIKE '99%' AND (SELECT COUNT(*) FROM reg WHERE member_key=member.key)<2");

         db_query ("DELETE FROM reg
                    WHERE (regstate_key IS NULL OR regstate_key='' OR regstate_key='01' OR regstate_key='05')
                    AND member_key ='$memberId' AND event_key='$event'");

         db_query ("UPDATE reg SET regstate_key='03', admin_key='$adminId'
                    WHERE (regstate_key='02' OR regstate_key='04')
                    AND member_key='$memberId' AND event_key='$event'");

     }
     else if($state == '06' || $state == '07'){
         db_query("UPDATE reg SET attended=".($state == '06' ? 1 : 0)." WHERE event_key='$event' AND member_key = '$memberId' ");
     }
     else if($state == '04'){
         if (checkMemberEventREadyToRegistrate($memberId, $event)) {
             db_query("UPDATE reg SET regstate_key='04' WHERE event_key='$event' AND member_key = '$memberId' ");
         }
         else{
             throw new Exception('Необходимо заполнить все обязательные поля выделенные розовым цветом!');
         }
     }
     else{
         db_query("UPDATE reg SET regstate_key='$state', admin_key='$adminId' WHERE event_key='$event' AND member_key = '$memberId' ");
     }
 }

function db_confirmRegistrationBulkMembersEvent($admin, $event, $members){
    global $db;
    $admin = $db->real_escape_string($admin);
    $event = $db->real_escape_string($event);
    $membersWithNotFilledRequiredField = [];
    $membersWithWrongRegstate= [];

    if($members){
        foreach ($members as $memberId){
            $memberId = $db->real_escape_string($memberId);

            $res = db_query("SELECT m.name, r.regstate_key FROM member m INNER JOIN reg r ON r.member_key=m.key WHERE r.event_key='$event' AND m.key ='$memberId'");
            $row = $res->fetch_assoc();

            if(checkMemberEventReadyToRegistrate($memberId, $event)){
                $row['regstate_key'] != '01' || $row['regstate_key'] != '02' || $row['regstate_key'] != NULL ?
                    $membersWithWrongRegstate [] = $row['name'] :
                    db_query("UPDATE reg SET regstate_key='04' WHERE event_key='$event' AND (regstate_key IS NULL OR regstate_key='01' OR regstate_key='02' ) AND member_key ='$memberId'");
            }
            else{
                $membersWithNotFilledRequiredField[] = $row['name'];
            }
        }

        if(count($membersWithNotFilledRequiredField) > 0){
            throw new Exception("Некоторые участники не были зарегистрированы ( ". (implode(', ', $membersWithNotFilledRequiredField)) ."). Проверьте правильность заполнения обязательных полей!");
        }

        if(count($membersWithWrongRegstate) > 0){
            throw new Exception("Некоторые участники не были зарегистрированы ( ". (implode(', ', $membersWithWrongRegstate)) ."). Состояние учасников должно ожидать подтверждения!");
        }
    }
}

// MEETINGS

function db_getMeetings($adminId, $sort_type, $sort_field, $localityFilter, $meetingTypeFilter, $startDate, $endDate){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $localityFilter = $db->real_escape_string($localityFilter);
    $meetingTypeFilter = $db->real_escape_string($meetingTypeFilter);
    $startDate = $db->real_escape_string($startDate);
    $endDate = $db->real_escape_string($endDate);
    $sort_type = $db->real_escape_string($sort_type);
    $sort_field = $sort_field != 'locality_key' ? $db->real_escape_string($sort_field)."" : " locality_name ";

    $meetings = array ();

    $requestMeeting = $meetingTypeFilter == "_all_" ? "" : " AND me.meeting_type='$meetingTypeFilter' ";
    $requestLocality = $localityFilter=="_all_" ? "" : " AND l.key='$localityFilter' ";
    $requestDates = " AND (me.date BETWEEN '$startDate' AND '$endDate')";
    $requestCheckMeetingAdditions = "SELECT COUNT(*) count FROM member m WHERE m.locality_key=l.key AND m.category_key='FS'";

    db_query('SET Session group_concat_max_len=20000');

    $res = db_query("SELECT DISTINCT * FROM (
            SELECT me.name, me.id, l.name as locality_name, me.date, 0 as add_list_count, me.list_count, me.saints_count,
            mt.name as meeting_name, mt.short_name, mt.key as meeting_type, me.guests_count, me.children_count,
            me.locality_key, me.fulltimers_count,
            (SELECT GROUP_CONCAT( CONCAT_WS(':', mb.key, mb.name, lo.name, mb.attend_meeting) ORDER BY mb.name ASC SEPARATOR ',') FROM member mb INNER JOIN locality lo ON lo.key=mb.locality_key WHERE FIND_IN_SET(mb.key, me.members)<>0) as members,
            (SELECT COUNT(*) FROM member m WHERE FIND_IN_SET(m.key, me.participants)<>0 AND m.category_key='FS') as fulltimers_in_list, me.participants,
            me.trainees_count,
            IF(($requestCheckMeetingAdditions), 1, 0) as show_additions,
            d.district as district, 0 as summary
            FROM access a
            LEFT JOIN country c ON c.key = a.country_key
            LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
            INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
            LEFT JOIN district d ON d.district=l.key
            INNER JOIN meetings me ON me.locality_key = l.key
            INNER JOIN meeting_type mt ON mt.key=me.meeting_type
            WHERE a.member_key='$adminId' $requestMeeting $requestLocality $requestDates
            UNION
            SELECT me.name, GROUP_CONCAT(me.id), l.name as locality_name, me.date,
            (SELECT COUNT(*) FROM member m WHERE (m.locality_key = l.key) AND (DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365 >= 12 OR m.birth_date IS NULL)) as add_list_count,
            GROUP_CONCAT(me.list_count), GROUP_CONCAT(me.saints_count),
            mt.name as meeting_name, mt.short_name, mt.key as meeting_type, GROUP_CONCAT(me.guests_count),
            GROUP_CONCAT(me.children_count), d.locality_key,
            GROUP_CONCAT(me.fulltimers_count),
            (SELECT GROUP_CONCAT( CONCAT_WS(':', mb.key, mb.name, lo.name, mb.attend_meeting) ORDER BY mb.name ASC SEPARATOR ',') FROM member mb INNER JOIN locality lo ON lo.key=mb.locality_key WHERE FIND_IN_SET(mb.key, me.members)<>0) as members,
            (SELECT COUNT(*) FROM member m WHERE FIND_IN_SET(m.key, me.participants)<>0 AND m.category_key='FS') as fulltimers_in_list, me.participants,
            GROUP_CONCAT(me.trainees_count),
            IF(($requestCheckMeetingAdditions), 1, 0) as show_additions,
            GROUP_CONCAT(d.district), 1 as summary
            FROM access a
            LEFT JOIN country c ON c.key = a.country_key
            LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
            INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
            INNER JOIN district d ON d.locality_key=l.key
            INNER JOIN meetings me ON me.locality_key = d.district
            INNER JOIN meeting_type mt ON mt.key=me.meeting_type
            WHERE a.member_key='$adminId' $requestMeeting $requestDates $requestLocality GROUP BY me.date, me.meeting_type
            UNION
            SELECT me.name, me.id, l.name as locality_name, me.date, 0 as add_list_count, me.list_count, me.saints_count,
            mt.name as meeting_name, mt.short_name, mt.key as meeting_type, me.guests_count, me.children_count,
            me.locality_key, me.fulltimers_count,
            (SELECT GROUP_CONCAT( CONCAT_WS(':', mb.key, mb.name, lo.name, mb.attend_meeting) ORDER BY mb.name ASC SEPARATOR ',') FROM member mb INNER JOIN locality lo ON lo.key=mb.locality_key WHERE FIND_IN_SET(mb.key, me.members)<>0) as members,
            (SELECT COUNT(*) FROM member m WHERE FIND_IN_SET(m.key, me.participants)<>0 AND m.category_key='FS') as fulltimers_in_list, me.participants,
            me.trainees_count,
            IF(($requestCheckMeetingAdditions), 1, 0) as show_additions,
            d.district as district, 0 as summary
            FROM meetings me
            INNER JOIN meeting_type mt ON mt.key=me.meeting_type
            INNER JOIN locality l ON l.key = me.locality_key
            LEFT JOIN district d ON d.locality_key=me.locality_key
            INNER JOIN meeting_template mtl ON mtl.locality_key = l.key
            WHERE FIND_IN_SET('$adminId', mtl.admin)<>0 $requestMeeting $requestDates $requestLocality
            ) q ORDER BY q.$sort_field ".$sort_type.", q.locality_name ASC");

    while ($row = $res->fetch_object()) $meetings[]=$row;
    return $meetings;
}

function db_getLocalityMembers($localityId){
    global $db;
    $_localityId = $db->real_escape_string($localityId);

    $res = db_query("SELECT CONCAT_WS(':', m.key, m.name, lo.name, m.attend_meeting) as member
        FROM member m
        INNER JOIN locality lo ON lo.key=m.locality_key
        WHERE lo.key = '$_localityId' ORDER BY m.name ASC");

    $members = [];
    while ($row = $res->fetch_assoc()) {
        $members[] = $row;
    }

    return $members;
}

function db_getMeetingMembersList($adminId, $meetingId, $locality, $date, $is_summary_meeting, $meeting_type){
    global $db;
    $meetingId = $db->real_escape_string($meetingId);
    $adminId = $db->real_escape_string($adminId);
    $locality = $db->real_escape_string($locality);
    $date = $db->real_escape_string($date);
    $is_summary_meeting = $db->real_escape_string($is_summary_meeting);
    $meeting_type = $db->real_escape_string($meeting_type);
    db_query('SET Session group_concat_max_len=20000');

    $req = $is_summary_meeting == 'true' ? "FIND_IN_SET(m.key, me.participants)<>0 AND me.date='$date' AND me.meeting_type='$meeting_type' " : " me.id='$meetingId' ";

    $r = db_query("SELECT members FROM meetings WHERE id ='$meetingId' ");
    $rowR = $r->fetch_assoc();

    if(!$rowR['members']){
        $res=db_query (
            "SELECT DISTINCT m.key as member_key, m.name as name, m.birth_date, l.name as locality_name,
            CASE WHEN FIND_IN_SET(m.key, me.participants)<>0 THEN 1 ELSE 0 END as attended
            FROM access a
            LEFT JOIN country c ON c.key = a.country_key
            LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
            INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
            LEFT JOIN district d ON d.locality_key=l.key
            INNER JOIN member m ON m.locality_key = l.key OR m.locality_key=d.district
            LEFT JOIN meetings me ON $req
            WHERE a.member_key='$adminId' ORDER BY name ASC"
        );
    }
    else{
        $res=db_query (
            "SELECT DISTINCT m.key as member_key, m.name as name, m.birth_date, l.name as locality_name,
            CASE WHEN FIND_IN_SET(m.key, me.participants)<>0 THEN 1 ELSE 0 END as attended
            FROM meetings me
            INNER JOIN member m ON FIND_IN_SET(m.key, me.members)<>0
            INNER JOIN locality l ON l.key=m.locality_key
            WHERE me.id='$meetingId' ORDER BY name ASC"
        );
    }
    $list = array ();
    while ($row = $res->fetch_object()) $list[]=$row;
    return $list;
}

function db_getMeetingsTypes(){
    $res = db_query("SELECT * FROM meeting_type");

    $types = array ();
    while ($row = $res->fetch_assoc()) $types[$row['key']]=$row['name'];
    return $types;
}

function db_getAdminMeetingLocalities($adminId){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    db_query('SET Session group_concat_max_len=20000');

    $res=db_query ("SELECT DISTINCT * FROM (
                    SELECT l.key as id, l.name as name
                    FROM access a
                    LEFT JOIN country c ON c.key = a.country_key
                    LEFT JOIN region r ON r.key = a.region_key or c.key=r.country_key
                    INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
                    WHERE a.member_key='$adminId'
                    UNION
                    SELECT d.district as id, l.name as name
                    FROM access a
                    LEFT JOIN country c ON c.key = a.country_key
                    LEFT JOIN region r ON r.key = a.region_key or c.key=r.country_key
                    INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
                    INNER JOIN district d ON d.locality_key = l.key
                    WHERE a.member_key='$adminId'
                    UNION
                    SELECT l.key as id, l.name as name
                    FROM meeting_template mt
                    INNER JOIN locality l ON l.key = mt.locality_key
                    WHERE FIND_IN_SET('$adminId', mt.admin)<>0
                    ) q ORDER BY q.name ASC");

    $localities = array ();
    while ($row = $res->fetch_assoc()) $localities[$row['id']]=$row['name'];
    return $localities;
}

function db_setMeetingMembersList($list, $meetingId, $childrenCount){
    global $db;
    $list = $db->real_escape_string($list);
    $meetingId = $db->real_escape_string($meetingId);
    $childrenCount = (int)$childrenCount;

    $saintsCount = count(explode(',', $list));
    $fulltimersCount = getCountFullTimers($list);
    $traineesCount = getCountTrainees($list);

    db_query("UPDATE meetings SET participants='$list', fulltimers_count=$fulltimersCount, trainees_count=$traineesCount,
            saints_count=".($saintsCount==1 && $list=='' ? 0 : $saintsCount).", children_count=$childrenCount
            WHERE id='$meetingId' ");
}

function getCountTrainees($list){
    global $db;
    $list = $db->real_escape_string($list);

    $res = db_query("SELECT COUNT(*) as count FROM member m WHERE m.key IN($list) AND m.category_key='FT'");

    if($res->num_rows>0){
        $row = $res->fetch_assoc();
        return (int)$row['count'];
    }
    return 0;
}

function getCountFullTimers($list){
    global $db;
    $list = $db->real_escape_string($list);

    $res = db_query("SELECT COUNT(*) as count FROM member m WHERE m.key IN($list) AND m.category_key='FS'");

    if($res->num_rows>0){
        $row = $res->fetch_assoc();
        return (int)$row['count'];
    }
    return 0;
}

function db_setMeeting($data){
    global $db;

    $date = $db->real_escape_string($data['date']);
    $locality = $db->real_escape_string($data['locality']);
    $meetingType = $db->real_escape_string($data['meetingType']);
    $note = $db->real_escape_string($data['note']);
    $name = $db->real_escape_string($data['meetingName']);

    $oldDate = isset($data['oldDate'] ) ? $db->real_escape_string($data['oldDate']) : null;
    $oldLocality = isset($data['oldLocality'] ) ? $db->real_escape_string($data['oldLocality']) : null;
    $oldMeetingType = isset($data['oldMeetingType'] ) ? $db->real_escape_string($data['oldMeetingType']) : null;

    $listCount = (int)($data['listCount']);
    $guestsCount = (int)($data['countGuest']);
    $childrenCount = (int)($data['countChildren']);

    $saintsCount = (int)($data['saintsCount']);
    $traineesCount = (int)($data['traineesCount']);
    $fulltimersCount = (int)($data['fulltimersCount']);

    $meetingId = isset($data['meetingId']) ? $db->real_escape_string($data['meetingId']) : null;

    $members = $data['members'];
    $attendMembers = $db->real_escape_string( $data['attendMembers']);
    $listCount = $members ? count(explode(',', $data['members'])) : db_getCountMembersByLocality($locality);

    if($meetingId){
        if(($date != $oldDate || $locality != $oldLocality || $meetingType != $oldMeetingType) && checkDoubleMeeting($date, $locality, $meetingType)){
            return true;
        }

        db_query("UPDATE meetings SET name='$name', children_count=$childrenCount, meeting_type='$meetingType',
                date='$date', locality_key='$locality', note='$note', guests_count=$guestsCount,
                children_count=$childrenCount, list_count=$listCount, saints_count=$saintsCount, members = '$members',
                trainees_count=$traineesCount, fulltimers_count=$fulltimersCount, participants='$attendMembers' WHERE id='$meetingId' ");
    }
    else {

        db_query("INSERT INTO meetings (meeting_type, date, locality_key, note,
                                        guests_count, children_count, list_count,
                                        saints_count, trainees_count, fulltimers_count, name, members, participants )
                VALUE ('$meetingType', '$date', '$locality', '$note', $guestsCount,
                        $childrenCount, $listCount, $saintsCount, $traineesCount, $fulltimersCount, '$name', '$members', '$attendMembers' )");
    }

    return false;
}

function db_getCountMembersByLocality($locality){
    global $db;
    $locality = $db->real_escape_string($locality);

    $res=db_query ("SELECT COUNT(DISTINCT m.key) as count
                    FROM access a
                    LEFT JOIN country c ON c.key = a.country_key
                    LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
                    INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
                    LEFT JOIN district d ON d.locality_key=l.key
                    INNER JOIN member m ON m.locality_key = l.key OR m.locality_key=d.district
                    WHERE l.key='$locality' AND (DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365 >= 12 OR m.birth_date IS NULL)");

    if($res->num_rows>0){
        $row = $res->fetch_assoc();
        return (int)$row['count'];
    }

    return 0;
}

function checkDoubleMeeting($date, $locality, $meetingType){
    $res = db_query("SELECT * FROM meetings WHERE date='$date' AND meeting_type='$meetingType' AND locality_key='$locality'");

    if($res->num_rows>0){
        return true;
    }
    return false;
}

function getMeetingMembersStatistic($adminId, $meetingType, $locality, $startDate, $endDate){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $meetingType = $meetingType == '_all_' ? '' : " AND me.meeting_type='".$db->real_escape_string($meetingType)."'";
    $locality = $db->real_escape_string($locality);
    $startDate = $db->real_escape_string($startDate);
    $endDate = $db->real_escape_string($endDate);
    $requestDates = " AND (me.date BETWEEN '$startDate' AND '$endDate')";
    db_query('SET Session group_concat_max_len=20000');

    $res=db_query ("SELECT * FROM (
                    SELECT m.key as member_key, m.name as name, l.name as locality_name, m.locality_key as locality_key,
                    COALESCE( GROUP_CONCAT(CONCAT_WS(':', me.meeting_type, me.date) ORDER BY me.date ASC SEPARATOR  ','), '') as meeting,
                    (SELECT COUNT(*) FROM member mb WHERE mb.locality_key=l.key) as general_count
                    FROM access a
                    LEFT JOIN country c ON c.key = a.country_key
                    LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
                    INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
                    INNER JOIN member m ON m.locality_key = l.key
                    INNER JOIN meetings me ON FIND_IN_SET(m.key, me.participants)<>0
                    WHERE a.member_key='$adminId' $meetingType ".($locality == '_all_' ? '' : " AND l.key='$locality'")." $requestDates GROUP BY m.key
                    UNION
                    SELECT m.key as member_key, m.name as name, l.name as locality_name, m.locality_key as locality_key,
                    COALESCE( GROUP_CONCAT(CONCAT_WS(':', me.meeting_type, me.date) ORDER BY me.date ASC SEPARATOR  ','), '') as meeting,
                    (SELECT COUNT(*) FROM member mb WHERE mb.locality_key=l.key) as general_count
                    FROM access a
                    LEFT JOIN country c ON c.key = a.country_key
                    LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
                    INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
                    INNER JOIN district d ON d.district=l.key
                    INNER JOIN member m ON m.locality_key = l.key
                    INNER JOIN meetings me ON FIND_IN_SET(m.key, me.participants)<>0
                    WHERE a.member_key='$adminId' $meetingType ".($locality == '_all_' ? '' : " AND (l.key='$locality' OR d.locality_key='$locality' )")." $requestDates GROUP BY m.key ) q ORDER BY q.name ASC");

    $list = array ();
    while ($row = $res->fetch_object()) $list[]=$row;
    return $list;
}

function checkIfLocationHasFulltimers($locality){
    global $db;
    $locality = $db->real_escape_string($locality);

    $res = db_query("SELECT COUNT(*) count FROM member m WHERE m.locality_key='$locality' AND m.category_key='FS'");

    $row = $res->fetch_assoc();
    if($row['count']>0){
        return true;
    }
    return false;
}

function db_getSingleAdminLocality ($adminId)
{
    global $db;
    $adminId = $db->real_escape_string($adminId);

    $res=db_query ("SELECT a.locality_key as id FROM access a WHERE a.member_key='$adminId'");

    $row = $res->fetch_assoc();
    return $row['id'];
}

function getCountMembersAdminsLocalities($adminId, $locality){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $locality = $locality === '_all_' ? '' : " AND l.key='".$db->real_escape_string($locality)."'";

    $res=db_query ("SELECT DISTINCT m.key as count
        FROM access a
        LEFT JOIN country c ON c.key = a.country_key
        LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
        INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
        LEFT JOIN district d ON d.locality_key=l.key
        INNER JOIN member m ON m.locality_key = l.key OR m.locality_key=d.district
        WHERE a.member_key='$adminId' $locality ");

    $count = 0;
    while($row = $res->fetch_assoc()){
        $count ++;
    }
    return $count;
}

function db_checkIfEventCreatedFromSite($adminId, $event){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $event = $db->real_escape_string($event);

    $res = db_query("SELECT COUNT(*) as count FROM event e INNER JOIN event_access ea ON ea.key=e.key WHERE e.key='$event' AND e.web = 1 AND ea.member_key='$adminId' AND ea.key='$event' ");
    $row = $res->fetch_assoc();

    if($row['count']>0){
        return true;
    }
    return false;
}

function db_checkIfAdminHasServiceRightEvent($adminId, $event){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $event = $db->real_escape_string($event);

    $res = db_query("SELECT COUNT(*) as count FROM event_access ea WHERE ea.member_key='$adminId' AND ea.key='$event' ");
    $row = $res->fetch_assoc();

    if($row['count']>0){
        return true;
    }
    return false;
}



/* EVENTS */
function db_isAdmin($adminId){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $res=db_query ("SELECT DISTINCT l.key as id, l.name as name FROM access a
                    LEFT JOIN country c ON c.key = a.country_key
                    LEFT JOIN region r ON r.key = a.region_key or c.key=r.country_key
                    LEFT JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
                    WHERE a.member_key='$adminId'");

    return $res->num_rows > 0;
}

function db_signUpMember($session_id, $login, $password, $name, $birthDate, $gender, $citizenship, $locality, $newLocality){
    global $db;
    $_session_id = $db->real_escape_string($session_id);
    $_login = $db->real_escape_string($login);
    $_password = $db->real_escape_string($password);
    $_name = $db->real_escape_string($name);
    $_birthDate = $db->real_escape_string($birthDate);
    $_gender = $db->real_escape_string($gender);
    $_citizenship = $db->real_escape_string($citizenship);
    $_locality = $db->real_escape_string($locality);
    $_newLocality = $db->real_escape_string($newLocality);

    db_checkSync ();

    if(!db_isAdminExist ($_login)){
        $newMemberId = db_getNewMemberKey();

        $stmt = $db->prepare("INSERT INTO member (`key`, email, name, birth_date, locality_key, male, citizenship_key, new_locality, changed, category_key) VALUES ('$newMemberId', '$_login', '$_name', '$_birthDate', ?, '$_gender', '$_citizenship', ?, 1, 'BL')");
        $newLocalityData = $locality == '_none_' ? $_newLocality : null;
        $localityData = $locality !== '_none_' ? $_locality : null;
        $stmt->bind_param('ss', $localityData, $newLocalityData);
        $stmt->execute();

        db_query ("INSERT INTO admin (member_key, login, password, session, role, created, changed) VALUES ('$newMemberId', '$_login', '$_password', '$_session_id', 0, now(), 1)");

        //db_sendMessageNewMember($_login,  $_name);
        $data = [];
        $data ['name'] = $_name;
        $data ['key'] = $newMemberId;
        $data ['author'] = $_name;
        db_sendMsgToRespOneSync(USER_TYPE, $data);
        return true;
    }
    return false;
}

function db_addMember ($session_id, $login, $password, $name){
    global $db;
    $_session_id = $db->real_escape_string($session_id);
    $_login = $db->real_escape_string($login);
    $_password = $db->real_escape_string($password);
    $_name = $db->real_escape_string($name);
    db_checkSync ();
    $newMemberId = db_getNewMemberKey();
    db_query ("INSERT INTO member (`key`, email, name, changed) VALUES ('$newMemberId', '$_login', '$_name', 1)");
    db_query ("INSERT INTO admin (member_key, login, password, session, role, changed) VALUES ('$newMemberId', '$_login', '$_password', '$_session_id', 0, 1)");
    $data = [];
    $data['name']= $_name;
    $data ['key']= $newMemberId;
    $data ['author'] = $_name;

    db_sendMsgToRespOneSync(USER_TYPE, $data);
}

function db_addNewMember ($name, $locality_key, $gender, $birth_date, $category_key, $attend_meeting, $locality_name, $citizenship,
    $baptize_date, $adminId){
    global $db;
    $_name = $db->real_escape_string($name);
    $_locality_key = $db->real_escape_string($locality_key);
    $_gender = $db->real_escape_string($gender);
    $_birth_date = $db->real_escape_string($birth_date);
    $_category_key = $db->real_escape_string($category_key);
    $_attend_meeting = $db->real_escape_string($attend_meeting);
    $_locality_name = $db->real_escape_string($locality_name);
    $_citizenship = $db->real_escape_string($citizenship);
    $_baptize_date = $db->real_escape_string($baptize_date);
    $_adminId = $db->real_escape_string($adminId);

    db_checkSync ();
    $newMemberId = db_getNewMemberKey();
    db_query ("INSERT INTO member (`key`, name, birth_date, male, locality_key, category_key, attend_meeting, new_locality,
        citizenship_key, baptized, admin_key, changed)
        VALUES ('$newMemberId', '$_name', '$_birth_date', '$_gender', '$_locality_key', '$_category_key', '$_attend_meeting',
                '$_locality_name', '$_citizenship', '$_baptize_date', '$_adminId', 1)");
}

function getAge($birthDate){
    $date = strtotime ($birthDate);
    $age =  (int)date("Y") - (int)strftime("%Y",$date);
    $m = (int)date("m") - (int)strftime("%m",$date);

    if ($m < 0 || ($m === 0 && (int)date("e") - (int)strftime("%e",$date))) {
        $age--;
    }
    return $age;
}

function db_getLocalityKeyByName($locality_name){
    global $db;
    $_locality_name = $db->real_escape_string($locality_name);
    $res = db_query("SELECT `key` FROM locality WHERE name = '$locality_name' LIMIT 1");

    if ($row = $res->fetch_assoc())
        return $row['key'];
    return null;
}

function db_setProfile ($adminId, $name, $birth_date, $cell_phone, $locality_key, $new_locality, $gender, $citizenship_key, $dispatch, $notice){
    global $db;
    $_adminId = $db->real_escape_string($adminId);
    $_name = $db->real_escape_string($name);
    $_birth_date = $db->real_escape_string($birth_date);
    $_cell_phone = $db->real_escape_string($cell_phone);
    $_locality_key = $db->real_escape_string($locality_key);
    $_new_locality = $db->real_escape_string($new_locality);
    $_gender = $db->real_escape_string($gender);
    $_citizenship_key = $db->real_escape_string($citizenship_key);
    $_dispatch = $db->real_escape_string($dispatch);
    $_notice = $db->real_escape_string($notice);

    $member = db_getMember($_adminId);
    $adminMember = db_getAdminAsMember($_adminId);

    $memberChanged = !db_isMemberIdChangedByAdmins($member['member_key']) || $member['name'] != $_name ||
        $member['birth_date'] != $_birth_date || $member['cell_phone'] != $_cell_phone ||
        $member['locality_key'] != $_locality_key || $member['new_locality'] != $_new_locality ||
        $member['male'] != $_gender || $member['citizenship_key'] != $_citizenship_key;

    db_query("UPDATE member SET name='$_name', birth_date='$_birth_date',"
            . " cell_phone='$_cell_phone', locality_key='$_locality_key',"
            . " new_locality='$_new_locality',"
            . " changed=".($memberChanged ? 1 : 0).", male='$_gender', citizenship_key='$_citizenship_key'"
            . " WHERE `key`='$_adminId'");

    if($adminMember['notice_info'] !== $_dispatch || $adminMember['notice_reg'] !== $_notice){
        db_query("UPDATE admin SET notice_info='$_dispatch', notice_reg='$_notice', changed=1 WHERE member_key='$_adminId'");
    }
}

function db_isMemberIdChangedByAdmins($memberId){
    global $db;
    $_memberId = $db->real_escape_string($memberId);

    if (strpos($_memberId, '99000') !== false) {
        return false;
    }
    return true;
}

function db_getMemberMain ($memberId, $eventId){
    global $db;
    $memberId = $db->real_escape_string($memberId);
    $eventId = $db->real_escape_string($eventId);

    $res=db_query ("SELECT m.key as member_key, m.name,
                CASE WHEN m.male=1 THEN 'male' WHEN m.male=0 THEN 'female' ELSE '' END as gender,
                m.male, m.birth_date, m.locality_key, m.address,
                m.category_key, m.document_key, m.document_num, m.document_date,
                m.document_auth, m.new_locality, m.citizenship_key, m.cell_phone,
                m.admin_key as mem_admin, e.name as event_name, e.key as event_key,
                e.need_passport, e.need_transport, e.need_prepayment, m.email,
                e.start_date, e.end_date, m.college_key, m.college_comment,
                IF (rg.name='--',l.name,CONCAT (l.name,', ',rg.name)) as locality_name,
                m.english, e.need_tp, e.need_flight, m.tp_num, e.private,
                m.tp_date, m.tp_auth, m.tp_name, c.key as country_key,
                m.school_start, m.school_end, m.college_start, m.college_end,
                DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365 as age,
                cl.locality_key as college_city
                FROM member as m
                INNER JOIN event e ON e.key = '$eventId'
                LEFT JOIN locality l ON m.locality_key=l.key
                LEFT JOIN region rg ON l.region_key=rg.key
                LEFT JOIN country c ON c.key=rg.country_key
                LEFT JOIN college cl ON cl.key=m.college_key
                WHERE m.key='$memberId' AND e.key='$eventId' ");

    if ($row = $res->fetch_assoc()) return $row;
    return NULL;
}

function db_rejectMemberRegistration ($adminId, $eventId){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $eventId = $db->real_escape_string($eventId);

    db_query ("UPDATE reg SET regstate_key='03' WHERE member_key='$adminId' AND event_key='$eventId'");
}

function db_getMembersForRegistration($search){
    global $db;
    $search = $db->real_escape_string($search);

    if($search == ''){
        return false;
    }

    $res = db_query("SELECT m.key as id, m.email, m.name, l.name as locality FROM member m "
            . "INNER JOIN locality l ON l.key=m.locality_key "
            . "WHERE m.name LIKE '%$search%' ORDER BY m.name ASC LIMIT 0, 3");

    $members = array();
    while ($row = $res->fetch_assoc()) $members[]=$row;
    return $members;
}

function db_removeEvent($eventId, $admin){
    global $db;
    $eventId = $db->real_escape_string($eventId);
    $admin = $db->real_escape_string($admin);

    $res = db_query("SELECT team_key FROM event WHERE `key`='$eventId'");
    $row = $res->fetch_object();
    if($res->num_rows > 0 && $row->team_key != ''){
        db_query("DELETE FROM team WHERE `key`='".$row->team_key."'");
    }

    db_query("DELETE FROM event WHERE `key`='$eventId' AND author='$admin'");
    db_query("DELETE FROM event_access WHERE `key`='$eventId' ");
    db_query("DELETE FROM reg WHERE event_key='$eventId'");
    db_query("DELETE FROM event_zones WHERE event_key='$eventId'");
    db_query("DELETE FROM message WHERE event_key='$eventId'");
}

function db_getEventToCreateNewMember ($eventId){
    global $db;
    $eventId = $db->real_escape_string($eventId);
    $res=db_query ("SELECT e.key as event_id,  e.name as event_name,
        e.archived,e.author,e.changed,e.close_registration,e.contrib,e.currency,e.end_date,e.event_type,
        e.info,e.invitation,e.is_active,e.locality_key,e.need_accom,e.need_address,e.need_flight,e.need_parking,
        e.need_passport,e.need_prepayment,e.need_service,e.need_tp,e.need_transport,e.organizer,e.participants_count,
        e.private,e.reg_message,e.regend_date,e.save_message,e.start_date,e.sync,e.team_key,e.web
                    FROM event e
                    WHERE e.key='$eventId'");

    if($res->num_rows>0) return $res->fetch_assoc();
    return null;
}

function db_getEvent ($eventId){
    global $db;
    $eventId = $db->real_escape_string($eventId);
    $res=db_query ("SELECT e.key as event_id, e.name as event_name, e.*, e.close_registration, e.participants_count,
                    GROUP_CONCAT(DISTINCT CONCAT_WS(',', m.key, m.name, m.email, l.name) ORDER BY m.key ASC SEPARATOR ';') as admins,
                    GROUP_CONCAT(DISTINCT CONCAT_WS(':', z.locality_key, z.region_key, z.country_key, lo.name, r.name, c.name) SEPARATOR ',') as zones
                    FROM event e
                    LEFT JOIN event_access a ON a.key = e.key
                    LEFT JOIN member m ON m.key=a.member_key
                    LEFT JOIN locality l ON l.key=m.locality_key
                    LEFT JOIN event_zones z ON z.event_key=e.key
                    LEFT JOIN region r ON r.key=z.region_key
                    LEFT JOIN country c ON c.key=z.country_key
                    LEFT JOIN locality lo ON lo.key=z.locality_key
                    WHERE e.key='$eventId' GROUP BY e.key");

    if($res->num_rows>0) return $res->fetch_assoc();
    return null;
}

function db_handleEvent ($name, $locality, $author, $start_date, $end_date, $reg_end_date, $passport,
    $prepayment, $private, $transport, $tp, $flight, $info, $reg_members, $reg_members_email, $team_key, $event_id,
    $event_type, $zones, $parking, $service, $accom, $close_registration, $participants_count){

    global $db;

    $_table = 'event';

    $author = $db->real_escape_string($author);
    $name = $db->real_escape_string($name);
    $locality = $db->real_escape_string($locality);
    $start_date = $db->real_escape_string($start_date);
    $end_date = $db->real_escape_string($end_date);
    $reg_end_date = $db->real_escape_string($reg_end_date);
    $passport = $db->real_escape_string($passport);
    $prepayment = $db->real_escape_string($prepayment);
    $private = $db->real_escape_string($private);
    $transport = $db->real_escape_string($transport);
    $tp = $db->real_escape_string($tp);
    $flight = $db->real_escape_string($flight);
    $info = $db->real_escape_string($info);
    $reg_members = $reg_members == '' ? null: $db->real_escape_string($reg_members);
    $reg_members_email = $reg_members_email == '' ? null : $db->real_escape_string($reg_members_email);
    $event_id = $db->real_escape_string($event_id);
    $team_key = $team_key ? $db->real_escape_string($team_key) : '';
    $event_type = $event_type ? $db->real_escape_string($event_type) : '';
    $close_registration = (int)$close_registration;
    $participants_count = (int)$participants_count;

    $zones = $zones == '' ? null: $db->real_escape_string($zones);

    $parking = $db->real_escape_string($parking);
    $service = $db->real_escape_string($service);
    $accom = $db->real_escape_string($accom);

    $adminsFromForm = explode (',', $reg_members);
    $adminsEmailFromForm = explode (',', $reg_members_email);

    $team_key = handleAdminsInTeamTable($team_key, $adminsEmailFromForm);

    if($event_id){
        db_query("UPDATE event SET name='$name', locality_key='$locality', author='$author', start_date='$start_date', end_date='$end_date', regend_date='$reg_end_date',
            info='$info', need_passport='$passport', need_transport='$transport', need_prepayment='$prepayment', private='$private',
            need_tp='$tp', need_flight='$flight', need_parking = '$parking', need_accom = '$accom', need_service = '$service',
            team_key='$team_key', event_type='$event_type', close_registration='$close_registration',
            participants_count='$participants_count', sync=0 WHERE `key`='$event_id'");
    }
    else{
        $event_id = db_getNextKeyForEvents();
        db_query("INSERT INTO event (`key`, name, locality_key, author, start_date, end_date, regend_date, info, need_passport,
            need_transport, need_prepayment, private, need_tp, need_flight, team_key, event_type, need_parking, need_accom,
            need_service, close_registration, participants_count, sync, web ) VALUES ('$event_id', '$name', '$locality', '$author',
              '$start_date', '$end_date', '$reg_end_date', '$info', '$passport', '$transport',
              '$prepayment', '$private', '$tp', '$flight', '$team_key', '$event_type', '$parking', '$accom', '$service',
              '$close_registration', '$participants_count', 0, 1) ");

        // author_name name key
        $arr = [];
        $arr ['author'] = db_getAdminNameById($author);
        $arr ['name'] = $name;
        $arr ['key'] = $event_id;


        db_sendMsgToRespOneSync(EVENT_TYPE, $arr);
    }


    addZonesForEvent($event_id, $zones, $_table);

    handleAdminsInEventAccessTable($event_id, $adminsFromForm, $_table);
}

function addZonesForEvent($event_id, $zones, $table){
    global $db;
    $_table = $table.'_zones';

    $res = db_query("SELECT * FROM $_table WHERE event_key='$event_id'");

    $existZones = [];
    while ($row = $res->fetch_assoc()) {
        if($row['locality_key']){
            $existZones[] = $row['locality_key'];
        }
        else if($row['region_key']){
            $existZones[] = $row['region_key'];
        }
        else if($row['country_key']){
            $existZones[] = $row['country_key'];
        }
    }

    $zonesInfo = $zones ? explode(',', $zones) : [];

    $zonesArr = [];
    if(count($zonesInfo)>0){
        foreach ($zonesInfo as $zone){
            $z = explode(':', $zone);
            $zonesArr[] = $z[1];

            if(count($existZones) == 0 || !in_array($z[1], $existZones)){
                $stmt = $db->prepare("INSERT INTO $_table (event_key, country_key, region_key, locality_key) VALUES ('$event_id', ?, ?, ?)");

                $field1 = $z[0] == 'c' ? $z[1] : null;
                $field2 = $z[0] == 'r' ? $z[1] : null;
                $field3 = $z[0] == 'l' ? $z[1] : null;

                $stmt->bind_param('sss', $field1, $field2, $field3);
                $stmt->execute();
            }
        }
    }

    if(count($existZones)>0){
        if(count($zonesArr) > 0){
            foreach ($existZones as $value) {
                if(!in_array($value, $zonesArr)){
                    db_query("DELETE FROM $_table WHERE event_key='$event_id' AND (locality_key='$value' OR region_key='$value' OR country_key='$value')");
                }
            }
        }
        else{
            db_query("DELETE FROM $_table WHERE event_key='$event_id'");
        }
    }
}

function db_getAdminNameById($adminId){
    global $db;
    $adminId = $db->real_escape_string($adminId);

    $res = db_query("SELECT name FROM member WHERE `key`='$adminId'");
    if($row = $res->fetch_object()){
        return $row->name;
    }
    return null;
}

function db_sendMessageNewMember($userEmail, $name){
    global $db;
    global $appRootPath;

    $_name = $db->real_escape_string($name);

    $domain = $_SERVER['HTTP_HOST'];
    $title = 'Создание учётной записи на сайте регистрации reg-page.ru';

    $body = "<p>$_name, ваша учётная запись на сайте reg-page.ru успешно создана. Чтобы войти в учётную запись, перейдите <a href='{$appRootPath}'login?email=$userEmail'>по этой ссылке</a>.</p>".
    "<br/>Команда сайта регистрации.";

    EMAILS::sendEmail($userEmail, $title, $body);
}

function db_sendMsgToRespOneSync($type='', $data){
    if($type != ''){

        switch ($type){

            case EVENT_TYPE:
                $title = "Создание нового мероприятия на сайте регистрации reg-page.ru";
                $body = "На сайте reg-page.ru создано мероприятие - '". $data['name']."' [".$data['key']."]. Автор: ".$data['author'];
                break;
            case USER_TYPE:
                $title = "Создание новой учетной записи на сайте регистрации reg-page.ru";
                $body = "На сайте reg-page.ru создана карточка участника [". $data['name']." ".$data['key']."]. Автор: ".$data['author'];
                break;
            case COLLEGE_TYPE:
                $title = "Создание нового вуза на сайте регистрации reg-page.ru";
                $body = "На сайте reg-page.ru создан ВУЗ - '".$data['name']."' [".$data['short_name']."]. Автор: ".$data['author'];
                break;
        }
        // $headers = "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: REG-PAGE<info@reg-page.ru>\r\nReply-To: REG-PAGE<info@reg-page.ru>\r\n";
        $email = db_getEmailRespOneSync();
        if($email){
            $emailArr = explode(',', $email);
            foreach ($emailArr as $value) {
                EMAILS::sendEmail($value, $title, $body, 'info@reg-page.ru');
            }
        }
    }
}

function db_getEmailRespOneSync (){
    $res = db_query("SELECT value FROM param WHERE name='sync_email'");

    $row = $res->fetch_object();
    return $res ? $row->value : null;
}

function db_getNextKeyForEvents(){
    $res=db_query ("SELECT `key` FROM event WHERE `key` LIKE '99%' ORDER BY `key` DESC LIMIT 1");
    $row = $res->fetch_object();
    $key = "99000000";
    if ($row && strlen($row->key)==8) $key = (string)($row->key + 1);
    return $key;
}

function getNextTeamKey(){
    $resTeam = db_query("SELECT `key` as team_key FROM team ORDER BY `key` DESC LIMIT 1");
    $rowTeam = $resTeam->fetch_object();
    $keyTeam = $rowTeam->team_key + 1;
    $team_key = $keyTeam < 10 ? '0'.$keyTeam : $keyTeam;
    return $team_key;
}

function handleAdminsInEventAccessTable($event_id, $admins, $table){
    // handle a event_access table to add or remove admins responseble for registration
    $_table = $table.'_access';

    $res = db_query("SELECT member_key FROM $_table WHERE `key`='$event_id'");
    $adminsFromDB = array();
    while($row = $res->fetch_assoc()) $adminsFromDB[]=$row['member_key'];
    if($admins[0] != ''){
        foreach ($admins as $a){
            if(count($adminsFromDB)==0 || !in_array($a, $adminsFromDB)){
                db_query("INSERT INTO $_table (`key`, member_key) VALUES ('$event_id', '$a')");
            }
        }
    }

    if(count($adminsFromDB)>0){
        foreach ($adminsFromDB as $a){
            if($admins[0] == '' || !in_array($a, $admins)){
                db_query("DELETE FROM $_table WHERE member_key='$a'");
            }
        }
    }
}

function handleAdminsInTeamTable($team_key, $admins){
    $tableHasAdmins = $team_key != '';

    if($admins[0] != '' && $team_key == ''){
        $team_key = getNextTeamKey();
    }

    if($admins[0] != ''){
        !$tableHasAdmins ?
                db_query("INSERT INTO team (`key`, email) VALUES ('$team_key', '". implode(',', $admins)."')") :
                db_query("UPDATE team SET email='". implode(',', $admins)."' WHERE `key`='$team_key'");
    }
    else if($admins[0] == ''){
        db_query("DELETE FROM team WHERE `key`='$team_key'");
    }

    return $admins[0] == '' ? '' : $team_key;
}

function db_getEventsForEventsPage($adminId, $sort_type, $sort_field){
    global $db;
    //$active_event = (int)$active_event == 1 ? ' AND e.is_active=1 ' : '';
    $sort_field = str_replace(' ', '', $db->real_escape_string($sort_field));
    $sort_type = str_replace(' ', '', $db->real_escape_string($sort_type));
    $adminId = str_replace(' ', '',$db->real_escape_string($adminId));

    $request= $adminId == "" ? "  AND e.is_active=1 " : "";

    // first request is by locality
    // second request is by admin access if zones more than access
    // third request is by admin acees if zones less than access
    // forth request is by zones (available only events are not restricted by zones)

    $res=db_query ("SELECT DISTINCT * FROM (
                    SELECT e.key as id, e.name as name, e.need_passport, e.need_transport, e.close_registration,
                    e.participants_count,
                    e.need_prepayment, e.start_date, e.end_date, e.need_flight, e.need_tp, e.regend_date, e.info, e.private,
                    e.locality_key, e.author, l.name as locality_name, e.is_active, e.archived, re.regstate_key
                    FROM event e
                    LEFT JOIN reg re ON re.event_key=e.key AND re.member_key='$adminId'
                    LEFT JOIN locality l ON l.key=e.locality_key
                    LEFT JOIN event_zones z ON z.event_key=e.key
                    LEFT JOIN country c ON c.key = z.country_key
                    LEFT JOIN region r ON r.key = z.region_key or c.key=r.country_key
                    INNER JOIN locality lo ON lo.key = z.locality_key or lo.region_key = r.key
                    INNER JOIN member m ON m.locality_key = lo.key
                    WHERE m.key='$adminId' $request

                    UNION

                    SELECT e.key as id, e.name as name, e.need_passport, e.need_transport, e.close_registration,
                    e.participants_count,
                    e.need_prepayment, e.start_date, e.end_date, e.need_flight, e.need_tp, e.regend_date, e.info, e.private,
                    e.locality_key, e.author, l.name as locality_name, e.is_active, e.archived, re.regstate_key
                    FROM event e
                    LEFT JOIN reg re ON re.event_key=e.key AND re.member_key='$adminId'
                    LEFT JOIN locality l ON l.key=e.locality_key
                    LEFT JOIN event_zones z ON z.event_key=e.key
                    LEFT JOIN country c ON c.key = z.country_key
                    LEFT JOIN region r ON r.key = z.region_key or c.key=r.country_key
                    INNER JOIN locality lo ON lo.key = z.locality_key or lo.region_key = r.key
                    INNER JOIN access a ON a.country_key=c.key or a.region_key=r.key or a.locality_key = lo.key
                    WHERE a.member_key='$adminId' $request

                    UNION

                    SELECT DISTINCT e.key as id, e.name as name, e.need_passport, e.need_transport, e.close_registration,
                    e.participants_count,
                    e.need_prepayment, e.start_date, e.end_date, e.need_flight, e.need_tp, e.regend_date, e.info, e.private,
                    e.locality_key, e.author, l.name as locality_name, e.is_active, e.archived, re.regstate_key
                    FROM event e
                    LEFT JOIN reg re ON re.event_key=e.key AND re.member_key='$adminId'
                    LEFT JOIN locality l ON l.key=e.locality_key
                    INNER JOIN access a ON a.member_key='$adminId'
                    INNER JOIN country c ON c.key = a.country_key
                    INNER JOIN region r ON r.key = a.region_key or c.key=r.country_key
                    INNER JOIN locality lo ON lo.key = a.locality_key or lo.region_key = r.key
                    INNER JOIN event_zones z ON z.event_key=e.key AND (z.country_key=c.key or z.region_key=r.key or z.locality_key=lo.key)
                    WHERE 1 $request

                    UNION

                    SELECT e.key as id, e.name as name, e.need_passport, e.need_transport, e.close_registration,
                    e.participants_count,
                    e.need_prepayment, e.start_date, e.end_date, e.need_flight, e.need_tp, e.regend_date, e.info, e.private,
                    e.locality_key, e.author, l.name as locality_name, e.is_active, e.archived, re.regstate_key
                    FROM event e
                    LEFT JOIN reg re ON re.event_key=e.key AND re.member_key='$adminId'
                    LEFT JOIN locality l ON l.key=e.locality_key
                    WHERE ((SELECT COUNT(*) FROM event_zones ez WHERE ez.event_key=e.key) = 0 OR e.author='$adminId') $request
                    ) q ORDER BY $sort_field $sort_type ");

    $events = array ();
    while ($row = $res->fetch_object()) $events[]=$row;
    return $events;
}

function db_getEventsLocalities(){
    $res = db_query("SELECT DISTINCT l.key, l.name FROM event e INNER JOIN locality l ON l.key=e.locality_key");

    if($res->num_rows > 0){
        $localities = array();
        while($row = $res->fetch_assoc()) $localities[]=$row;
        return $localities;
    }
    return null;
}

function db_getEventsAuthors(){
    $res = db_query("SELECT DISTINCT m.key, m.name FROM event e INNER JOIN member m ON e.author=m.key");

    if($res->num_rows > 0){
        $authors = array();
        while($row = $res->fetch_assoc()) $authors[]=$row;
        return $authors;
    }
    return null;
}

function  db_hasRightToHandleEvents($memberId){
    global $db;
    $memberId = $db->real_escape_string($memberId);

    $res = db_query("SELECT event FROM admin WHERE member_key='$memberId'");

    if($res->num_rows > 0){
        $row= $res->fetch_object();
        if($row->event == 1)
            return true;
    }
    return false;
}

function db_setEventActivity($eventId, $isActive, $adminId){
    global $db;
    $eventId = $db->real_escape_string($eventId);

    db_query("UPDATE event SET is_active=".($db->real_escape_string($isActive) == 'true' ? 1 : 0)." WHERE `key`='$eventId' AND author='$adminId'");
}

function getEventTypes(){
    $res = db_query("SELECT * FROM event_type");

    $types = array();
    while($row = $res->fetch_assoc()) $types[$row['key']]=$row['name'];
    return $types;
}

function db_setEventArchive($eventId, $adminId){
    global $db;
    $eventId = $db->real_escape_string($eventId);
    $adminId = $db->real_escape_string($adminId);

    $res = db_query("SELECT COUNT(*) as count FROM reg WHERE member_key LIKE '990%' and event_key='$eventId' ");
    $row = $res->fetch_assoc();

    if($row['count'] > 0){
        return false;
    }
    else{
        db_query('SET Session group_concat_max_len=20000');

        $res = db_query("SELECT e.event_type, e.name, e.start_date, e.end_date, e.locality_key, e.author,
                GROUP_CONCAT(r.member_key) as members,
                (SELECT GROUP_CONCAT(re.member_key) FROM reg re WHERE re.event_key=e.key AND re.coord<>0 AND re.attended=1) as coordinators,
                (SELECT GROUP_CONCAT(CONCAT_WS(':',re.service_key, re.member_key)) FROM reg re WHERE re.event_key=e.key AND re.service_key IS NOT NULL AND re.service_key<>'' AND re.attended=1) as service_key,
                (SELECT GROUP_CONCAT(re.member_key) FROM reg re WHERE re.event_key=e.key AND re.service_key IS NOT NULL AND re.service_key<>'' AND re.attended=1) as service_ones
                FROM event e
                INNER JOIN reg r ON r.event_key=e.key
                WHERE e.key='$eventId' AND e.author='$adminId' AND r.attended=1 GROUP BY e.key");

        $archive = NULL;
        while($row = $res->fetch_assoc()) $archive = $row;

        // set different info into event_archive.props field
        if($archive !== NULL){
            db_query("INSERT INTO event_archive
                (event_type, name, created, locality_key, start_date, end_date, author, members, coordinators, service_key, service_ones, members_count)
                VALUES ('".$archive['event_type']."', '".(explode('(', $archive['name'])[0])."', NOW(), '".$archive['locality_key']."',
                '".$archive['start_date']."', '".$archive['end_date']."', '".$archive['author']."',
                '".$archive['members']."', '".(count(explode(',',$archive['members'])))."')");

            db_query("UPDATE event SET archived=1 WHERE `key`='$eventId'");
            db_query("DELETE FROM message WHERE event_key='$eventId'");
            return true;
        }
    }
}

function db_getZonesForEvent($text, $field){
    global $db;
    $text = $db->real_escape_string($text);
    $field = $db->real_escape_string($field);

    if($text == ''){
        return false;
    }

    $res = db_query("SELECT DISTINCT $field.name, $field.key as id FROM country c
                    LEFT JOIN region r ON c.key=r.country_key
                    LEFT JOIN locality l ON l.region_key = r.key
                    WHERE $field.name LIKE '%$text%' LIMIT 0, 3");

    $zones = array();
    while ($row = $res->fetch_assoc()) $zones[]=$row;
    return $zones;
}

function db_getUserByLogin($login){
    global $db;
    $login = $db->real_escape_string($login);
    $res=db_query ("SELECT password, member_key, login FROM admin WHERE login='$login'");

    if ($row = $res->fetch_assoc()) return $row;
    return NULL;
}

function db_setUserPassword($adminId, $password){
    global $db;
    $password = $db->real_escape_string($password);
    $adminId = $db->real_escape_string($adminId);

    db_query("UPDATE admin SET password='$password', changed=1 WHERE member_key='$adminId'");
}

function db_setUserPasswordByLogin($login, $password){
    global $db;
    $password = $db->real_escape_string($password);
    $login = $db->real_escape_string($login);

    db_query("UPDATE admin SET password='$password', changed=1 WHERE login='$login'");
}

function db_loginUserByLogin($sessionId, $login){
    global $db;
    $sessionId = $db->real_escape_string($sessionId);
    $login = $db->real_escape_string($login);
    db_query ("UPDATE admin SET session='$sessionId' WHERE login='$login'");
}

function db_isAdminExist ($login)
{
    global $db;
    $login = $db->real_escape_string($login);
    $res=db_query ("SELECT * FROM admin WHERE login='$login'");
    return $res->num_rows>0 ? true : false;
}

function db_isAuthorizedMember($memberId){
    global $db;
    $memberId = $db->real_escape_string($memberId);

    $res=db_query ("SELECT * FROM admin WHERE member_key='$memberId'");
    return $res->num_rows>0 ? true : false;
}

function db_getGuestEvent ($eventId){
    global $db;
    $eventId = $db->real_escape_string($eventId);
    $res=db_query ("SELECT e.key as event_id, e.name as event_name, e.need_passport, e.need_transport,
        IF((SELECT COUNT(*) FROM reg rg WHERE rg.event_key=e.key AND (rg.regstate_key = '01' OR rg.regstate_key = '02' OR rg.regstate_key = '04' OR rg.regstate_key is NULL ) ) >= e.participants_count AND e.participants_count > 0, 1, 0) as stop_registration,
        e.close_registration, e.need_prepayment, e.start_date, e.end_date, e.need_flight, e.need_tp, e.regend_date, e.info, e.private,
        e.author, e.team_key, e.event_type, e.organizer, e.need_parking, e.need_accom, e.need_service
        FROM event e
        WHERE e.key='$eventId'");

    if($res->num_rows>0) return $res->fetch_assoc();
    return null;
}

$selectEventMemberInfo = "SELECT m.key as member_key, m.name, CASE WHEN m.male=1 THEN 'male' WHEN m.male=0 THEN 'female' ELSE '' END as gender, m.male,
                      m.birth_date, m.locality_key, m.address, m.cell_phone, m.email,
                      m.category_key, m.document_key,
                      m.document_num, m.document_date, m.document_auth, m.new_locality, m.citizenship_key,
                      m.admin_key as mem_admin,
                      IF (rg.name='--',l.name,CONCAT (l.name,', ',rg.name)) as locality_name,
                      m.english, m.tp_num, m.school_comment,
                      m.tp_date, m.tp_auth, m.tp_name,m.russian_lg, m.baptized, c.key as country_key,
                      m.school_start, m.school_end, m.college_start, m.college_end, m.college_key, m.college_comment,
                      DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365 as age, cl.locality_key as college_city
                      FROM member as m
                      LEFT JOIN locality l ON m.locality_key=l.key
                      LEFT JOIN region rg ON l.region_key=rg.key
                      LEFT JOIN country c ON c.key=rg.country_key
                      LEFT JOIN college cl ON cl.key=m.college_key";

function db_getEventMemberInfo ($memberId)
{
    global $db, $selectEventMemberInfo;
    $memberId = $db->real_escape_string($memberId);

    $res=db_query ("$selectEventMemberInfo WHERE m.key='$memberId' ");

    if ($row = $res->fetch_assoc()) return $row;
    return NULL;
}

function db_getLocalityByKey($locality_key){
    global $db;
    $locality = $db->real_escape_string($locality_key);
    $res = db_query("SELECT name FROM locality WHERE `key`='$locality'");

    if ($row = $res->fetch_object()){
        return $row->name;
    }
    return null;
}

function db_getAdminByLocality($locality_key){
    global $db;
    $locality = $db->real_escape_string($locality_key);

    $res = db_query ("
        SELECT DISTINCT m.name, a.login as email FROM member as m
        LEFT JOIN admin a ON a.member_key = m.key
        LEFT JOIN access ac ON ac.member_key = m.key
        LEFT JOIN country c ON c.key = ac.country_key
        LEFT JOIN region r ON r.country_key = c.key
        LEFT JOIN locality l ON l.region_key = r.key
        WHERE l.key='$locality' AND a.role>1
        UNION
        SELECT DISTINCT m.name, a.login as email FROM member as m
        LEFT JOIN admin a ON a.member_key = m.key
        LEFT JOIN access ac ON ac.member_key = m.key
        LEFT JOIN region r ON r.key = ac.region_key
        LEFT JOIN locality l ON l.region_key = r.key
        WHERE l.key='$locality' AND a.role>1
        UNION
        SELECT DISTINCT m.name, a.login as email FROM member as m
        LEFT JOIN admin a ON a.member_key = m.key
        LEFT JOIN access ac ON ac.member_key = m.key
        LEFT JOIN locality l ON ac.locality_key = l.key
        WHERE l.key='$locality' AND a.role>1 ");

    $admins = [];
    while ($row = $res->fetch_assoc()){
        $admins [] = $row;
    }
    return count ($admins) ? $admins : null;
}

function db_sendMessagesToMembersAdmins($eventId, $name, $locality){
    global $appRootPath;

    $domain = $_SERVER['HTTP_HOST'];
    $userLocality = db_getLocalityByKey($locality);
    $eventName = db_getEventInfoForAdmins($eventId);
    $admins = db_getAdminByLocality($locality);

    $title = 'Сообщение с сайта регистрации reg-page.ru';
    $headers = "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: REG-PAGE<info@reg-page.ru>\r\nReply-To: REG-PAGE<info@reg-page.ru>\r\n";

    foreach($admins as $admin){
        $adminEmail = $admin['email'];
        $body = "Здравствуйте, ". $admin['name']."!".
        "<p>Пользователь ". ( strlen($name) > 0 ? $name : $adminEmail)." из ". $userLocality .", отправил свои данные для участия в ". (strlen($eventName)>0 ? $eventName : "мероприятии")."</p>".
        "<p>Вы можете перейти по <a href='{$appRootPath}'login?email=$adminEmail'>ссылке</a> чтобы просмотреть данные, отправленные пользователем и принять решение о его регистрации на мероприятие.</p>".
        "<p>Такое же письмо, возможно, также было отправлено и другим администраторам, ответственным за регистрацию в этой местности.</p>".
        "<br/>Команда сайта регистрации.";

        //EMAILS::sendEmail($adminEmail, $title, $body, 'REG-PAGE<info@reg-page.ru>');
        //mail($adminEmail, $title, $body, $headers);
    }
}

function db_checkIfUserAddedToReg($eventId, $memberId){
    global $db;
    $_eventId = $db->real_escape_string($eventId);
    $_memberId = $db->real_escape_string($memberId);

    $res = db_query("SELECT * FROM reg WHERE member_key='$_memberId' AND event_key='$_eventId'");

    return $res->num_rows > 0;
}

function db_getEventName($eventId){
    global $db;
    $eventId = $db->real_escape_string($eventId);
    $res=db_query ("SELECT name FROM event WHERE `key`='$eventId'");
    $row = $res->fetch_assoc();
    return $row ? $row['name'] : "";
}

// ARCHIVE

function db_getArchiveEvents($sort_type, $sort_field, $startDate, $endDate){
    global $db;
    $startDate = $db->real_escape_string($startDate);
    $endDate = $db->real_escape_string($endDate);
    $sort_type = $db->real_escape_string($sort_type);
    $sort_field = $sort_field != 'locality_key' ? $db->real_escape_string($sort_field)."" : " locality_name ";

    $res = db_query("SELECT ea.id, ea.name, l.key as locality_key, l.name as locality_name,
                ea.event_type as event_type, ea.members_count, ea.start_date, ea.created as date_created
                FROM event_archive ea
                INNER JOIN locality l ON l.key = ea.locality_key
                ORDER BY $sort_field $sort_type");

    $events = array();
    while($row = $res->fetch_assoc()) $events[] = $row;
    return $events;
}

function db_getArchiveGeneralEvents($adminId, $sort_type, $sort_field, $localityFilter, $meetingTypeFilter, $startDate, $endDate){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $localityFilter = $db->real_escape_string($localityFilter);
    $meetingTypeFilter = $db->real_escape_string($meetingTypeFilter);
    $startDate = $db->real_escape_string($startDate);
    $endDate = $db->real_escape_string($endDate);
    $sort_type = $db->real_escape_string($sort_type);
    $sort_field = $sort_field != 'locality_key' ? $db->real_escape_string($sort_field)."" : " locality_name ";

    $events = array ();

    $res = db_query("SELECT ea.name, ea.start_date, ea.created as date_created,
                ea.event_type as event_type, GROUP_CONCAT(CONCAT_WS(':', ea.members_count, ea.start_date) ORDER BY ea.start_date ASC SEPARATOR  ',') as members_count
                FROM event_archive ea
                INNER JOIN locality l ON l.key = ea.locality_key GROUP BY ea.event_type");

    while ($row = $res->fetch_object()) $events[]=$row;
    return $events;
}

function db_getEventTypes(){
    $res = db_query("SELECT * FROM event_type");

    $types = array ();
    while ($row = $res->fetch_assoc()) $types[$row['key']]=$row['name'];
    return $types;
}

function db_getArchivedEventLocalities(){
    $res = db_query("SELECT l.key, l.name FROM event_archive ea INNER JOIN locality l ON l.key=ea.locality_key");

    $localities = array ();
    while ($row = $res->fetch_assoc()) $localities[$row['key']]=$row['name'];
    return $localities;
}

function db_getArchiveEventList($adminId, $eventId){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $eventId = $db->real_escape_string($eventId);
    db_query('SET Session group_concat_max_len=20000');

    // CASE WHEN FIND_IN_SET(m.key, ea.coordinators)<>0 THEN 1 ELSE 0 END as coord,
    // CASE WHEN FIND_IN_SET(m.key, ea.service_ones)<>0 THEN ea.service_key ELSE 0 END as service

    $res=db_query ("SELECT DISTINCT m.key as id, m.name as name, m.category_key, DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365 as age, l.name as locality
                    FROM event_archive ea
                    INNER JOIN member m ON FIND_IN_SET(m.key, ea.members)<>0
                    LEFT JOIN locality l ON l.key = m.locality_key
                    WHERE ea.id='$eventId' ORDER BY name ASC");

    $list = array();
    while($row = $res->fetch_assoc()) $list[]=$row;
    return $list;
}

function getEventArchiveMembersStatistic($adminId, $eventType, $startDate, $endDate, $text){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $eventRequest = $eventType == '_all_' ? '' : " AND ea.event_type='".$db->real_escape_string($eventType)."'";
    $startDate = $db->real_escape_string($startDate);
    $endDate = $db->real_escape_string($endDate);
    $requestDates = " AND (ea.start_date BETWEEN '$startDate' AND '$endDate')";
    $searchText = $text !='' ? "AND ( m.name LIKE '%".$db->real_escape_string($text)."%' OR l.name LIKE '%".$db->real_escape_string($text)."%' ) " : '';
    db_query('SET Session group_concat_max_len=20000');

    //COALESCE( GROUP_CONCAT(CONCAT_WS(':', ea.event_type, ea.start_date) ORDER BY ea.start_date ASC SEPARATOR  ','), '') as event,
    /*
    UNION
    SELECT m.key as member_key, m.name as name, l.name as locality_name, m.locality_key as locality_key,
    COALESCE( substring_index(GROUP_CONCAT(CONCAT_WS(':', ea.event_type, ea.start_date) ORDER BY ea.start_date ASC SEPARATOR  ','), ',', 20), '') as event,
    (SELECT COUNT(*) FROM member mb WHERE mb.locality_key=l.key) as general_count
    FROM access a
    LEFT JOIN country c ON c.key = a.country_key
    LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
    INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
    INNER JOIN district d ON d.district=l.key
    INNER JOIN member m ON m.locality_key = l.key
    INNER JOIN event_archive ea ON FIND_IN_SET(m.key, ea.members)<>0
    WHERE a.member_key='$adminId' ".($locality == '_all_' ? '' : " AND (l.key='$locality' OR d.locality_key='$locality' )")." GROUP BY m.key

     */

    $res=db_query ("SELECT m.key as member_key, m.name as name, l.name as locality_name, m.locality_key as locality_key,
                    COALESCE( substring_index(GROUP_CONCAT(CONCAT_WS(':', ea.event_type, ea.start_date) ORDER BY ea.start_date ASC SEPARATOR  ','), ',', 20), '') as event,
                    (SELECT COUNT(*) FROM member mb WHERE mb.locality_key=l.key) as general_count
                    FROM access a
                    LEFT JOIN country c ON c.key = a.country_key
                    LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
                    INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
                    INNER JOIN member m ON m.locality_key = l.key
                    INNER JOIN event_archive ea ON FIND_IN_SET(m.key, ea.members)<>0
                    WHERE a.member_key='$adminId' $eventRequest $searchText $requestDates GROUP BY m.key ORDER BY m.name ASC");

    $list = array ();
    while ($row = $res->fetch_object()) $list[]=$row;
    return $list;
}

function db_isSingleCityArchiveEvent(){
    $res = db_query("SELECT DISTINCT e.locality_key FROM event_archive e");

    $list = array ();
    while ($row = $res->fetch_assoc()) $list[]=$row;

    return count($list) < 2;
}

function db_saveMessageInfo($subject, $eventId, $receiver, $sender, $body){
    global $db;
    $_subject = $db->real_escape_string($subject);
    $_eventId = $db->real_escape_string($eventId);
    $_receiver = $db->real_escape_string($receiver);
    $_sender = $db->real_escape_string($sender);
    $_body = $db->real_escape_string($body);

    $memberId = db_getMemberByEmail($_receiver);
    if($memberId){
        db_query("INSERT INTO message (date, subject, event_key, receiver, sender, body) VALUES (NOW(), '$_subject', '$_eventId', '$memberId', '$_sender', '$_body')");
    }
}

function db_getMemberByEmail($email){
    global $db;
    $_email = $db->real_escape_string($email);

    $res = db_query("SELECT m.key as id FROM member m WHERE m.email='$_email'");

    $emails = array();
    if($res->num_rows>0){
        while($row = $res->fetch_assoc()) {
            $emails[] = $row['id'];
        }
        return count($emails) > 0 ? $emails[0] : null;
    }
    return null;
}

function db_getUserEventEmails($memberId, $eventId){
    global $db;
    $_memberId = $db->real_escape_string($memberId);
    $_eventId = $db->real_escape_string($eventId);

    $res = db_query("SELECT * FROM message WHERE event_key='$_eventId' AND receiver='$_memberId'");

    $emails = array();
    if($res->num_rows>0){
        while($row = $res->fetch_assoc()) {
            $emails[$row['id']] = $row;
        }
        return count($emails) > 0 ? $emails : null;
    }
    return null;
}

function checkIfEventHasParticipants($eventId){
    global $db;
    $_eventId = $db->real_escape_string($eventId);

    $res = db_query("SELECT * FROM reg WHERE event_key='$_eventId' ");

    if($res->num_rows>0){
        return true;
    }
    return false;
}

function db_isPasswordValid($adminId, $pass){
    global $db;
    $_adminId = $db->real_escape_string($adminId);
    $_pass = $db->real_escape_string($pass);

    $res = db_query("SELECT * FROM admin WHERE member_key='$_adminId' AND password='$_pass' ");

    if($res->num_rows>0){
        return true;
    }
    return false;
}

function db_setMemberLogin($adminId, $login){
    global $db;
    $_adminId = $db->real_escape_string($adminId);
    $_login = $db->real_escape_string($login);

    db_query("UPDATE admin SET login='$_login', changed=1 WHERE member_key='$_adminId' ");
    db_query("UPDATE member SET email='$_login', changed=1 WHERE `key`='$_adminId' ");
}

function db_getMemberById($memberId){
    global $db;
    $_memberId = $db->real_escape_string($memberId);

    $res = db_query("SELECT password, login FROM admin WHERE member_key='$_memberId' ");

    if($res->num_rows>0){
        $row = $res->fetch_assoc();
        return $row;
    }
    return false;
}

function db_getEventNeedAddress($eventId){
    global $db;
    $_eventId = $db->real_escape_string($eventId);

    $res = db_query("SELECT need_address FROM event WHERE `key`='$_eventId' ");

    if($res->num_rows>0){
        $row = $res->fetch_assoc();
        return (int)$row['need_address'] > 0;
    }
    return false;
}

function db_removeMeeting($meetingId){
    global $db;
    $_meetingId = $db->real_escape_string($meetingId);

    db_query("DELETE FROM  meetings WHERE id='$_meetingId' ");
}

function db_checkIfEventMemberFieldsHasDifference($adminId, $_dataFields, $memberId, $eventId){
    global $db;
    $_adminId = $db->real_escape_string($adminId);
    //$_dataFields = $db->real_escape_string($dataFields);
    $_memberId = $db->real_escape_string($memberId);
    $_eventId = $db->real_escape_string($eventId);

    $eventMember = db_getEventMember($_memberId, $_eventId);
    $regChanged = false;
    $memChanged = false;

    $regChanged =
            $_dataFields["arr_date"] != ($eventMember["arr_date"] == null ? '' : $eventMember["arr_date"]) ||
            $_dataFields["arr_time"] != ($eventMember["arr_time"] == null ? '' : substr($eventMember["arr_time"], 0, 5)) ||
            //$_dataFields["comment"] != $eventMember[$regCommentField] ||
            $_dataFields["dep_date"] != ($eventMember["dep_date"] == null ? '' : $eventMember["dep_date"]) ||
            $_dataFields["dep_time"] != ($eventMember["dep_time"] == null ? '' : substr($eventMember["dep_time"], 0, 5)) ||
            $_dataFields["status_key"] != $eventMember["status_key"] ||
            $_dataFields["mate_key"] != ($eventMember["mate_key"] == null ? '' : $eventMember["mate_key"]) ||
            $_dataFields["accom"] != ($eventMember["accom"] == null ? '' : $eventMember["accom"]) ||
            $_dataFields["parking"] != ($eventMember["parking"] == null ? '' : $eventMember["parking"]) ||
            $_dataFields["transport"] != ($eventMember["transport"] == null ? '' : $eventMember["transport"]) ||
            //$_dataFields["temp_phone"] != $eventMember["temp_phone"] ||
            //$_dataFields["prepaid"] != $eventMember["prepaid"] || false
            $_dataFields["currency"] != $eventMember["currency"] ||
            $_dataFields["service_key"] != ($eventMember["service_key"] == null ? '' : $eventMember["service_key"]) ||
            $_dataFields["coord"] != $eventMember["coord"] ||
            $_dataFields["flight_num_arr"] != ($eventMember["flight_num_arr"] == null ? '' : $eventMember["flight_num_arr"]) ||
            $_dataFields["flight_num_dep"] != ($eventMember["flight_num_dep"] == null ? '' : $eventMember["flight_num_dep"]) ||
            $_dataFields["note"] != ($eventMember["note"] == null ? '' : $eventMember["note"]) ||
            $_dataFields["aid"] != $eventMember['aid'] ||
            $_dataFields["contr_amount"] != $eventMember['contr_amount'] ||
            $_dataFields["trans_amount"] != $eventMember['trans_amount'] ||
            $_dataFields["fellowship"] != ($eventMember['fellowship'] == null ? '0' : $eventMember["fellowship"]) ||
            $_dataFields["visa"] != $eventMember['visa'];

    $memChanged =
            ($_dataFields["name"] != $eventMember["name"]) ||
            ($_dataFields["address"] != $eventMember["address"]) ||
            ($_dataFields["birth_date"] != $eventMember["birth_date"]) ||
            ($_dataFields["cell_phone"] != $eventMember["cell_phone"]) ||
            ($_dataFields["email"] != $eventMember["email"]) ||
            //($_dataFields["home_phone"] != $eventMember["home_phone"]) ||

            ($_dataFields["locality_key"] != $eventMember["locality_key"]) ||
            ($_dataFields["new_locality"] != ($eventMember["new_locality"] == null ? '' : $eventMember["new_locality"])) ||

            ($_eventId && db_getNeedPassport($_eventId) &&
            ($_dataFields["document_num"] != $eventMember["document_num"]) ||
            ($_dataFields["document_date"] != $eventMember["document_date"]) ||
            ($_dataFields["document_auth"] != $eventMember["document_auth"]) ||
            ($_dataFields["category_key"] != $eventMember["category_key"]) ||
            ($_dataFields["document_key"] != $eventMember["document_key"])) ||

            (($_dataFields["gender"] == "male" ? "1" : "0" ) != $eventMember["male"]) ||
            ($_dataFields["citizenship_key"] != $eventMember["citizenship_key"]) ||
            //($_dataFields["comment"] != $eventMember['admin_comment']) ||

            ($_eventId && db_getNeedPassportTp($_eventId) &&
            ($_dataFields["tp_num"] != ($eventMember["tp_num"] == null ? '' : $eventMember["tp_num"])   ||
            $_dataFields["tp_date"] != ($eventMember["tp_date"] == null ? '' : $eventMember["tp_date"]) ||
            $_dataFields["tp_auth"] != ($eventMember["tp_auth"] == null ? '' : $eventMember["tp_auth"]) ||
            $_dataFields["tp_name"] != ($eventMember["tp_name"] == null ? '' : $eventMember["tp_name"]) ||
            $_dataFields["english_level"] != $eventMember["english"])) ||

            (!$_eventId &&
            ($_dataFields["school_start"] != $eventMember["school_start"] ||
            $_dataFields["school_end"] != $eventMember["school_end"] ||
            $_dataFields["college_start"] != $eventMember["college_start"] ||
            $_dataFields["college_end"] != $eventMember["college_end"] ||
            $_dataFields["college"] != $eventMember["college_key"] ||
            $_dataFields["college_comment"] != $eventMember['college_comment'] ||
            $_dataFields["school_comment"] != $eventMember['school_comment'])) ||

            ($_dataFields["russian_lg"] != $eventMember['russian_lg']) ||
            (!$_eventId && $_dataFields["baptized"] != ($eventMember['baptized'] == null ? '' : $eventMember["baptized"]));

    return $memChanged || $regChanged;
}

function db_getMembersToInvite($search){
    global $db;
    $search = $db->real_escape_string($search);

    if($search == ''){
        return false;
    }

    $res = db_query("SELECT m.key as id, m.email, m.name, l.name as locality FROM member m "
            . "INNER JOIN locality l ON l.key=m.locality_key "
            . "WHERE m.name LIKE '%$search%' ORDER BY m.name ASC LIMIT 0, 3");

    $members = array();
    while ($row = $res->fetch_assoc()) $members[]=$row;
    return $members;
}

function db_inviteUsersToEvent($showAdminName, $adminId, $members, $eventId){
    global $db;
    $_members = $db->real_escape_string($members);
    $_eventId = $db->real_escape_string($eventId);
    $_adminId = $db->real_escape_string($adminId);
    $_showAdminName = $db->real_escape_string($showAdminName);

    $alreadyAddedArr = array();
    $emptyEmailArr = array();
    $sendEmailsArr = array();
    $errorMembeIdArr = array();
    $errorMembeEmailArr = array();

    $membersArr = explode(',', $_members);

    foreach ($membersArr as $memberId) {
        $res = db_query("SELECT m.name FROM reg r INNER JOIN member m ON m.key=r.member_key WHERE r.event_key='$_eventId' AND r.member_key='$memberId'");
        if($row = $res->fetch_assoc()){
            $alreadyAddedArr [] = $row['name'];
        }
        else{
            $res = db_query("SELECT m.email, m.name FROM member m WHERE m.key='$memberId'");
            if($row = $res->fetch_assoc()){
                if($row['email'] == ''){
                    $emptyEmailArr [] = $row['name'];
                }
                else{
                    if(!filter_var($row['email'], FILTER_VALIDATE_EMAIL)){
                        $errorMembeEmailArr = $row['name'];
                    }
                    else{
                        $resEvent = db_query("SELECT name, start_date, end_date, regend_date, info FROM event WHERE `key`='$_eventId'");
                        $rowEvent = $resEvent->fetch_assoc();
                        $linkCode = UTILS::getLinkToCreateMemberEvent($memberId, $_eventId);
                        $from_email = 'info@reg-page.ru';

                        if($_showAdminName){
                            $resAdmin = db_query("SELECT login FROM admin WHERE member_key='$_adminId'");
                            $rowAdmin = $resAdmin->fetch_assoc();
                            $from_email = $rowAdmin['login'];
                        }

                        UTILS::sendEmailToInviteMember($linkCode, $from_email, $row['email'], $row['name'], $rowEvent['name'], $rowEvent['start_date'], $rowEvent['end_date'], $rowEvent['regend_date'], $rowEvent['info']);
                        $sendEmailsArr[] = $row['name'];
                    }
                }
            }
            else{
                $errorMembeIdArr[] = $memberId;
            }
        }
    }
    return ['errorMembeIdArr' => $errorMembeIdArr, 'sendEmailsArr' => $sendEmailsArr, 'errorMembeEmailArr' => $errorMembeEmailArr, 'emptyEmailArr' => $emptyEmailArr, 'alreadyAddedArr' => $alreadyAddedArr];
}


function db_getEventMemberInvited($eventId, $memberId){
    global $db;
    $_memberId = $db->real_escape_string($memberId);
    $_eventId = $db->real_escape_string($eventId);

    $res=db_query ("SELECT m.key as member_key, m.name,
                    CASE WHEN m.male=1 THEN 'male' WHEN m.male=0 THEN 'female' ELSE '' END as gender, m.male,
                    m.birth_date, m.locality_key, m.address, m.home_phone, m.cell_phone, m.email,
                    m.category_key, m.document_key, m.admin_key as mem_admin,
                    m.document_num, m.document_date, m.document_auth, m.new_locality, m.citizenship_key,
                    m.english, m.tp_num, m.school_comment, m.tp_date, m.tp_auth, m.tp_name, m.russian_lg, m.baptized,
                    m.school_start, m.school_end, m.college_start, m.college_end, m.college_key, m.college_comment,

                    e.key as event_key, e.need_passport, e.need_transport, e.need_prepayment, e.start_date, e.end_date,
                    IF (rg.name='--',l.name,CONCAT (l.name,', ',rg.name)) as locality_name, e.organizer,
                    e.currency, e.contrib, e.need_flight, e.need_address, e.need_tp, e.name as event_name,

                    c.key as country_key,
                    DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365 as age
                    FROM event as e, member as m
                    LEFT JOIN locality l ON m.locality_key=l.key
                    LEFT JOIN region rg ON l.region_key=rg.key
                    LEFT JOIN country c ON c.key=rg.country_key
                    WHERE m.key='$_memberId' AND e.key='$_eventId' ");

    if ($row = $res->fetch_assoc()) return $row;
    return NULL;
}

function db_getReferences($sortField, $sortType){
    global $db;
    $_sortField = $db->real_escape_string($sortField);
    $_sortType = $db->real_escape_string($sortType);

    $res = db_query("SELECT r.name, r.link_article, p.name as page_name,
            r.block_num, r.published, r.id, r.page, b.name as block_name, r.priority
            FROM reference_system r
            INNER JOIN page p ON p.key=r.page
            INNER JOIN reference_block b ON b.id=r.block_num
            ORDER BY priority desc, $_sortField $_sortType ");

    $references = array();
    while($row = $res->fetch_assoc()){
        $references [] = $row;
    }

    if(count($references) > 0){
        return $references;
    }
    return null;
}

function db_addReference($data){
    global $db;

    $_name = $db->real_escape_string($data['name']);
    $_page = $db->real_escape_string($data['page']);
    $_link_article = $db->real_escape_string($data['link_article']);
    $_block = $db->real_escape_string($data['block']);
    $_published = $db->real_escape_string($data['published']);
    $_priority = $db->real_escape_string($data['priority']);

    db_query("INSERT INTO reference_system (name, link_article, page, block_num, published, priority) VALUES ('$_name', '$_link_article', '$_page', '$_block', '$_published', $_priority)");
}

function db_setReference($data){
    global $db;
    $_name = $db->real_escape_string($data['name']);
    $_page = $db->real_escape_string($data['page']);
    $_link_article = $db->real_escape_string($data['link_article']);
    $_block = (int)$data['block'];
    $_id = (int)$data['id'];
    $_published = $db->real_escape_string($data['published']);
    $_priority = $db->real_escape_string($data['priority']);

    db_query("UPDATE reference_system SET name='$_name', link_article = '$_link_article', page = '$_page', block_num = '$_block', published = '$_published', priority='$_priority' WHERE id=$_id");
}

function db_setReferenceFieldValue($field, $value, $id){
    global $db;
    $_field = $db->real_escape_string($field);
    $_value = (int)$value;
    $_id = (int)$id;

    db_query("UPDATE reference_system SET ".$_field." = '$_value' WHERE id=$_id ");
}

function db_deleteReference($id){
    $_id = (int)$id;

    db_query("DELETE FROM reference_system WHERE id=$_id ");
}

function db_getPages(){
    $res = db_query("SELECT * FROM page");

    $pages = array();
    while($row = $res->fetch_assoc()){
        $pages [$row['key']] = $row['name'];
    }

    return $pages;
}

function db_getReferencesBlock(){
    $res = db_query("SELECT * FROM reference_block");

    $blocks = array();
    while($row = $res->fetch_assoc()){
        $blocks [$row['id']] = $row['name'];
    }

    return $blocks;
}

function db_getEventTemplates($adminId){
    return [];
}

function db_getParticipantsForEvent($text, $field){
    global $db;
    $text = $db->real_escape_string($text);
    $field = $db->real_escape_string($field);

    if($text == ''){
        return false;
    }

    $res = db_query(
        "SELECT DISTINCT $field.name, $field.key as id, l.name as locality
        FROM locality l
        LEFT JOIN member m ON m.locality_key = l.key
        WHERE $field.name LIKE '%$text%' ORDER BY $field.name LIMIT 0, 3");

    $zones = array();
    while ($row = $res->fetch_assoc()) $zones[]=$row;
    return $zones;
}

function db_getParticipantsForMeetingByMember($text){
    global $db;
    $text = $db->real_escape_string($text);

    if($text == ''){
        return false;
    }

    $res = db_query(
        "SELECT DISTINCT m.name, m.key as id, l.name as locality, m.attend_meeting
        FROM locality l
        LEFT JOIN member m ON m.locality_key = l.key
        WHERE m.name LIKE '%$text%' ORDER BY m.name LIMIT 0, 3");

    $members = array();
    while ($row = $res->fetch_assoc()) $members[]=$row;
    return $members;
}

function db_getParticipantsForMeetingByLocality($text){
    global $db;
    $text = $db->real_escape_string($text);

    if($text == ''){
        return false;
    }

    $res = db_query(
        "SELECT DISTINCT l.name, l.key as id, l.name as locality
        FROM locality l
        LEFT JOIN member m ON m.locality_key = l.key
        WHERE l.name LIKE '%$text%' ORDER BY l.name LIMIT 0, 10");

    $zones = array();
    while ($row = $res->fetch_assoc()) $zones[]=$row;
    return $zones;
}

function db_getAvailableMembersForMeeting($text){
    global $db;
    $text = $db->real_escape_string($text);

    if($text == ''){
        return false;
    }

    $res = db_query("SELECT DISTINCT m.name, m.key as id, l.name as locality FROM member m
                    INNER JOIN locality l ON m.locality_key = l.key
                    WHERE m.name LIKE '%$text%' LIMIT 0, 3");

    $members = array();
    while ($row = $res->fetch_assoc()) $members[]=$row;
    return $members;
}

function db_addEventArchive($adminId, $data){
    global $db;
    $adminId = $db->real_escape_string($adminId);

    $mode = $db->real_escape_string($data['mode']);
    $name = $db->real_escape_string($data['name']);
    $eventType = $db->real_escape_string($data['eventType']);
    $eventLocality = $db->real_escape_string($data['eventLocality']);
    $eventStartDate = $db->real_escape_string($data['eventStartDate']);
    $eventEndDate = $db->real_escape_string($data['eventEndDate']);
    $eventInfo = $db->real_escape_string($data['eventInfo']);
    $participants = $data['participants'];
    $eventAdmins = $db->real_escape_string($data['eventAdmins']);
    $eventAdminsEmail = $db->real_escape_string($data['eventAdminsEmail']);
    $zones = $db->real_escape_string($data['zones']);
    $regEndDate = $db->real_escape_string($data['regEndDate']);
    $passport = $db->real_escape_string($data['passport']);
    $prepayment = $db->real_escape_string($data['prepayment']);
    $private = $db->real_escape_string($data['private']);
    $transport = $db->real_escape_string($data['transport']);
    $tp = $db->real_escape_string($data['tp']);
    $flight = $db->real_escape_string($data['flight']);
    $parking = $db->real_escape_string($data['parking']);
    $service = $db->real_escape_string($data['service']);
    $accom = $db->real_escape_string($data['accom']);
    $registration = $db->real_escape_string($data['registration']);
    $attendance = $db->real_escape_string($data['attendance']);
    $countMeetings = $db->real_escape_string($data['countMeetings']);

    if($name == '' || $eventType == '_none_' || $eventLocality == '_none_' || $eventStartDate == '' || $eventEndDate == ''){
        throw new Exception("Необходимо заполнить все поля выделенные розовым цветом.", 1);
    }
    else{

        if($registration === '1'){

            db_handleEvent ($name, $eventLocality, $adminId, $eventStartDate, $eventEndDate, $regEndDate, $passport,
            $prepayment, $private, $transport, $tp, $flight, $eventInfo, $reg_members, $eventAdminsEmail,
            $team_key,
            null, $eventType, $zones, $parking, $service, $accom, 'event_');
        }
        else{

        }
        //$res = db_query("SELECT * FROM event_archive WHERE end_date = '".."' ");
        //$row = $res->fetch_assoc();
        // check to filter double events
        $participantsArr = [];

        $_participants = json_decode($participants, TRUE);

        foreach ($_participants as $key => $_participant) {
            switch ($_participant['field']) {
                case 'c':
                    $res = db_query("SELECT m.key FROM member m
                        INNER JOIN locality l ON m.locality_key = l.key
                        INNER JOIN region r ON r.key = l.region_key
                        INNER JOIN country c ON c.key = r.country_key
                        WHERE c.key = '".$_participant['id']."' ");
                    while ($row = $res->fetch_assoc()) {
                        $participantsArr [] = $row['key'];
                    }
                    break;
                case 'r':
                    $res = db_query("SELECT m.key FROM member m
                        INNER JOIN locality l ON m.locality_key = l.key
                        INNER JOIN region r ON r.key = l.region_key
                        WHERE r.key = '".$_participant['id']."' ");
                    while ($row = $res->fetch_assoc()) {
                        if(!in_array($row['key'], $participantsArr)){
                            $participantsArr [] = $row['key'];
                        }
                    }
                    break;
                case 'l':
                    $res = db_query("SELECT m.key FROM member m
                        INNER JOIN locality l ON l.key = m.locality_key
                        WHERE l.key = '".$_participant['id']."' ");
                    while ($row = $res->fetch_assoc()) {
                        if(!in_array($row['key'], $participantsArr)){
                            $participantsArr [] = $row['key'];
                        }
                    }
                    break;
                case 'm':
                    if(!in_array($_participant['id'], $participantsArr)){
                        $participantsArr [] = $_participant['id'];
                    }
                    break;
            }
        }

        db_query("INSERT INTO event_archive (name, event_type, created, locality_key, author, members, start_date, end_date, members_count) VALUES ('$name', '$eventType', now(), '$eventLocality', '$adminId', '". implode(',', $participantsArr) ."', '$eventStartDate', '$eventEndDate', '".count($participantsArr)."')");

        $eventArchiveId = $db->insert_id;

        handleAdminsInEventAccessTable($eventArchiveId, $eventAdmins, 'event_archive_');
        addZonesForEvent($eventArchiveId, $zones, 'event_archive_');
    }
}

function db_getYouthList($adminId, $sort_field, $sort_type){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $sort_field = $db->real_escape_string($sort_field);
    $sort_type = $db->real_escape_string($sort_type);

    $res=db_query ("SELECT DISTINCT * FROM
        (SELECT DISTINCT m.key as id, m.name as name, IF (COALESCE(l.name,'')='', m.new_locality, l.name) as locality,
        m.email as email, m.cell_phone as cell_phone, m.changed>0 as changed, m.admin_key as admin_key,
        (SELECT name FROM member m2 WHERE m2.key=m.admin_key) as admin_name, m.active, m.locality_key,
        DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365 as age, m.birth_date,
        m.school_comment, m.college_comment, m.college_start, m.college_end, m.school_start, m.school_end,
        m.comment, co.name as college_name, m.category_key, m.attend_meeting,
        co.short_name as college,
        (SELECT rg.name FROM region rg WHERE rg.key=l.region_key) as region,/*the next 2 lines are Romans code ver 5.0.1*/
        (SELECT co.name FROM country co INNER JOIN region re ON co.key=re.country_key WHERE l.region_key=re.key) as country,
        (SELECT lo.name FROM locality lo WHERE co.locality_key = lo.key ) as college_locality,
        CASE WHEN m.category_key='SC' OR m.category_key='PS' THEN 1 ELSE 0 END as school,
        CASE WHEN m.school_start>0 THEN YEAR(NOW()) - m.school_start + 1 ELSE 0 END as school_level,
        CASE WHEN m.college_start>0 THEN YEAR(NOW()) - m.college_start + 1 ELSE 0 END as college_level
        FROM access as a
        LEFT JOIN country c ON c.key = a.country_key
        LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
        INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
        INNER JOIN member m ON m.locality_key = l.key
        LEFT JOIN college co ON co.key = m.college_key
        WHERE a.member_key='$adminId' AND ( m.category_key = 'ST' OR m.category_key = 'SC' OR ( (m.category_key = 'BL' OR m.category_key = 'SN' ) AND ROUND(DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365, 0) < 25 AND ROUND(DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365, 0) > 17))
        UNION
        SELECT DISTINCT m.key as id, m.name as name,
        (SELECT lo.name FROM locality lo WHERE m.locality_key = lo.key ) as locality,
        m.email as email, m.cell_phone as cell_phone, m.changed>0 as changed, m.admin_key as admin_key,
        (SELECT name FROM member m2 WHERE m2.key=m.admin_key) as admin_name, m.active, m.locality_key,
        DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365 as age, m.birth_date,
        m.school_comment, m.college_comment, m.college_start, m.college_end, m.school_start, m.school_end,
        m.comment, co.name as college_name, m.category_key, m.attend_meeting,
        co.short_name as college,
        (SELECT rg.name FROM region rg WHERE rg.key=l.region_key) as region,/*the next 2 lines are Romans code ver 5.0.1*/
        (SELECT co.name FROM country co INNER JOIN region re ON co.key=re.country_key WHERE l.region_key=re.key) as country,
        (SELECT lo.name FROM locality lo WHERE co.locality_key = lo.key ) as college_locality,
        CASE WHEN m.category_key='SC' OR m.category_key='PS' THEN 1 ELSE 0 END as school,
        CASE WHEN m.school_start>0 THEN YEAR(NOW()) - m.school_start + 1 ELSE 0 END as school_level,
        CASE WHEN m.college_start>0 THEN YEAR(NOW()) - m.college_start + 1 ELSE 0 END as college_level
        FROM access as a
        LEFT JOIN country c ON c.key = a.country_key
        LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
        INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
        INNER JOIN college co ON co.locality_key = l.key
        INNER JOIN member m ON m.college_key = co.key
        WHERE a.member_key='$adminId' AND ( m.category_key = 'ST' OR m.category_key = 'SC' OR ((m.category_key = 'BL' OR m.category_key = 'SN' ) AND ROUND(DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365, 0) < 25 AND ROUND(DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365, 0) > 17 ) )) q ORDER BY q.active DESC, $sort_field $sort_type");

    $members = array ();
    while ($row = $res->fetch_object()) $members[]=$row;
    return $members;
}

function db_getMeetingTemplates($memberId){
    global $db;
    $_memberId = $db->real_escape_string($memberId);
    db_query('SET Session group_concat_max_len=20000');

    $templates = [];

    $res = db_query("SELECT mt.id, me.key as meeting_type, me.name as meeting_name,
                    mt.name as template_name, l.key as locality_key, l.name as locality_name,
                    (SELECT GROUP_CONCAT(CONCAT_WS(':', m.key, m.name, lo.name, m.attend_meeting)  ORDER BY m.name ASC SEPARATOR  ',') FROM member m INNER JOIN locality lo ON m.locality_key=lo.key WHERE FIND_IN_SET(m.key, mt.participant)<>0) as participants,
                    (SELECT GROUP_CONCAT( CONCAT_WS(':', m.key, m.name, lo.name, m.attend_meeting) ORDER BY m.name ASC SEPARATOR  ',') FROM member m INNER JOIN locality lo ON m.locality_key=lo.key WHERE FIND_IN_SET(m.key, mt.admin)<>0) as admins
                    FROM meeting_template mt
                    INNER JOIN locality l ON l.key=mt.locality_key
                    INNER JOIN meeting_type me ON me.key=mt.meeting_type_key
                    WHERE FIND_IN_SET('$_memberId', mt.admin)<>0 ");

    while ($row = $res->fetch_assoc()) $templates[]=$row;

    return $templates;
}

function db_getMeetingTemplate($templateId){
    global $db;
    $_templateId = $db->real_escape_string($templateId);
    db_query('SET Session group_concat_max_len=20000');

    $res = db_query(
        "SELECT mt.id, mt.name as meeting_name, mt.meeting_type_key, mt.locality_key,
        (SELECT GROUP_CONCAT( CONCAT_WS(':', m.key, m.name, l.name) ORDER BY m.name ASC SEPARATOR ',') FROM member m INNER JOIN locality l ON l.key=m.locality_key WHERE FIND_IN_SET(m.key, mt.participant)<>0) as participants,
        (SELECT GROUP_CONCAT( CONCAT_WS(':', ROUND(DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365),
                    m.category_key) SEPARATOR  ',') as members FROM member m WHERE FIND_IN_SET(m.key, mt.participant)<>0) as members
        FROM meeting_template mt
        WHERE mt.id = '$_templateId' "
    );

    if($template = $res->fetch_assoc()){
        return $template;
    }

    return false;
}

function db_handleTemplate($adminId, $data){
    global $db;
    $_adminId = $db->real_escape_string($adminId);
    $type = isset($data['type']) ? $db->real_escape_string($data['type']) : '';
    $name = isset($data['name']) ? $db->real_escape_string($data['name']) : '';
    $locality = isset($data['locality']) ? $db->real_escape_string($data['locality']) : '';
    $participants = isset($data['participants']) ? $db->real_escape_string($data['participants']) : '';
    $admins = isset($data['admins']) ? $db->real_escape_string($data['admins']) : '';
    $id = isset($data['id']) ? $db->real_escape_string($data['id']) : '';

    $_participants = [];
    if($participants){
        $participantsArr = explode(',', $participants);

        foreach ($participantsArr as $value) {
            switch (strlen($value)) {
                case 6:
                    $res = db_query("SELECT m.key FROM member m
                        INNER JOIN locality l ON l.key = m.locality_key
                        WHERE l.key = '$value' ");

                    while ($row = $res->fetch_assoc()) {
                        if(!in_array($row['key'], $_participants)){
                            $_participants [] = $row['key'];
                        }
                    }

                    break;
                case 9:
                    if(!in_array($value, $_participants)){
                        $_participants [] = $value;
                    }
                    break;
            }
        }
    }

    $adminsArr = explode(',', $admins);
    if(!in_array($_adminId, $adminsArr)){
        $adminsArr [] = $_adminId;
    }

    if($id){
        db_query("UPDATE meeting_template
                SET name = '$name', meeting_type_key = '$type' ,
                locality_key = '$locality' , participant = '".implode(',', $_participants)."',
                admin = '".implode(',', $adminsArr)."' WHERE id = '$id' ");
    }
    else{
        db_query("INSERT INTO meeting_template (name, meeting_type_key, locality_key, participant, admin)
                  VALUES ('$name', '$type', '$locality', '".implode(',', $_participants)."', '".implode(',', $adminsArr)."') ");
    }
}

function db_deleteTemplateParticipant($memberId, $mode, $templateId){
    global $db;
    $_memberId = $db->real_escape_string($memberId);
    $_mode = $db->real_escape_string($mode);
    $_templateId = $db->real_escape_string($templateId);

    $res = db_query("SELECT * FROM meeting_template WHERE id='$_templateId'");
    $row = $res->fetch_assoc();

    $field = $_mode == 'participants' ? 'participant' : 'admin';
    $items = explode(',', $row [$field]);

    if (($key = array_search($_memberId, $items)) !== false) {
        unset($items[$key]);
    }

    if(count($items) > 0){
        db_query("UPDATE meeting_template SET $field ='". implode(',', $items) ."' WHERE id = '$_templateId' ");
    }
    else{
         db_query("DELETE FROM meeting_template WHERE id = '$_templateId' ");
    }
}

function db_getMeetingTemplateParticipantList($templateId, $mode){
    global $db;
    $_templateId = $db->real_escape_string($templateId);
    db_query('SET Session group_concat_max_len=20000');

    $participants = [];

    $res = db_query("SELECT
                    (SELECT GROUP_CONCAT(CONCAT_WS(':', m.key, m.name, lo.name)  ORDER BY m.name ASC SEPARATOR  ',') FROM member m INNER JOIN locality lo ON m.locality_key=lo.key WHERE FIND_IN_SET(m.key, mt.participant)<>0) as participants,
                    (SELECT GROUP_CONCAT( CONCAT_WS(':', m.key, m.name, lo.name) ORDER BY m.name ASC SEPARATOR  ',') FROM member m INNER JOIN locality lo ON m.locality_key=lo.key WHERE FIND_IN_SET(m.key, mt.admin)<>0) as admins
                    FROM meeting_template mt
                    WHERE mt.id='$_templateId' ");

    $row = $res->fetch_assoc();
    $participants[]  = $mode == 'participants' ? $row ['participants'] : $row['admins'];

    return implode(',', $participants);
}

function db_deleteMeetingTemplate($adminId, $templateId){
    global $db;
    $_adminId = $db->real_escape_string($adminId);
    $_templateId = $db->real_escape_string($templateId);

    $res = db_query("SELECT admin FROM meeting_template WHERE id = '$_templateId' ");
    $row = $res->fetch_assoc();

    if($row['admin']){
        $admins = explode(',', $row['admin']);

        if (($key = array_search($_adminId, $admins)) !== false) {
            unset($admins[$key]);
        }

        if(count($admins) == 0){
            db_query("DELETE FROM meeting_template WHERE id='$_templateId' ");
        }
        else{
            db_query("UPDATE meeting_template SET admin='".implode(',', $admins)."' WHERE id='$_templateId' ");
        }
    }
    else{
        db_query("DELETE FROM meeting_template WHERE id='$_templateId' ");
    }
}

function db_addTemplateMeeting($templateId){
    global $db;
    $_templateId = $db->real_escape_string($templateId);
    db_query('SET Session group_concat_max_len=20000');

    $res = db_query("SELECT mt.name, mt.meeting_type_key, mt.locality_key, mt.participant,
                    GROUP_CONCAT( CONCAT_WS(':', ROUND(DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365),
                    m.category_key) SEPARATOR  ',') as participants
                    FROM meeting_template mt
                    INNER JOIN member m ON FIND_IN_SET(m.key, mt.participant)<>0
                    WHERE id ='$_templateId' ");

    $row = $res->fetch_assoc();

    if($row){
        $traineesCount = 0;
        $childrenCount = 0;
        $fulltimersCount = 0;
        $saintsCount = 0;
        $members = $row['participant'];

        $participants = explode(',', $row['participants']);
        foreach ($participants as $key => $value) {
            $member = explode(':', $value);

            if(count($member) > 0){
                if(count($member) == 2){
                    $age = $member[0];
                    $category = $member[1];
                }
                else{
                    if(is_string ( $member[0] )){
                        $category = $member[0];
                        $age = 0;
                    }
                    else{
                        $age = $member[0];
                        $category = '';
                    }
                }

                if($age > 0  && $age < 12){
                    $childrenCount ++;
                }

                switch ($category) {
                    case 'FT':
                        $traineesCount ++;
                        break;
                    case 'FS':
                        $fulltimersCount ++;
                        break;
                }
            }
        }

        $locality = $row['locality_key'];
        $listCount = count($participants);

        setMeetingByTemplate(['saints_count' => $saintsCount, 'trainees_count' => $traineesCount,
            'fulltimers_count' => $fulltimersCount,
            'list_count' => $listCount, 'children_count' => $childrenCount, 'members' => $members,
            'locality_key' => $locality, 'meeting_type' => $row['meeting_type_key'], 'name' => $row['name'] ]);
    }
}

function setMeetingByTemplate($data){
    db_query('INSERT INTO meetings (trainees_count, fulltimers_count, list_count, children_count, members, locality_key, date, meeting_type, name)
        VALUES ("'.$data["trainees_count"].'", "'.$data["fulltimers_count"].'",
            "'.$data["list_count"].'", "'.$data["children_count"].'", "'.$data["members"].'",
            "'.$data["locality_key"].'", now(), "'.$data["meeting_type"].'", "'.$data["name"].'" )');
}

function db_addTemplateParticipant($mode, $participantId, $templateId){
    global $db;
    $_mode = $db->real_escape_string($mode);
    $_participantId = $db->real_escape_string($participantId);
    $_templateId = $db->real_escape_string($templateId);

    $field = $_mode == 'participants' ? 'participant' : 'admin';
    $res = db_query("SELECT $field FROM meeting_template WHERE id = '$_templateId' ");
    $row = $res->fetch_assoc();

    $arrItems = explode(',', $row[$field]);
    if(!in_array($_participantId, $arrItems)){
        $arrItems[] = $_participantId;

        db_query("UPDATE meeting_template SET $field = '".implode(',', $arrItems)."' WHERE id = '$_templateId' ");
        return true;
    }
    return false;
}

function db_isAvailableMeetingPage($memberId){
    global $db;
    $_memberId = $db->real_escape_string($memberId);
    db_query('SET Session group_concat_max_len=20000');

    $res = db_query("SELECT COUNT(*) as count FROM meeting_template WHERE FIND_IN_SET('$_memberId', admin)<>0 ");
    $row = $res->fetch_assoc();
    if($row['count'] > 0){
        return true;
    }

    return false;
}

function db_getMembersByLocality($localityId){
    global $db;
    $_localityId = $db->real_escape_string($localityId);

    $resLocality = db_query("SELECT m.key as id, m.attend_meeting, l.name as locality, m.name FROM locality l INNER JOIN member m ON l.key=m.locality_key WHERE l.key='$_localityId' ");

    $members = [];
    while($rowMember = $resLocality->fetch_assoc()){
        $members [] = $rowMember;
    }

    return $members;
}

function db_setAttendMeeting($value, $memberId){
    global $db;
    $_value = $db->real_escape_string($value);
    $_memberId = $db->real_escape_string($memberId);

    db_query("UPDATE member SET attend_meeting = '$_value' WHERE `key`='$_memberId' ");

    $res = db_query("SELECT birth_date, baptized, category_key FROM member WHERE `key`='$_memberId' ");
    if($row = $res->fetch_assoc()){
        if(!$row['birth_date'] || $row['birth_date'] == "0000-00-00"){
            return "Не забудьте указать дату рождения для формирования статистики посещаемости по возрастам.";
        }
        else if($row['category_key'] == 'SC' && (!$row['baptized'] || $row['baptized'] == "0000-00-00")){
            return "Если школьник крещён, нужно указать дату крещения для корректного формирования статистики.";
        }
        else{
            return false;
        }
    }
    else{
        return "Данного участника нет в списке.";
    }
}

function db_checkEventStopRegistration($eventId){
    global $db;
    $_eventId = $db->real_escape_string($eventId);

    /*
    $res=db_query ("SELECT IF((SELECT COUNT(*) FROM reg rg WHERE rg.event_key=e.key AND (rg.regstate_key = '01' OR rg.regstate_key = '02' OR rg.regstate_key = '04' OR rg.regstate_key is NULL )) >= e.participants_count AND e.participants_count > 0, 1, 0) as stop_registration, e.close_registration FROM event e WHERE e.key='$_eventId' ");
    */

    $res=db_query ("SELECT (SELECT COUNT(*) FROM reg rg WHERE rg.event_key=e.key AND (rg.regstate_key = '01' OR rg.regstate_key = '02' OR rg.regstate_key = '04' OR rg.regstate_key is NULL )) as count_members, e.participants_count, e.close_registration FROM event e WHERE e.key='$_eventId' ");

    $row= $res->fetch_assoc();

    return $row;
}

function db_getEventListName($eventId){
    global $db;
    $_eventId = $db->real_escape_string($eventId);

    $res=db_query ("SELECT list_name FROM event WHERE `key`='". $_eventId ."' ");

    $row= $res->fetch_assoc();

    return $row;
}

function db_getEventListItems($eventId){
    global $db;
    $_eventId = $db->real_escape_string($eventId);

    $res = db_query("SELECT list FROM event WHERE `key`='$_eventId' ");

    $resItems = [];
    while($row = $res->fetch_assoc()){
        $resItems [] = $row;
    }

    $items = $resItems.str_split(';');
    return $items;
}

function db_downloadExcelData($members){
    global $db;

    try{
        $_members = json_decode($members, TRUE);

        foreach ($_members as $key => $values) {
            foreach ($values as $k => $v) {
                $f->fb($k);
            }
        }
        return false;
    }
    catch (Exception $e) {
        return $e;
    }
}

function db_addFilter($filter_name, $admin_key){
    global $db;
    $_filter_name = $db->real_escape_string($filter_name);
    $_admin_key = $db->real_escape_string($admin_key);

    db_query("INSERT INTO filter (name, admin_key) VALUES ('$_filter_name', '$_admin_key') ");
}

function db_getAdminFilters($admin_key){
    global $db;
    $_admin_key = $db->real_escape_string($admin_key);

    $res = db_query("SELECT * FROM filter WHERE admin_key='$_admin_key' ");

    $filters = [];
    while($row = $res->fetch_assoc()){
        $filters [] = $row;
    }

    return $filters;
}

function db_saveFilterLocalities($filter_id, $filter_localities){
    global $db;
    $_filter_localities = $db->real_escape_string($filter_localities);
    $_filter_id = $db->real_escape_string($filter_id);

    db_query("UPDATE filter SET value = '".($_filter_localities == '' ? NULL : $_filter_localities)."' WHERE id='$_filter_id' ");
}

function db_saveFilter($filter_id, $filter_name){
    global $db;
    $_filter_name = $db->real_escape_string($filter_name);
    $_filter_id = $db->real_escape_string($filter_id);

    db_query("UPDATE filter SET name = '".$_filter_name."' WHERE id='$_filter_id' ");
}

function db_removeFilter($filter_id){
    global $db;
    $_filter_id = $db->real_escape_string($filter_id);

    db_query("DELETE FROM filter WHERE id='$_filter_id' ");
}

function db_getSettings(){
    $res = db_query("SELECT * FROM setting_category sc
                     LEFT JOIN setting_item si ON si.setting_category_key=sc.category_key");

    $settings = [];
    while($row = $res->fetch_assoc()){
        $settings [] = $row;
    }

    return $settings;
}

function db_getUserSettings($admin_key){
    global $db;
    $_admin_key = $db->real_escape_string($admin_key);

    $res = db_query("SELECT setting_key FROM user_setting WHERE member_key='$_admin_key' ");

    $settings = [];
    while($row = $res->fetch_assoc()){
        $settings [] = $row['setting_key'];
    }

    return $settings;
}

function db_getUserAccessAreaSettings($admin_key){
    global $db;
    $_admin_key = $db->real_escape_string($admin_key);

    $res = db_query("SELECT setting_key FROM user_access_area_setting WHERE member_key='$_admin_key' ");

    $settings = [];
    while($row = $res->fetch_assoc()){
        $settings [] = $row['setting_key'];
    }

    return $settings;
}

function db_updateUserSetting($admin_key, $setting_key, $is_checked){
    global $db;
    $_admin_key = $db->real_escape_string($admin_key);
    $_setting_key = $db->real_escape_string($setting_key);
    $_is_checked = $db->real_escape_string($is_checked);

    if($_is_checked === 'true'){
        db_query("INSERT INTO user_setting (member_key, setting_key) VALUES ('$_admin_key', '$_setting_key') ");
    }
    else{
        db_query("DELETE FROM user_setting WHERE member_key='$_admin_key' AND setting_key='$_setting_key'");
    }
}

function db_updateUserAccessAreaSetting($admin_key, $setting_key, $is_checked){
    global $db;
    $_admin_key = $db->real_escape_string($admin_key);
    $_setting_key = $db->real_escape_string($setting_key);
    $_is_checked = $db->real_escape_string($is_checked);

    if($_is_checked === 'true'){
        db_query("INSERT INTO user_access_area_setting (member_key, setting_key) VALUES ('$_admin_key', '$_setting_key') ");
    }
    else{
        db_query("DELETE FROM user_access_area_setting WHERE member_key='$_admin_key' AND setting_key='$_setting_key'");
    }
}
