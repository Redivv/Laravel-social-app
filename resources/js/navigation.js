$(document).ready(function() {
    main();
})


function main() {
    $('a.navNotifications').on('click',function() {
        let type = $(this).data('type');
        $('.'+type+'Count').html('');
        $(this).parent().one('hidden.bs.dropdown',function() {
            $('.'+type).find('.dropdown-item').addClass('read');
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
    $('.chatWith').on('click',function() {
        let friendName = $(this).data('name');
        //get url we want to visit
        let url= baseUrl+"/message/"+friendName;
        console.log(url);
        window.location =url;
    });
    
    $('.acceptFriend').on('click',function() {
                
        let friendName = $(this).data('name');
        let confirmation = confirm("Na pewno chcesz zaakceptować zaproszenie "+friendName+"?");
        if(confirmation==true){
            //get url we want to visit with ajax
            let url= baseUrl+"/friends/ajax/accept/"+friendName;
            //make request in ajax:
            var request = $.ajax({
                //select method
                method : 'post',
                //select destination
                url: url,
                //select content we want to send:
                data: {
                    //here, we just want to change our method to "put", since it is strictly laravelish method
                    //and is unavaible in html.
                    "_method":"patch",
                    //we don't need to change anything else, because we send user name in url.
                }
            });
            //if our request is succesfull, in other words, our response code is 200:
            request.done(function(response){
                //if status made by response is 'succes':
                if (response.status === 'success') {
                    //we delete object, that is not necessary from now.
                    let edit = $('#'+friendName);
                    let html= '<li class="displaynan"></li>';
                    $(edit).replaceWith(html);
                }
            });
            //if our request is unsuccesfull:
            request.fail(function (xhr){
                //we get our response as alert.
                alert(xhr.responseJSON.message);
            });
        }
    });
    $('.denyFriend').on('click',function() {
        let friendName = $(this).data('name');
        let confirmation = confirm("Na pewno chcesz odrzucić zaproszenie "+friendName+"?");
        if(confirmation==true){
            //get url we want to visit with ajax
            let url= baseUrl+"/friends/ajax/deny/"+friendName;
            //make request in ajax:
            var request = $.ajax({
                //select method
                method : 'post',
                //select destination
                url: url,
                //select content we want to send:
                data: {
                    "_method":"delete",
                }
            });
            request.done(function(response){
                //if status made by response is 'succes':
                if (response.status === 'success') {
                    //we delete object, that is not necessary from now.
                    let edit = $('#'+friendName);
                    let html= '<li class="displaynan"></li>';
                    $(edit).replaceWith(html);
                }
            });
            //if our request is unsuccesfull:
            request.fail(function (xhr){
                //we get our response as alert.
                alert(xhr.responseJSON.message);
            });
        }
    });

    $('a.clearAllBtn').one('click',function() {
        let type= $(this).data('type');
        let html = '<div class="text-center '+type+'">'+noNotifications+'</div><div class="notificationsContainer"></div>';
        $('.systemNotifications').html(html);

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