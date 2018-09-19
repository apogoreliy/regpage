<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin() || !$config) die('Access Denied');
if(!($maxfileuploads=ini_get('max_file_uploads')))
    $maxfileuploads=DEFAULT_MAX_FILE_UPLOADS;
?>
<h2>Настройки и параметры заявок</h2>
<form action="settings.php?t=tickets" method="post" id="save">
<?php csrf_token(); ?>
<input type="hidden" name="t" value="tickets" >
<table class="form_table settings_table" width="940" border="0" cellspacing="0" cellpadding="2">
    <thead>
        <tr>
            <th colspan="2">
                <h4>Настройки заявок</h4>
                <em>Глобальные настройки</em>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr><td width="220" class="required">Нумерация заявок:</td>
            <td>
                <input type="radio" name="random_ticket_ids"  value="0" <?php echo !$config['random_ticket_ids']?'checked="checked"':''; ?> />
                Последовательная (1,2,3,...)
                <input type="radio" name="random_ticket_ids"  value="1" <?php echo $config['random_ticket_ids']?'checked="checked"':''; ?> />
                Произвольная  <em>(рекомендуется)</em>
            </td>
        </tr>

        <tr>
            <td width="180" class="required">
                SLA по умолчанию:
            </td>
            <td>
                <select name="default_sla_id">
                    <option value="0">&mdash; нет &mdash;</option>
                    <?php
                    if($slas=SLA::getSLAs()) {
                        foreach($slas as $id => $name) {
                            echo sprintf('<option value="%d" %s>%s</option>',
                                    $id,
                                    ($config['default_sla_id'] && $id==$config['default_sla_id'])?'selected="selected"':'',
                                    $name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['default_sla_id']; ?></span>
            </td>
        </tr>
        <tr>
            <td width="180" class="required">Приоритет по умолчанию:</td>
            <td>
                <select name="default_priority_id">
                    <?php
                    $priorities= db_query('SELECT priority_id,priority_desc FROM '.TICKET_PRIORITY_TABLE);
                    while (list($id,$tag) = db_fetch_row($priorities)){ ?>
                        <option value="<?php echo $id; ?>"<?php echo ($config['default_priority_id']==$id)?'selected':''; ?>><?php echo $tag; ?></option>
                    <?php
                    } ?>
                </select>
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['default_priority_id']; ?></span>
             </td>
        </tr>
        <tr>
            <td>Максимум <b>открытых</b> заявок:</td>
            <td>
                <input type="text" name="max_open_tickets" size=4 value="<?php echo $config['max_open_tickets']; ?>">
                email/пользователь. <em>(Помогает со спамом - введите 0 для снятия ограничений)</em>
            </td>
        </tr>
        <tr>
            <td>Автоблокировка заявок:</td>
            <td>
                <input type="text" name="autolock_minutes" size=4 value="<?php echo $config['autolock_minutes']; ?>">
                <font class="error"><?php echo $errors['autolock_minutes']; ?></font>
                <em>минут(ы). (Введите 0 для отключения блокировки)</em>
            </td>
        </tr>
        <tr>
                    <td width="180">Приоритет Веб заявок:</td>
                    <td>
                        <input type="checkbox" name="allow_priority_change" value="1" <?php echo $config['allow_priority_change'] ?'checked="checked"':''; ?>>
                        <em>(Разрешает пользователям назначать приоритет)</em>
                    </td>
                </tr>
                <tr>
                    <td width="180">Приоритет Email заявок:</td>
                    <td>
                        <input type="checkbox" name="use_email_priority" value="1" <?php echo $config['use_email_priority'] ?'checked="checked"':''; ?> >
                        <em>(Используется email приоритет когда доступно)</em>
            </td>
        </tr>
        <tr>
            <td width="180">Show Related Tickets:</td>
            <td>
                <input type="checkbox" name="show_related_tickets" value="1" <?php echo $config['show_related_tickets'] ?'checked="checked"':''; ?> >
                <em>(Show all related tickets on user login - otherwise access is restricted to one ticket view per login)</em>
            </td>
        </tr>
        <tr>
            <td width="180">Отображение внутренних записей:</td>
            <td>
                <input type="checkbox" name="show_notes_inline" value="1" <?php echo $config['show_notes_inline'] ?'checked="checked"':''; ?> >
                <em>(Показывать внутренние записи вместе с ответами)</em>
              </td>
        </tr>
        <tr><td>Определение ссылок:</td>
            <td>
              <input type="checkbox" name="clickable_urls" <?php echo $config['clickable_urls']?'checked="checked"':''; ?>>
               <em>(конвентировать в заявках URLы в ссылки)</em>
            </td>
        </tr>
        <tr>
            <td>Антиспам:</td>
            <td>
                <input type="checkbox" name="enable_captcha" <?php echo $config['enable_captcha']?'checked="checked"':''; ?>>
                Включить CAPTCHA для новых web заявок.<em>(необходима GDLib)</em> &nbsp;<font class="error">&nbsp;<?php echo $errors['enable_captcha']; ?></font><br/>
            </td>
        </tr>
        <tr>
            <td>Reopened Tickets:</td>
            <td>
                <input type="checkbox" name="auto_assign_reopened_tickets" <?php echo $config['auto_assign_reopened_tickets']?'checked="checked"':''; ?>>
                Auto-assign reopened tickets to the last available respondent.
            </td>
        </tr>
        <tr>
            <td>Assigned Tickets:</td>
            <td>
                <input type="checkbox" name="show_assigned_tickets" <?php echo $config['show_assigned_tickets']?'checked="checked"':''; ?>>
                Show assigned tickets on open queue.
            </td>
        </tr>
        <tr>
            <td>Answered Tickets:</td>
            <td>
                <input type="checkbox" name="show_answered_tickets" <?php echo $config['show_answered_tickets']?'checked="checked"':''; ?>>
                Show answered tickets on open queue.
            </td>
        </tr>
        <tr>
            <td>Ticket Activity Log:</td>
            <td>
                <input type="checkbox" name="log_ticket_activity" <?php echo $config['log_ticket_activity']?'checked="checked"':''; ?>>
                Log ticket activity as internal notes.
            </td>
        </tr>
        <tr>
            <td>Staff Identity Masking:</td>
            <td>
                <input type="checkbox" name="hide_staff_name" <?php echo $config['hide_staff_name']?'checked="checked"':''; ?>>
                Hide staff's name on responses.
            </td>
        </tr>
        <tr>
            <th colspan="2">
                <em><b>Вложения</b></em>
            </th>
        </tr>
        <tr>
            <td width="180">Вложения:</td>
            <td>
              <input type="checkbox" name="allow_attachments" <?php echo $config['allow_attachments']?'checked="checked"':''; ?>><b> Разрешить вложения</b>
                &nbsp; <em>(глобальная настройка)</em>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['allow_attachments']; ?></font>
            </td>
        </tr>
        <tr>
            <td width="180">Email/API вложения:</td>
            <td>
                <input type="checkbox" name="allow_email_attachments" <?php echo $config['allow_email_attachments']?'checked="checked"':''; ?>> Принимать email/API вложения.
                    &nbsp;<font class="error">&nbsp;<?php echo $errors['allow_email_attachments']; ?></font>
            </td>
        </tr>
        <tr>
            <td width="180">Web вложения:</td>
            <td>
                <input type="checkbox" name="allow_online_attachments" <?php echo $config['allow_online_attachments']?'checked="checked"':''; ?> >
                    Разрешить web загрузку &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="allow_online_attachments_onlogin" <?php echo $config['allow_online_attachments_onlogin'] ?'checked="checked"':''; ?> >
                    Только для авторизованных пользователей.
                    <font class="error">&nbsp;<?php echo $errors['allow_online_attachments']; ?></font>
            </td>
        </tr>
        <tr>
            <td>Кол-во файлов для пользоват.:</td>
            <td>
                <select name="max_user_file_uploads">
                    <?php
                    for($i = 1; $i <=$maxfileuploads; $i++) {
                        ?>
                        <option <?php echo $config['max_user_file_uploads']==$i?'selected="selected"':''; ?> value="<?php echo $i; ?>">
                            <?php echo $i; ?>&nbsp;<?php echo ($i>1)?'файлов':'файл'; ?></option>
                        <?php
                    } ?>
                </select>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['max_user_file_uploads']; ?></font>
            </td>
        </tr>
        <tr>
            <td>Кол-во файлов для менеджеров:</td>
            <td>
                <select name="max_staff_file_uploads">
                    <?php
                    for($i = 1; $i <=$maxfileuploads; $i++) {
                        ?>
                        <option <?php echo $config['max_staff_file_uploads']==$i?'selected="selected"':''; ?> value="<?php echo $i; ?>">
                            <?php echo $i; ?>&nbsp;<?php echo ($i>1)?'файлов':'файл'; ?></option>
                        <?php
                    } ?>
                </select>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['max_staff_file_uploads']; ?></font>
            </td>
        </tr>
        <tr>
            <td width="180">Размер файла:</td>
            <td>
                <input type="text" name="max_file_size" value="<?php echo $config['max_file_size']; ?>"> в битах.
                    <em>(Максимальное значение указанное в системе <?php echo Format::file_size(ini_get('upload_max_filesize')); ?>)</em>
                    <font class="error">&nbsp;<?php echo $errors['max_file_size']; ?></font>
            </td>
        </tr>
        <tr>
            <td width="180">Ticket Response Files:</td>
            <td>
                <input type="checkbox" name="email_attachments" <?php echo $config['email_attachments']?'checked="checked"':''; ?> >Email attachments to the user
            </td>
        </tr>
        <tr>
            <th colspan="2">
                <em><strong>разрешенные типы файлов</strong>
                <font class="error">&nbsp;<?php echo $errors['allowed_filetypes']; ?></font></em>
            </th>
        </tr>
        <tr>
            <td colspan="2">
                <em>Введите типы файлов которые будут разрешены для загрузки. Например: .doc, .pdf.<br />Для разрешения всех типов файлов введите <b><i>.*</i></b>&nbsp; (НЕ рекомендуется).</em><br>
                <textarea name="allowed_filetypes" cols="21" rows="4" style="width: 65%;" wrap="hard" ><?php echo $config['allowed_filetypes']; ?></textarea>
            </td>
        </tr>
    </tbody>
</table>
<p style="padding-left:250px;">
    <input class="button" type="submit" name="submit" value="Сохранить">
    <input class="button" type="reset" name="reset" value="Очистить">
</p>
</form>

