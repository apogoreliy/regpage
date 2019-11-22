<?php
    include_once "header.php";
    include_once "nav.php";

    $hasMemberRightToSeePage = db_isAdmin($memberId);
    if(!$hasMemberRightToSeePage){
        die();
    }
    $localityStatus = db_getLocalitiesStatus();
    $localities = db_getAdminMeetingLocalities($memberId);
    $isSingleCity = db_isSingleCityAdmin($memberId);
    $adminLocality = db_getAdminLocality($memberId);
    $periodActual = db_getPeriodActual();
    $allPeriods = db_getAllPeriods();
    $periodInterval = db_getPeriodInterval();
// КУККИ УСТАНОВЛЕНЫ В ПРИХЕДЕРЕ!
    $selStatisticLocality = isset ($_COOKIE['selStatisticLocality']) ? $_COOKIE['selStatisticLocality'] : '_all_';
    $sort_field = isset ($_SESSION['sort_field-statistic']) ? $_SESSION['sort_field-statistic'] : 'city';
    $sort_type = isset ($_SESSION['sort_type-statistic']) ? $_SESSION['sort_type-statistic'] : 'asc';
?>
<div class="container">
  <div id="eventTabs" class="meetings-list">
    <div class="tab-content">
<!-- Botton bar Statistic START -->
      <div class="btn-toolbar">
          <a class="btn btn-success add-statistic" type="button"><i class="fa fa-plus icon-white"></i><span class="hide-name"> Добавить</span></a>
          <div class="dropdown">
              <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                  <span class="sortName fa fa-list"><?php echo $s = isset($_COOKIE['sort'])? $_COOKIE['sort'] : ' ' ?></span>
                  <span class="caret"></span>
              </button>
              <ul class="dropdown-menu" id="dropdownMenu" aria-labelledby="dropdownMenu1">
                <li><a id="sort-id" href="#" title="сортировать">Код</a>&nbsp;<i class="<?php echo $sort_field=='id' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                  <li><a id="sort-city" href="#" title="сортировать">Город</a>&nbsp;<i class="<?php echo $sort_field=='city' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                  <li><a id="sort-status" href="#" title="сортировать">Статус</a>&nbsp;<i class="<?php echo $sort_field=='status' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                  <li>
                      <?php
                      if (!$isSingleCity)
                          echo '<th><a id="sort-locality_key" href="#" title="сортировать">Местность (район)</a>&nbsp;<i class="'.($sort_field=='locality_key' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none').'"></i></th>';
                      ?>
                  </li>
                  <li><a id="sort-half_year" href="#" title="сортировать">Крещены за полгода</a>&nbsp;<i class="<?php echo $sort_field=='half_year' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                  <li><a id="sort-attended" href="#" title="сортировать">Посещают собрания</a>&nbsp;<i class="<?php echo $sort_field=='attended' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                  <li><a id="sort-count_ltmeeting" href="#" title="сортировать">На трапезе</a>&nbsp;<i class="<?php echo $sort_field=='count_ltmeeting' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                  <li><a id="sort-completed" href="#" title="сортировать">Заполнено</a>&nbsp;<i class="<?php echo $sort_field=='completed' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
              </ul>
          </div>
          <select id="fulfilledBlank" class="span2" style="margin-right: 10px;">
              <option value="_all_" selected>Все бланки</option>
              <option value="1">Заполненные</option>
              <option value="0">Не заполненные</option>
          </select>

          <?php if (!$isSingleCity) { ?>
          <select id="selStatisticLocality" class="span3" style="margin-right: 10px;">
              <option value='_all_' <?php echo $selStatisticLocality =='_all_' ? 'selected' : '' ?> >Все местности (районы)</option>
              <?php
                  foreach ($localities as $id => $name) {
                      echo "<option value='$id' ". ($id==$selStatisticLocality ? 'selected' : '') ." >".htmlspecialchars ($name)."</option>";
                  }
              ?>
          </select>
          <?php } ?>

          <select class="custom-select" multiple id="arhivePeriods" style="width: 255px;" size="3">
            <?php
                foreach ($allPeriods as $key => $name) {
                    echo "<option value='$key'>".$key.'  ('.htmlspecialchars ($name).")</option>";
                }
            ?>
          </select>

          <div id="blanksArchive" class="" data-id="<?php echo $periodActual; ?>" data-period="<?php echo $periodInterval; ?>">

          </div>
      </div>
<!-- Botton bar Statistic STOP -->
<!-- List Statistic BEGIN -->
      <div class="desctopVisible" id="statisticList">
        <table id="" class="table table-hover">
          <thead>
            <tr>
                <th style="text-align: left; min-width:70px"><a id="sort-id" href="#" title="сортировать">Код</a>&nbsp;<i class="<?php echo $sort_field=='id' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style=""><a id="sort-city" href="#" title="сортировать">Город</a>&nbsp;<i class="<?php echo $sort_field=='city' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <?php/*
                if (!$isSingleCity)
                    echo '<th><a id="sort-locality_key" href="#" title="сортировать">Местность (район)</a>&nbsp;<i class="'.($sort_field=='locality_key' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none').'"></i></th>';
                    */?>
                <th style="text-align: left; min-width:70px"><a id="sort-status" href="#" title="сортировать">Статус</a>&nbsp;<i class="<?php echo $sort_field=='status' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="text-align: left;"><a id="sort-bptz_half_year" href="#" title="сортировать">Крещены за полгода</a>&nbsp;<i class="<?php echo $sort_field=='half_year' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="text-align: left;"><a id="sort-attended" href="#" title="сортировать">Посещают собрания</a>&nbsp;<i class="<?php echo $sort_field=='attended' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="text-align: left;"><a id="sort-count_ltmeeting" href="#" title="сортировать">Количество на трапезе</a>&nbsp;<i class="<?php echo $sort_field=='count_ltmeeting' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="text-align: center;"><a id="sort-completed" href="#" title="сортировать">Заполнено</a>&nbsp;<i class="<?php echo $sort_field=='completed' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
            </tr>
          </thead>
          <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
        </table>
      </div>
      <div class="show-phone" id="statisticListMbl">
        <table id="meetingsPhone" class="table table-hover">
          <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
        </table>
      </div>
<!-- List Statistic STOP -->
    </div>
  </div>
</div>

<!-- ADD | EDIT STATISTIC Modal -->
<div id="addEditStatisticModal" class="modal hide fade" data-width="500" tabindex="-1" role="dialog" aria-labelledby="regNameEdit" aria-hidden="true" data-status_val="" data-author="" data-archive="" data-period_start="" data-period_end="" data-id_statistic="">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 id="">Статистика <span id="periodId"></span></h4><strong style="float: right" id="adminShortName"></strong>
        <h5 id="">Период <span id="periodDate"></span></h5>
    </div>
    <div class="modal-body" style="height:auto !important">
        <div class="desctop-visible tablets-visible">
            <div class="control-group row-fluid">
                <div style="margin-bottom: 5px">
                  <select id="statisticLocalityModal">
                      <?php
                          foreach ($localities as $id => $name) {
                              echo "<option value='$id' ". ($id == $singleLocality || $isSingleCity ? 'selected="selected"' :   '') ." >".htmlspecialchars ($name)."</option>";
                              }
                              ?>
                  </select>
                  <select id="localityStatus" class="col-sm">
                    <?php
                      foreach ($localityStatus as $id => $name) {
                        echo "<option value='$id'>$name</option>";
                      }
                      ?>
                  </select>
                </div>
          </div>
          <div class="control-group row-fluid" id="desctopModalStatisticsBlank">
            <table class="table table-hover">
              <thead>
                <th style="text-align: center; vertical-align: middle"><a class="confirmFulfill">Заполнить из списка</a></th>
                <th style="text-align: center; vertical-align: middle"></th>
                <th style="text-align: center; vertical-align: middle"></th>
                <th style="text-align: center; vertical-align: middle">Всего</th>
                <th style="text-align: center; vertical-align: middle">В том числе школьников</th>
              </thead>
              <tbody>
                <tr>
                  <td style="text-align: center; vertical-align: middle">Крещены за полгода</td>
                  <td></td>
                  <td></td>
                  <td style="text-align: center; vertical-align: middle"><input type="number"  id="bptzAll" class="span10 check_valid_field field_desktop_mdl" min="0" data-name="Крещено"></td>
                  <td style="text-align: center; vertical-align: middle"><input type="number"  id="bptz17" class="span10 check_valid_field field_desktop_mdl" min="0" data-name="Крещено школьников"></td>
                  <!--<td style="text-align: center; vertical-align: middle"><input type="number"  id="bptz17_25" class="span10" min="0"></td>
                  <td style="text-align: center; vertical-align: middle"><input type="number"  id="bptz25" class="span10" min="0"></td>
                  -->
                </tr>
                <tr>
                  <td style="text-align: center; vertical-align: middle">Посещают собрания 12-17 лет</td>
                  <td style="text-align: center; vertical-align: middle">Посещают собрания 18-25 лет</td>
                  <td style="text-align: center; vertical-align: middle">Посещают собрания 26-60 лет</td>
                  <td style="text-align: center; vertical-align: middle">Посещают собрания старше 60 лет</td>
                  <td style="text-align: center; vertical-align: middle">Посещают собрания всего</td>
                </tr>
                <tr>
                  <td style="text-align: center; vertical-align: middle"><input type="number" id="attended17" class="span10 check_valid_field field_desktop_mdl" min="0" data-name="Посещают собрания 12-17 лет"></td>
                  <td style="text-align: center; vertical-align: middle"><input type="number"  id="attended17_25" class="span10 check_valid_field field_desktop_mdl" min="0" data-name="Посещают собрания 18-25 лет"></td>
                  <td style="text-align: center; vertical-align: middle"><input type="number"  id="attended25" class="span10 check_valid_field field_desktop_mdl" min="0" data-name="Посещают собрания 26-60 лет"></td>
                  <td style="text-align: center; vertical-align: middle"><input type="number"  id="attended60" class="span10 check_valid_field field_desktop_mdl" min="0" data-name="Посещают собрания старше 60 лет"></td>
                  <td style="text-align: center; vertical-align: middle"><input type="number"  id="attendedAll" class="span10 check_valid_field field_desktop_mdl" min="0" data-name="Посещают собрания всего"></td>
                </tr>
                <tr>
                  <td colspan="4" style="text-align: right; vertical-align: middle">В среднем на трапезе</td>
                  <td style="text-align: center; vertical-align: middle"><input data-toggle="tooltip" title="Заполняется вручную!" type="number"  id="ltMeetingAverage" class="span10 check_valid_field field_desktop_mdl" min="0"></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="" id="mblModalStatisticsBlank">
            <div>
              <div>
                <div style="text-align: center; vertical-align: middle"><a class="confirmFulfill">Заполнить из списка</a></div>
                <div style="text-align: center; vertical-align: middle">Крещены за полгода</div>
                <div style="text-align: center; vertical-align: middle">Всего</div>
                  <div style="text-align: center; vertical-align: middle"><input type="number"  id="bptzAllmbl" class="span10 check_valid_field field_mobile_mdl" min="0" data-name="Крещено"></div>
                <div style="text-align: center; vertical-align: middle">В том числе школьников</div>
                <div style="text-align: center; vertical-align: middle"><input type="number"  id="bptz17mbl" class="span10 check_valid_field field_mobile_mdl" min="0" data-name="Крещено школьников"></div>
              </div>
              <div>
                <div>
                  <div style="text-align: center; vertical-align: middle">Посещают собрания 12-17 лет</div>
                  <div style="text-align: center; vertical-align: middle"><input type="number" id="attended17mbl" class="span10 check_valid_field field_mobile_mdl" min="0" data-name="Посещают собрания 12-17 лет"></div>
                  <div style="text-align: center; vertical-align: middle">Посещают собрания 18-25 лет</div>
                  <div style="text-align: center; vertical-align: middle"><input type="number"  id="attended17_25mbl" class="span10 check_valid_field field_mobile_mdl" min="0" data-name="Посещают собрания 18-25 лет"></div>
                  <div style="text-align: center; vertical-align: middle">Посещают собрания 26-60 лет</div>
                  <div style="text-align: center; vertical-align: middle"><input type="number"  id="attended25mbl" class="span10 check_valid_field field_mobile_mdl" min="0" data-name="Посещают собрания 26-60 лет"></div>
                  <div style="text-align: center; vertical-align: middle">Посещают собрания старше 60 лет</div>
                  <div style="text-align: center; vertical-align: middle"><input type="number"  id="attended60mbl" class="span10 check_valid_field field_mobile_mdl" min="0" data-name="Посещают собрания старше 60 лет"></div>
                  <div style="text-align: center; vertical-align: middle">Посещают собрания всего</div>
                  <div style="text-align: center; vertical-align: middle"><input type="number"  id="attendedAllmbl" class="span10 check_valid_field field_mobile_mdl" min="0" data-name="Посещают собрания всего"></div>
                </div>
                <div>
                  <div colspan="4" style="text-align: right; vertical-align: middle">В среднем на трапезе</div>
                  <div style="text-align: center; vertical-align: middle"><input data-toggle="tooltip" title="Заполняется вручную!" type="number"  id="ltMeetingAveragembl" class="span10 check_valid_field field_mobile_mdl" min="0"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="control-group row-fluid">
                <textarea id="comment" class="span12" placeholder="Поле для комментария" maxlength="500" ></textarea>
          </div>
        </div>
        <a href="#" id="btnDoDeleteStatistic">Удалить бланк статистики</a>
    </div>
    <div class="modal-footer">
        <label class="form-check-label" for="statisticCompleteChkbox" style="float: left"><input type="checkbox" id="statisticCompleteChkbox" class="" style="margin-bottom: 3px"> статистика заполнена</label>
        <button class="btn btn-info btnDoHandleStatistic">Сохранить</button>
        <button class="btn" id="cancelModalWindow" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>

<!-- YES | NO AUTOFULFILL Modal -->
<div id="autoFulfillModal" class="modal hide fade" data-width="400" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true" data-status_val="" data-author="" data-archive="" data-periods="" data-id_statistic="">
    <div class="modal-header" style="height: 20px">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    </div>
    <div class="modal-body"style="height: 120px !important">
        <div class="desctop-visible tablets-visible">
          <div class="control-group row-fluid">
            <div style="margin-bottom: 5px">
              <p class="text-center">
                <strong>На странице “Список” должны быть отмечены:</strong><br>
                все, кто посещает собрания, и у них<br>
                должна быть указана дата рождения.<br>
                У крещённых школьников должна быть<br>
                указана дата крещения.<br>
                Эти условия выполнены?
              </p>
            </div>
          </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-info btnDoHandleFulfillStatistic">Да</button>
        <button class="btn" id="cancelModalWindow" data-dismiss="modal" aria-hidden="true">Нет</button>
    </div>
</div>
<!-- END HTML BODY -->

<!-- YES | NO AUTOFULFILL Modal -->
<div id="deleteStatisticBlankConfirm" class="modal hide fade" data-width="400" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true" data-status_val="" data-author="" data-archive="" data-periods="" data-id_statistic="">
    <div class="modal-header" style="height: 20px">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    </div>
    <div class="modal-body"style="height: 40px !important">
        <div class="desctop-visible tablets-visible">
          <div class="control-group row-fluid">
            <div style="margin-bottom: 5px">
              <p class="text-center">
                <strong>Удалить бланк статистики?</strong>
              </p>
            </div>
          </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-danger btnDoConfirmDeleteStatistic">Удалить</button>
        <button class="btn" id="cancelModalWindow" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>
<!-- END HTML BODY -->

    <script>
var adminLocalityGlb = '<?php echo $adminLocality; ?>';
    </script>
    <script src="/js/statistic.js?v1"></script>
<?php
    include_once "footer.php";
?>
