$(document).ready (function (){

  loadDashboard (false);

  function getAdminsByCountry(){
    $.get('/ajax/activity_log.php?get_admins_name')
    .done (function(data) {
    });
    /*$.get('/ajax/activity_log.php?get_all_my_admin')
    .done (function(data) {
      console.log(data);
    });
    $.get('/ajax/activity_log.php?get_admins_by_region')
     .done (function(data) {
       console.log(data);
     });
    $.get('/ajax/activity_log.php?get_admins_by_country')
      .done (function(data) {
        console.log(data);
      });
    $.get('/ajax/activity_log.php?get_admins_by_locality')
      .done (function(data) {
          console.log(data);
      });
    $.get('/ajax/activity_log.php?get_locality_by_admin')
      .done (function(data) {
           console.log(data);
      });
    $.get('/ajax/activity_log.php?get_regions_by_admin')
      .done (function(data) {
            console.log(data);
       });
    $.get('/ajax/activity_log.php?get_country_by_admin')
      .done (function(data) {
          console.log(data);
       });*/
    }
getAdminsByCountry();
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
          console.log(startDate, ' and, ',stopDate);
        $.getJSON('/ajax/activity_log.php?get_activity', {start:startDate,stop:stopDate, locality: localityFilter, page: pageFilter, admins: listAdminsFilter})
            .done (function(data) {
              //console.log(data);
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
    $('.desctopVisible tbody').find('tr').each(function(){
      if (adminId == $(this).attr('data-id') && !$(this).hasClass('ruler-string')) {
        $(this).is(':visible') ? $(this).hide() : $(this).show();
      } else if ($(this).hasClass('ruler-string')) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
    $('.show-phone tbody').find('tr').each(function(){
      if (adminId == $(this).attr('data-id') && !$(this).hasClass('ruler-string')) {
        $(this).is(':visible') ? $(this).hide() : $(this).show();
      } else if ($(this).hasClass('ruler-string')) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
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
        if (checkList.indexOf(m.id) === -1) {
          checkList.push(m.id);
          tableRows.push('<tr data-id="'+m.id+'" data-name="'+m.name+'" data-locality="'+m.locality_name+'" data-locality_key="'+m.locality_key+'" data-time="'+m.time+'" data-id_string="'+m.id_string+'" data-id_page="'+m.page+'" class="ruler-string" style="background-color: lightblue;">'+
              '<td class="stts-count">' + he(m.id_string) +
              /*if (!globalSingleCity) {
                  '<td style="width:160px">' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') +
                  '</td>' +
               }*/
              '<td>' + he(m.name) + '</td>' +
              '<td class="stts-pages">' + he(m.page) + '</td>' +
              '<td style="width:100px">' + he(m.time) + '</td>' +
              '<td>' + he(m.locality_name) + '</td>' +
              '<td>' + he(m.locality) + '</td></tr>'
          );

          phoneRows.push('<tr data-id="'+m.id+'" data-name="'+m.name+'" data-locality="'+m.locality_name+'" data-locality_key="'+m.locality_key+'" data-time="'+m.time+'" data-id_string="'+m.id_string+'" data-id_page="'+m.page+'" class="ruler-string" style="background-color: lightblue">'+
              /*if (!globalSingleCity) {
                  '<td style="width:160px">' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') +
                  '</td>' +
               }*/
              '<td>' + he(m.name) + '</td>' +
              '<td>' + he(m.page) + '</td>' +
              '<td style="width:100px">' + he(m.time) + '</td>' +
              '<td>' + he(m.locality_name) + '</td></tr>'
          );
        }
          tableRows.push('<tr data-id="'+m.id+'" data-name="'+m.name+'" data-locality="'+m.locality_name+'" data-locality_key="'+m.locality_key+'" data-time="'+m.time+'" data-id_string="'+m.id_string+'" data-id_page="'+m.page+'" class="" style="display:none">'+
              '<td>' + he(m.id_string) +
              /*if (!globalSingleCity) {
                  '<td style="width:160px">' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') +
                  '</td>' +
               }*/
              '<td>' + he(m.name) + '</td>' +
              '<td>' + he(m.page) + '</td>' +
              '<td style="width:100px">' + he(m.time) + '</td>' +
              '<td>' + he(m.locality_name) + '</td>' +
              '<td>' + he(m.locality) + '</td></tr>'
          );

          phoneRows.push('<tr data-id="'+m.id+'" data-name="'+m.name+'" data-locality="'+m.locality_name+'" data-locality_key="'+m.locality_key+'" data-time="'+m.time+'" data-id_string="'+m.id_string+'" data-id_page="'+m.page+'" class="" style="display:none">'+
              /*if (!globalSingleCity) {
                  '<td style="width:160px">' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') +
                  '</td>' +
               }*/
              '<td>' + he(m.name) + '</td>' +
              '<td>' + he(m.page) + '</td>' +
              '<td style="width:100px">' + he(m.time) + '</td>' +
              '<td>' + he(m.locality_name) + '</td></tr>'
          );
      }

      $(".desctopVisible tbody").html (tableRows.join(''));
      $(".show-phone tbody").html (phoneRows.join(''));

      $(".ruler-string").unbind('click');
      $(".ruler-string").click (function (e) {
          e.stopPropagation();
          var memberId = $(this).attr('data-id');
          refreshNewList(memberId);
      });
      if (checkList) {
        var sessionsCount = [], sessionsCounter, pagersCounter = [];
        for (var i = 0; i < checkList.length; i++) {
          sessionsCounter = 0;
          pagersCounter =[];
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
                pagersCounter.push(list[iii].page);
              }
            }
          }
          sessionsCount.push(pagersCounter);
        }
          $('.ruler-string').each(function() {
            for (var i = 0; i < sessionsCount.length; i=i+3) {
              if ($(this).attr('data-id') == sessionsCount[i]) {
                  var ab = sessionsCount[i+1];
                  ab = ab + ' визитов';
                  ac = sessionsCount[i+2];
                  ac = 'Посещены:' + ac;
                $(this).find('.stts-count').text(ab);
                $(this).find('.stts-pages').text(ac);
              }
            }
          });
      }
/*
      $(".").unbind('click');
      $(".").click (function (e) {
          e.stopPropagation();
          var memberId = $(this).attr('data-id');
          refreshNewList(memberId);

          $.getJSON('/ajax/get.php', { member: memberId })
              .done (function(data) {
                  fillEditMember (memberId, data.member, data.localities);
                  //window.currentEditMemberId = memberId;
                  $('#modalEditMember #btnDoSaveMember').removeClass('create');
                  $('#modalEditMember').modal('show');
          })
      });

      $(".icon-black").unbind('click');
      $(".icon-black").click(function (event) {
          event.stopPropagation();

          if($(this).hasClass('icon-trash')){
              window.removeMemberId = $(this).parents('tr').attr('data-id');

              $.post('/ajax/members.php?is_member_in_reg', {
                  memberId : window.removeMemberId
              })
              .done(function(data){
                  if(!data.res){
                      if(window.removeMemberId.substr(0,2) === '99'){
                          removeMember(window.removeMemberId);
                      }
                      else{
                          $('#removeMemberFromList').modal('show');
                      }
                  }
                  else{
                      showError('Этот участник находится в списке регистрации! Удаление отменено.');
                  }
              });
          }
          else{
              var searchText = $('.search-text').val();
              var recoverMemberId = $(this).parents('tr').attr('data-id');
              handleMember(recoverMemberId, 1, '', searchText);
          }
      });
      */
  }
// LOCALITY FILTER END
// START DASHBOARD

// Sorted Fields start
  $("a[id|='sort']").click (function (){
    //clickOnSort = 1;
    var id = $(this).attr("id");
    var icon = $(this).siblings("i");
    $(".members-list a[id|='sort'][id!='"+id+"'] ~ i").attr("class","icon-none");
    icon.attr ("class", icon.hasClass("icon-chevron-down") ? "icon-chevron-up" : "icon-chevron-down");

    if (id === 'sort-number') {
      icon.hasClass("icon-chevron-down") ? sortingActivity(1) : sortingActivity(2);
    } else if (id === 'sort-name') {
      icon.hasClass("icon-chevron-down") ? sortingActivity(3) :sortingActivity(4);
    } else if (id === 'sort-page') {
      icon.hasClass("icon-chevron-down") ? sortingActivity(5) :sortingActivity(6);
    } else if (id === 'sort-time') {
      icon.hasClass("icon-chevron-down") ? sortingActivity(7) :sortingActivity(8);
    } else if (id === 'sort-locality') {
      icon.hasClass("icon-chevron-down") ? sortingActivity(9) :sortingActivity(10);
    } else if (id === 'sort-status') {
      icon.hasClass("icon-chevron-down") ? sortingActivity(11) :sortingActivity(12);
    } else {
      loadDashboard (true);
    }
});
function sortingActivity(sortType) {
  var list = [], tableRows = [], phoneRows = [], isLocationAlone = $('#selMeetingLocality option').length == 2 ?  true : false;
   var aLenght = $('.show-phone:visible');
   if (aLenght.length === 0) {
  $('#members').find('tr').each(function(){
    if (!$(this).hasClass('ruler-string')) {
        var memberName = $(this).attr('data-name'),
            timeActivity = $(this).attr('data-time'),
            id_string = $(this).attr('data-id_string'),
            page = $(this).attr('data-id_page'),
            id = $(this).attr('data-id'),
            locality = $(this).attr('data-locality_key'),
            localityName = $(this).attr('data-locality');
          if (id) {
        list.push({id_string: id_string, id: id, locality_key: locality, name: memberName, page: page, time: timeActivity, locality_name: localityName});
      }
    }
  });
  } else {
    $('#membersPhone').find('tr').each(function(){
        if (!$(this).hasClass('ruler-string')) {
          var memberName = $(this).attr('data-name'),
              timeActivity = $(this).attr('data-time'),
              id_string = $(this).attr('data-id_string'),
              page = $(this).attr('data-id_page'),
              id = $(this).attr('data-id'),
              locality = $(this).attr('data-locality_key'),
              localityName = $(this).attr('data-locality');
              if (id) {
                list.push({id_string: id_string, id: id, locality_key: locality, name: memberName, page: page, time: timeActivity, locality_name: localityName});
              }
            }
          });
        }
if (sortType == 1) {
  list.sort(function (a, b) {
    if (Number(a.id_string) > Number(b.id_string)) {
      return 1;
    }
    if (Number(a.id_string) < Number(b.id_string)) {
      return -1;
    }
    return 0;
  });
}

if (sortType == 2) {
  list.sort(function (a, b) {
    if (Number(a.id_string) < Number(b.id_string)) {
      return 1;
    }
    if (Number(a.id_string) > Number(b.id_string)) {
      return -1;
    }
    return 0;
  });
}
if (sortType == 3) {
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
    if (a.name < b.name) {
      return 1;
    }
    if (a.name > b.name) {
      return -1;
    }
    return 0;
  });
}
if (sortType == 5) {
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
}
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
if (sortType == 8) {
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
if (sortType == 9) {
  list.sort(function (a, b) {
    if (a.locality_name > b.locality_name) {
      return 1;
    }
    if (a.locality_name < b.locality_name) {
      return -1;
    }
    return 0;
  });
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
  });
}
/*
if (sortType == 11) {
  list.sort(function (a, b) {
    if (a.performed > b.performed) {
      return 1;
    }
    if (a.performed < b.performed) {
      return -1;
    }
    return 0;
  });
}
if (sortType == 12) {
  list.sort(function (a, b) {
    if (a.performed < b.performed) {
      return 1;
    }
    if (a.performed > b.performed) {
      return -1;
    }
    return 0;
  });
}
*/
  refreshActivity(list, true);
/*
  $('.btn-remove-meeting').unbind('click');
  $('.btn-remove-meeting').click(function(e){
      e.stopPropagation();
      var meetingId = $(this).parents('tr').attr('data-id'),
          modal = $("#modalRemoveMeeting");
      modal.find(".remove-meeting").attr("data-id", meetingId);
      modal.modal("show");
  });
    $("tbody tr").each(function(){
        if ($(this).find('.meeting-name') == 'Посещения') {
          $(this).find('.meeting-name').attr('style', 'font-weight: bold');
        }
    });
    */
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
          localityList = [];
          hideDevelopers = $("#hideDevelopers").prop('checked') ? '000005716' : false;
      if(localityFilter){
          localityList = localityFilter.split(',');
      }

      $(".members-list " + ( isTabletMode ? " #membersPhone " : " #members" ) + " tbody tr").each(function(){
          var memberLocality = $(this).attr('data-locality_key'),
              memberCategory = $(this).attr('data-category'),
              memberPage = $(this).attr('data-id_page'),
              memberName = $(this).attr('data-name').toLowerCase(),
              memberKey = $(this).attr('data-id');


          if(((localityFilter === '_all_' || localityFilter === undefined) && pageFilter === '_all_' && text === '' && listAdminsFilter === '_all_' && hideDevelopers === false) ||

              (
                  (in_array(memberLocality, localityList) || localityFilter === '_all_' || (localityFilter === undefined && localityList.length === 0))  &&
                  (memberPage === pageFilter || pageFilter === '_all_') && (memberKey === listAdminsFilter || listAdminsFilter === '_all_') &&  (hideDevelopers === false || memberKey != '000005716')) && (memberName.search(text) !== -1))
              {

              $(this).show();
              filteredMembers.push(memberKey);
          }
          else{
              $(this).hide();
          }
      });
      console.log(filteredMembers);
      return filteredMembers;
  }
  $("#hideDevelopers").change (function (){
      filterMembers();
  });

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

//START NEW LIST
/*
  function newListActivity() {
    var startDate = formatDateDotToDash($('.start-date').val(), false),
    stopDate = formatDateDotToDash($('.end-date').val(), true);
    startDate+='%';
    stopDate +='%';
    $.getJSON('/ajax/activity_log.php?get_activity', {start:startDate,stop:stopDate, locality: '_all_', page: '_all_', admins: '_all_'})
        .done (function(data) {
          //console.log(data);
            newListActivityRefresh (data.members);
          });
  }
  function newListActivityRefresh(list) {
    list.sort(function (a, b) {
      if (a.time < b.time) {
        return 1;
      }
      if (a.time > b.time) {
        return -1;
      }
      return 0;
    });
    var listArr = [], checkList = [];
    for (var i = 0; i < list.length; i++) {
      var b = list[i].id, c = list[i].time;
      //&& checkDate > c
      if (checkList.indexOf(b) === -1) {
        checkList.push(b);
        listArr.push(list[i]);
      }
    }
    var tableRows = [], phoneRows = [];
    for (var i in listArr){
        var m = listArr[i];

        tableRows.push('<tr data-id="'+m.id+'" data-name="'+m.name+'" data-locality="'+m.locality_name+'" data-locality_key="'+m.locality_key+'" data-time="'+m.time+'" data-id_string="'+m.id_string+'" data-id_page="'+m.page+'" class="ruler-string" style="background-color: lightblue">'+
            '<td>' + he(m.id_string) +
            *//*if (!globalSingleCity) {
                '<td style="width:160px">' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') +
                '</td>' +
             }*//*
            '<td>' + he(m.name) + '</td>' +
            '<td>' + he(m.page) + '</td>' +
            '<td style="width:100px">' + he(m.time) + '</td>' +
            '<td>' + he(m.locality_name) + '</td>' +
            '<td>' + he(m.locality) + '</td></tr>'
        );

        phoneRows.push('<tr data-id="'+m.id+'" data-name="'+m.name+'" data-locality="'+m.locality_name+'" data-locality_key="'+m.locality_key+'" data-time="'+m.time+'" data-id_string="'+m.id_string+'" data-id_page="'+m.page+'" class="ruler-string" style="background-color: lightblue">'+
            *//*if (!globalSingleCity) {
                '<td style="width:160px">' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') +
                '</td>' +
             }*//*
            '<td>' + he(m.name) + '</td>' +
            '<td>' + he(m.page) + '</td>' +
            '<td style="width:100px">' + he(m.time) + '</td>' +
            '<td>' + he(m.locality_name) + '</td></tr>'
        );
    }
    $(".desctopVisible tbody").prepend (tableRows.join(''));
    $(".show-phone tbody").prepend (phoneRows.join(''));

    $(".ruler-string").unbind('click');
    $(".ruler-string").click (function (e) {
        e.stopPropagation();
        var memberId = $(this).attr('data-id');
        refreshNewList(memberId);
    });
    console.log(listArr);
  }
  newListActivity();
  */
  //STOP NEW LIST
});


/*
$("a[id|='sort']").click (function (){
    var id = $(this).attr("id");
    var icon = $(this).siblings("i");

    $(($(document).width()>768 ? ".desctopVisible" : ".show-phone") + " a[id|='sort'][id!='"+id+"'] ~ i").attr("class","icon-none");
    icon.attr ("class", icon.hasClass("icon-chevron-down") ? "icon-chevron-up" : "icon-chevron-down");
    //loadDashboard ();
});

setAdminRole_0('.add-member','#btnDoSaveMember');


$('.send_file').click(function(){
    var file_data = $('.uploaded_excel_file').prop('files')[0];
    var form_data = new FormData();
    form_data.append('file', file_data);

    var admin_id = globalAdminId;

    $.ajax({
        url: '/ajax/excelList.php?upload_file&admin_id='+admin_id,
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            if(data.res){
                showHint("Данные успешно загружены");
            }
            else{
                showError("При загрузке данных произошел сбой. Обратитесь в службу поддержки.");
            }
            $('#modalUploadExcel').modal('hide');
        }
     });
});
  function refreshMembers (members){
      var tableRows = [], phoneRows = [];

      for (var i in members){
          var m = members[i];

          // *** last editor
          var notMe = (m.admin_key && m.admin_key!=window.adminId);
          // if the author is same for reg and mem records is was decided to show it only once
          var editor = m.admin_name;
          var htmlEditor = notMe ? '<i class="icon-user" title="Последние изменения: '+editor+'"></i>': '';

          // *** changes processed
          var htmlChanged = (m.changed > 0 ? '<i class="icon-pencil" title="Изменения еще не обработаны"></i>' : '');
          var age = getAgeWithSuffix(parseInt(m.age), m.age);
          // Cut the m.region string. Roman's code ver 5.0.0
          if (m.region =='--') {
            m.region = m.country;
          } else {
            m.region = m.region.substring(0, m.region.indexOf(" ("));
            // m.region += ', ';
            // m.region += m.country;
          }

          tableRows.push('<tr data-id="'+m.id+'" data-name="'+m.name+'" data-age="'+m.age+'" data-attendance="'+m.attend_meeting+'" data-locality="'+m.locality_key+'" data-category="'+m.category_key+'" class="'+(m.active==0?'inactive-member':'member-row')+'">'+
              '<td>' + he(m.name) +
  (in_array(5, window.user_settings) ? '<br/>'+ '<span class="user_setting_span">'+m.category_name+'</span>' : '') +
              '</td>' +
              if (!globalSingleCity) {
                  '<td style="width:160px">' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') +
                  (in_array(6, window.user_settings) ? '<br/>'+ '<span class="user_setting_span">'+(m.region || m.country)+'</span>' : '') +
                  '</td>' +
               }
              '<td>' + he(m.cell_phone) + '</td>' +
              '<td>' + he(m.email) + '</td>' +
              '<td style="width:50px">' + age + '</td>' +
              '<td><input type="checkbox" class="check-meeting-attend" '+ (m.attend_meeting == 1 ? "checked" : "") +' /></td>' +
              '<td>' + htmlChanged + htmlEditor + '</td>' +
              globalAdminRole != 0 ? '<td><i class="'+(m.active==0?'icon-circle-arrow-up':'icon-trash')+' icon-black" title="'+(m.active==0?'Добавить в список':'Удалить из списка')+'"/></td>' : '' +
              '</tr>'
          );

          phoneRows.push('<tr data-id="'+m.id+'" data-name="'+m.name+'" data-age="'+m.age+'" data-attendance="'+m.attend_meeting+'" data-locality="'+m.locality_key+'" data-category="'+m.category_key+'" class="'+(m.active==0?'inactive-member':'member-row')+'">'+
              '<td><span style="color: #006">' + he(m.name) + '</span>'+
              '<i style="float: right; cursor:pointer;" class="'+(m.active==0?'icon-circle-arrow-up':'icon-trash')+' icon-black" title="'+(m.active==0 ? 'Добавить в список':'Удалить из списка')+'"/>'+
              if (!$singleCity) { "'<div>' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') + ', ' + age + '</div>' + "; } (in_array(6, window.user_settings) ? '<span class="user_setting_span">'+(m.region || m.country)+'</span>' : '') +
              '<div><span >'+ (m.cell_phone?'тел.: ':'') +  he(m.cell_phone.trim()) + '</span>'+ (m.cell_phone && m.email ? ', ' :'' )+'<span>'+ (m.email?'email: ':'') +  he(m.email) + '</span></div>' +
              '<div>Посещает собрания: <input type="checkbox" class="check-meeting-attend" '+ (m.attend_meeting == 1 ? "checked" : "") +' /></div>'+
              '<div>'+ htmlChanged + htmlEditor + '</div>'+
              '</td>' +
              '</tr>'
          );
      }

      $(".desctopVisible tbody").html (tableRows.join(''));
      $(".show-phone tbody").html (phoneRows.join(''));

      filterMembers();

      $(".member-row").unbind('click');
      $(".member-row").click (function (e) {
          e.stopPropagation();
          var memberId = $(this).attr('data-id');
          $.getJSON('/ajax/get.php', { member: memberId })
              .done (function(data) {
                  fillEditMember (memberId, data.member, data.localities);
                  //window.currentEditMemberId = memberId;
                  $('#modalEditMember #btnDoSaveMember').removeClass('create');
                  $('#modalEditMember').modal('show');
          })
      });

      $(".icon-black").unbind('click');
      $(".icon-black").click(function (event) {
          event.stopPropagation();

          if($(this).hasClass('icon-trash')){
              window.removeMemberId = $(this).parents('tr').attr('data-id');

              $.post('/ajax/members.php?is_member_in_reg', {
                  memberId : window.removeMemberId
              })
              .done(function(data){
                  if(!data.res){
                      if(window.removeMemberId.substr(0,2) === '99'){
                          removeMember(window.removeMemberId);
                      }
                      else{
                          $('#removeMemberFromList').modal('show');
                      }
                  }
                  else{
                      showError('Этот участник находится в списке регистрации! Удаление отменено.');
                  }
              });
          }
          else{
              var searchText = $('.search-text').val();
              var recoverMemberId = $(this).parents('tr').attr('data-id');
              handleMember(recoverMemberId, 1, '', searchText);
          }
      });

      $("#check-all-download-checkboxes").change(function(){
          var checkAll = $(this).prop('checked');
          var a = 0;

          $(this).parents("#modalDownloadMembers").find(".download-checkboxes input[type='checkbox']").each(function(){
            $(this).attr('id')==='member-name' ? a = 1 : a = 0;
            if (a === 0) {
              $(this).prop('checked', checkAll);
            }
          });
      });

      $('.downloadItems').click(function(){
          var checkedFields = [];
          $("#modalDownloadMembers").find("input[type='checkbox']").each(function(){
              if ($(this).prop('checked')==true && ($(this).attr('id') != "check-all-download-checkboxes")){
                  checkedFields.push($(this).attr('data-download'));
              }
          });

          downloadMembersListExel(members, checkedFields);
          checkedFields = [];
      });

      $(".check-meeting-attend").click(function(e){
          e.stopPropagation();
      });

      $(".check-meeting-attend").change(function(e){
          e.stopPropagation();

          var value = $(this).prop('checked') ? 1 : 0, memberId = $(this).parents('tr').attr('data-id');

          $.post('/ajax/members.php?set_attend_meeting', {value : value, memberId : memberId})
          .done(function(data){
              if(data.result && value == 1){
                  showModalHintWindow("<strong>"+data.result+"</strong>");
              }
          });
      });
  }

$('.upload_excel_file').click(function(){
    $('#modalUploadExcel .list_data').html('');
    $('#modalUploadExcel').modal('show');
});

function showDataFromExcelFile(data){
    var columns = data[0],
        //items = [],
        members = [],
        desiredFields = ['Фамилия', 'Имя', 'Отчество','Пол','Дата рождения','Местность','Состояние','Трапеза'],
        nameFields = ['Фамилия', 'Имя', 'Отчество'];

    for (var rows in data){
        var member = [],
            //item_data = [],
            nameData = [];

        if (rows != 0){
            for (var row in data[rows]){
                if(columns[row] !== null && columns[row] !== undefined && in_array(columns[row], desiredFields)){
                    member.push({
                        key:   columns[row],
                        value: data[rows][row]
                    });

                    //item_data.push("<span title='"+ columns[row] + " ("+ data[rows][row] +")'>" + (data[rows][row].length > 28 ? data[rows][row].substring(0, 30) + '...' : data[rows][row]) + "</span>");
                }
            }
            //if (rows != 0){
            //    items.push("<div>"+ item_data.join(' ') +"</div>");
            //}

            for(var m in member){
                if(in_array(member[m]['key'], nameFields)){
                    nameData.push(member[m]['value'])
                }
            }
            member.push({'ФИО': nameData.join(' ')});
            members.push(member);
        }
    }

    $.post('/ajax/members.php?downloadExcelData', {members : JSON.stringify(members)})
    .done (function(data) {
        //$('#modalUploadExcel').modal('hide');
    });

    //$('.list_data').html(items.join(''));
}



    $(".btnDownloadMembers").click(function(event){
        event.stopPropagation();
        $('#modalDownloadMembers').modal('show').find("input[type='checkbox']").each(function(){
            $(this).prop('checked', true);
        });
    });

    $(".remove-member-reason").click(function(e){
        e.stopPropagation();
        e.preventDefault();
        var reason = '';

        if($(this).hasClass('empty-info')){
            reason = 'Информация отсутствует';
        }
        else if($(this).hasClass('outside')){
            reason = 'Не в церковной жизни';
        }
        $(".removeMemberReason").val(reason);
    });

    $(".btnShowStatistic").click(function(e){
        e.stopPropagation();
        var isTabletMode = $(document).width()<786,
            filterLocality = $('#selMemberLocality option:selected').text(),
            localitiesByFilter = [],
            countMembers = 0, countBelivers=0, countScholars = 0,
            countPreScholars = 0, countStudents = 0, countSaints = 0,
            countRespBrothers = 0, countFullTimers = 0, countTrainees = 0,
            countOthers = 0, countAttendances = 0,

            memberAgeIsNullList = [],

            countScholarsByAge = 0, countStudentsByAge = 0, countSaintsByAge = 0,
            countAttendancesScholarsByAge = 0, countAttendancesStudentsByAge = 0, countAttendancesSaintsByAge = 0,
            countAttendancesByAge = 0, countByAge = 0,

            countAttendancesMembers = 0, countAttendancesBelivers=0, countAttendancesScholars = 0,
            countAttendancesPreScholars = 0, countAttendancesStudents = 0, countAttendancesSaints = 0,
            countAttendancesRespBrothers = 0, countAttendancesFullTimers = 0, countAttendancesTrainees = 0,
            countAttendancesOthers = 0,
            averageAge = 0, averageAgeAttendances = 0;

        $(".members-list " + ( isTabletMode ? " #membersPhone " : " #members " ) + " tbody tr").each(function(){
            if($(this).css('display') !== 'none' && !$(this).hasClass('inactive-member')){
                countMembers ++;

                var name = $(this).attr('data-name'),
                    locality = $(this).attr('data-locality'),
                    category = $(this).attr('data-category'),
                    age = $(this).attr('data-age');

                if(!age || age == 'null'){
                    memberAgeIsNullList.push(name);
                }
                else{
                    if(age >=12 && age <= 17){
                        averageAge += parseInt(age);
                        countScholarsByAge++;
                        if($(this).attr('data-attendance') == 1){
                            averageAgeAttendances += parseInt(age);
                            countAttendancesScholarsByAge ++;
                        }
                    }
                    else if(age >=18 && age <= 25){
                        averageAge += parseInt(age);
                        countStudentsByAge++;
                        if($(this).attr('data-attendance') == 1){
                            averageAgeAttendances += parseInt(age);
                            countAttendancesStudentsByAge ++;
                        }
                    }
                    else if (age > 25){
                        averageAge += parseInt(age);
                        countSaintsByAge++;
                        if($(this).attr('data-attendance') == 1){
                            averageAgeAttendances += parseInt(age);
                            countAttendancesSaintsByAge ++;
                        }
                    }

                    if($(this).attr('data-attendance') == 1){
                        countAttendancesByAge++;
                    }
                    countByAge++;
                }

                if($(this).attr('data-attendance') == 1){
                    countAttendances ++;

                    switch (category){
                        case 'BL': countAttendancesBelivers++; break;
                        case 'SN': countAttendancesSaints++; break;
                        case 'SC': countAttendancesScholars++; break;
                        case 'PS': countAttendancesPreScholars++; break;
                        case 'ST': countAttendancesStudents++; break;
                        case 'RB': countAttendancesRespBrothers++; break;
                        case 'FS': countAttendancesFullTimers++; break;
                        case 'FT': countAttendancesTrainees++; break;
                        case 'OT': countAttendancesOthers++; break;
                    }
                }

                if(!in_array(locality, localitiesByFilter)){
                    localitiesByFilter.push(locality);
                }

                switch (category){
                    case 'BL': countBelivers++; break;
                    case 'SN': countSaints++; break;
                    case 'SC': countScholars++; break;
                    case 'PS': countPreScholars++; break;
                    case 'ST': countStudents++; break;
                    case 'RB': countRespBrothers++; break;
                    case 'FS': countFullTimers++; break;
                    case 'FT': countTrainees++; break;
                    case 'OT': countOthers++; break;
                }
            }
        });

        $("#modalStatistic h5").text('');
        var statistic =
                (countPreScholars >0 ? "<tr><td>Дошкольники</td><td class='text-align'>"+countPreScholars+"</td><td class='text-align'>"+countAttendancesPreScholars+"</td></tr>" : "" )+
                ( countScholars >0 ? "<tr><td>Школьники</td><td class='text-align'>"+countScholars+"</td><td class='text-align'>"+countAttendancesScholars+"</td></tr>" : "" ) +
                ( countStudents >0 ? "<tr><td>Студенты</td><td class='text-align'>"+countStudents+"</td><td class='text-align'>"+countAttendancesStudents+"</td></tr>" : "" )+
                (countSaints >0 ? "<tr><td>Святые в церк. жизни</td><td class='text-align'>"+countSaints+"</td><td class='text-align'>"+countAttendancesSaints+"</td></tr>" : "")+
                ( countRespBrothers >0 ? "<tr><td>Ответственные братья</td><td class='text-align'>"+countRespBrothers+"</td><td class='text-align'>"+countAttendancesRespBrothers+"</td></tr>" : "" )+
                ( countFullTimers >0 ? "<tr><td>Полновременные служащие</td><td class='text-align'>"+countFullTimers+"</td><td class='text-align'>"+countAttendancesFullTimers+"</td></tr>" : "" )+
                ( countTrainees >0 ? "<tr><td >Полновременно обучающиеся</td><td class='text-align'>"+countTrainees+"</td><td class='text-align'>"+countAttendancesTrainees+"</td></tr>" : "" )+
                ( countBelivers >0 ? "<tr><td>Верующие</td><td class='text-align'>"+countBelivers+"</td><td class='text-align'>"+countAttendancesBelivers+"</td></tr>" : "" )+
                ( countOthers >0 ? "<tr><td>Другие</td><td class='text-align'>"+countOthers+"</td><td class='text-align'>"+countAttendancesOthers+"</td></tr>" : "" ) +
                "<tr><td><strong>Всего</strong></td><td class='text-align'><strong>" + countMembers + "</strong></td><td class='text-align'><strong>"+countAttendances+"</strong></td></tr>";

        var additionalStatistic =
            (countScholarsByAge >0 ? "<tr><td>12-17 лет</td><td class='text-align'>"+countScholarsByAge+"</td><td class='text-align'>"+
                countAttendancesScholarsByAge+"</td></tr>" : "")+
            ( countStudentsByAge >0 ? "<tr><td>18-25 лет</td><td class='text-align'>"+countStudentsByAge+"</td><td class='text-align'>"+countAttendancesStudentsByAge+"</td></tr>" : "" ) +
            ( countSaintsByAge >0 ? "<tr><td>26 лет и старше</td><td class='text-align'>"+countSaintsByAge+"</td><td class='text-align'>"+countAttendancesSaintsByAge+"</td></tr>" : "" )+
            "<tr><td><strong>Всего</strong></td><td class='text-align'><strong>" + (countScholarsByAge+countStudentsByAge+countSaintsByAge) + "</strong></td><td class='text-align'><strong>"+(countAttendancesScholarsByAge+countAttendancesStudentsByAge+countAttendancesSaintsByAge)+"</strong></td></tr>"+
            ( countScholarsByAge>0 || countStudentsByAge> 0 || countSaintsByAge >0 ? "<tr><td>Средний возраст</td><td class='text-align'>"+(
                parseInt(averageAge / (countScholarsByAge + countStudentsByAge + countSaintsByAge)))+"</td>"+
            "<td class='text-align'>"+ (
                parseInt(averageAgeAttendances / (countAttendancesScholarsByAge + countAttendancesStudentsByAge + countAttendancesSaintsByAge))) +"</td></tr>" : "" );

        if(memberAgeIsNullList.length == 0){
            var additionalTableTemplate = '<h3>Данные для статистики</h3>'+
                '<table class="table table-hover">'+
                  '<thead>'+
                    '<tr>'+
                      '<th>По возрастам</th>'+
                      '<th class="text-align">По списку</th>'+
                      '<th class="text-align">Посещают собрания</th>'+
                    '</tr>'+
                  '</thead>'+
                  '<tbody>'+ additionalStatistic + '</tbody>'+
                '</table>';
        }
        else{
            var additionalTableTemplate = '<h3>Данные для статистики (по возрастам) не сформированы, поскольку не указана дата рождения:</h3> <div>'+ memberAgeIsNullList.join(', ') + '</div>';
        }

        var tableTemplate = '<h3>Сводные данные</h3><table class="table table-hover">'+
              '<thead>'+
                '<tr>'+
                  '<th>По категориям</th>'+
                  '<th class="text-align">По списку</th>'+
                  '<th class="text-align">Посещают собрания</th>'+
                '</tr>'+
              '</thead>'+
              '<tbody>'+ statistic + '</tbody>'+
            '</table>';

        $("#modalStatistic").find(".modal-header h3").html("Статистика" +
            (filterLocality === 'Все местности' ? ' (' + localitiesByFilter.length + ')' : ' (' + filterLocality + ')'));
        $("#modalStatistic").find(".modal-body").html(tableTemplate + additionalTableTemplate);
        //$("#modalStatistic").find(".modal-footer").html("<div style='float:left;'><strong>Количество местностей — "+localitiesByFilter.length+"</strong></div>");
        $("#modalStatistic").modal('show');
    });

    function removeMember(memberId){
        $.post('/ajax/members.php?remove', {
            memberId : memberId,
            sortedFields : sortedFields()
        })
        .done(function(data){
            refreshMembers(data.members);
        });
    }

    function downloadMembersListExel(members, checkedFields){
        var doc = '&document=', filteredMembers = filterMembers(), membersArr = [];

        if (checkedFields){
            doc += checkedFields;
        }

        for(var i in members){
            var member = members[i];
            if(in_array(member.id, filteredMembers)){
                membersArr.push(member);
            }
        }

        var  req = "&memberslength="+membersArr.length+"&adminId="+window.adminId+"&page=members";

        $.ajax({
            type: "POST",
            url: "/ajax/excelList.php",
            data: "members="+JSON.stringify(membersArr)+req+doc,
            cache: false,
            success: function(data) {
                location.href="./ajax/excelList.php?file="+data;
                setTimeout(function(){
                    deleteFile(data);
                }, 10000);
            }
        });
    }
    $("#remove-member").click(function (event) {
        event.stopPropagation();
        var reason = $('.removeMemberReason').val();

        if(reason.trim() === '') {
            return;
        }
        var searchText = $('.search-text').val();

        handleMember(window.removeMemberId, 0, reason, searchText);

        $('#removeMemberFromList').modal('hide');
    });

    function handleMember(member, active, reason, searchText) {
        $.getJSON('/ajax/members.php', {
            member: member,
            active: active,
            reason : reason.trim(),
            searchText : searchText,
            sortedFields : sortedFields()
        })
            .done (function(data) {
                window.removeMemberId = '';
                $('.removeMemberReason').val('');
                refreshMembers (data.members);
            });
    }

*/
