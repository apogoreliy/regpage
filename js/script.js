String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g, '');};

function he(str) {
    return str ? String(str)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;') : "";
}

//window.location.pathname === '/login' || '/login.php' ? '' : setCookie('sess_last_page', window.location.pathname, 356);
//getCookie('sess_last_page') === '/login' ? setCookie('sess_last_page', '/reg', 356) : '';
function isValidDate(d) {
  if ( Object.prototype.toString.call(d) !== "[object Date]" )
    return false;
  return !isNaN(d.getTime());
}
function DateSplitAndParce(item) {
  var dateParts = item.split(".");
  var date = new Date(dateParts[2], (dateParts[1] - 1), dateParts[0]);
  return  date
  }

function formatDate (date) {
	var d = new Date(date);
	if (isValidDate(d)){
		var day = d.getDate();
		var month = d.getMonth() + 1;
		var year = d.getFullYear();
		return (day<10 ? "0" : "") + day + (month<10 ? ".0" : ".") + month + "." + year;
	}
	return "";
}

function formatDDMM (date) {
    if (date) {
        var d = new Date(date);
        if (isValidDate(d))
        {
            var day = d.getDate();
            var month = d.getMonth() + 1;
            return (day<10 ? "0" : "") + day + (month<10 ? ".0" : ".") + month;
        }
    }
    return "";
}

function parseDate (date) {
	var d = /(\d{2})\.(\d{2})\.(\d{4})/.exec(date);
    if (d){
        var day = d[1];
        var month = d[2];
        var year = parseInt (d[3]);
        return toDateString (day, month, year);
    }
    return null;
}

function parseDDMM (ddmm, start) {
    var d = /(\d{2})\.(\d{2})/.exec(ddmm);
    if (d){
        var s = typeof start === "undefined" ? new Date () : start;
        var day = d[1];
        var month = d[2];
        var year = s.getFullYear();
        if (s.getMonth()==11 && month==1) year+=1;
        if (s.getMonth()==0 && month==12) year-=1;
        return toDateString (day, month, year);
    }
    return null;
}

function toDateString (day, month, year) {
    if (month==2 && day>((((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0)) ? 29 : 28)) return null;
    if ((month==4 || month==6 || month==9 || month==11) && day>30) return null;
    if (day>0 && day<32 && month>0 && month<13 && year>=0) {
        if (year<1000) year+=2000;
        return year + (month.length<2 ? "-0" : "-") + month + (day.length<2 ? "-0" : "-") + day;
    }
    return null;
}

function formatTime (time) {
	return time.replace(/:00$/,'');
}

function parseTime (time) {
	var t = /^(\d{2}):(\d{2})$/.exec (time);
	return (t && t[1]>=0 && t[1]<24 && t[2]>=0 && t[2]<60) ? time : null;
}

function setFieldError(field, isError){
    var el;

    if( field.hasClass('emNewLocality')){
        el = field.parents ("div.modal").find (".emLocality");
        isError = (!el.val() || el.val() === '_none_') && field.val() === '';

        if(!isError){
            el.parents('div.control-group').removeClass('error');
        }
    }

    if (field.parents (".control-group").length)
        field = field.parents (".control-group");

    if (isError)
        field.addClass ("error");
    else
        field.removeClass ("error");

    if (field.parents ("div.modal").find (".control-group.error").length>0 || field.parents ("div.modal").find (" select.error").length>0){
        field.parents("div.modal").find(".disable-on-invalid").addClass("disabled");
    } else{
        field.parents("div.modal").find(".disable-on-invalid").removeClass("disabled");
    }
}

function f (v) {
	var a = /^\d{1,2}\.\d{1,2}\.\d{2,4}$/.exec(v);
	//console.log ("v = " + v + "(" + (a?a:"null") + ")");
	return true;
}

function showError(html, autohide) {
	$("#globalError > span").html (html);
  var a = $("#globalError > span").text();
  if (a.length === 0) {
    //window.location = "login.php?returl="+/\/[^\/]+$/g.exec (document.URL);
    var b = window.location.href;
    window.location = b;
  }
	$("#globalError").fadeIn();
	if (autohide || typeof autohide === "undefined") window.setTimeout(function() { $("#globalError").fadeOut (); }, 4000);
}

function showHint(html, autohide) {
	$("#globalHint > span").html (html);
	$("#globalHint").fadeIn();
	if (autohide || typeof autohide === "undefined") window.setTimeout(function() { $("#globalHint").fadeOut (); }, 3000);
}

function displayAidFields (windowWidth, formEl, aidVal){
    var display = {'display': (aidVal < 1 || aidVal == undefined) ? 'none': 'block'};
    formEl.find('.emContrAmount, .emContrAmountLabel, .emTransAmount, .emTransAmountLabel, .emFellowship, .emFellowshipLabel').css(display);
    formEl.find('.emAid').val(aidVal || (aidVal < 1 || aidVal == undefined ? 0 : 1)).css({'display': 'block'});

    if(aidVal === 1){
        formEl.find('.emFellowship').val('_none_').change();
        formEl.find('.emTransAmount, .emContrAmount').val(0);
    }
    else{
        setFieldError(formEl.find('.emFellowship'), false);
        setFieldError(formEl.find('.emTransAmount'), false);
        setFieldError(formEl.find('.emContrAmount'), false);
        formEl.find('.emContrAmount, .emTransAmount').removeClass('error');
        displayBtnDoSaveMember();
    }
}

function displayBtnDoSaveMember(disable){
    disable ? $("#btnDoSaveMember").addClass ("disable-on-invalid") : $("#btnDoSaveMember").removeClass ("disable-on-invalid").removeClass("disabled");
}

function getEventFieldZoneArea (field){
    var result = 'l';
    switch(field.length){
        case 2:
            result = 'c';
            break;
        case 4:
            result = 'r';
            break;
    }
    return result;
}

$(document).ready(function(){
    /* http://digitalbush.com/projects/masked-input-plugin/ */
    $.mask.definitions['1']='[01]';
    $.mask.definitions['2']='[012]';
    $.mask.definitions['3']='[0123]';
    $.mask.definitions['5']='[012345]';
    $('input[placeholder="ДД.ММ.ГГГГ"]').mask("39.19.99?99");
    $('input[placeholder="ДД.ММ"]').mask("39.19");
    $('input[placeholder="ЧЧ:ММ"]').mask("29:59");

    $("#btnDoSendEventMsgAdmins").click (function (){
        if ($(this).hasClass('disabled')) return;

        var page = location.pathname.split('.')[0], eventId = page === "/reg" ? $("#events-list").val() : "";

        $.ajax({type: "POST", url: "/ajax/set.php", data: {event : eventId, message: $("#sendMsgTextAdmin").val(), name:$("#sendMsgNameAdmin").val(), email:$("#sendMsgEmailAdmin").val(), admins: page}})
        .done (function() {messageBox ('Ваше сообщение отправлено службе поддержки', $('#messageAdmins'));});
    });

    var isTabletWidth = $(document).width() < 980;

    $('.datepicker').datepicker({
        language: 'ru',
        autoclose : true,
        forceParse: false
    });

    $('.datepicker-form').datepicker({
        language: 'ru',
        autoclose : true,
        format: {
            toDisplay: function (date) {
                return formatDDMM(date);
            },
            toValue: function (date) {
                var arrDate = date.split('.');
                return new Date(parseDDMM(date, new Date ($('#modalEditMember').find("input[data-double_date$='"+(arrDate[1]+'-'+arrDate[0])+"']").attr('data-double_date'))));
            }
        }
    });

    $(".datepicker, .datepicker-form").change(function(){
       setFieldError ($(this), isValidDate($(this).val()));
    });

    $(".meeting-lists-combo").change(function(){
        listsType = $(".meeting-lists-combo").val();
        switch (listsType) {
            case 'meetings': window.location = '/meetings'; break;
            case 'visits': window.location = '/visits'; break;
        }
    });

    $(".emCollege").focus(function(){
        getColleges();
    });

$(".scroll-up").click(function(e){
    e.stopPropagation();

    //scrollTo(document.body, 0, 500);
  //  setTimeout(function(){
        document.body.scrollTop = document.documentElement.scrollTop = 0;
//    }, 500);
    $('body').animate({
        scrollTop: 0
    }, 500);
});

window.onscroll = function() {
    handleScrollUp();
    handleAditionalMenu();
};

function scrollTo(element, to, duration) {
    if (duration <= 0) return;
    var difference = to - element.scrollTop;
    var perTick = difference / duration * 10;

    setTimeout(function() {
        element.scrollTop = element.scrollTop + perTick;
        if (element.scrollTop === to) return;
        scrollTo(element, to, duration - 10);
    }, 10);
}

function handleScrollUp(){
    var height = $("body").height();
    //var scrollTop = $("body").scrollTop();

    height>600 && (window.pageYOffset > 300 || document.documentElement.scrollTop >300) ? $(".scroll-up").show() : $(".scroll-up").hide();
}

function handleAditionalMenu(){
    var height = $("body").height();
//    var scrollTop = $("body").scrollTop();

    if(height > 400 && (window.pageYOffset < 300 || document.documentElement.scrollTop < 300)){
        $('.aditional-menu').css('display', 'none');
        //$(".handle-aditional-menu").removeClass('fa-caret-square-o-up').addClass('fa-caret-square-o-down').css('display', 'none');
    }
    else{
        $('.aditional-menu').css('display', 'block');
        //$('.handle-aditional-menu').css('display', 'inline');
    }
    //if(height > 400 && (window.pageYOffset < 300 || document.documentElement.scrollTop < 300) && $('.aditional-menu').css('display') === 'block'){
    //    $('.aditional-menu').css('display', 'none');
    //}
}

    $('.search').click(function(){
        var el = $(this).siblings('.not-display');
        if(!$(this).hasClass('active')){
            el.css('display', 'inline');
            $(this).addClass('active');
            el.children('input').focus();
        }
        else{
            el.css('display', 'none');
            $(this).removeClass('active');
        }
    });

    $('.clear-search').click(function(){
        $(this).siblings('.search-text').val('');
        loadDashboard();
    });

    $('.emDepDate, .emArrDate').change(function(){
        var isDepDate = $(this).hasClass('emDepDate');
        if(!$(this).parents('.control-group').hasClass('error')) {
            var currentDates = $(this).val().split('.'),
            tooltipDate = $(this).parents("#modalEditMember").find( isDepDate ? ".tooltipDepDate" : ".tooltipArrDate").data ('date'),
            //tooltipDate = $(this).parents("#modalEditMember").find( isTabletWidth ? ".tablets-visible" : ".desctop-visible").find( isDepDate ? ".tooltipDepDate" : ".tooltipArrDate").data ('date'),
            eventDate = new Date(tooltipDate),
            eventDateMilliseconds = eventDate.setDate(eventDate.getDate() + ( isDepDate ? 10 : -11)),
            borderDate = new Date(eventDateMilliseconds),
            year = borderDate.getFullYear(),

            // if an event occurs in January 2017 borderdate will be in December 2016;
            // year for a current date will be choose 2016 but actually meant 2017
            // how to solve this situation?
            // add algorithm: add 1 year if current date is January and event occurs in January or in December

            currentDate = new Date((( currentDates[1] - 1 === 0 && borderDate.getFullYear() < eventDate.getFullYear()  && !isDepDate ) ||
            ( !isDepDate && eventDate.getMonth() == 11 && currentDates[1] - 1 === 0 ) ? year + 1 : year) , currentDates[1] - 1, currentDates[0]);

            if (isDepDate ? currentDate >= borderDate : currentDate <= borderDate) {
                $(this).focus().parents('.control-group').addClass('error');
                setFieldError($(this), true);
                showError('Некорректно введена дата '+ (isDepDate ? 'отъезда' : 'приезда'), true);
            }
        }
    });

    $(".emFellowship").change(function () {
        var value = $(this).val();
        displayBtnDoSaveMember(value === '_none_');

        if(value === '0') {
            $('#modalEditMember').find('.emContrAmount, .emTransAmount, .emAid').val(0);
            displayAidFields(isTabletWidth, $('#modalEditMember'), 0);
            showError("Сначала вам нужно пообщаться о финансовой помощи с братьями в вашей местности и затем заполнить эти поля, если будет необходимость");
        }
    });

    $(".emAid").change(function () {
        displayAidFields(isTabletWidth, $('#modalEditMember'), parseInt($(this).val()));
    });

    $('.emContrAmount, .emTransAmount').change(function(){
        if(isNaN($(this).val())) {
            $(this).focus().addClass('error');
            setFieldError($(this), true);
            showError('Некорректно введена сумма помощи', true);
        }
        else{
            setFieldError($(this), false);
            $(this).removeClass('error').removeClass('error');
        }
    });

    // additional check for emPrepaid field. General check input[valid] doesn't work
    $("input").keyup (function () {
        if($(this).attr('valid') && $(this).hasClass('emPrepaid')){
            setFieldError ($(this),
                $(this).parents(".controls").css('display')!='none' &&
                $(this).parents(isTabletWidth ? ".controls" : ".control-group").css('visibility')!='hidden' &&
                $(this).attr('disabled')!='disabled' &&
                    (($(this).attr("valid").indexOf ("required")>=0 && ($(this).val().length==0 || $(this).val()=="__.__.____" || $(this).val()=="__.__")) ||
                    ($(this).attr("valid").indexOf ("email")>=0 && ($(this).val().length>0 && /^[^@]+@[^@]+\.[^@]{2,5}$/.exec($(this).val())==null)) ||
                    ($(this).attr("valid").indexOf ("date")>=0 && ($(this).val().length>0 && $(this).val()!="__.__.____" && parseDate($(this).val())==null)) ||
                    ($(this).attr("valid").indexOf ("ddmm")>=0 && ($(this).val().length>0 && $(this).val()!="__.__" && parseDDMM($(this).val())==null)) ||
                    ($(this).attr("valid").indexOf ("time")>=0 && ($(this).val().length>0 && $(this).val()!="__:__" && parseTime($(this).val())==null)) ||
                    ($(this).attr("valid").indexOf ("integer")>=0 && (/^\d+$/.exec($(this).val())==null)) ||
                    ($(this).attr("valid").indexOf ("required")>=0 && $(this).attr("valid").indexOf ("integer")>=0 && $(this).val()==0) ||
                    ($(this).attr("valid").indexOf ("required")>=0 && $(this).val().trim()=='')
                )
            );
        }
    });

    // additional check for emCurrency field. General check select[valid='required'] doesn't work
    $("select").change (function () {
        if($(this).attr('valid') && $(this).hasClass('emCurrency')){
            setFieldError ($(this),
                $(this).parents (".controls").css('display')!='none' &&
                $(this).parents (isTabletWidth ? ".controls" : ".control-group").css('visibility')!='hidden' &&
                $(this).attr('disabled')!='disabled' &&
                (!$(this).val() || $(this).val()=="_none_"));
        }
    });

    $("input[valid]").keyup (function () {
        setFieldError ($(this),
            $(this).parents(".controls").css('display')!='none' &&
            $(this).parents(isTabletWidth ? ".controls" : ".control-group").css('visibility')!='hidden' &&
            $(this).attr('disabled')!='disabled' &&
                (($(this).attr("valid").indexOf ("required")>=0 && ($(this).val().length==0 || $(this).val()=="__.__.____" || $(this).val()=="__.__")) ||
                ($(this).attr("valid").indexOf ("email")>=0 && ($(this).val().length>0 && /^[^@]+@[^@]+\.[^@]{2,5}$/.exec($(this).val())==null)) ||
                ($(this).attr("valid").indexOf ("date")>=0 && ($(this).val().length>0 && $(this).val()!="__.__.____" && parseDate($(this).val())==null)) ||
                ($(this).attr("valid").indexOf ("ddmm")>=0 && ($(this).val().length>0 && $(this).val()!="__.__" && parseDDMM($(this).val())==null)) ||
                ($(this).attr("valid").indexOf ("time")>=0 && ($(this).val().length>0 && $(this).val()!="__:__" && parseTime($(this).val())==null)) ||
                ($(this).attr("valid").indexOf ("integer")>=0 && (/^\d+$/.exec($(this).val())==null)) ||
                ($(this).attr("valid").indexOf ("required")>=0 && $(this).attr("valid").indexOf ("integer")>=0 && $(this).val()==0) ||
                ($(this).attr("valid").indexOf ("required")>=0 && $(this).val().trim()=='')
            )
        );
    });

    $('.filter-aid').click(function(){
        var scope = $(this).data('scope');
        var modalHeader = $(this).parents('#modalAidStatistic').find('.modal-header h4');
        var eventId = modalHeader.data('event-id'), eventName = modalHeader.find('.event-name').text();

        $.getJSON('/ajax/get.php', { eventIdAid: eventId, scope : scope})
            .done (function(data) {
                getAidInfo (data.members, eventName, eventId);
            });
    });

    $(".emNewLocality").keyup (function () {
        setFieldError ($(this),
            $(this).parents(".controls").css('display')!='none' &&
            $(this).parents(isTabletWidth ? ".controls" : ".control-group").css('visibility')!='hidden' &&
            $(this).attr('disabled')!='disabled'
        );
    });

    $("select[valid='required']").change (function () {
        setFieldError ($(this),
            $(this).parents (".controls").css('display')!='none' &&
            $(this).parents (isTabletWidth ? ".controls" : ".control-group").css('visibility')!='hidden' &&
            $(this).attr('disabled')!='disabled' &&
            (!$(this).val() || $(this).val()=="_none_"));
    });

    $(document).ajaxSend(function(event, jqxhr, settings){
        if (/localities.php/.exec (settings.url)==null){
            var page = location.pathname.split('.')[0];
            if(page !== '/signup'){
                $("#ajaxLoading").show ();
            }
        }
    });

    $(document).ajaxStop(function (){
        $("#ajaxLoading").hide ();
    });

    $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
        if (thrownError=='Unauthorized') {
          var b = window.location.href;
          window.location = b;
            //window.location = "login.php?returl="+/\/[^\/]+$/g.exec (document.URL);
        }else{
            showError (jqxhr.responseText);
        }
    });

    $("#inputEmLocalityId").autocomplete({
      serviceUrl: '/ajax/localities2.php',
      onSelect: function (suggestion) {
          $("#inputEmLocalityId").focus();
      }
    });

    $('.locality-autocomplete').autocomplete({
        serviceUrl: '/ajax/localities.php',
        onSelect: function (suggestion) {
            $(".emLocality").val (suggestion.data);
            window.selLocId = suggestion.data;
            window.selLocName = suggestion.value;
            $(this).attr ("disabled", "disabled");
            $(this).next (".unblock-input").show ();
        }
    });

    $('.locality-autocomplete ~ .unblock-input').click(function (){
        var txt = $(this).prev (".locality-autocomplete");
        txt.removeAttr ("disabled");
        txt.focus ();
        txt.select ();
        $(this).hide ();
    });

    $('[rel=tooltip]').tooltip();

    $('input[tooltip]').keydown (function () {
            if ($(this).val().length==0)
                $('#'+$(this).attr('tooltip')).tooltip('show');
    });

    $('input[tooltip]').blur (function () {
            $('#'+$(this).attr('tooltip')).tooltip('hide');
    });

    $('.tooltip-on-change[type="checkbox"]').change (function () {
            if ($(this).prop('checked'))
                    $(this).parent().find ('a[rel="tooltip"]').tooltip('show');
    });

    $('.tooltip-on-change').mouseout (function () {
            $(this).parent().find ('a[rel="tooltip"]').tooltip('hide');
    });

    $(".modal-send-message").on('shown', function () {
        var name = $(this).find(".name-field");
        var email = $(this).find(".email-field");

        name.keyup();
        email.keyup();
        if (name.val()=='') name.focus();
        else if (!email.val()) email.focus();
        else {
            var text = $(this).find(".text-field");
            if (!text.val()) text.focus();
        }
    });

    $("#modalMessageBox").on('hide', function () {
        window.messageBoxParent.modal('hide');
    });

    $('.paidAid').click(function(){
        var obj = helpFuncToHandleAidInteraction(this);
        if(isNaN(parseInt(obj.amount))){
            var elem = $(this).parents("#modalUserAidInfo").find('.aid-amount');
            setFieldError(elem, true);
            showError('Некорректно введена сумма помощи', true);
        }
        else{
            handleAidInteraction(obj.id, obj.amount, obj.eventId, obj.eventName);
        }
    });

    $('.dismissAid').click(function(){
        var obj = helpFuncToHandleAidInteraction(this);
        handleAidInteraction(obj.id, -1, obj.eventId, obj.eventName);
    });

    $('.aid-amount').change(function(){
        if(!isNaN(parseInt($(this).val()))){
            setFieldError($(this), false);
        }
    });

    $('.emName').focus(function(){
        $('.name-hint').css('display', 'block');
    });

    $('.emName').blur(function(){
        $('.name-hint').css('display', 'none');
    });

    $(".emCategory").change(function(){
        handleBirthDateAndCategoryFields();
    });

    $(".emBirthdate").keyup(function(){
        handleBirthDateAndCategoryFields();
    });

    $(".emBirthdate").change(function(){
      var a = $(".emBirthdate").val();
      if (a.length === 0) {
          setFieldError($(this), true);
      } else {
          setFieldError($(this), false);
      }
    });

    $(".handle-new-locality").click(function(){
        $('.block-new-locality').css('display', 'block');
        $(".handle-new-locality").hide();
        setTimeout(function(){
            $('.emLocalityNew, .emNewLocality').focus();
        }, 500);
    });

    $(".handle-passport-info").click(function(){
        var block = $(".block-passport-info"), icon = $(this).find('i');;
        block.css('display') === 'none' ? block.css('display', 'block') : block.css('display', 'none');
        icon.hasClass('fa-chevron-down') ? icon.removeClass('fa-chevron-down').addClass('fa-chevron-up') : icon.removeClass('fa-chevron-up').addClass('fa-chevron-down') ;
    });

    $('.zones-checkbox-block input[type="checkbox"], .participants-checkbox-block input[type="checkbox"], .template-participants-checkbox-block input[type="checkbox"] ').change(function(){
        $(this).parents('.btn-group').siblings().find('input[type="checkbox"]').prop('checked', false);
    });

    $('.search-zones').keyup(function(){
        var text = $(this).val().trim().toLocaleLowerCase(),
        checkbox = $('.zones-checkbox-block input[type="checkbox"]:checked');

        if(text === ''){
            $('.zones-available').html('');
        }
        else if(!checkbox.length>0){
            showError('Выберите границу поиска');
            return;
        }
        else{
            $.post('/ajax/event.php?get_zones', {text : text, field : checkbox.attr('data-field')})
            .done(function(data){
                if(data.zones && data.zones.length > 0){
                    handleEventZones(data.zones);
                }
                else{
                    $('.zones-available').html('');
                }
            });
        }
    });

    $('.search-participants').keyup(function(){
        var text = $(this).val().trim().toLocaleLowerCase(),
        checkbox = $('.participants-checkbox-block input[type="checkbox"]:checked');

        if(text === ''){
            $('.participants-available').html('');
        }
        else if(!checkbox.length>0){
            showError('Выберите границу поиска');
            return;
        }
        else{
            $.post('/ajax/event.php?get_participants', {text : text, field : checkbox.attr('data-field')})
            .done(function(data){
                if(data.participants && data.participants.length > 0){
                    handleEventParticipants(data.participants);
                }
                else{
                    $('.participants-available').html('');
                }
            });
        }
    });

    $('.search-reg-member').keyup(function(){
        var text = $(this).val().trim().toLocaleLowerCase();

        if(text === ''){
            $('.reg-members-available').html('');
        }
        else{
            $.post('/ajax/event.php?members_for_registration', {text : text})
            .done(function(data){
                if(data.members && data.members.length > 0){
                    handleAdminsList(data.members);
                }
                else{
                    $('.reg-members-available').html('');
                }
            });
        }
    });
});

function handleBirthDateAndCategoryFields(){
    var noEvent = location.pathname.split('.')[0] === '/members';
    var categoryKey = $(".emCategory").val();
    var birthDate = $(".emBirthdate").val();

    if(noEvent && ( parseDate(birthDate)!==null || categoryKey !== '_none_')){
        var dateParts = birthDate.split(".");
        var date = new Date(dateParts[2], (dateParts[1] - 1), dateParts[0]);

        var schoolStart = $(".emSchoolStart").val(),
            schoolEnd = $(".emSchoolEnd").val(),
            collegeStart = $(".emCollegeStart").val(),
            collegeEnd = $(".emCollegeEnd").val(),
            college = $(".emCollege").attr('data-college'),

            collegeFullName = $(".emCollege").val(),
            indexFirstWhiteSpace = collegeFullName.indexOf(' '),
            collegeShortName = collegeFullName.substring(0, indexFirstWhiteSpace),
            collegeName = collegeFullName.substring(indexFirstWhiteSpace),

            collegeComment = $(".emCollegeComment").val();

        handleSchoolAndCollegeFields(getAge(date), categoryKey, schoolStart, schoolEnd, collegeStart, collegeEnd, college, collegeComment, collegeName, collegeShortName);
    }
}
// about age start
function prepareGetAge(preparingDate) {
  var isDash;
  preparingDate[4] == '-' ? isDash = true : sDash = false;
  if (isDash == true) {
    preparingDate = preparingDate.split('-');
    preparingDate = new Date(preparingDate[0], (preparingDate[1] - 1), preparingDate[2]);
    return  preparingDate;
  } else {
    preparingDate = preparingDate.split('.');
    preparingDate = new Date(preparingDate[2], (preparingDate[1] - 1), preparingDate[0]);
    return  preparingDate;
  }

}
function getAge(birthDate, notTodayDate) {
  var today;
  if (notTodayDate) {
    today = notTodayDate
  } else {
    today = new Date();
  }
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
}
function checkAgeLimit(classEvent,attrStartEventDate, isSelfRegistration) {
  var c = $(classEvent).attr('data-min_age');
  var e = $(classEvent).attr('data-max_age');
  var k;
  isSelfRegistration ? k = 01 : k = $('.emStatus').val();

  if ((c != 0 || e != 0) && ($(".emCategory").val() != 'FS' && $(".emCategory").val() != 'RB') && k == 01) {
    var h = prepareGetAge($(classEvent).attr(attrStartEventDate))
    var f = getAge(prepareGetAge($(".emBirthdate").val()), h);
    if (c != 0 && f < c) {
      showError('Возраст участника '+f+'. Мероприятие доступно для святых не младше ' + c + ' лет.', true);
      return false;
    } else if (e != 0 && f > e) {
      showError('Возраст участника '+f+'. Мероприятие доступно для святых не старше ' + e + ' лет.', true);
      return false;
    } else {
        return true;
    }
  }  else {
    return true;
  }
}
// about age end
function messageBox (html, parent) {
    window.messageBoxParent = parent;
    $("#modalMessageBox h4").html (html);
    $("#modalMessageBox").modal ('show');
}

function closeMessageBox(){
    $('#messageAdmins').modal('hide');
    $('#modalEventSendMsg').modal('hide');
    $('#messageAdmins textarea').val('');
    $('#modalEventSendMsg textarea').val('');
}

function closePopup(){
    $("#modalMessageBox").modal('hide');
}

$(this).keydown(function(e){
    if(e.which == 27){
        e.stopPropagation();
        closeModalStat(e);
        closeMessageBox();
    }
});

function htmlLabelByRegState (regstate, web) {
    var labelClass="", labelText="не зарегистрирован";
    if (regstate){
        switch (regstate){
            case '01': labelText='ожидание подтверждения';labelClass='label-warning';break;
            case '02': labelText='ожидание подтверждения';labelClass='label-warning';break;
            case '03': labelText='ожидание отмены';labelClass='label-warning';break;
            case '04': labelText='регистрация подтверждена';labelClass='label-success';break;
            case '05': labelText='регистрация отменена';labelClass='label-important';break;
        }
    }
    return '<span  class="'+ ( web == '1' ? " btnHandleRegstate " : "") + ' label '+labelClass+'" data-regstate="'+(regstate || 0 )+'">' + labelText + '</span>';
}

function htmlListItemsByRegstate (regstate, attended){
    var listItems = '';
        switch (regstate){
            case '03': listItems =
                        (attended == '1' ? '<li class="handle-regstate" data-action="07" ><a href="#">Отменить прибытие</a></li>' : '')  +
                        '<li class="handle-regstate" data-action="05" ><a href="#">Подтвердить отмену</a></li>'+
                        '<li class="handle-regstate" data-action="06" ><a href="#">Отметить прибывшим</a></li>';
                break;
            case '04': listItems =
                        (attended == '1' ? '<li class="handle-regstate" data-action="07" ><a href="#">Отменить прибытие</a></li>' : '')  +
                        '<li class="handle-regstate" data-action="03"><a href="#">Отменить</a></li>'+
                        '<li class="handle-regstate" data-action="06" ><a href="#">Отметить прибывшим</a></li>';
                break;
            case '05': listItems =
                        (attended == '1' ? '<li class="handle-regstate" data-action="07" ><a href="#">Отменить прибытие</a></li>' : '')  +
                        '<li class="handle-regstate" data-action="06"><a href="#">Отметить прибывшим</a></li>';
                break;
            default:
                listItems =
                        (attended == '1' ? '<li class="handle-regstate" data-action="07" ><a href="#">Отменить прибытие</a></li>' : '')  +
                        '<li class="handle-regstate" data-action="04" ><a href="#">Подтвердить</a></li>'+
                        '<li class="handle-regstate" data-action="03"><a href="#">Отменить</a></li>'+
                        '<li class="handle-regstate" data-action="06"><a href="#">Отметить прибывшим</a></li>';
                break;
        }

    return listItems;
}

function handleFieldsByAdminRole(adminRole, isEventPrivate, regstate){
    $("#forAdminRegNotice").text('');
    $("#btnDoRegisterMember").hide ();
    if(adminRole === 0){
        $(".role-send-msg, .role-admin, .role-edit").css('display','none');
    }
    else if(adminRole === 1 && isEventPrivate){
        $(".role-send-msg, .role-admin").css('display','none');
        $(".role-edit").css('display','inline-block');
        if (!regstate) {
          $("#forAdminRegNotice").text('Заявку на регистрацию на это мероприятие отправит ответственный за регистрацию в вашем регионе.');
        }
    }
    else{
        $(".role-send-msg, .role-admin, .role-edit").css('display','inline-block');
        if (!regstate || regstate=='05'){
            $("#btnDoRegisterMember").show ();
        }
        else{
            $("#btnDoRegisterMember").hide ();
        }
    }
}

function rebuildLocationsList(localities, selectedItem, arr){
    for( var l in localities){
        if(l.trim() !== ''){
            arr.push("<option value='"+l+"' "+ (selectedItem === l ? ' selected ': '') +">"+he (localities[l])+"</option>");
        }
    }
    return arr;
}

function rebuildLocationsListForInput(localities, selectedItem, arr){
    for( var l in localities){
        if(l.trim() !== ''){
            arr.push("<div class='listItemLocality' data-value='"+l+"'>"+he (localities[l])+"</div>");
        }
    }
    return arr;
}
// START autocomplete locality fieild
function showBlankEvents(zero) {
  var tempVarLocality, tempVarLocalityValue;
  zero ? tempVarLocalityText = '' : tempVarLocalityText = $('.emLocality option:selected').text();
  zero ? tempVarLocalityValue = '' : tempVarLocalityValue = $('.emLocality option:selected').val();
  tempVarLocalityValue === '_none_' ? tempVarLocalityText = '': '';
  $('#inputEmLocalityId').val(tempVarLocalityText);
  $('.emLocality').attr('data-value',tempVarLocalityValue);
  $('.emLocality').attr('data-text',tempVarLocalityText);
  $('#inputEmLocalityId').attr('data-value_input',tempVarLocalityValue);
  $('#inputEmLocalityId').attr('data-text_input',tempVarLocalityText);
}
function clearNewLocalityFieldByInput() {
  var el = $(".emNewLocality");
  if (el.val () != ""){
      el.val ("").removeAttr ("disabled").next (".unblock-input").hide ();
  }
}
function inputSelectParallels() {
  var a = $('#inputEmLocalityId').val();
  var counter = 0;

    $('.emLocality option').each(function () {
      var b = this.text;
      if (b == a) {
        counter++;
        var c = this.value;
        $('.emLocality').val(c);
        $('.emLocality').attr('data-value',c);
        $('.emLocality').attr('data-text',b);
        $('#inputEmLocalityId').attr('data-value_input',c);
        $('#inputEmLocalityId').attr('data-text_input',b);
      }
    });
    if (counter === 0) {
      $('.emLocality').val('_none_');
      $('.emLocality').attr('data-value','');
      $('.emLocality').attr('data-text','');
      $('#inputEmLocalityId').attr('data-value_input','');
      $('#inputEmLocalityId').attr('data-text_input','');
    }
}
function checkLocalityFieldsBlankAndKartochka() {
  setFieldError ($("#inputEmLocalityId"),
      $("#inputEmLocalityId").parents (".controls").css('display')!='none' &&
      $("#inputEmLocalityId").parents (isTabletWidth ? ".controls" : ".control-group").css('visibility')!='hidden' &&
      $("#inputEmLocalityId").attr('disabled')!='disabled' &&
      (!$("#modalEditMember").find(".emLocality").val() || $("#modalEditMember").find(".emLocality").val()=="_none_"));
  setFieldError ($(".emNewLocality"),
      $(".emNewLocality").parents (".controls").css('display')!='none' &&
      $(".emNewLocality").parents (isTabletWidth ? ".controls" : ".control-group").css('visibility')!='hidden' &&
      $(".emNewLocality").attr('disabled')!='disabled' &&
      (!$(".emNewLocality").val() || $("#modalEditMember").find(".emLocality").val()=="_none_"));
}
/*function clearNewLocalityField() {
  var el = $(".emNewLocality");
  if (el.val () != ""){
      el.val ("").removeAttr ("disabled").next (".unblock-input").hide ();
      $(".emAddress").focus();
  }
}*/
// STOP autocomplete locality fieild

function sortedFields(){
    var sort_type = 'desc',
        sort_field = 'name',
        searchText = $('.search-text').val(),
        el = $(($(document).width()>768 ? ".desctopVisible" : ".show-phone") + " a[id|='sort']");

    el.each (function (i){
        if ($(this).siblings("i.icon-chevron-down").length) {
            sort_type = 'asc';
            sort_field = $(this).attr("id").replace(/^sort-/,'');
        }
        else if ($(this).siblings("i.icon-chevron-up").length)
        {
            sort_type = 'desc';
            sort_field = $(this).attr("id").replace(/^sort-/,'');
        }
    });

    var locality = $("#selMemberLocality").val();
    var category = $("#selMemberCategory").val();
    if (locality == '_all_') locality = null;
    if (category == '_all_') category = null;

    return { sort_field:sort_field, sort_type:sort_type, locality:locality, category:category,  searchText :searchText}
}

function getValuesRegformFields(form, isIndexPage, isInvitation){
    var page = location.pathname.split('.')[0];
        page = page === '/youth' ? '/members' : page;

    return{
        event: window.currentEventId,
        name: form.find(".emName").val (),
        address: form.find(".emAddress").val(),
        arr_date: parseDDMM (form.find(".emArrDate").val(), new Date (form.find(".tooltipArrDate").data ('date'))),
        dep_date: parseDDMM (form.find(".emDepDate").val(), new Date (form.find(".tooltipDepDate").data ('date'))),
        arr_time: parseTime (form.find(".emArrTime").val()),
        dep_time: parseTime (form.find(".emDepTime").val()),
        birth_date: form.find(".emBirthdate").val(),
        cell_phone: form.find(".emCellPhone").val(),
        comment: page !== '/reg' && page !== '/members' && page !== '/admin' ? form.find(".emUserComment").val() : form.find(".emComment").val(),
        email: form.find(".emEmail").val(),

        locality_key: (page !== '/members' && page !== '/reg' && page !== '/admin' || isIndexPage) ? (window.selLocName == form.find(".emNewLocality").val() ? window.selLocId : '') : (form.find(".emLocality").val () === "_none_" ? (window.selLocName == form.find(".emNewLocality").val() ? window.selLocId : '') : form.find(".emLocality").val()),
        new_locality: (page !== '/members' && page !== '/reg' && page !== '/admin' || isIndexPage) ? (window.selLocName == form.find(".emNewLocality").val() ? '' : form.find(".emNewLocality").val()) : ( form.find(".emLocality").val () === "_none_" ? form.find(".emNewLocality").val() : ''),

        category_key: form.find(".emCategory").val () === "_none_" ? "" : form.find(".emCategory").val (),
        document_num: form.find(".emDocumentNum").val(),
        document_date: parseDate(form.find(".emDocumentDate").val()),
        document_auth: form.find(".emDocumentAuth").val(),
        document_key: form.find(".emDocumentType").val() === "_none_" ? "" : form.find(".emDocumentType").val (),

        tp_num: form.find(".emDocumentNumTp").val(),
        tp_date: parseDate(form.find(".emDocumentDateTp").val()),
        tp_auth: form.find(".emDocumentAuthTp").val(),
        tp_name: form.find(".emDocumentNameTp").val(),

        english_level: form.find(".emEnglishLevel").val() ? form.find(".emEnglishLevel").val() === "_none_" ? "" : form.find(".emEnglishLevel").val() : '_dont_change_',
        flight_num_arr: form.find(".emFlightNumArr").val(),
        flight_num_dep: form.find(".emFlightNumDep").val(),
        visa : form.find('.emVisa').val() === '_none_' ? 0 : form.find('.emVisa').val(),
        note: form.find(".emFlightNote").val(),

        status_key: form.find(".emStatus").val () == "_none_" ? "" : form.find(".emStatus").val(),
        gender: form.find('.emGender').val(),
        mate_key: form.find(".emMate").val() == "_none_" ? "" : form.find(".emMate").val(),
        coord: form.find(".emCoord").val() ?  form.find(".emCoord").val() : 0,
        accom: form.find(".emAccom").val() == "_none_" ? "" : form.find(".emAccom").val(),
        transport: form.find(".emTransport").val() == "_none_" ? "" : form.find(".emTransport").val(),
        parking: form.find(".emParking").val() == "_none_" ? "" : form.find(".emParking").val(),
        avtomobile: form.find(".emAvtomobile").val() ? form.find(".emAvtomobile").val() : "",
        avtomobile_number : form.find(".emAvtomobileNumber").val() ? form.find(".emAvtomobileNumber").val() : "",
        citizenship_key: form.find(".emCitizenship").val() == "_none_" ? "" : form.find(".emCitizenship").val(),
        prepaid: form.find(".emPrepaid").val (),
        currency: form.find(".emCurrency").val () == "_none_" ? "" : form.find(".emCurrency").val(),
        service_key: form.find(".emService").val () == "_none_" ? "" : form.find(".emService").val(),

        aid : form.find('.emAid').val(),
        contr_amount : form.find('.emAid').val() < 1 ? 0 : form.find('.emContrAmount').val(),
        trans_amount : form.find('.emAid').val() < 1 ? 0 : form.find('.emTransAmount').val(),
        fellowship : form.find('.emAid').val() < 1 ? 0 : form.find('.emFellowship').val(),

        russian_lg : form.find(".emRussianLanguage").val(),

        sortedFields : sortedFields(),

        schoolStart: form.find('.emSchoolStart').val(),
        schoolEnd : form.find('.emSchoolEnd').val(),
        collegeStart : form.find('.emCollegeStart').val(),
        collegeEnd : form.find('.emCollegeEnd').val(),
        //college : form.find('.emCollege').val() == "_none_" ? "" : form.find('.emCollege').val(),
        college : form.find('.emCollege').attr('data-college') ? form.find('.emCollege').attr('data-college') : "" ,
        collegeComment : form.find('.emCollegeComment').val(),
        school_comment : form.find(".emSchoolComment").val(),
        member: window.currentEditMemberId,
        baptized : parseDate (form.find(".emBaptized").val()),
        page : page !== '/members' && page !== '/reg' && page !== '/admin' ? '/login' : page,

        termsUse : form.find('#terms-use-checkbox').prop('checked'),
        isInvitation : isInvitation ? 1 : 0,
        regListName: form.find('.custom-list').val()
    }
}

function handleSchoolAndCollegeFields(age, categoryKey, schoolStart, schoolEnd, collegeStart, collegeEnd, college, collegeComment, collegeName, collegeShortName){
    var isSchool = categoryKey === "SC" || categoryKey === "PS" || ( !isNaN(age) && age < 18 && age > 6 );
    var isCollege = categoryKey === "ST" || ( categoryKey === "SC" && !isNaN(age) && age > 15 ) || (!isNaN(age) && age > 18 && age < 25);

    $('.school-fields').css('display', isSchool ? 'block' : 'none');
    $('.college-fields').css('display', isCollege ?'block' : 'none');
    var currentYear = new Date().getFullYear();

    if(isSchool){
        $('.emSchoolStart').val(schoolStart>0 ? schoolStart : '');
        $('.emSchoolEnd').val(schoolEnd>0 ? schoolEnd : '');
        var classLevel = schoolStart && schoolStart.length === 4 ? currentYear - schoolStart + 1 : '';
        $('.emClassLevel').html(classLevel > 0 && classLevel < 12 ? '('+classLevel+' класс)' : '');
        if(classLevel >= 9){
            $('.college-fields').css('display', 'block');
        }
    }

    if(isCollege){
        $('.emCollegeStart').val(collegeStart >0 ? collegeStart : '');
        $('.emCollegeEnd').val(collegeEnd >0 ? collegeEnd : '');
        $('.emCollege').val(collegeShortName && collegeName ? collegeShortName + ' ('+collegeName+')' : '');
        $('.emCollege').attr('data-college', college);
        $('.emCollegeComment').val(collegeComment || '');

        currentYear = parseInt(currentYear);
        var startCollege = collegeStart && collegeStart.length === 4 ? parseInt(collegeStart) : null ;
        var endCollege = collegeEnd && collegeEnd.length === 4 ? parseInt(collegeEnd) : null ;
        var courseLevel = startCollege ? currentYear - startCollege + 1 : null;

        if(startCollege && endCollege){
            if(currentYear < startCollege){
                courseLevel = "планирует поступить";
            }
            else if(currentYear === endCollege){
                var currentMonth = new Date().getMonth();
                courseLevel = currentMonth >= 6 ? "обучение завершено" : courseLevel + " курс, окончание в этом году";
            }
            else if(currentYear > endCollege){
                courseLevel = "учёба завершена";
            }
            else{
                courseLevel = courseLevel+" курс";
            }
        }

        $('.emCourseLevel').html(courseLevel ? '('+courseLevel+')' : '');
    }
}

function fillEditMember (memberId, info, localities, newMemberBlank) {
    window.currentEditMemberId = memberId;
    var windowWidth = $(document).width() < 980, age = parseInt(info['age']);
    var formEl = $('#modalEditMember');
    var arr = [], arr2 = [];
    /*for (l in localities) {
      arr2.push(localities[l]);
    }*/
    arr.push("<option value='_none_' selected>&nbsp;</option>");
    $(".emLocality").html(rebuildLocationsList(localities, '', arr));
    $(".inputEmLocality").html(rebuildLocationsListForInput(localities, '', arr2));

    $(".emBaptized").val(info['baptized'] ? formatDate(info['baptized']) : '');

    if(info['country_key'] === 'UA'){
        formEl.find('.emContrAmount').val(info['contr_amount']);
        formEl.find('.emTransAmount').val(info['trans_amount']);
        formEl.find('.emFellowship').val(info['fellowship']);

        displayAidFields(windowWidth, formEl, info['aid']);
        formEl.find('.needAid').css('display', 'block');
    }
    else{
        formEl.find('.emAid').val(0);
        formEl.find('.emContrAmount').val(0);
        formEl.find('.emTransAmount').val(0);
        formEl.find('.emFellowship').val(0);
        $('.needAid').css('display', 'none');
    }

    if (info["need_prepayment"]>0){
        $(".contrib-block, .prepaid-block, .currency-block").show();
    }
    else{
        $(".contrib-block, .prepaid-block, .currency-block").hide();
    }

    if (info["need_status"]>0){
        $(".emStatusLabel").show();
        $(".emStatus").show();
    }
    else{
        $(".emStatus").hide();
        $(".emStatusLabel").hide();
    }

    if (info["need_address"] == 0 && location.pathname.split('.')[0] !== '/members'){
        $(".address_block").css('display', 'none');
    }
    else{
        $(".address_block").css('display', 'block');
    }

    if (info["need_flight"]>0){
        $(".flight-info").show ();
        $(".emVisa").val(info["visa"] && info["visa"] !== '0' ? info["visa"] : "_none_").change();
    }
    else{
        $(".flight-info").hide ();
    }

    $(".emEnglishLevel").val (info["english"] !== null ? info["english"] : "_none_").change();

    if (info["need_tp"]>0) {
        $(".tp-passport-info").show ();
    }
    else {
        $(".tp-passport-info").hide ();
    }

    if (info["need_parking"]>0) {
        $('.parking').show();
    }
    else {
        $('.parking').hide();
    }

    if (info["need_service"]>0) {
        $('.service-block').show();
    }
    else {
        $('.service-block').hide();
    }

    if (info["need_accom"]>0) {
        $('.accom-block').show();
    }
    else {
        $('.accom-block').hide();
    }

    if(info["list_name"] && info["list_name"] != ''){
        $('.custom-block').show();
        $('.custom-label').text(info["list_name"]);

        var list = info["list"].split(';'),
            htmlList = "<option value='_none_' selected>&nbsp;</option>";

        for (l in list){
            if(list[l]){
                htmlList += "<option value='" + list[l] + "' " + (list[l] === info["reg_list_name"] ? 'selected' : '') +" >" + list[l] + "</option>";
            }
        }

        $('.custom-list').html(htmlList);
    }
    else{
        $('.custom-label').text('');
        $('.custom-list').html('');
        $('.custom-block').hide();
    }

    if (info["need_transport"]>0){
        $(".transportText").text( info["need_flight"]>0 ? "Транспорт" : "Транспорт");
        // $(".transportText").text( info["need_flight"]>0 ? "Поездка" : "Транспорт");
        $("#lblTransport .example").text(info["need_flight"]>0 ? "До места проведения мероприятия" : "До места проведения мероприятия");
        //$(".transportHint").attr("title", info["need_flight"]>0 ? "Групповая поездка до или после мероприятия" : "Для проезда от места проживания к залу собраний");
        $("#lblTransport, .grpTransport").css ("display", "block");
    }
    else{
        //if(windowWidth){
            $("#lblTransport, .grpTransport").css ("display", "none");
        //}
        /*else{
            $("#lblTransport").css("display", "block").css("visibility", "hidden");
            $(".grpTransport").css("display", "block").css ("visibility", "hidden");
        }
        */
    }

    if (info["need_passport"]>0) {
        $(".passport-info").show ();
        $(".grpPassport").show();
    }
    else{
        $(".passport-info").hide ();
        $(".grpPassport").hide();
    }

    if (info["need_flight"] == 0 && info["need_tp"] == 0 && info["need_passport"] == 0){
        $(".grpPassport").show();
    }

    if (!info["regstate_key"] || info["regstate_key"]=='05'){
        $("#btnDoRegisterMember").show ();
        // displayBtnDoSaveMember(info["regstate_key"] === undefined);
        displayBtnDoSaveMember(false);
    }
    else{
        $("#btnDoRegisterMember").hide ();
        displayBtnDoSaveMember(true);
    }

    $(".emFlightNumArr").val (info["flight_num_arr"] ? info["flight_num_arr"] : "").keyup();
    $(".emFlightNumDep").val (info["flight_num_dep"] ? info["flight_num_dep"] : "").keyup();
    $(".emFlightNote").val (info["note"] ? info["note"] : "").keyup();


    $(".emDocumentNumTp").val (info["tp_num"] ? info["tp_num"] : "").keyup();
    $(".emDocumentDateTp").val (info["tp_date"] ? formatDate (info["tp_date"]) : "").keyup();
    $(".emDocumentAuthTp").val (info["tp_auth"] ? info["tp_auth"] : "").keyup();
    $(".emDocumentNameTp").val (info["tp_name"] ? info["tp_name"] : "").keyup();

    if(info["need_address"] > 0) {
        $(".emAddress").val (info["address"] ? info["address"] : "").keyup();
    }
    else {
        $(".emAddress").val (info["address"] ? info["address"] : "");
    }

    $(".emArrDate").val (info["arr_date"] ? formatDDMM (info["arr_date"]) : "").attr('data-double_date', info["arr_date"]).keyup();
    $(".emArrTime").val (info["arr_time"] ? formatTime (info["arr_time"]) : "").keyup();
    $(".emBirthdate").val (info["birth_date"] ? info["birth_date"] : "").keyup();
    $(".emCellPhone").val (info["cell_phone"] ? info["cell_phone"] : "");
    //$(".emTempPhone").val (info["temp_phone"] ? info["temp_phone"] : "");
    $(".emDepDate").val (info["dep_date"] ? formatDDMM (info["dep_date"]) : "").attr('data-double_date', info["dep_date"]).keyup();
    $(".emDepTime").val (info["dep_time"] ? formatTime (info["dep_time"]) : "").keyup();
    $(".emEmail").val (info["email"] ? info["email"] : "").keyup();
    //$(".emHomePhone").val (info["home_phone"] ? info["home_phone"] : "");

    if ($(".emLocality").size()==0){

        // GUEST
        if (info["locality_key"]){
            window.selLocId = info["locality_key"];
            window.selLocName = info["locality_name"];
            $(".emNewLocality").val (info["locality_name"]).attr ("disabled", "disabled").next (".unblock-input").show ();
        }
        else{
            window.selLocId = "";
            window.selLocName = "";
            $(".emNewLocality").val (info["new_locality"] ? info["new_locality"] : "").removeAttr ("disabled").next (".unblock-input").hide ();
        }
    	if ($(".emNewLocality").val ()=="") $(".emNewLocality").keyup();
        //$(".emComment").val (info["admin_comment"] ? info["admin_comment"] : "");
        $(".emUserComment").val (info["comment"] ? info["comment"] : "");
        $("#emShowSharedComment").hide();
    }
    else{
    	// ADMIN
      if (newMemberBlank) {
        $('#modalEditMember').find('.emLocality').attr('style', 'background-color: #FCF4F4; border-color:#E08A88;')
      } else {
        $(".emLocality").val (info["locality_key"] ? info["locality_key"] : "_none_").change();
      }
        $(".emNewLocality").val (info["new_locality"] ? info["new_locality"] : "").removeAttr ("disabled").next (".unblock-input").hide ();
        if(!info["locality_key"]) $(".emNewLocality").val (info["new_locality"] ? info["new_locality"] : "").keyup();
        $(".emComment").val (info["admin_comment"] ? info["admin_comment"] : "");
        $(".emUserComment").val (info["comment"] ? info["comment"] : "").attr('disabled','disabled');

        /*
        if (info["comment"]){
            $("#sharedCommentText").text(he(info["comment"]));
            $("#emShowSharedComment").show();
            $("#emNoSharedComment").hide();
        }
        else{
            $("#emShowSharedComment").hide();
            $("#emNoSharedComment").show();
        }
        */

    }

    $(".block-new-locality").css("display", info["new_locality"] ? 'block' : 'none');
    $(".handle-new-locality").css("display", info["new_locality"] ? 'none' : 'block');

    $(".emRussianLanguage").val (info["russian_lg"] ? info["russian_lg"] : 1);
    $(".emCitizenship").val (info["citizenship_key"] ? info["citizenship_key"] : "_none_").change();
    $(".emDocumentNum").val (info["document_num"] ? info["document_num"] : "").keyup();
    $(".emDocumentDate").val (info["document_date"] ? formatDate (info["document_date"]) : "").keyup();
    $(".emDocumentAuth").val (info["document_auth"] ? info["document_auth"] : "").keyup();
    $(".emCategory").val (info["category_key"] ? info["category_key"] : "_none_").change();
    $(".emDocumentType").val (info["document_key"] ? info["document_key"] : "_none_").change();
    $(".tooltipArrDate").attr('title', "День и месяц приезда. Мероприятие начинается "+formatDate (info["start_date"])).tooltip('fixTitle').data ('date', info["start_date"]);
    $(".tooltipDepDate").attr('title', "День и месяц отъезда. Мероприятие заканчивается "+formatDate (info["end_date"])).tooltip('fixTitle').data ('date', info["end_date"]);

    if (info["gender"]) $('.emGender').val (info["gender"]); else $('.emGender').val ("_none_");
    $(".emGender").change();

    $(".emStatus").val (info["status_key"] ? info["status_key"] : "01" /* "_none_" */).change();

    if (info["accom"]) $('.emAccom').val (info["accom"]>0 ? "1" : "0"); else $('.emAccom').val ("_none_");
    $(".emAccom").change();

    $(".emAccom").change(function(){
        var val = $(this).val();

        if(val == 0){
            $(".emMate").val('_none_').attr('disabled', true);
        }
        else{
            $(".emMate").val('_none_').attr('disabled', false);
        }

    });

    if ($(".emMate").length){
        var emMateHtml = "<option value='_none_'>&nbsp;</option>", mateArr = [];

        $(".tab-pane.active " + (document.documentElement.clientWidth < 751 ? '.show-phone' : '.desctopVisible' ) + " table.reg-list tr[class|='regmem']").each (function (){
            var id = $(this).attr ('class').replace(/^regmem-/,'');
            if (id.length>2 && id.substr (0,2)!="99" && id!=memberId){
                var name = $(this).find('.mname1').text() + " (" + $(this).find('.mnameCategory').text() + ")";
                mateArr.push({'id': he(id), 'name' : he(name)});
                //emMateHtml+="<option value='"+he(id)+"'>"+he(name)+"</option>";
            }
        });

        mateArr.sort(function (a, b) {
            if (a.name < b.name) {
              return -1;
            }
            if (a.name > b.name) {
              return 1;
            }
            return 0;
        });

        for(var i in mateArr){
            var mate = mateArr[i];
            emMateHtml+="<option value='"+he(mate.id)+"'>"+he(mate.name)+"</option>";
        }

        $(".emMate").html (emMateHtml).val (info["mate_key"] ? info["mate_key"] : "_none_");
    }

    $(".emName").val (info["name"] ? info["name"] : "" ).keyup();

    if (info["name"] && info["name"].length>0){
        $(".emName").attr ("disabled", "disabled").next (".unblock-input").show ();
        //$("#tooltipNameHelp").hide();
    }
    else {
        $(".emName").removeAttr ("disabled").next (".unblock-input").hide ();
        //$("#tooltipNameHelp").show();
    }

    handleSchoolAndCollegeFields(age, info['category_key'], info['school_start'], info['school_end'],
            info['college_start'], info['college_end'], info['college_key'], info['college_comment'], info['college_name'], info['college_short_name']);

    $("#tooltipNameHelp").hide();

    if (info["transport"]) $('.emTransport').val (info["transport"]>0 ? "1" : "0"); else $('.emTransport').val ("_none_");
    $(".emTransport").change();

    if (info["need_parking"] > 0) {
        if(info["parking"] > 0){
            $('.emParking').val ("1");
            $('.emAvtomobileNumber').val (info["avtomobile_number"]);
            $('.emAvtomobile').val (info["avtomobile"]);
            $('.emAvtomobileNumber, .emAvtomobile').attr('disabled', false).attr('valid', 'required');
        }
        else{
            $('.emParking').val ("0");
            $('.emAvtomobileNumber').val (info["avtomobile_number"]);
            $('.emAvtomobile').val (info["avtomobile"]);
            $('.emAvtomobileNumber, .emAvtomobile').attr('disabled', true).attr('valid', '');
        }
        $(".emParking").change();
        $('.emAvtomobileNumber, .emAvtomobile').keyup();
    }
    else {
        $('.emParking').val ("0");
    }

    $(".emParking").change(function(){
        if($(this).val() == 1){
            $('.emAvtomobileNumber, .emAvtomobile').attr('disabled', false).attr('valid', 'required');
        }
        else{
            $('.emAvtomobileNumber, .emAvtomobile').attr('disabled', true).attr('valid', '');
        }
        $('.emAvtomobileNumber, .emAvtomobile').keyup();
    });

    //if (info["need_flight"]== 0){
    //    $(".emParking").change();
    //}

    $('.emCoord').val (info["coord"]>0 ? '1' : '0');

    if (info["currency"]) $('.currency').html (" (" + info["currency"] + ") "); else $('.currency').html ("");
    if (info["currency"]) $('.emCurrency').val (info["currency"]); else $('.emCurrency').val ("_none_");
    $(".emCurrency").change();

    $(".emPrepaid").val (info["prepaid"]>0 ? info["prepaid"] : "" ).keyup();
    $(".emContrib").val (info["contrib"]>0 ? info["contrib"] : "" ).keyup();

    if (info["service_key"]) $('.emService').val (info["service_key"]); else $('.emService').val ("_none_");
    $(".emService").change();

    if (info["attended"]>0){
        $("#eventMemberPlace").show ();
        $(".eventMemberArrived").text (info["place"]);
        $("#eventMemberPlaceService").show();
    }
    else {
        $("#eventMemberPlace").hide ();
        $(".eventMemberArrived").text ();
        $("#eventMemberPlaceService").hide();
    }

    var eventName = $('#eventTabs li.active a.dropdown-toggle span').text();
    if (!eventName) eventName = info["event_name"];

    $(".editMemberEventTitle").text (eventName);
    $(".eventMemberStatus").html (htmlLabelByRegState (info["regstate_key"]));

    window.permalink = info["permalink"];

    $("#txtPermalink").hide ();
    $("#lnkPermalink").text ("Показать ссылку");

    // if the administrator has already edited this form then the email field is not required for guests anymore
    /*
    if (info["reg_admin"])
    {
        $("#supEmailRequred").hide ();
        $(".emEmail").attr ('valid', 'email').keyup();
    }
    else
    {
        $("#supEmailRequred").show ();
        $(".emEmail").attr ('valid', window.isGuest ? 'required,email' : 'email').keyup();
    }
*/
    $(".emActive").prop('checked', info["active"]==1);

    /*
    $(".emLocality").change(function(){
        var locality = $(this).val().trim(), newLocality = $(".emNewLocality").val().trim();
        if(locality === '_none_' && newLocality !== ''){
            $(this).parents('.control-group').removeClass('error');
        }
        else if(locality !== '_none_' && newLocality === ''){
            $(".emNewLocality").parents('.control-group').removeClass('error');
        }

        checkLocalityFieldsFieldWithValue(locality, newLocality);
    });

    $(".emNewLocality").keyup(function(){
        checkLocalityFieldsFieldWithValue($(".emLocality").val().trim(), $(this).val().trim());
    });
    */

    $(".emNewLocality").keyup (function (){
        var el = $(".emLocality");
        var elVal = $("#inputEmLocalityId").val();
        var thisVal = $(this).val();
        if (el.val ()!="_none_" && window.selLocName != $(".emNewLocality").val() || elVal.lengh > 0){
            el.val ("_none_");
            showBlankEvents(true);
        }
        thisVal.length > 0 && elVal.length > 0 ? showBlankEvents(true) : '';
        setFieldError ($(this),
            $(this).parents (".controls").css('display')!='none' &&
            $(this).parents (isTabletWidth ? ".controls" : ".control-group").css('visibility')!='hidden' &&
            $(this).attr('disabled')!='disabled' &&
            (!$(this).val() || $("#modalEditMember").find(".emLocality").val()=="_none_"));

    });
    $(".listItemLocality").click(function () {
      var a = $(this).text();
      var b = $(this).attr('data-value');
      $("#inputEmLocalityId").val(a);
      $('.modalListInput').hide();
      inputSelectParallels();
      clearNewLocalityFieldByInput();
      checkLocalityFieldsBlankAndKartochka();
      $("#inputEmLocalityId").focus();
    });
}
/*
function checkLocalityFieldsFieldWithValue(locality, newLocality){
    if(locality !== '_none_' && newLocality !== ''){
        showError("Необходимо указать только одну местность!");
    }
}
*/

function setCookie(name, value, exdays){
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
    document.cookie=name + "=" + c_value;
}

function getCookie(name){
    var c_value = document.cookie;
    var c_start = c_value.indexOf(" " + name + "=");
    if (c_start == -1){
        c_start = c_value.indexOf(name + "=");
    }
    if (c_start == -1){
        c_value = null;
    }
    else{
        c_start = c_value.indexOf("=", c_start) + 1;
        var c_end = c_value.indexOf(";", c_start);
        if (c_end == -1)
        {
            c_end = c_value.length;
        }
        c_value = unescape(c_value.substring(c_start,c_end));
    }
    return c_value;
}

function setCookieNew(name, value, options) {
    options = options || {};

    var expires = options.expires;
    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);

    var updatedCookie = name + "=" + value;

    for (var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true){
            updatedCookie += "=" + propValue;
        }
    }

    document.cookie = updatedCookie;
}

$(document).click(function(event){
    event.stopPropagation();
    closeModalStat(event);
});

function closeModalStat(event){
    if ($(event.target).closest("#modalStatistic").length) {
        $('#modalStatistic').on('hidden.bs.modal', function() {
            $("#showStatistic").empty();
            $("#modalStatistic").modal('hide');
        });
    }
    else{
        $("#showStatistic").empty();
        $("#modalStatistic").modal('hide');
    }
}

function getAidInfo(stats, eventName, eventId){
    var tableRows = [], contrAmount = 0, transAmount = 0, aidPaid = 0, parsedAidPaid =0, unhandledUser = 0, str = 'Финансовая помощь не требуется!';
    $("#modalAidStatistic h4").html('<span>Информация о финансовой помощи - </span>' + '<span class="event-name">'+ eventName +'</span>').attr('data-event-id', eventId);

    if(stats.length > 0){
        $("#aidGeneralStatistic, #aidStatistic").css('display', 'block');
        $(".noAidNeed").css('display', 'none');
        stats.forEach(function(item){
            parsedAidPaid = parseInt(item.aid_paid);
            if(parsedAidPaid === 0){
                ++unhandledUser;
            }

            if(parsedAidPaid !== -1){
                aidPaid += parsedAidPaid;
                contrAmount += parseInt(item.contr_amount);
                transAmount += parseInt(item.trans_amount);
            }

            tableRows.push('<tr class="regmem-'+item.id + ( parsedAidPaid !== 0 ? " inactive-member" : " " ) +'" >'+
                '<td class="style-name mname">' + he(item.name) + '</td>' +
                '<td class="style-city">' + he(item.locality ? (item.locality.length>20 ? item.locality.substring(0,18)+'...' : item.locality) : '') + '</td>' +
                '<td class="contr_amount">'+ item.contr_amount +'</td>'+
                '<td class="trans_amount">'+ item.trans_amount +'</td>'+
                '<td class="aid_paid">'+ ( parsedAidPaid === -1 ? ' <span class="label label-important"> Отказано</span>' : parsedAidPaid ) +'</td>'+
                '<td><i class="fa fa-pencil fa-lg handleUserAid" title="Обработать данные"></i></td>'+
                '</tr>'
            );
        });

        str = '<tr>'+
            '<td>' + ( contrAmount + transAmount - aidPaid ) + '</td>' +
            '<td >' + aidPaid + '</td>' +
            '<td>'+ contrAmount +'</td>'+
            '<td>'+ transAmount +'</td>'+
            '<td>'+ unhandledUser +'</td>'+
            '</tr>';

        $("#aidGeneralStatistic tbody").html(str);
        $("#aidStatistic tbody").html(tableRows.join(''));
    }
    else{
        $(".noAidNeed").css('display', 'block').html('<h4 style="text-align: center">'+str+'</h4>');
        $("#aidGeneralStatistic, #aidStatistic").css('display', 'none');
    }

    $("#modalAidStatistic").modal('show');

    $('.handleUserAid').click(function(){
        var elem = $(this).parents("tr[class|='regmem']");
        var id = elem.attr ('class').replace(/^regmem-|inactive-member/,''), name = elem.find('.mname')[0].innerHTML;

        var str = '<tr data-eventid="'+eventId+'" data-event-name="'+eventName+'" class="mem_id-'+id+'">'+
            '<td>' + elem.find('.contr_amount')[0].innerHTML + '</td>' +
            '<td >' + elem.find('.trans_amount')[0].innerHTML + '</td>' +
            '<td>'+ elem.find('.aid_paid')[0].innerHTML +'</td>'+
            '</tr>';

        var modalEl = $('#modalUserAidInfo');
        setFieldError(modalEl.find('.aid-amount'), false);
        modalEl.find('.aid-amount').focus().val('');
        modalEl.find("h4").html(name);
        modalEl.find("#aidInfo tbody").html(str);
        modalEl.modal('show');
    });

    $('.downloadAidList').click(function(event){
        event.stopPropagation();

        var generalValues = [
            {
                amount : contrAmount + transAmount - aidPaid,
                field : 'Необходимая сумма'
            },
            {
                amount : contrAmount,
                field : 'На мероприятие'
            },
            {
                amount : transAmount,
                field : 'На транспорт'
            },
            {
                amount : unhandledUser,
                field : 'Необработанно'
            },
            {
                amount : aidPaid,
                field : 'Оказано помощи'
            }
        ];

        downloadAidListExcel(stats.length, stats, generalValues);
    });
}

function downloadAidListExcel(memberslength, members, generalValues){
    var req = "&memberslength="+memberslength+"&adminId="+window.adminId+"&page=reg_aid";
    var fields = ['contr_amount', 'trans_amount', 'aid_paid'];

    $.ajax({
        type: "POST",
        url: "/ajax/excelList.php",
        data: "members="+JSON.stringify(members)+req+'&fields='+fields+'&general_values='+JSON.stringify(generalValues),
        cache: false,
        success: function(data) {
            location.href="./ajax/excelList.php?file="+data;
            setTimeout(function(){
                deleteFile(data);
            }, 10000);
        }
    });
}

function helpFuncToHandleAidInteraction(that){
    var elem = $(that).parents("#modalUserAidInfo");
    var userId = elem.find("tr[class|='mem_id']").attr('class').replace(/^mem_id-/,'').replace(/inactive-member/,'');
    var eventId = elem.find("tr[class|='mem_id']").data('eventid');
    var eventName = elem.find("tr[class|='mem_id']").data('event-name');
    var amount = elem.find('.aid-amount').val();

    return{id:userId, amount: amount, eventId: eventId, eventName: eventName};
}

function handleAidInteraction(userId, amount, eventId, eventName){
    $.get("/ajax/set.php?member_id="+userId+"&amount="+amount+"&eventId="+eventId)
        .done (function(data) {
        getAidInfo(data.members, eventName, eventId);
        $("#modalUserAidInfo").modal('hide');
    });
}

function getStatistics(stats, eventName, localitiesLength, countParking, countTransport, countAccomSisters, countAccomBrothers, countBrothers, countSisters){
    var first = 0, second = 0, third = 0, forth = 0, fifth = 0, def  = 0, state;
    for(var i in stats){
        state = stats[i];
        switch (state){
            case '01': first++; break;
            case '02': second++; break;
            case '03': third++; break;
            case '04': forth++; break;
            case '05': fifth++; break;
            case 'null' : def++; break;
        }
    }

    var regWait = first + second;
    var responce = '<div class="statistic-block"><p>Регистрация подтверждена — '+(forth)+' чел.</p><p>Ожидание подтверждения — '+(regWait)+' чел.</p>' +
        '<p>Не зарегистрирован — '+(def)+' чел.</p>'+
        '<p><strong>Всего участников — '+(stats.length - third - fifth )+' чел.</strong></p>'+
        '<hr>'+
        '<p>Ожидание отмены — '+(third)+' чел.</p>'+
        '<p>Регистрация отменена — '+(fifth)+' чел.</p></div>';

    var additionalStatistic = '<div class="statistic-additional-block"><p>Зарегистрировано братьев  — ' + countBrothers +' чел.</p>' +
                                        '<p>Зарегистрировано сестёр  — ' + countSisters + ' чел.</p>' +
                                        '<p>Разместить — ' + countAccomBrothers + ' братьев и '+ countAccomSisters + ' сестёр</p>' +
                                        '<p><strong>Всего разместить — '+(parseInt(countAccomSisters) + parseInt(countAccomBrothers))+' чел.</strong></p>' +
                                        '<hr>'+
                                        '<p>Нужен транспорт — ' + countTransport +' чел.</p>' +
                                        '<p>Нужна парковка — ' + countParking +' м.</p></div>';

    $("#showStatistic").append(responce + additionalStatistic);
    $("#modalStatistic h5").text(eventName);
    $("#modalStatistic").find(".count-localities").html("<strong>Всего местностей — "+(localitiesLength)+"</strong>");
    $("#modalStatistic").modal('show');
}

function in_array(value, array){
    for(var i = 0; i < array.length; i++)
    {
        if(array[i] == value) return true;
    }
    return false;
}

function deleteFile(data){
    $.ajax({
        type: "POST",
        url: "/ajax/excelList.php",
        data: "data="+data,
        cache: false,
        success: function(data) {}
    });
}

function parseTimeTocheck(time){
    return time.substr(0,5);
}

function parseEventMemberDataToCheckChanges(info){
    return {
        accom : info["accom"]>0 ? "1" : "0",
        address : info["address"] ? info["address"] : "",
        admin_comment: info["admin_comment"] ? info["admin_comment"] : "",
        aid : info["aid"] ? info["aid"].toString() : "",
        arr_date : info["arr_date"] ? formatDDMM (info["arr_date"]) : "",
        arr_time : info["arr_time"] ? parseTimeTocheck (info["arr_time"]) : "",
        birth_date : info["birth_date"] ? formatDate (info["birth_date"]) : "",
        category_key : info["category_key"] ? info["category_key"] : "_none_",
        cell_phone : info["cell_phone"] ? info["cell_phone"] : "",
        citizenship_key : info["citizenship_key"] ? info["citizenship_key"] : "_none_",
        comment : info["comment"] ? info["comment"] : "",
        contr_amount : info['contr_amount'].toString(),
        coord : info["coord"]>0 ? '1' : '0',
        dep_date : info["dep_date"] ? formatDDMM (info["dep_date"]) : "",
        dep_time : info["dep_time"] ? parseTimeTocheck (info["dep_time"]) : "",
        document_date : info["document_date"] ? formatDate (info["document_date"]) : "",
        document_auth : info["document_auth"] ? info["document_auth"] : "",
        document_key : info["document_key"] ? info["document_key"] : "_none_",
        document_num : info["document_num"] ? info["document_num"] : "",
        email : info["email"] ? info["email"] : "",
        english : info["english"] ? info["english"].toString() : "_none_",
        fellowship : info['fellowship'] ? info['fellowship'].toString() : "0",
        flight_num_arr : info["flight_num_arr"] ? info["flight_num_arr"].toString() : "",
        flight_num_dep : info["flight_num_dep"] ? info["flight_num_dep"].toString() : "",
        gender : info["gender"] ? info["gender"].toString() : "_none_",
        home_phone : info["home_phone"] ? info["home_phone"] : "",
        locality_key : info["locality_key"] ? info["locality_key"] : "_none_",
        mate_key : info["mate_key"] ? info["mate_key"].toString() : "_none_",
        name : info["name"] ? info["name"] : "",
        new_locality : info["new_locality"] ? info["new_locality"] : "",
        note : info["note"] ? info["note"].toString() : "",
        parking : info["parking"]>0 ? "1" : "0",
        prepaid : info["prepaid"]>0 ? info["prepaid"].toString() : "",
        russian_lg : info["russian_lg"] ? info["russian_lg"].toString() : "1",
        service_key : info["service_key"] ? info["service_key"].toString() : "_none_",
        status_key : info["status_key"] ? info["status_key"] : "01",
        temp_phone : info["temp_phone"] ? info["temp_phone"] : "",
        tp_auth : info["tp_auth"] ? info["tp_auth"].toString() : "",
        tp_date : info["tp_date"] ? formatDate (info["tp_date"]) : "",
        tp_name : info["tp_name"] ? info["tp_name"].toString() : "",
        tp_num : info["tp_num"] ? info["tp_num"].toString() : "",
        trans_amount : info['trans_amount'].toString(),
        transport : info["transport"]>0 ? "1" : "0",
        visa : info["visa"] && info["visa"] !== '0' ? info["visa"].toString() : "_none_"
    }
}

function getAgeWithSuffix (parsedAge, age){
    var suffix = '';

    if(age){
        var end = parsedAge % 10;
        suffix = ' лет';
        if(end === 1 && parsedAge !== 11){
            suffix = " год";
        }
        else if((end === 2 && parsedAge !== 12) || (end === 3 && parsedAge !== 13) || ( end === 4 && parsedAge !== 14) ){
            suffix = " года";
        }
    }

    return age ? parsedAge + suffix : '—';
}

function getEventMemberColor(eventType){
    var eventColors = {'FCT':'#ee5f5b', 'SCC':'#b94a48','RYT':'#04c',
                'SWT':'#08c','TSC':'#5bc0de', 'RTS':'#62c462',
                'BLC':'#f89406', 'YPC':'#f80676','RBT':'#f80606',
                'CWT':'#f87606','CWF':'#b8a8e8', 'REC':'#dba8e8',
                'FCO':'#e8a8d1', 'STC':'#a8e8b6','STT':'#e8d7a8'};

    return eventColors[eventType];
}

$(".statOk, .statCancel").click(function(event){
    event.stopPropagation();
    $("#showStatistic").empty();
    $("#modalStatistic").modal('hide');
 });

 function isEmailvalid(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function bindHandlerToRemoveAdmin(){
    $('.removeRegMember').click(function(e){
        e.stopPropagation();
        var id = $(this).attr('data-id');

        if(in_array(id, window.regAdmins)){
            var index = window.regAdmins.indexOf(id);
            if (index > -1)
                window.regAdmins.splice(index, 1);
        }
        $(this).parents('div [data-id='+id+']').remove();
    });
}

function handleAdminsList(members, isEditMode){
    var list = buildListAdmins (members, isEditMode);
    !isEditMode ? $('.reg-members-available').html(list.join('')) : $('.reg-members-added').html(list.join(''));

    $('.addRegMember').click(function(e){
        e.stopPropagation();

        var element = $(this).parents('div');
        var id = element.attr('data-id');

        var isNewId = !in_array(id, window.regAdmins);
        var member = buildListAdmins([
            {id : id,
            name : element.attr('data-name'),
            locality: element.attr('data-locality'),
            email : element.attr('data-email')}], true);

        if(isNewId){
            $('.reg-members-added').append(member);
            $('.reg-members-available').html('');
            $('.search-reg-member').val('').focus();
        }
        else{
            showError('Этот администратор уже добавлен', true);
        }

        bindHandlerToRemoveAdmin();
    });

    bindHandlerToRemoveAdmin();
}

function buildListAdmins (members, isEditMode){
    var memberRows = [];
    for(var m in members){
        var member = members[m];

        if(isEditMode){
            window.regAdmins.push(member.id);
        }

        memberRows.push('<div data-id="'+member.id+'" data-name="'+member.name+'" data-locality="'+member.locality+'" data-email="'+member.email+'">'+
                            '<span>'+ member.name +'</span>'+
                            '<span> ('+member.locality+'</span> - '+
                            '<span>'+member.email+') </span> '+
                            '<i data-id="'+member.id+'" class="fa fa-lg '+ ( isEditMode ? "fa-times removeRegMember" : "fa-plus addRegMember") + '" '+
                            'title="' + ( isEditMode ? "Убрать " + member.name + " из списка ответственных за регистрацию" : "Назначить " + member.name + " ответственным за регистрацию" )+'">'+
                            '</i>'+
                        '</div>');
    }
    return memberRows;
}

function handleEventZones(zones, isEditMode){
    var list = buildListZones (zones, isEditMode);
    $(!isEditMode ? '.zones-available' : '.zones-added').html(list.join(''));

    $('.addZone').click(function(e){
        e.stopPropagation();

        var element = $(this).parents('div');
        var id = element.attr('data-id');

        var isNewId = !in_array(id, window.eventZones);
        var zone = buildListZones([{id : id, name : element.attr('data-name'), field : $('.zones-checkbox-block input[type="checkbox"]:checked').attr('data-field')}], true);

        if(isNewId){
            $('.zones-added').append(zone);
            $('.zones-available').html('');
            $('.search-zones').val('').focus();
        }
        else{
            showError('Эта зона уже добавлена', true);
        }

        bindHandlerToRemoveZone();
    });

    bindHandlerToRemoveZone();
}

function buildListZones (zones, isEditMode){
    var zonesRows = [];

    for(var m in zones){
        var zone = zones[m], field = zone.field;

        if(isEditMode){
            window.eventZones.push(zone.id);
        }

        zonesRows.push('<div data-id="'+zone.id+'" data-field="'+field+'" data-name="'+zone.name+'">'+
                            '<span>'+ zone.name +'</span> '+
                            '<i data-id="'+zone.id+'" class="fa fa-lg '+ ( isEditMode ? "fa-times removeZone" : "fa-plus addZone") + '" '+
                            'title="' + ( isEditMode ? "Убрать " + zone.name + " из зоны доступа" : "Добавить " + zone.name + " в зону доступа" )+'">'+
                            '</i>'+
                        '</div>');
    }
    return zonesRows;
}

function bindHandlerToRemoveZone(){
    $('.removeZone').click(function(e){
        e.stopPropagation();
        var id = $(this).attr('data-id');

        if(in_array(id, window.eventZones)){
            var index = window.eventZones.indexOf(id);
            if (index > -1)
                window.eventZones.splice(index, 1);
        }
        $(this).parents('div [data-id='+id+']').remove();
    });
}

function bindHandlerToRemoveParticipants(){
    $('.removeParticipant').click(function(e){
        e.stopPropagation();
        var id = $(this).attr('data-id');

        if(in_array(id, window.eventParticipants)){
            var index = window.eventParticipants.indexOf(id);
            if (index > -1)
                window.eventParticipants.splice(index, 1);
        }
        $(this).parents('div [data-id='+id+']').remove();
    });
}

function handleEventParticipants(participants, isEditMode){
    var list = buildParticipantList (participants, isEditMode);
    $(!isEditMode ? '.participants-available' : '.participants-added').html(list.join(''));

    $('.addParticipant').click(function(e){
        e.stopPropagation();

        var element = $(this).parents('div'), id = element.attr('data-id'), isNewId = !in_array(id, window.eventParticipants);
        var participant = buildParticipantList([{id : id, name : element.attr('data-name'), field : $('.participants-checkbox-block input[type="checkbox"]:checked').attr('data-field')}], true);

        if(isNewId){
            $('.participants-added').append(participant);
            $('.participants-available').html('');
            $('.search-participants').val('').focus();
        }
        else{
            showError('Эти участники уже добавлены', true);
        }

        bindHandlerToRemoveParticipants();
    });

    bindHandlerToRemoveParticipants();
}

function buildParticipantList (participants, isEditMode){
    var participantRows = [];

    for(var m in participants){
        var participant = participants[m], field = participant.field;

        if(isEditMode){
            window.eventParticipants.push(participant.id);
        }

        participantRows.push('<div data-id="'+participant.id+'" data-field="'+field+'" data-name="'+participant.name+'">'+
                            '<span>'+ participant.name +'</span> '+
                            '<i data-id="'+participant.id+'" class="fa fa-lg '+ ( isEditMode ? "fa-times removeParticipant" : "fa-plus addParticipant") + '" '+
                            'title="' + ( isEditMode ? "Убрать " + participant.name + " из участников" : "Добавить " + participant.name + " к участникам" )+'">'+
                            '</i>'+
                        '</div>');
    }
    return participantRows;
}

// empty admins array
window.regAdmins = [];
window.eventZones = [];
window.eventParticipants = [];

$(document).ready(function(){
  $(".members-lists-combo").change(function(){
      listsType = $(".members-lists-combo").val();
      switch (listsType) {
          case 'members': window.location = '/members'; break;
          case 'youth': window.location = '/youth'; break;
          case 'list': window.location = '/list'; break;
          case 'activity': window.location = '/activity'; break;
      }
  });
  $('.continue-closed-registration').click(function(){
      if (checkForRegEnd ('event-add-member')) $('#modalAddMembers').modal('show');
  });

  $('.openCollegesModal').click(function(){
      getColleges(false);
  });

  // START Locality and new locality fields behavior

          isTabletWidth = $(document).width() < 980;

    /*      function chchfields() {
            var a = $(".emNewLocality").val();
            var b = $(".emLocality").val();
            var e = $('.emLocality').attr('data-value');
            var f = $('#inputEmLocalityId').attr('data-value_input');

            if (a.length > 0 || e.length > 0) {
              $('#modalEditMember').find('#inputEmLocalityId').attr('style', 'background-color: none; border-color: gray;');
              $('#modalEditMember').find('.emNewLocality').attr('style', 'background-color: none; border-color: gray;');
              $('#modalEditMember').find('.block-new-locality').removeClass('error');
              $('#modalEditMember').find('.localityControlGroup').parent().removeClass('error');
            } else if (a.length < 1 && e.length < 1) {
              $('#modalEditMember').find('#inputEmLocalityId').attr('style', 'background-color: #FCF4F4; border-color:#E08A88;')
              $('#modalEditMember').find('.emNewLocality').attr('style', 'background-color: #FCF4F4; border-color:#E08A88;')
            }

            if (b === '_none_' && !$("#btnDoSaveMember").hasClass('disabled') && a.length < 1) {
              $("#btnDoSaveMember").addClass('disabled');
              $("#btnDoRegisterMember").addClass('disabled');
            } else if (b !== '_none_' && $("#btnDoSaveMember").hasClass('disabled')) {
              $("#btnDoSaveMember").removeClass('disabled');
            } else if (b === '_none_' && $("#btnDoSaveMember").hasClass('disabled') && a.length > 0) {
              $("#btnDoSaveMember").removeClass('disabled');
            } else if (b === '_none_' && $("#btnDoSaveMember").hasClass('disabled') && a.length < 1) {
              $("#btnDoRegisterMember").addClass('disabled');
            }
          }
          */

          function listInputLocality() {
            var y = $("#inputEmLocalityId").val();
            if (y.length < 1 && !$(".listItemLocality").is(':visible')) {
              $('.modalListInput').show();
            } else {
              $('.modalListInput').hide();
            }
          }
          $("#inputEmLocalityId").click(function () {
            listInputLocality();
            //checkLocalityFieldsBlankAndKartochka();
          });
          $("#inputEmLocalityId").keyup(function () {
            clearNewLocalityFieldByInput();
            inputSelectParallels();
            listInputLocality();
            checkLocalityFieldsBlankAndKartochka();
          });

          $("#inputEmLocalityId").on('focus', function () {
            inputSelectParallels();
            checkLocalityFieldsBlankAndKartochka();
          });
          $(".listItemLocality").hover(function () {
            console.log(' be beb e ');
          });

          $("#inputEmLocalityId").focusout(function(){
            if ($(".modalListInput").is(':visible')) {
              setTimeout(function () {
                if ($(".modalListInput").is(':visible')) {
                    $('.modalListInput').hide();
                }
              }, 150);
            }
          });
          $(".emNewLocality").click (function (){
            setFieldError ($(this),
                $(this).parents (".controls").css('display')!='none' &&
                $(this).parents (isTabletWidth ? ".controls" : ".control-group").css('visibility')!='hidden' &&
                $(this).attr('disabled')!='disabled' &&
                (!$(this).val() || $("#modalEditMember").find(".emLocality").val()=="_none_"));
          });
          // END Locality and new locality fields behavior

  $(".sort_college_locality").click(function(){
      var colleges = [], sortDirectionIcon = $(".sort-direction"), sortDirection = 'asc';
      $("#modalCollegesList #membersTable tbody tr").each(function(){
          var id = $(this).attr('data-college'),
              locality_key = $(this).attr('data-locality'),
              locality = $(this).attr('data-locality_name'),
              name = $(this).attr('data-name'),
              short_name = $(this).attr('data-short_name'),
              author = $(this).attr('data-author');

            colleges.push({id : id, author : author, locality : locality,locality_key :locality_key, name : name, short_name : short_name });
        });

        if(sortDirectionIcon.hasClass("fa-chevron-down")){
            sortDirectionIcon.removeClass("fa-chevron-down").addClass("fa-chevron-up");
            sortDirection = 'asc';
        }
        else{
            sortDirectionIcon.removeClass("fa-chevron-up").addClass("fa-chevron-down");
            sortDirection = 'desc';
        }

        colleges.sort(function (a, b) {
            if (a.locality < b.locality) {
              return sortDirection === 'asc' ? -1 : 1;
            }
            if (a.locality > b.locality) {
              return sortDirection === 'asc' ? 1 : -1;
            }
            return 0;
        });

        buildCollegesList(colleges);
    });

    $(".search-locality-to-add-college").keyup(function(){
        var text = $(this).val().trim().toLowerCase(), localityName;

        $("#modalEditCollege .locality-list").css('display', text ? 'block' : 'none');

        $("#modalEditCollege .locality-list li").each(function(){
            localityName = $(this).text().trim().toLowerCase();
            localityName.search(text) !== -1 ? $(this).show() : $(this).hide();
        });
    });

    $("#modalEditCollege .locality-list li").click(function(){
        renderCollegeLocality($(this).attr('data-value'), $(this).text());
        resetSearchLocality();
    });

    $('.colleges-city-list').change(function(){
        handleCollegesList();
    });

    $('.search-colleges').keyup(function(){
        handleCollegesList();
    });

    $('.clear-search-colleges').click(function(){
        $(this).siblings('.search-colleges').val('').focus();
        handleCollegesList();
    });

    $('#btnDoAddCollege').click(function(e){
        e.preventDefault();
        e.stopPropagation();
        var modal = $('#modalEditCollege');

        modal.find('.edit-college-name').val('').keyup();
        modal.find('.edit-college-short-name').val('').keyup();
        modal.find('.search-locality-to-add-college').val('').keyup();
        modal.find('.btnDoSaveCollege').removeAttr('data-college_id');
        setTimeout(function(){modal.find('.edit-college-name').focus();}, 1000);
        modal.find('h3').html('Добавить учебное заведение');
        modal.modal('show');
    });

    $('.cancelEditCollege').click(function(e){
        e.preventDefault();
        e.stopPropagation();
        $('#modalEditCollege').modal('hide');
    });

    $('.btnDoSaveCollege').click(function(e){
        e.preventDefault();
        e.stopPropagation();
        var modal = $(this).parents('#modalEditCollege');
        var collegeId = modal.find('.btnDoSaveCollege').attr('data-college_id'),
            name = modal.find('.edit-college-name').val().trim(),
            shortName = modal.find('.edit-college-short-name').val().trim(),
            locality = modal.find('.selected-locality span').attr('data-value'),
            text = $('#modalCollegesList').find('.search-colleges').val().trim();

        if(!name || !shortName){
            showError("Необходимо указать название и сокращенное название учебного заведения");
            return;
        }

        if(!locality){
            showError("Выберите местность из предложенного списка");
            return;
        }

        $.post('/ajax/members.php?setCollege', {collegeId:collegeId , name: name, shortName: shortName, locality: locality, text: text})
        .done(function(data){
            buildCollegesList(data.colleges);
            modal.modal('hide');
        });
    });

    $('.cancelDeleteCollege').click(function(e){
        e.preventDefault();
        e.stopPropagation();
        $('#modalDeleteCollege').modal('hide');
    });

    $('.btnDoDeleteCollege').click(function(e){
        e.preventDefault();
        e.stopPropagation();
        var collegeId = $(this).attr('data-college_id');
        var modal = $('#modalCollegesList');
        var text = modal.find('.search-colleges').val();

        $.post('/ajax/members.php?deleteCollege=true', {collegeId: collegeId, text: text}).done(function(data){
            buildCollegesList(data.colleges);
            $('#modalDeleteCollege').modal('hide');
        });
    });
});

// colleges
function getColleges(){
    $.post('/ajax/members.php?getColleges')
    .done(function(data){
        buildCollegesList(data.colleges);
        buildCollegesLocalityList(data.localities);
        handleCollegeModalWindow();
    });
}

    function resetSearchLocality(){
        $('.search-locality-to-add-college').val('');
        $("#modalEditCollege .locality-list").css('display', 'none');
    }

    function buildCollegesList(colleges){
        var arr = [], modalList = $('#modalCollegesList'), modalEditMemberWindowIsOpen = checkIfModalEditMemberOpen(), college = '';

        if(modalEditMemberWindowIsOpen){
            college = $("#modalEditMember .emCollege").attr('data-college');
        }

        for (var i in colleges){
            var c = colleges[i];

            arr.push(
                '<tr data-college="'+c["id"]+'" data-author="'+ c["author"]+'" data-locality_name="'+c["locality"]+'" data-name="'+c["name"]+'" data-short_name="'+ c["short_name"]+'" data-locality="'+ c["locality_key"]+'">' +
                    '<td style="width: 60%;">' + ( modalEditMemberWindowIsOpen ? '<input class="user-college" type="radio" ' + ( modalEditMemberWindowIsOpen && college == c["id"] ? "checked" : "") + ' style="margin-top: -4px; margin-right: 10px;">' : '' )+ he (c["short_name"]+ ' (' + c["name"]+ ')') + '</td>' +
                    '<td>' + he(c["locality"])+ '</td>' +
                    '<td>' + ( c["author"] == 1 ?
                        '<i style="float: right" class="fa fa-trash-o fa-lg openCollegeDeleteModal" title="Удалить"></i>'+
                        '<i style="float: right" class="fa fa-pencil fa-lg openCollegeEditModal" title="Редактировать"></i>' : '' ) +
                    '</td>' +
                '</tr>');
        }

        arr.unshift('<tr data-college="_none_" data-author="" data-locality_name="" data-name="" data-short_name="" data-locality="">' +
                    '<td style="width: 60%;">' + ( modalEditMemberWindowIsOpen ? '<input class="user-college" type="radio" ' + ( modalEditMemberWindowIsOpen && !college ? "checked" : "") + ' style="margin-top: -4px; margin-right: 10px;">' : '' )+ 'Нет учебного заведения' + '</td>' +
                    '<td>--</td>' +
                    '<td>&nbsp;</td>' +
                '</tr>');

        modalList.find('tbody').html(arr.length === 0 ? "<h4 style='text-align: center; margin-top: 20px;'>Учебные заведения не найдены</h4>" : arr.join(''));

        $('.openCollegeEditModal').on('click', function(e){
            e.preventDefault();
            e.stopPropagation();

            var element = $(this).parents('tr'), modal = $('#modalEditCollege'),
                collegeId = element.attr('data-college'), name = element.attr('data-name'),
                shortName = element.attr('data-short_name');

            modal.find('.btnDoSaveCollege').attr('data-college_id', collegeId);
            modal.find('.edit-college-name').val(name).keyup();
            modal.find('.edit-college-short-name').val(shortName).keyup();
            renderCollegeLocality(element.attr('data-locality'), element.attr('data-locality_name'));
            modal.find('h3').html('Изменить учебное заведение');
            modal.modal('show');
        });

        $('.openCollegeDeleteModal').click(function(e){
            e.preventDefault();
            e.stopPropagation();
            var collegeId = $(this).parents('tr').attr('data-college');

            var modal = $('#modalDeleteCollege');
            modal.find('.btnDoDeleteCollege').attr('data-college_id', collegeId);
            modal.modal('show');
        });

        $(".user-college").change(function(){
            var currentRadio = $(this);
            $(this).parents('tbody').find('.user-college').each(function(){
                $(this).prop('checked', false);
            });
            currentRadio.prop('checked', true);
        });

        handleCollegesList();
    }

    function renderCollegeLocality(localityValue, localityName){
        $(".search-locality-to-add-college").hide();
        $("#modalEditCollege .selected-locality").html('<span data-value="'+localityValue+'"><strong>'+localityName+'</strong> <i style="color:red;" class="fa fa-times removeCollegeLocality" title="Изменить город"></i></span>');

        $(".removeCollegeLocality").click(function(){
            setTimeout(function() {$(".search-locality-to-add-college").focus();}, 500);
            $(".search-locality-to-add-college").show();
            $("#modalEditCollege .selected-locality").html('');
        });
    }

    function handleCollegesList(){
        var collegeLocality = $('.colleges-city-list').val(), searchName = $('.search-colleges').val().toLowerCase().trim();

        $("#modalCollegesList tbody tr").each(function(){
            var locality = $(this).attr('data-locality'), name = $(this).attr('data-name').toLowerCase().trim(), shortName = $(this).attr('data-short_name').toLowerCase().trim(), shortNamePlusName = shortName+' ('+name+')';

            if((collegeLocality === '_all_' && searchName === '') || (collegeLocality !== '_all_' && collegeLocality === locality) || (searchName !== '' && (name.search(searchName) !== -1  || shortName.search(searchName) !== -1 || searchName === shortNamePlusName ))){
                $(this).show();
            }
            else{
                $(this).hide();
            }
        });
    }

    function checkIfModalEditMemberOpen(){
        return $('#modalEditMember').is(':visible');
    }

    function buildCollegesLocalityList(localities){
        var arr = [], modalList = $('#modalCollegesList'), isMatchedLocality = false, adminLocality = '<?php echo $adminLocality ?>';

        for (var i in localities){
            var l = localities[i];

            if(adminLocality && l.locality_key == adminLocality){
                isMatchedLocality = true;
            }

            arr.push('<option ' + ( adminLocality && l.locality_key == adminLocality ? "selected" : "" ) + ' value="'+l.locality_key+'" >'+l.locality+'</option>');
        }

        arr.unshift('<option '+ (isMatchedLocality ? "" : "selected") +' value="_all_" >Все местности</option>');

        modalList.find('.colleges-city-list').html(arr.length === 0 ? "" : arr.join(''));
    }

    function handleCollegeModalWindow(){
        var modalWindow = $("#modalCollegesList");
        if(checkIfModalEditMemberOpen()){
            var collegeName = $("#modalEditMember .emCollege").val();
            modalWindow.find('.modal-footer').html('<button class="btn btn-primary select-college">Выбрать</button><button class="btn" data-dismiss="modal" aria-hidden="true">Отмена</button>');
            modalWindow.find('.search-colleges').val(collegeName);

            $(".select-college").click(function(){
                var selectedCollegeName = '', selectedCollege = '';
                $(this).parents('#modalCollegesList').find('.user-college').each(function(){
                    var radioBtn = $(this).prop('checked');
                    if(radioBtn === true){
                        selectedCollege = $(this).parents('tr').attr('data-college');
                        selectedCollegeName = $(this).parents('tr').attr('data-name') ? $(this).parents('tr').attr('data-short_name') + ' (' + $(this).parents('tr').attr('data-name') + ')' : '';
                    }
                });

                if(selectedCollege === ''){
                    showError('Учебное заведение не выбрано!');
                    return;
                }
                else{
                    $('#modalEditMember').find('.emCollege').val(selectedCollegeName);
                    $('#modalEditMember').find('.emCollege').attr('data-college', selectedCollege === '_none_' ? '' : selectedCollege );
                    modalWindow.modal('hide');
                }
            });
        }
        else{
            modalWindow.find('.modal-footer').html('<button class="btn" data-dismiss="modal" aria-hidden="true">Ok</button>');
            modalWindow.find('.search-colleges').val('');
        }
        handleCollegesList();
        modalWindow.modal('show');
    }

function showModalHintWindow(text){
    var modalWindow = $("#modalHintWindow");
    modalWindow.find(".modal-body").html(text);
    modalWindow.modal('show');
}
function renewComboLists(comboboxSelector) {
  var pathpath = window.location.pathname;
  pathpath = pathpath.slice(1);
  if (pathpath != $(comboboxSelector).val()) {
    $(comboboxSelector).val(pathpath);
  }
}
