$(document).ready(function(){

  loadDashboard();

// START DASHBOARD
    function loadDashboard() {
      $.get('/ajax/statistic.php?get_statistic')
      .done(function(data){
        if(data.statistic){
          console.log(data.statistic);
          buildList(data.statistic);
          filtersList();
        }
      });
    }
    function buildList(list){
      tableRows = [], phoneRows = [];
      for (var i = 0; i < list.length; i++) {
         var item = list[i];

        dataString = 'data-id="'+item.statistic_card_id+'" data-locality_key="'+item.locality_key+'" data-author="'+item.author+'" data-locality_status="'+item.locality_status_id+'" data-archive="'+item.archive+'" data-comment="'+item.comment+'" data-bptz17="'+item.bptz_younger_17+'" data-bptz1725="'+item.bptz_17_25+'" data-bptzAll="'+item.bptz_count+'" data-attended60="'+item.attended_older_60+'" data-attended17="'+item.attended_younger_17+'" data-attended1725="'+item.attended_17_25+'" data-attended25="'+item.attended_older_25+'" data-attendedAll="'+item.attended_count+'" data-meeting_average="'+item.lt_meeting_average+'" data-completed="'+item.status_completed+'" data-periods="'+item.period_start+' - '+item.period_end+'" data-id_statistic="'+item.id_statistic+'"';
        tableRows.push('<tr class="row-statistic" style="cursor: pointer; '+(item.status_completed == '1' ? 'background-color: lightgreen' : '')+'" '+dataString+'>'+
            '<td>'+item.statistic_card_id+'</td>' +
            '<td>'+item.locality_name+'</td>'+
            '<td>'+item.status_name+'</td>'+
            '<td class="bptz_half_year">'+item.bptz_count+'</td>'+
            '<td class="attended_count">'+item.attended_count+'</td>'+
            '<td class="lt_meeting_average">'+item.lt_meeting_average+'</td>'+
            '<td style="text-align: center;">'+(item.status_completed == '1' ? '<i class="fa fa-check" aria-hidden="true"></i>' : '')+'</td></tr>'
        );

        phoneRows.push('<tr class="row-statistic" '+(item.status_completed == '1' ? 'style="background-color: lightgreen"' : '')+' '+dataString+'>'+
          '<td>'+item.statistic_card_id+'</td>' +
          '<td>'+item.locality_name+'</td>'+
          '<td>'+item.lt_meeting_average+'</td></tr>'
        );
      }
      $("#statisticList tbody").html (tableRows.join(''));
      $("#statisticListMbl tbody").html (phoneRows.join(''));

      function statisticBlankFill (id, locality_key, author, locality_status, archive, comment, bptzHalfYear, attendedCount, ltMeetingAverage, bptz17, bptz1725, bptz25, attended17, attended1725, attended25, completed, periods, idStatistic, attended60) {
        var periodsSE = periods.split(' - ');
        $('#addEditStatisticModal').attr('data-author', author);
        $('#addEditStatisticModal').attr('data-archive', archive);
        $('#addEditStatisticModal').attr('data-status_val', completed);
        $('#addEditStatisticModal').attr('data-period_start', periodsSE[0]);
        $('#addEditStatisticModal').attr('data-period_end', periodsSE[1]);
        $('#addEditStatisticModal').attr('data-id_statistic', idStatistic);
        $('#addEditStatisticModal').find('#statisticLocalityModal').val(locality_key);
        $('#addEditStatisticModal').find('#localityStatus').val(locality_status);
        $('#addEditStatisticModal').find('#periodId').text(id);
        $('#addEditStatisticModal').find('#periodDate').text(periods);
        $('#addEditStatisticModal').find('#bptz17').val(bptz17);
        //$('#addEditStatisticModal').find('#bptz17_25').val(bptz1725);
        $('#addEditStatisticModal').find('#attended60').val(attended60);
        $('#addEditStatisticModal').find('#bptzAll').val(bptzHalfYear);
        $('#addEditStatisticModal').find('#attended17').val(attended17);
        $('#addEditStatisticModal').find('#attended17_25').val(attended1725);
        $('#addEditStatisticModal').find('#attended25').val(attended25);
        $('#addEditStatisticModal').find('#attendedAll').val(attendedCount);
        $('#addEditStatisticModal').find('#ltMeetingAverage').val(ltMeetingAverage);
// MBL start
        $('#addEditStatisticModal').find('#bptz17mbl').val(bptz17);
        $('#addEditStatisticModal').find('#attended60mbl').val(attended60);
        $('#addEditStatisticModal').find('#bptzAllmbl').val(bptzHalfYear);
        $('#addEditStatisticModal').find('#attended17mbl').val(attended17);
        $('#addEditStatisticModal').find('#attended17_25mbl').val(attended1725);
        $('#addEditStatisticModal').find('#attended25mbl').val(attended25);
        $('#addEditStatisticModal').find('#attendedAllmbl').val(attendedCount);
        $('#addEditStatisticModal').find('#ltMeetingAveragembl').val(ltMeetingAverage);
// MBL stop
        $('#addEditStatisticModal').find('#comment').val(comment);
        completed == '1' ? $('#addEditStatisticModal').find('#statisticCompleteChkbox').prop('checked', true) : $('#addEditStatisticModal').find('#statisticCompleteChkbox').prop('checked', false);
        if (completed == '1') {
          // Дублирующаяся функция (не 100% соответствие)
          function treeShortNames(fullName) {
            var shortName;
            fullName ? fullName = fullName.split(' ') : '';
            if (fullName) {
              var two = fullName[1] ? fullName[1].slice(0,1) : FALSE;
              var tree = fullName[2] ? fullName[2].slice(0,1) : FALSE;
              if (two) {
                shortName = fullName[0] + ' ' + two+'.';
              }
              if (tree) {
                shortName = shortName +tree+'.';
              }
              return shortName;
            } else {
              return FALSE
            }
          }
          $.get('/ajax/statistic.php?get_member_name', {memberId: author})
          .done(function(data){
            if(data.statistic){
              var item = data.statistic;
              var shortName = treeShortNames(item[0]);
              $('#addEditStatisticModal').find('#adminShortName').text(shortName);
            }
          });
        }
      }

      $(".row-statistic").unbind('click');
      $('.row-statistic').click(function (){
        $('#addEditStatisticModal').modal('show');
        $('#addEditStatisticModal').hasClass('edit') ? '' : $('#addEditStatisticModal').addClass('edit');
        statisticBlankFill($(this).attr('data-id'), $(this).attr('data-locality_key'), $(this).attr('data-author'), $(this).attr('data-locality_status'), $(this).attr('data-archive'), $(this).attr('data-comment'), $(this).attr('data-bptzAll'), $(this).attr('data-attendedAll'), $(this).attr('data-meeting_average'), $(this).attr('data-bptz17'), $(this).attr('data-bptz1725'), $(this).attr('data-bptz25'), $(this).attr('data-attended17'), $(this).attr('data-attended1725'), $(this).attr('data-attended25'), $(this).attr('data-completed'), $(this).attr('data-periods'), $(this).attr('data-id_statistic'), $(this).attr('data-attended60'));
      })
    }
    function filtersList() {
      var periods;
      $('#arhivePeriods').val() ? periods = $('#arhivePeriods').val() : periods = [];
      //periods.indexOf($('#blanksArchive').attr('data-id')) === -1 ? $('.add-statistic').hide() : $('.add-statistic').show();
      $('.meetings-list tbody tr').each(function () {
        if ((($('#selStatisticLocality').val() === $(this).attr('data-locality_key')) || ($('#selStatisticLocality').val() === '_all_')) && (($('#fulfilledBlank').val() === $(this).attr('data-completed')) || ($('#fulfilledBlank').val() === '_all_')) && (periods.indexOf($(this).attr('data-id')) !== -1)){
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    }
    $('#selStatisticLocality, #fulfilledBlank').change(function () {
      filtersList();
    });

    $("a[id|='sort']").click (function (){

        var id = $(this).attr("id");
        var icon = $(this).siblings("i");

        $(".meetings-list a[id|='sort'][id!='"+id+"'] ~ i").attr("class","icon-none");
        icon.attr ("class", icon.hasClass("icon-chevron-down") ? "icon-chevron-up" : "icon-chevron-down");

        if (id == 'sort-city') {
          icon.hasClass("icon-chevron-down") ? sortingStatistic(1) : sortingStatistic(2);
        } else if (id == 'sort-status') {
          icon.hasClass("icon-chevron-down") ? sortingStatistic(3) :sortingStatistic(4);
        } else if (id === 'sort-id') {
          icon.hasClass("icon-chevron-down") ? sortingStatistic(5) : sortingStatistic(6);
        } else if (id === 'sort-bptz_half_year') {
          icon.hasClass("icon-chevron-down") ? sortingStatistic(7) :sortingStatistic(8);
        } else if (id === 'sort-attended') {
          icon.hasClass("icon-chevron-down") ? sortingStatistic(9) :sortingStatistic(10);
        } else if (id === 'sort-count_ltmeeting') {
          icon.hasClass("icon-chevron-down") ? sortingStatistic(11) :sortingStatistic(12);
        } else if (id === 'sort-completed') {
          icon.hasClass("icon-chevron-down") ? sortingStatistic(13) :sortingStatistic(14);
        }
    });

    function sortingStatistic (sortType) {

      var list = [], tableRows = [], phoneRows = [], isLocationAlone = $('#selMeetingLocality option').length == 2 ?  true : false;
      $('#statisticList tbody').find('tr').each(function(){
        var  id = $(this).attr('data-id'),
        locality_key = $(this).attr('data-locality_key'),
        author = $(this).attr('data-author'),
        locality_status = $(this).attr('data-locality_status'),
        archive = $(this).attr('data-archive'),
        comment = $(this).attr('data-comment'),
        bptzHalfYear = $(this).attr('data-bptzAll'),
        attendedCount = $(this).attr('data-attendedAll'),
        ltMeetingAverage = $(this).attr('data-meeting_average'),
        bptz17 = $(this).attr('data-bptz17'),
        bptz1725 = $(this).attr('data-bptz1725'),
        bptz25 = $(this).attr('data-bptz25'),
        attended17 = $(this).attr('data-attended17'),
        attended1725 = $(this).attr('data-attended1725'),
        attended25 = $(this).attr('data-attended25'),
        completed = $(this).attr('data-completed'),
        periods = $(this).attr('data-periods').split(' - '),
        idStatistic = $(this).attr('data-id_statistic'),
        statusName = $(this).find('td:eq(2)').text(),
        localityName = $(this).find('td:eq(1)').text();

        if (id) {
          list.push({archive: archive, attended_younger_17: attended17, attended_17_25: attended1725, attended_older_25: attended25, attended_count: attendedCount, author: author, bptz_younger_17: bptz17, bptz_17_25: bptz1725, bptz_older_25: bptz25, bptz_count: bptzHalfYear, card_comment: '', comment: comment, id_statistic: idStatistic, locality_key: locality_key, locality_name: localityName, locality_status_id: locality_status, lt_meeting_average: ltMeetingAverage, period_start: periods[0], period_end: periods[1], statistic_card_id: id, status_completed: completed, status_name: statusName});
        }
      });

      function sortingFun(type, key, key2) {

        if (type == 1) {
          list.sort(function (a, b) {
            if (eval(key) > eval(key2)) {
              return 1;
            }
            if (eval(key) < eval(key2)) {
              return -1;
            }
            return 0;
          });
        }

        if (type == 2) {
          list.sort(function (a, b) {
            if (eval(key) < eval(key2)) {
              return 1;
            }
            if (eval(key) > eval(key2)) {
              return -1;
            }
            return 0;
          });
        }
      }

      if (sortType == 1) {
        sortingFun(1, 'a.locality_name', 'b.locality_name');
      } else if (sortType == 2) {
        sortingFun(2, 'a.locality_name', 'b.locality_name');
      } else if (sortType == 3) {
        sortingFun(1, 'a.status_name', 'b.status_name');
      } else if (sortType == 4) {
        sortingFun(2, 'a.status_name', 'b.status_name');
      } else if (sortType == 5) {
        sortingFun(1, 'a.statistic_card_id', 'b.statistic_card_id');
      } else if (sortType == 6) {
        sortingFun(2, 'a.statistic_card_id', 'b.statistic_card_id');
      } else if (sortType == 7) {
        sortingFun(1, 'Number(a.bptz_count)', 'Number(b.bptz_count)');
      } else if (sortType == 8) {
        sortingFun(2, 'Number(a.bptz_count)', 'Number(b.bptz_count)');
      } else if (sortType == 9) {
        sortingFun(1, 'Number(a.attended_count)', 'Number(b.attended_count)');
      } else if (sortType == 10) {
        sortingFun(2, 'Number(a.attended_count)', 'Number(b.attended_count)');
      } else if (sortType == 11) {
        sortingFun(1, 'Number(a.lt_meeting_average)', 'Number(b.lt_meeting_average)');
      } else if (sortType == 12) {
        sortingFun(2, 'Number(a.lt_meeting_average)', 'Number(b.lt_meeting_average)');
      } else if (sortType == 13) {
        sortingFun(1, 'a.status_completed', 'b.status_completed');
      } else if (sortType == 14) {
        sortingFun(2, 'a.status_completed', 'b.status_completed');
      }
      buildList(list);
      filtersList();
    }

// STOP DASHBOARD
// START TOOLBAR
  $('.add-statistic').click(function (){
    $('#addEditStatisticModal').modal('show');
    clearBlankFields();
    $('#addEditStatisticModal').hasClass('edit') ? $('#addEditStatisticModal').removeClass('edit') : '';
    var startEnd = $('#blanksArchive').attr('data-period'),
    idPeriod = $('#blanksArchive').attr('data-id');
    $('#periodId').text(idPeriod);
    $('#periodDate').text(startEnd);
    var dateStartEnd = startEnd.split(' - ');
    $('#addEditStatisticModal').attr('data-period_start', dateStartEnd[0]);
    $('#addEditStatisticModal').attr('data-period_end', dateStartEnd[1]);
  })

  $('#addEditStatisticModal').on('hide', function (){
      setTimeout(function () {
        clearBlankFields();
    }, 200);
  })

  $('#arhivePeriods').change(function () {
    filtersList();
  });

  function setPeriodDefault() {
    $('#arhivePeriods option').each(function () {
      $(this).val() === $('#blanksArchive').attr('data-id') ? $('#arhivePeriods').val($('#blanksArchive').attr('data-id')) : '';
    })
  }

  setPeriodDefault();
// STOP TOOL BAR
// START MODAL

  $('.btnDoHandleStatistic').click(function (){
    if ($('#statisticCompleteChkbox').prop('checked') && checkFieldsStatModalInvalid()) {
      // check fields
      checkFieldsStatModalInvalid() ? showError('Заполните следующие поля: ' + checkFieldsStatModalInvalid().join(', ')) : logFileWhriter(this, checkFieldsStatModalInvalid(), showError('Непредвиденная ошибка'));
      // show error message
      return
    }
    if ($('#addEditStatisticModal').hasClass('edit')) {
      setStatistic(true);
    } else {
      setStatistic();
    }
    $('#addEditStatisticModal').modal('hide');
  })

  function checkFieldsStatModalInvalid() {
    // Check checkFieldsStatModal
    var answer = [];
    if ($('#mblModalStatisticsBlank').is(':visible')) {
      $('#addEditStatisticModal .field_mobile_mdl').each(function () {

          console.log('Im here');
          $(this).val() ? '' : answer.push($(this).attr('data-name'));

      });
    } else {
      $('#addEditStatisticModal .field_desktop_mdl').each(function () {

          console.log('Im there');
          $(this).val() ? '' : answer.push($(this).attr('data-name'));

      });
    }

    // return error.
    if (answer[0]) {
      return answer
    } else {
      return false
    }
  }

  // Start Log file
    function logFileWhriter(where, what, msg) {
      console.log(where, what);
    }
  // End Log file

  function setStatistic(doUpdate) {
    //console.log(periodId);
    var idStatistic = $('#addEditStatisticModal').attr('data-id_statistic');
    var locality = $('#statisticLocalityModal').val();
    var localityStatus = $('#localityStatus').val();
    var periodId = $('#periodId').text();
    var periodDate = $('#periodDate').text();
    var isDesktopSttsShow = $('#desctopModalStatisticsBlank').is(':visible');
    var bptz17 = isDesktopSttsShow ? $('#bptz17').val() : $('#bptz17mbl').val();
    //var bptz17_25 = $('#bptz17_25').val();
    var attended60 = isDesktopSttsShow ? $('#attended60').val() : $('#attended60mbl').val();
    var bptzAll = isDesktopSttsShow ? $('#bptzAll').val() : $('#bptzAllmbl').val();
    var attended17 = isDesktopSttsShow ? $('#attended17').val() : $('#attended17mbl').val();
    var attended17_25 = isDesktopSttsShow ? $('#attended17_25').val() : $('#attended17_25mbl').val();
    var attended25 = isDesktopSttsShow ? $('#attended25').val() : $('#attended25mbl').val();
    var attendedAll = isDesktopSttsShow ? $('#attendedAll').val() : $('#attendedAllmbl').val();
    var ltMeetingAverage = isDesktopSttsShow ? $('#ltMeetingAverage').val() : $('#ltMeetingAveragembl').val();
    var comment = $('#comment').val();
    var statisticCompleteChkboxбarchive, archive=0;
    $('#statisticCompleteChkbox').prop('checked') ? statisticCompleteChkbox = 1 : statisticCompleteChkbox = 0;

    if (doUpdate) {
      $.get('/ajax/statistic.php?update_statistic', {locality: locality, locality_status: localityStatus, period_id: periodId, bptz17: bptz17, bptzAll: bptzAll, attended17: attended17, attended17_25: attended17_25, attended25: attended25, attended60: attended60, attendedAll: attendedAll, lt_MeetingAverage: ltMeetingAverage, archive: archive, comment: comment, statisticCompleteChkbox: statisticCompleteChkbox, id_statistic: idStatistic})
      .done(function(data){
        if(data){
          loadDashboard();
          showHint('Изменения сохранены');
        }
      });
    } else {
      $.get('/ajax/statistic.php?set_statistic', {locality: locality, locality_status: localityStatus, period_id: periodId, bptz17: bptz17, bptzAll: bptzAll, attended17: attended17, attended17_25: attended17_25, attended25: attended25, attended60: attended60, attendedAll: attendedAll, lt_MeetingAverage: ltMeetingAverage, archive: archive, comment: comment, statisticCompleteChkbox: statisticCompleteChkbox})
      .done(function(data){
        if(data != 'error_001'){
          loadDashboard();
          showHint('Данные сохранены');
        } else {
          showError('Бланк статистики за данный переиод для этой местности уже существует.');
        }
      });
    }
  }

  function clearBlankFields() {
    $('#addEditStatisticModal').find('input').each(function () {
      if ($(this).attr('id') != 'statisticCompleteChkbox') {
        $(this).val('');
      } else if ($(this).attr('id') === 'statisticCompleteChkbox') {
        $(this).prop('checked', false)
      }
    });
    $('#addEditStatisticModal').find('#localityStatus').val('01');
    $('#addEditStatisticModal').find('#statisticLocalityModal').val(adminLocalityGlb);
    $('#addEditStatisticModal').find('textarea').val('');
    $('#addEditStatisticModal').find('#adminShortName').text('');
  }

  $('.confirmFulfill').click(function (){
    $('#autoFulfillModal').modal('show');
  })

  function statisticСalculation(sttsData, periodStart, periodEnd) {
    var counterAttend17 = 0, counterAttend1725 = 0, counterAttend25 = 0, counterAttend60 = 0, counterAttendAll = 0, bugAttend = 0, counterBptz17 = 0, counterBptz1725 = 0, counterBptz25 = 0, counterBptzAll=0, result =[];
    for (var i = 0; i < sttsData.length; i++) {
      var age = Math.round(sttsData[i].age);
      if (sttsData[i].attend === "1") {
        counterAttendAll++
        if ( age < 17) {
          counterAttend17++
        } else if (age > 60) {
          counterAttend60++
        } else if ((age > 25) && (age <= 60)) {
          counterAttend25++
        } else if ((age >= 17) && (age <= 25)) {
          counterAttend1725++
        } else {
          bugAttend++
        }
      }
      if ((periodEnd >= sttsData[i].baptized) && (sttsData[i].baptized >= periodStart)) {
        counterBptzAll++
        if (age < 17) {
          counterBptz17++
        } else if ((17 <= age) && (age <= 25)) {
          counterBptz1725++
        } else if (age > 25) {
          counterBptz25++
        }
      }
    }
    result.push({counterAttend17: counterAttend17, counterAttend1725: counterAttend1725, counterAttend25: counterAttend25, counterAttend60: counterAttend60, counterAttendAll: counterAttendAll, bugAttend: bugAttend, counterBptz17: counterBptz17, counterBptz1725: counterBptz1725, counterBptz25: counterBptz25, counterBptzAll: counterBptzAll});
    return result
  }

  function fulfillSttsModal(sttsResult) {
    var data = sttsResult[0];
    var bptz17=Number(data.counterBptz17) || 0,
    bptz17_25=Number(data.counterBptz1725) || 0,
    bptz25=Number(data.counterBptz25) || 0,
    bptzAll=Number(data.counterBptzAll) || 0,
    attended17=Number(data.counterAttend17) || 0,
    attended17_25=Number(data.counterAttend1725) || 0,
    attended25=Number(data.counterAttend25) || 0,
    attend60=Number(data.counterAttend60) || 0,
    attendedAll=Number(data.counterAttendAll) || 0;

    $('#addEditStatisticModal').find('#bptz17').val(bptz17);
    $('#addEditStatisticModal').find('#bptz17_25').val(bptz17_25);
    $('#addEditStatisticModal').find('#bptz25').val(bptz25);
    $('#addEditStatisticModal').find('#bptzAll').val(bptzAll);
    $('#addEditStatisticModal').find('#attended17').val(attended17);
    $('#addEditStatisticModal').find('#attended17_25').val(attended17_25);
    $('#addEditStatisticModal').find('#attended25').val(attended25);
    $('#addEditStatisticModal').find('#attended60').val(attend60);
    $('#addEditStatisticModal').find('#attendedAll').val(attendedAll);
    //mblModalStatisticsBlank$('#addEditStatisticModal').find('#bptz17').val(bptz17);
    $('#addEditStatisticModal').find('#bptz17mbl').val(bptz17);
    $('#addEditStatisticModal').find('#bptz17_25mbl').val(bptz17_25);
    $('#addEditStatisticModal').find('#bptz25mbl').val(bptz25);
    $('#addEditStatisticModal').find('#bptzAllmbl').val(bptzAll);
    $('#addEditStatisticModal').find('#attended17mbl').val(attended17);
    $('#addEditStatisticModal').find('#attended17_25mbl').val(attended17_25);
    $('#addEditStatisticModal').find('#attended25mbl').val(attended25);
    $('#addEditStatisticModal').find('#attended60mbl').val(attend60);
    $('#addEditStatisticModal').find('#attendedAllmbl').val(attendedAll);
  }

  $('.btnDoHandleFulfillStatistic').click(function (){
    $('#autoFulfillModal').modal('hide');
    var localityKey = $('#statisticLocalityModal').val();
    $.get('/ajax/statistic.php?get_members_statistic', {locality_key : localityKey})
    .done(function(data){
      if(data.statistic){
        //console.log(data.statistic);
        //console.log(statisticСalculation(data.statistic, $('#addEditStatisticModal').attr('data-period_start'), $('#addEditStatisticModal').attr('data-period_end')));
        fulfillSttsModal(statisticСalculation(data.statistic, $('#addEditStatisticModal').attr('data-period_start'), $('#addEditStatisticModal').attr('data-period_end')));
      }
    });
  })
    $('#btnDoDeleteStatistic').click(function (){
      $('#deleteStatisticBlankConfirm').modal('show');
    });
    $('.btnDoConfirmDeleteStatistic').click(function (){
      var idStatistic = $('#addEditStatisticModal').attr('data-id_statistic');
      $.get('/ajax/statistic.php?delete_members_statistic', {id_statistic : idStatistic})
      .done(function(data){
        $('#deleteStatisticBlankConfirm').modal('hide');
        $('#addEditStatisticModal').modal('hide');
        loadDashboard();
      });
    });
// STOP MODAL
// START global hint close
  $('#globalHint').find('.close-alert').click(function () {
    $("#globalHint").fadeOut();
  });
// STOP global hint close
  $(window).resize(function() {
    resizeScreenMdlStatistics();
  });
  function resizeScreenMdlStatistics() {
    if ($(window).width() >= 500) {
      if ($("#mblModalStatisticsBlank").is(':visible')) {
        $('#addEditStatisticModal').find('#bptz17').val($('#addEditStatisticModal').find('#bptz17mbl').val());
        $('#addEditStatisticModal').find('#bptz17_25').val($('#addEditStatisticModal').find('#bptz17_25mbl').val());
        $('#addEditStatisticModal').find('#bptz25').val($('#addEditStatisticModal').find('#bptz25mbl').val());
        $('#addEditStatisticModal').find('#bptzAll').val($('#addEditStatisticModal').find('#bptzAllmbl').val());
        $('#addEditStatisticModal').find('#attended17').val($('#addEditStatisticModal').find('#attended17mbl').val());
        $('#addEditStatisticModal').find('#attended17_25').val($('#addEditStatisticModal').find('#attended17_25mbl').val());
        $('#addEditStatisticModal').find('#attended25').val($('#addEditStatisticModal').find('#attended25mbl').val());
        $('#addEditStatisticModal').find('#attended60').val($('#addEditStatisticModal').find('#attended60mbl').val());
        $('#addEditStatisticModal').find('#attendedAll').val($('#addEditStatisticModal').find('#attendedAllmbl').val());
        $('#addEditStatisticModal').find('#ltMeetingAverage').val($('#addEditStatisticModal').find('#ltMeetingAveragembl').val());
      }
      $("#mblModalStatisticsBlank").hide();
      $("#desctopModalStatisticsBlank").show();
    } else if ($(window).width() < 500) {
      if ($("#desctopModalStatisticsBlank").is(':visible')) {
        $('#addEditStatisticModal').find('#bptz17mbl').val($('#addEditStatisticModal').find('#bptz17').val());
        $('#addEditStatisticModal').find('#bptz17_25mbl').val($('#addEditStatisticModal').find('#bptz17_25').val());
        $('#addEditStatisticModal').find('#bptz25mbl').val($('#addEditStatisticModal').find('#bptz25').val());
        $('#addEditStatisticModal').find('#bptzAllmbl').val($('#addEditStatisticModal').find('#bptzAll').val());
        $('#addEditStatisticModal').find('#attended17mbl').val($('#addEditStatisticModal').find('#attended17').val());
        $('#addEditStatisticModal').find('#attended17_25mbl').val($('#addEditStatisticModal').find('#attended17_25').val());
        $('#addEditStatisticModal').find('#attended25mbl').val($('#addEditStatisticModal').find('#attended25').val());
        $('#addEditStatisticModal').find('#attended60mbl').val($('#addEditStatisticModal').find('#attended60').val());
        $('#addEditStatisticModal').find('#attendedAllmbl').val($('#addEditStatisticModal').find('#attendedAll').val());
        $('#addEditStatisticModal').find('#ltMeetingAveragembl').val($('#addEditStatisticModal').find('#ltMeetingAverage').val());
      }
      $("#desctopModalStatisticsBlank").hide();
      $("#mblModalStatisticsBlank").show();
    }
  }
  resizeScreenMdlStatistics();
});
