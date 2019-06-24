<?php
include_once "header.php";
include_once "nav.php";

$hasMemberRightToSeePage = db_isAdmin($memberId);
if(!$hasMemberRightToSeePage){
    die();
}

$sort_field = isset ($_SESSION['sort_field-activity']) ? $_SESSION['sort_field-activity'] : 'name';
$sort_type = isset ($_SESSION['sort_type-activity']) ? $_SESSION['sort_type-activity'] : 'asc';
$localities = db_getAdminLocalities ($memberId);
$categories = db_getCategories();
$countries1 = db_getCountries(true);
$countries2 = db_getCountries(false);
$singleCity = db_isSingleCityAdmin($memberId);
$roleThisAdmin = db_getAdminRole($memberId);
$noEvent = true;
$selMemberLocality = isset ($_COOKIE['selMemberLocality']) ? $_COOKIE['selMemberLocality'] : '_all_';
$selMemberCategory = isset ($_COOKIE['selMemberCategory']) ? $_COOKIE['selMemberCategory'] : '_all_';

$allLocalities = db_getLocalities();
$adminLocality = db_getAdminLocality($memberId);

$user_settings = db_getUserSettings($memberId);
$userSettings = implode (',', $user_settings);
$ogogo = db_getAdminByLocalityCombobox($adminLocality);
$listAdminLocality = db_getAdminsListByLocalitiesCombobox($adminLocality);
include_once 'modals.php';

?>

<style>body {padding-top: 60px;}</style>
<div class="container">

<?php
$textBlock = db_getTextBlock('members_list');
if ($textBlock) echo "<div class='alert hide-phone'>$textBlock</div>";
?>
<div id="eventTabs" class="members-list">
    <div class="tab-content">
      <select class="controls span4 members-lists-combo" tooltip="Выберите нужный вам список здесь">
          <option value="members">Общий список</option>
          <option value="youth">Молодые люди</option>
          <option value="list">Ответственные за регистрацию</option>
          <?php if ($roleThisAdmin===2) { ?>
            <option value="activity" selected>Активность админов</option>
          <?php } ?>
      </select>
        <div class="btn-toolbar">
            <?php if (!$singleCity) { ?>
            <div class="btn-group">
                <select id="selMemberLocality" class="span2" >
                </select>
            </div>
            <?php } ?>
            <div class="btn-group">
                <select id="selMemberCategory" class="span2">
                    <option value="_all_" selected>Все страницы</option>
                    <option value="index">События</option>
                    <option value="reg">Регистрация</option>
                    <option value="members">Общий список</option>
                    <option value="youth">Молодые люди</option>
                    <option value="list">Ответственные за регистрацию</option>
                    <option value="meetings">Собрания</option>
                    <option value="visits">Посещения</option>
                    <option value="links">Ссылки</option>
                    <option value="help">Помощь</option>
                    <option value="profile">Профиль</option>
                    <option value="settings">Настройки</option>
                    <option value="login">Логин</option>
                    <option value="passrec">Восстановление пароля</option>
                    <option value="signup">Новый аккаунт</option>
                    <option value="invites">Пермалинки</option>
                    <option value="reference">Настройка помощи</option>
                    <option value="vt">Видеообучение</option>
                    <option value="pd">Официальные документы</option>
                    <option value="pm">Молитвенная рассылка</option>
                    <option value="st">Статистика</option>
                    <option value="rb">Обучение в Индии</option>
                    <option value="os">Обучение братьев</option>
                    <option value="mc">Мини-конференции</option>
                    <option value="ul">Избранные ссылки</option>
                    <option value="event">Мероприятия (разр.)</option>
                    <option value="statistic">Архив (разр.)</option>
                    <option value="activity">Активность (разр.)</option>
                    <option value="panel">Админка</option>
                </select>
            </div>
            <div class="btn-group">
    					<select id="listAdmins" class="span2">
                <option value='_all_' selected>Все ответственные</option>
    						<?php foreach ($listAdminLocality as $id => $name) {
                echo "<option value='$id'>".htmlspecialchars ($name)."</option>";
              } ?>
    					</select>
    				</div>
            <div class="input-group input-daterange datepicker">
                <input type="text" class="span2 start-date" value="<?php echo date("d.m.Y", strtotime("-1 months")); ?>" style="margin-bottom: -10px">
                <i class="btn fa fa-calendar" aria-hidden="true" style="margin-bottom: -10px"></i>
                <input type="text" class="span2 end-date" value="<?php echo date('d.m.Y'); ?>" style="margin-bottom: -10px">
            </div>
            <div class="btn-group">
                <a class="btn dropdown-toggle btnShowStatistic" data-toggle="dropdown" href="#" disabled>
                    <i class="fa fa-bar-chart"></i> <span class="hide-name">Статистика</span>
                </a>
            </div>
            <div class="btn-group">
                <a type="button" class="btn btn-default search"><i class="icon-search" title="Поле поиска"></i></a>
                <div class="not-display" data-toggle="1">
                    <input type="text"  class="controls search-text" placeholder="Введите текст">
                    <i class="icon-remove admin-list clear-search-members" style="margin-left: -20px; margin-top: -6px;"></i>
                </div>
            </div>
            <div class="btn-group">
                <label for="hideDevelopers"><input type="checkbox" id="hideDevelopers" style="margin-top: 0px;" name="" value=""> Скрыть разработчиков</label>
            </div>
            <div class="btn-group" data-locality="<?php echo $adminLocality; ?>">
            </div>
            </div>
            <div class="desctopVisible">
                <table id="members" class="table table-hover">
                    <thead>
                    <tr>
                        <th><a id="sort-number" href="#" title="сортировать">No</a>&nbsp;<i class="<?php echo $sort_field=='number' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        <th><a id="sort-name" href="#" title="сортировать">Ф.И.О.</a>&nbsp;<i class="<?php echo $sort_field=='name' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        <th><a id="sort-page" href="#" title="сортировать">Page</a>&nbsp;<i class="<?php echo $sort_field=='page' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        <th><a id="sort-time" href="#" title="сортировать">Time</a>&nbsp;<i class="<?php echo $sort_field=='time' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        <?php
                        if (!$singleCity)
                            echo '<th><a id="sort-locality" href="#" title="сортировать">Город</a>&nbsp;<i class="'.($sort_field=='locality' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none').'"></i></th>';
                        ?>
                    </tr>
                    </thead>
                    <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
                </table>
            </div>
            <div class="show-phone">
                <div class="dropdown">
                    <button style="margin-top: 10px;" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <span class="sortName"><?php echo $s = isset($_COOKIE['sort'])? $_COOKIE['sort'] : 'Сортировать' ?></span>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" id="dropdownMenu2" aria-labelledby="dropdownMenu">
                        <li><a id="sort-name" data-sort="ФИО" href="#" title="сортировать">ФИО</a>&nbsp;<i class="<?php echo $sort_field=='name' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                        <li>
                            <?php
                            if (!$singleCity){
                                echo '<a id="sort-locality" data-sort="Город" href="#" title="сортировать">Город</a>&nbsp;<i class="'.($sort_field=='locality' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none').'"></i>';
                            }
                            ?>
                        </li>
                        <li><a id="sort-birth_date" data-sort="Возраст" href="#" title="сортировать">Возраст</a>&nbsp;<i class="<?php echo $sort_field=='page' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                        <li><a id="sort-attend_meeting" href="#" data-sort="Посещает собрание" title="сортировать">Посещает собрание</a>&nbsp;<i class="<?php echo $sort_field=='time' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                    </ul>
                </div>
                <table id="membersPhone" class="table table-hover">
                    <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Edit Member Modal -->
<div id="modalEditMember" data-width="560" class="modal hide fade modal-edit-member" tabindex="-1" role="dialog" aria-labelledby="editMemberTitle" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="editMemberTitle">Карточка участника</h3>
    </div>
    <div class="modal-body">
        <?php
        //require_once 'form.php';
        require_once 'formTab.php';
        ?>
    </div>
    <div class="modal-footer">
        <!--<span class="footer-status">
            <input type="checkbox" class="emActive" />Активный
        </span> -->
        <button class="btn btn-info disable-on-invalid" id="btnDoSaveMember">Сохранить</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Отменить</button>
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

<!-- Name Editing Message Modal -->
<div id="modalUploadExcel" class="modal hide fade" data-width="1100" tabindex="-1" role="dialog" aria-labelledby="regNameEdit" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Загрузить файл</h3>
    </div>
    <div class="modal-body">
        <div class="btn-group">
            <a type="button" class="btn btn-default send_file" style="margin-right: 10px;"><i class="fa fa-download" title="Отправить файл"></i></a>
            <input type="file" class="uploaded_excel_file" placeholder="Выберите файл">
        </div>
        <div class="list_data">

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    </div>
</div>

<!-- Name Editing Message Modal -->
<div id="modalFilters" class="modal hide fade" data-width="400" tabindex="-1" role="dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Фильтры</h3>
    </div>
    <div class="modal-body">
        <div class="btn-group">
            <span class="btn btn-success fa fa-plus create_filter" title="Создать фильтр"></span>

        </div>
        <div class="btn-group filter_name_block" >
            <input class="filter_name" type="text" placeholder="Название" style="margin-bottom: 0; margin-left: 10px;"/>
            <span class="fa fa-check add-filter" title="Сохранить фильтр" style="font-size: 20px;"></span>
        </div>
        <div class="filters_list" style="margin-top: 20px;">

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    </div>
</div>

<!-- Name Editing Message Modal -->
<div id="modalShowFilter" class="modal hide fade" data-width="400" tabindex="-1" role="dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3></h3>
    </div>
    <div class="modal-body">
        <div class="show_filters_list">

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-success save-filter-localities" data-dismiss="modal" aria-hidden="true">Сохранить</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    </div>
</div>

<!-- Name Editing Message Modal -->
<div id="modalRemoveFilterConfirmation" class="modal hide fade" data-width="500" tabindex="-1" role="dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Подтверждение удаления фильтра</h3>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <button class="btn btn-danger remove_filter_confirm" data-dismiss="modal" aria-hidden="true">Подтвердить</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>

<script>
console.log("<?php echo $ogogo[0]; ?>");
console.log("<?php echo $ogogo[1]; ?>");
console.log("<?php echo $ogogo[2]; ?>");
console.log("<?php echo $ogogo[3]; ?>");
console.log("<?php echo $ogogo[4]; ?>");
console.log("<?php echo $ogogo[5]; ?>");
console.log("<?php echo $ogogo[6]; ?>");
console.log("<?php echo $ogogo[7]; ?>");
console.log("<?php echo $ogogo[8]; ?>");
    window.user_settings = "<?php echo $userSettings; ?>".split(',');
    var globalAdminId = '<?php echo $memberId; ?>';
    var globalSelMemberLocality = "<?php echo $selMemberLocality; ?>";
    var globalAdminRole = "<?php echo db_getAdminRole($memberId); ?>"
    var globalSingleCity = "<?php echo $singleCity; ?>"
</script>
<script src="/js/activity.js?v23"></script>
<?php
include_once "footer.php";
?>
