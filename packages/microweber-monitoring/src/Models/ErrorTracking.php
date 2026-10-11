<?php

namespace MicroweberPackages\Monitoring\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ErrorTracking extends Model
{
    use HasFactory;

    protected $table = 'error_tracking';

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\MicroweberPackages\Monitoring\Models\ErrorTrackingFactory::new();
    }

    protected $fillable = [
        'level',
        'message',
        'exception_class',
        'file',
        'line',
        'code',
        'trace',
        'url',
        'method',
        'user_id',
        'user_ip',
        'user_agent',
        'server_data',
        'context',
        'is_resolved',
        'occurrence_count',
        'last_occurred_at',
        'resolved_at',
        'resolved_by',
        'resolution_notes',
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
        'occurrence_count' => 'integer',
        'line' => 'integer',
        'last_occurred_at' => 'datetime',
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who encountered the error.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * Get the user who resolved the error.
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'resolved_by');
    }

    /**
     * Scope for unresolved errors.
     */
    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    /**
     * Scope for resolved errors.
     */
    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    /**
     * Scope for critical errors.
     */
    public function scopeCritical($query)
    {
        return $query->where('level', 'critical');
    }

    /**
     * Scope for errors from today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope for errors from this week.
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope for errors from this month.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
    }

    /**
     * Mark the error as resolved.
     */
    public function markAsResolved(?string $notes = null, ?int $userId = null): bool
    {
        return $this->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => $userId ?? auth()->id(),
            'resolution_notes' => $notes,
        ]);
    }

    /**
     * Increment occurrence count.
     */
    public function incrementOccurrence(): bool
    {
        return $this->update([
            'occurrence_count' => $this->occurrence_count + 1,
            'last_occurred_at' => now(),
        ]);
    }

    /**
     * Get the formatted error location.
     */
    public function getLocationAttribute(): string
    {
        return $this->file ? basename($this->file) . ':' . $this->line : 'Unknown';
    }

    /**
     * Get the formatted trace (limited to first 10 lines).
     */
    public function getFormattedTraceAttribute(): string
    {
        if (empty($this->trace)) {
            return '';
        }

        $lines = explode("\n", $this->trace);
        return implode("\n", array_slice($lines, 0, 10));
    }

    /**
     * Get the severity color for Filament UI.
     */
    public function getSeverityColorAttribute(): string
    {
        return match ($this->level) {
            'critical' => 'danger',
            'error' => 'danger',
            'warning' => 'warning',
            'notice' => 'warning',
            'info' => 'info',
            default => 'gray',
        };
    }
}
