<?php
// Автоматическое ежемесячное удаление мусора из контактов. Выполняется по заданию (cron)
include_once 'db.php';
include_once 'logWriter.php';

function db_deleteTrashFromContacts(){
  $counter = 0;
  $res = db_query ("SELECT `id` FROM `contacts`  WHERE `notice` = 2");
  while ($row = $res->fetch_assoc()) $counter++;

  if ($counter > 0) {
    logFileWriter(false, 'УДАЛЕНИЕ КОНТАКТОВ ИЗ КОРЗИНЫ. АВТОМАТИЧЕСКОЕ ОБСЛУЖИВАНИЕ СЕРВЕРА. Удалено '.$counter.' контактов из корзины.', 'WARNING');
    db_query ("DELETE FROM `contacts` WHERE `notice` = 2");
  } else {
    logFileWriter(false, 'УДАЛЕНИЕ КОНТАКТОВ ИЗ КОРЗИНЫ. АВТОМАТИЧЕСКОЕ ОБСЛУЖИВАНИЕ СЕРВЕРА. Контакты в корзине отсутствуют.', 'WARNING');
  }
}

db_deleteTrashFromContacts();
?>
