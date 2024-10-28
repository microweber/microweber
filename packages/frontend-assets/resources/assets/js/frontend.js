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
import "../tools/tabs.js";
import "../components/components.js";
import "../components/dialog.js";
import "../tools/common-extend.js";
import "../components/notification.js";

import "../core/_.js";
import "./animations.js";










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

if(!jQuery.fn.reload_module) {

    jQuery.fn.reload_module = function (c) {
        return this.each(function () {
            //   if($(this).hasClass("module")){
            (function (el) {
                mw.reload_module(el, function () {
                    if (typeof(c) != 'undefined') {
                        c.call(el, el)
                    }
                })
            })(this)
            //   }
        })
    };

}




