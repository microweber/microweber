/*!
 * Mercury Editor is a CoffeeScript and jQuery based WYSIWYG editor.  Documentation and other useful information can be
 * found at https://github.com/jejacks0n/mercury
 *
 * Supported browsers:
 *   - Firefox 4+
 *   - Chrome 10+
 *   - Safari 5+
 *
 * Copyright (c) 2011 Jeremy Jackson
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit
 * persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
 * Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 */

// ## Default Packages
//
// Some default packages are provided for you.  If you want to define your own, feel free to do so before including this
// script.  These, or your own packages can be specified to the loader in query params (read below for details).
if (!window.mercuryPackages) window.mercuryPackages = {
  development: {javascripts: 'mercury.js', stylesheets: 'mercury.css'},
  bundled: {javascripts: 'javascripts/mercury.js,javascripts/mercury_dialogs.js', stylesheets: 'stylesheets/mercury.bundle.css'}
};


// ## Mercury Loader
(function() {
  // Useragent detection, which we use to determine if the client is supported.  We do this method instead of checking
  // features because many of the features are supported in IE, but aren't implemented to the W3C spec.
  var browser = {
    webkit: /(webkit)[ \/]([\w.]+)/,
    opera: /(opera)(?:.*version)?[ \/]([\w.]+)/,
    msie: /(msie) ([\w.]+)/,
    mozilla: /(mozilla)(?:.*? rv:([\w.]+))?/
  };

  var ua = navigator.userAgent.toLowerCase();
  var match = browser.webkit.exec(ua) || browser.opera.exec(ua) || browser.msie.exec(ua) || ua.indexOf("compatible") < 0 && browser.mozilla.exec(ua) || [];
  browser = {version: match[2] || "0" };
  browser[match[1] || ""] = true;

  // If the browser isn't supported, we don't try to do anything more.  We do direct userAgent detection here because IE
  // thinks it's supported but isn't -- in part because it has it's own implementation of the contentEditable spec.
  if (document.getElementsByTagName && document.getElementById && document.designMode && !browser.konqueror && !browser.msie) {
    // supported
  } else {
    return;
  }

  // Default options, which can be overridden by specifying them in query params to the loader script.
  // You can provide any additional options to the loader, and they will be passed to the PageEditor instance when it's
  // created, so for instance you could put `visible=false`, and the editor be hidden after it's created.
  var options = {
    // A path or url from which the javascripts and css should be loaded.
    src: merc_src,
    // A value defined in the packages above.  Development is used by default.  If you want to provide your own package
    // you can just define one before including this script.
    pack: 'bundled'
  };

  // Hide the document during loading so there isn't a flicker while mercury is being loaded.
  var head = document.getElementsByTagName("head")[0];
  if (window == top) {
    var style = document.createElement('style');
    var rules = document.createTextNode('body{visibility:hidden;display:none}');
    style.type = 'text/css';
    if (style.styleSheet) style.styleSheet.cssText = rules.nodeValue;
    else style.appendChild(rules);
    head.appendChild(style);
  }

  // Because Mercury loads the document it's going to edit into an iframe we do some tweaks to the current document to
  // make that feel more seamless.
  function loadMercury() {
    if (document.mercuryLoaded) return;
    if (timer) window.clearTimeout(timer);
    document.mercuryLoaded = true;

    // If the current window is the top window, it means that Mercury hasn't been loaded yet.  So we load it.
    if (window == top) {
      var i;

      // Find the loader script and determine what options were provided so the defaults can be overridden.  To provide
      // options just pass them in as query params (eg. `mercury_loader.js?src=/asset_path&pack=bundled`)
      var scripts = document.getElementsByTagName('script');
      for (i = 0; i <= scripts.length - 1; i += 1) {
        var match = scripts[i].src.match(/mercury_loader\.js\??(.*)?$/);
        if (!match || !match[1]) continue;

        match[1].replace(/([^&=]*)=([^&=]*)/g, function (m, attr, value) {
          options[attr] = value;
        });
      }

      var pack = window.mercuryPackages[options.pack];
      setTimeout(function() {
        // Once we're ready to load Mercury we clear the document contents, and add in the css and javascript tags.
        // Once the script has loaded we display the body again, and instantiate a new instance of Mercury.PageEditor.
        document.body.innerHTML = '<div style="height:0;overflow:hidden;">Mercury</div>';
        for (i = 0; i <= document.styleSheets.length - 1; i += 1) {
          document.styleSheets[i].disabled = true;
        }

        // Load all the stylesheets.
        var stylesheets = pack.stylesheets.split(',');
        for (i = 0; i <= stylesheets.length - 1; i += 1) {
          var link = document.createElement('link');
          link.href = merc_src + '/' + stylesheets[i];
          link.media = 'screen';
          link.rel = 'stylesheet';
          link.type = 'text/css';
          head.appendChild(link);
        }

        // Load all the javascripts.
        var javascripts = pack.javascripts.split(',');
        var loaded = 0;
        function loadScript(src) {
          var script = document.createElement('script');
          script.src = merc_src + '/' + src;
          script.type = 'text/javascript';
          head.appendChild(script);
          script.onload = function() {
            loaded += 1;
            if (loaded >= javascripts.length) {
              document.body.style.visibility = 'visible';
              document.body.style.display = 'block';
              // Instantiate the PageEditor, passing in the options that were provided to the loader.
              new Mercury.PageEditor(null, options);
              // If there's a mercuryLoaded function available, call it.   You can provide one before the loading script
              // and it will be called after everything is loaded, but before everything is initialized.  You can bind
              // to the mercury:ready event or use Mercury.bind('ready', function() {}).
              if (typeof(window.mercuryLoaded) == 'function') {
                window.mercuryLoaded();
              }
            } else {
              loadScript(javascripts[loaded]);
            }
          };
        }
        loadScript(javascripts[loaded]);
      }, 1);
    } else if (top.Mercury) {
      // Since this file will be included in the iframe as well, we use it to tell Mercury that the document is ready to
      // be worked on.  By firing this event we're able to build the regions and get everything ready without having to
      // wait for assets and slow javascripts to load or complete.
      window.Mercury = top.Mercury;
      Mercury.trigger('initialize:frame');
    }
  }

  // This is a common technique for determining if the document has loaded yet, and is based on the methods used in
  // Prototype.js.  The following portions just call loadMercury once it's appropriate to do so.
  //
  // Support for the DOMContentLoaded event is based on work by Dan Webb, Matthias Miller, Dean Edwards, John Resig,
  // and Diego Perini.
  var timer;
  function checkReadyState() {
    if (document.readyState === 'complete') {
      document.stopObserving('readystatechange', checkReadyState);
      loadMercury();
    }
  }

  function pollDoScroll() {
    try { document.documentElement.doScroll('left'); }
    catch(e) {
      timer = pollDoScroll.defer();
      return;
    }
    loadMercury();
  }

  if (document.addEventListener) {
    document.addEventListener('DOMContentLoaded', loadMercury, false);
  } else {
    document.observe('readystatechange', checkReadyState);
    if (window == top) { timer = pollDoScroll.defer(); }
  }
})();
