<div class="card-body mb-3">

    @if ($openLinksInModal)
        @include('content::admin.content.livewire.open-links-in-modal-script')
    @endif 

    @include('content::admin.content.livewire.set-tree-active-content')
    @include('content::admin.content.livewire.card-header')

    <div class="">

        <div class="no-items-found">

            <div class="row mt-lg-2">
                <div class="col-12 d-flex flex-wrap justify-content-center align-items-center text-center">
                    <div class="col-lg-6 col-12 mt-xxl-0 order-lg-1 order-2">
                        @if (isset($inPage) and $inPage)
                            @if($contentType == 'content')
                                <h2 class="mb-2"><?php _e('You don’t have any content in this page'); ?></h2>
                                <p class="mb-4"><?php _e('Create your first content right now.');?></p>
                            @endif

                            @if($contentType == 'post')
                                <h2 class="mb-2"><?php _e('You don’t have any post in this page'); ?></h2>
                                <p class="mb-4"><?php _e('Create your first post right now.');?></p>
                            @endif

                            @if($contentType == 'product')
                                <h2 class="mb-2"><?php _e('You don’t have any product in this page'); ?></h2>
                                <p class="mb-4"><?php _e('Create your first product right now.');?></p>
                            @endif

                            @if($contentType == 'page')
                                <h2 class="mb-2"><?php _e('You don’t have any sub pages in this page'); ?></h2>
                                <p class="mb-4"><?php _e('Create your first page right now.');?></p>
                            @endif
                        @elseif (isset($inCategory) and $currentCategory)
                            @if($contentType == 'content')
                                <h2 class="mb-2"><?php _e('You don’t have any content in'); ?>  {{$currentCategory['title']}}</h2>
                                <p class="mb-4"><?php _e('Create your first content right now.');?></p>
                            @endif

                            @if($contentType == 'post')
                                <h2 class="mb-2"><?php _e('You don’t have any posts in'); ?>  {{$currentCategory['title']}}</h2>
                                <p class="mb-4"><?php _e('Create your first post right now.');?></p>
                            @endif

                            @if($contentType == 'product')
                                <h2 class="mb-2"><?php _e('You don’t have any products in'); ?>  {{$currentCategory['title']}}</h2>
                                <p class="mb-4"><?php _e('Create your first product right now.');?></p>
                            @endif

                            @if($contentType == 'page')
                                <h2 class="mb-2"><?php _e('You don’t have any pages in'); ?>  {{$currentCategory['title']}}</h2>
                                <p class="mb-4"><?php _e('Create your first page right now.');?></p>
                            @endif
                        @else
                            @if($contentType == 'content')
                                <h2 class="mb-2"><?php _e('You don’t have any content'); ?></h2>
                                <p class="mb-4"><?php _e('Create your first content right now.');?></p>
                            @endif

                            @if($contentType == 'post')
                                <h2 class="mb-2"><?php _e('You don’t have any post'); ?></h2>
                                <p class="mb-4"><?php _e('Create your first post right now.');?></p>
                            @endif

                            @if($contentType == 'product')
                                <h2 class="mb-2"><?php _e('You don’t have any product'); ?></h2>
                                <p class="mb-4"><?php _e('Create your first product right now.');?></p>
                            @endif

                            @if($contentType == 'page')
                                <h2 class="mb-2"><?php _e('You don’t have any page'); ?></h2>
                                <p class="mb-4"><?php _e('Create your first page right now.');?></p>
                            @endif
                        @endif


                        <p><?php _e( 'You are able to do that in very easy way!'); ?></p>

                        <br/>

                        @include('content::admin.content.livewire.create-content-buttons')
                    </div>
                    <div class="col-lg-6 col-12 no-items-box order-lg-2 order-1" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_posts.svg'); ">

                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
