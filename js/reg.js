  $('#emBirthdateLabelSup').html('Дата рождения<sup>*</sup>');
  $('.emBirthdate').attr('valid', 'required');
// check date fields
  $('#modalEditMember').on('show',  function() {
    setTimeout(function () {
      var form = $('#modalEditMember');
      var a = parseDDMM (form.find(".emArrDate").val());
      var b = parseDDMM (form.find(".emDepDate").val());
        if(!$('.emArrDate').parent().hasClass('error') && !a){
          $('.emArrDate').parent().addClass('error');
        };
        if(!$('.emDepDate').parent().hasClass('error') && !b){
          $('.emDepDate').parent().addClass('error');
      };
  }, 1000);
  });
  // start back button bahevior
/*
  if (window.history && window.history.pushState) {

    $(window).on('popstate', function() {
      alert('Back button was pressed.');
    });
  }
*/
// start button back
//window.onbeforeunload = function() { return "Your work will be lost."; };
/*document.addEventListener("backbutton", onBackKeyDown(), false);
  function onBackKeyDown() {
    alert('sdasdasdsd');
    //window.location = '/reg'

  window.addEventListener ("popstate", function (e) {
//код обработки события popstate
console.log(event.state);
alert('sdasdasdsd');
});
}*/
// end button back
// end back button bahevior

//START stop automatic scrolling on modal window
/*window.addEventListener("scroll", preventMotion, false);
window.addEventListener("touchmove", preventMotion, false);

function preventMotion(event){
  if ($('#modalEditMember').is(':visible') && $(document).width() < 980) {
    window.scrollTo(0, 0);
    event.preventDefault();
    event.stopPropagation();
  }
}*/
$('#modalEditMember').on('show', function() {
  if ($(document).width() < 980) {
    window.scrollTo(0, 0);
  }
})
// STOP stop automatic scrolling on modal window
// START prepare XLX for international meetings
function xlxCheckboxesInternational(element, show) {
  if (show) {
    $(element).attr("disabled", false);
    $(element).parent().show();
  } else {
    $(element).prop("checked", false);
    $(element).attr("disabled", "disabled");
    $(element).parent().hide();
  }
}
function xlxCheckboxesInternationalDisabled() {
  if ($('.tab-pane.active').attr('data-need_tp') === '1') {
//hide
    xlxCheckboxesInternational('#download-member-age');
    xlxCheckboxesInternational('#download-region');
    xlxCheckboxesInternational('#download-service');
    xlxCheckboxesInternational('#download-coord');
    xlxCheckboxesInternational('#download-mate');
    xlxCheckboxesInternational('#download-status');
    xlxCheckboxesInternational('#download-reg-state');
    xlxCheckboxesInternational('#download-document');
    xlxCheckboxesInternational('#download-english');
    xlxCheckboxesInternational('#download-visa');
    xlxCheckboxesInternational('#download-accom');
    xlxCheckboxesInternational('#download-transport');
    xlxCheckboxesInternational('#download-hotel');
    xlxCheckboxesInternational('#download-admin-comment');
    xlxCheckboxesInternational('#download-member-comment');
    xlxCheckboxesInternational('#download-paid');
//show
    xlxCheckboxesInternational('#download-airport-arrival', true);
    xlxCheckboxesInternational('#download-airport-departure', true);
    xlxCheckboxesInternational('#download-outline-language', true);
    xlxCheckboxesInternational('#download-study-group-language', true);
  } else {
//hide
    xlxCheckboxesInternational('#download-airport-arrival');
    xlxCheckboxesInternational('#download-airport-departure');
    xlxCheckboxesInternational('#download-outline-language');
    xlxCheckboxesInternational('#download-study-group-language');
//show
    xlxCheckboxesInternational('#download-member-age', true);
    xlxCheckboxesInternational('#download-region', true);
    xlxCheckboxesInternational('#download-service', true);
    xlxCheckboxesInternational('#download-coord', true);
    xlxCheckboxesInternational('#download-mate', true);
    xlxCheckboxesInternational('#download-status', true);
    xlxCheckboxesInternational('#download-reg-state', true);
    xlxCheckboxesInternational('#download-document', true);
    xlxCheckboxesInternational('#download-english', true);
    xlxCheckboxesInternational('#download-visa', true);
    xlxCheckboxesInternational('#download-accom', true);
    xlxCheckboxesInternational('#download-transport', true);
    xlxCheckboxesInternational('#download-hotel', true);
    xlxCheckboxesInternational('#download-admin-comment', true);
    xlxCheckboxesInternational('#download-member-comment', true);
    xlxCheckboxesInternational('#download-paid', true);
  }
}
// END prepare XLX for international meetings
function arrDepSecondCheckbox(element1, element2) {
  if ($(element1).prop('checked')) {
    $(element2).prop('checked', true);
  } else {
    $(element2).prop('checked', false);
  }
};
$('#download-arr-dep-date').click(function () {
  arrDepSecondCheckbox(this, '#download-dep-date');
});
$('#download-arr-dep-time').click(function () {
  arrDepSecondCheckbox(this, '#download-dep-time');
});
$('#download-tp').click(function () {
  arrDepSecondCheckbox(this, '#download-tp-name');
});
