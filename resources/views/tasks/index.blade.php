@extends('layouts.app')

@section('title', 'Task Saya')
@section('page-title', 'Task Saya')

@section('content')
<div class="row mb-4">
    <div class="col-12 col-md-4">
        <div class="card stat-card">
            <div class="stat-icon secondary">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $tasks->where('status', 'todo')->count() }}</div>
                <div class="stat-label">Todo</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-spinner"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $tasks->where('status', 'in_progress')->count() }}</div>
                <div class="stat-label">In Progress</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $tasks->where('status', 'done')->count() }}</div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>
    </div>
</div>

<div class="card animate-fadeIn">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-tasks text-primary"></i>
            Daftar Task
        </h3>
        @if(auth()->user()->hasRole(['admin', 'bph', 'kabinet']))
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Task
        </a>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Program</th>
                        @if(!auth()->user()->isStaff())
                        <th>Assigned To</th>
                        @endif
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Progress</th>
                        <th>Deadline</th>
                        <th class="no-sort" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                    <tr>
                        <td class="fw-semibold">{{ $task->title }}</td>
                        <td class="fs-sm">{{ $task->program->name ?? '-' }}</td>
                        @if(!auth()->user()->isStaff())
                        <td>
                            @if($task->assignee)
                            <div class="d-flex align-center gap-2">
                                <img src="{{ $task->assignee->avatar_url }}" alt="{{ $task->assignee->name }}" class="avatar-sm">
                                <span class="fs-sm">{{ $task->assignee->name }}</span>
                            </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        @endif
                        <td>
                            <span class="badge badge-{{ $task->status_badge }}">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $task->priority_badge }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </td>
                        <td style="min-width: 120px;">
                            <div class="d-flex align-center gap-2">
                                <div class="progress" style="flex: 1; height: 6px;">
                                    <div class="progress-bar {{ $task->progress >= 100 ? 'success' : '' }}" style="width: {{ $task->progress }}%;"></div>
                                </div>
                                <span class="fs-xs text-muted" style="width: 35px;">{{ $task->progress }}%</span>
                            </div>
                        </td>
                        <td class="fs-sm {{ $task->is_overdue ? 'text-danger fw-semibold' : '' }}">
                            {{ $task->deadline?->format('d M Y') ?? '-' }}
                            @if($task->is_overdue)
                            <i class="fas fa-exclamation-circle"></i>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-secondary btn-icon" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user()->hasRole(['admin', 'bph', 'kabinet']))
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-icon" data-confirm-delete="{{ $task->title }}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
