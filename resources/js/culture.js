$(document).ready(function(){
    console.log('ready');
    $('[data-tool="tooltip"]').tooltip();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    main();
});


function main() {
    $('#cultureSearch input[type=radio]').click(function(){
        $('.sortOptionBtn').removeClass('active');
        $(this).parent().addClass('active');
    });

    $('.cultureLikeBtn').on('click',function() {
        console.log("hey!");
        //$(this).addClass('active');
        likeItem(this);
    });
}


function likeItem(selected) {
    let itemId = $(selected).data('id');
    let url = base_url+"/culture/ajax/likeItem";

    let currentAmount = $(selected).find('.likesCount').html();
    console.log(currentAmount);
    
    if ($(selected).hasClass('active')) {

        $(selected).removeClass('active');
        $(selected).find('.likesCount').html(parseInt(currentAmount)-1);
        if (currentAmount == 1) {
            $(selected).find('.likesCount').addClass('invisible');
        }

    }else{
        
        $(selected).addClass('active');
        $(selected).find('.likesCount').html(parseInt(currentAmount)+1);
        if (currentAmount == 0) {
            $(selected).find('.likesCount').removeClass('invisible');
        }
    }

    var request = $.ajax({
        method : 'post',
        url: url,
        data: {"_method": "patch", itemId:itemId}
    });
    
    
    request.fail(function (xhr){
        alert(xhr.responseJSON.message);
    });
}