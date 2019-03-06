<?php
include_once "header.php";
include_once "nav.php";
include_once "modals.php";

$sort_field = isset ($_SESSION['sort_field']) ? $_SESSION['sort_field'] : 'name';
$sort_type = isset ($_SESSION['sort_type']) ? $_SESSION['sort_type'] : 'asc';
$listCountry = isset($_COOKIE['selCountry']) ? $_COOKIE['selCountry'] : null;

$countries = db_getCountriesList();
?>
    <div class="container">
        <?php
        $textBlock = db_getTextBlock('admin_list');
        if ($textBlock) echo "<div class='alert hide-phone'>$textBlock</div>";
        ?>
        <div id="eventTabs" class="admins-list">
            <div class="tab-content">
              <select class="controls span4 members-lists-combo" tooltip="Выберите нужный вам список здесь">
                  <option value="members">Общий список</option>
                  <option value="youth">Молодые люди</option>
                  <option selected value="list">Ответственные за регистрацию</option>
              </select>
                <div class="btn-toolbar">
                    <select class="span3" id="selCountry">
                        <?php
                        if($listCountry){
                            echo "<option value='_all_' ". ($listCountry=='_all_' ? 'selected' : '') .">Все страны</option>";
                            foreach ($countries as $id => $name) {
                                echo "<option value='$id' ". ($id==$listCountry ? 'selected' : '') .">".htmlspecialchars($name)."</option>";
                            }
                        }
                        else{
                            echo "<option value='_all_' selected>Все страны</option>";
                            foreach ($countries as $id => $name) {
                                echo "<option value='$id'>".htmlspecialchars($name)."</option>";
                            }
                        }
                        ?>
                    </select>

                    <select class="span3" id="selRegion"></select>
                    <select class="span3" id="selLocality"></select>
                    <a type="button" class="btn btn-default search"><i class="icon-search" title="Поле поиска"></i></a>
                    <div class="not-display" data-toggle="1">
                        <input type="text"  class="controls search-text" placeholder="Введите текст">
                        <i class="icon-remove admin-list clear-search"></i>
                    </div>
                </div>
                <div class="desctopVisible">
                    <table id="members" class="table table-hover">
                        <thead>
                        <tr>
                            <th><a id="sort-name" href="#" title="сортировать">Ф.И.О.</a>&nbsp;<i class="<?php echo $sort_field=='name' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
                            <th>Телефон</th>
                            <th>Email</th>
                            <?php
                            if (!db_isSingleCityAdmin($memberId))
                                echo '<th><a id="sort-note" href="#" title="сортировать">Область регистрации</a>&nbsp;<i class="'.($sort_field=='note' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none').'"></i></th>';
                            ?>
                        </tr>
                        </thead>
                        <tbody><tr><td colspan="8"><h3 style="text-align: center">Загрузка...</h3></td></tr></tbody>
                    </table>
                </div>
                <div class="show-phone">
                    <table id="membersPhone" class="table table-hover">
                        <thead>
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <span class="sortName"><?php echo $s = isset($_COOKIE['sort'])? $_COOKIE['sort'] : 'Сортировать' ?></span>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" id="dropdownMenu2" aria-labelledby="dropdownMenu">
                                <li><a id="sort-name" data-sort="ФИО" href="#" title="сортировать">ФИО</a>&nbsp;<i class="<?php echo $sort_field=='name' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></li>
                                <li>
                                    <?php
                                    if (!db_isSingleCityAdmin($memberId)){
                                        echo '<a id="sort-note" data-sort="Область регистрации" href="#" title="сортировать">Область регистрации</a>&nbsp;<i class="'.($sort_field=='note' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none').'"></i>';
                                    }
                                    ?>
                                </li>
                            </ul>
                        </div>
                        </thead>
                        <tbody><tr><td colspan="8"><h3>Загрузка...</h3></td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.search-text').bind("paste keyup", function(event){
            event.stopPropagation();

            var text = $('.search-text').val();
            if(text.length>=3 || text.length==0){
                filterAdmins();
            }
        });

        function loadDashboard (){
            var sort_type = 'desc',
                sort_field = 'name',
                el = $(($(document).width()>768 ? ".desctopVisible" : ".show-phone" ) + " a[id|='sort']"),
                text = $('.search-text').val();

            el.each (function (i){
                if ($(this).siblings("i.icon-chevron-down").length)
                {
                    sort_type = 'asc';
                    sort_field = $(this).attr("id").replace(/^sort-/,'');
                }
                else if ($(this).siblings("i.icon-chevron-up").length)
                {
                    sort_type = 'desc';
                    sort_field = $(this).attr("id").replace(/^sort-/,'');
                }
            });
            $.getJSON('./ajax/list.php', {sort_type: sort_type, sort_field: sort_field})
                .done (function(data) {
                    buildLocalityList (data.localities);
                    buildRegionsList (data.regions);
                    buildAdminsList (data.admins);
                    filterRegions();
                });
        }

        function buildAdminsList (admins){
            var tableRows = [], phoneRows=[];
            for (var i in admins)
            {
                var m = admins[i];
                var note = he(m.note);

                tableRows.push('<tr data-id="'+m.id+'" data-countries="'+m.countries+'" data-regions="'+m.regions+'" data-localities="'+m.localities+'" class="'+(m.active==0?'inactive-member':'member-row')+'">'+
                    '<td><span style="color: #006" class="admins-name">' + he(m.name) + '</span><p style="color:grey; font-size:10px; padding-bottom:0; margin-bottom:0;">' + he(m.locality_name) + '</p></td>' +
                    '<td>' + he(m.cell_phone) + '</td>' +
                    '<td>' + he(m.email) + '</td>' +
                    <?php if (!db_isSingleCityAdmin($memberId)) echo"'<td>' + (note.length > 50 ? note.substr(0, 50) + '...' : note) + '</td>' +"; ?>
                    '</tr>'
                );

                phoneRows.push('<tr data-id="'+m.id+'" data-countries="'+m.countries+'" data-regions="'+m.regions+'" data-localities="'+m.localities+'" class="'+(m.active==0?'inactive-member':'member-row')+'">'+
                    '<td><span style="color: #006" class="admins-name">' + he(m.name) + '</span><p style="color:grey; font-size:10px; padding-bottom:0; margin-bottom:0;">' + he(m.locality_name) + '</p>' +
                    '<div style="font-size:12px;"><span >'+ (m.cell_phone?'тел.: ':'') + he(m.cell_phone) + '</span>'+ (m.cell_phone && m.email ? ', ' :' ' )+'<span>'+ (m.email?'email: ':'') + he(m.email) + '</span></div>' +
                    <?php if (!db_isSingleCityAdmin($memberId)) echo"'<td>' + (note.length > 50 ? note.substr(0, 50) + '...' : note) + '</td>' +"; ?>
                    '</tr>'
                );
            }

            $(".desctopVisible tbody").html (tableRows.join(''));
            $(".show-phone tbody").html (phoneRows.join(''));

        }

        $("a[id|='sort']").click (function (){
            var id = $(this).attr("id");
            var icon = $(this).siblings("i");

            $( ($(document).width()>768 ? ".desctopVisible" : ".show-phone" ) + " a[id|='sort'][id!='"+id+"'] ~ i").attr("class","icon-none");
            icon.attr ("class", icon.hasClass("icon-chevron-down") ? "icon-chevron-up" : "icon-chevron-down");
            loadDashboard ();
        });

        function buildRegionsList (regions){
            var selectRegions = [], el = $("#selRegion"), selRegionCookie = getCookie("selRegion");

            regions.length===0 ? el.attr({"disabled":"disabled"}) : el.removeAttr("disabled");

            for (var i in regions) {
                var m = regions[i];
                selectRegions.push(('<option value="'+he(m.key)+'" '+((he(m.key)===selRegionCookie)?(' selected'):(' '))+'>'+he(m.name)+'</option>'));
            }

            selectRegions.unshift('<option value="_all_" '+((selRegionCookie==="_all_")?(" selected"):(" "))+'>Все области</option>');

            el.html(selectRegions.join(''));
        }

        function buildLocalityList (locality){
            var selectLocality = [], el = $("#selLocality");

            var selLocalityCookie = getCookie("selLocality");

            locality.length===0 ? el.attr({"disabled":"disabled"}) : el.removeAttr("disabled");

            for (var i in locality) {
                var m = locality[i];
                selectLocality.push(('<option value="'+he(m.locality_key)+'" '+((he(m.locality_key.substr(-6))===selLocalityCookie)?(' selected'):(' '))+'>'+he(m.name)+'</option>'));
            }

            selectLocality.unshift('<option value="_all_" '+((selLocalityCookie==="_all_")?(" selected"):(" "))+'>Все местности</option>');

            el.html(selectLocality.join(''));
        }

        $(document).ready (function (){
            loadDashboard ();
        });

        $("#selRegion").change (function (){
            $("#selLocality").val('_all_');
            filterRegions();
        });

        $("#selLocality").change (function (){
            filterLocalities();
        });

        $("#selCountry").change (function (){
            $("#selRegion").val('_all_');
            $("#selLocality").val('_all_')
            filterRegions();
        });

        function filterRegions(){
            var country = $('#selCountry').val(), countRegions = 0;

            $('#selRegion option').each(function(){
                if($(this).val().substr(0,2) == country || country == '_all_' || $(this).val() === '_all_'){
                    countRegions ++;
                    $(this).show();
                }
                else{
                    $(this).hide();
                }
            });
            countRegions > 1 ? $('#selRegion').removeAttr('disabled') :  $('#selRegion').attr('disabled', 'disabled');
            filterLocalities(countRegions);
        }

        function filterLocalities(countRegions=false){
            var region = $('#selRegion').val(), countLocalities = 0, country = $("#selCountry").val();

            $('#selLocality option').each(function(){
                if(($(this).val().substr(3,4) == region) || $(this).val() === '_all_' || ( region == '_all_' && country == '_all_' ) || ( region == '_all_' && country !== '_all_' && countRegions !== 0 && $(this).val().substr(0,2) == country) ){
                    countLocalities ++;
                    $(this).show();
                }
                else{
                    $(this).hide();
                }
            });

            countLocalities > 0 ? $('#selLocality').removeAttr('disabled') : $('#selLocality').attr('disabled', 'disabled');
            filterAdmins();
        }

        function filterAdmins(){
            var regionFilter = $("#selRegion").val(), localityFilter = $("#selLocality").val().substr(-6),
                countryFilter = $("#selCountry").val(), adminsRegions, adminsLocalities, adminsCountries, adminsName, searchText = $('.search-text').val().toLowerCase();

            setCookie("selRegion", regionFilter);
            setCookie("selLocality", localityFilter);
            setCookie("selCountry", countryFilter);

            $('.admins-list '+($(document).width() < 768 ? '.show-phone' : '.desctopVisible') + ' tbody tr').each(function(){
                adminsRegions = $(this).attr('data-regions') !== 'null' ? $(this).attr('data-regions').split(',') : 'null';
                adminsLocalities = $(this).attr('data-localities') !== 'null' ? $(this).attr('data-localities').split(',') : 'null';
                adminsCountries = $(this).attr('data-countries') !== 'null' ? $(this).attr('data-countries').split(',') : 'null';
                adminsName = $(this).find('.admins-name').text().toLowerCase();

                var value = '', regionsCountries = [];
                if(adminsRegions !== 'null'){
                    adminsRegions.forEach(function(item){
                        value = item.substr(0,2);
                        if(!in_array(value, regionsCountries))
                            regionsCountries.push(value);
                    });
                }

                ((searchText !== '' && searchText.length >=3 && adminsName.search(searchText) !== -1) || (searchText === '' && searchText.length < 3)) && ( in_array(regionFilter, adminsRegions) || in_array(localityFilter, adminsLocalities) || in_array(countryFilter, adminsCountries) || (regionFilter === '_all_' && countryFilter === '_all_' && localityFilter === '_all_') || (regionFilter === '_all_' && countryFilter !== '_all_' && localityFilter === '_all_' && (in_array(countryFilter, regionsCountries)))) ? $(this).show() : $(this).hide();
            });
        }
    </script>

<?php
include_once "footer.php";
?>
