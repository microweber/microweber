/**
* @class PrettyJSON.view.Node
* @extends Backbone.View
* 
* @author #rbarriga
* @version 0.1
*
*/
PrettyJSON.view.Node = Backbone.View.extend({
    tagName:'span',
    data:null,
    level:1,
    path:'',
    type:'',
    size:0,
    isLast:true,
    rendered:false,
    events:{
        'click .node-bracket': 'collapse',
        'mouseover .node-container': 'mouseover',
        'mouseout .node-container': 'mouseout'
    },
    initialize:function(){

        this.data = this.options.data;
        this.level = this.options.level || this.level;
        this.path = this.options.path;
        this.isLast = _.isUndefined(this.options.isLast) ?
            this.isLast : this.options.isLast;

        var m = this.getMeta();
        this.type = m.type;
        this.size = m.size;

        //new instance.
        this.childs = [];
        this.render();

        //Render first level.
        if (this.level == 1)
            this.show();

    },
    getMeta: function(){
        var val = {
            size: _.size(this.data),
            type: _.isArray(this.data) ? 'array' : 'object',
        };
        return val;
    },
    elements:function(){
        this.els = {
            container:$(this.el).find('.node-container'),
            contentWrapper: $(this.el).find('.node-content-wrapper'),
            top:$(this.el).find('.node-top'),
            ul: $(this.el).find('.node-body'),
            down:$(this.el).find('.node-down')
        };
    },
    render:function(){
        this.tpl = _.template(PrettyJSON.tpl.Node);
        $(this.el).html(this.tpl);
        this.elements();

        var b = this.getBrackets();
        this.els.top.html(b.top);
        this.els.down.html(b.bottom);

        this.hide();

        return this;
    },
    renderChilds:function(){
        var count = 1;
        _.each(this.data, function(val, key){

            var isLast = (count == this.size);
            count = count + 1;

            var path = (this.type == 'array') ? 
                this.path + '[' + key + ']' :
                this.path + '.' + key;

            var opt = {
                key: key,
                data: val,
                parent: this,
                path: path,
                level: this.level + 1,
                isLast: isLast
            };

            var child = (PrettyJSON.util.isObject(val) || _.isArray(val) ) ? 
                new PrettyJSON.view.Node(opt) : 
                new PrettyJSON.view.Leaf(opt);

            child.on('mouseover',function(e,path){
                this.trigger("mouseover",e, path);
            }, this);
            child.on('mouseout',function(e){
                this.trigger("mouseout",e);
            }, this);

            //body ul 
            var li = $('<li/>');

            var colom = '&nbsp;:&nbsp;';
            var left = $('<span />');
            var right =  $('<span />').append(child.el);
            (this.type == 'array') ? left.html('') : left.html(key + colom);

            left.append(right);
            li.append(left);

            this.els.ul.append(li);

            //references.
            child.parentTd = left;
            child.parent = this;
            this.childs.push(child);

        }, this);
    // eof iteration
    },
    isVisible:function(){
        return this.els.contentWrapper.is(":visible");
    },
    collapse:function(e){
        e.stopPropagation();
        this.isVisible() ? this.hide() : this.show();
        this.trigger("collapse",e);
    },
    show: function(){

        //lazy render ..
        if(!this.rendered){
            this.renderChilds();
            this.rendered = true;
        }

        this.els.top.html(this.getBrackets().top);
        this.els.contentWrapper.show();
        this.els.down.show();
    },
    hide: function(){
        var b = this.getBrackets();

        this.els.top.html(b.close);
        this.els.contentWrapper.hide();
        this.els.down.hide();
    },
    getBrackets:function(){
        var v = {
            top:'{',
            bottom:'}',
            close:'{ ... }'
        };
        if(this.type == 'array'){
            v = {
                top:'[',
                bottom:']',
                close:'[ ... ]'
            };
        };

        v.bottom = (this.isLast) ? v.bottom : v.bottom + ',';
        v.close = (this.isLast) ? v.close : v.close + ',';

        return v;
    },
    mouseover:function(e){
        e.stopPropagation();
        this.togglePath(true);
        this.trigger("mouseover",e, this.path);
    },
    mouseout:function(e){
        e.stopPropagation();
        this.togglePath(false);
        this.trigger("mouseout",e);
    },
    expandAll:function (){
        _.each(this.childs, function(child){
            if(child instanceof PrettyJSON.view.Node){
                child.show();
                child.expandAll();
            }
        },this);
        this.show();
    },
    collapseAll:function(){
        _.each(this.childs, function(child){
            if(child instanceof PrettyJSON.view.Node){
                child.hide();
                child.collapseAll();
            }
        },this);

        if(this.level != 1)
            this.hide();
    },
    togglePath:function(show){
        this.getPathEls();
        _.each(this.tds,function(td){
            show ?
                td.addClass('node-hgl-path'):
                td.removeClass('node-hgl-path');
        },this);
    },
    getPathEls:function(){
        this.tds = [];
        var view = this;
        while (view){
            var td = view.parentTd;
            if(td) this.tds.push(td);
            view = view.parent;
        }
    }
});