<?php
header('Content-Type: text/html; charset=utf-8');
include_once "db.php";

global $appRootPath;

session_start ();

if (!isset($isGuest))
{    
    $memberId = db_getMemberIdBySessionId (session_id());
    
    
    if ((!$memberId && isset ($_GET["link"])) || (!$memberId && isset ($_GET["invited"]))){
        
    }
    else if(!$memberId && strlen($_SERVER['REQUEST_URI']) == 3){
        // determine a special page
        $specPage = NULL;
        foreach (db_getSpecPages() as $sp){    
            if (isset ($_GET[$sp])){
                $specPage = $sp;
                break;
            }
        }

        if ($specPage){
            include 'header.php';
            include 'nav.php';
            include 'modals.php';
            
            echo '<div class="container"><div style="background-color: white; padding: 20px;">';
            echo db_getCustomPage($specPage);
            echo'</div>';
            echo '<div style="text-align: center; margin-top:50px; padding-top:20px; color: #ababab !important;"><p><font size="-2">© <a style="color:#ababab; text-decoration:underline" href="http://ivsite.ru" target="_blank">Бюро "Ивсайт"</a> | <a style="color:#ababab; text-decoration:underline" href="https://drive.google.com/open?id=1krSycWkozm2Y-UNwHwG1OuMU81LgzbB76EVVfszgMYU" target="_blank">Пользовательское соглашение</a> | <a style="color:#ababab; text-decoration:underline" href="http://reg-page.ru/pd" target="_blank">Официальные документы</a></font></p></div>';
            echo '</div>';            
        }
        else{
            header("Location: ".$appRootPath."?returl=".urlencode ($_SERVER["REQUEST_URI"]));
            // header("Location: ".$appRootPath."login?returl=".urlencode ($_SERVER["REQUEST_URI"]));
        }
        exit;
    }
    else if (!$memberId && preg_match("/(index.php)|(signup.php)|(passrec.php)/", $_SERVER["SCRIPT_NAME"])==0){        
        header("Location: ".$appRootPath."?returl=".urlencode ($_SERVER["REQUEST_URI"]));
        // header("Location: ".$appRootPath."login?returl=".urlencode ($_SERVER["REQUEST_URI"]));
    	exit;
    }
    else if($memberId && count(db_getAdminEventsRespForReg($memberId)) == 0 && !db_isAdmin($memberId) && !db_isAvailableMeetingPage($memberId) && preg_match("/(main.php)|(signup.php)|(passrec.php)|(index.php)|(profile.php)|(links.php)/", $_SERVER["SCRIPT_NAME"])==0){
        header("Location: ".$appRootPath);
    	exit;
    }

    include_once "textblock.php";
}
