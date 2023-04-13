import MicroweberBaseClass from "../containers/base-class.js";

export class LinkPicker extends MicroweberBaseClass {
    constructor() {
        super();
    }

    selectLink(targetElementSelector) {


        var linkEditor = new (mw.top()).LinkEditor({
            mode: 'dialog',
            controllers: [
                {type: 'url', config: {text: false, target: false}},
                {type: 'page', config: {text: false, target: false}},
                {type: 'post', config: {text: false, target: false}},
                {type: 'file', config: {text: false, target: false}},
                {type: 'email', config: {text: false, target: false}},
                {type: 'layout', config: {text: false, target: false}},

            ],
        });

        if (mw.$(targetElementSelector)) {
            linkEditor.setValue({
                url: mw.$(targetElementSelector).val() || ''
            })
        }
        var selectLinkInstance = this;
        linkEditor.promise().then(function (ldata) {
            if (!ldata) {
                return;
            }
            var result = {};

            var url = ldata.url;


            result.url = url;

            if (ldata.data) {
                if (ldata.data.id) {
                    result.id = ldata.data.id;
                    if ((ldata.data.type) && ldata.data.type === 'category') {

                        result.type = 'category';
                    } else if ((ldata.data.type) && ldata.data.type === 'page') {

                        result.type = 'content';
                    } else if (ldata.data.content_type) {
                        result.type = 'content';

                    }
                }

            }


            selectLinkInstance.dispatch('selected', result);

        })


    }

}
