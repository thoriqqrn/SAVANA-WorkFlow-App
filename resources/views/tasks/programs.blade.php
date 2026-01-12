@extends('layouts.app')

@section('title', $department->name . ' - Programs')
@section('page-title', $department->name)

@push('styles')
<style>
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
}

.breadcrumb a {
    color: var(--text-muted);
    text-decoration: none;
}

.breadcrumb a:hover {
    color: var(--primary);
}

.breadcrumb .separator {
    color: var(--text-muted);
}

.breadcrumb .current {
    color: var(--text-primary);
    font-weight: 500;
}

.program-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
}

.program-card {
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

.program-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg), 0 8px 24px -4px var(--primary-light);
}

.program-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.program-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
    background: linear-gradient(135deg, #10B981, #34D399);
}

.program-icon.dept-task {
    background: linear-gradient(135deg, var(--primary), var(--primary-soft));
}

.program-info {
    flex: 1;
    min-width: 0;
}

.program-name {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.program-desc {
    font-size: 0.8rem;
    color: var(--text-muted);
}

.program-progress {
    margin-top: auto;
}

.progress-bar-wrapper {
    height: 6px;
    background: var(--gray-200);
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 8px;
}

.progress-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary), var(--primary-soft));
    border-radius: 3px;
    transition: width 0.5s ease;
}

.program-stats {
    display: flex;
    justify-content: space-between;
    font-size: 0.8rem;
}

.stat-done {
    color: var(--success);
}

.stat-active {
    color: var(--warning);
}

.dept-tasks-card {
    background: linear-gradient(135deg, var(--primary), var(--primary-hover));
    color: white;
}

.dept-tasks-card .program-name,
.dept-tasks-card .program-desc,
.dept-tasks-card .stat-done,
.dept-tasks-card .stat-active {
    color: white;
}

.dept-tasks-card .progress-bar-wrapper {
    background: rgba(255, 255, 255, 0.2);
}

.dept-tasks-card .progress-bar-fill {
    background: white;
}

@media (max-width: 768px) {
    .program-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<nav class="breadcrumb">
    <a href="{{ route('tasks.index') }}">
        <i class="fas fa-tasks"></i> Tasks
    </a>
    <span class="separator">/</span>
    <span class="current">{{ $department->name }}</span>
</nav>

<div class="program-grid">
    <!-- Department Tasks Card -->
    <a href="{{ route('tasks.department.tasks', $department) }}" class="program-card dept-tasks-card">
        <div class="program-header">
            <div class="program-icon dept-task">
                <i class="fas fa-folder"></i>
            </div>
            <div class="program-info">
                <div class="program-name">Tugas Departemen</div>
                <div class="program-desc">Tugas khusus {{ $department->name }}</div>
            </div>
        </div>
        <div class="program-progress">
            @php
                $doneCount = \App\Models\Task::forDepartment($department->id)->done()->count();
                $progress = $deptTasksCount > 0 ? round(($doneCount / $deptTasksCount) * 100) : 0;
            @endphp
            <div class="progress-bar-wrapper">
                <div class="progress-bar-fill" style="width: {{ $progress }}%"></div>
            </div>
            <div class="program-stats">
                <span class="stat-done"><i class="fas fa-check"></i> {{ $doneCount }} selesai</span>
                <span class="stat-active"><i class="fas fa-clock"></i> {{ $deptPendingCount }} aktif</span>
            </div>
        </div>
    </a>

    <!-- Program Cards -->
    @foreach($programs as $program)
    <a href="{{ route('tasks.program', $program) }}" class="program-card">
        <div class="program-header">
            <div class="program-icon">
                <i class="fas fa-project-diagram"></i>
            </div>
            <div class="program-info">
                <div class="program-name">{{ $program->name }}</div>
                <div class="program-desc">{{ Str::limit($program->description, 50) }}</div>
            </div>
        </div>
        <div class="program-progress">
            @php
                $doneCount = $program->tasks()->done()->count();
                $progress = $program->total_tasks > 0 ? round(($doneCount / $program->total_tasks) * 100) : 0;
            @endphp
            <div class="progress-bar-wrapper">
                <div class="progress-bar-fill" style="width: {{ $progress }}%"></div>
            </div>
            <div class="program-stats">
                <span class="stat-done"><i class="fas fa-check"></i> {{ $doneCount }} selesai</span>
                <span class="stat-active"><i class="fas fa-clock"></i> {{ $program->pending_tasks ?? 0 }} aktif</span>
            </div>
        </div>
    </a>
    @endforeach
</div>

@if($programs->isEmpty())
<div class="card mt-4">
    <div class="card-body text-center py-5">
        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
        <h4>Belum ada program</h4>
        <p class="text-muted">Departemen ini belum memiliki program kerja</p>
    </div>
</div>
@endif
@endsection
