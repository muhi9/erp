!function(e){e.fn.clockTimePicker=function(t,o){if("string"==typeof t&&("value"==t||"val"==t)&&!o)return e(this).val();var a,r=e.extend(!0,{afternoonHoursInOuterCircle:!1,alwaysSelectHoursFirst:!1,autosize:!1,contextmenu:!1,colors:{buttonTextColor:"#0797FF",clockFaceColor:"#EEEEEE",clockInnerCircleTextColor:"#888888",clockInnerCircleUnselectableTextColor:"#CCCCCC",clockOuterCircleTextColor:"#000000",clockOuterCircleUnselectableTextColor:"#CCCCCC",hoverCircleColor:"#DDDDDD",popupBackgroundColor:"#FFFFFF",popupHeaderBackgroundColor:"#0797FF",popupHeaderTextColor:"#FFFFFF",selectorColor:"#0797FF",selectorNumberColor:"#FFFFFF",signButtonColor:"#FFFFFF",signButtonBackgroundColor:"#0797FF"},duration:!1,durationNegative:!1,fonts:{fontFamily:"Arial",clockOuterCircleFontSize:14,clockInnerCircleFontSize:12,buttonFontSize:20},hideUnselectableNumbers:!1,i18n:{okButton:"OK",cancelButton:"Cancel"},maximum:"23:59",minimum:"-23:59",modeSwitchSpeed:500,onlyShowClockOnMobile:!1,onAdjust:function(e,t){},onChange:function(e,t){},onClose:function(){},onModeSwitch:function(){},onOpen:function(){},popupWidthOnDesktop:200,precision:1,required:!1,separator:":",useDurationPlusSign:!1,vibrate:!0},"object"==typeof t?t:{}),n=".clock-timepicker input { caret-color: white; }";if(s()&&(n+=" .clock-timepicker input::selection { background:rgba(255,255,255,0.6); } .clock-timepicker input::-moz-selection { background:rgba(255,255,255,0.6); }"),a=!1,e("head style").each((function(){if(e(this).text()==n)return a=!0,!1})),!a){var c=document.createElement("style");c.type="text/css",c.styleSheet?c.styleSheet.cssText=n:c.appendChild(document.createTextNode(n)),(document.head||document.getElementsByTagName("head")[0]).appendChild(c)}return this.each((function(){var a=e(this),n=a.data();for(var c in n)r.hasOwnProperty(c)&&(r[c]=n[c]);if(function(){r.precision=parseInt(r.precision),r.modeSwitchSpeed=parseInt(r.modeSwitchSpeed),r.popupWidthOnDesktop=parseInt(r.popupWidthOnDesktop),r.fonts.clockOuterCircleFontSize=parseInt(r.fonts.clockOuterCircleFontSize),r.fonts.clockInnerCircleFontSize=parseInt(r.fonts.clockInnerCircleFontSize),r.fonts.buttonFontSize=parseInt(r.fonts.buttonFontSize),1!=r.precision&&5!=r.precision&&10!=r.precision&&15!=r.precision&&30!=r.precision&&60!=r.precision&&(console.error("%c[jquery-clock-timepicker] Invalid precision specified: "+r.precision+"! Precision has to be 1, 5, 10, 15, 30 or 60. For now, the precision has been set back to: 1","color:orange"),r.precision=1);r.separator&&!(""+r.separator).match(/[0-9]+/)||(console.error("%c[jquery-clock-timepicker] Invalid separator specified: "+(r.separator?r.separator:"(empty)")+"! The separator cannot be empty nor can it contain any decimals. For now, the separator has been set back to a colon (:).","color:orange"),r.separator=":");r.durationNegative&&!r.duration&&console.log("%c[jquery-clock-timepicker] durationNegative is set to true, but this has no effect because duration is false!","color:orange");r.maximum&&!r.maximum.match(/^-?[0-9]+:[0-9]{2}$/)&&(console.log('%c[jquery-clock-timepicker] Invalid time format for option "maximum": '+r.maximum+"! Maximum not used...","color:orange"),r.maximum=null);r.minimum&&!r.minimum.match(/^-?[0-9]+:[0-9]{2}$/)&&(console.log('%c[jquery-clock-timepicker] Invalid time format for option "minimum": '+r.minimum+"! Minimum not used...","color:orange"),r.minimum=null);!r.minimum||!r.maximum||r.minimum!=r.maximum&&l(r.minimum,r.maximum)||(console.log('%c[jquery-clock-timepicker] Option "minimum" must be smaller than the option "maximum"!',"color:orange"),r.minimum=null)}(),"vibrate"in navigator||(r.vibrate=!1),"string"!=typeof t){e(this).parent().hasClass("clock-timepicker")&&ce(e(this)),a.val(ne(a.val())),s()&&a.prop("readonly",!0);var p=a.val(),m="",u="HOUR",d=!1,f=!1,h=s()?e(document).width()-80:r.popupWidthOnDesktop,g=h-(s()?50:20),k=parseInt(g/2),v=parseInt(g/2),x=parseInt(g/2),w=k-16,b=w-29,y=!1,C=0;a.wrap('<div class="clock-timepicker" style="display:inline-block; position:relative">');var I,M=e('<div class="clock-timepicker-autosize">');if(M.css("position","absolute").css("opacity",0).css("display","none").css("top",parseInt(a.css("margin-top"))+"px").css("left","0px").css("font-size",a.css("font-size")).css("font-family",a.css("font-family")).css("font-weight",a.css("font-weight")).css("line-height",a.css("line-height")),a.parent().append(M),a.css("min-width",a.outerWidth()),re(),s()){function P(e){e.preventDefault()}function T(e){return e.preventDefault(),e.stopImmediatePropagation(),"HOUR"==u?ie():ae(),!1}(I=e('<div class="clock-timepicker-background">')).css("zIndex",99998).css("display","none").css("position","fixed").css("top","0px").css("left","0px").css("width","100%").css("height","100%").css("backgroundColor","rgba(0,0,0,0.6)"),a.parent().append(I),I.off("touchmove",P),I.on("touchmove",P),I.off("click",T),I.on("click",T)}var R=e('<div class="clock-timepicker-popup">');if(R.css("display","none").css("zIndex",99999).css("cursor","default").css("position","fixed").css("width",h+"px").css("backgroundColor",r.colors.popupBackgroundColor).css("box-shadow","0 4px 20px 0px rgba(0, 0, 0, 0.14)").css("border-radius","5px").css("overflow","hidden").css("user-select","none"),R.on("contextmenu",(function(){return!1})),s()){function E(e){e.preventDefault()}function O(e){return e.stopImmediatePropagation(),"HOUR"==u?ie():ae(),!1}R.css("left","40px").css("top","40px"),window.addEventListener("orientationchange",(function(){setTimeout((function(){G(),K()}),500)})),R.off("touchmove",E),R.on("touchmove",E),R.off("click",O),R.on("click",O)}if(a.parent().append(R),!s()){function S(t){"none"==R.css("display")||e(t.target)[0]==F[0]||e.contains(F.parent()[0],e(t.target)[0])||ee()}e(window).off("click.clockTimePicker",S),e(window).on("click.clockTimePicker",S)}var F=a;if(s()){(F=e('<div class="clock-timepicker-mobile-time">')).css("width","100%").css("fontFamily",r.fonts.fontFamily).css("fontSize","40px").css("padding","10px 0px").css("textAlign","center").css("color",r.colors.popupHeaderTextColor).css("backgroundColor",r.colors.popupHeaderBackgroundColor);var H=e('<span class="clock-timepicker-mobile-time-hours">');F.append(H);var z=e("<span>");z.html(r.separator),F.append(z);var $=e('<span class="clock-timepicker-mobile-time-minutes">');F.append($),R.append(F)}a.attr("autocomplete")&&a.attr("data-autocomplete-orig",a.attr("autocomplete")),a.prop("autocomplete","off"),a.attr("autocorrect")&&a.attr("data-autocorrect-orig",a.attr("autocorrect")),a.prop("autocorrect","off"),a.attr("autocapitalize")&&a.attr("data-autocapitalize-orig",a.attr("autocapitalize")),a.prop("autocapitalize","off"),a.attr("spellcheck")&&a.attr("data-spellcheck-orig",a.attr("spellcheck")),a.prop("spellcheck",!1),F.on("drag.clockTimePicker dragend.clockTimePicker dragover.clockTimePicker dragenter.clockTimePicker dragstart.clockTimePicker dragleave.clockTimePicker drop.clockTimePicker selectstart.clockTimePicker contextmenu.clockTimePicker",(function(e){if(!r.contextmenu||1==e.which)return e.stopImmediatePropagation(),e.preventDefault(),!1})),F.on("mousedown.clockTimePicker",se),F.on("keyup.clockTimePicker",(function(e){if(e.shiftKey||e.ctrlKey||e.altKey||!e.key.match(/^[0-9]{1}$/))return;var t=le().replace(/.[0-9]+$/,""),o=le().replace(/^(\+|-)?[0-9]+./,""),i="-"==le()[0],n=le();m+=e.key;var c=("HOUR"==u?(i?"-":"")+(r.duration||1!=m.length?"":"0")+m:t)+r.separator+("HOUR"==u?o:(1==m.length?"0":"")+m);l(c,r.minimum)&&(c=r.minimum);l(r.maximum,c)&&(c=r.maximum);pe(c=ne(c)),y=!0;var s=("HOUR"==u?(i?"-":"")+m+"0":t)+r.separator+("HOUR"==u?"00":m+"0");if("MINUTE"==u&&(2==m.length||parseInt(m+"0")>=60)||"HOUR"==u&&!r.duration&&2==m.length||(i?!l(r.minimum,s):!l(s,r.maximum)))return m="","HOUR"==u?60==r.precision||c==r.maximum&&r.maximum.match(/00$/)||"-"==r.minimum[0]&&c==r.minimum&&r.minimum.match(/00$/)?void ee():(oe(),void ae()):void ee();"HOUR"==u?ie():ae();c!=n&&(a.attr("value",c.replace(/^\+/,"")),r.onAdjust.call(a.get(0),c.replace(/^\+/,""),n.replace(/^\+/,"")));re(),K()})),F.on("keydown.clockTimePicker",(function(e){if(9==e.keyCode)ee();else if(13==e.keyCode)ee();else if(27==e.keyCode)pe(ne(p)),ee();else if(8==e.keyCode||46==e.keyCode){if(m="",!le())return!1;var t=le();e.preventDefault(),new RegExp("^(-|\\+)?([0-9]+)(.([0-9]{1,2}))?$").test(le());var o=!(!r.duration||!r.durationNegative||"-"!=RegExp.$1),i=parseInt(RegExp.$2),n=RegExp.$4?parseInt(RegExp.$4):0;"HOUR"==u?(pe(ne(c=0==i?r.required?(r.duration?"":"0")+"0"+r.separator+"00":"":(r.duration?"":"0")+"0"+r.separator+(n<10?"0":"")+n)),c?ie():ee(),t!=c&&(a.attr("value",c.replace(/^\+/,"")),r.onAdjust.call(a.get(0),c.replace(/^\+/,""),t.replace(/^\+/,"")))):0==n?0!=i||r.required?(te(),ie()):(pe(""),""!=t&&(a.attr("value",""),r.onAdjust.call(a.get(0),"",t.replace(/^\+/,""))),ee()):(pe(ne(c=(o?"-":"")+(i<10&&!r.duration?"0":"")+i+r.separator+"00")),ae(),t!=c&&(a.attr("value",c.replace(/^\+/,"")),r.onAdjust.call(a.get(0),c.replace(/^\+/,""),t.replace(/^\+/,"")))),re()}else if(36!=e.keyCode&&37!=e.keyCode||""==le())if(35!=e.keyCode&&39!=e.keyCode||""==le())if(190==e.keyCode||e.key==r.separator)e.preventDefault(),0==le().length&&pe("0"),pe(ne(le())),60!=r.precision?(ae(),"MINUTE"!=u&&oe()):ie();else if("+"==e.key&&r.duration&&r.durationNegative){e.preventDefault();var t=le();if("-"==t[0]){var c=t.substring(1);pe(ne(c)),a.attr("value",c),r.onAdjust.call(a.get(0),c,t),re(),K(),"HOUR"==u?ie():ae()}}else if("-"==e.key&&r.duration&&r.durationNegative){e.preventDefault();var t=le().replace(/^\+/,"");if("-"!=t[0]){var c="-"+t;pe(ne(c)),a.attr("value",c),r.onAdjust.call(a.get(0),c,t),re(),K(),"HOUR"==u?ie():ae()}}else{if(38!=e.keyCode&&"+"!=e.key&&40!=e.keyCode&&"-"!=e.key)return e.preventDefault(),e.stopPropagation(),e.stopImmediatePropagation(),!1;e.preventDefault();var t=le();new RegExp("^(-|\\+)?([0-9]+)(.([0-9]{1,2}))?$").test(t);var i=parseInt(RegExp.$2);r.duration&&r.durationNegative&&"-"==RegExp.$1&&(i=-i);var n=RegExp.$4?parseInt(RegExp.$4):0;"HOUR"==u?i+=38==e.keyCode||"+"==e.key?1:-1:(n+=(38==e.keyCode||"+"==e.key?1:-1)*r.precision)<0?n=0:n>59&&(n=60-r.precision);var s=r.minimum;r.duration&&r.durationNegative||"-"!=s[0]||(s="0:00");var d=r.maximum;if(1!=r.precision){var f=parseInt(d.replace(/^(\+|-)?[0-9]+./,""));d=d.replace(/.[0-9]+$/,"")+r.separator+(f-f%r.precision)}var c=(i<0?"-":"")+(i<10&&!r.duration?"0":"")+Math.abs(i)+r.separator+(n<10?"0":"")+n;"HOUR"==u&&(l(c,d)?l(s,c)||(c=s):c=d),t!=c&&(pe(ne(c)),a.attr("value",c.replace(/^\+/,"")),r.onAdjust.call(a.get(0),c.replace(/^\+/,""),t.replace(/^\+/,"")),re(),K(),"HOUR"==u?ie():ae())}else pe(ne(le())),60!=r.precision&&"MINUTE"!=u?(ae(),oe()):(e.preventDefault(),e.stopPropagation());else pe(ne(le())),"HOUR"!=u?(ie(),te()):(e.preventDefault(),e.stopPropagation())})),a.on("mousewheel.clockTimePicker",(function(e){a.is(":focus")&&X(e)})),a.on("focus.clockTimePicker",(function(e){s()?(J(),te(!0),ie()):setTimeout((function(){"none"==R.css("display")&&se(e)}),50)}));var U=e("<div>");U.css("position","relative").css("width",g+"px").css("height",g+"px").css("margin","10px "+(s()?25:10)+"px"),R.append(U);var N=e('<canvas class="clock-timepicker-hour-canvas">');N.css("cursor","default").css("position","absolute").css("top","0px").css("left","0px"),N.attr("width",g),N.attr("height",g),V(N),U.append(N);var D=e('<canvas class="clock-timepicker-minute-canvas">');if(D.css("cursor","default").css("position","absolute").css("top","0px").css("left","0px").css("display","none"),D.attr("width",g),D.attr("height",g),V(D),U.append(D),s()){var j=e("<div>");j.css("text-align","right").css("padding","15px 30px"),r.fonts.fontFamily=r.fonts.fontFamily.replace(/\"/g,"").replace(/\'/g,"");var A='<a style="text-decoration:none; color:'+r.colors.buttonTextColor+"; font-family:"+r.fonts.fontFamily+"; font-size:"+r.fonts.buttonFontSize+'px; padding-left:30px">',q=e(A);q.html(r.i18n.cancelButton),q.on("click",(function(){ee()})),j.append(q);var B=e(A);B.html(r.i18n.okButton),B.on("click",(function(){s()&&a.val(le()),r.vibrate&&navigator.vibrate(10),ee()})),j.append(B),R.append(j)}}else if(e(this).parent().hasClass("clock-timepicker"))if("dispose"==(t=t.toLowerCase()))ce(e(this));else if("value"==t||"val"==t){e(this).val(ne(o));var W=e(this).parent().find(".clock-timepicker-mobile-input");W.length>0&&W.val(ne(o))}else"show"==t?e(this).parent().find("canvas:first").trigger("keydown"):"hide"==t?(e(this).parent().find(".clock-timepicker-popup").css("display","none"),e(this).blur()):console.log("%c[jquery-clock-timepicker] Invalid option passed to clockTimePicker: "+t,"color:red");else console.log("%c[jquery-clock-timepicker] Before calling a function, please initialize the ClockTimePicker!","color:red");function V(t){s()?(t.on("touchstart",(function(t){t.preventDefault();var o=t.originalEvent.touches[0].pageX-e(this).offset().left,i=t.originalEvent.touches[0].pageY-e(this).offset().top,n=Math.sqrt(Math.pow(Math.abs(o-v),2)+Math.pow(Math.abs(i-x),2));if(r.duration&&r.durationNegative&&n<=20){f=!0;var c=le();return c.match(/^-/)?newVal=c.substring(1):newVal="-"+c.replace(/^(-|\+)/,""),r.minimum&&!l(r.minimum,newVal)&&(newVal=ne(r.minimum)),r.maximum&&!l(newVal,r.maximum)&&(newVal=ne(r.maximum)),pe(ne(newVal)),K(),a.attr("value",newVal.replace(/^\+/,"")),r.onAdjust.call(a.get(0),newVal.replace(/^\+/,""),c.replace(/^\+/,"")),void("HOUR"==u?ie():ae())}d=!0,Y(o,i)})),t.on("touchend",(function(e){e.preventDefault(),d=!1,f||60==r.precision||(oe(),ae()),f=!1})),t.on("touchmove",(function(t){(t.preventDefault(),d)&&Y(t.originalEvent.touches[0].pageX-e(this).offset().left,t.originalEvent.touches[0].pageY-e(this).offset().top)}))):(t.on("mousedown",(function(t){Y(t.pageX-e(this).offset().left,t.pageY-e(this).offset().top),d=!0})),t.on("mouseup",(function(t){d=!1;var o=t.pageX-e(this).offset().left,i=t.pageY-e(this).offset().top,n=Math.sqrt(Math.pow(Math.abs(o-v),2)+Math.pow(Math.abs(i-x),2));if(r.duration&&r.durationNegative&&n<=20){var c=le();return c.match(/^-/)?newVal=c.substring(1):newVal="-"+c.replace(/^(-|\+)/,""),r.minimum&&!l(r.minimum,newVal)&&(newVal=ne(r.minimum)),r.maximum&&!l(newVal,r.maximum)&&(newVal=ne(r.maximum)),pe(ne(newVal)),K(),a.attr("value",newVal.replace(/^\+/,"")),r.onAdjust.call(a.get(0),newVal.replace(/^\+/,""),c.replace(/^\+/,"")),void("HOUR"==u?ie():ae())}if(!Y(o,i,!0))return 60==r.precision?ee():"HOUR"==u?(oe(),ae()):ee(),!1;"MINUTE"==u||60==r.precision?ee():(oe(),ae())})),t.on("mousemove",(function(t){Y(t.pageX-e(this).offset().left,t.pageY-e(this).offset().top)})),t.on("mouseleave",(function(e){"HOUR"==u?_():Q()})),t.on("mousewheel",(function(e){X(e)}))),t.on("keydown",(function(e){e.preventDefault(),Y(),te(),ie(),p=le()}))}function X(e){var t=window.event||e;if(e.preventDefault(),!((new Date).getTime()-C<100)){C=(new Date).getTime();var o=Math.max(-1,Math.min(1,t.wheelDelta||-t.detail));new RegExp("^(-|\\+)?([0-9]+)(.([0-9]{1,2}))?$").test(le());var i=!(!r.duration||!r.durationNegative||"-"!=RegExp.$1),n=parseInt(RegExp.$2);i&&(n=-n);var c=RegExp.$4?parseInt(RegExp.$4):0;"HOUR"==u?(r.duration&&r.durationNegative&&0==n&&!i&&-1==o?i=!0:r.duration&&r.durationNegative&&0==n&&i&&1==o?i=!1:n+=o,-1==n&&(r.duration?r.durationNegative||(n=0):n=23),24!=n||r.duration||(n=0)):((c+=o*r.precision)<0&&(c=60+c),c>=60&&(c-=60));var s=le(),p=(n<10&&!r.duration?"0":"")+(i&&0==n?"-"+n:n)+r.separator+(c<10?"0":"")+c,m=!0;r.maximum&&!l(p,r.maximum)&&(m=!1),r.minimum&&!l(r.minimum,p)&&(m=!1),m||"HOUR"!=u||(p=ne(o>0?r.maximum:r.minimum),m=!0),m&&(pe(ne(p)),re(),K(),"HOUR"==u?ie():ae(),p!=s&&(a.attr("value",p.replace(/^\+/,"")),r.onAdjust.call(a.get(0),p.replace(/^\+/,""),s.replace(/^\+/,""))))}}function Y(e,t,o){var i=360*Math.atan((t-x)/(e-v))/(2*Math.PI)+90,n=Math.sqrt(Math.pow(Math.abs(e-v),2)+Math.pow(Math.abs(t-x),2)),c=0,s=0,p=!1;if(new RegExp("^(-|\\+)?([0-9]+).([0-9]{2})$").test(le())&&(p=!(!r.duration||!r.durationNegative||"-"!=RegExp.$1),c=parseInt(RegExp.$2),s=parseInt(RegExp.$3)),"HOUR"==u){i=Math.round(i/30);var m=-1;if(n<k+10&&n>k-28?e-v>=0?m=0==i?12:i:e-v<0&&(m=i+6):n<k-28&&n>k-65&&(e-v>=0?m=0!=i?i+12:0:e-v<0&&24==(m=i+18)&&(m=0)),r.afternoonHoursInOuterCircle&&(m+=m>=12?-12:12),m>-1){var f=(p?"-":"")+(m<10&&!r.duration?"0":"")+m+r.separator+(s<10?"0":"")+s;if(d||o){var h=!0;if(r.maximum&&!l(f,r.maximum)&&(h=!1),r.minimum&&!l(r.minimum,f)&&(h=!1),h||(r.maximum&&l((p?"-":"")+(m<10&&!r.duration?"0":"")+m+r.separator+"00",r.maximum)&&(f=ne(r.maximum),h=!0),r.minimum&&!l(r.minimum,(p?"-":"")+(m<10&&!r.duration?"0":"")+m+r.separator+"00")&&(f=ne(r.minimum),h=!0)),h)f!=(b=le())&&(r.vibrate&&navigator.vibrate(10),a.attr("value",f.replace(/^\+/,"")),r.onAdjust.call(a.get(0),f.replace(/^\+/,""),b.replace(/^\+/,""))),pe(ne(f)),re()}return y=!0,_(0==m?24:m,r.duration&&r.durationNegative&&n<=12),!0}return _(null,r.duration&&r.durationNegative&&n<=12),!1}if("MINUTE"==u){i=Math.round(i/6);var g=-1;if(n<k+10&&n>k-40&&(e-v>=0?g=i:e-v<0&&60==(g=i+30)&&(g=0)),g>-1){if(1!=r.precision){var w=Math.floor(g/r.precision);(g=w*r.precision+(1==Math.round((g-w*r.precision)/r.precision)?r.precision:0))>=60&&(g=0)}var b;f=(p?"-":"")+(c<10&&!r.duration?"0":"")+c+r.separator+(g<10?"0":"")+g,h=!0;if(r.maximum&&!l(f,r.maximum)&&(h=!1),r.minimum&&!l(r.minimum,f)&&(h=!1),(d||o)&&h)f!=(b=le())&&(r.vibrate&&navigator.vibrate(10),a.attr("value",f.replace(/^\+/,"")),r.onAdjust.call(a.get(0),f.replace(/^\+/,""),b.replace(/^\+/,""))),pe(ne(f));return y=!0,Q(0==g?60:g,r.duration&&r.durationNegative&&n<=12),!0}return Q(null,r.duration&&r.durationNegative&&n<=12),!1}}function K(){"HOUR"==u?_():Q()}function L(e,t){e.beginPath(),e.arc(v,x,12,0,2*Math.PI,!1),e.fillStyle=r.colors.signButtonBackgroundColor,e.fill(),t&&(e.beginPath(),e.arc(v,x,14,0,2*Math.PI,!1),e.strokeStyle=r.colors.signButtonBackgroundColor,e.stroke()),e.beginPath(),e.moveTo(v-6,x),e.lineTo(v+6,x),e.lineWidth=2,e.strokeStyle=r.colors.signButtonColor,e.stroke(),le().match(/^-/)||(e.beginPath(),e.moveTo(v,x-6),e.lineTo(v,x+6),e.lineWidth=2,e.strokeStyle=r.colors.signButtonColor,e.stroke())}function _(e,t){var o=N.get(0).getContext("2d");new RegExp("^(-|\\+)?([0-9]+).([0-9]{1,2})$").test(le());var a="-"==RegExp.$1,n=parseInt(RegExp.$2);if(o.clearRect(0,0,g,g),n>=24)R.css("visibility","hidden");else{if(r.onlyShowClockOnMobile||R.css("visibility","visible"),0==n&&(n=24),le()||(n=-1),o.beginPath(),o.arc(v,x,k,0,2*Math.PI,!1),o.fillStyle=r.colors.clockFaceColor,o.fill(),!s()&&e){var c=!0;r.maximum&&!l((a?"-":"")+(24==e?"00":e)+":00",r.maximum)&&(c=!1),r.minimum&&!l(r.minimum,(a?"-":"")+(24==e?"00":e)+":00",!0)&&(c=!1),c&&(o.beginPath(),o.arc(v+Math.cos(Math.PI/6*(e%12-3))*(e>12?r.afternoonHoursInOuterCircle?w:b:r.afternoonHoursInOuterCircle?b:w),x+Math.sin(Math.PI/6*(e%12-3))*(e>12?r.afternoonHoursInOuterCircle?w:b:r.afternoonHoursInOuterCircle?b:w),15,0,2*Math.PI,!1),o.fillStyle=r.colors.hoverCircleColor,o.fill())}for(o.beginPath(),o.arc(v,x,3,0,2*Math.PI,!1),o.fillStyle=r.colors.selectorColor,o.fill(),n>-1&&(!r.maximum||24==n||l(n,r.maximum))&&(o.beginPath(),o.moveTo(v,x),o.lineTo(v+Math.cos(Math.PI/6*(n%12-3))*(n>12?r.afternoonHoursInOuterCircle?w:b:r.afternoonHoursInOuterCircle?b:w),x+Math.sin(Math.PI/6*(n%12-3))*(n>12?r.afternoonHoursInOuterCircle?w:b:r.afternoonHoursInOuterCircle?b:w)),o.lineWidth=1,o.strokeStyle=r.colors.selectorColor,o.stroke(),o.beginPath(),o.arc(v+Math.cos(Math.PI/6*(n%12-3))*(n>12?r.afternoonHoursInOuterCircle?w:b:r.afternoonHoursInOuterCircle?b:w),x+Math.sin(Math.PI/6*(n%12-3))*(n>12?r.afternoonHoursInOuterCircle?w:b:r.afternoonHoursInOuterCircle?b:w),15,0,2*Math.PI,!1),o.fillStyle=r.colors.selectorColor,o.fill()),o.font=r.fonts.clockOuterCircleFontSize+"px "+r.fonts.fontFamily,i=1;i<=12;i++){var p=Math.PI/6*(i-3),m=i;r.afternoonHoursInOuterCircle?(m=i+12,n==i+12?o.fillStyle=r.colors.selectorNumberColor:o.fillStyle=r.colors.clockInnerCircleTextColor,24==m&&(m="00")):n==i?o.fillStyle=r.colors.selectorNumberColor:o.fillStyle=r.colors.clockOuterCircleTextColor,r.maximum&&!l((a?"-":"")+m+":00",r.maximum)||r.minimum&&!l(r.minimum,(a?"-":"")+m+":00",!0)?r.hideUnselectableNumbers||(o.fillStyle=r.colors.clockOuterCircleUnselectableTextColor,o.fillText(m,v+Math.cos(p)*w-o.measureText(m).width/2,x+Math.sin(p)*w+r.fonts.clockOuterCircleFontSize/3)):o.fillText(m,v+Math.cos(p)*w-o.measureText(m).width/2,x+Math.sin(p)*w+r.fonts.clockOuterCircleFontSize/3)}for(o.font=r.fonts.clockInnerCircleFontSize+"px "+r.fonts.fontFamily,i=1;i<=12;i++){p=Math.PI/6*(i-3),m=i;r.afternoonHoursInOuterCircle?n==i?o.fillStyle=r.colors.selectorNumberColor:o.fillStyle=r.colors.clockOuterCircleTextColor:(m=i+12,n==i+12?o.fillStyle=r.colors.selectorNumberColor:o.fillStyle=r.colors.clockInnerCircleTextColor,24==m&&(m="00")),r.maximum&&!l((a?"-":"")+m+":00",r.maximum)||r.minimum&&!l(r.minimum,(a?"-":"")+m+":00",!0)?r.hideUnselectableNumbers||(o.fillStyle=r.colors.clockInnerCircleUnselectableTextColor,o.fillText(m,v+Math.cos(p)*b-o.measureText(m).width/2,x+Math.sin(p)*b+r.fonts.clockInnerCircleFontSize/3)):o.fillText(m,v+Math.cos(p)*b-o.measureText(m).width/2,x+Math.sin(p)*b+r.fonts.clockInnerCircleFontSize/3)}r.duration&&r.durationNegative&&L(o,t)}}function Q(e,t){var o=D.get(0).getContext("2d");new RegExp("^(-|\\+)?([0-9]+).([0-9]{1,2})$").test(le());var a="-"==RegExp.$1,n=parseInt(RegExp.$2),c=parseInt(RegExp.$3);if(le()||(c=-1),r.onlyShowClockOnMobile||R.css("visibility","visible"),o.clearRect(0,0,g,g),o.beginPath(),o.arc(v,x,k,0,2*Math.PI,!1),o.fillStyle=r.colors.clockFaceColor,o.fill(),!s()&&e){60==e&&(e=0);var p=!0;r.maximum&&!l((a?"-":"")+n+":"+(e<10?"0":"")+e,r.maximum)&&(p=!1),r.minimum&&!l(r.minimum,(a?"-":"")+n+":"+(e<10?"0":"")+e)&&(p=!1),p&&(o.beginPath(),o.arc(v+Math.cos(Math.PI/6*(e/5-3))*w,x+Math.sin(Math.PI/6*(e/5-3))*w,15,0,2*Math.PI,!1),o.fillStyle=r.colors.hoverCircleColor,o.fill())}for(o.beginPath(),o.arc(v,x,3,0,2*Math.PI,!1),o.fillStyle=r.colors.selectorColor,o.fill(),!(c>-1)||r.maximum&&!l(n+":"+c,r.maximum)||r.minimum&&!l(r.minimum,n+":"+c)||(o.beginPath(),o.moveTo(v,x),o.lineTo(v+Math.cos(Math.PI/6*(c/5-3))*w,x+Math.sin(Math.PI/6*(c/5-3))*w),o.lineWidth=1,o.strokeStyle=r.colors.selectorColor,o.stroke(),o.beginPath(),o.arc(v+Math.cos(Math.PI/6*(c/5-3))*w,x+Math.sin(Math.PI/6*(c/5-3))*w,15,0,2*Math.PI,!1),o.fillStyle=r.colors.selectorColor,o.fill()),o.font=r.fonts.clockOuterCircleFontSize+"px "+r.fonts.fontFamily,i=1;i<=12;i++)if(Math.floor(5*i/r.precision)==5*i/r.precision){var m=Math.PI/6*(i-3);c==5*i||0==c&&12==i?o.fillStyle=r.colors.selectorNumberColor:o.fillStyle=r.colors.clockOuterCircleTextColor;var u=5*i==5?"05":5*i;60==u&&(u="00");p=!0;r.maximum&&!l((a?"-":"")+n+":"+u,r.maximum)&&(p=!1),r.minimum&&!l(r.minimum,(a?"-":"")+n+":"+u)&&(p=!1),p?o.fillText(u,v+Math.cos(m)*w-o.measureText(u).width/2,x+Math.sin(m)*w+r.fonts.clockOuterCircleFontSize/3):r.hideUnselectableNumbers||(o.fillStyle=r.colors.clockOuterCircleUnselectableTextColor,o.fillText(u,v+Math.cos(m)*w-o.measureText(u).width/2,x+Math.sin(m)*w+r.fonts.clockOuterCircleFontSize/3))}c>-1&&c%5!=0&&(o.beginPath(),o.arc(v+Math.cos(Math.PI/6*(c/5-3))*w,x+Math.sin(Math.PI/6*(c/5-3))*w,2,0,2*Math.PI,!1),o.fillStyle="white",o.fill()),r.duration&&r.durationNegative&&L(o,t)}function G(){var t;window.innerHeight<400?(h=window.innerHeight-60,R.css("width",h+200+"px"),F.css("position","absolute").css("left","0px").css("top","0px").css("width","200px").css("height",h+20+"px"),U.css("margin","10px 25px 0px 230px"),t=h+parseInt(U.css("margin-top"))+parseInt(U.css("margin-bottom"))):((h=window.innerWidth-80)>300&&(h=300),R.css("width",h+"px"),F.css("position","static").css("width","100%").css("height","auto"),U.css("margin","10px 25px 10px 25px"),t=h+parseInt(U.css("margin-top"))+parseInt(U.css("margin-bottom"))+65),R.css("left",parseInt((e("body").prop("clientWidth")-R.outerWidth())/2)+"px"),R.css("top",parseInt((window.innerHeight-t)/2)+"px"),g=h-50,k=parseInt(g/2),v=parseInt(g/2),x=parseInt(g/2),b=(w=k-16)-29,U.css("width",g+"px"),U.css("height",g+"px");var o=window.devicePixelRatio||1,i=N.get(0),a=D.get(0);i.width=g*o,i.height=g*o,a.width=g*o,a.height=g*o;var r=i.getContext("2d"),n=a.getContext("2d");r.scale(o,o),n.scale(o,o),N.css("width",g),N.css("height",g),D.css("width",g),D.css("height",g)}function J(){a.val()?pe(ne(a.val())):pe(ne("00:00")),!s()&&r.onlyShowClockOnMobile&&R.css("visibility","hidden"),s()&&G(),R.css("display","block"),K(),s()?I&&I.stop().css("opacity",0).css("display","block").animate({opacity:1},300):(Z(),e(window).on("scroll.clockTimePicker",(e=>{Z()}))),r.onOpen.call(a.get(0))}function Z(){var t=a.offset().top-e(window).scrollTop()+a.outerHeight();if(t+R.outerHeight()>window.innerHeight){var o=a.offset().top-e(window).scrollTop()-R.outerHeight();o>=0&&(t=o)}var i=a.offset().left-e(window).scrollLeft()-parseInt((R.outerWidth()-a.outerWidth())/2);R.css("left",i+"px").css("top",t+"px")}function ee(){e(window).off("scroll.clockTimePicker");var t=ne(a.val());if(m="",R.css("display","none"),s()?I.stop().animate({opacity:0},300,(function(){I.css("display","none")})):a.val(t),function(){if(document.activeElement==F.get(0)){var e=document.createElement("input");a.parent().get(0).appendChild(e),e.focus(),a.parent().get(0).removeChild(e)}}(),y||p||!t.match(new RegExp("^0+"+r.separator+"00$"))){if(p!=t){var o;if("createEvent"in document)(o=document.createEvent("HTMLEvents")).initEvent("change",!0,!1),a.get(0).dispatchEvent(o);else(o=document.createEventObject()).eventType="click",a.get(0).fireEvent("onchange",o);r.onChange.call(a.get(0),t.replace(/^\+/,""),p.replace(/^\+/,"")),p=t}}else pe("");r.onClose.call(a.get(0)),y=!1}function te(e){"HOUR"!=u&&(m="",_(),e?D.css("display","none"):D.css("zIndex",2).stop().animate({opacity:0,zoom:"80%",left:"10%",top:"10%"},r.modeSwitchSpeed,(function(){D.css("display","none")})),N.stop().css("zoom","100%").css("left","0px").css("top","0px").css("display","block").css("opacity",1).css("zIndex",1),u="HOUR",r.onModeSwitch.call(a.get(0),u))}function oe(e){"MINUTE"!=u&&(m="",Q(),D.stop().css("display","block").css("zoom","80%").css("left","10%").css("top","10%").css("opacity",0).css("zIndex",1),e?D.css("opacity",1).css("zoom","100%").css("left","0px").css("top","0px"):D.animate({opacity:1,zoom:"100%",left:"0px",top:"0px"}),u="MINUTE",r.onModeSwitch.call(a.get(0),u))}function ie(){F.focus(),setTimeout((function(){s()?(e(".clock-timepicker-mobile-time-hours").css("backgroundColor","rgba(255, 255, 255, 0.6)"),e(".clock-timepicker-mobile-time-minutes").css("backgroundColor","inherit")):F.get(0).setSelectionRange(0,le().indexOf(r.separator))}),1)}function ae(){F.focus(),setTimeout((function(){s()?(e(".clock-timepicker-mobile-time-hours").css("backgroundColor","inherit"),e(".clock-timepicker-mobile-time-minutes").css("backgroundColor","rgba(255, 255, 255, 0.6)")):F.get(0).setSelectionRange(le().indexOf(r.separator)+1,le().length)}),1)}function re(){r.autosize&&!s()&&(M.html(a.val()),M.css("display","inline-block"),a.css("width",M.outerWidth()+5+parseInt(a.css("padding-left"))+parseInt(a.css("padding-right"))+"px"),M.css("display","none"))}function ne(e){if(""==e)return r.required?r.duration?"0:00":"00:00":e;if(new RegExp("^(-|\\+)?([0-9]+)(.([0-9]{1,2})?)?$","i").test(e)){var t=parseInt(RegExp.$2);(a=parseInt(RegExp.$4))||(a=0);var o=!(!r.duration||!r.durationNegative||"-"!=RegExp.$1);if(t>=24&&!r.duration&&(t%=24),a>=60&&(a%=60),1!=r.precision){var i=Math.floor(a/r.precision);60==(a=i*r.precision+(1==Math.round((a-i*r.precision)/r.precision)?r.precision:0))&&(a=0,24!=++t||r.duration||(t=0))}e=(o?"-":"")+(t<10&&!r.duration?"0":"")+t+r.separator+(RegExp.$3?(a<10?"0":"")+a:"00")}else if(new RegExp("^(-|\\+)?.([0-9]{1,2})").test(e)){var a;(a=parseInt(RegExp.$2))>=60&&(a%=60),e=((o=!(!r.duration||!r.durationNegative||"-"!=RegExp.$1))&&a>0?"-":"")+"0"+(r.duration?"":"0")+r.separator+(a<10?"0":"")+a}else e="0"+(r.duration?"":"0")+r.separator+"00";return(r.duration&&r.useDurationPlusSign&&!e.match(/^\-/)&&!e.match(/^0+:00$/)?"+":"")+e}function ce(e){e.parent().find(".clock-timepicker-autosize").remove(),e.parent().find(".clock-timepicker-background").remove(),e.parent().find(".clock-timepicker-popup").remove(),e.unwrap(),e.off("drag.clockTimePicker dragend.clockTimePicker dragover.clockTimePicker dragenter.clockTimePicker dragstart.clockTimePicker dragleave.clockTimePicker drop.clockTimePicker selectstart.clockTimePicker contextmenu.clockTimePicker"),e.off("mousedown.clockTimePicker"),e.off("keyup.clockTimePicker"),e.off("keydown.clockTimePicker"),e.off("mousewheel.clockTimePicker"),e.off("focus.clockTimePicker"),e.attr("data-autocomplete-orig")?(e.attr("autocomplete",e.attr("data-autocomplete-orig")),e.removeAttr("data-autocomplete-orig")):e.removeAttr("autocomplete"),e.attr("data-autocorrect-orig")?(e.attr("autocorrect",e.attr("data-autocorrect-orig")),e.removeAttr("data-autocorrect-orig")):e.removeAttr("autocorrect"),e.attr("data-autocapitalize-orig")?(e.attr("autocapitalize",e.attr("data-autocapitalize-orig")),e.removeAttr("data-autocapitalize-orig")):e.removeAttr("autocapitalize"),e.attr("data-spellcheck-orig")?(e.attr("spellcheck",e.attr("data-spellcheck-orig")),e.removeAttr("data-spellcheck-orig")):e.removeAttr("spellcheck")}function se(e){if(!r.contextmenu||1==e.which)return function(e){var t="none"!=R.css("display");if(le())if(60==r.precision)te(!t),ie();else{var o=F.css("direction");o||(o="ltr");var i=F.css("text-align");i||(i="left");var a=F.innerWidth(),n=parseFloat(F.css("padding-left")),c=a-n-parseFloat(F.css("padding-right"));M.css("display","inline-block"),M.html(le());var s=M.innerWidth();M.html(r.separator);var l=M.innerWidth()/2;M.html(le().replace(new RegExp(r.separator+"[0-9]+$"),"")),l+=M.innerWidth(),M.css("display","none");var p=a/2;"left"==i||"justify"==i||"ltr"==o&&"start"==i||"rtl"==o&&"end"==i?p=Math.floor(n+l):"center"==i?p=Math.floor(n+(c-s)/2+l):("right"==i||"ltr"==o&&"end"==i||"rtl"==o&&"start"==i)&&(p=Math.floor(n+c-(s-l))),e.offsetX>=p-2&&(t||!r.alwaysSelectHoursFirst)?("HOUR"==u&&r.vibrate&&navigator.vibrate(10),oe(!t),ae()):("MINUTE"==u&&r.vibrate&&navigator.vibrate(10),te(!t),ie())}else pe(ne("00:00")),te(!t),ie();t||J()}(e),e.stopImmediatePropagation(),e.stopPropagation(),e.preventDefault(),!1}function le(){return s()?e(".clock-timepicker-mobile-time-hours").html()+r.separator+e(".clock-timepicker-mobile-time-minutes").html():F.val()}function pe(t){if(s()){if(t.match(/^(-|\\+)?([0-9]{1,2}).([0-9]{1,2})$/)){var o=RegExp.$1+(r.duration||1!=RegExp.$2.length?"":"0")+RegExp.$2,i=(1==RegExp.$3.length?"0":"")+RegExp.$3;e(".clock-timepicker-mobile-time-hours").html(o),e(".clock-timepicker-mobile-time-minutes").html(i)}}else F.val(t)}}));function s(){var e,t=!1;return e=navigator.userAgent||navigator.vendor||window.opera,(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(e)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(e.substr(0,4)))&&(t=!0),t}function l(e,t,o){var i="^(-|\\+)?([0-9]+)(.([0-9]{1,2}))?$";new RegExp(i,"i").test(e);var a=60*parseInt(RegExp.$2);RegExp.$4&&!o&&(a+=parseInt(RegExp.$4)),"-"==RegExp.$1&&(a*=-1),new RegExp(i,"i").test(t);var r=60*parseInt(RegExp.$2);return RegExp.$4&&!o&&(r+=parseInt(RegExp.$4)),"-"==RegExp.$1&&(r*=-1),a<=r}}}(jQuery);