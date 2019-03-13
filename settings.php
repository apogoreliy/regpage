<?php

include_once "header.php";
include_once "nav.php";

$hasMemberRightToSeePage = db_isAdmin($memberId);
if(!$hasMemberRightToSeePage){
    die();
}
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
					category_list.push(setting.category_name);

					settings_list.push('<h4>'+setting.category_name+'</h4>');
				}

				if(setting.setting_key){
					settings_list.push(
					'<div style="margin-bottom: 5px;">'+
					'<input style="margin-top:0" id="'+setting.setting_key+'" class="select_setting" type="checkbox" '+( in_array(setting.setting_key, user_settings) ? "checked" : "")+' />'+
					'<label for="'+setting.setting_key+'" style="display:inline; margin-left: 10px;">'+setting.name+'</label></div>');
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

				$.get('/ajax/setting.php?change_user_setting', {setting_key: setting_key, is_checked: is_checked})
	            .done (function(data) {
	            });
              if ($(this).attr('id') == 8 ) {
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
	});

</script>

<?php

include_once 'footer.php';

?>
