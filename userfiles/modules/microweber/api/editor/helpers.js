import {ElementManager} from  '../classes/element'

MWEditor.controllersHelpers = {
    '|' : function () {
        return ElementManager({
            tage: 'span',
            props: {
                className: 'mw-bar-delimiter'
            }
        });
    }
};
