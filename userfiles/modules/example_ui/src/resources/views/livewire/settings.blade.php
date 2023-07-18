<div style="width:320px;margin:0 auto;padding-top:50px">

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="#tabs-home-3" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                        Microweber Ui
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#tabs-profile-3" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab"><!-- Download SVG icon from http://tabler-icons.io/i/user -->
                        Module Options
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body" style="padding:5px">
            <div class="tab-content">
                <div class="tab-pane active show" id="tabs-home-3" role="tabpanel">
                    @include('microweber-module-example-ui::livewire.ui')
                </div>
                <div class="tab-pane" id="tabs-profile-3" role="tabpanel">
                    <div>
                        @include('microweber-module-example-ui::livewire.module-options')
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
