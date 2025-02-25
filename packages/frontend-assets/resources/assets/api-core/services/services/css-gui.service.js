
let _cssGUIVisible = false;


let _show = state => {
    const action = state ? 'add' : 'remove';
    mw.top().app.guiEditorBox[state ? 'show' : 'hide']();


}

const CSSGUIService = {
    show: () => {
        _cssGUIVisible = true;
        _show(_cssGUIVisible)
    },
    hide: () => {
        _cssGUIVisible = false;
        _show(_cssGUIVisible)
    },
    toggle: () => {
        _cssGUIVisible = !_cssGUIVisible;
        _show(_cssGUIVisible)
    },
    isVisible: () => {
        return _cssGUIVisible;
    }
}

addEventListener('load', function(){
    addEventListener('keydown', function(e){
        if(e.key === "Escape") {
            CSSGUIService.hide()
        }
    });

    mw.app.canvas.on('canvasDocumentKeydown',function(e){

        if(e.key === "Escape") {
            CSSGUIService.hide()
        }

    });
})


export default CSSGUIService;
