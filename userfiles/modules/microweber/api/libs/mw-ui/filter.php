<?php include('partials/header.php'); ?>
    <main>
        <div class="card style-1 mb-3">
            <div class="card-header">
                <h5><i class="mdi mdi-signal-cellular-3 text-primary mr-3"></i> <strong>Statistics</strong></h5>
                <div>

                </div>
            </div>

            <div class="card-body">
                <script>
                    $(document).ready(function () {
                        $('.js-show-filter').on('click', function () {
                            $(this).toggleClass('active');
                            if ($(this).hasClass('active')) {
                                $(this).find('i').removeClass('mdi-filter-outline').addClass('mdi-close-thick');
                            } else {
                                $(this).find('i').removeClass('mdi-close-thick').addClass('mdi-filter-outline');
                            }
                        });
                    });
                </script>


                <div class="d-flex align-items-center justify-content-between mt-3">
                    <div>
                        <form class="form-inline">
                            <div class="form-group">
                                <div class="input-group mb-0 prepend-transparent">
                                    <div class="input-group-prepend bg-white">
                                        <span class="input-group-text"><i class="mdi mdi-magnify mdi-20px"></i></span>
                                    </div>
                                    <input type="search" class="form-control" aria-label="Search" placeholder="<?php _e("Search for users"); ?>">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-outline-primary ml-2"><?php _e("Search"); ?></button>
                        </form>
                    </div>

                    <div>
                        <a href="#" class="btn btn-outline-primary icon-left btn-md js-show-filter" data-bs-toggle="collapse" data-bs-target="#show-filter"><i class="mdi mdi-filter-outline"></i> Filter</a>

                        <a href="#edit-user=0" class="btn btn-success" id="add-new-user-btn">
                            <i class="mdi mdi-plus icon-left"></i> <?php _e("Add new"); ?>
                        </a>
                    </div>
                </div>

                <div class="collapse" id="show-filter">
                    <div class="bg-primary-opacity-1 rounded px-3 py-2 mb-4 mt-3">
                        <div class="row d-flex justify-content-between">
                            <div class="col-md-4">
                                <div>
                                    <label class="d-block mb-2"><?php _e("Role"); ?></label>

                                    <select class="selectpicker" data-style="btn-md">
                                        <option value="-1"><?php _e("All"); ?></option>
                                        <option value="0"><?php _e("User"); ?></option>
                                        <option value="1"><?php _e("Admin"); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div>
                                    <label class="d-block mb-2"><?php _e('Status'); ?></label>


                                    <select class="selectpicker" data-style="btn-md">
                                        <option value="-1"><?php _e("All users"); ?></option>
                                        <option value="1"><?php _e("Active users"); ?></option>
                                        <option value="0"><?php _e("Disabled users"); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div>
                                    <label class="d-block mb-2"><?php _e('Sort by'); ?></label>

                                    <div class="form-group mb-0">
                                        <div class="custom-control custom-radio d-inline-block mr-2">
                                            <input type="radio" id="sortby1" name="sortby" class="custom-control-input mw_users_filter_show" value="created_at desc" checked="checked">
                                            <label class="custom-control-label" for="sortby1"><?php _e("Date created"); ?></label>
                                        </div>
                                        <div class="custom-control custom-radio d-inline-block mr-2">
                                            <input type="radio" id="sortby2" name="sortby" class="custom-control-input mw_users_filter_show" value="last_login desc">
                                            <label class="custom-control-label" for="sortby2"><?php _e("Last login"); ?></label>
                                        </div>
                                        <div class="custom-control custom-radio d-inline-block">
                                            <input type="radio" id="sortby3" name="sortby" class="custom-control-input mw_users_filter_show" value="username asc">
                                            <label class="custom-control-label" for="sortby3"><?php _e("Username"); ?></label>
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
