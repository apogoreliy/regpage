$('#modalEditMember').on('show',  function() {
  setTimeout(function () {
    var form = $('#modalEditMember');
    var a = parseDDMM (form.find(".emArrDate").val());
    var b = parseDDMM (form.find(".emDepDate").val());
      if(!$('.emArrDate').parent().hasClass('error') && !a){
        $('.emArrDate').parent().addClass('error');
      };
      if(!$('.emDepDate').parent().hasClass('error') && !b){
        $('.emDepDate').parent().addClass('error');
    };
}, 1000);
  handleFieldsByAdminRole(adminRole, $('.event-row.theActiveEvent').attr('data-private'), $('.event-row.theActiveEvent').attr('data-regstate_key'));
});
$('#modalEditMember').on('hide',  function() {
  setTimeout(function () {
    if (!($('#modalEditMember').find('.parking').is(':visible'))) {
      $('#modalEditMember').find('.emAvtomobileNumber').val() ? $('#modalEditMember').find('.emAvtomobileNumber').val(''):'';
      $('#modalEditMember').find('.emAvtomobile').val() ? $('#modalEditMember').find('.emAvtomobile').val(''):'';
      $('#modalEditMember').find('.emParking').val() != '_none_' ? $('#modalEditMember').find('.emParking').val(0) : '';
    }
  }, 500);
});
function showEmptyForm (eventId){
    window.currentEventId = eventId;

    $.getJSON('/ajax/guest.php', { eventId: eventId })
    .done (function(data){
        window.currentEventName=data.info.event_name;
        fillEditMember ('', data.info);
        $("#modalEditMember").modal('show');
    });
}

function showSuccessMessage (text, link){
    $("#regSuccessTitle").text (window.currentEventName);
    $("#regSuccessText").html (text);
    $("#regSuccessLink").html (link);
    if (link) $("#regSuccessNotes").show (); else $("#regSuccessNotes").hide ();
    $("#modalEditMember").addClass('hide').modal('hide');
    $("#modalRegSuccess").modal('show');
}
// START add event modal fields set

$('.notRequired').click(function () {
  $(this).parent().find('div').is(':visible') ? $(this).parent().find('div').hide() : $(this).parent().find('div').show();
  var a = $(this).text(), b;
  if (a[0] == '-') {
    a = a.substring(1);
    b = '+' + a;
    $(this).text(b)
  } else if (a[0] == '+') {
    a = a.substring(1);
    b = '-' + a;
    $(this).text(b)
  }
});

function checkCurrencyAndContribFields() {
  if ($('.event-currency-modal').val() === '_none_') {
    $('.event-contrib-modal').attr('disabled', true);
    $('.event-contrib-modal').val('');
  } else {
    $('.event-contrib-modal').attr('disabled', false);
  }
}
checkCurrencyAndContribFields();
$('.event-currency-modal').change(function () {
  checkCurrencyAndContribFields();
});

$('#modalAddEditEvent').on('show', function () {
  setTimeout(function () {
  $('.notRequired').each(function () {
    var x = $(this).parent().find('input').val() ? $(this).parent().find('input').val() : 0;
    var y = $(this).parent().find('select').val() ? $(this).parent().find('select').val() : 0;
    var xLength, a = $(this).text(), b;
    x.length ? xLength = x.length : xLength = 0;
    if ((y == 0 || y == '_none_') && (x == 0 || xLength ==0)) {
      $(this).parent().find('div').hide();
      if (a[0] == '-') {
        a = a.substring(1);
        b = '+' + a;
        $(this).text(b)
      }
    } else {
      $(this).parent().find('div').show();
      if (a[0] == '+') {
       a = a.substring(1);
       b = '-' + a;
       $(this).text(b)
     }
    }
  })
  }, 500);
});

// STOP add event modal fields set

// START DENY FOR REGISTRATION ON PRIVATE
function handleFieldsByAdminRole(adminRole, isEventPrivate, regstate){
    $("#forAdminRegNotice").text('');
    if((adminRole == 1 || adminRole == 0) && isEventPrivate == 1){
        if (regstate == 'null') {
          $.getJSON('/ajax/guest.php?msg_privat')
          .done (function(data){
              $("#forAdminRegNotice").text(data);
          });
        }
    }
    else{
        if (!regstate || regstate=='05'){
        }
        else{
        }
    }
}
// STOP DENY FOR REGISTRATION ON PRIVATE

// MESSAGE TO TEAMS OR SITE ADMIN
$('.send-message-support-phone').click(function(e) {
  if (window.location.pathname === '/index') {
    e.preventDefault();
    e.stopPropagation();
    $('#choiseHelpPoint').modal('show');
    var html, teamEmail;
    $('.list-events .event-row').each(function() {
      html = '<input type="button" id="" class="btn btn-primary btn_event_id" value="Вопрос о мероприятии - '+$(this).attr("data-name")+'" data-id_event="'+$(this).attr("data-id")+'" style="margin-bottom: 15px;"><br>';
    });
    $('#listBtnsEvents').html(html);
    $('.btn_event_id').click(function() {
      window.currentEventId = $(this).attr('data-id_event');

      $('#choiseHelpPoint').modal('hide');
      $('#modalEventSendMsg').modal('show');
    /* get.php get_team_email
      $.ajax({type: "POST", url: "/ajax/get.php?get_team_email", data: { eventId: email}})
      .done (function(data) {
        console.log(data);
        teamEmail = data.email;

      });
    */
    });
  }
});

$('#questionAboutWebsite').click(function() {
  $('#choiseHelpPoint').modal('hide');
  $('#messageAdmins').modal('show');
});
