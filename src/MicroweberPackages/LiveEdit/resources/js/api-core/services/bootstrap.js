import './index.js'

import {MWUniversalContainer} from "./containers/container.js";


import {Commands} from "./services/commands.js";
import {Modules} from "./services/modules.js";
import {Layouts} from "./services/layouts.js";
import {License} from "./services/license.js";
import {KeyboardEvents} from  "./services/keyboard-events.js";
import {IconPicker} from "./services/icon-picker";
import {LinkPicker} from "./services/link-picker";
import {ColorPicker} from "./services/color-picker";
import '@nextapps-be/livewire-sortablejs';

// other libs
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import { DynamicTargetMenus } from './services/dynamic-target-menus.js';
import {LiveEditCanvas} from "./components/live-edit-canvas/live-edit-canvas";
import { SingleFilePickerComponent } from './services/single-file-picker-component.js';
import { MWBroadcast } from './services/broadcast.js';
import { MWDocumentFocus } from './services/document.focus.service.js';
import { da } from 'vuetify/locale';

mw.app = new MWUniversalContainer();


    const canvasSetUrlInterceptor = async (url) => {
        return new Promise((resolve) => {
            if(mw.top().app.documentFocus.isActive()) {

            } else {

            }
        });
    };


    const canvas = new LiveEditCanvas({
        onSetUrl: canvasSetUrlInterceptor
    });




    mw.app.register('documentFocus', MWDocumentFocus);
    mw.app.register('broadcast', MWBroadcast);
    mw.app.register('canvas', canvas);
    mw.app.register('commands', Commands);
    mw.app.register('modules', Modules);

    mw.app.register('layouts', Layouts);
    mw.app.register('license', License);
    mw.app.register('keyboard', KeyboardEvents);
    mw.app.register('iconPicker', IconPicker);
    mw.app.register('linkPicker', LinkPicker);
    mw.app.register('colorPicker', ColorPicker);
    mw.app.register('dynamicTargetMenus', DynamicTargetMenus);


    mw.app.normalizeBase64Image = normalizeBase64Image;
    mw.app.normalizeBase64Images = normalizeBase64Images;

    mw.app.singleFilePickerComponent = options => {
        return new SingleFilePickerComponent(options)
    };

    mw.app.broadcast.on('canvasURL', (data) => {


        mw.app.broadcast.record('canvasURL', data.url);

        const isActive = mw.app.documentFocus.isActive();

        if(data.url === mw.top().win.document.getElementById('live-editor-frame').src ) {

            const ok = mw.element(`<button class="btn btn-primary" data-action="save">Update</button>`);
            const cancel = mw.element(`<button class="btn">Cancel</button>`);

            const dlg = mw.dialog({
                overlay: true,
                content: 'This page is already opened in another tab! Edit here?',
                footer: [cancel.get(0), ok.get(0)],
                id: 'canvasURLAlreadyOpened'
            })

            ok.on('click', function(){
                dlg.remove()
            });
            cancel.on('click', function(){
                dlg.remove();

            });
            // mw.app.broadcast.message('canvasURLAlreadyOpened', {...data});
        }
    })

    mw.app.broadcast.on('canvasURLAlreadyOpened', (data) => {

        if(data.url === mw.top().win.document.getElementById('live-editor-frame').src && mw.app.documentFocus.isActive()) {
            console.log(125, data)
            mw.confirm('This page is already opened in another tab! Edit here?', function(){

            }, function(){

            });

        }
    })





    function normalizeBase64Image (node, callback) {
        var type, obj;
        if (typeof node.src !== 'undefined' && node.src.indexOf('data:image/') === 0) {
            type = node.src.split('/')[1].split(';')[0];
            obj = {
                file: node.src,
                name: mw.random().toString(36) + "." + type
            }
            $.post(mw.settings.api_url + "media/upload", obj, function (data) {
                data = $.parseJSON(data);
                node.src = data.src;

                mw.top().app.registerChange(node);

                mw.trigger('imageSrcChanged', [node, node.src]);
                if (typeof callback === 'function') {
                    callback.call(node);
                }
            });
        }
        else if (node.style.backgroundImage.indexOf('data:image/') !== -1) {
            var bg = node.style.backgroundImage.replace(/url\(/g, '').replace(/\)/g, '')
            type = bg.split('/')[1].split(';')[0];
            obj = {
                file: bg,
                name: mw.random().toString(36) + "." + type
            };
            $.post(mw.settings.api_url + "media/upload", obj, function (data) {
                data = $.parseJSON(data);
                node.style.backgroundImage = 'url(\'' + data.src + '\')';


                mw.top().app.registerChange(node);
                if (typeof callback === 'function') {
                    callback.call(node);
                }
            });
        }
    };

    function normalizeBase64Images  (root, callback) {
        root = root || document.body;
        var all = root.querySelectorAll(".edit img[src*='data:image/'], .edit [style*='data:image/'][style*='background-image'], .mw-editor-area img[src*='data:image/'], .mw-editor-area [style*='data:image/'][style*='background-image']"),
            l = all.length, i = 0, count = 0;
        if (l > 0) {
            var btn = document.getElementById('main-save-btn');
            var btnPrev;
            if(btn){
                btnPrev = btn.disabled;
                btn.disabled = true;
            }
            for (; i < l; i++) {
                mw.tools.addClass(all[i], 'element');
                mw.wysiwyg.normalizeBase64Image(all[i], function (){
                    count++;
                    if(count === l) {
                        if(typeof callback === 'function') {
                            setTimeout(function(){
                                callback.call();
                            }, 10)
                        }
                        if(btn){
                            btn.disabled = btnPrev;
                        }
                    }
                });
            }
        } else {
            if(typeof callback === 'function') {
                setTimeout(function(){
                    callback.call();
                }, 10)
            }
        }
    };


//mw.app.register('commands', Commands);

// mw.app.domTreeSelect = function(node) {
//     // deprecated
//     // if(!mw.top().app._liveEditDomTree) {
//     //     return;
//     // }
//     //
//     // mw.top().app._liveEditDomTree.select(node)
// }


//}, 300);

// init other libs
window.Alpine = Alpine;
Alpine.plugin(focus);

Alpine.start();
