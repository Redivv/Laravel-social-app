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

/***/ "./resources/js/culture/culture.js":
/*!*****************************************!*\
  !*** ./resources/js/culture/culture.js ***!
  \*****************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _cultureFunctions__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./cultureFunctions */ "./resources/js/culture/cultureFunctions.js");

$(document).ready(function () {
  main();
});

function main() {
  $('input[type=radio][name=options]').click(function () {
    $('.sortOptionBtn').removeClass('active');
    $(this).parent().addClass('active');
  });
  $('#searchTags').on('keydown', function (key) {
    if (key.which == 13 || key.keyCode == 13) {
      key.preventDefault();
      Object(_cultureFunctions__WEBPACK_IMPORTED_MODULE_0__["addNewTagInputFromIn"])(this, '#searchTags-out');
    }
  });
  $("#searchTags").autocomplete({
    source: function source(request, response) {
      $.ajax({
        url: baseUrl + "/ajax/tag/autocompleteUser",
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
    minLength: 1,
    appendTo: '#tagUsers'
  });
}

/***/ }),

/***/ "./resources/js/culture/cultureFunctions.js":
/*!**************************************************!*\
  !*** ./resources/js/culture/cultureFunctions.js ***!
  \**************************************************/
/*! exports provided: sendAjaxRequestToWithFormData, addNewAttrForm, deleteAttrForm, showSpinnerOverlay, hideSpinnerOverlay, displayCategoryAttrs, addNewTagInputFromIn, addOnClickDeleteEventOnRemove, deleteTargetElement, turnOnToolipsOn, displayAddedImageIn, clearImageInputTagAndPreviewContainer, addNewPartnerInput */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "sendAjaxRequestToWithFormData", function() { return sendAjaxRequestToWithFormData; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "addNewAttrForm", function() { return addNewAttrForm; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "deleteAttrForm", function() { return deleteAttrForm; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "showSpinnerOverlay", function() { return showSpinnerOverlay; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "hideSpinnerOverlay", function() { return hideSpinnerOverlay; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "displayCategoryAttrs", function() { return displayCategoryAttrs; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "addNewTagInputFromIn", function() { return addNewTagInputFromIn; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "addOnClickDeleteEventOnRemove", function() { return addOnClickDeleteEventOnRemove; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "deleteTargetElement", function() { return deleteTargetElement; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "turnOnToolipsOn", function() { return turnOnToolipsOn; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "displayAddedImageIn", function() { return displayAddedImageIn; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "clearImageInputTagAndPreviewContainer", function() { return clearImageInputTagAndPreviewContainer; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "addNewPartnerInput", function() { return addNewPartnerInput; });
var attributesCount = 1;
function sendAjaxRequestToWithFormData(url, form) {
  var formData = extractFormData(form);
  sendAjaxRequestToUrlWithData(url, formData);
}
function addNewAttrForm() {
  var html = createNewAttrInput();
  $('.newCultureAttributes').append(html);
  $('.categoryAttr:last').focus();
  $('[data-tool=tooltip]').tooltip();
  $('.categoryAttrDelete>i:last').on('click', function () {
    deleteAttrForm(this);
  });
}

function createNewAttrInput() {
  attributesCount += 1;
  var html = '<div class="attrBox additionalAttr row mt-2">' + '<input class="categoryAttr form-control col-md-6" name="categoryAttr[]" id="categoryAttr' + attributesCount + '">' + '<span class="categoryAttrDelete col">' + '<i class="fas fa-times" data-tool="tooltip" title="' + deleteAttrMsg + '" data-placement="bottom"></i>' + '</span>' + '</div>';
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
  var request = $.ajax({
    method: 'post',
    url: url,
    processData: false,
    contentType: false,
    data: data
  });
  receiveAjaxResponse(request);
}

function receiveAjaxResponse(request) {
  request.done(function (response) {
    if (response.action === 'savedData') {
      displaySuccessInformation();
      hideSpinnerOverlay();
    }
  });
  request.fail(function (xhr) {
    $.each(xhr.responseJSON.errors, function (key, value) {
      alert(value);
    });
    hideSpinnerOverlay();
  });
}

function showSpinnerOverlay() {
  $('.spinnerOverlay:first').removeClass('d-none');
}
function hideSpinnerOverlay() {
  $('.spinnerOverlay:first').addClass('d-none');
}

function displaySuccessInformation() {
  alert(savedChanges);
}

function displayCategoryAttrs() {
  var html = createAtrrInputsHtml($('select#itemCategory option:selected').data('attrs'));
  $('#newItemAttributes').html(html);
}

function createAtrrInputsHtml(attrs) {
  var inputs = "";

  if (attrs) {
    $.each(attrs, function (key, value) {
      inputs += '<div class="attrBox">' + '<label class="d-block" for="itemAttr' + key + '-new">' + value + '</label>' + '<input class="itemAttr form-control col-6" name="itemAttr[]" id="itemAttr' + key + '-new">' + '</div>';
    });
  } else {
    inputs = '<span class="noCategoryInfo">' + selectCategoryMsg + '</span>';
  }

  return inputs;
}

function addNewTagInputFromIn(input, container) {
  container = container || "#itemTags-out";
  var newTagValue = $(input).val().trim();

  if (newTagValue !== "") {
    var html = createNewTagInput(newTagValue);
    $(container).append(html);
    clearInputsValue(input);
    turnOnToolipsOn(container + '>.itemTag:last');
    addOnClickDeleteEventOnRemove(container + '>.itemTag:last');
  }
}

function createNewTagInput(tagName) {
  var newTagHtml = '<div class="col itemTag" data-tool="tooltip" data-placement="bottom" title="' + deleteHobby + '">' + '<span>' + tagName + '</span>' + '<input type="hidden" name="itemTags[]" value="' + tagName + '">' + '</div>';
  return newTagHtml;
}

function addOnClickDeleteEventOnRemove(selector) {
  var target = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : "";

  if (target !== "") {
    $(selector).on('click', function () {
      deleteTargetElement(target);
    });
  } else {
    $(selector).on('click', deleteClickedElement);
  }
}

function deleteClickedElement() {
  if (confirm(confirmMsg)) {
    $(this).remove();
    $('.tooltip:first').remove();
  }
}

function deleteTargetElement(selector) {
  if (confirm(confirmMsg)) {
    $(selector).remove();
    $('.tooltip:first').remove();
  }
}
function turnOnToolipsOn(selector) {
  $(selector).tooltip();
}

function clearInputsValue(input) {
  $(input).val('');
}

function displayAddedImageIn(input, evt, container) {
  var files = evt.target.files; // FileList object
  // Empty the preview list

  $(container).empty();
  var html = '<div class="resetPictureBox"><i class="resetPicture fas fa-trash-alt" data-tool="tooltip" title="' + deleteImages + '" data-placement="bottom"></i></div>';
  $(container).append(html);
  $('[data-tool="tooltip"]').tooltip();
  var tag = $(input);
  $('.resetPicture').on('click', function () {
    clearImageInputTagAndPreviewContainer(tag, container);
  }); // Loop through the FileList and render image files as thumbnails.

  for (var i = 0, f; f = files[i]; i++) {
    // Only process image files.
    if (!f.type.match('image.*')) {
      $(this).val("");
      alert(badFileType);
      $(container).empty();
      break;
    }

    var reader = new FileReader(); // Closure to capture the file information.

    reader.onload = function (theFile) {
      return function (e) {
        // Render thumbnail.
        var span = document.createElement('span');
        span.innerHTML = ['<a href="', e.target.result, '" data-lightbox="previewImage"><img class="thumb" src="', e.target.result, '" title="', escape(theFile.name), '" alt="Picture Preview"/></a>'].join('');
        $(container).append(span, null);
      };
    }(f); // Read in the image file as a data URL.


    reader.readAsDataURL(f);
  }
}
function clearImageInputTagAndPreviewContainer(tag, container) {
  if (confirm(resetImgMsg)) {
    tag.val("");
    $(container).html('<input type="hidden" name="noImages" value="true">');
    $('.tooltip:first').remove();
  }
}
function addNewPartnerInput() {
  var html = createNewPartnerInput();
  $(html).insertBefore('#newPartnerButton');
  turnOnToolipsOn('.partnerDelete>i:last');
  addOnClickDeleteEventOnRemove('.partnerDelete:last', '.partner:last');
  $('.partnerThumb-input:last').on('change', function (evt) {
    var containerId = $(this).prev().attr('id');
    displayAddedImageIn(this, evt, '#' + containerId);
  });
}
var newPartners = 0;

function createNewPartnerInput() {
  newPartners++;
  var html = '<div class="form-group partner col">' + '<div class="partnerDelete"><i class="fas fa-times" data-tool="tooltip" title="' + deleteMsg + '"></i></div>' + '<output class="partnerThumb" id="partner' + newPartners + '-New"></output>' + '<input class="partnerThumb-input" type="file" name="partnersImages[]" required>' + '<input type="text" name="partnersNames[]" class="form-control" placeholder="Name" required>' + '<input type="text" name="partnersUrls[]" class="form-control mt-2" placeholder="Url" required>' + '</div>';
  return html;
}

/***/ }),

/***/ 8:
/*!***********************************************!*\
  !*** multi ./resources/js/culture/culture.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\Projects\Portal_Spol\resources\js\culture\culture.js */"./resources/js/culture/culture.js");


/***/ })

/******/ });