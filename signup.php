<?php
    include_once "header.php";
    if ($memberId) db_logoutAdmin ($memberId, session_id());
    $memberId = db_getMemberIdBySessionId (session_id());
    $isGuest = true;
    include_once 'modals.php';
    include_once "nav.php";

    $login = null;
    $password = null;

    if (isset($_GET["redirect"]) && $_COOKIE["log_log"]) {
      $redirect = true;
      $log_log = $_COOKIE["log_log"];
      $pas_pas = $_COOKIE["pas_pas"];
    }

    if (isset($_GET["code"])){
        $user = UTILS::getUserInfoByRecoveryCode($_GET["code"], true);

        if ($user !== 0){
            $countries1 = db_getCountries(true);
            $countries2 = db_getCountries(false);
            $localities = db_getLocalities();

            $login = $user[0];
            $password = $user[1];
?>

<div class="container signup-container">
    <form class="form-signup-full">
        <div class="control-group row-fluid alert alert-info" style="width: 100%; padding: 10px 0px 10px 0px; text-align: center; margin-bottom: 10px;">
            <span class="close" data-dismiss="alert" style="right: 0; padding-right: 10px;">&times;</span>
            <strong>Для завершения создания аккаунта, заполните обязательные поля и нажмите кнопку "Сохранить"</strong>
        </div>
        <div class="control-group row-fluid">
            <label class="span12">ФИО</label>
            <input class="span12 emName" type="text"  maxlength="70" valid="required" placeholder="Пример: Орлов Пётр Иванович">
        </div>
        <div class="control-group row-fluid">
            <label class="span12">Дата рождения</label>
            <input class="span12 emBirthdate datepicker" type="text" valid="required" placeholder="ДД.ММ.ГГГГ">
        </div>
        <div class="control-group row-fluid">
            <label class="span12">Пол</label>
            <select class="span12 emGender" valid="required">
                <option value='_none_' selected>&nbsp;</option>
                <option value='male'>MУЖ</option>
                <option value='female'>ЖЕН</option>
            </select>
        </div>
        <div class="control-group row-fluid">
            <label class="span12">Гражданство</label>
            <select class="span12 emCitizenship" valid="required" >
                <option value='_none_' selected>&nbsp;</option>
                <?php foreach ($countries1 as $id => $name) {
                    echo "<option value='$id' >".htmlspecialchars ($name)."</option>";
                }?>
                <option disabled="disabled">---------------------------</option>
                <?php foreach ($countries2 as $id => $name) {
                    echo "<option value='$id' >".htmlspecialchars ($name)."</option>";
                }?>
            </select>
        </div>
        <div class="control-group row-fluid">
            <label class="span12">Населённый пункт</label>
            <select class="span12 emLocality" valid="required">
                <option value='_none_' selected >&nbsp;</option>
                <?php
                    foreach ($localities as $id => $name) {
                        echo "<option value='$id' >".htmlspecialchars ($name)."</option>";
                    }
                ?>
            </select>
        </div>
        <div class="control-group row-fluid">
            <label class="span12">Если населённого пункта в списке нет, то укажите его здесь:</label>
            <input class="span12 emLocalityNew" type="text" maxlength="50" placeholder="Начните вводить название">
        </div>
        <div style="margin-top: 10px">
            <input style="margin-top: 0" type="checkbox" id="btn-agreement" class="agreement-use" valid="required">
            <label style="display: inline; font-size: 14px" for="btn-agreement">подтверждаю согласие на обработку моих персональных данных</label>
        </div>
        <div id="textError" class="alert alert-error" style="display:none">Поля не могут быть пустыми</div>

        <div class="alert alert-error ajaxError" style="display:none">Ошибка сервера. Обратитесь к разработчикам.</div>
        <button type="text" id="signUpFormBtn" class="btn btn-large btn-primary">Сохранить</button>
    </form>
</div>

<script>
$(document).ready(function(){

    //window.location = '/profile';
    $("[valid|='required']").keyup (function () {
        handleSignUpFormBtn();
    });

    $("[valid|='required']").change (function () {
        handleSignUpFormBtn();
    });

    $('.emLocalityNew').keyup(function(){
        if($(this).val().trim() !== ''){
            $(".emLocality").parents('.control-group').removeClass('error');
        }
        else{
            $(".emLocality").change();
        }
        handleSignUpFormBtn();
    });

    function handleSignUpFormBtn(){
        var isSetDisabled = false, value;
        $(".form-signup-full [valid|='required']").each(function(){
            value = $(this).val().trim();
            if($(this).hasClass('emLocality')){
                if(value === '_none_' && $(".emLocalityNew").val().trim() === ''){
                    isSetDisabled = true;
                }
            }
            else if($(this).hasClass('emName')){
                if(value === ''){
                    isSetDisabled = true;
                }
            }
            else if($(this).hasClass('agreement-use')){
                if(!$(this).is(":checked")){
                    isSetDisabled = true;
                }
            }
            else{
                if(value === '_none_' || value === ''){
                    isSetDisabled = true;
                }
            }
        });
        $('#signUpFormBtn').attr('disabled',isSetDisabled);
    }

    handleFullSignupFields();

    function handleFullSignupFields(){
        $(".emName").keyup();
        $(".emBirthdate").val('').keyup();
        $(".emGender").val('_none_').change();
        $(".emCitizenship").val('_none_').change();
        $(".emLocality").val('_none_').change();
    }

    $("#signUpFormBtn").click (function (e){
        e.stopPropagation();
        e.preventDefault();

        var login = '<?php echo $login; ?>',
            password = '<?php echo $password; ?>',
            name = $(".emName").val().trim(),
            birthDate = $(".emBirthdate").val().trim(),
            gender = $(".emGender").val().trim(),
            citizenship = $(".emCitizenship").val().trim(),
            locality = $(".emLocality").val().trim(),
            newLocality = $(".emLocalityNew").val().trim();

        if($(this).attr('disabled') === 'disabled'){
            $("#textError").show ();
            return;
        }
        else{
            $("#textError").hide ();
            $(".form-signup-full").css('display', 'none');
            showHint('Ваши данные отправлены для создания учетной записи.');
        }

        $.get('/ajax/login.php?signup', {
            signupLogin: login,
            password:password,
            name : name,
            birthDate : parseDate(birthDate),
            gender : gender === 'male' ? 1 : 0,
            citizenship : citizenship,
            locality : locality,
            newLocality : newLocality
        })
        .done (function(data) {
            $(".ajaxError").hide ();

            /*if(data === "error"){
                $("#emailError").hide ();
                $('#loginError').show();
            }
            else{*/
              showHint('Данные сохранены!');
              setTimeout(function () {
                window.location = '/index';
              }, 1500);
            //}
        })
        .fail(function() { $(".ajaxError").show (); });
        return false;
    });
});
</script>
<?php
            include_once "footer.php";
            die();
        }
        else{
            echo '<div class="container signup-container"><div class="alert alert-danger" role="alert">Эта ссылка недействительна или просрочена. Для создания аккаунта пройдите процедуру повторно.</div></div>';
            include_once "footer.php";
        }
    }
    else{
?>

<div class="container signup-container">
    <h3 class="title" style="display: none; text-align: center;">
        <div>Вам отправлена ссылка для подтверждения вашего электронного адреса.</div>
        <div>Пожалуйста, проверьте почту. Ссылка действительна в течение 10 минут.</div>
    </h3>
    <form class="form-signup">
        <div class="alert alert-warning" style="width: 100%; padding: 10px 7px 10px 10px; margin-bottom: 10px;">
            <span class="close" data-dismiss="alert" style="right: 0">&times;</span>
            <span>Создавайте учётную запись только для себя. По всем вопросам обращайтесь в <a href="#" data-toggle="modal" data-target="#messageAdmins" aria-hidden="true" title="Кликните, 0чтобы отправить сообщение службе поддержки">службу поддержки</a>.
            </span>
        </div>
        <label class="control-label" for="login">Логин (Электронная почта)</label>
        <input type="text" class="login" name="login" placeholder="Email" valid="required">

        <label class="control-label" for="password">Пароль</label>
        <input type="password" class="password" name="password" valid="required" placeholder="Придумайте пароль" maxlength="15">

        <label class="control-label" for="password">Подтверждение пароля</label>
        <input type="password" class="passwordConfirm" valid="required" placeholder="Повторите пароль" name="passwordConfirm" maxlength="15">

        <div id="loginError" class="alert alert-error" style="display:none; width: 100%; padding: 10px 7px 10px 10px; text-align: center; margin-bottom: 10px;">Учетная запись с таким логином уже существует. <a href="/login">Войти</a>?</div>
        <div id="emailError" class="alert alert-error" style="display:none; width: 100%; padding: 10px 7px 10px 10px; text-align: center; margin-bottom: 10px;">Логином должен быть корректный email</div>
        <div id="passError" class="alert alert-error" style="display:none; width: 100%; padding: 10px 7px 10px 10px; text-align: center; margin-bottom: 10px;">Пароли не соответствуют друг другу</div>
        <div id="passLengthError" class="alert alert-error" style="display:none; width: 100%; padding: 10px 7px 10px 10px; text-align: center; margin-bottom: 10px;">Длинна пароля должна быть не меньше 5 символов и не больше 15 символов</div>
        <div id="ajaxError" class="alert alert-error" style="display:none; width: 100%; padding: 10px 7px 10px 10px; text-align: center; margin-bottom: 10px;">Ошибка сервера. Обратитесь к разработчикам.</div>
        <div class="alert alert-info" style="width: 100%; padding: 10px 7px 10px 10px; margin-bottom: 0;">
            <span class="close" data-dismiss="alert" style="right: 0">&times;</span>
            <span>После нажатия кнопки "Создать" вам будет отправлено письмо со ссылкой для подтверждения адреса электронной почты. Ссылка действительна в течение 10 минут. При переходе по ссылке вам будет предложено заполнить данные для создания аккаунта.</span>
        </div>
        <div style="margin-top: 10px">
            <input style="margin-top: 0" type="checkbox" id="btn-terms-use" class="terms-use" valid="required">
            <label style="display: inline; font-size: 12px" for="btn-terms-use">я принимаю условия<a href="https://drive.google.com/open?id=1krSycWkozm2Y-UNwHwG1OuMU81LgzbB76EVVfszgMYU" target="_blank"> Пользовательского соглашения</a> и даю <a href="https://drive.google.com/open?id=1-6shNo_9D-nrssOVo9SyLTEnjh0aql2pU7USypcdCj0" target="_blank">согласие</a> на обработку моих персональных данных</label>
        </div>
        <button style="margin-top: 10px;" id="loginFormBtn" class="btn btn-large btn-primary">Создать</button>
        <div style="margin-left: 50px; margin-top: 20px;"><a href="/login" >У меня уже есть аккаунт <i class="fa fa-sign-in" aria-hidden="true"></i> Войти.</a></div>
    </form>
</div>
<?php
    }
?>

<script>
$(document).ready(function(){

  if ($(window).width()<551) {
      $('.alert-warning').css('margin-top', '50px');
  }

  var redirect = '<?php echo $redirect; ?>';
  var log_log = '<?php echo $log_log; ?>';
  var pas_pas = '<?php echo $pas_pas; ?>';

    if (redirect) {

      setTimeout(function () {
        $('.login').val(log_log);
        $('.password').val(pas_pas);
        $('.passwordConfirm').val('');
        $('.login').removeClass('error');
        $('.password').removeClass('error');
        setCookie('log_log', '', 1);
        setCookie('pas_pas', '', 1);

      }, 500);

      $('.alert-warning .close').next().text('Адрес, который вы ввели, не зарегистрирован в системе. Для создания нового аккаунта повторите пароль.');
    }

    $(".login").focus();

    $('.login').keydown(function(){
        $("#emailError").hide ();
    });

    $('.password, .passwordConfirm').keydown(function(){
        $("#passError, #passLengthError").hide ();
    });

    $('.password').keydown(function(){
        $(".passwordConfirm").val ('');
    });

    $("[valid|='required']").keydown (function () {
        handleLoginFormBtn();
    });

    $("[valid|='required']").change (function () {
        handleLoginFormBtn();
    });

    function handleLoginFormBtn(){
        var isSetDisabled = false, value;
        $(".form-signup [valid|='required']").each(function(){
            value = $(this).val().trim();
            if($(this).hasClass('terms-use') && $(this).prop('checked') === false){
                isSetDisabled = true;
            }
            else{
                if(value === ''){
                    isSetDisabled = true;
                }
            }
        });
        $('#loginFormBtn').attr('disabled',isSetDisabled);
    }

    handleSignupFields();

    function handleSignupFields(){
        $(".login").val('').keyup();
        $(".password").val('').keyup();
        $(".passwordConfirm").val('').keyup();
        $(".terms-use").prop('checked', false).change();
    }
});

$("#loginFormBtn").click (function (e){
    e.stopPropagation();
    e.preventDefault();

    var password = $(this).parents(".form-signup").find(".password").val();
    var login = $(this).parents(".form-signup").find(".login");
    login.val(login.val().trim());
    var passwordConfirm = $(this).parents(".form-signup").find(".passwordConfirm").val();

    if(!isEmailvalid(login.val())){
        login.focus();
        $("#emailError").show ();
        e.stopPropagation();
        e.preventDefault();
        return;
    }
    else{
        $("#emailError").hide ();
    }

    if(password !== passwordConfirm){
        $('.password').focus();
        $("#passError").show ();
        e.stopPropagation();
        e.preventDefault();
        return;
    }
    else{
        $("#passError").hide ();
    }

    if(password.length < 5 || password.length > 15){
        $("#passLengthError").show ();
        e.stopPropagation();
        e.preventDefault();
        return;
    }
    else{
        $("#passLengthError").hide ();
    }

    $.get('/ajax/login.php', { signupLogin: login.val() })
    .done (function(data) {
        $("#ajaxError").hide ();

        if(data === "error"){
            $("#emailError").hide ();
            $('#loginError').show();
        }
        else{
            $('.signup-container h3').css('display', 'block');
            $('.signup-container form').css('display', 'none');
            $.get('/ajax/login.php', { signupLogin: login.val(), password:password });
        }
    })
    .fail(function() { $("#ajaxError").show (); });
    return false;
});


/*
$("#btnDoSendEventMsgAdmins").click (function (){
    if ($(this).hasClass('disabled')) return;
    var message = $("#sendMsgTextAdmin").val(), name = $("#sendMsgNameAdmin").val(), email = $("#sendMsgEmailAdmin").val();
    $("#messageAdmins").modal('hide');

    $.ajax({type: "POST", url: "/ajax/set.php", data: {event:"", message: message, name: name, email:email, admins:"События admin"}})
     .done (function() {messageBox ('Ваше сообщение было отправлено', $('#messageAdmins'));});
});
*/

</script>

<?php
include_once "footer.php";
?>
