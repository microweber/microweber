{{--
type: layout
name: Small
description: Small
--}}
<style>
#newsletter-module-wrapper-small-{{ $params['id'] }} {
    max-width: 350px;
    min-height: 260px;
    background-color: #2c540b;
    border-radius: 4px;
}
#newsletter-module-wrapper-small-{{ $params['id'] }} h3 {
    font-size: 26px;
    color: white;
    padding: 16px 4px 12px 4px;
}
#newsletter-module-wrapper-small-{{ $params['id'] }} .btn-default {
    border: none;
}
#{{ $params['id'] }} label {
    color: white;
}
#newsletter-module-wrapper-small-{{ $params['id'] }} .form-group {
    margin: 4px 20px 6px 20px;
}
#newsletter-module-wrapper-small-{{ $params['id'] }} input[type="text"] {
    width: 100%;
}
#newsletter-module-wrapper-small-{{ $params['id'] }} .control-label {
    display: inline-block;
    margin-top: 8px;
}
#newsletter-module-wrapper-small-{{ $params['id'] }} a {
    color: #8be827;
}
#newsletter-module-wrapper-small-{{ $params['id'] }} .btn-default {
    margin-top: 2px;
    padding: 10px 17px;
}
#newsletter-module-wrapper-small-{{ $params['id'] }} .module-users-terms {
    margin-bottom: 10px;
}
</style>

<div class="newsletter-module-wrapper" id="newsletter-module-wrapper-small-{{ $params['id'] }}">

        @if($title)
            <h3>{{ $title }}</h3>
        @endif

        @if($description)
            <p>{{ $description }}</p>
        @endif




    <form method="post" id="newsletters-form-{{ $params['id'] }}">
        {!! csrf_field() !!}

        <div class="form-group hide-on-success">
            <div class="mw-flex-row">
                <div class="mw-flex-col-md-2 mw-flex-col-sm-12 mw-flex-col-xs-12">
                    <label class="control-label" for="name">
                        {{ __('Name') }}
                    </label>
                </div>
                <div class="mw-flex-col-md-10 mw-flex-col-sm-12 mw-flex-col-xs-12">
                    <input class="form-control" required="true" name="name" placeholder="Your Name" type="text"/>
                </div>
            </div>
        </div>

        <div class="form-group hide-on-success">
            <div class="mw-flex-row">
                <div class="mw-flex-col-md-2 mw-flex-col-sm-2 mw-flex-col-xs-12">
                    <label class="control-label" for="email">
                        {{ __('Email') }}
                    </label>
                </div>
                <div class="mw-flex-col-md-10 mw-flex-col-sm-10 mw-flex-col-xs-12">
                    <input class="form-control" required="true" name="email" placeholder="name@email.com" type="text"/>
                </div>
            </div>
        </div>


        @if($list_id)
            <input type="hidden" name="list_id" value="{{ $list_id }}" />
        @endif

        <div class="form-group hide-on-success">
            <div class="mw-flex-row">
                <div class="mw-flex-col-md-8 mw-flex-col-sm-8 mw-flex-col-xs-12">
                    @if($require_terms)
                        <div class="module-users-terms">
                            <label class="control-label" for="terms">
                                <input type="checkbox" required="true" name="terms" value="1"/>
                                {{ __('I agree to the terms and conditions') }}
                            </label>
                        </div>
                    @endif
                </div>
                <div class="mw-flex-col-md-4 mw-flex-col-sm-4 mw-flex-col-xs-12">
                    <div class="control-group">
                        <button type="submit" class="btn btn-default">
                            {{ __('Subscribe') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


@include('modules.newsletter::partials.newsletter-subscribe-script', ['params' => $params])
