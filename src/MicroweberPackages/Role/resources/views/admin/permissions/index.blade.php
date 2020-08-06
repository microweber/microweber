
@extends('invoice::admin.layout')

@section('title', 'Permission')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }} <br/>
            @endforeach
        </div><br/>
    @endif


    <div class="col-md-12">
<a href="{{route('permissions.create')}}" class="btn btn-success">Add New Permission</a>
<a href="{{route('roles.index')}}" class="btn btn-success">Roles</a>
</div>

    <div class="col-md-12 mt-5">
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($permissions as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->name }}</td>
                <td>
                    <a href="{{route('permissions.edit',$row->id)}}" class="btn btn-warning waves-effect">Edit</a>
                </td>
                <td>
                    <form id="delete_form" method="POST" action="{{ route('permissions.destroy',$row->id) }}">
                        {{ csrf_field() }}
                        <input name="_method" type="hidden" value="DELETE">
                        <button class="btn btn-danger waves-effect" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div></div>
    @endsection