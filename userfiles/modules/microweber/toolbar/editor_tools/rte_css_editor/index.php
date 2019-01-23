
<script type="text/javascript">
    parent.mw.require("external_callbacks.js");
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


    $(document).ready(function(){




            mw.cssEditorSelector = new mw.Selector({
                root: window.parent.document.body
            });

            mw.cssEditorSelector.active = false;
            $("#css-editor-picker").on("click", function(){
                mw.cssEditorSelector.active = !mw.cssEditorSelector.active;
                mw.liveEditSelectMode = mw.cssEditorSelector.active ? 'element' : 'css';
                mw.drag.plus.locked = mw.cssEditorSelector.active ? true : false;
                $(this).toggleClass('active');
                $('.mw-selector').toggle();
            })









    })

</script>


<script>


    $(document).ready(function () {


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
                                id: 'minWidth'
                            },
                            {
                                interface: 'size',
                                label: 'Max width',
                                id: 'maxWidth'
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
                                id: 'minHeight'
                            },
                            {
                                interface: 'size',
                                label: 'Max height',
                                id: 'maxHeight'
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
                    }
                ]
            },

            {
                interface: 'block',
                content: 'Background',
                class: 'mw-CSS-Editor-group-title',
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
                    },
                ]
            },
            {
                interface: 'block',
                content: 'Misc',
                class: 'mw-CSS-Editor-group-title',
            },
            {
                interface: 'block',

                class: 'mw-CSS-Editor-group',
                content: [
                    {
                        interface: 'block',
                        content: 'Border radius'
                    },
                    {
                        interface: 'quatro',
                        id: 'borderRadius',
                        label: ['Top Left', 'Top Right', 'Bottom Left', 'Bottom Right']
                    },

                    {
                        interface: 'block',
                        content: 'Element Shadow'
                    },
                    {
                        interface: 'shadow',
                        id: 'boxShadow'
                    }
                ]
            }


        ];


        $("#mw-css-editor").on('load', function () {
            this.contentWindow.mw.require('liveedit.css');

            this.contentWindow.mw.$('body').css({
                padding: '20px',
                background: '#fff'
            });
            mw.elementCSSEditor = new mw.propEditor.schema({
                schema: CSSEditorSchema,
                element: '#mw-css-editor'
            });

            this.contentWindow.mw.trigger('ComponentsLaunch');

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
                _setElementStyle(property, value);
                mw.$(mw.elementCSSEditor.currentElement).attr('staticdesign', true);
                mw.cssEditorSelector.positionSelected()
            });


            this.contentWindow.mw.$(".mw-CSS-Editor-group-title").on("click", function () {

                $(this).next().stop().slideToggle();
            })


        });


        mw.cssSelectorTree = new mw.tree({
            element: '#css-editor-selected-view',
        });


        $(mw.cssEditorSelector).on('select', function(){

            console.log(444)

            mw.cssEditorSelector.active = false;
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


                /*var brdcrmb_curr = document.createElement('span');
                 brdcrmb_curr.innerHTML = mw.elementCSSEditor.currentElement.nodeName
                 + (mw.elementCSSEditor.currentElement.className?'.'+mw.elementCSSEditor.currentElement.className.split(' ').join('.'):'')
                 + (mw.elementCSSEditor.currentElement.id?'#'+mw.elementCSSEditor.currentElement.id:'');
                 var brdcrmb = [brdcrmb_curr];
                 mw.tools.foreachParents(mw.elementCSSEditor.currentElement, function(){

                 var curr = document.createElement('span');
                 curr.innerHTML = this.nodeName
                 + (this.className?'.'+this.className.split(' ').join('.'):'')
                 + (this.id?'#'+this.id:'');
                 brdcrmb.push(curr)

                 });
                 brdcrmb.reverse();

                 $("#css-editor-selected-view").append(brdcrmb);*/

                var c = mw.elementCSSEditor.currentElement;
                var cname = c.nodeName + (c.className?'.'+c.className.split(' ').join('.'):'')+ (c.id?'#'+c.id:'');
                var parentcname = c.parentNode.nodeName + (c.parentNode.className?'.'+c.parentNode.className.split(' ').join('.'):'')+ (c.id?'#'+c.id:'');
                var treedata = [{
                    id: cname,
                    title: cname,
                    parent_id: 0,
                    parent_type: "page",
                    subtype: "home",
                    type: "page"
                }];


                mw.cssSelectorTree.setData(treedata);


                /*
                 *   id: 1
                 parent_id: 0
                 parent_type: "page"
                 subtype: "home"
                 title: "Home"
                 type: "page"
                 * */

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
                    borderRadius: (css.borderTopLeftRadius + ' ' + css.borderTopRightRadius + ' ' + css.borderBottomLeftRadius + ' ' + css.borderBottomRightRadius),
                };
                mw.elementCSSEditor.setValue(val);
            }

        });


    });


</script>
<div id="css-editor-selected-view">

</div>

<span class="mw-ui-btn mw-ui-btn-medium" id="css-editor-picker">Pick Element</span>