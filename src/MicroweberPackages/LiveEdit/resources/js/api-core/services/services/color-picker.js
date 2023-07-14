import MicroweberBaseClass from "../containers/base-class.js";

export class ColorPicker extends MicroweberBaseClass {

    constructor() {
        super();
        this.colorPickerInstances = [];
        this.position = {};
    }

    setPosition(x, y) {
        this.position = {
            x: x,
            y: y
        };
    }

    positionToElement(targetElementSelector) {
        var element = $(targetElementSelector)[0];

        var position = {};
        if (self !== top) {
            position = this.getElementPositionInFrames(element);
        } else {
            position = this.getElementPositionOnScreen(element);
        }

        let newPositionX = position.x + 40;
        let newPositionY = position.y;

        if ((window.top.innerWidth - newPositionX) < 200) {
            newPositionX = newPositionX - 250 - (window.top.innerWidth - newPositionX);
        }
        if ((window.top.innerHeight - newPositionY) < 400) {
            newPositionY = newPositionY - 400 + (window.top.innerHeight - newPositionY);
        }

        this.setPosition(newPositionX, newPositionY); 
    }

    selectColor(targetElementSelector, callback = false) {

        if (this.colorPickerInstances.length > 0) {
            for (let i = 0; i < this.colorPickerInstances.length; i++) {
                this.colorPickerInstances[i].remove();
            }
        }

        var target = $(targetElementSelector)[0];
        let randId = this.generateRandId(10);

        let colorPickerDialog = mw.top().dialog({
            content: '<div id="color-picker-'+randId+'" style="width:232px;height:325px;"></div>',
            title: 'Color Picker',
            footer: false,
            width: 230,
            overlayClose: true,
            position: this.position
        });

        if (colorPickerDialog.dialogContainer) {
            colorPickerDialog.dialogContainer.style.padding = '0px';
        }
        if (colorPickerDialog.overlay) {
            colorPickerDialog.overlay.style.backgroundColor = 'transparent';
        }

        this.colorPickerInstances.push(colorPickerDialog);

        mw.top().colorPicker({
            element: '#color-picker-' + randId,
            onchange: function (color) {

                target.value = color;
                target.dispatchEvent(new Event('input'));

                if (callback) {
                    callback(color);
                }
            }
        });
        colorPickerDialog.center();
    }

    generateRandId(length) {
        let result = '';
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        const charactersLength = characters.length;
        let counter = 0;
        while (counter < length) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
            counter += 1;
        }
        return result;
    }

    getElementPositionInFrames(element) {
        var x = 0;
        var y = 0;
        var currentWindow = window;

        while (currentWindow !== top) {
            var iframe = currentWindow.frameElement;
            var iframeRect = iframe.getBoundingClientRect();
            var elementRect = element.getBoundingClientRect();

            x += iframeRect.left + elementRect.left;
            y += iframeRect.top + elementRect.top;

            currentWindow = currentWindow.parent;
        }

        return { x: x, y: y };
    }

    getElementPositionOnScreen(element) {
        var rect = element.getBoundingClientRect();

        var scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        var x = rect.left + scrollLeft;
        var y = rect.top + scrollTop;

        // Adjust position to stay within the visible screen area
        var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
        var screenHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

        x = Math.min(Math.max(x, 0), screenWidth - element.offsetWidth);
        y = Math.min(Math.max(y, 0), screenHeight - element.offsetHeight);

        return { x: x, y: y };
    }
}
