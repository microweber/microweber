<module type="admin/modules/info"/>

<style>
    #xml_url_result {
        width: 24px;
        height: 24px;
        line-height: 24px;
        margin: 0px 0px -8px 0px;
        display: inline-block;
    }

  /*  .icon-result-loading {
        background: url(../image/icon_loading.gif) no-repeat center !important;
    }

    .icon-result-success {
        background: url(../image/icon_success.png) no-repeat center !important;
    }

    .icon-result-error {
        background: url(../image/icon_error.png) no-repeat center !important;
    }

    .icon-result-info {
        background: url(../image/icon_info.png) no-repeat center !important;
    }*/

    #xml_url_result_text {
        font-size: 11px;
        padding-top: 5px;
        display: block;
        width: 80%;
    }

    #import .nav-tabs {
        height: 55px;
        padding-left: 10px;
    }

    #import .nav-tabs li {
        height: 55px;
        line-height: 55px;
        margin: 0px 1px 0px 1px;
    }

    #import .nav-tabs li a {
        height: 55px;
        outline: none;
        cursor: pointer;
        width: 240px;
    }

    #import .nav-tabs li a {
        background: #f9f9f9;
        border: 1px solid #e9e9e9;
        border-bottom: 1px solid #dddddd;
    }

    #import .nav-tabs .nav-link.active {
        background: #fff;
        border-bottom: 1px solid #fff;
    }

    #import .nav-tabs .nav-link.active, #import .nav-tabs .nav-link.active i, #import .nav-tabs .nav-item.show .nav-link {
        color: #000;
    }

    #import .nav-tabs li a span.number {
        width: 25px;
        height: 15px;
        line-height: 15px;
        float: left;
        margin-right: 10px;
        margin-top: 10px;
        padding: 0px 5px;
        color: #BCBCBC;
        font-size: 32px;
        font-weight: bold;
    }

    #import .nav-tabs li a span.tab-name {
        display: block;
        font-weight: bold;
        min-width: 180px;
        line-height: 20px;
    }

    #import .nav-tabs > li > a {
        color: #a5a5a5;
    }

    #import .nav-tabs li a i {
        display: block;
        font-size: 10px;
        font-style: normal;
        line-height: 10px;
        font-weight: normal;
    }
</style>

<div class="card style-1 mb-3">

    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="admin/import_export_tool"/>
    </div>

    <div class="card-body pt-3" id="import">
        <ul class="nav nav-tabs" id="myTab" role="tablist">

            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                   aria-selected="true">
                    <span class="number">1</span>
                    <span class="tab-name">Import setup</span>
                    <i>Main feed settings</i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                   aria-controls="profile" aria-selected="false">
                    <span class="number">2</span>
                    <span class="tab-name">Data Mapping</span>
                    <i>Assign tags to content data types</i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                   aria-controls="contact" aria-selected="false">
                    <span class="number">3</span>
                    <span class="tab-name">Import</span>
                    <i>Start importing</i>
                </a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <livewire:import_export_tool_view_import/>

            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <livewire:import_export_tool_html_dropdown_mapping_preview/>

            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

                <div class="shadow-none p-3 mb-5 bg-light rounded">No shadow</div>
                <div class="shadow-sm p-3 mb-5 bg-body rounded">Small shadow</div>
                <div class="shadow p-3 mb-5 bg-body rounded">Regular shadow</div>
                <div class="shadow-lg p-3 mb-5 bg-body rounded">Larger shadow</div>

            </div>
        </div>


    </div>
</div>
