
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


mw.Editor.addController = function (name, render, checkSelection) {
    if (mw.Editor.controllers[name]) {
        console.warn(name + ' already defined');
        return;
    }
    if (typeof name === 'object') {
        var obj = name;
        name = obj.name;
        render = obj.render;
        checkSelection = obj.checkSelection;
    }
    if(mw.Editor.controllers[name]) {
        console.warn(name + ' controller is already registered in the editor')
        return;
    }
    mw.Editor.controllers[name] = function () {
        this.render = render;
        this.checkSelection = checkSelection;
        this.element = this.render();
    };
};
