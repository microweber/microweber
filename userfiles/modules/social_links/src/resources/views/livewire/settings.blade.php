<div class="row">
    <div class="col-12">

        <div class="mt-4">
            <label class="form-label"><?php _e("Select and type socials links you want to show"); ?></label>
            <small class="text-muted d-block mb-2"><?php _e("Select the social networks you want to see on your site, blog and store"); ?></small>
        </div>


        @php
            $socialNetworksEnabled = [];
            $socialLinksOptions = get_module_options($this->moduleId);
            foreach ($socialLinksOptions as $socialLinkOption) {
                if (strpos($socialLinkOption['option_key'], '_enabled') !== false) {
                    $socialNetworksEnabled[$socialLinkOption['option_key']] =  $socialLinkOption['option_value'];
                }
            }
        @endphp


        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('socialNetworksEnabled', @json($socialNetworksEnabled));
            });
            document.addEventListener('mw-option-saved', ($event) => {
                let newSocialNetworksEnabled = Alpine.store('socialNetworksEnabled');
                delete newSocialNetworksEnabled[$event.detail.optionKey];
                newSocialNetworksEnabled[$event.detail.optionKey] = $event.detail.optionValue;
                Alpine.store('socialNetworksEnabled', newSocialNetworksEnabled);

                mw.top().app.editor.dispatch('onModuleSettingsChanged', ({'moduleId': '{{$this->moduleId}}'} || {}))
            });
        </script>

        <div x-data>
        @foreach(getSupprtedSocialNetworks() as $socialNetwork=>$socialNetworkData)
            @php
                $socialNetworkOptionKeyUrl = $socialNetwork . '_url';
                $socialNetworkOptionKeyEnable = $socialNetwork . '_enabled';
                $socialNetworkIsEnabled = get_option($socialNetworkOptionKeyEnable, $this->moduleId);
            @endphp
            <div class="form-check my-3">
                <div class="d-flex flex-wrap align-items-center">
                    <div class="d-flex col-xl-3 col-md-6 col-12">
                        <livewire:microweber-option::toggle value="y" :optionKey="$socialNetworkOptionKeyEnable" :optionGroup="$moduleId" module="social_links" />
                        <div>
                            <label class="form-check-label d-flex align-items-center" for="{{$socialNetworkOptionKeyEnable}}">
                                <i class="mdi {{$socialNetworkData['icon']}} mdi-20px lh-1_0 me-1"></i> {{ucwords($socialNetwork)}}
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-9 col-md-6 col-12" x-show="$store.socialNetworksEnabled.{{$socialNetworkOptionKeyEnable}} == 1" x-transition>
                        <livewire:microweber-option::text :optionKey="$socialNetworkOptionKeyUrl" :optionGroup="$moduleId" module="social_links" />
                    </div>
                </div>
            </div>
        @endforeach
        </div>

    </div>
</div>
