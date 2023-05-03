<div>


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
    <div class="card bg-azure-lt mb-3">
        <div class="card-header">
            <h5 class="card-title"><i class="mdi mdi-account-multiple text-primary mr-3"></i> <strong><?php _e("Manage Users"); ?></strong>
            </h5>
        </div>

        <div class="card-body">
            <div id="users-manage-body">

                <div class="align-items-center justify-content-between mt-2" id="mw-admin-manage-users-header">
                    <div class="form-inline">
                        <div class="form-group">
                            <div class="input-group mb-0 prepend-transparent">
                                <div class="input-group-prepend  ">
                                    <span class="input-group-text"><i class="mdi mdi-magnify mdi-20px"></i></span>
                                </div>
                                <input type="search" wire:model.debounce.500ms="keyword" class="form-control" aria-label="Search" placeholder="{{ _e("Search for users") }}">

                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="{{route('admin.users.create')}}"
                           class="btn btn-primary" id="add-new-user-btn">
                            <i class="mdi mdi-account-plus mr-2"></i> <?php _e("Add user"); ?>
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-3">
                        <label class="d-block mb-2">Sort By</label>
                        <div class="d-flex">
                            <select class="form-select mr-3">
                                <option value="id">Id</option>
                                <option value="username">Username</option>
                                <option value="email">Email</option>
                                <option value="is_active">Is active</option>
                                <option value="last_login">Last login</option>
                                <option value="created_at">Created at</option>
                                <option value="updated_at">Updated at</option>
                            </select>
                            <select class="form-select">
                                <option value="asc">Ascending</option>
                                <option value="desc">Descending</option>
                            </select>
                        </div>
                    </div>
                    <div class="col mb-3">
                        <label class="d-block mb-2">Role</label>
                        <select class="form-select">
                            <option value="">-</option>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                </div>

                <style>
                    .mw-admin-users-manage-table td,
                    .mw-admin-users-manage-table td *{
                        vertical-align: middle;
                    }
                </style>

                <div class="table-responsive  mw-admin-users-manage-table">
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
                                    {{$user->id}}
                                </td>

                                <td>
                                    <div class="settings-info-holder-title">
                                        {{$user->first_name}} {{$user->last_name}}
                                        @if($user->is_admin)
                                            <br><small class=" "><?php _e('Admin');?></small>
                                        @else
                                            <br><small class=" "><?php _e('User');?></small>
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

                                    <a class="btn btn-outline-primary btn-sm" href="{{route('admin.users.edit', $user->id)}}"><?php _e('Edit');?></a>
                                </td>
                            </tr>


                        @endforeach
                        </tbody>
                    </table>
                </div>


                <div class="d-flex">
                    <div class="mx-auto">
                        {{ $users->links() }}
                    </div>
                </div>

                <div class="text-right mt-3">
                    @if ($users->count() > 0)
                        <a href="{{$exportUrl}}" class="btn btn-outline-success btn-sm">
                            <i class="mdi mdi-download"></i>
                            @if(isset($_GET['showFilter']))
                                <?php _e("Export"); ?> {{$users->count()}} @if($users->count()==1) <?php _e("user"); ?> @else <?php _e("users"); ?> @endif
                            @else
                                <?php _e("Export all"); ?>
                            @endif
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
