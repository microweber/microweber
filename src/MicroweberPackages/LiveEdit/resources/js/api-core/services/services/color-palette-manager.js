import MicroweberBaseClass from "../containers/base-class.js";
import ColorConverter from "./color-converter.js";


export default class ColorPaletteManager extends MicroweberBaseClass {

    colorConverter = null;
    palette = [];

    constructor() {
        super();
        this.colorConverter = new ColorConverter();

    }

    getColors() {
        var colors = [];
        var colorsFromDocument = this.getColorsFromCanvasDocument();
        var colorsFromPallete = this.getColorsFromCanvasDocument();

        if (colorsFromPallete.length > 0) {
            for (var i = 0; i < colorsFromPallete.length; i++) {
                if (colors.indexOf(colorsFromPallete[i]) === -1) {
                    colors.push(colorsFromPallete[i]);
                }
            }
        }
        if(colorsFromDocument.length > 0) {
            for (var i = 0; i < colorsFromDocument.length; i++) {
                if (colors.indexOf(colorsFromDocument[i]) === -1) {
                    colors.push(colorsFromDocument[i]);
                }
            }
        }

        //make unique
        var unique = colors.filter((v, i, a) => a.indexOf(v) === i);

        return unique;
    }

    addToPalette(color) {
        if (this.palette.indexOf(color) === -1) {
            this.palette.push(color);
        }
    }

    getColorsFromPalette() {
        var colors = [];
        if (this.palette.length > 0) {
            for (var i = 0; i < this.palette.length; i++) {
                if (colors.indexOf(this.palette[i]) === -1) {
                    colors.push(this.palette[i]);
                }
            }
        }
        return colors;
    }

    getColorsFromCanvasDocument() {
        var colorsDocgetWindow = mw.top().app.canvas.getWindow();
        var colorpicker_els = colorsDocgetWindow.$(".btn,h1,h2,h3,h4,h5,p,font");
        var colors = [];

        if (colorpicker_els.length > 0) {
            colorpicker_els.each(function () {

                var converter = new ColorConverter();
                var css = colorsDocgetWindow.getComputedStyle(this, null);
                if (css !== null) {
                    if (colors.indexOf(css.color) === -1) {
                        colors.push(converter.rgbToHex(css.color));
                    }
                    if (colors.indexOf(css.backgroundColor) === -1) {
                        colors.push(converter.rgbToHex(css.backgroundColor));
                    }
                }
            });
        }


        return colors;
    }

}
