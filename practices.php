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
          <div id="whach" class="tab-pane fade" >
            <div class="cd-panel-watch cd-panel--from-right-watch js-cd-panel-main-watch">
              <div class="cd-panel__container-watch">
                <header class="cd-panel__header-watch">
                  <h3>Основные практики </h3>
                  <a href="#0" class="cd-panel__close-watch js-cd-close-watch"> Закрыть</a>
                </header>
                <div class="cd-panel__content-watch">
                  <div style="height: 100px">

                  </div>
         <!-- your side panel content here -->
            <table id="blankTblWatch">
              <tr><td style="padding-bottom: 10px;" colspan="2"><strong id="dataPractic-watch"></strong></td></tr>
              <tr style=""><td>Подъём</td><td><input id="timeWakeup-watch" class="span1" type="time" style="width: 80px; text-align: right; margin-left: 10px;"></td></tr>
              <tr><td>Утреннее оживление  </td><td><input id="mrPractic-watch" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> мин.</td></tr>
              <tr><td>Личная молитва  </td><td><input id="ppPractic-watch" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> мин.</td></tr>
              <tr><td>Молитва с товарищем  </td><td><input id="pcPractic-watch" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> мин.</td></tr>
              <tr><td>Чтение Библии  </td><td><input id="rbPractic-watch" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> мин.</td></tr>
              <tr><td>Чтение служения  </td><td><input id="rmPractic-watch" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> мин.</td></tr>
              <tr style=""><td>Благовестие  </td><td><input id="gsplPractic-watch" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> мин.</td></tr>
              <tr style=""><td>Листовки  </td><td><input id="flPractic-watch" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> шт.</td></tr>
              <tr style=""><td>Контакты  </td><td><input id="cntPractic-watch" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> чел.</td></tr>
              <tr style=""><td>Спасённые  </td><td><input id="svdPractic-watch" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> чел.</td></tr>
              <tr style=""><td>Встречи</td><td><input id="meetPractic-watch" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"> чел.</td></tr>
              <tr style=""><td>Отбой</td><td><input id="timeHangup-watch" class="span1" type="time" style="width: 80px; text-align: right; margin-left: 10px;"></td></tr>
            </table>
            <textarea id="otherDesk-watch" rows="3" cols="80" style="margin-top: 5px; margin-bottom: 15px; width: 280px;"></textarea>
            <br>
            <input id="safePracticesToday-watch" class="btn btn-success" type="button" name="" data-id_member="" data-id="" value="Сохранить" style="margin-right: 30px;">
            <input class="btn btn-default" id="cd-panel__close-watch" type="button" name="" value="Закрыть">
            <hr>
    </div> <!-- cd-panel__content -->
  </div> <!-- cd-panel__container -->
</div> <!-- cd-panel -->
            <div class="" style="margin-top: 10px;">
              <select id="periodPractices" class="" name="">
                <option value="7">Последие 7 дней
                <option value="30">Последие 30 дней
              </select>
              <select id="adminlocalitiesCombo" class="" name="" <?php echo in_array('13', db_getUserSettings($memberId)) ? '':'style="display:none"';?>>
                <option value="_all_">Все</option>
                <?php foreach ($localities as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
              </select>
              <select id="servingCombo" class="" name="">
                <option value="_all_">Все служащие</option>
                  <?php foreach (db_getServiceonesPvom() as $id => $name) echo "<option value='$id'" .($id === $memberId ? 'selected' : '').">".htmlspecialchars ($name)."</option>"; ?>
              </select>
            </div>
            <table id="listPracticesForObserve" class="table table-hover">
              <thead>
                <tr>
                <th style="text-align: left; min-width:70px"><a id="sort-id" href="#" title="сортировать">ФИО</a>&nbsp;<i class="<?php echo $sort_field=='id' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="">Период</th>
                <th style="">УО</th>
                <th style="text-align: left;">ЛМ</th>
                <th style="text-align: left;">МТ</th>
                <th style="text-align: left;">ЧБ</th>
                <th style="text-align: left;">ЧС</th>
                <th style="text-align: left;">БЛ</th>
                <th style="text-align: left; <?php echo in_array('13', db_getUserSettings($memberId)) ? '':'display:none';?>">Местность</th>
                <th style="text-align: left;"><a id="sort-completed" href="#" title="сортировать">Служащий</a>&nbsp;<i class="<?php echo $sort_field=='completed' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                </tr>
              </thead>
              <tbody><tr><td colspan="8"><h3 style="text-align: center">Нет данных.</h3></td></tr></tbody>
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
          <div class="cd-panel-watch-mbl cd-panel--from-right-watch-mbl js-cd-panel-main-watch-mbl">
            <div class="cd-panel__container-watch-mbl">
              <header class="cd-panel__header-watch-mbl">
                <h3>Основные практики</h3>
                <a href="#0" class="cd-panel__close-watch-mbl js-cd-close-watch-mbl">Закрыть</a>
              </header>
              <div class="cd-panel__content-watch-mbl">
                <div style="height: 100px">

                </div>
       <!-- your side panel content here -->
          <table id="blankTblWatch-mbl">
            <tr><td style="padding-bottom: 10px;" colspan="2"><strong id="dataPractic-watch-mbl"></strong></td></tr>
            <tr style=""><td>Подъём</td><td><input id="timeWakeup-watch-mbl" class="span1" type="time" style="width: 80px; text-align: right; margin-left: 10px;"></td></tr>
            <tr><td>Утреннее оживление  </td><td><input id="mrPractic-watch-mbl" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"><td> мин.</td></tr>
            <tr><td>Личная молитва  </td><td><input id="ppPractic-watch-mbl" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"></td><td> мин.</td></tr>
            <tr><td>Молитва с товарищем  </td><td><input id="pcPractic-watch-mbl" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"></td><td> мин.</td></tr>
            <tr><td>Чтение Библии  </td><td><input id="rbPractic-watch-mbl" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"></td><td> мин.</td></tr>
            <tr><td>Чтение служения  </td><td><input id="rmPractic-watch-mbl" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"></td><td> мин.</td></tr>
            <tr style=""><td>Благовестие  </td><td><input id="gsplPractic-watch-mbl" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"></td><td> мин.</td></tr>
            <tr style=""><td>Листовки  </td><td><input id="flPractic-watch-mbl" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"></td><td> шт.</td></tr>
            <tr style=""><td>Контакты  </td><td><input id="cntPractic-watch-mbl" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"></td><td> чел.</td></tr>
            <tr style=""><td>Спасённые  </td><td><input id="svdPractic-watch-mbl" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"></td><td> чел.</td></tr>
            <tr style=""><td>Встречи</td><td><input id="meetPractic-watch-mbl" class="span1" type="number" style="width: 80px; text-align: right; margin-left: 10px;"></td><td> чел.</td></tr>
            <tr style=""><td>Отбой</td><td><input id="timeHangup-watch-mbl" class="span1" type="time" style="width: 80px; text-align: right; margin-left: 10px;"></td></tr>
          </table>
          <textarea id="otherDesk-watch-mbl" rows="3" cols="80" style="margin-top: 5px; margin-bottom: 15px; width: 280px;"></textarea>
          <br>
          <input id="safePracticesToday-watch-mbl" class="btn btn-success" type="button" name="" data-id_member="" data-id="" value="Сохранить" style="margin-right: 30px;">
          <input class="btn btn-default" id="cd-panel__close-watch-mbl" type="button" name="" value="Закрыть">
          <hr>
  </div> <!-- cd-panel__content -->
</div> <!-- cd-panel__container -->
</div> <!-- cd-panel -->
          <div class="" style="margin-top: 10px;">
            <select id="periodPracticesMbl" class="" name="">
              <option value="7">Последие 7 дней
              <option value="30">Последие 30 дней
            </select>
            <select id="adminlocalitiesComboMbl" class="" name="" <?php echo in_array('13', db_getUserSettings($memberId)) ? '':'style="display:none"';?>>
              <option value="_all_">Все</option>
              <?php foreach ($localities as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
            </select>
            <select id="servingComboMbl" class="" name="">
              <option value="_all_">Все служащие</option>
                <?php foreach (db_getServiceonesPvom() as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
            </select>
          </div>
            <table id="listPracticesForObserveMbl" class="table table-hover">
              <thead>
                <tr>
                <th style="text-align: left; width:100px"><a id="sort-id" href="#" title="сортировать">ФИО</a>&nbsp;<i class="<?php echo $sort_field=='id' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                <th style="width: 25px;">УО</th>
                <th style="text-align: left; width: 25px;">ЛМ</th>
                <th style="text-align: left; width: 25px;">МТ</th>
                <th style="text-align: left; width: 25px;">ЧБ</th>
                <th style="text-align: left; width: 25px;">ЧС</th>
                <th style="text-align: left; width: 25px;">БЛ</th>
                <th style=" width: 25px; text-align: left; <?php echo in_array('13', db_getUserSettings($memberId)) ? '':'display:none';?>">Местность</th>
                </tr>
              </thead>
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
      data_page.admin_locality = '<?php echo $adminLocality; ?>';
      var settingOff = '<?php echo (!in_array('12', db_getUserSettings($memberId)) && !in_array('9', db_getUserSettings($memberId)));?>';
      var settingOn = '<?php echo (in_array('12', db_getUserSettings($memberId)) && in_array('9', db_getUserSettings($memberId)));?>';
      data_page.option_practices_count = '<?php echo in_array('9', db_getUserSettings($memberId));?>';
      data_page.option_practices_watch = '<?php echo in_array('12', db_getUserSettings($memberId));?>';
      var wakeupOn = '<?php echo in_array('10', db_getUserSettings($memberId));?>';
      var gospelOn = '<?php echo in_array('11', db_getUserSettings($memberId));?>';
      var globalLocalityOn = '<?php echo in_array('13', db_getUserSettings($memberId));?>';
      var localityListGlb = '<?php foreach (db_getLocalities() as $id => $name) echo $id.'_'.$name.'_'; ?>';
      var localityListTmp = localityListGlb ? localityListGlb.split('_') : [];
      localityListGlb =[];
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


      data_page.admin_localities = [];
      var admin_localities = '<?php foreach ($localities as $id => $name) echo $id.'_'.$name.'_'; ?>';
      var admin_localitiesTmp = admin_localities ? admin_localities.split('_') : [];
      admin_localities=[];
      for (var i = 0; i < admin_localitiesTmp.length; i = i + 2) {
        if (admin_localitiesTmp[i+1]) {
          data_page.admin_localities[String(admin_localitiesTmp[i])] = admin_localitiesTmp[i+1];
        }
      }
      admin_localitiesTmp=[];


    </script>
    <script src="/js/practices.js?v4"></script>
<?php
    include_once "footer.php";
?>
