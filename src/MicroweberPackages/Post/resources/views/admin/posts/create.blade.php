@extends('admin::layouts.app')

@section('content')

    <div class="px-5">

       <div class="py-5">
           <h2>
               Choose Post Design
           </h2>
       </div>

        <div class="row row-cards">

            <div class="col-md-4">
                <h3>Title with Text</h3>
                <a href="#" class="card card-link card-link-pop">
                    <div class="card-body">
                       <h3>Awesome Title Is Here</h3>
                        <p>
                        It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy.
                        </p>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <h3>Image Body and Text</h3>
                <a href="#" class="card card-link card-link-pop">
                    <div class="card-body">
                        <h3>Awesome Title Is Here</h3>
                        <div class="text-center py-6 bg-muted-lt">
                            <svg xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 -960 960 960" width="48"><path d="M180-120q-24 0-42-18t-18-42v-600q0-24 18-42t42-18h600q24 0 42 18t18 42v600q0 24-18 42t-42 18H180Zm0-60h600v-600H180v600Zm56-97h489L578-473 446-302l-93-127-117 152Zm-56 97v-600 600Z"/></svg>
                        </div>
                        <p>
                            It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here'.
                        </p>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <h3>Video with Text</h3>
                <a href="#" class="card card-link card-link-pop">
                    <div class="card-body">
                        <div class="text-center py-6 bg-muted-lt">
                            <svg xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 -960 960 960" width="48"><path d="m392-313 260-169-260-169v338ZM140-160q-24 0-42-18t-18-42v-520q0-24 18-42t42-18h680q24 0 42 18t18 42v520q0 24-18 42t-42 18H140Zm0-60h680v-520H140v520Zm0 0v-520 520Z"/></svg>
                        </div>
                        <p>
                            It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here'.
                        </p>
                    </div>
                </a>
            </div>

        </div>
    </div>

@endsection
