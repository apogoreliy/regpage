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
// START statistic page
function db_getStatisticStrings ($memberId)
{
    global $db;
    $memberId = $db->real_escape_string($memberId);

    $res=db_query ("SELECT sit.id AS id_statistic, sit.statistic_card_id, sit.locality_key, sit.locality_status_id, sit.bptz_younger_17, sit.bptz_17_25, sit.bptz_older_25, sit.bptz_count, sit.attended_younger_17, sit.attended_17_25, sit.attended_older_25, sit.attended_count, sit.lt_meeting_average, sit.status_completed, sit.author, sit.archive, sit.comment,
      l.name AS locality_name, ls.name AS status_name, sc.period_start, sc.period_end, sc.comment AS card_comment
      FROM statistic_item sit
      INNER JOIN locality l ON l.key=sit.locality_key
      INNER JOIN locality_status ls ON ls.id=sit.locality_status_id
      INNER JOIN statistic_card sc ON sc.id=sit.statistic_card_id
    ");

    $statistic = array ();
    while ($row = $res->fetch_assoc()) $statistic[]=$row;
    return $statistic;
}

function db_setNewStatistic ($memberId, $locality, $localityStatusId, $statisticCardId, $bptzYounger17, $bptz17_25, $bptzOlder25, $bptz_count, $attendedYounger17, $attended17_25, $attendedOlder25, $attendedCount, $ltMeetingAverage, $archive, $comment, $statusCompleted)
{
    global $db;
    $memberId = $db->real_escape_string($memberId);
    $locality = $db->real_escape_string($locality);
    $localityStatusId = $db->real_escape_string($localityStatusId);
    $statisticCardId = $db->real_escape_string($statisticCardId);
    $bptzYounger17 = $db->real_escape_string($bptzYounger17);
    $bptz17_25 = $db->real_escape_string($bptz17_25);
    $bptzOlder25 = $db->real_escape_string($bptzOlder25);
    $bptz_count = $db->real_escape_string($bptz_count);
    $attendedYounger17 = $db->real_escape_string($attendedYounger17);
    $attended17_25 = $db->real_escape_string($attended17_25);
    $attendedOlder25 = $db->real_escape_string($attendedOlder25);
    $attendedCount = $db->real_escape_string($attendedCount);
    $ltMeetingAverage = $db->real_escape_string($ltMeetingAverage);
    $archive = $db->real_escape_string($archive);
    $comment = $db->real_escape_string($comment);
    $statusCompleted = $db->real_escape_string($statusCompleted);

    db_query ("INSERT INTO statistic_item (`statistic_card_id`, `locality_key`, `locality_status_id`, `bptz_younger_17`, `bptz_17_25`, `bptz_older_25`, `bptz_count`, `attended_younger_17`, `attended_17_25`, `attended_older_25`, `attended_count`, `lt_meeting_average`, `status_completed`, `author`, `archive`, `comment`)
     VALUES ('$statisticCardId', '$locality', '$localityStatusId', '$bptzYounger17', '$bptz17_25', '$bptzOlder25', '$bptz_count', '$attendedYounger17', '$attended17_25', '$attendedOlder25', '$attendedCount',
        '$ltMeetingAverage', '$statusCompleted', '$memberId', '$archive', '$comment')");
      return false;
}

function db_getLocalitiesStatus(){

    $res=db_query ("SELECT * FROM locality_status");

    $status = array ();
    while ($row = $res->fetch_assoc()) $status[$row['id']]=$row['name'];
    return $status;
}
function db_updateStatistic ($memberId, $locality, $localityStatusId, $statisticCardId, $bptzYounger17, $bptz17_25, $bptzOlder25, $bptz_count, $attendedYounger17, $attended17_25, $attendedOlder25, $attendedCount, $ltMeetingAverage, $archive, $comment, $statusCompleted, $idStatistic)
{
    global $db;

    $memberId = $db->real_escape_string($memberId);
    $locality = $db->real_escape_string($locality);
    $localityStatusId = $db->real_escape_string($localityStatusId);
    $statisticCardId = $db->real_escape_string($statisticCardId);
    $bptzYounger17 = $db->real_escape_string($bptzYounger17);
    $bptz17_25 = $db->real_escape_string($bptz17_25);
    $bptzOlder25 = $db->real_escape_string($bptzOlder25);
    $bptz_count = $db->real_escape_string($bptz_count);
    $attendedYounger17 = $db->real_escape_string($attendedYounger17);
    $attended17_25 = $db->real_escape_string($attended17_25);
    $attendedOlder25 = $db->real_escape_string($attendedOlder25);
    $attendedCount = $db->real_escape_string($attendedCount);
    $ltMeetingAverage = $db->real_escape_string($ltMeetingAverage);
    $archive = $db->real_escape_string($archive);
    $comment = $db->real_escape_string($comment);
    $statusCompleted = $db->real_escape_string($statusCompleted);

    db_query ("UPDATE statistic_item SET `statistic_card_id`='$statisticCardId', `locality_key`='$locality', `locality_status_id`='$localityStatusId', `bptz_younger_17`='$bptzYounger17', `bptz_17_25`='$bptz17_25', `bptz_older_25`='$bptzOlder25', `bptz_count`='$bptz_count', `attended_younger_17`='$attendedYounger17',
       `attended_17_25`='$attended17_25', `attended_older_25`='$attendedOlder25', `attended_count`='$attendedCount', `lt_meeting_average`='$ltMeetingAverage', `status_completed`='$statusCompleted', `author`='$memberId', `archive`='$archive', `comment`='$comment'
       WHERE `id` = '$idStatistic'");
      return false;
}

function db_getPeriodActual() {
  $res=db_query ("SELECT `id` FROM statistic_card");

  $period;
  while ($row = $res->fetch_assoc()) $period=$row['id'];
  return $period;
}

function db_getPeriodInterval() {
  $res=db_query ("SELECT sc.period_start as start, sc.period_end as stop FROM statistic_card sc");

  $period;
  while ($row = $res->fetch_assoc()) $period=$row['start'].' - '.$row['stop'];
  return $period;
}

function getMembersStatistic($locality) {
  $res=db_query ("SELECT m.key AS id, m.name AS name, m.locality_key AS locality, m.attend_meeting AS attend, m.baptized AS baptized, DATEDIFF(CURRENT_DATE, STR_TO_DATE(m.birth_date, '%Y-%m-%d'))/365 as age FROM member m WHERE m.locality_key = '$locality'");

  $members  = array ();
  while ($row = $res->fetch_assoc()) $members[]=$row;
  return $members;
}

// STOP statistic page
function db_getMsgParamPrivate() {
  global $db;
  $msg = 'msg_private_event';

  $res=db_query ("SELECT `value` FROM param WHERE `name` = '$msg'");
  $msgEcho = '';
  while ($row = $res->fetch_assoc()) $msgEcho=$row['value'];
  return $msgEcho;
}

?>
