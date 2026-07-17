<?php

declare(strict_types=1);

namespace MicroweberPackages\SocialLogin\Contracts;

/**
 * A plain data-transfer object carrying the normalised social user info
 * returned from any OAuth provider after a successful callback.
 */
class SocialUserResult
{
    public function __construct(
        public readonly string $provider,
        public readonly ?string $id,
        public readonly ?string $email,
        public readonly ?string $name,
        public readonly ?string $nickname,
        public readonly ?string $avatar,
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
    ) {}

    /**
     * Build from a Laravel Socialite user object.
     */
    public static function fromSocialiteUser(string $provider, \Laravel\Socialite\Contracts\User $user): self
    {
        $name = $user->getName();
        $firstName = null;
        $lastName = null;

        if ($name !== null && $name !== '') {
            $parts = explode(' ', $name, 2);
            $firstName = $parts[0];
            $lastName = $parts[1] ?? null;
        }

        return new self(
            provider: $provider,
            id: (string) $user->getId(),
            email: $user->getEmail(),
            name: $name,
            nickname: $user->getNickname(),
            avatar: $user->getAvatar(),
            firstName: $firstName,
            lastName: $lastName,
        );
    }
}