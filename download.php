<?php
$file = basename($_GET['file']);
$extraPath = $_GET['path'];
//$file = '/var/www/vhosts/u0654376.plsk.regruhosting.ru/test.new-constellation.ru/'.$extraPath.$file;
$file = '/home/regpager/'.$extraPath.$file;
if(!file_exists($file)){ // file does not exist
    die('file not found');
} else {
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$file");
    header("Content-Type: application/zip");
    header("Content-Transfer-Encoding: binary");

    // read the file from disk
    readfile($file);
}
?>
