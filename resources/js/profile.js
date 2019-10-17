$(document).ready(function(){
    // Setup Ajax csrf for future requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    main();
})


function main() {

    var img = new Image();
    img.src = base_url+"/chat/loading.gif";

    $('#tagForm').on('submit',function(e){
        e.preventDefault();
        let data = $('#tagInput').val().trim();
        if (data !== '') {
            let url = base_url+'/ajax/tag/addNew';

            $(document).one("ajaxSend", function(){
                $('#tagForm')[0].reset();
                let html = ' <div id="load" class="col-md-2 ml-sm-0 ml-md-5 mt-3">'+
                        '<img src="'+img.src+'">'+
                '</div>';
                $('.tagList').append(html);
            }); 

            var request = $.ajax({
                method: "post",
                url: url,
                data: {"tag":data, "_method": "PUT"}
            });

            request.done(function(response){
                if (response.status === "success") {
                    $('#load').replaceWith(response.html);

                    $('i.delete').on('click',function(){
                        deleteTag(this);
                    });
                }
             });

            request.fail(function (xhr){
                if (xhr.responseJSON.status == "repeat") {
                    alert("To zainteresowanie zostało już dodane")
                    $('#load').remove();
                }
            });

        }else{
            alert('Nie możesz wysłać pustego formularza');
        }
    });

    $( "#tagInput" ).autocomplete({
 
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

    $( "input#city" ).autocomplete({
 
        source: function(request, response) {
            $.ajax({
                url: base_url+"/ajax/tag/autocompleteCity",
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

    $('i.delete').on('click',function(){
        deleteTag(this);
    });
}

function deleteTag(tag) {
    if(confirm(delete_msg)){

        var url = base_url+'/ajax/tag/deleteTag';

        let data = $(tag).prev().html();

        var request = $.ajax({
            method: "post",
            url: url,
            data: {"tag" : data, "_method" : "DELETE"}
        });

        request.done(function(response){
            if (response.status === 'success') {
                $(tag).parent().remove();
            }
        });

        request.fail(function (xhr){
            if (xhr.responseJSON.status == "not-found") {
                alert("Nie znaleziono podanego tagu")
            }
        });
    }
}