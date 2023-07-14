@php
    $md5name = md5($input->getAttribute('name'));
@endphp

<input style="display:none;" {!! $renderAttributes !!} id="{{$md5name}}" />

<input type="button" style="
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background:#ffffff;
  font-size:0px;
  cursor: pointer;
  border: 1px solid #cacaca;"
  id="open-color-picker-{{$md5name}}"
 />

<script>

    function getElementPositionInFrames(element) {
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

    function getElementPositionOnScreen(element) {
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

    $(document).ready(function () {
        let element = document.getElementById('open-color-picker-{{$md5name}}');
        element.addEventListener('click', function () {

            let colorPicker = mw.app.colorPicker;

            var position = {};
            if (self !== top) {
                position = getElementPositionInFrames(element);
            } else {
                position = getElementPositionOnScreen(element);
            }

            let newPositionX = position.x + 40;
            let newPositionY = position.y;

            if ((window.top.innerWidth - newPositionX) < 200) {
                newPositionX = newPositionX - 250 - (window.top.innerWidth - newPositionX);
            }
            if ((window.top.innerHeight - newPositionY) < 400) {
                newPositionY = newPositionY - 400 + (window.top.innerHeight - newPositionY);
            }

            colorPicker.setPosition(newPositionX, newPositionY);
            colorPicker.selectColor('#{{$md5name}}', function(color) {
                element.style.backgroundColor = color;
            });

        });
    });
</script>
