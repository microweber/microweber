<module type="admin/modules/info" history_back="true"/>


<style>
    #mw-admin-manage-users-header {
        display: flex;
    }

    #users-manage-body {
        position: relative;
    }

    #users-manage-body .mw-spinner {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        border-radius: 50px;
    }
</style>



<div class="card style-1 bg-light mb-3">
    <div class="card-header">
        <h5><i class="mdi mdi-account-multiple text-primary mr-3"></i> <strong><?php _e("Manage Users"); ?></strong></h5>
    </div>

    <div class="card-body pt-3" id="users-manage-body">
        <div class="align-items-center justify-content-between mt-2" id="mw-admin-manage-users-header">
            <div>
                <script>
                    handleUserSearch = function (e) {
                        e.preventDefault();
                        var target = e.target.querySelector('[type="search"]');
                        mw.url.windowHashParam('search', target.value.trim());
                    }
                </script>
                <form class="form-inline" onsubmit="handleUserSearch(event)">
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
                <a href="#" class="btn btn-outline-primary icon-left btn-md js-show-filter" data-toggle="collapse" data-target="#show-filter"><i class="mdi mdi-filter-outline"></i><?php _e('Filter'); ?></a>

                <a href="<?php print admin_url('view:modules/load_module:users/edit-user:0'); ?>" class="btn btn-primary" id="add-new-user-btn">
                    <i class="mdi mdi-account-plus mr-2"></i> <?php _e("Add user"); ?>
                </a>
            </div>
        </div>

        <div class="manage-items mt-4" id="sort-users">
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

            <div class="collapse" id="show-filter">
                <div class="bg-primary-opacity-1 rounded px-3 py-2 mb-4">
                    <div class="row d-flex">
                        <div class="col-auto">
                            <div class="row d-flex justify-content-between">
                                <div class="col-md-4">
                                    <div>
                                        <label class="d-block mb-2"><?php _e("Role"); ?></label>
                                        <script>
                                            handleUserSortByRoles = function (e) {
                                                e.preventDefault();
                                                var val = e.target.value;
                                                if (val === '-1') {
                                                    mw.url.windowDeleteHashParam('is_admin');
                                                } else {
                                                    mw.url.windowHashParam('is_admin', val);
                                                }
                                            }
                                        </script>

                                        <select class="selectpicker" data-style="btn-md" onchange="handleUserSortByRoles(event)">
                                            <option value="-1"><?php _e("All"); ?></option>
                                            <option value="0"><?php _e("User"); ?></option>
                                            <option value="1"><?php _e("Admin"); ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div>
                                        <label class="d-block mb-2"><?php _e('Status'); ?></label>
                                        <script>
                                            handleUserSortByActiveState = function (e) {
                                                e.preventDefault();
                                                var val = e.target.value;
                                                if (val === '-1') {
                                                    mw.url.windowDeleteHashParam('is_active');
                                                } else {
                                                    mw.url.windowHashParam('is_active', val);
                                                }
                                            }
                                            function resetFilters() {
                                                mw.url.windowDeleteHashParam('is_active')
                                                mw.url.windowDeleteHashParam('is_admin')
                                                mw.url.windowDeleteHashParam('sortby')
                                                $('#show-filter .selectpicker').selectpicker('val', -1);
                                                mw.$('#sortby1')[0].checked = true
                                            }
                                        </script>

                                        <select class="selectpicker" data-style="btn-md" onchange="handleUserSortByActiveState(event)">
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
                                                <input type="radio" id="sortby1" name="sortby" class="custom-control-input mw_users_filter_show" value="created_at desc" checked="checked" onchange="mw.url.windowHashParam('sortby', this.value)">
                                                <label class="custom-control-label" for="sortby1"><?php _e("Date created"); ?></label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline-block mr-2">
                                                <input type="radio" id="sortby2" name="sortby" class="custom-control-input mw_users_filter_show" value="last_login desc" onchange="mw.url.windowHashParam('sortby', this.value)">
                                                <label class="custom-control-label" for="sortby2"><?php _e("Last login"); ?></label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline-block">
                                                <input type="radio" id="sortby3" name="sortby" class="custom-control-input mw_users_filter_show" value="username asc" onchange="mw.url.windowHashParam('sortby', this.value)">
                                                <label class="custom-control-label" for="sortby3"><?php _e("Username"); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col d-flex align-items-center justify-content-center">
                            <div class="text-center">
                                <button onclick="resetFilters()" class="btn btn-outline-primary btn-sm" type="button">Reset filters</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

