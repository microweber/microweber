@php
/*
type: layout
name: Default
description: Default
*/
@endphp

<style>
    .team-card-item-image {
        padding-top: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
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
                        <div class="team-card-item-image rounded-circle" style="background-image: url('{{ thumbnail($member['file'], 800) }}');"></div>
                    @else
                        <div class="rounded-circle">
                            <img width="300" height="300" src="{{ asset('modules/teamcard/default-content/default-image.svg') }}"/>
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
                    <a class="d-block mb-3" href="{{ $member['website'] }}" target="_blank">
                        {{$member['website']}}
                    </a>
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

