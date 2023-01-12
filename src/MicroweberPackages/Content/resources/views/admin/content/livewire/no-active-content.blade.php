<div class="card style-1 mb-3">
    <div class="card-body pt-3">

        <div class="no-items-found">

            <div class="row">
                <div class="col-12">
                    <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_products.svg'); ">


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


                        <p><?php _e( 'You are able to do that in very easy way!'); ?></p>

                        <br/>
                        @if($contentType == 'post')
                        <a href="{{route('admin.post.create')}}" class="btn btn-primary btn-rounded"><?php _e('Create a post'); ?></a>
                        @endif
                        @if($contentType == 'product')
                            <a href="{{route('admin.post.create')}}" class="btn btn-primary btn-rounded"><?php _e('Create a product'); ?></a>
                        @endif
                        @if($contentType == 'page')
                            <a href="{{route('admin.page.create')}}" class="btn btn-primary btn-rounded"><?php _e('Create a page'); ?></a>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
