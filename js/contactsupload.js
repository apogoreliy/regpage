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
/*
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
*/
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
$('#nameGlobalUploadVal').change(function() {
  if ($('#nameGlobalUploadVal').css('border-color') === 'red') {
    $('#nameGlobalUploadVal').css('border-color','lightgrey')
  }
});

function prepareArrayUpload(array) {
  xlsxDataGlobal.unshift(fields);
};
//START SAFE FUNCTION NEW UPLOAD BUTTON
$('.saveUploadItemsNew').click(function () {
  if ($('#nameGlobalUploadVal').val() === '_none_' || $('#nameGlobalUploadVal').val() === '') {
    showError('Заполните поле ФИО');
    $('#nameGlobalUploadVal').css('border-color', 'red');
    return
  } else if (($('#uploadCountry').val() === '_none_' && $('#citizenshipGlobalUploadVal').val() === '_none_') || $('#citizenshipGlobalUploadVal').val() === '') {
    showError('Выберите страну из списка или соответствующее поле из файла.');
    $('#citizenshipGlobalUploadVal').css('border-color', 'red');
    $('#uploadCountry').css('border-color', 'red');
    return
  } else if (!$('#periodOfContacts').val()) {
    showError('Заполните поле ПЕРИОД');
    return
  }
  $('#saveSpinner').show();

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
    if (!$('#uploadLocality').val() && $('#localityGlobalUploadVal').val() !== '_none_') {
      first.push($('#uploadLocality').val());
    }
  });
  console.log(fields,first);
  var errorUpload = 0, newArrForServer = [];

  function prepareArrayUploadNew(fieldsSelected,fieldsGlobal) {

    var fieldsCompare = [], newArrForServerReg = [], fieldsExist = ['other', 'email', 'name', 'date_order', 'genger', 'phone', 'citizenship', 'locality', 'category', 'index_post', 'date_sending', 'address', 'area', 'region_work', 'typeAppeal', 'status', 'other2', 'other1', 'other3', 'other4'], columnsNo = [] , dateOrd, dateSnd, dateOrdReady, dateSndReady;

    String.prototype.replaceAt = function(index, replacement) {
      return this.substr(0, index) + replacement + this.substr(index + replacement.length);
    }

    function toTrimAndToLower(x) {
      var y = x && isNaN(x) ? x.trim() : x;
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
    var c = fieldsSelected['date_order'] !== '_none_' ? fieldsSelected['date_order'] : '';
    var d = fieldsSelected['status'] !== '_none_' ? fieldsSelected['status'] : ''; // ???
    var e = fieldsSelected['genger'] !== '_none_' ? fieldsSelected['genger'] : '';
    var f = fieldsSelected['phone'] !== '_none_' ? fieldsSelected['phone'] : '';
    var g = fieldsSelected['citizenship'] !== '_none_' ? fieldsSelected['citizenship'] : '';
    var h = fieldsSelected['locality'] !== '_none_' ? fieldsSelected['locality'] : '';
    var j = fieldsSelected['category'] !== '_none_' ? fieldsSelected['category'] : '';
    var k = fieldsSelected['index_post'] !== '_none_' ? fieldsSelected['index_post'] : '';
    var l = fieldsSelected['date_sending'] !== '_none_' ? fieldsSelected['date_sending'] : '';
    var m = fieldsSelected['address'] !== '_none_' ? fieldsSelected['address'] : '';
    var n = fieldsSelected['area'] !== '_none_' ? fieldsSelected['area'] : '';
    var o = fieldsSelected['region_work'] !== '_none_' ? fieldsSelected['region_work'] : '';
    var p = fieldsSelected['region'] !== '_none_' ? fieldsSelected['region'] : '';
    var q = fieldsSelected['typeAppeal'] !== '_none_' ? fieldsSelected['typeAppeal'] : ''; // ???
    var r = fieldsSelected['other2'] !== '_none_' ? fieldsSelected['other2'] : '';
    var s = fieldsSelected['other3'] !== '_none_' ? fieldsSelected['other3'] : '';
    var t = fieldsSelected['other4'] !== '_none_' ? fieldsSelected['other4'] : ''; // ???
    var mainPeriod = $('#periodOfContacts').val(); // project

var fildsNamesAre = {aa:aa,b:b,c:c,bb:bb,d:d,e:e,f:f,g:g,h:h,j:j,k:k,l:l,m:m,n:n,o:o,p:p,q:q,r:r,s:s,t:t}
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
         if (xlsxDataGlobal[i][bb] === null || xlsxDataGlobal[i][bb] === undefined || xlsxDataGlobal[i][bb] === 'undefined') {
            break
         }
        if (fieldsSelected['name1'] !== '_none_') {
          y = xlsxDataGlobal[i][bb1];
          xlsxDataGlobal[i][bb] = xlsxDataGlobal[i][bb] + ' ' + xlsxDataGlobal[i][bb1];
          if (fieldsSelected['name2'] !== '_none_') {
            xlsxDataGlobal[i][bb] = xlsxDataGlobal[i][bb] + ' ' + xlsxDataGlobal[i][bb2];
          }
        } else {
          y = xlsxDataGlobal[i][bb];
        }
// Доделать auto genger
      if (fieldsSelected['genger'] === '_none_') {
        y ? toTrimAndToLower(y) : '';
         //var x = xlsxDataGlobal[i][bb];
         var u;
         if (y && isNaN(y)) {
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
      var otherCollect = '';
// type of appeal
        if (xlsxDataGlobal[i][q]) {
          otherCollect = otherCollect + 'Тип обращения: ' + xlsxDataGlobal[i][q] + '\n';
        }
//  category
        if (xlsxDataGlobal[i][j]) {
          otherCollect = otherCollect + 'Категория: ' + xlsxDataGlobal[i][j] + '\n';
        }
// comment
        if (xlsxDataGlobal[i][aa]) {
          otherCollect = otherCollect + '' + xlsxDataGlobal[i][aa] + '\n';
        }
// comment 2
        if (xlsxDataGlobal[i][r]) {
          otherCollect = otherCollect + '' + String(xlsxDataGlobal[0][fildsNamesAre.r])+ ': ' + xlsxDataGlobal[i][r] + '\n';
        }
// comment 3
        if (xlsxDataGlobal[i][s]) {
          otherCollect = otherCollect + ''+ String(xlsxDataGlobal[0][fildsNamesAre.s]) +': ' + xlsxDataGlobal[i][s] + '\n';
        }
// comment 4
        if (xlsxDataGlobal[i][t]) {
          otherCollect = otherCollect + ''+ String(xlsxDataGlobal[0][fildsNamesAre.t]) +': ' + xlsxDataGlobal[i][t];
        }

      tmpArr.push(otherCollect); // comment
      tmpArr.push(xlsxDataGlobal[i][bb]); // name
      if (!bb || bb === ' ') {
        showError('ФИО должно быть заполненно.');
        errorUpload = 1;
        return
      }
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



      if (first[0] !== '_none_') {
        tmpArr.push(first[0]);
      } else {
        var locKeyCitizenship,
        items = data_page.country_list,
        itemCitizenship = '', itemOurCitizenship = '', locKeyCitizenshipKey;
        itemCitizenship = toTrimAndToLower(xlsxDataGlobal[i][g]);

        for (var key in items) {
          itemOurCitizenship = items[key] && isNaN(items[key]) ? items[key].toLowerCase(): items[key];
          if (key === itemCitizenship) {
            locKeyCitizenship = items[key];
            locKeyCitizenshipKey = key;
          } else if (itemOurCitizenship === itemCitizenship) {
            locKeyCitizenship = items[key];
            locKeyCitizenshipKey = key;
          }
        }
        if (locKeyCitizenship) {
          tmpArr.push(locKeyCitizenshipKey); // citizenship_key
        } else if (i !== 0) {
          showError('Страна гражданства не определена, или её нет в нашей базе, обратитесь к администратору сайта.');
          errorUpload = 1;
          return
        } else {
          tmpArr.push('');
        }
        // Также нужно искать страну по ключу если кто то будет использовать ключи в экспортируемом файле
      }

      if (first[1] !== '') {
        tmpArr.push(first[1]);
      } else {
        tmpArr.push(xlsxDataGlobal[i][h]);
      }

    /*  if (first[2] !== '_none_') {
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

      */
      tmpArr.push(xlsxDataGlobal[i][b]); // email
      tmpArr.push(xlsxDataGlobal[i][f]); // cell_phone
// START date parsing

    /*  dateOrd = xlsxDataGlobal[i][c];

      if (dateOrd) {
        var yyyy = dateOrd.slice(6,10),
        mm = dateOrd.slice(5,7),
        dd = dateOrd.slice(0,4);
        dateOrd = yyyy + '-' + mm + '-' + dd;

        //dateOrdReady = dateOrd.substr(0, 0) + xlsxDataGlobal[i][c][3] + xlsxDataGlobal[i][c][4] + '.' + xlsxDataGlobal[i][c][0] + xlsxDataGlobal[i][c][1] + dateOrd.substr(5);
        //dateOrd = new Date(dateOrdReady);
      }
*/
      if (xlsxDataGlobal[i][c] && xlsxDataGlobal[i][c][2] === '.') {
        //var rplsDate = xlsxDataGlobal[i][c].replace(/\./g,'-');
        tmpArr.push(dateStrToddmmyyyyToyyyymmdd(xlsxDataGlobal[i][c])); // order date
      } else {
        tmpArr.push(xlsxDataGlobal[i][c]); // order date
      }

// STOP date parsing

// START Status
var stt = '';
switch (toTrimAndToLower(xlsxDataGlobal[i][d])) {
  case 'недозвон':
  stt = 1;
  break;
  case 'ошибка':
  stt = 2;
  break;
  case 'отказ':
  stt = 3;
  break;
  case 'заказ':
  stt = 4;
  break;
  case 'продолжение':
  stt = 5;
  break;
  case 'завершение':
  stt = 6;
  break;
  case 'бланк':
  stt = 7;
  break;

  default: '_none_'
}

      tmpArr.push(stt); // status toTrimAndToLower();
// STOP Status
      tmpArr.push(xlsxDataGlobal[i][k]); // Index Mail Post
      tmpArr.push(window.adminId); // adminId

// START date parsing
      dateSnd = xlsxDataGlobal[i][l];
      if (dateSnd) {
        dateSndReady = dateSnd.substr(0, 0) + xlsxDataGlobal[i][l][3] + xlsxDataGlobal[i][l][4] + '.' + xlsxDataGlobal[i][l][0] + xlsxDataGlobal[i][l][1] + dateSnd.substr(5);
        dateSnd = new Date(dateSndReady);
      }
      tmpArr.push(dateSnd); // date sending

// STOP date parsing
      tmpArr.push(xlsxDataGlobal[i][m]); // address
      tmpArr.push(xlsxDataGlobal[i][n]); // area
      tmpArr.push(xlsxDataGlobal[i][o]); // region of the work
      tmpArr.push(xlsxDataGlobal[i][p]); // region
      tmpArr.push(mainPeriod); // project


// Доделать добавление строк только отмеченных строк
      if (arrStr01.indexOf(i) !== -1 && $('#uploadStringsChkbx').prop('checked') || (!$('#uploadStringsChkbx').prop('checked'))) {
          newArrForServer.push(tmpArr);
      }

/*
// REG TABLE START
      tmpArrReg.push(xlsxDataGlobal[i][aa]); // other

      var aReg = $('.tab-pane.active').attr('id');
      aReg ? aReg = aReg.slice(9,17) : '';
      tmpArrReg.push(aReg); // id event

      tmpArrReg.push('01');
      tmpArrReg.push(window.adminId);
      tmpArrReg.push($('.tab-pane.active').attr('data-currency'));
      otherCollect ? tmpArrReg.push(otherCollect) : tmpArrReg.push(' ');

      tmpArrReg.push(null);
      tmpArrReg.push(null);

      if ((arrStr01.indexOf(i) !== -1 && $('#uploadStringsChkbx').prop('checked')) || (!$('#uploadStringsChkbx').prop('checked'))) {
          newArrForServerReg.push(tmpArrReg);
      }
*/
// REG TABLE STOP
// LOOOOOOOOP
      aa = aa !== '' ? Number(aa) + 16 : '';
      b = b !== '' ? Number(b) + 16 : '';
      bb = bb !== '' ? Number(bb) + 16 : '';
      bb1 = bb1 !== '' ? Number(bb1) + 16 : '';
      bb2 = bb2 !== '' ? Number(bb2) + 16 : '';
      c = c !== '' ? Number(c) + 16 : '';
      d = d !== '' ? Number(d) + 16 : '';
      e = e !== '' ? Number(e) + 16 : '';
      f = f !== '' ? Number(f) + 16 : '';
      g = g !== '' ? Number(g) + 16 : '';
      h = h !== '' ? Number(h) + 16 : '';
      j = j !== '' ? Number(j) + 16 : '';
      k = k !== '' ? Number(k) + 16 : '';
      l = l !== '' ? Number(l) + 16 : '';
      m = m !== '' ? Number(m) + 16 : '';
      n = n !== '' ? Number(n) + 16 : '';
      o = o !== '' ? Number(o) + 16 : '';
      p = p !== '' ? Number(p) + 16 : '';
      q = q !== '' ? Number(q) + 16 : '';
      r = r !== '' ? Number(r) + 16 : '';
      s = s !== '' ? Number(s) + 16 : '';
      t = t !== '' ? Number(t) + 16 : '';
    }

// FOR REG TABLE

    console.log(newArrForServer);
    if (errorUpload === 0) {
      //var y = JSON.stringify(newArrForServerReg);
      var x = JSON.stringify(newArrForServer);
      $.post('/ajax/excelUploadCnt.php', {xlsx_array: x})
      .done(function(data){
        console.log(data);
      });
    }
  }

  prepareArrayUploadNew(fields,first);
  if (errorUpload === 0) {
    var countStrTotal = newArrForServer ? newArrForServer.length - 1 : 0;
    $('#modalUploadItems').modal('hide');
      setTimeout(function () {
        showHint('Обработано '+countStrTotal+' строк');
      }, 700);
  } else {
    return
  }
  setTimeout(function () {
    $('#saveSpinner').show();
    window.location = '/contacts';
  }, 3000);
});
// STOP SAFE FUNCTION NEW UPLOAD BUTTON
// START UPLOAD FILE
$('#upload_file').change(function() {

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
  var option = {genger: 'Пол', email: 'Email', phone: 'Телефон', date_order: 'Дата заказа', date_sending: 'Дата закрытия', index_post: 'Индекс', region: 'Область', region_work: 'Регион работы', status: 'Статус', category: 'Категория',  typeAppeal: 'Тип обращения', address: 'Адрес', area: 'Район', other: 'Комментарий', other2: 'Доп. комментарий', other3: 'Доп. комментарий', other4: 'Доп. комментарий' };
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
      elements.push('<div class="col-6"><select class="float-left form-control form-control-sm" name=""><option value="_none_"></option>');
      var optionsString = options.join('');
      elements.push(optionsString);
      elements.push('</select></div><div class="col-6"><select class="float-right upload_fields form-control form-control-sm" name=""></select></div>');
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
    $('#citizenshipGlobalUploadVal').attr('disabled', true);
  } else {
    $('#citizenshipGlobalUpload').attr('disabled', false);
    $('#citizenshipGlobalUploadVal').attr('disabled', false);
  }
  var a =$('#uploadLocality').val();
  if ( a.length !== 0) {
    $('#localityGlobalUpload').attr('disabled', true);
    $('#localityGlobalUploadVal').attr('disabled', true);
  } else {
    $('#localityGlobalUpload').attr('disabled', false);
    $('#localityGlobalUploadVal').attr('disabled', false);
  }
}
/*
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
    } else if (($(this).prev().find('option:selected').val() === 'date_sending') || ($(this).prev().find('option:selected').val() === 'depart')) {
// arive AND depart checking should be here
    } else if ($(this).prev().find('option:selected').val() === 'region_work') {
// russpeaking checking should be here
    } else if ($(this).prev().find('option:selected').val() === 'date_order') {
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
*/
    // STOP CHECKING SELECT CONTENT
    //???
$('#globalValueForFields select').change(function(e) {
    checkSelectGlobalValue(e);
});

$('#uploadLocality').keyup(function(e) {
    checkSelectGlobalValue(e);
});

$('#uploadLocality').focus(function(e) {
    checkSelectGlobalValue(e);
});

$('#uploadLocality').focusout(function(e) {
    checkSelectGlobalValue(e);
});

$('#citizenshipGlobalUploadVal, #localityGlobalUploadVal').change(function() {
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
});
  // STOP SELECT FIELDS BEHAVIOR UPLOAD

// UPLOAD FUNCTIN START
  $('#uploadSpinner').hide();
  $('#saveSpinner').hide();
  $('form').on('submit', function (e) {
    e.preventDefault();
    $('#uploadSpinner').show();
    $('#uploadBtn').attr('disabled',true);
    // logic
    $.ajax({
        url: this.action,
        type: this.method,
        data: new FormData(this), // important
        processData: false, // important
        contentType: false, // important
        success: function (res) {
          xlsxDataGlobal = res;
          //console.log(xlsxDataGlobal);
        }
    });
/*
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
    */
    setTimeout(function () {
      var fieldsCount = xlsxDataGlobal[0] ? xlsxDataGlobal[0].length : 0;
      if (fieldsCount < 1) {
        $('#uploadMsgError').text('Файл не должен быть пустым');
        $('#uploadSpinner').hide();
        return
      }
      getUpdaterEditor(xlsxDataGlobal);
      //console.log(xlsxDataGlobal);
      //getUpdaterEditorForRegTbl(xlsxDataGlobalReg);
      //console.log(xlsxDataGlobalReg);
      newFileUploader(xlsxDataGlobal); // REBUILD IT
      stringPrepareForShow(xlsxDataGlobal);
      $('#uploadSpinner').hide();
      $('#uploadBtn').attr('disabled',false);
      collectString();
    }, 15000);
});
// UPLOAD FUNCTIN STOP
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

  // START strings builder UPLOAD
$('#uploadStringsShow').hide();
$('#uploadStringsChkbx').change(function () {
  $(this).prop('checked') ? $('#uploadStringsShow').show() : $('#uploadStringsShow').hide();
});

function stringPrepareForShow(xlsxData) {

    var uploadedStrings = [];
    for (var i = 0; i < xlsxData.length; i++) {
      if (i != 0) {
        var itemStr = xlsxData[i];
        var uuu = [];
        var counter = 0;
        for (var varvar in itemStr) {
          if (counter === 0 || counter === 3 || counter === 7) {
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
