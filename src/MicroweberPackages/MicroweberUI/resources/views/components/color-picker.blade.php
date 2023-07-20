@props(['label'=> 'Pick color'])


@php
    $md5name = md5(time().rand(1111,9999));
@endphp

<div>


    <input style="display:none" id="{{$md5name}}" {!! $attributes->merge() !!} />

    <div class="d-flex justify-content-between align-items-center">
        <div>
            <label>
                {{$label}}
            </label>
        </div>
        <div>
        <input type="button" style="
          width: 30px;
          height: 30px;
          border-radius: 50%;
          background: #fff;
          font-size:0px;
          cursor: pointer;
          border: 1px solid #cacaca;"
           id="open-color-picker-{{$md5name}}"
        />
        </div>
    </div>

    <script>

        $(document).ready(function () {
            let element = document.getElementById('open-color-picker-{{$md5name}}');
            setTimeout(function () {
                element.style.backgroundColor = document.getElementById('{{$md5name}}').value;
            }, 5);
            element.addEventListener('click', function () {

                let colorPicker = mw.app.colorPicker;
                colorPicker.setPositionToElement(element);
                colorPicker.selectColor('#{{$md5name}}', function(color) {
                    element.style.backgroundColor = color;
                });

            });
        });
    </script>
</div>
