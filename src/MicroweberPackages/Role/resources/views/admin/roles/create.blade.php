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

    <style>
        .table-permissions {

        }
    </style>

    <div class="col-md-12">
    <form id="form_validation" method="POST" action="{{ route('roles.store') }}">
        {{ csrf_field() }}
        <div class="form-group form-float">
            <div class="form-line">
                <label class="form-label">Role Name</label>
                <input type="text" class="form-control" name="name" value="{{old('name')}}" required>
            </div>
            @if ($errors->has('name'))
                <label id="name-error" class="error" for="email">{{ $errors->first('name') }}</label>
            @endif
        </div>

        @foreach($permissionGroups as $permissionGroupName=>$permissionGroup)
    <div class="mb-3 mt-4">
        <h5 class="font-weight-bold" style="text-transform: capitalize;">{{$permissionGroupName}}</h5>
        <small class="text-muted">The user can operate with the content of the website like edit pages, categories, posts, tags.Please check below what are the avaliable operations that user can do.</small>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="bg-white p-3">
                    <div class="table-responsive">
                        <table class="table table-permissions">
                            <thead>
                            <tr>
                                <th scope="col">
                                    <h6 class="font-weight-bold"><i class="mdi mdi-text mdi-18px mr-2 text-primary"></i> Add and edit {{$permissionGroupName}}</h6>
                                </th>
                                <th scope="col"></th>
                                <th class="text-center" scope="col">View</th>
                                <th class="text-center" scope="col">Create</th>
                                <th class="text-center" scope="col">Edit</th>
                                <th class="text-center" scope="col">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($permissionGroup as $key=>$permission)
                            <tr>
                                <?php if($key == 0): ?>
                                <th scope="row" rowspan="100">
                                    <small class="text-muted d-block">Click on the checkbox to alow the users action. User can edit?</small>
                                    <a href="#" class="btn btn-link px-0">Check tutorial how to set a role</a>
                                </th>
                                    <?php endif; ?>
                                <td>
                                    <img src="{{$permission['icon']}}" style="width:18px;margin-right:4px;" />
                                    <strong>{{$permission['name']}}</strong>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1_{{$key}}">
                                        <label class="custom-control-label" for="customCheck1_{{$key}}"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck2_{{$key}}"">
                                        <label class="custom-control-label" for="customCheck2_{{$key}}"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck3_{{$key}}">
                                        <label class="custom-control-label" for="customCheck3_{{$key}}"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck4_{{$key}}">
                                        <label class="custom-control-label" for="customCheck4_{{$key}}"></label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
        @endforeach

          {{--  <div class="form-group form-float">
                <label class="form-label">Permission</label>
                <select class="form-control show-tick" name="permission[]" multiple required>
                    <optgroup label="Permission" data-max-options="2">

                    </optgroup>
                </select>
                @if ($errors->has('permission'))
                    <label id="name-error" class="error" for="email">{{ $errors->first('permission') }}</label>
                @endif
            </div>--}}
            <button class="btn btn-outline-default waves-effect" type="reset"><i class="mdi mdi-cancel"></i> Cancel</button>
            <button class="btn btn-outline-success float-right waves-effect" type="submit"><i class="mdi mdi-content-save"></i> Save</button>
        </form>
    </div>
@endsection