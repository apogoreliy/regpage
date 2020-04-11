<?php
    include_once "header.php";
    include_once "nav.php";
    include_once "db/practicesdb.php";

    /*$hasMemberRightToSeePage = db_isAdmin($memberId);
    if(!in_array('10', db_getUserSettings($memberId)) && !in_array('9', db_getUserSettings($memberId))){
      echo "Location: http://www.reg-page.ru/settings.php";
      exit();
    }*/

    $localities = db_getAdminMeetingLocalities($memberId);
    $isSingleCity = db_isSingleCityAdmin($memberId);
    $adminLocality = db_getAdminLocality($memberId);
    //$bb = db_newDayPractices($memberId);
// COOKIES РАЗОБРАТЬСЯ !!!
//    $someselect = isset ($_COOKIE['someselectcookie']) ? $_COOKIE['someselectcookie'] : '_all_';
    //$sort_field = isset ($_COOKIE['sort_field_statistic']) ? $_COOKIE['sort_field_statistic'] : 'id';
    //$sort_type = isset ($_COOKIE['sort_type_statistic']) ? $_COOKIE['sort_type_statistic'] : 'desc';
    $sort_field = 'id';
    $sort_type = 'desc';
?>
<div class="container">
  <div id="eventTabs" class="meetings-list">
    <div class="tab-content"  style="padding-top: 0px;">
<!-- Botton bar Statistic START -->
      <div class="btn-toolbar">
          <!--<div class="dropdown">
              <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                  <span class="sortName fa fa-list"><?php echo $s = isset($_COOKIE['sort'])? $_COOKIE['sort'] : ' ' ?></span>
                  <span class="caret"></span>
              </button>
              <ul class="dropdown-menu" id="dropdownMenu" aria-labelledby="dropdownMenu1">
                <li><a id="sort-id" href="#" title="сортировать">Поле1</a>&nbsp;<i class="<?php echo $sort_field=='id' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                  <li><a id="sort-city" href="#" title="сортировать">Поле2</a>&nbsp;<i class="<?php echo $sort_field=='city' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                  <li><a id="sort-status" href="#" title="сортировать">Поле3</a>&nbsp;<i class="<?php echo $sort_field=='status' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                  <li><a id="sort-half_year" href="#" title="сортировать">Поле4</a>&nbsp;<i class="<?php echo $sort_field=='half_year' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                  <li><a id="sort-attended" href="#" title="сортировать">Поле5</a>&nbsp;<i class="<?php echo $sort_field=='attended' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                  <li><a id="sort-count_ltmeeting" href="#" title="сортировать">Поле6</a>&nbsp;<i class="<?php echo $sort_field=='count_ltmeeting' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                  <li><a id="sort-completed" href="#" title="сортировать">Поле7</a>&nbsp;<i class="<?php echo $sort_field=='completed' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
              </ul>
          </div>-->
      </div>
<!-- Botton bar Statistic STOP -->
<!-- List Statistic BEGIN -->
      <div class="desctopVisible" id="">
        <ul class="nav nav-tabs" style="margin-top: 10px">
          <li class="" id="whachTab" style="<?php echo in_array('12', db_getUserSettings($memberId)) ? '':'display:none' ?>"><a data-toggle="tab" href="#whach">Наблюдение</a></li>
          <li class="" id="pCountTab" style="<?php echo in_array('9', db_getUserSettings($memberId)) ? '':'display:none' ?>"><a data-toggle="tab" href="#pcount">Личный учёт</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane fade " id="whach">
            <div class="" style="margin-top: 10px;">
              <select class="" name="">
                <option>Последие 7 дней
              </select>
              <select class="" name="">
                <option>Местность
              </select>
              <select id="servingCombo" class="" name="">
                <option value="_all_">Все служащие</option>
                <option value="_none_">Не назначен</option>
                  <?php foreach (db_getServiceonesPvom() as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
              </select>
            </div>
            <table id="listPracticesForObserve" class="table table-hover">
              <thead>
                <tr>
                <th style="text-align: left; min-width:70px"><a id="sort-id" href="#" title="сортировать">ФИО</a>&nbsp;<i class="<?php echo $sort_field=='id' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style=""><a id="sort-city" href="#" title="сортировать">УО</a>&nbsp;<i class="<?php echo $sort_field=='city' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="text-align: left;"><a id="sort-status" href="#" title="сортировать">ЛМ</a>&nbsp;<i class="<?php echo $sort_field=='status' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="text-align: left;"><a id="sort-bptz_half_year" href="#" title="сортировать">МТ</a>&nbsp;<i class="<?php echo $sort_field=='half_year' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="text-align: left;"><a id="sort-attended" href="#" title="сортировать">ЧБ</a>&nbsp;<i class="<?php echo $sort_field=='attended' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="text-align: left;"><a id="sort-count_ltmeeting" href="#" title="сортировать">ЧС</a>&nbsp;<i class="<?php echo $sort_field=='count_ltmeeting' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="text-align: center;"><a id="sort-completed" href="#" title="сортировать">БЛ</a>&nbsp;<i class="<?php echo $sort_field=='completed' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="text-align: center;"><a id="sort-completed" href="#" title="сортировать">Местность</a>&nbsp;<i class="<?php echo $sort_field=='completed' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="text-align: center;"><a id="sort-completed" href="#" title="сортировать">Служащий</a>&nbsp;<i class="<?php echo $sort_field=='completed' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                </tr>
              </thead>
              <tbody><tr><td colspan="8"><h3 style="text-align: center">Страница в процессе разработки.</h3></td></tr></tbody>
            </table>
          </div>
          <div id="pcount" class="tab-pane fade">
            <div class="cd-panel cd-panel--from-right js-cd-panel-main cd-panel--is-visible">
              <header class="cd-panel__header">
                <h3>Основные практики</h3>
                <a href="#0" class="cd-panel__close js-cd-close">Закрыть</a>
              </header>
              <div class="cd-panel__container">
                <div class="cd-panel__content">
         <!-- your side panel content here -->
            <table id="blankTbl">
              <tr><td style="padding-bottom: 10px;" colspan="2"><strong id="dataPractic"></strong></td></tr>
              <tr style="<?php echo in_array('10', db_getUserSettings($memberId)) ? '':'display:none' ?>"><td>Подъём</td><td><input id="timeWakeup" class="span1" type="time" style="width: 80px; text-align: right; margin-left: 10px;"></td></tr>
              <tr><td>Утреннее оживление  </td><td><input id="mrPractic" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> мин.</td></tr>
              <tr><td>Личная молитва  </td><td><input id="ppPractic" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> мин.</td></tr>
              <tr><td>Молитва с товарищем  </td><td><input id="pcPractic" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> мин.</td></tr>
              <tr><td>Чтение Библии  </td><td><input id="rbPractic" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> мин.</td></tr>
              <tr><td>Чтение служения  </td><td><input id="rmPractic" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> мин.</td></tr>
              <tr style="<?php echo in_array('11', db_getUserSettings($memberId)) ? '':'display:none;' ?>"><td>Благовестие  </td><td><input id="gsplPractic" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> мин.</td></tr>
              <tr style="<?php echo in_array('11', db_getUserSettings($memberId)) ? '':'display:none;' ?>"><td>Листовки  </td><td><input id="flPractic" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> шт.</td></tr>
              <tr style="<?php echo in_array('11', db_getUserSettings($memberId)) ? '':'display:none;' ?>"><td>Контакты  </td><td><input id="cntPractic" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> чел.</td></tr>
              <tr style="<?php echo in_array('11', db_getUserSettings($memberId)) ? '':'display:none;' ?>"><td>Спасённые  </td><td><input id="svdPractic" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> чел.</td></tr>
              <tr style="<?php echo in_array('11', db_getUserSettings($memberId)) ? '':'display:none;' ?>"><td>Встречи</td><td><input id="meetPractic" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> чел.</td></tr>
              <tr style="<?php echo in_array('10', db_getUserSettings($memberId)) ? '':'display:none' ?>"><td>Отбой</td><td><input id="timeHangup" class="span1" type="time" style="width: 80px; text-align: right; margin-left: 10px;"></td></tr>
            </table>
            <textarea id="otherDesk" rows="3" cols="80" style="margin-top: 5px; margin-bottom: 15px; width: 280px;"></textarea>
            <br>
            <input id="safePracticesToday" class="btn btn-success" type="button" name="" value="Сохранить" style="margin-right: 30px;">
            <input class="btn btn-default" id="cd-panel__close" type="button" name="" value="Закрыть">
            <hr>
    </div> <!-- cd-panel__content -->
  </div> <!-- cd-panel__container -->
</div> <!-- cd-panel -->
            <div class="">
              <div class="">
                <!--<select class="" name="">
                  <option>Фильтр 1
                </select>
                <select class="" name="">
                  <option>Фильтр 2
                </select>
                <select class="" name="">
                  <option>Фильтр 3
                </select>-->
              </div>
            </div>
            <table id="practicesListPersonal" class="table table-hover">
              <thead>
                <tr>
                <th style="text-align: left;"><a id="sort-id" href="#" title="сортировать">Дата</a>&nbsp;<i class="<?php echo $sort_field=='id' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="<?php echo in_array('10', db_getUserSettings($memberId)) ? '':'display:none' ?>"><a id="" href="#" title="сортировать">Подъём</a>&nbsp;</th>
                <th style=""><a id="sort-city" href="#" title="сортировать">УО</a>&nbsp;<i class="<?php echo $sort_field=='city' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="text-align: left;"><a id="sort-status" href="#" title="сортировать">ЛМ</a>&nbsp;<i class="<?php echo $sort_field=='status' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="text-align: left;"><a id="sort-bptz_half_year" href="#" title="сортировать">МТ</a>&nbsp;<i class="<?php echo $sort_field=='half_year' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="text-align: left;"><a id="sort-attended" href="#" title="сортировать">ЧБ</a>&nbsp;<i class="<?php echo $sort_field=='attended' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="text-align: left;"><a id="sort-count_ltmeeting" href="#" title="сортировать">ЧС</a>&nbsp;<i class="<?php echo $sort_field=='count_ltmeeting' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="<?php echo in_array('11', db_getUserSettings($memberId)) ? 'text-align: left;':'display:none; text-align: left;' ?>"><a id="sort-completed" href="#" title="сортировать">БЛ</a>&nbsp;<i class="<?php echo $sort_field=='completed' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="<?php echo in_array('11', db_getUserSettings($memberId)) ? 'text-align: left;':'display:none; text-align: left;' ?>"><a id="sort-completed" href="#" title="сортировать">Л</a>&nbsp;<i class="<?php echo $sort_field=='completed' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="<?php echo in_array('11', db_getUserSettings($memberId)) ? 'text-align: left;':'display:none; text-align: left;' ?>"><a id="sort-completed" href="#" title="сортировать">К</a>&nbsp;<i class="<?php echo $sort_field=='completed' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="<?php echo in_array('11', db_getUserSettings($memberId)) ? 'text-align: left;':'display:none; text-align: left;' ?>"><a id="sort-completed" href="#" title="сортировать">С</a>&nbsp;<i class="<?php echo $sort_field=='completed' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="<?php echo in_array('11', db_getUserSettings($memberId)) ? 'text-align: left;':'display:none; text-align: left;' ?>"><a id="sort-completed" href="#" title="сортировать">В</a>&nbsp;<i class="<?php echo $sort_field=='completed' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="<?php echo in_array('10', db_getUserSettings($memberId)) ? '':'display:none' ?>"><a id="" href="#" title="сортировать">Отбой</a>&nbsp;</th>
                </tr>
              </thead>
              <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3>
              </td></tr></tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="show-phone" id="">
        <div class="tab-content">
        <ul class="nav nav-tabs">
          <li class="" id="whachTabMbl" style="<?php echo in_array('12', db_getUserSettings($memberId)) ? '':'display:none' ?>"><a data-toggle="tab" href="#whachMbl">Наблюдение</a></li>
          <li class="" id="pCountTabMbl" style="<?php echo in_array('9', db_getUserSettings($memberId)) ? '':'display:none' ?>"><a data-toggle="tab" href="#pcountMbl">Личный учёт</a></li>
        </ul>
        <div id="whachMbl" class="tab-pane fade">
          <table class="table table-hover">
            <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
          </table>
        </div>
        <div id="pcountMbl" class="tab-pane fade overflow-auto">
          <div class="cd-panelMbl cd-panel--from-rightMbl js-cd-panel-mainMbl">
            <header class="cd-panel__headerMbl">
              <h3>Основные практики</h3>
              <span href="#0" class="cd-panel__closeMbl js-cd-closeMbl cursor-pointer">Закрыть</span>
            </header>
            <div class="cd-panel__containerMbl">
              <div class="cd-panel__contentMbl">
          <table>
            <tr><td style="padding-bottom: 10px;"><strong id="dataPracticMbl"></strong></td><td colspan="3" style="padding-bottom: 10px;"></td></tr>
            <tr style="<?php echo in_array('10', db_getUserSettings($memberId)) ? '':'display:none' ?>"><td>Подъём</td><td><input id="timeWakeupMbl" class="span1" type="time" style="width: 80px; text-align: right; margin-left: 10px;"></td></tr>
            <tr><td>Утреннее оживление  </td><td><input id="mrPracticMbl" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"><td>мин.</td></td></tr>
            <tr><td>Личная молитва  </td><td><input id="ppPracticMbl" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"></td><td>мин.</td></tr>
            <tr><td>Молитва с товарищем  </td><td><input id="pcPracticMbl" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"></td><td>мин.</td></tr>
            <tr><td>Чтение Библии  </td><td><input id="rbPracticMbl" class="span1" type="number" style="width: 80px;text-align: right; margin-left: 10px;"></td><td>мин.</td></tr>
            <tr><td>Чтение служения  </td><td><input id="rmPracticMbl" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"></td><td>мин.</td></tr>
            <tr  style="<?php echo in_array('11', db_getUserSettings($memberId)) ? '':'display:none' ?>"><td>Благовестие  </td><td><input id="gsplPracticMbl" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"></td><td>мин.</td></tr>
            <tr  style="<?php echo in_array('11', db_getUserSettings($memberId)) ? '':'display:none' ?>"><td>Листовки  </td><td><input id="flPracticMbl" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"></td><td>шт.</td></tr>
            <tr  style="<?php echo in_array('11', db_getUserSettings($memberId)) ? '':'display:none' ?>"><td>Контакты  </td><td><input id="cntPracticMbl" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> </td><td>чел.</td></tr>
            <tr  style="<?php echo in_array('11', db_getUserSettings($memberId)) ? '':'display:none' ?>"><td>Спасённые  </td><td><input id="svdPracticMbl" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"></td><td>чел.</td></tr>
            <tr  style="<?php echo in_array('11', db_getUserSettings($memberId)) ? '':'display:none' ?>"><td>Встречи</td><td><input id="meetPracticMbl" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"></td><td>чел.</td></tr>
            <tr style="<?php echo in_array('10', db_getUserSettings($memberId)) ? '':'display:none' ?>"><td>Отбой</td><td><input id="timeHangupMbl" class="span1" type="time" style="width: 80px; text-align: right; margin-left: 10px;"></td></tr>
          </table>
          <textarea id="otherMbl" rows="3" cols="80" style="margin-top: 5px; margin-bottom: 15px; width: 280px;"></textarea>
          <br>
          <input id="safePracticesTodayMbl" class="btn btn-success" type="button" name="" value="Сохранить" style="margin-right: 30px;">
          <input id="cd-panel__closeMbl" class="btn btn-default" type="button" name="" value="Закрыть">
        </div>
        </div>
        </div>
          <br><br>
          <div class="">
            <div class="">
              <!--<select class="" name="">
                <option>Фильтр 1
              </select>
              <select class="" name="">
                <option>Фильтр 2
              </select>
              <select class="" name="">
                <option>Фильтр 3
              </select>-->
            </div>
          </div>
          <div id="practicesListPersonalMbl" class="table table-hover">
            <div><h3 style="text-align: center">Загрузка...</h3></div>
          </div>
        </div>
        </div>
      </div>
<!-- List Statistic STOP -->
    </div>
  </div>
</div>

    <script>
      var data_page = {};
      var adminLocalityGlb = '<?php echo $adminLocality; ?>';
      var settingOff = '<?php echo (!in_array('12', db_getUserSettings($memberId)) && !in_array('9', db_getUserSettings($memberId)));?>';
      var settingOn = '<?php echo (in_array('12', db_getUserSettings($memberId)) && in_array('9', db_getUserSettings($memberId)));?>';
      var wakeupOn = '<?php echo in_array('10', db_getUserSettings($memberId));?>';
      var gospelOn = '<?php echo in_array('11', db_getUserSettings($memberId));?>';
      var localityList = '<?php foreach (db_getLocalities() as $id => $name) echo $id.'_'.$name.'_'; ?>';
      var localityListTmp = localityList ? localityList.split('_') : [];
      localityList =[];
      data_page.locality = [];
      for (var i = 0; i < localityListTmp.length; i = i + 2) {
        if (localityListTmp[i+1]) {
          data_page.locality[String(localityListTmp[i])] = localityListTmp[i+1];
        }
      }
      localityListTmp = [];

      data_page.serviceones = [];
      var serving_ones = '<?php foreach (db_getServiceonesPvom() as $id => $name) echo $id.'_'.$name.'_'; ?>';
      var serving_onesTmp = serving_ones ? serving_ones.split('_') : [];
      serving_ones=[];
      for (var i = 0; i < serving_onesTmp.length; i = i + 2) {
        if (serving_onesTmp[i+1]) {
          data_page.serviceones[String(serving_onesTmp[i])] = serving_onesTmp[i+1];
        }
      }
      serving_onesTmp=[];

    </script>
    <script src="/js/practices.js?v4"></script>
<?php
    include_once "footer.php";
?>
