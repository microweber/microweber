<div>

    <div class="row row-cards">

        @foreach($modules as $module)
            <div class="col-md-3">
                <div class="card card-stacked" style="min-height:170px">
                    <div class="card-body text-center d-flex align-items-center justify-content-center flex-column">


                        <a href="{{module_admin_url($module->name)}}">
                            <img src="{{$module->icon()}}" style="width:64px" />
                            <h3 class="card-title pt-2 text-muted">
                                {{str_limit(_e($module->name, true), 30)}}
                            </h3>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

</div>
