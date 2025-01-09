<?php
/*

type: layout

name: Skin-1

description: Skin-1

*/
?>

<script>
    $(document).ready(function () {
        $('.js-show-team-member', '#<?php echo $params['id']; ?>').on('click', function () {
            var id = $(this).data('id');
            $('.js-member').hide();
            $('.js-member[data-id="' + id + '"]').show();
        });
    });
</script>

<div class="row text-center text-md-start d-flex align-items-center justify-content-center justify-content-lg-between">
    <div class="col-sm-10 col-md-6 col-lg-5 col-lg-4 mb-5 mb-md-0">
        @if ($teamcard->count() > 0)
            @foreach ($teamcard as $key => $member)
                <div class="w-450 mx-auto js-member" data-id="{{ $key }}" style="{{ $key > 0 ? 'display: none;' : '' }}">
                    @if ($member['file'])
                        <div class="img-as-background square">
                            <img loading="lazy" src="{{ thumbnail($member['file'], 850) }}"/>
                        </div>
                    @else
                        <div class="img-as-background square">
                            <img loading="lazy" src="{{ asset('modules/teamcard/default-content/default-image.svg') }}"/>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    <div class="col-sm-10 col-md-6 col-lg-5 col-lg-4">
        @if ($teamcard->count() > 0)
            @foreach ($teamcard as $key => $member)
                <div class="js-member" data-id="{{ $key }}" style="{{ $key > 0 ? 'display: none;' : '' }}">
                    <h1 class="mb-1">{{ $member['name'] }}</h1>
                    <p class="mb-3">{{ $member['role'] }}</p>
                    <p>{{ $member['bio'] }}</p>
                    <module type="social_links" template="skin-2"/>
                </div>
            @endforeach
        @endif
    </div>

    <div class="col-sm-10 col-md-12 col-lg-2">
        <div class="d-flex flex-lg-column align-items-center justify-content-center mt-7 mt-lg-0">
            @if ($teamcard->count() > 0)
                @foreach ($teamcard as $key => $member)
                    <div class="w-80 m-4 cursor-pointer js-show-team-member" data-id="{{ $key }}">
                        <div class="img-as-background rounded-circle square">
                            <img loading="lazy" src="{{ thumbnail($member['file'], 80) }}"/>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
