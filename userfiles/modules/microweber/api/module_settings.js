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

    this.options.element = $(this.options.element)[0];
    this.value = this.options.data.slice();

    var scope = this;

    this.items = [];

    if(!this.options.element) return;

    this.createItemHolder = function(){
        var holder = document.createElement('div');
        holder.className = 'mw-ui-box mw-ui-box-content';
        this.options.element.appendChild(holder);
        return holder;
    }

    this.createItem = function(curr, i){
        var item = new mw.propEditor.schema({
            schema: this.options.schema,
            element: this.createItemHolder()
        });
        this.items.push(item);
        item.setValue(curr);
        $(item).on('change', function(){
            $.each(this.getValue(), function(a, b){
                scope.value[i][a] = b;
            });
            $(scope).trigger('change', [scope.value, scope.value[i]]);
        })
    }

    this._autoSaveTime = null;
    this.autoSave = function(){
        if(this.options.autoSave){
            clearTimeout(this._autoSaveTime);
            this._autoSaveTime = setTimeout(function(){
                scope.save()
            }, 500);
        }
    }

    this.create = function(){
        this.value.forEach(function(curr, i){
            scope.createItem(curr, i);
        });
        if(this.options.sortable && $.fn.sortable){
            var conf = {
                update: function (event, ui) {
                    scope.autoSave();
                }
            };
            if(typeof this.options.sortable === 'object'){
                conf = $.extend({}, conf, this.options.sortable);
            }
            $(this.options.element).sortable(conf)
        }
    }

    this.init = function(){
        this.create()
    }

    this.save = function(){
        var val = this.toString();

        // ...options.save(this.options.key, this.options.group, val)
    }


    this.toString = function(){
        return JSON.stringify(this.value)
    }

    this.init();
}