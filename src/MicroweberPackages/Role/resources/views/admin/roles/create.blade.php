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

    <div class="mb-3">
        <h5 class="font-weight-bold">Content and Pages</h5>
        <small class="text-muted">The user can operate with the content of the website like edit pages, categories, posts, tags.Please check below what are the avaliable operations that user can do.</small>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="bg-white p-3">
                    <div class="table-responsive">
                        <table class="table table-permissions">
                            <thead>
                            <tr>
                                <th scope="col">
                                    <h6 class="font-weight-bold"><i class="mdi mdi-text mdi-18px mr-2 text-primary"></i> Add and edit content and pages</h6>
                                </th>
                                <th scope="col"></th>
                                <th class="text-center" scope="col">View</th>
                                <th class="text-center" scope="col">Create</th>
                                <th class="text-center" scope="col">Edit</th>
                                <th class="text-center" scope="col">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row" rowspan="100">
                                    <small class="text-muted d-block">Click on the checkbox to alow the users action. User can edit?</small>
                                    <a href="#" class="btn btn-link px-0">Check tutorial how to set a role</a>
                                </th>
                                <td><strong>Pages</strong></td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td><strong>Posts</strong></td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td><strong>Posts</strong></td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td><strong>Posts</strong></td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td><strong>Posts</strong></td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td><strong>Posts</strong></td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br/>
    <br/>
    <br/>
    <br/>
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