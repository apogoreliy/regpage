<?php
// DATA BASE QUERY
// MEETINGS
function db_getMeetingsForStatistics($adminId, $localityFilter, $meetingTypeFilter, $startDate, $endDate){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $localityFilter = $db->real_escape_string($localityFilter);
    $meetingTypeFilter = $db->real_escape_string($meetingTypeFilter);
    $startDate = $db->real_escape_string($startDate);
    $endDate = $db->real_escape_string($endDate);
    /* $sort_type = $db->real_escape_string($sort_type);
    $sort_field = $sort_field != 'locality_key' ? $db->real_escape_string($sort_field)."" : " locality_key ";
*/
    $meetings = array ();

    $requestMeeting = $meetingTypeFilter == "_all_" ? "" : " AND me.meeting_type='$meetingTypeFilter' ";
    $requestLocality = $localityFilter=="_all_" ? "" : " AND l.key='$localityFilter' ";
    $requestDates = " AND (me.date BETWEEN '$startDate' AND '$endDate')";

    db_query('SET Session group_concat_max_len=100000');

    $res = db_query("SELECT DISTINCT * FROM (
            SELECT me.name, me.id, l.name as locality_name, me.date,  me.list_count, me.saints_count,
            mt.name as meeting_name, mt.short_name, mt.key as meeting_type, me.guests_count, me.children_count,
            me.locality_key, me.note, me.fulltimers_count,
            (SELECT GROUP_CONCAT( CONCAT_WS(':', mb.key, mb.name, lo.name, mb.attend_meeting, mb.category_key, mb.locality_key, mb.birth_date) ORDER BY mb.name ASC SEPARATOR ',') FROM member mb INNER JOIN locality lo ON lo.key=mb.locality_key WHERE FIND_IN_SET(mb.key, me.members)<>0) as members,
            me.participants,
            me.trainees_count,
            0 as summary
            FROM access a
            LEFT JOIN country c ON c.key = a.country_key
            LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
            INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
            INNER JOIN meetings me ON me.locality_key = l.key
            INNER JOIN meeting_type mt ON mt.key=me.meeting_type
            WHERE a.member_key='$adminId' $requestMeeting $requestLocality $requestDates
            UNION
            SELECT me.name, me.id, l.name as locality_name, me.date, me.list_count, me.saints_count,
            mt.name as meeting_name, mt.short_name, mt.key as meeting_type, me.guests_count, me.children_count,
            me.locality_key, me.note, me.fulltimers_count,
            (SELECT GROUP_CONCAT( CONCAT_WS(':', mb.key, mb.name, lo.name, mb.attend_meeting, mb.category_key, mb.locality_key, mb.birth_date) ORDER BY mb.name ASC SEPARATOR ',') FROM member mb INNER JOIN locality lo ON lo.key=mb.locality_key WHERE FIND_IN_SET(mb.key, me.members)<>0) as members,
            me.participants,
            me.trainees_count,
             0 as summary
            FROM meetings me
            INNER JOIN meeting_type mt ON mt.key=me.meeting_type
            INNER JOIN locality l ON l.key = me.locality_key
            INNER JOIN meeting_template mtl ON mtl.locality_key = l.key
            WHERE FIND_IN_SET('$adminId', mtl.admin)<>0 $requestMeeting $requestDates $requestLocality
            ) q ORDER BY q.locality_name ASC");

    while ($row = $res->fetch_object()) $meetings[]=$row;
    return $meetings;
}

function db_getMeetings($adminId, $sort_type, $sort_field, $localityFilter, $meetingTypeFilter, $startDate, $endDate){
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $localityFilter = $db->real_escape_string($localityFilter);
    $meetingTypeFilter = $db->real_escape_string($meetingTypeFilter);
    $startDate = $db->real_escape_string($startDate);
    $endDate = $db->real_escape_string($endDate);
    $sort_type = $db->real_escape_string($sort_type);
    $sort_field = $sort_field != 'locality_key' ? $db->real_escape_string($sort_field)."" : " locality_key ";

    $meetings = array ();

    $requestMeeting = $meetingTypeFilter == "_all_" ? "" : " AND me.meeting_type='$meetingTypeFilter' ";
    $requestLocality = $localityFilter=="_all_" ? "" : " AND l.key='$localityFilter' ";
    $requestDates = " AND (me.date BETWEEN '$startDate' AND '$endDate')";
    $requestCheckMeetingAdditions = "SELECT COUNT(*) count FROM member m WHERE m.locality_key=l.key AND m.category_key='FS'";

    db_query('SET Session group_concat_max_len=100000');

    $res = db_query("SELECT DISTINCT * FROM (
            SELECT me.name, me.id, l.name as locality_name, me.date,  me.list_count, me.saints_count,
            mt.name as meeting_name, mt.short_name, mt.key as meeting_type, me.guests_count, me.children_count,
            me.locality_key, me.note, me.fulltimers_count, me.members as members, me.participants, me.trainees_count,
            IF(($requestCheckMeetingAdditions), 1, 0) as show_additions,
            0 as summary
            FROM access a
            LEFT JOIN country c ON c.key = a.country_key
            LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
            INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
            INNER JOIN meetings me ON me.locality_key = l.key
            INNER JOIN meeting_type mt ON mt.key=me.meeting_type
            WHERE a.member_key='$adminId' $requestMeeting $requestLocality $requestDates
            UNION
            SELECT me.name, me.id, l.name as locality_name, me.date, me.list_count, me.saints_count,
            mt.name as meeting_name, mt.short_name, mt.key as meeting_type, me.guests_count, me.children_count,
            me.locality_key, me.note, me.fulltimers_count, me.members as members, me.participants, me.trainees_count,
            IF(($requestCheckMeetingAdditions), 1, 0) as show_additions,
             0 as summary
            FROM meetings me
            INNER JOIN meeting_type mt ON mt.key=me.meeting_type
            INNER JOIN locality l ON l.key = me.locality_key
            INNER JOIN meeting_template mtl ON mtl.locality_key = l.key
            WHERE FIND_IN_SET('$adminId', mtl.admin)<>0 $requestMeeting $requestDates $requestLocality
            ) q ORDER BY q.$sort_field ".$sort_type.", q.locality_name ASC");

    while ($row = $res->fetch_object()) $meetings[]=$row;
    return $meetings;
}

function db_getMeetingMembersList($adminId, $meetingId, $locality, $date, $is_summary_meeting, $meeting_type){
    global $db;
    $meetingId = $db->real_escape_string($meetingId);
    $adminId = $db->real_escape_string($adminId);
    $locality = $db->real_escape_string($locality);
    $date = $db->real_escape_string($date);
    $is_summary_meeting = $db->real_escape_string($is_summary_meeting);
    $meeting_type = $db->real_escape_string($meeting_type);
    db_query('SET Session group_concat_max_len=100000');

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

    //$listCount = (int)($data['listCount']);
    $guestsCount = (int)($data['countGuest']);
    //$childrenCount = (int)($data['countChildren']);

    $saintsCount = (int)($data['saintsCount']);
    $traineesCount = (int)($data['traineesCount']);
    $fulltimersCount = (int)($data['fulltimersCount']);

    $meetingId = isset($data['meetingId']) ? $db->real_escape_string($data['meetingId']) : null;

    $members = $data['members'];
    $attendMembers = $db->real_escape_string( $data['attendMembers']);
    $listCount = $guestsCount + $saintsCount;
    //$listCount = $members ? count(explode(',', $data['members'])) : db_getCountMembersByLocality($locality);

    if($meetingId){
        if(($date != $oldDate || $locality != $oldLocality || $meetingType != $oldMeetingType) && checkDoubleMeeting($date, $locality, $meetingType, $name)){
            return true;
        }

        db_query("UPDATE meetings SET name='$name', meeting_type='$meetingType',
                date='$date', locality_key='$locality', note='$note', guests_count=$guestsCount,
                list_count=$listCount, saints_count=$saintsCount, members = '$members',
                trainees_count=$traineesCount, fulltimers_count=$fulltimersCount, participants='$attendMembers' WHERE id='$meetingId' ");
    }
    else {

        db_query("INSERT INTO meetings (meeting_type, date, locality_key, note,
                                        guests_count, list_count,
                                        saints_count, trainees_count, fulltimers_count, name, members, participants )
                VALUE ('$meetingType', '$date', '$locality', '$note', $guestsCount,
                         $listCount, $saintsCount, $traineesCount, $fulltimersCount, '$name', '$members', '$attendMembers' )");
    }

    return false;
}

function checkDoubleMeeting($date, $locality, $meetingType, $name){
    $res = db_query("SELECT * FROM meetings WHERE date='$date' AND meeting_type='$meetingType' AND name='$name' AND locality_key='$locality'");

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
    db_query('SET Session group_concat_max_len=100000');

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


function db_removeMeeting($meetingId){
    global $db;
    $_meetingId = $db->real_escape_string($meetingId);

    db_query("DELETE FROM  meetings WHERE id='$_meetingId' ");
}


function db_getMeetingTemplates($memberId){
    global $db;
    $_memberId = $db->real_escape_string($memberId);
    db_query('SET Session group_concat_max_len=100000');

    $templates = [];

    $res = db_query("SELECT mt.id, me.key as meeting_type, me.name as meeting_name,
                    mt.name as template_name, l.key as locality_key, l.name as locality_name,
                    (SELECT GROUP_CONCAT(CONCAT_WS(':', m.key, m.name, lo.name, m.attend_meeting, m.category_key, m.birth_date)  ORDER BY m.name ASC SEPARATOR  ',') FROM member m INNER JOIN locality lo ON m.locality_key=lo.key WHERE FIND_IN_SET(m.key, mt.participant)<>0) as participants,
                    (SELECT GROUP_CONCAT( CONCAT_WS(':', m.key, m.name, lo.name, m.attend_meeting, m.category_key, m.birth_date) ORDER BY m.name ASC SEPARATOR  ',') FROM member m INNER JOIN locality lo ON m.locality_key=lo.key WHERE FIND_IN_SET(m.key, mt.admin)<>0) as admins
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
    db_query('SET Session group_concat_max_len=100000');

    $res = db_query(
        "SELECT mt.id, mt.name as meeting_name, mt.meeting_type_key, mt.locality_key,
        (SELECT GROUP_CONCAT( CONCAT_WS(':', m.key, m.name, l.name, m.attend_meeting, m.category_key, m.locality_key, m.birth_date) ORDER BY m.name ASC SEPARATOR ',') FROM member m INNER JOIN locality l ON l.key=m.locality_key WHERE FIND_IN_SET(m.key, mt.participant)<>0) as participants,
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
    db_query('SET Session group_concat_max_len=100000');

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
    db_query('SET Session group_concat_max_len=100000');

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

        setMeetingByTemplate(['saints_count' => 0, 'trainees_count' => 0,
            'fulltimers_count' => 0,
            'list_count' => 0, 'children_count' => 0, 'members' => $members,
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

function db_getParticipantsForMeetingByMember($text){
    global $db;
    $text = $db->real_escape_string($text);

    if($text == ''){
        return false;
    }

    $res = db_query(
        "SELECT DISTINCT m.name, m.key as id, l.name as locality, m.attend_meeting, m.category_key
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


// get details of members
function db_getDetailsOfMembers($meetingId){
  global $db;
  $meetingId = $db->real_escape_string($meetingId);
  $members = array ();
  db_query('SET Session group_concat_max_len=100000');

  $res = db_query("SELECT DISTINCT * FROM (
          SELECT me.id as meeting_id, me.date, l.name as locality_name, me.locality_key,
          (SELECT GROUP_CONCAT( CONCAT_WS(':', mb.key, mb.name, lo.name, mb.attend_meeting, mb.category_key, mb.locality_key, mb.birth_date) ORDER BY mb.name ASC SEPARATOR ',') FROM member mb INNER JOIN locality lo ON lo.key=mb.locality_key WHERE FIND_IN_SET(mb.key, me.members)<>0) as members
          FROM meetings me
          INNER JOIN locality l ON l.key = me.locality_key
          WHERE me.id=$meetingId
          ) q ORDER BY q.locality_name ASC");

    while ($row = $res->fetch_object()) $members[]=$row;
    return $members;
}

/*
function db_getDetailsOfAllMembersAdmin ($adminId)
{
    global $db;
    $adminId = $db->real_escape_string($adminId);
    $active = 'name DESC, ';

    $res=db_query ("SELECT DISTINCT * FROM (SELECT m.key as id, m.name as name, IF (COALESCE(l.name,'')='', m.new_locality, l.name) as locality, m.email as email, m.locality_key, m.birth_date,
                    m.category_key, m.attend_meeting,
                    (SELECT rg.name FROM region rg WHERE rg.key=l.region_key) as region,
                    (SELECT co.name FROM country co INNER JOIN region re ON co.key=re.country_key WHERE l.region_key=re.key) as country
                    FROM access as a
                    LEFT JOIN country c ON c.key = a.country_key
                    LEFT JOIN region r ON r.key = a.region_key OR c.key=r.country_key
                    INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
                    INNER JOIN member m ON m.locality_key = l.key
                    LEFT JOIN category ca ON ca.key = m.category_key
                    WHERE a.member_key='$adminId'
                    UNION
                    SELECT m.key as id, m.name as name, IF (COALESCE(m.locality_key,'')='', m.new_locality, m.name) as locality, m.email as email, m.locality_key, m.birth_date,
                    m.category_key, m.attend_meeting,
                    '' as region,
                    '' as country
                    FROM member m
                    LEFT JOIN category ca ON ca.key = m.category_key
                    WHERE m.admin_key='$adminId' and m.locality_key is NULL
                    ) q ORDER BY '$active'");

    $members = array ();
    while ($row = $res->fetch_object()) $members[]=$row;
    return $members;
}
*/
?>
