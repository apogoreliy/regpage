<?php
include_once "ajax.php";

if (isset ($_GET ['invited'])){
    header("Content-Type: text/plain; charset=utf-8");
    $error = false;
    $memberId = $_GET ['member'];
    $eventId = $_GET ['event'];

    if(db_getEventMember($memberId, $eventId) == null){
        $event=db_getEvent ($eventId);
        if ($event){
            $member= db_getMember ($memberId);
            if($member){
                echo json_encode(array ("eventmember"=>db_getEventMemberInvited($eventId, $memberId)));
            }
            else{
                $error = "Неизвестный участник";
            }
        }
        else{
            $error = "Неизвестное мероприятие";
        }
    }
    else{
        $error = "Данный участник уже регистрировался";
    }

    if($error){
        header("HTTP/1.0 400 Bad Request");
        echo $error;
    }
    exit;
}

if (isset ($_GET ['cancel']))
{
    header("Content-Type: text/plain; charset=utf-8");
    db_unregisterMembers ($_POST ['member'], $_POST ['event'], array ($_POST ['member']));
    exit;
}
if (isset ($_GET ['restore']))
{
    header("Content-Type: text/plain; charset=utf-8");
    db_registerMembers ($_POST ['member'], $_POST ['event'], array ($_POST ['member']));
    exit;
}
if (isset ($_POST ['event'])){
    $adminId = db_getMemberIdBySessionId (session_id());
    $memberId=db_setEventMember ($adminId ? $adminId : '', $_GET, $_POST);
    $link = db_getPermalink ($memberId, $_POST ['event']);
    $messages = db_getEventMessages ($_POST ['event']);
    echo json_encode(array ("permalink"=>$link, "messages"=>$messages));
    exit;
}
if (isset ($_GET ['eventId']))
{
    $info=db_getGuestEvent ($_GET ['eventId']);
    if ($info)
    {
        echo json_encode(array ("info"=>$info));
        exit;
    }
    else
        $error = "Неизвестное мероприятие";
}
if (isset ($_GET ['msg_privat'])) {
  echo json_encode(db_getMsgParamPrivate());
  exit;
}
if (isset ($_GET ['link']))
{
    $member = db_getEventMemberByLink ($_GET ['link']);
    if ($member)
    {
        echo json_encode(array ("eventmember"=>$member, "localities"=>db_getLocalities()));
        exit;
    }
    else
        $error = "Бланк участника не найден";
}
else
    $error = "Неверные параметры запроса";

header("Content-Type: text/plain; charset=utf-8");
header("HTTP/1.0 400 Bad Request");
echo $error;

?>
