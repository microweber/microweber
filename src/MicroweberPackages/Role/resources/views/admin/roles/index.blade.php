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
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover dataTable js-exportable">
        <thead>
            <tr>
                <th style="width: 10%;">Id</th>
                <th style="width:90%;">Name</th>
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
                    <a href="{{route('roles.edit',$row->id)}}" class="btn btn-outline-primary btn-sm"><i class="mdi mdi-pencil"></i> Edit</a>
                </td>
                <td>
                    <form id="delete_form" method="POST" action="{{ route('roles.destroy',$row->id) }}">
                        {{ csrf_field() }}
                        <input name="_method" type="hidden" value="DELETE">
                        <button class="btn btn-outline-danger btn-sm" type="submit"><i class="mdi mdi-trash-can-outline"></i> Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection