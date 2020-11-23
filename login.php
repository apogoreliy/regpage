<?php
    include_once "header.php";
    $indexPage = true;

    // if ($memberId) db_logoutAdmin ($memberId, session_id());
    $memberId = db_getMemberIdBySessionId (session_id());
    $isGuest= isset($memberId) ? NULL : true;

    $isLink=false;
    $isInvited = false;
    include_once 'modals.php';
    include_once "nav.php";
    global $appRootPath;

    $countries1 = db_getCountries(true);
    $countries2 = db_getCountries(false);

    /* ******************* */
    if (isset ($_GET["link"])){
        $info = db_getEventMemberByLink ($_GET["link"]);
        $_SESSION["logged-in"]=$isLink=($info!=NULL);
    }
    if (isset ($_GET["invited"])){
        $invitation = UTILS::getUserInfoByLink($_GET["invited"]);

        $invitationEvent = db_getEvent((int)$invitation[0]);
        $invitationMember = db_getMember(str_repeat("0", 9 - strlen((int)$invitation[1])).''.(int)$invitation[1]);

        if($invitationEvent !== null && $invitationMember !== null){
            $_SESSION["logged-in"]=$isInvited=true;
        }
    }
    /* ******************* */

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
        <label class="control-label" for="login" style="margin-top: 50px;">Логин</label>
        <input type="text" id="login" name="login" placeholder="Email">
        <label class="control-label" for="password">Пароль</label>
        <input type="password" id="password" name="password" maxlength="15">
        <!--<div id="passRec" style="display:none"><a href="/passrec">Забыли пароль?</a></div>-->
        <div id="loginError" class="alert alert-error" style="display:none">Неверно введены логин или пароль</div>
        <div id="emailError" class="alert alert-error" style="display:none">Логином должен быть корректный email</div>
        <div id="ajaxError" class="alert alert-error" style="display:none">Ошибка сервера. Обратитесь к разработчикам.</div>
        <div id="passLengthError" class="alert alert-error" style="display:none">Длинна пароля должна быть не меньше 5 и не больше 15 символов</div>
        <div><a href="/passrec" style="margin-right: 90px;">Забыли пароль?</a> <a href="/signup" title="Если у вас нет учётной записи, зарегистрируйтесь пройдя по ссылке">Создать аккаунт</a></div>
        <button type="submit" id="loginFormBtn" class="btn btn-large btn-primary">Войти</button>
    </form>
</div>
<div class="container">
    <div class="form-logout" style="text-align: center">
        <h3 style="text-align: center">Выйти из аккаунта?</h3>
        <a type="button" id="" href="/" class="btn btn-large btn-primary logoutFormBtn">Выйти</a>
    </div>
</div>
<!-- Edit Member Modal -->
<div id="modalEditMember" data-width="600" class="modal-edit-member modal hide<?php if ($isLink || $isInvited) echo ' fade'; ?>" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-labelledby="editMemberEventTitle">
    <div class="modal-header">
        <button type="button" class="close close-form" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 class="editMemberEventTitle"></h4>
        <a style="margin-left: 0;" id="lnkEventInfo">Информация о мероприятии</a>
        <div style="margin-top: 10px;">
        <?php
           if ($isLink){
               echo '<span class="footer-status"><span class="eventMemberStatus"></span>';
               if ($info["regstate_key"] && $info["regstate_key"]!='05' && $info["regstate_key"]!='03')
                  echo '&nbsp;<a href="#" id="lnkCancelReg">Отменить регистрацию</a>';
               else if ($info["regstate_key"]=='03')
                  echo '&nbsp;<a href="#" id="lnkRestoreReg">Возобновить регистрацию</a>';
               echo '</span>';
           }
        ?>
        </div>
    </div>
    <div class="modal-body">
        <?php require_once 'formTab.php'; ?>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary disable-on-invalid" id="btnDoRegisterGuest">Отправить данные</button>
        <button class="btn" id="btnCancelChanges">Отмена</button>
    </div>
</div>

<script>

$(document).ready(function(){
  $('.logoutFormBtn').click(function(e){
      e.preventDefault();

      var memberId = '<?php echo $memberId; ?>';
      var getSessionId = "<?php print(session_id()); ?>"
      $.get('ajax/login.php?logout', {memberId: memberId, sessionId: getSessionId})
      .done (function() {
          window.location = "/";
          //document.cookie = "sess_last_page; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
      })
      .fail(function() {
          window.location = "/";
      })

  });
    function showSuccessMessage (text, link){
        $("#regSuccessTitle").text (window.currentEventName);
        $("#regSuccessText").html (text);
        $("#regSuccessLink").html (link);
        if (link) $("#regSuccessNotes").show (); else $("#regSuccessNotes").hide ();
        $("#modalEditMember").addClass('hide').modal('hide');
        $("#modalRegSuccess").modal('show');
    }

    $('#btnRegDone').click(function () {
        <?php if (!$isLink) { ?>
            $(this).parents ('div.modal').modal('hide');
        <?php } else { ?>
        var locHost = location.host, host;
        host = locHost.substr(4,3) !== 'dev'? 'https://test.new-constellation.ru/' : 'https://test.new-constellation.ru/';
        window.location = host ;
        <?php } ?>
    });

    $("#lnkEventInfo").click (function (){
        $.getJSON('/ajax/get.php', { event_info: window.currentEventId })
        .done (function(data) {
            $("#eventInfoTitle").text (data.event_name);
            $("#eventInfoText").html(data.event_info);
            $("#sendMsgText").val("");
            $('#modalEventInfo').modal('show');
        });
    });

    $('#lnkCancelReg').click (function (){
        if (confirm ("Вы уверены, что хотите отменить регистрацию?")){
            $.ajax({type: "POST", url: "/ajax/guest.php?cancel", data: {
                event: window.currentEventId,
                member: window.currentEditMemberId
            }})
            .done (function() {
                showSuccessMessage ("Ваша регистрация отменена.");
            });
        }
    });

    $('#lnkRestoreReg').click (function (){
        if (confirm ("Вы уверены, что хотите возобновить регистрацию?")){
            $.ajax({type: "POST", url: "/ajax/guest.php?restore", data: {
                event: window.currentEventId,
                member: window.currentEditMemberId
            }})
            .done (function(){
                showSuccessMessage ("Ваша регистрация возобновлена.");
            });
        }
    });

    $("#btnDoRegisterGuest").click (function (){
        if ($(this).hasClass('disabled')){
            showError("Необходимо заполнить все обязательные поля, выделенные розовым фоном!", true);
            return;
        }
        var form = $('#modalEditMember'), self = this, fieldsValue = getValuesRegformFields(form, true, true);
        if(!fieldsValue.termsUse){
            showError("Необходимо дать согласие на обработку персональных данных", true);
            return;
        }

        $.post("/ajax/guest.php", fieldsValue)
        .done (function(data){
            form.addClass('hide').modal('hide');
            if($(self).hasClass('edit')){
                $('#btnDoRegisterGuest').removeClass('edit').removeClass('guest');
                $('#successSavedDataModal').modal('show');
                window.setTimeout(function() { $('#successSavedDataModal').modal('hide'); }, 1300);
            }
            else{
                <?php if(!isset($memberId)){?>
                    showSuccessMessage (<?php echo $isLink ? "data.messages.save_message, null" : "data.messages.reg_message, data.permalink"; ?>);
                <?php }else{ ?>
                    window.location = '/';
                <?php } ?>
            }
        });
    });

    $('#btnCancelChanges').click (function (){
        $("#modalEditMember").addClass('hide').modal('hide');
    });

    <?php if ($isInvited) { ?>
    var memberId = "<?php echo $invitation[1]; ?>", eventId = "<?php echo $invitation[0]; ?>";

    $.getJSON('/ajax/guest.php?invited', {member: memberId, event: eventId })
     .done (function(data){
        if(data.eventmember){
            window.currentEventId = eventId;
            window.currentEventName = data.eventmember.event_name;
            fillEditMember (memberId, data.eventmember);
            $("#modalEditMember").modal('show');
        }
     });
    <?php } ?>

    <?php if ($isLink) { ?>
    $.getJSON('/ajax/guest.php', { link: "<?php echo $_GET['link']; ?>" })
     .done (function(data){
         window.currentEventId = data.eventmember.event_key;
         window.currentEventName = data.eventmember.event_name;
         fillEditMember (data.eventmember.member_key, data.eventmember);
         $("#modalEditMember").modal('show');
     });

    <?php } ?>

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

  if ($(window).width()<379) {
    console.log($(window).width());
      $("<br>").insertAfter("a[href='/passrec']");
  }

$("#loginFormBtn").click (function (e){
  var loginTrim = $('#login').val();
  loginTrim = loginTrim.trim();
    var emailValidate = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var password = $("#password").val();

    if(!emailValidate.test(loginTrim)){
        $('#loginError').hide();
        $("#emailError").show ();
        e.stopPropagation();
        e.preventDefault();
        return;
    }

    if(password.length < 5 || password.length > 15){
        $("#passLengthError").show ();
        e.stopPropagation();
        e.preventDefault();
        return;
    }

      $.get('ajax/login.php', {checklogin: loginTrim})
      .done (function(data) {
        if (data === 'new') {
            setCookie('log_log', loginTrim, 1);
            setCookie('pas_pas', password, 1);

          window.location = 'signup?redirect=yes';
        }
      });

    $.get('ajax/login.php', { login: loginTrim, password:$("#password").val() })
    .done (function(data) {
        $("#ajaxError").hide ();

        if(data === "error"){
            $("#emailError").hide ();
            $('#loginError').show();
            //$('#passRec').show();
        }
        else{
            $("#loginError").hide ();

            if(!getCookie('sess_last_page')) {
               setCookie('sess_last_page', '/index', 356);
            }
            window.location = "<?php print(substr($appRootPath, 0, -1).(isset ($_GET['returl'])?urldecode($_GET['returl']):$_SESSION["sess_last_page"])); ?>";
            //var getSessionId = "<?php print(session_id()); ?>"
            //(in_array(9, window.user_settings) ? '':'';
            //setCookie('sess_page_default','', 356);
        }
    })
    .fail(function() { $("#ajaxError").show (); });
    return false;
});

</script>
<script src="/js/login.js?v5"></script>
<?php
include_once "footer.php";
?>
