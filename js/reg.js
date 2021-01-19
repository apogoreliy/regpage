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

    // events for schoolers
    if ($('.tab-pane.active').attr('data-event_type') === 'SCC' || $('.tab-pane.active').attr('data-event_type') === 'RYC' || $('.tab-pane.active').attr('data-event_type') === 'TSC' || $('.tab-pane.active').attr('data-event_type') === 'RTS') {
      $('.emAccom').prev().text('Группа');
      $('.emMateLbl').text('Служащий');
      /*
      if ($('.tab-pane.active').attr('id') === 'eventTab-20190018') {
          $('.emAccom').val('1');
          $('.emAccom').attr('disabled', true);
          $('.emAccom').prev().text('Группа');
          $('.emAccom').parent().removeClass('error');
          $('.emMateLbl').text('Служащий');
      }
      */
    } else {
      if ($('.emAccom').prev().text() === 'Группа') {
        $('.emAccom').prev().text('Размещение*');
        $('.emMateLbl').text('Разместить с');
        // убрать
        if ($('.emAccom').attr('disabled')) {
          $('.emAccom').attr('disabled', false);
        }
        // убрать
        if (!$('.emAccom').val() && !$('.emAccom').parent().hasClass('error')) {
          $('.emAccom').parent().addClass('error');
        }
      }
    }

    setTimeout(function () {
      var form = $('#modalEditMember');
      var a = parseDDMM (form.find(".emArrDate").val());
      var b = parseDDMM (form.find(".emDepDate").val());
        if(!$('.emArrDate').parent().hasClass('error') && !a){
          $('.emArrDate').parent().addClass('error');
        }
        if(!$('.emDepDate').parent().hasClass('error') && !b){
          $('.emDepDate').parent().addClass('error');
        }
        if ((!a || !b) && !$('#btnDoRegisterMember').hasClass('disabled')) {
          $('#btnDoRegisterMember').addClass('disabled')
        }
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

// START Hide Global Error
$('#globalError').find('.close-alert').click(function () {
  $('#globalError').hide();
});
// STOP table present Hide Global Error

// START table present
function loadDashboardTbl (eventId){
    if (!eventId) eventId = $("#events-list").val();
    var request = getRequestFromFilters(setFiltersForRequest(eventId));

    $.getJSON('/ajax/dashboard.php?event='+eventId+request)
    .done (function(data) {
        refreshEventMembersTbl (eventId, data.members, data.localities);
    });
}

//var localityGlo = [];
function refreshEventMembersTbl (eventId, members, localities){
    var tableRows = [];
    var phoneRows = [];

    function get_localities(){
        $.get('/ajax/members.php?get_localities')
        .done (function(data) {
            $(data.localities).each(function(i) {
              var localityTemp = data.localities[i];
              a = localityTemp.id;
              b = localityTemp.name;
              localities[a] = b;
              localityGlo[a] = b;
            });
        });
    }
    get_localities();
setTimeout(function () {
    buildFilterLocalitiesList(eventId, localities);

    var showLocalityField = $("#eventTab-"+eventId).attr("data-show-locality-field") ===  '1';

    for (var i in members){
        var m = members[i];

        // *** last editor
        var notMe = (m.mem_admin_key && m.mem_admin_key!=window.adminId) || (m.reg_admin_key && m.reg_admin_key!=window.adminId);
        // if the author is same for reg and mem records is was decided to show it only once
        var editors = (m.mem_admin_key ? m.mem_admin_name : '') +
                      (m.mem_admin_key && m.reg_admin_key && m.mem_admin_key != m.reg_admin_key ? ' и ' : '') +
                      (m.reg_admin_key && m.mem_admin_key != m.reg_admin_key ? m.reg_admin_name : '');
        var htmlEditor = notMe ? '<i class="icon-user" title="Последние изменения: '+editors+'"></i>': '';

        // *** changes processed
        var htmlChanged = (m.changed > 0 ? '<i class="icon-pencil" title="Изменения еще не обработаны командой регистрации"></i>' : '');

        // *** email sending result
        var htmlEmail = '';
        if (m.send_result!='')
            if (m.send_result=='ok')
                htmlEmail = '<i class="icon-envelope show-messages" title="Письмо было отправлено"></i>';
            else if (m.send_result=='queue')
                htmlEmail = '<i class="icon-time" title="Письмо ждет отправки"></i>';
            else
                htmlEmail = '<i class="icon-warning-sign" title="'+he(m.send_result)+'"></i>';

        // *** living place
        var htmlPlace = m.place!=null ? '<i class="icon-flag"></i>' : '';
        var htmlPlaceFlag = m.place!=null ? '<span style="font-size:10px; color:grey;">'+he(m.place)+'</span>' : '';

        // *** service
        var htmlService = m.service ? '<i class="fa fa-wrench" title="'+he(m.service)+'" aria-hidden="true"></i>' :'';
        var coordFlag = m.coord == '1' ? '<i title="Координатор" class="fa fa-random" aria-hidden="true"></i>' : '';

        var dataItems = 'data-accom="'+m.accom+'" data-transport="'+m.transport+'" data-locality_key="'+m.locality_key+'" data-male="'+m.male+'" '+
                'data-parking="'+m.parking+'" data-regstate="'+m.regstate+'" data-prepaid="'+m.prepaid+'" data-locality="'+he(m.locality)+'"' +
                'data-attended="'+m.attended+'" data-aid_paid="'+(m.aid_paid || 0)+'" data-paid="'+m.paid+'" '+
                'data-place="'+(m.place || "") +'" data-service="'+m.service_key+'" data-status="'+m.status_key+'" data-coord="'+m.coord+'" data-mate="'+m.mate_key+'" '+
                'data-aid_amount="'+m.contr_amount+'" data-comment="'+he(m.admin_comment.length > 0 ? 1 : 0)+'" data-currency="'+(m.currency || '') +'"';
        // console.log(m);

        // Cut the m.region string. Roman's code ver 5.0.1
        if (!m.region) {
          m.region = '--';
        } else if (m.region =='--') {
          m.region = m.country;
        } else {
          m.region = m.region.substring(0, m.region.indexOf(" ("));
          // m.region += ', ';
          // m.region += m.country;
        }

        tableRows.push('<tr class="regmem-'+m.id+'" '+ dataItems +' >'+
            '<td class="style-checkbox"><input type="checkbox"></td>'+
            '<td class="style-name mname '+(m.male==1?'male':'female')+'"><span class="mname1">'+ he(m.name) + '</span></td>' +
            (showLocalityField ? '<td class=style-city>' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') +
            (in_array(2, window.user_settings) ? '<br/>'+ '<span class="user_setting_span">'+(m.region || m.country)+'</span>' : '') +
                '</td>' : '') +
            '<td class="style-cell hide-tablet">' + he(m.cell_phone) +
            (in_array(3, window.user_settings) ? '<br/>'+ '<span>'+m.email+'</span>' : '') +
            '</td>' +
            '<td><span class="mnameCategory" '+(in_array(1, window.user_settings) ? '' : ' style="display: none;"')+'>'+m.category_name+'</span></td><td><span>'+(m.service ? he(m.service) : '')+ '</span></td>'+
            '<td>*</td><td>'+m.paid+'</td><td>'+m.parking+'</td>'+
            '<td class="style-serv hide-tablet"><div>'+ (m.status ? he(m.status) : '') +'<br></div>' + ( m.coord == '1' ? '<div>Координатор</div>' : '') + '</td>' +
            '<td class="style-date"><span class="arrival" data-date="' + he(m.arr_date) + '" data-time="' + he(m.arr_time) + '">' + formatDDMM( m.arr_date) + '</span> - '+
            '<span class="departure" data-date="' + he(m.dep_date) + '" data-time="' + he(m.dep_time) + '">'+ formatDDMM(m.dep_date) + '</span><br>'+htmlPlace + ' ' +htmlPlaceFlag+'</td>'+
            '<td>' + htmlLabelByRegState(m.regstate, m.web) +
            '<ul class="regstate-list-handle">'+ htmlListItemsByRegstate(m.regstate, m.attended) + '</ul>'+
            "<div class='regmem-icons'>"+ htmlEmail + htmlChanged + htmlEditor + '</div></td>'
            + '</tr>'
        );

        phoneRows.push ('<tr class="regmem-'+m.id+'" '+ dataItems +' >'+
            '<td class="arrival" data-date="' + he( m.arr_date) + '" data-time="' + he(m.arr_time) + '"><input type="checkbox"></td>'+
            '<td class="departure" data-date="' + he(m.dep_date) + '" data-time="' + he(m.dep_time) + '"><span class="mname '+(m.male==1?'male':'female')+'"><span class="mname1">' + he(m.name) + '</span>' +
(in_array(1, window.user_settings) ? '<br/>'+ '<span class="user_setting_span mnameCategory">'+m.category_name+'</span>' : '') +
            "</span> " +
            (showLocalityField ? '<div>' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') +(in_array(2, window.user_settings) ? '<br/>'+ '<span class="user_setting_span">'+(m.region || m.country)+'</span>' : '') +
                '</div>' : '') +
            '<div><span>'+ he(m.cell_phone) + '</span>'+ (m.cell_phone && m.email ? ', ' :' ' )+
            (in_array(3, window.user_settings) ? '<br/>'+ '<span class="user_setting_span">'+m.email+'</span>' : '') +
            '</div>'+
            '<div><span>' + (m.status ? he(m.status) : '') + '<br>'+ (m.service ? he(m.service) : '') + '</span></div>' +
            '<div><span class="arrival" data-date="' + he(m.arr_date) + '" data-time="' + he(m.arr_time) + '">' +
            formatDDMM(m.arr_date) + '</span>'+
            '<span class="departure" data-date="' + he(m.dep_date) + '" data-time="' + he(m.dep_time) + '">'+ ' - '+formatDDMM(
                m.dep_date) + '</span>'+ htmlPlace + ' ' +htmlPlaceFlag + '</div>'+
            '<span>' + htmlLabelByRegState(m.regstate, m.web) +
            '<ul class="regstate-list-handle">'+ htmlListItemsByRegstate(m.regstate, m.attended) + '</ul>'+
            " <span class='regmem-icons'>" + coordFlag + htmlService + htmlEmail + htmlChanged + htmlEditor + '</span></span>'
            + '</td></tr>');
    }

    $("#eventTab-"+eventId+" table.reg-list tbody").html (tableRows.join(''));
    $("#phoneRegList-"+eventId+" tbody").html (phoneRows.join(''));

    var elemList = $("tr[class|='regmem']");
    elemList.unbind('click');
    elemList.click (function (){
        var memberId = $(this).attr ('class').replace(/^regmem-/,'');
            eventId = $("#events-list").val();
        var localityKeyMem = $(this).attr('data-locality_key');
        var localityValid = 0;
        for (var j in localityGlo) {
          if (localityKeyMem == j) {
            localityValid = 1;
          }
        }

        $.getJSON('/ajax/get.php', { member: memberId, event: eventId, fullList: localityValid})
        .done (function(data){
            fillEditMember (memberId, data.eventmember, data.localities);
            setAdminRole(memberId);
            $('#modalEditMember').attr('data-member_id', memberId);
            $('#modalEditMember').attr('data-locality_key', localityKeyMem);
            $('#modalEditMember').modal('show');
            $('.emName').removeClass('create-member');
        });
    });

    var elemCheckbox = $("tr[class|='regmem'] input[type='checkbox']");
    elemCheckbox.unbind('click');
    elemCheckbox.click (function (event){
        event.stopPropagation();
    });

    elemCheckbox.unbind('change');
    elemCheckbox.change (function (){
        updateTabPaneButtons ($(this).parents('div.tab-pane'));
        updateTabPaneAdditionalButtons($(this).parents('div.tab-pane'));
    });

    $("#eventTab-"+eventId+" table.reg-list th input[type='checkbox']").prop ('checked', false);
    updateTabPaneButtons ($('#eventTab-'+eventId));
    updateDownloadButton($('#eventTab-'+eventId), members.length);
    filterMembers();

    $(".downloadItems").unbind('click');
    $('.downloadItems').click(function(){
        var fields = [];
        if ($('.tab-pane.active').attr('data-need_tp') === '1') {
          function checkForInternationalEvent(elementId) {
            if ($(elementId).prop('checked')===true) {
              fields.push({'name': $(elementId).attr('data-download'), 'value': $(elementId).siblings("label").text()});
            };
          }
          checkForInternationalEvent('#download-city-member');
          checkForInternationalEvent('#download-country');
          checkForInternationalEvent('#download-birth-date-member');
          checkForInternationalEvent('#download-tp');
          checkForInternationalEvent('#download-post');
          checkForInternationalEvent('#download-phone-member');
          checkForInternationalEvent('#download-email-member');
          checkForInternationalEvent('#download-outline-language');
          checkForInternationalEvent('#download-study-group-language');
          checkForInternationalEvent('#download-arr-dep-date');
          checkForInternationalEvent('#download-flight-arr');
          checkForInternationalEvent('#download-arr-dep-time');
          checkForInternationalEvent('#download-airport-arrival');
          checkForInternationalEvent('#download-dep-date');
          checkForInternationalEvent('#download-flight-dep');
          checkForInternationalEvent('#download-dep-time');
          checkForInternationalEvent('#download-airport-departure');
        } else {
          $("#modalDownloadItems").find(".search-checkbox input[type='checkbox']").each(function(){
              if ($(this).prop('checked')===true && $(this).attr('id') != 'member_name'){
                  fields.push({'name': $(this).attr('data-download'), 'value': $(this).siblings("label").text()});
              }
          });
        }

        var item = $('.downloadExl').data('download'),
            eventIdReal = $("#events-list").val(),
            eventType = $("#eventTab-"+eventIdReal).attr("data-event_type"),
            needTranslate = $("#modalDownloadItems #download-translate").prop('checked');



        getDataToDownload(item, eventIdReal, eventType, fields, needTranslate);
    });

    $(".downloadExl").unbind('click');
    $(".downloadExl").click(function(event){
        event.stopPropagation();
        var item = $(this).attr('data-download');
        var eventIdReal = $("#events-list").val();
        var eventType = $("#eventTab-"+eventIdReal).attr("data-event_type");

        if(item === 'all'){
            var eventNeedFlight = $("#eventTab-"+eventIdReal).attr("data-need_flight") == '1',
                modal = $('#modalDownloadItems');
            eventNeedFlight ? modal.find(".translate").show() : modal.find(".translate").hide();

            modal.find('#check-all').prop('checked', true);
            modal.find('#uncheck-all').prop('checked', false);

            var custom_input = modal.find('.custom-download-item'),
                custom_list_item = $('.tab-pane.active').attr('data-custom_list_item');

            if(custom_list_item){
                custom_input.show();
                custom_input.find('label').text(custom_list_item)
            }
            else{
                custom_input.hide();
                custom_input.find('label').text('');
            }

            modal.modal('show').find("input[type='checkbox']").each(function(){
              if (!eventNeedFlight && $(this).parents('div').hasClass('translate')) {
                if ($(this).attr('id') != 'member_name') {
                  $(this).prop('checked', false);
                  $(this).attr('disabled', false);
                  //$(this).parent().show();
                }
              } else if ($('.tab-pane.active').attr('data-need_tp') === '1'){
                if ($(this).attr('id') != 'member_name') {
                  $(this).prop('checked', true);
                  //$(this).parent().show();
                  //$(this).attr('disabled', false);
                }
              } else {
                if ($(this).attr('id') != 'member_name') {
                  $(this).prop('checked', true);
                  $(this).attr('disabled', false);
                  //$(this).parent().show();
                }
              }
            });
        xlxCheckboxesInternationalDisabled();
        }
        else{
            getDataToDownload(item, eventIdReal, eventType);
        }
    });

    $(".btnRegstate").click(function(e){
        e.stopPropagation();
        var modal = $("#modalMemberRegstate");

        modal.modal('show');
    });

    $(".btnHandleRegstate").unbind('click');
    $(".btnHandleRegstate").click(function(e){
        e.stopPropagation();
        var thisSibling = $(this).siblings('.regstate-list-handle');

        if(thisSibling.css('display') === 'block'){
                thisSibling.css('display', 'none');
        }
        else{
            $('.regstate-list-handle').each(function(){
                if(thisSibling !== $(this))
                    $(this).css('display', 'none');
           });
           thisSibling.css('display', 'block');
        }
    });

    $(".regstate-list-handle li").unbind('click');
    $(".regstate-list-handle li").click(function(e){
       e.stopPropagation();
       var setstate = $(this).attr('data-action'), eventId = $("#events-list").val(), memberId = $(this).parents("tr").attr('class').replace(/^regmem-/,'');

       $('.regstate-list-handle').css('display', 'none');

       var request = getRequestFromFilters(setFiltersForRequest(eventId));

       $.getJSON('/ajax/set.php?set_state'+request, {event:eventId,  memberId: memberId, setstate: setstate })
       .done (function(data) {
           refreshEventMembers (eventId, data.members, data.localities);
       });
    });

    $(".show-messages").click(function(e){
        e.stopPropagation();
        var memberId = $(this).parents('tr').attr('class').replace(/^regmem-/,''),
            eventId = $("#events-list").val();

        $.post('/ajax/get.php?get_user_emails', {memberId:memberId, eventId:eventId}).done(function(data){
            if(data.emails){
                buildUserEmailsList(data.emails);
            }
            else{
                showHint("Для данного участника нет отправленных писем.");
            }
        });
    });
  }, 200);
}
$('#tblPresents').click(function () {
  if ($('#events-list').val() && !$(this).hasClass('table_present_activated')) {
    $('.table_present').show();
    loadDashboardTbl($('#events-list').val());
    $(this).hasClass('table_present_activated') ? '' : $(this).addClass('table_present_activated');
    showHint("Режим таблицы ключен.");
  } else if ($('#events-list').val() && $(this).hasClass('table_present_activated')) {
    $('.table_present').hide();
    loadDashboard($('#events-list').val());
    $(this).hasClass('table_present_activated') ? $(this).removeClass('table_present_activated') : '';
    showHint("Режим таблицы выключен.");
  } else if (!$('#events-list').val()) {
    $(this).hasClass('table_present_activated') ? $(this).removeClass('table_present_activated') : '';
    showError("Отсутствует мероприятие.");
  } else {

  }
});
// Stop table present
