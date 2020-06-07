<?php
// DATA BASE QUERY

// These Not Used ????????

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
/*
function db_getMemberIdBySessionId ($sessionId)
{
    global $db;
    $sessionId = $db->real_escape_string($sessionId);

    $res=db_query ("SELECT member_key from admin where session='$sessionId'");
    if ($row = $res->fetch_assoc()) return $row['member_key'];
    return NULL;
}
*/
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
/*
function db_getTeamEmailOld ($eventId)
{
    global $db;
    $eventId = $db->real_escape_string($eventId);
    $res=db_query ("SELECT t.email FROM team t INNER JOIN event e ON e.team_key=t.key WHERE e.key='$eventId'");
    $row = $res->fetch_assoc();
    return $row ? $row['email'] : null;
}*/

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
/*
function db_getCollegesNames(){
    $res = db_query("SELECT `key` as id, short_name, name FROM college");

    $colleges = array ();
    while ($row = $res->fetch_assoc()) $colleges[$row['id']]=$row['short_name']." (".$row['name']." )";
    return $colleges;
}
*/
/*
function db_isSingleCityArchiveEvent(){
    $res = db_query("SELECT DISTINCT e.locality_key FROM event_archive e");

    $list = array ();
    while ($row = $res->fetch_assoc()) $list[]=$row;

    return count($list) < 2;
}
*/
?>
