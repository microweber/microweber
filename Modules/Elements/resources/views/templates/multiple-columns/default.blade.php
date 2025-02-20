@php
    $cols = 3;
@endphp

<div class="mw-row no-edit" style="margin-block:0;padding: 15px 0;">
    @for($i = 1; $i <= $cols; $i++)
        <div class="mw-col" style="width: {{ 100/$cols }}%">
            <div class="mw-col-container safe-mode element">
                <div class="mw-empty-element element allow-drop allow-edit"></div>
            </div>
        </div>
    @endfor
</div>
