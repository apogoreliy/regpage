<?php
include_once "ajax.php";

if (isset ($_GET ['event_info']))
{
    echo json_encode(array ("event_info"=>db_getEventInfo ($_GET ['event_info']), "event_name"=>db_getEventName ($_GET ['event_info'])));
    exit;
}
elseif (isset ($_GET ['event_invitation']))
{
    echo json_encode(array ("event_invitation"=>db_getEventInvitation($_GET ['event_invitation'])));
    exit;
}

$adminId = db_getMemberIdBySessionId (session_id());
if (!$adminId)
{
    header("HTTP/1.0 401 Unauthorized");
    exit;
}
elseif(isset($_GET['get_users_to_invite'])){
    echo json_encode(["users"=> db_getMembersToInvite($_POST['name'])]);
    exit();
}
elseif(isset($_GET['check_changes'])){
    echo json_encode(["result"=> db_checkIfEventMemberFieldsHasDifference($adminId, $_GET['dataFields'], $_GET['member'], $_GET['event'])]);
    exit();
}
elseif(isset($_GET['check_password'])){
    echo json_encode(["result"=> isset($_POST['pass']) ? db_isPasswordValid($adminId, $_POST['pass']) : false]);
    exit();
}
elseif(isset($_GET['get_team_email'])){
    echo json_encode(["email"=> isset($_POST['eventId']) ? db_getTeamEmail($_POST['eventId']) : null]);
    exit();
}
elseif(isset($_GET['get_team_email_event'])){
    echo json_encode(["email"=> isset($_POST['eventId']) ? db_getTeamEmailFromEvents($_POST['eventId']) : null]);
    exit();
}
elseif(isset($_GET['get_user_emails'])){
    echo json_encode(["emails"=> db_getUserEventEmails($_POST['memberId'], $_POST['eventId'])]);
    exit();
}
elseif(isset($_GET['check_admin_resp']) && isset ($_POST['eventId']) ){
    echo json_encode(["result"=> db_isAdminRespForReg($adminId, $_POST['eventId'])]);
    exit();
}
elseif(isset($_GET['check_event']) && isset($_POST['event'])){
    echo json_encode(["result"=>db_checkIfEventCreatedFromSite($adminId, $_POST['event']),
        "service"=>db_checkIfAdminHasServiceRightEvent($adminId, $_POST['event']),
        "event" => db_getEvent($_POST['event'])]);
    exit();
}
elseif(isset($_GET['get_member_localities'])){
    echo json_encode(["localities"=>db_getAdminLocalities ($adminId)]);
    exit();
}
elseif(isset($_GET['get_member_localities_Not_Reg_Tbl'])){
    echo json_encode(["localities"=>db_getAdminLocalitiesNotRegTbl ($adminId)]);
    exit();
}
elseif(isset($_GET['get_localities'])){
    echo json_encode(["localities"=>db_isAdminRespForReg($adminId, $_POST['event']) ? db_getLocalities() : db_getAdminLocalitiesNotRegTbl ($adminId)]);
    exit();
}
elseif(isset($_GET['get_members'])){
    echo json_encode([
        "members"=> db_getAdminActiveMembers(
                db_isAdminRespForReg($adminId, $_POST['event']) ? null : $adminId,
                isset($_POST['locId']) ? $_POST['locId'] : null,
                isset($_POST['catId']) ? $_POST['catId'] : null,
                isset($_POST['text']) ? $_POST['text'] : null,
                $_POST['event'])
    ]);
    exit();
}
/*
elseif(isset ($_GET['eventIdStat'])){
    echo json_encode([
        "stats"=> db_getStatistic($_GET['eventIdStat'], isset($_GET['access']) ? $_GET['access']: null,  $adminId),
        "localities" => db_isAdminRespForReg($adminId, $_GET['eventIdStat']) ? db_getEventLocalities ($_GET['eventIdStat'], true): db_getAdminEventLocalities ($_GET['eventIdStat'], $adminId)
    ]);
    exit;
}
*/
elseif (isset ($_GET ['member']) && isset ($_GET ['event']))
{
    $member = db_getEventMember ($_GET ['member'], $_GET['event']);

    if ($member)
    {
        echo json_encode(array (
            "eventmember"=>$member,
            "localities"=>db_isAdminRespForReg($adminId, $_GET['event']) ? db_getLocalities() : db_getAdminLocalitiesNotRegTbl ($adminId) ));
        exit;
    }
    else{
        $error = "Бланк участника не найден";
    }
}
elseif (isset ($_GET ['member']))
{
    $member = db_getMember ($_GET ['member']);
    if ($member)
    {
        echo json_encode(array (
            "member"=>$member,
            "localities"=>db_getAdminLocalitiesNotRegTbl ($adminId)));
        exit;
    }
    else
        $error = "Участник не найден";
}
elseif (isset ($_POST ['name']))
{
    echo json_encode(array ("members"=>db_checkName ($_POST ['name'])));
    exit;
}
elseif (isset ($_GET ['memberCheck']) && isset ($_GET ['event']))
{
    $member = db_getEventMemberCheck ($_GET ['memberCheck'], $_GET ['event']);

    if ($member)
    {
        echo json_encode(array (
            "eventmember"=>$member,
            "localities"=>db_isAdminRespForReg($adminId, $_GET['event']) ? db_getLocalities() : db_getAdminLocalities ($adminId)));
        exit;
    }
    else{
        $error = "Бланк участника не найден";
    }
}
elseif (isset ($_GET ['eventIdAid'])) {
    echo json_encode(["members"=>db_getAidStatistic ($_GET ['eventIdAid'], isset($_GET['scope']) ? $_GET['scope'] : null , $adminId)]);
    exit;
}
elseif(isset($_GET['event_statistic'])){
    echo json_encode(["statistic"=>db_getEventAdditionalStaistic ($_POST ['eventId'], $adminId)]);
    exit;
}

if(isset($_GET['get_regstate'])){
    echo json_encode(["regstate"=>db_checkRegState ($_POST ['memberKey'], $_POST['eventAdmin'])]);
    exit();
}

if (isset ($_GET ['eventId']))
{
    $info=db_getEventToCreateNewMember ($_GET ['eventId']);
    if ($info)
    {
        echo json_encode(array (
            "info"=>$info,
            "localities"=>db_isAdminRespForReg($adminId, $_GET['eventId']) ? db_getLocalities() : db_getAdminLocalities ($adminId)));
        exit;
    }
    else
        $error = "Неизвестное мероприятие";
}
else
    $error = "Неверные параметры запроса";

header("HTTP/1.0 400 Bad Request");
echo $error;
