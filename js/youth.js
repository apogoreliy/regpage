renewComboLists('.members-lists-combo');
$('#modalEditMember').on('show', function() {
  setTimeout(function () {
    showBlankEvents();
  }, 50);
  setTimeout(function () {
    $('.emLocality').hide();
  }, 10);
  if (globalSingleCity && $('#modalEditMember').find('#btnDoSaveMember').hasClass('create')) {
    $('.emLocality option').each(function () {
        $(this).val() === '_none_' ? '' : $('.emLocality').val($(this).val());
    });
  }
});
