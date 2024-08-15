import MicroweberBaseClass from "../containers/base-class.js";

export class LinkPicker extends MicroweberBaseClass {
    constructor() {
        super();
    }

    selectLink(targetElementSelector) {


        var linkEditor = new (mw.top()).LinkEditor({
            mode: 'dialog',
            controllers: [
                {type: 'url', config: {text: false, target: true}},
                {type: 'page', config: {text: false, target: true}},
                {type: 'post', config: {text: false, target: true}},
                {type: 'file', config: {text: false, target: true}},
                {type: 'email', config: {text: false, target: true}},
                {type: 'layout', config: {text: false, target: false}},

            ],
        });

        if (mw.$(targetElementSelector)) {
            var elementToGetValueFrom = mw.$(targetElementSelector);
            var linkValue = '';
            var target = false;
            if (elementToGetValueFrom && elementToGetValueFrom[0] && elementToGetValueFrom[0].nodeName === 'A') {
                linkValue = elementToGetValueFrom.attr('href');

                if (elementToGetValueFrom.attr('target')) {
                    target = elementToGetValueFrom.attr('target');
                }

            } else {
                linkValue = elementToGetValueFrom.val();
            }
            if (linkValue == '#') {
                linkValue = '';
            }

            linkEditor.setValue({
                url: linkValue,
                target: target
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
            result.openInNewWindow = false;
            if(ldata.target){
                result.openInNewWindow = ldata.target;
            }

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
