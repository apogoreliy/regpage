<?php
// START get admins by L R C key
// Для получения всех местностей использовать существующий код используемый для комбобокса
function db_getAdminLocalitiesOnly ($adminId)
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
    while ($row = $res->fetch_assoc()) $localities[]=$row['id'];
    return $localities;
}

// get admins by region key
function db_getAdminRegionOnly($adminId){
    global $db;
    $adminId = $db->real_escape_string($adminId);

    $res=db_query ("SELECT region_key as region
                    FROM access
                    WHERE member_key = '$adminId' AND region_key IS NOT NULL");
    $regions = array ();
    while ($row = $res->fetch_assoc()) $regions[]=$row['region'];
    return $regions;
}

function db_getAdminCountryOnly($adminId){
    global $db;
    $adminId = $db->real_escape_string($adminId);

    $res=db_query ("SELECT country_key as country
                    FROM access
                    WHERE member_key = '$adminId' AND country_key IS NOT NULL");
    $countries = array ();
    while ($row = $res->fetch_assoc()) $countries[]=$row['country'];
    return $countries;
}

function db_getAdminsByRegion ($regions)
{
    global $db;
    $regions = implode( "','", $regions);
    //$regions = $db->real_escape_string($regions);
    $res=db_query ("SELECT member_key as id, region_key as region FROM access
    WHERE region_key IN ('".$regions."') ORDER BY region");

    $admins = array ();
    while ($row = $res->fetch_assoc()) $admins[]=$row['id'];
    return ($admins) ? $admins : null;
}
// get admins by country key
function db_getAdminsByCountry ($regions)
{
    global $db;
    $regions = implode( "','", $regions);
    //$regions = $db->real_escape_string($regions);
    $res=db_query ("SELECT member_key as id, country_key as country FROM access
    WHERE country_key IN ('".$regions."') ORDER BY country");

    $admins = array ();
    while ($row = $res->fetch_assoc()) $admins[]=$row['id'];
    return ($admins) ? $admins : null;
}
// get admins by locality key
function db_getAdminsByLocalitiesNew ($regions)
{
    global $db;
    $regions = implode( "','", $regions);
    //$regions = $db->real_escape_string($regions);
    $res=db_query ("SELECT member_key as id, locality_key as locality FROM access
    WHERE locality_key IN ('".$regions."') ORDER BY locality");

    $admins = array ();
    while ($row = $res->fetch_assoc()) $admins[]=$row['id'];
    return ($admins) ? $admins : null;
}

function db_getAdminsByLRC($memberId)
{
  $countriesArr = db_getAdminCountryOnly($memberId);
  $regionsArr = db_getAdminRegionOnly($memberId);
  $localitiesArr = db_getAdminLocalitiesOnly($memberId);
  $arrResult = array ();
  if ($localitiesArr) {
    $a = db_getAdminsByLocalitiesNew($localitiesArr);
    if (is_array($a)) {
      $arrResult = array_merge($arrResult, $a);
    }
  };
  if ($regionsArr) {
    $b = db_getAdminsByRegion($regionsArr);
    if (is_array($b)) {
      $arrResult = array_merge($arrResult, $b);
    }
  };
  if ($countriesArr) {
    $c = db_getAdminsByCountry($countriesArr);
    if (is_array($c)) {
      $arrResult = array_merge($arrResult, $c);
    }
  };
  $arrResult = array_keys(array_flip($arrResult));
  return $arrResult;
};

// get admins name by member key
function db_getAdminsNameByMembersKeys ($memberKeys)
{
    global $db;
    $memberKeys = implode( "','", $memberKeys);
    $res=db_query ("SELECT m.key as id, m.name as name FROM member m
    WHERE m.key IN ('".$memberKeys."') ORDER BY id");

    $admins = array ();
    while ($row = $res->fetch_assoc()) $admins[$row['id']]=$row['name'];
    return $admins;
}

// STOP get admins by L R C key
// START access to Archive for admins
function  db_isAuthorEvents($memberId){
    global $db;
    $memberId = $db->real_escape_string($memberId);

    $res = db_query("SELECT `key` FROM event WHERE `author`='$memberId'");

    if($res->num_rows > 0){
        $row= $res->fetch_object();
            return true;
    }
    return false;
}
function  db_isAuthorArciveEvents($memberId){
    global $db;
    $memberId = $db->real_escape_string($memberId);

    $res = db_query("SELECT `id` FROM event_archive WHERE `author`='$memberId'");

    if($res->num_rows > 0){
        $row= $res->fetch_object();
            return true;
    }
    return false;
}
function  db_isAdminArciveEvents($memberId){
    global $db;
    $memberId = $db->real_escape_string($memberId);

    $res = db_query("SELECT `id` FROM event_archive_access WHERE `member_key`='$memberId'");

    if($res->num_rows > 0){
        $row= $res->fetch_object();
            return true;
    }
    return false;
}
// STOP access to Archive for admins

function db_getMsgParamPrivate() {
  global $db;
  $msg = 'msg_private_event';

  $res=db_query ("SELECT `value` FROM param WHERE `name` = '$msg'");
  $msgEcho = '';
  while ($row = $res->fetch_assoc()) $msgEcho=$row['value'];
  return $msgEcho;
}

// servisone This use in Contacts Practices & formtab
function db_getServiceonesPvom(){

  $memberId = db_getMemberIdBySessionId (session_id());
  $localities = db_getAdminMeetingLocalities($memberId);

  $sqlLoc;
  if (!$localities) {
    return [];
  }
  foreach ($localities as $key4 => $value4) {
    if ($sqlLoc) {
      $sqlLoc = $sqlLoc." OR locality_key = '$key4'";
    }else {
      $sqlLoc = " locality_key = '$key4'";
    }

  }
// Здесь лучше отсортировать участников из нужных зон, доступных администратору
  $res = db_query("SELECT `serving` FROM member WHERE ($sqlLoc) AND `serving` <> '' ");

  $types = array ();
  while ($row = $res->fetch_assoc()) $types[]=$row['serving'];

//compare admins localities NEED
/*
  foreach ($types as $key=>$value){
    $b = db_getAdminMeetingLocalities($types[$key]);
    $a='';
    foreach ($b as $key3=>$value3){
      $a = in_array($b[$key3], $localities);
      if ($a) {
        break;
      }
    }
    if (!$a) {
      unset($types[$key]);
    }
  }
*/

  $admins = array ();

  foreach ($types as $value0){
    $res0 = db_query("SELECT `key`, `name` FROM member WHERE `key` = '$value0'");

    while ($row0 = $res0->fetch_assoc()) $admins[$row0['key']]=$row0['name'];
  }

return $admins;

}

function db_getResponsiblesLocality(){

  $memberId = db_getMemberIdBySessionId (session_id());
  $localities = db_getAdminMeetingLocalities($memberId);
  $sqlLoc;

  if (!$localities) {
    return [];
  }
  foreach ($localities as $key4 => $value4) {
    if ($sqlLoc) {
      $sqlLoc = $sqlLoc." OR locality_key = '$key4'";
    } else {
      $sqlLoc = " locality_key = '$key4'";
    }
  }
  // соединить таблицу с МЕМБЕР что бы сразу получить ФИО ответственных
// Здесь лучше отсортировать участников из нужных зон, доступных администратору
  $res = db_query("SELECT `member_key` FROM access WHERE ($sqlLoc)");

  $types = array ();
  while ($row = $res->fetch_assoc()) $types[]=$row['member_key'];

  $admins = array ();
  $unicTypes = array_unique($types);
  foreach ($unicTypes as $value0){
    $res0 = db_query("SELECT `key`, `name` FROM member WHERE `key` = '$value0'");

    while ($row0 = $res0->fetch_assoc()) $admins[$row0['key']]=$row0['name'];
  }

  return $admins;

}

?>
