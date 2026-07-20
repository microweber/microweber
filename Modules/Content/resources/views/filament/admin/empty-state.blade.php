<div class="text-center you-dont-have-any d-flex justify-content-center mt-5" style="min-height: 300px; max-height: 60vh; overflow: hidden;">
    <div>
@if($modelName == Modules\Content\Models\Content::class)

<h2 style="font-weight: 600;" class="mw-admin-empty-state-heading text-center mt-4">
No content found.
</h2>

{{-- task-2026-05-16-fd0d1d: primary CTA on the empty state. --}}
<div class="text-center mw-table-empty-cta-wrap">
    <a href="{{ route('filament.admin.resources.contents.create') }}" class="mw-table-empty-cta" aria-label="Add content">
        + Add content
    </a>
</div>

@svg('mw-no-content', 'mw-admin-empty-state-illustration', ['style' => 'max-width:200px;max-height:200px;width:200px;height:200px;'])

@endif

@if($modelName == Modules\Order\Models\Order::class)

<h2 style="font-weight: 600;" class="mw-admin-empty-state-heading text-center mt-4">
You do not have any orders yet.
</h2>

{{-- task-2026-05-16-fd0d1d: primary CTA on the empty state. --}}
<div class="text-center mw-table-empty-cta-wrap">
    <a href="{{ route('filament.admin.resources.orders.create') }}" class="mw-table-empty-cta" aria-label="Add order">
        + Add order
    </a>
</div>

@svg('mw-no-orders', 'mw-admin-empty-state-illustration', ['style' => 'max-width:200px;max-height:200px;width:200px;height:200px;'])

        @endif

@if($modelName == Modules\Customer\Models\Customer::class)

<h2 style="font-weight: 600;" class="mw-admin-empty-state-heading text-center mt-4">
You do not have any clients yet.
</h2>

{{-- task-2026-05-16-fd0d1d: primary CTA on the empty state. --}}
<div class="text-center mw-table-empty-cta-wrap">
    <a href="{{ route('filament.admin.resources.customers.create') }}" class="mw-table-empty-cta" aria-label="Add client">
        + Add client
    </a>
</div>

@svg('mw-no-clients', 'mw-admin-empty-state-illustration', ['style' => 'max-width:200px;max-height:200px;width:200px;height:200px;'])

            @endif


@if($modelName == Modules\Invoice\Models\Invoice::class)

<h2 style="font-weight: 600;" class="mw-admin-empty-state-heading text-center mt-4">
You do not have any invoices yet.
</h2>

{{-- task-2026-05-16-fd0d1d: primary CTA on the empty state. --}}
<div class="text-center mw-table-empty-cta-wrap">
    <a href="{{ route('filament.admin.resources.invoices.create') }}" class="mw-table-empty-cta" aria-label="Add invoice">
        + Add invoice
    </a>
</div>

@svg('mw-no-invoices', 'mw-admin-empty-state-illustration', ['style' => 'max-width:200px;max-height:200px;width:200px;height:200px;'])

            @endif

@if($modelName == Modules\Product\Models\Product::class)

<h2 style="font-weight: 600;" class="mw-admin-empty-state-heading text-center mt-4">
You do not have any products yet.
</h2>

{{-- task-2026-05-16-fd0d1d: primary CTA on the empty state. --}}
<div class="text-center mw-table-empty-cta-wrap">
    <a href="{{ route('filament.admin.resources.products.create') }}" class="mw-table-empty-cta" aria-label="Add product">
        + Add product
    </a>
</div>

@svg('mw-no-products', 'mw-admin-empty-state-illustration', ['style' => 'max-width:200px;max-height:200px;width:200px;height:200px;'])

        @endif

        @if($modelName == Modules\Page\Models\Page::class)

            <h2 style="font-weight: 600;" class="mw-admin-empty-state-heading text-center mt-4">
                You do not have any pages yet.
            </h2>

            {{-- task-2026-05-16-fd0d1d: primary CTA on the empty state. --}}
            <div class="text-center mw-table-empty-cta-wrap">
                <a href="{{ route('filament.admin.resources.pages.create') }}" class="mw-table-empty-cta" aria-label="Add page">
                    + Add page
                </a>
            </div>

@svg('mw-no-pages', 'mw-admin-empty-state-illustration', ['style' => 'max-width:200px;max-height:200px;width:200px;height:200px;'])

        @endif

        @if($modelName == Modules\Post\Models\Post::class)

            {{-- task-2026-05-16-008d91 / AI-729 — "No posts yet" empty
                 state for PostResource (context-aware heading + CTA
                 routing to create-post form; also carries the "Re-scope
                 this module" secondary action when inside Live Edit). --}}
            <div class="mw-admin-empty-state-posts" data-mw-empty-state="posts">
            <h2 style="font-weight: 600;" class="mw-admin-empty-state-heading text-center mt-4">
                No posts yet
            </h2>

            <p class="mw-admin-empty-state-explainer text-center">
                Articles, news, and updates you publish appear here.
            </p>

            {{-- task-2026-05-16-fd0d1d: primary CTA on the empty state. --}}
            <div class="text-center mw-table-empty-cta-wrap">
                <a href="{{ route('filament.admin.resources.posts.create') }}" class="mw-table-empty-cta" aria-label="Write your first post">
                    Write your first post →
                </a>
            </div>

@svg('mw-no-content', 'mw-admin-empty-state-illustration', ['style' => 'max-width:200px;max-height:200px;width:200px;height:200px;'])
            </div>
        @endif


        {{-- task-2026-05-28-2f5a6c / AI-1099 — Payment provider empty state. --}}
        @if($modelName == Modules\Payment\Models\PaymentProvider::class)
                <h2 style="font-weight: 600;" class="mw-admin-empty-state-heading text-center mt-4">
                    You do not have any payment providers yet.
                </h2>

                {{-- task-2026-05-16-fd0d1d: primary CTA on the empty state. --}}
                <div class="text-center mw-table-empty-cta-wrap">
                    <a href="{{ route('filament.admin.resources.payment-providers.index') }}" class="mw-table-empty-cta" aria-label="Configure payment providers">
                        + Configure payment providers
                    </a>
                </div>

@svg('mw-payments', 'mw-admin-empty-state-illustration', ['style' => 'max-width:200px;max-height:200px;width:200px;height:200px;'])

            @endif

        @if($modelName == Modules\Shipping\Models\ShippingProvider::class)

            <h2 style="font-weight: 600;" class="mw-admin-empty-state-heading text-center mt-4">
                You do not have any shipping providers yet.
            </h2>

            {{-- task-2026-05-16-fd0d1d: primary CTA on the empty state. --}}
            <div class="text-center mw-table-empty-cta-wrap">
                <a href="{{ route('filament.admin.resources.shipping-providers.index') }}" class="mw-table-empty-cta" aria-label="Configure shipping providers">
                    + Configure shipping providers
                </a>
            </div>

@svg('mw-shipping', 'mw-admin-empty-state-illustration', ['style' => 'max-width:200px;max-height:200px;width:200px;height:200px;'])

        @endif


        @if($modelName == Modules\Tax\Models\TaxType::class)
                <h2 style="font-weight: 600;" class="mw-admin-empty-state-heading text-center mt-4">
                    You do not have any taxes yet.
                </h2>

                {{-- task-2026-05-16-fd0d1d: primary CTA on the empty state. --}}
                <div class="text-center mw-table-empty-cta-wrap">
                    <a href="{{ route('filament.admin.resources.taxes.index') }}" class="mw-table-empty-cta" aria-label="Configure taxes">
                        + Configure taxes
                    </a>
                </div>

@svg('mw-taxes', 'mw-admin-empty-state-illustration', ['style' => 'max-width:200px;max-height:200px;width:200px;height:200px;'])

            @endif



        {{-- task-2026-05-28-2f5a6c / AI-1099 — Payment branch carries a
             text-only chrome (no SVG illustration — payments are
             transaction records, not setup objects) with a CTA pointing
             at the Payment Provider Settings page (records are created
             by transactions, not manually). --}}
        @if($modelName == Modules\Payment\Models\Payment::class)
            <h2 style="font-weight: 600;" class="mw-admin-empty-state-heading text-center mt-4">
                You do not have any payments yet.
            </h2>

            <p class="text-center mt-3" style="opacity: 0.7;">
                Set up payment providers to start accepting payments.
            </p>

            <div class="text-center mw-table-empty-cta-wrap">
                <a href="{{ route('filament.admin.resources.payment-providers.index') }}" class="mw-table-empty-cta" aria-label="Configure payment providers">
                    + Configure payment providers
                </a>
            </div>
        @endif

    </div>
</div>
