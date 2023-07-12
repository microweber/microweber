@php
    $md5name = md5($input->getAttribute('name'));
@endphp


<input type="text" id="{{$md5name}}" />

<div style="
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background: #fff;
    cursor: pointer;
  border: 1px solid #cacaca;" id="open-color-picker-{{$md5name}}">
</div>

<script>
    $(document).ready(function () {
        let colorPickerInstance = false; 
        document.getElementById('open-color-picker-{{$md5name}}').addEventListener("click", function() {
            colorPickerInstance = mw.colorPicker({
                element: '#open-color-picker-{{$md5name}}',
                position: 'bottom-left',
                onchange: function (color) {
                    var element = document.getElementById('{{$md5name}}');
                    element.value = color;
                    element.dispatchEvent(new Event('change'));
                }
            });
        });
    });
</script>
