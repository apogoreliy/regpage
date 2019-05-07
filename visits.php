<?php
include_once "header.php";
include_once "nav.php";
include_once "modals.php";

$localities = db_getAdminMeetingLocalities ($memberId);
$meetingsTypes = db_getMeetingsTypes();
$isSingleCity = db_isSingleCityAdmin($memberId);
$singleLocality = db_getSingleAdminLocality($memberId);

$categories = db_getCategories();

$selMeetingLocality = isset ($_COOKIE['selMeetingLocality']) ? $_COOKIE['selMeetingLocality'] : '_all_';
$selMeetingCategory = isset ($_COOKIE['selMeetingCategory']) ? $_COOKIE['selMeetingCategory'] : '_all_';

$sort_field = isset ($_SESSION['sort_field-visits']) ? $_SESSION['sort_field-meetings'] : 'date_visit';
$sort_type = isset ($_SESSION['sort_type-visits']) ? $_SESSION['sort_type-meetings'] : 'asc';
?>

<style>body {padding-top: 60px;}</style>
<div class="container">
    <div id="eventTabs" class="meetings-list">
        <div class="tab-content">
          <select class="controls span4 meeting-lists-combo" tooltip="Выберите нужный вам список здесь">
              <option value="meetings">Собрания</option>
              <option selected value="callsAndVisits">Посещения и звонки</option>
          </select>
            <div class="btn-toolbar" style="margin-top:10px !important">
                <a class="btn btn-success add-meeting" type="button"><i class="fa fa-plus icon-white"></i> <span class="hide-name">Добавить</span></a>
                <!--<a class="btn btn-primary show-templates" type="button"><i class="fa fa-list"></i> <span class="hide-name">Шаблоны</span></a>-->
                <a class="btn " href="#"><!-- add this style btn-meeting-members-statistic-->
                    <i class="fa fa-bar-chart" title="Поименная статистика" aria-hidden="true"></i>
                </a>
                <a class="btn " href="#"><!-- add this style btn-meeting-general-statistic-->
                    <i class="fa fa-area-chart" title="Общая статистика" aria-hidden="true"></i>
                </a>
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <span class="sortName fa fa-list"><?php echo $s = isset($_COOKIE['sort'])? $_COOKIE['sort'] : ' ' ?></span>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" id="dropdownMenu" aria-labelledby="dropdownMenu1">
                        <li><a id="sort-date_visit" href="#" title="сортировать">Дата</a>&nbsp;<i class="<?php echo $sort_field=='date_visit' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                        <li><a id="sort-list_members" href="#" title="сортировать">Фамилия Имя</a>&nbsp;<i class="<?php echo $sort_field=='list_members' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i>
                        </li>
                        <li>
                            <?php
                            if (!$isSingleCity)
                                echo '<th><a id="sort-locality_key" href="#" title="сортировать">Местность (район)</a>&nbsp;<i class="'.($sort_field=='locality_key' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none').'"></i></th>';
                            ?>
                        </li>
                        <li><a id="sort-act" href="#" title="сортировать">Событие</a>&nbsp;<i class="<?php echo $sort_field=='meeting_type' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                        <li><a id="sort-responsible" href="#" title="сортировать">Ответственный</a>&nbsp;<i class="<?php echo $sort_field=='responsible' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i>
                        </li>
                        <li><a id="sort-status" href="#" title="сортировать">Статус</a>&nbsp;<i class="<?php echo $sort_field=='responsible' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i>
                        </li>
                    </ul>
                </div>
                <div class="input-group input-daterange datepicker">
                    <input type="text" class="span2 start-date" value="<?php echo date("d.m.Y", strtotime("-1 months")); ?>">
                    <i class="btn fa fa-calendar" aria-hidden="true"></i>
                    <input type="text" class="span2 end-date" value="<?php echo date('d.m.Y', strtotime("+1 months")); ?>">
                </div>
                <select id="selMeetingCategory" class="span2">
                    <option value="_all_">Все события</option>
                    <option value="plan" selected>Планируемые</option>
                    <option value="1">Сделано</option>
                    <option value="2">Не сделано</option>
                </select>

                <?php if (!$isSingleCity) { ?>
                <select id="selMeetingLocality" class="span3">
                    <option value='_all_' <?php echo $selMeetingLocality =='_all_' ? 'selected' : '' ?> >Все местности (районы)</option>
                    <?php
                        foreach ($localities as $id => $name) {
                            echo "<option value='$id' ". ($id==$selMeetingLocality ? 'selected' : '') ." >".htmlspecialchars ($name)."</option>";
                        }
                    ?>
                </select>
                <?php } ?>
                <select id="responsibleList" data-id_admin="" class="col-sm span2">
                  <option value="_all_">Все</option>
                </select>
            </div>
            <div class="desctopVisible" id="visitsListTbl">
                <table id="meetings" class="table table-hover">
                    <thead>
                    <tr>
                        <th><a id="sort-date_visit" href="#" title="сортировать">Дата</a>&nbsp;<i class="<?php echo $sort_field=='date_visit' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        <th style=""><a id="sort-list_members" href="#" title="сортировать">Фамилия Имя</a>&nbsp;<i class="<?php echo $sort_field=='list_members' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        <?php
                        if (!$isSingleCity)
                            echo '<th><a id="sort-locality_key" href="#" title="сортировать">Местность (район)</a>&nbsp;<i class="'.($sort_field=='locality_key' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none').'"></i></th>';
                        ?>
                        <th style="text-align: left; min-width:100px"><a id="sort-act" href="#" title="сортировать">Событие</a>&nbsp;<i class="<?php echo $sort_field=='act' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        <th style="text-align: left; min-width:170px"><a id="sort-responsible" href="#" title="сортировать">Ответственный</a>&nbsp;<i class="<?php echo $sort_field=='responsible' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        <th style="text-align: left; width:130px;"><a id="sort-status" href="#" title="сортировать">Статус</a>&nbsp;<i class="<?php echo $sort_field=='responsible' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                    </tr>
                    </thead>
                    <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
                </table>
            </div>
            <div class="show-phone" id="visitsListMbl">
                <table id="meetingsPhone" class="table table-hover">
                    <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ADD | EDIT MEETING Modal -->
<div id="addEditMeetingModal" class="modal hide fade" data-width="500" tabindex="-1" role="dialog" aria-labelledby="regNameEdit" aria-hidden="true" data-status_val="">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 id="titleMeetingModal"></h4>
    </div>
    <div class="modal-body" style="height:auto !important">
        <div class="desctop-visible tablets-visible">
            <div class="control-group row-fluid"
            <?php if (count($localities) == 1) { ?>
              style="display: none"
             <?php } ?>
            >
                <div style="margin-bottom: 5px">
                  <select id="visitLocalityModal">
                      <?php
                          foreach ($localities as $id => $name) {
                              echo "<option value='$id' ". ($id == $singleLocality || $isSingleCity ? 'selected="selected"' :   '') ." >".htmlspecialchars ($name)."</option>";
                              }
                              ?>
                            </select>
                            <select style="float: right" id="responsible" data-id_admin="" class="col-sm">
                            </select>
                            <!--<span style="float: right" id="responsible" data-id_admin='
                            <?php echo $memberId; ?>' class="col-sm">
                            <?php echo db_getAdminNameById($memberId); ?>
                          </span>-->
                        </div>
                        <div class="" style="margin-bottom: 5px;">
                          <select id="actionType">
                            <option selected value="visit">Посещение</option>
                            <option value='call'>Звонок</option>
                          </select>
                          <select id="performedChkbx" class="status-select-plan" style="float: right">
                            <option selected value='0'>Планируется</option>
                            <option value='1'>Сделано</option>
                            <option value='2'>Не сделано</option>
                            <option value='3'>Удалить карточку</option>
                          </select>
                        </div>
                        <div class="">
                          <input id="actionDate" class="actionDate" type="date">
                          <span id="dayOfWeek" style="padding-left: 11px;" ></span>
                        </div>
                        <!--<div>
                           <input style="margin-bottom: 3px;" type="checkbox" id="performedChkbx">
                          <label for="performedChkbx">сделано</label>
                        </div> -->
                      </div>
              <div class="control-group row-fluid" style="margin-top: 15px;">
                  <div class="block-add-members">
                      <div class="members-available"></div>
                  </div>
                  <table class='table table-hover'>
                      <tbody></tbody>
                  </table>
              </div>
              <div class="control-group row-fluid">
                <textarea id="visitNote" class="span12 note-field" style="margin-top: 10px; margin-bottom: 10px"></textarea>
              </div>
        </div>
    </div>
    <div class="modal-footer">
      <!--<div>
          <a id="button-people-meting" class="btn btn-success" type="button" value="" data-field="m"><i class="fa fa-plus icon-plus icon-white"></i><span class="hide-name"> Добавить</span></a>
          <input id="clear-button-people-meeting" class="btn btn-warning" type="button" value="Очистить список" data-field="m" style="margin-top: 10px; margin-bottom: 10px;">
      </div>-->
        <button id="button-people-meting" style="float: left" data-field="m" class="btn btn-success"><i class="fa fa-plus icon-plus icon-white"></i><span class="hide-name"> Добавить</span></button>
        <button class="btn btn-info btnDoHandleMeeting disable-on-invalid">Сохранить</button>
        <button class="btn" id="cancelModalWindow" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>

<!-- STATUS Visit Modal -->
<!--
<div id="modalStatusChange" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true" data-id="" data-status_old="" data-mobile="" style="max-width: 300px;">
    <div class="modal-header" style="max-width: 300px;">
        <h3 style="text-align: center">Выберите статус</h3>
    </div>
    <div class="modal-body" style="max-width: 300px;">
        <table class="table table-hover table-condensed " style="margin-bottom: 0px !important">
            <tbody>
              <tr>
                  <td style="text-align: center"><span data-dismiss="modal" class="label label-default" style="font-size: 22px; cursor: pointer; padding: 10px" id="planingVisit" data-status_new="0" data-status_name="планируется">планируется</span></td>
              </tr>
              <tr>
                  <td style="text-align: center"><span data-dismiss="modal" class="label label-success" style="font-size: 22px; cursor: pointer; padding: 10px" id="doneVisit" data-status_new="1" data-status_name="сделано">сделано</span></td>
              </tr>
              <tr>
                  <td style="text-align: center"><span data-dismiss="modal" style="font-size: 22px; cursor: pointer; padding: 10px" class="label label-warning" id="missVisit" data-status_new="2" data-status_name="не сделано">не сделано</span></td>
              </tr>
              <tr>
                  <td style="text-align: center"><span data-dismiss="modal" style="font-size: 22px; cursor: pointer; padding: 10px" class="label label-info" id="deleteThisVisit" data-status_new="3" data-status_name="удалить карточку">удалить карточку</span></td>
              </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer" style="max-width: 300px;">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>
-->
<!-- STATISTIC Members Modal -->
<div id="modalMeetingStatistic" data-width="900" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Поименная статистика</h3>
    </div>
    <div class="modal-body">
        <select id="meetingTypeStatistic" class="span2">
            <option value='_all_' selected <?php echo $selMeetingCategory =='_all_' ? 'selected' : '' ?> >Все собрания</option>
            <?php foreach ($meetingsTypes as $id => $name) {
                echo "<option value='$id' ". ($id==$selMeetingCategory ? 'selected' : '') .">".htmlspecialchars ($name)."</option>";
            } ?>
        </select>
        <?php if (!$isSingleCity) { ?>
        <select id="localityStatistic" class="span2">
            <option value='_all_' <?php echo $selMeetingLocality =='_all_' ? 'selected' : '' ?> >Все местности (районы)</option>
            <?php
                foreach ($localities as $id => $name) {
                    echo "<option value='$id' ". ($id==$selMeetingLocality ? 'selected' : '') ." >".htmlspecialchars ($name)."</option>";
                }
            ?>
        </select>
        <?php } ?>
        <div class="input-group input-daterange datepicker">
            <input type="text" class="span2 start-date-statistic-members" value="<?php echo date("d.m.Y", strtotime("-1 months")); ?>">
            <i class="btn fa fa-calendar" aria-hidden="true"></i>
            <input type="text" class="span2 end-date-statistic-members" value="<?php echo date('d.m.Y'); ?>">
        </div>
        <i class="btn btn-meeting-download-members-statistic fa fa-download" title="Скачать общую статистику" aria-hidden="true"></i>
        <div style="margin-bottom: 10px;">
            <?php
                    foreach ($meetingsTypes as $type => $name){
                        echo '<span class="statistic-bar-form '.$type.'-meeting"></span><span>'.$name.'</span>';
                    }
            ?>
        </div>
        <div id="general-statistic"></div>
        <table class="table table-hover table-condensed statistic-meeting-list">
            <thead><tr><th style="width: 30%;">Фамилия Имя Отчество</th><th></th></tr></thead>
            <tbody></tbody>
        </table>
        <i class="fa fa-arrow-up fa-3x scroll-up" aria-hidden="true"></i>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Ok</button>
    </div>
</div>

<!-- STATISTIC General Modal -->
<div id="modalGeneralMeetingStatistic" data-width="900" data-height="500" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Общая статистика</h3>
    </div>
    <div class="modal-body">
        <select id="meetingTypeGeneralStatistic" class="span2">
            <option value='_all_' selected <?php echo $selMeetingCategory =='_all_' ? 'selected' : '' ?> >Все собрания</option>
            <?php foreach ($meetingsTypes as $id => $name) {
                echo "<option value='$id' ". ($id==$selMeetingCategory ? 'selected' : '') .">".htmlspecialchars ($name)."</option>";
            } ?>
        </select>
        <?php if (!$isSingleCity) { ?>
        <select id="localityGeneralStatistic" class="span2">
            <option value='_all_' <?php echo $selMeetingLocality =='_all_' ? 'selected' : '' ?> >Все местности (районы)</option>
            <?php
                foreach ($localities as $id => $name) {
                    echo "<option value='$id' ". ($id==$selMeetingLocality ? 'selected' : '') ." >".htmlspecialchars ($name)."</option>";
                }
            ?>
        </select>
        <?php } ?>
        <div class="input-group input-daterange datepicker">
            <input type="text" class="span2 start-date-statistic-general" value="<?php echo date("d.m.Y", strtotime("-1 months")); ?>">
            <i class="btn fa fa-calendar" aria-hidden="true"></i>
            <input type="text" class="span2 end-date-statistic-general" value="<?php echo date('d.m.Y'); ?>">
        </div>
        <i class="btn btn-meeting-download-general-statistic fa fa-download" title="Скачать статистику" aria-hidden="true"></i>

        <div id="chart_div" style="height: 85%"></div>
        <i class="fa fa-arrow-up fa-3x scroll-up" aria-hidden="true"></i>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Ok</button>
    </div>
</div>

<!-- MEETING Members Modal -->
<div id="modalRemoveMeeting" class="modalRemoveVisit modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <!--<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4>Удаление собрания</h4>
    </div>-->
    <div class="modal-body">
        <h5 style="text-align: center">Карточка будет удалена! Продолжить?</h5>
    </div>
    <div class="modal-footer" style="text-align: center">
        <button class="btn btn-primary remove-meeting" data-dismiss="modal" aria-hidden="true">Да</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Нет</button>
    </div>
</div>

<!-- TEMPLATES LIST Modal -->
<div id="modalTemplates" data-width="960" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Шаблоны собраний</h3>
    </div>
    <div class="modal-body">
            <div class="toolbar">
                <a class="btn btn-success btn-add-template" type="button"><i class="fa fa-plus icon-plus icon-white"></i> <span class="hide-name">Добавить</span></a>
            </div>
            <table id='template-list' class='table table-hover'>
                <thead>
                    <tr>
                        <th><a id='sort-name' href='#' title='сортировать'>Название</a>&nbsp;</th>
                        <th><a id='sort-locality' href='#' title='сортировать'>Местность</a>&nbsp;</th>
                        <th><a id='sort-participant' href='#' title='сортировать'>Участники</a>&nbsp;</th>
                        <th><a id='sort-admin' href='#' title='сортировать'>Редакторы</a>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
    </div>
    <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">OK</button>
    </div>
</div>

<!-- TEMPLATE Modal -->
<div id="modalHandleTemplate" data-width="500" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3></h3>
    </div>
    <div class="modal-body">
        <div class="desctop-visible tablets-visible">
          <div id="template">
                <div class="control-group row-fluid"
                <?php if (count($localities) == 1) { ?>
                  style="display: none"
                 <?php } ?>
                >
                    <label>Выберите местность</label>
                    <select class="span12 template-locality"  valid="required">
                        <option value='_none_' >&nbsp;</option>
                        <?php
                            foreach ($localities as $id => $name) {
                                echo "<option value='$id' >".htmlspecialchars ($name)."</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="control-group row-fluid">
                    <label>Выберите вид собрания</label>
                    <select  class="span12 template-meeting-type" valid="required">
                        <option value='_none_'>&nbsp;</option>
                        <?php
                            foreach ($meetingsTypes as $id => $name) {
                                echo "<option value='$id'>".htmlspecialchars ($name)."</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="control-group row-fluid">
                    <label>Название</label>
                    <input type="text" class="span12 template-name"  valid="required">
                </div>
                <div class="control-group row-fluid">
                    <label class="span12">Редакторы</label>
                    <div class="template-admins-added"></div>
                    <input type="text" class="span12 search-template-admins" placeholder="Введите текст">
                    <div class="template-admins-available" style="margin-bottom: 25px;"></div>
                </div>
            </div>
            <div id="list">
                <div class="control-group row-fluid">
                  <label style="float:left;">Поместных святых:</label>
                  <input type="text" style="float:right;" readonly class="span2 meeting-saints-count-template">
                </div>
                <!--<div class="control-group row-fluid" style="display:none;">
                  <label style="float:left;">Гостей:</label>
                  <input type="text" style="float:right;" class="span2 meeting-count-guest-template">
                </div>-->
                <div class="control-group row-fluid">
                  <label style="float:left;">Всего присутствующих:</label>
                  <input disabled type="text" style="float:right;" class="span2 meeting-count-template">
                </div>
                <div class="show-extra-fields control-group row-fluid fulltimersClass-template">
                  <div>В том числе полновременных служащих - <span class="meeting-count-fulltimers-template"></span></div>
                </div>
                <div>
                    <input id="button-people" class="btn btn-primary" type="button" value="Добавить участников" data-field="m" style="margin-top: 25px; margin-right: 10px; ">
                    <input id="clear-button-people" class="btn btn-warning" type="button" value="Очистить список" data-field="m" style="margin-top: 25px;">
                </div>
              <div class="control-group row-fluid" style="margin-top: 10px;">

                <!--<div class="control-group row-fluid">
                    <div class="checkbox-block">
                        <div class="btn-group">
                            <input id="checkbox-locality" type="radio" data-field="l" >
                            <label for="checkbox-locality">Местности</label>
                        </div>
                        <div class="btn-group">
                            <input id="checkbox-people" type="radio" data-field="m" >
                            <label for="checkbox-people" >Участники</label>
                        </div>

                    </div>
                </div>
                <div class="control-group row-fluid">
                    <div class="input-append span12">
                        <input class="span11 search-members" type="text" placeholder="Введите местность или ФИО">
                        <span class="add-on"><i style="color: #ddd;" class="fa fa-plus fa-lg"></i></span>
                    </div>

                </div> template-participants-available -->
                <div class="members-available"></div>


                <!--
                <span class="block-remove-members-buttons">
                    <a style="margin-right: 10px" class="remove-members">Удалить</a>
                    <a style="margin-right: 10px" class="remove-members-cancel-all">Отменить</a>
                </span>
                <a class="remove-members-check-all">Отметить всех</a>
                -->
                <table class='table table-hover'>
                    <thead>
                        <tr>
                            <th><a id='sort-meeting_type' href='#' title='сортировать'>ФИО</a>&nbsp;</th>
                            <th><a id='sort-locality' href='#' title='сортировать'>Местность</a>&nbsp;</th>
                            <th><a id='sort-attend_meeting' href='#' title='сортировать'>С</a>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
         </div>
       </div>
     </div>
   </div>
    <div class="modal-footer" style="display: flow-root;">
        <button class="btn btn-primary btn-handle-template"></button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>


<!-- PARTICIPANTS LIST Modal -->
<div id="modalParticipantsList" data-width="600" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3></h3>
    </div>
    <div class="modal-body">
        <div>
            <div class="toolbar">
                <div class="control-group row-fluid">
                    <div class="input-append span12">
                        <input class="span11 search-meeting-available-participants" type="text" placeholder="Введите ФИО">
                        <span class="add-on"><i style="color: #ddd;" class="fa fa-plus fa-lg"></i></span>
                    </div>
                    <div class="participants-available"></div>
                </div>
            </div>
        </div>
        <table class='table table-hover'>
            <thead>
                <tr>
                    <th><a id='sort-meeting_type' href='#' title='сортировать'>ФИО</a>&nbsp;</th>
                    <th><a id='sort-locality' href='#' title='сортировать'>Местность</a>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">OK</button>
    </div>
</div>

<!-- PARTICIPANTS LIST Modal -->
<div id="modalConfirmDeleteParticipant" data-width="600" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3></h3>
    </div>
    <div class="modal-body">

    </div>
    <div class="modal-footer">
        <button class="btn btn-primary doDeleteParticipant" data-dismiss="modal" aria-hidden="true">Удалить</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отменить</button>
    </div>
</div>

<!-- Modal Confirm to remove member from a LIST -->
<div id="modalConfirmRemoveMember" data-width="500" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Удаление участника</h3>
    </div>
    <div class="modal-body">

    </div>
    <div class="modal-footer">
        <button class="btn btn-primary delete-member-from-list" data-dismiss="modal" aria-hidden="true">Удалить</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отменить</button>
    </div>
</div>

<!-- Removing template Modal -->
<div id="modalConfirmDeleteTemplate" data-width="600" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Удаление шаблона собрания</h3>
    </div>
    <div class="modal-body">
        Вы действительно хотите удалить данный шаблон собрания?
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary doDeleteTemplate" data-dismiss="modal" aria-hidden="true">Удалить</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отменить</button>
    </div>
</div>

<!-- CREATING Meeting using template Modal -->
<div id="modalConfirmAddMeetingByTemplate" data-width="600" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Создание собрания на основе шаблона</h3>
    </div>
    <div class="modal-body">
        Вы действительно хотите создать собрание используя данный шаблон собрания?
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary add-template-meeting" data-dismiss="modal" aria-hidden="true">Создать</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отменить</button>
    </div>
</div>

<!-- Add Members Modal -->
<div id="modalAddMembersTemplate" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="addMembersTitle" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="addMembersTitle">Добавление участников</h3>
        <p id="addMemberEventTitle"></p>
    </div>
    <div class="modal-body">
        <form class="form-inline">
            <label
            <?php if (count($localities) == 1) { ?>
              style="display: none"
             <?php } ?>
            >Местность:</label>
            <select id="selAddMemberLocalityTemplate" class="span2"
            <?php if (count($localities) == 1) { ?>
              style="display: none"
             <?php } ?>
            >
              <option value='_all_' <?php echo $selMeetingLocality =='_all_' ? 'selected' : '' ?> >Все местности</option>
              <?php
                  foreach ($localities as $id => $name) {
                      echo "<option value='$id' ". ($id==$selMeetingLocality ? 'selected' : '') ." >".htmlspecialchars ($name)."</option>";
                  }
              ?>
            </select>

            <label>Категория:</label>
            <select id="selAddMemberCategoryTemplate" class="span2">
                <option value='_all_' selected>&lt;все&gt;</option>
                <?php foreach ($categories as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
            </select>
        </form>
        <div class="membersTable">
            <table class="table table-hover table-condensed">
                <thead><tr><th><input type="checkbox" id="selectAllMembersList"></th><th>Фамилия Имя Отчество</th><th>Местность</th></tr></thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" id="btnDoAddMembersTemplate"><i class="fa fa-check icon-ok icon-white"></i> Добавить выбранных</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Отменить</button>
    </div>
</div>
<script>
var nameAdmin = "<?php echo db_getAdminNameById($memberId); ?>";
var whatIsLocalityAdmin = "<?php echo db_getLocalityKeyByName(db_getMemberLocality($memberId)); ?>";
</script>
<script src="/js/visits.js?v118"></script>
<?php
    include_once './footer.php';
?>
