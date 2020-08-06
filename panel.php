<?php
include_once 'header2.php';
include_once 'nav2.php';
include_once 'panelsource/panelDB.php';
include_once 'panelsource/adminpaneldb.php';
$pages = db_getPages();
$customPages = db_getCustomPagesPanel();

$memberId = db_getMemberIdBySessionId (session_id());
if ($memberId != '000005716'){
  return;
}
//$aaa = db_newDailyPracticesPac(9); Dont touch!!!
?>

<div id="" class="container">
  <div class="" style="display: flex">
    <div class="col-md-2" style="margin-top: 50px;">
      <h3>ADMIN PANEL</h3>
      <br>
      <select id="selMemberCategory" class="" style="width: 150px;" title="Все страницы на которые когда либо осуществляется переход.">
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
        <option value="invites">Пермалинки</option>
        <option value="vt">Видеообучение</option>
        <option value="pd">Официальные документы</option>
        <option value="pm">Молитвенная рассылка</option>
        <option value="st">Статистика</option>
        <option value="rb">Обучение в Индии</option>
        <option value="os">Обучение братьев</option>
        <option value="mc">Мини-конференции</option>
        <option value="ul">Избранные ссылки</option>
        <option value="reference">Настройка помощи</option>
        <option value="activity">Журнал активности</option>
      </select>
      <h4>Pages</h4>
      <div class="">
        <?php
        foreach ($pages as $key => $page) {
            echo "<div><a target = '_blank' href='".$key."' >".$page."</a> <i class='icon-pencil'></i></div>";
        }?>
      </div>
      <h4>Custom pages</h4>
      <div class="">
        <?php
        foreach ($customPages as $name => $value) {
            echo "<div><a target = '_blank' href='".$name."' >".$name."</a> <i class='icon-pencil'></i></div>";
        }?>
      </div>
    </div>
    <div class="col-md-7" style="margin-top: 50px;">
      <h4>Options panel</h4>
      <br>
      <div class="container">
        <h2>Toggleable Tabs</h2>
        <br>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#home">General</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#menu1">Contacts</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#menu2">Practices</a>
          </li>
        </ul>

  <!-- Tab panes -->
        <div class="tab-content">
          <div id="home" class="container tab-pane active"><br>
            <h3>GENERAL</h3>
            <div class="" style="margin: 7px;">
              <input type="button" class="btn btn-danger btn-sm" id="" name="" value="button">
            </div>
            <div class="" style="margin: 7px;">
              <input type="button" class="btn btn-danger btn-sm" id="clearOldSessions" name="" value="Delete old session from admin session">
            </div>
            <div class=""style="border: 1px solid black; margin: 7px; padding: 7px;">
              <a href="logadmins">LOG ADMINS</a>
            </div>
            <div class=""style="border: 1px solid black; margin: 7px; padding: 7px;">
              <a href="panel">ADMIN PANEL</a>
            </div>
            <div style="margin: 7px;">
              <input type="button" class="btn btn-warning btn-sm" id="" name="" value="Download log files">
            </div>
          </div>
          <div id="menu1" class="container tab-pane fade"><br>
            <h3>Contacts</h3>
            <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
          </div>
          <div id="menu2" class="container tab-pane fade"><br>
            <h3>Practices</h3>
            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
          </div>
        </div>
      </div>
        <div class="noticePlace">
          <div class="alert alert-success alert-dismissible fade">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> Indicates a successful or positive action.
          </div>
        </div>
      </div>
      <div class="col-md-3" style="margin-top: 50px;">
        <h4>Info</h4>
        <br>
        <br>
        SOme info
      </div>
  </div>

</div>
<script type="text/javascript">
var somePages = <?php echo $pages; ?>;
var data_page = {};
</script>


<script src="/panelsource/panel.js?v29"></script>

<?php
include_once 'footer2.php';
?>
