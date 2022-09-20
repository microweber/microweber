<div class="card" wire:init="loadData">
    <div class="card-body">
        {!! json_encode($data, JSON_PRETTY_PRINT) !!}
    </div>
</div>
