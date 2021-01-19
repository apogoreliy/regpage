// from members.php

// functions from script.js

isTabletWidth = $(document).width() < 980;

$(".members-lists-combo").change(function(){
    listsType = $(".members-lists-combo").val();
    switch (listsType) {
        case 'members': window.location = '/members'; break;
        case 'youth': window.location = '/youth'; break;
        case 'list': window.location = '/list'; break;
        case 'activity': window.location = '/activity'; break;
    }
});

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

function in_array(value, array){
    for(var i = 0; i < array.length; i++)
    {
        if(array[i] == value) return true;
    }
    return false;
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

function he(str) {
    return str ? String(str)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;') : "";
}

function isValidDate(d) {
  if ( Object.prototype.toString.call(d) !== "[object Date]" )
    return false;
  return !isNaN(d.getTime());
}

function parseTime (time) {
	var t = /^(\d{2}):(\d{2})$/.exec (time);
	return (t && t[1]>=0 && t[1]<24 && t[2]>=0 && t[2]<60) ? time : null;
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

// For fill edit
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

function displayBtnDoSaveMember(disable){
    disable ? $("#btnDoSaveMember").addClass ("disable-on-invalid") : $("#btnDoSaveMember").removeClass ("disable-on-invalid").removeClass("disabled");
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
function formatTime (time) {
	return time.replace(/:00$/,'');
}

function handleSchoolAndCollegeFields(age, categoryKey, schoolStart, schoolEnd, collegeStart, collegeEnd, college, collegeComment, collegeName, collegeShortName, schoolComment){
    var isSchool = categoryKey === "SC" || categoryKey === "PS" || ( !isNaN(age) && age < 18 && age > 6 );
    var isCollege = categoryKey === "ST" || ( categoryKey === "SC" && !isNaN(age) && age > 15 ) || (!isNaN(age) && age > 18 && age < 25);

    $('.school-fields').css('display', isSchool ? 'block' : 'none');
    $('.college-fields').css('display', isCollege ?'block' : 'none');
    var currentYear = new Date().getFullYear();

    if(isSchool){
        $('.emSchoolStart').val(schoolStart>0 ? schoolStart : '');
        $('.emSchoolEnd').val(schoolEnd>0 ? schoolEnd : '');
        $('.emSchoolComment').val(schoolComment || '');
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

function clearNewLocalityFieldByInput() {
  var el = $(".emNewLocality");
  if (el.val () != ""){
      el.val ("").removeAttr ("disabled").next (".unblock-input").hide ();
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

// fill and edit

function fillEditMember (memberId, info, localities, newMemberBlank) {
    window.currentEditMemberId = memberId;
    var windowWidth = $(document).width() < 980, age = parseInt(info['age']);
    var formEl = $('#modalEditMember');
    var arr = [], arr2 = [];

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

        $("#lblTransport .example").text(info["need_flight"]>0 ? "До места проведения мероприятия" : "До места проведения мероприятия");
        $("#lblTransport, .grpTransport").css ("display", "block");
    }
    else {
            $("#lblTransport, .grpTransport").css ("display", "none");
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
    $(".semestrPvom").val (info["home_phone"] ? info["home_phone"] : "");

    if ($(".emLocality").size==0){

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
    } else{
    	// ADMIN
      if (newMemberBlank) {
        $('#modalEditMember').find('.emLocality').attr('style', 'background-color: #FCF4F4; border-color:#E08A88;')
      } else {
        $(".emLocality").val (info["locality_key"] ? info["locality_key"] : "_none_").change();
      }
        $(".emNewLocality").val (info["new_locality"] ? info["new_locality"] : "").removeAttr ("disabled").next (".unblock-input").hide ();
        if(!info["locality_key"]) $(".emNewLocality").val (info["new_locality"] ? info["new_locality"] : "").keyup();
        $(".emComment").val (info["admin_comment"] ? info["admin_comment"] : "");

        $("#service_ones_pvom").val (info["serving"] ? info["serving"] : "");
        $("#semestrPvom").val (info["home_phone"] ? info["home_phone"] : "");

        if (info["locality_key"] === '001192' && $('#selMemberLocality').find('option[value="001214"]').val()) {
          $("#service_ones_pvom").parent('div').show();
        } else {
          $("#service_ones_pvom").parent('div').hide();
        }

        $(".emUserComment").val (info["comment"] ? info["comment"] : "").attr('disabled','disabled');
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
    }
    else {
        $(".emName").removeAttr ("disabled").next (".unblock-input").hide ();
    }

    handleSchoolAndCollegeFields(age, info['category_key'], info['school_start'], info['school_end'],
            info['college_start'], info['college_end'], info['college_key'], info['college_comment'], info['college_name'], info['college_short_name'], info['school_comment']);

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

    $(".emActive").prop('checked', info["active"]==1);

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

function showModalHintWindow(text){
    var modalWindow = $("#modalHintWindow");
    modalWindow.find(".modal-body").html(text);
    modalWindow.modal('show');
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
        page : page !== '/members' && page !== '/reg' && page !== '/admin' ? '/index' : page,

        termsUse : form.find('#terms-use-checkbox').prop('checked'),
        isInvitation : isInvitation ? 1 : 0,
        regListName: form.find('.custom-list').val(),
        private: $('.event-row.theActiveEvent').attr('data-private') == 1 && page === '/index' ? 1 : '',
        serving: $('#service_ones_pvom').val(),
        home_phone: $('#semestrPvom').val()
    }
}

function setCookie(name, value, exdays){
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
    document.cookie=name + "=" + c_value;
}

// STOP functions from script.js

    get_localities();
    setAdminRole_0('.add-member','#btnDoSaveMember');

    function get_localities(){
        $.get('/ajax/members.php?get_localities')
        .done (function(data) {
            renderLocalities(data.localities);
        });
    }

    function renderLocalities(localities){
        var localities_list = [],
            selectedLocality = selectedLocalityGlo;

        localities_list.push("<option value='_all_' " + (selectedLocality =='_all_' ? 'selected' : '') +" >Все местности</option>");

        for (var l in localities){
            var locality = localities[l];
            localities_list.push("<option value='"+locality['id']+"' " + (selectedLocality == l ? 'selected' : '') +" >"+he(locality['name'])+"</option>");
        }

        $("#selMemberLocality").html(localities_list.join(''));
    }

    $('.show-filters').click(function(){
        $('.filter_name_block').hide();
        $('.filter_name').text('');
        getFilters();
        $("#modalFilters").modal('show');
    });

    $(".create_filter").click(function(){
        $('.filter_name_block').css('display', 'inline-block');
    });

    $('.remove_filter_confirm').click(function(){
        var filter_id = $(this).attr('data-filter_id');

        $.get('/ajax/members.php?remove_filter', {filter_id : filter_id})
        .done (function(data) {
            renderFilters(data.filters);
        });
    })

    $('.add-filter').click(function(){
        var filter_name = $('.filter_name').val().trim(),
            isDublicat = false;

        if(filter_name === ''){
            showError('Название фильтра не может быть пустым!');
            return
        }

        $("#modalFilters .filter_item").each(function(){
            var name = $(this).attr('data-name');

            if(name == filter_name){
                isDublicat = true;
            }
        });

        if(isDublicat){
            showError('Фильтр с таким названием уже существует и не может быть добавлен!');
        }
        else{
            $.get('/ajax/members.php?add_filter', {filter_name : filter_name})
            .done (function(data) {
                $('.filter_name').val('');
                showHint('Фильтр успешно добавлен');
                renderFilters(data.filters);
            });
        }
    });

    function getFilters(){
        $.get('/ajax/members.php?get_filters')
        .done (function(data) {
            renderFilters(data.filters);
        });
    }

    function renderFilters(filters){
        get_localities();

        var filters_list = [];

        for(var f in filters){
            var filter = filters[f],
                countItems = filter.value ? filter.value.split(',') : [];

            filters_list.push('<div class="filter_item" data-localities="'+filter.value+'" data-name="'+filter.name+'" data-id="'+filter.id+'">'+
                '<span class="fa fa-list-ul show_filter" title="Просмотреть фильтр"></span>'+
                '<span class="fa fa-pencil edit_filter" title="Редактировать фильтр"></span>'+
                '<span class="fa fa-trash remove_filter" title="Удалить фильтр"></span>'+
                '<span class="edit_filter_name">' +filter.name+ '</span>' +
                '<input class="filter_name_field" />'+
                '<span class="fa fa-check save_filter_name"></span>' +
                '<span>'+ (countItems.length > 0 ? " (" +countItems.length+ ") " : "") +'</span></div>');
        }


        $('.filters_list').html(filters_list.join(''));

        $('.remove_filter').click(function(){
            var filter_id = $(this).parents('.filter_item').attr('data-id'),
                filter_name = $(this).parents('.filter_item').attr('data-name'),
                modal = $('#modalRemoveFilterConfirmation');

            modal.find('.modal-body').text("Вы действительно хотите удалить данный фильтр - " + filter_name);
            modal.find('.remove_filter_confirm').attr('data-filter_id', filter_id);
            modal.modal('show');
        });

        $('.edit_filter').click(function(){
            var filter_name = $(this).parents('.filter_item').attr('data-name');

            $(this).parents('.filter_item').find('.edit_filter_name').css('display', 'none');
            $(this).parents('.filter_item').find('.save_filter_name').css('display', 'inline');
            $(this).parents('.filter_item').find('.filter_name_field').val(filter_name).css('display', 'inline');
        });

        $('.save_filter_name').click(function(){
            var filter_id = $(this).parents('.filter_item').attr('data-id'),
                filter_name = $(this).parents('.filter_item').find('.filter_name_field').val();

            $.get('/ajax/members.php?save_filter', {filter_id : filter_id, filter_name: filter_name})
            .done (function(data) {
                $(this).parents('.filter_item').find('.edit_filter_name').css('display', 'inline');
                $(this).parents('.filter_item').find('.filter_name_field').css('display', 'none');
                $(this).parents('.filter_item').find('.save_filter_name').css('display', 'none');

                renderFilters(data.filters);
            });
        });

        $('.show_filter').click(function(){
            var filter_id = $(this).parents('.filter_item').attr('data-id'),
                filter_name = $(this).parents('.filter_item').attr('data-name'),
                filter_localities = $(this).parents('.filter_item').attr('data-localities'),
                modal = $("#modalShowFilter"),
                filter_localities_list = [];

            if(filter_localities){
                filter_localities_list = filter_localities.split(',');
            }

            var temp_localities_list = [];

            $("#selMemberLocality option").each(function(){
                var l = $(this).val(),
                    locality =  $(this).text();

                if(l){
                    temp_localities_list.push('<div style="margin-bottom: 5px;"><input style="margin-top:0" id="'+l+'" type="checkbox" '+( in_array(l, filter_localities_list)? "checked" : "")+' /><label for="'+l+'" style="display:inline; margin-left: 10px;">'+locality+'</label></div>');
                }
            });

            modal.attr('data-filter_id', filter_id);
            modal.find('.modal-header h3').text(filter_name);
            modal.find('.show_filters_list').html(temp_localities_list.join(''));
            modal.modal('show');
        });
    }

    $('.save-filter-localities').click(function(){
        var modal = $("#modalShowFilter"),
            filter_id = modal.attr('data-filter_id'),
            checkedLocalities = [];

        modal.find('.show_filters_list input').each(function(){
            var isChecked = $(this).prop('checked'),
                id = $(this).attr('id');

            if(isChecked){
                checkedLocalities.push(id);
            }
        });

        $.get('/ajax/members.php?save_filter_localities', {filter_id : filter_id, filter_localities: checkedLocalities.join(',')})
        .done (function(data) {
            renderFilters(data.filters);
        });
    });

    $('.upload_excel_file').click(function(){
        $('#modalUploadExcel .list_data').html('');
        $('#modalUploadExcel').modal('show');
    });

    $('.send_file').click(function(){
        var file_data = $('.uploaded_excel_file').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);

        var admin_id = admin_idGlo;

        $.ajax({
            url: '/ajax/excelList.php?upload_file&admin_id='+admin_id,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data){
                if(data.res){
                    showHint("Данные успешно загружены");
                }
                else{
                    showError("При загрузке данных произошел сбой. Обратитесь в службу поддержки.");
                }
                $('#modalUploadExcel').modal('hide');
            }
         });
    });

    function showDataFromExcelFile(data){
        var columns = data[0],
            //items = [],
            members = [],
            desiredFields = ['Фамилия', 'Имя', 'Отчество','Пол','Дата рождения','Местность','Состояние','Трапеза'],
            nameFields = ['Фамилия', 'Имя', 'Отчество'];

        for (var rows in data){
            var member = [],
                //item_data = [],
                nameData = [];

            if (rows != 0){
                for (var row in data[rows]){
                    if(columns[row] !== null && columns[row] !== undefined && in_array(columns[row], desiredFields)){
                        member.push({
                            key:   columns[row],
                            value: data[rows][row]
                        });

                        //item_data.push("<span title='"+ columns[row] + " ("+ data[rows][row] +")'>" + (data[rows][row].length > 28 ? data[rows][row].substring(0, 30) + '...' : data[rows][row]) + "</span>");
                    }
                }
                //if (rows != 0){
                //    items.push("<div>"+ item_data.join(' ') +"</div>");
                //}

                for(var m in member){
                    if(in_array(member[m]['key'], nameFields)){
                        nameData.push(member[m]['value'])
                    }
                }
                member.push({'ФИО': nameData.join(' ')});
                members.push(member);
            }
        }

        $.post('/ajax/members.php?downloadExcelData', {members : JSON.stringify(members)})
        .done (function(data) {
            //$('#modalUploadExcel').modal('hide');
        });

        //$('.list_data').html(items.join(''));
    }

    function loadDashboard (){
        $.getJSON('/ajax/members.php', { sortedFields : sortedFields()})
            .done (function(data) {
                refreshMembers (data.members); });
    }

    function refreshMembers (members){
        var tableRows = [], phoneRows = [];

        for (var i in members){
            var m = members[i];

            // *** last editor
            var notMe = (m.admin_key && m.admin_key!=window.adminId);
            // if the author is same for reg and mem records is was decided to show it only once
            var editor = m.admin_name;
            var htmlEditor = notMe ? '<i class="icon-user" title="Последние изменения: '+editor+'"></i>': '';

            // *** changes processed
            var htmlChanged = (m.changed > 0 ? '<i class="icon-pencil" title="Изменения еще не обработаны"></i>' : '');
            var age = getAgeWithSuffix(parseInt(m.age), m.age);
            // Cut the m.region string. Roman's code ver 5.0.0
            if (m.region =='--') {
              m.region = m.country;
            } else {
              m.region = m.region.substring(0, m.region.indexOf(" ("));
              // m.region += ', ';
              // m.region += m.country;
            }

            tableRows.push('<tr data-id="'+m.id+'" data-name="'+m.name+'" data-age="'+m.age+'" data-attendance="'+m.attend_meeting+'" data-locality="'+m.locality_key+'" data-category="'+m.category_key+'" class="'+(m.active==0?'inactive-member':'member-row')+'">'+
                '<td>' + he(m.name) +
(in_array(5, window.user_settings) ? '<br/>'+ '<span class="user_setting_span">'+m.category_name+'</span>' : '') +
                '</td>' +
                (!singleCityGlo ? '<td style="width:160px">' + he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '') +
                    (in_array(6, window.user_settings) ? '<br/>'+ '<span class="user_setting_span">'+(m.region || m.country)+'</span>' : '') +
                    '</td>' : '') +

                '<td>' + he(m.cell_phone) + '</td>' +
                '<td>' + he(m.email) + '</td>' +
                '<td style="width:50px">' + age + '</td>' +
                '<td><input type="checkbox" class="check-meeting-attend" '+ (m.attend_meeting == 1 ? "checked" : "") +' /></td>' +
                '<td>' + htmlChanged + htmlEditor + '</td>' +
                (roleThisAdminGlo != 0 ? '<td><i class="'+(m.active==0?'icon-circle-arrow-up':'icon-trash')+' icon-black fa fa-trash" title="'+(m.active==0?'Добавить в список':'Удалить из списка')+'"/></td>' : '') +
                '</tr>'
            );

            phoneRows.push('<tr data-id="'+m.id+'" data-name="'+m.name+'" data-age="'+m.age+'" data-attendance="'+m.attend_meeting+'" data-locality="'+m.locality_key+'" data-category="'+m.category_key+'" class="'+(m.active==0?'inactive-member':'member-row')+'">'+
                '<td><span style="color: #006">' + he(m.name) + '</span>'+
                '<i style="float: right; cursor:pointer;" class="'+(m.active==0?'icon-circle-arrow-up':'icon-trash')+' icon-black fa fa-trash" title="'+(m.active==0 ? 'Добавить в список':'Удалить из списка')+'"/>'+
                (!singleCityGlo ? '<div>' + (he(m.locality ? (m.locality.length>20 ? m.locality.substring(0,18)+'...' : m.locality) : '')) + ', ' + age + '</div>' : '') +''+ (in_array(6, window.user_settings) ? '<span class="user_setting_span">'+(m.region || m.country)+'</span>' : '') +
                '<div><span >'+ /*(m.cell_phone?'тел.: ':'') + */ he(m.cell_phone.trim()) + '</span>'+ (m.cell_phone && m.email ? ', ' :'' )+'<span>'+ /*(m.email?'email: ':'') + */ he(m.email) + '</span></div>' +
                '<div>Посещает собрания: <input type="checkbox" class="check-meeting-attend" '+ (m.attend_meeting == 1 ? "checked" : "") +' /></div>'+
                '<div>'+ htmlChanged + htmlEditor + '</div>'+
                '</td>' +
                '</tr>'
            );
        }

        $(".desctopVisible tbody").html (tableRows.join(''));
        $(".show-phone tbody").html (phoneRows.join(''));

        filterMembers();

        $(".member-row").unbind('click');
        $(".member-row").click (function (e) {
            e.stopPropagation();
            var memberId = $(this).attr('data-id');
            $.getJSON('/ajax/get.php', { member: memberId })
                .done (function(data) {
                    fillEditMember (memberId, data.member, data.localities);
                    //window.currentEditMemberId = memberId;
                    $('#modalEditMember #btnDoSaveMember').removeClass('create');
                    $('#modalEditMember').modal('show');
            })
        });

        $(".icon-black").unbind('click');
        $(".icon-black").click(function (event) {
            event.stopPropagation();

            if($(this).hasClass('icon-trash')){
                window.removeMemberId = $(this).parents('tr').attr('data-id');

                $.post('/ajax/members.php?is_member_in_reg', {
                    memberId : window.removeMemberId
                })
                .done(function(data){
                    if(!data.res){
                        if(window.removeMemberId.substr(0,2) === '99'){
                            removeMember(window.removeMemberId);
                        }
                        else{
                            $('#removeMemberFromList').modal('show');
                        }
                    }
                    else{
                        showError('Этот участник находится в списке регистрации! Удаление отменено.');
                    }
                });
            }
            else{
                var searchText = $('.search-text').val();
                var recoverMemberId = $(this).parents('tr').attr('data-id');
                handleMember(recoverMemberId, 1, '', searchText);
            }
        });

        $("#check-all-download-checkboxes").change(function(){
            var checkAll = $(this).prop('checked');
            var a = 0;

            $(this).parents("#modalDownloadMembers").find(".download-checkboxes input[type='checkbox']").each(function(){
              $(this).attr('id')==='member-name' ? a = 1 : a = 0;
              if (a === 0) {
                $(this).prop('checked', checkAll);
              }
            });
        });

        $('.downloadItems').click(function(){
            var checkedFields = [];
            $("#modalDownloadMembers").find("input[type='checkbox']").each(function(){
                if ($(this).prop('checked')==true && ($(this).attr('id') != "check-all-download-checkboxes")){
                    checkedFields.push($(this).attr('data-download'));
                }
            });

            downloadMembersListExel(members, checkedFields);
            checkedFields = [];
        });

        $(".check-meeting-attend").click(function(e){
            e.stopPropagation();
        });

        $(".check-meeting-attend").change(function(e){
            e.stopPropagation();

            var value = $(this).prop('checked') ? 1 : 0, memberId = $(this).parents('tr').attr('data-id');

            $.post('/ajax/members.php?set_attend_meeting', {value : value, memberId : memberId})
            .done(function(data){
                if(data.result && value == 1){
                    showModalHintWindow("<strong>"+data.result+"</strong>");
                }
            });
        });
        // START hide empty city
        function hideEmptyCity() {
          var city = [], members = [];
          $('#selMemberLocality option').each(function () {
            city.push($(this).val());
          });
          $('#members tbody tr').each(function () {
            members.push($(this).attr('data-locality'));
          });
          for (var i = 0; i < city.length; i++) {
            if (!(city[i].indexOf(',') !== -1 || city[i] === '_all_')) {
              var a = members.indexOf(city[i]);
              if (a === -1) {
                $('#selMemberLocality option').each(function () {
                  if ($(this).val() == city[i]) {
                    $(this).css('display', 'none');
                  }
                });
              }
            }
          }
        }
        setTimeout(function () {
          hideEmptyCity();
        }, 1000);
        // STOP hide empty city
    }

    $(".btnDownloadMembers").click(function(event){
        event.stopPropagation();
        $('#modalDownloadMembers').modal('show').find("input[type='checkbox']").each(function(){
            $(this).prop('checked', true);
        });
    });

    $(".remove-member-reason").click(function(e){
        e.stopPropagation();
        e.preventDefault();
        var reason = '';

        if($(this).hasClass('empty-info')){
            reason = 'Информация отсутствует';
        }
        else if($(this).hasClass('outside')){
            reason = 'Не в церковной жизни';
        }
        $(".removeMemberReason").val(reason);
    });

    $(".btnShowStatistic").click(function(e){
        e.stopPropagation();
        var isTabletMode = $(document).width()<786,
            filterLocality = $('#selMemberLocality option:selected').text(),
            localitiesByFilter = [],
            countMembers = 0, countBelivers=0, countScholars = 0,
            countPreScholars = 0, countStudents = 0, countSaints = 0,
            countRespBrothers = 0, countFullTimers = 0, countTrainees = 0,
            countOthers = 0, countAttendances = 0,

            memberAgeIsNullList = [],

            countScholarsByAge = 0, countStudentsByAge = 0, countSaintsByAge = 0,
            countAttendancesScholarsByAge = 0, countAttendancesStudentsByAge = 0, countAttendancesSaintsByAge = 0,
            countAttendancesByAge = 0, countByAge = 0, countOlderByAge = 0,

            countAttendancesMembers = 0, countAttendancesBelivers=0, countAttendancesScholars = 0,
            countAttendancesPreScholars = 0, countAttendancesStudents = 0, countAttendancesSaints = 0,
            countAttendancesRespBrothers = 0, countAttendancesFullTimers = 0, countAttendancesTrainees = 0,
            countAttendancesOthers = 0, countAttendancesSaintsByOldAge = 0,
            averageAge = 0, averageAgeAttendances = 0;

        $(".members-list " + ( isTabletMode ? " #membersPhone " : " #members " ) + " tbody tr").each(function(){
            if($(this).css('display') !== 'none' && !$(this).hasClass('inactive-member')){
                countMembers ++;

                var name = $(this).attr('data-name'),
                    locality = $(this).attr('data-locality'),
                    category = $(this).attr('data-category'),
                    age = $(this).attr('data-age');

                if(!age || age == 'null'){
                    memberAgeIsNullList.push(name);
                }
                else{
                    if(age >=12 && age <= 17){
                        averageAge += parseInt(age);
                        countScholarsByAge++;
                        if($(this).attr('data-attendance') == 1){
                            averageAgeAttendances += parseInt(age);
                            countAttendancesScholarsByAge ++;
                        }
                    }
                    else if(age >=18 && age <= 25){
                        averageAge += parseInt(age);
                        countStudentsByAge++;
                        if($(this).attr('data-attendance') == 1){
                            averageAgeAttendances += parseInt(age);
                            countAttendancesStudentsByAge ++;
                        }
                    }
                    else if (age > 25 && age <= 60){
                        averageAge += parseInt(age);
                        countSaintsByAge++;
                        if($(this).attr('data-attendance') == 1){
                            averageAgeAttendances += parseInt(age);
                            countAttendancesSaintsByAge ++;
                        }
                    }
                    else if (age > 60){
                        averageAge += parseInt(age);
                        countOlderByAge++;
                        if($(this).attr('data-attendance') == 1){
                            averageAgeAttendances += parseInt(age);
                            countAttendancesSaintsByOldAge ++;
                        }
                    }
                    if($(this).attr('data-attendance') == 1){
                        countAttendancesByAge++;
                    }
                    countByAge++;
                }

                if($(this).attr('data-attendance') == 1){
                    countAttendances ++;

                    switch (category){
                        case 'BL': countAttendancesBelivers++; break;
                        case 'SN': countAttendancesSaints++; break;
                        case 'SC': countAttendancesScholars++; break;
                        case 'PS': countAttendancesPreScholars++; break;
                        case 'ST': countAttendancesStudents++; break;
                        case 'RB': countAttendancesRespBrothers++; break;
                        case 'FS': countAttendancesFullTimers++; break;
                        case 'FT': countAttendancesTrainees++; break;
                        case 'OT': countAttendancesOthers++; break;
                    }
                }

                if(!in_array(locality, localitiesByFilter)){
                    localitiesByFilter.push(locality);
                }

                switch (category){
                    case 'BL': countBelivers++; break;
                    case 'SN': countSaints++; break;
                    case 'SC': countScholars++; break;
                    case 'PS': countPreScholars++; break;
                    case 'ST': countStudents++; break;
                    case 'RB': countRespBrothers++; break;
                    case 'FS': countFullTimers++; break;
                    case 'FT': countTrainees++; break;
                    case 'OT': countOthers++; break;
                }
            }
        });

        $("#modalStatistic h5").text('');
        var statistic =
                (countPreScholars >0 ? "<tr><td>Дошкольники</td><td class='text-align'>"+countPreScholars+"</td><td class='text-align'>"+countAttendancesPreScholars+"</td></tr>" : "" )+
                ( countScholars >0 ? "<tr><td>Школьники</td><td class='text-align'>"+countScholars+"</td><td class='text-align'>"+countAttendancesScholars+"</td></tr>" : "" ) +
                ( countStudents >0 ? "<tr><td>Студенты</td><td class='text-align'>"+countStudents+"</td><td class='text-align'>"+countAttendancesStudents+"</td></tr>" : "" )+
                (countSaints >0 ? "<tr><td>Святые в церк. жизни</td><td class='text-align'>"+countSaints+"</td><td class='text-align'>"+countAttendancesSaints+"</td></tr>" : "")+
                ( countRespBrothers >0 ? "<tr><td>Ответственные братья</td><td class='text-align'>"+countRespBrothers+"</td><td class='text-align'>"+countAttendancesRespBrothers+"</td></tr>" : "" )+
                ( countFullTimers >0 ? "<tr><td>Полновременные служащие</td><td class='text-align'>"+countFullTimers+"</td><td class='text-align'>"+countAttendancesFullTimers+"</td></tr>" : "" )+
                ( countTrainees >0 ? "<tr><td >Полновременно обучающиеся</td><td class='text-align'>"+countTrainees+"</td><td class='text-align'>"+countAttendancesTrainees+"</td></tr>" : "" )+
                ( countBelivers >0 ? "<tr><td>Верующие</td><td class='text-align'>"+countBelivers+"</td><td class='text-align'>"+countAttendancesBelivers+"</td></tr>" : "" )+
                ( countOthers >0 ? "<tr><td>Другие</td><td class='text-align'>"+countOthers+"</td><td class='text-align'>"+countAttendancesOthers+"</td></tr>" : "" ) +
                "<tr><td><strong>Всего</strong></td><td class='text-align'><strong>" + countMembers + "</strong></td><td class='text-align'><strong>"+countAttendances+"</strong></td></tr>";

        var additionalStatistic =
            (countScholarsByAge >0 ? "<tr><td>12-17 лет</td><td class='text-align'>"+countScholarsByAge+"</td><td class='text-align'>"+
                countAttendancesScholarsByAge+"</td></tr>" : "")+
            ( countStudentsByAge >0 ? "<tr><td>18-25 лет</td><td class='text-align'>"+countStudentsByAge+"</td><td class='text-align'>"+countAttendancesStudentsByAge+"</td></tr>" : "" ) +
            ( countSaintsByAge >0 ? "<tr><td>26-60 лет</td><td class='text-align'>"+countSaintsByAge+"</td><td class='text-align'>"+countAttendancesSaintsByAge+"</td></tr>" : "" )+
            ( countOlderByAge >0 ? "<tr><td>старше 60</td><td class='text-align'>"+countOlderByAge+"</td><td class='text-align'>"+countAttendancesSaintsByOldAge+"</td></tr>" : "" )+
            "<tr><td><strong>Всего</strong></td><td class='text-align'><strong>" + (countScholarsByAge+countStudentsByAge+countSaintsByAge + countOlderByAge) + "</strong></td><td class='text-align'><strong>"+(countAttendancesScholarsByAge+countAttendancesStudentsByAge+countAttendancesSaintsByAge + countAttendancesSaintsByOldAge)+"</strong></td></tr>"+
            ( countScholarsByAge>0 || countStudentsByAge> 0 || countSaintsByAge >0 ? "<tr><td>Средний возраст</td><td class='text-align'>"+(
                parseInt(averageAge / (countScholarsByAge + countStudentsByAge + countSaintsByAge + countOlderByAge)))+"</td>"+
            "<td class='text-align'>"+ (
                parseInt(averageAgeAttendances / (countAttendancesScholarsByAge + countAttendancesStudentsByAge + countAttendancesSaintsByAge + countAttendancesSaintsByOldAge))) +"</td></tr>" : "" );

        if(memberAgeIsNullList.length == 0){
            var additionalTableTemplate = '<h3>По возрастам</h3>'+
                '<table class="table table-hover">'+
                  '<thead>'+
                    '<tr>'+
                      '<th>Возраст</th>'+
                      '<th class="text-align">По списку</th>'+
                      '<th class="text-align">Посещают собрания</th>'+
                    '</tr>'+
                  '</thead>'+
                  '<tbody>'+ additionalStatistic + '</tbody>'+
                '</table>';
        }
        else{
            var additionalTableTemplate = '<h3>Данные для статистики (по возрастам) не сформированы, поскольку не указана дата рождения:</h3> <div>'+ memberAgeIsNullList.join(', ') + '</div>';
        }

        var tableTemplate = '<h3>По категориям</h3><table class="table table-hover">'+
              '<thead>'+
                '<tr>'+
                  '<th>Категория</th>'+
                  '<th class="text-align">По списку</th>'+
                  '<th class="text-align">Посещают собрания</th>'+
                '</tr>'+
              '</thead>'+
              '<tbody>'+ statistic + '</tbody>'+
            '</table>';

        $("#modalStatistic").find(".modal-header h3").html("Статистика" +
            (filterLocality === 'Все местности' ? ' (' + localitiesByFilter.length + ')' : ' (' + filterLocality + ')'));
        $("#modalStatistic").find(".modal-body").html(tableTemplate + additionalTableTemplate);
        //$("#modalStatistic").find(".modal-footer").html("<div style='float:left;'><strong>Количество местностей — "+localitiesByFilter.length+"</strong></div>");
        $("#modalStatistic").modal('show');
    });

    $(".add-member").click(function(){
        var adminLocality = $(this).attr('data-locality');

        $.getJSON('/ajax/get.php?get_member_localities_Not_Reg_Tbl').done(function(data){
            fillEditMember ('', {need_passport : "1", need_tp : "1", locality_key : adminLocality}, data.localities, true);
            $('#modalEditMember #btnDoSaveMember').addClass('create');
            $('#modalEditMember').modal('show');
        });
    });

    function removeMember(memberId){
        $.post('/ajax/members.php?remove', {
            memberId : memberId,
            sortedFields : sortedFields()
        })
        .done(function(data){
            refreshMembers(data.members);
        });
    }

    function downloadMembersListExel(members, checkedFields){
        var doc = '&document=', filteredMembers = filterMembers(), membersArr = [];

        if (checkedFields){
            doc += checkedFields;
        }

        for(var i in members){
            var member = members[i];
            if(in_array(member.id, filteredMembers)){
                membersArr.push(member);
            }
        }

        var  req = "&memberslength="+membersArr.length+"&adminId="+window.adminId+"&page=members";

        $.ajax({
            type: "POST",
            url: "/ajax/excelList.php",
            data: "members="+JSON.stringify(membersArr)+req+doc,
            cache: false,
            success: function(data) {
                location.href="./ajax/excelList.php?file="+data;
                setTimeout(function(){
                    deleteFile(data);
                }, 10000);
            }
        });
    }

    $("#remove-member").click(function (event) {
        event.stopPropagation();
        var reason = $('.removeMemberReason').val();

        if(reason.trim() === '') {
            return;
        }
        var searchText = $('.search-text').val();

        handleMember(window.removeMemberId, 0, reason, searchText);

        $('#removeMemberFromList').modal('hide');
    });

    function handleMember(member, active, reason, searchText) {
        $.getJSON('/ajax/members.php', {
            member: member,
            active: active,
            reason : reason.trim(),
            searchText : searchText,
            sortedFields : sortedFields()
        })
            .done (function(data) {
                window.removeMemberId = '';
                $('.removeMemberReason').val('');
                refreshMembers (data.members);
            });
    }

    function saveMember (){
        if ($("#btnDoSaveMember").hasClass ("disable-on-invalid") && $(".emLocality").val () == "_none_" && $(".emNewLocality").val().trim().length==0)
        {
            showError("Необходимо выбрать населенный пункт из списка или если его нет, то указать его название");
            $(".localityControlGroup").addClass ("error");
            window.setTimeout(function() { $(".localityControlGroup").removeClass ("error"); }, 2000);
            return;
        }

        var el = $('#modalEditMember'), data = getValuesRegformFields(el);

        if(!data.name || !data.gender || !data.citizenship_key || !data.category_key){
            showError("Необходимо заполнить все поля выделенные розовым цветом.");
            return;
        }

        $.post("/ajax/members.php?update_member="+window.currentEditMemberId+($("#btnDoSaveMember").hasClass('create') ? "&create=true" : ""), data)
        .done (function(data) {
            refreshMembers(data.members);
            $('#modalEditMember').modal('hide');
        });
    }

    $(document).ready (function (){
        loadDashboard ();
    });

    $("a[id|='sort']").click (function (){
        var id = $(this).attr("id");
        var icon = $(this).siblings("i");

        $(($(document).width()>768 ? ".desctopVisible" : ".show-phone") + " a[id|='sort'][id!='"+id+"'] ~ i").attr("class","icon-none");
        icon.attr ("class", icon.hasClass("icon-chevron-down") ? "icon-chevron-up fa fa-sort-asc" : "icon-chevron-down fa fa-sort-desc");
        loadDashboard ();
    });

    $("#selMemberLocality").change (function (){
        setCookie('selMemberLocality', $(this).val());
        filterMembers();
    });

    $("#selMemberAttendMeeting").change(function(){
			setCookie('selAttendMeeting', $(this).val());
			filterMembers();
		});

    $("#selMemberCategory").change (function (){
        setCookie('selMemberCategory', $(this).val());
        filterMembers();
    });

    function filterMembers(){
        var isTabletMode = $(document).width()<786,
            localityFilter = $("#selMemberLocality").val(),
            categoryFilter = $("#selMemberCategory").val(),
            attendMeetingFilter = $("#selMemberAttendMeeting").val(),
            text = $('.search-text').val().trim().toLowerCase(),
            filteredMembers = [],
            localityList = [];

        if(localityFilter){
            localityList = localityFilter.split(',');
        }

        $(".members-list " + ( isTabletMode ? " #membersPhone " : " #members" ) + " tbody tr").each(function(){
            var memberLocality = $(this).attr('data-locality'),
                memberCategory = $(this).attr('data-category'),
                attendMeeting = $(this).attr('data-attendance'),
                memberName = $(this).find('td').first().text().toLowerCase(),
                memberKey = $(this).attr('data-id');


            if(((localityFilter === '_all_' || localityFilter === undefined) && categoryFilter === '_all_' && text === '' && attendMeetingFilter === '_all_') ||

                (
                    (in_array(memberLocality, localityList) || localityFilter === '_all_' || (localityFilter === undefined && localityList.length === 0))  &&
                    (memberCategory === categoryFilter || categoryFilter === '_all_') && (attendMeeting === attendMeetingFilter || attendMeetingFilter === '_all_') ) && (memberName.search(text) !== -1))
                {

                $(this).show();
                filteredMembers.push(memberKey);
            }
            else{
                $(this).hide();
            }
        });

        return filteredMembers;
    }

    $("#btnDoSaveMember").click (function (){
      var el = $('#modalEditMember');
      if ((el.find(".emGender").val () == "_none_" || el.find(".emName").val().trim().length==0) || el.find(".emCitizenship").val () == "_none_" || el.find(".emCategory").val () == "_none_" || (el.find(".emLocality").val () == "_none_" && el.find(".emNewLocality").val () == "")) {
        showError("Необходимо заполнить все поля выделеные розовым цветом");
        $(".localityControlGroup").addClass ("error");
        window.setTimeout(function() { $(".localityControlGroup").removeClass ("error"); }, 2000);
        return;
      }
      /*if (el.find(".emBirthdate").val().trim().length==0) {
        showError("Необходимо заполнить дату рождения");
        $(".localityControlGroup").addClass ("error");
        window.setTimeout(function() { $(".localityControlGroup").removeClass ("error"); }, 2000);
        return;
      }*/
        if (!$(this).hasClass('disabled')){
            saveMember();
        }
        else{
            showError("Необходимо заполнить все обязательные поля, выделенные розовым фоном!", true);
        }
    });

    $('.search-text').bind("paste keyup", function(event){
        event.stopPropagation();
        filterMembers();
    });

    $(".clear-search-members").click(function(){
       $(this).siblings('input').val('');
       filterMembers();
    });

    $('.emName ~ .unblock-input').click(function (){
        $('#modalNameEdit').modal('show');
    });

    $('#btnDoNameEdit').click (function (){
        $ ('.emName ~ .unblock-input').hide ();
        $ (".emName").removeAttr ("disabled");
        setTimeout(function() {$(".emName").focus();}, 1000);
    });

// START check dublicate
    $('.emName').on('blur', function(){
        var name = $(this).val();
        if (name && $('#btnDoSaveMember').hasClass('create')) {
            var name = $(this).val();

            // check this
//$('#btnDoSaveMember').addClass('create');
            $.post('/ajax/get.php', {name:name })
            .done (function(data) {
                if(data.members){
                    getMembersInfo(data.members);
                }
            });
        }
    });
    function getMembersInfo(members){
        //$('#modalEditMember').modal('hide');
        $('#modalMatchMem').modal('show');

        var tableRows = [];

        for (var i in members) {
            var m = members[i];
            tableRows.push('<tr data-id="'+m.member_key+'"><td><input type="checkbox"></td><td class="chkName">'+he(m.name)+'</td>'+
                    '<td class="chkBirDate">'+he(m.birth_date)+'</td><td class="chkLocal">'+he(m.locality_name)+'</td></tr>');
        }

        $("table.chkMember tbody").html (tableRows.join(''));
    }
    $('.chooseMemb').on('click', function(event){
        event.stopPropagation();
        $('#modalMatchMem').modal('hide');
        var memberId = $("table.chkMember input[type='checkbox']:checked").parents ("tr").attr('data-id');
        if(memberId && memberId !== undefined){
          $('#modalEditMember').modal('hide');
          setTimeout(function () {
            $.getJSON('/ajax/get.php', { member: memberId})
            .done (function(data) {
                fillEditMember (memberId,  data.member, data.localities);
                $('#btnDoSaveMember').removeClass('create');
                //$('#modalEditMember').attr('data-member_id', memberId);
                $('#modalEditMember').modal('show');
            });
          }, 700);
        }
    });

    $("#modalEditMember").show(function () {
      $(this).find("#inputEmLocalityId").focus();
      $(this).find(".emName").focus();
    });


// STOP from members.php

// from script.js
function renewComboLists(comboboxSelector) {
  var pathpath = window.location.pathname;
  pathpath = pathpath.slice(1);
  if (pathpath != $(comboboxSelector).val()) {
    $(comboboxSelector).val(pathpath);
  }
}

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

// Stop from script.js

renewComboLists('.members-lists-combo');
$('#modalEditMember').on('show', function() {
  setTimeout(function () {
    showBlankEvents();
    if ($('#inputEmLocalityId').val() === 'ПВОМ' && $('#selMemberLocality').find('option[value="001214"]').val()) {
      $('#semestrPvom').parent().show();
    } else {
      $('#semestrPvom').parent().hide();
      $('#semestrPvom').val('_none_');
    }
  }, 50);
  setTimeout(function () {
    $('.emLocality').hide();
  }, 10);
  if ($('#modalEditMember').find('.emNewLocality').is(':visible')) {
    var newLocalityLength = $('#modalEditMember').find('.emNewLocality').val();
  }
  if (globalSingleCity && $('#modalEditMember').find('#btnDoSaveMember').hasClass('create')) {
    $('.emLocality option').each(function () {
        $(this).val() === '_none_' ? '' : $('.emLocality').val($(this).val());
    });
  }
  $('.modalListInput').hide();
});
$('#modalEditMember').on('hide', function() {
  $('#modalEditMember').find('.emLocality').attr('data-value','');
  $('#modalEditMember').find('.emLocality').attr('data-text','');
  $('#modalEditMember').find('#inputEmLocalityId').attr('data-value_input','');
  $('#modalEditMember').find('#inputEmLocalityId').attr('data-text_input','');
  setTimeout(function () {
    if (!$('#modalEditMember').is(':visible')) {
      $('#modalEditMember .college-fields').find('input').each(function () {
        $(this).val() ? $(this).val('') : '';
      });
      $('#modalEditMember .school-fields').find('input').each(function () {
        $(this).val() ? $(this).val('') : '';
      });
    }
  }, 500);
});
history.pushState(null, null, location.href);
    window.onpopstate = function () {
      if ($('#modalEditMember').is(':visible')) {
        history.go(1);
        $('#modalEditMember').modal('hide');
      }
    };
// START bug cover main menu
$('#modalEditMember').hide();
// STOP bug cover main menu
$('#service_ones_pvom').change(function() {
  var serviceOne = '1';
  $(this).val() ? serviceOne = $(this).val() : '';
  if (serviceOne[0] == '9') {
    showError('Что бы выбрать этого служащего, дождитесь синхронизации базы с 1С . Это может занять некоторое время.')
    $(this).val('');
  }
});

/*
// START hide empty city
function hideEmptyCity() {
  var city = [], members = [];
  $('#selMemberLocality option').each(function () {
    city.push($(this).val());
  });
  $('#members tbody tr').each(function () {
    members.push($(this).attr('data-locality'));
  });

  for (var i = 0; i < city.length; i++) {
    if (!(city[i].indexOf(',') !== -1 || city[i] === '_all_')) {
      var a = members.indexOf(city[i]);
      if (a === -1) {
        $('#selMemberLocality option').each(function () {
          if ($(this).val() == city[i]) {
            $(this).css('display', 'none');
          }
        });
      }
    }
  }
}
setTimeout(function () {
  hideEmptyCity();
}, 2000);
// STOP hide empty city
*/
