<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class MotherAccount extends Model
{
    protected $fillable = [
        'email',
        'max_capacity',
        'lifespan_days',
        'start_date',
        'expiry_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'expiry_date' => 'date',
    ];

    // --- Relationships ---

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    public function activeAccounts(): HasMany
    {
        return $this->hasMany(Account::class)->where('status', 'active');
    }

    public function alerts()
    {
        return $this->morphMany(Alert::class, 'alertable');
    }

    // --- Computed Attributes ---

    public function getSeatsUsedAttribute(): int
    {
        return $this->activeAccounts()->count();
    }

    public function getSeatsRemainingAttribute(): int
    {
        return max(0, $this->max_capacity - $this->seats_used);
    }

    public function getDaysUntilExpiryAttribute(): int
    {
        return max(0, (int) now()->startOfDay()->diffInDays($this->expiry_date, false));
    }

    public function getIsExpiringSoonAttribute(): bool
    {
        return $this->status === 'active' && $this->days_until_expiry <= 2;
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date->isPast() || $this->status === 'expired';
    }

    // --- Scopes ---

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeExpiringSoon($query)
    {
        return $query->active()->where('expiry_date', '<=', now()->addDays(2));
    }

    public function scopeWithAvailableCapacity($query)
    {
        return $query->active()->whereRaw(
            '(SELECT COUNT(*) FROM accounts WHERE accounts.mother_account_id = mother_accounts.id AND accounts.status = "active") < mother_accounts.max_capacity'
        );
    }

    public function scopeOrderByLongestRemaining($query)
    {
        return $query->orderByDesc('expiry_date');
    }
}
