

mw.coreIcons = {
    category:'mw-icon-category',
    page:'mw-icon-page',
    home:'mw-icon-home',
    shop:'mai-market2',
    post:'mai-post'
};


mw.tags = function(options){

    options.element = $(options.element)[0];

    this.options = options;
    var scope = this;
    /*
        data: [
            {title:'Some tag', icon:'<i class="icon"></i>'},
            {title:'Some tag', icon:'icon'}
        ]
    */

    this.refresh = function(){
        $(scope.options.element).empty();
        this.rend();
    }

    this.setData = function(data){
        this.options.data = data;
        this.refresh()
    }
    this.rend = function(){
        $.each(this.options.data, function(i){
            var data = $.extend({index:i}, this);
            scope.options.element.appendChild(scope.tag(data))
        })
    }

     this.createIcon = function (config) {
        var ic = config.icon;

        if(!ic && config.type){
            ic = mw.coreIcons[config.type]

        }
        var icon;
        if(typeof ic === 'string' && ic.indexOf('<') === -1){
            icon = mwd.createElement('i');
            icon.className = ic;
        }
        else{
            icon = ic;
        }

        return $(icon)[0];
     }

     this.removeTag = function (index) {
        var item = this.options.data[index];
        this.options.data.splice(index,1);
        this.refresh();
        $(scope).trigger('tagRemoved', [item]);
     }

     this.tag = function (options) {

            var config = {
                close:true,
                tagBtnClass:'mw-ui-btn mw-ui-btn-medium',
            }

            $.extend(config, options)

            var tag_holder = mwd.createElement('span');
            var tag_close = mwd.createElement('span');

            tag_close._index = config.index;
            tag_holder._index = config.index;
            tag_holder._config = config;
            tag_holder.dataset.index = config.index;

            tag_holder.className = config.tagBtnClass;

            tag_holder.innerHTML = '<span class="tag-label-content">' + config.title + '</span>';

            var icon = this.createIcon(config)

            if(icon){
                $(tag_holder).prepend(icon);
            }

            tag_holder.onclick = function (e) {
                if(e.target !== tag_close){
                    $(scope).trigger('tagClick', [this._config, this._index, this])
                }
            }

            tag_close.className = 'mw-icon-close';
            if(config.close){
                tag_close.onclick = function () {
                    scope.removeTag(this._index);
                }
            }
            tag_holder.appendChild(tag_close);
            return tag_holder;
        }
        this.rend();
}

mw.treeTags = function(options){
    this.options = options;
    var scope = this;

    var tagsHolder = options.tagsHolder || $('<div class="mw-tree-tag-tags-holder"></div>');
    var treeHolder = options.treeHolder || $('<div class="mw-tree-tag-tree-holder"></div>');

    var treeSettings = $.extend({}, this.options, {element:treeHolder})
    var tagsSettings = $.extend({}, this.options, {element:tagsHolder, data:this.options.selectedData || []});

    this.tree = new mw.tree(treeSettings);

    this.tags = new mw.tags(tagsSettings);

    $( this.options.element ).append(tagsHolder);
    $( this.options.element ).append(treeHolder);

     $(this.tags).on('tagRemoved', function(event, item){
         scope.tree.unselect(item);
     });
     $(this.tree).on('selectionChange', function(event, selectedData){
        scope.tags.setData(selectedData)
    });

};