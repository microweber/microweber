<x-filament-panels::page>
    @php($cur = $order->currency ?: '')

    <div class="mb-2">
        <x-filament::button tag="a" color="gray" size="sm" icon="heroicon-o-arrow-left"
            :href="\Modules\Profile\Filament\Pages\OrderHistory::getUrl()">
            {{ __('Back to orders') }}
        </x-filament::button>
    </div>

    {{-- Summary --}}
    <x-filament::section>
        <x-slot name="heading">{{ __('Order summary') }}</x-slot>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 text-sm">
            <div>
                <p class="text-gray-500">{{ __('Order Number') }}</p>
                <p class="font-semibold">#{{ $order->order_reference_id ?: $order->id }}</p>
            </div>
            <div>
                <p class="text-gray-500">{{ __('Order Date') }}</p>
                <p class="font-medium">{{ $order->created_at?->format('M j, Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500">{{ __('Status') }}</p>
                @php($st = $order->order_status)
                <x-filament::badge :color="$st === 'completed' ? 'success' : ($st === 'pending' ? 'warning' : 'gray')" class="mt-1">
                    {{ __(ucfirst($st ?: 'new')) }}
                </x-filament::badge>
            </div>
            <div>
                <p class="text-gray-500">{{ __('Payment Status') }}</p>
                <x-filament::badge :color="$order->is_paid ? 'success' : 'danger'" class="mt-1">
                    {{ $order->is_paid ? __('Paid') : __('Unpaid') }}
                </x-filament::badge>
            </div>
        </div>
    </x-filament::section>

    {{-- Customer --}}
    <x-filament::section>
        <x-slot name="heading">{{ __('Customer Information') }}</x-slot>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 text-sm">
            <div>
                <p class="text-gray-500">{{ __('Name') }}</p>
                <p class="font-medium">{{ trim($order->first_name . ' ' . $order->last_name) ?: '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">{{ __('Email') }}</p>
                <p class="font-medium">{{ $order->email ?: '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">{{ __('Phone') }}</p>
                <p class="font-medium">{{ $order->phone ?: __('N/A') }}</p>
            </div>
        </div>
    </x-filament::section>

    {{-- Shipping --}}
    @if($order->address || $order->city || $order->country)
        <x-filament::section>
            <x-slot name="heading">{{ __('Shipping Address') }}</x-slot>
            <div class="text-sm leading-6">
                @if($order->address)<p>{{ $order->address }}</p>@endif
                @if($order->address2)<p>{{ $order->address2 }}</p>@endif
                <p>{{ $order->city }}{{ $order->city && $order->state ? ', ' : '' }}{{ $order->state }} {{ $order->zip }}</p>
                @if($order->country)<p>{{ $order->country }}</p>@endif
            </div>
        </x-filament::section>
    @endif

    {{-- Items --}}
    <x-filament::section>
        <x-slot name="heading">{{ __('Order Items') }}</x-slot>
        <div class="divide-y divide-gray-100 dark:divide-white/10">
            @forelse($order->cart as $cartItem)
                @php($product = $cartItem->products?->first())
                <div class="flex items-center justify-between py-3">
                    <div class="flex items-center gap-3">
                        @if($product)
                            <img src="{{ $product->thumbnail() }}" alt="{{ $product->title }}" class="h-12 w-12 rounded object-cover">
                        @else
                            <div class="h-12 w-12 rounded bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs text-gray-500">—</div>
                        @endif
                        <div>
                            <p class="font-medium text-sm">{{ $product?->title ?: (__('Product') . ' #' . $cartItem->rel_id) }}</p>
                            <p class="text-xs text-gray-500">{{ __('Qty:') }} {{ $cartItem->qty }}</p>
                        </div>
                    </div>
                    <p class="font-medium text-sm">{{ number_format($cartItem->price * $cartItem->qty, 2) }} {{ $cur }}</p>
                </div>
            @empty
                <p class="py-2 text-sm text-gray-500">{{ __('No items found') }}</p>
            @endforelse
        </div>

        <div class="mt-4 border-t border-gray-100 dark:border-white/10 pt-4 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-300">{{ __('Subtotal') }}</span>
                <span>{{ number_format($order->amount ?? 0, 2) }} {{ $cur }}</span>
            </div>
            <div class="flex justify-between font-semibold pt-2 mt-2 border-t border-gray-100 dark:border-white/10">
                <span>{{ __('Total') }}</span>
                <span>{{ number_format($order->amount ?? 0, 2) }} {{ $cur }}</span>
            </div>
        </div>
    </x-filament::section>

    {{-- Invoices --}}
    @if($invoices->isNotEmpty())
        <x-filament::section>
            <x-slot name="heading">{{ __('Invoices') }}</x-slot>
            <div class="divide-y divide-gray-100 dark:divide-white/10">
                @foreach($invoices as $invoice)
                    <div class="flex items-center justify-between py-3 text-sm">
                        <div>
                            <p class="font-medium">{{ $invoice->invoice_number }}</p>
                            <p class="text-xs text-gray-500">
                                {{ optional($invoice->invoice_date)->format('M j, Y') }}
                                · {{ number_format($invoice->total ?? 0, 2) }} {{ $cur }}
                                @if($invoice->paid_status) · {{ $invoice->paid_status }} @endif
                            </p>
                        </div>
                        <x-filament::button wire:click="downloadInvoice({{ $invoice->id }})" size="sm" icon="heroicon-o-arrow-down-tray" color="gray">
                            {{ __('Download PDF') }}
                        </x-filament::button>
                    </div>
                @endforeach
            </div>
        </x-filament::section>
    @endif

    {{-- Payments --}}
    @if($payments->isNotEmpty())
        <x-filament::section>
            <x-slot name="heading">{{ __('Payments') }}</x-slot>
            <div class="divide-y divide-gray-100 dark:divide-white/10">
                @foreach($payments as $payment)
                    <div class="flex items-center justify-between py-3 text-sm">
                        <div>
                            <p class="font-medium">{{ number_format($payment->amount ?? 0, 2) }} {{ strtoupper($payment->currency ?: $cur) }}</p>
                            <p class="text-xs text-gray-500">
                                {{ optional($payment->created_at)->format('M j, Y') }}
                                @if($payment->payment_provider) · {{ $payment->payment_provider }} @endif
                                @if($payment->transaction_id) · {{ \Illuminate\Support\Str::limit($payment->transaction_id, 24) }} @endif
                            </p>
                        </div>
                        <x-filament::badge :color="in_array(strtolower((string) $payment->status), ['paid','completed','success']) ? 'success' : 'gray'">
                            {{ ucfirst($payment->status ?: '—') }}
                        </x-filament::badge>
                    </div>
                @endforeach
            </div>
        </x-filament::section>
    @endif

    {{-- Digital downloads (guarded if the module is absent) --}}
    @if(class_exists(\Modules\DigitalProduct\Models\DigitalProductDownload::class))
        @php($mwOrderDownloads = \Modules\DigitalProduct\Models\DigitalProductDownload::with('file')->where('order_id', $order->id)->get())
        @if($mwOrderDownloads->isNotEmpty())
            <x-filament::section>
                <x-slot name="heading">{{ __('Downloads') }}</x-slot>
                <div class="divide-y divide-gray-100 dark:divide-white/10">
                    @foreach($mwOrderDownloads as $mwDl)
                        <div class="flex items-center justify-between py-3 text-sm">
                            <span class="truncate">{{ optional($mwDl->file)->original_name ?: __('File') }}</span>
                            @if($mwDl->canDownload())
                                <x-filament::button tag="a" size="sm" color="primary"
                                    :href="route('digital-product.download', ['token' => $mwDl->token])" target="_blank">
                                    {{ __('Download') }}
                                </x-filament::button>
                            @else
                                <span class="text-xs text-gray-400">{{ $mwDl->isExpired() ? __('Expired') : __('Limit reached') }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </x-filament::section>
        @endif
    @endif
</x-filament-panels::page>
