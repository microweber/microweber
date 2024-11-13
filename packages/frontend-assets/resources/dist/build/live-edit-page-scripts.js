/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
/*!**************************************************************!*\
  !*** ./resources/assets/live-edit/live-edit-page-scripts.js ***!
  \**************************************************************/
__webpack_require__.r(__webpack_exports__);
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return e; }; var t, e = {}, r = Object.prototype, n = r.hasOwnProperty, o = Object.defineProperty || function (t, e, r) { t[e] = r.value; }, i = "function" == typeof Symbol ? Symbol : {}, a = i.iterator || "@@iterator", c = i.asyncIterator || "@@asyncIterator", u = i.toStringTag || "@@toStringTag"; function define(t, e, r) { return Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }), t[e]; } try { define({}, ""); } catch (t) { define = function define(t, e, r) { return t[e] = r; }; } function wrap(t, e, r, n) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype), c = new Context(n || []); return o(a, "_invoke", { value: makeInvokeMethod(t, r, c) }), a; } function tryCatch(t, e, r) { try { return { type: "normal", arg: t.call(e, r) }; } catch (t) { return { type: "throw", arg: t }; } } e.wrap = wrap; var h = "suspendedStart", l = "suspendedYield", f = "executing", s = "completed", y = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var p = {}; define(p, a, function () { return this; }); var d = Object.getPrototypeOf, v = d && d(d(values([]))); v && v !== r && n.call(v, a) && (p = v); var g = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(p); function defineIteratorMethods(t) { ["next", "throw", "return"].forEach(function (e) { define(t, e, function (t) { return this._invoke(e, t); }); }); } function AsyncIterator(t, e) { function invoke(r, o, i, a) { var c = tryCatch(t[r], t, o); if ("throw" !== c.type) { var u = c.arg, h = u.value; return h && "object" == _typeof(h) && n.call(h, "__await") ? e.resolve(h.__await).then(function (t) { invoke("next", t, i, a); }, function (t) { invoke("throw", t, i, a); }) : e.resolve(h).then(function (t) { u.value = t, i(u); }, function (t) { return invoke("throw", t, i, a); }); } a(c.arg); } var r; o(this, "_invoke", { value: function value(t, n) { function callInvokeWithMethodAndArg() { return new e(function (e, r) { invoke(t, n, e, r); }); } return r = r ? r.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(e, r, n) { var o = h; return function (i, a) { if (o === f) throw Error("Generator is already running"); if (o === s) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var c = n.delegate; if (c) { var u = maybeInvokeDelegate(c, n); if (u) { if (u === y) continue; return u; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (o === h) throw o = s, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = f; var p = tryCatch(e, r, n); if ("normal" === p.type) { if (o = n.done ? s : l, p.arg === y) continue; return { value: p.arg, done: n.done }; } "throw" === p.type && (o = s, n.method = "throw", n.arg = p.arg); } }; } function maybeInvokeDelegate(e, r) { var n = r.method, o = e.iterator[n]; if (o === t) return r.delegate = null, "throw" === n && e.iterator["return"] && (r.method = "return", r.arg = t, maybeInvokeDelegate(e, r), "throw" === r.method) || "return" !== n && (r.method = "throw", r.arg = new TypeError("The iterator does not provide a '" + n + "' method")), y; var i = tryCatch(o, e.iterator, r.arg); if ("throw" === i.type) return r.method = "throw", r.arg = i.arg, r.delegate = null, y; var a = i.arg; return a ? a.done ? (r[e.resultName] = a.value, r.next = e.nextLoc, "return" !== r.method && (r.method = "next", r.arg = t), r.delegate = null, y) : a : (r.method = "throw", r.arg = new TypeError("iterator result is not an object"), r.delegate = null, y); } function pushTryEntry(t) { var e = { tryLoc: t[0] }; 1 in t && (e.catchLoc = t[1]), 2 in t && (e.finallyLoc = t[2], e.afterLoc = t[3]), this.tryEntries.push(e); } function resetTryEntry(t) { var e = t.completion || {}; e.type = "normal", delete e.arg, t.completion = e; } function Context(t) { this.tryEntries = [{ tryLoc: "root" }], t.forEach(pushTryEntry, this), this.reset(!0); } function values(e) { if (e || "" === e) { var r = e[a]; if (r) return r.call(e); if ("function" == typeof e.next) return e; if (!isNaN(e.length)) { var o = -1, i = function next() { for (; ++o < e.length;) if (n.call(e, o)) return next.value = e[o], next.done = !1, next; return next.value = t, next.done = !0, next; }; return i.next = i; } } throw new TypeError(_typeof(e) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, o(g, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), o(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, u, "GeneratorFunction"), e.isGeneratorFunction = function (t) { var e = "function" == typeof t && t.constructor; return !!e && (e === GeneratorFunction || "GeneratorFunction" === (e.displayName || e.name)); }, e.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, define(t, u, "GeneratorFunction")), t.prototype = Object.create(g), t; }, e.awrap = function (t) { return { __await: t }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, c, function () { return this; }), e.AsyncIterator = AsyncIterator, e.async = function (t, r, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(wrap(t, r, n, o), i); return e.isGeneratorFunction(r) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, defineIteratorMethods(g), define(g, u, "Generator"), define(g, a, function () { return this; }), define(g, "toString", function () { return "[object Generator]"; }), e.keys = function (t) { var e = Object(t), r = []; for (var n in e) r.push(n); return r.reverse(), function next() { for (; r.length;) { var t = r.pop(); if (t in e) return next.value = t, next.done = !1, next; } return next.done = !0, next; }; }, e.values = values, Context.prototype = { constructor: Context, reset: function reset(e) { if (this.prev = 0, this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(resetTryEntry), !e) for (var r in this) "t" === r.charAt(0) && n.call(this, r) && !isNaN(+r.slice(1)) && (this[r] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0].completion; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(e) { if (this.done) throw e; var r = this; function handle(n, o) { return a.type = "throw", a.arg = e, r.next = n, o && (r.method = "next", r.arg = t), !!o; } for (var o = this.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i.completion; if ("root" === i.tryLoc) return handle("end"); if (i.tryLoc <= this.prev) { var c = n.call(i, "catchLoc"), u = n.call(i, "finallyLoc"); if (c && u) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } else if (c) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); } else { if (!u) throw Error("try statement without catch or finally"); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } } } }, abrupt: function abrupt(t, e) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var o = this.tryEntries[r]; if (o.tryLoc <= this.prev && n.call(o, "finallyLoc") && this.prev < o.finallyLoc) { var i = o; break; } } i && ("break" === t || "continue" === t) && i.tryLoc <= e && e <= i.finallyLoc && (i = null); var a = i ? i.completion : {}; return a.type = t, a.arg = e, i ? (this.method = "next", this.next = i.finallyLoc, y) : this.complete(a); }, complete: function complete(t, e) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && e && (this.next = e), y; }, finish: function finish(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.finallyLoc === t) return this.complete(r.completion, r.afterLoc), resetTryEntry(r), y; } }, "catch": function _catch(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.tryLoc === t) { var n = r.completion; if ("throw" === n.type) { var o = n.arg; resetTryEntry(r); } return o; } } throw Error("illegal catch attempt"); }, delegateYield: function delegateYield(e, r, n) { return this.delegate = { iterator: values(e), resultName: r, nextLoc: n }, "next" === this.method && (this.arg = t), y; } }, e; }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }

if (window.self !== window.top) {
  // mw.require('liveedit.css');

  mw.lib.require('tinymce');
  mw.liveEditSaveService = {
    grammarlyFix: function grammarlyFix(data) {
      mw.$("grammarly-btn", data).remove();
      mw.$("grammarly-card", data).remove();
      mw.$("g.gr_", data).each(function () {
        mw.$(this).replaceWith(this.innerHTML);
      });
      mw.$("[data-gramm_id]", data).removeAttr('data-gramm_id');
      mw.$("[data-gramm]", data).removeAttr('data-gramm');
      mw.$("[data-gramm_id]", data).removeAttr('data-gramm_id');
      mw.$("grammarly-card", data).remove();
      mw.$("grammarly-inline-cards", data).remove();
      mw.$("grammarly-popups", data).remove();
      mw.$("grammarly-extension", data).remove();
      return data;
    },
    saving: false,
    coreSave: function coreSave(data) {
      if (!data) return false;
      $.each(data, function () {
        var body = mw.tools.parseHtml(this.html).body;
        mw.liveEditSaveService.grammarlyFix(body);
        mw.liveEditSaveService.animationsClearFix(body);
        this.html = body.innerHTML;
      });
      mw.liveEditSaveService.saving = true;

      /************  START base64  ************/
      data.from_url = window.location.href;
      if (typeof mw.liveEditIframeData !== 'undefined') {
        //sometimes the url can be modified by pop state, so we use iframedata original url
        if (typeof mw.liveEditIframeData.content_link !== 'undefined') {
          data.from_url = mw.liveEditIframeData.content_link;
        }

        //sometimes the lang can be modified from another tab, so we use iframedata original lang
        if (typeof mw.liveEditIframeData.multiLanguageCurrentLanguage !== 'undefined') {
          data.lang = mw.liveEditIframeData.multiLanguageCurrentLanguage;
        }
      }
      data = JSON.stringify(data);
      data = btoa(encodeURIComponent(data).replace(/%([0-9A-F]{2})/g, function toSolidBytes(match, p1) {
        return String.fromCharCode('0x' + p1);
      }));
      data = {
        data_base64: data
      };
      /************  END base64  ************/

      var xhr = $.ajax({
        type: 'POST',
        url: mw.settings.api_url + 'save_edit',
        data: data,
        dataType: "json",
        success: function success(saved_data) {
          if (saved_data && saved_data.new_page_url && !mw.liveEditSaveService.DraftSaving) {
            window.mw.parent().askusertostay = false;
            window.mw.askusertostay = false;
            window.location.href = saved_data.new_page_url;
          }
        }
      });
      xhr.always(function () {
        mw.liveEditSaveService.saving = false;
      });
      return xhr;
    },
    parseContent: function parseContent(root) {
      root = root || document.body;
      var doc = mw.tools.parseHtml(root.innerHTML);
      mw.$('.element-current', doc).removeClass('element-current');
      mw.$('.mce-content-body', doc).removeClass('mce-content-body');
      mw.$('.mce-content-focus', doc).removeClass('mce-content-focus');
      mw.$('.element-active', doc).removeClass('element-active');
      mw.$('.disable-resize', doc).removeClass('disable-resize');
      mw.$('.mw-module-drag-clone', doc).removeClass('mw-module-drag-clone');
      mw.$('.ui-draggable', doc).removeClass('ui-draggable');
      mw.$('.ui-draggable-handle', doc).removeClass('ui-draggable-handle');
      mw.$('.mt-ready', doc).removeClass('mt-ready');
      mw.$('.mw-webkit-drag-hover-binded', doc).removeClass('mw-webkit-drag-hover-binded');
      mw.$('.module-cat-toggle-Modules', doc).removeClass('module-cat-toggle-Modules');
      mw.$('.mw-module-drag-clone', doc).removeClass('mw-module-drag-clone');
      mw.$('.mce-edit-focus', doc).removeClass('mce-edit-focus');
      mw.$('.mce-edit-body', doc).removeClass('mce-edit-body');
      mw.$('[data-mce-style]', doc).removeAttr('data-mce-style');
      mw.$('[mce-content-body]', doc).removeAttr('mce-content-body');
      mw.$('[data-mce-style]', doc).removeAttr('data-mce-style');
      mw.$('[data-mce-selected]', doc).removeAttr('data-mce-selected');
      mw.$('[data-mce-href]', doc).removeAttr('data-mce-href');
      mw.$('-module', doc).removeClass('-module');
      mw.$('.empty-element', doc).remove();
      mw.$('.empty-element', doc).remove();
      mw.$('[data-mce-bogus]', doc).remove();
      mw.$('.edit .ui-resizable-handle', doc).remove();
      mw.$('script', doc).remove();
      mw.$('.moveable-control-box', doc).remove();

      //var doc = mw.$(doc).find('script').remove();

      mw.tools.classNamespaceDelete('all', 'ui-', doc, 'starts');
      mw.$("[contenteditable]", doc).removeAttr("contenteditable");
      var all = doc.querySelectorAll('[contenteditable]'),
        l = all.length,
        i = 0;
      for (; i < l; i++) {
        all[i].removeAttribute('contenteditable');
      }
      var all = doc.querySelectorAll('.module'),
        l = all.length,
        i = 0;
      for (; i < l; i++) {
        if (all[i].querySelector('.edit') === null) {
          all[i].innerHTML = '';
        }
      }
      return doc;
    },
    htmlAttrValidate: function htmlAttrValidate(edits) {
      var _final = [];
      $.each(edits, function () {
        var html = this.outerHTML;
        html = html.replace(/url\(&quot;/g, "url('");
        html = html.replace(/jpg&quot;/g, "jpg'");
        html = html.replace(/jpeg&quot;/g, "jpeg'");
        html = html.replace(/png&quot;/g, "png'");
        html = html.replace(/gif&quot;/g, "gif'");
        _final.push($(html)[0]);
      });
      return _final;
    },
    cleanUnwantedTags: function cleanUnwantedTags(body) {
      mw.$('.mw-skip-and-remove,script', body).remove();
      return body;
    },
    animationsClearFix: function animationsClearFix(body) {
      mw.$('[class*="animate__"]').each(function () {
        mw.tools.classNamespaceDelete(this, 'animate__');
      });
      return body;
    },
    collectData: function collectData(edits) {
      mw.$(edits).each(function () {
        mw.$('meta', this).remove();
        $('.mw-le-spacer', this).empty().removeAttr('data-resizable').removeAttr('style');
      });
      edits = this.htmlAttrValidate(edits);
      var l = edits.length,
        i = 0,
        helper = {},
        master = {};
      if (l > 0) {
        for (; i < l; i++) {
          helper.item = edits[i];
          var rel = mw.tools.mwattr(helper.item, 'rel');
          if (!rel) {
            mw.$(helper.item).removeClass('changed');
            mw.tools.foreachParents(helper.item, function (loop) {
              var cls = this.className;
              var rel = mw.tools.mwattr(this, 'rel');
              if (mw.tools.hasClass(cls, 'edit') && mw.tools.hasClass(cls, 'changed') && !!rel) {
                helper.item = this;
              }
            });
          }
          var rel = mw.tools.mwattr(helper.item, 'rel');
          if (!rel) {
            var field = !!helper.item.id ? '#' + helper.item.id : '';
            console.warn('Skipped save: .edit' + field + ' element does not have rel attribute.');
            continue;
          }
          mw.$(helper.item).removeClass('changed orig_changed');
          mw.$(helper.item).removeClass('module-over');
          mw.$('.mw-le-ghost-layout', helper.item).remove();
          mw.$('#mw-non-existing-temp-element-holder', helper.item).remove();
          mw.$('.module-over', helper.item).each(function () {
            mw.$(this).removeClass('module-over');
          });
          mw.$('.element[data-mwplaceholder]', helper.item).each(function () {
            var isEmpty = !this.innerHTML.trim();
            if (!isEmpty) {
              mw.$(this).removeAttr('data-mwplaceholder');
            }
          });
          mw.$('.element.lipsum', helper.item).each(function () {
            mw.$(this).removeClass('lipsum');
          });
          mw.$('[data-mw-live-edithover]', helper.item).each(function () {
            mw.$(this).removeAttr('data-mw-live-edithover');
          });
          mw.$('[data-mw-temp-option-save]', helper.item).each(function () {
            mw.$(this).removeAttr('data-mw-temp-option-save');
          });
          mw.$('[class]', helper.item).each(function () {
            var cls = this.getAttribute("class");
            if (typeof cls === 'string') {
              cls = cls.trim();
            }
            if (!cls) {
              this.removeAttribute("class");
            }
          });
          var content = mw.liveEditSaveService.cleanUnwantedTags(helper.item).innerHTML;
          var attr_obj = {};
          var attrs = helper.item.attributes;
          if (attrs.length > 0) {
            var ai = 0,
              al = attrs.length;
            for (; ai < al; ai++) {
              attr_obj[attrs[ai].nodeName] = attrs[ai].nodeValue;
            }
          }
          var obj = {
            attributes: attr_obj,
            html: content
          };
          var objdata = "field_data_" + i;
          master[objdata] = obj;
        }
      }
      return master;
    },
    getData: function getData(root) {
      var body = mw.liveEditSaveService.parseContent(root).body,
        edits = body.querySelectorAll('.edit.changed');
      return mw.liveEditSaveService.collectData(edits);
    },
    saveDisabled: false,
    draftDisabled: false,
    save: function save(data, success, fail) {
      mw.trigger('beforeSaveStart', data);
      // todo:

      if (mw.top().app) {
        if (mw.top().app && mw.top().app.cssEditor) {
          mw.top().app.cssEditor.publishIfChanged();
        }
      }
      if (mw.top().app) {
        if (mw.top().app && mw.top().options) {
          mw.top().options.publishTempOptions(document);
        }
      }
      if (mw.liveEditSaveService.saveDisabled) {
        return false;
      }
      if (!data) {
        var body = mw.liveEditSaveService.parseContent().body,
          edits = body.querySelectorAll('.edit.changed');
        data = mw.liveEditSaveService.collectData(edits);
      }
      var animations = (mw.__pageAnimations || []).filter(function (item) {
        return item.animation !== 'none';
      });
      if (animations && animations.length > 0) {
        var options = {
          group: 'template',
          key: 'animations-global',
          value: JSON.stringify(animations)
        };
        mw.top().options.saveOption(options);
        // await new Promise(resolve =>  {
        //     mw.options.saveOption(options, function(){
        //         resolve();
        //     });
        // });
      }
      if (mw.tools.isEmptyObject(data)) {
        if (success) {
          success.call({});
        }
        return false;
      }
      mw.trigger('saveStart', data);
      var xhr = mw.liveEditSaveService.coreSave(data);
      console.log(xhr, xhr.fail);
      if (!xhr) {
        return false;
      }
      xhr.fail(function (sdata) {
        if (xhr.status == 403) {
          var modal = mw.dialog({
            id: 'save_content_error_iframe_modal',
            html: "<iframe id='save_content_error_iframe' style='overflow-x:hidden;overflow-y:auto;' class='mw-modal-frame' ></iframe>",
            width: $(window).width() - 90,
            height: $(window).height() - 90
          });
          mw.askusertostay = false;
          mw.$("#save_content_error_iframe").ready(function () {
            var doc = document.getElementById('save_content_error_iframe').contentWindow.document;
            doc.open();
            doc.write(xhr.responseText);
            doc.close();
            var save_content_error_iframe_reloads = 0;
            doc = document.getElementById('save_content_error_iframe').contentWindow.document;
            mw.$("#save_content_error_iframe").on('load', function () {
              var is_cf = mw.$('.challenge-form', doc).length;
              save_content_error_iframe_reloads++;
              if (is_cf && save_content_error_iframe_reloads == 2) {
                setTimeout(function () {
                  mw.askusertostay = false;
                  mw.$('#save_content_error_iframe_modal').remove();
                }, 150);
              }
            });
          });
        }
        if (fail) {
          fail.call(sdata);
        }
      });
      xhr.done(function (sdata) {
        mw.$('.edit.changed').removeClass('changed');
        mw.$('.orig_changed').removeClass('orig_changed');
        if (document.querySelector('.edit.changed') !== null) {
          mw.liveEditSaveService.save();
        } else {
          mw.askusertostay = false;
          mw.trigger('saveEnd', sdata);
        }
        if (success) {
          success.call(sdata);
        }
      });
      xhr.fail(function (jqXHR, textStatus, errorThrown) {
        mw.trigger('saveFailed', textStatus, errorThrown);
        if (fail) {
          fail.call(sdata);
        }
      });
      return xhr;
    }
  };
  mw.saveLiveEdit = /*#__PURE__*/_asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee() {
    return _regeneratorRuntime().wrap(function _callee$(_context) {
      while (1) switch (_context.prev = _context.next) {
        case 0:
          return _context.abrupt("return", new Promise(function (resolve) {
            mw.liveEditSaveService.save(undefined, function () {
              return resolve(true);
            }, function () {
              return resolve(false);
            });
          }));
        case 1:
        case "end":
          return _context.stop();
      }
    }, _callee);
  }));
  mw.top().app.save = /*#__PURE__*/_asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee2() {
    return _regeneratorRuntime().wrap(function _callee2$(_context2) {
      while (1) switch (_context2.prev = _context2.next) {
        case 0:
          _context2.next = 2;
          return mw.saveLiveEdit();
        case 2:
          return _context2.abrupt("return", _context2.sent);
        case 3:
        case "end":
          return _context2.stop();
      }
    }, _callee2);
  }));
  addEventListener('load', function () {
    window.addEventListener('keydown', function (event) {
      mw.top().app.canvas.dispatch('iframeKeyDown', {
        event: event
      });
    });
    mw.$('span.mw-powered-by').on("click", function (e) {
      mw.top().tools.open_global_module_settings_modal('white_label/admin', 'mw-powered-by');
      return false;
    });
    var _handleEmptyEditFields = function _handleEmptyEditFields() {
      function manageNode(node) {
        var isEmptyLike = !node.innerHTML.trim();
        if (isEmptyLike && node.innerHTML.trim() === node.textContent.trim()) {
          // mw.element(node).append(`<p class="element" data-mwplaceholder="${mw.lang(`This is sample text for your page`)}"></p>`);
        } else {
          node.classList[isEmptyLike ? 'add' : 'remove']('mw-le-empty-element');
        }
      }
      document.querySelectorAll('.edit').forEach(function (node) {
        if (!node.__$$_handleEmptyEditFields) {
          node.__$$_handleEmptyEditFields = true;
          manageNode(node);
          node.addEventListener('input', function () {
            manageNode(this);
          });
        }
      });
      mw.top().app.on('editChanged', function (edit) {
        setTimeout(function () {
          return manageNode(edit);
        });
      });
    };
    _handleEmptyEditFields();
  });
  var _beforeUnload = null;
  mw.top().app.isNavigating = function () {
    return !!_beforeUnload && _beforeUnload.returnValue && _beforeUnload.defaultPrevented === true;
  };
  self.onbeforeunload = function (event) {
    _beforeUnload = event;

    // prevent user from leaving if there are unsaved changes
    var liveEditIframe = self;

    // var liveEditIframe = mw.top().app.canvas.getWindow();

    liveEditIframe.mw.isNavigating = true;
    if (typeof mw.top === 'function' && mw.top().app.canvas) {
      mw.top().app.canvas.dispatch('liveEditCanvasBeforeUnload');
    }
    setTimeout(function (liveEditIframe) {
      if (liveEditIframe) {
        if (liveEditIframe && liveEditIframe.mw) {
          liveEditIframe.mw.isNavigating = false;
        }
      }
    }, 1, liveEditIframe);
    if (liveEditIframe && liveEditIframe.mw && liveEditIframe.mw.askusertostay) {
      return true;
    } else {
      if (typeof mw.top === 'function' && mw.top().app.canvas) {
        var frame = mw.top().app.canvas.getFrame();
        if (frame && frame.parentElement) {
          mw.top().spinner({
            element: frame.parentElement,
            decorate: true,
            size: 52
          }).show();
        }
      }
    }
  };
  mw.drag = mw.drag || {};
  mw.drag.save = function () {
    return mw.liveEditSaveService.save();
  };
  mw.drag.fix_placeholders = function (isHard, selector) {
    selector = selector || '.edit .row';
    var more_selectors2 = 'div.col-md';
    var a = mw.top().app.templateSettings.helperClasses.external_grids_col_classes;
    var index;
    for (index = a.length - 1; index >= 0; --index) {
      more_selectors2 += ',div.' + a[index];
    }
    mw.$(selector).each(function () {
      var el = mw.$(this);
      el.children(more_selectors2).each(function () {
        var empty_child = mw.$(this).children('*');
        if (empty_child.length == 0) {
          mw.$(this).append('<div class="element" id="mw-element-' + mw.random() + '">' + '</div>');
          var empty_child = mw.$(this).children("div.element");
        }
      });
    });
  };
  mw.drag.module_settings = function () {
    var target = mw.top().app.liveEdit.moduleHandle.getTarget();
    return mw.top().app.editor.dispatch('onModuleSettingsRequest', target);
  };
  document.documentElement.addEventListener('click', function (event) {
    var target = event.target;
    var link = mw.tools.firstParentOrCurrentWithTag(target, 'a');
    if (link && link.href.indexOf('javascript:') !== 0 && !link.isContentEditable) {
      var tmp = document.createElement('a');
      tmp.href = link.href;
      if (tmp.host !== location.host && (!link.target || link.target === '_self')) {
        event.preventDefault();
        open(link.href);
      }
    }
  });
  document.addEventListener('keydown', function (event) {
    if (event.ctrlKey && event.keyCode === 83) {
      return mw.top().app.editor.dispatch('Ctrl+S', event);
    }
  });
  document.body.addEventListener('mouseenter', function (event) {
    $('body').removeClass('mw-live-body-mouse-moved-outside');
  });
  document.body.addEventListener('mouseleave', function (event) {
    $('body').addClass('mw-live-body-mouse-moved-outside');
  });
}
if (self === top) {
  window.addEventListener("load", function (event) {
    if (window.mwLiveEditIframeBackUrl) {
      // Create the <a> element (button)
      var stickyButton = document.createElement('a');
      stickyButton.id = 'back-to-live-sticky-button';
      stickyButton.textContent = 'Go Live Edit';
      stickyButton.href = window.mwLiveEditIframeBackUrl;
      stickyButton.classList.add('sticky');

      // Append the button to the document body
      document.body.appendChild(stickyButton);

      // Apply sticky behavior
      stickyButton.classList.add('sticky');

      // Create and apply the CSS style dynamically
      var style = document.createElement('style');
      style.textContent = "\n                    #back-to-live-sticky-button {\n                        position: fixed;\n                        left: 50%;\n                        transform: translateX(-50%);\n                        z-index: 1001;\n                        transition: top 0.3s;\n                        background: #3498db;\n                        color: #fff !important;\n                        padding: 10px 20px;\n                        border-radius: 5px;\n                        border-top-left-radius: 0;\n                        border-top-right-radius: 0;\n                        font-family: Arial, sans-serif;\n                        border: none;\n                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);\n                        cursor: pointer;\n                    }\n\n                    #back-to-live-sticky-button:hover {\n                        background: #2980b9;\n                    }\n\n\n                    #back-to-live-sticky-button.sticky {\n                        top: 0;\n                    }\n\n\n\n\n            ";
      document.head.appendChild(style);
    }

    // todo: remove
    document.querySelectorAll('.no-select [class*="col"]').forEach(function (node) {
      if (Array.from(node.children).filter(function (c) {
        return c.classList.contains('element');
      }).length) {
        node.classList.add('allow-select');
      }
    });
  });
}
/******/ })()
;