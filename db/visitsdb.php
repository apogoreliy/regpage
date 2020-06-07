<?php
// DATA BASE QUERY

// VISITS 

function db_getVisits($adminId, $sort_type, $sort_field, $localityFilter, $meetingTypeFilter, $startDate, $endDate){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $localityFilter = $db->real_escape_string($localityFilter);
    $meetingTypeFilter = $db->real_escape_string($meetingTypeFilter);
    $startDate = $db->real_escape_string($startDate);
    $endDate = $db->real_escape_string($endDate);
    $sort_type = $db->real_escape_string($sort_type);
    $sort_field = $sort_field != 'locality_key' ? $db->real_escape_string($sort_field)."" : " locality_key ";

    $meetings = array ();

    $requestMeeting = $meetingTypeFilter == "_all_" ? "" : " AND vi.act=0 ";
    $requestLocality = $localityFilter=="_all_" ? "" : " AND l.key='$localityFilter' ";
    $requestDates = " AND (vi.date_visit BETWEEN '$startDate' AND '$endDate')";

    db_query('SET Session group_concat_max_len=100000');

    $res = db_query("SELECT DISTINCT * FROM (
            SELECT vi.id as visit_id, vi.act,  vi.date_visit, vi.admin_key, vi.performed, vi.locality_key, vi.comments, vi.responsible, vi.count_members,
            (SELECT GROUP_CONCAT( CONCAT_WS(':', mb.key, mb.name, lo.name, mb.attend_meeting, mb.category_key, mb.locality_key, mb.cell_phone, mb.birth_date) ORDER BY mb.name ASC SEPARATOR ',') FROM member mb INNER JOIN locality lo ON lo.key=mb.locality_key WHERE FIND_IN_SET(mb.key, vi.list_members)<>0) as members, vi.list_members
            FROM access a
            LEFT JOIN country c ON c.key = a.country_key
            LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
            INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
            INNER JOIN visits vi ON vi.locality_key = l.key
            WHERE a.member_key='$adminId' $requestMeeting $requestLocality $requestDates
            ) q ORDER BY q.$sort_field ".$sort_type.", q.locality_key ASC");

    while ($row = $res->fetch_object()) $meetings[]=$row;
    return $meetings;
}

function db_setVisit($data){
    global $db;
    $date = $db->real_escape_string($data['date']);
    $locality = $db->real_escape_string($data['locality']);
    $note = $db->real_escape_string($data['note']);
    $actionType = $db->real_escape_string($data['actionType']);
    $responsible = $db->real_escape_string($data['responsible']);
    $adminIdIs= $db->real_escape_string($data['admin']);
    $countMembers = (int)($data['countMembers']);
    $performed = (int)($data['performed']);
    $visitId = isset($data['visitId']) ? $db->real_escape_string($data['visitId']) : null;
    $members = $data['members'];

    //$oldDate = isset($data['oldDate'] ) ? $db->real_escape_string($data['oldDate']) : null;
    //$oldLocality = isset($data['oldLocality'] ) ? $db->real_escape_string($data['oldLocality']) : null;
    //$oldMeetingType = isset($data['oldMeetingType'] ) ? $db->real_escape_string($data['oldMeetingType']) : null;
    //$meetingType = $db->real_escape_string($data['meetingType']);
    //$listCount = $db->real_escape_string($data['listCount']);
    //$traineesCount = isset($data['traineesCount']) ? $db->real_escape_string($data['traineesCount']) : null;
    //$fulltimersCount = (int)($data['fulltimersCount']);
    //$attendMembers = $db->real_escape_string( $data['attendMembers']);
    //$listCount = $responsible + $performed;
    //$listCount = $members ? count(explode(',', $data['members'])) : db_getCountMembersByLocality($locality);

    if($visitId){
       //if(checkDoubleMeeting($date, $locality, $actionType)){
       //     return true;
       // }

        db_query("UPDATE visits SET act='$actionType', admin_key='$adminIdIs',
                date_visit='$date', locality_key='$locality', comments='$note', responsible='$responsible',
                count_members='$countMembers', performed='$performed', list_members = '$members' WHERE id ='$visitId' ");
    }
    else {

        db_query("INSERT INTO visits (date_visit, locality_key, comments, responsible, count_members, performed, admin_key, act, list_members )
                VALUE ('$date', '$locality', '$note', '$responsible', '$countMembers', '$performed', '$adminIdIs', '$actionType', '$members' )");
    }

    return false;
}

function db_removeVisit($meetingId){
    global $db;
    $_meetingId = $db->real_escape_string($meetingId);

    db_query("DELETE FROM visits WHERE id = '$_meetingId' ");
}

function db_setDateVisit($value, $visitId){
    global $db;
    $_value = $db->real_escape_string($value);
    $_visitId = $db->real_escape_string($visitId);

    db_query("UPDATE visits SET date_visit = '$_value' WHERE id ='$_visitId' ");

    $res = db_query("SELECT date_visit FROM visits WHERE id ='$_visitId' ");
    if($row = $res->fetch_assoc()){
      return $row;
    }
    else{
        return "Данного события нет в списке.";
    }
}

function db_setPerformedVisit($value, $visitId){
    global $db;
    $_value = $db->real_escape_string($value);
    $_visitId = $db->real_escape_string($visitId);

    db_query("UPDATE visits SET performed = '$_value' WHERE id ='$_visitId' ");

    $res = db_query("SELECT performed FROM visits WHERE id ='$_visitId' ");
    if($row = $res->fetch_assoc()){
      return $row;
    }
    else{
        return "Данного события нет в списке.";
    }
}
?>
