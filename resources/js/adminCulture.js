import "lightbox2";

var pagiTarget = {
};

var pagiCount = {
}


var attributesCount = 1;

$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('[data-tool=tooltip]').tooltip();

    main();
})

function main() {

    $('a.tab').one('click', function () {
        renderContent(this);
    });

    $('#showTabsMenu').on('click', function () {
        if ($('.tabsPills').hasClass('show')) {
            $('.tabsPills').removeClass('show');
            $('.friendsList').removeClass('show');
            $(this).html('<i class="fas fa-arrow-left"></i>');
            setTimeout(function () {
                $('.darkOverlay').addClass('d-none');
            }, 900);
        } else {
            $('.tabsPills').addClass('show');
            $('.darkOverlay').removeClass('d-none');
            $(this).html('<i class="fas fa-times"></i>');

            $('.darkOverlay').one('click', function () {
                $('.tabsPills').removeClass('show');
                $('#showTabsMenu').html('<i class="fas fa-arrow-left"></i>');
                setTimeout(function () {
                    $('.darkOverlay').addClass('d-none');
                }, 900);
            });

        }
    });

    $('.categoryAttrAppend>i').on('click',addNewAttrForm);

    $('#newCategoryForm').on('submit',function (e) {
        e.preventDefault();
        
        let categoryNameIsFilled    = $('#categoryName').val().trim()   !== "";
        let firstAttributeIsFilled  = $('#categoryAttr1').val().trim()  !== "";

        if (categoryNameIsFilled && firstAttributeIsFilled) {
            extractFormData(this);
        }else{
            alert(emptyFieldsMsg);
        }
    });
}

function addNewAttrForm() {
    attributesCount += 1;
    let html = '<div class="attrBox row mt-2">'+
        '<input class="categoryAttr form-control col-md-6" name="categoryAttr[]" id="categoryAttr'+attributesCount+'">'+
        '<span class="categoryAttrDelete col">'+
            '<i class="fas fa-times" data-tool="tooltip" title="'+deleteAttrMsg+'" data-placement="bottom"></i>'+
        '</span>'+
        '</div>';
    $('.newCultureAttributes').append(html);
    $('[data-tool=tooltip]').tooltip();

    $('.categoryAttrDelete>i:last').on('click',function() {
        deleteAttrForm(this);
    });
}

function extractFormData(form) {
    let formData = new FormData(form);
    sendAjaxRequestToUrlWithDataByOptionalMethod(baseUrl+'/culture/newCategory',formData,'put');
}

function sendAjaxRequestToUrlWithDataByOptionalMethod(url, data, method) {
    method = method || "get";
    data.append('_method',method);
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
        }
    });
    request.fail(function (xhr) {
        alert(xhr.responseJson.message);
    });
}

function deleteAttrForm(button) {
    $(button).parents('.attrBox').remove();
    $('.tooltip').remove();
}

function renderContent(selected) {
    let targetId = $(selected).attr('id');
    let url = __baseUrl + '/admin/ajax/tab';

    $(document).one("ajaxSend", function () {
        let html = ' <div class="spinner-border text-dark" role="status">' +
            '<span class="sr-only">Loading...</span>' +
            '</div>';
        $('#' + targetId + '-content').html(html);
    });

    var request = $.ajax({
        method: 'get',
        url: url,
        data: {
            target: targetId
        }
    });


    request.done(function (response) {
        if (response.status === 'success') {
            $('#' + targetId + '-content').html(response.html);
            if (response.amount == 0) {
                $('#' + targetId + 'Count').html('');
            }

            $('#' + targetId + '-content').on('scroll', function () {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 200) {
                    $(this).off('scroll');
                    pagiContent(targetId);
                }
            });

            $('button.ticketBtn').on('click', function (e) {
                e.preventDefault();
                if (confirm(confirmMsg)) {
                    $('.spinnerOverlay').removeClass('d-none');
                    carryTicket(this, targetId);
                }
            });

            $('button.listBtn').on('click', function (e) {
                e.preventDefault();
                if (confirm(confirmMsg)) {
                    carryList(this, targetId);
                }
            });

            $('span.fetchBtn').tooltip();

            $('span.searchBtn').tooltip();

            $('span.fetchBtn').on('click', function () {
                $(this).addClass('spin');
                fetchContent(this);
            });

            $('.searchForm').on('submit', function (e) {
                e.preventDefault();
                search(this);
            });

        }
    });


    request.fail(function (xhr) {
        alert(xhr.responseJSON.message);
        $('#' + targetId + '-content').html('');
    });

}

function fetchContent(selected) {
    let targetId = $(selected).attr('id').split('-');
    targetId = targetId[0];
    let url = __baseUrl + '/admin/ajax/tab';

    var request = $.ajax({
        method: 'get',
        url: url,
        data: {
            target: targetId
        }
    });


    request.done(function (response) {
        if (response.status === 'success') {
            $('#' + targetId + '-content').html(response.html);
            if (response.amount == 0) {
                $('#' + targetId + 'Count').html('');
            }

            $('#' + targetId + '-content').off('scroll');
            $('#' + targetId + '-content').on('scroll', function () {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 200) {
                    $(this).off('scroll');
                    pagiContent(targetId);
                }
            });

            $('button.ticketBtn').on('click', function (e) {
                e.preventDefault();
                if (confirm(confirmMsg)) {
                    $('.spinnerOverlay').removeClass('d-none');
                    carryTicket(this, targetId);
                }
            });

            $('button.listBtn').on('click', function (e) {
                e.preventDefault();
                if (confirm(confirmMsg)) {
                    carryList(this, targetId);
                }
            });

            $('span.fetchBtn').tooltip();

            $('span.searchBtn').tooltip();

            $('span.fetchBtn').on('click', function () {
                $(this).addClass('spin');
                fetchContent(this);
            });

            $('.searchForm').on('submit', function (e) {
                e.preventDefault();
                search(this);
            });

        }
    });


    request.fail(function (xhr) {
        alert(xhr.responseJSON.message);
        $('#' + targetId + '-content').html('');
    });

}

function pagiContent(target) {
    if (pagiTarget[target]) {

        let url = __baseUrl + '/admin/ajax/pagiContent';
        pagiCount[target] = pagiCount[target] + 1;

        var request = $.ajax({
            method: 'get',
            url: url,
            data: {
                pagiTarget: target,
                pagiCount: pagiCount[target]
            }
        });


        request.done(function (response) {
            if (response.status === 'success') {
                pagiTarget[target] = response.pagiNext;
                $('#' + target + '-table').append(response.html);

                $('#' + target + '-content').on('scroll', function () {
                    if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 200) {
                        $(this).off('scroll');
                        pagiContent(target);
                    }
                });

                $('button.ticketBtn').off('click');
                $('button.ticketBtn').on('click', function (e) {
                    e.preventDefault();
                    if (confirm(confirmMsg)) {
                        $('.spinnerOverlay').removeClass('d-none');
                        carryTicket(this, target);
                    }
                });

                $('button.listBtn').off('click');
                $('button.listBtn').on('click', function (e) {
                    e.preventDefault();
                    if (confirm(confirmMsg)) {
                        carryList(this, target);
                    }
                });

                $('span.fetchBtn').off('click');
                $('span.fetchBtn').on('click', function () {
                    $(this).addClass('spin');
                    fetchContent(this);
                });
            }
        });


        request.fail(function (xhr) {
            alert(xhr.responseJSON.message);
        });
    }
}

function search(form) {
    let targetId = $(form).data('target');
    let searchCriteria = $('#' + targetId + 'Search-input').val().trim();

    if (searchCriteria != "") {

        let url = baseUrl + "/admin/ajax/searchList";

        var request = $.ajax({
            method: 'get',
            url: url,
            data: {
                target: targetId,
                criteria: searchCriteria
            }
        });


        request.done(function (response) {
            if (response.status === 'success') {
                $('#' + targetId + '-searchOut').html(response.html);
                $('#' + targetId + '-searchOut').find('button.ticketBtn').on('click', function (e) {
                    e.preventDefault();
                    if (confirm(confirmMsg)) {
                        $('.spinnerOverlay').removeClass('d-none');
                        carryTicket(this, targetId);
                    }
                });

                $('#' + targetId + '-searchOut').find('button.listBtn').on('click', function (e) {
                    e.preventDefault();
                    if (confirm(confirmMsg)) {
                        carryList(this, targetId);
                    }
                });
            }
        });


        request.fail(function (xhr) {
            alert(xhr.responseJSON.message);
        });
    }
}
