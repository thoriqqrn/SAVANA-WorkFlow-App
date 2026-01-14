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
        'department_id', // For department-level tasks
        'assigned_to',
        'created_by',
        'status',
        'progress',
        'priority',
        'deadline',
        'is_global', // For global tasks
    ];

    protected $casts = [
        'deadline' => 'date',
        'progress' => 'integer',
        'is_global' => 'boolean',
    ];

    public const STATUSES = ['todo', 'in_progress', 'pending', 'done'];

    protected static function boot()
    {
        parent::boot();

        // Notify assignee when task is created
        static::created(function ($task) {
            if ($task->assigned_to) {
                Notification::notifyTaskAssigned($task);
            }
        });
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeTodo($query)
    {
        return $query->where('status', 'todo');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDone($query)
    {
        return $query->where('status', 'done');
    }

    public function scopeGlobal($query)
    {
        return $query->where('is_global', true);
    }

    public function scopeForDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId)->whereNull('program_id');
    }

    public function scopeForProgram($query, $programId)
    {
        return $query->where('program_id', $programId);
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

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'todo' => 'To Do',
            'in_progress' => 'In Progress',
            'pending' => 'Pending',
            'done' => 'Done',
            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'todo' => '#6B7280',
            'in_progress' => '#F59E0B',
            'pending' => '#8B5CF6',
            'done' => '#10B981',
            default => '#6B7280',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'todo' => 'secondary',
            'in_progress' => 'warning',
            'pending' => 'primary',
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

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            default => 'Unknown',
        };
    }
}
