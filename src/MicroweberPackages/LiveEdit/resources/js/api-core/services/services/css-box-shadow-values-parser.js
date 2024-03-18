export default class CssBoxShadowValuesParser {
    parseBoxShadowValues(shadowString) {
        // Split the shadow string by semicolons to get individual shadows
        const shadowStrings = shadowString.split(';');

        // Parse each individual shadow value
        const shadows = shadowStrings.map(this.parseSingleValue);

        return shadows;
    }

    parseSingleValue(shadowValue) {
        // Split the shadow value by commas to get individual shadows
        const shadowParts = shadowValue.split(',');

        // Initialize variables to store parsed values
        const shadows = [];

        // Loop through each individual shadow part
        for (const part of shadowParts) {
            // Split the shadow part by spaces to get individual components
            const parts = part.trim().split(/\s+/);

            // Initialize variables for this particular shadow
            let inset = false;
            let horizontalLength = '0px';
            let verticalLength = '0px';
            let blurRadius = '0px';
            let spreadRadius = '0px';
            let shadowColor = 'rgba(0, 0, 0, 0)'; // Default shadow color

            // Loop through each component of this shadow part
            for (const part of parts) {
                if (part === 'inset') {
                    inset = true;
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
                } else if (part.startsWith('rgb') || part.startsWith('hsl')) {
                    shadowColor = part;
                }
            }

            // Add this parsed shadow to the shadows array
            shadows.push({
                inset,
                horizontalLength,
                verticalLength,
                blurRadius,
                spreadRadius,
                shadowColor
            });
        }

        return shadows;
    }
}
