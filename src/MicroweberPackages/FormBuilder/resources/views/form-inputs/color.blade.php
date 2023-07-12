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
  border: 1px solid #cacaca;" />

onclick="mw.app.colorPicker.selectColor('#{{$md5name}}')" />

<!--

<script>

    $(document).ready(function () {

        let hiddenElement = document.getElementById('{{$md5name}}');
        let element = document.getElementById('open-color-picker-{{$md5name}}');

        setTimeout(function() {
            element.style.background = hiddenElement.value;
        }, 300);

        element.addEventListener('click', function () {
            let colorPickerDialog = mw.top().dialog({
                content: '<div id="color-picker-{{$md5name}}"></div>',
                title: 'Color Picker',
                footer: false,
                width: 230,
                overlayClose: true
            });
            colorPickerDialog.dialogContainer.style.padding = '0px';
            colorPickerDialog.overlay.style.backgroundColor = 'transparent';

            mw.top().colorPicker({
                element: '#color-picker-{{$md5name}}',
                onchange: function (color) {

                    element.style.background = color;

                    hiddenElement.value = color;
                    hiddenElement.dispatchEvent(new Event('input'));
                }
            });
        });

    });
</script>
-->
