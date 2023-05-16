@extends('user::layout')

@section('content')

    <div>


        <div x-data="{ recovery: false }">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400" x-show="! recovery">
                {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
            </div>

            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400" x-cloak x-show="recovery">
                {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
            </div>

            <x-microweber-ui::validation-errors class="mb-4"/>

            <form method="POST" action="{{ route('two-factor.login') }}">
                @csrf

                <div class="mt-4" x-show="! recovery">
                    <x-microweber-ui::label class="form-label" for="code" value="{{ __('Code') }}"/>
                    <x-microweber-ui::input id="code" type="text" inputmode="numeric" name="code" autofocus x-ref="code"
                                            autocomplete="one-time-code"/>
                </div>

                <div class="mt-4" x-cloak x-show="recovery">
                    <x-microweber-ui::label class="form-label" for="recovery_code" value="{{ __('Recovery Code') }}"/>
                    <x-microweber-ui::input id="recovery_code" type="text" name="recovery_code" x-ref="recovery_code"
                                            autocomplete="one-time-code"/>
                </div>

                <div class=" justify-end mt-4">
                    <div class="row">
                        <div class="col-sm-6  ">
                            <a class="pull-left btn btn-ghost-secondary " href="#"
                               x-show="! recovery"
                               x-on:click="
                                        recovery = true;
                                        $nextTick(() => { $refs.recovery_code.focus() })
                                    ">
                                {{ __('Use a recovery code') }}
                            </a>


                            <a class="pull-left btn btn-outline  " href="#"
                                                     x-cloak
                                                     x-show="recovery"
                                                     x-on:click="
                                        recovery = false;
                                        $nextTick(() => { $refs.code.focus() })
                                    ">
                                {{ __('Use an authentication code') }}
                            </a>

                        </div>
                        <div class="col-sm-6 ">



<div class="pull-right">

    <x-microweber-ui::button class="ml-4">
        {{ __('Log in') }}
    </x-microweber-ui::button>
</div>

                        </div>
                    </div>


                </div>
            </form>
        </div>

    </div>
@endsection
