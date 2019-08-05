$(document).ready (function (){

  loadDashboard (false);
  renewComboLists('.members-lists-combo');
// LOCALITY FILTER START
  function renderLocalities(localities){
      var localities_list = [],
          selectedLocality = globalSelMemberLocality;

        localities_list.push("<option value='_all_' " + (selectedLocality =='_all_' ? 'selected' : '') +" >Все местности</option>");

      for (var l in localities){
          var locality = localities[l];
          localities_list.push("<option value='"+locality['id']+"' " + (selectedLocality == l ? 'selected' : '') +" >"+he(locality['name'])+"</option>");
      }

      $("#selMemberLocality").html(localities_list.join(''));
    }

  function get_localities(){
        $.get('/ajax/members.php?get_localities')
        .done (function(data) {
            renderLocalities(data.localities);
        });
    }

  get_localities();

  function getAdminReg() {
    $.post('/ajax/visits.php?get_list_admins', {})
      .done(function(data){
      });
  }
// START DASHBOARD
  function loadDashboard (reload){
    if (reload) {
      var startDate = formatDateDotToDash($('.start-date').val(), false),
      stopDate = formatDateDotToDash($('.end-date').val(), true),
      localityFilter = $("#selMemberLocality").val(),
      pageFilter = $("#selMemberCategory").val(),
      listAdminsFilter = $("#listAdmins").val();
          startDate+='%';
          stopDate +='%';
        $.getJSON('/ajax/activity_log.php?get_activity', {start:startDate,stop:stopDate, locality: localityFilter, page: pageFilter, admins: listAdminsFilter})
            .done (function(data) {
                refreshActivity (data.members);
              });
    } else {
      var startDate = formatDateDotToDash($('.start-date').val(), false),
      stopDate = formatDateDotToDash($('.end-date').val(), true);
      startDate+='%';
      stopDate +='%';
      $.getJSON('/ajax/activity_log.php?get_activity', {start:startDate,stop:stopDate, locality: '_all_', page: '_all_', admins: '_all_'})
          .done (function(data) {
              refreshActivity (data.members);
            });
    }
  }
  function refreshNewList(adminId) {
    pageFilter = $("#selMemberCategory").val();
    $('.desctopVisible tbody').find('tr').each(function(){
      if (adminId == $(this).attr('data-id') && !$(this).hasClass('ruler-string') && (pageFilter == $(this).attr('data-id_page') || pageFilter == '_all_')) {
        $(this).is(':visible') ? $(this).hide() : $(this).show();
      } else if ($(this).hasClass('ruler-string') && $(this).is(':visible')) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
    $('.show-phone tbody').find('tr').each(function(){
      if (adminId == $(this).attr('data-id') && !$(this).hasClass('ruler-string') && (pageFilter == $(this).attr('data-id_page') || pageFilter == '_all_')) {
        $(this).is(':visible') ? $(this).hide() : $(this).show();
      } else if ($(this).hasClass('ruler-string') && $(this).is(':visible')) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
  }
  function pageNameTranslator(page) {
    var pageBack;
    switch (page) {
      case "index" : pageBack = 'События'; break;
      case "reg" : pageBack = 'Регистрация'; break;
      case "members" : pageBack = 'Общий список'; break;
      case "youth" : pageBack = 'Молодые люди'; break;
      case "list" : pageBack = 'Ответственные за регистрацию'; break;
      case "meetings" : pageBack = 'Собрания'; break;
      case "visits" : pageBack = 'Посещения'; break;
      case "links" : pageBack = 'Ссылки'; break;
      case "help" : pageBack = 'Помощь'; break;
      case "profile" : pageBack = 'Профиль'; break;
      case "settings" : pageBack = 'Настройки'; break;
      case "login" : pageBack = 'Логин'; break;
      case "passrec" : pageBack = 'Восстановление пароля'; break;
      case "signup" : pageBack = 'Новый аккаунт'; break;
      case "invites" : pageBack = 'Пермалинки'; break;
      case "reference" : pageBack = 'Настройка помощи'; break;
      case "vt" : pageBack = 'Видеообучение'; break;
      case "pd" : pageBack = 'Официальные документы'; break;
      case "pm" : pageBack = 'Молитвенная рассылка'; break;
      case "st" : pageBack = 'Статистика'; break;
      case "rb" : pageBack = 'Обучение в Индии'; break;
      case "os" : pageBack = 'Обучение братьев'; break;
      case "mc" : pageBack = 'Мини-конференции'; break;
      case "ul" : pageBack = 'Избранные ссылки'; break;
      case "event" : pageBack = 'Мероприятия (разр.)'; break;
      case "statistic" : pageBack = 'Архив (разр.)'; break;
      case "activity" : pageBack = 'Активность'; break;
      case "panel" : pageBack = 'Админка (разр.)'; break;
    }
    if (!pageBack) return null

    return pageBack
  }
  function refreshActivity (list, isSort){
      var tableRows = [], phoneRows = [], checkList = [];
      if (!isSort) {
        list.sort(function (a, b) {
          if (a.time < b.time) {
            return 1;
          }
          if (a.time > b.time) {
            return -1;
          }
          return 0;
        });
        list.sort(function (a, b) {
          if (a.name > b.name) {
            return 1;
          }
          if (a.name < b.name) {
            return -1;
          }
          return 0;
        });
      }

      for (var i in list){
        var m = list[i];
        if ((!isSort && checkList.indexOf(m.id) === -1) || (isSort && m.header && checkList.indexOf(m.id) === -1)) {
          checkList.push(m.id);
          tableRows.push('<tr data-id="'+m.id+'" data-name="'+m.name+'" data-locality="'+m.locality_name+'" data-locality_key="'+m.locality_key+'" data-time="'+m.time+'" data-id_string="'+m.id_string+'" data-id_page="" class="ruler-string" style="'+m.visible+'">'+
              /*if (!globalSingleCity) {
                  '<td style="width:160px">' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') +
               }*/
              '<td style="width: 250px;"><b>' + he(m.name) + '</b><br><span class="stts-count" style="width: 250px;"></span></td>' +
              '<td><strong class="stts-pages"></strong></td>' +
              '<td style="width:100px"><b>Последнее<br>' + he(m.time) + '</b></td>' +
              '<td><b>' + he(m.locality_name) + '</b></td></tr>'
          );

          phoneRows.push('<tr data-id="'+m.id+'" data-name="'+m.name+'" data-locality="'+m.locality_name+'" data-locality_key="'+m.locality_key+'" data-time="'+m.time+'" data-id_string="'+m.id_string+'" data-id_page="" class="ruler-string" style="'+m.visible+'">'+
              /*if (!globalSingleCity) {
                  '<td style="width:160px">' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') +
               }*/
              '<td>' + he(m.name) + ' <span class="stts-count" style="padding-left:3px;"></span><br><i>' + he(m.locality_name) + '</i></td>' +
              '<td><span class="stts-pages"></span></td>' +
              '<td style="width:70px">' + he(m.time) + '</td></tr>'
          );
        }
        if (!isSort  || (isSort && m.header === false)) {
          var pageRus = pageNameTranslator(m.page);
          !isSort ? m.visible = 'display: none' : '';
          tableRows.push('<tr data-id="'+m.id+'" data-name="'+m.name+'" data-locality="'+m.locality_name+'" data-locality_key="'+m.locality_key+'" data-time="'+m.time+'" data-id_string="'+m.id_string+'" data-id_page="'+m.page+'" class="" style="'+ he(m.visible) +'">'+
              /*if (!globalSingleCity) {
                  '<td style="width:160px">' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') +
               }*/
              '<td>' + he(m.name) + '</td>' +
              '<td>' + pageRus + '</td>' +
              '<td style="width:100px">' + he(m.time) + '</td>' +
              '<td>' + he(m.locality_name) + '</td></tr>'
          );

          phoneRows.push('<tr data-id="'+m.id+'" data-name="'+m.name+'" data-locality="'+m.locality_name+'" data-locality_key="'+m.locality_key+'" data-time="'+m.time+'" data-id_string="'+m.id_string+'" data-id_page="'+m.page+'" class="" style="'+ he(m.visible) +'">'+
              /*if (!globalSingleCity) {
                  '<td style="width:160px">' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') +
               }*/
              '<td>' + he(m.name) + '<br><i>' + he(m.locality_name) + '</i></td>' +
              '<td>' + pageRus + '</td>' +
              '<td style="width:100px">' + he(m.time) + '</td></tr>'
          );
        }
      }

      $(".desctopVisible tbody").html (tableRows.join(''));
      $(".show-phone tbody").html (phoneRows.join(''));

      $(".ruler-string").unbind('click');
      $(".ruler-string").click (function (e) {
          e.stopPropagation();
          var memberId = $(this).attr('data-id');
          if ($(this).hasClass('active_ruler')) {
            $(this).removeClass('active_ruler')
          } else {
            $('.ruler-string').each(function () {
              $(this).removeClass('active_ruler');
            });
            $(this).addClass('active_ruler');
          }
          refreshNewList(memberId);
      });
      if (checkList) {
        var sessionsCount = [], sessionsCounter, pagersCounter = [], pagersCounterRus = [];
        for (var i = 0; i < checkList.length; i++) {
          isSort ? sessionsCounter = -1:sessionsCounter = 0;
          pagersCounter =[], pagersCounterRus =[];
          sessionsCount.push(checkList[i]);
          for (var ii = 0; ii < list.length; ii++) {
            if (checkList[i] == list[ii].id) {
              sessionsCounter++;
            }
          }
          sessionsCount.push(sessionsCounter);
          for (var iii = 0; iii < list.length; iii++) {
            if (checkList[i] == list[iii].id) {

              if (pagersCounter.indexOf(list[iii].page) === -1) {
                var rusPageName = pageNameTranslator(list[iii].page);
                rusPageName ? pagersCounter.push(list[iii].page) : '';
                rusPageName ? pagersCounterRus.push(rusPageName) : '';
              }
            }
          }
          sessionsCount.push(pagersCounterRus);
        }
          $('.ruler-string').each(function() {
            for (var i = 0; i < sessionsCount.length; i=i+3) {
              if ($(this).attr('data-id') == sessionsCount[i]) {
                  var ab = sessionsCount[i+1];
                  ab = 'визитов ' + ab ;
                  ac = sessionsCount[i+2];
                  console.log('First ',ac);
                  ac = ac.join(', ');
                  console.log('Second ', ac);
                $(this).find('.stts-count').text(ab);
                $(this).find('.stts-pages').text(ac);
              }
            }
          });
      }
  }
// LOCALITY FILTER END
// START DASHBOARD

// Sorted Fields start
  $("a[id|='sort']").click (function (){
    var id = $(this).attr("id");
    var icon = $(this).siblings("i");
    $(".members-list a[id|='sort'][id!='"+id+"'] ~ i").attr("class","icon-none");
    icon.attr ("class", icon.hasClass("icon-chevron-down") ? "icon-chevron-up" : "icon-chevron-down");

    if (id === 'sort-name') {
      icon.hasClass("icon-chevron-down") ? sortingActivity(3) :sortingActivity(4);
    } else if (id === 'sort-page') {
      icon.hasClass("icon-chevron-down") ? sortingActivity(5) :sortingActivity(6);
    } else if (id === 'sort-time') {
      icon.hasClass("icon-chevron-down") ? sortingActivity(7) :sortingActivity(8);
    } else if (id === 'sort-locality') {
      icon.hasClass("icon-chevron-down") ? sortingActivity(9) :sortingActivity(10);
    } else {
      loadDashboard (true);
    }
});
function sortingActivity(sortType) {
  var list = [], tableRows = [], phoneRows = [], isLocationAlone = $('#selMeetingLocality option').length == 2 ?  true : false;
   var aLenght = $('.show-phone:visible');
   if (aLenght.length === 0) {
  $('#members').find('tr').each(function(){
        var memberName = $(this).attr('data-name'),
            timeActivity = $(this).attr('data-time'),
            id_string = $(this).attr('data-id_string'),
            page = $(this).attr('data-id_page'),
            id = $(this).attr('data-id'),
            locality = $(this).attr('data-locality_key'),
            localityName = $(this).attr('data-locality'),
            visibleStts = $(this).is(':visible') ? '':'display: none',
            headerString = false;
            if ($(this).hasClass('ruler-string')) {
              headerString = true;
            }
          if (id) {
        list.push({id_string: id_string, id: id, locality_key: locality, name: memberName, page: page, time: timeActivity, locality_name: localityName, visible: visibleStts, header: headerString});
      }

  });
  } else {
    $('#membersPhone').find('tr').each(function(){
          var memberName = $(this).attr('data-name'),
              timeActivity = $(this).attr('data-time'),
              id_string = $(this).attr('data-id_string'),
              page = $(this).attr('data-id_page'),
              id = $(this).attr('data-id'),
              locality = $(this).attr('data-locality_key'),
              localityName = $(this).attr('data-locality');
              visibleStts = $(this).is(':visible') ? '':'display: none';
              headerString = false;
              if ($(this).hasClass('ruler-string')) {
                headerString = true;
              }
              if (id) {
                list.push({id_string: id_string, id: id, locality_key: locality, name: memberName, page: page, time: timeActivity, locality_name: localityName, visible: visibleStts, header: headerString});
              }
          });
        }
if (sortType == 3) {
  list.sort(function (a, b) {
    if (a.time < b.time) {
      return 1;
    }
    if (a.time > b.time) {
      return -1;
    }
    return 0;
  });
  list.sort(function (a, b) {
    if (a.name > b.name) {
      return 1;
    }
    if (a.name < b.name) {
      return -1;
    }
    return 0;
  });
}

if (sortType == 4) {
  list.sort(function (a, b) {
    if (a.time < b.time) {
      return 1;
    }
    if (a.time > b.time) {
      return -1;
    }
    return 0;
  });
  list.sort(function (a, b) {
    if (a.name < b.name) {
      return 1;
    }
    if (a.name > b.name) {
      return -1;
    }
    return 0;
  });
}
/*if (sortType == 5) {
  list.sort(function (a, b) {
    if (a.page > b.page) {
      return 1;
    }
    if (a.page < b.page) {
      return -1;
    }
    return 0;
  });
  list.sort(function (a, b) {
    if (a.name > b.name) {
      return 1;
    }
    if (a.name < b.name) {
      return -1;
    }
    return 0;
  });
}
if (sortType == 6) {
  list.sort(function (a, b) {
    if (a.page < b.page) {
      return 1;
    }
    if (a.page > b.page) {
      return -1;
    }
    return 0;
  });

  list.sort(function (a, b) {
    if (a.name > b.name) {
      return 1;
    }
    if (a.name < b.name) {
      return -1;
    }
    return 0;
  });
}*/
if (sortType == 7) {
  list.sort(function (a, b) {
    if (a.time > b.time) {
      return 1;
    }
    if (a.time < b.time) {
      return -1;
    }
    return 0;
  });
  /*list.sort(function (a, b) {
    if (a.name > b.name) {
      return 1;
    }
    if (a.name < b.name) {
      return -1;
    }
    return 0;
  });*/
}
if (sortType == 8) {
  list.sort(function (a, b) {
    if (a.time < b.time) {
      return 1;
    }
    if (a.time > b.time) {
      return -1;
    }
    return 0;
  });/*
  list.sort(function (a, b) {
    if (a.name > b.name) {
      return 1;
    }
    if (a.name < b.name) {
      return -1;
    }
    return 0;
  });*/
}
if (sortType == 9) {
  list.sort(function (a, b) {
    if (a.locality_name > b.locality_name) {
      return 1;
    }
    if (a.locality_name < b.locality_name) {
      return -1;
    }
    return 0;
  });/*
  list.sort(function (a, b) {
    if (a.name < b.name) {
      return 1;
    }
    if (a.name > b.name) {
      return -1;
    }
    return 0;
  });*/
}
if (sortType == 10) {
  list.sort(function (a, b) {
    if (a.locality_name < b.locality_name) {
      return 1;
    }
    if (a.locality_name > b.locality_name) {
      return -1;
    }
    return 0;
  });/*
  list.sort(function (a, b) {
    if (a.name > b.name) {
      return 1;
    }
    if (a.name < b.name) {
      return -1;
    }
    return 0;
  });*/
}

  refreshActivity(list, true);

  }
// SortedFields end
// filters start
  function filterMembers(){
      var isTabletMode = $(document).width()<786,
          localityFilter = $("#selMemberLocality").val(),
          pageFilter = $("#selMemberCategory").val(),
          //attendMeetingFilter = $("#selMemberAttendMeeting").val(),
          listAdminsFilter = $("#listAdmins").val(),
          text = $('.search-text').val().trim().toLowerCase(),
          filteredMembers = [],
          localityList = [],
          counterVisits = [],
          counterAdmins = [];
      if(localityFilter){
          localityList = localityFilter.split(',');
      }

      $(".members-list " + ( isTabletMode ? " #membersPhone " : " #members" ) + " tbody tr").each(function(){
          var memberLocality = $(this).attr('data-locality_key'),
              memberCategory = $(this).attr('data-category'),
              memberPage = $(this).attr('data-id_page'),
              memberName = $(this).attr('data-name').toLowerCase(),
              memberKey = $(this).attr('data-id');

          if(((localityFilter === '_all_' || localityFilter === undefined) && pageFilter === '_all_' && text === '' && listAdminsFilter === '_all_' && $(this).hasClass('ruler-string')) ||

              (
                  ((in_array(memberLocality, localityList) && ($(this).hasClass('ruler-string') || $(this).is(':visible'))) || localityFilter === '_all_' || (localityFilter === undefined && localityList.length === 0))  &&
                  ((memberPage === pageFilter && $(this).is(':visible')) || pageFilter === '_all_' || $(this).hasClass('ruler-string')) && ((memberKey === listAdminsFilter && ($(this).hasClass('ruler-string') || $(this).is(':visible'))) || listAdminsFilter === '_all_')) && (memberName.search(text) !== -1) && (!(localityFilter === '_all_' && pageFilter === '_all_' && listAdminsFilter === '_all_') || $(this).is(':visible')))
              {
                $(this).hasClass('ruler-string') ? counterAdmins.push(memberKey):'';
                if (!$(this).hasClass('ruler-string') && (pageFilter === memberPage || pageFilter ==='_all_')) {
                  counterVisits.push({id:memberKey, page: memberPage});
                }
                $(this).show();
                filteredMembers.push(memberKey);

              } else {
                $(this).hasClass('ruler-string') ? counterAdmins.push(memberKey):'';
                if (!$(this).hasClass('ruler-string') && (pageFilter === memberPage || pageFilter ==='_all_')) {
                  counterVisits.push({id:memberKey, page: memberPage});
                }
                $(this).hide();
              }
      });

      if (counterAdmins) {

        var sessionsCount = [], sessionsCounter;
        for (var i = 0; i < counterAdmins.length; i++) {
          sessionsCounter = 0;
          sessionsCount.push(counterAdmins[i]);
          for (var ii = 0; ii < counterVisits.length; ii++) {
            if (counterAdmins[i] == counterVisits[ii].id) {
              sessionsCounter++;
            }
          }
          sessionsCount.push(sessionsCounter);
        }
          $('.ruler-string').each(function() {
            for (var i = 0; i < sessionsCount.length; i=i+2) {
              if ($(this).attr('data-id') == sessionsCount[i]) {
                  var ab = sessionsCount[i+1];
                  ab = 'визитов ' + ab ;
                $(this).find('.stts-count').text(ab);
              }
            }
          });
      }
      return filteredMembers;
  }

  $("#selMemberLocality").change (function (){
      setCookie('selMemberLocality', $(this).val());
      filterMembers();
  });

  $("#listAdmins").change(function(){
    setCookie('selAttendMeeting', $(this).val());
    filterMembers();
  });

  $("#selMemberCategory").change (function (){
      setCookie('selMemberCategory', $(this).val());
      filterMembers();
  });
  $('.search-text').bind("paste keyup", function(event){
      event.stopPropagation();
      filterMembers();
  });

  $(".clear-search-members").click(function(){
     $(this).siblings('input').val('');
     filterMembers();
  });

  $(".start-date").change (function (){
      loadDashboard(true);
  });

  $(".end-date").change (function (){
      loadDashboard(true);
  });

  function formatDateDotToDash (date, plusOne) {
    var dateParts = date.split(".");
    var day = plusOne === true ?  dateParts[0]+1 : dateParts[0]-1;
    var getNewDate = dateParts[2] + '-' + dateParts[1] + '-' + day;
	   return getNewDate;
   }
   renewComboLists('.meeting-lists-combo');

// STOP DOCUMENT READY
});
