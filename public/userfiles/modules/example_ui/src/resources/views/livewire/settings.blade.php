<div>
    <div class="card">
        <div class="card-header px-3 pb-0 pt-4">
            <ul class="list-unstyled d-flex align-items-center gap-3" data-bs-toggle="tabs" role="tablist">
                <li role="presentation">
                    <a href="#tabs-home-3" class="mw-admin-action-links mw-adm-liveedit-tabs" data-bs-toggle="tab" aria-selected="true" role="tab"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                        Microweber Ui
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tabs-profile-3" class="mw-admin-action-links mw-adm-liveedit-tabs active" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab"><!-- Download SVG icon from http://tabler-icons.io/i/user -->
                        Module Options
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body" style="padding:5px">
            <div class="tab-content">
                <div class="tab-pane" id="tabs-home-3" role="tabpanel">
                    @include('microweber-module-example-ui::livewire.ui')
                </div>
                <div class="tab-pane active show" id="tabs-profile-3" role="tabpanel">
                    @include('microweber-module-example-ui::livewire.module-options')
                </div>
            </div>
        </div>
    </div>
</div>
