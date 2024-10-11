function _classCallCheck(t,i){if(!(t instanceof i))throw new TypeError("Cannot call a class as a function")}var Sticky=function(){function t(){var i=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};_classCallCheck(this,t),this.selector=i,this.elements=[],this.version="1.2.0",this.vp=this.getViewportSize(),this.body=document.querySelector("body"),this.options={wrap:e.wrap||!1,marginTop:e.marginTop||0,stickyFor:e.stickyFor||0,stickyClass:e.stickyClass||null,stickyContainer:e.stickyContainer||"body"},this.updateScrollTopPosition=this.updateScrollTopPosition.bind(this),this.updateScrollTopPosition(),window.addEventListener("load",this.updateScrollTopPosition),window.addEventListener("scroll",this.updateScrollTopPosition),this.run()}return t.prototype.run=function(){var t=this,i=setInterval((function(){if("complete"===document.readyState){clearInterval(i);var e=document.querySelectorAll(t.selector);t.forEach(e,(function(i){return t.renderElement(i)}))}}),10)},t.prototype.renderElement=function(t){var i=this;t.sticky={},t.sticky.active=!1,t.sticky.marginTop=parseInt(t.getAttribute("data-margin-top"))||this.options.marginTop,t.sticky.stickyFor=parseInt(t.getAttribute("data-sticky-for"))||this.options.stickyFor,t.sticky.stickyClass=t.getAttribute("data-sticky-class")||this.options.stickyClass,t.sticky.wrap=!!t.hasAttribute("data-sticky-wrap")||this.options.wrap,t.sticky.stickyContainer=this.options.stickyContainer,t.sticky.container=this.getStickyContainer(t),t.sticky.container.rect=this.getRectangle(t.sticky.container),t.sticky.rect=this.getRectangle(t),"img"===t.tagName.toLowerCase()&&(t.onload=function(){return t.sticky.rect=i.getRectangle(t)}),t.sticky.wrap&&this.wrapElement(t),this.activate(t)},t.prototype.wrapElement=function(t){t.insertAdjacentHTML("beforebegin","<span></span>"),t.previousSibling.appendChild(t)},t.prototype.activate=function(t){t.sticky.rect.top+t.sticky.rect.height<t.sticky.container.rect.top+t.sticky.container.rect.height&&t.sticky.stickyFor<this.vp.width&&!t.sticky.active&&(t.sticky.active=!0),this.elements.indexOf(t)<0&&this.elements.push(t),t.sticky.resizeEvent||(this.initResizeEvents(t),t.sticky.resizeEvent=!0),t.sticky.scrollEvent||(this.initScrollEvents(t),t.sticky.scrollEvent=!0),this.setPosition(t)},t.prototype.initResizeEvents=function(t){var i=this;t.sticky.resizeListener=function(){return i.onResizeEvents(t)},window.addEventListener("resize",t.sticky.resizeListener)},t.prototype.destroyResizeEvents=function(t){window.removeEventListener("resize",t.sticky.resizeListener)},t.prototype.onResizeEvents=function(t){this.vp=this.getViewportSize(),t.sticky.rect=this.getRectangle(t),t.sticky.container.rect=this.getRectangle(t.sticky.container),t.sticky.rect.top+t.sticky.rect.height<t.sticky.container.rect.top+t.sticky.container.rect.height&&t.sticky.stickyFor<this.vp.width&&!t.sticky.active?t.sticky.active=!0:(t.sticky.rect.top+t.sticky.rect.height>=t.sticky.container.rect.top+t.sticky.container.rect.height||t.sticky.stickyFor>=this.vp.width&&t.sticky.active)&&(t.sticky.active=!1),this.setPosition(t)},t.prototype.initScrollEvents=function(t){var i=this;t.sticky.scrollListener=function(){return i.onScrollEvents(t)},window.addEventListener("scroll",t.sticky.scrollListener)},t.prototype.destroyScrollEvents=function(t){window.removeEventListener("scroll",t.sticky.scrollListener)},t.prototype.onScrollEvents=function(t){t.sticky.active&&this.setPosition(t)},t.prototype.setPosition=function(t){this.css(t,{position:"",width:"",top:"",left:""}),this.vp.height<t.sticky.rect.height||!t.sticky.active||(t.sticky.rect.width||(t.sticky.rect=this.getRectangle(t)),t.sticky.wrap&&this.css(t.parentNode,{display:"block",width:t.sticky.rect.width+"px",height:t.sticky.rect.height+"px"}),0===t.sticky.rect.top&&t.sticky.container===this.body?this.css(t,{position:"fixed",top:t.sticky.rect.top+"px",left:t.sticky.rect.left+"px",width:t.sticky.rect.width+"px"}):this.scrollTop>t.sticky.rect.top-t.sticky.marginTop?(this.css(t,{position:"fixed",width:t.sticky.rect.width+"px",left:t.sticky.rect.left+"px"}),this.scrollTop+t.sticky.rect.height+t.sticky.marginTop>t.sticky.container.rect.top+t.sticky.container.offsetHeight?(t.sticky.stickyClass&&t.classList.remove(t.sticky.stickyClass),this.css(t,{top:t.sticky.container.rect.top+t.sticky.container.offsetHeight-(this.scrollTop+t.sticky.rect.height)+"px"})):(t.sticky.stickyClass&&t.classList.add(t.sticky.stickyClass),this.css(t,{top:t.sticky.marginTop+"px"}))):(t.sticky.stickyClass&&t.classList.remove(t.sticky.stickyClass),this.css(t,{position:"",width:"",top:"",left:""}),t.sticky.wrap&&this.css(t.parentNode,{display:"",width:"",height:""})))},t.prototype.update=function(){var t=this;this.forEach(this.elements,(function(i){i.sticky.rect=t.getRectangle(i),i.sticky.container.rect=t.getRectangle(i.sticky.container),t.activate(i),t.setPosition(i)}))},t.prototype.destroy=function(){var t=this;this.forEach(this.elements,(function(i){t.destroyResizeEvents(i),t.destroyScrollEvents(i),delete i.sticky}))},t.prototype.getStickyContainer=function(t){for(var i=t.parentNode;!i.hasAttribute("data-sticky-container")&&!i.parentNode.querySelector(t.sticky.stickyContainer)&&i!==this.body;)i=i.parentNode;return i},t.prototype.getRectangle=function(t){this.css(t,{position:"",width:"",top:"",left:""});var i=Math.max(t.offsetWidth,t.clientWidth,t.scrollWidth),e=Math.max(t.offsetHeight,t.clientHeight,t.scrollHeight),s=0,o=0;do{s+=t.offsetTop||0,o+=t.offsetLeft||0,t=t.offsetParent}while(t);return{top:s,left:o,width:i,height:e}},t.prototype.getViewportSize=function(){return{width:Math.max(document.documentElement.clientWidth,window.innerWidth||0),height:Math.max(document.documentElement.clientHeight,window.innerHeight||0)}},t.prototype.updateScrollTopPosition=function(){this.scrollTop=(window.pageYOffset||document.scrollTop)-(document.clientTop||0)||0},t.prototype.forEach=function(t,i){for(var e=0,s=t.length;e<s;e++)i(t[e])},t.prototype.css=function(t,i){for(var e in i)i.hasOwnProperty(e)&&(t.style[e]=i[e])},t}();!function(t,i){"undefined"!=typeof exports?module.exports=i:"function"==typeof define&&define.amd?define([],i):t.Sticky=i}(this,Sticky);
var BlockLoader=function(){return{start:function(target,opts){var el=$(target);opts=$.extend(true,{opacity:.03,overlayColor:"#000000",type:"v2",size:"lg",state:"brand",centerX:true,centerY:true,message:"Please wait...",shadow:true,width:"auto"},opts);var html='<div class="blockui"><span>'+opts.message+'</span><span><div class="k-spinner k-spinner--'+opts.version+" k-spinner--"+opts.state+" k-spinner--"+opts.size+'"></div></span></div>';var params={message:html,centerY:opts.centerY,centerX:opts.centerX,css:{top:"30%",left:"50%",border:"0",padding:"0",backgroundColor:"none",width:opts.width},overlayCSS:{backgroundColor:opts.overlayColor,opacity:opts.opacity,cursor:"wait",zIndex:"10"}};if(target=="body"){params.css.top="50%";$.blockUI(params)}else{var el=$(target);el.block(params)}},stop:function(target){if(target&&target!="body"){$(target).unblock()}else{$.unblockUI()}}}};
(function($){"use strict";$.sessionTimeout=function(options){var defaults={title:"Your Session is About to Expire!",message:"Your session is about to expire.",logoutButton:"Logout",keepAliveButton:"Stay Connected",keepAliveUrl:"/keep-alive",sessionCheckUrl:"/check-alive",ajaxType:"POST",ajaxData:"",redirUrl:"/timed-out",logoutUrl:"/log-out",warnAfter:9e5,redirAfter:12e5,keepAliveInterval:5e3,keepAlive:true,ignoreUserActivity:false,onStart:false,onWarn:false,onRedir:false,countdownMessage:false,countdownBar:false,countdownSmart:false};var opt=defaults,timer,countdown={};if(options){opt=$.extend(defaults,options)}if(opt.warnAfter>=opt.redirAfter){console.error('Bootstrap-session-timeout plugin is miss-configured. Option "redirAfter" must be equal or greater than "warnAfter".');return false}if(typeof opt.onWarn!=="function"){var countdownMessage=opt.countdownMessage?"<p>"+opt.countdownMessage.replace(/{timer}/g,'<span class="countdown-holder"></span>')+"</p>":"";var coundownBarHtml=opt.countdownBar?'<div class="progress">                   <div class="progress-bar progress-bar-striped countdown-bar active" role="progressbar" style="min-width: 15px; width: 100%;">                     <span class="countdown-holder"></span>                   </div>                 </div>':"";$("body").append('<div class="modal fade session-modal" id="session-timeout-dialog">               <div class="modal-dialog">                 <div class="modal-content">                   <div class="modal-header">                     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                     <h4 class="modal-title">'+opt.title+'</h4>                   </div>                   <div class="modal-body">                     <p>'+opt.message+"</p>                     "+countdownMessage+"                     "+coundownBarHtml+'                   </div>                   <div class="modal-footer">                     <button id="session-timeout-dialog-logout" type="button" class="btn btn-default">'+opt.logoutButton+'</button>                     <button id="session-timeout-dialog-keepalive" type="button" class="btn btn-primary" data-dismiss="modal">'+opt.keepAliveButton+"</button>                   </div>                 </div>               </div>              </div>");$("#session-timeout-dialog-logout").on("click",(function(){window.location=opt.logoutUrl}));$("#session-timeout-dialog").on("hide.bs.modal",(function(){startSessionTimer()}))}if(!opt.ignoreUserActivity){var mousePosition=[-1,-1];$(document).not(".modal-dialog").on("keyup mouseup mousemove touchend touchmove",(function(e){if(e.type==="mousemove"){if(e.clientX===mousePosition[0]&&e.clientY===mousePosition[1]){return}mousePosition[0]=e.clientX;mousePosition[1]=e.clientY;if(!$("#session-timeout-dialog").hasClass("show")){startSessionTimer()}}else{startSessionTimer()}if($("#session-timeout-dialog").length>0&&$("#session-timeout-dialog").data("bs.modal")&&$("#session-timeout-dialog").data("bs.modal").isShown){$("#session-timeout-dialog").modal("hide");$("body").removeClass("modal-open");$("div.modal-backdrop").remove()}}))}var keepAlivePinged=false,lastUsed=0;function keepAlive(){if(!keepAlivePinged){$.ajax({type:opt.ajaxType,url:opt.keepAliveUrl,data:opt.ajaxData}).done((function(result){if($.isNumeric(result)){lastUsed=result}else{window.location=opt.logoutUrl}}));keepAlivePinged=true;setTimeout((function(){keepAlivePinged=false}),opt.keepAliveInterval)}}function checkSession(){$.ajax({type:opt.ajaxType,url:urls.session_check_alive,data:opt.ajaxData}).done((function(result){if($.isNumeric(result)){clearTimeout(timer)}else{window.location=opt.logoutUrl}}))}function startSessionTimer(notKeep=false){clearTimeout(timer);if(opt.countdownMessage||opt.countdownBar){startCountdownTimer("session",true)}if(typeof opt.onStart==="function"){opt.onStart(opt)}if(opt.keepAlive&&!notKeep){keepAlive()}timer=setTimeout((function(){$.ajax({type:opt.ajaxType,url:urls.session_check_alive,data:opt.ajaxData}).done((function(result){if($.isNumeric(result)){if(lastUsed==result){$("#session-timeout-dialog").modal("show");startDialogTimer()}else{result=lastUsed;startSessionTimer(true)}}else{window.location=opt.logoutUrl}}))}),opt.warnAfter)}function startDialogTimer(){clearTimeout(timer);if(!$("#session-timeout-dialog").hasClass("in")&&(opt.countdownMessage||opt.countdownBar)){startCountdownTimer("dialog",true)}timer=setTimeout((function(){if(typeof opt.onRedir!=="function"){window.location=opt.redirUrl}else{opt.onRedir(opt)}}),opt.redirAfter-opt.warnAfter)}function startCountdownTimer(type,reset){clearTimeout(countdown.timer);if(type==="dialog"&&reset){countdown.timeLeft=Math.floor((opt.redirAfter-opt.warnAfter)/1e3)}else if(type==="session"&&reset){countdown.timeLeft=Math.floor(opt.redirAfter/1e3)}if(opt.countdownBar&&type==="dialog"){countdown.percentLeft=Math.floor(countdown.timeLeft/((opt.redirAfter-opt.warnAfter)/1e3)*100)}else if(opt.countdownBar&&type==="session"){countdown.percentLeft=Math.floor(countdown.timeLeft/(opt.redirAfter/1e3)*100)}var countdownEl=$(".countdown-holder");var secondsLeft=countdown.timeLeft>=0?countdown.timeLeft:0;if(opt.countdownSmart){var minLeft=Math.floor(secondsLeft/60);var secRemain=secondsLeft%60;var countTxt=minLeft>0?minLeft+"m":"";if(countTxt.length>0){countTxt+=" "}countTxt+=secRemain+"s";countdownEl.text(countTxt)}else{countdownEl.text(secondsLeft+"s")}if(opt.countdownBar){$(".countdown-bar").css("width",countdown.percentLeft+"%")}countdown.timeLeft=countdown.timeLeft-1;countdown.timer=setTimeout((function(){startCountdownTimer(type)}),1e3)}startSessionTimer()}})(jQuery);