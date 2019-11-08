$(document).ready(function(){
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

    
    $('.reportBtn').on('click',function() {
        let reportReason = prompt(reportUserReason);
        if (reportReason.trim() == '') {
            alert(reportUserReason);
        }else{
            $('.spinnerOverlay').removeClass('d-none');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

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