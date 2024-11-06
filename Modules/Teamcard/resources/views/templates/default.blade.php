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

<div class="team-card-holder d-flex flex-wrap">
    @php
        $count = 0;
    @endphp

    @if ($teamcard->count() > 0)
        @foreach ($teamcard as $member)
            @php
                $count++;
            @endphp
            <div class="team-card-item col-md-6 col-12 mb-3 overflow-hidden text-start my-5 d-flex flex-wrap">
                <div class="col-md-6 pe-2">
                    @if ($member['file'])
                        <div class="team-card-item-image rounded-circle" style="background-image: url('{{ thumbnail($member['file'], 800) }}');"></div>
                    @else
                        <div class="rounded-circle">
                            <img width="300" height="300" src="{{ asset('modules/teamcard/default-content/default-image.svg') }}"/>
                        </div>
                    @endif
                </div>

                <div class="col-md-6 ps-2">
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
        <div>
            Add your teamcard.
        </div>
    @endif
</div>

