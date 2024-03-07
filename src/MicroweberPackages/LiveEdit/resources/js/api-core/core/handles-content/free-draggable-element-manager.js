import MicroweberBaseClass from "../../services/containers/base-class";


export class FreeDraggableElementManager extends MicroweberBaseClass {

    proto = null;

    constructor(proto) {
        super();
        this.proto = proto;
    }


    makeFreeDraggableElement(element) {
        //$(element).draggable();
        // make on percent
        $(element).draggable(
            {
              //  containment: "parent",
                scroll: false,
                start: function (event, ui) {

                },
                drag: function (event, ui) {
                    mw.app.dispatch('liveEditRefreshHandlesPosition');

                },
                stop: function (event, ui) {

                    mw.top().app.dispatch('mw.elementStyleEditor.applyCssPropertyToNode', {
                        node: this[0],
                        prop: 'top',
                        val: $(this).position().top
                    });

                    mw.top().app.dispatch('mw.elementStyleEditor.applyCssPropertyToNode', {
                        node: this[0],
                        prop: 'left',
                        val: $(this).position().left
                    });

                    mw.app.dispatch('liveEditRefreshHandlesPosition');

                    // $(this).css('z-index', 1000);
                   // $(this).css('position', 'absolute');
                 //   $(this).css('width', $(this).outerWidth());
                  //  $(this).css('height', $(this).outerHeight());
                 //   $(this).css('top', $(this).position().top);
               //     $(this).css('left', $(this).position().left);
                }
            }
        );


   //     $(element).draggable().css("position", "absolute");
    }

    destroyFreeDraggableElement(element) {
        $(element).draggable('destroy');

    }

}
