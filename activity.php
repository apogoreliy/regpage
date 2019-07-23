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
$adminCountry = db_getAdminCountry($memberId);
$allLocalities = db_getLocalities();
$adminLocality = db_getAdminLocality($memberId);
$user_settings = db_getUserSettings($memberId);
$userSettings = implode (',', $user_settings);
$admisListLRC = db_getAdminsByLRC($memberId);
$listAdminLocality = db_getAdminsNameByMembersKeys($admisListLRC);
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
            <option value="activity" selected>Активность администраторов</option>
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
                    <option value="activity">Журнал активности пользователей</option>
                    <option value="event">Мероприятия (разр.)</option>
                    <option value="statistic">Архив (разр.)</option>
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
            <div class="btn-group" data-locality="<?php echo $adminLocality; ?>">
            </div>
            </div>
            <div class="desctopVisible">
                <table id="members" class="table table-hover">
                    <thead>
                    <tr>
                        <th><a id="sort-name" href="#" title="сортировать">Ф.И.О.</a>&nbsp;<i class="<?php echo $sort_field=='name' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        <th><a id="sort-page" href="#" title="сортировать">Страница</a>&nbsp;<i class="<?php echo $sort_field=='page' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        <th><a id="sort-time" href="#" title="сортировать">Время посещения</a>&nbsp;<i class="<?php echo $sort_field=='time' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
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
                        <li><a id="sort-page" data-sort="Страница" href="#" title="сортировать">Страница</a>&nbsp;<i class="<?php echo $sort_field=='page' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                        <li><a id="sort-time" href="#" data-sort="Время посещения" title="сортировать">Время посещения</a>&nbsp;<i class="<?php echo $sort_field=='time' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                    </ul>
                </div>
                <table id="membersPhone" class="table table-hover">
                    <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>

    window.user_settings = "<?php echo $userSettings; ?>".split(',');
    var globalAdminId = '<?php echo $memberId; ?>';
    var globalSelMemberLocality = "<?php echo $selMemberLocality; ?>";
    var globalAdminRole = "<?php echo db_getAdminRole($memberId); ?>"
    var globalSingleCity = "<?php echo $singleCity; ?>"
</script>
<script src="/js/activity.js?v28"></script>
<?php
include_once "footer.php";
?>
