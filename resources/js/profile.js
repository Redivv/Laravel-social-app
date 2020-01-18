import "lightbox2";

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
