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

    $(document).ready(function () {
        let element = document.getElementById('open-color-picker-{{$md5name}}');
        element.addEventListener('click', function () {

            let colorPicker = mw.app.colorPicker;

            var position = getElementPositionInFrames(element);

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
