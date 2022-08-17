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
        <div class="card bg-none style-1 mb-0">
            <div class="card-header px-0">
                <h5><i class="mdi mdi-signal-cellular-3 text-primary mr-3"></i> <strong>General</strong></h5>
                <div>

                </div>
            </div>

            <div class="card-body pt-3 px-0">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="font-weight-bold">Seo Settings</h5>
                        <small class="text-muted">Fill in the fields for maximum results when finding your website in search engines.</small>
                    </div>
                    <div class="col-md-8">
                        <div class="card bg-light style-1 mb-3">
                            <div class="card-body pt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-4">
                                            <label class="control-label">Website Name</label>
                                            <small class="text-muted d-block mb-2">This is very important for search engines. Your website will be categorized by many criteria and its name is one of them.</small>
                                            <input class="form-control" value="Microweber"/>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="control-label">Website Description</label>
                                            <small class="text-muted d-block mb-2">Describe what your website is about.</small>
                                            <textarea class="form-control"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Website Keywords</label>
                                            <small class="text-muted d-block mb-2">Ex.: Cat, Videos of Cats, Funny Cats, Cat Pictures, Cat for Sale, Cat Products and Food</small>
                                            <input class="form-control"/>
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
                        <h5 class="font-weight-bold">General Settings</h5>
                        <small class="text-muted">Set regional settings for your website or online store. They will also affect the language you use and the fees for the orders.</small>
                    </div>
                    <div class="col-md-8">
                        <div class="card bg-light style-1 mb-3">
                            <div class="card-body pt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-4">
                                            <label class="control-label">Date format</label>
                                            <small class="text-muted d-block mb-2">Choose a date format for your website</small>
                                            <select class="selectpicker" data-width="100%">
                                                <option>2020-05-20 11:28:27 (Y-m-d H:i:s)</option>
                                                <option>2020-05-20 11:28:27 (Y-m-d H:i:s)</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Time zone</label>
                                            <small class="text-muted d-block mb-2">Set a time zone</small>
                                            <select class="selectpicker" data-width="100%">
                                                <option>UTC</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Change Favicon</label>
                                            <small class="text-muted d-block mb-2">Select an icon for your website. It is best to be part of your logo.</small>
                                            <div class="d-flex">
                                                <div class="img-circle-holder img-absolute border-radius-0 border-silver mr-3">
                                                    <img src="assets/img/no-image.jpg">
                                                </div>

                                                <button type="button" class="btn btn-outline-primary">Upload favicon</button>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Posts per Page</label>
                                            <small class="text-muted d-block mb-2">Select how many posts or products you want to be shown per page?</small>
                                            <input class="form-control" value="10"/>
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
                        <h5 class="font-weight-bold">Social Networks links</h5>
                        <small class="text-muted">Add links to your social media accounts. Once set up, you can use them anywhere on your site using the "social networks" module with drag and drop technology.</small>
                    </div>
                    <div class="col-md-8">
                        <div class="card bg-light style-1 mb-3">
                            <div class="card-body pt-3">
                                <div class="row">
                                    <div class="col-12 socials-settings">
                                        <div class="form-group mb-4">
                                            <label class="control-label">Select and type socials links you want to show</label>
                                            <small class="text-muted d-block mb-2">Select the social networks you want to see on your site, blog and store</small>
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-facebook mdi-20px lh-1_0 mr-2"></i> facebook.com/</label>
                                                <input class="form-control" value="Microweber"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-twitter mdi-20px lh-1_0 mr-2"></i> twitter.com/</label>
                                                <input class="form-control" value=""/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-pinterest mdi-20px lh-1_0 mr-2"></i> pinterest.com/</label>
                                                <input class="form-control" value=""/>
                                            </div>
                                        </div>

                                        <a href="javascript:;" class="btn btn-outline-primary btn-sm mb-3" data-bs-toggle="collapse" data-bs-target="#more-socials-settings" aria-expanded="true">Show more</a>

                                        <div class="collapse" id="more-socials-settings">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                    <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-youtube mdi-20px lh-1_0 mr-2"></i> youtube.com/</label>
                                                    <input class="form-control" value=""/>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                    <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-linkedin mdi-20px lh-1_0 mr-2"></i> linkedin.com/</label>
                                                    <input class="form-control" value=""/>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                    <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-github mdi-20px lh-1_0 mr-2"></i> github.com/</label>
                                                    <input class="form-control" value=""/>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                    <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-instagram mdi-20px lh-1_0 mr-2"></i> instagram.com/</label>
                                                    <input class="form-control" value=""/>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                    <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-soundcloud mdi-20px lh-1_0 mr-2"></i> soundcloud.com/</label>
                                                    <input class="form-control" value=""/>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                    <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-mixdcloud mdi-20px lh-1_0 mr-2"></i> mixdcloud.com/</label>
                                                    <input class="form-control" value=""/>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                    <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-medium mdi-20px lh-1_0 mr-2"></i> medium.com/</label>
                                                    <input class="form-control" value=""/>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox d-flex align-items-center">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                    <label class="custom-control-label mr-2 d-flex" for="customCheck1"><i class="mdi mdi-rss mdi-20px lh-1_0 mr-2"></i> RSS</label>
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

        <div class="row copyright mt-3">
            <div class="col-12">
                <p class="text-muted text-center small">Open-source website builder and CMS Microweber 2020. Version: 1.18</p>
            </div>
        </div>
    </main>


<?php include('partials/footer.php'); ?>
