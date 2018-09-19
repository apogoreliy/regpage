<?php
    include_once "header.php";
    include_once "nav.php";
    include_once "modals.php";    

    $sort_field = isset ($_COOKIE['sort_field_reference']) ? $_COOKIE['sort_field_reference'] : 'name';
    $sort_type = isset ($_COOKIE['sort_type_reference']) ? $_COOKIE['sort_type_reference'] : 'asc';

    $references = db_getReferences($sort_field, $sort_type);
?>

<div class="container">
    <div class="reference-content tab-content">
        <div>
            <a class="btn btn-primary btn-add-reference"><i class="fa fa-plus" title="Добавить"></i> <span class="hide-name">Добавить</span></a>
        </div>
        <?php
            if(!$references){
                echo '<div style="text-align: center;"><h4>На данный момент нет созданных справок</h4></div>';
            }
            else{
        ?>
            <div class="desctop-references-header" style="margin-top: 20px; border-bottom: 1px solid #f5f5f5; height: 30px;">
                <div class="span4">
                    <a id="sort-name" href="#" title="сортировать">Название</a>&nbsp;
                        <i class="<?php echo $sort_field == 'name' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i>
                </div>
                <div class="span2">
                    <a id="sort-page_name" href="#" title="сортировать">Страница</a>&nbsp;
                        <i class="<?php echo $sort_field == 'page_name' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i>
                </div>
                <div class="span2">
                    <a id="sort-block_name" href="#" title="сортировать">Блок на главной</a>&nbsp;
                    <i class="<?php echo $sort_field == 'block_name' ? ($sort_type=='desc' ? 'icon-chevron-up' : 'icon-chevron-down') : 'icon-none'; ?>"></i>
                </div>
                <div class="span2">Опубликовано</div>                
                <div class="span1">&nbsp;</div>
            </div>
            <div class="references-list">
                <div class="desctop-references"></div>
                <div class="phone-references"></div>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Users Emails Modal -->
<div id="modalAddReference" data-width="400" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Карточка инструкции</h3>
    </div>
    <div class="modal-body">     
        <div >
            <label class="">Название инструкции</label>
            <input class=" name" type="text" />
        </div>
        <div >
            <label class="">Ссылка на инструкцию</label>
            <input class=" link-article" type="text" />
        </div>
        <div>
            <label class="">Страница на сайте</label>
            <select class=" page" >
                <?php
                $pages = db_getPages();
                foreach ($pages as $key => $page) {
                    echo "<option value='".$key."' >".$page."</option>";
                }
                ?>
            </select>
        </div>
        <div >
            <label class="">Блок на главной</label>
            <select class="block" >
                <?php
                echo "<option value='_none_'>&nbsp;</option>";
                
                $blocks = db_getReferencesBlock();
                foreach ($blocks as $key => $block) {
                    echo "<option value='".$key."' >".$block."</option>";
                }
                ?>
            </select>
        </div>
        <div >
            <label class="">Опубликовано</label>
            <select class=" published">
                <option value='0' selected>НЕТ</option>
                <option value='1'>ДА</option>
            </select>
        </div>
        <div >
            <label class="">Приоритет</label>
            <input class="priority" type="text" />
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary handle-reference" ></button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>

<div id="modalDeleteReference" class="modal hide fade" tabindex="-1" role="dialog"  aria-labelledby="regEndedTitle" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 id="regEndedTitle">Подтверждение удаления справки</h4>
    </div>
    <div class="modal-body">
        <h4>Вы действительно хотите удалить справку: <em class="deleted-reference"></em> ?</h4>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary delete-reference">Да</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отмена</button>
    </div>
</div>

<script>
$(document).ready(function(){
   loadReferences(); 
});

$("a[id|='sort']").click (function (){
    var id = $(this).attr("id"), icon = $(this).siblings("i");

    $(".desctop-references-header" + " a[id|='sort'][id!='"+id+"'] ~ i").attr("class","icon-none");
    icon.attr ("class", icon.hasClass("icon-chevron-down") ? "icon-chevron-up" : "icon-chevron-down");

    setCookie('sort_field_reference', id.replace(/^sort-/,''));
    setCookie('sort_type_reference', icon.hasClass("icon-chevron-down") ? "asc" : "desc");

    loadReferences();
});

$(".btn-add-reference").click(function(e){
    e.preventDefault();
    
    handleReferenceFields(true);
    $("#modalAddReference").find('.handle-reference').addClass('add-reference').removeClass('set-reference').attr('data-id', '').html('Добавить');
    
    $(".add-reference").unbind('click');
    $(".add-reference").click(function(){
        var modal = $("#modalAddReference"), referenceFieldsValue = handleReferenceFields();

        if(referenceFieldsValue.name.trim() === '' || referenceFieldsValue.link_article.trim() === ''){
            showError("Необходимо указать название и ссылку для справки");
            return;
        }
        else{
            $.post('/ajax/reference.php?add', referenceFieldsValue)
            .done(function(data){
                modal.modal('hide');
                renderReferences(data.references);
            });
        }
    });
    
    $("#modalAddReference").modal('show');
});

function handleReferenceFields(data = false){
    var modal = $("#modalAddReference");
    
    if(data){
        modal.find('.name').val(data.name || '');
        modal.find('.link-article').val(data.link_article || '');
        modal.find('.page').val(data.page || 'Главная');
        modal.find('.block').val(data.block || 0);
        modal.find('.published').val(data.published || 0);
        modal.find('.priority').val(data.priority || 0);
    }    
    else{
        var name = modal.find('.name').val(),
            link_article = modal.find('.link-article').val(),
            page = modal.find('.page').val(),
            block = modal.find('.block').val(),
            published = modal.find('.published').val(),
            id = modal.find('.handle-reference').attr('data-id');
            priority = modal.find('.priority').val();
                
        return {
            name :name,
            link_article :link_article,
            page : page,
            block : block === '_none_' ? 0 : block,
            published : published,
            id : id,
            priority : priority
        };
    }
}

function loadReferences(){
    $.getJSON('/ajax/reference.php?get')
    .done(function(data){
        if(data.references){
            renderReferences(data.references);
        }        
    });
}

function renderReferences(references){
    var resultDesctop = [], resultPhone = [];
    
    for(var i in references){
        var r = references[i];

        resultDesctop.push('<div class="reference-item" data-priority="'+r.priority+'" data-id="'+r.id+'">'+
                        '<div class="span4">'+
                            '<a class="reference-name" data-name="'+r.name+'" href="'+r.link_article+'" target="_blank">'+
                                ( r.name.length > 60 ? r.name.substring(0, 60)+'...' :  r.name) +
                            '</a>'+
                        '</div>'+
                        '<div class="span2 reference-page" data-page="'+r.page+'">'+r.page_name+'</div>'+
                        '<div class="span2 reference-block" data-block="'+r.block_num+'">'+r.block_name+'</div>'+
                        '<div class="span2"><input type="checkbox" class="reference-published set-field" ' + ( r.published === '1' ? "checked" : "" ) + ' data-field="published" /></div>'+                        
                        '<div class="span1"><span class="fa fa-trash fa-lg btn-delete-reference" title="Удалить"></span></div>'+
                    '</div>');
        
        resultPhone.push('<div class="reference-item" data-priority="'+r.priority+'" data-id="'+r.id+'">'+
                        '<div><span>Название: </span><a class="reference-name" data-name="'+ r.name +'" href="'+r.link_article+'" target="_blank">'+( r.name.length > 60 ? r.name.substring(0, 60)+'...' :  r.name)+'</a></div>'+
                        '<div class="reference-page" data-page="'+r.page+'"><span>Страница: </span>'+r.page_name+'</div>'+
                        '<div class="reference-block" data-block="'+r.block_num+'"><span>Блок: </span>'+r.block_name+'</div>'+
                        '<div class=""><span>Опубликовано: </span><input type="checkbox" class="reference-published set-field" ' + ( r.published === '1' ? "checked" : "" ) + ' data-field="published" /></div>'+                        
                        '<div class=""><span class="fa fa-trash fa-lg btn-delete-reference" title="Удалить"></span></div>'+
                    '</div>');
    }
    
    $(".references-list .desctop-references").html(resultDesctop.join(''));
    $(".references-list .phone-references").html(resultPhone.join(''));

    $(".reference-name").unbind('click');
    $(".reference-name").click(function(e){
        e.stopPropagation();
    });
    
    $(".btn-delete-reference").unbind('click');
    $(".btn-delete-reference").click(function(e){
        e.stopPropagation();
        var id = $(this).parents('.reference-item').attr('data-id'), 
            name= $(this).parents('.reference-item').find('.reference-name').text(), 
            modal = $("#modalDeleteReference");
        
        modal.find('.deleted-reference').html(name).parents('#modalDeleteReference').find('.delete-reference').attr('data-id', id);
        modal.modal('show');
    });
    
    $(".set-field").unbind('click');
    $(".set-field").click(function(e){
        e.stopPropagation();
    });
    
    $(".set-field").unbind('change');
    $(".set-field").change(function(e){
        e.stopPropagation();
        var value = $(this).prop('checked') === true ? 1 : 0, id = $(this).parents('.reference-item').attr('data-id'), field = $(this).attr('data-field');
       
        $.post('/ajax/reference.php?set_field', {field: field, value : value, id : id});
    });   
    
    $(".reference-item").unbind('click');
    $(".reference-item").click(function(e){
        e.stopPropagation();
        
        var id = $(this).attr('data-id'), 
            modal = $("#modalAddReference"),
            name = $(this).find('.reference-name').attr('data-name').trim(),
            link_article = $(this).find('.reference-name').attr('href').trim(),
            page = $(this).find('.reference-page').attr('data-page'),
            published = $(this).find('.reference-published').prop('checked') === true ? 1 : 0,
            block = $(this).find('.reference-block').attr('data-block');
            priority = $(this).attr('data-priority');
            
        handleReferenceFields({
            name : name,
            link_article : link_article,
            page : page,
            block : block,
            published : published,
            priority: priority
        });
        
        modal.find('.handle-reference').removeClass('add-reference').addClass('set-reference').attr('data-id', id).html('Изменить');
        
        $(".set-reference").unbind('click');
        $(".set-reference").click(function(){
            var modal = $("#modalAddReference"), referenceFieldsValue = handleReferenceFields();

            if(referenceFieldsValue.name.trim() === '' || referenceFieldsValue.link_article.trim() === ''){
                showError("Необходимо указать название и ссылку для справки");
                return;
            }
            else{
                $.post('/ajax/reference.php?set', referenceFieldsValue)
                .done(function(data){
                    modal.modal('hide');
                    renderReferences(data.references);
                });
            }
        });
    
        modal.modal('show');
    });
}

$(".delete-reference").click(function(){
    var id = $(this).attr('data-id');
    
    $.post('/ajax/reference.php?delete', {id : id})
    .done(function(data){
        renderReferences(data.references);
        $("#modalDeleteReference").modal('hide');
    });
});
</script>