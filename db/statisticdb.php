<?php
// DATA BASE QUERY
// Statistic page
function db_getStatisticStrings ($memberId, $localities)
{
    global $db;
    $memberId = $db->real_escape_string($memberId);
    $localities = implode( "','", $localities);

    $res=db_query ("SELECT sit.id AS id_statistic, sit.statistic_card_id, sit.locality_key, sit.locality_status_id, sit.bptz_younger_17, sit.bptz_17_25, sit.attended_older_60, sit.bptz_count, sit.attended_younger_17, sit.attended_17_25, sit.attended_older_25, sit.attended_count, sit.lt_meeting_average, sit.status_completed, sit.author, sit.archive, sit.comment,
      l.name AS locality_name, ls.name AS status_name, sc.period_start, sc.period_end, sc.comment AS card_comment
      FROM statistic_item sit
      INNER JOIN locality l ON l.key=sit.locality_key
      INNER JOIN locality_status ls ON ls.id=sit.locality_status_id
      INNER JOIN statistic_card sc ON sc.id=sit.statistic_card_id
      WHERE sit.locality_key IN ('".$localities."')
      ORDER BY sc.period_end DESC");

    $statistic = array ();
    while ($row = $res->fetch_assoc()) $statistic[]=$row;
    return $statistic;
}

function db_setNewStatistic ($memberId, $locality, $localityStatusId, $statisticCardId, $bptzYounger17, $bptz_count, $attendedYounger17, $attended17_25, $attendedOlder25, $attended60, $attendedCount, $ltMeetingAverage, $archive, $comment, $statusCompleted)
{
    global $db;
    $memberId = $db->real_escape_string($memberId);
    $locality = $db->real_escape_string($locality);
    $localityStatusId = $db->real_escape_string($localityStatusId);
    $statisticCardId = $db->real_escape_string($statisticCardId);
    $bptzYounger17 = $db->real_escape_string($bptzYounger17);
    //$bptz17_25 = $db->real_escape_string($bptz17_25);
    $attended60 = $db->real_escape_string($attended60);
    $bptz_count = $db->real_escape_string($bptz_count);
    $attendedYounger17 = $db->real_escape_string($attendedYounger17);
    $attended17_25 = $db->real_escape_string($attended17_25);
    $attendedOlder25 = $db->real_escape_string($attendedOlder25);
    $attendedCount = $db->real_escape_string($attendedCount);
    $ltMeetingAverage = $db->real_escape_string($ltMeetingAverage);
    $archive = $db->real_escape_string($archive);
    $comment = $db->real_escape_string($comment);
    $statusCompleted = $db->real_escape_string($statusCompleted);

    db_query ("INSERT INTO statistic_item (`statistic_card_id`, `locality_key`, `locality_status_id`, `bptz_younger_17`, `bptz_count`, `attended_younger_17`, `attended_17_25`, `attended_older_25`, `attended_count`, `lt_meeting_average`, `status_completed`, `author`, `archive`, `comment`, `attended_older_60`)
     VALUES ('$statisticCardId', '$locality', '$localityStatusId', '$bptzYounger17', '$bptz_count', '$attendedYounger17', '$attended17_25', '$attendedOlder25', '$attendedCount',
        '$ltMeetingAverage', '$statusCompleted', '$memberId', '$archive', '$comment', '$attended60')");
      return false;
}

function db_getLocalitiesStatus(){

    $res=db_query ("SELECT * FROM locality_status");

    $status = array ();
    while ($row = $res->fetch_assoc()) $status[$row['id']]=$row['name'];
    return $status;
}
function db_updateStatistic ($memberId, $locality, $localityStatusId, $statisticCardId, $bptzYounger17, $bptz_count, $attendedYounger17, $attended17_25, $attendedOlder25, $attended60, $attendedCount, $ltMeetingAverage, $archive, $comment, $statusCompleted, $idStatistic)
{
    global $db;

    $memberId = $db->real_escape_string($memberId);
    $locality = $db->real_escape_string($locality);
    $localityStatusId = $db->real_escape_string($localityStatusId);
    $statisticCardId = $db->real_escape_string($statisticCardId);
    $bptzYounger17 = $db->real_escape_string($bptzYounger17);
    //$bptz17_25 = $db->real_escape_string($bptz17_25);
    $attended60 = $db->real_escape_string($attended60);
    $bptz_count = $db->real_escape_string($bptz_count);
    $attendedYounger17 = $db->real_escape_string($attendedYounger17);
    $attended17_25 = $db->real_escape_string($attended17_25);
    $attendedOlder25 = $db->real_escape_string($attendedOlder25);
    $attendedCount = $db->real_escape_string($attendedCount);
    $ltMeetingAverage = $db->real_escape_string($ltMeetingAverage);
    $archive = $db->real_escape_string($archive);
    $comment = $db->real_escape_string($comment);
    $statusCompleted = $db->real_escape_string($statusCompleted);

    db_query ("UPDATE statistic_item SET `statistic_card_id`='$statisticCardId', `locality_key`='$locality', `locality_status_id`='$localityStatusId', `bptz_younger_17`='$bptzYounger17', `bptz_count`='$bptz_count', `attended_younger_17`='$attendedYounger17',
       `attended_17_25`='$attended17_25', `attended_older_25`='$attendedOlder25', `attended_older_60`='$attended60', `attended_count`='$attendedCount', `lt_meeting_average`='$ltMeetingAverage', `status_completed`='$statusCompleted', `author`='$memberId', `archive`='$archive', `comment`='$comment'
       WHERE `id` = '$idStatistic'");
      return false;
}

function db_getPeriodActual() {
  $res=db_query ("SELECT `id` FROM statistic_card WHERE `period_end` = (SELECT MAX(`period_end`) FROM statistic_card)");

  $period;
  while ($row = $res->fetch_assoc()) $period=$row['id'];
  return $period;
}

function db_getAllPeriods() {
  $res=db_query ("SELECT `id` as id, `period_start` as start, `period_end` as stop FROM statistic_card ORDER BY stop DESC");

  $period = array();
  while ($row = $res->fetch_assoc()) $period[$row['id']]=$row['start'].' - '.$row['stop'];
  return $period;
}

function db_getPeriodInterval() {
  $res=db_query ("SELECT sc.period_start as start, sc.period_end as stop FROM statistic_card sc WHERE sc.period_end = (SELECT MAX(`period_end`) FROM statistic_card)");

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

function getCheckDublicateStatistic($statisticId, $locality) {
  global $db;
  $statisticId = $db->real_escape_string($statisticId);
  $locality = $db->real_escape_string($locality);

  $res = db_query ("SELECT `id`, `statistic_card_id`, `locality_key`
    FROM statistic_item
    WHERE statistic_card_id = '$statisticId' AND locality_key = '$locality'");

  $statisticBlank = array();
  while ($row = $res->fetch_assoc()) $statisticBlank[] = $row;
  return $statisticBlank;
}

function db_deleteMembersStatistic()
{
  global $db;
  $statisticId = $db->real_escape_string($statisticId);
  $res = db_query ("DELETE FROM statistic_item
    WHERE id = '$statisticId'");

  return $res;
}
// STATISTIC

?>
