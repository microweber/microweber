<div style="padding:150px;">

    <div>
        <input wire:model="search" type="text" placeholder="Search fonts...">
    </div>

    <div>
        @foreach($fonts as $font)
          <div>
              {{$font['family']}}
          </div>
        @endforeach
    </div>

    <div>
        {!! $fonts->links('livewire-tables::specific.bootstrap-4.pagination') !!}
    </div>
</div>
