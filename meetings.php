<?php
include_once "header.php";
include_once "nav.php";
include_once "modals.php";
include_once "db/meetingsdb.php";

$localities = db_getAdminMeetingLocalities ($memberId);
$localitiesWithFilters = db_getAdminLocalitiesNotRegTbl($adminId);
$meetingsTypes = db_getMeetingsTypes();
$isSingleCity = db_isSingleCityAdmin($memberId);
$singleLocality = db_getSingleAdminLocality($memberId);

$categories = db_getCategories();

$selMeetingLocality = isset ($_COOKIE['selMeetingLocality']) ? $_COOKIE['selMeetingLocality'] : '_all_';
$selMeetingCategory = isset ($_COOKIE['selMeetingCategory']) ? $_COOKIE['selMeetingCategory'] : '_all_';

$sort_field = isset ($_SESSION['sort_field-meetings']) ? $_SESSION['sort_field-meetings'] : 'date';
$sort_type = isset ($_SESSION['sort_type-meetings']) ? $_SESSION['sort_type-meetings'] : 'desc';
?>

<style>body {padding-top: 60px;}</style>
<div class="container">
    <div id="eventTabs" class="meetings-list">
        <div class="tab-content">
          <select class="controls span4 meeting-lists-combo" tooltip="Выберите нужный вам список здесь">
              <option selected value="meetings">Собрания</option>
              <option value="visits">Посещения и звонки</option>
          </select>
            <div class="btn-toolbar" style="margin-top:10px !important">
                <a class="btn btn-success add-meeting" type="button"><i class="fa fa-plus icon-white"></i> <span class="hide-name">Добавить</span></a>
                <a class="btn btn-primary show-templates" type="button"><i class="fa fa-list"></i> <span class="hide-name">Шаблоны</span></a>
                <a class="btn btn-meeting-members-statistic" href="#">
                    <i class="fa fa-bar-chart" title="Поимённая статистика" aria-hidden="true"></i>
                </a>
                <a class="btn btn-meeting-general-statistic" href="#">
                    <i class="fa fa-area-chart" title="Общая статистика" aria-hidden="true"></i>
                </a>
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <span class="sortName"><?php echo $s = isset($_COOKIE['sort'])? $_COOKIE['sort'] : 'Сортировать' ?></span>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" id="dropdownMenu" aria-labelledby="dropdownMenu1">
                        <li><a id="sort-date" href="#" title="сортировать">Дата</a>&nbsp;<i class="<?php echo $sort_field=='date' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                        <li>
                            <?php
                            if (!$isSingleCity)
                                echo '<th><a id="sort-locality_key" href="#" title="сортировать">Местность (район)</a>&nbsp;<i class="'.($sort_field=='locality_key' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none').'"></i></th>';
                            ?>
                        </li>
                        <li><a id="sort-meeting_type" href="#" title="сортировать">Собрание</a>&nbsp;<i class="<?php echo $sort_field=='meeting_type' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                        <li><a id="sort-list_count" href="#" title="сортировать">На собрании</a>&nbsp;<i class="<?php echo $sort_field=='list_count' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                    </ul>
                </div>
                <div class="input-group input-daterange datepicker">
                    <input type="text" class="span2 start-date" value="<?php echo date("d.m.Y", strtotime("-1 months")); ?>">
                    <i class="btn fa fa-calendar" aria-hidden="true"></i>
                    <input type="text" class="span2 end-date" value="<?php echo date('d.m.Y'); ?>">
                </div>
                <select id="selMeetingCategory" class="span2">
                    <option value='_all_' selected <?php echo $selMeetingCategory =='_all_' ? 'selected' : '' ?> >Все собрания</option>
                    <?php foreach ($meetingsTypes as $id => $name) {
                        echo "<option value='$id' ". ($id==$selMeetingCategory ? 'selected' : '') .">".htmlspecialchars ($name)."</option>";
                    } ?>
                </select>

                <select id="selMeetingLocality" class="span3" <?php if ($isSingleCity) { ?>
                  style="display:none;"
                <?php } ?>
                  >
                    <option value='_all_' <?php echo $selMeetingLocality =='_all_' ? 'selected' : '' ?> >Все местности (районы)</option>
                    <?php
                        foreach ($localities as $id => $name) {
                            echo "<option value='$id' ". ($id==$selMeetingLocality ? 'selected' : '') ." >".htmlspecialchars ($name)."</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="desctopVisible">
                <table id="meetings" class="table table-hover">
                    <thead>
                    <tr>
                        <th><a id="sort-date" href="#" title="сортировать">Дата</a>&nbsp;<i class="<?php echo $sort_field=='date' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        <th><a id="sort-name" href="#" title="сортировать">Собрание</a>&nbsp;<i class="<?php echo $sort_field=='name' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        <?php
                        if (!$isSingleCity)
                            echo '<th><a id="sort-locality_key" href="#" title="сортировать">Местность (район)</a>&nbsp;<i class="'.($sort_field=='locality_key' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none').'"></i></th>';
                        ?>
                        <th style="text-align: center"><a id="sort-saints_count" href="#" title="сортировать">Святых</a>&nbsp;<i class="<?php echo $sort_field=='saints_count' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        <th id="pvom_count" style="text-align: center; display: none"><a href="#" title="">ПВОМ</a></th>
                        <th style="text-align: center"><a id="sort-guests_count" href="#" title="сортировать">Гостей</a>&nbsp;<i class="<?php echo $sort_field=='guests_count' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        <th style="text-align: center"><a href="#">Всего</a></th>
                    </tr>
                    </thead>
                    <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
                </table>
            </div>
            <div class="show-phone">
                <table id="meetingsPhone" class="table table-hover">
                    <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ADD | EDIT MEETING Modal -->
<div id="addEditMeetingModal" class="modal hide fade" data-width="500" tabindex="-1" role="dialog" aria-labelledby="regNameEdit" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 id="titleMeetingModal"></h4>
    </div>
    <div class="modal-body">
        <div class="desctop-visible tablets-visible">

            <div style="margin-bottom: 10px;">
                <a class="show-templates open-in-meeting-window">Заполнить из шаблона</a>
            </div>
            <div class="control-group row-fluid" style="width: 46.5%;">
              <select class="span4" id="meetingLocalityModal" valid="required" >
                  <?php
                      foreach ($localities as $id => $name) {
                          echo "<option value='$id' ". ($id==$selMeetingLocality || $isSingleCity ? 'selected' : '') ." >".htmlspecialchars ($name)."</option>";
                      }
                  ?>
              </select>
              </div>
              <div class="control-group row-fluid" style="width: 48%; float: right;">
                <select id="meetingCategory" class="span12" valid="required">
                    <?php foreach ($meetingsTypes as $id => $name) {
                        echo "<option value='$id'>".htmlspecialchars ($name)."</option>";
                    } ?>
                </select>
            </div>
            <div class="control-group row-fluid">
                <label class="span12" style="min-height: 10px; line-height: 20px;">Название собрания</label>
                <input type="text" class="span12 meetingName" valid="required">
            </div>
            <div class="control-group row-fluid" >


            </div>
<div class="control-group row-fluid">
    <label style="margin-right: 80px; margin-bottom: 0px">Дата</label>
    <label style="margin-right: 17px; margin-bottom: 0px">Cвятых</label>
    <label style="margin-right: 18px; margin-bottom: 0px">Гостей</label>
    <label style="margin-bottom: 0px">Всего</label>

  <div class="control-group row-fluid" style="margin-right: 100px; margin-top: -5px">
    <input class="datepicker meetingDate span2" type="text" placeholder="ДД.ММ.ГГГГ" valid="required,date" style="margin-top: 5px; margin-right: 10px; width: 100px;">
    <input type="text" readonly class="span2 meeting-saints-count" style="margin-right: 10px; width: 55px;">
    <input type="text" class="span2 meeting-count-guest" style="margin-right: 10px; width: 55px;">
    <input disabled type="text" class="span2 meeting-count" style="width: 55px; margin-right: 10px;">
    </div>
    <div class="">
      <label class="traineesClass">ПВОМ</label>
      <input type="text" style="width: 55px" class="span2 meeting-count-trainees traineesClass">
      <span style="padding-left: 10px;" class="show-extra-fields fulltimersClass">Полноврем. служащих — <span class="meeting-count-fulltimers"></span></span>
    </div>
</div>
            <!--<div class="show-extra-fields control-group row-fluid fulltimersClass">
            </div>
            <div class="control-group row-fluid">
                <label>Детей до 12 лет:</label>
                <input disabled type="text" style="float:right;" class="span2 meeting-count-children">
            </div> -->
            <div class="control-group row-fluid" style="margin-top: 5px">
                <!--<label class="span12 meeting-label-note note-field">Комментарий:</label> -->
                <textarea class="span12 meeting-note note-field"></textarea>
            </div>
            <div class="control-group row-fluid">
                <!--<div>
                    <a href="" class="meeting-add-btn" style="margin-right: 10px">Добавить</a> <a href="" style="margin-right: 10px" class="meeting-clear-btn">Очистить список</a>
                </div>-->
                <div class="block-add-members">
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
                    <div class="input-append span12">
                        <input class="span11 search-members" type="text" placeholder="Введите местность или ФИО">
                        <span class="add-on"><i style="color: #ddd;" class="fa fa-plus fa-lg"></i></span>
                    </div>-->
                    <div class="members-available"></div>
                </div>
                <table class='table table-hover'>
                    <thead>
                        <tr>
                            <th><input id="selectAllMembers" type="checkbox">&nbsp;&nbsp;&nbsp;<a id='' class="sortingByName sortMembersModal" href='#' title='сортировать'> ФИО</a>&nbsp;</th>
                            <th><a id='' href='#' title='сортировать' class="sortingByLocality sortMembersModal">Местность</a>&nbsp;</th>
                            <th><a id='' href='#' title='сортировать' class="sortingByOld sortMembersModal">В</a>&nbsp;</th>
                            <th><a id='' href='#' title='сортировать' class="sortingByattend sortMembersModal">С</a>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
      <div style="float: left">
          <a id="button-people-meting" class="btn btn-success" type="button" data-field="m" value="Добавить участника"><i class="fa fa-plus icon-white" ></i> <span class="hide-name"> Добавить</span></a>
          <a id="clear-button-people-meeting" class="btn btn-warning" type="button" value="Очистить список"><i class="fa fa-minus icon-white" ></i> <span class="hide-name">Очистить список</span></a>
      </div>
        <button class="btn btn-info btnDoHandleMeeting">Сохранить</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>

<!-- MEETING Members Modal -->
<!--
<div id="modalMeetingMembers" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Список</h3>
    </div>
    <div class="modal-body">
        <div>
            <input class="span3 search-meeting-members" type="text" placeholder="Введите ФИО">
            <i class="btn fa fa-check fa-lg filter-stat-members-checked" title="Отмеченные" aria-hidden="true"></i>
            <i class="btn fa fa-ban fa-lg filter-stat-members-unchecked" title="Неотмеченные" aria-hidden="true"></i>
        </div>
        <table class="table table-hover table-condensed ">
            <thead><tr><th>&nbsp;</th><th>Фамилия Имя Отчество</th><th>Дата рождения</th></tr></thead>
            <tbody>
            </tbody>
        </table>
        <i class="fa fa-arrow-up fa-3x scroll-up" aria-hidden="true"></i>
    </div>
    <div class="modal-footer">
        <button class="btn btn-success doSaveListMembers" data-dismiss="modal" aria-hidden="true">Сохранить</button>
        <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>
-->

<!-- STATISTIC Members Modal -->
<div id="modalMeetingStatistic" data-width="900" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Поимённая статистика</h3>
    </div>
    <div class="modal-body">
        <select id="meetingTypeStatistic" class="span2">
            <option value='_all_' selected <?php echo $selMeetingCategory =='_all_' ? 'selected' : '' ?> >Все собрания</option>
            <?php foreach ($meetingsTypes as $id => $name) {
                echo "<option value='$id' ". ($id==$selMeetingCategory ? 'selected' : '') .">".htmlspecialchars ($name)."</option>";
            } ?>
        </select>

        <select id="localityStatistic" class="span2" <?php if (!$isSingleCity) { ?>
          style="display: none;"
          <?php } ?>
        >
            <option value='_all_' <?php echo $selMeetingLocality =='_all_' ? 'selected' : '' ?> >Все местности (районы)</option>
            <?php
                foreach ($localitiesWithFilters as $id => $name) {
                    echo "<option value='$id' ". ($id==$selMeetingLocality ? 'selected' : '') ." >".htmlspecialchars ($name)."</option>";
                }
            ?>
        </select>

        <div class="input-group input-daterange datepicker">
            <input type="text" class="span2 start-date-statistic-members" value="<?php echo date("d.m.Y", strtotime("-1 months")); ?>">
            <i class="btn fa fa-calendar" aria-hidden="true"></i>
            <input type="text" class="span2 end-date-statistic-members" value="<?php echo date('d.m.Y'); ?>">
        </div>
        <i class="btn btn-meeting-download-members-statistic fa fa-download" title="Скачать общую статистику" aria-hidden="true"></i><br>
        <label style="margin-left: 7px;" for="showAllMembersCkbx"><input id="showAllMembersCkbx" type="checkbox"style="margin-bottom: 7px;"> только те, кто посещал собрания.</label>
        <div id="meetingsLegends" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="regNameEdit" aria-hidden="true" data-width="200">
            <?php
                    foreach ($meetingsTypes as $type => $name){
                        echo '<span class="statistic-bar-form '.$type.'-meeting"></span><span>'.$name.'</span><br>';
                    }
            ?>
            <input type="button" name="" value="Закрыть" id="meetingsLegendsClose" style="margin-left: 65px !important; margin-top: 10px; margin-bottom: 10px;">
        </div>
        <div><span id="general-statistic"></span><input type="button" name="" value="Обозначения собраний" id="meetingsLegendsShow" style="margin-left: 10px; margin-bottom: 8px; float:right"></div>
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

        <select id="localityGeneralStatistic" class="span2" <?php if (!$isSingleCity) { ?>
          style="display:none;"
        <?php } ?>
          >
            <option value='_all_' <?php echo $selMeetingLocality =='_all_' ? 'selected' : '' ?> >Все местности (районы)</option>
            <?php
                foreach ($localities as $id => $name) {
                    echo "<option value='$id' ". ($id==$selMeetingLocality ? 'selected' : '') ." >".htmlspecialchars ($name)."</option>";
                }
            ?>
        </select>

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
<div id="modalRemoveMeeting" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4>Удаление собрания</h4>
    </div>
    <div class="modal-body">
        <h5>Вы действительно хотите удалить запись об этом собрании?</h5>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary remove-meeting" data-dismiss="modal" aria-hidden="true">Удалить</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отмена</button>
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
                <div class="control-group row-fluid" style="width: 48%;">
                    <select class="span12 template-locality" valid="required" >
                        <option value='_none_' >&nbsp;</option>
                        <?php
                            foreach ($localities as $id => $name) {
                                echo "<option value='$id' >".htmlspecialchars ($name)."</option>";
                            }
                        ?>
                    </select>
                    </div>
                    <div class="control-group row-fluid"  style="width: 48%; float: right;">
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
                    <label>Название собрания</label>
                    <input type="text" class="span12 template-name"  valid="required" style="margin-bottom: 25px;">
                </div>
            </div>
            <div id="list">
                <!--<div class="control-group row-fluid">
                  <label style="float:left;">Поместных святых:</label>
                  <input type="text" style="float:right;" readonly class="span2 meeting-saints-count-template">
                </div>
                <div class="control-group row-fluid" style="display:none;">
                  <label style="float:left;">Гостей:</label>
                  <input type="text" style="float:right;" class="span2 meeting-count-guest-template">
                </div>
                <div class="control-group row-fluid">
                  <label style="float:left;">Всего присутствующих:</label>
                  <input disabled type="text" style="float:right;" class="span2 meeting-count-template">
                </div>-->
                <div class="show-extra-fields control-group row-fluid fulltimersClass-template">
                  <div>В том числе полновременных служащих - <span class="meeting-count-fulltimers-template"></span></div>
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
                            <th><a id='sort-by_name' href='#' title='сортировать' class="sortingByName sortMembersModal">ФИО</a>&nbsp;</th>
                            <th><a id='sort-locality' href='#' title='сортировать' class="sortingByLocality sortMembersModal">Местность</a>&nbsp;</th>
                            <th><a id='sort-old' href='#' title='сортировать' class="sortingByOld sortMembersModal">В</a>&nbsp;</th>
                            <th><a id='sort-attend_meeting' href='#' title='сортировать' class="sortingByattend sortMembersModal">С</a>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
         </div>
         <div class="control-group row-fluid">
             <strong class="span12">Редакторы</strong>
             <div class="template-admins-added"></div>
             <input type="text" class="span12 search-template-admins" placeholder="Введите текст">
             <div class="template-admins-available" style="margin-bottom: 25px;"></div>
         </div>
       </div>
     </div>
   </div>
    <div class="modal-footer" style="display: flow-root;">
      <div style="float: left">
          <a id="button-people" class="btn btn-success" type="button" data-field="m"><i class="fa fa-plus icon-white"></i> <span class="hide-name">&nbsp;Добавить</span></a>
          <a id="clear-button-people" class="btn btn-warning" type="button" data-field="m"><i class="fa fa-minus icon-white"></i> <span class="hide-name">&nbsp;Очистить список</span></a>
      </div>
        <button class="btn btn-info btn-handle-template"></button>
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
        <button id="saveAdminsForTemplate" class="btn btn-default" data-dismiss="modal" aria-hidden="true">OK</button>
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
            <input class="span2" type="text" id="searchBlockFilter" placeholder="Введите фамилию" style="margin-top: 5px;">
        </form>
        <div id="addMemberTableHeader">
            <!--<a id="selectAllMembersList" onclick="">Выбрать всех</a>
            <a id="unselectAllMembersList" onclick="">Отменить выбор</a>-->
        </div>
        <div class="membersTable">
            <table class="table table-hover table-condensed">
                <thead><tr><th><input type="checkbox" id="selectAllMembersList">&nbsp;</th><th>Фамилия Имя Отчество</th><th>Местность</th></tr></thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" id="btnDoAddMembersTemplate"><i class="fa fa-check icon-ok icon-white"></i> Добавить выбранных</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Отменить</button>
    </div>
</div>
<script type="text/javascript">
var gloLocalityAgmin = '<?php echo $localitiesWithFilters; ?>';
var gloIsSingleCity = parseInt('<?php echo $isSingleCity; ?>');
var gloSingleLocality = gloIsSingleCity ? '<?php echo $singleLocality; ?>' : '';
</script>

<script src="/js/meetings.js?v133"></script>
<?php
    include_once './footer.php';
?>
