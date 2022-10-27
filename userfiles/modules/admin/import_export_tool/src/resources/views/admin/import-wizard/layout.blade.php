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

            .import-wizard__link .desc {
                text-transform: capitalize;
                display: inline-block;
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
                    <a href="{{route('admin.import-export-tool.import-wizard')}}" class="import-wizard__link">
                        <span class="step">1</span>
                        <span class="desc">Import Type</span>
                    </a>
                </li>
                <li class="import-wizard__item @if($tab =='upload') active @endif">
                    <div class="import-wizard__link">
                        <span class="step">2</span>
                        <span class="desc">Upload File</span>
                    </div>
                </li>
                <li class="import-wizard__item @if($tab =='map') active @endif">
                    <div class="import-wizard__link">
                        <span class="step">3</span>
                        <span class="desc">Map Fields</span>
                    </div>
                </li>
                <li class="import-wizard__item @if($tab =='import') active @endif">
                    <div class="import-wizard__link">
                        <span class="step">4</span>
                        <span class="desc">Import</span>
                    </div>
                </li>
            </ul>
            <div class="mt-5 mb-5">
                @yield('content')
            </div>
        </div>

    </div>
</div>
