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

});
