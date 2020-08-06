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

    <script>
        $(document).ready(function () {
            function checkForCheckedBoxes() {
                $('.js-check-for-checked').each(function (index) {
                    var checked = false;
                    $(this).find('input[type="checkbox"]').each(function (index) {
                        if ($(this).is(':checked')) {
                            checked = true;
                        }
                    });

                    if (checked == true) {
                        $(this).find('td').addClass('bg-primary-opacity-1');
                    }else{
                        $(this).find('td').removeClass('bg-primary-opacity-1');
                    }
                });
            }

            $('body').on('click', '.js-check-for-checked input[type="checkbox"]', function () {
                checkForCheckedBoxes();
            });

            checkForCheckedBoxes();
        });
    </script>

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
                <small class="text-muted">
                    The user can operate with the content of the website like edit pages, categories, posts, tags.
                    Please check below what are the avaliable operations that user can do.
                </small>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="bg-white p-3">
                            <div class="table-responsive">
                                <table class="table table-permissions">
                                    <thead>
                                    <tr>
                                        <th scope="col" colspan="2">
                                            <h6 class="font-weight-bold mb-0"><i class="mdi mdi-text mdi-18px mr-2 text-primary"></i> Add and edit {{$permissionGroupName}}</h6>
                                        </th>
                                        <th class="text-center" scope="col">View</th>
                                        <th class="text-center" scope="col">Create</th>
                                        <th class="text-center" scope="col">Edit</th>
                                        <th class="text-center" scope="col">Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($permissionGroup as $key=>$permission)
                                        @php
                                            $permissionHash = md5($permission['name'])
                                        @endphp
                                        <tr class="js-check-for-checked">
                                            <?php if($key == 0): ?>
                                            <th scope="row" rowspan="100" class="row-desc">
                                                <small class="text-muted d-block">Click on the checkbox to allow the users can {{strtolower($permissionGroupName)}} actions?</small>
                                                <a href="#" class="btn btn-link px-0">Check tutorial how to set a role</a>
                                            </th>
                                            <?php endif; ?>
                                            <td class="row-module-name">
                                                <img src="{{$permission['icon']}}" class="module-img"/>
                                                {{$permission['name']}}
                                            </td>
                                            <td class="text-center">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1_{{$permissionHash}}">
                                                    <label class="custom-control-label" for="customCheck1_{{$permissionHash}}"></label>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck2_{{$permissionHash}}">
                                                    <label class="custom-control-label" for="customCheck2_{{$permissionHash}}"></label>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck3_{{$permissionHash}}">
                                                    <label class="custom-control-label" for="customCheck3_{{$permissionHash}}"></label>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck4_{{$permissionHash}}">
                                                    <label class="custom-control-label" for="customCheck4_{{$permissionHash}}"></label>
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
        <button class="btn btn-outline-danger" type="reset"><i class="mdi mdi-cancel"></i> Cancel</button>
        <button class="btn btn-outline-success float-right waves-effect" type="submit"><i class="mdi mdi-content-save"></i> Save</button>
    </form>
@endsection