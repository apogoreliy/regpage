<?php

include_once "../db.php";

header("Content-Type: application/json; charset=utf-8");
session_start ();

function exception_handler($exception) {
	header("http/1.0 500 Internal server error");
	echo $exception->getMessage();
	exit;
}

function error_handler ($errno, $errstr, $errfile, $errline) {
    header("http/1.0 500 Internal server error");
    echo "$errno: ".$errstr;
    exit;
}

set_exception_handler('exception_handler');
set_error_handler('error_handler');

?>