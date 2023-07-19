<div class="row">
    <div class="col-12">

        <div class="mt-4">
            <label class="form-label"><?php _e("Select and type socials links you want to show"); ?></label>
            <small class="text-muted d-block mb-2"><?php _e("Select the social networks you want to see on your site, blog and store"); ?></small>
        </div>

        @foreach(getSupprtedSocialNetworks() as $socialNetwork=>$socialNetworkData)
            @php
                $socialNetworkOptionKeyUrl = $socialNetwork . '_url';
                $socialNetworkOptionKeyEnable = $socialNetwork . '_enabled';
                $socialNetworkIsEnabled = get_option($socialNetworkOptionKeyEnable, $this->moduleId);
            @endphp
            <div class="form-check my-3" x-data="{socialNetworkEnabled: '{{$socialNetworkIsEnabled}}'}">
                <div class="d-flex flex-wrap align-items-center">

                    <div @mw-option-saved.window="function() {
                        if ($event.detail.optionKey === '{{$socialNetworkOptionKeyEnable}}') {
                            socialNetworkEnabled = $event.detail.optionValue;
                        }
                    }" class="d-flex col-xl-3 col-md-6 col-12">

                        <livewire:microweber-option::toggle value="y" :optionKey="$socialNetworkOptionKeyEnable" :optionGroup="$moduleId" module="social_links" />
                        <div>
                            <label class="form-check-label d-flex align-items-center" for="{{$socialNetworkOptionKeyEnable}}">
                                <i class="mdi {{$socialNetworkData['icon']}} mdi-20px lh-1_0 me-1"></i> {{ucwords($socialNetwork)}}
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-9 col-md-6 col-12" x-show="socialNetworkEnabled" x-transition>
                        <livewire:microweber-option::text :optionKey="$socialNetworkOptionKeyUrl" :optionGroup="$moduleId" module="social_links" />
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</div>
