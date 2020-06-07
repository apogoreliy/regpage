<?php
include_once '../db.php';
include_once '../PHPExcel.php';
include_once '../PHPExcel/IOFactory.php';
include_once '../FirePHP.class.php';
header("Content-Type: application/json; charset=utf-8");

$myArrForUpload = json_decode($_POST['xlsx_array'], TRUE);
$sqlForRegReady = '';
$sqlMem = '';

function db_getNewContactIdPackage ()
{
    $res=db_query ("SELECT `id` FROM contacts ORDER BY `id` DESC LIMIT 1");
    $row = $res->fetch_object();
    $key = "100000";
    if ($row && strlen($row->id)==6) $key = (string)($row->id + 1);
    return $key;
}

function fileUploaderAdd($arr){
$newId = db_getNewContactIdPackage();
// INSERT TO CONTACT TABLE
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
        if ($counter < 16) {
// value
          if (empty($value)) {
            $value = ' ';
          }
        $val = "'$value'";
// prepare array
if (($counter == 0)) {
          $newId = (string)($newId + 1);
          $newIdVal = "'$newId'";
          $val = "(".$newIdVal.','.$val;
        } elseif ($counter == 15) {
          $val = $val.")";
        }
// add element to the temp array
        $sqltemp[$counter-1] = $val;
      }
      if ($counter == 15) {
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
          unset($sqltemp[10]);
          unset($sqltemp[11]);
          unset($sqltemp[12]);
          unset($sqltemp[13]);
          unset($sqltemp[14]);
          break;
      }
        $counter++;
      }
    }
  }
  $sqlMem = implode(',',$sql);
  db_query("INSERT INTO contacts (`id`, `comment`, `name`, `male`, `country_key`, `locality`, `email`, `phone`, `order_date`,`status`,`index_post`,`responsible`, `sending_date`, `address`, `area`, `region_work`, `region`) VALUES ".$sqlMem."");
};

if (isset($_POST['xlsx_array'])) {
  fileUploaderAdd($myArrForUpload);
  exit();
}
?>
