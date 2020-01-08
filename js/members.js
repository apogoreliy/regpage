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
});
$('#modalEditMember').on('hide', function() {
  $('#modalEditMember').find('.emLocality').attr('data-value','');
  $('#modalEditMember').find('.emLocality').attr('data-text','');
  $('#modalEditMember').find('#inputEmLocalityId').attr('data-value_input','');
  $('#modalEditMember').find('#inputEmLocalityId').attr('data-text_input','');
  setTimeout(function () {
    if (!$('#modalEditMember').is(':visible')) {
      $('#modalEditMember .college-fields').find('input').each(function () {
        $(this).val() ? $(this).val('') : '';
      });
      $('#modalEditMember .school-fields').find('input').each(function () {
        $(this).val() ? $(this).val('') : '';
      });
    }
  }, 500);
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
/*
// START hide empty city
function hideEmptyCity() {
  var city = [], members = [];
  $('#selMemberLocality option').each(function () {
    city.push($(this).val());
  });
  $('#members tbody tr').each(function () {
    members.push($(this).attr('data-locality'));
  });

  for (var i = 0; i < city.length; i++) {
    if (!(city[i].indexOf(',') !== -1 || city[i] === '_all_')) {
      var a = members.indexOf(city[i]);
      if (a === -1) {
        $('#selMemberLocality option').each(function () {
          if ($(this).val() == city[i]) {
            $(this).css('display', 'none');
          }
        });
      }
    }
  }
}
setTimeout(function () {
  hideEmptyCity();
}, 2000);
// STOP hide empty city
*/
