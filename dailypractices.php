<?php
//Автоматическое добавление строк для учёта практик (practices) выполняется по заданию (cron)
include_once 'db.php';
include_once 'logWriter.php';

function db_newDailyPractices(){

  logFileWriter(false, 'ПРАКТИКИ. Автоматическое добавление строк учёта практик.', 'WARNING');

  $currentDate = date("Y-m-d");
  $yesterday = date("Y-m-d", strtotime("-1 days"));
  $result = array();
  $res=db_query ("SELECT `member_key` FROM user_setting WHERE `setting_key` = '9'");
  while ($row = $res->fetch_assoc()) $result[]=$row['member_key'];

  $resultat = array();
  foreach ($result as $a){
    $resu=db_query ("SELECT `member_id` FROM practices WHERE `member_id` = '$a' AND `date_practic` = '$currentDate'");
    while ($rows = $resu->fetch_assoc()) $resultat[]=$rows['member_id'];
  }

  foreach ($result as $aa){
    if (!in_array($aa, $resultat)){
      //$resultat
      echo "$aa, ";
      db_query("INSERT INTO practices (`date_create`, `member_id`, `date_practic`) VALUES (NOW(), '$aa', '$currentDate')");
      logFileWriter($aa, 'ПРАКТИКИ. АВТОМАТИЧЕСКОЕ ОБСЛУЖИВАНИЕ СЕРВЕРА. Добавлена строка учёта практик для данного пользователя.', 'WARNING');
    }
  }
}

db_newDailyPractices();
?>
