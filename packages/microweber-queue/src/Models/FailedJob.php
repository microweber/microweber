<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent model for the Laravel `failed_jobs` table.
 *
 * @property int $id
 * @property string $uuid
 * @property string $connection
 * @property string $queue
 * @property string $payload
 * @property string $exception
 * @property \Illuminate\Support\Carbon|null $failed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 */
class FailedJob extends Model
{
    protected $table = 'failed_jobs';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'connection',
        'queue',
        'payload',
        'exception',
        'failed_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'failed_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public $timestamps = false;

    /**
     * Display name of the failed job class from the payload.
     */
    public function getDisplayNameAttribute(): string
    {
        $payload = $this->decodedPayload();

        $displayName = $payload['displayName'] ?? null;
        if (is_string($displayName) && $displayName !== '') {
            return $displayName;
        }

        $data = $payload['data'] ?? null;
        if (is_array($data)) {
            $commandName = $data['commandName'] ?? null;
            if (is_string($commandName) && $commandName !== '') {
                return $commandName;
            }
        }

        return 'Unknown job';
    }

    /**
     * First line / short excerpt of the exception.
     */
    public function getExceptionSummaryAttribute(): string
    {
        $exception = $this->exception;
        if ($exception === '') {
            return '';
        }

        $lines = preg_split('/\R/', $exception) ?: [];
        $first = trim((string) ($lines[0] ?? ''));

        return mb_strlen($first) > 200 ? mb_substr($first, 0, 200) . '…' : $first;
    }

    /**
     * @return array<string, mixed>
     */
    public function decodedPayload(): array
    {
        if ($this->payload === '') {
            return [];
        }

        $decoded = json_decode($this->payload, true);

        return is_array($decoded) ? $decoded : [];
    }
}
