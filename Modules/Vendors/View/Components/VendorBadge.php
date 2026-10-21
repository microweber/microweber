<?php

namespace Modules\Vendors\View\Components;

use Illuminate\View\Component;
use Modules\Product\Models\Product;
use Modules\Vendors\Models\Vendor;

/**
 * <x-vendor-badge :content-id="content_id()" /> — a "Sold by {vendor}" link
 * for a product, pointing at that vendor's public shop page.
 *
 * Resolves the product owner (created_by) to an approved Vendor and renders
 * nothing when the product has no vendor (plain store products are unchanged).
 * Templates opt in with a single tag; no core template is rewritten.
 */
class VendorBadge extends Component
{
    public ?Vendor $vendor = null;

    public function __construct($contentId = null)
    {
        if (! $contentId) {
            return;
        }

        $ownerId = (int) Product::withoutGlobalScopes()
            ->whereKey($contentId)
            ->value('created_by');

        if ($ownerId) {
            $this->vendor = Vendor::query()
                ->where('user_id', $ownerId)
                ->where('is_approved', true)
                ->first();
        }
    }

    /** Only render when the product actually belongs to an approved vendor. */
    public function shouldRender(): bool
    {
        return $this->vendor !== null;
    }

    public function render()
    {
        return view('modules.vendors::components.vendor-badge');
    }
}
