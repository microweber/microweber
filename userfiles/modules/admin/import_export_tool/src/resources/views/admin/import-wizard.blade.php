<div class="card style-1 mb-3">

    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="admin/import_export_tool"/>
    </div>

    <div class="card-body pt-3">

        <style>
            .tab-list {
                list-style: none;
                text-align: center;
                padding: 25px 0;
                border-bottom: 1px solid #e5e5e5;
            }
            .tab-list__item {
                display: inline-block;
                -webkit-transition: all .4s ease;
                -o-transition: all .4s ease;
                -moz-transition: all .4s ease;
                transition: all .4s ease;
                padding: 0 13px;
            }
            .tab-list__link {
                font-weight: 700;
                font-size: 15px;
                color: #fff;
                display: inline-block;
                -webkit-border-radius: 22.5px;
                -moz-border-radius: 22.5px;
                border-radius: 22.5px;
                background: #999;
                width: 200px;
                text-align: left;
            }
            .tab-list__link .step {
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
            .tab-list__link .desc {
                text-transform: capitalize;
                display: inline-block;
            }
            .tab-list .active .tab-list__link {
                background: #4592ff;
            }
            .tab-list .active .tab-list__link .step {
                background: #0000001a;
            }
            .tab-list .tab-list__link:hover {
                color: #fff;
                text-decoration: none;
            }
            .import-wizard-select-type {
                width: 200px;
                height: 100px;
                background: #f0f0f0;
                border: 1px solid #ddd;
                border-radius: 4px;
                margin-right:10px;
                cursor:pointer;
                text-align: center;
                padding-top:20px;
                padding-bottom:20px;
            }
            .import-wizard-select-type:hover {
                background: #4592ff;
                border: 1px solid #3e83e5;
                color:#fff;
            }
        </style>
        <form class="wizard-container" method="POST" action="#" id="js-wizard-form" novalidate="novalidate">

            <div class="text-center">
                <h1>
                    Importing new feed
                </h1>
                <p>Please follow the wizard to import new feed.</p>
            </div>

            <ul class="tab-list nav nav-pills justify-content-center">
                <li class="tab-list__item active">
                    <a class="tab-list__link" href="#tab1" data-toggle="tab">
                        <span class="step">1</span>
                        <span class="desc">Import Type</span>
                    </a>
                </li>
                <li class="tab-list__item">
                    <a class="tab-list__link" href="#tab2" data-toggle="tab">
                        <span class="step">2</span>
                        <span class="desc">Upload File</span>
                    </a>
                </li>
                <li class="tab-list__item">
                    <a class="tab-list__link" href="#tab3" data-toggle="tab">
                        <span class="step">3</span>
                        <span class="desc">Map Fields</span>
                    </a>
                </li>
                <li class="tab-list__item">
                    <a class="tab-list__link" href="#tab4" data-toggle="tab">
                        <span class="step">4</span>
                        <span class="desc">Import</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-3">
                <div class="tab-pane active " id="tab1">
                    <div class="d-flex justify-content-center">
                        <div class="import-wizard-select-type">
                            <b>Pages</b>
                        </div>
                        <div class="import-wizard-select-type">
                            <b>Posts</b>
                        </div>
                        <div class="import-wizard-select-type">
                            <b>Products</b>
                        </div>
                        <div class="import-wizard-select-type">
                            <b>Categories</b>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab2">

                </div>
                <div class="tab-pane" id="tab3">

                </div>
                <div class="tab-pane" id="tab4">

                </div>
            </div>
        </form>

    </div>
</div>
