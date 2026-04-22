<?php

declare(strict_types=1);

namespace Modules\ContactForm\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\ContactForm\Models\Form;

/**
 * @mixin Form
 */
final class FormResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $viewer = $request->user();
        $isAdmin = $viewer !== null && (int) $viewer->is_admin === 1;

        $data = [
            'id' => (int) $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'module_id' => $this->module_id,
            'list_id' => $this->list_id !== null ? (int) $this->list_id : null,
            'description' => $this->description,
            'confirmation_message' => $this->confirmation_message,
            'is_active' => $this->is_active !== null ? (bool) $this->is_active : null,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];

        // Notification recipient list is admin-only — prevents harvesting
        // inbound email addresses of staff via the public API.
        if ($isAdmin) {
            $data['emails_notifications'] = $this->emails_notifications;
            $data['emails_notifications_subject'] = $this->emails_notifications_subject;
        }

        return $data;
    }
}
