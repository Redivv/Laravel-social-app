$(document).ready(function(){
    main();
});


function main() {
    $('input[type=radio][name=options]').click(function(){
        $('.sortOptionBtn').removeClass('active');
        $(this).parent().addClass('active');
    });
}