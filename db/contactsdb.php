<?php
// DATA BASE QUERY
// CONTACTS

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
  $newId = db_getNewContactId ();
  if ($Id) {
    db_query("UPDATE contacts SET `name`='$name', `phone`='$phone', `locality`='$locality', `male`='$male', `status`='$status', `email`='$email', `responsible`='$responsible', `responsible_previous`='$responsiblePrev', `area`='$area', `address`= '$address', `comment`= '$comment', `index_post` = '$index', `region` = '$region', `region_work` = '$regionWork', `country_key`='$countryKey'  WHERE `id` = '$Id'");
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
    c.sending_date, c.crm_id, m.name AS member_name
    FROM contacts AS c
    INNER JOIN member m ON m.key = c.responsible
    WHERE c.id = '$id'");
    while ($row = $res->fetch_assoc()) $result[]=$row;

  return $result;
}
// Delete contacts strings
function db_deleteContactString($id){
  global $db;
  //$id = $db->real_escape_string($id);
  foreach ($id as $value) {
    db_query ("DELETE FROM contacts WHERE `id`='$value'");
    db_query ("DELETE FROM chat WHERE `group_id`='$value'");
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
    c.sending_date, c.crm_id, m.name AS member_name
    FROM contacts AS c
    INNER JOIN member m ON m.key = c.responsible
    WHERE c.responsible_previous = '$memberId' OR c.responsible = '$memberId' ORDER BY c.name");
    while ($row = $res->fetch_assoc()) $result[]=$row;
  } else {
    $res=db_query ("SELECT c.id,c.time_stamp,c.name,c.phone,c.locality,c.male,c.status,c.email,c.responsible, c.responsible_previous,c.area,c.address,c.comment,c.index_post,c.region,c.region_work,c.country_key,c.order_date,
    c.sending_date, c.crm_id, m.name AS member_name
    FROM contacts AS c
    INNER JOIN member m ON m.key = c.responsible
    WHERE c.responsible = '$memberId' ORDER BY c.name");
    while ($row = $res->fetch_assoc()) $result[]=$row;
  }
  return $result;
}
// set CRM ID
function  db_crmIdSet($idCRM, $id, $memberId, $text, $comment){
  global $db;
  $id = $db->real_escape_string($id);
  $idCRM = $db->real_escape_string($idCRM);
  $memberId = $db->real_escape_string($memberId);
  $text = $db->real_escape_string($text);
  $comment = $db->real_escape_string($comment);
  db_query("UPDATE contacts SET `crm_id` = '$idCRM', `order_date` = NOW(), `comment` = '$comment' WHERE `id` = '$id'");
  db_query("INSERT INTO chat (`group_id`, `member_key`, `message`) VALUES ('$id', '$memberId', '$text')");
}

// CHAT
// new message
function db_newChatMsg($memberId, $data){
  global $db;
  $id = $db->real_escape_string($data['id']);
  $text = $db->real_escape_string($data['text']);
  $memberId = $db->real_escape_string($memberId);
  db_query("INSERT INTO chat (`group_id`, `member_key`, `message`) VALUES ('$id', '$memberId', '$text')");
}
// update message
function db_updateChatMsg($memberId, $data){
  global $db;
  $memberId = $db->real_escape_string($memberId);
  db_query("UPDATE chat SET `message` = '', WHERE `id` = ''");
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

?>
