<div class="row">


    <div class="col-md-12">
        <h5>{{$template['description']}}</h5>

        @if($template['is_paid'])
            <span class="badge badge-sm badge-success">PREMIUM LICENSE</span>
        @else
            <span class="badge badge-sm badge-primary">FREE LICENSE</span>
        @endif

    </div>

    <div class="col-md-6">


        <div class="template-preview" style="height:350px;background-image: url('{{$template['screenshot_link']}}');"></div>


        <hr />
        <small>v{{$template['version']}}</small>
    </div>

    <div class="col-md-6">

        @if ($template['dist']['type'] == 'license_key')

            <script>
                $("#license-key-save").submit(function(e) {
                    e.preventDefault();
                    $('.validate-license-message').html('<span class="text-success">Validating your license...</div>');
                    formData = $(this).serialize();
                    $.ajax({
                        type: "POST",
                        url: mw.settings.site_url,
                        data: formData,
                        success: function(data){
                            if (data.validated) {

                                $('.validate-license-message').html('<span class="text-success">License is valid!</span>');

                                mw.dialog.get().remove();
                                installTemplate('{{$template['name']}}');

                            } else {
                                $('.validate-license-message').html('<span class="text-danger">License is invalid!</span>');
                            }
                        }
                    });
                });
            </script>

            <form id="license-key-save">
                <div class="form-group">
                    <label for="licenseKey">License key</label>
                    <input type="text" class="form-control" name="license_key" id="licenseKey" aria-describedby="licenseKeyHelp" placeholder="Enter license key">
                    <small id="licenseKeyHelp" class="form-text text-muted">Enter your license key to unlock this template</small>
                </div>
                <input type="hidden" name="license_rel_type" value="{{$template['name']}}">
                <input type="hidden" name="save_license" value="1">
                <button type="submit" class="btn btn-primary">Validate & Install</button>
                 <br />
                 <br />
                <div class="validate-license-message"></div>
                <br />
                <br />

                <a href="{{$template['buy_link']}}" target="_blank">I dont have a license key?</a>
            </form>
        @else

            <script>
                function downloadTemplate(name, btnInstance) {
                    $(btnInstance).attr('disabled', 'disabled');
                    $(btnInstance).html('Downloading your template...');

                    setTimeout(function () {
                        $(btnInstance).html('This can take some time...');
                    }, 8000);

                    setTimeout(function () {
                        $(btnInstance).html('Still downloading...');
                    }, 14000);

                    setTimeout(function () {
                        $(btnInstance).html('Sit back and relax.. one more seconds');
                    }, 20000);

                    $.ajax({
                        url: mw.settings.site_url + '?download_template=' + name,
                        type: "GET",
                        success: function (json) {

                            $(btnInstance).removeAttr('disabled');

                            if (json.status == 'success') {

                                $(btnInstance).html('Done!');

                                setTimeout(function () {
                                    $(btnInstance).html('Redirecting to install page...');
                                }, 2000);

                                setTimeout(function () {
                                    window.location.href = "<?php echo site_url();?>?request_template={{$template['target-dir']}}";
                                }, 3000);

                            } else {
                                $(btnInstance).html('Failed downloading');
                            }
                        }
                    });
                }
            </script>

            <h4>Download template {{$template['description']}}</h4>
            <p>Click the button bellow to download and use this template</p>
            <br />
            <button type="button" class="btn btn-primary" onclick="downloadTemplate('{{$template['name']}}', this)">
                Start download
            </button>
            <small>v{{$template['version']}}</small>

        @endif


    </div>

</div>
