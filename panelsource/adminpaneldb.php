<?php
//Автоматическое задание настроек пользователей
// DATA BASE QUERY

include_once "db.php";

function db_newDailyPracticesPac($b){


  $result = array();
  $res=db_query ("SELECT `key` FROM member WHERE `locality_key` = '001192'");
  while ($row = $res->fetch_assoc()) $result[]=$row['key'];

  $resultat = array();
  foreach ($result as $a){
    $resu=db_query ("SELECT `member_key` FROM user_setting WHERE `member_key` = '$a' AND `setting_key` = '$b'");
    while ($rows = $resu->fetch_assoc()) $resultat[]=$rows['member_key'];
  }

  foreach ($result as $aa){
    if (!in_array($aa, $resultat)){
      //$resultat
      echo "$aa, ";
      db_query("INSERT INTO user_setting (`member_key`, `setting_key`) VALUES ('$aa', '$b')");
    }
  }
}

?>
