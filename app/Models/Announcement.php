<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'has_poll',
        'poll_question',
        'poll_ends_at',
    ];

    protected $casts = [
        'has_poll' => 'boolean',
        'poll_ends_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(AnnouncementComment::class)->latest();
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(AnnouncementReaction::class);
    }

    public function pollOptions(): HasMany
    {
        return $this->hasMany(PollOption::class);
    }

    public function getTotalVotesAttribute(): int
    {
        return $this->pollOptions->sum('votes_count');
    }

    public function hasUserVoted(?int $userId): bool
    {
        if (!$userId) return false;
        return PollVote::whereIn('poll_option_id', $this->pollOptions->pluck('id'))
            ->where('user_id', $userId)
            ->exists();
    }

    public function getUserVoteOptionId(?int $userId): ?int
    {
        if (!$userId) return null;
        $vote = PollVote::whereIn('poll_option_id', $this->pollOptions->pluck('id'))
            ->where('user_id', $userId)
            ->first();
        return $vote?->poll_option_id;
    }

    public function isPollActive(): bool
    {
        if (!$this->has_poll) return false;
        if (!$this->poll_ends_at) return true;
        return $this->poll_ends_at->isFuture();
    }

    public function getReactionCountsAttribute(): array
    {
        return $this->reactions->groupBy('type')
            ->map(fn($group) => $group->count())
            ->toArray();
    }

    public function getUserReaction(?int $userId): ?string
    {
        if (!$userId) return null;
        return $this->reactions->where('user_id', $userId)->first()?->type;
    }
}
