$(document).ready(function() {
    main();
})


function main() {
    $('a.navNotifications').on('click',function() {
        let type = $(this).data('type');
        $('.'+type+'Count').html('');
        $(this).parent().one('hidden.bs.dropdown',function() {
            $('.'+type+'>.dropdown-item').addClass('read');
        })

        if (type !== "chatNotifications") {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
    
            let url = baseUrl+'/user/readNotifications';
    
            let request = $.ajax({
                method : 'post',
                url: url,
                data: {"_method":"PATCH",type:type}
            });
            
            request.fail(function (xhr){
                alert(xhr.responseJSON.message);
            });
        }

    });

    $('a.clearAllBtn').one('click',function() {
        let type= $(this).data('type');
        let html = '<div class="text-center '+type+'">'+noNotifications+'</div>';
        $(this).parent().html(html);

        let url = baseUrl+'/user/deleteNotifications';

        let request = $.ajax({
            method : 'post',
            url: url,
            data: {"_method":"DELETE",type:type}
        });
        
        request.fail(function (xhr){
            alert(xhr.responseJSON.message);
        });
    })
}