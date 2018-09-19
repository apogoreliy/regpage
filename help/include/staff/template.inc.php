<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');

$info=array();
$qstr='';
if($template && $_REQUEST['a']!='add'){
    $title='Изменение шаблона';
    $action='update';
    $submit_text='Сохранить';
    $info=$template->getInfo();
    $info['id']=$template->getId();
    $qstr.='&id='.$template->getId();
}else {
    $title='Добавить новый шаблон';
    $action='add';
    $submit_text='Добавить';
    $info['isactive']=isset($info['isactive'])?$info['isactive']:0;
    $qstr.='&a='.urlencode($_REQUEST['a']);
}
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>
<form action="templates.php?<?php echo $qstr; ?>" method="post" id="save">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="<?php echo $action; ?>">
 <input type="hidden" name="a" value="<?php echo Format::htmlchars($_REQUEST['a']); ?>">
 <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
 <h2>Email шаблоны</h2>
 <table class="form_table" width="940" border="0" cellspacing="0" cellpadding="2">
    <thead>
        <tr>
            <th colspan="2">
                <h4><?php echo $title; ?></h4>
                <em>Информация о шаблоне</em>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td width="180" class="required">
              Название:
            </td>
            <td>
                <input type="text" size="30" name="name" value="<?php echo $info['name']; ?>">
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['name']; ?></span>
            </td>
        </tr>
        <tr>
            <td width="180" class="required">
                Статус:
            </td>
            <td>
                <input type="radio" name="isactive" value="1" <?php echo $info['isactive']?'checked="checked"':''; ?>><strong>Включено</strong>
                <input type="radio" name="isactive" value="0" <?php echo !$info['isactive']?'checked="checked"':''; ?>>Отключено
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['isactive']; ?></span>
            </td>
        </tr>
        <?php
        if($template){ ?>
        <tr>
            <th colspan="2">
                <em><strong>Содержимое шаблона</strong>: Нажмите для редактирования&nbsp;
                    <span class="error">*&nbsp;<?php echo $errors['rules']; ?></span></em>
            </th>
        </tr>
        <?php
         foreach(Template::message_templates() as $k=>$tpl){
            echo sprintf('<tr><td colspan=2>&nbsp;<strong><a href="templates.php?id=%d&a=manage&tpl=%s">%s</a></strong>&nbsp-&nbsp<em>%s</em></td></tr>',
                    $template->getId(),$k,Format::htmlchars($tpl['name']),Format::htmlchars($tpl['desc']));
         }
        }else{ ?>
        <tr>
            <td width="180" class="required">
                Клонирование:
            </td>
            <td>
                <select name="tpl_id">
                    <option value="0">&mdash; выберите &mdash;</option>
                    <?php
                    $sql='SELECT tpl_id,name FROM '.EMAIL_TEMPLATE_TABLE.' ORDER by name';
                    if(($res=db_query($sql)) && db_num_rows($res)){
                        while(list($id,$name)=db_fetch_row($res)){
                            $selected=($info['tpl_id'] && $id==$info['tpl_id'])?'selected="selected"':'';
                            echo sprintf('<option value="%d" %s>%s</option>',$id,$selected,$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['tpl_id']; ?></span>
                 <em>(выберите для создания шаблона на основе существующего)</em>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <th colspan="2">
                <em><strong>Примечание</strong>: Внутренняя запись.&nbsp;</em>
            </th>
        </tr>
        <tr>
            <td colspan=2>
                <textarea name="notes" cols="21" rows="8" style="width: 80%;"><?php echo $info['notes']; ?></textarea>
            </td>
        </tr>
    </tbody>
</table>
<p style="padding-left:225px;">
	<input type="hidden" name="lang_id" value="en"><!-- Смена языков не реализована, сделаем постоянный / osticket.ru -->
    <input type="submit" name="submit" value="<?php echo $submit_text; ?>">
    <input type="reset"  name="reset"  value="Очистить">
    <input type="button" name="cancel" value="Отмена" onclick='window.location.href="templates.php"'>
</p>
</form>
