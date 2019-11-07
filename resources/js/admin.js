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
    })
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

            $('button.ticketBtn').on('click',function(e) {
                e.preventDefault();
                if (confirm(confirmMsg)) {
                    $('.spinnerOverlay').removeClass('d-none');
                    carryTicket(this,targetId);
                }
            })

            $('button.listBtn').on('click',function(e) {
                e.preventDefault();
                if (confirm(confirmMsg)) {
                    carryList(this,targetId);
                }
            })

            $('span.fetchBtn').on('click',function() {
                $(this).addClass('spin');
                fetchContent(this);
            })

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
            $('#'+targetId+'-content').html(response.html);
            if (response.amount == 0) {
                $('#'+targetId+'Count').html('');
            }else{
                $('#'+targetId+'Count').html(response.amount);
            }

            $('button.ticketBtn').on('click',function(e) {
                e.preventDefault();
                if (confirm(confirmMsg)) {
                    $('.spinnerOverlay').removeClass('d-none');
                    carryTicket(this,targetId);
                }
            })

            $('button.listBtn').on('click',function(e) {
                e.preventDefault();
                if (confirm(confirmMsg)) {
                    carryList(this,targetId);
                }
            })

            $('span.fetchBtn').on('click',function() {
                $(this).addClass('spin');
                fetchContent(this);
            })

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
            }
        }
    });
    
    
    request.fail(function (xhr){
        alert(xhr.responseJSON.message);
    });
}