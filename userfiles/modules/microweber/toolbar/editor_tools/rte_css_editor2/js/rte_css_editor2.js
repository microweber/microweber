mw.lib.require('colorpicker');

mw.require('prop_editor.js');
mw.require('module_settings.js');
mw.require('domtree.js');
mw.require("events.js");
mw.require("forms.js");
mw.require("files.js");
mw.require("url.js");
mw.require('prop_editor.js');
mw.require('color.js');
mw.require('selector.js');
mw.require('tree.js');

mw.require('css_parser.js');
mw.require('tags.js');

mw.app = mw.top().app;
var targetWindow = mw.top().app.canvas.getWindow();
if (targetWindow) {
    var targetMw = targetWindow.mw;

}


var positionSelector = function () {
    var root = mw.element({props: {className: 'mw-position-selector'}})
    var posTop = mw.element({props: {className: 'mw-position-selector-top'}});
    var posRight = mw.element({props: {className: 'mw-position-selector-right'}});
    var posBottom = mw.element({props: {className: 'mw-position-selector-bottom'}});
    var posLeft = mw.element({props: {className: 'mw-position-selector-left'}});
    var all = mw.element({props: {className: 'mw-position-selector-all'}});

    root.append(posTop)
    root.append(posRight)
    root.append(posBottom)
    root.append(posLeft)
    root.append(all)

    return {
        root: root,
        top: posTop,
        right: posRight,
        bottom: posBottom,
        left: posLeft,
        all: all,
    };
}
$(document).on('ready', function () {

    // setTimeout(function () {


    window.liveEditDomTree = new mw.DomTree({
        element: '#domtree',
        resizable: true,
        targetDocument: targetMw.win.document,
        canSelect: function (node, li) {
            var can = mw.top().app.liveEdit.canBeElement(node)
            return can;
            var cant = (!mw.tools.isEditable(node) && !node.classList.contains('edit') && !node.id);
            return !cant;
            // return mw.tools.isEditable(node) || node.classList.contains('edit');
        },
        onHover: function (e, target, node, element) {
            if (typeof targetMw !== 'undefined') {
                //   targetMw.liveEditSelector.setItem(node, targetMw.liveEditSelector.interactors, false);
            }
        },
        onSelect: function (e, target, node, element) {
            setTimeout(function () {


                if (typeof targetMw !== 'undefined') {

                    mw.top().app.liveEdit.selectNode(node);

                    targetMw.tools.scrollTo(node, undefined, 200);
                }
            }, 100);
        }
    });


    //  NS_ERROR_NOT_INITIALIZED
    //  mw.top().app._liveEditDomTree = liveEditDomTree
    // }, 700);

    $('.rte_css_editor_svg').each(function (img) {
        (function (img) {

            $.get(img.src, function (data) {

                $(img).replaceWith(data.all[0])
            })
        })(this)
    })

    $('.mw-field.mw-field-flat.unit').each(function () {
        if (!this.querySelector('.mw-range')) {
            var sl = mw.element('<div class="mw-range default-values-list-slider" />');
            $('input', this).on('input', function () {
                var val = this.value;
                var slVal = this.value;
                var t = ['auto', 'normal', 'inherit', 'initial'];

                if (t.includes(val)) {
                    slVal = 0
                }

                var num = parseFloat(slVal);
                if (isNaN(num)) {
                    num = 0;
                }

                if (/^\d+$/.test(val)) {
                    val = this.value + 'px'
                }
                output(this.parentNode.dataset.prop, val);
                $(sl.get(0)).slider('value', num)
            })
            $(sl.get(0)).slider({
                min: 0,
                max: 100,
                step: 1,
                slide: function (event, ui) {

                    var dvalUnit = (/^\d+$/.test(this.previousElementSibling.value) ? 'px' : this.previousElementSibling.value.replace(/[0-9]/g, '')).trim().toLowerCase();
                    var units = ['cm', 'mm', 'q', 'in', 'pc', 'pt', 'px', 'em', 'ex', 'ch', 'rem', 'lh', 'vw', 'vh', 'vmin', 'vmax',]
                    var dval = ui.value;

                    dval += (units.indexOf(dvalUnit) !== -1 ? dvalUnit : 'px');

                    console.log(dval)
                    console.log(dvalUnit)

                    this.previousElementSibling.value = dval;
                    output(this.parentNode.dataset.prop, dval)

                }
            })
            $(this).append(sl.get(0));
        }
    })


})

 var ActiveNode = null;
ActiveNode = mw.top().app.liveEdit.getSelectedNode();

$(document).on('ready', function () {
    if (typeof window.liveEditDomTree !== 'undefined' && window.liveEditDomTree) {
        window.liveEditDomTree.select(ActiveNode);
        selectNode(ActiveNode);
    }
})
// mw.top().app.canvas.on('canvasDocumentClick', function () {
//
//     ActiveNode = mw.top().app.liveEdit.getSelectedNode();
//
//     activeTree();
// });
// mw.top().app.canvas.on('canvasDocumentClick', function () {
//
//     ActiveNode = mw.top().app.liveEdit.getSelectedNode();
//
//     activeTree();
// });

// mw.top().app.canvas.on('refreshSelectedElement', function () {
//
//
// });

window.document.addEventListener('refreshSelectedElement', function (e) {


    ActiveNode = mw.top().app.liveEdit.getSelectedNode();

    if (typeof window.liveEditDomTree !== 'undefined' && window.liveEditDomTree) {
        window.liveEditDomTree.select(ActiveNode);
        selectNode(ActiveNode);
    } else {
        setTimeout(function () {
            if (typeof window.liveEditDomTree !== 'undefined' && window.liveEditDomTree) {
                window.liveEditDomTree.select(ActiveNode);
                selectNode(ActiveNode);
            }
        }, 1000);
    }


    //  activeTree();
});


var reset = function () {
    var ActiveNode = mw.top().app.liveEdit.getSelectedNode();

    if (!ActiveNode) {
        return;
    }
    var sel = mw.tools.generateSelectorForNode(ActiveNode);
    var data = {};
    data[sel] = {
        selector: sel,
        value: "reset"
    };

    targetMw.$.post(mw.settings.api_url + "current_template_save_custom_css", data, function (data) {
        mw.notification.success('Element styles restored');
        mw.tools.refresh(top.document.querySelector('link[href*="live_edit.css"]'))
    }).fail(function () {

    });
    mw.top().app.registerChange(ActiveNode)
    // activeTree();
};


var CSSShadow;

var _activeTree = null;
var _pauseActiveTree = false;
var activeTree = function () {

    // deprecated
    // this is now handled by the liveEditDomTree
    return;

    if (!ActiveNode || _pauseActiveTree) {
        return;
    }


    var canvasDocument = mw.top().app.canvas.getDocument();

    var getParent = function (node) {
        var canvasDocument = mw.top().app.canvas.getDocument();

        if (!node || node === canvasDocument.body || !node.parentNode || targetMw.tools.hasClass(node, 'edit')) {
            return false;
        }
        if (node.parentNode.id) {
            return node.parentNode;
        } else if (targetMw.tools.hasClass(node.parentNode, 'edit')) {
            return node.parentNode;
        } else {
            return getParent(node.parentNode);
        }
    };
    var data = [], curr = ActiveNode, count = 0;


    while (curr && curr !== canvasDocument.body) {
        var custom = !!curr.className;


        //  if(curr.id || mw.tools.hasClass(curr, 'edit') || custom){
        if (curr.id || mw.tools.hasClass(curr, 'edit') || custom) {
            count++;
            if (count > 4) {
                //   break;
            }
            var parent = getParent(curr);
            var selector = targetMw.tools.generateSelectorForNode(curr)
                .replace(/\[/g, 'mw')
                .replace(/["']/g, '')
                .replace(/\]/g, 'mw');
            var parent_selector = 0;

            if (parent) {
                parent_selector = targetMw.tools.generateSelectorForNode(parent)
                    .replace(/\[/g, 'mw')
                    .replace(/["']/g, '')
                    .replace(/\]/g, 'mw');
            }
            var ttitle = curr.tagName.toLowerCase() + (curr.classList.length ? ('.' + curr.className.split(' ').join('.')) : '');
            if (mw.tools.hasClass(curr, 'module')) {
                ttitle = curr.dataset.mwTitle || curr.dataset.type;
            }
            var item = {
                selector: selector,
                id: curr.id,
                type: 'page',
                title: ttitle,
                parent_id: parent_selector,
                parent_type: 'page',
                element: curr
            };

            data.push(item)
        } else {
            parent = null;
        }
        if (parent) {
            curr = parent;
        } else {
            curr = null;
        }

    }
    data = data.reverse();

    $('#tree').empty();
    // selectedData: selectedData,
    //
    //     var selectedData = []
    //     data.unshift({
    //         id: 0,
    //         type: 'category',
    //         title: 'None',
    //         "parent_id": 0,
    //         "parent_type": "category"
    //     });


    _activeTree = new mw.tree({
        element: '#tree',
        data: data,
        saveState: false,
        selectable: true,
        singleSelect: true,
        contextMenu: [
            {
                title: 'Reset styles',
                icon: 'mw-icon-reload',
                action: function () {
                    reset()
                }
            }
        ]
    });

    _activeTree.openAll();
    // _activeTree.select($('#tree li:last')[0]);

    if (ActiveNode && ActiveNode.id) {
        for (var i = 0; i < data.length; i++) {
            if (data[i].element.id === ActiveNode.id) {
                console.log(data[i].element, ActiveNode.id, data[i].id)

                if (typeof window.liveEditDomTree !== 'undefined' && window.liveEditDomTree) {
                    window.liveEditDomTree.select(data[i].element);
                }

                //alert('TODO SELECT NODE '+ActiveNode.id)
                // _activeTree.select(data[i].id, 'page', false);
                // _activeTree.select($('#tree li
                //  _activeTree.select($('#tree li:first')[0], 'page', false);
                break;
            }
        }
    }


    // pagesTree.show(id, 'category');
    // pagesTree.select(id, 'category', true);

    $(_activeTree).on('selectionChange', function (e, data) {
        _pauseActiveTree = true;
        if (data[0]) {
            mw.top().app.liveEdit.selectNode(data[0].element);
            //     targetMw.liveEditSelector.select(data[0].element);
        }
        setTimeout(function () {
            _pauseActiveTree = false;
        }, 10)
    })


};


var _prepare = {
    shadow: function () {
        var root = document.querySelector('#shadow');
        if (!root) {
            return;
        }
        CSSShadow = new mw.propEditor.schema({
            schema: [
                {
                    interface: 'shadow',
                    id: 'boxShadow',
                    pickerPosition: 'top-left'
                }
            ],
            element: root,
            size: 'medium'
        });
        $(CSSShadow).on('change', function (e, id, val) {
            output(id, val)
        });
        $('.mw-ui-field', root).addClass('mw-ui-field-medium');
        $('.mw-ui-btn', root).addClass('mw-ui-btn-medium');
    },
    border: function () {

        var bordercolor = document.querySelector('#border-color')
        colorPickers.push(mw.colorPicker({
            element: bordercolor,
            position: bordercolor.dataset.position || 'top-right',
            onchange: function (color) {

                $(bordercolor).trigger('colorChange', color)

            },
            color: this.value
        }));

        var pos = positionSelector();

        $('#border-size, #border-color, #border-type').on('change input colorChange', function () {

            var prop = 'border',
                propval = $('#border-position').val();
            if (propval !== 'all') {
                prop += (propval)
            }
            var color = $('#border-color').val() || '#111',
                type = $('#border-type').val() || 'solid',
                size = $('#border-size').val() || '1';
            output(prop, size + 'px ' + type + ' ' + color);
        });
    },
    units: function () {
        var units = [
            'px', '%', 'rem', 'em', 'vh', 'vw'
        ];
        units = [];// todo: add units
        $('mw-accordion-item.mw-accordion-item-css .unit:not(.ready)').each(function () {
            this.classList.add('ready')
            // var select = $('<select style="width: 60px"/>');
            var select = $('<span class="reset-field  tip" data-tipposition="top-right" data-tip="Restore default value"><i class="mdi mdi-history"></i></span>');
            select.on('click', function () {
                var prev = $(this).prev();
                var prop = prev.attr('data-prop');
                var css = getComputedStyle(ActiveNode);
                var val = '';
                if (prop) {
                    output(prop, '');
                    val = css[prop];
                }


                var isbg = prev.prev().find('.mw-field-color-indicator-display');
                if (isbg.length) {
                    isbg.css('backgroundColor', val);
                }


                prev.val(val);
                prev.find('input').val(val);
                var n = parseFloat(val)
                $('.mw-range.ui-slider', prev).slider('value', !isNaN(n) ? n : 0)
            });
            $('input', this)
                .attr('type', 'range');

            //.after('<input>')
            $.each(units, function () {
                select.append('<option value="' + this + '">' + this + '</option>');
            });
            select.on('change', function () {
                var prev = $(this).parent().prev();
                var prop = prev.attr('data-prop');
                if (prop) {
                    output(prop, prev.find('input').val() + this.value)

                }
            });

            $(this).after(select);
            $('input', this).on('input', function () {
                var $el = $(this);
                var parent = $el.parent()
                var val = $el.val().trim();
                var prop = parent.attr('data-prop');
                if (prop) {
                    if (parseFloat(val) == val) {
                        output(prop, val ? val + 'px' : '');
                    } else {
                        output(prop, val ? val + 'px' : '');
                    }
                }

            })
        })
    }
};
var _populate = {
    margin: function (css) {
        if (!css || !css.get) return;
        var margin = css.get.margin(undefined, true);
        mw.$('.margin-top').val(parseFloat(margin.top));
        mw.$('.margin-right').val(parseFloat(margin.right));
        mw.$('.margin-bottom').val(parseFloat(margin.bottom));
        mw.$('.margin-left').val(parseFloat(margin.left));
    },
    border: function (css) {
        if (!css || !css.get) return;
        var border = css.get.border(true);

        var frst = {};
        for (var i in border) {
            if (border[i].width !== 0) {
                frst = border[i];
                break;
            }
        }
        var size = frst.width || 0;
        var color = frst.color || 'rgba(0,0,0,1)';
        var style = frst.style || 'none';

        mw.$('#border-position').val('all');
        mw.$('#border-size').val(size);
        mw.$('#border-color').val(color);
        mw.$('#border-type').val(style);
    },
    padding: function (css) {
        var padding = css.get.padding(undefined, true);
        mw.$('.padding-top').val(parseFloat(padding.top));
        mw.$('.padding-right').val(parseFloat(padding.right));
        mw.$('.padding-bottom').val(parseFloat(padding.bottom));
        mw.$('.padding-left').val(parseFloat(padding.left))
    },
    common: function (css) {
        $('.unit').each(function () {
            var val = css.css[this.dataset.prop];
            var btn = $('.mw-ui-btn', this.parentNode)[0];
            if (btn) {
                btn._defaultValue = '';
            }
            if (val) {
                var nval = parseFloat(val);
                var isn = !isNaN(nval);
                var unit = val.replace(/[0-9]/g, '').replace(/\./g, '');
                val = isn ? nval : val;
                if (btn) {
                    btn._defaultValue = val;
                }
                $('input', this).val(val);
                $('.mw-range.ui-slider', this).slider('value', isn ? nval : 0)
            }
        });
        $(".colorField").each(function () {
            if (this.dataset.prop) {
                var color = css.css[this.dataset.prop];
                var hasColor = color !== 'rgba(0, 0, 0, 0)';
                if (color) {
                    if (hasColor) {
                        this.value = mw.color.rgbOrRgbaToHex(color);
                    } else {
                        this.value = 'none';
                        this.previousElementSibling.querySelector('.mw-field-color-indicator-display').style.backgroundColor = 'transparent'
                    }
                }

                this.type = 'text'

                var el = this;

                el.placeholder = '#ffffff';
                if (this.parentNode.querySelector('.mw-field-color-indicator') === null) {
                    $(this).before('<span class="mw-field-color-indicator"><span class="mw-field-color-indicator-display"></span></span>')
                }
                var indikatorDisplay = this.parentNode.querySelector('.mw-field-color-indicator-display');
                indikatorDisplay.style.backgroundColor = this.value

                colorPickers.push(mw.colorPicker({
                    element: this,
                    position: this.dataset.position || 'bottom-right',
                    onchange: function (color) {
                        if (el.dataset.prop) {
                            output(el.dataset.prop, color);
                        } else if (el.dataset.func) {
                            eval(el.dataset.func + '(' + color + ')');
                        } else {
                            $(el).trigger('colorChange', color)
                        }
                        indikatorDisplay.style.backgroundColor = color
                    },
                    color: this.value
                }))

            }
        });
        $(".background-preview").css('backgroundImage', css.css.backgroundImage)
    },
    textAlign: function (css) {
        var align = css.get.alignNormalize();
        $(".text-align .active").removeClass('active');
        $(".text-align .ta-" + align).addClass('active');
    },
    regular: function (css) {
        $(".regular").each(function () {
            var propName = this.dataset.prop;
            if (propName === 'fontFamily') {
                var val = css.css[this.dataset.prop].replace(/\"/g, "");
                var el = $(this)
                el.val(val);
                if (el.val() === null) {
                    el.append('<option value="' + val + '">' + val + '</option>');
                    el.val(val);
                }
            } else {
                $(this).val(css.css[this.dataset.prop])
            }
        });
    }
};

var populate = function (css) {
    $.each(_populate, function () {
        this(css)
    })
};

var sccontainertype = function (value) {
    var cnt = mw.tools.firstParentOrCurrentWithAnyOfClasses(ActiveNode, ['container', 'container-fluid']);
    if (cnt && mw.tools.isEditable(cnt)) {
        cnt.classList.remove('container');
        cnt.classList.remove('container-fluid');
        cnt.classList.add(value);
        mw.top().app.registerChange(cnt);
    }
}
var scColumns = function (property, value) {
    var tg = ActiveNode;
    while (tg && tg.classList) {
        if (!tg.classList.contains('col') && !tg.className.contains('col-')) {
            tg = tg.parentNode;
        } else {
            break
        }
    }

    if (property === 'col-desktop') {
        for (var i = 1; i <= 12; i++) {
            tg.classList.remove('col-' + i)
            tg.classList.remove('col-lg-' + i)
        }
        // tg.classList.add('col-' + value)
        tg.classList.add('col-lg-' + value)
    } else if (property === 'col-tablet') {
        for (var i = 1; i <= 12; i++) {
            tg.classList.remove('col-md-' + i);
        }
        tg.classList.add('col-md-' + value);
    } else if (property === 'col-mobile') {
        for (var i = 1; i <= 12; i++) {
            tg.classList.remove('col-xs-' + i)
            tg.classList.remove('col-sm-' + i)
        }
        tg.classList.add('col-sm-' + value)
    }
}

var OverlayNode = null;

var specialCases = function (property, value) {
    if (!property) return;
    if (property.includes('col-')) {
        scColumns(property, value)
        return true;
    } else if (OverlayNode && property === 'overlay-color') {
        OverlayNode.style.backgroundColor = value;
        mw.top().app.registerChange(OverlayNode);
        return true;
    } else if (OverlayNode && property === 'overlay-blend-mode') {
        OverlayNode.style.mixBlendMode = value;
        mw.top().app.registerChange(OverlayNode);
        return true;
    }

}

var populateSpecials = function (css) {
    var holder = document.getElementById('columns-edit');
    var colDesktop = document.querySelector('[data-prop="col-desktop"]')
    var coltablet = document.querySelector('[data-prop="col-tablet"]')
    var colmobile = document.querySelector('[data-prop="col-mobile"]')
    colDesktop.value = ''
    coltablet.value = ''
    colmobile.value = ''
    holder.style.display = 'none';

    var containerType = document.querySelector('#container-type');
    containerType.style.display = 'none';
    var ol = document.getElementById('overlay-edit');
    ol.style.display = 'none';
    OverlayNode = null;

    if (ActiveNode) {
        var cnt = mw.tools.firstParentOrCurrentWithAnyOfClasses(ActiveNode, ['container', 'container-fluid']);
        if (cnt && mw.tools.isEditable(cnt)) {
            containerType.style.display = '';
            if (cnt.classList.contains('container-fluid')) {
                document.querySelector('[name="containertype"][value="container-fluid"]').checked = true
            } else if (cnt.classList.contains('container')) {
                document.querySelector('[name="containertype"][value="container"]').checked = true
            }
        }

        var layout = mw.tools.firstParentOrCurrentWithAnyOfClasses(ActiveNode, ['module-layouts']);
        if (layout) {
            var overlay = layout.querySelector('.mw-layout-overlay');
            OverlayNode = overlay;
            if (overlay) {
                var overlayCss = getComputedStyle(overlay);
                var bgColor = overlayCss.backgroundColor;
                var blend = overlayCss.mixBlendMode;
                var oc = document.getElementById('overlay-color')
                var blendfield = document.getElementById('overlay-blend-mode')
                blendfield.value = blend
                oc.value = bgColor
                oc.parentNode.querySelector('.mw-field-color-indicator-display').style.backgroundColor = bgColor
                ol.style.display = '';
            }

        }


        var col = null;
        var tg = ActiveNode;
        while (tg && tg.classList) {
            if (!tg.classList.contains('col') && !tg.className.contains('col-')) {
                tg = tg.parentNode;
            } else {
                if (mw.tools.isEditable(tg)) {
                    col = tg;
                    break
                } else {
                    tg = tg.parentNode;
                }
            }
        }
        if (col) {
            holder.style.display = '';
            var lg = col.className.split('col-lg-')[1] || '';
            var md = col.className.split('col-md-')[1] || '';
            var sm = col.className.split('col-sm-')[1] || '';
            colDesktop.value = lg.split(' ')[0];
            coltablet.value = md.split(' ')[0];
            colmobile.value = sm.split(' ')[0];
        }
    }
}

var output = function (property, value) {
    var mwTarget = targetMw;


    ActiveNode = mw.top().app.liveEdit.getSelectedNode();


    if (ActiveNode && ActiveNode.length) {
        ActiveNode = ActiveNode[0]
    }
    if (ActiveNode) {
        if (!specialCases(property, value)) {


            mw.top().app.cssEditor.temp(ActiveNode, property.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase(), value)

            ActiveNode.setAttribute('staticdesign', true);
        }
        mw.top().app.registerChange(ActiveNode);

    }

};

var _defaultValuesArray = ['auto', 'inherit', 'unset'];

var numValue = function (value) {
    if (/^\d+$/.test(value)) {
        return value + 'px';
    }
    return value;
};


var defaultValuesUIProp = null
var defaultValuesUITarget = null;
var _defaultValuesUI;
var dfslider
var defaultValuesUI = function (prop, targetNode) {

    if (!_defaultValuesUI) {
        var node = mw.element('<div class="default-values-list" />');
        _defaultValuesUI = node.get(0);
        dfslider = mw.element('<div class="mw-range default-values-list-slider" />');
        node.append(dfslider)
        $(dfslider.get(0)).slider({
            slide: function (event, ui) {
                var val = parseFloat(ui.value);
                if (isNaN(val)) {
                    val = 0;
                }
                output(defaultValuesUIProp, numValue(val))
                defaultValuesUITarget.value = val
            }
        })
        _defaultValuesArray.forEach(function (val) {
            var li = mw.element({
                tag: 'span',
                props: {
                    innerHTML: val,

                    dataset: {
                        value: val
                    }
                },

            })
            li.on('click', function () {
                output(prop, numValue(this.dataset.value))
            })
            node.append(li)
        })
        document.body.appendChild(node.get(0))
        document.body.addEventListener('click', function (e) {
            if (e.target !== _defaultValuesUI && !_defaultValuesUI.contains(e.target) && e.target !== defaultValuesUITarget) {
                _defaultValuesUI.style.display = 'none';
            }
        })
    }

    targetNode.addEventListener('focus', function (e) {
        var val = parseFloat(targetNode.value);
        if (isNaN(val)) {
            val = 0;
        }
        $(dfslider.get(0)).slider("value", val)
        defaultValuesUIProp = prop;
        defaultValuesUITarget = e.target;
        var rect = e.target.getBoundingClientRect();
        _defaultValuesUI.style.top = (rect.top + scrollY + rect.height) + 'px'
        var lft = (rect.left + scrollX);
        if ((lft + _defaultValuesUI.offsetWidth) > innerWidth) {
            lft = innerWidth - (_defaultValuesUI.offsetWidth + 10);
        }
        _defaultValuesUI.style.left = lft + 'px'
        _defaultValuesUI.style.display = 'block';
    })


    return node;
}

var init = function () {

    var spacesFields = [
        ['.margin-top', 'marginTop'],
        ['.margin-right', 'marginRight'],
        ['.margin-bottom', 'marginBottom'],
        ['.margin-left', 'marginLeft'],

        ['.padding-top', 'paddingTop'],
        ['.padding-right', 'paddingRight'],
        ['.padding-bottom', 'paddingBottom'],
        ['.padding-left', 'paddingLeft'],
    ];

    spacesFields.forEach(function (item) {
        var node = mw.element(item[0])
        node.on('input', function () {
            output(item[1], numValue(this.value))
        });
        defaultValuesUI(item[1], node.get(0))
    })


    $('.text-align > span').on('click', function () {
        output('textAlign', this.dataset.value);
        $('.text-align > .active').removeClass('active');
        $(this).addClass('active')
    });
    $(".colorField").each(function () {
        var el = this;
        el.oninput = function () {
            var color = this.value;
            if (el.dataset.prop) {
                output(el.dataset.prop, color);
            } else if (el.dataset.func) {
                eval(el.dataset.func + '(' + color + ')');
            } else {
                $(el).trigger('colorChange', color)
            }
        }
    });

    $(".regular").on('input', function () {
        output(this.dataset.prop, this.value)
    });

    $("#background-remove").on("click", function () {
        $('.background-preview').css('backgroundImage', 'none');
        output('backgroundImage', 'none')
    });
    $("#background-reset").on("click", function () {
        output('backgroundImage', '');
    });

    $("#background-select-gradient").on("click", function () {

        var mTitle = "Pick gradient color";
        var defaults = {
            url: mw.external_tool('gradient_picker'),
            width: 500,
            height: 500,
            autoHeight: true,
            overlay: true,
            title: 'Gradient Picker',
            className: 'mw-dialog-module-settings',
            closeButtonAction: 'remove'
        };
        var options = {};
        var settings = Object.assign({}, defaults, options)
        return targetMw.dialogIframe(settings);

    });
    $("#background-select-item").on("click", function () {
        var dialog;
        var picker = new (mw.top()).filePicker({
            type: 'images',
            label: false,
            autoSelect: false,
            footer: true,
            _frameMaxHeight: true,

            onResult: function (data) {
                if (!data) return;
                var url = data.src ? data.src : data;
                output('backgroundImage', 'url(' + url + ')');
                $('.background-preview').css('backgroundImage', 'url(' + url + ')')
                dialog.remove()
            },
            cancel: function () {
                dialog.remove()
            }
        });
        dialog = mw.top().dialog({
            content: picker.root,
            title: mw.lang('Select image'),
            footer: false,
            width: 1200
        })


    });

    _prepare.units();
    _prepare.border();
    _prepare.shadow();

    $('.mw-ui-box-header').on('click', function () {
        setTimeout(function () {
            $(document.body).trigger('click')
        }, 400)
    });


};
// function setTargetMw(mw) {
//     targetMw = mw;
//
//
// }

function selectNode(node) {
    var nodes = [node]
    if (nodes && nodes[0] && nodes[0].nodeType === 1) {
        var css = mw.CSSParser(nodes[0]);
        populate(css);
        ActiveNode = nodes[0];

        populateSpecials(css);


        var clsdata = [];
        $.each(nodes[0].className.split(' '), function () {
            var cls = this.trim();
            if (cls) {
                clsdata.push({title: cls})
            }
        });
        (window.classes || initClasses()).setData(clsdata)
    }

    if (ActiveNode) {
        var can = ActiveNode.textContent === ActiveNode.innerHTML;
        mw.$('#text-mask')[can ? 'show' : 'hide']();
        mw.$('#text-mask-field')[0].checked = mw.tools.hasClass(ActiveNode, 'mw-bg-mask');
        if (ActiveNode.classList.contains('module') || !mw.tools.parentsOrCurrentOrderMatchOrOnlyFirst(ActiveNode.parentNode, ['edit', 'module'])) {
            $('#classtags-accordion').hide();
        } else {
            $('#classtags-accordion').show();
        }
    }

    animationGUI.set()
}


$(document).ready(function () {
    mw.$('.mw-field input').attr('autocomplete', 'off')


    $(".mw-element-spacing-editor input")
        .on('focus', function () {

            $(".mw-ese-holder.active").removeClass('active');
            $(this).parent().addClass('active');
        })
        .on('blur', function () {
            $(".mw-ese-holder.active").removeClass('active');
        });


    init();

    var editorRoot = document.getElementById('css-editor-root');

    setInterval(function () {

        editorRoot.classList[ActiveNode ? 'remove' : 'add']('disabled');
    }, 700)
    mw.components._init();


});

$(window).on('load', function () {

    document.body.addEventListener('click', function () {
        // colorPickers.forEach(function (cp) {
        //      if(cp.hide) {
        //         cp.hide()
        //     } else {
        //         cp.style.display = 'none'
        //     }
        //
        // })
    })

});



initClasses = function () {
    if (!window.classes) {
        window.classes = new mw.tags({
            element: '#classtags',
            data: [],
            inputField: true,
            wrap: true,
            hideItem: function (item) {
                return item.title.indexOf('module') !== -1
                    || item.title.indexOf('element') !== -1
                    || item.title.indexOf('allow-drop') !== -1
                    || item.title.indexOf('cloneable') !== -1
                    || item.title.indexOf('ui-draggable') !== -1
                    || item.title.indexOf('ui-draggable-handle') !== -1
                    || item.title === 'edit'
                    || item.title === 'safe-mode'
                    || item.title === 'parallax'
                    || item.title === 'changed'
                    || item.title === 'pull-left'
                    || item.title === 'left'
                    || item.title === 'right'
                    || item.title === 'pull-right'
                    || item.title === 'mw-bg-mask'
                    || item.title === 'lipsum'
                    || item.title.indexOf('nodrop') !== -1;
            }
        });
        $(classes).on('change', function (e, item, data) {
            var cls = [];
            $.each(data, function () {
                cls.push(this.title);
            });
            ActiveNode.setAttribute('class', cls.join(' '))
            mw.top().app.registerChange(ActiveNode);
        });
    }
    return window.classes;
};


$(window).on('load', function () {
    initClasses()

    mw.components._init();
})













var animations = {
    common: [
        'none',
        'bounce',
        'flash',
        'pulse',
        'rubberBand',
        'shakeX',
        'shakeY',
        'headShake',
        'swing',
        'tada',
        'wobble',
        'jello',
        'heartBeat',
        'flip',
        'flipInX',
        'flipInY',

        'hinge',
        'jackInTheBox',
        'rollIn'
    ],
    appear: [
        'backInDown',
        'backInLeft',
        'backInRight',
        'backInUp',
        'bounceIn',
        'bounceInDown',
        'bounceInLeft',
        'bounceInRight',
        'bounceInUp',
        'fadeIn',
        'fadeInDown',
        'fadeInDownBig',
        'fadeInLeft',
        'fadeInLeftBig',
        'fadeInRight',
        'fadeInRightBig',
        'fadeInUp',
        'fadeInUpBig',
        'fadeInTopLeft',
        'fadeInTopRight',
        'fadeInBottomLeft',
        'fadeInBottomRight',
        'lightSpeedInRight',
        'lightSpeedInLeft',
        'rotateIn',
        'rotateInDownLeft',
        'rotateInDownRight',
        'zoomIn',
        'zoomInDown',
        'zoomInLeft',
        'zoomInRight',
        'zoomInUp',
        'slideInDown',
        'slideInLeft',
        'slideInRight',
        'slideInUp'
    ]
};

;(function(){
    var animationApi = {
        preview: function (animation) {
            return targetMw.__animate(animation)
        },
        remove: function (id) {

            var item = targetMw.__pageAnimations.find(function (item) {  return item.id === id });
            var citem = Object.assign({}, item)
            targetMw.__pageAnimations.splice(targetMw.__pageAnimations.indexOf(item), 1);
            Array.from(targetMw.doc.querySelectorAll(citem.selector)).forEach(function (node){
                if(node.$$mwAnimations && node.$$mwAnimations.length) {
                    var i = node.$$mwAnimations.findIndex(function(a){return a.id === id});
                    if(i > -1) {
                        node.$$mwAnimations.splice(i, 1);
                    }
                }
            })
        },
        add: function (node, obj) {
            var sel = mw.tools.generateSelectorForNode(node);
            var id = mw.id('animation');



            if (!node.$$mwAnimations) {
                node.$$mwAnimations = [];
            }

            var curr = node.$$mwAnimations.find(function (item) {
                return item.when === obj.when;
            });

            if (curr) {
                //this.remove(curr.id)
            }
            var config = Object.assign({selector: sel, id: id}, obj)
            node.$$mwAnimations.push(config)
            targetMw.__pageAnimations.push(config)
            animationApi.preview(config)

            return config;
        }
    };

    var textCase = function (text) {
        var result = text.replace(/([A-Z])/g, " $1");
        return result.charAt(0).toUpperCase() + result.slice(1);
    }

    window.animationGUI = {

        _types:  {
            animationSelector: function() {
                var wrap = mw.element({
                    props: { className: 's-field'},
                    content: [
                        {
                            tag: 'label',
                            props: { innerHTML: mw.lang('Motion')},
                        },
                        {

                            props: {  className: 'mw-field mw-field-flat'},
                        }
                    ]
                });
                var select = mw.element('<select />');
                for (var cat in animations) {
                    if (animations.hasOwnProperty(cat)) {
                        var group = document.createElement('optgroup');
                        group.label = textCase(cat)
                        select.append(group);
                        var groupAnims = animations[cat];
                        var i = 0;
                        for( ; i < groupAnims.length; i++) {
                            var option = document.createElement('option');
                            option.value = groupAnims[i];
                            option.innerHTML = textCase(groupAnims[i]);
                            group.appendChild(option)
                        }
                    }
                }
                mw.element('.mw-field', wrap).append(select)
                return wrap;

            },
            speed: function () {
                var root = mw.element({
                    props: { className: 's-field'},

                    content: [
                        {
                            tag: 'label',
                            props: { innerHTML: mw.lang('Speed')},
                        },
                        {
                            props: {
                                className: 'mw-field mw-field-flat unit',
                                dataset: {
                                    // after: 'sec.'
                                },
                            },
                            content: {
                                tag: 'input',
                                props: { type: 'text', placeholder: mw.lang('Speed in seconds') },
                            }
                        }

                    ]
                })
                var sl = mw.element('<div class="mw-range default-values-list-slider" />');
                mw.element('.mw-field', root).append(sl)
                $(sl.get(0)).slider({
                    min: 0.1,
                    max: 5,
                    step: 0.1,
                    slide: function( event, ui ) {
                        // this.previousElementSibling.value = Math.round((ui.value / 1000) * 100) / 100;
                        this.previousElementSibling.value = ui.value;
                        $(this.previousElementSibling).trigger('input')
                    }
                })
                return  root;
            },
            when: function () {
                return  mw.element(
                    {

                        props: {
                            style: {
                                marginTop: '10px'
                            }
                        },
                        content: [
                            {
                                props: { className: 's-field'},
                                content: [
                                    {
                                        tag: 'label',
                                        props: { innerHTML: mw.lang('Trigger')},
                                    },
                                    {
                                        props: {
                                            className: 'mw-field mw-field-flat',
                                        },
                                        content: [
                                            {
                                                tag: 'select',
                                                props: {  },
                                                content: [
                                                    { tag: 'option', props: { value: 'onAppear', innerHTML: mw.lang('When element appears on screen')}},
                                                    { tag: 'option', props: { value: 'onHover', innerHTML: mw.lang('When mouse is over')}},
                                                    { tag: 'option', props: { value: 'onClick', innerHTML: mw.lang('When element is clicked')}},
                                                ]
                                            },
                                        ]
                                    },
                                ]
                            },

                        ]
                    }

                );
            }
        },
        renderSingle: function (anim) {
            var box = mw.element('<div class="mw-module-settings-box" />');
            var del = mw.element('<a class="mw-ui-link animation-clear-btn">Clear</a>')
            var typeSelect = animationGUI._types.animationSelector();
            var speed = animationGUI._types.speed();
            var when = animationGUI._types.when();

            del.on('click', function () {
                mw.element('select', typeSelect)
                    .val('none')
                    .get(0)
                    .dispatchEvent(new Event('input'));
            })

            box
                .append(del)
                .append(typeSelect)
                .append(speed)
                .append(when);

            mw.element('select', when).val(anim.when).on('input', function () {
                var curr = mw.__pageAnimations.find(function(a){
                    return !!anim.id || a.id === anim.id
                });
                anim.when = this.value;
                if(curr) {
                    curr.when = this.value;
                }
                mw.top().app.registerChange(ActiveNode)
            });

            $('.mw-range', speed.get(0)).slider('value', parseFloat(anim.speed))

            mw.element('select', typeSelect).val(anim.animation).on('input', function () {

                mw.element('select', when).get(0).disabled = this.value === 'none';
                mw.element('input', speed.get(0)).get(0).disabled = this.value === 'none';
                $( '.mw-range', speed.get(0) ).slider( this.value === 'none' ? "disable" : "enable" );


                anim.animation = this.value;
                targetMw.__animate(anim)
                var curr = mw.__pageAnimations.find(function(a){
                    return !!anim.id || a.id === anim.id
                });
                if(curr) {
                    curr.animation = this.value;
                }
                mw.top().app.registerChange(ActiveNode)

            });

            $('input', speed.get(0)).val(anim.speed).on('input', function () {
                $('.mw-range', speed.get(0)).slider('value', parseFloat(this.value))
                var val = this.value + 's';
                anim.speed = val;
                targetMw.__animate(anim);
                var curr = mw.__pageAnimations.find(function(a){
                    return !!anim.id || a.id === anim.id
                });
                if (curr) {
                    curr.speed = val;
                }
                mw.top().app.registerChange(ActiveNode)
            });

            mw.element('select', when).get(0).disabled = anim.animation === 'none';
            mw.element('input', speed.get(0)).get(0).disabled = anim.animation === 'none';
            $( '.mw-range', speed.get(0) ).slider( anim.animation === 'none' ? "disable" : "enable" );

            return box
        },
        add: function () {
            var el = document.querySelector('#animations');
            var anim = {
                selector: mw.tools.generateSelectorForNode(ActiveNode),
                animation: 'none',
                speed: 1,
                when: 'onAppear',
                id: mw.id('animation')
            }
            animationApi.add(ActiveNode, anim);
            el.appendChild(animationGUI.renderSingle(anim).get(0));
        },
        set: function () {
            var el = document.querySelector('#animations');
            while (el.firstChild) {
                el.removeChild(el.firstChild);
            }
            /* Add blank animation to each */
            if(!ActiveNode.$$mwAnimations) {

                animationApi.add(ActiveNode, {
                    selector: mw.tools.generateSelectorForNode(ActiveNode),
                    animation: 'none',
                    speed: 1,
                    when: 'onAppear',
                    id: mw.id('animation')
                });
            }

            if(ActiveNode && ActiveNode.$$mwAnimations) {
                ActiveNode.$$mwAnimations.forEach(function (anim){
                    el.appendChild(animationGUI.renderSingle(anim).get(0));
                });
            }

        }
    };

    window.animationGUI = animationGUI;

})();
