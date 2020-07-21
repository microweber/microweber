@extends('admin::_layouts.app')

@section('title', trans_choice('admin.role', 1))

@section('content')
    <!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <admin::button-dropdown :name="trans('admin.batch')">
                        <slot name="links">
                            @can('delete_role')
                                <admin::ajax class="dropdown-item" :url="route('admin.roles.destroy', 0)" method="delete" :confirm="trans('admin.delete_message')" selected="ids" :text="trans('admin.delete')" />
                            @endcan
                        </slot>
                    </admin::button-dropdown>

                    @can('add_role')
                        <div class="btn-group">
                            <a class="btn btn-sm btn-success" href="{{ Admin::action('create') }}"><i
                                        class="fa fa-plus f-s-12"></i> {{ trans('admin.add_role') }}</a>
                        </div>
                    @endcan

                    <div class="card-tools">
                        <form id="search" action="{{ Admin::action('index') }}">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="search" class="form-control float-right"
                                       value="{{ request('search') }}"
                                       placeholder="{{ trans('admin.search_name') }}...">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <admin::table checkbox="true">
                    <slot name="thead">
                        <tr>
                            <th>{{ trans('admin.name') }}</th>
                            <th>{{ trans('admin.guard') }}</th>
                            <th>{{ trans('admin.created_at') }}</th>
                            <th>{{ trans('admin.updated_at') }}</th>
                            <th>{{ trans('admin.operating') }}</th>
                        </tr>
                    </slot>
                    <slot name="tbody">
                        @foreach($results as $role)
                            <tr @if($role->name != 'superadmin') data-id="{{ $role->id }}" @endif>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->guard_name }}</td>
                                <td>{{ $role->created_at }}</td>
                                <td>{{ $role->updated_at }}</td>
                                <td>
                                    @can('edit_role')
                                        <a href="{{ Admin::action('edit', $role->id) }}">{{ trans('admin.edit') }}</a>
                                        &nbsp;
                                    @endcan
                                    @can('delete_role')
                                        @if($role->name !='superadmin')
                                                <admin::ajax :url="route('admin.roles.destroy', $role->id)" method="delete" :confirm="trans('admin.delete_message')" :text="trans('admin.delete')" />
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </slot>
                    </admin::table>
                </div>
                <div class="card-footer clearfix">
                    <admin::page :results="$results"/>
                </div>
            </div>
        </div>
        <!-- end panel -->
    </div>
@endsection