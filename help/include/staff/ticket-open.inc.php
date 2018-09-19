<?php
if(!defined('OSTSCPINC') || !$thisstaff || !$thisstaff->canCreateTickets()) die('Access Denied');
$info=array();
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>
<form action="tickets.php?a=open" method="post" id="save"  enctype="multipart/form-data">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="create">
 <input type="hidden" name="a" value="open">
 <h2>Открытие новой заявки</h2>
 <table class="form_table" width="940" border="0" cellspacing="0" cellpadding="2">
    <thead>
        <tr>
            <th colspan="2">
                <h4>Новая заявка</h4>
                <em><strong>Информация о пользователе</strong></em>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td width="160" class="required">
                Email адрес:
            </td>
            <td>

                <input type="text" size="50" name="email" id="email" class="typeahead" value="<?php echo $info['email']; ?>"
                    autocomplete="off" autocorrect="off" autocapitalize="off">
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['email']; ?></span>
            <?php 
            if($cfg->notifyONNewStaffTicket()) { ?>
               &nbsp;&nbsp;&nbsp;
               <input type="checkbox" name="alertuser" <?php echo (!$errors || $info['alertuser'])? 'checked="checked"': ''; ?>>Отправить оповещение пользователю.
            <?php 
             } ?>
            </td>
        </tr>
        <tr>
            <td width="160" class="required">
                ФИО:
            </td>
            <td>
                <input type="text" size="50" name="name" id="name" value="<?php echo $info['name']; ?>">
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['name']; ?></span>
            </td>
        </tr>
        <tr>
            <td width="160">
                Номер телефона:
            </td>
            <td>
                <input type="text" size="20" name="phone" id="phone" value="<?php echo $info['phone']; ?>">
                &nbsp;<span class="error">&nbsp;<?php echo $errors['phone']; ?></span>
                Доб. <input type="text" size="6" name="phone_ext" id="phone_ext" value="<?php echo $info['phone_ext']; ?>">
                &nbsp;<span class="error">&nbsp;<?php echo $errors['phone_ext']; ?></span>
            </td>
        </tr>
        <tr>
            <th colspan="2">
                <em><strong>Информация о заявке</strong>:</em>
            </th>
        </tr>
        <tr>
            <td width="160" class="required">
                Источник заявки:
            </td>
            <td>
                <select name="source">
                    <option value="" selected >&mdash; выберите источник&mdash;</option>
                    <option value="Phone" <?php echo ($info['source']=='Phone')?'selected="selected"':''; ?>>Телефон</option>
                    <option value="Email" <?php echo ($info['source']=='Email')?'selected="selected"':''; ?>>Email</option>
                    <option value="Other" <?php echo ($info['source']=='Other')?'selected="selected"':''; ?>>Другое</option>
                </select>
                &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['source']; ?></font>
            </td>
        </tr>
        <tr>
            <td width="160" class="required">
                Отдел:
            </td>
            <td>
                <select name="deptId">
                    <option value="" selected >&mdash; выберите отдел &mdash;</option>
                    <?php
                    if($depts=Dept::getDepartments()) {
                        foreach($depts as $id =>$name) {
                            echo sprintf('<option value="%d" %s>%s</option>',
                                    $id, ($info['deptId']==$id)?'selected="selected"':'',$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['deptId']; ?></font>
            </td>
        </tr>

        <tr>
            <td width="160" class="required">
                Тема обращения:
            </td>
            <td>
                <select name="topicId">
                    <option value="" selected >&mdash; выберите тему &mdash;</option>
                    <?php
                    if($topics=Topic::getHelpTopics()) {
                        foreach($topics as $id =>$name) {
                            echo sprintf('<option value="%d" %s>%s</option>',
                                    $id, ($info['topicId']==$id)?'selected="selected"':'',$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['topicId']; ?></font>
            </td>
        </tr>
        <tr>
            <td width="160">
                Приоритет:
            </td>
            <td>
                <select name="priorityId">
                    <option value="0" selected >&mdash; по умолчанию &mdash;</option>
                    <?php
                    if($priorities=Priority::getPriorities()) {
                        foreach($priorities as $id =>$name) {
				if ($name=="Low") $name="Низкий"; if ($name=="Normal") $name="Средний"; if ($name=="High") $name="Высокий"; if ($name=="Emergency") $name="Критичный"; // Добавка от osTicket.Ru
                            echo sprintf('<option value="%d" %s>%s</option>', $id, ($info['priorityId']==$id)?'selected="selected"':'',$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['priorityId']; ?></font>
            </td>
         </tr>
         <tr>
            <td width="160">
                SLA план:
            </td>
            <td>
                <select name="slaId">
                    <option value="0" selected="selected" >&mdash; по умолчанию &mdash;</option>
                    <?php
                    if($slas=SLA::getSLAs()) {
                        foreach($slas as $id =>$name) {
                            echo sprintf('<option value="%d" %s>%s</option>',
                                    $id, ($info['slaId']==$id)?'selected="selected"':'',$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['slaId']; ?></font>
            </td>
         </tr>

         <tr>
            <td width="160">
                Проср. дата:
            </td>
            <td>
                <input class="dp" id="duedate" name="duedate" value="<?php echo Format::htmlchars($info['duedate']); ?>" size="12" autocomplete=OFF>
                &nbsp;&nbsp;
                <?php
                $min=$hr=null;
                if($info['time'])
                    list($hr, $min)=explode(':', $info['time']);

                echo Misc::timeDropdown($hr, $min, 'time');
                ?>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['duedate']; ?> &nbsp; <?php echo $errors['time']; ?></font>
                <em>Время соответсвует вашему часовому поясу (GMT <?php echo $thisstaff->getTZoffset(); ?>)</em>
            </td>
        </tr>

        <?php
        if($thisstaff->canAssignTickets()) { ?>
        <tr>
            <td width="160">Ответственный:</td>
            <td>
                <select id="assignId" name="assignId">
                    <option value="0" selected="selected">&mdash; выберите пользователя или команду &mdash;</option>
                    <?php
                    if(($users=Staff::getAvailableStaffMembers())) {
                        echo '<OPTGROUP label="Пользователи ('.count($users).')">';
                        foreach($users as $id => $name) {
                            $k="s$id";
                            echo sprintf('<option value="%s" %s>%s</option>',
                                        $k,(($info['assignId']==$k)?'selected="selected"':''),$name);
                        }
                        echo '</OPTGROUP>';
                    }

                    if(($teams=Team::getActiveTeams())) {
                        echo '<OPTGROUP label="Команды ('.count($teams).')">';
                        foreach($teams as $id => $name) {
                            $k="t$id";
                            echo sprintf('<option value="%s" %s>%s</option>',
                                        $k,(($info['assignId']==$k)?'selected="selected"':''),$name);
                        }
                        echo '</OPTGROUP>';
                    }
                    ?>
                </select>&nbsp;<span class='error'>&nbsp;<?php echo $errors['assignId']; ?></span>
            </td>
        </tr>
        <?php
        } ?>
        <tr>
            <th colspan="2">
                <em><strong>Сообщение</strong></em>
            </th>
        </tr>
        <tr>
            <td colspan=2>
                <div>
                    <em><strong>Тема</strong></em> &nbsp;<font class="error">*&nbsp;<?php echo $errors['subject']; ?></font><br>
                    <input type="text" name="subject" size="60" value="<?php echo $info['subject']; ?>">
                </div>
                <div><em><strong>Сообщение</strong></em> <font class="error">*&nbsp;<?php echo $errors['issue']; ?></font></div>
                <textarea name="issue" cols="21" rows="8" style="width:80%;"><?php echo $info['issue']; ?></textarea>
            </td>
        </tr>
        <?php
        //is the user allowed to post replies??
        if($thisstaff->canPostReply()) {
            ?>
        <tr>
            <th colspan="2">
                <em><strong>Ответ</strong>: Заполняется в случае одновременного ответа на заявку.</em>
            </th>
        </tr>
        <tr>
            <td colspan=2>
            <?php
            if(($cannedResponses=Canned::getCannedResponses())) {
                ?>
                <div>
                    готовый ответ:&nbsp;
                    <select id="cannedResp" name="cannedResp">
                        <option value="0" selected="selected">&mdash; выберите готовый ответ &mdash;</option>
                        <?php
                        foreach($cannedResponses as $id =>$title) {
                            echo sprintf('<option value="%d">%s</option>',$id,$title);
                        }
                        ?>
                    </select>
                    &nbsp;&nbsp;&nbsp;
                    <label><input type='checkbox' value='1' name="append" id="append" checked="checked">Добавить к тексту</label>
                </div>
            <?php
            } ?>
                <textarea name="response" id="response" cols="21" rows="8" style="width:80%;"><?php echo $info['response']; ?></textarea>
                <table border="0" cellspacing="0" cellpadding="2" width="100%">
                <?php
                if($cfg->allowAttachments()) { ?>
                    <tr><td width="100" valign="top">Вложения:</td>
                        <td>
                            <div class="canned_attachments">
                            <?php
                            if($info['cannedattachments']) {
                                foreach($info['cannedattachments'] as $k=>$id) {
                                    if(!($file=AttachmentFile::lookup($id))) continue;
                                    $hash=$file->getHash().md5($file->getId().session_id().$file->getHash());
                                    echo sprintf('<label><input type="checkbox" name="cannedattachments[]" 
                                            id="f%d" value="%d" checked="checked" 
                                            <a href="file.php?h=%s">%s</a>&nbsp;&nbsp;</label>&nbsp;',
                                            $file->getId(), $file->getId() , $hash, $file->getName());
                                }
                            }
                            ?>
                            </div>
                            <div class="uploads"></div>
                            <div class="file_input">
                                <input type="file" class="multifile" name="attachments[]" size="30" value="" />
                            </div>
                        </td>
                    </tr>
                <?php
                } ?>

            <?php
            if($thisstaff->canCloseTickets()) { ?>
                <tr>
                    <td width="100">Статус заявки:</td>
                    <td>
                        <input type="checkbox" name="ticket_state" value="closed" <?php echo $info['ticket_state']?'checked="checked"':''; ?>>
                        <b>Закрыть при ответе</b>&nbsp;<em>(только если заполнено поле ответа)</em>
                    </td>
                </tr>
            <?php
            } ?>
             <tr>
                <td width="100">Подпись:</td>
                <td>
                    <?php
                    $info['signature']=$info['signature']?$info['signature']:$thisstaff->getDefaultSignatureType();
                    ?>
                    <label><input type="radio" name="signature" value="none" checked="checked"> нет</label>
                    <?php
                    if($thisstaff->getSignature()) { ?>
                        <label><input type="radio" name="signature" value="mine"
                            <?php echo ($info['signature']=='mine')?'checked="checked"':''; ?>> моя подпись</label>
                    <?php
                    } ?>
                    <label><input type="radio" name="signature" value="dept"
                        <?php echo ($info['signature']=='dept')?'checked="checked"':''; ?>> подпись отдела</label>
                </td>
             </tr>
            </table>
            </td>
        </tr>
        <?php
        } //end canPostReply
        ?>
        <tr>
            <th colspan="2">
                <em><strong>Внутреннее сообщение</strong>: Не обязательно для заполнения <font class="error">&nbsp;<?php echo $errors['note']; ?></font></em>
            </th>
        </tr>
        <tr>
            <td colspan=2>
                <textarea name="note" cols="21" rows="6" style="width:80%;"><?php echo $info['note']; ?></textarea>
            </td>
        </tr>
    </tbody>
</table>
<p style="padding-left:250px;">
    <input type="submit" name="submit" value="Создать">
    <input type="reset"  name="reset"  value="Очистить">
    <input type="button" name="cancel" value="Отмена" onclick='window.location.href="tickets.php"'>
</p>
</form>
