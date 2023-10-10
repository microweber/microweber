<div class="col-xxl-10 col-md-11 col-12 px-md-0 px-2 mx-auto">


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
    <h1 class="main-pages-title"> <?php _e("Manage Users"); ?></h1>

    <div class="card mb-3" x-data="{showFilters: false}">

        <div class="card-body">
            <div class="row" id="users-manage-body">
                <div class="flex-wrap align-items-center justify-content-between mt-2" id="mw-admin-manage-users-header">

                   <div class="col-lg-4 col-12">
                       <div class="input-icon mb-3">
                           <input type="search" wire:model.debounce.500ms="keyword" class="form-control" aria-label="Search" placeholder="{{ _e("Search for users") }}">

                           <span class="input-icon-addon">
                          <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                        </span>
                       </div>
                   </div>

                    <div class="col-lg-8 col-12 text-lg-end">

                        @php
                            $editUserLink = route('admin.users.edit', user_id());
                        @endphp
                        <a href="{{ $editUserLink }}"
                           class="btn btn-outline-secondary mx-1 btn-sm" id="add-edit-user-btn">
                            <?php _e("Edit profile"); ?>
                        </a>

                        <button type="button" class="btn btn-outline-primary mx-1 btn-sm" @click="showFilters = ! showFilters">
                            <?php _e("Show Filters"); ?>
                        </button>
                        <a href="{{route('admin.users.create')}}"
                           class="btn btn-primary mx-1 btn-sm" id="add-new-user-btn">
                            <?php _e("Add new user"); ?>
                        </a>

                    </div>
                </div>
                <div x-show="showFilters">
                    <div class="col mb-3">
                        <label class="d-block mb-2"><?php _e('Sort By');?></label>
                        <div class="d-flex align-items-center">
                            <select wire:model="orderBy" class="form-select me-2">
                                <option value="id"><?php _e('Id');?></option>
                                <option value="username"><?php _e('Username');?></option>
                                <option value="email"><?php _e('Email');?></option>
                                <option value="is_active"><?php _e('Is active');?></option>
                                <option value="last_login"><?php _e('Last login');?></option>
                                <option value="created_at"><?php _e('Created at');?></option>
                                <option value="updated_at"><?php _e('Updated at');?></option>
                            </select>
                            <select wire:model="orderDirection" class="form-select ms-2">
                                <option value="asc"><?php _e('Ascending');?></option>
                                <option value="desc"><?php _e('Descending');?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col mb-3">
                        <label class="d-block mb-2"><?php _e('Role');?></label>
                        <select wire:model="role" class="form-select">
                            <option value=""><?php _e('All');?></option>
                            <option value="admin"><?php _e('Admin');?></option>
                            <option value="user"><?php _e('User');?></option>
                        </select>
                    </div>
                </div>

                <style>
                    .mw-admin-users-manage-table td,
                    .mw-admin-users-manage-table td *{
                        vertical-align: middle;
                    }
                </style>

                <div class="card my-4">
                    @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table mw-admin-users-manage-table">
                            <thead>
                            <tr>
                                <th><?php _e('Name');?> </th>
                                <th><?php _e('Username');?> </th>
                                <th><?php _e('Phone');?> </th>
                                <th><?php _e('Role');?> </th>
                                <th class="w-1"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)

                                <tr id="mw-admin-user-{{$user->id}}">
                                    <td>
                                        <div class="d-flex py-1 align-items-center">

                                            @if(empty($user->thumbnail))
                                            {{--   Ako nqma avatar --}}
                                            <span class="avatar admin-users-no-user-img-wrapper me-2 border-0 rounded-circle bg-light">
                                                <img width="24px" height="24px" src="{{$user->avatar}}" alt="">
                                            </span>
                                            @else
                                            {{--   Ako ima--}}
                                            <span class="avatar me-2 shadow-none">
                                                <img class="rounded-circle" src="{{$user->avatarUrl()}}" alt="">
                                            </span>
                                            @endif

                                            <div class="flex-fill d-flex">
                                                <div ><a class="tblr-body-color" href="{{route('admin.users.edit', $user->id)}}">{{$user->email}}</a></div>
                                                <div class="font-weight-medium ms-2">{{$user->first_name}} {{$user->last_name}}

                                                    @if($user->id == user_id())
                                                        <span class="badge badge-outline text-primary fs-6">{{_e('you')}}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{$user->username}}
                                    </td>
                                    <td>
                                        {{$user->phone}}
                                    </td>
                                    <td class="text-muted">
                                        @if($user->is_admin)
                                            <small class=" "><?php _e('Admin');?></small>
                                        @else
                                            <small class=" "><?php _e('User');?></small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $editUserLink = route('admin.users.edit', $user->id);
                                            if (user_id() == $user->id) {
                                                $editUserLink = admin_url('user/profile');
                                            }
                                        @endphp
                                        <a href="{{$editUserLink}}"><?php _e('Edit');?></a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    @else
                        {{_e('No such user in the records')}}
                    @endif
                </div>

                <div class="d-flex">
                    <div class="mx-auto">
                        {{ $users->links() }}
                    </div>
                </div>

                <div class="text-right my-3">
                    @if ($users->count() > 0)
                        <a href="{{$exportUrl}}" class="btn btn-outline-success">
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
