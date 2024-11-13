/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/assets/components/colorpicker.js":
/*!****************************************************!*\
  !*** ./resources/assets/components/colorpicker.js ***!
  \****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
;
(function () {
  mw._colorPickerDefaults = {
    skin: 'mw-tooltip-default',
    position: 'bottom-center',
    onchange: false
  };
  var _isValidColorTest = new Option();
  var isValidColor = function isValidColor(color) {
    _isValidColorTest.style.color = '';
    _isValidColorTest.style.color = color;
    return _isValidColorTest.style.color !== '';
  };
  var colorPickers = [];
  mw._colorPicker = function (options) {
    if (!mw.tools.colorPickerColors) {
      mw.tools.colorPickerColors = [];
      if (typeof mw.top().app !== 'undefined' && typeof mw.top().app.templateSettings !== 'undefined' && typeof mw.top().app.templateSettings.colorPaletteManager !== 'undefined') {
        var colorsFromPallete = mw.top().app.templateSettings.colorPaletteManager.getColors();
        if (colorsFromPallete.length > 0) {
          colorsFromPallete.forEach(function (color) {
            if (mw.tools.colorPickerColors.indexOf(color) === -1) {
              mw.tools.colorPickerColors.push(color);
            }
          });
        }
      } else if (typeof mw.color !== 'undefined' && typeof mw.color.rgbToHex !== 'undefined') {
        //legacy mode

        var colorpicker_els = mw.top().$(".btn,h1,h2,h3,h4,h5");
        if (colorpicker_els.length > 0) {
          colorpicker_els.each(function () {
            var css = parent.getComputedStyle(this, null);
            if (css !== null) {
              if (mw.tools.colorPickerColors.indexOf(css.color) === -1) {
                mw.tools.colorPickerColors.push(mw.color.rgbToHex(css.color));
              }
              if (mw.tools.colorPickerColors.indexOf(css.backgroundColor) === -1) {
                mw.tools.colorPickerColors.push(mw.color.rgbToHex(css.backgroundColor));
              }
            }
          });
        }
      }
    }
    var proto = this;
    if (!options) {
      return false;
    }
    var settings = $.extend({}, mw._colorPickerDefaults, options);
    if (settings.element === undefined || settings.element === null) {
      return false;
    }
    var $el = mw.$(settings.element);
    if ($el[0] === undefined) {
      return false;
    }
    if ($el[0].mwcolorPicker) {
      return $el[0].mwcolorPicker;
    }
    $el[0].mwcolorPicker = this;
    this.element = $el[0];
    if ($el[0].mwToolTipBinded !== undefined) {
      return false;
    }
    if (!settings.method) {
      if (this.element.nodeName === 'DIV') {
        settings.method = 'inline';
      }
    }
    this.settings = settings;
    $el[0].mwToolTipBinded = true;
    var sett = {
      showAlpha: true,
      showHSL: false,
      showRGB: false,
      showHEX: true,
      //     paletteEditable: true,
      palette: mw.tools.colorPickerColors
    };
    if (typeof mw.top().app !== 'undefined' && typeof mw.top().app.templateSettings !== 'undefined' && typeof mw.top().app.templateSettings.colorPaletteManager !== 'undefined') {
      var colorsFromPallete = mw.top().app.templateSettings.colorPaletteManager.getColors();
      if (colorsFromPallete.length > 0) {
        colorsFromPallete.forEach(function (color) {
          if (mw.tools.colorPickerColors.indexOf(color) === -1) {
            mw.tools.colorPickerColors.push(color);
          }
        });
      }
      sett.palette = mw.tools.colorPickerColors;
      sett.paletteEditable = true;
    }
    if (settings.value) {
      sett.color = settings.value;
    }
    if (typeof settings.showRGB !== 'undefined') {
      sett.showRGB = settings.showRGB;
    }
    if (typeof settings.showHEX !== 'undefined') {
      sett.showHEX = settings.showHEX;
    }
    if (typeof settings.showHSL !== 'undefined') {
      sett.showHSL = settings.showHSL;
    }
    var frame;
    this.setColor = function (color) {
      frame.setColor(color);
    };
    if (settings.method === 'inline') {
      sett.attachTo = $el[0];
      frame = sett.attachTo.ownerDocument.defaultView.AColorPicker.createPicker(sett); // AColorPicker works only with local window
      frame.on('coloradd', function (picker, color) {
        if (typeof mw.top().app !== 'undefined' && typeof mw.top().app.templateSettings !== 'undefined' && typeof mw.top().app.templateSettings.colorPaletteManager !== 'undefined') {
          mw.top().app.templateSettings.colorPaletteManager.addToPaletteMemory(color);
        }
      });
      frame.on('colorremove', function (picker, color) {
        if (typeof mw.top().app !== 'undefined' && typeof mw.top().app.templateSettings !== 'undefined' && typeof mw.top().app.templateSettings.colorPaletteManager !== 'undefined') {
          mw.top().app.templateSettings.colorPaletteManager.removeFromPaletteMemory(color);
        }
      });
      frame.onchange = function (data) {
        if (proto.settings.onchange) {
          proto.settings.onchange(data.color);
        }
        if ($el[0].nodeName === 'INPUT') {
          var val = val === 'transparent' ? val : '#' + val;
          $el.val(val);
          var prev = $el[0].previousElementSibling;
          if (prev && prev.classList.contains('mw-field-color-indicator')) {
            var dp = prev.querySelector('.mw-field-color-indicator-display');
            if (dp) {
              dp.style.backgroundColor = val;
            }
          }
        }
      };
    } else {
      var tip = mw.tooltip(settings),
        $tip = mw.$(tip).hide();
      colorPickers.push(tip);
      this.tip = tip;
      mw.$('.mw-tooltip-content', tip).empty().css({
        padding: 0
      });
      sett.attachTo = mw.$('.mw-tooltip-content', tip)[0];
      frame = AColorPicker.createPicker(sett);
      frame.on('coloradd', function (picker, color) {
        if (typeof mw.top().app !== 'undefined' && typeof mw.top().app.templateSettings !== 'undefined' && typeof mw.top().app.templateSettings.colorPaletteManager !== 'undefined') {
          mw.top().app.templateSettings.colorPaletteManager.addToPaletteMemory(color);
        }
      });
      frame.on('colorremove', function (picker, color) {
        if (typeof mw.top().app !== 'undefined' && typeof mw.top().app.templateSettings !== 'undefined' && typeof mw.top().app.templateSettings.colorPaletteManager !== 'undefined') {
          mw.top().app.templateSettings.colorPaletteManager.removeFromPaletteMemory(color);
        }
      });
      frame.onchange = function (data) {
        if (frame.pause) {
          return;
        }
        if (proto.settings.onchange) {
          proto.settings.onchange(data.color);
        }
        if ($el[0].nodeName === 'INPUT') {
          $el.val(data.color);
          var prev = $el[0].previousElementSibling;
          if (prev && prev.classList.contains('mw-field-color-indicator')) {
            var dp = prev.querySelector('.mw-field-color-indicator-display');
            if (dp) {
              dp.style.backgroundColor = data.color;
            }
          }
        }
      };
      if ($el[0].nodeName === 'INPUT') {
        $el.on('input', function (e) {
          var val = this.value.trim();
          if (!val) {
            val = 'transparent';
          }
          if (isValidColor(val)) {
            var prev = $el[0].previousElementSibling;
            if (prev && prev.classList.contains('mw-field-color-indicator')) {
              var dp = prev.querySelector('.mw-field-color-indicator-display');
              if (dp) {
                dp.style.backgroundColor = val;
              }
            }
            if (proto.settings.onchange) {
              proto.settings.onchange(val);
            }
          }
        });
        $el.on('focus', function (e) {
          if (this.value.trim()) {
            frame.pause = true;
            frame.color = this.value;
            setTimeout(function () {
              frame.pause = false;
            });
          }
          colorPickers.forEach(function (clpckr) {
            if (clpckr !== tip) {
              mw.$(clpckr).hide();
            }
          });
          mw.$(tip).show();
          mw.tools.tooltip.setPosition(tip, $el[0], settings.position);
        });
      } else {
        $el.on('click', function (e) {
          colorPickers.forEach(function (clpckr) {
            if (clpckr !== tip) {
              mw.$(clpckr).hide();
            }
          });
          mw.$(tip).toggle();
          mw.tools.tooltip.setPosition(tip, $el[0], settings.position);
        });
      }
      var documents = [document];
      if (self !== mw.top().win) {
        documents.push(mw.top().win.document);
      }
      $el.addClass('mw-color-picker-field');
    }
    if (frame && frame.element) {
      //append remove color
      var frameEl = $(frame.element).find('.a-color-picker-palette').first()[0];
      var removeColorButton = mw.element('<div class="a-color-picker-palette-color color-picker-palette-color-transparent"  style="background-image: repeating-conic-gradient(silver 0 25%, transparent 0 50%);background-size: 9px 9px;"></div>');
      removeColorButton.on('click', function (e) {
        e.stopPropagation();
        if (proto.settings.onchange) {
          proto.settings.onchange('rgb(0 0 0 / 0%)');
        }
      });
      var resetColorButton = mw.element("\n                <div\n                    class=\"a-color-picker-palette-color color-picker-palette-color-reset\"></div>\n                    ");
      resetColorButton.on('click', function (e) {
        e.stopPropagation();
        if (proto.settings.onchange) {
          proto.settings.onchange('revert-layer');
        }
      });
      frameEl.append(removeColorButton.get(0));
      frameEl.append(resetColorButton.get(0));
      if (typeof mw.top().app !== 'undefined' && typeof mw.top().app.templateSettings !== 'undefined' && typeof mw.top().app.templateSettings.colorPaletteManager !== 'undefined') {
        if (proto.settings.value) {
          var inMemory = mw.top().app.templateSettings.colorPaletteManager.isInPaletteMemory(proto.settings.value);
          if (inMemory) {
            var removeColorFromMemory = mw.element("\n                                                            <div\n                                                                class=\"a-color-picker-palette-color color-picker-palette-color-remove-from-palette\"></div>\n                                                                ");
            removeColorFromMemory.on('click', function (e) {
              e.stopPropagation();
              if (proto.settings.value) {
                var color = proto.settings.value;
                mw.top().app.templateSettings.colorPaletteManager.removeFromPaletteMemory(color);
                if (proto.settings.onchange) {
                  proto.settings.onchange('revert-layer');
                }
              }
            });
            frameEl.append(removeColorFromMemory.get(0));
          }
        }
      }
    }
    if (this.tip) {
      this.show = function () {
        mw.$(this.tip).show();
        mw.tools.tooltip.setPosition(this.tip, this.settings.element, this.settings.position);
      };
      this.hide = function () {
        mw.$(this.tip).hide();
      };
      this.toggle = function () {
        var tip = mw.$(this.tip);
        if (tip.is(':visible')) {
          this.hide();
        } else {
          $el.focus();
          this.show();
        }
      };
    }
    var prev = $el[0].previousElementSibling;
    if (prev && prev.classList.contains('mw-field-color-indicator')) {
      var dp = prev.querySelector('.mw-field-color-indicator-display');
      if (dp) {
        dp.style.backgroundColor = $el[0].value;
      }
    }
  };
  mw.colorPicker = function (o) {
    return new mw._colorPicker(o);
  };
  $(document).ready(function () {
    $(document).on('click', function (e) {
      if (!mw.tools.hasParentsWithClass(e.target, 'a-color-picker') && !mw.tools.hasClass(e.target, 'mw-color-picker-field')) {
        colorPickers.forEach(function (clpckr) {
          mw.$(clpckr).hide();
        });
      }
    });
  });
})();

/***/ }),

/***/ "./resources/assets/components/components.js":
/*!***************************************************!*\
  !*** ./resources/assets/components/components.js ***!
  \***************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
mw.components = {
  _rangeOnce: false,
  'range': function range(el) {
    var options = this._options(el);
    var defaults = {
      range: 'min',
      animate: "fast"
    };
    var ex = {},
      render = el;
    if (el.nodeName === 'INPUT') {
      el._pauseChange = false;
      el.type = 'text';
      render = document.createElement('div');
      $(el).removeClass('mw-range');
      $(render).addClass('mw-range');
      $(el).after(render);
      ex = {
        slide: function slide(event, ui) {
          el._pauseChange = true;
          $(el).val(ui.value).trigger('change').trigger('input');
          setTimeout(function () {
            el._pauseChange = false;
          }, 78);
        }
      };
    }
    var settings = $.extend({}, defaults, options, ex);
    if (el.min) {
      settings.min = parseFloat(el.min);
    }
    if (el.max) {
      settings.max = parseFloat(el.max);
    }
    if (el.value) {
      settings.value = parseFloat(el.value);
    }
    mw.$(render).slider(settings).on('mousedown touchstart', function () {
      mw.$(this).addClass('active');
    });
    $(el).on('input', function () {
      mw.$(render).slider("value", this.value);
    });
    if (!mw.components._rangeOnce) {
      mw.components._rangeOnce = true;
      mw.$(document.body).on('mouseup touchend', function () {
        mw.$('.mw-range.active').removeClass('active');
      });
    }
  },
  'color-picker': function colorPicker(el) {
    var options = this._options(el);
    var defaults = {
      position: 'bottom-center'
    };
    var settings = $.extend({}, defaults, options);
    var nav = document.createElement('div');
    nav.className = 'mw-ui-btn-nav mw-color-picker-holder';
    var view = document.createElement('div');
    view.className = 'mw-ui-btn';
    view.innerHTML = '<span class="mw-ui-btn-img"></span>';
    nav.appendChild(view);
    var inputEl;
    if (el.nodeName === 'INPUT') {
      inputEl = el;
      mw.$(el).addClass('mw-ui-field').after(nav);
      nav.appendChild(el);
      mw.$('.mw-ui-btn-img', view).css("background-color", el.value);
      inputEl._time = null;
    }
    var picker = mw.colorPicker({
      element: inputEl,
      position: settings.position,
      onchange: function onchange(color) {
        mw.$('.mw-ui-btn-img', view).css("background-color", color);
        mw.$(inputEl).trigger('change');
      }
    });
    mw.$(view).on("click", function () {
      setTimeout(function () {
        picker.toggle();
      }, 10);
    });
  },
  'file-uploader': function fileUploader(el) {
    var options = this._options(el);
    var defaults = {
      element: el
    };
    var settings = $.extend({}, defaults, options);
    var ch = mw.$(el).attr("onchange");
    mw.fileWindow({
      types: 'media',
      change: function change(url) {
        try {
          eval(ch);
        } catch (err) {}
      }
    });
  },
  'modules-tabs': function modulesTabs(el) {
    var options = this._options(el);
    options.breakPoint = 100; //makes accordion if less then 100px
    if (window.live_edit_sidebar) {
      mw.$(el).addClass('mw-accordion-window-height');
      options.breakPoint = 800; //makes accordion if less then 800px
    }
    var accordion = this.accordion(el);
    var tb = new mw.tabAccordion(options, accordion);
  },
  'tab-accordion': function tabAccordion(el) {
    var options = this._options(el);
    var accordion = this.accordion(el);
    var tb = new mw.tabAccordion(options, accordion);
  },
  accordion: function accordion(el) {
    if (!el || el._accordion) return;
    if (!$(el).is(':visible')) {
      setTimeout(function () {
        mw.components.accordion(el);
      }, 777, el);
      return;
    }
    el._accordion = true;
    var options = this._options(el);
    var settings = $.extend(options, {
      element: el
    });
    var accordion = new mw.uiAccordion(settings);
    if ($(el).hasClass('mw-accordion-window-height')) {
      accordion._setHeight = function () {
        var max = mw.$(window).height() - (accordion.root.offset().top - mw.$(window).scrollTop());
        accordion.root.css('height', max);
        var content_max = max - accordion.titles.length * accordion.titles.eq(0).outerHeight();
        accordion.contents.css('height', content_max);
      };
      accordion._setHeight();
      mw.$(window).on('load resize', function () {
        accordion._setHeight();
      });
      if (window !== top) {
        mw.$(top).on('load resize', function () {
          accordion._setHeight();
        });
      }
    }
    if ($(el).hasClass('mw-accordion-full-height')) {
      accordion._setHeight = function () {
        var max = Math.min($(el).parent().height(), mw.$(window).height());
        accordion.root.css('maxHeight', max);
        var content_max = max - accordion.titles.length * accordion.titles.eq(0).outerHeight();
        accordion.contents.css('maxHeight', content_max);
      };
      accordion._setHeight();
      mw.$(window).on('load resize', function () {
        accordion._setHeight();
      });
      if (window !== top) {
        mw.$(top).on('load resize', function () {
          accordion._setHeight();
        });
      }
    }
    return accordion;
  },
  postSearch: function postSearch(el) {
    var defaults = {
      keyword: el.value,
      limit: 4
    };
    el._setValue = function (id) {
      mw.tools.ajaxSearch(this._settings, function () {});
    };
    el = mw.$(el);
    var options = JSON.parse(el.attr("data-options") || '{}');
    settings = $.extend({}, defaults, options);
    el[0]._settings = settings;
    el.wrap("<div class='mw-component-post-search'></div>");
    el.after("<ul></ul>");
    el.on("input focus blur", function (event) {
      if (!el[0].is_searching) {
        var val = el.val();
        if (event.type == 'blur') {
          mw.$(this).next('ul').hide();
          return false;
        }
        if (event.type == 'focus') {
          if ($(this).next('ul').html()) {
            mw.$(this).next('ul').show();
          }
          return false;
        }
        el[0].is_searching = true;
        this._settings.keyword = this.value;
        mw.$('ul', el).empty("");
        el.parent().addClass("loading");
        mw.tools.ajaxSearch(this._settings, function () {
          var lis = [];
          var json = this;
          for (var item in json) {
            var obj = json[item];
            if (_typeof(obj) === 'object') {
              var li = document.createElement("li");
              li._value = obj;
              li.innerHTML = obj.title;
              mw.$(li).on("mousedown touchstart", function () {
                el.val(this._value.title);
                el[0]._value = this._value;
                el.trigger('postSelected', [this._value]);
                mw.$(this.parentNode).hide();
              });
              lis.push(li);
            }
          }
          el.parent().removeClass("loading");
          var ul = el.parent().find("ul");
          ul.empty().append(lis).show();
          el[0].is_searching = false;
        });
      }
    });
    el.trigger("postSearchReady");
  },
  _options: function _options(el) {
    return mw.tools.elementOptions(el);
  },
  _init: function _init() {
    mw.$('.mw-field input[type="range"]').addClass('mw-range');
    mw.$('[data-mwcomponent], [data-mw-component]').each(function () {
      var component = this.dataset.mwComponent || this.dataset.mwcomponent;
      if (mw.components[component]) {
        mw.components[component](this);
        mw.$(this).removeAttr('data-mwcomponent').removeAttr('data-mw-component');
      }
    });
    $.each(this, function (key) {
      if (key.indexOf('_') === -1) {
        mw.$('.mw-' + key + ', mw-' + key).not(".mw-component-ready").each(function () {
          mw.$(this).addClass('mw-component-ready');
          mw.components[key](this);
        });
      }
    });
  }
};
/*

$(document).ready(function () {
    mw.components._init();
});

$(window).on('load', function () {
    mw.components._init();
});

    mw.on('ComponentsLaunch', function () {
        mw.components._init();
    });

    mw.on('mwDialogShow', function () {
        setTimeout(function () {
            mw.components._init();
        }, 110);
    });

$(window).on('ajaxStop', function () {
    setTimeout(function () {
        mw.components._init();
    }, 100);
});*/

mw.registerComponent = function (name, func) {
  if (mw.components[name]) {
    console.warn('Component ' + name + ' already exists.');
    return;
  }
  mw.components[name] = func;
};

/***/ }),

/***/ "./resources/assets/components/dialog.js":
/*!***********************************************!*\
  !*** ./resources/assets/components/dialog.js ***!
  \***********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
(function (mw) {
  mw.dialog = function (options) {
    return new mw.Dialog(options);
  };
  mw.dialogIframe = function (options, cres) {
    options.pauseInit = true;
    var attr = 'frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture; display-capture" allowfullscreen';
    if (options.autoHeight) {
      // attr += ' scrolling="no"';
      options.height = 'auto';
    }
    options.content = '<iframe src="' + mw.external_tool(options.url.trim()) + '" ' + attr + '></iframe>';
    options.className = ('mw-dialog-iframe mw-dialog-iframe-loading ' + (options.className || '')).trim();
    options.className += options.autoHeight ? ' mw-dialog-iframe-autoheight' : '';
    var dialog = new mw.Dialog(options, cres);
    dialog.iframe = dialog.dialogContainer.querySelector('iframe');
    mw.tools.loading(dialog.dialogContainer, 90);
    dialog.dialogContainer.style.minHeight = '100px';
    mw.spinner({
      element: dialog.dialogContainer,
      size: 32,
      decorate: true
    }).show();
    if (options && options.id) {
      dialog.iframe.id = options.id + '-content-window';
    }
    var maxHeight = 'calc(100vh - 140px)';
    setTimeout(function () {
      var frame = dialog.dialogContainer.querySelector('iframe');
      frame.style.minHeight = 0; // reset in case of conflicts
      frame.loading = 'lazy';
      if (options.autoHeight) {
        mw.tools.iframeAutoHeight(frame, {
          dialog: dialog,
          maxHeightWindowScroll: maxHeight
        });
      } else {
        $(frame).height(options.height - 60);
        frame.style.position = 'relative';
        frame.style.maxHeight = maxHeight;
      }
      mw.$(frame).on('load', function () {
        mw.tools.loading(dialog.dialogContainer, false);
        mw.spinner({
          element: dialog.dialogContainer,
          size: 32,
          decorate: true
        }).remove();
        setTimeout(function () {
          dialog.center();
          mw.$(frame).on('bodyResize', function () {
            dialog.center();
          });
          dialog.dialogMain.classList.remove('mw-dialog-iframe-loading');
          if (mw.tools.canAccessIFrame(frame)) {
            if (frame && frame.contentWindow && frame.contentWindow.thismodal) {
              frame.contentWindow.thismodal = dialog;
              if (options.autoHeight) {
                mw.tools.iframeAutoHeight(frame, {
                  dialog: dialog,
                  maxHeightWindowScroll: maxHeight
                });
              }
            }
          }
        }, 78);
        if (mw.tools.canAccessIFrame(frame)) {
          mw.$(frame.contentWindow.document).on('keydown', function (e) {
            if (mw.event.is.escape(e) && !mw.event.targetIsField(e)) {
              if (mw.top().__dialogs && mw.top().__dialogs.length) {
                var dlg = mw.top().__dialogs;
                dlg[dlg.length - 1]._doCloseButton();
                $(dlg[dlg.length - 1]).trigger('closedByUser');
              } else {
                if (dialog.options.closeOnEscape) {
                  dialog._doCloseButton();
                  $(dialog).trigger('closedByUser');
                }
              }
            }
          });
        }
        if (typeof options.onload === 'function') {
          options.onload.call(dialog);
        }
      });
    }, 12);
    return dialog;
  };

  /** @deprecated */
  mw.modal = mw.dialog;
  mw.modalFrame = mw.dialogIframe;
  mw.dialog.remove = function (selector) {
    var dlg = mw.dialog.get(selector);
    if (dlg) {
      dlg.remove();
    }
  };
  mw.dialog.get = function (selector) {
    selector = selector || '.mw-dialog';
    var $el = mw.$(selector);
    var el = $el[0];
    if (!el) return false;
    if (el._dialog) {
      return el._dialog;
    }
    var child_cont = el.querySelector('.mw-dialog-holder');
    var parent_cont = $el.parents(".mw-dialog-holder:first");
    if (child_cont) {
      return child_cont._dialog;
    } else if (parent_cont.length !== 0) {
      return parent_cont[0]._dialog;
    } else if (window.thismodal) {
      return thismodal;
    } else {
      // deprecated
      child_cont = el.querySelector('.mw_modal');
      parent_cont = $el.parents(".mw_modal:first");
      if (child_cont) {
        return child_cont.modal;
      } else if (parent_cont.length !== 0) {
        return parent_cont[0].modal;
      }
      return false;
    }
  };
  mw.Dialog = function (options, cres) {
    var scope = this;
    options = options || {};
    options.content = options.content || options.html || '';
    if (!options.height && typeof options.autoHeight === 'undefined') {
      options.height = 'auto';
      options.autoHeight = true;
    }
    var defaults = {
      skin: 'default',
      overlay: true,
      overlayClose: false,
      autoCenter: true,
      root: document,
      id: options.name || options.id || mw.id('mw-dialog-'),
      content: '',
      closeOnEscape: true,
      closeButton: true,
      closeButtonAppendTo: '.mw-dialog-header',
      closeButtonAction: 'remove',
      // 'remove' | 'hide'
      draggable: true,
      scrollMode: 'inside',
      // 'inside' | 'window',
      centerMode: 'intuitive',
      // 'intuitive' | 'center'
      containment: 'window',
      overflowMode: 'auto',
      // 'auto' | 'hidden' | 'visible'
      disableTextSelection: false
    };
    this.options = $.extend({}, defaults, options, {
      // skin: 'default'
    });
    this.id = this.options.id;
    var exist = document.getElementById(this.id);
    if (exist) {
      return exist._dialog;
    }
    this.hasBeenCreated = function () {
      return this.options.root.getElementById(this.id) !== null;
    };
    if (this.hasBeenCreated()) {
      return this.options.root.getElementById(this.id)._dialog;
    }
    if (!mw.top().__dialogs) {
      mw.top().__dialogs = [];
    }
    if (!mw.top().__dialogsData) {
      mw.top().__dialogsData = {};
    }
    if (!mw.top().__dialogsData._esc) {
      mw.top().__dialogsData._esc = true;
      mw.$(document).on('keydown', function (e) {
        if (mw.event.is.escape(e)) {
          var dlg = mw.top().__dialogs[mw.top().__dialogs.length - 1];
          if (dlg && dlg.options && dlg.options.closeOnEscape) {
            dlg._doCloseButton();
          }
        }
      });
    }
    mw.top().__dialogs.push(this);
    this.draggable = function () {
      if (this.options.draggable && $.fn.draggable) {
        var $holder = mw.$(this.dialogHolder);
        $holder.draggable({
          handle: this.options.draggableHandle || '.mw-dialog-header',
          start: function start() {
            $holder.addClass('mw-dialog-drag-start');
            scope._dragged = true;
          },
          stop: function stop() {
            $holder.removeClass('mw-dialog-drag-start');
          },
          containment: scope.options.containment,
          scroll: false,
          iframeFix: true
        });
      }
    };
    this.header = function () {
      this.dialogHeader = this.options.root.createElement('div');
      this.dialogHeader.className = 'mw-dialog-header';
      if (this.options.title || this.options.header) {
        this.dialogHeader.innerHTML = '<div class="modal-title settings-title-inside">' + (this.options.title || this.options.header) + '</div>';
      }
    };
    this.footer = function (content) {
      this.dialogFooter = this.options.root.createElement('div');
      this.dialogFooter.className = 'mw-dialog-footer';
      if (this.options.footer) {
        $(this.dialogFooter).append(this.options.footer);
      }
    };
    this.title = function (title) {
      var root = mw.$('.mw-dialog-title', this.dialogHeader);
      if (typeof title === 'undefined') {
        return root.html();
      } else {
        if (root[0]) {
          root.html(title);
        } else {
          mw.$(this.dialogHeader).prepend('<div class="modal-title settings-title-inside">' + title + '</div>');
        }
      }
    };
    this.build = function () {
      this.dialogMain = this.options.root.createElement('div');
      if (this.options.disableTextSelection) {
        this.dialogMain.style.userSelect = 'none';
      }
      this.dialogMain.id = this.id;
      var cls = 'mw-dialog mw-dialog-scroll-mode-' + this.options.scrollMode + ' mw-dialog-skin-' + this.options.skin + ' mw-dialog-overflowMode-' + this.options.overflowMode;
      cls += !this.options.className ? '' : ' ' + this.options.className;
      this.dialogMain.className = cls;
      this.dialogMain._dialog = this;
      this.dialogHolder = this.options.root.createElement('div');
      this.dialogHolder.id = 'mw-dialog-holder-' + this.id;
      this.dialogHolder._dialog = this;
      this.header();
      this.footer();
      this.draggable();
      this.dialogContainer = this.options.root.createElement('div');
      this.dialogContainer._dialog = this;

      // TODO: obsolate
      this.container = this.dialogContainer;
      this.dialogContainer.className = 'mw-dialog-container';
      this.dialogHolder.className = 'mw-dialog-holder';
      this.dialogContainer.setAttribute('x-data', '{}');
      this.dialogContainer.setAttribute('x-trap', 'true');
      var cont = this.options.content;
      if (this.options.shadow) {
        this.shadow = this.dialogContainer.attachShadow({
          mode: 'open'
        });
        if (typeof cont === 'string') {
          this.shadow.innerHTML = cont;
        } else {
          this.shadow.appendChild(cont);
        }
      } else {
        mw.$(this.dialogContainer).append(cont);
      }
      if (this.options.encapsulate) {
        this.iframe = cont;
        this.iframe.style.display = '';
      }
      this.dialogHolder.appendChild(this.dialogHeader);
      this.dialogHolder.appendChild(this.dialogContainer);
      this.dialogHolder.appendChild(this.dialogFooter);
      this.closeButton = this.options.root.createElement('div');
      this.closeButton.className = 'mw-dialog-close';
      this.closeButton.$scope = this;
      this.closeButton.onclick = function (e) {
        e.preventDefault();
        this.$scope[this.$scope.options.closeButtonAction]();
        $(this.$scope).trigger('closedByUser');
      };
      this.main = mw.$(this.dialogContainer); // obsolete
      this.main.width = this.width;
      this.width(this.options.width || 600);
      this.height(this.options.height || 320);
      this.options.root.body.appendChild(this.dialogMain);
      this.dialogMain.appendChild(this.dialogHolder);
      if (this.options.closeButtonAppendTo) {
        if (this.options.closeButton !== false) {
          mw.$(this.options.closeButtonAppendTo, this.dialogMain).append(this.closeButton);
        }
      } else {
        if (this.options.closeButton !== false) {
          this.dialogHolder.appendChild(this.closeButton);
        }
      }
      this.dialogOverlay();
      return this;
    };
    this._doCloseButton = function () {
      this[this.options.closeButtonAction]();
    };
    this.containmentManage = function () {
      if (scope.options.containment === 'window') {
        if (scope.options.scrollMode === 'inside') {
          var rect = this.dialogHolder.getBoundingClientRect();
          var $win = mw.$(window);
          var sctop = $win.scrollTop();
          var height = $win.height();
          if (rect.top < sctop || sctop + height > rect.top + rect.height) {
            this.center();
          }
        }
      }
    };
    this.dialogOverlay = function () {
      this.overlay = this.options.root.createElement('div');
      this.overlay.className = 'mw-dialog-overlay';
      this.overlay.$scope = this;
      if (this.options.overlay === true || typeof this.options.overlay === 'string') {
        this.dialogMain.appendChild(this.overlay);
        if (typeof this.options.overlay === 'string') {
          this.overlay.style.backgroundColor = this.options.overlay;
        }
      }
      mw.$(this.overlay).on('click', function () {
        if (typeof this.$scope.options.overlayClose === 'function') {
          if (this.$scope.options.overlayClose() === true) {
            this.$scope._doCloseButton();
            $(this.$scope).trigger('closedByUser');
          }
        } else if (this.$scope.options.overlayClose === true) {
          this.$scope._doCloseButton();
          $(this.$scope).trigger('closedByUser');
        }
      });
      return this;
    };
    this._afterSize = function () {
      if (mw._iframeDetector) {
        mw._iframeDetector.pause = true;
        var frame = window.frameElement;
        if (frame && parent !== top) {
          var height = this.dialogContainer.scrollHeight + this.dialogHeader.scrollHeight;
          if ($(frame).height() < height) {
            frame.style.height = height + 100 - this.dialogHeader.offsetHeight - this.dialogFooter.offsetHeight + 'px';
            if (window.thismodal) {
              thismodal.height(height + 100);
            }
          }
        }
      }
    };
    this.show = function () {
      mw.$(this.dialogMain).find('iframe').each(function () {
        this._intPause = false;
      });
      mw.$(this.dialogMain).addClass('active');
      this.center();
      this._afterSize();
      mw.$(this).trigger('Show');
      mw.trigger('mwDialogShow', this);
      return this;
    };
    this._hideStart = false;
    this.hide = function () {
      if (!this._hideStart) {
        this._hideStart = true;
        mw.$(this.dialogMain).find('iframe').each(function () {
          this._intPause = true;
        });
        setTimeout(function () {
          scope._hideStart = false;
        }, 300);
        mw.$(this.dialogMain).removeClass('active');
        if (mw._iframeDetector) {
          mw._iframeDetector.pause = false;
        }
        mw.$(this).trigger('Hide');
        mw.trigger('mwDialogHide', this);
      }
      return this;
    };
    this.forceRemove = function () {
      mw.$(this.dialogMain).remove();
      for (var i = 0; i < mw.top().__dialogs.length; i++) {
        if (mw.top().__dialogs[i] === this) {
          mw.top().__dialogs.splice(i, 1);
          break;
        }
      }
      clearInterval(this._observe.interval);
      return this;
    };
    this.remove = function () {
      mw.$(this).trigger('BeforeRemove');
      if (typeof this.options.beforeRemove === 'function') {
        if (this.options.beforeRemove(this) === false) {
          return;
        }
      }
      this.hide();
      mw.removeInterval('iframe-' + this.id);
      if (this.options.onremove) {
        this.options.onremove();
      }
      mw.$(this).trigger('Remove');
      mw.trigger('mwDialogRemove', this);
      this.forceRemove();
    };
    this.destroy = this.remove;
    this._prevHeight = -1;
    this._dragged = false;
    this.center = function (width, height) {
      var $holder = mw.$(this.dialogHolder),
        $window = mw.$(window);
      var holderHeight = height || $holder.outerHeight();
      var holderWidth = width || $holder.outerWidth();
      var dtop,
        css = {};
      if (this.options.centerMode === 'intuitive' && this._prevHeight < holderHeight) {
        dtop = $window.height() / 2 - holderHeight / 2;
      } else if (this.options.centerMode === 'center') {
        dtop = $window.height() / 2 - holderHeight / 2;
      }
      if (!scope._dragged) {
        css.left = $window.outerWidth() / 2 - holderWidth / 2;
      } else {
        css.left = parseFloat($holder.css('left'));
      }
      if (css.left + holderWidth > $window.width()) {
        css.left = css.left - (css.left + holderWidth - $window.width());
      }
      if (dtop) {
        css.top = dtop > 0 ? dtop : 0;
      }

      /*if(window !== mw.top().win && document.body.scrollHeight > mw.top().win.innerHeight){
          $win = $(mw.top());
            css.top = $(document).scrollTop() + 50;
          var off = $(window.frameElement).offset();
          if(off.top < 0) {
              css.top += Math.abs(off.top);
          }
          if(window.thismodal) {
              css.top += thismodal.dialogContainer.scrollTop;
          }
        }*/

      $holder.css(css);
      this._prevHeight = holderHeight;
      this._afterSize();
      mw.$(this).trigger('dialogCenter');
      return this;
    };
    this.width = function (width) {
      if (!width) {
        return mw.$(this.dialogHolder).outerWidth();
      }
      mw.$(this.dialogHolder).width(width);
      this._afterSize();
    };
    this.height = function (height) {
      if (!height) {
        return mw.$(this.dialogHolder).outerHeight();
      }
      mw.$(this.dialogHolder).height(height);
      this._afterSize();
    };
    this.resize = function (width, height) {
      if (typeof width !== 'undefined') {
        this.width(width);
      }
      if (typeof height !== 'undefined') {
        this.height(height);
      }
      this.center(width, height);
    };
    this.content = function (content) {
      this.options.content = content || '';
      $(this.dialogContainer).empty().append(this.options.content);
      return this;
    };
    this.result = function (result, doClose) {
      this.value = result;
      if (this.options.onResult) {
        this.options.onResult.call(this, result);
      }
      if (cres) {
        cres.call(this, result);
      }
      $(this).trigger('Result', [result]);
      if (doClose) {
        this._doCloseButton();
      }
    };
    this.contentMaxHeight = function () {
      var scope = this;
      if (this.options.scrollMode === 'inside') {
        mw.interval('iframe-' + this.id, function () {
          var max = mw.$(window).height() - scope.dialogHeader.clientHeight - scope.dialogFooter.clientHeight - 40;
          scope.dialogContainer.style.maxHeight = max + 'px';
          scope.dialogContainer.style.maxHeight = 'calc(100vh - 100px)';
          scope.containmentManage();
        });
      }
    };
    this._observe = {};
    this.observeDimensions = function (cb) {
      if (!this._observe.interval) {
        var changed = function changed() {
          var css = getComputedStyle(scope.dialogMain);
          if (!scope._observe.data) {
            scope._observe.data = {
              width: css.width,
              height: css.height
            };
            return {
              width: css.width,
              height: css.height
            };
          } else {
            var curr = scope._observe.data;
            // if(curr.width !== css.width || curr.height !== css.height) {
            scope._observe.data = {
              width: css.width,
              height: css.height
            };
            return {
              width: css.width,
              height: css.height
            };
            // }
          }
        };
        this._observe.interval = setInterval(function () {
          var chg = changed();
          if (chg) {
            cb.call(scope, chg);
          }
        }, 333);
      }
    };
    this.position = function (x, y) {
      if (!x) {
        return;
      }
      scope._dragged = true;
      if (_typeof(x) === 'object') {
        y = x.y;
        x = x.x;
      }
      if (typeof y === 'undefined') {
        y = x;
      }
      if (typeof x === 'number') {
        x += 'px';
      }
      if (typeof y === 'number') {
        y += 'px';
      }
      this.dialogHolder.style.left = x;
      this.dialogHolder.style.top = y;
      return this;
    };
    this.positionToElement = function (targetElementSelector) {
      var _this = this;
      if (!targetElementSelector) {
        return;
      }
      var node = $(targetElementSelector)[0];
      var nodeWindow = node.ownerDocument.defaultView;
      var off = mw.element(node).offset();
      if (nodeWindow.frameElement) {
        var winframe = mw.top().element(nodeWindow.frameElement).offset();
        off.offsetTop += winframe.offsetTop;
        off.offsetLeft += winframe.offsetLeft;
      }
      this.position(off.offsetLeft, off.offsetTop + off.height);
      setTimeout(function () {
        var colorPickerDialogOff = mw.element(_this.dialogHolder).offset();
        if (colorPickerDialogOff.offsetTop + colorPickerDialogOff.height > mw.top().win.innerHeight) {
          _this.position(off.offsetLeft - colorPickerDialogOff.width, mw.top().win.innerHeight - colorPickerDialogOff.height);
        }
      }, 10);
    };
    this.calculateDialogPositionXY = function (element) {
      var position = {};
      if (self !== top) {
        position = this.getElementPositionInFrames(element);
      } else {
        position = this.getElementPositionOnScreen(element);
      }
      var newPositionX = position.x;
      var newPositionY = position.y;
      var elementBounding = element.getBoundingClientRect();
      var dialogBounding = this.dialogMain.getBoundingClientRect();
      var repositionDialog = this.positionDialogWithoutOverlap(this.dialogMain, element);
      if (repositionDialog) {
        newPositionX = repositionDialog.x;
        newPositionY = repositionDialog.y;
      }

      // newPositionX = (newPositionX + elementBounding.width) - 50;

      // if ((window.top.innerWidth - newPositionX) < (dialogBounding.width - 50)) {
      //     newPositionX = newPositionX - dialogBounding.width - (window.top.innerWidth - newPositionX);
      // }
      //
      // if ((window.top.innerHeight - newPositionY) < (dialogBounding.height - 50)) {
      //     newPositionY = newPositionY - dialogBounding.height + (window.top.innerHeight - newPositionY);
      // }

      if (newPositionX < 0) {
        newPositionX = 0;
      }
      if (newPositionY < 0) {
        newPositionY = 0;
      }
      console.log('innerWidth', elementBounding.width);
      console.log('innerHeight', elementBounding.height);
      console.log('newPositionX', newPositionX);
      console.log('newPositionY', newPositionY);
      this.options.position = {
        x: newPositionX,
        y: newPositionY
      };
      this.position(newPositionX, newPositionY);
    };
    this.positionDialogWithoutOverlap = function (dialogElement, targetElement) {
      var dialogRect = dialogElement.getBoundingClientRect();
      var targetRect = targetElement.getBoundingClientRect();
      var dialogWidth = dialogRect.width;
      var dialogHeight = dialogRect.height;
      var targetLeft = targetRect.left;
      var targetTop = targetRect.top;
      var targetWidth = targetRect.width;
      var targetHeight = targetRect.height;
      var currentWindow = window;
      while (currentWindow !== top) {
        var iframe = currentWindow.frameElement;
        var iframeRect = iframe.getBoundingClientRect();
        targetLeft += iframeRect.left;
        targetTop += iframeRect.top;
        currentWindow = currentWindow.parent;
      }
      var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
      var screenHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
      var spaceRight = screenWidth - targetLeft - targetWidth;
      var spaceBelow = screenHeight - targetTop - targetHeight;
      var dialogLeft, dialogTop;
      if (spaceRight >= dialogWidth) {
        dialogLeft = targetLeft + targetWidth;
        dialogTop = Math.max(targetTop + (targetHeight - dialogHeight) / 2, 0);
      } else if (spaceBelow >= dialogHeight) {
        dialogLeft = targetLeft + (targetWidth - dialogWidth) / 2;
        dialogTop = targetTop + targetHeight;
      } else {
        // Fallback position if there is not enough space on the right or below
        dialogLeft = targetLeft + targetWidth;
        dialogTop = Math.max(targetTop + (targetHeight - dialogHeight) / 2, 0);
      }
      return {
        x: dialogLeft,
        y: dialogTop
      };
    };
    this.getElementPositionInFrames = function (element) {
      var x = 0;
      var y = 0;
      var currentWindow = window;
      while (currentWindow !== top) {
        var iframe = currentWindow.frameElement;
        var iframeRect = iframe.getBoundingClientRect();
        var elementRect = element.getBoundingClientRect();
        x += iframeRect.left + elementRect.left;
        y += iframeRect.top + elementRect.top;
        currentWindow = currentWindow.parent;
      }
      return {
        x: x,
        y: y
      };
    };
    this.getElementPositionOnScreen = function (element) {
      var rect = element.getBoundingClientRect();
      var scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
      var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      var x = rect.left + scrollLeft;
      var y = rect.top + scrollTop;

      // Adjust position to stay within the visible screen area
      var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
      var screenHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
      x = Math.min(Math.max(x, 0), screenWidth - element.offsetWidth);
      y = Math.min(Math.max(y, 0), screenHeight - element.offsetHeight);
      return {
        x: x,
        y: y
      };
    };
    this.init = function () {
      this.build();
      this.contentMaxHeight();
      if (!this.options.position) {
        this.center();
      }
      this.show();
      if (this.options.autoCenter) {
        (function (scope) {
          mw.$(window).on('resize orientationchange load', function () {
            scope.contentMaxHeight();
            scope.center();
          });
        })(this);
      }
      if (!this.options.pauseInit) {
        mw.$(this).trigger('Init');
      }
      this.observeDimensions(function () {
        scope.center();
      });
      if (this.options.position) {
        this.position(this.options.position);
      }
      return this;
    };
    this.init();
  };
  mw.Dialog.elementIsInDialog = function (node) {
    return mw.tools.firstParentWithClass(node, 'mw-dialog');
  };
})(window.mw);
(function () {
  function scoped() {
    var all = document.querySelectorAll('style[scoped]'),
      i = 0;
    try {
      for (; i < all.length; i++) {
        var parent = all[i].parentNode;
        parent.id = parent.id || mw.id('scoped-id-');
        var prefix = '#' + parent.id + ' ';
        var rules = all[i].sheet.rules;
        var r = 0;
        for (; r < rules.length; r++) {
          var newRule = prefix + rules[r].cssText;
          all[i].sheet.deleteRule(r);
          all[i].sheet.insertRule(newRule, r);
        }
        all[i].removeAttribute('scoped');
      }
    } catch (error) {}
  }
  scoped();
  $(window).on('load', function () {
    scoped();
  });
})();

/***/ }),

/***/ "./resources/assets/components/icon_selector.js":
/*!******************************************************!*\
  !*** ./resources/assets/components/icon_selector.js ***!
  \******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
(function () {
  var IconLoader = function IconLoader(store) {
    var scope = this;
    var defaultVersion = '-1';
    var common = {
      'fontAwesome': {
        cssSelector: '.fa',
        detect: function detect(target) {
          return target.classList.includes('fa');
        },
        render: function render(icon, target) {
          target.classList.add('fa');
          target.classList.add(icon);
        },
        remove: function remove(target) {
          target.classList.remove('fa');
          var exception = ['fa-lg', 'fa-2x', 'fa-3x', 'fa-4x', 'fa-5x', 'fa-fw', 'fa-spin', 'fa-pule', 'fa-rotate-90', 'fa-rotate-180', 'fa-rotate-270', 'fa-flip-horizontal', 'fa-flip-vertical'];
          mw.tools.classNamespaceDelete(target, 'fa-', undefined, undefined, exception);
        },
        icons: function icons() {
          return new Promise(function (resolve) {
            var icons = ['glass', 'music', 'search', 'envelope-o', 'heart', 'star', 'star-o', 'user', 'film', 'th-large', 'th', 'th-list', 'check', 'times', 'search-plus', 'search-minus', 'power-off', 'signal', 'cog', 'trash-o', 'home', 'file-o', 'clock-o', 'road', 'download', 'arrow-circle-o-down', 'arrow-circle-o-up', 'inbox', 'play-circle-o', 'repeat', 'refresh', 'list-alt', 'lock', 'flag', 'headphones', 'volume-off', 'volume-down', 'volume-up', 'qrcode', 'barcode', 'tag', 'tags', 'book', 'bookmark', 'print', 'camera', 'font', 'bold', 'italic', 'text-height', 'text-width', 'align-left', 'align-center', 'align-right', 'align-justify', 'list', 'outdent', 'indent', 'video-camera', 'picture-o', 'pencil', 'map-marker', 'adjust', 'tint', 'pencil-square-o', 'share-square-o', 'check-square-o', 'arrows', 'step-backward', 'fast-backward', 'backward', 'play', 'pause', 'stop', 'forward', 'fast-forward', 'step-forward', 'eject', 'chevron-left', 'chevron-right', 'plus-circle', 'minus-circle', 'times-circle', 'check-circle', 'question-circle', 'info-circle', 'crosshairs', 'times-circle-o', 'check-circle-o', 'ban', 'arrow-left', 'arrow-right', 'arrow-up', 'arrow-down', 'share', 'expand', 'compress', 'plus', 'minus', 'asterisk', 'exclamation-circle', 'gift', 'leaf', 'fire', 'eye', 'eye-slash', 'exclamation-triangle', 'plane', 'calendar', 'random', 'comment', 'magnet', 'chevron-up', 'chevron-down', 'retweet', 'shopping-cart', 'folder', 'folder-open', 'arrows-v', 'arrows-h', 'bar-chart', 'twitter-square', 'facebook-square', 'camera-retro', 'key', 'cogs', 'comments', 'thumbs-o-up', 'thumbs-o-down', 'star-half', 'heart-o', 'sign-out', 'linkedin-square', 'thumb-tack', 'external-link', 'sign-in', 'trophy', 'github-square', 'upload', 'lemon-o', 'phone', 'square-o', 'bookmark-o', 'phone-square', 'twitter', 'facebook', 'github', 'unlock', 'credit-card', 'rss', 'hdd-o', 'bullhorn', 'bell', 'certificate', 'hand-o-right', 'hand-o-left', 'hand-o-up', 'hand-o-down', 'arrow-circle-left', 'arrow-circle-right', 'arrow-circle-up', 'arrow-circle-down', 'globe', 'wrench', 'tasks', 'filter', 'briefcase', 'arrows-alt', 'users', 'link', 'cloud', 'flask', 'scissors', 'files-o', 'paperclip', 'floppy-o', 'square', 'bars', 'list-ul', 'list-ol', 'strikethrough', 'underline', 'table', 'magic', 'truck', 'pinterest', 'pinterest-square', 'google-plus-square', 'google-plus', 'money', 'caret-down', 'caret-up', 'caret-left', 'caret-right', 'columns', 'sort', 'sort-desc', 'sort-asc', 'envelope', 'linkedin', 'undo', 'gavel', 'tachometer', 'comment-o', 'comments-o', 'bolt', 'sitemap', 'umbrella', 'clipboard', 'lightbulb-o', 'exchange', 'cloud-download', 'cloud-upload', 'user-md', 'stethoscope', 'suitcase', 'bell-o', 'coffee', 'cutlery', 'file-text-o', 'building-o', 'hospital-o', 'ambulance', 'medkit', 'fighter-jet', 'beer', 'h-square', 'plus-square', 'angle-double-left', 'angle-double-right', 'angle-double-up', 'angle-double-down', 'angle-left', 'angle-right', 'angle-up', 'angle-down', 'desktop', 'laptop', 'tablet', 'mobile', 'circle-o', 'quote-left', 'quote-right', 'spinner', 'circle', 'reply', 'github-alt', 'folder-o', 'folder-open-o', 'smile-o', 'frown-o', 'meh-o', 'gamepad', 'keyboard-o', 'flag-o', 'flag-checkered', 'terminal', 'code', 'reply-all', 'star-half-o', 'location-arrow', 'crop', 'code-fork', 'chain-broken', 'question', 'info', 'exclamation', 'superscript', 'subscript', 'eraser', 'puzzle-piece', 'microphone', 'microphone-slash', 'shield', 'calendar-o', 'fire-extinguisher', 'rocket', 'maxcdn', 'chevron-circle-left', 'chevron-circle-right', 'chevron-circle-up', 'chevron-circle-down', 'html5', 'css3', 'anchor', 'unlock-alt', 'bullseye', 'ellipsis-h', 'ellipsis-v', 'rss-square', 'play-circle', 'ticket', 'minus-square', 'minus-square-o', 'level-up', 'level-down', 'check-square', 'pencil-square', 'external-link-square', 'share-square', 'compass', 'caret-square-o-down', 'caret-square-o-up', 'caret-square-o-right', 'eur', 'gbp', 'usd', 'inr', 'jpy', 'rub', 'krw', 'btc', 'file', 'file-text', 'sort-alpha-asc', 'sort-alpha-desc', 'sort-amount-asc', 'sort-amount-desc', 'sort-numeric-asc', 'sort-numeric-desc', 'thumbs-up', 'thumbs-down', 'youtube-square', 'youtube', 'xing', 'xing-square', 'youtube-play', 'dropbox', 'stack-overflow', 'instagram', 'flickr', 'adn', 'bitbucket', 'bitbucket-square', 'tumblr', 'tumblr-square', 'long-arrow-down', 'long-arrow-up', 'long-arrow-left', 'long-arrow-right', 'apple', 'windows', 'android', 'linux', 'dribbble', 'skype', 'foursquare', 'trello', 'female', 'male', 'gratipay', 'sun-o', 'moon-o', 'archive', 'bug', 'vk', 'weibo', 'renren', 'pagelines', 'stack-exchange', 'arrow-circle-o-right', 'arrow-circle-o-left', 'caret-square-o-left', 'dot-circle-o', 'wheelchair', 'vimeo-square', 'try', 'plus-square-o', 'space-shuttle', 'slack', 'envelope-square', 'wordpress', 'openid', 'university', 'graduation-cap', 'yahoo', 'google', 'reddit', 'reddit-square', 'stumbleupon-circle', 'stumbleupon', 'delicious', 'digg', 'pied-piper', 'pied-piper-alt', 'drupal', 'joomla', 'language', 'fax', 'building', 'child', 'paw', 'spoon', 'cube', 'cubes', 'behance', 'behance-square', 'steam', 'steam-square', 'recycle', 'car', 'taxi', 'tree', 'spotify', 'deviantart', 'soundcloud', 'database', 'file-pdf-o', 'file-word-o', 'file-excel-o', 'file-powerpoint-o', 'file-image-o', 'file-archive-o', 'file-audio-o', 'file-video-o', 'file-code-o', 'vine', 'codepen', 'jsfiddle', 'life-ring', 'circle-o-notch', 'rebel', 'empire', 'git-square', 'git', 'hacker-news', 'tencent-weibo', 'qq', 'weixin', 'paper-plane', 'paper-plane-o', 'history', 'circle-thin', 'header', 'paragraph', 'sliders', 'share-alt', 'share-alt-square', 'bomb', 'futbol-o', 'tty', 'binoculars', 'plug', 'slideshare', 'twitch', 'yelp', 'newspaper-o', 'wifi', 'calculator', 'paypal', 'google-wallet', 'cc-visa', 'cc-mastercard', 'cc-discover', 'cc-amex', 'cc-paypal', 'cc-stripe', 'bell-slash', 'bell-slash-o', 'trash', 'copyright', 'at', 'eyedropper', 'paint-brush', 'birthday-cake', 'area-chart', 'pie-chart', 'line-chart', 'lastfm', 'lastfm-square', 'toggle-off', 'toggle-on', 'bicycle', 'bus', 'ioxhost', 'angellist', 'cc', 'ils', 'meanpath', 'buysellads', 'connectdevelop', 'dashcube', 'forumbee', 'leanpub', 'sellsy', 'shirtsinbulk', 'simplybuilt', 'skyatlas', 'cart-plus', 'cart-arrow-down', 'diamond', 'ship', 'user-secret', 'motorcycle', 'street-view', 'heartbeat', 'venus', 'mars', 'mercury', 'transgender', 'transgender-alt', 'venus-double', 'mars-double', 'venus-mars', 'mars-stroke', 'mars-stroke-v', 'mars-stroke-h', 'neuter', 'genderless', 'facebook-official', 'pinterest-p', 'whatsapp', 'server', 'user-plus', 'user-times', 'bed', 'viacoin', 'train', 'subway', 'medium', 'y-combinator', 'optin-monster', 'opencart', 'expeditedssl', 'battery-full', 'battery-three-quarters', 'battery-half', 'battery-quarter', 'battery-empty', 'mouse-pointer', 'i-cursor', 'object-group', 'object-ungroup', 'sticky-note', 'sticky-note-o', 'cc-jcb', 'cc-diners-club', 'clone', 'balance-scale', 'hourglass-o', 'hourglass-start', 'hourglass-half', 'hourglass-end', 'hourglass', 'hand-rock-o', 'hand-paper-o', 'hand-scissors-o', 'hand-lizard-o', 'hand-spock-o', 'hand-pointer-o', 'hand-peace-o', 'trademark', 'registered', 'creative-commons', 'gg', 'gg-circle', 'tripadvisor', 'odnoklassniki', 'odnoklassniki-square', 'get-pocket', 'wikipedia-w', 'safari', 'chrome', 'firefox', 'opera', 'internet-explorer', 'television', 'contao', '500px', 'amazon', 'calendar-plus-o', 'calendar-minus-o', 'calendar-times-o', 'calendar-check-o', 'industry', 'map-pin', 'map-signs', 'map-o', 'map', 'commenting', 'commenting-o', 'houzz', 'vimeo', 'black-tie', 'fonticons'];
            resolve(icons.map(function (i) {
              return "fa-".concat(i);
            }));
          });
        },
        name: 'Font Awesome',
        load: mw.settings.libs_url + 'font-awesome/font-awesome.css',
        unload: function unload() {
          document.querySelector('link[href*="font-awesome"]').remove();
        },
        version: '4.7.0'
      },
      'materialIcons': {
        cssSelector: '.material-icons',
        detect: function detect(target) {
          return target.classList.contains('material-icons');
        },
        render: function render(icon, target) {
          target.classList.add('material-icons');
          target.innerHTML = icon;
        },
        remove: function remove(target) {
          mw.tools.removeClass(target, 'material-icons');
          target.innerHTML = '';
        },
        icons: function icons() {
          return new Promise(function (resolve) {
            $.get(mw.settings.modules_url + 'microweber/api/material.icons.js', function (data) {
              resolve(JSON.parse(data));
            });
          });
        },
        name: 'Material Icons',
        load: mw.settings.libs_url + 'material_icons' + '/material_icons.css',
        unload: function unload() {
          top.document.querySelector('link[href*="material_icons.css"]').remove();
        },
        version: 'mw'
      },
      'iconsMindLine': {
        cssSelector: '[class*="mw-micon-"]:not([class*="mw-micon-solid-"])',
        detect: function detect(target) {
          return target.className.includes('mw-micon-') && !target.className.includes('mw-micon-solid-');
        },
        render: function render(icon, target) {
          target.classList.add(icon);
        },
        remove: function remove(target) {
          mw.tools.classNamespaceDelete(target, 'mw-micon-', undefined, undefined, []);
        },
        icons: function icons() {
          var scope = this;
          var parse = function parse(cssLink) {
            if (!cssLink.sheet) {
              return;
            }
            var icons = cssLink.sheet.cssRules;
            var l = icons.length,
              i = 0,
              mindIcons = [];
            for (; i < l; i++) {
              var sel = icons[i].selectorText;
              if (!!sel && sel.indexOf('.mw-micon-') === 0) {
                var cls = sel.replace(".", '').split(':')[0];
                mindIcons.push(cls);
              }
            }
            return mindIcons;
          };
          var load = function load(cb) {
            var cssLink = mw.top().win.document.querySelector('link[href*="mw-icons-mind/line"]');
            if (cssLink) {
              cb.call(undefined, cssLink);
            } else {
              $.get(scope.load, function (data) {
                cssLink = document.createElement('link');
                cssLink.type = 'text/css';
                cssLink.rel = 'stylesheet';
                cssLink.href = scope.load;
                $(document.head).append(cssLink);
                cb.call(undefined, cssLink);
              });
            }
          };
          return new Promise(function (resolve) {
            load(function (link) {
              resolve(parse(link));
            });
          });
        },
        name: 'Icons Mind Line',
        load: mw.settings.site_url + 'vendor/microweber-packages/frontend-assets-libs/mw-icons-mind/line/style.css',
        unload: function unload() {
          document.querySelector('link[href*="mw-icons-mind/line/style"]').remove();
        },
        version: 'mw_local'
      },
      'iconsMindSolid': {
        cssSelector: '[class*="mw-micon-solid-"]',
        detect: function detect(target) {
          return target.className.includes('mw-micon-solid-');
        },
        render: function render(icon, target) {
          target.classList.add(icon);
        },
        remove: function remove(target) {
          mw.tools.classNamespaceDelete(target, 'mw-micon-solid-', undefined, undefined, []);
        },
        icons: function icons() {
          var scope = this;
          var parse = function parse(cssLink) {
            if (!cssLink.sheet) {
              return;
            }
            var icons = cssLink.sheet.cssRules;
            var l = icons.length,
              i = 0,
              mindIcons = [];
            for (; i < l; i++) {
              var sel = icons[i].selectorText;
              if (!!sel && sel.indexOf('.mw-micon-solid-') === 0) {
                var cls = sel.replace(".", '').split(':')[0];
                mindIcons.push(cls);
              }
            }
            return mindIcons;
          };
          var load = function load(cb) {
            var cssLink = mw.top().win.document.querySelector('link[href*="mw-icons-mind/solid"]');
            if (cssLink) {
              cb.call(undefined, cssLink);
            } else {
              $.get(scope.load, function (data) {
                cssLink = document.createElement('link');
                cssLink.type = 'text/css';
                cssLink.rel = 'stylesheet';
                cssLink.href = scope.load;
                $(document.head).append(cssLink);
                cb.call(undefined, cssLink);
              });
            }
          };
          return new Promise(function (resolve) {
            load(function (link) {
              resolve(parse(link));
            });
          });
        },
        name: 'Icons Mind Solid',
        load: mw.settings.site_url + 'vendor/microweber-packages/frontend-assets-libs/mw-icons-mind/solid/style.css',
        unload: function unload() {
          document.querySelector('link[href*="mw-icons-mind/solid/style"]').remove();
        },
        version: 'mw_local'
      },
      'materialDesignIcons': {
        cssSelector: '.mdi',
        detect: function detect(target) {
          return target.classList.includes('mdi');
        },
        render: function render(icon, target) {
          target.classList.add('mdi');
          target.classList.add(icon);
        },
        remove: function remove(target) {
          mw.tools.classNamespaceDelete(target, 'mdi-', undefined, undefined, []);
          target.classList.remove('mdi');
        },
        icons: function icons() {
          var scope = this;
          var load = function load(cb) {
            var cssLink = mw.top().win.document.querySelector('link[href*="materialdesignicons"]');
            if (cssLink) {
              cb.call(undefined, cssLink);
            } else {
              $.get(scope.load, function (data) {
                cssLink = document.createElement('link');
                cssLink.type = 'text/css';
                cssLink.rel = 'stylesheet';
                cssLink.href = scope.load;
                $(document.head).append(cssLink);
                cb.call(undefined, cssLink);
              });
            }
          };
          return new Promise(function (resolve) {
            load(function (link) {
              if (!link || !link.sheet) {
                resolve([]);
                return;
              }
              var icons = link.sheet.cssRules;
              var l = icons.length,
                i = 0,
                mdiIcons = [];
              for (; i < l; i++) {
                var sel = icons[i].selectorText;
                if (!!sel && sel.indexOf('.mdi-') === 0) {
                  var cls = sel.replace(".", '').split(':')[0];
                  mdiIcons.push(cls);
                }
              }
              resolve(mdiIcons);
            });
          });
        },
        name: 'Material Design Icons',
        // load:  mw.settings.modules_url + 'microweber/css/fonts/materialdesignicons/css/materialdesignicons.min.css',
        load: mw.settings.site_url + 'vendor/microweber-packages/frontend-assets-libs/mdi/css/materialdesignicons.css',
        unload: function unload() {
          document.querySelector('link[href*="materialdesignicons"]').remove();
        },
        version: 'mw_local'
      },
      'mwIcons': {
        cssSelector: '[class*="mw-icon-"]',
        detect: function detect(target) {
          return target.className.includes('mw-icon-');
        },
        render: function render(icon, target) {
          target.classList.add(icon);
        },
        remove: function remove(target) {
          mw.tools.classNamespaceDelete(target, 'mw-icon-', undefined, undefined, []);
        },
        icons: function icons() {
          return new Promise(function (resolve) {
            $.get(mw.settings.modules_url + 'microweber/api/microweber.icons.js', function (data) {
              resolve(JSON.parse(data));
            });
          });
        },
        name: 'Microweber Icons',
        load: mw.settings.modules_url + 'microweber/css/fonts/materialdesignicons/css/materialdesignicons.min.css',
        unload: function unload() {
          document.querySelector('link[href*="materialdesignicons"]').remove();
        },
        version: 'mw_local'
      },
      SVGIcons: {
        cssSelector: 'svg[viewBox]',
        detect: function detect(target) {
          return target.nodeName === 'SVG';
        },
        render: function render(icon, target) {
          target.innerHTML = icon.source;
          var svg = target.querySelector('svg');
          if (svg) {
            svg.setAttribute('width', '1em');
            svg.setAttribute('fill', 'currentColor');
            svg.setAttribute('height', '1em');
            svg.style.width = '1em';
            svg.style.height = '1em';
            svg.style.fill = 'currentColor';
          }
        },
        remove: function remove(target) {
          target.innerHTML = '';
        },
        icons: function icons() {
          return new Promise(function (resolve) {
            if (window.TemplateVectorIcons) {
              resolve(TemplateVectorIcons);
            } else {
              $.getScript(mw.settings.template_url + 'template_icons.js', function () {
                resolve(TemplateVectorIcons);
              });
            }
          });
        },
        name: 'Vector Icons',
        load: null,
        unload: function unload() {},
        version: 'mw_local'
      }
    };
    var storage = function storage() {
      if (!mw.top().__IconStorage) {
        mw.top().__IconStorage = [];
      }
      return mw.top().__IconStorage;
    };
    this.storage = store || storage;
    var iconSetKey = function iconSetKey(options) {
      return options.name + options.version;
    };
    var iconSetPush = function iconSetPush(options) {
      if (!storage().find(function (a) {
        return iconSetKey(options) === iconSetKey(a);
      })) {
        return storage().push(options);
      }
      return false;
    };
    var addFontIconSet = function addFontIconSet(options) {
      options.version = options.version || defaultVersion;
      iconSetPush(options);
      if (typeof options.load === 'string') {
        mw.require(options.load);
      } else if (typeof options.load === 'function') {
        options.load();
      }
    };
    var addIconSet = function addIconSet(conf) {
      if (typeof conf === 'string') {
        if (common[conf]) {
          conf = common[conf];
        } else {
          console.warn(conf + ' is not defined.');
          return;
        }
      }
      if (!conf) return;
      conf.type = conf.type || 'font';
      if (conf.type === 'font') {
        return addFontIconSet(conf);
      }
    };
    this.addIconSet = function (conf) {
      addIconSet(conf);
      return this;
    };
    this.removeIconSet = function (name, version) {
      var str = storage();
      var item = str.find(function (a) {
        return a.name === name && (!version || a.version === version);
      });
      if (item) {
        if (item.unload) {
          item.unload();
        }
        str.splice(str.indexOf(item), 1);
      }
    };
    this.init = function () {
      storage().forEach(function (iconSet) {
        scope.addIconSet(iconSet);
      });
    };
  };
  mw.iconLoader = function (options) {
    return new IconLoader(options);
  };
})();
(function () {
  var IconPicker = function IconPicker(options) {
    options = options || {};
    var loader = mw.iconLoader();
    var defaults = {
      iconsPerPage: 40,
      iconOptions: {
        size: true,
        color: true,
        reset: false
      }
    };
    this.settings = mw.object.extend(true, {}, defaults, options);
    var scope = this;
    var tabAccordionBuilder = function tabAccordionBuilder(items) {
      var res = {
        root: mw.element('<div class="mw-tab-accordion" data-options="tabsSize: medium, tabsColor: tab" />'),
        items: []
      };
      items.forEach(function (item) {
        var el = mw.element('<div class="mw-accordion-item px-3" />');
        var content = mw.element('<div class="mw-accordion-content px-1">' + (item.content || '') + '</div>');
        var title = mw.element('<div class="mw-ui-box-header mw-accordion-title">' + item.title + '</div>');
        el.append(title);
        el.append(content);
        content.css({
          overflowX: 'hidden'
        });
        res.root.append(el);
        res.items.push({
          title: title,
          content: content,
          root: el
        });
      });
      setTimeout(function () {
        if (mw.components) {
          mw.components._init();
        }
      }, 10);
      return res;
    };
    var createUI = function createUI() {
      var root = mw.element({
        props: {
          className: 'mw-icon-selector-root mx-auto w-100 my-3'
        }
      });
      var iconsBlockHolder, tabs, optionsHolder, iconsHolder;
      if (scope.settings.iconOptions) {
        tabs = tabAccordionBuilder([{
          title: 'Icons'
        }, {
          title: 'Options'
        }]);
        iconsBlockHolder = tabs.items[0].content;
        optionsHolder = tabs.items[1].content;
        root.append(tabs.root);
      } else {
        iconsBlockHolder = mw.element().addClass('px-3');
        root.append(iconsBlockHolder);
      }
      iconsHolder = mw.element().addClass('mw-icon-picker-icons-holder');
      iconsBlockHolder.append(iconsHolder);
      return {
        root: root,
        tabs: tabs,
        iconsBlockHolder: iconsBlockHolder,
        iconsHolder: iconsHolder,
        optionsHolder: optionsHolder
      };
    };
    var _e = {};
    this.on = function (e, f) {
      _e[e] ? _e[e].push(f) : _e[e] = [f];
    };
    this.dispatch = function (e, f) {
      _e[e] ? _e[e].forEach(function (c) {
        c.call(this, f);
      }) : '';
    };
    var actionNodes = {};
    var createOptions = function createOptions(holder) {
      if (holder && scope.settings.iconOptions) {
        if (scope.settings.iconOptions.size) {
          var label = mw.element("\n                        <div class=\"mw-icon-selector-flex my-2\">\n                            <label class=\"mw-icon-selector-control-label live-edit-label px-0\">".concat(mw.lang('Size'), "</label>\n                        </div>\n                    "));
          var currentSize = 20;
          if (scope.settings.target) {
            currentSize = parseFloat(getComputedStyle(scope.settings.target).fontSize);
          }
          var sizeel = mw.element('<div class="mwiconlist-settings-section-block-item input-group input-group-flat" style="width: 230px;"><span class="input-group-text"><kbd>px</kbd></span></div>');
          var sizeinput = mw.element("<input class=\"form-control\" value=\"".concat(currentSize, "\" type=\"number\" min=\"8\" max=\"200\">"));
          var sizeinput2 = mw.element("<input class=\"mw-icon-selector-form-control-range\" value=\"".concat(currentSize, "\" type=\"range\" min=\"8\" max=\"200\">"));
          actionNodes.size = sizeinput;
          sizeinput.on('input', function () {
            scope.dispatch('sizeChange', sizeinput.get(0).value);
            sizeinput2.val(sizeinput.get(0).value);
          });
          sizeinput2.on('input', function () {
            sizeinput.val(sizeinput2.get(0).value);
            scope.dispatch('sizeChange', sizeinput.get(0).value);
          });
          holder.append(label);
          sizeel.prepend(sizeinput2);
          sizeel.prepend(sizeinput);
          holder.append(sizeel);
        }
        if (scope.settings.iconOptions.color) {
          var cel = mw.element('<div class="mwiconlist-settings-section-block-item"><label class="mw-icon-selector-control-label live-edit-label px-0 mb-2 ps-2">Choose color</label></div>');
          var cinput = mw.element('<input class="mw-icon-selector-form-control mw-icon-selector-2-column" type="color">');
          actionNodes.color = cinput;
          cinput.on('input', function () {
            scope.dispatch('colorChange', cinput.get(0).value);
          });
          var cpHolder = mw.element();
          // cel.append(cinput);
          setTimeout(function () {
            var cpOptions = {
              element: cpHolder.get(0),
              position: 'bottom-center',
              onchange: function onchange(color) {
                scope.dispatch('colorChange', color);
              }
            };
            if (scope.settings.target) {
              cpOptions.value = getComputedStyle(scope.settings.target).color;
            }
            mw.colorPicker(cpOptions);
          }, 100);
          cel.append(cpHolder);
          holder.append(cel);
        }
        if (scope.settings.iconOptions.imageReplace) {
          var rel = mw.element("\n                    <div class=\"my-3\">\n                        <label class=\"mw-icon-selector-control-label live-edit-label px-0 mb-2 ps-2\">".concat(mw.lang('Replace with image'), "</label>\n                    </div>"));
          var rinput = mw.element("\n                        <button type=\"button\" style=\"min-width: 150px\" class=\"btn btn-light border-0 go-live-edit-href-set admin-toolbar-buttons\">\n                        ".concat(mw.lang('Choose'), "\n                        </button>\n                    "));
          rinput.on('click', function () {
            mw.filePickerDialog(function (url) {
              scope.dispatch('iconReplaced', {
                type: 'image',
                url: url
              });
            });
          });
          rel.append(rinput);
          holder.append(rel);
        }
        if (scope.settings.iconOptions.reset) {
          var rel = mw.element("\n                    <div class=\"my-3\">\n                        <label class=\"mw-icon-selector-control-label live-edit-label px-0 mb-2 ps-2\">".concat(mw.lang('Reset icon options'), "</label>\n                    </div>"));
          var rinput = mw.element("\n                        <button type=\"button\" style=\"min-width: 150px\" class=\"btn btn-outline-secondary \">\n                        ".concat(mw.lang('Reset'), "\n                        </button>\n                    "));
          rinput.on('click', function () {
            scope.dispatch('reset', rinput.get(0).value);
          });
          rel.append(rinput);
          holder.append(rel);
        }
      }
    };
    var _prepareIconsLists = function _prepareIconsLists(c) {
      var sets = loader.storage();
      var all = sets.length;
      var i = 0;
      sets.forEach(function (set) {
        if (!set._iconsLists) {
          (function (aset) {
            aset.icons().then(function (data) {
              aset._iconsLists = data;
              i++;
              if (i === all) c.call(sets, sets);
            });
          })(set);
        } else {
          i++;
          if (i === all) c.call(sets, sets);
        }
      });
    };
    var createPaging = function createPaging(length, page) {
      page = page || 1;
      var max = 999;
      var pages = Math.min(Math.ceil(length / scope.settings.iconsPerPage), max);
      var paging = document.createElement('ul');
      paging.className = 'pagination mw-live-edit-pagination-tabler d-flex flex-wrap mx-auto mt-3';
      if (scope.settings.iconsPerPage >= length) {
        return paging;
      }
      var active = false;
      for (var i = 1; i <= pages; i++) {
        var el = document.createElement('a');
        el.innerHTML = i;
        el._value = i;
        if (page === i) {
          el.className = 'active';
          active = i;
        }
        el.classList.add('page-link');
        el.onclick = function () {
          comRender({
            page: this._value
          });
        };
        var elLi = document.createElement('li');
        elLi.className = 'page-item';
        elLi.appendChild(el);
        paging.appendChild(elLi);
      }
      var all = paging.querySelectorAll('li');
      for (var i = active - 3; i < active + 2; i++) {
        if (all[i]) {
          all[i].className += ' mw-paging-visible-range';
        }
      }
      if (active < pages) {
        var next = document.createElement('a');
        next.innerHTML = '&raquo;';
        next._value = active + 1;
        next.className = 'mw-paging-visible-range mw-paging-next';
        next.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 6l6 6l-6 6"></path></svg>';
        $(paging).append(next);
        next.onclick = function () {
          comRender({
            page: this._value
          });
        };
      }
      if (active > 1) {
        var prev = document.createElement('a');
        prev.className = 'mw-paging-visible-range mw-paging-prev';
        prev.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M15 6l-6 6l6 6"></path></svg>';
        prev._value = active - 1;
        $(paging).prepend(prev);
        prev.onclick = function () {
          comRender({
            page: this._value
          });
        };
      }
      return paging;
    };
    var searchField = function searchField() {
      var time = null;
      scope.searchField = mw.element({
        tag: 'input',
        props: {
          className: 'form-control-live-edit-input',
          placeholder: 'Search',
          oninput: function oninput() {
            clearTimeout(time);
            time = setTimeout(function () {
              comRender();
            }, 123);
          }
        }
      });
      var searchFieldWrapper = mw.element('<div class="form-control-live-edit-label-wrapper m-0 mt-2"></div>');
      var searchFieldSpanEffect = mw.element('<span class="form-control-live-edit-bottom-effect"></span>');
      searchFieldWrapper.append(scope.searchField);
      searchFieldWrapper.append(searchFieldSpanEffect);
      return searchFieldWrapper;
    };
    var comRender = function comRender(options) {
      options = options || {};
      options = mw.object.extend({}, {
        set: scope.selectField.get(0).options[scope.selectField.get(0).selectedIndex]._value,
        term: scope.searchField.get(0).value
      }, options);
      scope.ui.iconsHolder.empty().append(renderSearchResults(options));
    };
    var searchSelector = function searchSelector() {
      var sel = mw.element('<select class="form-select form-control-live-edit-input" />');
      scope.selectField = sel;
      loader.storage().forEach(function (item) {
        var el = document.createElement('option');
        el._value = item;
        el.innerHTML = item.name;
        sel.append(el);
      });
      sel.on('change', function () {
        comRender();
      });
      var searchSelectorWrapper = mw.element('<div class="form-control-live-edit-label-wrapper m-0 mt-2"></div>');
      searchSelectorWrapper.append(sel);
      return searchSelectorWrapper;
    };
    var search = function search(conf) {
      conf = conf || {};
      conf.set = conf.set || loader.storage()[0];
      conf.page = conf.page || 1;
      conf.term = (conf.term || '').trim().toLowerCase();
      if (!conf.set._iconsLists) {
        return;
      }
      var all = conf.set._iconsLists.filter(function (f) {
        return (f.name || f).toLowerCase().indexOf(conf.term) !== -1;
      });
      var off = scope.settings.iconsPerPage * (conf.page - 1);
      var to = off + Math.min(all.length - off, scope.settings.iconsPerPage);
      return mw.object.extend({}, conf, {
        data: all.slice(off, to),
        all: all,
        off: off
      });
      /*for ( var i = off; i < to; i++ ) {
        }*/
    };
    var renderSearchResults = function renderSearchResults(conf) {
      var res = search(conf);
      if (!res) return;
      var pg = createPaging(res.all.length, res.page);
      var root = mw.element();
      if (!res.data.length) {
        root.append(mw.element("<div class=\"alert\" role=\"alert\"><h5 class=\"text-secondary fw-normal\">No results for <strong>".concat(conf.term, "</strong></h5></div>")));
      }
      res.data.forEach(function (iconItem) {
        var icon = mw.element({
          tag: 'span',
          props: {
            className: 'mwiconlist-icon',
            onclick: function onclick(e) {
              scope.dispatch('select', {
                icon: iconItem,
                renderer: res.set.render,
                render: function render() {
                  var sets = loader.storage();
                  sets.forEach(function (set) {
                    set.remove(scope.target);
                  });
                  return res.set.render(iconItem, scope.target);
                }
              });
              setTimeout(function () {
                mw.trigger('iconInserted');
              });
            }
          }
        });
        root.append(icon);
        res.set.render(iconItem, icon.get(0));
      });
      root.append(pg);
      return root;
    };
    var createIconsBlock = function createIconsBlock() {
      mw.spinner({
        element: scope.ui.iconsHolder.get(0),
        size: 30
      }).show();
      _prepareIconsLists(function () {
        comRender();
        mw.spinner({
          element: scope.ui.iconsHolder.get(0)
        }).hide();
      });
    };
    this.create = function () {
      this.ui = createUI();
      createOptions(this.ui.optionsHolder);
      this.ui.iconsBlockHolder.prepend(searchField());
      this.ui.iconsBlockHolder.prepend(searchSelector());
      createIconsBlock();
    };
    this.get = function () {
      return this.ui.root.get(0);
    };
    this.dialog = function (method) {
      if (method === 'hide') {
        this._dialog.hide();
        return;
      }
      if (!this._dialog) {
        this._dialog = mw.top().dialog({
          content: this.get(),
          title: 'Select icon',
          closeButtonAction: 'hide',
          width: 450,
          overlayClose: true
        });
        this._dialog.dialogContainer.style.padding = '0px';
        this._dialog.overlay.style.backgroundColor = 'transparent';
        mw.components._init();
      }
      this._dialog.show();
      return this._dialog;
    };
    this.destroy = function () {
      this.get().remove();
      if (this._dialog) {
        this._dialog.remove();
      }
      if (this._tooltip) {
        this._tooltip.remove();
      }
    };
    this.target = null;
    this.tooltip = function (target) {
      this.target = target;
      if (target === 'hide' && this._tooltip) {
        this._tooltip.style.display = 'none';
      } else {
        if (!this._tooltip) {
          this._tooltip = mw.tooltip({
            content: this.get(),
            element: target,
            position: 'bottom-center'
          });
        } else {
          mw.tools.tooltip.setPosition(this._tooltip, target, 'bottom-center');
        }
        this._tooltip.style.display = 'block';
        if (target.nodeType === 1) {
          var css = getComputedStyle(target);
          $('[type="number"],[type="range"]', this._tooltip).val(parseFloat(css.fontSize));
          $('[type="color"]', this._tooltip).val(mw.color.rgbOrRgbaToHex(css.color));
        }
      }
      mw.components._init();
      return this._tooltip;
    };
    this.init = function () {
      this.create();
    };
    this.promise = function () {
      return new Promise(function (resolve) {
        scope.on('select', function (data) {
          resolve(data);
        });
      });
    };
    this.init();
  };
  mw.iconPicker = function (options) {
    return new IconPicker(options);
  };
})();

/***/ }),

/***/ "./resources/assets/components/notification.js":
/*!*****************************************************!*\
  !*** ./resources/assets/components/notification.js ***!
  \*****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
;
(function () {
  var defaultTimeout = 5000;
  var targetWindow = window.top;
  var targetDocument = window.top.document;
  var errorsHandlePrev = [];
  mw.errorsHandle = function (obj) {
    while (errorsHandlePrev.length) {
      errorsHandlePrev[errorsHandlePrev.length - 1].remove();
      errorsHandlePrev.pop();
    }
    if (!obj) return;
    if (obj.status === 401) {
      mw.session.checkPause = false;
      mw.session.checkPauseExplicitly = false;
      mw.session.logRequest();
    }
    obj.errors = obj.errors || obj.form_errors;
    if (!obj.errors) {
      $('.invalid-feedback', targetDocument).hide();
      $('.valid-feedback', targetDocument).hide();
    }
    if (obj.errors) {
      var html = [];
      for (var key in obj.errors) {
        var bsel = $('.custom-file-input[name="' + key + '"], .form-control[name="' + key + '"]').last()[0];
        if (!bsel) {
          var err = obj.errors[key].map ? obj.errors[key][0] : obj.errors[key];
          html.push(err);
        } else if (bsel) {
          var next = bsel.nextElementSibling;
          if (!next || !next.classList.contains('invalid-feedback')) {
            next = targetDocument.createElement('div');
            next.classList.add('invalid-feedback');
            bsel.parentNode.insertBefore(next, bsel.nextSibling);
            errorsHandlePrev.push(next);
          }
          next.style.display = 'block';
          $(next, targetDocument).css('display', 'block');
          next.innerHTML = obj.errors[key];
        }
      }
      if (html.length) {
        mw.notification.warning(html.join('<br>'));
      }
    }
    if (obj.errors && obj.message) {
      mw.notification.warning(obj.message);
    }
  };
  mw.notification = {
    msg: function msg(data, timeout, alert) {
      timeout = timeout || defaultTimeout;
      alert = alert || false;
      if (data) {
        if (data.status) {
          if (data.responseJSON && data.responseJSON.message) {
            var conf = {};
            if (data.status === 200) {
              conf.success = data.responseJSON.message || data.statusText;
            } else {
              conf.warning = data.responseJSON.message || data.statusText;
            }
            mw.notification.msg(conf, timeout, alert);
          }
        } else if (data.success) {
          if (alert) {
            mw.notification.success(data.success, timeout);
          } else {
            mw.alert(data.success);
          }
        } else if (data.error) {
          mw.notification.error(data.error, timeout);
        } else if (data.warning) {
          mw.notification.warning(data.warning, timeout);
        }
      }
    },
    build: function build(type, text, name) {
      var div = targetDocument.createElement('div');
      div.id = name;
      div.className = 'mw-notification mw-' + type;
      div.innerHTML = '<div>' + text + '</div>';
      return div;
    },
    append: function append(type, text, timeout, name) {
      if (_typeof(type) === 'object') {
        text = type.text;
        timeout = type.timeout;
        name = type.name;
        type = type.type;
      }
      var closeBtn = "\n            <button style=\"--c-300:var(--gray-300);--c-400:var(--gray-400);--c-500:var(--gray-500);--c-600:var(--gray-600);\" class=\"fi-icon-btn relative flex items-center justify-center rounded-lg outline-none transition duration-75 focus-visible:ring-2 -m-2 h-9 w-9 text-gray-400 hover:text-gray-500 focus-visible:ring-primary-600 dark:text-gray-500 dark:hover:text-gray-400 dark:focus-visible:ring-primary-500 fi-color-gray fi-no-notification-close-btn\" type=\"button\"  >\n                <svg class=\"fi-icon-btn-icon h-5 w-5\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 20 20\" fill=\"currentColor\" aria-hidden=\"true\" data-slot=\"icon\">\n                    <path d=\"M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z\"></path>\n                </svg>\n            </button>\n        ";
      var tpl = "\n\n        <div class=\"mw-notification\">\n            <div class=\"  align-items-center text-bg-".concat(type, " border-0\" role=\"alert\" aria-live=\"assertive\" aria-atomic=\"true\">\n                <div class=\"flex items-center justify-between\">\n                    <div>\n                        ").concat(text, "\n                    </div>\n                    ").concat(closeBtn, "\n                </div>\n            </div>\n        </div>\n\n        ");
      var last = $('.mw-notification', targetDocument).last();
      if (last.length) {
        var btm = parseFloat(last.css('top'));
        if (isNaN(btm)) {
          btm = 0;
        }
        btm += last.outerHeight();
      }
      tpl = $(tpl, targetDocument).appendTo(targetDocument.body);
      tpl.css('top', btm);
      tpl.find('button').on('click', function () {
        tpl.remove();
      });
      setTimeout(function () {
        tpl.remove();
      }, timeout);
    },
    success: function success(text, timeout, name) {
      if (_typeof(text) === 'object') {
        timeout = text.timeout;
        name = text.name;
        text = text.text;
      }
      timeout = timeout || defaultTimeout;
      mw.notification.append('success', text, timeout, name);
    },
    error: function error(text, timeout, name) {
      if (_typeof(text) === 'object') {
        timeout = text.timeout;
        name = text.name;
        text = text.text;
      }
      timeout = timeout || defaultTimeout;
      mw.notification.append('danger', text, timeout, name);
    },
    warning: function warning(text, timeout, name) {
      if (_typeof(text) === 'object') {
        timeout = text.timeout;
        name = text.name;
        text = text.text;
      }
      timeout = timeout || defaultTimeout;
      mw.notification.append('warning', text, timeout, name);
    }
  };
})();

/***/ }),

/***/ "./resources/assets/core/@core.js":
/*!****************************************!*\
  !*** ./resources/assets/core/@core.js ***!
  \****************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _options_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./options.js */ "./resources/assets/core/options.js");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }

mw.pauseSave = false;
mw.askusertostay = false;
if (window.top === window) {
  window.onbeforeunload = function () {
    if (mw.askusertostay) {
      mw.notification.warning(mw.lang('You have unsaved changes'));
      return mw.lang('You have unsaved changes');
    }
  };
}
window.mwd = document;
window.mww = window;
mw.doc = document;
mw.win = window;
mw.loaded = false;
mw._random = new Date().getTime();
mw.random = function () {
  return mw._random++;
};
mw.id = function (prefix) {
  prefix = prefix || 'mw-';
  return prefix + mw.random();
};
mw.onLive = function (callback) {
  if (typeof mw.settings.liveEdit === 'boolean' && mw.settings.liveEdit) {
    callback.call(this);
  }
};
mw.onAdmin = function (callback) {
  if (window['mwAdmin']) {
    callback.call(this);
  }
};
mw.target = {};
mw.log = function () {
  if (mw.settings.debug) {
    var _top$console;
    (_top$console = top.console).log.apply(_top$console, arguments);
  }
};
mw.$ = function (selector, context) {
  if (_typeof(selector) === 'object' || typeof selector === 'string' && selector.indexOf('<') !== -1) {
    return jQuery(selector);
  }
  context = context || document;
  if (typeof document.querySelector !== 'undefined') {
    if (typeof selector === 'string') {
      try {
        return jQuery(context.querySelectorAll(selector));
      } catch (e) {
        return jQuery(selector, context);
      }
    } else {
      return jQuery(selector, context);
    }
  } else {
    return jQuery(selector, context);
  }
};
mw.parent = function () {
  if (window === top) {
    return window.mw;
  }
  if (mw.tools.canAccessWindow(parent) && parent.mw) {
    return parent.mw;
  }
  return window.mw;
};
mw.top = function () {
  if (!!mw.__top) {
    return mw.__top;
  }
  var getLastParent = function getLastParent() {
    var result = window;
    var curr = window;
    while (curr && mw.tools.canAccessWindow(curr) && (curr.mw || curr.parent.mw)) {
      result = curr;
      curr = curr.parent;
    }
    mw.__top = curr.mw;
    return result.mw;
  };
  if (window === top) {
    mw.__top = window.mw;
    return window.mw;
  } else {
    if (mw.tools.canAccessWindow(top) && top.mw) {
      mw.__top = top.mw;
      return top.mw;
    } else {
      if (window.top !== window.parent) {
        return getLastParent();
      } else {
        mw.__top = window.mw;
        return window.mw;
      }
    }
  }
};

/***/ }),

/***/ "./resources/assets/core/_.js":
/*!************************************!*\
  !*** ./resources/assets/core/_.js ***!
  \************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
mw.temp_reload_module_queue_holder = [];
mw["_"] = function (obj, sendSpecific, DONOTREPLACE) {
  if (mw.on) {
    mw.on.DOMChangePause = true;
  }
  var url = typeof obj.url !== 'undefined' ? obj.url : mw.settings.site_url + 'module/';
  var selector = typeof obj.selector !== 'undefined' ? obj.selector : '';
  var params = typeof obj.params !== 'undefined' ? obj.params : {};
  var to_send = params;
  if (typeof $(obj.selector)[0] === 'undefined') {
    mw.pauseSave = false;
    mw.on.DOMChangePause = false;
    return false;
  }
  if (mw.session) {
    mw.session.checkPause = true;
  }
  var $node = $(obj.selector);
  var node = $node[0];
  var attrs = node.attributes;

  // wait between many reloads
  if (node.id) {
    if (mw.temp_reload_module_queue_holder.indexOf(node.id) === -1) {
      mw.temp_reload_module_queue_holder.push(node.id);
      setTimeout(function () {
        var reload_index = mw.temp_reload_module_queue_holder.indexOf(node.id);
        delete mw.temp_reload_module_queue_holder[reload_index];
      }, 300);
    } else {
      return;
    }
  }
  if (sendSpecific) {
    attrs["class"] !== undefined ? to_send["class"] = attrs["class"].nodeValue : "";
    attrs["data-module-name"] !== undefined ? to_send["data-module-name"] = attrs["data-module-name"].nodeValue : "";
    attrs["data-type"] !== undefined ? to_send["data-type"] = attrs["data-type"].nodeValue : "";
    attrs["type"] !== undefined ? to_send["type"] = attrs["type"].nodeValue : "";
    attrs["template"] !== undefined ? to_send["template"] = attrs["template"].nodeValue : "";
    attrs["ondrop"] !== undefined ? to_send["ondrop"] = attrs["ondrop"].nodeValue : "";
  } else {
    for (var i in attrs) {
      if (attrs[i] !== undefined) {
        var name = attrs[i].name;
        var val = attrs[i].nodeValue;
        if (typeof to_send[name] === 'undefined') {
          to_send[name] = val;
        }
      }
    }
  }
  var b = true;
  for (var a in to_send) {
    if (to_send.hasOwnProperty(a)) {
      b = false;
    }
  }
  if (b) {
    mw.tools.removeClass(document.body, 'loading');
    mw.pauseSave = false;
    mw.on.DOMChangePause = false;
    return false;
  }
  var storedValues = $node.get(0).dataset['storeValues'] === 'true' ? {} : false;
  if (storedValues) {
    $node.find('[name]').each(function () {
      storedValues[this.name] = $(this).val();
    });
  }
  var xhr = $.post(url, to_send, function (data) {
    if (!!mw.session) {
      mw.session.checkPause = false;
    }
    if (DONOTREPLACE) {
      mw.tools.removeClass(document.body, 'loading');
      mw.pauseSave = false;
      mw.on.DOMChangePause = false;
      return false;
    }
    var docdata = mw.tools.parseHtml(data);
    if (storedValues) {
      mw.$('[name]', docdata).each(function () {
        var el = $(this);
        if (!el.val()) {
          el.val(storedValues[this.name] || undefined);
          this.setAttribute("value", storedValues[this.name] || '');
        }
      });
    }
    var hasDone = typeof obj.done === 'function';
    var id;
    if (typeof to_send.id !== 'undefined') {
      id = to_send.id;
    } else {
      id = docdata.body.querySelector(['id']);
    }
    mw.$(selector).replaceWith($(docdata.body).html());
    var count = 0;
    if (hasDone) {
      setTimeout(function () {
        count++;
        obj.done.call($(selector)[0], data);
        mw.trigger('moduleLoaded');
      }, 33);
    }
    if (!id) {
      mw.pauseSave = false;
      mw.on.DOMChangePause = false;
      return false;
    }
    typeof mw.drag !== 'undefined' ? mw.drag.fix_placeholders(true) : '';
    var m = document.getElementById(id);
    // typeof obj.done === 'function' ? obj.done.call(selector, m) : '';

    if (mw.wysiwyg) {
      $(m).hasClass("module") ? mw.wysiwyg.init_editables(m) : '';
    }
    if (mw.on && !hasDone) {
      mw.on.moduleReload(id, "", true);
      mw.trigger('moduleLoaded');
    }
    if ($.fn.selectpicker) {
      $('.selectpicker').selectpicker();
    }
    if (mw.on) {
      mw.on.DOMChangePause = false;
    }
    mw.tools.removeClass(document.body, 'loading');
  }).fail(function () {
    mw.pauseSave = false;
    typeof obj.fail === 'function' ? obj.fail.call(selector) : '';
  }).always(function () {
    mw.pauseSave = false;
  });
  return xhr;
};

/***/ }),

/***/ "./resources/assets/core/ajax.js":
/*!***************************************!*\
  !*** ./resources/assets/core/ajax.js ***!
  \***************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return e; }; var t, e = {}, r = Object.prototype, n = r.hasOwnProperty, o = Object.defineProperty || function (t, e, r) { t[e] = r.value; }, i = "function" == typeof Symbol ? Symbol : {}, a = i.iterator || "@@iterator", c = i.asyncIterator || "@@asyncIterator", u = i.toStringTag || "@@toStringTag"; function define(t, e, r) { return Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }), t[e]; } try { define({}, ""); } catch (t) { define = function define(t, e, r) { return t[e] = r; }; } function wrap(t, e, r, n) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype), c = new Context(n || []); return o(a, "_invoke", { value: makeInvokeMethod(t, r, c) }), a; } function tryCatch(t, e, r) { try { return { type: "normal", arg: t.call(e, r) }; } catch (t) { return { type: "throw", arg: t }; } } e.wrap = wrap; var h = "suspendedStart", l = "suspendedYield", f = "executing", s = "completed", y = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var p = {}; define(p, a, function () { return this; }); var d = Object.getPrototypeOf, v = d && d(d(values([]))); v && v !== r && n.call(v, a) && (p = v); var g = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(p); function defineIteratorMethods(t) { ["next", "throw", "return"].forEach(function (e) { define(t, e, function (t) { return this._invoke(e, t); }); }); } function AsyncIterator(t, e) { function invoke(r, o, i, a) { var c = tryCatch(t[r], t, o); if ("throw" !== c.type) { var u = c.arg, h = u.value; return h && "object" == _typeof(h) && n.call(h, "__await") ? e.resolve(h.__await).then(function (t) { invoke("next", t, i, a); }, function (t) { invoke("throw", t, i, a); }) : e.resolve(h).then(function (t) { u.value = t, i(u); }, function (t) { return invoke("throw", t, i, a); }); } a(c.arg); } var r; o(this, "_invoke", { value: function value(t, n) { function callInvokeWithMethodAndArg() { return new e(function (e, r) { invoke(t, n, e, r); }); } return r = r ? r.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(e, r, n) { var o = h; return function (i, a) { if (o === f) throw Error("Generator is already running"); if (o === s) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var c = n.delegate; if (c) { var u = maybeInvokeDelegate(c, n); if (u) { if (u === y) continue; return u; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (o === h) throw o = s, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = f; var p = tryCatch(e, r, n); if ("normal" === p.type) { if (o = n.done ? s : l, p.arg === y) continue; return { value: p.arg, done: n.done }; } "throw" === p.type && (o = s, n.method = "throw", n.arg = p.arg); } }; } function maybeInvokeDelegate(e, r) { var n = r.method, o = e.iterator[n]; if (o === t) return r.delegate = null, "throw" === n && e.iterator["return"] && (r.method = "return", r.arg = t, maybeInvokeDelegate(e, r), "throw" === r.method) || "return" !== n && (r.method = "throw", r.arg = new TypeError("The iterator does not provide a '" + n + "' method")), y; var i = tryCatch(o, e.iterator, r.arg); if ("throw" === i.type) return r.method = "throw", r.arg = i.arg, r.delegate = null, y; var a = i.arg; return a ? a.done ? (r[e.resultName] = a.value, r.next = e.nextLoc, "return" !== r.method && (r.method = "next", r.arg = t), r.delegate = null, y) : a : (r.method = "throw", r.arg = new TypeError("iterator result is not an object"), r.delegate = null, y); } function pushTryEntry(t) { var e = { tryLoc: t[0] }; 1 in t && (e.catchLoc = t[1]), 2 in t && (e.finallyLoc = t[2], e.afterLoc = t[3]), this.tryEntries.push(e); } function resetTryEntry(t) { var e = t.completion || {}; e.type = "normal", delete e.arg, t.completion = e; } function Context(t) { this.tryEntries = [{ tryLoc: "root" }], t.forEach(pushTryEntry, this), this.reset(!0); } function values(e) { if (e || "" === e) { var r = e[a]; if (r) return r.call(e); if ("function" == typeof e.next) return e; if (!isNaN(e.length)) { var o = -1, i = function next() { for (; ++o < e.length;) if (n.call(e, o)) return next.value = e[o], next.done = !1, next; return next.value = t, next.done = !0, next; }; return i.next = i; } } throw new TypeError(_typeof(e) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, o(g, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), o(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, u, "GeneratorFunction"), e.isGeneratorFunction = function (t) { var e = "function" == typeof t && t.constructor; return !!e && (e === GeneratorFunction || "GeneratorFunction" === (e.displayName || e.name)); }, e.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, define(t, u, "GeneratorFunction")), t.prototype = Object.create(g), t; }, e.awrap = function (t) { return { __await: t }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, c, function () { return this; }), e.AsyncIterator = AsyncIterator, e.async = function (t, r, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(wrap(t, r, n, o), i); return e.isGeneratorFunction(r) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, defineIteratorMethods(g), define(g, u, "Generator"), define(g, a, function () { return this; }), define(g, "toString", function () { return "[object Generator]"; }), e.keys = function (t) { var e = Object(t), r = []; for (var n in e) r.push(n); return r.reverse(), function next() { for (; r.length;) { var t = r.pop(); if (t in e) return next.value = t, next.done = !1, next; } return next.done = !0, next; }; }, e.values = values, Context.prototype = { constructor: Context, reset: function reset(e) { if (this.prev = 0, this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(resetTryEntry), !e) for (var r in this) "t" === r.charAt(0) && n.call(this, r) && !isNaN(+r.slice(1)) && (this[r] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0].completion; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(e) { if (this.done) throw e; var r = this; function handle(n, o) { return a.type = "throw", a.arg = e, r.next = n, o && (r.method = "next", r.arg = t), !!o; } for (var o = this.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i.completion; if ("root" === i.tryLoc) return handle("end"); if (i.tryLoc <= this.prev) { var c = n.call(i, "catchLoc"), u = n.call(i, "finallyLoc"); if (c && u) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } else if (c) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); } else { if (!u) throw Error("try statement without catch or finally"); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } } } }, abrupt: function abrupt(t, e) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var o = this.tryEntries[r]; if (o.tryLoc <= this.prev && n.call(o, "finallyLoc") && this.prev < o.finallyLoc) { var i = o; break; } } i && ("break" === t || "continue" === t) && i.tryLoc <= e && e <= i.finallyLoc && (i = null); var a = i ? i.completion : {}; return a.type = t, a.arg = e, i ? (this.method = "next", this.next = i.finallyLoc, y) : this.complete(a); }, complete: function complete(t, e) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && e && (this.next = e), y; }, finish: function finish(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.finallyLoc === t) return this.complete(r.completion, r.afterLoc), resetTryEntry(r), y; } }, "catch": function _catch(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.tryLoc === t) { var n = r.completion; if ("throw" === n.type) { var o = n.arg; resetTryEntry(r); } return o; } } throw Error("illegal catch attempt"); }, delegateYield: function delegateYield(e, r, n) { return this.delegate = { iterator: values(e), resultName: r, nextLoc: n }, "next" === this.method && (this.arg = t), y; } }, e; }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
var _jqxhr = jQuery.ajax;
mw.jqxhr = _jqxhr;
jQuery.ajax = function (url, options) {
  options = options || {};
  var settings = {};
  if (_typeof(url) === 'object') {
    $.extend(settings, url);
  } else {
    settings.url = url;
  }
  if (typeof settings.success === 'function') {
    settings._success = settings.success;
    delete settings.success;
    settings.success = function (data, status, xhr) {
      if (xhr.status === 200) {
        if (data && (data.form_data_required || data.form_data_module)) {
          mw.extradataForm(settings, data);
        } else {
          if (typeof this._success === 'function') {
            var scope = this;
            scope._success.call(scope, data, status, xhr);
          }
        }
      }
    };
  }
  settings = $.extend({}, settings, options);
  return _jqxhr(settings);
};
$.ajaxSetup({
  cache: false,
  error: function error(xhr, e) {
    if (xhr.status === 422) {
      mw.errorsHandle(xhr.responseJSON);
    } else if (xhr.status !== 200 && xhr.status !== 0) {
      mw.notification.error('Error ' + xhr.status + ' - ' + xhr.statusText + ' - \r\n' + xhr.responseText);
      setTimeout(function () {
        mw.tools.loading(false);
      }, 333);
    }
  }
});
jQuery.cachedScript = function (url, options) {
  options = $.extend(options || {}, {
    dataType: "script",
    cache: true,
    url: url
  });
  return jQuery.ajax(options);
};
mw.getScripts = function (array, callback) {
  if (typeof array === 'string') {
    array = array.split(',');
  }
  array = array.filter(function (item) {
    return !!item.trim();
  });
  var all = array.length,
    ready = 0;
  $.each(array, function () {
    var scr = $('<script>');
    $(scr).on('load', function () {
      ready++;
      if (all === ready) {
        callback.call();
      }
    });
    scr[0].src = this.indexOf('//') !== -1 ? this : mw.settings.includes_url + 'api/' + this;
    document.body.appendChild(scr[0]);
  });
};
mw.moduleCSS = mw.module_css = function (url) {
  if (!~mw.required.indexOf(url)) {
    mw.required.push(url);
    var el = document.createElement('link');
    el.rel = 'stylesheet';
    el.type = 'text/css';
    el.href = url;
    document.head.insertBefore(el, document.head.firstChild);
  }
};
mw.moduleJS = mw.module_js = function (url) {
  mw.require(url, true);
};

// Modules

mw.load_module = function (name, selector, callback, attributes) {
  attributes = attributes || {};
  attributes.module = name;
  return mw._({
    selector: selector,
    params: attributes,
    done: function done() {
      mw.settings.sortables_created = false;
      if (typeof callback === 'function') {
        callback.call(this);
      }
    }
  });
};
mw.loadModuleData = function (name, update_element, callback, attributes) {
  attributes = attributes || {};
  if (typeof update_element == 'function') {
    callback = update_element;
  }
  update_element = document.createElement('div');
  attributes.module = name;
  mw._({
    selector: update_element,
    params: attributes
  }, false, true).done(function (data) {
    setTimeout(function () {
      callback.call(this, data);
      $(document).off('focusin.modal');
    }, 50);
  });
};
mw.getModule = function (name, params, callback) {
  if (typeof params == 'function') {
    callback = params;
  }
  params = params || {};
  var update_element = document.createElement('div');
  for (var x in params) {
    update_element.setAttribute(x, params[x]);
  }
  mw.loadModuleData(name, update_element, function (a) {
    callback.call(a);
  });
};
mw.reload_module_intervals = {};
mw.reload_module_interval = function (module_name, interval) {
  interval = interval || 1000;
  var obj = {
    pause: false
  };
  if (!!mw.reload_module_intervals[module_name]) {
    clearInterval(mw.reload_module_intervals[module_name]);
  }
  mw.reload_module_intervals[module_name] = setInterval(function () {
    if (!obj.pause) {
      obj.pause = true;
      mw.reload_module(module_name, function () {
        obj.pause = false;
      });
    }
  }, interval);
  return mw.reload_module_intervals['module_name'];
};
mw.reload_module_parent = function (module, callback) {
  if (self !== parent && !!parent.mw) {
    parent.mw.reload_module(module, callback);
    if (typeof top.mweditor != 'undefined' && _typeof(top.mweditor) == 'object' && typeof top.mweditor.contentWindow != 'undefined') {
      top.mweditor.contentWindow.mw.reload_module(module, callback);
    } else if (typeof mw.top().win.iframe_editor_window != 'undefined' && _typeof(mw.top().win.iframe_editor_window) == 'object' && typeof mw.top().win.iframe_editor_window.mw != 'undefined') {
      mw.top().win.iframe_editor_window.mw.reload_module(module, callback);
    }
    if (typeof parent.mw_preview_frame_object != 'undefined' && _typeof(parent.mw_preview_frame_object) == 'object' && typeof parent.mw_preview_frame_object.contentWindow != 'undefined') {
      if (parent.mw_preview_frame_object.contentWindow != null && typeof parent.mw_preview_frame_object.contentWindow.mw != 'undefined') {
        parent.mw_preview_frame_object.contentWindow.mw.reload_module(module, callback);
      }
    }
  } else {
    if (typeof mweditor != 'undefined' && (typeof mweditor === "undefined" ? "undefined" : _typeof(mweditor)) == 'object' && typeof mweditor.contentWindow != 'undefined' && typeof mweditor.contentWindow.mw != 'undefined') {
      mweditor.contentWindow.mw.reload_module(module, callback);
    }
  }
};
mw.reload_modules = function (array, callback, simultaneously) {
  if (array.array && !array.slice) {
    callback = array.callback || array.done || array.ready;
    simultaneously = array.simultaneously;
    array = array.array;
  }
  simultaneously = simultaneously || false;
  if (simultaneously) {
    var l = array.length,
      ready = 0,
      i = 0;
    for (; i < l; i++) {
      mw.reload_module(array[i], function () {
        ready++;
        if (ready === l && callback) {
          callback.call();
        }
      });
    }
  } else {
    if (array.length === 0) {
      if (callback) {
        callback.call();
      }
    } else {
      var m = array[0];
      array.shift();
      mw.reload_module(m, function () {
        mw.reload_modules(array, callback, false);
      });
    }
  }
};
mw.reload_module_everywhere = function (module, eachCallback) {
  mw.tools.eachWindow(function () {
    if (this.mw && this.mw.reload_module) {
      this.mw.reload_module(module, function () {
        if (typeof eachCallback === 'function') {
          eachCallback.call(this);
        }
      });
    }
  });
};
mw.reload_module = /*#__PURE__*/function () {
  var _ref = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee2(module, callback) {
    var l, i, w, done, refreshLiveWireModule, module_name, refresh_modules_explode, m;
    return _regeneratorRuntime().wrap(function _callee2$(_context2) {
      while (1) switch (_context2.prev = _context2.next) {
        case 0:
          if (!(module.constructor === [].constructor)) {
            _context2.next = 4;
            break;
          }
          l = module.length, i = 0, w = 1;
          for (; i < l; i++) {
            mw.reload_module(module[i], function () {
              w++;
              if (w === l && typeof callback === 'function') {
                callback.call();
              }
              $(this).trigger('ModuleReload');
            });
          }
          return _context2.abrupt("return", false);
        case 4:
          done = callback || function () {};
          refreshLiveWireModule = /*#__PURE__*/function () {
            var _ref2 = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee(module) {
              var component;
              return _regeneratorRuntime().wrap(function _callee$(_context) {
                while (1) switch (_context.prev = _context.next) {
                  case 0:
                    component = Livewire.find(module.getAttribute('wire:id'));
                    _context.next = 3;
                    return component.$refresh();
                  case 3:
                  case "end":
                    return _context.stop();
                }
              }, _callee);
            }));
            return function refreshLiveWireModule(_x3) {
              return _ref2.apply(this, arguments);
            };
          }();
          if (!(typeof module !== 'undefined')) {
            _context2.next = 20;
            break;
          }
          if (!(_typeof(module) === 'object')) {
            _context2.next = 17;
            break;
          }
          if (!module.getAttribute('wire:id')) {
            _context2.next = 14;
            break;
          }
          _context2.next = 11;
          return refreshLiveWireModule(module);
        case 11:
          done.call();
          _context2.next = 15;
          break;
        case 14:
          mw._({
            selector: module,
            done: done
          });
        case 15:
          _context2.next = 20;
          break;
        case 17:
          module_name = module.toString();
          refresh_modules_explode = module_name.split(",");
          for (i = 0; i < refresh_modules_explode.length; i++) {
            module = refresh_modules_explode[i];
            if (typeof module != 'undefined') {
              module = module.replace(/##/g, '#');
              m = mw.$(".module[data-type='" + module + "']");
              if (m.length === 0) {
                try {
                  m = $(module);
                } catch (e) {}
                ;
              }
              (function (callback) {
                var count = 0;
                for (var i = 0; i < m.length; i++) {
                  mw.reload_module(m[i], function () {
                    count++;
                    if (count === m.length && typeof callback === 'function') {
                      callback.call();
                    }
                    $(document).trigger('ModuleReload');
                  });
                }
              })(callback);
            }
          }
        case 20:
        case "end":
          return _context2.stop();
      }
    }, _callee2);
  }));
  return function (_x, _x2) {
    return _ref.apply(this, arguments);
  };
}();
mw.clear_cache = function () {
  $.ajax({
    url: mw.settings.site_url + 'api/clearcache',
    type: "POST",
    success: function success(data) {
      if (mw.notification != undefined) {
        mw.notification.msg(data);
      }
    }
  });
};
mw.get = function (action, params, callback) {
  var obj;
  var url = mw.settings.api_url + action;
  var type = _typeof(params);
  if (type === 'string') {
    obj = mw.serializeFields(params);
  } else if (type.constructor === {}.constructor) {
    obj = params;
  } else {
    obj = {};
  }
  $.post(url, obj).success(function (data) {
    return typeof callback === 'function' ? callback.call(data) : data;
  }).error(function (data) {
    return typeof callback === 'function' ? callback.call(data) : data;
  });
};

/***/ }),

/***/ "./resources/assets/core/events.js":
/*!*****************************************!*\
  !*** ./resources/assets/core/events.js ***!
  \*****************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
mw.on = function (eventName, callback) {
  eventName = eventName.trim();
  $.each(eventName.split(' '), function () {
    mw.$(mw._on._eventsRegister).on(this.toString(), callback);
  });
};
mw.trigger = function (eventName, paramsArray) {
  return mw.$([window, mw._on._eventsRegister]).trigger(eventName, paramsArray);
};
mw._on = {
  _eventsRegister: {},
  mouseDownAndUp: function mouseDownAndUp(el, callback) {
    var $el = mw.$(el);
    el = $el[0];
    $el.on('mousedown touchstart', function () {
      this.__downTime = new Date().getTime();
      (function (el) {
        setTimeout(function () {
          el.__downTime = -1;
        }, 777);
      })(this);
    });
    $el.on('mouseup touchend', function (e) {
      if (!!callback) {
        callback.call(this, new Date().getTime() - this.__downTime, e);
      }
    });
  },
  onmodules: {},
  moduleReload: function moduleReload(id, c, trigger) {
    var exists;
    if (trigger) {
      exists = typeof mw.on.onmodules[id] !== 'undefined';
      if (exists) {
        var i = 0,
          l = mw.on.onmodules[id].length;
        for (; i < l; i++) {
          mw.on.onmodules[id][i].call(document.getElementById(id));
        }
      }
      return false;
    }
    if (typeof c === 'function') {
      exists = typeof mw.on.onmodules[id] !== 'undefined';
      if (exists) {
        mw.on.onmodules[id].push(c);
      } else {
        mw.on.onmodules[id] = [c];
      }
    } else if (c === 'off') {
      exists = typeof mw.on.onmodules[id] !== 'undefined';
      if (exists) {
        mw.on.onmodules[id] = [];
      }
    }
  },
  _hashrec: {},
  _hashparams: [],
  _hashparam_funcs: [],
  hashParam: function hashParam(param, callback, trigger, isManual) {
    var index;
    if (isManual) {
      index = mw.on._hashparams.indexOf(param);
      if (mw.on._hashparam_funcs[index] !== undefined) {
        mw.on._hashparam_funcs[index].call(false, false);
      }
      return false;
    }
    if (trigger === true) {
      index = mw.on._hashparams.indexOf(param);
      if (index !== -1) {
        var hash = location.hash;
        var params = mw.url.getHashParams(hash);
        if (typeof params[param] === 'string' && mw.on._hashparam_funcs[index] !== undefined) {
          var pval = decodeURIComponent(params[param]);
          mw.on._hashparam_funcs[index].call(pval, pval);
        }
      }
    } else {
      mw.on._hashparams.push(param);
      mw.on._hashparam_funcs.push(callback);
    }
  },
  hashParamEventInit: function hashParamEventInit() {
    var hash = location.hash;
    var params = mw.url.getHashParams(hash);
    if (hash === '' || hash === '#' || hash === '#?') {
      var len = mw.on._hashparams.length,
        i = 0;
      for (; i < len; i++) {
        mw.on.hashParam(mw.on._hashparams[i], "", true);
      }
    } else {
      for (var x in params) {
        if (params[x] !== mw.on._hashrec[x] || typeof mw.on._hashrec[x] === 'undefined') {
          mw.on.hashParam(x, "", true);
        }
      }
    }
    mw.on._hashrec = params;
  },
  DOMChangePause: false,
  DOMChangeTime: 1500,
  DOMChange: function DOMChange(element, callback, attr, a) {
    attr = attr || false;
    a = a || false;
    element.addEventListener("input", function (e) {
      if (!mw.on.DOMChangePause) {
        if (!a) {
          callback.call(this);
        } else {
          clearTimeout(element._int);
          element._int = setTimeout(function () {
            callback.call(element);
          }, mw.on.DOMChangeTime);
        }
      }
    }, false);
    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;
    if (typeof MutationObserver === 'function') {
      var observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
          if (!mw.on.DOMChangePause) {
            callback.call(mutation.target);
          }
        });
      });
      var config = {
        attributes: attr,
        childList: true,
        characterData: true
      };
      observer.observe(element, config);
    } else {
      element.addEventListener("DOMCharacterDataModified", function (e) {
        if (!mw.on.DOMChangePause) {
          if (!a) {
            callback.call(this);
          } else {
            clearTimeout(element._int);
            element._int = setTimeout(function () {
              callback.call(element);
            }, mw.on.DOMChangeTime);
          }
        }
      }, false);
      element.addEventListener("DOMNodeInserted", function (e) {
        if (/*mw.tools.hasClass(e.target, 'element') || */mw.tools.hasClass(e.target, 'module') || mw.tools.hasParentsWithClass(e.target, 'module')) {
          return false;
        }
        if (!mw.on.DOMChangePause) {
          if (!a) {
            callback.call(this);
          } else {
            clearTimeout(element._int);
            element._int = setTimeout(function () {
              callback.call(element);
            }, mw.on.DOMChangeTime);
          }
        }
      }, false);
      if (attr) {
        element.addEventListener("DOMAttrModified", function (e) {
          var attr = e.attrName;
          if (attr !== "contenteditable") {
            if (!mw.on.DOMChangePause) {
              if (!a) {
                callback.call(this);
              } else {
                clearTimeout(element._int);
                element._int = setTimeout(function () {
                  callback.call(element);
                }, mw.on.DOMChangeTime);
              }
            }
          }
        }, false);
      }
    }
  },
  stopWriting: function stopWriting(el, c) {
    if (el === null || typeof el === 'undefined') {
      return false;
    }
    if (!el.onstopWriting) {
      el.onstopWriting = null;
    }
    clearTimeout(el.onstopWriting);
    el.onstopWriting = setTimeout(function () {
      c.call(el);
    }, 400);
  },
  scrollBarOnBottom: function scrollBarOnBottom(obj, distance, callback) {
    if (typeof obj === 'function') {
      callback = obj;
      obj = window;
      distance = 0;
    }
    if (typeof distance === 'function') {
      callback = distance;
      distance = 0;
    }
    obj._pauseCallback = false;
    obj.pauseScrollCallback = function () {
      obj._pauseCallback = true;
    };
    obj.continueScrollCallback = function () {
      obj._pauseCallback = false;
    };
    mw.$(obj).scroll(function (e) {
      var h = obj === window ? document.body.scrollHeight : obj.scrollHeight;
      var calc = h - mw.$(obj).scrollTop() - mw.$(obj).height();
      if (calc <= distance && !obj._pauseCallback) {
        callback.call(obj);
      }
    });
  },
  tripleClick: function tripleClick(el, callback) {
    var t,
      timeout = 199;
    el = el || window;
    el.addEventListener("dblclick", function () {
      t = setTimeout(function () {
        t = null;
      }, timeout);
    });
    el.addEventListener("click", function (e) {
      if (t) {
        clearTimeout(t);
        t = null;
        callback.call(el, e.target);
      }
    });
  },
  transitionEnd: function transitionEnd(el, callback) {
    mw.$(el).bind('webkitTransitionEnd transitionend msTransitionEnd oTransitionEnd otransitionend', function () {
      callback.call(el);
    });
  },
  ones: {},
  one: function one(name, c, trigger, isDone) {
    if (trigger !== true) {
      if (mw.on.ones[name] === undefined) {
        mw.on.ones[name] = [c];
      } else {
        mw.on.ones[name].push(c);
      }
    } else {
      if (mw.on.ones[name] !== undefined) {
        var i = 0,
          l = mw.on.ones[name].length;
        for (; i < l; i++) {
          if (isDone === true) {
            mw.on.ones[name][i].call('ready', 'ready');
          } else {
            mw.on.ones[name][i].call('start', 'start');
          }
        }
      }
    }
  },
  userIteractionInitRegister: new Date().getTime(),
  userIteractionInit: function userIteractionInit() {
    var max = 378;
    mw.$(document).on('mousemove touchstart click keydown resize ajaxStop', function () {
      var time = new Date().getTime();
      if (time - mw._on.userIteractionInitRegister > max) {
        mw._on.userIteractionInitRegister = time;
        mw.trigger('UserInteraction');
      }
    });
  }
};
for (var x in mw._on) mw.on[x] = mw._on[x];
mw.hashHistory = [window.location.hash];
mw.prevHash = function () {
  var prev = mw.hashHistory[mw.hashHistory.length - 2];
  return prev !== undefined ? prev : '';
};
$(window).on("hashchange load", function (event) {
  if (event.type === 'load') {
    mw._on.userIteractionInit();
  }
  mw.on.hashParamEventInit();
  var hash = location.hash;
  var isMWHash = hash.replace(/\#/g, '').indexOf('mw@') === 0;
  if (isMWHash) {
    var MWHash = hash.replace(/\#/g, '').replace('mw@', '');
    var el = document.getElementById(MWHash);
    if (el) {
      mw.tools.scrollTo(el);
    }
  }
  if (hash.includes("showpostscat")) {
    mw.$("html").addClass("showpostscat");
  } else {
    mw.$("html").removeClass("showpostscat");
  }
  if (event.type === 'hashchange') {
    mw.hashHistory.push(location.hash);
    var size = mw.hashHistory.length;
    var changes = mw.url.whichHashParamsHasBeenRemoved(mw.hashHistory[size - 1], mw.hashHistory[size - 2]),
      l = changes.length,
      i = 0;
    if (l > 0) {
      for (; i < l; i++) {
        mw.on.hashParam(changes[i], "", true, true);
      }
    }
  }
});
mw.event = {
  windowLeave: function windowLeave(c) {
    document.documentElement.addEventListener('mouseout', function (e) {
      if (!e.relatedTarget && !e.toElement && c) {
        c.call(document.body, e);
      }
    });
  },
  cancel: function cancel(e, prevent) {
    prevent === true ? e.preventDefault() : '';
    e.cancelBubble = true;
    if (e.stopPropagation) e.stopPropagation();
  },
  key: function key(e, _key) {
    return e.keyCode === parseFloat(_key);
  },
  page: function page(e) {
    e = e.originalEvent || e;
    if (e.type.indexOf('touch') !== 0) {
      return {
        x: e.pageX,
        y: e.pageY
      };
    } else {
      return {
        x: e.changedTouches[0].pageX,
        y: e.changedTouches[0].pageY
      };
    }
  },
  targetIsField: function targetIsField(e) {
    e = e.originalEvent || e;
    var t = e.target;
    return t.nodeName === 'INPUT' || t.nodeName === 'textarea' || t.nodeName === 'select';
  },
  get: function get(e) {
    return e.originalEvent || e;
  },
  keyCode: function keyCode(e) {
    e = mw.event.get(e);
    return e.keyCode || e.which;
  },
  isKeyCode: function isKeyCode(e, code) {
    return this.keyCode(e) === code;
  },
  is: {
    comma: function comma(e) {
      e = mw.event.get(e);
      return e.keyCode === 188;
    },
    enter: function enter(e) {
      e = mw.event.get(e);
      return e.key === "Enter" || mw.event.isKeyCode(e, 13);
    },
    escape: function escape(e) {
      e = mw.event.get(e);
      return e.key === "Escape" || mw.event.isKeyCode(e, 27);
    },
    backSpace: function backSpace(e) {
      e = mw.event.get(e);
      return e.key === "Backspace" || mw.event.isKeyCode(e, 8);
    },
    "delete": function _delete(e) {
      e = mw.event.get(e);
      return e.key === "Delete" || mw.event.isKeyCode(e, 46);
    }
  }
};
mw.on('ComponentsLaunch', function () {
  mw.components._init();
});
mw.on('mwDialogShow', function () {
  setTimeout(function () {
    mw.components._init();
  }, 110);
});

/***/ }),

/***/ "./resources/assets/core/helpers.js":
/*!******************************************!*\
  !*** ./resources/assets/core/helpers.js ***!
  \******************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Helpers: () => (/* binding */ Helpers)
/* harmony export */ });
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
var Helpers = {
  fragment: function fragment() {
    if (!this._fragment) {
      this._fragment = document.createElement('div');
      this._fragment.style.visibility = 'hidden';
      this._fragment.style.position = 'absolute';
      this._fragment.style.width = '1px';
      this._fragment.style.height = '1px';
      document.body.appendChild(this._fragment);
    }
    return this._fragment;
  },
  _isBlockCache: {},
  isBlockLevel: function isBlockLevel(node) {
    if (!node || node.nodeType === 3) {
      return false;
    }
    var blockLike = ['flex', 'block', 'grid', 'table', 'list-item', 'table-row', 'table-cell', 'table-row-group', 'table-header-group', 'table-footer-group', 'table-column-group', 'table-column', 'table-caption', 'table-cell', 'table-row', 'inline-block', 'inline-table', 'inline-flex', 'inline-grid', 'flow-root', 'grid', 'grid-item', 'grid-line', 'grid-row', 'grid-column', 'grid-area', 'grid-auto-rows', 'grid-auto-columns', 'grid-template-rows', 'grid-template-columns', 'grid-template-areas', 'grid-template', 'grid-auto-flow', 'grid-row-gap', 'grid-column-gap', 'grid-gap', 'grid-row-start', 'grid-row-end', 'grid-column-start', 'grid-column-end', 'grid-area'];
    return blockLike.indexOf(getComputedStyle(node).display) !== -1;
  },
  _isInlineCache: {},
  isInlineLevel: function isInlineLevel(node) {
    if (node.nodeType === 3) {
      return false;
    }
    var name = node.nodeName;
    if (typeof this._isInlineCache[name] !== 'undefined') {
      return this._isInlineCache[name];
    }
    var test = document.createElement(name);
    this.fragment().appendChild(test);
    this._isInlineCache[name] = getComputedStyle(test).display === 'inline' && node.nodeName !== 'BR';
    this.fragment().removeChild(test);
    return this._isInlineCache[name];
  },
  elementOptions: function elementOptions(el) {
    var opt = (el.dataset.options || '').trim().split(','),
      _final = {};
    if (!opt[0]) return _final;
    $.each(opt, function () {
      var arr = this.split(':');
      var val = arr[1].trim();
      if (!val) {} else if (val === 'true' || val === 'false') {
        val = val === 'true';
      } else if (!/\D/.test(val)) {
        val = parseInt(val, 10);
      }
      _final[arr[0].trim()] = val;
    });
    return _final;
  },
  createAutoHeight: function createAutoHeight() {
    if (window.thismodal && thismodal.iframe) {
      mw.tools.iframeAutoHeight(thismodal.iframe, 'now');
    } else if (mw.top().win.frameElement && mw.top().win.frameElement.contentWindow === window) {
      mw.tools.iframeAutoHeight(mw.top().win.frameElement, 'now');
    } else if (window.top !== window) {
      mw.top().$('iframe').each(function () {
        try {
          if (this.contentWindow === window) {
            mw.tools.iframeAutoHeight(this, 'now');
          }
        } catch (e) {}
      });
    }
  },
  collision: function collision(el1, el2) {
    if (!el1 || !el2) return;
    el1 = mw.$(el1);
    el2 = mw.$(el2);
    var o1 = el1.offset();
    var o2 = el2.offset();
    o1.width = el1.width();
    o1.height = el1.height();
    o2.width = el2.width();
    o2.height = el2.height();
    return o1.left < o2.left + o2.width && o1.left + o1.width > o2.left && o1.top < o2.top + o2.height && o1.top + o1.height > o2.top;
  },
  distance: function distance(x1, y1, x2, y2) {
    var a = x1 - x2;
    var b = y1 - y2;
    return Math.floor(Math.sqrt(a * a + b * b));
  },
  copy: function copy(value) {
    var tempInput = document.createElement("textarea");
    tempInput.style = "position: absolute; left: -1000px; top: -1000px";
    tempInput.value = value;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);
    mw.notification.success(mw.lang('Copied') + ': "' + value + '"');
  },
  cloneObject: function cloneObject(object) {
    return jQuery.extend(true, {}, object);
  },
  constructions: function constructions() {
    mw.$(".mw-image-holder").each(function () {
      var img = this.querySelector('img');
      if (img && img.src) {
        mw.$(this).css('backgroundImage', 'url(' + img.src + ')');
      }
    });
  },
  isRtl: function isRtl(el) {
    //todo
    el = el || document.documentElement;
    return document.documentElement.dir === 'rtl';
  },
  isEditable: function isEditable(item) {
    var el = item;
    if (!!item.type && !!item.target) {
      el = item.target;
    }
    return mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(el, ['edit', 'module']) && mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(el, ['edit', 'noedit']);
  },
  eachIframe: function eachIframe(callback, root, ignore) {
    root = root || document;
    var scope = this;
    ignore = ignore || [];
    var all = root.querySelectorAll('iframe'),
      i = 0;
    for (; i < all.length; i++) {
      if (mw.tools.canAccessIFrame(all[i]) && ignore.indexOf(all[i]) === -1) {
        callback.call(all[i].contentWindow, all[i].contentWindow);
        scope.eachIframe(callback, all[i].contentWindow.document);
      }
    }
  },
  eachWindow: function eachWindow(callback, options) {
    options = options || {};
    var curr = window;
    callback.call(curr, curr);
    while (curr !== top) {
      if (curr.parent && curr.parent.document) {
        this.eachIframe(function (iframeWindow) {
          callback.call(iframeWindow, iframeWindow);
        }, curr.parent.document, [curr]);
        curr = curr.parent;
        callback.call(curr, curr);
      }
    }
    this.eachIframe(function (iframeWindow) {
      callback.call(iframeWindow, iframeWindow);
    });
    if (window.opener !== null && mw.tools.canAccessWindow(opener)) {
      callback.call(window.opener, window.opener);
      this.eachIframe(function (iframeWindow) {
        callback.call(iframeWindow, iframeWindow);
      }, window.opener.document);
    }
  },
  canAccessWindow: function canAccessWindow(winObject) {
    var can = false;
    try {
      var doc = winObject.document;
      can = !!doc.body;
    } catch (err) {}
    return can;
  },
  canAccessIFrame: function canAccessIFrame(iframe) {
    var can = false;
    try {
      var doc = iframe.contentDocument || iframe.contentWindow.document;
      can = !!doc.body && !!doc.documentElement;
    } catch (err) {}
    return can;
  },
  createStyle: function createStyle(c, css, ins) {
    ins = ins || document.getElementsByTagName('head')[0];
    var style = mw.$(c)[0];
    if (!style) {
      style = document.createElement('style');
      ins.appendChild(style);
    }
    style.innerHTML = css;
    return style;
  },
  cssNumber: function cssNumber(val) {
    var units = ["px", "%", "in", "cm", "mm", "em", "ex", "pt", "pc"];
    if (typeof val === 'number') {
      return val + 'px';
    } else if (typeof val === 'string' && parseFloat(val).toString() === val) {
      return val + 'px';
    } else {
      if (isNaN(parseFloat(val))) {
        return '0px';
      } else {
        return val;
      }
    }
  },
  isField: function isField(target) {
    var t = target.nodeName.toLowerCase();
    var fields = /^(input|textarea|select)$/i;
    return fields.test(t);
  },
  toggleCheckbox: function toggleCheckbox(node) {
    if (node === null || node === undefined) return false;
    node.checked = !node.checked;
    return node.checked;
  },
  jQueryFields: function jQueryFields(root) {
    if (typeof root === 'string') {
      root = document.querySelector(root);
    }
    if (typeof root === 'undefined' || root === null) return false;
    var allFields = "textarea, select, input[type='checkbox']:checked, input[type='color'], input[type='date'], input[type='datetime'], input[type='datetime-local'], input[type='email'], input[type='file'], input[type='hidden'], input[type='month'], input[type='number'], input[type='password'], input[type='radio']:checked, input[type='range'], input[type='search'], input[type='tel'], input[type='text'], input[type='time'], input[type='url'], input[type='week']";
    return mw.$(allFields, fields).not(':disabled');
  },
  toggle: function toggle(who, toggler, callback) {
    who = mw.$(who);
    who.toggle();
    who.toggleClass('toggle-active');
    mw.$(toggler).toggleClass('toggler-active');
    mw.is.func(callback) ? callback.call(who) : '';
  },
  _confirm: function _confirm(question, callback) {
    var conf = confirm(question);
    if (conf && typeof callback === 'function') {
      callback.call(window);
    }
    return conf;
  },
  el_switch: function el_switch(arr, type) {
    if (type === 'semi') {
      mw.$(arr).each(function () {
        var el = mw.$(this);
        if (el.hasClass("semi_hidden")) {
          el.removeClass("semi_hidden");
        } else {
          el.addClass("semi_hidden");
        }
      });
    } else {
      mw.$(arr).each(function () {
        var el = mw.$(this);
        if (el.css('display') === 'none') {
          el.show();
        } else {
          el.hide();
        }
      });
    }
  },
  focus_on: function focus_on(el) {
    if (!$(el).hasClass('mw-focus')) {
      mw.$(".mw-focus").each(function () {
        this !== el ? mw.$(this).removeClass('mw-focus') : '';
      });
      mw.$(el).addClass('mw-focus');
    }
  },
  scrollTo: function scrollTo(el, callback, minus) {
    minus = minus || 0;
    if ($(el).length === 0) {
      return false;
    }
    if (typeof callback === 'number') {
      minus = callback;
    }
    mw.$('html,body').stop().animate({
      scrollTop: mw.$(el).offset().top - minus
    }, function () {
      typeof callback === 'function' ? callback.call(el) : '';
    });
  },
  accordion: function accordion(el, callback) {
    var speed = 200;
    var container = el.querySelector('.mw-accordion-content');
    if (container === null) return false;
    var is_hidden = getComputedStyle(container).display === 'none';
    if (!$(container).is(":animated")) {
      if (is_hidden) {
        mw.$(container).slideDown(speed, function () {
          mw.$(el).addClass('active');
          typeof callback === 'function' ? callback.call(el, 'visible') : '';
        });
      } else {
        mw.$(container).slideUp(speed, function () {
          mw.$(el).removeClass('active');
          typeof callback === 'function' ? callback.call(el, 'hidden') : '';
        });
      }
    }
  },
  index: function index(el, parent, selector) {
    el = mw.$(el)[0];
    selector = selector || el.tagName.toLowerCase();
    parent = parent || el.parentNode;
    var all;
    if (parent.constructor === [].constructor) {
      all = parent;
    } else {
      all = mw.$(selector, parent);
    }
    var i = 0,
      l = all.length;
    for (; i < l; i++) {
      if (el === all[i]) return i;
    }
  },
  highlight: function highlight(el, color, speed1, speed2) {
    if (!el) return false;
    mw.$(el).stop();
    color = color || '#48AD79';
    speed1 = speed1 || 777;
    speed2 = speed2 || 777;
    var curr = window.getComputedStyle(el, null).backgroundColor;
    if (curr === 'transparent') {
      curr = '#ffffff';
    }
    mw.$(el).animate({
      backgroundColor: color
    }, speed1, function () {
      mw.$(el).animate({
        backgroundColor: curr
      }, speed2, function () {
        mw.$(el).css('backgroundColor', '');
      });
    });
  },
  highlightStop: function highlightStop(el) {
    mw.$(el).stop();
    mw.$(el).css('backgroundColor', '');
  },
  toCamelCase: function toCamelCase(str) {
    return str.replace(/(\-[a-z])/g, function (a) {
      return a.toUpperCase().replace('-', '');
    });
  },
  multihover: function multihover() {
    var l = arguments.length,
      i = 1;
    var type = arguments[0].type;
    var check = type === 'mouseover' || type === 'mouseenter';
    for (; i < l; i++) {
      check ? mw.$(arguments[i]).addClass('hovered') : mw.$(arguments[i]).removeClass('hovered');
    }
  },
  listSearch: function listSearch(val, list) {
    val = val.trim().toLowerCase();
    if (!val) {
      $('li', list).show();
      return;
    }
    this.search(val, 'li', function (found) {
      if (found) {
        $(this).show();
      } else {
        $(this).hide();
      }
    }, list);
  },
  search: function search(string, selector, callback, root) {
    root = !!root ? $(root)[0] : mwd;
    if (!root) {
      return;
    }
    string = string.toLowerCase();
    var items;
    if (_typeof(selector) === 'object') {
      items = selector;
    } else if (typeof selector === 'string') {
      items = root.querySelectorAll(selector);
    } else {
      return false;
    }
    var i = 0,
      l = items.length;
    for (; i < l; i++) {
      items[i].textContent.toLowerCase().contains(string) ? callback.call(items[i], true) : callback.call(items[i], false);
    }
  },
  ajaxIsSearching: false,
  ajaxSearcSetting: {
    limit: 10,
    keyword: '',
    order_by: 'updated_at desc',
    search_in_fields: 'title'
  },
  ajaxSearch: function ajaxSearch(o, callback) {
    if (!mw.tools.ajaxIsSearching) {
      var obj = $.extend({}, mw.tools.ajaxSearcSetting, o);
      mw.tools.ajaxIsSearching = $.post(mw.settings.site_url + "api/get_content_admin", obj, function (data) {
        if (typeof callback === 'function') {
          callback.call(data, data);
        }
      }).always(function () {
        mw.tools.ajaxIsSearching = false;
      });
    }
    return mw.tools.ajaxIsSearching;
  },
  getPostById: function getPostById(id, callback) {
    var config = {
      limit: 10,
      keyword: '',
      order_by: 'updated_at desc',
      search_in_fields: 'id',
      id: id
    };
    return this.ajaxSearch(config, callback);
  },
  iframeLinksToParent: function iframeLinksToParent(iframe) {
    mw.$(iframe).contents().find('a').each(function () {
      var href = this.href;
      if (href.contains(mw.settings.site_url)) {
        this.target = '_parent';
      }
    });
  },
  get_filename: function get_filename(s) {
    if (s.contains('.')) {
      var d = s.lastIndexOf('.');
      return s.substring(s.lastIndexOf('/') + 1, d < 0 ? s.length : d);
    } else {
      return undefined;
    }
  },
  is_field: function is_field(obj) {
    if (obj === null || typeof obj === 'undefined' || obj.nodeType === 3) {
      return false;
    }
    if (!obj.nodeName) {
      return false;
    }
    var t = obj.nodeName.toLowerCase();
    if (t === 'input' || t === 'textarea' || t === 'select') {
      return true;
    }
    return false;
  },
  getAttrs: function getAttrs(el) {
    var attrs = el.attributes;
    var obj = {};
    for (var x in attrs) {
      if (attrs[x].nodeName) {
        obj[attrs[x].nodeName] = attrs[x].nodeValue;
      }
    }
    return obj;
  },
  copyAttributes: function copyAttributes(from, to, except) {
    except = except || [];
    var attrs = mw.tools.getAttrs(from);
    if (mw.tools.is_field(from) && mw.tools.is_field(to)) to.value = from.value;
    for (var x in attrs) {
      $.inArray(x, except) == -1 && x != 'undefined' ? to.setAttribute(x, attrs[x]) : '';
    }
  },
  isEmptyObject: function isEmptyObject(obj) {
    for (var a in obj) {
      if (obj.hasOwnProperty(a)) return false;
    }
    return true;
  },
  getFirstEqualFromTwoArrays: function getFirstEqualFromTwoArrays(a, b) {
    var ia = 0,
      ib = 0,
      la = a.length,
      lb = b.length;
    loop: for (; ia < la; ia++) {
      var curr = a[ia];
      for (; ib < lb; ib++) {
        if (b[ib] == curr) {
          return curr;
        }
      }
    }
  },
  has: function has(el, what) {
    return el.querySelector(what) !== null;
  },
  html_info: function html_info(html) {
    if (typeof mw._html_info === 'undefined') {
      mw._html_info = document.createElement('div');
      mw._html_info.id = 'mw-html-info';
      document.body.appendChild(mw._html_info);
    }
    mw.$(mw._html_info).html(html);
    return mw._html_info;
  },
  image_info: function image_info(a, callback) {
    var img = document.createElement('img');
    img.className = 'semi_hidden';
    img.src = a.src;
    document.body.appendChild(img);
    img.onload = function () {
      callback.call({
        width: mw.$(img).width(),
        height: mw.$(img).height()
      });
      mw.$(img).remove();
    };
  },
  refresh_image: function refresh_image(node) {
    node.src = mw.url.set_param('refresh_image', mw.random(), node.src);
    return node;
  },
  refresh: function refresh(a, onSuccess) {
    if (a === null || typeof a === 'undefined') {
      return false;
    }
    if (a.src) {
      a.src = mw.url.set_param('mwrefresh', mw.random(), a.src);
      if (onSuccess) {
        jQuery.get(a.getAttribute('src'), function (e) {
          onSuccess.call(a, e);
        });
      }
    } else if (a.href) {
      a.href = mw.url.set_param('mwrefresh', mw.random(), a.href);
      if (onSuccess) {
        jQuery.get(a.getAttribute('href'), function (e) {
          onSuccess.call(a, e);
        });
      }
    }
  },
  getDiff: function getDiff(_new, _old) {
    var diff = {},
      x,
      y;
    for (x in _new) {
      if (!x in _old || _old[x] != _new[x]) {
        diff[x] = _new[x];
      }
    }
    for (y in _old) {
      if (typeof _new[y] === 'undefined') {
        diff[y] = "";
      }
    }
    return diff;
  },
  parseHtml: function parseHtml(html) {
    var doc = document.implementation.createHTMLDocument("");
    doc.body.innerHTML = html;
    return doc;
  },
  isEmpty: function isEmpty(node) {
    return node.innerHTML.trim().length === 0;
  },
  isJSON: function isJSON(a) {
    if (_typeof(a) === 'object') {
      if (a.constructor === {}.constructor) {
        return true;
      } else {
        return false;
      }
    } else if (typeof a === 'string') {
      try {
        JSON.parse(a);
      } catch (e) {
        return false;
      }
      return true;
    } else {
      return false;
    }
  },
  toJSON: function toJSON(w) {
    if (_typeof(w) === 'object' && mw.tools.isJSON(w)) {
      return w;
    }
    if (typeof w === 'string') {
      try {
        var r = JSON.parse(w);
      } catch (e) {
        var r = {
          "0": w
        };
      }
      return r;
    }
    if (_typeof(w) === 'object' && w.constructor === [].constructor) {
      var obj = {},
        i = 0,
        l = w.length;
      for (; i < l; i++) {
        obj[i] = w[i];
      }
      return obj;
    }
  },
  mwattr: function mwattr(el, key, val) {
    if (!el) {
      return;
    }
    if (typeof val === 'undefined') {
      return el.dataset[key] || el.getAttribute(key) || false;
    } else {
      el.dataset[key] = val;
    }
  },
  disable: function disable(el, text, global) {
    text = text || mw.msg.loading + '...';
    global = global || false;
    var _el = mw.$(el);
    if (!_el.length) {
      return false;
    }
    if (!_el.hasClass("disabled")) {
      _el.addClass('disabled');
      if (_el[0].tagName !== 'INPUT') {
        _el.attr("data-text", _el.html());
        _el.html(text);
      } else {
        _el.attr("data-text", _el.val());
        _el.val(text);
      }
      if (global) mw.$(document.body).addClass("loading");
    }
    return el;
  },
  enable: function enable(el) {
    var _el = mw.$(el);
    if (!_el.length) {
      return false;
    }
    var text = _el.attr("data-text");
    _el.removeClass("disabled");
    if (_el[0].tagName !== 'INPUT') {
      _el.html(text);
    } else {
      _el.val(text);
    }
    mw.$(document.body).removeClass("loading");
    return el;
  },
  prependClass: function prependClass(el, cls) {
    el.className = (cls + ' ' + el.className).trim();
  },
  inview: function inview(el) {
    var $el = mw.$(el);
    if ($el.length === 0) {
      return false;
    }
    var dt = mw.$(window).scrollTop(),
      db = dt + mw.$(window).height(),
      et = $el.offset().top;
    return et <= db && !(dt > $el.height() + et);
  },
  wholeinview: function wholeinview(el) {
    var $el = mw.$(el),
      dt = mw.$(window).scrollTop(),
      db = dt + mw.$(window).height(),
      et = $el.offset().top,
      eb = et + mw.$(el).height();
    return eb <= db && et >= dt;
  },
  preload: function preload(u, c) {
    var im = new Image();
    if (typeof c === 'function') {
      im.onload = function () {
        c.call(u, im);
      };
    }
    im.src = u;
  },
  mapNodeValues: function mapNodeValues(n1, n2) {
    if (!n1 || !n2) return false;
    var setValue1 = !!n1.type && n1.nodeName !== 'BUTTON' || n1.nodeName === 'TEXTAREA' ? 'value' : 'textContent';
    var setValue2 = !!n2.type && n2.nodeName !== 'BUTTON' || n2.nodeName === 'TEXTAREA' ? 'value' : 'textContent';
    var events = 'keyup paste';
    mw.$(n1).bind(events, function () {
      n2[setValue2] = n1[setValue1];
      mw.$(n2).trigger('change');
    });
    mw.$(n2).bind(events, function () {
      n1[setValue1] = n2[setValue2];
      mw.$(n1).trigger('change');
    });
  },
  copyEvents: function copyEvents(from, to) {
    if (typeof $._data(from, 'events') === 'undefined') {
      return false;
    }
    $.each($._data(from, 'events'), function () {
      $.each(this, function () {
        mw.$(to).bind(this.type, this.handler);
      });
    });
  },
  setTag: function setTag(node, tag) {
    var el = document.createElement(tag);
    mw.tools.copyAttributes(node, el);
    while (node.firstChild) {
      el.appendChild(node.firstChild);
    }
    mw.tools.copyEvents(node, el);
    mw.$(node).replaceWith(el);
    return el;
  },
  module_settings: function module_settings(a, view, liveedit) {
    var opts = {};
    if (typeof liveedit === 'undefined') {
      opts.liveedit = true;
    }
    if (!!view) {
      opts.view = view;
    } else {
      opts.view = 'admin';
    }
    return mw.live_edit.showSettings(a, opts);
  },
  fav: function fav(a) {
    var canvas = document.createElement("canvas");
    canvas.width = 16;
    canvas.height = 16;
    var context = canvas.getContext("2d");
    context.fillStyle = "#EF3D25";
    context.fillRect(0, 0, 16, 16);
    context.font = "normal 10px Arial";
    context.textAlign = 'center';
    context.textBaseline = 'middle';
    context.fillStyle = "white";
    context.fillText(a, 8, 8);
    var im = canvas.toDataURL();
    var l = document.createElement('link');
    l.className = 'mwfav';
    l.setAttribute('rel', 'icon');
    l.setAttribute('type', 'image/png');
    l.href = im;
    mw.$(".mwfav").remove();
    document.getElementsByTagName('head')[0].appendChild(l);
  },
  px2pt: function px2pt(px) {
    var n = parseInt(px, 10);
    if (isNaN(n)) {
      return false;
    }
    return Math.round(3 / 4 * n);
  },
  matches: function matches(node, what) {
    if (node === 'init') {
      if (!!document.documentElement.matches) mw.tools.matchesMethod = 'matches';else if (!!document.documentElement.matchesSelector) mw.tools.matchesMethod = 'matchesSelector';else if (!!document.documentElement.mozMatchesSelector) mw.tools.matchesMethod = 'mozMatchesSelector';else if (!!document.documentElement.webkitMatchesSelector) mw.tools.matchesMethod = 'webkitMatchesSelector';else if (!!document.documentElement.msMatchesSelector) mw.tools.matchesMethod = 'msMatchesSelector';else if (!!document.documentElement.oMatchesSelector) mw.tools.matchesMethod = 'oMatchesSelector';else mw.tools.matchesMethod = undefined;
    } else {
      if (node === null) {
        return false;
      }
      if (typeof node === 'undefined') {
        return false;
      }
      if (node.nodeType !== 1) {
        return false;
      }
      if (!!mw.tools.matchesMethod) {
        return node[mw.tools.matchesMethod](what);
      } else {
        var doc = document.implementation.createHTMLDocument("");
        node = node.cloneNode(true);
        doc.body.appendChild(node);
        var all = doc.body.querySelectorAll(what),
          l = all.length,
          i = 0;
        for (; i < l; i++) {
          if (all[i] === node) {
            return true;
          }
        }
        return false;
      }
    }
  }
};

/***/ }),

/***/ "./resources/assets/core/modules.js":
/*!******************************************!*\
  !*** ./resources/assets/core/modules.js ***!
  \******************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
mw.load_module = function (name, selector, callback, attributes) {
  attributes = attributes || {};
  attributes.module = name;
  return mw._({
    selector: selector,
    params: attributes,
    done: function done() {
      mw.settings.sortables_created = false;
      if (typeof callback === 'function') {
        callback.call(this);
      }
    }
  });
};
mw.module = {
  getData: function getData(module, options) {
    if (_typeof(module) === 'object') {
      options = module;
      module = options.module;
    }
    options = options || {};
    options.module = module || options.module;
    return mw.module.xhr.post('/', options);
  },
  getAttributes: function getAttributes(target) {
    var node = mw.element(target).get(0);
    if (!target) return;
    var attrs = node.attributes;
    var data = {};
    for (var i in attrs) {
      if (attrs.hasOwnProperty(i) && attrs[i] !== undefined) {
        var name = attrs[i].name;
        var val = attrs[i].nodeValue;
        if (typeof data[name] === 'undefined') {
          data[name] = val;
        }
      }
    }
    return data;
  },
  insert: function insert(target, module, config, pos) {
    return new Promise(function (resolve) {
      pos = pos || 'bottom';
      var action;
      var id = mw.id('mw-module-'),
        el = '<div id="' + id + '"></div>';
      if (pos === 'top') {
        action = 'before';
        if (mw.tools.hasClass(target, 'allow-drop')) {
          action = 'prepend';
        }
      } else if (pos === 'bottom') {
        action = 'after';
        if (mw.tools.hasClass(target, 'allow-drop')) {
          action = 'append';
        }
      }
      mw.element(target)[action](el);
      mw.load_module(module, '#' + id, function () {
        resolve(this);
        console.log(this);
        mw.tools.scrollTo('#' + id);
      }, config);
    });
  }
};

/***/ }),

/***/ "./resources/assets/core/objects.js":
/*!******************************************!*\
  !*** ./resources/assets/core/objects.js ***!
  \******************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
mw.object = {
  extend: function extend() {
    var extended = {};
    var deep = false;
    var i = 0;
    var l = arguments.length;
    if (Object.prototype.toString.call(arguments[0]) === '[object Boolean]') {
      deep = arguments[0];
      i++;
    }
    var merge = function merge(obj) {
      for (var prop in obj) {
        if (Object.prototype.hasOwnProperty.call(obj, prop)) {
          if (deep && Object.prototype.toString.call(obj[prop]) === '[object Object]') {
            extended[prop] = mw.object.extend(true, extended[prop], obj[prop]);
          } else {
            extended[prop] = obj[prop];
          }
        }
      }
    };
    for (; i < l; i++) {
      var obj = arguments[i];
      merge(obj);
    }
    return extended;
  }
};

/***/ }),

/***/ "./resources/assets/core/options.js":
/*!******************************************!*\
  !*** ./resources/assets/core/options.js ***!
  \******************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
// JavaScript Document

/**
 *
 * Options API
 *
 * @package        js
 * @subpackage        options
 * @since        Version 0.567
 */

// ------------------------------------------------------------------------

/**
 * mw.options
 *
 *  mw.options object
 *
 * @package        js
 * @subpackage    options
 * @category    options internal api
 * @version 1.0
 */

mw.options = {
  saveOption: function saveOption(o, c, err) {
    if (_typeof(o) !== 'object') {
      return false;
    }
    var group = o.group || o.option_group,
      key = o.key || o.option_key,
      value = typeof o.value !== 'undefined' ? o.value : o.option_value;
    if (!group || !key || typeof value === 'undefined') {
      return false;
    }
    var lang = false;
    if (typeof o.lang !== 'undefined') {
      lang = o.lang;
    }
    var module = false;
    if (typeof o.module !== 'undefined') {
      module = o.module;
    }
    var data = {
      option_group: group,
      option_key: key,
      option_value: value
    };
    if (lang) {
      // for multilanguage module
      data.lang = lang;
    }
    if (module) {
      // for module param
      data.module = module;
    }
    return $.ajax({
      type: "POST",
      url: mw.settings.site_url + "api/save_option",
      data: data,
      success: function success(a) {
        if (typeof c === 'function') {
          c.call(a);
        }
      },
      error: function error(a) {
        if (typeof err === 'function') {
          err.call(a);
        }
      }
    });
  },
  save: function save(el, callback) {
    el = mw.$(el);
    var og, og1, refresh_modules11;
    if (!el) {
      return;
    }
    var opt_id = el.attr('data-id');
    og1 = og = el.attr('option-group') || el.attr('option_group') || el.attr('data-option-group');
    if (og1 == null || typeof og1 === 'undefined' || og1 == '') {}
    var og_parent = null;
    var og_test = mw.tools.firstParentWithClass(el[0], 'module');
    if (og_test) {
      og_parent = og_test.id;
      og_parent = mw.$(og_test).attr('for-module-id') || og_test.id;
    }
    // refresh_modules11 = og1 = og = og_test.id;

    var refresh_modules12 = el.attr('data-reload') || el.attr('data-refresh');
    var also_reload = el.attr('data-reload') || el.attr('data-also-reload');
    var modal = mw.$(mw.dialog.get(el).container);
    if (refresh_modules11 == undefined && modal !== undefined) {
      var for_m_id = modal.attr('data-settings-for-module');
    }
    if (refresh_modules11 == undefined) {
      var refresh_modules11 = el.attr('data-refresh');
    }
    var a = ['data-module-id', 'data-settings-for-module', 'option-group', 'data-option-group', 'data-refresh'],
      i = 0,
      l = a.length;
    var mname = modal !== undefined ? modal.attr('data-type') : undefined;

    // if (typeof(refresh_modules11) == 'undefined') {
    //     for (; i < l; i++) {
    //         var og = og === undefined ? el.attr(a[i]) : og;
    //     }
    // } else {
    //     var og = refresh_modules11;
    // }
    //
    // if (og1 != undefined) {
    //     var og = og1;
    //     if (refresh_modules11 == undefined) {
    //         if (refresh_modules12 == undefined) {
    //             refresh_modules11 = og1;
    //         } else {
    //             refresh_modules11 = refresh_modules12;
    //         }
    //     }
    // }

    var val;
    var dvu;
    var dvc;
    if (el[0].type === 'checkbox') {
      val = '', dvu = el.attr('data-value-unchecked'), dvc = el.attr('data-value-checked');
      if (!!dvu && !!dvc) {
        val = el[0].checked ? dvc : dvu;
      } else {
        var items = document.getElementsByName(el[0].name),
          i = 0,
          len = items.length;
        for (; i < len; i++) {
          var _val = items[i].value;
          val = items[i].checked == true ? val === '' ? _val : val + "," + _val : val;
        }
      }
    } else {
      val = el.val();
    }
    if (typeof og == 'undefined' && typeof og == 'undefined' && og_parent) {
      og = og_parent;
    }
    var o_data = {
      option_key: el.attr('name'),
      option_group: og,
      option_value: val
    };
    if (mname === undefined) {
      if (mname === undefined && og_test !== undefined && og_test && $(og_test).attr('data-type')) {
        var mname_from_type = $(og_test).attr('data-type');
        mname = mname_from_type.replace('/admin', '');
        o_data.module = mname;
      } else if (og_test !== undefined && og_test && $(og_test).attr('parent-module')) {
        o_data.module = $(og_test).attr('parent-module');
      }
    }
    if (mname !== undefined) {
      o_data.module = mname;
    }
    if (for_m_id !== undefined) {
      o_data.for_module_id = for_m_id;
    }
    if (og != undefined) {
      o_data.id = have_id;
    }
    var have_id = el.attr('data-custom-field-id');
    if (have_id != undefined) {
      o_data.id = have_id;
    }
    var have_option_type = el.attr('data-option-type');
    if (have_option_type != undefined) {
      o_data.option_type = have_option_type;
    } else {
      var have_option_type = el.attr('option-type');
      if (have_option_type != undefined) {
        o_data.option_type = have_option_type;
      }
    }
    var reaload_in_parent = el.attr('parent-reload');
    if (opt_id !== undefined) {
      o_data.id = opt_id;
    }
    var attrLang = el.attr('lang');
    if (typeof attrLang !== 'undefined') {
      o_data.lang = attrLang;
    }
    var attrModule = el.attr('module');
    if (typeof attrModule !== 'undefined') {
      o_data.module = attrModule;
    }
    $.ajax({
      type: "POST",
      url: mw.settings.site_url + "api/save_option",
      data: o_data,
      success: function success(data) {
        var which_module_to_reload = null;
        if (typeof refresh_modules11 == 'undefined') {
          which_module_to_reload = og1;
        } else if (refresh_modules12) {
          which_module_to_reload = refresh_modules12;
        }
        if (typeof liveEditSettings != 'undefined' && liveEditSettings || mw.top().win.liveEditSettings) {
          if (!og1 && og_parent) {
            which_module_to_reload = og_parent;
          }
        }
        var reload_in_parent_trieggered = false;
        if (mw.admin) {
          if (mw.top().win.mweditor && mw.top().win.mweditor.contentWindow) {
            setTimeout(function () {
              mw.top().win.mweditor.contentWindow.mw.reload_module("#" + which_module_to_reload);
            }, 777);
          }
        }
        if (window.parent.mw) {
          if (self !== top) {
            setTimeout(function () {
              var mod_element = window.parent.document.getElementById(which_module_to_reload);
              if (mod_element) {
                // var module_parent_edit_field = window.mw.parent().tools.firstParentWithClass(mod_element, 'edit')
                // var module_parent_edit_field = window.mw.parent().tools.firstMatchesOnNodeOrParent(mod_element, ['.edit[rel=inherit]'])
                var module_parent_edit_field = window.mw.parent().tools.firstMatchesOnNodeOrParent(mod_element, ['.edit:not([itemprop=dateModified])']);
                if (!module_parent_edit_field) {
                  module_parent_edit_field = window.mw.parent().tools.firstMatchesOnNodeOrParent(mod_element, ['.edit[rel=inherit]']);
                }
                if (module_parent_edit_field) {
                  // window.mw.parent().tools.addClass(module_parent_edit_field, 'changed');
                  window.mw.parent().wysiwyg.change(module_parent_edit_field);
                  window.mw.parent().askusertostay = true;
                }
              }
              mw.reload_module_parent("#" + which_module_to_reload);
              if (which_module_to_reload != og1) {
                mw.reload_module_parent("#" + og1);
              }
              if (typeof mw !== 'undefined' && mw.top().app && mw.top().app.editor) {
                mw.top().app.editor.dispatch('onModuleSettingsChanged', {
                  'moduleId': which_module_to_reload
                } || {});
              }
              reload_in_parent_trieggered = 1;
            }, 777);
          }
          if (window.mw.parent().reload_module != undefined) {
            if (!!mw.admin) {
              setTimeout(function () {
                window.mw.parent().reload_module("#" + which_module_to_reload);
                mw.options.___rebindAllFormsAfterReload();
              }, 777);
            } else {
              if (window.parent.mweditor != undefined) {
                window.parent.mweditor.contentWindow.mw.reload_module("#" + which_module_to_reload, function () {
                  setTimeout(function () {
                    window.mw.parent().exec("mw.admin.editor.set", window.parent.mweditor);
                    mw.options.___rebindAllFormsAfterReload();
                  }, 777);
                });
              }
              if (window.parent.mw != undefined) {
                window.mw.parent().reload_module("#" + which_module_to_reload, function () {
                  setTimeout(function () {
                    window.mw.parent().exec("mw.admin.editor.set", window.parent.mweditor);
                    mw.options.___rebindAllFormsAfterReload();
                  }, 777);
                });
              }
            }
            reload_in_parent_trieggered = 1;
          }
        }

        // if (reaload_in_parent != undefined && reaload_in_parent !== null) {
        //     //     window.mw.parent().reload_module("#"+refresh_modules11);
        //
        //     return false;
        // }

        if (also_reload != undefined) {
          if (window.mw != undefined && reaload_in_parent !== true) {
            if (window.mw.reload_module !== undefined) {
              window.mw.reload_module(also_reload, function (reloaded_el) {
                //  mw.options.form(reloaded_el, callback);
                mw.options.___rebindAllFormsAfterReload();
              });
              window.mw.reload_module('#' + also_reload, function (reloaded_el) {
                //mw.options.form(reloaded_el, callback);
                mw.options.___rebindAllFormsAfterReload();
              });
            }
          }
        }

        /*           if (reaload_in_parent !== true && for_m_id != undefined && for_m_id != '') {
                       for_m_id = for_m_id.toString()
                       if (window.mw != undefined) {
                                   // if (window.mw.reload_module !== undefined) {
                           //
                           // 			window.mw.reload_module('#'+for_m_id, function(reloaded_el){
                           //
                           // 				mw.options.form(reloaded_el, callback);
                           // 			});
                           //        }
                       }
                   } else*/

        if (reload_in_parent_trieggered == false && reaload_in_parent !== true && which_module_to_reload != undefined && which_module_to_reload != '') {
          which_module_to_reload = which_module_to_reload.toString();
          if (window.mw.reload_module !== undefined) {
            mw.reload_module_parent(which_module_to_reload, function () {
              mw.reload_module_parent("#" + which_module_to_reload, function () {
                mw.top().app.dispatch('moduleReloaded');
              });
            });
          }
        }
        typeof callback === 'function' ? callback.call(data) : '';
        setTimeout(function () {
          mw.options.___rebindAllFormsAfterReload();
          if (mw.top && mw.top().app) {
            setTimeout(function () {
              mw.top().app.dispatch('moduleReloaded');
            }, 300);
          }
        }, 300);
        //
        //
        //d(refresh_modules11);
        //d(mw.options._bindedRootFormsRegistry);
      }
    });
  },
  tempOption: function tempOption(element, option) {
    var existingOption = [];
    var jsonAttr = element.getAttribute('data-mw-temp-option-save');
    if (jsonAttr) {
      existingOption = JSON.parse(jsonAttr);
    }
    // find by group  and key
    // Specify the group and key to search for
    var searchGroup = option.group;
    var searchKey = option.key;

    // Find the index of the matching object
    var index = existingOption.findIndex(function (item) {
      return item.group === searchGroup && item.key === searchKey;
    });
    if (index !== -1) {
      // Merge the option into the existing object
      existingOption[index] = _objectSpread(_objectSpread({}, existingOption[index]), option);
    } else if (Array.isArray(existingOption)) {
      existingOption.push(option);
    } else {
      existingOption = [option];
    }
    element.setAttribute('data-mw-temp-option-save', JSON.stringify(existingOption));
  },
  publishTempOptions: function publishTempOptions(doc) {
    var optionElements = doc.querySelectorAll('[data-mw-temp-option-save]');
    var optionsToSave = [];
    if (optionElements) {
      for (var i = 0; i < optionElements.length; i++) {
        var optionElement = optionElements[i];
        var jsonAttr = optionElement.getAttribute('data-mw-temp-option-save');
        var optionsAll = JSON.parse(jsonAttr);
        if (optionsAll.length > 0) {
          for (var j = 0; j < optionsAll.length; j++) {
            var option = optionsAll[j];
            optionsToSave.push(option);
          }
        }
        optionElement.removeAttribute('data-mw-temp-option-save');
      }
    }
    if (optionsToSave) {
      for (var i = 0; i < optionsToSave.length; i++) {
        var option = optionsToSave[i];
        this.saveOption(option);
      }
    }
  }
};
mw.options._optionSaved = null;
mw.options._bindedRootFormsRegistry = [];
mw.options.remove_bindings = function ($selector) {
  var $root = mw.$($selector);
  var root = $root[0];
  if (!root) return;
  if (root._optionsEvents) {
    delete root._optionsEvents;
    root._optionsEventsClearBidings = true;
  }
  root.addClass('mw-options-form-force-rebind');
  mw.$("input, select, textarea", root).not('.mw-options-form-binded-custom').each(function () {
    var item = mw.$(this);
    if (item && item[0] && item[0]._optionsEventsBinded) {
      delete item[0]._optionsEventsBinded;
    }
  });
};
mw.options.form = function ($selector, callback, beforepost) {
  //setTimeout(function () {

  var numOfbindigs = 0;
  //   var force_rebind = true;
  var force_rebind = false;
  var $root = mw.$($selector);
  var root = $root[0];
  if (!root) return;

  //
  if (root && $root.hasClass('mw-options-form-force-rebind')) {
    force_rebind = true;
  }
  if (!root._optionsEvents) {
    mw.$("input, select, textarea", root).not('.mw-options-form-binded-custom').each(function () {
      //this._optionSaved = true;

      var item = mw.$(this);
      if (force_rebind) {
        item[0]._optionsEventsBinded = null;
      }
      if (item && item[0] && !item[0]._optionsEventsBinded) {
        if (item.hasClass('mw_option_field')) {
          numOfbindigs++;
          item[0]._optionsEventsBinded = true;
          if (root._optionsEventsClearBidings) {
            item.off('change input paste');
          }
          item.addClass('mw-options-form-binded');
          item.on('change input paste', function (e) {
            var isCheckLike = true;
            var token = isCheckLike ? this.name : this.name + mw.$(this).val();
            mw.options.___slowDownEvent(token, this, function () {
              if (typeof root._optionsEvents.beforepost === 'function') {
                root._optionsEvents.beforepost.call(this);
              }
              if (top !== self && window.mw.parent().drag && window.mw.parent().drag.save) {
                window.mw.parent().drag.save();
              }
              mw.options.save(this, root._optionsEvents.callback);
            });
            //}
          });
        }
      }
    });
  }

  // REBIND
  if (numOfbindigs > 0) {
    root._optionsEvents = root._optionsEvents || {};
    root._optionsEvents = $.extend({}, root._optionsEvents, {
      callback: callback,
      beforepost: beforepost
    });
    var rebind = {};
    if (typeof root._optionsEvents.beforepost === 'function') {
      rebind.beforepost = root._optionsEvents.beforepost;
    }
    rebind.callback = root._optionsEvents.callback;
    rebind.binded_selector = $selector;
    var rebindtemp = mw.tools.cloneObject(rebind);
    //fix here chek if in array

    var is_in = mw.options._bindedRootFormsRegistry.filter(function (a) {
      return a.binded_selector === $selector;
    });
    if (!is_in.length) {
      mw.options._bindedRootFormsRegistry.push(rebindtemp);
    }
  }
  // END OF REBIND

  //}, 10,$selector, callback, beforepost);
};
mw.options.___slowDownEvents = {};
mw.options.___slowDownEvent = function (token, el, call) {
  if (typeof mw.options.___slowDownEvents[token] === 'undefined') {
    mw.options.___slowDownEvents[token] = null;
  }
  clearTimeout(mw.options.___slowDownEvents[token]);
  mw.options.___slowDownEvents[token] = setTimeout(function () {
    call.call(el);
  }, 700);
};
mw.options.___rebindAllFormsAfterReload = function () {
  var token = '___rebindAllFormsAfterReload';
  mw.options.___slowDownEvent(token, this, function () {
    for (var i = 0, l = mw.options._bindedRootFormsRegistry.length; i < l; i++) {
      var binded_root = mw.options._bindedRootFormsRegistry[i];
      if (binded_root.binded_selector) {
        var $root = mw.$(binded_root.binded_selector);
        var root = $root[0];
        if (root) {
          var rebind_beforepost = null;
          var rebind_callback = null;
          if (typeof binded_root.beforepost === 'function') {
            var rebind_beforepost = binded_root.beforepost;
          }
          if (typeof binded_root.callback === 'function') {
            var rebind_callback = binded_root.callback;
          }
          var has_non_binded = false;
          mw.$("input, select, textarea", root).not('.mw-options-form-binded-custom').not('.mw-options-form-binded').each(function () {
            var item = mw.$(this);
            if (item.hasClass('mw_option_field')) {
              if (!item[0]._optionsEventsBinded) {
                has_non_binded = true;
                item.attr('autocomplete', 'off');
              }
            }
          });
          if (root._optionsEvents && has_non_binded && rebind_callback) {
            root._optionsEvents = null;
            root._optionsEventsClearBidings = true;
            mw.options.form(binded_root.binded_selector, rebind_callback, rebind_beforepost);

            // mw.options._bindedRootFormsRegistry =  mw.options._bindedRootFormsRegistry.filter(function (a) {
            //     return a.binded_selector != binded_root.binded_selector
            // })
          }
        }
      }
    }
  });
};
//
// mw.options.___locateModuleNodesToBeRealoaded = function (selectror,window_scope) {
//
//    var module = module.replace(/##/g, '#');
//    var m = mw.$(".module[data-type='" + module + "']");
//    if (m.length === 0) {
//        try { var m = mw.$(module); }  catch(e) {};
//    }
//
//}

/***/ }),

/***/ "./resources/assets/core/reload-module.js":
/*!************************************************!*\
  !*** ./resources/assets/core/reload-module.js ***!
  \************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
mw.reload_module = function (module, callback) {
  if (module.constructor === [].constructor) {
    var l = module.length,
      i = 0,
      w = 1;
    for (; i < l; i++) {
      mw.reload_module(module[i], function () {
        w++;
        if (w === l && typeof callback === 'function') {
          callback.call();
        }
        $(this).trigger('ModuleReload');
      });
    }
    return false;
  }
  var done = callback || function () {};
  if (typeof module !== 'undefined') {
    if (_typeof(module) === 'object') {
      mw._({
        selector: module,
        done: done
      });
    } else {
      var module_name = module.toString();
      var refresh_modules_explode = module_name.split(",");
      for (var i = 0; i < refresh_modules_explode.length; i++) {
        var module = refresh_modules_explode[i];
        if (typeof module != 'undefined') {
          module = module.replace(/##/g, '#');
          var m = mw.$(".module[data-type='" + module + "']");
          if (m.length === 0) {
            try {
              m = $(module);
            } catch (e) {}
            ;
          }
          (function (callback) {
            var count = 0;
            for (var i = 0; i < m.length; i++) {
              mw.reload_module(m[i], function () {
                count++;
                if (count === m.length && typeof callback === 'function') {
                  callback.call();
                }
                $(document).trigger('ModuleReload');
              });
            }
          })(callback);
        }
      }
    }
  }
};
mw.Xreload_module = function (module, callback) {
  console.log(1, 'mw.reload_module');
  if (Array.isArray(module)) {
    var l = module.length,
      i = 0,
      w = 1;
    for (; i < l; i++) {
      mw.reload_module(module[i], function () {
        w++;
        if (w === l && typeof callback === 'function') {
          callback.call();
        }
        $(this).trigger('ModuleReload');

        //
        // if(self !== top && top && top.mw) {
        //     if (module[i] && module[i].id) {
        //         mw.top().app.dispatch('onModuleReloaded', module[i].id);
        //     }
        // }
      });
    }
    return false;
  }
  var currId = false;
  var doc = false;
  var done = callback || function () {};
  if (typeof module !== 'undefined') {
    if (_typeof(module) === 'object') {
      if (mw.top().app && mw.top().app.liveEdit && mw.top().app.liveEdit.handles.get('module')) {
        var curr = mw.top().app.liveEdit.handles.get('module').getTarget();
        if (curr && curr === module) {
          currId = curr.id;
          doc = curr.ownerDocument;
        }
      }
      var xhr = mw._({
        selector: module,
        done: done
      });
      if (xhr) {
        xhr.success(function () {
          if (mw.top().app && mw.top().app.liveEdit && mw.top().app.liveEdit.handles.get('module')) {
            if (doc) {
              var newNode = doc.getElementById(currId);
              if (newNode) {
                mw.top().app.liveEdit.handles.get('module').set(newNode);
                mw.top().app.liveEdit.handles.get('module').position(newNode);
              }
            }
          }
        });
      }
    } else {
      var module_name = module.toString();
      var refresh_modules_explode = module_name.split(",");
      for (var i = 0; i < refresh_modules_explode.length; i++) {
        module = refresh_modules_explode[i];
        if (typeof module != 'undefined') {
          module = module.replace(/##/g, '#');
          var m = mw.$(".module[data-type='" + module + "']");
          if (m.length === 0) {
            try {
              m = $(module);
            } catch (e) {}
            ;
          }
          if (!m.length && typeof callback === 'function') {
            callback.call();
          }
          (function (callback) {
            var count = 0;
            for (var i = 0; i < m.length; i++) {
              mw.reload_module(m[i], function () {
                count++;
                if (count === m.length && typeof callback === 'function') {
                  callback.call();
                }
                $(document).trigger('ModuleReload');
              });
            }
          })(callback);
        }
      }
    }
  } else {
    if (typeof callback === 'function') {
      callback.call();
    }
  }
};

/***/ }),

/***/ "./resources/assets/core/url.js":
/*!**************************************!*\
  !*** ./resources/assets/core/url.js ***!
  \**************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// URL Strings - Manipulations

window.json2url = function (obj) {
  var t = [];
  for (var x in obj) t.push(x + "=" + encodeURIComponent(obj[x]));
  return t.join("&").replace(/undefined/g, 'false');
};
mw.url = {
  hashStart: '',
  getDomain: function getDomain(url) {
    return url.match(/:\/\/(www\.)?(.[^/:]+)/)[2];
  },
  removeHash: function removeHash(url) {
    return url.replace(/#.*/, "");
  },
  getHash: function getHash(url) {
    return url.indexOf('#') != -1 ? url.substring(url.indexOf('#'), url.length) : "";
  },
  strip: function strip(url) {
    return url.replace(/#[^#]*$/, "").replace(/\?[^\?]*$/, "");
  },
  getUrlParams: function getUrlParams(url) {
    url = mw.url.removeHash(url);
    if (url.includes('?')) {
      var arr = url.slice(url.indexOf('?') + 1).split('&');
      var obj = {},
        i = 0,
        len = arr.length;
      for (; i < len; i++) {
        var p_arr = arr[i].split('=');
        obj[p_arr[0]] = p_arr[1];
      }
      return obj;
    } else {
      return {};
    }
  },
  set_param: function set_param(param, value, url) {
    url = url || window.location.href;
    var hash = mw.url.getHash(url);
    var params = mw.url.getUrlParams(url);
    params[param] = value;
    var params_string = json2url(params);
    var url = mw.url.strip(url);
    return decodeURIComponent(url + "?" + params_string + hash);
  },
  remove_param: function remove_param(url, param) {
    var hash = mw.url.getHash(url);
    var params = mw.url.getUrlParams(url);
    delete params[param];
    var params_string = json2url(params);
    url = mw.url.strip(url);
    return decodeURIComponent(url + "?" + params_string + hash);
  },
  getHashParams: function getHashParams(hash) {
    var r = new RegExp(mw.url.hashStart, "g");
    var hash = hash.replace(r, "");
    var hash = hash.replace(/\?/g, "");
    if (hash == '' || hash == '#') {
      return {};
    } else {
      var hash = hash.replace(/#/g, "");
      var arr = hash.split('&');
      var obj = {},
        i = 0,
        len = arr.length;
      for (; i < len; i++) {
        var p_arr = arr[i].split('=');
        obj[p_arr[0]] = p_arr[1];
      }
      return obj;
    }
  },
  setHashParam: function setHashParam(param, value, hash) {
    var hash = hash || mw.hash();
    var obj = mw.url.getHashParams(hash);
    obj[param] = value;
    return mw.url.hashStart + decodeURIComponent(json2url(obj));
  },
  windowHashParam: function windowHashParam(a, b) {
    if (b !== undefined) {
      mw.hash(mw.url.setHashParam(a, b));
    } else {
      return mw.url.getHashParams(mw.hash())[a];
    }
  },
  deleteHashParam: function deleteHashParam(hash, param) {
    var params = mw.url.getHashParams(hash);
    delete params[param];
    var params_string = decodeURIComponent(mw.url.hashStart + json2url(params));
    return params_string;
  },
  windowDeleteHashParam: function windowDeleteHashParam(param) {
    mw.hash(mw.url.deleteHashParam(window.location.hash, param));
  },
  whichHashParamsHasBeenRemoved: function whichHashParamsHasBeenRemoved(currHash, prevHash) {
    var curr = mw.url.getHashParams(currHash);
    var prev = mw.url.getHashParams(prevHash);
    var hashes = [];
    for (var x in prev) {
      curr[x] === undefined ? hashes.push(x) : '';
    }
    return hashes;
  },
  hashParamToActiveNode: function hashParamToActiveNode(param, classNamespace, context) {
    var context = context || document.body;
    var val = mw.url.windowHashParam(param);
    mw.$('.' + classNamespace, context).removeClass('active');
    var active = mw.$('.' + classNamespace + '-' + val, context);
    if (active.length > 0) {
      active.addClass('active');
    } else {
      mw.$('.' + classNamespace + '-none', context).addClass('active');
    }
  },
  mwParams: function mwParams(url) {
    url = url || window.location.pathname;
    url = mw.url.removeHash(url);
    var arr = url.split('/');
    var obj = {};
    var i = 0,
      l = arr.length;
    for (; i < l; i++) {
      if (arr[i].indexOf(':') !== -1 && arr[i].indexOf('http') === -1) {
        var p = arr[i].split(':');
        obj[p[0]] = p[1];
      }
    }
    return obj;
  },
  type: function type(url) {
    if (!url) return;
    url = url.toString();
    if (url === 'false') {
      return false;
    }
    if (url.indexOf('/images.unsplash.com/') !== -1) {
      return 'image';
    }
    var extension = url.split('.').pop();
    var images = 'jpg,png,gif,jpeg,bmp,webp';
    if (images.includes(extension)) {
      return 'image';
    } else if (extension == 'pdf') {
      return 'pdf';
    } else if (url.includes('youtube.com') || url.includes('youtu.be')) {
      return 'youtube';
    } else if (url.includes('vimeo.com')) {
      return 'vimeo';
    } else {
      return 'link';
    }
  }
};
mw.slug = {
  max: 2048,
  normalize: function normalize(string) {
    if (!string) return '';
    string = string.substring(0, mw.slug.max);
    return string.replace(/[`\/~!@#$%^&№€§*()\=?'"<>\{\}\[\]\\]/g, '');
  },
  removeSpecials: function removeSpecials(string) {
    string = mw.slug.normalize(string);
    if (!string) return string;
    var special = 'àáäãâèéëêìíïîòóöôõùúüûñç·=,:;',
      normal = 'aaaaaeeeeiiiiooooouuuunc------',
      len = special.length,
      i = 0;
    for (; i < len; i++) {
      var bad = special[i];
      var good = normal[i];
      string = string.replace(new RegExp(bad, 'g'), good);
    }
    return string;
  },
  create: function create(string) {
    string = string || '';
    string = mw.slug.removeSpecials(string);
    return string.trim().toLowerCase().replace(/[-\s]+/g, '-');
  }
};

/***/ }),

/***/ "./resources/assets/js/admin-tools.service.js":
/*!****************************************************!*\
  !*** ./resources/assets/js/admin-tools.service.js ***!
  \****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AdminTools: () => (/* binding */ AdminTools)
/* harmony export */ });
/* harmony import */ var _tools_domhelpers_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../tools/domhelpers.js */ "./resources/assets/tools/domhelpers.js");
/* harmony import */ var _tools_iframe_auto_height_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../tools/iframe-auto-height.js */ "./resources/assets/tools/iframe-auto-height.js");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function _classPrivateFieldInitSpec(e, t, a) { _checkPrivateRedeclaration(e, t), t.set(e, a); }
function _checkPrivateRedeclaration(e, t) { if (t.has(e)) throw new TypeError("Cannot initialize the same private elements twice on an object"); }
function _classPrivateFieldSet(s, a, r) { return s.set(_assertClassBrand(s, a), r), r; }
function _classPrivateFieldGet(s, a) { return s.get(_assertClassBrand(s, a)); }
function _assertClassBrand(e, t, n) { if ("function" == typeof e ? e === t : e.has(t)) return arguments.length < 3 ? t : n; throw new TypeError("Private element is not present on this object"); }


var _ready = /*#__PURE__*/new WeakMap();
var AdminTools = /*#__PURE__*/function () {
  function AdminTools(app) {
    _classCallCheck(this, AdminTools);
    _classPrivateFieldInitSpec(this, _ready, false);
    this._app = app;
    this.init();
  }
  return _createClass(AdminTools, [{
    key: "init",
    value: function init() {
      if (_classPrivateFieldGet(_ready, this)) {
        return;
      }
      _classPrivateFieldSet(_ready, this, true);
      this.extend(_tools_domhelpers_js__WEBPACK_IMPORTED_MODULE_0__.DomHelpers);
      this.iframeAutoHeight = _tools_iframe_auto_height_js__WEBPACK_IMPORTED_MODULE_1__.iframeAutoHeight;
    }
  }, {
    key: "index",
    value: function index(el, parent, selector) {
      el = mw.$(el)[0];
      selector = selector || el.tagName.toLowerCase();
      parent = parent || el.parentNode;
      var all;
      if (parent.constructor === [].constructor) {
        all = parent;
      } else {
        all = mw.$(selector, parent);
      }
      var i = 0,
        l = all.length;
      for (; i < l; i++) {
        if (el === all[i]) return i;
      }
    }
  }, {
    key: "extend",
    value: function extend() {
      var methods = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
      for (var i in methods) {
        this[i] = methods[i];
      }
    }
  }]);
}();

/***/ }),

/***/ "./resources/assets/js/animations.js":
/*!*******************************************!*\
  !*** ./resources/assets/js/animations.js ***!
  \*******************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
;
(function () {
  mw.__pageAnimations = mw.__pageAnimations || [];
  var prefix = 'animate__';
  var suffix = 'animated';
  var __initialHiddenClass = 'mw-anime--InitialHidden';
  var stop = function stop(target) {
    if (!target) {
      return;
    }
    Array.from(target.classList).filter(function (cls) {
      return cls.indexOf(prefix) === 0;
    }).forEach(function (cls) {
      target.classList.remove(cls);
    });
  };
  var animateCSS = function animateCSS(options) {
    if (!options || !options.animation || options.animation === 'none') {
      return;
    }
    var selector = options.selector,
      removeAtEnd = options.animation,
      animation = options.animation,
      speed = options.speed;
    var cb = options.callback;
    if (typeof speed === 'number') {
      speed = speed + 's';
    }
    var animationName = prefix + animation;
    var node = selector;
    if (typeof selector === 'string') {
      node = document.querySelector(selector);
    }
    if (!node) {
      return;
    }
    node.classList.remove(__initialHiddenClass);
    if (speed) {
      node.style.setProperty('--animate-duration', speed);
    }
    var isInline = getComputedStyle(node).display === 'inline';
    if (isInline) {
      node.style.display = 'inline-block';
      var ms = parseFloat(speed) * 1000;
      setTimeout(function () {
        node.style.display = '';
      }, ms + 10);
    }
    node.classList.add(prefix + suffix, animationName);
    function handleAnimationEnd(event) {
      event.stopPropagation();
      node.classList.remove(prefix + suffix, animationName);
      if (cb) {
        cb.call();
      }
    }
    node.addEventListener('animationend', handleAnimationEnd, {
      once: true
    });
  };
  mw.__animate = animateCSS;
  var __animationTypes = {
    onAppear: function onAppear(data) {
      if ('IntersectionObserver' in window) {
        var filter = function filter(item) {
          return item.when === 'onAppear';
        };
        var nodes = [];
        ;
        (data || []).filter(filter).forEach(function (item) {
          var node = document.querySelector(item.selector);
          if (node) {
            if (!node.$$mwAnimations) {
              node.$$mwAnimations = [];
            }
            var has = node.$$mwAnimations.find(filter);
            if (!has) {
              node.$$mwAnimations.push(item);
              nodes.push(node);
            }
          }
        });
        if (!mw.settings.liveEdit && nodes.length) {
          var observer = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (el) {
              if (!el.target.$$mwAnimationDone && el.isIntersecting) {
                el.target.$$mwAnimationDone = true;
                animateCSS(el.target.$$mwAnimations.find(filter));
              }
            });
          });
          nodes.forEach(function (el) {
            observer.observe(el);
          });
        }
      }
    },
    onHover: function onHover(data) {
      var filter = function filter(item) {
        return item.when === 'onHover';
      };
      (data || []).filter(filter).forEach(function (item) {
        var node = document.querySelector(item.selector);
        if (node) {
          if (!node.$$mwAnimations) {
            node.$$mwAnimations = [];
          }
          var has = node.$$mwAnimations.find(filter);
          if (!has) {
            node.$$mwAnimations.push(item);
            if (!mw.settings.liveEdit) {
              node.addEventListener('mouseenter', function () {
                animateCSS(this.$$mwAnimations.find(filter));
              });
            }
          }
        }
      });
    },
    onClick: function onClick(data) {
      var filter = function filter(item) {
        return item.when === 'onClick';
      };
      (data || []).filter(filter).forEach(function (item) {
        var node = document.querySelector(item.selector);
        if (node) {
          if (!node.$$mwAnimations) {
            node.$$mwAnimations = [];
          }
          var has = node.$$mwAnimations.find(filter);
          if (!has) {
            node.$$mwAnimations.push(item);
            if (!mw.settings.liveEdit) {
              node.addEventListener('click', function () {
                animateCSS(this.$$mwAnimations.find(filter));
              });
            }
          }
        }
      });
    }
  };
  var _animateInit = false;
  window.animateInit = function (data) {
    if (!_animateInit) {
      _animateInit = true;
      var style = document.createElement('style');
      style.innerHTML = '.' + __initialHiddenClass + '{ opacity:0; pointer-events: none; }';
      document.getElementsByTagName('head')[0].appendChild(style);
    }
    data.forEach(function (item) {
      if (item.hidden) {
        var node = document.querySelector(item.selector);
        if (node) {
          node.classList.add(__initialHiddenClass);
        }
      }
    });
    for (var i in __animationTypes) {
      if (__animationTypes.hasOwnProperty(i)) {
        __animationTypes[i](data);
      }
    }
  };
  addEventListener('DOMContentLoaded', function () {
    animateInit(mw.__pageAnimations);
  });
  addEventListener('load', function () {
    animateInit(mw.__pageAnimations);
  });
})();

/***/ }),

/***/ "./resources/assets/js/core.js":
/*!*************************************!*\
  !*** ./resources/assets/js/core.js ***!
  \*************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
//import {MWUniversalContainer} from '../../../input/front-end/js/containers/container.js';
// input/front-endjs/containers/container.js

if (!window.mw) {
  window.mw = {};
}

// window.mw.container = new MWUniversalContainer();

mw.required = [];
mw.require = function (url, inHead, key, defered) {
  if (!url) return;
  var defer;
  if (defered) {
    defer = ' defer ';
  } else {
    defer = '   ';
  }
  if (typeof inHead === 'boolean' || typeof inHead === 'undefined') {
    inHead = inHead || false;
  }
  var keyString;
  if (typeof inHead === 'string') {
    keyString = '' + inHead;
    inHead = key || false;
  }
  if (typeof key === 'string') {
    keyString = key;
  }
  var toPush = url,
    urlModified = false;
  if (!!keyString) {
    toPush = keyString;
    urlModified = true;
  }
  var t = url.split('.').pop();
  url = url.includes('//') ? url : t !== "css" ? mw.settings.includes_url + "api/" + url : mw.settings.includes_url + "css/" + url;
  if (!urlModified) toPush = url;
  if (!~mw.required.indexOf(toPush)) {
    mw.required.push(toPush);
    url = url.includes("?") ? url + '&mwv=' + mw.version : url + "?mwv=" + mw.version;
    if (document.querySelector('link[href="' + url + '"],script[src="' + url + '"]') !== null) {
      return;
    }
    var cssRel = " rel='stylesheet' ";
    var string = t !== "css" ? "<script " + defer + "  src='" + url + "'></script>" : "<link " + cssRel + " href='" + url + "' />";
    if (_typeof($.fn) === 'object') {
      $(document.head).append(string);
    } else {
      var el;
      if (t !== "css") {
        el = document.createElement('script');
        el.src = url;
        el.defer = !!defer;
        el.setAttribute('type', 'text/javascript');
        document.head.appendChild(el);
      } else {
        el = document.createElement('link');
        if (defered) {
          el.as = 'style';
          el.rel = 'preload';
          el.addEventListener('load', function (e) {
            return el.rel = 'stylesheet';
          });
        } else {
          el.rel = 'stylesheet';
        }
        el.href = url;
        document.head.appendChild(el);
      }
    }
  }
};

/***/ }),

/***/ "./resources/assets/libs/jseldom/jseldom-jquery.js":
/*!*********************************************************!*\
  !*** ./resources/assets/libs/jseldom/jseldom-jquery.js ***!
  \*********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/**
Copyright (c) 2010, All Right Reserved, Wong Shek Hei @ shekhei@gmail.com
License: GNU Lesser General Public License (http://www.gnu.org/licenses/lgpl.html)
**/
(function (jQuery) {
  var expr = /[.#\w].([\S]*)/g,
    classexpr = /(\.)([^.#[]+)/g,
    idexpr = /(#)[^.#[]+/,
    tagexpr = /^[\w]+/,
    varexpr = /(\w+?)=(['"])([^\2$]*?)\2/,
    simpleselector = /^[\w]+$/,
    rTrimmer = /^[\s]*[\"\']?|[\"\']?[\s]*$/g,
    rSpaceTrim = /^[\s]+|[\s]+$/g;
  if (!String.prototype.trim) {
    String.prototype.trim = function () {
      return this.replace(rSpaceTrim, '');
    };
  }
  function parseSelector(selector) {
    //var buffer = [];
    var buffer = {
        sel: "",
        val: []
      },
      arr = [],
      isVar = false,
      quote,
      varbuff = "",
      pc = '',
      c = '',
      i,
      selLen = selector.length;
    for (var _i = 0; _i < selLen; _i++) {
      pc = c;
      c = selector.charAt(_i);
      if (isVar) {
        if (c === '\\' && _i + 1 < selLen) {
          varbuff += selector.charAt(++_i);
        } else if (quote === c) {
          quote = undefined;
          varbuff += c;
        } else if ((c === '\'' || c === '\"') && quote === undefined) {
          quote = c;
          varbuff += c;
        } else if (c === ']' && quote === undefined) {
          buffer.val.push(varbuff);
          varbuff = "";
          isVar = false;
        } else if (c !== ']' || quote !== undefined) {
          if (quote === undefined && c === ',') {
            buffer.val.push(varbuff);
            varbuff = "";
          } else {
            varbuff += c;
          }
        }
      } else if (c === '\\' && _i + 1 < selLen) {
        if (isVar) {
          varbuff += selector.charAt(++_i);
        }
      } else if (c === '[' && quote === undefined) {
        isVar = true;
      } else if (c === ' ' || c === '+' || c === "<") {
        // end of a tag, or as a sibling element
        // now lets peek forward
        if (c === " ") {
          for (; c === " " && _i < selLen; _i++) {
            c = selector.charAt(_i);
          }
          if (_i < selLen) {
            --_i;
            c = selector.charAt(_i);
          }
        }
        arr.push(buffer);
        if (c === '+' || c === '<') {
          arr.push({
            sel: c,
            val: ''
          });
          while (_i < selLen && (c = selector.charAt(++_i)) === " ") {}
          if (_i < selLen) {
            c = selector.charAt(--_i);
          }
        }
        buffer = {
          sel: "",
          val: []
        };
      } else if (c !== ' ' && c !== ']') {
        buffer.sel += c;
      }
    }
    if (buffer.sel.length != 0 || buffer.val.length != 0) {
      arr.push(buffer);
    }
    var len = arr.length;
    for (var _i2 = 0; _i2 < len; _i2++) {
      var sel = arr[_i2].sel,
        temp = {};
      if (sel === '+' || sel === "<") {
        temp.tag = sel;
      } else {
        temp.tag = tagexpr.exec(sel);
        temp.id = idexpr.exec(sel);
        if (temp.id && $.isArray(temp.id)) {
          temp.id = temp.id[0].substr(1);
        }
        if (!temp.tag) {
          temp.tag = 'div';
        }
        temp.vars = [];
        var classes = [];
        for (var j = 0; j < arr[_i2].val.length; j++) {
          var index = arr[_i2].val[j].indexOf('=');
          var key = arr[_i2].val[j].substr(0, index).trim();
          var val = arr[_i2].val[j].substr(index + 1);
          val = val.replace(rTrimmer, '');
          if (key === "text") {
            temp.text = val;
          } else if (key === "class" && val.trim().length) {
            classes.push(val);
          } else {
            temp.vars.push([key, val]);
          }
        }
        var arr2 = sel.match(classexpr);
        if (arr2) {
          for (var j = 0; j < arr2.length; j++) {
            classes.push(arr2[j].substr(1));
          }
          temp.className = classes.join(" ");
        }
      }
      arr[_i2] = temp;
    }
    return arr;
  }
  ;
  function nonArrVer(selector, count) {
    var arr = [],
      newel = [];
    if (simpleselector.test(selector)) {
      arr = [{
        tag: selector
      }]; //if it is just a simple tag selector, then there is no need to parse it, makes it much more efficient
    } else {
      arr = parseSelector(selector);
    }
    if (typeof count === "undefined") {
      count = 1;
    }
    var returns = $();
    var parent = [];
    var lastparent = [];
    var tempholder = document.createElement('div');
    //var buff = [];
    var depth = 0,
      i = 0,
      x = 0,
      len = arr.length;
    for (var _i3 = 0; _i3 < len; _i3++) {
      if (arr[_i3].tag == '+' || arr[_i3].tag === '<') {
        parent = lastparent.slice();
        --depth;
      } else {
        for (var _x = 0; _x < count; _x++) {
          if (arr[_i3].tag == 'input') {
            // special case, needs to create tag by html
            var html = [];
            html.push("<" + arr[_i3].tag);
            if (arr[_i3].id) {
              html.push("id=\'" + arr[_i3].id + "\'");
            }
            if (arr[_i3].className || arr[_i3].vars && "class" in arr[_i3].vars) {
              var val = [];
              if (arr[_i3].className) {
                val.push(arr[_i3].className);
              }
              if (arr[_i3].vals["class"]) {
                val.push(arr[_i3].vars["class"]);
              }
              html.push("class=\'" + val.join(" "));
              html.push("\'");
            }
            if (arr[_i3].vars) {
              for (var j = 0; j < arr[_i3].vars.length; j++) {
                html.push(arr[_i3].vars[j][0] + "=\'" + arr[_i3].vars[j][1] + "\'");
              }
            }
            if (arr[_i3].text) {
              html.push("value=\'" + arr[_i3].text + "\'");
            }
            html.push("/>");
            lastparent[_x] = parent[_x];
            if (!parent[_x]) {
              tempholder.innerHTML = html.join(" ");
              parent[_x] = tempholder.removeChild(tempholder.firstChild);
            } else {
              parent[_x].innerHTML = parent[_x].innerHTML + html.join(" ");
              parent[_x] = parent[_x].lastChild;
            }
          } else {
            var buff = document.createElement(arr[_i3].tag);
            if (arr[_i3].vars) {
              for (var j = 0; j < arr[_i3].vars.length; j++) {
                buff.setAttribute(arr[_i3].vars[j][0], arr[_i3].vars[j][1]);
              }
            }
            if (arr[_i3].id) {
              buff.id = arr[_i3].id;
            }
            if (arr[_i3].className) {
              buff.className = arr[_i3].className;
            }
            if (arr[_i3].text) {
              buff.appendChild(document.createTextNode(arr[_i3].text));
            }
            lastparent[_x] = parent[_x];
            if (parent[_x]) {
              parent[_x] = parent[_x].appendChild(buff);
            } else {
              parent[_x] = buff;
            }
          }
        }
        if (!depth++) {
          Array.prototype.push.apply(returns, parent);
        }
      }
      Array.prototype.push.apply(newel, parent);
    }
    return returns;
  }
  ;
  function arrVer(sel, arr, count) {
    var regex = /{[^}]*}/g;
    var vars = sel.match(regex) || [];
    var newel = $();
    for (var i = 0; i < arr.length; i++) {
      var tsel = sel;
      for (var j = 0; j < vars.length; j++) {
        var vname = vars[j].substr(1, vars[j].length - 2).split('.'),
          val = arr[i][vname[0]],
          oldval;
        if (vname[0].trim() === "") {
          val = arr[i].toString();
        } else {
          for (var x = 1; x < vname.length; x++) {
            oldval = val;
            val = val[vname[x]];
            if (!val) {
              val = "";
              break;
            }
            if (typeof val === "function") {
              val = val.call(oldval);
            }
          }
        }
        if ($.isArray(val)) {
          val = val.join(",");
        }
        if (typeof val === "string") {
          val = val.replace(/\\['"\\]|['"]/g, function (s) {
            return "\\" + s;
          });
        } else if (typeof val === "function") {
          val = val.call(oldval);
        }
        val = val || "";
        tsel = tsel.replace(vars[j], val);
      }
      Array.prototype.push.apply(newel, nonArrVer(tsel, count));
      // newel = newel.pushStack(nonArrVer(tsel, count));
    }
    return newel;
  }
  ;
  var isArray = Array.isArray || function (arr) {
    return toString.call(arr) === "[object Array]";
  };
  $.jseldom = function () {
    if (arguments.length == 2 && $.isPlainObject(arguments[1])) {
      return arrVer.apply(this, [arguments[0], [arguments[1]]]);
    } else if (arguments.length == 1 || arguments.length == 2 && !isArray(arguments[1])) {
      return nonArrVer.apply(this, arguments);
    } else if (arguments.length == 2) {
      return arrVer.apply(this, arguments);
    }
  };
  $.jseldomf = function (selector) {
    var args = [selector, [Array.prototype.slice.call(arguments, 1)]];
    return arrVer.apply(this, args);
  };
})(jQuery);

/***/ }),

/***/ "./resources/assets/tools/common-extend.js":
/*!*************************************************!*\
  !*** ./resources/assets/tools/common-extend.js ***!
  \*************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
mw._intervals = {};
mw.interval = function (key, func) {
  if (!key || !func || !!mw._intervals[key]) return;
  mw._intervals[key] = func;
};
mw.removeInterval = function (key) {
  delete mw._intervals[key];
};
setInterval(function () {
  for (var i in mw._intervals) {
    mw._intervals[i].call();
  }
}, 99);
mw.controllers = {};
mw.external_tool = function (url) {
  return !url.includes("/") ? mw.settings.site_url + "editor_tools/" + url : url;
};
mw.postMsg = function (w, obj) {
  w.postMessage(JSON.stringify(obj), window.location.href);
};
mw.uploader = function (o) {
  mw.require("files.js");
  var uploader = mw.files.uploader(o);
  return uploader;
};
mw.fileWindow = function (config) {
  config = config || {};
  config.mode = config.mode || 'dialog'; // 'inline' | 'dialog'
  var q = {
    types: config.types,
    title: config.title
  };
  url = mw.settings.site_url + 'editor_tools/rte_image_editor?' + $.param(q) + '#fileWindow';
  var frameWindow;
  var toreturn = {
    dialog: null,
    root: null,
    iframe: null
  };
  if (config.mode === 'dialog') {
    var modal = mw /*.top()*/.dialogIframe({
      url: url,
      name: "mw_rte_image",
      width: 530,
      height: 'auto',
      autoHeight: true,
      //template: 'mw_modal_basic',
      overlay: true,
      title: mw.lang('Select image')
    });
    var frame = mw.$('iframe', modal.main);
    frameWindow = frame[0].contentWindow;
    toreturn.dialog = modal;
    toreturn.root = frame.parent()[0];
    toreturn.iframe = frame[0];
    frameWindow.onload = function () {
      frameWindow.$('body').on('Result', function (e, url, m) {
        if (config.change) {
          config.change.call(undefined, url);
          modal.remove();
        }
      });
      $(modal).on('Result', function (e, url, m) {
        if (config.change) {
          config.change.call(undefined, url);
          modal.remove();
        }
      });
    };
  } else if (config.mode === 'inline') {
    var fr = document.createElement('iframe');
    fr.src = url;
    fr.frameBorder = 0;
    fr.className = 'mw-file-window-frame';
    toreturn.iframe = fr;
    mw.tools.iframeAutoHeight(fr);
    if (config.element) {
      var $el = $(config.element);
      if ($el.length) {
        toreturn.root = $el[0];
      }
      $el.append(fr);
    }
    fr.onload = function () {
      this.contentWindow.$('body').on('change', function (e, url, m) {
        if (config.change) {
          config.change.call(undefined, url);
        }
      });
    };
  }
  return toreturn;
};
mw.accordion = function (el, callback) {
  return mw.tools.accordion(mw.$(el)[0], callback);
};

/***/ }),

/***/ "./resources/assets/tools/domhelpers.js":
/*!**********************************************!*\
  !*** ./resources/assets/tools/domhelpers.js ***!
  \**********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   DomHelpers: () => (/* binding */ DomHelpers)
/* harmony export */ });
var _this = undefined,
  _DomHelpers;
function _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
var DomHelpers = (_DomHelpers = {
  loops: {},
  stopLoop: function stopLoop(loop) {
    delete _this.loops[loop];
  },
  foreachParents: function foreachParents(el, callback) {
    if (!el) return false;
    var index = mw.random();
    this.loops[index] = true;
    var _curr = el.parentNode;
    var count = -1;
    if (_curr !== null && _curr !== undefined) {
      var _tag = _curr.tagName;
      while (_tag !== 'BODY') {
        count++;
        var caller = callback.call(_curr, index, count);
        _curr = _curr.parentNode;
        if (caller === false || _curr === null || _curr === undefined || !this.loops[index]) {
          delete this.loops[index];
          break;
        }
        _tag = _curr.tagName;
      }
    }
  },
  classNamespaceDelete: function classNamespaceDelete(el_obj, namespace, parent, namespacePosition, exception) {
    var el, exceptions;
    if (el_obj.element && el_obj.namespace) {
      el = el_obj.element;
      namespace = el_obj.namespace;
      parent = el_obj.parent;
      namespacePosition = el_obj.namespacePosition;
      exceptions = el_obj.exceptions || [];
    } else {
      el = el_obj;
      exceptions = [];
    }
    namespacePosition = namespacePosition || 'contains';
    parent = parent || mwd;
    if (el === 'all') {
      var all = parent.querySelectorAll('.edit *'),
        i = 0,
        l = all.length;
      for (; i < l; i++) {
        mw.tools.classNamespaceDelete(all[i], namespace, parent, namespacePosition);
      }
      return;
    }
    if (!!el.className && typeof el.className.split === 'function') {
      var cls = el.className.split(" "),
        l = cls.length,
        i = 0,
        _final2 = [];
      for (; i < l; i++) {
        if (namespacePosition === 'contains') {
          if (!cls[i].includes(namespace) || exceptions.indexOf(cls[i]) !== -1) {
            _final2.push(cls[i]);
          }
        } else if (namespacePosition === 'starts') {
          if (cls[i].indexOf(namespace) !== 0) {
            _final2.push(cls[i]);
          }
        }
      }
      el.className = _final2.join(" ");
    }
  },
  firstWithBackgroundImage: function firstWithBackgroundImage(node) {
    if (!node) return false;
    if (!!node.style.backgroundImage) return node;
    var _final3 = false;
    mw.tools.foreachParents(node, function (loop) {
      if (!!this.style.backgroundImage) {
        mw.tools.stopLoop(loop);
        _final3 = this;
      }
    });
    return _final3;
  },
  parentsOrCurrentOrderMatchOrOnlyFirstOrNone: function parentsOrCurrentOrderMatchOrOnlyFirstOrNone(node, arr) {
    return !mw.tools.hasAnyOfClassesOnNodeOrParent(node, [arr[1]]) || mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node, arr);
  },
  parentsOrCurrentOrderMatchOrOnlyFirst: function parentsOrCurrentOrderMatchOrOnlyFirst(node, arr) {
    var curr = node;
    while (curr && curr.classList) {
      var h1 = mw.tools.hasClass(curr, arr[0]);
      var h2 = mw.tools.hasClass(curr, arr[1]);
      if (h1 && h2) {
        return false;
      } else {
        if (h1) {
          return true;
        } else if (h2) {
          return false;
        }
      }
      curr = curr.parentNode;
    }
    return false;
  }
}, _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_DomHelpers, "parentsOrCurrentOrderMatchOrOnlyFirstOrNone", function parentsOrCurrentOrderMatchOrOnlyFirstOrNone(node, arr) {
  var curr = node;
  while (curr && curr.classList) {
    var h1 = mw.tools.hasClass(curr, arr[0]);
    var h2 = mw.tools.hasClass(curr, arr[1]);
    if (h1 && h2) {
      return false;
    } else {
      if (h1) {
        return true;
      } else if (h2) {
        return false;
      }
    }
    curr = curr.parentNode;
  }
  return true;
}), "parentsOrCurrentOrderMatch", function parentsOrCurrentOrderMatch(node, arr) {
  var curr = node,
    match = {
      a: 0,
      b: 0
    },
    count = 1,
    hadA = false;
  while (curr && curr.classList) {
    count++;
    var h1 = mw.tools.hasClass(curr, arr[0]);
    var h2 = mw.tools.hasClass(curr, arr[1]);
    if (h1 && h2) {
      if (match.a > 0) {
        return true;
      }
      return false;
    } else {
      if (h1) {
        match.a = count;
        hadA = true;
      } else if (h2) {
        match.b = count;
      }
      if (match.b > match.a) {
        return hadA ? true : false;
      }
    }
    curr = curr.parentNode;
  }
  return false;
}), "parentsOrCurrentOrderMatchOrNone", function parentsOrCurrentOrderMatchOrNone(node, arr) {
  if (!node) return false;
  var curr = node,
    match = {
      a: 0,
      b: 0
    },
    count = 1,
    hadA = false;
  while (curr && curr.classList) {
    count++;
    var h1 = mw.tools.hasClass(curr, arr[0]);
    var h2 = mw.tools.hasClass(curr, arr[1]);
    if (h1 && h2) {
      if (match.a > 0) {
        return true;
      }
      return false;
    } else {
      if (h1) {
        match.a = count;
        hadA = true;
      } else if (h2) {
        match.b = count;
      }
      if (match.b > match.a) {
        return hadA ? true : false;
      }
    }
    curr = curr.parentNode;
  }
  return match.a === 0 && match.b === 0;
}), "parentsOrCurrentOrderMatchOrOnlyFirstOrBoth", function parentsOrCurrentOrderMatchOrOnlyFirstOrBoth(node, arr) {
  var curr = node,
    has1 = false,
    has2 = false;
  while (curr && curr.classList) {
    var h1 = mw.tools.hasClass(curr, arr[0]);
    var h2 = mw.tools.hasClass(curr, arr[1]);
    if (h1 && h2) {
      return true;
    } else {
      if (h1) {
        return true;
      } else if (h2) {
        return false;
      }
    }
    curr = curr.parentNode;
  }
  return false;
}), "matchesAnyOnNodeOrParent", function matchesAnyOnNodeOrParent(node, arr) {
  var curr = node;
  while (curr && curr.classList) {
    var i = 0;
    for (; i < arr.length; i++) {
      if (mw.tools.matches(curr, arr[i])) {
        return true;
      }
    }
    curr = curr.parentNode;
  }
  return false;
}), "firstMatchesOnNodeOrParent", function firstMatchesOnNodeOrParent(node, arr) {
  if (!arr) return;
  if (typeof arr === 'string') {
    arr = [arr];
  }
  var curr = node;
  while (curr && curr.classList) {
    var i = 0;
    for (; i < arr.length; i++) {
      if (mw.tools.matches(curr, arr[i])) {
        return curr;
      }
    }
    curr = curr.parentNode;
  }
  return false;
}), "lastMatchesOnNodeOrParent", function lastMatchesOnNodeOrParent(node, arr) {
  if (!arr) return;
  if (typeof arr === 'string') {
    arr = [arr];
  }
  var curr = node,
    result;
  while (curr && curr.classList) {
    var i = 0;
    for (; i < arr.length; i++) {
      if (mw.tools.matches(curr, arr[i])) {
        result = curr;
      }
    }
    curr = curr.parentNode;
  }
  return result;
}), "hasAnyOfClassesOnNodeOrParent", function hasAnyOfClassesOnNodeOrParent(node, arr) {
  var curr = node;
  while (curr && curr.classList) {
    var i = 0;
    for (; i < arr.length; i++) {
      if (mw.tools.hasClass(curr, arr[i])) {
        return true;
      }
    }
    curr = curr.parentNode;
  }
  return false;
}), "hasClass", function hasClass(classname, whattosearch) {
  if (classname === null) {
    return false;
  }
  if (typeof classname === 'string') {
    return classname.split(' ').indexOf(whattosearch) > -1;
  } else if (_typeof(classname) === 'object') {
    return mw.tools.hasClass(classname.className, whattosearch);
  } else {
    return false;
  }
}), "hasAllClasses", function hasAllClasses(node, arr) {
  if (!node) return;
  var has = true;
  var i = 0,
    nodec = node.className.trim().split(' ');
  for (; i < arr.length; i++) {
    if (nodec.indexOf(arr[i]) === -1) {
      return false;
    }
  }
  return has;
}), _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_DomHelpers, "hasAnyOfClasses", function hasAnyOfClasses(node, arr) {
  if (!node) return;
  var i = 0,
    l = arr.length,
    cls = node.className;
  for (; i < l; i++) {
    if (mw.tools.hasClass(cls, arr[i])) {
      return true;
    }
  }
  return false;
}), "hasParentsWithClass", function hasParentsWithClass(el, cls) {
  if (!el) return;
  var curr = el.parentNode;
  while (curr && curr.classList) {
    if (mw.tools.hasClass(curr, cls)) {
      return true;
    }
    curr = curr.parentNode;
  }
  return false;
}), "hasParentWithId", function hasParentWithId(el, id) {
  if (!el) return;
  var curr = el.parentNode;
  while (curr && curr.classList) {
    if (curr.id === id) {
      return true;
    }
    curr = curr.parentNode;
  }
  return false;
}), "hasChildrenWithTag", function hasChildrenWithTag(el, tag) {
  tag = tag.toLowerCase();
  var has = false;
  mw.tools.foreachChildren(el, function (loop) {
    if (this.nodeName.toLowerCase() === tag) {
      has = true;
      mw.tools.stopLoop(loop);
    }
  });
  return has;
}), "hasParentsWithTag", function hasParentsWithTag(el, tag) {
  if (!el || !tag) return;
  tag = tag.toLowerCase();
  var curr = el.parentNode;
  while (curr && curr.classList) {
    if (curr.nodeName.toLowerCase() === tag) {
      return true;
    }
    curr = curr.parentNode;
  }
  return false;
}), "hasHeadingParent", function hasHeadingParent(el) {
  if (!el) return;
  var h = /^(h[1-6])$/i;
  var curr = el.parentNode;
  while (curr && curr.classList) {
    if (h.test(curr.nodeName.toLowerCase())) {
      return true;
    }
    curr = curr.parentNode;
  }
  return false;
}), "addClass", function addClass(el, cls) {
  if (!cls || !el) {
    return false;
  }
  if (el.fn) {
    el = el[0];
    if (!el) {
      return;
    }
  }
  if (typeof cls === 'string') {
    cls = cls.trim();
  }
  if (!el) return;
  var arr = cls.split(" ");
  var i = 0;
  if (arr.length > 1) {
    for (; i < arr.length; i++) {
      mw.tools.addClass(el, arr[i]);
    }
    return;
  }
  if (_typeof(el) === 'object') {
    if (el.classList) {
      el.classList.add(cls);
    } else {
      if (!mw.tools.hasClass(el.className, cls)) el.className += ' ' + cls;
    }
  }
  if (typeof el === 'string') {
    if (!mw.tools.hasClass(el, cls)) el += ' ' + cls;
  }
}), "removeClass", function removeClass(el, cls) {
  if (typeof cls === 'string') {
    cls = cls.trim();
  }
  if (!cls || !el) return;
  if (el === null) {
    return false;
  }
  if (el.fn) {
    el = el[0];
    if (!el) {
      return;
    }
  }
  if (typeof el === 'undefined') {
    return false;
  }
  if (el.constructor === [].constructor) {
    var i = 0,
      l = el.length;
    for (; i < l; i++) {
      mw.tools.removeClass(el[i], cls);
    }
    return;
  }
  if (_typeof(cls) === 'object') {
    var arr = cls;
  } else {
    var arr = cls.split(" ");
  }
  var i = 0;
  if (arr.length > 1) {
    for (; i < arr.length; i++) {
      mw.tools.removeClass(el, arr[i]);
    }
    return;
  } else if (!arr.length) {
    return;
  }
  if (el.classList && cls) {
    el.classList.remove(cls);
  } else {
    if (mw.tools.hasClass(el.className, cls)) el.className = (el.className + ' ').replace(cls + ' ', '').replace(/\s{2,}/g, ' ').trim();
  }
}), "isEventOnElement", function isEventOnElement(event, node) {
  if (event.target === node) {
    return true;
  }
  mw.tools.foreachParents(event.target, function () {
    if (event.target === node) {
      return true;
    }
  });
  return false;
}), "isEventOnElements", function isEventOnElements(event, array) {
  var l = array.length,
    i = 0;
  for (; i < l; i++) {
    if (event.target === array[i]) {
      return true;
    }
  }
  var isEventOnElements = false;
  mw.tools.foreachParents(event.target, function () {
    var l = array.length,
      i = 0;
    for (; i < l; i++) {
      if (event.target === array[i]) {
        isEventOnElements = true;
      }
    }
  });
  return isEventOnElements;
}), _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_DomHelpers, "isEventOnClass", function isEventOnClass(event, cls) {
  if (mw.tools.hasClass(event.target, cls) || mw.tools.hasParentsWithClass(event.target, cls)) {
    return true;
  }
  return false;
}), "firstChildWithClass", function firstChildWithClass(parent, cls) {
  var toreturn;
  mw.tools.foreachChildren(parent, function (loop) {
    if (this.nodeType === 1 && mw.tools.hasClass(this, cls)) {
      mw.tools.stopLoop(loop);
      toreturn = this;
    }
  });
  return toreturn;
}), "firstChildWithTag", function firstChildWithTag(parent, tag) {
  var toreturn;
  var tag = tag.toLowerCase();
  mw.tools.foreachChildren(parent, function (loop) {
    if (this.nodeName.toLowerCase() === tag) {
      toreturn = this;
      mw.tools.stopLoop(loop);
    }
  });
  return toreturn;
}), "hasChildrenWithClass", function hasChildrenWithClass(node, cls) {
  var _final4 = false;
  mw.tools.foreachChildren(node, function () {
    if (mw.tools.hasClass(this.className, cls)) {
      _final4 = true;
    }
  });
  return _final4;
}), "parentsOrder", function parentsOrder(node, arr) {
  var only_first = [];
  var obj = {},
    l = arr.length,
    i = 0,
    count = -1;
  for (; i < l; i++) {
    obj[arr[i]] = -1;
  }
  if (!node) return obj;
  var curr = node.parentNode;
  while (curr && curr.classList) {
    count++;
    var cls = curr.className;
    i = 0;
    for (; i < l; i++) {
      if (mw.tools.hasClass(cls, arr[i]) && only_first.indexOf(arr[i]) === -1) {
        obj[arr[i]] = count;
        only_first.push(arr[i]);
      }
    }
    curr = curr.parentNode;
  }
  return obj;
}), "parentsAndCurrentOrder", function parentsAndCurrentOrder(node, arr) {
  var only_first = [];
  var obj = {},
    l = arr.length,
    i = 0,
    count = -1;
  for (; i < l; i++) {
    obj[arr[i]] = -1;
  }
  if (!node) return obj;
  var curr = node;
  while (curr && curr.classList) {
    count++;
    var cls = curr.className;
    i = 0;
    for (; i < l; i++) {
      if (mw.tools.hasClass(cls, arr[i]) && only_first.indexOf(arr[i]) === -1) {
        obj[arr[i]] = count;
        only_first.push(arr[i]);
      }
    }
    curr = curr.parentNode;
  }
  return obj;
}), "firstParentWithClass", function firstParentWithClass(el, cls) {
  if (!el) return false;
  var curr = el.parentNode;
  while (curr && curr.classList) {
    if (curr.classList.contains(cls)) {
      return curr;
    }
    curr = curr.parentNode;
  }
  return false;
}), "firstParentOrCurrentWithClass", function firstParentOrCurrentWithClass(el, cls) {
  if (!el) return false;
  var curr = el;
  while (curr && curr.classList) {
    if (mw.tools.hasClass(curr, cls)) {
      return curr;
    }
    curr = curr.parentNode;
  }
  return false;
}), "firstBlockLikeLevel", function firstBlockLikeLevel(el) {
  var blockLikes = ['TD', 'DIV', 'LI', 'DD', 'DT', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'MAIN', 'HEADER', 'P'];
  while (el && el.classList) {
    if (mw.tools.isBlockLevel(el) || blockLikes.indexOf(el.nodeName) !== -1) {
      return el;
    }
    el = el.parentNode;
  }
}), "firstBlockLevel", function firstBlockLevel(el) {
  while (el && el.classList) {
    if (mw.tools.isBlockLevel(el)) {
      return el;
    }
    el = el.parentNode;
  }
}), _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_DomHelpers, "firstNotInlineLevel", function firstNotInlineLevel(el) {
  if (el.nodeType !== 1) {
    el = el.parentNode;
  }
  if (!el) {
    return;
  }
  while (el && el.classList) {
    if (!mw.tools.isInlineLevel(el)) {
      return el;
    }
    el = el.parentNode;
  }
}), "firstParentOrCurrentWithId", function firstParentOrCurrentWithId(el, id) {
  if (!el) return false;
  var curr = el;
  while (curr && el.classList) {
    if (curr.id === id) {
      return curr;
    }
    curr = curr.parentNode;
  }
  return false;
}), "firstParentOrCurrentWithAllClasses", function firstParentOrCurrentWithAllClasses(node, arr) {
  if (!node) return false;
  var curr = node;
  while (curr && curr.classList) {
    if (mw.tools.hasAllClasses(curr, arr)) {
      return curr;
    }
    curr = curr.parentNode;
  }
  return false;
}), "firstParentOrCurrentWithAnyOfClasses", function firstParentOrCurrentWithAnyOfClasses(node, arr) {
  if (!node) return false;
  var curr = node;
  while (curr && curr.classList) {
    if (!curr) return false;
    if (mw.tools.hasAnyOfClasses(curr, arr)) {
      return curr;
    }
    curr = curr.parentNode;
  }
  return false;
}), "lastParentWithClass", function lastParentWithClass(el, cls) {
  if (!el) return;
  var _has = false;
  var curr = el.parentNode;
  while (curr && curr.classList) {
    if (mw.tools.hasClass(curr, cls)) {
      _has = curr;
    }
    curr = curr.parentNode;
  }
  return _has;
}), "firstParentWithTag", function firstParentWithTag(el, tag) {
  if (!el || !tag) return;
  tag = typeof tag !== 'string' ? tag : [tag];
  var curr = el.parentNode;
  while (curr && curr.classList) {
    if (tag.indexOf(curr.nodeName.toLowerCase()) !== -1) {
      return curr;
    }
    curr = curr.parentNode;
  }
  return false;
}), "firstParentOrCurrentWithTag", function firstParentOrCurrentWithTag(el, tag) {
  if (!el || !tag) return;
  tag = typeof tag !== 'string' ? tag : [tag];
  var curr = el;
  while (curr && curr.classList) {
    if (tag.indexOf(curr.nodeName.toLowerCase()) !== -1) {
      return curr;
    }
    curr = curr.parentNode;
  }
  return false;
}), "generateSelectorForNode", function generateSelectorForNode(node, strict) {
  if (typeof strict === 'undefined') {
    strict = true;
  }
  if (node === null || node.nodeType === 3) {
    return false;
  }
  if (node.nodeName === 'BODY') {
    return 'body';
  }
  if (mw.tools.hasClass(node, 'edit')) {
    var field = node.getAttribute('field');
    var rel = node.getAttribute('rel');
    if (field && rel) {
      return '.edit[field="' + field + '"][rel="' + rel + '"]';
    }
  }
  if (strict && !node.id) {
    if (node && node.classList && !node.classList.contains('edit') && mw.tools.isEditable(node)) {
      node.id = mw.id('mw-element-');
    }
  }
  if (!!node.id /*&& node.id.indexOf('element_') === -1*/) {
    return '#' + node.id;
  }
  var filter = function filter(item) {
    return item !== 'changed' && item !== 'module-over' && item !== 'mw-bg-mask' && item !== 'element-current';
  };
  if (node.className && typeof node.className.trim === 'function') {
    var _final = node.className.trim() ? '.' + node.className.trim().split(' ').filter(filter).join('.') : node.nodeName.toLocaleLowerCase();
  } else {
    var _final = node.nodeName.toLocaleLowerCase();
  }
  _final = _final.replace(/\.\./g, '.');
  mw.tools.foreachParents(node, function (loop) {
    if (this.id /*&& node.id.indexOf('element_') === -1*/) {
      _final = '#' + this.id + ' > ' + _final;
      mw.tools.stopLoop(loop);
      return false;
    }
    var n;
    if (this && this.className && typeof this.className.trim === 'function') {
      n = this.nodeName.toLocaleLowerCase() + '.' + this.className.trim().split(' ').join('.');
    } else {
      n = this.nodeName.toLocaleLowerCase();
    }
    _final = n + ' > ' + _final;
  });
  return _final.replace(/.changed/g, '').replace(/.element-current/g, '').replace(/.module-over/g, '');
}));
if (window.mw && window.mw.tools) {
  for (var i in DomHelpers) {
    mw.tools[i] = DomHelpers[i];
  }
}

/***/ }),

/***/ "./resources/assets/tools/element.js":
/*!*******************************************!*\
  !*** ./resources/assets/tools/element.js ***!
  \*******************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
(function () {
  var _MWElement = function MWElement(options, root) {
    var scope = this;
    this.toggle = function () {
      this.css('display', this.css('display') === 'none' ? 'block' : 'none');
    };
    this._active = function () {
      return this.nodes[this.nodes.length - 1];
    };
    this.getDocument = function () {
      return this._active().ownerDocument;
    };
    this.getWindow = function () {
      return this.getDocument().defaultView;
      ;
    };
    this.get = function (selector, scope) {
      this.nodes = (scope || document).querySelectorAll(selector);
    };
    this.each = function (cb) {
      if (this.nodes) {
        for (var i = 0; i < this.nodes.length; i++) {
          cb.call(this.nodes[i], i);
        }
      } else if (this.node) {
        cb.call(this.node, 0);
      }
      return this;
    };
    this.scrollTop = function (val) {
      if (typeof val === 'undefined') {
        return this._active().scrollTop;
      }
      return this.each(function () {
        this.scrollTop = val;
      });
    };
    this.encapsulate = function () {};
    this.create = function () {
      var _options = {};
      if (this.settings.is) {
        _options.is = this.settings.is;
      }
      var el = this.document.createElement(this.settings.tag, _options);
      this.node = el;

      /*if (this.settings.encapsulate) {
          var mode = this.settings.encapsulate === true ? 'open' : this.settings.encapsulate;
          el.attachShadow({
              mode: mode
          });
      }*/

      this.nodes = [el];
      if (this.settings.content) {
        if (Array.isArray(this.settings.content)) {
          this.settings.content.forEach(function (el) {
            if (Object.getPrototypeOf(el) === Object.prototype) {
              scope.append(new _MWElement(el));
            } else {
              scope.append(el);
            }
          });
        } else if (this.settings.content instanceof _MWElement) {
          this.append(this.settings.content);
        } else if (_typeof(this.settings.content) === 'object') {
          this.append(new _MWElement(this.settings.content));
        } else if (typeof this.settings.content === 'string') {
          this.get(0).innerHTML = this.settings.content;
        }
      }
      this.$node = $(el);
    };
    this._specialProps = function (dt, val) {
      if (dt === 'tooltip') {
        this.node.dataset[dt] = val;
        return true;
      }
    };
    this.setProps = function () {
      for (var i in this.settings.props) {
        if (i === 'dataset') {
          for (var dt in this.settings.props[i]) {
            this.node.dataset[dt] = this.settings.props[i][dt];
          }
        } else if (i === 'style') {
          for (var st in this.settings.props[i]) {
            this.node.style[st] = this.settings.props[i][st];
          }
        } else {
          var val = this.settings.props[i];
          if (!this._specialProps(i, val)) {
            this.node[i] = val;
          }
        }
      }
    };
    this.__ = {
      cssNumber: ['animationIterationCount', 'columnCount', 'fillOpacity', 'flexGrow', 'flexShrink', 'fontWeight', 'gridArea', 'gridColumn', 'gridColumnEnd', 'gridColumnStart', 'gridRow', 'gridRowEnd', 'gridRowStart', 'lineHeight', 'opacity', 'order', 'orphans', 'widows', 'zIndex', 'zoom']
    };
    this._normalizeCSSValue = function (prop, val) {
      if (typeof val === 'number') {
        if (this.__.cssNumber.indexOf(prop) === -1) {
          val = val + 'px';
        }
      }
      return val;
    };
    this.css = function (css, val) {
      if (typeof css === 'string') {
        if (typeof val !== 'undefined') {
          var nval = this._normalizeCSSValue(css, val);
          this.each(function () {
            this.style[css] = nval;
          });
        } else {
          return this.document.defaultView.getComputedStyle(this.node)[css];
        }
      }
      if (_typeof(css) === 'object') {
        for (var i in css) {
          this.each(function () {
            this.style[i] = scope._normalizeCSSValue(i, css[i]);
          });
        }
      }
      return this;
    };
    this.dataset = function (prop, val) {
      if (typeof val === 'undefined') {
        return this._active()[prop];
      }
      this.each(function () {
        this.dataset[prop] = val;
      });
      return this;
    };
    this.attr = function (prop, val) {
      if (typeof val === 'undefined') {
        return this._active()[prop];
      }
      this.each(function () {
        this.setAttribute(prop, val);
      });
      return this;
    };
    this.focus = function () {
      this._active().focus();
      return this;
    };
    this.val = function (val) {
      if (typeof val === 'undefined') {
        return this._active().value;
      }
      this.each(function () {
        this.value = val;
      });
      return this;
    };
    this.prop = function (prop, val) {
      var active = this._active();
      if (!active) {
        return;
      }
      if (typeof val === 'undefined') {
        return active[prop];
      }
      if (active[prop] !== val) {
        active[prop] = val;
        this.trigger('propChange', [prop, val]);
      }
      return this;
    };
    this.hide = function () {
      return this.each(function () {
        this.style.display = 'none';
      });
    };
    this.show = function () {
      return this.each(function () {
        this.style.display = '';
      });
    };
    this.find = function (sel) {
      var el = mw.element('#r' + new Date().getTime());
      this.each(function () {
        var all = this.querySelectorAll(sel);
        for (var i = 0; i < all.length; i++) {
          if (el.nodes.indexOf(all[i]) === -1) {
            el.nodes.push(all[i]);
          }
        }
      });
      return el;
    };
    var prepareClasses = function prepareClasses() {
      var classes = [];
      Array.from(arguments).forEach(function (arg) {
        Array.from(arg).forEach(function (arg) {
          var arr;
          if (Array.isArray(arg)) {
            arr = arg;
          } else {
            arr = arg.split(' ');
          }
          arr.forEach(function (cls) {
            cls = cls.trim();
            if (!!cls) {
              classes.push(cls);
            }
          });
        });
      });
      return classes;
    };
    this.hasClass = function (c) {
      var active = this._active();
      if (active) {
        return active.classList.contains(c);
      }
      return false;
    };
    this.addClass = function () {
      var classes = prepareClasses(arguments);
      return this.each(function () {
        var node = this;
        classes.forEach(function (cls) {
          node.classList.add(cls);
        });
      });
    };
    this.toggleClass = function () {
      var classes = prepareClasses(arguments);
      return this.each(function () {
        var node = this;
        classes.forEach(function (cls) {
          node.classList.toggle(cls);
        });
      });
    };
    this.removeClass = function () {
      var classes = prepareClasses(arguments);
      return this.each(function () {
        var node = this;
        classes.forEach(function (cls) {
          node.classList.remove(cls);
        });
      });
    };
    this.remove = function () {
      return this.each(function () {
        this.remove();
      });
    };
    this.empty = function () {
      return this.html('');
    };
    this.html = function (val) {
      if (typeof val === 'undefined') {
        return this._active().innerHTML;
      }
      return this.each(function () {
        this.innerHTML = val;
      });
    };
    this.text = function (val, clean) {
      if (typeof val === 'undefined') {
        return this.node.textContent;
      }
      if (typeof clean === 'undefined') {
        clean = true;
      }
      if (clean) {
        val = this.document.createRange().createContextualFragment(val).textContent;
      }
      // this.node.innerHTML = val;
      return this.each(function () {
        this.textContent = val;
      });
    };
    this._asdom = function (obj) {
      if (typeof obj === 'string') {
        return this.document.createRange().createContextualFragment(obj);
      } else if (obj.node) {
        return obj.node;
      } else if (obj.nodes) {
        return obj.nodes[obj.nodes.length - 1];
      } else {
        return obj;
      }
    };
    this.offset = function () {
      if (this._active()) {
        var win = this.getWindow();
        var rect = this._active().getBoundingClientRect();
        rect.offsetTop = rect.top + win.pageYOffset;
        rect.offsetBottom = rect.bottom + win.pageYOffset;
        rect.offsetLeft = rect.left + win.pageXOffset;
        return rect;
      }
    };
    this.width = function (val) {
      if (val) {
        return this.css('width', val);
      }
      return this._active().offsetWidth;
    };
    this.height = function (val) {
      if (val) {
        return this.css('height', val);
      }
      return this._active().offsetHeight;
    };
    this.parent = function () {
      return mw.element(this._active().parentNode);
    };
    this.next = function () {
      return mw.element(this._active().nextElementSibling);
    };
    this.prev = function () {
      return mw.element(this._active().previousElementSibling);
    };
    this.parents = function (selector) {
      selector = selector || '*';
      var el = this._active();
      var curr = el.parentElement;
      var res = mw.element();
      res.nodes = [];
      while (curr) {
        if (curr.matches(selector)) {
          res.nodes.push(curr);
        }
        curr = curr.parentElement;
      }
      return res;
    };
    this.append = function (el) {
      if (el) {
        this.each(function () {
          this.append(scope._asdom(el));
        });
      }
      return this;
    };
    this.before = function (el) {
      if (el) {
        this.each(function () {
          if (this.parentNode) {
            this.parentNode.insertBefore(scope._asdom(el), this);
          }
        });
      }
      return this;
    };
    this.after = function (el) {
      if (el) {
        this.each(function () {
          if (this.parentNode) {
            this.parentNode.insertBefore(scope._asdom(el), this.nextSibling);
          }
        });
      }
    };
    this.prepend = function (el) {
      if (el) {
        this.each(function () {
          this.prepend(scope._asdom(el));
        });
      }
      return this;
    };
    this._disabled = false;
    Object.defineProperty(this, "disabled", {
      get: function get() {
        return this._disabled;
      },
      set: function set(value) {
        this._disabled = value;
        this.node.disabled = this._disabled;
        this.node.dataset.disabled = this._disabled;
      }
    });
    this.trigger = function (event, data) {
      data = data || {};
      this.each(function () {
        this.dispatchEvent(new CustomEvent(event, {
          detail: data,
          cancelable: true,
          bubbles: true
        }));
        if (scope._on[event]) {
          scope._on[event].forEach(function (cb) {
            cb.call(this, event, data);
          });
        }
      });
      return this;
    };
    this.get = function (i) {
      return this.nodes[i];
    };
    this._on = {};
    this.on = function (events, cb) {
      events = events.trim().split(' ');
      events.forEach(function (ev) {
        if (!scope._on[ev]) {
          scope._on[ev] = [];
        }
        scope._on[ev].push(cb);
        scope.each(function () {
          /*this.addEventListener(ev, function(e) {
              cb.call(scope, e, e.detail, this);
          }, false);*/
          this.addEventListener(ev, cb, false);
        });
      });
      return this;
    };
    this.init = function () {
      this.nodes = [];
      var _root = root || document;
      if (_root.get) {
        _root = _root.get(0);
      }
      this.root = _root;
      this._asElement = false;
      this.document = this.root.body ? this.root : this.root.ownerDocument;
      options = options || {};
      if (options.nodeName && options.nodeType) {
        this.nodes.push(options);
        this.node = options;
        options = {};
        this._asElement = true;
      } else if (typeof options === 'string') {
        if (options.indexOf('<') === -1) {
          this.nodes = Array.prototype.slice.call(this.root.querySelectorAll(options));
          options = {};
          this._asElement = true;
        } else {
          var el = this._asdom(options);
          this.nodes = [].slice.call(el.children);
          this._asElement = true;
        }
      }
      options = options || {};
      var defaults = {
        tag: 'div',
        props: {}
      };
      this.settings = $.extend({}, defaults, options);
      if (this._asElement) return;
      this.create();
      this.setProps();
    };
    this.init();
  };
  mw.element = function (options, root) {
    return new _MWElement(options, root);
  };
  mw.element.module = function (name, func) {
    _MWElement.prototype[name] = func;
  };
})();

/***/ }),

/***/ "./resources/assets/tools/iframe-auto-height.js":
/*!******************************************************!*\
  !*** ./resources/assets/tools/iframe-auto-height.js ***!
  \******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   iframeAutoHeight: () => (/* binding */ iframeAutoHeight)
/* harmony export */ });
var iframeAutoHeight = function iframeAutoHeight(frame, opt) {
  opt = opt || {};
  opt.maxHeightWindowScroll = opt.maxHeightWindowScroll || null;
  frame = mw.$(frame)[0];
  if (!frame) return;
  if (frame.dataset.autoHeight === 'false') return;
  var _detector = document.createElement('div');
  _detector.className = 'mw-iframe-auto-height-detector';
  _detector.id = mw.id();
  var insertDetector = function insertDetector() {
    if (frame.contentWindow && frame.contentWindow.document && frame.contentWindow.document.body) {
      var det = frame.contentWindow.document.querySelector('.mw-iframe-auto-height-detector');
      if (!det) {
        frame.contentWindow.document.body.appendChild(_detector);
      } else if (det !== frame.contentWindow.document.body.lastChild) {
        frame.contentWindow.document.body.appendChild(det);
      }
      if (frame.contentWindow.mw) {
        frame.contentWindow.mw._iframeDetector = _detector;
      }
    }
  };
  setTimeout(function () {
    insertDetector();
  }, 100);
  frame.style.minHeight = 0 + 'px';
  if (opt.maxHeightWindowScroll) {
    frame.style.maxHeight = opt.maxHeightWindowScroll;
    frame.style.overflow = 'auto';
    frame.scrolling = "auto";
  } else {
    frame.scrolling = "auto";
    frame.style.overflow = 'hidden';
  }
  mw.$(frame).on('load resize', function () {
    if (!mw.tools.canAccessIFrame(frame)) {
      console.log('Iframe can not be accessed.', frame);
      return;
    }
    if (!frame.contentWindow.document.body) {
      return;
    }
    if (!!frame.contentWindow.document.querySelector('.mw-iframe-auto-height-detector')) {
      return;
    }
    insertDetector();
    if (opt.maxHeightWindowScroll) {
      frame.style.maxHeight = opt.maxHeightWindowScroll;
      frame.style.overflow = 'auto';
      frame.scrolling = "auto";
    } else {
      frame.scrolling = "auto";
      frame.style.overflow = 'hidden';
    }
  });
  var offset = function offset() {
    return _detector.getBoundingClientRect().top;
  };
  frame._intPause = false;
  frame._int = setInterval(function () {
    if (!frame._intPause && frame.parentNode && frame.contentWindow && frame.contentWindow.document.body) {
      var calc = offset() + _detector.offsetHeight;
      frame._currHeight = frame._currHeight || 0;
      if (calc && calc !== frame._currHeight) {
        frame._currHeight = calc;
        frame.style.height = Math.max(calc) + 'px';
        var scroll = Math.max(frame.contentWindow.document.documentElement.scrollHeight, frame.contentWindow.document.body.scrollHeight);
        if (scroll > frame._currHeight) {
          frame._currHeight = scroll;
          frame.style.height = scroll + 'px';
        }
        mw.$(frame).trigger('bodyResize');
      }
    } else if (!frame.parentElement) {
      clearInterval(frame._int);
    } else {
      //clearInterval(frame._int);
    }
  }, 77);
};

/***/ }),

/***/ "./resources/assets/tools/loading.js":
/*!*******************************************!*\
  !*** ./resources/assets/tools/loading.js ***!
  \*******************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Loading: () => (/* binding */ Loading),
/* harmony export */   Progress: () => (/* binding */ Progress)
/* harmony export */ });
var progressDefaults = {
  skin: 'mw-ui-progress',
  action: "".concat(mw.lang('Loading'), "..."),
  progress: 0
};
var Progress = function Progress(obj) {
  if (typeof obj.element === 'string') {
    obj.element = mw.$(obj.element)[0];
  }
  if (obj.element === null || !obj.element) return false;
  if (obj.element.querySelector('.mw-ui-progress-bar')) {
    obj.element.progressOptions.show();
    return obj.element.progressOptions;
  }
  obj = $.extend({}, progressDefaults, obj);
  if (obj.progress > 100) {
    obj.progress = 100;
  }
  if (obj.progress < 0) {
    obj.progress = 0;
  }
  var progress = document.createElement('div');
  progress.className = obj.skin;
  progress.innerHTML = '<div class="mw-ui-progress-bar" style="width: ' + obj.progress + '%;"></div><div class="mw-ui-progress-info">' + obj.action + '</div><span class="mw-ui-progress-percent">' + obj.progress + '%</span>';
  progress.progressInfo = obj;
  var options = {
    progress: progress,
    show: function show() {
      this.progress.style.display = '';
    },
    hide: function hide() {
      this.progress.style.display = 'none';
    },
    remove: function remove() {
      progress.progressInfo.element.progressOptions = undefined;
      mw.$(this.progress).remove();
    },
    set: function set(v, action) {
      if (v > 100) {
        v = 100;
      }
      if (v < 0) {
        v = 0;
      }
      action = action || this.progress.progressInfo.action;
      mw.$('.mw-ui-progress-bar', this.progress).css('width', v + '%');
      mw.$('.mw-ui-progress-percent', this.progress).html(v + '%');
      progress.progressInfo.element.progressOptions.show();
    }
  };
  progress.progressOptions = obj.element.progressOptions = options;
  obj.element.appendChild(progress);
  return options;
};
var Loading = function Loading(element, progress, speed) {
  /*
     progress:number 0 - 100,
   speed:string, -> 'slow', 'normal, 'fast'
     mw.tools.loading(true) -> slowly animates to 95% on body
   mw.tools.loading(false) -> fast animates to 100% on body
     */
  function set(el, progress, speed) {
    speed = speed || 'normal';
    mw.tools.removeClass(el, 'mw-progress-slow');
    mw.tools.removeClass(el, 'mw-progress-normal');
    mw.tools.removeClass(el, 'mw-progress-fast');
    mw.tools.addClass(el, 'mw-progress-' + speed);
    element.__loadingTime = setTimeout(function () {
      el.querySelector('.mw-progress-index').style.width = progress + '%';
    }, 10);
  }
  if (typeof element === 'boolean') {
    progress = !!element;
    element = document.body;
  }
  if (typeof element === 'number') {
    progress = element;
    element = document.body;
  }
  if (element === document || element === document.documentElement) {
    element = document.body;
  }
  element = mw.$(element)[0];
  if (element === null || !element) return false;
  if (element.__loadingTime) {
    clearTimeout(element.__loadingTime);
  }
  var isLoading = mw.tools.hasClass(element, 'mw-loading');
  var el = element.querySelector('.mw-progress');
  if (!el) {
    el = document.createElement('div');
    el.className = 'mw-progress';
    el.innerHTML = '<div class="mw-progress-index"></div>';
    if (element === document.body) el.style.position = 'fixed';
    element.appendChild(el);
  }
  if (progress === 'hide') {
    el.remove();
    return;
  }
  var pos = getComputedStyle(element).position;
  if (pos === 'static') {
    element.style.position = 'relative';
  }
  if (progress) {
    if (progress === true) {
      set(el, 95, speed || 'slow');
    } else if (typeof progress === 'number') {
      progress = progress <= 100 ? progress : 100;
      progress = progress >= 0 ? progress : 0;
      set(el, progress, speed);
    }
  } else {
    if (el) {
      set(el, 100, speed || 'fast');
    }
    element.__loadingTime = setTimeout(function () {
      mw.$(element).removeClass('mw-loading-defaults mw-loading');
      mw.$(el).remove();
    }, 700);
  }
};

/***/ }),

/***/ "./resources/assets/tools/spinner.js":
/*!*******************************************!*\
  !*** ./resources/assets/tools/spinner.js ***!
  \*******************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
mw.Spinner = function (options) {
  if (!options || !options.element) {
    return;
  }
  this.$element = $(options.element);
  if (!this.$element.length) return;
  this.element = this.$element[0];
  if (this.element._mwSpinner) {
    return this.element._mwSpinner;
  }
  this.element._mwSpinner = this;
  this.options = options;
  this.options.size = this.options.size || 20;
  this.options.color = this.options.color || '#4592ff';
  this.options.insertMode = this.options.insertMode || 'append';
  this.color = function (val) {
    if (!val) {
      return this.options.color;
    }
    this.options.color = val;
    this.$spinner.find('circle').css({
      stroke: this.options.color
    });
  };
  this.size = function (val) {
    if (!val) {
      return this.options.size;
    }
    this.options.size = parseFloat(val);
    this.$spinner.css({
      width: this.options.size,
      height: this.options.size
    });
  };
  this.setState = function (state) {
    mw.tools.classNamespaceDelete(this.$spinner[0], 'mw-spinner-state-');
    mw.tools.addClass(this.$spinner[0], 'mw-spinner-state-' + state);
  };
  this.create = function () {
    this.$spinner = $('<div class="mw-spinner mw-spinner-mode-' + this.options.insertMode + '" style="display: none;"><svg viewBox="0 0 50 50"><circle cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle><path class="mw-spinner-checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/></svg></div>');
    this.size(this.options.size);
    this.color(this.options.color);
    this.$element[this.options.insertMode](this.$spinner);
    this.show();
    return this;
  };
  this.show = function () {
    this.$spinner.show();
    this.$element.addClass('has-mw-spinner');
    if (this.options.decorate) {
      this.$element.addClass('mw-spinner-decorate');
    }
    return this;
  };
  this.hide = function () {
    this.$spinner.hide();
    this.$element.removeClass('has-mw-spinner');
    if (this.options.decorate) {
      this.$element.removeClass('mw-spinner-decorate');
    }
    return this;
  };
  this.remove = function () {
    this.hide();
    this.$spinner.remove();
    delete this.element._mwSpinner;
  };
  this.create().show();
};
mw.spinner = function (options) {
  return new mw.Spinner(options);
};

/***/ }),

/***/ "./resources/assets/tools/storage.js":
/*!*******************************************!*\
  !*** ./resources/assets/tools/storage.js ***!
  \*******************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
mw.storage = {
  init: function init() {
    try {
      if (window.location.href.indexOf('data:') === 0 || !('localStorage' in window) || /* IE Security configurations */typeof window['localStorage'] === 'undefined') return false;
      var lsmw = localStorage.getItem("mw");
      if (typeof lsmw === 'undefined' || lsmw === null) {
        lsmw = localStorage.setItem("mw", "{}");
      }
      this.change("INIT");
      return lsmw;
    } catch (error) {
      console.log(error);
    }
  },
  identity: function identity() {
    if (!mw._storageIdentity) {
      mw._storageIdentity = 'mw-' + Date.now();
    }
    return mw._storageIdentity;
  },
  rootIdentity: function rootIdentity() {
    if (!mw.top()._storageIdentity) {
      mw.top()._storageIdentity = 'mw-' + Date.now();
    }
    return mw.top()._storageIdentity;
  },
  set: function set(key, val) {
    try {
      if (!('localStorage' in window)) return false;
      var curr = JSON.parse(localStorage.getItem("mw"));
      curr[key] = val;
      var a = localStorage.setItem("mw", JSON.stringify(curr));
      mw.storage.change("CALL", key, val);
      return a;
    } catch (error) {
      console.log(error);
    }
  },
  get: function get(key) {
    try {
      if (!('localStorage' in window)) return false;
      var curr = JSON.parse(localStorage.getItem("mw"));
      return curr[key];
    } catch (error) {
      console.log(error);
    }
  },
  _keys: {},
  change: function change(key, callback, other) {
    if (!('localStorage' in window)) return false;
    if (key === 'INIT' && 'addEventListener' in document) {
      addEventListener('storage', function (e) {
        if (e.key === 'mw') {
          var _new = JSON.parse(e.newValue || {});
          var _old = JSON.parse(e.oldValue || {});
          var diff = mw.tools.getDiff(_new, _old);
          for (var t in diff) {
            if (t in mw.storage._keys) {
              var i = 0,
                l = mw.storage._keys[t].length;
              for (; i < l; i++) {
                mw.storage._keys[t][i].call(undefined, diff[t]);
              }
            }
          }
        }
      }, false);
    } else if (key === 'CALL') {
      if (!document.hidden && typeof mw.storage._keys[callback] !== 'undefined') {
        var i = 0,
          l = mw.storage._keys[callback].length;
        for (; i < l; i++) {
          mw.storage._keys[callback][i].call(other);
        }
      }
    } else {
      if (key in mw.storage._keys) {
        mw.storage._keys[key].push(callback);
      } else {
        mw.storage._keys[key] = [callback];
      }
    }
  }
};
mw.storage.init();

/***/ }),

/***/ "./resources/assets/tools/tabs.js":
/*!****************************************!*\
  !*** ./resources/assets/tools/tabs.js ***!
  \****************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
mw.tabs = function (obj, element, model) {
  /*
  *
  *  {
  *       linkable: 'link' | 'auto',
  *       nav: string
  *       tabs: string
  *       onclick: function
  *  }
  *
  * */
  element = element || document.body;
  model = typeof model === 'undefined' ? true : model;
  if (model) {
    model = {
      set: function set(i) {
        if (typeof i === 'number') {
          if (!$(obj.nav).eq(i).hasClass(active)) {
            mw.$(obj.nav).removeClass(active);
            mw.$(obj.nav).eq(i).addClass(active);
            mw.$(obj.tabs).hide().eq(i).show();
          }
        }
      },
      setLastClicked: function setLastClicked() {
        if (typeof obj.lastClickedTabIndex != 'undefined' && obj.lastClickedTabIndex !== null) {
          this.set(obj.lastClickedTabIndex);
        }
      },
      unset: function unset(i) {
        if (typeof i === 'number') {
          if ($(obj.nav).eq(i).hasClass(active)) {
            mw.$(obj.nav).eq(i).removeClass(active);
            mw.$(obj.tabs).hide().eq(i).hide();
          }
        }
      },
      toggle: function toggle(i) {
        if (typeof i === 'number') {
          if ($(obj.nav).eq(i).hasClass(active)) {
            this.unset(i);
          } else {
            this.set(i);
          }
        }
      }
    };
  }
  var active = obj.activeNav || obj.activeClass || "active active-info",
    firstActive = 0;
  obj.lastClickedTabIndex = null;
  if (obj.linkable) {
    if (obj.linkable === 'link') {} else if (typeof obj.linkable === 'string') {
      $(window).on('load hashchange', function () {
        var param = mw.url.windowHashParam(obj.linkable);
        if (param) {
          var el = $('[data-' + obj.linkable + '="' + param + '"]');
        }
      });
      $(obj.nav).each(function (i) {
        this.dataset.linkable = obj.linkable + '-' + i;
        (function (linkable, i) {
          this.onclick = function () {
            mw.url.windowHashParam(linkable, i);
          };
        })(obj.linkable, i);
      });
    }
  }
  mw.$(obj.nav).on('click', function (e) {
    if (obj.linkable) {
      if (obj.linkable === 'link') {}
    } else {
      if (!$(this).hasClass(active)) {
        var i = mw.tools.index(this, mw.$(obj.nav).get(), mw.$(obj.nav)[0].nodeName);
        mw.$(obj.nav).removeClass(active);
        mw.$(this).addClass(active);
        mw.$(obj.tabs).hide().eq(i).show();
        obj.lastClickedTabIndex = i;
        if (typeof obj.onclick === 'function') {
          obj.onclick.call(this, mw.$(obj.tabs).eq(i)[0], e, i);
        }
      } else {
        if (obj.toggle === true) {
          mw.$(this).removeClass(active);
          mw.$(obj.tabs).hide();
          if (typeof obj.onclick === 'function') {
            var i = mw.tools.index(this, element, obj.nav);
            obj.onclick.call(this, mw.$(obj.tabs).eq(i)[0], e, i);
          }
        }
      }
    }
    return false;
  }).each(function (i) {
    if (mw.tools.hasClass(this, active)) {
      firstActive = i;
    }
  });
  model.set(firstActive);
  return model;
};

/***/ }),

/***/ "./resources/assets/widgets/hamburger.js":
/*!***********************************************!*\
  !*** ./resources/assets/widgets/hamburger.js ***!
  \***********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
var MWSiteMobileMenuService = /*#__PURE__*/function () {
  function MWSiteMobileMenuService() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    _classCallCheck(this, MWSiteMobileMenuService);
    _defineProperty(this, "state", false);
    _defineProperty(this, "currentMenu", null);
    var defaults = {
      popupTemplate: 'mw-vhmbgr-active-popup'
    };
    this.settings = Object.assign({}, defaults, options);
    this.init();
  }
  return _createClass(MWSiteMobileMenuService, [{
    key: "buildSubMenus",
    value: function buildSubMenus(ul) {
      var holder = document.querySelector('.navigation-holder');
      var cacltop = "".concat(holder ? holder.offsetHeight : 0, "px");
      var maxHeight = "calc(100vh - ".concat(cacltop, ")");
      ul.querySelectorAll('ul').forEach(function (node) {
        var li = node.parentNode;
        var btn = document.createElement("span");
        btn.className = 'mw-vhmbgr-submenu-toggle-button';
        btn.addEventListener("click", function (e) {
          li.classList.toggle('mw-vhmbgr-submenu-active');
          node.style.maxHeight = li.classList.contains('mw-vhmbgr-submenu-active') ? maxHeight : '';
          e.preventDefault();
          e.stopPropagation();
        });
        li.prepend(btn);
      });
    }
  }, {
    key: "buildMobileMenu",
    value: function buildMobileMenu(targetMenu) {
      var _this = this;
      if (this.currentMenu) {
        this.currentMenu.remove();
      }
      var ul = document.createElement('ul');
      if (ul && targetMenu.querySelector('ul')) {
        ul.innerHTML = targetMenu.querySelector('ul').innerHTML;
        ul.querySelectorAll('[style],[class]').forEach(function (node) {
          node.removeAttribute('style');
          node.removeAttribute('class');
        });
        var block = document.createElement('div');
        this.$block = block;
        var ovl = document.createElement('div');
        ovl.className = 'mw-vhmbgr-active-overlay';
        block.className = this.settings.popupTemplate;
        this.currentMenu = block;
        ovl.addEventListener('click', function (e) {
          _this.mobileMenu(undefined, false);
        });
        this.buildSubMenus(ul);
        block.append(ul);
        document.body.append(ovl);
        document.body.append(block);
      }
    }
  }, {
    key: "mobileMenu",
    value: function mobileMenu(node, state) {
      var action = 'toggle';
      if (state === true) {
        action = 'add';
      } else if (state === false) {
        action = 'remove';
      }
      if (node) {
        node.classList[action]('mw-vhmbgr-active');
      } else {
        Array.from(document.querySelectorAll('.mw-vhmbgr')).forEach(function (node) {
          node.classList[action]('mw-vhmbgr-active');
        });
      }
      var holder = document.querySelector('.navigation-holder');
      this.$block.style.top = "".concat(holder ? holder.offsetHeight : 0, "px");
      this.$block.style.maxHeight = "calc(100vh - ".concat(this.$block.style.top, ")");
      document.body.classList[action]('mw-vhmbgr-menu-active');
    }
  }, {
    key: "init",
    value: function init() {
      var _this2 = this;
      document.body.addEventListener('click', function (e) {
        _this2.mobileMenu(undefined, false);
      });
      var nav = document.querySelector('.mw-vhmbgr--navigation');
      if (nav) {
        this.buildMobileMenu(nav);
      }
      Array.from(document.querySelectorAll('.mw-vhmbgr')).forEach(function (node) {
        node.addEventListener('click', function (e) {
          _this2.mobileMenu(node);
          e.preventDefault();
          e.stopPropagation();
        });
      });
    }
  }]);
}();
var MWSiteMobileMenu = function MWSiteMobileMenu(options) {
  var hamburger = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 5;
  options.threshold = options.threshold || 800;
  options.color = options.color || '#111';
  options.size = options.size || '55px';
  var hamburgers = ["\n        <svg class=\"mw-vhmbgr mw-vhmbgrRotate mw-vhmbgr1\" viewBox=\"0 0 100 100\"><path class=\"mw-vhmbgr--line mw-vhmbgr--top\" d=\"m 30,33 h 40 c 0,0 9.044436,-0.654587 9.044436,-8.508902 0,-7.854315 -8.024349,-11.958003 -14.89975,-10.85914 -6.875401,1.098863 -13.637059,4.171617 -13.637059,16.368042 v 40\"/><path class=\"mw-vhmbgr--line mw-vhmbgr--middle\" d=\"m 30,50 h 40\"/><path class=\"mw-vhmbgr--line mw-vhmbgr--bottom\" d=\"m 30,67 h 40 c 12.796276,0 15.357889,-11.717785 15.357889,-26.851538 0,-15.133752 -4.786586,-27.274118 -16.667516,-27.274118 -11.88093,0 -18.499247,6.994427 -18.435284,17.125656 l 0.252538,40\"/></svg>\n        ", "\n        <svg class=\"mw-vhmbgr mw-vhmbgr2\" viewBox=\"0 0 100 100\"><path class=\"mw-vhmbgr--line mw-vhmbgr--top\" d=\"m 70,33 h -40 c -6.5909,0 -7.763966,-4.501509 -7.763966,-7.511428 0,-4.721448 3.376452,-9.583771 13.876919,-9.583771 14.786182,0 11.409257,14.896182 9.596449,21.970818 -1.812808,7.074636 -15.709402,12.124381 -15.709402,12.124381\"/><path class=\"mw-vhmbgr--line mw-vhmbgr--middle\" d=\"m 30,50 h 40\"/><path class=\"mw-vhmbgr--line mw-vhmbgr--bottom\" d=\"m 70,67 h -40 c -6.5909,0 -7.763966,4.501509 -7.763966,7.511428 0,4.721448 3.376452,9.583771 13.876919,9.583771 14.786182,0 11.409257,-14.896182 9.596449,-21.970818 -1.812808,-7.074636 -15.709402,-12.124381 -15.709402,-12.124381\"/></svg>\n        ", "\n        <svg class=\"mw-vhmbgr mw-vhmbgrRotate mw-vhmbgr4\" viewBox=\"0 0 100 100\"><path class=\"mw-vhmbgr--line mw-vhmbgr--top\" d=\"m 70,33 h -40 c 0,0 -8.5,-0.149796 -8.5,8.5 0,8.649796 8.5,8.5 8.5,8.5 h 20 v -20\"/><path class=\"mw-vhmbgr--line mw-vhmbgr--middle\" d=\"m 70,50 h -40\"/><path class=\"mw-vhmbgr--line mw-vhmbgr--bottom\" d=\"m 30,67 h 40 c 0,0 8.5,0.149796 8.5,-8.5 0,-8.649796 -8.5,-8.5 -8.5,-8.5 h -20 v 20\"/></svg>\n        ", "\n        <svg class=\"mw-vhmbgr mw-vhmbgrRotate180 mw-vhmbgr5\" viewBox=\"0 0 100 100\"><path class=\"mw-vhmbgr--line mw-vhmbgr--top\" d=\"m 30,33 h 40 c 0,0 8.5,-0.68551 8.5,10.375 0,8.292653 -6.122707,9.002293 -8.5,6.625 l -11.071429,-11.071429\"/><path class=\"mw-vhmbgr--line mw-vhmbgr--middle\" d=\"m 70,50 h -40\"/><path class=\"mw-vhmbgr--line mw-vhmbgr--bottom\" d=\"m 30,67 h 40 c 0,0 8.5,0.68551 8.5,-10.375 0,-8.292653 -6.122707,-9.002293 -8.5,-6.625 l -11.071429,11.071429\"/></svg>\n        ", "\n        <svg class=\"mw-vhmbgr mw-vhmbgr6\" viewBox=\"0 0 100 100\"><path class=\"mw-vhmbgr--line mw-vhmbgr--top\" d=\"m 30,33 h 40 c 13.100415,0 14.380204,31.80258 6.899646,33.421777 -24.612039,5.327373 9.016154,-52.337577 -12.75751,-30.563913 l -28.284272,28.284272\"/><path class=\"mw-vhmbgr--line mw-vhmbgr--middle\" d=\"m 70,50 c 0,0 -32.213436,0 -40,0 -7.786564,0 -6.428571,-4.640244 -6.428571,-8.571429 0,-5.895471 6.073743,-11.783399 12.286435,-5.570707 6.212692,6.212692 28.284272,28.284272 28.284272,28.284272\"/><path class=\"mw-vhmbgr--line mw-vhmbgr--bottom\" d=\"m 69.575405,67.073826 h -40 c -13.100415,0 -14.380204,-31.80258 -6.899646,-33.421777 24.612039,-5.327373 -9.016154,52.337577 12.75751,30.563913 l 28.284272,-28.284272\"/></svg>\n        ", "\n        <svg class=\"mw-vhmbgr mw-vhmbgrRotate mw-vhmbgr7\" viewBox=\"0 0 100 100\"><path class=\"mw-vhmbgr--line mw-vhmbgr--top\" d=\"m 70,33 h -40 c 0,0 -6,1.368796 -6,8.5 0,7.131204 6,8.5013 6,8.5013 l 20,-0.0013\"/><path class=\"mw-vhmbgr--line mw-vhmbgr--middle\" d=\"m 70,50 h -40\"/><path class=\"mw-vhmbgr--line mw-vhmbgr--bottom\" d=\"m 69.575405,67.073826 h -40 c -5.592752,0 -6.873604,-9.348582 1.371031,-9.348582 8.244634,0 19.053564,21.797129 19.053564,12.274756 l 0,-40\"/></svg>\n        ", "\n        <svg class=\"mw-vhmbgr mw-vhmbgrRotate mw-vhmbgr8\" viewBox=\"0 0 100 100\"><path class=\"mw-vhmbgr--line mw-vhmbgr--top\" d=\"m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20\"/><path class=\"mw-vhmbgr--line mw-vhmbgr--middle\" d=\"m 30,50 h 40\"/><path class=\"mw-vhmbgr--line mw-vhmbgr--bottom\" d=\"m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20\"/></svg>\n\n        "];
  var curr = document.getElementById('mw-vhmbgr--style');
  if (curr) {
    curr.remove();
  }
  var css = document.createElement('style');
  css.id = "mw-vhmbgr--style";
  css.textContent = "\n\n\n\n   .mw-vhmbgr-active-popup,\n.mw-vhmbgr-active-overlay{\n  opacity: 0;\n  visibility: hidden;\n  pointer-events: none;\n  transition: .5s;\n}\nbody.mw-vhmbgr-menu-active .mw-vhmbgr-active-popup,\nbody.mw-vhmbgr-menu-active .mw-vhmbgr-active-overlay{\n  opacity: 1;\n  visibility: visible;\n  pointer-events: all;\n}\n\n\n.mw-vhmbgr-active-popup li{\n    list-style: none;\n  }\n  .mw-vhmbgr-active-popup  > ul{\n    max-height: calc(100vh - 200px);\n    overflow: auto;\n    padding: 20px;\n  }\n  .mw-vhmbgr-active-popup{\n    position: fixed;\n    z-index: 51;\n    top: 100px;\n    left: 50%;\n    transform: translateX(-50%);\n    background-color: #fff;\n    box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;\n    min-width: 300px;\n\n  }\n  .mw-vhmbgr-active-overlay{\n    position: fixed;\n    z-index: 50;\n    top: 0;\n    left: 0;\n    width: 100%;\n    height: 100%;\n    background-color: rgba(0,0,0,.2);\n  }\n\n\n.mw-vhmbgr {\n  cursor: pointer;\n  -webkit-tap-highlight-color: transparent;\n  transition: transform 400ms;\n  -moz-user-select: none;\n  -webkit-user-select: none;\n  -ms-user-select: none;\n  user-select: none;\n  width: var(--size);\n  color: var(--color);\n}\n\n\n.mw-vhmbgrRotate.mw-vhmbgr-active {\n  transform: rotate(45deg);\n}\n.mw-vhmbgrRotate180.mw-vhmbgr-active {\n  transform: rotate(180deg);\n}\n.mw-vhmbgr--line {\n  fill:none;\n  transition: stroke-dasharray 400ms, stroke-dashoffset 400ms;\n  stroke: currentColor;\n  stroke-width:5.5;\n  stroke-linecap:round;\n}\n.mw-vhmbgr1 .mw-vhmbgr--top {\n  stroke-dasharray: 40 139;\n}\n.mw-vhmbgr1 .mw-vhmbgr--bottom {\n  stroke-dasharray: 40 180;\n}\n.mw-vhmbgr1.mw-vhmbgr-active .mw-vhmbgr--top {\n  stroke-dashoffset: -98px;\n}\n.mw-vhmbgr1.mw-vhmbgr-active .mw-vhmbgr--bottom {\n  stroke-dashoffset: -138px;\n}\n.mw-vhmbgr2 .mw-vhmbgr--top {\n  stroke-dasharray: 40 121;\n}\n.mw-vhmbgr2 .mw-vhmbgr--bottom {\n  stroke-dasharray: 40 121;\n}\n.mw-vhmbgr2.mw-vhmbgr-active .mw-vhmbgr--top {\n  stroke-dashoffset: -102px;\n}\n.mw-vhmbgr2.mw-vhmbgr-active .mw-vhmbgr--bottom {\n  stroke-dashoffset: -102px;\n}\n.mw-vhmbgr3 .mw-vhmbgr--top {\n  stroke-dasharray: 40 130;\n}\n.mw-vhmbgr3 .mw-vhmbgr--middle {\n  stroke-dasharray: 40 140;\n}\n.mw-vhmbgr3 .mw-vhmbgr--bottom {\n  stroke-dasharray: 40 205;\n}\n.mw-vhmbgr3.mw-vhmbgr-active .mw-vhmbgr--top {\n  stroke-dasharray: 75 130;\n  stroke-dashoffset: -63px;\n}\n.mw-vhmbgr3.mw-vhmbgr-active .mw-vhmbgr--middle {\n  stroke-dashoffset: -102px;\n}\n.mw-vhmbgr3.mw-vhmbgr-active .mw-vhmbgr--bottom {\n  stroke-dasharray: 110 205;\n  stroke-dashoffset: -86px;\n}\n.mw-vhmbgr4 .mw-vhmbgr--top {\n  stroke-dasharray: 40 121;\n}\n.mw-vhmbgr4 .mw-vhmbgr--bottom {\n  stroke-dasharray: 40 121;\n}\n.mw-vhmbgr4.mw-vhmbgr-active .mw-vhmbgr--top {\n  stroke-dashoffset: -68px;\n}\n.mw-vhmbgr4.mw-vhmbgr-active .mw-vhmbgr--bottom {\n  stroke-dashoffset: -68px;\n}\n.mw-vhmbgr5 .mw-vhmbgr--top {\n  stroke-dasharray: 40 82;\n}\n.mw-vhmbgr5 .mw-vhmbgr--bottom {\n  stroke-dasharray: 40 82;\n}\n.mw-vhmbgr5.mw-vhmbgr-active .mw-vhmbgr--top {\n  stroke-dasharray: 14 82;\n  stroke-dashoffset: -72px;\n}\n.mw-vhmbgr5.mw-vhmbgr-active .mw-vhmbgr--bottom {\n  stroke-dasharray: 14 82;\n  stroke-dashoffset: -72px;\n}\n.mw-vhmbgr6 .mw-vhmbgr--top {\n  stroke-dasharray: 40 172;\n}\n.mw-vhmbgr6 .mw-vhmbgr--middle {\n  stroke-dasharray: 40 111;\n}\n.mw-vhmbgr6 .mw-vhmbgr--bottom {\n  stroke-dasharray: 40 172;\n}\n.mw-vhmbgr6.mw-vhmbgr-active .mw-vhmbgr--top {\n  stroke-dashoffset: -132px;\n}\n.mw-vhmbgr6.mw-vhmbgr-active .mw-vhmbgr--middle {\n  stroke-dashoffset: -71px;\n}\n.mw-vhmbgr6.mw-vhmbgr-active .mw-vhmbgr--bottom {\n  stroke-dashoffset: -132px;\n}\n.mw-vhmbgr7 .mw-vhmbgr--top {\n  stroke-dasharray: 40 82;\n}\n.mw-vhmbgr7 .mw-vhmbgr--middle {\n  stroke-dasharray: 40 111;\n}\n.mw-vhmbgr7 .mw-vhmbgr--bottom {\n  stroke-dasharray: 40 161;\n}\n.mw-vhmbgr7.mw-vhmbgr-active .mw-vhmbgr--top {\n  stroke-dasharray: 17 82;\n  stroke-dashoffset: -62px;\n}\n.mw-vhmbgr7.mw-vhmbgr-active .mw-vhmbgr--middle {\n  stroke-dashoffset: 23px;\n}\n.mw-vhmbgr7.mw-vhmbgr-active .mw-vhmbgr--bottom {\n  stroke-dashoffset: -83px;\n}\n.mw-vhmbgr8 .mw-vhmbgr--top {\n  stroke-dasharray: 40 160;\n}\n.mw-vhmbgr8 .mw-vhmbgr--middle {\n  stroke-dasharray: 40 142;\n  transform-origin: 50%;\n  transition: transform 400ms;\n}\n.mw-vhmbgr8 .mw-vhmbgr--bottom {\n  stroke-dasharray: 40 85;\n  transform-origin: 50%;\n  transition: transform 400ms, stroke-dashoffset 400ms;\n}\n.mw-vhmbgr8.mw-vhmbgr-active .mw-vhmbgr--top {\n  stroke-dashoffset: -64px;\n}\n.mw-vhmbgr8.mw-vhmbgr-active .mw-vhmbgr--middle {\n\n  transform: rotate(90deg);\n}\n.mw-vhmbgr8.mw-vhmbgr-active .mw-vhmbgr--bottom {\n  stroke-dashoffset: -64px;\n}\n\n.mw-vhmbgr-wrapper{\n  position: relative;\n  display: block;\n  width: var(--size);\n  height: var(--size);\n  overflow: hidden;\n}\n\n.mw-vhmbgr-wrapper > svg{\n  position: absolute;\n  width: 200%;\n  height: 200%;\n  left: -50%;\n  top: -50%;\n}\n\n\n   .mw-vhmbgr, .mw-vhmbgr-wrapper {\n      --size: ".concat(options.size, ";\n      --color: ").concat(options.color, "\n    }\n\n   .mw-vhmbgr-wrapper{\n    display: none;\n   }\n    @media (max-width: ").concat(options.threshold, "px) {\n      .mw-vhmbgr--navigation{\n        display: none;\n      }\n      .mw-vhmbgr-wrapper{\n        display: block;\n      }\n    }\n\n   ");
  document.body.append(css);
  document.querySelectorAll('.mw-vhmbgr--navigation').forEach(function (node) {
    var mobileMenu = document.createElement('span');
    mobileMenu.className = 'mw-vhmbgr-wrapper';
    mobileMenu.innerHTML = hamburgers[hamburger];
    node.after(mobileMenu);
  });
  new MWSiteMobileMenuService(options);
};
if (window.mw) {
  window.mw.MWSiteMobileMenu = MWSiteMobileMenu;
}

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
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
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*****************************************!*\
  !*** ./resources/assets/js/frontend.js ***!
  \*****************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _core_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./core.js */ "./resources/assets/js/core.js");
/* harmony import */ var _core_core_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../core/@core.js */ "./resources/assets/core/@core.js");
/* harmony import */ var _core_modules_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../core/modules.js */ "./resources/assets/core/modules.js");
/* harmony import */ var _core_url_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../core/url.js */ "./resources/assets/core/url.js");
/* harmony import */ var _core_objects_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../core/objects.js */ "./resources/assets/core/objects.js");
/* harmony import */ var _core_events_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../core/events.js */ "./resources/assets/core/events.js");
/* harmony import */ var _core_reload_module_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../core/reload-module.js */ "./resources/assets/core/reload-module.js");
/* harmony import */ var _widgets_hamburger_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../widgets/hamburger.js */ "./resources/assets/widgets/hamburger.js");
/* harmony import */ var _tools_element_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../tools/element.js */ "./resources/assets/tools/element.js");
/* harmony import */ var _tools_spinner_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../tools/spinner.js */ "./resources/assets/tools/spinner.js");
/* harmony import */ var _tools_storage_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../tools/storage.js */ "./resources/assets/tools/storage.js");
/* harmony import */ var _components_colorpicker_js__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ../components/colorpicker.js */ "./resources/assets/components/colorpicker.js");
/* harmony import */ var _tools_tabs_js__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ../tools/tabs.js */ "./resources/assets/tools/tabs.js");
/* harmony import */ var _components_components_js__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ../components/components.js */ "./resources/assets/components/components.js");
/* harmony import */ var _components_dialog_js__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ../components/dialog.js */ "./resources/assets/components/dialog.js");
/* harmony import */ var _tools_common_extend_js__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ../tools/common-extend.js */ "./resources/assets/tools/common-extend.js");
/* harmony import */ var _components_notification_js__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ../components/notification.js */ "./resources/assets/components/notification.js");
/* harmony import */ var _components_icon_selector_js__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ../components/icon_selector.js */ "./resources/assets/components/icon_selector.js");
/* harmony import */ var _core_js__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! ../core/_.js */ "./resources/assets/core/_.js");
/* harmony import */ var _animations_js__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__(/*! ./animations.js */ "./resources/assets/js/animations.js");
/* harmony import */ var _admin_tools_service_js__WEBPACK_IMPORTED_MODULE_20__ = __webpack_require__(/*! ./admin-tools.service.js */ "./resources/assets/js/admin-tools.service.js");
/* harmony import */ var _tools_loading_js__WEBPACK_IMPORTED_MODULE_21__ = __webpack_require__(/*! ../tools/loading.js */ "./resources/assets/tools/loading.js");
/* harmony import */ var _core_ajax_js__WEBPACK_IMPORTED_MODULE_22__ = __webpack_require__(/*! ../core/ajax.js */ "./resources/assets/core/ajax.js");
/* harmony import */ var _libs_jseldom_jseldom_jquery_js__WEBPACK_IMPORTED_MODULE_23__ = __webpack_require__(/*! ../libs/jseldom/jseldom-jquery.js */ "./resources/assets/libs/jseldom/jseldom-jquery.js");
/* harmony import */ var _core_helpers_js__WEBPACK_IMPORTED_MODULE_24__ = __webpack_require__(/*! ../core/helpers.js */ "./resources/assets/core/helpers.js");






















mw.tools = new _admin_tools_service_js__WEBPACK_IMPORTED_MODULE_20__.AdminTools(mw.app);
mw.tools.loading = _tools_loading_js__WEBPACK_IMPORTED_MODULE_21__.Loading;
mw.tools.progress = _tools_loading_js__WEBPACK_IMPORTED_MODULE_21__.Progress;



for (var i in _core_helpers_js__WEBPACK_IMPORTED_MODULE_24__.Helpers) {
  mw.tools[i] = _core_helpers_js__WEBPACK_IMPORTED_MODULE_24__.Helpers[i];
}
if (!jQuery.fn.reload_module) {
  jQuery.fn.reload_module = function (c) {
    return this.each(function () {
      //   if($(this).hasClass("module")){
      (function (el) {
        mw.reload_module(el, function () {
          if (typeof c != 'undefined') {
            c.call(el, el);
          }
        });
      })(this);
      //   }
    });
  };
}
})();

/******/ })()
;