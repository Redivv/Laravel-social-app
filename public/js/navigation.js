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

/***/ "./resources/js/navigation.js":
/*!************************************!*\
  !*** ./resources/js/navigation.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  main();
});

function main() {
  $('a.navNotifications').on('click', function () {
    var type = $(this).data('type');
    $('.' + type + 'Count').html('');
    new_messages = 0;
    $(document).prop('title', title);
    $(this).parent().one('hidden.bs.dropdown', function () {
      $('.' + type).find('.dropdown-item').addClass('read');
    });

    if (type !== "chatNotifications") {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      var url = baseUrl + '/user/readNotifications';
      var request = $.ajax({
        method: 'post',
        url: url,
        data: {
          "_method": "PATCH",
          type: type
        }
      });
      request.fail(function (xhr) {
        $.each(xhr.responseJSON.errors, function (key, value) {
          alert(value);
        });
      });
    }
  });
  $('.acceptFriend').on('click', function () {
    acceptFriend(this);
  });
  $('.denyFriend').on('click', function () {
    denyFriend(this);
  });
  $('a.clearAllBtn').one('click', function () {
    var type = $(this).data('type');
    var html = '<div class="text-center ' + type + '">' + noNotifications + '</div><div class="notificationsContainer"></div>';
    $('.systemNotifications').html(html);
    var url = baseUrl + '/user/deleteNotifications';
    var request = $.ajax({
      method: 'post',
      url: url,
      data: {
        "_method": "DELETE",
        type: type
      }
    });
    request.fail(function (xhr) {
      $.each(xhr.responseJSON.errors, function (key, value) {
        alert(value);
      });
    });
  });
}

function acceptFriend(selected) {
  var friendName = $(selected).data('name');
  var confirmation = confirm(friendAcceptMsg + friendName + "?");

  if (confirmation == true) {
    //get url we want to visit with ajax
    var url = baseUrl + "/friends/ajax/accept/" + friendName; //make request in ajax:

    var request = $.ajax({
      //select method
      method: 'post',
      //select destination
      url: url,
      //select content we want to send:
      data: {
        //here, we just want to change our method to "put", since it is strictly laravelish method
        //and is unavaible in html.
        "_method": "patch" //we don't need to change anything else, because we send user name in url.

      }
    }); //if our request is succesfull, in other words, our response code is 200:

    request.done(function (response) {
      //if status made by response is 'succes':
      if (response.status === 'success') {
        //we delete object, that is not necessary from now.
        $(selected).parents('.dropdown-item').remove();
      }
    }); //if our request is unsuccesfull:

    request.fail(function (xhr) {
      //we get our response as alert.
      $.each(xhr.responseJSON.errors, function (key, value) {
        alert(value);
      });
    });
  }
}

function denyFriend(selected) {
  var friendName = $(selected).data('name');
  var confirmation = confirm(friendDenyMsg + friendName + "?");

  if (confirmation == true) {
    //get url we want to visit with ajax
    var url = baseUrl + "/friends/ajax/deny/" + friendName; //make request in ajax:

    var request = $.ajax({
      //select method
      method: 'post',
      //select destination
      url: url,
      //select content we want to send:
      data: {
        "_method": "delete"
      }
    });
    request.done(function (response) {
      //if status made by response is 'succes':
      if (response.status === 'success') {
        //we delete object, that is not necessary from now.
        $(selected).parents('.dropdown-item').remove();
      }
    }); //if our request is unsuccesfull:

    request.fail(function (xhr) {
      //we get our response as alert.
      $.each(xhr.responseJSON.errors, function (key, value) {
        alert(value);
      });
    });
  }
}

/***/ }),

/***/ 9:
/*!******************************************!*\
  !*** multi ./resources/js/navigation.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\Safo\resources\js\navigation.js */"./resources/js/navigation.js");


/***/ })

/******/ });