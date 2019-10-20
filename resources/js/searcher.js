$(document).ready(function(){
    main();
})


function main() {

    $('div.hobbyCriteria>button').on('click',function() {
        let hobby = $('input#hobby').val();
        if (hobby.trim() != "") {
            addNewHobby(hobby);
        }
    })

    $('div.hobbyCriteria').on('keydown',function(e) {
        if (e.keyCode == 13 || e.which == 13) {
            e.preventDefault();
            let hobby = $('input#hobby').val();
            if (hobby.trim() != "") {
                addNewHobby(hobby.trim());
            }   
        }
    })

    $('.hobby').on('click',function() {
        if (confirm(deleteMsg)) {
            $(this).remove();
        }
    })

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
    let html = '<span class="hobby mr-4 clearfix"><li>'+hobby+'</li>'+
    '<input type="hidden" value="'+slug(hobby)+'" name="hobby[]"></span>';
    $('#hobbyOutput>ul').append(html);
    $('input#hobby').val('');
}

function slug(string) {
    return string
        .toLowerCase()
        .replace(/ /g,'-')
        ;
}