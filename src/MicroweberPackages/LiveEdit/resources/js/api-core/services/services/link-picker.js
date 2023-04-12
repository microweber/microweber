import MicroweberBaseClass from "../containers/base-class.js";

export class LinkPicker extends MicroweberBaseClass {
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

        linkEditor.promise().then(function (ldata) {
            if (!ldata) {
                return
            }


            var url = ldata.url;
            var url_display = ldata.url;
            var btn_category_id;
            var btn_content_id;


            if (ldata.data) {
                if (ldata.data.id) {
                    if ((ldata.data.type) && ldata.data.type === 'category') {
                        //url = 'category:' + ldata.data.id;
                        btn_category_id = ldata.data.id;
                    } else if ((ldata.data.type) && ldata.data.type === 'page') {
                        //url = 'content:' + ldata.data.id;
                        btn_content_id = ldata.data.id;
                    } else if (ldata.data.content_type) {
                        //url = 'content:' + ldata.data.id;
                        btn_content_id = ldata.data.id;
                    }
                }

            }
            if (!url_display) {
                url_display = url;
            }

            alert(url_display + ' ' + url + ' ' + btn_category_id + ' ' + btn_content_id);
        })


    }

}
