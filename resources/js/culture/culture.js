import {
    addNewTagInputFromIn,
} from "./cultureFunctions";
$(document).ready(function(){
    main();
});


function main() {
    $('input[type=radio][name=options]').click(function(){
        $('.sortOptionBtn').removeClass('active');
        $(this).parent().addClass('active');
    });

    
    $('#searchTags').on('keydown',function(key){
        if (key.which == 13 || key.keyCode == 13) {
            key.preventDefault();
            addNewTagInputFromIn(this,'#searchTags-out');
        }
    });

    

    $("#searchTags").autocomplete({
 
        source: function(request, response) {
            $.ajax({
                url: baseUrl+"/ajax/tag/autocompleteUser",
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
        minLength: 1,
        appendTo: '#tagUsers'
    });
}