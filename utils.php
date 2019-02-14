<?php

require_once ("class.smtp.php");
require_once ("class.phpmailer.php");
require_once ("config.php");
require_once ("FirePHP.class.php");

class UTILS
{
    static function getCollegeEndYears(){
        $currentYear = date("Y");
        $yearsArr = [];

        for($i = 0; $i < 5; $i++){
            $yearsArr [] = $currentYear + $i;
        }

        return $yearsArr;
    }

    static function guessHost () {
        return strstr(dirname(__FILE__), 'www.dev.')===FALSE
               ? "https://reg-gage.ru"
               : "https://www.dev.reg-gage.ru";
    }
    
    static function formatDate ($d) {
        $date = new DateTime($d);
        $test_arr  = explode('-', $d);
        return count($test_arr) == 3 && checkdate($test_arr[1], $test_arr[2], $test_arr[0]) ? $date->format('d.m.Y') : "";
    }
    
    static function getLinkToCreateMemberEvent ($memberId, $eventId){
        $memberIdStr = base64_encode($memberId);
        $eventIdStr = base64_encode($eventId);
        $memberIdLength = strlen($memberIdStr);
        $eventIdStrLength = strlen($eventIdStr);

        return str_pad($memberIdStr.($memberIdLength < 10 ? '0'.$memberIdLength : $memberIdLength), $memberIdLength, 0, STR_PAD_LEFT).str_pad($eventIdStr.($eventIdStrLength < 10 ? '0'.$eventIdStrLength : $eventIdStrLength), $eventIdStrLength, 0, STR_PAD_LEFT);
    } 
    
    static function getUserInfoByLink($link){
        $eventLength = (int)substr($link, -2);
        $eventId = base64_decode(substr($link, -(2 + $eventLength), $eventLength));
        $memberIdLength = (int)substr($link, -(4 + $eventLength), 2);
        $memberId = base64_decode(substr($link, -($eventLength + 4 + $memberIdLength), $memberIdLength));
        
        return [$eventId, $memberId];
    }
    
    static function getRecoveryCodeByUserInfo ($user){
        $loginStr = base64_encode(isset($user['login']) ? $user['login'] : $user['signupLogin']);
        $passStr = base64_encode($user['password']);
        $loginLength = strlen($loginStr);
        $passLength = strlen($passStr);

        return md5((isset($user['login']) ? $user['login'] : $user['signupLogin']).$user['password'].date('d-m-Y-H-i')).str_pad($passStr.($passLength < 10 ? '0'.$passLength : $passLength), $passLength, 0, STR_PAD_LEFT).str_pad($loginStr.($loginLength < 10 ? '0'.$loginLength : $loginLength), $loginLength, 0, STR_PAD_LEFT);
    }  

    static function getRecoveryCodeByUserInfoToChangeLogin ($user){
        $loginStr = base64_encode($user['login']);
        $passStr = base64_encode($user['password']);
        $oldLogin = base64_encode($user['old_login']);
        $loginLength = strlen($loginStr);
        $passLength = strlen($passStr);
        $oldLoginLength = strlen($oldLogin);

        return md5(($user['login']).$user['password'].$user['old_login'].date('d-m-Y-H-i')).str_pad($passStr.($passLength < 10 ? '0'.$passLength : $passLength), $passLength, 0, STR_PAD_LEFT).str_pad($loginStr.($loginLength < 10 ? '0'.$loginLength : $loginLength), $loginLength, 0, STR_PAD_LEFT).str_pad($oldLogin.($oldLoginLength < 10 ? '0'.$oldLoginLength : $oldLoginLength), $oldLoginLength, 0, STR_PAD_LEFT);
    }  

    static function getMemberKeyByRecoveryCode ($code) {
        $loginLength = (int)substr($code, -2);
        $user = base64_decode(substr($code, -(2 + $loginLength), $loginLength));
        $userInfo = db_getUserByLogin($user);

        if($userInfo){
            for ($i = 0; $i <= 10; $i++){
                $date = new DateTime();
                if ($i) $date->modify("-$i minutes");
                if (md5($user. $userInfo['password'] . $date->format('d-m-Y-H-i')) == substr($code, 0, 32))
                    return $user;
            }
        }        
        return 0;
    }

    static function getUserInfoByRecoveryCode($code, $isSignup=false){
        $loginLength = (int)substr($code, -2);
        $login = base64_decode(substr($code, -(2 + $loginLength), $loginLength));
        $passLength = (int)substr($code, -(4 + $loginLength), 2);
        $password = base64_decode(substr($code, -($loginLength + 4 + $passLength), $passLength));
        
        /*if($isSignup){
            $nameLength = (int)substr($code, -(6 + $loginLength + $passLength), 2);
            $name = base64_decode(substr($code, -($loginLength + 6 + $passLength + $nameLength), $nameLength));
        }
        */

        for ($i = 0; $i <= 10; $i++){
            $date = new DateTime();
            if ($i) $date->modify("-$i minutes");
            if (md5($login.$password . $date->format('d-m-Y-H-i')) == substr($code, 0, 32))
                return [$login, $password];
                //return [$login, $password, ($isSignup ? $name : NULL)];
        }
        return 0;
    } 
    
    static function getUserInfoByRecoveryCodeToChangeLogin($code){
        $oldLoginLength = (int)substr($code, -2);
        $oldLogin = base64_decode(substr($code, -(2 + $oldLoginLength), $oldLoginLength));
        
        $loginLength = (int)substr($code, -(4 + $oldLoginLength), 2);
        $login = base64_decode(substr($code, -($oldLoginLength + 4 + $loginLength), $loginLength));
                
        $passwordLength = (int)substr($code, -(6 + $loginLength + $oldLoginLength), 2);
        $password = base64_decode(substr($code, -($loginLength + 6 + $oldLoginLength + $passwordLength), $passwordLength));
        
        for ($i = 0; $i <= 10; $i++){            
            $date = new DateTime();
            if ($i) $date->modify("-$i minutes");
            if (md5($login.$password.$oldLogin . $date->format('d-m-Y-H-i')) == substr($code, 0, 32))
                return [$login, $password, $oldLogin];
        }
        return 0;
    } 
    
    static function sendConfirmationEmailToCreateAccount($email, $user){  
        global $appRootPath;
        $code = urlencode(UTILS::getRecoveryCodeByUserInfo($user));

        $htmlText = "<p>На сайте reg-page.ru получен запрос на создание учётной записи пользователя.</p>".
                    "<p>Для подтверждения запроса перейдите <a href='{$appRootPath}signup?code=$code'>по этой ссылке</a>.</p>".
                    "<p>Ссылка действительна в течение 10 минут.</p>".
                    "<p>Если вы получили это письмо по ошибке или не намерены создавать учётную запись на сайте reg-page.ru, просто проигнорируйте это письмо.</p>".
                    "<p>Команда сайта регистрации</p>";
                
        $subject = "Создание учётной записи на сайте регистрации reg-page.ru";

        EMAILS::sendEmail($email, $subject, $htmlText, 'info@reg-page.ru', 'Сайт регистрации');
    }
    
    static function sendConfirmationEmailToChangeLogin($email, $memberId){  
        global $appRootPath;      
        $member = db_getMemberById($memberId);
                
        if($member){
            $user = ['login'=>$email, 'password'=> $member['password'], 'old_login'=> $member['login']];
            $code = urlencode(UTILS::getRecoveryCodeByUserInfoToChangeLogin($user));

            $htmlText = "<p>На сайте reg-page.ru получен запрос на изменение логина пользователя.</p>".
                        "<p>Для подтверждения запроса перейдите <a href='{$appRootPath}'login?change_login=$code'>по этой ссылке</a>.</p>".
                        "<p>Ссылка действительна в течение 10 минут.</p>".
                        "<p>Если вы получили это письмо по ошибке или не намерены изменять логин на сайте reg-page.ru, просто проигнорируйте это письмо.</p>".
                        "<p>Команда сайта регистрации</p>";

            $subject = "Изменение логина на сайте регистрации reg-page.ru";

            EMAILS::sendEmail($email, $subject, $htmlText, 'info@reg-page.ru', 'Сайт регистрации');
            return true;
        }
        else{
            return false;
        }        
    }    
    
    static function sendEmailToInviteMember($linkCode, $from_email, $email, $memberName, $eventName, $eventStartDate, $eventEndDate, $eventRegendDate, $eventInfo){  
        global $appRootPath;

        $path = $appRootPath.'?invited='.$linkCode;

        $htmlText = "<p>Здравствуйте, ".$memberName.".</p>".
                    "<p>Вы приглашены на ". $eventName ."</p>".
                    "<p>Для регистрации на мероприятие перейдите <a href='{$path}'>по этой ссылке</a>.</p>".
                    "<p>".$eventInfo."</p>";

        $subject = "Приглашение для регистрации на сайте reg-page.ru";

        
        EMAILS::sendEmail($email, $subject, $htmlText, $from_email, 'Сайт регистрации');
        return true;
    }        
}

class EMAILS
{    
    static function sendEmail ($to, $subject, $htmlText, $replyTo=null, $fromName=null, $images=null, $headers=null)
    {        
        $mail = new PHPMailer;
        //$mail->SMTPDebug = 2;                               // Enable verbose debug output        
        /*
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        */
        
        $mail->CharSet = "UTF-8";
        $mail->Encoding = "base64";        
        /* MailGun */
        /*
        $mail->Port = 587;                                     // TCP port to connect to
        $mail->Host = 'smtp.mailgun.org';                      // Specify main and backup SMTP servers
        $mail->Username = 'info@reg-page.ru';
        $mail->Password = 'sdf3223@sfsdas2434SD23mlc2';
        */
        //$mail->SMTPSecure = 'tls';                             // Enable SSL encryption, `tls` also accepted                

        $mail->addAddress($to); // Add a recipient
        // $mail->setFrom('info@reg-page.ru', 'Regpage');
        $_replyTo = $replyTo ? $replyTo : 'info@reg-page.ru'; // 'info@reg-page.ru'
        $mail->setFrom('info@reg-page.ru', $fromName ? $fromName: 'Regpage');
        $mail->addReplyTo($_replyTo, $fromName ? $fromName : 'Regpage');
        $mail->isHTML(true);                                  // Set email format to HTML
        
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        if ($headers)
            foreach ($headers as $name=>$value)
                $mail->addCustomHeader($name, $value);

        $mail->Subject = $subject;
        $mail->Body = $htmlText;
        //$mail->AltBody = $plainText;

        //if ($images)
        //    foreach ($images as $img)
        //        $mail->addAttachment(dirname(__FILE__) . '/../img/' . $img[0], '', 'base64', '', 'inline', $img[1]);
        
        if (!$mail->send()){
            return $mail->ErrorInfo;
        }
        else
            return null;
    }         
}