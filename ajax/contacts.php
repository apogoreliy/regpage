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
    db_crmIdSet($_GET['crm_id'], $_GET['id'], $adminId, $_GET['text'], $_GET['comment'],$_GET['notes']);
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
    db_updateChatMsg($_GET['id'], $_GET['text']);
    exit();
}

if(isset($_GET['delete_message'])){
    db_deleteChatMsg($_GET['id']);
    exit();
}

//EMAIL TO UKRAINE
if (isset ($_POST['text_message']))
{
    $email = getValueParamByName('crm_ua_email');
    //$adminId = db_getMemberIdBySessionId (session_id());
    $error = null;
    $message = stripslashes($_POST['text_message']);
    if ($email){
            $res = EMAILS::sendEmail ($email, "Новый заказ с сайта регистрации", $message);
            if($res != null){
                $error = $res;
            }
    }
    else {
        $error = "Сообщение не может быть послано, т.к. адрес не определен";
    }
    if($error == null){
        echo json_encode(["result"=>true]);
        exit;
    }
}

?>
