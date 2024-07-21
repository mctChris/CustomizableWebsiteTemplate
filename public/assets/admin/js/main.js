$(document).ready(function(){
    $('.btn-save-main-form').on('click', function(){
        window.onbeforeunload = null;
        if(typeof form_validate == 'function'){
            if(!form_validate()){
                return;
            }
        }
        document.getElementById('mainForm').submit();
    })
    $('.btn-save-delete-form').on('click', function(){
        if(confirm("Do you want to delete the record(s)?")){
            app.selected_bulk_action = 'delete'
            app.$nextTick(function(){
                window.onbeforeunload = null;
                document.getElementById('deleteForm').submit();
            })
        }
    })
    if(config){
        $('.nav > .nav-item.nav-dropdown .nav-link').each(function(){
            if($(this).data('section') == config.breadcrumb_section){
                $(this).addClass('active');
                $(this).parents('.nav-dropdown').addClass('open')
            }
        })
        $('.nav > .nav-item > .nav-link').each(function(){
            if($(this).data('section') == config.breadcrumb_section){
                $(this).addClass('active');
            }
        })
    }
    $('.permissions-tb .permissions-type').on('click', function(){
        var index = $(this).parent().index() + 1;
        var checkbox_list = $('.permissions-tb tr td:nth-child(' + index + ')').find('input[type="checkbox"]');
        checkOrUncheckAll(checkbox_list);
    })
    $('.permissions-tb .permissions-section').on('click', function(){
        var checkbox_list = $(this).parent().parent().find('input[type="checkbox"]');
        checkOrUncheckAll(checkbox_list);
    })

    $('.checkbox-select-all').on('click', function(){
        var checkbox_list = $(this).parents('table').eq(0).find('input[type="checkbox"]');
        checkOrUncheckAll(checkbox_list);
    })

    initFormChangeDetection();

    window.onbeforeunload = function() {
        if(formHasChanges()){
            return 'Are you sure to leave this page without save?';
        }
    };

    $('.navbar-toggler').on('click', function(){
        if($('body').hasClass('sidebar-lg-show')){
            $('body').removeClass('sidebar-lg-show sidebar-show');
        }else{
            $('body').addClass('sidebar-lg-show sidebar-show');
        }
    })

    $('.nav-dropdown > a').on('click', function(){
        $(this).parent().toggleClass('open')
    })

    $('.badge-status').on('click', function(){
        var self = $(this);
        self.addClass('--loading');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: config.endpoints.bulk_action,
            data: {
                id: $(this).data('id'),
                action: $(this).data('is_show') == 1 ? 'offline' : 'online'
            },
            success: function(data){
                var result = data.result[0];
                self.data('is_show', result.is_show);
                self.removeClass('badge-success badge-danger badge-warning');
                if(result.is_show == 0){
                    self.addClass('badge-danger');
                    self.text('Offline');
                }else if(result.is_show == 1){
                    self.addClass('badge-success');
                    self.text('Online');
                }
            },
            complete: function(){
                self.removeClass('--loading');
            }
        })
    })

})

function getMainFormSerialize(){
    if(tinyMCE){
        tinyMCE.triggerSave();
    }
    return $('#mainForm').serialize().replace(encodeURIComponent('<p><br data-mce-bogus="1"></p>'), '');
}

function initFormChangeDetection() {
    window.initialMainFormState = getMainFormSerialize();
}
function formHasChanges() {
    return getMainFormSerialize() != window.initialMainFormState;
}
 function checkOrUncheckAll(checkbox_list){
    var checked_all = true;
    checkbox_list = checkbox_list.filter(function(value){
        return !$(this).attr('disabled')
    })
    checkbox_list.each(function(){
        if($(this).attr('checked') != 'checked'){
            checked_all = false;
        }
    })
    if(checked_all){
        checkbox_list.prop('checked', false).removeAttr('checked')
    }else{
        checkbox_list.prop('checked', true).attr('checked','checked');
    }
 }