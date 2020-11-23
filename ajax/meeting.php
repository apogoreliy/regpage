<?php
include_once "ajax.php";
include_once "../db/meetingsdb.php";
$adminId = db_getMemberIdBySessionId (session_id());
if (!$adminId)
{
    header("HTTP/1.0 401 Unauthorized");
    exit;
}


if (isset ($_GET['sort_field']))
{
    $_SESSION['sort_field-meetings']=$_GET['sort_field'];
    $sort_field = $_GET ['sort_field'];
}
else
    $sort_field = 'date';

if (isset ($_GET['sort_type']))
{
    $_SESSION['sort_type-meetings']=$_GET['sort_type'];
    $sort_type = $_GET['sort_type'];
}
else{
    $sort_type = 'DESC';
}

if(isset($_GET['get_meetings'])){
    echo json_encode(["meetings"=>db_getMeetings($adminId, $sort_type, $sort_field, $_GET['localityFilter'], $_GET['meetingFilter'], $_GET['startDate'],$_GET['endDate'])]);
    exit();
}
if(isset($_GET['get_meetings_for_statistics'])){
    echo json_encode(["meetings"=>db_getMeetingsForStatistics($_GET['adminId'], $_GET['localityFilter'], $_GET['meetingFilter'], $_GET['startDate'],$_GET['endDate'])]);
    exit();
}
/*
else if(isset($_GET['get_member_details'])){
    echo json_encode(array("members"=>db_getDetailsOfAllMembersAdmin($adminId)));
    exit();
}
*/
else if(isset($_GET['get_member_details_meeting'])){
    echo json_encode(array("members"=>db_getDetailsOfMembers($_GET['meeting_id'])));
    exit();
}
else if(isset($_GET['get_list']) && isset($_POST['meeting_id'])){
    echo json_encode(["list"=>db_getMeetingMembersList($adminId, $_POST['meeting_id'], $_POST['locality'], $_POST['date'], $_POST['is_summary_meeting'], $_POST['meeting_type'])]);
    exit();
}
else if(isset ($_GET['set_list'])){
    db_setMeetingMembersList($_GET['list'], $_GET['meeting_id'], $_GET['children_count']);
    echo json_encode(["meetings"=>db_getMeetings($adminId, $sort_type, $sort_field, $_GET['localityFilter'], $_GET['meetingFilter'], $_GET['startDate'],$_GET['endDate'])]);
    exit();
}
else if(isset ($_GET['remove'])){
    db_removeMeeting($_POST['meeting_id']);
    echo json_encode(["meetings"=>db_getMeetings($adminId, $sort_type, $sort_field, $_GET['localityFilter'], $_GET['meetingFilter'], $_GET['startDate'],$_GET['endDate'])]);
    exit();
}
else if(isset($_GET['set_meeting'])){
    $isDoubleMeeting = db_setMeeting($_POST);

    echo json_encode(["meetings"=>db_getMeetings($adminId, $sort_type, $sort_field, $_GET['localityFilter'], $_GET['meetingFilter'], $_GET['startDate'],$_GET['endDate']),
                      "isDoubleMeeting"=>$isDoubleMeeting]);
    exit();
}
else if(isset ($_GET['get_members_statistic'])){
    echo json_encode(["list"=>getMeetingMembersStatistic($adminId, $_GET['meeting_type'], $_GET['locality'], $_GET['startDate'], $_GET['endDate']),
        "members_list_count"=>getCountMembersAdminsLocalities($adminId, $_GET['locality'])]);
    exit();
}
else if(isset ($_GET['get_general_statistic'])){
    echo json_encode(["list"=>db_getMeetings($adminId, 'desc', 'date', $_GET['locality'], $_GET['meeting_type'], $_GET['startDate'],$_GET['endDate'])]);
    exit();
}
else if(isset($_GET['show_extra_fields'])){
    echo json_encode(["show"=>checkIfLocationHasFulltimers($_POST['locality'])]);
    exit();
}
else if(isset($_GET['handle_template'])){
    db_handleTemplate($adminId, $_POST);
    echo json_encode(["templates"=>db_getMeetingTemplates($adminId)]);
    exit();
}
else if(isset($_GET['get_templates'])){
    echo json_encode(["templates"=>db_getMeetingTemplates($adminId)]);
    exit();
}
else if(isset($_GET['get_template'])){
    echo json_encode(["template"=>db_getMeetingTemplate($_POST['templateId'])]);
    exit();
}
elseif(isset ($_GET['get_template_participants'])){
    if($_POST['field'] == 'l'){
        $res = db_getParticipantsForMeetingByLocality($_POST['text']);
    }
    else{
        $res = db_getParticipantsForMeetingByMember($_POST['text']);
    }
    echo json_encode(["participants"=> $res]);
    exit;
}
elseif(isset ($_GET['delete_template'])){
    db_deleteMeetingTemplate($adminId, $_POST['templateId']);
    echo json_encode(["templates" => db_getMeetingTemplates($adminId)]);
    exit;
}
elseif(isset ($_GET['add_template_meeting'])){
    db_addTemplateMeeting($_POST['templateId']);

    echo json_encode(["meetings"=>db_getMeetings($adminId, $sort_type, $sort_field, $_GET['localityFilter'], $_GET['meetingFilter'], $_GET['startDate'],$_GET['endDate'])]);
    exit;
}
elseif(isset ($_GET['delete_participants'])){
    db_deleteTemplateParticipant($_POST['memberId'], $_POST['mode'], $_POST['templateId']);

    echo json_encode(["participants"=> db_getMeetingTemplateParticipantList($_POST['templateId'], $_POST['mode']),
                      "templates" => db_getMeetingTemplates($adminId)]);
    exit;
}
elseif(isset ($_GET['get_available_members'])){
    echo json_encode(["members"=> db_getAvailableMembersForMeeting($_POST['text'])]);
    exit;
}
elseif(isset ($_GET['add_participants'])){
    db_addTemplateParticipant($_POST['mode'], $_POST['participantId'], $_POST['templateId']);

    echo json_encode(["participants"=> db_getMeetingTemplateParticipantList($_POST['templateId'], $_POST['mode']),
                      "templates" => db_getMeetingTemplates($adminId)]);
    exit;
}
elseif(isset($_GET['get_locality_members'])){
    echo json_encode(["members" => db_getLocalityMembers($_POST['localityId'])]);
    exit;
}
elseif(isset ($_GET['get_member'])){
    echo json_encode(["members" => db_getMembersByLocality($_POST['localityId'])]);
    exit;
}
elseif(isset ($_GET['set_admins_to_template'])){
    echo json_encode(["result" => db_setAdminsToTemplate($_GET['templateId'], $_GET['admins'])]);
    exit;
}
