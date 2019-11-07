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
/******/ 	return __webpack_require__(__webpack_require__.s = 6);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin.js":
/*!*******************************!*\
  !*** ./resources/js/admin.js ***!
  \*******************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  main();
});

function main() {
  $('a.tab').one('click', function () {
    renderContent(this);
  });
}

function renderContent(selected) {
  var targetId = $(selected).attr('id');
  var url = __baseUrl + '/admin/ajax/tab';
  $(document).one("ajaxSend", function () {
    var html = ' <div class="spinner-border text-dark" role="status">' + '<span class="sr-only">Loading...</span>' + '</div>';
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
      } else {
        $('#' + targetId + 'Count').html(response.amount);
      }

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
      $('span.fetchBtn').on('click', function () {
        $(this).addClass('spin');
        fetchContent(this);
      });
    }
  });
  request.fail(function (xhr) {
    alert(xhr.responseJSON.message);
    $('#' + targetId + '-content').html('');
  });
}

function fetchContent(selected) {
  var targetId = $(selected).attr('id').split('-');
  targetId = targetId[0];
  var url = __baseUrl + '/admin/ajax/tab';
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
      } else {
        $('#' + targetId + 'Count').html(response.amount);
      }

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
      $('span.fetchBtn').on('click', function () {
        $(this).addClass('spin');
        fetchContent(this);
      });
    }
  });
  request.fail(function (xhr) {
    alert(xhr.responseJSON.message);
    $('#' + targetId + '-content').html('');
  });
}

function carryTicket(decided, target) {
  var decision = $(decided).attr('name');
  var ticketId = $(decided).parent().serialize();
  var request = $.ajax({
    method: 'post',
    url: __baseUrl + '/admin/ajax/ticket',
    data: {
      "_method": "PATCH",
      ticketId: ticketId,
      decision: decision
    }
  });
  request.done(function (response) {
    if (response.status === 'success') {
      $(decided).parent().parent().parent().remove();
      var currentAmount = $('#' + target + 'Count').text();
      var currentAmountNot = $('#descSys').text();

      if (currentAmount - 1 == 0) {
        $('#' + target + 'Count').html('');
      } else {
        $('#' + target + 'Count').html(parseInt(currentAmount) - 1);
      }

      if (currentAmountNot - 1 == 0) {
        $('.systemNotificationsCount').html('');
      } else {
        $('.systemNotificationsCount').html(parseInt(currentAmountNot) - 1);
      }

      $('a.' + ticketId.substring(9)).remove();
      $('.spinnerOverlay').addClass('d-none');
    }
  });
  request.fail(function (xhr) {
    alert(xhr.responseJSON.message);
  });
}

function carryList(decided, target) {
  var decision = $(decided).attr('name');

  if (decision == 'edit') {
    var editValue = prompt("Nowa Nazwa");
  } else {
    var editValue = "";
  }

  $('.spinnerOverlay').removeClass('d-none');
  var elementId = $(decided).parent().serialize();
  var request = $.ajax({
    method: 'post',
    url: __baseUrl + '/admin/ajax/list',
    data: {
      "_method": "PATCH",
      elementId: elementId,
      decision: decision,
      editValue: editValue,
      target: target
    }
  });
  request.done(function (response) {
    if (response.status === 'success') {
      switch (decision) {
        case 'delete':
          $(decided).parent().parent().parent().remove();
          $('.spinnerOverlay').addClass('d-none');
          break;

        case 'edit':
          $(decided).parent().parent().prev().prev().html(editValue);
          $('.spinnerOverlay').addClass('d-none');
      }
    }
  });
  request.fail(function (xhr) {
    alert(xhr.responseJSON.message);
  });
}

/***/ }),

/***/ 6:
/*!*************************************!*\
  !*** multi ./resources/js/admin.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\Projects\Portal_Spol\resources\js\admin.js */"./resources/js/admin.js");


/***/ })

/******/ });