@extends('layouts.app')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@push('styles')
<style>
.notifications-page .notification-item {
    display: flex;
    gap: 16px;
    padding: 16px;
    background: var(--bg-card);
    border-radius: 12px;
    margin-bottom: 12px;
    border: 1px solid var(--border-color);
    text-decoration: none;
    color: inherit;
    transition: all 0.2s;
}

.notifications-page .notification-item:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.notifications-page .notification-item.unread {
    background: rgba(124, 58, 237, 0.05);
    border-left: 4px solid var(--primary);
}

.notifications-page .notification-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.notifications-page .notification-icon.primary { background: var(--primary-light); color: var(--primary); }
.notifications-page .notification-icon.warning { background: var(--warning-light); color: var(--warning); }
.notifications-page .notification-icon.success { background: var(--success-light); color: var(--success); }
.notifications-page .notification-icon.info { background: var(--info-light); color: var(--info); }

.notifications-page .notification-content {
    flex: 1;
}

.notifications-page .notification-title {
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 4px;
}

.notifications-page .notification-message {
    color: var(--text-muted);
    font-size: 0.9rem;
    margin-bottom: 8px;
}

.notifications-page .notification-time {
    font-size: 0.8rem;
    color: var(--text-muted);
}

.notifications-page .notification-actions {
    display: flex;
    gap: 8px;
    align-items: flex-start;
}
</style>
@endpush

@section('content')
<div class="notifications-page">
    <div class="card animate-fadeIn">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-bell text-primary"></i>
                Semua Notifikasi
            </h3>
            @if($notifications->where('read_at', null)->count() > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm">
                    <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                </button>
            </form>
            @endif
        </div>
        <div class="card-body">
            @forelse($notifications as $notification)
                @php
                    $icon = match($notification->type) {
                        'task_assigned' => 'fas fa-tasks',
                        'deadline_reminder' => 'fas fa-clock',
                        'evaluation_new' => 'fas fa-star',
                        'announcement' => 'fas fa-bullhorn',
                        default => 'fas fa-bell',
                    };
                    $color = match($notification->type) {
                        'task_assigned' => 'primary',
                        'deadline_reminder' => 'warning',
                        'evaluation_new' => 'success',
                        'announcement' => 'info',
                        default => 'secondary',
                    };
                @endphp
                <a href="{{ route('notifications.read', $notification) }}" 
                   class="notification-item {{ !$notification->read_at ? 'unread' : '' }}">
                    <div class="notification-icon {{ $color }}">
                        <i class="{{ $icon }}"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">{{ $notification->title }}</div>
                        <div class="notification-message">{{ $notification->message }}</div>
                        <div class="notification-time">
                            <i class="fas fa-clock"></i>
                            {{ $notification->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="notification-actions">
                        @if(!$notification->read_at)
                        <span class="badge badge-primary">Baru</span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-bell-slash"></i>
                    </div>
                    <h5 class="empty-state-title">Tidak Ada Notifikasi</h5>
                    <p class="empty-state-text">Belum ada notifikasi untuk ditampilkan.</p>
                </div>
            @endforelse
            
            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
