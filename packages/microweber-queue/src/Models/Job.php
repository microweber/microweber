<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent model for the Laravel `jobs` table.
 *
 * Standalone-safe: extends Illuminate\Database\Eloquent\Model only.
 *
 * @property int $id
 * @property string $queue
 * @property string $payload
 * @property int $attempts
 * @property int|null $reserved_at
 * @property int $available_at
 * @property int $created_at
 * @property int|null $reserved
 * @property int|null $mw_processed
 * @property string|null $job_hash
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Job extends Model
{
    protected $table = 'jobs';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'queue',
        'payload',
        'attempts',
        'reserved_at',
        'available_at',
        'created_at',
        'reserved',
        'mw_processed',
        'job_hash',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'attempts' => 'integer',
            'reserved_at' => 'integer',
            'available_at' => 'integer',
            'created_at' => 'integer',
            'reserved' => 'integer',
            'mw_processed' => 'integer',
            'updated_at' => 'datetime',
        ];
    }

    public $timestamps = false;

    /**
     * Display name of the queued job class from the payload.
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
