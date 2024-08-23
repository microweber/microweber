import "./core.js";
import "../core/@core.js";
import "../core/modules.js";
import "../core/ajax.js";
import "../core/url.js";
import "../core/objects.js";
import "../core/events.js";
import "../core/reload-module.js";
import "../core/_.js";
import "../widgets/hamburger.js";
import "../tools/element.js";
import "../tools/spinner.js";
import "../tools/storage.js";
import "../components/colorpicker.js";
import "../tools/tabs.js";
import "../components/dialog.js";
import "../tools/common-extend.js";








import { AdminTools } from "./admin-tools.service.js";



mw.tools = new AdminTools(mw.app);

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




console.log('frontend.js loaded');
