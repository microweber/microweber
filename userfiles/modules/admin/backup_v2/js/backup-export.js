mw.SelectableList = function (options) {
    options = options || {};
    var defaults = {

    };
    var scope = this;

    this.settings = $.extend({}, defaults, options);

    if(!this.settings.element) {
        return;
    }
    this.$element = $(this.settings.element);
    if(!this.$element.length) return;

    this.createSingle = function (item) {
        var tpl = '<label>' +
            '<span class="mw-selectable-list-icon">' +
            '    <i></i>' +
            '</span>' +
            '<span class="mw-selectable-list-input">' +
            '    <span class="mw-ui-check">' +
            '        <input>' +
            '        <span></span>' +
            '    </span>' +
            '</span>' +
            '<span class="mw-selectable-list-content">' +
            '    <span class="mw-selectable-list-content-title"></span>' +
            '    <span class="mw-selectable-list-content-description">' +
            '    </span>' +
            '</span>' +
            '</label>';
        var $tpl = $(tpl);
        if(item.icon) {
            $tpl.find('.mw-selectable-list-icon i').addClass(item.icon.className).html(item.icon.content);
        } else {
            $tpl.find('.mw-selectable-list-icon').remove()
        }
        if(item.input) {
            var inp = $tpl.find('input');
            inp.attr('type', item.input.type || 'checkbox');
            if(item.input.name) {
                inp.attr('name', item.input.name);
            }
            if(item.input.value) {
                inp.attr('value', item.input.value);
            }
            inp[0].checked = item.input.checked === true;
        } else {
            $tpl.find('.mw-selectable-list-input').remove();
        }
        if(item.title) {
            $tpl.find('.mw-selectable-list-content-title').html(item.title);
        } else {
            $tpl.find('.mw-selectable-list-content-title').remove();
        }
        if(item.description) {
            $tpl.find('.mw-selectable-list-content-description').html(item.description);
        } else {
            $tpl.find('.mw-selectable-list-content-description').remove();
        }
        return $tpl[0];
    };

    this.createFromData = function () {
        this.root = document.createElement('div');
        this.root.className = 'mw-selectable-list';
        for(var i =0; i<this.settings.data.length; i++) {
            this.root.appendChild(this.createSingle(this.settings.data[i]));
        }
    };

    this.states = function () {
        $('input', this.root).each(function () {
            if(this.checked) {
                mw.tools.addClass(mw.tools.firstParentWithTag(this, 'label'), 'active');
            } else {
                mw.tools.removeClass(mw.tools.firstParentWithTag(this, 'label'), 'active');
            }
        });
    };

    this.initEvents = function () {
        var scope = this;
        $('input', this.root).on('change', function () {
            scope.states();
        });
    };

    this.selectAll = function () {
        $('input[type="checkbox"]', this.root).each(function () {
            this.checked = true;
        });
    };

    this.selectNone = function () {
        $('input[type="checkbox"]', this.root).each(function () {
            this.checked = false;
        });
    };

    this.init = function () {
        if(this.settings.data && this.settings.data.length) {
            this.createFromData();
            this.$element.html(this.root)
        }
        this.states();
        this.initEvents();
    };

    this.init();
}


mw.Stepper = function (options) {
    options = options || {};
    var defaults = {
        items: '*',
        /*

        validation: {
            step1: function () {
                return true;
            }
        }

        */
    };
    var scope = this;

    this.settings = $.extend({}, defaults, options);

    if(!this.settings.element) {
        return;
    }

    this.validateStep = function (step) {
        if(this.settings.validation && this.settings.validation['step' + step]){
            return this.settings.validation['step' + step]();
        }
        return true;
    };

    this.next = function () {
        var next = this.step() + 1;
        if(next > this.getItems().length) {
            return;
        }
        this.step(next);
    };
    this.prev = function () {
        var next = this._step - 1;
        if(next < 1) {
            return;
        }
        this.step(next);
    };

    this.first = function () {
        this.step(0);
    };
    this.last = function () {
        var items = this.getItems();
        this.step(items.length);
    };

    this.back = this.prev;

    this.getItems = function () {
        return this.$element.children(this.settings.items);
    };

    this.step = function (step) {
        if( step === 0) {
             step = 1;
        }
        if(!step) {
            return this._step;
        }
        if(step > this._step){
            if(!this.validateStep(this._step)) {
                return;
            }
        }
        this._step = step;
        this.getItems().removeClass('active').eq(step-1).addClass('active');
    };

    this.prepare = function () {
        this._step = this.settings.step || 1;
        if(this._step < 1) {
            this._step = 1;
        }
        this.$element = mw.$(this.settings.element).not('.mw-stepper-ready').eq(0).addClass('mw-stepper mw-stepper-ready');
        this.element = this.$element[0];
        this.getItems().addClass('mw-stepper-item');
    };

    this.selfButtons = function () {
        mw.$('[data-mwstepper]', this.element).each(function () {
            var attr = this.dataset.mwstepper.trim(), el = $(this);
            if(attr === 'next') {
                el.on('click', function () {
                    scope.next();
                    if(mw.Dialog.elementIsInDialog(this)) {
                        mw.dialog.get(this).center();
                        //console.log(scope.step());
                    }
                });
            }
            if(attr === 'prev') {
                el.on('click', function () {
                    scope.prev();
                    if(mw.Dialog.elementIsInDialog(this)) {
                        mw.dialog.get(this).center()
                    }
                });
            }
        });
    };

    this.init = function () {
        this.prepare();
        this.step(this._step);
        this.selfButtons();
    };

    this.init();
};

mw.stepper = function (options) {
    return new mw.Stepper(options);
};

mw.backup_export = {

	select_all: function() {
		exportContentSelector.select(exportContentSelector.options.data);
	},

	unselect_all: function() {
		exportContentSelector.unselectAll();
	},

	choice: function(template_holder) {
mw.log(mw.$(template_holder).html())
		this.dialog = mw.dialog({
		    title: 'Select data which you want to export',
		    id: 'mw_backup_export_modal',
            content: mw.$(template_holder).html(),
            width: 595,
            closeButton: false,
            closeOnEscape: false,
		});

        mw.backup_export.stepper = mw.stepper({
            element: this.dialog.dialogContainer.querySelector('.export-stepper')
        });

        mw.backup_export.typesSelector = new mw.SelectableList({
            element: '#backup-select-options-to-export',
            data: [
                {
                    input: {name: 'export_items', value: 'orders'},
                    icon: { className: 'mdi mdi-shopping-outline'  },
                    title: 'Export all orders',
                    description: 'If you check this checkbox then all of your orders will be added into the backup export'
                },
                {
                    input: {name: 'export_items', value: 'users'},
                    icon: { className: 'mdi mdi-account-multiple-outline'  },
                    title: 'Export users',
                    description: 'This check box will include all your user database in the backup'
                },
                {
                    input: {name: 'export_items', value: 'menus'},
                    icon: { className: 'mdi mdi-menu'  },
                    title: 'Export menus',
                    description: 'If you want to include the existing menus in the bachup use this check'
                },
                {
                    input: {name: 'export_items', value: 'comments'},
                    icon: { className: 'mdi mdi-comment-account-outline'  },
                    title: 'Export comments',
                    description: 'Export all comments of your website'
                },
                {
                    input: {name: 'export_items', value: 'forms_data,forms_lists,notifications'},
                    icon: { className: 'mdi mdi-email-outline'  },
                    title: 'Export contacts',
                    description: 'Export all contact lists of your website'
                },
                {
                    input: {name: 'export_items', value: 'forms_data,forms_lists'},
                    icon: { className: 'mdi mdi-format-list-text'  },
                    title: 'Export Posts & Contents',
                    description: 'Export all posts & contents of your website'
                },
                {
                    input: {name: 'export_items', value: 'options'},
                    icon: { className: 'mdi mdi-cog-outline'  },
                    title: 'Export  website settings',
                    description: 'All settings lik, website name, company details etc, '
                },
                {
                    input: {name: 'export_items', value: 'template'},
                    icon: { className: 'mdi mdi-text-box-check-outline' },
                    title: 'Export current website template',
                    description: 'All files from current website template'
                },
                {
                    input: {name: 'export_items', value: 'template_default_content'},
                    icon: { className: 'mdi mdi-format-list-text' },
                    title: 'Export default content for template',
                    description: 'All content data for the current website template'
                },
                {
                    input: {name: 'export_media', value: true},
                    icon: { className: 'mdi mdi-image-multiple-outline'  },
                    title: 'Include media files  <span>(images, videos, etc..)</span>',
                    description: ''
                }

            ]
        });


        $.get(mw.settings.api_url + "content/get_admin_js_tree_json", function (treeData) {

            window.exportContentSelector = new mw.tree({
                data: treeData,
                selectable: true,
                multiPageSelect: true,
                element: mw.backup_export.dialog.dialogContainer.querySelector('#quick-parent-selector-tree'),
                saveState:false
            });

        });
        var all = mw.$('.js-export-format', mw.backup_export.dialog.dialogContainer);
        all.on('input', function () {
            all.not(this).val($(this).val())
        })
	},

	export_selected: function(manifest) {
	    var scope = this;
		mw.backup_export.get_log_check('start');
		manifest.format = $('.js-export-format').val();
		$.get(route('admin.backup.export'), manifest , function(exportData) {
			if (exportData.data.download) {
			    scope.done = true;
				mw.backup_export.get_log_check('stop');
                scope.exportLog('<a href="'+exportData.data.download+'" class="mw-ui-btn" style="font-weight:bold;"><i class="mw-icon-download"></i> &nbsp;Download your backup</a>');
			 	mw.notification.success(exportData.success);
                mw.progress({
                    element:'.js-export-log',
                    action:''
                }).hide();
                $('.export-step-4-action').html('Export completed!');
                mw.reload_module('admin/backup_v2/manage');
			} else {
				mw.backup_export.export_selected(manifest);
			}
			if(exportData.precentage){
			    mw.progress({
                    element:'.js-export-log',
                    action:''
                }).set(exportData.precentage);
            }
		 });
	},
    exportLogNode: null,
    exportLogContent: null,
    exportLog: function (string) {
        if(!this.exportLogNode) {
            this.exportLogNode = $('.js-export-log');
            this.exportLogContent = $('<div class="js-export-log-content"></div>');
            this.exportLogNode.append(this.exportLogContent);
        }
        this.exportLogContent.html(string);
    },
	get_log_check: function(action) {
        mw.backup_export.get_log();
	},
    done:false,
    canGet: true,
	get_log: function(c) {
	    if(!this.canGet) return;
        this.canGet = false;
        var scope = this;
		$.ajax({
		    url: userfilesUrl + 'backup-export-session.log',
		    success: function (data) {
		        if(scope.done) {
                    scope.done = false;
                }
                else {
                    data = data.replace(/\n/g, "<br />");
                    scope.exportLog(data);
                    setTimeout(function () {
                        scope.canGet = true;
                        scope.get_log();
                    }, 2222);
                }

		    },
		    error: function() {
               // scope.exportLog('Error opening log file.');
		    },
            always: function () {

            }
		});
	},

	export_fullbackup_start: function() {
        this.exportLog('Generating full backup...');

        var send_uri = 'all&format=' + $('.js-export-format').val() + '&include_media=true';


        var include_modules = [];
        var include_templates = [];
        $('.js-export-modules:checked').each(function(){
            include_modules.push(this.value);
        });
        $('.js-export-templates:checked').each(function(){
            include_templates.push(this.value);
        });

        if (include_modules) {
            send_uri += '&include_modules=' + include_modules.join(',');
        }

        if (include_templates) {
            send_uri += '&include_templates=' + include_templates.join(',');
        }

        mw.backup_export.export_selected(send_uri);
        mw.backup_export.stepper.last();

        $('.export-step-4-action').html('Exporting your content')
	},

	export_start: function () {

        var selected_content = exportContentSelector.options.selectedData;
        var selected_export_items = [];


        /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
        $("[name='export_items']:checked").each(function() {
            selected_export_items.push($(this).val());
        });

        var selected;
        selected = selected_export_items.join(',') ;

        if(!selected.length && !selected_content.length){
            mw.alert("Please check at least one of the checkboxes");
            return;
        }

        var export_manifest = {};
        export_manifest.content_ids = [];
        export_manifest.categories_ids = [];
        export_manifest.items = selected;

        selected_content.forEach(function(item, i){
            if(item.type === 'category' ){
                export_manifest.categories_ids.push(item.id);
            } else {
                export_manifest.content_ids.push(item.id);
            }
        });

        this.exportLog('Generating backup...');

        mw.backup_export.export_selected(export_manifest);
        mw.backup_export.stepper.last();
        $('.export-step-4-action').html('Exporting your content');
    }
}
