<?php
$s = $_SERVER["SCRIPT_NAME"];

$h = ($_SERVER['PHP_SELF']);
$res = '';
switch ($h) {
     case '/index.php':
        $res = 'События';
        break;
    case '/reg.php':
        $res = 'Регистрация';
        break;
    case '/members.php':
        $res = 'Списки';
        break;
    case '/meetings.php':
        $res = 'Собрания';
        break;
    case '/visits.php':
        $res = 'Посещения и звонки';
        break;
    case '/list.php':
        $res = 'Ответственные';
        break;
    case '/login.php':
        $res = 'Войти';
        break;
    case '/signup.php':
        $res = 'Создать учётную запись';
        break;
    case '/reference.php':
        $res = 'Справка';
        break;
    case '/statistic.php':
        $res = 'Статистика';
        break;
    case '/youth.php':
        $res = 'Молодёжь';
        break;
    case '/event.php':
        $res = 'Мероприятия';
        break;
    case '/links.php':
        $res = 'Ссылки';
        break;
    default:
        $res = '';
        break;
}
?>
<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
        <span class="show-name-list" style="margin-top:10px;"><?php echo $res; ?></span>
        <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>

        <div class="btn-group" style="float: right; margin-right: 10px;">
          <a class="btn dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-question fa-lg"></i><span class="hide-name" style="padding-left: 5px">Помощь</span></a>
            <ul class="dropdown-menu pull-right">
              <?php

                $sortField = isset ($_COOKIE['sort_field_reference']) ? $_COOKIE ['sort_field_reference'] : 'name';
                $sortType = isset ($_COOKIE['sort_type_reference']) ? $_COOKIE ['sort_type_reference'] : 'asc';
                $references = db_getReferences($sortField, $sortType);

                $page = explode('.', substr($_SERVER['PHP_SELF'], 1))[0];
                $countReference = 0;

                foreach ($references as $key => $reference) {
                    if($page == $reference['page'] && $reference['published'] == '1'){
                        $countReference ++;
                        echo '<li class="modal-reference"><a href="'.$reference['link_article'].'" target="_blank">'.$reference['name'].'</a></li>';
                    }
                }

                if($countReference == 0){
                    echo "<li class='modal-reference'>Справочной информации по этому разделу пока нет</li>";
                }
                ?>
            </ul>
        </div>

        <!-- <span class="btn fa fa-envelope-o fa-lg send-message-support-phone" style="float: right;" data-toggle="modal" data-target="#messageAdmins" title="Отправить сообщение службе поддержки" aria-hidden="true"></span> -->

        <div class="nav-collapse collapse">
            <ul class="nav">
            <?php

            if(!isset($isGuest) && isset($memberId)){
                echo "<li ";
                if (strpos ($s,"/index")!==FALSE) echo 'class="active"';
                // if (strpos ($s,"/index")!==FALSE) echo 'class="active"';
                // echo '><a href="/">События</a></li>';
                echo '><a href="/index">События</a></li>';
            }

            if(!isset($isGuest) && db_isAdmin($memberId) || db_hasAdminFullAccess($memberId)) {
                echo '<li';
                if (strpos ($s,"/reg")!==FALSE || strpos ($s,"/admin")!==FALSE) {echo " class='active'";}
                echo"><a href='/reg'>Регистрация</a></li>";
            }

            if(!isset($isGuest) && db_isAdmin($memberId)) {
                echo '<li';
                if (strpos ($s,"/members")!==FALSE || strpos($s,"/youth")!==FALSE || strpos($s,"/list")!==FALSE) {echo " class='active'";}
                echo"><a href='/members'>Списки</a></li>";
            }

            /*if(!isset($isGuest) && db_isAdmin($memberId) || db_hasAdminFullAccess($memberId)) {
                echo '<li';
                if (strpos ($s,"/youth")!==FALSE) {echo " class='active'";}
                echo"><a href='/youth'>Молодёжь</a></li>";
            }*/

            if((!isset($isGuest) && db_isAdmin($memberId) && (!in_array('8', db_getUserSettings($memberId)))) || (db_hasAdminFullAccess($memberId) && (!in_array('8', db_getUserSettings($memberId))))) {
                echo '<li';
                if (strpos ($s,"/meetings")!==FALSE || strpos ($s,"/visits")!==FALSE ) {echo " class='active'";}
                echo"><a href='/meetings'>Собрания</a></li>";
            }

            /*if((!isset($isGuest) && db_isAdmin($memberId)) || db_hasAdminFullAccess($memberId)) {
                echo '<li';
                if (strpos ($s,"/list")!==FALSE){echo " class='active'";}
                echo"><a href='/list'>Ответственные</a></li>";
            }*/

            if(isset($memberId) && $memberId == '000005716' && !isset($isGuest) && db_isAdmin($memberId)) {
                echo '<li';
                if (strpos ($s,"/statistic")!==FALSE) {echo " class='active'";}
                echo"><a href='/statistic'>Архив</a></li>";
            }

            if(isset($memberId) && $memberId == '000005716' && !isset($isGuest) && db_isAdmin($memberId)) {
                echo '<li';
                if (strpos ($s,"/event")!==FALSE) {echo " class='active'";}
                echo"><a href='/event'>Мероприятия</a></li>";
            }


            if(isset($memberId)){
                echo '<li';
                if (strpos ($s,"/links")!==FALSE) {echo " class='active'";}
                echo"><a href='/links'>Ссылки</a></li>";
            }

            if(isset($memberId) && ($memberId == '000008601' || $memberId == '000001679' || $memberId == '000005716')){

                echo '<li';
                if (strpos ($s,"/reference")!==FALSE) {echo " class='active'";}
                echo"><a href='/reference'>Справка</a></li>";
            }

            if(!isset($isGuest) && isset($memberId)){
                echo '<li class="divider-vertical"></li>';
            }

            if (!isset($isGuest) && $memberId) {
                list($name, $email) = db_getMemberNameEmail($memberId);

                $_name = '';

                if($name){
                    $nameArr = explode(' ', $name);
                    $_name  = $nameArr[0].' '.( $nameArr[1] ? strtoupper(mb_substr($nameArr[1], 0, 1, 'utf-8')).'. ' : '' ).' '.($nameArr[2] ? strtoupper(mb_substr($nameArr[2], 0, 1, 'utf-8')).'. ' : '');
                }
                else{
                    $_name = $email;
                }

                echo '<li class="btn-group">
                        <a class="user-name-field dropdown-toggle" data-toggle="dropdown"';
                            //echo count(db_getAdminAccess ($memberId))>0 ? 'title="Область регистрации: '.htmlspecialchars (implode (", ", db_getAdminAccess ($memberId))).'"' : '';
                            echo 'href="#"><span class="user-name">'.$_name.'</span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li';
                        if (strpos ($s,'/profile')!==FALSE) echo ' class="active"';
                        echo '><a href="/profile">Профиль</a></li>';

                        $access_areas = db_getAdminAccess ($memberId);

                        if($access_areas && count($access_areas) > 0){
                            echo '<li';
                            if (strpos ($s,'/settings')!==FALSE) echo ' class="active"';
                            echo '><a href="/settings">Настройки</a></li>';
                        }
                        echo'<li><a href="/" class="logout">Выйти</a></li>
                        </ul>
                    </li>';
            }
            else {
                echo '<li ';
                if (strpos ($s,"/index")!==FALSE) echo 'class="active"';
                echo '><a href="/index">Войти</a></li>';
                echo '<li ';
                if (strpos ($s,"/signup")!==FALSE) echo 'class="active"';
                echo '><a href="/signup">Создать учётную запись</a></li>';
            }
            ?>
        </ul>
      </div><!--/.nav-collapse -->
      <div class='notifications center'></div>
    </div>
  </div>

</div>
<script>
function referenceSysAnew() {
  var memberId = '<?php echo $memberId; ?>';
  if (window.location.pathname.length === 3 && memberId) {
    var linksArr=[];
    var pathpath = window.location.pathname;
    pathpath = pathpath[1] + pathpath[2];
    $.post('/ajax/reference.php', {})
    .done(function(data){

      $(data.references).each(function (i) {
        var reference = data.references[i];
         if (reference['page'] == pathpath) {
            linksArr.push('<li class="modal-reference"><a href="'+reference["link_article"]+'" target="_blank">'+reference["name"]+'</a></li>');

         }
      })
      if (linksArr.length != 0) {
        $('.dropdown-menu.pull-right').html(linksArr);
      } else {
        linksArr = '<li class="modal-reference"><a href="#">Справочной информации по этому разделу пока нет</a></li>';
        $('.dropdown-menu.pull-right').html(linksArr);
      }
    });
  }
}

referenceSysAnew();

    $('.logout').click(function(e){
        e.preventDefault();

        var memberId = '<?php echo $memberId; ?>';
        var getSessionIdLogOut = "<?php print(session_id()); ?>"
        $.get('ajax/login.php?logout', {memberId: memberId, sessionId: getSessionIdLogOut})
        .done (function() {
            window.location ='<?php $_SESSION["sess_last_page"]; ?>';
        })
        .fail(function() {
            window.location = "/";
        })

    });

    $('.btn-navbar').click(function(){
        if($('.nav-collapse').hasClass('in')){
            $('.show-name-list').css('display','inline');
        }
        else $('.show-name-list').css('display','none');
    });

    $(".send-message-regteam").click (function (){
        $("#sendMsgEventName").text ($('#events-list option:selected').text());
    });
// Give me Admin Role 0   ver 5.1.8
    function setAdminRole_0(element1, element2) {
        var adminRole = parseInt('<?php echo db_getAdminRole($memberId); ?>');
        if (adminRole===0) {
          $ (element1).hide ();
          $ (element2).hide ();
        }
    }
</script>
