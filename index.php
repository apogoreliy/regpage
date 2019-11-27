<?php
    include_once "header.php";
    global $appRootPath;

    $indexPage = true;
    $isGuest= isset($memberId) ? NULL : true;
    $isUserWithRights = db_isAdmin($memberId) ? 1 : 0 ;
    $adminEvents = implode(',', db_getAdminEventsRespForReg($memberId));
    $isLink=false;
    $isInvited = false;
    $pwd = db_getSelfRegPwd();

    if (isset ($_POST["pwd"]) || isset($memberId)) $_SESSION["logged-in"]=(isset ($_POST["pwd"]) && ($_POST["pwd"]==$pwd || $_POST["pwd"]=="beInHim") || isset($memberId));

    $isEventAdmin = isset($memberId) ? db_hasRightToHandleEvents($memberId) : false;
    $isAuthorEvents = db_isAuthorEvents($memberId);
    $isAuthorArchiveEvents = db_isAuthorArciveEvents($memberId);
    $isAdminArchiveEvents = db_isAdminArciveEvents($memberId);
    $countries1 = db_getCountries(true);
    $countries2 = db_getCountries(false);

    include_once "nav.php";
    include_once 'modals.php';
?>


<div id='guest-container' class='container'>

<?php

// determine a special page
$specPage = NULL;
foreach (db_getSpecPages() as $sp){
    if (isset ($_GET[$sp])){
        $specPage = $sp;
        break;
    }
}

if (!$pwd)
{
    print '<h1 style="margin-top: 100px">В данный момент регистрация не производится</h1></div class="container">';
    include_once "footer.php";
    exit;
}


if (isset ($_GET["exit"])) $_SESSION["logged-in"]=false;
/*
if (isset ($_GET["link"])){
    $info = db_getEventMemberByLink ($_GET["link"]);
    $_SESSION["logged-in"]=$isLink=($info!=NULL);
}
if (isset ($_GET["invited"])){
    $invitation = UTILS::getUserInfoByLink($_GET["invited"]);

    $invitationEvent = db_getEvent((int)$invitation[0]);
    $invitationMember = db_getMember(str_repeat("0", 9 - strlen((int)$invitation[1])).''.(int)$invitation[1]);

    if($invitationEvent !== null && $invitationMember !== null){
        $_SESSION["logged-in"]=$isInvited=true;
    }
}
*/

if (isset ($_SESSION["logged-in"]) && $specPage){
    echo '<div class="container"><div style="background-color: white; padding: 20px;">';
    echo db_getCustomPage($specPage);
    echo'</div></div>';
}
else if (isset ($_SESSION["logged-in"])){
    $sort_field = 'start_date';
    $sort_type = isset ($_COOKIE['sort_type_event']) ? $_COOKIE['sort_type_event'] : 'asc';
?>
    <div id="eventTabs">
        <div class="tab-content">
            <div class="event-list-block">
                <?php
                    if($memberId == '000005716'){
                    echo '
                        <div class="btn-group">
                            <a class="btn btn-success btnAddEvent" title="Добавить мероприятие" href="#">
                                <i class="fa fa-plus"></i>
                                <span class="hide-name">Добавить</span>
                            </a>
                        </div>
                        ';
                    }
                ?>
                <div class="desctopVisible">
                <h3>Конференции и обучения</h3>
                    <div class="events-list-headers">
                        <span class="span5">Название</span>
                        <span class="span3">Место проведения</span>
                        <span class="span2 event-date"><a id="sort-start_date" href="#" title="сортировать">Даты</a>&nbsp;<i class="<?php echo $sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down'; ?>"></i></span>
                        <span class="span2 event-icons">&nbsp;</span>
                    </div>
                    <div class="list-events"></div>
                    <div class="block-hidden-events">
                        <div style="font-weight: bold; padding-left: 10px; padding-bottom: 10px; border-bottom: 1px solid #ddd">Скрытые мероприятия</div>
                        <div class="hidden-events-list"></div>
                    </div>
                </div>

                <div class="show-phone">
                    <div class="list-events"></div>
                    <div class="block-hidden-events">
                        <div style="font-weight: bold; padding-bottom: 10px; border-bottom: 1px solid #ddd">Скрытые мероприятия</div>
                        <div class="hidden-events-list"></div>
                    </div>
                </div>
                <div style="margin-top: 10px; margin-left: 10px;" ><a class="handle-hidden-events" href="#">Не отображать скрытые мероприятия <i class="fa fa-chevron-down"></i></a></div>
            </div>
            <div style="font-weight: bold;" class='empty-meeting-list-info'>Сейчас нет мероприятий, открытых для самостоятельной регистрации.</div>
        </div>
        <?php
            $sortField = isset ($_SESSION['sort_field_reference']) ? $_SESSION['sort_field_reference'] : 'name';
            $sortType = isset ($_SESSION['sort_type_reference']) ? $_SESSION['sort_type_reference'] : 'asc';
            $references = db_getReferences($sortField, $sortType);
            if($references){
                $regRefs = [];
                $indexRefs = [];
                $accountRefs = [];
                $countReferences = 0;

                foreach ($references as $key => $value) {
                    if($value['page'] == 'index' && $value['block_num'] != '0'){
                        $countReferences ++;
                        $indexRefs [] = '<div><a target="_blank" href="'.$value['link_article'].'">'.$value['name'].'</a></div>';
                    }
                    else if($value['page'] == 'reg' && $value['block_num'] != '0'){
                        $countReferences ++;
                        $regRefs [] = '<div><a target="_blank" href="'.$value['link_article'].'">'.$value['name'].'</a></div>';
                    }
                    else if($value['block_num'] != '0'){
                        $countReferences ++;
                        $accountRefs [] = '<div><a target="_blank" href="'.$value['link_article'].'">'.$value['name'].'</a></div>';
                    }
                }
                $refs = '';

                if($countReferences > 0){
                    if($indexRefs) $refs = '<div>'. implode('', $indexRefs).'</div>';
                    if($regRefs) $refs .= '<div>'. implode('', $regRefs).'</div>';
                    if($accountRefs) $refs .= '<div>'. implode('', $accountRefs).'</div>';

                    echo '<div style="margin-top:10px;" class="tab-content references-blocks">'. $refs .'</div>';
                }
            }
        ?>
        <div style="margin-top:10px; margin-left:10px; "><a id="frameArchive">Показать архив мероприятий</a></div>
        <iframe id="frameArchivePage" style="width:1168px; height: 1000px; border: none;"src="/archive.php"></iframe>
    </div>

<!-- Edit Member Modal -->
<div id="modalEditMember" data-width="600" class="modal-edit-member modal hide<?php if ($isLink || $isInvited) echo ' fade'; ?>" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-labelledby="editMemberEventTitle">
    <div class="modal-header">
        <button type="button" class="close close-form" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 class="editMemberEventTitle"></h4>
        <a style="margin-left: 0;" id="lnkEventInfo">Информация о мероприятии</a>
        <div style="margin-top: 10px;">
        <?php
           if ($isLink){
               echo '<span class="footer-status"><span class="eventMemberStatus"></span>';
               if ($info["regstate_key"] && $info["regstate_key"]!='05' && $info["regstate_key"]!='03')
                  echo '&nbsp;<a href="#" id="lnkCancelReg">Отменить регистрацию</a>';
               else if ($info["regstate_key"]=='03')
                  echo '&nbsp;<a href="#" id="lnkRestoreReg">Возобновить регистрацию</a>';
               echo '</span>';
           }
        ?>
        </div>
    </div>
    <div class="modal-body">
        <?php require_once 'formTab.php'; ?>
    </div>
    <div class="modal-footer">
      <div id="forAdminRegNotice" style="color: red; font-style: bold; font-size: 16px; padding-bottom: 15px; text-align: center;">
      </div>
        <button class="btn btn-primary disable-on-invalid" id="btnDoRegisterGuest">Отправить данные</button>
        <button class="btn" id="btnCancelChanges">Отмена</button>
    </div>
</div>

<?php include_once "modals.php"; ?>

<!-- Name Editing Message Modal -->
<div id="modalNameEdit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="regNameEdit" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="regNameEdit">Внимание!</h3>
        <p>Правила изменения ФИО участника</p>
    </div>
    <div class="modal-body">
        <ol>
            <li>Поле ФИО может редактировать <b>только ответственный за регистрацию</b>.</li>
            <li>Если фамилия недавно была изменена, напишите новую фамилию <b>в поле "Комментарий"</b> (например: "<i>Новая фамилия Петрова, с ноября 2013</i>").</li>
        <ol>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    </div>
</div>

<!-- Name Editing Message Modal -->
<div id="modalRejectMember" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="regNameEdit" aria-hidden="true">
    <div class="modal-body">
        <span style="font-size:16px;"></span>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary rejectEventRegistration">Подтвердить</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>

<!-- Success Saved Profile Data Modal -->
<div id="successSavedDataModal" class="modal hide fade" tabindex="-1" role="dialog"  aria-labelledby="regEndedTitle" aria-hidden="true">
    <div class="modal-body">
        <h3 style="text-align: center;">Ваши данные успешно сохранены!</h3>
    </div>
    <div class="modal-footer">
        <button id="changeBlankConfirmation" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Ok</button>
    </div>
</div>

<!-- MODALS FOR EVENTS -->

<!-- Delete Event Modal -->
<div id="modalDeleteEvent" class="modal hide fade" tabindex="-1" aria-labelledby="addEventTitle" role="dialog" aria-hidden="true">
    <div class="modal-body"></div>
    <div class="modal-footer">
        <button class="btn btn-primary doDeleteEvent">Удалить</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отменть</button>
    </div>
</div>

<!-- Handle Event Info Modal -->
<div id="modalHandleEventInfo" class="modal hide fade" tabindex="-1" aria-labelledby="addEventTitle" role="dialog" aria-hidden="true">
    <div class="modal-header"></div>
    <div class="modal-body">
        <div class="control-group row-fluid">
            <textarea name="editor1" id="editor1" class="span12 " rows="25"></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary doSaveEventInfo">Сохранить</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отменть</button>
    </div>
</div>

<!-- Show Event Info Modal -->
<div id="modalShowEventInfo" class="modal hide fade" data-width="700" tabindex="-1" aria-labelledby="addEventTitle" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <h4 style="display: inline-block;"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    </div>
    <div class="modal-body"></div>
</div>

<!-- GET EVENT STATISTIC CONFIRMATION Modal -->
<div id="modalGetEventArchiveConfirm" class="modal hide fade" tabindex="-1" aria-labelledby="addEventTitle" role="dialog" aria-hidden="true">
    <div class="modal-body">
        <h4>Вы действительно хотите архивировать данные этого мероприятия?</h4>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary doGetEventArchive">Да</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отменть</button>
    </div>
</div>

<!-- GET EVENT STATISTIC CONFIRMATION Modal -->
<div id="modalOrganizorInfo" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Информация об организаторе мероприятия</h3>
    </div>
    <div class="modal-body"></div>
    <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Ok</button>
    </div>
</div>

<!-- Registration Ended Message Modal -->
<div id="modalAddEditEvent" class="modal hide fade" tabindex="-1" role="dialog"  aria-labelledby="regEndedTitle" aria-hidden="true">
    <div class="modal-header">
    </div>
    <div class="modal-body">
        <div class="controls">
            <div class="control-group row-fluid">
                <label class="span12">Название мероприятия<sup>*</sup></label>
                <div class="control-group row-fluid">
                    <input class="span12 event-name" type="text" valid="required" placeholder="Название">
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12">Вид мероприятия<sup>*</sup></label>
                <div class="control-group row-fluid">
                    <select class="span12 event-type" valid="required" style="margin-bottom:0;">
                        <option value='_none_'></option>
                        <?php
                            foreach (getEventTypes() as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>";
                        ?>
                    </select>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12">Место проведения мероприятия<sup>*</sup></label>
                <div class="control-group row-fluid">
                    <select class="span12 event-locality" valid="required" style="margin-bottom:0;">
                        <option value='_none_'></option>
                        <?php
                            foreach (db_getLocalities() as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>";
                        ?>
                    </select>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12">Дата начала мероприятия<sup>*</sup></label>
                <div class="control-group row-fluid date">
                    <input type="text" class="form-control span12 event-start-date datepicker" readonly maxlength="10" placeholder="ДД.ММ.ГГГГ" valid="required, date">
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-th"></span>
                    </div>
                </div>
            </div>

            <div class="control-group row-fluid">
                <label class="span12">Дата окончания мероприятия<sup>*</sup></label>
                <div class="control-group row-fluid date">
                    <input class="form-control span12 event-end-date datepicker" readonly type="text" maxlength="10" placeholder="ДД.ММ.ГГГГ" valid="required, date">
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-th"></span>
                    </div>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12">Дата окончания регистрации<sup>*</sup></label>
                <div class="control-group row-fluid input-group date">
                    <input class="form-control span12 event-reg-end-date datepicker" readonly type="text" maxlength="10" placeholder="ДД.ММ.ГГГГ" valid="required, date">
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-th"></span>
                    </div>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12 notRequired">+  Закрытое мероприятие?</label>
                <div class="control-group row-fluid">
                    <select class="span12 event-private">
                        <option value='0' selected>НЕТ</option>
                        <option value='1'>ДА</option>
                    </select>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12 notRequired">+  Максимальное количество участников</label>
                <div class="control-group row-fluid">
                    <input class="span12 event-participants_count" type="text" placeholder="Количество">
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12 notRequired">+  Закрыть регистрацию?</label>
                <div class="control-group row-fluid">
                    <select class="span12 close_registration">
                        <option value='0' selected>НЕТ</option>
                        <option value='1'>ДА</option>
                    </select>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12 notRequired">+  Нужны паспортные данные?</label>
                <div class="control-group row-fluid">
                    <select class="span12 event-passport">
                        <option value='0' selected>НЕТ</option>
                        <option value='1'>ДА</option>
                    </select>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12 notRequired">+  Нужна предварительная оплата?</label>
                <div class="control-group row-fluid">
                    <select class="span12 event-prepayment">
                        <option value='0' selected>НЕТ</option>
                        <option value='1'>ДА</option>
                    </select>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12 notRequired">+  Нужна инфромация о транспорте?</label>
                <div class="control-group row-fluid">
                    <select class="span12 event-transport">
                        <option value='0' selected>НЕТ</option>
                        <option value='1'>ДА</option>
                    </select>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12 notRequired">+  Нужны данные загранпаспорта?</label>
                <div class="control-group row-fluid">
                    <select class="span12 event-tp">
                        <option value='0' selected>НЕТ</option>
                        <option value='1'>ДА</option>
                    </select>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12 notRequired">+  Нужна инфромация об авиарейсе?</label>
                <div class="control-group row-fluid">
                    <select class="span12 event-flight">
                        <option value='0' selected>НЕТ</option>
                        <option value='1'>ДА</option>
                    </select>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12 notRequired">+  Нужна инфромация о размещении?</label>
                <div class="control-group row-fluid">
                    <select class="span12 event-accom">
                        <option value='0' selected>НЕТ</option>
                        <option value='1'>ДА</option>
                    </select>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12 notRequired">+  Нужна инфромация о парковке?</label>
                <div class="control-group row-fluid">
                    <select class="span12 event-parking">
                        <option value='0' selected>НЕТ</option>
                        <option value='1'>ДА</option>
                    </select>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12 notRequired">+  Нужна инфромация о служении?</label>
                <div class="control-group row-fluid">
                    <select class="span12 event-service">
                        <option value='0' selected>НЕТ</option>
                        <option value='1'>ДА</option>
                    </select>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12 notRequired">+  Валюта и сумма взноса</label>
                <div class="control-group row-fluid">
                    <select class="span12 event-currency-modal">
                        <option value='_none_' selected>НЕТ</option>
                        <option value='RUR'>Рубли</option>
                        <option value='USD'>USD</option>
                        <option value='EUR'>EUR</option>
                    </select>
                <div class="control-group row-fluid">
                    <label class="span12">Сумма взноса</label>
                    <div class="control-group row-fluid">
                        <input class="span12 event-contrib-modal" type="number" placeholder="Сумма">
                    </div>
                </div>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12 notRequired">+  Емайл команды регистрации</label>
                <div class="control-group row-fluid">
                    <input class="span12 event-email-modal" type="email" placeholder="Емайл">
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12 notRequired">+  Организатор мероприятия</label>
                <div class="control-group row-fluid">
                    <input class="span12 event-organizer-modal" type="text" placeholder="Организатор">
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12 notRequired">+  Возрастные ограничения</label>
                <div class="control-group row-fluid">
                    <input class="span12 event-min-age-modal" type="number" placeholder="Минимум"> <input class="span12 event-max-age-modal" type="number" placeholder="Максимум">
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12 notRequired">+  Статус</label>
                <div class="control-group row-fluid">
                    <select class="span12 event-status-modal">
                        <option value='0' selected>Нет</option>
                        <option value='1'>Да</option>
                    </select>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12">Информация о мероприятии</label>
                <div class="control-group row-fluid">
                    <textarea class="span12 event-info" cols="5"></textarea>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12" style="min-height:20px">Зона доступа</label>
                <div class="control-group row-fluid zones-checkbox-block">
                    <div class="btn-group">
                        <label class="">Страны</label>
                        <input type="checkbox" data-field="c" >
                    </div>
                    <div class="btn-group">
                        <label class="">Области</label>
                        <input type="checkbox" data-field="r" >
                    </div>
                    <div class="btn-group">
                        <label class="">Местности</label>
                        <input type="checkbox" data-field="l" >
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <div class="zones-added"></div>
                    <input type="text" class="span12 search-zones" placeholder="Введите страну, область или местность">
                    <div class="zones-available"></div>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12" style="min-height:20px">Ответственные за регистрацию</label>
                <div class="control-group row-fluid">
                    <div class="reg-members-added"></div>
                    <input type="text" class="span12 search-reg-member" placeholder="Введите текст">
                    <div class="reg-members-available"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary btnHandleEventForm">Сохранить</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>

<script >

$(document).ready(function(){
    window.regAdmins = [];
    window.eventZones = [];
    loadEvents();
    var isEventAdminArc = '<?php echo $isEventAdmin; ?>';
    var hasEventAdmin = '<?php echo $adminEvents; ?>';
    var isAuthorSomeEvents = '<?php echo $isAuthorEvents; ?>';
    var isAuthorArchiveSomeEvents = '<?php echo $isAuthorArchiveEvents; ?>';
    var isAdminArchiveSomeEvents = '<?php echo $isAdminArchiveEvents; ?>';
    isEventAdminArc || hasEventAdmin.length > 0 || isAuthorSomeEvents || isAuthorArchiveSomeEvents || isAdminArchiveSomeEvents ? $('#frameArchive').show() : $('#frameArchive').hide();
    $('#frameArchivePage').hide();
    $('#frameArchive').click(function() {
      $('#frameArchivePage').is(':visible') ? $('#frameArchivePage').hide() : $('#frameArchivePage').show();
      resizeIframe();
      $('#frameArchivePage').is(':visible') ? $('#frameArchive').focus() : $('#frameArchivePage').focus();

    });
    $(window).resize(function(){
      resizeIframe();
    });
    function resizeIframe() {
      if ($(window).width() >= 1199 && $('#frameArchivePage').is(':visible')) {
        $('#frameArchivePage').css('width', '1168px');
      } else if (($(window).width() < 1199 && $(window).width() >= 980) && $('#frameArchivePage').is(':visible')) {
        $('#frameArchivePage').css('width', '940px');
      } else if (($(window).width() < 980 && $(window).width() >= 768) && $('#frameArchivePage').is(':visible')) {
        $('#frameArchivePage').css('width', '724px');
      } else if ($(window).width() < 768 && $('#frameArchivePage').is(':visible')) {
        var x = $(window).width()-40;
        $('#frameArchivePage').css('width', x+'px');
      }
    }
    $('.handle-hidden-events').click(function(){
        $(this).hasClass('active') ? $(this).removeClass('active') : $(this).addClass('active');
        localStorage.setItem('hide-hiden-events', $(this).hasClass('active'));
        buildEventsList(getEvents());
    });
    // START confirme modal
    $('#changeBlankConfirmation').click(function() {
      if ($('#modalShowEventInfo').is(':visible')) {
          $('#modalShowEventInfo').modal('hide');
      }
      loadEvents();
    });
    // STOP confirme modal
    function getEvents(){
        var isTabletMode = $(document).width() < 768;
        var events = [];

        $((isTabletMode ? ".show-phone " : ".desctopVisible " ) + " .list-events div.event-row").each(function(){
             events.push(returnEventObject($(this).attr('data-archived'), $(this).attr('data-author'), $(this).attr('data-end_date'),
                                  $(this).attr('data-id'), $(this).attr('data-is_active'), $(this).attr('data-locality_name'),
                                  $(this).attr('data-name'), $(this).attr('data-private'), $(this).attr('data-start_date'), $(this).attr('data-regstate_key')));
        });

        $((isTabletMode ? ".show-phone " : ".desctopVisible " ) + " .hidden-events-list div.event-row").each(function(){
            events.push(returnEventObject($(this).attr('data-archived'), $(this).attr('data-author'), $(this).attr('data-end_date'),
                                  $(this).attr('data-id'), $(this).attr('data-is_active'), $(this).attr('data-locality_name'),
                                  $(this).attr('data-name'), $(this).attr('data-private'), $(this).attr('data-start_date'), $(this).attr('data-regstate_key')));
        });
        return events;
    }

    function returnEventObject(archived, author, end_date, id, is_active, locality_name, name, private, start_date, regstate_key){
        return {
            archived : archived,
            author : author,
            end_date : end_date,
            id : id,
            is_active : is_active,
            locality_name : locality_name,
            name : name,
            private : private,
            start_date : start_date,
            regstate_key : regstate_key
        }
    }

    function setFiltersForRequest(){
        var sort_type = 'asc', sort_field = 'start_date';

        $(" a[id|='sort']").each (function (i) {
            if ($(this).siblings("i.icon-chevron-down").length) {
                sort_type = 'asc';
                sort_field = $(this).attr("id").replace(/^sort-/,'');
                setCookie("sort_type_event", sort_type);
                setCookie("sort_field_event", sort_field);
            }
            else if ($(this).siblings("i.icon-chevron-up").length) {

                sort_type = 'desc';
                sort_field = $(this).attr("id").replace(/^sort-/,'');
                setCookie("sort_type_event", sort_type);
                setCookie("sort_field_event", sort_field);
            }
        });

        var filters = [];
        filters = [{name: "sort_field", value: sort_field},
                   {name: "sort_type", value: sort_type}];
        return filters;
    }

    function getRequestFromFilters(arr){
        var str = '';
        arr.map(function(item){
            str += ('&'+item["name"] +'='+item["value"]);
        });
        return str;
    }

    function loadEvents(){
        var request = getRequestFromFilters(setFiltersForRequest());

        $.post('/ajax/event.php?get_events'+request)
        .done(function(data){
            buildEventsList(data.events);
        });
    }

    $("a[id|='sort']").click (function (){
        var id = $(this).attr("id");
        var icon = $(this).siblings("i");
        $(".tab-content a[id|='sort'][id!='"+id+"'] ~ i").attr("class","icon-none");
        icon.attr ("class", icon.hasClass("icon-chevron-down") ? "icon-chevron-up" : "icon-chevron-down");
        var sortedFields = setFiltersForRequest();
        sortEvents(sortedFields[0]['value'], sortedFields[1]['value']);
    });

    $('.doGetEventArchive').click(function(e){
        e.stopPropagation();
        var eventId = $(this).attr('data-id'),
            //request = getRequestFromFilters(setFiltersForRequest());
            request, eventMemberAccess;
            var isSysAdmin= '<?php echo $isEventAdmin; ?>';
            var isMemberAdminOfEvent = '<?php echo $adminEvents; ?>';
            if (isMemberAdminOfEvent) {
                isMemberAdminOfEvent = isMemberAdminOfEvent.split(',');
                eventMemberAccess = isMemberAdminOfEvent.indexOf(eventId);
            }
            eventMemberAccess != -1 ? eventMemberAccess = 1 : eventMemberAccess = 0;
            isSysAdmin != false ? isSysAdmin = 1 : isSysAdmin = 0;
        $.post('/ajax/event.php?set_archive', {eventId: eventId, isAdmin: eventMemberAccess, isSysAdmin: isSysAdmin})
        .done(function(data){
            if(!data.res){
                showError("Архивирование данных отклонено. Записи в БД ещё не синхронизированы с 1С или вы не ответственный за это мероприятие, возможно это дублирующий архив.");
            }
            buildEventsList(data.events);
            $("#modalGetEventArchiveConfirm").modal('hide');
        });
    });

    function sortEvents(sortField, sortType){
        var events = getEvents();
        events.sort(function(a,b){
            if(sortType === 'asc'){
                if (a[sortField] > b[sortField]) {
                    return 1;
                }
                if (a[sortField] < b[sortField]) {
                    return -1;
                }
                return 0;
            }
            else{
                if (a[sortField] < b[sortField]) {
                    return 1;
                }
                if (a[sortField] > b[sortField]) {
                    return -1;
                }
                return 0;
            }
        });
        buildEventsList(events);
    }

    function buildEventsList(events){
      // if member is the admin of event then RIGHT = true ELSE false
        if(events && events.length > 0){
            var eventRows = [], eventRowsTablet = [], hiddenEventsDesctop = [], hiddenEventsTablet = [],
                memberId = "<?php echo $memberId; ?>",
                warningMsg = "У вас нет возможности редактировать и удалять данное мероприятие",
                isEventsAdmin = '<?php echo $isEventAdmin; ?>',
                isUserWithRights = '<?php echo $isUserWithRights; ?>',
                hidenEvents = (localStorage.getItem('hiden_events') && localStorage.getItem('hiden_events').split(',')) || [],
                hideHidenEvents = localStorage.getItem('hide-hiden-events') === 'true', icons = '', eventAttrs = '', desctopEvent = '', tabletEvent = '';

            for(var i in events){
                var event = events[i], archiveAccess = -1,
                    isEventActive = parseInt(event.is_active);
                    var evArr = '<?php echo $adminEvents; ?>';
                    if (evArr) {
                        evArr = evArr.split(',');
                        archiveAccess = evArr.indexOf(event.id);
                    }

                icons =
                    ( in_array(event.id, hidenEvents) ? '<span style="display: inline;" class="fa fa-arrow-up btnEventHiding" title="Показать мероприятие"></span>' : '<span style="display: inline; margin-right: 5px; margin-left: 5px;" class="fa fa-arrow-down btnEventHiding" title="Скрыть мероприятие"></span>') +
                    ( (memberId == '000005716') ?
                       ( isEventActive ? '<span  style="display: inline; margin-right: 5px; margin-left: 5px;"class="fa fa-check-circle  btnEventActivity" title="Сделать неактивным"></span>' : '<span style="display: inline; margin-right: 5px; margin-left: 5px;" class="fa fa-times btnEventActivity" title="Сделать активным"></span>') +
                        '<span style="display: inline; margin-right: 5px; margin-left: 5px;" class="fa fa-pencil btnEditEvent" title="Редактировать мероприятие"></span>'+
                        '<span style="display: inline; margin-right: 5px;" class="fa fa-trash-o btnRemoveEvent" title="Удалить мероприятие" aria-hidden="true"></span>' : '')+
                        ((event.archived === '0' && (isEventsAdmin || archiveAccess != -1 || memberId === event.author)) ? '<span style="display: inline; margin-left: 5px;" class="fa fa-database btnGetArchive" title="Архивировать данные" aria-hidden="true"></span>' : '');

                eventAttrs = ' class="event-row" data-name="'+event.name+'" data-locality_name="'+event.locality_name+'" '+
                        'data-start_date="'+event.start_date+'" data-end_date="'+event.end_date+'" data-private="'+event.private+'" '+
                        'data-is_active="'+event.is_active+'" data-id="'+event.id+'" data-author="'+event.author+'" '+
                        'data-archived="'+event.archived+'" data-regstate_key="'+event.regstate_key+'" data-max_age="'+event.max_age+'" data-min_age="'+event.min_age+'" ';

                var regstateText='', regstateClass = '';
                if(event.member_key !== null){
                    switch (event.regstate_key){
                        case null: regstateText='ожидание регистрации'; regstateClass='info';break;
                        case '01': regstateText='ожидание подтверждения'; regstateClass='warning';break;
                        case '02': regstateText='ожидание подтверждения'; regstateClass='warning';break;
                        case '03': regstateText='ожидание отмены'; regstateClass='warning';break;
                        case '04': regstateText='регистрация подтверждена'; regstateClass='success';break;
                        case '05': regstateText='регистрация отменена'; regstateClass='important';break;
                        default : regstateText=''; regstateClass='';break;
                    }
                }

                desctopEvent = '<div '+eventAttrs+'>'+
                            '<span class="span5 event-name">'+ event.name + '</span>'+
                            '<span class="span3">'+ event.locality_name + '</span>'+
                            '<span class="span2 event-date">'+ formatDDMM(event.start_date) + ' - ' + formatDDMM(event.end_date)+'</span>'+
                            '<span class="span2 event-icons"  style="width: 190px";>'+ (regstateClass == "" ?  "" : '<span style="margin-top:5px; margin-left: 0px; margin-right: 19px; display: inline;" class="label label-'+regstateClass+'">'+ regstateText + '</span>') +((regstateText) ? ((regstateText === 'регистрация подтверждена' || regstateText === 'ожидание подтверждения') ? '<br><span style="padding-left: 0px;"><a style="padding-left: 0px; font-size: 12px" class="handleRegistrationFast editEventMember" title="Редактировать данные"> Изменить</a></span><span><a class="rejectRegistrationFast" title="Отменить регистрацию" style=" margin-right: 12px; font-size: 12px"> Отменить</a></span>':''):'<span style="margin-top:5px; margin-left: 0px; margin-right: 1px; padding-left:0px;"><a class="handleRegistrationFast addEventMember" >Зарегистрироваться</a></span>')+ icons +'</span>'+
                            '</div>';

                tabletEvent = '<div '+eventAttrs+'>'+
                            '<div class="event-name"><strong>'+ event.name.split('(')[0] + '</strong></div>'+
                            ( event.name.split('(')[1] ? '<div class="event-name">'+ event.name.split('(')[1].split(')')[0] + '<span>, '+ event.locality_name + '.</span></div>' : '')+
                            '<div><span style="margin-top:5px; margin-right:5px;" class="label label-'+regstateClass+'">'+ regstateText + '</span>' + icons+ '</div>'+
                        '</div>';

                if((!parseInt(isUserWithRights) && parseInt(event.private)>0 && !in_array(event.id, evArr.split(',')) && event.regstate_key === null) || ((memberId !== event.author || memberId === '000005716') && !isEventActive)){
                    continue;
                }
                else if(in_array(event.id, hidenEvents)){
                    hiddenEventsDesctop.push(desctopEvent);
                    hiddenEventsTablet.push(tabletEvent);
                }
                else{
                    eventRows.push(desctopEvent);
                    eventRowsTablet.push(tabletEvent);
                }
            }

            if(hidenEvents.length > 0){
                $(".handle-hidden-events").show();
                hideHidenEvents ? $(".block-hidden-events").hide() : $(".block-hidden-events").show();
                hideHidenEvents ? $(".handle-hidden-events").addClass('active').html("Показывать скрытые мероприятия") : $(".handle-hidden-events").removeClass('active').html("Не показывать скрытые мероприятия");
            }
            else{
                $(".handle-hidden-events, .block-hidden-events").hide();
            }

            if(eventRows.length > 0 ){
                $("#eventTabs .event-list-block").show();
                $("#eventTabs .empty-meeting-list-info").hide();

                $('.desctopVisible .list-events').html(eventRows.join(''));
                $('.show-phone .list-events').html(eventRowsTablet.join(''));
                $('.desctopVisible .hidden-events-list').html(hiddenEventsDesctop.join(''));
                $('.show-phone .hidden-events-list').html(hiddenEventsTablet.join(''));

                $('.btnGetArchive').click(function(e){
                    e.stopPropagation();
                    var eventId = $(this).parents('.event-row').attr('data-id');

                    var modal = $("#modalGetEventArchiveConfirm");
                    modal.find('.doGetEventArchive').attr('data-id', eventId);
                    modal.modal('show');

                });

                $('.btnEventHiding').click(function(e){
                    e.stopPropagation();
                    var isEventHiden = $(this).hasClass('fa-times'),
                        eventId = $(this).parents('.event-row').attr('data-id');

                    if(!isEventHiden && !in_array(eventId, hidenEvents)){
                        hidenEvents.push(eventId);
                    }
                    else{
                        var index = hidenEvents.indexOf(eventId);
                        hidenEvents.splice(index, 1);
                    }

                    localStorage.setItem('hiden_events', hidenEvents.join(','));

                    buildEventsList(events);
                });

                // handle event activity
                $('.btnEventActivity').click(function(e){
                    e.stopPropagation();
                    var isEventActive = $(this).hasClass('fa-check-circle'),
                        eventId = $(this).parents('.event-row').attr('data-id'),
                        request = getRequestFromFilters(setFiltersForRequest());

                    $.post('/ajax/event.php?set_activity'+request, {eventId: eventId, isActive : !isEventActive})
                    .done(function(data){
                        buildEventsList(data.events);
                    });
                });

                // Confirmation to remove an event
                $('.btnRemoveEvent').click(function(e){
                    e.stopPropagation();
                    var event = $(this).parents('.event-row'),
                        eventId = event.attr('data-id'),
                        eventName = event.find('.event-name').text(),
                        author = event.attr('data-author'),
                        isArchived = event.attr('data-archived');

                    if(!isMemberAuthorEvent(author, memberId, warningMsg)){
                        return;
                    }

                    var modalWindow = $('#modalDeleteEvent');
                    modalWindow.find('.modal-body').html('<h4 data-id="'+ eventId+'" style="text-align: center;">'+(isArchived === '1' ? '' : 'Данные по этому мероприятию ещё не архивированы. <br/>')+
                            'Вы действительно хотите удалить мероприятие - '+ eventName +' ?</h4>');
                    modalWindow.modal('show');
                });

                // Get event info to edit
                $('.btnEditEvent').click(function(e){
                    e.stopPropagation();
                    var event = $(this).parents('.event-row'),
                        eventId = event.attr('data-id'),
                        author = event.attr('data-author');

                    if(!isMemberAuthorEvent(author, memberId, warningMsg)){
                        return;
                    }

                    $.post('/ajax/event.php?get_event', {eventId: eventId})
                    .done(function(data){
                        fillEventForm(data.event);
                    });
                });

                // Show an info regarding an event
                /*$('.list-events .event-row .editEventMember').click(function(e){
                e.stopPropagation();
                console.log('Im here');
              });*/

                $(".rejectRegistrationFast").click (function (e){
                  e.stopPropagation();
                  var modal = $(this).parents('div');
                  var eventId = modal.attr ('data-id');
                  var eventName = modal.attr ('data-name');
                  window.currentEventId = eventId;

                  $('#modalRejectMember span').html('Вы действительно хотите отменить регистрацию на мероприятие <h4>' + eventName + '?</h4>');
                  $('#modalRejectMember').modal('show');
                });

                $('.handleRegistrationFast').click(function(e){
                  e.stopPropagation();
                  $('.theActiveEvent').removeClass('theActiveEvent');
                  var thisParent = $(this).parent().parent().parent();
                  $(thisParent).addClass('theActiveEvent');
                  var eventId = $(thisParent).attr('data-id');
                  arriveDepartMyself($(thisParent).attr('data-start_date'),'.emArrDate');
                  arriveDepartMyself($(thisParent).attr('data-end_date'),'.emDepDate');

                  $.post('/ajax/event.php?get_member_event', {eventId: eventId})
                  .done(function(data){
                      if(!data.error){
                          var modal = $("#modalShowEventInfo"),
                              event = data.event,
                              btnText, btnClass,
                              eventInfo = '<div style="text-align:justify;">'+(event.info)+'</div><div class="official-info-link"></div><div class="official-info-block" style="display:none; font-size:x-small;color:grey;line-height:10px"></div>',
                              member = data.member, memberInfo = data.member_info;

                          if(member){
                              switch (member['regstate_key']){
                                  case '03': btnText='ожидание отмены'; btnClass='warning';break;
                                  case '04': btnText='регистрация подтверждена'; btnClass='success';break;
                                  case '05': btnText='регистрация отменена'; btnClass='important';break;
                                  default : btnText='ожидание подтверждения'; btnClass='warning';break;
                              }
                          }
                          var userInfo =  '<div style="margin-bottom:15px;">'+
                              (member ? member['regstate_key'] != '05' && member['regstate_key'] != '03' ? '<a class="btn btn-primary handleRegistration editEventMember" type="button" title="Редактировать данные" style="margin-right: 5px;"><i class="fa fa-pencil icon-white"></i>&nbsp;Изменить</a> <a class="btn btn-danger rejectRegistration" type="button" title="Отменить регистрацию" style="margin-right: 5px;"><i class="fa fa-times fa-lg icon-white"></i>&nbsp;Отменить</a> ' : '&nbsp;'  :  '<a class="btn btn-success handleRegistration addEventMember" type="button" title="Зарегистрироваться на мероприятие" style="margin-right: 5px;"> <i class="fa fa-check icon-white"></i> Зарегистрироваться</a>') +
                              ' <a class="btn send-message" type="button" data-email="'+(memberInfo ? memberInfo['email'] : "")+'" data-name="'+(memberInfo ? memberInfo['name']: "")+'" title="Сообщение команде регистрации" style="margin-right: 5px;"><i class="fa fa-envelop icon-envelope"></i></a>' +
                              (member ? '<div style="margin-top:10px;" ><span class="label label-'+btnClass+'">'+btnText+'</span></div>' : '&nbsp;') +
                              ( member && ( member['regstate_key'] == '05' || member['regstate_key'] == '03') && member['admin_comment'].length > 0 ? "<p><div style='color: red;'>Причина отмены: "+member['admin_comment']+"</div></p>" : "")+
                                      '</div>';

                          modal.find('.modal-header h4').html(he(event.event_name) + ( !member && (event.close_registration === "1" || event.stop_registration === "1") ? ' <label class="label label-danger">Регистрация закрыта</label>' : '' )).attr('data-stop_registration', event.stop_registration).attr('data-close_registration', event.close_registration);
                          modal.find('.modal-body').html(userInfo+eventInfo);
                          modal.attr('data-event-id', eventId);
                          modal.modal('show');

                          $(".official-info-link").html(event.organizer !== '' ? '<a style="font-size:x-small; color:grey" href="#" class="organizor-info">Официальная информация</a>' : '');

                          $(".organizor-info").click(function(e){
                              e.preventDefault();
                              e.stopPropagation();
                              $(".official-info-block").css('display')==='none' ? $(".official-info-block").html('<div>Организатор: '+event.organizer+'. </div><div>Вид события: христианское мероприятие (богослужение).</div>').show() : $(".official-info-block").hide();
                          });

                          $(".rejectRegistration").click (function (){
                              var modal = $(this).parents('#modalShowEventInfo');
                              var eventId = modal.attr ('data-event-id');
                              var eventName = modal.find ('h4').text();
                              window.currentEventId = eventId;

                              $('#modalRejectMember span').html('Вы действительно хотите отменить регистрацию на <h4>' + eventName + '?</h4>');
                              $('#modalRejectMember').modal('show');
                          });

                          $(".send-message").click (function (){
                              var eventId = $(this).parents('#modalShowEventInfo').attr ('data-event-id');
                              window.currentEventId = eventId;
                              var name = $(this).attr('data-name'), email = $(this).attr('data-email');

                              $("#sendMsgName").val(name);
                              $("#sendMsgEmail").val (email);
                              $("#sendMsgText").val("");
                              $('#modalEventSendMsg').modal('show');
                          });
                      }
                      setTimeout(function () {
                        if ($('#modalShowEventInfo').is(':visible')) {
                          function clickFastRegAutomatic() {
                            var memberId = '<?php echo $memberId; ?>', isThisAdmin = '<?php echo $isUserWithRights; ?>' ,modalWindow = $('#modalShowEventInfo'),
                                eventId = modalWindow.attr ('data-event-id'), stopRegistration = modalWindow.find('.modal-header h4').attr('data-stop_registration'), closeRegistration = modalWindow.find('.modal-header h4').attr('data-close_registration');
                                window.currentEventId = eventId;

                                var isEditMode = $('#modalShowEventInfo .handleRegistration').hasClass('editEventMember'), request = isEditMode ? "?edit_registration" : "?add_registration" ;

                                if(!isEditMode && ( closeRegistration === "1" || stopRegistration === "1" )){
                                  showModalHintWindow("<strong>Онлайн-регистрация на это мероприятие закрыта.<br>По всем вопросам <a href='' data-toggle='modal' data-target='#modalEventSendMsg'>обращайтесь к команде регистрации.</a> </strong>");
                                  return;
                                }
                                if(memberId){
                                  $.getJSON('/ajax/event.php'+request , { eventIdRegistration: eventId})
                                  .done (function(data){
                                    fillEditMember (memberId, data.eventmember, data.localities);
                                    $('#btnDoRegisterGuest').removeClass('guest');
                                    isEditMode ? $('#btnDoRegisterGuest').addClass('edit') : $('#btnDoRegisterGuest').removeClass('edit');
                                    $('#modalEditMember').modal('show');
                                    isEditMode ? $('#modalShowEventInfo').modal('hide') : '';
                                    $('.emMate').hide();
                                    $('.emMateLbl').hide();
                                  });
                                } else{
                                  $('#btnDoRegisterGuest').addClass('guest');
                                  showEmptyForm (eventId);
                                }
                              }
                              clickFastRegAutomatic();
                            }
                    }, 600);
                  });
                  /*
                  var memberId = '<?php echo $memberId; ?>', isThisAdmin = '<?php echo $isUserWithRights; ?>';
                  modalWindow = $(this).parent().parent().parent();
                  eventId = modalWindow.attr ('data-id');
                  // Данные ниже должны быть сверены с конкретным пользователем
                  stopRegistration = modalWindow.attr('data-is_active');
                  closeRegistration = modalWindow.attr('data-private');
                  window.currentEventId = eventId;

                  var isEditMode = $(this).hasClass('editEventMember'), request = isEditMode ? "?edit_registration" : "?add_registration" ;
console.log('stop is ', stopRegistration, 'close is ', closeRegistration, modalWindow);
                  if(!isEditMode && ( closeRegistration === "1" || stopRegistration === "0" )){
                      showModalHintWindow("<strong>Онлайн-регистрация на это мероприятие закрыта.<br>По всем вопросам <a href='' data-toggle='modal' data-target='#modalEventSendMsg'>обращайтесь к команде регистрации.</a> </strong>");
                      return;
                  }
                  if(memberId){
                      $.getJSON('/ajax/event.php'+request , { eventIdRegistration: eventId})
                      .done (function(data){
                          fillEditMember (memberId, data.eventmember, data.localities);
                          $('#btnDoRegisterGuest').removeClass('guest');
                          isEditMode ? $('#btnDoRegisterGuest').addClass('edit') : $('#btnDoRegisterGuest').removeClass('edit');
                          $('#modalEditMember').modal('show');
                          $('.emMate').hide();
                          $('.emMateLbl').hide();
                      });
                  } else{
                      $('#btnDoRegisterGuest').addClass('guest');
                      showEmptyForm (eventId);
                  }*/
                });

                $('.list-events .event-row').click(function(e){
                    e.stopPropagation();
                    $('.theActiveEvent').removeClass('theActiveEvent');
                    $(this).addClass('theActiveEvent');
                    var eventId = $(this).attr('data-id');
                    arriveDepartMyself($(this).attr('data-start_date'),'.emArrDate');
                    arriveDepartMyself($(this).attr('data-end_date'),'.emDepDate');

                    $.post('/ajax/event.php?get_member_event', {eventId: eventId})
                    .done(function(data){
                        if(!data.error){
                            var modal = $("#modalShowEventInfo"),
                                event = data.event,
                                btnText, btnClass,
                                eventInfo = '<div style="text-align:justify;">'+(event.info)+'</div><div class="official-info-link"></div><div class="official-info-block" style="display:none; font-size:x-small;color:grey;line-height:10px"></div>',
                                member = data.member, memberInfo = data.member_info;

                            if(member){
                                switch (member['regstate_key']){
                                    case '03': btnText='ожидание отмены'; btnClass='warning';break;
                                    case '04': btnText='регистрация подтверждена'; btnClass='success';break;
                                    case '05': btnText='регистрация отменена'; btnClass='important';break;
                                    default : btnText='ожидание подтверждения'; btnClass='warning';break;
                                }
                            }
                            var userInfo =  '<div style="margin-bottom:15px;">'+
                                (member ? member['regstate_key'] != '05' && member['regstate_key'] != '03' ? '<a class="btn btn-primary handleRegistration editEventMember" type="button" title="Редактировать данные" style="margin-right: 5px;"><i class="fa fa-pencil icon-white"></i>&nbsp;Изменить</a> <a class="btn btn-danger rejectRegistration" type="button" title="Отменить регистрацию" style="margin-right: 5px;"><i class="fa fa-times fa-lg icon-white"></i>&nbsp;Отменить</a> ' : '&nbsp;'  :  '<a class="btn btn-success handleRegistration addEventMember" type="button" title="Зарегистрироваться на мероприятие" style="margin-right: 5px;"> <i class="fa fa-check icon-white"></i> Зарегистрироваться</a>') +
                                ' <a class="btn send-message" type="button" data-email="'+(memberInfo ? memberInfo['email'] : "")+'" data-name="'+(memberInfo ? memberInfo['name']: "")+'" title="Сообщение команде регистрации" style="margin-right: 5px;"><i class="fa fa-envelop icon-envelope"></i></a>' +
                                (member ? '<div style="margin-top:10px;" ><span class="label label-'+btnClass+'">'+btnText+'</span></div>' : '&nbsp;') +
                                ( member && ( member['regstate_key'] == '05' || member['regstate_key'] == '03') && member['admin_comment'].length > 0 ? "<p><div style='color: red;'>Причина отмены: "+member['admin_comment']+"</div></p>" : "")+
                                        '</div>';

                            modal.find('.modal-header h4').html(he(event.event_name) + ( !member && (event.close_registration === "1" || event.stop_registration === "1") ? ' <label class="label label-danger">Регистрация закрыта</label>' : '' )).attr('data-stop_registration', event.stop_registration).attr('data-close_registration', event.close_registration);
                            modal.find('.modal-body').html(userInfo+eventInfo);
                            modal.attr('data-event-id', eventId);
                            modal.modal('show');

                            $(".official-info-link").html(event.organizer !== '' ? '<a style="font-size:x-small; color:grey" href="#" class="organizor-info">Официальная информация</a>' : '');

                            $(".organizor-info").click(function(e){
                                e.preventDefault();
                                e.stopPropagation();
                                $(".official-info-block").css('display')==='none' ? $(".official-info-block").html('<div>Организатор: '+event.organizer+'. </div><div>Вид события: христианское мероприятие (богослужение).</div>').show() : $(".official-info-block").hide();
                            });

                            $('.handleRegistration').click(function(){
                                var memberId = '<?php echo $memberId; ?>', isThisAdmin = '<?php echo $isUserWithRights; ?>' ,modalWindow = $(this).parents('#modalShowEventInfo'),
                                    eventId = modalWindow.attr ('data-event-id'), stopRegistration = modalWindow.find('.modal-header h4').attr('data-stop_registration'), closeRegistration = modalWindow.find('.modal-header h4').attr('data-close_registration');
                                window.currentEventId = eventId;

                                var isEditMode = $(this).hasClass('editEventMember'), request = isEditMode ? "?edit_registration" : "?add_registration" ;

                                if(!isEditMode && ( closeRegistration === "1" || stopRegistration === "1" )){
                                    showModalHintWindow("<strong>Онлайн-регистрация на это мероприятие закрыта.<br>По всем вопросам <a href='' data-toggle='modal' data-target='#modalEventSendMsg'>обращайтесь к команде регистрации.</a> </strong>");
                                    return;
                                }
                                if(memberId){
                                    $.getJSON('/ajax/event.php'+request , { eventIdRegistration: eventId})
                                    .done (function(data){
                                        fillEditMember (memberId, data.eventmember, data.localities);
                                        $('#btnDoRegisterGuest').removeClass('guest');
                                        isEditMode ? $('#btnDoRegisterGuest').addClass('edit') : $('#btnDoRegisterGuest').removeClass('edit');
                                        $('#modalEditMember').modal('show');
                                        $('.emMate').hide();
                                        $('.emMateLbl').hide();
                                    });
                                } else{
                                    $('#btnDoRegisterGuest').addClass('guest');
                                    showEmptyForm (eventId);
                                }
                            });

                            $(".rejectRegistration").click (function (){
                                var modal = $(this).parents('#modalShowEventInfo');
                                var eventId = modal.attr ('data-event-id');
                                var eventName = modal.find ('h4').text();
                                window.currentEventId = eventId;

                                $('#modalRejectMember span').html('Вы действительно хотите отменить регистрацию на <h4>' + eventName + '?</h4>');
                                $('#modalRejectMember').modal('show');
                            });

                            $(".send-message").click (function (){
                                var eventId = $(this).parents('#modalShowEventInfo').attr ('data-event-id');
                                window.currentEventId = eventId;
                                var name = $(this).attr('data-name'), email = $(this).attr('data-email');

                                $("#sendMsgName").val(name);
                                $("#sendMsgEmail").val (email);
                                $("#sendMsgText").val("");
                                $('#modalEventSendMsg').modal('show');
                            });
                        }
                    });
                });
            }
            else{
                $("#eventTabs .event-list-block").hide();
                $("#eventTabs .empty-meeting-list-info").show();
            }
        }
        else{
            $('.list-events').html('<tr><h3 style="text-align:center;">На данный момент нет доступных мероприятий</h3></tr>');
        }
    }

    function isMemberAuthorEvent (author, memberId, warningMsg){
        if(author !== memberId){
            showError(warningMsg);
            return false;
        }
        return true;
    }

    // Adding events
    $('.btnAddEvent').click(function(){
        fillEventForm();
    });

    function fillEventForm(event){
        var form = $('#modalAddEditEvent');
        if(event){
            if(event.admins !== ''){
                var admins = event.admins.split(';'), arrAdmins = [];
                for(var i in admins){
                    var adminInfo = admins[i].split(',');
                    arrAdmins.push(
                        {id : adminInfo[0],
                        name : adminInfo[1],
                        email : adminInfo[2],
                        locality: adminInfo[3]
                    });
                }
            }
            form.attr('data-event-id', event.event_id);
            form.attr('data-team_key', event.team_key);

            if(event.zones !== ''){
                var zones = event.zones.split(','), arrZones = [];
                for(var i in zones){
                    var zone = zones[i].split(':');
                    arrZones.push(
                        {id : zone[0],
                        name : zone[1],
                        field : getEventFieldZoneArea(zone[0])
                    });
                }
            }

          form.modal('show');
        // author
          form.find('.event-name').val(event ? event.event_name : '').keyup();
          form.find('.event-type').val(event ? event.event_type : '_none_').change();
          form.find('.event-locality').val(event ? event.locality_key : '_none_').change();
          form.find('.event-start-date').val(event ? formatDate(event.start_date) : '').keyup();
          form.find('.event-end-date').val(event ? formatDate(event.end_date) : '').keyup();
          form.find('.event-reg-end-date').val(event ? formatDate(event.regend_date) : '').keyup();
          form.find('.event-passport').val(event ? event.need_passport : 0);
          form.find('.event-prepayment').val(event ? event.need_prepayment : 0);
          form.find('.event-private').val(event ? event.private : 0);
          form.find('.event-transport').val(event ? event.need_transport : 0);
          form.find('.event-tp').val(event ? event.need_tp : 0);
          form.find('.event-flight').val(event ? event.need_flight : 0);
          form.find('.event-info').val(event ? event.info : '');
          form.find('.event-currency-modal').val(event ? (event.currency != null ? event.currency : '_none_') : '');
          form.find('.event-contrib-modal').val(event ? event.contrib : '');
          form.find('.event-email-modal').val(event ? event.team_email : '');
          form.find('.event-organizer-modal').val(event ? event.organizer : '');
          form.find('.event-min-age-modal').val(event ? event.max_age : '');
          form.find('.event-max-age-modal').val(event ? event.min_age : '');
          form.find('.event-status-modal').val(event ? event.need_status : '');
          form.find('.reg-members-added').html(event ? handleAdminsList(arrAdmins, true) : '');
          form.find('.search-reg-member').val('');
          form.find('.zones-added').html(event ? handleEventZones(arrZones, true) : '');
          form.find('.search-zones').val('');
          form.find('.reg-members-available').html('');
          form.find('.close_registration').val(event.close_registration );
          form.find('.event-participants_count').val(event.participants_count );
        } else {
          form.modal('show');
          form.find('.event-name').val(event ? event.event_name : '').keyup();
          form.find('.event-type').val(event ? event.event_type : '_none_').change();
          form.find('.event-locality').val(event ? event.locality_key : '_none_').change();
          form.find('.event-start-date').val(event ? formatDate(event.start_date) : '').keyup();
          form.find('.event-end-date').val(event ? formatDate(event.end_date) : '').keyup();
          form.find('.event-reg-end-date').val(event ? formatDate(event.regend_date) : '').keyup();
        }

        form.find('.btnHandleEventForm').addClass(event ? 'doSetEvent' : 'doAddEvent').removeClass(event ? 'doAddEvent' : 'doSetEvent');
        form.find('.modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button><h3 id="regEndedTitle">'+ (event ? "Редактировать мероприятие" : "Добавление мероприятия") + '</h3>');
    }

    $('.btnHandleEventForm').click(function(e){
        e.preventDefault();
        e.stopPropagation();

        var form = $(this).parents('#modalAddEditEvent');
        var eventId = form.attr('data-event-id');
        var teamKey = form.attr('data-team_key');

        var requestStr = $(this).hasClass('doSetEvent') ? '&eventId='+eventId + '&teamKey='+teamKey : '' ;

        var admins = form.find('.reg-members-added')[0]['children'], arrAdmins = [], arrAdminsEmail=[],
            zones = form.find('.zones-added')[0]['children'], arrZones = [];

        if(admins.length > 0){
            for (var a in admins){
                if(admins[a].dataset && admins[a].dataset['id'])
                    arrAdmins.push(admins[a].dataset['id']);
                if(admins[a].dataset && admins[a].dataset['email'])
                    arrAdminsEmail.push(admins[a].dataset['email']);
            }
        }

        if(zones.length > 0){
            for (var z in zones){
                if(zones[z].dataset && zones[z].dataset['id'])
                    arrZones.push(zones[z].dataset['field']+':'+zones[z].dataset['id']);
            }
        }

        var name = form.find('.event-name').val().trim();
        var locality = form.find('.event-locality').val();
        var startDate = form.find('.event-start-date').val();
        var endDate = form.find('.event-end-date').val();
        var regendDate = form.find('.event-reg-end-date').val();

        if(admins.length===0){
            showError('Необходимо добавить ответственных за регистрацию');
            return;
        }

        if(name === '' || startDate === '' || endDate === '' || regendDate === '' || locality === '_none_'){
            showError('Необходимо заполнить все поля выделенные розовым фоном');
            return;
        }

        $.post('/ajax/event.php?handle_event'+requestStr, {
            name : name,
            locality : locality,
            start_date: parseDate(startDate),
            end_date: parseDate(endDate),
            reg_end_date: parseDate(regendDate),
            passport: form.find('.event-passport').val() === '_none_' ? 0 : form.find('.event-passport').val(),
            prepayment: form.find('.event-prepayment').val() === '_none_' ? 0 : form.find('.event-prepayment').val(),
            private: form.find('.event-private').val() === '_none_' ? 0 : form.find('.event-private').val(),
            transport: form.find('.event-transport').val() === '_none_' ? 0 : form.find('.event-transport').val(),
            tp: form.find('.event-tp').val() === '_none_' ? 0 : form.find('.event-tp').val(),
            flight: form.find('.event-flight').val() === '_none_' ? 0 : form.find('.event-flight').val(),
            info: form.find('.event-info').val(),
            reg_members: arrAdmins.join(','),
            reg_members_email : arrAdminsEmail.join(','),
            event_type : form.find('.event-type').val(),
            zones: arrZones.join(','),
            parking: form.find('.event-parking').val() === '_none_' ? 0 : form.find('.event-parking').val(),
            service: form.find('.event-service').val() === '_none_' ? 0 : form.find('.event-service').val(),
            accom: form.find('.event-accom').val() === '_none_' ? 0 : form.find('.event-accom').val(),
            close_registration: form.find('.close_registration').val(),
            participants_count : form.find('.event-participants_count').val(),
            currency:form.find('.event-currency-modal').val(),
            contrib:form.find('.event-contrib-modal').val(),
            team_email:form.find('.event-email-modal').val(),
            organizer:form.find('.event-organizer-modal').val(),
            min_age:form.find('.event-min-age-modal').val(),
            max_age:form.find('.event-max-age-modal').val(),
            status:form.find('.event-status-modal').val()
        }).done(function(){
            loadEvents();
            $('#modalAddEditEvent').modal('hide');
        });
    });

    $('.event-info').focus(function(){
        var modal = $('#modalHandleEventInfo'), info = $(this).val();
        modal.find('#editor1').val(info);
        modal.find('.modal-header').html('<h4>Редактировать информацию о мероприятии</h4>');
        modal.modal('show');
    });

    $('.doSaveEventInfo').click(function(){
        var value = $('#modalHandleEventInfo #editor1').val();
        $("#modalAddEditEvent .event-info").val(value);
        $('#modalHandleEventInfo').modal('hide');
    });

    // Remove an event
    $('.doDeleteEvent').click(function(){
        var eventId = $(this).parents('#modalDeleteEvent').find('h4').attr('data-id');

        $.post('/ajax/event.php?remove_event', {eventId: eventId})
            .done(function(data){
                if(data.result === 'ok'){
                    loadEvents();
                }
                else{
                    showError(data.result);
                }
                $('#modalDeleteEvent').modal('hide');
            });
    });

    window.isGuest = true;

    /*
    <?php //if ($isInvited) { ?>
    var memberId = "<?php //echo $invitation[1]; ?>", eventId = "<?php //echo $invitation[0]; ?>";

    $.getJSON('/ajax/guest.php?invited', {member: memberId, event: eventId })
     .done (function(data){
        if(data.eventmember){
            window.currentEventId = eventId;
            window.currentEventName = data.eventmember.event_name;
            fillEditMember (memberId, data.eventmember);
            $("#modalEditMember").modal('show');
        }
     });
    <?php //} ?>

    <?php //if ($isLink) { ?>

    $.getJSON('/ajax/guest.php', { link: "<?php //echo $_GET['link']; ?>" })
     .done (function(data){
         window.currentEventId = data.eventmember.event_key;
         window.currentEventName = data.eventmember.event_name;
         fillEditMember (data.eventmember.member_key, data.eventmember);
         $("#modalEditMember").modal('show');
     });

    <?php //} ?>
    */


    $('a[href="#"]').click( function(e) {e.preventDefault();} );

    $('.emName ~ .unblock-input').click(function (){
        $('#modalNameEdit').modal('show');
    });

    $('.rejectEventRegistration').click(function(){
        $('#modalRejectMember').modal('hide');
        $.getJSON('/ajax/event.php', { eventIdReject: window.currentEventId})
        .done (function(){
            window.location = '/index';
        });
    });

    $("#btnDoRegisterGuest").click (function (){
        if ($(this).hasClass('disabled')){

            showError("Необходимо заполнить все обязательные поля, выделенные розовым фоном!", true);
            return;
        }
        if (!checkAgeLimit(".theActiveEvent","data-start_date", true)) {
          return;
        }
        var form = $('#modalEditMember'), self = this, fieldsValue = getValuesRegformFields(form, true, false);

        if(!fieldsValue.termsUse){
            showError("Необходимо дать согласие на обработку персональных данных", true);
            return;
        }

        $.post("/ajax/guest.php", fieldsValue)
        .done (function(data){
            form.addClass('hide').modal('hide');
            if($(self).hasClass('edit')){
                $('#btnDoRegisterGuest').removeClass('edit').removeClass('guest');
                $('#successSavedDataModal').modal('show');
                window.setTimeout(function() {
                  $('#successSavedDataModal').modal('hide');
                  if ($('#modalShowEventInfo').is(':visible')) {
                      $('#modalShowEventInfo').modal('hide');
                  }
                  loadEvents();
                }, 1300);
            }
            else{
                <?php if(!isset($memberId)){?>
                    showSuccessMessage (<?php echo $isLink ? "data.messages.save_message, null" : "data.messages.reg_message, data.permalink"; ?>);
                <?php }else{ ?>
                  setTimeout(function () {
                    window.location = '/index';
                  }, 200);
                <?php } ?>
            }
        });

    });


    $("#lnkEventInfo").click (function (){
        $.getJSON('/ajax/get.php', { event_info: window.currentEventId })
        .done (function(data) {
            $("#eventInfoTitle").text (data.event_name);
            $("#eventInfoText").html(data.event_info);
            $("#sendMsgText").val("");
            $('#modalEventInfo').modal('show');
        });
    });


    $('#btnRegDone').click(function () {
        <?php if (!$isLink) { ?>
            $(this).parents ('div.modal').modal('hide');
        <?php } else { ?>
        var locHost = location.host, host;
        host locHost.substr(4,3) !== 'dev'? 'http://reg-page.ru/' : 'http://www.dev.reg-page.ru/';
        window.location = host ;
        <?php } ?>
    });

    /*
    $('#lnkCancelReg').click (function (){
        if (confirm ("Вы уверены, что хотите отменить регистрацию?")){
            $.ajax({type: "POST", url: "/ajax/guest.php?cancel", data: {
                event: window.currentEventId,
                member: window.currentEditMemberId
            }})
            .done (function() {
                showSuccessMessage ("Ваша регистрация отменена.");
            });
        }
    });

    $('#lnkRestoreReg').click (function (){
        if (confirm ("Вы уверены, что хотите возобновить регистрацию?")){
            $.ajax({type: "POST", url: "/ajax/guest.php?restore", data: {
                event: window.currentEventId,
                member: window.currentEditMemberId
            }})
            .done (function(){
                showSuccessMessage ("Ваша регистрация возобновлена.");
            });
        }
    });
  */
    $('#btnCancelChanges').click (function (){
        $("#modalEditMember").addClass('hide').modal('hide');
    });


    $("#btnDoSendEventMsg").click (function (){
        if ($(this).hasClass('disabled')) return;
        $.ajax({type: "POST", url: "/ajax/set.php", data: { event: window.currentEventId, message: $("#sendMsgText").val(), name:$("#sendMsgName").val(), email:$("#sendMsgEmail").val()}})
        .done (function() {
            messageBox ('Ваше сообщение отправлено команде регистрации', $('#modalEventSendMsg'));
        });
    });

    /*
    $("#btnDoSendEventMsgAdmins").click (function (){
        if ($(this).hasClass('disabled')) return;
        var isGuest = '<?php echo $isGuest ?>';

        $.ajax({type: "POST", url: "/ajax/set.php", data: {guest: isGuest, event:"", message: $("#sendMsgTextAdmin").val(), name:$("#sendMsgNameAdmin").val(), email:$("#sendMsgEmailAdmin").val(), admins:"События index.php"}})
        .done (function() {messageBox ('Ваше сообщение отправлено службе поддержки', $('#messageAdmins'));
            $("#sendMsgTextAdmins").val('');
        });
    });
    */
    /* Romans Code 5.0.6*/
    function arriveDepartMyself(tagAttr, ClassDates) {
/* получаем данные и сбрасываем настройки виджета календаря для заданного заданого поля*/
      var getDateArriveOrDepart = tagAttr.split('-');
      getDateArriveOrDepart[1]--;
      $(ClassDates).datepicker('destroy');

      $(ClassDates).datepicker({
          language: 'ru',
          autoclose : true,
          defaultViewDate: {
              month: getDateArriveOrDepart[1]
            },
          format: {
              toDisplay: function (date) {
                  return formatDDMM(date);
              },
              toValue: function (date) {
                  var arrDate = date.split('.');
                  return new Date(parseDDMM(date, new Date ($('#modalEditMember').find("input[data-double_date$='"+(arrDate[1]+'-'+arrDate[0])+"']").attr('data-double_date'))));
              }
          }
      });
    }
    /* END Romans Code  */
});
var adminRole = '<?php echo db_getAdminRole($memberId); ?>';

</script>
<script src="/js/mainpage.js?v17"></script>

<?php
}

include_once "footer.php";
?>
</div>
