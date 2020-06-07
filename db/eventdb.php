<?php
// DATA BASE QUERY

// Event $ ARCHIVE

function db_setEventArchive($eventId, $adminId, $isAdmin, $isSysAdmin){
    global $db;
    $eventId = $db->real_escape_string($eventId);
    $adminId = $db->real_escape_string($adminId);
    $isAdmin = $db->real_escape_string($isAdmin);
    $isSysAdmin = $db->real_escape_string($isSysAdmin);

    $res = db_query("SELECT COUNT(*) as count FROM reg WHERE member_key LIKE '990%' and event_key='$eventId' ");
    $row = $res->fetch_assoc();
    $mes = db_query("SELECT COUNT(*) as count FROM event_archive WHERE event_id='$eventId' ");
    $mow = $mes->fetch_assoc();
    if($row['count'] > 0 || $mow['count'] > 0){
        return false;
    }
    else{
        db_query('SET Session group_concat_max_len=100000');

        $res = db_query("SELECT e.event_type, e.name, e.start_date, e.end_date, e.locality_key, e.author, e.contrib, e.currency, e.key,
                GROUP_CONCAT(r.member_key) as members,
                (SELECT GROUP_CONCAT(re.member_key) FROM reg re WHERE re.event_key=e.key AND re.coord<>0 AND re.attended=1) as coordinators,
                (SELECT GROUP_CONCAT(CONCAT_WS(':',re.service_key, re.member_key)) FROM reg re WHERE re.event_key=e.key AND re.service_key IS NOT NULL AND re.service_key<>'' AND re.attended=1) as service_key,
                (SELECT GROUP_CONCAT(re.member_key) FROM reg re WHERE re.event_key=e.key AND re.service_key IS NOT NULL AND re.service_key<>'' AND re.attended=1) as service_ones,
                (SELECT GROUP_CONCAT(eac.member_key) FROM event_access eac WHERE eac.key=e.key) as responsibles
                FROM event e
                INNER JOIN reg r ON r.event_key=e.key
                WHERE e.key='$eventId' AND (e.author='$adminId' OR '$isAdmin' = '1' OR '$isSysAdmin' = '1') AND r.attended=1 GROUP BY e.key");

        $archive = NULL;
        while($row = $res->fetch_assoc()) $archive = $row;

        // set different info into event_archive.props field
        if($archive !== NULL){
            db_query("INSERT INTO event_archive
                (event_type, name, created, locality_key, start_date, end_date, author, members, coordinators, service_key, service_ones, members_count, contrib, currency, event_id)
                VALUES ('".$archive['event_type']."', '".(explode('(', $archive['name'])[0])."', NOW(), '".$archive['locality_key']."', '".$archive['start_date']."', '".$archive['end_date']."', '".$archive['author']."','".$archive['members']."', '".$archive['coordinators']."', '".$archive['service_key']."', '".$archive['responsibles']."', '".(count(explode(',',$archive['members'])))."', '".$archive['contrib']."', '".$archive['currency']."', '".$archive['key']."')");

            db_query("UPDATE event SET archived=1 WHERE `key`='$eventId'");
            db_query("DELETE FROM message WHERE `event_key`='$eventId'");
            return true;
        }
    }
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
    $eventAdmins = $data['eventAdmins'];
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
    $reg_members = $db->real_escape_string($data['regMembers']);
    $team_key = $db->real_escape_string($data['teamKey']);
    $eventContrib = $db->real_escape_string($data['contrib']);
    $eventCurrency = $db->real_escape_string($data['currency']);
    if($name == '' || $eventType == '_none_' || $eventLocality == '_none_' || $eventStartDate == '' || $eventEndDate == ''){
        throw new Exception("Необходимо заполнить все поля выделенные розовым цветом.", 1);
    }
    else{

        if($registration === '1'){

            db_handleEvent ($name, $eventLocality, $adminId, $eventStartDate, $eventEndDate, $regEndDate, $passport,
            $prepayment, $private, $transport, $tp, $flight, $eventInfo, $reg_members, $eventAdminsEmail,
            $team_key, null, $eventType, $zones, $parking, $service, $accom, 'event_', '1');
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

        db_query("INSERT INTO event_archive (name, event_type, created, locality_key, author, members, start_date, end_date, members_count, service_ones, contrib, currency) VALUES ('$name', '$eventType', now(), '$eventLocality', '$adminId', '". implode(',', $participantsArr) ."', '$eventStartDate', '$eventEndDate', '".count($participantsArr)."', '". implode(',', $eventAdmins) ."', '$eventContrib', '$eventCurrency')");

        $eventArchiveId = $db->insert_id;

        handleAdminsInEventAccessTable($eventArchiveId, $eventAdmins, 'event_archive');
        addZonesForEvent($eventArchiveId, $zones, 'event_archive');
    }
}

function db_getArchiveEvents($sort_type, $sort_field, $startDate, $endDate){
    global $db;
    $startDate = $db->real_escape_string($startDate);
    $endDate = $db->real_escape_string($endDate);
    $sort_type = $db->real_escape_string($sort_type);
    $sort_field = $sort_field != 'locality_key' ? $db->real_escape_string($sort_field)."" : " locality_name ";

    $res = db_query("SELECT ea.id, ea.name, l.key as locality_key, l.name as locality_name,
                ea.event_type as event_type, ea.members_count, ea.start_date, ea.created as date_created, ea.service_ones, ea.service_key as services, ea.coordinators, ea.event_id
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
    db_query('SET Session group_concat_max_len=100000');

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
    db_query('SET Session group_concat_max_len=100000');

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
?>
