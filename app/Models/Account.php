<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Account extends Model
{
    protected $fillable = [
        'email',
        'mother_account_id',
        'buyer_id',
        'plan_duration_days',
        'plan_start_date',
        'plan_expiry_date',
        'status',
        'assigned_at',
    ];

    protected $casts = [
        'plan_start_date' => 'date',
        'plan_expiry_date' => 'date',
        'assigned_at' => 'datetime',
    ];

    // --- Relationships ---

    public function motherAccount(): BelongsTo
    {
        return $this->belongsTo(MotherAccount::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function alerts()
    {
        return $this->morphMany(Alert::class, 'alertable');
    }

    // --- Computed Attributes ---

    public function getPlanDaysRemainingAttribute(): int
    {
        return max(0, (int) now()->startOfDay()->diffInDays($this->plan_expiry_date, false));
    }

    public function getIsPlanExpiringSoonAttribute(): bool
    {
        return in_array($this->status, ['active', 'unassigned']) && $this->plan_days_remaining <= 2 && $this->plan_days_remaining > 0;
    }

    public function getIsPlanExpiredAttribute(): bool
    {
        return $this->plan_expiry_date->isPast();
    }

    // --- Scopes ---

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUnassigned($query)
    {
        return $query->where('status', 'unassigned');
    }

    public function scopeCooldown($query)
    {
        return $query->where('status', 'cooldown');
    }

    public function scopePlanExpiringSoon($query)
    {
        return $query->whereIn('status', ['active', 'unassigned'])
            ->where('plan_expiry_date', '<=', now()->addDays(2))
            ->where('plan_expiry_date', '>=', now());
    }

    public function scopeNeedingTransfer($query)
    {
        return $query->where('status', 'unassigned');
    }
}
