!function(e){var t={};function r(o){if(t[o])return t[o].exports;var a=t[o]={i:o,l:!1,exports:{}};return e[o].call(a.exports,a,a.exports,r),a.l=!0,a.exports}r.m=e,r.c=t,r.d=function(e,t,o){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(r.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)r.d(o,a,function(t){return e[t]}.bind(null,a));return o},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="/",r(r.s=40)}({40:function(e,t,r){e.exports=r(41)},41:function(e,t){function r(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if(!(Symbol.iterator in Object(e)||"[object Arguments]"===Object.prototype.toString.call(e)))return;var r=[],o=!0,a=!1,n=void 0;try{for(var i,s=e[Symbol.iterator]();!(o=(i=s.next()).done)&&(r.push(i.value),!t||r.length!==t);o=!0);}catch(e){a=!0,n=e}finally{try{o||null==s.return||s.return()}finally{if(a)throw n}}return r}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance")}()}function o(e){if(0==$(e.currentTarget).scrollTop()&&!1===stop_pagi){pagi++;var t=window.location.href;$.ajax({method:"get",url:t,data:{pagi:pagi}}).done((function(e){"success"==e.status&&""!=e.html&&($("#talkMessages").prepend('<li id="top-msg"></li>'),$("#talkMessages").prepend(e.html),$("div.chat-history").scrollTop($("div.chat-history").scrollTop()+$("#top-msg").position().top-$("div.chat-history").height()/4+$("#top-msg").height()/4),stop_pagi=e.stop)}))}}function a(e){var t=$(e.currentTarget);if($(t).scrollTop()+$(t).innerHeight()>=$(t)[0].scrollHeight&&!1===stop_pagi_convo){pagi_convo++;var r=__baseUrl+"/ajax/message/getMore/"+pagi_convo;console.log(r),$.ajax({method:"get",url:r,data:{pagi:pagi_convo}}).done((function(e){"success"==e.status&&($(t).append(e.html),stop_pagi_convo=e.stop)}))}}$(document).ready((function(){$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}}),$("div.chat-history").scrollTop($("div.chat-history").prop("scrollHeight"));var e=new Image;e.src=__baseUrl+"/chat/loading.gif",$("div.chat-history").bind("scroll",o),$("ul.list").bind("scroll",a),$("#talkSendMessage").on("submit",(function(t){var o,a,n;(t.preventDefault(),$("#message-data").val()||$("#upload-pictures").val())?(a=$(this),o=__baseUrl+"/ajax/message/send",$(document).one("ajaxSend",(function(){a[0].reset(),$(".emojionearea-editor").empty(),$("#picture-preview").empty();var t='<li class="clearfix" id="to-be-replaced"><img src="'+e.src+'"></li>';$("#talkMessages").append(t),$("div.chat-history").scrollTop($("div.chat-history").prop("scrollHeight"))})),(n=$.ajax({method:"post",url:o,enctype:"multipart/form-data",processData:!1,contentType:!1,data:new FormData(this)})).done((function(e){if("success"==e.status){$("#to-be-replaced").replaceWith(e.html),$("div.chat-history").scrollTop($("div.chat-history").prop("scrollHeight"));$("#user-"+e.receiver_id).length&&($("#user-"+e.receiver_id).hasClass("activeUser")&&"activeUser",$("#user-"+e.receiver_id+"+hr").remove(),$("#user-"+e.receiver_id).remove()),$("#people-list .list").prepend(e.html2),active_id.includes(parseInt(e.receiver_id,10))&&$("#user-"+e.receiver_id).addClass("activeUser"),$(".talkDeleteConversation").on("submit",(function(e){confirm(deleteConvo)||e.preventDefault()})),$(".talkBlockConversation").on("submit",(function(e){confirm(blockConvo)||e.preventDefault()}))}})),n.fail((function(e){if("blocked-user"==e.responseJSON.status)alert(e.responseJSON.msg);else if(422==e.status)for(var t=0,o=Object.entries(e.responseJSON.errors);t<o.length;t++){var a=r(o[t],2),n=a[0],i=a[1];alert("Błąd ".concat(n,": ").concat(i))}$("#to-be-replaced").remove()}))):alert("Nie możesz wysłać pustej wiadomości")})),$("body").on("click",".talkDeleteMessage",(function(e){var t,r,o;if(e.preventDefault(),t=$(this),o=t.data("message-id"),r=__baseUrl+"/ajax/message/delete/"+o,!confirm(deleteMessage))return!1;$.ajax({method:"post",url:r,data:{_method:"DELETE"}}).done((function(e){"success"==e.status&&$("#message-"+o).hide(500,(function(){$(this).remove()}))}))})),$(".talkDeleteConversation").on("submit",(function(e){confirm(deleteConvo)||e.preventDefault()})),$(".talkBlockConversation").on("submit",(function(e){confirm(blockConvo)||e.preventDefault()})),$("#upload-pictures").change((function(e){var t=e.target.files;$("#picture-preview").empty();for(var r,o=0;r=t[o];o++){if(!r.type.match("image.*")){$(this).val(""),alert("Niewłaściwy Typ Pliku!"),$("#picture-preview").empty();break}var a=new FileReader;a.onload=function(e){return function(t){var r=document.createElement("span");r.innerHTML=['<img class="thumb" src="',t.target.result,'" title="',escape(e.name),'"/>'].join(""),$("#picture-preview").prepend(r,null),$(".emojionearea-editor").focus()}}(r),a.readAsDataURL(r)}})),$("#message-data").emojioneArea({filtersPosition:"bottom",events:{keypress:function(e,t){13==(t.keyCode||t.which)&&(t.preventDefault(),$("#message-data").val(this.getText()),$("#talkSendMessage").submit())}}})}))}});