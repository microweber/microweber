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


                <div class="template-preview" style="height:450px;background-image: url('{{$template['screenshot_link']}}');"></div>


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
                            } else {
                                $('.validate-license-message').html('<span class="text-danger">License is invalid!</span>');
                            }
                        }
                    });
                });
            </script>

            <div id="template-install-box">
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
            </div>

        @endif


    </div>

</div>
