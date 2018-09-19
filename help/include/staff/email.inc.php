<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');
$info=array();
$qstr='';
if($email && $_REQUEST['a']!='add'){
    $title='Обновить Email';
    $action='update';
    $info=$email->getInfo();
    $info['id']=$email->getId();
    if($info['mail_delete'])
        $info['postfetch']='delete';
    elseif($info['mail_archivefolder'])
        $info['postfetch']='archive';
    else
        $info['postfetch']=''; //nothing.
    if($info['userpass'])
        $passwdtxt='Для смены пароля, введите новые пароль.';

    $qstr.='&id='.$email->getId();
}else {
    $title='Добавить новый Email';
    $action='create';
    $info['ispublic']=isset($info['ispublic'])?$info['ispublic']:1;
    $info['ticket_auto_response']=isset($info['ticket_auto_response'])?$info['ticket_auto_response']:1;
    $info['message_auto_response']=isset($info['message_auto_response'])?$info['message_auto_response']:1;
    $qstr.='&a='.$_REQUEST['a'];
}
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>
<h2>Email адрес</h2>
<form action="emails.php?<?php echo $qstr; ?>" method="post" id="save">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="<?php echo $action; ?>">
 <input type="hidden" name="a" value="<?php echo Format::htmlchars($_REQUEST['a']); ?>">
 <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
 <table class="form_table" width="940" border="0" cellspacing="0" cellpadding="2">
    <thead>
        <tr>
            <th colspan="2">
                <h4><?php echo $title; ?></h4>
                <em><strong>Email информация</strong>: Логин и пароль не обязательны, но нужны когда используется IMAP/POP получение или SMTP отправка.</em>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td width="180" class="required">
                Email адрес
            </td>
            <td>
                <input type="text" size="35" name="email" value="<?php echo $info['email']; ?>">
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['email']; ?></span>
            </td>
        </tr>
        <tr>
            <td width="180" class="required">
                Email имя
            </td>
            <td>
                <input type="text" size="35" name="name" value="<?php echo $info['name']; ?>">
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['name']; ?>&nbsp;</span>
            </td>
        </tr>
        <tr>
            <td width="180">
                Логин
            </td>
            <td>
                <input type="text" size="35" name="userid" value="<?php echo $info['userid']; ?>">
                &nbsp;<span class="error">&nbsp;<?php echo $errors['userid']; ?>&nbsp;</span>
            </td>
        </tr>
        <tr>
            <td width="180">
                Пароль
            </td>
            <td>
                <input type="password" size="35" name="passwd" value="<?php echo $info['passwd']; ?>">
                &nbsp;<span class="error">&nbsp;<?php echo $errors['passwd']; ?>&nbsp;</span>
                <br><em><?php echo $passwdtxt; ?></em>
            </td>
        </tr>
        <tr>
            <th colspan="2">
                <em><strong>Наcтройки POP/IMAP</strong>: Настройки для получения входящей почты. &nbsp;<font class="error">&nbsp;<?php echo $errors['mail']; ?></font></em>
            </th>
        </tr>
        <tr><td>Статус</td>
            <td>
                <label><input type="radio" name="mail_active"  value="1"   <?php echo $info['mail_active']?'checked="checked"':''; ?> /><strong>Включено</strong></label>
                &nbsp;&nbsp;
                <label><input type="radio" name="mail_active"  value="0"   <?php echo !$info['mail_active']?'checked="checked"':''; ?> />Отключено</label>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['mail_active']; ?></font>
            </td>
        </tr>
        <tr><td>Сервер</td>
            <td><input type="text" name="mail_host" size=35 value="<?php echo $info['mail_host']; ?>">
                &nbsp;<font class="error">&nbsp;<?php echo $errors['mail_host']; ?></font>
            </td>
        </tr>
        <tr><td>Порт</td>
            <td><input type="text" name="mail_port" size=6 value="<?php echo $info['mail_port']?$info['mail_port']:''; ?>">
                &nbsp;<font class="error">&nbsp;<?php echo $errors['mail_port']; ?></font>
            </td>
        </tr>
        <tr><td>Протокол</td>
            <td>
                <select name="mail_protocol">
                    <option value='POP'>&mdash; выберите &mdash;</option>
                    <option value='POP' <?php echo ($info['mail_protocol']=='POP')?'selected="selected"':''; ?> >POP</option>
                    <option value='IMAP' <?php echo ($info['mail_protocol']=='IMAP')?'selected="selected"':''; ?> >IMAP</option>
                </select>
                <font class="error">&nbsp;<?php echo $errors['mail_protocol']; ?></font>
            </td>
        </tr>

        <tr><td>Шифрование</td>
            <td>
                <select name="mail_encryption">
                    <option value='NONE'>Нет</option>
                    <option value='SSL' <?php echo ($info['mail_encryption']=='SSL')?'selected="selected"':''; ?> >SSL</option>
                </select>
                <font class="error">&nbsp;<?php echo $errors['mail_encryption']; ?></font>
            </td>
        </tr>
        <tr><td>Периодичность</td>
            <td>
                <input type="text" name="mail_fetchfreq" size=4 value="<?php echo $info['mail_fetchfreq']?$info['mail_fetchfreq']:''; ?>"> Интервал проверки почты в минутах
                &nbsp;<font class="error">&nbsp;<?php echo $errors['mail_fetchfreq']; ?></font>
            </td>
        </tr>
        <tr><td>Количество</td>
            <td>
                <input type="text" name="mail_fetchmax" size=4 value="<?php echo $info['mail_fetchmax']?$info['mail_fetchmax']:''; ?>"> Максимум писем за процес получения.
                &nbsp;<font class="error">&nbsp;<?php echo $errors['mail_fetchmax']; ?></font>
            </td>
        </tr>
        <tr>
            <td width="180">
                Приоритет заявки:
            </td>
            <td>
                <select name="priority_id">
                    <option value="">&mdash; выберите &mdash;</option>
                    <?php
                    $sql='SELECT priority_id,priority_desc FROM '.PRIORITY_TABLE.' pri ORDER by priority_urgency DESC';
                    if(($res=db_query($sql)) && db_num_rows($res)){
                        while(list($id,$name)=db_fetch_row($res)){
                            $selected=($info['priority_id'] && $id==$info['priority_id'])?'selected="selected"':'';
                            echo sprintf('<option value="%d" %s>%s</option>',$id,$selected,$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<span class="error"><?php echo $errors['priority_id']; ?></span>
            </td>
        </tr>
        <tr>
            <td width="180">
                Отдел для заявки:
            </td>
            <td>
                <select name="dept_id">
                    <option value="">&mdash; Select Department &mdash;</option>
                    <?php
                    $sql='SELECT dept_id,dept_name FROM '.DEPT_TABLE.' dept ORDER by dept_name';
                    if(($res=db_query($sql)) && db_num_rows($res)){
                        while(list($id,$name)=db_fetch_row($res)){
                            $selected=($info['dept_id'] && $id==$info['dept_id'])?'selected="selected"':'';
                            echo sprintf('<option value="%d" %s>%s</option>',$id,$selected,$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<span class="error"><?php echo $errors['dept_id']; ?></span>
            </td>
        </tr>
        <tr>
            <td width="180">
                Автоответ
            </td>
            <td>
                <input type="checkbox" name="noautoresp" value="1" <?php echo $info['noautoresp']?'checked="checked"':''; ?> >
                <strong>Disable</strong> new ticket auto-response for this email. Overwrite global and dept. settings.
            </td>
        </tr>
        <tr><td valign="top">Полученные письма</td>
             <td>
                <input type="radio" name="postfetch" value="archive" <?php echo ($info['postfetch']=='archive')? 'checked="checked"': ''; ?> >
                 Перемещать в: <input type="text" name="mail_archivefolder" size="20" value="<?php echo $info['mail_archivefolder']; ?>"/> папку.
                    &nbsp;<font class="error">&nbsp;<?php echo $errors['mail_folder']; ?></font>
                <input type="radio" name="postfetch" value="delete" <?php echo ($info['postfetch']=='delete')? 'checked="checked"': ''; ?> >
                Удалить полученные письма
                <input type="radio" name="postfetch" value="" <?php echo (isset($info['postfetch']) && !$info['postfetch'])? 'checked="checked"': ''; ?> >
                 Не выполнять действий
              <br><em>Перемещение писем в резервную папку очень рекомендуется.</em> &nbsp;<font class="error"><?php echo $errors['postfetch']; ?></font>
            </td>
        </tr>

        <tr>
            <th colspan="2">
                <em><strong>Настройки SMTP</strong>: When enabled the <b>email account</b> will use SMTP server instead of internal PHP mail() function for outgoing emails. &nbsp;<font class="error">&nbsp;<?php echo $errors['smtp']; ?></font></em>
            </th>
        </tr>
        <tr><td>Статус</td>
            <td>
                <label><input type="radio" name="smtp_active"  value="1"   <?php echo $info['smtp_active']?'checked':''; ?> />Включено</label>
                <label><input type="radio" name="smtp_active"  value="0"   <?php echo !$info['smtp_active']?'checked':''; ?> />Отключено</label>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['smtp_active']; ?></font>
            </td>
        </tr>
        <tr><td>SMTP сервер</td>
            <td><input type="text" name="smtp_host" size=35 value="<?php echo $info['smtp_host']; ?>">
                &nbsp;<font class="error">&nbsp;<?php echo $errors['smtp_host']; ?></font>
            </td>
        </tr>
        <tr><td>SMTP порт</td>
            <td><input type="text" name="smtp_port" size=6 value="<?php echo $info['smtp_port']?$info['smtp_port']:''; ?>">
                &nbsp;<font class="error">&nbsp;<?php echo $errors['smtp_port']; ?></font>
            </td>
        </tr>
        <tr><td>Необходима авторизация?</td>
            <td>

                 <label><input type="radio" name="smtp_auth"  value="1"
                    <?php echo $info['smtp_auth']?'checked':''; ?> />Да</label>
                 <label><input type="radio" name="smtp_auth"  value="0"
                    <?php echo !$info['smtp_auth']?'checked':''; ?> />Нет</label>
                <font class="error">&nbsp;<?php echo $errors['smtp_auth']; ?></font>
            </td>
        </tr>
        <tr>
            <td>Allow Header Spoofing?</td>
            <td>
                <input type="checkbox" name="smtp_spoofing" value="1" <?php echo $info['smtp_spoofing'] ?'checked="checked"':''; ?>>
                Allow email header spoofing <em>(only applies to emails being sent through this account)</em>
            </td>
        </tr>
        <tr>
            <th colspan="2">
                <em><strong>Внутренние записи</strong>: Заметки администратора. &nbsp;<span class="error">&nbsp;<?php echo $errors['notes']; ?></span></em>
            </th>
        </tr>
        <tr>
            <td colspan=2>
                <textarea name="notes" cols="21" rows="5" style="width: 60%;"><?php echo $info['notes']; ?></textarea>
            </td>
        </tr>
    </tbody>
</table>
<p style="padding-left:225px;">
    <input type="submit" name="submit" value="Отправить">
    <input type="reset"  name="reset"  value="Очистить">
    <input type="button" name="cancel" value="Отмена" onclick='window.location.href="emails.php"'>
</p>
</form>
