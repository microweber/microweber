<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
<div x-data="{ activeTab: 'default' }">

    <style>
        .iframe-container {
            position: relative;
            height:350px;
        }
        .iframe {
            width: 200%;
            height: 200%;
            transform-origin: 0 0;
            position: absolute;
            left: 0;
            top: 0;
            transform: scale(0.5);
            border: 0px transparent;
        }
    </style>

    @php
    $campaign = $getCampaign();
    @endphp

    @if(isset($campaign['emailTemplate']))

        <style>
            .iframe-container {
                height:450px !important;
            }
        </style>

        <div class="flex gap-4 justify-end mb-4">
            <x-filament::button
                size="sm"
                color="gray"
                onclick="confirm('Are you sure you want to change the template?') || event.stopImmediatePropagation()"
                wire:click="deleteTemplate"
            >
                Change Template
            </x-filament::button>
            <x-filament::button
                size="sm"
                color="gray"
                href="{{route('filament.admin.pages.newsletter.template-editor') . '?id=' . $campaign['emailTemplate']['id'] . '&campaignId=' . $campaign['campaignId']}}"
                tag="a"
            >
                Edit Template
            </x-filament::button>
        </div>

        <div class="iframe-container rounded-md">
            <iframe
                loading="lazy"
                src="{{admin_url('modules/newsletter/preview-email-template-saved')}}?id={{$campaign['emailTemplate']['id']}}"
                class="iframe rounded-md"></iframe>
        </div>

    @else


        <x-filament::tabs>
            <x-filament::tabs.item
                alpine-active="activeTab === 'default'"
                x-on:click="activeTab = 'default'"
            >
                Default Templates
            </x-filament::tabs.item>

            <x-filament::tabs.item
                alpine-active="activeTab === 'existing'"
                x-on:click="activeTab = 'existing'"
            >
                Existing Templates
            </x-filament::tabs.item>

        </x-filament::tabs>

        <div x-show="activeTab === 'default'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
            @foreach($getEmailTemplates() as $template)

                <div class="group rounded-md border border-gray-500/10 hover:shadow-xl">
                    <div class="relative">

                        <div class="iframe-container rounded-md">
                            <iframe
                                loading="lazy"
                                src="{{admin_url('modules/newsletter/preview-email-template')}}?filename={{$template['name']}}"
                                scrolling="no"
                                class="iframe rounded-md"></iframe>
                        </div>

                        <div class="p-4">
                            <h3 class="text-lg font-semibold">{{ucfirst($template['name'])}}</h3>
                        </div>

                        <div class="opacity-0 group-hover:opacity-100 transition absolute top-0 w-full h-full flex items-center gap-4 p-8 backdrop-blur-sm">

                            <div class="w-full flex items-center justify-center gap-4">

                                <x-filament::button
                                    size="sm"
                                    color="info"
                                    wire:click="startWithTemplate('{{$template['name']}}')"
                                >
                                    @if($getCampaignId())
                                        Use
                                    @else
                                        Start
                                    @endif
                                </x-filament::button>

                                <x-filament::button
                                    size="sm"
                                    color="gray"
                                    href="{{admin_url('modules/newsletter/preview-email-template')}}?filename={{$template['name']}}"
                                    tag="a"
                                    target="_blank"
                                >
                                    Preview
                                </x-filament::button>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
       <div x-show="activeTab === 'existing'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
           @foreach(\MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate::all() as $template)

               <div class="group rounded-md border border-gray-500/10 hover:shadow-xl">
                   <div class="relative">

                       <div class="iframe-container rounded-md">
                           <iframe
                               loading="lazy"
                               src=""
                               scrolling="no"
                               class="iframe rounded-md"></iframe>
                       </div>

                       <div class="p-4">
                            <h3 class="text-lg font-semibold">{{ucfirst($template['title'])}}</h3>
                        </div>

                       <div class="opacity-0 group-hover:opacity-100 transition absolute top-0 w-full h-full flex items-center gap-4 p-8 backdrop-blur-sm">

                           <div class="w-full flex items-center justify-center gap-4">

                               <x-filament::button
                                   size="sm"
                                   color="info"
                                   wire:click="startWithTemplateById('{{$template['id']}}')"
                               >
                                  @if($getCampaignId())
                                   Use
                                  @else
                                   Start
                                  @endif
                               </x-filament::button>

                               <x-filament::button
                                   size="sm"
                                   color="gray"
                                   href=""
                                   tag="a"
                                   target="_blank"
                               >
                                   Preview
                               </x-filament::button>
                           </div>
                       </div>
                   </div>
               </div>

           @endforeach
       </div>

    @endif
</div>
</x-dynamic-component>
