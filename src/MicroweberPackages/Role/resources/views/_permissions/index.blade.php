@extends('admin::_layouts.app')

@section('title', trans_choice('admin.permission', 0))

@section('content')
    <!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-md-12">
            <div class="card">
                <!-- /.card-header -->
                <div class="card-header">
                    <admin::button-dropdown :name="trans('admin.batch')">
                        <slot name="links">
                            @can('delete_role')
                                <admin::ajax class="dropdown-item" :url="route('admin.permissions.destroy', 0)"
                                             method="delete" :confirm="trans('admin.delete_message')" selected="ids"
                                             :text="trans('admin.delete')"/>
                            @endcan
                        </slot>
                    </admin::button-dropdown>

                    @can('add_permission')
                        <div class="btn-group">
                            <a class="btn btn-sm btn-success" href="{{ Admin::action('create') }}"><i
                                        class="fa fa-plus f-s-12"></i> {{ trans('admin.add_permission') }}</a>
                        </div>
                    @endcan

                    <div class="card-tools">
                        <form id="search" action="{{ Admin::action('index') }}">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="search" class="form-control float-right"
                                       value="{{ request('search') }}"
                                       placeholder="{{ trans('admin.search') }}...">

                                {{--<div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>--}}
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <admin::table checkbox="true" nodata="true">
                        <slot name="thead">
                            <tr>
                                <th>{{ trans_choice('admin.permission', 1) }}</th>
                                <th>{{ trans('admin.name') }}</th>
                                <th>{{ trans('admin.guard') }}</th>
                                <th>{{ trans('admin.updated_at') }}</th>
                                <th>{{ trans('admin.operating') }}</th>
                            </tr>
                        </slot>
                        <slot name="tbody">
                            @foreach($results as $permission)
                                <tr @if(!($permission->name == 'dashboard' && $permission->guard_name == 'admin')) data-id="{{ $permission->id }}" @endif>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ \Illuminate\Support\Str::after(trans('admin.'.$permission->name), 'admin.') }}</td>
                                    <td>{{ $permission->guard_name }}</td>
                                    <td>{{ $permission->updated_at }}</td>
                                    <td>
                                        @if(!($permission->name == 'dashboard' && $permission->guard_name == 'admin'))
                                            @can('edit_permission')
                                                @if(true || auth()->user()->can($permission->name))
                                                    <a href="{{ Admin::action('edit', $permission->id) }}">{{ trans('admin.edit') }}</a>
                                                @endif
                                                &nbsp;
                                            @endcan
                                            @can('delete_permission')
                                                @if(true || auth()->user()->can($permission->name))
                                                    <admin::ajax
                                                            :url="route('admin.permissions.destroy', $permission->id)"
                                                            method="delete" :confirm="trans('admin.delete_message')"
                                                            :text="trans('admin.delete')"/>
                                                @endif
                                            @endcan
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </slot>
                    </admin::table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- end panel -->
    </div>

    <script>
        Admin.boot(function () {
            var table = $('.table').DataTable({
                searching: true,
                dom: 'Brtip',
                buttons: [
                    'print'
                ]
            });

            $('.checkbox-style').iCheck({
                checkboxClass: 'icheckbox_flat-red',
                increaseArea: '10%' // optional
            });
            Admin.icheckEvent();

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
        });
    </script>
@endsection