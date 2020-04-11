// JS PRACTICES
// Personal practices

$(document).ready(function(){

  // STOP choise active tab
  if (settingOff) {
    window.location = '/settings';
  }
  !wakeupOn ? wakeupOn = 'style="display: none;"' : '';
  !gospelOn ? gospelOn = 'style="display: none;"' : '';
  // START choise active tab. Show / hide label of tabs
  if ($('#whachTab').is(':visible')) {
    $('#whachTab').addClass('active');
    $('#whach').addClass('in active');
    $('#whachTab').find('a').css('font-weight','bold');

  } else if ($('#pCountTab').is(':visible')) {
    $('#pCountTab').addClass('active');
    $('#pcount').addClass('in active');
    $('#pCountTab').find('a').css('font-weight','bold');
  }

  $('#whachTab').click(function() {
    $(this).find('a').css('font-weight','bold');
    $('#pCountTab').find('a').css('font-weight','normal');
  });

  $('#pCountTab').click(function() {
    $(this).find('a').css('font-weight','bold');
    $('#whachTab').find('a').css('font-weight','normal');
  });

  if ($('#whachTabMbl').is(':visible')) {
    $('#whachTabMbl').addClass('active');
    $('#whachMbl').addClass('in active');
  } else if ($('#pCountTabMbl').is(':visible')) {
    $('#pCountTabMbl').addClass('active');
    $('#pcountMbl').addClass('in active');
  }

  if (!settingOn) {
    $('.tab-content').find('.nav-tabs').hide();
  }

  if ($(window).width()>=769 && $(window).width() < 980) {
    $('#blankTbl').css('width', '287px');
  }

  $(window).resize(function(){
    if ($(window).width()>=769 && !$('#pcount').hasClass('active')) {
      $('#pcount').addClass('in active');
    } else if ($(window).width()<769 && !$('#pcountMbl').hasClass('active')) {
      $('#pcountMbl').addClass('in active');
    } else if ($(window).width()>=769 && $('#pcount').hasClass('active')) {
      if ($(window).width() < 980) {
        $('#blankTbl').css('width', '287px');
      }
    }
  });


// Data for the daily practices blank
  function practicesBlankToday(dataForBlank) {
// DeskTop
  var a,b,c,d,e,f,g,k,l,m;

  dataForBlank[0].m_revival != 0 ? a = dataForBlank[0].m_revival : a = null;
  dataForBlank[0].p_pray != 0 ? b = dataForBlank[0].p_pray : b = null;
  dataForBlank[0].co_pray != 0 ? c = dataForBlank[0].co_pray : c = null;
  dataForBlank[0].r_bible != 0 ? d = dataForBlank[0].r_bible : d = null;
  dataForBlank[0].r_ministry != 0 ? e = dataForBlank[0].r_ministry : e = null;
  dataForBlank[0].evangel != 0 ? f = dataForBlank[0].evangel : f = null;
  dataForBlank[0].flyers != 0 ? g = dataForBlank[0].flyers : g = null;
  dataForBlank[0].contacts != 0 ? k = dataForBlank[0].contacts : k = null;
  dataForBlank[0].saved != 0 ? l = dataForBlank[0].saved : l = null;
  dataForBlank[0].meetings != 0 ? m = dataForBlank[0].meetings : m = null;

    var dayOfWeek = getNameDayOfWeekByDayNumber(dataForBlank[0].date_practic , false);
    var textDate = dataForBlank[0].date_practic +', '+ dayOfWeek;
    $('#dataPractic').text(textDate);
    $('#mrPractic').val(a);
    $('#ppPractic').val(b);
    $('#pcPractic').val(c);
    $('#rbPractic').val(d);
    $('#rmPractic').val(e);
    $('#gsplPractic').val(f);
    $('#flPractic').val(g);
    $('#cntPractic').val(k);
    $('#svdPractic').val(l);
    $('#meetPractic').val(m);
    $('#timeWakeup').val(dataForBlank[0].wakeup);
    $('#timeHangup').val(dataForBlank[0].hangup);
    $('#otherDesk').val(dataForBlank[0].other);

// Mobile
    $('#dataPracticMbl').text(textDate);
    $('#mrPracticMbl').val(a);
    $('#ppPracticMbl').val(b);
    $('#pcPracticMbl').val(c);
    $('#rbPracticMbl').val(d);
    $('#rmPracticMbl').val(e);
    $('#gsplPracticMbl').val(f);
    $('#flPracticMbl').val(g);
    $('#cntPracticMbl').val(k);
    $('#svdPracticMbl').val(l);
    $('#meetPracticMbl').val(m);
    $('#timeWakeupMbl').val(dataForBlank[0].wakeup);
    $('#timeHangupMbl').val(dataForBlank[0].hangup);
    $('#otherMbl').val(dataForBlank[0].other);
  }

  $.get('/ajax/practices.php?get_practices_today')
    .done (function(data) {
      practicesBlankToday(data.practices);
    });

  function practicesList(x) {
    var tableData=[],tableDataMbl=[],m_revival,p_pray,co_pray,r_bible,r_ministry,evangel,flyers,contacts,saved,meetings,dayOfWeek, dayOfWeek2, hangupTime, wakeupTime;
    for (var i = 0; i < x.length; i++) {
      dayOfWeek = getNameDayOfWeekByDayNumber(x[i].date_practic , false);
      //x[i].date_practic ? dayOfWeek = dayOfWeek.getDay() : '';
      x[i].m_revival != 0 ? m_revival = x[i].m_revival : m_revival = '-';
      x[i].p_pray != 0 ? p_pray = x[i].p_pray : p_pray = '-';
      x[i].co_pray != 0 ? co_pray = x[i].co_pray : co_pray = '-';
      x[i].r_bible != 0 ? r_bible = x[i].r_bible : r_bible = '-';
      x[i].r_ministry != 0 ? r_ministry = x[i].r_ministry: r_ministry = '-';
      x[i].evangel != 0 ? evangel = x[i].evangel : evangel = '-';
      x[i].flyers != 0 ? flyers = x[i].flyers : flyers = '-';
      x[i].contacts != 0 ? contacts = x[i].contacts : contacts = '-';
      x[i].saved != 0 ? saved = x[i].saved : saved = '-';
      x[i].meetings != 0 ? meetings = x[i].meetings : meetings = '-';
      x[i].wakeup ? wakeupTime = x[i].wakeup : wakeupTime = '-';
      x[i].hangup ? hangupTime = x[i].hangup : hangupTime = '-';
      wakeupTime.length > 5 ? wakeupTime = wakeupTime.substr(0, wakeupTime.length - 3) : '';
      hangupTime.length > 5 ? hangupTime = hangupTime.substr(0, hangupTime.length - 3) : '';
      tableData.push('<tr class="practices_str cursor-pointer" data-other="'+x[i].other+'" data-weekday="'+dayOfWeek+'" data-date="'+x[i].date_practic+'"><td >'+x[i].date_practic+' <br><span class="example" style="margin-left: 0">'+dayOfWeek+'</span></td><td '+wakeupOn+'>'+wakeupTime+'</td><td>'+m_revival+'</td><td>'+p_pray+'</td><td>'+co_pray+'</td><td>'+r_bible+'</td><td>'+r_ministry+'</td><td '+gospelOn+'>'+evangel+'</td><td '+gospelOn+'>'+flyers+'</td><td '+gospelOn+'>'+contacts+'</td><td '+gospelOn+'>'+saved+'</td><td '+gospelOn+'>'+meetings+'</td><td '+wakeupOn+'>'+hangupTime+'</td></tr>');
      tableDataMbl.push('<div class="practices_strMbl" data-other="'+x[i].other+'" data-weekday="'+dayOfWeek+'" data-date="'+x[i].date_practic+'" data-uo="'+x[i].m_revival+'" data-lm="'+x[i].p_pray+'" data-mt="'+x[i].co_pray+'" data-chb="'+x[i].r_bible+'" data-chs="'+x[i].r_ministry+'" data-bl="'+x[i].evangel+'" data-l="'+x[i].flyers+'" data-k="'+x[i].contacts+'" data-s="'+x[i].saved+'" data-v="'+x[i].meetings+'" data-wakeup="'+wakeupTime+'" data-hangup="'+hangupTime+'" ><strong>'+x[i].date_practic+' '+dayOfWeek+'</strong><span '+wakeupOn+'>. Подъём: '+wakeupTime+'</span><span>, УО: '+x[i].m_revival+'</span><span>, ЛМ: '+x[i].p_pray+'</span><span>, МТ: '+x[i].co_pray+'</span><span>, ЧБ: '+x[i].r_bible+'</span><span>, ЧС: '+x[i].r_ministry+'</span><span '+gospelOn+'>, БЛ: '+x[i].evangel+'</span><span '+gospelOn+'>, Л: '+x[i].flyers+'</span><span '+gospelOn+'>, К: '+x[i].contacts+'</span><span '+gospelOn+'>, С: '+x[i].saved+'</span><span '+gospelOn+'>, В: '+x[i].meetings+'</span><span '+wakeupOn+'>, Отбой: '+hangupTime+'.</span></div><hr style="margin: 10px 0;">');
    }
     $('#practicesListPersonal tbody').html(tableData);
     $('#practicesListPersonalMbl').html(tableDataMbl);

     $('.practices_str').click(function() {
// dates compare
       var datedateTmp = Date.parse ($(this).attr('data-date'));
       var datedate = new Date(datedateTmp);
       var curdate = new Date();

       if (!$('.cd-panel').hasClass('cd-panel--is-visible')) {
         $('.cd-panel').addClass('cd-panel--is-visible');
       }
       if (!((datedate.getDate() === curdate.getDate()) && (datedate.getFullYear() === curdate.getFullYear()) && (datedate.getMonth() === curdate.getMonth()))) {
         $('#safePracticesToday').attr('disabled',true);
       } else {
         $('#safePracticesToday').attr('disabled',false);
       }
       var textDate = $(this).find('td:nth-child(1)').text();
       $('#dataPractic').text(textDate);
       $('#mrPractic').val($(this).find('td:nth-child(3)').text());
       $('#ppPractic').val($(this).find('td:nth-child(4)').text());
       $('#pcPractic').val($(this).find('td:nth-child(5)').text());
       $('#rbPractic').val($(this).find('td:nth-child(6)').text());
       $('#rmPractic').val($(this).find('td:nth-child(7)').text());
       $('#gsplPractic').val($(this).find('td:nth-child(8)').text());
       $('#flPractic').val($(this).find('td:nth-child(9)').text());
       $('#cntPractic').val($(this).find('td:nth-child(10)').text());
       $('#svdPractic').val($(this).find('td:nth-child(11)').text());
       $('#meetPractic').val($(this).find('td:nth-child(12)').text());
       $('#timeWakeup').val($(this).find('td:nth-child(2)').text());
       $('#timeHangup').val($(this).find('td:nth-child(13)').text());
       $('#otherDesk').val($(this).attr('data-other'));

     });
     // Mobile fulfil blank
     $('.practices_strMbl').click(function() {
       var datedateTmpMbl = Date.parse ($(this).attr('data-date'));
       var datedateMbl = new Date(datedateTmpMbl);
       var curdateMbl = new Date();

       if (!$('.cd-panelMbl').hasClass('cd-panel--is-visibleMbl')) {
         $('.cd-panelMbl').addClass('cd-panel--is-visibleMbl');
       }

       if (!((datedateMbl.getDate() === curdateMbl.getDate()) && (datedateMbl.getFullYear() === curdateMbl.getFullYear()) && (datedateMbl.getMonth() === curdateMbl.getMonth()))) {
         $('#safePracticesTodayMbl').attr('disabled',true);
       } else {
         $('#safePracticesTodayMbl').attr('disabled',false);
       }
       var textDateMbl = $(this).attr('data-date') + ', ' + $(this).attr('data-weekday');

              $('#dataPracticMbl').text(textDateMbl);
              $('#mrPracticMbl').val($(this).attr('data-uo'));
              $('#ppPracticMbl').val($(this).attr('data-lm'));
              $('#pcPracticMbl').val($(this).attr('data-mt'));
              $('#rbPracticMbl').val($(this).attr('data-chb'));
              $('#rmPracticMbl').val($(this).attr('data-chs'));
              $('#gsplPracticMbl').val($(this).attr('data-bl'));
              $('#flPracticMbl').val($(this).attr('data-l'));
              $('#cntPracticMbl').val($(this).attr('data-k'));
              $('#svdPracticMbl').val($(this).attr('data-s'));
              $('#meetPracticMbl').val($(this).attr('data-v'));
              $('#timeWakeupMbl').val($(this).attr('data-wakeup'));
              $('#timeHangupMbl').val($(this).attr('data-hangup'));
              $('#otherMbl').val($(this).attr('data-other'));
     });
  }

  function practicesListupdate() {
    $.get('/ajax/practices.php?get_practices')
      .done (function(data) {
        practicesList(data.practices);
      });
  }
  practicesListupdate();
//UPDATE Daily practices

  $('#safePracticesToday').click(function() {
    var dataBlank = {};

    dataBlank.mr = $('#mrPractic').val();
    dataBlank.pp = $('#ppPractic').val();
    dataBlank.pc = $('#pcPractic').val();
    dataBlank.rb = $('#rbPractic').val();
    dataBlank.rm = $('#rmPractic').val();
    dataBlank.gspl = $('#gsplPractic').val();
    dataBlank.fl = $('#flPractic').val();
    dataBlank.cnt = $('#cntPractic').val();
    dataBlank.svd = $('#svdPractic').val();
    dataBlank.meet = $('#meetPractic').val();
    dataBlank.wake = $('#timeWakeup').val();
    dataBlank.hang = $('#timeHangup').val();
    dataBlank.oth = $('#otherDesk').val();

    $.get('/ajax/practices.php?update_practices_today',{user_data: dataBlank})
      .done (function(data) {
        practicesListupdate();
      });
  });
  $('#safePracticesTodayMbl').click(function() {
    var dataBlank = {};

    dataBlank.mr = $('#mrPracticMbl').val();
    dataBlank.pp = $('#ppPracticMbl').val();
    dataBlank.pc = $('#pcPracticMbl').val();
    dataBlank.rb = $('#rbPracticMbl').val();
    dataBlank.rm = $('#rmPracticMbl').val();
    dataBlank.gspl = $('#gsplPracticMbl').val();
    dataBlank.fl = $('#flPracticMbl').val();
    dataBlank.cnt = $('#cntPracticMbl').val();
    dataBlank.svd = $('#svdPracticMbl').val();
    dataBlank.meet = $('#meetPracticMbl').val();
    dataBlank.wake = $('#timeWakeupMbl').val();
    dataBlank.hang = $('#timeHangupMbl').val();
    dataBlank.oth = $('#otherMbl').val();

    $.get('/ajax/practices.php?update_practices_today',{user_data: dataBlank})
      .done (function(data) {
        practicesListupdate();
      });
  });
// START SLIDE SIDE PANEL
  $('.cd-panel__close').click(function() {
    $('.cd-panel').removeClass('cd-panel--is-visible');
  });

  $('#cd-panel__close').click(function() {
    $('.cd-panel').removeClass('cd-panel--is-visible');
  });

  $('.cd-panel__closeMbl').click(function() {
    $('.cd-panelMbl').removeClass('cd-panel--is-visibleMbl');
  });

  $('#cd-panel__closeMbl').click(function() {
    $('.cd-panelMbl').removeClass('cd-panel--is-visibleMbl');
  });

// STOP SLIDE SIDE PANEL
// Serviceones watch to the practices
  function practicesListServiceones(x) {
// ВОзможно местности и служащих лучше брать мз объектов JS чем из базы если есть смысл и какая то экономия в этом
// Создать массив ключ местности название местности, и в качестве ключа подставлять переданные данные и так же со служащими ибо их ограниченное количество. Данные можно обновлять после загрузки страници или после определённых операций.
    console.log(x);
    var tableDataser=[], tableDataMblser=[], m_revivalser, p_prayser, co_prayser, r_bibleser, r_ministryser, evangelser, flyersser, contactsser, savedser, meetingsser, dayOfWeekser, hangupTimeser, wakeupTimeser, serving_one;
    for (var i = 0; i < x.length; i++) {
      if (adminLocalityGlb !== '001214') {
        if (window.adminId !== '000005716' && window.adminId !== '000001679') {
          return
        }
      }
      var shortNameMem = twoNames2(x[i].name);
      x[i].m_revival != 0 ? m_revivalser = x[i].m_revival : m_revivalser = '-';
      x[i].p_pray != 0 ? p_prayser = x[i].p_pray : p_prayser = '-';
      x[i].co_pray != 0 ? co_prayser = x[i].co_pray : co_prayser = '-';
      x[i].r_bible != 0 ? r_bibleser = x[i].r_bible : r_bibleser = '-';
      x[i].r_ministry != 0 ? r_ministryser = x[i].r_ministry: r_ministryser = '-';
      x[i].evangel != 0 ? evangelser = x[i].evangel : evangelser = '-';
      x[i].flyers != 0 ? flyersser = x[i].flyers : flyersser = '-';
      x[i].contacts != 0 ? contactsser = x[i].contacts : contactsser = '-';
      x[i].saved != 0 ? savedser = x[i].saved : savedser = '-';
      x[i].meetings != 0 ? meetingsser = x[i].meetings : meetingsser = '-';
      x[i].wakeup ? wakeupTimeser = x[i].wakeup : wakeupTimeser = '-';
      x[i].hangup ? hangupTimeser = x[i].hangup : hangupTimeser = '-';
      x[i].serving ? serving_one = x[i].serving : serving_one = '_none_';
      var serviceOneName = '-';
      var serviceOnesAll = data_page.serviceones;
      for (var ii in serviceOnesAll) {
        if (ii === x[i].serving) {
          serviceOneName = serviceOnesAll[ii];
        }
      }
      wakeupTimeser.length > 5 ? wakeupTimeser = wakeupTimeser.substr(0, wakeupTimeser.length - 3) : '';
      hangupTimeser.length > 5 ? hangupTimeser = hangupTimeser.substr(0, hangupTimeser.length - 3) : '';
      dayOfWeekser = getNameDayOfWeekByDayNumber(x[i].date_practic , false);
      tableDataser.push('<tr class="practices_so_str cursor-pointer" data-id="'+x[i].id+'" data-locality="'+x[i].locality_key+'" data-other="'+x[i].other+'" data-weekday="'+dayOfWeekser+'" data-date="'+x[i].date_practic+'" data-serviceone="'+serving_one+'"><td >'+shortNameMem+'</td><td>'+m_revivalser+'</td><td>'+p_prayser+'</td><td>'+co_prayser+'</td><td>'+r_bibleser+'</td><td>'+r_ministryser+'</td><td>'+evangelser+'</td><td>'+x[i].loc_name+'</td><td>'+serviceOneName+'</td></tr>');
    }
    $('#listPracticesForObserve tbody').html(tableDataser);
  }

  function practicesListServiceonesUpdate() {
    $.get('/ajax/practices.php?get_practices_for_admin')
      .done (function(data) {
        practicesListServiceones(data.practices);
      });
  }
  practicesListServiceonesUpdate();
// DUBLICATE FUNCTION VISITS.JS
  function twoNames2(fullName) {
    var shortName;
    fullName ? fullName = fullName.split(' ') : '';
    if (fullName) shortName = fullName[0] + ' ' + fullName[1];
    return shortName;
  }

  function filtersStrings() {
    //$('tbody .practices_so_str').
  }

  $('#servingCombo').change(function () {
    if ($(this).val() === '_all_' || $(this).val() === '') {

      $('tbody .practices_so_str').show();
    } else {
      $('tbody .practices_so_str').each(function() {
        $(this).attr('data-serviceone') === $('#servingCombo').val() ? $(this).show() : $(this).hide();
      });
    }
  })

  if (!$('.cd-panel').hasClass('cd-panel--is-visible') && $('#pcount').is(':visible')) {
    $('.cd-panel').addClass('cd-panel--is-visible');
  } else if ($('.cd-panel').hasClass('cd-panel--is-visible') && !$('#pcount').is(':visible')) {
    $('.cd-panel').removeClass('cd-panel--is-visible');
  }

/*
  if (!$('.cd-panel').hasClass('cd-panel--is-visibleMbl') && $('#listPracticesForObserve').is(':visible')) {
    $('.cd-panel').addClass('cd-panel--is-visibleMbl');
  } else if ($('.cd-panel').hasClass('cd-panel--is-visible') && !$('#pcount').is(':visible')) {
    $('.cd-panel').removeClass('cd-panel--is-visibleMbl');
  }
*/
});
