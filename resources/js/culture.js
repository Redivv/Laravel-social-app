$(document).ready(function(){
    main();
});


function main() {
    $('#cultureSearch input[type=radio]').click(function(){
        $('.sortOptionBtn').removeClass('active');
        $(this).parent().addClass('active');
    });
}
