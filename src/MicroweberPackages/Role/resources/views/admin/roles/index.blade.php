@extends('invoice::admin.layout')

@section('title', 'Roles')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }} <br/>
            @endforeach
        </div><br/>
    @endif

    <div class="col-md-12 text-right">
         <a href="{{route('roles.create')}}" class="btn btn-outline-primary mb-3"><i class="mdi mdi-account-settings"></i> Add New Role</a>
         <a href="{{route('permissions.create')}}" class="btn btn-outline-primary mb-3"><i class="mdi mdi-security"></i> Add New Permissions</a>
    </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Permissions</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->name }}</td>
                        <td>
                            @foreach($row->permissions()->pluck('name') as $permission)
                                {{ $permission }},
                            @endforeach
                        </td>
                        <td>
                            <a href="{{route('roles.edit',$row->id)}}" class="btn btn-warning waves-effect">Edit</a>
                        </td>
                        <td>
                            <form id="delete_form" method="POST" action="{{ route('roles.destroy',$row->id) }}">
                                {{ csrf_field() }}
                                <input name="_method" type="hidden" value="DELETE">
                                <button class="btn btn-danger waves-effect" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection