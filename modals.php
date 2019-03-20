<!-- Event Info Modal -->
<div id="modalEventInfo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="eventInfoTitle" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    <h4 id="eventInfoTitle"></h4>
  </div>
  <div class="modal-body"><div id="eventInfoText"></div></div>
  <div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">ОK</button></div>
</div>

<!-- Event Send Message Modal -->
<div id="modalEventSendMsg" class="modal hide fade modal-send-message in" tabindex="-1" role="dialog" aria-labelledby="eventSendMsgTitle" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="eventSendMsgTitle">Сообщение команде регистрации</h3>
        <p id="sendMsgEventName"></p>
    </div>
    <div class="modal-body">
        <form class="form-horizontal">
            <div class="control-group">
                <label class="control-label" for="sendMsgName">От<sup>*</sup></label>
                <div class="controls">
                    <input type="text" id="sendMsgName" class="span4 name-field" placeholder="Имя" valid="required">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="sendMsgEmail">Email<sup>*</sup></label>
                <div class="controls">
                    <input type="text" id="sendMsgEmail" class="span4 email-field" placeholder="Email" valid="required, email">
                </div>
            </div>
            <textarea class="span5 text-field" rows="10" id="sendMsgText"></textarea>
        </form>
  </div>
  <div class="modal-footer">
    <button class="btn btn-success disable-on-invalid" id="btnDoSendEventMsg">Отправить</button>
    <button class="btn close-message-box" data-dismiss="modal" aria-hidden="true">Отменить</button>
  </div>
</div>

<!-- Message Sent Confirmation Modal -->
<div id="modalMessageBox" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-body">
        <button type="button" class="close close-message-box" data-dismiss="modal" aria-hidden="true">x</button>
        <h4></h4>
    </div>
</div>

<!-- Shared Comment Modal -->
<div id="modalSharedComment" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="eventInfoTitle" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Комментарий участника</h3>
    </div>
    <div class="modal-body"><div id="sharedCommentText"></div></div>
    <div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">ОK</button></div>
</div>

<!-- Admins Send Message Modal -->
<div id="messageAdmins" class="modal hide fade modal-send-message" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="eventSendMsgTitle">Сообщение службе поддержки</h3>
        <p id="sendMsgEventNameAdmins"></p>
    </div>
    <div class="modal-body">
        <form class="form-horizontal">
            <div class="control-group">
                <label class="control-label" for="sendMsgNameAdmin">От<sup>*</sup></label>
                <div class="controls">
                    <input type="text" id="sendMsgNameAdmin" class="span4 name-field" placeholder="Имя" valid="required">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="sendMsgEmailAdmin">Email<sup>*</sup></label>
                <div class="controls">
                    <input type="text" id="sendMsgEmailAdmin" class="span4 email-field" placeholder="Email" valid="required, email">
                </div>
            </div>
            <textarea class="span5 text-field" rows="10" id="sendMsgTextAdmin"></textarea>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn btn-success disable-on-invalid" id="btnDoSendEventMsgAdmins">Отправить</button>
        <button class="btn close-message-box" data-dismiss="modal" aria-hidden="true">Отменить</button>
    </div>
</div>

<!-- Registration state statistics -->

<div id="modalStatistic" class="modal hide fade" data-width="500" tabindex="-1" role="dialog" aria-labelledby="eventInfoTitle" aria-hidden="true">
    <div id="closeModal">
        <div class="modal-header">
            <button type="button" class="close statCancel" data-dismiss="modal" aria-hidden="true">x</button>
            <h3>Статистика</h3>
            <h5></h5>
        </div>
        <div class="modal-body"><div id="showStatistic"></div></div>
    </div>
</div>

<!-- Aid modal statistics -->
<div id="modalAidStatistic" data-width="960" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close close-form" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 class="" style="text-align: center"></h4>
    </div>
    <div class="modal-body">
        <div class="btn-group">
            <a class="btn dropdown-toggle downloadAidList" title="Скачать" data-toggle="dropdown" href="#">
                <i class="fa fa-download fa-lg" aria-hidden="true"></i>
            </a>
        </div>
        <div class="btn-group">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-filter fa-lg" aria-hidden="true"></i> Выбрать
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a data-scope="all" class="filter-aid" tabindex="-1" href="#">Все участники</a></li>
                <li><a data-scope="handled" class="filter-aid" tabindex="-1" href="#">Обработанные</a></li>
                <li><a data-scope="unhandled" class="filter-aid" tabindex="-1" href="#">Необработанные</a></li>
                <li><a data-scope="rejected" class="filter-aid" tabindex="-1" href="#">Отказано</a></li>
            </ul>
        </div>
        <div class="desctop-visible">
            <div id="aidStatistic">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Участник</th>
                        <th>Город</th>
                        <th class="">На мероприятие</th>
                        <th class="">На транспорт</th>
                        <!-- <th class="">Общались</th> -->
                        <th class="">Оказано помощи</th>
                        <th class=""> </th>
                    </tr>
                    </thead>
                    <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
                </table>
            </div>
            <hr />
            <div id="aidGeneralStatistic">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Недостающая сумма</th>
                    <th>Оказано помощи</th>
                    <th>Мероприятие</th>
                    <th>Транспорт</th>
                    <th>Необработанно</th>
                </tr>
                </thead>
                <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
            </table>
        </div>
        </div>
        <div class="tablets-visible">
            <div id="aidStatistic">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th><i title="Участник" class="fa fa-user fa-lg" aria-hidden="true"></i></th>
                        <th><i title="Город" class="fa fa-university fa-lg" aria-hidden="true"></i></th>
                        <th ><i title="На мероприятие" class="fa fa-users fa-lg" aria-hidden="true"></i></th>
                        <th><i title="На транспорт" class="fa fa-train fa-lg" aria-hidden="true"></i></th>
                        <!-- <th class="">Общались</th> -->
                        <th><i title="Оказано помощи" class="fa fa-money fa-lg" aria-hidden="true"></i></th>
                        <th> </th>
                    </tr>
                    </thead>
                    <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
                </table>
            </div>
            <hr />
            <div id="aidGeneralStatistic">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th><i title="Недостающая сумма" class="fa fa-dollar fa-lg"></i></th>
                    <th><i title="Оказано помощи" class="fa fa-money fa-lg"></i></th>
                    <th><i title="Мероприятие" class="fa fa-users fa-lg" aria-hidden="true"></i></th>
                    <th><i title="Транспорт" class="fa fa-train fa-lg" aria-hidden="true"></i></th>
                    <th><i title="Необработанные" class="fa fa-user-times fa-lg" aria-hidden="true"></i></th>
                </tr>
                </thead>
                <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
            </table>
        </div>
        </div>

        <div class="noAidNeed"></div>
    </div>
    <div class="modal-footer">
        <button class="btn close-form" data-dismiss="modal" aria-hidden="true">Ok</button>
    </div>
</div>

<!-- Aid modal statistics -->
<div id="modalUserAidInfo" data-width="960" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close close-form" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 style="text-align: center"></h4>
    </div>
    <div class="modal-body">
        <div id="aidInfo">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>На мероприятие</th>
                    <th>На транспорт</th>
                    <!-- <th class="">Общались</th> -->
                    <th>Оказано помощи</th>
                </tr>
                </thead>
                <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
            </table>
            <div class="control-group row-fluid">
                <input class="span12 aid-amount" type="text" placeholder="Введите сумму помощи">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary paidAid" aria-hidden="true">Помочь</button>
        <button class="btn btn-danger dismissAid" aria-hidden="true">Отказать</button>
        <button class="btn close-form" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>

<!-- Registration Ended Message Modal -->
<div id="modalRejectRegConfirm" class="modal hide fade" tabindex="-1" role="dialog"  aria-labelledby="regEndedTitle" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 id="regEndedTitle">Подтверждение отмены регистрации</h4>
    </div>
    <div class="modal-body" style="min-height: 80px;">
        <p></p>
        <div class="control-group row-fluid">
            <input class="span12 reason-reject" type="text" placeholder="Причина отмены регистрации">
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary confirmRegReject">Подтвердить</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>

<!-- LIST Colleges Modal -->
<div id="modalCollegesList" data-width="960" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="addCollegeTitle" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="addCollegeTitle">Список учебных заведений</h3>
    </div>
    <div class="modal-body">
        <form class="form-inline">
            <button class="btn btn-info" id="btnDoAddCollege">
                <i class="fa fa-plus-circle" aria-hidden="true"></i> Добавить
            </button>
            <select style="margin-left: 10px;" class="controls colleges-city-list">

            </select>
            <input type="text" style="padding-right: 20px;" class="span4 controls search-colleges" placeholder="Введите текст для поиска">
            <i class="fa fa-remove clear-search-colleges"></i>
        </form>
        <hr/>
        <div id="membersTable">
            <table class="table table-hover table-condensed">
                <thead><tr><th style="width: 60%">Учебное заведение</th><th class="sort_college_locality" title="Сортировать">Местность <span class="sort-direction fa fa-chevron-down"></span></th><th> </th></tr></thead>
                <tbody><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer"></div>
</div>

<!-- Change College Modal -->
<div id="modalEditCollege" class="modal hide fade" tabindex="-1" aria-labelledby="addCollegeTitle" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close cancelEditCollege" data-dismiss="modal" aria-hidden="true">x</button>
        <h3></h3>
    </div>
    <div class="modal-body">
        <div style="display: inline-block;">
            <div class="control-group row-fluid">
                <label class="span12">Полное название<sup>*</sup></label>
                <div class="control-group row-fluid">
                    <input class="span12 edit-college-name" valid="required" type="text" maxlength="100" autocomplete="off">
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12">Сокращенное название<sup>*</sup></label>
                <div class="control-group row-fluid">
                    <input class="span12 edit-college-short-name" valid="required" type="text" maxlength="100" autocomplete="off">
                </div>
            </div>
            <div class="control-group row-fluid">
                <label class="span12">Город<sup>*</sup></label>
                <div class="selected-locality"></div>
                <input type="text" placeholder="Введите название местности" valid="required" class="controls span12 search-locality-to-add-college">
                <!-- <i class="icon-remove"></i> -->
                <ul class="locality-list span12">
                    <?php foreach ($allLocalities as $id => $name) echo "<li data-value='$id' title='Выбрать ".htmlspecialchars ($name)."'>".htmlspecialchars ($name)."</li>"; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-success btnDoSaveCollege">Сохранить</button>
        <button class="btn btn-default cancelEditCollege" data-dismiss="modal" aria-hidden="true">Отменть</button>
    </div>
</div>

<!-- Delete College Modal -->
<div id="modalDeleteCollege" class="modal hide fade" tabindex="-1" aria-labelledby="addCollegeTitle" role="dialog" aria-hidden="true">
    <div class="modal-body">
        <h4 style="text-align: center;">Вы действительно хотите удалить учебное заведение?</h4>
    </div>
    <div class="modal-footer">
        <button class="btn btn-success btnDoDeleteCollege">Удалить</button>
        <button class="btn btn-default cancelDeleteCollege" data-dismiss="modal" aria-hidden="true">Отменть</button>
    </div>
</div>

<!-- Change Document Items To Download Modal SERVICE PAGE -->
<div id="modalDownloadMembers" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="regEndedTitle" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close cancelDownloadItems" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="documentItemsTitle">Выберите необходимые данные</h3>
    </div>
    <div class="modal-body">
        <div style="margin-bottom: 10px;">
            <input type="checkbox" id="check-all-download-checkboxes">
            <label for="check-all-download-checkboxes">Установить все флажки / Снять все флажки</label>
        </div>
        <div class="download-checkboxes">
            <div class="download-checkboxes-first-column">
                <div>
                    <input type="checkbox" data-download="locality" id="download-city">
                    <label for="download-city">Город</label>
                </div>
                <div>
                    <input type="checkbox" data-download="cell_phone" id="download-phone">
                    <label for="download-phone">Телефон</label>
                </div>
                <div>
                    <input type="checkbox" data-download="email" id="download-email">
                    <label for="download-email">Email</label>
                </div>
                <div>
                    <input type="checkbox" data-download="birth-date" id="download-birth-date">
                    <label for="download-birth-date">Дата рождения</label>
                </div>
                <div>
                    <input type="checkbox" data-download="age" id="download-age">
                    <label for="download-age">Возраст</label>
                </div>
                <div>
                    <input type="checkbox" data-download="male" id="download-male">
                    <label for="download-male">Пол</label>
                </div>
                <div>
                    <input type="checkbox" data-download="attend_meeting" id="download-attend_meeting">
                    <label for="download-attend_meeting">Посещает собрание</label>
                </div>
                <div>
                    <input type="checkbox" data-download="comment" id="download-comment">
                    <label for="download-comment">Комментарий</label>
                </div>
            </div>
            <div style="display: inline-block;">
                <div>
                    <input type="checkbox" data-download="school" id="download-school">
                    <label for="download-school">Школа</label>
                </div>
                <div>
                    <input type="checkbox" data-download="school_start" id="download-school_start">
                    <label for="download-school_start">Год начала учебы в школе</label>
                </div>
                <div>
                    <input type="checkbox" data-download="school_end" id="download-school_end">
                    <label for="download-school_end">Год окончания учебы в школе</label>
                </div>
                <div>
                    <input type="checkbox" data-download="school_level" id="download-school_level">
                    <label for="download-school_level">Класс</label>
                </div>
                <div>
                    <input type="checkbox" data-download="school_comment" id="download-school_comment">
                    <label for="download-school_comment">Примечание о школе</label>
                </div>
                <div>
                    <input type="checkbox" data-download="college" id="download-college">
                    <label for="download-college">Вуз</label>
                </div>
                <div>
                    <input type="checkbox" data-download="college_start" id="download-college_start">
                    <label for="download-college_start">Год начала учебы в вузе</label>
                </div>
                <div>
                    <input type="checkbox" data-download="college_end" id="download-college_end">
                    <label for="download-college_end">Год окончания учебы в вузе</label>
                </div>
                <div>
                    <input type="checkbox" data-download="college_level" id="download-college_level">
                    <label for="download-college_level">Курс</label>
                </div>
                <div>
                    <input type="checkbox" data-download="college_comment" id="download-college_comment">
                    <label for="download-college_comment">Примечание о вузе</label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-success downloadItems" data-dismiss="modal" aria-hidden="true">Подтвердить</button>
        <button class="btn btn-default cancelDownloadItems" data-dismiss="modal" aria-hidden="true">Отменть</button>
    </div>
</div>

<!-- Remove Member From List Modal -->
<div id="removeMemberFromList" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="regNameEdit" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 id="regNameEdit">Введите причину удаления участника из списка</h4>
    </div>
    <div class="modal-body">
        <div style="color: #AAA; margin-bottom: 10px; font-weight: normal; font-size: 12px;">
            Если эта карточка участника удаляется из-за дублирования, укажите Ф.И.О. карточки, которая остаётся в списке. Например: "В списке уже есть Иванова Антонина Павловна".
        </div>
        <div style="margin-bottom: 10px;">
            Вставить текст: <a href="" class="remove-member-reason empty-info">Информация отсутствует</a>, <a href="" class="remove-member-reason outside">Не в церковной жизни</a>.
        </div>
        <div>
            <input class="removeMemberReason" type="text" maxlength="70" valid="required" placeholder="Причина">
        </div>
    </div>
    <div class="modal-footer">
        <button id="remove-member" class="btn btn-success" data-dismiss="modal" aria-hidden="true">Удалить</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>

<div id="modalHintWindow" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Ok</button>
    </div>
</div>

<div id="modalHandleCloseEvent" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-body">
        <strong>Регистрация на это мероприятие закрыта. Продолжить?</strong>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary continue-closed-registration" data-dismiss="modal" aria-hidden="true">Да</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Нет</button>
    </div>
</div>

<!-- Registration Success Modal -->
<div id="modalRegSuccess" class="modal hide fade" data-width="720" tabindex="-1" role="dialog" aria-labelledby="regSuccessTitle">
    <div class="modal-header"><h3 id="regSuccessTitle"></h3></div>
    <div class="modal-body">
        <div class="lead" id="regSuccessText"></div>
        <div id="regSuccessNotes">
            <hr/>
            <p class="text-info">Пожалуйста, сохраните ссылку на ваш бланк регистрации:</p>
            <p class="text-info"><b><?php echo $appRootPath; ?><span id="regSuccessLink"></span></span></b></p>
            <p class="text-info">По этой ссылке вы можете в любое время изменить ваши данные. Мы просим вас до начала мероприятия указать в бланке уточнённое время приезда и отъезда или отменить регистрацию, если вы не сможете участвовать в мероприятии.</p>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" id="btnRegDone" aria-hidden="true">OK</button>
    </div>
</div>
