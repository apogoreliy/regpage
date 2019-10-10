<?php
include_once "header.php";
include_once "nav.php";
include_once "./modals.php";

$localities = db_getArchivedEventLocalities (); $eventTypes = db_getEventTypes();
//$isSingleCity = db_isSingleCityArchiveEvent();
$eventTemplates = db_getEventTemplates($memberId);

$sort_field = isset ($_SESSION['sort_field-archive']) ? $_SESSION['sort_field-archive'] : 'start_date';
$sort_type = isset ($_SESSION['sort_type-archive']) ? $_SESSION['sort_type-archive'] : 'desc';
$services = db_getServices();

?>

<style>body {padding-top: 60px;}</style>
<div class="container">
    <div id="eventTabs" class="events-list">
        <div class="tab-content">
            <div class="btn-toolbar">
                <a class="btn btn-success btn-add-event" type="button"><i class="icon-plus icon-white"></i> <span class="hide-name">Добавить</span></a>
                <a class="btn btn-event-members-statistic" href="#">
                    <i class="fa fa-bar-chart" title="Поименная статистика" aria-hidden="true"></i>
                </a>
                <a class="btn btn-event-general-statistic" href="#">
                    <i class="fa fa-area-chart" title="Общая статистика" aria-hidden="true"></i>
                </a>
                <div class="input-group input-daterange datepicker">
                    <input type="text" class="span2 archive-event-start-date" value="<?php echo date("d.m.Y", strtotime("-1 year")); ?>">
                    <i class="btn fa fa-calendar" aria-hidden="true"></i>
                    <input type="text" class="span2 archive-event-end-date" value="<?php echo date('d.m.Y'); ?>">
                </div>
                <select id="selectedEventType" class="span2">
                    <option value='_all_'>Все мероприятия</option>
                    <?php foreach ($eventTypes as $id => $name) {
                        echo "<option value='$id'>".htmlspecialchars ($name)."</option>";
                    } ?>
                </select>

                <?php if (!$isSingleCity) { ?>
                <select id="selectedEventLocality" class="span2">
                    <option value='_all_' >Все местности</option>
                    <?php
                        foreach ($localities as $id => $name) {echo "<option value='$id' >".htmlspecialchars ($name)."</option>"; }
                    ?>
                </select>
                <?php } ?>
            </div>
            <div class="desctopVisible">
                <table id="archiveEvents" class="table table-hover">
                    <thead>
                    <tr>
                        <th><a id="sort-main-start_date" href="#" title="сортировать">Дата</a>&nbsp;<i class="<?php echo $sort_field=='start_date' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        <th><a id="sort-main-name" href="#" title="сортировать">Название</a>&nbsp;<i class="<?php echo $sort_field=='name' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        <?php
                        if (!$isSingleCity)
                            echo '<th><a id="sort-main-locality_key" href="#" title="сортировать">Местность</a>&nbsp;<i class="'.($sort_field=='locality_key' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none').'"></i></th>';
                        ?>
                        <th><a id="sort-main-members_count" href="#" title="сортировать">Количество</a>&nbsp;<i class="<?php echo $sort_field=='members_count' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
                </table>
            </div>
            <div class="show-phone">
                <table id="archiveEventsPhone" class="table table-hover">
                    <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- EVENT Members Modal -->
<div id="modalEventMembers" data-width="900" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Список</h3>
    </div>
    <div class="modal-body">
        <div>
            <input class="span3 search-event-members" type="text" placeholder="Введите текст">
            <!--<select class="controls span3 filter-members-by-service">
                <option value='_all_'>Все служения</option>
                <?php
                    //foreach ($services as $key => $value) {
                    //    echo '<option value='.$key.'>'.$value.'</option>';
                    //}
                ?>
            </select>-->
            <!--<i class="btn fa fa-check fa-lg filter-members-coord" title="Выбрать координаторов" aria-hidden="true"></i>
            <i class="btn fa fa-wrench fa-lg filter-members-serving_ones" title="Выбрать служащих" aria-hidden="true"></i>-->
        </div>
        <table class="table table-hover table-condensed ">
            <thead>
                <tr>
                    <th><a id="sort-event-name" href="#" title="сортировать">ФИО</a>&nbsp;<i class="<?php echo $sort_event_field=='name' ? ($sort_event_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                    <th><a id="sort-event-locality" href="#" title="сортировать">Местность</a>&nbsp;<i class="<?php echo $sort_event_field=='locality' ? ($sort_event_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                    <th><a id="sort-event-age" href="#" title="сортировать">Возраст</a>&nbsp;<i class="<?php echo $sort_event_field=='age' ? ($sort_event_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                    <!--<th><a id="sort-event-coord" href="#" title="сортировать">Коорд.</a>&nbsp;<i class="<?php echo $sort_event_field=='coord' ? ($sort_event_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                    <th>Служение</th>-->
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <i class="fa fa-arrow-up fa-3x scroll-up" aria-hidden="true"></i>
    </div>
    <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Ok</button>
    </div>
</div>


<!-- EVENT ARCHIVE Modal -->
<div id="modalEventArchive" data-width="500" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Статистика</h3>
    </div>
    <div class="modal-body">

    </div>
    <div class="modal-footer">
        <button class="btn btn-success" data-dismiss="modal" aria-hidden="true">Ok</button>
    </div>
</div>

<!-- GENERAL EVENT MEMBERS STATISTIC Modal -->
<div id="generalEventMembersModal" data-width="900" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Поименная статистика</h3>
    </div>
    <div class="modal-body">
        <input type="text" class="controls span3 search-general-event-members" placeholder="Введите текст для поиска">
        <select id="eventTypeMembersStatistic" class="span3">
            <option value='_all_' >Все мероприятия</option>
            <?php foreach ($eventTypes as $id => $name) {
                echo "<option value='$id'>".htmlspecialchars ($name)."</option>";
            } ?>
        </select>
        <div class="input-group input-daterange datepicker">
            <input type="text" class="span2 start-date-statistic-members" value="<?php echo date("d.m.Y", strtotime("-1 year")); ?>">
            <i class="btn fa fa-calendar" aria-hidden="true"></i>
            <input type="text" class="span2 end-date-statistic-members" value="<?php echo date('d.m.Y'); ?>">
        </div>
        <i class="btn btn-meeting-download-members-statistic fa fa-download" title="Скачать общую статистику" aria-hidden="true"></i>
        <div style="margin-bottom: 10px;">
            <?php
                foreach ($eventTypes as $type => $name){
                    // echo '<span class="statistic-bar-form '.$type.'-meeting"></span><span>'.$name.'</span>';
                }
            ?>
        </div>
        <div id="general-statistic"></div>
        <table class="table table-hover table-condensed">
            <thead><tr><th style="width: 30%;">Фамилия Имя Отчество</th><th></th></tr></thead>
            <tbody></tbody>
        </table>
        <i class="fa fa-arrow-up fa-3x scroll-up" aria-hidden="true"></i>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Ok</button>
    </div>
</div>

<!-- GENERAL EVENT STATISTIC Modal -->
<div id="modalGeneralEventArchive" data-width="900" data-height="500" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Общая статистика</h3>
    </div>
    <div class="modal-body">
        <select id="eventTypeGeneralArchive" class="span2">
            <option value='_all_'>Все собрания</option>
            <?php foreach ($eventTypes as $id => $name) {
                echo "<option value='$id'>".htmlspecialchars ($name)."</option>";
            } ?>
        </select>
        <?php if (!$isSingleCity) { ?>
        <select id="localityGeneralArchive" class="span2">
            <option value='_all_'>Все местности</option>
            <?php
                foreach ($localities as $id => $name) {
                    echo "<option value='$id' >".htmlspecialchars ($name)."</option>";
                }
            ?>
        </select>
        <?php } ?>
        <div class="input-group input-daterange datepicker">
            <input type="text" class="span2 start-date-archive-general" value="<?php echo date("d.m.Y", strtotime("-1 months")); ?>">
            <i class="btn fa fa-calendar" aria-hidden="true"></i>
            <input type="text" class="span2 end-date-archive-general" value="<?php echo date('d.m.Y'); ?>">
        </div>
        <i class="btn btn-event-download-general-archive fa fa-download" title="Скачать архив" aria-hidden="true"></i>

        <div>
            <table class="table table-hover table-condensed general-event-archive-list">
                <thead><tr><th style="width: 30%;">Мероприятие</th><th></th></tr></thead>
                <tbody></tbody>
            </table>
        </div>
        <i class="fa fa-arrow-up fa-3x scroll-up" aria-hidden="true"></i>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Ok</button>
    </div>
</div>

<!-- MODAL CHOOSE STATISTIC EVENT OR TEMPLATE -->
<div id="modalAddEventTemplate" data-width="400" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Добавить мероприятие</h3>
    </div>
    <div class="modal-body">
        <button class="btn btn-default btn-event event">Мероприятие</button>
        <button style="float: right;" class="btn btn-default btn-event template">Шаблон мероприятия</button>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary btn-do-add-event" disabled>Добавить</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>

<!-- Registration Ended Message Modal -->
<div id="modalAddEditEvent" class="modal hide fade" tabindex="-1" role="dialog"  aria-labelledby="regEndedTitle" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3></h3>
    </div>
    <div class="modal-body">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#new-event" data-toggle="tab">Новое мероприятие</a></li>
            <li><a href="#event-templates" data-toggle="tab">Шаблоны</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="new-event">
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
                    <select class="span12 event-type" valid="required">
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
                    <select class="span12 event-locality" valid="required">
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
                <label class="span12">Нужно отмечать присутствующих?</label>
                <div class="control-group row-fluid">
                    <select class="span12 event-attendance">
                        <option value='0' selected>НЕТ</option>
                        <option value='1'>ДА</option>
                    </select>
                </div>
            </div>
            <div class="block-count-meetings">
                <div class="control-group row-fluid">
                    <label class="span12">Количество собраний<sup>*</sup></label>
                    <div class="control-group row-fluid">
                        <input class="form-control span12 event-count-meetings" type="text" valid="required">
                    </div>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12">Нужна регистрация?<sup>*</sup></label>
                <div class="control-group row-fluid">
                    <select class="span12 event-registration" valid="required">
                        <option value='0' selected>НЕТ</option>
                        <option value='1'>ДА</option>
                    </select>
                </div>
            </div>
            <div class="block-registration">
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
                    <label class="span12">Закрытое мероприятие?</label>
                    <div class="control-group row-fluid">
                        <select class="span12 event-private">
                            <option value='0' selected>НЕТ</option>
                            <option value='1'>ДА</option>
                        </select>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <label class="span12">Нужны паспортные данные?</label>
                    <div class="control-group row-fluid">
                        <select class="span12 event-passport">
                            <option value='0' selected>НЕТ</option>
                            <option value='1'>ДА</option>
                        </select>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <label class="span12">Нужна предварительная оплата?</label>
                    <div class="control-group row-fluid">
                        <select class="span12 event-prepayment">
                            <option value='0' selected>НЕТ</option>
                            <option value='1'>ДА</option>
                        </select>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <label class="span12">Нужна инфромация о транспорте?</label>
                    <div class="control-group row-fluid">
                        <select class="span12 event-transport">
                            <option value='0' selected>НЕТ</option>
                            <option value='1'>ДА</option>
                        </select>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <label class="span12">Нужны данные загранпаспорта?</label>
                    <div class="control-group row-fluid">
                        <select class="span12 event-tp">
                            <option value='0' selected>НЕТ</option>
                            <option value='1'>ДА</option>
                        </select>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <label class="span12">Нужна инфромация об авиарейсе?</label>
                    <div class="control-group row-fluid">
                        <select class="span12 event-flight">
                            <option value='0' selected>НЕТ</option>
                            <option value='1'>ДА</option>
                        </select>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <label class="span12">Нужна инфромация о размещении?</label>
                    <div class="control-group row-fluid">
                        <select class="span12 event-accom">
                            <option value='0' selected>НЕТ</option>
                            <option value='1'>ДА</option>
                        </select>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <label class="span12">Нужна инфромация о парковке?</label>
                    <div class="control-group row-fluid">
                        <select class="span12 event-parking">
                            <option value='0' selected>НЕТ</option>
                            <option value='1'>ДА</option>
                        </select>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <label class="span12">Нужна инфромация о служении?</label>
                    <div class="control-group row-fluid">
                        <select class="span12 event-service">
                            <option value='0' selected>НЕТ</option>
                            <option value='1'>ДА</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="block-participants">
                <div class="control-group row-fluid">
                    <label class="span12">Участники</label>
                    <div class="control-group row-fluid participants-checkbox-block">
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
                        <div class="btn-group">
                            <label class="">Люди</label>
                            <input type="checkbox" data-field="m" >
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="participants-added"></div>
                        <input type="text" class="span12 search-participants" placeholder="Введите страну, область или местность">
                        <div class="participants-available"></div>
                    </div>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12">Информация о мероприятии</label>
                <div class="control-group row-fluid">
                    <textarea class="span12 event-info" cols="5"></textarea>
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12">Зона доступа</label>
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
                <label class="span12">Ответственные за регистрацию</label>
                <div class="control-group row-fluid">
                    <div class="reg-members-added"></div>
                    <input type="text" class="span12 search-reg-member" placeholder="Введите текст">
                    <div class="reg-members-available"></div>
                </div>
            </div>
            <div class="tab-pane" id="event-templates">
                <?php
                    if(count($eventTemplates) > 0 ){
                        foreach ($array as $key => $value) {
                            echo '<div>'.$value.'</div>';
                        }
                    }
                    else{
                        echo 'У вас пока нет готовых шаблонов';
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary btnHandleEventForm">Сохранить</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>

<script>
$(document).ready(function(){

    $(".event-registration").change(function(){
        if($(this).val() === "0"){
            $(".block-registration").hide();
            $(".block-participants").show() ;
        }
        else{
            $(".block-participants").hide() ;
            $(".block-registration").show() ;
        }
    });

    $(".event-attendance").change(function(){
        $(this).val() === "0" ? $(".block-count-meetings").hide() : $(".block-count-meetings").show() ;
    });

    function fillEventForm(event){
        var form = $('#modalAddEditEvent'),
            element = $("#modalAddEventTemplate").find('.modal-body .btn-event.btn-success');

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
        }

        // author
        form.find('.event-name').val(event ? event.event_name : '').keyup();
        form.find('.event-type').val(event ? event.event_type : '_none_').change();
        form.find('.event-locality').val(event ? event.locality_key : '_none_').change();
        form.find('.event-start-date').val(event ? formatDate(event.start_date) : '').keyup();
        form.find('.event-end-date').val(event ? formatDate(event.end_date) : '').keyup();
        form.find('.event-reg-end-date').val(event ? formatDate(event.regend_date) : '').keyup();
        form.find('.event-registration').val(event ? event.need_registration : 0).change();
        form.find('.event-attendance').val(event ? event.attendance : 0).change();
        form.find('.event-count-meetings').val(event ? event.count_meetings : 1).keyup();
        form.find('.event-passport').val(event ? event.need_passport : 0);
        form.find('.event-prepayment').val(event ? event.need_prepayment : 0);
        form.find('.event-private').val(event ? event.private : 0);
        form.find('.event-transport').val(event ? event.need_transport : 0);
        form.find('.event-tp').val(event ? event.need_tp : 0);
        form.find('.event-flight').val(event ? event.need_flight : 0);
        form.find('.event-info').val(event ? event.info : '');
        form.find('.reg-members-added').html(event ? handleAdminsList(arrAdmins, true) : '');
        form.find('.search-reg-member').val('');
        form.find('.zones-added').html(event ? handleEventZones(arrZones, true) : '');
        form.find('.search-zones').val('');
        form.find('.reg-members-available').html('');

        form.find('.btnHandleEventForm').addClass(event ? 'set' : 'add').removeClass(event ? 'add' : 'set');

        if(element.length > 0){
            if(element.hasClass('event')){
                form.find('.btnHandleEventForm').addClass('event').removeClass('template');
                form.find('.modal-header h3').html((event ? 'Редактирование' : 'Создание' ) + ' мероприятия');
            }
            else{
                form.find('.btnHandleEventForm').addClass('template').removeClass('event');
                form.find('.modal-header h3').html((event ? 'Редактирование' : 'Создание' ) + ' шаблона мероприятия');
            }
        }

        form.modal('show');
    }

    loadArchiveEvents();

    $(".btn-add-event").click(function(){
        $("#modalAddEventTemplate").modal('show');
    });

    $(".btn-event").click(function(){
        $(this).addClass("btn-success").siblings().removeClass("btn-success");
        $(".btn-do-add-event").attr('disabled', false);
    });

    $(".btn-do-add-event").click(function(){
        fillEventForm();
    });

    $('.btnHandleEventForm').click(function(e){
        e.preventDefault();
        e.stopPropagation();

        if($(this).hasClass('event')){
            handleEventCreation();
        }
        else if($(this).hasClass('template')){
            handleTemplateCreation();
        }
    });

    function handleEventCreation(){
        var fields = getEventFieldsValue();

        if(fields.name === '' || fields.eventType === '_none_' || fields.eventLocality === '_none_' || fields.eventEndDate === '' || fields.eventStartDate === ''){
            showError("Необходимо заполнить все поля выделенные розовым цветом.");
            return;
        }

        if(field.attendance === '1' && (typeof fields.countMeetings == 'string' || fields.countMeetings <= 0 )){
            showError("Количество собраний должно быть больше 0");
            return;
        }

        $("#modalAddEditEvent").modal('hide');

        $.post("/ajax/archive.php?add_event", fields ).done(function(data){
            refreshArchiveEvents(data.events);
        });
    }

    function handleTemplateCreation(){

    }

    function getEventFieldsValue(){
        var modalWindow = $("#modalAddEditEvent"), participants = [], arrZones = [], eventAdmins = [], eventAdminsEmail = [];

            modalWindow.find(".participants-added > div").each(function(){
                participants.push({field: $(this).attr('data-field'), id : $(this).attr('data-id')});
            });

            var zones = modalWindow.find('.zones-added')[0]['children'],

            if(zones.length > 0){
                for (var z in zones){
                    if(zones[z].dataset && zones[z].dataset['id'])
                        arrZones.push(zones[z].dataset['field']+':'+zones[z].dataset['id']);
                }
            }

            modalWindow.find(".reg-members-added > div").each(function(){
                eventAdmins.push($(this).attr('data-id'));
                eventAdminsEmail.push($(this).attr('data-email'));
            });

            return({
                mode : true ? 'add' : 'edit';
                startDate: parseDate($(".archive-event-start-date").val()),
                endDate : parseDate($(".archive-event-end-date").val()),
                name : modalWindow.find(".event-name").keyup().val().trim(),
                eventType : modalWindow.find(".event-type").change().val().trim(),
                eventLocality : modalWindow.find(".event-locality").change().val(),
                eventStartDate : parseDate(modalWindow.find(".event-start-date").keyup().val().trim()),
                eventEndDate : parseDate(modalWindow.find(".event-end-date").keyup().val().trim()),
                eventInfo : modalWindow.find(".event-info").val().trim(),
                participants : JSON.stringify(participants),
                zones : arrZones.join(','),
                eventAdmins : eventAdmins.join(','),
                eventAdminsEmail : eventAdminsEmail.join(','),

                registration : modalWindow.find(".event-registration").val(),
                attendance   : modalWindow.find(".event-attendance").val(),
                countMeetings : parseInt(modalWindow.find(".event-count-meetings").val()),

                regEndDate: parseDate(modalWindow.find('.event-reg-end-date').val()),
                passport:  modalWindow.find('.event-passport').val(),
                prepayment: modalWindow.find('.event-prepayment').val(),
                private: modalWindow.find('.event-private').val(),
                transport: modalWindow.find('.event-transport').val(),
                tp: modalWindow.find('.event-tp').val(),
                flight: modalWindow.find('.event-flight').val(),
                parking: modalWindow.find('.event-parking').val(),
                service: modalWindow.find('.event-service').val(),
                accom: modalWindow.find('.event-accom').val()
            });
    }

    $("#generalEventMembersModal .modal-body, #modalEventArchive .modal-body, #modalEventMembers .modal-body").scroll(function(){
        handleScrollUpBtn($(this));
    });

    $("#generalEventMembersModal .scroll-up, #modalEventArchive .scroll-up, #modalEventMembers .scroll-up").click(function(e){
        e.stopPropagation();
        $(this).parents('.modal-body').animate({
            scrollTop: 0
        }, 500);
    });

    function handleScrollUpBtn(form){
        var height = form.find('tbody').height();
        var scrollTop = form.scrollTop();
        height>600 && scrollTop>300 ? form.find(".scroll-up").show() : form.find(" .scroll-up").hide();
    }

    function loadArchiveEvents(){
        var request = getRequestFromFilters(setFiltersForRequest());

        $.getJSON('/ajax/archive.php?get_events'+request).done(function(data){
            refreshArchiveEvents(data.events);
        });
    }

    $("a[id|='sort-event']").click(function(){
        var icon = $(this).siblings("i"), id = $(this).attr("id");;
        $("#modalEventMembers a[id|='sort-event'][id!='"+id+"'] ~ i").attr("class","icon-none");
        icon.attr("class", (icon.hasClass("icon-chevron-down") ? "icon-chevron-up" : "icon-chevron-down"));
        sortEventMembers ();
    });

    $("a[id|='sort-main']").click(function(){
        var icon = $(this).siblings("i"), id = $(this).attr("id");;
        $("#archiveEvents a[id|='sort-main'][id!='"+id+"'] ~ i").attr("class","icon-none");
        icon.attr("class", (icon.hasClass("icon-chevron-down") ? "icon-chevron-up" : "icon-chevron-down"));
        sortEventMain ();
    });

    $('.btn-event-members-statistic').click(function(){
        getMembersArchive();
    });

    function drawChart(information){
        var tableRows = [],
            showEventType = $("#meetingTypeGeneralStatistic").val() === '_all_',
            showLocalityName = $("#localityGeneralStatistic").val() === '_all_';

        information.forEach(function(item){
            var arr = [], countInfo = 0, eventStartDate, eventCountMembers;

                countInfo = item.members_count.split(',');
                for(var j in countInfo){
                    eventStartDate = countInfo[j].split(':')[1];
                    eventCountMembers = countInfo[j].split(':')[0];

                    arr.push('<div style="display: inline-block; text-align: center;"><span style="background-color:'+getEventMemberColor(item['event_type'])+'; display: block; height: 20px" class="archive-bar" title="'+eventStartDate+'"></span>'+
                            '<span style="font-size:12px;">'+eventCountMembers+'</span></div>');
                }

            tableRows.push(
                '<tr>'+
                '<td class="statistic-part" style="width:35%"><strong>'+he(item.name)+'</strong></td><td>'+arr.join('')+'</td>/tr>'
            );
        });

        $('.general-event-archive-list').html(tableRows.join(''));
    }

    function getGeneralEventArchive(toDownload) {
        var locality = $("#localityGeneralArchive").val();
        var event = $("#eventTypeGeneralArchive").val();
        var startDate = $(".start-date-statistic-general").val();
        var endDate = $(".end-date-statistic-general").val();

        $.getJSON('/ajax/archive.php?get_general_archive', {locality : locality, event : event, startDate : parseDate(startDate), endDate : parseDate(endDate)})
        .done(function(data){
            toDownload ? downloadGeneralArchive(data.list) : drawChart(data.list);
        });
    }

    function downloadGeneralArchive(list){
        if(list.length>0){
            $.ajax({
                type: "POST",
                url: "/ajax/excelList.php",
                data: "page=event_general"+"&list="+JSON.stringify(list)+"&adminId="+window.adminId,
                cache: false,
                success: function(data) {
                    document.location.href="./ajax/excelList.php?file="+data;
                    setTimeout(function(){
                        deleteFile(data);
                    }, 10000);
                }
            });
        }
        else{
            showError("Список пустой. Нет данных для скачивания");
        }
    }

    $("#modalGeneralEventArchive").on('shown', function(){
        getGeneralEventArchive();
    });

    $("#meetingTypeGeneralStatistic, #localityGeneralStatistic, .start-date-statistic-general, .end-date-statistic-general").change (function (){
        getGeneralEventArchive();
    });

    $(".btn-event-general-statistic").click(function(){
        $("#modalGeneralEventArchive").modal('show');
    });

    $('.btn-event-download-general-archive').click(function(){
        getGeneralEventArchive(true);
    });

    $("#eventTypeMembersStatistic, #localityEventMemberStatistic, .start-date-statistic-members, .end-date-statistic-members").change(function(){
        getMembersArchive();
    });

    $(".search-general-event-members").keyup(function(){
        filterGeneralEventMembersList();
    });

    function filterGeneralEventMembersList(){
        var searchText = $(".search-event-members").val().trim().toLowerCase(),
            membersLocality, memberId, membersArr = [], isListPassed=false, membersName;
        /*showCoordOnes = $(".filter-members-coord").hasClass('active'),
            showServingOnes = $(".filter-members-serving_ones").hasClass('active'),
            selectedService = $(".filter-members-by-service").val(),
            membersCoord, membersService, membersServiceTempArr, */


        $("#generalEventMembersModal tbody tr").each(function(){
            memberId = $(this).attr('data-id');
            membersName = $(this).find('td').first().text().trim().toLowerCase();
            membersLocality = $(this).find('td:nth-child(2)').text().trim().toLowerCase();

            /*
            if(membersService !== '0' && selectedService !== '_all_' && !isListPassed){
                membersServiceTempArr = membersService.split(',');

                for(var i in membersServiceTempArr){
                    if(membersServiceTempArr[i].split(':')[0] === selectedService){
                        membersArr.push(membersServiceTempArr[i].split(':')[1]);
                    }
                }

                isListPassed = true;
            }
            */

            /*(!showCoordOnes || (showCoordOnes && membersCoord === '1')) && (!showServingOnes || (showServingOnes && membersService !== '0')) && (selectedService === '_all_' || (selectedService !== '_all_' && in_array(memberId, membersArr))) && */
            (searchText === '' || (searchText !== '' && (membersName.search(searchText) !== -1 || membersLocality.search(searchText) !== -1)))  ? $(this).show() : $(this).hide();
        });
    }

    function getMembersArchive(){
        var eventType = $("#eventTypeMembersStatistic").val(),
            startDate = $(".start-date-statistic-members").val(),
            endDate = $(".end-date-statistic-members").val(),
            text = $(".search-general-event-members").val().trim();

        $.getJSON('/ajax/archive.php?get_members_statistic', {text: text, event_type: eventType, startDate:parseDate(startDate), endDate : parseDate(endDate)})
        .done(function(data){
            buildMembersArchiveList(data.list);
            $("#generalEventMembersModal").modal('show');
            $("#generalEventMembersModal .scroll-up, #generalEventMembersModal .scroll-up").hide();
        });
    }

    function buildMembersArchiveList(list){
        var tableRows = [], isLocalitySelected = $("#localityEventMemberStatistic").val() !== '_all_';

        for (var i in list) {
            var m = list[i], arr = [], event = m.event.split(','), eventArr=[];

            for(var j in event){
                eventArr = event[j].split(':');
                arr.push('<span style="background-color:'+getEventMemberColor(eventArr[0])+'" class="statistic-bar '+(eventArr[0]+'-meeting')+'" data-type="'+eventArr[0]+'" title="'+(eventArr[1])+'"></span>');
            }

            tableRows.push(
                '<tr data-id="'+m.member_key+'" data-locality_name="'+he(m.locality_name)+'" data-locality="'+(m.locality_key)+'" >'+
                '<td class="statistic-part"><span>'+he(m.name)+'</span>' + (isLocalitySelected ? '' : ' <span>('+he(m.locality_name)+')</span>')+'</td><td class="statistic-date-part" style="padding-top: 10px;">'+arr.join('')+'</td>/tr>'
            );
        }

        //$("#modalMeetingStatistic .btn-meeting-download-members-statistic").attr('data-list_count', parseInt(membersListCount) || '' ).attr('data-members_count', members_count || '');
        //$("#modalMeetingStatistic #general-statistic").html('По списку — '+(parseInt(membersListCount) || '')+' чел. Участвовали в собраниях — '+members_count+' чел.');
        $("#generalEventMembersModal tbody").html(tableRows.join(''));
    }

    function sortEventMain(){
        var sort_type, sort_field, name, start_date, id, event_type, locality_key, locality_name, members_count, eventsArr = [];

        $("#archiveEvents").find(" a[id|='sort-main']").each (function () {
            if ($(this).siblings("i.icon-chevron-down").length) {
                sort_type = 'asc';
                sort_field = $(this).attr("id").replace(/^sort-main-/,'');
            }
            else if ($(this).siblings("i.icon-chevron-up").length) {
                sort_type = 'desc';
                sort_field = $(this).attr("id").replace(/^sort-main-/,'');
            }
        });

        $("#archiveEvents tbody tr").each(function(){
            start_date = $(this).attr('data-date');
            id = $(this).attr('data-id');
            locality_key = $(this).attr('data-locality');
            event_type = $(this).attr('data-type');
            name = $(this).find('td:nth-child(2)').text();
            locality_name = $(this).find('td:nth-child(3)').text();
            members_count = $(this).find('td:nth-child(4)').text();

            eventsArr.push({
                name : name,
                event_type : event_type,
                start_date : parseDate(start_date),
                id: id,
                locality_key : locality_key,
                locality_name : locality_name,
                members_count : members_count
            });
        });

        eventsArr.sort(function(a, b){
            if (a[sort_field] > b[sort_field]) {
                return sort_type === 'desc' ? -1 : 1;
            }
            else if (a[sort_field] < b[sort_field]) {
              return sort_type === 'desc' ? 1 : -1;
            }
            return 0;
        });

        refreshArchiveEvents(eventsArr);
    }

    function sortEventMembers(){
        var sort_type, sort_field, name, age, id, locality, membersArr = [];

        $("#modalEventMembers").find(" a[id|='sort-event']").each (function (i) {
            if ($(this).siblings("i.icon-chevron-down").length) {
                sort_type = 'asc';
                sort_field = $(this).attr("id").replace(/^sort-event-/,'');
            }
            else if ($(this).siblings("i.icon-chevron-up").length) {
                sort_type = 'desc';
                sort_field = $(this).attr("id").replace(/^sort-event-/,'');
            }
        });

        $("#modalEventMembers tbody tr").each(function(){
            name = $(this).find('td').first().text();
            age = $(this).attr('data-age');
            //coord = $(this).attr('data-coord');
            id = $(this).attr('data-id');
            locality = $(this).attr('data-locality');
            //service = $(this).attr('data-service');

            membersArr.push({
                name : name,
                age:isNaN(age) ? null : age,
                //coord: coord,
                id: id,
                locality : locality
                //service : service
            });
        });

        membersArr.sort(function(a, b){
            if (a[sort_field] > b[sort_field]) {
                return sort_type === 'desc' ? -1 : 1;
            }
            else if (a[sort_field] < b[sort_field]) {
              return sort_type === 'desc' ? 1 : -1;
            }
            return 0;
        });

        buildEventMembersList(membersArr);
    }

    function refreshArchiveEvents(events){
        var tableRows = [], phoneRows = [];
        var isSingleCity = parseInt('<?php echo $isSingleCity; ?>');

        for (var i in events){
            var e = events[i],
                dataString = ' class="archive-event-row '+(parseInt(e.summary) ? 'meeting-summary' : '')+' " data-id="'+(e.id)+'"'+
                ' data-locality="'+e.locality_key+'" data-type="'+e.event_type+'" data-date="'+formatDate(e.start_date)+'"';

            tableRows.push('<tr '+dataString +'>'+
                '<td>' + formatDate(e.start_date) + '</td>' +
                '<td>' + he(e.name || '') + '</td>' +
                (isSingleCity ? '' : '<td>' + he(e.locality_name ? (e.locality_name.length>20 ? e.locality_name.substring(0,18)+'...' : e.locality_name) : '') + '</td>') +
                '<td>' + (e.members_count || '') + '</td>' +
                '<td><i class="fa fa-list fa-lg archive-event-list" title="Список"/></td>' +
                '</tr>'
            );

            phoneRows.push('<tr '+dataString+'>'+
                '<td><span>' + he(e.name) + '</span>'+
                '<i style="float: right;" class="fa fa-list fa-lg archive-event-list" title="Список"/>'+
                '<div><strong>' + he(e.meeting_name) + '</strong></div>' +
                (isSingleCity ? '' : '<div>'+ he(e.locality_name ? (e.locality_name.length>20 ? e.locality_name.substring(0,18)+'...' : e.locality_name) : '') + '</div>') +
                '<div>'+formatDate(e.start_date)+'</div>'+
                '<div>Всего: '+ (e.members_count || '') +'</div>'+
                '</td>' +
                '</tr>'
            );
        }

        $(".desctopVisible tbody").html (tableRows.join(''));
        $(".show-phone tbody").html (phoneRows.join(''));

        $(".archive-event-row, .archive-event-list").unbind('click');
        $(".archive-event-row, .archive-event-list").click (function (e) {
            e.stopPropagation();
            var that = this,
                eventId = $(this).hasClass('archive-event-row') ? $(this).attr('data-id') : $(this).parents('tr').attr('data-id');

            $.post('/ajax/archive.php?get_members_list', {event_id: eventId})
            .done(function(data){
                if($(that).hasClass('archive-event-row')){
                    buildEventStatistic(data.list);
                }
                else{
                    window.services = data.services;
                    buildEventMembersList(data.list);
                }
            });
        });

        filterArchivedEventsList();
    }

    function buildEventStatistic(list){
        var info = '', childrenCount=0, studentsCount =0, adultsCount = 0, meedleAgeCount=0, oldCount = 0, localities = [];

        for (var i in list) {
            var m = list[i], age = parseInt(m.age);
            if(!in_array(m.locality, localities)){
                localities.push(m.locality);
            }

            if(age < 18){
                childrenCount++;
            }
            else if(age >=18 && age <=23){
                studentsCount++;
            }
            else if(age > 23 && age <= 40){
                adultsCount++;
            }
            else if(age > 40 && age <= 60){
                meedleAgeCount++;
            }
            else{
                oldCount++;
            }
        }

        info = '<div class="eventArchiveInfo" >'+
                    '<div><span>Количество местностей: <strong>'+localities.length+'</strong></span></div>'+
                    '<div><span>Общее количество участников: <strong>'+list.length+'</strong></span></div>'+
                    (childrenCount > 0 ? '<div><span>В возрасте младше 18 лет: <strong>'+childrenCount+'</strong></span></div>' : '' )+
                    (studentsCount > 0 ? '<div><span>В возрасте от 18 до 23 лет: <strong>'+studentsCount+'</strong></span></div>' : '' )+
                    (adultsCount > 0 ? '<div><span>В возрасте от 24 до 40 лет: <strong>'+adultsCount+'</strong></span></div>' : '') +
                    (meedleAgeCount > 0 ? '<div><span>В возрасте от 41 до 60 лет: <strong>'+meedleAgeCount+'</strong></span></div>' : '') +
                    (oldCount > 0 ? '<div><span>В возрасте старше 60 лет: <strong>'+oldCount+'</strong></span></div>' : '') +
                '</div>';

        $("#modalEventArchive .modal-body").html(info);
        $("#modalEventArchive").modal('show');
        $("#modalEventArchive .scroll-up").hide();
    }

    function getRequestFromFilters(arr){
        var str = '';
        arr.map(function(item){
            str += ('&'+item["name"] +'='+item["value"]);
        });
        return str;
    }

    function setFiltersForRequest(){
        var sort_type = 'desc',
            sort_field = 'start_date';

        $('#eventTabs').find(" a[id|='sort']").each (function (i) {
            if ($(this).siblings("i.icon-chevron-down").length) {
                sort_type = 'asc';
                sort_field = $(this).attr("id").replace(/^sort-main-/,'');
            }
            else if ($(this).siblings("i.icon-chevron-up").length) {
                sort_type = 'desc';
                sort_field = $(this).attr("id").replace(/^sort-main-/,'');
            }
        });

        var startDate = $(".start-date").val();
        var endDate = $(".end-date").val();

        var filters = [];
        filters = [{name: "sort_field", value: sort_field},
                    {name: "sort_type", value: sort_type},
                    {name: "startDate", value: parseDate(startDate)},
                    {name: "endDate", value: parseDate(endDate)}];

        return filters;
    }

    $('.archive-event-start-date, .archive-event-end-date').change(function(){
        loadArchiveEvents();
    });

    $("#selectedEventType, #selectedEventLocality").change(function(){
        filterArchivedEventsList();
    });

    function filterArchivedEventsList(){
        var locality = $("#selectedEventLocality").val(),
            eventType = $("#selectedEventType").val(),
            startDate = $(".archive-event-start-date").val(),
            endDate = $(".archive-event-end-date").val(),
            startDateMilliseconds = (new Date(parseDate(startDate))).getTime(),
            endDateMilliseconds = (new Date(parseDate(endDate))).getTime(),
            currentEventDateMilliseconds;

        $(".events-list tbody tr").each(function(){
            currentEventDateMilliseconds = (new Date(parseDate($(this).attr('data-date')))).getTime();
            ($(this).attr('data-locality') === locality || locality === '_all_' || locality === undefined) && ($(this).attr('data-type') === eventType || eventType === '_all_') && (startDateMilliseconds <= currentEventDateMilliseconds && currentEventDateMilliseconds <=endDateMilliseconds) ? $(this).show() : $(this).hide();
        });
    }

    function buildEventMembersList(list){
        var tableRows = [];

        for (var i in list) {
            var m = list[i],
                /*isServingOne = false, serviceName = '', */
                age = getAgeWithSuffix(parseInt(m.age), m.age);
            /*
            if(m.service !== '0'){
                var servingOnesItems = m.service.split(','), serviceKey;
                for (var j in servingOnesItems){
                    if(servingOnesItems[j].split(':')[1] === m.id){
                        serviceKey = servingOnesItems[j].split(':')[0];
                    }
                }
                serviceName = window.services[serviceKey];
                isServingOne = true;
            }*/

            // data-service="'+m.service+'" data-coord="'+m.coord+'"
            tableRows.push('<tr class="" data-id="'+m.id+'" data-locality="'+m.locality+'" data-age="'+parseInt(m.age)+'" >'+
                    '<td>'+he(m.name)+'</td>'+
                    '<td>'+he(m.locality)+'</td>'+
                    '<td>'+(age)+'</td>'+
                    //'<td>'+(m.coord === '1' ? '<span class="fa fa-check"></span>' : '')+'</td>'+
                    //'<td>'+(isServingOne ? he(serviceName) : '')+'</td>'+
                    '</tr>');
        }

        $("#modalEventMembers tbody").html(tableRows.join(''));
        $("#modalEventMembers").modal('show');
        $("#modalEventMembers .scroll-up").hide();
        filterEventMembersList();
    }

    /*
    $(".filter-members-coord, .filter-members-serving_ones").click(function(){
        $(this).hasClass('active') ? $(this).removeClass('active') : $(this).addClass('active');
        filterEventMembersList();
    });

    $(".filter-members-coord").click(function(){
        filterEventMembersList();
    });

    $(".filter-members-by-service").change(function(){
        filterEventMembersList();
    });
    */

    $(".search-event-members").keyup(function(){
        filterEventMembersList();
    });

    function filterEventMembersList(){
        var searchText = $(".search-event-members").val().trim().toLowerCase(),
            membersLocality,
            memberId, membersArr = [], isListPassed=false, membersName;

            //showCoordOnes = $(".filter-members-coord").hasClass('active'),
            //showServingOnes = $(".filter-members-serving_ones").hasClass('active'),
            //selectedService = $(".filter-members-by-service").val(),
            //membersCoord, membersService, membersServiceTempArr

        $("#modalEventMembers tbody tr").each(function(){
            //membersCoord = $(this).attr('data-coord');
            //membersService = $(this).attr('data-service');
            memberId = $(this).attr('data-id');
            membersName = $(this).find('td').first().text().trim().toLowerCase();
            membersLocality = $(this).find('td:nth-child(2)').text().trim().toLowerCase();

            /*
            if(membersService !== '0' && selectedService !== '_all_' && !isListPassed){
                membersServiceTempArr = membersService.split(',');

                for(var i in membersServiceTempArr){
                    if(membersServiceTempArr[i].split(':')[0] === selectedService){
                        membersArr.push(membersServiceTempArr[i].split(':')[1]);
                    }
                }

                isListPassed = true;
            }
            */

            /*
            (!showCoordOnes || (showCoordOnes && membersCoord === '1')) && (!showServingOnes || (showServingOnes && membersService !== '0')) && (selectedService === '_all_' || (selectedService !== '_all_' && in_array(memberId, membersArr))) &&
            */
             (searchText === '' || (searchText !== '' && (membersName.search(searchText) !== -1 || membersLocality.search(searchText) !== -1)))  ? $(this).show() : $(this).hide();
        });
    }
    /*
    $("#btnDoSendEventMsgAdmins").click (function (){
        if ($(this).hasClass('disabled')) return;

        $.ajax({type: "POST", url: "/ajax/set.php", data: {event:"", message: $("#sendMsgTextAdmin").val(), name:$("#sendMsgNameAdmin").val(), email:$("#sendMsgEmailAdmin").val(), admins:"Статистика"}})
        .done (function() {messageBox ('Ваше сообщение отправлено службе поддержки', $('#messageAdmins'));
            $("#sendMsgTextAdmins").val('');
        });
    });
    */
});

</script>
<?php
    include_once './footer.php';
?>
