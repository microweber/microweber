<?php

declare(strict_types=1);

namespace MicroweberPackages\User\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\User\Models\User;

/**
 * @mixin User
 */
final class UserApiResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'thumbnail' => $this->thumbnail,
            'is_active' => (int) $this->is_active,
            'is_admin' => (int) $this->is_admin,
            'is_verified' => (int) $this->is_verified,
            'is_public' => (int) $this->is_public,
            'role' => $this->role,
            'profile_url' => $this->profile_url,
            'website_url' => $this->website_url,
            'last_login' => $this->last_login,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
