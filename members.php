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
            <div class="btn-toolbar">
            <div class="btn-group">
                <a class="btn btn-success add-member" data-locality="<?php echo $adminLocality; ?>" type="button"><i class="fa fa-plus icon-white"></i> <span class="hide-name">Добавить</span></a>
            </div>
            <?php if (!$singleCity) { ?>
            <div class="btn-group">
                <select id="selMemberLocality" class="span3">
                    <option value='_all_' <?php echo $selMemberLocality =='_all_' ? 'selected' : '' ?> >Все местности</option>
                    <?php
                        foreach ($localities as $id => $name) {
                            echo "<option value='$id' ". ($id==$selMemberLocality ? 'selected' : '') ." >".htmlspecialchars ($name)."</option>";
                        }
                    ?>
                </select>
            </div>
            <?php } ?>
            <div class="btn-group">
                <select id="selMemberCategory" class="span3">
                    <option value='_all_' selected <?php echo $selMemberCategory =='_all_' ? 'selected' : '' ?> >Все категории</option>
                    <?php foreach ($categories as $id => $name) {
                        echo "<option value='$id' ". ($id==$selMemberCategory ? 'selected' : '') .">".htmlspecialchars ($name)."</option>";
                    } ?>
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

<script>                   

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
            
            tableRows.push('<tr data-id="'+m.id+'" data-locality="'+m.locality_key+'" data-category="'+m.category_key+'" class="'+(m.active==0?'inactive-member':'member-row')+'">'+
                '<td>' + he(m.name) + '</td>' +
                <?php if (!$singleCity) echo "'<td>' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') + '</td>' +"; ?>
                '<td>' + he(m.cell_phone) + '</td>' +
                '<td>' + he(m.email) + '</td>' +
                '<td>' + age + '</td>' +
                '<td><input type="checkbox" class="check-meeting-attend" '+ (m.attend_meeting == 1 ? "checked" : "") +' /></td>' +
                '<td>' + htmlChanged + htmlEditor + '</td>' +
                '<td><i class="'+(m.active==0?'icon-circle-arrow-up':'icon-trash')+' icon-black" title="'+(m.active==0?'Добавить в список':'Удалить из списка')+'"/></td>' +
                '</tr>'
            );

            phoneRows.push('<tr data-id="'+m.id+'" data-locality="'+m.locality_key+'" data-category="'+m.category_key+'" class="'+(m.active==0?'inactive-member':'member-row')+'">'+
                '<td><span style="color: #006">' + he(m.name) + '</span>'+
                '<i style="float: right; cursor:pointer;" class="'+(m.active==0?'icon-circle-arrow-up':'icon-trash')+' icon-black" title="'+(m.active==0 ? 'Добавить в список':'Удалить из списка')+'"/>'+
                <?php if (!$singleCity) echo "'<div>' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') + '</div>' +"; ?>
                '<div><span >'+ /*(m.cell_phone?'тел.: ':'') + */ he(m.cell_phone.trim()) + '</span>'+ (m.cell_phone && m.email ? ', ' :'' )+'<span>'+ /*(m.email?'email: ':'') + */ he(m.email) + '</span></div>' +
                '<div>'+ age +'</div>'+
                '<div>Посещает собрание: <input type="checkbox" class="check-meeting-attend" '+ (m.attend_meeting == 1 ? "checked" : "") +' /></div>'+
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
            countRespBrothers = 0, countFullTimers = 0, countTrainees = 0, countOthers = 0;
            
        $(".members-list " + ( isTabletMode ? " #membersPhone " : " #members " ) + " tbody tr").each(function(){
            if($(this).css('display') !== 'none'){
                countMembers ++;
                
                var locality = $(this).attr('data-locality'), category = $(this).attr('data-category');
                
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
                ( countPreScholars >0 ? "<div>Дошкольники — "+countPreScholars+"</div>" : "" )+
                ( countScholars >0 ? "<div>Школьники — "+countScholars+"</div>" : "" ) +
                ( countStudents >0 ? "<div>Студенты — "+countStudents+"</div>" : "" )+                                        
                ( countSaints >0 ? "<div>Святые в церк. жизни — "+countSaints+"</div>" : "" )+       
                ( countRespBrothers >0 ? "<div>Ответственные братья — "+countRespBrothers+"</div>" : "" )+       
                ( countFullTimers >0 ? "<div>Полновременные служащие — "+countFullTimers+"</div>" : "" )+       
                ( countTrainees >0 ? "<div>Полновременно обучающиеся — "+countTrainees+"</div>" : "" )+       
                ( countBelivers >0 ? "<div>Верующие — "+countBelivers+"</div>" : "" )+
                ( countOthers >0 ? "<div>Другие — "+countOthers+"</div>" : "" ) +
                "<div>Всего человек в списке — "+countMembers+"</div>";

        $("#modalStatistic").find(".modal-header h3").html("Статистика" + (filterLocality === 'Все местности' ? "" : ' <span style="font-size:16px;">(' + filterLocality + ')</span> '));
        $("#modalStatistic").find(".modal-body").html(statistic);
        $("#modalStatistic").find(".modal-footer").html("<div style='float:left;'><strong>Количество местностей — "+localitiesByFilter.length+"</strong></div>");
        $("#modalStatistic").modal('show');
    });
        
    $(".add-member").click(function(){
        var adminLocality = $(this).attr('data-locality');
        
        $.getJSON('/ajax/get.php?get_member_localities').done(function(data){
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

    $("#selMemberCategory").change (function (){
        setCookie('selMemberCategory', $(this).val());
        filterMembers();
    });
    
    function filterMembers(){
        var isTabletMode = $(document).width()<786, 
            localityFilter = $("#selMemberLocality").val(),
            categoryFilter = $("#selMemberCategory").val(),
            text = $('.search-text').val().trim().toLowerCase(),
            filteredMembers = [];
        
        $(".members-list " + ( isTabletMode ? " #membersPhone " : " #members" ) + " tbody tr").each(function(){
            var memberLocality = $(this).attr('data-locality'), 
                memberCategory = $(this).attr('data-category'),
                memberName = $(this).find('td').first().text().toLowerCase(),
                memberKey = $(this).attr('data-id');                       
            
            if(((localityFilter === '_all_' || localityFilter === undefined) && categoryFilter === '_all_' && text === '') || (   
                (memberLocality === localityFilter || localityFilter === '_all_' || localityFilter === undefined) && 
                (memberCategory === categoryFilter || categoryFilter === '_all_') && (memberName.search(text) !== -1))){
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

<?php
include_once "footer.php"; 
?>