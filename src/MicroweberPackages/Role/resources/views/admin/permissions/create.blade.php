

<script>
    mw.lib.require('bootstrap4');
</script>


<div class="container" style="margin-top: 30px">

            <!-- Vertical Layout -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="car-header">
                            <h2>
                                Add New Permission
                            </h2>
                            <a href="{{route('permissions.index')}}" class="btn btn-success">Back to Permissions</a>
                        </div>
                        <div class="card-body">
                           <form id="form_validation" method="POST" action="{{ route('permissions.store') }}">
                            {{ csrf_field() }}
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="name" value="{{old('name')}}" required>
                                        <label class="form-label">Name</label>
                                         @if ($errors->has('name'))
                                            <label id="name-error" class="error" for="name">{{ $errors->first('name') }}</label>
                                        @endif
                                    </div>
                                </div>
                                <button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Vertical Layout -->
           
        </div>
