@extends('layouts.app')

@section('title', 'Detail User')
@section('page-title', 'Detail User')

@section('content')
<div class="row">
    <div class="col-12 col-lg-4">
        <div class="card animate-fadeIn">
            <div class="card-body text-center">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="avatar-xl mb-3">
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-2">{{ $user->email }}</p>
                <span class="badge badge-{{ $user->role?->name === 'admin' ? 'danger' : ($user->role?->name === 'bph' ? 'warning' : ($user->role?->name === 'kabinet' ? 'info' : 'secondary')) }}">
                    {{ ucfirst($user->role?->name ?? 'No Role') }}
                </span>
                <span class="badge badge-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                    {{ ucfirst($user->status) }}
                </span>
                
                <hr class="my-3">
                
                <div class="text-left">
                    <div class="d-flex justify-between mb-2">
                        <span class="text-muted">Departemen</span>
                        <span class="fw-semibold">{{ $user->department?->name ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-between mb-2">
                        <span class="text-muted">Bergabung</span>
                        <span class="fw-semibold">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                </div>
                
                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary flex-1">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary flex-1">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-lg-8">
        <!-- Task Stats -->
        <div class="row mb-4">
            <div class="col-4">
                <div class="card stat-card animate-fadeIn">
                    <div class="stat-icon info">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $user->task_stats['total'] }}</div>
                        <div class="stat-label">Total Task</div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card stat-card animate-fadeIn">
                    <div class="stat-icon warning">
                        <i class="fas fa-spinner"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $user->task_stats['in_progress'] }}</div>
                        <div class="stat-label">In Progress</div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card stat-card animate-fadeIn">
                    <div class="stat-icon success">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $user->task_stats['done'] }}</div>
                        <div class="stat-label">Selesai</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Tasks -->
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tasks text-primary"></i>
                    Task Terbaru
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Program</th>
                                <th>Status</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->tasks->take(5) as $task)
                            <tr>
                                <td class="fw-semibold">{{ $task->title }}</td>
                                <td class="fs-sm">{{ $task->program->name ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ $task->status_badge }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-center gap-2">
                                        <div class="progress" style="flex: 1; height: 6px;">
                                            <div class="progress-bar" style="width: {{ $task->progress }}%;"></div>
                                        </div>
                                        <span class="fs-xs text-muted">{{ $task->progress }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Tidak ada task
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Evaluations -->
        @if($user->evaluations->count() > 0)
        <div class="card animate-fadeIn mt-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-star text-warning"></i>
                    Riwayat Evaluasi
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th>Disiplin</th>
                                <th>Tanggung Jawab</th>
                                <th>Teamwork</th>
                                <th>Inisiatif</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->evaluations->take(5) as $eval)
                            <tr>
                                <td class="fw-semibold">{{ $eval->period ?? '-' }}</td>
                                <td>{{ $eval->discipline }}</td>
                                <td>{{ $eval->responsibility }}</td>
                                <td>{{ $eval->teamwork }}</td>
                                <td>{{ $eval->initiative }}</td>
                                <td>
                                    <span class="badge badge-{{ $eval->average_score >= 80 ? 'success' : ($eval->average_score >= 60 ? 'warning' : 'danger') }}">
                                        {{ number_format($eval->average_score, 1) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
