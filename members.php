<?php
include_once "headertmp.php";
include_once "nav2.php";

$hasMemberRightToSeePage = db_isAdmin($memberId);
if(!$hasMemberRightToSeePage){
    die();
}

$sort_field = isset ($_SESSION['sort_field-members']) ? $_SESSION['sort_field-members'] : 'name';
$sort_type = isset ($_SESSION['sort_type-members']) ? $_SESSION['sort_type-members'] : 'asc';
$localities = db_getAdminLocalities ($memberId);
$categories = db_getCategories();
$countries1 = db_getCountries(true);
$countries2 = db_getCountries(false);
$singleCity = db_isSingleCityAdmin($memberId);
$noEvent = true;
$roleThisAdmin = db_getAdminRole($memberId);
$selMemberLocality = isset ($_COOKIE['selMemberLocality']) ? $_COOKIE['selMemberLocality'] : '_all_';
$selMemberCategory = isset ($_COOKIE['selMemberCategory']) ? $_COOKIE['selMemberCategory'] : '_all_';

$allLocalities = db_getLocalities();
$adminLocality = db_getAdminLocality($memberId);

$user_settings = db_getUserSettings($memberId);
$userSettings = implode (',', $user_settings);

include_once 'modals2.php';

?>

<style>
body {
  padding-top: 60px;
  font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
  font-size: 14px;
  }
</style>
<div class="container">

<?php
$textBlock = db_getTextBlock('members_list');
if ($textBlock) echo "<div class='alert hide-phone'>$textBlock</div>";
?>
<div id="eventTabs" class="members-list">
    <div class="tab-content">
      <select class="form-control form-control-sm members-lists-combo" tooltip="Выберите нужный вам список здесь" style="width: 300px;">
          <option selected value="members">Общий список</option>
          <option value="youth">Молодые люди</option>
          <option value="list">Ответственные за регистрацию</option>
          <?php if ($roleThisAdmin===2) { ?>
            <option value="activity" selected>Активность ответственных</option>
          <?php } ?>
      </select>
        <div class="btn-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-success btn-sm add-member" data-locality="<?php echo $adminLocality; ?>"><i class="fa fa-plus icon-white"></i> <span class="hide-name">Добавить</span></button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-info btn-sm show-filters"><i class="fa fa-filter icon-white"></i> <span class="hide-name">Фильтры</span></button>
            </div>
            <?php if (!$singleCity) { ?>
            <div class="btn-group">
                <select id="selMemberLocality" class="form-control form-control-sm" >
                </select>
            </div>
            <?php } ?>
            <div class="btn-group">
                <select id="selMemberCategory" class="form-control form-control-sm">
                    <option value='_all_' selected <?php echo $selMemberCategory =='_all_' ? 'selected' : '' ?> >Все категории</option>
                    <?php foreach ($categories as $id => $name) {
                        echo "<option value='$id' ". ($id==$selMemberCategory ? 'selected' : '') .">".htmlspecialchars ($name)."</option>";
                    } ?>
                </select>
            </div>
            <div class="btn-group">
    					<select id="selMemberAttendMeeting" class="form-control form-control-sm">
    						<option value='_all_'>Все участники</option>
    						<option value='1' >Посещают собрания</option>
    						<option value='0' >Не посещают собрания</option>
    					</select>
    				</div>
            <div class="btn-group">
              <button class="btn btn-secondary btn-sm btnDownloadMembers" type="button"><i class="fa fa-download"></i> <span class="hide-name">Скачать</span></button>
            </div>
            <div class="btn-group">
              <button class="btn btn-secondary btn-sm btnShowStatistic" type="button"><i class="fa fa-bar-chart"></i> <span class="hide-name">Статистика</span></button>
            </div>
            <div class="btn-group">
              <button class="btn btn-secondary btn-sm search" type="button"><i class="icon-search fa fa-search" title="Поле поиска"></i></button>
                <div class="not-display" data-toggle="1">
                    <input type="text" class="search-text" placeholder="Введите текст" style="height: 30px;">
                    <i class="icon-remove fa fa-times admin-list clear-search-members" style="margin-left: -20px; margin-top: -6px;"></i>
                </div>
            </div>
            <?php if(isset($memberId) && ($memberId == '000008601')){ ?>
            <div class="btn-group">
                <a type="button" data-toggle='modal' class="btn btn-default upload_excel_file"><i class="fa fa-file" title="Загрузить файл Excel"></i></a>
            </div>
            <?php } ?>
            </div>
            <div class="desctopVisible">
                <table id="members" class="table table-hover">
                    <thead>
                    <tr>
                        <th><a id="sort-name" href="#" title="сортировать">Ф.И.О.</a>&nbsp;<i class="<?php echo $sort_field=='name' ? ($sort_type=='desc' ? 'icon-chevron-up fa fa-sort-asc' : 'icon-chevron-down fa fa-sort-desc') : 'icon-none'; ?>"></i></th>
                        <?php
                        if (!$singleCity)
                            echo '<th><a id="sort-locality" href="#" title="сортировать">Город</a>&nbsp;<i class="'.($sort_field=='locality' ? ($sort_type=='desc' ? 'icon-chevron-up fa fa-sort-asc' : 'icon-chevron-down fa fa-sort-desc') : 'icon-none').'"></i></th>';
                        ?>
                        <th>Телефон</th>
                        <th>Email</th>
                        <th style="width: 80px"><a id="sort-birth_date" href="#" title="сортировать">Возраст</a>&nbsp;<i class="<?php echo $sort_field=='birth_date' ? ($sort_type=='desc' ? 'icon-chevron-up fa fa-sort-asc' : 'icon-chevron-down fa fa-sort-desc') : 'icon-none'; ?>"></i></th>
                        <th style="width: 40px"><a id="sort-attend_meeting" href="#" title="Посещает собрания">С</a>&nbsp;<i class="<?php echo $sort_field=='attend_meeting' ? ($sort_type=='desc' ? 'icon-chevron-up fa fa-sort-asc' : 'icon-chevron-down fa fa-sort-desc') : 'icon-none'; ?>"></i></th>
                        <th> </th>
                        <th>&nbsp;</th>
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
                        <li><a id="sort-birth_date" data-sort="Возраст" href="#" title="сортировать">Возраст</a>&nbsp;<i class="<?php echo $sort_field=='birth_date' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                        <li><a id="sort-attend_meeting" href="#" data-sort="Посещает собрание" title="сортировать">Посещает собрание</a>&nbsp;<i class="<?php echo $sort_field=='attend_meeting' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                    </ul>
                </div>
                <table id="membersPhone" class="table table-hover">
                    <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
                </table>
            </div>
        </div>
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

<!-- Edit Member Modal -->
<div id="modalEditMember" data-width="560" class="modal modal-edit-member" aria-labelledby="editMemberTitle" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h3 id="editMemberTitle">Карточка участника</h3>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        </div>
        <div class="modal-body">
          <?php
          //require_once 'form.php';
          require_once 'formTab2.php';
          ?>
        </div>
        <div class="modal-footer">
          <!--<span class="footer-status">
              <input type="checkbox" class="emActive" />Активный
          </span> -->
          <button class="btn btn-info btn-sm disable-on-invalid" id="btnDoSaveMember">Сохранить</button>
          <button class="btn btn-secondary btn-sm" data-dismiss="modal" aria-hidden="true">Отменить</button>
        </div>
      </div>
    </div>
</div>

<!-- Name Editing Message Modal -->
<div id="modalNameEdit" class="modal" tabindex="-1" aria-labelledby="regNameEdit" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
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
        <button id="btnDoNameEdit" class="btn btn-success btn-sm" data-dismiss="modal" aria-hidden="true">Изменить ФИО</button>
        <button class="btn btn-secondary btn-sm" data-dismiss="modal" aria-hidden="true">Отмена</button>
      </div>
    </div>
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

<!-- FILTERS Modal -->
<div id="modalFilters" class="modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
        <h3>Фильтры</h3>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
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
          <button class="btn btn-secondary btn-sm" data-dismiss="modal" aria-hidden="true">Закрыть</button>
      </div>
    </div>
  </div>
</div>

<!-- Filter2 Modal -->
<div id="modalShowFilter" class="modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
        <h3></h3>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    </div>
    <div class="modal-body">
        <div class="show_filters_list">

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-success btn-sm save-filter-localities" data-dismiss="modal" aria-hidden="true">Сохранить</button>
        <button class="btn btn-secondary btn-sm" data-dismiss="modal" aria-hidden="true">Закрыть</button>
      </div>
    </div>
  </div>
</div>

<!-- Name Editing Message Modal -->
<div id="modalRemoveFilterConfirmation" class="modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
        <h3>Подтверждение удаления фильтра</h3>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <button class="btn btn-danger btn-sm remove_filter_confirm" data-dismiss="modal" aria-hidden="true">Подтвердить</button>
        <button class="btn btn-secondary btn-sm" data-dismiss="modal" aria-hidden="true">Отмена</button>
      </div>
    </div>
  </div>
</div>


<script>

var globalSingleCity = "<?php echo $singleCity; ?>";
window.user_settings = "<?php echo $userSettings; ?>".split(',');
var selectedLocalityGlo = "<?php echo $selMemberLocality; ?>";
var admin_idGlo = '<?php echo $memberId; ?>';
var singleCityGlo = '<?php echo $singleCity; ?>';
var roleThisAdminGlo = '<?php echo $roleThisAdmin; ?>';
// STOP check dublicate
</script>
<script src="/js/members.js?v9"></script>
<?php
include_once "footer.php";
?>
