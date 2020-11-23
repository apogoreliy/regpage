<?php
    include_once "header.php";
    include_once "nav.php";
    include_once "modals.php";
    include_once "bulkEditModal.php";

    global $appRootPath;

    $hasMemberRightToSeePage = count(db_getAdminLocalities($memberId))>0 || db_hasAdminFullAccess($memberId);
    if(!$hasMemberRightToSeePage){
        die();
    }
    $categories = db_getCategories();
    $countries1 = db_getCountries(true);
    $countries2 = db_getCountries(false);
    $selectedEventId = isset($_COOKIE['eventChoose']) ? $_COOKIE['eventChoose'] : false;
    $adminCountry = db_getAdminCountry($memberId);

    $singleCity = db_isSingleCityAdmin($memberId);
    $user_settings = db_getUserSettings($memberId);

    $userSettings = implode (',', $user_settings);
?>

<div class="container">
    <div class="aditional-menu">
        <a class="btn btn-primary disabled chk-dep chk-register role-admin" type="button"><i class="fa fa-check icon-white" title="Зарегистрировать"></i> <span class="hide-name">Зарегистрировать</span></a>
        <a class="btn btn-primary disabled chk-dep chk-bulkedit role-edit" type="button"><i class="fa fa-list icon-white" title="Изменить"></i> <span class="hide-name">Изменить</span></a>
        <a class="btn btn-danger disabled chk-dep chk-remove role-edit" type="button"><i class="fa fa-ban icon-white" title="Отменить"></i> <span class="hide-name">Отменить</span></a>
        <?php if($event->web == 1){ ?>
        <a class="btn btn-warning disabled chk-dep filter-icons bulkedit-prove" type="button"><i class="fa fa-asterisk" aria-hidden="true" title="Подтвердить"></i> <span class="hide-name">Подтвердить</span></a>
        <a class="btn btn-danger disabled chk-dep filter-icons bulkedit-prove" type="button"><i class="fa fa-asterisk" aria-hidden="true" title="Отметить прибытие"></i> <span class="hide-name">Отметить прибытие</span></a>
        <?php } ?>
        <div class="btn-group">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-envelop" title="Отправить сообщение"></i> <span class="hide-name">Отправить сообщение</span>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a class="chk-send-letter disabled chk-dep role-send-msg" tabindex="-1" href="#">Сообщение участникам</a></li>
                <li><a class="chk-send-invitation disabled chk-dep role-send-msg" tabindex="-1" href="#">Персональное приглашение</a></li>
            </ul>
        </div>
    </div>
<?php
    $textBlock = db_getTextBlock('admin_reg');
    if ($textBlock) echo "<div class='alert hide-phone'>$textBlock</div>";

    $events = db_getEventsByAdmin($memberId);
    $checkEventsArr = false;
    foreach($events as $index => $event){
      if ($event->id == $selectedEventId)
      {
        $checkEventsArr = true;
      };
    }
    if ($checkEventsArr != true) $selectedEventId =false;

    if(!$events){
        echo '<p class="absent-events">В настоящее время нет мероприятий для регистрации.</p>';
    }
    else{
?>

<div class="tabbable hide-phone registration-list" id="eventTabs">
    <div class="tab-content">
        <select class="controls span5" id="events-list">
            <?php
            $activeIsSet = false;
            foreach($events as $index => $event){
                $isEventActive = (!$selectedEventId && !$activeIsSet) || ($selectedEventId == $event->id);
                echo "<option value='".$event->id."' data-id='".$event->id."' ". ($isEventActive ? 'selected' : '').">". htmlspecialchars($event->name)."</option>";
                $activeIsSet=true;
            }
            ?>
        </select>
    <?php
    $activeIsSet = false;

    echo '<span style="margin-left:10px;" class="close-event-registration"></span>';

    foreach ($events as $index=>$event) {
        $showLocalityField = !db_isSingleCityAdmin($memberId) || db_isAdminRespForReg($memberId, $event->id);
        $sort_field = isset ($_SESSION['sort_field_'.$event->id]) ? $_SESSION['sort_field_'.$event->id] : 'name';
        $sort_type = isset ($_SESSION['sort_type_'.$event->id]) ? $_SESSION['sort_type_'.$event->id] : 'asc';
    ?>

    <div class="tab-pane<?php echo (($selectedEventId == $event->id) || (!$activeIsSet && !$selectedEventId) ? " active" : ""); ?>"
        id="eventTab-<?php echo $event->id; ?>" data-start="<?php echo $event->start_date; ?>" data-end="<?php echo $event->end_date; ?>" data-transport="<?php echo $event->need_transport; ?>"
        data-custom_list_item ="<?php echo $event->list_name; ?>"
        data-regend="<?php echo $event->regend_date; ?>" data-event_type="<?php echo $event->event_type; ?>" data-private="<?php echo $event->private; ?>" data-access="<?php echo $memberId == $event->admin_access ? 1: 0 ; ?>"
        data-show-locality-field="<?php echo $showLocalityField ? 1 : 0; ?>"
        data-need_flight="<?php echo $event->need_flight; ?>" data-need_tp="<?php echo $event->need_tp; ?>" data-min_age="<?php echo $event->min_age; ?>" data-max_age="<?php echo $event->max_age; ?>" data-need_status="<?php echo $event->need_status; ?>" data-currency="<?php echo $event->currency; ?>"
        >
        <div>
        <div class="btn-toolbar">
            <a class="btn btn-success event-add-member role-edit" type="button"><i class="fa fa-plus icon-white"></i> <span class="hide-name">Добавить</span></a>
            <a class="btn btn-primary disabled chk-dep chk-register role-admin" type="button"><i class="fa fa-check icon-white"></i> <span class="hide-name">Зарегистрировать</span></a>
            <a class="btn btn-primary disabled chk-dep chk-bulkedit role-edit" type="button"><i class="fa fa-list icon-white"></i> <span class="hide-name">Изменить</span></a>
            <a class="btn btn-danger disabled chk-dep chk-remove role-edit" type="button"><i class="fa fa-ban icon-white"></i> <span class="hide-name">Отменить</span></a>
            <a class="btn btn-success chk-invite role-edit" type="button"><i class="fa fa-user icon-white" title="Пригласить пользователя"></i> <span class="hide-name">Пригласить пользователя</span></a>
            <span class="btn send-message-regteam" tabindex="-1" style="margin-right: 10px; font-family: Arial;" title="Отправить сообщение команде регистрации" data-toggle="modal" data-target="#modalEventSendMsg"><i class="fa fa-envelope"></i>  <b>Написать команде регистрации</b></span>
            <?php if($event->web == 1){ ?>
            <a class="btn btn-warning disabled chk-dep filter-icons bulkedit-prove" type="button"><i class="fa fa-asterisk" aria-hidden="true"></i> <span class="hide-name">Подтвердить</span></a>
            <a class="btn btn-danger disabled chk-dep filter-icons bulkedit-prove" type="button"><i class="fa fa-asterisk" aria-hidden="true"></i> <span class="hide-name">Отменить прибытие</span></a>
            <?php } ?>
        </div>
        <div class="btn-toolbar">
            <a class="btn event-info" type="button"><i class="icon-info-sign"></i> <span class="hide-name">О мероприятии</span></a>
            <a class="btn statService" type="button"><i class="fa fa-bar-chart"></i> <span class="hide-name">Статистика</span></a>
            <div class="btn-group">
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="icon-envelope"></i> <span class="hide-name">Отправить</span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="chk-send-letter disabled chk-dep role-send-msg" tabindex="-1" href="#">Сообщение участникам</a></li>
                    <li><a class="chk-send-invitation disabled chk-dep role-send-msg" tabindex="-1" href="#">Персональное приглашение</a></li>
                </ul>
            </div>
            <div class="btn-group">
                <a class="btn dropdown-toggle downloadGeneral" data-toggle="dropdown" href="#">
                    <i class="fa fa-download"></i> <span class="hide-name">Скачать</span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="downloadExl" data-download="all" tabindex="-1" href='#'>Весь список</a></li>
                    <li><a class="downloadExl" data-download="service" tabindex="-1" href="#">Служащие</a></li>
                    <li><a class="downloadExl" data-download="coord" tabindex="-1" href="#">Координаторы</a></li>
                    <li><a class="downloadExl" data-download="parking" tabindex="-1" href="#">Данные о парковке</a></li>
                </ul>
            </div>
            <div class="btn-group" style="<?php if ($memberId != '000005716' && $memberId != '000001679') { ?>display:none<?php }; ?>">
              <a class="btn upload-toggle uploadGeneral" data-toggle="dropdown" href="#">
                  <i class="fa fa-upload"></i> <span class="hide-name">Загрузить</span>
                  <span class=""></span>
              </a>
              <ul class="dropdown-menu">
                  <li><a class="uploadExl" data-download="" tabindex="-1" href='#'>Загрузить из файла</a></li>
              </ul>
            </div>
            <select id="localitiesCombobox" style="margin-bottom: 0" class="span2 <?php echo " filterLocality-".$event->id; ?> "></select>
            <select style="margin-bottom: 0" class="span2 filter-regstate">
                <option value='_all_' selected>Все состояния</option>
                <option value='01'>Не зарегистрированные</option>
                <option value='02'>Ожидание подтверждения</option>
                <option value='03'>Ожидание отмены</option>
                <option value='04'>Регистрация подтверждена</option>
                <option value='05'>Регистрация отклонена</option>
            </select>
            <!--<div class="btn-group filter-icons">
                <i class="btn fa fa-user-plus fa-lg filter-users" data-regstate="04" title="Регистрация подтверждена" aria-hidden="true"></i>
                <i class="btn fa fa-thumbs-o-up fa-lg filter-users" data-regstate="02, 01" title="Ожидание подтверждения" aria-hidden="true"></i>
                <i class="btn fa fa-window-close fa-lg filter-users" data-regstate="05" title="Регистрация отклонена" aria-hidden="true"></i>
                <i class="btn fa fa-user-times fa-lg filter-users" data-regstate="03" title="Ожидание отмены" aria-hidden="true"></i>
                <i class="btn fa fa-user fa-lg filter-users" data-regstate="NULL" title="Не зарегистрированные" aria-hidden="true"></i>
            </div> -->
            <?php echo $adminCountry == 'UA' ? '<a class="btn aid-statistic" type="button" title="Информация о финансовой помощи"><i class="fa fa-money"></i></a>' : '' ?>
            <?php if('000002395' != $memberId){ ?>
                <i class="btn fa fa-flag fa-lg filter-arrived filter-icons" data-attended="1" title="Прибывшие" aria-hidden="true"></i>
                <i class="btn fa fa fa-clock-o fa-lg filter-arrived filter-icons" data-attended="2" title="Не прибывшие" aria-hidden="true"></i>
            <!--<div class="btn-group filter-icons">
                <i class="btn fa fa-random fa-lg filter-coord" data-attended="1" title="Координаторы" aria-hidden="true"></i>
                <i class="btn fa fa fa-wrench fa-lg filter-service" data-attended="2" title="Служащие" aria-hidden="true"></i>
            </div> -->
            <?php } ?>
            <!-- show this button only if user is admin with full responcebility -->
            <a type="button" class="btn btn-default search"><i class="icon-search" title="Поле поиска"></i></a>
            <div class="not-display" data-toggle="1">
                <input type="text"  class="controls search-text" placeholder="Введите текст">
                <i class="icon-remove clear-search"></i>
            </div>
            </div>
        </div>
        <div class="desctopVisible">
            <div id="statReg">
                <table class="table table-hover reg-list">
                    <thead>
                        <tr>
                            <th class="style-checkbox"><input type="checkbox"></th>
                            <th><a id="sort-name" href="#" title="сортировать">Участник</a>&nbsp;<i class="<?php echo $sort_field=='name' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                                <?php
                                    if ($showLocalityField){
                                        echo '<th><a id="sort-locality" href="#" title="сортировать">Город</a>&nbsp;<i class="'.($sort_field=='locality' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none').'"></i></th>';
                                    }
                                ?>
                            <th class="hide-tablet">Телефон</th>


                            <th class="hide-tablet"><a id="sort-status" href='#' title="сортировать">Статус</a>&nbsp;<i class="<?php echo $sort_field=='status' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>

                            <th>Даты</th>
                            <th><a id="sort-regstate" href="#" title="сортировать">Состояние</a>&nbsp;<i class="<?php echo $sort_field=='regstate' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        </tr>
                    </thead>
                    <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
                </table>
            </div>
        </div>
        <div class="show-phone">
            <div id="statReg">
            <table class="table table-hover reg-list show-phone" id="phoneRegList-<?php echo $event->id; ?>">
                <thead>
                    <tr>
                        <th><input type="checkbox"></th>
                        <th>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="sortName"><?php echo $s = isset($_COOKIE['sort'])? $_COOKIE['sort'] : 'Сортировать' ?></span>
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" id="dropdownMenu2" aria-labelledby="dropdownMenu1">
                                    <li><a id="sort-name" data-sort="Участник" href="#" title="сортировать">Участник</a>&nbsp;<i class="<?php echo $sort_field=='name' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                                    <li>
                                        <?php
                                        if ($showLocalityField){
                                            echo '<a id="sort-locality" data-sort="Город" href="#" title="сортировать">Город</a>&nbsp;<i class="'.($sort_field=='locality' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none').'"></i>';
                                        }
                                        ?>
                                    </li>
                                    <li><a id="sort-regstate" href="#" data-sort="Состояние" title="сортировать">Состояние</a>&nbsp;<i class="<?php echo $sort_field=='regstate' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                                </ul>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
            </table>
            </div>
        </div>
    </div>

    <?php $activeIsSet=true; } ?>
    </div>
</div>

<?php } ?>

<!-- Add Members Modal -->
<div id="modalAddMembers" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="addMembersTitle" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="addMembersTitle">Добавление участников</h3>
        <p id="addMemberEventTitle"></p>
    </div>
    <div class="modal-body">
        <form class="form-inline">
            <select id="selAddMemberLocality" class="span2"></select>
            <select id="selAddMemberCategory" class="span2">
                <option value='_all_' selected>&lt;все категории&gt;</option>
                <?php foreach ($categories as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
            </select>
            <input class="span2" type="text" id="searchBlockFilter" placeholder="Введите фамилию" style="margin-top: 5px;">
        </form>
        <div id="addMemberTableHeader">
            <a onclick="$('.member-row > td > input[type=checkbox]').filter(':visible').prop('checked', true);">Выбрать всех</a>
            <a onclick="$('.member-row > td > input[type=checkbox]').prop('checked', false);">Отменить выбор</a>
        </div>
        <div class="control-group row-fluid searchBlock">
            <label class="span12">Введите ФИО или местность участника</label>
            <input class="span12 searchMemberToAdd" type="text">
        </div>
        <div class="membersTable">
            <table class="table table-hover table-condensed">
                <thead><tr><th>&nbsp;</th><th>Фамилия Имя Отчество</th><th>Местность</th></tr></thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" id="btnDoAddMembers"><i class="fa fa-check icon-ok icon-white"></i> Добавить выбранных</button>
        <button class="btn btn-success" id="btnDoCreateMember"><i class="fa fa-plus icon-plus icon-white"></i> Создать нового</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Отменить</button>
    </div>
</div>

<!-- Edit Member Modal -->
<div id="modalEditMember" data-width="560" data-keyboard="false" data-backdrop="static" class="modal hide fade modal-edit-member" tabindex="-1" role="dialog" aria-labelledby="editMemberEventTitle" aria-hidden="true" data-locality_key="">
    <div class="modal-header">
        <button type="button" class="close close-form" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 class="editMemberEventTitle"></h4>
        <a style="margin-left: 0" id="lnkEventInfo" title="Открывает окно с информацией об этом мероприятии">Информация о мероприятии</a>
        <a id="lnkPermalink" class="role-send-msg" title="Ссылка на этот бланк регистрации">Показать ссылку</a>
        <input id="txtPermalink" type="text" style="display: none;" />
        <a onclick="showLetterDialog('', false);" class="role-send-msg" title="Отправить сообщение данному участнику">Отправить сообщение</a>
        <div class="header-status" style="margin-top:10px;">
            <span class="eventMemberStatus"></span>
            <a href="#" rel="tooltip" data-toggle="tooltip" data-placement="right" title="" tabindex="-1" id="eventMemberPlace"><i class="icon-flag"></i></a>
            <span class="eventMemberArrived"></span>
        </div>
    </div>
    <div class="modal-body">
        <?php
            //require_once 'form.php';
            require_once 'formTab.php';
        ?>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary disable-on-invalid role-admin" id="btnDoRegisterMember"><i class="fa fa-check icon-white"></i> Зарегистрировать</button>
        <button class="btn btn-info disable-on-invalid role-edit" id="btnDoSaveMember">Сохранить</button>
        <button class="btn close-form" data-dismiss="modal" aria-hidden="true">Отменить</button>
        <p id="forAdminRegNotice" style="color: red; font-style: bold; font-size: 16px; padding-top: 15px; text-align: center;"></p>
    </div>
</div>

<!-- Send Letter Modal -->
<div id="modalSendLetter" class="modal hide fade modal-send-message" tabindex="-1" role="dialog" aria-labelledby="sendLetterTitle" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="sendLetterTitle">Персональное сообщение</h3>
        <p id="sendInvitationEventName"></p>
    </div>
    <div class="modal-body">
        <form class="form-horizontal">
            <div class="control-group">
                <label class="control-label" for="sendLetterName">Тема</label>
                <div class="controls">
                    <input type="text" class="span4 name-field" id="sendLetterTopic" placeholder="Имя" valid="required">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="sendLetterName">От<sup>*</sup></label>
                <div class="controls">
                    <input type="text" class="span4 name-field" id="sendLetterName" placeholder="Имя" valid="required">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="sendLetterEmail">Email<sup>*</sup></label>
                <div class="controls">
                    <input type="text" class="span4 email-field" id="sendLetterEmail" placeholder="Email" valid="required, email">
                </div>
            </div>
            <div style="clear: both; margin-bottom: 10px">
                <a onclick="var t = $('#sendLetterText'); t.textrange('replace', '{СсылкаНаБланк}');t.textrange('setcursor', t.textrange('get', 'end'));">Вставить ссылку на бланк регистрации</a>
            </div>
            <textarea class="span5 text-field" rows="10" id="sendLetterText"></textarea>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn btn-success disable-on-invalid" id="btnDoSendLetter">Отправить</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Отменить</button>
    </div>
</div>

<!-- Registration Ended Message Modal -->
<div id="modalRegEnded" class="modal hide fade" tabindex="-1" role="dialog"  aria-labelledby="regEndedTitle" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="regEndedTitle">Регистрация завершена</h3>
    </div>
    <div class="modal-body">
        Регистрация на это мероприятие завершена <span id="regendDate"></span>.<p style="margin-top: 5px">О возможности регистрации новых участников вам необходимо общаться с командой регистрации.</p>
    </div>
    <div class="modal-footer">
        <button class="btn btn-warning send-message" data-dismiss="modal" aria-hidden="true">Сообщение команде регистрации</button>
        <button id="btnRegEndedAction" class="btn btn-success" data-dismiss="modal" aria-hidden="true">Общение состоялось, продолжить</button>
    </div>
</div>

<!-- Name Editing Message Modal -->
<div id="modalNameEdit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="regNameEdit" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="regNameEdit">Внимание!</h3>
        <p>Правила изменения ФИО участника</p>
    </div>
    <div class="modal-body">
        <ol>
            <li>Вводите ФИО в строгой последовательности: <b>Фамилия Имя Отчество</b>.</li>
            <li>Если фамилия недавно была изменена, напишите прежнюю фамилию после отчества в скобках.</li>
            <li><span style="color:red">Не заменяйте ФИО и другие поля данными другого участника!</span> Для нового участника необходимо создать новую карточку.</li>
        <ol>
    </div>
    <div class="modal-footer">
        <button id="btnDoNameEdit" class="btn btn-success" data-dismiss="modal" aria-hidden="true">Изменить ФИО</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>

<!-- Match Members Modal -->
<div id="modalMatchMem" class="modal hide fade" tabindex="-1" role="dialog"  aria-labelledby="regEndedTitle" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close regMemb close-form" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="regEndedTitle">Добавление участников</h3>
    </div>
    <div class="modal-body">
        <p>Найдены участники с указанными ФИО</p>
        <table class="table table-hover table-condensed chkMember">
            <thead><tr><th>&nbsp;</th><th>Фамилия Имя Отчество</th><th>Дата рождения</th><th>Местность</th></tr></thead>
            <tbody>
            </tbody>
    	</table>
    </div>
    <div class="modal-footer">
        <button class="btn btn-warning regMemb close-form" data-dismiss="modal" aria-hidden="true">Отмена</button>
        <button class="btn btn-success chooseMemb" data-dismiss="modal" aria-hidden="true">Выбрать данного участника</button>
    </div>
</div>

<!-- Change Document Items To Download Modal -->
<div id="modalDownloadItems" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="regEndedTitle" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close cancelDownloadItems" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 id="documentItemsTitle">Выберите необходимые данные</h4>
    </div>
    <div class="modal-body">
        <div>
            <input type="checkbox" id="check-all">
            <label for="check-all">Установить все флажки / Снять все флажки</label>
        </div>
        <div class="translate" style="border-bottom: 1px solid lightgrey; margin-bottom: 5px;">
            <input type="checkbox" id="download-translate">
            <label for="download-translate">Переводить на английский</label>
        </div>

        <div class="search-checkbox">
            <div class="search-checkbox-first-column">
              <div>
                  <input type="checkbox" id="member_name" disabled checked>
                  <label for="member_name">ФИО</label>
              </div>
                <div>
                    <input type="checkbox" data-download="birth_date" id="download-birth-date-member">
                    <label for="download-birth-date-member">Дата рождения</label>
                </div>
                <div>
                    <input type="checkbox" data-download="age" id="download-member-age">
                    <label for="download-member-age">Возраст</label>
                </div>
                <div>
                    <input type="checkbox" data-download="locality" id="download-city-member">
                    <label for="download-city-member">Город</label>
                </div>
                <div>
                    <input type="checkbox" data-download="region" id="download-region">
                    <label for="download-region">Область</label>
                </div>
                <div>
                    <input type="checkbox" data-download="country" id="download-country">
                    <label for="download-country">Страна</label>
                </div>
                <div>
                    <input type="checkbox" data-download="post" id="download-post">
                    <label for="download-post">Почтовый адрес</label>
                </div>
                <div>
                    <input type="checkbox" data-download="service" id="download-service">
                    <label for="download-service">Служение</label>
                </div>
                <div>
                    <input type="checkbox" data-download="coord" id="download-coord">
                    <label for="download-coord">Координатор</label>
                </div>
                <div>
                    <input type="checkbox" data-download="cell_phone" id="download-phone-member">
                    <label for="download-phone-member">Телефон</label>
                </div>
                <div>
                    <input type="checkbox" data-download="email" id="download-email-member">
                    <label for="download-email-member">Email</label>
                </div>
                <div>
                    <input type="checkbox" data-download="mate" id="download-mate">
                    <label for="download-mate">Разместить с</label>
                </div>
                <div>
                    <input type="checkbox" data-download="status" id="download-status">
                    <label for="download-status">Статус</label>
                </div>
            </div>
            <div style="display: inline-block;">
                <div>
                    <input type="checkbox" data-download="arr_date" id="download-arr-dep-date">
                    <input style="display: none;" type="checkbox" data-download="dep_date" id="download-dep-date">
                    <label for="download-arr-dep-date">Даты приезда и отъезда</label>
                </div>
                <div>
                    <input type="checkbox" data-download="arr_time" id="download-arr-dep-time">
                    <input style="display: none;" type="checkbox" data-download="dep_time" id="download-dep-time">
                    <label for="download-arr-dep-time">Время приезда и отъезда</label>
                </div>
                <div>
                    <input type="checkbox" data-download="regstate" id="download-reg-state">
                    <label for="download-reg-state">Состояние регистрации</label>
                </div>
                <div>
                    <input type="checkbox" data-download="document" id="download-document">
                    <label for="download-document">Паспортные данные</label>
                </div>
                <div>
                    <input type="checkbox" data-download="tp" id="download-tp">
                    <label for="download-tp">Данные загранпаспорта</label>
                    <input style="display:none" type="checkbox" data-download="tp_name" id="download-tp-name">
                </div>
                <div>
                    <input type="checkbox" data-download="english" id="download-english">
                    <label for="download-english">Уровень английского</label>
                </div>
                <div>
                    <input type="checkbox" data-download="flight-arr" id="download-flight-arr">
                    <label for="download-flight-arr">Авиарейс прибытия</label>
                </div>
                <div>
                    <input type="checkbox" data-download="flight-dep" id="download-flight-dep">
                    <label for="download-flight-dep">Авиарейс вылета</label>
                </div>
                <div>
                    <input type="checkbox" data-download="visa" id="download-visa">
                    <label for="download-visa">Виза</label>
                </div>
                <div>
                    <input type="checkbox" data-download="accom" id="download-accom">
                    <label for="download-accom">Размещение</label>
                </div>
                <div>
                    <input type="checkbox" data-download="transport" id="download-transport">
                    <label for="download-transport">Поездка (транспорт)</label>
                </div>
                <div class="custom-download-item">
                    <input type="checkbox" data-download="custom_item" id="download-custom_item">
                    <label for="download-custom_item"></label>
                </div>
                <div>
                    <input type="checkbox" data-download="hotel" id="download-hotel">
                    <label for="download-hotel">Гостиница</label>
                </div>
                <div>
                    <input type="checkbox" data-download="admin-comment" id="download-admin-comment">
                    <label for="download-admin-comment">Комментарий администратора</label>
                </div>
                <div>
                    <input type="checkbox" data-download="comment" id="download-member-comment" >
                    <label for="download-member-comment" >Комментарий участника</label>
                </div>
                <div>
                    <input type="checkbox" data-download="paid" id="download-paid">
                    <label for="download-paid">Внесённый взнос</label>
                </div>
                <div>
                    <input type="checkbox" data-download="airport-arrival" id="download-airport-arrival">
                    <label for="download-airport-arrival">Аэропорт прибытия</label>
                </div>
                <div>
                    <input type="checkbox" data-download="airport-departure" id="download-airport-departure">
                    <label for="download-airport-departure">Аэропорт вылета</label>
                </div>
                <div>
                    <input type="checkbox" data-download="outline-language" id="download-outline-language">
                    <label for="download-outline-language">Планы на русском</label>
                </div>
                <div>
                    <input type="checkbox" data-download="study-group-language" id="download-study-group-language">
                    <label for="download-study-group-language">Группы изучения на русском</label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-success downloadItems" data-dismiss="modal" aria-hidden="true">Скачать</button>
        <button class="btn btn-default cancelDownloadItems" data-dismiss="modal" aria-hidden="true">Отменить</button>
    </div>
</div>

<!-- Change Document Items To Upload Modal -->
<div id="modalUploadItems" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="regEndedTitle" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close cancelDownloadItems" data-dismiss="modal" aria-hidden="true">x</button>
        <h4>Выберите файл для загрузки</h4>
    </div>
    <div class="modal-body">
      <div class="" id="uploadMsgError" style="color: red; font-size: 18px;"></div>
      <form id="formUpload" class="" action="ajax/excelUpload2.php" method="post" enctype="multipart/form-data">
          <input type="file" id="upload_file" name="upload_file" accept=".xls, .xlsx">
          <button type="submit" id="uploadBtn" style="display:none;">Заг-Заг</button>
          <div class="loader_weel"></div>
          <span id="psevdoSpiner">Loading...</span>
      </form>
      <hr>
      <div class="" id="globalValueForFields">
        <h4>Применить ко всем строкам</h4>
        <label for="uploadCountry">Страна, гражданство*</label>
        <select class="" id="uploadCountry">
            <option checked value="_none_"></option>
            <option value="RU">Россия</option>
            <option value="UA">Украина</option>
            <option value="AM">Армения</option>
        </select>
        <label for="uploadLocality">Местность*</label>
        <select class="" id="uploadLocality">
          <option checked value="_none_"></option>
            <?php
                foreach (db_getLocalities() as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>";
            ?>
        </select>
        <label for="uploadCategory">Категория*</label>
        <select class="" id="uploadCategory">
          <option checked value="_none_"></option>
            <?php foreach (db_getCategories() as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
        </select>
        <label for="uploadAccom">Размещение*</label>
        <select class="" id="uploadAccom">
            <option value='_none_' selected>&nbsp;</option>
            <option value="1">ТРЕБУЕТСЯ</option>
            <option value="0">НЕ ТРЕБУЕТСЯ</option>
        </select>
      </div>
      <hr>
      <h4>Настроить поля</h4>
      <div id="newuploadBoard" class="">
        <select class="float-left" id="nameGlobalUpload" title="Это обязательное поле и оно должно содержать ФИО полностью или фамилию">
            <option value="name">ФИО или Фамилия</option>
        </select>
        <select class="float-right upload_fields" id="nameGlobalUploadVal" title="Это обязательное поле и оно должно содержать ФИО полностью или фамилию">
            <option value=""></option>
        </select>
        <select class="float-left" id="name1GlobalUpload" title="Это дополнительное поле, оно должно содержать имя, если в поле выше содержится только фамилия">
            <option value="name1">Имя (опционально)</option>
        </select>
        <select class="float-right upload_fields" id="" title="Это дополнительное поле, оно должно содержать имя, если в поле выше содержится только фамилия">
            <option value=""></option>
        </select>
        <select class="float-left" id="name2GlobalUpload" title="Это дополнительное поле, оно должно содержать отчество.">
            <option value="name2">Отчество (опционально)</option>
        </select>
        <select class="float-right upload_fields" id="" title="Это дополнительное поле, оно должно содержать отчество.">
            <option value=""></option>
        </select>
        <select class="float-left" id="citizenshipGlobalUpload" title="Поле должно содержать название страны">
            <option value="citizenship">Гражданство</option>
        </select>
        <select class="float-right upload_fields" id="citizenshipGlobalUploadVal" title="Поле должно содержать название страны">
            <option value=""></option>
        </select>
        <select class="float-left" id="localityGlobalUpload" title="Поле должно содержать название местности">
            <option value="locality">Местность</option>
        </select>
        <select class="float-right upload_fields" id="localityGlobalUploadVal" title="Поле должно содержать название местности">
            <option value=""></option>
        </select>
        <select class="float-left" id="categoryGlobalUpload" title="Поле должно содержать название категории например: студент или студенты, школьник и тп.">
            <option value="category">Категория</option>
        </select>
        <select class="float-right upload_fields" id="categoryGlobalUploadVal" title="Поле должно содержать название категории например: студент или студенты, школьник и тп.">
            <option value=""></option>
        </select>
        <select class="float-left" id="accomGlobalUpload" title="Поле может содержать слова да/нет или требуется не требуется">
            <option value="accom">Размещение</option>
        </select>
        <select class="float-right upload_fields" id="accomGlobalUploadVal" title="Поле может содержать слова да/нет или требуется/не требуется">
            <option value=""></option>
        </select>
        <hr>
      </div>
      <div id="" class="">
        <label for="uploadStringsChkbx"><input type="checkbox" name="" value="" id="uploadStringsChkbx" style="margin-bottom: 3px"> Настроить строки</label>
        <div id="uploadStringsShow" class="" style="max-height: 300px; overflow-y: auto;">
        </div>
      </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-success saveUploadItemsNew" aria-hidden="true">Загрузить</button>
        <button class="btn btn-default cancelUploadItems" data-dismiss="modal" aria-hidden="true">Отменить</button>
    </div>
</div>

<!-- Modal message upload xlsx -->
<div id="uplpadStringCounterModal" data-width="400" class="modal hide fade modal-edit-member" tabindex="-1" role="dialog" aria-hidden="true">
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

<!-- Handle Member Regstate -->
<div id="modalHandleMemberAttended" data-width="400" class="modal hide fade modal-edit-member" tabindex="-1" role="dialog" aria-labelledby="editMemberEventTitleService" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 class="editMemberEventTitleService">Отменить прибытие?</h4>
    </div>
    <div class="modal-body">

    </div>
    <div class="modal-footer">
        <button class="btn btn-danger reject-attended" data-dismiss="modal" aria-hidden="true">Да</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Нет</button>
    </div>
</div>

<!-- CONFIRM TO SAVE CHANGES IN REG FORM MODAL WINDOW -->
<div id="confirmToSaveChangesModal" data-width="600" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="editMemberEventTitleService" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4>Сохранение изменений</h4>
    </div>
    <div class="modal-body">
        Некоторые данные были изменены. Вы действительно хотите отменить эти изменения?
    </div>
    <div class="modal-footer">
        <button class="btn btn-danger confirm-save-changes" data-dismiss="modal" aria-hidden="true">Да</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Нет</button>
    </div>
</div>

<!-- Users Emails Modal -->
<div id="modalUserEmails" data-width="900" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Отправленные сообщения</h3>
    </div>
    <div class="modal-body">
        <div style="width: 100%; padding-bottom: 10px; display: inline-block; border-bottom: 1px solid #eee">
            <span class="span1">Дата</span>
            <span class="span5">Тема</span>
            <span class="span2">Отправитель</span>
            <span>&nbsp;</span>
        </div>
        <div class="emails-list"></div>
    </div>
    <div class="modal-footer">
        <button class="btn " data-dismiss="modal" aria-hidden="true">Ok</button>
    </div>
</div>

<!-- Users Emails Modal -->
<div id="modalUserEmailBodyDetailed" data-width="500" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Полное содержание письма</h3>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <button class="btn " data-dismiss="modal" aria-hidden="true">Ok</button>
    </div>
</div>

<!-- Users Emails Modal -->
<div id="modalInviteUser" data-width="500" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Пригласить пользователя</h3>
    </div>
    <div class="modal-body">
        <div class="invited-users">

        </div>
        <input style="width: 100%; padding: 5px 0 5px 5px;" type="text" class="invite-name" placeholder="Введите фамилию и имя">
        <div class="available-invited-users">

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary btn-invite-member">Пригласить</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>

<!-- Users Emails Modal -->
<div id="modalShowResponseAfterInviteMembers" data-width="400" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-body">
        <div class="response">

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Ok</button>
    </div>
</div>
<script>
var globalSingleCity = "<?php echo $singleCity; ?>";
    $(document).ready (function (){
        window.user_settings = "<?php echo $userSettings; ?>".split(',');
        setAdminRole();

        // data-close_registration="'+m.close_registration+'" data-stop_registration="'+m.stop_registration+'"
        <?php
        list ($adminName, $adminEmail) = db_getMemberNameEmail ($memberId);
        echo '$("#sendMsgName, #sendLetterName").val("'.htmlspecialchars($adminName).'"); $("#sendMsgEmail, #sendLetterEmail").val("'.htmlspecialchars($adminEmail).'");';
        echo '$("#sendMsgNameAdmin, #sendLetterNameAdmin").val("'.htmlspecialchars($adminName).'"); $("#sendMsgEmailAdmin, #sendLetterEmailAdmin").val("'.htmlspecialchars($adminEmail).'");';
        ?>
        $("#sendMsgName, #sendMsgEmail, #sendMsgNameAdmin, #sendMsgEmailAdmin").keyup();

        $('.tab-content').addClass($(document).width()<980 ? 'phone' : 'desc');

        var eventIdInit = $("#events-list").val();
        if(eventIdInit){
            checkStopEventRegistration(eventIdInit);
            loadDashboard(eventIdInit);
        }

        handleControlButtons();
    });


    $('.searchMemberToAdd').keyup(function(){
       loadMembersList ();
    });

    $('.filter-arrived').click(function(){
        var element = $(this);
        element.hasClass('active') ? element.removeClass('active') : element.addClass('active');
        element.siblings().removeClass('active');
        loadDashboard();
    });

    $('.filter-regstate').change(function(){
        filterMembers();
    });

    $("select[class*='filterLocality']").change(function(){
        filterMembers();
    });

    function updateDownloadButton(eventId, membersLength){
        eventId.find(".downloadGeneral")[ membersLength === 0 ? "addClass" : "removeClass" ] ("disabled");
    }

    function handleControlButtons(){
        var eventId = $("#events-list").val(),
            element = $('#eventTab-'+eventId),
            hasAccessToAllLocals = parseInt($('#eventTab-'+eventId).attr('data-access')) === 1;

            if(hasAccessToAllLocals){
                element.find(".filter-icons").show();
            }
            else{
                element.find(".filter-icons").hide();
            }
    }

    function buildFilterLocalitiesList(eventId, localities){
        var form = $('#eventTab-'+eventId);
        if (localities) {
          var sortable = [];
          for (var vehicle in localities) {
              sortable.push([vehicle, localities[vehicle]]);
          }
          sortable.sort(function(a, b) {
            if (a[1] > b[1]) {
              return 1;
            }
            if (a[1] < b[1]) {
              return -1;
            }
            return 0;
          });
          localities =[];

          for (var i = 0; i < sortable.length; i++) {
            localities[sortable[i][0]] = sortable[i][1];
          }
        }
        if(eventId){
            var arr = [], value = form.find(".filterLocality-"+eventId).val();
            arr.push("<option value='_all_' "+ (!value || value === '_all_' ? 'selected' : '') + ") +>Все местности</option>\n\
                      <option value='without' "+ (value === 'without' ? 'selected' : '') +">Местность не указана</option>");

            $('#eventTab-'+eventId).find(".filterLocality-"+eventId).html(rebuildLocationsList(localities, value, arr).join(""));
        }
        else{
            $('#eventTab-'+eventId).find(".filterLocality-"+eventId).html('');
        }
    }

    function rebuildMemberList (members){
        var arr = [], checkFilter = $("#addMemberTableHeader");
        if(members){
            for(var m in members){
                var member = members[m];
                arr.push(
                "<tr id='mr-"+m+"' class='member-row'><td><input type='checkbox'></td><td>"+ he (member.name) + "</td><td>"+
                    he(member.locality) + "</td></tr>");
            }
        }

        if(arr.length > 0){
            arr.length > 1 ? checkFilter.show() : checkFilter.hide();
            $(".membersTable").show();
            $(".membersTable tbody").html(arr.join(""));

            $(".member-row > td > input[type='checkbox']").change (function (){
                if ($(".member-row > td > input[type='checkbox']:checked").length>0) $("#btnDoAddMembers").removeClass ("disabled");
                else $("#btnDoAddMembers").addClass ("disabled");
            });
        }
        else{
            checkFilter.hide();
            $(".membersTable tbody").html("");
            $(".membersTable").hide();
        }
    }

    function getLocalities(event, cb){
        $.post('/ajax/get.php?get_localities', {event : event})
         .done(function(data){
             cb(data);
         });
    }

    function loadMembersList (){
        var locId = $("#selAddMemberLocality").val();
        var catId = $("#selAddMemberCategory").val();
        var text = $(".searchMemberToAdd").val().trim().toLowerCase();
        var event = $("#events-list").val();
        var hasAccessToAllLocals = parseInt($('#eventTab-'+event).attr('data-access')) === 1;

        locId = locId && locId !== '_all_' ? locId : null ;
        catId = catId && catId !== '_all_' ? catId : null;
        text = text && text.length >= 3 ? text : null;

        var arr = [];
        arr.push("<option value='_all_' selected>&lt;все местности&gt;</option>");

        getLocalities(event, function(data){
          if ($('#selAddMemberLocality').val() == undefined || $('#selAddMemberLocality').val() === '_all_') {
            $("#selAddMemberLocality").html(rebuildLocationsList(data.localities, locId, arr).join(""));
          }
        });

        if(locId || catId || text || !hasAccessToAllLocals){
            $.post('/ajax/get.php?get_members', {
               event : event,
               locId: locId && locId !== '_all_' ? locId : null ,
               catId: catId && catId !== '_all_' ? catId : null,
               text : text ? text : null})
            .done(function(data){
                $(".member-row > td > input[type='checkbox']").attr('checked', false);
                $("#addMemberEventTitle").text ($('#events-list option:selected').text());
                $("#btnDoAddMembers").addClass ("disabled");

                hasAccessToAllLocals && !locId ? $('.searchBlock').show() : $('.searchBlock').hide();
                rebuildMemberList (data.members);
            });
        }
        else{
            $("#btnDoAddMembers").addClass ("disabled");
            $('.searchBlock').show();
            rebuildMemberList ();
        }
    }

    function setAdminRole (memberId){
        var eventId = $("#events-list").val();
        var adminRole = '<?php echo db_getAdminRole($memberId); ?>';
        var isEventPrivate = parseInt($("#eventTab-"+eventId).attr("data-private")) === 1;

        if(memberId){
            $.post('/ajax/get.php?get_regstate', { eventAdmin:eventId, memberKey: memberId})
            .done (function(data) {
                handleFieldsByAdminRole(parseInt(adminRole), isEventPrivate, data.regstate);
            });
        }
        else{
            handleFieldsByAdminRole(parseInt(adminRole), isEventPrivate);
        }
    }

    $(".chk-invite").click(function(){
        if($('.registration-closed').children().length > 0){
            showModalHintWindow("<strong>Онлайн-регистрация на это мероприятие закрыта.<br>По всем вопросам <a href='' data-toggle='modal' data-target='#modalEventSendMsg'>обращайтесь к команде регистрации.</a> </strong>");
        }
        else{
            var modal = $("#modalInviteUser");
            modal.find('.invited-users').html('');
            modal.find('.available-invited-users').html('');
            modal.modal('show');
            setTimeout(function(){ modal.find('.invite-name').focus} , 500);
        }
    });

    $(".invite-name").keyup(function(e){
        e.stopPropagation();
        var value = $(this).val().trim();
        if (value.length < 3) {
          return;
        }
        var modal = $("#modalInviteUser");

        $.post('/ajax/get.php?get_users_to_invite', {name : value})
        .done(function(data){
            var users = data.users, usersArr = [];

            for(var i in users){
                usersArr.push('<div class="available-user" data-name="'+users[i].name+'" data-locality="'+users[i].locality +'" data-member="'+users[i].id+'" >'+users[i].name+' ('+users[i].locality +') <i style="color:green;" class="fa fa-plus"></i></div>');
            }

            modal.find('.available-invited-users').html(usersArr.join(''));

            $(".available-user").click(function(e){
                e.stopPropagation();
                var memberId = $(this).attr('data-member'),
                    locality = $(this).attr('data-locality'),
                    memberName = $(this).attr('data-name');
                buildInvitedUsers({name : memberName, id : memberId, locality : locality});
            });
        });
    });

    function buildInvitedUsers(user, toRemove = false){
        var modal = $("#modalInviteUser"), usersArr = [];

        modal.find(".invited-users div").each(function(){
            var memberId = $(this).attr('data-member'),
                locality = $(this).attr('data-locality'),
                name = $(this).attr('data-name');

            if(toRemove && memberId === user){
                return;
            }
            else{
                usersArr.push('<div class="invited-user" data-name="'+name+'" data-locality="'+locality +'" data-member="'+memberId+'" >'+name+' ('+locality +') <i style="color:red;" class="fa fa-minus"></i></div>');
            }
        });

        if(!toRemove){
            usersArr.push('<div class="invited-user" data-name="'+user.name+'" data-locality="'+user.locality +'" data-member="'+user.id+'" >'+user.name+' ('+user.locality +') <i style="color:red;" class="fa fa-minus"></i></div>');
        }

        modal.find(".invited-users").html(usersArr.join(''));
        modal.find(".invite-name").val('');
        modal.find('.available-invited-users').html('');

        $(".invited-user").click(function(e){
            e.stopPropagation();
            var memberId = $(this).attr('data-member');
            buildInvitedUsers(memberId, true);
        });
    }

    $(".btn-invite-member").click(function(){
        var modal = $("#modalInviteUser"),
            usersArr = [],
            event = $("#events-list").val(),
            showAdminName = false;

        modal.find(".invited-users div").each(function(){
            usersArr.push($(this).attr('data-member'));
        });

        if(usersArr.length === 0){
            showError("Не выбран ни один человек для приглашения");
        }
        else{
            $.post('/ajax/set.php?invite_users', {users : usersArr.join(','), event : event, showAdminName : showAdminName})
            .done(function(data){
                modal.modal('hide');

                var result = data.result, text = '';

                if(result.alreadyAddedArr.length>0){
                    text = text + "<div style='margin-top:10px;'>" +
                    "<div><b>Эти люди уже были добавлены в список:</b></div>" + result.alreadyAddedArr.join('<br>') + "</div>";
                }
                if(result.emptyEmailArr.length>0){
                    text = text + "<div style='margin-top:10px;'>" +
                    "<div><b>У этих людей нет электронного адреса:</b></div>" + result.emptyEmailArr.join('<br>') + "</div>";
                }
                if(result.errorMembeEmailArr.length>0){
                    text = text + "<div style='margin-top:10px;'>" +
                    "<div>У этих людей некорректный email. Возможности отправить им письмо нет:</div>" + result.errorMembeEmailArr.join('<br/>') + "</div>";
                }
                if(result.errorMembeIdArr.length>0){
                    text = text + "<div style='margin-top:10px;'>"+
                    "<div>Людей с такими id нет в базе данных:</div>" + result.errorMembeIdArr.join('<br/>') + "</div>";
                }
                if(result.sendEmailsArr.length>0){
                    text = text + "<div style='margin-top:10px;'>"+
                    "<div><b>Приглашение отправлено следующим людям:</b></div>"+result.sendEmailsArr.join('<br/>')+ "</div>";
                }

                showResponseAfterInviteMembers(text);
            });
        }
    });

    function showResponseAfterInviteMembers(text){
        var modal = $("#modalShowResponseAfterInviteMembers");

        modal.find('.response').html(text);
        modal.modal('show');
    }

    $('.search-text').bind("paste keyup", function(event){
        event.stopPropagation();
        var eventId = $("#events-list").val();
        var text = $('#eventTab-'+eventId).find('.search-text').val();
        if(text.length>=3 || text.length==0) loadDashboard();
    });

    function setFiltersForRequest(eventId){
        var sort_type = 'desc',
            sort_field = 'name',
            el =$('#eventTab-'+eventId),
            hasAccessToAllLocals = parseInt($('#eventTab-'+eventId).attr('data-access')) === 1;

        el.find(" a[id|='sort']").each (function (i) {
            if ($(this).siblings("i.icon-chevron-down").length) {
                sort_type = 'asc';
                sort_field = $(this).attr("id").replace(/^sort-/,'');
            }
            else if ($(this).siblings("i.icon-chevron-up").length) {
                sort_type = 'desc';
                sort_field = $(this).attr("id").replace(/^sort-/,'');
            }
        });

        var searchText = el.find('.search-text').val() ? el.find('.search-text').val().trim() : '',
            regstate = [],
            attended = '';

        el.find('.filter-arrived').each(function(){
            if($(this).hasClass('active')){
                attended = $(this).attr('data-attended');
            }
        });

        /*
        el.find('.filter-users').each(function(){
            if($(this).hasClass('active')){
                regstate.push($(this).attr('data-regstate'));
            }
        });
        */

        regstate = el.find('.filter-users').val();

        var coord = hasAccessToAllLocals && el.find('.filter-coord').hasClass('active') ? 1 : '';
        var service = hasAccessToAllLocals && el.find('.filter-service').hasClass('active') ? 1 : '';
        var locality = el.find('.filterLocality-'+eventId).val();
        var localityFilter = locality && locality !== "_all_" ? locality : '';
        var filters = [];
        filters = [{name: "sort_field", value: sort_field},
                   {name: "sort_type", value: sort_type},
                   {name: "searchText", value: searchText},
                   {name: "attended", value: attended},
                   {name: "regstate", value: regstate || ''},
                   {name: "coord", value: coord},
                   {name: "service", value: service},
                   {name: "localityFilter", value: localityFilter}];
        return filters;
    }

    function getRequestFromFilters(arr){
        var str = '';
        arr.map(function(item){
            str += ('&'+item["name"] +'='+item["value"]);
        });
        return str;
    }

    function filterMembers(){
        var eventId = $("#events-list").val();
        var mode = $(document).width() < 768 ? " .show-phone" : " .desctopVisible";

        var localityFilter = $(".filterLocality-"+eventId+" option:selected").text().trim();
        var regstateFilter = $("#eventTab-"+eventId+" .filter-regstate").val();
        var memberLocality, memberRegstate, localityList = [], memberLocalityKey;
        var localityFilterList = $(".filterLocality-"+eventId).val();

        if(localityFilterList){
            localityList = localityFilterList.split(',');
        }

        $("#eventTab-"+eventId+mode+" tbody tr[class!='regmem']").each(function(){
            memberLocality = $(this).attr('data-locality');
            memberLocalityKey = $(this).attr('data-locality_key');
            memberRegstate = $(this).attr('data-regstate');
            (localityFilter === 'Все местности' || in_array(memberLocalityKey, localityList) || (memberLocality==='' && localityFilter === 'Местность не указана' ))
            && (regstateFilter === '_all_' || (memberRegstate === 'null' && regstateFilter==='01') || (memberRegstate === '03' && regstateFilter==='03') || (memberRegstate === '04' && regstateFilter==='04') || (memberRegstate === '05' && regstateFilter==='05') || ((memberRegstate === '01' || memberRegstate === '02') && regstateFilter==='02')) ?
            $(this).show() : $(this).hide();
        });
    }

    function loadDashboard (eventId){
        if (!eventId) eventId = $("#events-list").val();
        var request = getRequestFromFilters(setFiltersForRequest(eventId));

        $.getJSON('/ajax/dashboard.php?event='+eventId+request)
        .done (function(data) {
            refreshEventMembers (eventId, data.members, data.localities);
        });
    }

    $(".statService").click(function(e){
        e.stopPropagation();
        var eventName = $("#events-list option:selected").text(),
            states = [], localities = [],
            countParking = 0, countTransport = 0,
            countAccomSisters = 0, countAccomBrothers = 0,
            countBrothers = 0, countSisters = 0,
            locality, state, male, accom, transport, parking;

        $("div.tab-pane.active .desctopVisible tr[class|='regmem'] ").each(function () {
            locality = $(this).attr("data-locality");
            state = $(this).attr("data-regstate");
            male = $(this).attr("data-male");
            accom = $(this).attr("data-accom");
            transport = $(this).attr("data-transport");
            parking = $(this).attr("data-parking");

            if(parking === '1'){
                countParking++;
            }

            if(transport === '1'){
                countTransport++;
            }

            if(accom === '1' && male === '1' && state === '04'){
                countAccomBrothers++;
            }

            if(accom === '1' && male === '0' && state === '04'){
                countAccomSisters++;
            }

            if(male === '1' && state === '04'){
                countBrothers++;
            }

            if(male === '0' && state === '04'){
                countSisters++;
            }
            states.push($(this).attr('data-regstate'));
            if(!in_array(locality, localities) && state !== '03' && state !== '05')
                localities.push(locality);
        });

        getStatistics(states, eventName, localities.length, countParking, countTransport, countAccomSisters, countAccomBrothers, countBrothers, countSisters);
    });

    $('.aid-statistic').click(function(){
        var eventId = $("#events-list").val();
        var eventName = $("#events-list option:selected").text();

        $.getJSON('/ajax/get.php', { eventIdAid: eventId})
            .done (function(data) {
                getAidInfo (data.members, eventName, eventId);
            });
    });

    if($(document).width()<768){
        $("[data-sort]").click(function(){
            var s = $(this).attr('data-sort');
            $(".sortName").text(s);
            document.cookie = "sort=" + s;
        });
    }
    var localityGlo = [];
    function refreshEventMembers (eventId, members, localities){
        var tableRows = [];
        var phoneRows = [];

        function get_localities(){
            $.get('/ajax/members.php?get_localities')
            .done (function(data) {
                $(data.localities).each(function(i) {
                  var localityTemp = data.localities[i];
                  a = localityTemp.id;
                  b = localityTemp.name;
                  localities[a] = b;
                  localityGlo[a] = b;
                });
            });
        }
        get_localities();
setTimeout(function () {
        buildFilterLocalitiesList(eventId, localities);

        var showLocalityField = $("#eventTab-"+eventId).attr("data-show-locality-field") ===  '1';

        for (var i in members){
            var m = members[i];

            // *** last editor
            var notMe = (m.mem_admin_key && m.mem_admin_key!=window.adminId) || (m.reg_admin_key && m.reg_admin_key!=window.adminId);
            // if the author is same for reg and mem records is was decided to show it only once
            var editors = (m.mem_admin_key ? m.mem_admin_name : '') +
                          (m.mem_admin_key && m.reg_admin_key && m.mem_admin_key != m.reg_admin_key ? ' и ' : '') +
                          (m.reg_admin_key && m.mem_admin_key != m.reg_admin_key ? m.reg_admin_name : '');
            var htmlEditor = notMe ? '<i class="icon-user" title="Последние изменения: '+editors+'"></i>': '';

            // *** changes processed
            var htmlChanged = (m.changed > 0 ? '<i class="icon-pencil" title="Изменения еще не обработаны командой регистрации"></i>' : '');

            // *** email sending result
            var htmlEmail = '';
            if (m.send_result!='')
                if (m.send_result=='ok')
                    htmlEmail = '<i class="icon-envelope show-messages" title="Письмо было отправлено"></i>';
                else if (m.send_result=='queue')
                    htmlEmail = '<i class="icon-time" title="Письмо ждет отправки"></i>';
                else
                    htmlEmail = '<i class="icon-warning-sign" title="'+he(m.send_result)+'"></i>';

            // *** living place
            var htmlPlace = m.place!=null ? '<i class="icon-flag"></i>' : '';
            var htmlPlaceFlag = m.place!=null ? '<span style="font-size:10px; color:grey;">'+he(m.place)+'</span>' : '';

            // *** service
            var htmlService = m.service ? '<i class="fa fa-wrench" title="'+he(m.service)+'" aria-hidden="true"></i>' :'';
            var coordFlag = m.coord == '1' ? '<i title="Координатор" class="fa fa-random" aria-hidden="true"></i>' : '';

            var dataItems = 'data-accom="'+m.accom+'" data-transport="'+m.transport+'" data-locality_key="'+m.locality_key+'" data-male="'+m.male+'" '+
                    'data-parking="'+m.parking+'" data-regstate="'+m.regstate+'" data-prepaid="'+m.prepaid+'" data-locality="'+he(m.locality)+'"' +
                    'data-attended="'+m.attended+'" data-aid_paid="'+(m.aid_paid || 0)+'" data-paid="'+m.paid+'" '+
                    'data-place="'+(m.place || "") +'" data-service="'+m.service_key+'" data-status="'+m.status_key+'" data-coord="'+m.coord+'" data-mate="'+m.mate_key+'" '+
                    'data-aid_amount="'+m.contr_amount+'" data-comment="'+he(m.admin_comment.length > 0 ? 1 : 0)+'" data-currency="'+(m.currency || '') +'"';
            // console.log(m);

            // Cut the m.region string. Roman's code ver 5.0.1
            if (!m.region) {
              m.region = '--';
            } else if (m.region =='--') {
              m.region = m.country;
            } else {
              m.region = m.region.substring(0, m.region.indexOf(" ("));
              // m.region += ', ';
              // m.region += m.country;
            }

            tableRows.push('<tr class="regmem-'+m.id+'" '+ dataItems +' >'+
                '<td class="style-checkbox"><input type="checkbox"></td>'+
                '<td class="style-name mname '+(m.male==1?'male':'female')+'"><span class="mname1">'+ he(m.name) +
                (in_array(1, window.user_settings) ? '</span><br/>'+ '<span class="mnameCategory user_setting_span">'+m.category_name+'</span>' : '</span>'+ '<span class="mnameCategory user_setting_span" style="display: none;">'+m.category_name+'</span>') +
                '</td>' +
                (showLocalityField ? '<td class=style-city>' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') +
                (in_array(2, window.user_settings) ? '<br/>'+ '<span class="user_setting_span">'+(m.region || m.country)+'</span>' : '') +
                    '</td>' : '') +
                '<td class="style-cell hide-tablet">' + he(m.cell_phone) +
                (in_array(3, window.user_settings) ? '<br/>'+ '<span class="user_setting_span">'+m.email+'</span>' : '') +
                '</td>' +

               '<td class="style-serv hide-tablet"><div>'+ (m.status ? he(m.status) : '') +'<br>'+'<span class="user_setting_span">'+(m.service ? he(m.service) : '')+ '</span>' + '</div>' + ( m.coord == '1' ? '<div>Координатор</div>' : '') + '</td>' +

                '<td class="style-date"><span class="arrival" data-date="' + he(m.arr_date) + '" data-time="' + he(m.arr_time) + '">' + formatDDMM( m.arr_date) + '</span> - '+
                '<span class="departure" data-date="' + he(m.dep_date) + '" data-time="' + he(m.dep_time) + '">'+ formatDDMM(m.dep_date) + '</span><br>'+htmlPlace + ' ' +htmlPlaceFlag+'</td>'+
                '<td>' + htmlLabelByRegState(m.regstate, m.web) +
                '<ul class="regstate-list-handle">'+ htmlListItemsByRegstate(m.regstate, m.attended) + '</ul>'+
                "<div class='regmem-icons'>"+ htmlEmail + htmlChanged + htmlEditor + '</div></td>'
                + '</tr>'
            );

            phoneRows.push ('<tr class="regmem-'+m.id+'" '+ dataItems +' >'+
                '<td class="arrival" data-date="' + he( m.arr_date) + '" data-time="' + he(m.arr_time) + '"><input type="checkbox"></td>'+
                '<td class="departure" data-date="' + he(m.dep_date) + '" data-time="' + he(m.dep_time) + '"><span class="mname '+(m.male==1?'male':'female')+'"><span class="mname1">' + he(m.name) + '</span>' +
(in_array(1, window.user_settings) ? '<br/>'+ '<span class="user_setting_span mnameCategory">'+m.category_name+'</span>' : '') +
                "</span> " +
                (showLocalityField ? '<div>' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') +(in_array(2, window.user_settings) ? '<br/>'+ '<span class="user_setting_span">'+(m.region || m.country)+'</span>' : '') +
                    '</div>' : '') +
                '<div><span>'+ he(m.cell_phone) + '</span>'+ (m.cell_phone && m.email ? ', ' :' ' )+
                (in_array(3, window.user_settings) ? '<br/>'+ '<span class="user_setting_span">'+m.email+'</span>' : '') +
                '</div>'+
                '<div><span>' + (m.status ? he(m.status) : '') + '<br>'+ (m.service ? he(m.service) : '') + '</span></div>' +
                '<div><span class="arrival" data-date="' + he(m.arr_date) + '" data-time="' + he(m.arr_time) + '">' +
                formatDDMM(m.arr_date) + '</span>'+
                '<span class="departure" data-date="' + he(m.dep_date) + '" data-time="' + he(m.dep_time) + '">'+ ' - '+formatDDMM(
                    m.dep_date) + '</span>'+ htmlPlace + ' ' +htmlPlaceFlag + '</div>'+
                '<span>' + htmlLabelByRegState(m.regstate, m.web) +
                '<ul class="regstate-list-handle">'+ htmlListItemsByRegstate(m.regstate, m.attended) + '</ul>'+
                " <span class='regmem-icons'>" + coordFlag + htmlService + htmlEmail + htmlChanged + htmlEditor + '</span></span>'
                + '</td></tr>');
        }

        $("#eventTab-"+eventId+" table.reg-list tbody").html (tableRows.join(''));
        $("#phoneRegList-"+eventId+" tbody").html (phoneRows.join(''));

        var elemList = $("tr[class|='regmem']");
        elemList.unbind('click');
        elemList.click (function (){
            var memberId = $(this).attr ('class').replace(/^regmem-/,'');
                eventId = $("#events-list").val();
            var localityKeyMem = $(this).attr('data-locality_key');
            var localityValid = 0;
            for (var j in localityGlo) {
              if (localityKeyMem == j) {
                localityValid = 1;
              }
              /*if (localityGlo.hasOwnProperty(j)) {
              }*/
            }

            $.getJSON('/ajax/get.php', { member: memberId, event: eventId, fullList: localityValid})
            .done (function(data){
                fillEditMember (memberId, data.eventmember, data.localities);
                setAdminRole(memberId);
                $('#modalEditMember').attr('data-member_id', memberId);
                $('#modalEditMember').attr('data-locality_key', localityKeyMem);
                $('#modalEditMember').modal('show');
                $('.emName').removeClass('create-member');
            });
        });

        var elemCheckbox = $("tr[class|='regmem'] input[type='checkbox']");
        elemCheckbox.unbind('click');
        elemCheckbox.click (function (event){
            event.stopPropagation();
        });

        elemCheckbox.unbind('change');
        elemCheckbox.change (function (){
            updateTabPaneButtons ($(this).parents('div.tab-pane'));
            updateTabPaneAdditionalButtons($(this).parents('div.tab-pane'));
        });

        $("#eventTab-"+eventId+" table.reg-list th input[type='checkbox']").prop ('checked', false);
        updateTabPaneButtons ($('#eventTab-'+eventId));
        updateDownloadButton($('#eventTab-'+eventId), members.length);
        filterMembers();

        $(".downloadItems").unbind('click');
        $('.downloadItems').click(function(){
            var fields = [];
            if ($('.tab-pane.active').attr('data-need_tp') === '1') {
              function checkForInternationalEvent(elementId) {
                if ($(elementId).prop('checked')===true) {
                  fields.push({'name': $(elementId).attr('data-download'), 'value': $(elementId).siblings("label").text()});
                };
              }
              checkForInternationalEvent('#download-city-member');
              checkForInternationalEvent('#download-country');
              checkForInternationalEvent('#download-birth-date-member');
              checkForInternationalEvent('#download-tp');
              checkForInternationalEvent('#download-post');
              checkForInternationalEvent('#download-phone-member');
              checkForInternationalEvent('#download-email-member');
              checkForInternationalEvent('#download-outline-language');
              checkForInternationalEvent('#download-study-group-language');
              checkForInternationalEvent('#download-arr-dep-date');
              checkForInternationalEvent('#download-flight-arr');
              checkForInternationalEvent('#download-arr-dep-time');
              checkForInternationalEvent('#download-airport-arrival');
              checkForInternationalEvent('#download-dep-date');
              checkForInternationalEvent('#download-flight-dep');
              checkForInternationalEvent('#download-dep-time');
              checkForInternationalEvent('#download-airport-departure');
            } else {
              $("#modalDownloadItems").find(".search-checkbox input[type='checkbox']").each(function(){
                  if ($(this).prop('checked')===true && $(this).attr('id') != 'member_name'){
                      fields.push({'name': $(this).attr('data-download'), 'value': $(this).siblings("label").text()});
                  }
              });
            }

            var item = $('.downloadExl').data('download'),
                eventIdReal = $("#events-list").val(),
                eventType = $("#eventTab-"+eventIdReal).attr("data-event_type"),
                needTranslate = $("#modalDownloadItems #download-translate").prop('checked');



            getDataToDownload(item, eventIdReal, eventType, fields, needTranslate);
        });

        $(".downloadExl").unbind('click');
        $(".downloadExl").click(function(event){
            event.stopPropagation();
            var item = $(this).attr('data-download');
            var eventIdReal = $("#events-list").val();
            var eventType = $("#eventTab-"+eventIdReal).attr("data-event_type");

            if(item === 'all'){
                var eventNeedFlight = $("#eventTab-"+eventIdReal).attr("data-need_flight") == '1',
                    modal = $('#modalDownloadItems');
                eventNeedFlight ? modal.find(".translate").show() : modal.find(".translate").hide();

                modal.find('#check-all').prop('checked', true);
                modal.find('#uncheck-all').prop('checked', false);

                var custom_input = modal.find('.custom-download-item'),
                    custom_list_item = $('.tab-pane.active').attr('data-custom_list_item');

                if(custom_list_item){
                    custom_input.show();
                    custom_input.find('label').text(custom_list_item)
                }
                else{
                    custom_input.hide();
                    custom_input.find('label').text('');
                }

                modal.modal('show').find("input[type='checkbox']").each(function(){
                  if (!eventNeedFlight && $(this).parents('div').hasClass('translate')) {
                    if ($(this).attr('id') != 'member_name') {
                      $(this).prop('checked', false);
                      $(this).attr('disabled', false);
                      //$(this).parent().show();
                    }
                  } else if ($('.tab-pane.active').attr('data-need_tp') === '1'){
                    if ($(this).attr('id') != 'member_name') {
                      $(this).prop('checked', true);
                      //$(this).parent().show();
                      //$(this).attr('disabled', false);
                    }
                  } else {
                    if ($(this).attr('id') != 'member_name') {
                      $(this).prop('checked', true);
                      $(this).attr('disabled', false);
                      //$(this).parent().show();
                    }
                  }
                });
/*
                $('#modalDownloadItems').on('show', function() {

                })
*/
            xlxCheckboxesInternationalDisabled();
            }
            else{
                getDataToDownload(item, eventIdReal, eventType);
            }
        });

        $(".btnRegstate").click(function(e){
            e.stopPropagation();
            var modal = $("#modalMemberRegstate");

            modal.modal('show');
        });

        $(".btnHandleRegstate").unbind('click');
        $(".btnHandleRegstate").click(function(e){
            e.stopPropagation();
            var thisSibling = $(this).siblings('.regstate-list-handle');

            if(thisSibling.css('display') === 'block'){
                    thisSibling.css('display', 'none');
            }
            else{
                $('.regstate-list-handle').each(function(){
                    if(thisSibling !== $(this))
                        $(this).css('display', 'none');
               });
               thisSibling.css('display', 'block');
            }
        });

        $(".regstate-list-handle li").unbind('click');
        $(".regstate-list-handle li").click(function(e){
           e.stopPropagation();
           var setstate = $(this).attr('data-action'), eventId = $("#events-list").val(), memberId = $(this).parents("tr").attr('class').replace(/^regmem-/,'');

           $('.regstate-list-handle').css('display', 'none');

           var request = getRequestFromFilters(setFiltersForRequest(eventId));

           $.getJSON('/ajax/set.php?set_state'+request, {event:eventId,  memberId: memberId, setstate: setstate })
           .done (function(data) {
               refreshEventMembers (eventId, data.members, data.localities);
           });
        });

        $(".show-messages").click(function(e){
            e.stopPropagation();
            var memberId = $(this).parents('tr').attr('class').replace(/^regmem-/,''),
                eventId = $("#events-list").val();

            $.post('/ajax/get.php?get_user_emails', {memberId:memberId, eventId:eventId}).done(function(data){
                if(data.emails){
                    buildUserEmailsList(data.emails);
                }
                else{
                    showHint("Для данного участника нет отправленных писем.");
                }
            });
        });
      }, 200);
    }

    function buildUserEmailsList(emails){
        var modal = $("#modalUserEmails"), list = [], item;

        for (var i in emails){
            item = emails[i];
            list.push('<div style="min-height: 25px; height:auto; padding-top: 5px; border-bottom: 1px solid #eee; display: flow-root" data-id="'+item.id+'">'+
                        '<span class="span1">'+formatDDMM(item.date)+'</span>'+
                        '<span class="span5"><strong>'+he(item.subject)+'</strong></span>'+
                        '<span class="span2">'+he(item.sender)+'</span>'+
                        '<span class=" showEmaildetails show-message-details" style="float:right"><i class="fa fa-chevron-down" title="Показать подробности содержания" aria-hidden="true"></i></td>'+
                    '</div>'+
                    '<div style="border-bottom:1px solid #eee; padding: 5px 20px 10px 20px; display:none;" data-detail="'+item.id+'">'+(item.body)+'</div>');
        }

        modal.find('.emails-list').html(list.join(''));
        modal.modal('show');

        $("#modalUserEmails .emails-list div").click(function(){
            var showDetails = $(this).find('.showEmaildetails').hasClass('show-message-details'),
                emailId = $(this).attr('data-id');

            if(showDetails){
                $(this).find('.showEmaildetails').removeClass('show-message-details').addClass('hide-message-details').html('<i class="fa fa-chevron-up" title="Скрыть подробности содержания" aria-hidden="true"></i>');
                $(this).parents('#modalUserEmails').find('div[data-detail="'+emailId+'"]').show();
            }
            else{
                $(this).find('.showEmaildetails').addClass('show-message-details').removeClass('hide-message-details').html('<i class="fa fa-chevron-down" title="Показать подробности содержания" aria-hidden="true"></i>');
                $(this).parents('#modalUserEmails').find('div[data-detail="'+emailId+'"]').hide();
            }

            //$("#modalUserEmailBodyDetailed").modal('show');
        });
    }

    $(document).click(function(){
        $('.regstate-list-handle').css('display', 'none');
    });

    function getDataToDownload(item, eventId, eventType, fields, needTranslate){
        $.getJSON('/ajax/dashboard.php', {event : eventId})
        .done(function(data){
           downloadMembersList(item, data.members.length, data.members, eventType, fields, needTranslate);
        });
    }

    $("#check-all").click(function(){
        var checked = $(this).prop('checked');
        $("#uncheck-all").prop('checked', !checked);
        handleCheckboxForDownloading(checked);
    });

    $("#uncheck-all").click(function(){
        var checked = $(this).prop('checked');
        $("#check-all").prop('checked', !checked);
        handleCheckboxForDownloading(!checked);
    });

    function handleCheckboxForDownloading(checkedAll){
        $("#modalDownloadItems .search-checkbox input[type='checkbox']").each(function(){
          if ($(this).attr('id') != 'member_name') {
            $(this).prop('checked', checkedAll);
          }
        });
          xlxCheckboxesInternationalDisabled();
    }

    function downloadMembersList(item, countMembersAll, members, eventType, fields=false, needTranslate=false){
        var doc = '&document=', req = "&memberslength="+countMembersAll+"&"+item+"=true&adminId="+window.adminId+"&page=reg&event_type="+eventType+"&need_translation=" +(needTranslate? "yes" : "no");

        if (fields){
            doc += JSON.stringify(fields);
        }

        $.ajax({
            type: "POST",
            url: "/ajax/excelList.php",
            data: "members="+JSON.stringify(members)+req+doc,
            cache: false,
            success: function(data) {
                document.location.href="./ajax/excelList.php?file="+data;
                setTimeout(function(){
                    deleteFile(data);
                }, 10000);
            }
        });
    }

    function updateTabPaneButtons (tabPane) {
        if (tabPane.find ("tr[class|='regmem'] input[type='checkbox']:checked").length>0)
           tabPane.find (".chk-dep").removeClass ("disabled");
        else
           tabPane.find (".chk-dep").addClass ("disabled");
    }

    function updateTabPaneAdditionalButtons(tabPane){
        if (tabPane.find ("tr[class|='regmem'] input[type='checkbox']:checked").length>0)
           $('.aditional-menu').find (".chk-dep").removeClass ("disabled");
        else
           $('.aditional-menu').find (".chk-dep").addClass ("disabled");
    }

    function getMembersInfo(members){
        $('#modalEditMember').modal('hide');
        $('#modalMatchMem').modal('show');

        var tableRows = [];

        for (var i in members) {
            var m = members[i];
            tableRows.push('<tr data-id="'+m.member_key+'"><td><input type="checkbox"></td><td class="chkName">'+he(m.name)+'</td>'+
                    '<td class="chkBirDate">'+he(m.birth_date)+'</td><td class="chkLocal">'+he(m.locality_name)+'</td></tr>');
        }

        $("table.chkMember tbody").html (tableRows.join(''));
    }

    $('.chooseMemb').on('click', function(event){
        event.stopPropagation();
        $('#modalMatchMem').modal('hide');
        var eventId = $("#events-list").val();
        var memberId = $("table.chkMember input[type='checkbox']:checked").parents ("tr").attr('data-id');
        if(memberId && memberId !== undefined){
            $.getJSON('/ajax/get.php', { memberCheck: memberId, event : eventId, create: true})
            .done (function(data) {
                fillEditMember (memberId, data.eventmember, data.localities);
                $('#btnDoSaveMember').addClass('create');
                $('#btnDoSaveMember').addClass('locality_all');
                $('#modalEditMember').attr('data-member_id', memberId);
                $('#modalEditMember').modal('show');
            });
            setAdminRole(memberId);
        }
    });

    $('.regMemb').click(function(){
        $('#modalEditMember').attr('data-member_id', '');
        $('#modalEditMember').modal('show');
        $('#modalMatchMem').modal('hide');
    });

    $('.emName').on('blur', function(){
        if($(this).hasClass('create-member')){
            var name = $(this).val();

            // check this
            $('#btnDoSaveMember').addClass('create');
            $.post('/ajax/get.php', {name:name })
            .done (function(data) {
                if(data.members){
                    getMembersInfo(data.members);
                }
            });
        }
    });

    function saveMember (doRegister) {
        var elem =$('#btnDoSaveMember'),
            el = $('#modalEditMember');
            //el = $('#modalEditMember').find($(document).width() > 980 ? '.desctop-visible' : '.tablets-visible');
        if ((el.find(".emLocality").val () == "_none_" && el.find(".emNewLocality").val().trim().length==0) || el.find(".emCitizenship").val () == "_none_" || el.find(".emGender").val () == "_none_" || el.find(".emName").val().trim().length==0 || el.find(".emParking").val () == "_none_") {
          showError("Укажите гражданство, пол, ФИО и населенный пункт");
          $(".localityControlGroup").addClass ("error");
          window.setTimeout(function() { $(".localityControlGroup").removeClass ("error"); }, 2000);
          return;
        }
        /*if (el.find(".emArrDate").val().trim().length==0 && el.find(".emDepDate").val().trim().length==0 || el.find(".emAccom").val () == "_none_" || el.find(".emBirthdate").val().trim().length==0 || el.find(".emCategory").val () == "_none_" || el.find(".emStatus").val () == "_none_") {
          showError("Необходимо заполнить все поля отмеченные розовым");
          $(".localityControlGroup").addClass ("error");
          window.setTimeout(function() { $(".localityControlGroup").removeClass ("error"); }, 2000);
          return;
        }*/
        /*if (el.find(".emParking").val () == "1" && (el.find(".emAvtomobileNumber").val().trim().length==0 || el.find(".emAvtomobile").val().trim().length==0)) {
          showError("Необходимо заполнить номер, марку и цвет автомобиля");
          $(".localityControlGroup").addClass ("error");
          window.setTimeout(function() { $(".localityControlGroup").removeClass ("error"); }, 2000);
          return;
        }*/
        if ((elem.hasClass ("disable-on-invalid") || doRegister) && el.find(".emLocality").val () == "_none_" && el.find(".emNewLocality").val().trim().length==0) {
            showError("Необходимо выбрать населенный пункт из списка или если его нет, то указать его название");
            $(".localityControlGroup").addClass ("error");
            window.setTimeout(function() { $(".localityControlGroup").removeClass ("error"); }, 2000);
            return;
        }

        if(el.find('.emAid').val() === '1' && el.find('.emContrAmount').val() === '0' && el.find('.emTransAmount').val() === '0'){
            setFieldError(el.find('.emContrAmount'), true);
            showError("Необходимо указать сумму требуемой помощи");
            return;
        }

        var eventId = $("#events-list").val();
        var create = elem.hasClass('create') ? "&create="+eventId+"&event="+eventId :  "&event="+eventId ;
        var request = getRequestFromFilters(setFiltersForRequest(eventId));

        var data = getValuesRegformFields(el);

        $.post("/ajax/set.php?member="+window.currentEditMemberId + request + create + (doRegister?"&register=yes":""), data)
        .done (function(data){
            refreshEventMembers (eventId, data.members, data.localities);
            $('#modalEditMember').modal('hide');
            elem.removeClass('create');
            $('.emName ').removeClass('create');
        });
    }

    $('.emName ~ .unblock-input').click(function (){
        $('#modalNameEdit').modal('show');
    });

    $('#btnDoNameEdit').click (function (){
        $ ('.emName ~ .unblock-input').hide ();
        $ (".emName").removeAttr ("disabled");
        setTimeout(function() {$(".emName").focus();}, 1000);
    });

    $("table.reg-list th input[type='checkbox']").click (function (){
          $(this).parents("table").find("tr[class|='regmem'] input[type='checkbox']:visible").prop('checked', $(this).is(':checked'));
        updateTabPaneButtons ($(this).parents('div.tab-pane'));
    });

    $('#modalAddMembers').on('hide', function (){
        $("#selAddMemberLocality, #selAddMemberCategory").val('_all_');
        $(".searchMemberToAdd").val('');
    });

    function hideExistingMemberRegistration() {
      var existRegistration = [];
      $('.reg-list tr:visible').each(function() {
        var classId = $(this).attr('class');
        classId = classId ? $(this).attr('class').replace(/^regmem-/,'mr-') : '';
        classId ? existRegistration.push(classId) : '';
      });
      $('.membersTable tr').each(function() {
        var current = $(this).attr('id');
        if ((existRegistration.indexOf(current) != -1) && existRegistration) {
          $(this).hide();
        }
      });
    }

    $('#modalAddMembers').on('show', function (e){
        e.stopPropagation();
        $('#searchBlockFilter').val('');
        loadMembersList ();
        setTimeout(function () {
            hideExistingMemberRegistration();
        }, 1500);
    });

    $("#selAddMemberLocality, #selAddMemberCategory").change (function (){
        $('#searchBlockFilter').val('');
        loadMembersList ();
        setTimeout(function () {
          hideExistingMemberRegistration();
        }, 1500);
    });

    $("#btnDoSaveMember").click (function (){
        if (!$(this).hasClass('disabled')){
          if (checkAgeLimit(".tab-pane.active","data-start", false)) {
              saveMember(false);
            }
        }
        else{
            showError("Необходимо заполнить все обязательные поля, выделенные розовым фоном!", true);
        }
    });

    $("#btnDoRegisterMember").click (function (){

      var btn = $(this).attr('id');

      if (!$(this).hasClass('disabled')) {
        if (checkAgeLimit(".tab-pane.active","data-start", false)) {
          if (checkForRegEnd(btn)) {
              saveMember (true);
            }
          }
        } else {
          showError("Необходимо заполнить все обязательные поля, выделенные розовым фоном!", true);
        }
    });

    $("#events-list").change(function(){
        var eventId = $(this).val(), eventIdCurrent = $('.tab-pane.active').attr('id').replace(/^eventTab-/,'');

        if(eventId !== eventIdCurrent){
            $('.tab-pane.active').removeClass('active');
            $('.tab-pane#eventTab-'+eventId).addClass('active');
        }

        setCookieNew("eventChoose", eventId);

        checkStopEventRegistration(eventId);

        if ($("#eventTab-"+eventId+" table").text ().indexOf("Загрузка")!=-1){
            loadDashboard (eventId);
        }
        else{
            handleControlButtons();
        }
        setAdminRole();
    });

function checkStopEventRegistration(eventId){
    $.post('/ajax/event.php?check_stop_reg', {eventId: eventId})
    .done(function(data){
        var text = '';
        if (parseInt(data.res.participants_count) > 0){
            if(data.res.close_registration === '1' || parseInt(data.res.count_members) >= parseInt(data.res.participants_count)){
                text = "<span class='label label-important registration-closed'><a style='color:white;' data-toggle='modal'>Регистрация закрыта</a></span>";
            }
            else{
                text = "<span class='label label-info' id='labelExtraInfo' data-max-participants='"+ (parseInt(data.res.participants_count))+"'><span style='color:white; cursor: pointer' data-toggle='modal' >Осталось мест — "+ (parseInt(data.res.participants_count) - parseInt(data.res.count_members))+"</span></span>";
            }
        } else if (parseInt(data.res.participants_count) === 0) {
          if(data.res.close_registration === '1'){
              text = "<span class='label label-important registration-closed'><a style='color:white;' data-toggle='modal' >Регистрация закрыта</a></span>";
          }
        }
        $(".close-event-registration").html(text);
        $('#labelExtraInfo').click(function () {
          var a = $(this).attr('data-max-participants');
          var b = 'Максимальное количество участников для этого мероприятия — ' + a + ' чел.';
          $('#modalHintWindow').modal('show');
          $('#modalHintWindow .modal-body').text(b);
        })
    });
}

    $("#btnDoSaveBulk").click (function (){
        if (!$(this).hasClass('disabled')){
            var eventId = $("#events-list").val(), ids = [];
            $("div.tab-pane.active tr[class|='regmem'] input[type='checkbox']:checked").parents ("tr").each (function(){
                ids.push ($(this).attr ('class').replace(/^regmem-/,''));
            });

            var request = getRequestFromFilters(setFiltersForRequest(eventId));
            var needTransportTemp = $(".tab-pane.active").attr("data-transport") == "1" ? "0" : "";
            console.log($(".beTransport").val());
            $.post("/ajax/set.php?members="+ids.join(',')+"&event="+eventId+request,
            {
                arr_date: parseDate ($(".beArrDate").val()),
                arr_time: parseTime ($(".beArrTime").val()),
                dep_date: parseDate ($(".beDepDate").val()),
                dep_time: parseTime ($(".beDepTime").val()),
                accom: $(".beAccom").val() == "_none_" ? "" : $(".beAccom").val(),
                transport: $(".beTransport").val() == "_none_" || $(".beTransport").val() == null ? needTransportTemp : $(".beTransport").val(),
                status: $(".beStatus").val() == "_none_" ? "" : $(" .beStatus").val(),
                service: $(".beService").val() == "_none_" ? "" : $(" .beService").val(),
                coord: $(".beCoord").val(),
                mate: $(".beMate").val() == "_none_" ? "" : $(" .beMate").val(),
                parking: "0",
            }).done (function(data) {
                refreshEventMembers (eventId, data.members, data.localities);
                $('#modalBulkEditor').modal('hide');
            });
        }
    });

    $("#btnDoAddMembers").click (function (){
        var eventId = $("#events-list").val();

        var members = [];
        $(".member-row > td > input[type='checkbox']:checked").each (function () {
            members.push ($(this).parent().parent().attr ('id').replace(/^mr-/,''));
        });

        var request = getRequestFromFilters(setFiltersForRequest(eventId));

        if (members.length>0) {
            $.getJSON('/ajax/set.php?event='+eventId+request, { reg_new_members: members.join(',') })
            .done (function(data) {
                refreshEventMembers (eventId, data.members, data.localities);
                $('#modalAddMembers').modal('hide');
            });
        }
    });

    $("#btnDoCreateMember").click (function (){
        var eventId = $("#events-list").val();
        $.getJSON('/ajax/get.php', { eventId: eventId })
        .done (function(data) {
            fillEditMember ('', data.info, data.localities);
            $('#modalAddMembers').modal('hide');
            $('#modalEditMember').attr('data-member_id', '');
            $(".emLocality").val("_none_").change();
            var el = $('#modalEditMember').modal('show');
            //el.find('.edit-member-form').empty();
            $(document).width() >980 ? el.find('.tablets-visible').empty() : el.find('.desctop-visible').empty();
            $('.emName').addClass('create-member');
        });
    });

    function checkForRegEnd (action){
        var regend = $(".tab-pane.active").data ("regend");
        if (regend) {
            regend = new Date (regend);
            if (isValidDate(regend)) {
                regend.setHours(23);
                regend.setMinutes(59);
                regend.setSeconds(59);
                if (regend < new Date ()) {
                    window.RegEndedAction = action;
                    $("#regendDate").text (formatDate(regend));
                    $('#modalRegEnded').modal('show');
                    return false;
                }
            }
        }
        return true;
    }

    $(".event-add-member").click (function (){

        if($('.registration-closed').children().length > 0){
            var access = $(this).parents('.tab-pane.active').attr('data-access');

            if(access === '1'){
                $("#modalHandleCloseEvent").modal('show');
            }
            else{
                showModalHintWindow("<strong>Онлайн-регистрация на это мероприятие закрыта.<br>По всем вопросам <a href='' data-toggle='modal' data-target='#modalEventSendMsg'>обращайтесь к команде регистрации.</a> </strong>");
            }
        }
        else if (checkForRegEnd ('event-add-member')) $('#modalAddMembers').modal('show');
    });

    $("#btnRegEndedAction").click (function (){
        switch (window.RegEndedAction) {
            case 'event-add-member':
                $('#modalAddMembers').modal('show');
                break;
            case 'chk-register':
                chkRegister ();
                break;
            case 'btnDoRegisterMember':
                saveMember (true);
                break;
        }
    });

    function chkRegister(){

        var eventId = $("#events-list").val(), ids = [];

        if($(document).width() > 980) {
            $("div.tab-pane.active tr[class|='regmem'] input[type='checkbox']:checked").parent("td").siblings("td[class*='mname']").each(function () {
                ids.push($(this).parents("tr").attr('class').replace(/^regmem-/, ''));
            });
        }
        else {
            $("div.tab-pane.active tr[class|='regmem'] input[type='checkbox']:checked").parents("tr[class|='regmem']").find(".mname").each(function () {

                ids.push($(this).parents("tr[class|='regmem']").attr('class').replace(/^regmem-/, ''));
            });
        }

        var request = getRequestFromFilters(setFiltersForRequest(eventId));

        $.getJSON('/ajax/set.php?event='+eventId+request, {register_members: ids.join(',') })
        .done (function(data) {
            refreshEventMembers (eventId, data.members, data.localities);
            if (data.invalid && data.invalid.length>0)
                alert("Следующие участники не были зарегистрированы:\n\n"+data.invalid.toString().replace(/,/g,'\n')+"\n\nПроверьте правильность заполнения всех полей и возрастные ограничения мероприятия!", false);
        });
    }

    $(".chk-register").click (function (){
        if ($(this).hasClass('disabled')) return;

        if (checkForRegEnd ('chk-register'))
            chkRegister ();
    });

    function handleBulkModal(that){
        if ($(that).hasClass('disabled')) return;
        $("#bulkEditorEventTitle").text($('#events-list option:selected').text());

        var arr_date=null, arr_time=null, dep_date=null, dep_time=null, accom=null, trans=null,
            service_key =null, coord = null, mate = null, memberId = null,
            attended = null, place = null, aidneed=null, aidpaid=null, prepaid=null, paid = null, currency = null, status_key = null;

        $("div.tab-pane.active tr[class|='regmem'] input[type='checkbox']:checked").parents ("tr").each (function(){
            if (arr_date===null) arr_date=$(this).find(".arrival").data('date');
            else if (arr_date!=$(this).find(".arrival").data('date')) arr_date="";

            if (arr_time===null) arr_time=$(this).find(".arrival").data('time');
            else if (arr_time!=$(this).find(".arrival").data('time')) arr_time="";

            if (dep_date===null) dep_date=$(this).find(".departure").data('date');
            else if (dep_date!=$(this).find(".departure").data('date')) dep_date="";

            if (dep_time===null) dep_time=$(this).find(".departure").data('time');
            else if (dep_time!=$(this).find(".departure").data('time')) dep_time="";

            if (accom===null) accom=$(this).data('accom');
            else if (accom!=$(this).data('accom')) accom="_none_";

            if (trans===null) trans=$(this).data('transport');
            else if (trans!=$(this).data('transport')) trans="_none_";

            if (status_key===null) status_key=$(this).attr('data-status');
            else if (status_key!=$(this).data('data-status')) status_key="_none_";

            if (service_key===null) service_key=$(this).attr('data-service');
            else if (service_key !== $(this).attr('data-service')) service_key="_none_";

            if (coord===null) coord=$(this).attr('data-coord');
            else if (coord !== $(this).attr('data-coord')) coord= "0";

            if (mate===null) mate=$(this).data('mate');
            else if (mate!=$(this).data('mate')) mate="_none_";

            if (mate===null) memberId=$(this).attr ('class').replace(/^regmem-/,'');
            else if (memberId!=$(this).attr ('class').replace(/^regmem-/,'')) memberId="_none_";

            if (attended===null) attended=$(this).attr('data-attended');
            else if (attended !== $(this).attr('data-attended')) attended= "_none_";

            if (place===null) place=$(this).attr('data-place');
            else if (place !== $(this).attr('data-place')) place= "";

            if (prepaid===null) prepaid=$(this).attr('data-prepaid');
            else if (prepaid !== $(this).attr('data-prepaid')) prepaid= "";

            if (aidpaid===null) aidpaid=parseInt($(this).attr('data-aid_paid'));
            else if (aidpaid !== $(this).attr('data-aid_paid')) aidpaid= "";

            if (aidneed===null) parseInt(aidneed=$(this).attr('data-aid_amount'));
            else if (aidneed !== $(this).attr('data-aid_amount')) aidneed= "";

            if (currency===null) currency=$(this).attr('data-currency');
            else if (currency !== $(this).attr('data-currency')) currency= "";

            if (paid===null) paid=$(this).attr('data-paid');
            else if (paid !== $(this).attr('data-paid')) paid= "";
        });

        if ($("div.tab-pane.active").data ("transport")=='1') {
            var needFlight = $("div.tab-pane.active").data ("need_flight")=='1';
            var needTp = $("div.tab-pane.active").data ("need_tp")=='1';

            $(".transportText").text( needFlight ? "Поездка" : "Транспорт");
            $(".transportHint").attr("title", needFlight ? "Групповая поездка до или после мероприятия" : "Для проезда от места проживания к залу собраний");
            $(".beGrpTransport, .beLblTransport").css('display', 'block');
        }
        else {
            $(".beGrpTransport, .beLblTransport").css('display', 'none');
        }

        $(".beArrDate").val(formatDate(arr_date)).keyup();
        $(".beArrTime").val(formatTime(arr_time));
        $(".beDepDate").val(formatDate(dep_date)).keyup();
        $(".beDepTime").val(formatTime(dep_time));
        $(".beAccom").val(accom).change();
        $(".beTransport").val(trans).change();
        $(".beService").val(service_key);
        $(".beCoord").val(coord);
        $(".beStatus").val(status_key);

        if ($(".beMate").length){
            var emMateHtml = "<option value='_none_' selected>&nbsp;</option>";

// ????
            $(".tab-pane.active " + (document.documentElement.clientWidth < 751 ? '.show-phone' : '.desctopVisible' ) + " table.reg-list tr[class|='regmem']").each (function (){
                var id = $(this).attr ('class').replace(/^regmem-/,'');
                if (id.length>2 && id.substr (0,2)!="99" && id!=memberId){
                    var name = $(this).find('.mname .mname1').text() + ', ' + $(this).find('.mname .mnameCategory').text();
                    emMateHtml+="<option value='"+he(id)+"'>"+he(name)+"</option>";
                }
            });

            $(".beMate").html (emMateHtml).val (mate);
        }

        $(".emPlace").val(place);
        $(".emPaid").val(paid>0 ? paid: '');
        $(".emPrepaid").val(prepaid > 0 ? prepaid + (currency ?' ('+currency+')' : '' ) : '');
        $(".emAidpaid").val(parseInt(aidneed) > 0 ? ( aidpaid >= aidneed ? aidneed : '' ) : '');
        $(".emAttended").val(attended);

        var el = $(".tab-pane.active");
        $(".beTooltipArrDate").attr('title', "Начало "+formatDDMM (el.data ("start"))).tooltip('fixTitle');
        $(".beTooltipDepDate").attr('title', "Конец "+formatDDMM (el.data ("end"))).tooltip('fixTitle');
        var eventId = $("#events-list").val();

        $.post('/ajax/get.php?check_event', {event: eventId})
        .done(function(data){
            data.service ? $('#modalBulkEditor .service-admin-fields').css('display','block') : $('#modalBulkEditor .service-admin-fields').css('display','none');
            data.result ? $('#modalBulkEditor .show-admin-fields').css('display','block') : $('#modalBulkEditor .show-admin-fields').css('display','none');
            handleBulkModalFormFields(data.event);
            $('#modalBulkEditor').modal('show');
        });
    }

    function handleBulkModalFormFields(event){
        if (event["need_accom"]>0) {
            $('.accom-block').show();
        }
        else {
            $('.accom-block').hide();
        }

        if (event["need_parking"]>0) {
            $('.parking').show();
        }
        else {
            $('.parking').hide();
        }

        if (event["need_transport"] > 0){
            $(".block-transport").show();
        }
        else{
            $(".block-transport").hide();
        }

        if (event["need_service"] > 0){
            $(".service-block").show();
        }
        else{
            $(".service-block").hide();
        }

    }

    function handleCheckedMembers(){
         var alreadyArrivedNames = [], alreadyArrivedIds = [], notArrivedIds = [];

         if($(document).width() > 980) {
             $("div.tab-pane.active tr[class|='regmem'] input[type='checkbox']:checked").parent("td").siblings("td[class*='mname']").each(function () {
                 if($(this).parents("tr").attr('data-attended') === '1'){
                     alreadyArrivedNames.push($(this).parents("tr").find('.mname').text());
                     alreadyArrivedIds.push($(this).parents("tr").attr('class').replace(/^regmem-/, ''));
                 }
                 else{
                     notArrivedIds.push($(this).parents("tr").attr('class').replace(/^regmem-/, ''));
                 }
             });
         }
         else {
             $("div.tab-pane.active tr[class|='regmem'] input[type='checkbox']:checked").parents("tr[class|='regmem']").find(".mname").each(function () {
                 if($(this).parents("tr[class|='regmem']").attr('data-attended') === '1'){
                     alreadyArrivedNames.push($(this).parents("tr[class|='regmem']").find('.mname').text());
                     alreadyArrivedIds.push($(this).parents("tr[class|='regmem']").attr('class').replace(/^regmem-/, ''));
                 }
                 else{
                     notArrivedIds.push($(this).parents("tr[class|='regmem']").attr('class').replace(/^regmem-/, ''));
                 }
             });
         }

         return {alreadyArrivedNames : alreadyArrivedNames, alreadyArrivedIds : alreadyArrivedIds, notArrivedIds : notArrivedIds};
     }

     $(".bulkedit-arrived").click(function(){
         var eventId = $("#events-list").val();

         var res = handleCheckedMembers();
         if($(this).hasClass('disabled') || (res.alreadyArrivedIds.length === 0 && res.notArrivedIds.length === 0)){
             return;
         }

         if(res.alreadyArrivedNames.length > 0){
             $("#modalHandleMemberAttended .modal-body").html(res.alreadyArrivedNames.join(', '));
             $("#modalHandleMemberAttended").modal('show');
             return;
         }

         setAttendedToMembers(eventId, res.notArrivedIds);
     });

     $('.reject-attended').click(function(){
         var res = handleCheckedMembers(), eventId = $("#events-list").val();
         setAttendedToMembers(eventId, res.notArrivedIds, res.alreadyArrivedIds);
     });

     function setAttendedToMembers(eventId, notArrivedIds, alreadyArrivedIds=false){
         var request = getRequestFromFilters(setFiltersForRequest(eventId));

         $.getJSON('/ajax/set.php?set_attended_members'+request, {
             event: eventId,
             set_attended_members: notArrivedIds.length > 0 ? notArrivedIds.join(',') : null,
             dismiss_attended_members: alreadyArrivedIds && alreadyArrivedIds.length > 0 ? alreadyArrivedIds.join(',') : null
         })
         .done (function(data) {
             refreshEventMembers (eventId, data.members, data.localities);
             if (data.invalid && data.invalid.length>0)
                alert("Следующие участники не были зарегистрированы:\n\n"+data.invalid.toString().replace(/,/g,'\n')+"\n\nПроверьте правильность заполнения всех полей!", false);
         });
     }

    function handleBulkModalFull(that){
        if ($(that).hasClass('disabled')) return;

        var ids = handleCheckedMembers().checked,
            event = $("#events-list").val(),
            request = getRequestFromFilters(setFiltersForRequest(event));

        $.getJSON("/ajax/set.php?confirm_registration"+request,{
            event: event,
            membersIds : ids.join(',')
        })
        .done (function(data) {
            refreshEventMembers (event, data.members, data.localities);
        });
    }

    $(".chk-bulkedit").click (function (){
        if ($(this).hasClass('disabled')) return;

        handleBulkModal(this);
    });

    $(".bulkedit-prove").click (function (){
        if ($(this).hasClass('disabled')) return;

        handleBulkModalFull(this);
    });

    $("#btnDoSaveFull").click (function (){
        if (!$(this).hasClass('disabled')){
            var eventId = $("#events-list").val();
            var ids = [];

            $("div.tab-pane.active tr[class|='regmem'] input[type='checkbox']:checked").parents ("tr").each (function(){
                ids.push ($(this).attr ('class').replace(/^regmem-/,''));
            });

            var modal = $('#modalFullBulkEditor');
            var paid = modal.find(".emPaid").val();
            var request = getRequestFromFilters(setFiltersForRequest(eventId));

            $.post("/ajax/set.php?members="+ids.join(',')+"&event="+eventId+"&checkServ=true"+request,{
                paid : paid === '' ? 0 : paid,
                place : modal.find(".emPlace").val(),
                attended : modal.find(".emAttended").val() === '1' ? 1 : null
            })
            .done (function(data) {
                refreshEventMembers (eventId, data.members, data.localities);
                modal.modal('hide');
            });
        }
    });

    $(".chk-remove").click (function (){
        if ($(this).hasClass('disabled')) return;
        var msg = "Вы действительно хотите отменить регистрацию следующих участников?\n\n";
        var ids = [];
        var w = $(document).width();
        if(w > 980) {
            $("div.tab-pane.active tr[class|='regmem'] input[type='checkbox']:checked").parent("td").siblings("td[class*='mname']").each(function (index, el) {
                ids.push($(this).parents("tr").attr('class').replace(/^regmem-/, ''));
                msg += el.textContent + ", ";
            });
        }
        else {
            $("div.tab-pane.active tr[class|='regmem'] input[type='checkbox']:checked").parents("tr[class|='regmem']").find(".mname").each(function (index, el) {
                ids.push($(this).parents("tr[class|='regmem']").attr('class').replace(/^regmem-/, ''));
                msg += el.textContent + ", ";
            });
        }

        msg=msg.replace(/,\s+$/gm, '');

        if (confirm (msg)) {
            var eventId = $("#events-list").val();
            $.getJSON('/ajax/set.php', { event: eventId, remove_members: ids.join(',') })
            .done (function(data) {
                refreshEventMembers (eventId, data.members, data.localities);
            });
        }
    });

/*
    $('.confirmRegReject').click(function(){
        var eventId = $("#events-list").val();
        //var eventId = $("ul.dropdown-menu li.active > a").attr ("href").replace(/^#eventTab-/,'');
        var ids = $(this).attr('data-ids'), isReadyToRemove = $(this).attr('data-need_reason') === "yes";
        var reason = $('#modalRejectRegConfirm .reason-reject').val().trim();

        if(reason === '' && !isReadyToRemove){
            showError('Необходимо указать причину отмены регистрации');
            return;
        }

        var text = $('.search-text').val().trim();
        var request = getRequestFromFilters(setFiltersForRequest(eventId));

        $.getJSON('/ajax/set.php?event='+eventId+request, {searchText : text, remove_members: ids, reason: reason })
        .done (function(data) {
            $('#modalRejectRegConfirm').modal('hide');
            refreshEventMembers (eventId, data.members, data.localities);
        });
    });
    */
    $("#lnkPermalink").click (function (){
        var editor = $("#txtPermalink");
        if (editor.css('display')!='none') {
            editor.hide ();
            $(this).text ("Показать ссылку");
        }
        else {
            editor.show ();
            var locHost = location.host, host;
            host = '<?php echo $appRootPath; ?>';
            editor.val (host+'invites?link='+window.permalink);
            editor.select();
            $(this).text ("Скрыть ссылку");
        }
    });

    $(".event-info, #lnkEventInfo").click (function (){
        var eventId = $("#events-list").val();
        $.getJSON('/ajax/get.php', { event_info: eventId })
            .done (function(data) {
            $("#eventInfoTitle").text ($('#events-list option:selected').text());
            $("#eventInfoText").html(data.event_info);
            $("#sendMsgText").val("");
            $('#modalEventInfo').modal('show');
        });
    });

    $(".send-message").click (function (){
        $("#sendMsgEventName").text ($('#events-list option:selected').text());
        $("#modalEventSendMsg").modal('show');
    });

    $("#emShowSharedComment").click(function (){
        $('#modalSharedComment').modal('show');
    });

    $(".chk-send-invitation").click (function (){
        if ($(this).hasClass('disabled')) return;

        var el = $("#sendLetterText");
        if (el.val().trim()!=='')
            showLetterDialog(el.val(), true);
        else
        {
            var eventId = $("#events-list").val();
            $.getJSON('/ajax/get.php', { event_invitation: eventId })
                .done (function(data) {
                    showLetterDialog(data.event_invitation, true);
            });
        }
    });

    $(".chk-send-letter").click (function (){
        if ($(this).hasClass('disabled')) return;
        showLetterDialog('', false);
    });

    function showLetterDialog (text, isInvitation){
        window.letterIsInvitation = isInvitation;
        $("#sendLetterTitle").text (isInvitation ? "Приглашение выбранным участникам" : "Сообщение выбранным участникам");
        $("#sendLetterName").text ($('#events-list option:selected').text());
        $("#sendLetterText").val(text);
        $("#sendLetterTopic").val('Сообщение с сайта reg-page.ru');

        var eventId = $("#events-list").val();

        $.post('/ajax/get.php?get_team_email', {eventId : eventId})
        .done(function(data){
            if(data.email){
                $("#sendLetterEmail").val(data.email);
            }
            $("#modalSendLetter").modal('show');
        });
    }

    $("#btnDoSendLetter").click (function (){
        if ($(this).hasClass('disabled')) return;
        var eventId = $("#events-list").val(), ids = [], el = $("#modalEditMember").data('modal');
        if (el && el.isShown)
            ids.push (window.currentEditMemberId);
        else
            $("div.tab-pane.active tr[class|='regmem'] input[type='checkbox']:checked").parent ("td").siblings ("td[class*='mname']").each (function(){
                ids.push ($(this).parents("tr").attr ('class').replace(/^regmem-/,''));
            });
        var request = getRequestFromFilters(setFiltersForRequest(eventId));

        $.post('/ajax/set.php?event='+eventId+request,{
            send_to_members: ids.join(','),
            //subject: window.letterIsInvitation ? "Приглашение с сайта reg-page.ru" : "Сообщение с сайта reg-page.ru",
            text: $("#sendLetterText").val(),
            from_name: $("#sendLetterName").val(),
            from_email: $("#sendLetterEmail").val(),
            type: window.letterIsInvitation ? "invitation" : "letter",
            subject : $("#sendLetterTopic").val()
        },function(data){
    /*
            messageBox ((window.letterIsInvitation ?
                (ids.length>1 ? 'Приглашения поставлены' : 'Приглашение поставлено') :
                (ids.length>1 ? 'Сообщения поставлены' : 'Сообщение поставлено')) + ' в очередь на отправку <br/>и будет отправлено в течение нескольких минут', $('#modalSendLetter')
            );
    */

            showHint((window.letterIsInvitation ?
                (ids.length>1 ? 'Приглашения поставлены' : 'Приглашение поставлено') :
                (ids.length>1 ? 'Сообщения поставлены' : 'Сообщение поставлено')) + ' в очередь на отправку и будет отправлено в течение нескольких минут');
            $("#modalSendLetter").modal('hide');

            refreshEventMembers (eventId, data.members, data.localities);
        });
    });

    $("#btnDoSendEventMsg").click (function (){
        if ($(this).hasClass('disabled')) return;
        var eventId = $("#events-list").val();
        $.ajax({type: "POST", url: "/ajax/set.php", data: {event: eventId, message: $("#sendMsgText").val(), name:$("#sendMsgName").val(), email:$("#sendMsgEmail").val()}})
         .done (function(data) {
            if (data.result){
                messageBox ('Ваше сообщение отправлено команде регистрации', $('#modalEventSendMsg'));
                closeMessageBox();
                setTimeout(closePopup, 3000);
            }
         });
    });

/*
    $("#btnDoSendEventMsgAdmins").click (function (){
        if ($(this).hasClass('disabled')) return;
        var eventId = $("#events-list").val();
        $.ajax({type: "POST", url: "/ajax/set.php", data: {event:eventId,  message: $("#sendMsgTextAdmin").val(), name:$("#sendMsgNameAdmin").val(), email:$("#sendMsgEmailAdmin").val(), admins:"Регистрация"}})
         .done (function() {
             messageBox ('Ваше сообщение отправлено службе поддержки', $('#messageAdmins'));
             closeMessageBox();
             setTimeout(closePopup, 3000);
         });
    });
    */

    $(".send-messageAdmins").click(function(){
        $("#sendMsgEventNameAdmins").text ($('#events-list option:selected').text());
    });

    $("a[id|='sort']").click (function (){
        var id = $(this).attr("id"), icon = $(this).siblings("i");
        $("div.tab-pane.active a[id|='sort'][id!='"+id+"'] ~ i").attr("class","icon-none");
        icon.attr ("class", icon.hasClass("icon-chevron-down") ? "icon-chevron-up" : "icon-chevron-down");
        loadDashboard ();
    });

    $(document).keyup(function(e) {
        if (e.keyCode === 27) {
            var modal = $("#modalEditMember");
            if (modal.data('modal') && modal.data('modal').isShown && !$("#confirmToSaveChangesModal").is(':visible')){
                //var memberEventData = parseEventMemberDataToCheckChanges(getValuesRegformFields(form));
                var memberId = $("#modalEditMember").attr('data-member_id'),
                    eventId = $("#events-list").val(),
                    dataFields = getValuesRegformFields(modal);

                $.getJSON('/ajax/get.php?check_changes', {dataFields : dataFields, member: memberId, event: eventId})
                .done (function(data){
                    if(data.result){
                        $("#confirmToSaveChangesModal").modal('show');
                        return;
                    }
                    $('#modalEditMember').modal('hide');
                });
            }
        }
   });

    $(".confirm-save-changes").click(function(){
        $('#modalEditMember').modal('hide');
    });
    // Romans Code 5.0.4
    function arriveDepart(tagAttr, ClassDates) {
// Это мой код, получаем данные и сбрасываем настройки виджета календаря для заданного заданого поля
      var getDateArriveOrDepart = $('.tab-pane.active').attr(tagAttr);
      getDateArriveOrDepart = getDateArriveOrDepart.split('-');
      getDateArriveOrDepart[1]--;
      $(ClassDates).datepicker('destroy');
// Этот копия кода из script.js
      $(ClassDates).datepicker({
          language: 'ru',
          autoclose : true,
          defaultViewDate: {
              month: getDateArriveOrDepart[1],
              year: getDateArriveOrDepart[0]
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
  arriveDepart('data-start','.emArrDate');
  arriveDepart('data-end','.emDepDate');

  $("#events-list").change(function () {
    arriveDepart('data-start','.emArrDate');
    arriveDepart('data-end','.emDepDate');
  });
    // END Romans Code
</script>
<script src="/js/reg.js?v65"></script>
<script src="/js/regupload.js?v5"></script>
<?php
    include_once "footer.php";
?>
