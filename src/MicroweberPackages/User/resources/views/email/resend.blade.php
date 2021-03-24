@include('admin::partials.header')


<div class="container">
    <div class="row clearfix mt-20">
        <div class="col-md-6 col-md-offset-3 column">
            <form role="form" action="{{route('verification.send',[
            'id'=>$id,
            'hash'=>$hash,
        ]    )}}" method="post">


                @csrf
                <h1>Resend email verification</h1>


                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-12 column">
        </div>
    </div>
</div>



@include('admin::partials.footer')
