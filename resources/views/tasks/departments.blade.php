@extends('layouts.app')

@section('title', 'Task Management')
@section('page-title', 'Task Management')

@push('styles')
<style>
.dept-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.dept-card {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow), var(--shadow-primary);
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.dept-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg), 0 8px 24px -4px var(--primary-light);
}

.dept-card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.dept-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
}

.dept-icon.global {
    background: linear-gradient(135deg, var(--primary), var(--primary-soft));
}

.dept-icon.dept {
    background: linear-gradient(135deg, #3B82F6, #60A5FA);
}

.dept-info {
    flex: 1;
    min-width: 0;
}

.dept-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.dept-desc {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.dept-stats {
    display: flex;
    gap: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
}

.stat-item i {
    font-size: 1rem;
}

.stat-item.total {
    color: var(--text-secondary);
}

.stat-item.pending {
    color: var(--warning);
}

.global-card {
    background: linear-gradient(135deg, var(--primary), var(--primary-hover));
    color: white;
}

.global-card .dept-name,
.global-card .dept-desc,
.global-card .stat-item {
    color: white;
}

.global-card .dept-icon {
    background: rgba(255, 255, 255, 0.2);
}

.global-card .dept-stats {
    border-top-color: rgba(255, 255, 255, 0.2);
}

@media (max-width: 768px) {
    .dept-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="dept-grid">
    <!-- Global Tasks Card -->
    <a href="{{ route('tasks.global') }}" class="dept-card global-card">
        <div class="dept-card-header">
            <div class="dept-icon global">
                <i class="fas fa-globe"></i>
            </div>
            <div class="dept-info">
                <div class="dept-name">Global Tasks</div>
                <div class="dept-desc">Tugas untuk semua departemen</div>
            </div>
        </div>
        <div class="dept-stats">
            <div class="stat-item total">
                <i class="fas fa-tasks"></i>
                <span>{{ $globalTasksCount }} tugas</span>
            </div>
            <div class="stat-item pending">
                <i class="fas fa-clock"></i>
                <span>{{ $globalPendingCount }} aktif</span>
            </div>
        </div>
    </a>

    <!-- Department Cards -->
    @foreach($departments as $department)
    <a href="{{ route('tasks.department', $department) }}" class="dept-card">
        <div class="dept-card-header">
            <div class="dept-icon dept">
                <i class="fas fa-building"></i>
            </div>
            <div class="dept-info">
                <div class="dept-name">{{ $department->name }}</div>
                <div class="dept-desc">{{ $department->cabinet?->name ?? 'No Cabinet' }}</div>
            </div>
        </div>
        <div class="dept-stats">
            <div class="stat-item total">
                <i class="fas fa-tasks"></i>
                <span>{{ $department->total_tasks ?? 0 }} tugas</span>
            </div>
            <div class="stat-item pending">
                <i class="fas fa-clock"></i>
                <span>{{ $department->pending_tasks ?? 0 }} aktif</span>
            </div>
        </div>
    </a>
    @endforeach
</div>
@endsection
