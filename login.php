<?php 
    include_once "preheader.php";
    if ($memberId) db_logoutAdmin ($memberId);
    $memberId = db_getMemberIdBySessionId (session_id());
    $isGuest = true;
    include_once "header.php";
    include_once 'modals.php';
    include_once "nav.php";
    global $appRootPath;
    
    if (isset($_GET["change_login"])){
        $user = UTILS::getUserInfoByRecoveryCodeToChangeLogin($_GET["change_login"]);

        if ($user !== 0){
            $login = $user[0];
            $password = $user[1]; 
            $oldLogin = $user[2]; 
                        
            $memId = db_loginAdmin(session_id(), $oldLogin, $password);
            db_setMemberLogin($memId, $login);
            ?>
            <script>
                window.location = '/profile';
            </script>
            <?php
        }
        else{            
            echo '<div class="container signup-container"><div class="alert alert-danger" role="alert">Эта ссылка недействительна или просрочена. Для изменения логина пройдите процедуру повторно.</div></div>';
        }
    }
    else if (isset ($_GET["email"])){
        global $db;
        $isEmail = $db->real_escape_string($_GET["email"]);
    }
?>

<div class="container">
    <form class="form-signin">
        <label class="control-label" for="login">Логин</label>
        <input type="text" id="login" name="login" placeholder="Email">
        <label class="control-label" for="password">Пароль</label>
        <input type="password" id="password" name="password">
        <!--<div id="passRec" style="display:none"><a href="/passrec">Забыли пароль?</a></div>-->
        <div id="loginError" class="alert alert-error" style="display:none">Неверно введены логин или пароль</div>
        <div id="emailError" class="alert alert-error" style="display:none">Логином должен быть корректный email</div>
        <div id="ajaxError" class="alert alert-error" style="display:none">Ошибка сервера. Обратитесь к разработчикам.</div>
        <div id="passLengthError" class="alert alert-error" style="display:none">Длинна пароля должна быть не меньше 5 символов</div>
        <div><a href="/passrec">Забыли пароль?</a></div>
        <button type="submit" id="loginFormBtn" class="btn btn-large btn-primary">Войти</button>        
    </form>
</div>

<script>

$(document).ready(function(){
    $("#login").focus();
        
    <?php if (isset($isEmail)) { ?>

    $('.form-signin').find('#login').val('<?php echo $isEmail; ?>');

    <?php } ?>
        
    $('#password').keydown(function(){
        $("#passLengthError").hide ();
    });
    
    $('#login').keydown(function(){
        $("#loginError,#emailError").hide ();
    });
});

$("#loginFormBtn").click (function (e){
    var emailValidate = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var password = $("#password").val();
    
    if(!emailValidate.test($('#login').val())){   
        $('#loginError').hide();
        $("#emailError").show ();
        e.stopPropagation();
        e.preventDefault();
        return;
    }    
    
    if(password.length < 5){   
        $("#passLengthError").show ();
        e.stopPropagation();
        e.preventDefault();
        return;
    }
    
    $.get('ajax/login.php', { login: $("#login").val(), password:$("#password").val() })
    .done (function(data) {
        $("#ajaxError").hide ();            
                        
        if(data === "error"){
            $("#emailError").hide ();
            $('#loginError').show();
            //$('#passRec').show();
        }
        else{
            $("#loginError").hide ();                
            window.location = "<?php print(substr($appRootPath, 0, -1).(isset ($_GET['returl'])?urldecode($_GET['returl']):'/')); ?>";
        } 
    })
    .fail(function() { $("#ajaxError").show (); });
    return false;
});

/*
$("#btnDoSendEventMsgAdmins").click (function (){
    if ($(this).hasClass('disabled')) return;
    $.ajax({type: "POST", url: "/ajax/set.php", data: {event:"", message: $("#sendMsgTextAdmin").val(), name:$("#sendMsgNameAdmin").val(), email:$("#sendMsgEmailAdmin").val(), admins:"Главная admin"}})
     .done (function() {messageBox ('Ваше сообщение было отправлено', $('#messageAdmins'));});
});
*/

</script>

<?php
include_once "footer.php"; 
?>