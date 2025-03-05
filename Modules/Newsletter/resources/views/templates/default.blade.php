{{--
type: layout
name: Default
description: Default
--}}
<div class="newsletter-module-wrapper well">


    @if($title)
        <h3>{{ $title }}</h3>
    @endif

    @if($description)
        <p>{{ $description }}</p>
    @endif


    <form method="post" id="newsletters-form-{{ $params['id'] }}">
        {!! csrf_field() !!}

        <div class="col-xl-8 col-lg-10 col-12">
            <div class="form-group hide-on-success my-2">
                <label class="control-label requiredField" for="name1">
                    {{ __('Name') }}
                    <span class="asteriskField">*</span>
                </label>
                <input class="form-control" required="true" name="name" placeholder="Your Name" type="text"/>
            </div>

            <div class="form-group hide-on-success my-2">
                <label class="control-label requiredField" for="email1">
                    {{ __('Email') }}
                    <span class="asteriskField">*</span>
                </label>
                <input class="form-control" required="true" name="email" placeholder="name@email.com" type="text"/>
            </div>

        </div>

        @if($list_id)
            <input type="hidden" name="list_id" value="{{ $list_id }}"/>
        @endif
        @if($require_terms)
            <div class="form-group hide-on-success">
                <label class="control-label requiredField" for="terms">
                    <input type="checkbox" required="true" name="terms" value="1"/>
                    {{ __('I agree to the terms and conditions') }}
                </label>
            </div>
        @endif

        <div class="form-group hide-on-success mt-4">
            <div>
                <button class="btn btn-primary" name="submit" type="submit">
                    {{ __('Submit') }}
                </button>
            </div>
        </div>
    </form>
</div>
@include('modules.newsletter::partials.newsletter-subscribe-script', ['params' => $params])
