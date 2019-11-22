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
/******/ 	return __webpack_require__(__webpack_require__.s = 8);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/activityWall.js":
/*!**************************************!*\
  !*** ./resources/js/activityWall.js ***!
  \**************************************/
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
  $('#addPost').emojioneArea({
    pickerPosition: "bottom",
    placeholder: "\xa0",
    autocomplete: false,
    buttonTitle: ""
  });
  $('.commentsDesc').emojioneArea({
    pickerPosition: "top",
    placeholder: "Napisz Komentarz",
    autocomplete: false,
    buttonTitle: "kokok",
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
  $('.commentsForm').on('submit', function (e) {
    e.preventDefault();
    var tag = $(this);
    tag[0].reset();
    tag.find('.emojionearea-editor').empty();
  });
  $('.btnComment').on('click', function () {
    var commentBox = $(this).parent().parent().next();
    commentBox.removeClass('d-none');
    commentBox.find('.emojionearea-editor').focus();
  });
  $('#postPicture').change(function (evt) {
    var files = evt.target.files; // FileList object
    // Empty the preview list

    $('#picture-preview').empty();
    var html = '<div class="resetPictureBox"><i class="resetPicture fas fa-trash-alt"></i></div>';
    $('#picture-preview').append(html);
    var tag = $(this);
    $('.resetPicture').one('click', function () {
      if (confirm(resetImgMsg)) {
        tag.val("");
        $('#picture-preview').empty();
      }
    }); // Loop through the FileList and render image files as thumbnails.

    for (var i = 0, f; f = files[i]; i++) {
      // Only process image files.
      if (!f.type.match('image.*')) {
        $(this).val("");
        alert(badFileType);
        $('#picture-preview').empty();
        break;
      }

      var reader = new FileReader(); // Closure to capture the file information.

      reader.onload = function (theFile) {
        return function (e) {
          // Render thumbnail.
          var span = document.createElement('span');
          span.innerHTML = ['<img class="thumb" src="', e.target.result, '" title="', escape(theFile.name), '"/>'].join('');
          $('#picture-preview').append(span, null);
          $('.emojionearea-editor').focus();
        };
      }(f); // Read in the image file as a data URL.


      reader.readAsDataURL(f);
    }
  });
  $('#wallPost').on('submit', function (e) {
    e.preventDefault();

    if ($('#postPicture').val() || $('#addPost').val()) {
      var url = baseUrl + "/user/ajax/newPost";
      var tag = $(this);
      $(document).one("ajaxSend", function () {
        tag[0].reset();
        $('.emojionearea-editor').empty();
        $('#picture-preview').empty();
        var html = '<div id="spinner" class="ajaxSpinner">' + '<div class="spinner-border text-dark" role="status">' + '<span class="sr-only">Loading...</span>' + '</div>' + '</div>';

        if ($('.noContent').length) {
          $('.noContent').remove();
        }

        $('#friendsWallFeed').prepend(html);
      });
      var request = $.ajax({
        method: "post",
        url: url,
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        data: new FormData(this)
      });
      request.done(function (response) {
        if (response.status === 'success') {
          $('#spinner').remove();
          $('#friendsWallFeed').prepend(response.html);
          $('.postDelete').on('click', function () {
            deletePost(this);
          });
        }
      });
      request.fail(function (xhr) {
        alert(xhr.responseJSON.message);
        $('#spinner').remove();
      });
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
                span.innerHTML = ['<img class="thumb" src="', e.target.result, '" title="', escape(theFile.name), '"/>'].join('');
                $('#modalPicture-preview').append(span, null);
                $('.emojionearea-editor').focus();
              };
            }(f); // Read in the image file as a data URL.


            reader.readAsDataURL(f);
          }

          $('input[name="noPicture"]').remove();
        });
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
                $('#post' + post).replaceWith(response.html);
                $('#post' + post).next().remove();
              }
            });
            request.fail(function (xhr) {
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
  $('.postDelete').off('click');
  $('.postDelete').on('click', function () {
    deletePost(this);
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
      alert(xhr.responseJSON.message);
    });
  }
}

/***/ }),

/***/ 8:
/*!********************************************!*\
  !*** multi ./resources/js/activityWall.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\Projects\Portal_Spol\resources\js\activityWall.js */"./resources/js/activityWall.js");


/***/ })

/******/ });