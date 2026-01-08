<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'program_id',
        'assigned_to',
        'created_by',
        'status',
        'progress',
        'priority',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'date',
        'progress' => 'integer',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeTodo($query)
    {
        return $query->where('status', 'todo');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeDone($query)
    {
        return $query->where('status', 'done');
    }

    public function scopeOverdue($query)
    {
        return $query->where('deadline', '<', now())
            ->where('status', '!=', 'done');
    }

    public function scopeUpcoming($query, $days = 7)
    {
        return $query->whereBetween('deadline', [now(), now()->addDays($days)])
            ->where('status', '!=', 'done');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->deadline && $this->deadline->isPast() && $this->status !== 'done';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'todo' => 'secondary',
            'in_progress' => 'warning',
            'done' => 'success',
            default => 'secondary',
        };
    }

    public function getPriorityBadgeAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'info',
            'medium' => 'primary',
            'high' => 'danger',
            default => 'secondary',
        };
    }
}
