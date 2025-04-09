@extends('admin::layouts.app')

@section('content')
    <div class="px-3">
    <div class="row">
    <div class="col-md-12">

        <div class="card style-1 mb-3 mt-3">
            <div class="card-body pt-3">

                <ul class="nav nav-tabs">

                    <li class="nav-item">
                        <a href="{{route('admin.billing.index')}}" class="nav-link
                              @if(route_is('admin.billing.index')) active @endif ">
                            Subscriptions
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.billing.users')}}" class="nav-link
                              @if(route_is('admin.billing.users')) active @endif ">
                            Users
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.billing.subscription_plans')}}" class="nav-link
                              @if(route_is('admin.billing.subscription_plans')) active @endif ">
                            Subscription Plans
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.billing.subscription_plan_groups')}}" class="nav-link
                              @if(route_is('admin.billing.subscription_plan_groups')) active @endif ">
                            Subscription Plan Groups
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.billing.settings')}}" class="nav-link
                         @if(route_is('admin.billing.settings')) active @endif ">
                            Settings
                        </a>
                    </li>
                </ul>
                <div>

                @yield('content2')

                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
