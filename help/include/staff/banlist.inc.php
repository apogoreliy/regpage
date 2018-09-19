<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin() || !$filter) die('Access Denied');

$qstr='';
$select='SELECT rule.* ';
$from='FROM '.FILTER_RULE_TABLE.' rule ';
$where='WHERE rule.filter_id='.db_input($filter->getId());
$search=false;
if($_REQUEST['q'] && strlen($_REQUEST['q'])>3) {
    $search=true;
    if(strpos($_REQUEST['q'],'@') && Validator::is_email($_REQUEST['q']))
        $where.=' AND rule.val='.db_input($_REQUEST['q']);
    else
        $where.=' AND rule.val LIKE "%'.db_input($_REQUEST['q'],false).'%"';

}elseif($_REQUEST['q']) {
    $errors['q']='Term too short!';
}

$sortOptions=array('email'=>'rule.val','status'=>'isactive','created'=>'rule.created','created'=>'rule.updated');
$orderWays=array('DESC'=>'DESC','ASC'=>'ASC');
$sort=($_REQUEST['sort'] && $sortOptions[strtolower($_REQUEST['sort'])])?strtolower($_REQUEST['sort']):'email';
//Sorting options...
if($sort && $sortOptions[$sort]) {
    $order_column =$sortOptions[$sort];
}
$order_column=$order_column?$order_column:'rule.val';

if($_REQUEST['order'] && $orderWays[strtoupper($_REQUEST['order'])]) {
    $order=$orderWays[strtoupper($_REQUEST['order'])];
}

$order=$order?$order:'ASC';
if($order_column && strpos($order_column,',')){
    $order_column=str_replace(','," $order,",$order_column);
}
$x=$sort.'_sort';
$$x=' class="'.strtolower($order).'" ';
$order_by="$order_column $order ";

$total=db_count('SELECT count(DISTINCT rule.id) '.$from.' '.$where);
$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;
$pageNav=new Pagenate($total, $page, PAGE_LIMIT);
$pageNav->setURL('banlist.php',$qstr.'&sort='.urlencode($_REQUEST['sort']).'&order='.urlencode($_REQUEST['order']));
$qstr.='&order='.($order=='DESC'?'ASC':'DESC');
$query="$select $from $where ORDER BY $order_by LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
//echo $query;
?>
<h2>Заблокированные Email адреса</h2>
<div style="width:600; float:left;padding-top:5px;">
    <form action="banlist.php" method="GET" name="filter">
     <input type="hidden" name="a" value="filter" >
     <div>
       Поиск: <input name="q" type="text" size="20" value="<?php echo Format::htmlchars($_REQUEST['q']); ?>">
        &nbsp;&nbsp;
        <input type="submit" name="submit" value="Найти"/>
     </div>
    </form>
 </div>
<div style="float:right;text-align:right;padding-right:5px;"><b><a href="banlist.php?a=add" class="Icon newstaff">Заблокировать новый Email</a></b></div>
<div class="clear"></div>
<?php
if(($res=db_query($query)) && ($num=db_num_rows($res)))
    $showing=$pageNav->showing();
else
    $showing='No banned emails matching the query found!';

if($search)
    $showing='Search Results: '.$showing;
    
?>
<form action="banlist.php" method="POST" name="banlist">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="mass_process" >
<input type="hidden" id="action" name="a" value="" >
 <table class="list" border="0" cellspacing="1" cellpadding="0" width="940">
    <caption><?php echo $showing; ?></caption>
    <thead>
        <tr>
            <th width="7px">&nbsp;</th>        
            <th width="350"><a <?php echo $email_sort; ?> href="staff.php?<?php echo $qstr; ?>&sort=email">Email адрес</a></th>
            <th width="140"><a  <?php echo $status_sort; ?> href="staff.php?<?php echo $qstr; ?>&sort=status">Статус блокировки</a></th>
            <th width="120"><a <?php echo $created_sort; ?> href="staff.php?<?php echo $qstr; ?>&sort=created">Дата добавления</a></th>
            <th width="180"><a <?php echo $updated_sort; ?> href="staff.php?<?php echo $qstr; ?>&sort=updated">Последнее обновление</a></th>
        </tr>
    </thead>
    <tbody>
    <?php
        if($res && db_num_rows($res)):
            $ids=($errors && is_array($_POST['ids']))?$_POST['ids']:null;
            while ($row = db_fetch_array($res)) {
                $sel=false;
                if($ids && in_array($row['id'],$ids))
                    $sel=true;
                ?>
               <tr id="<?php echo $row['id']; ?>">
                <td width=7px>
                  <input type="checkbox" class="ckb" name="ids[]" value="<?php echo $row['id']; ?>" <?php echo $sel?'checked="checked"':''; ?>>
                </td>
                <td>&nbsp;<a href="banlist.php?id=<?php echo $row['id']; ?>"><?php echo Format::htmlchars($row['val']); ?></a></td>
                <td>&nbsp;&nbsp;<?php echo $row['isactive']?'Активно':'<b>Отключено</b>'; ?></td>
                <td><?php echo Format::db_date($row['created']); ?></td>
                <td><?php echo Format::db_datetime($row['updated']); ?>&nbsp;</td>
               </tr>
            <?php
            } //end of while.
        endif; ?>
    <tfoot>
     <tr>
        <td colspan="5">
            <?php if($res && $num){ ?>
            Выбрать:&nbsp;
            <a id="selectAll" href="#ckb">все</a>&nbsp;&nbsp;
            <a id="selectNone" href="#ckb">ничего</a>&nbsp;&nbsp;
            <a id="selectToggle" href="#ckb">обратить</a>&nbsp;&nbsp;
            <?php }else{
                echo 'No banned emails found!';
            } ?>
        </td>
     </tr>
    </tfoot>
</table>
<?php
if($res && $num): //Show options..
    echo '<div>&nbsp;Страница:'.$pageNav->getPageLinks().'&nbsp;</div>';
?>
<p class="centered" id="actions">
    <input class="button" type="submit" name="enable" value="Включить" >
    &nbsp;&nbsp;
    <input class="button" type="submit" name="disable" value="Отключить" >
    &nbsp;&nbsp;
    <input class="button" type="submit" name="delete" value="Удалить">
</p>
<?php
endif;
?>
</form>

<div style="display:none;" class="dialog" id="confirm-action">
    <h3>Подтверждение действий</h3>
    <a class="close" href="">&times;</a>
    <hr/>
    <p class="confirm-action" style="display:none;" id="enable-confirm">
        Вы уверены что хотите <b>включить</b> выбранные правила?
    </p>
    <p class="confirm-action" style="display:none;" id="disable-confirm">
        Вы уверены что хотите <b>отключить</b> выбранные правила?
    </p>
    <p class="confirm-action" style="display:none;" id="delete-confirm">
        <font color="red"><strong>Вы уверены что хотите УДАЛИТЬ выбранные правила?</strong></font>
    </p>
    <div>Подтвердите для продолжения...</div>
    <hr style="margin-top:1em"/>
    <p class="full-width">
        <span class="buttons" style="float:left">
            <input type="button" value="Нет" class="close">
        </span>
        <span class="buttons" style="float:right">
            <input type="button" value="Да" class="confirm">
        </span>
     </p>
    <div class="clear"></div>
</div>

