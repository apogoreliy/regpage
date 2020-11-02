<?php
// DATA BASE QUERY
// CONTACTS
//include_once 'logWriter.php';
function db_getNewContactId ()
{
    $res=db_query ("SELECT `id` FROM contacts ORDER BY `id` DESC LIMIT 1");
    $row = $res->fetch_object();
    $key = "100000";
    if ($row && strlen($row->id)==6) $key = (string)($row->id + 1);
    return $key;
}

// Create or update a contact
function db_newOrUpdateContactString($memberId, $data){
  global $db;
  $memberId = $db->real_escape_string($memberId);
  $data['id'] ? $Id = $db->real_escape_string($data['id']) : $Id='';
  $data['name'] ? $name = $db->real_escape_string($data['name']) : $name='';
  $data['phone'] ? $phone = $db->real_escape_string($data['phone']) : $phone='';
  $data['locality'] ? $locality = $db->real_escape_string($data['locality']) : $locality='';
  $data['male'] ? $male = $db->real_escape_string($data['male']) : $male='';
  $data['status'] ? $status = $db->real_escape_string($data['status']) : $status='';
  $data['email'] ? $email = $db->real_escape_string($data['email']) : $email='';
  $data['responsible'] ? $responsible = $db->real_escape_string($data['responsible']) : $responsible='';
  $data['responsible_prev'] ? $responsiblePrev = $db->real_escape_string($data['responsible_prev']) : $responsiblePrev='';
  $data['area'] ? $area = $db->real_escape_string($data['area']) : $area='';
  $data['address'] ? $address = $db->real_escape_string($data['address']) :  $address = '';
  $data['comment'] ? $comment = $db->real_escape_string($data['comment']) : $comment='';
  $data['index'] ? $index =  $db->real_escape_string($data['index']) : $index ='';
  $data['region'] ? $region =  $db->real_escape_string($data['region']) : $region ='';
  $data['region_work'] ? $regionWork =  $db->real_escape_string($data['region_work']) : $regionWork ='';
  $data['country'] ? $countryKey =  $db->real_escape_string($data['country']) : $countryKey ='';
  $data['order_date'] ? $orderDate =  $db->real_escape_string($data['order_date']) : $orderDate = NULL;
  $newId = db_getNewContactId ();
  if ($Id) {
    db_query("UPDATE contacts SET `name`='$name', `phone`='$phone', `locality`='$locality', `male`='$male', `status`='$status', `email`='$email', `responsible`='$responsible', `responsible_previous`='$responsiblePrev', `area`='$area', `address`= '$address', `comment`= '$comment', `index_post` = '$index', `region` = '$region', `region_work` = '$regionWork', `country_key`='$countryKey', `order_date`='$orderDate' WHERE `id` = '$Id'");
    return 'update';
  } else {
    db_query("INSERT INTO contacts (`id`, `name`, `phone`, `locality`, `male`, `status`, `email`, `responsible`, `responsible_previous`, `area`, `address`, `comment`, `index_post`, `region`, `region_work`, `country_key`) VALUES ('$newId', '$name', '$phone', '$locality', '$male', '$status', '$email', '$responsible', '$responsiblePrev', '$area', '$address', '$comment', '$index', '$region', '$regionWork', '$countryKey')");

    $res=db_query ("SELECT `id`, `responsible`, `responsible_previous` FROM contacts ORDER BY `id` DESC LIMIT 1");
    $row = $res->fetch_object();
    $key = "";
    if ($row && strlen($row->id)==6) $key = (string)($row->id);
    return $key;
  }
}
// get contact
function db_getContactString ($id){
  global $db;
  $id = $db->real_escape_string($id);
  $result = [];

    $res=db_query ("SELECT c.id,c.time_stamp,c.name,c.phone,c.locality,c.male,c.status,c.email,c.responsible, c.responsible_previous,c.area,c.address,c.comment,c.index_post,c.region,c.region_work,c.country_key,c.order_date,
    c.sending_date, c.crm_id, m.name AS member_name, c.notice
    FROM contacts AS c
    INNER JOIN member m ON m.key = c.responsible
    WHERE c.id = '$id' AND c.notice <> 2");
    while ($row = $res->fetch_assoc()) $result[]=$row;

  return $result;
}
// Delete contacts strings
function db_deleteContactString($id, $adminId){
  foreach ($id as $value) {
    db_query ("UPDATE contacts SET `notice` = 2 WHERE `id`='$value'");
    logFileWriter($adminId, 'КОНТАКТЫ. Перемещён в корзину контакт '.$value);
  }
}
// Delete contacts strings from DATABASE
function db_deleteContactStringTotal($id, $adminId){
  foreach ($id as $value) {
    db_query ("DELETE FROM contacts WHERE `id`='$value'");
    db_query ("DELETE FROM chat WHERE `group_id`='$value'");
    logFileWriter($adminId, 'КОНТАКТЫ. Удалён из базы контакт '.$value);
  }
}
// responsible set
function db_responsibleSet($id, $responsibleNew, $adminId){
  global $db;
  //$id = $db->real_escape_string($id);
  $responsibleKey = $db->real_escape_string($responsibleNew[0]);
  $responsibleName = $db->real_escape_string($responsibleNew[1]);
  $text = 'Назначен ответственный: '.$responsibleName;
  foreach ($id as $value) {
    db_query ("UPDATE contacts SET `responsible` = '$responsibleKey', `responsible_previous` = '$value[1]' WHERE `id`='$value[0]'");
    db_query("INSERT INTO chat (`group_id`, `member_key`, `message`) VALUES ('$value[0]', '$adminId', '$text')");
    db_newNotification($responsibleKey, $value[0]);
    $textLog = 'КОНТАКТЫ. Контакт ID - '.$value[0].'. '.$text.'. Предыдущий '.$value[1].'.';
    logFileWriter($adminId, $textLog);
  }
}
// responsible set for admin 0
function db_responsibleSetZero($data, $adminId){
  global $db;
  $adminId = $db->real_escape_string($adminId);
  $text;
  foreach ($data as $value) {
    $text = 'Назначен ответственный '.$value[2];
    db_query ("UPDATE contacts SET `responsible` = '$value[1]', `responsible_previous` = '$adminId' WHERE `id`='$value[0]'");
    db_query("INSERT INTO chat (`group_id`, `member_key`, `message`) VALUES ('$value[0]', '$adminId', '$text')");
    db_newNotification($value[1], $value[0]);
    $textLog = 'КОНТАКТЫ. Контакт ID - '.$value[0].'. '.$text.'. Предыдущий '.$adminId.'.';
    logFileWriter($adminId, $textLog);
  }
}

// get the contact
function db_getContactsStrings($memberId, $role){
  global $db;
  $role = $db->real_escape_string($role);
  $memberId = $db->real_escape_string($memberId);
  $result = [];
  if ($role > 0) {
    $res=db_query ("SELECT c.id,c.time_stamp,c.name,c.phone,c.locality,c.male,c.status,c.email,c.responsible, c.responsible_previous,c.area,c.address,c.comment,c.index_post,c.region,c.region_work,c.country_key,c.order_date,
    c.sending_date, c.crm_id, c.project, m.name AS member_name, c.notice
    FROM contacts AS c
    INNER JOIN member m ON m.key = c.responsible
    WHERE (c.responsible_previous = '$memberId' OR c.responsible = '$memberId') AND c.notice <> 2 ORDER BY c.name");
    while ($row = $res->fetch_assoc()) $result[]=$row;
  } else {
    $res=db_query ("SELECT c.id,c.time_stamp,c.name,c.phone,c.locality,c.male,c.status,c.email,c.responsible, c.responsible_previous,c.area,c.address,c.comment,c.index_post,c.region,c.region_work,c.country_key,c.order_date,
    c.sending_date, c.crm_id, c.project, m.name AS member_name, c.notice
    FROM contacts AS c
    INNER JOIN member m ON m.key = c.responsible
    WHERE (c.responsible_previous = '$memberId' OR c.responsible = '$memberId') AND c.notice <> 2 ORDER BY c.name");
    while ($row = $res->fetch_assoc()) $result[]=$row;
  }
  return $result;
}

function db_getContactsStringsPrev($memberId, $contRole){
//WHERE c.responsible_previous = '$memberId'  AND c.notice <> 2 ORDER BY c.responsible");
  global $db;
  $memberId = $db->real_escape_string($memberId);
  $contRole = $db->real_escape_string($contRole);
  $previousAdmin = ' c.responsible_previous = '.$memberId;
  if ($contRole === '2') {
    $resultCheck = [];
    $resCheck=db_query ("SELECT `member_key` FROM contacts_resp WHERE `role` = '1'");
    while ($rowCheck = $resCheck->fetch_assoc()) $resultCheck[]=$rowCheck['member_key'];

    for ($i=0; $i < count($resultCheck); $i++) {
      $previousAdmin = $previousAdmin.' OR c.responsible_previous = '.$resultCheck[$i];
    }
  }
  //c.responsible_previous = '000005944' OR c.responsible_previous = '000001679'
  $result = [];
    $res=db_query ("SELECT c.id, c.status,c.responsible, m.name AS member_name
    FROM contacts AS c
    INNER JOIN member m ON m.key = c.responsible
    WHERE $previousAdmin AND c.notice <> 2 ORDER BY member_name");
    while ($row = $res->fetch_assoc()) $result[]=$row;

  return $result;
}

// set CRM ID
function  db_crmIdSet($idCRM, $id, $memberId, $text, $comment, $notes){
  global $db;
  $id = $db->real_escape_string($id);
  $idCRM = $db->real_escape_string($idCRM);
  $memberId = $db->real_escape_string($memberId);
  $text = $db->real_escape_string($text);
  $comment = $db->real_escape_string($comment);
  db_query("UPDATE contacts SET `crm_id` = '$idCRM', `order_date` = NOW(), `comment` = '$comment' WHERE `id` = '$id'");
  db_query("INSERT INTO chat (`group_id`, `member_key`, `message`) VALUES ('$id', '$memberId', '$text')");
  if ($notes) {
    $notes = $db->real_escape_string($notes);
    db_query("INSERT INTO chat (`group_id`, `member_key`, `message`) VALUES ('$id', '$memberId', '$notes')");
  }
}

// CHAT
// new message
function db_newChatMsg($memberId, $data, $list=false){
  global $db;
  $id = $db->real_escape_string($data['id']);
  $text = $db->real_escape_string($data['text']);
  $memberId = $db->real_escape_string($memberId);
  $memberId = $db->real_escape_string($memberId);
  db_query("INSERT INTO chat (`group_id`, `member_key`, `message`) VALUES ('$id', '$memberId', '$text')");
  if ($list) {
    $result = [];
    $res=db_query ("SELECT * FROM chat WHERE `group_id` = '$id' ORDER BY `time_stamp`");
    while ($row = $res->fetch_assoc()) $result[]=$row;

    return $result;
  }
  return false;
}
// update message
function db_updateChatMsg($id, $text){
  global $db;
  $id = $db->real_escape_string($id);
  $text = $db->real_escape_string($text);
  db_query("UPDATE chat SET `message` = '$text' WHERE `id` = '$id'");
}
// delete message
function db_deleteChatMsg($id){
  global $db;
  $id = $db->real_escape_string($id);
  $res = db_query("DELETE FROM chat WHERE `id`='$id'");
}

// get the messages
function db_getChatMessages($memberId, $idContacts){
  global $db;
  $memberId = $db->real_escape_string($memberId);
  $idContacts = $db->real_escape_string($idContacts);
  $result = [];
  $res=db_query ("SELECT * FROM chat WHERE `group_id` = '$idContacts' ORDER BY `time_stamp`");
  while ($row = $res->fetch_assoc()) $result[]=$row;

  return $result;
}
// get admin
function db_getAdminByLocalityCnt($locality_key){
    global $db;
    $locality = $db->real_escape_string($locality_key);

    $res = db_query ("
        SELECT DISTINCT m.key, m.name, a.login as email FROM member as m
        LEFT JOIN admin a ON a.member_key = m.key
        LEFT JOIN access ac ON ac.member_key = m.key
        LEFT JOIN country c ON c.key = ac.country_key
        LEFT JOIN region r ON r.country_key = c.key
        LEFT JOIN locality l ON l.region_key = r.key
        WHERE l.key='$locality' AND a.role>0
        UNION
        SELECT DISTINCT m.key, m.name, a.login as email FROM member as m
        LEFT JOIN admin a ON a.member_key = m.key
        LEFT JOIN access ac ON ac.member_key = m.key
        LEFT JOIN region r ON r.key = ac.region_key
        LEFT JOIN locality l ON l.region_key = r.key
        WHERE l.key='$locality' AND a.role>0
        UNION
        SELECT DISTINCT m.key, m.name, a.login as email FROM member as m
        LEFT JOIN admin a ON a.member_key = m.key
        LEFT JOIN access ac ON ac.member_key = m.key
        LEFT JOIN locality l ON ac.locality_key = l.key
        WHERE l.key='$locality' AND a.role>0 ");

    $admins = [];
    while ($row = $res->fetch_assoc()){
        $admins [] = $row;
    }
    return count ($admins) ? $admins : null;
}

// get region of the work
function db_getregionOfWork ()
{
    $res=db_query ("SELECT `id`, `region` FROM region_work ORDER BY `region`");

    $region = array ();
    while ($row = $res->fetch_assoc()) $region[$row['id']]=$row['region'];
    return $region;
}
// members - admins for combobox
function db_getAdminMembersAdmins ($adminId)
{
    global $db;
    $adminId = $db->real_escape_string($adminId);

    $res=db_query ("SELECT DISTINCT * FROM (
                        SELECT m.key as id, m.name as name, l.name as locality, l.key as locId, m.category_key as catId
                        FROM access a
                        LEFT JOIN country c ON c.key = a.country_key
                        LEFT JOIN region r ON r.key = a.region_key or c.key=r.country_key
                        INNER JOIN locality l ON l.region_key = r.key OR l.key=a.locality_key
                        INNER JOIN member m ON m.locality_key = l.key
                        WHERE a.member_key='$adminId'
                        UNION
                        SELECT m.key as id, m.name as name, COALESCE(l.name, m.new_locality) as locality,
                        l.key as locId, m.category_key as catId
                        FROM member m
                        LEFT JOIN locality l ON l.key=m.locality_key
                        WHERE m.admin_key='$adminId'
                        UNION
                        SELECT m.key as id, m.name as name, COALESCE(l.name, m.new_locality) as locality, l.key as locId,
                        m.category_key as catId
                        FROM reg
                        INNER JOIN member m ON m.key=reg.member_key
                        LEFT JOIN locality l ON l.key=m.locality_key
                        WHERE reg.admin_key='$adminId'
                        ) q ORDER BY q.name");

    $members = array ();
    while ($row = $res->fetch_assoc()){
      $names = split(' ', $row['name']);
      $twoName = $names[0].' '.$names[1];
        $members[$row['id']]=array (
            "name" => $twoName,
            "locality" => $row['locality'],
            "localityId" => $row['locId'],
            "categoryId" => $row['catId']
        );
      }
    $list=[];
      foreach ($members as $key => $value) {
        $result='';
        $res=db_query ("SELECT `login` FROM admin WHERE `member_key` = '$key'");
        while ($row = $res->fetch_assoc()) $result=$row['login'];

        if ($result) {
          $list[$key]=$value;
        }
      }
    return $list;
}

function db_addStatusHistoryStr($status)
{
  global $db;
  $status = $db->real_escape_string($status);
  db_query("INSERT INTO contacts_statistic (`date_changed`, `status`) VALUES (NOW(), '$status')");
}

function db_getMemberListAdminsForContacts ()
{
    $res=db_query ("SELECT a.member_key as id, m.name as name, m.locality_key as locality
        FROM admin as a
        INNER JOIN member m ON m.key = a.member_key
        ORDER BY m.name
        ");

    $members = array ();
    while ($row = $res->fetch_assoc()) $members[$row['id']]=$row['name'].'_'.$row['locality'];
    return $members;
}

// new notification
function db_newNotification($adminId, $contactId){
  global $db;
  $contactId = $db->real_escape_string($contactId);
  $adminId = $db->real_escape_string($adminId);

  db_query("UPDATE contacts SET `notice` = 1 WHERE `id` = '$contactId'");

}

// delete notification
function db_deleteNotification($id)
{
  global $db;
  $id = $db->real_escape_string($id);

  db_query("UPDATE contacts SET `notice` = 0 WHERE `id` = '$id'");
}

// responsible set
function db_statusMultipleSet($id, $statusNew){
  global $db;
  $statusNew = $db->real_escape_string($statusNew);
  foreach ($id as $value) {
    db_query ("UPDATE contacts SET `status` = '$statusNew' WHERE `id`='$value'");
    db_addStatusHistoryStr($statusNew);
  }
}

function db_getUniqueProjects ()
{
    $res=db_query ("SELECT DISTINCT project FROM contacts WHERE notice <> 2 ORDER BY project");

    $projects = array ();
    while ($row = $res->fetch_assoc()) $projects[]=$row['project'];
    return $projects;
}

function db_getRespCombobox ($memberId)
{
  global $db;
  $memberId = $db->real_escape_string($memberId);
  $res=db_query ("SELECT `group_of_admin` FROM contacts_resp WHERE `member_key` = '$memberId'");

  $resp='1';
  while ($row = $res->fetch_assoc()) $resp=$row['group_of_admin'];

  $resp2 = [];
  if ($resp !== '1') {
    $resp_arr = explode(',', $resp);

    for ($x = 0; $x <= count($resp_arr); $x++) {

      $xx = $resp_arr[$x];

      $res2=db_query ("SELECT `key`, `name` FROM member WHERE `key` = '$xx'");
      while ($row2 = $res2->fetch_assoc()) $resp2[$row2['key']]=$row2['name'];
    }
    return $resp2;
  }
  return $resp2;
}

// START responsibles group
function db_getAdminResponsiblesGroup ($adminId)
{
    $res=db_query ("SELECT `group_of_admin` FROM contacts_resp WHERE `member_key` = '$adminId'");

    $resp = '';
    //$resp_arr = [];
    while ($row = $res->fetch_assoc()) $resp=$row['group_of_admin'];
/*
    if ($resp) {
      $resp_arr = explode(',', $resp);
    }
*/
    return $resp;
}

function db_setRespForAdmin($adminId, $keys, $role){
  global $db;
  $adminId = $db->real_escape_string($adminId);
  $keys = $db->real_escape_string($keys);
  $role = $db->real_escape_string($role);
  $check = '0';
  $msg = '';
  $res=db_query ("SELECT `member_key` FROM contacts_resp WHERE `member_key` = '$adminId'");
  while ($row = $res->fetch_assoc()) $check=$row['member_key'];

  if ($check !== '0') {
    db_query ("UPDATE contacts_resp SET `group_of_admin` = '$keys' WHERE `member_key`='$adminId'");
// Добавить админа который управляет если это не сам пользователь делает
    $msg = 'Обновлён список ответственных у администратора - '.$keys;
  } else {
    db_query ("INSERT INTO contacts_resp (`member_key`, `role`, `group_of_admin`) VALUES ('$adminId', $role, '$keys')");
// Добавить админа который делает это
    $msg = 'Админом с ролью 2 добавлен новый администратор с ролью - '.$role.'. И списоком ответственных - '.$keys;
  }
  logFileWriter($adminId, 'КОНТАКТЫ. '.$msg);
}

function db_getContactsRoleAdmin($adminId){
  global $db;
  $adminId = $db->real_escape_string($adminId);
  $role = [];

  $res=db_query ("SELECT `role`, `group_of_admin` FROM contacts_resp WHERE `member_key` = '$adminId'");
  while ($row = $res->fetch_assoc()) $role=[$row['role'], $row['group_of_admin']];
  return $role;
}

function db_setUpdadteContactsRoleAdmin($adminId, $role){
  global $db;
  $adminId = $db->real_escape_string($adminId);
  $role = $db->real_escape_string($role);
  $check = '0';
  $msg = 'Администору ';

  $res=db_query ("SELECT `member_key` FROM contacts_resp WHERE `member_key` = '$adminId'");
  while ($row = $res->fetch_assoc()) $check=$row['member_key'];

if ($role === 'none' && $check !== '0') {
    db_query("DELETE FROM contacts_resp WHERE `member_key`='$adminId'");
    $msg = 'Администор удалён из таблицы ответственных за контакты, ';
} elseif ($check !== '0' && $role !== 'none') {
    db_query ("UPDATE contacts_resp SET `role` = '$role' WHERE `member_key`='$adminId'");
  } elseif ($check === '0' && $role !== 'none') {
    db_query ("INSERT INTO contacts_resp (`member_key`,`role`) VALUES ('$adminId','$role')");
  }
  logFileWriter($adminId, 'КОНТАКТЫ. '.$msg.'назначена роль '.$role);
}

// STOP responsibles group


/*
function logFileWriter2($logMemberId, $info)
{
  $logAdminName = db_getAdminNameById($logMemberId);
  $logAdminLocaity = db_getLocalityByKey(db_getAdminLocality($logMemberId));
  $logAdminCountry = db_getAdminCountry($logMemberId);
  $logAdminRole = db_getAdminRole($logMemberId);

  $file = 'logFile_'.date("d-m-Y").'.txt'; //
  //Добавим разделитель, чтобы мы смогли отличить каждую запись
  $text = 'DEBUG ==================================================='.PHP_EOL;
  $text .=  date('d-m-Y H:i:s') .PHP_EOL; //Добавим актуальную дату после текста или дампа массива
  $text .= 'Admin is '.$logAdminName.'. Key is '. $logMemberId.'. Role is '.$logAdminRole.'. '.$logAdminCountry.'. '. $logAdminLocaity.'. '.PHP_EOL;
  $text .= $info.PHP_EOL.PHP_EOL;

  $fOpen = fopen($file,'a'); //Открываем файл или создаём если его нет
  fwrite($fOpen, $text); //Записываем
  fclose($fOpen); //Закрываем файл
}
*/
?>
