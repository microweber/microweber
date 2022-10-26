<div class="card style-1 mb-3">

    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="admin/import_export_tool"/>
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
            }
            .import-wizard__link {
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
            }
            .import-wizard .active .import-wizard__link .step {
                background: #0000001a;
            }
            .import-wizard .import-wizard__link:hover {
                color: #fff;
                text-decoration: none;
            }
            .import-wizard-select-type {
                width: 200px;
                background: #f0f0f0;
                border: 1px solid #ddd;
                border-radius: 4px;
                margin-right:10px;
                cursor:pointer;
                text-align: center;
                padding-top:20px;
                padding-bottom:25px;
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

            <ul class="import-wizard justify-content-center">
                <li class="import-wizard__item active">
                    <a class="import-wizard__link" href="#tab1" data-toggle="tab">
                        <span class="step">1</span>
                        <span class="desc">Import Type</span>
                    </a>
                </li>
                <li class="import-wizard__item">
                    <a class="import-wizard__link" href="#tab2" data-toggle="tab">
                        <span class="step">2</span>
                        <span class="desc">Upload File</span>
                    </a>
                </li>
                <li class="import-wizard__item">
                    <a class="import-wizard__link" href="#tab3" data-toggle="tab">
                        <span class="step">3</span>
                        <span class="desc">Map Fields</span>
                    </a>
                </li>
                <li class="import-wizard__item">
                    <a class="import-wizard__link" href="#tab4" data-toggle="tab">
                        <span class="step">4</span>
                        <span class="desc">Import</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-5 mb-5">
                <div class="tab-pane active" id="tab1">
                    <div class="d-flex justify-content-center">
                        <div class="import-wizard-select-type">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:50px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <div class="mt-2">
                                <b>Pages</b>
                            </div>
                        </div>
                        <div class="import-wizard-select-type">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:50px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                            </svg>
                            <div class="mt-2">
                                <b>Posts</b>
                            </div>
                        </div>
                        <div class="import-wizard-select-type">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:50px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            <div class="mt-2">
                             <b>Products</b>
                            </div>
                        </div>
                        <div class="import-wizard-select-type">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:50px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v2.25A2.25 2.25 0 006 10.5zm0 9.75h2.25A2.25 2.25 0 0010.5 18v-2.25a2.25 2.25 0 00-2.25-2.25H6a2.25 2.25 0 00-2.25 2.25V18A2.25 2.25 0 006 20.25zm9.75-9.75H18a2.25 2.25 0 002.25-2.25V6A2.25 2.25 0 0018 3.75h-2.25A2.25 2.25 0 0013.5 6v2.25a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                            <div class="mt-2">
                                <b>Categories</b>
                            </div>
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
