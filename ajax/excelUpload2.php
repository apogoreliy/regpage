<?php
include_once '../db.php';
include_once '../PHPExcel.php';
include_once '../PHPExcel/IOFactory.php';
include_once '../FirePHP.class.php';
header("Content-Type: application/json; charset=utf-8");

function fileUploader($upfile){
  /*/home/regpager/tmp/ ИЛИ /tmp/*/
  $target_dir = "/home/regpager/tmp/";
  $target_file = $target_dir.basename($_FILES["upload_file"]["tmp_name"]);
  $uploadOk = 1;

  $objPHPExcel = PHPExcel_IOFactory::load($target_file);
  $uploadArray = [];
  $uploadString = [];
  foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
   $worksheetTitle     = $worksheet->getTitle();
   $highestRow         = $worksheet->getHighestRow(); // e.g. 10
   $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
   $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
   $nrColumns = ord($highestColumn) - 64;

   for ($row = 1; $row <= $highestRow; ++ $row) {
     foreach($uploadString as $key => $value){
        unset($uploadString[$key]);
     }
       for ($col = 0; $col < $highestColumnIndex; ++ $col) {
// нужно исключать не подходящие столбцы, если столбец не возвращает даты регулярно можно его не проверять
// Возможно понадобиться опция, опустить проверку дат надо тестировать выкатить версию какая получится
// Использовать Регулярки!!!
         $checkDateComplete = false;
         $cellTmp = $worksheet->getCellByColumnAndRow($col, $row);
         $valTmp = $cellTmp->getValue();
         //$valTmp = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($valTmp));
         $dataType = PHPExcel_Cell_DataType::dataTypeForValue($valTmp);
         if ($dataType == 'n') {
          $valTmpY = date('Y', PHPExcel_Shared_Date::ExcelToPHP($valTmp));
          $valTmpM = date('m', PHPExcel_Shared_Date::ExcelToPHP($valTmp));
          $valTmpD = date('d', PHPExcel_Shared_Date::ExcelToPHP($valTmp));
          $aaa = $valTmpY > 1920;
          $bbb = $valTmpY < 2039;
          if ($aaa && $bbb) {
            checkdate($valTmpM, $valTmpD, $valTmpY);
            $checkDateComplete = true;
          }
         }
         if ($checkDateComplete) {
           $cell = $worksheet->getCellByColumnAndRow($col, $row);
           $val = $cell->getValue();
           $val = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($val));
           $dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
         } elseif ($col == 30) {
           break;
         } else {
           $cell = $worksheet->getCellByColumnAndRow($col, $row);
           $val = $cell->getValue();
           $dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
         }
           array_push($uploadString, $val);
       }
       $uploadArray[] = $uploadString;
   }
 }
 echo json_encode($uploadArray);
 //exit();
}

fileUploader($_FILES["upload_file"]);
?>
