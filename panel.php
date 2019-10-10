<?php
include_once 'header.php';
include_once 'nav.php';
include_once 'panelsource/panelDB.php';
$pages = db_getPages();
$customPages = db_getCustomPagesPanel();
?>

<div id="" class="container">
  <div class="" style="display: flex">
    <div class="col-md-3">
      <h3>Admin panel</h3>
      <br>
      <select id="selMemberCategory" class="span2" title="Все страницы на которые когда либо осуществляется переход.">
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
        <option value="activity">Журнал активности пользователей</option>
      </select>
      <h3>PAGES</h3>
      <div class="">
        <?php
        foreach ($pages as $key => $page) {
            echo "<div><a target = '_blank' href='".$key."' >".$page."</a> <i class='icon-pencil'></i></div>";
        }?>
      </div>
      <h3>CUSTOM PAGES</h3>
      <div class="">
        <?php
        foreach ($customPages as $name => $value) {
            echo "<div><a target = '_blank' href='".$name."' >".$name."</a> <i class='icon-pencil'></i></div>";
        }?>
      </div>
    </div>
    <div class="col-md-6">
      <h4>Options panel</h4>
      <br>
      <br>
      <div class="" style="margin: 7px;">
        <input type="button" class="btn btn-danger" id="copySessions" name="" value="Copy session from admin to admin session" disabled>
      </div>
      <div class="" style="margin: 7px;">
        <input type="button" class="btn btn-danger" id="clearOldSessions" name="" value="Delete old session from admin session">
      </div>
      <div class=""style="border: 1px solid black; margin: 7px; padding: 7px;">
        <a href="logadmins">LOG ADMINS</a>
      </div>
      <div class=""style="border: 1px solid black; margin: 7px; padding: 7px;">
        <a href="panel">ADMIN PANEL</a>
      </div>
      <div class="noticePlace">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <span class="span">Sessions has been copied.</span>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <h4>Info</h4>
        <br>
        <br>
        SOme info

      </div>
  </div>

</div>
<script type="text/javascript">
var somePages = <?php echo $pages; ?>;
</script>


<script src="/panelsource/panel.js?v29"></script>

<?php
include_once 'footer.php';
?>
