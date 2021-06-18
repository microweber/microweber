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

        <div class="card bg-light style-1 mb-3" style="max-width: 600px; margin: 0 auto;">
            <div class="card-header">
                <h5><i class="mdi mdi-login text-primary mr-3"></i> <strong>Mail Template</strong></h5>
                <div>

                </div>
            </div>

            <div class="card-body pt-3">
                <h5 class="mb-3">Edit mail template</h5>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-4">
                            <label class="control-label">Template Name</label>
                            <small class="text-muted d-block mb-2">Name the email template so you can recognize it more easily</small>
                            <input type="text" class="form-control" placeholder="New user registration (default)"/>
                        </div>

                        <div class="form-group mb-3">
                            <label class="control-label">Is this mail template Active?</label>
                            <small class="text-muted d-block mb-2">Тurn off or turn on auto-reply for this email template</small>
                        </div>

                        <div class="form-group mb-4">
                            <div class="custom-control custom-switch pl-0">
                                <label class="d-inline-block mr-5" for="customSwitch1">No</label>
                                <input type="checkbox" class="custom-control-input" id="customSwitch1" checked="">
                                <label class="custom-control-label" for="customSwitch1">Yes</label>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="control-label">Template type</label>
                            <small class="text-muted d-block mb-2">Name the email template so you can recognize it more easily</small>
                            <div>
                                <select class="selectpicker" data-width="100%">
                                    <option>new_user_register</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="thin"/>

                <h5 class="mb-3">Sendind the email</h5>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-4">
                            <label class="control-label">From Name</label>
                            <small class="text-muted d-block mb-2">On what behalf will the e-mail be sent?</small>
                            <input type="text" class="form-control" placeholder="Ex. personal or company name"/>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="control-label">From Email</label>
                            <small class="text-muted d-block mb-2">From which email it will be sent?</small>
                            <input type="text" class="form-control" placeholder="Ex. your@mail.com"/>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="control-label">Copy To</label>
                            <small class="text-muted d-block mb-2">To which email should you send a copy?</small>
                            <input type="text" class="form-control" placeholder="Ex. your@mail.com"/>
                        </div>
                    </div>
                </div>
                <hr class="thin"/>

                <h5 class="mb-3">Message</h5>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-4">
                            <label class="control-label">Subject</label>
                            <small class="text-muted d-block mb-2">Subject of your email</small>
                            <input type="text" class="form-control" placeholder=""/>
                        </div>

                        <div class="form-group">
                            <div class="input-group mb-3 append-transparent">
                                <input type="text" class="form-control form-control-sm" value="https://www.microweber.com/file?v=OpaOpiFp9Hw">
                                <div class="input-group-append">
                                    <span class="input-group-text py-0 px-2"><a href="#" class="text-danger">X</a></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group mb-3 append-transparent">
                                <input type="text" class="form-control form-control-sm" value="https://www.microweber.com/file?v=OpaOpiFp9Hw">
                                <div class="input-group-append">
                                    <span class="input-group-text py-0 px-2"><a href="#" class="text-danger">X</a></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="control-label">E-mail attachments</label>
                            <small class="text-muted d-block mb-2">You can attach a file to the automatic email</small>
                            <a href="#" class="btn btn-sm btn-outline-primary">Add new email template</a>
                        </div>

                        <div class="form-group mb-4">
                            <textarea class="form-control">Dear {{username}}</textarea>
                        </div>
                    </div>
                </div>
                <hr class="thin"/>

            </div>
        </div>

        <div class="row copyright mt-3">
            <div class="col-12">
                <p class="text-muted text-center small">Open-source website builder and CMS Microweber 2020. Version: 1.18</p>
            </div>
        </div>
    </main>


<?php include('partials/footer.php'); ?>
