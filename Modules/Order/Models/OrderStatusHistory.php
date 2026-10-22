<?php

namespace Modules\Order\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MicroweberPackages\User\Models\User;

class OrderStatusHistory extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\Order\Events\OrderStatusHistoryWasCreated::class,
        'updated' => \Modules\Order\Events\OrderStatusHistoryWasUpdated::class,
        'deleted' => \Modules\Order\Events\OrderStatusHistoryWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

    use HasFactory;

    protected $table = 'order_status_history';

    protected $fillable = [
        'order_id',
        'old_status',
        'new_status',
        'user_id',
        'note',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
