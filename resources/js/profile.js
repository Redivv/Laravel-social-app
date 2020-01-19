import "lightbox2";
var pagi = 0;

$(document).ready(function() {
    $('[data-tool="tooltip"]').tooltip();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    main();
});


function main() {

    $('.activityPosts:first').on('scroll',function() {
        pagiPosts(this);
    });


    $('.likeBtn').on('click',function() {
        likeUser(this);
    });
    
    $('.reportBtn').on('click',function() {
        reportUser(this);
    });

    $('.addFriend').on('click',function() {
        addFriend(this);
    });

    $('#expandInfoModal').on('show.bs.modal', function (e) {
        let button = $(e.relatedTarget);
        fetchContent(button);
      });

    $('#expandInfoModal').on('hidden.bs.modal', function (e) {
        let spinnerHtml = '<div class="spinner-border" role="status">'+
            '<span class="sr-only">Loading...</span>'+
        '</div>';
        $(this).find('.modal-body').html(spinnerHtml);
    });

    $('.likePostButton').on('click',function() {
        likePost(this);
    });

    $('.postDelete').on('click',function(){
        deletePost(this);
    });

    $('#showUserData').on('click',function() {
        if ($('.profileData:first').hasClass('show')) {
            $('.profileData').removeClass('show');
            $(this).html('<i class="fas fa-user-circle"></i>');
            setTimeout(function(){
                $('.darkOverlay').addClass('d-none');
            }, 900);
        }else{
            $('.profileData').addClass('show');
            $('.darkOverlay').removeClass('d-none');
            $(this).html('<i class="fas fa-times"></i>');

            $('.darkOverlay').one('click',function(){
                $('.profileData').removeClass('show');
                $('#showUserData').html('<i class="fas fa-arrows-alt-h"></i>');
                setTimeout(function(){
                    $('.darkOverlay').addClass('d-none');
                }, 900);
            });

        }
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

function addFriend(selected){
    
    let friendName = $(selected).data('name');
    
    let url= baseUrl+"/friends/ajax/add/"+friendName;

    let html= '<i class="active fas fa-user-check"></i>';
    $(selected).find('i').replaceWith(html);
    $(selected).removeClass('addFriend');
    
    var request = $.ajax({
        method : 'post',
        url: url,
        data: {"_method":"put",}
    });
    
    request.fail(function (xhr){
        
        alert(xhr.responseJSON.message);
    });
}

function fetchContent(button) {
    let requestedContent = button.data('content');
    let userId = button.data('id');
    
    let url = base_url + "/user/profile/ajax/fetchContent";

    var request = $.ajax({
        method : 'get',
        url: url,
        data: {userId: userId,requestedContent: requestedContent}
    });
    
    
    request.done(function(response){
        if (response.status === 'success') {
            $("#expandInfoModal").find('.modal-body').html(response.html);
        }
    });
    
    
    request.fail(function (xhr){
        alert(xhr.responseJSON.message);
    });
}

function likePost(selected) {
    let postId = $(selected).data('id');
    let url = base_url + "/user/ajax/likePost";

    let likesCount = $(selected).children('.likesCount').html().trim();
    if (likesCount == "") {
        likesCount = 0;
    }
    likesCount = parseInt(likesCount);

    if ($(selected).hasClass('active')) {
        $(selected).removeClass('active');
        if (likesCount - 1 == 0) {
            $(selected).children('.likesCount').html('');
        }else{
            $(selected).children('.likesCount').html(likesCount-1);
        }
    }else{
        $(selected).addClass('active');
        $(selected).children('.likesCount').html(likesCount+1);
    }

    var request = $.ajax({
        method : 'post',
        url: url,
        data: {'_method':'PATCH', postId:postId}
    });
}

function pagiPosts(selected) {
    if($(selected).scrollTop() + $(selected).innerHeight() >= $(selected)[0].scrollHeight - 200) {
        $(selected).off('scroll');
        pagi++;
        let url = base_url + "/user/ajax/getMorePosts";

        let sortParam = "userName";
        let userName  = $('#userName').text().trim();

        var request = $.ajax({
            method : 'get',
            url: url,
            data: {pagiTime:pagi,sortBy:sortParam, userName:userName}
        });
        
        
        request.done(function(response){
            if (response.status === 'success') {
                $('.activityPosts:first').append(response.html);
                if (response.stopPagi == false) {

                    $('.activityPosts:first').on('scroll',function() {
                        pagiPosts(this);
                    });

                }

                $('.postDelete').off('click');
                $('.postDelete').on('click',function(){
                    deletePost(this);
                });

                $('.likePostButton').off('click');
                $('.likePostButton').on('click',function() {
                    likePost(this);
                });
            
            }
        });
        
        
        request.fail(function (xhr){
            alert(xhr.responseJSON.message);
        });
    }
}

function deletePost(selected) { 
    if (confirm(deletePostMsg)) {
        let url = base_url + "/user/ajax/deletePost";
        let postId = $(selected).data('id');
        $('.spinnerOverlay').removeClass('d-none');

        var request = $.ajax({
            method : 'post',
            url: url,
            data: {'_method': 'DELETE',id:postId}
        });
        
        
        request.done(function(response){
            if (response.status === 'success') {
                $('#post'+postId).next().remove();
                $('#post'+postId).remove();
                $('.spinnerOverlay').addClass('d-none');
            }
        });
        
        
        request.fail(function (xhr){
            $('.spinnerOverlay').addClass('d-none');
            alert(xhr.responseJSON.message);
        });
    }
}

