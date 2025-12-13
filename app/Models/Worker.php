<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'rating',
        'completed_jobs',
        'recommended_by',
        'is_verified',
        'is_available',
        'specializations',
        'location',
        'hourly_rate',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'decimal:2',
            'hourly_rate' => 'decimal:2',
            'is_verified' => 'boolean',
            'is_available' => 'boolean',
            'specializations' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'worker_service')->withPivot('price', 'description')->withTimestamps();
    }

    public function jobRequests(): HasMany
    {
        return $this->hasMany(JobRequest::class);
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class)->withPivot('awarded_by', 'reason')->withTimestamps();
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(WorkerPortfolio::class);
    }

    public function serviceCombos(): HasMany
    {
        return $this->hasMany(ServiceCombo::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
