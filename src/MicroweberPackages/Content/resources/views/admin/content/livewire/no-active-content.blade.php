<div class="card style-1 mb-3">

    @include('content::admin.content.livewire.card-header')

    <div class="card-body pt-3">

        <div class="no-items-found">

            <div class="row">
                <div class="col-12">
                    <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_products.svg'); ">


                        @if (isset($inPage))
                            @if($contentType == 'content')
                                <h4><?php _e('You don’t have any content in this page'); ?></h4>
                                <p><?php _e('Create your first content right now.');?></p>
                            @endif

                            @if($contentType == 'post')
                                <h4><?php _e('You don’t have any post in this page'); ?></h4>
                                <p><?php _e('Create your first post right now.');?></p>
                            @endif

                            @if($contentType == 'product')
                                <h4><?php _e('You don’t have any product in this page'); ?></h4>
                                <p><?php _e('Create your first product right now.');?></p>
                            @endif

                            @if($contentType == 'page')
                                <h4><?php _e('You don’t have any sub pages in this page'); ?></h4>
                                <p><?php _e('Create your first page right now.');?></p>
                            @endif
                        @elseif (isset($inCategory))
                            @if($contentType == 'content')
                                <h4><?php _e('You don’t have any content in'); ?>  {{$currentCategory['title']}}</h4>
                                <p><?php _e('Create your first content right now.');?></p>
                            @endif

                            @if($contentType == 'post')
                                <h4><?php _e('You don’t have any posts in'); ?>  {{$currentCategory['title']}}</h4>
                                <p><?php _e('Create your first post right now.');?></p>
                            @endif

                            @if($contentType == 'product')
                                <h4><?php _e('You don’t have any products in'); ?>  {{$currentCategory['title']}}</h4>
                                <p><?php _e('Create your first product right now.');?></p>
                            @endif

                            @if($contentType == 'page')
                                <h4><?php _e('You don’t have any pages in'); ?>  {{$currentCategory['title']}}</h4>
                                <p><?php _e('Create your first page right now.');?></p>
                            @endif
                        @else
                            @if($contentType == 'content')
                                <h4><?php _e('You don’t have any content'); ?></h4>
                                <p><?php _e('Create your first content right now.');?></p>
                            @endif

                            @if($contentType == 'post')
                                <h4><?php _e('You don’t have any post'); ?></h4>
                                <p><?php _e('Create your first post right now.');?></p>
                            @endif

                            @if($contentType == 'product')
                                <h4><?php _e('You don’t have any product'); ?></h4>
                                <p><?php _e('Create your first product right now.');?></p>
                            @endif

                            @if($contentType == 'page')
                                <h4><?php _e('You don’t have any page'); ?></h4>
                                <p><?php _e('Create your first page right now.');?></p>
                            @endif
                        @endif


                        <p><?php _e( 'You are able to do that in very easy way!'); ?></p>

                        <br/>

                        @if($contentType == 'post')
                            <a href="{{route('admin.post.create')}}" class="btn btn-primary btn-rounded"><?php _e('Create a post'); ?></a>
                        @endif

                        @if($contentType == 'product')
                            <a href="{{route('admin.product.create')}}" class="btn btn-primary btn-rounded"><?php _e('Create a product'); ?></a>
                        @endif

                        @if($contentType == 'page')
                            <a href="{{route('admin.page.create')}}" class="btn btn-primary btn-rounded"><?php _e('Create a page'); ?></a>
                        @endif

                        @if($contentType == 'content')
                            <a href="{{route('admin.page.create')}}" class="btn btn-primary btn-rounded"><?php _e('Create a page'); ?></a>
                            <a href="{{route('admin.post.create')}}" class="btn btn-primary btn-rounded"><?php _e('Create a post'); ?></a>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
