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
        $_SESSION["logged-in"]=$isInvited=true;
      /*  if($invitationEvent !== null && $invitationMember !== null){
            $_SESSION["logged-in"]=$isInvited=true;
        }*/
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
        <button type="button" id="escapeFormInvites" class="btn btn-large btn-primary" style="margin-left: auto; margin-right: auto">Вернуться на главную страницу</button>
  </form>
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
                    window.location = location.host;
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

});

$("#escapeFormInvites").click (function (){
      window.location = '/';
});

</script>
<?php
include_once "footer.php";
?>
