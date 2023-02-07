<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" <?php print lang_attributes(); ?>>
<head>
    <title></title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>



    <link rel="stylesheet" href="http://localhost/mw3/userfiles/modules/microweber/css/ui.css">


    <style>
        .mw-le-btn.disabled,
        .mw-le-btn[disabled]{
            pointer-events: none;
            opacity: .8;
        }
        .mw-le-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50px;
            line-height: 40px;
            min-height: 40px;
            text-align: center;
            white-space: nowrap;
            padding: 0 20px;
            color: white;
            text-decoration: none;
            outline: none;
            cursor: pointer;
            font-size: 16px;
            background-color: #464646;
        }
        .mw-le-btn-sm{ font-size:12px;  }
        .mw-le-btn-primary{ background-color: #39b54a; }
        .mw-le-btn-primary2{ background-color: #0078ff; }

        .mw-le-btn-icon svg *{
            fill: white;
        }
        .mw-le-btn-icon svg{
            fill: white;
            width: 20px;
        }
        .mw-le-btn-icon{
            width: 40px;
            padding: 0;
        }

        .mw-le-nav-box{
            display: block;
            padding: 25px;
            background-color: white;
            position: relative;
            box-shadow: 0 0 32px rgba(0,0,0,.17);
            border-radius: 10px;
        }


        .mw-le-hamburger span{
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;

            transition: .4s cubic-bezier(0.4, 0.0, 0.2, 1);;
            background-color: white;
            user-select: none;
        }
        .mw-le-hamburger span + span{
            top: 7px;
        }
        .mw-le-hamburger span + span + span{
            top: 14px;
        }
        .mw-le-hamburger{
            display: inline-block;
            vertical-align: middle;
            position: relative;
            width: 24px;
            height: 24px;
            cursor: pointer;
            transition: .4s cubic-bezier(0.4, 0.0, 0.2, 1);;
        }
    </style>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700&display=swap');
        html{
            --toolbar-height: 90px;
        }

        *,*:before,*:after{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body{
            overflow: hidden;
            height: 100vh;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }
        #live-edit-fram-holder{
            position: fixed;
            top: var(--toolbar-height);
            left: 0;
            width: 100%;
            height: calc(100% - var(--toolbar-height));
            bottom: 0;
        }
        #live-editor-frame{
            position: absolute;
            top: 0;
            width: 100%;
            height: 100%;
            left: 50%;
            transform: translateX(-50%);
            transition: width .3s;
        }
        #toolbar{
            height: var(--toolbar-height);
            width: 100%;
            align-items: center;
            justify-content: space-between;
            flex-wrap: nowrap;
            background-color: #000;
            display: flex;
            padding: 0 30px;
            position: relative;
            z-index: 2;
            transition: height .3s;
        }

        html.preview #toolbar{

            overflow: hidden;

        }

        #preview-nav span{
            display: inline-flex;
            width: 40px;
            height: 40px;
            justify-content: center;
            align-items: center;
            border-radius: 55px;
            background-color: #464646;
        }
        .toolbar-nav{
            background-color: #2b2b2b;
            border-radius: 100px;
            display: inline-flex;
            margin: 15px 0;
            padding: 10px 10px;
            align-items: center;
            justify-content: space-between;
            flex-wrap: nowrap;
            gap: 5px;
            vertical-align: top;
        }
        #preview-nav svg{
            width: 25px;
            fill: white;
        }
        #live-edit-fram-holder.has-mw-spinner:after{
            backdrop-filter: blur(10px);
        }

        #user-menu-wrapper #user-menu{
            position: absolute;
        }

        #user-menu-wrapper{
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            height: var(--toolbar-height);
        }

        #user-menu{
            position: absolute;
            top: 100%;
            right: 0;
            opacity: 0;
            transform: scale(.8) translateY(-20px);
            pointer-events: none;
            transition: .4s;
            width: 300px;
        }

        #user-menu-wrapper.active #user-menu{
            opacity: 1;
            transform: scale(1) translateY(0px);
            pointer-events: auto;
        }

        html[dir="rtl"] #user-menu{
            left: 0;
            right: auto;
        }


        .active > .mw-le-hamburger > span:nth-child(2){
            width: 40px;
            height: 40px;
            border-radius: 50px;
            background-color: rgb(255 255 255 / 19%);
            left: -5px;
            top: -11px;
        }
        .mw-le-hamburger > span:first-child{
            transform-origin: right center;
        }
        .mw-le-hamburger > span:last-child{
            transform-origin: right bottom;
        }
        .active > .mw-le-hamburger{
            transform: rotate(-90deg);
        }
        .active > .mw-le-hamburger > span:first-child{
            transform: rotate(-45deg) translateY(-2px);
        }
        .active > .mw-le-hamburger > span:last-child{
            transform: rotate(45deg) translateY(2px);
        }









    </style>
    <script>
        mw.require('editor.js');
        mw.require('css_parser.js');
        mw.require('le2/modules-list.js');
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{--<script src="https://unit.microweber.com/le2/editor.js"></script>--}}
    <script src="https://unit.microweber.com/le2/live-edit2.js"></script>
    <script>



        $.fn.reload_module  = function () {

        }


        var frame, frameHolder;

        addEventListener('load', () => {

            var userMenuWrapper = document.getElementById('user-menu-wrapper');
            document.getElementById('toolbar-user-menu-button').addEventListener('click', function () {
                userMenuWrapper.classList.toggle('active')
            });

            /* demo */
            fetch('http://localhost/mw3/api/live-edit/modules-list')
                .then(function (data){
                    return data.json();
                }).then(function (data){

                var modulesList = new ModulesList({
                    data: data
                });
                modulesList.create().then(function (){
                    console.log(modulesList.root)
                    /*mw.dialog({
                        content: modulesList.root
                    })*/
                })

            })




            var initEditor = function () {
                var holder = document.querySelector('#mw-live-edit-editor');

                var _fontFamilyProvider = function () {
                    var _e = {};
                    this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
                    this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

                    this.provide = function (fontsArray) {
                        this.dispatch('change', fontsArray.map(function (font){
                            return {
                                label: font,
                                value: font,
                            }
                        }))
                    }

                };

                window.fontFamilyProvider = new _fontFamilyProvider();

                window.liveEditor = mw.Editor({
                    document: frame.contentWindow.document,
                    executionDocument: frame.contentWindow.document,
                    element: holder,
                    mode: 'document',
                    regions: '.edit',
                    editMode: 'liveedit',
                    controls:  [
                        [
                             'undoRedo',
                            {
                                group: {
                                    icon: 'mdi mdi-format-title',
                                    controls: ['format', 'lineHeight']
                                }
                            },

                            {
                                group: {
                                    controller: 'bold',
                                    controls: [ 'italic', 'underline', 'strikeThrough', 'removeFormat']
                                }
                            },
                            'fontSelector',

                            'fontSize',


                            {
                                group: {
                                    controller: 'alignLeft',
                                    controls: [ 'alignLeft', 'alignCenter', 'alignRight', 'alignJustify' ]
                                }
                            },

                            {
                                group: {
                                    controller: 'ul',
                                    controls: [ 'ol' ]
                                }
                            },


                            'image',
                            {
                                group: {
                                    controller: 'link',
                                    controls: [ 'unlink' ]
                                }
                            },
                            {
                                group: {
                                    controller: 'textColor',
                                    controls: [ 'textBackgroundColor' ]
                                }
                            },

                            'pin',

                        ]
                    ],
                    smallEditorPositionX: 'center',
                    smallEditorSkin: 'lite',

                    interactionControls: [

                    ],

                    id: 'live-edit-wysiwyg-editor',

                    minHeight: 250,
                    maxHeight: '70vh',
                    state: mw.liveEditState,

                    fontFamilyProvider: fontFamilyProvider
                });

                console.log(liveEditor)

/*                liveEditor.on('action', function (){
                    mw.wysiwyg.change(liveEditor.api.elementNode(liveEditor.api.getSelection().focusNode))
                })
                liveEditor.on('smallEditorReady', function (){
                    fontFamilyProvider.provide(mw.top().wysiwyg.fontFamiliesExtended);
                })
                $(liveEditor).on('selectionchange', function (){
                    var sel = liveEditor.getSelection();
                    if(sel.rangeCount) {
                        liveEditor.lastRange =  sel.getRangeAt(0) ;
                    } else {
                        liveEditor.lastRange = undefined;
                    }

                })*/

                holder.innerHTML = '';
                holder.appendChild(liveEditor.wrapper);


                var memPin = liveEditor.storage.get(liveEditor.settings.id + '-small-editor-pinned');
                if(typeof memPin === 'undefined') {
                    liveEditor.smallEditorApi.pin()
                }

            }


            initEditor()









            const responsiveEmulator = {
                set: function (width) {
                    if(typeof width === 'number'){
                        width = width + 'px'
                    }
                    frame.style.width = width
                },
                tablet: function () {
                    responsiveEmulator.set(800)
                },
                phone: function () {
                    responsiveEmulator.set(400)
                },
                desktop: function () {
                    responsiveEmulator.set('100%')
                }

            }





            Array.from(document.querySelectorAll('#preview-nav [data-preview]')).forEach(node => {
                node.addEventListener('click', function (){
                    responsiveEmulator[this.dataset.preview]()
                })
            })





        });
        var pagePreviewToggle = function () {
            document.documentElement.classList.toggle('preview');
            document.documentElement.style.setProperty('--toolbar-height', '0px');
            frame.contentWindow.document.documentElement.classList.toggle('mw-le--page-preview');
            frame.contentWindow.document.body.classList.toggle('mw-live-edit');

            var css = `
                html.mw-le--page-preview body{
                    padding-top: 0 !important
                }
                html.mw-le--page-preview .mw_image_resizer,
                html.mw-le--page-preview #live_edit_toolbar_holder,
                html.mw-le--page-preview .mw-handle-item,
                html.mw-le--page-preview .mw-selector,
                html.mw-le--page-preview .mw_dropable,
                html.mw-le--page-preview .mw-padding-ctrl,
                html.mw-le--page-preview .mw-control-box,
                html.mw-le--page-preview .mw-control-box,
                html.mw-le--page-preview #live_edit_toolbar_holder
                {
                    display: none !important
                }
            `

            var node = frame.contentWindow.document.createElement('style');
            node.textContent = css;
            frame.contentWindow.document.body.appendChild(node)

        }
    </script>


</head>
<body>
    <div id="toolbar">
        <div class="toolbar-nav">
            <span class="mw-le-btn mw-le-btn-icon mw-le-btn-primary2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 32px;"><title>keyboard-backspace</title><path d="M21,11H6.83L10.41,7.41L9,6L3,12L9,18L10.41,16.58L6.83,13H21V11Z" /></svg>
            </span>
        </div>
        <nav id="preview-nav" class="toolbar-nav">
            <span data-preview="tablet"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>tablet</title><path d="M19,18H5V6H19M21,4H3C1.89,4 1,4.89 1,6V18A2,2 0 0,0 3,20H21A2,2 0 0,0 23,18V6C23,4.89 22.1,4 21,4Z" /></svg></span>
            <span data-preview="desktop"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>monitor</title><path d="M21,16H3V4H21M21,2H3C1.89,2 1,2.89 1,4V16A2,2 0 0,0 3,18H10V20H8V22H16V20H14V18H21A2,2 0 0,0 23,16V4C23,2.89 22.1,2 21,2Z" /></svg></span>
            <span data-preview="phone"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>cellphone</title><path d="M17,19H7V5H17M17,1H7C5.89,1 5,1.89 5,3V21A2,2 0 0,0 7,23H17A2,2 0 0,0 19,21V3C19,1.89 18.1,1 17,1Z" /></svg></span>
        </nav>
        <div class="toolbar-nav" id="mw-live-edit-editor"></div>
        <div class="toolbar-nav">
            <span class="mw-le-btn mw-le-btn-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12.5,8C9.85,8 7.45,9 5.6,10.6L2,7V16H11L7.38,12.38C8.77,11.22 10.54,10.5 12.5,10.5C16.04,10.5 19.05,12.81 20.1,16L22.47,15.22C21.08,11.03 17.15,8 12.5,8Z" /></svg>
            </span>
            <span class="mw-le-btn mw-le-btn-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>redo</title><path d="M18.4,10.6C16.55,9 14.15,8 11.5,8C6.85,8 2.92,11.03 1.54,15.22L3.9,16C4.95,12.81 7.95,10.5 11.5,10.5C13.45,10.5 15.23,11.22 16.62,12.38L13,16H22V7L18.4,10.6Z" /></svg>
            </span>
            <span class="mw-le-btn mw-le-btn-primary">
                Save
            </span>
        </div>

        <span class="mw-le-btn mw-le-btn-icon" onclick="pagePreviewToggle()">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>eye</title><path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z" /></svg>
        </span>

         <div id="user-menu-wrapper">
            <span class="mw-le-hamburger" id="toolbar-user-menu-button">
                <span></span>
                <span></span>
                <span></span>
            </span>
            <div id="user-menu" class="mw-le-nav-box">
                Project
            </div>
        </div>

    </div>
    <div id="live-edit-fram-holder">
        <iframe id="live-editor-frame"
                title="Inline Frame Example"
                width="100%"
                height="2000"
                referrerpolicy="no-referrer"
                frameborder="0"
                src="<?php print site_url(); ?>?editmode=y">
        </iframe>
    </div>

<script>

    frame = document.getElementById('live-editor-frame');
    frameHolder = document.getElementById('live-edit-fram-holder');
    mw.spinner({
        element: frameHolder, size: 52, decorate: true
    })

    frame.addEventListener('load', function () {
        var doc = frame.contentWindow.document;
        /*var link = doc.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://unit.microweber.com/le2/live-edit2.css.css';
        doc.body.appendChild(link);

        liveEdit = new LiveEdit({
            root: frame.contentWindow.document.body,
            strict: false,
            mode: 'auto',
            document: frame.contentWindow.document
        })*/

        mw.spinner({
            element: frameHolder
        }).remove()

    })

</script>


</body>
</html>
