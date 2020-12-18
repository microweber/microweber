@extends('invoice::admin.layout')

@section('title', 'Tax types')

@section('content')

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Precent</th>
            <th scope="col">Description</th>
            <th scope="col" colspan="2">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($taxTypes as $taxType):
        <tr>
            <th scope="row">{{ $taxType->id }}</th>
            <td>{{ $taxType->name }}</td>
            <td>{{ $taxType->percent }}%</td>
            <td>{{ $taxType->description }}</td>
            <td>{{ $taxType->id }}</td>
            <td>
                <a href="{{ route('tax-types.edit',$taxType->id)}}" class="btn btn-primary">Edit</a>
            </td>
            <td>
                <form action="{{ route('tax-types.destroy', $taxType->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

@endsection