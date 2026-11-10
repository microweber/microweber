# Microweber Activity Log

A lightweight, best-effort audit trail for Microweber. Writes rows to an `activity_log` table recording logins, failed logins, settings changes, role grants, mass-deletes and any other action you want to track — with the actor, subject, IP, user-agent and a JSON metadata payload.

## Features

- Static `ActivityLogger` service — no instantiation, no facade needed.
- **Best-effort:** every write is wrapped in a `try/catch` and silently swallowed, so an audit failure can never block the action being audited.
- Denormalized `actor_email` so the trail survives user deletion.
- Polymorphic subject (`subject_type` / `subject_id`) for "what was acted on".
- Indexed for the per-action and per-user drill-down views.

## Installation

```bash
composer require microweber-packages/microweber-activity-log
```

This package ships the `ActivityLogger` service only. The `activity_log` table is created by the application migration `database/migrations/*_create_activity_log_table.php`; run your migrations before logging:

```bash
php artisan migrate
```

## Usage

```php
use MicroweberPackages\ActivityLog\Services\ActivityLogger;

// Record an arbitrary action (user/subject/metadata all optional)
ActivityLogger::record(
    action: 'settings.update',
    user: auth()->user(),
    subject: $option,                 // any object with getKey()/id + optional email
    metadata: ['key' => 'website_name', 'from' => 'Old', 'to' => 'New'],
);

// Convenience: a successful login
ActivityLogger::recordLogin(auth()->user(), ['via' => 'password']);

// Convenience: a failed login (no user object — just the attempted email)
ActivityLogger::recordFailedLogin('attacker@example.com', ['reason' => 'bad_password']);
```

### Method signatures

```php
ActivityLogger::record(
    string $action,
    ?object $user = null,
    ?object $subject = null,
    array $metadata = []
): void;

ActivityLogger::recordLogin(?object $user, array $metadata = []): void;
ActivityLogger::recordFailedLogin(string $attemptedEmail, array $metadata = []): void;
```

`$user` and `$subject` are duck-typed: the logger reads the id via `getKey()` (falling back to `->id`) and the email via `->email`. When `$user` is `null` the authenticated request user is used as a fallback. The request IP and user-agent are captured automatically (UA truncated to 500 chars).

## Table schema

`activity_log` columns: `id`, `user_id` (nullable), `actor_email`, `action`, `subject_type` (nullable), `subject_id` (nullable), `ip_address`, `user_agent`, `metadata` (JSON), `created_at`. Indexed on `(action, created_at)`, `(user_id, created_at)` and `(subject_type, subject_id)`.

## Key classes

- `MicroweberPackages\ActivityLog\Services\ActivityLogger` — the `final` static logger.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov

## License

MIT
