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

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title"><i class="mdi mdi-shield-edit-outline text-primary mr-3"></i> <strong>Privacy policy</strong></h5>
                <div>

                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="font-weight-bold settings-title-inside">Privacy policy settings</h5>
                        <small class="text-muted d-block mb-3">A Privacy Policy is a legal agreement
                            that explains what kinds of personal
                            information you gather from website
                            visitors, how you use this information,
                            and how you keep it safe. Examples of
                            personal information might include:
                            Names. Dates of birth.
                        </small>

                        <small class="text-muted d-block">The General Data Protection Regulation
                            (EU) 2016/679 (GDPR) is a regulation in
                            EU law on data protection and privacy in
                            the European Union (EU) and the
                            European Economic Area (EEA).
                            It also addresses the transfer of personal
                            data outside the EU and EEA areas.
                        </small>
                    </div>

                    <div class="col-md-8">
                        <div class="card bg-azure-lt ">
                            <div class=" ">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Users must agree to the Terms and Conditions</label>
                                            <small class="text-muted d-block mb-2">Should your users agree to the terms of use of the website</small>
                                        </div>

                                        <div class="form-group mb-3">
                                            <div class="form-check form-switch pl-0">
                                                <label class="d-inline-block mr-5" for="customSwitch1">No</label>
                                                <input type="checkbox" class="form-check-input" id="customSwitch1" checked="">
                                                <label class="custom-control-label" for="customSwitch1">Yes</label>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">User consent text</label>
                                            <small class="text-muted d-block mb-2">You can download a variety of templates online to help you figure out how to format your policy. If you're not sure if you've included all the required information, you might check with a lawyer or IT security specialist.</small>
                                            <textarea class="form-control"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">URL of terms and conditions</label>
                                            <small class="text-muted d-block mb-2">Уou need to create this page and type in the address field.</small>

                                            <input class="form-control" type="text" placeholder="Ex. http://yourwebsite.com/privacy-policy"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">


                <div class="row">
                    <div class="col-md-4">
                        <h5 class="font-weight-bold settings-title-inside">Contact form settings</h5>
                        <small class="text-muted">Make settings for your contact form (there may be more than one) related to the conditions for sending data and using the website.</small>
                    </div>
                    <div class="col-md-8">
                        <div class="card bg-azure-lt ">
                            <div class=" ">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Users must agree to the terms and conditions</label>
                                            <small class="text-muted d-block mb-2">If the user does not agree to the terms, he will not be able to use the contact form</small>
                                        </div>

                                        <div class="form-group mb-3">
                                            <div class="form-check form-switch pl-0">
                                                <label class="d-inline-block mr-5" for="customSwitch1">No</label>
                                                <input type="checkbox" class="form-check-input" id="customSwitch1" checked="">
                                                <label class="custom-control-label" for="customSwitch1">Yes</label>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">Saving data and emails</label>
                                            <small class="text-muted d-block mb-2">Will you save the information from the emails in your database on the website?</small>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-checkbox my-2">
                                                <input type="checkbox" class="form-check-input" id="customCheck1" checked="">
                                                <label class="custom-control-label" for="customCheck1">Skip saving emails in my website database.</label>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">Want to view and edit the text and the page?</label>
                                            <button class="btn btn-sm btn-outline-primary mt-2" data-bs-toggle="collapse" data-bs-target="#contact-form-settings">Edit the text and URL</button>
                                        </div>

                                        <div class="collapse" id="contact-form-settings">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Checkbox describe</label>
                                                <small class="text-muted d-block mb-2">The text will uppear to the user</small>

                                                <input class="form-control" type="text" placeholder="I agree with the..."/>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label class="form-label">URL of terms and conditions</label>
                                                <small class="text-muted d-block mb-2">Уou need to create this page and type in the address field.</small>

                                                <input class="form-control" type="text" placeholder="Ex. http://yourwebsite.com/privacy-policy"/>
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

        <div class="card mb-4">
            <div class="card-body">


                <div class="row">
                    <div class="col-md-4">
                        <h5 class="font-weight-bold settings-title-inside">Comments form settings</h5>
                        <small class="text-muted">Make settings for the comment form. Are there any rules they must agree to when posting a comment?</small>
                    </div>

                    <div class="col-md-8">
                        <div class="card bg-azure-lt ">
                            <div class=" ">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Users must agree to the terms and conditions</label>
                                            <small class="text-muted d-block mb-2">If the user does not agree to the terms, he will not be able to use the contact form</small>
                                        </div>

                                        <div class="form-group mb-3">
                                            <div class="form-check form-switch pl-0">
                                                <label class="d-inline-block mr-5" for="customSwitch1">No</label>
                                                <input type="checkbox" class="form-check-input" id="customSwitch1" checked="">
                                                <label class="custom-control-label" for="customSwitch1">Yes</label>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">Saving data and emails</label>
                                            <small class="text-muted d-block mb-2">Will you save the information from the emails in your database on the website?</small>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-checkbox my-2">
                                                <input type="checkbox" class="form-check-input" id="customCheck1" checked="">
                                                <label class="custom-control-label" for="customCheck1">Skip saving emails in my website database.</label>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">Want to view and edit the text and the page?</label>
                                            <button class="btn btn-sm btn-outline-primary mt-2" data-bs-toggle="collapse" data-bs-target="#comments-form-settings">Edit the text and URL</button>
                                        </div>

                                        <div class="collapse" id="comments-form-settings">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Checkbox describe</label>
                                                <small class="text-muted d-block mb-2">The text will uppear to the user</small>

                                                <input class="form-control" type="text" placeholder="I agree with the..."/>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label class="form-label">URL of terms and conditions</label>
                                                <small class="text-muted d-block mb-2">Уou need to create this page and type in the address field.</small>

                                                <input class="form-control" type="text" placeholder="Ex. http://yourwebsite.com/privacy-policy"/>
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

        <div class="card mb-4">
            <div class="card-body">


                <div class="row">
                    <div class="col-md-4">
                        <h5 class="font-weight-bold settings-title-inside">Newsletter settings</h5>
                        <small class="text-muted">Make settings for your contact form (there may be more than one) related to the conditions for sending data and using the website.</small>
                    </div>
                    <div class="col-md-8">
                        <div class="card bg-azure-lt ">
                            <div class=" ">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Users must agree to the terms and conditions</label>
                                            <small class="text-muted d-block mb-2">If the user does not agree to the terms, he will not be able to use the contact form</small>
                                        </div>

                                        <div class="form-group mb-3">
                                            <div class="form-check form-switch pl-0">
                                                <label class="d-inline-block mr-5" for="customSwitch1">No</label>
                                                <input type="checkbox" class="form-check-input" id="customSwitch1" checked="">
                                                <label class="custom-control-label" for="customSwitch1">Yes</label>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">Saving data and emails</label>
                                            <small class="text-muted d-block mb-2">Will you save the information from the emails in your database on the website?</small>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="custom-control custom-checkbox my-2">
                                                <input type="checkbox" class="form-check-input" id="customCheck1" checked="">
                                                <label class="custom-control-label" for="customCheck1">Skip saving emails in my website database.</label>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">Want to view and edit the text and the page?</label>
                                            <button class="btn btn-sm btn-outline-primary mt-2" data-bs-toggle="collapse" data-bs-target="#newsletter-settings">Edit the text and URL</button>
                                        </div>

                                        <div class="collapse" id="newsletter-settings">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Checkbox describe</label>
                                                <small class="text-muted d-block mb-2">The text will uppear to the user</small>

                                                <input class="form-control" type="text" placeholder="I agree with the..."/>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label class="form-label">URL of terms and conditions</label>
                                                <small class="text-muted d-block mb-2">Уou need to create this page and type in the address field.</small>

                                                <input class="form-control" type="text" placeholder="Ex. http://yourwebsite.com/privacy-policy"/>
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


        <div class="row copyright mt-3">
            <div class="col-12">
                <p class="text-muted text-center small">Open-source website builder and CMS Microweber 2020. Version: 1.18</p>
            </div>
        </div>
    </main>


<?php include('partials/footer.php'); ?>
