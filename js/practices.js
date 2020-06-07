// JS PRACTICES
// Personal practices

$(document).ready(function(){

  // STOP choise active tab
  if (settingOff) {
    window.location = '/settings';
  }
  !wakeupOn ? wakeupOn = 'style="display: none;"' : wakeupOn = '';
  !gospelOn ? gospelOn = 'style="display: none;"' : gospelOn = '';
  !globalLocalityOn ? globalLocalityOn = 'style="display: none;"' : globalLocalityOn = '';
  // START choise active tab. Show / hide label of tabs
  // START DeskTop

    if ($('#whachTab').hasClass('active') || $('#pCountTab').hasClass('active')) {
      if ($('#whachTab').hasClass('active')) {
        $('#whachTab').find('a').css('font-weight','bold');
        $('#pCountTab').find('a').css('font-weight','normal');
        if ($('#pCountTab').hasClass('active')) {
          $('#pCountTab').removeClass('active')
        }
        if ($('#pcount').hasClass('active')) {
          $('#pcount').removeClass('active')
          $('#pcount').removeClass('in');
        }
        if (!$('#whach').hasClass('active')) {
          $('#whach').addClass('in active');
        }
      } else {
        $('#pCountTab').find('a').css('font-weight','bold');
        $('#whachTab').find('a').css('font-weight','normal');
        if ($('#whach').hasClass('active')) {
          $('#whach').removeClass('active')
          $('#whach').removeClass('in');
        }
        if (!$('#pcount').hasClass('active')) {
          $('#pcount').addClass('in active')
        }
      }
    } else if (data_page.option_practices_watch) {
      $('#whachTab').addClass('active')
      $('#whachTab').find('a').css('font-weight','bold');
      $('#pCountTab').find('a').css('font-weight','normal');
      if ($('#pcount').hasClass('active')) {
        $('#pcount').removeClass('active')
        $('#pcount').removeClass('in');
      }
      if (!$('#whach').hasClass('active')) {
        $('#whach').addClass('in active');
      }
    } else {
      $('#pCountTab').addClass('active')
      $('#pCountTab').find('a').css('font-weight','bold');
      $('#whachTab').find('a').css('font-weight','normal');
      if ($('#whach').hasClass('active')) {
        $('#whach').removeClass('active')
        $('#whach').removeClass('in');
      }
      if (!$('#pcount').hasClass('active')) {
        $('#pcount').addClass('in active');
      }
    }

// STOP DeskTop

  $('#whachTab').click(function() {
    $(this).find('a').css('font-weight','bold');
    $('#pCountTab').find('a').css('font-weight','normal');
    $('#pCountTabMbl').hasClass('active') ? '' : $('#pCountTabMbl').removeClass('active');
    $('#whachTabMbl').hasClass('active') ? '' : $('#whachTabMbl').addClass('active');
    if (!$('#whachMbl').hasClass('active')) {
      $('#whachMbl').addClass('in active');
    }
    if ($('#pcountMbl').hasClass('active')) {
      $('#pcountMbl').removeClass('active')
      $('#pcountv').removeClass('in');
    }
  });

  $('#pCountTab').click(function() {
    $(this).find('a').css('font-weight','bold');
    $('#whachTab').find('a').css('font-weight','normal');
    $('#whachTabMbl').hasClass('active') ? '' : $('#whachTabMbl').removeClass('active');
    $('#pCountTabMbl').hasClass('active') ? '' : $('#pCountTabMbl').addClass('active');
    if (!$('#pcountMbl').hasClass('active')) {
      $('#pcountMbl').addClass('in active');
    }
    if ($('#whachMbl').hasClass('active')) {
      $('#whachMbl').removeClass('active')
      $('#whachMbl').removeClass('in');
    }
  });

  // START Mobile tabs ruller
    if ($('#whachTabMbl').hasClass('active') || $('#pCountTabMbl').hasClass('active')) {
      if ($('#whachTabMbl').hasClass('active')) {
        $('#whachTabMbl').find('a').css('font-weight','bold');
        $('#pCountTabMbl').find('a').css('font-weight','normal');
        if ($('#pCountTabMbl').hasClass('active')) {
          $('#pCountTabMbl').removeClass('active')
        }
        if ($('#pcountMbl').hasClass('active')) {
          $('#pcountMbl').removeClass('active')
          $('#pcountMbl').removeClass('in');
        }
        if (!$('#whachMbl').hasClass('active')) {
          $('#whachMbl').addClass('in active');
        }
      } else {
        $('#pCountTabMbl').find('a').css('font-weight','bold');
        $('#whachTabMbl').find('a').css('font-weight','normal');
        if ($('#whachMbl').hasClass('active')) {
          $('#whachMbl').removeClass('active')
          $('#whachMbl').removeClass('in');
        }
        if (!$('#pcountMbl').hasClass('active')) {
          $('#pcountMbl').addClass('in active')
        }
      }
    } else if (data_page.option_practices_watch) {
      $('#whachTabMbl').addClass('active');
      $('#whachTabMbl').find('a').css('font-weight','bold');
      $('#pCountTabMbl').find('a').css('font-weight','normal');
      if ($('#pcountMbl').hasClass('active')) {
        $('#pcountMbl').removeClass('active')
        $('#pcountMbl').removeClass('in');
      }
      if (!$('#whachMbl').hasClass('active')) {
        $('#whachMbl').addClass('in active');
      }
    } else {
      $('#pCountTabMbl').addClass('active')
      $('#pCountTabMbl').find('a').css('font-weight','bold');
      $('#whachTabMbl').find('a').css('font-weight','normal');
      if ($('#whachMbl').hasClass('active')) {
        $('#whachMbl').removeClass('active')
        $('#whachMbl').removeClass('in');
      }
      if (!$('#pcountMbl').hasClass('active')) {
        $('#pcountMbl').addClass('in active')
      }
    }


    $('#whachTabMbl').click(function() {
      $(this).find('a').css('font-weight','bold');
      $('#pCountTabMbl').find('a').css('font-weight','normal');
      $('#pCountTab').hasClass('active') ? '' : $('#pCountTab').removeClass('active');
      $('#whachTab').hasClass('active') ? '' : $('#whachTab').addClass('active');
      if (!$('#whach').hasClass('active')) {
        $('#whach').addClass('in active');
      }
      if ($('#pcount').hasClass('active')) {
        $('#pcount').removeClass('active')
        $('#pcount').removeClass('in');
      }
    });

    $('#pCountTabMbl').click(function() {
      $(this).find('a').css('font-weight','bold');
      $('#whachTabMbl').find('a').css('font-weight','normal');
      $('#whachTab').hasClass('active') ? '' : $('#whachTab').removeClass('active');
      $('#pCountTab').hasClass('active') ? '' : $('#pCountTab').addClass('active');
      if (!$('#pcount').hasClass('active')) {
        $('#pcount').addClass('in active');
      }
      if ($('#whach').hasClass('active')) {
        $('#whach').removeClass('active')
        $('#whach').removeClass('in');
      }
    });
  // STOP Mobail tabs ruller

  if (!settingOn) {
    $('.tab-content').find('.nav-tabs').hide();
  }

  if ($(window).width()>=769 && $(window).width() < 980) {
    $('#blankTbl').css('width', '287px');
  }

// ПРОРАБОТАТЬ ГЛЮЧИТ ПЕРИОДИЧЕСКИ ОБОИМ ТАБАМ ЗАДАЕТСЯ АКТИВ !!!
  $(window).resize(function(){
    if ($(window).width()>=769 && $('#pcountMbl').hasClass('active') && !$('#whach').hasClass('active')) {
      $('#pcount').hasClass('active') ? '' : $('#pcount').addClass('in active');
    } else if ($(window).width()>=769 && $('#whachMbl').hasClass('active') && !$('#pcount').hasClass('active')) {
      $('#whach').hasClass('active') ? '' : $('#whach').addClass('in active');
    } else if ($(window).width()<769 && !$('#pcountMbl').hasClass('active') && !$('#whachMbl').hasClass('active') ) {
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
  if (dataForBlank.length === 0) {
    return
  }
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
    var tableData=[],tableDataMbl=[],m_revival,p_pray,co_pray,r_bible, r_ministry,evangel,flyers,contacts,saved,meetings,dayOfWeek, dayOfWeek2, hangupTime, wakeupTime;
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
      tableData.push('<tr class="practices_str cursor-pointer" data-other="'+x[i].other+'" data-weekday="'+dayOfWeek+'" data-date="'+x[i].date_practic+'"><td>'+x[i].date_practic+' <br><span class="example" style="margin-left: 0">'+dayOfWeek+'</span></td><td '+wakeupOn+'>'+wakeupTime+'</td><td>'+m_revival+'</td><td>'+p_pray+'</td><td>'+co_pray+'</td><td>'+r_bible+'</td><td>'+r_ministry+'</td><td '+gospelOn+'>'+evangel+'</td><td '+gospelOn+'>'+flyers+'</td><td '+gospelOn+'>'+contacts+'</td><td '+gospelOn+'>'+saved+'</td><td '+gospelOn+'>'+meetings+'</td><td '+wakeupOn+'>'+hangupTime+'</td></tr>');
      tableDataMbl.push('<div class="practices_strMbl" data-other="'+x[i].other+'" data-weekday="'+dayOfWeek+'" data-date="'+x[i].date_practic+'" data-uo="'+x[i].m_revival+'" data-lm="'+x[i].p_pray+'" data-mt="'+x[i].co_pray+'" data-chb="'+x[i].r_bible+'" data-chs="'+x[i].r_ministry+'" data-bl="'+x[i].evangel+'" data-l="'+x[i].flyers+'" data-k="'+x[i].contacts+'" data-s="'+x[i].saved+'" data-v="'+x[i].meetings+'" data-wakeup="'+wakeupTime+'" data-hangup="'+hangupTime+'" ><strong>'+x[i].date_practic+' '+dayOfWeek+'</strong><span '+wakeupOn+'>. Подъём: '+wakeupTime+'</span><span>, УО: '+x[i].m_revival+'</span><span>, ЛМ: '+x[i].p_pray+'</span><span>, МТ: '+x[i].co_pray+'</span><span>, ЧБ: '+x[i].r_bible+'</span><span>, ЧС: '+x[i].r_ministry+'</span><span '+gospelOn+'>, БЛ: '+x[i].evangel+'</span><span '+gospelOn+'>, Л: '+x[i].flyers+'</span><span '+gospelOn+'>, К: '+x[i].contacts+'</span><span '+gospelOn+'>, С: '+x[i].saved+'</span><span '+gospelOn+'>, В: '+x[i].meetings+'</span><span '+wakeupOn+'>, Отбой: '+hangupTime+'.</span></div><hr style="margin: 10px 0;">');
    }
     $('#practicesListPersonal tbody').html(tableData);
     $('#practicesListPersonalMbl').html(tableDataMbl);

     $('.practices_str').click(function(e) {
       e.stopPropagation();
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
       $('#mrPractic').val($(this).find('td:nth-child(3)').text() !== '-' ? $(this).find('td:nth-child(3)').text() : '');
       $('#ppPractic').val($(this).find('td:nth-child(4)').text() !== '-' ? $(this).find('td:nth-child(4)').text() : '');
       $('#pcPractic').val($(this).find('td:nth-child(5)').text() !== '-' ? $(this).find('td:nth-child(5)').text() : '');
       $('#rbPractic').val($(this).find('td:nth-child(6)').text() !== '-' ? $(this).find('td:nth-child(6)').text() : '');
       $('#rmPractic').val($(this).find('td:nth-child(7)').text() !== '-' ? $(this).find('td:nth-child(7)').text() : '');
       $('#gsplPractic').val($(this).find('td:nth-child(8)').text() !== '-' ? $(this).find('td:nth-child(8)').text() : '');
       $('#flPractic').val($(this).find('td:nth-child(9)').text() !== '-' ? $(this).find('td:nth-child(9)').text() : '');
       $('#cntPractic').val($(this).find('td:nth-child(10)').text() !== '-' ? $(this).find('td:nth-child(10)').text() : '');
       $('#svdPractic').val($(this).find('td:nth-child(11)').text() !== '-' ? $(this).find('td:nth-child(11)').text() : '');
       $('#meetPractic').val($(this).find('td:nth-child(12)').text() !== '-' ? $(this).find('td:nth-child(12)').text() : '');
       $('#timeWakeup').val($(this).find('td:nth-child(2)').text() !== '-' ? $(this).find('td:nth-child(2)').text() : '');
       $('#timeHangup').val($(this).find('td:nth-child(13)').text() !== '-' ? $(this).find('td:nth-child(13)').text() : '');
       $('#otherDesk').val($(this).attr('data-other'));

     });
     // Mobile fulfil blank
     $('.practices_strMbl').click(function(e) {
       e.stopPropagation(e);
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
    var tableDataser=[], tableDataMblser=[], m_revivalser, p_prayser, co_prayser, r_bibleser, r_ministryser, evangelser, flyersser, contactsser, savedser, meetingsser, dayOfWeekser, hangupTimeser, wakeupTimeser, serving_one, statisticLine = [], statisticLineObj ={};
    for (var i = 0; i < x.length; i++) {
      if (data_page.admin_locality !== '001214') {
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
      x[i].serving ? serving_one = x[i].serving : serving_one = '';
      var serviceOneName = '-';
      var serviceOnesAll = data_page.serviceones;
      for (var ii in serviceOnesAll) {
        if (ii === x[i].serving) {
          serviceOneName = serviceOnesAll[ii];
        }
      }

// START COLLECT THE STRINGS
//statisticLine[x[i].member_id] !== undefined ? statisticLine[x[i].member_id] = statisticLine[x[i].member_id]+1 : statisticLine[x[i].member_id] = 1;
//console.log(statisticLine[x[i].member_id][3]== undefined, ', ', x[i].m_revival);
if (statisticLine[x[i].member_id] === undefined) {
  statisticLine[x[i].member_id] = [x[i].locality_key, x[i].serving, serviceOneName, Number(x[i].m_revival), Number(x[i].p_pray), Number(x[i].co_pray), Number(x[i].r_bible), Number(x[i].r_ministry), Number(x[i].evangel), Number(x[i].flyers), Number(x[i].contacts), Number(x[i].saved), Number(x[i].meetings), x[i].member_id, shortNameMem, x[i].loc_name,1];
} else {
  statisticLine[x[i].member_id][3] += Number(x[i].m_revival);
  statisticLine[x[i].member_id][4] += Number(x[i].p_pray);
  statisticLine[x[i].member_id][5] += Number(x[i].co_pray);
  statisticLine[x[i].member_id][6] += Number(x[i].r_bible);
  statisticLine[x[i].member_id][7] += Number(x[i].r_ministry);
  statisticLine[x[i].member_id][8] += Number(x[i].evangel);
  statisticLine[x[i].member_id][9] += Number(x[i].flyers);
  statisticLine[x[i].member_id][10] += Number(x[i].contacts);
  statisticLine[x[i].member_id][11] += Number(x[i].saved);
  statisticLine[x[i].member_id][12] += Number(x[i].meetings);
  statisticLine[x[i].member_id][16] += 1;
}

//statisticLine[x[i].member_id][3] == undefined ? statisticLine[x[i].member_id][3] =  Number(statisticLine[x[i].member_id][3]) + Number(x[i].m_revival) : statisticLine[x[i].member_id][3] = Number(x[i].m_revival);

//statisticLineObj[x[i].member_id][x[i].locality_key];
// STOP COLLECT THE STRINGS
      wakeupTimeser.length > 5 ? wakeupTimeser = wakeupTimeser.substr(0, wakeupTimeser.length - 3) : '';
      hangupTimeser.length > 5 ? hangupTimeser = hangupTimeser.substr(0, hangupTimeser.length - 3) : '';
      serviceOneName !== '-' ? serviceOneName = twoNames2(serviceOneName) : serviceOneName = serviceOneName;
      dayOfWeekser = getNameDayOfWeekByDayNumber(x[i].date_practic , false);
      tableDataser.push('<tr class="practices_so_str cursor-pointer" data-id="'+x[i].id+'" data-locality="'+x[i].locality_key+'" data-other="'+x[i].other+'" data-weekday="'+dayOfWeekser+'" data-date="'+x[i].date_practic+'" data-serviceone="'+serving_one+'" data-member_id="'+x[i].member_id+'" data-wakeup="'+wakeupTimeser+'" data-hangup="'+hangupTimeser+'" data-flyers="'+flyersser+'" data-contacts="'+contactsser+'" data-saved="'+savedser+'" data-meetings="'+meetingsser+'" style="display: none;"><td>'+shortNameMem+'</td><td>'+dayOfWeekser+'<br><span class="example" style="margin-left: 0;">'+x[i].date_practic+'</span></td><td>'+m_revivalser+'</td><td>'+p_prayser+'</td><td>'+co_prayser+'</td><td>'+r_bibleser+'</td><td>'+r_ministryser+'</td><td>'+evangelser+'</td><td '+globalLocalityOn+'>'+x[i].loc_name+'</td><td>'+serviceOneName+'</td></tr>');

      tableDataMblser.push('<tr class="practices_so_str_mbl cursor-pointer" data-id="'+x[i].id+'" data-locality="'+x[i].locality_key+'" data-other="'+x[i].other+'" data-weekday="'+dayOfWeekser+'" data-date="'+x[i].date_practic+'" data-serviceone="'+serving_one+'" data-member_id="'+x[i].member_id+'" data-wakeup="'+wakeupTimeser+'" data-hangup="'+hangupTimeser+'" data-flyers="'+flyersser+'" data-contacts="'+contactsser+'" data-saved="'+savedser+'" data-meetings="'+meetingsser+'" style="display: none;"><td><span class="name_student_str">'+shortNameMem+'</span><br><span class="example" style="margin-left: 0;">'+x[i].date_practic+'</span><br><span class="example" style="margin-left: 0;">'+dayOfWeekser+'</span></td><td>'+m_revivalser+'</td><td>'+p_prayser+'</td><td>'+co_prayser+'</td><td>'+r_bibleser+'</td><td>'+r_ministryser+'</td><td>'+evangelser+'</td><td '+globalLocalityOn+'>'+x[i].loc_name+'</td></tr>');
    }

    var tempArr = [];
    for (var colectStr in statisticLine) {
      tempArr = statisticLine[colectStr];
      var adminSohrtName;
        tempArr[2] !== '-' ? adminSohrtName = twoNames2(tempArr[2]) : adminSohrtName = tempArr[2];
        tableDataser.unshift('<tr class="practices_main_str cursor-pointer" data-member_id="'+tempArr[13]+'" data-locality="'+tempArr[0]+'" data-serviceone="'+tempArr[1]+'" style="background-color: #f2f2f2"><td>'+tempArr[14]+'</td><td>'+tempArr[16]+' дней</td><td>'+tempArr[3]+'</td><td>'+tempArr[4]+'</td><td>'+tempArr[5]+'</td><td>'+tempArr[6]+'</td><td>'+tempArr[7]+'</td><td>'+tempArr[8]+'</td><td '+globalLocalityOn+'>'+tempArr[15]+'</td><td>'+adminSohrtName+'</td></tr>');
        tableDataMblser.unshift('<tr class="practices_main_str_mbl cursor-pointer" data-member_id="'+tempArr[13]+'" data-locality="'+tempArr[0]+'" data-serviceone="'+tempArr[1]+'" style="background-color: #f2f2f2"><td>'+tempArr[14]+'<br> <span class="example" style="margin-left: 0;">'+adminSohrtName+'</span></td><td>'+tempArr[3]+'</td><td>'+tempArr[4]+'</td><td>'+tempArr[5]+'</td><td>'+tempArr[6]+'</td><td>'+tempArr[7]+'</td><td>'+tempArr[8]+'</td><td '+globalLocalityOn+'>'+tempArr[15]+'</td></tr>');
    }

    $('#listPracticesForObserve tbody').html(tableDataser);
    $('#listPracticesForObserveMbl tbody').html(tableDataMblser);

    $('tbody .practices_main_str').click(function(e) {
      e.stopPropagation();
      $('.active_string').removeClass('active_string');
      $(this).hasClass('active_string') ? '' : $(this).addClass('active_string');
      var idMem = $(this).attr('data-member_id');
      var rrr, moved = 0;
      $(this).hasClass('str_moved') ? moved = 1 : $(this).addClass('str_moved');
      $('tbody .practices_so_str').each(function() {
        if ($(this).attr('data-member_id') === idMem) {
          if ($(this).is(':visible')) {
            $(this).hide();
            $('.active_string').hasClass('active_string') ? $('.active_string').removeClass('active_string') : '';
          } else {
            $(this).show();
            if (moved === 0) {
              rrr = $(this);
              $(this).remove();
              $('.active_string').after(rrr);
            }
          }
        } else {
          $(this).hide();
        }
      });
        $('tbody .practices_so_str').click(function(u) {
          u.stopPropagation();
          $('.active_sub_string').removeClass('active_sub_string');
          $(this).addClass('active_sub_string');
          if (!$('.cd-panel-watch').hasClass('cd-panel--is-visible-watch')) {
            $('.cd-panel-watch').addClass('cd-panel--is-visible-watch');
          }
//--------------
          var datedateTmpMbl = Date.parse ($(this).attr('data-date'));
          var datedateMbl = new Date(datedateTmpMbl);
          var curdateMbl = new Date();

          if (!((datedateMbl.getDate() === curdateMbl.getDate()) && (datedateMbl.getFullYear() === curdateMbl.getFullYear()) && (datedateMbl.getMonth() === curdateMbl.getMonth()))) {
            $('#safePracticesToday-watch').attr('disabled',false);
          } else {
            $('#safePracticesToday-watch').attr('disabled',true);
          }
//----------------
          $('#safePracticesToday-watch').attr('data-id_member', $(this).attr('data-member_id'));
          $('#safePracticesToday-watch').attr('data-id', $(this).attr('data-id'));
          $('#dataPractic-watch').text($(this).attr('data-date') + ', ' + $(this).attr('data-weekday') + ', ' +$(this).find('td:nth-child(1)').text());
          $('#timeWakeup-watch').val($(this).attr('data-wakeup') !== '' ? $(this).attr('data-wakeup') : '');
          $('#mrPractic-watch').val($(this).find('td:nth-child(3)').text() !== '-' ? $(this).find('td:nth-child(3)').text() : '');
          $('#ppPractic-watch').val($(this).find('td:nth-child(4)').text() !== '-' ? $(this).find('td:nth-child(4)').text() : '');
          $('#pcPractic-watch').val($(this).find('td:nth-child(5)').text() !== '-' ? $(this).find('td:nth-child(5)').text() : '');
          $('#rbPractic-watch').val($(this).find('td:nth-child(6)').text() !== '-' ? $(this).find('td:nth-child(6)').text() : '');
          $('#rmPractic-watch').val($(this).find('td:nth-child(7)').text() !== '-' ? $(this).find('td:nth-child(7)').text() : '');
          $('#gsplPractic-watch').val($(this).find('td:nth-child(8)').text() !== '-' ? $(this).find('td:nth-child(8)').text() : '');
          $('#flPractic-watch').val($(this).attr('data-flyers') !== '' ? $(this).attr('data-flyers') : '');
          $('#cntPractic-watch').val($(this).attr('data-contacts') !== '' ? $(this).attr('data-contacts') : '');
          $('#svdPractic-watch').val($(this).attr('data-saved') !== '' ? $(this).attr('data-saved') : '');
          $('#meetPractic-watch').val($(this).attr('data-meetings') !== '' ? $(this).attr('data-meetings') : '');
          $('#timeHangup-watch').val($(this).attr('data-hangup') !== '' ? $(this).attr('data-hangup') : '');
          $('#otherDesk-watch').val($(this).attr('data-other'));
//--------------
          //$.get('/ajax/practices.php?get_practices_edit')
            //.done (function(data) {
            //  practicesBlankToday(data.practices);
            //});
        });
    });
// MOBILE WATCH
    $('tbody .practices_main_str_mbl').click(function(e) {
      e.stopPropagation();
      $('.active_string_mbl').removeClass('active_string_mbl');
      $(this).hasClass('active_string_mbl') ? '' : $(this).addClass('active_string_mbl');
      var idMem = $(this).attr('data-member_id');
      var rrr, moved = 0;
      $(this).hasClass('str_moved_mbl') ? moved = 1 : $(this).addClass('str_moved');
      $('tbody .practices_so_str_mbl').each(function() {
        if ($(this).attr('data-member_id') === idMem) {
          if ($(this).is(':visible')) {
            $(this).hide();
            $('.active_string_mbl').hasClass('active_string_mbl') ? $('.active_string_mbl').removeClass('active_string_mbl') : '';
          } else {
            $(this).show();
            if (moved === 0) {
              rrr = $(this);
              $(this).remove();
              $('.active_string_mbl').after(rrr);
            }
          }
        } else {
          $(this).hide();
        }
      });
        $('tbody .practices_so_str_mbl').click(function(u) {
          u.stopPropagation();
          $('.active_sub_string_mbl').removeClass('active_sub_string_mbl');
          $(this).addClass('active_sub_string_mbl');
          if (!$('.cd-panel-watch-mbl').hasClass('cd-panel--is-visible-watch-mbl')) {
            $('.cd-panel-watch-mbl').addClass('cd-panel--is-visible-watch-mbl');
          }

//--------------
          var datedateTmpMbl = Date.parse ($(this).attr('data-date'));
          var datedateMbl = new Date(datedateTmpMbl);
          var curdateMbl = new Date();

          if (!((datedateMbl.getDate() === curdateMbl.getDate()) && (datedateMbl.getFullYear() === curdateMbl.getFullYear()) && (datedateMbl.getMonth() === curdateMbl.getMonth()))) {
              $('#safePracticesToday-watch-mbl').attr('disabled',false);
            } else {
              $('#safePracticesToday-watch-mbl').attr('disabled',true);
          }
//----------------

          $('#safePracticesToday-watch-mbl').attr('data-id_member', $(this).attr('data-member_id'));
          $('#safePracticesToday-watch-mbl').attr('data-id', $(this).attr('data-id'));
          $('#dataPractic-watch-mbl').text($(this).attr('data-date') + ', ' + $(this).attr('data-weekday')+', '+$(this).find('.name_student_str').text());
          $('#timeWakeup-watch-mbl').val($(this).attr('data-wakeup') !== '' ? $(this).attr('data-wakeup') : '');
          $('#mrPractic-watch-mbl').val($(this).find('td:nth-child(2)').text() !== '-' ? $(this).find('td:nth-child(2)').text() : '');
          $('#ppPractic-watch-mbl').val($(this).find('td:nth-child(3)').text() !== '-' ? $(this).find('td:nth-child(3)').text() : '');
          $('#pcPractic-watch-mbl').val($(this).find('td:nth-child(4)').text() !== '-' ? $(this).find('td:nth-child(4)').text() : '');
          $('#rbPractic-watch-mbl').val($(this).find('td:nth-child(5)').text() !== '-' ? $(this).find('td:nth-child(5)').text() : '');
          $('#rmPractic-watch-mbl').val($(this).find('td:nth-child(6)').text() !== '-' ? $(this).find('td:nth-child(6)').text() : '');
          $('#gsplPractic-watch-mbl').val($(this).find('td:nth-child(7)').text() !== '-' ? $(this).find('td:nth-child(7)').text() : '');
          $('#flPractic-watch-mbl').val($(this).attr('data-flyers') !== '' ? $(this).attr('data-flyers') : '');
          $('#cntPractic-watch-mbl').val($(this).attr('data-contacts') !== '' ? $(this).attr('data-contacts') : '');
          $('#svdPractic-watch-mbl').val($(this).attr('data-saved') !== '' ? $(this).attr('data-saved') : '');
          $('#meetPractic-watch-mbl').val($(this).attr('data-meetings') !== '' ? $(this).attr('data-meetings') : '');
          $('#timeHangup-watch-mbl').val($(this).attr('data-hangup') !== '' ? $(this).attr('data-hangup') : '');
          $('#otherDesk-watch-mbl').val($(this).attr('data-other'));
//--------------
          //$.get('/ajax/practices.php?get_practices_edit')
            //.done (function(data) {
            //  practicesBlankToday(data.practices);
            //});
        });
    });

  }

  $('#safePracticesToday-watch').click(function() {
    var dataBlank = {};
    var idString = $(this).attr('data-id');
    dataBlank.mr = $('#mrPractic-watch').val();
    dataBlank.pp = $('#ppPractic-watch').val();
    dataBlank.pc = $('#pcPractic-watch').val();
    dataBlank.rb = $('#rbPractic-watch').val();
    dataBlank.rm = $('#rmPractic-watch').val();
    dataBlank.gspl = $('#gsplPractic-watch').val();
    dataBlank.fl = $('#flPractic-watch').val();
    dataBlank.cnt = $('#cntPractic-watch').val();
    dataBlank.svd = $('#svdPractic-watch').val();
    dataBlank.meet = $('#meetPractic-watch').val();
    dataBlank.wake = $('#timeWakeup-watch').val();
    dataBlank.hang = $('#timeHangup-watch').val();
    dataBlank.oth = $('#otherDesk-watch').val();

    $.get('/ajax/practices.php?update_practices_edit',{user_data: dataBlank, id: idString})
      .done (function(data) {
        if (data === 1) {
          $('.active_sub_string').find('td:nth-child(3)').text(dataBlank.mr);
          $('.active_sub_string').find('td:nth-child(4)').text(dataBlank.pp);
          $('.active_sub_string').find('td:nth-child(5)').text(dataBlank.pc);
          $('.active_sub_string').find('td:nth-child(6)').text(dataBlank.rb);
          $('.active_sub_string').find('td:nth-child(7)').text(dataBlank.rm);
          $('.active_sub_string').find('td:nth-child(8)').text(dataBlank.gspl);
          $('.active_sub_string').attr('data-flyers', dataBlank.fl);
          $('.active_sub_string').attr('data-contacts', dataBlank.cnt);
          $('.active_sub_string').attr('data-saved', dataBlank.svd);
          $('.active_sub_string').attr('data-meetings', dataBlank.meet);
          $('.active_sub_string').attr('data-wakeup', dataBlank.wake);
          $('.active_sub_string').attr('data-hangup', dataBlank.hang );
          $('.active_sub_string').attr('data-other', dataBlank.oth);
        }
      });
  });

  $('#safePracticesToday-watch-mbl').click(function() {
    var dataBlank = {};
    var idString = $(this).attr('data-id');
    dataBlank.mr = $('#mrPractic-watch-mbl').val();
    dataBlank.pp = $('#ppPractic-watch-mbl').val();
    dataBlank.pc = $('#pcPractic-watch-mbl').val();
    dataBlank.rb = $('#rbPractic-watch-mbl').val();
    dataBlank.rm = $('#rmPractic-watch-mbl').val();
    dataBlank.gspl = $('#gsplPractic-watch-mbl').val();
    dataBlank.fl = $('#flPractic-watch-mbl').val();
    dataBlank.cnt = $('#cntPractic-watch-mbl').val();
    dataBlank.svd = $('#svdPractic-watch-mbl').val();
    dataBlank.meet = $('#meetPractic-watch-mbl').val();
    dataBlank.wake = $('#timeWakeup-watch-mbl').val();
    dataBlank.hang = $('#timeHangup-watch-mbl').val();
    dataBlank.oth = $('#otherDesk-watch-mbl').val();

    $.get('/ajax/practices.php?update_practices_edit',{user_data: dataBlank, id: idString})
      .done (function(data) {
        if (data === 1) {
          $('.active_sub_string_mbl').find('td:nth-child(2)').text(dataBlank.mr);
          $('.active_sub_string_mbl').find('td:nth-child(3)').text(dataBlank.pp);
          $('.active_sub_string_mbl').find('td:nth-child(4)').text(dataBlank.pc);
          $('.active_sub_string_mbl').find('td:nth-child(5)').text(dataBlank.rb);
          $('.active_sub_string_mbl').find('td:nth-child(6)').text(dataBlank.rm);
          $('.active_sub_string_mbl').find('td:nth-child(7)').text(dataBlank.gspl);
          $('.active_sub_string_mbl').attr('data-flyers', dataBlank.fl);
          $('.active_sub_string_mbl').attr('data-contacts', dataBlank.cnt);
          $('.active_sub_string_mbl').attr('data-saved', dataBlank.svd);
          $('.active_sub_string_mbl').attr('data-meetings', dataBlank.meet);
          $('.active_sub_string_mbl').attr('data-wakeup', dataBlank.wake);
          $('.active_sub_string_mbl').attr('data-hangup', dataBlank.hang );
          $('.active_sub_string_mbl').attr('data-other', dataBlank.oth);
        }
      });
  });

    function practicesListServiceonesUpdate(periodValue) {
      var adminLocalitiiesForSQL = '';
// LOGJS.JS Нужно создать лог js и подключать к нужным файлам. Проверка объектов и ассоциативных массивов с помощью какогото кода.
// counter=0; for (var i in arr) {if (arr[i]=="" && counter === 0) console.log('ERROR variable = ', data_page.admin_localities, ' counter should be "0" = ', counter);}

      for (var variable in data_page.admin_localities) {
        if (adminLocalitiiesForSQL) {
          adminLocalitiiesForSQL = adminLocalitiiesForSQL + ' OR m.locality_key = ' + String(variable);
        } else {
          adminLocalitiiesForSQL = adminLocalitiiesForSQL + 'm.locality_key = ' + String(variable);
        }
      }
      console.log(data_page.admin_localities, ', ', data_page.admin_locality);
      if (!adminLocalitiiesForSQL) {
        return
      }
      if (data_page.admin_locality === '001214') {
        adminLocalitiiesForSQL = adminLocalitiiesForSQL + ' OR m.locality_key = 001192';
      }
// var localityAdmin = data_page.admin_locality === '001214' ? adminLocalitiiesForSQL + " OR m.locality_key = 001192": adminLocalitiiesForSQL;
    var dataObj = {};
    dataObj.localities = adminLocalitiiesForSQL;
    dataObj.period = periodValue || $('#periodPractices').val();
    $.get('/ajax/practices.php?get_practices_for_admin', {data: dataObj})
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

  function filterAminLocality() {
    $('tbody .practices_main_str').each(function() {
      if (($(this).attr('data-serviceone') === $('#servingCombo').val() || $('#servingCombo').val() === '_all_') && ($(this).attr('data-locality') === $('#adminlocalitiesCombo').val() || $('#adminlocalitiesCombo').val() === '_all_')) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
    $('tbody .practices_so_str').each(function() {
      $(this).hide();
    });
  }

  $('#servingCombo, #adminlocalitiesCombo').change(function () {
      filterAminLocality();
  });

  function filterAminLocalityMbl() {
    $('tbody .practices_main_str_mbl').each(function() {
      if (($(this).attr('data-serviceone') === $('#servingComboMbl').val() || $('#servingComboMbl').val() === '_all_') && ($(this).attr('data-locality') === $('#adminlocalitiesComboMbl').val() || $('#adminlocalitiesComboMbl').val() === '_all_')) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
    $('tbody .practices_so_str_mbl').each(function() {
      $(this).hide();
    });
  }

  $('#servingComboMbl, #adminlocalitiesComboMbl').change(function () {
    filterAminLocalityMbl();
  });

  $('#periodPractices, #periodPracticesMbl').change(function () {
      practicesListServiceonesUpdate($(this).val());
      setTimeout(function () {
        filterAminLocality();
        filterAminLocalityMbl();
      }, 500);      
  });
/*  $('#adminlocalitiesCombo').change(function () {

    //DAILY STRINGS

    if ($(this).val() === '_all_' || $(this).val() === '') {

      $('tbody .practices_so_str').show();
    } else {
      $('tbody .practices_so_str').each(function() {
        $(this).attr('data-locality') === $('#adminlocalitiesCombo').val() && ($(this).attr('data-serviceone') === $('#servingCombo').val() || $('#servingCombo').val() === '_all_') ? $(this).show() : $(this).hide();
      });
    }

  });*/

  if (!$('.cd-panel').hasClass('cd-panel--is-visible') && $('#pcount').is(':visible')) {
    $('.cd-panel').addClass('cd-panel--is-visible');
  } else if ($('.cd-panel').hasClass('cd-panel--is-visible') && !$('#pcount').is(':visible')) {
    $('.cd-panel').removeClass('cd-panel--is-visible');
  }

  if (!$('.cd-panelMbl').hasClass('cd-panel--is-visibleMbl') && $('#pcountMbl').is(':visible')) {
    $('.cd-panelMbl').addClass('cd-panel--is-visibleMbl');
  } else if ($('.cd-panelMbl').hasClass('cd-panel--is-visibleMbl') && !$('#pcountMbl').is(':visible')) {
    $('.cd-panelMbl').removeClass('cd-panel--is-visibleMbl');
  }

/*
  if (!$('.cd-panel').hasClass('cd-panel--is-visibleMbl') && $('#listPracticesForObserve').is(':visible')) {
    $('.cd-panel').addClass('cd-panel--is-visibleMbl');
  } else if ($('.cd-panel').hasClass('cd-panel--is-visible') && !$('#pcount').is(':visible')) {
    $('.cd-panel').removeClass('cd-panel--is-visibleMbl');
  }
*/

// BLANK FOR WATCH PAGE

  $('.cd-panel__close-watch').click(function() {
    $('.cd-panel-watch').removeClass('cd-panel--is-visible-watch');
  });

  $('#cd-panel__close-watch').click(function() {
    $('.cd-panel-watch').removeClass('cd-panel--is-visible-watch');
  });

  $('.cd-panel__close-watch-mbl').click(function() {
    $('.cd-panel-watch-mbl').removeClass('cd-panel--is-visible-watch-mbl');
  });

  $('#cd-panel__close-watch-mbl').click(function() {
    $('.cd-panel-watch-mbl').removeClass('cd-panel--is-visible-watch-mbl');
  });

  setTimeout(function () {
    filterAminLocality();
    filterAminLocalityMbl();
  }, 1250);

});
