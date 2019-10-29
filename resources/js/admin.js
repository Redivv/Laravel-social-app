$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    main();
})

function main() {

    var img = new Image();
    img.src = __baseUrl+"/chat/loading.gif";

    $('a.tab').one('click',function () {
        renderContent(this,img);
    })
}

function renderContent(selected,img) {
    let targetId = $(selected).attr('id');
    let url = __baseUrl+'/admin/ajax/tab';

    $(document).one("ajaxSend", function(){
        let html = '<img src="'+img.src+'">';
        $('#'+targetId+'-content').html(html);
    });

    var request = $.ajax({
        method : 'get',
        url: url,
        data: {target:targetId}
    });
    
    
    request.done(function(response){
        if (response.status === 'success') {
            alert('kek');
        }
    });
    
    
    request.fail(function (xhr){
        console.log(xhr);
    });

}