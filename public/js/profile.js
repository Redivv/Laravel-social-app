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
/******/ 	return __webpack_require__(__webpack_require__.s = 5);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/profile.js":
/*!*********************************!*\
  !*** ./resources/js/profile.js ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  // Setup Ajax csrf for future requests
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  main();
});

function main() {
  var img = new Image();
  img.src = base_url + "/chat/loading.gif";
  $('#tagForm').on('submit', function (e) {
    e.preventDefault();
    var data = $('#tagInput').val().trim();

    if (data !== '') {
      var url = base_url + '/ajax/tag/addNew';
      $(document).one("ajaxSend", function () {
        $('#tagForm')[0].reset();
        var html = ' <div id="load" class="col-md-2 ml-sm-0 ml-md-5 mt-3">' + '<img src="' + img.src + '">' + '</div>';
        $('.tagList').append(html);
      });
      var request = $.ajax({
        method: "post",
        url: url,
        data: {
          "tag": data,
          "_method": "PUT"
        }
      });
      request.done(function (response) {
        if (response.status === "success") {
          $('#load').replaceWith(response.html);
          $('i.delete').on('click', function () {
            deleteTag(this);
          });
        }
      });
      request.fail(function (xhr) {
        if (xhr.responseJSON.status == "repeat") {
          alert("To zainteresowanie zostało już dodane");
          $('#load').remove();
        }
      });
    } else {
      alert('Nie możesz wysłać pustego formularza');
    }
  });
  $("#tagInput").autocomplete({
    source: function source(request, response) {
      $.ajax({
        url: base_url + "/ajax/tag/autocompleteHobby",
        data: {
          term: request.term
        },
        dataType: "json",
        success: function success(data) {
          var resp = $.map(data, function (obj) {
            return obj.name;
          });
          response(resp);
        }
      });
    },
    minLength: 1
  });
  $("input#city").autocomplete({
    source: function source(request, response) {
      $.ajax({
        url: base_url + "/ajax/tag/autocompleteCity",
        data: {
          term: request.term
        },
        dataType: "json",
        success: function success(data) {
          var resp = $.map(data, function (obj) {
            return obj.name;
          });
          response(resp);
        }
      });
    },
    minLength: 1
  });
  $('i.delete').on('click', function () {
    deleteTag(this);
  });
}

function deleteTag(tag) {
  if (confirm(delete_msg)) {
    var url = base_url + '/ajax/tag/deleteTag';
    var data = $(tag).prev().html();
    var request = $.ajax({
      method: "post",
      url: url,
      data: {
        "tag": data,
        "_method": "DELETE"
      }
    });
    request.done(function (response) {
      if (response.status === 'success') {
        $(tag).parent().remove();
      }
    });
    request.fail(function (xhr) {
      if (xhr.responseJSON.status == "not-found") {
        alert("Nie znaleziono podanego tagu");
      }
    });
  }
}

/***/ }),

/***/ 5:
/*!***************************************!*\
  !*** multi ./resources/js/profile.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\Safo\resources\js\profile.js */"./resources/js/profile.js");


/***/ })

/******/ });