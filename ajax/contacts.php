<?php
// Ajax
include_once "ajax.php";
include_once "../db/contactsdb.php";
include_once '../logWriter.php';
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

if(isset($_POST['type']) && $_POST['type'] === 'delete_contact'){
    db_deleteContactString($_POST['delete_contacts_id'], $adminId);
    exit();
}

if(isset($_POST['type']) && $_POST['type'] === 'responsible_set'){
    db_responsibleSet($_POST['id'], $_POST['responsible'], $adminId);
    exit();
}

if(isset($_POST['type']) && $_POST['type'] === 'responsible_set_zero'){
    db_responsibleSetZero($_POST['data'], $adminId);
    exit();
}

if(isset($_GET['add_crm_id'])){
    db_crmIdSet($_GET['crm_id'], $_GET['id'], $adminId, $_GET['text'], $_GET['comment'],$_GET['notes']);
    exit();
}

// Statistic status
if(isset($_GET['add_history_status'])){
    db_addStatusHistoryStr($_GET['status']);
    exit();
}

// CHAT
if(isset($_GET['get_messages'])){
    echo json_encode(["messages"=>db_getChatMessages($adminId,$_GET['id'])]);
    exit();
}

if(isset($_GET['new_message'])){
    echo json_encode(["messages"=>db_newChatMsg($adminId, $_GET['data'], $_GET['list'])]);
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
if (isset($_POST['type']) && $_POST['type'] === 'message_ua')
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

// notification
if(isset($_GET['set_notice'])){
    db_newNotification($_GET['admin'],$_GET['contact']);
    exit();
}

if(isset($_GET['delete_notices'])){
    db_deleteNotification($_GET['id']);
    exit();
}

// set status multiple
if(isset($_POST['type']) && $_POST['type'] === 'set_status_multiple'){
    db_statusMultipleSet($_POST['contact_id'], $_POST['new_status']);
    exit();
}

?>
