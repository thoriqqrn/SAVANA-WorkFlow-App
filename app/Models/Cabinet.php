<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cabinet extends Model
{
    protected $fillable = ['name', 'year', 'status'];

    protected $casts = [
        'status' => 'string',
    ];

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public static function current()
    {
        return static::active()->latest()->first();
    }
}
