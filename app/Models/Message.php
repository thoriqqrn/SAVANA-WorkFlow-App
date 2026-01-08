<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeBetweenUsers($query, $user1Id, $user2Id)
    {
        return $query->where(function ($q) use ($user1Id, $user2Id) {
            $q->where('sender_id', $user1Id)->where('receiver_id', $user2Id);
        })->orWhere(function ($q) use ($user1Id, $user2Id) {
            $q->where('sender_id', $user2Id)->where('receiver_id', $user1Id);
        });
    }

    // Get unread count for a user
    public static function unreadCountFor($userId): int
    {
        return self::where('receiver_id', $userId)->where('is_read', false)->count();
    }

    // Get conversation list for a user
    public static function getConversations($userId)
    {
        $sentTo = self::where('sender_id', $userId)->pluck('receiver_id');
        $receivedFrom = self::where('receiver_id', $userId)->pluck('sender_id');
        
        $userIds = $sentTo->merge($receivedFrom)->unique();
        
        return User::whereIn('id', $userIds)
            ->where('id', '!=', $userId)
            ->get()
            ->map(function ($user) use ($userId) {
                $lastMessage = self::betweenUsers($userId, $user->id)
                    ->orderByDesc('created_at')
                    ->first();
                
                $unreadCount = self::where('sender_id', $user->id)
                    ->where('receiver_id', $userId)
                    ->where('is_read', false)
                    ->count();
                
                $user->last_message = $lastMessage;
                $user->unread_count = $unreadCount;
                
                return $user;
            })
            ->sortByDesc(fn($u) => $u->last_message?->created_at);
    }
}
