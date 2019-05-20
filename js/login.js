if ($('.logout').length === 0) {
  $('.form-signin').show();
  $('.form-logout').hide();
} else {
  $('.form-signin').hide();
  $('.form-logout').show();
  if (getCookie('sess_last_page') && $('.form-logout').on(':visible')){
    window.location = getCookie('sess_last_page');
  }
}
