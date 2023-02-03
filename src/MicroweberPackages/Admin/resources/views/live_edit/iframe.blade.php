<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" <?php print lang_attributes(); ?>>
<head>
    <title></title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>



    <link rel="stylesheet" href="http://localhost/mw3/userfiles/modules/microweber/css/ui.css">


    <style>
        *,*:before,*:after{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html,body{
            overflow: hidden;
            margin: 0;
            padding: 0;
        }
        #live-edit-fram-holder{
            position: fixed;
            top: 90px;
            left: 0;
            width: 100%;
            height: calc(100% - 90px);
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
            height: 90px;
            background-color: #000;
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
            padding: 10px 25px;
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
    </style>
    <script>
        mw.require('editor.js');
        mw.require('css_parser.js');
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{--<script src="https://unit.microweber.com/le2/editor.js"></script>--}}
    <script src="https://unit.microweber.com/le2/live-edit2.js"></script>
    <script>

        $.fn.reload_module  = function () {

        }


        var frame, frameHolder;

        addEventListener('load', () => {

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





/*            const editor = new MWEditor({
                regions: '.edit',
                mode: 'document',
                /!*smallEditor: [
                    [ 'bold', 'italic', 'link']
                ],*!/
                iframeAreaSelector: 'body',
                iframe: frame,

                controls: [
                    ['undoRedo', '|', 'fontSelector', 'fontSize', 'media', 'format', 'unlink', 'removeFormat', 'image'],
                    [ 'bold', 'italic','link', '|',  'mobilePreview', 'textColor' ],


                ]
            });*/



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
    </script>


</head>
<body>
    <div id="toolbar">

        <nav id="preview-nav" class="toolbar-nav">
            <span data-preview="tablet"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>tablet</title><path d="M19,18H5V6H19M21,4H3C1.89,4 1,4.89 1,6V18A2,2 0 0,0 3,20H21A2,2 0 0,0 23,18V6C23,4.89 22.1,4 21,4Z" /></svg></span>
            <span data-preview="desktop"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>monitor</title><path d="M21,16H3V4H21M21,2H3C1.89,2 1,2.89 1,4V16A2,2 0 0,0 3,18H10V20H8V22H16V20H14V18H21A2,2 0 0,0 23,16V4C23,2.89 22.1,2 21,2Z" /></svg></span>
            <span data-preview="phone"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>cellphone</title><path d="M17,19H7V5H17M17,1H7C5.89,1 5,1.89 5,3V21A2,2 0 0,0 7,23H17A2,2 0 0,0 19,21V3C19,1.89 18.1,1 17,1Z" /></svg></span>
        </nav>
        <div   class="toolbar-nav" id="mw-live-edit-editor"></div>
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
