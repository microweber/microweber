<div>
    <label class="form-label text-uppercase text-sm">Alignment</label>
    <div class="btn-group" role="group" aria-label="Button Alignment">
        <button wire:click="$set('settings.align', 'left')" type="button"
                class="btn btn-dark btn-sm @if($settings['align'] == 'left'): active @endif"><i
                class="fa fa-align-left"></i>
        </button>
        <button wire:click="$set('settings.align', 'center')" type="button"
                class="btn btn-dark btn-sm @if($settings['align'] == 'center'): active @endif"><i
                class="fa fa-align-center"></i>
        </button>
        <button wire:click="$set('settings.align', 'right')" type="button"
                class="btn btn-dark btn-sm @if($settings['align'] == 'right'): active @endif"><i
                class="fa fa-align-right"></i>
        </button>
    </div>
</div>
