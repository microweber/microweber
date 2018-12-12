

mw.coreIcons = {
    category:'mw-icon-category',
    page:'mw-icon-page',
    home:'mw-icon-home',
    shop:'mai-market2',
    post:'mai-post'
};


mw.tags = mw.chips = function(options){

    "use strict";

    options.element = $(options.element)[0];
    options.size = options.size || 'medium';

    this.options = options;
    this.options.map = this.options.map || { title: 'title', value: 'id', image: 'image', icon: 'icon' };
    this.map = this.options.map;
    var scope = this;
    /*
        data: [
            {title:'Some tag', icon:'<i class="icon"></i>'},
            {title:'Some tag', icon:'icon', image:'http://some-image/jpg.png'},
            {title:'Some tag', color:'warn'},
        ]
    */

    this.refresh = function(){
        $(scope.options.element).empty();
        this.rend();
    };

    this.setData = function(data){
        this.options.data = data;
        this.refresh();
    };
    this.rend = function(){
        $.each(this.options.data, function(i){
            var data = $.extend({index:i}, this);
            scope.options.element.appendChild(scope.tag(data))
        });
    };

    this.dataValue = function(data){
        if(typeof data === 'string'){
            return data;
        }
        else{
            return data[this.map.value]
        }
    };

    this.dataImage = function(data){
        if(data[this.map.image]){
            var img = document.createElement('span');
            img.className = 'mw-ui-btn-img';
            img.style.backgroundImage = 'url('+data.image+')';
            return img;
        }
    };

    this.dataTitle = function(data){
        if(typeof data === 'string'){
            return data;
        }
        else{
            return data[this.map.title]
        }
    };

    this.dataIcon = function(data){
        if(typeof data === 'string'){
            return;
        }
        else{
            return data[this.map.icon]
        }
    };


     this.createImage = function (config) {
         var img = this.dataImage(config);
        if(img){
            return img;
        }
     };
     this.createIcon = function (config) {
        var ic = this.dataIcon(config);

        if(!ic && config.type){
            ic = mw.coreIcons[config.type];

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
     };

     this.removeTag = function (index) {
        var item = this.options.data[index];
        this.options.data.splice(index,1);
        this.refresh();
        $(scope).trigger('tagRemoved', [item]);
     };

     this.tag = function (options) {
            var config = {
                close:true,
                tagBtnClass:'mw-ui-btn mw-ui-btn-' + this.options.size
            };

            $.extend(config, options);

         if(this.options.color){
             config.tagBtnClass +=  ' mw-ui-btn-' + this.options.color;
         }

         if(this.options.outline){
             config.tagBtnClass +=  ' mw-ui-btn-outline';
         }

         if(this.options.rounded){
             config.tagBtnClass +=  ' mw-ui-btn-rounded';
         }


            var tag_holder = mwd.createElement('span');
            var tag_close = mwd.createElement('span');

            tag_close._index = config.index;
            tag_holder._index = config.index;
            tag_holder._config = config;
            tag_holder.dataset.index = config.index;

            tag_holder.className = config.tagBtnClass;

             if(options.image){

             }

            tag_holder.innerHTML = '<span class="tag-label-content">' + this.dataTitle(config) + '</span>';


            var icon = this.createIcon(config);

            var image = this.createImage(config);

             if(image){
                 $(tag_holder).prepend(image);
             }
             if(icon){
                 $(tag_holder).prepend(icon);
             }


            tag_holder.onclick = function (e) {
                if(e.target !== tag_close){
                    $(scope).trigger('tagClick', [this._config, this._index, this])
                }
            };

            tag_close.className = 'mw-icon-close';
            if(config.close){
                tag_close.onclick = function () {
                    scope.removeTag(this._index);
                };
            }
            tag_holder.appendChild(tag_close);
            return tag_holder;
        };

     this.init = function () {
         this.rend();
     };
    this.init();
};

mw.treeTags = mw.treeChips = function(options){
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