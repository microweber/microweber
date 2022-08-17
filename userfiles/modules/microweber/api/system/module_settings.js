mw.moduleSettings = function(options){
    /*
        options:

            data: [Object],
            element: NodeElement || selector string || jQuery array,
            schema: mw.propEditor.schema,
            key: String
            group: String,
            autoSave: Boolean

    */

    var defaults = {
        sortable: true,
        autoSave: true
    };

    if(!options.schema || !options.data || !options.element) return;

    this.options = $.extend({}, defaults, options);

    this.options.element = mw.$(this.options.element)[0];
    this.value = this.options.data.slice();

    var scope = this;

    this.items = [];

    if(!this.options.element) {
        return;
    }

    this.interval = function (c) {
        if(!this._interval) {
            this._intervals = [];
            this._interval = setInterval(function () {
                if(scope.options.element && document.body.contains(scope.options.element)) {
                    scope._intervals.forEach(function (func){
                        func.call(scope);
                    });
                } else {
                    clearInterval(scope._interval);
                }
            }, 1000);
        }
    };

    this.createItemHolderHeader = function(i){
        if(this.options.header){
            var header = document.createElement('div');
            header.className = "mw-ui-box-header";
            var render = this.options.header
                .replace(/{count}/g, '<span class="mw-module-settings-box-index">'+(i+1)+'</span>');

            header.innerHTML = render;
            var valReflect = header.querySelector('[data-reflect]');
            if(valReflect) {
                setTimeout(function (valReflect){
                    var node = header.parentElement.querySelector('[name="'+valReflect.dataset.reflect+'"]');
                    if(node) {
                        valReflect.innerHTML = node.value;
                        node.addEventListener('input', function (){
                            valReflect.innerHTML = node.value;
                        });
                    }

                }, 100, valReflect);

            }
            mw.$(header).on('click', function(){
                mw.$(this).next().slideToggle();
            });
            return header;

        }
    };
    this.headerAnalize = function(header){
        mw.$("[data-action='remove']", header).on('click', function(e){
            e.stopPropagation();
            e.preventDefault();
            var el = this;
            mw.confirm('Are you sure you want to delete this item?', function () {
                $(mw.tools.firstParentOrCurrentWithAnyOfClasses(el, ['mw-module-settings-box'])).remove();
                scope.refactorDomPosition();
                scope.autoSave();
            })

        });
    };
    this.createItemHolder = function(i){
        i = i || 0;
        var holder = document.createElement('div');
        var holderin = document.createElement('div');
        holder.className = 'mw-ui-box mw-module-settings-box';
        holderin.className = 'mw-ui-box-content mw-module-settings-box-content';
        holderin.style.display = 'none';
        holder.appendChild(holderin);
        if(!this.options.element.children) {
            this.options.element.appendChild(holder);
        } else if (!this.options.element.children[i]){
            this.options.element.appendChild(holder);
        } else if (this.options.element.children[i]){
            $(this.options.element.children[i]).before(holder);
        }


        return holder;
    };

    this.addNew = function(pos, method){
        method = method || 'new';
        pos = pos || 0;
        var _new;

        var val = this.value[0];

        if(val) {
            _new = mw.tools.cloneObject(JSON.parse(JSON.stringify(this.value[0])));

        } else {
            _new = {};
        }


        if(_new.title) {
            _new.title += ' - new';
        } else if(_new.name) {
            _new.name += ' - new';
        }
        if(method === 'new'){
            $.each(this.options.schema, function(){
                if(this.value) {
                    if(typeof this.value === 'function') {
                        _new[this.id] = this.value();
                    } else {
                        _new[this.id] = this.value;
                    }
                }
            });
        } else if(method === 'blank') {
            for (var i in _new) {
                _new[i] = '';
                if(i === 'name' || i === 'title') {
                    _new[i] = 'New';
                }
            }
        }





        this.value.splice(pos, 0, _new);
        this.createItem(_new, pos);
        scope.refactorDomPosition();
        scope.autoSave();
    };

    this.remove = function(pos){
        if(typeof pos === 'undefined') return;
        this.value.splice(pos, 1);
        this.items.splice(pos, 1);
        mw.$(this.options.element).children().eq(pos).animate({opacity: 0, height: 0}, function(){
            mw.$(this).remove();
        });
        mw.$(scope).trigger('change', [scope.value/*, scope.value[i]*/]);
    };

    this.createItem = function(curr, i){
        var box = this.createItemHolder(i);
        var header = this.createItemHolderHeader(i);
        var item = new mw.propEditor.schema({
            schema: this.options.schema,
            element: box.querySelector('.mw-ui-box-content')
        });
        mw.$(box).prepend(header);
        this.headerAnalize(header);
        this.items.push(item);
        item.options.element._prop = item;
        item.setValue(curr);
        mw.$(item).on('change', function(){
            $.each(item.getValue(), function(a, b){
                // todo: faster approach
                var index = mw.$(box).parent().children('.mw-module-settings-box').index(box);
                scope.value[index][a] = b;
            });
            $('[data-bind]', header).each(function () {
                var val = item.getValue();
                var bind = this.dataset.bind;
                if(val[bind]){
                    this.innerHTML = val[bind];
                } else {
                    this.innerHTML = this.dataset.orig;
                }
            });
            mw.$(scope).trigger('change', [scope.value/*, scope.value[i]*/]);
        });
        $('[data-bind]', header).each(function () {
            var val = item.getValue();
            var bind = this.dataset.bind;
            this.dataset.orig = this.innerHTML;
            if(val[bind]){
                this.innerHTML = val[bind];
            }
        });
    };

    this._autoSaveTime = null;
    this.autoSave = function(){
        if(this.options.autoSave){
            clearTimeout(this._autoSaveTime);
            this._autoSaveTime = setTimeout(function(){
                scope.save();
            }, 500);
        }
    };

    this.refactorDomPosition = function(){
        scope.items = [];
        scope.value = [];
        mw.$(".mw-module-settings-box-index", this.options.element).each(function (i) {
            mw.$(this).text(i+1);
        });
        mw.$('.mw-module-settings-box-content', this.options.element).each(function(i){
            scope.items.push(this._prop);
            scope.value.push(this._prop.getValue());
        });
        mw.$(scope).trigger('change', [scope.value]);
    };

    this.create = function(){
        this.value.forEach(function(curr, i){
            scope.createItem(curr, i);
        });
        if(this.options.sortable && $.fn.sortable){
            var conf = {
                update: function (event, ui) {
                    setTimeout(function(){
                        scope.refactorDomPosition();
                        scope.autoSave();
                    }, 10);
                },
                handle:this.options.header ? '.mw-ui-box-header' : undefined,
                axis:'y'
            };
            if(typeof this.options.sortable === 'object'){
                conf = $.extend({}, conf, this.options.sortable);
            }
            mw.$(this.options.element).sortable(conf);
        }
    };

    this.init = function(){
        this.create();
    };

    this.save = function(){
        var key = (this.options.key || this.options.option_key);
        var group = (this.options.group || this.options.option_group);
        if( key && group){
            var options = {
                group:this.options.group,
                key:this.options.key,
                value:this.toString()
            };
            mw.options.saveOption(options, function(){
                mw.notification.msg(scope.savedMessage || mw.msg.settingsSaved)
            });
        }
        else{
            if(!key){
                console.warn('Option key is not defined.');
            }
            if(!group){
                console.warn('Option group is not defined.');
            }
        }

    };


    this.toString = function(){
        return JSON.stringify(this.value);
    };

    this.init();
};
