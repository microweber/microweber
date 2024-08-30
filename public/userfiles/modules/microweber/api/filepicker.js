
mw.require('uploader.js');
mw.require('filemanager.js');

var fileUploadProgress = function (fileName, progress, target) {
    if (!target) {
        target = document.body;
    }

    var node = target.querySelector('[data-file="' + fileName + '"]');
    if(!node) {
        node = document.createElement('div');
        node.dataset.file = fileName;
        target.appendChild(node);
    }

    mw.progress({element: node, action: fileName}).set(progress);

};


mw.filePickerDialog = (conf = {pickerOptions: {}, dialogOptions: {}}, callback) => {

    if(typeof conf === 'function') {
        callback = conf;
        conf = {pickerOptions: {}, dialogOptions: {}}
    }
    if(!conf.pickerOptions) {
        conf.pickerOptions = {}
    }

    if(!conf.dialogOptions) {
        conf.dialogOptions = {}
    }

    const {pickerOptions, dialogOptions} = conf;
    let dialog, picker;


    let filePickerDefaults = {
        type: 'images',
        label: false,
        autoSelect: false,
        footer: true,
        _frameMaxHeight: true,
    }

    let filepickerSettings = Object.assign({}, filePickerDefaults, pickerOptions);
    let url;

    picker = new mw.filePicker({
        ...filepickerSettings,
        onResult: function(res) {
            url = res.src ? res.src : res;
            if(!url) {
                dialog.remove();
                return
            }
            url = url.toString();
            result = url
            dialog.remove();

        }
    });




    var dialogDefaults = {
        content: picker.root,
        title: mw.lang('Select image'),
        footer: false,
        width: 860,
    }

    var dialogSettings = Object.assign({}, dialogDefaults, dialogOptions);

    let resolver, result = null;
    const promise = new Promise(resolve => {
        resolver = resolve;
    });

    dialog = mw.top().dialog(dialogSettings);
    picker.$cancel.on('click', function(){
        dialog.remove()
    });
    mw.top().$(dialog).on('Remove', function(){

        resolver(url);
        if(typeof callback === 'function') {
            callback.call(undefined, url)
        }
    });



    return {
        picker, dialog, promise: () => promise
    }
}

mw.filePicker = function (options) {
    options = options || {};
    var scope = this;
    var defaults = {
        components: [
            {type: 'desktop', label: mw.lang('My computer')},
            {type: 'url', label: mw.lang('URL')},
            {type: 'server', label: mw.lang('Uploaded')},
            {type: 'library', label: mw.lang('Media library')}
        ],
        nav: 'tabs', // 'tabs | 'dropdown',
        hideHeader: false,
        dropDownTargetMode: 'self', // 'self', 'dialog'
        element: null,
     //   type: null, // filetypes to accept, ex. images, videos, audios, documents
        footer: true,
        okLabel: mw.lang('OK'),
        cancelLabel: mw.lang('Cancel'),
        uploaderType: 'big', // 'big' | 'small'
        confirm: function (data) {

        },
        cancel: function () {

        },
        label: mw.lang('Media'),
        autoSelect: true, // depending on the component
        boxed: false,
        multiple: false
    };



    this.settings = $.extend(true, {}, defaults, options,  );
    this.settings.type = this.settings.type || this.settings.accept;

    this.$root = $('<div class="'+ (this.settings.boxed ? ('card mb-3') : '') +' mw-filepicker-root form-control-live-edit-label-wrapper"></div>');
    this.root = this.$root[0];

    $.each(this.settings.components, function (i) {
        this['index'] = i;
    });


    this.components = {
        _$inputWrapper: function (label) {
            var html = '<div class="mw-ui-field-holder">' +
                /*'<label>' + label + '</label>' +*/
                '</div>';
            return mw.$(html);
        },
        url: function () {
            var $input = $('<input class="form-control" placeholder="http://example.com/image.jpg">');
            scope.$urlInput = $input;
            var $wrap = this._$inputWrapper(scope._getComponentObject('url').label);
            $wrap.append($input);
            $input.before('<label class="form-label">'+mw.lang('Insert file url')+'</label>');

            $input.on('input', function () {
                var val = this.value.trim();
                scope.setSectionValue(val || null, false);
            });


            return $wrap[0];
        },

        desktop: function () {


            var _setdesktopType = function () {
                var $zone;
                if(scope.settings.uploaderType === 'big') {
                    $zone = $('<div class="mw-file-drop-zone">' +
                        '<div class="mw-file-drop-zone-holder">' +
                        '<div class="mw-file-drop-zone-img"></div>' +
                        '<div class="mw-ui-progress-small"><div class="mw-ui-progress-bar" style="width: 0%"></div></div>' +
                        '<span class="btn btn-pill  btn-primary">'+mw.lang('Add file')+'</span> ' +
                        '<p>'+mw.lang('or drop files to upload')+'</p>' +
                        '</div>' +
                        '</div>');
                } else if(scope.settings.uploaderType === 'small') {
                    $zone = $('<div class="mw-file-drop-zone mw-file-drop-zone-small mw-file-drop-square-zone"> <div class="mw-file-drop-zone-holder"> <span class="mw-ui-link">'+mw.lang('Add file')+'</span> ' +
                        '<p>'+mw.lang('or drop files to upload')+'</p>' +
                        '</div>' +
                        '</div>')
                }
                var $el = $(scope.settings.element).eq(0);
                $el.removeClass('mw-filepicker-desktop-type-big mw-filepicker-desktop-type-small');
                $el.addClass('mw-filepicker-desktop-type-' + scope.settings.uploaderType);
                scope.uploaderHolder.empty().append($zone);
            };

            var $wrap = this._$inputWrapper(scope._getComponentObject('desktop').label);
            scope.uploaderHolder = mw.$('<div class="mw-uploader-type-holder"></div>');
            _setdesktopType();

            $wrap.append(scope.uploaderHolder);
            scope.uploader = mw.upload({
                element: $wrap[0],
                multiple: scope.settings.multiple,
                type: scope.settings.type,
                on: {
                    progress: function (prg, obj, b) {
                        scope.uploaderHolder.find('.mw-ui-progress-bar').stop().animate({width: prg.percent + '%'}, 'fast');
                    },
                    fileUploadError: function (file) {
                        $(scope).trigger('FileUploadError', [file]);
                    },
                    fileAdded: function (file) {
                        $(scope).trigger('FileAdded', [file]);
                    },
                    fileUploaded: function (file) {
                        scope.setSectionValue(file);

                        $(scope).trigger('FileUploaded', [file]);
                        if ( !scope.settings.disableFileAutoSelect ) {
                            scope.result();
                        }
                        if (scope.settings.fileUploaded) {
                            scope.settings.fileUploaded(file);
                        }
                        if (!scope.settings.multiple) {
                            mw.notification.success('File uploaded');
                            scope.uploaderHolder.find('.mw-file-drop-zone-img').css('backgroundImage', 'url('+file.src+')');
                        }
                        // scope.uploaderHolder.find('.mw-file-drop-zone-img').css('backgroundImage', 'url('+file.src+')');
                    }
                }
            });
            return $wrap[0];
        },
        server: function () {
            var $wrap = this._$inputWrapper(scope._getComponentObject('server').label);
            /*mw.load_module('files/admin', $wrap, function () {

            }, {'filetype':'images'});*/

            $(scope).on('$firstOpen', function (e, el, type) {
                 if (type === 'server') {
                      var fm = mw.FileManager({
                         element:$wrap[0],
                         canSelectFolder: false,
                         selectable: true,
                         multiselect: scope.settings.multiple,
                         stickyHeader: true,
                         selectableRow: true,
                         type: scope.settings.type
                     });
                     fm.on('insert', function (){
                         scope.result();
                     });
                     fm.on('insertByValue', function (val){
                         scope.setSectionValue(val.map(a=>a.url));
                         scope.result();
                     });
                     fm.on('selectionChanged', function (val){

                         scope.setSectionValue(val.map(a=>a.url));
                         if (scope.settings.autoSelect) {
                             scope.result();
                         }
                     });
                }
            });

            return $wrap[0];
        },
        library: function () {
            var $wrap = this._$inputWrapper(scope._getComponentObject('library').label);
            $(scope).on('$firstOpen', function (e, el, type) {
                var comp = scope._getComponentObject('library');
                if (type === 'library') {
                    mw.tools.loading(el, true);
                    var fr = mw.top().tools.moduleFrame('pictures/media_library');
                    $wrap.append(fr);
                    if(scope.settings._frameMaxHeight) {
                        fr.style.maxHeight = '60vh';
                        fr.scrolling = 'yes';
                    }
                    fr.onload = function () {
                        mw.tools.loading(el, false);
                        this.contentWindow.mw.on.hashParam('select-file', function (pval) {
                            var url = pval.toString();
                            scope.setSectionValue(url);
                            if (scope.settings.autoSelect) {
                                scope.result();
                            }
                        });
                    };
                }
            });

            /*mw.load_module('pictures/media_library', $wrap);*/
            return $wrap[0];
        }
    };

    this.hideUploaders = function (type) {
        mw.$('.mw-filepicker-component-section', this.$root).hide();
    };

    this.showUploaders = function (type) {
        mw.$('.mw-filepicker-component-section', this.$root).show();
    };


    this.settings.components = this.settings.components.filter(function (item) {
        return !!scope.components[item.type];
    });


    this._navigation = null;
    this.__navigation_first = [];

    this.navigation = function () {
        this._navigationHeader = document.createElement('div');
        this._navigationHeader.className = 'mw-filepicker-component-navigation-header mb-3' + (this.settings.boxed ? 'card-header no-border' : '');
        if (this.settings.hideHeader) {
            this._navigationHeader.style.display = 'none';
        }
        if (this.settings.label) {
            this._navigationHeader.innerHTML = '<h6><strong>' + this.settings.label + '</strong></h6>';
        }
        this._navigationHolder = document.createElement('div');
        if(this.settings.nav === false) {

        }
        else if(this.settings.nav === 'tabs') {

            var ul = $('<div class="form-control-live-edit-label-wrapper d-flex mw-live-edit-resolutions-wrapper mx-0 w-100" />');
            this.settings.components.forEach(function (item) {
                ul.append('<a href="javascript:;" class="js-filepicker-pick-type-tab-'+item.type+'   btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100" data-type="'+item.type+'">'+item.label+'</a>');
            });
            this._navigationHolder.appendChild(this._navigationHeader);
            this._navigationHeader.appendChild(ul[0]);
            setTimeout(function () {
                scope._tabs = mw.tabs({
                    nav: $('a', ul),
                    tabs: $('.mw-filepicker-component-section', scope.$root),
                    activeClass: 'active',
                    onclick: function (el, event, i) {
                        if(scope.__navigation_first.indexOf(i) === -1) {
                            scope.__navigation_first.push(i);
                            $(scope).trigger('$firstOpen', [el, this.dataset.type]);
                        }
                        scope.manageActiveSectionState();


                        setTimeout(function () {

                            var dialog =  mw.top().dialog.get();
                            if(dialog){
                                dialog.center();
                            }

                        }, 50);
                    }
                });
            }, 78);
        } else if(this.settings.nav === 'dropdown') {
            var select = $('<select class="form-select btn-as-link" data-style="btn-sm" data-width="auto" data-title="' + mw.lang('Add file') + '"/>');
            scope._select = select;
            this.settings.components.forEach(function (item) {
                select.append('<option class="nav-item" value="'+item.type+'">'+item.label+'</option>');
            });

            this._navigationHolder.appendChild(this._navigationHeader);
            this._navigationHeader.appendChild(select[0]);
            select.on('changed.bs.select', function (e, xval) {
                var val = select.selectpicker('val');
                var componentObject = scope._getComponentObject(val) ;
                var index = scope.settings.components.indexOf(componentObject);
                var items = $('.mw-filepicker-component-section', scope.$root);
                if(scope.__navigation_first.indexOf(val) === -1) {
                    scope.__navigation_first.push(val);
                    $(scope).trigger('$firstOpen', [items.eq(index)[0], val]);
                }
                if(scope.settings.dropDownTargetMode === 'dialog') {
                    var temp = document.createElement('div');
                    var item = items.eq(index);
                    item.before(temp);
                    item.show();
                    var footer = false;
                    if (scope._getComponentObject('url').index === index ) {
                        footer =  document.createElement('div');
                        var footerok = $('<button type="button" class=" btn btn-primary">' + scope.settings.okLabel + '</button>');
                        var footercancel = $('<button type="button" class=" btn">' + scope.settings.cancelLabel + '</button>');
                        footerok.disabled = true;
                        footer.appendChild(footercancel[0]);
                        footer.appendChild(footerok[0]);
                        footer.appendChild(footercancel[0]);
                        footercancel.on('click', function () {
                            scope.__pickDialog.remove();
                        });
                        footerok.on('click', function () {
                            scope.setSectionValue(scope.$urlInput.val().trim() || null);
                            if (scope.settings.autoSelect) {
                                scope.result();
                            }
                            // scope.__pickDialog.remove();
                        });
                    }

                    scope.__pickDialog = mw.top().dialog({
                        overlay: true,
                        content: item,
                        beforeRemove: function () {
                            $(temp).replaceWith(item);
                            item.hide();
                            scope.__pickDialog = null;
                        },
                        footer: footer
                    });
                } else {
                    items.hide().eq(index).show();
                }
            });
        }
        this.$root.prepend(this._navigationHolder);

    };
    this.__displayControllerByTypeTime = null;

    this.displayControllerByType = function (type) {
        type = (type || '').trim();
        var item = this._getComponentObject(type) ;
        clearTimeout(this.__displayControllerByTypeTime);
        this.__displayControllerByTypeTime = setTimeout(function () {
            if(scope.settings.nav === 'tabs') {
                mw.$('[data-type="'+type+'"]', scope.$root).click();
            } else if(scope.settings.nav === 'dropdown') {
                $(scope._select).selectpicker('val', type);
            }
        }, 10);
    };



    this.footer = function () {
        if(!this.settings.footer || this.settings.autoSelect) return;
        this._navigationFooter = document.createElement('div');
        this._navigationFooter.className = 'modal-footer mw-ui-form-controllers-footer mw-filepicker-footer ' + (this.settings.boxed ? 'card-footer' : '');
        this.$ok = $('<button type="button" class="mw-admin-action-links mw-adm-liveedit-tabs mw-liveedit-button-animation-component">' + this.settings.okLabel + '</button>');
        this.$cancel = $('<button type="button" class="mw-admin-action-links mw-adm-liveedit-tabs mw-liveedit-button-animation-component">' + this.settings.cancelLabel + '</button>');
        this._navigationFooter.appendChild(this.$cancel[0]);
        this._navigationFooter.appendChild(this.$ok[0]);
        this.$root.append(this._navigationFooter);
        this.$ok[0].disabled = true;
        this.$ok.on('click', function () {
            scope.result();
        });
        this.$cancel.on('click', function () {
            scope.settings.cancel()
        });
    };

    this.result = function () {
        var activeSection = this.activeSection();
        if(this.settings.onResult) {
            this.settings.onResult.call(this, activeSection._filePickerValue);
        }
        $(scope).trigger('Result', [activeSection._filePickerValue]);
    };

    this.getValue = function () {
        return this.activeSection()._filePickerValue;
    };

    this._getComponentObject = function (type) {
        return this.settings.components.find(function (comp) {
            return comp.type && comp.type === type;
        });
    };

    this._sections = [];
    this.buildComponentSection = function () {
        var main = mw.$('<div class="'+(this.settings.boxed ? 'card-body' : '') +' mw-filepicker-component-section"></div>');
        this.$root.append(main);
        this._sections.push(main[0]);
        return main;
    };

    this.buildComponent = function (component) {
        if(this.components[component.type]) {
            return this.components[component.type]();
        }
    };

    this.buildComponents = function () {
        $.each(this.settings.components, function () {
            var component = scope.buildComponent(this);
            if(component){
                var sec = scope.buildComponentSection();
                sec.append(component);
            }
        });
    };

    this.build = function () {
        this.navigation();
        this.buildComponents();
        if(this.settings.nav === 'dropdown') {
            $('.mw-filepicker-component-section', scope.$root).hide().eq(0).show();
        }
        this.footer();
    };

    this.init = function () {
        this.build();
        if (this.settings.element) {
            $(this.settings.element).eq(0).append(this.$root);
        }
        if($.fn.selectpicker) {
            $('select', scope.$root).selectpicker();
        }
    };

    this.hide = function () {
        this.$root.hide();
    };
    this.show = function () {
        this.$root.show();
    };

    this.activeSection = function () {
        return $(this._sections).filter(function (){
            return $(this).css('display') !== 'none';
        })[0];
    };

    this.setSectionValue = function (val, close) {
        close = typeof close === 'undefined' ? true : close;
        var activeSection = this.activeSection();
        if(activeSection) {
            activeSection._filePickerValue = val;
        }

        if(close && scope.__pickDialog) {
            scope.__pickDialog.remove();
        }
        this.manageActiveSectionState();
    };
    this.manageActiveSectionState = function () {
        // if user provides value for more than one section, the active value will be the one in the current section
        var activeSection = this.activeSection();
        if (this.$ok && this.$ok[0]) {
            this.$ok[0].disabled = !(activeSection && activeSection._filePickerValue);
        }
    };

    this.init();
};
