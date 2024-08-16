import ColorConverter from "./color-converter.js";

export default class CssBoxShadowValuesParser {

    replaceRGBWithHex(shadowString) {
        const convert = new ColorConverter();
        const rgbRegex = /rgba?\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})(,\s*(1|0?\.\d+))?\)/g;
        const hslRegex = /hsla?\((\d{1,3}),\s*(\d{1,3}%),\s*(\d{1,3}%)(,\s*(1|0?\.\d+))?\)/g;

        let result = shadowString.replace(rgbRegex, (match) => convert.rgbOrRgbaToHex(match));
        result = result.replace(hslRegex, (match) => convert.hslToHex(match));

        return result;
    }

    parseBoxShadowValues(shadowString) {

        const hexShadowString = this.replaceRGBWithHex(shadowString);
        const shadowStrings = hexShadowString.split(',');

        const parsedShadows = [];

        for (const shadow of shadowStrings) {
            const parsedShadow = this.parseSingleValue(shadow.trim());

            parsedShadows.push(parsedShadow);
        }

        return parsedShadows;
    }

    parseSingleValue(shadowValue) {
        const shadowParts = shadowValue.split(/\s+(?![^(]*\))/);

        let inset = false;
        let horizontalLength = '0px';
        let verticalLength = '0px';
        let blurRadius = '0px';
        let spreadRadius = '0px';
        let shadowColor = '';


        const convert = new ColorConverter();

         for (const part of shadowParts) {
            if (part === 'inset') {
                inset = 'inset';
            } else if (part.endsWith('px') || part.endsWith('em') || part.endsWith('%')) {
                if (horizontalLength === '0px') {
                    horizontalLength = part;
                } else if (verticalLength === '0px') {
                    verticalLength = part;
                } else if (blurRadius === '0px') {
                    blurRadius = part;
                } else if (spreadRadius === '0px') {
                    spreadRadius = part;
                }
            } else if (part.startsWith('rgb(') || part.startsWith('rgba(')) {
                shadowColor = convert.rgbOrRgbaToHex(part) || '';
            } else if (part.startsWith('hsl(')) {
                shadowColor = convert.hslToHex(part) || '';
            } else if (part.startsWith('#')) {
                shadowColor = part;
            }
        }

         return {
            inset,
            horizontalLength,
            verticalLength,
            blurRadius,
            spreadRadius,
            shadowColor
        };
    }
}
