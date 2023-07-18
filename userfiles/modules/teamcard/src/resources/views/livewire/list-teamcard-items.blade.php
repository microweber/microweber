<div>

    @if(!empty($items))

        <div x-data="{
showEditTab: 'main'
}" x-init="() => {
    window.livewire.on('switchToMainTab', () => {
        showEditTab = 'main'
    })
}">
            <div style="overflow: hidden">


                <div x-show="showEditTab=='main'" x-transition:enter="tab-pane-slide-left-active">
                    <div class="row row-cards">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><?php _e('Teamcard items') ?></h3>
                                </div>
                                <div class="list-group list-group-flush list-group-hoverable">
                                    @foreach($items as $item)
                                        <div class="list-group-item" >
                                            <div class="row align-items-center">
                                                <div class="col-auto">move</div>
                                                <div class="col-auto">
                                                    @if(isset($item['file']))
                                                        <a href="#"><span class="avatar"
                                                                          style="background-image: url('{{ $item['file'] }}')"></span></a>
                                                    @endif
                                                </div>
                                                <div class="col text-truncate">

                                                    @if(isset($item['name']))
                                                        <a href="#" class="text-reset d-block">{{ $item['name'] }}</a>

                                                    @endif
                                                    <div class="d-block text-muted text-truncate mt-n1">

                                                        @if(isset($item['bio']))
                                                            {{ $item['bio'] }}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-auto">


                                                    <x-microweber-ui::button class="ms-2"
                                                                             x-on:click="showEditTab = 'tabs-nav-tab-{{ $item['itemId']  }}'">
                                                        bbbbbbbbbb
                                                    </x-microweber-ui::button>


                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                @foreach($items as $item)

                    <div

                         x-show="showEditTab=='tabs-nav-tab-{{ $item['itemId']  }}'"
                         x-transition:enter="tab-pane-slide-right-active">
                        <button x-on:click="showEditTab = 'main'">Back</button>


                        <div>

                            <livewire:microweber-module-teamcard::edit-item :moduleId="$moduleId"
                                                                            :moduleType="$moduleType"
                                                                            :wire:key="$item['itemId']"
                                                                            :itemId="$item['itemId']"/>

                        </div>


                    </div>
                @endforeach


            </div>
        </div>
    @else

        <div class="alert-info">
            No items
        </div>

    @endif


</div>
