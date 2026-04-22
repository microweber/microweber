<?php

declare(strict_types=1);

namespace Modules\Newsletter\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Modules\Newsletter\Models\NewsletterSubscriber;

/**
 * @mixin NewsletterSubscriber
 */
final class NewsletterSubscriberResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user() && (int) $request->user()->is_admin === 1;

        $payload = [
            'id' => (int) $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'status' => $this->status,
            'is_subscribed' => (bool) $this->is_subscribed,
            'list_id' => $this->list_id !== null ? (int) $this->list_id : null,
            'subscribed_at' => $this->toIso($this->subscribed_at),
            'unsubscribed_at' => $this->toIso($this->unsubscribed_at),
            'created_at' => $this->toIso($this->created_at),
            'updated_at' => $this->toIso($this->updated_at),
        ];

        if (!$isAdmin) {
            unset($payload['email']);
        }

        return $payload;
    }

    private function toIso(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        if ($value instanceof Carbon) {
            return $value->toIso8601String();
        }

        return Carbon::parse((string) $value)->toIso8601String();
    }
}
