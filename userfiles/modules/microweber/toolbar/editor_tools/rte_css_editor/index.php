
<style>

    #css-editor-selected-view {
        font-size: 12px;
        text-transform: lowercase;
        margin-bottom: 10px;
    }

    #css-editor-selected-view .mw-tree-nav-skin-default .mw-tree-item-content{
        line-height: 14px;
    }
    #css-editor-selected-view .mw-tree-nav-skin-default li{
        line-height: normal;
    }
    #css-editor-selected-view .mw-tree-nav-skin-default li li{
        padding-left: 10px;
    }
    #css-editor-selected-view .mw-tree-nav-skin-default li ul li:after{
        display: none;
    }
    #css-editor-selected-view .mw-tree-nav-skin-default li ul li:before{
        display: none;
    }
    #css-editor-selected-view > span {
        display: inline-block;
        padding: 0px 8px;
        border-radius: 2px;
        background-color: #eee;
        margin: 0 2px;
        letter-spacing: 0px !important;
        line-height: normal !important;
        vertical-align: middle;
    }

    #mw-css-editor {
        position: absolute;
        top: 60px;
        left: 0;
        width: 100%;
        height: -webkit-calc(100% - 60px);
        height: calc(100% - 60px);
    }

    #css-editor{
        border: 1px solid #77777730;
        border-radius: 3px;
        overflow: hidden;
        border-bottom: none;
        margin-bottom: 15px;
    }
    #css-editor-picker svg{
        margin-top: 2px;
    }
    #css-editor-picker path{
        fill:#777;
    }
    #css-editor-picker {
        margin: 0 0 10px 0;
    }
    #css-editor-picker.active{
        box-shadow: inset 0 0 11px rgba(0,0,0,.2);
        background: #fff;
    }

    .mw-CSS-Editor-group-border .prop-ui-field-holder-size{
        float: left;
        width: 45%;
    }
    .mw-CSS-Editor-group-border .prop-ui-field-holder-size + .prop-ui-field-holder{
        float: left;
        clear: none;
    }
    .mw-CSS-Editor-border-label{
        clear: both;
        padding-top: 15px;
        margin-top: 15px;
        border-top: 1px solid #ccc;
    }

</style>
<script type="text/javascript">
    //parent.mw.require("external_callbacks.js");
    mw.require("jquery-ui.js");
    mw.require("events.js");
    mw.require("forms.js");
    mw.require("files.js");
    mw.require("tools.js");
    mw.require("url.js");
    mw.require('prop_editor.js');
    mw.require('color.js');
    mw.require('selector.js')
    mw.require('tree.js')
</script>
<script>


    CSSEditorSchema = [
        {
            interface: 'block',
            class: 'mw-CSS-Editor-group-title',
            content: 'Size'
        },
        {
            interface: 'block',
            class: 'mw-CSS-Editor-group',
            content: [
                {
                    interface: 'block',
                    class: 'mw-css-editor-group',
                    content: [
                        {
                            interface: 'size',
                            label: 'Width',
                            id: 'width'
                        },
                        {
                            interface: 'size',
                            label: 'Height',
                            id: 'height'
                        }
                    ]
                },
                {
                    interface: 'block',
                    class: 'mw-css-editor-group',
                    content: [
                        {
                            interface: 'size',
                            label: 'Min width',
                            id: 'minWidth',
                            autocomplete:[
                                '0'
                            ]
                        },
                        {
                            interface: 'size',
                            label: 'Max width',
                            id: 'maxWidth',
                            autocomplete:[
                                'none'
                            ]
                        }
                    ]
                },
                {
                    interface: 'block',
                    class: 'mw-css-editor-group',
                    content: [
                        {
                            interface: 'size',
                            label: 'Min height',
                            id: 'minHeight',
                            autocomplete:[
                                '0'
                            ]
                        },
                        {
                            interface: 'size',
                            label: 'Max height',
                            id: 'maxHeight',
                            autocomplete:[
                                'none'
                            ]
                        }
                    ]
                }
            ]
        },
        {
            interface: 'block',
            class: 'mw-CSS-Editor-group-title',
            content: 'Spacing'
        },
        {
            interface: 'block',
            class: 'mw-CSS-Editor-group',
            content: [
                {
                    interface: 'block',
                    class: '',
                    content: 'Margin'
                },
                {
                    interface: 'quatro',
                    label: [' top', 'right', 'bottom', 'left'],
                    id: 'margin'
                },
                {
                    interface: 'block',
                    class: '',
                    content: 'Padding'
                },
                {
                    interface: 'quatro',
                    label: ['top', 'right', 'bottom', 'left'],
                    id: 'padding'
                }
            ]
        },


        {
            interface: 'block',
            class: 'mw-CSS-Editor-group-title',
            content: 'Typography'
        },
        {
            interface: 'block',
            class: 'mw-CSS-Editor-group',
            content: [
                {
                    interface: 'block',
                    class: 'mw-css-editor-group',
                    content: [
                        {
                            interface: 'size',
                            label: 'Size',
                            id: 'fontSize'
                        },
                        {
                            interface: 'select',
                            label: 'Weight',
                            id: 'fontWeight',
                            options: ['inherit', 'normal', 'bold', 'bolder', 'lighter', 100, 200, 300, 400, 500, 600, 700, 800, 900]
                        }

                    ]
                },
                {
                    interface: 'block',
                    class: 'mw-css-editor-group',
                    content: [
                        {
                            interface: 'select',
                            label: 'Style',
                            id: 'fontStyle',
                            options: ['italic', 'normal']
                        },
                        {
                            interface: 'color',
                            label: 'Color',
                            id: 'color'
                        }

                    ]
                },
                {
                    interface: 'block',
                    class: 'mw-css-editor-group',
                    content: [
                        {
                            interface: 'select',
                            label: 'Text transform',
                            id: 'textTransform',
                            options: ['none', 'uppercase', 'lowercase', 'capitalize']
                        },
                        {
                            interface: 'size',
                            label: 'Line Height',
                            id: 'lineHeight'
                        }
                    ]
                },
                {
                    interface: 'block',
                    class: 'mw-css-editor-group',
                    content: [
                        {
                            interface: 'size',
                            label: 'Letter spacing',
                            id: 'letterSpacing'
                        },
                        {
                            interface: 'size',
                            label: 'Word spacing',
                            id: 'wordSpacing'
                        }
                    ]
                }
            ]
        },

        {
            interface: 'block',
            content: 'Background',
            class: 'mw-CSS-Editor-group-title'
        },
        {
            interface: 'block',
            class: 'mw-CSS-Editor-group',
            content: [
                {
                    interface: 'color',
                    label: 'Color',
                    id: 'backgroundColor'
                },
                {
                    interface: 'file',
                    id: 'backgroundImage',
                    label: 'Image',
                    types: 'images'
                },
                {
                    interface: 'select',
                    id: 'backgroundRepeat',
                    label: 'Repeat',
                    options: ['no-repeat', 'repeat-x', 'repeat-y', 'repeat']
                },
                {
                    interface: 'select',
                    id: 'backgroundSize',
                    label: 'Size',
                    options: ['auto', 'cover', {title: 'fit', value: 'contain'}, {title: 'scale', value: '100% 100%'}]
                },
                {
                    interface: 'select',
                    id: 'backgroundPosition',
                    label: 'Position',
                    options: [
                        'center',
                        {title: 'Top Left', value: '0 0'},
                        {title: 'Top Center', value: '50% 0'},
                        {title: 'Top Right', value: '100% 0'},
                        {title: 'Middle Left', value: '0 50%'},
                        {title: 'Middle Right', value: '100% 50%'},
                        {title: 'Middle Right', value: '100% 50%'},
                        {title: 'Bottom Left', value: '0 100%'},
                        {title: 'Bottom Center', value: '50% 100%'},
                        {title: 'Bottom Right', value: '100% 100%'}

                    ]
                }
            ]
        },
        {
            interface: 'block',
            content: 'Border',
            class: 'mw-CSS-Editor-group-title'
        },
        {
            interface: 'block',
            class: 'mw-CSS-Editor-group mw-CSS-Editor-group-border',
            content: [
                {
                    interface: 'block',
                    content: 'Border Top'
                },
                {
                    interface: 'size',
                    id: 'borderTopWidth',
                    label: 'Size'
                },
                {
                    interface: 'select',
                    id: 'borderTopStyle',
                    label: 'Type',
                    options: [
                        'none',
                        'hidden',
                        'dotted',
                        'dashed',
                        'solid',
                        'double',
                        'groove',
                        'ridge',
                        'inset',
                        'outset'
                    ]
                },
                {
                    interface: 'color',
                    label: 'Color',
                    id: 'borderTopColor'
                },
                {
                    interface: 'block',
                    content: 'Border Bottom',
                    class: 'mw-CSS-Editor-border-label'
                },
                {
                    interface: 'size',
                    id: 'borderBottomWidth',
                    label: 'Size'
                },
                {
                    interface: 'select',
                    id: 'borderBottomStyle',
                    label: 'Type',
                    options: [
                        'none',
                        'hidden',
                        'dotted',
                        'dashed',
                        'solid',
                        'double',
                        'groove',
                        'ridge',
                        'inset',
                        'outset'
                    ]
                },
                {
                    interface: 'color',
                    label: 'Color',
                    id: 'borderBottomColor'
                },
                {
                    interface: 'block',
                    content: 'Border Left',
                    class: 'mw-CSS-Editor-border-label'
                },
                {
                    interface: 'size',
                    id: 'borderLeftWidth',
                    label: 'Size'
                },
                {
                    interface: 'select',
                    id: 'borderLeftStyle',
                    label: 'Type',
                    options: [
                        'none',
                        'hidden',
                        'dotted',
                        'dashed',
                        'solid',
                        'double',
                        'groove',
                        'ridge',
                        'inset',
                        'outset'
                    ]
                },
                {
                    interface: 'color',
                    label: 'Color',
                    id: 'borderLeftColor'
                },
                {
                    interface: 'block',
                    content: 'Border Right',
                    class: 'mw-CSS-Editor-border-label'
                },
                {
                    interface: 'size',
                    id: 'borderRightWidth',
                    label: 'Size'
                },
                {
                    interface: 'select',
                    id: 'borderRightStyle',
                    label: 'Type',
                    options: [
                        'none',
                        'hidden',
                        'dotted',
                        'dashed',
                        'solid',
                        'double',
                        'groove',
                        'ridge',
                        'inset',
                        'outset'
                    ]
                },
                {
                    interface: 'color',
                    label: 'Color',
                    id: 'borderRightColor'
                }
            ]
        },
        {
            interface: 'block',
            content: 'Misc',
            class: 'mw-CSS-Editor-group-title'
        },
        {
            interface: 'block',
            class: 'mw-CSS-Editor-group',
            content: [
                {
                    interface: 'block',
                    content: 'Rounded Corners'
                },
                {
                    interface: 'quatro',
                    id: 'borderRadius',
                    label: ['Top Left', 'Top Right', 'Bottom Left', 'Bottom Right']
                },

                {
                    interface: 'block',
                    content: 'Element Shadow',
                },
                {
                    interface: 'shadow',
                    id: 'boxShadow',
                    pickerPosition: 'top-left'
                }
            ]
        }

    ];

    $(document).ready(function(){
            mw.cssEditorSelector = new mw.Selector({
                root: window.parent.document.body,
                document: window.parent.document
            });
            parent.mw.cssEditorSelector = mw.cssEditorSelector;
            mw.cssEditorSelector.active(false);
            $("#css-editor-picker").on("click", function(){
                mw.cssEditorSelector.active(!mw.cssEditorSelector.active());
            });


        $(mw.cssEditorSelector).on('stateChange', function (e, state) {
            if(state) {
                $("#css-editor-picker").addClass('active');
                top.mw.liveEditSelectMode = 'css';
                top.mw.drag.plus.locked = true;
                $('.mw-selector').show();
            } else {
                $("#css-editor-picker").removeClass('active');
                top.mw.liveEditSelectMode = 'element';
                top.mw.drag.plus.locked = false;
                $('.mw-selector').hide();
            }
        });

        var treeStub = {
            setData : function (data) {
                $('#css-editor-selected-view').html(data[data.length-1].title)
            }
        };

        mw.cssSelectorTree = treeStub/*new mw.tree({
            element: '#css-editor-selected-view',
            saveState: false
        })*/;

        $(mw.cssEditorSelector).on('select', function(){

            mw.cssEditorSelector.hideAll();
            mw.cssEditorSelector.active(false);
            mw.liveEditSelectMode = 'element';
            if(mw.elementCSSEditor){
                var el = mw.cssEditorSelector.selected[0];
                if(mw.tools.hasParentsWithClass(el, 'mw-control-box')){
                    return;
                }
                mw.elementCSSEditor.currentElement = null;

                $("#mw-css-editor-selected").css('backgroundImage', 'none')

                if (el.id && !mw.tools.hasAnyOfClassesOnNodeOrParent(el, ['mw-defaults'])) {
                    mw.elementCSSEditor.currentElement = el;
                }
                else {
                    mw.tools.foreachParents(el, function (loop) {
                        if (this.id && !mw.tools.hasAnyOfClassesOnNodeOrParent(this, ['mw-defaults'])) {
                            mw.elementCSSEditor.currentElement = this;
                            mw.tools.stopLoop(loop);
                        }
                    });
                }
                if (!mw.elementCSSEditor.currentElement) {
                    mw.elementCSSEditor.disable();
                    return;
                }
                mw.elementCSSEditor.enable();

                window.cname = function (ccurr) {
                    if(!ccurr) return;
                    var cls = (ccurr.className || '').trim().replace(/\s{2,}/g, ' ').replace(/\.\./g,'.').replace(/\.\./g,'.');
                    cls = cls?'.'+cls.split(' ').join('.'):'';
                    var cn = ccurr.nodeName + cls + (ccurr.id?'#'+ccurr.id:'');
                    return cn;
                };
                var c = mw.elementCSSEditor.currentElement;

                if(!c || !c.parentNode) return;

                var parentcname = c.parentNode.nodeName + (c.parentNode.className?'.'+c.parentNode.className.split(' ').join('.'):'')+ (c.id?'#'+c.id:'');
                var treedata = [], ccurr = c;

                while (ccurr ) {

                        treedata.push({
                            id: cname(ccurr),
                            title: cname(ccurr),//ccurr.nodeName,
                            parent_id: ccurr.parentNode.nodeName !== 'HTML' ? cname(ccurr.parentNode) : 0,
                            parent_type: ccurr.parentNode.nodeName !== 'HTML' ?  "page" : null,
                            subtype: "page",
                            type: "page"
                        });
                        ccurr = ccurr.parentNode;

                        if(ccurr.nodeName === 'HTML'){
                            break;
                        }

                }

                treedata.reverse();
                console.log(treedata)
                mw.cssSelectorTree.setData(treedata);

                setFrame();

                var css = getComputedStyle(el);
                var bgimg = css.backgroundImage;
                if(bgimg.indexOf('url(') !== -1){
                    bgimg = bgimg.split('(')[1].split(')')[0]
                }
                var val = {
                    margin: (css.marginTop + ' ' + css.marginRight + ' ' + css.marginBottom + ' ' + css.marginLeft),
                    padding: (css.paddingTop + ' ' + css.paddingRight + ' ' + css.paddingBottom + ' ' + css.paddingLeft),
                    fontSize: el.style.fontSize || css.fontSize,
                    letterSpacing: css.letterSpacing,
                    fontWeight: css.fontWeight,
                    fontStyle: css.fontStyle,
                    lineHeight: css.lineHeight,
                    textTransform: css.textTransform,
                    backgroundClip: css.backgroundClip,
                    color: mw.color.rgbToHex(css.color),
                    backgroundColor: mw.color.rgbToHex(css.backgroundColor),
                    backgroundImage: bgimg,
                    backgroundRepeat: css.backgroundRepeat,
                    backgroundSize: css.backgroundSize,
                    backgroundPosition: css.backgroundPosition,
                    width: css.width,
                    minWidth: css.minWidth,
                    maxWidth: css.maxWidth,
                    minHeight: css.minHeight,
                    maxHeight: css.maxHeight,
                    height: css.height,
                    boxShadow: css.boxShadow,
                    wordSpacing: css.wordSpacing,
                    borderTopWidth: css.borderTopWidth,
                    borderTopStyle: css.borderTopStyle,
                    borderTopColor: css.borderTopColor,
                    borderRightWidth: css.borderRightWidth,
                    borderRightStyle: css.borderRightStyle,
                    borderRightColor: css.borderRightColor,
                    borderLeftWidth: css.borderLeftWidth,
                    borderLeftStyle: css.borderLeftStyle,
                    borderLeftColor: css.borderLeftColor,
                    borderBottomWidth: css.borderBottomWidth,
                    borderBottomStyle: css.borderBottomStyle,
                    borderBottomColor: css.borderBottomColor,
                    borderRadius: (css.borderTopLeftRadius + ' ' + css.borderTopRightRadius + ' ' + css.borderBottomLeftRadius + ' ' + css.borderBottomRightRadius),
                };
                mw.elementCSSEditor.setValue(val);
            }

        });


    });

    setFrame = function () {
        var frame = parent.mw.liveEditWidgets.cssEditorInSidebarAccordion()
        frame.style.height = 'auto';
        var h1 = Math.max($(document.body).outerHeight(), document.body.scrollHeight);
        frame.style.height = h1 + 'px';
    };


    $(window).on('load', function () {

        mw.require('liveedit.css');

        setFrame();

       mw.$('body').css({
            padding: '20px',
            background: '#fff'
        });
        mw.elementCSSEditor = new mw.propEditor.schema({
            schema: CSSEditorSchema,
            element: '#css-editor'
        });
        parent.mw.elementCSSEditor = mw.elementCSSEditor;

        mw.elementCSSEditor.disable();

        mw.trigger('ComponentsLaunch');

        var _prepareCSSValue = function(property, value){
            if(property === 'backgroundImage'){
                return 'url(' + value + ')';
            }
            return value;
        };
        var _setElementStyle = function(p, value){
            var val = _prepareCSSValue(p, value);
            var css = {};
            css[p] = val;
            if(p === 'backgroundClip') {
                css = {
                    'background-clip':val,
                    '-webkit-background-clip':val
                };
            }
            mw.$(mw.elementCSSEditor.currentElement).css(css);
        };
        $(mw.elementCSSEditor).on('change', function (event, property, value) {
            if($.isArray(value)){
                value = value[0];
            }
            var currState = top.mw.liveEditState.state()
            if(currState[currState.length-1].$id !== 'css'){
                top.mw.liveEditState.record({
                    target: mw.elementCSSEditor.currentElement.parentNode,
                    value: mw.elementCSSEditor.currentElement.parentNode.innerHTML,
                    $id: 'css'
                });
            }

            _setElementStyle(property, value);
            mw.$(mw.elementCSSEditor.currentElement).attr('staticdesign', true);
            top.mw.liveEditState.timeoutRecord({
                target: mw.elementCSSEditor.currentElement.parentNode,
                value: mw.elementCSSEditor.currentElement.parentNode.innerHTML,
            });
            mw.cssEditorSelector.positionSelected()
        });


        mw.$(".mw-CSS-Editor-group-title").on("click", function () {

            $(this).next().stop().slideToggle(function () {
                setFrame()
            });
        })

        setTimeout(function(){
            setFrame()
        }, 777);

        $(top.document.body).on('click', function (e) {
            if(mw.elementCSSEditor.currentElement){
                var el = mw.tools.firstParentOrCurrentWithId(e.target, mw.elementCSSEditor.currentElement.id);
                if(!el) {
                    mw.elementCSSEditor.disable()
                    mw.cssEditorSelector.hideAll()
                    mw.elementCSSEditor.currentElement = null;
                }
            }
        })
    })
</script>
<div id="css-editor-selected-view"></div>
<span class="mw-ui-btn mw-ui-btn-medium tip" id="css-editor-picker" data-tip="<?php _e('Pick element'); ?>" data-tipposition="right-center">
    <svg xmlns="http://www.w3.org/2000/svg" height="22" height="22" viewBox="0 0 1024 1024" version="1.1">
        <path d="M215.871559 402.969794c0-152.609747 124.147508-276.726555 276.726555-276.726555S769.325693 250.360048 769.325693 402.969794c0 41.884946-10.053983 81.198324-26.777877 116.812449l48.88129 39.539528c24.581861-46.796816 38.678928-99.92892 38.678928-156.353001 0-186.087209-151.392012-337.50992-337.50992-337.50992S155.089218 216.882586 155.089218 402.969794c0 182.040033 144.982022 330.508459 325.518818 336.901053l-9.846252-62.381769C328.532455 666.136519 215.871559 548.100196 215.871559 402.969794zM758.151189 765.617232l138.170896-14.190187-324.347133-262.33273c23.283286-21.468964 38.107923-51.960419 38.107923-86.12452 0-64.885799-52.606125-117.487831-117.491924-117.487831s-117.491924 52.602032-117.491924 117.487831c0 64.888869 52.606125 117.487831 117.491924 117.487831 12.138458 0 23.616884-2.366907 34.627658-5.783727l65.056692 412.29723 75.414597-106.777909c3.213181-4.541433 10.04682-4.229325 12.821003 0.586354l85.460394 148.020218 72.817446-42.040489-86.455047-149.752676C749.566674 772.22472 752.652965 766.181073 758.151189 765.617232z"/>
    </svg>
</span>
<div id="css-editor"></div>
