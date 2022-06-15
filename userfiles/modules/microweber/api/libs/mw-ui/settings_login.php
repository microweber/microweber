<?php include('partials/header.php'); ?>


    <div class="tree">
        DURVO
    </div>

    <script>
        $(document).ready(function () {
//        $('body > .main').addClass('show-sidebar-tree');
        });
    </script>

    <main>
        <div class="main-toolbar">
            <a href="#" class="btn btn-link text-silver px-0"><i class="mdi mdi-chevron-left"></i> Settings</a>
        </div>

        <div class="card bg-none style-1 mb-0">
            <div class="card-header px-0">
                <h5><i class="mdi mdi-login text-primary mr-3"></i> <strong>Login and register</strong></h5>
                <div>

                </div>
            </div>

            <div class="card-body pt-3 px-0">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="font-weight-bold">Register options</h5>
                        <small class="text-muted">Set your settings for proper login and register functionality.</small>
                    </div>
                    <div class="col-md-8">
                        <div class="card bg-light style-1 mb-3">
                            <div class="card-body pt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label class="control-label">Enable user registration</label>
                                            <small class="text-muted d-block mb-2">Do you allow users to register on your website? If you choose “yes”, they will do that with their email.</small>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-switch pl-0">
                                                <label class="d-inline-block mr-5" for="customSwitch1">No</label>
                                                <input type="checkbox" class="custom-control-input" id="customSwitch1" checked="">
                                                <label class="custom-control-label" for="customSwitch1">Yes</label>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="control-label">Registration approval required</label>
                                            <small class="text-muted d-block mb-2">Do you want the user to verify their account after registration? This way you will be sure that the user who has registered is a real person.</small>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-switch pl-0">
                                                <label class="d-inline-block mr-5" for="customSwitch1">No</label>
                                                <input type="checkbox" class="custom-control-input" id="customSwitch1" checked="">
                                                <label class="custom-control-label" for="customSwitch1">Yes</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-none style-1 mb-0">
            <div class="card-body pt-3 px-0">
                <hr class="thin mt-0 mb-5"/>

                <div class="row">
                    <div class="col-md-4">
                        <h5 class="font-weight-bold">Register form settings</h5>
                        <small class="text-muted">Customize your registration form in the best way by specifying the fields you want are required and you want Captcha certification.</small>
                    </div>
                    <div class="col-md-8">
                        <div class="card bg-light style-1 mb-3">
                            <div class="card-body pt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <a href="#" class="btn btn-link btn-sm py-1 px-0 float-right">View Register Form settings</a>
                                            <label class="control-label">Set the form fields</label>
                                            <small class="text-muted d-block mb-2">Use the checkbox to determine which visible fields are required for registration.</small>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                                <label class="custom-control-label" for="customCheck1">Show first name field?</label>
                                            </div>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                                <label class="custom-control-label" for="customCheck1">Show first name field?</label>
                                            </div>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                                <label class="custom-control-label" for="customCheck1">Show password confirmation field?</label>
                                            </div>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                                <label class="custom-control-label" for="customCheck1">Show newsletter subscription field?</label>
                                            </div>
                                        </div>

                                        <hr class="thin"/>

                                        <div class="form-group mb-3">
                                            <a href="#" class="btn btn-link btn-sm py-1 px-0 float-right">View Captcha module settings</a>
                                            <label class="control-label">Disable Captcha - Registration Form</label>
                                            <small class="text-muted d-block mb-2">Enable or Disable captcha code verification in the registration area.</small>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                                <label class="custom-control-label" for="customCheck1">Disable Captcha?</label>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="control-label">Disable registration with temporary email?</label>
                                            <small class="text-muted d-block mb-2">Users can register with temporary emails like - Mailinator, MailDrop, Guerrilla...</small>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                                <label class="custom-control-label" for="customCheck1">Disable registration with temporary email?</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-none style-1 mb-0">
            <div class="card-body pt-3 px-0">
                <hr class="thin mt-0 mb-5"/>

                <div class="row">
                    <div class="col-md-4">
                        <h5 class="font-weight-bold">Login form settings</h5>
                        <small class="text-muted">Customize your registration form in the best way by specifying the fields you want are required and you want Captcha certification</small>
                    </div>
                    <div class="col-md-8">
                        <div class="card bg-light style-1 mb-3">
                            <div class="card-body pt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <a href="#" class="btn btn-link btn-sm py-1 px-0 float-right">View Captcha module settings</a>
                                            <label class="control-label">Login form settings</label>
                                            <small class="text-muted d-block mb-2">Do I need a captcha foma for login each time?</small>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                                <label class="custom-control-label" for="customCheck1">Require Captcha to Login?</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-none style-1 mb-0">
            <div class="card-body pt-3 px-0">
                <hr class="thin mt-0 mb-5"/>

                <div class="row">
                    <div class="col-md-4">
                        <h5 class="font-weight-bold">Social Networks login</h5>
                        <small class="text-muted">Allow your users to register on your site, blog or store through their social media accounts.</small>
                    </div>
                    <div class="col-md-8">
                        <div class="card bg-light style-1 mb-3">
                            <div class="card-body pt-3">
                                <div class="row">
                                    <div class="col-12 socials-logins-settings">
                                        <div class="form-group mb-3">
                                            <label class="control-label">Enable user registration with socials accounts</label>
                                            <small class="text-muted d-block mb-2">Do you allow users to register on your website with their social media accounts. This will save time of the users to register.</small>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-switch pl-0">
                                                <label class="d-inline-block mr-5" for="users-social-newtworks-login">No</label>
                                                <input type="checkbox" class="custom-control-input" id="users-social-newtworks-login" data-bs-toggle="collapse" data-bs-target="#allow-users-social-newtworks-login">
                                                <label class="custom-control-label" for="users-social-newtworks-login">Yes</label>
                                            </div>
                                        </div>

                                         <div class="collapse-x" id="allow-users-social-newtworks-login">
                                            <div class="form-group mb-3">
                                                <label class="control-label mb-0">Allow Social Login with</label>
                                                <hr class="thin"/>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="custom-control-input" id="fb-login" data-bs-toggle="collapse" data-bs-target="#fb-login-settings">
                                                    <label class="custom-control-label mr-2 d-flex" for="fb-login"><i class="mdi mdi-facebook mdi-30px lh-1_0 mr-2"></i> Enable login with Facebook?</label>
                                                </div>
                                            </div>

                                            <div class="collapse" id="fb-login-settings">
                                                <small class="d-block mb-1">1. Api access <a href="https://developers.facebook.com/apps" target="_blank">https://developers.facebook.com/apps</a></small>
                                                <small class="d-block mb-1">2. In Website with Facebook Login please enter: <span class="text-muted">http://localhost/microweber/</span></small>
                                                <small class="d-block mb-1">3. If asked for callback url - use: <span class="text-muted">http://localhost/microweber/api/social_login_process?provider=facebook</span></small>

                                                <div class="form-group mt-3">
                                                    <label>App ID/API Key</label>
                                                    <input type="text" class="form-control" placeholder=""/>
                                                </div>

                                                <div class="form-group">
                                                    <label>App Secret</label>
                                                    <input type="text" class="form-control"/>
                                                </div>
                                            </div>

                                            <hr class="thin"/>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck2" data-bs-toggle="collapse" data-bs-target="#twitter-login-settings">
                                                    <label class="custom-control-label mr-2 d-flex" for="customCheck2"><i class="mdi mdi-twitter mdi-30px lh-1_0 mr-2"></i> Twitter login enabled?</label>
                                                </div>
                                            </div>

                                            <div class="collapse" id="twitter-login-settings">
                                                <small class="d-block mb-1">1. Register your application <a href="https://dev.twitter.com/apps" target="_blank">https://dev.twitter.com/apps</a></small>
                                                <small class="d-block mb-1">2. In Website enter: <span class="text-muted">http://localhost/microweber/</span></small>
                                                <small class="d-block mb-1">3. In Callback URL enter: <br/><span class="text-muted">http://localhost/microweber/api/social_login_process?provider=twitter</span></small>

                                                <div class="form-group mt-3">
                                                    <label>Consumer key</label>
                                                    <input type="text" class="form-control" placeholder=""/>
                                                </div>

                                                <div class="form-group">
                                                    <label>Consumer secret</label>
                                                    <input type="text" class="form-control"/>
                                                </div>
                                            </div>

                                            <hr class="thin"/>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1" data-bs-toggle="collapse" data-bs-target="#github-login-settings">
                                                    <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-github mdi-30px lh-1_0 mr-2"></i> Github login enabled?</label>
                                                </div>
                                            </div>

                                            <div class="collapse" id="github-login-settings">
                                                <small class="d-block mb-1">1. Register your application <a href="https://github.com/settings/applications/new" target="_blank">https://github.com/settings/applications/new</a></small>
                                                <small class="d-block mb-1">2. n Main URL enter: <span class="text-muted">http://localhost/microweber/</span></small>
                                                <small class="d-block mb-1">3. In Callback URL enter: <br/><span class="text-muted">http://localhost/microweber/api/social_login_process?provider=github</span></small>

                                                <div class="form-group mt-3">
                                                    <label>Client ID</label>
                                                    <input type="text" class="form-control" placeholder=""/>
                                                </div>

                                                <div class="form-group">
                                                    <label>Client secret</label>
                                                    <input type="text" class="form-control"/>
                                                </div>
                                            </div>

                                            <hr class="thin"/>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1" data-bs-toggle="collapse" data-bs-target="#linkedin-login-settings">
                                                    <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-linkedin mdi-30px lh-1_0 mr-2"></i> Linked In login enabled?</label>
                                                </div>
                                            </div>

                                            <div class="collapse" id="linkedin-login-settings">
                                                <small class="d-block mb-1">1. Register your application <a href="https://www.linkedin.com/secure/developer" target="_blank">https://www.linkedin.com/secure/developer</a></small>
                                                <small class="d-block mb-1">2. Website enter: <span class="text-muted">http://localhost/microweber/</span></small>
                                                <small class="d-block mb-1">3. In Callback URL enter: <br/><span class="text-muted">http://localhost/microweber/api/social_login_process?provider=linkedin</span></small>

                                                <div class="form-group mt-3">
                                                    <label>Client ID</label>
                                                    <input type="text" class="form-control" placeholder=""/>
                                                </div>

                                                <div class="form-group">
                                                    <label>Client secret</label>
                                                    <input type="text" class="form-control"/>
                                                </div>
                                            </div>

                                            <hr class="thin"/>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1" data-bs-toggle="collapse" data-bs-target="#google-login-settings">
                                                    <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-google mdi-30px lh-1_0 mr-2"></i> Google login enabled?</label>
                                                </div>
                                            </div>

                                            <div class="collapse" id="google-login-settings">
                                                <small class="d-block mb-1">1. Set your Api access <a href="https://code.google.com/apis/console/" target="_blank">https://code.google.com/apis/console/</a></small>
                                                <small class="d-block mb-1">2. In redirect URI please enter: <span class="text-muted">http://local.local/thirdparty/api/social_login_process?provider=google</span></small>

                                                <div class="form-group mt-3">
                                                    <label>Client ID</label>
                                                    <input type="text" class="form-control" placeholder=""/>
                                                </div>

                                                <div class="form-group">
                                                    <label>Client secret</label>
                                                    <input type="text" class="form-control"/>
                                                </div>
                                            </div>

                                            <hr class="thin"/>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1" data-bs-toggle="collapse" data-bs-target="#mw-login-settings">
                                                    <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-microweber mdi-30px lh-1_0 mr-2"></i> Microweber login enabled?</label>
                                                </div>
                                            </div>

                                            <div class="collapse" id="mw-login-settings">
                                                <small class="d-block mb-1">Please enter your credentials for Microweber Login Server</small>

                                                <div class="form-group mt-3">
                                                    <label>Server URL</label>
                                                    <input type="text" class="form-control" placeholder=""/>
                                                </div>

                                                <div class="form-group mt-3">
                                                    <label>Client ID</label>
                                                    <input type="text" class="form-control" placeholder=""/>
                                                </div>

                                                <div class="form-group">
                                                    <label>Client secret</label>
                                                    <input type="text" class="form-control"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-none style-1 mb-0">
            <div class="card-body pt-3 px-0">
                <hr class="thin mt-0 mb-5"/>

                <div class="row">
                    <div class="col-md-4">
                        <h5 class="font-weight-bold">Email notifications</h5>
                        <small class="text-muted">Register users can automatically receive an automatic email from you. See the settings and post your messages.</small>
                    </div>
                    <div class="col-md-8">
                        <div class="card bg-light style-1 mb-3">
                            <div class="card-body pt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label class="control-label">Send email for a user registration</label>
                                            <small class="text-muted d-block mb-2">Do you want users to receive an e-mail when registering?</small>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-switch pl-0">
                                                <label class="d-inline-block mr-5" for="customSwitch1">No</label>
                                                <input type="checkbox" class="custom-control-input" id="customSwitch1" checked="">
                                                <label class="custom-control-label" for="customSwitch1">Yes</label>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="control-label">Select email template</label>
                                            <small class="text-muted d-flex justify-content-between align-items-center mb-2">Choose template to send for registred users.
                                                <button class="btn btn-sm btn-outline-primary">Add new email template</button>
                                            </small>
                                            <small class="text-muted d-block mb-2">If you add few emails for same functionality they will be showing in dropdown box.</small>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-8">
                                                <input class="form-control" type="text" placeholder="" value="Forgot password" readonly/>
                                            </div>

                                            <div class="col-4 d-flex align-items-center">
                                                <button class="btn btn-sm btn-outline-success">Edit</button>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-8">
                                                <input class="form-control" type="text" placeholder="" value="New comment reply" readonly/>
                                            </div>

                                            <div class="col-4 d-flex align-items-center">
                                                <button class="btn btn-sm btn-outline-success">Edit</button>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-8">
                                                <input class="form-control" type="text" placeholder="" value="New order" readonly/>
                                            </div>

                                            <div class="col-4 d-flex align-items-center">
                                                <button class="btn btn-sm btn-outline-success">Edit</button>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-8">
                                                <input class="form-control" type="text" placeholder="" value="Sample" readonly/>
                                            </div>

                                            <div class="col-4 d-flex align-items-center">
                                                <button class="btn btn-sm btn-outline-success">Edit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-none style-1 mb-0">
            <div class="card-body pt-3 px-0">
                <hr class="thin mt-0 mb-5"/>

                <div class="row">
                    <div class="col-md-4">
                        <h5 class="font-weight-bold">Other settings</h5>
                        <small class="text-muted">Advanced setting where you can set different URL addresses.</small>
                    </div>
                    <div class="col-md-8">
                        <div class="card bg-light style-1 mb-3">
                            <div class="card-body pt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="control-label">Register URL</label>
                                            <small class="text-muted d-block mb-2">You can set a custom url for the register page</small>

                                            <input class="form-control" type="text" placeholder="" value="Use default"/>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Login URL</label>
                                            <small class="text-muted d-block mb-2">You can set a custom url for the login page</small>

                                            <input class="form-control" type="text" placeholder="" value="Use default"/>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Logout URL</label>
                                            <small class="text-muted d-block mb-2">You can set a custom url for the logout page</small>

                                            <input class="form-control" type="text" placeholder="" value="Use default"/>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Forgot password URL</label>
                                            <small class="text-muted d-block mb-2">You can set a custom url for the forgot password page</small>

                                            <input class="form-control" type="text" placeholder="" value="Use default"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row copyright mt-3">
            <div class="col-12">
                <p class="text-muted text-center small">Open-source website builder and CMS Microweber 2020. Version: 1.18</p>
            </div>
        </div>
    </main>


<?php include('partials/footer.php'); ?>
