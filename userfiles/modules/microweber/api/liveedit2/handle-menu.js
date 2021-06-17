import {DomService} from "./dom";

export const HandleMenu = function(options) {

    this.options = options || {};

    var scope = this;

    this._visible = true;
    this.visible = function () {
        return this._visible;
    };
    this.show = function (){
        this._visible = true;
    }

    this.create = function(){
        this.root = mw.element({
            props: {
                className: 'mw-le-handle-menu'
            }
        })
    }


    this.hide();
}
