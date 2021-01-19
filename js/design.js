// SCROLL UP
function handleScrollUp(){
		var height = $("body").height();
		//var scrollTop = $("body").scrollTop();

		height>600 && (window.pageYOffset > 300 || document.documentElement.scrollTop >300) ? $(".scroll-up").show() : $(".scroll-up").hide();

    if (height>600 && (window.pageYOffset > 300 || document.documentElement.scrollTop >50)) {
      if ($('.contactsBtnsBar').css('margin-top') !=='-77px') {
				if ($(window).width()>=769 && $(window).width()<1200) {
				//$('.contactsBtnsBar').css('padding-right','90px');
				}
				$('.contactsBtnsBar').css('margin-top', '-77px');
				$('.contactsBtnsBar').css('border-bottom', '1px solid #ddd');
				$('.contactsBtnsBar').css('border-left', '1px solid #ddd');
				$('.contactsBtnsBar').css('border-right', '1px solid #ddd');
				$('.contactsBtnsBar').css('background-color', '#eee');
      }
    } else {
      if ($('.contactsBtnsBar').css('margin-top') !== '-50px') {
				$('.contactsBtnsBar').css('margin-top', '-50px');
				$('.contactsBtnsBar').css('border-bottom', 'none');
				$('.contactsBtnsBar').css('border-left', 'none');
				$('.contactsBtnsBar').css('border-right', 'none');
				$('.contactsBtnsBar').css('background-color', 'white');
      }
    }
}

window.onscroll = function() {
		handleScrollUp();
};

$(".scroll-up").click(function(e){
		e.stopPropagation();

		//scrollTo(document.body, 0, 500);
	//  setTimeout(function(){
				document.body.scrollTop = document.documentElement.scrollTop = 0;
//    }, 500);
		$('body').animate({
				scrollTop: 0
		}, 500);
});
// STOP SCROLL UP

// START Menu & resize
// width


/*
if ($(window).width()>=1200) {
	//$('.contactsBtnsBar').css('padding-right','349px');
}
*/

if (window.location.pathname === '/contacts') {

	if ($(window).height()>=550) {
		var windowScreenHeight = $(window).height();
		var mainBlockHeight = windowScreenHeight;
		if (data_page.admin_role > 0 && windowScreenHeight < 675) {
			mainBlockHeight = mainBlockHeight - 170 - (windowScreenHeight - 550);
		} else if (data_page.admin_role === '0' && windowScreenHeight < 615) {
			mainBlockHeight = mainBlockHeight - 225 - (windowScreenHeight - 550);
		}else {
			mainBlockHeight = mainBlockHeight - 298;
		}
		var commentBlockHeight = windowScreenHeight - 339;
		var chatBlockHeight = windowScreenHeight - 370;

		chatBlockHeight+= 'px';
		commentBlockHeight+= 'px';
		mainBlockHeight+= 'px';

		$('#chatBlock').css('height', chatBlockHeight);
		$('#commentContact').parent().parent().css('height', commentBlockHeight);
		$('#personalBlankTab').css('height', mainBlockHeight);
	}

  if ($(window).width()>=769) {
		//$('.show-name-list').hide();
		$('#dropdownMenuContacts').hide();
    $('#listContactsMbl').hide();
    $('#listContacts').show();
    $('#selectAllChekboxMblShow').hide();
		if (data_page.admin_role === '0') {
			$('.contactsBtnsBar').css('padding-right', '200px');
		}
		if ($(window).width()>=769 && $(window).width()<1200) {
			$('#orderSentToContact').css('margin-right','90px');
		}
  } else {
		$('.bell-alarm').hide();
		$('.show-name-list').show();
    $('#listContactsMbl').show();
    $('#listContacts').hide();
    $('#selectAllChekboxMblShow').show();
    $('#navbarNav ul').css('font-weight', 'bold');
    $('#navbarNav ul').css('padding', '10px');
    $('#addContact').html('<i class="fa fa-plus"></i>');
    $('#openUploadModal').html('<i class="fa fa-upload"></i>');
		$('#deleteContactsShowModal').html('<i class="fa fa-trash"></i>');
		$('#appointResponsibleShow').html('<i class="fa fa-exchange"></i>');
		$('#appointStatusShow').html('<i class="fa fa-flag"></i>');
		$('#respStatistic').html('<i class="fa fa-list"></i>');
		$('#openFiltersPanelBtn').html('<i class="fa fa-filter"></i>');
// panel
		$('#addContact').parent().parent().css('min-width', '250px');
		$('#addContact').parent().parent().css('padding-left', '10px');
		$('#addContact').parent().parent().css('margin-bottom', '0px');
		$('#addContact').parent().css('padding-right', '5px');


		$('#contactsBtnsBar').css('width', (($(window).width()+15)+'px'));
		$('#contactsBtnsBar button').css('margin-bottom', '8px');
		$('#contactsBtnsBar').css('padding-right', '2px');
//
		$('#textFiltersForUsers').css('width', $('#textFiltersForUsers').parent().css('width'));
		$('#textFiltersForUsers').css('padding-left', '5px');

		$('.fa-question').parent().parent().css('margin-left', '0px');
    $('.divider-vertical').css('height', '0px');
    $('.divider-vertical').css('width', '120px');
    $('.divider-vertical').css('border-top', '1px solid  #716f6f');
    $('.divider-vertical').css('margin', '9px 9px');
    $('.nav-sub-container').css('min-width', '100%');
    $('#helpButton').hide();
    $('#helpButtonMbl').show();
		$('#phoneContact').parent().removeClass('col-6');
		$('#phoneContact').parent().addClass('col-5');
		$('#phoneContactCalling').addClass('col-1');
		$('#phoneContactCalling').show();
		//mobile
		if (data_page.admin_role === '0') {
			$('#listContactsMbl').css('padding-top', '50px');
		} else {
			if ($(window).width()<=381 && $('#respStatistic').is(':visible')) {
				$('#periodLabel').css('margin-left', '120px');
				$('#listContactsMbl').css('padding-top', '80px');
			} else {
				$('#listContactsMbl').css('padding-top', '40px');
			}
		}

		$('.cd-panel__header-watch').css('width', '100%');
		$('.cd-panel__container-watch').css('width', '100%');
		$('#orderSentToContact').css('margin-right', '40px');
		$('#myBlanks').parent().attr('style', 'padding-right: 10px; margin-right: 8px; margin-top: 4px;');
		$('#periodsCombobox').parent().attr('style', 'padding-right: 10px; margin-right: 8px; margin-top: 4px;');

		$('#search-text').parent().attr('style', 'padding-left: 8px; padding-right: 10px; margin-right: 8px; margin-top: 4px; margin-bottom: 0px !important;');
		var searchWidth = $(window).width()-50;
		searchWidth = String(searchWidth) + 'px';
		$('#search-text').attr('style', 'max-width: '+searchWidth+' !important;')
		$('#search-text').parent().hide();
		$('#openSearchFieldBtn').show();
		if ($(window).width()<525 && $(window).width()>380) {
			$('#adminNotes').attr('cols', '38');
		} else if ($(window).width()<=380 && $(window).width()>314) {
			$('#adminNotes').attr('cols', '30');
		} else if ($(window).width()<=314) {
			$('#adminNotes').attr('cols', '26');
		}
  }

// resize

  $(window).resize(function(){
    if ($(window).width()>=769) {
			// menu
			$('#helpButton').show();
      $('#helpButtonMbl').hide();
			$('.show-name-list').hide();
			$('.divider-vertical').css('height', '34px');
      $('.divider-vertical').css('width', '0px');
      $('.divider-vertical').css('border-left', '1px solid  #716f6f');
      $('.divider-vertical').css('margin', '0px 9px');
			$('#navbarNav ul').css('font-weight', 'normal');
      $('#navbarNav ul').css('padding', '0');

			// Buttons bar & buttons
			$('#contactsBtnsBar').css('width', '1170px');
			$('#addContact').html('<i class="fa fa-plus"></i> Добавить');
	    $('#openUploadModal').html('<i class="fa fa-upload"></i> Загрузить');
			$('#deleteContactsShowModal').html('<i class="fa fa-trash"></i> Удалить');
			$('#appointResponsibleShow').html('<i class="fa fa-exchange"></i> Передать');
			$('#appointStatusShow').html('<i class="fa fa-flag"></i> Изменить статус');
			$('#respStatistic').html('<i class="fa fa-list"></i> Распределение');
			$('#openFiltersPanelBtn').html('Фильтры');
			$('#search-text').attr('style', 'max-width: 100px !important;');
			$('#search-text').parent().attr('style', 'padding-left: 0px; padding-right: 0px; margin-right: 0px; margin-top: 0px; margin-bottom: 0px !important;');			
			$('#openSearchFieldBtn').hide();

			// List
			$('#listContactsMbl').hide();
			$('#listContacts').show();
			$('#selectAllChekboxMblShow').hide();

			// Blank
			$('#periodLabel').css('margin-left', '160px');
			$('.cd-panel__header-watch').css('width', '420px');
			$('.cd-panel__container-watch').css('width', '420px');
			$('#orderSentToContact').css('margin-right', '90px');
			if ($(window).width()>=525) {
				$('#adminNotes').attr('cols', '56');
			}

		/*
      $('.nav-sub-container').css('min-width', '1170px');
			$('#search-text').parent().attr('style', 'padding-left: 0; padding-right: 10px; margin-bottom: 0px !important;');
		*/
    } else if ($(window).width()<769) {
			// menu
			$('#helpButton').hide();
			$('#helpButtonMbl').show();
			$('.show-name-list').show();
			$('.divider-vertical').css('height', '0px');
			$('.divider-vertical').css('width', '120px');
			$('.divider-vertical').css('border-top', '1px solid  #716f6f');
			$('.divider-vertical').css('margin', '9px 9px');
			$('.nav-sub-container').css('min-width', '100%');

			// Buttons bar & buttons
			$('#contactsBtnsBar').css('min-width', '100px');
			$('#addContact').html('<i class="fa fa-plus"></i>');
	    $('#openUploadModal').html('<i class="fa fa-upload"></i>');
			$('#deleteContactsShowModal').html('<i class="fa fa-trash"></i>');
			$('#appointResponsibleShow').html('<i class="fa fa-exchange"></i>');
			$('#appointStatusShow').html('<i class="fa fa-flag"></i>');
			$('#respStatistic').html('<i class="fa fa-list"></i>');
			$('#openFiltersPanelBtn').html('<i class="fa fa-filter"></i>');
			$('#search-text').parent().attr('style', 'padding-left: 8px; padding-right: 10px; margin-right: 8px; margin-top: 4px; margin-bottom: 0px !important;');
			var searchWidth = $(window).width()-50;
			searchWidth = String(searchWidth) + 'px';
			$('#search-text').attr('style', 'max-width: '+searchWidth+' !important;');
			$('#openSearchFieldBtn').show();
			if ($(window).width()<=381 && $('#respStatistic').is(':visible')) {
				$('#periodLabel').css('margin-left', '120px');
				$('#listContactsMbl').css('padding-top', '80px');
			} else {
				$('#listContactsMbl').css('padding-top', '40px');
			}
			$('#contactsBtnsBar').css('width', (($(window).width()+15)+'px'));

			// List
			$('#listContactsMbl').show();
			$('#listContacts').hide();
			$('#selectAllChekboxMblShow').show();

			// Blank
			$('.cd-panel__header-watch').css('width', '100%');
			$('.cd-panel__container-watch').css('width', '100%');
			$('#orderSentToContact').css('margin-right', '40px');
			if ($(window).width()<525 && $(window).width()>380) {
				$('#adminNotes').attr('cols', '38');
			} else if ($(window).width()<=380 && $(window).width()>314) {
				$('#adminNotes').attr('cols', '30');
			} else if ($(window).width()<=314) {
				$('#adminNotes').attr('cols', '26');
			}
			/*
			//mobile
			if (data_page.admin_role === '0') {
				$('#listContactsMbl').css('padding-top', '170px');
			} else {
				$('#listContactsMbl').css('padding-top', '210px');
			}

			$('#search-text').parent().attr('style', 'padding-left: 8px; padding-right: 10px; margin-right: 8px; margin-top: 4px;');
			*/
    }

  });
}
// STOP Menu & resize
