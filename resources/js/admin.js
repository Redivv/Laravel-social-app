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

    $('#infoNotDesc').emojioneArea({
        pickerPosition: "bottom",
        placeholder: "\xa0",
        autocomplete:false
    });

    $('#infoWallDesc').emojioneArea({
        pickerPosition: "bottom",
        placeholder: "\xa0",
        autocomplete:false
    });

    $('#postPicture').change(function(evt){
        var files = evt.target.files; // FileList object
        
        // Empty the preview list
        $('#adminPicture-preview').empty();

        
        let html = '<div class="resetPictureBox"><i class="resetPicture fas fa-trash-alt"></i></div>';
        $('#adminPicture-preview').append(html);
        let tag = $(this);

        $('.resetPicture').one('click',function() {
            if (confirm(resetImgMsg)) {
                tag.val("");
                $('#adminPicture-preview').empty();
            }
        })

        // Loop through the FileList and render image files as thumbnails.
        for (var i = 0, f; f = files[i]; i++) {

        // Only process image files.
        if (!f.type.match('image.*')) {
            $(this).val("");
            alert(badFileType);
            $('#adminPicture-preview').empty();
            break;
        }

        var reader = new FileReader();

        // Closure to capture the file information.
        reader.onload = (function(theFile) {
            return function(e) {
            // Render thumbnail.
            var span = document.createElement('span');
            span.innerHTML = ['<img class="thumb" src="', e.target.result,
                                '" title="', escape(theFile.name), '"/>'].join('');
            $('#adminPicture-preview').append(span, null);
            $('.emojionearea-editor').focus();
            };
        })(f);

        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
        }
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
            }
        });
        
        
        request.fail(function (xhr){
            alert(xhr.responseJSON.message);
        });
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
            }
        }
    });
    
    
    request.fail(function (xhr){
        alert(xhr.responseJSON.message);
    });
}