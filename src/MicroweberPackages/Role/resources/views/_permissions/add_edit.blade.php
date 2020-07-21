@extends('admin::_layouts.app')

@section('title', trans('admin.'.($model->id?'edit_permission':'add_permission')))


<admin::bread-middle :middle="['url' => Admin::action('index'), 'name' => trans_choice('admin.permission', 1)]" />

@section('content')
    <admin::form :model="$model">
    <!-- begin row -->
        <div class="row">
            <!-- begin col-12 -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group row">
                            <admin::label :text="trans_choice('admin.permission', 1)" required="true" class="col-md-3 text-right" />
                            <div class="col-md-6">
                                <admin::input type="text" :model="$model" name="name" id="name" :value="old('name', $model->name)"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <admin::label :text="trans('admin.guard')" required="true" class="col-md-3 text-right" />
                            <div class="col-md-6">
                                <admin::select name="guard" :selected="old('guard_name', $model->guard_name)" :results="$guards" toName="true" search="false" />
                            </div>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <button type="submit" class="float-right btn btn-primary"
                                style="width: 120px">{{ trans('admin.save') }}</button>
                    </div>
                </div>
            </div></div>
    </admin::form>
    <!-- end row -->

    <!-- end #content -->
@endsection
