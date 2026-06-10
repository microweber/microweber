import "./core.js";
import "../core/@core.js";
import "../core/modules.js";

import "../core/url.js";
import "../core/objects.js";
import "../core/events.js";

import "../core/reload-module.js";

import "../widgets/hamburger.js";
import "../tools/element.js";
import "../tools/spinner.js";
import "../tools/storage.js";
import "../components/colorpicker.js";
import "../components/gallery.js";
import "../tools/tabs.js";
import "../tools/extradata-form.js";
import "../components/components.js";
import "../components/dialog.js";
import "../tools/common-extend.js";
import "../components/notification.js";
import "../components/icon_selector.js";
import "../api-core/core/core/forms.js";
import "../api-core/core/core/files.js";
import "../api-core/core/core/session.js";
import "../api-core/core/core/shop.js";
import "../api-core/core/core/csp-skin-handlers.js";

import "../core/_.js";
import "./animations.js";







/*
 * Guard jQuery extensions behind a typeof check so frontend.js
 * can load on public pages BEFORE jQuery is present (jQuery is
 * lazy-loaded only when a public page actually needs it — see
 * ApijsScriptTag.php + TemplateManager::injectConditionalJquery
 * Footer).
 *
 * If jQuery loads later (because the page renders a Slick /
 * Masonry / Datetimepicker / Chosen module skin), re-register the
 * extensions via a `window.__mwRegisterJqueryExtensions` hook
 * that the conditional footer script calls after jQuery loads.
 */
if (typeof window !== 'undefined') {
    var __mwRegisterCommuter = function () {
        if (typeof window.$ === 'undefined' || !window.$.fn) return false;
        if (window.$.fn.commuter) return true;
        window.$.fn.commuter = function (a, b) {
            if (!a) { return }
            var b = b || function () {};
            return this.each(function () {
                if ((this.type === 'checkbox' || this.type === 'radio') && !this.cmactivated) {
                    this.cmactivated = true;
                    mw.$(this).on("change", function () {
                        this.checked === true ? a.call(this) : b.call(this);
                    });
                }
            });
        };
        return true;
    };
    if (!__mwRegisterCommuter()) {
        window.__mwRegisterJqueryExtensions = window.__mwRegisterJqueryExtensions || [];
        window.__mwRegisterJqueryExtensions.push(__mwRegisterCommuter);
    }
}


import { AdminTools } from "./admin-tools.service.js";
import { Loading, Progress } from "../tools/loading.js";



mw.tools = new AdminTools(mw.app);
mw.tools.loading = Loading;
mw.tools.progress = Progress;

import "../core/ajax.js";
import "../libs/jseldom/jseldom-jquery.js";

import {Helpers} from "../core/helpers.js";



for ( let i in Helpers ) {
    mw.tools[i] = Helpers[i];
}

/*
 * `jQuery.fn.reload_module` extension also guarded behind a
 * typeof check. Same lazy-register hook as `.commuter` above.
 */
if (typeof window !== 'undefined') {
    var __mwRegisterReloadModule = function () {
        if (typeof window.jQuery === 'undefined' || !window.jQuery.fn) return false;
        if (window.jQuery.fn.reload_module) return true;
        window.jQuery.fn.reload_module = function (c) {
            return this.each(function () {
                (function (el) {
                    mw.reload_module(el, function () {
                        if (typeof(c) != 'undefined') {
                            c.call(el, el)
                        }
                    })
                })(this)
            })
        };
        return true;
    };
    if (!__mwRegisterReloadModule()) {
        window.__mwRegisterJqueryExtensions = window.__mwRegisterJqueryExtensions || [];
        window.__mwRegisterJqueryExtensions.push(__mwRegisterReloadModule);
    }
}






