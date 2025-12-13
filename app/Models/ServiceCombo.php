<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServiceCombo extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'name',
        'description',
        'original_price',
        'discounted_price',
        'discount_percentage',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'original_price' => 'decimal:2',
            'discounted_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'combo_service')->withTimestamps();
    }
}
