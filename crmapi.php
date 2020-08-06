<?php

$link = 'https://bibleforall.envycrm.com/crm/api/v1/lead/set/?api_key=ecdfd3e079da4ab92942a50d8dd67991b5878f21';
$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$info = $_POST['info'];
$value1 = $_POST['value1'];
$value2 = $_POST['value2'];
$value3 = $_POST['value3'];
$value4 = $_POST['value4'];
$value5 = $_POST['value5'];
$value6 = $_POST['value6'];
$value7 = $_POST['value7'];
$value8 = $_POST['value8'];

if (!$name || !$phone || !$value8 || !$value6) {
  return 'error: the fields has been empty';
}

$curl = curl_init();

$data = [
'method' => 'create', // метод, 'create' - для создания, 'update' - для обновления, в данном случае использовать нет необходимости

'pipeline_id' => 81639, // id воронки "Заказы (не обязательное поле, если не указано используется воронка по-умолчанию)

'stage_id' => 366498, // id этапа (не обязательное поле, если не указано используется этап воронки по-умолчанию; 366498 - проверка заказа)

//'employee_id' => 0, // id ответственного сотрудника (не обязательное поле, если не указано, то ответственный не указывается, лид доступен всем, если значение 0, то "Администратор", если указан id сотрудника, то будет назначен сотрудник, чей id передан; 0 Алексей Р.; 418629 Женя Р.)

'inbox_type_id' => 458658, // id типа входящего обращения: 374553 - сайт, 458658 - обзвон

//'visit_id' => $_COOKIE['WhiteCallback_visit'], // идентификатор визита сервиса, будет присутствовать, если установлен js код наших виджетов, поле не обязательное, автоматически добавит информацию о посетителе, получается из Cookie

'values' => [ // массив значений системных и произвольных полей
'name' => $name ? $name : 'Заявка с сайта ' . ($phone ? $phone : $email), // имя
'phone' => $phone, // телефон
'email' => $email, // email
'comment' => $info, // примечание

//'utm_source' => $utm_source, // utm-метка utm_source
//'utm_medium' => $utm_medium, // utm-метка utm_medium
//'utm_campaign' => $utm_campaign, // utm-метка utm_campaign
//'utm_content' => $utm_content, // utm-метка utm_content
//'utm_term' => $utm_term, // utm-метка utm_utm_term

'custom' => [ // массив дополнительных полей
// в каждой строке массив идентификатора поля и его значения
['input_id' => 131211, 'value' => $value1], // количество
['input_id' => 131340, 'value' => $value2], // комментарий
['input_id' => 131586, 'value' => $value3], // страна
['input_id' => 131589, 'value' => $value4], // область
['input_id' => 131592, 'value' => $value5], // район
['input_id' => 131595, 'value' => $value6], // местность
['input_id' => 131598, 'value' => $value7], // адрес
['input_id' => 131601, 'value' => $value8]  // индекс
]
]
];

curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_URL, $link);
curl_setopt($curl,CURLOPT_POST,true);
curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode(['request' => $data]));
curl_setopt($curl,CURLOPT_HEADER,false);

$out=curl_exec($curl);
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
curl_close($curl);

$answer = json_decode($out, true);

if ($answer['message'] === 'success') {
  echo $answer['id'];
  //header("Location: https://www.bibleforall.ru/");
} else {
  echo 'Failed';
  //header("Location: https://www.reg-page.ru/");
}

?>
