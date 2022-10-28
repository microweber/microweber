<div class="card style-1 mb-3">

    <div class="card-header">
        <?php $module_info = module_info('admin/import_export_tool'); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/>
            <strong><?php _e($module_info['name']); ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">

       <div>
           <a href="{{route('admin.import-export-tool.index')}}" class="btn btn-outline-primary btn-sm">
               <i class="fa fa-arrow-left"></i> Back to Imports</a>
       </div>

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


            <div class="row">
                <div class="mx-auto col-md-8">
                    <h4>
                        Import Wizard
                    </h4>
                    <p>Please follow the wizard to import new feed.</p>
                </div>
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
                            <x-import_export_tool::icon width="38px" name="{{$import_feed['import_to']}}" />
                        </div>
                        @else
                            <span class="desc">Import Type</span>
                        @endif
                    </a>
                </li>
                <li class="import-wizard__item @if($tab =='upload') active @endif">
                    <a href="#" wire:click="showTab('upload')" class="import-wizard__link">
                        <span class="step">2</span>
                        @if($import_feed['source_type'] && !empty($import_feed['source_file_realpath']))
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
                            <div class="desc-icon">
                                <x-import_export_tool::icon width="38px" name="check" />
                            </div>
                        @else
                        <span class="desc">Upload File</span>
                        @endif
                    </a>
                </li>
                <li class="import-wizard__item @if($tab =='map') active @endif">
                    <a href="#" wire:click="showTab('map')" class="import-wizard__link">
                        <span class="step">3</span>
                        @if($import_feed['mapped_content_count'])
                            <div class="desc-box">
                                <span class="desc">Map fields</span>
                                <span class="small-desc">
                                    Mapped
                                </span>
                            </div>
                            <div class="desc-icon">
                                <x-import_export_tool::icon width="38px" name="check" />
                            </div>
                        @else
                        <span class="desc">Map Fields</span>
                        @endif
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
