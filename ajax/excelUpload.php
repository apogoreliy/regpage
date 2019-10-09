<?php
include_once '../db.php';
include_once '../PHPExcel.php';
include_once '../PHPExcel/IOFactory.php';
include_once '../FirePHP.class.php';
header("Content-Type: application/json; charset=utf-8");

$myArrForUpload = json_decode($_POST['xlsx_array'], TRUE);
$myArrForUploadReg = json_decode($_POST['xlsx_array_reg'], TRUE);
$sqlForRegReady = '';
$sqlMem = '';

function fileUploaderAdd($arr, $arrReg){

// INSERT TO REG TABLE
  $sqltemp = [];
  $sql = array();
  $arrJob = $arr[0];
  $sqlArrCreate;
  $counterArr = 0;
  $counter1 = count($arr);
  for ($i=0; $i < $counter1; $i++) {
    $arrTemp = $arr[$i];
    $counter = 0;
    if ($i!=0) {
      foreach($arrTemp as $value) {
        if ($counter < 10) {
// value
          if (empty($value)) {
            $value = ' ';
          }
        $val = "'$value'";
// memberId counter & "(", ")" & prepare array
        if (($counter == 0) && ($counterArr == 0)) {
          $memcounter = db_getNewMemberKey(); // решить и переходить к формированию и добавлению записей в рег и мемберс
          $newMemberId = "'$memcounter'";
          array_unshift($arrReg[$counterArr+1], $memcounter);
          $val = "(".$newMemberId.",".$val;
        } elseif (($counter == 0) && ($counterArr != 0)) {
          $memcounter = (int)$memcounter;
          $memcounter += 1;
          $newMemberId = (string)$memcounter;
          $memcounter = (string)$memcounter;
          array_unshift($arrReg[$counterArr+1], $newMemberId);
          $newMemberId = "'$newMemberId'";
          $val = "(".$newMemberId.",".$val;
        } elseif ($counter == 9) {
          $val = $val.")";
        }
// add element to the temp array
        $sqltemp[$counter-1] = $val;
      }
      if ($counter == 9) {
          $counter = 0;
          $counterArr++;
          $sql[] = implode(',',$sqltemp);
          unset($sqltemp[0]);
          unset($sqltemp[1]);
          unset($sqltemp[2]);
          unset($sqltemp[3]);
          unset($sqltemp[4]);
          unset($sqltemp[5]);
          unset($sqltemp[6]);
          unset($sqltemp[7]);
          unset($sqltemp[8]);
          unset($sqltemp[9]);
          break;
      }
        $counter++;
      }
    }
  }
  $sqlMem = implode(',',$sql);
  //print_r($sqlMem);

  db_query("INSERT INTO member (`key`, `comment`, `email`, `name`, `birth_date`, `college_comment`, `male`, `cell_phone`,`citizenship_key`,`locality_key`,`category_key`) VALUES ".$sqlMem."");

// INSERT TO REG TABLE
$sqlForRegTemp = [];
$sqlForReg = [];
$counter2 = count($arr);
for ($i=0; $i < $counter2; $i++) {
  $arrRegItem = $arrReg[$i];
  if ($i!=0) {
    $counterReg = 0;
    $lengthReg = count($arrRegItem);
    foreach($arrRegItem as $row) {
      $rows = "'$row'";
      if ($counterReg === 0) {
        $rows = "(".$rows;
      } elseif ($counterReg === 10) {
        $rows = "NOW(), UUID()";
        $rows = $rows.")";
        $sqlForRegTemp[] = $rows;
        break;
      }
      $sqlForRegTemp[] = $rows;
      $counterReg++;
    }
    $sqlForReg[] = implode(',',$sqlForRegTemp);
  }
}
$sqlForRegReady = implode(',',$sqlForRegTemp);
//print_r($sqlForRegReady);

db_query("INSERT INTO reg (`member_key`, `comment`, `event_key`, `arr_date`, `dep_date`, `status_key`, `admin_key`, `currency`,`admin_comment`,`accom`,`created`, `permalink`) VALUES ".$sqlForRegReady."");
// exit();
//return true;
// ОЧИЩАТЬ ВСЁ ПРИ ВЫХОДЕ ИЗ ФУНКЦИИ
};

if (isset($_POST['xlsx_array']) && isset($_POST['xlsx_array_reg'])) {
  echo json_encode(fileUploaderAdd($myArrForUpload, $myArrForUploadReg));
  exit();
}
?>
