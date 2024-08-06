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
        var colorsFromMemory = this.getPaletteFromMemory();

        if (colorsFromPallete.length > 0) {
            for (var i = 0; i < colorsFromPallete.length; i++) {
                if (colors.indexOf(colorsFromPallete[i]) === -1) {
                    colors.push(colorsFromPallete[i]);
                }
            }
        }
        if (colorsFromDocument.length > 0) {
            for (var i = 0; i < colorsFromDocument.length; i++) {
                if (colors.indexOf(colorsFromDocument[i]) === -1) {
                    colors.push(colorsFromDocument[i]);
                }
            }
        }

        if (colorsFromMemory.length > 0) {
            for (var i = 0; i < colorsFromMemory.length; i++) {
                if (colors.indexOf(colorsFromMemory[i]) === -1) {
                    colors.push(colorsFromMemory[i]);
                }
            }
        }

        //make unique
        var unique = colors.filter((v, i, a) => a.indexOf(v) === i);

        return unique;
    }

    addToPalette(color) {
        var converter = new ColorConverter();
        color = converter.rgbToHex(color);
        if (this.palette.indexOf(color) === -1) {
            this.palette.push(color);
        }
    }

    getPaletteFromMemory() {
        var colors = [];
        var memory = mw.storage.get('colorPalette');
        if (memory) {
            colors = memory;
        }
        return colors;
    }

    addToPaletteMemory(color) {
        var converter = new ColorConverter();
        color = converter.rgbToHex(color);

        var memory = this.getPaletteFromMemory();
        if (!memory) {
            memory = [];
        }
        if (memory.indexOf(color) === -1) {
            memory.push(color);
        }
        mw.storage.set('colorPalette', memory);

        if (this.palette.indexOf(color) === -1) {
            this.palette.push(color);
        }
    }
    isInPaletteMemory(color) {
        var converter = new ColorConverter();
        color = converter.rgbToHex(color);
        var memory = this.getPaletteFromMemory();
        if (memory.indexOf(color) !== -1) {
            return true;
        }
    }
    removeFromPaletteMemory(color) {
        var converter = new ColorConverter();
        color = converter.rgbToHex(color);

        var memory = this.getPaletteFromMemory();
        var index = memory.indexOf(color);
        if (index !== -1) {
            memory.splice(index, 1);
        }
        mw.storage.set('colorPalette', memory);

        var index = this.palette.indexOf(color);
        if (index !== -1) {
            this.palette.splice(index, 1);
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
        if(!colorsDocgetWindow){
            return [];
        }
        if (typeof colorsDocgetWindow.$ === 'undefined') {
            return [];
        }

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
