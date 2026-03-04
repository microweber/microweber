# API Resources

## Overview

API Resources provide a transformation layer between your Eloquent models and the JSON responses returned by your application. They allow you to expressively and easily transform models and model collections into JSON.

## When to Use Resources

**ALWAYS use Resource classes instead of:**

- Manual array mapping in controllers
- Returning models directly from controllers
- Using `toArray()` or `toJson()` on models directly

**Resources should be used for:**

- Transforming single models
- Transforming collections of models
- Nested relationships
- API responses
- Inertia.js page props

## Basic Structure

```php
<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
final class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource_id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
```

## Key Principles

### 1. Use @mixin for IDE Support

Always add `@mixin` docblock to get IDE autocompletion for model properties and methods:

```php
/**
 * @mixin User
 */
final class UserResource extends JsonResource
{
    // Now $this-> gives you User model methods and properties
}
```

### 2. Always Use resource_id for Public IDs

**NEVER expose internal database IDs**. Always use `resource_id` (UUID) as `id`:

```php
return [
    'id' => $this->resource_id,  // ✅ Correct
    'id' => $this->id,            // ❌ Wrong - security risk
];
```

### 3. Use resolve() for Non-HTTP Contexts

When passing resources to Inertia or non-API contexts, use `resolve()`:

```php
// Single resource
return Inertia::render('Users/Show', [
    'user' => (new UserResource($user))->resolve(),
]);

// Collection
return Inertia::render('Users/Index', [
    'users' => UserResource::collection($users)->resolve(),
]);
```

### 4. Nested Resources

**ALWAYS use Resource classes for nested entities**, not manual mapping:

```php
// ✅ Correct: Use nested Resource
final class UrlResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource_id,
            'name' => $this->name,
            'recent_forwards' => WebhookForwardResource::collection(
                $this->webhookForwards()->latest()->limit(5)->get()
            ),
        ];
    }
}

// ❌ Wrong: Manual mapping in controller
'recentForwards' => $url->webhookForwards()
    ->latest()
    ->limit(5)
    ->get()
    ->map(fn ($forward) => [
        'id' => $forward->resource_id,
        'status_code' => $forward->status_code,
    ])
    ->all(),
```

### 5. Conditional Attributes

Use conditional methods to include attributes based on context:

```php
public function toArray(Request $request): array
{
    return [
        'id' => $this->resource_id,
        'name' => $this->name,

        // Include when loaded
        'posts' => PostResource::collection($this->whenLoaded('posts')),

        // Include when counted
        'posts_count' => $this->whenCounted('posts'),

        // Include when condition is true
        'secret' => $this->when($request->user()->isAdmin(), 'secret-value'),

        // Include when not null
        'phone' => $this->whenNotNull($this->phone),
    ];
}
```

## Controller Usage Patterns

### Single Resource

```php
public function show(User $user): Response
{
    return Inertia::render('Users/Show', [
        'user' => (new UserResource($user))->resolve(),
    ]);
}
```

### Resource Collection

```php
public function index(): Response
{
    $users = User::with('roles')->get();

    return Inertia::render('Users/Index', [
        'users' => UserResource::collection($users)->resolve(),
    ]);
}
```

### API Endpoints

```php
// For API routes, return directly (no resolve() needed)
public function show(User $user): UserResource
{
    return new UserResource($user);
}

public function index(): AnonymousResourceCollection
{
    return UserResource::collection(User::paginate());
}
```

## Common Patterns

### Eager Loading in Resources

```php
public function toArray(Request $request): array
{
    // Load relationships if needed
    $this->loadMissing('posts', 'roles');

    return [
        'id' => $this->resource_id,
        'posts' => PostResource::collection($this->posts),
        'roles' => RoleResource::collection($this->roles),
    ];
}
```

### Computed Properties

```php
public function toArray(Request $request): array
{
    return [
        'id' => $this->resource_id,
        'name' => $this->name,

        // Use model methods
        'is_active' => $this->isActive(),
        'full_name' => $this->getFullName(),

        // Computed values
        'avatar_url' => url('/avatars/'.$this->avatar),
    ];
}
```

### Relationship Counts

```php
public function toArray(Request $request): array
{
    return [
        'id' => $this->resource_id,
        'name' => $this->name,
        'posts_count' => $this->whenCounted('posts'),
        'comments_count' => $this->whenCounted('comments'),
    ];
}
```

## Anti-Patterns to Avoid

### ❌ Don't Map in Controllers

```php
// ❌ Bad: Manual mapping
public function show(Url $url): Response
{
    return Inertia::render('Urls/Show', [
        'forwards' => $url->forwards->map(fn($f) => [
            'id' => $f->resource_id,
            'status' => $f->status,
        ]),
    ]);
}

// ✅ Good: Use Resource
public function show(Url $url): Response
{
    return Inertia::render('Urls/Show', [
        'forwards' => WebhookForwardResource::collection($url->forwards)->resolve(),
    ]);
}
```

### ❌ Don't Return Models Directly

```php
// ❌ Bad: Exposes all attributes and internal IDs
return Inertia::render('Users/Show', [
    'user' => $user,
]);

// ✅ Good: Use Resource for controlled transformation
return Inertia::render('Users/Show', [
    'user' => (new UserResource($user))->resolve(),
]);
```

### ❌ Don't Skip resolve() in Inertia

```php
// ❌ Bad: Resource not resolved
return Inertia::render('Users/Show', [
    'user' => new UserResource($user),
]);

// ✅ Good: Use resolve()
return Inertia::render('Users/Show', [
    'user' => (new UserResource($user))->resolve(),
]);
```

## Testing Resources

```php
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

final class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_transforms_user_correctly(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $resource = (new UserResource($user))->resolve();

        $this->assertEquals($user->resource_id, $resource['id']);
        $this->assertEquals('John Doe', $resource['name']);
        $this->assertEquals('john@example.com', $resource['email']);
    }

    public function test_it_does_not_expose_internal_id(): void
    {
        $user = User::factory()->create();
        $resource = (new UserResource($user))->resolve();

        $this->assertArrayNotHasKey('internal_id', $resource);
        $this->assertNotEquals($user->id, $resource['id']);
    }
}
```

## Summary

**Key Rules:**

1. ✅ Always use Resource classes for model transformation
2. ✅ Use `resource_id` as `id` in resources
3. ✅ Use `resolve()` when passing to Inertia
4. ✅ Use Resource classes for nested entities
5. ✅ Add `@mixin` docblock for IDE support
6. ❌ Never manually map models in controllers
7. ❌ Never expose internal database IDs
8. ❌ Never return models directly
