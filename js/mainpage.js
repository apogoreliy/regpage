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
