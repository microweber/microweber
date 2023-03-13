var initResizables = () => {
    Array.from(document.querySelectorAll('.mw-le-spacer:not([data-resizable])')).forEach(node => {
        node.innerHTML = '';
        node.className = 'mw-le-spacer noedit nodrop';

        var nodeInfo = document.createElement('span');
        node.append(nodeInfo);
        nodeInfo.className = 'mw-le-spacer-info';

        var nodeInfoContent = document.createElement('span');
        nodeInfo.append(nodeInfoContent);
        nodeInfoContent.className = 'mw-le-spacer-info-content';


        node._$resizer = new Resizable({
            element: node,
            document: node.ownerDocument,
            direction: 'vertical',
            maxHeight: 220
        });


        node._$resizer.on('resize', data => {
            nodeInfoContent.textContent = data.height + 'px';
            node.classList.add('mw-le-spacer-resizing');
            document.body.classList.add('isTyping');
        });

        ;(nodeInfoContent => {
            node._$resizer.on('ready', data => {

                nodeInfoContent.textContent = data.height + 'px';
            });
        })(nodeInfoContent);



        node._$resizer.on('resizeStop', data => {
            node.classList.remove('mw-le-spacer-resizing');
            mw.liveedit.cssEditor.temp(node, 'height', node.offsetHeight + 'px');
            mw.wysiwyg.change(node);
            mw.liveEditSelector.positionSelected();
            node.style.height = '';
            document.body.classList.remove('isTyping');
        });

        node._$resizer.mount()

    });
};

addEventListener('load', function () {
    initResizables();
    setTimeout(function(){
        initResizables();
    }, 2000);
});

$(window).on('moduleLoaded', function(){
    setTimeout(function(){

        initResizables();

    }, 333)
});


mw.liveedit.initReady = function() {
    mw.liveedit.data.init();
    mw.liveEditSelector = new mw.Selector({
        root: document.body,
        autoSelect: false
    });

    initResizables();

    mw.on.moduleReload(function () {
        initResizables();
    })

    mw.drag.create();

    mw.liveedit.editFields.handleKeydown();

    mw.dragSTOPCheck = false;

    var t = document.querySelectorAll('[field="title"]'),
        l = t.length,
        i = 0;

    for (; i < l; i++) {
        mw.$(t[i]).addClass("nodrop");
    }



    mw.wysiwyg.init_editables();
    mw.wysiwyg.prepare();
    mw.wysiwyg.init();
    mw.ea = mw.ea || new mw.ElementAnalyzer();
};
