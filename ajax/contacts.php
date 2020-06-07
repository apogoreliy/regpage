<?php
// Ajax
include_once "ajax.php";
include_once "../db/contactsdb.php";

$adminId = db_getMemberIdBySessionId (session_id());

if (!$adminId)
{
    header("HTTP/1.0 401 Unauthorized");
    exit;
}

if(isset($_GET['get_contacts'])){
    echo json_encode(["contacts"=>db_getContactsStrings($adminId, $_GET['role'])]);
    exit();
}

if(isset($_GET['get_contact'])){
    echo json_encode(["contact"=>db_getContactString($_GET['id'])]);
    exit();
}

if(isset($_GET['new_update_contact'])){
    echo json_encode(["id"=>db_newOrUpdateContactString($adminId, $_GET['data'])]);
    exit();
}

if(isset($_GET['delete_contact'])){
    db_deleteContactString($_GET['id']);
    exit();
}

if(isset($_GET['responsible_set'])){
    db_responsibleSet($_GET['id'], $_GET['responsible'], $adminId);
    exit();
}

if(isset($_GET['add_crm_id'])){
    db_crmIdSet($_GET['crm_id'], $_GET['id'], $adminId, $_GET['text'], $_GET['comment']);
    exit();
}

// CHAT
if(isset($_GET['get_messages'])){
    echo json_encode(["messages"=>db_getChatMessages($adminId,$_GET['id'])]);
    exit();
}

if(isset($_GET['new_message'])){
    db_newChatMsg($adminId, $_GET['data']);
    exit();
}

if(isset($_GET['update_message'])){
    db_newChatMsg($adminId, $_GET['data']);
    exit();
}

/*
//EMAIL TO UKRAINE
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
*/

?>
