// START UPLOADING FILES \|/|\|/|\|/|
var xlsxDataGlobal = [], xlsxDataGlobalReg = [], usefulData = {}, uploadWindow = {};
// START OBJECT FOR MODAL WINDOW
uploadWindow = {

}
// localityArr это повторение но нужно использовать объект usefulData
// ПОМЕСТИТЬ ФУНКЦИИ ДЛЯ ФОРМИРОВАНИЯ МАССИВОВ ДАННЫХ В ОБЪЕКТ ПЕРЕДАВАТЬ В ПАРАМЕТРАХ УНИВЕРСАЛЬНЫЕ УСЛОВИЯ И ТЭГИ
// selectors - string
usefulData.funFulfilInvert = function(listIdStr, selectorsStr, invert, ifNot1Str, orIfNot2Str) {
  var arr = new Map();
  // Использовать функции для вывода свойств объекта (елементов). В функции можно задать параметры и сделать вывод определённых значений массива, согласно заданому параметру, как условию.
  $(selectorsStr).each(function() {
    if ((ifNot1Str && orIfNot2Str && $(this).val() !== ifNot1Str && $(this).text() !== orIfNot2Str) || (ifNot1Str && !orIfNot2Str && $(this).val() !== ifNot1Str)) {
      invert ? arr[$(this).text().toLowerCase()] = $(this).val() : arr[$(this).val()] = $(this).text();
    }
  });
  usefulData[listIdStr] = arr;
};
usefulData.funFulfilInvert('conditions','#eventTabs .filter-regstate option', 0 ,'_all_');
usefulData.funFulfilInvert('events', '#eventTabs #events-list option', 0, '_none_');
usefulData.funFulfilInvert('citizenshipInvert', '#modalEditMember .emCitizenship option', 1 ,'_none_','---------------------------');
usefulData.funFulfilInvert('localityInvert', '#uploadLocality option', 1, '_none_');
usefulData.funFulfilInvert('categoryInvert', '#uploadCategory option', 1, '_none_');

$(".uploadExl").unbind('click');
$(".uploadExl").click(function(event){
    event.stopPropagation();
    $('#modalUploadItems').modal().show();
});
// START ???
function getUpdaterEditor(array) {

  console.log(array);
  uploadTableBuilder (array);
}
// STOP ???
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
}

function prepareArrayUpload(array) {
  xlsxDataGlobal.unshift(fields);
};
//START SAFE FUNCTION NEW UPLOAD BUTTON
$('.saveUploadItemsNew').click(function () {
  if (($('#uploadCountry').val() === '_none_') && ($('#citizenshipGlobalUpload').next().val() === '' || $('#citizenshipGlobalUpload').next().val() === '_none_') || ($('#uploadLocality').val() === '_none_') && ($('#localityGlobalUpload').next().val() === '' || $('#localityGlobalUpload').next().val() === '_none_') || ($('#uploadCategory').val() === '_none_') && ($('#categoryGlobalUpload').next().val() === '' || $('#categoryGlobalUpload').next().val() === '_none_') || ($('#uploadAccom').val() === '_none_') && ($('#accomGlobalUpload').next().val() === '' || $('#accomGlobalUpload').next().val() === '_none_') || ($('#nameGlobalUpload').next().val() === '') || ($('#nameGlobalUpload').next().val() === '_none_')) {
    showError('Заполните обязательные поля отмеченные звёздочкой* и поле ФИО.');
    return false
  }

  var fields = [], first = [], left = [], right = [], a;

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

  var errorUpload = 0, newArrForServer = [];
  function prepareArrayUploadNew(fieldsSelected,fieldsGlobal) {
    var fieldsCompare = [], newArrForServerReg = [], fieldsExist = ['other', 'email', 'name', 'birth', 'genger', 'phone', 'citizenship', 'locality', 'category', 'accom', 'arrive', 'depart', 'parking', 'russpeaking', 'other2', 'vuz', 'other4', 'other1', 'other3'], columnsNo = [];
    function toTrimAndToLower(x) {
      var y = x ? x.trim() : x;
      y = y && isNaN(y) ? y.toLowerCase() : y;
      return y;
    }
// loop
/*
    for (var key_i in fieldsSelected) {
      columnsNo[key_i] = Number(fieldsSelected[key_i]);
    };
console.log(columnsNo);
*/
// loop
    var aa = fieldsSelected['other'] !== '_none_' ? fieldsSelected['other'] : '';
    var b = fieldsSelected['email'] !== '_none_' ? fieldsSelected['email'] : '';
    var bb = fieldsSelected['name'] !== '_none_' ? fieldsSelected['name'] : '';
    var bb1 = fieldsSelected['name1'] !== '_none_' ? fieldsSelected['name1'] : '';
    var bb2 = fieldsSelected['name2'] !== '_none_' ? fieldsSelected['name2'] : '';
    var c = fieldsSelected['birth'] !== '_none_' ? fieldsSelected['birth'] : '';
    var d = fieldsSelected['vuz'] !== '_none_' ? fieldsSelected['vuz'] : ''; // ???
    var e = fieldsSelected['genger'] !== '_none_' ? fieldsSelected['genger'] : '';
    var f = fieldsSelected['phone'] !== '_none_' ? fieldsSelected['phone'] : '';
    var g = fieldsSelected['citizenship'] !== '_none_' ? fieldsSelected['citizenship'] : '';
    var h = fieldsSelected['locality'] !== '_none_' ? fieldsSelected['locality'] : '';
    var j = fieldsSelected['category'] !== '_none_' ? fieldsSelected['category'] : '';
    var k = fieldsSelected['accom'] !== '_none_' ? fieldsSelected['accom'] : '';
    var l = fieldsSelected['arrive'] !== '_none_' ? fieldsSelected['arrive'] : '';
    var m = fieldsSelected['depart'] !== '_none_' ? fieldsSelected['depart'] : '';
    var n = fieldsSelected['parking'] !== '_none_' ? fieldsSelected['parking'] : '';
    var o = fieldsSelected['russpeaking'] !== '_none_' ? fieldsSelected['russpeaking'] : '';
    var p = fieldsSelected['other1'] !== '_none_' ? fieldsSelected['other1'] : '';
    var q = fieldsSelected['other2'] !== '_none_' ? fieldsSelected['other2'] : ''; // ???
    var r = fieldsSelected['other3'] !== '_none_' ? fieldsSelected['other3'] : '';
    var s = fieldsSelected['other4'] !== '_none_' ? fieldsSelected['other4'] : '';
    //var t = fieldsSelected['other'] !== '_none_' ? fieldsSelected['other'] : ''; // ???

// START Custom stream
    var arrStr01 = [0];
    if ($('#uploadStringsChkbx').prop('checked')) {
      var counterCustomStr = 1;
      $('.string_name_upload').each(function () {
        if (!$(this).hasClass('deny_string')) {
          arrStr01.push(counterCustomStr);
        }
        counterCustomStr++;
      });
    }

// STOP Custom stream
    for (var i = 0; i < xlsxDataGlobal.length; i++) {
      var tmpArr = [], female, tmpArrReg = [];
        var y;
        if (fieldsSelected['name1'] !== '_none_') {
          y = xlsxDataGlobal[i][bb1];
          xlsxDataGlobal[i][bb] = xlsxDataGlobal[i][bb] + ' ' + xlsxDataGlobal[i][bb1];
          if (fieldsSelected['name2'] !== '_none_') {
            xlsxDataGlobal[i][bb] = xlsxDataGlobal[i][bb] + ' ' + xlsxDataGlobal[i][bb2];
          }
        } else {
          y = xlsxDataGlobal[i][bb];
        }
// Доделать
      if (fieldsSelected['genger'] === '_none_') {
        y ? toTrimAndToLower(y) : '';
         //var x = xlsxDataGlobal[i][bb];
         var u;
         if (y) {
           u = y.slice(-1);
         }
         if ((u == 'а') || (u == 'я') || (u == 'э') || (u == 'е')) {
           female = 0;
         } else {
           female = 1;
         }
       }

// Здесь порядок соответствует порядку запроса на сервере
// заменить пеменные объекты
      var otherCollect = ' ', letterArr = [s,r,q,p,aa];
      for (var ij = 0; ij < letterArr.length; ij++) {
        var colNo = letterArr[ij];
        if (xlsxDataGlobal[i][colNo]) {
          otherCollect = otherCollect + xlsxDataGlobal[i][colNo] + ' | ';
        }
      }

      tmpArr.push(otherCollect); // comment
      tmpArr.push(xlsxDataGlobal[i][b]); // email
      tmpArr.push(xlsxDataGlobal[i][bb]); // name
      tmpArr.push(xlsxDataGlobal[i][c]); // birth_date
      tmpArr.push(xlsxDataGlobal[i][d]); // college_comment
      if (e) {
        var itemGender;
        itemGender = toTrimAndToLower(xlsxDataGlobal[i][e]);
         if (itemGender && i !== 0) {
           if ((itemGender[0] === 'б' || itemGender[0] === 'м') && itemGender.length < 13) {
             tmpArr.push(1); // male
           } else if ((itemGender[0] === 'ж' || itemGender[0] === 'с') && itemGender.length < 13) {
             tmpArr.push(0); // female
           } else {
               showError('Пол не определён в строке с значением '+itemGender+' . Используйте для обозначений слова: брат/сестра, муж/жен, м/ж, мужской/женский и т.п.');
               errorUpload = 1;
               return
           }
         } else if (i === 0) {
            tmpArr.push('');
         } else {
            showError('В колонке Пол все строки должны быть заполненны.');
            errorUpload = 1;
            return
         }
      } else {
        tmpArr.push(female);
      }

      tmpArr.push(xlsxDataGlobal[i][f]); // cell_phone

      if (first[0] !== '_none_') {
        tmpArr.push(first[0]);
      } else {
        var locKeyCitizenship,
        items = usefulData.citizenshipInvert,
        itemCitizenship = '', itemOurCitizenship = '';
        itemCitizenship = toTrimAndToLower(xlsxDataGlobal[i][g]);

        for (var key in items) {
          itemOurCitizenship = items[key] && isNaN(items[key]) ? items[key].toLowerCase(): items[key];
          if (key === itemCitizenship) {
            locKeyCitizenship = items[key];
          } else if (itemOurCitizenship === itemCitizenship) {
            locKeyCitizenship = items[key];
          }
        }
        if (locKeyCitizenship) {
          tmpArr.push(locKeyCitizenship); // citizenship_key
        } else if (i !== 0) {
          showError('Страна гражданства не определена, или её нет в нашей базе, обратитесь к администратору сайта.');
          errorUpload = 1;
          return
        } else {
          tmpArr.push('');
        }
        // Также нужно искать страну по ключу если кто то будет использовать ключи в экспортируемом файле
      }
      if (first[1] !== '_none_') {
        tmpArr.push(first[1]);
      } else {
        var localityArr = usefulData.localityInvert, locKey = '', localityNew = '', itemLocality = '';

          itemLocality = toTrimAndToLower(xlsxDataGlobal[i][h]);

          for (var key in localityArr) {
            if (key === itemLocality) {
              locKey = localityArr[key];
            }
          }
          if (locKey) {
            tmpArr.push(locKey); // locality_key
            localityNew = '';
          } else {
            locKey = '';
            tmpArr.push(locKey);
            localityNew = xlsxDataGlobal[i][h];
          }
      }
      if (first[2] !== '_none_') {
        tmpArr.push(first[2]);
      } else {
        var itemsCategory = usefulData.categoryInvert, itemCategory = '', itemCategoryTemp='';
         itemCategoryTemp = toTrimAndToLower(xlsxDataGlobal[i][j]);
        for (var key in itemsCategory) {
          itemOurCategory = itemsCategory[key] && isNaN(itemsCategory[key]) ? itemsCategory[key].toLowerCase(): itemsCategory[key];
          if (key === itemCategoryTemp || itemOurCategory === itemCategoryTemp) {
            itemCategory = itemsCategory[key];
          } else if (itemCategoryTemp === 'студент' || itemCategoryTemp === 'студенты') {
            itemCategory = 'ST';
          } else if (itemCategoryTemp === 'школьник' || itemCategoryTemp === 'школьники') {
            itemCategory = 'SC';
          } else if (itemCategoryTemp === 'верующий' || itemCategoryTemp === 'верующие') {
            itemCategory = 'BL';
          } else if (itemCategoryTemp === 'святые' || itemCategoryTemp === 'святые') {
            itemCategory = 'SN';
          }
        }
        if (itemCategory) {
          tmpArr.push(itemCategory); // category_key
        } else if (i !== 0) {
          showError('В базе отсутствует категория: \"'+itemCategoryTemp+'\".');
          newArrForServer.splice(0,newArrForServer.length);
          errorUpload = 1;
          return
        } else {
          tmpArr.push('');
        }
      }
      tmpArr.push(window.adminId); // adminId

      //tmpArr.push(xlsxDataGlobal[i][l]); //
      //tmpArr.push(xlsxDataGlobal[i][m]); //
      //tmpArr.push(xlsxDataGlobal[i][n]); //
      if (o) {
        tmpArr.push(xlsxDataGlobal[i][o]);
      } else {
        tmpArr.push('1');
      }

      // new locality
      tmpArr.push(localityNew);

      //tmpArr.push(xlsxDataGlobal[i][p]); //
      //tmpArr.push(xlsxDataGlobal[i][q]); //
      //tmpArr.push(xlsxDataGlobal[i][r]); //
      //tmpArr.push(xlsxDataGlobal[i][s]); //
      // tmpArr.push(xlsxDataGlobal[i][t]); //

// Доделать добавление строк только отмеченных строк
      if (arrStr01.indexOf(i) !== -1 && $('#uploadStringsChkbx').prop('checked') || (!$('#uploadStringsChkbx').prop('checked'))) {
          newArrForServer.push(tmpArr);
      }

// REG TABLE START
      tmpArrReg.push(xlsxDataGlobal[i][aa]); // other

      var aReg = $('.tab-pane.active').attr('id');
      aReg ? aReg = aReg.slice(9,17) : '';
      tmpArrReg.push(aReg); // id event

      l ? tmpArrReg.push(xlsxDataGlobal[i][l]) : tmpArrReg.push($('.tab-pane.active').attr('data-start'));
      m ? tmpArrReg.push(xlsxDataGlobal[i][m]) : tmpArrReg.push($('.tab-pane.active').attr('data-end'));
      tmpArrReg.push('01');
      tmpArrReg.push(window.adminId);
      tmpArrReg.push($('.tab-pane.active').attr('data-currency'));
      otherCollect ? tmpArrReg.push(otherCollect) : tmpArrReg.push(' ');
      if (first[3] !== '_none_') {
        tmpArrReg.push(first[3]);
      } else {
        var itemAccomTemp = toTrimAndToLower(xlsxDataGlobal[i][k]);
        if (itemAccomTemp === 'да' || itemAccomTemp === 'требуется') {
          tmpArrReg.push(1); // accom
        } else {
          tmpArrReg.push(0);
        }
      }
      tmpArrReg.push(null);
      tmpArrReg.push(null);

      if ((arrStr01.indexOf(i) !== -1 && $('#uploadStringsChkbx').prop('checked')) || (!$('#uploadStringsChkbx').prop('checked'))) {
          newArrForServerReg.push(tmpArrReg);
      }

// REG TABLE STOP
// LOOOOOOOOP
      aa = aa !== '' ? Number(aa) + 30 : '';
      b = b !== '' ? Number(b) + 30 : '';
      bb = bb !== '' ? Number(bb) + 30 : '';
      bb1 = bb1 !== '' ? Number(bb1) + 30 : '';
      bb2 = bb2 !== '' ? Number(bb2) + 30 : '';
      c = c !== '' ? Number(c) + 30 : '';
      d = d !== '' ? Number(d) + 30 : '';
      e = e !== '' ? Number(e) + 30 : '';
      f = f !== '' ? Number(f) + 30 : '';
      g = g !== '' ? Number(g) + 30 : '';
      h = h !== '' ? Number(h) + 30 : '';
      j = j !== '' ? Number(j) + 30 : '';
      k = k !== '' ? Number(k) + 30 : '';
      l = l !== '' ? Number(l) + 30 : '';
      m = m !== '' ? Number(m) + 30 : '';
      n = n !== '' ? Number(n) + 30 : '';
      o = o !== '' ? Number(o) + 30 : '';
      p = p !== '' ? Number(p) + 30 : '';
      q = q !== '' ? Number(q) + 30 : '';
      r = r !== '' ? Number(r) + 30 : '';
      s = s !== '' ? Number(s) + 30 : '';
      // t = t !== '' ? Number(t) + 30 : '';
    }

// FOR REG TABLE

    console.log(newArrForServer);
    if (errorUpload === 0) {
      var y = JSON.stringify(newArrForServerReg);
      var x = JSON.stringify(newArrForServer);
      $.post('/ajax/excelUpload.php', {xlsx_array: x, xlsx_array_reg: y})
      .done(function(data){
        //console.log(data);
      });
    }
  }
  prepareArrayUploadNew(fields,first);
  if (errorUpload === 0) {
    var countStrTotal = newArrForServer ? newArrForServer.length - 1 : 0;
    $('#modalUploadItems').modal('hide');
      setTimeout(function () {
        loadDashboard();
        showHint('Обработано '+countStrTotal+' строк');
      }, 350);
  }

});
// STOP SAFE FUNCTION NEW UPLOAD BUTTON
// START UPLOAD FILE
$('#upload_file').change(function() {
  // ВЫКЛЮЧИТЬ БЛОКИРОВКУ С ПРОШЛЫХ НАСТРОЕК, НО, ВОЗМОЖНО ЛУЧШЕ ОСТАВИТЬ ГЛОБАЛЬНЫЕ НАСТРОЙКИ. УТОЧНИТЬ.

  $('#globalValueForFields').find('select').each(function () {
    $(this).attr('disabled', false);
  });

  $('#newuploadBoard').find('select').each(function () {
    if ($(this).hasClass('float-right')) {
      $(this).val('_none_');
    }
  });

  $('#uploadMsgError').text('');
  $('#uploadBtn').click();
  if ($('#upload_file').val()) {
    $('#uploadStringsShow').html('');
    $('#uploadStringsChkbx').attr('disabled', true);
    $('.saveUploadItemsNew').attr('disabled', true);
    setTimeout(function () {
      $('.saveUploadItemsNew').attr('disabled', false);
      $('#uploadStringsChkbx').attr('disabled', false);
    }, 2500);
  }
});
// STOP UPLOAD FILE
// START SELECT FIELDS BUILDER UPLOAD
function buildModalSelect() {
  var option = {genger: 'Пол', birth: 'Дата рождения', arrive: 'Прибытие', depart: 'Убытие', email: 'Емайл', phone: 'Телефон', parking: 'Парковка', russpeaking: 'Рускоговорящий?', vuz: 'Примечение к ВУЗу', other4: 'Прочее', other3: 'Прочее', other1: 'Прочее', other2: 'Прочее', other: 'Прочее'};
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

// STOP SELECT FIELDS BUILDER UPLOAD
function newFileUploader(xlsxData) {
  var uploadedFieldOptions = [];
  for (var i = 0; i < 1; i++) {
    var ccounter = 0;
    for (var j = 0; j < xlsxData[i].length; j++) {
      j == 0 ? uploadedFieldOptions.push('<option value="_none_" selected></option>') :'';
      xlsxData[i][j] != null ? uploadedFieldOptions.push('<option value="'+ccounter+'">'+xlsxData[i][j]+'</option>'):'';
      xlsxData[i][j] != null ? ccounter++ : '';
    }
  }
  var uploadedFieldOptionsString = uploadedFieldOptions.join('');
  $('#newuploadBoard').find('.upload_fields').each(function () {
    $(this).html(uploadedFieldOptionsString);
  });
  console.log(xlsxData);
}
// START SELECT FIELDS BEHAVIOR UPLOAD
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

function checkSelectGlobalValue(e) {
  // LOOP передать событие вфунцию и извлечь селектор или ID и состояние
  if ($('#uploadCountry').val() !== '_none_') {
    $('#citizenshipGlobalUpload').attr('disabled', true);
    $('#citizenshipGlobalUpload').next().attr('disabled', true);
  } else {
    $('#citizenshipGlobalUpload').attr('disabled', false);
    $('#citizenshipGlobalUpload').next().attr('disabled', false);
  }
  if ($('#uploadLocality').val() !== '_none_') {
    $('#localityGlobalUpload').attr('disabled', true);
    $('#localityGlobalUpload').next().attr('disabled', true);
  } else {
    $('#localityGlobalUpload').attr('disabled', false);
    $('#localityGlobalUpload').next().attr('disabled', false);
  }
  if ($('#uploadCategory').val() !== '_none_') {
    $('#categoryGlobalUpload').attr('disabled', true);
    $('#categoryGlobalUpload').next().attr('disabled', true);
  } else {
    $('#categoryGlobalUpload').attr('disabled', false);
    $('#categoryGlobalUpload').next().attr('disabled', false);
  }
  if ($('#uploadAccom').val() !== '_none_') {
    $('#accomGlobalUpload').attr('disabled', true);
    $('#accomGlobalUpload').next().attr('disabled', true);
  } else {
    $('#accomGlobalUpload').attr('disabled', false);
    $('#accomGlobalUpload').next().attr('disabled', false);
  }
}
    // START CHECKING SELECT CONTENT
$('#newuploadBoard select').change(function () {
  var lArr = xlsxDataGlobal[0] ? xlsxDataGlobal[0].length : 0;
  var f = Number($(this).val());
  var g = 0, err = 0, msgErr = '';
  // ОДНИ И ТЕ ЖЕ ПЕРЕМЕННЫЕ ОБЪЯВЛЯЮТСЯ ПО СТО РАЗ В ОДНОЙ И ТОЙ ЖЕ ЗОНЕ ВИДИМОСТИ


  if (!$(this).hasClass('upload_fields')) {
    checkSelect($(this).val(),$(this), $(this).find('option:selected').text());
  } else if ($(this).hasClass('upload_fields')) {
    if ($(this).prev().attr('id') === 'nameGlobalUpload') {
      // Сделать универсальную функцию для проверки
      xlsxDataGlobal.forEach(function (arr) {
        if (g !== 0) {
          if (/\d/.test(arr[f+g])) {
            msgErr ='Поле имя не должно содержать цифры.';
          }
        }
        if (msgErr) {
          return
        }
        g=g+lArr;
      });
    } else if ($(this).prev().attr('id') === 'localityGlobalUpload') {
      // Сделать универсальную функцию для проверки
      var loc =[];
      $('#uploadLocality option').each(function () {
        loc.push($(this).text());
      });
      // нужно добавлять новые местности в новые местности, а undefined проверять на пустую запись, которых может быть по 500 штук
      xlsxDataGlobal.forEach(function (arr) {
        if (g !== 0) {
            if (loc.indexOf(arr[f+g]) === -1 && arr[f+g] !== undefined) {
// CHECKING new locality
// Знаки должны игнорироваться и фамилии и имена тоже не должны попадать ВОЗМОЖНО исключить повторное использование опций в комбобоксах в загружаемых файлах
              var localityCheck = String(arr[f+g]);
              localityCheck.trim();
              var regular = /^\d|\d{3}/;
              var digit = regular.test(localityCheck);
              var lengthStr = localityCheck.length > 45;
              if (digit || lengthStr) {
                msgErr = 'Местности <b>'+arr[f+g]+'</b> нет в списке.';
              }
              //add 99 befor new locality (not here because it checking only)
            }
        }
        if (msgErr) {
          return
        }
        g=g+lArr;
      });
    } else if ($(this).prev().find('option:selected').val() === 'email') {
      var counter = 1;
      xlsxDataGlobal.forEach(function (arr) {
        if (g !== 0) {
          //console.log(/.+@.+\..+/.test(arr[f+g]), ', ',arr[f+g]);
          if (!(/.+@.+\..+/.test(arr[f+g]))) {
            var dltTrim = arr[f+g];
            arr[f+g] ? dltTrim = String(dltTrim).trim() : dltTrim = arr[f+g];
            if (dltTrim) {
              msgErr = 'Некорректные адреса Email.';
              return
            } else {
              counter++
              if (xlsxDataGlobal.length === counter) {
                msgErr = 'В выбранном поле отсутствуют записи.';
                return
              }
            }
          }
        }
        g=g+lArr;
      });
    } else if ($(this).prev().find('option:selected').val() === 'phone') {
      xlsxDataGlobal.forEach(function (arr) {
        if (g !== 0) {
          if (!(/^\s*\+?\d+[^a-z]/.test(arr[f+g])) || arr[f+g] === '') {
            msgErr = 'Некорректные номера телефонов.';
          }
        }
        if (msgErr) {
          return
        }
        g=g+lArr;
      });
    } else if ($(this).prev().attr('id') === 'citizenshipGlobalUpload') {
// citizenship checking should be here
// МОЖНО Использовать КАК БАЗОВАЮ ПРОВЕРКУ И РАСШИРЕНИЯ
      xlsxDataGlobal.forEach(function (arr) {
        if (g !== 0) {
          if (/\d/.test(arr[f+g])) {
            msgErr ='Поле Гражданство не должно содержать цифры.';
          }
        }
        if (msgErr) {
          return
        }
        g=g+lArr;
      });
    } else if ($(this).prev().find('option:selected').val() === 'genger') {
// genger checking should be here
    } else if (($(this).prev().find('option:selected').val() === 'arrive') || ($(this).prev().find('option:selected').val() === 'depart')) {
// arive AND depart checking should be here
    } else if ($(this).prev().find('option:selected').val() === 'russpeaking') {
// russpeaking checking should be here
    } else if ($(this).prev().find('option:selected').val() === 'birth') {
// birthday checking should be here
    } else if ($(this).prev().attr('id') === 'categoryGlobalUpload') {
// category checking should be here
    } else if ($(this).prev().attr('id') === 'accomGlobalUpload') {
// accom checking should be here
      var accomCheck;
      xlsxDataGlobal.forEach(function (arr) {
        arr[f+g] ? accomCheck = arr[f+g].toLowerCase() : '';
        if (g !== 0) {
          if (!(accomCheck === 'да' || accomCheck === 'требуется' || accomCheck === 'нужно' || accomCheck === 'нет' || accomCheck === 'не требуется' || accomCheck === 'не нужно')) {
            msgErr = 'Поле размещение должно быть типа Да/Нет ИЛИ Требуется/Не требуется, ИЛИ Нужно/Не нужно.'
          }
        }
        if (msgErr) {
          return
        }
        g=g+lArr;
      });
    }
    if (msgErr) {
      // попробовать новую функцию и в нее передавать текст ошибки + прерывать процесс через ретюрн если возможно
      $(this).val('_none_');
      showError(msgErr);
      return
    }
  }
});
    // START CHECKING SELECT CONTENT
    //???
$('#globalValueForFields select').change(function(e) {
    checkSelectGlobalValue(e);
});
$('#citizenshipGlobalUploadVal, #localityGlobalUploadVal, #categoryGlobalUploadVal, #accomGlobalUploadVal').change(function() {
    //checkSelectGlobalValue(e);
    // LOOP передать событие вфунцию и извлечь селектор или ID и состояние
    if ($('#citizenshipGlobalUploadVal').val() !== '_none_') {
      $('#uploadCountry').attr('disabled', true);
    } else {
      $('#uploadCountry').attr('disabled', false);
    }
    if ($('#localityGlobalUploadVal').val() !== '_none_') {
      $('#uploadLocality').attr('disabled', true);
    } else {
      $('#uploadLocality').attr('disabled', false);
    }
    if ($('#categoryGlobalUploadVal').val() !== '_none_') {
      $('#uploadCategory').attr('disabled', true);
    } else {
      $('#uploadCategory').attr('disabled', false);
    }
    if ($('#accomGlobalUploadVal').val() !== '_none_') {
      $('#uploadAccom').attr('disabled', true);
    } else {
      $('#uploadAccom').attr('disabled', false);
    }
});
  // STOP SELECT FIELDS BEHAVIOR UPLOAD

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
      var fieldsCount = xlsxDataGlobal[0] ? xlsxDataGlobal[0].length : 0;
      if (fieldsCount < 1) {
        $('#uploadMsgError').text('Файл не должен быть пустым');
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

  $('#upload_file').val('');
  $('#uploadStringsShow').html('');
  $('#psevdoSpiner').hide();
  $('.loader_weel').hide();
  $('.saveUploadItemsNew').attr('disabled', 'disabled');

  $('#globalValueForFields').find('select').each(function () {
    $(this).val('_none_');
    $(this).attr('disabled', false);
  });

  $('#newuploadBoard').find('select').each(function () {
    if ($(this).hasClass('float-right')) {
      $(this).html('');
      $(this).prev().attr('disabled', false);
      $(this).attr('disabled', false);
    }
  });
});
/*
$('#modalUploadItems').on('hide', function () {

});
*/
  // START strings builder UPLOAD
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
  // START NEW FUN CHEK DELETED STRING and compare them with GENERAL array UPLOAD
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
  // STOP strings builder UPLOAD
// START CHOISE TOOLTIP FOR COMBOBOX

// STOP CHOISE TOOLTIP FOR COMBOBOX

  // NEW FUN CHEK DELETED STRING and compare them with GENERAL array UPLOAD

// STOP UPLOADING FILES \|/|\|/|\|/|
