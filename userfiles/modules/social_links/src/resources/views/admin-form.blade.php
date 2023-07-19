<div class="row">
    <div class="col-12">

        <div class="mt-4">
            <label class="form-label"><?php _e("Select and type socials links you want to show"); ?></label>
            <small class="text-muted d-block mb-2"><?php _e("Select the social networks you want to see on your site, blog and store"); ?></small>
        </div>

        @php
        $socialNetworks = array(
            'instagram' => array(
                'icon' => 'mdi-instagram'
            ),
            'facebook' => array(
                'icon' => 'mdi-facebook'
            ),
            'twitter' => array(
                'icon' => 'mdi-twitter'
            ),
            'googleplus' => array(
                'icon' => 'mdi-google-plus'
            ),
            'pinterest' => array(
                'icon' => 'mdi-pinterest'
            ),
            'youtube' => array(
                'icon' => 'mdi-youtube'
            ),
            'linkedin' => array(
                'icon' => 'mdi-linkedin'
            ),
            'github' => array(
                'icon' => 'mdi-github'
            ),
            'soundcloud' => array(
                'icon' => 'mdi-soundcloud'
            ),
            'mixcloud' => array(
                'icon' => 'mdi-mixcloud'
            ),
            'medium' => array(
                'icon' => 'mdi-medium'
            ),
            'discord' => array(
                'icon' => 'mdi-discord'
            ),
            'skype' => array(
                'icon' => 'mdi-skype'
            )
        );
        @endphp

        @foreach($socialNetworks as $socialNetwork=>$socialNetworkData)
        <div class="form-check my-3">
            <div class="d-flex flex-wrap align-items-center">
                <div class="col-xl-3 col-md-6 col-12">
                   <x-microweber-ui::toggle>
                       <div style="margin-left:10px">
                           <label class="form-check-label d-flex align-items-center" for="{{$socialNetwork}}_enabled">
                               <i class="mdi {{$socialNetworkData['icon']}} mdi-20px lh-1_0 me-1"></i> {{ucwords($socialNetwork)}}
                           </label>
                       </div>
                   </x-microweber-ui::toggle>
                </div>
                <div class="col-xl-9 col-md-6 col-12">
                    @php
                    $socialNetworkOptionKeyUrl = $socialNetwork . '_url';
                    @endphp
                    <livewire:microweber-option::text :optionKey="$socialNetworkOptionKeyUrl" :optionGroup="$params['id']" module="social_links" />
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>
