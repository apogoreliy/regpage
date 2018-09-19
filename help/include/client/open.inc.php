<?php
if(!defined('OSTCLIENTINC')) die('Access Denied!');
$info=array();
if($thisclient && $thisclient->isValid()) {
    $info=array('name'=>$thisclient->getName(),
                'email'=>$thisclient->getEmail(),
                'phone'=>$thisclient->getPhone(),
                'phone_ext'=>$thisclient->getPhoneExt());
}

$info=($_POST && $errors)?Format::htmlchars($_POST):$info;
?>
<h1>Открыть новую заявку</h1>
<p>Пожалуйста заполните следующие поля для открытия новой заявки.</p>
<form id="ticketForm" method="post" action="open.php" enctype="multipart/form-data">
  <?php csrf_token(); ?>
  <input type="hidden" name="a" value="open">
  <table width="800" cellpadding="1" cellspacing="0" border="0">
    <tr>
        <th class="required" width="160">Имя:</th>
        <td>
            <?php
            if($thisclient && $thisclient->isValid()) {
                echo $thisclient->getName();
            } else { ?>
                <input id="name" type="text" name="name" size="30" value="<?php echo $info['name']; ?>">
                <font class="error">*&nbsp;<?php echo $errors['name']; ?></font>
            <?php
            } ?>
        </td>
    </tr>
    <tr>
        <th class="required" width="160">Email адрес:</th>
        <td>
            <?php
            if($thisclient && $thisclient->isValid()) { 
                echo $thisclient->getEmail();
            } else { ?>
                <input id="email" type="text" name="email" size="30" value="<?php echo $info['email']; ?>">
                <font class="error">*&nbsp;<?php echo $errors['email']; ?></font>
            <?php
            } ?>
        </td>
    </tr>
    <tr>
        <th>Номер телефона:</th>
        <td>

            <input id="phone" type="text" name="phone" size="17" value="<?php echo $info['phone']; ?>">
            <label for="ext" class="inline">Доп.:</label>
            <input id="ext" type="text" name="phone_ext" size="3" value="<?php echo $info['phone_ext']; ?>">
            <font class="error">&nbsp;<?php echo $errors['phone']; ?>&nbsp;&nbsp;<?php echo $errors['phone_ext']; ?></font>
        </td>   
    </tr>
    <tr>
        <td class="required">Тема обращения:</td>
        <td>
            <select id="topicId" name="topicId">
                <option value="" selected="selected">&mdash; Выберите тему обращения &mdash;</option>
                <?php
                if($topics=Topic::getPublicHelpTopics()) {
                    foreach($topics as $id =>$name) {
                        echo sprintf('<option value="%d" %s>%s</option>',
                                $id, ($info['topicId']==$id)?'selected="selected"':'', $name);
                    }
                } else { ?>
                    <option value="0">Основная</option>
                <?php
                } ?>
            </select>
            <font class="error">*&nbsp;<?php echo $errors['topicId']; ?></font>
        </td>
    </tr>
    <tr>
        <td class="required">Заголовок:</td>
        <td>
            <input id="subject" type="text" name="subject" size="40" value="<?php echo $info['subject']; ?>">
            <font class="error">*&nbsp;<?php echo $errors['subject']; ?></font>
        </td>
    </tr>
    <tr>
        <td class="required">Сообщение:</td>
        <td>
            <textarea id="message" cols="60" rows="8" name="message"><?php echo $info['message']; ?></textarea> <font class="error">*&nbsp;<?php echo $errors['message']; ?></font>
			<div><em>Пожалуйста опишите вашу проблему как можно более подробно...</em></div>
        </td>
    </tr>

    <?php if(($cfg->allowOnlineAttachments() && !$cfg->allowAttachmentsOnlogin())
            || ($cfg->allowAttachmentsOnlogin() && ($thisclient && $thisclient->isValid()))) { ?>
    <tr>
        <td>Вложения:</td>
        <td>
            <input type="file" class="multifile" name="attachments[]" id="attachments" size="30" value="" />
			<div class="uploads"></div><br>
            <font class="error">&nbsp;<?php echo $errors['attachments']; ?></font>
        </td>
    </tr>
    <?php } ?>
    <?php
    if($cfg->allowPriorityChange() && ($priorities=Priority::getPriorities())) { ?>
    <tr>
        <td>Приоритет:</td>
        <td>
            <select id="priority" name="priorityId">
                <?php
                    if(!$info['priorityId'])
                        $info['priorityId'] = $cfg->getDefaultPriorityId(); //System default.
                    foreach($priorities as $id =>$name) {
                        echo sprintf('<option value="%d" %s>%s</option>',
                                        $id, ($info['priorityId']==$id)?'selected="selected"':'', $name);
                        
                    }
                ?>
            </select>
            <font class="error">&nbsp;<?php echo $errors['priorityId']; ?></font>
        </td>
    </tr>
    <?php
    }
    ?>
    <?php
    if($cfg && $cfg->isCaptchaEnabled() && (!$thisclient || !$thisclient->isValid())) {
        if($_POST && $errors && !$errors['captcha'])
            $errors['captcha']='Введите текст еще раз';
        ?>
    <tr class="captchaRow">
        <td class="required">Защита от спама:</td>
        <td>
            <span class="captcha"><img src="captcha.php" border="0" align="left"></span>
            <input id="captcha" type="text" name="captcha" size="6">
            <em>Введите текст представленный на картинке.</em>
            <font class="error">*&nbsp;<?php echo $errors['captcha']; ?></font>
        </td>
    </tr>
    <?php
    } ?>
    <tr><td colspan=2>Поля отмеченные <font class="error">*</font> обязательны для заполнения.</td></tr>
  </table>
  <p style="padding-left:150px;">
        <input type="submit" value="Отправить">
        <input type="reset" value="Очистить">
        <input type="button" value="Отмена" onClick='window.location.href="index.php"'>
  </p>
</form>