var MeetingsPage = (function(){
    google.charts.load('current', {'packages':['corechart', 'bar']});
var isFillTemplate = 0;
var clickOnSort = 0;
var startSystemFilter = 1;
    $(document).ready(function(){

      // empty admins array
        window.meetingTemplateAdmins = [];
        window.meetingTemplateAdminsList = [];
        window.meetingTemplateParticipantsList = [];

loadMeetings();
//loadMeetings();
// CALLS AND VISITS CODE
function loadMeetings(){
    var request = getRequestFromFilters(setFiltersForRequest());
    $.getJSON('/ajax/visits.php?get_visits'+request).done(function(data){
      refreshMeetings(data.meetings);
      filterMeetingsList();
    });
}

function getMeetingCounts(item){
    var traineesCount = 0, fulltimersCount = 0, guestCount = 0, childrenCount = 0,
            listCount = 0, saintsCount = 0, countMembers, fulltimersInSaintsList = 0;

    return{
        traineesCount : traineesCount, fulltimersCount:fulltimersCount, guestCount:guestCount, childrenCount: childrenCount,
        listCount : listCount, saintsCount:saintsCount , countMembers:countMembers
    };
}

function refreshMeetings(meetings,sort){
    var tableRows = [], phoneRows = [], responsibiles = [], responsibleName, performedStatus, listOfMembersGoals, idDatePicker=[];
    var isSingleCity = parseInt('<?php echo $isSingleCity; ?>');
    var isLocationAlone = $('#selMeetingLocality option').length == 2 ?  true : false;

    $('#responsibleList option').each(function(){
      if (($(this).val() != '_all_')) {
        var tempVar = [];

      // empty admins array
        tempVar.push($(this).val());
        tempVar.push($(this).text());
        responsibiles.push(tempVar);
      }
    });

    for (var i in meetings){
        var m = meetings[i], dataString, moscow = false, shortNameMbl;
        splitMember = m.members.split(',');
        if (m.members) {
          for (var i = 0; i < splitMember.length; i++) {
            temtArr = splitMember[i].split(':');
            if (temtArr.length < 8 && temtArr.length > 6) {
                splitMember[i] = splitMember[i] + splitMember[i+1];
            } else if (temtArr.length < 7) {
              splitMember.splice(i, 1);
            }
          }
          m.members = splitMember.join(',');
          if (splitMember[0].indexOf("Москва:") != -1 && !(splitMember[0].indexOf("Москва:0") != -1 || splitMember[0].indexOf("Москва:1") != -1)) {
            splitMember = splitMember[0].split(':');
            var varTempRbld = splitMember.splice(3, 1);
            splitMember[2] = splitMember[2] + varTempRbld;
            shortNameMbl = twoNames(splitMember[1]);
          } else {
              splitMember = splitMember[0].split(':');
              shortNameMbl = twoNames(splitMember[1]);
          }
      }
        m.performed == 0 ? performedStatus = 'планируется' : '';
        m.performed == 1 ? performedStatus = 'сделано' : '';
        m.performed == 2 ? performedStatus = 'не сделано' : '';
        //m.performed == 3 ? performedStatus = 'удалить карточку' : '';
        m.performed == 0 ? performedStatusCss = 'status-select-plan' : '';
        m.performed == 1 ? performedStatusCss = 'status-select-done' : '';
        m.performed == 2 ? performedStatusCss = 'status-select-failed' : '';
        //m.performed == 3 ? performedStatusCss = 'label-info' : '';
        for (var i in responsibiles) {
          responsibiles[i][0] == m.responsible ? responsibleName = responsibiles[i][1] : '';
        }

        function twoNames(fullName) {
          var shortName;
          fullName ? fullName = fullName.split(' ') : '';
          if (fullName) shortName = fullName[0] + ' ' + fullName[1];
          return shortName;
        }
        responsibleName = twoNames(responsibleName);
        function listOfMembersGoals() {
          var arr=[];
          m.members ? splitAllMember = m.members.split(',') : '';
          for (var i = 0; i < splitAllMember.length; i++) {
            if (splitAllMember[i].indexOf("Москва:") != -1 && !(splitAllMember[i].indexOf("Москва:0") != -1 || splitAllMember[i].indexOf("Москва:1") != -1)) {
              moscow = true;
            }
                splitAllMember[i] ? shortNames = splitAllMember[i].split(':'): '';
            if (moscow === true) {
              shortNames[7] ? '' : shortNames[7] = '';
              arr.push((twoNames(shortNames[1])) + ' ' + shortNames[7]);
            } else {
              shortNames[6] ? '' : shortNames[6] = '';
              arr.push((twoNames(shortNames[1])) + ' ' + shortNames[6]);
            }
          }
          return arr
        }
        var shortNameFirst = [], shortNameMore = [];
        shortNameFirst = listOfMembersGoals();
        shortNameMore = listOfMembersGoals();
        shortNameMore.splice(0,5);
        var dayOfWeek = getNameDayOfWeek(m.date_visit);
        //m.performed == 1 ? backgroundDone = 'bg-gray-mbl' : backgroundDone = '';
        m.performed == 0 ? planStts = 'selected' : planStts = '';
        m.performed == 1 ? doneStts = 'selected' : doneStts = '';
        m.performed == 2 ? failedStts = 'selected' : failedStts = '';

        dataString = 'data-members="'+m.members+'" data-responsible="'+m.responsible+'" data-performed="'+m.performed+'"  '+'class="meeting-row '+(parseInt(m.summary) ? 'meeting-summary' : '')+' " data-note="'+he(m.comments || '')+'" data-type="'+m.act+'" data-date="'+m.date_visit+'" data-count="'+m.count_members+'" data-status_name="'+performedStatus+'" data-id="'+m.visit_id+'" '+' data-locality="'+m.locality_key+'" data-admin_key="'+m.admin_key+'" ';
        tableRows.push('<tr '+dataString +'>'+
            '<td style="text-align:left;"><input style="background-color: inherit; width: 130px" class="visitListDate" type="date" value="' + m.date_visit+'"></td>' +
            '<td class="meeting-name" style="text-align:left;width:330px; padding-left: 5px;"><div>'+ (shortNameFirst[0] || '')+'</div><div>'+ (shortNameFirst[1] || '')+'</div><div>' + (shortNameFirst[2] || '')+'</div><div>' + (shortNameFirst[3] || '')+'</div><div>' + (shortNameFirst[4] || '')+'</div><div>' + (shortNameMore || '')+'</div></td><td style="text-align: left"><span>'+(!isLocationAlone && m.members ? splitMember[2] : '')+'</span></td>' +
            '<td style="text-align:left;"><span>'+ he(m.act || '') +'</span><br><span style="margin-left: 0px" class="example day-of-week-in-list">'+dayOfWeek+'</span></td>' +
            '<td style="text-align:left;"><span>' + (responsibleName || '') + '</span></td>' +
            '<td style="text-align:left;width:130px" class="statusChange"><select class="visitСhangeStts '+performedStatusCss+'" style="width:130px;"><option '+planStts+' value="0">Планируется</option><option value="1" '+doneStts+'>Сделано</option><option value="2" '+failedStts+'>Не сделано</option><option value="3">Удалить карточку</option></select></td>' +
            '</tr>'
        );

        phoneRows.push('<tr '+dataString+'>'+
            '<td><div class="meeting-name"><strong>' + he(m.members ? shortNameMbl : '') + '</strong></div><div>' +
            (!isLocationAlone && m.members ? splitMember[2] : '') + ' '+
            he(m.members ? splitMember[6] : '') + '</div><div><input style="background-color: inherit; width: 130px; margin-bottom: 10px; margin-top: 10px; margin-right:0px" class="visitListDate" type="date" value="' + m.date_visit+'"><span class="example day-of-week-in-list">'+ dayOfWeek+ '</span><br><select class="visitСhangeStts '+performedStatusCss+'" style="width:145px; margin-right:7px; margin-top:5px"><option '+planStts+' value="0">Планируется</option><option value="1" '+doneStts+'>Сделано</option><option value="2" '+failedStts+'>Не сделано</option><option value="3">Удалить карточку</option></select><span>' +he(m.act || '') + '  ' + '</span></div>'  +
            '</td>' +
            '</tr>'
        );
    }

    $("#visitsListTbl tbody").html (tableRows.join(''));
    $("#visitsListMbl tbody").html (phoneRows.join(''));

    $(".meeting-name").unbind('click');
    $(".meeting-name").click (function () {
        var element = $(this).parents('tr');
        var note = element.attr('data-note');
        var date = element.attr('data-date');
        var membersCount = element.attr('data-count');
        var performed = element.attr('data-performed');
        var locality = element.attr('data-locality');
        var actionType = element.attr('data-type');
        var adminKey = element.attr('data-admin_key');
        var responsible = element.attr('data-responsible');
        var actionId = element.attr('data-id');
        var members = element.attr('data-members');
        var textMode = 'Карточка события';

        $("#addEditMeetingModal").find('.btnDoHandleMeeting').attr('data-id', actionId).attr('data-locality', locality).attr('data-date', date).attr('data-type',actionType);
        $("#addEditMeetingModal").attr('data-id', actionId);
        fillMeetingModalForm(textMode, date, locality, actionType, note, membersCount, performed, adminKey, responsible, actionId, members);
    });

    $(".dateQuickChange").click(function(e){
        e.stopPropagation();
    });

    $(".dateQuickChange").change(function(e){
        e.stopPropagation();
        var value = $(this).prop('checked') ? 1 : 0, memberId = $(this).parents('tr').attr('data-id'), winmin = $(this);
        $.post('/ajax/visits.php?set_date_visit', {value : value, memberId : memberId})
        .done(function(data){
          if(data.result){
              //showModalHintWindow("<strong>"+data.result+"</strong>");
              $(winmin).parents('tr').attr('data-performed', data.result.performed);
          }
      });
    });
/*
    $('.btn-remove-meeting').unbind('click');
    $('.btn-remove-meeting').click(function(e){
        e.stopPropagation();
        var meetingId = $(this).parents('tr').attr('data-id'),
            modal = $("#modalRemoveMeeting");
        modal.find(".remove-meeting").attr("data-id", meetingId);
        modal.modal("show");
    });
    */
    $("tbody tr").each(function(){
        if ($(this).find('.meeting-name') == 'Посещения') {
          $(this).find('.meeting-name').attr('style', 'font-weight: bold');
        }
    });
    $(".visitListDate").change(function(e){
        e.stopPropagation();
        var value = $(this).val(), visitId = $(this).parents('tr').attr('data-id'), winmin = $(this);
        if (!$(this).val()) {
          var actualyDate = $(this).parents('tr').attr('data-date');
          $(this).val(actualyDate);
          return
        }
        $.post('/ajax/visits.php?set_date_visit', {value : value, visitId : visitId})
        .done(function(data){
          if(data.result){
              //showModalHintWindow("<strong>"+data.result+"</strong>");
              $(winmin).parents('tr').attr('data-date', data.result.date_visit);
              var dayOfWeek = getNameDayOfWeek(data.result.date_visit);
              $(winmin).parents('td').find('.day-of-week-in-list').text(dayOfWeek);
          }
      });
    });

    $(".visitСhangeStts").change(function(e){
        e.stopPropagation();
        statusQua = 1;
        var statusOld = $(this).parents('tr').attr('data-performed');
        var statusNew = $(this).val();
        var visitId = $(this).parents('tr').attr('data-id');
        if (statusNew == 3) {
              modal = $("#modalRemoveMeeting");
          modal.find(".remove-meeting").attr("data-id", visitId);
          modal.modal("show");
          $(this).parents('td').find('select').val(statusOld);
        } else if (statusNew != statusOld) {

            $.post('/ajax/visits.php?set_status_visit', {value : statusNew, visitId : visitId})
            .done(function(data){
              if(!data.result){
                console.log('same data');
              }
            });
          statusNew == 0 ? statusNameCss = 'status-select-plan' : '' ;
          statusNew == 1 ? statusNameCss = 'status-select-done' : '' ;
          statusNew == 2 ? statusNameCss = 'status-select-failed' : '' ;

          $(this).parents('tr').attr('data-performed', statusNew);
          $(this).parents('td').find('select').val(statusNew);
          if ($(this).parents('td').find('select').hasClass('status-select-plan')) {
            $(this).parents('td').find('select').removeClass('status-select-plan');
            $(this).parents('td').find('select').addClass(statusNameCss);
          } else if ($(this).parents('td').find('select').hasClass('status-select-done')) {
            $(this).parents('td').find('select').removeClass('status-select-done');
            $(this).parents('td').find('select').addClass(statusNameCss);
          } else if ($(this).parents('td').find('select').hasClass('status-select-failed')) {
            $(this).parents('td').find('select').removeClass('status-select-failed');
            $(this).parents('td').find('select').addClass(statusNameCss);
          }
        } else {
          console.log('inspect option');
        }
    });
  sort ? filterMeetingsList() : '';
}

$(".btnDoHandleMeeting").click(function(){
    var modal = $("#addEditMeetingModal");

    var visitId = modal.attr('data-id') ? modal.attr('data-id') : '';
    var date = modal.find('#actionDate').val();
    var locality = modal.find('#visitLocalityModal').val();
    var actionType = $("#actionType").find(':selected').text();
    var note = modal.find('#visitNote').val();
    var responsible = modal.find('#responsible option:selected').val();
    var request = getRequestFromFilters(setFiltersForRequest());
    var performed = modal.find('#performedChkbx').val();
    var admin_key = window.adminId;

    if(!date || !locality || !actionType){
        showError('Необходимо заполнить все обязательные поля выделенные розовым цветом');
        return;
    }

    var members = [];
    modal.find("tbody tr").each(function(){
        members.push($(this).attr('data-id'));
    });

    var countMembers = members.length;

    if (countMembers === 0) {
      showError('Перед сохранением добавьте участника');
      return
    }

    $.post('/ajax/visits.php?set_visit'+request, {
        visitId : visitId,
        date: date,
        locality: locality,
        actionType : actionType,
        responsible: responsible,
        performed: performed,
        note: note,
        members : members.join(','),
        admin: admin_key,
        countMembers: countMembers
    }).done(function(data){
        if(data.isDoubleMeeting){
            showError('Данное собрание является дублирующим и не было сохранено!');
        }
        $(".localities-available").html('');
        $(".localities-added").html('');
        $(".searchLocality").val('');

        loadMeetings();
        $("#addEditMeetingModal").modal('hide');
    });
});

$(".remove-meeting").click(function(e){
    e.stopPropagation();
    var meetingId = $(this).attr('data-id');
    var request = getRequestFromFilters(setFiltersForRequest());
    if (!meetingId) {
      $("#modalRemoveMeeting").modal('hide');
      $("#addEditMeetingModal").modal('hide');
      return
    }
    if ($("#addEditMeetingModal").is(':visible')) {
      $("#addEditMeetingModal").modal('hide');
    }
    $("#modalRemoveMeeting").modal('hide');
    $.post('/ajax/visits.php?remove'+request, {meeting_id: meetingId})
    .done(function(data){
        refreshMeetings(data.meetings, true);
    });
});

function fillMeetingModalForm(textMode, date, locality, actionType, note, countList, performed, adminKey, responsible, actionId, members){
  fillAdminsOfLocations('#responsible', responsible);
    //window.selectedMeetingMembers = [];
// figure it out START
    var modal = $("#addEditMeetingModal"), isSingleCity = parseInt('<?php echo $isSingleCity; ?>');
    locality = isSingleCity ? '<?php echo $singleLocality; ?>' : locality;
    var isLocationAlone = $('#selMeetingLocality option').length == 2 ?  true : false;
// END
    nn = formatDate (new Date());
    nnn = formatDateNew(nn);
    modal.attr('data-status_val',performed);
    modal.find('#actionDate').val(date || nnn);
    modal.find('#actionType').val(actionType == 'Звонок' ? 'call' : 'visit');
    modal.find('#visitLocalityModal').val(locality || whatIsLocalityAdmin);
    modal.find('#performedChkbx').val(performed || 0);
    modal.find('#visitNote').val(note || '');
    //modal.find('#responsible').val(responsible);
    if (textMode == 'Карточка события') {
      (actionType == 'Посещение') ? modal.find("#titleMeetingModal").text('Посещение') : modal.find("#titleMeetingModal").text('Звонок');
    } else {
      modal.find("#titleMeetingModal").text('Посещение');
    }

    $(".show-templates.open-in-meeting-window").css('display', textMode === 'Новое событие' ? 'block': 'none');
    if(members && members !== 'null'){
        var members = members.split(','), membersArr = [];
        for(var i in members){
            var member = members[i];
            if (members[i].indexOf("Москва:") != -1 && !(members[i].indexOf("Москва:0") != -1 || members[i].indexOf("Москва:1") != -1)) {
              member = members[i].split(':');
              var varTemp = member.splice(3, 1);
              member[2] = member[2] + varTemp;
            } else {
              member = members[i].split(':');
            }

            membersArr.push({id: member[0], name: member[1], locality: member[2], attend_meeting: member[3], category_key: member[4], locality_key: member[5], cell_phone: member[6], birth_date: member[7], present : member[0]});
        }
        buildMembersList("#addEditMeetingModal", membersArr);
    }
    else{
        var modalWindow = $("#addEditMeetingModal");
        modalWindow.find('.members-available').html('');
        modalWindow.find('tbody').html('');
    }
    modal.modal('show');
    modal.find('#responsible').val(responsible);
    if (textMode == 'Новое событие') {
      if ($('.addEditMode').length == 0) {
        $('#addEditMeetingModal').addClass('addEditMode');
      }
    $('#modalAddMembersTemplate').modal('show');
    }
    getNameDayOfWeek( $('#addEditMeetingModal').find('.actionDate').val(),'#dayOfWeek', false);
    $('#modalAddMembersTemplate').on('hide', function (){
        if ($("#addEditMeetingModal").hasClass('new-visit-create')) {
          $("#addEditMeetingModal").removeClass('new-visit-create');
          $("#addEditMeetingModal").modal('hide');
        }
      });
}

$(".add-meeting").click(function(){
    $("#addEditMeetingModal").removeAttr('data-id');
    $("#addEditMeetingModal").find('.btnDoHandleMeeting').removeAttr('data-id');
    $("#addEditMeetingModal").addClass('new-visit-create');
    //handleExtraFields(false);
    fillMeetingModalForm('Новое событие');
});

function buildMembersList(modalWindowSelector, list, mode){
    var members = [];
    $(modalWindowSelector).find('.members-available').html('');

    if(list && list.length > 0){
        for (var i in list){
            var member = list[i], buttons = "<i title='Удалить' class='fa fa-trash fa-lg btn-remove-member'></i>";

            if (member.id < 990000000) {
            if (member.birth_date) {

              x = getAge(prepareGetAge(member.birth_date));
              x = getAgeWithSuffix(parseInt(x), x);
            } else {
              x='';
            }

            members.push("<tr class='check-member' data-id='"+member.id+"' data-attend_meeting='"+member.attend_meeting+"' data-name='"+member.name+"' data-category_key='"+member.category_key+"' data-birth_date='"+member.birth_date+"' data-locality_key='"+member.locality_key+"' data-cell_phone='"+member.cell_phone+"' data-locality='"+member.locality+"'><td><span><strong>" +member.name +"</strong><br>" +(member.cell_phone ? member.cell_phone +", " : '')  +x+"</span></td>"+
                "<td>"+buttons+"</td>"+
                "</tr>");
          }
        }

        if (mode == 'add_mode') {
          $(modalWindowSelector).find('.modal-body tbody').prepend(members.join(''));
        } else {
          $(modalWindowSelector).find('.modal-body tbody').html(members.join(''));
        }


        /*$('.check-member').click(function(){
            var element = $(this).find('.check-member-checkbox');

            element.prop('checked', !element.prop('checked'));
        });*/

        $(modalWindowSelector).find(".btn-remove-member").click(function(){
            var memberIdToDelete = $(this).parents('tr').attr('data-id'), members = [];

            $(modalWindowSelector + " tbody tr").each(function(){
                var id = $(this).attr("data-id"), name = $(this).attr("data-name"),
                locality = $(this).attr("data-locality"),
                attend_meeting = $(this).attr("data-attend_meeting"),
                category_key = $(this).attr("data-category_key"),
                locality_key = $(this).attr("data-locality_key"),
                cell_phone = $(this).attr("data-cell_phone"),
                birth_date = $(this).attr("data-birth_date"),
                present = $(this).find('.check-member-checkbox').prop('checked');
                if(id !== memberIdToDelete){
                    members.push({id : id, name: name, locality : locality, attend_meeting: attend_meeting, locality_key: locality_key, category_key: category_key, cell_phone: cell_phone, birth_date: birth_date, present : present});
                }
            });

            buildMembersList(modalWindowSelector, members);
            membersCounterMeeting();
            //confirmToRemoveMemberFromMeetingList(modalWindowSelector, memberId, memberName);
        });
        $(modalWindowSelector).find(".check-member-checkbox").click(function(){
          membersCounterMeeting();
        })
    }
    else{
        $(modalWindowSelector).find('.modal-body tbody').html('');
    }
    membersCounterMeeting();
}
// get admins of localities

function fillAdminsOfLocations(element, responsible) {
  var localities = []; responsibles = [];
  //element == '#responsibleList' ? responsibles.push('<option value="_all_">Все</option>') : '';
  $('#selMeetingLocality option').each(function(){
    $(this).val() != '_all_' ? localities.push($(this).val()): '';
  })
    $.post('/ajax/visits.php?get_list_admins', {})
      .done(function(data){
        var member = data.members;
        $(member).each(function(){
          id = this.id;
          x = this.locality_key;
          name = this.name;
          $(localities).each(function(i){
            if (localities[i] == x) {
              if (id != responsible && responsible) {
                responsibles.push('<option value="'+ id +'">'+ name +'</option>');
              } else if (id == responsible) {
                if (element == '#responsibleList') {
                  responsibles.push('<option selected value="'+ id +'">'+ name +'</option>');
                } else {
                    responsibles.push('<option selected value="'+ id +'">'+ name +'</option>');
                }
              } else if (!responsible && id == window.adminId) {
                if (element == '#responsibleList') {
                  responsibles.push('<option value="'+ id +'">'+ name +'</option>');
                } else {
                  responsibles.push('<option selected value="'+ id +'">'+ name +'</option>');
                }
              } else {
                responsibles.push('<option value="'+ id +'">'+ name +'</option>');
              }
            }
          })
        })
        $(element).html(responsibles.join(''));
      });
}

function filterMeetingsList(){
    startSystemFilter === 1 ? $('#selMeetingCategory').val('plan') : '';
    startSystemFilter === 1 ? $('#responsibleList').val(window.adminId) : '';
    startSystemFilter === 1 ? startSystemFilter = 0 : '';
    var responsible = $("#responsibleList").val();
    var locality = $("#selMeetingLocality").val();
    var meetingType = $("#selMeetingCategory").val();
    var countIteration = 0;
    if (meetingType === 'plan'){ meetingType = '0'};

    $(".meetings-list tbody tr").each(function(){
        (($(this).attr('data-responsible') === responsible || responsible == '_all_') && ($(this).attr('data-locality') === locality || $(this).attr('data-district') === locality || locality === '_all_') && ($(this).attr('data-performed') === meetingType || meetingType === '_all_')) ? ($(this).show(), countIteration++) : $(this).hide();
    });
    if (countIteration === 0 && start === 1) {
      $('#selMeetingCategory').val('_all_');
      $("#responsibleList").val('_all_');
      filterMeetingsList();
    }
}
function setFiltersForRequest(){
    var sort_type = 'desc',
        sort_field = 'date_visit';

    $('#eventTabs').find(" a[id|='sort']").each (function (i) {
        if ($(this).siblings("i.icon-chevron-down").length) {
            sort_type = 'asc';
            sort_field = $(this).attr("id").replace(/^sort-/,'');
        }
        else if ($(this).siblings("i.icon-chevron-up").length) {
            sort_type = 'desc';
            sort_field = $(this).attr("id").replace(/^sort-/,'');
        }
    });

    var localityFilter = $("#selMeetingLocality").val();
    var meetingTypeFilter = $("#selMeetingCategory").val();
    meetingTypeFilter == 'plan' ? meetingTypeFilter = '0' : '';
    var startDate = $(".start-date").val();
    var endDate = $(".end-date").val();
    var filters = [];
    filters = [{name: "sort_field", value: sort_field},
                {name: "sort_type", value: sort_type},
                {name: "meetingFilter", value: meetingTypeFilter || '_all_'},
                {name: "localityFilter", value: localityFilter || '_all_'},
                {name: "startDate", value: parseDate(startDate)},
                {name: "endDate", value: parseDate(endDate)}];

    return filters;
}

function getRequestFromFilters(arr){
    var str = '';
    arr.map(function(item){
        str += ('&'+item["name"] +'='+item["value"]);
    });
    return str;
}

function add(a, b) {
    return parseInt(a) + parseInt(b);
}

$("#responsibleList").change(function(){
    filterMeetingsList();
});

$("#selMeetingCategory, #selMeetingLocality").change(function(){
    filterMeetingsList();
});

$("a[id|='sort']").click (function (){
    //clickOnSort = 1;
    var id = $(this).attr("id");
    var icon = $(this).siblings("i");

    $(".meetings-list a[id|='sort'][id!='"+id+"'] ~ i").attr("class","icon-none");
    icon.attr ("class", icon.hasClass("icon-chevron-down") ? "icon-chevron-up" : "icon-chevron-down");
/*
    if (id == 'sort-list_members') {
      icon.hasClass("icon-chevron-down") ? sortingVisits(1) : sortingVisits(2);
      filterMeetingsList()
    } else if (id == 'sort-responsible') {
      icon.hasClass("icon-chevron-down") ? sortingVisits(3) :sortingVisits(4);
      filterMeetingsList()
    } else {
      loadMeetings ();
    }
*/
    if (id === 'sort-list_members') {
      icon.hasClass("icon-chevron-down") ? sortingVisits(1) : sortingVisits(2);
    } else if (id === 'sort-responsible') {
      icon.hasClass("icon-chevron-down") ? sortingVisits(3) :sortingVisits(4);
    } else if (id === 'sort-act') {
      icon.hasClass("icon-chevron-down") ? sortingVisits(5) :sortingVisits(6);
    } else if (id === 'sort-date_visit') {
      icon.hasClass("icon-chevron-down") ? sortingVisits(7) :sortingVisits(8);
    } else if (id === 'sort-locality_key') {
      icon.hasClass("icon-chevron-down") ? sortingVisits(9) :sortingVisits(10);
    } else if (id === 'sort-status') {
      icon.hasClass("icon-chevron-down") ? sortingVisits(11) :sortingVisits(12);
    } else {
      loadMeetings ();
    }
});

function sortingVisits(sortType) {
  var list = [], tableRows = [], phoneRows = [], isLocationAlone = $('#selMeetingLocality option').length == 2 ?  true : false;
  $('#meetings').find('tr').each(function(){
      var membersList = $(this).attr('data-members'),
          responsible = $(this).attr('data-responsible'),
          performed = $(this).attr('data-performed'),
          note = $(this).attr('data-note'),
          type = $(this).attr('data-type'),
          date = $(this).attr('data-date'),
          count = $(this).attr('data-count'),
          id = $(this).attr('data-id'),
          locality = $(this).attr('data-locality'),
          admin = $(this).attr('data-admin_key'),
          responsibleName = $(this).find('td:eq(4)').text(),
          visit_data = $(this).find('td:eq(1)').text(),
          localityName = $(this).find('td:eq(2)').text();
    if (id) {
      list.push({visit_id: id, act: type, locality_key: locality, performed: performed, members: membersList, responsible: responsible, comments: note, date_visit: date, count_members: count, admin_key: admin, responsible_name: responsibleName, visit_data: visit_data, locality_name: localityName});
    }
  });

if (sortType == 1) {
  list.sort(function (a, b) {
    if (a.visit_data > b.visit_data) {
      return 1;
    }
    if (a.visit_data < b.visit_data) {
      return -1;
    }
    return 0;
  });
}

if (sortType == 2) {
  list.sort(function (a, b) {
    if (a.visit_data < b.visit_data) {
      return 1;
    }
    if (a.visit_data > b.visit_data) {
      return -1;
    }
    return 0;
  });
}
if (sortType == 3) {
  list.sort(function (a, b) {
    if (a.responsible_name > b.responsible_name) {
      return 1;
    }
    if (a.responsible_name < b.responsible_name) {
      return -1;
    }
    return 0;
  });
}

if (sortType == 4) {
  list.sort(function (a, b) {
    if (a.responsible_name < b.responsible_name) {
      return 1;
    }
    if (a.responsible_name > b.responsible_name) {
      return -1;
    }
    return 0;
  });
}
if (sortType == 5) {
  list.sort(function (a, b) {
    if (a.act > b.act) {
      return 1;
    }
    if (a.act < b.act) {
      return -1;
    }
    return 0;
  });
}

if (sortType == 6) {
  list.sort(function (a, b) {
    if (a.act < b.act) {
      return 1;
    }
    if (a.act > b.act) {
      return -1;
    }
    return 0;
  });
}
if (sortType == 7) {
  list.sort(function (a, b) {
    if (a.date_visit > b.date_visit) {
      return 1;
    }
    if (a.date_visit < b.date_visit) {
      return -1;
    }
    return 0;
  });
}
if (sortType == 8) {
  list.sort(function (a, b) {
    if (a.date_visit < b.date_visit) {
      return 1;
    }
    if (a.date_visit > b.date_visit) {
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
refreshMeetings(list,true);

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
}
var dateActionChange=parseDDMM ($('#addEditMeetingModal').find('.actionDate').val());

function formatDateNew(date) {
  dates = []
  var tempDate = date.split('.');
  dates.push(tempDate[2]);
  dates.push(tempDate[1]);
  dates.push(tempDate[0]);
  //var dates2 = new Date(tempDate[2], tempDate[1], tempDate[0]);
  date = dates.join('-');
  return date;

}
function getNameDayOfWeek(date, modal, short) {
  //var date = parseDDMM ($('#addEditMeetingModal').find('.actionDate').val());
  var tempDate = date.split('-');
  var dates = new Date(tempDate[0], tempDate[1]-1, tempDate[2]);
  var weekday = new Array(7);
  if (short) {
    weekday[0] = "Вс";
    weekday[1] = "Пн";
    weekday[2] = "Вт";
    weekday[3] = "Ср";
    weekday[4] = "Чт";
    weekday[5] = "Пт";
    weekday[6] = "Сб";
  } else {
    weekday[0] = "Воскресенье";
    weekday[1] = "Понедельник";
    weekday[2] = "Вторник";
    weekday[3] = "Среда";
    weekday[4] = "Четверг";
    weekday[5] = "Пятница";
    weekday[6] = "Суббота";
  }
  var dayofweek = weekday[dates.getDay()];
  if (modal) {
    $(modal).text(dayofweek);
  } else {
    return dayofweek;
  }
}

$('.actionDate').change(function() {
  if ($('#addEditMeetingModal').find('.actionDate').val()) {
    $('#addEditMeetingModal').find('.actionDate').removeAttr('style', 'background-color: #FCF4F4; border-color:#E08A88;');
    var date = $('#addEditMeetingModal').find('.actionDate').val();
    getNameDayOfWeek(date, '#dayOfWeek', false);
  } else {
    $('#addEditMeetingModal').find('.actionDate').attr('style', 'background-color: #FCF4F4; border-color:#E08A88;')
  }

})
$('#actionType').change(function() {
  $('#actionType').val() == 'call' ? $('#titleMeetingModal').html('Звонок') : $('#titleMeetingModal').html('Посещение');

})
$('#performedChkbx').change(function() {
  var statusNew = $(this).val();
  var statusOld = $('#addEditMeetingModal').attr('data-status_val');
  statusNew == 0 ? statusNameCss = 'status-select-plan' : '' ;
  statusNew == 1 ? statusNameCss = 'status-select-done' : '' ;
  statusNew == 2 ? statusNameCss = 'status-select-failed' : '' ;

  if (statusNew == 3) {
    modal = $("#modalRemoveMeeting");
    $(this).val(statusOld);
    var visitId = $("#addEditMeetingModal").attr('data-id');
    if (visitId) {
      modal.find(".remove-meeting").attr("data-id", visitId);
      modal.modal("show");
    } else {
      modal.modal("show");
    }
  } else if ($(this).hasClass('status-select-plan')) {
    $(this).removeClass('status-select-plan');
    $(this).addClass(statusNameCss);
  } else if ($(this).hasClass('status-select-done')) {
    $(this).removeClass('status-select-done');
    $(this).addClass(statusNameCss);
  } else if ($(this).hasClass('status-select-failed')) {
    $(this).removeClass('status-select-failed');
    $(this).addClass(statusNameCss);
  } else {
      console.log('inspect option');
  }
});

// END CALLS AND VISITS CODE

        setAdminRole_0('.add-meeting');
        $('.checkbox-block input').change(function(){
            var isElementChecked = $(this).prop('checked'),
                modalWindowSelector = $(this).parents('.modal').attr('id');

            if(isElementChecked){
                if($(this).attr('id') === 'checkbox-locality'){
                    $('.search-members').val('').attr('placeholder', 'Введите местность').focus();
                }
                else{
                    $('.search-members').val('').attr('placeholder', 'Введите ФИО').focus();
                }

                $(this).parents('.checkbox-block').find('input').prop('checked', false);
                $(this).prop('checked', true);
            }
            $('#'+modalWindowSelector + ' .members-available').html('');
        });

        $('.search-members').keyup(function(){
            var text = $(this).val().trim().toLocaleLowerCase(),
                modalWindowSelector = $(this).parents('.modal').attr('id'),
                checkbox = $('.checkbox-block input[type="radio"]:checked');

            if(text === ''){
                $('#'+modalWindowSelector + ' .members-available').html('');
            }
            else if(!checkbox.length>0){
                showError('Выберите один из пунктов: "Местности" или "Участники".');
                return;
            }
            else{
                $.post('/ajax/meeting.php?get_template_participants', {text : text, field : checkbox.attr('data-field')})
                .done(function(data){
                    if(data.participants && data.participants.length > 0){
                        getMeetingMembersToAdd('#'+modalWindowSelector, data.participants);
                        //handleTemplateParticipants(data.participants);
                    }
                    else{
                        $('#'+modalWindowSelector + '.members-available').html('');
                    }
                });
            }
        });

        $(".search-meeting-available-participants").keyup(function(){
            var text = $(this).val().trim();
            if(text){
                $.post('/ajax/meeting.php?get_available_members', {text: text})
                .done(function(data){
                    var isAdminMode = false;
                    getMeetingParticipantsToAdd(data.members, isAdminMode);
                });
            }
            else{
                $('.participants-available').html('');
            }
        });

        /*
        $(".search-meeting-members").keyup(function(){
            filterMembersList();
        });
        */

        $('.search-template-admins').keyup(function(){
            var text = $(this).val().trim().toLocaleLowerCase();

            if(text === ''){
                $('.template-admins-available').html('');
            }
            else{
                $.post('/ajax/meeting.php?get_template_participants', {text : text, field : 'm'})
                .done(function(data){
                    if(data.participants && data.participants.length > 0){
                        handleTemplateAdmins(data.participants);
                    }
                    else{
                        $('.template-admins-available').html('');
                    }
                });
            }
        });

        function handleTemplateAdmins(admins, isEditMode){
            var list = buildTemplateAdminsList (admins, isEditMode);

            $(!isEditMode ? '.template-admins-available' : '.template-admins-added').html(list.join(''));
            if(admins.length === 0){
                $('.template-admins-available').html('');
                $('.template-admins-added').html('');
            }

            $('.addTemplateAdmin').click(function(e){
                e.stopPropagation();

                var element = $(this).parents('div'), id = element.attr('data-id'), isNewId = !in_array(id, window.meetingTemplateAdmins);
                var admin = buildTemplateAdminsList([{id : id, name : element.attr('data-name'), field : 'm'}], true);

                if(isNewId){
                    $('.template-admins-added').append(admin);
                    $('.template-admins-available').html('');
                    $('.search-template-admins').val('').focus();
                }
                else{
                    showError('Эти редакторы уже добавлены', true);
                }

                bindHandlerToRemoveAdmins();
            });

            bindHandlerToRemoveAdmins();
        }

        function buildTemplateAdminsList (admins, isEditMode){
            var adminRows = [];

            for(var m in admins){
                var admin = admins[m];

                if(isEditMode){
                    window.meetingTemplateAdmins.push(admin.id);
                }

                adminRows.push('<div data-id="'+admin.id+'" data-name="'+admin.name+'">'+
                                    '<span>'+ admin.name +'</span> '+
                                    '<i data-id="'+admin.id+'" class="fa fa-lg '+ ( isEditMode ? "fa-times removeTemplateAdmin" : "fa-plus addTemplateAdmin") + '" '+
                                    'title="' + ( isEditMode ? "Убрать " + admin.name + " из редакторов" : "Добавить " + admin.name + " к редакторам" )+'">'+
                                    '</i>'+
                                '</div>');
            }
            return adminRows;
        }

        function bindHandlerToRemoveAdmins(){
            $('.removeTemplateAdmin').click(function(e){
                e.stopPropagation();
                var id = $(this).attr('data-id');

                if(in_array(id, window.meetingTemplateAdmins)){
                    var index = window.meetingTemplateAdmins.indexOf(id);
                    if (index > -1)
                        window.meetingTemplateAdmins.splice(index, 1);
                }
                $(this).parents('div [data-id='+id+']').remove();
            });
        }

        $(".show-templates").click(function(e){
            e.preventDefault();
            var modalWindow = $("#modalTemplates");

            getMeetingTemplates($(this).hasClass('open-in-meeting-window'));
            modalWindow.modal('show');
        });

        $(".btn-add-template").click(function(){
            var template = {
                type : '_none_',
                name : '',
                localityKey : '_none_',
                participants : '',
                admins: ''
            };

            handleTemplate('add', template);
        });

        function getMeetingTemplates(isOpenInMeetingWindow){
            $.get('/ajax/meeting.php?get_templates')
            .done(function(data){
                buildTemplatesList(data.templates, isOpenInMeetingWindow);
            });
        }

        function buildTemplatesList(templates, isOpenInMeetingWindow){
            if(templates.length === 0){
                $("#template-list").hide();
                $("#modalTemplates .modal-body .empty-template-list").remove();
                $("#modalTemplates .modal-body").append('<div class="empty-template-list" style="text-align:center; padding-top: 20px;padding-bottom: 20px;"><strong>На данный момент шаблонов нет.</strong></div>');
            }
            else{
                var templatesArr = [];
                $("#template-list").show();
                $("#modalTemplates .modal-body .empty-template-list").remove();

                for(var i in templates){
                    var t = templates[i],
                        buttons = '<span class="fa fa-plus btn-add-template-meeting fa-lg" title="Создать собрание"></span> <span class="edit-template" title="Редактировать шаблон"><i class="fa fa-pencil fa-lg"></i></span> <span title="Удалить шаблон" class="delete-template"><i class="fa fa-trash fa-lg"></i></span>';

                    var admins = t.admins ? t.admins.split(',') : [],
                        participants = t.participants ? t.participants.split(',') : '';

                    templatesArr.push(
                        "<tr data-id='"+t.id+"' data-locality='"+t.locality_key+"' data-type='"+t.meeting_type+"' >" +
                        "<td><label class='form-check-label'>"+ ( isOpenInMeetingWindow ? "<input style='margin-top: -3px;' type='checkbox' class='check-template form-check-input'> " : "" ) + t.template_name+"</label></td>"+
                        "<td class='template-name'>"+ t.locality_name + "</td>"+
                        "<td class='template-participants' data-participants='"+participants+"'>"+ participants.length + " <i class='fa fa-list fa-lg template-participants-info' title='Список участников'></i></td>"+
                        "<td class='template-admins' data-admins='"+t.admins+"'>"+ admins.length +" <i class='fa fa-list fa-lg template-admins-info' title='Список редакторов'></i></td>"+
                        "<td>"+ buttons +"</td>"+
                        "</tr>"
                    );
                }

                $("#template-list tbody").html(templatesArr.join(''));

                var modalWindowTemplates = $("#modalTemplates");

                if(isOpenInMeetingWindow){
                    modalWindowTemplates.find('.modal-footer').html('<button class="btn btn-primary fill-meeting-by-template" data-dismiss="modal" aria-hidden="true" disabled>Заполнить</button>'+
                        '<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отменить</button>');

                    $(".fill-meeting-by-template").click(function(){
                        if(!$(this).attr('disabled')){
                            $("#addEditMeetingModal tbody").html('');
                            var templateId = modalWindowTemplates.find(".check-template[type='checkbox']:checked").parents('tr').attr('data-id');

                            $.post('/ajax/meeting.php?get_template', { templateId : templateId }).done(function(data){
                                if(data.template){
                                    var templateInfo = data.template, countList = '',
                                        countChildren = '', countFulltimers = '', countTrainees = '';

                                    if(templateInfo.members){
                                        var members = templateInfo.members.split(',');
                                        countList = members.length;

                                        for(var i in members){
                                            var member = members[i].split(':'), age, category;

                                            if(member.length > 0){
                                                category = member.length == 2 ? member[1] : typeof member[0] === 'string' ? member[0] : '';
                                                age = member.length == 2 ? member[0] : typeof member[0] === 'string' ? 0 : member[0];

                                                if(age > 0  && age < 12){
                                                    countChildren= 0;
                                                }
// Romans change
                                                switch (category) {
                                                    case 'FT':
                                                        countTrainees = 0;
                                                        break;
                                                    case 'FS':
                                                        countFulltimers = 0;
                                                        break;
                                                }
                                            }
                                        }
                                    }
                                    isFillTemplate = 1;
                                    fillMeetingModalForm('Новое собрание', '', templateInfo.locality_key, templateInfo.meeting_type_key, '', countList, '', '', countChildren, countFulltimers, countTrainees, false, '', templateInfo.meeting_name, templateInfo.participants, '');

                                }
                                else{
                                    showError('Информация по данному шаблону не найдена.');
                                }
                            });
                        }
                    })
                }
                else{
                    modalWindowTemplates.find('.modal-footer').html('<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Ok</button>');
                }

                $(".check-template").change(function(){
                    var changedItemId = $(this).parents('tr').attr("data-id");

                    $(this).parents("tbody").find("tr").each(function(){
                        if($(this).attr("data-id") !== changedItemId){
                            $(this).find('.check-template').prop('checked', false);
                        }
                    });

                    $(".fill-meeting-by-template").attr('disabled', !$(this).prop('checked'));
                });

                $(".btn-add-template-meeting").click(function(){
                    var modalWindow = $("#modalConfirmAddMeetingByTemplate"),
                        id = $(this).parents('tr').attr('data-id');

                    modalWindow.find('.add-template-meeting').attr('data-id', id);
                    modalWindow.modal('show');
                });

                $(".edit-template").click(function(){
                    var element = $(this).parents('tr'),
                        id = element.attr('data-id'),
                        type = element.attr('data-type'),
                        name = element.find('.template-name').text().trim(),
                        locality = element.attr('data-locality'),
                        participants = element.find('.template-participants').attr('data-participants'),
                        admins = element.find('.template-admins').attr('data-admins');

                    var template = {
                        type : type,
                        name : name,
                        localityKey : locality,
                        participants : participants,
                        admins: admins,
                        id: id
                    };

                    handleTemplate('edit', template);
                });

                $(".delete-template").click(function(){
                    var modalWindow = $("#modalConfirmDeleteTemplate"),
                        element = $(this).parents('tr'),
                        id = element.attr('data-id');

                        modalWindow.find('.doDeleteTemplate').attr('data-id', id);
                        modalWindow.modal('show');
                });

                $(".template-admins-info").click(function(){
                    var admins = $(this).parents('.template-admins').attr('data-admins'),
                        templateId = $(this).parents('tr').attr('data-id');

                    buildAdminsList('admins', admins, templateId);
                });

                $(".template-participants-info").click(function(){
                    var participants = $(this).parents('.template-participants').attr('data-participants'),
                        templateId = $(this).parents('tr').attr('data-id');
                    buildAdminsList('participants', participants, templateId);
                });
            }
        }

        $(".add-template-meeting").click(function(){
            var templateId = $(this).attr('data-id');

            var request = getRequestFromFilters(setFiltersForRequest());

            $.post('/ajax/meeting.php?add_template_meeting&'+request, {templateId: templateId})
            .done(function(data){
                refreshMeetings(data.meetings);
            });
        });

        $('.doDeleteTemplate').click(function(){
            var templateId = $(this).attr('data-id');
            $.post('/ajax/meeting.php?delete_template', {templateId :templateId}).done(function(data){
                var modalWindow = $("#addEditMeetingModal").data('modal');

                buildTemplatesList(data.templates, modalWindow && modalWindow.isShown);
            });
        });

        function buildAdminsList(mode, list, templateId){
            var modalWindow =  $("#modalParticipantsList"), members = [];
            modalWindow.attr('data-mode', mode).attr('data-templateId', templateId);

            $(".search-meeting-available-participants").val('').focus();
            $(".participants-available").html('');

            window.meetingTemplateParticipantsList = [];
            window.meetingTemplateAdminsList = [];

            if(list){
                var listArr = list.split(',');

                for (var i in listArr){
                    var item = listArr[i], member = item.split(':'), buttons = "<i title='Удалить' class='fa fa-trash fa-lg btn-remove-item'></i>";

                    if(mode === 'participants'){
                        window.meetingTemplateParticipantsList.push(member[0]);
                    }
                    else{
                        window.meetingTemplateAdminsList.push(member[0]);
                    }

                    members.push("<tr data-id='"+member[0]+"' data-name='"+member[1]+"'>"+
                        "<td>"+member[1]+"</td>"+
                        "<td>"+member[2]+"</td>"+
                        "<td>"+buttons+"</td>"+
                        "</tr>");
                }

                modalWindow.find('table').show();
                modalWindow.find('.modal-body .empty-template-participants-list').remove();
                modalWindow.find('.modal-body tbody').html(members.join(''));

                $(".btn-remove-item").click(function(){
                    var itemId = $(this).parents('tr').attr('data-id'),
                        confirmationWindow = $("#modalConfirmDeleteParticipant"),
                        name = $(this).parents('tr').attr('data-name');

                    confirmationWindow.find('.doDeleteParticipant').attr('data-id', itemId).attr('data-mode', mode).attr('data-template', templateId);

                    confirmationWindow.find('.modal-body').html('Вы действительно хотите удалить '+ name +' из списка ' + ( mode === 'participants' ? 'участников' : 'редакторов'));
                    confirmationWindow.find('.modal-header h3').html('Удаление '+ ( mode === 'participants' ? 'участника' : 'редактора'));
                    confirmationWindow.modal('show');
                });
            }
            else{
                modalWindow.find('table').hide();
                modalWindow.find('.modal-body .empty-template-participants-list').remove();
                modalWindow.find('.modal-body').append('<div class="empty-template-participants-list" style="text-align:center; padding-top: 20px;padding-bottom: 20px;"><strong>'+ ( mode === "participants" ? "Список участников пуст" : "Список редакторов пуст" )+ '</strong></div>');
            }



            modalWindow.find('.modal-header h3').html( mode === 'participants' ? 'Список участников' : 'Список редакторов');
            modalWindow.modal('show');
        }

        $(".doDeleteParticipant").click(function(){
            var memberId = $(this).attr('data-id'),
                mode = $(this).attr('data-mode'),
                templateId =  $(this).attr('data-template');

            $.post('/ajax/meeting.php?delete_participants', {memberId : memberId, mode : mode, templateId : templateId})
            .done(function(data){
                var modalWindow = $("#addEditMeetingModal").data('modal');

                buildAdminsList(mode, data.participants, templateId);
                buildTemplatesList(data.templates, modalWindow && modalWindow.isShown);
            });
        });

        $(".template-meeting-type").change(function(){
            var text = $('.template-meeting-type option:selected').text();
            $(".template-name").val(text).keyup();
        });

        function handleTemplate(mode, template){
            var modalWindow = $("#modalHandleTemplate");
            $("#modalHandleTemplate").attr('data-id', template.id);
            modalWindow.find('.modal-header h3').html( mode === 'add' ? 'Создание шаблона' : 'Изменение шаблона' );

            modalWindow.find('.template-meeting-type').val(template.type).change();
            modalWindow.find('.template-name').val(template.name).keyup();
            modalWindow.find('.template-locality').val(template.localityKey).change();

            var adminsArrTemp = template.admins ? template.admins.split(',') : [],
                participantsArrTemp = template.participants ? template.participants.split(',') : [],
                adminsArr = [], participantsArr = [];

            if(adminsArrTemp.length > 0){
                for (var i = 0 in adminsArrTemp) {
                    var admin = adminsArrTemp[i];
                    var adminItem = admin.split(':');
                    adminsArr.push({id: adminItem[0], name : adminItem[1]});
                }
            }

            if(participantsArrTemp.length > 0){
                for (var i = 0 in participantsArrTemp) {
                    var participant = participantsArrTemp[i];
                    var participantItem = participant.split(':');
                    if (participant.indexOf("Москва:") != -1 && !(splitMember[0].indexOf("Москва:0") != -1 || splitMember[0].indexOf("Москва:1") != -1)) {
                      var varTemp = participantItem.splice(3, 1);
                      participantItem[2] = participantItem[2] + varTemp;
                    }
                    participantsArr.push({id: participantItem[0], name : participantItem[1], locality: participantItem[2], attend_meeting: participantItem[3], category_key : participantItem[4]});
                }
            }

            //window.selectedTemplateMembers = [];

            buildMembersList(modalWindow.selector, participantsArr);
            handleTemplateAdmins(adminsArr, mode !== 'add');

            modalWindow.find('.btn-handle-template').html( mode === 'add' ? 'Создать' : 'Изменить' ).addClass( mode === 'add' ? 'add' : 'edit').removeClass( mode === 'add' ? 'edit' : 'add').attr('data-id', template.id);
            modalWindow.modal('show');
        }

        $('.btn-handle-template').click(function(){
            var modalWindow = $("#modalHandleTemplate");

            var type = modalWindow.find('.template-meeting-type').val(),
                name = modalWindow.find('.template-name').val(),
                locality = modalWindow.find('.template-locality').val(),
                participants = [],
                admins = modalWindow.find('.template-admins').val(),
                id = $(this).attr('data-id');

            if(type === '_none_' || name.trim() === '' || locality === '_none_'){
                showError('Необходимо заполнить все поля выделенные розовым цветом.');
                return;
            }

            modalWindow.find('#list tbody tr').each(function(){
                var memberId = $(this).attr('data-id');
                participants.push(memberId);
            });

            $.post("/ajax/meeting.php?handle_template", {id: id, type: type, name : name, locality : locality, participants : participants.join(','), admins : window.meetingTemplateAdmins.join(',')})
                .done(function(data){
                    var modal = $("#addEditMeetingModal").data('modal');
                    buildTemplatesList(data.templates, modal && modal.isShown);

                    modalWindow.modal('hide');
                });
        });

        $(".btn-meeting-general-statistic").click(function(){
            $("#modalGeneralMeetingStatistic").modal('show');
        });

        $("#modalGeneralMeetingStatistic").on('shown', function(){
            getGeneralStatistic();
        });

        $("#meetingTypeGeneralStatistic, #localityGeneralStatistic, .start-date-statistic-general, .end-date-statistic-general").change (function (){
            getGeneralStatistic();
        });

        $(window).resize(function() {
            if($("#modalGeneralMeetingStatistic").is(':visible')){
                if(this.resizeTO) clearTimeout(this.resizeTO);
                this.resizeTO = setTimeout(function() {
                    $(this).trigger('resizeEnd');
                }, 500);
            }
        });

        $(window).on('resizeEnd', function() {
            getGeneralStatistic();
        });

        function drawChart(information){
            var info = [],
                showMeetingType = $("#meetingTypeGeneralStatistic").val() === '_all_',
                showLocalityName = $("#localityGeneralStatistic").val() === '_all_';

            info.push(['Дата', 'По списку', 'На собрании', 'Служащих', 'Обучающихся', 'Гостей', 'Всего', 'Детей']);

            information.forEach(function(item){
                var meetingCounts = getMeetingCounts(item);

                if((meetingCounts.countMembers + meetingCounts.childrenCount)>0){
                    info.push( [
                        formatDate(item.date) + (showLocalityName ? '\n( '+he(item.locality_name)+' )' : '') + (showMeetingType ? '\n'+he(item.short_name) : ''),
                        meetingCounts.listCount,
                        meetingCounts.saintsCount,
                        meetingCounts.fulltimersCount,
                        meetingCounts.traineesCount,
                        meetingCounts.guestCount,
                        meetingCounts.countMembers,
                        meetingCounts.childrenCount
                    ]);
                }
            });

            var data = google.visualization.arrayToDataTable(info);

            var options = {
              chart: {
                title: '',
                subtitle: '',
              },
              bars: 'horizontal'
            };

            var chart = new google.charts.Bar(document.getElementById('chart_div'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        }

        function getGeneralStatistic(toDownload) {
            var locality = $("#localityGeneralStatistic").val();
            var meetingType = $("#meetingTypeGeneralStatistic").val();
            var startDate = $(".start-date-statistic-general").val();
            var endDate = $(".end-date-statistic-general").val();

            $.getJSON('/ajax/meeting.php?get_general_statistic', {locality : locality || '_all_', meeting_type : meetingType || '_all_', startDate : parseDate(startDate), endDate : parseDate(endDate)})
            .done(function(data){
                toDownload ? downloadGeneralStatistic(data.list) : drawChart(data.list);
            });
        }

        function downloadGeneralStatistic(list){
            if(list.length>0){
                $.ajax({
                    type: "POST",
                    url: "/ajax/excelList.php",
                    data: "page=meeting_general"+"&list="+JSON.stringify(list)+"&adminId="+window.adminId,
                    cache: false,
                    success: function(data) {
                        document.location.href="./ajax/excelList.php?file="+data;
                        setTimeout(function(){
                            deleteFile(data);
                        }, 10000);
                    }
                });
            }
            else{
                showError("Список пустой. Нет данных для скачивания");
            }
        }

        $('.btn-meeting-download-general-statistic').click(function(){
            getGeneralStatistic(true);
        });

        $('.btn-meeting-download-members-statistic').click(function(){
            var memberLocality, memberName, members = [], list_count = $(this).attr('data-list_count') || 0 , members_count = $(this).attr('data-members_count') || 0;

            $("#modalMeetingStatistic tbody tr").each(function(){
                memberLocality = $(this).attr('data-locality_name');
                memberName = $(this).find('.statistic-part > span').text();

                var meetings=[];
                $(this).find('.statistic-date-part span').each(function(){
                    meetings.push($(this).attr('data-type')+':'+$(this).attr('title'));
                });

                members.push({locality: memberLocality, memberName:memberName, meetings:meetings.join(','), members_count: members_count, list_count: list_count});
            });

            if(members.length>0){
                $.ajax({
                    type: "POST",
                    url: "/ajax/excelList.php",
                    data: "page=meeting_members"+"&members="+JSON.stringify(members)+"&adminId="+window.adminId+"&members_count="+members_count+"&list_count="+list_count+"&start_date="+$('.start-date-statistic-members').val() + "&end_date="+$('.end-date-statistic-members').val(),
                    cache: false,
                    success: function(data) {
                        document.location.href="./ajax/excelList.php?file="+data;
                        setTimeout(function(){
                            deleteFile(data);
                        }, 10000);
                    }
                });
            }
            else{
                showError("Список пустой. Нет данных для скачивания");
            }
        });

        $("#modalMeetingStatistic .modal-body, #modalGeneralMeetingStatistic .modal-body").scroll(function(){
            handleScrollUpBtn($(this));
        });

        function handleScrollUpBtn(form){
            var height = form.find('tbody').height();
            var scrollTop = form.scrollTop();
            height>600 && scrollTop>300 ? form.find(".scroll-up").show() : form.find(" .scroll-up").hide();
        }

        $("#modalMeetingStatistic .scroll-up, #modalGeneralMeetingStatistic .scroll-up").click(function(e){
            e.stopPropagation();
            $(this).parents('.modal-body').animate({
                scrollTop: 0
            }, 500);
        });

        $(".input-daterange .start-date, .input-daterange .end-date").change(function(){
            loadMeetings();
        });

        $("#localityStatistic, #meetingTypeStatistic, .start-date-statistic-members, .end-date-statistic-members").change(function(){
            getMembersStatistic();
        });



        $(".btn-meeting-members-statistic").click(function(){
            getMembersStatistic();
        });

        function getMembersStatistic(){
            var locality = $("#localityStatistic").val();
            var meetingType = $("#meetingTypeStatistic").val();
            var startDate = $(".start-date-statistic-members").val();
            var endDate = $(".end-date-statistic-members").val();

            $.getJSON('/ajax/meeting.php?get_members_statistic', {locality: locality || '_all_', meeting_type: meetingType || '_all_', startDate:parseDate(startDate), endDate : parseDate(endDate)}).done(function(data){
                buildStatisticMembersList(data.list, data.members_list_count);
                $("#modalMeetingStatistic").modal('show');
                $("#modalMeetingStatistic .scroll-up").hide();
            });
        }

        function buildStatisticMembersList(list, membersListCount){
            var tableRows = [], members_count = 0;

            for (var i in list) {
                var m = list[i], arr = [], meeting = m.meeting.split(','), meetingArr;

                members_count ++;
                for(var j in meeting){
                    meetingArr = meeting[j].split(':');
                    arr.push('<span class="statistic-bar '+(meetingArr[0]+'-meeting')+'" data-type="'+meetingArr[0]+'" title="'+(meetingArr[1])+'"></span>');
                }

                tableRows.push(
                    '<tr data-id="'+m.member_key+'" data-locality_name="'+he(m.locality_name)+'" data-locality="'+(m.locality_key)+'" >'+
                    '<td class="statistic-part"><span>'+he(m.name)+'</span></td><td class="statistic-date-part" style="padding-top: 10px;">'+arr.join('')+'</td>/tr>'
                );
            }

            $("#modalMeetingStatistic .btn-meeting-download-members-statistic").attr('data-list_count', parseInt(membersListCount) || '' ).attr('data-members_count', members_count || '');
            $("#modalMeetingStatistic #general-statistic").html('По списку — '+(parseInt(membersListCount) || '')+' чел. Участвовали в собраниях — '+members_count+' чел.');
            $("#modalMeetingStatistic tbody").html(tableRows.join(''));
        }

        $(".meeting-add-btn").click(function(e){
            e.preventDefault();

            $(".block-add-members").css('display') === 'block' ? $(".block-add-members").css('display', 'none'): $(".block-add-members").css('display', 'block');
        });

        $(".meeting-clear-btn").click(function(e){
            e.preventDefault();

            var members = [], modalWindowSelector = $(this).parents('.modal').attr('id');


            buildMembersList('#'+modalWindowSelector, members);
        });

        function getMeetingMembersToAdd(modalWindowSelector, members){
            var memberRows = [];

            if(!members || members.length === 0){
                $(modalWindowSelector).find('.members-available').html('');
            }
            else{
                for(var m in members){
                    var member = members[m];

                    memberRows.push(
                        '<div data-id="'+member.id+'" data-attend_meeting="'+member.attend_meeting+'" data-name="'+member.name+'" data-cell_phone="'+member.cell_phone+'" data-category="'+member.category_key+'" data-birth_date="'+member.birth_date+'" data-locality="'+member.locality+'">'+
                            '<span>'+ member.name + ' (' + member.locality + ')</span> '+
                            '<i data-id="'+member.id+'" class="fa fa-lg fa-plus addMeetingMember" title="Добавить" ></i>'+
                        '</div>'
                    );
                }

                $(modalWindowSelector + ' .members-available').html(memberRows.join(''));

                $(modalWindowSelector + ' .addMeetingMember').click(function(e){
                    e.stopPropagation();
                    var element = $(this).parents('div'),
                        id = element.attr("data-id"),
                        name = element.attr("data-name"),
                        locality = element.attr("data-locality"),
                        attendMeeting = element.attr('data-attend_meeting'),
                        category = element.attr('data-category'),
                        mode = 'm',
                        membersId = [], list = [];

                    //if(modalWindowSelector === "#modalHandleTemplate"){
                        var checkbox = $(modalWindowSelector + ' .checkbox-block input[type="radio"]:checked');

                        if(!checkbox.length>0){
                            showError('Выберите один из пунктов: "Местности" и или "Участники".');
                            return;
                        }
                        else{
                            mode = checkbox.attr('data-field');
                        }
                    //}

                    // get existing list
                    $(modalWindowSelector + " tbody tr").each(function(){
                        var memberName = $(this).attr('data-name'),
                            memberId = $(this).attr('data-id'),
                            memberLocality = $(this).attr('data-locality'),
                            attendMeeting = $(this).attr('data-attend_meeting'),
                            membercategory = $(this).attr('data-category'),
                            birth_date = $(this).attr('birth_date'),
                            present = $(this).find('.check-member-checkbox').prop('checked');
                            membersId.push(memberId);

                        list.push({id: memberId, name: memberName, locality: memberLocality, attend_meeting : attendMeeting, category_key: membercategory, birth_date: birth_date, present : present});
                    });

                    list.sort(function (a, b) {
                      if (a.name > b.name) {
                        return 1;
                      }
                      if (a.name < b.name) {
                        return -1;
                      }
                      // a должно быть равным b
                      return 0;
                    });

                    if(mode === 'm' && in_array(id, membersId)){
                        showError("Этот участник уже добавлен в список.");
                    }
                    else if(mode === 'm' && !in_array(id, membersId)){
                        $(modalWindowSelector + ' .search-members').val('').focus();
                        list.push({id: id, name: name, locality: locality, attend_meeting: attendMeeting, category_key: category, birth_date: birth_date});
                        buildMembersList(modalWindowSelector, list);
                        membersCounterMeeting();
                    }
                    else if(mode === 'l'){
                        $.post('/ajax/meeting.php?get_member', {localityId: id})
                        .done(function(data){
                            if(data.members){
                                var doubleMembers = [];

                                for(var i in data.members){
                                    var member = data.members[i];
                                    if(!in_array(member.id, membersId)){
                                        membersId.push(member.id);
                                        list.push({id: member.id, name: member.name, attend_meeting: member.attend_meeting, locality: member.locality, birth_date: member.birth_date});
                                    }
                                    else{
                                        doubleMembers.push(member.name);
                                    }
                                }

                                if(doubleMembers.length > 0){
                                    showError(( doubleMembers.length > 1 ? "Эти " : "Этот ") + ( doubleMembers.length > 1 ? "участники (" : "участник (") + doubleMembers.join(', ') + ") уже "+ ( doubleMembers.length > 1 ? "добавлены" : "добавлен") + " в список.");
                                }
                            }

                            $(modalWindowSelector + ' .members-available').html('');
                            $(modalWindowSelector + ' .search-members').val('').focus();

                            list.sort(function (a, b) {
                                if (a.name > b.name) {
                                    return 1;
                                }
                                if (a.name < b.name) {
                                    return -1;
                                }
                                // a должно быть равным b
                                return 0;
                            });
                            buildMembersList(modalWindowSelector, list);
                        });
                    }
                });
            }
        }

        function confirmToRemoveMemberFromMeetingList(modalWindowSelector, memberId, memberName){
            var confirmationWindow = $("#modalConfirmRemoveMember");
            confirmationWindow.find('.delete-member-from-list').attr('data-id', memberId).attr('data-modal', modalWindowSelector);
            confirmationWindow.find('.modal-body').html('Вы действительно хотите удалить '+ memberName +' из списка?');
            confirmationWindow.modal('show');
        }

        $('.delete-member-from-list').click(function(){
            var memberIdToDelete = $(this).attr('data-id'),
                members = [],
                modalWindowSelector = $(this).attr('data-modal');

            $(modalWindowSelector + " tbody tr").each(function(){
                var id = $(this).attr("data-id"), name = $(this).attr("data-name"), locality = $(this).attr("data-locality");
                if(id !== memberIdToDelete){
                    members.push({id : id, name: name, locality : locality});
                }
            });

            buildMembersList(modalWindowSelector, members);
        });



        $('.meeting-saints-count, .meeting-count-fulltimers, .meeting-count-trainees, .meeting-count-guest').keyup(function(){
            var modal = $("#addEditMeetingModal");

            var count = '';
            var saintsCount = modal.find('.meeting-saints-count').val();
            var countGuests = modal.find('.meeting-count-guest').val();
            var countFulltimers = modal.find('.meeting-count-fulltimers').val();
            var countTrainees = modal.find('.meeting-count-trainees').val();
        });


// ROMANS CODE 5.4
        $('#modalHandleTemplate').on('show', function (){
            setTimeout(membersCounterMeeting, 500);
          });

        $('#addEditMeetingModal').on('hide', function (){
          $('#addEditMeetingModal').find('.actionDate').removeAttr('style', 'background-color: #FCF4F4; border-color:#E08A88;');
            if ($('.addEditMode').length > 0) $('#addEditMeetingModal').removeClass('addEditMode');
          });



        $("#selAddMemberLocalityTemplate, #selAddMemberCategoryTemplate").change (function (){
              loadMembersListFilter ();
          });



        $("#meetingCategory").change(function(){
          if ($("#titleMeetingModal").text() == 'Новое собрание' && $(this).parents("#addEditMeetingModal").is(':visible')){
            if (isFillTemplate === 0) {
              var text = $('#meetingCategory option:selected').text();
              $(".meetingName").val(text);
              update_members_list();
            } else {
              isFillTemplate = 0;
            }
          }
          membersCounterMeeting();
        });

        $("#meetingLocalityModal").change(function(){
          if (isFillTemplate === 0) {
            if($(this).parents("#addEditMeetingModal").is(':visible')){
              //var locality = $(this).val();
              //  chechExtraFields(locality);
                update_members_list(1);
            }
          }
          /*$("#meetingLocalityModal").val() == 001009 ? $(".traineesClass").show() : $(".traineesClass").hide();
          console.log($("#meetingLocalityModal").val());*/
        });
//template modal
        $(".template-locality").change(function(){
            membersCounterMeeting();
        })

        $(".meeting-count-guest").keyup(function(){
            membersCounterMeeting();
        })

        $("#button-people").click (function (){
          $('#modalAddMembersTemplate').modal('show');
        });

        $("#clear-button-people").click (function (){
          $('#modalHandleTemplate').find('.modal-body tbody').html('');
          membersCounterMeeting();
        });
// addEditMeetingModal
        $("#clear-button-people-meeting").click (function (){
          $('#addEditMeetingModal').find('.modal-body tbody').html('');
          membersCounterMeeting();
        });

        $("#button-people-meting").click (function (){
          if ($('.addEditMode').length == 0) $('#addEditMeetingModal').addClass('addEditMode');

          $('#modalAddMembersTemplate').modal('show');
        });

        $("#selectAllMembers").click (function (){
          $('.check-member > td > .check-member-label > input[type=checkbox]').filter(':visible').prop('checked', true);
          membersCounterMeeting();
        });

        $("#unselectAllMembers").click (function (){
          $('.check-member > td > .check-member-label > input[type=checkbox]').prop('checked', false);
          membersCounterMeeting();
        });

        $("#selectAllMembersList").click (function() {
          if ($("#selectAllMembersList").prop('checked')) {
            $('.member-row > td > input[type=checkbox]').filter(':visible').prop('checked', true);
          } else {
            $('.member-row > td > input[type=checkbox]').prop('checked', false);
          }
        });
        function screenSizeMdl() {
          if ($(window).width() < 800) {
            $("#responsible").attr('style', 'float: none');
            $("#performedChkbx").attr('style', 'float: none');
            $("#cancelModalWindow").attr('style', 'margin-right: 60px');
            if ($(window).width() < 769 && $(window).width() > 371) {
              $(".btn-toolbar").attr('style', '');
            }
            if ($(window).width() <= 371) {
              $(".btn-toolbar").attr('style', 'margin-top:15px !important');
            }
            if ($(window).width() >= 769) {
              $(".btn-toolbar").attr('style', 'margin-top:10px !important');
            }
          } else {
            $("#responsible").attr('style', 'float: right');
            $("#performedChkbx").attr('style', 'float: right');
            $("#cancelModalWindow").removeAttr('style');
            $(".btn-toolbar").attr('style', 'margin-top:10px !important');
          }
        }

        screenSizeMdl();

        $(window).resize(function(){
          screenSizeMdl();
        });

  function membersCounterMeeting(template) {
//TEMPLATE
    if ($('#modalHandleTemplate').is(':visible')) {
      var  attendMembersCountDinamicTemp = [], fSMembersCountDinamicTemp = [];
      var modalWindowCountTemplate = $("#modalHandleTemplate");
      modalWindowCountTemplate.find("tbody tr").each(function(){
        attendMembersCountDinamicTemp.push($(this).attr('data-id'));
        if ($(this).attr('data-category_key') == 'FS') {
          fSMembersCountDinamicTemp.push($(this).attr('data-category_key'))
         }
       });
         $('.meeting-count-fulltimers-template').text(fSMembersCountDinamicTemp.length);
         $('.meeting-saints-count-template').val(attendMembersCountDinamicTemp.length);
         $('.meeting-count-template').val(attendMembersCountDinamicTemp.length);
         var f = $(this).find('.meeting-count-trainees-template').text();
         (f.length === 0 && fSMembersCountDinamicTemp.length ===0) ? $('.fulltimersClass-template').hide() : $('.fulltimersClass-template').show();
    }

  // BLANK
  if ($('#addEditMeetingModal').is(':visible') && $('#titleMeetingModal').text()=='Новое собрание') {
          var  attendMembersCountDinamic = [], fSMembersCountDinamic = [];
          var modalWindowCount = $("#addEditMeetingModal");
          modalWindowCount.find("tbody tr").each(function(){
            if ($(this).attr('data-id') >= 990000000) $(this).hide();
            if($(this).find('.check-member-checkbox').prop('checked')){
              attendMembersCountDinamic.push($(this).attr('data-id'));
              if ($(this).attr('data-category_key') == 'FS') fSMembersCountDinamic.push($(this).attr('data-category_key'));
            }
        });
        $('.meeting-count-fulltimers').text(fSMembersCountDinamic.length);
        var a = attendMembersCountDinamic.length > 0 ? attendMembersCountDinamic.length : 0;
        var bb = $('.meeting-count-guest').val();
        $('.meeting-saints-count').val(attendMembersCountDinamic.length);
        var e = Number(a) + Number(bb);
        $('.meeting-count').val(e);
        var d = $('.meeting-count-fulltimers').text();
        if ((d == 0 || d.length === 0) && fSMembersCountDinamic.length ===0) {
          $('.fulltimersClass').hide();
          $('.meeting-count-fulltimers').text('')
        } else {
          $('.fulltimersClass').show()
        }
    }
    if ($('#addEditMeetingModal').is(':visible') && $('#titleMeetingModal').text()=='Карточка собрания') {
      var modalWindowCount = $("#addEditMeetingModal"), attendMembersCountDinamicEditMode =[], fSMembersCountDinamicEditMode=[];
      modalWindowCount.find("tbody tr").each(function(){
        if ($(this).attr('data-id') >= 990000000) $(this).hide();
        if($(this).find('.check-member-checkbox').prop('checked')){
          attendMembersCountDinamicEditMode.push($(this).attr('data-id'));
          if ($(this).attr('data-category_key') == 'FS') fSMembersCountDinamicEditMode.push($(this).attr('data-category_key'));
        }

      });
      $('.meeting-count-fulltimers').text(fSMembersCountDinamicEditMode.length);
      var a = attendMembersCountDinamicEditMode.length > 0 ? attendMembersCountDinamicEditMode.length : 0;
      var bb = $('.meeting-count-guest').val();
      $('.meeting-saints-count').val(attendMembersCountDinamicEditMode.length);
      var e = Number(a) + Number(bb);
      $('.meeting-count').val(e);
      var d = $('.meeting-count-fulltimers').text();
      if ((d == 0 || d.length === 0) && fSMembersCountDinamicEditMode.length ===0) {
        $('.fulltimersClass').hide();
        $('.meeting-count-fulltimers').text('')
      } else {
        $('.fulltimersClass').show()
      }
    }
}

        function update_members_list(changeLocalityBox){
            var meetingType = $("#meetingCategory").val();
            var locality = $("#meetingLocalityModal").val();
            var title = $("#addEditMeetingModal #titleMeetingModal").text().trim()
            var countLocalities = $('#meetingLocalityModal option').size();

            if(countLocalities == 1){
                locality = $("#meetingLocalityModal option:first").val()
                $("#meetingLocalityModal").val(locality).change()
            }

            if((meetingType === 'LT' || meetingType === 'PM') && locality && title === 'Новое собрание'){
              if (confirm("Внимание! Заполнить список святыми из этой местности? Текущий список будет будет заменён")) {
                $.post('/ajax/meeting.php?get_locality_members', {localityId : locality})
                .done(function(data){
                    var members = data.members, membersArr = [];
                    if(members.length > 0){
                        for(var m in members){
                            var member = members[m]['member'];
                            var memberArr = member.split(':');
                            if (member.indexOf("Москва:") != -1 &&
                          !(splitMember[0].indexOf("Москва:0") != -1 || splitMember[0].indexOf("Москва:1") != -1)) {
                              var varTemp = memberArr.splice(3, 1);
                              memberArr[2] = memberArr[2] + varTemp;
                            }
                            membersArr.push({id: memberArr[0], name: memberArr[1], locality: memberArr[2], attend_meeting: memberArr[3], category_key: memberArr[4], birth_date: memberArr[5], present : in_array(memberArr[0], [])});

                        }
                        buildMembersList("#addEditMeetingModal", membersArr);

                    } else{
                        var modalWindow = $("#addEditMeetingModal");
                        modalWindow.find('.members-available').html('');
                        modalWindow.find('tbody').html('');
                    }
                    })
                  }
            } else if (changeLocalityBox != 1 && (meetingType === 'GM' || meetingType === 'CM' || meetingType === 'HM' || meetingType === 'YM') && locality && title === 'Новое собрание') {
              if (confirm("Внимание! Очистить список участников?")) {
                var modalWindow = $("#addEditMeetingModal");
                modalWindow.find('.members-available').html('');
                modalWindow.find('tbody').html('');
              //  $('.meeting-list-count').val(0);
                $('.meeting-saints-count').val(0);
                var meetingLocalityModal = $('#meetingLocalityModal').val();
                //(meetingLocalityModal == '001013' || meetingLocalityModal == '001009') ? $('.meeting-count-fulltimers').val('0') : '';
                // $('#meetingLocalityModal').val() == '001009' ? $('.meeting-count-trainees').val('0') : '';
              }
            } else if (changeLocalityBox == 1 && (meetingType === 'GM' || meetingType === 'CM' || meetingType === 'HM' || meetingType === 'YM') && locality && title === 'Новое собрание') {
              changeLocalityBox = 0;
              var modalWindow = $("#addEditMeetingModal");
              modalWindow.find('.members-available').html('');
              modalWindow.find('tbody').html('');
        //      $('.meeting-list-count').val(0);
              $('.meeting-saints-count').val(0);
              var meetingLocalityModal = $('#meetingLocalityModal').val();
              //(meetingLocalityModal == '001013' || meetingLocalityModal == '001009') ? $('.meeting-count-fulltimers').val('0') : '';
              // $('#meetingLocalityModal').val() == '001009' ? $('.meeting-count-trainees').val('0') : '';
            }
        }

    //ADD MEMBERS TO TAMPLATE
    function rebuildMemberList (members){
        var arr = [], checkFilter = $("#addMemberTableHeader");
        if(members){
            for(var m in members){
                var member = members[m];
                a = 'data-locality="'+member.locality+'"';
                arr.push(
                "<tr data-member_key="+member.id+" data-category_key="+member.category_key+" data-locality_key="+member.locality_key+" data-birth_date="+member.birth_date+" "+a+" data-cell_phone = '"+member.cell_phone+"' data-attend_meeting = "+member.attend_meeting+" id='mr-"+m+"' class='member-row'><td><input type='checkbox' id="+member.id+" class='form-check-input'></td><td><label for="+member.id+" class='form-check-label'>"+ he (member.name) + "</label></td><td>"+
                    he(member.locality) + "</td></tr>");
            }
        }

        if(arr.length > 0){
            arr.length > 1 ? checkFilter.show() : checkFilter.hide();
            $(".membersTable").show();
            $(".membersTable tbody").html(arr.join(""));

            $(".member-row > td > input[type='checkbox']").change (function (){
                if ($(".member-row > td > input[type='checkbox']:checked").length>0) $("#btnDoAddMembers").removeClass ("disabled");
                else $("#btnDoAddMembers").addClass ("disabled");
            });
        }
        else{
            checkFilter.hide();
            $(".membersTable tbody").html("");
            $(".membersTable").hide();
        }
var modalAddMembersTemplate = $("#modalAddMembersTemplate");
        modalAddMembersTemplate.find("tbody tr").each(function(){
          u = $(this).attr('data-member_key');
          if (u[0] == 9 ) {
            $(this).hide();
          }
        });
    }

    function loadMembersListFilter (){
      var locId = $("#selAddMemberLocalityTemplate").val();
      var catId = $("#selAddMemberCategoryTemplate").val();
      //var text = $(".searchMemberToAdd").val().trim().toLowerCase();
      var modalAddMembersTemplate = $("#modalAddMembersTemplate");
      if (locId === '_all_' && catId === '_all_') {
        loadMembersList();

      }
      /*if (text.length > 3) {
          modalAddMembersTemplate.find("tbody tr").each(function(){
        });
      }*/
      if (catId != '_all_' || locId != '_all_') {
        if (catId != '_all_' && locId != '_all_') {
          modalAddMembersTemplate.find("tbody tr").each(function(){
            u = $(this).attr('data-member_key');
            if ($(this).attr('data-category_key') == catId && $(this).attr('data-locality_key') == locId && u[0]!=9) {
              $(this).show();
            } else {
              $(this).hide();
            }
          });
        } else {
          modalAddMembersTemplate.find("tbody tr").each(function(){
            uu = $(this).attr('data-member_key');
            if (locId == '_all_') {
                $(this).show();
                if ($(this).attr('data-category_key') != catId || uu[0]==9) {
                  $(this).hide();
                }
              } else {
                $(this).show();
                if ($(this).attr('data-locality_key') != locId || uu[0]==9) {
                  $(this).hide();
                }
              }
        });
        }
      }
    }

    function loadMembersList (){
        var locId = $("#selAddMemberLocalityTemplate").val();
        var catId = $("#selAddMemberCategoryTemplate").val();
        //var text = $(".searchMemberToAdd").val().trim().toLowerCase();
        var hasAccessToAllLocals = false; // parseInt($('#eventTab-'+event).attr('data-access')) === 1;

        locId = locId && locId !== '_all_' ? locId : null ;
        catId = catId && catId !== '_all_' ? catId : null;
        //text = text && text.length >= 3 ? text : null;
/*
        var arr = [];
        arr.push("<option value='_all_' selected>&lt;все&gt;</option>");

        getLocalities(function(data){
            $("#selAddMemberLocality").html(rebuildLocationsList(data.localities, locId, arr).join(""));
        });
*/
        if(locId || catId || !hasAccessToAllLocals){

            $.post('/ajax/members.php', {
              })
            .done(function(data){
                $(".member-row > td > input[type='checkbox']").attr('checked', false);
                $("#addMemberEventTitle").text ($('#events-list option:selected').text());
                $("#btnDoAddMembers").addClass ("disabled");

                hasAccessToAllLocals && !locId ? $('.searchBlock').show() : $('.searchBlock').hide();
                rebuildMemberList (data.members);
            });
        }
        else{
            $("#btnDoAddMembers").addClass ("disabled");
            $('.searchBlock').show();
            rebuildMemberList ();
        }
    }

    $("#btnDoAddMembersTemplate").click (function (){
      var modalAddMembersTemplate = $("#modalAddMembersTemplate"), arrMembersTemplate=[], u, arrMembersTemplateCheck=[], addEditMeetingModal = $("#addEditMeetingModal"), visitMember=[];
      if ($("#addEditMeetingModal").hasClass('new-visit-create')) {
        $("#modalAddMembersTemplate").find('tbody tr').each(function() {
          if ($(this).find('.form-check-input').prop("checked")) {
              var tempVar = $(this).attr('data-member_key');
              visitMember.push(tempVar);
          }
        })
        if (visitMember.length > 1) {
          for (var i in visitMember) {

              var modal = $("#addEditMeetingModal");

              var visitId = '';
              var date = modal.find('#actionDate').val();
              var locality = modal.find('#visitLocalityModal').val();
              var actionType = $("#actionType").find(':selected').text();
              var note = modal.find('#visitNote').val();
              var responsible = modal.find('#responsible option:selected').val();
              var request = getRequestFromFilters(setFiltersForRequest());
              var performed = modal.find('#performedChkbx').val();
              var admin_key = window.adminId;


              if(!date || !locality || !actionType){
                  showError('Необходимо заполнить все обязательные поля выделенные розовым цветом');
                  return
              }

              var members = visitMember[i];
              var countMembers = 1;
              $.post('/ajax/visits.php?set_visit'+request, {
                  visitId : visitId,
                  date: date,
                  locality: locality,
                  actionType : actionType,
                  responsible: responsible,
                  performed: performed,
                  note: note,
                  members : members,
                  admin: admin_key,
                  countMembers: countMembers
              }).done(function(data){
                  if(data.isDoubleMeeting){
                      showError('Данное собрание является дублирующим и не было сохранено!');
                  }
                  $(".localities-available").html('');
                  $(".localities-added").html('');
                  $(".searchLocality").val('');

                  loadMeetings();
                  $("#addEditMeetingModal").modal('hide');
              });
          }
        } else if (visitMember.length === 0) {
            $("#addEditMeetingModal").modal('hide');
        } else {
          console.log("normal way");
        }
      }
      $("#addEditMeetingModal").hasClass('new-visit-create') ? $("#addEditMeetingModal").removeClass('new-visit-create') : '';
//checking exist list
      addEditMeetingModal.find("tbody tr").each(function(){
         arrMembersTemplateCheck.push($(this).attr('data-id'))
      });

      modalAddMembersTemplate.find("tbody tr").each(function(){
        u = $(this).attr('data-member_key');
        if ($(this).find('.form-check-input').prop("checked") && u[0] == 0 && !in_array(u, arrMembersTemplateCheck)) {
          arrMembersTemplate.push({id: $(this).attr('data-member_key'), category_key: $(this).attr('data-category_key'), locality: $(this).attr('data-locality'), name: $(this).find('label').text(), locality_key: $(this).attr('data-locality_key'), attend_meeting: $(this).attr('data-attend_meeting'), cell_phone: $(this).attr('data-cell_phone'), birth_date: $(this).attr('data-birth_date')});
        }
      });

      if (arrMembersTemplate.length != 0) {
          if ($('.addEditMode').length != 0) {
            buildMembersList('#addEditMeetingModal', arrMembersTemplate, 'add_mode');
          }
      }
      $('#modalAddMembersTemplate').modal('hide');
    });

    $('#modalAddMembersTemplate').on('show', function (e){
        e.stopPropagation();
        $("#selAddMemberLocalityTemplate, #selAddMemberCategoryTemplate").val('_all_');
        //$(".searchMemberToAdd").val('');
        loadMembersList ();
        loadMembersListFilter ();
      });
    });
})();
