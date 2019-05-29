<?php
include_once "header.php";
include_once "nav.php";

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

$selMemberLocality = isset ($_COOKIE['selMemberLocality']) ? $_COOKIE['selMemberLocality'] : '_all_';
$selMemberCategory = isset ($_COOKIE['selMemberCategory']) ? $_COOKIE['selMemberCategory'] : '_all_';

$allLocalities = db_getLocalities();
$adminLocality = db_getAdminLocality($memberId);

$user_settings = db_getUserSettings($memberId);
$userSettings = implode (',', $user_settings);

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
          <option selected value="members">Общий список</option>
          <option value="youth">Молодые люди</option>
          <option value="list">Ответственные за регистрацию</option>
      </select>
        <div class="btn-toolbar">
            <div class="btn-group">
                <a class="btn btn-success add-member" data-locality="<?php echo $adminLocality; ?>" type="button"><i class="fa fa-plus icon-white"></i> <span class="hide-name">Добавить</span></a>
            </div>
            <div class="btn-group">
                <a class="btn btn-info show-filters" type="button"><i class="fa fa-filter icon-white"></i> <span class="hide-name">Фильтры</span></a>
            </div>
            <?php if (!$singleCity) { ?>
            <div class="btn-group">
                <select id="selMemberLocality" class="span2" >
                </select>
            </div>
            <?php } ?>
            <div class="btn-group">
                <select id="selMemberCategory" class="span2">
                    <option value='_all_' selected <?php echo $selMemberCategory =='_all_' ? 'selected' : '' ?> >Все категории</option>
                    <?php foreach ($categories as $id => $name) {
                        echo "<option value='$id' ". ($id==$selMemberCategory ? 'selected' : '') .">".htmlspecialchars ($name)."</option>";
                    } ?>
                </select>
            </div>
            <div class="btn-group">
    					<select id="selMemberAttendMeeting" class="span2">
    						<option value='_all_' >Все участники</option>
    						<option value='1' >Посещают собрания</option>
    						<option value='0' >Не посещают собрания</option>
    					</select>
    				</div>
            <div class="btn-group">
                <a class="btn dropdown-toggle btnDownloadMembers" data-toggle="dropdown" href="#">
                    <i class="fa fa-download"></i> <span class="hide-name">Скачать</span>
                </a>
            </div>
            <div class="btn-group">
                <a class="btn dropdown-toggle btnShowStatistic" data-toggle="dropdown" href="#">
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
                        <th><a id="sort-name" href="#" title="сортировать">Ф.И.О.</a>&nbsp;<i class="<?php echo $sort_field=='name' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        <?php
                        if (!$singleCity)
                            echo '<th><a id="sort-locality" href="#" title="сортировать">Город</a>&nbsp;<i class="'.($sort_field=='locality' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none').'"></i></th>';
                        ?>
                        <th>Телефон</th>
                        <th>Email</th>
                        <th><a id="sort-birth_date" href="#" title="сортировать">Возраст</a>&nbsp;<i class="<?php echo $sort_field=='birth_date' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                        <th><a id="sort-attend_meeting" href="#" title="Посещает собрания">С</a>&nbsp;<i class="<?php echo $sort_field=='attend_meeting' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
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
    window.user_settings = "<?php echo $userSettings; ?>".split(',');
    get_localities();
    setAdminRole_0('.add-member','#btnDoSaveMember');

    function get_localities(){
        $.get('/ajax/members.php?get_localities')
        .done (function(data) {
            renderLocalities(data.localities);
        });
    }

    function renderLocalities(localities){
        var localities_list = [],
            selectedLocality = "<?php echo $selMemberLocality; ?>";

        localities_list.push("<option value='_all_' " + (selectedLocality =='_all_' ? 'selected' : '') +" >Все местности</option>");

        for (var l in localities){
            var locality = localities[l];
            localities_list.push("<option value='"+locality['id']+"' " + (selectedLocality == l ? 'selected' : '') +" >"+he(locality['name'])+"</option>");
        }

        $("#selMemberLocality").html(localities_list.join(''));
    }

    $('.show-filters').click(function(){
        $('.filter_name_block').hide();
        $('.filter_name').text('');
        getFilters();
        $("#modalFilters").modal('show');
    });

    $(".create_filter").click(function(){
        $('.filter_name_block').css('display', 'inline-block');
    });

    $('.remove_filter_confirm').click(function(){
        var filter_id = $(this).attr('data-filter_id');

        $.get('/ajax/members.php?remove_filter', {filter_id : filter_id})
        .done (function(data) {
            renderFilters(data.filters);
        });
    })

    $('.add-filter').click(function(){
        var filter_name = $('.filter_name').val().trim(),
            isDublicat = false;

        if(filter_name === ''){
            showError('Название фильтра не может быть пустым!');
            return
        }

        $("#modalFilters .filter_item").each(function(){
            var name = $(this).attr('data-name');

            if(name == filter_name){
                isDublicat = true;
            }
        });

        if(isDublicat){
            showError('Фильтр с таким названием уже существует и не может быть добавлен!');
        }
        else{
            $.get('/ajax/members.php?add_filter', {filter_name : filter_name})
            .done (function(data) {
                $('.filter_name').val('');
                showHint('Фильтр успешно добавлен');
                renderFilters(data.filters);
            });
        }
    });

    function getFilters(){
        $.get('/ajax/members.php?get_filters')
        .done (function(data) {
            renderFilters(data.filters);
        });
    }

    function renderFilters(filters){
        get_localities();

        var filters_list = [];

        for(var f in filters){
            var filter = filters[f],
                countItems = filter.value ? filter.value.split(',') : [];

            filters_list.push('<div class="filter_item" data-localities="'+filter.value+'" data-name="'+filter.name+'" data-id="'+filter.id+'">'+
                '<span class="fa fa-list-ul show_filter" title="Просмотреть фильтр"></span>'+
                '<span class="fa fa-pencil edit_filter" title="Редактировать фильтр"></span>'+
                '<span class="fa fa-trash remove_filter" title="Удалить фильтр"></span>'+
                '<span class="edit_filter_name">' +filter.name+ '</span>' +
                '<input class="filter_name_field" />'+
                '<span class="fa fa-check save_filter_name"></span>' +
                '<span>'+ (countItems.length > 0 ? " (" +countItems.length+ ") " : "") +'</span></div>');
        }


        $('.filters_list').html(filters_list.join(''));

        $('.remove_filter').click(function(){
            var filter_id = $(this).parents('.filter_item').attr('data-id'),
                filter_name = $(this).parents('.filter_item').attr('data-name'),
                modal = $('#modalRemoveFilterConfirmation');

            modal.find('.modal-body').text("Вы действительно хотите удалить данный фильтр - " + filter_name);
            modal.find('.remove_filter_confirm').attr('data-filter_id', filter_id);
            modal.modal('show');
        });

        $('.edit_filter').click(function(){
            var filter_name = $(this).parents('.filter_item').attr('data-name');

            $(this).parents('.filter_item').find('.edit_filter_name').css('display', 'none');
            $(this).parents('.filter_item').find('.save_filter_name').css('display', 'inline');
            $(this).parents('.filter_item').find('.filter_name_field').val(filter_name).css('display', 'inline');
        });

        $('.save_filter_name').click(function(){
            var filter_id = $(this).parents('.filter_item').attr('data-id'),
                filter_name = $(this).parents('.filter_item').find('.filter_name_field').val();

            $.get('/ajax/members.php?save_filter', {filter_id : filter_id, filter_name: filter_name})
            .done (function(data) {
                $(this).parents('.filter_item').find('.edit_filter_name').css('display', 'inline');
                $(this).parents('.filter_item').find('.filter_name_field').css('display', 'none');
                $(this).parents('.filter_item').find('.save_filter_name').css('display', 'none');

                renderFilters(data.filters);
            });
        });

        $('.show_filter').click(function(){
            var filter_id = $(this).parents('.filter_item').attr('data-id'),
                filter_name = $(this).parents('.filter_item').attr('data-name'),
                filter_localities = $(this).parents('.filter_item').attr('data-localities'),
                modal = $("#modalShowFilter"),
                filter_localities_list = [];

            if(filter_localities){
                filter_localities_list = filter_localities.split(',');
            }

            var temp_localities_list = [];

            $("#selMemberLocality option").each(function(){
                var l = $(this).val(),
                    locality =  $(this).text();

                if(l){
                    temp_localities_list.push('<div style="margin-bottom: 5px;"><input style="margin-top:0" id="'+l+'" type="checkbox" '+( in_array(l, filter_localities_list)? "checked" : "")+' /><label for="'+l+'" style="display:inline; margin-left: 10px;">'+locality+'</label></div>');
                }
            });

            modal.attr('data-filter_id', filter_id);
            modal.find('.modal-header h3').text(filter_name);
            modal.find('.show_filters_list').html(temp_localities_list.join(''));
            modal.modal('show');
        });
    }

    $('.save-filter-localities').click(function(){
        var modal = $("#modalShowFilter"),
            filter_id = modal.attr('data-filter_id'),
            checkedLocalities = [];

        modal.find('.show_filters_list input').each(function(){
            var isChecked = $(this).prop('checked'),
                id = $(this).attr('id');

            if(isChecked){
                checkedLocalities.push(id);
            }
        });

        $.get('/ajax/members.php?save_filter_localities', {filter_id : filter_id, filter_localities: checkedLocalities.join(',')})
        .done (function(data) {
            renderFilters(data.filters);
        });
    });

    $('.upload_excel_file').click(function(){
        $('#modalUploadExcel .list_data').html('');
        $('#modalUploadExcel').modal('show');
    });

    $('.send_file').click(function(){
        var file_data = $('.uploaded_excel_file').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);

        var admin_id = '<?php echo $memberId; ?>';

        $.ajax({
            url: '/ajax/excelList.php?upload_file&admin_id='+admin_id,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data){
                if(data.res){
                    showHint("Данные успешно загружены");
                }
                else{
                    showError("При загрузке данных произошел сбой. Обратитесь в службу поддержки.");
                }
                $('#modalUploadExcel').modal('hide');
            }
         });
    });

    function showDataFromExcelFile(data){
        var columns = data[0],
            //items = [],
            members = [],
            desiredFields = ['Фамилия', 'Имя', 'Отчество','Пол','Дата рождения','Местность','Состояние','Трапеза'],
            nameFields = ['Фамилия', 'Имя', 'Отчество'];

        for (var rows in data){
            var member = [],
                //item_data = [],
                nameData = [];

            if (rows != 0){
                for (var row in data[rows]){
                    if(columns[row] !== null && columns[row] !== undefined && in_array(columns[row], desiredFields)){
                        member.push({
                            key:   columns[row],
                            value: data[rows][row]
                        });

                        //item_data.push("<span title='"+ columns[row] + " ("+ data[rows][row] +")'>" + (data[rows][row].length > 28 ? data[rows][row].substring(0, 30) + '...' : data[rows][row]) + "</span>");
                    }
                }
                //if (rows != 0){
                //    items.push("<div>"+ item_data.join(' ') +"</div>");
                //}

                for(var m in member){
                    if(in_array(member[m]['key'], nameFields)){
                        nameData.push(member[m]['value'])
                    }
                }
                member.push({'ФИО': nameData.join(' ')});
                members.push(member);
            }
        }

        $.post('/ajax/members.php?downloadExcelData', {members : JSON.stringify(members)})
        .done (function(data) {
            //$('#modalUploadExcel').modal('hide');
        });

        //$('.list_data').html(items.join(''));
    }

    function loadDashboard (){
        $.getJSON('/ajax/members.php', { sortedFields : sortedFields()})
            .done (function(data) {
                refreshMembers (data.members); });
    }

    function refreshMembers (members){
        var tableRows = [], phoneRows = [];

        for (var i in members){
            var m = members[i];

            // *** last editor
            var notMe = (m.admin_key && m.admin_key!=window.adminId);
            // if the author is same for reg and mem records is was decided to show it only once
            var editor = m.admin_name;
            var htmlEditor = notMe ? '<i class="icon-user" title="Последние изменения: '+editor+'"></i>': '';

            // *** changes processed
            var htmlChanged = (m.changed > 0 ? '<i class="icon-pencil" title="Изменения еще не обработаны"></i>' : '');
            var age = getAgeWithSuffix(parseInt(m.age), m.age);
            // Cut the m.region string. Roman's code ver 5.0.0
            if (m.region =='--') {
              m.region = m.country;
            } else {
              m.region = m.region.substring(0, m.region.indexOf(" ("));
              // m.region += ', ';
              // m.region += m.country;
            }

            tableRows.push('<tr data-id="'+m.id+'" data-name="'+m.name+'" data-age="'+m.age+'" data-attendance="'+m.attend_meeting+'" data-locality="'+m.locality_key+'" data-category="'+m.category_key+'" class="'+(m.active==0?'inactive-member':'member-row')+'">'+
                '<td>' + he(m.name) +
(in_array(5, window.user_settings) ? '<br/>'+ '<span class="user_setting_span">'+m.category_name+'</span>' : '') +
                '</td>' +
                <?php if (!$singleCity) { ?>
                    '<td style="width:160px">' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') +
                    (in_array(6, window.user_settings) ? '<br/>'+ '<span class="user_setting_span">'+(m.region || m.country)+'</span>' : '') +
                    '</td>' +
                <?php } ?>
                '<td>' + he(m.cell_phone) + '</td>' +
                '<td>' + he(m.email) + '</td>' +
                '<td style="width:50px">' + age + '</td>' +
                '<td><input type="checkbox" class="check-meeting-attend" '+ (m.attend_meeting == 1 ? "checked" : "") +' /></td>' +
                '<td>' + htmlChanged + htmlEditor + '</td>' +
  <?php if (db_getAdminRole($memberId) != 0) { ?> '<td><i class="'+(m.active==0?'icon-circle-arrow-up':'icon-trash')+' icon-black" title="'+(m.active==0?'Добавить в список':'Удалить из списка')+'"/></td>' <?php } ?> +
                '</tr>'
            );

            phoneRows.push('<tr data-id="'+m.id+'" data-name="'+m.name+'" data-age="'+m.age+'" data-attendance="'+m.attend_meeting+'" data-locality="'+m.locality_key+'" data-category="'+m.category_key+'" class="'+(m.active==0?'inactive-member':'member-row')+'">'+
                '<td><span style="color: #006">' + he(m.name) + '</span>'+
                '<i style="float: right; cursor:pointer;" class="'+(m.active==0?'icon-circle-arrow-up':'icon-trash')+' icon-black" title="'+(m.active==0 ? 'Добавить в список':'Удалить из списка')+'"/>'+
                <?php if (!$singleCity) echo "'<div>' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') + ', ' + age + '</div>' + "; ?> (in_array(6, window.user_settings) ? '<span class="user_setting_span">'+(m.region || m.country)+'</span>' : '') +
                '<div><span >'+ /*(m.cell_phone?'тел.: ':'') + */ he(m.cell_phone.trim()) + '</span>'+ (m.cell_phone && m.email ? ', ' :'' )+'<span>'+ /*(m.email?'email: ':'') + */ he(m.email) + '</span></div>' +
                '<div>Посещает собрания: <input type="checkbox" class="check-meeting-attend" '+ (m.attend_meeting == 1 ? "checked" : "") +' /></div>'+
                '<div>'+ htmlChanged + htmlEditor + '</div>'+
                '</td>' +
                '</tr>'
            );
        }

        $(".desctopVisible tbody").html (tableRows.join(''));
        $(".show-phone tbody").html (phoneRows.join(''));

        filterMembers();

        $(".member-row").unbind('click');
        $(".member-row").click (function (e) {
            e.stopPropagation();
            var memberId = $(this).attr('data-id');
            $.getJSON('/ajax/get.php', { member: memberId })
                .done (function(data) {
                    fillEditMember (memberId, data.member, data.localities);
                    //window.currentEditMemberId = memberId;
                    $('#modalEditMember #btnDoSaveMember').removeClass('create');
                    $('#modalEditMember').modal('show');
            })
        });

        $(".icon-black").unbind('click');
        $(".icon-black").click(function (event) {
            event.stopPropagation();

            if($(this).hasClass('icon-trash')){
                window.removeMemberId = $(this).parents('tr').attr('data-id');

                $.post('/ajax/members.php?is_member_in_reg', {
                    memberId : window.removeMemberId
                })
                .done(function(data){
                    if(!data.res){
                        if(window.removeMemberId.substr(0,2) === '99'){
                            removeMember(window.removeMemberId);
                        }
                        else{
                            $('#removeMemberFromList').modal('show');
                        }
                    }
                    else{
                        showError('Этот участник находится в списке регистрации! Удаление отменено.');
                    }
                });
            }
            else{
                var searchText = $('.search-text').val();
                var recoverMemberId = $(this).parents('tr').attr('data-id');
                handleMember(recoverMemberId, 1, '', searchText);
            }
        });

        $("#check-all-download-checkboxes").change(function(){
            var checkAll = $(this).prop('checked');
            var a = 0;

            $(this).parents("#modalDownloadMembers").find(".download-checkboxes input[type='checkbox']").each(function(){
              $(this).attr('id')==='member-name' ? a = 1 : a = 0;              
              if (a === 0) {
                $(this).prop('checked', checkAll);
              }
            });
        });

        $('.downloadItems').click(function(){
            var checkedFields = [];
            $("#modalDownloadMembers").find("input[type='checkbox']").each(function(){
                if ($(this).prop('checked')==true){
                    checkedFields.push($(this).attr('data-download'));
                }
            });

            downloadMembersListExel(members, checkedFields);
            checkedFields = [];
        });

        $(".check-meeting-attend").click(function(e){
            e.stopPropagation();
        });

        $(".check-meeting-attend").change(function(e){
            e.stopPropagation();

            var value = $(this).prop('checked') ? 1 : 0, memberId = $(this).parents('tr').attr('data-id');

            $.post('/ajax/members.php?set_attend_meeting', {value : value, memberId : memberId})
            .done(function(data){
                if(data.result && value == 1){
                    showModalHintWindow("<strong>"+data.result+"</strong>");
                }
            });
        });
    }

    $(".btnDownloadMembers").click(function(event){
        event.stopPropagation();
        $('#modalDownloadMembers').modal('show').find("input[type='checkbox']").each(function(){
            $(this).prop('checked', true);
        });
    });

    $(".remove-member-reason").click(function(e){
        e.stopPropagation();
        e.preventDefault();
        var reason = '';

        if($(this).hasClass('empty-info')){
            reason = 'Информация отсутствует';
        }
        else if($(this).hasClass('outside')){
            reason = 'Не в церковной жизни';
        }
        $(".removeMemberReason").val(reason);
    });

    $(".btnShowStatistic").click(function(e){
        e.stopPropagation();
        var isTabletMode = $(document).width()<786,
            filterLocality = $('#selMemberLocality option:selected').text(),
            localitiesByFilter = [],
            countMembers = 0, countBelivers=0, countScholars = 0,
            countPreScholars = 0, countStudents = 0, countSaints = 0,
            countRespBrothers = 0, countFullTimers = 0, countTrainees = 0,
            countOthers = 0, countAttendances = 0,

            memberAgeIsNullList = [],

            countScholarsByAge = 0, countStudentsByAge = 0, countSaintsByAge = 0,
            countAttendancesScholarsByAge = 0, countAttendancesStudentsByAge = 0, countAttendancesSaintsByAge = 0,
            countAttendancesByAge = 0, countByAge = 0,

            countAttendancesMembers = 0, countAttendancesBelivers=0, countAttendancesScholars = 0,
            countAttendancesPreScholars = 0, countAttendancesStudents = 0, countAttendancesSaints = 0,
            countAttendancesRespBrothers = 0, countAttendancesFullTimers = 0, countAttendancesTrainees = 0,
            countAttendancesOthers = 0,
            averageAge = 0, averageAgeAttendances = 0;

        $(".members-list " + ( isTabletMode ? " #membersPhone " : " #members " ) + " tbody tr").each(function(){
            if($(this).css('display') !== 'none' && !$(this).hasClass('inactive-member')){
                countMembers ++;

                var name = $(this).attr('data-name'),
                    locality = $(this).attr('data-locality'),
                    category = $(this).attr('data-category'),
                    age = $(this).attr('data-age');

                if(!age || age == 'null'){
                    memberAgeIsNullList.push(name);
                }
                else{
                    if(age >=12 && age <= 17){
                        averageAge += parseInt(age);
                        countScholarsByAge++;
                        if($(this).attr('data-attendance') == 1){
                            averageAgeAttendances += parseInt(age);
                            countAttendancesScholarsByAge ++;
                        }
                    }
                    else if(age >=18 && age <= 25){
                        averageAge += parseInt(age);
                        countStudentsByAge++;
                        if($(this).attr('data-attendance') == 1){
                            averageAgeAttendances += parseInt(age);
                            countAttendancesStudentsByAge ++;
                        }
                    }
                    else if (age > 25){
                        averageAge += parseInt(age);
                        countSaintsByAge++;
                        if($(this).attr('data-attendance') == 1){
                            averageAgeAttendances += parseInt(age);
                            countAttendancesSaintsByAge ++;
                        }
                    }

                    if($(this).attr('data-attendance') == 1){
                        countAttendancesByAge++;
                    }
                    countByAge++;
                }

                if($(this).attr('data-attendance') == 1){
                    countAttendances ++;

                    switch (category){
                        case 'BL': countAttendancesBelivers++; break;
                        case 'SN': countAttendancesSaints++; break;
                        case 'SC': countAttendancesScholars++; break;
                        case 'PS': countAttendancesPreScholars++; break;
                        case 'ST': countAttendancesStudents++; break;
                        case 'RB': countAttendancesRespBrothers++; break;
                        case 'FS': countAttendancesFullTimers++; break;
                        case 'FT': countAttendancesTrainees++; break;
                        case 'OT': countAttendancesOthers++; break;
                    }
                }

                if(!in_array(locality, localitiesByFilter)){
                    localitiesByFilter.push(locality);
                }

                switch (category){
                    case 'BL': countBelivers++; break;
                    case 'SN': countSaints++; break;
                    case 'SC': countScholars++; break;
                    case 'PS': countPreScholars++; break;
                    case 'ST': countStudents++; break;
                    case 'RB': countRespBrothers++; break;
                    case 'FS': countFullTimers++; break;
                    case 'FT': countTrainees++; break;
                    case 'OT': countOthers++; break;
                }
            }
        });

        $("#modalStatistic h5").text('');
        var statistic =
                (countPreScholars >0 ? "<tr><td>Дошкольники</td><td class='text-align'>"+countPreScholars+"</td><td class='text-align'>"+countAttendancesPreScholars+"</td></tr>" : "" )+
                ( countScholars >0 ? "<tr><td>Школьники</td><td class='text-align'>"+countScholars+"</td><td class='text-align'>"+countAttendancesScholars+"</td></tr>" : "" ) +
                ( countStudents >0 ? "<tr><td>Студенты</td><td class='text-align'>"+countStudents+"</td><td class='text-align'>"+countAttendancesStudents+"</td></tr>" : "" )+
                (countSaints >0 ? "<tr><td>Святые в церк. жизни</td><td class='text-align'>"+countSaints+"</td><td class='text-align'>"+countAttendancesSaints+"</td></tr>" : "")+
                ( countRespBrothers >0 ? "<tr><td>Ответственные братья</td><td class='text-align'>"+countRespBrothers+"</td><td class='text-align'>"+countAttendancesRespBrothers+"</td></tr>" : "" )+
                ( countFullTimers >0 ? "<tr><td>Полновременные служащие</td><td class='text-align'>"+countFullTimers+"</td><td class='text-align'>"+countAttendancesFullTimers+"</td></tr>" : "" )+
                ( countTrainees >0 ? "<tr><td >Полновременно обучающиеся</td><td class='text-align'>"+countTrainees+"</td><td class='text-align'>"+countAttendancesTrainees+"</td></tr>" : "" )+
                ( countBelivers >0 ? "<tr><td>Верующие</td><td class='text-align'>"+countBelivers+"</td><td class='text-align'>"+countAttendancesBelivers+"</td></tr>" : "" )+
                ( countOthers >0 ? "<tr><td>Другие</td><td class='text-align'>"+countOthers+"</td><td class='text-align'>"+countAttendancesOthers+"</td></tr>" : "" ) +
                "<tr><td><strong>Всего</strong></td><td class='text-align'><strong>" + countMembers + "</strong></td><td class='text-align'><strong>"+countAttendances+"</strong></td></tr>";

        var additionalStatistic =
            (countScholarsByAge >0 ? "<tr><td>12-17 лет</td><td class='text-align'>"+countScholarsByAge+"</td><td class='text-align'>"+
                countAttendancesScholarsByAge+"</td></tr>" : "")+
            ( countStudentsByAge >0 ? "<tr><td>18-25 лет</td><td class='text-align'>"+countStudentsByAge+"</td><td class='text-align'>"+countAttendancesStudentsByAge+"</td></tr>" : "" ) +
            ( countSaintsByAge >0 ? "<tr><td>26 лет и старше</td><td class='text-align'>"+countSaintsByAge+"</td><td class='text-align'>"+countAttendancesSaintsByAge+"</td></tr>" : "" )+
            "<tr><td><strong>Всего</strong></td><td class='text-align'><strong>" + (countScholarsByAge+countStudentsByAge+countSaintsByAge) + "</strong></td><td class='text-align'><strong>"+(countAttendancesScholarsByAge+countAttendancesStudentsByAge+countAttendancesSaintsByAge)+"</strong></td></tr>"+
            ( countScholarsByAge>0 || countStudentsByAge> 0 || countSaintsByAge >0 ? "<tr><td>Средний возраст</td><td class='text-align'>"+(
                parseInt(averageAge / (countScholarsByAge + countStudentsByAge + countSaintsByAge)))+"</td>"+
            "<td class='text-align'>"+ (
                parseInt(averageAgeAttendances / (countAttendancesScholarsByAge + countAttendancesStudentsByAge + countAttendancesSaintsByAge))) +"</td></tr>" : "" );

        if(memberAgeIsNullList.length == 0){
            var additionalTableTemplate = '<h3>Данные для статистики</h3>'+
                '<table class="table table-hover">'+
                  '<thead>'+
                    '<tr>'+
                      '<th>По возрастам</th>'+
                      '<th class="text-align">По списку</th>'+
                      '<th class="text-align">Посещают собрания</th>'+
                    '</tr>'+
                  '</thead>'+
                  '<tbody>'+ additionalStatistic + '</tbody>'+
                '</table>';
        }
        else{
            var additionalTableTemplate = '<h3>Данные для статистики (по возрастам) не сформированы, поскольку не указана дата рождения:</h3> <div>'+ memberAgeIsNullList.join(', ') + '</div>';
        }

        var tableTemplate = '<h3>Сводные данные</h3><table class="table table-hover">'+
              '<thead>'+
                '<tr>'+
                  '<th>По категориям</th>'+
                  '<th class="text-align">По списку</th>'+
                  '<th class="text-align">Посещают собрания</th>'+
                '</tr>'+
              '</thead>'+
              '<tbody>'+ statistic + '</tbody>'+
            '</table>';

        $("#modalStatistic").find(".modal-header h3").html("Статистика" +
            (filterLocality === 'Все местности' ? ' (' + localitiesByFilter.length + ')' : ' (' + filterLocality + ')'));
        $("#modalStatistic").find(".modal-body").html(tableTemplate + additionalTableTemplate);
        //$("#modalStatistic").find(".modal-footer").html("<div style='float:left;'><strong>Количество местностей — "+localitiesByFilter.length+"</strong></div>");
        $("#modalStatistic").modal('show');
    });

    $(".add-member").click(function(){
        var adminLocality = $(this).attr('data-locality');

        $.getJSON('/ajax/get.php?get_member_localities_Not_Reg_Tbl').done(function(data){
            fillEditMember ('', {need_passport : "1", need_tp : "1", locality_key : adminLocality}, data.localities);
            $('#modalEditMember #btnDoSaveMember').addClass('create');
            $('#modalEditMember').modal('show');
        });
    });

    function removeMember(memberId){
        $.post('/ajax/members.php?remove', {
            memberId : memberId,
            sortedFields : sortedFields()
        })
        .done(function(data){
            refreshMembers(data.members);
        });
    }

    function downloadMembersListExel(members, checkedFields){
        var doc = '&document=', filteredMembers = filterMembers(), membersArr = [];

        if (checkedFields){
            doc += checkedFields;
        }

        for(var i in members){
            var member = members[i];
            if(in_array(member.id, filteredMembers)){
                membersArr.push(member);
            }
        }

        var  req = "&memberslength="+membersArr.length+"&adminId="+window.adminId+"&page=members";

        $.ajax({
            type: "POST",
            url: "/ajax/excelList.php",
            data: "members="+JSON.stringify(membersArr)+req+doc,
            cache: false,
            success: function(data) {
                location.href="./ajax/excelList.php?file="+data;
                setTimeout(function(){
                    deleteFile(data);
                }, 10000);
            }
        });
    }

    $("#remove-member").click(function (event) {
        event.stopPropagation();
        var reason = $('.removeMemberReason').val();

        if(reason.trim() === '') {
            return;
        }
        var searchText = $('.search-text').val();

        handleMember(window.removeMemberId, 0, reason, searchText);

        $('#removeMemberFromList').modal('hide');
    });

    function handleMember(member, active, reason, searchText) {
        $.getJSON('/ajax/members.php', {
            member: member,
            active: active,
            reason : reason.trim(),
            searchText : searchText,
            sortedFields : sortedFields()
        })
            .done (function(data) {
                window.removeMemberId = '';
                $('.removeMemberReason').val('');
                refreshMembers (data.members);
            });
    }

    function saveMember (){
        if ($("#btnDoSaveMember").hasClass ("disable-on-invalid") && $(".emLocality").val () == "_none_" && $(".emNewLocality").val().trim().length==0)
        {
            showError("Необходимо выбрать населенный пункт из списка или если его нет, то указать его название");
            $(".localityControlGroup").addClass ("error");
            window.setTimeout(function() { $(".localityControlGroup").removeClass ("error"); }, 2000);
            return;
        }

        var el = $('#modalEditMember'), data = getValuesRegformFields(el);

        if(!data.name || !data.gender || !data.citizenship_key || !data.category_key){
            showError("Необходимо заполнить все поля выделенные розовым цветом.");
            return;
        }

        $.post("/ajax/members.php?update_member="+window.currentEditMemberId+($("#btnDoSaveMember").hasClass('create') ? "&create=true" : ""), data)
        .done (function(data) {
            refreshMembers(data.members);
            $('#modalEditMember').modal('hide');
        });
    }

    $(document).ready (function (){
        loadDashboard ();
    });

    $("a[id|='sort']").click (function (){
        var id = $(this).attr("id");
        var icon = $(this).siblings("i");

        $(($(document).width()>768 ? ".desctopVisible" : ".show-phone") + " a[id|='sort'][id!='"+id+"'] ~ i").attr("class","icon-none");
        icon.attr ("class", icon.hasClass("icon-chevron-down") ? "icon-chevron-up" : "icon-chevron-down");
        loadDashboard ();
    });

    $("#selMemberLocality").change (function (){
        setCookie('selMemberLocality', $(this).val());
        filterMembers();
    });

    $("#selMemberAttendMeeting").change(function(){
			setCookie('selAttendMeeting', $(this).val());
			filterMembers();
		});

    $("#selMemberCategory").change (function (){
        setCookie('selMemberCategory', $(this).val());
        filterMembers();
    });

    function filterMembers(){
        var isTabletMode = $(document).width()<786,
            localityFilter = $("#selMemberLocality").val(),
            categoryFilter = $("#selMemberCategory").val(),
            attendMeetingFilter = $("#selMemberAttendMeeting").val(),
            text = $('.search-text').val().trim().toLowerCase(),
            filteredMembers = [],
            localityList = [];

        if(localityFilter){
            localityList = localityFilter.split(',');
        }

        $(".members-list " + ( isTabletMode ? " #membersPhone " : " #members" ) + " tbody tr").each(function(){
            var memberLocality = $(this).attr('data-locality'),
                memberCategory = $(this).attr('data-category'),
                attendMeeting = $(this).attr('data-attendance'),
                memberName = $(this).find('td').first().text().toLowerCase(),
                memberKey = $(this).attr('data-id');


            if(((localityFilter === '_all_' || localityFilter === undefined) && categoryFilter === '_all_' && text === '' && attendMeetingFilter === '_all_') ||

                (
                    (in_array(memberLocality, localityList) || localityFilter === '_all_' || (localityFilter === undefined && localityList.length === 0))  &&
                    (memberCategory === categoryFilter || categoryFilter === '_all_') && (attendMeeting === attendMeetingFilter || attendMeetingFilter === '_all_') ) && (memberName.search(text) !== -1))
                {

                $(this).show();
                filteredMembers.push(memberKey);
            }
            else{
                $(this).hide();
            }
        });

        return filteredMembers;
    }

    $("#btnDoSaveMember").click (function (){
      var el = $('#modalEditMember');
      if ((el.find(".emGender").val () == "_none_" || el.find(".emName").val().trim().length==0) || el.find(".emCitizenship").val () == "_none_" || el.find(".emCategory").val () == "_none_" || (el.find(".emLocality").val () == "_none_" && el.find(".emNewLocality").val () == "")) {
        showError("Необходимо заполнить все поля выделеные розовым цветом");
        $(".localityControlGroup").addClass ("error");
        window.setTimeout(function() { $(".localityControlGroup").removeClass ("error"); }, 2000);
        return;
      }
      /*if (el.find(".emBirthdate").val().trim().length==0) {
        showError("Необходимо заполнить дату рождения");
        $(".localityControlGroup").addClass ("error");
        window.setTimeout(function() { $(".localityControlGroup").removeClass ("error"); }, 2000);
        return;
      }*/
        if (!$(this).hasClass('disabled')){
            saveMember();
        }
        else{
            showError("Необходимо заполнить все обязательные поля, выделенные розовым фоном!", true);
        }
    });

    $('.search-text').bind("paste keyup", function(event){
        event.stopPropagation();
        filterMembers();
    });

    $(".clear-search-members").click(function(){
       $(this).siblings('input').val('');
       filterMembers();
    });

    $('.emName ~ .unblock-input').click(function (){
        $('#modalNameEdit').modal('show');
    });

    $('#btnDoNameEdit').click (function (){
        $ ('.emName ~ .unblock-input').hide ();
        $ (".emName").removeAttr ("disabled");
        setTimeout(function() {$(".emName").focus();}, 1000);
    });
</script>
<script src="/js/members.js?v1"></script>
<?php
include_once "footer.php";
?>
