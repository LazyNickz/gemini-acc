<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = [
        'type',
        'alertable_type',
        'alertable_id',
        'message',
        'severity',
        'resolved',
        'resolved_at',
    ];

    protected $casts = [
        'resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    // --- Relationships ---

    public function alertable()
    {
        return $this->morphTo();
    }

    // --- Scopes ---

    public function scopeUnresolved($query)
    {
        return $query->where('resolved', false);
    }

    public function scopeResolved($query)
    {
        return $query->where('resolved', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    // --- Methods ---

    public function resolve(): void
    {
        $this->update([
            'resolved' => true,
            'resolved_at' => now(),
        ]);
    }
}
