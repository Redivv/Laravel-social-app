$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    main();
})

function main() {

    var img = new Image();
    img.src = __baseUrl+"/chat/loading.gif";

    $('a.tab').one('click',function () {
        renderContent(this,img);
    })
}

function renderContent(selected,img) {
    let targetId = $(selected).attr('id');
    let url = __baseUrl+'/admin/ajax/tab';

    $(document).one("ajaxSend", function(){
        let html = '<img src="'+img.src+'">';
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

            $('button.ticketBtn').on('click',function(e) {
                e.preventDefault();
                carryTicket(this,targetId);
            })

            $('button.listBtn').on('click',function(e) {
                e.preventDefault();
                carryList(this,targetId);
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
            if (currentAmount-1 == 0) {
                $('#'+target+'Count').html('');
            }else{
                $('#'+target+'Count').html(currentAmount-1);
            }
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
                    break;
            
                case 'edit':
                    $(decided).parent().parent().prev().prev().html(editValue);
            }
        }
    });
    
    
    request.fail(function (xhr){
        alert(xhr.responseJSON.message);
    });
}