
<script>
    mw.lib.require('bootstrap4');
</script>
        <div class="container-fluid">
            <div class="block-header">
                <h2>Add New Role</h2>
            </div>

            <!-- Vertical Layout -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Add New Role
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
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
                    </div>
                </div>
            </div>
            <!-- #END# Vertical Layout -->
           
        </div>