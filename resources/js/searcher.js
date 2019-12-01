$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    main();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
})


function main() {

    $('div.hobbyCriteria>button').on('click',function() {
        let hobby = $('input#hobby').val();
        if (hobby.trim() != "") {
            addNewHobby(hobby);
        }
    });

    $('div.hobbyCriteria').on('keydown',function(e) {
        if (e.keyCode == 13 || e.which == 13) {
            e.preventDefault();
            let hobby = $('input#hobby').val();
            if (hobby.trim() != "") {
                addNewHobby(hobby.trim());
            }   
        }
    });

    
    $('.reportBtn').on('click',function() {
        let reportReason = prompt(reportUserReason);
        if (reportReason.trim() == '') {
            alert(reportUserReason);
        }else{
            $('.spinnerOverlay').removeClass('d-none');

            let userName = $(this).data('name');
            let url = base_url+"/user/report";

            var request = $.ajax({
                method : 'post',
                url: url,
                data: {"_method": 'PUT', userName:userName, reason:reportReason.trim()}
            });
            
            
            request.done(function(response){
                if (response.status === 'success') {
                    $('.spinnerOverlay').addClass('d-none');
                    alert(reportUserSuccess);
                }
            });
            
            
            request.fail(function (xhr){
                alert(xhr.responseJSON.message);
            });
        }
    });

    $( "#hobby" ).autocomplete({
 
        source: function(request, response) {
            $.ajax({
                url: base_url+"/ajax/tag/autocompleteHobby",
                data: {
                    term : request.term
                },
                dataType: "json",
                success: function(data){
                    var resp = $.map(data,function(obj){
                    return obj.name;
                }); 
                response(resp);
                }
            });
        },
        minLength: 1
    });

    $('.addFriend').on('click',function() {
        //local var in JS == let
        //get name of friend you want to delete
        let friendName = $(this).data('name');
        //get url we want to visit with ajax
        let url= baseUrl+"/friends/ajax/add/"+friendName;
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
                "_method":"put",
                //we don't need to change anything else, because we send user name in url.
            }
        });
        //if our request is succesfull, in other words, our response code is 200:
        request.done(function(response){
            //if status made by response is 'succes':
            if (response.status === 'success') {
                //we delete object, that is not necessary from now.
                let edit = $('#'+friendName).find('i');
                let html= '<i class="fas fa-user-check"></i>';
                $(edit).replaceWith(html);
                // alert('U,magnumka///');
            }
        });
        //if our request is unsuccesfull:
        request.fail(function (xhr){
            //we get our response as alert.
            alert(xhr.responseJSON.message);
        });
    });
    
    $('.likeBtn').on('click',function() {
        let userId = $(this).data('id');
        let url = base_url+"/user/ajax/likeUser";

        let currentAmount = $(this).find('.likesAmount').html().trim();
        if ($(this).hasClass('active')) {

            $(this).removeClass('active');
            $(this).find('.likesAmount').html(parseInt(currentAmount)-1);
            if (currentAmount == 1) {
                $(this).find('.likesAmount').addClass('invisible');
            }

        }else{
            
            $(this).addClass('active');
            $(this).find('.likesAmount').html(parseInt(currentAmount)+1);
            if (currentAmount == 0) {
                $(this).find('.likesAmount').removeClass('invisible');
            }
        }

        var request = $.ajax({
            method : 'post',
            url: url,
            data: {"_method": "patch", userId:userId}
        });
        
        
        request.fail(function (xhr){
            alert(xhr.responseJSON.message);
        });
    })
}

function addNewHobby(hobby) {
    let html = '<li class="hobby mr-4 clearfix"><span>'+hobby+'</span>'+
    '<input type="hidden" value="'+slug(hobby)+'" name="hobby[]"></li>';
    $('#hobbyOutput>ul').append(html);
    $('input#hobby').val('');
    

    $('li.hobby').on('click',function() {
        if (confirm(deleteMsg)) {
            $(this).remove();
        }
    });
}

function slug(string) {
    return string
        .toLowerCase()
        .replace(/ /g,'-')
        ;
}