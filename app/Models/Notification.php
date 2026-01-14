<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    // Types
    public const TYPE_TASK_ASSIGNED = 'task_assigned';
    public const TYPE_DEADLINE_REMINDER = 'deadline_reminder';
    public const TYPE_EVALUATION_NEW = 'evaluation_new';
    public const TYPE_ANNOUNCEMENT = 'announcement';

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderByDesc('created_at')->limit($limit);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Helpers
    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }

    public function getIconAttribute(): string
    {
        return match($this->type) {
            self::TYPE_TASK_ASSIGNED => 'fas fa-tasks',
            self::TYPE_DEADLINE_REMINDER => 'fas fa-clock',
            self::TYPE_EVALUATION_NEW => 'fas fa-star',
            self::TYPE_ANNOUNCEMENT => 'fas fa-bullhorn',
            default => 'fas fa-bell',
        };
    }

    public function getColorAttribute(): string
    {
        return match($this->type) {
            self::TYPE_TASK_ASSIGNED => 'primary',
            self::TYPE_DEADLINE_REMINDER => 'warning',
            self::TYPE_EVALUATION_NEW => 'success',
            self::TYPE_ANNOUNCEMENT => 'info',
            default => 'secondary',
        };
    }

    // Static creators
    public static function notifyTaskAssigned(Task $task): void
    {
        if (!$task->assigned_to) return;

        self::create([
            'user_id' => $task->assigned_to,
            'type' => self::TYPE_TASK_ASSIGNED,
            'title' => 'Task Baru',
            'message' => "Kamu ditugaskan untuk: {$task->title}",
            'data' => ['task_id' => $task->id],
        ]);
    }

    public static function notifyDeadlineReminder(Task $task): void
    {
        if (!$task->assigned_to || !$task->deadline) return;

        $daysLeft = now()->diffInDays($task->deadline, false);
        
        self::create([
            'user_id' => $task->assigned_to,
            'type' => self::TYPE_DEADLINE_REMINDER,
            'title' => 'Deadline Mendekat',
            'message' => "Task '{$task->title}' jatuh tempo dalam {$daysLeft} hari",
            'data' => ['task_id' => $task->id, 'deadline' => $task->deadline->format('Y-m-d')],
        ]);
    }

    public static function notifyEvaluation(Evaluation $evaluation): void
    {
        self::create([
            'user_id' => $evaluation->user_id,
            'type' => self::TYPE_EVALUATION_NEW,
            'title' => 'Evaluasi Baru',
            'message' => "Kamu mendapat evaluasi dari " . ucfirst($evaluation->evaluator_type),
            'data' => ['evaluation_id' => $evaluation->id, 'period' => $evaluation->period],
        ]);
    }

    public static function notifyAnnouncement(Announcement $announcement, $userId): void
    {
        self::create([
            'user_id' => $userId,
            'type' => self::TYPE_ANNOUNCEMENT,
            'title' => 'Pengumuman Baru',
            'message' => $announcement->title,
            'data' => ['announcement_id' => $announcement->id],
        ]);
    }
}
