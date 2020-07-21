
<script>
    mw.lib.require('bootstrap4');
</script>

<div class="container" style="margin-top: 30px">

            <!-- Vertical Layout -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Edit Permission
                            </h2>
                        </div>
                        <div class="body">
                           <form id="form_validation" method="POST" action="{{ route('users.update',$user->id) }}">
                            {{ csrf_field() }}
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">person</i>
                                    </span>
                                    <div class="form-line {{ $errors->has('name') ? ' error' : '' }}">
                                        <input name="_method" type="hidden" value="PUT">
                                        <input type="text" class="form-control" name="name" value="{{$user->name}}" placeholder="Username" required autofocus>
                                    </div>
                                    @if ($errors->has('email'))
                                        <label id="name-error" class="error" for="name">{{ $errors->first('name') }}</label>
                                    @endif
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                    <i class="material-icons">email</i>
                                    </span>
                                    <div class="form-line {{ $errors->has('email') ? ' error' : '' }}">
                                        <input type="text" class="form-control" name="email" value="{{$user->email}}" placeholder="Email Address" required autofocus>
                                    </div>
                                    @if ($errors->has('email'))
                                        <label id="email-error" class="error" for="email">{{ $errors->first('email') }}</label>
                                    @endif
                                </div>                               
                                <div class="form-group form-float">
                                    <label class="form-label">Roles</label>
                                    <select class="form-control show-tick" name="roles[]" id="you" multiple required>
                                        <optgroup label="Roles" data-max-options="2">
                                            @foreach($roles as $role)
                                                <option>{{ $role }}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                     @if ($errors->has('roles'))
                                        <label id="name-error" class="error" for="email">{{ $errors->first('roles') }}</label>
                                    @endif
                                </div>
                                <button class="btn btn-primary waves-effect" type="submit">UPDATE</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Vertical Layout -->
           
        </div>