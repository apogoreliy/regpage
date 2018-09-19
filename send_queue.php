<?php

include_once "db.php";
$letters = db_pickLetters ();

if($letters){
    foreach ($letters as $key => $letter) {                
        if($letter->to_email != ''){
            $teamEmail = db_getTeamEmail ($letter->event_key);
            if (EMAILS::sendEmail (trim($letter->to_email), $letter->subject, $letter->body, $letter->from_email, $letter->from_name) != null){
                $err = error_get_last ();
                $result = substr ($err['message'], 0, 50);
            }
            else{
                $result = "ok";
                db_saveMessageInfo($letter->subject, $letter->event_key, $letter->to_email, $letter->from_email, $letter->body);
            }
        }   
        else{
            $result = 'Нет email адреса';
        }
        db_dequeueLetter((int)$letter->id, $result);
    }
}