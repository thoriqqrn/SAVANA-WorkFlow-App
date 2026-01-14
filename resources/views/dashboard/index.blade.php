@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
.date-header {
    background: linear-gradient(135deg, var(--primary), var(--primary-soft));
    border-radius: 16px;
    padding: 24px;
    color: white;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}
.date-header .date-left {
    display: flex;
    align-items: center;
    gap: 16px;
}
.date-header .date-day {
    font-size: 3rem;
    font-weight: 700;
    line-height: 1;
}
.date-header .date-info {
    font-size: 1rem;
    opacity: 0.9;
}
.date-header .greeting {
    font-size: 1.125rem;
    font-weight: 600;
}
/* Quick Actions */
.quick-actions {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}
.quick-actions .btn {
    flex: 1;
    min-width: 120px;
}
/* Stat cards */
.stat-mini {
    padding: 20px;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 16px;
}
.stat-mini .stat-icon {
    width: 48px;
    height: 48px;
    font-size: 1.25rem;
}
.stat-mini .stat-value {
    font-size: 1.75rem;
    font-weight: 700;
}
.stat-mini .stat-label {
    font-size: 0.875rem;
    color: var(--text-muted);
}
/* Task Progress */
.progress-circle {
    width: 140px;
    height: 140px;
    position: relative;
}
.progress-legend {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.progress-legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
}
.progress-legend-item .dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}
@media (max-width: 768px) {
    .date-header {
        flex-direction: column;
        text-align: center;
        padding: 16px;
    }
    .date-header .date-left {
        flex-direction: column;
        gap: 8px;
    }
    .date-header .date-day {
        font-size: 2.5rem;
    }
    .date-header .date-info {
        font-size: 0.9rem;
    }
    .quick-actions {
        flex-direction: column;
    }
    .quick-actions .btn {
        width: 100%;
        flex: unset;
    }
    .stat-mini {
        flex-direction: column;
        text-align: center;
        padding: 16px;
        gap: 10px;
    }
    .stat-mini .stat-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    .stat-mini .stat-value {
        font-size: 1.5rem;
    }
    .stat-mini .stat-label {
        font-size: 0.8rem;
    }
    .progress-circle {
        width: 120px;
        height: 120px;
    }
    .card-body.d-flex {
        flex-direction: column;
        gap: 1rem !important;
    }
    .progress-legend {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
    }
    .progress-legend-item {
        font-size: 0.75rem;
    }
    .col-6.col-lg-3 {
        flex: 0 0 50%;
        max-width: 50%;
    }
    .col-12.col-md-5,
    .col-12.col-md-7 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    .row .col-12.col-lg-8,
    .row .col-12.col-lg-4 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}

@media (max-width: 480px) {
    .date-header .date-day {
        font-size: 2rem;
    }
    .stat-mini .stat-value {
        font-size: 1.25rem;
    }
    .col-6.col-lg-3 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
</style>
@endpush

@section('content')
<!-- Date Header -->
<div class="date-header animate-fadeIn">
    <div class="date-left">
        <div class="date-day">{{ now()->format('d') }}</div>
        <div>
            <div class="fw-semibold">{{ now()->locale('id')->isoFormat('dddd') }}</div>
            <div class="date-info">{{ now()->locale('id')->isoFormat('MMMM YYYY') }}</div>
        </div>
    </div>
    <div class="text-right">
        <div class="greeting">Selamat {{ now()->hour < 12 ? 'Pagi' : (now()->hour < 15 ? 'Siang' : (now()->hour < 18 ? 'Sore' : 'Malam')) }}, {{ auth()->user()->name }}!</div>
        <div class="date-info"><i class="fas fa-clock"></i> {{ now()->format('H:i') }} WIB</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions animate-fadeIn">
    @if(auth()->user()->hasRole(['admin', 'bph', 'kabinet']))
    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Task
    </a>
    @endif
    <a href="{{ route('timelines.calendar') }}" class="btn btn-outline-primary">
        <i class="fas fa-calendar"></i> Kalender
    </a>
    <a href="{{ route('drives.index') }}" class="btn btn-outline-primary">
        <i class="fab fa-google-drive"></i> Drive
    </a>
    <a href="{{ route('links.index') }}" class="btn btn-outline-primary">
        <i class="fas fa-external-link-alt"></i> Links
    </a>
</div>

<!-- Stats -->
<div class="row mb-4">
    @if($stats['totalUsers'])
    <div class="col-6 col-lg-3 mb-3">
        <div class="stat-mini animate-fadeIn">
            <div class="stat-icon primary"><i class="fas fa-users"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['totalUsers']) }}</div>
                <div class="stat-label">Anggota</div>
            </div>
        </div>
    </div>
    @endif
    
    <div class="col-6 col-lg-3 mb-3">
        <div class="stat-mini animate-fadeIn">
            <div class="stat-icon info"><i class="fas fa-project-diagram"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['totalPrograms']) }}</div>
                <div class="stat-label">Proker</div>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-lg-3 mb-3">
        <div class="stat-mini animate-fadeIn">
            <div class="stat-icon success"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['completedTasks']) }}</div>
                <div class="stat-label">Task Selesai</div>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-lg-3 mb-3">
        <div class="stat-mini animate-fadeIn">
            <div class="stat-icon {{ $stats['overdueTasks'] > 0 ? 'danger' : 'warning' }}"><i class="fas fa-clock"></i></div>
            <div>
                <div class="stat-value">{{ number_format($stats['pendingTasks']) }}</div>
                <div class="stat-label">Pending @if($stats['overdueTasks'] > 0)<span class="text-danger">({{ $stats['overdueTasks'] }} late)</span>@endif</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column -->
    <div class="col-12 col-lg-8 mb-4">
        <div class="row">
            <!-- Task Progress -->
            <div class="col-12 col-md-5 mb-3">
                <div class="card animate-fadeIn h-100">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="fas fa-chart-pie text-primary"></i> Progress Task</h6>
                    </div>
                    <div class="card-body d-flex align-center justify-center gap-4">
                        <div class="progress-circle">
                            <canvas id="taskChart" width="140" height="140"></canvas>
                        </div>
                        <div class="progress-legend">
                            <div class="progress-legend-item">
                                <span class="dot" style="background: #9CA3AF;"></span>
                                <span>Todo ({{ $tasksByStatus['todo'] }})</span>
                            </div>
                            <div class="progress-legend-item">
                                <span class="dot" style="background: #F59E0B;"></span>
                                <span>In Progress ({{ $tasksByStatus['in_progress'] }})</span>
                            </div>
                            <div class="progress-legend-item">
                                <span class="dot" style="background: #10B981;"></span>
                                <span>Done ({{ $tasksByStatus['done'] }})</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Timeline -->
            <div class="col-12 col-md-7 mb-3">
                <div class="card animate-fadeIn h-100">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="fas fa-calendar text-primary"></i> Timeline</h6>
                        <a href="{{ route('timelines.calendar') }}" class="fs-sm text-primary">Lihat →</a>
                    </div>
                    <div class="card-body">
                        @forelse($upcomingTimelines->take(4) as $timeline)
                        <div class="d-flex align-center gap-3 {{ !$loop->last ? 'mb-3 pb-3' : '' }}" style="{{ !$loop->last ? 'border-bottom: 1px solid var(--border-color);' : '' }}">
                            <span style="width: 12px; height: 12px; border-radius: 50%; background: {{ $timeline->color ?? '#7C3AED' }}; flex-shrink: 0;"></span>
                            <div class="flex-1">
                                <div class="fw-semibold">{{ $timeline->title }}</div>
                                <div class="text-muted fs-xs">{{ $timeline->start_date->format('d M Y') }}</div>
                            </div>
                        </div>
                        @empty
                        <div class="text-muted text-center py-4">Tidak ada timeline mendatang</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Tasks -->
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="fas fa-tasks text-primary"></i> Task Terbaru</h6>
                <a href="{{ route('tasks.index') }}" class="fs-sm text-primary">Semua →</a>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Deadline</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTasks->take(5) as $task)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ Str::limit($task->title, 30) }}</div>
                                    <div class="text-muted fs-xs">{{ $task->program->name ?? '-' }}</div>
                                </td>
                                <td><span class="badge badge-{{ $task->status_badge }}">{{ ucfirst($task->status) }}</span></td>
                                <td>
                                    <div class="d-flex align-center gap-2">
                                        <div class="progress" style="width: 60px; height: 6px;">
                                            <div class="progress-bar {{ $task->progress >= 100 ? 'success' : '' }}" style="width: {{ $task->progress }}%;"></div>
                                        </div>
                                        <span class="fs-xs text-muted">{{ $task->progress }}%</span>
                                    </div>
                                </td>
                                <td class="{{ $task->is_overdue ? 'text-danger fw-semibold' : 'text-muted' }} fs-sm">
                                    {{ $task->deadline?->format('d M') ?? '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">Tidak ada task</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Column -->
    <div class="col-12 col-lg-4">
        @if(isset($staffRanking) && $staffRanking->count() > 0)
        <div class="card animate-fadeIn mb-3">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="fas fa-trophy text-warning"></i> Top Staff</h6>
            </div>
            <div class="card-body">
                @foreach($staffRanking->take(5) as $index => $staff)
                <div class="d-flex align-center gap-3 {{ !$loop->last ? 'mb-3 pb-3' : '' }}" style="{{ !$loop->last ? 'border-bottom: 1px solid var(--border-color);' : '' }}">
                    <span class="fw-bold fs-lg {{ $index === 0 ? 'text-warning' : ($index === 1 ? 'text-secondary' : ($index === 2 ? 'text-danger' : 'text-muted')) }}" style="width: 24px;">{{ $index + 1 }}</span>
                    <img src="{{ $staff->avatar_url }}" alt="" class="avatar-sm">
                    <div class="flex-1">
                        <div class="fw-semibold">{{ $staff->name }}</div>
                        <div class="text-muted fs-xs">{{ $staff->department?->name ?? 'No Dept' }}</div>
                    </div>
                    <div class="fw-bold text-primary">{{ number_format(($staff->evaluations_avg_total_score ?? 0) / 4, 1) }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- My Programs -->
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h6 class="card-title mb-0"><i class="fas fa-folder-open text-info"></i> Proker Saya</h6>
                <a href="{{ route('programs.my') }}" class="fs-sm text-primary">Semua →</a>
            </div>
            <div class="card-body">
                @php
                    $myPrograms = \App\Models\Program::forUser(auth()->id())->with('department')->take(3)->get();
                @endphp
                @forelse($myPrograms as $program)
                <div class="d-flex align-center gap-3 {{ !$loop->last ? 'mb-3 pb-3' : '' }}" style="{{ !$loop->last ? 'border-bottom: 1px solid var(--border-color);' : '' }}">
                    <div class="flex-1">
                        <div class="fw-semibold">{{ Str::limit($program->name, 25) }}</div>
                        <div class="text-muted fs-xs">{{ $program->department?->name ?? '-' }}</div>
                    </div>
                    <div class="d-flex align-center gap-2">
                        <div class="progress" style="width: 40px; height: 6px;">
                            <div class="progress-bar" style="width: {{ $program->progress }}%;"></div>
                        </div>
                        <span class="fs-xs text-muted">{{ $program->progress }}%</span>
                    </div>
                </div>
                @empty
                <div class="text-muted text-center py-3">Belum ada proker</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('taskChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Todo', 'In Progress', 'Done'],
                datasets: [{
                    data: [{{ $tasksByStatus['todo'] }}, {{ $tasksByStatus['in_progress'] }}, {{ $tasksByStatus['done'] }}],
                    backgroundColor: ['#9CA3AF', '#F59E0B', '#10B981'],
                    borderWidth: 0,
                    spacing: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });
    }
});
</script>
@endpush
