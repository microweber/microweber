<div class="card style-1 mb-3">

    <div class="card-header">
        <?php $module_info = module_info('admin/import_export_tool'); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/>
            <strong><?php _e($module_info['name']); ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">

        <style>
            .import-wizard {
                list-style: none;
                text-align: center;
                padding: 25px 0;
                border-bottom: 1px solid #e5e5e5;
            }

            .import-wizard__item {
                display: inline-block;
                -webkit-transition: all .4s ease;
                -o-transition: all .4s ease;
                -moz-transition: all .4s ease;
                transition: all .4s ease;
                padding: 0 13px;
                margin-top:10px;
            }

            .import-wizard__link {
                font-size: 15px;
                color: #fff;
                display: inline-block;
                -webkit-border-radius: 22.5px;
                -moz-border-radius: 22.5px;
                border-radius: 22.5px;
                background: #999;
                width: 185px;
                text-align: left;
            }

            .import-wizard__link .step {
                display: inline-block;
                height: 45px;
                width: 45px;
                line-height: 45px;
                text-align: center;
                -webkit-border-radius: 50%;
                -moz-border-radius: 50%;
                border-radius: 50%;
                background: #666;
                font-size: 18px;
                margin-right: 10px;
            }

            .import-wizard__link .desc-box {
                display: inline-block;
                width: 126px;
                float: right;
                font-size: 12px;
                padding-top: 5px;
            }
            .import-wizard__link .desc-icon {
                width: 33px;
                position: absolute;
                margin-top: -42px;
                margin-left: 135px;
                opacity: 0.4;
            }

            .import-wizard__link .desc {
                text-transform: capitalize;

            }
            .import-wizard__link .small-desc {
                text-transform: capitalize;
                display: block;
                color: #eee;
                font-size: 10px;
            }

            .import-wizard .active .import-wizard__link {
                background: #4592ff;
                font-weight: bold;
            }

            .import-wizard .active .import-wizard__link .step {
                background: #0000001a;
            }

            .import-wizard .import-wizard__link:hover {
                color: #fff;
                text-decoration: none;
            }

            a.import-wizard-select-type {
                color: #3e3e3e;
            }

            a.import-wizard-select-type:hover {
                text-decoration: none;
            }

            .import-wizard-select-type {
                width: 200px;
                background: #f0f0f0;
                border: 1px solid #ddd;
                border-radius: 10px;
                margin-right: 10px;
                cursor: pointer;
                text-align: center;
                padding-top: 20px;
                padding-bottom: 25px;
            }

            .import-wizard-select-type:hover {
                background: #4592ff;
                border: 1px solid #3e83e5;
                color: #fff;
            }
        </style>

        @if (session()->has('successMessage'))
            <script id="js-success-message-{{time()}}">
                mw.notification.success('{{ session('successMessage') }}');
            </script>
        @endif

        @if (session()->has('errorMessage'))
            <script id="js-error-message-{{time()}}">
                mw.notification.error('{{ session('errorMessage') }}');
            </script>
        @endif

        <div class="wizard-container">

            <div class="text-center">
                <h1>
                    Import Wizard
                </h1>
                <p>Please follow the wizard to import new feed.</p>
            </div>

            <ul class="import-wizard justify-content-center">
                <li class="import-wizard__item @if($tab =='type') active @endif">
                    <a href="#" wire:click="showTab('type')" class="import-wizard__link">
                        <span class="step">1</span>
                        @if($import_feed['import_to'])
                        <div class="desc-box">
                            <span class="desc">Import</span>
                            <span class="small-desc">{{$import_feed['import_to']}}</span>
                        </div>
                        <div class="desc-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:40px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                            </svg>
                        </div>
                        @else
                            <span class="desc">Import Type</span>
                        @endif
                    </a>
                </li>
                <li class="import-wizard__item @if($tab =='upload') active @endif">
                    <a href="#" wire:click="showTab('upload')" class="import-wizard__link">
                        <span class="step">2</span>
                        @if($import_feed['source_type'])
                            <div class="desc-box">
                                <span class="desc">Upload File</span>
                                <span class="small-desc">
                                    @if($import_feed['source_type'] == 'upload_file')
                                        From computer
                                    @else
                                        From URL
                                    @endif
                                </span>
                            </div>
                        @else
                        <span class="desc">Upload File</span>
                        @endif
                    </a>
                </li>
                <li class="import-wizard__item @if($tab =='map') active @endif">
                    <a href="#" wire:click="showTab('map')" class="import-wizard__link">
                        <span class="step">3</span>
                        <span class="desc">Map Fields</span>
                    </a>
                </li>
                <li class="import-wizard__item @if($tab =='import') active @endif">
                    <a href="#" wire:click="showTab('import')" class="import-wizard__link">
                        <span class="step">4</span>
                        <span class="desc">Import</span>
                    </a>
                </li>
            </ul>
            <div class="mb-5">
                @yield('content')
            </div>
        </div>

    </div>
</div>
