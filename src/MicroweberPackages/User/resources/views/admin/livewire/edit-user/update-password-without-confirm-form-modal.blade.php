<div>
    <div class="modal-header">
        <h5 class="modal-title">{{_e('Update user password')}}</h5>
        <button type="button" class="btn-close" wire:click="$emit('closeModal')" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <div class="mx-5 my-5" x-data="{password: '', passwordConfirm: '', revealPassword:'', revealPasswordConfirm:''}">

            <form wire:submit.prevent="updatePassword">
                <div>
                   <h2>{{_e('Update Password')}}</h2>
                    {{_e('Ensure your account is using a long, random password to stay secure.')}}
                </div>

                @if(!$saved)
                <div class="col-span-6 sm:col-span-4 mt-4">

                    <x-microweber-ui::label for="password" value="Password" />

                    <div class="input-group input-group-flat">
                        <input class="form-control" id="password" x-model="password" :type="revealPassword ? 'text' : 'password'" wire:model.defer="state.password" />
                        <span class="input-group-text" x-show="password">
                            <a href="javascript:void(0)" class="input-group-link" x-on:click="revealPassword =! revealPassword">
                                <span x-show="!revealPassword">
                                    {{_e('Show password')}}
                                </span>
                               <span x-show="revealPassword">
                                    {{_e('Hide password')}}
                                </span>
                            </a>
                      </span>
                    </div>

                    <x-microweber-ui::input-error for="password" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-4 mt-4">
                    <x-microweber-ui::label for="password_confirm" value="Confirm Password" />

                    <div class="input-group input-group-flat">
                    <input class="form-control" id="password_confirm"  x-model="passwordConfirm" :type="revealPasswordConfirm ? 'text' : 'password'" wire:model.defer="state.password_confirm" />
                        <span class="input-group-text" x-show="passwordConfirm">
                            <a href="javascript:void(0)" class="input-group-link" x-on:click="revealPasswordConfirm =! revealPasswordConfirm">
                                <span x-show="!revealPasswordConfirm">
                                    {{_e('Show password')}}
                                </span>
                               <span x-show="revealPasswordConfirm">
                                    {{_e('Hide password')}}
                                </span>
                            </a>
                      </span>
                    </div>
                    <x-microweber-ui::input-error for="password_confirm" class="mt-2" />
                </div>
                @endif

                <div class="mt-4">

                    @if($saved)
                        <div class="alert alert-success">
                            <div class="alert-body">
                                {{_e('Password is changed.')}}
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline-primary" wire:click="$emit('closeModal')" aria-label="Close">
                            {{_e('Ok! Close')}}
                        </button>
                    @else

                    <x-microweber-ui::button>
                        {{_('Change')}}
                    </x-microweber-ui::button>

                    @endif

                </div>
            </form>
        </div>
    </div>
</div>
