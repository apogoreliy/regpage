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
    }
    if (globalSingleCity && $('#modalEditMember').find('.emLocality').val() === '_none_' && newLocalityLength.length < 1) {
      $('.emLocality option').each(function () {
          $(this).val() === '_none_' ? '' : $('.emLocality').val($(this).val());
      });
    }
    $('.modalListInput').hide();

    if ($("#eventTabs").find(".tab-pane.active").attr('data-access') != 1) {
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
  $('#modalEditMember').find('.emLocality').attr('data-value','');
  $('#modalEditMember').find('.emLocality').attr('data-text','');
  $('#modalEditMember').find('#inputEmLocalityId').attr('data-value_input','');
  $('#modalEditMember').find('#inputEmLocalityId').attr('data-text_input','');
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
// START UPLOADING FILES
var xlsxDataGlobal, xlsxDataGlobalReg = [];

$(".uploadExl").unbind('click');
$(".uploadExl").click(function(event){
    event.stopPropagation();
    $('#modalUploadItems').modal().show();
});

function getUpdaterEditor(array) {
  var female, ii = 0, emptyString = 0;
  for (var i = 0; i < array.length; i++) {
    var item = array[i];
    if (i!=0) {
      ii = 0;
      emptyString = 0;
      for (var key in item) {
        //var ii = 0; ii < array[i].length; ii++
        if ((ii > 2) && (ii < 14)) {
          if (ii === 3) {
            var y = item[key];
            var x = item[key-1];
            var u;
            if (item[key]) {
              u = y.slice(-1);
            }
            if ((u == 'а') || (u == 'я') || (u == 'э') || (u == 'е')) {
              female = 1;
            } else {
              female = 0;
            }
            var z = x + ' ' + y;
            item[key-1] = z;
          } else if (ii === 6) {
            var d = item[key-2];
            var f = item[key];
            var g = f + ' ' + d;
            item[key-2] = g;
            item[key] = '';
            female == 1 ? item[key-1] = 0 : item[key-1] = 1;
          } else if (ii === 8) {
            var m = item[key-2];
            var n = item[key];
            var v = n + ' ' + m;
            item[key-2] = v;
            item[key] = '';
          } else if (ii === 10) {
            var p = item[key-4];
            var j = item[key];
            var r = item[key- 10];
            var o = j + ' ' + r + ' ' + p;
            item[key- 10] = o;
            item[key] = '';
            item[key-4] = item[key-2];
            item[key-2] = '';
          } else if (ii === 11) {
            item[key-4] = $('#uploadCountry').val();
          } else if (ii === 12) {
            item[key-4] = $('#uploadCategory').val();
          } else {
            var a = item[key];
            item[key-1] = a;
            item[key] = '';
          }
        }
        ii++;
      }
    }
  }
  uploadTableBuilder (array);
}

function getUpdaterEditorForRegTbl(array) {
  var female, ii = 0;
  for (var i = 0; i < array.length; i++) {
    var item = array[i];
    if (i!=0) {
      ii = 0;
    for (var key in item) {
        //var ii = 0; ii < array[i].length; ii++
        if ((ii > 0) && (ii < 14)) {
          if (ii === 1) {
            var a = $('.tab-pane.active').attr('id');
            a ? a = a.slice(9,17) : '';
            item[key] = a;
          } else if (ii === 2) {
            item[key] = $('.tab-pane.active').attr('data-start');
          } else if (ii === 3) {
            item[key] = $('.tab-pane.active').attr('data-end');
          } else if (ii === 4) {
            item[key] = '01';
          } else if (ii === 5) {
            item[key] = window.adminId;
          } else if (ii === 6) {
            item[key] = $('.tab-pane.active').attr('data-currency');
          } else if (ii === 8) {
            var m = item[key-1];
            var n = item[key];
            var v = m + ' ' + n;
            item[key-1] = v;
            item[key] = $('#uploadAccom').val();
          } else if ((ii === 9) || (ii === 10)) {
            item[key] = null;
          }
        }
        ii++;
      }
    }
  }
}

function uploadTableBuilder (array) {
var htmlValueCol = '<h4>Колонки</h4>', htmlValueStr = '<h4>Строки</h4>';
  for (var i = 0; i < array.length; i++) {
    var halas;
      for (var ii = 0; ii < array[i].length; ii++) {
        if (i == 0) {
          if (array[i][ii]) {
            //console.log(array[i][ii]);
            array[i][ii] ? htmlValueCol += '<label><input type="checkbox" data-col="'+array[i][ii]+'" checked> '+array[i][ii]+'</label>' : '';
          } else if (!halas) {
            htmlValueCol += '<hr>';
            //$('#modalUploadItems').find('#uploadPrepare').html(htmlValueCol);
            halas = 1;
          }
        }
       }
      }
      //ДОДЕЛАТЬ ВЫДАЧУ СТРОК
/*
      for (var iii = 0; iii <= array.length; iii++) {

        if ((iii != 0)) {
          if (array[iii] && (iii !=array.length)) {
              htmlValueStr += '<label><input type="checkbox" data-col="'+array[iii][3]+'" checked>'+array[iii][3]+'</label>';
            } else{
              console.log(htmlValueStr);
              $('#modalUploadItems').find('#uploadPrepareStr').html(htmlValueStr);
              break;
            }
          }
        }*/
}
$('.saveUploadItems').click(function () {
  var countStr = xlsxDataGlobal.length -1;
  for (var i = 0; i < xlsxDataGlobal.length; i++) {
    var counterLCC = 0;
    for (var ii in xlsxDataGlobal[i]) {
      if (counterLCC == 7) {
        xlsxDataGlobal[i][ii] = $('#uploadCountry').val();
      } else if (counterLCC == 8) {
        xlsxDataGlobal[i][ii] = $('#uploadLocality').val();
      } else if (counterLCC == 9) {
        xlsxDataGlobal[i][ii] = $('#uploadCategory').val();
      } else if (counterLCC == 10) {
        xlsxDataGlobal[i][ii] = $('#uploadAccom').val();
      }
      counterLCC++; // Что то не то считает - калонки а вроде должен строки
    }
  }
  $('#uplpadStringCounterModal').modal('show');
  $('#uplpadStringCounterModal').find('#uplpadStringCounter').text(countStr);
});

$('#uplpadStringCounterBtn').click(function () {
  if ($('#upload_file').val() && ($('#uploadCategory').val() != '_none_') && ($('#uploadCountry').val() != '_none_') && ($('#uploadAccom').val() != '_none_')) {
    var countStr = xlsxDataGlobal.length -1;
    for (var i = 0; i < xlsxDataGlobal.length; i++) {
      var counterLCC = 0;
      for (var ii in xlsxDataGlobal[i]) {
        if (counterLCC == 7) {
          xlsxDataGlobal[i][ii] = $('#uploadCountry').val();
        } else if (counterLCC == 8) {
          xlsxDataGlobal[i][ii] = $('#uploadLocality').val();
        } else if (counterLCC == 9) {
          xlsxDataGlobal[i][ii] = $('#uploadCategory').val();
        } else if (counterLCC == 10) {
          xlsxDataGlobal[i][ii] = $('#uploadAccom').val();
        }
        counterLCC++;
      }
    }
    var y = JSON.stringify(xlsxDataGlobalReg);
    var x = JSON.stringify(xlsxDataGlobal);
    $.post('/ajax/excelUpload.php', {xlsx_array: x, xlsx_array_reg: y})
    .done(function(data){
      //console.log(data);
    });
    $('#modalUploadItems').modal('hide');
    setTimeout(function () {
        loadDashboard();
        showHint('Обработо '+countStr+' строк');
    }, 300);
  } else {
    $('#uploadMsgError').text('Местность, категория или файл не выбраны.');
  }
});

$('#upload_file').change(function() {
  $('#uploadMsgError').text('');
  $('#uploadBtn').click();
  if ($('#upload_file').val()) {
    setTimeout(function () {
      $('.saveUploadItems').attr('disabled', false);
    }, 2500);
  }
});

$('form').on('submit', function (e) {
    e.preventDefault();
    // logic
    $.ajax({
        url: this.action,
        type: this.method,
        data: new FormData(this), // important
        processData: false, // important
        contentType: false, // important
        success: function (res) {
          xlsxDataGlobal = res;
          console.log(xlsxDataGlobal);
        }
    });

    $.ajax({
        url: this.action,
        type: this.method,
        data: new FormData(this), // important
        processData: false, // important
        contentType: false, // important
        success: function (res) {
          xlsxDataGlobalReg = res;
          //console.log(xlsxDataGlobalReg);
        }
    });

    setTimeout(function () {
      if (xlsxDataGlobal[0].length < 13) {
        $('#uploadMsgError').text('Не достаточно полей в файле.');
        return
      }
      getUpdaterEditor(xlsxDataGlobal);
      //console.log(xlsxDataGlobal);
      getUpdaterEditorForRegTbl(xlsxDataGlobalReg);
      //console.log(xlsxDataGlobalReg);
    }, 2500);
});

$('#modalUploadItems').on('show', function () {
  $('.saveUploadItems').attr('disabled', 'disabled');
  $('#upload_file').val('');
  $('#uploadCategory').val('_none_');
  $('#uploadLocality').val('_none_');
  $('#uploadAccom').val('_none_');
  $('#uploadPrepare').html('');
});
