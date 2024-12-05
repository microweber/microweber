<div>
    @if(!empty($availableMethods))

        <div class="payment-method-selector">
            <div class="grid gap-4">
                @foreach($availableMethods as $method)
                    <label
                        class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 @if($selectedMethod === $method['provider']) border-primary bg-primary/5 @else border-gray-200 @endif">
                        <input type="radio" name="payment_method" value="{{ $method['provider'] }}"
                               wire:model.live="selectedMethod"
                               wire:click="selectMethod('{{ $method['provider'] }}')"
                               class="h-4 w-4 text-primary border-gray-300 focus:ring-primary">
                        <div class="ml-3">
                            <span class="block text-sm font-medium text-gray-900">{{ $method['name'] }}</span>
                        </div>


                    </label>


                    @if(!empty($selectedMethod) and $selectedMethod === $method['provider'])
                        <div class="mt-4">
                            {!! app()->payment_method_manager->render($selectedMethod) !!}
                        </div>
                    @endif
                @endforeach
            </div>


        </div>

    @else
        <div class="alert alert-info">
            {{ __('No payment methods available') }}
        </div>
    @endif

</div>
