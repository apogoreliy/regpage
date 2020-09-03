<?php
//Автоматическое проверка учёта практик (practices) выполняется по заданию (cron)
include_once 'db.php';
include_once 'logWriter.php';

function db_stopDailyPractices(){
  logFileWriter(false, 'ПРАКТИКИ. Автоматическая проверка учёта практик.', 'DEBUG');
  $practicesMemberKeys=[];
// get keys of members
  $res=db_query ("SELECT `member_key` FROM user_setting WHERE `setting_key` = '9'");
  while ($row = $res->fetch_assoc()) $practicesMemberKeys[]=$row['member_key'];

  foreach ($practicesMemberKeys as $i){
    $resultat= array();
// get the not filled strings for members by the key
    $res2=db_query ("SELECT * FROM `practices`
      WHERE `member_id`='$i' AND `date_practic`>DATE_ADD(CURRENT_DATE(),INTERVAL -8 DAY) AND `m_revival`=0 AND `p_pray`=0 AND  `co_pray`=0 AND `r_bible`=0 AND `r_ministry`=0 AND `evangel`=0 AND `flyers`=0 AND `contacts`=0 AND
      `saved`=0 AND `meetings`=0 AND ISNULL(`wakeup`) AND ISNULL(`hangup`) AND `other`=''");
    while ($rows2 = $res2->fetch_assoc()) $resultat[]=$rows2;

// get the not filled strings for members by the key
    if (count($resultat) === 8) {
      $keyOfMember = $resultat[0]['member_id'];
      logFileWriter($resultat[0]['member_id'], 'ПРАКТИКИ. АВТОМАТИЧЕСКОЕ ОБСЛУЖИВАНИЕ СЕРВЕРА. Для данного пользователя автоматически отключен учёт практик.', 'WARNING');
      db_query ("DELETE FROM `user_setting` WHERE `member_key`='$keyOfMember' AND `setting_key`=9");
      echo $keyOfMember.' has been disabled';
    }
  }
}

db_stopDailyPractices();
?>
