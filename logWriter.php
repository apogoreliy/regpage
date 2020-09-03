<?php

// LOG FILE

function logFileWriter($logMemberId, $info, $type='INFO')
{
  $logMemberId ? $logAdminName = db_getAdminNameById($logMemberId) : $logAdminName = 'SERVER REG-PAGE.RU';
  $logMemberId ? $logAdminLocaity = db_getLocalityByKey(db_getAdminLocality($logMemberId)) : $logAdminLocaity = '-';
  $logMemberId ? $logAdminCountry = db_getAdminCountry($logMemberId) : $logAdminCountry = '-';
  $logMemberId ? $logAdminRole = db_getAdminRole($logMemberId) : $logAdminRole = 'SUPERVISOR';
  $logMemberId ? '' : $logMemberId = 'none';

  $file = 'logFile_'.date("d-m-Y").'.log'; //
  //Добавим разделитель, чтобы мы смогли отличить каждую запись
  $text = $type.' ==================================================='.PHP_EOL;
  $text .=  date('d-m-Y H:i:s') .PHP_EOL; //Добавим актуальную дату после текста или дампа массива
  $text .= 'Admin is '.$logAdminName.'. Key is '. $logMemberId.'. Role is '.$logAdminRole.'. '.$logAdminCountry.'. '. $logAdminLocaity.'. '.PHP_EOL;
  $text .= $info.PHP_EOL.PHP_EOL;

  $fOpen = fopen($file,'a'); //Открываем файл или создаём если его нет
  fwrite($fOpen, $text); //Записываем
  fclose($fOpen); //Закрываем файл
}

/*
function logFileWriter2($logMemberId, $customText)
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

function db_getMemberIdBySessionIdLOG ($sessionId)
{
    global $db;
    $sessionId = $db->real_escape_string($sessionId);

    $res=db_query ("SELECT admin_key from admin_session where id_session='$sessionId'");
    if ($row = $res->fetch_assoc()) return $row['admin_key'];
    return NULL;
}
*/
/*
$logNamePieces = explode(" ", $logAdminName);
$logFirstName = $pieces[1];
$logSecondName = $pieces[2];
$logAdminName = $logFirstName.' '.$logSecondName;
*//*
function logFileWriter($logMemberId, $info)
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
