<?php

include_once "preheader.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-144838221-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-144838221-1');
</script>
    <meta charset="utf-8">
    <title>Страница регистрации</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="Expires" content="Wed, 06 Sep 2017 16:35:12 GMT">
    <meta http-equiv="Pragma" content="no-cache">
    <!--<meta name="yandex-verification" content="56dd8d3e07b017d1" />-->
    <meta name="mailru-domain" content="z83V20hFDKLekMbc" />

    <link href="favicon.ico" rel="shortcut icon" />

    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet" />
    <link href="css/bootstrap-modal.css" rel="stylesheet" />
    <link href="css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <link href="css/style.css?v68" rel="stylesheet" />

    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="js/bootstrap-modalmanager.js" type="text/javascript"></script>
    <script src="js/bootstrap-modal.js" type="text/javascript"></script>
    <script src="js/jquery.mockjax.js" type="text/javascript"></script>
    <script src="js/jquery.autocomplete.js" type="text/javascript"></script> <!-- https://github.com/devbridge/jQuery-Autocomplete -->
    <script src="js/jquery.maskedinput.min.js" type="text/javascript"></script>
    <script src="js/script.js?v165" type="text/javascript"></script>
    <script src="js/jquery-textrange.js" type="text/javascript"></script>
    <script src="js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="js/bootstrap-datepicker.ru.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="https://cdn.envybox.io/widget/cbk.css">
</head>

<body style="padding-top: 10px; padding-bottom: 10px; padding-left: 0px; padding-right: 0px;">
    <script>window.adminId=<?php echo isset ($memberId) ? "'$memberId'" : 'null'; ?>;</script>
    <div id="globalError" class="alert alert-error above-modal" style="display: none"><a class="close close-alert" href="#">&times;</a><span>AJAX ERROR</span></div>
    <div id="globalHint" class="alert alert-success above-modal" style="display: none"><a class="close close-alert" href="#">&times;</a><span>GLOBAL HINT</span></div>
    <div id="ajaxLoading" style="display: none"><img src="img/ajax_loader.gif"></div>
    <div><i class="fa fa-arrow-up fa-3x scroll-up" aria-hidden="true"></i></div>

<?php
include_once "./modals.php";
$s = $_SERVER["SCRIPT_NAME"];
$isEventAdminNav = isset($memberId) ? db_hasRightToHandleEvents($memberId) : false;
$h = ($_SERVER['PHP_SELF']);
$localities = db_getArchivedEventLocalities (); $eventTypes = db_getEventTypes();
//$isSingleCity = db_isSingleCityArchiveEvent();
$isSingleCity = false;
$eventTemplates = db_getEventTemplates($memberId);

$sort_field = isset ($_SESSION['sort_field-archive']) ? $_SESSION['sort_field-archive'] : 'start_date';
$sort_type = isset ($_SESSION['sort_type-archive']) ? $_SESSION['sort_type-archive'] : 'desc';
$services = db_getServices();
?>
<div class="container" style="width:auto">
    <div id="eventTabs" class="events-list">
        <div class="tab-content">
          <div class="btn-group" style="float: right; margin-right: 10px;">
            <a class="btn dropdown-toggle" type="button" data-toggle="dropdown" style="margin-top: 1px; height: 19px;"><i class="fa fa-question fa-lg"></i><span class="hide-name" style="padding-left: 5px">Помощь</span></a>
              <ul class="dropdown-menu pull-right">
                <?php

                  $sortField = isset ($_COOKIE['sort_field_reference']) ? $_COOKIE ['sort_field_reference'] : 'name';
                  $sortType = isset ($_COOKIE['sort_type_reference']) ? $_COOKIE ['sort_type_reference'] : 'asc';
                  $references = db_getReferences($sortField, $sortType);

                  $page = 'statistic';
                  $countReference = 0;

                  foreach ($references as $key => $reference) {
                      if($page == $reference['page'] && $reference['published'] == '1'){
                          $countReference ++;
                          echo '<li class="modal-reference"><a href="'.$reference['link_article'].'" target="_blank">'.$reference['name'].'</a></li>';
                      }
                  }

                  if($countReference == 0){
                      echo "<li class='modal-reference'>Справочной информации по этому разделу пока нет</li>";
                  }
                  ?>
              </ul>
          </div>
            <div class="btn-toolbar">
                <a class="btn btn-success btn-add-event" type="button"><i class="icon-plus icon-white"></i> <span class="hide-name">Добавить</span></a>
                <a class="btn btn-event-members-statistic" href="#">
                    <i class="fa fa-bar-chart" title="Поименная статистика" aria-hidden="true"></i>
                </a>
                <a class="btn btn-event-general-statistic" href="#">
                    <i class="fa fa-area-chart" title="Общая статистика" aria-hidden="true"></i>
                </a>
                <!--<div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <span class="sortName"><?php //echo $s = isset($_COOKIE['sort'])? $_COOKIE['sort'] : 'Сортировать' ?></span>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" id="dropdownMenu" aria-labelledby="dropdownMenu1">
                        <li><a id="sort-date" href="#" title="сортировать">Дата</a>&nbsp;<i class="<?php //echo $sort_field=='date' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                        <li>
                            <?php
                            //if (!$isSingleCity)
                            //    echo '<th><a id="sort-locality_key" href="#" title="сортировать">Местность (район)</a>&nbsp;<i class="'.($sort_field=='locality_key' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none').'"></i></th>';
                            ?>
                        </li>
                        <li><a id="sort-main-name" href="#" title="сортировать">Вид собрания</a>&nbsp;<i class="<?php //echo $sort_field=='meeting_type' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                        <li><a id="sort-main-count" href="#" title="сортировать">По списку</a>&nbsp;<i class="<?php //echo $sort_field=='members_count' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                    </ul>
                </div> -->
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
            <select class="controls span3 filter-members-by-service">
                <option value='_all_'>Все служения</option>
                <?php
                    foreach ($services as $key => $value) {
                        echo '<option value='.$key.'>'.$value.'</option>';
                    }
                ?>
            </select>
            <i class="btn fa fa-check fa-lg filter-members-coord" title="Выбрать координаторов" aria-hidden="true"></i>
            <i class="btn fa fa-wrench fa-lg filter-members-serving_ones" title="Выбрать служащих" aria-hidden="true"></i>
        </div>
        <table class="table table-hover table-condensed ">
            <thead>
                <tr>
                    <th><a id="sort-event-name" href="#" title="сортировать">ФИО</a>&nbsp;<i class="<?php echo $sort_event_field=='name' ? ($sort_event_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                    <th><a id="sort-event-locality" href="#" title="сортировать">Местность</a>&nbsp;<i class="<?php echo $sort_event_field=='locality' ? ($sort_event_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                    <th><a id="sort-event-age" href="#" title="сортировать">Возраст</a>&nbsp;<i class="<?php echo $sort_event_field=='age' ? ($sort_event_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                    <!--<th><a id="sort-event-coord" href="#" title="сортировать">Коорд.</a>&nbsp;<i class="<?php/* echo $sort_event_field=='coord' ? ($sort_event_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; */?>"></i></th>
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
<div id="modalAddEvent" data-width="400" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true" style="top: 10% !important">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Добавить мероприятие</h3>
    </div>
    <div class="modal-body">
        <button class="btn btn-default btn-event btn-add-event btn-success">Мероприятие</button>
        <button style="float: right;" class="btn btn-default btn-event btn-add-event-template" disabled>Шаблон мероприятия</button>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary btn-do-add-event">Добавить</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>

<div id="modalCreateEvent" class="modal hide fade" tabindex="-1" role="dialog"  aria-labelledby="regEndedTitle" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3></h3>
    </div>
    <div class="modal-body">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#new-event" data-toggle="tab">Новое</a></li>
                <!--<li><a href="#event-templates" data-toggle="tab">Шаблоны</a></li>-->
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
                            <label class="span12">Дата начала<sup>*</sup></label>
                            <div class="control-group row-fluid date">
                                <input type="text" class="form-control span12 event-start-date datepicker" readonly maxlength="10" placeholder="ДД.ММ.ГГГГ" valid="required">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>
                        <div class="control-group row-fluid">
                            <label class="span12">Дата окончания<sup>*</sup></label>
                            <div class="control-group row-fluid date">
                                <input class="form-control span12 event-end-date datepicker" readonly type="text" maxlength="10" placeholder="ДД.ММ.ГГГГ" valid="required">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>

                        <div class="block-event-fields">

                        </div>

                        <div class="control-group row-fluid">
                            <label class="span12">Информация о мероприятии</label>
                            <div class="control-group row-fluid">
                                <textarea class="span12 event-info" cols="5"></textarea>
                            </div>
                        </div>
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
                        <div class="control-group row-fluid">
                            <label class="span12">Зона видимости</label>
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
                            <label class="span12">Ответственные за мероприятие</label>
                            <div class="control-group row-fluid">
                                <div class="reg-members-added"></div>
                                <input type="text" class="span12 search-reg-member" placeholder="Введите текст">
                                <div class="reg-members-available"></div>
                            </div>
                        </div>
                        <div class="control-group row-fluid">
                            <label>Валюта</label>
                            <select class="" name="" id="currencySelect">
                              <option value="_none_" selected></option>
                              <option value="USD">USD</option>
                              <option value="EUR">EUR</option>
                              <option value="RUR">RUR</option>
                            </select>
                            <label>Взнос</label>
                            <input type="number" class="span4" id="contribSelect" placeholder="Введите сумму взноса">
                        </div>
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
        <button class="btn btn-primary btn-do-create ">Создать</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>

<script>
$(document).ready(function(){
    loadArchiveEvents();
    var mobileScreen500;
    $(window).width() < 499 ? mobileScreen500 = true : mobileScreen500 = false;
    $(".event-type").change(function(){
        var eventType = $(this).val();
    });

    $(".btn-add-event").click(function(){
        $("#modalAddEvent").modal('show');
    });

    $(".btn-event").click(function(){
        $(this).addClass("btn-success").siblings().removeClass("btn-success");
        $(".btn-do-add-event").attr('disabled', false);
    });

    $(".btn-do-add-event").click(function(){
        var element = $(this).parents("#modalAddEvent").find('.modal-body .btn-event.btn-success'),
            modalWindow = $("#modalCreateEvent");

            getEventFieldsValue();
        //modalWindow.find('.event-name').keyup();

        if(element.length > 0){
            if(element.hasClass('btn-add-event')){
                modalWindow.find('.btn-do-create').addClass('btn-do-create-event').removeClass('btn-do-create-event-template');
                modalWindow.find('.modal-header h3').html('Создание мероприятия');
            }
            else{
                modalWindow.find('.btn-do-create').addClass('btn-do-create-event-template').removeClass('btn-do-create-event');
                modalWindow.find('.modal-header h3').html('Создание шаблона мероприятия');
            }

            modalWindow.modal('show');
        }
    });

    $(".btn-do-create").click(function(){
        if($(this).hasClass('btn-do-create-event')){

          if ($('#modalCreateEvent').find('.event-end-date').val() < $('#modalCreateEvent').find('.event-start-date').val()) {
            showError('Дата окончания не может быть раньше даты начала мероприятия');
            $('#modalCreateEvent').find('.event-end-date').css('border-color', 'red');
            $('#modalCreateEvent').find('.event-start-date').css('border-color', 'red');
            return
          }
            handleEventCreation();
            $('#modalAddEvent').modal('hide');
        }
        else if($(this).hasClass('btn-do-create-event-template')){
            handleTemplateCreation();
        }
    });

    function handleEventCreation(){
        var fields = getEventFieldsValue();

        if(fields.name === '' || fields.eventType === '_none_' || fields.eventLocality === '_none_' || fields.eventEndDate === '' || fields.eventStartDate === ''){
            showError("Необходимо заполнить все поля выделенные розовым цветом.");
            return;
        }
        $("#modalCreateEvent").modal('hide');

        $.post("/ajax/archive.php?add_event", fields ).done(function(data){
            refreshArchiveEvents(data.events);
        });
    }
    $("#modalCreateEvent").on('hide', function () {
      $("#selectedEventType").focus();      
    });
// TEMPLATE CREATION
    function handleTemplateCreation(){

    }

    function getEventFieldsValue(){
        var modalWindow = $("#modalCreateEvent");
        var startDate = $(".archive-event-start-date").val(),
            endDate = $(".archive-event-end-date").val(),
            name = modalWindow.find(".event-name").keyup().val().trim(),
            eventType = modalWindow.find(".event-type").change().val().trim(),
            eventLocality = modalWindow.find(".event-locality").change().val(),
            eventStartDate = modalWindow.find(".event-start-date").keyup().val().trim(),
            eventEndDate = modalWindow.find(".event-end-date").keyup().val().trim(),
            eventInfo = modalWindow.find(".event-info").val().trim(),
            currencySelect = $("#currencySelect").val().trim(),
            contribSelect = $("#contribSelect").val().trim(),
            eventAdmins = [], participants = [], zones = '', mode = 1, eventAdminsEmail = 1, regEndDate = 1, passport = 1, prepayment = 1, privateVar = 1, transport = 1, tp = 1, flight = 1, parking = 1, service = 1, accom = 1, registration = 0, attendance = 1, countMeetings = 1, regMembers = 1, teamKey = 1;
            modalWindow.find(".participants-added > div").each(function(){
                var field = $(this).attr('data-field'), id = $(this).attr('data-id');
                participants.push({field: field, id : id});
            });
            var itrCounter = 0;
            modalWindow.find(".zones-added > div").each(function(){
                var field = $(this).attr('data-field'), id = $(this).attr('data-id');
                itrCounter++;
                var f = field + ':' + id;
                itrCounter === 1 ? zones+= f : zones = zones + ',' + f;
            });
            modalWindow.find(".reg-members-added > div").each(function(){
                eventAdmins.push($(this).attr('data-id'));
            });
            eventAdmins[0] ? '' : eventAdmins[0] = window.adminId;
            return({
                teamKey: teamKey,
                regMembers: regMembers,
                countMeetings: countMeetings,
                attendance: attendance,
                registration: registration,
                accom: accom,
                service: service,
                parking: parking,
                flight: flight,
                tp: tp,
                transport: transport,
                private: privateVar,
                prepayment: prepayment,
                passport: passport,
                regEndDate: regEndDate,
                mode: mode,
                eventAdminsEmail: eventAdminsEmail,
                startDate: parseDate(startDate),
                endDate : parseDate(endDate),
                name : name,
                eventType : eventType,
                eventLocality : eventLocality,
                eventStartDate : parseDate(eventStartDate),
                eventEndDate : parseDate(eventEndDate),
                eventInfo : eventInfo,
                participants : JSON.stringify(participants),
                zones : zones,
                eventAdmins : eventAdmins,
                currency: currencySelect,
                contrib: contribSelect
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
        /*showCoordOnes = $(".filter-members-coord").hasClass('active');
          showServingOnes = $(".filter-members-serving_ones").hasClass('active');
            selectedService = $(".filter-members-by-service").val();
            var membersCoord, membersService, membersServiceTempArr; */


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
                ' data-locality="'+e.locality_key+'" data-type="'+e.event_type+'" data-date="'+formatDate(e.start_date)+'" data-services="'+e.services+'" data-responsibles="'+e.service_ones+'" data-coordinators="'+e.coordinators+'" data-event_id="'+e.event_id+'"';

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
            $(this).parents('tr').addClass('active-string');
            var that = this,
                eventId = $(this).hasClass('archive-event-row') ? $(this).attr('data-id') : $(this).parents('tr').attr('data-id');


            $.post('/ajax/archive.php?get_members_list', {event_id: eventId})
            .done(function(data){
                if($(that).hasClass('archive-event-row')){
                    buildEventStatistic(data.list);
                }
                else{
                    window.services = data.services;
                    buildEventMembersList(data.list, $('.active-string').attr('data-event_id'), $('.active-string').attr('data-coordinators'), $('.active-string').attr('data-services'), $('.active-string').attr('data-responsibles'));
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

    function buildEventMembersList(list,eventId1C, coordinators, services, responsibles){
      if (services) {
        services = services.split(',');
        coordinators = coordinators.split(',');
        for (var i in services) {
          services[i] = services[i].split(':');
        }
      }
      // Services
      for (var i in list) {
        for (var j in services) {
           if (services[j][1] === list[i].id) {
             list[i].service = services[j][0];
           }
        }
      }
      // Coordinators
      for (var i in list) {
        for (var j in coordinators) {
           if (coordinators[j] == list[i].id) {
             list[i].coordinator = '1';
           }
        }
      }
        var tableRows = [];
        for (var i in list) {
            var m = list[i], serviceNameRus, a, b,
                /*isServingOne = false, serviceName = '', */
                age = getAgeWithSuffix(parseInt(m.age), m.age);
                m.service != 'undefined' ? serviceCheck = true : serviceCheck = false;
                if (serviceCheck) {
                  $('.filter-members-by-service option').each(function() {
                    var a = $(this).val(),
                    b = $(this).text();
                    m.service == a ? serviceNameRus = b : '';
                  });
                }
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
            tableRows.push('<tr class="" data-id="'+m.id+'" data-locality="'+m.locality+'" data-age="'+parseInt(m.age)+'" data-service="'+m.service+'" data-coordinator="'+m.coordinator+'" >'+
                    '<td>'+he(m.name)+'<span>'+(m.coordinator === '1' ? '<br><i>Координатор</i>' : '')+'</span>'+(m.service ? '<br>' : '')+'<i>'+(m.service ? serviceNameRus : '')+'</i></td>'+
                    '<td>'+he(m.locality)+'</td>'+
                    '<td>'+(age)+'</td>'+
                    '</tr>');

        }

        $("#modalEventMembers tbody").html(tableRows.join(''));
        $("#modalEventMembers").modal('show');
        $("#modalEventMembers .scroll-up").hide();
        filterEventMembersList();
    }


    $(".filter-members-serving_ones").click(function(){
        $(this).hasClass('active') ? $(this).removeClass('active') : $(this).addClass('active');
        filterEventMembersList();
    });

    $(".filter-members-coord").click(function(){
        $(this).hasClass('active') ? $(this).removeClass('active') : $(this).addClass('active');
        filterEventMembersList();
    });

    $(".filter-members-by-service").change(function(){
        filterEventMembersList();
    });
    $(".search-event-members").keyup(function(){
        filterEventMembersList();
    });

    function filterEventMembersList(){
        var searchText = $(".search-event-members").val().trim().toLowerCase(),
            membersLocality,
            memberId, membersArr = [], isListPassed=false, membersName, membersCoord, membersService, membersServiceTempArr,
            showCoordOnes = $(".filter-members-coord").hasClass('active'),
            showServingOnes = $(".filter-members-serving_ones").hasClass('active'),
            selectedService = $(".filter-members-by-service").val();

        $("#modalEventMembers tbody tr").each(function(){
            membersCoord = $(this).attr('data-coordinator');
            membersService = $(this).attr('data-service');
            memberId = $(this).attr('data-id');
            membersName = $(this).find('td').first().text().trim().toLowerCase();
            membersLocality = $(this).find('td:nth-child(2)').text().trim().toLowerCase();

/*
            if(membersService && selectedService !== '_all_' && !isListPassed){
                membersServiceTempArr = membersService.split(',');

                for(var i in membersServiceTempArr){
                    if(membersServiceTempArr[i].split(':')[0] === selectedService){
                        membersArr.push(membersServiceTempArr[i].split(':')[1]);
                    }
                }

                isListPassed = true;
            }*/
            /*
             (!showServingOnes || (showServingOnes && membersService)) && (selectedService === '_all_' || (selectedService !== '_all_' && in_array(memberId, membersArr))) &&
(!showCoordOnes || (showCoordOnes && membersCoord === '1'))
            */
             (!showCoordOnes || (showCoordOnes && membersCoord === '1')) &&
             ((selectedService === '_all_' && !showServingOnes) || (selectedService !== '_all_' && selectedService == membersService) || (showServingOnes && membersService != 'undefined' && (selectedService === '_all_' || selectedService == membersService))) &&
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
?>
  </body>
</html>
