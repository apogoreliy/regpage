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

  function getSatusStatistics() {
    $.get('panelsource/panelAjax.php?get_statistics_status', {})
    .done(function(data){
      var count = [];
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
      }

      var html = '<tr><td style="text-align: right;">'+count['Всего']+'</td><td style="text-align: right;">'+count['Вработе']+'</td><td style="text-align: right;">'+count['Недозвон']+'</td><td style="text-align: right;">'+count['Ошибка']+'</td><td style="text-align: right;">'+count['Отказ']+'</td><td style="text-align: right;">'+count['Заказ']+'</td><td style="text-align: right;">'+count['Продолжение']+'</td><td style="text-align: right;">'+count['Завершение']+'</td><td style="text-align: right;">'+count['Безстатуса']+'</td></tr>';
      $('#listStatStatistics').html(html);
    });
  }
  $('#statusesStatisticsBtn').click(function () {
    getSatusStatistics();
  });

  //Print element
    $('#printStatusesStatistics').click(function () {
      function printElem(elem){
        popup($(elem).html());
      }

      function popup(data){
        var mywindow = window.open('', 'Статистика', 'height=400,width=600');
        mywindow.document.write('<html><head><title>Октябрь</title>');
        mywindow.document.write('</head><body > <style>th {border-bottom: 1px solid black; text-align: center; border-collapse: collapse;} table, td {border-bottom: 1px solid black; text-align: right; border-collapse: collapse;}</style>');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
      }
      printElem('#tableStatStatisticsPrint');
    });
  //Print element

// ready page stop here
});
