@php
/*
type: layout
name: Default
description: Default
*/
@endphp

<style>
    /* audit-test 2026-05-07 PM TICKET-AV bundle: was background-size/position
       on a div, now object-fit/object-position on an inner <img>. aspect-ratio:1
       keeps the square shape that padding-top:100% used to give. */
    .team-card-item-image {
        aspect-ratio: 1 / 1;
        overflow: hidden;
    }
    .team-card-item-image > img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }
</style>

<div class="team-card-holder row">
    @php
        $count = 0;
    @endphp

    @if ($teamcard->count() > 0)
        @foreach ($teamcard as $member)
            @php
                $count++;
            @endphp
            <div class="row col-lg-6 col-12 mx-auto team-card-item mb-3 overflow-hidden text-lg-start text-center justify-content-center my-5">
                <div class="col-sm-4 pe-2">
                    @if ($member['file'])
                        {{-- audit-test 2026-05-07 PM TICKET-AV bundle: migrated `<div bg-image>`
                             to real `<img>` (closes CSS-injection vector + adds alt + lazy). --}}
                        {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                        <div class="team-card-item-image rounded-circle">
                            {!! responsive_thumbnail($member['file'], 800, null, [
                                'alt' => $member['name'] ?? __('Team member'),
                            ]) !!}
                        </div>
                    @else
                        <div class="rounded-circle">
                            <img width="300" height="300" src="{{ asset('modules/teamcard/default-content/default-image.svg') }}" alt="{{ $member['name'] ?? __('Team member') }}" class="img-fluid"/>
                        </div>
                    @endif
                </div>

                <div class="col-lg-8 col-12 ps-2">
                    <h3 class="team-card-item-name">
                        {{$member['name']}}
                    </h3>
                    <p class="team-card-item-position">
                        {{$member['role']}}
                    </p>
                    {{-- task-2026-05-05-90021f — security: external
                         user-supplied URL must carry rel=noopener
                         noreferrer to block tabnabbing. --}}
                    @if(!empty($member['website']))
                    <a class="d-block mb-3" href="{{ $member['website'] }}" target="_blank" rel="noopener noreferrer">
                        {{$member['website']}}
                    </a>
                    @endif
                    <p class="team-card-item-bio italic">
                        {{$member['bio']}}
                    </p>
                </div>
            </div>
        @endforeach
    @else
        <p class="mw-pictures-clean">No team members added in the module. Please add your teammates</p>
    @endif
</div>

