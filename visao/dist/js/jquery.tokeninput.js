!function(e){var t={method:"GET",contentType:"json",queryParam:"q",searchDelay:300,minChars:1,propertyToSearch:"name",jsonContainer:null,hintText:"Type in a search term",noResultsText:"No results",searchingText:"Searching...",deleteText:"&times;",animateDropdown:!0,tokenLimit:null,tokenDelimiter:",",preventDuplicates:!1,tokenValue:"id",prePopulate:null,processPrePopulate:!1,idPrefix:"token-input-",resultsFormatter:function(e){return"<li>"+e[this.propertyToSearch]+"</li>"},tokenFormatter:function(e){return"<li><p>"+e[this.propertyToSearch]+"</p></li>"},onResult:null,onAdd:null,onDelete:null,onReady:null},n={tokenList:"token-input-list",token:"token-input-token",tokenDelete:"token-input-delete-token",selectedToken:"token-input-selected-token",highlightedToken:"token-input-highlighted-token",dropdown:"token-input-dropdown",dropdownItem:"token-input-dropdown-item",dropdownItem2:"token-input-dropdown-item2",selectedDropdownItem:"token-input-selected-dropdown-item",inputToken:"token-input-input-token"},o={BEFORE:0,AFTER:1,END:2},i={BACKSPACE:8,TAB:9,ENTER:13,ESCAPE:27,SPACE:32,PAGE_UP:33,PAGE_DOWN:34,END:35,HOME:36,LEFT:37,UP:38,RIGHT:39,DOWN:40,NUMPAD_ENTER:108,COMMA:188},s={init:function(n,o){var i=e.extend({},t,o||{});return this.each(function(){e(this).data("tokenInputObject",new e.TokenList(this,n,i))})},clear:function(){return this.data("tokenInputObject").clear(),this},add:function(e){return this.data("tokenInputObject").add(e),this},remove:function(e){return this.data("tokenInputObject").remove(e),this},get:function(){return this.data("tokenInputObject").getTokens()}};e.fn.tokenInput=function(e){return s[e]?s[e].apply(this,Array.prototype.slice.call(arguments,1)):s.init.apply(this,arguments)},e.TokenList=function(t,s,a){function r(){return null!==a.tokenLimit&&F>=a.tokenLimit?(I.hide(),void g()):void 0}function l(){if(O!==(O=I.val())){var e=O.replace(/&/g,"&").replace(/\s/g," ").replace(/</g,"&lt;").replace(/>/g,"&gt;");W.html(e),I.width(W.width()+30)}}function u(t){var n=a.tokenFormatter(t);n=e(n).addClass(a.classes.token).insertBefore(M),e("<span>"+a.deleteText+"</span>").addClass(a.classes.tokenDelete).appendTo(n).click(function(){return h(e(this).parent()),S.change(),!1});var o={id:t.id};return o[a.propertyToSearch]=t[a.propertyToSearch],e.data(n.get(0),"tokeninput",t),t.hasOwnProperty("id_professor")?(L=L.slice(0,j).concat({nome_professor:t.nome_professor,id_professor:t.id_professor}).concat(L.slice(j)),j++):(L=L.slice(0,j).concat({nome_usuario:t.nome_usuario,id_usuario:t.id_usuario}).concat(L.slice(j)),j++),k(L,S),F+=1,null!==a.tokenLimit&&F>=a.tokenLimit&&(I.hide(),g()),n}function c(t){var n=a.onAdd;if(F>0&&a.preventDuplicates){var o=null;if(G.children().each(function(){var n=e(this),i=e.data(n.get(0),"tokeninput");return i&&i.id===t.id?(o=n,!1):void 0}),o)return d(o),M.insertAfter(o),void I.focus()}(null==a.tokenLimit||F<a.tokenLimit)&&(u(t),r()),I.val(""),g(),e.isFunction(n)&&n.call(S,t)}function d(e){e.addClass(a.classes.selectedToken),N=e.get(0),I.val(""),g()}function p(e,t){e.removeClass(a.classes.selectedToken),N=null,t===o.BEFORE?(M.insertBefore(e),j--):t===o.AFTER?(M.insertAfter(e),j++):(M.appendTo(G),j=F),I.focus()}function f(t){var n=N;N&&p(e(N),o.END),n===t.get(0)?p(t,o.END):d(t)}function h(t){var n=e.data(t.get(0),"tokeninput"),o=a.onDelete,i=t.prevAll().length;i>j&&i--,t.remove(),N=null,I.focus(),L=L.slice(0,i).concat(L.slice(i+1)),j>i&&j--,k(L,S),F-=1,null!==a.tokenLimit&&I.show().val("").focus(),e.isFunction(o)&&o.call(S,n)}function k(t,n){var o=e.map(t,function(e){return e[a.tokenValue]});n.val(o.join(a.tokenDelimiter))}function g(){U.hide().empty(),B=null}function m(){U.css({position:"absolute",top:e(G).offset().top+e(G).outerHeight(),left:e(G).offset().left,zindex:999}).show()}function v(){a.searchingText&&(U.html("<p>"+a.searchingText+"</p>"),m())}function T(){a.hintText&&(U.html("<p>"+a.hintText+"</p>"),m())}function y(e,t){return e.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)("+t+")(?![^<>]*>)(?![^&;]+;)","gi"),"<b>$1</b>")}function w(e,t,n){return e.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)("+t+")(?![^<>]*>)(?![^&;]+;)","g"),y(t,n))}function C(t,n){achou=!1;var o=new Array,i="";"undefined"!=typeof n[0]&&null!==n[0]&&n.length>0&&(i=n[0].hasOwnProperty("id_professor")?"id_professor":"id_usuario");for(var s=0;s<n.length;s++)o[s]=n[s][i];var r=!1,l=new Array;if(n&&n.length){U.empty();var u=e("<ul>").appendTo(U).mouseover(function(t){E(e(t.target).closest("li"))}).mousedown(function(t){return c(e(t.target).closest("li").data("tokeninput")),S.change(),!1}).hide();e.each(n,function(n,i){if(i.hasOwnProperty("id_professor"))for(var c in L)L[c].id_professor==i.id_professor&&l.push(i.id_professor);else for(var c in L)L[c].id_usuario==i.id_usuario&&l.push(i.id_usuario);var d=a.resultsFormatter(i);if(d=w(d,i[a.propertyToSearch],t),i.hasOwnProperty("id_professor")){var p=(new Array,l.indexOf(i.id_professor));if(l[p]!==i.id_professor){if(d=e(d).appendTo(u),d.addClass(n%2?a.classes.dropdownItem:a.classes.dropdownItem2),!achou)for(s=-1;s<o.length;s++)if(o.indexOf(i.id_professor)>=0){achou=!0,E(d);break}e.data(d.get(0),"tokeninput",i),r=!0}}else{r=!1;var f=l.indexOf(i.id_usuario);if(l[f]!==i.id_usuario){if(d=e(d).appendTo(u),d.addClass(n%2?a.classes.dropdownItem:a.classes.dropdownItem2),0===n&&E(d),!achou)for(s=-1;s<o.length;s++)if(o.indexOf(i.id_usuario)>=0){achou=!0,E(d);break}e.data(d.get(0),"tokeninput",i),r=!0}else r=!1}}),m(),a.animateDropdown?u.slideDown("fast"):u.show(),r||a.noResultsText&&(U.html("<p>"+a.noResultsText+"</p>"),m())}else a.noResultsText&&(U.html("<p>"+a.noResultsText+"</p>"),m())}function E(t){t&&(B&&x(e(B)),t.addClass(a.classes.selectedDropdownItem),B=t.get(0))}function x(e){e.removeClass(a.classes.selectedDropdownItem),B=null}function _(){var t=I.val().toLowerCase();t&&t.length&&(N&&p(e(N),o.AFTER),t.length>=a.minChars?(v(),clearTimeout(P),P=setTimeout(function(){R(t)},a.searchDelay)):g())}function R(t){var n=t+D(),o=b.get(n);if(o)C(t,o);else if(a.url){var i=D(),s={};if(s.data={},i.indexOf("?")>-1){var r=i.split("?");s.url=r[0];var l=r[1].split("&");e.each(l,function(e,t){var n=t.split("=");s.data[n[0]]=n[1]})}else s.url=i;s.data[a.queryParam]=t,s.type=a.method,s.dataType=a.contentType,a.crossDomain&&(s.dataType="jsonp"),s.success=function(o){e.isFunction(a.onResult)&&(o=a.onResult.call(S,o)),b.add(n,a.jsonContainer?o[a.jsonContainer]:o),I.val().toLowerCase()===t&&C(t,a.jsonContainer?o[a.jsonContainer]:o)},s.error=function(){a.noResultsText&&(U.html("<p>"+a.noResultsText+"</p>"),m())},e.ajax(s)}else if(a.local_data){var u=e.grep(a.local_data,function(e){return e[a.propertyToSearch].toLowerCase().indexOf(t.toLowerCase())>-1});e.isFunction(a.onResult)&&(u=a.onResult.call(S,u)),b.add(n,u),C(t,u)}}function D(){var e=a.url;return"function"==typeof a.url&&(e=a.url.call()),e}if("string"===e.type(s)||"function"===e.type(s)){a.url=s;var A=D();void 0===a.crossDomain&&(a.crossDomain=-1===A.indexOf("://")?!1:location.href.split(/\/+/g)[1]!==A.split(/\/+/g)[1])}else"object"==typeof s&&(a.local_data=s);a.classes?a.classes=e.extend({},n,a.classes):a.theme?(a.classes={},e.each(n,function(e,t){a.classes[e]=t+"-"+a.theme})):a.classes=n;var P,O,L=[],F=0,b=new e.TokenList.Cache,I=e('<input type="text"  autocomplete="off">').css({outline:"none"}).attr("id",a.idPrefix+t.id).focus(function(){(null===a.tokenLimit||a.tokenLimit!==F)&&T()}).blur(function(){g(),e(this).val("")}).bind("keyup keydown blur update",l).keydown(function(t){var n,s;switch(t.keyCode){case i.LEFT:case i.RIGHT:case i.UP:case i.DOWN:if(e(this).val()){var a=null;return a=t.keyCode===i.DOWN||t.keyCode===i.RIGHT?e(B).next():e(B).prev(),a.length&&E(a),!1}n=M.prev(),s=M.next(),n.length&&n.get(0)===N||s.length&&s.get(0)===N?t.keyCode===i.LEFT||t.keyCode===i.UP?p(e(N),o.BEFORE):p(e(N),o.AFTER):t.keyCode!==i.LEFT&&t.keyCode!==i.UP||!n.length?t.keyCode!==i.RIGHT&&t.keyCode!==i.DOWN||!s.length||d(e(s.get(0))):d(e(n.get(0)));break;case i.BACKSPACE:if(n=M.prev(),!e(this).val().length)return N?(h(e(N)),S.change()):n.length&&d(e(n.get(0))),!1;1===e(this).val().length?g():setTimeout(function(){_()},5);break;case i.TAB:case i.ENTER:case i.NUMPAD_ENTER:case i.COMMA:if(B)return c(e(B).data("tokeninput")),S.change(),!1;break;case i.ESCAPE:return g(),!0;default:String.fromCharCode(t.which)&&setTimeout(function(){_()},5)}}),S=e(t).hide().val("").focus(function(){I.focus()}).blur(function(){I.blur()}),N=null,j=0,B=null,G=e("<ul />").addClass(a.classes.tokenList).click(function(t){var n=e(t.target).closest("li");n&&n.get(0)&&e.data(n.get(0),"tokeninput")?f(n):(N&&p(e(N),o.END),I.focus())}).mouseover(function(t){var n=e(t.target).closest("li");n&&N!==this&&n.addClass(a.classes.highlightedToken)}).mouseout(function(t){var n=e(t.target).closest("li");n&&N!==this&&n.removeClass(a.classes.highlightedToken)}).insertBefore(S),M=e("<li />").addClass(a.classes.inputToken).appendTo(G).append(I),U=e("<div>").addClass(a.classes.dropdown).appendTo("body").hide(),W=e("<tester/>").insertAfter(I).css({position:"absolute",top:-9999,left:-9999,width:"auto",fontSize:I.css("fontSize"),fontFamily:I.css("fontFamily"),fontWeight:I.css("fontWeight"),letterSpacing:I.css("letterSpacing"),whiteSpace:"nowrap"});S.val("");var H=a.prePopulate||S.data("pre");a.processPrePopulate&&e.isFunction(a.onResult)&&(H=a.onResult.call(S,H)),H&&H.length&&e.each(H,function(e,t){u(t),r()}),e.isFunction(a.onReady)&&a.onReady.call(),this.clear=function(){G.children("li").each(function(){0===e(this).children("input").length&&h(e(this))})},this.add=function(e){c(e)},this.remove=function(t){G.children("span").each(function(){if(0===e(this).children("input").length){var n=e(this).data("tokeninput"),o=!0;for(var i in t)if(t[i]!==n[i]){o=!1;break}o&&h(e(this))}})},this.getTokens=function(){return L}},e.TokenList.Cache=function(t){var n=e.extend({max_size:500},t),o={},i=0,s=function(){o={},i=0};this.add=function(e,t){i>n.max_size&&s(),o[e]||(i+=1),o[e]=t},this.get=function(e){return o[e]}}}(jQuery);