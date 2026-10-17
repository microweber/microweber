<?php

declare(strict_types=1);

namespace MicroweberPackages\ActivityLog\Services;

use Illuminate\Support\Facades\DB;

final class ActivityLogger
{
    /**
     * Record a single audit-trail row.
     *
     * @param string                       $action   Action slug.
     * @param object|null                  $user     Authenticated user (null = anon).
     * @param object|null                  $subject  Acted-on entity (null = collection).
     * @param array<string,mixed>          $metadata Action-specific JSON payload.
     */
    public static function record(
        string $action,
        ?object $user = null,
        ?object $subject = null,
        array $metadata = []
    ): void {
        try {
            $request = function_exists('request') ? request() : null;

            DB::table('activity_log')->insert([
                'user_id'     => self::idOf($user) ?: self::idOf($request?->user()),
                'actor_email' => self::emailOf($user) ?: self::emailOf($request?->user()),
                'action'      => $action,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id'  => self::idOf($subject),
                'ip_address'  => $request?->ip(),
                'user_agent'  => self::truncate($request?->userAgent(), 500),
                'metadata'    => empty($metadata) ? null : json_encode($metadata, JSON_UNESCAPED_UNICODE),
                'created_at'  => now(),
            ]);
        } catch (\Throwable $e) {
            // Audit log is best-effort. Silently swallow — never
            // block the action being audited. Caller controls
            // whether to retry.
        }
    }

    /**
     * Convenience: record a successful login.
     *
     * @param object|null         $user
     * @param array<string, mixed> $metadata
     */
    public static function recordLogin(?object $user, array $metadata = []): void
    {
        self::record('auth.login', user: $user, metadata: $metadata);
    }

    /**
     * Convenience: record a failed login (no user object available).
     *
     * @param array<string, mixed> $metadata
     */
    public static function recordFailedLogin(string $attemptedEmail, array $metadata = []): void
    {
        $request = function_exists('request') ? request() : null;
        try {
            DB::table('activity_log')->insert([
                'user_id'     => null,
                'actor_email' => self::truncate($attemptedEmail, 191),
                'action'      => 'auth.login_failed',
                'ip_address'  => $request?->ip(),
                'user_agent'  => self::truncate($request?->userAgent(), 500),
                'metadata'    => empty($metadata) ? null : json_encode($metadata, JSON_UNESCAPED_UNICODE),
                'created_at'  => now(),
            ]);
        } catch (\Throwable $e) {
            // best-effort
        }
    }

    /**
     * @param object|null $entity
     */
    private static function idOf($entity): ?int
    {
        if (!$entity) {
            return null;
        }
        $id = is_object($entity) && method_exists($entity, 'getKey')
            ? $entity->getKey()
            : ($entity->id ?? null);
        return is_numeric($id) ? (int) $id : null;
    }

    /**
     * @param object|null $entity
     */
    private static function emailOf($entity): ?string
    {
        if (!$entity) {
            return null;
        }
        $email = $entity->email ?? null;
        return is_string($email) ? self::truncate($email, 191) : null;
    }

    private static function truncate(?string $val, int $max): ?string
    {
        if ($val === null) {
            return null;
        }
        return mb_substr($val, 0, $max);
    }
}
