<?php
include_once "ajax.php";

if (isset ($_GET['sort_field']))
{
    $_SESSION['sort_field_'.$_GET ['event']]=$_GET ['sort_field'];
    $sort_field = $_GET ['sort_field'];
}
else if (isset($_GET ['event']) && isset ($_SESSION['sort_field_'.($_GET['event'])]))
    $sort_field = $_SESSION['sort_field_'.($_GET ['event'])];
else
    $sort_field = 'name';

if (isset ($_GET['sort_type']))
{
    $_SESSION['sort_type_'.$_GET ['event']]=$_GET ['sort_type'];
    $sort_type = $_GET ['sort_type'];
}
else if (isset($_GET ['event']) && isset ($_SESSION['sort_type_'.($_GET ['event'])]))
    $sort_type = $_SESSION['sort_type_'.($_GET ['event'])];
else
    $sort_type = 'asc';

if (isset ($_POST ['message']) && isset ($_POST ['event']) && isset ($_POST ['name']) && isset ($_POST ['email']) && !isset ($_POST['admins']))
{
    $email = db_getTeamEmail ($_POST ['event']);
    $adminId = db_getMemberIdBySessionId (session_id());
    $locality = db_getMemberLocality($adminId);
    $locality = $locality ? "\r\n\r\nНаселённый пункт отправителя: $locality" : "";
    $error = null;

    $message = stripslashes ($_POST ['message']).$locality;

    if ($email){
        $from_name = stripslashes ($_POST ['name']);
        $from_email = stripslashes ($_POST ['email']);
        $arrEmails = explode(',', $email);
        foreach ($arrEmails as $value) {
            $res = EMAILS::sendEmail ($value, "Сообщение с сайта reg-page.ru: ".($from_name), $message, $from_email);
            if($res != null){
                $error = $res;
            }
        }
    }
    else
        $error = "Сообщение не может быть послано, т.к. адрес команды регистрации не определен";

    if($error == null){
        echo json_encode(["result"=>true]);
        exit;
    }
}

if (isset ($_POST ['message']) && isset($_POST ['event']) && isset ($_POST ['name']) && isset ($_POST ['email']) && isset ($_POST['admins']))
{
    $email = db_getTeamAdmins();
    if($_POST ['event']!="") $infoEvent = db_getEventInfoForAdmins($_POST ['event']);
    else $infoEvent="";

    if (!isset ($_POST ['guest']))
    {
        $locality = db_getMemberLocality(db_getMemberIdBySessionId (session_id()));
        if ($locality) $locality = "\r\n\r\nНаселённый пункт отправителя: $locality";
    }
    else{
        $locality ="";
    }

    if ($email)
    {
        header("Content-Type: text/plain; charset=utf-8");
        $from_name = stripslashes ($_POST ['name']);
        $from_email = stripslashes ($_POST ['email']);
        // $headers = "From: $from_name<$from_email>\r\nReply-To: $from_name<$from_email>\r\n";
        $arrEmails = explode(',', $email);
        foreach ($arrEmails as $value) {
            EMAILS::sendEmail (stripslashes ($value), "Сообщение с сайта reg-page.ru - ".($from_name), (stripslashes ($_POST ['message'])).$locality."\n".$infoEvent."\n"."Страница: ".($_POST["admins"]), $from_email);
        }
        exit;
    }
    else
        $error = "Сообщение не может быть послано, т.к. адрес команды не определен";
}

if (!isset($error))
{
    $adminId = db_getMemberIdBySessionId (session_id());
    if (!$adminId)
    {
        header("HTTP/1.0 401 Unauthorized");
        exit;
    }
}

if(isset ($_GET['set_login'])){
    if(isset($_POST['login'])){
        if(UTILS::sendConfirmationEmailToChangeLogin($_POST['login'], $adminId)){
            db_logoutAdmin($adminId, session_id());
            echo json_encode(["result"=>true]);
        }
        else{
            echo json_encode(["result"=>false]);
        }
    }
    else{
        echo json_encode(["result"=>false]);
    }
    exit;
}
if(isset ($_GET['invite_users'])){
    if(isset($_POST['users'])){
        echo json_encode(["result"=> db_inviteUsersToEvent($_POST['showAdminName'], $adminId, $_POST['users'], $_POST['event'])]);
    }
    exit;
}
else if (isset ($_GET ['event']) && isset ($_POST ['send_to_members']) && isset ($_POST ['subject'])
    && isset ($_POST ['text']) && isset ($_POST ['from_name']) && isset ($_POST ['from_email']) )
{
    global $appRootPath;

    $eventId = $_GET ['event'];
    $type = $_POST ['type'];
    $subject = stripslashes ($_POST ['subject']);
    $text = str_replace("\n", '<br>', htmlspecialchars (stripslashes ($_POST ['text'])));
    $from_name = stripslashes ($_POST ['from_name']);
    $from_email = stripslashes ($_POST ['from_email']);
    //$headers  = 'MIME-Version: 1.0' . "\r\n";
    //$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    //$headers .= "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: $from_name<$from_email>\r\nReply-To: $from_name<$from_email>\r\n";
    foreach (preg_split("/,/", $_POST ['send_to_members'], -1, PREG_SPLIT_NO_EMPTY) as $memberId)
    {
        $reg = db_getEventMember($memberId, $eventId);
        if (!$reg['regstate_key'] || $type!='invitation'){
            if (strstr ($text, '{СсылкаНаБланк}')){
                $permalink = db_getPermalink($memberId, $eventId);
                $body = str_replace('{СсылкаНаБланк}', '<a href="'.$appRootPath.$permalink.'">'.$appRootPath.$permalink.'</a>', $text);
            }
            else{
                $body = $text;
            }

            $userEmail = db_getMemberNameEmail($memberId);
            db_enqueueLetter ($memberId, $eventId, $subject, $body, trim($userEmail[1]), $from_email, $from_name);
        }
    }
}
else if(isset ($_GET['set_state'])){
    db_setMembersRegstateEvent($adminId, $_GET['event'], $_GET ['memberId'], $_GET ['setstate']);
}
else if(isset($_GET['set_attended_members'])){
    db_setAttendedDismissMembersEvent($adminId, $_GET['event'], isset($_GET ['set_attended_members']) ? $_GET ['set_attended_members'] : null, isset($_GET['dismiss_attended_members']) ? $_GET ['dismiss_attended_members'] : null);
}
else if(isset($_GET['confirm_registration'])){
    db_confirmRegistrationBulkMembersEvent($adminId, $_GET['event'], isset($_GET['membersIds']) ? preg_split("/,/", $_GET ['membersIds'], -1, PREG_SPLIT_NO_EMPTY) : null);
}
else if (isset ($_GET ['member']) && isset ($_GET ['event']))
{
    db_setEventMember ($adminId, $_GET, $_POST);
}
else if (isset ($_GET ['members']) && isset ($_GET ['event']) && !isset($_GET['checkServ']))
{
    db_setEventMembers ($adminId, $_GET ['event'], preg_split("/,/", $_GET ['members'], -1, PREG_SPLIT_NO_EMPTY),
                        $_POST["arr_date"], $_POST["arr_time"], $_POST["dep_date"], $_POST["dep_time"],
                        $_POST["accom"], $_POST["transport"], isset($_POST["status"])? $_POST["status"] : NULL, $_POST["coord"], isset($_POST["service"])? $_POST["service"] : NULL,
                        isset($_POST["mate"])? $_POST["mate"] : NULL);
}
else if (isset ($_GET ['remove_members']) && isset ($_GET ['event'])){
    db_unregisterMembers ($adminId, $_GET ['event'], preg_split("/,/", $_GET ['remove_members'], -1, PREG_SPLIT_NO_EMPTY));
}
else if (isset ($_GET ['register_members']) && isset ($_GET ['event']))
{
    $invalid = db_registerMembers ($adminId, $_GET ['event'], preg_split("/,/", $_GET ['register_members'], -1, PREG_SPLIT_NO_EMPTY));
}
else if (isset ($_GET ['reg_new_members']) && isset ($_GET ['event']))
{
    db_registerNewMembers ($adminId, $_GET ['event'], preg_split("/,/", $_GET ['reg_new_members'], -1, PREG_SPLIT_NO_EMPTY));
}
else if (isset ($_GET ['members']) && isset ($_GET ['event']) && isset($_GET['checkServ'])){
    db_setEventMembersService ($adminId,  $_GET ['event'], preg_split("/,/", $_GET ['members'], -1, PREG_SPLIT_NO_EMPTY),
            $_POST['paid'], $_POST['place'], isset($_POST['attended']) ? $_POST['attended'] : null);
}
else if (isset ($_GET ['member_id']) && isset ($_GET ['amount']) && isset ($_GET ['eventId'])) {
    db_setUserAid($_GET ['member_id'], $_GET ['amount'], $_GET ['eventId']);
    echo json_encode(["members"=>db_getAidStatistic ($_GET ['eventId'], isset($_GET['scope']) ? $_GET['scope'] : null , $adminId)]);
    exit;
}
elseif (isset ($_GET ['set_profile'])) {
    db_setProfile ($adminId, $_POST["name"], $_POST["birth_date"],
        $_POST["cell_phone"], $_POST["locality_key"], $_POST["new_locality"],
        $_POST["gender"]=="male" ? 1 : ($_POST["gender"]=="female" ? 0 : null),
        $_POST["citizenship_key"], $_POST['notice_info'], $_POST['notice_reg']);
    echo json_encode(["res"=>"ok"]);

    exit();
}
elseif (isset ($_GET ['set_password'])) {
    if(isset($_POST['pass']) && db_isPasswordValid($adminId, $_POST['pass']) && isset($_POST['newPass']) && strlen($_POST['newPass']) >= 5){
        db_setUserPassword($adminId, $_POST['newPass'],session_id());
        echo json_encode(["result"=> true]);
    }
    else{
        echo json_encode(["result"=> false]);
    }

    exit();
}

if(isset($_GET ['event'])){
    if(db_isAdminRespForReg($adminId, $_GET ['event'])){
        echo json_encode(array ("members"=> db_getDashboardMembersService (
            $_GET ['event'],
            isset ($_GET['attended'])? $_GET['attended'] : NULL,
            NULL,
            $sort_field, $sort_type,
            isset ($_GET['searchText'])? $_GET['searchText'] : NULL,
            isset ($_GET['coord'])? $_GET['coord'] : NULL,
            isset ($_GET['service'])? $_GET['service'] : NULL,
            NULL),
            "invalid"=>isset($invalid) ? $invalid : null,
            "localities"=>db_getEventLocalities ($_GET['event'])));
    }
    else{
        echo json_encode(array ("members"=>db_getDashboardMembers ($adminId, $_GET ['event'], $sort_field, $sort_type, isset ($_GET['searchText'])? $_GET['searchText'] : NULL,
            NULL, NULL),
            "invalid"=>isset($invalid) ? $invalid : null,
            "localities"=>db_getAdminEventLocalities ($_GET['event'], $adminId)));
    }
    exit;
}
else if(!isset($error))
    $error = "Неверные параметры запроса";

header("HTTP/1.0 400 Bad Request");
header("Content-Type: text/plain; charset=utf-8");
echo $error;

?>
