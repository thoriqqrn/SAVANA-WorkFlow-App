@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan & Statistik')

@section('content')
<!-- Stats Overview -->
<div class="row mb-4">
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card stat-card animate-fadeIn">
            <div class="stat-icon primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['totalUsers'] }}</div>
                <div class="stat-label">Total Anggota</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card stat-card animate-fadeIn">
            <div class="stat-icon info">
                <i class="fas fa-project-diagram"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['totalPrograms'] }}</div>
                <div class="stat-label">Total Proker</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card stat-card animate-fadeIn">
            <div class="stat-icon warning">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['totalTasks'] }}</div>
                <div class="stat-label">Total Task</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card stat-card animate-fadeIn">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['completedTasks'] }}</div>
                <div class="stat-label">Task Selesai</div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Task Distribution -->
    <div class="col-12 col-lg-6">
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie text-primary"></i>
                    Distribusi Task
                </h3>
            </div>
            <div class="card-body">
                <canvas id="taskChart" height="250"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Program Status -->
    <div class="col-12 col-lg-6">
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar text-primary"></i>
                    Status Program
                </h3>
            </div>
            <div class="card-body">
                <canvas id="programChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Department Stats -->
    <div class="col-12 col-lg-8">
        <div class="card animate-fadeIn mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-building text-primary"></i>
                    Statistik per Departemen
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Departemen</th>
                                <th>Anggota</th>
                                <th>Proker</th>
                                <th>Task Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departments as $department)
                            @php
                                $deptTasks = \App\Models\Task::whereHas('program', fn($q) => $q->where('department_id', $department->id));
                                $completedDeptTasks = (clone $deptTasks)->where('status', 'done')->count();
                                $totalDeptTasks = $deptTasks->count();
                            @endphp
                            <tr>
                                <td class="fw-semibold">{{ $department->name }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $department->users_count }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-primary">{{ $department->programs_count }}</span>
                                </td>
                                <td>
                                    @if($totalDeptTasks > 0)
                                    <div class="d-flex align-center gap-2">
                                        <div class="progress" style="flex: 1; max-width: 100px;">
                                            <div class="progress-bar success" style="width: {{ ($completedDeptTasks / $totalDeptTasks) * 100 }}%;"></div>
                                        </div>
                                        <span class="fs-sm">{{ $completedDeptTasks }}/{{ $totalDeptTasks }}</span>
                                    </div>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Staff -->
    <div class="col-12 col-lg-4">
        <div class="card animate-fadeIn mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-trophy text-warning"></i>
                    Top 10 Staff
                </h3>
            </div>
            <div class="card-body">
                @forelse($topStaff as $index => $staff)
                <div class="d-flex align-center gap-3 {{ !$loop->last ? 'mb-3 pb-3' : '' }}" style="{{ !$loop->last ? 'border-bottom: 1px solid var(--border-color);' : '' }}">
                    <div class="fw-bold {{ $index < 3 ? 'text-warning' : '' }}" style="width: 20px;">{{ $index + 1 }}</div>
                    <img src="{{ $staff->avatar_url }}" alt="{{ $staff->name }}" class="avatar-sm">
                    <div class="flex-1">
                        <div class="fw-semibold fs-sm">{{ $staff->name }}</div>
                        <div class="text-muted fs-xs">{{ $staff->department?->name ?? '-' }}</div>
                    </div>
                    <span class="badge badge-success">{{ number_format($staff->evaluations_avg_total_score / 4, 1) }}</span>
                </div>
                @empty
                <p class="text-muted text-center mb-0">Belum ada data evaluasi</p>
                @endforelse
            </div>
        </div>
        
        <!-- Export -->
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-download text-primary"></i>
                    Export
                </h3>
            </div>
            <div class="card-body">
                <a href="{{ route('reports.export', 'pdf') }}" class="btn btn-outline-danger w-100 mb-2">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('reports.export', 'excel') }}" class="btn btn-outline-success w-100">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Task Distribution Chart
    const taskCtx = document.getElementById('taskChart');
    if (taskCtx) {
        new Chart(taskCtx, {
            type: 'doughnut',
            data: {
                labels: ['Todo', 'In Progress', 'Selesai'],
                datasets: [{
                    data: [{{ $tasksByStatus['todo'] }}, {{ $tasksByStatus['in_progress'] }}, {{ $tasksByStatus['done'] }}],
                    backgroundColor: ['#9CA3AF', '#F59E0B', '#10B981'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    
    // Program Status Chart
    const programCtx = document.getElementById('programChart');
    if (programCtx) {
        new Chart(programCtx, {
            type: 'bar',
            data: {
                labels: ['Planning', 'Active', 'Completed', 'Cancelled'],
                datasets: [{
                    label: 'Jumlah Program',
                    data: [{{ $programsByStatus['planning'] }}, {{ $programsByStatus['active'] }}, {{ $programsByStatus['completed'] }}, {{ $programsByStatus['cancelled'] }}],
                    backgroundColor: ['#9CA3AF', '#F59E0B', '#10B981', '#EF4444']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
