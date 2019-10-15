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

    $('#newTag_form').on('submit',function(e){
        e.preventDefault();
        if ($('#newTag_input').val().trim() !== '') {
            var url = base_url+'/ajax/tag/' + $('#newTag_input').val().trim();

            var request = $.ajax({
                method: "post",
                url: url,
                data: {"_method": "PUT"}
            });

            request.done(function(response){
                if (response.status === "success") {
                    alert('Dziaba dziaba dziaba');
                }
                
            });

        }else{
            alert('spierdalaj');
        }
        $(this)[0].reset();
    });

    $('i.delete').on('click',function(){
        if(confirm(delete_msg)){

            var url = base_url+'/ajax/tag/1';

            var request = $.ajax({
                method: "post",
                url: url,
                data: {"_method" : "DELETE"}
            });
            request.done(function(response){
                if (response.status === 'success') {
                    alert('brykiety');
                }
            });
        }
    })
}