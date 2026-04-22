<?php

namespace Modules\WordPressMigration\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent model for a single in-flight WordPress migration.
 *
 * The row lives as long as the migration matters to the operator —
 * create on first probe, update on re-probe, update on each
 * worker tick, retain on completion so the history surface can
 * render "imported N posts from example.com".
 *
 * `source_url_hash` is the sha256 of `source_url` and is the
 * real uniqueness key; callers should use the repository's
 * `storeProbeResult()` instead of touching hashes by hand.
 *
 * @property int $id
 * @property string $source_url
 * @property string $source_url_hash
 * @property string $source_host
 * @property string $status
 * @property string|null $mode
 * @property \Illuminate\Database\Eloquent\Casts\ArrayObject|null $probe_result
 * @property \Illuminate\Support\Carbon|null $last_probed_at
 * @property \Illuminate\Database\Eloquent\Casts\ArrayObject|null $options
 * @property \Illuminate\Database\Eloquent\Casts\ArrayObject|null $progress
 * @property string|null $encrypted_credentials  Decrypted automatically when read.
 * @property \Illuminate\Support\Carbon|null $credentials_expire_at
 * @property string|null $last_error
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $finished_at
 */
class WordPressMigrationJob extends Model
{
    public const STATUS_PROBING = 'probing';
    public const STATUS_READY = 'ready';
    public const STATUS_UNREACHABLE = 'unreachable';
    public const STATUS_RUNNING = 'running';
    public const STATUS_FINISHED = 'finished';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELED = 'canceled';

    public const TERMINAL_STATUSES = [
        self::STATUS_FINISHED,
        self::STATUS_FAILED,
        self::STATUS_CANCELED,
    ];

    protected $table = 'wp_migration_jobs';

    protected $fillable = [
        'source_url',
        'source_url_hash',
        'source_host',
        'status',
        'mode',
        'probe_result',
        'last_probed_at',
        'options',
        'progress',
        'encrypted_credentials',
        'credentials_expire_at',
        'last_error',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'probe_result' => AsArrayObject::class,
        'options' => AsArrayObject::class,
        'progress' => AsArrayObject::class,
        'encrypted_credentials' => 'encrypted',
        'last_probed_at' => 'datetime',
        'credentials_expire_at' => 'datetime',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    protected $hidden = [
        'encrypted_credentials',
        'source_url_hash',
    ];

    public function isTerminal(): bool
    {
        return in_array($this->status, self::TERMINAL_STATUSES, true);
    }

    public function hasValidCredentials(): bool
    {
        if ($this->encrypted_credentials === null || $this->encrypted_credentials === '') {
            return false;
        }
        if ($this->credentials_expire_at === null) {
            return false;
        }
        return $this->credentials_expire_at->isFuture();
    }
}
