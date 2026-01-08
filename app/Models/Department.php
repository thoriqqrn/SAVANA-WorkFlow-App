<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = ['name', 'description', 'cabinet_id', 'status'];

    protected $casts = [
        'status' => 'string',
    ];

    public function cabinet(): BelongsTo
    {
        return $this->belongsTo(Cabinet::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    public function timelines(): HasMany
    {
        return $this->hasMany(Timeline::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getHeadAttribute()
    {
        return $this->users()->whereHas('role', function ($q) {
            $q->where('name', 'kabinet');
        })->first();
    }
}
