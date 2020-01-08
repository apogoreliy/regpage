//представление и пользовательское взаимодействие;
//управление данными;
//общее состояние приложения;
//настройка и код-прослойка, чтобы все части работали вместе;

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


// START UPLOADING FILES \|/|\|/|\|/|
var xlsxDataGlobal = [], xlsxDataGlobalReg = [];

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
    } else {
      for (var key in item) {
        if (ii === 3) {
          var y = item[key];
          var x = item[key-1];
          var z = x + ' ' + y;
          item[key-1] = z;
          item[key] = null;
        }
        ii++;
      }
    }
  }
  console.log(array);
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
      counterLCC++; // Что то не то считает - колонки, а вроде должен строки
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
    console.log(xlsxDataGlobal);
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
  $('.saveUploadItems').attr("disabled", true);
});

//START NEW UPLOAD BUTTON
function prepareArrayUpload(array) {
// количество элементов массива должны соответствовать количеству элементов в массиве с данными
  xlsxDataGlobal.unshift(fields);
};

$('.saveUploadItemsNew').click(function () {
  if (($('#uploadCountry').val() === '_none_') && ($('#citizenshipGlobalUpload').next().val() === '' || $('#citizenshipGlobalUpload').next().val() === '_none_') || ($('#uploadLocality').val() === '_none_') && ($('#localityGlobalUpload').next().val() === '' || $('#localityGlobalUpload').next().val() === '_none_') || ($('#uploadCategory').val() === '_none_') && ($('#categoryGlobalUpload').next().val() === '' || $('#categoryGlobalUpload').next().val() === '_none_') || ($('#nameGlobalUpload').next().val() === '') || ($('#nameGlobalUpload').next().val() === '_none_')) {
    showError('Заполните обязательные поля отмеченные звёздочкой* и поле ФИО.');
    return false
  }

  var fields = [], first = [], left = [], right = [], a;
// можно создать ассоциативный массив это будет проще
// 1, 4, 5 и 6 обязательные для заполнения либо из соответствовующих комбобоксов глобальными значениями
// то есть если глобальные селекты пусты то обязательно к заполению соответствующее поле, и обводить всё красным и после исправления красное убирать
// Данные проверять именно при выборе поля в комбобоксе дата, число (номер), буквы, соответствие списку
  $('#modalUploadItems').find('select').each(function () {
    if ($(this).hasClass('float-left')) {
      fields[$(this).val()] = 0;
      a = $(this).val();
    } else if ($(this).hasClass('float-right')) {
      fields[a] = $(this).val();
    } else {
      first.push($(this).val());
    }
    /*if ($(this).val() !== '_none_') {

    }*/
  });
  console.log(fields,first);
//  prepareArrayUpload(fields);

  /*
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
    console.log(xlsxDataGlobal);
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
  $('.saveUploadItems').attr("disabled", true);
  */
});
//STOP NEW UPLOAD BUTTON

$('#upload_file').change(function() {
  $('#uploadMsgError').text('');
  $('#uploadBtn').click();
  if ($('#upload_file').val()) {
    $('#uploadStringsShow').html('');
    $('#uploadStringsChkbx').attr('disabled', true);
    $('.saveUploadItems').attr('disabled', true);
    setTimeout(function () {
      $('.saveUploadItems').attr('disabled', false);
      $('#uploadStringsChkbx').attr('disabled', false);
    }, 2500);
  }
});

function buildModalSelect() {
  var option = {genger: 'Пол', birth: 'Дата рождения', accom: 'Размещение', arrive: 'Прибытие', depart: 'Убытие', email: 'Емайл', parking: 'Парковка', russpeaking: 'Рускоговорящий?', attended: 'Полное участие?', vuz: 'ВУЗ', course: 'КУРС', phone: 'Телефон', telegramm: 'Телеграмм', otime: 'Отметка времени', other: 'Иное'};
  var elements = [];
  for (var i = 0; i < Object.keys(option).length; i++) {
    var options = [], conterForSelected = 0;
    conterForSelected = conterForSelected + i + 1;
    conterOptionTemp = 0;
      for (var variable in option) {
        if (option.hasOwnProperty(variable)) {
          conterOptionTemp++;
          if (conterOptionTemp == conterForSelected) {
            options.push('<option value="'+variable+'" selected>'+option[variable]+'</option>');
          } else {
            options.push('<option value="'+variable+'">'+option[variable]+'</option>');
          }
        }
      }
      elements.push('<select class="float-left" name=""><option value="_none_"></option>');
      var optionsString = options.join('');
      elements.push(optionsString);
      elements.push('</select><select class="float-right upload_fields" name=""></select>');
    }
  var elementsString =  elements.join('');
  $('#newuploadBoard').append(elementsString);
}
buildModalSelect();

function newFileUploader(xlsxData) {
  var uploadedFieldOptions = [];
  for (var i = 0; i < 1; i++) {
    for (var j = 0; j < xlsxData[i].length; j++) {
      j == 0 ? uploadedFieldOptions.push('<option value="_none_" selected></option>') :'';
      xlsxData[i][j] != null ? uploadedFieldOptions.push('<option value="'+j+'">'+xlsxData[i][j]+'</option>'):'';
    }
  }
  var uploadedFieldOptionsString = uploadedFieldOptions.join('');
  $('#newuploadBoard').find('.upload_fields').each(function () {
    $(this).html(uploadedFieldOptionsString);
  });
  console.log(xlsxData);
}
// START UPLOAD BAR. CHECK SELECT FIELDS FUNCTION
function checkSelect(value, el, txt) {
  var a = [];
  $('#newuploadBoard select').each(function () {
    if (!$(this).hasClass('upload_fields')) {
      a.push($(this).val());
    }
  });
  var counter = 0;
  a.forEach(function (item, index) {
    if (item === value) {
      counter++;
      if ((counter > 1) && (el.val() !== '_none_') && (el.val() !== 'other')) {
        el.val('_none_');
        showError('Поле '+txt+' уже выбрано.')
      }
    }
  });
}

function checkSelectGlobalValue() {
  if ($('#uploadCountry').val() !== '_none_') {
    $('#citizenshipGlobalUpload').attr('disabled', true);
    $('#citizenshipGlobalUpload').next().attr('disabled', true);
    $('#citizenshipGlobalUpload').next().val('_none_');
  } else {
    $('#citizenshipGlobalUpload').attr('disabled', false);
    $('#citizenshipGlobalUpload').next().attr('disabled', false);
  }
  if ($('#uploadLocality').val() !== '_none_') {
    $('#localityGlobalUpload').attr('disabled', true);
    $('#localityGlobalUpload').next().attr('disabled', true);
    $('#localityGlobalUpload').next().val('_none_');
  } else {
    $('#localityGlobalUpload').attr('disabled', false);
    $('#localityGlobalUpload').next().attr('disabled', false);
  }
  if ($('#uploadCategory').val() !== '_none_') {
    $('#categoryGlobalUpload').attr('disabled', true);
    $('#categoryGlobalUpload').next().attr('disabled', true);
    $('#categoryGlobalUpload').next().val('_none_');
  } else {
    $('#categoryGlobalUpload').attr('disabled', false);
    $('#categoryGlobalUpload').next().attr('disabled', false);
  }
}
$('#newuploadBoard select').change(function () {
  if (!$(this).hasClass('upload_fields')) {
    checkSelect($(this).val(),$(this), $(this).find('option:selected').text());
  } else if ($(this).hasClass('upload_fields')) {
    if ($(this).prev().attr('id') === 'nameGlobalUpload') {
      // Сделать универсальную функцию для проверки
      var g = 0, err = 0;
      var f = Number($(this).val());
      xlsxDataGlobal.forEach(function (arr) {
        if (g !== 0) {
          if (/\d/.test(arr[f+g])) {
            showError('Поле имя не должно содержать цифры.');
            err = 1;
          }
        }
        if (err === 1) {
          return
        }
        g=g+30;
      });
      if (err === 1) {
        $(this).val('_none_');
      }
    } else if ($(this).prev().attr('id') === 'localityGlobalUpload') {
      // Сделать универсальную функцию для проверки
      var g = 0, err = 0, loc =[];
      var f = Number($(this).val());
      $('#uploadLocality option').each(function () {
        loc.push($(this).text());
      });
      // нужно добавлять новые местности в новые местности, а undefined проверять на пустую запись, которых может быть по 500 штук
      xlsxDataGlobal.forEach(function (arr) {
        if (g !== 0) {
            if (loc.indexOf(arr[f+g]) === -1 && arr[f+g] !== undefined) {
              showError('Местности <b>'+arr[f+g]+'</b> нет в списке.');
              err = 1;
            }
        }
        if (err === 1) {
          return
        }
        g=g+30;
      });
      if (err === 1) {
        $(this).val('_none_');
      }
    } else if ($(this).prev().find('option:selected').val() === 'email') {
      var g = 0, err = 0;
      var f = Number($(this).val());
      xlsxDataGlobal.forEach(function (arr) {
        if (g !== 0) {
          if (!(/@/.test(arr[f+g])) || arr[f+g] === '') {
            showError('Некорректные адреса Email.');
            err = 1;
          }
        }
        if (err === 1) {
          return
        }
        g=g+30;
      });
    } else if ($(this).prev().find('option:selected').val() === 'phone') {
      var g = 0, err = 0;
      var f = Number($(this).val()-3);
      xlsxDataGlobal.forEach(function (arr) {
        if (g !== 0) {
          if (!(/^\s*\+?\d+[^a-z]/.test(arr[f+g])) || arr[f+g] === '') {
//Доделать
            showError('Некорректные номера телефонов.');
            err = 1;
          }
        }
        if (err === 1) {
          return
        }
        g=g+30;
      });
    }
    if (err === 1) {
      $(this).val('_none_');
    }
  }
});
$('#globalValueForFields select').change(function () {
    checkSelectGlobalValue();
});
// STOP UPLOAD BAR. SELECT CHECK FUNCTION
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
    $('#psevdoSpiner').show();
    $('.loader_weel').show();
    setTimeout(function () {
      if (xlsxDataGlobal[0].length < 13) {
        $('#uploadMsgError').text('Не достаточно полей в файле.');
        $('#psevdoSpiner').hide();
        $('.loader_weel').hide();
        return
      }
      getUpdaterEditor(xlsxDataGlobal);
      //console.log(xlsxDataGlobal);
      getUpdaterEditorForRegTbl(xlsxDataGlobalReg);
      //console.log(xlsxDataGlobalReg);
      newFileUploader(xlsxDataGlobal); // REBUILD IT
      stringPrepareForShow(xlsxDataGlobal);
      $('#psevdoSpiner').hide();
      $('.loader_weel').hide();
      collectString();
    }, 2500);
});

$('#modalUploadItems').on('show', function () {
  $('#uploadStringsShow').html('');
  $('#psevdoSpiner').hide();
  $('.loader_weel').hide();
  $('.saveUploadItems').attr('disabled', 'disabled');
  $('#upload_file').val('');
  $('#uploadCategory').val('_none_');
  $('#uploadLocality').val('_none_');
  $('#uploadAccom').val('_none_');
  $('#uploadPrepare').html('');
});

// START strings builder
$('#uploadStringsShow').hide();
$('#uploadStringsChkbx').change(function () {
  $(this).prop('checked') ? $('#uploadStringsShow').show() : $('#uploadStringsShow').hide();
})


function stringPrepareForShow(xlsxData) {

    var uploadedStrings = [];
    for (var i = 0; i < xlsxData.length; i++) {
      if (i != 0) {
        var itemStr = xlsxData[i];
        var uuu = [];
        var counter = 0;
        for (var varvar in itemStr) {
          if (counter === 2 || counter === 3 || counter === 11) {
            uuu.push(itemStr[varvar]);
          }
          counter++;
        }
        uploadedStrings.push('<span class="stringShow"><span class="string_name_upload">'+uuu[0]+', '+uuu[1]+', '+uuu[2]+' </span><span class="denyThisString"> X</span></span><br>');
      }
    }
    var uploadedFieldOptionsString = uploadedStrings.join('');
      $('#uploadStringsShow').html(uploadedFieldOptionsString);

      $('.denyThisString').click(function () {
        if (!$(this).parent().find('.string_name_upload').hasClass('deny_string')) {
          $(this).parent().find('.string_name_upload').addClass('deny_string');
          $(this).html('V');
        } else {
          $(this).parent().find('.string_name_upload').removeClass('deny_string');
          $(this).html('X');
        }
      })
}
// START NEW FUN CHEK DELETED STRING and compare them with GENERAL array
function collectString() {
  var arrStr = [];
  $('.string_name_upload').each(function () {
    if (!$(this).hasClass('deny_string')) {
      var a = $(this).text();
      a = a.split(', ');
      arrStr.push(a);
    }
  });
  console.log(arrStr);
}

// STOP strings builder
// START Checking forms for valid
// STOP Checking forms for valid

// START NEW FUN CHEK DELETED STRING and compare them with GENERAL array

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

// 1 раздел выдача результатов запроса
// 2 Фильтры и сортировки без запросов в базу данных
// 3 правка и удаление записей
// 4 пакетная правка записей
// 5 раздел выдача результатов вспомогательных или связанных запросов (массивы)
// вормирования Гридов и прочих представлений данных для основного списка
// вормирования Гридов и прочих представлений данных для вспомогательных списков
/*
function Mag(name) {
  this.name = name;
  this.hit = 10;
  this.armor = 5;
  this.health = 50;
  this.looky = 0;
  this.target = function (a) {
    var rndb = Math.floor(Math.random() * 10)
    if ((this.armor + rndb - a) >= 0) {
      return this.health;
    } else {
      return this.health = this.health + (this.armor + rndb - a);
    }
  };
}
var mag_1 = new Mag('Yoksel');
var mag_2 = new Mag('Moxell');
var co = 0;
while (co < 100) {
  console.log(mag_2.name ,' health is ', mag_2.target(mag_1.hit));
  console.log(mag_1.name ,' health is ', mag_1.target(mag_2.hit));
  co++;
  if ((mag_1.health < 0) || (mag_2.health < 0)) {
    mag_1.health < 0 ? console.log(mag_2.name, ' won!') : console.log(mag_1.name, ' won!');
    break;
  }
}
*/
// Hide Global Error
$('#globalError').find('.close-alert').click(function () {
  $('#globalError').hide();
});
