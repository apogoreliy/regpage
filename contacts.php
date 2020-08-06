<?php
    include_once "header2.php";
    include_once "nav2.php";
    include_once 'logWriter.php';
    include_once "db/contactsdb.php";

    /*$hasMemberRightToSeePage = db_isAdmin($memberId);
    if(!in_array('10', db_getUserSettings($memberId)) && !in_array('9', db_getUserSettings($memberId))){
      echo "Location: http://www.reg-page.ru/settings.php";
      exit();
    }*/
    $bellOn = $_COOKIE['sort_new'] == 'sort_new' ? $_COOKIE['sort_new'] : '';
    $localities = db_getAdminMeetingLocalities($memberId);
    $isSingleCity = db_isSingleCityAdmin($memberId);
    $adminLocality = db_getAdminLocality($memberId);
    $adminRole = db_getAdminRole($memberId);
    $adminRole === 0 ? $isAdminZero = 'style="display: none;' : '';
    $membersForCombobox = db_getAdminMembersAdmins($memberId);
    $listAdmins = db_getMemberListAdminsForContacts();

    function shortNameMember ($fullName='')
      {
        if ($fullName) {
          $pieces = explode(" ", $fullName);
          $shortName = $pieces[0].' '.$pieces[1];
          return $shortName;
        }
      }


    //$bb = db_newDayPractices($memberId);
// COOKIES РАЗОБРАТЬСЯ !!!
//    $someselect = isset ($_COOKIE['someselectcookie']) ? $_COOKIE['someselectcookie'] : '_all_';
    //$sort_field = isset ($_COOKIE['sort_field_statistic']) ? $_COOKIE['sort_field_statistic'] : 'id';
    //$sort_type = isset ($_COOKIE['sort_type_statistic']) ? $_COOKIE['sort_type_statistic'] : 'desc';
    $sort_field = 'id';
    $sort_type = 'desc';
?>
<div style="background-color: white; margin-left: auto; margin-right: auto; max-width: 1170px; padding-bottom: 20px">
  <div class="" style="background-color: #eee; margin-left: auto; margin-right: auto; height: 60px; max-width: 1170px">

  </div>
<div class="container">
  <span id="saveSpinner" style="position: fixed; z-index: 1000; margin: 30% 50%; width: 3rem; height: 3rem;" class=" spinner-border text-primary"></span>
<!-- Botton bar Statistic START -->
  <div class="row contactsBtnsBar" style="" id="contactsBtnsBar">
    <div class="" style="max-width:625px; min-width:300px; padding-right: 5px; padding-left: 0;">
        <button id="addContact" class="btn btn-success btn-sm" type="button" title="Добавить новый контакт"><i class="fa fa-plus"></i></button>
        <button id="openUploadModal" class="btn btn-primary btn-sm" title="Загрузить контакты из файла" type="button" data-toggle="modal" data-target="#modalUploadItems"><i class="fa fa-upload"></i></button>
        <button id="deleteContactsShowModal" class="btn btn-danger btn-sm" title="Удалить выбранные контакты" type="button" data-toggle="modal" data-target="#deleteContactsModal" disabled ><i class="fa fa-trash"></i></button>
        <button id="appointResponsibleShow" style="background-color: #ff8c00; color: #fff" class="btn btn-warning btn-sm" type="button" title="Передать выбранные контакты" disabled><i class="fa fa-exchange" aria-hidden="true"></i> Передать</button>
        <button id="appointStatusShow" class="btn btn-secondary btn-sm" type="button" title="Задать статус выбранным контакты" data-toggle="modal" data-target="#statusContactsModal" disabled>Статус</button>
    </div>
    <div class="" style="padding-left: 0; padding-right: 10px; display: <?php if($adminRole === 0) echo 'none'?>">
            <select id="respShow" class="form-control form-control-sm" name="" style="width:180px;">
              <option value="_all_">Все ответственные</option>
              <?php if($adminRole !== 0) foreach ($membersForCombobox as $id => $name) echo "<option value='$id'>".htmlspecialchars (shortNameMember($name['name']))."</option>"; ?>
            </select>
    </div>
    <div class="" style="padding-left: 0; padding-right: 10px;">
            <select id="statusShow" class="form-control form-control-sm" name="">
              <option value="_all_">Все статусы</option>
              <option value="7">В работе</option>
              <option value="1">Недозвон</option>
              <option value="2">Ошибка</option>
              <option value="3">Отказ</option>
              <option value="4">Заказ</option>
              <option value="5">Продолжение</option>
              <option value="6">Завершение</option>
            </select>
    </div>
    <div class="" style="padding-left: 0; padding-right: 10px;">
            <select id="maleShow" class="form-control form-control-sm" name="">
              <option value="_all_">Все</option>
              <option value="1">муж.</option>
              <option value="0">жен.</option>
            </select>
    </div>

    <div class="" style="padding-left: 0; padding-right: 10px;">
            <select id="myBlanks" class="form-control form-control-sm" name="">
              <?php if($adminRole !== 0) {?>
                <option value="_all_">Все</option>
                <?php }; ?>
              <option value="1" selected>Мои контакты</option>
              <option value="0">Переданные</option>
            </select>
    </div>

    <div class="" style="padding-left: 0; padding-right: 10px;">
      <div class="input-group mb-3" style="margin-bottom: 0px!important;">
          <input type="search" id="search-text" class="form-control form-control-sm" name="search-text"  style="width:100px;" placeholder="Поиск">
          <div class="input-group-append">
            <button id="searchBtn" class="btn btn-secondary btn-sm" type="submit"><i class="fa fa-search"></i></button>
          </div>
        </div>
    </div>
    <div style="text-align: left; margin-left: 10px; margin-top: 8px" id="selectAllChekboxMblShow"><label class="form-check-label font-weight-normal"><input id="" type="checkbox" class="checkAllStrings" name="" value=""> Выбрать все</label></div>
<!-- Dropdown Menu START-->
    <div id="dropdownMenuContacts" class="dropdown" style="padding-top: 4px; margin-left: 10px; display: none;">
        <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenuContacts" data-toggle="dropdown">
            <span class="sortName">Сортировать</span>
        </button>
        <div class="dropdown-menu">
          <span class="dropdown-item"><a id="sort-name" href="#" title="сортировать">ФИО </a><i class="fa fa-caret-down"></i></span>
          <span class="dropdown-item"><a id="sort-locality" href="#" title="сортировать">Местность </a><i class="fa icon_none"></i></span>
        </div>
    </div>
<!-- Dropdown Menu STOP-->
  </div>
<!-- Botton bar Statistic STOP -->
<!-- List Statistic BEGIN -->
      <div class="" id="desctop_visible" style="margin-top: 60px;">
        <div id="" class="">
          <div class="tab-pane">
            <a href="#0" class="cd-panel__close-watch js-cd-close-watch">Закрыть</a>
            <div class="cd-panel-watch cd-panel--from-right-watch js-cd-panel-main-watch">
              <div class="cd-panel__container-watch">
                <div id="sideBarBlankContact" class="cd-panel__content-watch">
         <!-- your side panel content here -->
            <div style="height:40px; margin-left: -10px; margin-bottom: 18px;">
            </div>
            <header class="cd-panel__header-watch">
            </header>
              <div class="row">
                <div class="col-12" style="padding-left: 9px;">
                  <label for="" class="required-for-label">ФИО</label>
                  <input type="text" class="form-control form-control-sm" id="nameContact" placeholder="">
                </div>
              </div>
              <div class="row" style="margin-top: 10px;">
                <div class="col-6" style="padding-right: 5px; padding-left: 9px;">
                  <label for="">Телефон</label>
                  <input type="text" class="form-control form-control-sm" id="phoneContact" placeholder="">
                </div>
                <div class="col-6" style="padding-left: 5px;">
                  <label for="">Email</label>
                  <input type="email" class="form-control form-control-sm" id="emailContact" placeholder="">
                </div>
              </div>

            <ul class="nav nav-tabs" style="margin-top: 10px; margin-left: -5px;">
              <li class="nav-item" id="personalBlank" style=""><a class="nav-link active" data-toggle="tab" href="#personalBlankTab" data-id="">Данные</a></li>
              <li class="nav-item" id="blankComment" style=""><a class="nav-link" data-toggle="tab" href="#blankCommentTab">Комментарий</a></li>
              <li class="nav-item" id="blankHistory" style=""><a class="nav-link" data-toggle="tab" href="#blankHistoryTab">История</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane container active" id="personalBlankTab" style="padding: 0;">
                <div class="row" style="margin-top: 10px;">
                  <div class="col-6" style="padding-right: 5px; padding-left: 9px;">
                    <label for="" class="required-for-label">Страна</label>
                    <!--<input type="text" class="form-control form-control-sm" id="countryContact" placeholder="">-->
                    <select class="form-control form-control-sm" id="countryContact">
                      <option value=""></option>
                      <option value="AM">Армения</option>
                      <option value="BY">Беларусь</option>
                      <option value="KZ">Казахстан</option>
                      <option value="LV">Латвия</option>
                      <option value="LT">Литва</option>
                      <option value="RU">Россия</option>
                      <option value="UA">Украина</option>
                      <option value="EE">Эстония</option>
                    </select>
                  </div>
                  <div class="col-6" style="padding-left: 5px;">
                    <label for="" class="required-for-label">Пол</label>
                    <select type="text" class="form-control form-control-sm" id="maleContact" placeholder="">
                      <option value="_none_">
                      <option value="1">муж.
                      <option value="0">жен.
                    </select>
                  </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                  <div class="col-6" style="padding-right: 5px; padding-left: 9px;">
                    <label for="">Область</label>
                    <input type="text" class="form-control form-control-sm" id="regionContact" placeholder="">
                  </div>
                  <div class="col-6" style="padding-left: 5px;">
                    <label for="">Район (при наличии)</label>
                    <input type="text" class="form-control form-control-sm" id="areaContact" placeholder="">
                  </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                  <div class="col-6" style="padding-right: 5px; padding-left: 9px;">
                    <label for="">Населённый пункт</label>
                    <input type="text" class="form-control form-control-sm" id="localityContact" placeholder="">
                  </div>
                  <div class="col-6" style="padding-left: 5px;">
                    <label for="">Индекс</label>
                    <input type="text" class="form-control form-control-sm" id="indexContact" maxlength="6" placeholder="">
                  </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                  <div class="col-12" style="padding-left: 9px;">
                    <label for="">Адрес</label>
                    <input type="text" class="form-control form-control-sm" id="addressContact" placeholder="">
                  </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                  <div class="col-12" <?php echo $isAdminZero; ?>  style="padding-left: 9px;">
                    <label for="">Регион работы</label>
                    <select id="regionWorkContact" class="form-control form-control-sm" name="" title="Регион работы">
                      <option value=""></option>
                      <?php foreach (db_getregionOfWork () as $id => $name) echo "<option value='$name'>".htmlspecialchars ($name)."</option>"; ?>
                    </select>
                      <!--<input type="text" class="form-control" id="regionWorkContact" placeholder="Регион работы">-->
                  </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                  <div class="col-6" style="padding-right: 5px; padding-left: 9px;">
                    <label for="">Статус</label>
                    <select class="form-control form-control-sm" id="statusContact" class="selectpicker">
                      <option value="" selected></option>
                      <option value="7">В работе</option>
                      <option value="1">Недозвон</option>
                      <option value="2">Ошибка</option>
                      <option value="3">Отказ</option>
                      <option value="4">Заказ</option>
                      <option value="5">Продолжение</option>
                      <option value="6">Завершение</option>
                    </select>
                  </div>
                  <div class="col-6" style="padding-left: 5px;">
                    <label for="">Ответственный</label>
                    <select id="responsibleContact" class="form-control form-control-sm" name="" data-responsible_previous="" data-responsible="" title="Ответственный">
                      <?php if ($adminRole !== 0) foreach ($membersForCombobox as $id => $name) echo "<option value='$id'>".htmlspecialchars (shortNameMember($name['name']))."</option>"; ?>
                    </select>
                    <!--<input type="text" class="form-control" id="responsibleContact" placeholder="Ответственный" data-responsible_previous="" data-responsible="">-->
                  </div>
                </div>
              </div>

            <div class="tab-pane container fade" id="blankCommentTab" style="padding: 0;">
              <div class="row" style="margin-top: 20px;">
                <div class="col-12" style="padding-left: 9px;">
                  <textarea id="commentContact" class="form-control form-control-sm" rows="15" cols="80" style="width: 100%;"></textarea>
                </div>
              </div>
            </div>
            <div class="tab-pane container fade" id="blankHistoryTab" style="padding: 0;">
            <!-- Прокрутка при переполнении для нижнего дива-->
            <div class="row" style="margin-top: 10px; margin-left: 0px; margin-right: 0px;">
              <div class="col-6"><span id="labelOrderDate">Заказа НЗ не было</span><span id="orderDate"></span><i class="cursor-pointer fa fa-pencil" id="orderDateEditIco" style="padding-left: 0px; font-size:14px;"></i>
                <span style="display: none;"><input type="date" id="orderDateEdit"><i class="cursor-pointer fa fa-check-circle" id="orderDateEditIcoOk" style="padding-left: 10px; color: green; font-size:20px;"></i><i class="cursor-pointer fa fa-ban" id="orderDateEditIcoCancel" style="padding-left: 10px; color: red; font-size:20px;"></i></span>
                </div>
              <div class="col-6"><span>Отправка: </span><span id="sendingDate"></span></div>
            </div>
            <hr>
              <div id="chatBlock" class="container" style="min-height: 200px; overflow-y: auto;">
              </div>
              <div class="row" style="padding-top: 10px; border-top: 1px solid lightgrey;">
                <div class="col-12">
                  <div class="input-group">
                    <input type="text" class="form-control form-control-sm" id="msgChatText" placeholder="Поле для записи" style="border:0;">
                    <div class="input-group-btn">
                      <button id="msgChatSend" class="btn btn-default" type="submit"><i class="fa fa-send-o" style="color:grey; font-size:20px"></i></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="" style="text-align: right; background-color: #f5f5f5; margin-left: -15px; margin-right: -15px; padding-top: 15px; padding-bottom: 15px; border-top: 1px solid lightgrey;">

              <input id="orderSentToContact" class="btn btn-secondary btn-sm" type="button" data-id_admin="" data-id="" value="Отправить заказ" style="margin-right: 95px;" data-target="" data-toggle="modal">
              <input id="saveContact" class="btn btn-info btn-sm" type="button" data-id_admin="" data-id="" value="Сохранить" style="margin-right: 5px;">
              <input class="btn my_btn_cancel btn-sm" id="cd-panel__close-watch" type="button" name="" value="Закрыть" style="margin-right: 15px;">
            </div>
          </div>
    </div> <!-- cd-panel__content -->
  </div> <!-- cd-panel__container -->
</div> <!-- cd-panel -->
            <table id="listContacts" class="table table-hover">
              <thead>
                <tr>
                <th style="text-align: left;"><input id="checkAllStrings" type="checkbox" class="" name="" value="" style="margin-top: 5px;"></th>
                <th style="text-align: left; min-width:70px"><a id="sort-name-contact" href="#" title="сортировать">ФИО</a>&nbsp;<i class="fa fa-caret-down"></i></th>
                <th style="text-align: left;"><a id="sort-locality-contact" href="#" title="сортировать">Местность<i class="fa icon_none"></a>&nbsp;</i></th>
                <th style="text-align: left;">Телефон</th>
                <th style="text-align: left;">Статус</th>
                <th style="text-align: left; min-width:150px;">Ответственный</th>
                </tr>
              </thead>
              <tbody><tr><td colspan="12"><h3 style="text-align: center">Загрузка списка.</h3></td></tr></tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="show-phone" id="listContactsMbl" style="padding-top: 150px;">
      </div>
<!-- List & blank Contacts STOP -->
  </div>
</div>
<!-- Modal Header -->
    <div id="modalUploadItems" class="modal">
      <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Выберите файл для загрузки</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <div class="" id="uploadMsgError" style="color: red; font-size: 18px;"></div>
          <form id="formUpload" class="" action="ajax/excelUpload2.php" method="post" enctype="multipart/form-data">
            <input id="upload_file" type="file" class="btn btn-default btn-sm" name="upload_file" accept=".xls, .xlsx">
            <button id="uploadBtn" type="submit" class="btn btn-primary btn-sm" style="display: none;">
              Загрузить
            </button>
            <span id="uploadSpinner" class="spinner-border spinner-grow-sm"></span>
          </form>
          <hr>
          <div class="row" id="globalValueForFields">
            <div class="col-12">
              <h5>Применить ко всем строкам</h5>
            </div>
            <div class="col-6">
              <label for="uploadCountry">Страна</label>
              <select class="form-control form-control-sm" id="uploadCountry">
                <option checked value="_none_"></option>
                <option value="RU">Россия</option>
                <option value="UA">Украина</option>
                <option value="AM">Армения</option>
                <option value="BY">Белоруссия</option>
                <option value="UZ">Узбекистан</option>
                <option value="KG">Киргизия</option>
                <option value="KZ">Казахстан</option>
              </select>
            </div>
            <div class="col-6">
              <label for="uploadLocality">Местность</label>
              <input type="text" class="form-control form-control-sm" id="uploadLocality">
            </div>
          </div>
          <hr>
          <h5>Настроить поля</h5>
          <div id="newuploadBoard" class="row">
            <div class="col-6">
              <select class="float-left form-control form-control-sm" id="nameGlobalUpload" title="Это обязательное поле и оно должно содержать ФИО полностью или фамилию">
                  <option value="name">ФИО или Фамилия</option>
              </select>
            </div>
            <div class="col-6">
              <select class="float-right upload_fields form-control form-control-sm" id="nameGlobalUploadVal" title="Это обязательное поле и оно должно содержать ФИО полностью или фамилию">
                  <option value=""></option>
              </select>
            </div>
            <div class="col-6">
              <select class="float-left form-control form-control-sm" id="name1GlobalUpload" title="Это дополнительное поле, оно должно содержать имя, если в поле выше содержится только фамилия">
                  <option value="name1">Имя (опционально)</option>
              </select>
            </div>
            <div class="col-6">
              <select class="float-right upload_fields form-control form-control-sm" id="" title="Это дополнительное поле, оно должно содержать имя, если в поле выше содержится только фамилия">
                  <option value=""></option>
              </select>
            </div>
            <div class="col-6">
              <select class="float-left form-control form-control-sm" id="name2GlobalUpload" title="Это дополнительное поле, оно должно содержать отчество.">
                  <option value="name2">Отчество (опционально)</option>
              </select>
            </div>
            <div class="col-6">
              <select class="float-right upload_fields form-control form-control-sm" id="" title="Это дополнительное поле, оно должно содержать отчество.">
                  <option value=""></option>
              </select>
            </div>
            <div class="col-6">
              <select class="float-left form-control form-control-sm" id="citizenshipGlobalUpload" title="Поле должно содержать название страны">
                  <option value="citizenship">Страна</option>
              </select>
            </div>
            <div class="col-6">
              <select class="float-right upload_fields form-control form-control-sm" id="citizenshipGlobalUploadVal" title="Поле должно содержать название страны">
                  <option value=""></option>
              </select>
            </div>
            <div class="col-6">
              <select class="float-left form-control form-control-sm" id="localityGlobalUpload" title="Поле должно содержать название местности">
                  <option value="locality">Местность</option>
              </select>
            </div>
            <div class="col-6">
              <select class="float-right upload_fields form-control form-control-sm" id="localityGlobalUploadVal" title="Поле должно содержать название местности">
                  <option value=""></option>
              </select>
            </div>
            <hr>
          </div>
          <div id="" class="">
            <label for="uploadStringsChkbx"><input type="checkbox" name="" value="" id="uploadStringsChkbx" style="margin-bottom: 3px"> Настроить строки</label>
            <div id="uploadStringsShow" class="" style="max-height: 300px; overflow-y: auto;">
            </div>
          </div>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button class="btn btn-success saveUploadItemsNew" aria-hidden="true">Добавить на сайт</button>
          <button type="button" class="btn btn-secondary cancelUploadItems" data-dismiss="modal">Закрыть</button>
        </div>
      </div>
    </div>
    </div>
<!-- STOP Modal upload -->
<!-- Modal message upload xlsx -->
    <div id="uplpadStringCounterModal" data-width="400" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        </div>
        <div class="modal-body">
          <h4>Будет добавлено <span id="uplpadStringCounter"></span> строк</h4>
        </div>
        <div class="modal-footer">
            <button class="btn btn-success" id="uplpadStringCounterBtn" data-dismiss="modal" aria-hidden="true">Да</button>
            <button class="btn" data-dismiss="modal" aria-hidden="true">Нет</button>
        </div>
    </div>
<!-- STOP Modal message upload xlsx -->
<!-- STOP Modal save confirm-->
    <div id="saveConfirm" data-width="400" class="modal" tabindex="-1" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
          <h5>Заказ будет отправлен на обработку</h5>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        </div>
        <div class="modal-body">
          <h6>Примечание к заказу</h6>
          <textarea id="adminNotes" name="name" rows="3" cols="56"></textarea>
        </div>
        <div class="modal-footer">
          <button class="btn btn-sm btn-secondary" id="saveConfirmBtn" data-dismiss="" aria-hidden="true">Отправить</button>
          <button class="btn btn-sm my_btn_cancel" id="saveCancelConfirmBtn" data-dismiss="" aria-hidden="true">Отменить</button>
        </div>
        </div>
      </div>
    </div>
<!-- STOP Modal save confirm -->
<!-- START Modal set responsible -->
    <div id="setResponsibleModal" data-width="400" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5>Выберите ответственного</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
          </div>
          <div class="modal-body">
            <div class="col-md-12" style="padding-left: 0;">
                <select id="responsibleList" class="form-control form-control-sm" name="">
                  <option value="_all_"></option>
                  <?php if($adminRole !== 0) foreach ($membersForCombobox as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name['name'])."</option>"; ?>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-sm btn-success" id="appointResponsible" data-dismiss="modal" aria-hidden="true" disabled>Передать</button>
              <button class="btn  btn-sm btn-secondary" data-dismiss="modal" aria-hidden="true">Отмена</button>
            </div>
          </div>
      </div>
    </div>
<!-- STOP Modal set responsible -->
<!-- START Modal set responsible admin 0 -->
    <div id="setResponsibleModalAdminZero" data-width="400" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5></h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
          </div>
          <div class="modal-body">
            <div class="col-md-12" style="padding-left: 0;">
              <b>Передать выбранные контакты?</b><br>
            </div>
            <div class="col-md-12" style="padding-left: 0; overflow-y: auto; max-height: 300px;" id="listForSetRespAdminZero">

            </div>
          </div>
            <div class="modal-footer">
              <button class="btn btn-sm btn-success" id="appointResponsibleAdminZero" data-dismiss="modal" aria-hidden="true" >Передать</button>
              <button class="btn  btn-sm btn-secondary" data-dismiss="modal" aria-hidden="true">Отмена</button>
            </div>
          </div>
      </div>
    </div>
<!-- STOP Modal set responsible admin 0 -->

<!-- START Modal delete contact -->
    <div id="deleteContactsModal" data-width="400" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5>Удалить выбранные контакты?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <b>Данные будут удалены без возможности восстановления.</b><br>
                Будут удалены следующие контакты: <br>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12" id="listDeleteStr" style="max-height:300px; overflow-y: auto;">
              </div>
            </div>
          </div>
            <div class="modal-footer">
              <button class="btn btn-sm btn-danger" id="deleteContact" data-dismiss="modal" aria-hidden="true" disabled>Удалить</button>
              <button class="btn  btn-sm btn-secondary" data-dismiss="modal" aria-hidden="true">Отмена</button>
            </div>
          </div>
      </div>
    </div>

<!-- SAVE COMFIRME-->
    <div class="modal">
      <!-- STOP Modal delete contact -->
      <!--Содержимое модального окна-->
      <button class="button-ok" id="modalSaveComfirm">Да</button>
      <button class="button-close" id="">Нет</button>
      <button class="button-close" id="">Отмена</button>
    </div>
<!-- STOP Modal delete contact -->

<!-- START Modal status contact -->
  <div id="statusContactsModal" data-width="400" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Задать выбранным контактам статус</h5>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <select id="comboStatusModal" class="form-control form-control-sm" name="">
                <option value="" selected></option>
                <option value="7">В работе</option>
                <option value="1">Недозвон</option>
                <option value="2">Ошибка</option>
                <option value="3">Отказ</option>
                <option value="4">Заказ</option>
                <option value="5">Продолжение</option>
                <option value="6">Завершение</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12" id="listStatusStr" style="max-height:300px; margin-top: 10px; overflow-y: auto;">
            </div>
          </div>
        </div>
          <div class="modal-footer">
            <button class="btn btn-sm btn-danger" id="statusContactBtn" data-dismiss="modal" aria-hidden="true" disabled>Задать</button>
            <button class="btn  btn-sm btn-secondary" data-dismiss="modal" aria-hidden="true">Отмена</button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal">
<!-- STOP Modal status contact -->

    <script>
      var data_page = {};

      data_page.admin_name = '<?php echo db_getAdminNameById($memberId);?>';

      data_page.members_admin_responsibles = [];
      respAdmTmp = '<?php foreach (db_getAdminByLocalityCnt($adminLocality) as $id => $name) echo $name['key'].'_'.$name['name'].'_'; ?>';
      respAdmTmp ? respAdmTmp = respAdmTmp.split('_') : respAdmTmp=[];
      for (var v = 0; v < respAdmTmp.length; v = v + 2) {
        if (respAdmTmp[v+1]) {
          data_page.members_admin_responsibles[String(respAdmTmp[v])] = respAdmTmp[v+1];
        }
      }
      respAdmTmp=[];

      data_page.members_responsibles = [];
      var respMemTmp = '<?php if ($adminRole !== 0) foreach ($membersForCombobox as $id => $name) echo $id.'_'.$name['name'].'_'; ?>';
      respMemTmp ? respMemTmp = respMemTmp.split('_') : respMemTmp=[];
      for (var iv = 0; iv < respMemTmp.length; iv = iv + 2) {
        if (respMemTmp[iv+1]) {
          data_page.members_responsibles[String(respMemTmp[iv])] = respMemTmp[iv+1];
        }
      }
      respMemTmp=[];

      data_page.locality_responsibles = [];
      var respListTmp = '<?php foreach (db_getResponsiblesLocality() as $id => $name) echo $id.'_'.$name.'_'; ?>';
      respListTmp ? respListTmp = respListTmp.split('_') : respListTmp=[];
      for (var iii = 0; iii < respListTmp.length; iii = iii + 2) {
        if (respListTmp[iii+1]) {
          data_page.locality_responsibles[String(respListTmp[iii])] = respListTmp[iii+1];
        }
      }
      respListTmp = [];

      data_page.country_list = [];
      var countryListTmp = '<?php foreach (db_getCountriesList() as $id => $name) echo $id.'_'.$name.'_'; ?>';
      countryListTmp ? countryListTmp = countryListTmp.split('_') : countryListTmp=[];
      for (var ii = 0; ii < countryListTmp.length; ii = ii + 2) {
        if (countryListTmp[ii+1]) {
          data_page.country_list[String(countryListTmp[ii])] = countryListTmp[ii+1];
        }
      }
      countryListTmp = [];
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
      data_page.admin_role = '<?php echo $adminRole; ?>';
      data_page.full_admin_list =[];
      var admins_list = '<?php foreach ($listAdmins as $id => $name) echo $id.'_'.$name.'_'; ?>';
      var admin_list_tmp = admins_list ? admins_list.split('_') : [];
      for (var i = 0; i < admin_list_tmp.length; i = i + 2) {
        if (admin_list_tmp[i+1]) {
          data_page.full_admin_list[String(admin_list_tmp[i])] = admin_list_tmp[i+1];
        }
      }
      admins_list = [], admin_list_tmp = [];

      data_page.sort_new = '<?php echo $bellOn; ?>';
    </script>
    <script src="/js/contacts.js?v20"></script>
    <script src="/js/contactsupload.js?v3"></script>
<?php
    include_once "footer2.php";
?>
