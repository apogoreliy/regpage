<?php
include_once "header.php";
include_once "nav.php";
//Roman's code ver 5.0.1
$user_settings = db_getUserSettings($memberId);
$userSettings = implode (',', $user_settings);

$sort_field = isset ($_COOKIE['sort_field_youth']) ? $_COOKIE['sort_field_youth'] : 'name';
$sort_type = isset ($_COOKIE['sort_type_youth']) ? $_COOKIE['sort_type_youth'] : 'asc';
$listCountry = isset($_COOKIE['selCountry']) ? $_COOKIE['selCountry'] : null;

$allLocalities = db_getLocalities();
$adminLocality = db_getAdminLocality($memberId);
$localities = db_getAdminLocalities ($memberId);
$categories = db_getCategories();
$countries1 = db_getCountries(true);
$countries2 = db_getCountries(false);
$singleCity = db_isSingleCityAdmin($memberId);
$noEvent = true;

$selMemberLocality = isset ($_COOKIE['selMemberLocality']) ? $_COOKIE['selMemberLocality'] : '_all_';
$selMemberCategory = isset ($_COOKIE['selMemberCategory']) ? $_COOKIE['selMemberCategory'] : '_all_';
$selMemberCategory = isset ($_COOKIE['selMemberAge']) ? $_COOKIE['selMemberAge'] : '_all_';

$collegeEndYears = UTILS::getCollegeEndYears();

include_once "modals.php";

?>

<div class="container">
	<div id="eventTabs" class="members-list">
		<div class="tab-content">
			<select class="controls span4 members-lists-combo" tooltip="Выберите нужный вам список здесь">
          <option value="members">Общий список</option>
          <option selected value="youth">Молодые люди</option>
          <option value="list">Ответственные за регистрацию</option>
      </select>
			<div class="btn-toolbar">
				<div class="btn-group">
					<a class="btn btn-success add-member" data-locality="<?php echo $adminLocality; ?>" type="button"><i class="fa fa-plus icon-white"></i> <span class="hide-name">Добавить</span></a>
				</div>
				<div class="btn-group">
					<a class="btn dropdown-toggle btnDownloadMembers" data-toggle="dropdown" href="#" title="Скачать список">
						<i class="fa fa-download"></i> Скачать<span class="hide-name"></span>
					</a>
				</div>
				<div class="btn-group">
					<a class="btn dropdown-toggle btnShowStatistic" data-toggle="dropdown" href="#" title="Статистика">
						<i class="fa fa-bar-chart"></i> Статистика<span class="hide-name"></span>
					</a>
				</div>
				<div class="btn-group">
					<a class="btn openCollegesModal" href="#">
						<i class="fa fa-university"></i> <span class="hide-name">Учебные заведения</span>
					</a>
				</div>
				<div class="btn-group">
					<a type="button" class="btn btn-default search"><i class="icon-search" title="Поле поиска"></i></a>
					<span class="not-display" data-toggle="1">
						<input type="text"  class="controls search-text" placeholder="Введите текст">
						<i class="icon-remove admin-list clear-search"></i>
					</span>
				</div>
				<div style="margin-top: 10px;">
				<?php if (!$singleCity) { ?>
				<div class="btn-group">
					<select id="selMemberLocality" class="span2">
						<option value='_all_' <?php echo $selMemberLocality =='_all_' ? 'selected' : '' ?> >Все местности</option>
						<?php foreach (db_getAdminLocalitiesNotRegTbl($memberId) as $id => $name) {
								echo "<option value='$id' ". ($id==$selMemberLocality ? 'selected' : '') ." >".htmlspecialchars ($name)."</option>";
						} ?>
					</select>
				</div>
				<?php } ?>
				<div class="btn-group">
					<select id="selMemberCategory" class="span2">
						<option value='_all_' >Все категории</option>
						<option value='SC' >Школьники</option>
						<option value='ST' >Студенты</option>
						<option value='SN' >Святые в церк. жизни</option>
						<option value='BL' >Верующие</option>
					</select>
				</div>
				<div class="btn-group">
					<select id="selMemberAge" class="span2">
						<option value='_all_'>Все возрасты</option>
						<option value='10' >10 и старше</option>
						<option value='11' >11 и старше</option>
						<option value='12' >12 и старше</option>
						<option value='13' >13 и старше</option>
						<option value='14' >14 и старше</option>
						<option value='15' selected>15 и старше</option>
						<option value='16' >16 и старше</option>
						<option value='17' >17 и старше</option>
						<option value='18' >18 и старше</option>
						<option value='19' >19 и старше</option>
						<option value='20' >20 и старше</option>
						<option value='21' >21 и старше</option>
						<option value='22' >22 и старше</option>
						<option value='23' >23 и старше</option>
					</select>
				</div>
				<div class="btn-group">
					<select id="selMemberCollegeEnd" class="span2">
						<option value='_all_' selected>Все года (вуз)</option>
						<?php
							foreach ($collegeEndYears as $key) {
								echo "<option value='$key'>$key</option>";
							}
						?>
					</select>
				</div>
				<div class="btn-group">
					<select id="selMemberSchoolEnd" class="span2">
						<option value='_all_'>Все года (школа)</option>
						<?php
							foreach ($collegeEndYears as $key) {
								echo "<option value='$key'>$key</option>";
							}
						?>
					</select>
				</div>
				<div class="btn-group">
					<select id="selMemberAttendMeeting" class="span2">
						<option value='_all_' >Все участники</option>
						<option value='1' >Посещают собрания</option>
						<option value='0' >Не посещают собрания</option>
					</select>
				</div>
			</div>
			</div>
			<div class="desctopVisible">
				<table id="members" class="table table-hover">
					<thead>
					<tr>
						<th><a id="sort-name" href="#" title="сортировать">Ф.И.О.</a>&nbsp;
							<i class="<?php echo $sort_field=='name' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i>
						</th>
						<th><a id="sort-locality" href="#" title="сортировать">Город</a>&nbsp;
							<i class="<?php echo $sort_field=='locality' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i>
						</th>
						<th><a id="sort-cellphone" href="#" title="сортировать">Телефон</a>&nbsp;
							<i class="<?php echo $sort_field=='college_locality' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?> "></i>
						</th>
						<th><a id="sort-college" href="#" title="сортировать">Учебное заведение</a>&nbsp;
							<i class="<?php echo $sort_field=='college' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?> "></i>
						</th>
						<th><a id="sort-age" href="#" title="сортировать">Возраст</a>&nbsp;
							<i class="<?php echo $sort_field=='age' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i>
						</th>
						<th><a id="sort-attend_meeting" href="#" title="Посещает собрания">С</a>&nbsp;<i class="<?php echo $sort_field=='attend_meeting' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i></th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
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
		<button class="btn btn-info disable-on-invalid" id="btnDoSaveMember">Сохранить</button>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Отменить</button>
	</div>
</div>
<script>
	$(document).ready(function(){
		//Roman's code ver 5.0.1
		window.user_settings = "<?php echo $userSettings; ?>".split(',');

		loadYouthList();
		setAdminRole_0('.add-member','#btnDoSaveMember');
		$(".clear-college").click(function(e){
			e.stopPropagation();
			$(".emCollege").val('');
			$(".emCollege").attr('data-college', '');
		});

		$("#btnDoSaveMember").click (function (){
			if (!$(this).hasClass('disabled')){
				saveMember();
			}
			else{
				showError("Необходимо заполнить все обязательные поля, выделенные розовым фоном!", true);
			}
		});

		$("#selMemberLocality").change (function (){
			setCookie('selMemberLocality', $(this).val());
			filterMembers();
		});

		$("#selMemberCategory").change (function (){
			setCookie('selMemberCategory', $(this).val());
			filterMembers();
		});

		$("#selMemberAge").change (function (){
			setCookie('selMemberAge', $(this).val());
			filterMembers();
		});

		$("#selMemberCollegeEnd").change(function(){
			setCookie('selCollegeEnd', $(this).val());
			filterMembers();
		});

		$("#selMemberAttendMeeting").change(function(){
			setCookie('selAttendMeeting', $(this).val());
			filterMembers();
		});

		$("#selMemberSchoolEnd").change(function(){
			setCookie('selSchoolEnd', $(this).val());
			filterMembers();
		});

		$(".add-member").click(function(){
			var adminLocality = $(this).attr('data-locality');

			$.getJSON('/ajax/get.php?get_member_localities_Not_Reg_Tbl').done(function(data){
				fillEditMember ('', {need_passport : "1", need_tp : "1", locality_key : adminLocality}, data.localities);
				$('#modalEditMember #btnDoSaveMember').addClass('create');
				$('#modalEditMember').modal('show');
			});
		});

		$(".btnDownloadMembers").click(function(event){
			event.stopPropagation();
			$('#modalDownloadMembers').modal('show').find("input[type='checkbox']").each(function(){
				$(this).prop('checked', true);
			});
		});

		$(".btnShowStatistic").click(function(e){
			e.stopPropagation();
			var isTabletMode = $(document).width()<786,
				filterLocality = $('#selMemberLocality option:selected').text(),
				filterAge = $('#selMemberAge').val(),
				localitiesByFilter = [], countMembers = 0, countScholars = 0, countStudents = 0;

			$(".members-list " + ( isTabletMode ? " #membersPhone " : " #members " ) + " tbody tr").each(function(){
				if($(this).css('display') !== 'none'){
					countMembers ++;

					var locality = $(this).attr('data-locality'), category = $(this).attr('data-category');

					if(!in_array(locality, localitiesByFilter)){
						localitiesByFilter.push(locality);
					}

					switch (category){
						case 'SC': countScholars++; break;
						case 'ST': countStudents++; break;
					}
				}
			});

			$("#modalStatistic h5").text('');
			var statistic =
					( countScholars >0 ? "<div>Школьники — "+countScholars+"</div>" : "" ) +
					( countStudents >0 ? "<div>Студенты — "+countStudents+"</div>" : "" )+
					"<div>Всего человек в списке — "+countMembers+"</div>";

			$("#modalStatistic").find(".modal-header h3").html("Статистика" + (filterLocality === 'Все местности' ? "" : ' <span style="font-size:16px;">(' + filterLocality + ')</span> '));
			$("#modalStatistic").find(".modal-body").html(statistic);
			$("#modalStatistic").find(".modal-footer").html("<div style='float:left;'><strong>Количество местностей — "+localitiesByFilter.length+"</strong></div><div>Статистика выводится для возраста "+filterAge+" лет и старше</div>");
			$("#modalStatistic").modal('show');
		});

		$("#remove-member").click(function (event) {
			event.stopPropagation();
			var reason = $('.removeMemberReason').val();

			if(reason.trim() === '') {
				showError('Необходимо указать причину удаления.');
				return;
			}
			var searchText = $('.search-text').val();

			handleMember(window.removeMemberId, 0, reason, searchText);

			$('#removeMemberFromList').modal('hide');
		});

		$('.search-text').keyup(function(){
			filterMembers();
		});

		$("a[id|='sort']").click (function (){
	        var id = $(this).attr("id"), icon = $(this).siblings("i");

	        $(($(document).width()>768 ? ".desctopVisible" : ".show-phone") + " a[id|='sort'][id!='"+id+"'] ~ i").attr("class","icon-none");
	        icon.attr ("class", icon.hasClass("icon-chevron-down") ? "icon-chevron-up" : "icon-chevron-down");

			setCookie('sort_field_youth', id.replace(/^sort-/,''));
			setCookie('sort_type_youth', icon.hasClass("icon-chevron-down") ? "asc" : "desc");

	        loadYouthList();
	    });
	});

	function removeMember(memberId){
		$.post('/ajax/youth.php?remove', {
			memberId : memberId,
			sortedFields : sortedFields()
		})
		.done(function(data){
			refreshYouthList(data.members);
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

	function handleMember(member, active, reason, searchText) {
		$.getJSON('/ajax/youth.php', {
			member: member,
			active: active,
			reason : reason.trim(),
			searchText : searchText,
			sortedFields : sortedFields()
		})
			.done (function(data) {
				window.removeMemberId = '';
				$('.removeMemberReason').val('');
				refreshYouthList (data.members);
			});
	}

	function loadYouthList(){
		$.getJSON('/ajax/youth.php?get')
		.done(function(data){
			refreshYouthList(data.members);
		});
	}

	function refreshYouthList(members){
		var tableRows = [], phoneRows = [];

		for (var i in members){
			var m = members[i];
			//console.log(m);
			// *** last editor
			var notMe = (m.admin_key && m.admin_key!=window.adminId);
			// if the author is same for reg and mem records is was decided to show it only once
			var editor = m.admin_name;
			var htmlEditor = notMe ? '<i class="icon-user" title="Последние изменения: '+editor+'"></i>': '';

			// *** changes processed
			var htmlChanged = (m.changed > 0 ? '<i class="icon-pencil" title="Изменения еще не обработаны"></i>' : '');

			var age = getAgeWithSuffix(parseInt(m.age), m.age),
				memberInfo = '',
				memberSchoolOrCollegeDegree = getMemberSchoolOrCollegeDegree(m.category_key, age, m.college_start, m.college_end, m.school_start, m.school_end);
			var dataFields = 'data-id="'+m.id+'" data-category="'+m.category_key+'" data-attendmeeting="'+m.attend_meeting+'"data-age="'+parseInt(m.age)+'" data-locality="'+m.locality_key+'" data-school_end="'+m.school_end+'" data-college_end="'+m.college_end+'" ' ;

			var rowStyle = m.category_key == 'SC' && m.college ? ' style="color: red;" ' : '',
				cellPhones = m.cell_phone.split(','),cellPhonesStr = '';

			if(cellPhones.length > 1){
				for(var i in cellPhones){
					cellPhonesStr += '<div>'+cellPhones[i] +'</div>';
				}
			}
			else {
				cellPhonesStr = cellPhones[0];
			}

			switch(m.category_key){
				case 'SC':
					memberInfo = "Школьник";
					break;
				case 'ST':
					memberInfo = "Студент";
					break;
				case 'BL':
					memberInfo = "Верующие";
					break;
				case 'SN':
					memberInfo = "Святые в церковной жизни";
					break;
			}
			//console.log(m);
			// Cut the m.region string. Roman's code ver 5.0.0
			if (m.region =='--') {
				m.region = m.country;
			} else {
				m.region = m.region.substring(0, m.region.indexOf(" ("));
				// m.region += ', ';
				// m.region += m.country;
			}
			tableRows.push('<tr ' + dataFields +' class="'+(m.active==0?'inactive-member':'member-row')+'">'+
				'<td>' + he(m.name) + '<div style="margin-left:0" class="example">'+memberInfo + memberSchoolOrCollegeDegree+'</div></td>' +
				'<td>' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') +
				(in_array(7, window.user_settings) ? '<br/>' + '<span class="user_setting_span">'+(m.region || m.country) + '</span>' : '') + '</td>' +
				'<td>' + cellPhonesStr+ '</td>' +
				'<td '+ rowStyle +'>' + ( he(m.college) ? he(m.college) : "" ) +
				'<div style="margin-left: 0;" class="example">'+( he(m.college_locality) ? he(m.college_locality) : "" ) +'</div></td>' +
				'<td>' + age + '</td>' +
				'<td><input type="checkbox" class="check-meeting-attend" '+ (m.attend_meeting == 1 ? "checked" : "") +' /></td>' +
				'<td>' + htmlChanged + htmlEditor + '</td>' +
				<?php if (db_getAdminRole($memberId) != 0) { ?>'<td><i class="'+(m.active==0?'icon-circle-arrow-up' : 'icon-trash')+' icon-black" title="'+(m.active==0?'Добавить в список':'Удалить из списка')+'"/></td>' <?php } ?> +
				'</tr>'
			);

			phoneRows.push('<tr ' + dataFields +' class="'+(m.active==0?'inactive-member':'member-row')+'">'+
				'<td>'+ '<span style="float:right;">' + htmlChanged + htmlEditor +
 				'<i class="'+(m.active == 0 ? 'icon-circle-arrow-up' : 'icon-trash' )+' icon-black" title="'+(m.active==0?'Добавить в список':'Удалить из списка')+'"/>'+ '</span>' +
				'<span style="color: #006">' + he(m.name) + '</span>'+
				'<div>' + m.cell_phone + '</div>' +
				'<div>' + ' <span style="margin-left:0" class="example">'+memberInfo + memberSchoolOrCollegeDegree+'</span></div>'+
				'<div>' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') + ', ' + age +'</div>' + (in_array(7, window.user_settings) ? '<span class="user_setting_span">'+(m.region || m.country) + '</span>' : '') +
				'<div '+rowStyle+'>'+ (he(m.college) ? he(m.college) : "") + '<span>'+ ( he(m.college_locality) ? ' (' + he(m.college_locality) + ')' : "" ) +'</span></div>'+
				'<div>Посещает собрания: <input type="checkbox" class="check-meeting-attend" '+ (m.attend_meeting == 1 ? "checked" : "") +' /></div>'+
				'</td>' +
				'</tr>'
			);
		}

		$(".desctopVisible tbody").html (tableRows.join(''));
		$(".show-phone tbody").html (phoneRows.join(''));

		$(".member-row").unbind('click');
		$(".member-row").click (function () {
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

		filterMembers();
	}

	function getMemberSchoolOrCollegeDegree(category, age, collegeStart, collegeEnd, schoolStart, schoolEnd){
		var currentYear = new Date().getFullYear();
		var currentMonth = new Date().getMonth(); // month for check
		if(category === 'SC'){
			var classLevel;
// Start Roman's code ver 5.0.7
			if (schoolStart && schoolStart.length === 4) {
				classLevel = currentMonth <= 6 ? currentYear - 1 - schoolStart + 1 : currentYear - schoolStart + 1;
			}
// End Roman's code
			return classLevel ? classLevel > 0 && classLevel < 12 ? ', '+classLevel+' класс' : '' : age >= 7 ? age - 6 : '';
		}
		else{
			var startCollege = collegeStart && collegeStart.length === 4 ? parseInt(collegeStart) : '' ;
			var endCollege = collegeEnd && collegeEnd.length === 4 ? parseInt(collegeEnd) : '' ;
			var courseLevel;
// Start Roman's code ver 5.0.7
			if (startCollege) {
				courseLevel = currentMonth <= 6 ? currentYear - 1 - startCollege + 1 : currentYear - startCollege + 1;
			} else {
				courseLevel = '';
			}
// End Roman's code убрал && endCollege из следующего следом условия
			if(startCollege){
				if(currentYear < startCollege){
					courseLevel = "планирует поступить";
				}
				else if (currentYear === startCollege && currentMonth < 6) {
					courseLevel = "планирует поступить";
				}
				else if(endCollege && (currentYear === endCollege)){
					courseLevel = currentMonth >= 6 ? "обучение завершено" : courseLevel + " курс, окончание в этом году";
				}
				else if(endCollege && (currentYear > endCollege)){
					courseLevel = "учёба завершена";
				}
				else{
					courseLevel = courseLevel+" курс";
				}
			}
			return courseLevel ? ', '+ courseLevel : courseLevel;
		}
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

		$.post("/ajax/youth.php?update_member="+window.currentEditMemberId+($("#btnDoSaveMember").hasClass('create') ? "&create=true" : ""), data)
		.done (function(data) {
			refreshYouthList(data.members);
			$('#modalEditMember').modal('hide');
		});
	}

	function filterMembers(){
		var isTabletMode = $(document).width()<786,
			localityFilter = $("#selMemberLocality").val(),
			categoryFilter = $("#selMemberCategory").val(),
			collegeEndFilter = $("#selMemberCollegeEnd").val(),
			schoolEndFilter = $("#selMemberSchoolEnd").val(),
			attendMeetingFilter = $("#selMemberAttendMeeting").val(),
			ageFilter = $("#selMemberAge").val(),
			text = $('.search-text').val().trim().toLowerCase(),
			filteredMembers = [];

		$(".members-list " + ( isTabletMode ? " #membersPhone " : " #members " ) + " tbody tr").each(function(){
			var memberLocality = $(this).attr('data-locality'),
				memberCategory = $(this).attr('data-category'),
				memberAge = parseInt($(this).attr('data-age')),
				memberSchoolEnd = $(this).attr('data-school_end'),
				memberCollegeEnd = $(this).attr('data-college_end'),
				attendMeeting = $(this).attr('data-attendmeeting'),
				memberName = $(this).find('td').first().text().toLowerCase(),
				memberKey = $(this).attr('data-id');

			if(
			((localityFilter === '_all_' || localityFilter === undefined) && categoryFilter === '_all_' && text === '' && ageFilter === '_all_' && schoolEndFilter === '_all_'  && collegeEndFilter === '_all_' && attendMeetingFilter === '_all_') ||
			(
				(memberLocality === localityFilter || localityFilter === '_all_' || localityFilter === undefined) &&
				(memberCategory === categoryFilter || categoryFilter === '_all_') &&
				(memberSchoolEnd === schoolEndFilter || schoolEndFilter === '_all_') &&
				(memberCollegeEnd === collegeEndFilter || collegeEndFilter === '_all_') &&
				(attendMeeting === attendMeetingFilter || attendMeetingFilter === '_all_') &&
				(memberAge >= parseInt(ageFilter) || ageFilter === '_all_') &&
				(memberName.search(text) !== -1)
			)){
				$(this).show();
				filteredMembers.push(memberKey);
			}
			else{
				$(this).hide();
			}
		});

		return filteredMembers;
	}
var globalSingleCity = "<?php echo $singleCity; ?>";

</script>
<script src="/js/youth.js?v7"></script>
<?php include_once "footer.php"; ?>
