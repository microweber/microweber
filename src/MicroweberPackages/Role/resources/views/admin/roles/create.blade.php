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
        <a href="{{route('roles.index')}}" class="btn btn-outline-primary mb-3"><i
                    class="mdi mdi-account-arrow-left"></i> Back to Roles</a>
    </div>

    <div class="col-md-12">
        <form id="form_validation" method="POST" action="{{ route('roles.store') }}">
            {{ csrf_field() }}
            <div class="form-group form-float">
                <div class="form-line">
                    <input type="text" class="form-control" name="name" value="{{old('name')}}" required>
                    <label class="form-label">Name</label>
                </div>
                @if ($errors->has('name'))
                    <label id="name-error" class="error" for="email">{{ $errors->first('name') }}</label>
                @endif
            </div>
            <div class="form-group form-float">
                <label class="form-label">Permission</label>
                <select class="form-control show-tick" name="permission[]" multiple required>
                    <optgroup label="Permission" data-max-options="2">
                        @foreach($permissions as $permission)
                            <option>{{ $permission }}</option>
                        @endforeach
                    </optgroup>
                </select>
                @if ($errors->has('permission'))
                    <label id="name-error" class="error" for="email">{{ $errors->first('permission') }}</label>
                @endif
            </div>
            <button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
        </form>
    </div>
@endsection