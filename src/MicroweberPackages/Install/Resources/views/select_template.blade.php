@extends('app::public.layout')

@section('content')

    <script>
        function showMarketplaceItemsInstallScreen() {
            var html = '<div class="mw-flex-row">';
            $.post("<?php print site_url() ?>?get_market_templates_for_install_screen=1", function (data) {
                $.each(data, function (index, value) {
                    if (value.name && value.description) {
                        var is_default = false;
                        var screenshot = '';
                        if (value.is_default && value.is_default == 1) {
                            var is_default = true;
                        }
                        if (value.screenshot) {
                            var screenshot = value.screenshot;
                        }
                        if (value.latest_version && value.latest_version.extra && value.latest_version.extra._meta && value.latest_version.extra._meta.screenshot) {
                            var screenshot = value.latest_version.extra._meta.screenshot;
                        }

                        html += '<div class="m-b-20  m-l-10   mw-flex-col-md-5" style="padding:10px;"><div style="width: 100%; height: 120px; background-image: url(' + screenshot + '); background-size: cover; background-position: top center;"></div><br /><button type="button" class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline" style="width: 100%;"  data-screenshot="' + screenshot + '" onclick="installMarketplaceItemByPackageName(' + '\'' + value.name + '\'' + ')">Install ' + value.description + '</button><br /></div>';
                    }
                });
                html += '</div>';
                $("#templates-from-marketplace").html(html);
            });
        }
        showMarketplaceItemsInstallScreen();
    </script>

    <div class="row my-5 d-flex align-items-center ">
        <div class="col-12 col-sm-12 col-md-7 col-lg-12 col-xl-8 mx-auto">

            <div class="card">
                <div class="card-body py-4">

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif


                    <div id="templates-from-marketplace"></div>


                </div>
            </div>

        </div>
    </div>

@endsection
