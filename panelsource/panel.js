$(document).ready(function(){
  var sessionsGlobal = [];
  var tormozzz = [];
/*
  $('#copySessions').click(function() {
    if (window.confirm("Do you really want to copy sessions?")) {
      $.when(getSessionsAdmins()).then(eachSession());
    }
  });

  function copySessionsAdmins(member,session) {
    $.post('panelsource/panelAjax.php?copy_sessions', {member: member, session: session})
    .done(function(data){
    });
  }

  function eachSession() {
    $(sessionsGlobal).each(function(i) {
      copySessionsAdmins(sessionsGlobal[i].member_key, sessionsGlobal[i].session);
      if (window.confirm("Do you really want to copy session?")) {
        console.log('Ready', sessionsGlobal[i].member_key,' ', sessionsGlobal[i].session);
      } else {
        console.log('Failed', sessionsGlobal[i].member_key,' ', sessionsGlobal[i].session);
      }
    });
  }

  function getSessionsAdmins() {
    $.post('panelsource/panelAjax.php?get_sessions', {})
    .done(function(data){
      sessionsGlobal = data.sessions;
    });
  }
  */
  $('#onPracticesForStudentsPVOM').click(function() {
    if (window.confirm("Включить практики всем обучающимся?")) {
      $.get('panelsource/panelAjax.php?set_practices_pvom', {})
      .done(function(data){
        $('#noticeForAddPractices').text(data.result);
      })
    }
  });

  function getSatusStatistics(from, to) {
    $.get('panelsource/panelAjax.php?get_statistics_status', {from: from, to: to})
    .done(function(data){
      var count = [], unique_id_contacts_arr = [], unique_id_contacts = 0, statistics_crush = false;
      count['Всего'] = 0;
      count['Недозвон'] = 0;
      count['Ошибка'] = 0;
      count['Отказ'] = 0;
      count['Заказ'] = 0;
      count['Продолжение'] = 0;
      count['Завершение'] = 0;
      count['Вработе'] = 0;
      count['Безстатуса'] = 0;

      var array = data.result;
      for (var i = 0; i < array.length; i++) {
          count['Всего']++;
        if (array[i][1] === '1') {
          count['Недозвон']++;
        } else if (array[i][1] === '2') {
          count['Ошибка']++;
        } else if (array[i][1] === '3') {
          count['Отказ']++;
        } else if (array[i][1] === '4') {
          count['Заказ']++;
        } else if (array[i][1] === '5') {
          count['Продолжение']++;
        } else if (array[i][1] === '6') {
          count['Завершение']++;
        } else if (array[i][1] === '7') {
          count['Вработе']++;
        } else {
          count['Безстатуса']++;
        }

        if (unique_id_contacts_arr.indexOf(array[i][2]) === -1 && array[i][2]) {
          unique_id_contacts_arr.push(array[i][2]);
          unique_id_contacts++;
        }
        if (!array[i][2]) {
          statistics_crush = true;
        }
      }
      if (statistics_crush) {
        console.log(unique_id_contacts);
        unique_id_contacts = 'Не возможно посчитать.';
        //console.log('Ошибка 1.');
      }

      var html = '<tr><td>Обработано контактов</td><td><strong>'+unique_id_contacts+'</strong></td></tr><tr><td>Всего контактов</td><td>'+count['Всего']+'</td></tr><tr><td>Контактов в работе</td><td>'+count['Вработе']+'</td></tr><tr><td>Недозвон</td><td>'+count['Недозвон']+'</td></tr><tr><td>Ошибка</td><td>'+count['Ошибка']+'</td></tr><tr><td>Отказ</td><td>'+count['Отказ']+'</td></tr><tr><td>Заказ</td><td>'+count['Заказ']+'</td></tr><tr><td>Продолжение</td><td>'+count['Продолжение']+'</td></tr><tr><td>Завершение</td><td>'+count['Завершение']+'</td></tr><tr><td>Без статуса</td><td>'+count['Безстатуса']+'</td></tr>';
      $('#listStatStatistics').html(html);
    });
  }
  $('#statusesStatisticsBtn').click(function (e) {
    if (!$('#statusesStatisticsSelect').val()) {
      e.stopPropagation();
      $('#InfoStatisticStatusesContainer').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button> <strong>Ошибка!</strong> Выберите месяц из списка.</div>');
    } else {
      var x = $('#statusesStatisticsSelect').val();
      x = x.split('_')
      getSatusStatistics(x[0], x[1]);
    }
  });

  //Print element
    $('#printStatusesStatistics').click(function () {
      function printElem(elem){
        popup($(elem).html());
      }

      function popup(data){
        var mywindow = window.open('', 'Статистика', 'height=400,width=600');
        mywindow.document.write('<html><head><title>'+$('#statusesStatisticsSelect option:selected').text()+'</title>');
        mywindow.document.write('</head><body > <style>th {border-bottom: 1px solid black; text-align: left; border-collapse: collapse;} table, td {border-bottom: 1px solid black; text-align: left; border-collapse: collapse;}</style>');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
      }
      printElem('#tableStatStatisticsPrint');
    });
  //Print element
  $('#dltSameStrOfLog').click(function () {
    fetch('panelsource/panelAjax.php?dlt_same_logstr')
  });

  $('#dlt99LogStr').click(function () {
    fetch('panelsource/panelAjax.php?dlt_99_logstr');
  });

  $('#dltDvlpLogStr').click(function () {
    fetch('panelsource/panelAjax.php?dlt_dvlp_logstr');
  });
// ready page stop here
});
