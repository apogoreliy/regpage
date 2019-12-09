renewComboLists('.members-lists-combo');
$('#modalEditMember').on('show', function() {
  setTimeout(function () {
    showBlankEvents();
  }, 50);
  setTimeout(function () {
    $('.emLocality').hide();
  }, 10);
  if ($('#modalEditMember').find('.emNewLocality').is(':visible')) {
    var newLocalityLength = $('#modalEditMember').find('.emNewLocality').val();
  }
  if (globalSingleCity && $('#modalEditMember').find('#btnDoSaveMember').hasClass('create')) {
    $('.emLocality option').each(function () {
        $(this).val() === '_none_' ? '' : $('.emLocality').val($(this).val());
    });
  }
  $('.modalListInput').hide();
  setTimeout(function () {
    if (!$('#modalEditMember .college-fields').is(':visible')) {
      $('#modalEditMember .college-fields').find('input').each(function () {
        $(this).val() ? $(this).val('') : '';
      });
    }
    if (!$('#modalEditMember .school-fields').is(':visible')) {
      $('#modalEditMember .school-fields').find('input').each(function () {
        $(this).val() ? $(this).val('') : '';
      });
    }
  }, 3000);
});
$('#modalEditMember').on('hide', function() {
  $('#modalEditMember').find('.emLocality').attr('data-value','');
  $('#modalEditMember').find('.emLocality').attr('data-text','');
  $('#modalEditMember').find('#inputEmLocalityId').attr('data-value_input','');
  $('#modalEditMember').find('#inputEmLocalityId').attr('data-text_input','');
});
history.pushState(null, null, location.href);
    window.onpopstate = function () {
      if ($('#modalEditMember').is(':visible')) {
        history.go(1);
        $('#modalEditMember').modal('hide');
      }
    };
// START bug cover main menu
$('#modalEditMember').hide();
// STOP bug cover main menu
