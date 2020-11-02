<?php
function db_getSessionsAdmins(){
    $res=db_query ("SELECT member_key, session FROM admin WHERE session IS NOT NULL");

		$sessions = array ();
		while ($row = $res->fetch_object()) $sessions[]=$row;
		return $sessions;
}

/*
function db_copySessions($adminId, $sessionId)
{
	global $db;
	$adminId = $db->real_escape_string($adminId);
	$sessionId = $db->real_escape_string($sessionId);
	db_query ("INSERT INTO admin_session (id_session, admin_key) VALUES ('$sessionId','$adminId')");
}
*/
function db_delete_old_sessions()
{
	global $db;
  $datatime = date("Y-m-d H:i:s");
  db_query ("DELETE FROM admin_session WHERE ($datatime - `time_last_visit`)>356");
}

function db_getCustomPagesPanel(){
    $res = db_query("SELECT * FROM custom_page");

    $pages = array();
    while($row = $res->fetch_assoc()){
        $pages [$row['name']] = $row['value'];
    }
    return $pages;
}

function db_setPracticesForStudentsPVOM() {
  logFileWriter(db_getMemberIdBySessionId (session_id()), 'ПРАКТИКИ. Пакетное добавление учёта практик для обучающихся ПВОМ администратором.', 'WARNING');

  $currentDate = date("Y-m-d");
  $resultFoUser = ':';
  $students = array();
  $queryStudents=db_query ("SELECT `key` FROM member WHERE `locality_key` = '001192'");
  while ($rowOfStudents = $queryStudents->fetch_assoc()) $students[]=$rowOfStudents['key'];

  $checkSettingOn = '';
  foreach ($students as $student){
    $checkSettingOn = '';
    $queryPracticesOn=db_query ("SELECT `member_key` FROM user_setting WHERE `member_key` = '$student' AND `setting_key` = '9'");
    while ($rowOfPracticesOn = $queryPracticesOn->fetch_assoc()) $checkSettingOn=$rowOfPracticesOn['member_key'];

    if (!$checkSettingOn) {
      $resultFoUser = $resultFoUser.' '.$student;
      db_query("INSERT INTO user_setting (`member_key`, `setting_key`) VALUES ('$student', '9')");
      $queryExistString=db_query ("SELECT `member_id` FROM practices WHERE `member_id` = '$student' AND `date_practic` = '$currentDate'");
      $rowExistString = $queryExistString->fetch_assoc();
      if (!$rowExistString['member_id']) {
        db_query("INSERT INTO practices (`date_create`, `member_id`, `date_practic`) VALUES (NOW(), '$student', '$currentDate')");
      }

      logFileWriter($student, 'ПРАКТИКИ. Пакетное подключение учёта практик для данного пользователя.', 'DEBUG');
    } else {
      logFileWriter($student, 'ПРАКТИКИ. Опция учёта практик для данного пользователя была подключена ранее.', 'DEBUG');
    }
  }

  if ($resultFoUser === ':') {
    $resultFoUser = 'У всех обучающихся ПВОМ включен учёт ежедневных практик.';
  } else {
    $resultFoUser = 'Опции включены для пользователей c ключами'.$resultFoUser;
  }
  return $resultFoUser;
}
// Roles of responsibles in contacts
function db_getResponsibleContacts1And2() {
    $res=db_query ("SELECT c.member_key, c.role, c.group_of_admin, m.name
      FROM contacts_resp AS c
      INNER JOIN member m ON m.key = c.member_key");

		$responsibles = array ();
		while ($row = $res->fetch_assoc()) $responsibles[]=$row;
		return $responsibles;
}

function db_getResponsibleContactsZero() {
    $res=db_query ("SELECT c.member_key, c.role, c.group_of_admin, m.name
    FROM contacts_resp AS c
    INNER JOIN member m ON m.key = c.member_key");

    $responsibles = [];
    while ($row = $res->fetch_assoc()) $responsibles[]=$row;

    $responsiblesZero = [];
    $responsiblesTemp = [];
    for ($i=0; $i < count($responsibles); $i++) {
      $temp = explode(',', $responsibles[$i]['group_of_admin']);
      for ($ii=0; $ii < count($temp); $ii++) {
        $tempKey = $temp[$ii];
        $res2=db_query ("SELECT `key`, `name` FROM member WHERE `key` = '$tempKey'");
        while ($row2 = $res2->fetch_assoc()) $responsiblesTemp[$row2['key']]=$row2['name'];
      }
      $responsiblesZero[$responsibles[$i]['name'].', роль '.$responsibles[$i]['role']] = $responsiblesTemp;
      $responsiblesTemp = [];
    }

		return $responsiblesZero;
}

function db_checkLostContacts($adminsFirstAndSecondRoles='')
{
  $contacts =[];

  $res=db_query ("SELECT `id`, `name`, `notice` FROM `contacts` WHERE (`responsible` <> '000005944' AND `responsible` <> '000001679') AND (`responsible_previous` <> '000001679' AND `responsible_previous` <> '000005944')");
  while ($row = $res->fetch_assoc()) $contacts[]=$row['id'].' '.$row['name'].' '.$row['notice'];

  return $contacts;
}


?>
