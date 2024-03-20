import MicroweberBaseClass from "../containers/base-class.js";


class ColorConverter extends MicroweberBaseClass {
    constructor() {
        super();
    }

    rgbaToHex(orig) {
        var a, isPercent,
            rgb = orig.replace(/\s/g, '').match(/^rgba?\((\d+),(\d+),(\d+),?([^,\s)]+)?/i),
            alpha = (rgb && rgb[4] || "").trim(),
            hex = rgb ?
                (rgb[1] | 1 << 8).toString(16).slice(1) +
                (rgb[2] | 1 << 8).toString(16).slice(1) +
                (rgb[3] | 1 << 8).toString(16).slice(1) : orig;

        if (alpha !== '') {
            a = alpha;
        } else {
            a = '01';
        }
        a = ((a * 255) | 1 << 8).toString(16).slice(1)
        hex = hex + a;

        return '#' + hex;
    }

    rgbToHex(color) {
        if (typeof color == 'object') {
            color = color.r + ',' + color.g + ',' + color.b;
        }
        if (color.contains('rgb')) {
            color = color.replace(/rgba/g, '').replace(/rgb/g, '').replace(/\(|\)/g, "").replace(/\s/g, "");
        }
        color = color.split(',');
        if (color !== 'transparent') {
            return "#" + ((1 << 24) + (parseInt(color[0]) << 16) + (parseInt(color[1]) << 8) + parseInt(color[2])).toString(16).slice(1);
        } else {
            return 'transparent';
        }
    }
    hslToHex(h, s, l) {
        h /= 360;
        s /= 100;
        l /= 100;

        let r, g, b;

        if (s === 0) {
            r = g = b = l; // achromatic
        } else {
            const hue2rgb = (p, q, t) => {
                if (t < 0) t += 1;
                if (t > 1) t -= 1;
                if (t < 1 / 6) return p + (q - p) * 6 * t;
                if (t < 1 / 2) return q;
                if (t < 2 / 3) return p + (q - p) * (2 / 3 - t) * 6;
                return p;
            };
            const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
            const p = 2 * l - q;
            r = hue2rgb(p, q, h + 1 / 3);
            g = hue2rgb(p, q, h);
            b = hue2rgb(p, q, h - 1 / 3);
        }

        const toHex = (x) => {
            const hex = Math.round(x * 255).toString(16);
            return hex.length === 1 ? '0' + hex : hex;
        };

        return `#${toHex(r)}${toHex(g)}${toHex(b)}`;
    }

    rgbOrRgbaToHex(color) {
        if (!color) return '';

        if (color.indexOf('#') !== -1) {
            return color;
        }
        if (color.indexOf('rgb(') === 0) {
            return this.rgbToHex(color);
        } else {
            return this.rgbaToHex(color);
        }
    }

    hexToRgb(hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }

    colorParse(CSScolor) {
        CSScolor = CSScolor || 'rgb(0,0,0,0)';
        var final = { r: 0, g: 0, b: 0, alpha: 0 };
        if (CSScolor.contains('rgb')) {
            var parse = CSScolor.replace(/rgba/g, '').replace(/rgb/g, '').replace(/\(|\)/g, "").replace(/\s/g, "").split(',');
            final.r = parseInt(parse[0], 10);
            final.g = parseInt(parse[1], 10);
            final.b = parseInt(parse[2], 10);
            final.alpha = Number((parse[3] || 1));
            return final;
        } else {
            final = this.hexToRgb(CSScolor);
            final.alpha = 1
            return final;
        }
    }

    getBrightness(color) {
        var rgb = this.colorParse(color);
        return {
            color: (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000,
            alpha: rgb.alpha * 100
        };
    }

    isDark(color) {
        var brightness = this.getBrightness(color);
        return brightness.color < 128 && brightness.alpha > 22;
    }

    isLight(color) {
        return !this.isDark(color);
    }

    hexToRgbaCSS(hex, alpha) {
        alpha = alpha || 1;
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? ('rgba(' + parseInt(result[1], 16) + ',' + parseInt(result[2], 16) + ',' + parseInt(result[3], 16) + ',' + alpha + ')') : '';
    }

    random() {
        return '#' + Math.floor(Math.random() * 16777215).toString(16);
    }

    decimalToHex(decimal) {
        var hex = decimal.toString(16);
        if (hex.length == 1) hex = '0' + hex;
        return hex;
    }

    hexToDecimal(hex) {
        return parseInt(hex, 16);
    }

    oppositeColor(color) {
        color = !color.contains("#") ? color : color.replace("#", '');
        return this.decimalToHex(255 - this.hexToDecimal(color.substr(0, 2)))
            + this.decimalToHex(255 - this.hexToDecimal(color.substr(2, 2)))
            + this.decimalToHex(255 - this.hexToDecimal(color.substr(4, 2)));
    }
}

export default ColorConverter;
