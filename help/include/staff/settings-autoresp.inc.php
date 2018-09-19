<h2>Настройки автоответчиков</h2>
<form action="settings.php?t=autoresp" method="post" id="save">
<?php csrf_token(); ?>
<input type="hidden" name="t" value="autoresp" >
<table class="form_table settings_table" width="940" border="0" cellspacing="0" cellpadding="2">
    <thead>
        <tr>
            <th colspan="2">
                <h4>Настройки автоответчиков</h4>
                <em>Глобавльная настройка - может быть отключена на уровне отдела или email.</em>
            </th>
        </tr>
    </thead>
    <tbody>

        <tr>
            <td width="160">Новая заявка:</td>
            <td>
                <input type="radio" name="ticket_autoresponder"  value="1"   <?php echo $config['ticket_autoresponder']?'checked="checked"':''; ?> /><b>Включено</b>
                <input type="radio" name="ticket_autoresponder"  value="0"   <?php echo !$config['ticket_autoresponder']?'checked="checked"':''; ?> />Отключено
                &nbsp;&nbsp;&nbsp;
                <em>(Автоответ включает номер заявки для возможности проверки статуса)</em>
            </td>
        </tr>
        <tr>
            <td width="160">Новая заявка от менеджера:</td>
            <td>
                <input type="radio" name="ticket_notice_active"  value="1"   <?php echo $config['ticket_notice_active']?'checked="checked"':''; ?> /><b>Включено</b>
                <input type="radio" name="ticket_notice_active"  value="0"   <?php echo !$config['ticket_notice_active']?'checked="checked"':''; ?> />Отключено
                 &nbsp;&nbsp;&nbsp;
                 <em>(Уведомление направляется когда менеджер создал заявку на клиента)</em>
            </td>
        </tr>
        <tr>
            <td width="160">Новое сообщение:</td>
            <td>
                <input type="radio" name="message_autoresponder"  value="1"   <?php echo $config['message_autoresponder']?'checked="checked"':''; ?> /><b>Включено</b>
                <input type="radio" name="message_autoresponder"  value="0"   <?php echo !$config['message_autoresponder']?'checked="checked"':''; ?> />Отключено
                &nbsp;&nbsp;&nbsp;
                <em>(Confirmation notice sent when a new message is appended to an existing ticket)</em>
            </td>
        </tr>
        <tr>
            <td width="160">Уведомление о превышении лимита:</td>
            <td>
                <input type="radio" name="overlimit_notice_active"  value="1"   <?php echo $config['overlimit_notice_active']?'checked="checked"':''; ?> /><b>Включено</b>
                <input type="radio" name="overlimit_notice_active"  value="0"   <?php echo !$config['overlimit_notice_active']?'checked="checked"':''; ?> />Отключено
                &nbsp;&nbsp;&nbsp;
                <em>(Ticket denied notice sent to user on limit violation. Admin gets alerts on ALL denials by default)</em>
            </td>
        </tr>
    </tbody>
</table>
<p style="padding-left:200px;">
    <input class="button" type="submit" name="submit" value="Сохранить">
    <input class="button" type="reset" name="reset" value="Очистить">
</p>
</form>
