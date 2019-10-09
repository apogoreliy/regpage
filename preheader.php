<?php
header('Content-Type: text/html; charset=utf-8');
include_once "db.php";
global $appRootPath;
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 365);  // 365 day cookie lifetime
session_start ();
// START COOKIES
isset ($_SESSION['sort_type-statistic']) ? '' : setCookie("sort_type_statistic", 'asc');
isset ($_SESSION['sort_field-statistic']) ? '' : setCookie("sort_field_statistic", 'city');
isset ($_COOKIE['selStatisticLocality']) ? '' : setCookie("selStatisticLocality", '_all_');
// STOP COOKIES
if (!isset($isGuest))
{
    $memberId = db_getMemberIdBySessionId (session_id());
    $memberId ? db_lastVisitTimeUpdate(session_id()) : '';
    $thispage = explode('.', substr($_SERVER['PHP_SELF'], 1))[0];
    $memberId && $thispage != 'archive' ? db_activityLogInsert($memberId, $thispage) : '';
    if ((!$memberId && isset ($_GET["link"])) || (!$memberId && isset ($_GET["invited"]))){

    }
    else if(strlen($_SERVER['REQUEST_URI']) == 3){

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
            include 'footer.php';
        }
        else{
            header("Location: ".$appRootPath."login?returl=".urlencode ($_SERVER["REQUEST_URI"]));
        }
        exit;
    }
    else if (!$memberId && preg_match("/(login.php)|(signup.php)|(passrec.php)/", $_SERVER["SCRIPT_NAME"])==0){
        header("Location: ".$appRootPath."login?returl=".urlencode ($_SERVER["REQUEST_URI"]));
    	exit;
    }
    else if($memberId && count(db_getAdminEventsRespForReg($memberId)) == 0 && !db_isAdmin($memberId) && !db_isAvailableMeetingPage($memberId) && preg_match("/(index.php)|(signup.php)|(passrec.php)|(login.php)|(profile.php)|(links.php)/", $_SERVER["SCRIPT_NAME"])==0){
        header("Location: ".$appRootPath);
    	exit;
    }

    include_once "textblock.php";

}
