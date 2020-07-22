
<script>
    mw.lib.require('bootstrap4');
</script>

<div class="container" style="margin-top: 30px">

    <!-- Vertical Layout -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h2>
                        Edit Permission
                    </h2>
                </div>
                <div class="card-body">
                   <form id="form_validation" method="POST" action="{{ route('permissions.update',$permission->id) }}">
                    {{ csrf_field() }}
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input name="_method" type="hidden" value="PUT">
                                <input type="text" class="form-control" name="name" value="{{ $permission->name }}" required>
                                <label class="form-label">Name</label>
                            </div>
                        </div>
                        <button class="btn btn-primary waves-effect" type="submit">UPDATE</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Vertical Layout -->

</div>