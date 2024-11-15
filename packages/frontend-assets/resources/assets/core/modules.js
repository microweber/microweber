




mw.load_module = function(name, selector, callback, attributes) {
    attributes = attributes || {};
    attributes.module = name;
    return mw._({
        selector: selector,
        params: attributes,
        done: function() {
            mw.settings.sortables_created = false;
            if (typeof callback === 'function') {
                callback.call(this);
            }
        }
    });
};

mw.module = {
    inViewport(el) {
        if(!el || !el.parentNode) {
            return false;
        }

        const doc = el.ownerDocument;
        const win = doc.defaultView;


        const bounding = el.getBoundingClientRect();
        const elHeight = el.offsetHeight;
        const elWidth = el.offsetWidth;



        if (bounding.top >= -elHeight
            && bounding.left >= -elWidth
            && bounding.right <= (win.innerWidth || doc.documentElement.clientWidth) + elWidth
            && bounding.bottom <= (win.innerHeight || doc.documentElement.clientHeight) + elHeight) {

            return true;
        } else {

            return  false
         }
    },
    getData: function (module, options) {
        if(typeof module === 'object') {
            options = module;
            module = options.module;
        }
        options = options || {};
        options.module = module || options.module;
        return mw.module.xhr.post('/', options);
    },
    getAttributes: function (target) {
        var node = mw.element(target).get(0);
        if (!target) return;
        var attrs = node.attributes;
        var data = {};
        for (var i in attrs) {
            if(attrs.hasOwnProperty(i) && attrs[i] !== undefined){
                var name = attrs[i].name;
                var val = attrs[i].nodeValue;
                if(typeof data[name] === 'undefined'){
                    data[name]  = val;
                }
            }
        }
        return data;
    },
    insert: function(target, module, config, pos, stateManager, explicitAction) {




        return new Promise(function (resolve) {
            pos = pos || 'bottom';
            var action;
            var id = mw.id('mw-module-'),
                el = '<div id="' + id + '"></div>';

        if (pos === 'top') {
            action = 'before';
            if(mw.tools.hasAnyOfClasses(target, ['allow-drop', 'mw-col'])) {
                action = 'prepend';
            }
        } else if (pos === 'bottom') {
            action = 'after';
            if(mw.tools.hasAnyOfClasses(target, ['allow-drop', 'mw-col'])) {
                action = 'append';
            }
        }
        if(mw.tools.hasAnyOfClasses(target, [ 'mw-col'])) {
             if(target.firstElementChild.classList.contains('mw-col-container')) {
                target = target.firstElementChild;
                if(target.firstElementChild.classList.contains('mw-empty-element')) {
                    target = target.firstElementChild
                 }
             }
        }
        //if element is image and has a link
        if( target.nodeName  == 'IMG' && target.parentNode && target.parentNode.nodeName === 'A'){
            target = target.parentNode;
         }

        if(target.classList && target.classList.contains('edit')) {
            if(pos === 'top' || action === 'before') {
                action = 'prepend'
            } else if(pos === 'bottom' || action === 'after') {
                action = 'append'
            }
        }


        var parent = mw.$(target).parent().get(0);
        if(!parent){
            parent = mw.tools.firstParentOrCurrentWithAnyOfClasses(target, ['edit', 'module']);
        }
        if(!parent){
            parent = target;
        }

        if(stateManager) {
            stateManager.record({
                target: parent,
                value: parent.innerHTML
            });
        }

        if(pos === 'append') {
            action = 'append'
        }
        if(pos === 'prepend') {
            action = 'prepend'
        }


        if(target.classList.contains('mw-free-layout-container')) {
            action = 'append'
        }



        if(target.nodeName === 'TD' && !explicitAction) {
            if(action === 'before') {
                action = 'prepend'
            } else if(action === 'after') {
                action = 'append'
            }
        }


        mw.$(target)[explicitAction || action](el);
        mw.load_module(module, '#' + id, function (a,b) {
            if(stateManager) {
                stateManager.record({
                    target: parent,
                    value: parent.innerHTML
                });
            }

            if(!mw.module.inViewport(document.querySelector('#' + id))) {
                mw.tools.scrollTo('#' + id);
            }





            resolve(this);
        }, config);
    });
    },
    insert_old: function(target, module, config, pos) {


        console.log(7772, target, module, config, pos)

        return new Promise(function (resolve) {
            pos = pos || 'bottom';
            var action;
            var id = mw.id('mw-module-'),
                el = '<div id="' + id + '"></div>';

            if (pos === 'top') {
                action = 'before';
                if (mw.tools.hasClass(target, 'allow-drop')) {
                    action = 'prepend';
                }
            } else if (pos === 'bottom') {
                action = 'after';
                if (mw.tools.hasClass(target, 'allow-drop')) {
                    action = 'append';
                }
            }
            mw.element(target)[action](el);
            mw.load_module(module, '#' + id, function () {
                resolve(this);

                console.log(this)

                mw.tools.scrollTo('#' + id)
            }, config);
        });
    }
};
