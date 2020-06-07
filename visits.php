<?php
include_once "header.php";
include_once "nav.php";
include_once "modals.php";
include_once "db/visitsdb.php";

$localities = db_getAdminMeetingLocalities ($memberId);
$isSingleCity = db_isSingleCityAdmin($memberId);
$singleLocality = db_getSingleAdminLocality($memberId);
$adminLocality = db_getAdminLocality($memberId);
$categories = db_getCategories();
$listAdminLocality = db_getAdminsListByLocalitiesCombobox($adminLocality);

$selMeetingLocality = isset ($_COOKIE['selMeetingLocality']) ? $_COOKIE['selMeetingLocality'] : '_all_';
$selMeetingCategory = isset ($_COOKIE['selMeetingCategory']) ? $_COOKIE['selMeetingCategory'] : '_all_';

$sort_field = isset ($_SESSION['sort_field-visits']) ? $_SESSION['sort_field-visits'] : 'date_visit';
$sort_type = isset ($_SESSION['sort_type-visits']) ? $_SESSION['sort_type-visits'] : 'asc';
?>

<style>body {padding-top: 60px;}</style>
<div class="container">
    <div id="eventTabs" class="meetings-list">
        <div class="tab-content">
          <select class="controls span4 meeting-lists-combo" tooltip="Выберите нужный вам список здесь">
              <option value="meetings">Собрания</option>
              <option selected value="visits">Посещения и звонки</option>
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
                        <li><a id="sort-act" href="#" title="сортировать">Событие</a>&nbsp;<i class="<?php echo $sort_field=='act' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                        <li><a id="sort-responsible" href="#" title="сортировать">Ответственный</a>&nbsp;<i class="<?php echo $sort_field=='responsible' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i>
                        </li>
                        <li><a id="sort-performed" href="#" title="сортировать">Статус</a>&nbsp;<i class="<?php echo $sort_field=='performed' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i>
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
                  <?php foreach ($listAdminLocality as $id => $name) {
                  echo "<option value='$id'>".htmlspecialchars ($name)."</option>";
                } ?>
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
                        <th style="text-align: left; width:130px;"><a id="sort-performed" href="#" title="сортировать">Статус</a>&nbsp;<i class="<?php echo $sort_field=='performed' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
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
                              <?php foreach ($listAdminLocality as $id => $name) {
                              echo "<option value='$id'>".htmlspecialchars ($name)."</option>";
                            } ?>
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
<script src="/js/visits.js?v122"></script>
<?php
    include_once './footer.php';
?>
