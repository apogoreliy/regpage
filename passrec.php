<?php
    include_once "header.php";
    include_once "nav.php";

    if (isset($_POST["code"]) && isset($_POST["password"])){
        $login = UTILS::getMemberKeyByRecoveryCode($_POST["code"]);

        if ($login !== 0){
            db_setUserPasswordByLogin($login, $_POST["password"]);
            db_loginUserByLogin (session_id(), $login);
?>
<script>
            window.location = '/';
</script>
<?php
            die();
        }
    }
    else
        $login = NULL;
?>

<div class="container">
    <div class="passrec-container">
<?php
    if (isset($_POST["email"])){
        $email = $_POST["email"];
        $user = db_getUserByLogin($email);

        if (!$user)
            $error = "Пользователь с таким email не найден. Вы можете <a href='/signup'>создать учетную запись</a> если вас её еще нет.";
        else
        {
            $code = UTILS::getRecoveryCodeByUserInfo($user);
            $body = "Здравствуйте!".
                    "<p>Получен запрос на восстановление вашего пароля для сайта reg-page.ru.</p>".
                    "<p>Чтобы установить новый пароль перейдите по ссылке: <a href='https://$host/passrec?code=$code'>https://$host/passrec?code=$code</a> (ссылка действительна в течение 10 минут)</p>" .
                    "<p>Если вы не запрашивали восстановление пароля, просто проигнорируйте это письмо или напишите нам.</p>" .
                    "<p>Команда сайта регистрации</p>";

            $title = "Восстановление пароля для сайта reg-page.ru";
            $headers = "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: Сайт регистрации<info@reg-page.ru>\r\nReply-To: Сайт регистрации<info@reg-page.ru>\r\n";

            EMAILS::sendEmail(htmlspecialchars($email), $title, $body);
            //mail(htmlspecialchars($email), $title, $body, $headers);
        }
    }
    else if ($login===NULL && isset($_GET["code"])){
        $login = UTILS::getMemberKeyByRecoveryCode($_GET["code"]);
    }

    if (isset($_POST["email"]) && !isset($error))
        echo '<h3 class="title">Вам отправлена ссылка для восстановления пароля. Пожалуйста, проверьте почту.</h3>';
    else if ($login){
?>
        <h2 class="title">Введите новый пароль два раза</h2>
        <form id="change-password" role="form" method="POST">
            <input name="code" type="hidden" value="<?php echo htmlspecialchars($_GET['code'])?>" />
            <div id="passgroup" class="form-group">
                <input type="password" maxlength="50" minlength="5" class="form-control input-lg" id="pwd1" name="password"
                       placeholder="Новый пароль" required autofocus>
                <input type="password" maxlength="50" minlength="5" class="form-control input-lg" id="pwd2" name="password2"
                       placeholder="Новый пароль еще раз" required autofocus>
                <p class="help-block" id="helpPasswordNoMatch" style="font-weight: bold;display: none;">Несоответствие паролей</p>
            </div>
            <button id="btnChangePassword" type="submit" class="btn btn-success btn-lg" style="width: 100%" disabled>Сменить пароль</button>
        </form>
<?php }
    else { ?>
        <h2 class="title">ВОССТАНОВЛЕНИЕ ПАРОЛЯ</h2>
        <?php if ($login===0) { ?>
            <div class="alert alert-danger" role="alert">Эта ссылка недействительна или уже просрочена. Пожалуйста, закажите новую.</div>
        <?php } else { ?>
            <p>Введите ваш email и мы вышлем на него ссылку для восстановления пароля</p>
        <?php
            if(isset($memberId)){
                ?>
                <script>
                    window.location = '/passrec';
                </script>
            <?php
                db_logoutAdmin($memberId, session_id());
            }
        } ?>

        <form class="form-inline" role="form" method="POST">
            <div class="form-group">
                <label class="sr-only" for="email">Email</label>
                <input type="email" maxlength="50" class="controls" id="email" name="email" placeholder="Email" required autofocus>
            </div>
            <button type="submit" class="btn btn-success">Отправить</button>
        </form>
        <?php if (isset($error)) echo "<div class='alert alert-danger' role='alert'>$error</div>";
    } ?>
    </div>
</div>

<script>
    function checkPassword () {
        var pwd1 = $('#pwd1').val();
        var pwd2 = $('#pwd2').val();
        if (pwd1.length > 0 || pwd2.length > 0) {
            if (pwd1 == pwd2) {
                $("#passgroup").removeClass("has-error");
                $("#helpPasswordNoMatch").hide();
                $("#btnChangePassword").prop('disabled', false);
            }
            else {
                $("#passgroup").addClass("has-error");
                $("#helpPasswordNoMatch").show();
                $("#btnChangePassword").prop('disabled', true);
            }
        }
    }

    $(function () {
        $("#pwd1").keyup(function (event) {
            event.preventDefault();
            if ($("#pwd2").val().length>0) {
                $("#pwd2").val('');
                checkPassword();
            }
            if (event.which==13) {
                $("#pwd2").focus();
                checkPassword ();
            }
        });

        $("#pwd2").keyup(function (event) {
            checkPassword ();
        });
    });
</script>
