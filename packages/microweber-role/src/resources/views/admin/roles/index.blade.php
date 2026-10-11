@extends('admin::layouts.app')

@section('title', 'Manage roles')

@section('icon')
    <i class="mdi mdi-format-list-checks module-icon-svg-fill"></i>
@endsection

@section('content')
    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }} <br/>
            @endforeach
        </div><br/>
    @endif

    <div class="row d-flex justify-content-end align-items-end mb-4">
        <div class="col-md-6">
            <h5 class="font-weight-bold settings-title-inside">User roles list and permitions</h5>
            <small class="text-muted">List of your user roles below</small>
        </div>

        <div class="col-md-6 text-end text-right">
            <a href="{{route('admin.role.create')}}" class="btn btn-success btn-sm"><i class="mdi mdi-book-account"></i> Add New role</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover dataTable js-exportable table-permissions">
            <thead>
            <tr>
                <th style="width:200px;"><?php _e('Role name'); ?></th>
                <th class="text-center"><?php _e('Users'); ?></th>
                <th style="width:300px;" class="text-center"><?php _e('Actions'); ?></th>
            </tr>
            </thead>
           {{-- <tfoot>
            <tr>
                <th style="width: 50px;">ID</th>
                <th style="width:200px;"><?php _e('Role name'); ?></th>
                <th class="text-center"><?php _e('Users'); ?></th>
                <th style="width:300px;" class="text-center"><?php _e('Actions'); ?></th>
            </tr>
            </tfoot>--}}
            <tbody>
            @foreach($roles as $row)
                <tr>
                    <td>{{ $row->name }}</td>
                    <td class="text-center">{{ $row->users->count() }}</td>
                    <td style="width:300px;" class="text-center">
                        <a href="{{route('roles.edit',$row->id)}}" class="btn btn btn-sm btn-outline-primary btn-link-to-bordered">Edit</a>

                        <form method="post" action="{{ route('roles.clone') }}" class="d-inline">
                            <input type="hidden" value="{{ $row->id }}" name="id">
                            <button type="submit" class="btn btn btn-sm btn-outline-primary btn-link-to-bordered">Duplicate</button>
                        </form>

                        <form id="delete_form" method="POST" action="{{ route('roles.destroy',$row->id) }}" class="d-inline">
                            {{ csrf_field() }}
                            <input name="_method" type="hidden" value="DELETE">
                            <button class="btn btn-link btn-sm text-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
