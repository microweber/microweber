@if (isset($slide['must-save-from-settings']))
    <div class="noedit noelement nodrop">
        <p> {{ _e('Click on settings to edit accordion item') }} </p>
    </div>
@else
    @if (isset($useTextFromLiveEdit) and $useTextFromLiveEdit)
        <div class="allow-drop edit" field="accordion-item-{{ $edit_field_key }}"
             rel="module-{{ $params['id'] }}">
            <div class="element">
                <p> {{ isset($slide['content']) ? $slide['content'] : 'Type your text here' }}</p>
            </div>
        </div>
    @else
        <div class="noedit noelement nodrop">
            <p>{{ isset($slide['content']) ? $slide['content'] : 'Type your text here' }}</p>
        </div>
    @endif
@endif
