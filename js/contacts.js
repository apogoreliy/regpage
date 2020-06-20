// JS PRACTICES
// Contacts

$(document).ready(function(){
  console.log(data_page);

  if (data_page.admin_role === '0') {
    $('#responsibleContact').html('<option value="'+window.adminId+'">'+twoNames2(data_page.admin_name)+'');
  } else {
    $('#responsibleContact').val(window.adminId);
  }



function contactsStringsLoad(x, idStr) {

  var tableData=[],tableDataMbl=[];
  function statusSwitch(x) {
    var result = '-';
    switch (x) {
      case '1':
      result = 'Недозвон';
      break;
      case '2':
      result = 'Ошибка';
      break;
      case '3':
      result = 'Отказ';
      break;
      case '4':
      result = 'Заказ';
      break;
      case '5':
      result = 'Продолжение';
      break;
      case '6':
      result = 'Завершение';
      break;
      case '7':
      result = 'Бланк';
      break;
    }
    return result
  }
  for (var i = 0; i < x.length; i++) {

    var prevAdm, dateorder, datesending, idStrMbl = '', idStrDsk = '';
    if ($(window).width()>=769) {
      idStrDsk = idStr;
    } else {
      idStrMbl = idStr;
    }
    if (data_page.admin_role > 0) {
      prevAdm = data_page.members_responsibles[x[i].responsible_previous];
    } else {
      prevAdm = data_page.members_admin_responsibles[x[i].responsible_previous];
    }
    prevAdm ? prevAdm = twoNames2(prevAdm) : prevAdm = '';
    statusSwitch(x[i].status);
    tempCom = x[i].comment;
    var comSlice = '';
    if (tempCom[1] && tempCom.indexOf('\n') >= 30) {
      comSlice = tempCom.slice(0,30) + '...';
    } else if (tempCom[1] && tempCom.indexOf('\n') < 30) {
      var pos = tempCom.indexOf('\n');
      comSlice = tempCom.slice(0,pos) + '...';
    }
    if (x[i].order_date === null || x[i].order_date === '' || x[i].order_date === 'null' || x[i].order_date === undefined || x[i].order_date === 'undefined') {
      dateorder = '';
    } else {
      dateorder = x[i].order_date.slice(0,10);
      dateorder = dateStrToddmmyyyyToyyyymmdd(dateorder, true);
    }
    if (x[i].sending_date === null || x[i].sending_date === '' || x[i].sending_date === 'null' || x[i].sending_date === undefined || x[i].sending_date === 'undefined') {
      datesending = '';
    } else {
      datesending = x[i].sending_date.slice(0,10);
      datesending = dateStrToddmmyyyyToyyyymmdd(datesending, true);
    }

    tableData.push('<tr class="contacts_str '+(idStrDsk === x[i].id ? 'active_string' : '')+' cursor-pointer" data-id="'+x[i].id+'" data-crm_id="'+x[i].crm_id+'" data-responsible_name ="'+x[i].member_name+'" data-other="'+(x[i].comment === ' '? '' : x[i].comment)+'"  data-date="'+x[i].time_stamp+'" data-index_post="'+x[i].index_post+'" data-address="'+(x[i].address === ' ' ? '' : x[i].address)+'" data-area="'+(x[i].area === ' ' ? '' : x[i].area)+'" data-country_key="'+x[i].country_key+'" data-email="'+x[i].email+'" data-male="'+x[i].male+'" data-order_date="'+dateorder+'" data-region="'+(x[i].region === ' ' ? '' : x[i].region)+'" data-region_work="'+(x[i].region_work === ' ' ? '': x[i].region_work)+'" data-responsible="'+x[i].responsible+'" data-responsible_previous="'+x[i].responsible_previous+'" data-responsible_previous_name="'+prevAdm+'" data-sending_date="'+datesending+'" data-status_key="'+x[i].status+'"><td><input class="checkboxString" type="checkbox"></td><td><span class="data_name">'+x[i].name+'</span><br><span class="grey_text" style="margin-left: 0">'+comSlice+'</span></td><td><span  class="data_locality">'+(x[i].locality === ' ' ? '' : x[i].locality)+'</span><br><span class="grey_text" style="margin-left: 0">'+x[i].region+'</span></td><td class="data_phone">'+x[i].phone+'</td><td class="data_status">'+statusSwitch(x[i].status)+'</td><td><span>'+twoNames2(x[i].member_name)+'</span><br><span class="data_responsible_previous grey_text">'+prevAdm+'</span></td></tr>');

    tableDataMbl.push('<div style="border-bottom: 1px solid lightgrey; padding-bottom: 5px; padding-top: 5px;" class="contacts_str cursor-pointer '+(idStrMbl === x[i].id ? 'active_string' : '')+'" data-id="'+x[i].id+'" data-crm_id="'+x[i].crm_id+'" data-responsible_name ="'+x[i].member_name+'" data-other="'+(x[i].comment === ' '? '' : x[i].comment)+'"  data-date="'+x[i].time_stamp+'" data-index_post="'+x[i].index_post+'" data-address="'+(x[i].address === ' ' ? '' : x[i].address)+'" data-area="'+(x[i].area === ' ' ? '' : x[i].area)+'" data-country_key="'+x[i].country_key+'" data-email="'+x[i].email+'" data-male="'+x[i].male+'" data-order_date="'+dateorder+'" data-region="'+(x[i].region === ' ' ? '' : x[i].region)+'" data-region_work="'+(x[i].region_work === ' ' ? '': x[i].region_work)+'" data-responsible="'+x[i].responsible+'" data-responsible_previous="'+x[i].responsible_previous+'" data-responsible_previous_name="'+prevAdm+'" data-sending_date="'+datesending+'" data-status_key="'+x[i].status+'"><div><input class="checkboxString" type="checkbox"><span class="data_name"> <b>'+x[i].name+'</b> </span><br><span  class="data_locality" style="padding-left: 17px;"> '+(x[i].locality === ' ' ? '' : x[i].locality)+' </span><span class="" style="margin-left: 0"> '+x[i].region+' </span></div><div class="data_phone" style="padding-left: 17px;"> <a href="tel:'+x[i].phone+'">'+x[i].phone+'</a></div><div class="data_status" style="padding-left: 17px;">'+statusSwitch(x[i].status)+'</div></div>');
  }
   $('#listContacts tbody').html(tableData);
   $('#listContactsMbl').html(tableDataMbl);

    $('#msgChatSend').click(function() {
      if (!$('#msgChatText').val() || !$('#saveContact').attr('data-id')) {
        return
      }
      var dataMsg ={
        text: $('#msgChatText').val(),
        id: $('#saveContact').attr('data-id')
      };
      $.get('/ajax/contacts.php?new_message', {data: dataMsg})
        .done (function(data) {
        });

      $.get('/ajax/contacts.php?get_messages', {id: $('#saveContact').attr('data-id')})
        .done (function(data) {
          historyBuilder(data.messages);
        });
        $('#msgChatText').val('');
      });

   $('.contacts_str').click(function(e) {
     e.stopPropagation();
     $('#orderDateEditIco').show();
     $('#orderDateEdit').parent().hide();
     if ($('.cd-panel-watch').hasClass('cd-panel--is-visible-watch')) {
      if (checkChangedForSave()) {
        if (!confirm('Изменения не сохранены, закрыть карточку?')) {
          return
        }
      }
     }
     if ($('#nameContact').css('border-color') === 'rgb(255, 0, 0)') {
       $('#nameContact').css('border-color', 'lightgrey');
     }
     if ($(this).hasClass('active_string')) {
      if ($('.cd-panel-watch').hasClass('cd-panel--is-visible-watch')) {
        $('.cd-panel-watch').removeClass('cd-panel--is-visible-watch');
        $(this).removeClass('active_string')
      } else {
        $('.cd-panel-watch').addClass('cd-panel--is-visible-watch');
      }
     } else {
       $('#blankHistory').show();
       $.get('/ajax/contacts.php?get_messages', {id: $(this).attr('data-id')})
         .done (function(data) {
           historyBuilder(data.messages);
         });
      $('.active_string') ? $('.active_string').removeClass('active_string') : '';
      $(this).addClass('active_string');
      $('.cd-panel-watch').hasClass('cd-panel--is-visible-watch') ? '' : $('.cd-panel-watch').addClass('cd-panel--is-visible-watch');
     }
     var countryName = '';

     if (data_page.admin_role === '0') {
       var first = '', second = '';
       first = '<option value="'+$(this).attr('data-responsible')+'">'+twoNames2($(this).attr('data-responsible_name'))+'';
       if ($(this).attr('data-responsible_previous')) {
        second = '<option value="'+$(this).attr('data-responsible_previous')+'">'+twoNames2($(this).attr('data-responsible_previous_name'))+'';
       }
       $('#responsibleContact').html(first+second);
     }

     $(this).attr('data-country_key') ? countryName = data_page.country_list[$(this).attr('data-country_key')] : '';
     $('#nameContact').val($(this).find('.data_name').text());
     $('#phoneContact').val($(this).find('.data_phone').text());
     $('#emailContact').val($(this).attr('data-email'));
     $('#countryContact').val($(this).attr('data-country_key'));
     $('#maleContact').val($(this).attr('data-male'));
     $('#regionContact').val($(this).attr('data-region'));
     $('#areaContact').val($(this).attr('data-area'));
     $('#localityContact').val($(this).find('.data_locality').text());
     $('#indexContact').val($(this).attr('data-index_post'));
     $('#addressContact').val($(this).attr('data-address'));
     $('#commentContact').val($(this).attr('data-other'));
     $('#regionWorkContact').val($(this).attr('data-region_work'));
     $('#responsibleContact').val($(this).attr('data-responsible'));
     $('#responsibleContact').attr('data-responsible', $(this).attr('data-responsible'));
     $('#responsibleContact').attr('data-responsible_previous', $(this).attr('data-responsible_previous'));
     if ($(this).attr('data-order_date') === 'null' || $(this).attr('data-order_date') === '00.00.0000' || $(this).attr('data-order_date') === 'undefined' || $(this).attr('data-order_date') === '') {
       $('#labelOrderDate').text('Заказа НЗ не было ');
       $('#orderDate').hide();
     } else if ($(this).attr('data-order_date') !== 'null' || $(this).attr('data-order_date') !== '00.00.0000'  || $(this).attr('data-order_date') !== 'undefined' || $(this).attr('data-order_date') !== '') {
       $('#labelOrderDate').text('Заказ НЗ от ');
       $('#orderDate').show();
     }

     if ($(this).attr('data-sending_date') === 'null' || $(this).attr('data-sending_date') === '00.00.0000' || $(this).attr('data-sending_date') === 'undefined') {
       $('#sendingDate').parent().hide();
     } else if ($(this).attr('data-sending_date') !== 'null' || $(this).attr('data-sending_date') !== '00.00.0000' || $(this).attr('data-sending_date') !== 'undefined') {
       $('#sendingDate').parent().show();
     }

     $('#orderDate').text($(this).attr('data-order_date'));
     $('#sendingDate').text($(this).attr('data-sending_date'));

     $('#saveContact').attr('data-id', $(this).attr('data-id'));
     $('#saveContact').attr('data-id_admin', window.adminId);
     $('#statusContact').val($(this).attr('data-status_key'));

     // Check a send function
     if ($(this).attr('data-order_date') === '' || $(this).attr('data-order_date') === 'null' || $(this).attr('data-order_date') === 'undefined' || $(this).attr('data-order_date') === '00.00.0000') {
       $('#orderSentToContact').attr('disabled', false);
       $('#orderDateEdit').val('');
     } else {
       $('#orderSentToContact').attr('disabled', true);

       var dateOrderChkeck = dateStrToddmmyyyyToyyyymmdd($(this).attr('data-order_date'), false);
       var dateOrder = new Date(dateOrderChkeck);
       var currentDate = new Date();

       $('#orderDateEdit').val(dateOrderChkeck);

       if (((currentDate.getFullYear() - dateOrder.getFullYear()) > 1) || ((currentDate.getFullYear() - dateOrder.getFullYear()) < 0)) {
         $('#orderSentToContact').attr('disabled', false);
       } else if ((currentDate.getFullYear() - dateOrder.getFullYear()) === 1) {
         if (((currentDate.getMonth()-dateOrder.getMonth()) === -11) && (currentDate.getDate() < dateOrder.getDate())) {
           $('#orderSentToContact').attr('disabled', true);
         } else {
           $('#orderSentToContact').attr('disabled', false);
         }
       } else if ((currentDate.getFullYear() - dateOrder.getFullYear()) === 0 ) {
         if (((currentDate.getMonth() - dateOrder.getMonth()) > 1) || ((currentDate.getMonth() - dateOrder.getMonth()) === 1 && (currentDate.getDate()>=dateOrder.getDate()))) {
           $('#orderSentToContact').attr('disabled', false);
         } else if ((currentDate.getMonth() - dateOrder.getMonth()) <= 0) {
           $('#orderSentToContact').attr('disabled', true);
         } else {
           $('#orderSentToContact').attr('disabled', true);
         }
       } else {
         $('#orderSentToContact').attr('disabled', true);
       }
     }

     if (data_page.admin_role === '0' && $('#myBlanks').val() === '0') {
       $('#sideBarBlankContact').find('input').attr('disabled', true)
       $('#sideBarBlankContact').find('select').attr('disabled', true)
       $('#sideBarBlankContact').find('textarea').attr('disabled', true)
       $('#cd-panel__close-watch').attr('disabled', false)
     } else if (data_page.admin_role === '0' && $('#myBlanks').val() === '1' && $('#nameContact').attr('disabled') === 'disabled') {

       var dateOrderChkeckEx = dateStrToddmmyyyyToyyyymmdd($(this).attr('data-order_date'), false);

       var dateOrderEx = new Date(dateOrderChkeckEx);
       var currentDateEx = new Date();

       $('#orderDateEdit').val(dateOrderChkeckEx);

       $('#sideBarBlankContact').find('input').attr('disabled', false)
       $('#sideBarBlankContact').find('select').attr('disabled', false)
       $('#sideBarBlankContact').find('textarea').attr('disabled', false)

       if ($('#orderDate').text() === '' || $('#orderDate').text() === 'null' || $('#orderDate').text() === 'undefined' || $('#orderDate').text() === '00.00.0000') {
         $('#orderSentToContact').attr('disabled', false);
       } else if(((currentDateEx.getFullYear()- dateOrderEx.getFullYear()) === 1) && ((currentDateEx.getMonth()-dateOrderEx.getMonth()) === -11) && (currentDateEx.getDate() < dateOrderEx.getDate())) {

         $('#orderSentToContact').attr('disabled', true);
       } else if ((((currentDateEx.getFullYear() - dateOrderEx.getFullYear()) === 0) && ((currentDateEx.getMonth() -dateOrderEx.getMonth()) === 1) && (currentDateEx.getDate() < dateOrderEx.getDate())) || (((currentDateEx.getFullYear() - dateOrderEx.getFullYear()) === 0) && ((currentDateEx.getMonth() - dateOrderEx.getMonth()) === 0))) {
         $('#orderSentToContact').attr('disabled', true);
       }
     }
   });

   $('.checkboxString').click(function (e) {
     e.stopPropagation(e);
     if ($(this).prop('checked')) {
       $('#deleteContact').attr('disabled') ? $('#deleteContact').attr('disabled', false) : '';
       $('#deleteContactsShowModal').attr('disabled') ? $('#deleteContactsShowModal').attr('disabled', false) : '';
       $('#appointResponsible').attr('disabled') ? $('#appointResponsible').attr('disabled', false) : '';
       $('#appointResponsibleShow').attr('disabled') ? $('#appointResponsibleShow').attr('disabled', false) : '';
     } else {
       $('#deleteContact').attr('disabled', true);
       $('#deleteContactsShowModal').attr('disabled', true);
       $('#appointResponsible').attr('disabled', true);
       $('#appointResponsibleShow').attr('disabled', true);
       $('.contacts_str').find('.checkboxString').each(function () {
         if ($(this).prop('checked')) {
           $('#deleteContact').attr('disabled', false);
           $('#deleteContactsShowModal').attr('disabled', false);
           $('#appointResponsible').attr('disabled', false);
           $('#appointResponsibleShow').attr('disabled', false);
         }
       });
       /*
       setTimeout(function () {
         if (counter === 0) {
           $('#deleteContact').attr('disabled', true);
           $('#appointResponsible').attr('disabled', true);
         }
       }, 120);*/
     }
   });
   setTimeout(function () {
     filtersOfString();
   }, 50);
}

function historyBuilder(data) {
  var readyMessages = [], name, nameTmp = '', author, edit;
  for (var i = 0; i < data.length; i++) {
    author='', edit='', name = '';
    if (data[i].member_key === window.adminId) {
      name = $('.user-name').text();
      author = 1;
    } else {
      for (var variable in data_page.members_admin_responsibles) {
        if (data[i].member_key === variable) {
          nameTmp = data_page.members_admin_responsibles[variable].split(' ');
           name = nameTmp[0] + ' ' + nameTmp[1][0] + '. ';
          if (name[2]) {
            name = name + nameTmp[2][0] + '. ';
          }
        }
      }
      if (!name) {
        for (var variable in data_page.members_responsibles) {
          if (data[i].member_key === variable) {
            nameTmp = data_page.members_responsibles[variable].split(' ');
             name = nameTmp[0] + ' ' + nameTmp[1][0] + '. ';
            if (name[2]) {
              name = name + nameTmp[2][0] + '. ';
            }
          }
        }
      }
      if (!name) {
        name = data[i].member_key;
      }
    }
    if (author) {
      author = '<div class="row change_msg_div" style="display: none"><div class="col-10"><textarea rows="3" class="form-control form-control-sm change_msg_field">'+data[i].message+'</textarea></div><div class="col-2" style="padding-left: 5px; padding-right: 5px;"><input type="button" class="btn btn-success change_msg_ok btn-sm" value="Ок" style="margin-right: 10px;"><input type="button" class="btn btn-danger change_msg_cancel btn-sm" value="X"><input type="button" class="btn btn-warning change_msg_delete btn-sm" value="Удалить" style="margin-top: 5px;"></div></div>';
    } else {
      edit = 'style="display:none"';
    }

    readyMessages.push('<div data-id="'+data[i].id+'"><p data-member_id="'+data[i].member_key+'" data-date="'+data[i].time_stamp+'" style="margin-bottom: 3px;"><span class="about_message">'+name+' '+data[i].time_stamp+' </span><i class="cursor-pointer fa fa-pencil edit_history_msg" '+edit+'></i></p><p class="text_history_msg" style="margin-bottom: 0px;">'+data[i].message+'</p>'+author+'<hr style="margin-bottom: 8px; margin-top: 8px;"></div>');
  }
  $('#chatBlock').html(readyMessages);

  $('.edit_history_msg').click(function (e) {
    e.stopPropagation();
    $(this).parent().parent().find('.change_msg_div').show();
    $(this).parent().parent().find('.text_history_msg').hide();
  });

  $('.change_msg_ok').click(function (e) {
    e.stopPropagation();

    $(this).parent().parent().hide();
    $(this).parent().parent().parent().find('.text_history_msg').show();
    $(this).parent().parent().parent().find('.text_history_msg').text($(this).parent().parent().find('.change_msg_field').val());
console.log($(this).parent().parent().parent().find('.change_msg_field').val());
    $.get('/ajax/contacts.php?update_message', {id: $(this).parent().parent().parent().attr('data-id'), text:$(this).parent().parent().parent().find('.change_msg_field').val()})
      .done (function(data) {
      });
  });

  $('.change_msg_cancel').click(function(e){
    e.stopPropagation();
    $(this).parent().parent().hide();
    $(this).parent().parent().parent().find('.text_history_msg').show();
  });

  $('.change_msg_delete').click(function(e){
    e.stopPropagation();
    if (confirm('Удалить запись?')) {
    $(this).parent().parent().parent().hide();
    $.get('/ajax/contacts.php?delete_message', {id: $(this).parent().parent().parent().attr('data-id')})
      .done (function(data) {
      });
    }
  });
}
  function messageFunction(type, id, responsible) {
    var text;
    if (type === 'responsible') {
      text = 'Назначен ответственный: ' + responsible;
    }
    var dataMsgFun = {
      text: text,
      id: id
    };
    $.get('/ajax/contacts.php?new_message', {data: dataMsgFun})
      .done (function(data) {
      });
    }

    function getMessages(id) {
      $.get('/ajax/contacts.php?get_messages', {id: id})
      .done (function(data) {
        historyBuilder(data.messages);
      });
    }

// ADD NEW CONTACT
  function clearingBlankOfContact() {

    $('#orderDateEditIco').show();
    $('#orderDateEdit').parent().hide();
    $('#sideBarBlankContact').find('input').each(function () {
      $(this).attr('type') !== 'button' ? $(this).val('') : '';
    });
    $('#maleContact').val('_none_');
    $('#countryContact').val('');
    $('#commentContact').val('');
    $('#responsibleContact').attr('data-responsible_previous', '');
    $('#responsibleContact').attr('data-responsible', '');
    $('#orderDate').text('');
    $('#labelOrderDate').text('Заказа НЗ не было');
    $('#sendingDate').text('');
    $('#statusContact').val('');
    $('#saveContact').attr('data-id', '');
    $('#saveContact').attr('data-id_admin', window.adminId);
    $('#createdDate').text('');
    $('#chatBlock').text('');
    $('#personalBlank').find('.nav-link').hasClass('active') ? '': $('#personalBlank').find('.nav-link').addClass('active');
    $('#personalBlankTab').hasClass('active') ? '': $('#personalBlankTab').addClass('active');
    $('#blankComment').find('.nav-link').hasClass('active') ? $('#blankComment').find('.nav-link').removeClass('active') : '';
    $('#blankCommentTab').hasClass('active') ? $('#blankCommentTab').removeClass('active') : '';
    $('#blankCommentTab').hasClass('show') ? $('#blankCommentTab').removeClass('show') : '';
    $('#blankHistory').find('.nav-link').hasClass('active') ? $('#blankHistory').find('.nav-link').removeClass('active') : '';
    $('#blankHistoryTab').hasClass('active') ? $('#blankHistoryTab').removeClass('active') : '';
    $('#blankHistoryTab').hasClass('show') ? $('#blankHistoryTab').removeClass('show') : '';
  }

  function saveEditContact() {
// check change of the responsible
var currentResponsible = '', prevResponsible = '';
  if (($('#responsibleContact').val() && ($('#responsibleContact').val() === $('#responsibleContact').attr('data-responsible'))) || (!$('#responsibleContact').val() && $('#responsibleContact').attr('data-responsible'))) {
    // responsible has not been changed OR responsible has not been choisen
    currentResponsible = $('#responsibleContact').attr('data-responsible');
    prevResponsible = $('#responsibleContact').attr('data-responsible_previous');
  } else if (!$('#responsibleContact').val() && !$('#responsibleContact').attr('data-responsible')) {
    // create new contact AND no responsible choise
  currentResponsible = window.adminId;
  prevResponsible = $('#responsibleContact').attr('data-responsible_previous');
  } else if ($('#responsibleContact').val() && !$('#responsibleContact').attr('data-responsible')) {
    // create new contact AND responsible has been choisen
    currentResponsible = $('#responsibleContact').val();
    prevResponsible = $('#responsibleContact').attr('data-responsible_previous');
  } else if ($('#responsibleContact').val() && ($('#responsibleContact').val() !== $('#responsibleContact').attr('data-responsible'))) {
    currentResponsible = $('#responsibleContact').val();
    prevResponsible = $('#responsibleContact').attr('data-responsible');
    setTimeout(function () {
      if (data_page.admin_role === '0') {
          clearingBlankOfContact();
          $('.cd-panel-watch').removeClass('cd-panel--is-visible-watch');
      }
    }, 350);
  }
    var data = {};
    data.id = $('#saveContact').attr('data-id');
    data.name = $('#nameContact').val();
    data.phone = $('#phoneContact').val();
    data.email = $('#emailContact').val();
    data.country = $('#countryContact').val();
    data.male = $('#maleContact').val();
    data.region = $('#regionContact').val();
    data.area = $('#areaContact').val();
    data.locality = $('#localityContact').val();
    data.index = $('#indexContact').val();
    data.address = $('#addressContact').val();
    data.comment = $('#commentContact').val();
    data.region_work = $('#regionWorkContact').val();
    data.status = $('#statusContact').val();
    data.responsible = currentResponsible;
    data.responsible_prev = prevResponsible;

    if ($('#orderDate').text()) {
      data.order_date = dateStrToddmmyyyyToyyyymmdd($('#orderDate').text(), false);
    } else {
      data.order_date = null;
    }

    $('#saveContact').attr('data-id_admin') ? data.admin = $('#saveContact').attr('data-id_admin') : data.admin = window.adminId;

    $.get('/ajax/contacts.php?new_update_contact', {data: data})
      .done (function(data) {
        if (data.id && data.id !== 'update') {
          $('#saveContact').attr('data-id', data.id);
          $('#saveContact').attr('data-id_admin', window.adminId);

          if ($('#responsibleContact').val()) {
            $('#responsibleContact').attr('data-responsible', $('#responsibleContact').val());
          } else {
            $('#responsibleContact').attr('data-responsible', window.adminId);
            $('#responsibleContact').val(window.adminId);
          }

          contactsListUpdate(data.id);

          setTimeout(function () {
            if ((data.id !== $('.active_string').attr('data-id')) && ($('.active_string').find('.data_name').text().trim() !== $('#nameContact').val().trim())) {
              clearingBlankOfContact();
              $('.cd-panel-watch').removeClass('cd-panel--is-visible-watch');
              $('.active_string') ? $('.active_string').removeClass('active_string') : '';
            }
          }, 2500);

        } else if (data.id === 'update') {
          if ($('#responsibleContact').val() !== $('#responsibleContact').attr('data-responsible') && $('#responsibleContact').val()) {
            messageFunction('responsible', $('#saveContact').attr('data-id'), shortNames3($('#responsibleContact option:selected').text()));
            setTimeout(function () {
              getMessages($('#saveContact').attr('data-id'));
            }, 200);
            $('#responsibleContact').attr('data-responsible_previous', $('#responsibleContact').attr('data-responsible'));
            $('#responsibleContact').attr('data-responsible', $('#responsibleContact').val());
          } else if (!$('#responsibleContact').val() && $('#responsibleContact').attr('data-responsible') != window.adminId) {
            $('#responsibleContact').attr('data-responsible_previous', $('#responsibleContact').attr('data-responsible'));
            $('#responsibleContact').attr('data-responsible', window.adminId);
          }
        } else {
          console.log('Error. No any answer.');
        }
      });

      setTimeout(function () {
        stringUpdater($('#saveContact').attr('data-id'));
        //contactsListUpdate();
        showHint('Карточка сохранена.')
      }, 500);
  }

  $('#nameContact').keyup(function () {
    $('#nameContact').css('border-color', '#ced4da');
  });

  $('#countryContact,#maleContact').change(function () {
    $(this).css('border-color', '#ced4da');
  });

  $('#saveContact').click(function() {
    if (!$('#nameContact').val()) {
      showError('Заполните поле ФИО.');
      $('#nameContact').css('border-color', 'red');
      return
    }
    if (!$('#countryContact').val()) {
      showError('Заполните поле Страна.');
      $('#countryContact').css('border-color', 'red');
      return
    }
    if ($('#maleContact').val()==='_none_') {
      showError('Заполните поле Пол.');
      $('#maleContact').css('border-color', 'red');
      return
    }
    $('#blankHistory').show();
    saveEditContact();
  });
// responsible delete
  function deleteContact() {
    var dataStr = [];
    $('.contacts_str').each(function () {
      if ($(this).is(':visible') && $(this).find('.checkboxString').prop('checked')) {
        dataStr.push($(this).attr('data-id'));
        if ($(this).attr('data-id')=== $('#saveContact').attr('data-id')) {
          clearingBlankOfContact();
        }
        $(this).hide();
        $(this).removeClass('.contacts_str');
        $(this).addClass('.contacts_str_trash');
      }
    });

    // ПРОВЕРИТЬ на больших объёмах, возможно лучше действовать синхронно, что бы запрос не выполнился прежде формирования массива содержащего данные для запроса

    $.get('/ajax/contacts.php?delete_contact', {id: dataStr})
      .done (function(data) {
      });

    setTimeout(function () {
      showHint('Удалено строк: '+dataStr.length)
      //contactsListUpdate();
    }, 100);
  }

  $('#deleteContact').click(function() {
    deleteContact();
    $('#deleteContactsShowModal').attr('disabled', true);
    //$('#deleteContact').attr('disabled', true);
    $('#appointResponsible').attr('disabled', true);
    $('#appointResponsibleShow').attr('disabled', true);
    $('#checkAllStrings').prop('checked', false);
  });
// responsible set
  function responsibleSetContact() {
    var data = [];
    $('.contacts_str').each(function () {
      if ($(this).is(':visible') && $(this).find('.checkboxString').prop('checked') && ($('#responsibleList').val() !== $(this).attr('data-responsible'))) {
        data.push([$(this).attr('data-id'), $(this).attr('data-responsible')]);
        if ($(this).attr('data-id')=== $('#saveContact').attr('data-id')) {
          $('#responsibleContact').attr('data-responsible_previous', $('#responsibleContact').attr('data-responsible'));
          $('#responsibleContact').attr('data-responsible', $('#responsibleList').val());
          $('#responsibleContact').val($('#responsibleList').val());
        }
      }
    });
    data.length === 0 ? showHint('Назначаемый ответственный не должен совпадать с текущим ответственным') : '';
    var responsibleArr = [$('#responsibleList').val(), shortNames3($('#responsibleList option:selected').text())];
    // ПРОВЕРИТЬ на больших объёмах, возможно лучше действовать синхронно, что бы запрос не выполнился прежде формирования массива содержащего данные для запроса
    $.get('/ajax/contacts.php?responsible_set', {id: data, responsible: responsibleArr})
      .done (function(data) {
        $('#setResponsibleModal').modal().hide();
      });

    setTimeout(function () {
      contactsListUpdate();
    }, 100);
  }

  $('#appointResponsible').click(function() {
    if ($('#responsibleList').val() === '_all_') {
      showError('Выберите ответственного');
      return
    }
    responsibleSetContact();
    $('#deleteContactsShowModal').attr('disabled', true);
    $('#deleteContact').attr('disabled', true);
    $('#appointResponsibleShow').attr('disabled', true);
    $('#checkAllStrings').prop('checked', false);
    setTimeout(function () {
      $('#appointResponsible').attr('disabled', true);
    }, 300);
  });

  function contactsListUpdate(idStr) {
    $.get('/ajax/contacts.php?get_contacts', {role: data_page.admin_role})
      .done (function(data) {
        contactsStringsLoad(data.contacts, idStr);
      });
    }

    contactsListUpdate();

    function contactStringLoader(data) {
      console.log(data);
      // Доделать для новых карточек добавление строк
      if (!data) {
        return
      }
      /*var countryName = '';
      $(this).attr('data-country_key') ? countryName = data_page.country_list[$(this).attr('data-country_key')] : '';*/

      if (data_page.admin_role === '0') {
        var first = '', second = '';
        first = '<option value="'+$('.active_string').attr('data-responsible')+'">'+twoNames2($('.active_string').attr('data-responsible_name'))+'';
        if ($('.active_string').attr('data-responsible_previous')) {
         second = '<option value="'+$('.active_string').attr('data-responsible_previous')+'">'+twoNames2($('.active_string').attr('data-responsible_previous_name'))+'';
        }
        $('#responsibleContact').html(first+second);
      }

        var email = data.email === ' ' ? '' : data.email;
        var region = data.region === ' ' ? '' : data.region;
        var area = data.area === ' ' ? '' : data.area;
        var locality = data.locality === ' ' ? '' : data.locality;
        var address = data.address === ' ' ? '' : data.address;
        var comment = data.comment === ' ' ? '' : data.comment;

// blank update
        $('#nameContact').val(data.name);
        $('#countryContact').val(data.country_key);
        $('#phoneContact').val(data.phone);
        $('#emailContact').val(email);
        $('#maleContact').val(data.male);
        $('#regionContact').val(region);
        $('#areaContact').val(area);
        $('#localityContact').val(locality);
        $('#indexContact').val(data.index_post);
        $('#addressContact').val(address);
        $('#commentContact').val(comment);
        $('#regionWorkContact').val(data.region_work);
        $('#responsibleContact').val(data.responsible);
        $('#responsibleContact').attr('data-responsible', data.responsible);
        $('#responsibleContact').attr('data-responsible_previous', data.responsible_previous);
        $('#saveContact').attr('data-id', data.id);
        $('#statusContact').val(data.status);

// string update
        $('.active_string').find('.data_name').text(data.name);
        $('.active_string').attr('data-country_key', data.country_key);
        $('.active_string').find('.data_phone').text(data.phone);
        $('.active_string').attr('data-email', data.email);
        $('.active_string').attr('data-male', data.male);
        $('.active_string').attr('data-region', data.region);
        $('.active_string').attr('data-area', data.area);
        $('.active_string').find('.data_locality').text(data.locality);
        $('.active_string').attr('data-index_post', data.index_post);
        $('.active_string').attr('data-address', data.address);
        $('.active_string').attr('data-other', data.comment);
        $('.active_string').attr('data-region_work', data.region_work);
        $('.active_string').attr('data-responsible', data.responsible);
        $('.active_string').attr('data-responsible_previous', data.responsible_previous);
        $('.active_string').attr('data-id', data.id);
        $('.active_string').attr('data-status_key',data.status);

        if (data.order_date && data.order_date !== '0000-00-00') {
          dateOrd = dateStrToddmmyyyyToyyyymmdd(data.order_date, true);
          $('.active_string').attr('data-order_date', dateOrd);
        } else {
          $('.active_string').attr('');
        }
    }

    function stringUpdater(idStr) {
      $.get('/ajax/contacts.php?get_contact', {id: idStr})
      .done (function(data) {
        contactStringLoader(data.contact[0]);
      });
    }

  $('.cd-panel__close-watch').click(function() {
    if (checkChangedForSave()) {
      if (!confirm('Изменения не сохранены, закрыть карточку?')) {
        return
      }
    }
    setTimeout(function () {
      $('.cd-panel-watch').removeClass('cd-panel--is-visible-watch');
      $('.active_string') ? $('.active_string').removeClass('active_string') : '';
    }, 50);
  });

  $('#cd-panel__close-watch').click(function() {
    if (checkChangedForSave()) {
      if (!confirm('Изменения не сохранены, закрыть карточку?')) {
        return
      }
    }
    setTimeout(function () {
      $('.cd-panel-watch').removeClass('cd-panel--is-visible-watch');
      $('.active_string') ? $('.active_string').removeClass('active_string') : '';
    }, 50);
  });

  $('#addContact').click(function() {
    $('.active_string') ? $('.active_string').removeClass('active_string') : '';
    if (data_page.admin_role === '0') {
      $('#responsibleContact').html('<option value="'+window.adminId+'">'+twoNames2(data_page.admin_name)+'');
    }
    clearingBlankOfContact();
    $('#orderSentToContact').attr('disabled', false);
    $('.cd-panel-watch').hasClass('cd-panel--is-visible-watch') ? '' : $('.cd-panel-watch').addClass('cd-panel--is-visible-watch');
    $('#blankHistory').hide();
  });

  $('#checkAllStrings').click(function() {
      $('.contacts_str').each(function () {
        if ($('#checkAllStrings').prop('checked')) {
          if ($(this).is(':visible')) {
            $(this).find('.checkboxString').prop('checked', true);
            $('#deleteContact').attr('disabled') ? $('#deleteContact').attr('disabled', false) : '';
            $('#deleteContactsShowModal').attr('disabled') ? $('#deleteContactsShowModal').attr('disabled', false) : '';
            $('#appointResponsible').attr('disabled') ? $('#appointResponsible').attr('disabled', false) : '';
            $('#appointResponsibleShow').attr('disabled') ? $('#appointResponsibleShow').attr('disabled', false) : '';
          }
        } else {
          $(this).find('.checkboxString').prop('checked', false);
          $('#deleteContact').attr('disabled') ? '' : $('#deleteContact').attr('disabled', true);
          $('#deleteContactsShowModal').attr('disabled') ? '' : $('#deleteContactsShowModal').attr('disabled', true);
          $('#appointResponsible').attr('disabled') ? '' : $('#appointResponsible').attr('disabled', true);
          $('#appointResponsibleShow').attr('disabled') ? '' : $('#appointResponsibleShow').attr('disabled', true);
        }
      });
  });
  $('.checkAllStrings').click(function() {
      $('.contacts_str').each(function () {
        if ($('.checkAllStrings').prop('checked')) {
          if ($(this).is(':visible')) {
            $(this).find('.checkboxString').prop('checked', true);
            $('#deleteContact').attr('disabled') ? $('#deleteContact').attr('disabled', false) : '';
            $('#deleteContactsShowModal').attr('disabled') ? $('#deleteContactsShowModal').attr('disabled', false) : '';
            $('#appointResponsible').attr('disabled') ? $('#appointResponsible').attr('disabled', false) : '';
            $('#appointResponsibleShow').attr('disabled') ? $('#appointResponsibleShow').attr('disabled', false) : '';
          }
        } else {
          $(this).find('.checkboxString').prop('checked', false);
          $('#deleteContact').attr('disabled') ? '' : $('#deleteContact').attr('disabled', true);
          $('#deleteContactsShowModal').attr('disabled') ? '' : $('#deleteContactsShowModal').attr('disabled', true);
          $('#appointResponsible').attr('disabled') ? '' : $('#appointResponsible').attr('disabled', true);
          $('#appointResponsibleShow').attr('disabled') ? '' : $('#appointResponsibleShow').attr('disabled', true);
        }
      });
  });
// SENTO ORDER TO
function sendTheOrder(ua) {

  var name = $('#nameContact').val(),
  country = $('#countryContact option:selected').text(),
  region = $('#regionContact').val(),
  area = $('#areaContact').val(),
  locality = $('#localityContact').val(),
  address = $('#addressContact').val(),
  index = $('#indexContact').val(),
  howMuch = 1,
  phone = $('#phoneContact').val(),
  email = $('#emailContact').val(),
  notesOfAdmin = $('#adminNotes').val(),
  commentNotForCrm = '',
  isFirstOrder = false,
  comment = 'Заказ отправил(а) ' + twoNames2(data_page.admin_name)+'.',
  dateNow = new Date().toISOString().slice(0,10);
  dateNow = dateStrToddmmyyyyToyyyymmdd(dateNow, true);

  if ($('#orderDate').text() === '00.00.0000' || $('#orderDate').text() === '' || $('#orderDate').text() === 'null' || $('#orderDate').text() === null || $('#orderDate').text() === 'undefined' || $('#orderDate').text() === undefined) {
    isFirstOrder = true;
  } else {
    comment = comment + ' Первый заказ был ' + $('#orderDate').text() + '\n';
    commentNotForCrm =  commentNotForCrm + ' Первый заказ был ' + $('#orderDate').text() + '. ' + notesOfAdmin + '\n';
  }

  commentNotForCrm = commentNotForCrm + $('#commentContact').val();

  //$('#saveContact').attr('data-id_admin') ? data.admin = $('#saveContact').attr('data-id_admin') : data.admin = window.adminId;

  function addCrmId(crm_id) {
    if (crm_id.result === true) {
      crm_id = '';
    }
    var text = 'Отправлен заказ.';
    if (!isFirstOrder) {
      text = text + ' Первый заказ был ' + $('#orderDate').text() + '.';
    }
    $.get('/ajax/contacts.php?add_crm_id', {crm_id: crm_id, id: $('#saveContact').attr('data-id'), text: text, comment: commentNotForCrm, notes: notesOfAdmin})
    .done(function(data){
    });
  }

    if (ua === 'UA') {
      var textMessage = 'ФИО: '+ name +'<br>Страна: '+ country +'<br>Область: '+ region +'<br>Район: '+ area +'<br>Местность: '+ locality +'<br>Адрес: '+ address +'<br>Индекс:'+ index  +'<br>Количество: '+ howMuch +'<br>Телефон: '+ phone +'<br>Емайл: '+ email +'<br>Комментарий: '+ commentNotForCrm + '. ' + notesOfAdmin +'<br>Отправлено с сайта: '+ dateNow + '<br>Отправил: '+ twoNames2(data_page.admin_name);
      $.post('ajax/contacts.php', {text_message: textMessage, name: twoNames2(data_page.admin_name)})
      .done (function(data) {
          if (data) {
            addCrmId(data);
            showHint('Заказ отправлен команде проекта BFA');
          } else {
            showError('Что то пошло не так, обратитесь в тех. поддержку');
            console.log(data);
          }
      });
    } else {
      $.post('crmapi.php', {name: name, value3: country, value4: region, value5: area, value6: locality, value7: address, value8: index, value1: howMuch, phone: phone, email: email, value2: comment})
      .done (function(data) {
        if (data === 'Failed') {
          showError('Что то пошло не так, обратитесь в тех. поддержку');
        } else {
          addCrmId(data);
          showHint('Заказ отправлен команде проекта BFA');
        }
      });
    }
  }

  $('#orderSentToContact').click(function() {
    $('#adminNotes').val('');
  });

  $('#saveConfirmBtn').click(function() {
    if (!$('#nameContact').val()) {
      showError('Заполните поле ФИО');
      $('#nameContact').css('border-color', 'red');
      return
    } else if (!$('#phoneContact').val()) {
      showError('Заполните поле Телефон');
      $('#phoneContact').css('border-color', 'red');
      return
    } else if (!$('#regionContact').val()) {
      showError('Заполните поле Область');
      $('#regionContact').css('border-color', 'red');
      return
    } else if (!$('#localityContact').val()) {
      showError('Заполните поле Населённый пункт');
      $('#localityContact').css('border-color', 'red');
      return
    } else if (!$('#indexContact').val()) {
      showError('Заполните поле Индекс');
      $('#indexContact').css('border-color', 'red');
      return
    } else if (!$('#addressContact').val()) {
      showError('Заполните поле Адрес');
      $('#addressContact').css('border-color', 'red');
      return
    } else {
      $('#addressContact').css('border-color', '#ced4da');
      $('#indexContact').css('border-color', '#ced4da');
      $('#localityContact').css('border-color', '#ced4da');
      $('#regionContact').css('border-color', '#ced4da');
      $('#phoneContact').css('border-color', '#ced4da');
      $('#nameContact').css('border-color', '#ced4da');
    }
    $('#blankHistory').show();
    $('#orderSentToContact').attr('disabled', true);
    saveEditContact();

    if ($('#countryContact').val() === 'UA') {
      setTimeout(function () {
        sendTheOrder('UA');
        $('#saveConfirm').hide();
      }, 500);
    } else {
      setTimeout(function () {
        sendTheOrder();
        $('#saveConfirm').hide();
      }, 500);
    }
  });
  $('#appointResponsibleShow').click(function() {
    $('#setResponsibleModal').modal().show();
  })

// checking changed any fields in the blank
    function checkChangedForSave() {
      if ($('#nameContact').val() === $('.active_string').find('.data_name').text() && $('#countryContact').val() === $('.active_string').attr('data-country_key') && $('#phoneContact').val() === $('.active_string').find('.data_phone').text() && $('#emailContact').val() === $('.active_string').attr('data-email') && $('#maleContact').val() === $('.active_string').attr('data-male') && $('#regionContact').val() === $('.active_string').attr('data-region') && $('#areaContact').val() === $('.active_string').attr('data-area') && $('#localityContact').val() === $('.active_string').find('.data_locality').text() && $('#indexContact').val() === $('.active_string').attr('data-index_post') && $('#addressContact').val() === $('.active_string').attr('data-address') && $('#commentContact').val() === $('.active_string').attr('data-other') && $('#regionWorkContact').val() === $('.active_string').attr('data-region_work') && $('#responsibleContact').val() === $('.active_string').attr('data-responsible') && $('#responsibleContact').attr('data-responsible') === $('.active_string').attr('data-responsible') &&  $('#responsibleContact').attr('data-responsible_previous') === $('.active_string').attr('data-responsible_previous') &&  $('#saveContact').attr('data-id') === $('.active_string').attr('data-id') && ($('#statusContact').val() === $('.active_string').attr('data-status_key')) && (($('#orderDate').text() === $('.active_string').attr('data-order_date')) || ($('#orderDate').text() === '' &&  $(this).attr('data-order_date') === undefined))
      ){
        return false
      } else {
        return true
      }

      // (!$('.active_string').attr('data-status_key') && $('#statusContact').val() === null);
      //$('#sendingDate').text() === $(this).attr('data-sending_date');
    }

  $('#orderDate').hide();
  $('#sendingDate').parent().hide();

  function filtersOfString() {
    var filterBlank, text, fio, loc, region, searchResult;
    text = $('#search-text').val().trim();

    $('.contacts_str').each(function() {

      // Search text
      if (text.length > 2) {
        fio = $(this).find('.data_name').text().trim();
        loc = $(this).find('.data_locality').text().trim();
        region = $(this).attr('data-region');
        searchResult = true;
        if (fio.toLowerCase().indexOf(String(text.toLowerCase())) === -1  && loc.toLowerCase().indexOf(String(text.toLowerCase())) === -1 && region.toLowerCase().indexOf(String(text.toLowerCase())) === -1) {
          searchResult = false;
        }
      } else {
        searchResult = true;
      }
      // STOP Search text

      //Filter currents blanks
        data_page.admin_role === '0' ? filterBlank = ($('#myBlanks').val() === '1' && $(this).attr('data-responsible') === window.adminId) || ($('#myBlanks').val() === '0' && $(this).attr('data-responsible_previous') === window.adminId) : filterBlank = true;
      //Stop Filter currents blanks

        if (($('#maleShow').val() === '_all_' || $('#maleShow').val() === $(this).attr('data-male')) && ($('#statusShow').val() === '_all_' || $('#statusShow').val() === $(this).attr('data-status_key')) && ($('#respShow').val() === '_all_' || $('#respShow').val() === $(this).attr('data-responsible')) && filterBlank && searchResult) {
          $(this).show();
        } else {
          $(this).hide();
          if ($('.cd-panel-watch').hasClass('cd-panel--is-visible-watch') && $('#saveContact').attr('data-id') === $(this).attr('data-id')) {
            clearingBlankOfContact();
            $('.cd-panel-watch').removeClass('cd-panel--is-visible-watch');
          }
        }
    });
  }

  $('#maleShow, #statusShow, #respShow, #myBlanks').change(function (event) {
    event.stopPropagation();
    filtersOfString();
  });

  $('#search-text').bind("paste keyup", function(event){
    event.stopPropagation();
    filtersOfString();
    });
// change date in the blank
    $('#orderDateEditIco').click(function(){
      if (data_page.admin_role === '0' && $('#myBlanks').val() === '0') {
        return
      }
      $('#orderDateEdit').parent().show();
      $(this).hide();
    });

    $('#orderDateEditIcoCancel').click(function(){
      $('#orderDateEditIco').show();
      $(this).parent().hide();
    });

    $('#orderDateEditIcoOk').click(function(){
      if ($('#orderDateEdit').val()) {
        $('#orderDate').show();
        var dateorder = dateStrToddmmyyyyToyyyymmdd($('#orderDateEdit').val(), true);
        $('#orderDate').text(dateorder);
        $('#labelOrderDate').text('Заказ НЗ от ');
      } else {
        $('#labelOrderDate').text('Заказа НЗ не было');
        $('#orderDate').hide();
        $('#orderDate').text('');
      }
      $('#orderDateEditIco').show();
      $(this).parent().hide();
    });


// STOP change date in the blank
  /*$('#openUploadModal').click(function() {
    $('#uploadModal').modal('show');
  });

// MODAL UPLOAD
  $('#closeUploadModal').click(function() {
    $('#uploadModal').modal('hide');
  });*/

  //$('#listContacts').html();

});
