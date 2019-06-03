<?php
include_once "ajax.php";

header('Content-type: text/plain');

if(isset($_GET["logout"])){
    if (isset($_GET["memberId"]) && isset($_GET['sessionId'])){
        db_logoutAdmin ($_GET['memberId'], $_GET['sessionId']);
    }
    exit();
}
else if(isset($_GET["logout_total"])){
    if (isset($_GET["memberId"])){
        db_logoutAdminTotal ($_GET['memberId']);
    }
    exit();
}
else if (isset($_GET["signup"])) {
    $res = db_signUpMember(session_id(), $_GET['signupLogin'], $_GET['password'], $_GET['name'],  $_GET['birthDate'], $_GET['gender'], $_GET['citizenship'], $_GET['locality'], $_GET['newLocality']);

    if($res){
        db_loginUserByLogin (session_id(), $_GET['signupLogin']);
    }
    exit();
}
else if(isset($_GET['remove_account'])){
    db_removeAccount($_POST['member'], $_POST['reason']);

    exit();
}
else if (isset($_GET["login"]) && isset($_GET["password"])) {
    $memberId = db_loginAdmin (session_id(), $_GET["login"], $_GET["password"]);
    print $memberId ? "success" : "error" ;
}
else if (isset($_GET["signupLogin"])) {
    $email = ($_GET["signupLogin"]);
    $memberId = db_isAdminExist ($email);

    if(isset($_GET["password"])){
        if(!$memberId){
            UTILS::sendConfirmationEmailToCreateAccount($email, $_GET);
        }
    }
    else{
        print !$memberId ? "success" : "error";
    }
}
?>
