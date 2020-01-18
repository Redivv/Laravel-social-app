$(document).ready(function(){
    $('[data-tool="tooltip"]').tooltip();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    main();
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

    $('#advancedSearch').on('show.bs.collapse',function() {
        $('.toggleArrow').addClass('rotate');
    });

    $('#advancedSearch').on('hide.bs.collapse',function() {
        $('.toggleArrow').removeClass('rotate');
    });
    
    $('.reportBtn').on('click',function() {
        reportUser(this);
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
        addFriend(this);
    });
    
    $('.likeBtn').on('click',function() {
        likeUser(this);
    });
}

function likeUser(selected) {
    let userId = $(selected).data('id');
    let url = base_url+"/user/ajax/likeUser";

    let currentAmount = $(selected).find('.likesAmount').html().trim();
    if ($(selected).hasClass('active')) {

        $(selected).removeClass('active');
        $(selected).find('.likesAmount').html(parseInt(currentAmount)-1);
        if (currentAmount == 1) {
            $(selected).find('.likesAmount').addClass('invisible');
        }

    }else{
        
        $(selected).addClass('active');
        $(selected).find('.likesAmount').html(parseInt(currentAmount)+1);
        if (currentAmount == 0) {
            $(selected).find('.likesAmount').removeClass('invisible');
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
}

function addFriend(selected){
    //get name of friend you want to delete
    let friendName = $(selected).data('name');
    //get url we want to visit with ajax
    let url= baseUrl+"/friends/ajax/add/"+friendName;

    let html= '<i class="fas fa-user-check"></i>';
    $(selected).find('i').replaceWith(html);
    $(selected).removeClass('addFriend');
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
    //if our request is unsuccesfull:
    request.fail(function (xhr){
        //we get our response as alert.
        alert(xhr.responseJSON.message);
    });
}

function reportUser(selected) {
    let reportReason = prompt(reportUserReason);
        if (reportReason.trim() == '') {
            alert(reportUserReasonErr);
        }else{
            $('.spinnerOverlay').removeClass('d-none');

            let userName = $(selected).data('name');
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
                    $(selected).removeClass('reportBtn');
                }
            });
            
            
            request.fail(function (xhr){
                alert(xhr.responseJSON.message);
            });
        }
}

function addNewHobby(hobby) {
    let html = '<li class="hobby mr-5 clearfix" data-toggle="tooltip" data-placement="bottom" title="'+deleteHobby+'"><span>'+hobby+'</span>'+
    '<input type="hidden" value="'+slug(hobby)+'" name="hobby[]"></li>';
    $('#hobbyOutput>ul').append(html);
    $('input#hobby').val('');

    $('li.hobby').last().tooltip();

    $('li.hobby').last().on('click',function() {
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