<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    protected $fillable = [
        'name',
        'description',
        'department_id',
        'created_by',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'program_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    // Person In Charge (PIC) - can be multiple
    public function pics(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'program_pics')
            ->withTimestamps();
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function timelines(): HasMany
    {
        return $this->hasMany(Timeline::class);
    }

    public function getProgressAttribute(): int
    {
        $tasks = $this->tasks;
        if ($tasks->isEmpty()) return 0;
        
        return (int) round($tasks->avg('progress'));
    }

    public function getLeaderAttribute()
    {
        return $this->members()->wherePivot('role', 'leader')->first();
    }

    // Check if user is member or PIC
    public function hasMemberOrPic($userId): bool
    {
        return $this->members()->where('user_id', $userId)->exists()
            || $this->pics()->where('user_id', $userId)->exists();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    // Get programs where user is member or PIC
    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->whereHas('members', fn($m) => $m->where('user_id', $userId))
              ->orWhereHas('pics', fn($p) => $p->where('user_id', $userId));
        });
    }
}

