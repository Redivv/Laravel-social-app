
var attributesCount = 1;

export function sendAjaxRequestToWithFormData(url,form) {
    let formData = extractFormData(form);
    sendAjaxRequestToUrlWithData(url,formData);
}

export function addNewAttrForm() {
    let html = createNewAttrInput();
    $('.newCultureAttributes').append(html);
    $('[data-tool=tooltip]').tooltip();

    $('.categoryAttrDelete>i:last').on('click',function() {
        deleteAttrForm(this);
    });
}

function createNewAttrInput() {
    attributesCount += 1;
    let html = '<div class="attrBox additionalAttr row mt-2">'+
        '<input class="categoryAttr form-control col-md-6" name="categoryAttr[]" id="categoryAttr'+attributesCount+'">'+
        '<span class="categoryAttrDelete col">'+
            '<i class="fas fa-times" data-tool="tooltip" title="'+deleteAttrMsg+'" data-placement="bottom"></i>'+
        '</span>'+
        '</div>';

    return html;
}

function deleteAttrForm(button) {
    $(button).parents('.attrBox').remove();
    $('.tooltip').remove();
}

function extractFormData(form) {
    return new FormData(form);
}

function sendAjaxRequestToUrlWithData(url, data) {
    let request = $.ajax({
        method : 'post',
        url: url,
        processData: false,
        contentType: false,
        data: data
    });
    receiveAjaxResponse(request);
}

function receiveAjaxResponse(request) {
    request.done(function (response) {
        if (response.status === 'success') {
            alert('kek');
            hideSpinnerOverlay();
        }
    });
    request.fail(function (xhr) {
        alert(xhr.responseJson.message);
        hideSpinnerOverlay();
    });
}

function hideSpinnerOverlay() {
    $('.spinnerOverlay:first').addClass('d-none');
}
