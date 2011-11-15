/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.debug = {};

(function(){

var cp;

function createConsole(){

    var scriptPanel = new Ext.debug.ScriptsPanel();
    var logView = new Ext.debug.LogPanel();
    var tree = new Ext.debug.DomTree();

    var tabs = new Ext.TabPanel({
        activeTab: 0,
        border: false,
        tabPosition: 'bottom',
        items: [{
            title: 'Debug Console',
            layout:'border',
            items: [logView, scriptPanel]
        },{
            title: 'DOM Inspector',
            layout:'border',
            items: [tree]
        }]
    });

    cp = new Ext.Panel({
        id: 'x-debug-browser',
        title: 'Console',
        collapsible: true,
        animCollapse: false,
        style: 'position:absolute;left:0;bottom:0;',
        height:200,
        logView: logView,
        layout: 'fit',
        
        tools:[{
            id: 'close',
            handler: function(){
                cp.destroy();
                cp = null;
                Ext.EventManager.removeResizeListener(handleResize);
            }
        }],

        items: tabs
    });

    cp.render(document.body);

    cp.resizer = new Ext.Resizable(cp.el, {
        minHeight:50,
        handles: "n",
        pinned: true,
        transparent:true,
        resizeElement : function(){
            var box = this.proxy.getBox();
            this.proxy.hide();
            cp.setHeight(box.height);
            return box;
        }
    });

    function handleResize(){
        cp.setWidth(Ext.getBody().getViewSize().width);
    }
    Ext.EventManager.onWindowResize(handleResize);

    handleResize();
}


Ext.apply(Ext, {
    log : function(){
        if(!cp){
            createConsole();
        }
        cp.logView.log.apply(cp.logView, arguments);
    },

    logf : function(format, arg1, arg2, etc){
        Ext.log(String.format.apply(String, arguments));
    },

    dump : function(o){
        if(typeof o == 'string' || typeof o == 'number' || typeof o == 'undefined' || Ext.isDate(o)){
            Ext.log(o);
        }else if(!o){
            Ext.log("null");
        }else if(typeof o != "object"){
            Ext.log('Unknown return type');
        }else if(Ext.isArray(o)){
            Ext.log('['+o.join(',')+']');
        }else{
            var b = ["{\n"];
            for(var key in o){
                var to = typeof o[key];
                if(to != "function" && to != "object"){
                    b.push(String.format("  {0}: {1},\n", key, o[key]));
                }
            }
            var s = b.join("");
            if(s.length > 3){
                s = s.substr(0, s.length-2);
            }
            Ext.log(s + "\n}");
        }
    },

    _timers : {},

    time : function(name){
        name = name || "def";
        Ext._timers[name] = new Date().getTime();
    },

    timeEnd : function(name, printResults){
        var t = new Date().getTime();
        name = name || "def";
        var v = String.format("{0} ms", t-Ext._timers[name]);
        Ext._timers[name] = new Date().getTime();
        if(printResults !== false){
            Ext.log('Timer ' + (name == "def" ? v : name + ": " + v));
        }
        return v;
    }
});

})();


Ext.debug.ScriptsPanel = Ext.extend(Ext.Panel, {
    id:'x-debug-scripts',
    region: 'east',
    minWidth: 200,
    split: true,
    width: 350,
    border: false,
    layout:'anchor',
    style:'border-width:0 0 0 1px;',

    initComponent : function(){

        this.scriptField = new Ext.form.TextArea({
            anchor: '100% -26',
            style:'border-width:0;'
        });

        this.trapBox = new Ext.form.Checkbox({
            id: 'console-trap',
            boxLabel: 'Trap Errors',
            checked: true
        });

        this.toolbar = new Ext.Toolbar([{
                text: 'Run',
                scope: this,
                handler: this.evalScript
            },{
                text: 'Clear',
                scope: this,
                handler: this.clear
            },
            '->',
            this.trapBox,
            ' ', ' '
        ]);

        this.items = [this.toolbar, this.scriptField];

        Ext.debug.ScriptsPanel.superclass.initComponent.call(this);
    },

    evalScript : function(){
        var s = this.scriptField.getValue();
        if(this.trapBox.getValue()){
            try{
                var rt = eval(s);
                Ext.dump(rt === undefined? '(no return)' : rt);
            }catch(e){
                Ext.log(e.message || e.descript);
            }
        }else{
            var rt = eval(s);
            Ext.dump(rt === undefined? '(no return)' : rt);
        }
    },

    clear : function(){
        this.scriptField.setValue('');
        this.scriptField.focus();
    }

});

Ext.debug.LogPanel = Ext.extend(Ext.Panel, {
    autoScroll: true,
    region: 'center',
    border: false,
    style:'border-width:0 1px 0 0',

    log : function(){
        var markup = [  '<div style="padding:5px !important;border-bottom:1px solid #ccc;">',
                    Ext.util.Format.htmlEncode(Array.prototype.join.call(arguments, ', ')).replace(/\n/g, '<br />').replace(/\s/g, '&#160;'),
                    '</div>'].join('');

        this.body.insertHtml('beforeend', markup);
        this.body.scrollTo('top', 100000);
    },

    clear : function(){
        this.body.update('');
        this.body.dom.scrollTop = 0;
    }
});

Ext.debug.DomTree = Ext.extend(Ext.tree.TreePanel, {
    enableDD:false ,
    lines:false,
    rootVisible:false,
    animate:false,
    hlColor:'ffff9c',
    autoScroll: true,
    region:'center',
    border:false,

    initComponent : function(){


        Ext.debug.DomTree.superclass.initComponent.call(this);
        
        // tree related stuff
        var styles = false, hnode;
        var nonSpace = /^\s*$/;
        var html = Ext.util.Format.htmlEncode;
        var ellipsis = Ext.util.Format.ellipsis;
        var styleRe = /\s?([a-z\-]*)\:([^;]*)(?:[;\s\n\r]*)/gi;

        function findNode(n){
            if(!n || n.nodeType != 1 || n == document.body || n == document){
                return false;
            }
            var pn = [n], p = n;
            while((p = p.parentNode) && p.nodeType == 1 && p.tagName.toUpperCase() != 'HTML'){
                pn.unshift(p);
            }
            var cn = hnode;
            for(var i = 0, len = pn.length; i < len; i++){
                cn.expand();
                cn = cn.findChild('htmlNode', pn[i]);
                if(!cn){ // in this dialog?
                    return false;
                }
            }
            cn.select();
            var a = cn.ui.anchor;
            treeEl.dom.scrollTop = Math.max(0 ,a.offsetTop-10);
            //treeEl.dom.scrollLeft = Math.max(0 ,a.offsetLeft-10); no likey
            cn.highlight();
            return true;
        }

        function nodeTitle(n){
            var s = n.tagName;
            if(n.id){
                s += '#'+n.id;
            }else if(n.className){
                s += '.'+n.className;
            }
            return s;
        }

        function onNodeSelect(t, n, last){
            return;
            if(last && last.unframe){
                last.unframe();
            }
            var props = {};
            if(n && n.htmlNode){
                if(frameEl.pressed){
                    n.frame();
                }
                if(inspecting){
                    return;
                }
                addStyle.enable();
                reload.setDisabled(n.leaf);
                var dom = n.htmlNode;
                stylePanel.setTitle(nodeTitle(dom));
                if(styles && !showAll.pressed){
                    var s = dom.style ? dom.style.cssText : '';
                    if(s){
                        var m;
                        while ((m = styleRe.exec(s)) != null){
                            props[m[1].toLowerCase()] = m[2];
                        }
                    }
                }else if(styles){
                    var cl = Ext.debug.cssList;
                    var s = dom.style, fly = Ext.fly(dom);
                    if(s){
                        for(var i = 0, len = cl.length; i<len; i++){
                            var st = cl[i];
                            var v = s[st] || fly.getStyle(st);
                            if(v != undefined && v !== null && v !== ''){
                                props[st] = v;
                            }
                        }
                    }
                }else{
                    for(var a in dom){
                        var v = dom[a];
                        if((isNaN(a+10)) && v != undefined && v !== null && v !== '' && !(Ext.isGecko && a[0] == a[0].toUpperCase())){
                            props[a] = v;
                        }
                    }
                }
            }else{
                if(inspecting){
                    return;
                }
                addStyle.disable();
                reload.disabled();
            }
            stylesGrid.setSource(props);
            stylesGrid.treeNode = n;
            stylesGrid.view.fitColumns();
        }

        this.loader = new Ext.tree.TreeLoader();
        this.loader.load = function(n, cb){
            var isBody = n.htmlNode == document.body;
            var cn = n.htmlNode.childNodes;
            for(var i = 0, c; c = cn[i]; i++){
                if(isBody && c.id == 'x-debug-browser'){
                    continue;
                }
                if(c.nodeType == 1){
                    n.appendChild(new Ext.debug.HtmlNode(c));
                }else if(c.nodeType == 3 && !nonSpace.test(c.nodeValue)){
                    n.appendChild(new Ext.tree.TreeNode({
                        text:'<em>' + ellipsis(html(String(c.nodeValue)), 35) + '</em>',
                        cls: 'x-tree-noicon'
                    }));
                }
            }
            cb();
        };

        //tree.getSelectionModel().on('selectionchange', onNodeSelect, null, {buffer:250});

        this.root = this.setRootNode(new Ext.tree.TreeNode('Ext'));

        hnode = this.root.appendChild(new Ext.debug.HtmlNode(
                document.getElementsByTagName('html')[0]
        ));

    }
});


// highly unusual class declaration
Ext.debug.HtmlNode = function(){
    var html = Ext.util.Format.htmlEncode;
    var ellipsis = Ext.util.Format.ellipsis;
    var nonSpace = /^\s*$/;

    var attrs = [
        {n: 'id', v: 'id'},
        {n: 'className', v: 'class'},
        {n: 'name', v: 'name'},
        {n: 'type', v: 'type'},
        {n: 'src', v: 'src'},
        {n: 'href', v: 'href'}
    ];

    function hasChild(n){
        for(var i = 0, c; c = n.childNodes[i]; i++){
            if(c.nodeType == 1){
                return true;
            }
        }
        return false;
    }

    function renderNode(n, leaf){
        var tag = n.tagName.toLowerCase();
        var s = '&lt;' + tag;
        for(var i = 0, len = attrs.length; i < len; i++){
            var a = attrs[i];
            var v = n[a.n];
            if(v && !nonSpace.test(v)){
                s += ' ' + a.v + '=&quot;<i>' + html(v) +'</i>&quot;';
            }
        }
        var style = n.style ? n.style.cssText : '';
        if(style){
            s += ' style=&quot;<i>' + html(style.toLowerCase()) +'</i>&quot;';
        }
        if(leaf && n.childNodes.length > 0){
            s+='&gt;<em>' + ellipsis(html(String(n.innerHTML)), 35) + '</em>&lt;/'+tag+'&gt;';
        }else if(leaf){
            s += ' /&gt;';
        }else{
            s += '&gt;';
        }
        return s;
    }

    var HtmlNode = function(n){
        var leaf = !hasChild(n);
        this.htmlNode = n;
        this.tagName = n.tagName.toLowerCase();
        var attr = {
            text : renderNode(n, leaf),
            leaf : leaf,
            cls: 'x-tree-noicon'
        };
        HtmlNode.superclass.constructor.call(this, attr);
        this.attributes.htmlNode = n; // for searching
        if(!leaf){
            this.on('expand', this.onExpand,  this);
            this.on('collapse', this.onCollapse,  this);
        }
    };


    Ext.extend(HtmlNode, Ext.tree.AsyncTreeNode, {
        cls: 'x-tree-noicon',
        preventHScroll: true,
        refresh : function(highlight){
            var leaf = !hasChild(this.htmlNode);
            this.setText(renderNode(this.htmlNode, leaf));
            if(highlight){
                Ext.fly(this.ui.textNode).highlight();
            }
        },

        onExpand : function(){
            if(!this.closeNode && this.parentNode){
                this.closeNode = this.parentNode.insertBefore(new Ext.tree.TreeNode({
                    text:'&lt;/' + this.tagName + '&gt;',
                    cls: 'x-tree-noicon'
                }), this.nextSibling);
            }else if(this.closeNode){
                this.closeNode.ui.show();
            }
        },

        onCollapse : function(){
            if(this.closeNode){
                this.closeNode.ui.hide();
            }
        },

        render : function(bulkRender){
            HtmlNode.superclass.render.call(this, bulkRender);
        },

        highlightNode : function(){
            //Ext.fly(this.htmlNode).highlight();
        },

        highlight : function(){
            //Ext.fly(this.ui.textNode).highlight();
        },

        frame : function(){
            this.htmlNode.style.border = '1px solid #0000ff';
            //this.highlightNode();
        },

        unframe : function(){
            //Ext.fly(this.htmlNode).removeClass('x-debug-frame');
            this.htmlNode.style.border = '';
        }
    });

    return HtmlNode;
}();


