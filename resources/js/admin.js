import "lightbox2";

var pagiTarget = {
    'userList'      :       true,
    'tagList'       :       true,
    'cityList'      :       true,
    'profileTicket' :       true,
    'userTicket'    :       true,
};

var pagiCount = {
    'userList'      :   0,
    'tagList'       :   0,
    'cityList'      :   0,
    'profileTicket' :   0,
    'userTicket'    :   0
}

$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    main();
})

function main() {

    $('a.tab').one('click',function () {
        renderContent(this);
    });

    $('#showTabsMenu').on('click',function() {
        if ($('.tabsPills').hasClass('show')){
            $('.tabsPills').removeClass('show');
            $('.friendsList').removeClass('show');
            $(this).html('<i class="fas fa-arrow-left"></i>');
            setTimeout(function(){
                $('.darkOverlay').addClass('d-none');
            }, 900);
        }else{
            $('.tabsPills').addClass('show');
            $('.darkOverlay').removeClass('d-none');
            $(this).html('<i class="fas fa-times"></i>');
            
            $('.darkOverlay').one('click',function(){
                $('.tabsPills').removeClass('show');
                $('#showTabsMenu').html('<i class="fas fa-arrow-left"></i>');
                setTimeout(function(){
                    $('.darkOverlay').addClass('d-none');
                }, 900);
            });

        }
    });

    $('#infoNotDesc').emojioneArea({
        pickerPosition: "left",
        placeholder: "\xa0",
        autocomplete:true,
        shortcuts: false
    });

    $('#infoMailTitle').emojioneArea({
        inline:true,
        pickerPosition: "bottom",
        placeholder: "\xa0",
        autocomplete:true,
        shortcuts: false
    });

    $('#infoMailDesc').emojioneArea({
        pickerPosition: "bottom",
        placeholder: "\xa0",
        autocomplete:true,
        shortcuts: false
    });

    $('#adminInfoForm').on('submit',function(e) {
        e.preventDefault()
        let url = baseUrl + "/admin/ajax/wideInfo";
        let tag = $(this);

            $(document).one("ajaxSend", function(){
                tag[0].reset();
                $('.emojionearea-editor').empty();
                $('#adminPicture-preview').empty();
                $('.spinnerOverlay').removeClass('d-none');
            });

        var request = $.ajax({
            method: "post",
            url: url,
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            data: new FormData(this)
        });

        request.done(function(response){
            if (response.status === 'success') {
                $('.spinnerOverlay').addClass('d-none');
                alert("Powiadomienia zostały wysłane");
            }
        });
        
        
        request.fail(function (xhr){
            alert(xhr.responseJSON.message);
        });
    });


}

function renderContent(selected) {
    let targetId = $(selected).attr('id');
    let url = __baseUrl+'/admin/ajax/tab';

    $(document).one("ajaxSend", function(){
        let html = ' <div class="spinner-border text-dark" role="status">'+
         '<span class="sr-only">Loading...</span>'+
        '</div>';
        $('#'+targetId+'-content').html(html);
    });

    var request = $.ajax({
        method : 'get',
        url: url,
        data: {target:targetId}
    });
    
    
    request.done(function(response){
        if (response.status === 'success') {
            $('#'+targetId+'-content').html(response.html);
            if (response.amount == 0) {
                $('#'+targetId+'Count').html('');
            }else{
                $('#'+targetId+'Count').html(response.amount);
            }

            $('#'+targetId+'-content').on('scroll',function() {
                if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 200) {
                    $(this).off('scroll');
                    pagiContent(targetId);
                }
             });

            $('button.ticketBtn').off('click');
            $('button.ticketBtn').on('click',function(e) {
                e.preventDefault();
                if (confirm(confirmMsg)) {
                    $('.spinnerOverlay').removeClass('d-none');
                    carryTicket(this,targetId);
                }
            });

            $('button.listBtn').off('click');
            $('button.listBtn').on('click',function(e) {
                e.preventDefault();
                if (confirm(confirmMsg)) {
                    carryList(this,targetId);
                }
            });

            $('span.fetchBtn').tooltip();

            $('span.searchBtn').tooltip();

            $('span.fetchBtn').on('click',function() {
                $(this).addClass('spin');
                fetchContent(this);
            });

            $('.searchForm').on('submit',function(e) {
                e.preventDefault();
                search(this);
            });

        }
    });
    
    
    request.fail(function (xhr){
        alert(xhr.responseJSON.message);
        $('#'+targetId+'-content').html('');
    });

}

function fetchContent(selected) {
    let targetId = $(selected).attr('id').split('-');
    targetId = targetId[0];
    let url = __baseUrl+'/admin/ajax/tab';

    var request = $.ajax({
        method : 'get',
        url: url,
        data: {target:targetId}
    });
    
    
    request.done(function(response){
        if (response.status === 'success') {
            $('.tooltip:first').remove();
            $('#'+targetId+'-content').html(response.html);
            if (response.amount == 0) {
                $('#'+targetId+'Count').html('');
            }else{
                $('#'+targetId+'Count').html(response.amount);
            }

            $('#'+targetId+'-content').off('scroll');
            $('#'+targetId+'-content').on('scroll',function() {
                if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 200) {
                    $(this).off('scroll');
                    pagiContent(targetId);
                }
             });

            $('button.ticketBtn').off('click');
            $('button.ticketBtn').on('click',function(e) {
                e.preventDefault();
                if (confirm(confirmMsg)) {
                    $('.spinnerOverlay').removeClass('d-none');
                    carryTicket(this,targetId);
                }
            });

            $('button.listBtn').off('click');
            $('button.listBtn').on('click',function(e) {
                e.preventDefault();
                if (confirm(confirmMsg)) {
                    carryList(this,targetId);
                }
            });

            $('span.fetchBtn').tooltip();

            $('span.searchBtn').tooltip();

            $('span.fetchBtn').on('click',function() {
                $(this).addClass('spin');
                fetchContent(this);
            });

            $('.searchForm').on('submit',function(e) {
                e.preventDefault();
                search(this);
            });

        }
    });
    
    
    request.fail(function (xhr){
        alert(xhr.responseJSON.message);
        $('#'+targetId+'-content').html('');
    });

}

function carryTicket(decided,target) {
    
    let decision = $(decided).attr('name');
    let ticketId = $(decided).parent().serialize();

    var request = $.ajax({
        method : 'post',
        url: __baseUrl+'/admin/ajax/ticket',
        data: {"_method": "PATCH", ticketId:ticketId, decision:decision}
    });
    
    
    request.done(function(response){
        if (response.status === 'success') {
            $(decided).parent().parent().parent().remove();
            let currentAmount = $('#'+target+'Count').text();
            let currentAmountNot = $('#descSys').text();

            if (currentAmount-1 == 0) {
                $('#'+target+'Count').html('');
            }else{
                $('#'+target+'Count').html(parseInt(currentAmount)-1);
            }

            if (currentAmountNot-1 == 0) {
                $('.systemNotificationsCount').html('');
            }else{
                $('.systemNotificationsCount').html(parseInt(currentAmountNot)-1);
            }
            $('a.'+ticketId.substring(9)).remove();
            if($('#descSys').html().trim() == ""){
                $('div.systemNotifications').html('<div class="text-center">'+noNotifications+'</div>');
            }
            $('.spinnerOverlay').addClass('d-none');
        }
    });
    
    
    request.fail(function (xhr){
        alert(xhr.responseJSON.message);
    });
}

function carryList(decided,target) {
    let decision = $(decided).attr('name');
    if (decision == 'edit') {
        var editValue = prompt("Nowa Nazwa");
    }else{
        var editValue = "";
    }
    $('.spinnerOverlay').removeClass('d-none');
    let elementId = $(decided).parent().serialize();

    var request = $.ajax({
        method : 'post',
        url: __baseUrl+'/admin/ajax/list',
        data: {"_method": "PATCH", elementId:elementId, decision:decision, editValue:editValue, target:target}
    });

    request.done(function(response){
        if (response.status === 'success') {
            switch (decision) {
                case 'delete':
                    $(decided).parent().parent().parent().remove();
                    $('.spinnerOverlay').addClass('d-none');
                    break;
            
                case 'edit':
                    $(decided).parent().parent().prev().prev().html(editValue);
                    $('.spinnerOverlay').addClass('d-none');
                default:
                    $(decided).addClass('alreadySent');
                    $('.spinnerOverlay').addClass('d-none');
                    break;
            }
        }
    });
    
    
    request.fail(function (xhr){
        alert(xhr.responseJSON.message);
        $('.spinnerOverlay').addClass('d-none');
    });
}

function pagiContent(target) {
    if (pagiTarget[target]) {

        let url = __baseUrl+'/admin/ajax/pagiContent';
        pagiCount[target] = pagiCount[target]+1;

        var request = $.ajax({
            method : 'get',
            url: url,
            data: {pagiTarget:target, pagiCount:pagiCount[target]}
        });
        
        
        request.done(function(response){
            if (response.status === 'success') {
                pagiTarget[target] = response.pagiNext;
                $('#'+target+'-table').append(response.html);
 
                $('#'+target+'-content').on('scroll',function() {
                    if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 200) {
                        $(this).off('scroll');
                        pagiContent(target);
                    }
                });

                $('button.ticketBtn').off('click');
                $('button.ticketBtn').on('click',function(e) {
                    e.preventDefault();
                    if (confirm(confirmMsg)) {
                        $('.spinnerOverlay').removeClass('d-none');
                        carryTicket(this,target);
                    }
                });
    
                $('button.listBtn').off('click');
                $('button.listBtn').on('click',function(e) {
                    e.preventDefault();
                    if (confirm(confirmMsg)) {
                        carryList(this,target);
                    }
                });
    
                $('span.fetchBtn').off('click');
                $('span.fetchBtn').on('click',function() {
                    $(this).addClass('spin');
                    fetchContent(this);
                });
            }
        });
        
        
        request.fail(function (xhr){
            alert(xhr.responseJSON.message);
        });
    }
}

function search(form) {
    let targetId = $(form).data('target');
    let searchCriteria = $('#'+targetId+'Search-input').val().trim();

    if (searchCriteria != "") {

        let url = baseUrl + "/admin/ajax/searchList";

        var request = $.ajax({
            method : 'get',
            url: url,
            data: {target:targetId,criteria:searchCriteria}
        });
        
        
        request.done(function(response){
            if (response.status === 'success') {
                $('#'+targetId+'-searchOut').html(response.html);
                $('#'+targetId+'-searchOut').find('button.ticketBtn').off('click');
                $('#'+targetId+'-searchOut').find('button.ticketBtn').on('click',function(e) {
                    e.preventDefault();
                    if (confirm(confirmMsg)) {
                        $('.spinnerOverlay').removeClass('d-none');
                        carryTicket(this,targetId);
                    }
                });

                $('#'+targetId+'-searchOut').find('button.listBtn').off('click');
                $('#'+targetId+'-searchOut').find('button.listBtn').on('click',function(e) {
                    e.preventDefault();
                    if (confirm(confirmMsg)) {
                        carryList(this,targetId);
                    }
                });
            }
        });
        
        
        request.fail(function (xhr){
            alert(xhr.responseJSON.message);
        });
    }
}