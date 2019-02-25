var MeetingsPage = (function(){
    google.charts.load('current', {'packages':['corechart', 'bar']});

    $(document).ready(function(){

        // empty admins array
        window.meetingTemplateAdmins = [];
        window.meetingTemplateAdminsList = [];
        window.meetingTemplateParticipantsList = [];
        //window.selectedTemplateMembers = [];
        //window.selectedMeetingMembers = [];

        loadMeetings();
        setAdminRole_0('.add-meeting');
        $('.checkbox-block input').change(function(){
            var isElementChecked = $(this).prop('checked'),
                modalWindowSelector = $(this).parents('.modal').attr('id');

            if(isElementChecked){
                if($(this).attr('id') === 'checkbox-locality'){
                    $('.search-members').val('').attr('placeholder', 'Введите местность').focus();
                }
                else{
                    $('.search-members').val('').attr('placeholder', 'Введите ФИО').focus();
                }

                $(this).parents('.checkbox-block').find('input').prop('checked', false);
                $(this).prop('checked', true);
            }
            $('#'+modalWindowSelector + ' .members-available').html('');
        });

        $('.search-members').keyup(function(){
            var text = $(this).val().trim().toLocaleLowerCase(),
                modalWindowSelector = $(this).parents('.modal').attr('id'),
                checkbox = $('.checkbox-block input[type="radio"]:checked');

            if(text === ''){
                $('#'+modalWindowSelector + ' .members-available').html('');
            }
            else if(!checkbox.length>0){
                showError('Выберите один из пунктов: "Местности" или "Участники".');
                return;
            }
            else{
                $.post('/ajax/meeting.php?get_template_participants', {text : text, field : checkbox.attr('data-field')})
                .done(function(data){
                    if(data.participants && data.participants.length > 0){
                        getMeetingMembersToAdd('#'+modalWindowSelector, data.participants);
                        //handleTemplateParticipants(data.participants);
                    }
                    else{
                        $('#'+modalWindowSelector + '.members-available').html('');
                    }
                });
            }
        });

        $(".search-meeting-available-participants").keyup(function(){
            var text = $(this).val().trim();
            if(text){
                $.post('/ajax/meeting.php?get_available_members', {text: text})
                .done(function(data){
                    var isAdminMode = false;
                    getMeetingParticipantsToAdd(data.members, isAdminMode);
                });
            }
            else{
                $('.participants-available').html('');
            }
        });

        /*
        $(".search-meeting-members").keyup(function(){
            filterMembersList();
        });
        */

        $('.search-template-admins').keyup(function(){
            var text = $(this).val().trim().toLocaleLowerCase();

            if(text === ''){
                $('.template-admins-available').html('');
            }
            else{
                $.post('/ajax/meeting.php?get_template_participants', {text : text, field : 'm'})
                .done(function(data){
                    if(data.participants && data.participants.length > 0){
                        handleTemplateAdmins(data.participants);
                    }
                    else{
                        $('.template-admins-available').html('');
                    }
                });
            }
        });

        function handleTemplateAdmins(admins, isEditMode){
            var list = buildTemplateAdminsList (admins, isEditMode);

            $(!isEditMode ? '.template-admins-available' : '.template-admins-added').html(list.join(''));
            if(admins.length === 0){
                $('.template-admins-available').html('');
                $('.template-admins-added').html('');
            }

            $('.addTemplateAdmin').click(function(e){
                e.stopPropagation();

                var element = $(this).parents('div'), id = element.attr('data-id'), isNewId = !in_array(id, window.meetingTemplateAdmins);
                var admin = buildTemplateAdminsList([{id : id, name : element.attr('data-name'), field : 'm'}], true);

                if(isNewId){
                    $('.template-admins-added').append(admin);
                    $('.template-admins-available').html('');
                    $('.search-template-admins').val('').focus();
                }
                else{
                    showError('Эти редакторы уже добавлены', true);
                }

                bindHandlerToRemoveAdmins();
            });

            bindHandlerToRemoveAdmins();
        }

        function buildTemplateAdminsList (admins, isEditMode){
            var adminRows = [];

            for(var m in admins){
                var admin = admins[m];

                if(isEditMode){
                    window.meetingTemplateAdmins.push(admin.id);
                }

                adminRows.push('<div data-id="'+admin.id+'" data-name="'+admin.name+'">'+
                                    '<span>'+ admin.name +'</span> '+
                                    '<i data-id="'+admin.id+'" class="fa fa-lg '+ ( isEditMode ? "fa-times removeTemplateAdmin" : "fa-plus addTemplateAdmin") + '" '+
                                    'title="' + ( isEditMode ? "Убрать " + admin.name + " из редакторов" : "Добавить " + admin.name + " к редакторам" )+'">'+
                                    '</i>'+
                                '</div>');
            }
            return adminRows;
        }

        function bindHandlerToRemoveAdmins(){
            $('.removeTemplateAdmin').click(function(e){
                e.stopPropagation();
                var id = $(this).attr('data-id');

                if(in_array(id, window.meetingTemplateAdmins)){
                    var index = window.meetingTemplateAdmins.indexOf(id);
                    if (index > -1)
                        window.meetingTemplateAdmins.splice(index, 1);
                }
                $(this).parents('div [data-id='+id+']').remove();
            });
        }

        $(".show-templates").click(function(e){
            e.preventDefault();
            var modalWindow = $("#modalTemplates");

            getMeetingTemplates($(this).hasClass('open-in-meeting-window'));
            modalWindow.modal('show');
        });

        $(".btn-add-template").click(function(){
            var template = {
                type : '_none_',
                name : '',
                localityKey : '_none_',
                participants : '',
                admins: ''
            };

            handleTemplate('add', template);
        });

        function getMeetingTemplates(isOpenInMeetingWindow){
            $.get('/ajax/meeting.php?get_templates')
            .done(function(data){
                buildTemplatesList(data.templates, isOpenInMeetingWindow);
            });
        }

        function buildTemplatesList(templates, isOpenInMeetingWindow){
            if(templates.length === 0){
                $("#template-list").hide();
                $("#modalTemplates .modal-body .empty-template-list").remove();
                $("#modalTemplates .modal-body").append('<div class="empty-template-list" style="text-align:center; padding-top: 20px;padding-bottom: 20px;"><strong>На данный момент шаблонов нет.</strong></div>');
            }
            else{
                var templatesArr = [];
                $("#template-list").show();
                $("#modalTemplates .modal-body .empty-template-list").remove();

                for(var i in templates){
                    var t = templates[i],
                        buttons = '<span class="fa fa-plus btn-add-template-meeting fa-lg" title="Создать собрание"></span> <span class="edit-template" title="Редактировать шаблон"><i class="fa fa-pencil fa-lg"></i></span> <span title="Удалить шаблон" class="delete-template"><i class="fa fa-trash fa-lg"></i></span>';

                    var admins = t.admins ? t.admins.split(',') : [],
                        participants = t.participants ? t.participants.split(',') : '';

                    templatesArr.push(
                        "<tr data-id='"+t.id+"' data-locality='"+t.locality_key+"' data-type='"+t.meeting_type+"' >" +
                        "<td>"+ ( isOpenInMeetingWindow ? "<input style='margin-top: -3px;' type='checkbox' class='check-template'> " : "" ) + t.template_name+"</td>"+
                        "<td class='template-name'>"+ t.locality_name + "</td>"+
                        "<td class='template-participants' data-participants='"+participants+"'>"+ participants.length + " <i class='fa fa-list fa-lg template-participants-info' title='Список участников'></i></td>"+
                        "<td class='template-admins' data-admins='"+t.admins+"'>"+ admins.length +" <i class='fa fa-list fa-lg template-admins-info' title='Список редакторов'></i></td>"+
                        "<td>"+ buttons +"</td>"+
                        "</tr>"
                    );
                }

                $("#template-list tbody").html(templatesArr.join(''));

                var modalWindowTemplates = $("#modalTemplates");

                if(isOpenInMeetingWindow){
                    modalWindowTemplates.find('.modal-footer').html('<button class="btn btn-primary fill-meeting-by-template" data-dismiss="modal" aria-hidden="true" disabled>Заполнить</button>'+
                        '<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отменить</button>');

                    $(".fill-meeting-by-template").click(function(){
                        if(!$(this).attr('disabled')){
                            var templateId = modalWindowTemplates.find(".check-template[type='checkbox']:checked").parents('tr').attr('data-id');

                            $.post('/ajax/meeting.php?get_template', { templateId : templateId }).done(function(data){
                                if(data.template){
                                    var templateInfo = data.template, countList = '',
                                        countChildren = '', countFulltimers = '', countTrainees = '';

                                    if(templateInfo.members){
                                        var members = templateInfo.members.split(',');

                                        countList = members.length;

                                        for(var i in members){
                                            var member = members[i].split(':'), age, category;

                                            if(member.length > 0){
                                                category = member.length == 2 ? member[1] : typeof member[0] === 'string' ? member[0] : '';
                                                age = member.length == 2 ? member[0] : typeof member[0] === 'string' ? 0 : member[0];

                                                if(age > 0  && age < 12){
                                                    countChildren ++;
                                                }

                                                switch (category) {
                                                    case 'FT':
                                                        countTrainees ++;
                                                        break;
                                                    case 'FS':
                                                        countFulltimers ++;
                                                        break;
                                                }
                                            }
                                        }
                                    }

                                    fillMeetingModalForm('Новое собрание', '', templateInfo.locality_key, templateInfo.meeting_type_key, '', countList, '', '', countChildren, countFulltimers, countTrainees, false, '', templateInfo.meeting_name, templateInfo.participants, '');
                                }
                                else{
                                    showError('Информация по данному шаблону не найдена.');
                                }
                            });
                        }
                    })
                }
                else{
                    modalWindowTemplates.find('.modal-footer').html('<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Ok</button>');
                }

                $(".check-template").change(function(){
                    var changedItemId = $(this).parents('tr').attr("data-id");

                    $(this).parents("tbody").find("tr").each(function(){
                        if($(this).attr("data-id") !== changedItemId){
                            $(this).find('.check-template').prop('checked', false);
                        }
                    });

                    $(".fill-meeting-by-template").attr('disabled', !$(this).prop('checked'));
                });

                $(".btn-add-template-meeting").click(function(){
                    var modalWindow = $("#modalConfirmAddMeetingByTemplate"),
                        id = $(this).parents('tr').attr('data-id');

                    modalWindow.find('.add-template-meeting').attr('data-id', id);
                    modalWindow.modal('show');
                });

                $(".edit-template").click(function(){
                    var element = $(this).parents('tr'),
                        id = element.attr('data-id'),
                        type = element.attr('data-type'),
                        name = element.find('.template-name').text().trim(),
                        locality = element.attr('data-locality'),
                        participants = element.find('.template-participants').attr('data-participants'),
                        admins = element.find('.template-admins').attr('data-admins');

                    var template = {
                        type : type,
                        name : name,
                        localityKey : locality,
                        participants : participants,
                        admins: admins,
                        id: id
                    };

                    handleTemplate('edit', template);
                });

                $(".delete-template").click(function(){
                    var modalWindow = $("#modalConfirmDeleteTemplate"),
                        element = $(this).parents('tr'),
                        id = element.attr('data-id');

                        modalWindow.find('.doDeleteTemplate').attr('data-id', id);
                        modalWindow.modal('show');
                });

                $(".template-admins-info").click(function(){
                    var admins = $(this).parents('.template-admins').attr('data-admins'),
                        templateId = $(this).parents('tr').attr('data-id');

                    buildAdminsList('admins', admins, templateId);
                });

                $(".template-participants-info").click(function(){
                    var participants = $(this).parents('.template-participants').attr('data-participants'),
                        templateId = $(this).parents('tr').attr('data-id');
                    buildAdminsList('participants', participants, templateId);
                });
            }
        }

        $(".add-template-meeting").click(function(){
            var templateId = $(this).attr('data-id');

            var request = getRequestFromFilters(setFiltersForRequest());

            $.post('/ajax/meeting.php?add_template_meeting&'+request, {templateId: templateId})
            .done(function(data){
                refreshMeetings(data.meetings);
            });
        });

        $('.doDeleteTemplate').click(function(){
            var templateId = $(this).attr('data-id');
            $.post('/ajax/meeting.php?delete_template', {templateId :templateId}).done(function(data){
                var modalWindow = $("#addEditMeetingModal").data('modal');

                buildTemplatesList(data.templates, modalWindow && modalWindow.isShown);
            });
        });

        function buildAdminsList(mode, list, templateId){
            var modalWindow =  $("#modalParticipantsList"), members = [];
            modalWindow.attr('data-mode', mode).attr('data-templateId', templateId);

            $(".search-meeting-available-participants").val('').focus();
            $(".participants-available").html('');

            window.meetingTemplateParticipantsList = [];
            window.meetingTemplateAdminsList = [];

            if(list){
                var listArr = list.split(',');

                for (var i in listArr){
                    var item = listArr[i], member = item.split(':'), buttons = "<i title='Удалить' class='fa fa-trash fa-lg btn-remove-item'></i>";

                    if(mode === 'participants'){
                        window.meetingTemplateParticipantsList.push(member[0]);
                    }
                    else{
                        window.meetingTemplateAdminsList.push(member[0]);
                    }

                    members.push("<tr data-id='"+member[0]+"' data-name='"+member[1]+"'>"+
                        "<td>"+member[1]+"</td>"+
                        "<td>"+member[2]+"</td>"+
                        "<td>"+buttons+"</td>"+
                        "</tr>");
                }

                modalWindow.find('table').show();
                modalWindow.find('.modal-body .empty-template-participants-list').remove();
                modalWindow.find('.modal-body tbody').html(members.join(''));

                $(".btn-remove-item").click(function(){
                    var itemId = $(this).parents('tr').attr('data-id'),
                        confirmationWindow = $("#modalConfirmDeleteParticipant"),
                        name = $(this).parents('tr').attr('data-name');

                    confirmationWindow.find('.doDeleteParticipant').attr('data-id', itemId).attr('data-mode', mode).attr('data-template', templateId);

                    confirmationWindow.find('.modal-body').html('Вы действительно хотите удалить '+ name +' из списка ' + ( mode === 'participants' ? 'участников' : 'редакторов'));
                    confirmationWindow.find('.modal-header h3').html('Удаление '+ ( mode === 'participants' ? 'участника' : 'редактора'));
                    confirmationWindow.modal('show');
                });
            }
            else{
                modalWindow.find('table').hide();
                modalWindow.find('.modal-body .empty-template-participants-list').remove();
                modalWindow.find('.modal-body').append('<div class="empty-template-participants-list" style="text-align:center; padding-top: 20px;padding-bottom: 20px;"><strong>'+ ( mode === "participants" ? "Список участников пуст" : "Список редакторов пуст" )+ '</strong></div>');
            }



            modalWindow.find('.modal-header h3').html( mode === 'participants' ? 'Список участников' : 'Список редакторов');
            modalWindow.modal('show');
        }

        $(".doDeleteParticipant").click(function(){
            var memberId = $(this).attr('data-id'),
                mode = $(this).attr('data-mode'),
                templateId =  $(this).attr('data-template');

            $.post('/ajax/meeting.php?delete_participants', {memberId : memberId, mode : mode, templateId : templateId})
            .done(function(data){
                var modalWindow = $("#addEditMeetingModal").data('modal');

                buildAdminsList(mode, data.participants, templateId);
                buildTemplatesList(data.templates, modalWindow && modalWindow.isShown);
            });
        });

        $(".template-meeting-type").change(function(){
            var text = $('.template-meeting-type option:selected').text();
            $(".template-name").val(text).keyup();
        });

        function handleTemplate(mode, template){
            var modalWindow = $("#modalHandleTemplate");
            $("#modalHandleTemplate").attr('data-id', template.id);
            modalWindow.find('.modal-header h3').html( mode === 'add' ? 'Создание шаблона' : 'Изменение шаблона' );

            modalWindow.find('.template-meeting-type').val(template.type).change();
            modalWindow.find('.template-name').val(template.name).keyup();
            modalWindow.find('.template-locality').val(template.localityKey).change();

            var adminsArrTemp = template.admins ? template.admins.split(',') : [],
                participantsArrTemp = template.participants ? template.participants.split(',') : [],
                adminsArr = [], participantsArr = [];

            if(adminsArrTemp.length > 0){
                for (var i = 0 in adminsArrTemp) {
                    var admin = adminsArrTemp[i];
                    var adminItem = admin.split(':');
                    adminsArr.push({id: adminItem[0], name : adminItem[1]});
                }
            }

            if(participantsArrTemp.length > 0){
                for (var i = 0 in participantsArrTemp) {
                    var participant = participantsArrTemp[i];
                    var participantItem = participant.split(':');
                    participantsArr.push({id: participantItem[0], name : participantItem[1], locality: participantItem[2], attend_meeting: participantItem[3]});
                }
            }

            //window.selectedTemplateMembers = [];

            buildMembersList(modalWindow.selector, participantsArr);
            handleTemplateAdmins(adminsArr, mode !== 'add');

            modalWindow.find('.btn-handle-template').html( mode === 'add' ? 'Создать' : 'Изменить' ).addClass( mode === 'add' ? 'add' : 'edit').removeClass( mode === 'add' ? 'edit' : 'add').attr('data-id', template.id);
            modalWindow.modal('show');
        }

        $('.btn-handle-template').click(function(){
            var modalWindow = $("#modalHandleTemplate");

            var type = modalWindow.find('.template-meeting-type').val(),
                name = modalWindow.find('.template-name').val(),
                locality = modalWindow.find('.template-locality').val(),
                participants = [],
                admins = modalWindow.find('.template-admins').val(),
                id = $(this).attr('data-id');

            if(type === '_none_' || name.trim() === '' || locality === '_none_'){
                showError('Необходимо заполнить все поля выделенные розовым цветом.');
                return;
            }

            modalWindow.find('#list tbody tr').each(function(){
                var memberId = $(this).attr('data-id');
                participants.push(memberId);
            });

            $.post("/ajax/meeting.php?handle_template", {id: id, type: type, name : name, locality : locality, participants : participants.join(','), admins : window.meetingTemplateAdmins.join(',')})
                .done(function(data){
                    var modal = $("#addEditMeetingModal").data('modal');
                    buildTemplatesList(data.templates, modal && modal.isShown);

                    modalWindow.modal('hide');
                });
        });

        $(".btn-meeting-general-statistic").click(function(){
            $("#modalGeneralMeetingStatistic").modal('show');
        });

        $("#modalGeneralMeetingStatistic").on('shown', function(){
            getGeneralStatistic();
        });

        $("#meetingTypeGeneralStatistic, #localityGeneralStatistic, .start-date-statistic-general, .end-date-statistic-general").change (function (){
            getGeneralStatistic();
        });

        $(window).resize(function() {
            if($("#modalGeneralMeetingStatistic").is(':visible')){
                if(this.resizeTO) clearTimeout(this.resizeTO);
                this.resizeTO = setTimeout(function() {
                    $(this).trigger('resizeEnd');
                }, 500);
            }
        });

        $(window).on('resizeEnd', function() {
            getGeneralStatistic();
        });

        function drawChart(information){
            var info = [],
                showMeetingType = $("#meetingTypeGeneralStatistic").val() === '_all_',
                showLocalityName = $("#localityGeneralStatistic").val() === '_all_';

            info.push(['Дата', 'По списку', 'На собрании', 'Служащих', 'Обучающихся', 'Гостей', 'Всего', 'Детей']);

            information.forEach(function(item){
                var meetingCounts = getMeetingCounts(item);

                if((meetingCounts.countMembers + meetingCounts.childrenCount)>0){
                    info.push( [
                        formatDate(item.date) + (showLocalityName ? '\n( '+he(item.locality_name)+' )' : '') + (showMeetingType ? '\n'+he(item.short_name) : ''),
                        meetingCounts.listCount,
                        meetingCounts.saintsCount,
                        meetingCounts.fulltimersCount,
                        meetingCounts.traineesCount,
                        meetingCounts.guestCount,
                        meetingCounts.countMembers,
                        meetingCounts.childrenCount
                    ]);
                }
            });

            var data = google.visualization.arrayToDataTable(info);

            var options = {
              chart: {
                title: '',
                subtitle: '',
              },
              bars: 'horizontal'
            };

            var chart = new google.charts.Bar(document.getElementById('chart_div'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        }

        function getGeneralStatistic(toDownload) {
            var locality = $("#localityGeneralStatistic").val();
            var meetingType = $("#meetingTypeGeneralStatistic").val();
            var startDate = $(".start-date-statistic-general").val();
            var endDate = $(".end-date-statistic-general").val();

            $.getJSON('/ajax/meeting.php?get_general_statistic', {locality : locality || '_all_', meeting_type : meetingType || '_all_', startDate : parseDate(startDate), endDate : parseDate(endDate)})
            .done(function(data){
                toDownload ? downloadGeneralStatistic(data.list) : drawChart(data.list);
            });
        }

        function downloadGeneralStatistic(list){
            if(list.length>0){
                $.ajax({
                    type: "POST",
                    url: "/ajax/excelList.php",
                    data: "page=meeting_general"+"&list="+JSON.stringify(list)+"&adminId="+window.adminId,
                    cache: false,
                    success: function(data) {
                        document.location.href="./ajax/excelList.php?file="+data;
                        setTimeout(function(){
                            deleteFile(data);
                        }, 10000);
                    }
                });
            }
            else{
                showError("Список пустой. Нет данных для скачивания");
            }
        }

        $('.btn-meeting-download-general-statistic').click(function(){
            getGeneralStatistic(true);
        });

        $('.btn-meeting-download-members-statistic').click(function(){
            var memberLocality, memberName, members = [], list_count = $(this).attr('data-list_count') || 0 , members_count = $(this).attr('data-members_count') || 0;

            $("#modalMeetingStatistic tbody tr").each(function(){
                memberLocality = $(this).attr('data-locality_name');
                memberName = $(this).find('.statistic-part > span').text();

                var meetings=[];
                $(this).find('.statistic-date-part span').each(function(){
                    meetings.push($(this).attr('data-type')+':'+$(this).attr('title'));
                });

                members.push({locality: memberLocality, memberName:memberName, meetings:meetings.join(','), members_count: members_count, list_count: list_count});
            });

            if(members.length>0){
                $.ajax({
                    type: "POST",
                    url: "/ajax/excelList.php",
                    data: "page=meeting_members"+"&members="+JSON.stringify(members)+"&adminId="+window.adminId+"&members_count="+members_count+"&list_count="+list_count+"&start_date="+$('.start-date-statistic-members').val() + "&end_date="+$('.end-date-statistic-members').val(),
                    cache: false,
                    success: function(data) {
                        document.location.href="./ajax/excelList.php?file="+data;
                        setTimeout(function(){
                            deleteFile(data);
                        }, 10000);
                    }
                });
            }
            else{
                showError("Список пустой. Нет данных для скачивания");
            }
        });

        $("#modalMeetingStatistic .modal-body, #modalGeneralMeetingStatistic .modal-body").scroll(function(){
            handleScrollUpBtn($(this));
        });

        function handleScrollUpBtn(form){
            var height = form.find('tbody').height();
            var scrollTop = form.scrollTop();
            height>600 && scrollTop>300 ? form.find(".scroll-up").show() : form.find(" .scroll-up").hide();
        }

        $("#modalMeetingStatistic .scroll-up, #modalGeneralMeetingStatistic .scroll-up").click(function(e){
            e.stopPropagation();
            $(this).parents('.modal-body').animate({
                scrollTop: 0
            }, 500);
        });

        $(".input-daterange .start-date, .input-daterange .end-date").change(function(){
            loadMeetings();
        });

        $("#localityStatistic, #meetingTypeStatistic, .start-date-statistic-members, .end-date-statistic-members").change(function(){
            getMembersStatistic();
        });

        $("a[id|='sort']").click (function (){
            var id = $(this).attr("id");
            var icon = $(this).siblings("i");

            $(".meetings-list a[id|='sort'][id!='"+id+"'] ~ i").attr("class","icon-none");
            icon.attr ("class", icon.hasClass("icon-chevron-down") ? "icon-chevron-up" : "icon-chevron-down");
            loadMeetings ();
        });

        function handleExtraFields(show){
            $(".show-extra-fields").css('display', show ? 'block' : 'none');
        }

        function chechExtraFields(locality){
            $.post('/ajax/meeting.php?show_extra_fields', {locality : locality})
            .done(function(data){
                handleExtraFields(data.show);
            });
        }

        $(".add-meeting").click(function(){
            $("#addEditMeetingModal").find('.btnDoHandleMeeting').removeAttr('data-id');
            handleExtraFields(false);
            fillMeetingModalForm('Новое собрание');
        });

        $(".btn-meeting-members-statistic").click(function(){
            getMembersStatistic();
        });

       /*
        $(".filter-stat-members-checked, .filter-stat-members-unchecked").click(function(){
            if($(this).hasClass('active'))
                $(this).removeClass('active');
            else
                $(this).addClass('active');

            $(this).siblings('.btn.fa').removeClass('active');

            filterMembersList();
        });

        function filterMembersList(){
            var onlyCheckedMembers = $(".filter-stat-members-checked").hasClass('active');
            var onlyUncheckedMembers = $(".filter-stat-members-unchecked").hasClass('active');
            var isMemberChecked, memberName;
            var searchField = $(".search-meeting-members").val().trim().toLowerCase();

            $("#modalMeetingMembers tbody tr").each(function(){
                isMemberChecked = $(this).find('input[type="checkbox"]').prop('checked');
                memberName = $(this).find('td:nth-child(2)').text().toLowerCase();

                (!onlyCheckedMembers && !onlyUncheckedMembers && searchField==='') || (((onlyUncheckedMembers && !isMemberChecked) || (onlyCheckedMembers && isMemberChecked) || (!onlyUncheckedMembers && !onlyCheckedMembers)) && (searchField === '' || (searchField !== '' && memberName.search(searchField) !== -1))) ? $(this).show() : $(this).hide();
            });
        }
        */

        function filterMeetingsList(){
            var locality = $("#selMeetingLocality").val();
            var meetingType = $("#selMeetingCategory").val();

            $(".meetings-list tbody tr").each(function(){
                ($(this).attr('data-locality') === locality || $(this).attr('data-district') === locality || locality === '_all_') && ($(this).attr('data-type') === meetingType || meetingType === '_all_') ? $(this).show() : $(this).hide();
            });
        }

        function getMembersStatistic(){
            var locality = $("#localityStatistic").val();
            var meetingType = $("#meetingTypeStatistic").val();
            var startDate = $(".start-date-statistic-members").val();
            var endDate = $(".end-date-statistic-members").val();

            $.getJSON('/ajax/meeting.php?get_members_statistic', {locality: locality || '_all_', meeting_type: meetingType || '_all_', startDate:parseDate(startDate), endDate : parseDate(endDate)}).done(function(data){
                buildStatisticMembersList(data.list, data.members_list_count);
                $("#modalMeetingStatistic").modal('show');
                $("#modalMeetingStatistic .scroll-up").hide();
            });
        }

        function buildStatisticMembersList(list, membersListCount){
            var tableRows = [], members_count = 0;

            for (var i in list) {
                var m = list[i], arr = [], meeting = m.meeting.split(','), meetingArr;

                members_count ++;
                for(var j in meeting){
                    meetingArr = meeting[j].split(':');
                    arr.push('<span class="statistic-bar '+(meetingArr[0]+'-meeting')+'" data-type="'+meetingArr[0]+'" title="'+(meetingArr[1])+'"></span>');
                }

                tableRows.push(
                    '<tr data-id="'+m.member_key+'" data-locality_name="'+he(m.locality_name)+'" data-locality="'+(m.locality_key)+'" >'+
                    '<td class="statistic-part"><span>'+he(m.name)+'</span></td><td class="statistic-date-part" style="padding-top: 10px;">'+arr.join('')+'</td>/tr>'
                );
            }

            $("#modalMeetingStatistic .btn-meeting-download-members-statistic").attr('data-list_count', parseInt(membersListCount) || '' ).attr('data-members_count', members_count || '');
            $("#modalMeetingStatistic #general-statistic").html('По списку — '+(parseInt(membersListCount) || '')+' чел. Участвовали в собраниях — '+members_count+' чел.');
            $("#modalMeetingStatistic tbody").html(tableRows.join(''));
        }

        $(".btnDoHandleMeeting").click(function(){
            var modal = $("#addEditMeetingModal");

            var date = modal.find('.meetingDate').val();
            var locality = modal.find('#meetingLocalityModal').val();
            var meetingType = modal.find('#meetingCategory').val();
            var meetingName = modal.find('.meetingName').val();
            var note = modal.find('.meeting-note').val();

            var listCount = modal.find('.meeting-list-count').val();
            var countGuest = modal.find('.meeting-count-guest').val();
            var countChildren = modal.find('.meeting-count-children').val();

            var fulltimersCount = modal.find('.meeting-count-fulltimers').val();
            var traineesCount = modal.find('.meeting-count-trainees').val();

            var request = getRequestFromFilters(setFiltersForRequest());
            var meetingId = $(this).attr('data-id') ? $(this).attr('data-id') : '';

            var oldDate = $(this).attr('data-date'), oldLocality = $(this).attr('data-locality'), oldMeetingType = $(this).attr('data-meeting_type');

            if(!date || !locality || !meetingType){
                showError('Необходимо заполнить все обязательные поля выделенные розовым цветом');
                return;
            }

            var members = [], attendMembers = [];
            modal.find("tbody tr").each(function(){
                members.push($(this).attr('data-id'));

                if($(this).find('.check-member-checkbox').prop('checked')){
                    attendMembers.push($(this).attr('data-id'));
                }
            });

           var saintsCount = attendMembers.length;

            $.post('/ajax/meeting.php?set_meeting'+request, {
                meetingId : meetingId,
                date:parseDate(date),
                locality: locality,
                meetingType: meetingType,
                oldDate: oldDate,
                oldLocality: oldLocality,
                oldMeetingType: oldMeetingType,
                meetingName : meetingName,
                note: note,
                listCount: listCount || 0,
                saintsCount : saintsCount || 0,
                countGuest: countGuest || 0,
                countChildren: countChildren || 0,
                traineesCount : traineesCount || 0,
                fulltimersCount : fulltimersCount || 0,
                members : members.join(','),
                attendMembers : attendMembers.length > 0 ? attendMembers.join(',') : ''
            }).done(function(data){
                if(data.isDoubleMeeting){
                    showError('Данное собрание является дублирующим и не было сохранено!');
                }
                $(".localities-available").html('');
                $(".localities-added").html('');
                $(".searchLocality").val('');

                refreshMeetings(data.meetings);
                $("#addEditMeetingModal").modal('hide');
            });
        });

        function fillMeetingModalForm(textMode, date, locality, meetingType, note, countList, count, countGuests, countChildren, countFulltimers, countTrainees, isMeetingSummary, saintsCount, meetingName, members, participants){
            //window.selectedMeetingMembers = [];

            var modal = $("#addEditMeetingModal"), isSingleCity = parseInt('<?php echo $isSingleCity; ?>');
            locality = isSingleCity ? '<?php echo $singleLocality; ?>' : locality;

            modal.find(".meetingName").val(meetingName || '');
            modal.find('.meetingDate').val(date || formatDate (new Date())).keyup().attr('disabled', isMeetingSummary ? 'disabled' : false);
            modal.find('#meetingLocalityModal').val(locality || '_all_').change().attr('disabled', isSingleCity || isMeetingSummary ? 'disabled' : false);
            modal.find('#meetingCategory').val(meetingType || '_all_').change().attr('disabled', isMeetingSummary ? 'disabled' : false);
            modal.find('.meeting-note').val(note || '');
            isMeetingSummary ? $('.note-field').hide() : $('.note-field').show();

            modal.find('.meeting-list-count').val(countList || '').attr('disabled', isMeetingSummary ? 'disabled' : false);
            modal.find('.meeting-saints-count').val(saintsCount || '').attr('disabled', isMeetingSummary ? 'disabled' : false);
            modal.find('.meeting-count').val(count || '').attr('disabled', 'disabled');
            modal.find('.meeting-count-guest').val(countGuests || '').attr('disabled', isMeetingSummary ? 'disabled' : false);
            modal.find('.meeting-count-children').val(countChildren || '').attr('disabled', isMeetingSummary ? 'disabled' : false);
            modal.find('.meeting-count-fulltimers').val(countFulltimers || '').attr('disabled', isMeetingSummary ? 'disabled' : false);
            modal.find('.meeting-count-trainees').val(countTrainees || '').attr('disabled', isMeetingSummary ? 'disabled' : false);

            modal.find("#titleMeetingModal").text(textMode);

            $(".show-templates.open-in-meeting-window").css('display', textMode === 'Новое собрание' ? 'block': 'none');
            if(members && members !== 'null'){
                var members = members.split(','), membersArr = [];
                for(var i in members){
                    var member = members[i].split(':');

                    membersArr.push({id: member[0], name: member[1], locality: member[2], attend_meeting: member[3], present : in_array(member[0], participants)});
                }

                buildMembersList("#addEditMeetingModal", membersArr);
            }
            else{
                var modalWindow = $("#addEditMeetingModal");
                modalWindow.find('.members-available').html('');
                modalWindow.find('tbody').html('');
            }

            modal.modal('show');
        }

        function buildMembersList(modalWindowSelector, list){
            var members = [];
            $(modalWindowSelector).find('.members-available').html('');

            if(list && list.length > 0){
                for (var i in list){
                    var member = list[i], buttons = "<i title='Удалить' class='fa fa-trash fa-lg btn-remove-member'></i>";
                    members.push("<tr class='check-member' data-id='"+member.id+"' data-attend_meeting='"+member.attend_meeting+"' data-name='"+member.name+"' data-locality='"+member.locality+"'>"+
                        "<td>" + ( modalWindowSelector === '#modalHandleTemplate' ? "" : "<input type='checkbox' "+(member.present ? "checked='true'" : "" ) + " style='margin-top: -3px;' class='check-member-checkbox'> ") +member.name +"</td>"+
                        "<td>"+member.locality+"</td>"+
                        "<td>"+(member.attend_meeting == 1 ? '<i class="fa fa-check"></i>' : '-') +"</td>"+
                        "<td>"+buttons+"</td>"+
                        "</tr>");
                }

                $(modalWindowSelector).find('.modal-body tbody').html(members.join(''));

                $('.check-member').click(function(){
                    var element = $(this).find('.check-member-checkbox');

                    element.prop('checked', !element.prop('checked'));
                });

                $(modalWindowSelector).find(".btn-remove-member").click(function(){
                    var memberIdToDelete = $(this).parents('tr').attr('data-id'), members = [];

                    $(modalWindowSelector + " tbody tr").each(function(){
                        var id = $(this).attr("data-id"), name = $(this).attr("data-name"),
                        locality = $(this).attr("data-locality"),
                        attend_meeting = $(this).attr("data-attend_meeting"),
                        present = $(this).find('.check-member-checkbox').prop('checked');

                        if(id !== memberIdToDelete){
                            members.push({id : id, name: name, locality : locality, attend_meeting: attend_meeting, present : present});
                        }
                    });

                    buildMembersList(modalWindowSelector, members);
                    //confirmToRemoveMemberFromMeetingList(modalWindowSelector, memberId, memberName);
                });
            }
            else{
                $(modalWindowSelector).find('.modal-body tbody').html('');
            }
        }

        /*
        function updateRemoveTemplateMembersButton(toShowButton){
            toShowButton ? $('.block-remove-members-buttons').show() : $('.block-remove-members-buttons').hide();
        }
        */

        /*
        $('.remove-members-check-all').click(function(e){
            e.preventDefault();
            var members = [], modalWindowSelector = $(this).parents('.modal').attr('id');

            $('#'+modalWindowSelector + " tbody tr .check-member").each(function(){
                $(this).prop('checked', true);
                var id = $(this).parents('tr').attr('data-id');

                if(modalWindowSelector === 'addEditMeetingModal'){
                    if(!in_array(id, window.selectedMeetingMembers)){
                        window.selectedMeetingMembers.push(id);
                    }
                }
                else {
                    if(!in_array(id, window.selectedTemplateMembers)){
                        window.selectedTemplateMembers.push(id);
                    }
                }
            });

            //updateRemoveTemplateMembersButton(true);
        });
        */

        /*
        $('.remove-members-cancel-all').click(function(e){
            e.preventDefault();
            var members = [], modalWindowSelector = $(this).parents('.modal').attr('id');

            $('#'+modalWindowSelector + " tbody tr .check-member").each(function(){
                $(this).prop('checked', false);
            });

            if(modalWindowSelector === 'addEditMeetingModal')
                window.selectedMeetingMembers = [];
            else
                window.selectedTemplateMembers = [];

            //updateRemoveTemplateMembersButton(false);
        });
        */

        /*
        $('.remove-members').click(function(e){
            e.preventDefault();
            var members = [], modalWindowSelector = $(this).parents('.modal').attr('id');

            $('#'+modalWindowSelector + " tbody tr").each(function(){
                var id = $(this).attr("data-id"), name = $(this).attr("data-name"), locality = $(this).attr("data-locality");

                if((modalWindowSelector === 'addEditMeetingModal' && !in_array(id, window.selectedMeetingMembers)) || ( modalWindowSelector === 'modalHandleTemplate' && !in_array(id, window.selectedTemplateMembers))){
                    members.push({id : id, name: name, locality : locality});
                }
            });

            buildMembersList('#'+modalWindowSelector, members);
        });
        */

        $(".meeting-add-btn").click(function(e){
            e.preventDefault();

            $(".block-add-members").css('display') === 'block' ? $(".block-add-members").css('display', 'none'): $(".block-add-members").css('display', 'block');
        });

        $(".meeting-clear-btn").click(function(e){
            e.preventDefault();

            var members = [], modalWindowSelector = $(this).parents('.modal').attr('id');

            /*
            if(modalWindowSelector === 'addEditMeetingModal')
                window.selectedMeetingMembers = [];
            else
                window.selectedTemplateMembers = [];
            */

            buildMembersList('#'+modalWindowSelector, members);
        });

        function getMeetingMembersToAdd(modalWindowSelector, members){
            var memberRows = [];

            if(!members || members.length === 0){
                $(modalWindowSelector).find('.members-available').html('');
            }
            else{
                for(var m in members){
                    var member = members[m];

                    memberRows.push(
                        '<div data-id="'+member.id+'" data-attend_meeting="'+member.attend_meeting+'" data-name="'+member.name+'" data-locality="'+member.locality+'">'+
                            '<span>'+ member.name + ' (' + member.locality + ')</span> '+
                            '<i data-id="'+member.id+'" class="fa fa-lg fa-plus addMeetingMember" title="Добавить" ></i>'+
                        '</div>'
                    );
                }

                $(modalWindowSelector + ' .members-available').html(memberRows.join(''));

                $(modalWindowSelector + ' .addMeetingMember').click(function(e){
                    e.stopPropagation();
                    var element = $(this).parents('div'),
                        id = element.attr("data-id"),
                        name = element.attr("data-name"),
                        locality = element.attr("data-locality"),
                        attendMeeting = element.attr('data-attend_meeting'),
                        mode = 'm',
                        membersId = [], list = [];

                    //if(modalWindowSelector === "#modalHandleTemplate"){
                        var checkbox = $(modalWindowSelector + ' .checkbox-block input[type="radio"]:checked');

                        if(!checkbox.length>0){
                            showError('Выберите один из пунктов: "Местности" и или "Участники".');
                            return;
                        }
                        else{
                            mode = checkbox.attr('data-field');
                        }
                    //}

                    // get existing list
                    $(modalWindowSelector + " tbody tr").each(function(){
                        var memberName = $(this).attr('data-name'),
                            memberId = $(this).attr('data-id'),
                            memberLocality = $(this).attr('data-locality'),
                            attendMeeting = $(this).attr('data-attend_meeting'),
                            present = $(this).find('.check-member-checkbox').prop('checked');
                            membersId.push(memberId);

                        list.push({id: memberId, name: memberName, locality: memberLocality, attend_meeting : attendMeeting, present : present});
                    });

                    list.sort(function (a, b) {
                      if (a.name > b.name) {
                        return 1;
                      }
                      if (a.name < b.name) {
                        return -1;
                      }
                      // a должно быть равным b
                      return 0;
                    });

                    if(mode === 'm' && in_array(id, membersId)){
                        showError("Этот участник уже добавлен в список.");
                    }
                    else if(mode === 'm' && !in_array(id, membersId)){
                        $(modalWindowSelector + ' .search-members').val('').focus();
                        list.push({id: id, name: name, locality: locality, attend_meeting: attendMeeting});
                        buildMembersList(modalWindowSelector, list);
                    }
                    else if(mode === 'l'){
                        $.post('/ajax/meeting.php?get_member', {localityId: id})
                        .done(function(data){
                            if(data.members){
                                var doubleMembers = [];

                                for(var i in data.members){
                                    var member = data.members[i];
                                    if(!in_array(member.id, membersId)){
                                        membersId.push(member.id);
                                        list.push({id: member.id, name: member.name, attend_meeting: member.attend_meeting, locality: member.locality});
                                    }
                                    else{
                                        doubleMembers.push(member.name);
                                    }
                                }

                                if(doubleMembers.length > 0){
                                    showError(( doubleMembers.length > 1 ? "Эти " : "Этот ") + ( doubleMembers.length > 1 ? "участники (" : "участник (") + doubleMembers.join(', ') + ") уже "+ ( doubleMembers.length > 1 ? "добавлены" : "добавлен") + " в список.");
                                }
                            }

                            $(modalWindowSelector + ' .members-available').html('');
                            $(modalWindowSelector + ' .search-members').val('').focus();

                            list.sort(function (a, b) {
                                if (a.name > b.name) {
                                    return 1;
                                }
                                if (a.name < b.name) {
                                    return -1;
                                }
                                // a должно быть равным b
                                return 0;
                            });
                            buildMembersList(modalWindowSelector, list);
                        });
                    }
                });
            }
        }

        function confirmToRemoveMemberFromMeetingList(modalWindowSelector, memberId, memberName){
            var confirmationWindow = $("#modalConfirmRemoveMember");
            confirmationWindow.find('.delete-member-from-list').attr('data-id', memberId).attr('data-modal', modalWindowSelector);
            confirmationWindow.find('.modal-body').html('Вы действительно хотите удалить '+ memberName +' из списка?');
            confirmationWindow.modal('show');
        }

        $('.delete-member-from-list').click(function(){
            var memberIdToDelete = $(this).attr('data-id'),
                members = [],
                modalWindowSelector = $(this).attr('data-modal');

            $(modalWindowSelector + " tbody tr").each(function(){
                var id = $(this).attr("data-id"), name = $(this).attr("data-name"), locality = $(this).attr("data-locality");
                if(id !== memberIdToDelete){
                    members.push({id : id, name: name, locality : locality});
                }
            });

            buildMembersList(modalWindowSelector, members);
        });

        function setFiltersForRequest(){
            var sort_type = 'desc',
                sort_field = 'date';

            $('#eventTabs').find(" a[id|='sort']").each (function (i) {
                if ($(this).siblings("i.icon-chevron-down").length) {
                    sort_type = 'asc';
                    sort_field = $(this).attr("id").replace(/^sort-/,'');
                }
                else if ($(this).siblings("i.icon-chevron-up").length) {
                    sort_type = 'desc';
                    sort_field = $(this).attr("id").replace(/^sort-/,'');
                }
            });

            var localityFilter = $("#selMeetingLocality").val();
            var meetingTypeFilter = $("#selMeetingCategory").val();

            var startDate = $(".start-date").val();
            var endDate = $(".end-date").val();

            var filters = [];
            filters = [{name: "sort_field", value: sort_field},
                        {name: "sort_type", value: sort_type},
                        {name: "meetingFilter", value: meetingTypeFilter || '_all_'},
                        {name: "localityFilter", value: localityFilter || '_all_'},
                        {name: "startDate", value: parseDate(startDate)},
                        {name: "endDate", value: parseDate(endDate)}];

            return filters;
        }

        function getRequestFromFilters(arr){
            var str = '';
            arr.map(function(item){
                str += ('&'+item["name"] +'='+item["value"]);
            });
            return str;
        }

        function loadMeetings(){
            var request = getRequestFromFilters(setFiltersForRequest());

            $.getJSON('/ajax/meeting.php?get_meetings'+request).done(function(data){
                refreshMeetings(data.meetings);
            });
        }

        /*
        function buildMeetingMembersList(list, meetingId, isSummaryMeeting){
            var tableRows = [];

            for (var i in list) {
                var m = list[i];
                var isAttended = m.attended === '1';

                tableRows.push('<tr class="'+(isAttended ? "highlight-member" : "")+'" data-id="'+m.member_key+'" data-birth_date="'+formatDate(m.birth_date)+'" >'+
                        '<td><input type="checkbox" '+( isAttended ? 'checked' : '')+'></td>'+
                        '<td>'+he(m.name)+'</td>'+
                        '<td>'+he(formatDate(m.birth_date))+'</td>'+
                        '</tr>');
            }

            $("#modalMeetingMembers tbody").html(tableRows.join(''));
            isSummaryMeeting ? $("#modalMeetingMembers .doSaveListMembers").hide() : $("#modalMeetingMembers .doSaveListMembers").show().attr('data-id', meetingId);
            $("#modalMeetingMembers").modal('show');
            $("#modalMeetingMembers .scroll-up, #modalMeetingStatistic .scroll-up").hide();
            $("#modalMeetingMembers tbody input[type='checkbox'], #modalMeetingMembers tbody tr").click(function(e){
                e.stopPropagation();
                var field, checkbox;

                if($(this).attr('data-id')){
                    field = $(this);
                    checkbox = $(this).find("input[type='checkbox']");

                    if(!checkbox.prop('checked')){
                        checkbox.prop('checked',true);
                        field.addClass('highlight-member');
                    }
                    else{
                        checkbox.prop('checked',false);
                        field.removeClass('highlight-member');
                    }
                }
                else {
                    if($(this).prop('checked'))
                        $(this).parents('tr').addClass('highlight-member');
                    else
                        $(this).parents('tr').removeClass('highlight-member');
                }

                filterMembersList();
            });
        }
        */

        function add(a, b) {
            return parseInt(a) + parseInt(b);
        }

        $('.meeting-saints-count, .meeting-count-fulltimers, .meeting-count-trainees, .meeting-count-guest').keyup(function(){
            var modal = $("#addEditMeetingModal");

            var count = '';
            var saintsCount = modal.find('.meeting-saints-count').val();
            var countGuests = modal.find('.meeting-count-guest').val();
            var countFulltimers = modal.find('.meeting-count-fulltimers').val();
            var countTrainees = modal.find('.meeting-count-trainees').val();
        });

        function getMeetingCounts(item){
            var traineesCount = 0, fulltimersCount = 0, guestCount = 0, childrenCount = 0,
                    listCount = 0, saintsCount = 0, countMembers, fulltimersInSaintsList = 0;

            fulltimersInSaintsList = parseInt(item.fulltimers_in_list.split(',').reduce(add, 0));
            listCount = parseInt(item.list_count.split(',').reduce(add, 0)) + parseInt(item.add_list_count);
            saintsCount = parseInt(item.saints_count.split(',').reduce(add, 0)) - fulltimersInSaintsList;
            guestCount = parseInt(item.guests_count.split(',').reduce(add, 0));
            childrenCount = parseInt(item.children_count.split(',').reduce(add, 0));

            if(item.show_additions === '1'){
                fulltimersCount = parseInt(item.fulltimers_count.split(',').reduce(add, 0));
                traineesCount = parseInt(item.trainees_count.split(',').reduce(add, 0));
            }

            countMembers = saintsCount + guestCount + fulltimersCount + traineesCount;

            return{
                traineesCount : traineesCount, fulltimersCount:fulltimersCount, guestCount:guestCount, childrenCount: childrenCount,
                listCount : listCount, saintsCount:saintsCount , countMembers:countMembers
            };
        }

        function refreshMeetings(meetings){
            var tableRows = [], phoneRows = [];
            var isSingleCity = parseInt('<?php echo $isSingleCity; ?>');

            for (var i in meetings){
                var m = meetings[i], dataString, meetingCounts = getMeetingCounts(m);

                dataString = 'data-members="'+m.members+'" data-district="'+m.district+'" data-summary="'+m.summary+'" data-extra_fields="'+m.show_additions+'" '+
                    'class="meeting-row '+(parseInt(m.summary) ? 'meeting-summary' : '')+' " data-note="'+he(m.note)+'" data-type="'+m.meeting_type+'" data-date="'+m.date+'" '+
                    'data-count="'+meetingCounts.countMembers+'" data-fulltimers="'+meetingCounts.fulltimersCount+'" data-trainees="'+meetingCounts.traineesCount+'" '+
                    'data-count_list="'+meetingCounts.listCount+'" data-saints_count="'+meetingCounts.saintsCount+'" '+
                    'data-count_guests="'+meetingCounts.guestCount+'" data-count_children="'+meetingCounts.childrenCount+'" data-id="'+m.id+'" '+
                    ' data-locality="'+m.locality_key+'" data-participants="'+m.participants+'" ';

                tableRows.push('<tr '+dataString +'>'+
                    '<td>' + formatDate(m.date) + '</td>' +
                    '<td class="meeting-name">' + he(m.name || '') + '</td>' +
                    (isSingleCity ? '' : '<td>' + he(m.locality_name ? (m.locality_name.length>20 ? m.locality_name.substring(0,18)+'...' : m.locality_name) : '') + '</td>') +
                    '<td>' + (meetingCounts.listCount || '') + '</td>' +
                    '<td>' + (meetingCounts.saintsCount || '') + '</td>' +
                    '<td>' + (meetingCounts.countMembers || '') + '</td>' +
                    '<td><!--<i class="fa fa-list fa-lg meeting-list" title="Список"></i>--><i title="Удалить" class="fa fa-trash fa-lg btn-remove-meeting"></i></td>' +
                    '</tr>'
                );

                phoneRows.push('<tr '+dataString+'>'+
                    '<td><span>' + he(m.name) + '</span>'+
                    '<i style="float: right;" title="Удалить" class="fa fa-trash fa-lg btn-remove-meeting"></i>'+
                    //'<i style="float: right;" class="fa fa-list fa-lg meeting-list" title="Список"/>'+
                    '<div class="meeting-name"><strong>' + he(m.name) + '</strong></div>' +
                    '<div>'+formatDate(m.date)+'</div>'+
                    (isSingleCity ? '' : '<div>'+ he(m.locality_name ? (m.locality_name.length>20 ? m.locality_name.substring(0,18)+'...' : m.locality_name) : '') + '</div>') +
                    (meetingCounts.listCount ? ('<div class="meeting-list-counts" >По списку: '+ meetingCounts.listCount +'</div>' ) : '')+
                    (meetingCounts.saintsCount ? ('<div class="meeting-saints-counts" >На собрании: '+ meetingCounts.saintsCount + '</div>') : '') +
                    (meetingCounts.countMembers ? ('<div class="meeting-list-counts" >Всего: '+ meetingCounts.countMembers +'</div>') : '')+
                    '</td>' +
                    '</tr>'
                );
            }

            $(".desctopVisible tbody").html (tableRows.join(''));
            $(".show-phone tbody").html (phoneRows.join(''));

            $(".meeting-row").unbind('click');
            $(".meeting-row").click (function () {
                var element = $(this);
                var note = element.attr('data-note');
                var date = element.attr('data-date');
                var count = element.attr('data-count');
                var countList = element.attr('data-count_list');
                var countGuests = element.attr('data-count_guests');
                var countChildren = element.attr('data-count_children');
                var locality = element.attr('data-locality');
                var meetingType = element.attr('data-type');
                var meetingName = element.find('.meeting-name').text();
                var meetingId = element.attr('data-id');
                var saintsCount = element.attr('data-saints_count');
                var countFulltimers = element.attr('data-fulltimers');
                var countTrainees = element.attr('data-trainees');
                var members = element.attr('data-members');
                var textMode = 'Карточка собрания';
                var participants = element.attr('data-participants').split(',');

                var isMeetingSummary = element.attr('data-summary') === '1';
                parseInt(element.attr('data-extra_fields')) ? $(".show-extra-fields").show() : $(".show-extra-fields").hide();

                $("#addEditMeetingModal").find('.btnDoHandleMeeting').attr('data-id', meetingId).attr('data-locality', locality).attr('data-date', date).attr('data-meeting_type',meetingType);
                $("#addEditMeetingModal").attr('data-id', meetingId);

                console.log(members);
                fillMeetingModalForm(textMode, formatDate(date), locality, meetingType, note, countList, count, countGuests, countChildren, countFulltimers, countTrainees, isMeetingSummary, saintsCount, meetingName, members, participants);
            });

            /*
            $('.meeting-list').unbind('click');
            $('.meeting-list').click(function(e){
                e.stopPropagation();
                var meetingId = $(this).parents('tr').attr('data-id');
                var locality = $(this).parents('tr').attr('data-locality');
                var date = $(this).parents('tr').attr('data-date');
                var isSummaryMeeting = $(this).parents('tr').attr('data-summary') == '1';
                var meetingType = $(this).parents('tr').attr('data-type');

                $("#modalMeetingMembers .filter-stat-members-checked, #modalMeetingMembers .filter-stat-members-unchecked").removeClass('active');
                $("#modalMeetingMembers .search-meeting-members").val('');

                $.post('/ajax/meeting.php?get_list', {meeting_id: meetingId, locality : locality, date : date, is_summary_meeting: isSummaryMeeting, meeting_type: meetingType})
                .done(function(data){
                    buildMeetingMembersList(data.list, meetingId, isSummaryMeeting);
                });
            });
            */

            $('.btn-remove-meeting').unbind('click');
            $('.btn-remove-meeting').click(function(e){
                e.stopPropagation();
                var meetingId = $(this).parents('tr').attr('data-id'),
                    modal = $("#modalRemoveMeeting");
                modal.find(".remove-meeting").attr("data-id", meetingId);
                modal.modal("show");
            });
        }

        $(".remove-meeting").click(function(e){
            e.stopPropagation();
            var meetingId = $(this).attr('data-id');
            var request = getRequestFromFilters(setFiltersForRequest());

            $("#modalRemoveMeeting").modal('hide');
            $.post('/ajax/meeting.php?remove'+request, {meeting_id: meetingId})
            .done(function(data){
                refreshMeetings(data.meetings);
            });
        });

        /*
        $(".doSaveListMembers").click(function(){
            var members = [], meetingId = $(this).attr('data-id'), childrenCount = 0;

            $("#modalMeetingMembers tbody tr input[type='checkbox']:checked").each(function(){
                var element = $(this).parents('tr');

                members.push(element.attr('data-id'));

                var birthDate = element.attr('data-birth_date');
                var dateParts = birthDate.split(".");
                var date = new Date(dateParts[2], (dateParts[1] - 1), dateParts[0]);
                var age = getAge(date);

                if(age > 0 && age < 12 ){
                    childrenCount ++;
                }
            });

            var request = getRequestFromFilters(setFiltersForRequest());
            $.getJSON('/ajax/meeting.php?set_list'+request, {meeting_id:meetingId, list: members.join(','), children_count:childrenCount })
            .done (function(data) {
                refreshMeetings(data.meetings);
                $('#modalMeetingMembers').modal('hide');
            });
        });
        */

        $("#selMeetingCategory, #selMeetingLocality").change(function(){
            filterMeetingsList();
        });

        $("#meetingCategory").change(function(){
            var text = $('#meetingCategory option:selected').text();
            $(".meetingName").val(text);
            update_members_list();
        });

        $("#meetingLocalityModal").change(function(){
            if($(this).parents("#addEditMeetingModal").is(':visible')){
                var locality = $(this).val();

                chechExtraFields(locality);
                update_members_list();
            }
        });

        function update_members_list(){
            var meetingType = $("#meetingCategory").val()
            var locality = $("#meetingLocalityModal").val()
            var title = $("#addEditMeetingModal #titleMeetingModal").text().trim()
            var countLocalities = $('#meetingLocalityModal option').size();

            if(countLocalities == 1){
                locality = $("#meetingLocalityModal option:first").val()
                $("#meetingLocalityModal").val(locality).change()
            }

            if((meetingType === 'LT' || meetingType === 'PM') && locality && title === 'Новое собрание'){

                $.post('/ajax/meeting.php?get_locality_members', {localityId : locality})
                .done(function(data){
                    var members = data.members, membersArr = [];
                    if(members.length > 0){
                        for(var m in members){
                            var member = members[m]['member'];
                            var memberArr = member.split(':');
                            membersArr.push({id: memberArr[0], name: memberArr[1], locality: memberArr[2], attend_meeting: memberArr[3], present : in_array(memberArr[0], [])});
                        }
                        buildMembersList("#addEditMeetingModal", membersArr);
                    }
                    else{
                        var modalWindow = $("#addEditMeetingModal");
                        modalWindow.find('.members-available').html('');
                        modalWindow.find('tbody').html('');
                    }
                })
            }
        }

    });
})();
