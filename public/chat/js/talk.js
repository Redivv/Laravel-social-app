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
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/talk.js":
/*!******************************!*\
  !*** ./resources/js/talk.js ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance"); }

function _iterableToArrayLimit(arr, i) { if (!(Symbol.iterator in Object(arr) || Object.prototype.toString.call(arr) === "[object Arguments]")) { return; } var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

$(document).ready(function () {
  // Setup Ajax csrf for future requests
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  }); // Scroll chat history to newest message on start

  $("div.chat-history").scrollTop($('div.chat-history').prop('scrollHeight')); // Preload loading gif for immediate display

  var img = new Image();
  img.src = __baseUrl + "/chat/loading.gif"; // Bind scroll function to chat history for pagination request

  $("div.chat-history").bind('scroll', chk_scroll); // Bind scroll function to chat history for pagination request

  $("ul.list").bind('scroll', chk_scroll_down); // Enter key will send a message, Shift+enter will do normal break
  // Sending a message dynamicly

  $('#talkSendMessage').on('submit', function (e) {
    e.preventDefault();

    if ($('#message-data').val() || $('#addPost').val()) {
      var url, request, tag;
      tag = $(this);
      url = __baseUrl + '/ajax/message/send';
      $(document).one("ajaxSend", function () {
        tag[0].reset();
        $('.emojionearea-editor').empty();
        $('#picture-preview').empty();
        var html = '<li class="clearfix" id="to-be-replaced">' + '<div class="spinner-border text-dark" role="status">' + '<span class="sr-only">Loading...</span>' + '</div>' + '</li>';
        $('#talkMessages').append(html);
        $("div.chat-history").scrollTop($('div.chat-history').prop('scrollHeight'));
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
        if (response.status == 'success') {
          $('#to-be-replaced').replaceWith(response.html);
          $("div.chat-history").scrollTop($('div.chat-history').prop('scrollHeight'));
          var $thread = $('#user-' + response.receiver_id);
          var active_flag = true;

          if ($thread.length) {
            if ($('#user-' + response.receiver_id).hasClass('activeUser')) {
              active_flag = 'activeUser';
            }

            $('#user-' + response.receiver_id + '+hr').remove();
            $('#user-' + response.receiver_id).remove();
          }

          $('#people-list .list').prepend(response.html2);

          if (active_id.includes(parseInt(response.receiver_id, 10))) {
            $('#user-' + response.receiver_id).addClass('activeUser');
          }

          $('.talkDeleteConversation').on('submit', function (e) {
            if (!confirm(deleteConvo)) {
              e.preventDefault();
            }
          });
          $('.talkBlockConversation').on('submit', function (e) {
            if (!confirm(blockConvo)) {
              e.preventDefault();
            }
          });
        }
      });
      request.fail(function (xhr) {
        if (xhr.responseJSON.status == "blocked-user") {
          alert(xhr.responseJSON.msg);
        } else if (xhr.status == 422) {
          for (var _i = 0, _Object$entries = Object.entries(xhr.responseJSON.errors); _i < _Object$entries.length; _i++) {
            var _Object$entries$_i = _slicedToArray(_Object$entries[_i], 2),
                key = _Object$entries$_i[0],
                value = _Object$entries$_i[1];

            alert("B\u0142\u0105d ".concat(key, ": ").concat(value));
          }
        }

        $('#to-be-replaced').remove();
      });
    } else {
      alert('Nie możesz wysłać pustej wiadomości');
    }
  }); // Soft deleting a message dynamicly

  $('body').on('click', '.talkDeleteMessage', function (e) {
    e.preventDefault();
    var tag, url, id, request;
    tag = $(this);
    id = tag.data('message-id');
    url = __baseUrl + '/ajax/message/delete/' + id;

    if (!confirm(deleteMessage)) {
      return false;
    }

    request = $.ajax({
      method: "post",
      url: url,
      data: {
        "_method": "DELETE"
      }
    });
    request.done(function (response) {
      if (response.status == 'success') {
        $('#message-' + id).hide(500, function () {
          $(this).remove();
        });
      }
    });
  }); // Confirm blocking or deleting a Convo

  $('.talkDeleteConversation').on('submit', function (e) {
    if (!confirm(deleteConvo)) {
      e.preventDefault();
    }
  });
  $('.talkBlockConversation').on('submit', function (e) {
    if (!confirm(blockConvo)) {
      e.preventDefault();
    }
  });
  $('#upload-pictures').change(function (evt) {
    var files = evt.target.files; // FileList object
    // Empty the preview list

    $('#picture-preview').empty(); // Loop through the FileList and render image files as thumbnails.

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
          $('#picture-preview').prepend(span, null);
          $('.emojionearea-editor').focus();
        };
      }(f); // Read in the image file as a data URL.


      reader.readAsDataURL(f);
    }
  });
  $('#message-data').emojioneArea({
    filtersPosition: "bottom",
    events: {
      keypress: function keypress(editor, e) {
        if ((e.keyCode == 13 || e.which) == 13) {
          e.preventDefault();
          $('#message-data').val(this.getText());
          $('#talkSendMessage').submit();
        }
      }
    }
  });
}); // On full up scroll chat history try to load more messages

function chk_scroll(e) {
  var elem = $(e.currentTarget);

  if (elem.scrollTop() == 0 && stop_pagi === false) {
    pagi++;
    var url = window.location.href;
    var request = $.ajax({
      method: "get",
      url: url,
      data: {
        pagi: pagi
      }
    });
    request.done(function (response) {
      if (response.status == 'success') {
        if (response.html != '') {
          $('#talkMessages').prepend('<li id="top-msg"></li>');
          $('#talkMessages').prepend(response.html);
          $("div.chat-history").scrollTop($("div.chat-history").scrollTop() + $("#top-msg").position().top - $("div.chat-history").height() / 4 + $("#top-msg").height() / 4);
          stop_pagi = response.stop;
        }
      }
    });
  }
}

function chk_scroll_down(e) {
  var elem = $(e.currentTarget);

  if ($(elem).scrollTop() + $(elem).innerHeight() >= $(elem)[0].scrollHeight && stop_pagi_convo === false) {
    pagi_convo++;
    var url = __baseUrl + '/ajax/message/getMore/' + pagi_convo;
    console.log(url);
    var request = $.ajax({
      method: "get",
      url: url,
      data: {
        pagi: pagi_convo
      }
    });
    request.done(function (response) {
      if (response.status == 'success') {
        $(elem).append(response.html);
        stop_pagi_convo = response.stop;
      }
    });
  }
}

/***/ }),

/***/ 1:
/*!************************************!*\
  !*** multi ./resources/js/talk.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\Projects\Portal_Spol\resources\js\talk.js */"./resources/js/talk.js");


/***/ })

/******/ });
