@extends('admin::_layouts.app')

@section('title', trans_choice('admin.'.($model->id?'edit_user':'add_user'), 1))

<admin::bread-middle :middle="['url' => Admin::action('index'), 'name' => trans_choice('admin.user', 1)]"/>

@section('content')
    <admin::form :model="$model">
        <!-- begin row -->
        <div class="row">
            <!-- begin col-12 -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @foreach($user_name_fileds as $key => $filed)
                            <div class="form-group row">
                                <admin::label {{--:required="$key==0"--}} required class="col-md-3 text-right"
                                              :text="trans('admin.'.$filed)"/>
                                <div class="col-md-6">
                                    <admin::input :name="$filed" :value="old($filed, $model->{$filed})"
                                                  :readonly="$model->name=='superadmin'"/>
                                </div>
                            </div>
                        @endforeach

                        <div class="form-group row {{ $errors->has('metas.avatar')?"has-error":"" }}">
                            <admin::label class="col-md-3 text-right" :text="trans('admin.avatar')"/>
                            <div class="col-md-6">
                                <admin::input-file name="metas[avatar]"
                                                   :value="old('metas.avatar', $model->avatar)"/>
                            </div>
                        </div>
                        @can('edit_role')
                            <?php old('role')?>
                            <div class="form-group row {{ $errors->has('role')?"has-error":"" }}">
                                <admin::label class="col-md-3" :text="trans_choice('admin.role', 1)"/>
                                <div class="col-md-6">
                                    <admin::select name="role[]" multiple="true">
                                        @foreach($roles as $role)
                                            <option {{ $model->roles->contains(function ($value, $key)use($role) {
    return $value->name == $role->name && $value->guard_name==$role->guard_name;
})?'selected':'' }} value="{{ $role->name.':'.$role->guard_name }}">{{ $role->guard_name.':'.$role->name }}</option>
                                        @endforeach
                                    </admin::select>
                                </div>
                            </div>
                        @endcan
                        @isset($password)
                            <div class="form-group row {{ $errors->has('password')?"has-error":"" }}">
                                <admin::label class="col-md-3" :text="trans_choice('admin.password', 1)"/>
                                <div class="col-sm-6 input-group">{{ $password }}</div>
                            </div>
                        @endisset
                        {{--@if($model->id)
                            <div class="form-group row">
                                <label for="password"
                                       class="col-sm-3 text-right col-form-label text-right col-form-label-sm"></label>

                                <div class="col-sm-6">
                                    <div class="callout callout-warning"
                                         style="margin:20px 0 0 0">{{ trans('admin.chang_password_tip') }}</div>
                                </div>
                            </div>
                        @endif
                        <div class="form-group row {{ $errors->has('password')?"has-error":"" }}">
                            <admin::label class="col-md-3" :text="trans_choice('admin.password', 1)"/>
                            <div class="col-sm-6 input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-eye-slash"></i></span>
                                </div>
                                <admin::input type="password" name="password"/>
                            </div>
                        </div>
                        <div class="form-group row  {{ $errors->has('password')?"has-error":"" }}">
                            <admin::label class="col-md-3" :text="trans_choice('admin.password_confirmation', 1)"/>
                            <div class="col-sm-6 input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-eye-slash"></i></span>
                                </div>
                                <admin::input type="password" name="password_confirmation"/>
                            </div>
                        </div>--}}
                    </div>
                    <div class="card-footer clearfix">
                        <button type="submit" class="float-right btn btn-primary"
                                style="width: 120px">{{ trans('admin.save') }}</button>
                    </div>
                </div>
            </div>
            <!-- end panel -->
            <!-- end col-12 -->
        </div>
    </admin::form>
    <!-- end row -->

    <!-- end #content -->
@endsection