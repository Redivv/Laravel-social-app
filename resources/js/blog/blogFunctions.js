
var attributesCount = 1;

export function sendAjaxRequestToWithFormData(url, form) {
    let formData = extractFormData(form);
    sendAjaxRequestToUrlWithData(url, formData);
}

export function getDataByAjaxFromUrlWithData(url, data){
    sendGetAjaxRequestToUrlWithData(url, data);
}

export function addNewAttrForm() {
    let html = createNewAttrInput();
    $('.newCultureAttributes').append(html);
    $('.categoryAttr:last').focus();
    $('[data-tool=tooltip]').tooltip();

    $('.categoryAttrDelete>i:last').on('click', function () {
        deleteAttrForm(this);
    });
}

function createNewAttrInput() {
    attributesCount += 1;
    let html = '<div class="attrBox additionalAttr row mt-2">' +
        '<input class="categoryAttr form-control col-md-6" name="categoryAttr[]" id="categoryAttr' + attributesCount + '">' +
        '<span class="categoryAttrDelete col">' +
        '<i class="fas fa-times" data-tool="tooltip" title="' + deleteAttrMsg + '" data-placement="bottom"></i>' +
        '</span>' +
        '</div>';

    return html;
}

export function deleteAttrForm(button) {
    $(button).parents('.attrBox').remove();
    $('.tooltip').remove();
}

function extractFormData(form) {
    return new FormData(form);
}

function sendAjaxRequestToUrlWithData(url, data) {
    let request = $.ajax({
        method: 'post',
        url: url,
        processData: false,
        contentType: false,
        data: data
    });
    receiveAjaxResponse(request);
}

function sendGetAjaxRequestToUrlWithData(url, data) {
    let request = $.ajax({
        method: 'get',
        url: url,
        data: {data : data}
    });
    receiveAjaxResponse(request);
}


function receiveAjaxResponse(request) {
    request.done(function (response) {
        switch (response.action) {
            case 'savedData':
                displaySuccessInformation();
                hideSpinnerOverlay();
                break;
            default:
                hideSpinnerOverlay();
                break;

        }
    });
    request.fail(function (xhr) {
        $.each(xhr.responseJSON.errors, function (key, value) {
            alert(value);
        });
        hideSpinnerOverlay();
    });
}

export function showSpinnerOverlay() {
    $('.spinnerOverlay:first').removeClass('d-none');
}

export function hideSpinnerOverlay() {
    $('.spinnerOverlay:first').addClass('d-none');
}

function displaySuccessInformation() {
    alert(savedChanges);
}

export function displayCategoryAttrs() {
    let html = createAtrrInputsHtml($('select#postCategory option:selected').data('attrs'));
    $('#newpostAttributes').html(html);
}

function createAtrrInputsHtml(attrs) {
    let inputs = "";
    if (attrs) {
        $.each(attrs, function (key, value) {
            inputs +=
                '<div class="attrBox">' +
                '<label class="d-block" for="postAttr' + key + '-new">' + value + '</label>' +
                '<input class="postAttr form-control col-6" name="postAttr[]" id="postAttr' + key + '-new">' +
                '</div>';
        });
    } else {
        inputs = '<span class="noCategoryInfo">' + selectCategoryMsg + '</span>'
    }
    return inputs;
}

export function addNewTagInputFromIn(input,container) {
    container = container || "#postTags-out";
    let newTagValue = $(input).val().trim();
    if (newTagValue !== "") {
        let html = createNewTagInput(newTagValue);

        $(container).append(html);

        clearInputsValue(input);

        turnOnToolipsOn(container+'>.postTag:last');

        addOnClickDeleteEventOnRemove(container+'>.postTag:last');
    }
}

function createNewTagInput(tagName) {
    let newTagHtml =
        '<div class="col postTag" data-tool="tooltip" data-placement="bottom" title="' + deleteHobby + '">' +
        '<span>' + tagName + '</span>' +
        '<input type="hidden" name="postTags[]" value="' + tagName + '">' +
        '</div>';

    return newTagHtml;
}

export function addOnClickDeleteEventOnRemove(selector,target = "") {
    if (target !== "") {
        $(selector).on('click', function() {
            deleteTargetElement(target);
        });
    }else{
        $(selector).on('click', deleteClickedElement);
    }
}

function deleteClickedElement() {
    if (confirm(confirmMsg)) {
        $(this).remove();
        $('.tooltip:first').remove();
    }
}

export function deleteTargetElement(selector) {
    if (confirm(confirmMsg)) {
        $(selector).remove();
        $('.tooltip:first').remove();
    }
}

export function turnOnToolipsOn(selector) {
    $(selector).tooltip();
}

function clearInputsValue(input) {
    $(input).val('');
}

export function displayAddedImageIn(input, evt, container) {
    var files = evt.target.files; // FileList object

    // Empty the preview list
    $(container).empty();


    let html = '<div class="resetPictureBox"><i class="resetPicture fas fa-trash-alt" data-tool="tooltip" title="' + deleteImages + '" data-placement="bottom"></i></div>';
    $(container).append(html);
    $('[data-tool="tooltip"]').tooltip();
    let tag = $(input);

    $('.resetPicture').on('click', function () {
        clearImageInputTagAndPreviewContainer(tag, container);
    });

    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {

        // Only process image files.
        if (!f.type.match('image.*')) {
            $(this).val("");
            alert(badFileType);
            $(container).empty();
            break;
        }

        var reader = new FileReader();

        // Closure to capture the file information.
        reader.onload = (function (theFile) {
            return function (e) {
                // Render thumbnail.
                var span = document.createElement('span');
                span.innerHTML = ['<a href="', e.target.result, '" data-lightbox="previewImage"><img class="thumb" src="', e.target.result,
                    '" title="', escape(theFile.name), '" alt="Picture Preview"/></a>'].join('');
                $(container).append(span, null);
            };
        })(f);

        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
    }
}
export function clearImageInputTagAndPreviewContainer(tag, container) {
    if (confirm(resetImgMsg)) {
        tag.val("");
        $(container).html('<input type="hidden" name="noImages" value="true">');
        $('.tooltip:first').remove();
    }
}

