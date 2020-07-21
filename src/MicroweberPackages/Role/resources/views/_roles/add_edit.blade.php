@extends('admin::_layouts.app')

@section('title', trans_choice('admin.'.($model->id?'edit_role':'add_role'), 1))

<admin::bread-middle :middle="['url' => Admin::action('index'), 'name' => trans('admin.role')]" />

@section('content')
    <admin::form :model="$model">
    <!-- begin row -->
        <div class="row">
            <!-- begin col-12 -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">{{ trans('admin.role') }}</h3></div>
                    <div class="card-body">
                        <div class="form-group row {{ $errors->has('name')?"has-error":"" }}">
                            <admin::label :text="trans('admin.name')" required="true" class="col-md-4 text-right" />
                            <div class="col-md-8">
                                <admin::input name="name" :value="old('name', $model->name)" :readonly="$model->name=='superadmin'" />
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('guard')?"has-error":"" }}">
                            <admin::label :text="trans('admin.guard')" required="true" class="col-md-4 text-right" />
                            <div class="col-md-8">
                                <admin::select name="guard" :selected="old('guard', $model->guard_name)" :results="$guards" toName="true" search="false" />
                            </div>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <button type="submit" class="float-right btn btn-primary"
                                style="width: 120px">{{ trans('admin.save') }}</button>
                    </div>
                </div>
            </div>
            <!-- end panel -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans_choice('admin.permission', 1) }}</h3>
                        <div class="card-tools">
                            <form id="search" action="http://admin6.test/admin/permissions">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="search" class="form-control float-right" value="" placeholder="{{ trans('admin.search_name') }}">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0 data-list">

                    </div>
                    @foreach($guards as $guard)
                        <template class="{{ $guard }}">
                            <table class="table text-nowrap table-borderless permissions no-data">
                                <thead>
                                <th width="80"><input type="checkbox"
                                                      class="grid-select-all checkbox-style"></th>
                                <th>{{ trans_choice('admin.permission', 0) }}</th>
                                <th>{{ trans('admin.guard') }}</th>
                                </thead>
                                <tbody>
                                @isset($permissions_group[$guard])
                                    @foreach($permissions_group[$guard] as $permission)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="permissions[]"
                                                       {{ in_array($permission->id, $current_permissions)?'checked':'' }} class="grid-row-checkbox checkbox-style"
                                                       value="{{ $permission->id }}">
                                            </td>
                                            <td>{{ \Illuminate\Support\Str::after(trans("{$permission->guard_name}.{$permission->name}"), '.') }}</td>
                                            <td>{{ $permission->guard_name }}</td>
                                        </tr>
                                    @endforeach
                                @endisset
                                </tbody>
                            </table>
                        </template>
                    @endforeach
                </div>
            </div>
        </div>
    </admin::form>
    <!-- end row -->

    <!-- end #content -->
    <script>
        Admin.boot(function () {
            var table;

            $('[name="search"]').keyup(function () {
                if ($(this).prop('comStart')) return;    // 中文输入过程中不截断

                NProgress.start();
                table.search($('input[name="search"]').val()).draw();
                NProgress.done();
            }).on('compositionstart', function () {
                $(this).prop('comStart', true);
            }).on('compositionend', function () {
                $(this).prop('comStart', false);
            });

            $('[name="guard"]').change(function () {
                var name = $(this).val();
                //console.log($('template.' + name).html());
                $('.data-list').html($('template.' + name).html());
                table = $('.data-list>table').DataTable({searching: true, dom: 'Brtip',
                    buttons: [
                    'print'
                ]});
                $('.checkbox-style').iCheck({
                    checkboxClass: 'icheckbox_flat-red',
                    increaseArea: '10%' // optional
                });
                Admin.icheckEvent();
            });
            $('[name="guard"]').trigger('change');
        });
    </script>
@endsection
