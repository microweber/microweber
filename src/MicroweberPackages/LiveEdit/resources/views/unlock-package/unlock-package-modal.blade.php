<?php

/*

type: layout

name: Default

description: Default

*/

?>


@php

$packageName = $params['package_name'] ?? 'microweber-modules/white_label';

@endphp

<div class="mh-100vh unlock-package-wrapper row">
    <script>
        $(document).ready(function() {
            document.getElementById('js-unlock-package-save-license-<?php echo $params['id'];?>').addEventListener('click', function() {
                var licenseKey = document.getElementById('js-unlock-package-license-key-<?php echo $params['id'];?>').value;
                mw.top().app.license.save(licenseKey).then(function (response) {
                    if (response.data.success) {
                        mw.notification.success('License key saved');
                        mw.top().admin.admin_package_manager.install_composer_package_by_package_name('<?php echo $packageName; ?>');
                    } else if (response.data.warning) {
                        mw.notification.warning(response.data.warning);
                    } else {
                        mw.notification.warning('Invalid license key');
                    }
                });
            });

        });
    </script>

    <div class="col-md-8 unlock-package-columns">
       <div class="px-5">

            <div class="mb-3">
               <h1 class="font-weight-bold">Unlock The power of <span class="text-orange">BIG Template</span></h1>
               <h4>Use all layouts to make an awesome websites!</h4>
               <h4>Buy license key "Big Template" and unlock 300 more layouts,</h4>
               <h4>only for $59 per year or $169 lifetime license</h4>

                <a class="btn get-license-btn" target="_blank" href="https://microweber.org/go/market?prefix={{ $packageName }}">Get license key</a>
           </div>

            <form class="d-flex align-items-center gap-3">
                <input type="text" id="js-unlock-package-license-key-<?php echo $params['id'];?>" name="local_key" autocomplete="on" autofocus="true" class="form-control my-3" placeholder="License key">
                <div class="ms-md-3">
                    <button id="js-unlock-package-save-license-<?php echo $params['id'];?>" type="button" class="btn unlock-big-template-btn">Unlock</button>
                </div>
            </form>

            <p class="font-weight-bold mt-3">Have a problem with your White Label <br> license key?
                <a class="text-orange" target="_blank" href="https://microweber.org/go/feedback/" >Contact us.</a>
            </p>
       </div>
    </div>

    <div class="col-md-4 px-0 unlock-package-columns pt-0 mb-3" style="background-color: #f5f5f5;">
        <div class="unlock-package-right-side-img" style="background-image: url('{{ asset('vendor/microweber-packages/frontend-assets-libs/img/right-banner.jpg') }}')">
            <div class="ps-5" style="padding-top: 200px;">
                <h1 class="d-flex gap-2">
                    <span class="font-weight-bold">350+</span>
                    <span class="font-weight-normal">Layouts</span>
                </h1>
                <h2 class="d-flex gap-2">
                    <span class="font-weight-bold">20</span>
                    <span class="font-weight-normal">Categories</span>
                </h2>
                <h3 class="d-flex gap-2">
                    <span class="font-weight-bold">75</span>
                    <span class="font-weight-normal">Modules</span>
                </h3>
                <h4>Fresh Updates</h4>
                <h4>Live Support</h4>
                <h4>Theme & Layouts Customization</h4>
                <h4> And Much More ...</h4>
            </div>
        </div>
    </div>
</div>
