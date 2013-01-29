/**
* @class PrettyJSON.view.Leaf
* @extends Backbone.View
* 
* @author #rbarriga
* @version 0.1
*
*/
PrettyJSON.view.Leaf = Backbone.View.extend({
    tagName:'span',
    data:null,
    level:0,
    path:'',
    type:'string',
    isLast: true,
    events: {
        "mouseover .leaf-container": "mouseover",
        "mouseout .leaf-container": "mouseout"
    },
    initialize: function(){
        this.data = this.options.data;
        this.level = this.options.level;
        this.path = this.options.path;
        this.type = this.getType();
        this.isLast = _.isUndefined(this.options.isLast) ? 
            this.isLast : this.options.isLast;

        this.render();
    },
    getType: function(){
        var m = 'string';
        var d = this.data; 

        if(_.isNumber(d)) m = 'number';
        else if(_.isBoolean(d)) m = 'boolean';
        else if(_.isDate(d)) m = 'date';
        return m;
    },
    getState:function(){
        var coma = this.isLast ? '': ',';
        var state = {
            data: this.data,
            level: this.level,
            path: this.path,
            type: this.type,
            coma: coma
        };
        return state;
    },
    render: function(){
        var state = this.getState();
        this.tpl = _.template(PrettyJSON.tpl.Leaf, state);
        $(this.el).html(this.tpl);
        return this;
    },
    mouseover:function(e){
        e.stopPropagation();
        this.toggleTdPath(true);
        var path = this.path + '&nbsp;:&nbsp;<span class="' + this.type +'"><b>' + this.data + '</b></span>';
        this.trigger("mouseover",e, path);
    },
    mouseout:function(e){
       e.stopPropagation();
       this.toggleTdPath(false);
       this.trigger("mouseout",e);
    },
    getTds:function(){
        this.tds = [];
        var view = this;
        while (view){
            var td = view.parentTd;
            if(td) 
                this.tds.push(td);
            view = view.parent;
        }
    },
    toggleTdPath:function(show){
        this.getTds();
        _.each(this.tds,function(td){
            show ?
                td.addClass('node-hgl-path'):
                td.removeClass('node-hgl-path');
        },this);
    }
});