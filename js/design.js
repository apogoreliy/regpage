// SCROLL UP
function handleScrollUp(){
		var height = $("body").height();
		//var scrollTop = $("body").scrollTop();

		height>600 && (window.pageYOffset > 300 || document.documentElement.scrollTop >300) ? $(".scroll-up").show() : $(".scroll-up").hide();

    if (height>600 && (window.pageYOffset > 300 || document.documentElement.scrollTop >50)) {
      if ($('.contactsBtnsBar').css('margin-top') !=='-77px') {
				$('.contactsBtnsBar').css('margin-top', '-77px');
				$('.contactsBtnsBar').css('border-bottom', '1px solid lightgrey');
      }
    } else {
      if ($('.contactsBtnsBar').css('margin-top') !== '-50px') {
				$('.contactsBtnsBar').css('margin-top', '-50px');
				$('.contactsBtnsBar').css('border-bottom', 'none');
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
  if ($(window).width()>=769) {
		//$('.show-name-list').hide();
    $('#listContactsMbl').hide();
    $('#listContacts').show();
    $('#selectAllChekboxMblShow').hide();
  } else {
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
    $('.fa-question').parent().parent().css('margin-left', '0px');
    $('.divider-vertical').css('height', '0px');
    $('.divider-vertical').css('width', '120px');
    $('.divider-vertical').css('border-top', '1px solid  #716f6f');
    $('.divider-vertical').css('margin', '9px 9px');
    $('.nav-sub-container').css('min-width', '100%');
    $('#helpButton').hide();
    $('#helpButtonMbl').show();
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
      $('#addContact').html('<i class="fa fa-plus"></i> Добавить');
      $('#openUploadModal').html('<i class="fa fa-upload"></i> Загрузить');
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
      $('#contactsBtnsBar').css('min-width', '700px');
      $('.divider-vertical').css('height', '34px');
      $('.divider-vertical').css('width', '0px');
      $('.divider-vertical').css('border-left', '1px solid  #716f6f');
      $('.divider-vertical').css('margin', '0px 9px');
      $('.nav-sub-container').css('min-width', '1170px');
      $('#helpButton').show();
      $('#helpButtonMbl').hide();
    } else if ($(window).width()<769) {
			$('.show-name-list').show();
      $('#listContactsMbl').show();
      $('#listContacts').hide();
      $('#selectAllChekboxMblShow').show();
      $('#openUploadModal').html('<i class="fa fa-upload"></i>');
      $('#addContact').html('<i class="fa fa-plus"></i>');
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
  /*    $('#navbarNav ul').css('font-weight', 'bold');
      $('#navbarNav ul').css('padding', '10px 10px');
      $('#contactsBtnsBar').css('padding-left', '8px');
      $('#respShow').parent().css('padding-left', '8px');
      $('#statusShow').parent().css('padding-left', '8px');
      $('#maleShow').parent().css('padding-left', '8px');
      */
    }
  });
// STOP Menu & resize
