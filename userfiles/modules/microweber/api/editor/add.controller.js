
/*************************************************************
 *
        mw.Editor.addController(
            'underline',
            function () {

            }, function () {

            }
        );

        mw.Editor.addController({
            name: 'underline',
            render: function () {

            },
            checkSelection: function () {

            }
        })

 **************************************************************/


mw.Editor.addController = function (name, render, checkSelection, dependencies) {
    if (mw.Editor.controllers[name]) {
        console.warn(name + ' already defined');
        return;
    }
    if (typeof name === 'object') {
        var obj = name;
        name = obj.name;
        render = obj.render;
        checkSelection = obj.checkSelection;
        dependencies = obj.dependencies;
    }
    if(mw.Editor.controllers[name]) {
        console.warn(name + ' controller is already registered in the editor')
        return;
    }
    mw.Editor.controllers[name] = function () {
        this.render = render;
        if(checkSelection) {
            this.checkSelection = checkSelection;
        }
        this.element = this.render();
        this.dependencies = dependencies;
    };
};


mw.Editor.addInteractionController = function (name, render, interact, dependencies) {
    if (mw.Editor.controllers[name]) {
        console.warn(name + ' already defined');
        return;
    }
    if (typeof name === 'object') {
        var obj = name;
        name = obj.name;
        render = obj.render;
        interact = obj.interact;
        dependencies = obj.dependencies;
    }
    if(mw.Editor.interactionControls[name]) {
        console.warn(name + ' controller is already registered in the editor')
        return;
    }
    mw.Editor.interactionControls[name] = function () {
        this.render = render;
        if(interact) {
            this.interact = interact;
        }
        this.element = this.render();
        this.dependencies = dependencies;
    };
};
