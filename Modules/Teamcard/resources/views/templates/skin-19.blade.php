@php
/*

type: layout

name: Team Cards

description: Team members using x-team-card component

*/
@endphp

<x-row class="g-4">
    @if($teamcard->count() > 0)
        @foreach($teamcard as $member)
            <x-col size="12" size-md="6" size-lg="3">
                <x-team-card
                    :name="$member['name'] ?? ''"
                    :role="$member['role'] ?? ''"
                    :bio="$member['bio'] ?? ''"
                    :image="$member['file'] ? thumbnail($member['file'], 300) : asset('modules/teamcard/default-content/default-image.svg')"
                    :website="$member['website'] ?? ''"
                    class="shadow-sm h-100"
                />
            </x-col>
        @endforeach
    @else
        <p class="mw-pictures-clean">No team members added in the module. Please add your teammates</p>
    @endif
</x-row>