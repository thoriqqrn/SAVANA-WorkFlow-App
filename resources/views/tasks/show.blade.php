@extends('layouts.app')

@section('title', 'Detail Task')
@section('page-title', $task->title)

@section('content')
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clipboard-check text-primary"></i>
                    Detail Task
                </h3>
                <div class="d-flex gap-2">
                    <span class="badge badge-{{ $task->status_badge }}">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                    <span class="badge badge-{{ $task->priority_badge }}">
                        {{ ucfirst($task->priority) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <h4 class="mb-3">{{ $task->title }}</h4>
                
                @if($task->description)
                <p class="text-muted">{{ $task->description }}</p>
                @endif
                
                <hr class="my-3">
                
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="text-muted fs-sm">Program</label>
                            <div class="fw-semibold">
                                <a href="{{ route('programs.show', $task->program) }}">{{ $task->program->name ?? '-' }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="text-muted fs-sm">Departemen</label>
                            <div class="fw-semibold">{{ $task->program->department->name ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="text-muted fs-sm">Deadline</label>
                            <div class="fw-semibold {{ $task->is_overdue ? 'text-danger' : '' }}">
                                {{ $task->deadline?->format('d M Y') ?? '-' }}
                                @if($task->is_overdue)
                                <span class="badge badge-danger">Overdue</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="text-muted fs-sm">Dibuat oleh</label>
                            <div class="d-flex align-center gap-2">
                                @if($task->creator)
                                <img src="{{ $task->creator->avatar_url }}" alt="{{ $task->creator->name }}" class="avatar-sm">
                                <span class="fw-semibold">{{ $task->creator->name }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr class="my-3">
                
                <!-- Progress Update -->
                <div class="mb-4">
                    <label class="form-label">Progress: {{ $task->progress }}%</label>
                    <div class="progress mb-3" style="height: 12px;">
                        <div class="progress-bar {{ $task->progress >= 100 ? 'success' : '' }}" style="width: {{ $task->progress }}%;"></div>
                    </div>
                    
                    @if(auth()->user()->id === $task->assigned_to || auth()->user()->hasRole(['admin', 'bph', 'kabinet']))
                    <form action="{{ route('tasks.progress', $task) }}" method="POST" class="d-flex align-center gap-3">
                        @csrf
                        @method('PATCH')
                        <input type="range" name="progress" class="flex-1" min="0" max="100" step="5" value="{{ $task->progress }}" id="progressSlider">
                        <span id="progressValue" class="fw-bold" style="min-width: 40px;">{{ $task->progress }}%</span>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save"></i> Update
                        </button>
                    </form>
                    @endif
                </div>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-lg-4">
        <!-- Assignee Card -->
        <div class="card animate-fadeIn mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user text-primary"></i>
                    Ditugaskan Kepada
                </h3>
            </div>
            <div class="card-body">
                @if($task->assignee)
                <div class="text-center">
                    <img src="{{ $task->assignee->avatar_url }}" alt="{{ $task->assignee->name }}" class="avatar-lg mb-3">
                    <h5 class="mb-1">{{ $task->assignee->name }}</h5>
                    <p class="text-muted fs-sm mb-2">{{ $task->assignee->email }}</p>
                    <span class="badge badge-{{ $task->assignee->role?->name === 'kabinet' ? 'info' : 'secondary' }}">
                        {{ ucfirst($task->assignee->role?->name ?? '-') }}
                    </span>
                </div>
                @else
                <div class="empty-state" style="padding: 1rem;">
                    <div class="empty-state-icon" style="width: 48px; height: 48px;">
                        <i class="fas fa-user-slash"></i>
                    </div>
                    <p class="text-muted fs-sm mb-0">Belum ada assignee</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Info Card -->
        <div class="card animate-fadeIn">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle text-primary"></i>
                    Info
                </h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-between mb-2">
                    <span class="text-muted">Dibuat</span>
                    <span class="fw-semibold">{{ $task->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="d-flex justify-between mb-2">
                    <span class="text-muted">Diupdate</span>
                    <span class="fw-semibold">{{ $task->updated_at->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('progressSlider')?.addEventListener('input', function() {
    document.getElementById('progressValue').textContent = this.value + '%';
});
</script>
@endpush
