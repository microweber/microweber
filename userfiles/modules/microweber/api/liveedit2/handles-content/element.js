import {HandleMenu} from "../handle-menu";

export const ElementHandleContent = function () {
    this.root = mw.element({
        props: {
            id: 'mw-handle-item-element-root'
        }
    });
    this.menu = new HandleMenu({
        id: 'mw-handle-item-element',
        className: 'mw-handle-type-default',
        buttons: [
            {
                title: mw.lang('Insert'),
                icon: 'mdi-plus-circle',
                className: 'mw-handle-insert-button',

                action: function (el) {
                }
            }
        ],
    });

    this.menu.show()

    this.root.append(this.menu.wrapper)

}
