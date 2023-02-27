
<div class="card style-1 mb-3">
    <div class="card-body">
        <div class="no-items-found products">
            <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_products.svg'); ">
                <h4>{{ _e('You don’t have any products') }}</h4>
                <p>{{ _e('Create your first product right now.') }} <br/>
                    {{ _e( 'You are able to do that in very easy way!') }}</p>
                <br/>
                <a href="{{ route('admin.product.create')  }}" class="btn btn-primary btn-rounded">{{ _e('Create a Product') }}</a>
            </div>
        </div>
    </div>
</div>
