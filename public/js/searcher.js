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
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/searcher.js":
/*!**********************************!*\
  !*** ./resources/js/searcher.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  main();
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
});

function main() {
  $('div.hobbyCriteria>button').on('click', function () {
    var hobby = $('input#hobby').val();

    if (hobby.trim() != "") {
      addNewHobby(hobby);
    }
  });
  $('div.hobbyCriteria').on('keydown', function (e) {
    if (e.keyCode == 13 || e.which == 13) {
      e.preventDefault();
      var hobby = $('input#hobby').val();

      if (hobby.trim() != "") {
        addNewHobby(hobby.trim());
      }
    }
  });
  $('.reportBtn').on('click', function () {
    var reportReason = prompt(reportUserReason);

    if (reportReason.trim() == '') {
      alert(reportUserReason);
    } else {
      $('.spinnerOverlay').removeClass('d-none');
      var userName = $(this).data('name');
      var url = base_url + "/user/report";
      var request = $.ajax({
        method: 'post',
        url: url,
        data: {
          "_method": 'PUT',
          userName: userName,
          reason: reportReason.trim()
        }
      });
      request.done(function (response) {
        if (response.status === 'success') {
          $('.spinnerOverlay').addClass('d-none');
          alert(reportUserSuccess);
        }
      });
      request.fail(function (xhr) {
        alert(xhr.responseJSON.message);
      });
    }
  });
  $("#hobby").autocomplete({
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
  $('.addFriend').on('click', function () {
    //local var in JS == let
    //get name of friend you want to delete
    var friendName = $(this).data('name'); //get url we want to visit with ajax

    var url = baseUrl + "/friends/ajax/add/" + friendName; //make request in ajax:

    var request = $.ajax({
      //select method
      method: 'post',
      //select destination
      url: url,
      //select content we want to send:
      data: {
        //here, we just want to change our method to "put", since it is strictly laravelish method
        //and is unavaible in html.
        "_method": "put" //we don't need to change anything else, because we send user name in url.

      }
    }); //if our request is succesfull, in other words, our response code is 200:

    request.done(function (response) {
      //if status made by response is 'succes':
      if (response.status === 'success') {
        //we delete object, that is not necessary from now.
        var edit = $('#' + friendName).find('i');
        var html = '<i class="fas fa-user-check"></i>';
        $(edit).replaceWith(html); // alert('U,magnumka///');
      }
    }); //if our request is unsuccesfull:

    request.fail(function (xhr) {
      //we get our response as alert.
      alert(xhr.responseJSON.message);
    });
  });
}

function addNewHobby(hobby) {
  var html = '<li class="hobby mr-4 clearfix"><span>' + hobby + '</span>' + '<input type="hidden" value="' + slug(hobby) + '" name="hobby[]"></li>';
  $('#hobbyOutput>ul').append(html);
  $('input#hobby').val('');
  $('li.hobby').on('click', function () {
    if (confirm(deleteMsg)) {
      $(this).remove();
    }
  });
}

function slug(string) {
  return string.toLowerCase().replace(/ /g, '-');
}

/***/ }),

/***/ 3:
/*!****************************************!*\
  !*** multi ./resources/js/searcher.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\Safo\resources\js\searcher.js */"./resources/js/searcher.js");


/***/ })

/******/ });