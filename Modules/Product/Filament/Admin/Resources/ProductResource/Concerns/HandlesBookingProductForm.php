<?php

namespace Modules\Product\Filament\Admin\Resources\ProductResource\Concerns;

/**
 * Shared Create/Edit handling for the "Bookable product" form section.
 *
 * The per-product booking configuration lives in the optional Booking module's
 * `booking_product_settings` table, so every reference to that model is guarded
 * with class_exists(): the toggle still maps to the `booking_product` subtype
 * when Booking is disabled, it just won't persist the detailed settings.
 */
trait HandlesBookingProductForm
{
    /** Booking form field keys that are NOT Product attributes. */
    protected function bookingFormKeys(): array
    {
        return [
            'is_bookable', 'booking_type', 'booking_capacity', 'booking_price_unit',
            'booking_unit_price', 'booking_min_duration', 'booking_max_duration',
            'booking_slot_minutes', 'booking_opens_at', 'booking_closes_at',
            'booking_buffer_minutes', 'booking_check_in_time', 'booking_check_out_time',
        ];
    }

    /** The booking form subset stashed during mutate, consumed after save. */
    protected array $bookingFormData = [];

    /**
     * Pull the booking fields out of the form payload (they are not Product
     * columns) and remember whether the product is bookable.
     */
    protected function extractBookingFormData(array &$data): bool
    {
        $isBookable = !empty($data['is_bookable']);

        $this->bookingFormData = [];
        foreach ($this->bookingFormKeys() as $key) {
            if (array_key_exists($key, $data)) {
                $this->bookingFormData[$key] = $data[$key];
                unset($data[$key]);
            }
        }

        return $isBookable;
    }

    /** Whether the just-submitted form marked the product bookable. */
    protected function stashedIsBookable(): bool
    {
        return !empty($this->bookingFormData['is_bookable']);
    }

    /** Persist the stashed booking settings for a saved product. */
    protected function persistBookingSettings($record, bool $isBookable): void
    {
        $model = self::bookingSettingModel();
        if ($model === null || !$record) {
            return;
        }

        $model::syncFromForm((int) $record->getKey(), $this->bookingFormData, $isBookable);
    }

    /** Reflect a stored settings row back into the Edit form fields. */
    protected function fillBookingFormData($record, array &$data): void
    {
        $data['is_bookable'] = ($data['subtype'] ?? 'product') === 'booking_product';

        $model = self::bookingSettingModel();
        if ($model === null || !$record) {
            return;
        }

        $setting = $model::where('product_id', $record->getKey())->first();
        if ($setting) {
            $data = array_merge($data, $setting->toFormData());
        }
    }

    /** @return class-string|null The Booking settings model when the module is present. */
    protected static function bookingSettingModel(): ?string
    {
        $class = '\\Modules\\Booking\\Models\\BookingProductSetting';

        return class_exists($class) ? $class : null;
    }
}
