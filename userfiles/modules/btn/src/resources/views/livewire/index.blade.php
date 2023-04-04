<div>
      <div>
        <input type="text" wire:model.debounce.100ms="settings.text"/>

        <select wire:model.debounce.100ms="settings.align">
            <option value="none">none</option>
            <option value="left">left</option>
            <option value="right">right</option>
            <option value="center">center</option>
        </select>

    </div>



    <div class="btn-group" role="group" aria-label="Button Align">
        <button wire:click="setAlign('none')"  type="button" class="btn btn-primary @if($settings['align'] == 'none'): active @endif">None</button>
        <button wire:click="setAlign('left')"  type="button" class="btn btn-primary @if($settings['align'] == 'left'): active @endif">Left</button>
        <button wire:click="setAlign('center')" type="button" class="btn btn-primary @if($settings['align'] == 'center'): active @endif">Middle</button>
        <button wire:click="setAlign('right')" type="button" class="btn btn-primary @if($settings['align'] == 'right'): active @endif">Right</button>
    </div>

</div>
