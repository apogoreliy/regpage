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
		var commentBlockHeight = windowScreenHeight - 308;
		var chatBlockHeight = windowScreenHeight - 401;

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
    $('#addContact').parent().css('padding-left', '10px');
    $('#respShow').parent().css('padding-left', '8px');
    $('#statusShow').parent().css('padding-left', '8px');
    $('#maleShow').parent().css('padding-left', '8px');
    $('#respShow').parent().css('margin-right', '8px');
    $('#statusShow').parent().css('margin-right', '8px');
    $('#maleShow').parent().css('margin-right', '8px');
    $('#respShow').parent().css('margin-top', '8px');
    $('#statusShow').parent().css('margin-top', '4px');
    $('#maleShow').parent().css('margin-top', '4px');
    $('#contactsBtnsBar').css('min-width', '100px');
		$('#contactsBtnsBar').css('width', '100%');
		$('.contactsBtnsBar').css('padding-right', '20px');
    $('.fa-question').parent().parent().css('margin-left', '0px');
    $('.divider-vertical').css('height', '0px');
    $('.divider-vertical').css('width', '120px');
    $('.divider-vertical').css('border-top', '1px solid  #716f6f');
    $('.divider-vertical').css('margin', '9px 9px');
    $('.nav-sub-container').css('min-width', '100%');
    $('#helpButton').hide();
    $('#helpButtonMbl').show();
		//mobile
		if (data_page.admin_role === '0') {
			$('#listContactsMbl').css('padding-top', '130px');
		} else {
			$('#listContactsMbl').css('padding-top', '170px');
		}
		$('.cd-panel__header-watch').css('width', '100%');
		$('.cd-panel__container-watch').css('width', '100%');
		$('#orderSentToContact').css('margin-right', '40px');
		$('#myBlanks').parent().attr('style', 'padding-left: 8px; padding-right: 10px; margin-right: 8px; margin-top: 4px;');
		$('#search-text').parent().attr('style', 'padding-left: 8px; padding-right: 10px; margin-right: 8px; margin-top: 4px;');
  }

// resize
  $(window).resize(function(){
    if ($(window).width()>=769) {
			$('.show-name-list').hide();
      $('#listContactsMbl').hide();
      $('#listContacts').show();
      $('#selectAllChekboxMblShow').hide();
      $('#navbarNav ul').css('font-weight', 'normal');
      $('#navbarNav ul').css('padding', '0');
      $('#addContact').parent().css('padding-left', '0px');
      $('#respShow').parent().css('padding-left', '0px');
      $('#statusShow').parent().css('padding-left', '0px');
      $('#maleShow').parent().css('padding-left', '0px');
      $('#respShow').parent().css('margin-right', '0px');
      $('#statusShow').parent().css('margin-right', '0px');
      $('#maleShow').parent().css('margin-right', '0px');
      $('#respShow').parent().css('margin-top', '0px');
      $('#statusShow').parent().css('margin-top', '0px');
      $('#maleShow').parent().css('margin-top', '0px');
      $('#contactsBtnsBar').css('width', '1170px');
      $('.divider-vertical').css('height', '34px');
      $('.divider-vertical').css('width', '0px');
      $('.divider-vertical').css('border-left', '1px solid  #716f6f');
      $('.divider-vertical').css('margin', '0px 9px');
      $('.nav-sub-container').css('min-width', '1170px');
      $('#helpButton').show();
      $('#helpButtonMbl').hide();
			//Desktop
			$('.cd-panel__header-watch').css('width', '420px');
			$('.cd-panel__container-watch').css('width', '420px');
			$('#orderSentToContact').css('margin-right', '90px');
			$('#myBlanks').parent().attr('style', 'padding-left: 0; padding-right: 10px;');
			$('#search-text').parent().attr('style', 'padding-left: 0; padding-right: 10px;');

    } else if ($(window).width()<769) {
			$('.show-name-list').show();
      $('#listContactsMbl').show();
      $('#listContacts').hide();
      $('#selectAllChekboxMblShow').show();
      $('#respShow').parent().css('margin-right', '8px');
      $('#statusShow').parent().css('margin-right', '8px');
      $('#maleShow').parent().css('margin-right', '8px');
      $('#respShow').parent().css('margin-top', '8px');
      $('#statusShow').parent().css('margin-top', '4px');
      $('#maleShow').parent().css('margin-top', '4px');
      $('#contactsBtnsBar').css('min-width', '100px');
      $('.divider-vertical').css('height', '0px');
      $('.divider-vertical').css('width', '120px');
      $('.divider-vertical').css('border-top', '1px solid  #716f6f');
      $('.divider-vertical').css('margin', '9px 9px');
      $('.nav-sub-container').css('min-width', '100%');
      $('#helpButton').hide();
      $('#helpButtonMbl').show();
			//mobile
			if (data_page.admin_role === '0') {
				$('#listContactsMbl').css('padding-top', '170px');
			} else {
				$('#listContactsMbl').css('padding-top', '210px');
			}
			$('.cd-panel__header-watch').css('width', '100%');
			$('.cd-panel__container-watch').css('width', '100%');
			$('#orderSentToContact').css('margin-right', '40px');
			$('#myBlanks').parent().attr('style', 'padding-left: 8px; padding-right: 10px; margin-right: 8px; margin-top: 4px;');
			$('#search-text').parent().attr('style', 'padding-left: 8px; padding-right: 10px; margin-right: 8px; margin-top: 4px;');
    }
  });
// STOP Menu & resize
