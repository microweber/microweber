
/*************************************************************
 *
        MWEditor.addController(
            'underline',
            function () {

            }, function () {

            }
        );

        MWEditor.addController({
            name: 'underline',
            render: function () {

            },
            checkSelection: function () {

            }
        })

 **************************************************************/


MWEditor.addController = function (name, render, checkSelection, dependencies) {
    if (MWEditor.controllers[name]) {
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
    if(MWEditor.controllers[name]) {
        console.warn(name + ' controller is already registered in the editor')
        return;
    }
    MWEditor.controllers[name] = function () {
        this.render = render;
        if(checkSelection) {
            this.checkSelection = checkSelection;
        }
        this.element = this.render();
        this.dependencies = dependencies;
    };
};


MWEditor.addInteractionController = function (name, render, interact, dependencies) {
    if (MWEditor.controllers[name]) {
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
    if(MWEditor.interactionControls[name]) {
        console.warn(name + ' controller is already registered in the editor')
        return;
    }
    MWEditor.interactionControls[name] = function () {
        this.render = render;
        if(interact) {
            this.interact = interact;
        }
        this.element = this.render();
        this.dependencies = dependencies;
    };
};
