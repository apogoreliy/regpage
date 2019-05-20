<?php
    include_once "header.php";
    include_once "nav.php";
    include_once './modals.php';

    $countries1 = db_getCountries(true);
    $countries2 = db_getCountries(false);
    $localities = db_getLocalities();
    $member = db_getProfile($memberId);
    $adminMember = db_getAdminAsMember($memberId);
    $currentLogin = $member['email'];
    $isMemberAdmin = db_isAdmin($memberId);
?>
<div class="container" id="user-profile">
    <div class="controls">
    <div class="control-group row-fluid" style="margin-bottom: 10px" >
        <label style="display:inline">Логин</label>
        <span> <?php echo $currentLogin; ?></span>
    </div>
    <div style="margin: 10px 0 15px 0;" >
        <div class="btn btn-change-login" style="padding: 5px; display: inline">Изменить логин</div>
        <div class="btn btn-change-password" style="padding: 5px; display: inline">Изменить пароль</div>
    </div>
    <div class="control-group row-fluid" style="margin-bottom: 5px;">
        <label class="span12">ФИО<span class="example">Пример: Орлов Пётр Иванович</span></label>
        <input class="span12 emName"  style="margin-bottom: 0;" tooltip="tooltipName" value="<?php echo $member['name']; ?>" type="text" maxlength="70" placeholder="Фамилия Имя Отчество">
        <i class="icon-pencil unblock-input" style="display: none;"></i>
        <span style="margin-left: 0; font-size: 11px;" class="example">Если фамилия недавно изменялась, укажите в скобках прежнюю фамилию</span>
    </div>
    <div class="control-group row-fluid">
        <label class="span12">Дата рождения</label>
        <input class="span12 emBirthdate datepicker" value="<?php echo UTILS::formatDate($member['birth_date']); ?>" type="text"  maxlength="10" placeholder="ДД.ММ.ГГГГ">
    </div>
    <div class="control-group row-fluid">
        <label class="span12">Пол</label>
        <select class="span12 emGender" value="<?php echo $member['gender']; ?>">
            <option value='_none_'>&nbsp;</option>
            <option value='male' <?php echo $member['gender'] == 'male' ? "selected" : ""; ?> >MУЖ</option>
            <option value='female' <?php echo $member['gender'] == 'female' ? "selected" : ""; ?> >ЖЕН</option>
        </select>
    </div>
    <div class="control-group row-fluid">
        <label class="span12">Гражданство</label>
        <select class="span12 emCitizenship">
            <option value='_none_'>&nbsp;</option>
            <?php foreach ($countries1 as $id => $name) {
                echo "<option value='$id'";
                echo $member['citizenship_key'] == $id ? 'selected' : '';
                echo " >".htmlspecialchars ($name)."</option>";
            }?>
            <option disabled="disabled">---------------------------</option>
            <?php foreach ($countries2 as $id => $name) {
                echo "<option value='$id'";
                echo $member['citizenship_key'] == $id ? 'selected' : '';
                echo " >".htmlspecialchars ($name)."</option>";
            }?>
        </select>
    </div>
    <div class="control-group row-fluid">
        <label class="span12">Населённый пункт</label>
        <select class="span12 emLocality">
            <option value='_none_' selected>&nbsp;</option>
            <?php
                foreach ($localities as $id => $name) {
                    echo "<option value='$id'";
                    echo $member['locality_key'] == $id ? 'selected' : '';
                    echo " >".htmlspecialchars ($name)."</option>";
                }
            ?>
        </select>
    </div>
    <div style="margin-bottom: 10px; color: cadetblue;">
        <span class="handle-new-locality">Вашего населённого пункта нет в списке?</span>
    </div>
    <div class="control-group row-fluid block-new-locality">
        <input class="span12 emLocalityNew" placeholder="Введите название населённого пункта в этом поле" value="<?php echo $member['new_locality']; ?>" type="text" maxlength="50">
    </div>
    <!--<div class="control-group row-fluid">
        <label class="span12">Почтовый адрес</label>
        <input style="margin-bottom: 0;" class="span12 emAddress" value="<?php //echo $member['address']; ?>" type="text" maxlength="150">
        <span style="margin-left: 0;" class="example">Пример: Россия, 180000, Псковская обл., г. Псков, ул. Труда 5, кв. 6</span>
    </div>
    <div style="margin-top: 5px;" class="control-group row-fluid">
        <label class="span12">Домашний телефон</label>
        <input class="span12 emHomePhone" type="text" value="<?php echo $member['home_phone']; ?>" maxlength="50" placeholder="+XXXXXXXXXX">
    </div>    -->
    <div class="control-group row-fluid" style="margin-bottom: 5px;">
        <label class="span12">Мобильный телефон</label>
        <input style="margin-bottom: 0;" class="span12 emCellPhone" value="<?php echo $member['cell_phone']; ?>" type="text" maxlength="50" placeholder="+XXXXXXXXXX" tooltip="tooltipCellphone">
        <span style="margin-left: 0;" class="example">Если имеется несколько номеров, укажите их через запятую</span>
    </div>
    <!--<div style="margin-top: 5px;" class="control-group row-fluid">
        <label class="span12">Уровень английского</label>
        <select class="span12 emEnglishLevel">
            <option value="0" <?php echo $member['english'] == 0 ? "selected" : ""; ?> >Не владею</option>
            <option value="1" <?php echo $member['english'] == 1 ? "selected" : ""; ?> >Начальный уровень</option>
            <option value="2" <?php echo $member['english']==2 ? "selected" : ""; ?> >Хороший уровень</option>
        </select>
    </div>
    <div class="control-group row-fluid">
        <div class="handle-passport-info" style="margin-bottom: 10px;color: cadetblue" ><strong>Паспортные данные</strong><i style="margin-left: 10px;" class="fa fa-chevron-down fa-lg"></i></div>
    </div>
    <div class="passport-info" style="display: none;">
        <div class="control-group row-fluid ">
            <label class="span12">Тип документа</label>
            <div class="control-group row-fluid">
                <select class="span12 emDocumentType">
                    <option value='_none_'>&nbsp;</option>
                    <?php //foreach (db_getDocuments() as $id => $name) {
                       // echo "<option value='$id'";
                      //  echo $member['document_key'] == $id ? 'selected' : '';
                      //  echo " >".htmlspecialchars ($name)."</option>";
                    //}?>
                </select>
            </div>
        </div>
        <div class="control-group row-fluid ">
            <label class="span12">Номер документа</label>
            <div class="control-group row-fluid">
                <input class="span12 emDocumentNum" value="<?php //echo $member['document_num']; ?>" type="text" maxlength="20">
            </div>
        </div>
        <div class="control-group row-fluid ">
            <label class="span12">Дата выдачи</label>
            <div class="control-group row-fluid">
                <input class="span12 emDocumentDate datepicker" value="<?php //echo UTILS::formatDate($member['document_date']); ?>" type="text" maxlength="10" placeholder="ДД.ММ.ГГГГ">
            </div>
        </div>
        <div class="control-group row-fluid ">
            <label class="span12">Кем выдан</label>
            <div class="control-group row-fluid">
                <input class="span12 emDocumentAuth" value="<?php //echo $member['document_auth']; ?>" type="text" maxlength="150">
            </div>
        </div>
        <div class="control-group row-fluid ">
            <label class="span12">Номер загранпаспорта</label>
            <div class="control-group row-fluid">
                <input class="span12 emDocumentNumTp" value="<?php //echo $member['tp_num']; ?>" type="text" maxlength="20">
            </div>
        </div>
        <div class="control-group row-fluid">
            <label class="span12">Страна, которой выдан паспорт</label>
            <div class="control-group row-fluid">
                <input class="span12 emDocumentAuthTp" value="<?php //echo $member['tp_auth']; ?>" type="text" maxlength="20">
            </div>
        </div>
        <div class="control-group row-fluid">
            <label class="span12">Дата окончания действия загранпаспорта</label>
            <div class="control-group row-fluid">
                <input class="span12 emDocumentDateTp datepicker" value="<?php //echo UTILS::formatDate($member['tp_date']); ?>" type="text" maxlength="10" placeholder="ДД.ММ.ГГГГ">
            </div>
        </div>
        <div style="margin-bottom: 5px;" class="control-group row-fluid">
            <label class="span12">Фамилия и имя латинскими буквами</label>
            <div class="control-group row-fluid">
                <input style="margin-bottom: 0;" class="span12 emDocumentNameTp" value="<?php //echo $member['tp_name']; ?>" type="text" maxlength="150">
                <span style="margin-left: 0;" class="example">как указано в загранпаспорте</span>
            </div>
        </div>
    -->
    </div>
    <div class="control-group row-fluid">
        <input style="margin-top: 0" type="checkbox" id="btn-terms-use" class="emDispatch" <?php echo $adminMember['notice_info'] == 1 ? 'checked' : '' ?>>
        <label style="display: inline; font-weight: normal" for="btn-terms-use">получать уведомления о конференциях и обучениях</label>
    </div>
    <?php if($isMemberAdmin){ ?>
    <div class="control-group row-fluid" style="margin-bottom: 10px;">
        <input style="margin-top: 0" type="checkbox" id="btn-notice" class="emNotice" <?php echo $adminMember['notice_reg'] ==1 ? 'checked' : '' ?>>
        <label style="display: inline; font-weight: normal" for="btn-notice">получать уведомления о регистрации участников</label>
    </div>
    <?php } ?>
    <div class="control-group row-fluid " style="margin-bottom: 10px; margin-top: 5px;">
        <a class="remove-account" href="#">Удалить учётную запись</a>
        <!--<div class="btn btn-danger remove-account" style="width: 100%; padding: 5px 0;">Удалить учётную запись</div>-->
    </div>
    <div class="control-group row-fluid " style="margin-bottom: 10px; margin-top: 5px;">
        <div class="btn btn-success saveProfile" style="width: 100%; padding: 5px 0;">Сохранить</div>
    </div>
    <div class="control-group row-fluid " style="margin-bottom: 10px; margin-top: 5px;">
        <a class="btn btn-primary" style="width: 100%; padding: 5px 0;" href="/profile" style="color: white;" >Отмена</a>
    </div>
</div>
</div>
<div style="clear: both;"></div>

<div id="reasonDeleteAccountModal" class="modal hide fade" tabindex="-1" role="dialog"  aria-labelledby="regEndedTitle" aria-hidden="true">
    <div class="modal-header">
        <h3 style="text-align: center;">Причина удаления учётной записи</h3>
    </div>
    <div class="modal-body" style="padding: 15px 30px 15px 15px">
        <input style="width: 100%" type="text" placeholder="Укажите причину" class="reason-remove-account">
    </div>
    <div class="modal-footer">
        <button class="btn btn-danger confirmRemoveAccount" data-dismiss="modal" aria-hidden="true">Ok</button>
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Отменить</button>
    </div>
</div>

<div id="modalChangeLogin" class="modal hide fade" tabindex="-1" role="dialog"  aria-labelledby="regEndedTitle" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 style="text-align: center;">Изменение логина</h3>
    </div>
    <div class="modal-body">
        <input class="new-login" style="width: 100%; padding: 5px 0 5px 5px;" type="text" placeholder="Введите новый email">
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary btn-save-login">Изменить</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>

<div id="modalChangePassword" class="modal hide fade" tabindex="-1" role="dialog"  aria-labelledby="regEndedTitle" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 style="text-align: center;">Изменение пароля</h3>
    </div>
    <div class="modal-body">
        <div class="block-password">
            <input style="width: 100%; padding: 5px 0 5px 5px; margin-top: 10px" class="span12 emPassword" placeholder="Введите старый пароль" type="password" maxlength="50">
            <div class="alert alert-warning passRecProfile" style="display: none; margin-top: 10px; width: 100%; padding: 10px 0px 10px 0px; text-align: center; margin-bottom: 10px;">
                <span>Пароль не верный</span>
                <a href="/passrec">Забыли пароль?</a>
            </div>
            <input style="width: 100%; padding: 5px 0 5px 5px; margin-top: 10px" class="span12 emNewPassword" placeholder="Введите новый пароль" type="password" maxlength="50">
            <input style="width: 100%; padding: 5px 0 5px 5px; margin-top: 10px" class="span12 emPasswordConfirm" placeholder="Повторите новый пароль" type="password" maxlength="50">

            <div class="alert alert-danger" id="passError" style="display: none; width: 100%; padding: 10px 0px 10px 0px; text-align: center; margin-bottom: 10px;">
                Пароли не соответствуют друг другу
            </div>
            <div class="alert alert-danger" id="passLengthError" style="display: none; width: 100%; padding: 10px 0px 10px 0px; text-align: center; margin-bottom: 10px;">
                Длинна пароля должна быть не меньше 5 символов
            </div>
            <div class="alert alert-danger" id="passWhiteSpacesError" style="display: none; width: 100%; padding: 10px 0px 10px 0px; text-align: center; margin-bottom: 10px;">
                Пароль не может содержать пробельные символы
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary btn-save-password">Изменить</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>

<!-- Success Saved Profile Data Modal -->
<div id="modalShowChangeLoginInfo" style="z-index: 9999;" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-body">
        <h4 style="text-align: center;">
            <div>Вам отправлена ссылка для подтверждения вашего электронного адреса. Пожалуйста, проверьте почту. Ссылка действительна в течение 10 минут.</div>
        </h4>
    </div>
    <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Ok</button>
    </div>
</div>

<?php
include_once "footer.php";
 ?>

<script>
    $(".remove-account").click(function(e){
        e.preventDefault();
        $("#reasonDeleteAccountModal").modal('show');
    });

    $(".confirmRemoveAccount").click(function(){
        var reason = $(".reason-remove-account").val().trim();

        if(reason === ''){
            showError("Необходимо указать причину удаления учётной записи");
            return;
        }

        $.post('/ajax/login.php?remove_account', {member: '<?php echo $memberId; ?>', reason : reason}).done(function(){
            window.location = '/';
        });
    });

    $(".btn-change-login").click(function(){
        $(".new-login").val('');
        $("#modalChangeLogin").modal('show');
        setTimeout(function(){
            $(".new-login").focus();
        }, 1000);
    });

    $(".btn-change-password").click(function(){
        $(".btn-save-password").attr('disabled', true);
        $(".emPassword, .emNewPassword, .emPasswordConfirm").val('');
        $("#modalChangePassword").modal('show');
        setTimeout(function(){
            $(".emPassword").focus();
        }, 1000);
    });

    function handlePasswordAlerts(){
        var confirmPassVal = $('.emPasswordConfirm').val(),
            passVal = $('.emNewPassword').val(),
            pass = $('.emPassword').val(),
            isPasswordHasError = false;

        if((pass.length < 5 && pass.length >= 0) || (passVal.length < 5 && passVal.length >= 0) || (passVal !== confirmPassVal && confirmPassVal.length >= 0)){
            isPasswordHasError = true;
        }

        if(isStringContainsWhitespace(confirmPassVal) || isStringContainsWhitespace(passVal)) {
            $('#passWhiteSpacesError').show();
            isPasswordHasError = true;
        }
        else{
            $('#passWhiteSpacesError').hide();
        }

        if(passVal.length < 5 && passVal.length > 0){
            $('#passLengthError').show();
            isPasswordHasError = true;
        }
        else{
            $('#passLengthError').hide();
        }

        if(passVal !== confirmPassVal && confirmPassVal.length > 0){
            $('#passError').show();
            isPasswordHasError = true;
        }
        else{
            $('#passError').hide();
        }

        $(".btn-save-password").attr('disabled', isPasswordHasError);
        return isPasswordHasError;
    }

    $('.emPassword').keyup(function(){
        var pass = $(this).val().trim();

        if(pass.length >= 5){
            $.post('/ajax/get.php?check_password', {pass: pass})
            .done(function(data){
                handlePasswordAlerts();
                if(data.result){
                    $(".passRecProfile").css('display', 'none');
                }
                else{
                    $(".passRecProfile").css('display', 'block');
                }
            });
        }
        else{
            handlePasswordAlerts();
            $(".passRecProfile").css('display', 'none');
        }
    });

    $('.emNewPassword, .emPasswordConfirm').keyup(function(){
        handlePasswordAlerts();
    });

    /*
    $("#btnDoSendEventMsgAdmins").click (function (){
        if ($(this).hasClass('disabled')) return;

        $.ajax({type: "POST", url: "/ajax/set.php", data: {event:"", message: $("#sendMsgTextAdmin").val(), name:$("#sendMsgNameAdmin").val(), email:$("#sendMsgEmailAdmin").val(), admins:"События index.php"}})
        .done (function() {messageBox ('Ваше сообщение отправлено службе поддержки', $('#messageAdmins'));
            $("#sendMsgTextAdmins").val('');
        });
    });
    */

    function isStringContainsWhitespace(str){
        return str.indexOf(' ') >= 0;
    }

    $(".btn-save-login").click(function(){
        var login = $(".new-login").val().trim();
        var currentLogin = '<?php echo $currentLogin; ?>';

        if(currentLogin.trim() === login){
            showError("Ваш новый логин такой же как и старый.");
            return;
        }
        else if(login === '' || !isEmailvalid(login)){
            login === '' ? showError("Логин не может быть пустой строкой") : showError("Логин некорректный. Проверьте правильность написания");
            return;
        }
        else{
            $("#modalChangeLogin").modal('hide');
            $("#modalShowChangeLoginInfo").modal('show');
            $("#modalShowChangeLoginInfo").parents('.modal-scrollable').css('z-index', 9999);

            $.post('/ajax/set.php?set_login', {login : login})
            .done(function(data){
                if(data.result){
                    window.location = '/login';
                }
                else{
                    showError("Укажите логин");
                }
            });
        }
    });

    $(".btn-save-password").click(function(){
        var pass = $(".emPassword").val().trim();
        var newPass = $(".emNewPassword").val().trim();
        var newPassConfirm = $(".emPasswordConfirm").val().trim();

        if($(".btn-save-password").attr('disabled') === 'disabled' || handlePasswordAlerts() || (newPass!== '' && newPass !== newPassConfirm)){
            $(".btn-save-password").attr('disabled', true);
            showError("Введите корректные данные для паролей");
            return;
        }

        $.post('/ajax/set.php?set_password', {pass:pass, newPass: newPass})
        .done(function(data){
            if(data.result){
                $("#modalChangePassword").modal('hide');
                showHint("Пароль успешно изменен!");
            }
            else{
                $(".btn-save-password").attr('disabled', true);
                showError("Введите корректные данные для паролей");
            }
        });
    });

    $('.saveProfile').click(function(){
        var el = $('#user-profile');

        $.post("/ajax/set.php?set_profile",{
            name: el.find(".emName").val (),
            birth_date: parseDate (el.find(".emBirthdate").val()),
            cell_phone: el.find(".emCellPhone").val(),
            locality_key: el.find(".emLocality").val () == "_none_" ? '' : el.find(".emLocality").val(),
            new_locality: el.find(".emLocality").val () == "_none_" ? el.find(".emLocalityNew").val() : '',
            gender: el.find('.emGender').val(),
            citizenship_key: el.find(".emCitizenship").val () == "_none_" ? "" : el.find(".emCitizenship").val(),
            notice_info : el.find('.emDispatch').prop('checked') === true ? 1 : 0,
            notice_reg : el.find('.emNotice').prop('checked') === true ? 1 : 0
        })
        .done (function(){
            $('.user-name').text(el.find(".emName").val ());
            showHint("Ваши данные успешно сохранены!");
        });
    });
</script>
<script src="/js/profile.js?v1"></script>
