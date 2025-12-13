<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JobRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'worker_id',
        'service_id',
        'title',
        'description',
        'status',
        'priority',
        'budget',
        'final_price',
        'scheduled_at',
        'completed_at',
        'location',
        'user_notes',
        'worker_notes',
    ];

    protected function casts(): array
    {
        return [
            'budget' => 'decimal:2',
            'final_price' => 'decimal:2',
            'scheduled_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'user_id');
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(WorkerProfile::class, 'worker_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
