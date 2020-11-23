<?php
// Автоматическое ежемесячное удаление мусора из контактов. Выполняется по заданию (cron)
include_once 'db.php';
include_once 'logWriter.php';

function db_deleteTrashFromContacts(){
  $counter = 0;
  $res = db_query ("SELECT `id` FROM `contacts`  WHERE `notice` = 2");
  while ($row = $res->fetch_assoc()) $counter++;

  if ($counter > 0) {
    logFileWriter(false, 'СЕССИИ АДМИНИСТРАТОРОВ. АВТОМАТИЧЕСКОЕ ОБСЛУЖИВАНИЕ СЕРВЕРА. Удалено '.$counter.' просроченных сессий.', 'WARNING');
    db_query ("DELETE FROM `contacts` WHERE `notice` = 2");
  } else {
    logFileWriter(false, 'СЕССИИ АДМИНИСТРАТОРОВ. АВТОМАТИЧЕСКОЕ ОБСЛУЖИВАНИЕ СЕРВЕРА. Просроченные сессии отсутствуют.', 'WARNING');
  }
}

db_deleteTrashFromContacts();
?>
