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
        <h5><i class="mdi mdi-account-multiple text-primary mr-3"></i> <strong><?php _e("Manage Users"); ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3" id="users-manage-body">
        <form method="GET">
            <div class="align-items-center justify-content-between mt-2" id="mw-admin-manage-users-header">
                <div class="form-inline">
                    <div class="form-group">
                        <div class="input-group mb-0 prepend-transparent">
                            <div class="input-group-prepend bg-white">
                                <span class="input-group-text"><i class="mdi mdi-magnify mdi-20px"></i></span>
                            </div>
                            <input type="search" name="search" class="form-control" aria-label="Search" placeholder="<?php _e("Search for users"); ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary ml-2"><?php _e("Search"); ?></button>
                </div>
                <div>
                    <a href="#" class="btn btn-outline-primary icon-left btn-md js-show-filter" data-toggle="collapse"
                       data-target="#show-filter"><i class="mdi mdi-filter-outline"></i><?php _e('Filter'); ?></a>

                    <a href="<?php print admin_url('view:modules/load_module:users/edit-user:0'); ?>"
                       class="btn btn-primary" id="add-new-user-btn">
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

                <div class="collapse @if(isset($_GET['show_filter'])) show @endif" id="show-filter">
                    <div class="bg-primary-opacity-1 rounded px-3 py-2 mb-4">
                        <div class="row d-flex">
                            <div class="col-auto">
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
                                                    <input type="radio" id="sortby1" name="sortby"
                                                           class="custom-control-input mw_users_filter_show"
                                                           value="created_at desc" checked="checked">
                                                    <label class="custom-control-label"
                                                           for="sortby1"><?php _e("Date created"); ?></label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block mr-2">
                                                    <input type="radio" id="sortby2" name="sortby"
                                                           class="custom-control-input mw_users_filter_show"
                                                           value="last_login desc">
                                                    <label class="custom-control-label"
                                                           for="sortby2"><?php _e("Last login"); ?></label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block">
                                                    <input type="radio" id="sortby3" name="sortby"
                                                           class="custom-control-input mw_users_filter_show"
                                                           value="username asc">
                                                    <label class="custom-control-label"
                                                           for="sortby3"><?php _e("Username"); ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <button class="btn btn-outline-primary btn-sm" name="show_filter" value="1" type="submit">
                                        Submit filters
                                    </button>
                                </div> &nbsp;&nbsp;
                                <div class="text-center">
                                    <a href="{{route('admin.user.index')}}" class="btn btn-outline-primary btn-sm" type="button">
                                        Reset filters
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
        <style>
            .mw-admin-users-manage-table td,
            .mw-admin-users-manage-table td *{
                vertical-align: middle;
            }
        </style>

        <div class="table-responsive bg-white mw-admin-users-manage-table">
            <table class="table table-hover m-0" cellspacing="0" cellpadding="0">
                <tbody>

                @foreach($users as $user)

                <tr id="mw-admin-user-{{$user->id}}">
                    <td>
                        <div class="img-circle-holder img-absolute w-60">
                            <img src="{{$user->avatar}}">
                        </div>
                    </td>

                    <td>
                        <div class="text-outline-primary font-weight-bold">
                            {{$user->first_name}} {{$user->last_name}}
                            @if($user->is_admin)
                                <br><small class="text-dark"><?php _e('Admin');?></small>
                                @else
                                <br><small class="text-dark"><?php _e('User');?></small>
                            @endif
                        </div>
                    </td>

                    <td>
                        <small class="text-muted d-block"><?php _e('Username');?></small>
                        {{$user->username}}
                    </td>

                    <td>
                        <small class="text-muted d-block"><?php _e('Phone');?></small>
                        {{$user->phone}}
                    </td>

                    <td>
                        <small class="text-muted d-block"><?php _e('Email');?></small>
                        {{$user->email}}
                    </td>

                    <td>
                        <span class="mw-icon-check mw-registered" style="float: none"></span>
                    </td>

                    <td>

                        <a class="btn btn-outline-primary btn-sm"
                           href="{{admin_url()}}view:modules/load_module:users/edit-user:{{$user->id}}"><?php _e('Edit');?></a>
                    </td>
                </tr>


                @endforeach
                </tbody>
            </table>
        </div>


        <div class="d-flex">
            <div class="mx-auto">
                <?php echo $users->links("pagination::bootstrap-4"); ?>
            </div>
        </div>

    </div>
</div>

