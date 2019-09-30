mw.liveedit.data = {
    _data:{},
    init: function() {
        mw.$(document.body)
        .on('touchmove mousemove', function(e){
            var hasLayout = !!mw.tools.firstMatchesOnNodeOrParent(e.target, ['[data-module-name="layouts"]', '[data-type="layouts"]']);
            mw.liveedit.data.set('move', 'hasLayout', hasLayout);
            mw.liveedit.data.set('move', 'hasModuleOrElement', mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['module', 'element']));
        })
        .on('mouseup touchend', function(e){
            mw.liveedit.data.set('mouseup', 'isIcon', mw.wysiwyg.firstElementThatHasFontIconClass(e.target));
        });


    },
    set: function(action, item, value){
        this._data[action] = this._data[action] || {};
        this._data[action][item] = value;
    },
    get: function(action, item){
        return this._data[action] ? this._data[action][item] : undefined;
    }
};
