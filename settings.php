<?php

include_once "header.php";
include_once "nav.php";

//$hasMemberRightToSeePage = db_isAdmin($memberId);
//if(/*!$hasMemberRightToSeePage*/!$memberId){
//    die();
//}
?>

<style>body {padding-top: 60px;}</style>
<div class="container">
	<div id="eventTabs">
		<div class="tab-content">
			<h3>Настройки</h3>
			<hr />
			<div class="settings_list">
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
    var adminRole = '<?php echo db_getAdminRole($memberId); ?>';
		get_settings_list();

		function get_settings_list(){
			$.get('/ajax/setting.php?get_settings')
            .done (function(data) {
                render_setting_list(data.settings, data.user_settings, data.access_area, data.user_access_area_settings);
            });
		}

		function render_setting_list(settings, user_settings, access_area, user_access_area_settings){
			var settings_list = [], category_list = [];

			for(var s in settings){
				var setting = settings[s];

				if(!in_array(setting.category_name, category_list)){
          if(setting.category_key && adminRole != 0){
					   category_list.push(setting.category_name);
					   settings_list.push('<h4>'+setting.category_name+'</h4>');
          } else if (setting.category_key == 6 && adminRole == 0){
            category_list.push(setting.category_name);
            settings_list.push('<h4>'+setting.category_name+'</h4>');
          }
				}

				if(setting.setting_key && adminRole != 0){
          var sName = '';
          if (setting.setting_key == 9 && in_array(setting.setting_key, user_settings)) {
            sName = setting.name;
          } else {
            sName = setting.name;
          }
					settings_list.push(
					'<div style="margin-bottom: 5px;">'+
					'<input style="margin-top:0" id="'+setting.setting_key+'" class="select_setting" type="checkbox" '+( in_array(setting.setting_key, user_settings) ? "checked" : "")+' />'+
					'<label for="'+setting.setting_key+'" style="display:inline; margin-left: 10px;">'+sName+'</label></div>');
				} else if ((setting.setting_key == 9 && adminRole == 0) || (setting.setting_key == 10 && adminRole == 0) || (setting.setting_key == 11 && adminRole == 0)) {
          if (setting.setting_key == 9 && in_array(setting.setting_key, user_settings)) {
            sName = setting.name;
          } else {
            sName = setting.name;
          }
          settings_list.push(
          '<div style="margin-bottom: 5px;">'+
          '<input style="margin-top:0" id="'+setting.setting_key+'" class="select_setting" type="checkbox" '+( in_array(setting.setting_key, user_settings) ? "checked" : "")+' />'+
          '<label for="'+setting.setting_key+'" style="display:inline; margin-left: 10px;">'+sName+'</label></div>');
        }

				if(setting.category_name == 'Зоны доступа'){
					for(var a in access_area){
						var area = access_area[a];

						settings_list.push(
							'<div style="margin-bottom: 5px;">'+
							//'<input style="margin-top:0" id="'+a+'" class="select_area" type="checkbox" '+( in_array(a, user_access_area_settings) ? "checked" : "")+' />'+
							'<label for="'+a+'" style="display:inline;">'+area+'</label></div>'
						);
					}
				}
			}

			$('.settings_list').html(settings_list.join(''));

			$('.select_setting').change(function(){
				var is_checked = $(this).prop('checked'),
					setting_key = $(this).attr('id');
					if (window.adminId[0] === '9') {
						showHint('Для новых пользователей настройки станут доступны в течении суток.');
						$(this).prop('checked', false);
						return
					}
				$.get('/ajax/setting.php?change_user_setting', {setting_key: setting_key, is_checked: is_checked})
	            .done (function(data) {
	            });

				if (($(this).attr('id') === '9') && !is_checked) {
					if ($('#10').prop('checked') === true) {
						setTimeout(function () {
							$.get('/ajax/setting.php?change_user_setting', {setting_key: 10, is_checked: is_checked})
				            .done (function(data) {
				            });
							$('#10').prop('checked', false);
						}, 30);
					}
					if ($('#11').prop('checked') === true) {
						setTimeout(function () {
							$.get('/ajax/setting.php?change_user_setting', {setting_key: 11, is_checked: is_checked})
				            .done (function(data) {
				            });
							$('#11').prop('checked', false);
						}, 60);
					}
				}
				setTimeout(function () {
					if (($(this).attr('id') === '9') && is_checked) {
						$.get('/ajax/practices.php?new_practices')
						.done (function(data) {
						});
					}
				}, 100);

        	if ($(this).attr('id') == '8' || $(this).attr('id') === '9' || $(this).attr('id') == '12') {
						$('#10').attr('disabled', true);
						$('#11').attr('disabled', true);
            setTimeout(function() { window.location = '/settings'} ,700);
        	}
        });
			$('.select_area').change(function(){
				var is_checked = $(this).prop('checked'),
					setting_key = $(this).attr('id');

				$.get('/ajax/setting.php?change_access_area', {setting_key: setting_key, is_checked: is_checked})
	            .done (function(data) {
	            });
			});
		}
		setTimeout(function () {
			if ($('#9').prop('checked') === false) {
				$('#10').attr('disabled', true);
				$('#11').attr('disabled', true);
				$('#10').next().attr('title', 'Что бы активировать эту опцию, начните ежедненвный учёт, отметив галочку выше.');
				$('#11').next().attr('title', 'Что бы активировать эту опцию, начните ежедненвный учёт, отметив галочку выше.');
				$('#10').attr('title', 'Что бы активировать эту опцию, начните ежедненвный учёт, отметив галочку выше.');
				$('#11').attr('title', 'Что бы активировать эту опцию, начните ежедненвный учёт, отметив галочку выше.');
			}
		}, 100);
	});

</script>
<script src="/js/settings.js?v1"></script>
<?php

include_once 'footer.php';

?>
