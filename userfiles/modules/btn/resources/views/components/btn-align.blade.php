<div>
    <label class="form-label">Align</label>
    <div class="btn-group" role="group" aria-label="Button Align">
        <button wire:click="$set('settings.align', 'left')" type="button"
                class="btn btn-primary @if($settings['align'] == 'left'): active @endif"><i
                class="fa fa-align-left"></i>
        </button>
        <button wire:click="$set('settings.align', 'center')" type="button"
                class="btn btn-primary @if($settings['align'] == 'center'): active @endif"><i
                class="fa fa-align-center"></i>
        </button>
        <button wire:click="$set('settings.align', 'right')" type="button"
                class="btn btn-primary @if($settings['align'] == 'right'): active @endif"><i
                class="fa fa-align-right"></i>
        </button>
    </div>
</div>
