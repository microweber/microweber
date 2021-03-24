@include('admin::partials.header')

<main class="w-100 h-100vh ">
    <div class="row my-5 d-flex align-items-center ">
        <div class="col-12 col-sm-9 col-md-7 col-lg-5 col-xl-4 mx-auto">


            <div class="card">
                <div class="card-body py-4">

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form role="form" method="POST" action="{{ route('password.update') }}">
                        <h3>Reset Password</h3>

                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">

                        <label class="control-label">Email: {{$email}}</label>




                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

                            <input type="password" class="form-control" id="password" name="password"
                                   placeholder="Password"/>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
                            @endif
                            <input type="password" class="form-control" id="password_confirmation"
                                   name="password_confirmation" placeholder="Confirm Password"/>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-default submit btn-primary">Reset
                                Password
                            </button>
                        </div>

                        @if ($errors->has('password'))
                            <div class="text-danger"><strong>{{ $errors->first('password') }}</strong></div>
                        @endif



                    </form>


                </div>
            </div>
        </div>

    </div>
</main>


@include('admin::partials.footer')
