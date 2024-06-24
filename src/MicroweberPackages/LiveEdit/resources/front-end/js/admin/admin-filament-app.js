

import "../core/@core.js";
import "./admin-filament-app.globals.js";
import "../core/url.js";
import "../core/events.js";




import {AdminTools} from "./admin-tools.service.js";
import {AdminFilament} from "./admin-filament.js";



window.mw.tools = new AdminTools(mw.app);





//libs
import "xss";

import TomSelect  from "tom-select";




globalThis.TomSelect = TomSelect;
window.TomSelect = TomSelect;





//core
import "../core/i18n.js";
import "../core/objects.js";
import "../core/icon-resolver.js";






import {Progress, Loading} from "../tools/loading.js";
import {Helpers} from "../core/helpers.js";

mw.tools.progress = Progress;
mw.tools.loading = Loading;

for ( let i in Helpers ) {
    mw.tools[i] = Helpers[i];
}


import "../tools/storage.js";
import "../tools/element.js";
import "../tools/cookie.js";
 import "../tools/common-extend.js";
import "../tools/tabs.js";
import "../tools/spinner.js";


//components
import "../components/icon_selector.js";
import "../components/tree.js";
import "../components/tags.js";
import "../components/uiaccordion.js";



import "../components/dialog.js";
import {Alert, Confirm, Prompt} from "../components/system-dialogs.js";
import {RichTextEditor} from "../components/richtext-editor.js";
import {ControlBox} from "../components/control_box.js";
import {SystemDialogsService} from "../components/modules-dialogs.js";
import {TemplatePreview} from "../admin/template-preview.service.js";

mw.alert = Alert;
mw.controlBox = ControlBox;
mw.confirm = Confirm;
mw.prompt = Prompt;
mw.richTextEditor = RichTextEditor;
mw.templatePreview = TemplatePreview;

for (let i in SystemDialogsService) {
    mw.tools[i] = SystemDialogsService[i];
}
mw.systemDialogsService = SystemDialogsService;


import "../components/autocomplete.js";
import "../components/form-controls.js";
import "../components/uploader.js";
import "../components/filemanager.js";

import "../components/filepicker.js";
import "../components/link-editor.js";
import "../components/color.js";
import "../components/colorpicker.js";
import "../components/components.js";
import "../components/notification.js";


//widgets
import "../widgets/tree.js";
import { IconPicker } from "../widgets/icon-picker.js";







mw.app.register('iconPicker', IconPicker);


mw.admin.filament = new AdminFilament();




mw.admin.dispatch('init');
mw.admin.dispatch('ready');




mw.required = [] ;
mw.require = function(url, inHead, key, defered) {
    if(!url) return;
    var defer;
    if(defered) {
        defer = ' defer ';
    } else {
        defer = '   '
    }
    if(typeof inHead === 'boolean' || typeof inHead === 'undefined'){
        inHead = inHead || false;
    }
    var keyString;
    if(typeof inHead === 'string'){
        keyString = ''+inHead;
        inHead = key || false;
    }
    if(typeof key === 'string'){
        keyString = key;
    }
    var toPush = url, urlModified = false;
    if (!!keyString) {
        toPush = keyString;
        urlModified = true
    }
    var t = url.split('.').pop();
    url = url.includes('//') ? url : (t !== "css" ? mw.settings.includes_url + "api/" + url  :  mw.settings.includes_url + "css/" + url);
    if(!urlModified) toPush = url;
    if (!~mw.required.indexOf(toPush)) {

      mw.required.push(toPush);
      url = url.includes("?") ?  url + '&mwv=' + mw.version : url + "?mwv=" + mw.version;
      if(document.querySelector('link[href="'+url+'"],script[src="'+url+'"]') !== null){
          return
      }

      var cssRel = " rel='stylesheet' ";

      if(defered){
        cssRel = " rel='preload' as='style' onload='this.onload=null;this.rel=\'stylesheet\'' ";
      }


      var string = t !== "css" ? "<script "+defer+"  src='" + url + "'></script>" : "<link "+cssRel+" href='" + url + "' />";

          if(typeof $.fn === 'object'){
              $(mw.head).append(string);
          }
          else{
              var el;
              if( t !== "css")  {
                  el = document.createElement('script');
                  el.src = url;
                  el.defer = !!defer;
                  el.setAttribute('type', 'text/javascript');
                  mw.head.appendChild(el);
              }
              else{

                 el = document.createElement('link');
                 if(defered) {
                    el.as='style';
                    el.rel='preload';
                    el.addEventListener('load', e => el.rel='stylesheet');
                 } else {
                    el.rel='stylesheet';
                 }


                 el.href = url;
                 mw.head.appendChild(el);
              }
          }

    }
  };
