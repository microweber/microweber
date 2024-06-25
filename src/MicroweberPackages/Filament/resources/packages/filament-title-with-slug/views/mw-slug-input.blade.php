<x-filament-forms::field-wrapper.index
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
    class="-mt-3 filament-seo-slug-input-wrapper"
>
    <div
        x-data="{
            context: '{{ $getContext() }}', // edit or create
            state: $wire.entangle('{{ $getStatePath() }}'), // current slug value
            statePersisted: '', // slug value received from db
            stateInitial: '', // slug value before modification
            editing: false,
            modified: false,
            initModification: function() {

                this.stateInitial = this.state;

                if(!this.statePersisted) {
                    this.statePersisted = this.state;
                }

                this.editing = true;

                setTimeout(() => $refs.slugInput.focus(), 75);
                {{--$nextTick(() => $refs.slugInput.focus());--}}

            },
            submitModification: function() {

                if(!this.stateInitial) {
                    this.state = '';
                }
                else {
                    this.state = this.stateInitial;
                }

                $wire.set('{{ $getStatePath() }}', this.state)

                this.detectModification();

                this.editing = false;

           },
           cancelModification: function() {

                this.stateInitial = this.state;

                this.detectModification();

                this.editing = false;

           },
           resetModification: function() {

                this.stateInitial = this.statePersisted;

                this.detectModification();

           },
           detectModification: function() {

                this.modified = this.stateInitial !== this.statePersisted;

           },
        }"
        x-on:submit.document="modified = false"
    >

        <div
            {{ $attributes->merge($getExtraAttributes())->class(['flex gap-4 items-center justify-between group text-sm filament-forms-text-input-component']) }}
        >

            @if($getReadOnly())

                <span class="flex">
                    <span class="mr-1">{{ $getLabelPrefix() }}</span>
                    <span class="text-gray-400">{{ $getFullBaseUrl() }}</span>
                    <span class="text-gray-400 font-semibold">{{ $getState() }}</span>
                </span>

                @if($getSlugInputUrlVisitLinkVisible())
                    <x-filament::link
                        :href="$getRecordUrl()"
                        target="_blank"
                        size="sm"
                        icon="heroicon-m-arrow-top-right-on-square"
                        icon-position="after"
                    >
                        {{ $getVisitLinkLabel() }}
                    </x-filament::link>
                @endif

            @else

                <span
                    class="
                        @if(!$getState()) flex items-center gap-1 @endif
                    "
                >

                    <span>{{ $getLabelPrefix() }}</span>

                    <span
                        x-text="!editing ? '{{ $getFullBaseUrl() }}' : '{{ $getBasePath() }}'"
                        class="text-gray-400"
                    ></span>

                    <a
                        href="#"
                        role="button"
                        title="{{ trans('filament-title-with-slug::package.permalink_action_edit') }}"
                        x-on:click.prevent="initModification()"
                        x-show="!editing"
                        class="
                            cursor-pointer
                            font-semibold text-gray-400
                            inline-flex items-center justify-center
                            hover:underline hover:text-primary-500
                            dark:hover:text-primary-400
                            gap-1
                        "
                        :class="context !== 'create' && modified ? 'text-gray-600 bg-gray-100 dark:text-gray-400 dark:bg-gray-700 px-1 rounded-md' : ''"
                    >
                        <span class="mr-1">{{ $getState() }}</span>

                        @svg('heroicon-m-pencil-square', 'h-4 w-4 text-primary-600 dark:text-primary-400', ['stroke-width' => '2'])

                        <span class="sr-only">{{ trans('filament-title-with-slug::package.permalink_action_edit') }}</span>

                    </a>

                    @if($getSlugLabelPostfix())
                        <span
                            x-show="!editing"
                            class="ml-0.5 text-gray-400"
                        >{{ $getSlugLabelPostfix() }}</span>
                    @endif

                    <span x-show="!editing && context !== 'create' && modified"> [{{ trans('filament-title-with-slug::package.permalink_status_changed') }}]</span>

                </span>

                <div
                    class="flex-1 mx-2"
                    x-show="editing"
                    style="display: none;"
                >
                    <div class="flex overflow-hidden transition duration-75 bg-white rounded-lg shadow-sm fi-input-wrapper ring-1 focus-within:ring-2 dark:bg-white/5 ring-gray-950/10 focus-within:ring-primary-600 dark:ring-white/20 dark:focus-within:ring-primary-500 fi-fo-text-input">
                        <input
                            type="text"
                            x-ref="slugInput"
                            x-model="stateInitial"
                            x-bind:disabled="!editing"
                            x-on:keydown.enter="submitModification()"
                            x-on:keydown.escape="cancelModification()"
                            {!! ($autocomplete = $getAutocomplete()) ? "autocomplete=\"{$autocomplete}\"" : null !!}
                            id="{{ $getId() }}"
                            {!! ($placeholder = $getPlaceholder()) ? "placeholder=\"{$placeholder}\"" : null !!}
                            {!! $isRequired() ? 'required' : null !!}
                            {{ $getExtraInputAttributeBag()->class([
                                'fi-input block w-full border-none bg-transparent py-1.5 text-base text-gray-950 outline-none transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-xs sm:leading-6 ps-3 pe-3',
                                'border-danger-600 ring-danger-600' => $errors->has($getStatePath())])
                            }}
                        />
                    </div>

                </div>

                <div
                    x-show="editing"
                    class="flex space-x-2 gap-2"
                    style="display: none;"
                >

                    <a
                        href="#"
                        role="button"
                        x-on:click.prevent="submitModification()"
                        style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                        class="
                            fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 disabled:pointer-events-none disabled:opacity-70 rounded-lg fi-btn-color-primary gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 dark:bg-custom-500 dark:hover:bg-custom-400 focus:ring-custom-500/50 dark:focus:ring-custom-400/50 fi-ac-btn-action
                        "
                    >
                        {{ trans('filament-title-with-slug::package.permalink_action_ok') }}
                    </a>

                    <x-filament::link
                        x-show="context === 'edit' && modified"
                        x-on:click.prevent="resetModification()"
                        class="cursor-pointer ml-4"
                        icon="heroicon-o-arrow-path"
                        color="gray"
                        size="sm"
                        title="{{ trans('filament-title-with-slug::package.permalink_action_reset') }}"
                    >
                        <span class="sr-only">{{ trans('filament-title-with-slug::package.permalink_action_reset') }}</span>
                    </x-filament::link>

                    <x-filament::link
                        x-on:click.prevent="cancelModification()"
                        class="cursor-pointer"
                        icon="heroicon-o-x-mark"
                        color="gray"
                        size="sm"
                        title="{{ trans('filament-title-with-slug::package.permalink_action_cancel') }}"
                    >
                        <span class="sr-only">{{ trans('filament-title-with-slug::package.permalink_action_cancel') }}</span>
                    </x-filament::link>

                </div>

                <span
                    x-show="context === 'edit'"
                    class="flex items-center space-x-2"
                >

                    @if($getSlugInputUrlVisitLinkVisible())
                        <template x-if="!editing">
                            <x-filament::link
                                :href="$getRecordUrl()"
                                target="_blank"
                                size="sm"
                                icon="heroicon-m-arrow-top-right-on-square"
                                icon-position="after"
                            >
                                {{ $getVisitLinkLabel() }}
                            </x-filament::link>
                        </template>
                    @endif

            </span>

            @endif

        </div>

    </div>

</x-filament-forms::field-wrapper.index>
