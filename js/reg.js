//представление и пользовательское взаимодействие;
//управление данными;
//общее состояние приложения;
//настройка и код-прослойка, чтобы все части работали вместе;
// 1 раздел выдача результатов запроса
// 2 Фильтры и сортировки без запросов в базу данных
// 3 правка и удаление записей
// 4 пакетная правка записей
// 5 раздел выдача результатов вспомогательных или связанных запросов (массивы)
// вормирования Гридов и прочих представлений данных для основного списка
// вормирования Гридов и прочих представлений данных для вспомогательных списков

  $('#emBirthdateLabelSup').html('Дата рождения<sup>*</sup>');
  $('.emBirthdate').attr('valid', 'required');
// check date fields
  $('#modalEditMember').on('show', function() {
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
    setTimeout(function () {
      showBlankEvents();
    }, 50);
    setTimeout(function () {
      $('.emLocality').hide();
    }, 10);
    if ($('#modalEditMember').find('.emNewLocality').is(':visible')) {
      var newLocalityLength = $('#modalEditMember').find('.emNewLocality').val();
      var newLocalityLengthLenght;
      newLocalityLength ? newLocalityLengthLenght = newLocalityLength.length : newLocalityLengthLenght = 0;
    }
    if (globalSingleCity && $('#modalEditMember').find('.emLocality').val() === '_none_' && newLocalityLengthLenght < 1) {
      $('.emLocality option').each(function () {
          $(this).val() === '_none_' ? '' : $('.emLocality').val($(this).val());
      });
    }
// ADMIN'S LOCALITIES
    var localityValid = 0;
    for (var j in localityGlo) {
      if ($('#modalEditMember').attr('data-locality_key') == j) {
        localityValid = 1;
      }
      /*if (localityGlo.hasOwnProperty(j)) {
      }*/
    }
    if (localityValid == 0) {
      $('.modalListInput .listItemLocality').each(function () {
        var ccounter = 0;
        for (var j in localityGlo) {
          if ($(this).attr('data-value') == j || $('#modalEditMember').attr('data-locality_key') == $(this).attr('data-value')) {
            ccounter++ ;
          }
        }
        ccounter > 0 ? '' : $(this).hide();
      });
    }
// ADMIN'S LOCALITIES

    $('.modalListInput').hide();

    if ($("#eventTabs").find(".tab-pane.active").attr('data-access') != 1 && localityValid != 0 && !$('#btnDoSaveMember').hasClass('locality_all')) {
      $("#inputEmLocalityId").autocomplete('disable');
      $("#inputEmLocalityId").autocomplete({
        serviceUrl: '/ajax/localities2.php',
        onSelect: function (suggestion) {
            $("#inputEmLocalityId").focus();
        }
      });
    } else {
      $("#inputEmLocalityId").autocomplete('disable');
      $("#inputEmLocalityId").autocomplete({
        serviceUrl: '/ajax/localities3.php',
        onSelect: function (suggestion) {
            $("#inputEmLocalityId").focus();
        }
      });
    };
  });

$('#modalEditMember').on('hide', function() {
  $('#btnDoSaveMember').hasClass('locality_all') ? $('#btnDoSaveMember').removeClass('locality_all') : '';
  $('#modalEditMember').find('.emLocality').attr('data-value','');
  $('#modalEditMember').find('.emLocality').attr('data-text','');
  $('#modalEditMember').find('#inputEmLocalityId').attr('data-value_input','');
  $('#modalEditMember').find('#inputEmLocalityId').attr('data-text_input','');
  // ОЧИСТИТЬ поля парковки. Код повторяется на странице Индекс
  setTimeout(function () {
    if (!($('#modalEditMember').is(':visible'))) {
      $('#modalEditMember').find('.emAvtomobileNumber').val() ? $('#modalEditMember').find('.emAvtomobileNumber').val(''):'';
      $('#modalEditMember').find('.emAvtomobile').val() ? $('#modalEditMember').find('.emAvtomobile').val(''):'';
      $('#modalEditMember').find('.emParking').val() != '_none_' ? $('#modalEditMember').find('.emParking').val(0) : '';
    }
  }, 500);
});

  // start back button bahevior
/*
  window.onpopstate = function(event) {
    alert("location: " + document.location + ", state: " + JSON.stringify(event.state));
  };
  if (window.history && window.history.pushState) {

    console.log('Im here');

    $(window).on('popstate', function() {

      console.log('Im there');
      alert('Back button was pressed.');
    });
  }
*/
// start button back
/*
history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
//onBackKeyDown();
function onBackKeyDown() {
  window.location = '/reg';
};
//onBackKeyDown();

window.onbeforeunload = function() {
  return "Your work will be lost.";
};

  function onBackKeyDown() {
    alert('sdasdasdsd');
    //window.location = '/reg'

  window.addEventListener ("popstate", function (e) {
//код обработки события popstate
console.log(event.state);
alert('sdasdasdsd');
});
}*/
history.pushState(null, null, location.href);
    window.onpopstate = function () {
      if ($('#modalEditMember').is(':visible')) {
        history.go(1);
        $('#modalEditMember').modal('hide');
      }
    };


    //Do your code here

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
});
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

// START Search for members
$('#searchBlockFilter').on('input', function (e) {
  //
  var existRegistration = [];
  $('.reg-list tr').each(function() {
    var classId = $(this).attr('class');
    classId = classId ? $(this).attr('class').replace(/^regmem-/,'mr-') : '';
    classId ? existRegistration.push(classId) : '';
  });
  var desired = $(this).val();
  $('.membersTable tr').each(function() {
    var str = $(this).find('td:nth-child(2)').text();
    var current = $(this).attr('id');
    str.toLowerCase().indexOf(String(desired.toLowerCase())) === -1 ? $(this).hide() : $(this).show();
    if ((existRegistration.indexOf(current) != -1) && existRegistration) {
      $(this).hide();
    }
  });
});
// STOP Search for members

// Hide Global Error
$('#globalError').find('.close-alert').click(function () {
  $('#globalError').hide();
});
