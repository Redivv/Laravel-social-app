/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 9);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/singlePost.js":
/*!************************************!*\
  !*** ./resources/js/singlePost.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip();
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  main();
});

function main() {
  $('#editPostDesc').emojioneArea({
    pickerPosition: "top",
    placeholder: "\xa0",
    autocomplete: false
  });
  $('.commentsDesc').emojioneArea({
    pickerPosition: "top",
    placeholder: "Napisz Komentarz",
    inline: false,
    events: {
      keypress: function keypress(editor, e) {
        if (e.keyCode == 13 || e.which == 13) {
          e.preventDefault();
          editor.parent().prev().val(this.getText());
          editor.parent().prev().parent().submit();
        }
      }
    }
  });
  $('#editModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var post = button.data('id');
    var modal = $(this);
    var spinnerHtml = '<div class="spinnerBox text-center mt-2">' + '<div class="spinner-border text-dark" role="status">' + '<span class="sr-only">Loading...</span>' + '</div>' + '</div>';
    modal.find('.modal-body').html(spinnerHtml);
    var url = baseUrl + '/user/ajax/getPost/' + post;
    var request = $.ajax({
      method: 'get',
      url: url
    });
    request.done(function (response) {
      if (response.status === 'success') {
        modal.find('.modal-body').html(response.html);
        $('.resetPicture').one('click', function () {
          if (confirm(resetImgMsg)) {
            $('#editPicture').val("");
            $('#modalPicture-preview').empty();
            modal.find('#editPost').prepend('<input name="noPicture" type="hidden" value="noPicture">');
          }
        });
        $('#editPostDesc').emojioneArea({
          pickerPosition: "bottom",
          placeholder: "\xa0",
          autocomplete: false
        });
        $('#editPicture').change(function (evt) {
          var files = evt.target.files; // FileList object
          // Empty the preview list

          $('#modalPicture-preview').empty();
          var html = '<div class="resetPictureBox"><i class="resetPicture fas fa-trash-alt"></i></div>';
          $('#modalPicture-preview').append(html);
          var tag = $(this);
          $('.resetPicture').one('click', function () {
            if (confirm(resetImgMsg)) {
              tag.val("");
              $('#modalPicture-preview').empty();
              modal.find('#editPost').prepend('<input name="noPicture" type="hidden" value="noPicture">');
            }
          }); // Loop through the FileList and render image files as thumbnails.

          for (var i = 0, f; f = files[i]; i++) {
            // Only process image files.
            if (!f.type.match('image.*')) {
              $(this).val("");
              alert(badFileType);
              $('#modalPicture-preview').empty();
              break;
            }

            var reader = new FileReader(); // Closure to capture the file information.

            reader.onload = function (theFile) {
              return function (e) {
                // Render thumbnail.
                var span = document.createElement('span');
                span.innerHTML = ['<a href="', e.target.result, '" data-lightbox="editPost"><img class="thumb" src="', e.target.result, '" title="', escape(theFile.name), '"/></a>'].join('');
                $('#modalPicture-preview').append(span, null);
                $('.emojionearea-editor').focus();
              };
            }(f); // Read in the image file as a data URL.


            reader.readAsDataURL(f);
          }

          $('input[name="noPicture"]').remove();
        });
        $('#editPost').off('submit');
        $('#editPost').on('submit', function (e) {
          e.preventDefault();

          if ($('#editPicture').val() || $('#editPostDesc').val()) {
            var _url = baseUrl + "/user/ajax/editPost";

            var tag = $(this);
            $(document).one("ajaxSend", function () {
              $('#editModal').modal('hide');
              $('.spinnerOverlay').removeClass('d-none');
              tag[0].reset();
              $('.emojionearea-editor').empty();
              $('#modalPicture-preview').empty();
            });
            var request = $.ajax({
              method: "post",
              url: _url,
              enctype: 'multipart/form-data',
              processData: false,
              contentType: false,
              data: new FormData(this)
            });
            request.done(function (response) {
              if (response.status === 'success') {
                $('.spinnerOverlay').addClass('d-none');
                $('#post' + post).parent().replaceWith(response.html);
                $('.postDelete').off('click');
                $('.postDelete').on('click', function () {
                  deletePost(this);
                });
                $('.likePostButton').off('click');
                $('.likePostButton').on('click', function () {
                  likePost(this);
                });
                $('.commentsForm').off('submit');
                $('.commentsForm').on('submit', function (e) {
                  addComment(e, this);
                });
                $('.btnComment').one('click', function () {
                  $('#post' + post).parent().find('.commentsDesc:first').emojioneArea({
                    pickerPosition: "top",
                    placeholder: "Napisz Komentarz",
                    inline: false,
                    events: {
                      keypress: function keypress(editor, e) {
                        if (e.keyCode == 13 || e.which == 13) {
                          e.preventDefault();
                          editor.parent().prev().val(this.getText());
                          editor.parent().prev().parent().submit();
                        }
                      }
                    }
                  });
                  getComments(this);
                });
              }
            });
            request.fail(function (xhr) {
              $('.spinnerOverlay').addClass('d-none');
              alert(xhr.responseJSON.message);
            });
          }
        });
      }
    });
    request.fail(function (xhr) {
      alert(xhr.responseJSON.message);
    });
  });
  $('#editModal').on('hide.bs.modal', function () {
    $('#editPicture').off('change');
    $(this).find('.modal-body').html('');
  });
  $('.postDelete').on('click', function () {
    deletePost(this);
  });
  $('.likePostButton').on('click', function () {
    likePost(this);
  });
  $('.commentsForm').on('submit', function (e) {
    addComment(e, this);
  });
  $('.commentDelete').on('click', function (e) {
    deleteComment(this);
  });
  $('.replyButton').on('click', function () {
    addReplyForm(this);
  });
  $('.likeCommentButton').on('click', function () {
    likeComment(this);
  });
  $('#commentEditModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var commentId = button.data('id');
    var modal = $(this);
    var comment = $('#com-' + commentId);
    var content = comment.find('.commentDesc').html().trim();
    console.log(content);
    modal.find('.emojionearea-editor').html(content);
    modal.find('#editPostDesc').val(content);
    $('#editComment').off('submit');
    $('#editComment').on('submit', function (e) {
      e.preventDefault();
      var tag = $(this);
      var newComment = $(this).serializeArray();

      if (newComment[0].value.trim() != "") {
        var url = baseUrl + "/user/ajax/editComment";
        $(document).one("ajaxSend", function () {
          $('#commentEditModal').modal('hide');
          $('.spinnerOverlay').removeClass('d-none');
          tag[0].reset();
          $('.emojionearea-editor').empty();
        });
        var request = $.ajax({
          method: 'post',
          url: url,
          data: {
            "_method": 'PATCH',
            data: newComment,
            commentId: commentId
          }
        });
        request.done(function (response) {
          if (response.status === 'success') {
            comment.replaceWith(response.html);
            $('.spinnerOverlay').addClass('d-none');
            $('.commentDelete').off('click');
            $('.commentDelete').on('click', function (e) {
              deleteComment(this);
            });
            $('.likeCommentButton').off('click');
            $('.likeCommentButton').on('click', function () {
              likeComment(this);
            });
            $('.replyButton').off('click');
            $('.replyButton').on('click', function () {
              addReplyForm(this);
            });
          }
        });
        request.fail(function (xhr) {
          alert(xhr.responseJSON.message);
          $('.spinnerOverlay').addClass('d-none');
        });
      } else {
        alert(emptyCommentMsg);
      }
    });
  });
}

function deletePost(selected) {
  if (confirm(deletePostMsg)) {
    var url = baseUrl + "/user/ajax/deletePost";
    var postId = $(selected).data('id');
    $('.spinnerOverlay').removeClass('d-none');
    var request = $.ajax({
      method: 'post',
      url: url,
      data: {
        '_method': 'DELETE',
        id: postId
      }
    });
    request.done(function (response) {
      if (response.status === 'success') {
        $('#post' + postId).next().remove();
        $('#post' + postId).remove();
        $('.spinnerOverlay').addClass('d-none');
      }
    });
    request.fail(function (xhr) {
      $('.spinnerOverlay').addClass('d-none');
      alert(xhr.responseJSON.message);
    });
  }
}

function deleteComment(selected) {
  if (confirm(deleteCommentMsg)) {
    var url = baseUrl + "/user/ajax/deleteComment";
    var commentId = $(selected).data('id');
    $('.spinnerOverlay').removeClass('d-none');
    var request = $.ajax({
      method: 'post',
      url: url,
      data: {
        '_method': 'DELETE',
        id: commentId
      }
    });
    request.done(function (response) {
      if (response.status === 'success') {
        $('#com-' + commentId).siblings('.commentRepliesBox').remove();

        if (!$(selected).hasClass('replyDelete')) {
          var commAmount = $(selected).parents('.postComments').prev().find('.postCommentsCount').html().trim();

          if (commAmount - 1 <= 0) {
            $(selected).parents('.postComments').prev().find('.postCommentsCount').html("");
          } else {
            $(selected).parents('.postComments').prev().find('.postCommentsCount').html(commAmount - 1);
          }
        }

        $('#com-' + commentId).remove();
        $('.spinnerOverlay').addClass('d-none');
      }
    });
    request.fail(function (xhr) {
      alert(xhr.responseJSON.message);
    });
  }
}

function addReplyForm(selected) {
  $('#replyForm').remove();
  var parentId = $(selected).data('id');
  var formHtml = '<div class="replyForm">' + '<form id="replyForm" method="post">' + '<div class="input-group row">' + '<input type="text" name="commentDesc" id="replyInput" class="form-control replyDesc col-11" placeholder="Napisz Komentarz" aria-label="Napisz Komentarz">' + '<div class="input-group-append col-1 commentButtons">' + '<i class="fas fa-user-tag"></i>' + '</div>' + '</div>' + '</form>' + '</div>';
  var parentComment = $('#com-' + parentId);
  $(formHtml).insertAfter('#com-' + parentId);
  $('#replyInput').emojioneArea({
    pickerPosition: "top",
    placeholder: "Napisz Komentarz",
    inline: false,
    events: {
      keypress: function keypress(editor, e) {
        if (e.keyCode == 13 || e.which == 13) {
          e.preventDefault();
          editor.parent().prev().val(this.getText());
          editor.parent().prev().parent().submit();
        }
      }
    }
  });
  $('#replyForm').find('.emojionearea-editor').focus();
  $('#replyForm').off('submit');
  $('#replyForm').on('submit', function (e) {
    e.preventDefault();
    var tag = $(this);
    $(document).one("ajaxSend", function () {
      tag[0].reset();
      tag.parent().remove();
      var html = '<div id="spinner" class="ajaxSpinner">' + '<div class="spinner-border text-dark" role="status">' + '<span class="sr-only">Loading...</span>' + '</div>' + '</div>';
      $('#com-' + parentId).next().prepend(html);
    });
    var data = tag.serializeArray();
    var url = baseUrl + "/user/ajax/newComment";

    if (data[0].value.trim() != "") {
      var request = $.ajax({
        method: 'post',
        url: url,
        data: {
          "_method": "PUT",
          data: data,
          parentId: parentId
        }
      });
      request.done(function (response) {
        if (response.status === 'success') {
          $('.ajaxSpinner').remove();
          $('#com-' + parentId).next().prepend(response.html);
          $('.commentDelete').off('click');
          $('.commentDelete').on('click', function (e) {
            deleteComment(this);
          });
        }
      });
      request.fail(function (xhr) {
        $('.ajaxSpinner').remove();
        alert(xhr.responseJSON.message);
      });
    } else {
      alert(emptyCommentMsg);
    }
  });
}

function addComment(event, selected) {
  event.preventDefault();
  var tag = $(selected);
  var postId = tag.data('id');
  $(document).one("ajaxSend", function () {
    tag[0].reset();
    tag.find('.emojionearea-editor').empty();
    var html = '<div id="spinner" class="ajaxSpinner">' + '<div class="spinner-border text-dark" role="status">' + '<span class="sr-only">Loading...</span>' + '</div>' + '</div>';
    $('#feed-' + postId).prepend(html);
  });
  var data = tag.serializeArray();
  var url = baseUrl + "/user/ajax/newComment";

  if (data[0].value.trim() != "") {
    var request = $.ajax({
      method: 'post',
      url: url,
      data: {
        "_method": "PUT",
        data: data,
        postId: postId
      }
    });
    request.done(function (response) {
      if (response.status === 'success') {
        $('.ajaxSpinner').remove();
        $('#feed-' + postId).prepend(response.html);
        $('.commentDelete').off('click');
        var commentAmount = $('#post' + postId).find('.postCommentsCount').html().trim();

        if (commentAmount == "") {
          commentAmount = 0;
        }

        $('#post' + postId).find('.postCommentsCount').html(parseInt(commentAmount) + 1);
        $('.commentDelete').off('click');
        $('.commentDelete').on('click', function (e) {
          deleteComment(this);
        });
        $('.likeCommentButton').off('click');
        $('.likeCommentButton').on('click', function () {
          likeComment(this);
        });
        $('.replyButton').off('click');
        $('.replyButton').on('click', function () {
          addReplyForm(this);
        });
      }
    });
    request.fail(function (xhr) {
      $('.ajaxSpinner').remove();
      alert(xhr.responseJSON.message);
    });
  } else {
    alert(emptyCommentMsg);
  }
}

function likeComment(selected) {
  var commentId = $(selected).data('id');
  var url = baseUrl + "/user/ajax/likeComment";
  var likesCount = $(selected).siblings('.likesCount').html().trim();

  if (likesCount == "") {
    likesCount = 0;
  }

  likesCount = parseInt(likesCount);

  if ($(selected).hasClass('active')) {
    $(selected).removeClass('active');

    if (likesCount - 1 == 0) {
      $(selected).siblings('.likesCount').html('');
    } else {
      $(selected).siblings('.likesCount').html(likesCount - 1);
    }
  } else {
    $(selected).addClass('active');
    $(selected).siblings('.likesCount').html(likesCount + 1);
  }

  var request = $.ajax({
    method: 'post',
    url: url,
    data: {
      '_method': 'PATCH',
      commentId: commentId
    }
  });
}

function likePost(selected) {
  var postId = $(selected).data('id');
  var url = baseUrl + "/user/ajax/likePost";
  var likesCount = $(selected).children('.likesCount').html().trim();

  if (likesCount == "") {
    likesCount = 0;
  }

  likesCount = parseInt(likesCount);

  if ($(selected).hasClass('active')) {
    $(selected).removeClass('active');

    if (likesCount - 1 == 0) {
      $(selected).children('.likesCount').html('');
    } else {
      $(selected).children('.likesCount').html(likesCount - 1);
    }
  } else {
    $(selected).addClass('active');
    $(selected).children('.likesCount').html(likesCount + 1);
  }

  var request = $.ajax({
    method: 'post',
    url: url,
    data: {
      '_method': 'PATCH',
      postId: postId
    }
  });
}

/***/ }),

/***/ 9:
/*!******************************************!*\
  !*** multi ./resources/js/singlePost.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\Projects\Portal_Spol\resources\js\singlePost.js */"./resources/js/singlePost.js");


/***/ })

/******/ });