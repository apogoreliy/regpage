<?php

include_once "ajax.php";

$adminId = db_getMemberIdBySessionId (session_id());

if (isset ($_GET['sort_field'])){
    $_SESSION['sort_field_event']=$_GET ['sort_field'];
    $sort_field = $_GET ['sort_field'];
}
else if (isset ($_SESSION['sort_field_event']))
    $sort_field = $_SESSION['sort_field_event'];
else
    $sort_field = 'regend_date';

if (isset ($_GET['sort_type'])){
    $_SESSION['sort_type_event']=$_GET ['sort_type'];
    $sort_type = $_GET ['sort_type'];
}
else if (isset ($_SESSION['sort_type_event']))
    $sort_type = $_SESSION['sort_type_event'];
else
    $sort_type = 'asc';

if(isset ($_GET['get_events'])){
    echo json_encode(["events"=> db_getEventsForEventsPage($adminId, $sort_type, $sort_field)
        //"localities"=>db_getEventsLocalities(),
        //"authors"=>db_getEventsAuthors(),
        ]);
    exit;
}
elseif(isset ($_GET['get_guest_event'])){
    echo json_encode(["event"=> db_getGuestEvent($_POST['eventId'])]);
    exit;
}
elseif(isset ($_GET['members_for_registration'])){
    echo json_encode(["members"=> db_getMembersForRegistration($_POST['text'])]);
    exit;
}

if (!$adminId)
{
    header("HTTP/1.0 401 Unauthorized");
    exit;
}

if(isset ($_GET['handle_event'])){
    db_handleEvent($_POST['name'], $_POST['locality'], $adminId, $_POST['start_date'], $_POST['end_date'], 
        $_POST['reg_end_date'], $_POST['passport'], $_POST['prepayment'], $_POST['private'], $_POST['transport'], 
        $_POST['tp'], $_POST['flight'], $_POST['info'], $_POST['reg_members'], $_POST['reg_members_email'], 
        isset($_GET['teamKey']) ? $_GET['teamKey'] : null,
        isset($_GET['eventId']) ? $_GET['eventId'] : null, $_POST['event_type'], $_POST['zones'], 
        $_POST['parking'],$_POST['service'],$_POST['accom'], $_POST['close_registration'], $_POST['participants_count']);
    echo json_encode(["result"=> "ok"]);
    exit;
}
elseif (isset ($_GET ['check_stop_reg'])){        
    echo json_encode(["res" => db_checkEventStopRegistration ($_POST['eventId'])]);
    exit;
}
elseif(isset ($_GET['remove_event'])){
    if(!checkIfEventHasParticipants($_POST['eventId'])){
        db_removeEvent($_POST['eventId'], $adminId);
        echo json_encode(["result"=> "ok"]);
    }
    else{
        echo json_encode(["result"=> "Вы не можете удалить данное мероприятие, поскольку есть добавленные пользователи"]);
    }
    exit;
}
elseif(isset($_GET['get_member_event'])){
    if($adminId && isset($_POST['eventId'])){
        echo json_encode(["event"=> db_getGuestEvent($_POST['eventId']), "member"=> db_getEventMember ($adminId, $_POST['eventId']), "member_info"=> db_getEventMemberInfo ($adminId)]);
    }  
    else{
        echo json_encode(["error"=> "ok"]);
    }
    exit;
}
elseif(isset ($_GET['get_event'])){
    echo json_encode(["event"=> db_getEvent($_POST['eventId'])]);
    exit;
}
elseif(isset ($_GET['set_activity'])){
    db_setEventActivity($_POST['eventId'], $_POST['isActive'], $adminId);    
}
elseif(isset($_GET['set_archive'])){
    if(isset($_POST['eventId'])){
        echo json_encode(["res"=> db_setEventArchive($_POST['eventId'], $adminId)]);        
    }  
}
elseif(isset ($_GET['get_zones'])){
    echo json_encode(["zones"=> db_getZonesForEvent($_POST['text'], $_POST['field'])]);
    exit;
}
elseif(isset ($_GET['get_participants'])){
    echo json_encode(["participants"=> db_getParticipantsForEvent($_POST['text'], $_POST['field'])]);
    exit;
}
elseif (isset ($_GET ['eventIdReject'])){
    db_rejectMemberRegistration ($adminId, $_GET['eventIdReject']);
    
    echo json_encode(["member"=>"ok"]);
    exit;
}
elseif (isset ($_GET ['eventIdRegistration'])){
    $member = isset($_GET['edit_registration']) ? db_getEventMember ($adminId, $_GET['eventIdRegistration']) : db_getMemberMain ($adminId, $_GET['eventIdRegistration']);
    if ($member){
        echo json_encode(array ("eventmember"=>$member, 
            "localities"=>db_getLocalities()));
        exit;
    }
    else{
        $error = "Бланк участника не найден";
    }
}

if(isset($error)){
    header("HTTP/1.0 400 Bad Request");
    echo $error;
}
else{
    echo json_encode(["events"=> db_getEventsForEventsPage($adminId, $sort_type, $sort_field),
        "localities"=>db_getEventsLocalities(),
        "authors"=>db_getEventsAuthors()]);
    exit;
}